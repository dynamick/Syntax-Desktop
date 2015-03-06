{trad varname='l' label='password|password_hint|conferma_password|conferma_password_hint|account_save|annulla|error_required_field|error_invalid_email|verifica_valore|reinserire_stesso_valore'}
<form action="{$synPublicPath}/server/account_helper.php"
  class="form-horizontal" method="POST" id="form06">
  <fieldset>
    <div id="legend">
      <legend class=""></legend>
    </div>

    <div class="form-group">
      <label class="col-sm-3 control-label" for="f-user_password">
        {$l.password} <abbr title="campo obbligatorio">∗</abbr>
      </label>
      <div class="col-sm-9">
        <input type="password" id="f-user_password" name="user[password]" class="form-control text required">
        <p class="help-block">{$l.password_hint}</p>
      </div>
    </div>

    <div class="form-group">
      <label class="col-sm-3 control-label" for="f-password_confirm">
        {$l.conferma_password} <abbr title="campo obbligatorio">∗</abbr>
      </label>
      <div class="col-sm-9">
        <input type="password" id="f-password_confirm" name="password_confirm" class="form-control text required" autocomplete="off">
        <p class="help-block">{$l.conferma_password_hint}</p>
      </div>
    </div>

    <div class="form-group">
      <div class="col-sm-offset-3 col-sm-9">
        <input type="hidden" value="reset_password" name="cmd">
        <input type="hidden" value="{$user.id}" name="user_id">
        <input type="hidden" value="{$user.hashed_id}" name="user_token">
        <input type="hidden" value="{$user.key}" name="user_key">
        <button class="btn btn-success" type="submit">{$l.account_save}</button>
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
    $("#form06").validate({
      rules:{
        password_confirm: { equalTo: "#f-user_password" }
      }
    });
  });
</script>
{/capture}
{append var=synPageScript value=$smarty.capture.pageScript scope=parent}