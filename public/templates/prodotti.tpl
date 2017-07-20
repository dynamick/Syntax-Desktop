<!DOCTYPE html>
<html lang="{$synLangInitial}">
{include file="partial/_head.tpl"}
<body>
  {include file="partial/_header.tpl"}

  <div class="section">
    <!-- Page Content -->
    <div class="container">
      {if $smarty.get.id|intval > 0}
        {include file="partial/_product_detail.tpl"}
      {elseif ( $smarty.get.idcategoria|intval > 0 && $smarty.get.id|intval  == 0 )}
        {include file="partial/_products_list.tpl"}
      {else}
        {include file="partial/_products_categories.tpl"}
      {/if}
      <!-- /.row -->
    </div>
  </div>
  <!-- /.container -->

  {include file="partial/_cta.tpl"}
  {include file="partial/_footer.tpl"}
  {include file="partial/_scripts.tpl"}
</body>
</html>
