<?php
  error_reporting(E_ALL & ~(E_NOTICE | E_DEPRECATED | E_WARNING));
  ini_set('display_errors', 0);

  require_once('../config/cfg.php');

  s_start(); // session_start

  if (!isset($_SESSION['spammer']))
    $_SESSION['spammer'] = 0;

  $sleep_time   = 5;
  $xhr          = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
  $lng          = getLangInitial();
  $ref          = $_SERVER['HTTP_REFERER'];
  $req          = intval($_POST['formId']);
  $admin_name   = 'Risorse umane';
  $mandatory    = array();
  $labels       = array();
  $mailpattern  = '/^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,6}$/';
  $allowedTypes = array( //file ammessi per l'upload
    'application/msword'
  , 'application/pdf'
  , 'application/vnd.ms-powerpoint'
  , 'application/x-mspublisher'
  , 'application/vnd.ms-excel'
  , 'application/vnd.ms-powerpoint'
  , 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
  , 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
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

  //print_debug($_POST);

  /* ============================================================================
   * - recupero dal db i dati del form
   * ============================================================================ */
  $qr1 = <<<EOFSQL
     SELECT f.id, f.destinatario, f.privacy, f.captcha,
            t1.{$lng} AS titolo, t2.{$lng} AS descrizione, t3.{$lng} AS risposta
       FROM forms f
  LEFT JOIN aa_translation t1 ON f.titolo = t1.id
  LEFT JOIN aa_translation t2 ON f.descrizione = t2.id
  LEFT JOIN aa_translation t3 ON f.risposta = t3.id
      WHERE f.id = '{$req}'
EOFSQL;

  $re1 = $db->execute($qr1);
  if ($ar1 = $re1->fetchRow()) {
    extract($ar1, EXTR_PREFIX_ALL, 'form');
    //$form_response = false;

  } else {
    // form non trovato, sparo un errore 500 e tutti a casa
    header('X-Error-Message: Incorrect form ID', true, 500);
    die('Incorrect form ID');
  }

  // - recupero l'elenco dei campi
  $qr2 = <<<EOSQL
     SELECT f.titolo, f.tipo, f.formato, f.obbligatorio,
            t1.{$lng} AS label
       FROM form_fields f
  LEFT JOIN aa_translation t1 ON f.label = t1.id
      WHERE f.id_form = '{$form_id}'

EOSQL;
  //AND f.obbligatorio = 1
  $re2 = $db->execute($qr2);
  while ($ar2 = $re2->fetchRow()) {
    $labels[$ar2['titolo']] = $ar2['label'];
    if (1 == $ar2['obbligatorio'])
      $mandatory[$ar2['titolo']] = ($ar2['tipo'] == 'file') ? 'file' : $ar2['formato'];
  }

  if($_POST['action']=='submit') {
    $error = 0;
    $_SESSION['form'.$form_id]['error'] = array();

// - ciclo il $_POST per cercare dati mancanti
    foreach ($mandatory as $k => $v) {
      if ($v == 'file') {
        if (empty($_FILES[$k])) {
          $error ++;
          $_SESSION['form'.$form_id]['error'][$k] = 'empty';
        } elseif ( !in_array( $_FILES[$k]['type'], $allowedTypes)) {
          $error ++;
          $_SESSION['form'.$form_id]['error'][$k] = 'File type not admitted!';
        }
      } else {
        if (empty($_POST[$k])) {
          $error ++;
          $_SESSION['form'.$form_id]['error'][$k] = 'empty';
        }
        if ($v == 'email' && !preg_match($mailpattern, $_POST[$k])) {
          $error ++;
          $_SESSION['form'.$form_id]['error'][$k] = 'empty';
        }
      }
      if ($form_privacy==1 && $_POST['privacy'] != 1) {
        $error ++;
        $_SESSION['form'.$form_id]['error']['privacy'] = 'empty';

      }
      //if($form_captcha!='nessuno' && strtolower($_POST['captcha'])!=strtolower($_SESSION['security_code'])){
      $spammer = false;
      if ($form_captcha != 'nessuno') {
        if ($form_captcha == 'honeypot') {
          if (!empty($_POST['twitter_account']))
            $spammer = true;
        } else {
          if (!isset($_SESSION['security_code'])) {
            $spammer = true;

          } else {
            if ( empty($_POST['captcha'])
              || strtolower($_POST['captcha']) != strtolower($_SESSION['security_code'])
              ){
              $error ++;
              $_SESSION['form'.$form_id]['error']['captcha'] = 'empty';
            }
          }
        }
      }

      if ($spammer) {
        $error ++;
        $_SESSION['spammer'] += 10;
        $_SESSION['form'.$form_id]['error'] = 'Spam protection';
        sleep ($sleep_time * $_SESSION['spammer']);
        //header('Location: /index.php');
        header('HTTP/1.1 401 Unauthorized', true, 401);
        die('Unauthorized');
      }
    }

    if ($error > 0){
  // - errore, ripresento il form
      $_SESSION['form'.$form_id]['data'] = serialize($_POST);

    } else {
  // - tutto ok
      unset($_SESSION['form'.$form_id]['error']);

      if($form_destinatario){
      /* ========================================================================
       * - mail all'admin
       * ======================================================================== */
        // elimino i campi irrilevanti
        unset(
          $_POST['MAX_FILE_SIZE'],
          $_POST['captcha'],
          $_POST['privacy'],
          $_POST['action'],
          $_POST['formId'],
          $_POST['twitter_account']);

        $rows = '';
        foreach ($_POST as $k => $v) {
          $$k = addslashes(strip_tags($v));
          if (!empty($v)) {
            $rows .= "<tr>\n";
            $rows .= "  <th width='15%' valign='top'>{$labels[$k]}</th>\n";
            if (is_array($v)) {
              $rows .= "  <td>".implode(', ', $v)."</td>\n";
            } else {
              $rows .= "  <td>".nl2br(utf8_decode($v))."</td>\n";
            }
            $rows .= "</tr>\n";
          }
        }

        // lowercase the keys and extract to variables
        extract( array_change_key_case($_POST, CASE_LOWER) );

        $sender = array(
          'name' => ($nome || $cognome) ? "$nome $cognome" : $synWebsiteTitle,
          'mail' => ($email) ? $email : $form_destinatario
          );
        $to = array(
          'name' => $synWebsiteTitle,
          'mail' => $form_destinatario
          );
        $subject = $form_titolo;
        $data = array(
          'site_name' => $synWebsiteTitle,
          'form_name' => $form_titolo,
          'rows' => $rows,
          'time' => date('d/m/Y \a\l\l\e H:i')
          );

        $template = "email/mail_from_form{$form_id}.html"; // template specifico
        if ( !is_file($synAbsolutePath.$synPublicPath.'templates/'.$template) )
          $template = 'email/mail_from_form.html'; // template generico

        try {
          // - istanzio oggetto
          $mail = new synMailer($sender, $to, $subject);
          // - imposto template
          $mail->setTemplate($template, $data);
          if (!empty($_FILES)) {
            // allegati della mail
            foreach ($_FILES as $file) {
              if (in_array($file['type'], $allowedTypes)) {
                /*if (strpos($file['type'], 'image') !== false) {
                  if ($file['size'] < $max_file_size) {
                    // immagine troppo grande
                  }
                }*/
                // - aggiungo allegato
                $mail->AddAttachment( $file['tmp_name'], $file['name'] );
              } else {
                throw new Exception('File type ' . $file['type'] . ' is not admitted!', 1);
              }
            }
          }
          // - invio la mail
          $mail->go();

        } catch(Exception $e) {
          $_SESSION['form'.$form_id]['error'] = $e->getMessage();
        }

      } //if($form_destinatario)


      /*
      if (1 == $form_response && !empty($email)) {
         // mail all'utente
        $sender = array(
          'name' => $synWebsiteTitle,
          'mail' => ADMIN_MAIL
          );
        $to = array(
          'name' => ($nome || $cognome) ? "$nome $cognome" : $email,
          'mail' => $email
          );
        $subject = $form_titolo;
        $template = "email/response_from_form{$form_id}.html";
        $data = array(
          'site_name' => $synWebsiteTitle
          );

        try {
          $mail = new synMailer($sender, $to, $subject);
          $mail->setTemplate($template, $data);
          $mail->go();

        } catch(Exception $e) {
          $_SESSION['form'.$form_id]['error'] = $e->getMessage();
        }
      } */



      /* ========================================================================
       * - salvataggio su db
       * ======================================================================== */
      // allegati come join???
      $hash = addslashes(json_encode($_POST));
      $insert = <<<EOINS
      INSERT INTO dati_inviati (
        id_form,
        hash,
        timestamp
      ) VALUES (
        '{$form_id}',
        '{$hash}',
         NOW()
      )
EOINS;
      try {
        $db->execute($insert);
        // TODO: salvare gli allegati!
        /*if (!empty($_FILES)) { // allegati della mail
          foreach ($_FILES as $file) {
            if (in_array($file['type'], $allowedTypes)) {
              //_uploadDocument($id, $file, 'file_inviati', $synPublicPath.'mat/sent/')
            }
          }
        }*/
      } catch(Exception $e) {
        $_SESSION['form'.$form_id]['error'] = $e->getMessage();
      }

      $_SESSION['form'.$form_id]['submitted'] = true;
    } // if $error>0
  } // if _POST[action]

  /* ============================================================================
   * - fine, rimando al referer
   * ============================================================================ */

  if ($xhr) {
    if ($error > 0) {
      // errore
      $form_obj = new formBuilder($form_id);
      $status = 'error';
      $message = $form_obj->errorMsg( $_SESSION['form'.$form_id]['error'] );

    } else {
      // tutto ok
      $status = 'ok';
      $message = $form_risposta;
      unset($_SESSION['form'.$form_id]);
    }
    $ret = array('status' => $status, 'message' => $message);
    header('Content-type: application/json');
    echo json_encode($ret);

  } else {
    //echo "<a href=\"{$ref}\">OK &rarr; {$ref}</a>";
    header('P3P: CP="NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM"'); // privacy policy per IE6
    header("location: ".$ref);
  }



if (!function_exists(_uploadDocument)) {
  function _uploadDocument($id, $f, $stub, $mat) {
    if (!$mat)
      return;

    $name  = $f["name"];
    $bits  = explode(".", $name);
    $ext   = end($bits);
    $fname = $stub.'_id'.$id.'.'.$ext;
    $file  = $f['tmp_name'];
    $max_size = 1024 * 200; // 200Kb

    if ( $file != 'none'
      && $name != ''
      && $file != ''
      ){
      if ( strpos($f['type'], 'image') !== false
        && $f['size'] < $max_size
        ){
        $file = ''; // TODO: ridimensionare l'immagine
      }
      move_uploaded_file($file, getenv('DOCUMENT_ROOT').$mat.$fname);
      @chmod($mat.$fname, 0777);
      return $ext;
    }
  }
}
?>
