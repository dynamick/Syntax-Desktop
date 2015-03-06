{trad varname='l' label='account_welcome|account_dati|account_password|account_mail|logout'}
<div class="jumbotron">
  <p>{$l.account_welcome} {$user.name} {$user.surname}</p>
</div>
<div class="btn-group btn-group-lg btn-group-justified" >
  <a class="btn btn-default" href="{pageInfo info='path' page=$synPageId}?cmd=edit">{$l.account_dati}</a>
  <a class="btn btn-default" href="{pageInfo info='path' page=$synPageId}?cmd=change_password">{$l.account_password}</a>
  <a class="btn btn-default" href="{pageInfo info='path' page=$synPageId}?cmd=change_email">{$l.account_mail}</a>
  <a class="btn btn-default btn-danger" href="{$account_helper}?cmd=logout">{$l['logout']}</a>
</div>
