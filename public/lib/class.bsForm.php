<?php

// Twitter Bootstrap extension for FormBuilder

class bsForm extends formBuilder {
  private $counter = 0;
  protected $input_class = 'form-control';
  protected $button_class = 'btn';
  protected $submit_button_class = 'btn-primary';
  protected $reset_button_class = 'btn-default';


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
        $input = $this->textArea($name, $identifier, $value, $obbligatorio, $label);
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

    if ($input != '') {
      if (isset($this->group_fields[$name])) {
        // field is associated to a group
        $group_id = $this->group_fields[$name];
        if (!isset($this->groups[$group_id]['fields'][$name])) {
          // add field to group
          $this->groups[$group_id]['fields'][$name] = array(
            'label' => $label_tag,
            'input' => $input,
            'key' => $this->field_key
          );
        }

      } else {
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
      } // if (isset($this->group_fields[$name]))
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

    // special groups management!
    if (!empty($this->groups)) {
      foreach($this->groups as $k => $g) {
        $html = $g['html'];
        $fset = $g['fieldset'];

        if (!empty($g['fields'])) {
          $field_key = 0;
          foreach ($g['fields'] as $key => $field) {
            // replace the field html with the one provided by the group
            $find = array("%%{$key}_label%%", "%%{$key}%%");
            $replace = array($field['label'], $field['input']);
            $html = str_replace($find, $replace, $html);
            if ($field_key == 0)
              $field_key = $field['key'];
          }
          // remove group, as it's not needed anymore
          unset( $this->groups[$k] );
        }

        if(!empty($fset) && isset($this->fieldset[$fset])){
          // add the modified html to its fieldset
          $this->fieldset[$fset]['fields'][ $field_key ] = $html;
        } else {
          // add the modified html to the form
          $this->fields[ $field_key ] = $html;
        }
      }
    }

    if ($tot) {
      // ci sono fieldset
      $cnt = 1;
      foreach ($this->fieldset as $k => $s) {
        ksort( $s['fields'], SORT_NUMERIC );
        $fs = '';

        if ( !empty($s['legend']) && !is_numeric($s['legend']) )
          $fs .= "    <legend><span>{$s['legend']}</span></legend>\n";

        if (!empty($s['fields'])) {
          $fs .= "  <fieldset id=\"fs{$k}\" class=\"col-md-12\">\n";
          $fs .= implode(PHP_EOL, $s['fields']);
          $fs .= "  </fieldset>\n";
        }

        if (isset($this->group_fieldset[ $k ])) {
          // fieldset associated to a group
          $group_id = $this->group_fieldset[ $k ];
          if (!isset($this->groups[ $group_id ]['fields'][$name])) {
            // seize the fieldset for later use
            $this->groups[$group_id]['html'] .= $fs;
          }
        } else {
          // append the fieldset to the main form
          $form .= $fs;
        }
      }

      $form .= "<div class=\"row\"><div class=\"col-md-12 text-center\">\n";
      if ($this->captcha!='nessuno')
        $form .= $this->insertCaptcha($this->captcha);

      if ($this->privacy == true)
        $form .= $this->insertDisclaimer();

      if ($this->inline_buttons)
        $form .=  $this->insertInlineButtons($this->buttons);
      else
        $form .=  $this->insertButtons($this->buttons);

      $form .= "</div></div>\n";

    } else {
      // non ci sono fieldset, inserisco i campi nel form
      ksort( $this->fields, SORT_NUMERIC );

      $form .= implode(PHP_EOL, $this->fields);

      if ( $this->captcha != 'nessuno' )
        $form .= $this->insertCaptcha($this->captcha);

      if ( $this->privacy == true )
        $form .= $this->insertDisclaimer();

      if ( $this->inline_buttons )
        $form .= $this->insertInlineButtons($this->buttons);
      else
        $form .= $this->insertButtons($this->buttons);
    }
    $form .= "</form>\n";

    if ( $this->include_script )
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
    $ph    = ($this->hide_label) ? " placeholder=\"{$label}\"" : '';

    $ret = "  <textarea name=\"{$name}\" id=\"{$identifier}\" class=\"form-control input-lg{$class}\" rows=\"5\" cols=\"60\" tabindex=\"{$index}\"{$ph}></textarea>";
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


  function linkedDisclaimer() {
    $disclaimer = sprintf( $this->approvazione_link, $this->submitLabel, $this->privacy_page );
    $ret = <<<EOPD
    <div class="control-group">
      <input type="hidden" name="privacy" id="fprivacy" value="1" {$this->tagClosure()}>
      <p class="text-muted"><small>{$disclaimer}</small></p>
    </div>
EOPD;
    return $ret;
  }


  function defaultDisclaimer(){
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

}


// EOF class.bsForm.php
