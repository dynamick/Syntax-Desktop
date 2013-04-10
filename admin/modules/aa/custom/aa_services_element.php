<?php
global $cmd;

  //if the tree frame is loaded, tree frame must die
  if (isset($treeFrame) and $treeFrame=="true") die();

  echo "<script>";
  echo "window.parent.content.action('homeBtn','window.parent.document.location.href=\"index.php?aa_service=5\";');";
  echo "</script>\n";      

  switch($_REQUEST["cmd"]) {
    case ADD:             break;
    case MODIFY:          break;
    case CHANGE:          break;
    case INSERT:          break;
    case DELETE:          break;
    case MULTIPLEDELETE:  break;
    case RPC:             break;
    
    /**************************************************************************
    *                             STEP 0
    ***************************************************************************/      
    case "1": 
      aaHeader($str["first_step"],$str["first_step_bis"]);

      if (!isset($_GET["back"]) or $_GET["back"]!="true") {
        unset($_SESSION["synServiceId"]); unset($synServiceId); 
        unset($_SESSION["synServiceTitle"]); unset($synServiceTitle);     
        unset($_SESSION["synServiceTitleLang"]); unset($synServiceTitleLang);     
        unset($_SESSION["synServiceTable"]); unset($synServiceTable);       
        unset($_SESSION["synServiceDescription"]); unset($synServiceDescription);
        unset($_SESSION["synServiceDescriptionLang"]); unset($synServiceDescriptionLang);
        unset($_SESSION["synServiceIcon"]); unset($synServiceIcon);
        unset($_SESSION["synNElement"]); unset($synNElement);
        unset($_SESSION["synDbSync"]); unset($synDbSync);
        unset($_SESSION["synServiceMultilang"]); unset($synServiceMultilang);
        unset($_SESSION["synInitOrder"]); unset($synInitOrder);
        unset($_SESSION["synElmId"]); unset($synElmId);
        unset($_SESSION["synElmName"]); unset($synElmName);
        unset($_SESSION["synElmType"]); unset($synElmType);
        unset($_SESSION["synElmLabel"]); unset($synElmLabel);
        unset($_SESSION["synElmLabelLang"]); unset($synElmLabelLang);
        unset($_SESSION["synElmSize"]); unset($synElmSize);
        unset($_SESSION["synElmHelp"]); unset($synElmHelp);
        unset($_SESSION["synElmHelpLang"]); unset($synElmHelpLang);
        unset($_SESSION["synElmSize"]); unset($synElmSize);
        unset($_SESSION["synElmQry"]); unset($synElmQry);
        unset($_SESSION["synElmPath"]); unset($synElmPath);
        unset($_SESSION["synElmValue"]); unset($synElmValue);
        unset($_SESSION["synElmJoinsItem"]); unset($synElmJoinsItem);
        unset($_SESSION["synElmOrder"]); unset($synElmOrder);
        unset($_SESSION["synElmFilter"]); unset($synElmFilter);
        unset($_SESSION["synKey"]); unset($synKey);
        unset($_SESSION["synVisible"]); unset($synVisible);
        unset($_SESSION["synEditable"]); unset($synEditable);
        unset($_SESSION["synMultilang"]); unset($synMultilang);
      } 
      
      
      
      //read the DB data and set the appropriate variables
      if (isset($_GET["synPrimaryKey"])) {
        $aavalue=substr(stripslashes(urldecode($_GET["synPrimaryKey"])),5);
        $where=$_GET["aa_join"]."=".$aavalue; //'".$_GET["aa_value"]."'";
        $res=$db->Execute("SELECT * FROM aa_services where id=$aavalue");
        $arr=$res->FetchRow();
        $res2=$db->Execute("SELECT * FROM aa_services_element where ".$where." order by `order`");
        sess("synServiceId");          $_SESSION["synServiceId"]=$arr["id"];
        sess("synServiceTitle");       $_SESSION["synServiceTitle"]=$arr["name"];
        sess("synServiceTable");       $_SESSION["synServiceTable"]=$arr["syntable"];
        sess("synServiceDescription"); $_SESSION["synServiceDescription"]=$arr["description"];
        sess("synServiceIcon");        $_SESSION["synServiceIcon"]=$arr["icon"];
        sess("synNElement");           $_SESSION["synNElement"]=$res2->RecordCount();
        sess("synDbSync");             $_SESSION["synDbSync"]=$arr["dbsync"];
        sess("synServiceMultilang");   $_SESSION["synServiceMultilang"]=$arr["multilang"];
        sess("synInitOrder");          $_SESSION["synInitOrder"]=$arr["initOrder"];
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
        $count=0;
        while ($arr=$res2->FetchRow()) {
          $_SESSION["synElmId"][$count]=$arr["id"];
          $_SESSION["synElmName"][$count]=$arr["name"];
          $_SESSION["synElmType"][$count]=$arr["type"];
          $_SESSION["synElmLabel"][$count]=$arr["label"];
          $_SESSION["synElmSize"][$count]=$arr["size"];
          $_SESSION["synElmHelp"][$count]=$arr["help"];
          $_SESSION["synElmSize"][$count]=$arr["size"];
          $_SESSION["synElmQry"][$count]=$arr["qry"];
          $_SESSION["synElmPath"][$count]=$arr["path"];
          $_SESSION["synElmValue"][$count]=$arr["value"];
          $_SESSION["synElmJoinsItem"][$count]=$arr["joins"];
          $_SESSION["synKey"][$count]=$arr["iskey"];
          $_SESSION["synVisible"][$count]=$arr["isvisible"];
          $_SESSION["synElmOrder"][$count]=$arr["order"];
          $_SESSION["synElmFilter"][$count]=$arr["filter"];
          $_SESSION["synEditable"][$count]=$arr["iseditable"];
          $_SESSION["synMultilang"][$count]=$arr["ismultilang"];
          
          if (abs($_SESSION["synInitOrder"])==($_SESSION["synElmId"][$count])) {  
            $_SESSION["synInitOrder"]=($count+1)*($_SESSION["synInitOrder"]/abs($_SESSION["synInitOrder"]));
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
      $tmp_serviceDBSync = isset($_SESSION["synDbSync"]) ? $_SESSION["synDbSync"] : "0";
      
      if ($tmp_serviceId >= 0) $disabled=" disabled=\"disabled\" "; else $disabled="";
      if ($tmp_serviceTitleLang == "" and $tmp_serviceTitle != "") {
        $tmp_serviceTitleLang = translate($tmp_serviceTitle);
        $_SESSION["synServiceTitleLang"] = $tmp_serviceTitleLang;
      }
      if ($tmp_serviceDescriptionLang == "" and $tmp_serviceDescription != "") {
        $tmp_serviceDescriptionLang = translate($tmp_serviceDescription);
        $_SESSION["synServiceDescriptionLang"] = $tmp_serviceDescriptionLang;
      }
      echo $synHtml->form("action='$PHP_SELF' method='POST' enctype='multipart/form-data' name='myform'");

        echo "<table style='border-bottom: 1px solid gray; background: white; width: 100%; vertical-align: bottom; ' cellpadding='5' cellspacing='0'>";
        echo "<tr><td width='20%'><br></td><td width='30%'><br></td><td rowspan=9 style='border: 0px solid gray;'>".icon($tmp_serviceIcon)."</td></tr>";
        echo "<tr><td align=right class=\"mandatory\">".$str["servicename"].": </td><td>";
        echo $synHtml->text(" name=\"synServiceTitleLang\" value=\"".$tmp_serviceTitleLang."\" ");
        echo "</td></tr>\n";
        echo "<tr><td align=right>".$str["tablename"].": </td><td>";
        echo $synHtml->text(" name=\"synServiceTable\" value=\"".$tmp_serviceTable."\" $disabled ");
        echo "</td></tr>\n";
        echo "<tr><td align=right>".$str["servicedescription"].": </td><td>";
        echo $synHtml->text(" name=\"synServiceDescriptionLang\" value=\"".$tmp_serviceDescriptionLang."\" ");
        echo "</td></tr>\n";
        echo "<tr><td align=right class=\"mandatory\">".$str["serviceparameter"].": </td><td>";
        if ($tmp_serviceNElement == 0) {
          $tmp_serviceNElement = 2;  
          $_SESSION["synNElement"] = $tmp_serviceNElement;
        }
        echo $synHtml->text(" name=\"synNElement\" value=\"".$tmp_serviceNElement."\" onchange=\"if (this.value<2) {alert('Warning: service with less 2 fields are meaningless'); }\" ");
        echo "</td></tr>\n";
        echo "<tr><td align=right class=\"mandatory\">".$str["dbsync"].":  </td><td>";
        if ($tmp_serviceDBSync == "1") $checked=" checked=\"checked\" ";else $checked="";
        echo $synHtml->check(" name=\"synDbSync\" value=\"1\" $checked ");
        echo "</td></tr>\n";
        //echo "<tr><td align=right class=\"mandatory\">".$str["multilang"].":  </td><td>";
        //if ($synServiceMultilang=="1") $checked=" checked=\"checked\" ";else $checked="";
        //echo $synHtml->check(" name=\"synServiceMultilang\" value=\"1\" $checked ");
        echo "</td></tr>\n";
        echo "<tr><td align=right>".$str["serviceicon"].":  </td><td>";
        echo $synHtml->text(" name=\"synServiceIcon\" value=\"".$tmp_serviceIcon."\" ");
        echo "</td></tr>\n";
        echo "<tr><td></td><td>";
        echo "</td></tr>\n";
        echo "<tr><td></td><td>";
        echo $synHtml->hidden(" name=\"cmd\" value=\"10\" ");
        echo "</td></tr>\n";
        echo "<tr><td colspan=2>";
        echo "</td></tr>\n";
        echo "</table>";
        echo "<table style='width: 100%;'>";
        echo "<tr><td style='width: 99%;'></td><td style='padding-right: 20px;'>";
        echo $synHtml->button(" value=\"".$str["forward"]." &gt;&gt;\"  class='action_button' ", $str["forward"]);
        echo "</td></tr></table>";
      echo $synHtml->form_c();

      //initToolbar ( newBtn, saveBtn, removeBtn, switchBtn, refreshBtn, homeBtn, backBtn)
      echo "<script>initToolbar (false,false,false,true,true,true,true);action('newBtn','document.location.href=\"?cmd=0\";');</script>\n";      
      echo "<script>document.forms['myform'].elements[0].focus();</script>";
      echo "<script>";
      echo "  window.parent.content.addBox('multilang','');";
      echo "</script>";

      break;

    case "10": 
      aaHeader($str["second_step"],$str["second_step_bis"]);
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
      
      echo $synHtml->form("action='$PHP_SELF' method='POST' enctype='multipart/form-data' name='myform' ");
      echo "<table style='border-bottom: 1px solid gray; background: white; width: 100%; vertical-align: bottom' cellpadding='3' cellspacing='0'>";
      echo "<tr><td colspan=\"8\"><br></td></tr>";
      echo "<tr><td></td><td class=\"mandatory\">Del</td><td class=\"mandatory\">".$str["fieldname"]."</td><td class=\"mandatory\"><b>".$str["fieldtype"]."</b></td><td><b>".$str["fieldlabel"]."</b></td><td><b>".$str["fieldhelp"]."</b></td><td><b>".$str["fieldorder"]."</b></td><td><b>".$str["roworder"]."</b></td></tr>";

      //initialize the key field
      if (!isset($_SESSION["synElmName"]) and !isset($_SESSION["synElmType"])) {
        sess("synElmName");
        sess("synElmType");
        $_SESSION["synElmName"][0]="id"; $synElmName[0]="id"; 
        $_SESSION["synElmType"][0]=1 /*synKey*/; $synElmType[0]=1/*synKey*/;
        for ($i=1; $i<$_SESSION["synNElement"]; $i++) {
          $_SESSION["synElmName"][$i]=""; $synElmName[$i]="";
          $_SESSION["synElmType"][$i]=2/*synText*/; $synElmType[$i]=2/*synText*/;
        }
      }
      
      for ($i=0; $i<$_SESSION["synNElement"]; $i++) {
        if(!isset($_SESSION["synElmType"][$i])) {
          $_SESSION["synElmType"][$i]=2; 
          $synElmType[$i]=2;
        }

        $elm=new synElement();
        echo "<tr id=\"row$i\"><td>$i</td>\n";
        echo "<td><a onclick=\"hide('row$i')\" style='cursor: hand;'><img src=\"images/delete.gif\" /></a></td>";
        foreach($elm->configuration($i) as $k=>$v)
          echo "<td style='text-align:center'>".$elm->configuration($i,$k)."</td>\n";
        echo "</tr>\n";
      }
      echo "<tr><td colspan=\"8\"> <br> <span style='color: red'><strong>Red</strong></span> fields are mandatory </td></tr>";
      echo "</table>";
      echo $synHtml->hidden(" name=\"cmd\" value=\"20\" ");
      
        echo "<table style='width: 100%;'>";
        echo "<tr><td style='padding-right: 20px; text-align: right;'>";
      echo $synHtml->button(" value=\"&lt;&lt; ".$str["backward"]."\" class='cancel_button' tabindex=\"100\" onclick=\"document.location.href='".$PHP_SELF."?cmd=1&back=true'; return false;\" ", $str["backward"]);
      echo $synHtml->button(" value=\"".$str["forward"]." &gt;&gt;\" class='action_button' tabindex=\"101\" ", $str["forward"]);
      echo "</td></tr>\n";
      echo "</table>";
      
      
      
      echo $synHtml->form_c();
      
      //initToolbar ( newBtn, saveBtn, removeBtn, switchBtn, refreshBtn, homeBtn, backBtn)
      echo "<script>initToolbar (false,false,false,true,true,true,true);action('newBtn','document.location.href=\"?cmd=0\";');</script>\n";      
      echo "<script>document.forms['myform'].elements[0].focus();</script>";

      break;

    case "20": 
      aaHeader($str["third_step"],$str["third_step_bis"]);
      sess("synElmName");
      sess("synElmType");
      sess("synElmLabel");
      sess("synElmLabelLang");
      sess("synElmSize");
      sess("synElmHelp");
      sess("synElmHelpLang");
      sess("synElmJoins");
      sess("synElmOrder");
      sess("synElmFilter");

      sess("synChkKey");
      sess("synChkVisible");
      sess("synChkEditable");
      sess("synChkMultilang");
      
      sess("synInitOrder");
      
      echo $synHtml->form("action='$PHP_SELF' method='POST' enctype='multipart/form-data' name='myform' ");
      echo "<div style='border-bottom: 1px solid gray; background: white; width: 100%; padding: 10px;'>";
      echo "<table style='width: 100%; vertical-align: bottom' cellpadding='0' cellspacing='0'>";
      echo "<tr><td></td><td></td><td style='padding-left: 10px;'><img src=\"images/isKey.gif\" alt=\"".$str["alt_fieldkey"]."\"></td><td><img src=\"images/isVisible.gif\" alt=\"".$str["alt_fieldindex"]."\"></td><td><img src=\"images/isEditable.gif\" alt=\"".$str["alt_fieldeditable"]."\"></td><td><img src=\"images/ismultilang.gif\" alt=\"".$str["alt_fieldmultilang"]."\"></td></tr>\n";

      for ($i=0; $i<$_SESSION["synNElement"]; $i++) {
        if (isset($_SESSION["synElmName"][$i])) { 
          //create the new element and get the configuration array
          $className=getElmClassName($_SESSION["synElmType"][$i]);
          $elm=new $className;
          $configuration=$elm->configuration($i);
          
          //write the element's image
          if (file_exists("images/".$_SESSION["synElmType"][$i].".gif")) $img="<img src=\"images/".$_SESSION["synElmType"][$i].".gif\">";else $img="";
  
          //start the row
          echo "<tr>";
  
          //image and name of service
          echo "<td style='text-align:center; border-bottom: 1px solid black; background: #CCC;'>&nbsp;$img</td>\n";
          echo "<td style='text-align:left; border-bottom: 1px solid black; border-width: 0 1px 1px 0; color: darkred;background: #CCC; padding: 3px; '><strong> ".$_SESSION["synElmName"][$i]."</strong></td>\n";

          //key check
          if (isset($_SESSION["synKey"][$i]) and $_SESSION["synKey"][$i]==1) $chk="checked=\"checked\" "; else $chk="";
          if (isset($_SESSION["synElmType"][$i]) and $_SESSION["synElmType"][$i]==1/*synKey*/) $chk="checked=\"checked\" onclick=\"this.checked=true;\" ";
          if (isset($_SESSION["synChkKey"][$i]) and $_SESSION["synChkKey"][$i]=="1") echo "<td style='border-bottom: 1px solid #F2F2F2; padding-left: 10px;' ><input type=\"hidden\" name=\"synKey[$i]\" value=\"\"/><input type=\"checkbox\" name=\"synKey[$i]\" value=\"1\" title=\"Is key field?\" $chk/></td>\n";
          else echo "<td style='border-bottom: 1px solid #F2F2F2; padding-left: 10px;' ><input type=\"checkbox\"   disabled=\"disabled\" /></td>\n";
  
          //visible check
          if (isset($_SESSION["synVisible"][$i]) and $_SESSION["synVisible"][$i]==1) $chk="checked=\"checked\" "; else $chk="";
          if (isset($_SESSION["synChkVisible"][$i]) and $_SESSION["synChkVisible"][$i]=="1") echo "<td style='border-bottom: 1px solid #F2F2F2;'><input type=\"hidden\"  name=\"synVisible[$i]\" value=\"\"/><input type=\"checkbox\"  name=\"synVisible[$i]\" value=\"1\" title=\"Is visible in the index?\" $chk/></td>\n";
          else echo "<td style='border-bottom: 1px solid #F2F2F2;'><input type=\"checkbox\"   disabled=\"disabled\" /></td>\n";
  
          //editable check
          if (isset($_SESSION["synEditable"][$i]) and $_SESSION["synEditable"][$i]==1) $chk="checked=\"checked\" "; else $chk="";
          if (isset($_SESSION["synChkEditable"][$i]) and $_SESSION["synChkEditable"][$i]=="1") echo "<td style='border-bottom: 1px solid #F2F2F2;'><input type=\"hidden\" name=\"synEditable[$i]\" value=\"\"/><input type=\"checkbox\" name=\"synEditable[$i]\" value=\"1\" title=\"Is directly editable in the cell index?\" $chk/></td>\n";
          else echo "<td style='border-bottom: 1px solid #F2F2F2;'><input type=\"checkbox\"  disabled=\"disabled\" /></td>\n";
  
          //multilang check
          if (isset($_SESSION["synMultilang"][$i]) and $_SESSION["synMultilang"][$i]==1) $chk="checked=\"checked\" "; else $chk="";
          if (isset($_SESSION["synChkMultilang"][$i]) and $_SESSION["synChkMultilang"][$i]=="1") echo "<td style='border-bottom: 1px solid #F2F2F2;'><input type=\"hidden\" name=\"synMultilang[$i]\" value=\"\"/><input type=\"checkbox\" name=\"synMultilang[$i]\" value=\"1\" title=\"Is multilanguage field?\" $chk/></td>\n";
          else echo "<td style='border-bottom: 1px solid #F2F2F2;'><input type=\"checkbox\"  disabled=\"disabled\" /></td>\n";
  
          //joins -- ancora in funzione??
          //if ($synElmJoins[$i]!="")
          //  echo "<td style='text-align:center; border-bottom: 1px solid black; background: #CCC;'>".$synHtml->select(" name=\"synElmJoinsItem[$i]\" ","SELECT id,name FROM aa_services_element where container=".$synElmJoins[$i], $synElmJoinsItem[$i])."</td>";
  
          //prints the configuration strings
          echo "<td style='border-bottom: 1px solid #F2F2F2; padding: 4px 0;'>";
          
          if(isset($configuration) and is_array($configuration)) {
            foreach($configuration as $k=>$v)
              echo "<div style='margin-left: 10px; float: left'><img src='images/bullet.gif' /> ".$elm->configuration($i,$k)."</div>\n";
              //echo "<td style='text-align:right; border-bottom: 1px solid black; padding-left: 20px'>".$elm->configuration($i,$k)."</td>\n";
          } 
          
          //order by ASC or DESC??
          if (abs($_SESSION["synInitOrder"])==($i+1)) {
            $negCheck="";$posCheck="";
            $_SESSION["synInitOrder"]>0?$posCheck=" checked=\"checked\" ":$negCheck=" checked=\"checked\" ";
            echo "<div style='margin-left: 20px; float: left;'><img src='images/bullet.gif' /> Order By: ASC".$synHtml->radio(" name=\"synInitOrder\" value=\"".abs($_SESSION["synInitOrder"])."\" $posCheck ")." - DESC ".$synHtml->radio(" name=\"synInitOrder\" value=\"-".abs($_SESSION["synInitOrder"])."\" $negCheck ")."</div>";
          }
          echo "</td>";

          echo "</tr>\n";
        } //end if          
      }   //end for
      
      echo $synHtml->hidden(" name=\"cmd\" value=\"30\" ");
      echo "</table></div>";

      echo "<table style='width: 100%;'>";
      echo "<tr><td style='padding-right: 20px; text-align: right;'>";
      echo "<div  class='action_button' style='float: left' >&nbsp;&nbsp;&nbsp;".$str["servicegeneratefile"].": ".$synHtml->check(" name=\"synGenerate\" value=\"1\" ")."</div>";
      echo $synHtml->button(" value=\"&lt;&lt; ".$str["backward"]."\" class='cancel_button' onclick=\"document.location.href='".$PHP_SELF."?cmd=10'; return false;\" ", $str["backward"]);
      echo $synHtml->button(" value=\"".$str["save"]."\" class='action_button' name='save' ", $str["save"]);
      echo "</td></tr>\n";
      echo "</table>";
      
      echo $synHtml->form_c();
      
      //initToolbar ( newBtn, saveBtn, removeBtn, switchBtn, refreshBtn, homeBtn, backBtn)
      echo "<script>initToolbar (false,false,false,true,true,true,false);action('newBtn','document.location.href=\"?cmd=0\";');</script>\n";      
      echo "<script>document.forms['myform'].save.focus();</script>";

      break;
      
      
    case "30": 
      $newService=false;

      unset($_SESSION["synElmName"]);  sess("synElmName");
      unset($_SESSION["synElmType"]);  sess("synElmType");
      unset($_SESSION["synKey"]);      sess("synKey");
      unset($_SESSION["synVisible"]);  sess("synVisible");
      unset($_SESSION["synEditable"]); sess("synEditable");
      unset($_SESSION["synMultilang"]); sess("synMultilang");
      unset($_SESSION["synElmLabel"]); sess("synElmLabel");
      unset($_SESSION["synElmLabelLang"]); sess("synElmLabelLang");
      unset($_SESSION["synElmHelp"]); sess("synElmHelp");
      unset($_SESSION["synElmHelpLang"]); sess("synElmHelpLang");
      unset($_SESSION["synElmPath"]);  sess("synElmPath");
      unset($_SESSION["synElmQry"]);   sess("synElmQry");
      unset($_SESSION["synElmValue"]); sess("synElmValue");
      unset($_SESSION["synElmJoinsItem"]); sess("synElmJoinsItem");
      unset($_SESSION["synInitOrder"]); sess("synInitOrder");
      unset($_SESSION["synElmFilter"]);  sess("synElmFilter");
      unset($_SESSION["synElmSize"]);  sess("synElmSize");
      
      //check if the service contains multilang elements
      $_SESSION["synServiceMultilang"]="";
      for ($i=0; $i<$_SESSION["synNElement"]; $i++) if (isset($_SESSION["synMultilang"][$i]) and $_SESSION["synMultilang"][$i]=="1") {$_SESSION["synServiceMultilang"]=1;}
      
      //insert or update the service properties
      $res=$db->Execute("SELECT * FROM aa_services WHERE `name`='".$_SESSION["synServiceTitleLang"]."'");
      if ( $res->RecordCount()==0 AND !isset($_SESSION["synServiceId"]) ) {
        $_SESSION["synServiceTitle"]=insertTranslation($_SESSION["synServiceTitleLang"]);
        $_SESSION["synServiceDescription"]=insertTranslation($_SESSION["synServiceDescriptionLang"]);
        $db->Execute("INSERT INTO aa_services (`name`, `syntable`, `description`, `dbsync`, `icon`, `initOrder`, `multilang`) VALUES ('".addslashes($_SESSION["synServiceTitle"])."', '".addslashes($_SESSION["synServiceTable"])."', '".addslashes($_SESSION["synServiceDescription"])."', '".addslashes($_SESSION["synDbSync"])."', '".addslashes($_SESSION["synServiceIcon"])."', ".addslashes($_SESSION["synInitOrder"]).", '".addslashes($_SESSION["synServiceMultilang"])."')" );
        $id=$db->Insert_Id();
        $newService=true;
      } else if (isset($_SESSION["synServiceId"])) { 
          updateTranslation($_SESSION["synServiceTitle"],$_SESSION["synServiceTitleLang"]);
          updateTranslation($_SESSION["synServiceDescription"],$_SESSION["synServiceDescriptionLang"]);
          $db->Execute("UPDATE aa_services set `name`='".addslashes($_SESSION["synServiceTitle"])."', `description`='".addslashes($_SESSION["synServiceDescription"])."', `dbsync`='".addslashes($_SESSION["synDbSync"])."', `icon`='".addslashes($_SESSION["synServiceIcon"])."', `initOrder`='".addslashes($_SESSION["synInitOrder"])."', `multilang`='".addslashes($_SESSION["synServiceMultilang"])."' WHERE `id`=".addslashes($_SESSION["synServiceId"])."" );
          $id=$_SESSION["synServiceId"];
        } else {echo "<script>alert('".$str["err_service_already_exists"]."'); parent.location.href='index.php?aa_service=5';</script>";die;}


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
          $res=$db->Execute("SELECT id FROM aa_services_element WHERE `container`=$id AND `name`='".$tmp_name."' AND `type`='".$tmp_type."' AND `iskey`='".$tmp_key."' AND `isvisible`='".$tmp_visible."' AND `iseditable`='".$tmp_editable."' AND `label`='".$tmp_label."'  AND `help`='".$tmp_help."' AND `path`='".$tmp_path."' AND `qry`='".$tmp_qry."' AND `value`='".$tmp_value."' AND `joins`='".$tmp_joins."' AND `order`=".$tmp_order." AND `filter`='".$tmp_filter."'");
          list($lastId)=$res->FetchRow();
          //$lastId=$res->Insert_ID();
          $delsql.=$lastId.", ";

          //select the ID of the just updated/inserted element and use it for the order by field 
          if ($i+1==abs($_SESSION["synInitOrder"])) {
            $sign=$_SESSION["synInitOrder"]/abs($_SESSION["synInitOrder"]);
            $res=$db->Execute("SELECT id FROM aa_services_element WHERE `container`=$id AND `name`='".$tmp_name."' AND `type`='".$tmp_type."' AND `iskey`='".$tmp_key."' AND `isvisible`='".$tmp_visible."' AND `iseditable`='".$tmp_editable."' AND `label`='".$tmp_label."' AND `size`='".$tmp_size."' AND `help`='".$tmp_help."' AND `path`='".$tmp_path."' AND `qry`='".$tmp_qry."' AND `value`='".$tmp_value."' AND `joins`='".$tmp_joins."' AND `order`=".$tmp_order." AND `filter`='".$tmp_filter."'");
            list($orderByElementId)=$res->FetchRow();
            $db->Execute("UPDATE aa_services set `initOrder`=".($orderByElementId*$sign)." WHERE `id`=$id" );
          }
        }
      }
      //remove the unwanted fields
      $delsql=substr($delsql,0,strlen($delsql)-2);
      $db->Execute("DELETE FROM aa_services_element WHERE `container`=$id and id NOT IN($delsql)");

      //echo $synHtml->hidden(" name=\"cmd\" value=\"40\" ");
      //echo "</table>";
      
      //HTML GENERATION FILE
      $db->debug=0;
      if (isset($_POST["synGenerate"])) {
        ob_start();
          include("ihtml/auto_service_file.php");
          //readfile(dirname(realpath(__FILE__))."/schema.php");
          //Modified by CarloC 01/07/2004 - See syntax forum
          readfile(dirname(realpath(__FILE__))."/../ihtml/schema.php");
        $contents=ob_get_contents();
        ob_end_clean();
        $filename="custom/_".$_SESSION["synServiceTable"].".php";
        file_put_contents($filename,$contents);
        $db->Execute("UPDATE aa_services set `path`='".addslashes($filename)."' WHERE `id`=$id" );

      }

      //GROUP ASSIGN
      if ($newService) {
        aaHeader("Groups",$str["service_saved"]);
        echo "<div style='border-bottom: 1px solid gray; background: white; width: 100%; padding: 10px;'>";
        echo "<div  style='margin-left: 20px; font-family: Verdana; font-size: x-small;'>";      
        echo $synHtml->form("action='$PHP_SELF' method='POST' enctype='multipart/form-data'");
        echo $synHtml->hidden("name='cmd' value='groups' ");
        echo $synHtml->hidden("name='service' value='$id' ");
        
        echo $synHtml->hidden("name='servicename' value='".$_SESSION["synServiceTitle"]."' ");
        echo "<p>".$str["service2groups"]."</p>";
        $qry="SELECT * FROM aa_groups ORDER BY name";
        $res=$db->Execute($qry);
        while (list($idgroup,$namegroup)=$res->FetchRow()) {
          echo "<input type=\"checkbox\" name=\"group[]\" value=\"$idgroup\" /> <strong>$namegroup</strong> ";

          $qry="SELECT * FROM `aa_group_services` WHERE `group`=".$idgroup." ORDER BY parent";
          $opt="";
          $res2=$db->Execute($qry);
          while ($arr2=$res2->FetchRow()) {
            $opt.="<option value=\"".$arr2["id"]."\">".translate($arr2["name"])."</option>";
          }
          echo "- Parent menu item: <select name=\"startingPoint[".$idgroup."]\">".$opt."</select><br/>";
        }
        echo $synHtml->button(" value='".$str["save"]."' class='action_button' ", $str["save"]);
        echo $synHtml->form_c();
        echo "</div></div>";
      } else echo "<script>parent.location.href='index.php?aa_service=5';</script>"; 

      break;
      
    /**************************************************************************
    *                             ASSIGN SERVICE TO GROUPS
    ***************************************************************************/      
      
    case "groups":
      $name=$_POST["servicename"];
      $service=$_POST["service"];
      if (is_array($_POST["group"]) ) {
        foreach ($_POST["group"] as $g) {
          $idname=insertTranslation(translate($name));
          $startingPoint=$_POST["startingPoint"][$g];
          $db->Execute("INSERT INTO aa_group_services (`name`,`group`,`service`,`parent`,`insert`,`modify`,`delete`) VALUES ('$idname','$g','$service',$startingPoint,1,1,1)");
        } 
      }      
      echo "<script>alert('".$str["service_reminder"]."');</script>";
      echo "<script>parent.location.href='index.php?aa_service=5';</script>";
      
      break;


    /**************************************************************************
    *                             buttons list
    ***************************************************************************/      
      
    case "buttonList":
      $res=$db->Execute("SELECT * FROM aa_service_buttons");
      while ($arr=$res->FetchRow()) {
      }  
    break;
  }
  

//-----------------------------------Private Function---------------------------  
  function icon($icona) {
    global $str;
      $dir_name = "images/service_icon/";
      $dir = opendir($dir_name);
      if (!$icona) $icona="images/spacer.gif";
      $ret  = "".$str["selected_icon"].": <img src=\"$icona\" name=\"synIcon\">";
      $ret .= "<div id=\"iconbox\">\n";
      $file = array();
      while ($file_name = readdir($dir)) {
        if (($file_name != "." && $file_name != ".." && $file_name!="Thumbs.db")) {
          $file[] = $dir_name.$file_name;
          //$dim=@getimagesize($file);
          //if ($dim[0]<22 and $dim[1]<22) echo "<img src=\"$file\" Onclick=\"document.forms[0].sicon.value='$file'; document.sicona.src='$file'\">\n";
        }
      }
      asort($file);
      foreach($file as $f) {
        $ret .= "  <img src=\"$f\" Onclick=\"document.forms[0].synServiceIcon.value='$f'; document.synIcon.src='$f'\"  width=\"16\" height=\"16\" >\n";
      }
      $ret .= "</div>\n";
      return $ret;
  }


  //salva $var in una variabile di sessione
  function sess($var) {
    //if (!session_is_registered($var)) session_register($var);
    if (isset($_REQUEST[$var])) $_SESSION[$var]=$_REQUEST[$var];
    else {
      global $$var;
      $_SESSION[$var]=$$var;
    }
  }
  
  function getElmClassName($id) {
    global $db;
    $qry="SELECT * FROM aa_element WHERE id=".$id;
    $res=$db->Execute($qry);
    $arr=$res->FetchRow();
    return $arr["classname"];
  }
?>
