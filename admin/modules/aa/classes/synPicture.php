<?php
/* ------------------------------------------------ *
 * class synPICTURE                                 *
 * Creates an image selector powered by KCfinder    *
 * ------------------------------------------------ */

class synPicture extends synElement {
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
    $this->db    = " varchar(255) NULL DEFAULT NULL";
    $this->mat   = $mat;
    $this->thumb = "{$synPublicPath}/thumb.php?src=%s&amp;w=250&amp;h=250&amp;far=1";
  }

  //private function
  function _html() {
    global $synPublicPath, $mat;

    $value  = str_replace("\"", "&quot;", $this->translate( $this->getValue() ) );
    $lang   = $_SESSION['aa_CurrentLangInitial'];
    $kc_url = '/admin/assets/js/vendor/kcfinder/browse.php?type=image&theme=default&lang=' . $lang;

    if ( $this->isImage($value) ) {
      $thumb = sprintf( $this->thumb, $value );
      $src = "<img src=\"{$thumb}\" class=\"thumbnail\" alt=\"{$value}\"\>";
    } else
      $src = NULL;

    $ret = <<<EORET
    <div id="{$this->name}-preview" class="synpicture" data-tmb-url="{$this->thumb}" data-kc-url="{$kc_url}">
      <div class="preview">{$src}</div>
      <div class="input-group">
        <input id="{$this->name}" type="text" name="{$this->name}" class="form-control" value="{$value}" readonly />
        <div class="input-group-btn">
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
    $filename     = $this->translate( $this->value );
    if ( !empty($filename) ) {
      $file_exists  = is_file( $synAbsolutePath . $filename );
      $ext          = pathinfo( $synAbsolutePath . $filename, PATHINFO_EXTENSION );
      if ( $file_exists ) {
        $is_image     = $this->isImage( $filename );
        $size         = filesize( $synAbsolutePath . $filename );
        $fsize        = byteConvert( $size );
        list($w, $h)  = getimagesize( $synAbsolutePath . $filename );
        if ($size < 100000)
          $filename = sprintf( $this->thumb, $filename );
        $show = "style=\"background-image:url('{$filename}');\"";
        $ret  = "<a class=\"preview\" {$show} href=\"{$filename}\" data-ext=\"{$ext}\" data-size=\"{$fsize}\" " 
              . "data-width=\"{$w}\" data-height=\"{$h}px\">&nbsp;</a>";
      } else {
        $ret = "<span class=\"text-muted\">Error {$filename}</span>";
      }
    } else {
      $ret = '<span class="text-muted">Empty</span>';
    }
    return $ret;
  }
  
  //check if it is an image or a document
  function isImage($filename) {
    global $synAbsolutePath;
    $ret = false;
    if (file_exists( $synAbsolutePath . $filename ) ) {
      if ( @getimagesize( $synAbsolutePath . $filename ) !== false)
        $ret = true;
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
// end of class synPicture
