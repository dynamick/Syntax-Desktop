<?php
require_once('../../config/cfg.php');

# compatibility check
if (!class_exists('SimpleXMLElement')) {
  echo "<b>SimpleXMLElement</b> functions not available!<br />This is PHP ".phpversion().", version 5+ required.<br>\n";
  die();
}

if($_POST['submitted']!=1){
# non sottomesso ---------------------------------------------------------------
  
  $html = <<<EOHTML
    <p>Seleziona il file da importare:</p>
    <form action="" method="post" enctype="multipart/form-data">
    <p>
      <input type="file" name="import">
    </p>
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

 #echo '<pre>', print_r($_FILES), '</pre>';
  if(  isset($_FILES['import'])
    && $_FILES['import']['type']=='text/xml'
    && $_FILES['import']['error']===0
    ){
    #$xml = file_get_contents($_FILES['import']['tmp_name']);
    #echo $xml;
    $xml = simplexml_load_file($_FILES['import']['tmp_name']);

    foreach($xml->service as $service){

      $container = $service->container;
      $cont_keys = array();
      $cont_vals = array();
      
      foreach($container->children() as $key=>$value){
        if($key!='id'){ //l'id non ci serve
          if(count($value->children())>0){
            $langs = array();
            $labels = array();
            foreach($value->children() as $lang=>$label){
              $langs[] = $lang;
              $labels[] = addslashes($label);
            }
            $qry = "INSERT INTO aa_translation (`".implode('`,`', $langs)."`) VALUES ('".implode("','", $labels)."')";
            $res = $db->Execute($qry);
            $ins_id = $db->Insert_Id(); //111;
            $cont_keys[] = $key;
            $cont_vals[] = $ins_id;

          } else {
            if($key=='syntable'){
/*
              $table = $value;
              $check = checkTableExistance($table);

              if($check!=false){
                $i = 0;
                do {
                  $i++;
                  $newtable = $table.$i;
                  $newcheck = checkTableExistance($newtable);
                } while ($newchek!=false);
                $value = $newtable;
              }
*/
            }
            $cont_keys[] = $key;
            $cont_vals[] = addslashes($value);
          }
        }

      }
      $qry = "INSERT INTO aa_services (`".implode('`,`', $cont_keys)."`) VALUES ('".implode("','", $cont_vals)."')";
      $res = $db->Execute($qry);
      $cont_id = $db->Insert_Id();
echo $qry, '<br><br>';
      echo '<br><br>';

      $elements = $service->elements;
      foreach($elements->children() as $element){
        $cont_keys = array();
        $cont_vals = array();
        foreach($element->children() as $k=>$v){
          if($k!='id'){
            if(count($v->children())>0){
              $langs = array();
              $labels = array();
              foreach($v->children() as $lang=>$label){
                $langs[] = $lang;
                $labels[] = addslashes($label);
              }
              $qry = "INSERT INTO aa_translation (`".implode('`,`', $langs)."`) VALUES ('".implode("','", $labels)."')";
              $res = $db->Execute($qry);
              $ins_id = $db->Insert_Id();

              $cont_keys[] = $k;
              $cont_vals[] = $ins_id;

            } else {
              if($k=='container') $v = $cont_id;
              if($k=='type'){
                $r = $db->execute("SELECT id FROM aa_element WHERE classname='$v'");
                $a = $r->fetchrow();
                $v = $a['id'];
              }
              $cont_keys[] = $k;
              $cont_vals[] = addslashes($v);
            }
          }
        }
        $qry = "INSERT INTO aa_services_element (`".implode('`,`', $cont_keys)."`) VALUES ('".implode("','", $cont_vals)."')";
        $res = $db->Execute($qry);
        $elm_id = $db->Insert_Id();
        echo $qry, '<br><br>';

        if($element[initOrder]==1){
          if($element[negative]==1){
            $newInitOrder = '-';
          }
          $newInitOrder .= $elm_id;
        }
      }

      if($newInitOrder!=''){
        $qry = "UPDATE aa_services SET `initOrder`='$newInitOrder' WHERE id=$cont_id";
        $res = $db->Execute($qry);
        echo $qry, '<br>';
      }
      echo '<br><hr>';
    }
  }

}


function checkTableExistance($table){
  global $db;
  return $check = @$db->execute("SELECT * FROM {$table}");
}
?>
