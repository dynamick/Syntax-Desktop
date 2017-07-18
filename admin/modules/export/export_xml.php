<?php
require_once('../../config/cfg.php');

// compatibility check
if (!class_exists('SimpleXMLElement')) {
  echo "<b>SimpleXMLElement</b> functions not available!<br />This is PHP ".phpversion().", version 5+ required.<br>\n";
  die();
}

if(!isset($_POST['submitted']) or $_POST['submitted']!=1){
// non sottomesso ---------------------------------------------------------------
  $rows = '';
  $qry = "SELECT * FROM `aa_services`";
  $res = $db->execute($qry);

  while($arr = $res->fetchrow()){
    extract($arr);

    $faicon = null;
    if ($icon && substr( $icon, 0, 2 ) == 'fa') {
      $faicon = "<i class=\"fa {$icon}\"></i>";
    }
    $name = translateDesktop($name);
    $rows .= <<<EOROW
    <tr>
      <td style="width:1px"><input type="checkbox" name="selected[]" value="{$id}"></td>
      <td style="width:1px">{$faicon}</td>
      <td>{$name}</td>
      <td class="text-right"><code>{$syntable}</code></td>
    </tr>
EOROW;
  }

  $html = <<<EOHTML
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Export XML</title>
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Open+Sans:700,300,400,600&amp;subset=latin,cyrillic">
    <link rel="stylesheet" type="text/css" href="../../assets/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="../../assets/css/font-awesome.min.css" />
    <link rel="stylesheet" type="text/css" href="../../assets/css/syntax.css" />    
  </head>
  <body>
    <div class="container-fluid">
      <h3>Seleziona i servizi da esportare:</h3>
      <form action="" method="post">
        <table class="table table-striped">
        {$rows}
        </table>
        <p>
          <input type="hidden" name="submitted" value="1">
          <button type="reset" class="btn btn-default">Annulla</button>
          <button type="submit" class="btn btn-primary">Procedi</button>
        </p>
      </form>
    </div>
    <script type="text/javascript" src="../../assets/js/vendor/jquery.js"></script>
    <script>
      $(function(){
        $("input[type='checkbox']").change( function() {
          var _this = $(this), _tr = _this.closest('tr');
          if ( _this.is(":checked")) {
            _tr.addClass("info"); 
          } else {
            _tr.removeClass("info");
          }
        });
      });
    </script>
  </body>
</html>
EOHTML;
  echo $html;


} else {
// sottomesso -------------------------------------------------------------------
  $db->SetFetchMode(ADODB_FETCH_ASSOC);

  // indice delle lingue
  $tc = $db->MetaColumns('aa_translation');
  $langkeys = array_keys($tc);

  // echo '<pre>', print_r($_POST), '<pre>';
  $selected = implode(',',$_POST['selected']);
  $xml = new SimpleXMLElement('<root></root>');
  //$xml = new SimpleXMLElement();
  
  $filename_arr = array();
  $filename_arr[] = sanitizePath($synWebsiteTitle);

  $qry = "SELECT * FROM `aa_services` WHERE id IN ({$selected})";
  $res = $db->execute($qry);
  while($arr = $res->fetchrow()){
    // ciclo sui servizi
    $service = $xml->addChild('service');
    $container = $service->addChild('container');

    // name
    $name = $arr['name'];
    $q = "SELECT * FROM aa_translation WHERE id={$name}";
    $r = $db->execute($q);
    $a = $r->fetchrow();
    $arr['name'] = array_combine($langkeys, $a);
    $filename_arr[] = $arr['syntable'];
        
    // description
    $description = $arr['description'];
    $q = "SELECT * FROM aa_translation WHERE id={$description}";
    $r = $db->execute($q);
    $a = $r->fetchrow();
    $arr['description'] = array_combine($langkeys, $a);

    $initOrder = $arr['initOrder'];
    $initNegative = false;
    if($initOrder{0}=='-'){
      $initOrder = substr($initOrder, 1);
      $initNegative = true;
    }

    foreach($arr as $k=>$v){
      if(is_array($v)){
        $n = $container->addChild($k);
        foreach($v as $key=>$value){
          if($key!='id'){
            $n->addChild($key, str_replace('&', '&amp;', html_entity_decode($value, ENT_NOQUOTES, 'UTF-8')));
          }
        }
      } else $container->addChild($k, $v);
    }

    $elements = $service->addChild('elements');
    $qe = "SELECT * FROM `aa_services_element` WHERE container={$arr['id']} ORDER BY `order`";
    $re = $db->execute($qe);
    while($ae = $re->fetchrow()){
      // ciclo sugli elementi
      $element = $elements->addChild('element');

      //label
      $label = $ae['label'];
      $q = "SELECT * FROM aa_translation WHERE id={$label}";
      $r = $db->execute($q);
      $a = $r->fetchrow();
      $ae['label'] = array_combine($langkeys, $a);

      //help
      $help = $ae['help'];
      $q = "SELECT * FROM aa_translation WHERE id={$help}";
      $r = $db->execute($q);
      $a = $r->fetchrow();
      $ae['help'] = array_combine($langkeys, $a);

      //class name
      $type = $ae['type'];
      $q = "SELECT classname FROM aa_element WHERE id={$type}";
      $r = $db->execute($q);
      $a = $r->fetchrow();
      $ae['type'] = $a['classname'];

      foreach($ae as $k=>$v){
        if(is_array($v)){
          $n = $element->addChild($k);
          foreach($v as $key=>$value){
            if($key!='id'){
              $n->addChild($key, str_replace('&', '&amp;', html_entity_decode($value, ENT_NOQUOTES, 'UTF-8')));
            }
          }
        } else $element->addChild($k, $v);
        if($k=='id' && $v==$initOrder){
          $element->addAttribute('initOrder', true);
          if($initNegative) $element->addAttribute('negative', true);
        }
      }
    } // fine ciclo elementi
    
  } // fine ciclo servizi

  // download xml
  $filename = implode('__', $filename_arr); //sanitizePath($synWebsiteTitle);
  header("Pragma: public");
  header("Expires: 0");
  header("Content-Description: File Transfer");
  header("Content-Disposition: attachment; filename={$filename}.xml;");

  header('Content-Type: text/xml');
  //echo $xml->asXML();
  $dom = dom_import_simplexml($xml)->ownerDocument;
  $dom->formatOutput = true;
  echo $dom->saveXML();  
}
?>
