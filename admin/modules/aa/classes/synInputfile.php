<?php

/*************************************
* class INPUTFILE                    *
* Create a input type="file" obj     *
**************************************/
class synInputfile extends synElement {

  var $mat;

  //constructor(name, value, label, size, help, $mat)
  function synInputfile( $n = '', $v = '', $l = '', $s = 255, $h = '', $mat = '/mat/') {
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


  function _html() {
    global ${$this->name}, $synAbsolutePath, $synPublicPath;
    $ret = $disabled = '';
    //$httpHost = getenv("HTTP_HOST");
    $mat = $this->compileQry( $this->translatePath($this->mat) );
    $filext = $this->translate( $this->value );
    $filename = $this->createFilename() . "." . $filext;
    $filepath = $mat . $filename;

    if ($this->isImage($filepath)) {
      $thumb = "{$synPublicPath}/thumb.php?src={$filepath}&amp;w=250&amp;h=250&amp;far=1";
      $src = "'<img src=\"{$thumb}\" class=\"file-preview-image\">'";
    } else
      $src = "'<a href=\"/{$filepath}\">{$filename}</a>'";

    if (!empty($filext)) {
      $preview = $src;
      $label = "'{$filename}'";
    } else {
      $preview = 'null';
      $label = 'null';
    }

    $ret = <<<EORET
    <input id="{$this->name}" type="file" data-name="{$this->name}" name="" class="file-input-control" />
    <input type="hidden" name="{$this->name}_old" value="{$filext}" />
    <script type="text/javascript">
      preview['{$this->name}'] = new Array();
      preview['{$this->name}']['src'] = {$preview};
      preview['{$this->name}']['label'] = {$label};
    </script>
EORET;

    return $ret;
  }


  //create the file name
  function createFilename($withLang=true) {
    //global $aa_CurrentLang;
    if(!isset($_SESSION))
      session_start();
    $aa_CurrentLang = $_SESSION['aa_CurrentLang'];
    $container  = $this->container;
    $key        = $container->getKey();
    $table      = $container->table;
    if ($this->multilang==1 && $withLang)
      $multilang = '_' . $this->getLang();
    else
      $multilang = '';
    //$filename = $table . '_' . $this->name . '_' . str_replace("'", '', str_replace("`", '', str_replace( '=', "", trim(urldecode($key))))) . $multilang;
    $filename = $table . '_' . $this->name . '_' . str_replace( array( "'", "`", '='), '', trim(urldecode($key)) ) . $multilang;
    return $filename;
  }

  //upload the document...
  function uploadDocument() {
    global $synAbsolutePath, ${$this->name}, ${$this->name . '_name'};

    $documentRoot = $synAbsolutePath . DIRECTORY_SEPARATOR;
    $mat          = $this->compileQry( $this->translatePath( $this->mat ) );
    if ( isset( $_FILES[ $this->name ] ) ) {
      $name         = $_FILES[ $this->name ]['name'];
      $pieces       = explode( '.', $name);
      $ext          = strtolower( $this->translate( end($pieces) ) );
      $filename     = $this->createFilename() . '.' . $ext;
      $file         = $_FILES[ $this->name ]['tmp_name'];
      $originalname = $_FILES[ $this->name ]['name'];

      if ( $file != 'none'
        && $file != ''
        && $originalname != ''
        ){
        if ( !is_dir( $documentRoot . $mat ) )
          mkdir( $documentRoot . $mat );
        $mime = str_replace( array('"', "'"), '', $_FILES[ $this->name ]['type'] );

        if (synElement::isFileTypeAllowed( $mime )) {
          move_uploaded_file( $file, $documentRoot . $mat . $filename );
          @chmod( $documentRoot . $mat . $filename, 0777 );
        } else {
          echo "<script>alert('File type {$mime} not allowed');</script>";
        }
      }
    }
  }

  //normally an element doesn't have a document to delete (only synInputfile)
  function deleteDocument() {
    global $synAbsolutePath;
    include_once( '../../includes/php/utility.php' );
    $ext = $this->translate( $this->getValue() );
    $mat = $this->compileQry( $this->translatePath($this->mat) );
    $filename = $this->createFilename(false);
    if ($this->multilang==1) {
      $fileToBeRemoved = $synAbsolutePath . $mat . DIRECTORY_SEPARATOR . $filename . '_*';
    } else {
      $fileToBeRemoved = $synAbsolutePath . $mat . DIRECTORY_SEPARATOR . $filename . '.*';
    }
    foreach ( glob( $fileToBeRemoved ) as $filename ) {
      unlink( $filename );
    }
  }

  //get the values of element
  function getValue() {
      if (isset($_FILES[$this->name]))
        $value = $_FILES[$this->name];
      else
        $value = $this->value;
      $ext = '';
      if (is_array($value)) {
        $ext = end(explode('.', $value['name']));
      } else {
        if ($ext == '' && isset($_REQUEST[ $this->name.'_old' ]))
          $ext = $_REQUEST[ $this->name.'_old' ];
        if ($ext == '')
          $ext = $value;
      }
      return strtolower($ext);
  }

  //get the label of the element
  function getCell() {
    global $synAbsolutePath;
    $ret          = '';
    $show         = null;
    $ext          = $this->translate( $this->value );
    $mat          = $this->compileQry( $this->translatePath($this->mat) );
    $filename     = $mat . $this->createFilename() . '.' . $ext;
    $file_exists  = is_file( $synAbsolutePath . $filename );
    $is_image     = $this->isImage( $filename );

    if ( $ext
      && $file_exists
      && $is_image
      ){
      $size = filesize($synAbsolutePath.$filename);
      $fsize = byteConvert($size);
      list($w, $h) = @getimagesize($synAbsolutePath.$filename);
      if ($size < 100000)
        $show = "style=\"background-image:url('{$filename}');\"";
      $ret .= "<a class=\"preview\" {$show} href=\"{$filename}\" data-ext=\"{$ext}\" data-size=\"{$fsize}\" data-width=\"{$w}\" data-height=\"{$h}px\">&nbsp;</a>";

    } elseif ( $ext
        && $file_exists
        && !$is_image
        ){
      $ret = "<span class=\"text-muted\"><a href=\"{$filename}\" target=\"_blank\" title=\"Download\">Document {$ext}</a></span>";

    } elseif ( $ext
        && !$file_exists
        ){
      $ret = "<span class=\"text-muted\">Error {$ext}</span>";

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
      if (getimagesize( $synAbsolutePath . $filename ) !== false)
        $ret = true;
    }
    return $ret;
  }

  //set the upload path of the element
  function setPath($path) {
    if (substr($path,-1) != '/')
      $path .= '/';
    //if (!file_exists($path)) echo "<div>Path $path not found</div>";
    $this->mat = $path;
    return true;
  }

  //translate path and insert dynamic content
  function translatePath($path) {
    global $synAdminPath;
    if (strpos($path, '§syntaxRelativePath§') !== false)
      $path = str_replace('§syntaxRelativePath§', $synAdminPath, $path);
    return $path;
  }

  //function for the auto-configuration
  function configuration( $i='', $k=99 ) {
    global
      $synAbsolutePath, $synElmName, $synElmType, $synElmLabel, $synElmSize, $synElmHelp,
      $synElmPath, $synChkKey, $synChkVisible, $synChkEditable, $synChkMultilang;

    $synHtml = new synHtml();

    //Calculate the correct path
    $syntaxPath   = str_replace( '\\', '/', realpath('../../../' ));
    $documentRoot = str_replace( '\\', '/', $synAbsolutePath );
    $pathinfo     = substr( $syntaxPath, strlen($documentRoot) );
    if ( !isset($synElmPath[$i])
      || $synElmPath[$i] == ''
       )
      $synElmPath[$i] = $pathinfo . '/public/mat';

    //parent::configuration();
    //$this->configuration[8]="Percorso: ".$synHtml->text(" name=\"synElmPath[$i]\" value=\"$synElmPath[$i]\"")."<br><span style='color: gray'>Insert directory path without DOCUMENT ROOT.<br />I.e. <strong>/mysite/syntax/public/templates/</strong> <br> Use <strong>�syntaxRelativePath�</strong><br />for dynamically insert Syntax Desktop relative path.</span>";
    $this->configuration[8] = "Percorso: " . $synHtml->text(" name=\"synElmPath[$i]\" value=\"$synElmPath[$i]\"") . "<br><span style='color: gray'>Insert directory path without DOCUMENT ROOT.<br />I.e. <strong>/public/mat/</strong></span>";

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


} //end of class inputfile

?>
