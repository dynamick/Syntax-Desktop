<?php
// VERSIONE BETA
// Marco 2012.09.18

class synUpload extends synElement {

  var $mat;
  var $pattern;

  //constructor(name, value, label, size, help, $mat)
  function __construct($n='', $v='', $l='', $s=255, $h='', $mat='/mat/') {
    if ($n=='')
      $n = 'text'.date("his");

    if ($l=='')
      $l = ucfirst($n);

    $this->type    = 'file';
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
        if ( is_array( $arr_tmp )
          && count($arr_tmp) == 5
          ) list($app_title, $app_order, $app_table, $app_field, $app_linkfield) = $arr_tmp;
      }


      $ret = <<<EOC

      <div id="plup_{$this->name}">
        <p>You browser doesn't have Flash, Silverlight, or HTML5 support.</p>
      </div>

      <script type="text/javascript" src="plupload/plupload.full.js"></script>
      <script type="text/javascript" src="plupload/jquery.plupload.queue.js"></script>
      <script type="text/javascript" src="plupload/it.js"></script>
      <script type="text/javascript">
      $(function() {
        $("#plup_{$this->name}").pluploadQueue({
          // General settings
          runtimes        : 'flash,silverlight,html5',
          url             : 'ihtml/plupload.php',
          max_file_size   : '10mb',
          chunk_size      : '1mb',
          multiple_queues : true,

          // Resize images on clientside if we can
          resize : {
            width         : 1280,
            height        : 1280,
            quality       : 90
          },

          // Specify what files to browse for
          filters : [{
            title         : "Image files",
            extensions    : "jpg,jpeg,gif,png"
          }],

          // Flash settings
          flash_swf_url   : 'plupload/plupload.flash.swf',

          // Silverlight settings
      silverlight_xap_url : 'plupload/plupload.silverlight.xap',

          multipart_params: {
            'key'         : '{$keyArr[1]}',
            'description' : '{$app_title}',
            'order'       : '{$app_order}',
            'table'       : '{$app_table}',
            'field'       : '{$app_field}',
            'linkfield'   : '{$app_linkfield}',
            'path'        : '{$this->mat}'
          }

          // eventi: vedi http://www.plupload.com/example_events.php
        });
      });
      </script>

EOC;

// versione per debug
/*
      $ret = <<<EOC

      <div id="plup_{$this->name}">
        <p>You browser doesn't have Flash, Silverlight, or HTML5 support.</p>
      </div>

      <script type="text/javascript" src="plupload/plupload.full.js"></script>
      <script type="text/javascript" src="plupload/jquery.plupload.queue.js"></script>
      <script type="text/javascript" src="plupload/it.js"></script>
      <script type="text/javascript">
      $(function() {
        $("#plup_{$this->name}").pluploadQueue({
          // General settings
          runtimes        : 'flash,silverlight,html5',
          url             : 'ihtml/plupload.php',
          max_file_size   : '10mb',
          chunk_size      : '1mb',
          multiple_queues : true,

          // Resize images on clientside if we can
          resize : {
            width         : 1280,
            height        : 1280,
            quality       : 90
          },

          // Specify what files to browse for
          filters : [{
            title         : "Image files",
            extensions    : "jpg,jpeg,gif,png"
          }],

          // Flash settings
          flash_swf_url   : 'plupload/plupload.flash.swf',

          // Silverlight settings
      silverlight_xap_url : 'plupload/plupload.silverlight.xap',

          multipart_params: {
            'key'         : '{$keyArr[1]}',
            'description' : '{$app_title}',
            'order'       : '{$app_order}',
            'table'       : '{$app_table}',
            'field'       : '{$app_field}',
            'linkfield'   : '{$app_linkfield}',
            'path'        : '{$this->mat}'
          }

          // eventi: vedi http://www.plupload.com/example_events.php
		, preinit : {
			Init: function(up, info) {
				log('[Init]', 'Info:', info, 'Features:', up.features);
			},

			UploadFile: function(up, file) {
				log('[UploadFile]', file);

				// You can override settings before the file is uploaded
				// up.settings.url = 'upload.php?id=' + file.id;
				// up.settings.multipart_params = {param1 : 'value1', param2 : 'value2'};
			}
		},

		// Post init events, bound after the internal events
		init : {
			Refresh: function(up) {
				// Called when upload shim is moved
				log('[Refresh]');
			},

			StateChanged: function(up) {
				// Called when the state of the queue is changed
				log('[StateChanged]', up.state == plupload.STARTED ? "STARTED" : "STOPPED");
			},

			QueueChanged: function(up) {
				// Called when the files in queue are changed by adding/removing files
				log('[QueueChanged]');
			},

			UploadProgress: function(up, file) {
				// Called while a file is being uploaded
				log('[UploadProgress]', 'File:', file, "Total:", up.total);
			},

			FilesAdded: function(up, files) {
				// Callced when files are added to queue
				log('[FilesAdded]');

				plupload.each(files, function(file) {
					log('  File:', file);
				});
			},

			FilesRemoved: function(up, files) {
				// Called when files where removed from queue
				log('[FilesRemoved]');

				plupload.each(files, function(file) {
					log('  File:', file);
				});
			},

			FileUploaded: function(up, file, info) {
				// Called when a file has finished uploading
				log('[FileUploaded] File:', file, "Info:", info);
			},

			ChunkUploaded: function(up, file, info) {
				// Called when a file chunk has finished uploading
				log('[ChunkUploaded] File:', file, "Info:", info);
			},

			Error: function(up, args) {
				// Called when a error has occured
				log('[error] ', args);
			}
		}





        });
      });


	function log() {
		var str = "";

		plupload.each(arguments, function(arg) {
			var row = "";

			if (typeof(arg) != "string") {
				plupload.each(arg, function(value, key) {
					// Convert items in File objects to human readable form
					if (arg instanceof plupload.File) {
						// Convert status to human readable
						switch (value) {
							case plupload.QUEUED:
								value = 'QUEUED';
								break;

							case plupload.UPLOADING:
								value = 'UPLOADING';
								break;

							case plupload.FAILED:
								value = 'FAILED';
								break;

							case plupload.DONE:
								value = 'DONE';
								break;
						}
					}

					if (typeof(value) != "function") {
						row += (row ? ', ' : '') + key + '=' + value;
					}
				});

				str += row + " ";
			} else {
				str += arg + " ";
			}
		});

		$('#log').append(str + "\\n");
	}
      </script>
    <textarea wrap="off" spellcheck="false" style="width: 100%; height: 150px; font-size: 11px" id="log"></textarea>
EOC;
*/
    } else {
      $ret = "This field is disabled in insert mode. Save and modify this entry to upload files.";
    }
    return  $ret;
  }

  //create the file name
  function createFilename($withLang=true) {
    global $aa_CurrentLang;

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
    $documentRoot = $synAbsolutePath.'/';
    $mat = $this->translatePath($this->mat);
    $ext = $this->translate(substr(${$this->name.'_name'}, -3));
    $filename = $this->createFilename().'.'.$ext;
    $file = ${$this->name};
    $original_filename = ${$this->name.'_name'};
    if ( $file != 'none'
      && $original_filename != ''
      && $file != ''
      ){
      if (!file_exists($documentRoot.$mat))
        mkdir($documentRoot.$mat);
      move_uploaded_file($file,$documentRoot.$mat.$filename);
      @chmod($documentRoot.$mat.$filename, 0777);
    }

    $save_path = '';
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

  //normally an element hasn't a document to delete (only synInputfile)
  function deleteDocument() {
  	global $synAbsolutePath, ${$this->name}, ${$this->name."_name"}, ${$this->name."_old"};
    include_once("../../includes/php/utility.php");

    $ext = $this->translate($this->getValue());
    $mat = $this->translatePath($this->mat);
    $filename = $this->createFilename(false);
    $documentRoot = $synAbsolutePath.'/';
    $fileToBeRemoved = $documentRoot.$mat.$filename.'*';
    foreach (glob($fileToBeRemoved) as $filename){
      unlink($filename);
    }
  }

  //get the values of element
  function getValue() {
    global ${$this->name}, ${$this->name."_name"}, ${$this->name."_old"};
    $ext = substr(${$this->name."_name"},-3);
    if ($ext == '')
      $ext = ${$this->name."_old"};
    if ($ext == '')
      $ext = $this->value;

    return $ext;
  }

  //get the label of the element
  function getCell() {
  	global $synAbsolutePath;
    $ext = $this->translate($this->value);
    $mat = $this->translatePath($this->mat);
    $filename = $mat.$this->createFilename().'.'.$ext;
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
  function configuration($i='', $k=99) {
  	global
      $synAbsolutePath, $synElmLabel, $synElmName, $synElmSize, $synElmPath, $synChkVisible,
      $synChkMultilang, $synElmValue, $synElmType, $synElmHelp, $synChkEditable, $synChkKey;


    $synHtml = new synHtml();

    //Calculate the correct path
    $syntaxPath = str_replace("\\", "/", realpath('../../../'));
    $documentRoot = str_replace("\\", "/", $synAbsolutePath);
    $pathinfo = substr($syntaxPath, strlen($documentRoot));

    if ( !isset($synElmPath[$i])
      || $synElmPath[$i] == ''
      )
      $synElmPath[$i] = $pathinfo.'/mat';

    if ( !isset($synElmValue[$i])
      || $synElmValue[$i] == ''
      ) $synElmValue[$i] = 'title|ordine|photos|photo|album';

    //parent::configuration();
    $this->configuration[8] = "Path: ".$synHtml->text(" name=\"synElmPath[$i]\" value=\"$synElmPath[$i]\"")."<br><span style='color: gray'>Insert directory path without DOCUMENT ROOT.<br />I.e. <strong>/mysite/syntax/public/templates/</strong> <br> Use <strong>§syntaxRelativePath§</strong><br />for dynamically insert Syntax Desktop relative path.</span>";
    $this->configuration[9] = "Join: ".$synHtml->text(" name=\"synElmValue[$i]\" value=\"$synElmValue[$i]\"")."<br><span style='color: gray'>Usage: title field|order field|table name|field|foreign key field</span>";

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


} //end of class inputfile

?>
