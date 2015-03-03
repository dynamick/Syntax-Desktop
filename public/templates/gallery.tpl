<!DOCTYPE html>
<html lang="{$smarty.session.synSiteLangInitial}">
{include file="partial/_head.tpl"}
<body>
  {include file="partial/_header.tpl"}


  <!-- Page Content -->
  <div class="container">
    {gallery}
    {if $smarty.get.id neq ''}
      {include file="partial/gallery_detail.tpl"}
    {else}
      {include file="partial/gallery_list.tpl"}
    {/if}

    <hr>

    <!-- Call to Action Section -->
    <div class="well">
      <div class="row">
        <div class="col-md-8">
          <p class="lead">Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
          <p>Molestias, expedita, saepe, vero rerum deleniti beatae veniam harum neque nemo praesentium.</p>
        </div>
        <div class="col-md-4">
          <a class="btn btn-lg btn-default btn-block" href="#">Call to Action</a>
        </div>
      </div>
    </div>

    {include file="partial/_footer.tpl"}

  </div>
  <!-- /.container -->

  {include file="partial/_scripts.tpl"}
</body>
</html>