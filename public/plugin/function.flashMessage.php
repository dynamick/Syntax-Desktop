<?php
function smarty_function_flashMessage($params, &$smarty){
  if(!isset($_SESSION))
    session_start();

  $message = null;
  $type = null;

  if ( isset($_SESSION['flash']['message'])
    && !empty($_SESSION['flash']['message'])
    ){
    $message = $_SESSION['flash']['message']['text'];
    $type = $_SESSION['flash']['message']['type'];

    unset($_SESSION['flash']['message']);
  }

  if ( isset($_SESSION[ACCOUNT_KEY]['message'])
    && !empty($_SESSION[ACCOUNT_KEY]['message'])
    ){
    $message = $_SESSION[ACCOUNT_KEY]['message']['text'];
    $type = $_SESSION[ACCOUNT_KEY]['message']['type'];

    unset($_SESSION[ACCOUNT_KEY]['message']);
  }

  if (!empty($message)) {
    $smarty->assign('msg', $message);
    $smarty->assign('type', $type);
    $smarty->display('partial/_alert.tpl');
  }
}

// EOF