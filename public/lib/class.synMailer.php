<?php
/*
 * ================================
 * classe per inviare email in HTML
 * ================================
 * - fa uso di template (devono essere messi in /public/templates)
 * - dipende da phpmailer
 * - preferibilmente usare metodo SMTP
 */

class synMailer extends PHPMailer {

  protected $template;
  protected $template_path;

  public function __construct($from='', $dest='', $subject='', $multiple_recipients=''){
    global $synAdministrator, $synWebsiteTitle, $smtp_conf;

    $this->From     = isset($smtp_conf['auth']) ? $smtp_conf['auth'] : $synAdministrator;
    $this->FromName = $synWebsiteTitle;

    // aggiungo mittente come reply-to
    if ( !empty($from) ) { 
      $this->AddReplyTo( $from['mail'], $from['name'] ); 
    }

    // dati destinatario
    if (is_array($multiple_recipients) && !empty($multiple_recipients)) {
      foreach ($multiple_recipients as $address) {
        $ok = $this->AddAddress( trim($address) );
      }
    } else {
      $ok = $this->AddAddress($dest['mail'],
                              $dest['name']);
    }

    if (!$ok)
      throw new Exception('Critical error: cannot set recipient.');
    //echo '<pre>', print_r($this), '</pre>'; die();

    // eventuali CC vanno settati con questo metodo
    //$this->AddCC("test@test.com","Test Name");

    // eventuali BCC vanno settati con questo metodo
    //$this->AddBCC("test@test.com","Test Name");

    // subject della mail
    $this->Subject  = $subject;

    // setta il charset a UTF-8 (non è il default!)
    $this->CharSet  = 'UTF-8';

    // setta il ContentType della mail
    $this->IsHTML(true);

    
    if ( isset($smtp_conf) && !empty($smtp_conf) ) {
      // CONFIGURAZIONE SMTP
      $this->IsSMTP();
      $this->Host       = $smtp_conf['host'];
      $this->Port       = $smtp_conf['port'];
      $this->SMTPAuth   = $smtp_conf['auth'];
      $this->SMTPSecure = $smtp_conf['secure'];    
      $this->Username   = $smtp_conf['user'];
      $this->Password   = $smtp_conf['pass'];
      $this->SMTPdebug  = false;

    } else {
      $this->isMail(); // metodo mail sembra non accettare 'to' multipli
      //$this->IsSendmail();
      //$this->IsQmail();
    }

    // path dei template
    $this->template_path = $_SERVER['DOCUMENT_ROOT'] . '/public/templates/';
  }


  /*
   * recupera il template, valorizza le variabili e lo setta come Body
   */
  public function setTemplate($template, $data){
    try {
      // controlla i dati
      $this->isArray($data);

      $template = file_get_contents($this->template_path.$template);
      //$template = preg_replace('/\\\\/','', $body); //Strip backslashes
      foreach ($data as $key=>$value){
        $template = str_replace("%%{$key}%%", $value, $template);
      }
      $this->Body = $template;
      $this->template = $template;
      $this->AltBody = strip_tags($template);

      if(!$template){
        echo $template . ' not found!!!<hr>'; die();
      }

    } catch(Exception $e) {
      throw $e;
    }
  }


  /*
   * controlla i dati e, se tutto ok, spedisce
   */
  public function go(){
    if (empty($this->From))
      throw new Exception('Critical error: Missing email sender.');
    if ($this->Subject == '')
      throw new Exception('Critical error: Missing email subject.');
    if (empty($this->Body))
      throw new Exception('Critical error: Missing email body.');

    try {
      $this->Send();
    } catch(Exception $e) {
      echo 'mail non spedita<hr>';
      throw $e;
    }
    return true;
  }

  /*
   * ritorna l'array To
   * /
  function getTo(){
    return $this->all_recipients;
  }*/

  /*
   * metodo alternativo - il template deve essere uno script php
   */
  private function _getEmailTemplate($filename, $variables, $lang) {
    extract($variables); // array delle variabili
    $template = $this->template_path . $lang . '_' . $filename . '.php';
    if (is_file($template)) {
      ob_start();
        include $template;
      return ob_get_clean();
    }
    return false;
  }


  /*
   * controlla che i dati forniti siano un array
   */
  function isArray($arrayCandidate){
    if(!is_array($arrayCandidate)){
      throw new Exception('Information provided for email template is not in the valid format.');
    }
  }
}

//EOF class.synMailer.php
