<!DOCTYPE html>
<html lang="{$smarty.session.synSiteLangInitial}">
{news}{include file="partial/_head.tpl"}
<body>
  {include file="partial/_header.tpl"}

  <!-- Page Content -->
  <div class="container">

    <!-- Intro Content -->
    {if isset($item)}
      {include file="partial/_news_detail.tpl"}
    {else}
      {include file="partial/_news_list.tpl"}
    {/if}
    <!-- /.row -->

    {include file="partial/_footer.tpl"}
  </div>
  <!-- /.container -->

  {include file="partial/_scripts.tpl"}
</body>
</html>