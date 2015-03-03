{trad varname='l' label='sign_in|password_dimenticata|login_submit|reg_submit|error_required_field|error_invalid_email|verifica_valore|reinserire_stesso_valore'}
<div class="row">
  <div class="col-sm-6 col-md-4 col-md-offset-4">
    <h1 class="text-center login-title">{$l.sign_in}</h1>
    <div class="account-wall">
      <img class="profile-img" src="{$synPublicPath}/img/generic_user.jpg" alt="">
      <form action="{$synPublicPath}/server/account_helper.php"
        id="form_login_user" class="form-signin" method="post" novalidate="novalidate">
        <input type="text" name="email" class="form-control required email" placeholder="Email" autofocus>
        <input type="password" name="password" class="form-control required" placeholder="Password">
        <input type="hidden" name="cmd" value="login">
        <button class="btn btn-lg btn-primary btn-block" type="submit">{$l.login_submit}</button>
        <a href="{pageInfo info='path' page=$synPageId}?cmd=forgot_password" class="pull-right need-help">{$l.password_dimenticata}</a>
        <a href="{pageInfo info='path' page=$synPageId}?cmd=register" class="pull-left new-account">{$l.reg_submit}</a>
        <span class="clearfix"></span>
      </form>
    </div>
  </div>
</div>
{capture name=pageScript}
<script type="text/javascript">
  $(document).ready(function(){
    $.validator.messages.required="{$l.error_required_field}";
    $.validator.messages.email="{$l.error_invalid_email}";
    $.validator.messages.remote="{$l.verifica_valore}";
    $.validator.messages.equalTo="{$l.reinserire_stesso_valore}";
    $("#form_login_user").validate();
  });
</script>
{/capture}{append var=synPageScript value=$smarty.capture.pageScript scope=parent}