<?php
//////////////////////////////////////////////////////////////
///  phpThumb() by James Heinrich <info@silisoftware.com>   //
//        available at http://phpthumb.sourceforge.net     ///
//////////////////////////////////////////////////////////////
///                                                         //
// See: phpthumb.readme.txt for usage instructions          //
//      NOTE: THIS FILE HAS NO EFFECT IN OBJECT MODE!       //
//            THIS CONFIG FILE ONLY APPLIES TO phpThumb.php //
//                                                         ///
//////////////////////////////////////////////////////////////

ob_start();
if (!file_exists(dirname(__FILE__).'/phpthumb.functions.php') || !include_once(dirname(__FILE__).'/phpthumb.functions.php')) {
  ob_end_flush();
  die('failed to include_once(phpthumb.functions.php) - realpath="'.realpath(dirname(__FILE__).'/phpthumb.functions.php').'"');
}
ob_end_clean();

//$PHPTHUMB_CONFIG['document_root']                        = realpath(($_SERVER["DOCUMENT_ROOT"] && ereg('^'.preg_quote(realpath($_SERVER["DOCUMENT_ROOT"])), realpath(__FILE__))) ? $_SERVER["DOCUMENT_ROOT"] : str_replace(dirname(@$_SERVER['PHP_SELF']), '', str_replace(DIRECTORY_SEPARATOR, '/', dirname(__FILE__))));
$PHPTHUMB_CONFIG['document_root']                        = $_SERVER["DOCUMENT_ROOT"];
$PHPTHUMB_CONFIG['cache_directory']                      = $_SERVER["DOCUMENT_ROOT"].'/cache/';
$PHPTHUMB_CONFIG['cache_disable_warning']                = false;
$PHPTHUMB_CONFIG['cache_directory_depth']                = 1;
$PHPTHUMB_CONFIG['cache_maxage']                         = 86400 * 90;        // 90 days
$PHPTHUMB_CONFIG['cache_maxsize']                        = 1024 * 1024 * 200; // 200 Mb
$PHPTHUMB_CONFIG['cache_maxfiles']                       = 500;
$PHPTHUMB_CONFIG['cache_source_enabled']                 = false;
$PHPTHUMB_CONFIG['cache_source_directory']               = dirname(__FILE__).'/cache/source/';
$PHPTHUMB_CONFIG['cache_source_filemtime_ignore_local']  = false;
$PHPTHUMB_CONFIG['cache_source_filemtime_ignore_remote'] = true;
$PHPTHUMB_CONFIG['cache_default_only_suffix']            = '';
$PHPTHUMB_CONFIG['cache_prefix']                         = 'thumb_'.str_replace('www.', '', @$_SERVER['SERVER_NAME']);
$PHPTHUMB_CONFIG['cache_force_passthru']                 = true;
$PHPTHUMB_CONFIG['temp_directory']                       = $PHPTHUMB_CONFIG['cache_directory'];
if (phpthumb_functions::version_compare_replacement(phpversion(), '4.3.2', '>=')
&& !defined('memory_get_usage') && !@ini_get('memory_limit')) {
  $PHPTHUMB_CONFIG['max_source_pixels']                  = 0;
} else {
  $PHPTHUMB_CONFIG['max_source_pixels']                  = round(max(intval(ini_get('memory_limit')), intval(get_cfg_var('memory_limit'))) * 1048576 / 6);
}
$PHPTHUMB_CONFIG['prefer_imagemagick']                   = false;
$PHPTHUMB_CONFIG['imagemagick_use_thumbnail']            = false;
if (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN') {
  $PHPTHUMB_CONFIG['imagemagick_path']                   = 'C:/ImageMagick/convert.exe';
} else {
  $PHPTHUMB_CONFIG['imagemagick_path']                   = null;
}
$PHPTHUMB_CONFIG['output_format']                        = 'jpeg';
$PHPTHUMB_CONFIG['output_maxwidth']                      = 0;
$PHPTHUMB_CONFIG['output_maxheight']                     = 0;
$PHPTHUMB_CONFIG['output_interlace']                     = true;
$PHPTHUMB_CONFIG['error_image_width']                    = 400;
$PHPTHUMB_CONFIG['error_image_height']                   = 100;
$PHPTHUMB_CONFIG['error_message_image_default']          = '';
$PHPTHUMB_CONFIG['error_bgcolor']                        = 'CCCCFF';
$PHPTHUMB_CONFIG['error_textcolor']                      = 'FF0000';
$PHPTHUMB_CONFIG['error_fontsize']                       = 1;
$PHPTHUMB_CONFIG['error_die_on_error']                   = true;
$PHPTHUMB_CONFIG['error_silent_die_on_error']            = false;
$PHPTHUMB_CONFIG['error_die_on_source_failure']          = true;
$PHPTHUMB_CONFIG['nohotlink_enabled']                    = false;
$PHPTHUMB_CONFIG['nohotlink_valid_domains']              = array(@$_SERVER['HTTP_HOST']);
$PHPTHUMB_CONFIG['nohotlink_erase_image']                = true;
$PHPTHUMB_CONFIG['nohotlink_text_message']               = 'Off-server thumbnailing is not allowed';
$PHPTHUMB_CONFIG['nooffsitelink_enabled']                = true;
$PHPTHUMB_CONFIG['nooffsitelink_valid_domains']          = array(@$_SERVER['HTTP_HOST'], 'www.local.it');
$PHPTHUMB_CONFIG['nooffsitelink_require_refer']          = false;
$PHPTHUMB_CONFIG['nooffsitelink_erase_image']            = false;
$PHPTHUMB_CONFIG['nooffsitelink_watermark_src']          = '';
$PHPTHUMB_CONFIG['nooffsitelink_text_message']           = 'Image taken from '.@$_SERVER['HTTP_HOST'];
$PHPTHUMB_CONFIG['border_hexcolor']                      = '000000';
$PHPTHUMB_CONFIG['background_hexcolor']                  = 'FFFFFF';
$PHPTHUMB_CONFIG['ttf_directory']                        = $_SERVER["DOCUMENT_ROOT"].'/public/';
$PHPTHUMB_CONFIG['mysql_query']                          = '';
$PHPTHUMB_CONFIG['mysql_hostname']                       = 'localhost';
$PHPTHUMB_CONFIG['mysql_username']                       = '';
$PHPTHUMB_CONFIG['mysql_password']                       = '';
$PHPTHUMB_CONFIG['mysql_database']                       = '';
$PHPTHUMB_CONFIG['high_security_enabled']                = false;
$PHPTHUMB_CONFIG['high_security_password']               = '';
$PHPTHUMB_CONFIG['disable_debug']                        = false;
$PHPTHUMB_CONFIG['allow_src_above_docroot']              = false;
$PHPTHUMB_CONFIG['allow_src_above_phpthumb']             = true;
$PHPTHUMB_CONFIG['allow_parameter_file']                 = false;
$PHPTHUMB_CONFIG['allow_parameter_goto']                 = false;
$PHPTHUMB_CONFIG['http_user_agent']                      = '';
$PHPTHUMB_CONFIG['disable_pathinfo_parsing']             = false;
$PHPTHUMB_CONFIG['disable_imagecopyresampled']           = false;
$PHPTHUMB_CONFIG['disable_onlycreateable_passthru']      = true;
$PHPTHUMB_CONFIG['http_fopen_timeout']                   = 5;
$PHPTHUMB_CONFIG['http_follow_redirect']                 = true;
$PHPTHUMB_CONFIG['use_exif_thumbnail_for_speed']         = false;
$PHPTHUMB_CONFIG['allow_local_http_src']                 = false;
$PHPTHUMB_DEFAULTS_GETSTRINGOVERRIDE                     = true;
$PHPTHUMB_DEFAULTS_DISABLEGETPARAMS                      = false;
$PHPTHUMB_DEFAULTS['q']                                  = 95;

function phpThumbURL($ParameterString) {
  global $PHPTHUMB_CONFIG;
  return str_replace(@$PHPTHUMB_CONFIG['document_root'], '', dirname(__FILE__)).DIRECTORY_SEPARATOR.'phpThumb.php?'.$ParameterString.'&hash='.md5($ParameterString.@$PHPTHUMB_CONFIG['high_security_password']);
}
?>
