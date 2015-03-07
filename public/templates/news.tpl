<!DOCTYPE html>
<html lang="{$smarty.session.synSiteLangInitial}">
{news}{include file="partial/_head.tpl"}
<body>
  {include file="partial/_header.tpl"}

  <div class="section">
    <!-- Page Content -->
    <div class="container">
    {if isset($item)}
      {include file="partial/_news_detail.tpl"}
    {else}
      {include file="partial/_news_list.tpl"}
    {/if}
    </div>
    <!-- /.container -->
  </div>
  {include file="partial/_cta.tpl"}
  {include file="partial/_footer.tpl"}
  {include file="partial/_scripts.tpl"}
</body>
</html>