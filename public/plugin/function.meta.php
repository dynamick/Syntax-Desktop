<?php

function smarty_function_meta($params, &$smarty){
  global $db, $synWebsiteTitle;

  $req  = isset($_GET['id'])      ? intval($_GET['id']) : 0;
  $line = isset($_GET['idlinea']) ? intval($_GET['idlinea']) : 0;
  $area = isset($_GET['idarea'])  ? intval($_GET['idarea']) : 0;
	$lang = $_SESSION["synSiteLangInitial"];

  $template         = $smarty->getTemplateVars('synTemplate');
  $title_page 			= $smarty->getTemplateVars('synPageMeta_title') ? $smarty->getTemplateVars('synPageMeta_title') : $smarty->getTemplateVars('synPageTitle');
  $description_page = $smarty->getTemplateVars('synPageMeta_description');
  $keyword_page 		= $smarty->getTemplateVars('synPageMeta_keywords');
  $canonical        = '';
  $qry              = '';
  $qry_model        = <<<EOQ

       SELECT i.id,
              t1.{$lang} AS meta_title, t2.{$lang} AS meta_description, t3.{$lang} AS meta_keywords, t4.{$lang} AS title
         FROM %s i
    LEFT JOIN aa_translation t1 ON t1.id = i.meta_title
    LEFT JOIN aa_translation t2 ON t2.id = i.meta_description
    LEFT JOIN aa_translation t3 ON t3.id = i.meta_keywords
    LEFT JOIN aa_translation t4 ON t4.id = i.%s
        WHERE i.id = '%d'

EOQ;

  // valorizzo la qry
  if($template == 'news.tpl' && $req>0){
    // notizie
    $qry = sprintf($qry_model, 'news', 'title', $req);

  } else if($template == 'prodotti.tpl'){
    //$1/?area=$2&idarea=$3&linea=$4&idlinea=$5&prodotto=$6&id=$7
    if ($req>0) {
      // articoli (prodotti)
      $qry = sprintf($qry_model, 'articoli', 'nome', $req);

    } elseif ($line>0) {
      // linee
      $qry = sprintf($qry_model, 'linee', 'nome', $line);

    } elseif ($area>0) {
      // aree
      $qry = sprintf($qry_model, 'aree', 'nome', $area);
    }

  } else if ($template == 'referenze.tpl' && $req>0) {
    $qry = sprintf($qry_model, 'referenze', 'nome', $req);
  }

  //echo $qry.'<br>';
  if (!empty($qry)) {
		$res = $db->Execute($qry);
		if($arr = $res->FetchRow()){
  		extract($arr);
      $title       = ($meta_title!='') ? $meta_title : $title; //fallback su titolo dell'item
  		$description = $meta_description;
  		$keyword     = $meta_keywords;
  	}
  }

  if(trim($title)=='')
  	$title = $title_page;

	if(trim($description)=='')
  	$description = $description_page;

  if(trim($keyword)=='')
  	$keyword = $keyword_page;

  if ( isset($_GET['synSiteLang'])
    || isset($_GET['_next_page'])
    ){
    $server = $_SERVER['SERVER_NAME'];
    $page_path = createPath($smarty->getTemplateVars('synPageId'));

    if (isset($_GET['title'])){
      $page_path .= $_GET['title'].'~'.$_GET['id'].($template=='referenze.tpl' ? '/' : '.html');
    }
    if (isset($_GET['parent'])){
      $page_path .= htmlspecialchars($_GET['parent']);
    }
    $canonical = "<link rel=\"canonical\" href=\"http://{$server}{$page_path}\">\n";
  }

 	$smarty->assign('meta_title',       $title.' > '.$synWebsiteTitle);
	$smarty->assign('meta_description', $description);
	$smarty->assign('meta_keywords',    $keyword);
  $smarty->assign('meta_canonical',   $canonical);
}

// EOF
