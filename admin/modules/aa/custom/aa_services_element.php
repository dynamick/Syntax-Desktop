<?
global $cmd;

  //if the tree frame is loaded, tree frame must die
  if ($treeFrame=="true") die();

  echo "<script>";
  echo "window.parent.content.action('homeBtn','window.parent.document.location.href=\"index.php?aa_service=5\";');";
  echo "</script>\n";      

  switch($_REQUEST["cmd"]) {
    case ADD:  
    break;
    case MODIFY:  
    break;
    case CHANGE:  
    break;
    case INSERT:
    break;
    case DELETE: 
    break;
    case MULTIPLEDELETE:
    break;
    case RPC:
    break;

    
    /**************************************************************************
    *                             STEP 0
    ***************************************************************************/      
    case "1": 
      aaHeader($str["first_step"],$str["first_step_bis"]);

      if ($_GET["back"]!="true") {
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
      
      if (isset($_SESSION["synServiceId"])) $disabled=" disabled=\"disabled\" "; else $disabled="";
      if ($_SESSION["synServiceTitleLang"]=="") $_SESSION["synServiceTitleLang"]=translate($_SESSION["synServiceTitle"]);
      if ($_SESSION["synServiceDescriptionLang"]=="") $_SESSION["synServiceDescriptionLang"]=translate($_SESSION["synServiceDescription"]);
      echo $synHtml->form("action='$PHP_SELF' method='POST' enctype='multipart/form-data' name='myform'");

        echo "<table style='border-bottom: 1px solid gray; background: white; width: 100%; vertical-align: bottom; ' cellpadding='5' cellspacing='0'>";
        echo "<tr><td width='20%'><br></td><td width='30%'><br></td><td rowspan=9 style='border: 0px solid gray;'>".icon($_SESSION["synServiceIcon"])."</td></tr>";
        echo "<tr><td align=right class=\"mandatory\">".$str["servicename"].": </td><td>";
        echo $synHtml->text(" name=\"synServiceTitleLang\" value=\"".$_SESSION["synServiceTitleLang"]."\" ");
        echo "</td></tr>\n";
        echo "<tr><td align=right>".$str["tablename"].": </td><td>";
        echo $synHtml->text(" name=\"synServiceTable\" value=\"".$_SESSION["synServiceTable"]."\" $disabled ");
        echo "</td></tr>\n";
        echo "<tr><td align=right>".$str["servicedescription"].": </td><td>";
        echo $synHtml->text(" name=\"synServiceDescriptionLang\" value=\"".$_SESSION["synServiceDescriptionLang"]."\" ");
        echo "</td></tr>\n";
        echo "<tr><td align=right class=\"mandatory\">".$str["serviceparameter"].": </td><td>";
        if ($_SESSION["synNElement"]==0) $_SESSION["synNElement"]=2;
        echo $synHtml->text(" name=\"synNElement\" value=\"".$_SESSION["synNElement"]."\" onchange=\"if (this.value<2) {alert('Warning: service with less 2 fields are meaningless'); }\" ");
        echo "</td></tr>\n";
        echo "<tr><td align=right class=\"mandatory\">".$str["dbsync"].":  </td><td>";
        if ($_SESSION["synDbSync"]=="1") $checked=" checked=\"checked\" ";else $checked="";
        echo $synHtml->check(" name=\"synDbSync\" value=\"1\" $checked ");
        echo "</td></tr>\n";
        //echo "<tr><td align=right class=\"mandatory\">".$str["multilang"].":  </td><td>";
        //if ($synServiceMultilang=="1") $checked=" checked=\"checked\" ";else $checked="";
        //echo $synHtml->check(" name=\"synServiceMultilang\" value=\"1\" $checked ");
        echo "</td></tr>\n";
        echo "<tr><td align=right>".$str["serviceicon"].":  </td><td>";
        echo $synHtml->text(" name=\"synServiceIcon\" value=\"".$_SESSION["synServiceIcon"]."\" ");
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
        echo $synHtml->button(" value=\"".$str["forward"]." &gt;&gt;\"  class='action_button' ");
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
      <?
      
      if ($_POST["synDbSync"]=="") $_SESSION["synDbSync"]="";
      if ($_SESSION["synServiceTable"]=="") $_SESSION["synServiceTable"]=str_replace(" ","_",strtolower($_SESSION["synServiceTitleLang"]));
      
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
      echo $synHtml->button(" value=\"&lt;&lt; ".$str["backward"]."\" class='cancel_button' tabindex=\"100\" onclick=\"document.location.href='".$PHP_SELF."?cmd=1&back=true'; return false;\" ");
      echo $synHtml->button(" value=\"".$str["forward"]." &gt;&gt;\" class='action_button' tabindex=\"101\" ");
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
          if ($_SESSION["synKey"][$i]==1) $chk="checked=\"checked\" "; else $chk="";
          if ($_SESSION["synElmType"][$i]==1/*synKey*/) $chk="checked=\"checked\" onclick=\"this.checked=true;\" ";
          if ($_SESSION["synChkKey"][$i]=="1") echo "<td style='border-bottom: 1px solid #F2F2F2; padding-left: 10px;' ><input type=\"checkbox\" name=\"synKey[$i]\" value=\"1\" title=\"Is key field?\" $chk/></td>\n";
          else echo "<td style='border-bottom: 1px solid #F2F2F2; padding-left: 10px;' ><input type=\"checkbox\"   disabled=\"disabled\" /></td>\n";
  
          //visible check
          if ($_SESSION["synVisible"][$i]==1) $chk="checked=\"checked\" "; else $chk="";
          if ($_SESSION["synChkVisible"][$i]=="1") echo "<td style='border-bottom: 1px solid #F2F2F2;'><input type=\"checkbox\"  name=\"synVisible[$i]\" value=\"1\" title=\"Is visible in the index?\" $chk/></td>\n";
          else echo "<td style='border-bottom: 1px solid #F2F2F2;'><input type=\"checkbox\"   disabled=\"disabled\" /></td>\n";
  
          //editable check
          if ($_SESSION["synEditable"][$i]==1) $chk="checked=\"checked\" "; else $chk="";
          if ($_SESSION["synChkEditable"][$i]=="1") echo "<td style='border-bottom: 1px solid #F2F2F2;'><input type=\"checkbox\" name=\"synEditable[$i]\" value=\"1\" title=\"Is directly editable in the cell index?\" $chk/></td>\n";
          else echo "<td style='border-bottom: 1px solid #F2F2F2;'><input type=\"checkbox\"  disabled=\"disabled\" /></td>\n";
  
          //multilang check
          if ($_SESSION["synMultilang"][$i]==1) $chk="checked=\"checked\" "; else $chk="";
          if ($_SESSION["synChkMultilang"][$i]=="1") echo "<td style='border-bottom: 1px solid #F2F2F2;'><input type=\"checkbox\" name=\"synMultilang[$i]\" value=\"1\" title=\"Is multilanguage field?\" $chk/></td>\n";
          else echo "<td style='border-bottom: 1px solid #F2F2F2;'><input type=\"checkbox\"  disabled=\"disabled\" /></td>\n";
  
          //joins -- ancora in funzione??
          //if ($synElmJoins[$i]!="")
          //  echo "<td style='text-align:center; border-bottom: 1px solid black; background: #CCC;'>".$synHtml->select(" name=\"synElmJoinsItem[$i]\" ","SELECT id,name FROM aa_services_element where container=".$synElmJoins[$i], $synElmJoinsItem[$i])."</td>";
  
          //prints the configuration strings
          echo "<td style='border-bottom: 1px solid #F2F2F2; padding: 4px 0;'>";
          foreach($configuration as $k=>$v)
            echo "<div style='margin-left: 10px; float: left'><img src='images/bullet.gif' /> ".$elm->configuration($i,$k)."</div>\n";
            //echo "<td style='text-align:right; border-bottom: 1px solid black; padding-left: 20px'>".$elm->configuration($i,$k)."</td>\n";
          
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
      echo $synHtml->button(" value=\"&lt;&lt; ".$str["backward"]."\" class='cancel_button' onclick=\"document.location.href='".$PHP_SELF."?cmd=10'; return false;\" ");
      echo $synHtml->button(" value=\"".$str["save"]."\" class='action_button' name='save' ");
      echo "</td></tr>\n";
      echo "</table>";
      
      echo $synHtml->form_c();
      
      //initToolbar ( newBtn, saveBtn, removeBtn, switchBtn, refreshBtn, homeBtn, backBtn)
      echo "<script>initToolbar (false,false,false,true,true,true,false);action('newBtn','document.location.href=\"?cmd=0\";');</script>\n";      
      echo "<script>document.forms['myform'].save.focus();</script>";

      break;
    case "30": 
      $newService=false;

      unset($_SESSION["synElmSize"]);  sess("synElmSize");
      unset($_SESSION["synElmQry"]);   sess("synElmQry");
      unset($_SESSION["synElmPath"]);  sess("synElmPath");
      unset($_SESSION["synElmValue"]); sess("synElmValue");
      unset($_SESSION["synElmJoinsItem"]); sess("synElmJoinsItem");
      unset($_SESSION["synKey"]);      sess("synKey");
      unset($_SESSION["synVisible"]);  sess("synVisible");
      unset($_SESSION["synEditable"]); sess("synEditable");
      unset($_SESSION["synMultilang"]); sess("synMultilang");
      unset($_SESSION["synInitOrder"]); sess("synInitOrder");
      
      //check if the service contains multilang elements
      $_SESSION["synServiceMultilang"]="";
      for ($i=0; $i<$_SESSION["synNElement"]; $i++) if ($_SESSION["synMultilang"][$i]=="1") {$_SESSION["synServiceMultilang"]=1;}
      
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

      for ($i=0; $i<$_SESSION["synNElement"]; $i++) {
        if (isset($_SESSION["synElmName"][$i])) { 
          if ($_SESSION["synElmLabel"][$i]=="") $_SESSION["synElmLabelLang"][$i]=ucwords($_SESSION["synElmName"][$i]);
          if (isset($_SESSION["synElmId"][$i])) {
            updateTranslation($_SESSION["synElmLabel"][$i],$_SESSION["synElmLabelLang"][$i]);
            updateTranslation($_SESSION["synElmHelp"][$i],$_SESSION["synElmHelpLang"][$i]);
            if ($_SESSION["synElmSize"][$i]=="") $s=""; else $s="`size`=".$_SESSION["synElmSize"][$i]; 
            $db->Execute("UPDATE aa_services_element set `container`=".addslashes($_SESSION["synServiceId"]).", `name`='".addslashes($_SESSION["synElmName"][$i])."', `type`='".addslashes($_SESSION["synElmType"][$i])."', `iskey`='".addslashes($_SESSION["synKey"][$i])."', `isvisible`='".addslashes($_SESSION["synVisible"][$i])."', `iseditable`='".addslashes($_SESSION["synEditable"][$i])."', `ismultilang`='".addslashes($_SESSION["synMultilang"][$i])."', `label`='".addslashes($_SESSION["synElmLabel"][$i])."', $size `help`='".addslashes($_SESSION["synElmHelp"][$i])."', `path`='".addslashes($_SESSION["synElmPath"][$i])."', `qry`='".addslashes($_SESSION["synElmQry"][$i])."', `value`='".addslashes($_SESSION["synElmValue"][$i])."', `joins`='".addslashes($_SESSION["synElmJoinsItem"][$i])."', `order`=".addslashes($_SESSION["synElmOrder"][$i]).", `filter`='".addslashes($_SESSION["synElmFilter"][$i])."' WHERE id=".$_SESSION["synElmId"][$i]."");
          } else {
            $_SESSION["synElmLabel"][$i]=insertTranslation($_SESSION["synElmLabelLang"][$i]);
            $_SESSION["synElmHelp"][$i]=insertTranslation($_SESSION["synElmHelpLang"][$i]);
            if ($_SESSION["synElmSize"][$i]=="" or $_SESSION["synElmSize"][$i]==0) $_SESSION["synElmSize"][$i]="0"; 
            $db->Execute("INSERT INTO aa_services_element (`container`, `name`, `type`, `iskey`, `isvisible`, `iseditable`, `ismultilang`, `label`, `size`, `help`, `path`, `qry`, `value`, `joins`, `order`, `filter`) VALUES ($id, '".addslashes($_SESSION["synElmName"][$i])."','".addslashes($_SESSION["synElmType"][$i])."', '".addslashes($_SESSION["synKey"][$i])."', '".addslashes($_SESSION["synVisible"][$i])."', '".addslashes($_SESSION["synEditable"][$i])."', '".addslashes($_SESSION["synMultilang"][$i])."', '".addslashes($_SESSION["synElmLabel"][$i])."','".addslashes($_SESSION["synElmSize"][$i])."','".addslashes($_SESSION["synElmHelp"][$i])."','".addslashes($_SESSION["synElmPath"][$i])."','".addslashes($_SESSION["synElmQry"][$i])."','".addslashes($_SESSION["synElmValue"][$i])."','".addslashes($_SESSION["synElmJoinsItem"][$i])."', ".addslashes($_SESSION["synElmOrder"][$i]).",'".addslashes($_SESSION["synElmFilter"][$i])."')");
          }

          //get the last id and add it at the delsql text
          $res=$db->Execute("SELECT id FROM aa_services_element WHERE `container`=$id AND `name`='".addslashes($_SESSION["synElmName"][$i])."' AND `type`='".addslashes($_SESSION["synElmType"][$i])."' AND `iskey`='".addslashes($_SESSION["synKey"][$i])."' AND `isvisible`='".addslashes($_SESSION["synVisible"][$i])."' AND `iseditable`='".addslashes($_SESSION["synEditable"][$i])."' AND `label`='".addslashes($_SESSION["synElmLabel"][$i])."'  AND `help`='".addslashes($_SESSION["synElmHelp"][$i])."' AND `path`='".addslashes($_SESSION["synElmPath"][$i])."' AND `qry`='".addslashes($_SESSION["synElmQry"][$i])."' AND `value`='".addslashes($_SESSION["synElmValue"][$i])."' AND `joins`='".addslashes($_SESSION["synElmJoinsItem"][$i])."' AND `order`=".addslashes($_SESSION["synElmOrder"][$i])." AND `filter`='".addslashes($_SESSION["synElmFilter"][$i])."'");
          list($lastId)=$res->FetchRow();
          //$lastId=$res->Insert_ID();
          $delsql.=$lastId.", ";

          //select the ID of the just updated/inserted element and use it for the order by field 
          if ($i+1==abs($_SESSION["synInitOrder"])) {
            $sign=$_SESSION["synInitOrder"]/abs($_SESSION["synInitOrder"]);
            $res=$db->Execute("SELECT id FROM aa_services_element WHERE `container`=$id AND `name`='".addslashes($_SESSION["synElmName"][$i])."' AND `type`='".addslashes($_SESSION["synElmType"][$i])."' AND `iskey`='".addslashes($_SESSION["synKey"][$i])."' AND `isvisible`='".addslashes($_SESSION["synVisible"][$i])."' AND `iseditable`='".addslashes($_SESSION["synEditable"][$i])."' AND `label`='".addslashes($_SESSION["synElmLabel"][$i])."' AND `size`='".addslashes($_SESSION["synElmSize"][$i])."' AND `help`='".addslashes($_SESSION["synElmHelp"][$i])."' AND `path`='".addslashes($_SESSION["synElmPath"][$i])."' AND `qry`='".addslashes($_SESSION["synElmQry"][$i])."' AND `value`='".addslashes($_SESSION["synElmValue"][$i])."' AND `joins`='".addslashes($_SESSION["synElmJoinsItem"][$i])."' AND `order`=".addslashes($_SESSION["synElmOrder"][$i])." AND `filter`='".addslashes($_SESSION["synElmFilter"][$i])."'");
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
            $opt.="<option value=\"".$arr2["id"]."\">".translateSite($arr2["name"])."</option>";
          }
          echo "- Parent menu item: <select name=\"startingPoint[".$idgroup."]\">".$opt."</select><br/>";
        }
        echo $synHtml->button(" value='".$str["save"]."' class='action_button' ");
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
