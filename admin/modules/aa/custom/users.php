<?php
//******************************************************************************
//                                DO NOT MODIFY BELOW
//******************************************************************************
global $cmd, $synWebsiteTitle, $synAbsolutePath;

switch($cmd) {
  case 'export':
    $fname = '/cache/users_' . time() . '.csv';
    $fields = array(
      // campo / etichetta colonna
      'name'        => 'Nome',
      'surname'     => 'Cognome',
      'email'       => 'Email',
      'address'     => 'Indirizzo',
      'city'        => 'Città',
      'zip'         => 'CAP',
      'province'    => 'Provincia',
      'newsletter'  => 'Newsletter',
      'created_at'  => 'Data di iscrizione',
      'last_update' => 'Ultimo aggiornamento',
      'last_access' => 'Ultimo accesso',
      'login_count' => 'Conteggio login'
    );

    $head = implode( ';', $fields ) . "\r\n";
    $rows = '';

    $db->setFetchMode( ADODB_FETCH_ASSOC );
    $qry  = 'SELECT ' . implode( ',', array_keys($fields) ). ' FROM users ORDER BY created_at DESC';
    $res  = $db->Execute($qry);
    while ( $arr = $res->FetchRow() )
      $rows .= implode( ';', $arr ) . "\r\n";

    $fo = fopen( $synAbsolutePath . $fname, 'wb' ) or die('impossibile aprire il file');
    //fwrite($fo, "\xEF\xBB\xBF"); // utf-8 BOM
    fwrite($fo, utf8_decode( $head ));
    fwrite($fo, utf8_decode( $rows ));
    fclose($fo);

    $html = <<<EOHTML
    <div class="jumbotron">
      <h1>Esportazione Utenti</h1>
      <p>&Egrave; stato generato il file CSV che puoi scaricare sul tuo pc:</p>
      <p><a class="btn btn-success btn-lg" href="{$fname}">{$fname}</a></p>
      <p><a class="btn btn-link" href="/admin/modules/aa/content.php">Torna indietro</a></p>
    </div>
    <script>
      window.parent.content.addBox('custom', '');
    </script>
EOHTML;

    die( $html );
    break;

  case '':
    $form = "<div class='panel-heading'>Clicca qui per esportare gli iscritti in formato CSV per Excel.</div>"
      . "<form class='panel-body' action='content.php?cmd=export' target='content' method='post'>"
      . "<button type='submit' class='btn btn-large btn-block btn-success'><i class='fa fa-download'></i> Esporta</button>"
      . "</form>";
    $script  = "<script>";
    $script .= "  var txt=\"{$form}\"; ";
    $script .= "  window.parent.content.addBox('custom',txt);";
    $script .= "</script>";

    echo $script;

    break;
  default:
    break;
}

// EOS