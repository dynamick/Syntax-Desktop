<?php
  include ("../config/cfg.php");

  if(!isset($_SESSION))
    session_start();

  if ( empty($_SESSION['synSiteLang'])
    || !isset($_SESSION['synSiteLang'])
    ){
    updateLang();
  }
  $lang = $_SESSION['synSiteLangInitial'];

  include('../plugin/_user_labels.php');
  $l = multiTranslateDictionary($labels);

  $command = '';
  if (isset($_POST['cmd']) && !empty($_POST['cmd'])){
    $command = filter_input(INPUT_POST, 'cmd', FILTER_SANITIZE_STRING);
  } elseif (isset($_GET['cmd']) && !empty($_GET['cmd'])){
    $command = filter_input(INPUT_GET, 'cmd', FILTER_SANITIZE_STRING);
  }

  if ( isset($_POST['ret']) && !empty($_POST['ret']) ){
    $return_page = filter_input(INPUT_POST, 'ret', FILTER_SANITIZE_URL);

  } elseif ( isset($_GET['ret']) && !empty($_GET['ret'])){
    $return_page = filter_input(INPUT_GET, 'ret', FILTER_SANITIZE_URL);
  } else {
    $return_page = createPath(PAGE_ACCOUNT);
  }

  $account = new synAccount($db, $synRootPasswordSalt, ACCOUNT_KEY);
  $account->setParams(array(
    'labels'      => $l,
    'lang'        => $lang,
    'return_page' => $return_page,
    'sitename'    => $synWebsiteTitle,
    'siteurl'     => 'http://'.$_SERVER['SERVER_NAME'],
    'pageurl'     => createPath(PAGE_ACCOUNT),
    'sender'      => array('name' => ADMIN_NAME, 'mail' => ADMIN_MAIL)
    //,'debug'       => true
  ));

  switch($command){
    case 'create':
      $account->create();
      break;

    case 'send_again':
      $account->send_again();
      break;

    case 'activate':
      $account->activate();
      break;

    case 'change_password':
      $account->change_password();
      break;

    case 'send_new_password':
      $account->send_new_password();
      break;

    case 'reset_password':
      $account->reset_password();
      break;

    case 'update':
      $account->update();
      break;

    case 'set_new_email':
      $account->set_new_email();
      break;

    case 'activate_new_email':
      $account->activate_new_email();
      break;

    case 'login':
      $account->login();
      break;

    case 'logout':
      $account->logout();
      break;

    default:
      break;
  }

  header('P3P: CP="NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM"'); // privacy policy per IE6
  header("location: ".$return_page);
  exit();

//EOF