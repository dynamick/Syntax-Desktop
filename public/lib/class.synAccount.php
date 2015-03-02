<?php
/*
 * Marco 2013/11/28
 */

class synAccount {

  static $db;     // db wrapper (oggetto)
  static $salt;   // password salt (stringa)
  private $key;    // nome del cookie/sessione (stringa)

  protected $labels       = '';
  protected $lang         = 'it';
  protected $action       = '/public/server/account_helper.php';
  protected $expiration   = '';
  protected $sitename     = 'www.teknopoint.com';
  protected $siteurl      = 'www.teknopoint.com';
  protected $pageurl      = '/area-privata/';
  protected $publicpath   = '';
  protected $sender       = array('name'=>'teknopoint', 'mail'=>'tecnico@teknopoint.com');
  protected $js           = '';
  protected $return_page  = '';
  protected $debug        = false;
  protected $marker       = '<abbr title="campo obbligatorio">&lowast;</abbr>';

  // NB. le variabili senza valore non si possono settare con setParams()
  protected $is_logged;

  private $error = array();


  function __construct(ADODB_PDO $db, $salt = '', $key = 'web_user'){
    self::$db = $db;
    self::$salt = $salt;
    $this->key = $key;

    $this->checkLogin();
    $this->expiration = time()+(86400 * 30);  //30 days
  }


  // setter generico
	function setParams($params = array()){
		if ( is_array($params)
      && !empty($params)
      ){
      //echo '<pre>', print_r($params), '</pre>';
			foreach($params as $key => $val){
				if(isset($this->$key)){
					$this->$key = $val;
				} //else echo "impossibile settare {$key}<br>";
			}
		}
	}


  // getter generico
  function getParam($key){
    if(isset($this->$key)){
      return $this->$key;
    }
    return false;
  }


  function login(){
    $mail = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $pass = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
    $persistent = filter_input(INPUT_POST, 'persistent', FILTER_VALIDATE_BOOLEAN);

    if ($mail && $pass) {
      $user = new synUser(self::$db);
      if ($user->get_user_by_email($mail)) {
        // utente trovato
        if ($this->hash($pass) == $user->password) { //$user->password
          // password ok

          if ($persistent) {
            setcookie("{$this->key}[id]", $this->hash($user->id), $this->expiration, '/');
            setcookie("{$this->key}[group]", $group, $this->_expires, '/');
          } else {
            $_SESSION[$this->key]['id'] = $user->id;
            $_SESSION[$this->key]['group'] = $user->group;
          }
          $user->update_login_info($user->id, true, true, true);
          $this->set_flash_message($this->labels['autenticato'].' '.$user->email, 'success');
          //return true;

        } else {
          // password sbagliata
          $user->increase_login_attempts($user->id);
          $this->error = 'error_incorrect_password';
        }
      } else {
        // utente non trovato
        $this->error = 'error_unknown_email';
      }

    } else {
      // input non valido ??
      $this->error = 'error_invalid_input';
    }

    if (!empty($this->error)) {
      $this->set_flash_message($this->labels[$this->error], 'danger');
    }

    if ($this->debug) {
      echo "{$this->hash($pass)} == {$user->password}<br>";
      echo '<pre>', $this->get_flash_message(false), '</pre>';
      echo "<a href=\"{$this->return_base_path()}?cmd=\">continua</a><br>";
      die();

    } else {
      return true;
      //header('location: '.$this->return_base_path().'?cmd=');
    }
    return false;
  }


  function logout(){
    if (isset($_COOKIE[$this->key]['id'])) {
      setcookie("{$this->key}[id]", '', time()-3600, '/');
    }
    unset($_SESSION[$this->key]);
    $this->is_logged = false;
    $this->set_flash_message($this->labels['message_logout'], 'success');
    //$this->_show_message($this->lang->line('auth_message_logged_out'));
  }


  function checkLogin(){
    if(!isset($_SESSION))
      session_start();

    $this->is_logged = false;
    $found =  false;

    //$cookie = filter_input(INPUT_COOKIE, "{$this->key}['id']", FILTER_SANITIZE_STRING);
    $cookie = isset($_COOKIE[$this->key]['id']) ? addslashes($_COOKIE[$this->key]['id']) : false;
    $session = isset($_SESSION[$this->key]['id']) ? intval($_SESSION[$this->key]['id']) : false;

    if ($cookie || $session) {
      $user = new synUser(self::$db);

      if (!empty($cookie)) {
        $found = $user->get_user_by_hash($cookie);
      } elseif (!empty($session)) {
        $found = $user->get_user_by_id($session);
      }

      if ($found) {
        $_SESSION[$this->key]['id'] = $user->id;
        $_SESSION[$this->key]['group'] = $user->group;
        $this->is_logged = true;

        return $user;
      } else {
        return false;
      }
    } else {
      return false;
    }
  }


  function is_logged_in(){
    return $this->is_logged;
  }


  function loginForm(){
    $formId = '01';
    $session = isset($_SESSION['form'.$formId]) ? $_SESSION['form'.$formId] : false;
    $l = $this->labels;

    //$form = new formBuilder($formId, $l);
    $form = new formBuilder($formId, $l);
    $form->setAttributes(array(
      'reset_button'         => false,
      'include_script'       => false,
      'hide_label'           => true,
      'input_class'          => 'input-block-level',
      'button_class'         => 'btn btn-inverse btn-block',
      'submit_button_class'  => null,
      'container_element'    => 'p',
      'implicit_label_class' => 'implicit',
      'class'                => 'span4 offset4',
      'action'               => $this->action,
      'required_mark'        => $this->marker,
      'error1'               => $l['error_required_field'],
      'error2'               => $l['error_invalid_email'],
      'error3'               => $l['verifica_valore'],
      'error4'               => $l['reinserire_stesso_valore'],
      'submitLabel'          => $l['login_submit'] //,      'hide_label'=>true
    ));

    $form->addField('email',       $l['email'],            NULL, 'text', true, 'email');
    $form->addField('password',    $l['password'],         NULL, 'password', true, 'text');
    $form->addField('persistent',  $l['login_automatico'], 1, 'checkbox');
    $form->addField('cmd',         NULL,                  'login', 'hidden');

    if($this->return_page)
      $form->addField('ret', NULL, $this->return_page, 'hidden');

    // validation errors
    if(isset($session['error'])){
	  	$form->errorMsg($session['error']);
	  	unset($_SESSION['form'.$formId]);
    }
    // class errors
    if(!empty($this->error)){
	  	$form->errorMsg($this->error);
	  	unset($this->error);
    }
    $this->js = $form->validateScript();

    return $form->render();
  }


  public function welcome(){
    if($this->is_logged_in()){
      $logged_id = intval($_SESSION[$this->key]['id']);
      $user = new synUser(self::$db, $logged_id, $this->publicpath);
      $user->get_user_by_id($logged_id);
      $name = ($user->company) ? $user->company : $user->name.' '.$user->surname;

      $html = <<<EOHTML
      <strong>{$this->labels['benvenuto']}</strong>
      <h3>{$name}</h3>
      <hr />
EOHTML;

      return $html;
    }
    return false;
  }


  public function welcome_user(){
    if($this->is_logged_in()){
      $logged_id = intval($_SESSION[$this->key]['id']);
      $user = new synUser(self::$db, $logged_id, $this->publicpath);
      $user->get_user_by_id($logged_id);
      $name = ($user->company) ? $user->company : $user->email;

      return $name;
    }
    return false;
  }


  function registerForm($typeid = null){
    $formId = '02';
    $session = isset($_SESSION['form'.$formId]) ? $_SESSION['form'.$formId] : false;
    $l = $this->labels;

    //$form = new formBuilder($formId, $l);
    $form = new formBuilder($formId, $l);
    $form->setAttributes(array(
      'privacy'        => 1,
      'class'          => 'synform',
      'implicit_label_class' => 'implicit',
      'action'         => $this->action,
      'include_script' => false,
      'required_mark'  => $this->marker,
      'db'             => self::$db,
      'informativa'    => nl2br($l['informativa_privacy']),
      'approvazione'   => $l['approvazione_disclaimer'],
      'resetLabel'     => $l['reg_reset'],
      'submitLabel'    => $l['reg_submit'],
      'error1'         => $l['error_required_field'],
      'error2'         => $l['error_invalid_email'],
      'error3'         => $l['verifica_valore'],
      'error4'         => $l['reinserire_stesso_valore'],
      'validateRules'  => array('password_confirm: {equalTo: "#f-user_password"}')
    ));

    $form->addFieldset(1);
    $form->addField('user[name]',       $l['nome'],             $this->getFieldValue('name'),     'text', true, 'text', null, 1 );
    $form->addField('user[surname]',    $l['cognome'],          $this->getFieldValue('surname'),  'text', true, 'text', null, 1 );
    $form->addField('user[company]',    $l['ragione_sociale'],  $this->getFieldValue('company'),  'text', true, 'text', null, 1 );
    $form->addField('user[address]',    $l['indirizzo'],        $this->getFieldValue('address'),  'text', false, 'text', null, 1 );
    $form->addField('user[city]',       $l['citta'],            $this->getFieldValue('city'),     'text', false, 'text', null, 1 );
    $form->addField('user[zip]',        $l['cap'],              $this->getFieldValue('zip'),      'text', false, 'text', null, 1 );
    $form->addField('user[province]',   $l['provincia'],        $this->getFieldValue('province'), 'text', false, 'text', null, 1 );
    $form->addField('user[phone]',      $l['telefono'],         $this->getFieldValue('phone'),    'text', true,  'text', null, 1 );

    $form->addFieldset(2);
    $form->addField('user[email]',      $l['email'],             $this->getFieldValue('email'),    'text', true,  'email', null, 2 );
    $form->addField('user[password]',   $l['password'],          $this->getFieldValue('password'), 'password', true, 'text', null, 2);
    $form->addField('password_confirm', $l['conferma_password'], null, 'password', true, 'text', null, 2);

    $form->addFieldset(3);
    //$subscribe_options = array(1=>array('label'=>'Voglio iscrivermi alla newsletter', 'selezionato'=>1));
    //$form->addField('user[newsletter]', 'Voglio iscrivermi alla newsletter', 1, 'checkbox', false, '', $subscribe_options);

    if($this->return_page)
      $form->addField('ret', NULL, $this->return_page, 'hidden');

    $form->addField('cmd',   NULL, 'create', 'hidden');
    $form->addField('type',  NULL, $typeid, 'hidden');

    // validation errors
    if ( isset($session['error'])
      //&& !empty($session['error'])
      ){
	  	$form->errorMsg($session['error']);
	  	unset($_SESSION['form'.$formId]);
    }

    // class errors
    if ( isset($this->error)
      && !empty($this->error)
      ){
      echo '<pre>', var_dump($this->error), '</pre>';
	  	$form->errorMsg($this->error);
	  	unset($this->error);
    }

    unset($_SESSION[$this->key]['userdata']);
    $html  = $form->render();

    $this->js = $form->validateScript();
    return $html;
  }



  public function editForm(){
    if($this->is_logged_in()){
      $user    = new synUser(self::$db);
      $user->get_user_by_id($_SESSION[$this->key]['id']);
      $formId  = '03';
      $session = isset($_SESSION['form'.$formId]) ? $_SESSION['form'.$formId] : false;
      $l       = $this->labels;


      //$form = new formBuilder($formId, $l);
      $form = new formBuilder($formId, $l);
      $form->setAttributes(array(
        'privacy'      => 0,
        'class'        => 'synform',
        'implicit_label_class' => 'implicit',
        'action'       => $this->action,
        'db'           => self::$db,
        'include_script' => false,
        'resetLabel'   => $l['annulla'],
        'submitLabel'  => $l['salva'],
        'error1'       => $l['error_required_field'],
        'error2'       => $l['error_invalid_email'],
        'error3'       => $l['verifica_valore'],
        'error4'       => $l['reinserire_stesso_valore'],
        'required_mark' => $this->marker
      ));


      $form->addFieldset(1);

      $form->addField('user[name]',        $l['nome'],            $user->name,         'text',  true,  'text',  null, 1);
      $form->addField('user[surname]',     $l['cognome'],         $user->surname,      'text',  true,  'text',  null, 1);
      $form->addField('user[company]',     $l['ragione_sociale'], $user->company,      'text',  false, 'text',  null, 1);
      $form->addField('user[address]',     $l['indirizzo'],       $user->address,      'text',  false, 'text',  null, 1);
      $form->addField('user[city]',        $l['citta'],           $user->city,         'text',  false, 'text',  null, 1);
      $form->addField('user[zip]',         $l['cap'],             $user->zip,          'text',  false, 'text',  null, 1);
      $form->addField('user[province]',    $l['provincia'],       $user->province,     'text',  false, 'text',  null, 1);
      $form->addField('user[phone]',       $l['telefono'],        $user->phone,        'text',  true,  'text',  null, 1);


      $form->addFieldset(2);

      $form->addField('cmd', NULL, 'update', 'hidden');
      $form->addField('user_id', NULL, $user->id, 'hidden');
      $form->addField('user_token', NULL, $user->hashed_id, 'hidden');

      if($this->return_page)
        $form->addField('ret', NULL, $this->return_page, 'hidden');

      if(isset($session['error'])){
        echo '<pre>', print_r($session['error'], 1), '</pre>';
        $form->errorMsg($session['error']);
        unset($_SESSION['form'.$formId]);
      }
      //return $form->render();
      $this->js = $form->validateScript();

      $html  = $form->render();
      return $html;

    } else {
      //header('location: '.$this->page);
    }
  }

  function forgotPasswordForm(){
    $formId  = '04';
    $session = isset($_SESSION['form'.$formId]) ? $_SESSION['form'.$formId] : false;
    $l       = $this->labels;

    //$form = new formBuilder($formId, $l);
    $form = new formBuilder($formId, $l);
    $form->setAttributes(array(
      'reset_button'   => false,
      'class'          => 'synform',
      'include_script' => false,
      'action'         => $this->action,
      'submitLabel'    => 'invia',
      'required_mark'  => $this->marker,
      'error1'         => $l['error_required_field'],
      'error2'         => $l['error_invalid_email'],
      'error3'         => $l['verifica_valore'],
      'error4'         => $l['reinserire_stesso_valore']
    ));
    $form->addField('email', $l['email'], NULL, 'text', true, 'email');
    $form->addField('cmd', NULL, 'send_new_password', 'hidden');
    $form->addField('ret', NULL, $this->pageurl, 'hidden');

    if(isset($session['error'])){
	  	$form->errorMsg($session['error']);
      unset($_SESSION['form'.$formId]);
    }

    $html  = "<p>{$l['email_recupero_password']}</p>";
    $html .= $form->render();

    $this->js = $form->validateScript();

    return $html;
  }


  function changePasswordForm(){
    if($this->is_logged_in()){
      $user    = new synUser(self::$db);
      $user->get_user_by_id($_SESSION[$this->key]['id']);

      $formId  = '05';
      $session = isset($_SESSION['form'.$formId]) ? $_SESSION['form'.$formId] : false;
      $l       = $this->labels;

      //$form = new formBuilder($formId, $l);
      $form = new formBuilder($formId, $l);
      $form->setAttributes(array(
        'reset_button'  => false,
        'class'         => 'synform',
        'include_script' => false,
        'action'        => $this->action,
        'required_mark' => $this->marker,
        'submitLabel'   => 'invia',
        'error1'         => $l['error_required_field'],
        'error2'         => $l['error_invalid_email'],
        'error3'         => $l['verifica_valore'],
        'error4'         => $l['reinserire_stesso_valore'],
        'validateRules'  => array('password_confirm: {equalTo: "#f-user_password"}')
      ));

      $form->addField('user[password]',     $l['password'],          null,  'password', true, 'text', null, null);
      $form->addField('password_confirm',   $l['conferma_password'], NULL,  'password', true, 'text');
      $form->addField('old_password',       $l['vecchia_password'],  NULL,  'password', true, 'text');

      $form->addField('cmd', NULL, 'change_password', 'hidden');
      $form->addField('user_id', NULL, $user->id, 'hidden');
      $form->addField('user_token', NULL, $user->hashed_id, 'hidden');

      if(isset($session['error'])){
        $form->errorMsg($session['error']);
        unset($_SESSION['form'.$formId]);
      }

      $html = $form->render();
      $this->js = $form->validateScript();

      return $html;
    }
  }

  function resetPasswordForm(){
    //if($this->is_logged_in()){
      $hash    = filter_input(INPUT_GET, 'user', FILTER_SANITIZE_STRING);
      $key     = filter_input(INPUT_GET, 'key', FILTER_SANITIZE_STRING);
      $user    = new synUser(self::$db);
      $user->get_user_by_hash($hash);

      $formId  = '06';
      $session = isset($_SESSION['form'.$formId]) ? $_SESSION['form'.$formId] : false;
      $l       = $this->labels;

      //$form = new formBuilder($formId, $l);
      $form = new formBuilder($formId, $l);
      $form->setAttributes(array(
        'reset_button'  => false,
        'class'         => 'synform',
        'include_script' => false,
        'action'        => $this->action,
        'submitLabel'   => 'invia',
        'error1'        => $l['error_required_field'],
        'error2'        => $l['error_invalid_email'],
        'error3'        => $l['verifica_valore'],
        'error4'        => $l['reinserire_stesso_valore'],
        'required_mark' => $this->marker
      ));

      $form->addField('user[password]',     $l['password'],          NULL,  'password', true, 'text');
      $form->addField('password_confirm',   $l['conferma_password'], NULL,  'password', true, 'text');

      $form->addField('cmd', NULL, 'reset_password', 'hidden');
      $form->addField('user_id', NULL, $user->id, 'hidden');
      $form->addField('post_user_token', NULL, $hash, 'hidden');
      $form->addField('user_key', NULL, $key, 'hidden');

      if(isset($session['error'])){
        $form->errorMsg($session['error']);
        unset($_SESSION['form'.$formId]);
      }

      $html = $form->render();
      $this->js = $form->validateScript();

      return $html;
    //}
  }


  function reActivateForm(){
    $formId  = '07';
    $session = isset($_SESSION['form'.$formId]) ? $_SESSION['form'.$formId] : false;
    $l       = $this->labels;

    //$form = new formBuilder($formId, $l);
    $form = new formBuilder($formId, $l);
    $form->setAttributes(array(
      'reset_button'  => false,
      'class'         => 'synform',
      'include_script' => false,
      'action'        => $this->action,
      'submitLabel'   => 'invia',
      'error1'         => $l['error_required_field'],
      'error2'         => $l['error_invalid_email'],
      'error3'         => $l['verifica_valore'],
      'error4'         => $l['reinserire_stesso_valore'],
      'required_mark' => $this->marker
    ));
    $form->addField('email', $l['email'], NULL, 'text', true, 'email');
    $form->addField('cmd', NULL, 'send_again', 'hidden');

    if(isset($session['error'])){
	  	$form->errorMsg($session['error']);
      unset($_SESSION['form'.$formId]);
    }

//    $html  = "<p>{$l['email_attivazione']}</p>";
    $html = $form->render();
    $this->js = $form->validateScript();

    return $html;
  }


  function changeEmailForm(){
    if($this->is_logged_in()){
      $user    = new synUser(self::$db);
      $user->get_user_by_id($_SESSION[$this->key]['id']);

      $formId  = '08';
      $session = isset($_SESSION['form'.$formId]) ? $_SESSION['form'.$formId] : false;
      $l       = $this->labels;

      //$form = new formBuilder($formId, $l);
      $form = new formBuilder($formId, $l);
      $form->setAttributes(array(
        'reset_button'  => false,
        'class'         => 'synform', // mono-form',
        'include_script' => false,
        'action'        => $this->action,
        'required_mark' => $this->marker,
        'error1'         => $l['error_required_field'],
        'error2'         => $l['error_invalid_email'],
        'error3'         => $l['verifica_valore'],
        'error4'         => $l['reinserire_stesso_valore'],
        'submitLabel'   => 'invia'
      ));

      $form->addField('user[new_email]', $l['email'],    NULL,  'text', true, 'email');
      $form->addField('user[password]',  $l['password'], NULL,  'password', true, 'text');

      $form->addField('cmd', NULL, 'set_new_email', 'hidden');
      $form->addField('user_id', NULL, $user->id, 'hidden');
      $form->addField('user_token', NULL, $user->hashed_id, 'hidden');
      $form->addField('ret', NULL, $this->pageurl, 'hidden');

      if(isset($session['error'])){
        $form->errorMsg($session['error']);
        unset($_SESSION['form'.$formId]);
      }

      $html = $form->render();
      $this->js = $form->validateScript();

      return $html;
    }
  }



  function create(){
    $error = false;
    $return = $this->return_page;

    if (count($_POST['user'])>0) {
      $post = $_POST['user'];

      $pass = $post['password'];
      $pass_confirm = $_POST['password_confirm'];
      if ($pass != $pass_confirm){
        // le password non coincidono
        $this->set_flash_message($this->labels['error_password_match'], 'danger');
        $error = true;
      }

      $user = new synUser(self::$db);
      if (!$user->is_email_available($post['email'])) {
        // email già utilizzata
        $this->set_flash_message($this->labels['error_email_used'], 'danger');
        $error = true;
      }

      if (!$error){
        try {
          $activation_code = $this->hash(rand().microtime());
          $activation_url = $this->siteurl.$this->pageurl.'?cmd=activate&token='.$activation_code;

          $post['password'] = $this->hash($pass);
          $post['confirmation_code'] = $activation_code;
          $post['created'] = date('Y-m-d H:i:s');
          //echo '<pre>', print_r($post), '</pre>';
          //die('provo a salvare');

          // campi composti- fuori standard!!!
          if (isset($_POST['date'])) {
            $post['purchase_date'] = $_POST['date']['Date_Year'].'-'.$_POST['date']['Date_Month'].'-'.$_POST['date']['Date_Day'];
          }
          if (isset($_POST['ic'])) {
            $post['installation_company'] = $_POST['ic']['name'].PHP_EOL.$_POST['ic']['address'].PHP_EOL.$_POST['ic']['fiscal_code'];
          }

          $user_id = $user->create_user($post, false, DEFAULT_GROUP);
          $recipient = array(
            'name' => $post['name'],
            'mail' => $post['email']);
          $data = array(
            'site_name' => $this->sitename,
            'activation_url' => $activation_url
            );
          $this->notify(
            $recipient,
            $this->labels['email_activate_subject'],
            "email/activate_{$this->lang}.html",
            $data
          );

          $confirm_message = $this->labels['message_registration_completed_1'];
          if ($post['require_extension']=='1')
            $confirm_message .= PHP_EOL.$this->labels['message_extension_queued'];

          $this->set_flash_message($this->labels['message_registration_completed_1'], 'success');
          $return = $this->return_base_path().'?cmd=';

        } catch (Exception $e) {
          // errore insert?
          $this->set_flash_message($e->getMessage(), 'danger');
          $return = $this->return_base_path().'?cmd=register';
        }

      } else {
        // errore
        $_SESSION[$this->key]['userdata'] = $post;
        $return = $this->return_base_path().'?cmd=register';
      }
    }

    if ($this->debug) {
      echo '<pre>', $this->get_flash_message(false), '</pre>';
      echo "<a href=\"{$return}\">continua</a><br>";
      die();

    } else {
      header('location: '.$return);
    }
  }


  function send_again(){
    $error = false;

    if (count($_POST['email'])>0) {
      $post = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
      $user = new synUser(self::$db);
      $user->get_user_by_email($post);

      if ($user==null) {
        $this->set_flash_message($this->labels['error_unknown_user'], 'warning');
      } else {
        if (empty($user->activated)) {
          if (empty($user->confirmation_code)) {
            $activation_code = $this->hash(rand().microtime());
            $user = $user->update_user(array('confirmation_code' => $activation_code), $user->id);
              echo 'updated c.c.<br>';
          } else {
            $activation_code = $user->confirmation_code;
          }

          $activation_url = $this->siteurl.$this->return_base_path().'?cmd=activate&token='.$activation_code;
          $recipient = array(
            'name' => $user->name,
            'mail' => $user->email
            );
          $data = array(
            'site_name' => $this->sitename,
            'activation_url' => $activation_url
            );

          $this->notify(
            $recipient,
            $this->labels['email_activate_subject'],
            "email/activate_{$this->lang}.html",
            $data
            );

          $this->set_flash_message(sprintf($this->labels['message_activation_email_sent'], $post), 'success');
        } else {
          $this->set_flash_message($this->labels['error_active_user'], 'danger');
        }
      }
    } //else die('s.b.');

    if ($this->debug) {
      echo $this->return_base_path().'--<br>';
      echo '<pre>', $this->get_flash_message(false), '</pre>';
      echo "<a href=\"{$this->return_base_path()}?cmd=\">continua</a><br>";
      die();

    } else {
      header('location: '.$this->return_base_path().'?cmd=');
    }
  }


  function activate($multiple_recipients = ''){
    if (isset($_GET['token'])){
      $token = filter_input(INPUT_GET, 'token', FILTER_SANITIZE_STRING);
      $user = new synUser(self::$db);

      // Activate user
      if ($user_id = $user->activate_user($token)) {
        $user->update_user(array('hashed_id' => $this->hash($user_id)), $user_id);
        $user->get_user_by_id($user_id);
        //$tipo = getTipoUtente($user->type, $this->lang);
        $data = array(
          'user' => ($user->company ? $user->company : $user->name.' '.$user->surname),
          'email' => $user->email,
          'site_name' => $this->sitename,
          'admin_url' => $this->siteurl.'/admin/',
          'tipo' => ($tipo['tipo'] ? $tipo['tipo'] : 'tipo non definito')
          );

        $this->notify(
          $this->sender,
          'Nuovo utente registrato',
          'email/admin_notify_activation.html',
          $data,
          $multiple_recipients
          );
        $this->set_flash_message($this->labels['message_activation_ok'], 'success');

      } else { // fail
        $this->set_flash_message($this->labels['message_activation_failed'], 'danger');
      }
    } else {
      $this->set_flash_message($this->labels['error_invalid_token'], 'danger');
    }

    if ($this->debug) {
      echo '<pre>', $this->get_flash_message(false), '</pre>';
      echo "<a href=\"{$this->return_base_path()}?cmd=\">continua</a><br>";
      die();

    } else {
      header('location: '.$this->return_base_path().'?cmd=');
    }
  }


  function update(){
    if ( $this->is_logged_in()
      && count($_POST['user'])>0
      ){

      $user = new synUser(self::$db);
      $user->get_user_by_id($_SESSION[$this->key]['id']);

      $post = $_POST['user'];
      $post_user_id    = filter_input(INPUT_POST, 'user_id', FILTER_SANITIZE_NUMBER_INT);
      $post_user_token = filter_input(INPUT_POST, 'user_token', FILTER_SANITIZE_STRING);

      // campi composti- fuori standard!!!
      if (isset($_POST['date'])) {
        $post['purchase_date'] = $_POST['date']['Date_Year'].'-'.$_POST['date']['Date_Month'].'-'.$_POST['date']['Date_Day'];
      }
      if (isset($_POST['ic'])) {
        $post['installation_company'] = $_POST['ic']['name'].PHP_EOL.$_POST['ic']['address'].PHP_EOL.$_POST['ic']['fiscal_code'];
      }
      //echo '<pre>', print_r($post), '</pre>'; //die();

      if(isset($_POST['ret'])){
        $this->return_page = filter_input(INPUT_POST, 'ret', FILTER_SANITIZE_URL);
      }

      if($user->hashed_id != $post_user_token){
        $this->set_flash_message($this->labels['error_invalid_token'], 'danger');
        //die($this->labels['error_invalid_token']);
      }


      try {
        $user->update_user($post, $user->id);
        $this->set_flash_message($this->labels['message_update_ok'], 'success');
        header('location: '.$this->return_page);

      } catch (Exception $e) {
        $this->set_flash_message($e->getMessage(), 'danger');
        header('location: '.$this->return_base_path().'?cmd=edit');
      }

    }
  }


  function change_password(){
    if ( $this->is_logged_in()
      && count($_POST['user'])>0
      ){

      $user = new synUser(self::$db);
      $user->get_user_by_id($_SESSION[$this->key]['id']);

      $post                  = filter_var_array($_POST['user'], FILTER_SANITIZE_STRING);
      $post_old_password     = filter_input(INPUT_POST, 'old_password', FILTER_SANITIZE_STRING);
      $post_password_confirm = filter_input(INPUT_POST, 'password_confirm', FILTER_SANITIZE_STRING);

      $post_user_id          = filter_input(INPUT_POST, 'user_id', FILTER_SANITIZE_NUMBER_INT);
      $post_user_token       = filter_input(INPUT_POST, 'user_token', FILTER_SANITIZE_STRING);

      if(isset($_POST['ret'])){
        $this->return_page = filter_input(INPUT_POST, 'ret', FILTER_SANITIZE_URL);
      } else {
        $this->return_page = $this->return_base_path().'?cmd=';
      }
      $return = $this->return_base;

      if($user->hashed_id != $post_user_token){
        die($this->labels['error_invalid_token']);
      }
      if ($user->password != $this->hash($post_old_password)) {
        $this->set_flash_message($this->labels['error_incorrect_old_password'], 'danger');
        $return = $this->return_base_path().'?cmd=change_password';

      } elseif ($post['password'] != $post_password_confirm){
        $this->set_flash_message($this->labels['error_password_match'], 'danger');
        $return = $this->return_base_path().'?cmd=change_password';

      } else {
        // tutto ok
        try {
          $new_password = $post['password']; //filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
          $hashed_password = $this->hash($new_password);
          $user->update_user(array('password'=>$hashed_password), $user->id);
          $recipient = array(
            'name' => $user->name,
            'mail' => $user->email,
            'password' => $post['password']
            );
          $data = array(
            'site_name' => $this->sitename,
            'password' => $new_password
            );
          // invio mail all'utente
          $this->notify($recipient,
                        $this->labels['email_change_password_subject'],
                        "email/change_password_{$this->lang}.html",
                        $data);

          $this->set_flash_message($this->labels['message_password_updated'], 'success');

        } catch (Exception $e) {
          $this->set_flash_message($e->getMessage(), 'danger');
          $return = $this->return_base_path().'?cmd=change_password';
        }
      }
    }

    if ($this->debug) {
      echo "{$new_password} - {$hashed_password}<br>";
      echo '<pre>', $this->get_flash_message(false), '</pre>';
      echo "<a href=\"{$return}\">continua</a><br>";
      die();

    } else {
      header('location: '.$return.'?cmd=');
    }
  }


  function send_new_password(){
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    if($email){
      $user = new synUser(self::$db);
      if($user->get_user_by_email($email)){
        $new_pass_key = $this->hash(rand().microtime());
        $user->set_password_key($user->id, $new_pass_key);
        $recipient = array(
          'name'=>$user->nome,
          'mail'=>$user->email
          );
        $data = array(
          'site_name' => $this->sitename,
          'site_url' => "{$this->siteurl}{$this->pageurl}",
          'uid' => $user->hashed_id,
          'new_pass_key' => $new_pass_key
          );
        // invio mail all'utente
        $this->notify($recipient,
                      $this->labels['email_reset_password_subject'],
                      "email/reset_password_{$this->lang}.html",
                      $data);

        $this->set_flash_message(sprintf($this->labels['message_new_password_sent'], $email), 'success');
        //echo '1';

      } else {
        $this->set_flash_message($this->labels['error_unknown_email'], 'danger');
        //echo '2';
      }
    } else {
      $this->set_flash_message($this->labels['error_invalid_email'], 'danger');
      //echo '3';
    }
    //die("<a href='{$this->pageurl}'>next</a>");
		return NULL;
  }


  function reset_password(){
    if(count($_POST['user'])>0){

      $post                  = filter_var_array($_POST['user'], FILTER_SANITIZE_STRING);
      $post_password_confirm = filter_input(INPUT_POST, 'password_confirm', FILTER_SANITIZE_STRING);
      $post_user_id          = filter_input(INPUT_POST, 'user_id', FILTER_SANITIZE_NUMBER_INT);
      $post_user_token       = filter_input(INPUT_POST, 'user_token', FILTER_SANITIZE_STRING);
      $post_user_key         = filter_input(INPUT_POST, 'user_key', FILTER_SANITIZE_STRING);

      $user = new synUser(self::$db);
      $user->get_user_by_id($post_user_id);

      if(isset($_POST['ret'])){
        $this->return_page = filter_input(INPUT_POST, 'ret', FILTER_SANITIZE_URL);
      }

      if($user->hashed_id != $post_user_token){
        die($this->labels['error_invalid_token']);
      }


      if ($post['password'] != $post_password_confirm){
        $this->set_flash_message($this->labels['error_password_match'], 'danger');

      } else {
        // tutto ok
        try {
          $new_password = $this->hash($post['password']);
          if($user->reset_password($user->id, $new_password, $post_user_key, 172800)){
            $recipient = array('name'=>$user->nome, 'mail'=>$user->email);
            $data = array(
              'site_name' => $this->sitename,
              'password' => $post['password']
              );
            // invio mail all'utente
            $this->notify($recipient,
                          $this->labels['email_change_password_subject'],
                          "email/change_password_{$this->lang}.html",
                          $data);

            $this->set_flash_message($this->labels['message_password_updated'], 'success');

          } else {
            $this->set_flash_message($this->labels['error_password_update'], 'danger');
          }
        } catch (Exception $e) {
          $this->set_flash_message($e->getMessage(), 'danger');
        }
      }
    }

    if ($this->debug) {
      echo '<pre>', $this->get_flash_message(false), '</pre>';
      echo "<a href=\"{$this->return_base_path()}?cmd=\">continua</a><br>";
      die();

    } else {
      header('location: '.$this->return_base_path().'?cmd=');
    }
  }

	function set_new_email(){
    if ( $this->is_logged_in()
      && count($_POST['user'])>0
      ){

      $user = new synUser(self::$db);
      $user->get_user_by_id($_SESSION[$this->key]['id']);

      $post_new_email  = filter_var($_POST['user']['new_email'], FILTER_SANITIZE_STRING);
      $post_password   = filter_var($_POST['user']['password'], FILTER_SANITIZE_STRING);
      $post_user_id    = filter_input(INPUT_POST, 'user_id', FILTER_SANITIZE_NUMBER_INT);
      $post_user_token = filter_input(INPUT_POST, 'user_token', FILTER_SANITIZE_STRING);

      if(isset($_POST['ret'])){
        $this->return_page = filter_input(INPUT_POST, 'ret', FILTER_SANITIZE_URL);
      }

      if($user->hashed_id != $post_user_token){
        die($this->labels['error_invalid_token']);
      }

      if($user->password != $this->hash($post_password)){
        $this->set_flash_message($this->labels['error_incorrect_password'], 'danger');

      } elseif(!$user->is_email_available($post_new_email)){
        $this->set_flash_message($this->labels['error_email_used'], 'warning');

      } else {
        // tutto ok
        try {
          $new_email_key = $this->hash(rand().microtime());
          $user->set_new_email($user->id, $post_new_email, $new_email_key, TRUE);

          $recipient = array('name'=>$user->nome, 'mail'=>$post_new_email);
          $data = array(
            'site_name' => $this->sitename,
            //'site_url' => "{$this->siteurl}{$this->pageurl}",
            'site_url' => "{$this->siteurl}{$this->action}",
            'new_email' => $post_new_email,
            'new_email_key' => $new_email_key
            );

          // invio mail all'utente
          $this->notify($recipient,
                        $this->labels['email_change_email_subject'],
                        "email/change_email_{$this->lang}.html",
                        $data);

          $this->set_flash_message($this->labels['message_email_updated'], 'success');

        } catch (Exception $e) {
          $this->set_flash_message($e->getMessage(), 'danger');
        }
      }
    }
    return NULL;
	}


  function activate_new_email(){
    if ( $this->is_logged_in()){
      $user = new synUser(self::$db);
      $user->get_user_by_id($_SESSION[$this->key]['id']);
      $new_email_key = filter_input(INPUT_GET, 'key', FILTER_SANITIZE_STRING);

      if($user->activate_new_email($user->id, $new_email_key)){
        //return $this->show_message('message_new_email_activated');
        $this->set_flash_message($this->labels['message_new_email_activated'], 'success');
      } else {
        //return $this->show_message('error_new_email_failed');
        $this->set_flash_message($this->labels['error_new_email_failed'], 'danger');
      }
    }
    //return false;
    header('location: '.$this->return_base_path().'?cmd=');
    die();
  }


  function show_message($message){
    $html = false;
    if(!empty($this->labels[$message])){
      $html = $this->labels[$message];
    } else {
      $html = 'Missing translation: ['.$message.']';
    }
    return $html;
  }


  function set_flash_message($message, $type='warning'){
    if(!isset($_SESSION))
      session_start();

    $_SESSION[$this->key]['message']['text'] = $message;
    $_SESSION[$this->key]['message']['type'] = $type;
  }


  function get_flash_message($clean = TRUE) {
    if ( isset($_SESSION[$this->key]['message']['text'])
      && !empty($_SESSION[$this->key]['message']['text'])
      ){
      $message = $_SESSION[$this->key]['message']['text'];
      if ($clean)
        unset($_SESSION[$this->key]['message']);

      return "<div class=\"alert\">{$message}</div>";
    }
    return NULL;
  }


  /*
   * Invia una mail
   */
  function notify($to='', $subject='', $template='', $data=array(), $multiple_recipients=''){
    try {
      $mail = new synMailer($this->sender, $to, $subject, $multiple_recipients);
      $mail->setTemplate($template, $data);
      $mail->go();

    } catch(Exception $e) {
      $this->set_flash_message($e->getMessage(), 'danger');
    }
  }


  function hash($str){
    return md5($str.self::$salt);
  }


  function validate_script(){
    return $this->js;
  }

  /*
   * rimuove i parametri dalla return page, tipo '?cmd=ciao'
   */
  function return_base_path($url=null){
    $url = $this->return_page;
    $pos = strrpos ($url, '?');
    if ($pos !== false)
      $url = substr($url, 0, $pos);

    return $url;
  }

  function getUserData(){
    $result = false;
    $user = new synUser(self::$db);
    $user->get_user_by_id($_SESSION[$this->key]['id']);
    if (is_object($user)) {
      foreach ($user as $k => $v) {
        $result[$k] = $v;
      }
    }
    return $result;
  }


  function getUserDataByHash(){
    $result = false;
    $hash   = filter_input(INPUT_GET, 'user', FILTER_SANITIZE_STRING);
    $key    = filter_input(INPUT_GET, 'key', FILTER_SANITIZE_STRING);
    $user   = new synUser(self::$db);
    $user->get_user_by_hash($hash);
    if (is_object($user)) {
      foreach ($user as $k => $v) {
        $result[$k] = $v;
      }
      $result['key'] = $key;
    }
    return $result;
  }


  function getFieldValue($field){
    $ret = null;
    if (isset($_SESSION[$this->key]['userdata'][$field]))
      $ret = trim($_SESSION[$this->key]['userdata'][$field]);

    return $ret;
  }


  function testmail(){
    $this->notify(array('name'=>'', 'mail'=>'assistenza@kleis.it'),
                  'Test da '.$this->sitename,
                  'email/change_password_it.html',
                  array('password'=>'bubu'));
  }

}


// EOF