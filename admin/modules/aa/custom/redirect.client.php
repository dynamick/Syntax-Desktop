<?php
ini_set('display_errors', 0);
$url = filter_input(INPUT_GET, 'url', FILTER_SANITIZE_URL);

if (empty( $url ))
  return;
else {

  $url = urldecode($url);
  $host = parse_url( $url, PHP_URL_HOST );
  if (empty($host)) {
    // relative URL, add Host
    $url = 'http://' . getenv('SERVER_NAME') . $url;
  }
  if ( filter_var( $url, FILTER_VALIDATE_URL) ) {
    $headers = get_headers( $url, 1 );
    $ret = array(
      'code' => $headers[0],
      'redirect' => isset( $headers['Location'] ) ? $headers['Location'] : null
    );
  } else {
    $ret = array( 'code' => 'invalid URL!' );
  }
  echo json_encode( $ret );
}