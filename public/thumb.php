<?php
error_reporting(E_ALL & ~(E_NOTICE | E_DEPRECATED | E_WARNING));
ini_set('display_errors', false);

if(ini_get('date.timezone')=='')
  date_default_timezone_set('Europe/Rome');

// handle per phpthumb
include 'lib/phpthumb/phpThumb.php';

// EOF