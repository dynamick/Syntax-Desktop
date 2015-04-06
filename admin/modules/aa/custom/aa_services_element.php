<?php
global $cmd;

  //if the tree frame is loaded, tree frame must die
  if (isset($treeFrame) and $treeFrame=="true") 
    die();

  if (isset($_REQUEST['cmd']) && $_REQUEST['cmd'] != JSON) {
    echo "<script>";
    echo "window.parent.content.action('homeBtn','window.parent.document.location.href=\"index.php?aa_service=5\";');";
    echo "</script>\n";
  }

  switch($_REQUEST["cmd"]) {
    case ADD:             break;
    case MODIFY:          break;
    case CHANGE:          break;
    case INSERT:          break;
    case DELETE:          break;
    case MULTIPLEDELETE:  break;
    case RPC:             break;
    case JSON:            break;

        ////////////////////////////////////////////////////////////////////////////
       //                                                                        //
      //                                 STEP 0                                 //
     //                                                                        //
    ////////////////////////////////////////////////////////////////////////////
    
    case "1":
      aaHeader($str["first_step"], $str["first_step_bis"]);

      if (!isset($_GET['back']) or $_GET['back']!='true') {
        $vars = array(
          'synServiceId',
          'synServiceTitle',
          'synServiceTitleLang',
          'synServiceTable',
          'synServiceDescription',
          'synServiceDescriptionLang',
          'synServiceIcon',
          'synNElement',
          'synDbSync',
          'synServiceMultilang',
          'synInitOrder',
          'synElmId',
          'synElmName',
          'synElmType',
          'synElmLabel',
          'synElmLabelLang',
          'synElmSize',
          'synElmHelp',
          'synElmHelpLang',
          'synElmSize',
          'synElmQry',
          'synElmPath',
          'synElmValue',
          'synElmJoinsItem',
          'synElmOrder',
          'synElmFilter',
          'synKey',
          'synVisible',
          'synEditable',
          'synMultilang'
        );
        foreach( $vars as $var)
          unset( $_SESSION[$var], $$var );
      }

      //read the DB data and set the appropriate variables
      if ( isset($_GET["synPrimaryKey"]) ) {
        $aavalue = substr(stripslashes(urldecode($_GET["synPrimaryKey"])), 5);
        $where = $_GET["aa_join"]."=".$aavalue; 
        $res = $db->Execute("SELECT * FROM aa_services WHERE id={$aavalue}");
        $arr = $res->FetchRow();
        $res2 = $db->Execute("SELECT * FROM aa_services_element WHERE {$where} ORDER BY `order`");
        sess("synServiceId");          $_SESSION["synServiceId"] = $arr["id"];
        sess("synServiceTitle");       $_SESSION["synServiceTitle"] = $arr["name"];
        sess("synServiceTable");       $_SESSION["synServiceTable"] = $arr["syntable"];
        sess("synServiceDescription"); $_SESSION["synServiceDescription"] = $arr["description"];
        sess("synServiceIcon");        $_SESSION["synServiceIcon"] = $arr["icon"];
        sess("synNElement");           $_SESSION["synNElement"] = $res2->RecordCount();
        sess("synDbSync");             $_SESSION["synDbSync"] = $arr["dbsync"];
        sess("synServiceMultilang");   $_SESSION["synServiceMultilang"] = $arr["multilang"];
        sess("synInitOrder");          $_SESSION["synInitOrder"] = $arr["initOrder"];
        sess("synElmId");
        sess("synElmName");
        sess("synElmType");
        sess("synElmLabel");
        sess("synElmSize");
        sess("synElmHelp");
        sess("synElmSize");
        sess("synElmQry");
        sess("synElmPath");
        sess("synElmValue");
        sess("synElmJoinsItem");
        sess("synElmOrder");
        sess("synElmFilter");
        sess("synKey");
        sess("synVisible");
        sess("synEditable");
        sess("synMultilang");
        $count = 0;
        while ($arr = $res2->FetchRow()) {
          $_SESSION["synElmId"][$count] = $arr["id"];
          $_SESSION["synElmName"][$count] = $arr["name"];
          $_SESSION["synElmType"][$count] = $arr["type"];
          $_SESSION["synElmLabel"][$count] = $arr["label"];
          $_SESSION["synElmSize"][$count] = $arr["size"];
          $_SESSION["synElmHelp"][$count] = $arr["help"];
          $_SESSION["synElmSize"][$count] = $arr["size"];
          $_SESSION["synElmQry"][$count] = $arr["qry"];
          $_SESSION["synElmPath"][$count] = $arr["path"];
          $_SESSION["synElmValue"][$count] = $arr["value"];
          $_SESSION["synElmJoinsItem"][$count] = $arr["joins"];
          $_SESSION["synKey"][$count] = $arr["iskey"];
          $_SESSION["synVisible"][$count] = $arr["isvisible"];
          $_SESSION["synElmOrder"][$count] = $arr["order"];
          $_SESSION["synElmFilter"][$count] = $arr["filter"];
          $_SESSION["synEditable"][$count] = $arr["iseditable"];
          $_SESSION["synMultilang"][$count] = $arr["ismultilang"];

          if (abs($_SESSION["synInitOrder"]) == ($_SESSION["synElmId"][$count])) {
            $_SESSION["synInitOrder"] = ($count+1)*($_SESSION["synInitOrder"]/abs($_SESSION["synInitOrder"]));
          }
          $count++;
        }
      }

      $tmp_serviceId = isset($_SESSION["synServiceId"]) ? $_SESSION["synServiceId"] : -1;
      $tmp_serviceTable = isset($_SESSION["synServiceTable"]) ? $_SESSION["synServiceTable"] : "";
      $tmp_serviceTitle = isset($_SESSION["synServiceTitle"]) ? $_SESSION["synServiceTitle"] : "";
      $tmp_serviceTitleLang = isset($_SESSION["synServiceTitleLang"]) ? $_SESSION["synServiceTitleLang"] : "";
      $tmp_serviceDescription = isset($_SESSION["synServiceDescription"]) ? $_SESSION["synServiceDescription"] : "";
      $tmp_serviceDescriptionLang = isset($_SESSION["synServiceDescriptionLang"]) ? $_SESSION["synServiceDescriptionLang"] : "";
      $tmp_serviceIcon = isset($_SESSION["synServiceIcon"]) ? $_SESSION["synServiceIcon"] : "";
      $tmp_serviceNElement = isset($_SESSION["synNElement"]) ? $_SESSION["synNElement"] : 0;
      $tmp_serviceDBSync = isset($_SESSION["synDbSync"]) ? $_SESSION["synDbSync"] : "";

      if ($tmp_serviceId >= 0) 
        $disabled = " disabled=\"disabled\" "; 
      else  
        $disabled = "";
      if ($tmp_serviceTitleLang == "" and $tmp_serviceTitle != "") {
        $tmp_serviceTitleLang = translate($tmp_serviceTitle);
        $_SESSION["synServiceTitleLang"] = $tmp_serviceTitleLang;
      }
      if ($tmp_serviceDescriptionLang == "" and $tmp_serviceDescription != "") {
        $tmp_serviceDescriptionLang = translate($tmp_serviceDescription);
        $_SESSION["synServiceDescriptionLang"] = $tmp_serviceDescriptionLang;
      }
      if ($tmp_serviceNElement == 0) {
        $tmp_serviceNElement = 2;
        $_SESSION["synNElement"] = $tmp_serviceNElement;
      }      
      if ($tmp_serviceDBSync == "1") 
        $sync_checked = ' checked="checked" ';
      else 
        $sync_checked = '';      
      //echo $synHtml->form("action='{$PHP_SELF}' method='POST' enctype='multipart/form-data' name='myform'");
      echo $synHtml->form("action=\"{$PHP_SELF}\" method=\"post\" enctype=\"multipart/form-data\" autocomplete=\"off\"");
      
      echo inputBlock( $str['servicename'],         $synHtml->text(" name=\"synServiceTitleLang\" value=\"{$tmp_serviceTitleLang}\" autofocus"));
      echo inputBlock( $str['tablename'],           $synHtml->text(" name=\"synServiceTable\" value=\"{$tmp_serviceTable}\" {$disabled}"));
      echo inputBlock( $str['servicedescription'],  $synHtml->text(" name=\"synServiceDescriptionLang\" value=\"{$tmp_serviceDescriptionLang}\" "));
      echo inputBlock( $str['serviceparameter'],    $synHtml->text(" name=\"synNElement\" value=\"{$tmp_serviceNElement}\" onchange=\"if (this.value<2) {alert('Warning: service with less 2 fields are meaningless'); }\" "));
      echo inputBlock( $str['dbsync'],              $synHtml->check(" name=\"synDbSync\" value=\"1\" class=\"syn-check\" {$sync_checked} "));
      echo inputBlock( $str['serviceicon'],         $synHtml->text(" name=\"synServiceIcon\" value=\"{$tmp_serviceIcon}\"", ' icp'));

      echo $synHtml->hidden(' name="cmd" value="10" ');
      echo $synHtml->button(" value=\"{$str["forward"]}\" class=\"btn btn-primary pull-right\"", $str["forward"]);
      echo $synHtml->form_c();


      echo "<script>initToolbar (false,false,false,true,true,true,true);action('newBtn','document.location.href=\"?cmd=0\";');</script>\n";
      //echo "<script>document.forms['myform'].elements[0].focus();</script>";
      echo "<script>";
      echo "  window.parent.content.addBox('multilang','');";
      echo "</script>";

      break;

      
        ////////////////////////////////////////////////////////////////////////////
       //                                                                        //
      //                                 STEP 1                                 //
     //                                                                        //
    ////////////////////////////////////////////////////////////////////////////

    case "10":
      aaHeader($str["second_step"], $str["second_step_bis"]);
      sess("synServiceTitleLang");
      sess("synServiceTable");
      sess("synServiceDescriptionLang");
      sess("synServiceMultilang");
      sess("synServiceIcon");
      sess("synNElement");
      sess("synDbSync");

      //funzione in javascript
      ?>
        <script>
          function hide(obj) {
            if (confirm("Sicuro di voler cancellare la riga?")) {
              o=document.getElementById(obj);
              //o.style.display="none";
              o.parentNode.removeChild(o);
              //removeNode(true);
            }
          }
        </script>
      <?php

      $tmp_serviceDBSync = isset($_POST["synDbSync"]) ? isset($_POST["synDbSync"]) : "";
      $tmp_serviceTitleLang = isset($_SESSION["synServiceTitleLang"]) ? $_SESSION["synServiceTitleLang"] : "";
      $tmp_serviceTable = isset($_SESSION["synServiceTable"]) ? $_SESSION["synServiceTable"] : "";

      $_SESSION["synDbSync"] = $tmp_serviceDBSync;
      if($tmp_serviceTable == "") {
        $tmp_serviceTable = str_replace(" ","_",strtolower($_SESSION["synServiceTitleLang"]));
        $_SESSION["synServiceTable"] = $tmp_serviceTable;
      }

      echo '<div class="alert alert-warning"><i class="fa fa-exclamation-triangle"></i> <strong>Red</strong></span> fields are mandatory.</div>';
      echo $synHtml->form("action='$PHP_SELF' method='POST' enctype='multipart/form-data' name='myform' ");
      echo "<table class=\"table table-striped table-hover\">";
      echo "<tr><td></td><td class=\"mandatory\">Del</td><td class=\"mandatory\">".$str["fieldname"]."</td>";
      echo "<td class=\"mandatory\"><b>".$str["fieldtype"]."</b></td><td><b>".$str["fieldlabel"]."</b></td><td><b>".$str["fieldhelp"]."</b></td><td><b>".$str["fieldorder"]."</b></td><td><b>".$str["roworder"]."</b></td></tr>";

      //initialize the key field
      if (!isset($_SESSION["synElmName"]) and !isset($_SESSION["synElmType"])) {
        sess("synElmName");
        sess("synElmType");
        $_SESSION["synElmName"][0] = "id"; 
        $synElmName[0] = "id";
        $_SESSION["synElmType"][0] = 1; 
        $synElmType[0] = 1 /*synKey*/;
        for ($i=1; $i < $_SESSION["synNElement"]; $i++) {
          $_SESSION["synElmName"][$i] = ""; 
          $synElmName[$i] = "";
          $_SESSION["synElmType"][$i] = 2 /*synText*/; 
          $synElmType[$i] = 2 /*synText*/;
        }
      }

      for ($i=0; $i<$_SESSION["synNElement"]; $i++) {
        if(!isset($_SESSION["synElmType"][$i])) {
          $_SESSION["synElmType"][$i]=2;
          $synElmType[$i]=2;
        }

        $elm=new synElement();
        echo "<tr id=\"row{$i}\"><td>$i</td>\n";
        echo "<td><a onclick=\"hide('row{$i}')\" class=\"btn btn-danger btn-sm\"><i class=\"fa fa-trash\"></i></a></td>";
        foreach($elm->configuration($i) as $k=>$v)
          echo "<td style='text-align:center'>".$elm->configuration($i, $k)."</td>\n";
        echo "</tr>\n";
      }
      //echo "<tr><td colspan=\"8\"> <br> <span style='color: red'><strong>Red</strong></span> fields are mandatory </td></tr>";
      echo "</table><hr>";

      echo $synHtml->hidden(" name=\"cmd\" value=\"20\" ");
      echo $synHtml->button(" value=\"{$str["backward"]}\" class='btn btn-default' tabindex=\"100\" onclick=\"document.location.href='".$PHP_SELF."?cmd=1&back=true'; return false;\" ", $str["backward"]);
      echo $synHtml->button(" value=\"{$str["forward"]}\" class='btn btn-primary pull-right' tabindex=\"101\" ", $str["forward"]);
      echo $synHtml->form_c();

      //initToolbar ( newBtn, saveBtn, removeBtn, switchBtn, refreshBtn, homeBtn, backBtn)
      echo "<script>initToolbar (false,false,false,true,true,true,true);action('newBtn','document.location.href=\"?cmd=0\";');</script>\n";
      //echo "<script>document.forms['myform'].elements[0].focus();</script>";

      break;
      
      
        ////////////////////////////////////////////////////////////////////////////
       //                                                                        //
      //                                 STEP 2                                 //
     //                                                                        //
    ////////////////////////////////////////////////////////////////////////////
      
    case '20':
      aaHeader($str['third_step'], $str['third_step_bis']);
      sess('synElmName');
      sess('synElmType');
      sess('synElmLabel');
      sess('synElmLabelLang');
      sess('synElmSize');
      sess('synElmHelp');
      sess('synElmHelpLang');
      sess('synElmJoins');
      sess('synElmOrder');
      sess('synElmFilter');
      sess('synChkKey');
      sess('synChkVisible');
      sess('synChkEditable');
      sess('synChkMultilang');
      sess('synInitOrder');

      echo $synHtml->form("action='{$PHP_SELF}' method='POST' enctype='multipart/form-data' name='myform' ");
      echo "<table class=\"table table-striped table-hover\">";
      //echo "<tr><td></td><td></td><td style='padding-left: 10px;'><img src=\"images/isKey.gif\" alt=\"".$str["alt_fieldkey"]."\"></td><td><img src=\"images/isVisible.gif\" alt=\"".$str["alt_fieldindex"]."\"></td><td><img src=\"images/isEditable.gif\" alt=\"".$str["alt_fieldeditable"]."\"></td><td><img src=\"images/ismultilang.gif\" alt=\"".$str["alt_fieldmultilang"]."\"></td></tr>\n";
      echo "<thead><tr><td></td>"
         . "<th><span data-toggle=\"tooltip\" data-placement=\"top\" title=\"{$str["alt_fieldkey"]}\"><i class=\"fa fa-key\"></i></span></th>"
         . "<th><span data-toggle=\"tooltip\" data-placement=\"top\" title=\"{$str["alt_fieldindex"]}\"><i class=\"fa fa-list\"></i></span></th>"
         . "<th><span data-toggle=\"tooltip\" data-placement=\"top\" title=\"{$str["alt_fieldeditable"]}\"><i class=\"fa fa-pencil-square-o\"></i></span></th>"
         . "<th><span data-toggle=\"tooltip\" data-placement=\"top\" title=\"{$str["alt_fieldmultilang"]}\"><i class=\"fa fa-language\"></i></span></th>"
         . "<th colspan=\"30\"></th>"
         . "</tr></thead>\n";
      echo "<tbody>\n";
      for ($i=0; $i<$_SESSION["synNElement"]; $i++) {
        if (isset($_SESSION["synElmName"][$i])) {
          //create the new element and get the configuration array
          $className = getElmClassName($_SESSION["synElmType"][$i]);
          $elm = new $className;
          $configuration = $elm->configuration($i);

          //write the element's image
          /*
          if (file_exists("images/".$_SESSION["synElmType"][$i].".gif"))
            $img = "<img src=\"images/".$_SESSION["synElmType"][$i].".gif\">";
          else
            $img = "";
          */
          //start the row
          echo "<tr>";
          //image and name of service
          //echo "<th class=\"warning\">{$img}</th>\n";
          echo "<th class=\"active\">".$_SESSION["synElmName"][$i]."</th>\n";

          echo '<td>'.cellSwitch( 'synKey', $i, 'Is key field?').'</td>';
          echo '<td>'.cellSwitch( 'synVisible', $i, 'Is visible in the index?').'</td>';
          echo '<td>'.cellSwitch( 'synEditable', $i, 'Is directly editable in the cell index?').'</td>';
          echo '<td>'.cellSwitch( 'synMultilang', $i, 'Is multilanguage field?').'</td>';

          //joins -- ancora in funzione??
          //if ($synElmJoins[$i]!="")
          //  echo "<td style='text-align:center; border-bottom: 1px solid black; background: #CCC;'>".$synHtml->select(" name=\"synElmJoinsItem[$i]\" ","SELECT id,name FROM aa_services_element where container=".$synElmJoins[$i], $synElmJoinsItem[$i])."</td>";

          //prints the configuration strings
          //echo "<td>";

          if(isset($configuration) and is_array($configuration)) {
            foreach($configuration as $k=>$v)
              echo "<td><i class=\"fa fa-chevron-circle-right\"></i> ".$elm->configuration($i,$k)."</td>\n";
              //echo "<td style='text-align:right; border-bottom: 1px solid black; padding-left: 20px'>".$elm->configuration($i,$k)."</td>\n";
          }

          //order by ASC or DESC??
          if (abs($_SESSION["synInitOrder"])==($i+1)) {
            $negCheck = ""; $posCheck = "";
            $_SESSION["synInitOrder"] > 0 
              ? $posCheck = " checked=\"checked\" " 
              : $negCheck = " checked=\"checked\" ";
            $options = array(
              abs($_SESSION["synInitOrder"]) => 'ASC',
              '-'.abs($_SESSION["synInitOrder"]) => 'DESC'
            );
            $dir = ($_SESSION['synInitOrder'] > 0) ? 'ASC' : 'DESC';
            //echo "<td><i class=\"fa fa-chevron-circle-right\"></i> Order By: ASC".$synHtml->radio(" name=\"synInitOrder\" value=\"".abs($_SESSION["synInitOrder"])."\" $posCheck ")." - DESC ".$synHtml->radio(" name=\"synInitOrder\" value=\"-".abs($_SESSION["synInitOrder"])."\" $negCheck ")."</td>";
            echo "<td><i class=\"fa fa-chevron-circle-right\"></i> Order By:<br>".$synHtml->select( 'name="synInitOrder"', $options, $dir )."</td>";
          }
          echo "<td colspan=\"30\"></td>";

          echo "</tr>\n";
        } //end if
      }   //end for
      echo "</tbody></table>\n";

      // GENERATE CUSTOM PHP
      echo "<div class=''>{$str["servicegeneratefile"]}: ".$synHtml->check(" name=\"synGenerate\" value=\"1\" class=\"syn-check\" data-size=\"mini\"")."</div>";

      echo "<hr>\n";
      echo $synHtml->hidden(' name="cmd" value="30" ');
      echo $synHtml->button(" value=\"&lt;&lt; ".$str["backward"]."\" class='btn btn-default' onclick=\"document.location.href='".$PHP_SELF."?cmd=10'; return false;\" ", $str["backward"]);
      echo $synHtml->button(" value=\"".$str["save"]."\" class='btn btn-primary pull-right' name='save' ", $str["save"]);
      echo $synHtml->form_c();

      //initToolbar ( newBtn, saveBtn, removeBtn, switchBtn, refreshBtn, homeBtn, backBtn)
      echo "<script>initToolbar (false,false,false,true,true,true,false);action('newBtn','document.location.href=\"?cmd=0\";');</script>\n";
      //echo "<script>document.forms['myform'].save.focus();</script>";

      break;

      
        ////////////////////////////////////////////////////////////////////////////
       //                                                                        //
      //                                 STEP 3                                 //
     //                                                                        //
    ////////////////////////////////////////////////////////////////////////////

    case "30":
      $newService = false;

      unset($_SESSION['synElmName']);       sess('synElmName');
      unset($_SESSION['synElmType']);       sess('synElmType');
      unset($_SESSION['synKey']);           sess('synKey');
      unset($_SESSION['synVisible']);       sess('synVisible');
      unset($_SESSION['synEditable']);      sess('synEditable');
      unset($_SESSION['synMultilang']);     sess('synMultilang');
      unset($_SESSION['synElmLabel']);      sess('synElmLabel');
      unset($_SESSION['synElmLabelLang']);  sess('synElmLabelLang');
      unset($_SESSION['synElmHelp']);       sess('synElmHelp');
      unset($_SESSION['synElmHelpLang']);   sess('synElmHelpLang');
      unset($_SESSION['synElmPath']);       sess('synElmPath');
      unset($_SESSION['synElmQry']);        sess('synElmQry');
      unset($_SESSION['synElmValue']);      sess('synElmValue');
      unset($_SESSION['synElmJoinsItem']);  sess('synElmJoinsItem');
      unset($_SESSION['synInitOrder']);     sess('synInitOrder');
      unset($_SESSION['synElmFilter']);     sess('synElmFilter');
      unset($_SESSION['synElmSize']);       sess('synElmSize');

      //check if the service contains multilang elements
      $_SESSION['synServiceMultilang'] = '';
      if (isset($_SESSION['synElement'])) {
        for ($i = 0; $i < $_SESSION['synElement']; $i++) {
          if (isset($_SESSION['synMultilang'][$i]) and $_SESSION['synMultilang'][$i] == "1")
            $_SESSION['synServiceMultilang'] = 1;
        }
      }
      //insert or update the service properties
      $res = $db->Execute("SELECT * FROM aa_services WHERE `name` = '{$_SESSION['synServiceTitleLang']}'");
      if ( $res->RecordCount() == 0 AND !isset($_SESSION['synServiceId']) ) {
        $_SESSION['synServiceTitle'] = insertTranslation($_SESSION['synServiceTitleLang']);
        $_SESSION['synServiceDescription'] = insertTranslation($_SESSION['synServiceDescriptionLang']);
        $ins = "INSERT INTO aa_services (`name`, `syntable`, `description`, `dbsync`, `icon`, `initOrder`, `multilang`) VALUES "
             . "  ('".addslashes($_SESSION["synServiceTitle"])
             . "', '".addslashes($_SESSION["synServiceTable"])
             . "', '".addslashes($_SESSION["synServiceDescription"])
             . "', '".addslashes($_SESSION["synDbSync"])
             . "', '".addslashes($_SESSION["synServiceIcon"])
             . "', '".addslashes($_SESSION["synInitOrder"])
             . "', '".addslashes($_SESSION["synServiceMultilang"])
             . "'  )";
        $db->Execute($ins);
        $id = $db->Insert_Id();
        $newService = true;
      } else if (isset($_SESSION['synServiceId'])) {
          updateTranslation($_SESSION['synServiceTitle'], $_SESSION['synServiceTitleLang']);
          updateTranslation($_SESSION['synServiceDescription'], $_SESSION['synServiceDescriptionLang']);
          $upd = "UPDATE aa_services set "
               . "          `name`='".addslashes($_SESSION['synServiceTitle'])
               . "', `description`='".addslashes($_SESSION['synServiceDescription'])
               . "',      `dbsync`='".addslashes($_SESSION['synDbSync'])
               . "',        `icon`='".addslashes($_SESSION['synServiceIcon'])
               . "',   `initOrder`='".addslashes($_SESSION['synInitOrder'])
               . "',   `multilang`='".addslashes($_SESSION['synServiceMultilang'])
               . "'     WHERE `id`='".addslashes($_SESSION['synServiceId'])
               . "'";
          $db->Execute($upd);
          $id = $_SESSION['synServiceId'];
        } else {
          echo "<script>alert('".$str["err_service_already_exists"]."'); parent.location.href='index.php?aa_service=5';</script>";
          die();
        }


      //variable $id contains the current service ID

      //echo $synHtml->form("action='$PHP_SELF' method='POST' enctype='multipart/form-data' ");
      //echo "<table cellpaddin=3 style='margin: 10px; border: 1px solid black; background: white' cellspacing=\"0\">\n";

      //$db->Execute("DELETE FROM aa_services_element WHERE `container`=$id");
      $delsql = "";
      for ($i=0; $i<$_SESSION["synNElement"]; $i++) {
        if (isset($_SESSION["synElmName"][$i])) {
          $tmp_name = isset($_SESSION["synElmName"][$i]) ? addslashes($_SESSION["synElmName"][$i]) : "";
          $tmp_type = isset($_SESSION["synElmType"][$i]) ? addslashes($_SESSION["synElmType"][$i]) : "";
          $tmp_key = isset($_SESSION["synKey"][$i]) ? addslashes($_SESSION["synKey"][$i]) : "";
          $tmp_visible = isset($_SESSION["synVisible"][$i]) ? addslashes($_SESSION["synVisible"][$i]) : "";
          $tmp_editable = isset($_SESSION["synEditable"][$i]) ? addslashes($_SESSION["synEditable"][$i]) : "";
          $tmp_multilang = isset($_SESSION["synMultilang"][$i]) ? addslashes($_SESSION["synMultilang"][$i]) : "";
          $tmp_label = isset($_SESSION["synElmLabel"][$i]) ? addslashes($_SESSION["synElmLabel"][$i]) : "";
          $tmp_labelLang = isset($_SESSION["synElmLabelLang"][$i]) ? addslashes($_SESSION["synElmLabelLang"][$i]) : "";
          $tmp_help = isset($_SESSION["synElmHelp"][$i]) ? addslashes($_SESSION["synElmHelp"][$i]) : "";
          $tmp_helpLang = isset($_SESSION["synElmHelpLang"][$i]) ? addslashes($_SESSION["synElmHelpLang"][$i]) : "";
          $tmp_path = isset($_SESSION["synElmPath"][$i]) ? addslashes($_SESSION["synElmPath"][$i]) : "";
          $tmp_qry = isset($_SESSION["synElmQry"][$i]) ? addslashes($_SESSION["synElmQry"][$i]) : "";
          $tmp_value = isset($_SESSION["synElmValue"][$i]) ? addslashes($_SESSION["synElmValue"][$i]) : "";
          $tmp_joins = isset($_SESSION["synElmJoinsItem"][$i]) ? addslashes($_SESSION["synElmJoinsItem"][$i]) : "";
          $tmp_order = isset($_SESSION["synElmOrder"][$i]) ? addslashes($_SESSION["synElmOrder"][$i]) : "";
          $tmp_filter = isset($_SESSION["synElmFilter"][$i]) ? addslashes($_SESSION["synElmFilter"][$i]) : "";
          $tmp_size = isset($_SESSION["synElmSize"][$i]) ? $_SESSION["synElmSize"][$i] : 0;
          if ($tmp_labelLang == "") {
            $tmp_labelLang = ucwords($tmp_name);
            $_SESSION["synElmLabelLang"][$i] = $tmp_labelLang;
          }
          if (isset($_SESSION["synElmId"][$i])) {
            updateTranslation($tmp_label,$tmp_labelLang);
            updateTranslation($tmp_help,$tmp_helpLang);
            #if ($_SESSION["synElmSize"][$i]=="") $s=""; else $s="`size`=".$_SESSION["synElmSize"][$i];
            $size = ($tmp_size > 0) ? "`size`=".$tmp_size.", " : "";
            $db->Execute("UPDATE aa_services_element set `container`=".addslashes($_SESSION["synServiceId"]).", `name`='".$tmp_name."', `type`='".$tmp_type."', `iskey`='".$tmp_key."', `isvisible`='".$tmp_visible."', `iseditable`='".$tmp_editable."', `ismultilang`='".$tmp_multilang."', `label`='".$tmp_label."', $size `help`='".$tmp_help."', `path`='".$tmp_path."', `qry`='".$tmp_qry."', `value`='".$tmp_value."', `joins`='".$tmp_joins."', `order`=".$tmp_order.", `filter`='".$tmp_filter."' WHERE id=".$_SESSION["synElmId"][$i]."");
          } else {
            $tmp_label = insertTranslation($tmp_labelLang);
            $_SESSION["synElmLabel"][$i] = $tmp_label;
            $_SESSION["synElmLabelLang"][$i] = $tmp_labelLang;

            $tmp_help = insertTranslation($tmp_helpLang);
            $_SESSION["synElmHelp"][$i] = $tmp_help;
            $_SESSION["synElmHelpLang"][$i] = $tmp_helpLang;

            $_SESSION["synElmSize"][$i] = $tmp_size;
            $db->Execute("INSERT INTO aa_services_element (`container`, `name`, `type`, `iskey`, `isvisible`, `iseditable`, `ismultilang`, `label`, `size`, `help`, `path`, `qry`, `value`, `joins`, `order`, `filter`) VALUES ($id, '".$tmp_name."','".$tmp_type."', '".$tmp_key."', '".$tmp_visible."', '".$tmp_editable."', '".$tmp_multilang."', '".$tmp_label."','".$tmp_size."','".$tmp_help."','".$tmp_path."','".$tmp_qry."','".$tmp_value."','".$tmp_joins."', ".$tmp_order.",'".$tmp_filter."')");
          }

          //get the last id and add it at the delsql text
          $res = $db->Execute("SELECT id FROM aa_services_element WHERE `container`=$id AND `name`='".$tmp_name."' AND `type`='".$tmp_type."' AND `iskey`='".$tmp_key."' AND `isvisible`='".$tmp_visible."' AND `iseditable`='".$tmp_editable."' AND `label`='".$tmp_label."'  AND `help`='".$tmp_help."' AND `path`='".$tmp_path."' AND `qry`='".$tmp_qry."' AND `value`='".$tmp_value."' AND `joins`='".$tmp_joins."' AND `order`=".$tmp_order." AND `filter`='".$tmp_filter."'");
          list($lastId) = $res->FetchRow();
          //$lastId=$res->Insert_ID();
          $delsql .= $lastId.", ";

          //select the ID of the just updated/inserted element and use it for the order by field
          if ($i+1==abs($_SESSION["synInitOrder"])) {
            $sign = $_SESSION["synInitOrder"]/abs($_SESSION["synInitOrder"]);
            $res = $db->Execute("SELECT id FROM aa_services_element WHERE `container`=$id AND `name`='".$tmp_name."' AND `type`='".$tmp_type."' AND `iskey`='".$tmp_key."' AND `isvisible`='".$tmp_visible."' AND `iseditable`='".$tmp_editable."' AND `label`='".$tmp_label."' AND `size`='".$tmp_size."' AND `help`='".$tmp_help."' AND `path`='".$tmp_path."' AND `qry`='".$tmp_qry."' AND `value`='".$tmp_value."' AND `joins`='".$tmp_joins."' AND `order`=".$tmp_order." AND `filter`='".$tmp_filter."'");
            list($orderByElementId)=$res->FetchRow();
            $db->Execute("UPDATE aa_services set `initOrder`=".($orderByElementId*$sign)." WHERE `id`='{$id}'" );
          }
        }
      }
      //remove the unwanted fields
      $delsql=substr($delsql,0,strlen($delsql)-2);
      $db->Execute("DELETE FROM aa_services_element WHERE `container`='{$id}' and id NOT IN($delsql)");

      //echo $synHtml->hidden(" name=\"cmd\" value=\"40\" ");
      //echo "</table>";

      //HTML GENERATION FILE
      $db->debug = 0;
      if (isset($_POST["synGenerate"])) {
        ob_start();
          //include("ihtml/auto_service_file.php");
          //readfile(dirname(realpath(__FILE__))."/schema.php");
          //Modified by CarloC 01/07/2004 - See syntax forum
          readfile(dirname(realpath(__FILE__))."/../ihtml/schema.php");
        $contents = ob_get_contents();
        ob_end_clean();
        $filename = "custom/".$_SESSION["synServiceTable"].".php";
        file_put_contents($filename, $contents);
        //$db->Execute("UPDATE aa_services set `path`='".addslashes($filename)."' WHERE `id` = '{$id}'" );
      }

      //GROUP ASSIGN
      if ($newService) {

        aaHeader("Groups", $str["service_saved"]);
        echo $synHtml->form("action='$PHP_SELF' method='POST' enctype='multipart/form-data'");
        echo $synHtml->hidden( "name='cmd' value='groups' " );
        echo $synHtml->hidden( "name='service' value='{$id}' " );
        echo $synHtml->hidden( "name='servicename' value='{$_SESSION['synServiceTitle']}' ");

        echo "<p>".$str["service2groups"]."</p>";

        $qry = "SELECT * FROM aa_groups ORDER BY name";
        $res = $db->Execute($qry);
        while (list($idgroup, $namegroup) = $res->FetchRow()) {
          $html  = '<div class="form-group">'.PHP_EOL;
          $html .= '  <div class="col-sm-6 col-lg-4">'.PHP_EOL;
          $html .= '    <div class="input-group">'.PHP_EOL;

          $html .= "  <span class=\"input-group-addon\" style=\"width:150px; text-align:left;\">\n";
          $html .= "    <label><input type=\"checkbox\" name=\"group[]\" value=\"{$idgroup}\" class=\"trigger\"> {$namegroup}</label>";
          $html .= "  </span>";
          
          $opts = createOptionsArray($idgroup);
          $html .= "<select class=\"form-control\" name=\"startingPoint[{$idgroup}]\" disabled>{$opts}</select>";

          $html .= "  <span class=\"input-group-addon\"><i class=\"fa fa-sitemap\"></i></span>\n";
          $html .= '    </div>';
          $html .= '  </div>';
          $html .= '</div>'.PHP_EOL;
          echo $html;
        }

        echo '<hr>';
        echo $synHtml->button(" value='".$str["save"]."' class='btn btn-primary' ", $str["save"]);
        echo $synHtml->form_c();

        echo <<<EOS
        <script type="text/javascript">
          $('.trigger').click(function() {
            $(this).parents('span').next('select').attr('disabled', !this.checked);
          });
        </script>
EOS;
        
      } else
        echo "<script>parent.location.href='index.php?aa_service=5';</script>";

      break;


        ////////////////////////////////////////////////////////////////////////////
       //                                                                        //
      //                   ASSIGN SERVICE TO GROUPS (STEP 4)                    //
     //                                                                        //
    ////////////////////////////////////////////////////////////////////////////
    
    case "groups":
      $name = $_POST["servicename"];
      $service = $_POST["service"];
      if (is_array($_POST["group"]) ) {
        foreach ($_POST["group"] as $g) {
          $idname = insertTranslation(translate($name));
          $startingPoint = $_POST["startingPoint"][$g];
          $db->Execute("INSERT INTO aa_group_services (`name`,`group`,`service`,`parent`,`insert`,`modify`,`delete`) VALUES ('{$idname}','{$g}','{$service}',{$startingPoint},1,1,1)");
        }
      }
      // js alerts
      echo "<script>alert('".$str["service_reminder"]."');</script>";
      echo "<script>parent.location.href='index.php?aa_service=5';</script>";

      break;


        ////////////////////////////////////////////////////////////////////////////
       //                                                                        //
      //                         BUTTON LIST (STEP ???)                         //
     //                                                                        //
    ////////////////////////////////////////////////////////////////////////////

    case "buttonList":
      $res = $db->Execute("SELECT * FROM aa_service_buttons");
      while ($arr = $res->FetchRow()) {
      }
      break;
  }


//-----------------------------------Private Function---------------------------

  //salva $var in una variabile di sessione
  function sess($var) {
    if (isset($_REQUEST[$var])) :
      $_SESSION[$var] = $_REQUEST[$var];
    else :
      global $$var;
      $_SESSION[$var] = $$var;
    endif;
  }

  function getElmClassName($id) {
    global $db;
    $qry = "SELECT * FROM aa_element WHERE id = '{$id}'";
    $res = $db->Execute($qry);
    $arr = $res->FetchRow();
    return $arr['classname'];
  }
  
  function inputBlock( $label, $input, $class='' ) {
    $html = <<<EOHTML
    <div class="form-group {$class}">
      <label class="col-sm-2 control-label">{$label}</label>
      <div class="col-sm-10 col-lg-6">
        {$input}
      </div>
    </div>
    <hr>
EOHTML;
    return $html;
  }  


  function cellSwitch( $field, $counter, $help) {
    $checked = '';
    $class = ''; //'syn-check';
    $fieldChk = str_replace('syn', 'synChk', $field);
    
    if (isset($_SESSION[$field][$counter]) and $_SESSION[$field][$counter]==1)
      $checked = 'checked="checked"';
    if (isset($_SESSION["synElmType"][$counter]) and $_SESSION["synElmType"][$counter]==1 /*synKey*/)
      $checked = 'checked="checked" onclick="this.checked=true;"';
 
    if (isset($_SESSION[$fieldChk][$counter]) and $_SESSION[$fieldChk][$counter]=='1') {
      $ret  = "<span data-toggle=\"tooltip\" data-placement=\"top\" title=\"{$help}\">";
      $ret .= "<input type=\"hidden\" name=\"{$field}[{$counter}]\" value=\"\">";
      $ret .= "<input type=\"checkbox\" name=\"{$field}[{$counter}]\" value=\"1\" {$checked} class=\"{$class}\" data-size=\"mini\">";
      $ret .= "</span>";
    } else {
      $ret = "<input type=\"checkbox\" class=\"{$class}\" data-size=\"mini\" disabled />";
    }

    return $ret;
  }
  
  // returns selectable menu items
  function createOptionsArray($idgroup, $parent='0', $recursion=-1) {
    global $db;

    $recursion ++;
    $ret        = '';
    $table      = 'aa_group_services';
    $caption    = 'name';
    $lang       = $_SESSION['aa_CurrentLangInitial'];
    $joinfield  = ", t.{$lang} AS {$caption} ";
    $join       = " LEFT JOIN aa_translation t ON {$caption} = t.id ";

    $qry = "SELECT {$table}.* {$joinfield} FROM `{$table}`{$join} WHERE parent='{$parent}' AND `group`='{$idgroup}' ORDER BY `order` ASC";
    $res = $db->Execute($qry);
    while($arr = $res->fetchrow()){
      $indent = str_repeat('&nbsp;&nbsp;', $recursion);
      //$disabled = ($arr['service']>0 || $arr['link']) ? 'disabled' : null;
      //$ret .= "  <option value=\"{$arr['id']}\"{$disabled}>{$indent}{$arr[$caption]}</option>\n";

      if ($arr['service']==0 && empty($arr['link']))
        $ret .= "  <option value=\"{$arr['id']}\">{$indent}{$arr[$caption]}</option>\n";

      if($children = createOptionsArray($idgroup, $arr['id'], $recursion)){
        $ret .= $children;
      }
    }
    return $ret;
  }  
?>