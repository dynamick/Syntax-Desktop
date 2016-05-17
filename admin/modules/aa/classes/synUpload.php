<?php
// VERSIONE BETA
// Marco 2012.09.18

class synUpload extends synElement {

  var $mat;
  var $pattern;

  //constructor(name, value, label, size, help, $mat)
  function __construct( $n = '', $v = '', $l = '', $s = 255, $h = '', $mat = '/mat/' ) {
    if ( $n == '' )
      $n = 'text' . date("his");

    if ( $l == '' )
      $l = ucfirst($n);

    $this->type    = 'file';
    $this->name    = $n;
    if ( $v == null ) {
      global $$n;
      $this->value = $$n;
    } else {
      $this->value = $v;
    }
    $this->pattern = $this->value;
    $this->label   = $l;
    $this->size    = $s;
    $this->help    = $h;
    $this->db      = ' varchar(255) NOT NULL ';
    $this->mat     = $mat;
  }


  //private function
  public function _html() {
    global ${$this->name}, $PHP_SELF;

    if ( !isset($_SESSION) )
      session_start();

    if ( isset($_REQUEST['cmd']) )
      $cmd = $_REQUEST['cmd'];
    else
      $cmd = '';

    if ( $cmd == 'modifyrow' ) {
      $container = $this->container;
      $keyArr    = explode('=', str_replace("'", '', str_replace( '`', '', trim( urldecode($container->getKey()) ) ) ) );
      $app_title = $app_order = $app_table = $app_field = $app_linkfield = '';
      if ( isset($this->pattern) ) {
        $arr_tmp = explode( '|', $this->pattern );
        if ( is_array( $arr_tmp )
          && count( $arr_tmp ) == 5
          ) list($app_title, $app_order, $app_table, $app_field, $app_linkfield) = $arr_tmp;
      }
      $resize_props = '';
      if ( isset( $this->qry )
        && preg_match( '/(\d+)x(\d+)/', $this->qry, $match )
        ){
        //echo print_r($match); die();
        $resize_props = 'resizeImage: true, '
                      . "maxImageWidth: {$match[1]}, "
                      . "maxImageHeight: {$match[2]}, "
                      . 'resizeImageQuality: 1.00, ' //. "resizePreference: 'width', "
                      . PHP_EOL;
      }

      $ret = "<input id=\"{$this->name}\" name=\"{$this->name}[]\" type=\"file\" multiple=true>";
      $nocache = rand();
      $script = <<<EOC
        $("#{$this->name}").fileinput({
          allowedFileTypes: ['image'],
          {$resize_props}
          browseIcon: '<i class="fa fa-folder-open-o"></i> ',
          removeIcon: '<i class="fa fa-trash"></i> ',
          uploadIcon: '<i class="fa fa-upload"></i> ',
          previewFileIcon: '<i class="fa fa-file-o"></i> &nbsp;',
          msgValidationErrorIcon: '<i class="fa fa-exclamation-circle"></i> &nbsp;',
          uploadClass: 'btn btn-warning',
          layoutTemplates: {
            icon: '<span class="fa fa-file kv-caption-icon"></span> '
          },
          fileActionSettings: {
            removeIcon:       '<i class="fa fa-trash text-danger"></i>',
            uploadIcon:       '<i class="fa fa-upload text-info"></i>',
            indicatorNew:     '<i class="fa fa-hand-o-down text-warning"></i>',
            indicatorSuccess: '<i class="fa fa-check-circle file-icon-large text-success"></i>',
            indicatorError:   '<i class="fa fa-exclamation-circle text-danger"></i>',
            indicatorLoading: '<i class="fa fa-hand-o-up text-muted"></i>'
          },
          uploadUrl: "ihtml/upload2.php?r={$nocache}",
          uploadAsync: false,
          uploadExtraData: function() {
            return {
              key         : '{$keyArr[1]}',
              description : '{$app_title}',
              order       : '{$app_order}',
              table       : '{$app_table}',
              field       : '{$app_field}',
              linkfield   : '{$app_linkfield}',
              path        : '{$this->mat}'
            };
          }
        });
EOC;

      enqueue_js($script);

    } else {
      $ret = "This field is disabled in insert mode. Save and modify this entry to upload files.";
    }
    return  $ret;
  }

  //create the file name
  function createFilename($withLang=true) {
    //global $aa_CurrentLang;
    if ( !isset($_SESSION) )
      session_start();
    $aa_CurrentLang = $_SESSION['aa_CurrentLang'];

    $container = $this->container;
    $key       = $container->getKey();
    $table     = $container->table;
    $multilang = ($this->multilang==1 && $withLang) ? "_".$this->getLang() : '';

    //$filename = $table."_".$this->name."_".str_replace("'", '', str_replace('`', '', str_replace('=', '', trim(urldecode($key))))).$multilang;

    $filename = "{$table}_{$this->name}_".str_replace(array("'", '`', '='), '', trim(urldecode($key))).$multilang;

    return $filename;
  }

  //upload the document...
  function uploadDocument() {
    global $synAbsolutePath, ${$this->name}, ${$this->name.'_name'};
    $documentRoot = $synAbsolutePath . '/';
    $mat = $this->translatePath( $this->mat );
    $ext = $this->translate( substr( ${$this->name . '_name'}, -3));
    $filename = $this->createFilename() . '.' . $ext;
    $file = ${$this->name};
    $original_filename = ${$this->name . '_name'};
    if ( $file != 'none'
      && $original_filename != ''
      && $file != ''
      ){
      if ( !file_exists($documentRoot . $mat) )
        mkdir( $documentRoot . $mat );
      move_uploaded_file( $file, $documentRoot . $mat . $filename );
      @chmod( $documentRoot . $mat . $filename, 0777 );
    }

    $save_path = '';
    $file = '';
    if ( isset($_FILES['userfile']) ) {
      $file = $_FILES['userfile'];

      $k = count($file['name']);
      for($i=0 ; $i < $k ; $i++){
        if ( isset($save_path)
          && $save_path != ''
          ){
          $name = explode('/', $file['name'][$i]);
          move_uploaded_file($file['tmp_name'][$i], $save_path.$name[count($name)-1]);
        }
      }
    }
  }

  //normally an element hasn't a document to delete (only synInputfile)
  function deleteDocument() {
    global $synAbsolutePath, ${$this->name}, ${$this->name."_name"}, ${$this->name."_old"};
    include_once("../../includes/php/utility.php");

    $ext = $this->translate($this->getValue());
    $mat = $this->translatePath($this->mat);
    $filename = $this->createFilename(false);
    $documentRoot = $synAbsolutePath . '/';
    $fileToBeRemoved = $documentRoot . $mat . $filename . '*';
    foreach (glob($fileToBeRemoved) as $filename){
      unlink($filename);
    }
  }

  //get the values of element
  function getValue() {
    global ${$this->name}, ${$this->name . '_name'}, ${$this->name . '_old'};
    $ext = substr(${$this->name . '_name'},-3);
    if ($ext == '')
      $ext = ${$this->name . '_old'};
    if ($ext == '')
      $ext = $this->value;

    return $ext;
  }

  //get the label of the element
  function getCell() {
    global $synAbsolutePath;
    $ext = $this->translate($this->value);
    $mat = $this->translatePath($this->mat);
    $filename = $mat . $this->createFilename() . '.' . $ext;
    $file_exists = file_exists( $synAbsolutePath . $filename );
    $isImg = $this->isImage($filename);
    if ( $ext && $file_exists && $isImg )
      $ret = "<div style='overflow:hidden; height:25px; display:inline; background:url({$filename}) no-repeat center;'></div>";
    elseif ( $ext && $file_exists && !$isImg )
      $ret = "<span style='color: gray'>Document {$ext}</span>";
    elseif ( $ext && !$file_exists )
      $ret = "<span style='color: gray'>Error {$ext}</span>";
    else
      $ret = "<span style='color: gray'>Empty</span>";

    return $ret;
  }

  //check if it is an image or a document
  function isImage($filename) {
    global $synAbsolutePath;
    if ( file_exists( $synAbsolutePath . $filename ) ) {
      if ( getimagesize( $synAbsolutePath . $filename )!==false)
        $ret = true;
      else
        $ret = false;
    } else {
      $ret = false;
    }
    return $ret;
  }

  //set the upload path of the element
  function setPath($path) {
    if ( substr( $path, -1) != '/' )
      $path .= '/';
    //if (!file_exists($path)) echo "<div>Path $path not found</div>";
    $this->mat = $path;
    return true;
  }

  //translate path and insert dynamic content
  function translatePath($path) {
    global $synAdminPath;
    if ( strpos( $path, "§syntaxRelativePath§" ) !== false )
      $path = str_replace( "§syntaxRelativePath§", $synAdminPath, $path );
    return $path;
  }

  //function for the auto-configuration
  function configuration( $i = '', $k = 99 ) {
    global
      $synAbsolutePath, $synElmLabel, $synElmName, $synElmSize, $synElmPath, $synChkVisible,
      $synChkMultilang, $synElmValue, $synElmType, $synElmHelp, $synChkEditable, $synChkKey;

    $synHtml = new synHtml();

    //Calculate the correct path
    $syntaxPath   = str_replace( "\\", '/', realpath( '../../../' ) );
    $documentRoot = str_replace( "\\", '/', $synAbsolutePath );
    $pathinfo     = substr( $syntaxPath, strlen( $documentRoot ) );

    if ( !isset($synElmPath[$i])
      || $synElmPath[$i] == ''
      )
      $synElmPath[$i] = $pathinfo . '/mat';

    if ( !isset($synElmValue[$i] )
      || $synElmValue[$i] == ''
      ) $synElmValue[$i] = 'title|ordine|photos|photo|album';

    //parent::configuration();
    $this->configuration[8] = "Path: "
                            . $synHtml->text(" name=\"synElmPath[{$i}]\" value=\"{$synElmPath[$i]}\"")
                            . '<span class="help-block">Insert directory path without DOCUMENT ROOT.<br />'
                            . 'I.e. <strong>/mysite/syntax/public/templates/</strong><br>'
                            . 'Use <strong>§syntaxRelativePath§</strong><br />'
                            . 'to insert dynamically Syntax Desktop\'s relative path.</span>';
    $this->configuration[9] = "Join: "
                            . $synHtml->text(" name=\"synElmValue[{$i}]\" value=\"{$synElmValue[$i]}\"")
                            . '<span class="help-block">Usage: title field|order field|table name|field|foreign key field</span>';

    //enable or disable the 3 check at the last configuration step
    $synChkKey[$i]       = 0;
    $synChkVisible[$i]   = 1;
    $synChkEditable[$i]  = 0;
    $synChkMultilang[$i] = 1;

    if ($k==99)
      return $this->configuration;
    else
      return $this->configuration[$k];
  }
}
//end of class inputfile
