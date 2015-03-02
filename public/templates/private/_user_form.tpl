{trad varname='l' label='nome|cognome|account_save|account_reset|error_required_field|error_invalid_email|verifica_valore|reinserire_stesso_valore|account_exit'}
<form action="{$synPublicPath}/server/account_helper.php"
  class="form-horizontal" method="POST" id="form_edit_user">
  <fieldset>
    <div id="legend">
      <legend class=""></legend>
    </div>

    <div class="form-group">
      <label class="col-sm-3 control-label" for="f-user_name">
        {$l.nome}  <abbr title="campo obbligatorio">∗</abbr>
      </label>
      <div class="col-sm-9">
        <input type="text" id="f-user_name" name="user[name]" class="form-control text required" value="{$user.name}">
        <p class="help-block"></p>
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-3 control-label" for="f-user_surname">
        {$l.cognome}  <abbr title="campo obbligatorio">∗</abbr>
      </label>
      <div class="col-sm-9">
        <input type="text" id="f-user_surname" name="user[surname]" class="form-control text required" value="{$user.surname}">
        <p class="help-block"></p>
      </div>
    </div>

    <div class="form-group">
      <div class="col-sm-offset-3 col-sm-9">
        <input type="hidden" value="update" name="cmd">
        <input type="hidden" value="{$user.id}" name="user_id">
        <input type="hidden" value="{$user.hashed_id}" name="user_token">
        <input type="hidden" value="{$return_page}" name="ret">
        <button class="btn btn-success" type="submit">
          {$l.account_save}
        </button>
        <button class="btn btn-default btn-sm" type="reset">
          {$l.account_reset}
        </button>
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
    $("#form_edit_user").validate();
  });

</script>
{/capture}{assign pageScript $smarty.capture.pageScript scope="parent"}