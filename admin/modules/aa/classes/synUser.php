<?php
  class synUser {
    var $userId;
    var $groupId;
    var $groupName;
    var $canInsert=1;
    var $canModify=1;
    var $canDelete=1;

    function synUser() {
      global $db;
      //check if user session is active
      $this->userId=getSynUser();
      if (!$this->userId) {
        echo "<script>alert('Sessione scaduta. Rieffettuare il login'); window.top.location.reload();</script>";
        die("sessione scaduta");
      }

      //retrieve the groupId and groupName
      //$qry="SELECT * FROM aa_groups";
      $qry="SELECT * FROM aa_groups WHERE id='".$_SESSION["synGroup"]."'";
      $res=$db->Execute($qry);
      $arr=$res->FetchRow($res);
      $this->groupId=$arr["id"];
      $this->groupName=$arr["name"];

      //check the permission of this service for the current user
      $qry="SELECT * FROM `aa_group_services` WHERE `group` = '".$this->groupId."' and service='".$_SESSION["aa_service"]."'";
      $res=$db->Execute($qry);
      if ($res->RecordCount()>0) {
        $arr=$res->FetchRow($res);
        $arr["insert"]==""?$this->canInsert=0:$this->canInsert=1;
        $arr["modify"]==""?$this->canModify=0:$this->canModify=1;
        $arr["delete"]==""?$this->canDelete=0:$this->canDelete=1;
      }
    } //end of construction

  }//end of class
?>