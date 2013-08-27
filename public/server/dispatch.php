<?php
  require_once("../config/cfg.php");
  global $db, $synRootPasswordSalt, $synWebsiteTitle;
  session_start();

  $lng          = $_SESSION['synSiteLangInitial'];
  $ref          = $_SERVER["HTTP_REFERER"];
  $req          = intval($_POST['formId']);
  $admin_name   = 'Webmaster';
  $fields       = array();
  $mailpattern  = '/^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,6}$/';
  $allowedTypes = array( //file ammessi per l'upload
    'application/msword'
  , 'application/pdf'
  , 'application/vnd.ms-powerpoint'
  , 'application/x-mspublisher'
  , 'image/gif'
  , 'image/x-xbitmap'
  , 'image/png'
  , 'application/png'
  , 'application/x-png'
  , 'image/jpg'
  , 'image/jpeg'
  , 'image/pjpeg'
  , 'image/jpe_'
  , 'image/jp_'
  , 'image/pipeg'
  , 'image/vnd.swiftview-jpeg'
  , 'application/jpg'
  , 'application/x-jpg'
  , 'image/tiff'
  , 'image/bmp'
  );
#echo '<pre>', print_r($_POST), '</pre>';
  # ============================================================================
  # - recupero dal db i dati del form
  # ============================================================================
  $qr1 = <<<EOFSQL
     SELECT f.id, f.destinatario, f.privacy, f.captcha,
            t1.$lng AS titolo, t2.$lng AS descrizione
       FROM forms f
  LEFT JOIN aa_translation t1 ON f.titolo=t1.id
  LEFT JOIN aa_translation t2 ON f.descrizione=t2.id
      WHERE f.id = $req
EOFSQL;
// f.save_to, s.syntable,
// LEFT JOIN aa_services s ON f.save_to = s.id

  $re1 = $db->execute($qr1);
  if($ar1 = $re1->fetchRow()){
    $form_id = $ar1['id'];
    $form_titolo = $ar1['titolo'];
    $form_descrizione = $ar1['descrizione'];
    $form_destinatario = $ar1['destinatario'];
    $form_privacy = $ar1['privacy'];
    $form_captcha = $ar1['captcha'];
    #$form_save_to = $ar1['save_to'];
    #$form_syntable = $ar1['syntable'];
  }

  # - recupero l'elenco dei campi obbligatori
  $qr2 = <<<EOSQL
     SELECT f.titolo, f.formato, f.tipo
       FROM form_fields f
      WHERE f.id_form = $form_id
        AND f.obbligatorio=1
EOSQL;
  $re2 = $db->execute($qr2);
  while($ar2 = $re2->fetchRow()){
    $fields[$ar2['titolo']] = ($ar2['tipo']=='file' ? $ar2['tipo'] : $ar2['formato']);
  }

  if($_POST['action']=='submit') {
    $error = 0;
    $_SESSION['form'.$form_id]['error'] = array();

# - ciclo $_POST e $_FILES per cercare dati mancanti
    foreach($fields as $k=>$v){
      if ( ($v!='file' && $_POST[$k]=='')
        || ($v=='file' && (!isset($_FILES[$k]) || $_FILES[$k]['error']>0))
        || ($v=='email' && !preg_match($mailpattern, $_POST[$k]))
        ){
        $error ++;
        $_SESSION['form'.$form_id]['error'][$k] = 'empty';
      }
      if ( $form_privacy==1
        && $_POST['privacy']!=1
        ){
        $error ++;
        $_SESSION['form'.$form_id]['error']['privacy'] = 'empty';
      }
      if($form_captcha!='nessuno' && 
        (!isset($_POST['captcha']) || trim($_POST['captcha'])=="" || strtolower($_POST['captcha'])!=strtolower($_SESSION['security_code']))){
        $error ++;
        $_SESSION['form'.$form_id]['error']['captcha'] = 'empty';
      }
    }

    if($error>0){
  # - errore, ripresento il form
      $_SESSION['form'.$form_id]['data'] = serialize($_POST);

    } else {
  # - tutto ok
      unset($_SESSION['form'.$form_id]['error']);

      if($form_destinatario){
      # ========================================================================
      # - mail all'admin
      # ========================================================================
        $ora   = date('d/m/Y \a\l\l\e H:i');
        $table = "<table width=100% cellspacing=1 cellpadding=5 border=1>\n";

        # elimino i campi irrilevanti
        unset($_POST['MAX_FILE_SIZE'], $_POST['captcha'], $_POST['privacy'], $_POST['action'], $_POST['formId']);

        foreach($_POST as $k=>$v){
          $$k = addslashes(strip_tags($v));
          if($v!=''){
            $table .= "<tr>\n";
            $table .= "  <th width='15%'>{$k}</th>\n";
            if(is_array($v)){
              $table .= "  <td>".implode(', ',$v)."</td>\n";
            } else {
              $table .= "  <td>".nl2br(utf8_decode($v))."</td>\n";
            }
            $table .= "</tr>\n";
          }
        }
        $table.= "</table>\n";

        $body = <<<EOBODY
        <h3>Nuovo messaggio da {$synWebsiteTitle}</h3>
        <p>Un utente ha compilato il form "{$form_titolo}":</p>
        {$table}
        <hr>
        Spedito il $ora.
EOBODY;

        $mail = new PHPMailer(true);
        $mail->From     = ($email) ? $email : $form_destinatario;
        $mail->FromName = ($nome || $cognome) ? "$nome $cognome" : $synWebsiteTitle;
        $mail->CharSet  = 'UTF-8';
        $mail->Subject  = "Messaggio da {$synWebsiteTitle}";
        $mail->AltBody  = strip_tags($body);
        $mail->MsgHTML($body);
        $mail->AddAddress($form_destinatario, $admin_name);

        if(!empty($_FILES)){ // allegati della mail
          foreach($_FILES as $file){
            if (in_array($file['type'], $allowedTypes)) {
              $mail->AddAttachment($file['tmp_name'], $file['name']);
            }
          }
        }

    # - invio la mail
        try {
          $mail->Send();
          $_SESSION['form'.$form_id]['submitted'] = true;

        } catch (phpmailerException $e) {
          $_SESSION['form'.$form_id]['error'] = $e->errorMessage();
          //Pretty error messages from PHPMailer

        } catch (Exception $e) {
          $_SESSION['form'.$form_id]['error'] = $e->getMessage();
          //Boring error messages from anything else!
        }
      } //if($form_destinatario)

/*
      // disabilitato - POTENZIALE DISASTRO
      if($form_syntable){
      # ========================================================================
      # - salvataggio su db
      # ========================================================================

      # - controllo che esista la tabella
        // $check = $db->execute('SELECT 1 FROM `{$form_syntable}` LIMIT 0'); die($check.'##');
        $insfields = implode('`,`', array_keys($_POST));
        $insvalues = implode("','", array_values($_POST));
        $ins = "INSERT INTO {$form_syntable} (`{$insfields}`) VALUES ('{$insvalues}')";
        $db->execute($ins);
        $insert_id = $db->Insert_Id();

      # - ciclo sugli allegati
        if($insert_id && !empty($_FILES)){
          foreach($_FILES as $file=>$dati){
            if (in_array($dati['type'], $allowedTypes)) {
              // ricavo il path
              $qf = "SELECT path FROM aa_services_element WHERE container={$form_save_to} AND name='$file'";
              $rf = $db->execute($qf);
              if($af=$rf->fetchrow()){
                $path = $af['path'].'/';
                $fname = $form_syntable.'_'.$file;
                if($ret = _uploadDocument($insert_id, $dati, $fname, $path)){
                  $db->execute("UPDATE {$form_syntable} SET $file='$ret' WHERE id=$insert_id");
                }
              }
            }
          }
        }// if !empty($_FILES)
      }// if $form_syntable
*/
    }// if $error>0
  }// if _POST[action]

  # ============================================================================
  # - fine, rimando al referer
  # ============================================================================
  header('P3P: CP="NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM"'); // privacy policy per IE6
  header("location: ".$ref);



function _uploadDocument($id, $f, $stub, $mat) {
  if(!$mat) return;
  $name  = $f["name"];
  $bits  = explode(".", $name);
  $ext   = end($bits);
  $fname = $stub.'_id'.$id.".".$ext;
  $file  = $f["tmp_name"];

  if (
    $file != 'none' &&
    $name != '' &&
    $file != ''
  ) {
    move_uploaded_file($file, $_SERVER['DOCUMENT_ROOT'].$mat.$fname);
    @chmod($mat.$fname,0777);
    return $ext;
  }
}
?>
