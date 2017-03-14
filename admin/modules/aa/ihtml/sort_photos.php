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

$data   = array_filter( filter_var_array( $_POST['data'], FILTER_SANITIZE_STRING ), 'trim');
$stack  = array_filter( filter_var_array( $_POST['stack'], FILTER_SANITIZE_STRING ), 'trim');
$params = array();

foreach($stack AS $pos => $photo) {
  $order = ($pos+1)*10;
  $params[] = "({$photo}, {$order})";
}
if ( !empty($params) ){
  $values = implode( ', ', $params );
  $qry = "INSERT INTO `{$data['table']}` (id, {$data['order']}) VALUES {$values} ON DUPLICATE KEY UPDATE {$data['order']} = VALUES({$data['order']})";
  try {
    $db->execute( $qry );
  } catch( Execption $e) {
    $values = $e;
  }
}
echo json_encode( $values );

// EOF