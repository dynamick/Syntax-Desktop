{trad varname=l label='account_new_email|password|account_old_pwd_hint|account_save|account_reset|error_required_field|error_invalid_email|verifica_valore|reinserire_stesso_valore|campo_obbligatorio|account_exit'}
<form class="form-horizontal" action="{$synPublicPath}/server/account_helper.php" method="POST" id="form_new_email">
  <fieldset>
    <div id="legend">
      <legend class=""></legend>
    </div>

    <div class="form-group">
      <label class="col-sm-3 control-label" for="f-user_new_email">
        {$l.account_new_email} <abbr title="{$l.campo_obbligatorio}">∗</abbr>
      </label>
      <div class="col-sm-9">
        <input type="text" id="f-user_new_email" name="user[new_email]" class="form-control email required">
      </div>
    </div>

    <div class="form-group">
      <label class="col-sm-3 control-label" for="f-user_password">
        {$l.password} <abbr title="{$l.campo_obbligatorio}">∗</abbr>
      </label>
      <div class="col-sm-9">
        <input type="password" id="f-user_password" name="user[password]" class="form-control text required">
        <p class="help-block">{$l.account_old_pwd_hint}</p>
      </div>
    </div>

    <div class="form-group">
      <div class="col-sm-offset-3 col-sm-9">
        <input type="hidden" value="set_new_email" name="cmd">
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
<script src="{$synPublicPath}/js/jquery-1.11.0.min.js"></script>
<script src="{$synPublicPath}/js/private/bootstrap.min.js"></script>
<script src="{$synPublicPath}/js/jquery.validate.min.js"></script>
<script type="text/javascript">
  $(document).ready(function(){
    $.validator.messages.required="{$l.error_required_field}";
    $.validator.messages.email="{$l.error_invalid_email}";
    $.validator.messages.remote="{$l.verifica_valore}";
    $.validator.messages.equalTo="{$l.reinserire_stesso_valore}";
    $("#form_new_email").validate();
  });
</script>
{/capture}{assign pageScript $smarty.capture.pageScript scope="parent"}