<?php
ini_set('display_errors','On');
require_once('../../config/cfg.php');

# compatibility check
if (!class_exists('SimpleXMLElement')) {
  echo "<b>SimpleXMLElement</b> functions not available!<br />This is PHP ".phpversion().", version 5+ required.<br>\n";
  die();
}

if(!isset($_POST['submitted']) or $_POST['submitted']!=1){
# non sottomesso ---------------------------------------------------------------

  // qry gruppi/menu
  $grp = '';
  $qry = <<<EOQRY
  SELECT s.id, s.name, s.parent, s.group, g.name AS groupname
    FROM aa_group_services s
    JOIN aa_groups g ON s.group = g.id
   WHERE s.parent = 0
ORDER BY g.parent_id
EOQRY;
  $res = $db->execute($qry);
  if($arr = $res->fetchrow()){
    do {
      $groupid = $arr['group'];
      $groupname = $arr['groupname'];

      $grp .= "<tr>\n";
      $grp .= "  <th align=\"left\"><input class=\"toggle\" type=\"checkbox\" name=\"group[]\" value=\"{$groupid}\" /> {$groupname}</th>\n";
      $grp .= "  <td><select class=\"toggleable\" name=\"menuitem[{$groupid}]\" disabled=\"disabled\">\n";
      do {
        $service = $arr['id'];
        $name = translateSite($arr['name']);
        $grp .= "    <option value=\"{$service}\">{$name}</option>\n";
        $next = $arr = $res->fetchrow();
      } while ($next && $groupid==$arr['group']);

      $grp .= "  </select></td>\n";
      $grp .= "  <td><label><input type=\"checkbox\" value=\"1\" name=\"insert[{$groupid}]\" checked=\"checked\" disabled=\"disabled\" class=\"toggleable\"> Inserimento</label></td>\n";
      $grp .= "  <td><label><input type=\"checkbox\" value=\"1\" name=\"modify[{$groupid}]\" checked=\"checked\" disabled=\"disabled\" class=\"toggleable\"> Modifica</label></td>\n";
      $grp .= "  <td><label><input type=\"checkbox\" value=\"1\" name=\"delete[{$groupid}]\" checked=\"checked\" disabled=\"disabled\" class=\"toggleable\"> Cancellazione</label></td>\n";
      $grp .= "</tr>\n";

    } while ($next);
  }


  $html = <<<EOHTML
  <form action="" method="post" enctype="multipart/form-data">
  <ol>
    <li>
      <label for="fimport">Seleziona il file da importare:</label><br/>
      <input type="file" name="import" id="fimport"><br/><br/>
    </li>
    <li>
      <label>Seleziona i gruppi e il relativo menu a cui aggiungere i nuovi servizi:</label>
      <table cellpadding="4">
      {$grp}
      </table>
    </li>
    <li>
      <p>Procedi con l'importazione:</p>
      <input type="hidden" name="submitted" value="1">
      <button type="reset">Annulla</button>
      <button type="submit">Procedi</button>
    </li>
  </ol>
  </form>
  <script type="text/javascript" src="http://www.google.com/jsapi"></script>
  <script type="text/javascript">google.load("jquery", "1");</script>
  <script type="text/javascript">
    $(document).ready(function(){
      $('.toggle').click(function(){
        _this = $(this);
        if(_this.is(":checked")){
          _this.parents('tr').find('.toggleable').attr('disabled', false);
        } else {
          _this.parents('tr').find('.toggleable').attr('disabled', true);
        }
      });
    })
  </script>
EOHTML;
  echo $html;


} else {
# sottomesso -------------------------------------------------------------------

 #echo '<pre>', print_r($_FILES), '</pre>';
  if( isset($_FILES['import'])
    && $_FILES['import']['type']=='text/xml'
    && $_FILES['import']['error']===0
    ){
    #$xml = file_get_contents($_FILES['import']['tmp_name']);
    #echo $xml;
    $xml = simplexml_load_file($_FILES['import']['tmp_name']);
    $available_langs = getAvailableLang();

    foreach($xml->service as $service){

      $container = $service->container;
      $cont_keys = array();
      $cont_vals = array();
      $newInitOrder = '';

      foreach($container->children() as $key=>$value){
        if(strtolower($key)!='id'){ //l'id non ci serve
          if(count($value->children())>0){ // valore multilingua
            $langs = array();
            $labels = array();
            foreach($value->children() as $lang=>$label){
              if(strtolower($lang)!='id' && in_array(strtolower($lang), $available_langs)){
                $langs[] = $lang;
                $labels[] = addslashes($label);
              }
            }
            $qry = "INSERT INTO aa_translation (`".implode('`,`', $langs)."`) VALUES ('".implode("','", $labels)."')";
            $res = $db->Execute($qry);
            $ins_id = $db->Insert_Id(); //111;
            $cont_keys[] = $key;
            $cont_vals[$key] = $ins_id;

          } else {
            if($key=='syntable'){
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
            }

            $cont_keys[] = $key;
            $cont_vals[$key] = addslashes(trim($value));
          }
        }
      }


      $qry = "INSERT INTO aa_services (`".implode('`,`', $cont_keys)."`) VALUES ('".implode("','", $cont_vals)."')";
      $res = $db->Execute($qry);
      $cont_id = $db->Insert_Id();
      echo $qry, '<br><br>';
      //echo '<br><br>';

      $elements = $service->elements;
      foreach($elements->children() as $element){
        $elm_keys = array();
        $elm_vals = array();
        foreach($element->children() as $k=>$v){
          if($k!='id'){
            if(count($v->children())>0){
              $langs = array();
              $labels = array();
              foreach($v->children() as $lang=>$label){
                if(strtolower($lang)!='id' && in_array(strtolower($lang), $available_langs)){
                  $langs[] = $lang;
                  $labels[] = addslashes(trim($label));
                }
              }
              $qry = "INSERT INTO aa_translation (`".implode('`,`', $langs)."`) VALUES ('".implode("','", $labels)."')";
              $res = $db->Execute($qry);
              $ins_id = $db->Insert_Id();

              $elm_keys[] = $k;
              $elm_vals[$k] = $ins_id;

            } else {
              if($k=='container') $v = $cont_id;
              if($k=='type'){
                $r = $db->execute("SELECT id FROM aa_element WHERE classname='$v'");
                $a = $r->fetchrow();
                $v = $a['id'];
              }
              $elm_keys[] = trim($k);
              $elm_vals[$k] = addslashes(trim($v));
            }
          }
        }
        $qry = "INSERT INTO aa_services_element (`".implode('`,`', $elm_keys)."`) VALUES ('".implode("','", $elm_vals)."')";
        $res = $db->Execute($qry);
        $elm_id = $db->Insert_Id();
        echo $qry, '<br><br>';

        if($element['initOrder']==1){
          if($element['negative']==1){
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

      // aggiungo il servizio al menu degli utenti selezionati
      if( isset($_POST['group'])){
        foreach($_POST['group'] AS $g){
          $idname     = insertTranslation(translate($cont_vals['name']));
          $menuitem   = $_POST['menuitem'][$g];
          $can_insert = $_POST['insert'][$g];
          $can_edit   = $_POST['modify'][$g];
          $can_delete = $_POST['delete'][$g];
          $order      = getNextPosition($menuitem);

          $menuins = <<<EOQRY
          INSERT INTO aa_group_services (
            `name`,
            `group`,
            `service`,
            `parent`,
            `insert`,
            `modify`,
            `delete`,
            `order`
          ) VALUES (
            '{$idname}',
            '{$g}',
            '{$cont_id}',
            '{$menuitem}',
            '{$can_insert}',
            '{$can_edit}',
            '{$can_delete}',
            '{$order}'
          )
EOQRY;
          $db->execute($menuins);
          echo $menuins, '<br><hr>';
        }
      }
      // sincronizzo?
      // $contenitore->dbSynchronize();
    }
  } else {
    echo '<p><b>Errore:</b> il file fornito non è un xml valido.</p>';
  }
}


function getAvailableLang(){
  global $db;
  $available = array();
  $res = $db->Execute('SELECT initial FROM aa_lang ORDER BY id');
  while($arr = $res->fetchrow()){
    $available[] = trim(strtolower($arr['initial']));
  }
  return $available;
}

function getNextPosition($parent){
  global $db;
  $res = $db->Execute("SELECT MAX(`order`) AS max FROM aa_group_services WHERE parent='{$parent}'");
  if($arr = $res->fetchrow()){
    $max = ($arr['max']+10);
  }
  return $max;
}


function checkTableExistance($table){
  global $db, $synDbName;
  $exists = false;

  $qry = <<<EOQ
SELECT table_name
FROM information_schema.tables
WHERE table_schema = '{$synDbName}'
AND table_name = '{$table}'
EOQ;

  $res = $db->execute($qry);
  $arr = $res->fetchrow();
  if(is_array($arr)) $exists = true;

  //var_dump($check); die();
  return $exists;
}

?>
