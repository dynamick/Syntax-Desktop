<?php
//$url = 'http://www.example.com';

//print_r(get_headers($url));

global $cmd, $synWebsiteTitle, $synAbsolutePath;

switch($cmd) {
  case 'export':
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

  case MODIFY:
    $url = NULL;
    $qry = "SELECT * FROM `{$synTable}` WHERE {$synPrimaryKey}";
    $res = $db->Execute($qry);
    if ($arr = $res->fetchRow()) {
      $url = urlencode( $arr['from'] );
    }
    $form = '' //"<div class='panel-heading'>Test redirect:</div>"
          . "<div class='panel-body'>"
          . "<button type='button' class='btn btnn-large btn-block btn-success' onclick='window.parent.content.testUrl()'>"
          . "<i class='fa fa-check'></i> test</button>"
          . "</div>";

    $script1  = <<<EOS
    <script>
      function testUrl(){
        var code, redirect;
        $.getJSON('custom/redirect.client.php', {url:'{$url}'}, function(data) {
          code = data.code;
          redirect = data.redirect;
        }).success(function() {
          var body = [
            '<table class="table table-striped">',
            '<tr>',
              '<th>URL:</th>',
              '<td><code>', '{$arr['from']}', '</code></td>',
            '</tr>',
            '<tr>',
              '<th>HTTP Code:</th>',
              '<td>', code, '</td>',
            '</tr>',
            '<tr>',
              '<th>Redirect to:</th>',
              '<td><code>', redirect, '</code></td>',
            '</tr>',
            '</table>'
          ];
          bootbox.dialog({
            title: "Test chiamata URL",
            message: body.join(''),
            buttons: { success: { label: 'Ok', className: "btn-success" } }
          });
        });
      }
    </script>
EOS;
    echo $script1;

    $script  = "  var txt=\"{$form}\"; ";
    $script .= "  window.parent.content.addBox('custom',txt);";
    enqueue_js( $script );
    break;

  default:
    $reset_script  = "  var txt = ''; ";
    $reset_script .= "  addBox('custom', txt);";
    enqueue_js( $reset_script );

    break;
}

// EOS