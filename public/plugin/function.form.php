<?php

function smarty_function_form($params, &$smarty) {
  global $db, $synWebsiteTitle, $synPublicPath;

  if(!isset($_SESSION))
    session_start();

  $page     = intval($params['page']);
  $formid   = intval($params['id']);
  $lng      = $_SESSION['synSiteLangInitial'];
  $t        = multiTranslateDictionary(array(
            'informativa', 
            'informativa_privacy', 
            'checkfields', 
            'campo_obbligatorio', 
            'email_non_valida', 
            'verifica_valore', 
            'cancella', 
            'invia', 
            'codice_sicurezza'
            ));

  if (!empty($page))
    $form_var = getFormAttributesByPage($page, $lng); // vedi sotto
    
  elseif (!empty($formid))
    $form_var = getFormAttributesById($formid, $lng); // vedi sotto
    
  else
    return false;

  $form_id  = $form_var['id'];

  if (!$form_id)
    return false;  // nessun form trovato, esco

  $fieldset = getFormFieldset($form_id, $lng); // vedi sotto
  $fields   = getFormFields($form_id, $lng, $params); // vedi sotto
  $form     = new formBuilder($form_id);
  
  $session  = isset($_SESSION['form'.$form_id]) 
            ? $_SESSION['form'.$form_id] 
            : false;
  $html     = "<h2>{$form_var['titolo']}</h2>\n";

  if ( isset($session) 
    && $session['submitted']==true
    ){
    // - form sottomesso correttamente, presento il messaggio di conferma
    $html .= $form_var['risposta'];
    unset($_SESSION['form'.$form_id]);

  } else {
    // - errore/non ancora sottomesso, presento il form
    $html .= $form_var['descrizione'];
    if (isset($session['data'])) {
      // se presenti, imposto i dati gi? inseriti
      $dati = unserialize($session['data']);
      foreach($dati as $k=>$v){
        switch($fields[$k]['tipo']){
          case 'checkbox':
          case 'radio':
          case 'select':
            foreach($fields[$k]['opzioni'] as $f=>$s){
              $fields[$k]['opzioni'][$f]['selezionato'] = (in_array($f,$s)) ? 1 : 0;
            }
            break;
          case 'text':
          case 'textarea':
          case 'password':
          	$fields[$k]['value'] = $v;
            break;
        }
      }
    }

    $form->setAttributes(array( // configurazione
      'action'        => $synPublicPath.'/server/dispatch.php',
      'xhtml'         => false,
      'method'        => 'post',
      'class'         => 'synform',
      'resetLabel'    => $t['cancella'],
      'submitLabel'   => $t['invia'],
      'captcha'       => $form_var['captcha'],
      'captchaConfig' => array( 'width'=>270, 'height'=>50 ),
      'captchaLabel'  => $t['codice_sicurezza'],
      'privacy'       => nl2br($form_var['privacy']),
      'informativa'   => $t['informativa_privacy'],
      'approvazione'  => $t['informativa'],
      'checkfields'   => $t['checkfields'],
      'error1'        => $t['campo_obbligatorio'],
      'error2'        => $t['email_non_valida'],
      'error3'        => $t['verifica_valore'],
      'debug'         => false
      ));

    foreach($fieldset as $s => $l) {
    	$form->addFieldset($s, $l);
    }

    foreach($fields as $f) {
    	$form->addField($f['titolo'], $f['label'], $f['value'], $f['tipo'], $f['obbligatorio'], $f['formato'], $f['opzioni'], $f['fieldset']);
    }

    if (isset($session['error'])) {
	  	$form->errorMsg($session['error']);
    }

  	$html .= $form->render();
    unset($_SESSION['form'.$form_id]);
  }
  
  return $html;
}


// recupera dal db gli attributi del form
function getFormAttributesByPage($req, $lng='it'){
  global $db;
  $qr1 = <<<EOSQL
  
     SELECT f.id, f.destinatario, f.privacy, f.captcha,
            t1.{$lng} AS titolo, t2.{$lng} AS descrizione, t3.{$lng} AS risposta
       FROM forms f
  LEFT JOIN aa_translation t1 ON f.titolo=t1.id
  LEFT JOIN aa_translation t2 ON f.descrizione=t2.id
  LEFT JOIN aa_translation t3 ON f.risposta=t3.id
      WHERE f.pagina = '{$req}'
      LIMIT 0,1
      
EOSQL;
  $re1 = $db->execute($qr1);
  $ar1 = $re1->fetchRow();
  return $ar1;
}


function getFormAttributesById($req, $lng='it'){
  global $db;
  $qr1 = <<<EOSQL
  
     SELECT f.id, f.destinatario, f.privacy, f.captcha,
            t1.{$lng} AS titolo, t2.{$lng} AS descrizione, t3.{$lng} AS risposta
       FROM forms f
  LEFT JOIN aa_translation t1 ON f.titolo=t1.id
  LEFT JOIN aa_translation t2 ON f.descrizione=t2.id
  LEFT JOIN aa_translation t3 ON f.risposta=t3.id
      WHERE f.id = '{$req}'
      
EOSQL;
  $re1 = $db->execute($qr1);
  $ar1 = $re1->fetchRow();
  return $ar1;
}


// recupera l'elenco dei campi
function getFormFields($form_id, $lng='it', $params){
  global $db;
  $fields = array();
  $qr2 = <<<EOSQL
  
     SELECT f.id, f.titolo, f.tipo, f.obbligatorio, f.formato, f.value, f.fieldset,
            t1.{$lng} AS label
       FROM form_fields f
  LEFT JOIN aa_translation t1 ON f.label=t1.id
      WHERE f.id_form = '{$form_id}'
   ORDER BY f.ordine
   
EOSQL;
  $re2 = $db->execute($qr2);
  while ($ar2 = $re2->fetchRow()){
    extract($ar2);
    $options =  array();
    if ( $tipo == 'select'
      || $tipo == 'checkbox'
      || $tipo == 'radio'
      ){
      // recupero le eventuali options
      $qr3 = "SELECT o.value, o.selezionato, t.$lng AS label FROM field_options o LEFT JOIN aa_translation t ON o.label=t.id WHERE o.id_field = $id ORDER BY o.ordine";
      $re3 = $db->execute($qr3);
      while ($ar3 = $re3->fetchRow()) {
        $selezionato = (isset($params[$titolo]) && $params[$titolo] == $ar3['value']) 
                     ? true 
                     : $ar3['selezionato'];
                     
        $options[$ar3['value']] = array(
          'label'       => $ar3['label'], 
          'selezionato' => $selezionato
          );
      }
    } else {
      // se impostato utilizzo il parametro smarty come value
      if ( $value == '' 
        && isset($params[$titolo])
        ){
        $value = $params[$titolo];
      }
    }
    $fields[$titolo] = array(
            'titolo' => $titolo,
              'tipo' => $tipo,
      'obbligatorio' => $obbligatorio,
           'formato' => $formato,
             'value' => $value,
             'label' => $label,
           'opzioni' => $options,
          'fieldset' => $fieldset
    );
  }
  return $fields;
}


// recupera l'elenco dei fieldset
function getFormFieldset($form_id, $lng='it'){
  global $db;
  $fieldset = array();
  $qr2 = <<<EOSQL
     SELECT f.id, t1.{$lng} AS legend
       FROM form_fieldsets f
  LEFT JOIN aa_translation t1 ON f.titolo=t1.id
      WHERE f.id_form = '{$form_id}'
   ORDER BY f.ordine
EOSQL;
  $re2 = $db->execute($qr2);
  while ($ar2 = $re2->fetchRow()){
    extract($ar2);
    $fieldset[$id] = $legend;
  }
  return $fieldset;
}



// EOF