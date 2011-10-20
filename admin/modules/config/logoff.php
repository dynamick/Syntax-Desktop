<?
  session_start();
  //session_unregister("synUser");
  unset($_SESSION["synUser"]);
  header("location: ../../");
?>