<?php
require_once('../../config/cfg.php');

# compatibility check
if (!class_exists('SimpleXMLElement')) {
  echo "<b>SimpleXMLElement</b> functions not available!<br />This is PHP ".phpversion().", version 5+ required.<br>\n";
  die();
}

if(!isset($_POST['submitted']) or $_POST['submitted']!=1){
# non sottomesso ---------------------------------------------------------------
  $rows = '';
  $qry = "SELECT * FROM `aa_services`";
  $res = $db->execute($qry);

  while($arr = $res->fetchrow()){
    extract($arr);

    $rows .= "<p><input type=\"checkbox\" name=\"selected[]\" value=\"{$id}\">";
    $rows .= "<img src=\"{$synAdminPath}/modules/aa/{$icon}\" alt=\"{$id}\" width=\"16\" height=\"16\"> ";
    $rows .= translateDesktop($name)." (<code>{$syntable}</code>)</p>\n";
  }

  $html = <<<EOHTML
    <p>Seleziona i servizi da esportare:</p>
    <form action="" method="post">
  {$rows}
    <p>
      <input type="hidden" name="submitted" value="1">
      <button type="reset">Annulla</button>
      <button type="submit">Procedi</button>
    </p>
    </form>
EOHTML;
  echo $html;


} else {
# sottomesso -------------------------------------------------------------------
  $db->SetFetchMode(ADODB_FETCH_ASSOC);

  # indice delle lingue
  $tc = $db->MetaColumns('aa_translation');
  $langkeys = array_keys($tc);

  #echo '<pre>', print_r($_POST), '<pre>';
  $selected = implode(',',$_POST['selected']);
  $xml = new SimpleXMLElement('<root></root>');
  //$xml = new SimpleXMLElement();
  
  $qry = "SELECT * FROM `aa_services` WHERE id IN ({$selected})";
  $res = $db->execute($qry);
  while($arr = $res->fetchrow()){
    # ciclo sui servizi
    $service = $xml->addChild('service');
    $container = $service->addChild('container');

    // name
    $name = $arr['name'];
    $q = "SELECT * FROM aa_translation WHERE id={$name}";
    $r = $db->execute($q);
    $a = $r->fetchrow();
    $arr['name'] = array_combine($langkeys, $a);
    
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
      # ciclo sugli elementi
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

  # download xml

  $filename = sanitizePath($synWebsiteTitle);
  header("Pragma: public");
  header("Expires: 0");
  header("Content-Description: File Transfer");
  header("Content-Disposition: attachment; filename={$filename}.xml;");

  header('Content-Type: text/xml');
  echo $xml->asXML();
}
?>
