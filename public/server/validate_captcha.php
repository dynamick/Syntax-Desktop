<?php

// Begin the session
session_start();

// To avoid case conflicts, make the input uppercase and check against the session value
// If it's correct, echo '1' as a string
//if(strtoupper($_GET['captcha']) == $_SESSION['security_code'])
if(strtolower($_GET['captcha']) == strtolower($_SESSION['security_code']))
	echo json_encode(true);
// Else echo '0' as a string
else
	echo json_encode(false);

?>
