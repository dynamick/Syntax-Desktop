<?php
  //this is an auto-generated page by SyntaxDesktop
  error_reporting(E_ALL & ~(E_NOTICE | E_DEPRECATED | E_WARNING));
  ini_set('display_errors', 1);

  header('Content-type: text/html; charset=utf-8');
  header('X-UA-Compatible: IE=edge,chrome=1');

  if(!isset($_SESSION))
    session_start();

  if(ini_get('date.timezone')=='')
    date_default_timezone_set('Europe/Rome');

  if (file_exists(dirname(__FILE__).'/public/config/cfg.php')) {
    require('public/config/cfg.php');
  } else {
    die('Is it the first time you run Syntax Desktop? If yes, run <a href="admin/setup.php">setup</a>.');
  }

  // Main
  $pageId = getPageId();

  $smarty = new synSmarty();
  $smarty->setPage($pageId);
  $smarty->display($smarty->synTemplate);

// EOF index.php