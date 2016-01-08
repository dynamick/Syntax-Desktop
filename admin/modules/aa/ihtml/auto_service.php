<?php

if (!isset($_SESSION))
  session_start();

if (!isset($_REQUEST['cmd']))
  $_REQUEST['cmd'] = '';

// auto-load delle classi istanziate
function __autoload($class) {
  global $synAbsolutePath;
  require_once $synAbsolutePath . '/admin/modules/aa/classes/' . $class . '.php';
}

  //definizione variabili globali
  $buttons      = array();
  $synContainer = isset($_REQUEST['aa_service'])
                ? $_REQUEST['aa_service']
                : $_SESSION['aa_service'];

  if ( !isset($db) )
    include_once ("../../../config/cfg.php"); //if RPC

  //creo il contenitore
  $res = $db->Execute( "SELECT syntable, name, description, multilang, dbsync, initOrder FROM aa_services WHERE id = '{$synContainer}'" );

  extract( $res->FetchRow(), EXTR_PREFIX_ALL, 'cont' );
  $table = str_replace( ' ', '_', strToLower( $cont_syntable ) );
  $dbSync = $cont_dbsync;
  $cont_order = ( $cont_initOrder{0} === '-' ) ? 'DESC' : 'ASC';
  $contenitore = synContainer::getInstance( $table, $buttons, TRUE, $cont_name, $cont_description, $cont_multilang );
  $contenitore->setDefaultOrderDirection( $cont_order );

  //ci aggiungo gli elementi
  $elem_qry = <<<EOEQRY
      SELECT se.*, e.classname AS classname
        FROM aa_services_element se
  INNER JOIN aa_element e ON se.type = e.id
       WHERE se.container = '{$synContainer}'
    ORDER BY se.`order`, se.`id`
EOEQRY;
  $res = $db->Execute( $elem_qry );
  while ( $arr = $res->FetchRow() ) {
    extract( $arr, EXTR_PREFIX_ALL, 'elem');
    $elem = new $elem_classname( $elem_name, $elem_value, translateDesktop($elem_label), $elem_size, translateDesktop($elem_help) );
    $elem->isListable( $elem_isvisible, $elem_label, $elem_iseditable );
    if ($elem_path)
      $elem->setPath( $elem_path );
    if ($elem_iskey == 1)
      $elem->setKey( TRUE );
    if ($elem_ismultilang == 1)
      $elem->setMultilang( TRUE );

    if ( !empty($elem_qry)
      && ( !isset($_REQUEST[$elem_name]) || empty($_REQUEST[$elem_name]) )
      ){
      $elem->setQry( $elem_qry );
      $elem->setPath( $elem_path );
    }

    if ($elem_id == abs( $cont_initOrder ) )
      $contenitore->setDefaultOrder( $elem_name );
    $contenitore->checkJoins( $elem_id );
    $contenitore->addElement( $elem );
  }

  //sincronizzo il db con gli elementi aggiunti al contenitore
  if ( $dbSync == '1' && $_REQUEST['cmd'] == '' )
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
    $after = 'stay';

  if (!defined('RPC'))
    define('RPC', 'rpcfunction');

  //----------------------------------------------------------------------------
  //                                   FUNZIONI
  //----------------------------------------------------------------------------


  //if some search qry is done, add the constraint to the qry string
  // !!! DEPRECATED START !!!
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
      if ( $_SESSION["aa_order"] == $_GET["aa_order"])
        if ($_SESSION["aa_order_direction"] == " DESC")
          $_SESSION["aa_order_direction"] = " ASC";
        else
          $_SESSION["aa_order_direction"] = " DESC";

      $_SESSION["aa_order"] = $_GET["aa_order"];

    } elseif (!isset($_SESSION["aa_order"])) {
      global $synTable;
      $res = $db->Execute("SELECT s.initOrder FROM aa_services s where s.syntable='".$synTable."'");
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
  // !!! DEPRECATED END !!!

  // new queryBuilder
  function buildQuery($contenitore, $db) {
    global $treeFrame;

    if (isset($_GET['aa_search_clean']))
      unset($_SESSION['aa_qry']);

    $query = new queryBuilder( 'select' );
    $table = $query->addTable( $contenitore->getTable() );

    foreach( $contenitore->element AS $e ){
      if ( !$treeFrame
        || $treeFrame && ($e->list || $e->is_key)
        ){
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
  $custom_file = $synAbsolutePath . $synAdminPath . '/modules/aa/custom/' . $synTable . '.php';
  // OCIO le inclusioni non devono rompere il json. l'inclusione di aa_service.php è indispensabile
  //if ($cmd != JSON && is_file($custom_file))
  if ( is_file ( $custom_file ) )
    include ( $custom_file );

  switch ( $cmd ) {

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

      if (isset($_REQUEST['after']))
        $default_action = $_REQUEST['after'];
      else
        $default_action = 'saveandexit';

      $after_options = array(
        'exit' => $str['saveandexit'],
        'stay' => $str['save'],
       'clone' => $str['saveandclone'],
         'new' => $str['saveandadd']
      );
      $actions    = $synHtml->select('name="after"', $after_options, $default_action);
      $ok_button  = $synHtml->button("name='cmd' value='".INSERT."' class='btn btn-success'", '<i class="fa fa-check"></i> OK');
      $del_button = null;

      $bottom = <<<EOBOTTOMBAR
        <nav class="navbar form-toolbar navbar-fixed-bottom">
          <div class="container-fluid">
            <a href="{$PHP_SELF}" class="btn btn-primary navbar-btn animsition-link">
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
      enqueue_js( 'initToolbar (false, true, false, true, true, true);' );

      //echo the multilang option
      enqueue_js( $contenitore->getMultilangBoxNew(2) );
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
        //echo "<script>alert(\"{$err}\");</script>";
        setAlert( '<b>Errore</b>: elemento non creato', 'danger' );
        $jumpTo = $PHP_SELF.'?cmd='.MODIFY.'&synPrimaryKey='.urlencode($synPrimaryKey);

      } else {
        //set the next page
        if ($_REQUEST["changeto"]!='') {
          $after = 'changelang';
        }

        setAlert( 'Elemento creato correttamente.', 'success' );
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
            $jumpTo = $PHP_SELF."?cmd=".ADD."&after={$after}";
            break;

          case 'new': // salva & nuovo
            resetClone($synTable);
            $jumpTo = $PHP_SELF."?cmd=".ADD."&after={$after}";
            break;

          case 'stay': // salva & continua
          default:
            resetClone($synTable);
            $jumpTo = $PHP_SELF."?cmd=".MODIFY."&synPrimaryKey={$synPrimaryKey}&after={$after}";
            break;
        }
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

      if (isset($_REQUEST['after']))
        $default_action = $_REQUEST['after'];
      else
        $default_action = 'exit';

      $after_options = array(
        'exit' => $str['saveandexit'],
        'stay' => $str['save'],
       'clone' => $str['saveandclone'],
        'next' => $str['saveandnext'],
         'new' => $str['saveandadd']
      );

      $actions    = $synHtml->select('name="after"', $after_options, $default_action);
      $ok_button  = $synHtml->button("name='cmd' value='".CHANGE."' class=\"btn btn-success\"", '<i class="fa fa-check"></i> OK');
      $del_button = ($synLoggedUser->canDelete == 1)
                  ? $synHtml->button("name='cmd' value='".DELETE."' class=\"btn btn-danger btn-delete\"", '<i class="fa fa-times"></i> '.$str['delete'])
                  : null;
//? $synHtml->button("name='cmd' value='".DELETE."' class='btn btn-danger' onclick=\"return (confirm('{$str["sure_delete"]}'));\"", '<i class="fa fa-times"></i> '.$str['delete'])
      $bottom = <<<EOBOTTOMBAR
        <nav class="navbar form-toolbar navbar-fixed-bottom">
          <div class="container-fluid">
            <a href="{$PHP_SELF}" class="btn btn-primary navbar-btn animsition-link">
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

      // initToolbar ( newBtn, saveBtn, removeBtn, switchBtn, refreshBtn, homeBtn, backBtn)
      enqueue_js( 'initToolbar (false, true, true, true, true, true);' );
      enqueue_js( "action('removeBtn', 'if (confirm(top.str[\"aa_confirmDel\"])) window.parent.content.document.location=\"content.php?cmd=delrow&synPrimaryKey=".urlencode($synPrimaryKey)."\";');" );

      // echo the multilang option
      enqueue_js( $contenitore->getMultilangBoxNew(2) );

      break;


        ////////////////////////////////////////////////////////////////////////
       //                                                                    //
      //                          UPDATE A ROW                              //
     //                                                                    //
    ////////////////////////////////////////////////////////////////////////

    case CHANGE:
      //echo '<pre>', print_r($_POST), '</pre>';
      $synPrimaryKey = urldecode(trim($_POST['synPrimaryKey']));

      $contenitore->uploadDocument();
      $upd = $contenitore->getUpdateString();
      //echo '<pre>', print_r($upd), '</pre>'; //die();
      $ok = true;

      if (!empty($upd)) {
        $qry = "UPDATE `{$synTable}` SET {$upd} WHERE {$synPrimaryKey}";
        $ok = $db->Execute($qry);
        $ok = $ok && $contenitore->execute_callbacks('update');
      }
      //echo "<a href=\"{$PHP_SELF}?cmd=".MODIFY."&synPrimaryKey={$synPrimaryKey}\">avanti</a>"; die();

      //controllo errori
      if ( !$ok ) {
        setAlert('<b>Errore</b>: elemento non aggiornato', 'error');
        $jumpTo = $PHP_SELF . "?cmd=" . MODIFY . "&synPrimaryKey=" . urlencode($synPrimaryKey);
        //echo '<a href="'.$jumpTo.'" class="btn btn-default">Avanti</a>'; die();
      }

      //set the next page
      if ( isset( $_REQUEST['changeto'] ) && !empty( $_REQUEST['changeto'] ) ) {
        $after = 'changelang';
      }

      switch ($after) {
        case 'changelang': // salva & cambia lingua
          resetClone($synTable);
          setAlert( 'Modifiche salvate correttamente.', 'success' );
          $jumpTo = $PHP_SELF . '?cmd=' . MODIFY . '&synPrimaryKey=' . $synPrimaryKey . '&synSetLang=' . $_REQUEST['changeto'];
          break;

        case 'exit': // salva & torna alla lista
          resetClone($synTable);
          setAlert( 'Modifiche salvate correttamente.', 'success' );
          $jumpTo = $PHP_SELF;
          break;

        case 'clone': // salva & duplica
          unset($_POST['id']); //altrimenti continua a lavorare sullo stesso record
          $_SESSION[$synTable.'_clone'] = serialize($_POST);
          setAlert( 'Elemento originale salvato correttamente.', 'success' );
          $jumpTo = $PHP_SELF . '?cmd=' . ADD . '&after=' . $after;
          break;

        case 'next': // salva & prossimo
          resetClone($synTable);
          // select next id
          $nextqry = "SELECT `{$synTable}`.id FROM `{$synTable}` WHERE " . str_replace( '=', '>', $synPrimaryKey );
          if ( isset($_SESSION['aa_joinStack'])
            && is_array($_SESSION['aa_joinStack'])
            ){
            // if a join is set, filter this record's siblings
            $stackLastKey = end( array_keys( $_SESSION['aa_joinStack'] ) );
            $join         = new synJoin( $_SESSION['aa_joinStack'][ $stackLastKey ]['idjoin']);
            $toElmName    = $join->toElmName;
            $value        = $_SESSION['aa_joinStack'][ $stackLastKey ]['value'];
            $nextqry     .= " AND `{$toElmName}` = '{$value}' ";
          }
          $nextqry .= " ORDER BY {$synTable}.id ASC LIMIT 0, 1";
          $res = $db->Execute( $nextqry );

          if ( $arr = $res->FetchRow() ) {
            setAlert( 'Modifiche salvate correttamente.', 'success' );
            $jumpTo = $PHP_SELF . '?cmd=' . MODIFY . '&synPrimaryKey=' . urlencode("`id`=\"{$arr['id']}\"") . '&after=' .$after;
          } else {
            setAlert( 'Non sono presenti altri record.', 'warning' );
            $jumpTo = $PHP_SELF; //non esiste un record successivo, torno alla lista
          }
          break;

        case 'new': // salva & nuovo
          resetClone($synTable);
          setAlert( 'Modifiche salvate correttamente.', 'success' );
          $jumpTo = $PHP_SELF."?cmd=".ADD."&after={$after}";
          break;

        case 'stay': // salva & continua
        default:
          resetClone($synTable);
          setAlert( 'Modifiche salvate correttamente.', 'success' );
          $jumpTo = $PHP_SELF."?cmd=".MODIFY."&synPrimaryKey={$synPrimaryKey}&after={$after}";

          //echo '<pre>', print_r($_SESSION['synAlert']), '</pre>';
          //die('--');
          break;
      }


      break;



        ////////////////////////////////////////////////////////////////////////
       //                                                                    //
      //                          DELETE A ROW                              //
     //                                                                    //
    ////////////////////////////////////////////////////////////////////////


    case DELETE:
      $msg = array();
      $boostrap_table_message = array();
      $code = 200;

      $synPrimaryKey = stripslashes(urldecode($_REQUEST["synPrimaryKey"]));

      //$res = $db->Execute("SELECT * FROM {$synTable} WHERE {$synPrimaryKey}");
      //$db->SetFetchMode(ADODB_FETCH_BOTH);
      //$arr = $res->FetchRow();
      //$contenitore->updateValues( $arr );
      //if ($contenitore->ownerField=="" OR in_array($arr[$contenitore->ownerField],$_SESSION["synGroupChild"]) OR $arr[$contenitore->ownerField]==0) {

      $canDelete = $contenitore->isDeletable();
      if ($canDelete===true) {
        $contenitore->deleteDocument();
        $contenitore->execute_callbacks('delete');

        $del = "DELETE FROM {$synTable} WHERE {$synPrimaryKey}";
        $res = $db->Execute( $del );
        if ( isset($debug) && $debug == 1 ) {
          echo $res;
          $jumpTo = $PHP_SELF . "?cmd=" . MODIFY . "&synPrimaryKey=" . urlencode($synPrimaryKey);
          echo '<a href="'.$jumpTo.'" class="btn btn-default">Avanti</a>'; die();
        }
        if ($res) {
          $msg['status'] = 'Elemento eliminato correttamente.';
          $boostrap_table_message[] = array(
            'message' => $msg['status'],
            'type' => 8
            );

        } else {
          $code = 500;
          $msg['error'] = 'Impossibile eliminare l\'elemento.';
          $boostrap_table_message[] = array(
            'message' => $msg['error'],
            'type' => 1
            );
        }
      } else {
        $msg['unauthorized'] = 'Non sei autorizzato ad eliminare questo elemento.';
        $boostrap_table_message[] = array(
          'message' => $msg['unauthorized'],
          'type' => 2
          );
      }

      if (isset($xhr) && $xhr) {
        //http_response_code( $code ); // php 5.4
        header( 'HTTP/1.0 '.$code ); // this tells jQuery the operation's outcome
        echo json_encode( $boostrap_table_message );

      } else {
        if ( isset($msg['unauthorized']) )
          setAlert( $msg['unauthorized'], 'warning' );

        if ( isset($msg['error']) )
          setAlert( $msg['error'], 'danger' );

        if ( isset( $msg['status']) )
          setAlert( $msg['status'] );

        //set the next page
        $jumpTo = $PHP_SELF;
      }
      break;


        ////////////////////////////////////////////////////////////////////////
       //                                                                    //
      //                          DELETE X ROWS                             //
     //                                                                    //
    ////////////////////////////////////////////////////////////////////////

    case MULTIPLEDELETE:
      //TODO: attenzione alle chiavi. Prende solamente l'id!!!!!!!!!
      $i = 0;
      $msg = array();
      $boostrap_table_message = array();
      $code = 200;

      //http://php.net/manual/en/function.http-response-code.php
      if (isset($_POST['checkrow'])) {
        foreach ($_POST['checkrow'] as $id) {
          $key = urldecode($id);
          $res = $db->Execute("SELECT * FROM `{$synTable}` WHERE {$key}");
          if ($res->recordCount() > 0) {
            $contenitore->updateValues($res->FetchRow());

            $canDelete = $contenitore->isDeletable();
            if ($canDelete===true) {
              @$contenitore->deleteDocument();
              $res = $db->Execute("DELETE FROM `{$synTable}` WHERE {$key}" );
              $i ++;

            } else {
              $code = 401;
              $msg['unauthorized'] = "Row {$key}: {$canDelete}";
              $boostrap_table_message[] = array(
                'message' => "Row {$key}: {$canDelete}",
                'type' => 2
                );
            }
          } else {
            $code = 404;
            $msg['error'] = "{$key} non trovata in {$synTable}";
            $boostrap_table_message[] = array(
                'message' => "{$key} non trovata in {$synTable}",
                'type' => 1
                );
          }
        }
        $msg['status'] = "{$i} {$str['row_deleted']}";
        $boostrap_table_message[] = array(
          'message' => $msg['status'],
          'type' => 0
          );
      }

      if ($xhr) {
        //http_response_code( $code ); // php 5.4
        header( 'HTTP/1.0 '.$code ); // this tells jQuery the operation's outcome
        echo json_encode( $boostrap_table_message );

      } else {
        //echo '<script>alert("'. implode(', ', $msg) .'");</script>';
        foreach( $msg['unauthorized'] as $u )
          setAlert( $u, 'warning' );
        foreach( $msg['error'] as $e )
          setAlert( $e, 'danger' );
        foreach( $msg['status'] as $s )
          setAlert( $s );

        //set the next page
        $jumpTo = $PHP_SELF;
      }

      break;


        ////////////////////////////////////////////////////////////////////////
       //                                                                    //
      //                 RPC - set values via ajax                          //
     //                                                                    //
    ////////////////////////////////////////////////////////////////////////

    case RPC:
      global $contenitore, $debug;
      $msg = array();

      if ( isset($_GET['synPrimaryKey']) && !empty($_GET['synPrimaryKey'])) {
        $synPrimaryKey = urldecode(stripslashes( $_GET['synPrimaryKey'] ));

        $field = $_GET['field'];
        $value = $_GET['value'];
        $synTable = $contenitore->getTable();

        $qry = "UPDATE {$synTable} SET `{$field}` = '{$value}' WHERE {$synPrimaryKey}";
        if ( $db->Execute( $qry ) ) {
          $code = 200;
        } else {
          $code = 500;
          $msg['error'] = 'Cannot execute update query!';
        }
      } else {
        $code = 500;
        $msg['error'] = 'synPrimaryKey not set!';
      }

      header( 'HTTP/1.0 '.$code ); // this tells jQuery the operation's outcome
      echo json_encode( $msg );

    break;


        ////////////////////////////////////////////////////////////////////////
       //                                                                    //
      //                          JSON Table Data                           //
     //                                                                    //
    ////////////////////////////////////////////////////////////////////////

    case JSON:
      // in this case errors must be handled manually
      ini_set('display_errors', 0);
      $db->setErrorMode(2);
      $error = NULL;

      if ($synLoggedUser->canModify==1) {
        $label  = $str['modify'];
        $button = "<a href=\"%s&amp;synPrimaryKey=%s\" class=\"btn btn-xs btn-bl-ock btn-success animsition-link\" title=\"{$label}\">";
        $button.= "<i class=\"fa fa-edit\"></i></a>";
        $contenitore->buttons[$button] = "?cmd=".MODIFY;
      }

      // DELETE button
      if ($synLoggedUser->canDelete==1) {
        $label  = $str['delete'];
        $button = "<a href=\"getData.php%s&amp;synPrimaryKey=%s\" class=\"btn btn-xs btn-bl-ock btn-danger ajax-delete\" title=\"{$label}\">";
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
        $qry->addOrderBy( $sort, $order_dir, $contenitore->getTable() );

      //if (!empty($search))
        //$qry->addSearchClause( $search );

      $boostrap_table_data = array();
      $boostrap_table_errors = array();

      // get row total
      $tot = $db->Execute( $qry->getCountQuery() );
      list($count) = $tot->fetchRow();

      // get subset results
      try {
        $res = $db->execute( $qry->getQuery() );
      } catch( Exception $e) {
        $boostrap_table_errors[] = array(
          'message' => $e->getMessage(),
          'type' => 1
          );
      }

      // pay attention to variables names: they can collide with your data!!!
      while ($row = $res->FetchRow()) {
        $contenitore->updateValues( $row );
        try {
          $contenitore_data_hash = $contenitore->getJsonRow();
        } catch( Exception $e) {
          // data cannot be json-encoded
          $contenitore_data_hash = array(); // it must be an object!
          $boostrap_table_errors[] = array(
            'message' => $e->getMessage(),
            'type' => 1
            );
        }

        if (empty($search))
          $boostrap_table_data[] = $contenitore_data_hash;
        else {
          $str_values = strip_tags( implode(' ', $contenitore_data_hash) );
          if ( stripos( $str_values, $search ) !== false)
            $boostrap_table_data[] = $contenitore_data_hash;
        }

        // if an error message is thrown, store it for later
        // otherwise our json may be corrupted!
        $error = error_get_last();
        //echo '<pre>', print_r($error), '</pre>';
        if ( $error !== null ) {
          $file  = str_replace( $synAbsolutePath, '', $error['file']);
          $mex   = "<b>{$error['message']}</b><br> in {$file}, line {$error['line']}";
          $alert = array( 'type' => $error['type'], 'message' => $mex );
          //echo '<pre>', print_R($row_error), '</pre>';
          if ( !in_array( $alert, $boostrap_table_errors) )
            $boostrap_table_errors[] = $alert;
        }
      }

      if (!empty($search)) {
        $count = count($boostrap_table_data);
        if ($offset > 0)
          $boostrap_table_data = array_slice( $boostrap_table_data, $offset, $limit );
      }

      //echo '<pre>', print_r($data), '</pre>';
      //$qry->debug();

      // populate results json
      $json = array(
        'total' => $count,
        'rows' => $boostrap_table_data
        );

      // some error occured, add it to json response
      if ( !empty($boostrap_table_errors) )
        $json['error'] = $boostrap_table_errors;

      $output = json_encode( $json );

      if ( $output == false && json_last_error() !== JSON_ERROR_NONE ) {
        // json-encoding failed
        $boostrap_table_errors[] = array(
          'message' => json_last_error_msg(),
          'type' => 1
          );
        echo json_encode( array('error' => $boostrap_table_errors ) );
      } else {
        echo $output;
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
        $label = $str['modify'];
        $contenitore->buttons[$label] = MODIFY;
      }

      # DELETE button
      if ($synLoggedUser->canDelete==1) {
        $label = $str['delete'];
        $contenitore->buttons[$label] = DELETE;
      }

      //perform the qry
      //$qry = addQueryWhere("SELECT `{$synTable}`.* FROM `{$synTable}`");


      if ($treeFrame == 'true') {
        // TODO: full implementation of queryBuilder into synContainer and synTree
        //$contenitore->getTree2( $qry );
        $qry = buildQuery( $contenitore, $db );
        $contenitore->getTree( $qry );
        //echo 'order: '.$qry->getOrderBy().'<br>';
      } else {
        if ( $contenitore->treeExists() === true ) {
          enqueue_js( 'parent.refreshTreeFrame();' );
          enqueue_js( 'parent.openTreeFrame();' );
        }
        $sort_name = $contenitore->getDefaultOrder();
        $sort_dir = strtolower( $contenitore->getDefaultOrderDirection() );
        $header = $contenitore->getJsonHeader();
        //          data-cookie-id-table="settings_{$synTable}"
        $table = <<<EOTABLE
        <table id="mainTable" class="table table-striped table-condensed" data-sort-name="{$sort_name}" data-sort-order="{$sort_dir}">
          <thead>
            <tr>{$header}</tr>
          </thead>
        </table>
EOTABLE;

        echo aaHeader( translateDesktop($contenitore->title), translateDesktop($contenitore->description) );
        echo $table;

        // init the toolbar
        enqueue_js( "initToolbar ({$synLoggedUser->canInsert}, 0, {$synLoggedUser->canDelete}, 1, 1, 0);" );

        //echo the multilang option
        enqueue_js( $contenitore->getMultilangBoxNew(1) );
      }
      break;

  }

  //jump to the next page
  if (isset($jumpTo)) {
    js_location($jumpTo); die();
  }

  function aaHeader($title, $title2='') {
    global $cmd;
    if ($cmd != '') {
      $header = <<<EOHEADER
      <div id="formHeader" class="page-header">
        <h2>
          <span data-placement="right" data-toggle="tooltip" data-original-title="{$title2}">{$title}</span>
        </h2>
      </div>
EOHEADER;
    } else {
      $header = <<<EOHEADER
      <h2 class="table-toolbar-title">
        <span data-placement="right" data-toggle="tooltip" data-original-title="{$title2}">{$title}</span>
      </h2>
EOHEADER;

    }
    echo $header;
  }

  function resetClone($synTable){
    if(!isset($_SESSION)) session_start();
    if(isset($_SESSION[$synTable.'_clone']))
      $_SESSION[$synTable.'_clone'] = '';
  }

  function js_location($location) {
    /* riposiziona la pagina */
    echo "<script>if (!window.top.synDebug) window.location=\"{$location}\";</script>";
  }

?>