<?php
function smarty_function_account($params, &$smarty) {
  global $db, $synPublicPath, $synRootPasswordSalt, $synWebsiteTitle, $synAdministrator;

  include('_user_labels.php');

  if(!isset($_SESSION))
    session_start();

  if ( empty($_SESSION['synSiteLang'])
    || !isset($_SESSION['synSiteLang'])
    ){
    updateLang();
  }
  $lang = $_SESSION['synSiteLangInitial'];

  $command = '';
  if (isset($_POST['cmd']) && !empty($_POST['cmd'])){
    $command = filter_input(INPUT_POST, 'cmd', FILTER_SANITIZE_STRING);
  } elseif (isset($_GET['cmd']) && !empty($_GET['cmd'])){
    $command = filter_input(INPUT_GET, 'cmd', FILTER_SANITIZE_STRING);
  } elseif  ( (isset($params['cmd']) && !empty($params['cmd'])) ) {
    $command = addslashes($params['cmd']);
  }

  if (isset($_POST['ret']) && !empty($_POST['ret'])){
    $ret = filter_input(INPUT_POST, 'ret', FILTER_SANITIZE_URL);
  } elseif (isset($_GET['ret']) && !empty($_GET['ret'])){
    $ret = filter_input(INPUT_GET, 'ret', FILTER_SANITIZE_URL);
  }

  $pageurl = createPath($smarty->getTemplateVars('synPageId'));
  $l       = multiTranslateDictionary($labels, false);
  $sidebar = '';
  $html    = '';


  if(!empty($ret) && is_numeric($ret)){
    $return_page = createPath($ret);
  } else {
    $return_page = $pageurl;
  }

  $account = new synAccount($db, $synRootPasswordSalt, ACCOUNT_KEY);
  $account->setParams(array(
    'labels'      => $l,
    'lang'        => $lang,
    'return_page' => $return_page,
    'sitename'    => $synWebsiteTitle,
    'siteurl'     => 'http://'.$_SERVER['SERVER_NAME'],
    'pageurl'     => $pageurl,
    'publicpath'  => $synPublicPath,
    'sender'      => array('name'=>ADMIN_NAME, 'mail'=> ADMIN_MAIL),
    //'debug' => true
    ));

    // destinatari per notifiche NUOVA REGISTRAZIONE
    $recipients = array(
      'assistenza@kleis.it'
      );

  switch($command){
    case 'register':
      $smarty->assign( 'return_page', $return_page );
      //$html  = $account->get_flash_message();
      $html = $smarty->fetch('private/_register_form.tpl');
      break;


    case 'edit':
      $user = $account->getUserData();
      if ($user['installation_company']) {
        $data = explode("\n", $user['installation_company']);
        $user['ic_name'] = $data[0];
        $user['ic_address'] = $data[1];
        $user['ic_fiscal_code'] = $data[2];
      }
      $smarty->assign( 'user', $user );
      $smarty->assign( 'return_page', $return_page );
      //$html  = $account->get_flash_message();
      $html = $smarty->fetch('private/_user_form.tpl');
      break;


    case 'forgot_password':
      $smarty->assign( 'return_page', $return_page );
      //$html  = $account->get_flash_message();
      $html = $smarty->fetch('private/_reset_password.tpl');
      break;


    case 'reset_password':
      $user = $account->getUserDataByHash();
      $smarty->assign( 'user', $user );
      $html = $smarty->fetch('private/_reset_password_form.tpl');
      break;


    case 'change_password':
      $user = $account->getUserData();
      $smarty->assign( 'user', $user );
      $smarty->assign( 'return_page', $return_page );
      //$html  = $account->get_flash_message();
      $html = $smarty->fetch('private/_password_form.tpl');
      break;


    case 'change_email':
      $smarty->assign( 'user', $account->getUserData() );
      $smarty->assign( 'return_page', $return_page );
      //$html  = $account->get_flash_message();
      $html = $smarty->fetch('private/_email_form.tpl');
      break;

    case 'activate':
      $account->activate( $recipients );
      break;

    case 'reactivate':
      // richiedi una nuova mail attivazione
      break;


    case 'logout':
      header('P3P: CP="NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM"'); // privacy policy per IE6
      header("location: {$synPublicPath}/server/account_helper.php?cmd={$command}&ret={$pageurl}");
      exit();
      break;

    case 'login':
    default:
      if ($account->is_logged_in()) {
        $user = $account->checkLogin();
        $smarty->assign( 'user', $account->getUserData() );
        $html = $smarty->fetch('private/_welcome.tpl');
      } else {
        $html = $smarty->fetch('private/_login.tpl');
      }
      break;
  }

  $smarty->assign('content', $html);
}


// end function.account.php