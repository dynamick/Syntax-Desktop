<?php

function smarty_function_form($params, &$smarty) {
  global $db, $synWebsiteTitle, $synPublicPath, $reCaptchaKey;

  s_start(); // session_start
  $labels       = array(
                  'informativa',
                  'informativa_privacy',
                  'informativa_privacy_link',
                  'checkfields',
                  'campo_obbligatorio',
                  'email_non_valida',
                  'verifica_valore',
                  'cancella',
                  'invia',
                  'codice_sicurezza',
                  'conferma_recaptcha'
                );
  $t            = multiTranslateDictionary( $labels );
  $form         = safe_get( $params['id'], NULL );
  $page         = safe_get( $params['page'], $smarty->getTemplateVars('synPageId'), 'intval' );
  $lng          = getLangInitial();
  $ajax_submit  = TRUE;


  if ( !empty($form) )
    $form_var = getFormAttributes( $form, 'id', $lng); // vedi sotto
  else
    $form_var = getFormAttributes( $page, 'pagina', $lng); // vedi sotto

  if ( !$form_var )
    return false;  // nessun form trovato, esco

  $form_id  = $form_var['id'];
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
    && $session['submitted'] == true
    ){
    // - form sottomesso correttamente, presento il messaggio di conferma
    $html .= $form_var['risposta'];
    unset($_SESSION['form'.$form_id]);

  } else {
    // - errore/non ancora sottomesso, presento il form
    $html .= $form_var['descrizione'];
    if (isset($session['data'])) {
      // se presenti, imposto i dati già inseriti
      $dati = unserialize( $session['data'] );
      foreach( $dati as $k => $v ) {
        switch( $fields[$k]['tipo'] ) {
          case 'checkbox':
          case 'radio':
          case 'select':
            foreach( $fields[$k]['opzioni'] as $f => $s )
              $fields[$k]['opzioni'][$f]['selezionato'] = (in_array($f, $v)) ? 1 : 0;
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
      'button_class'        => 'btn',
      'submit_button_class' => 'btn-primary',
      'reset_button_class'  => 'btn-default',
      'resetLabel'          => $t['cancella'],
      'submitLabel'         => $t['invia'],
      'reset_button'        => true,
      'captcha'             => $form_var['captcha'],
      'captchaConfig'       => array( 'width' => 270, 'height' => 50 ),
      'reCaptchaKey'        => $reCaptchaKey,
      'captchaLabel'        => $t['codice_sicurezza'],
      'reCaptchaLabel'      => $t['conferma_recaptcha'],
      'informativa'         => nl2br($t['informativa_privacy']),
      'privacy'             => $form_var['privacy'],
      'privacy_page'        => $privacy_page,
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

    // ============================ START GROUPs ============================
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

    // ============================  END GROUPs =============================

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
function getFormAttributes( $req, $identifier='id', $lng='it' ){
  global $db;
  $db->setFetchMode( ADODB_FETCH_ASSOC );
  $where = "WHERE f.{$identifier} = '{$req}'";
  $qry = <<<EOSQL
     SELECT f.id, f.destinatario, f.privacy, f.privacy_page, f.captcha,
            t1.{$lng} AS titolo, t2.{$lng} AS descrizione, t3.{$lng} AS risposta
       FROM forms f
  LEFT JOIN aa_translation t1 ON f.titolo=t1.id
  LEFT JOIN aa_translation t2 ON f.descrizione=t2.id
  LEFT JOIN aa_translation t3 ON f.risposta=t3.id
      {$where}
      LIMIT 0,1
EOSQL;
  $res = $db->Execute( $qry );
  if ( $arr = $res->FetchRow() ) {
    return $arr;
  } else {
    return FALSE;
  }
}


// recupera l'elenco dei campi
function getFormFields( $form_id, $lng='it', $params ){
  global $db;
  $fields = array();
  $qr2 = <<<EOSQL

     SELECT f.id, f.titolo, f.tipo, f.obbligatorio, f.formato, f.value, f.fieldset,
            t1.{$lng} AS label
       FROM form_fields f
  LEFT JOIN aa_translation t1 ON f.label = t1.id
  LEFT JOIN form_fieldsets fs ON f.fieldset = fs.id
      WHERE f.id_form = '{$form_id}'
   ORDER BY fs.ordine, f.ordine

EOSQL;
  $re2 = $db->execute($qr2);
  while ( $ar2 = $re2->fetchRow() ) {
    extract($ar2);
    $options =  array();
    if ( $tipo == 'select'
      || $tipo == 'checkbox'
      || $tipo == 'radio'
      ){
      // get input options
      if ( isset( $params[$titolo]['options'] )
        && is_array( $params[$titolo]['options'] )
        ){ // something has been passed via smarty parameters
        /* 
        template example (replace 'myfield' with the field's name): 
        {$options = ['options' => ['1' => ['label'=>'Uno', 'selezionato'=>false], '2' => ['label'=>'Due', 'selezionato'=>true ]]] }
        {form page=$synPageId myfield=$options}
        */
        $options = $params[$titolo]['options'];

      } else {
        // get options from database
        $qr3 = <<<EOQ
        SELECT o.value, o.selezionato, t.{$lng} AS label
          FROM field_options o
     LEFT JOIN aa_translation t ON o.label = t.id
         WHERE o.id_field = '{$id}'
      ORDER BY o.ordine
EOQ;
        $re3 = $db->execute($qr3);
        $sel = false;
        while ( $ar3 = $re3->fetchRow() ) {
          $options[ $ar3['value'] ] = array(
            'label'       => $ar3['label'],
            'selezionato' => $ar3['selezionato'] // default selection
          );
          if ($ar3['selezionato'])
            $sel = $ar3['value'];
        }
        // parameter-driven selection
        if ( isset( $params[$titolo] )
          && array_key_exists( $params[$titolo], $options)
          ){
          $options[ $params[$titolo] ]['selezionato'] = true;
          $options[ $sel ]['selezionato'] = false;
        }
      }
    } else {
      // if set, use smarty parameter as seletcted value
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
function getFormFieldset( $form_id, $lng='it' ) {
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
