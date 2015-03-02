<?php
//require_once("class.formBuilder.php");
//require_once("phpmailer/class.phpmailer.php");
//require_once("MCAPI.class.php");

class synUser {
/*
public    This means the property or method can be accessed by any part of a script both inside and outside the class definition. All methods are regarded as public unless preceded by a different modifier.
protected This prevents external access to a property or method, but permits access internally and to parent and child classes.
private   Private properties and methods can be accessed only within the class where they are defined.
*/


  // properties defined here
  static $db;
  //private $_mailpattern = '/^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,6}$/';
  private $table_name   = 'users';
  //private $query;

  // constructor
  public function __construct(ADODB_PDO $db){ //, $salt='', $site_name, $admin_mail, $labels=array()) {
    self::$db = $db;
  }

  /**
   * Get user record by Id
   *
   * @param int
   * @param bool
   * @return  object
   */
  function get_user_by_id($user_id) {
    $qry = "SELECT * FROM `{$this->table_name}` WHERE id='{$user_id}' AND activated = '1'";
      //$qry = "SELECT * FROM `{$this->table_name}` WHERE id='{$user_id}'";
      //echo $qry.'<br>';
    $res = self::$db->execute($qry);
    self::$db->setFetchMode(ADODB_FETCH_ASSOC);
    if(is_object($res)){
      $arr = $res->fetchrow();
      foreach($arr as $key => $value)
        $this->$key = $value;

      return TRUE;
    }

    return NULL;
  }


  /**
   * Get user record by hashed Id
   *
   * @param int
   * @param bool
   * @return  object
   */
  function get_user_by_hash($hashed_id) {
    $qry = "SELECT * FROM `{$this->table_name}` WHERE hashed_id='{$hashed_id}' AND activated = '1'";
    $res = self::$db->execute($qry);

    if(is_object($res) && $arr = $res->fetchrow()){
      foreach($arr as $key => $value)
        $this->$key = $value;

      return TRUE;
    }
      //return $res->fetchrow();
    return NULL;
  }


  /**
   * Get user record by email
   *
   * @param string
   * @return  object
   */
  function get_user_by_email($email) {
    $qry = "SELECT * FROM `{$this->table_name}` WHERE LOWER(email)='".strtolower($email)."'"; // AND activated = '1'";
    $res = self::$db->execute($qry);

    if(is_object($res) && $arr = $res->fetchrow()){
      foreach($arr as $key => $value)
        $this->$key = $value;

      return TRUE;
    }
      //return $res->fetchrow();
    return NULL;
  }


  /**
   * Check if email is available for registering
   *
   * @param string
   * @return  bool
   */
  function is_email_available($email) {
    $qry = "SELECT id FROM `{$this->table_name}` WHERE LOWER(email)='".strtolower($email)."' OR new_email='".strtolower($email)."'";
    $res = self::$db->execute($qry);

    return self::$db->affected_rows() == 0;
  }


  /**
   * Create new user record
   *
   * @param array
   * @param bool
   * @return  array
   */
  function create_user($user, $activated = TRUE, $group = 1) {

    $now = date("Y-m-d H:i:s");
    $user['timestamp'] = $now;
    $user['activated'] = $activated ? 1 : null;
    //$user['confirmation_code'] = md5($now);
    $user['group'] = $group; //$this->_getDefaultGroup();

    $user_keys = array_map('addslashes', array_keys($user));
    $user_vals = array_map('addslashes', array_values($user));
    $fields    = implode('`, `', $user_keys);
    $values    = implode("', '", $user_vals);

    $qry  = "INSERT INTO `{$this->table_name}` (`{$fields}`) VALUES ('{$values}')";
    if (self::$db->execute($qry)) {
      $insert_id = self::$db->insert_Id();
      //return array('user_id' => $insert_id);
      return $insert_id;

    } else {
      throw new Exception('User cannot be created');
    }
    return NULL;
  }


  function update_user($user, $user_id) {

    $user['last_update'] = date("Y-m-d H:i:s");
    $user_vals = array();

    foreach($user as $k => $v){
      if(is_array($v))
        $v = implode(' ', $v);
      $user_vals[] = "`{$k}`='{$v}'";
    }

    $values = implode(', ', $user_vals);
    $query  = "UPDATE `{$this->table_name}` SET {$values} WHERE id='{$user_id}' AND activated='1'";
    //echo $query; die();
    if (self::$db->execute($query)) {
      return true;
    } else {
      throw new Exception("User cannot be updated!");
    }
    return NULL;
  }

/*
  function update_user_password($new_password, $user_id) {

    $user['last_update'] = date("Y-m-d H:i:s");
    $query = "UPDATE `{$this->table_name}` SET `password`='{$new_password}' WHERE id={$user_id} AND activated='1'";

    if (self::$db->execute($query)) {
      return true;
    } else {
      //throw new Exception('Impossibile aggiornare questo utente');
      throw new Exception("Impossibile aggiornare questo utente ({$query})");
    }
    return NULL;
  }
*/

  /**
   * Activate user if activation key is valid.
   * Can be called for not activated users only.
   *
   * @param int
   * @param string
   * @param bool
   * @return  bool
   */
  function activate_user1($user_id, $activation_key, $activate_by_email) {

    $field = ($activate_by_email) ? 'new_email_key' : 'new_password_key';
    $qry = "SELECT 1 FROM `{$this->table_name}` WHERE `id` = '{$user_id}' AND `{$field}` = '{$activation_key}'";
    $res = self::$db->execute($qry);

		if ($res->Affected_Rows() == 1) {
      $upd = "UPDATE `{$this->table_name}` SET `activated` = '1', `{$field}` = NULL WHERE `id` = '{$user_id}'";
      $res = self::$db->execute($upd);
			//$this->create_profile($user_id);
			return TRUE;
		}
		return FALSE;
  }

  function activate_user($activation_key) {
    $qry = "SELECT id FROM `{$this->table_name}` WHERE `confirmation_code` = '{$activation_key}' LIMIT 0,1";
    $res = self::$db->execute($qry);

		if ($arr = $res->fetchRow()) {
      $user_id = $arr['id'];
      $upd = "UPDATE `{$this->table_name}` SET `activated` = '1', `confirmation_code` = NULL WHERE `id` = '{$user_id}'";
      $res = self::$db->execute($upd);

			return $user_id;
		}
		return FALSE;
  }


  /**
   * Purge table of non-activated users
   *
   * @param int
   * @return  void
   */
  function purge_unactive($expire_period = 172800) {
    $qry = "DELETE FROM `{$this->table_name}` WHERE `activated` = '0' AND UNIX_TIMESTAMP(created) < '".time()-$expire_period."'";
    $res = self::$db->execute($qry);
  }

  /**
   * Delete user record
   *
   * @param int
   * @return  bool
   */
  function delete_user($user_id) {
    $qry = "DELETE FROM `{$this->table_name}` WHERE `id` = '{$user_id}'";
    $res = self::$db->execute($qry);
		if (self::$db->Affected_Rows() > 0) {
			//$this->delete_profile($user_id);
			return TRUE;
		}
		return FALSE;
  }

  /**
   * Set new password key for user.
   * This key can be used for authentication when resetting user's password.
   *
   * @param int
   * @param string
   * @return  bool
   */
  function set_password_key($user_id, $new_pass_key) {
    $upd = "UPDATE `{$this->table_name}` SET `new_password_key` = '{$new_pass_key}', `new_password_requested` = NOW() WHERE `id` = '{$user_id}'";
    $res = self::$db->execute($upd);

		return self::$db->Affected_Rows() > 0;
  }

  /**
   * Check if given password key is valid and user is authenticated.
   *
   * @param int
   * @param string
   * @param int
   * @return  void
   */
  function can_reset_password($user_id, $new_pass_key, $expire_period = 900) {
    $qry = "SELECT 1 FROM `{$this->table_name}` WHERE `id` = '{$user_id}' AND `new_password_key` = '{$new_pass_key}' AND UNIX_TIMESTAMP(new_password_requested) > '".time() - $expire_period."'";
    $res = self::$db->execute($qry);

		return self::$db->Affected_Rows() == 1;
  }

  /**
   * Change user password if password key is valid and user is authenticated.
   *
   * @param int
   * @param string
   * @param string
   * @param int
   * @return  bool
   */
  function reset_password($user_id, $new_pass, $new_pass_key, $expire_period = 900) {
    $time = time() - $expire_period;
    $upd = <<<EOUPD
    UPDATE `{$this->table_name}`
       SET `password` = '{$new_pass}', `new_password_key` = NULL, `new_password_requested` = NULL
     WHERE `id` = '{$user_id}'
       AND `new_password_key` = '{$new_pass_key}'
       AND UNIX_TIMESTAMP(new_password_requested) > '{$time}'
EOUPD;

    $res = self::$db->execute($upd);
		return self::$db->Affected_Rows() > 0;
  }

  /**
   * Change user password
   *
   * @param int
   * @param string
   * @return  bool
   */
  function change_password($new_pass, $user_id) {
    $upd = "UPDATE `{$this->table_name}` SET `password` = '{$new_pass}' WHERE `id` = '{$user_id}'";
    $res = self::$db->execute($upd);

		return self::$db->Affected_Rows() > 0;
  }

  /**
   * Set new email for user (may be activated or not).
   * The new email cannot be used for login or notification before it is activated.
   *
   * @param int
   * @param string
   * @param string
   * @param bool
   * @return  bool
   */
  function set_new_email($user_id, $new_email, $new_email_key, $activated) {
    $field = $activated ? 'new_email' : 'email';
    $upd = <<<EOUPD
    UPDATE `{$this->table_name}`
       SET `{$field}` = '{$new_email}', `new_email_key` = '{$new_email_key}'
     WHERE `id` = '{$user_id}'
       AND `activated` = '{$activated}'
EOUPD;

    $res = self::$db->execute($upd);
		return self::$db->Affected_Rows() > 0;
  }

  /**
   * Activate new email (replace old email with new one) if activation key is valid.
   *
   * @param int
   * @param string
   * @return  bool
   */
  function activate_new_email($user_id, $new_email_key) {
    $upd = <<<EOUPD
    UPDATE `{$this->table_name}`
       SET `email` = `new_email`, `new_email` = NULL, `new_email_key` = NULL
     WHERE `id` = '{$user_id}'
       AND `new_email_key` = '{$new_email_key}'
EOUPD;

    $res = self::$db->execute($upd);
		return self::$db->Affected_Rows() > 0;
  }

  /**
   * Update user login info, such as IP-address or login time, and
   * clear previously generated (but not activated) passwords.
   *
   * @param int
   * @param bool
   * @param bool
   * @return  void
   */
  function update_login_info($user_id, $record_ip, $record_time, $increase_login) {
    $fields = '';
		if ($record_ip)
      $fields .= ", `last_ip` = '{$_SERVER['REMOTE_ADDR']}'";

    if ($record_time)
      $fields .= ", `last_access` = NOW()";

    if ($increase_login)
      $fields .= ", `login_count` = `login_count`+1";

    $upd = "UPDATE `{$this->table_name}` SET `new_password_key` = NULL, `new_password_requested` = NULL, `login_attempts` = 0{$fields} WHERE `id` = '{$user_id}'";
//echo $upd; die();
    $res = self::$db->execute($upd);
		return self::$db->Affected_Rows() > 0;
  }


  /*
   * Increases number of login attempts
   */
  function increase_login_attempts($user_id){
    $qry = "UPDATE `{$this->table_name}` SET `login_attempts` = `login_attempts`+1, `last_ip` = '{$_SERVER['REMOTE_ADDR']}' WHERE `id` = '{$user_id}'";
    $res = self::$db->execute($qry);
  }


  /*
   * resets login attempts
   */
  function reset_login_attempts($user_id){
    $qry = "UPDATE `{$this->table_name}` SET `login_attempts` = 0 WHERE `id` = '{$user_id}'";
    $res = self::$db->execute($qry);
  }


  /**
   * Ban user
   *
   * @param int
   * @param string
   * @return  void
   */
  function ban_user($user_id, $reason = NULL) {
  }

  /**
   * Unban user
   *
   * @param int
   * @return  void
   */
  function unban_user($user_id) {
  }

  /**
   * Create an empty profile for a new user
   *
   * @param int
   * @return  bool
   */
  private function create_profile($user_id) {
  }

  /**
   * Delete user profile
   */
  private function delete_profile($user_id) {
  }

}

// end synUser
