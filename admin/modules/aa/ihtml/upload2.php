<?php
  error_reporting(E_ALL & ~(E_NOTICE | E_DEPRECATED | E_WARNING));
  ini_set('display_errors', 1);

include_once ('../../../config/cfg.php');

if (!isset($_SESSION))
  session_start();

//echo '<pre>', print_r($_FILES), '</pre>';
//echo '<pre>', print_r($_POST), '</pre>';


// get the files posted
//$images = $_FILES['images'];

$path         = isset($_POST['path'])
                ? trim(addslashes($_POST['path']))
                : $synPublicPath.$mat;
$key          = isset($_POST['key'])
                ? intval($_POST['key'])
                : null;
$table        = isset($_POST['table'])
                ? trim(addslashes($_POST['table']))
                : 'photos';

// set required column names
$file_field   = isset($_POST['field'])
                ? trim(addslashes($_POST['field']))
                : 'photo';
$description  = isset($_POST['description'])
                ? trim(addslashes($_POST['description']))
                : 'title';
$foreign_key  = isset($_POST['linkfield'])
                ? trim(addslashes($_POST['linkfield']))
                : 'album';
$order_field  = isset($_POST['order'])
                ? trim(addslashes($_POST['order']))
                : 'ordine';

$date_field   = 'date';
$user_field   = 'autore';
$format_field = 'format';

if (empty($key)) {
  echo json_encode( array('error' => 'Key not received!') );
  // or you can throw an exception
  return; // terminate
}

$user       = $_SESSION['synUser'];
$order      = getMaxOrder($table, $order_field, $foreign_key, $key);
// TODO: make sub-directory storage optional
$targetDir  = $synAbsolutePath . $path . $key . '/';

if (!is_dir($targetDir)) {
  try {
    mkdir($targetDir);
  } catch (Exception $e) {
    echo json_encode( array('error' => 'Cannot create directory: '.$targetDir) );
    return; // terminate
  }
} elseif (!is_writable($targetDir)) {
  echo json_encode( array('error' => 'Directory '.$targetDir.' is not writable!') );
  return; // terminate
}

// a flag to see if everything is ok
$success = null;

// file paths to store
$paths = array();
$output = array();

if (empty($_FILES)) {
  echo json_encode( array('error' => 'No files sent for upload.') );
  // or you can throw an exception
  return; // terminate
}

foreach ($_FILES as $fileArray) {
  // get file names
  $filenames = $fileArray['name'];

  // loop and process files
  for ( $i = 0; $i < count($filenames); $i++ ){
    $fileName = $fileArray['name'][$i];
    // Clean the fileName for security reasons
    $fileName = preg_replace('/[^\w\._]+/', '_', $fileName);
    $target = $targetDir.$fileName;

    if (move_uploaded_file( $fileArray['tmp_name'][$i], $target )) {
      $success = true;
      $paths[] = $target;
    } else {
      $success = false;
      break;
    }

    // check and process based on successful status
    if ( $success === true ) {
        $info        = pathinfo( $target );
        $fileName    = $info['filename'];
        $ext         = strtolower( $info['extension'] );
        $order      += 10;
        list($w, $h) = getimagesize( $target );
        $format      = ($h > $w) ? 'portrait' : 'landscape';

        $data        = array(
          $description => $fileName,
          $file_field => $ext,
          $foreign_key => $key,
          $order_field => $order,
          $date_field => date('Y-m-d H:i:s'),
          $user_field => $user,
          $format_field => $format
        );
        $row_id = saveData($table, $data);

        if ($row_id) {
          $newFileName = strtolower( "{$table}_{$file_field}_id{$row_id}.{$ext}" );
          rename( $target, $targetDir. DIRECTORY_SEPARATOR .$newFileName);
          $output = array( 'uploaded' => $newFileName );
        } else {
          $output = array( 'error' => 'Error while saving data to DB' );
        }

    } elseif ($success === false) {
        $output = array( 'error' => 'Error while uploading files. Contact the system administrator' );
        // delete any uploaded files
        foreach ( $paths as $file ) {
          unlink( $file );
        }
    } else {
      $output = array( 'error' => 'No files were processed.' );
    }
  }
}
// return a json encoded response for plugin to process successfully
echo json_encode( $output );


function saveData($table, $data){
  global $db;
  $id = false;
  $fields = array();
  $values = array();
  $columns = array_keys($db->MetaColumns( $table ));

  foreach( $data as $column => $value ){
    if (in_array(strtoupper($column), $columns)) {
      $fields[] = $column;
      $values[] = $value;
    }
  }

  if (!empty($fields) && !empty($values)) {
    $field_str = implode("`,`", $fields);
    $value_str = implode("','", $values);

    $qry = "INSERT INTO {$table} (`{$field_str}`) VALUES ('{$value_str}')";
    if ($res = $db->Execute($qry)) {
      $id = $db->Insert_ID();
    }
  }
  return $id;
}


function getMaxOrder($table, $order_column, $foreign_key, $key_value) {
  global $db;
  $order = 0;
  $sql = "SELECT MAX(`{$order_column}`) AS max_order FROM `{$table}` WHERE `{$foreign_key}`='{$key_value}'";
  $res = $db->execute( $sql );
  if ($row = $res->fetchrow()) {
    $order = $row['max_order'];
  }
  return $order;
}

function sanitize($var){
  $var = filter_var($var, FILTER_SANITIZE_STRING);
  return trim($var);
}
