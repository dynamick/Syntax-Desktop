<?php
/*
* Smarty plugin
* -------------------------------------------------------------
* File:     function.check.php
* Type:     function
* Name:     check
* Purpose:  Controlla l'autenticazione utente, e nel caso positivo, salva il cookies
* -------------------------------------------------------------
*/
function smarty_function_regCheck($params, &$smarty)
{
  global $db;
  $phpself=$_SERVER["PHP_SELF"];
  if ($_REQUEST["act"]=="login") {
    $username=$_POST["username"];
    $password=$_POST["password"];
    $qry="SELECT * FROM iscritto WHERE username='$username' AND password='$password' and attivo='1'";
    $res=$db->Execute($qry);
    $q=$res->RecordCount();
    if ($q==0) {echo "<script>alert('Nome utente o password errati.'); location.href='/registrazione/';</script>";die();}
    else {
      $arr=$res->FetchRow();
      $id=$arr["id"];
      setcookie ("userid", $id, time()+(3600*24*365));  /* spira in 1 anno */
      header("location: $phpself");
    }
  } elseif ($_REQUEST["act"]=="logoff") {
    global $userid,$db;
    setcookie ("userid", "", time()-(3600));  /* spirato*/
    header("location: $phpself");
  }
  return ;
}
?>