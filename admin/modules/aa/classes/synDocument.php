<?php
/* ------------------------------------------------ *
 * class synDocument                                *
 * Creates an image selector powered by KCfinder    *
 * ------------------------------------------------ */

class synDocument extends synElement {
  var $mat;

  //constructor(name, value, label, size, help, $mat)
  function __construct( $n = '', $v = '', $l = '', $s = 255, $h = '', $mat = '/mat/') {
    global $synPublicPath;

    if (empty($n))
      $n = 'text' . date('his');
    if (empty($l))
      $l = ucfirst($n);

    $this->type  = 'file';
    $this->name  = $n;
    if ($v==null) {
      global $$n;
      $this->value = $$n;
    } else
      $this->value = $v;
    $this->label = $l;
    $this->size  = $s;
    $this->help  = $h;
    $this->db    = " varchar(255) NOT NULL";
    $this->mat   = $mat;
  }

  //private function
  function _html() {
    global $synPublicPath, $mat;
    
    $value  = str_replace("\"", "&quot;", $this->translate( $this->getValue() ) );
    $lang   = $_SESSION['aa_CurrentLangInitial'];
    $kc_url = '/admin/assets/js/vendor/kcfinder/browse.php?type=file&theme=default&lang=' . $lang;

    $ret = <<<EORET
    <div id="{$this->name}-preview" class="syndocument" data-kc-url="{$kc_url}">
      <div class="input-group">
        <input id="{$this->name}" type="text" name="{$this->name}" class="form-control" value="{$value}" readonly />
        <div class="input-group-btn">
          <a href="{$value}" class="btn btn-default btn-download" target="_blank">
            <i class="fa fa-download"></i>
            Download
          </a>
          <button type="button" class="btn btn-default btn-clean">
            <i class="fa fa-times"></i>
            Remove
          </button>
          <button type="button" class="btn btn-primary btn-browse">
            <i class="fa fa-folder-open-o"></i>
            Browse
          </button>
        </div>
      </div>
      <div class="modal fade" tabindex="-1" role="dialog" style="background-color:rgba(0,0,0,.3)">
        <div class="modal-dialog" role="document" style="width:90%;">
          <div class="modal-content">
            <div class="modal-body" style="height:90vh;"></div>
          </div>
        </div>
      </div>
    </div>
EORET;
    $_SESSION['KCFINDER']['disabled'] = false;
    $_SESSION['KCFINDER']['uploadURL'] = $synPublicPath . $mat . DIRECTORY_SEPARATOR;

    return $ret;    
  }

  //get the label of the element
  function getCell() {
    global $synAbsolutePath;
    $ret          = '';
    $show         = null;
    $filename     = urldecode($this->translate( $this->value ));
    if ( !empty($filename) ) {
      $file_exists  = is_file( $synAbsolutePath . $filename );
      $ext          = pathinfo( $synAbsolutePath . $filename, PATHINFO_EXTENSION );
      $basename     = basename( $filename ); 
      if ( $file_exists ) {
        $ret  = "<a class=\"btn btn-link\" href=\"{$filename}\" target=\"_blank\">{$basename}</a>";
      } else {
        $ret = "<span class=\"text-muted\">Error: {$basename}</span>";
      }
    } else {
      $ret = '<span class="text-muted">Empty</span>';
    }
    return $ret;
  }
   
  //function for the auto-configuration
  function configuration( $i = '', $k = 99 ) {
    global
      $synElmName, $synElmType, $synElmLabel, $synElmSize, $synElmHelp, $synElmPath, 
      $synChkKey, $synChkVisible, $synChkEditable, $synChkMultilang;    

    $synHtml = new synHtml();

    if ( !isset($synElmSize[$i]) || empty($synElmSize[$i]) ) 
      $synElmSize[$i] = $this->size;
    $this->configuration[4] = "Dimensione: " . $synHtml->text(" name=\"synElmSize[{$i}]\" value=\"{$synElmSize[$i]}\"");

    //enable or disable the 3 check at the last configuration step
    $_SESSION['synChkKey'][$i]        = 0;
    $_SESSION['synChkVisible'][$i]    = 1;
    $_SESSION['synChkEditable'][$i]   = 0;
    $_SESSION['synChkMultilang'][$i]  = 1;

    if ($k==99)
      return $this->configuration;
    else
      return $this->configuration[$k];
  }
} 
// end of class synDocument
