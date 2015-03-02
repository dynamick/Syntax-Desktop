<?php
/*
* Smarty plugin
* -------------------------------------------------------------
* File:     function.boxUtente.php
* Type:     function
* Name:     boxUtente
* Purpose:  Gestisce il box di autenticazione utente
* -------------------------------------------------------------
*/
function smarty_function_regBoxUser($params, &$smarty) {
  global $db, $synPublicPath, $synRootPasswordSalt, $synWebsiteTitle, $synAdministrator;

  $lng = $_SESSION['synSiteLangInitial'];
  $path = createPath($smarty->getTemplateVars('synPageId'));
  $account_url = createPath(PAGE_ACCOUNT);
  $account_helper = $synPublicPath.'/server/account_helper.php';

  $l = multiTranslateDictionary(array('reserved_area','login','logout','registrati','recupero_password'));
  $html = '';

  $account = new synAccount($db, $synRootPasswordSalt, ACCOUNT_KEY);
  $user = $account->checkLogin();
  if ($account->is_logged_in()) {
    $logged = true;
    $name = $account->welcome_user();
    $html .= <<<EOHTML

    <a href="{$account_url}" class="btn btn-primary">
      <i class="fa fa-user"></i>
      {$name}
    </a>
    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
      <span class="caret"></span>
      <span class="sr-only">Dropdown</span>
    </button>
    <ul class="dropdown-menu pull-right" role="menu">
      <li><a href="{$account_url}?cmd=edit">I tuoi dati</a></li>
      <li><a href="{$account_url}?cmd=change_password">Cambia password</a></li>
      <li><a href="{$account_url}?cmd=change_email">Cambia email</a></li>
      <li class="divider"></li>
      <li><a href="{$account_helper}?cmd=logout"><i class="fa fa-power-off"></i> {$l['logout']}</a></li>
    </ul>
EOHTML;


  } else {
    $logged = false;
    $action = $account->getParam('action');
    $html .= <<<EOHTML
    <a href="{$account_url}" class="btn btn-primary">
      <i class="fa fa-unlock-alt"></i>
      {$l['reserverd_area']}
    </a>
EOHTML;
  }
//echo '<pre>', print_r($user), '</pre>'; die();
  $smarty->assign('user_data', $user);
  $smarty->assign('logged_in', $logged);
  $smarty->assign('user_button', $html);
  //return $html;
}


// EOF function.regBoxUser.php
