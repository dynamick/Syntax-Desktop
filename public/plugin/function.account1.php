<?php
function smarty_function_account($params, &$smarty) {
  global $db,$synRootPasswordSalt;
  
  # Custom variable
  $siteName           = "Syntax Desktop";
  $fromName           = "Admin <info@syntaxdesktop.com>"; 
  $pagePath           = createPath('52');             # set the registration page id
  $privacyFile        = "/public/privacy.html";
  $newsletter_active  = false;                        # set to true if listmessenger is installed      
	$t        					= multiTranslateDictionary(array(
    'password_dimenticata', 
    'registrati_qui', 
    'inserisci_dati', 
    'email', 
    'password', 
    'invia', 
    'annulla', 
    'nome', 
    'cognome', 
    'ragione_sociale', 
    'indirizzo', 
    'citta', 
    'cap', 
    'provincia', 
    'cinque_caratteri', 
    'necessaria_registrazione', 
    'dati_personali', 
    'vostro_account', 
    'conferma_password', 
    'letto_accettato', 
    'letto_informativa', 
    'privacy', 
    'newsletter', 
    'registrazione_newsletter', 
    'campo_obbligatorio', 
    'email_non_valida', 
    'almeno_cinque_caratteri', 
    'password_sbagliate', 
    'dati_salvati', 
    'conferma_registrazione', 
    'account_attivo', 
    'effettua_login', 
    'utente_password_errati', 
    'autenticato', 
    'effettua_logout', 
    'dati_aggiornati', 
    'inserisci_dati', 
    'password_rigenerata', 
    'nuovi_dati_inviati', 
    'email_sconosciuta', 
    'indirizzo_non_presente', 
    'riprova')
  );
	
  # Automatic variable initialization
  $pagetitle          = $smarty->getTemplateVars('synPageTitle');  
  $userid             = $_COOKIE["web_user"]["id"];   
  $pageURL            = "http://".getenv("HTTP_HOST");

  #+-------------------------------------------------------------------------+
  #¦                             AUTENTICAZIONE                              ¦
  #+-------------------------------------------------------------------------+
  if (!isset($_REQUEST["action"])) {
    if(!isset($_COOKIE["web_user"])) {
      ob_start();
?>
<div class="sidebar-column">
  <h2><?=$pagetitle?></h2>
  <p>
    <a href="<?=$pagePath?>?action=forgot" class="follow"><?=$t['password_dimenticata']?></a><br/>
    <a href="<?=$pagePath?>?action=reg" class="follow"><?=$t['registrati_qui']?></a>
  </p>
</div>
<div class="main-column">
  <div class="intro">
<?php
  if ($_GET['err']=='wrong_pwd') echo "<h3>{$t['utente_password_errati']}.</h3>\n";
?>  
    <p><?=$t['inserisci_dati']?>:</p>
    <form action="/public/server/setcookies.php" method="post" id="login">
      <div id="errori"></div>
      <fieldset>
        <p>
          <label for="remail"><?=$t['email']?>: *</label>
          <input tabindex="1" id="remail" name="email" type="text" class="text email required" />
        </p>
        <p>
          <label for="rpassword"><?=$t['password']?>: *</label>
          <input tabindex="2" id="rpassword" name="password" type="password" class="text required" />
        </p>
        <p>
          <input type="hidden" name="act" value="login"/>
          <button type="submit" class="button"><span><?=$t['invia']?></span></button>
        </p>
      </fieldset>
    </form>
    <script type="text/javascript">
      $(document).ready(function() {
        $.validator.messages.required="<?=$t['campo_obbligatorio']?>"
        $.validator.messages.email="<?=$t['email_non_valida']?>"
        $("#login").validate()
      })
    </script>
  </div>
</div>
<?php
      $contents = ob_get_contents();
    } else {
      $res = $db->Execute("SELECT email FROM users WHERE id='".$_COOKIE['web_user']['id']."' AND active=1 LIMIT 0,1");
      list($email) = $res->FetchRow();
      ob_start();
?>
<div class="big-column">
  <h2><?=$pagetitle?></h2>
  <p><?=$t['autenticato']?> <strong><?=$email?></strong></p>
  <p><a class="follow" href="/public/server/setcookies.php?act=logoff"><?=$t['effettua_logout']?></a></p>
</div>
<?
      $contents = ob_get_contents();
    }
    ob_end_clean();
    #+-------------------------------------------------------------------------+


  #+-------------------------------------------------------------------------+
  #¦ * FORM REGISTRAZIONE / MODIFICA DATI *                                  ¦
  #¦-------------------------------------------------------------------------¦
  } elseif ( $_GET["action"]=="reg"
          || $_GET["action"]=="update"
          ){
    
    if ( $_GET["action"]=="update"
      && $userid
      ){
      $u_res = $db->Execute("SELECT * FROM anagrafica_web WHERE id='{$userid}' AND attivo=1 LIMIT 0,1");
      $u_arr = $u_res->FetchRow();
    }
    $tab = 0;
    ob_start();
    ?>
  <div class="big-column">
    <h2><?=$pagetitle?></h2>
    <script type="text/javascript">
    //<![CDATA[
    $().ready(function() {
      $.validator.messages.required="<?=$t['campo_obbligatorio']?>";
      $.validator.messages.email="<?=$t['email_non_valida']?>";
      $.validator.messages.minlength="<?=$t['almeno_cinque_caratteri']?>",
      $.validator.messages.equalTo="<?=$t['password_sbagliate']?>"
    
      $("#reg").validate({
        errorPlacement: function(error, element) {
          error.appendTo(element.parents("p"));
        },  
        rules: {
          password: {minlength:5},
          password2: {minlength:5, equalTo:"#rpassword"}
        },
      });
    });
    //]]>
    </script>
    <p><?=$t['necessaria_registrazione']?></p>
    <form action="<?=$pagePath?>" method="post" id="reg">
      <div id="errori"></div>
      <fieldset>
        <legend><?=$t['dati_personali']?></legend>
        <p>
          <label for="rnome"><?=$t['nome']?>: *</label>
          <input tabindex="<?=tabIndex($tab)?>" id="rnome" name="name" type="text" class="text required" value="<?=$u_arr['name']?>" />
        </p>
        <p>
          <label for="rcognome"><?=$t['cognome']?>: *</label>
          <input tabindex="<?=tabIndex($tab)?>" id="rcognome" name="surname" type="text" class="text required" value="<?=$u_arr['surname']?>" />
        </p>
        <p>
          <label for="rragione"><?=$t['ragione_sociale']?>:</label>
          <input tabindex="<?=tabIndex($tab)?>" id="rragione" name="company" type="text" class="text" value="<?=$u_arr['company']?>" />
        </p>
        <p>
          <label for="rindirizzo"><?=$t['indirizzo']?>:</label>
          <input tabindex="<?=tabIndex($tab)?>" id="rindirizzo" name="address" type="text" class="text" value="<?=$u_arr['address']?>" />
        </p>
        <p>
          <label for="rcitta"><?=$t['citta']?>:</label>
          <input tabindex="<?=tabIndex($tab)?>" id="rcitta" name="city" type="text" class="text" value="<?=$u_arr['city']?>" />
        </p>
        <p>
          <label for="rcap"><?=$t['cap']?>:</label>
          <input tabindex="<?=tabIndex($tab)?>" id="rcap" name="zip" type="text" class="text" value="<?=$u_arr['zip']?>" />
        </p>
        <p>
          <label for="rprovincia"><?=$t['provincia']?>:</label>
          <input tabindex="<?=tabIndex($tab)?>" id="rprovincia" name="province" type="text" class="text" value="<?=$u_arr['province']?>" />      
        </p>
      </fieldset>
      <fieldset>
        <legend><?=$t['vostro_account']?></legend>
        <p>
          <label for="remail"><?=$t['email']?>: *</label>
          <input tabindex="<?=tabIndex($tab)?>" id="remail" name="email" type="text" class="text email required" value="<?=$u_arr['email']?>" />
        </p>
        <p>
          <label for="rpassword"><?=$t['password']?> (<?=$t['cinque_caratteri']?>): *</label>
          <input tabindex="<?=tabIndex($tab)?>" id="rpassword" name="password" type="password" class="text<?=($_REQUEST['action']=='reg' ? ' required':'')?>" />
        </p>
        <p>
          <label for="rpassword2"><?=$t['conferma_password']?>: *</label>
          <input tabindex="<?=tabIndex($tab)?>" id="rpassword2" name="password2" type="password" class="text<?=($_REQUEST['action']=='reg' ? ' required':'')?>" />
        </p>
<? if($newsletter_active==true){ ?>
        <p>
          <label for="cnewsletter"><?=$t['newsletter']?>:</label>
          <span class="wrapper">
            <input tabindex="<?=tabIndex($tab)?>" id="cnewsletter" name="newsletter" type="checkbox" value="1" class="check" <?if(_checkMailExists($u_arr['email'])) echo "checked=\"checked\"";?>/>
            <?=$t['registrazione_newsletter']?>
          </span>
        </p>
<? } ?>
<? if($_REQUEST["action"]=="reg"){ ?>
        <p>
          <label for="cprivacy"><?=$t['privacy']?>: *</label>
          <span class="wrapper">
            <input tabindex="<?=tabIndex($tab)?>" id="cprivacy" name="privacy" type="checkbox" value="1" class="check required" />
            <?=$t['letto_accettato']?> <a href="<?= $privacyFile ?>?TB_iframe=true&amp;width=600&amp;height=450" class="follow thickbox"><?=$t['letto_informativa']?></a>
          </span>
        </p>
  <? } ?>
      </fieldset>
      <p class="wide">
        <input type="hidden" name="action" value="<?=($_REQUEST['action']=='reg' ? 'save' : 'update')?>" />
        <input type="hidden" name="userid" value="<?=$u_arr['id']?>" />      
        <button type="reset" class="button"><span><?=$t['annulla']?></span></button>
        <button type="submit" class="button" tabindex="<?=tabIndex($tab)?>"><span><?=$t['invia']?></span></button>
      </p>
    </form>
  </div>
<?php
    #+-------------------------------------------------------------------------+
    ob_end_flush();
    $contents = ob_get_contents();
    ob_end_clean();

  #+-------------------------------------------------------------------------+
  #¦ * SALVATAGGIO DATI + SPEDIZIONE CONFERMA *                              ¦
  #¦-------------------------------------------------------------------------¦
  } elseif ($_POST["action"]=="save") {

    $nome       = addslashes(strip_tags($_REQUEST["name"]));
    $cognome    = addslashes(strip_tags($_REQUEST["surname"]));
    $ragione    = addslashes(strip_tags($_REQUEST["company"]));
    $indirizzo  = addslashes(strip_tags($_REQUEST["address"]));
    $citta      = addslashes(strip_tags($_REQUEST["city"]));
    $cap        = addslashes(strip_tags($_REQUEST["zip"]));
    $provincia  = addslashes(strip_tags($_REQUEST["province"]));
    $email      = addslashes(strip_tags($_REQUEST["email"]));
    $password   = addslashes(strip_tags($_REQUEST["password"]));
    $password2  = addslashes(strip_tags($_REQUEST["password2"]));
    $newsletter = addslashes(strip_tags($_REQUEST["newsletter"]));
    $privacy    = addslashes(strip_tags($_REQUEST["privacy"]));
    $data       = date("Y-m-d H:i:s");
    $confirm    = md5($data);
    $hashed_pwd = md5($password.$synRootPasswordSalt);
    $error      = '';

    if ($nome=="")
      $error = 'Digitare il proprio nome.';
    if ($cognome=="")
      $error = 'Digitare il proprio cognome.';
    if ($email=="")
      $error = 'Nessuna email inserita.';
    if ($privacy!="1")
      $error = 'Accettare la nota informativa sulla privacy per procedere.';
    if ($password!=$password2)
      $error = 'La ripetizione della password non coincide. Ti preghiamo di riprovare.';

    if ($error!='') {
      // manca qualcosa, abortisco
      die("<script type=\"text/javascript\">alert('".$error."'); history.go(-1);</script>");

    } else {
      // tutto ok, inserisco i dati
      $qry = <<<EOQ
      INSERT INTO users (
        name,
        surname,
        company,
        email,
        address,
        city,
        zip,
        province,
        confirmation_code,
        active,
        `group`,
        timestamp,
        password,
        newsletter
      ) VALUES (
        '$nome',
        '$cognome',
        '$ragione',
        '$email',
        '$indirizzo',
        '$citta',
        '$cap',
        '$provincia',
        '$confirm',
        '',
        '1',
        '$data',
        '$hashed_pwd',
        '$newsletter'
      );
EOQ;
      $res = $db->Execute($qry);
      //$userid = $db->Insert_Id();

      $destinatari = "$nome $cognome <$email>";
      $link        = $pageURL.$pagePath."?action=conferma&validation=".$confirm;
      $oggetto     = "Iscrizione a $siteName";
      $messaggio   = <<<EOM
  <p>Ciao $nome,<br />ricevi questo messaggio perche' e' stato recentemente creato un account sul sito $siteName.</p>
  <p>Le tue credenziali di accesso sono:</p>
  <ul>
    <li><strong>username:</strong> $email</li>
    <li><strong>password:</strong> $password</li>
  </ul>
  <h4>IMPORTANTE!</h4>
  <p>Se veramente intendi iscriverti, clicca su <strong><a href="$link">questo link</a></strong> per attivare la tua sottoscrizione.</p>
  <p>Buona navigazione,<br />lo staff di $siteName</p>
EOM;

      # Header della mail:
      $intestazioni .= "From: ".$fromName."\n";
      htmlmail($destinatari, $oggetto, $messaggio, $intestazioni);
    }

    ob_start();
?>
<div class="big-column">
  <h2><?=$pagetitle?></h2>
  <h3><?=$t['dati_salvati']?></h3>
  <p><?=$t['conferma_registrazione']?></p>
</div>
<?php
    #+-------------------------------------------------------------------------+
    $contents = ob_get_contents();
    ob_end_clean();

  #+-------------------------------------------------------------------------+
  #¦ * AGGIORNAMENTO DATI *                                                  ¦
  #¦-------------------------------------------------------------------------¦
  } elseif (($_POST["action"]=="update") && ($_POST["userid"]==$userid)) {
    $nome       = addslashes(strip_tags($_REQUEST["name"]));
    $cognome    = addslashes(strip_tags($_REQUEST["surname"]));
    $ragione    = addslashes(strip_tags($_REQUEST["company"]));
    $email      = addslashes(strip_tags($_REQUEST["email"]));
    $indirizzo  = addslashes(strip_tags($_REQUEST["address"]));
    $citta      = addslashes(strip_tags($_REQUEST["city"]));
    $cap        = addslashes(strip_tags($_REQUEST["zip"]));
    $provincia  = addslashes(strip_tags($_REQUEST["province"]));
    $password   = addslashes(strip_tags($_REQUEST["password"]));
    $password2  = addslashes(strip_tags($_REQUEST["password2"]));
    $newsletter = addslashes(strip_tags($_REQUEST["newsletter"]));
    $error      = '';

    if ($nome=="")
      $error = 'Digitare il proprio nome.';
    if ($cognome=="")
      $error = 'Digitare il proprio cognome.';
    if ($email=="")
      $error = 'Nessuna email inserita.';
    if ($password && ($password!=$password2))
      $error = 'La ripetizione della password non coincide. Ti preghiamo di riprovare.';

    if ($error!='') {
      // manca qualcosa, abortisco
      die("<script type=\"text/javascript\">alert('".$error."'); history.go(-1);</script>");

    } else {
      // tutto ok, inserisco i dati
      $set_password = ($password ? ", password = '".md5($password.$synRootPasswordSalt)."'" : "");
      $qry = <<<EOQ
      UPDATE users SET
            name = '$nome',
         surname = '$cognome',
         company = '$ragione',
           email = '$email',
         address = '$indirizzo',
            city = '$citta',
             zip = '$cap',
      newsletter = '$newsletter',
        province = '$provincia' $set_password
        WHERE id ='$userid' 
      AND active = '1';
EOQ;
      $res = $db->Execute($qry);
      
      //aggiorno la newsletter
      if($newsletter_active==true){
        _delUserFromNewsletter($email);
        if($newsletter==1) _addUserToNewsletter($email,1,$nome,$cognome);
      }

    }
    ob_start();
?>
<div class="big-column">
  <h2><?=$pagetitle?></h2>
  <p><?=$t['dati_aggiornati']?>.</p>
</div>
<?php
    #+-------------------------------------------------------------------------+
    $contents = ob_get_contents();
    ob_end_clean();
    


  #+-------------------------------------------------------------------------+
  #¦ * ATTIVAZIONE ACCOUNT *                                                 ¦
  #¦-------------------------------------------------------------------------¦
  } elseif ($_GET["action"]=="conferma") {
    $validation = addslashes(strip_tags($_GET["validation"]));
    $qry = "SELECT * FROM users WHERE confirmation_code='".$validation."'";
    $res = $db->Execute($qry);
    $q   = $res->RecordCount();
    if ($q!=0) {
      $arr  = $res->FetchRow();
      $user = $arr['id'];
      $name = $arr['name']." ".$arr['surname'];
      $qry2 = "UPDATE users SET active='1', confirmation_code='' WHERE id='$user'";
      $db->Execute($qry2);

      if($newsletter_active==true){      
        //aggiorno la newsletter
        _delUserFromNewsletter($arr['email']);
        if($arr['newsletter']==1) _addUserToNewsletter($arr['email'],1,$arr['name'],$arr['surname']);
      }
      
      //notifica
      htmlmail($fromName, "Nuovo utente registrato", $name." si &egrave; registrato con successo.");
    }
    
    ob_start();
?>
<div class="big-column">
  <h2><?=$pagetitle?></h2>

  <p><?=$t['account_attivo']?></p>
  <p><a href="<?=$pagePath?>" class="follow"><?=$t['effettua_login']?></a></p>

</div>
<?php
    $contents = ob_get_contents();
    ob_end_clean();
    #+-------------------------------------------------------------------------+


  #+-------------------------------------------------------------------------+
  #¦ * PASSWORD DIMENTICATA *                                                ¦
  #¦-------------------------------------------------------------------------¦
  } elseif ($_GET["action"]=="forgot") {
    ob_start();
?>
<div class="big-column">
  <h2><?=$pagetitle?></h2>
  <script type="text/javascript">
  //<![CDATA[
  $().ready(function() {
    $("#reg").validate({
      messages: {
        email: {required: "campo richiesto", email: "inserisci un indirizzo valido"}
      }
    });
  });
  //]]>
  </script>
  <p><?=$t['inserisci_dati']?>:</p>
  <form action="?action=forgot_send" method="post" id="reg">
    <fieldset>
      <div id="errori"></div>  
      <P>
        <label for="remail"><?=$t['email']?>: *</label>
        <input tabindex="1" id="remail" name="email" type="text" class="text email required" />
      </P>
      <div class="wide">
        <input type="hidden" name="action" value="forgot_send" />
        <button tabindex="2" type="submit" class="button"><span><?=$t['invia']?></span></button>
      </P>
    </fieldset>
  </form>
</div>
<?php
    $contents = ob_get_contents();
    ob_end_clean();
    #+-------------------------------------------------------------------------+



  #+-------------------------------------------------------------------------+
  #¦ * RESET + SPEDIZIONE PASSWORD *                                         ¦
  #¦-------------------------------------------------------------------------¦
  } elseif ($_POST["action"]=="forgot_send") {
    $email = addslashes(strip_tags($_REQUEST["email"]));
    
    $qry = "SELECT id,name,surname FROM users WHERE email='".$email."'  LIMIT 0,1";
    #$qry = "SELECT id,name,surname FROM users WHERE email='".$email."' AND active='1' LIMIT 0,1";
    $res = $db->Execute($qry);
    $q   = $res->RecordCount();
    if ($q!=0){ // **************** trovato qualcosa ************************/
      $arr = $res->FetchRow();

      $id      = $arr['id'];
      $user    = $arr['name']." ".$arr['surname'];
      $new_pwd = str_makerand(6, 6, false, false, true); //genero la nuova password

      $qry_update = "UPDATE users SET password='".md5($new_pwd)."' WHERE id='".$id."' AND active='1'";
      $res = $db->Execute($qry_update);

      /* Email all'iscritto */
      $destinatari  = "$user <$email>";
      $oggetto = "Invio dati autenticazione di ".$siteName;
      $messaggio = <<<EOM
    <p>Caro utente,<br />ricevi questo messaggio perch&egrave; &egrave; stata resettata la password del tuo account sul sito $siteName.</p>
    <p>La tua nuova password é:</p>
    <h4>$new_pwd</h4>
    <p>La password &egrave; stata generata casualmente, potrai cambiarla con una di tua scelta una volta effettuato il login.</p>
    <p>Buona navigazione,<br />lo staff di $siteName</p>
EOM;

      $intestazioni .= "From: $fromName\n";

      htmlmail($destinatari, $oggetto, $messaggio, $intestazioni);

      $text = <<<EOT
<h2>{$t['password_rigenerata']}</h2>
<p>{$t['nuovi_dati_inviati']} <strong>$email</strong>.</p>
EOT;

    } else { // **************** trovato niente ************************/

      $text = <<<EOT
<h2>{$t['email_sconosciuta']}</h2>
<p>{$t['indirizzo_non_presente']} <a href="$pagePath" class="follow">{$t['riprova']}</a></p>
EOT;
    }

    $contents = "<div class=\"big-column\">\n".$text."</div>\n";
    #+-------------------------------------------------------------------------+

  } else {
  
    $contents = 'manca qualcosa... '.$_REQUEST['action'];
  }
  return $contents;
}


?>