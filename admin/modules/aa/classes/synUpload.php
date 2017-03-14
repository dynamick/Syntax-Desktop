<?php

/* ------------------------------------------------ *
 * class synUpload                                  *
 * Handles image galleries: multiple upload, sort   *
 * and delete.                                      *
 * v1.0.0                                           *
 * ------------------------------------------------ */

class synUpload extends synElement {

  var $mat;
  var $pattern;

  //constructor(name, value, label, size, help, $mat)
  function __construct( $n = '', $v = '', $l = '', $s = 255, $h = '', $mat = '/mat/' ) {
    global $synPublicPath;

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
    $this->thumb   = "{$synPublicPath}/thumb.php?w=250&amp;h=250&amp;far=1&amp;src=%s"; // TODO: move this elsewere
  }


  //private function
  public function _html() {
    global ${$this->name}, $PHP_SELF, $db, $synAbsolutePath;

    if ( !isset($_SESSION) )
      session_start();

    if ( isset($_REQUEST['cmd']) )
      $cmd = $_REQUEST['cmd'];
    else
      $cmd = '';

    if ( $cmd == 'modifyrow' ) {
      $container = $this->container;
      $keyArr    = explode('=', str_replace("'", '', str_replace( '`', '', trim( urldecode($container->getKey()) ) ) ) );
      $app_title = $app_order = $app_table = $app_field = $app_linkfield = $preview_props = '';
      $thumb_arr = $thumb_cfg = $preview_arr = array();

      if ( isset($this->pattern) ) {
        $arr_tmp = explode( '|', $this->pattern );
        if ( is_array( $arr_tmp )
          && count( $arr_tmp ) == 5
          ) list($app_title, $app_order, $app_table, $app_field, $app_linkfield) = $arr_tmp;
        if ($app_table) {
          $qry = "SELECT * FROM `{$app_table}` WHERE `{$app_linkfield}` = '{$keyArr[1]}' ORDER BY `{$app_order}` ASC";
          $res = $db->Execute( $qry );
          $path = $this->mat . $keyArr[1] . DIRECTORY_SEPARATOR;
          while( $row = $res->fetchRow() ) {
            $filename =  $app_table . '_' . $app_field . '_id' . $row['id'] . '.' . $row[$app_field];
            $thumb_arr[] = sprintf( $this->thumb, $path . $filename );
            $size        = @filesize( $synAbsolutePath . $path . $filename );
            $thumb_cfg[] = array(
              'caption' => $row['title'],
              'size'    => $size,
              'width'   => '250px',
              'heigth'  => '250px',
              'key'     => $row['id'],
              'extra'   => array(
                'table' => $app_table,
                'field' => $app_field,
                'ext'   => $row[$app_field],
                'path'  => $path
              )
            );
          }
          $preview_props = 'initialPreview: ' . json_encode( $thumb_arr ) . ', '
                         . 'initialPreviewConfig: ' . json_encode( $thumb_cfg ) . ', '
                         . 'initialPreviewAsData: true, '
                         . 'initialPreviewFileType: "image", '
                         . PHP_EOL;
        }
      }
      $warning = '';
      $resize_props = '';
      if ( isset( $this->qry )
        && preg_match( '/(\d+)(?:[\s\D]+)(\d+)/', $this->qry, $match )
        ){
        $resize_props = 'resizeImage: true, '
                      . "maxImageWidth: {$match[1]}, "
                      . "maxImageHeight: {$match[2]}, "
                      . 'resizeImageQuality: 1.00, ' //. "resizePreference: 'width', "
                      . PHP_EOL;
      } else {
        $warning  = '<div class="alert alert-warning">'
                  . '<h4>Attenzione: dimensioni massime non impostate</h4>'
                  . 'Potresti avvertire dei rallentamenti, inoltre il server potrebbe rifiutare file troppo grandi.'
                  . '</div>';
      }

      $ret      = "<input id=\"{$this->name}\" name=\"{$this->name}[]\" type=\"file\" multiple=true>" . $warning;
      $nocache  = rand();
      $lang     = $_SESSION['aa_CurrentLangInitial'];
      //maxFileSize: '{$max_size}', $max_size = ini_get('upload_max_filesize');

      $script   = <<<EOC
        function thumbToOriginal( e, params ) {
          var _filePreviewImage = params.modal.find( '.file-preview-image' ),
              src = _filePreviewImage.prop( 'src' );
          if ( !_filePreviewImage.length )
            return;
          src = src.replace( '/public/thumb.php?w=250&h=250&far=1&src=', '' );
          _filePreviewImage.prop( 'src', src );
        };      

        var extra = {
          key         : '{$keyArr[1]}',
          description : '{$app_title}',
          order       : '{$app_order}',
          table       : '{$app_table}',
          field       : '{$app_field}',
          linkfield   : '{$app_linkfield}',
          path        : '{$this->mat}'
        };

        $("#{$this->name}").fileinput({
          theme: 'fa',
          language: '{$lang}',
          allowedFileExtensions: ['jpg', 'jpeg', 'gif', 'png'],
          {$resize_props}
          {$preview_props}
          overwriteInitial: false,
          uploadUrl: "ihtml/upload2.php?r={$nocache}",
          deleteUrl: "ihtml/delete_photo.php",
          uploadAsync: true,
          uploadExtraData: function() {
            return extra;
          }
        }).on( 'filesorted', function(e, params) {
          var stack = new Array();
          for( var i in params.stack) {
            stack.push( params.stack[i].key );
          }  
          $.post('ihtml/sort_photos.php', { data: extra, stack: stack } )
          .done( function(data) {
            sendNotify({ type:3, message: 'Foto riordinate' });
          }).fail( function(e){
            sendNotify({ type:1, message: e });            
          });
        }).on( 'filedeleted', function(e, key) {
          sendNotify({ type:3, message: 'Foto ' + key +' eliminata.' });
        }).on( 'fileuploaded', function(e, data) {
          var uploaded = data.response.initialPreviewConfig[0];
          sendNotify({ type:3, message: uploaded.caption + ' caricato sul server.' });
        }).on( 'filezoomshow filezoomnext filezoomprev', thumbToOriginal );
EOC;
      // bugged, as of 2017-03-13 
      // http://plugins.krajee.com/file-input#event-fileimageresized
      /*.on('fileimagesresized', function(event) {
        sendNotify({ type:2, message: 'Le foto più grandi di {$this->qry} sono state ridimensionate.' });
      });*/
      enqueue_js($script);

    } else {
      $ret  = '<div class="alert alert-warning">'
            .   '<h4>This field is disabled in insert mode</h4>'
            .   'Save and modify this entry to upload files.'
            . '</div>';
    }
    return  $ret;
  }

  //create the file name
  function createFilename( $withLang = true ) {
    //global $aa_CurrentLang;
    if ( !isset($_SESSION) )
      session_start();
    $aa_CurrentLang = $_SESSION['aa_CurrentLang'];

    $container  = $this->container;
    $key        = $container->getKey();
    $table      = $container->table;
    $multilang  = ($this->multilang == 1 && $withLang) ? "_" . $this->getLang() : '';

    $filename   = "{$table}_{$this->name}_" 
                . str_replace( array("'", '`', '='), '', trim( urldecode($key) ) ) 
                . $multilang;

    return $filename;
  }

  //upload the document...
  function uploadDocument() {
    global $synAbsolutePath, ${$this->name}, ${$this->name.'_name'};
    $documentRoot       = $synAbsolutePath . DIRECTORY_SEPARATOR;
    $mat                = $this->translatePath( $this->mat );
    $ext                = $this->translate( substr( ${$this->name . '_name'}, -3));
    $filename           = $this->createFilename() . '.' . $ext;
    $file               = ${$this->name};
    $original_filename  = ${$this->name . '_name'};
    if ( $file != 'none'
      && $original_filename != ''
      && $file != ''
      ){
      if ( !file_exists( $documentRoot . $mat ) )
        mkdir( $documentRoot . $mat );
      move_uploaded_file( $file, $documentRoot . $mat . $filename );
      @chmod( $documentRoot . $mat . $filename, 0777 );
    }

    $save_path = '';
    $file = '';
    if ( isset($_FILES['userfile']) ) {
      $file = $_FILES['userfile'];
      $k = count($file['name']);
      for( $i = 0; $i < $k; $i++ ) {
        if ( isset($save_path)
          && $save_path != ''
          ){
          $name = explode('/', $file['name'][$i]);
          move_uploaded_file( $file['tmp_name'][$i], $save_path . $name[count($name)-1] );
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
      $synAbsolutePath, $synChkEditable, $synChkKey, $synChkMultilang, $synChkVisible, 
      $synElmHelp, $synElmLabel, $synElmName, $synElmPath, $synElmQry, $synElmSize, $synElmType, $synElmValue;

    $synHtml = new synHtml();

    //Calculate the correct path
    $syntaxPath   = str_replace( "\\", '/', realpath( '../../../' ) );
    $documentRoot = str_replace( "\\", '/', $synAbsolutePath );
    $pathinfo     = substr( $syntaxPath, strlen( $documentRoot ) );

    if ( !isset($synElmPath[$i])
      || $synElmPath[$i] == ''
      ) $synElmPath[$i] = $pathinfo . '/mat';

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

    $value = (isset($synElmQry[$i])) ? htmlentities($synElmQry[$i]) : '';

    $options = array(
      'null'      => 'No resize',
      '1024×768'  => '1024×768 (format 1:33, 0.78 Megapixel)',
      '1280×1024' => '1280×1024 (format 1:25, 1.31 Megapixel)',
      '1440×900'  => '1440×900 (format 1:60, 1.6 Megapixel)',
      '1680×1200' => '1680×1200 (format 1:40, 2.0 Megapixel)',
      '1920×1200' => '1920×1200 (format 1:60, 2.2 Megapixel)',
      '2560×1440' => '2560×1440 (format 1:78, 3.6 Megapixel)',
    );
    if ( isset($synElmQry[$i]) || empty($synElmQry[$i]) )
      $synElmQry[$i] = '1280x1024';
    $this->configuration[10]  = 'Resize: ' . $synHtml->select( "name=\"synElmQry[{$i}]\"", $options, $synElmQry[$i], FALSE ) 
                              . '<span class="help-block">Sets the maximum dimensions for uploaded images.</span>';

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
