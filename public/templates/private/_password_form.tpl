{trad varname='l' label='account_old_pwd|account_old_pwd_hint|password|password_hint|account_password_confirm|account_password_confirm_hint|account_save|account_reset|error_required_field|error_invalid_email|verifica_valore|reinserire_stesso_valore|campo_obbligatorio|account_exit'}
<form action="{$synPublicPath}/server/account_helper.php"
  class="form-horizontal" method="POST" id="form03">
  <fieldset>
    <div id="legend">
      <legend class=""></legend>
    </div>

    <div class="form-group">
      <label class="col-sm-3 control-label" for="f-old_password">
        {$l.account_old_pwd} <abbr title="{$l.campo_obbligatorio}">∗</abbr>
      </label>
      <div class="col-sm-9">
        <input type="password" id="f-old_password" name="old_password" class="form-control text required">
        <p class="help-block">{$l.account_old_pwd_hint}</p>
      </div>
    </div>

    <div class="form-group">
      <label class="col-sm-3 control-label" for="f-user_password">
        {$l.password} <abbr title="{$l.campo_obbligatorio}">∗</abbr>
      </label>
      <div class="col-sm-9">
        <input type="password" id="f-user_password" name="user[password]" class="form-control text required">
        <p class="help-block">{$l.password_hint}</p>
      </div>
    </div>

    <div class="form-group">
      <label class="col-sm-3 control-label" for="f-password_confirm">
        {$l.account_password_confirm} <abbr title="{$l.campo_obbligatorio}">∗</abbr>
      </label>
      <div class="col-sm-9">
        <input type="password" id="f-password_confirm" name="password_confirm" class="form-control text required" autocomplete="off">
        <p class="help-block">{$l.account_password_confirm_hint}</p>
      </div>
    </div>

    <div class="form-group">
      <div class="col-sm-offset-3 col-sm-9">
        <input type="hidden" value="change_password" name="cmd">
        <input type="hidden" value="{$user.id}" name="user_id">
        <input type="hidden" value="{$user.hashed_id}" name="user_token">
        <input type="hidden" value="{$return_page}" name="ret">
        <button class="btn btn-success" type="submit">{$l.account_save}</button>
        <button class="btn btn-default btn-sm" type="reset">{$l.account_reset}</button>
        <a href="{pageInfo info='path' page=$synPageId}" class="btn btn-sm">{$l.account_exit}</a>
      </div>
    </div>
  </fieldset>
</form>
{capture name=pageScript}
<script type="text/javascript">
  $(document).ready(function(){
    $.validator.messages.required="{$l.error_required_field}";
    $.validator.messages.email="{$l.error_invalid_email}";
    $.validator.messages.remote="{$l.verifica_valore}";
    $.validator.messages.equalTo="{$l.reinserire_stesso_valore}";
    $("#form03").validate({
      rules:{
        password_confirm: { equalTo: "#f-user_password" }
      }
    });
  });
</script>
{/capture}
{append var=synPageScript value=$smarty.capture.pageScript scope=parent}