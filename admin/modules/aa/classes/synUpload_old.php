<?php
// VERSIONE BETA
// Marco 2010.12.30

/*************************************
* class INPUTFILE                    *
* Create a input type="file" obj     *
**************************************/
class synUpload extends synElement {

  var $mat;
  var $pattern;

  //constructor(name, value, label, size, help, $mat)
  function __construct($n="", $v="", $l="", $s=255, $h="", $mat="/mat/") {
    if ($n=='')
      $n = 'text'.date("his");
    if ($l=='')
      $l = ucfirst($n);

    $this->type    = "file";
    $this->name    = $n;
    if ($v==null) {
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

    if(!isset($_SESSION))
      session_start();

    if (isset($_REQUEST['cmd']))
      $cmd = $_REQUEST['cmd'];
    else
      $cmd = '';

    if($cmd == 'modifyrow') {
      $container = $this->container;
      $keyArr = explode('=', str_replace("'", '', str_replace('`', '', trim(urldecode($container->getKey())))));
      $app_title = $app_order = $app_table = $app_field = $app_linkfield = '';
      if(isset($this->pattern)) {
        $arr_tmp = explode('|', $this->pattern);
        if(is_array($arr_tmp) and count($arr_tmp)==5)
          list($app_title, $app_order, $app_table, $app_field, $app_linkfield) = $arr_tmp;
      }

      $uploadPHP = 'ihtml/upload.php?key='.$keyArr[1]
                 . '&session_id='.session_id()
                 . '&description='.$app_title
                 . '&order='.$app_order
                 . '&table='.$app_table
                 . '&field='.$app_field
                 . '&linkfield='.$app_linkfield
                 . '&path='.$this->mat;

      $debug = false;
      if($debug==true){
        $debuglevel = '100';
        $logwindow = 'true';
      } else {
        $debuglevel = '0';
        $logwindow = 'false';
      }

      $ret = <<<EOC

        <object width="640" height="500" name="JUpload" codebase="http://java.sun.com/update/1.5.0/jinstall-1_5-windows-i586.cab#Version=5,0,0,3" classid="clsid:8AD9C840-044E-11D1-B3E9-00805F499D93">
          <param value="{$uploadPHP}"     name="postURL" />
          <param value="2147483648"       name="maxFileSize" />
          <param value="wjhk.jupload.jar" name="archive" />
          <param value="true"             name="sendMD5Sum" />
          <param value="{$debuglevel}"     name="debugLevel" />
          <param value="JUpload"          name="name" />
          <param value="wjhk.jupload2.JUploadApplet" name="code" />
          <param value="{$logwindow}"     name="showLogWindow" />
          <param value="true"             name="mayscript" />
          <param value="false"            name="scriptable" />
          <param value="SUCCESS"          name="stringUploadSuccess" />
          <param value="ERROR: (.*)"      name="stringUploadError" />
          <param value="500000"           name="maxChunkSize" />
          <param value="1024"             name="maxPicHeight" />
          <param value="1024"             name="maxPicWidth" />
          <param value="fileChooserIconSize" name="80" />
          <param value="lookAndFeel"      name="system" />
          <param value="showStatusBar"    name="true" />
          <param value="jpg/jpeg/gif/png" name="allowedFileExtensions" />
          <comment>
            <embed width="700" height="500" pluginspage="http://java.sun.com/products/plugin/index.html#download"
              postURL              ="{$uploadPHP}"
              maxfilesize          ="2147483648"
              archive              ="wjhk.jupload.jar"
              sendmd5sum           ="true"
              debugLevel           ="{$debuglevel}"
              name                 ="JUpload"
              code                 ="wjhk.jupload2.JUploadApplet"
              showlogwindow        ="{$logwindow}"
              mayscript            ="true"
              scriptable           ="false"
              stringuploadsuccess  ="SUCCESS"
              stringuploaderror    ="ERROR: (.*)"
              maxchunksize         ="500000"
              maxPicHeight         ="1024"
              maxPicWidth          ="1024"
              fileChooserIconSize  ="80"
              lookAndFeel          ="system"
              showStatusBar        ="true"
              allowedFileExtensions="jpg/jpeg/gif/png"
              uploadPolicy         ="PictureUploadPolicy"
              type                 ="application/x-java-applet;version=1.5" />
              <noembed>
                Java 1.5 or higher plugin required.
              </noembed>
          </comment>
        </object>
EOC;

    } else {
      $ret = "This field is disabled in insert mode. Save and modify this entry to upload files.";
    }
    return  $ret;
  }

  //create the file name
  function createFilename($withLang=true) {
    global $aa_CurrentLang;
    $container=$this->container;
    $key=$container->getKey();
    $table=$container->table;
    if ($this->multilang==1 and $withLang) $multilang="_".$this->getLang(); else $multilang="";
    $filename=$table."_".$this->name."_".str_replace("'","",str_replace("`","",str_replace("=","",trim(urldecode($key))))).$multilang;
    return $filename;
  }

  //upload the document...
  function uploadDocument() {
    global $synAbsolutePath, ${$this->name}, ${$this->name."_name"};    // ${$this->name} = ${"surname"} = $surname
    $documentRoot = $synAbsolutePath."/";
    $mat = $this->translatePath($this->mat);
    $ext = $this->translate(substr(${$this->name."_name"},-3));
    $filename = $this->createFilename().".$ext";
    $file = ${$this->name};
    $original_filename = ${$this->name."_name"};
    if ($file!="none" AND $original_filename!="" AND $file!="") {
      if (!file_exists($documentRoot.$mat)) mkdir($documentRoot.$mat);
      move_uploaded_file($file,$documentRoot.$mat.$filename);
      @chmod($documentRoot.$mat.$filename,0777);
    }

    $save_path = '';
    $file = $_FILES['userfile'];
    $k = count($file['name']);
    for($i=0 ; $i < $k ; $i++){
    	if(isset($save_path) && $save_path!=""){
    		$name = explode('/',$file['name'][$i]);
    		move_uploaded_file($file['tmp_name'][$i], $save_path . $name[count($name)-1]);
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
    $documentRoot = $synAbsolutePath."/";
    $fileToBeRemoved = $documentRoot.$mat.$filename."*";
    foreach (glob($fileToBeRemoved) as $filename)
      unlink($filename);
  }

  //get the values of element
  function getValue() {
    global ${$this->name}, ${$this->name."_name"}, ${$this->name."_old"};
    $ext = substr(${$this->name."_name"},-3);
    if ($ext=='')
      $ext = ${$this->name."_old"};
    if ($ext=='')
      $ext = $this->value;

    return $ext;
  }

  //get the label of the element
  function getCell() {
  	global $synAbsolutePath;
    $ext = $this->translate($this->value);
    $mat = $this->translatePath($this->mat);
    $filename = $mat.$this->createFilename().".".$ext;
    $file_exists = file_exists($synAbsolutePath.$filename);
    $isImg = $this->isImage($filename);
    if ($ext and $file_exists and $isImg)
      $ret = "<div style='overflow: hidden; height: 25px; display:inline;background: url($filename) no-repeat center;width: 100%' onMouseOver=\"openbox('$filename')\" onMouseOut=\"closebox()\"></div>";
    elseif ($ext and $file_exists and !$isImg)
      $ret="<span style='color: gray'>Document $ext</span>";
    elseif ($ext and !$file_exists)
      $ret="<span style='color: gray'>Error $ext</span>";
    else
      $ret="<span style='color: gray'>Empty</span>";

    return $ret;
    //die;
  }

  //check if it is an image or a document
  function isImage($filename) {
  	global $synAbsolutePath;
    if (file_exists($synAbsolutePath.$filename)) {
      if (getimagesize($synAbsolutePath.$filename)!==false)
        $ret=true;
      else
        $ret=false;
    } else {
      $ret=false;
    }
    return $ret;
  }

  //set the upload path of the element
  function setPath($path) {
    if (substr($path,-1)!="/") $path.="/";
    //if (!file_exists($path)) echo "<div>Path $path not found</div>";
    $this->mat = $path;
    return true;
  }

  //translate path and insert dynamic content
  function translatePath($path) {
    global $synAdminPath;
    if (strpos($path,"§syntaxRelativePath§")!==false) $path=str_replace("§syntaxRelativePath§",$synAdminPath,$path);
    return $path;
  }

  //function for the auto-configuration
  function configuration($i="",$k=99) {
  	global $synAbsolutePath;

    global $synElmName,$synElmType,$synElmLabel,$synElmSize,$synElmHelp;
    global $synElmPath,$synElmValue;
    $synHtml = new synHtml();

    //Calculate the correct path
    $syntaxPath=str_replace("\\","/",realpath("../../../"));
    $documentRoot=str_replace("\\","/",$synAbsolutePath);
    $pathinfo=substr($syntaxPath,strlen($documentRoot));
    if (!isset($synElmPath[$i]) or $synElmPath[$i]=="") $synElmPath[$i]=$pathinfo."/mat";
    if (!isset($synElmValue[$i]) or $synElmValue[$i]=="") $synElmValue[$i]="title|ordine|photos|photo|album";

    //parent::configuration();
    $this->configuration[8]="Path: ".$synHtml->text(" name=\"synElmPath[$i]\" value=\"$synElmPath[$i]\"")."<br><span style='color: gray'>Insert directory path without DOCUMENT ROOT.<br />I.e. <strong>/mysite/syntax/public/templates/</strong> <br> Use <strong>§syntaxRelativePath§</strong><br />for dynamically insert Syntax Desktop relative path.</span>";
    $this->configuration[9]="Join: ".$synHtml->text(" name=\"synElmValue[$i]\" value=\"$synElmValue[$i]\"")."<br><span style='color: gray'>Usage: title field|order field|table name|field|foreign key field</span>";

    //enable or disable the 3 check at the last configuration step
    global $synChkKey, $synChkVisible, $synChkEditable, $synChkMultilang;
    $synChkKey[$i]=0;
    $synChkVisible[$i]=1;
    $synChkEditable[$i]=0;
    $synChkMultilang[$i]=1;

    if ($k==99) return $this->configuration;
    else return $this->configuration[$k];
  }


} //end of class inputfile

?>
