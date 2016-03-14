<?php
  //this is an auto-generated page by SyntaxDesktop
  error_reporting(E_ALL & ~(E_NOTICE | E_DEPRECATED | E_WARNING));
  ini_set('display_errors', 1);

  header('Content-type: text/html; charset=utf-8');
  header('X-UA-Compatible: IE=edge,chrome=1');

  if(ini_get('date.timezone')=='')
    date_default_timezone_set('Europe/Rome');

  if (file_exists(dirname(__FILE__).'/public/config/cfg.php')) {
    require('public/config/cfg.php');
    // start session
    s_start();

  } else {
    die('Is it the first time you run Syntax Desktop? If yes, run <a href="admin/setup.php">setup</a>.');
  }

  // check if we must redirect the page
  // checkRedirects();

  // Main
  $pageId = getPageId();

  $smarty = new synSmarty();
  $smarty->setPage($pageId);

  // enable gzip compression
  //ob_start( 'ob_gzhandler' );
  $smarty->display($smarty->synTemplate);
  //ob_flush();

// EOF index.php
