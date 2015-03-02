{trad varname='l' label='nuovo_utente|nome|cognome|email|password|account_password_confirm|privacy|informativa_privacy|informativa|salva|annulla|error_required_field|error_invalid_email|verifica_valore|captcha'}
<form action="{$synPublicPath}/server/account_helper.php"
  class="form-horizontal" method="POST" id="sign_up_form" accept-charset="UTF-8">
  <fieldset>
    <div id="legend">
      <legend class="">{$l.nuovo_utente}</legend>
    </div>

    <div class="form-group">
      <label class="col-sm-3 control-label" for="f-user_name">
        {$l.nome} <abbr title="campo obbligatorio">∗</abbr>
      </label>
      <div class="col-sm-9">
        <input type="text" id="f-user_name" name="user[name]" class="form-control text required">
        <span class="glyphicon glyphicon-remove form-control-feedback form-control-feedback-error"></span>
        <span class="glyphicon glyphicon-ok form-control-feedback form-control-feedback-ok"></span>
        <p class="help-block"></p>
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-3 control-label" for="f-user_surname">
        {$l.cognome} <abbr title="campo obbligatorio">∗</abbr>
      </label>
      <div class="col-sm-9">
        <input type="text" id="f-user_surname" name="user[surname]" class="form-control text required">
        <span class="glyphicon glyphicon-remove form-control-feedback form-control-feedback-error"></span>
        <span class="glyphicon glyphicon-ok form-control-feedback form-control-feedback-ok"></span>
        <p class="help-block"></p>
      </div>
    </div>

    <hr>

    <div class="form-group">
      <label class="col-sm-3 control-label" for="f-user_email">
        {$l.email} <abbr title="campo obbligatorio">∗</abbr>
      </label>
      <div class="col-sm-9">
        <input type="text" id="f-user_phone" name="user[email]" class="form-control email required">
        <span class="glyphicon glyphicon-remove form-control-feedback form-control-feedback-error"></span>
        <span class="glyphicon glyphicon-ok form-control-feedback form-control-feedback-ok"></span>
        <p class="help-block"></p>
      </div>
    </div>

    <div class="form-group">
      <label class="col-sm-3 control-label" for="f-user_password">
        {$l.password} <abbr title="campo obbligatorio">∗</abbr>
      </label>
      <div class="col-sm-9">
        <input type="password" id="f-user_password" name="user[password]" class="form-control text required">
        <span class="glyphicon glyphicon-remove form-control-feedback form-control-feedback-error"></span>
        <span class="glyphicon glyphicon-ok form-control-feedback form-control-feedback-ok"></span>
        <p class="help-block"></p>
      </div>
    </div>

    <div class="form-group">
      <label class="col-sm-3 control-label" for="f-password_confirm">
        {$l.account_password_confirm} <abbr title="campo obbligatorio">∗</abbr>
      </label>
      <div class="col-sm-9">
        <input type="password" id="f-password_confirm" name="password_confirm" class="form-control text required" autocomplete="off">
        <span class="glyphicon glyphicon-remove form-control-feedback form-control-feedback-error"></span>
        <span class="glyphicon glyphicon-ok form-control-feedback form-control-feedback-ok"></span>
        <p class="help-block"></p>
      </div>
    </div>

    <div class="form-group">
      <div class="col-sm-9 col-sm-push-3">
        <img src="{$synPublicPath}/lib/syncaptcha/synCaptcha.php?width=300&amp;height=80&amp;characters=5&amp;use_dict=1&amp;lines=1&amp;noise=1"
          width="300" height="80" class="thumbnail" alt="">
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-3 control-label" for="f-password_confirm">
        {$l.captcha} <abbr title="campo obbligatorio">∗</abbr>
      </label>
      <div class="col-sm-9">
        <input type="text" name="captcha" id="fcaptcha" class="form-control text required" />
        <span class="glyphicon glyphicon-remove form-control-feedback form-control-feedback-error"></span>
        <span class="glyphicon glyphicon-ok form-control-feedback form-control-feedback-ok"></span>
        <p class="help-block"></p>
      </div>
    </div>

  </fieldset>
  <fieldset id="fs3">
    <div class="form-group">
      <label class="col-sm-3 control-label">{$l.privacy}</label>
      <div class="col-sm-9">
        <pre style="white-space:pre-line">{$l.informativa_privacy}</pre>
        <div class="checkbox">
          <label>
            <input type="checkbox" value="1" class="required" name="privacy">{$l.informativa}</label>
        </div>
      </div>
    </div>

    <div class="form-group">
      <div class="col-sm-offset-3 col-sm-9">
        <input type="hidden" value="create" name="cmd">
        <input type="hidden" value="{$return_page}" name="ret">
        <button class="btn btn-success" type="submit">{$l.salva}</button>
        <button class="btn btn-default btn-sm" type="reset">{$l.annulla}</button>
      </div>
    </div>
  </fieldset>
</form>
{capture name=pageScript}
<script src="{$synPublicPath}/js/vendor/jquery-1.11.0.min.js"></script>
<script src="{$synPublicPath}/js/vendor/bootstrap.min.js"></script>
<script src="{$synPublicPath}/js/jquery.validate.min.js"></script>
<script type="text/javascript">
  $(document).ready(function(){
    $.validator.messages.required = "{$l.error_required_field}";
    $.validator.messages.email = "{$l.error_invalid_email}";
    $.validator.messages.remote = "{$l.verifica_valore}";
    $.validator.messages.equalTo = "{$l.reinserire_stesso_valore}";
    $("#sign_up_form").validate({
      highlight: function(element) {
        $(element).closest('.form-group').addClass('has-error has-feedback');
      },
      unhighlight: function(element) {
        $(element).closest('.form-group').removeClass('has-error').addClass('has-success');
      },
      errorElement: 'span',
      errorClass: 'help-block',
      errorPlacement: function(error, element) {
          if(element.parent('.input-group').length) {
              error.insertAfter(element.parent());
          } else {
              error.insertAfter(element);
          }
      },
      rules:{
        captcha: { required:true, remote:"{$synPublicPath}/server/validate_captcha.php" },
        password_confirm: { equalTo: "#f-user_password" }
      }
    });
  });
</script>
{/capture}{assign pageScript $smarty.capture.pageScript scope="parent"}