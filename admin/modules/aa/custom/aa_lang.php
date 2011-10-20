<?
global $cmd;

	switch($cmd) {
		case "":
    break;
		case ADD:  
    break;
		case MODIFY:  
    break;
		case CHANGE:  
    break;
		case INSERT:
      $qry="ALTER TABLE `aa_translation` ADD `".$_REQUEST["initial"]."` TEXT NOT NULL";
      @$res=$db->Execute($qry);     
      
      $qry="SELECT * FROM `aa_lang`";
      $res=$db->Execute($qry);
      $arr=$res->FetchRow();
      $copyLang=$arr["initial"];
      $qry="UPDATE `aa_translation` SET `".$_REQUEST["initial"]."`=".$copyLang;
      $res=$db->Execute($qry);
    break;
		case DELETE: 
      $qry="SELECT * FROM aa_lang WHERE ".stripslashes($_REQUEST["synPrimaryKey"]);
      $res=$db->Execute($qry);
      while ($arr=$res->FetchRow()) {
        $qry="ALTER TABLE `aa_translation` DROP `".$arr["initial"]."`";
        @$db->Execute($qry);      
      }

    break;
    case MULTIPLEDELETE:
      if (is_array($_REQUEST["checkrow"]))
      foreach ($checkrow as $k=>$v) {

        $qry="SELECT * FROM aa_lang WHERE ".urldecode($v);
        $res=$db->Execute($qry);
        while ($arr=$res->FetchRow()) {
          $qry="ALTER TABLE `aa_translation` DROP `".$arr["initial"]."`";
          @$db->Execute($qry);      
        }
      } 
    break;
    case RPC:
    break;
  }
  

?>