<?php
/*
* Smarty plugin
* -------------------------------------------------------------
* File:     function.userMenu.php
* Type:     function
* Name:     boxUtente
* Purpose:  Gestisce il box di autenticazione utente
* -------------------------------------------------------------
*/
function smarty_function_userMenu($params, &$smarty) {
  global $db, $synPublicPath, $synRootPasswordSalt, $synWebsiteTitle, $synAdministrator;

  //include('_user_labels.php');
  $lng = getLangInitial();
  $path = createPath( $smarty->getTemplateVars('synPageId') );
  $account_url = createPath(PAGE_ACCOUNT);
  $account_helper = $synPublicPath.'/server/account_helper.php';
  $l = multiTranslateDictionary(array('reserved_area','login','logout','registrati','recupero_password'));
  $html = '';

  $account = new synAccount($db, $synRootPasswordSalt, ACCOUNT_KEY);
  /*$account->setParams(array(
    'labels'      => $l,
    'lang'        => $lng,
    'return_page' => $path,
    'sitename'    => $synWebsiteTitle,
    'siteurl'     => 'http://'.$_SERVER['SERVER_NAME'],
    'pageurl'     => $account_url,
    'publicpath'  => $synPublicPath,
    'sender'      => array('name'=>ADMIN_NAME, 'mail'=> ADMIN_MAIL),
    //'debug' => true
    ));*/
  $user = $account->checkLogin();

  if ($account->is_logged_in()) {
    $logged = true;

    $label = $account->welcome_user();



    $name = $account->welcome_user();
    $html .= <<<EOHTML

    <a href="{$account_url}" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
      <i class="fa fa-user"></i>
      {$name}
      <i class="fa fa-angle-down"></i>
    </a>
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

    $label = $l['reserverd_area'];

/*
<li class="open">
  <a class="upper dropdown-toggle" data-toggle="dropdown" href="#" id="dropdownMenuLogin" aria-expanded="true">Login</a>
  <div class="dropdown-menu widget-box" aria-labelledby="dropdownMenuLogin">
    <form>
      <div class="form-group">
        <label class="sr-only">Username or Email</label>
        <input type="text" class="form-control input-lg" name="" placeholder="Username or Email" value="">
      </div>
      <div class="form-group">
        <label class="sr-only">Password</label>
        <input type="password" class="form-control input-lg" name="" placeholder="Password" value="">
      </div>
      <div class="form-inline form-group">
        <button class="btn btn-primary btn-xs" type="button">Login</button>
        <div class="checkbox">
          <label>
            <input type="checkbox"><small> Remember me</small>
          </label>
        </div>
      </div><a href="#"><small>Lost your Password?</small></a>
    </form>
  </div>
</li>
*/

    $action = $account->getParam('action');
    $html .= <<<EOHTML
    <a href="{$account_url}" class="">
      <i class="fa fa-unlock-alt"></i>
      {$l['reserverd_area']}
    </a>
EOHTML;
  }

  $smarty->assign( 'user_data', $user );
  $smarty->assign( 'logged_in', $logged );
  $smarty->assign( 'user_button', $html );
}

// EOF function.userMenu.php