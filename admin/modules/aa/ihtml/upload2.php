<?php
error_reporting( E_ALL);// & ~(E_NOTICE | E_DEPRECATED | E_WARNING)) ;
ini_set( 'display_errors', 'off' );

$xhr = isset($_SERVER['HTTP_X_REQUESTED_WITH']) and (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
header('Content-Type: ' . ($xhr ? 'application/json' : 'text/plain'));

include_once ('../../../config/cfg.php');

if (!isset($_SESSION))
  session_start();

//echo '<pre>', print_r($_FILES), '</pre>';
//echo '<pre>', print_r($_POST), '</pre>';
$uploaded   = array();
$errors     = array();
$errorkeys  = array();

// get the files posted
//$images = $_FILES['images'];

$path         = isset($_POST['path'])
                ? sanitize( $_POST['path'] ) //trim(addslashes($_POST['path']))
                : $synPublicPath.$mat;
$key          = isset($_POST['key'])
                ? sanitize( $_POST['key'] ) //intval($_POST['key'])
                : null;
$table        = isset($_POST['table'])
                ? sanitize( $_POST['table'] ) //trim(addslashes($_POST['table']))
                : 'photos';

// set required column names
$file_field   = isset($_POST['field'])
                ? sanitize( $_POST['field'] ) //trim(addslashes($_POST['field']))
                : 'photo';
$description  = isset($_POST['description'])
                ? sanitize( $_POST['description'] ) //trim(addslashes($_POST['description']))
                : 'title';
$foreign_key  = isset($_POST['linkfield'])
                ? sanitize( $_POST['linkfield'] ) //trim(addslashes($_POST['linkfield']))
                : 'album';
$order_field  = isset($_POST['order'])
                ? sanitize( $_POST['order'] ) //trim(addslashes($_POST['order']))
                : 'ordine';

$date_field   = 'date';
$user_field   = 'autore';
$format_field = 'format';

if ( empty($key) ) :
  $errors[] = 'Key not received!';
  break;

else :
  $user       = $_SESSION['synUser'];
  $order      = getMaxOrder($table, $order_field, $foreign_key, $key);
  // TODO: make sub-directory storage optional
  $targetDir  = $synAbsolutePath . $path . $key . '/';

  if ( !is_dir($targetDir) ) {
    try {
      mkdir($targetDir);
    } catch (Exception $e) {
      $errors[] = 'Cannot create directory: ' . $targetDir;
      break;
    }
  } elseif ( !is_writable($targetDir) ) {
    $errors[] = 'Directory ' . $targetDir . ' is not writable!';
    break;
  }

  // a flag to see if everything is ok
  $success = null;

  // file paths to store
  $paths = array();
  $output = array();

  if ( empty($_FILES) ) {
    $errors[] = 'No files sent for upload.';
    break;
  }

  foreach ( $_FILES AS $fileArray ) :
    // get file names
    $filenames = $fileArray['name'];

    // loop and process files
    for ( $i = 0; $i < count($filenames); $i++ ) :
      $fileName = $fileArray['name'][$i];
      $fileName = preg_replace('/[^\w\._]+/', '_', $fileName); // Clean the fileName for security reasons
      $target   = $targetDir . $fileName;
      
      // see http://php.net/manual/en/features.file-upload.errors.php
      switch ( $fileArray['error'][$i] ) {
        case UPLOAD_ERR_OK: // 0
          // everything ok
          break;
        case UPLOAD_ERR_INI_SIZE: // 1
        case UPLOAD_ERR_FORM_SIZE: // 2
          $errors[] = 'Exceeded filesize limit.';
          break;
        case UPLOAD_ERR_NO_FILE: // 4
          $errors[] = 'No file sent.';
          break;
        default:
          $errors[] = 'Unknown errors.';
      }

      if ( $fileArray['error'][$i] == 0 ) :
        $success = move_uploaded_file( $fileArray['tmp_name'][$i], $target );
        // check and process based on successful status
        if ( $success === true ) {
            $paths[]      = $target;
            $info         = pathinfo( $target );
            $fileName     = $info['filename'];
            $ext          = strtolower( $info['extension'] );
            $order        += 10;
            list($w, $h)  = getimagesize( $target );
            $format       = ($h > $w) ? 'portrait' : 'landscape';
            $data         = array(
              $description  => $fileName,
              $file_field   => $ext,
              $foreign_key  => $key,
              $order_field  => $order,
              $date_field   => date('Y-m-d H:i:s'),
              $user_field   => $user,
              $format_field => $format
            );
            $row_id = saveData( $table, $data );

            if ($row_id) {
              $newFileName = strtolower( "{$table}_{$file_field}_id{$row_id}.{$ext}" );
              rename( $target, $targetDir . DIRECTORY_SEPARATOR . $newFileName);
              $uploaded[] = $newFileName;
            } else {
              $errors[] =  'Error while saving data to DB';
            }

        } else { //if ($success === false) {
          if ( $error = error_get_last() ) // due to max_input_vars or similar...
            $errors[] = $error['message'] ;
          else
            $errors[] = 'File ' . $target . ' could not be uploaded.';

          // delete any uploaded files
          foreach ( $paths as $file )
            unlink( $file );
        }
      endif; // if $fileArray['error'][$i] == 1

      if ( !empty($errors) )
        $errorkeys[] = $i;        
    endfor; // $i < count($filenames)
  
  endforeach; // $_FILES AS $fileArray

endif; // empty($key)

if ( empty($uploaded) && empty($errors) ) {
  $errors[] = 'Unknown error. No files were processed.';
}

$output = array_filter( array( 'uploaded' => $uploaded, 'error' => $errors, 'errorkeys' => $errorkeys ) );

// return a json encoded response for plugin to process successfully
echo json_encode( $output );


// ========================================================================= //


function saveData( $table, $data ){
  global $db;
  $id       = FALSE;
  $fields   = array();
  $params   = array();
  $columns  = array_keys( $db->MetaColumns( $table ) );

  foreach( $data AS $column => $value ){
    if ( in_array( strtoupper($column), $columns) ) {
      $fields[] = $column;
      $params[] = '?';
    } else
      unset( $data[$column] );
  }

  if ( !empty($fields) 
    && !empty($params) 
    ){
    $qry = 'INSERT INTO ' . $table . ' (`' . implode( '`,`', $fields ) . '`) VALUES (' . implode( ', ', $params ) . ')';
    if ( $res = $db->Execute( $qry, array_values($data) ) )
      $id = $db->Insert_ID();
  }
  return $id;
}


function getMaxOrder( $table, $order_column, $foreign_key, $key_value ) {
  global $db;
  $order = 0;
  $sql = "SELECT MAX(`{$order_column}`) AS max_order FROM `{$table}` WHERE `{$foreign_key}` = '{$key_value}'";
  $res = $db->execute( $sql );
  if ( $row = $res->fetchrow() )
    $order = $row['max_order'];
  return $order;
}

function sanitize($var){
  $var = filter_var($var, FILTER_SANITIZE_STRING);
  return trim($var);
}
