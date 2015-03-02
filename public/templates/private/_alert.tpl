<div class="row">
  <div class="col-xs-12">
    <div class="alert alert-{$flash.type|default:'warning'}">
      <button type="button" class="close" data-dismiss="alert">&times;</button>
      {$flash.message|default:'errore'}
    </div>
  </div>
</div>
