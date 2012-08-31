<?php
session_id($_GET["session_id"]);
include_once ("../../../config/cfg.php");
global $synAbsolutePath, $synPublicPath, $mat;
if(isset($_GET['path'])) {
  $mat = trim(addslashes($_GET['path']));
} else {
  $mat = $synPublicPath.$mat;
}

$save_path = $synAbsolutePath.$mat.'/';
$bSuccess  = true;
$key       = intval($_GET["key"]);

$description_column_name = (trim(addslashes($_GET['description']))!='' ? trim(addslashes($_GET['description'])) : 'title');
$order_column_name       = (trim(addslashes($_GET['order']))!=''       ? trim(addslashes($_GET['order']))       : 'ordine');
$table                   = (trim(addslashes($_GET['table']))!=''       ? trim(addslashes($_GET['table']))       : 'photos');
$field                   = (trim(addslashes($_GET['field']))!=''       ? trim(addslashes($_GET['field']))       : 'photo');
$linkfield               = (trim(addslashes($_GET['linkfield']))!=''   ? trim(addslashes($_GET['linkfield']))   : 'album');

$qo = "SELECT MAX(`{$order_column_name}`) AS max_order FROM `{$table}` WHERE `{$linkfield}`='{$key}'";
$ro = $db->execute($qo);
if($ao = $ro->fetchrow()){
  $ordine = $ao['max_order'];
}

foreach ($_FILES as $file => $fileArray) {
	echo("File key: $file\n");

  $ordine += 10;

	$name = explode('/',$fileArray['name']);
  $filename = $name[count($name)-1];
  $description = substr($filename,0,-4);
  $ext = strtolower(substr(strrchr($filename, '.'), 1));
  $qry = <<<EOQINS

    INSERT INTO {$table} (
      `{$description_column_name}`,
      `{$field}`,
      `{$linkfield}`,
      `{$order_column_name}`
    ) VALUES (
      '{$description}',
      '{$ext}',
      {$key},
      '{$ordine}'
    )

EOQINS;

  echo $qry.PHP_EOL;

  if($res = $db->Execute($qry)){
    $id = $db->Insert_ID();
    $newFilename = "{$table}_{$field}_id{$id}.{$ext}";

    try {
      move_uploaded_file($fileArray['tmp_name'], $save_path.$newFilename);
      echo 'copied to '.$save_path.$newFilename.PHP_EOL;
      $bSuccess += true;

    } catch (Exception $e) {
      $error .= $e->getMessage();
      $bSuccess += false;
    }
  } else {
    $bSuccess += false;
  }
}

//Let's say to the applet that it's a success or a failure:
echo("\n");
if ($bSuccess) {
	echo "SUCCESS\n";
} else {
	echo "ERROR: $error\n";
}
//echo "<br>End of upload.php script\n";

?>
