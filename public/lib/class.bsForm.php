<?php

// Twitter Bootstrap extension for FormBuilder

class bsForm extends formBuilder {
  private $counter = 0;
  protected $input_class = 'form-control';

  public function addField($name, $label, $value='', $tipo='text', $obbligatorio=0, $formato='', $options=array(), $fieldset='', $hint='', $disabled=false) {
    $identifier = (is_array($name))
                ? 'f-'.$this->sanitizeName($name[0])
                : 'f-'.$this->sanitizeName($name);
    $split_column = false;
    $label_tag  = ($this->hide_label)
                ? null
                : "  <label for=\"{$identifier}\" class=\"control-label\">".trim($label).($obbligatorio==1 ? ' '.$this->required_mark : '')."</label>";
    switch($tipo){
      case 'html':
        $input = $value;
        $label_tag = '';
        $hint = '';
        break;
      case 'html_short':
        $input = $value;
        $label_tag = '';
        $hint = '';
        break;
      case 'button':
        $label_tag = $hint = '';
        $input = $this->button($name, $label, $value, $formato);
        break;
      case 'textarea':
        $input = $this->textArea($name, $identifier, $value, $obbligatorio, $hint);
        break;
      case 'password':
        $input = $this->inputPassword($name, $identifier, $value, $obbligatorio, $hint, $label, false);
        $split_column = true;
        break;
      case 'file':
        $input = $this->fileInput($name, $identifier, $obbligatorio, $hint);
        $split_column = true;
        break;
      case 'checkbox':
        //$label_tag = '';
        $input = $this->checkboxInput($name, $identifier, $value, $obbligatorio, $options, $hint, $label);
        $split_column = true;
        break;
      case 'radio':
        $input = $this->radioInput($name, $identifier, $value, $obbligatorio, $options, $hint);
        $split_column = true;
        break;
      case 'select':
        $input = $this->comboBox($name, $identifier, $value, $obbligatorio, $options, $hint, $disabled);
        $split_column = true;
        break;
      case 'hidden':
        $this->addHiddenField($name, $value);
        $label_tag = '';
        $input = $hint = '';
        break;
      case 'range':
        $identifier = 'f-'.implode('-', $name);
        $input = $this->rangeField($name, $identifier, $value);
        break;
      case 'text':
      default:
        $input = $this->textInput($name, $identifier, $value, $obbligatorio, $formato, $hint, $label);
        $split_column = true;
        break;
    }

    if($input!='') {
      $this->counter ++;

      $label = trim($label).($obbligatorio==1 ? ' '.$this->required_mark : null);
      $hint = ($hint) ? "<span class=\"help-block\">{$hint}</span>" : null;
      $f = '';
      $span = null;
      if ($this->class=='form-vertical') {
        $span = ($split_column) ? 'span6 ' : 'span12 clearfix ';
        if ($this->counter==1) {
          $f .= '<div class="row-fluid">';
        }
      }

      $f  .= <<<ENDOFFIELD
      <div class="{$span}form-group">
        {$label_tag}
        {$input}
        {$hint}
      </div>
ENDOFFIELD;

      if ($this->class=='form-vertical') {
        if ($this->counter==2 || $split_column==false) {
          $f .= '</div>'.PHP_EOL;
          $this->counter = 0;
        }
      }

      if($fieldset){
        $this->fieldset[$fieldset]['fields'][] = $f;
      } else {
        $this->fields[] = $f;
      }
    }
    if (is_array($name))
      $this->hook[$name[0]] = $label;
    else
      $this->hook[$name] = $label;
  }


  function render(){
    $enctype = '';
    if($this->multipart==true){
      $enctype = ' enctype="multipart/form-data"';
      $this->addHiddenField('MAX_FILE_SIZE', $this->maxFileSize);
    }
    $this->addHiddenField('action', 'submit');
    $this->addHiddenField('formId', $this->id);

    $class = ($this->class!='') ? " class=\"{$this->class}\"" : false;
    $tot   = count($this->fieldset);

    $form  = "<form{$enctype} role=\"form\" id=\"form{$this->id}\"{$class} method=\"{$this->method}\" action=\"{$this->action}\">\n";
    $form .= $this->error;
    //if ($this->ajax_submit)      $form .= "<div class=\"form-respond text-center\"></div>\n";

    if ($tot) {
      // ci sono fieldset
      $cnt = 1;
      foreach ($this->fieldset as $k => $s) {
        $block_class = 'item_bottom'; // classi per skroller
        if ($cnt == 1) {
          $form .= "<div class=\"row\">\n";
          $block_class = 'item_top'; // classi per skroller
        }
        $form .= "  <fieldset id=\"fs{$k}\" class=\"col-md-6 col-sm-6 col-md-6 col-xs-12\">\n";
        $form .= "    <div class=\"{$block_class}\">\n";
        if ($s['legend']!='' && !is_numeric($s['legend']))
          $form .= "    <legend><span>{$s['legend']}</span></legend>\n";
        $form .= implode(PHP_EOL, $s['fields']);
        $form .= "    </div>\n";
        $form .= "  </fieldset>\n";

        if ($cnt == 2) {
          $form .= "</div>\n";
          $cnt = 1;
        } else
          $cnt = 2;
      }
      if ($cnt == 2) {
        $form .= "</div>\n";
      }       

      $form .= "<div class=\"row\"><div class=\"col-md-12 text-center\">\n";
      if ($this->captcha!='nessuno')
        $form .= $this->insertCaptcha($this->captcha);

      if ($this->privacy == true)
        $form .= $this->insertDisclaimer();

      if ($this->inline_buttons){
        $form .=  $this->insertInlineButtons($this->buttons);
      } else {
        $form .=  $this->insertButtons($this->buttons);
      }
      $form .= "</div></div>\n";

    } else {
      // non ci sono fieldset, inserisco i campi nel form
      $form .= implode(PHP_EOL, $this->fields);

      if($this->captcha!='nessuno'){
        $form .= $this->insertCaptcha($this->captcha);
      }

      if($this->privacy==true){
        $form .= $this->insertDisclaimer();
      }

      if($this->inline_buttons){
        $form .= $this->insertInlineButtons($this->buttons);
      } else {
        $form .= $this->insertButtons($this->buttons);
      }
    }
    $form .= "</form>\n";

    if ($this->include_script)
      $form .= $this->validateScript();

    return $form;
  }

  function textInput($name, $identifier, $value='', $obbligatorio=0, $formato='', $hint='', $label) {
    $class  = 'form-control input-lg';
    $class  = $this->input_class;
    $class .= ($obbligatorio==1) ? ' required' : '';
    $class .= ($formato!='text') ? ' '.$formato : '';
    $index = $this->tabIndex();
    $ph    = ($this->hide_label) ? " placeholder=\"{$label}\"" : '';

    $ret = "  <input type=\"text\" name=\"{$name}\" id=\"{$identifier}\" class=\"{$class}\" value=\"{$value}\" tabindex=\"{$index}\"{$ph}{$this->tagClosure()}>";
    return $ret;
  }


  function inputPassword($name, $identifier, $value='', $obbligatorio=0, $hint='', $label) {
    $class  = 'form-control input-lg';
    $class .= ($obbligatorio==1) ? ' required' : '';
    $index = $this->tabIndex();
    $ph    = ($this->hide_label) ? " placeholder=\"{$label}\"" : '';

    $ret = "  <input type=\"password\" name=\"{$name}\" id=\"{$identifier}\" class=\"{$class}\" value=\"{$value}\" autocomplete=\"off\" tabindex=\"{$index}\"{$ph}{$this->tagClosure()}>";
    return $ret;
  }


  function textArea($name, $identifier, $value='', $obbligatorio=0, $label) {
    $class = ($obbligatorio==1) ? ' required' : '';
    $index = $this->tabIndex();

    $ret = "  <textarea name=\"{$name}\" id=\"{$identifier}\" class=\"form-control input-lg{$class}\" rows=\"5\" cols=\"60\" tabindex=\"{$index}\"></textarea>";
    return $ret;
  }

  function checkboxInput($name, $identifier, $value='', $obbligatorio=0, $options=array(), $hint, $label) {
    $this->haschecks = true;
    $class  = '';
    $ml   = ($obbligatorio==1) ? ' required minlength="1"' : '';
    $ret  = '';
    $i    = 0;
    foreach ($options as $k=>$v) {
      $i ++;
      $index = $this->tabIndex();
      $checked = ($v['selezionato']==1) ? ' checked="checked"' : '';
      $required = ($i==1) ? $ml : null;
      $ret .= <<<EOPTS
      <div class="checkbox">
        <label>
          <input type="checkbox" name="{$name}[]" id="{$identifier}{$i}" value="{$k}"{$checked} tabindex="{$index}"{$required}{$this->tagClosure()}>
          {$v['label']}
        </label>
      </div>
EOPTS;
      $i ++;
    }
    return $ret;
  }

  function radioInput($name, $identifier, $value='', $obbligatorio=0, $options=array()) {
    $this->haschecks = true;
    $class  = 'radio';
    $class .= ($obbligatorio==1) ? ' required' : '';
    $ret    = '';

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
        <label class="radio inline">
          <input type="radio" name="{$name}[]" id="{$identifier}{$i}" class="{$class}" value="{$k}"{$checked}
            tabindex="{$index}"{$this->tagClosure()}> {$v['label']}</label>
EOPTS;
        $i ++;
      }
    }
    return $ret;
  }



  function comboBox($name, $identifier, $value='', $obbligatorio=0, $options=array(), $hint, $disabled) {
    if(count($options)==0) return;

    $class = ($obbligatorio==1) ? ' required' : '';
    $index = $this->tabIndex();
    $sel = false;
    $ret = '';
    $readonly = '';

    if($disabled){
      $readonly = ' disabled="disabled"';
      $ret .= "<input type=\"hidden\" name=\"{$name}\" value=\"{$value}\"/>";
    }
    $ret .= "  <select name=\"{$name}\" id=\"{$identifier}\" class=\"input-xlarge{$class}\" tabindex=\"{$index}\"{$readonly}>\n";
    foreach($options as $k=>$v){
      $index = $this->tabIndex();
      if(! $sel
        && (( $value=='' && $v['selezionato']==1)
           || $k==$value)
        ){
        $checked = ' selected="selected"';
        $sel = true;
      } else $checked = '';

      $ret .= "    <option value=\"{$k}\"{$checked}>{$v['label']}</option>\n";
    }
    $ret .= "  </select>\n";
    return $ret;
  }


  function rangeField($name, $identifier, $value = array(null, null)) {
    $checked = null;
    $disabled = null;
    if (empty($value[2]) || $value[2]=='ssss'){
      $checked = ' checked="checked"';
      $disabled = ' disabled="disabled"';
    }
    $ret = <<<EORF
    <div class="input-daterange">
      <input type="text" class="input-small" name="{$name[0]}" value="{$value[0]}" id="{$identifier}" {$this->tagClosure()}>
      <span class="add-on">al</span>
      <input type="text" class="input-small" name="{$name[1]}" value="{$value[1]}"{$disabled} {$this->tagClosure()}>
    </div>
    <label class="checkbox inline">
      <input type="checkbox" class="trigger"{$checked}> Tuttora
    </label>
EORF;

    return $ret;
  }

  function insertDisclaimer(){
    $ret = <<<EOPD
      <div class="control-group">
        <label class="control-label">Privacy</label>
        <div class="controls">
          <p class="form-disclaimer">{$this->informativa}</p>
        </div>
      </div>
      <div class="control-group">
        <div class="controls">
          <label class="checkbox">
            <input type="checkbox" name="privacy" id="fprivacy" class="checkbox required" value="1" tabindex="{$this->tabIndex()}"{$this->tagClosure()}>
            {$this->approvazione}
          </label>
        </div>
      </div>

EOPD;
    return $ret;
  }



  function insertCaptcha($type){
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
      case 'honeypot':
        $ret = $this->setHoneypot();
        break;
      default:
        $ret = $this->setSecurityImage();
        break;
    }
    return $ret;
  }




  function setSynCaptcha(){
    extract($this->captchaConfig);
    $src = "/public/lib/syncaptcha/synCaptcha.php?width={$width}&amp;height={$height}&amp;characters={$chars}&amp;use_dict={$dict}&amp;lines={$lines}&amp;noise={$noise}";
    $ret = <<<EOSC
      <div class="form-group">
        <div class="row">
          <div class="col-md-4">
            <img src="{$src}" width="{$width}" height="{$height}" class="captcha" alt=""{$this->tagClosure()}>
          </div>
          <div class="col-md-8">
            <label>{$this->captchaLabel}</label>
            <input type="text" name="captcha" id="fcaptcha" class="form-control input-lg required" tabindex="{$this->tabIndex()}"{$this->tagClosure()}>
          </div>
        </div>
      </div>
EOSC;
    $this->validateRules[] = 'captcha:{required:true, remote:"/public/server/validate_captcha.php"}';
    return $ret;
  }



  function insertButtons($buttons){
    $ret  = "<div class=\"form-group\">\n";
    $ret .= implode(PHP_EOL, $this->hiddenfields).PHP_EOL;
    if ($buttons == true) {
      if ($this->submit_button==true) {
        $class = $this->button_class.' '.$this->submit_button_class;
        $ret .= "<button class=\"{$class}\" type=\"submit\" id=\"submit\" tabindex=\"{$this->tabIndex()}\">{$this->submitLabel}</button>";
      }
      if ($this->reset_button == true) {
        $class = $this->button_class.' '.$this->reset_button_class;
        $ret .= "  <button type=\"reset\" class=\"{$class}\">{$this->resetLabel}</button>\n";
      }
      if(count($this->additional_buttons)>0){
        foreach($this->additional_buttons as $b){
          $ret .= $b;
        }
      }
    }
    $ret .= "</div>\n";
    return $ret;
  }

  /*
  function validateScript(){
    $params = array();
    // campi particolari
    if($this->haschecks){
      $params[] = "    highlight: function(element){ $(element).addClass('error').siblings('span.{$this->cblabelclass}').addClass('error-field');}";
      $params[] = "    unhighlight: function(element, errorClass, validClass){ $(element).removeClass('error').siblings('span.{$this->cblabelclass}').removeClass('error-field'); }";
    }

    // regole particolari
    if ( is_array($this->validateRules)
      && count($this->validateRules)>0
      ) $params[] = "    rules:{".implode(",\n", $this->validateRules)."}";

    $rules = implode(",\n  ", $params);
    $js  = <<<EOSCRIPT
    <script type="text/javascript">
      function sdValidate(form){
        $.validator.messages.required="{$this->error1}";
        $.validator.messages.email="{$this->error2}";
        $.validator.messages.remote="{$this->error3}";
        $.validator.messages.equalTo="{$this->error4}";
        form.validate({
          {$rules}
        });
      }

      $(document).ready(function(){
        var form = $("#form{$this->id}");
        sdValidate(form);
      });
    </script>
EOSCRIPT;

    return $js;
  }
  */
}


// EOF class.bsForm.php
