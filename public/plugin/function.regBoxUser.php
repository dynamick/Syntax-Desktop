<?php
/*
* Smarty plugin
* -------------------------------------------------------------
* File:     function.boxUtente.php
* Type:     function
* Name:     boxUtente
* Purpose:  Gestisce il box di autenticazione utente
* -------------------------------------------------------------
*/
function smarty_function_regBoxUser($params, &$smarty)
{
  global $db,$userid;
  $phpself=$_SERVER["PHP_SELF"];

  if ($userid!="") {
    $qry="SELECT * FROM iscritto WHERE id='$userid' ";
    $res=$db->Execute($qry);
    $arr=$res->FetchRow();
    $nome=$arr["nome"];
    ob_start();
    //echo "<pre>"; print_r($_SERVER); echo "</pre>";
    ?>
			<div id="areaprivata">
			  <div class="box">
					<div class="titolo">Utente Autenticato</div>
				  <div>Benvenuto <?=$nome?></div>
  	    	<div style="margin-top:20px; font-size:12px">Vuoi uscire? <a href="<?=$phpself?>/?act=logoff">Premi qui!</a></div>
				</div>
			</div>
    <?
    $ret=ob_get_contents();
    ob_end_clean();
  } else {
    global $synPublicPath;
    ob_start();
    ?>
			<div id="areaprivata">
			  <div class="box">
					<div class="titolo">AREA RISERVATA</div>
					<form action="<?=$phpself?>/" method="post" style="padding:0; margin:0">
            <input type="hidden" name="act" value="login"/>
						<div>Utente <input name="username" type="text" size="17" /></div>
						<div style="margin-top:3px;">Password <input name="password" type="password" size="14" maxlength="5" />
            <input type="image" src="<?=$synPublicPath?>/img/go.gif" alt="Accedi" /></div>
            <input type="hidden" name="act" value="login"/>
					</form>
			    	<div style="margin-top:20px; font-size:12px">Nuovo Utente? <a href="/registrazione/">Registrati</a></div>
				</div>
			</div>
    <?
    $ret=ob_get_contents();
    ob_end_clean();
  }
  return $ret;
}
?>
