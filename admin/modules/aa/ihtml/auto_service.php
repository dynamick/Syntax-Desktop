<?php
if(!isset($_SESSION)) session_start();
if(!isset($_REQUEST['cmd'])) $_REQUEST['cmd'] = '';

# auto-load delle classi istanziate
function __autoload($class) {
  global $synAbsolutePath;
  require_once $synAbsolutePath.'/admin/modules/aa/classes/'.$class.'.php';
}


  //definizione variabili globali
  $synContainer = isset($_REQUEST["aa_service"]) ? $_REQUEST["aa_service"]: $_SESSION["aa_service"];
  $buttons=array();

  //creo il contenitore
  if(!isset($db)) include_once ("../../../config/cfg.php"); //if RPC
  
  $res = $db->Execute("SELECT * FROM aa_services WHERE id=$synContainer");
  $arr = $res->FetchRow();
  $synDb=str_replace(" ","_",strToLower($arr["syntable"]));
  $contenitore = synContainer::getInstance($synDb, $buttons, true, $arr["name"], $arr["description"],$arr["multilang"]);

  $dbSync=$arr["dbsync"];

  //ci aggiungo gli elementi
  $res=$db->Execute("SELECT se.*,e.classname as classname FROM aa_services_element se INNER JOIN aa_element e ON se.type=e.id WHERE container=$synContainer order by `order`, `id`");
  $count = 0;
  while ($arr=$res->FetchRow()) {
    $obj[$count] = new $arr["classname"]($arr["name"], $arr["value"], translateDesktop($arr["label"]), $arr["size"], translateDesktop($arr["help"]));
    $obj[$count]->isListable($arr["isvisible"], $arr["label"], $arr["iseditable"]);
  //$obj[$count]->setContainer($contenitore);

    if ($arr["path"]!="") $obj[$count]->setPath($arr["path"]);
    if ($arr["iskey"]==1) $obj[$count]->setKey(true);
    if ($arr["ismultilang"]==1) $obj[$count]->setMultilang(true);

    if ($arr["qry"]!="" and (!isset($_REQUEST[$arr['name']]) || $_REQUEST[$arr["name"]]=="")) {
      $obj[$count]->setQry($arr["qry"]);
      $obj[$count]->setPath($arr["path"]);
    }

    $contenitore->checkJoins($arr["id"]);
    $contenitore->addElement($obj[$count]);
    $count++;
  }

  //sincronizzo il db con gli elementi aggiunti al contenitore
  if ($dbSync=="1" && $_REQUEST["cmd"]=='') $contenitore->dbSynchronize();

  //----------------------------------------------------------------------------
  //                               INIZIALIZZAZIONE
  //----------------------------------------------------------------------------
  //variabili globali
  $PHP_SELF=$_SERVER['PHP_SELF'];

  $cmd = '';
  if (isset($_REQUEST['cmd'])) $cmd = $_REQUEST['cmd'];
  if (isset($_POST['default-cmd']) && $cmd=='') $cmd = $_POST['default-cmd']; // FckEditor/toolbar save button
  if (isset($_POST['after'])) $after = $_POST['after']; else $after="stay";
  if (!defined("RPC")) define("RPC", "rpcfunction");

  //----------------------------------------------------------------------------
  //                                   FUNZIONI
  //----------------------------------------------------------------------------


  //if some search qry is done, add the constraint to the qry string
  function addQueryWhere ($qry) {
    global $aa_qry, $synTable, $db, $aa_group_services, $contenitore, $treeFrame;

    //remove session to see the entire list
    if (isset($_GET["aa_search_clean"])) session_unregister("aa_qry");

    if (!isset($_SESSION["aa_joinStack"])) {
      $res=$db->Execute("SELECT gs.filter FROM aa_group_services gs INNER JOIN aa_groups g ON gs.group=g.id, aa_users u where u.id_group=g.id and u.id=".getSynUser()." and gs.id='".$_SESSION["aa_group_services"]."'");
      list($filter)=$res->FetchRow();
      if ($filter!="") $qry.=" WHERE $filter";
    }

    //add tree permission
    $treeQry=$contenitore->getTreePermission();
    if ($treeQry!="" and $filter=="") {
      if (strpos($qry,"WHERE")==false) $qry.=" WHERE ";
      else $qry.=" AND ";
      $qry.=$treeQry;
    }

    if (isset($_POST["aa_search"])) {
      $type   = $_POST["type"];
      $keyword= $_POST["keyword"];
      $field  = $_POST["field"];

      $fieldObj=$contenitore->getElement($field);
      if ($fieldObj->isMultilang()) {
        $qry.=" LEFT JOIN aa_translation ON ".$synTable.".".$field." = aa_translation.id";
        $field = "aa_translation`.`".$_SESSION['synSiteLangInitial'];
      }

      if (strpos($qry,"WHERE")===false) $qry.=" WHERE ";
      else $qry.=" AND ";

      if ($type=="=") $qry.="`".$field."` = '".$keyword."' ";
      if ($type=="like") $qry.="`".$field."` LIKE '%".$keyword."%' ";
      if ($type==">") $qry.="`".$field."` > '".$keyword."' ";
      if ($type=="<") $qry.="`".$field."` < '".$keyword."' ";
      if ($type=="acceso") $qry.=" `".$field."` <> \"\" ";
      if ($type=="spento") $qry.=" `".$field."`=\"\" ";

      session_register("aa_qry");
      $_SESSION['aa_qry'] = $qry;
    }

    //check owner field...
    $owner = (isset($contenitore->ownerField)) ? $contenitore->ownerField : '';
    if ($owner!="" && $treeFrame!="true") { // ...unless it's a tree
      if (strpos($qry, "WHERE")==false) $qry.=" WHERE ";
      else $qry.=" AND ";
      $qry.="($owner IN (".implode(',', $_SESSION["synGroupChild"]).") OR $owner IS NULL OR $owner='') ";
    }

    if (isset($_SESSION["aa_joinStack"])) {
      if (strpos($qry,"WHERE")==false) $qry.=" WHERE ";
      else $qry.=" AND ";
      $stackKeys=array_keys($_SESSION["aa_joinStack"]);
      $stackLastKey=$stackKeys[count($stackKeys)-1];
      $join=new synJoin($_SESSION["aa_joinStack"][$stackLastKey]["idjoin"]);
      $toElmName=$join->toElmName;
      $value=$_SESSION["aa_joinStack"][$stackLastKey]["value"];
      $qry.="`".$toElmName."` = '".$value."' ";
    }

    if (isset($_SESSION["aa_qry"])) $qry=$_SESSION["aa_qry"];

    if (isset($_GET["aa_order"])) {
      if ($_SESSION["aa_order"]==$_GET["aa_order"])
        if ($_SESSION["aa_order_direction"]==" DESC") $_SESSION["aa_order_direction"]=" ASC";
        else $_SESSION["aa_order_direction"]=" DESC";

      $_SESSION["aa_order"]=$_GET["aa_order"];

    } elseif (!isset($_SESSION["aa_order"])) {
      global $synTable;
      $res=$db->Execute("SELECT s.initOrder FROM aa_services s where s.syntable='".$synTable."'");
      list($initOrderElement)=$res->FetchRow();
      if ($initOrderElement!=0) {
        $sign=abs($initOrderElement)/$initOrderElement;
        $res=$db->Execute("SELECT se.name FROM aa_services_element se where se.id=".abs($initOrderElement));
        list($initOrderElement)=$res->FetchRow();
        $sign>0?$_SESSION["aa_order_direction"]=" ASC":$_SESSION["aa_order_direction"]=" DESC";
        $_SESSION["aa_order"]=str_replace(" ", "_", $initOrderElement);
      }
    }

    if (isset($_SESSION["aa_order"])) {
      $qry.=" ORDER BY `".$_SESSION["aa_order"]."` ".$_SESSION["aa_order_direction"];
    }

    #echo 'Query: <code>',$qry,'</code>';
    return $qry;
  } //end addQueryWhere



//******************************************************************************
//***........................................................................***
//***............................... ACTIONS ................................***
//***........................................................................***
//******************************************************************************

  $synHtml = new synHtml();
  $synLoggedUser = new synUser();
  $synTable = $contenitore->getTable();
  $synTitle = $contenitore->getTitle();
  $synDescription = $contenitore->getDescription();

  if (!getSynUser()) {echo "<script>alert('Sessione scaduta. Prego riconnettersi.'); top.location.href='../../';</script>"; die("sessione scaduta");}

  //include custom function. The naming convention is: path/to/modules/aa/custom/$synTable.php
  if (file_exists($synAbsolutePath.$synAdminPath."/modules/aa/custom/".$synTable.".php")===true) {
    include ($synAbsolutePath.$synAdminPath."/modules/aa/custom/".$synTable.".php");
  }


  switch($cmd) {
    /**************************************************************************
    *                             ADD A ROW
    ***************************************************************************/

    case ADD:
      echo "<script src=\"../../includes/js/tooltip/tooltip.js\"></script>";
      aaHeader($str["insertrow"],$str["insertrow_bis"]);

      if(isset($_SESSION[$synTable.'_clone']) and trim($_SESSION[$synTable.'_clone'])!=""){
        $data = unserialize($_SESSION[$synTable.'_clone']);
        foreach($data as $k=>$v){
          if(is_array($v)){
            $data[$k] = implode($v, '|'); // in caso di multicheck
          }
        }
        $contenitore->updateValues($data); // fracco i valori in sessione nel contenitore
      }

      echo $synHtml->form("action='$PHP_SELF' method='POST' enctype='multipart/form-data' onsubmit='javascript: true || loading();' autocomplete=\"off\"");
      $contenitore->getHtml();
      $after_options = array(
        'exit' => $str['saveandexit'],
        'stay' => $str['save'],
       'clone' => $str['saveandclone'],
         'new' => $str['saveandadd']
      );
      $label  = $str["new"];
      $bottom  = "<table id=\"actions\">\n";
      $bottom .= "  <tr>\n";
      $bottom .= "    <td>";
      $ico_off = "<img src=\"img/tool_undo.png\" alt=\"{$label}\" /> ";
      $bottom .= $synHtml->hidden("name='changeto' value=''");
      $bottom .= $synHtml->hidden("name='default-cmd' value='".INSERT."'");
      $bottom .= $synHtml->button("name='off' value='' class='cancel_button' onclick='document.location=\"{$PHP_SELF}\"; return false;'", $ico_off.$str["cancel"], 'reset');
      $bottom .= "    </td>\n";
      $bottom .= "    <td align=\"right\">";
      $ico_ok  = "<img src=\"img/accept.png\" alt=\"{$label}\" /> ";
      $bottom .= $synHtml->select('name="after" class="submit-actions"', $after_options);
      $bottom .= $synHtml->button("name='cmd' value='".INSERT."' class='action_button'", $ico_ok.'OK');
      $bottom .= "    </td>\n";
      $bottom .= "  </tr>\n";
      $bottom .= "</table>\n";

      echo $bottom;
      echo $synHtml->form_c();

      //initToolbar ( newBtn, saveBtn, removeBtn, switchBtn, refreshBtn, homeBtn, backBtn)
      echo "<script>initToolbar (false,true,false,true,true,true);</script>\n";

      //echo the multilang option
      echo $contenitore->getMultilangBox(2);

      break;

    /**************************************************************************
    *                             MODIFY A ROW
    ***************************************************************************/

    case MODIFY:
      aaHeader($str["modifyrow"],$str["modifyrow_bis"]);
      $synPrimaryKey=stripslashes(urldecode(trim($_REQUEST["synPrimaryKey"])));

      echo $synHtml->form("action=\"$PHP_SELF\" method=\"post\" enctype=\"multipart/form-data\" autocomplete=\"off\"");
      $res=$db->Execute("select * from $synTable where $synPrimaryKey");
      $contenitore->updateValues($res->FetchRow());
      $contenitore->getHtml();

      $after_options = array(
        'exit' => $str['saveandexit'],
        'stay' => $str['save'],
       'clone' => $str['saveandclone'],
        'next' => $str['saveandnext'],
         'new' => $str['saveandadd']
      );
      $label  = $str["modify"];
      $bottom  = "<table id=\"actions\">\n";
      $bottom .= "  <tr>\n";
      $bottom .= "    <td>";
      $ico_off = "<img src=\"img/tool_undo.png\" alt=\"{$label}\" /> ";
      $bottom .= $synHtml->hidden("name='synPrimaryKey' value='".urlencode($synPrimaryKey)."'");
      $bottom .= $synHtml->hidden("name='changeto' value=''");
      $bottom .= $synHtml->hidden("name='default-cmd' value='".CHANGE."'");
      $bottom .= $synHtml->button("name='off' value='' class='cancel_button' onclick='document.location=\"{$PHP_SELF}\"; return false;'", $ico_off.$str["cancel"], 'reset');
      $bottom .= "    </td>\n";
      if ($synLoggedUser->canDelete==1) {
        $ico_del = "<img src=\"img/container_delete.png\" alt=\"{$label}\" /> ";
        $bottom .= "    <td align=\"right\" width=\"80%\"><div class=\"button-wrapper\">";
        $bottom .= $synHtml->button("name='cmd' value='".DELETE."' class='delete_button' onclick=\"return (confirm('{$str["sure_delete"]}'));\"", $ico_del.$str['delete']);
        $bottom .= "    </div></td>\n";
      }
      $bottom .= "    <td align=\"right\">";
      $ico_ok  = "<img src=\"img/accept.png\" alt=\"{$label}\" /> ";
      $bottom .= $synHtml->select('name="after" class="submit-actions"', $after_options);
      $bottom .= $synHtml->button("name='cmd' value='".CHANGE."' class='action_button'", $ico_ok.'OK');
      $bottom .= "    </td>\n";
      $bottom .= "  </tr>\n";
      $bottom .= "</table>\n";

      echo $bottom;
      echo $synHtml->form_c();

      //initToolbar ( newBtn, saveBtn, removeBtn, switchBtn, refreshBtn, homeBtn, backBtn)
      $script = "<script type=\"text/javascript\">\n";
      $script.= "  initToolbar (false, true, true, true, true, true);\n";
      $script.= "  action('removeBtn', 'if (confirm(top.str[\"aa_confirmDel\"])) window.parent.content.document.location=\"content.php?cmd=delrow&synPrimaryKey=".urlencode($synPrimaryKey)."\";');\n";
      $script.= "</script>\n";

      echo $script;

      //echo the multilang option
      echo $contenitore->getMultilangBox(2);

      break;

    /**************************************************************************
    *                             CHANGE A ROW
    ***************************************************************************/

    case CHANGE:

      $synPrimaryKey=urldecode(trim($_POST["synPrimaryKey"]));

      $contenitore->uploadDocument();
      $upd = $contenitore->getUpdateString();
      $ok = true;

      if ($upd!="") {
        $qry = "UPDATE `$synTable` SET $upd WHERE $synPrimaryKey";
        $ok = $db->Execute($qry);
        $ok = $ok && $contenitore->execute_callbacks('update');
      }

      #die('done');
      //controllo errori
      if (!$ok) echo "<script>alert(\"$err\"); history.go(-1);</script>";
      //else echo 'ok';

      //set the next page
      if ($_REQUEST["changeto"]!='') {
        $after = 'changelang';
      }

      switch ($after) {
        case 'changelang': // salva & cambia lingua
          resetClone($synTable);
          $jumpTo = $PHP_SELF."?cmd=".MODIFY."&synPrimaryKey={$synPrimaryKey}&synSetLang=".$_REQUEST["changeto"];
          break;

        case 'exit': // salva & torna alla lista
          resetClone($synTable);
          $jumpTo = $PHP_SELF;
          break;

        case 'clone': // salva & duplica
          unset($_POST['id']); //altrimenti continua a lavorare sullo stesso record
          $_SESSION[$synTable.'_clone'] = serialize($_POST);
          $jumpTo = $PHP_SELF."?cmd=".ADD;
          break;

        case 'next': // salva & prossimo
          resetClone($synTable);
          $nextqry = "SELECT `{$synTable}`.id FROM `{$synTable}` WHERE id>".intval($_POST['id'])." ORDER BY id LIMIT 0,1";
          $res = $db->Execute($nextqry);
          if($arr = $res->FetchRow()){
            $jumpTo = $PHP_SELF."?cmd=".MODIFY."&synPrimaryKey=".urlencode("`id`=\"{$arr['id']}\"");
          } else {
            $jumpTo = $PHP_SELF; //non esiste un record successivo, torno alla lista
          }
          break;

        case 'new': // salva & nuovo
          resetClone($synTable);
          $jumpTo = $PHP_SELF."?cmd=".ADD;
          break;

        case 'stay': // salva & continua
        default:
          resetClone($synTable);
          $jumpTo = $PHP_SELF."?cmd=".MODIFY."&synPrimaryKey={$synPrimaryKey}";
          break;
      }
      break;

    /**************************************************************************
    *                             INSERT A ROW
    ***************************************************************************/

    case INSERT:
      $synPrimaryKey=$contenitore->getKeyURLString();
      $err = '';
      //upload available documents
      $contenitore->uploadDocument();

      $contenitore->execute_callbacks('insert');
      //execute insert qry
      $qry = "INSERT INTO $synTable (".$contenitore->getFieldsString().") VALUES (".$contenitore->getInsertString().")";
      $err = $res = $db->Execute($qry);

      //$insertId = $db->Insert_Id();
      $insertId = $db->Insert_Id();

      // DA IMPLEMENTARE
      //$err = $err && $contenitore->execute_callbacks('insert');

      //error check
      if (!$err) {
        //echo "<script>alert(\"$err\"); history.go(-1);</script>";
        echo "<script>alert(\"$err\");</script>";
      }

      //set the next page
      if ($_REQUEST["changeto"]!='') {
        $after = 'changelang';
      }

      switch ($after) {
        case 'changelang': // salva & cambia lingua
          resetClone($synTable);
          $jumpTo = $PHP_SELF."?cmd=".MODIFY."&synPrimaryKey={$synPrimaryKey}&synSetLang=".$_REQUEST["changeto"];
          break;

        case 'exit': // salva & torna alla lista
          resetClone($synTable);
          $jumpTo = $PHP_SELF;
          break;

        case 'clone': // salva & duplica
          unset($_POST['id']); //altrimenti continua a lavorare sullo stesso record
          $_SESSION[$synTable.'_clone'] = serialize($_POST);
          $jumpTo = $PHP_SELF."?cmd=".ADD;
          break;

        case 'new': // salva & nuovo
          resetClone($synTable);
          $jumpTo = $PHP_SELF."?cmd=".ADD;
          break;

        case 'stay': // salva & continua
        default:
          resetClone($synTable);
          $jumpTo = $PHP_SELF."?cmd=".MODIFY."&synPrimaryKey={$synPrimaryKey}";
          break;
      }

      break;

    /**************************************************************************
    *                             DELETE A ROW
    ***************************************************************************/

    case DELETE:

      $synPrimaryKey=stripslashes(urldecode($_REQUEST["synPrimaryKey"]));
      $res=$db->Execute("select * from $synTable where $synPrimaryKey");

      //delete if the user has the owner permission
      $arr=$res->FetchRow();
      $contenitore->updateValues($arr);
      //if ($contenitore->ownerField=="" OR in_array($arr[$contenitore->ownerField],$_SESSION["synGroupChild"]) OR $arr[$contenitore->ownerField]==0) {
      $canDelete=$contenitore->isDeletable();
      if ($canDelete===true) {
        $contenitore->deleteDocument();
        $res = $db->Execute("DELETE FROM $synTable WHERE $synPrimaryKey" );
        $contenitore->execute_callbacks('delete');

      } else {
        echo "<script>alert(\"".$canDelete."\");</script>";
      }

      //set the next page
      $jumpTo=$PHP_SELF;

      break;

    /**************************************************************************
    *                        DELETE MULTIPLE ROWS
    ***************************************************************************/

    case MULTIPLEDELETE:
      //TODO: attenzione alle chiavi. Prende solamente l'id!!!!!!!!!
      $i=0;
      if (isset($checkrow)) {
      foreach ($checkrow as $id) {
          $key=urldecode($id);
          $res=$db->Execute("select * from $synTable where $key");
          $contenitore->updateValues($res->FetchRow());

          $canDelete=$contenitore->isDeletable();
          if ($canDelete===true) {
            @$contenitore->deleteDocument();
            //$res=$db->Execute("DELETE FROM $synTable WHERE $synPrimaryKey" );
            $res=$db->Execute("DELETE FROM $synTable WHERE $key" );
            $i++;
          } else {
            echo "<script>alert(\"Row $key: ".$canDelete."\");</script>";
          }

        }
        echo "<script>alert(\"$i ".$str["row_deleted"]."\");</script>";
      }

      //set the next page
      $jumpTo=$PHP_SELF;

      break;


    /**************************************************************************
    *                        RPC - set values
    ***************************************************************************/
    case RPC:
      global $contenitore, $debug;

      $synPrimaryKey=stripslashes($_GET["synPrimaryKey"]);
      $field=$_GET["field"];
      $value=$_GET["value"];
      $synTable=$contenitore->getTable();

      $qry = "update $synTable set `$field`='$value' where $synPrimaryKey";
      $err = $db->Execute($qry);

      //controllo errori
      if (!$err) echo "<script>alert(\"".htmlentities($err)."\");</script>";
    break;


    /**************************************************************************
    *                             LIST OF THE ROWS
    ***************************************************************************/
    case "":
      global $treeFrame;
      echo "<!-- *** inclusione di schema.php *** -->\n";

      resetClone($synTable);
      // TO DO: questo sbianca la ricerca. trovare una soluzione!!!
      //unset($_POST); //in caso di edit abortito

      //Change the rows mod and del button
      # EDIT button
      if ($synLoggedUser->canModify==1) {
        $label  = $str["modify"];
        $button = "<a href=\"%s&amp;synPrimaryKey=%s\" class=\"button\" title=\"{$label}\">";
        $button.= "<img src=\"img/container_edit.png\" alt=\"{$label}\" /></a>";
        $contenitore->buttons[$button]="?cmd=".MODIFY;
      }

      # DELETE button
      if ($synLoggedUser->canDelete==1) {
        $label  = $str["delete"];
        $button = "<a href=\"%s&amp;synPrimaryKey=%s\" class=\"button\" title=\"{$label}\" ";
        $button.= "onclick=\"return (confirm('{$str["sure_delete"]}'));\">";
        $button.= "<img src=\"img/container_delete.png\" alt=\"{$label}\" /></a>";
        $contenitore->buttons[$button]="?cmd=".DELETE;
      }

      //perfrom the qry
      $qry = addQueryWhere("select `$synTable`.* from `$synTable`");

      $pager = new synPager($db,'syntax',"content.php", "content", true);//$db, $id, $targetFile, $targetFrame, $showpagelink
      $res=$pager->Execute($qry,$synRowsPerPage);
      if ($treeFrame=="true") {$contenitore->getTree($qry);/*die();*/}
      else {
        if ($contenitore->treeExists()===true) echo "<script type=\"text/javascript\">\n  parent.refreshTreeFrame();\n  parent.openTreeFrame();\n</script>\n";
        echo "<form action=\"?cmd=".MULTIPLEDELETE."\" method=\"post\" style=\"margin: 0px;\">\n";
        echo "  <table id=\"mainTable\" cellpadding=\"0\" cellspacing=\"0\">\n";
        echo "    <thead>\n";
        echo "      <tr>\n".$contenitore->getHeader()."      </tr>\n";
        echo "    </thead>\n";
        echo "    <tbody>\n";
        while ($arr = $res->FetchRow()) {
          $contenitore->updateValues($arr);
          echo "      <tr>\n".$contenitore->getRow()."      </tr>\n";
        }
        echo "    </tbody>\n";
        echo "  </table>\n</form>\n";

        if ($synLoggedUser->canDelete==1) {
          echo "  <p class=\"selezione\">\n";
          echo "    <a href=\"javascript:void(0)\" onfocus=\"markAllRows('mainTable'); return false;\" accesskey=\"s\"><img alt=\"freccia\" src=\"img/container_arrow.png\" /> ".$str["selectdeselect"]." [s]</a>\n";
          echo "  </p>\n";
        }

        echo "<script type=\"text/javascript\">\n"; // start page scripts
          //paging system
          echo "  var arrpage = ['".implode("','", explode("  ", $pager->index))."'];\n";
          echo "  paging('".$pager->firstPage."','".$pager->prevPage."','".$pager->nextPage."','".$pager->lastPage."', arrpage, '".$pager->footer."');\n";
          //initToolbar ( newBtn, saveBtn, removeBtn, switchBtn, refreshBtn, homeBtn, backBtn)
          echo "  initToolbar (".$synLoggedUser->canInsert.",0,".$synLoggedUser->canDelete.",1,1,0);\n";
        echo "</script>\n"; // end page scripts

        //update the columns field for searching
        echo $contenitore->getColumnSearch();

        //echo the multilang option
        echo $contenitore->getMultilangBox(1);
      }

      break;
  }

  //jump to the next page
  if (isset($jumpTo)) {
    js_location($jumpTo);
  }

  function aaHeader($tit,$tit2="") {
    echo "<div id=\"formHeader\">\n";
    echo "  <h4>$tit</h4>\n";
    echo "  <div >$tit2</div>\n";
    echo "</div>\n";
  }
  
  function resetClone($synTable){
    if(!isset($_SESSION)) session_start();
    if(isset($_SESSION[$synTable.'_clone']))
      $_SESSION[$synTable.'_clone'] = '';
  }
?>
