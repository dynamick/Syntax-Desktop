<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     function.thumb.php
 * Type:     function
 * Name:     thumb
 * Purpose:  wraps phpThumb
 * Author:   Marco
 * -------------------------------------------------------------
 */

function smarty_function_thumb($params, &$smarty){
  global $synAbsolutePath, $synPublicPath;

  if (!isset($params['src']))
    $params['src'] = $synPublicPath . 'mat/default.png';

  $paramArray = array();
  foreach( $params as $key => $value )
    $paramArray[] = $key . '=' . rawurlencode($value); //$PHPTHUMB_CONFIG['high_security_url_separator'];

  //$paramString .= 'hash=' . md5( $paramString . $PHPTHUMB_CONFIG['high_security_password'] );
  $paramString = htmlentities( implode( '&', $paramArray) );

  return $synPublicPath . DIRECTORY_SEPARATOR . 'thumb.php?' . $paramString;
}

// EOF