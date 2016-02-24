<?php

function smarty_function_form($params, &$smarty) {
  global $db, $synWebsiteTitle, $synPublicPath;

  s_start(); // session_start

  $page         = safe_get( $params['page'], NULL, 'intval' );
  $formid       = safe_get( $params['id'], NULL );
  $lng          = getLangInitial();
  $ajax_submit  = TRUE;
  $t            = multiTranslateDictionary(array(
                'informativa',
                'informativa_privacy',
                'informativa_privacy_link',
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

  $fieldset = getFormFieldset( $form_id, $lng ); // vedi sotto
  $fields   = getFormFields( $form_id, $lng, $params ); // vedi sotto
  foreach( $fields as $field ) {
    if ($field['tipo'] == 'file')
      $ajax_submit = false; // non posso trasmettere file via ajax
  }

  $form     = new bsForm( $form_id );
  //$form     = new formBuilder($form_id);

  $session  = safe_get( $_SESSION['form'.$form_id], FALSE );
  $html     = ''; //"<h2>{$form_var['titolo']}</h2>\n";

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

    $privacy_page = $form_var['privacy_page']
                  ? createPath( $form_var['privacy_page'] )
                  : NULL;

    $form_config = array( // configurazione
      'action'              => $synPublicPath . '/server/dispatch.php',
      'method'              => 'post',
      'class'               => 'synform',
      'hide_label'          => false,
      'resetLabel'          => $t['cancella'],
      'submitLabel'         => $t['invia'],
      'button_class'        => 'btn',
      'submit_button_class' => 'btn-primary',
      'reset_button_class'  => 'btn-default',
      'captcha'             => $form_var['captcha'],
      'captchaConfig'       => array( 'width' => 270, 'height' => 50 ),
      'captchaLabel'        => $t['codice_sicurezza'],
      'privacy'             => $form_var['privacy'],
      'privacy_page'        => $privacy_page,
      'informativa'         => nl2br($t['informativa_privacy']),
      'approvazione'        => $t['informativa'],
      'approvazione_link'   => $t['informativa_privacy_link'],
      'checkfields'         => $t['checkfields'],
      'error1'              => $t['campo_obbligatorio'],
      'error2'              => $t['email_non_valida'],
      'error3'              => $t['verifica_valore'],
      'include_script'      => false,
      'ajax_submit'         => $ajax_submit,
      'debug'               => false
      );

    $form->setAttributes( $form_config );

    foreach ($fieldset as $s => $l)
    	$form->addFieldset($s, $l);

    // ============================ START GROUPs ============================ */
    // special markup for custom fields
    // syntax: $form->addGroup( <group name>, <group html>, <array of field names>, <fieldset id> );
    /*

    $group0 = <<<EOGROUP
    <div id="cp" class="well">
      <section>
        %%nome_label%%
        <label class="input">
        %%nome%%
        </label>
      </section>
    </div>
EOGROUP;
    $group_fields = array('nome');
    $form->addGroup('name', $group0, $group_fields, 0);

    */
    // ============================  END GROUPs ============================= */

    foreach($fields as $f) {
    	$form->addField(
        $f['titolo'],
        $f['label'],
        $f['value'],
        $f['tipo'],
        $f['obbligatorio'],
        $f['formato'],
        $f['opzioni'],
        $f['fieldset']
      );
    }

    if ( isset($session['error']) )
	  	$form->errorMsg($session['error']);

  	$html .= $form->render();
    unset( $_SESSION['form'.$form_id] );
  }

  if ( isset($form_config['include_script']) && $form_config['include_script'] === FALSE )
    $smarty->assign( 'pageScript', $form->validateScript() );

  // eventuali fieldset catturati
  $extra_markup = $form->getExtraMarkup();
  if (!empty($extra_markup))
    $smarty->assign( 'extra_markup', $extra_markup );

  return '<div id="form' . $form_id . '-wrapper">' . $html . '</div>';
}


// recupera dal db gli attributi del form
function getFormAttributesByPage($req, $lng='it'){
  global $db;
  $qr1 = <<<EOSQL

     SELECT f.id, f.destinatario, f.privacy, f.privacy_page, f.captcha,
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

     SELECT f.id, f.destinatario, f.privacy, f.privacy_page, f.captcha,
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
  LEFT JOIN aa_translation t1 ON f.label = t1.id
 RIGHT JOIN form_fieldsets fs ON f.fieldset = fs.id
      WHERE f.id_form = '{$form_id}'
   ORDER BY fs.ordine, f.ordine

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
