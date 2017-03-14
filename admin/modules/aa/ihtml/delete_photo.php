<?php
error_reporting( E_ALL & ~(E_NOTICE | E_DEPRECATED | E_WARNING) );
ini_set( 'display_errors', 'off' );

// start session & check authorization
if (!isset($_SESSION))
  session_start();
if ( !isset($_SESSION['synUser']) || empty($_SESSION['synUser'])) {
  header('HTTP/1.1 401 Unauthorized');
  die('Unauthorized');
}

$xhr = isset($_SERVER['HTTP_X_REQUESTED_WITH']) and (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
header('Content-Type: ' . ($xhr ? 'application/json' : 'text/plain'));

// load variables
include_once ('../../../config/cfg.php');


$key   = intval( $_POST['key'] );
$table = trim( filter_var( $_POST['table'], FILTER_SANITIZE_STRING ) );
$field = trim( filter_var( $_POST['field'], FILTER_SANITIZE_STRING ) );
$ext   = trim( filter_var( $_POST['ext'], FILTER_SANITIZE_STRING ) );
$path  = trim( filter_var( $_POST['path'], FILTER_SANITIZE_STRING ) );

if ( $key > 0 && isset($table) ) {
  $file = $synAbsolutePath . $path . "{$table}_{$field}_id{$key}.{$ext}";
  $qry = "DELETE FROM `{$table}` WHERE id = '{$key}'";
  try {
    $values = $db->execute( $qry );
    if ( is_file($file) ) {
      @unlink($file);
    } else {
      //$values = ' - ' . $file .' non trovato!';
    }
  } catch( Execption $e) {
    $values = $e;
  }
}

echo json_encode( $values );

// EOF
