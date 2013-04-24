<?php
/**
  * FORM BUILDER CLASS
  *
  * Crea l'html del form utilizzando i parametri passati dalla funzione
  * function.form.php.
  *
  * @author Marco Pozzato  <marco@kleis.it>
  * @param int $id ID del form (record sul db)
  * @param array $attributes Attributi del form
  * @return str $form
  * @version 1.1
  */

class formBuilder {

  private $action;
  private $approvazione;
  private $captcha = 'nessuno';
  private $captchaConfig = array();
  private $captchaLabel = 'Codice di sicurezza';
  private $cblabelclass = 'cblabel';
  private $checkfields;
  private $class;
  private $debug = false;
  private $error;
  private $fields = array();
  private $fieldset = array();  
  private $haschecks = false;
  private $hiddenfields = array();
  private $hook = array();
  private $id;
  private $informativa;
  private $lastFs;  
  private $maxFileSize = 3000000;
  private $method;
  private $multipart = 0;
  private $privacy = false;
  private $privacyLabel = 'Privacy';
  private $required_mark = '*';
  private $resetLabel = 'Reset';
  private $submitLabel = 'Submit';
  private $tab = 1;
  private $validateRules = array();
  private $error1;
  private $error2;
  private $error3;
  private $xhtml = true;
    

  public function __construct($id) {
    $this->setAttributes(array(
      'id'=>$id,
      'method'=>'post',
      'captchaConfig'=>array(
        'width'=>300, 
        'height'=>80, 
        'chars'=>5, 
        'dict'=>1, 
        'lines'=>1, 
        'noise'=>1
        )
    ));  
  }

  
	public function setAttributes($params) {
		if(!empty($params) && is_array($params)) {
			$objArr = array();
			$keyLookupArr = array();

			foreach($this as $key=>$value) {
				$objArr[$key] = $value;
				$keyLookupArr[strtolower($key)] = $key;
			}	

			foreach($params as $key=>$value) {
				$key = strtolower($key);
				if(array_key_exists($key, $keyLookupArr)) {
					$key = $keyLookupArr[$key];
					if(is_array($this->$key) && !empty($this->$key)) {
						if(is_array($value))
							$this->$key = array_merge($this->$key, $value);
					} else $this->$key = $value;

				} elseif(array_key_exists("attributes", $objArr)){
					$this->attributes[$key] = $value;
        }
			}
		}

    if($this->debug===true){
      echo '<pre>', var_dump($this), '</pre>', PHP_EOL;    
    }
	}

  public function addFieldset($id, $legend) {
    $this->fieldset[$id] = array(
      'legend' => $legend, 
      'fields' => array()
      );
    $this->lastFs = $id;      
  }
  
  public function addField($name, $label, $value='', $tipo='text', $obbligatorio=0, $formato='', $options=array(), $fieldset='') {
    $class = '';
    switch($tipo){
      case 'textarea':
        $input = $this->textArea($name, $value, $obbligatorio);
        $class = ' class="full"';
        break;
      case 'password':
        $input = $this->inputPassword($name, $value, $obbligatorio);
        break;
      case 'file':
        $input = $this->fileInput($name, $obbligatorio);
        break;
      case 'checkbox':
        $input = $this->checkboxInput($name, $value, $obbligatorio, $options); 
        break;
      case 'radio':
        $input = $this->radioInput($name, $value, $obbligatorio, $options); 
        break;
      case 'select':
        $input = $this->comboBox($name, $obbligatorio, $options); 
        break;
      case 'hidden':
        $this->addHiddenField($name, $value);
        $input = '';
        break;
      case 'text':
      default:
        $input = $this->textInput($name, $value, $obbligatorio, $formato);
        break;
    }

    if($input!='') {
      $f  = "<div{$class}>\n";
      $f .= "  <label for=\"f{$name}\">".trim($label).($obbligatorio==1 ? ' '.$this->required_mark : '')."</label>\n";
      $f .= $input.PHP_EOL;
      $f .= "</div>\n";
    }

    if($fieldset){
      $this->fieldset[$fieldset]['fields'][] = $f;    
    } else {
      $this->fields[] = $f;
    } 

    $this->hook[$name] = $label;
  }

  
  public function render(){
    if($this->multipart==true){
      $enctype = ' enctype="multipart/form-data"';
      $this->addHiddenField('MAX_FILE_SIZE', $this->maxFileSize);
    } else
      $enctype = '';
      
    $this->addHiddenField('action', 'submit');
    $this->addHiddenField('formId', $this->id);    
    
    $class = ($this->class!='') ? " class=\"{$this->class}\"" : false;
    $tot   = count($this->fieldset);

    $form  = "<form{$enctype} id=\"form{$this->id}\"{$class} method=\"{$this->method}\" action=\"{$this->action}\">\n";
    $form .= $this->error;
    if($tot){
      // ci sono fieldset, aggiungo i campi speciali all'ultimo
      if($this->captcha!='nessuno'){
        $this->fieldset[$this->lastFs]['fields'][] = $this->insertCaptcha($this->captcha);
      }
  
      if($this->privacy==true){
        $this->fieldset[$this->lastFs]['fields'][] = $this->insertDisclaimer();
      }

      $this->fieldset[$this->lastFs]['fields'][] = $this->insertButtons();

      foreach($this->fieldset as $k => $s){
        $form .= "  <fieldset id=\"fs{$k}\">\n";
        $form .= "    <legend>{$s['legend']}</legend>\n";      
        $form .= implode(PHP_EOL, $s['fields']);
        $form .= "  </fieldset>\n";          
      }

    } else {
      // non ci sono fieldset, inserisco i campi nel form
      $form .= implode(PHP_EOL, $this->fields); 
  
      if($this->captcha!='nessuno'){
        $form .= $this->insertCaptcha($this->captcha);
      }
  
      if($this->privacy==true){
        $form .= $this->insertDisclaimer();
      }

      $form .= $this->insertButtons();
    }
    $form .= "</form>\n";
    $form .= $this->validateScript();

    return $form;
  }

  public function errorMsg($error){
    $this->hook['captcha'] = $this->captchaLabel;
    $this->hook['privacy'] = 'privacy';

    $ret  = "<div class=\"alert error\">\n";
    if (is_array($error)){
      $ret .= '<strong>'.$this->checkfields.'</strong>';
      $ret .= "<ul>\n";    
      foreach($error as $k=>$v){
        $ret .= "<li>{$this->hook[$k]}</li>\n";      
      }
      $ret .= "</ul>\n";      
    } else {
      $ret .= $error; 
    }
    $ret .= "</div>\n";     
    $this->error = $ret;
  } 


  private function addHiddenField($name, $value) {
    $this->hiddenfields[] = "  <input type=\"hidden\" name=\"{$name}\" value=\"{$value}\"{$this->tagClosure()}>"; 
  }


  private function textInput($name, $value='', $obbligatorio=0, $formato='') {
    $class  = 'text';
    $class .= ($obbligatorio==1) ? ' required' : '';
    $class .= ($formato!='text') ? ' '.$formato : '';
    $index = $this->tabIndex();
    
    $ret = "  <input type=\"text\" name=\"{$name}\" id=\"f{$name}\" class=\"{$class}\" value=\"{$value}\" tabindex=\"{$index}\"{$this->tagClosure()}>";
    return $ret;
  }


  private function inputPassword($name, $value='', $obbligatorio=0) {
    $class  = 'text';
    $class .= ($obbligatorio==1) ? ' required' : '';
    $index = $this->tabIndex();
    
    $ret = "  <input type=\"password\" name=\"{$name}\" id=\"f{$name}\" class=\"{$class}\" value=\"{$value}\" tabindex=\"{$index}\"{$this->tagClosure()}>";
    return $ret;
  }

  
  private function textArea($name, $value='', $obbligatorio=0) {
    $class = ($obbligatorio==1) ? ' class="required"' : '';
    $index = $this->tabIndex();    

    $ret = "  <textarea name=\"{$name}\" id=\"f{$name}\"{$class} rows=\"6\" cols=\"60\" tabindex=\"{$index}\">{$value}</textarea>";
    return $ret;    
  }


  private function fileInput($name, $obbligatorio=0) {
    $this->multipart = true;
    $class  = 'text';
    $class .= ($obbligatorio==1) ? ' required' : '';
    $index = $this->tabIndex();

    $ret = "  <input type=\"file\" name=\"{$name}\" id=\"f{$name}\" class=\"{$class}\" tabindex=\"{$index}\"{$this->tagClosure()}>";
    return $ret;    
  }  


  private function checkboxInput($name, $value='', $obbligatorio=0, $options=array()) {
    $this->haschecks = true;
    $class  = 'checkbox';
    $class .= ($obbligatorio==1) ? ' required' : '';

    if(count($options)>0){
      $i = '';
      foreach($options as $k=>$v){
        $index = $this->tabIndex();
        $checked = ($v['selezionato']==1) ? ' checked="checked"' : '';    
        $ret .= <<<EOPTS
        <input type="checkbox" name="{$name}[]" id="f{$name}{$i}" class="{$class}" value="{$k}"{$checked} 
          tabindex="{$index}"{$this->tagClosure()}><span class="{$this->cblabelclass}">{$v['label']}</span><br{$this->tagClosure()}>
EOPTS;
        $i ++;      
      }    
    } else {
        $ret .= "  <input type=\"checkbox\" name=\"{$name}[]\" id=\"f{$name}\" class=\"{$class}\" value=\"{$value}\" tabindex=\"{$index}\"{$this->tagClosure()}>";
    }
    return $ret;    
  }  


  private function radioInput($name, $value='', $obbligatorio=0, $options=array()) {
    $this->haschecks = true;  
    $class  = 'radio';
    $class .= ($obbligatorio==1) ? ' required' : '';
    
    if(count($options)>0){
      $i = '';
      $sel = false;
      foreach($options as $k=>$v){
        $index = $this->tabIndex();
        if(!$sel && $v['selezionato']==1){
          $checked = ' checked="checked"';
          $sel = true;        
        } else $checked = ''; 
        $ret .= <<<EOPTS
        <input type="radio" name="{$name}[]" id="f{$name}{$i}" class="{$class}" value="{$k}"{$checked} 
          tabindex="{$index}"{$this->tagClosure()}><span class="{$this->cblabelclass}">{$v['label']}</span><br{$this->tagClosure()}>
EOPTS;
        $i ++;
      }    
    }
    return $ret;    
  }  


  private function comboBox($name, $obbligatorio=0, $options=array()) {
    if(count($options)==0) return;

    $class .= ($obbligatorio==1) ? ' class="required"' : '';
    $index  = $this->tabIndex();    
    $sel = false;
    $ret = "  <select name=\"{$name}\" id=\"f{$name}\"{$class} tabindex=\"{$index}\">\n";
    foreach($options as $k=>$v){
      $index = $this->tabIndex();
      if(!$sel && $v['selezionato']==1){
        $checked = ' selected="selected"';
        $sel = true;        
      } else $checked = ''; 
          
      $ret .= "    <option value=\"{$k}\"{$checked}>{$v['label']}</option>\n"; 
    }    
    $ret .= "  </select>\n";

    return $ret;
  }  


  private function insertCaptcha($type){
    $ret = '';
    switch($type){
      case 'nessuno':
        break;
      case 'synCaptcha':
        $ret = $this->setSynCaptcha();
        break;
      case 'reCaptcha':
        $ret = $this->setReCaptcha();
        break;
      default:
        $ret = $this->setSecurityImage();      
        break;
    }
    return $ret;
  }


  private function setSecurityImage(){
    extract($this->captchaConfig);
    $src = "/public/CaptchaSecurityImages.php?width={$width}&height={$height}&character={$chars}";
    $ret = <<<EOSC
      <div class="full">
        <img src="{$src}" width="{$width}" height="{$height}" class="captcha" alt=""{$this->tagClosure()}>
        <label for="fcaptcha">{$this->captchaLabel}</label>
        <input type="text" name="captcha" id="fcaptcha" class="text required" tabindex="{$this->tabIndex()}"{$this->tagClosure()}>
      </div>
EOSC;
    $this->validateRules[] = 'captcha:{required:true, remote:"/public/server/validate_captcha.php"}';
    return $ret;
  }  


  private function setSynCaptcha(){
    extract($this->captchaConfig);  
    $src = "/public/lib/syncaptcha/synCaptcha.php?width={$width}&amp;height={$height}&amp;characters={$chars}&amp;use_dict={$dict}&amp;lines={$lines}&amp;noise={$noise}";  
    $ret = <<<EOSC
      <div class="full">
        <img src="{$src}" width="{$width}" height="{$height}" class="captcha" alt=""{$this->tagClosure()}>
        <label for="fcaptcha">{$this->captchaLabel}</label>
        <input type="text" name="captcha" id="fcaptcha" class="text required" tabindex="{$this->tabIndex()}"{$this->tagClosure()}>
      </div>
EOSC;
    $this->validateRules[] = 'captcha:{required:true, remote:"/public/server/validate_captcha.php"}';
    return $ret;
  }


  private function setReCaptcha(){  
    require_once('./recaptchalib.php');
    $publickey = '6LeldL8SAAAAALRJlfPAx3T1ZlGK8CwpyJtfUuP1';
    $ret = recaptcha_get_html($publickey);
    $this->validateRules[] = 'recaptcha_response_field:{ required:true, remote:{url:"/public/server/validate_recaptcha.php", type:"post", data:{recaptcha_challenge_field: function(){ return $("#recaptcha_challenge_field").val();} }}}';
    return $ret;
  }


  private function insertDisclaimer(){
    $ret = <<<EOPD
      <div class="full">
        <label for="fprivacy">{$this->privacyLabel}</label>
        <div id="finformativa" class="privacy-disclaimer">{$this->informativa}</div>
      </div>
      <div class="full">
        <input type="checkbox" name="privacy" id="fprivacy" class="checkbox required" value="1" 
          tabindex="{$this->tabIndex()}"{$this->tagClosure()}><span 
          class="{$this->cblabelclass}">{$this->approvazione}</span><br{$this->tagClosure()}>
      </div>
EOPD;
    return $ret;
  }


  private function insertButtons(){
    $ret  = "  <div class=\"button-wrap\">\n";
    $ret .= implode(PHP_EOL, $this->hiddenfields);    
    $ret .= "    <button type=\"submit\" class=\"submit\" tabindex=\"{$this->tabIndex()}\"><span><b>{$this->submitLabel}</b></span></button>\n";
    $ret .= "    <button type=\"reset\" class=\"reset\"><span><b>{$this->resetLabel}</b></span></button>\n";
    $ret .= "  </div>\n";  
    return $ret;
  }


  private function validateScript(){
    $params = array();
    // campi particolari
    if($this->haschecks){
      $params[] = "    highlight: function(element){ $(element).addClass('error').siblings('span.{$this->cblabelclass}').addClass('error-field');}";
      $params[] = "    unhighlight: function(element, errorClass, validClass){ $(element).removeClass('error').siblings('span.{$this->cblabelclass}').removeClass('error-field'); }";
    }

    // regole particolari
    if(is_array($this->validateRules) && count($this->validateRules)>0){
      $params[] = "    rules:{".implode(",\n", $this->validateRules)."}";
    }  
  
    $js  = "<script type=\"text/javascript\">\n".($this->xhtml==true ? "//<![CDATA[\n" : '');
    $js .= "  $(document).ready(function(){\n";
    $js .= "    $.validator.messages.required=\"".$this->error1."\";\n";
    $js .= "    $.validator.messages.email=\"".$this->error2."\";\n";
    $js .= "    $.validator.messages.remote=\"".$this->error3."\";\n";
    $js .= "    $(\"#form{$this->id}\").validate({\n".implode(",\n", $params)."\n    });\n";
    $js .= "  });\n";
    $js .= ($this->xhtml==true ? "//]]>\n" : '')."</script>\n";
    
    return $js;  
  }


  private function tabIndex(){
    return $this->tab ++;
  }


  private function tagClosure(){
    if($this->xhtml==true) return ' /';
  }
  
} //end class formBuilder
?>
