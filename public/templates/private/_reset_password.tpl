{trad varname='l' label='email_recupero_password|login_submit|account_send|reg_submit|error_required_field|error_invalid_email|verifica_valore|reinserire_stesso_valore'}
<div class="row">
  <div class="col-sm-6 col-md-4 col-md-offset-4">
    <h1 class="text-center login-title">{$l.email_recupero_password}</h1>
    <div class="account-wall">
      <form action="{$synPublicPath}/server/account_helper.php" id="form_forgot_password" class="form-signin" method="post" >
        <input type="text" name="email" class="form-control required email" placeholder="Email">
        <input type="hidden" value="send_new_password" name="cmd">
        <button class="btn btn-lg btn-primary btn-block" type="submit">{$l.account_send}</button>
        <a href="?cmd=" class="pull-right need-help">{$l.login_submit}</a>
        <a href="?cmd=register" class="pull-left new-account">{$l.reg_submit}</a>
        <span class="clearfix"></span>
      </form>
    </div>
  </div>
</div>
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
    $("#form_forgot_password").validate();
  });
</script>
{/capture}{assign pageScript $smarty.capture.pageScript scope="parent"}