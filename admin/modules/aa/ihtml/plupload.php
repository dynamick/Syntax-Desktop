<?php
/**
 * plupload.php
 *
 * License: http://www.plupload.com/license
 * Contributing: http://www.plupload.com/contributing
 */

include_once ("../../../config/cfg.php");
global $synAbsolutePath, $synPublicPath, $mat;

// HTTP headers for no cache etc
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");


if(isset($_REQUEST['path'])) {
  $mat = trim(addslashes($_REQUEST['path']));
} else {
  $mat = $synPublicPath.$mat;
}

$save_path               = $synAbsolutePath.$mat.'/';
$key                     = intval($_REQUEST["key"]);
$description_column_name = (trim(addslashes($_REQUEST['description']))!='' ? trim(addslashes($_REQUEST['description'])) : 'title');
$order_column_name       = (trim(addslashes($_REQUEST['order']))!=''       ? trim(addslashes($_REQUEST['order']))       : 'ordine');
$table                   = (trim(addslashes($_REQUEST['table']))!=''       ? trim(addslashes($_REQUEST['table']))       : 'photos');
$field                   = (trim(addslashes($_REQUEST['field']))!=''       ? trim(addslashes($_REQUEST['field']))       : 'photo');
$linkfield               = (trim(addslashes($_REQUEST['linkfield']))!=''   ? trim(addslashes($_REQUEST['linkfield']))   : 'album');

$ordine = 0;
$qo = "SELECT MAX(`{$order_column_name}`) AS max_order FROM `{$table}` WHERE `{$linkfield}`='{$key}'";
$ro = $db->execute($qo);
if($ao = $ro->fetchrow()){
  $ordine = $ao['max_order'];
}



// Settings
//$targetDir = ini_get("upload_tmp_dir").DIRECTORY_SEPARATOR ."plupload"; // tmp_dir non è settata su kleis.eu???
$targetDir = $synAbsolutePath.$synPublicPath.'/mat';

$cleanupTargetDir = true; // Remove old files
$maxFileAge = 5 * 3600; // Temp file age in seconds

// 5 minutes execution time
@set_time_limit(5 * 60);

// Uncomment this one to fake upload time
// usleep(5000);

// Get parameters
$chunk = isset($_REQUEST["chunk"])   ? intval($_REQUEST["chunk"])  : 0;
$chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 0;

$fileName = isset($_REQUEST["name"]) ? $_REQUEST["name"]           : '';
// Clean the fileName for security reasons
$fileName = preg_replace('/[^\w\._]+/', '_', $fileName);


// Make sure the fileName is unique but only if chunking is disabled
if ( $chunks < 2
  && file_exists($targetDir.DIRECTORY_SEPARATOR .$fileName)
  ){
	$ext = strrpos($fileName, '.');
	$fileName_a = substr($fileName, 0, $ext);
	$fileName_b = substr($fileName, $ext);

	$count = 1;
	while (file_exists($targetDir.DIRECTORY_SEPARATOR .$fileName_a.'_'.$count.$fileName_b))
		$count++;

	$fileName = $fileName_a.'_'.$count.$fileName_b;
}


$filePath = $targetDir.DIRECTORY_SEPARATOR .$fileName;

// Create target dir
if (!file_exists($targetDir))
	@mkdir($targetDir);

// Remove old temp files
if ( $cleanupTargetDir
  && is_dir($targetDir)
  && ($dir = opendir($targetDir))
  ){
	while (($file = readdir($dir)) !== false) {
		$tmpfilePath = $targetDir.DIRECTORY_SEPARATOR . $file;

		// Remove temp file if it is older than the max age and is not the current file
		if ( preg_match('/\.part$/', $file)
      && (filemtime($tmpfilePath) < time() - $maxFileAge)
      && ($tmpfilePath != "{$filePath}.part")
      ){
			@unlink($tmpfilePath);
		}
	}

	closedir($dir);
} else
	die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "id"}');


// Look for the content type header
if (isset($_SERVER["HTTP_CONTENT_TYPE"]))
	$contentType = $_SERVER["HTTP_CONTENT_TYPE"];

if (isset($_SERVER["CONTENT_TYPE"]))
	$contentType = $_SERVER["CONTENT_TYPE"];

// Handle non multipart uploads older WebKit versions didn't support multipart in HTML5
if (strpos($contentType, "multipart") !== false) {
	if ( isset($_FILES['file']['tmp_name'])
    && is_uploaded_file($_FILES['file']['tmp_name'])
    ){
		// Open temp file
		$out = fopen("{$filePath}.part", $chunk == 0 ? "wb" : "ab");
		if ($out) {
			// Read binary input stream and append it to temp file
			$in = fopen($_FILES['file']['tmp_name'], "rb");

			if ($in) {
				while ($buff = fread($in, 4096))
					fwrite($out, $buff);
			} else
				die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');

			fclose($in);
			fclose($out);
			@unlink($_FILES['file']['tmp_name']);

		} else
			die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
	} else
		die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');

} else {
	// Open temp file
	$out = fopen("{$filePath}.part", $chunk == 0 ? "wb" : "ab");
	if ($out) {
		// Read binary input stream and append it to temp file
		$in = fopen("php://input", "rb");

		if ($in) {
			while ($buff = fread($in, 4096))
				fwrite($out, $buff);

      echo "{\"jsonrpc\" : \"2.0\", \"result\" : \"{$filePath}\", \"id\" : \"id\", \"message\": \"{$filePath} wrote to\"}";
		} else
			die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');

		fclose($in);
		fclose($out);
	} else
		die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
}


// Check if file has been uploaded
if ( !$chunks
  || $chunk == $chunks - 1
  ){

  $info        = pathinfo($filePath);
  $description = $info['filename'];
  $ext         = $info['extension'];
  $ordine     += 10;

  $qry = <<<EOQINS

    INSERT INTO {$table} (
      `{$description_column_name}`,
      `{$field}`,
      `{$linkfield}`,
      `{$order_column_name}`
    ) VALUES (
      '{$description}',
      '{$ext}',
      '{$key}',
      '{$ordine}'
    )

EOQINS;

  echo "{\"jsonrpc\" : \"2.0\", \"message\": \"{$qry}\"}";

  if($res = $db->Execute($qry)){
    $id = $db->Insert_ID();
    $newFilename = "{$table}_{$field}_id{$id}.{$ext}";
  }

  // add exif management???

	// Strip the temp .part suffix off
  //rename("{$filePath}.part", $filePath);
	//rename("{$filePath}.part", $targetDir.DIRECTORY_SEPARATOR .$newFilename);
  rename("{$filePath}.part", $save_path.$newFilename);


  die("{\"jsonrpc\" : \"2.0\", \"result\" : \"{$filePath}\", \"id\" : \"id\", \"message\": \"last part: {$filePath}.part, renamed to {$newFilename}\"}");
}


// Return JSON-RPC response
die('{"jsonrpc" : "2.0", "result" : null, "id" : "id", "cleanFileName" : "'.$fileName.'"}');


// EOF plupload.php