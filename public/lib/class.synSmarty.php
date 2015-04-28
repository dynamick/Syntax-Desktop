<?php
require('smarty/Smarty.class.php');

// The setup.php file is a good place to load
// required application library files, and you
// can do that right here. An example:
// require('guestbook/guestbook.lib.php');

/*|
|*| Marco 2010-12-02
|*| Nuova versione per Smarty 3.0
|*/
class synSmarty extends Smarty {
   var $synTemplate, $synPackagePath, $synPluginPath;

   public $synPageNode = array();
   public $synPageScript = array();

   function synSmarty($pageId) {
      global $synAbsolutePath, $synPublicPath, $synPluginPath;
      // Class Constructor. These automatically get set with each new instance.
      parent::__construct();
      $lang = getLangInitial();

      # paths
      $this->template_dir   = $synAbsolutePath.$synPublicPath.'/templates/';
      $this->compile_dir    = $synAbsolutePath.'/cache/';
      $this->cache_dir      = $synAbsolutePath.'/cache/';
      $this->plugins_dir    = array(
                                $synAbsolutePath.$synPublicPath.'/lib/smarty/plugins/',
                                $synAbsolutePath.$synPublicPath.$synPluginPath);

      $this->debugging      = false;
      $this->caching        = false;
      $this->cache_lifetime = 100;
      $this->synTemplate    = $this->getSynTemplate($pageId);

      $this->clearCompiledTemplate($this->synTemplate);
      $this->traverseTree($pageId);

      $this->assign('synTemplate', $this->synTemplate);
      $this->assign('synPageScript', $this->synPageScript);
      $this->assign('synLangInitial', $lang);
   }



  // get the page template and extract the other page variables
  // the variable will be available in the smarty template using this form:
  // synPageXxxxx

  function getSynTemplate($pageId) {
    global $db, $synPublicPath, $synAdminPath, $synAbsolutePath, $synWebsiteTitle;

    $qry = <<<EOQRY

    SELECT aa_page.*,
           aa_template.id as template_id, aa_template.filename
      FROM aa_page,
           aa_template
     WHERE aa_page.id = '{$pageId}'
       AND aa_page.template = aa_template.id

EOQRY;
    //echo $qry, '<br>';

    $db->setFetchMode(ADODB_FETCH_ASSOC);
    $res = $db->Execute($qry);

    if ($res->RecordCount() > 0)
      $arr = $res->FetchRow();
    else
      return false;

    $qry = "SELECT id FROM aa_services WHERE syntable='aa_page'";
    $res_temp = $db->Execute($qry);
    $arr_temp = $res_temp->FetchRow();
    $id_service = $arr_temp["id"];

    foreach ($arr as $key => $value) {
      $qry = "SELECT * FROM aa_services_element WHERE container='{$id_service}' AND name='{$key}'";
      $res_temp = $db->Execute($qry);
      $arr_temp = $res_temp->FetchRow();
      if($arr_temp["ismultilang"]==1){
        $value = translateSite($value);
        $this->assign("synPage".ucfirst($key)."_lang", "_".$_SESSION["synSiteLangInitial"]);
      }
      $this->assign("synPage".ucfirst($key), $value);
    }

    $this->assign('synAdminPath', $synAdminPath);
    $this->assign('synPublicPath', $synPublicPath);
    $this->assign('synAbsolutePath', $synAbsolutePath);
    $this->assign('synWebsiteTitle', $synWebsiteTitle);

    //variabile per riconoscere le chiamate via AJAX
    $xhr = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
    $this->assign('ajax_call', $xhr);

    //se il template esiste su file allora prendo quello, altrimenti lo carico dal database
    $filename = $arr['filename'];
    $template_id = $arr['template_id'];
    if ($filename != '')
      $template = $filename;
    else
      $template = "db:".$template_id;

    $db->setFetchMode(ADODB_FETCH_BOTH);
    return $template;
  }

/*
  DEPRECATED
  function traverseTree($id) {
    global $db;
    static $synLevel=0;
    $qry="SELECT dest.*,src.title as srctitle FROM aa_page src, aa_page dest WHERE src.id='$id' AND src.parent=dest.id";
    $res=$db->Execute($qry);
    if ($res->RecordCount()==0) {
      $qry="SELECT title FROM aa_page WHERE id='$id'";
      $res=$db->Execute($qry);
      $arr=$res->FetchRow();
      $title=translateSite($arr["title"]);
      $this->synPageNode[$synLevel]["id"]=$id;
      $this->synPageNode[$synLevel]["title"]=$title;
      $synLevel++;
      return;
    }
    $arr=$res->FetchRow();
    $new_id=$arr["id"];
    $new_title=translateSite($arr["srctitle"]);
    $ret=$this->traverseTree($new_id);
    $this->synPageNode[$synLevel]["id"]=$id;
    $this->synPageNode[$synLevel]["title"]=$new_title;
    $this->assign("synPageNode".$synLevel, $id);
    $this->assign("synPageLevel", $synLevel);
    $synLevel++;
    return;
  }
*/

  // new version
  // marco 2010-12-28
  function traverseTree($id) {
    global $db;
    static $synLevel=0;
    if(!$id) return;

    $lng = $_SESSION['synSiteLangInitial'];
    $qry = <<<EOQ
    SELECT p.parent, t1.$lng AS title, t2.$lng AS slug
      FROM aa_page p
 LEFT JOIN aa_translation t1 ON p.title = t1.id
 LEFT JOIN aa_translation t2 ON p.slug = t2.id
     WHERE p.id={$id}
EOQ;
    $res = $db->Execute($qry);
    if($arr = $res->FetchRow()){
      $this->traverseTree($arr['parent']);
      $this->synPageNode[$synLevel]['id'] = $id;
      $this->synPageNode[$synLevel]['slug'] = $arr['slug'];
      $this->synPageNode[$synLevel]['title'] = $arr['title'];
      $this->assign('synPageNode'.$synLevel, $id);
      $this->assign('synPageLevel', $synLevel);
      $synLevel++;
    }
    return;
  }
}

// EOF class.synSmarty.php