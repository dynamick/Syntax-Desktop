<?php

if(!isset($_SESSION))
  session_start();
if(!isset($_REQUEST['cmd']))
  $_REQUEST['cmd'] = '';

// auto-load delle classi istanziate
function __autoload($class) {
  global $synAbsolutePath;
  require_once $synAbsolutePath.'/admin/modules/aa/classes/'.$class.'.php';
}

  //definizione variabili globali
  $synContainer = isset($_REQUEST["aa_service"]) ? $_REQUEST["aa_service"] : $_SESSION["aa_service"];
  $buttons=array();

  //creo il contenitore
  if(!isset($db))
    include_once ("../../../config/cfg.php"); //if RPC

  $res = $db->Execute("SELECT * FROM aa_services WHERE id='{$synContainer}'");
  $arr = $res->FetchRow();
  $synDb=str_replace(" ","_",strToLower($arr["syntable"]));
  $contenitore = synContainer::getInstance($synDb, $buttons, true, $arr["name"], $arr["description"],$arr["multilang"]);

  $dbSync=$arr["dbsync"];

  //ci aggiungo gli elementi
  $res=$db->Execute("SELECT se.*, e.classname as classname FROM aa_services_element se INNER JOIN aa_element e ON se.type=e.id WHERE container='{$synContainer}' order by `order`, `id`");
  $count = 0;
  while ($arr=$res->FetchRow()) {
    $obj[$count] = new $arr["classname"]($arr["name"], $arr["value"], translateDesktop($arr["label"]), $arr["size"], translateDesktop($arr["help"]));
    $obj[$count]->isListable($arr["isvisible"], $arr["label"], $arr["iseditable"]);
  //$obj[$count]->setContainer($contenitore);

    if ($arr["path"] != "")
      $obj[$count]->setPath($arr["path"]);
    if ($arr["iskey"] == 1)
      $obj[$count]->setKey(true);
    if ($arr["ismultilang"] == 1)
      $obj[$count]->setMultilang(true);

    if ( $arr["qry"]!=''
      && ( !isset($_REQUEST[$arr['name']]) || $_REQUEST[$arr["name"]] == '' )
      ){
      $obj[$count]->setQry($arr["qry"]);
      $obj[$count]->setPath($arr["path"]);
    }

    $contenitore->checkJoins($arr["id"]);
    $contenitore->addElement($obj[$count]);
    $count++;
  }

  //sincronizzo il db con gli elementi aggiunti al contenitore
  if ($dbSync=="1" && $_REQUEST["cmd"]=='')
    $contenitore->dbSynchronize();

  //----------------------------------------------------------------------------
  //                               INIZIALIZZAZIONE
  //----------------------------------------------------------------------------
  //variabili globali
  $PHP_SELF = $_SERVER['PHP_SELF'];

  $cmd = '';
  if (isset($_REQUEST['cmd']))
    $cmd = $_REQUEST['cmd'];
  if (isset($_POST['default-cmd']) && $cmd=='')
    $cmd = $_POST['default-cmd']; // FckEditor/toolbar save button

  if (isset($_POST['after']))
    $after = $_POST['after'];
  else
    $after="stay";

  if (!defined("RPC"))
    define("RPC", "rpcfunction");

  //----------------------------------------------------------------------------
  //                                   FUNZIONI
  //----------------------------------------------------------------------------


  //if some search qry is done, add the constraint to the qry string
  function addQueryWhere ($qry) {
    global $aa_qry, $synTable, $db, $aa_group_services, $contenitore, $treeFrame;

    //remove session to see the entire list
    if (isset($_GET["aa_search_clean"]))
      unset($_SESSION['aa_qry']);

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
        $field = "aa_translation`.`".$_SESSION['aa_CurrentLangInitial'];
      }

      if (strpos($qry,"WHERE")===false) $qry.=" WHERE ";
      else $qry.=" AND ";

      if ($type=="=") $qry.="`".$field."` = '".$keyword."' ";
      if ($type=="like") $qry.="`".$field."` LIKE '%".$keyword."%' ";
      if ($type==">") $qry.="`".$field."` > '".$keyword."' ";
      if ($type=="<") $qry.="`".$field."` < '".$keyword."' ";
      if ($type=="acceso") $qry.=" `".$field."` <> \"\" ";
      if ($type=="spento") $qry.=" `".$field."`=\"\" ";

      //session_register("aa_qry");
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




  // new queryBuilder
  function buildQuery($contenitore, $db) {
    global $treeFrame;

    if (isset($_GET['aa_search_clean']))
      unset($_SESSION['aa_qry']);

    $query = new queryBuilder( 'select' );
    $table = $query->addTable( $contenitore->getTable() );

    foreach( $contenitore->element AS $e ){
      if ($e->list || $e->is_key) {
        if ($e->multilang == 1) {
          $translation = $query->addJoin( 'aa_translation' );
          $translation->setOn( $table->setField($e->name), $translation->setField('id') );
          $translation->setMode( 'LEFT' );
          $translation->addField( $_SESSION['aa_CurrentLangInitial'], $e->name.'aatrans' );
        }
        if (isset($e->table_join)) {
          $tjoin = $query->addJoin( $table->getName() . '-' . $e->table_join );
          $tjoin->setOn( $tjoin->setField( 'id_' . $table->getName()), $table->setField('id') );
          $tjoin->addField( 'id_' . $e->table_join );
          $query->addWhereClause( $tjoin->setField( $e->name ), 1 );

        } else {
          $table ->addField( $e->name );
        }
      }
    }

    if ( !isset($_SESSION['aa_joinStack']) ) {
      $user = getSynUser();
      $sql = <<<EOQ
      SELECT gs.filter
        FROM aa_group_services gs
  INNER JOIN aa_groups g ON gs.group = g.id, aa_users u
       WHERE u.id_group = g.id
         AND u.id = '{$user}'
         AND gs.id = '{$_SESSION['aa_group_services']}'
EOQ;
      $res = $db->Execute($sql);
      $arr = $res->FetchRow();
      if ( !empty($arr['filter']) ){
        $query->addWhere( $arr['filter'] );
      }
    } else {
      $stackKeys    = array_keys( $_SESSION['aa_joinStack'] );
      $stackLastKey = array_pop( $stackKeys );
      $join         = new synJoin( $_SESSION['aa_joinStack'][$stackLastKey]['idjoin'] );
      $toElmName    = $join->toElmName;
      $value        = $_SESSION['aa_joinStack'][$stackLastKey]['value'];

      $query->addWhereClause( $table->setField($toElmName), $value );
    }

    //add tree permission
    $treeQry = $contenitore->getTreePermission();
    if (!empty($treeQry))
      $query->addWhere( $treeQry );

    //check owner field...
    $owner = ( isset($contenitore->ownerField) )
           ? $contenitore->ownerField
           : null;
    if (!empty($owner) && $treeFrame != 'true') { // ...unless it's a tree
      $field = $table->setField($owner);
      $clause = array(
        "{$field} IN (".implode(',', $_SESSION['synGroupChild']).')',
        "{$field} IS NULL",
        "{$field} = ''"
      );
      // add owner clause in a single stack
      $query->addWhere( '(' . implode(' OR ', $clause) . ')');
    }

    //$query->debug();
    //echo 'POST: <pre>', print_r($_POST), '</pre>';
    return $query; //->getQuery();
  }




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

  if (!getSynUser()) {
    echo "<script>alert('Sessione scaduta. Prego riconnettersi.'); top.location.href='../../';</script>";
    die("sessione scaduta");
  }

  //include custom function. The naming convention is: path/to/modules/aa/custom/$synTable.php
  //if ($cmd != JSON && file_exists($synAbsolutePath.$synAdminPath."/modules/aa/custom/".$synTable.".php")===true) {
  if (file_exists($synAbsolutePath.$synAdminPath."/modules/aa/custom/".$synTable.".php")===true) {
    include ($synAbsolutePath.$synAdminPath."/modules/aa/custom/".$synTable.".php");
  }


  switch($cmd) {

        ////////////////////////////////////////////////////////////////////////////
       //                                                                        //
      //                            ADD A ROW                                   //
     //                                                                        //
    ////////////////////////////////////////////////////////////////////////////

    case ADD:
      #echo "<script src=\"../../includes/js/tooltip/tooltip.js\"></script>";
      aaHeader( $str["insertrow"], $str["insertrow_bis"] );

      if(isset($_SESSION[$synTable.'_clone']) and trim($_SESSION[$synTable.'_clone'])!=""){
        $data = unserialize($_SESSION[$synTable.'_clone']);
        if(is_array($data)){
          foreach($data as $k=>$v){
            if(is_array($v)){
              $data[$k] = implode($v, '|'); // in caso di multicheck
            }
          }
          $contenitore->updateValues($data); // fracco i valori in sessione nel contenitore
        }
      }

      echo $synHtml->form("action='$PHP_SELF' method='POST' enctype='multipart/form-data' onsubmit='javascript: true || loading();' autocomplete=\"off\"");
      $contenitore->getHtml();

      $hiddens  = $synHtml->hidden("name='changeto' value=''");
      $hiddens .= $synHtml->hidden("name='default-cmd' value='".INSERT."'");

      $after_options = array(
        'exit' => $str['saveandexit'],
        'stay' => $str['save'],
       'clone' => $str['saveandclone'],
         'new' => $str['saveandadd']
      );
      $actions    = $synHtml->select('name="after" class="form-control"', $after_options);
      $ok_button  = $synHtml->button("name='cmd' value='".INSERT."' class='btn btn-success'", '<i class="fa fa-check"></i> OK');
      $del_button = null;

      $bottom = <<<EOBOTTOMBAR
        <nav class="navbar form-toolbar navbar-fixed-bottom">
          <div class="container-fluid">
            <a href="{$PHP_SELF}" class="btn btn-primary navbar-btn">
              <i class="fa fa-mail-reply"></i> {$str["cancel"]}
            </a>
            <div class="navbar-form navbar-right">
              <div class="input-group">
                {$actions}
                <span class="input-group-btn">{$ok_button}</span>
              </div>
            </div>
            <div class="navbar-form navbar-right">
              {$del_button}
            </div>
          </div>
        </nav>
EOBOTTOMBAR;

      echo $hiddens;
      echo $bottom;
      echo $synHtml->form_c();



      //initToolbar ( newBtn, saveBtn, removeBtn, switchBtn, refreshBtn, homeBtn, backBtn)
      echo "<script>initToolbar (false,true,false,true,true,true);</script>\n";

      //echo the multilang option
      echo $contenitore->getMultilangBoxNew(2);

      break;


        ////////////////////////////////////////////////////////////////////////
       //                                                                    //
      //                          INSERT A ROW                              //
     //                                                                    //
    ////////////////////////////////////////////////////////////////////////

    case INSERT:
      $synPrimaryKey = $contenitore->getKeyURLString();
      $err = '';
      //upload available documents
      $contenitore->uploadDocument();


      //execute insert qry
      $qry = "INSERT INTO `{$synTable}` ({$contenitore->getFieldsString()}) VALUES ({$contenitore->getInsertString()})";
      $err = $res = $db->Execute($qry);

      $insertId = $db->Insert_Id();
      $contenitore->setKeyValue($insertId);

      $err = $err && $contenitore->execute_callbacks('insert');

      //error check
      if (!$err) {
        //echo "<script>alert(\"$err\"); history.go(-1);</script>";
        echo "<script>alert(\"{$err}\");</script>";
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


        ////////////////////////////////////////////////////////////////////////////
       //                                                                        //
      //                            MODIFY A ROW                                //
     //                                                                        //
    ////////////////////////////////////////////////////////////////////////////

    case MODIFY:
      aaHeader($str["modifyrow"], $str["modifyrow_bis"]);
      $synPrimaryKey = stripslashes(urldecode(trim($_REQUEST["synPrimaryKey"])));

      echo $synHtml->form("action=\"{$PHP_SELF}\" method=\"post\" enctype=\"multipart/form-data\" autocomplete=\"off\"");
      $res = $db->Execute("SELECT * FROM `{$synTable}` WHERE {$synPrimaryKey}");
      $contenitore->updateValues($res->FetchRow());
      $contenitore->getHtml();

      $hiddens  = $synHtml->hidden("name='synPrimaryKey' value='".urlencode($synPrimaryKey)."'");
      $hiddens .= $synHtml->hidden("name='changeto' value=''");
      $hiddens .= $synHtml->hidden("name='default-cmd' value='".CHANGE."'");
      //$hiddens .= $synHtml->button("name='off' value='' class='cancel_button' onclick='document.location=\"{$PHP_SELF}\"; return false;'", $ico_off.$str["cancel"], 'reset');

      $after_options = array(
        'exit' => $str['saveandexit'],
        'stay' => $str['save'],
       'clone' => $str['saveandclone'],
        'next' => $str['saveandnext'],
         'new' => $str['saveandadd']
      );
      $actions    = $synHtml->select('name="after" class="form-control"', $after_options);
      $ok_button  = $synHtml->button("name='cmd' value='".CHANGE."' class='btn btn-success'", '<i class="fa fa-check"></i> OK');
      $del_button = ($synLoggedUser->canDelete == 1)
                  ? $synHtml->button("name='cmd' value='".DELETE."' class='btn btn-danger' onclick=\"return (confirm('{$str["sure_delete"]}'));\"", '<i class="fa fa-times"></i> '.$str['delete'])
                  : null;

      $bottom = <<<EOBOTTOMBAR
        <nav class="navbar form-toolbar navbar-fixed-bottom">
          <div class="container-fluid">
            <a href="{$PHP_SELF}" class="btn btn-primary navbar-btn">
              <i class="fa fa-mail-reply"></i> {$str["cancel"]}
            </a>
            <div class="navbar-form navbar-right">
              <div class="input-group">
                {$actions}
                <span class="input-group-btn">{$ok_button}</span>
              </div>
            </div>
            <div class="navbar-form navbar-right">
              {$del_button}
            </div>
          </div>
        </nav>
EOBOTTOMBAR;

      echo $hiddens;
      echo $bottom;
      echo $synHtml->form_c();


      //initToolbar ( newBtn, saveBtn, removeBtn, switchBtn, refreshBtn, homeBtn, backBtn)
      $script = "<script type=\"text/javascript\">\n";
      $script.= "  initToolbar (false, true, true, true, true, true);\n";
      $script.= "  action('removeBtn', 'if (confirm(top.str[\"aa_confirmDel\"])) window.parent.content.document.location=\"content.php?cmd=delrow&synPrimaryKey=".urlencode($synPrimaryKey)."\";');\n";
      $script.= "</script>\n";

      echo $script;

      //echo the multilang option
      echo $contenitore->getMultilangBoxNew(2);

      break;


        ////////////////////////////////////////////////////////////////////////
       //                                                                    //
      //                          UPDATE A ROW                              //
     //                                                                    //
    ////////////////////////////////////////////////////////////////////////

    case CHANGE:

      $synPrimaryKey = urldecode(trim($_POST["synPrimaryKey"]));

      $contenitore->uploadDocument();
      $upd = $contenitore->getUpdateString();
      $ok = true;

      if ($upd!="") {
        $qry = "UPDATE `{$synTable}` SET {$upd} WHERE {$synPrimaryKey}";
        $ok = $db->Execute($qry);
        $ok = $ok && $contenitore->execute_callbacks('update');
      }

      //echo "<a href=\"{$PHP_SELF}?cmd=".MODIFY."&synPrimaryKey={$synPrimaryKey}\">avanti</a>"; die();

      //controllo errori
      if (!$ok)
        echo "<script>alert(\"{$ok}\"); history.go(-1);</script>";
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



        ////////////////////////////////////////////////////////////////////////
       //                                                                    //
      //                          DELETE A ROW                              //
     //                                                                    //
    ////////////////////////////////////////////////////////////////////////


    case DELETE:

      $synPrimaryKey = stripslashes(urldecode($_REQUEST["synPrimaryKey"]));
      $res = $db->Execute("SELECT * FROM {$synTable} WHERE {$synPrimaryKey}");

      //delete if the user has the owner permission
      $arr = $res->FetchRow();
      $contenitore->updateValues($arr);
      //if ($contenitore->ownerField=="" OR in_array($arr[$contenitore->ownerField],$_SESSION["synGroupChild"]) OR $arr[$contenitore->ownerField]==0) {
      $canDelete = $contenitore->isDeletable();
      if ($canDelete===true) {
        $contenitore->deleteDocument();
        $contenitore->execute_callbacks('delete');

        $res = $db->Execute("DELETE FROM {$synTable} WHERE {$synPrimaryKey}" );

      } else {
        echo "<script>alert(\"{$canDelete}\");</script>";
      }

      //set the next page
      $jumpTo = $PHP_SELF;

      break;


        ////////////////////////////////////////////////////////////////////////
       //                                                                    //
      //                          DELETE X ROWS                             //
     //                                                                    //
    ////////////////////////////////////////////////////////////////////////

    case MULTIPLEDELETE:
      //TODO: attenzione alle chiavi. Prende solamente l'id!!!!!!!!!
      $i = 0;
      if (isset($checkrow)) {
        foreach ($checkrow as $id) {
          $key = urldecode($id);
          $res = $db->Execute("SELECT * FROM `{$synTable}` WHERE {$key}");
          $contenitore->updateValues($res->FetchRow());

          $canDelete = $contenitore->isDeletable();
          if ($canDelete===true) {
            @$contenitore->deleteDocument();
            $res = $db->Execute("DELETE FROM `{$synTable}` WHERE {$key}" );
            $i++;
          } else {
            echo "<script>alert(\"Row {$key}: {$canDelete}\");</script>";
          }
        }
        echo "<script>alert(\"{$i} {$str['row_deleted']}\");</script>";
      }

      //set the next page
      $jumpTo = $PHP_SELF;

      break;


        ////////////////////////////////////////////////////////////////////////
       //                                                                    //
      //                 RPC - set values via ajax                          //
     //                                                                    //
    ////////////////////////////////////////////////////////////////////////

    case RPC:
      global $contenitore, $debug;

      $synPrimaryKey = stripslashes($_GET["synPrimaryKey"]);
      $field = $_GET["field"];
      $value = $_GET["value"];
      $synTable = $contenitore->getTable();

      $qry = "UPDATE {$synTable} SET `{$field}`='{$value}' WHERE {$synPrimaryKey}";
      $err = $db->Execute($qry);

      //controllo errori
      if (!$err)
        echo "<script>alert(\"".htmlentities($err)."\");</script>";
    break;


        ////////////////////////////////////////////////////////////////////////
       //                                                                    //
      //                                                                    //
     //                                                                    //
    ////////////////////////////////////////////////////////////////////////

    case JSON:
      if ($synLoggedUser->canModify==1) {
        $label  = $str['modify'];
        $button = "<a href=\"%s&amp;synPrimaryKey=%s\" class=\"btn btn-xs btn-success\" title=\"{$label}\">";
        $button.= "<i class=\"fa fa-edit\"></i></a>";
        $contenitore->buttons[$button] = "?cmd=".MODIFY;
      }

      # DELETE button
      if ($synLoggedUser->canDelete==1) {
        $label  = $str['delete'];
        $button = "<a href=\"%s&amp;synPrimaryKey=%s\" class=\"btn btn-xs btn-danger\" title=\"{$label}\" ";
        $button.= "onclick=\"return (confirm('{$str["sure_delete"]}'));\">";
        $button.= "<i class=\"fa fa-trash\"></i></a>";
        $contenitore->buttons[$button] = "?cmd=".DELETE;
      }

      if (isset($_GET['limit'])) {
        $limit = $_GET['limit'];
        unset( $_REQUEST['limit'] ); // TODO: synElement prende i valori in request. fixare
      } else {
        $limit = 10;
      }

      if (isset($_GET['offset'])) {
        $offset = $_GET['offset'];
        unset( $_REQUEST['offset'] ); // TODO: synElement prende i valori in request. fixare
      } else {
        $offset = 0;
      }

      if (isset($_GET['sort'])) {
        $sort = $_GET['sort'];
        unset( $_REQUEST['sort'] ); // TODO: synElement prende i valori in request. fixare
      } else {
        $sort = '';
      }

      if (isset($_GET['order'])) {
        $order_dir = $_GET['order'];
        unset( $_REQUEST['order'] ); // TODO: synElement prende i valori in request. fixare
      } else {
        $order_dir = 'asc';
      }

      if (isset($_GET['search'])) {
        $search = $_GET['search'];
        unset( $_REQUEST['search'] ); // TODO: synElement prende i valori in request. fixare
      } else {
        $search = '';
      }

      // instance query
      $qry = buildQuery( $contenitore, $db );
      if (empty($search))
        $qry->setLimit( $limit, $offset );
      if (!empty($sort))
        $qry->addOrderBy( $sort, $order_dir );
      //if (!empty($search))
        //$qry->addSearchClause( $search );

      // get row total
      $tot = $db->Execute( $qry->getCountQuery() );
      list($count) = $tot->fetchRow();

      // get subset results
      $res = $db->execute( $qry->getQuery() );
      $data = array();
      while ($row = $res->FetchRow()) {
        $contenitore->updateValues( $row );
        $hash = $contenitore->getJsonRow();
        if (empty($search))
          $data[] = $hash;
        else {
          $str_values = strip_tags( implode(' ', $hash) );
          if ( stripos( $str_values, $search ) !== false)
            $data[] = $hash;
        }
      }
      if (!empty($search)) {
        $count = count($data);
        if ($offset>0)
          $data = array_slice( $data, $offset, $limit );
      }

      //echo '<pre>', print_r($data), '</pre>';
      //$qry->debug();
      // populate results json
      $json = array(
        'total' => $count,
        'rows' => $data
        );

      echo json_encode( $json );
    break;


        ////////////////////////////////////////////////////////////////////////
       //                                                                    //
      //                          LIST ALL ROWS                             //
     //                                                                    //
    ////////////////////////////////////////////////////////////////////////

    case 'OLD':
      global $treeFrame;
      echo "<!-- *** inclusione di schema.php *** -->\n";

      resetClone($synTable);
      // TO DO: questo sbianca la ricerca. trovare una soluzione!!!
      //unset($_POST); //in caso di edit abortito

      //Change the rows mod and del button
      # EDIT button
      if ($synLoggedUser->canModify==1) {
        $label  = $str['modify'];
        $button = "<a href=\"%s&amp;synPrimaryKey=%s\" class=\"btn btn-xs btn-success\" title=\"{$label}\">";
        //$button.= "<img src=\"img/container_edit.png\" alt=\"{$label}\" /></a>";
        $button.= "<i class=\"fa fa-edit\"></i></a>";
        $contenitore->buttons[$button] = "?cmd=".MODIFY;
      }

      # DELETE button
      if ($synLoggedUser->canDelete==1) {
        $label  = $str['delete'];
        $button = "<a href=\"%s&amp;synPrimaryKey=%s\" class=\"btn btn-xs btn-danger\" title=\"{$label}\" ";
        $button.= "onclick=\"return (confirm('{$str["sure_delete"]}'));\">";
        //$button.= "<img src=\"img/container_delete.png\" alt=\"{$label}\" /></a>";
        $button.= "<i class=\"fa fa-trash\"></i></a>";
        $contenitore->buttons[$button] = "?cmd=".DELETE;
      }

      //perfrom the qry
      $qry = addQueryWhere("SELECT `{$synTable}`.* FROM `{$synTable}`");

      $pager = new synPager($db, 'syntax', 'content.php', 'content', true, true, "pager_{$synTable}");
      $res = $pager->Execute($qry, $synRowsPerPage);

      if ($treeFrame == "true") {
        $contenitore->getTree($qry);
        // die();
      } else {
        if ($contenitore->treeExists()===true) {
          echo "<script type=\"text/javascript\">\n"
             . "  parent.refreshTreeFrame();\n"
             . "  parent.openTreeFrame();\n"
             . "</script>\n";
        }
        echo "<form action=\"?cmd=".MULTIPLEDELETE."\" method=\"post\" style=\"margin: 0px;\">\n"
           . "  <table id=\"mainTable\" class=\"table table-striped table-condensed\">\n"
           . "    <thead>\n"
           . "      <tr>\n".$contenitore->getHeader()."      </tr>\n"
           . "    </thead>\n"
           . "    <tbody>\n";
        while ($arr = $res->FetchRow()) {
          $contenitore->updateValues($arr);
          echo "      <tr>\n".$contenitore->getRow()."      </tr>\n";
        }
        echo "    </tbody>\n"
           . "  </table>\n</form>\n";

        if ($synLoggedUser->canDelete==1) {
          echo <<<EOSCRIPT
          <script src="../../assets/js/jquery.selectable-list.js"></script>
          <script>
          $(function(){
            $("table").selectableList();
          });
          </script>
EOSCRIPT;
        }

        echo "<script type=\"text/javascript\">\n" // start page scripts
           . "  var arrpage = ['".implode("','", explode("  ", $pager->index))."'];\n"
           . "  paging('".$pager->firstPage."','".$pager->prevPage."','".$pager->nextPage."','".$pager->lastPage."', arrpage, '".$pager->footer."');\n"
            //  initToolbar ( newBtn, saveBtn, removeBtn, switchBtn, refreshBtn, homeBtn, backBtn)
           . "  initToolbar (".$synLoggedUser->canInsert.", 0, ".$synLoggedUser->canDelete.", 1, 1, 0);\n"
           . "</script>\n"; // end page scripts

        //update the columns field for searching
        echo $contenitore->getColumnSearch();

        //echo the multilang option
        echo $contenitore->getMultilangBoxNew(1);
      }

      break;

        ////////////////////////////////////////////////////////////////////////
       //                                                                    //
      //                          LIST ALL ROWS                             //
     //                                                                    //
    ////////////////////////////////////////////////////////////////////////

    case '':
      global $treeFrame;

      resetClone($synTable);
      // TO DO: questo sbianca la ricerca. trovare una soluzione!!!
      //unset($_POST); //in caso di edit abortito

      if ($synLoggedUser->canModify==1) {
        $label  = $str['modify'];
        $contenitore->buttons[$label] = MODIFY;
      }

      # DELETE button
      if ($synLoggedUser->canDelete==1) {
        $label  = $str['delete'];
        $contenitore->buttons[$label] = DELETE;
      }

      //perform the qry
      $qry = addQueryWhere("SELECT `{$synTable}`.* FROM `{$synTable}`");
      $res = $db->Execute( $qry );
      //echo 'qry: '.$qry.'<br>';

      /*
      $qry = buildQuery( $contenitore, $db );
      $res = $db->execute( $qry->getQuery() );
      echo 'qry: '.$qry->getQuery().'<br>';
      */

      if ($treeFrame == "true") {
        //$contenitore->getTree2( $qry );
        $contenitore->getTree( $qry );

      } else {
        if ($contenitore->treeExists()===true) {
          echo "<script type=\"text/javascript\">\n"
             . "  parent.refreshTreeFrame();\n"
             . "  parent.openTreeFrame();\n"
             . "</script>\n";
        }
        $dir = (isset($_SESSION['aa_order_direction']) && strpos($_SESSION['aa_order_direction'], 'DESC'))
             ? 'desc'
             : 'asc' ;
        $header = $contenitore->getJsonHeader();
        $table = <<<EOTABLE
        <div id="filter-bar"></div>
        <table id="mainTable"
          class="table table-striped table-condensed"
          data-url="getData.php?cmd=getjson"
          data-click-to-select="false"
          data-show-filter="true"
          data-show-refresh="true"
          data-show-toggle="true"
          data-show-columns="true"
          data-search="true"
          data-sort-name="{$_SESSION['aa_order']}"
          data-sort-order="{$dir}"
          data-side-pagination="server"
          data-pagination="true"
          data-page-list="[10, 20, 50, 100]"
          data-icons-prefix="fa">
          <thead>
            <tr>{$header}</tr>
          </thead>
        </table>
EOTABLE;

        echo aaHeader( translateDesktop($contenitore->title), translateDesktop($contenitore->description) );
        echo $table;

        //echo the multilang option
        echo $contenitore->getMultilangBoxNew(1);
      }
      break;

  }

  //jump to the next page
  if (isset($jumpTo)) {
    js_location($jumpTo);
  }

  function aaHeader($title, $title2='') {
    $header = <<<EOHEADER
    <div id="formHeader" class="page-header">
      <h2>
        <span data-placement="right" data-toggle="tooltip" data-original-title="{$title2}">{$title}</span>
      </h2>
    </div>
EOHEADER;
    echo $header;
  }

  function resetClone($synTable){
    if(!isset($_SESSION)) session_start();
    if(isset($_SESSION[$synTable.'_clone']))
      $_SESSION[$synTable.'_clone'] = '';
  }

?>