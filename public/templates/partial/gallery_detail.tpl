  <!-- Page Heading/Breadcrumbs -->
  <div class="row">
    <div class="col-lg-12">
      <h1 class="page-header">
        <h1>{$album.title} <small>{$album.fdate}</small></h1>
      </h1>
      {include file="partial/_breadcrumbs.tpl"}
    </div>
  </div>
  <!-- /.row -->

  <div class="row">
  {foreach from=$photos key=k item=p}
    <div class="col-xs-6 col-md-3 text-center">
      <a href="{$p.src}" class="thumbnail popup">
        <img class="img-responsive img-hover" src="{$synPublicPath}/thumb.php?src={$p.src}&amp;w=300&amp;h=300&amp;zc=1" alt="{$p.alt}" />
      </a>
    </div>
    {if ($k+1) % 4 == 0}
  </div><div class="row">
    {/if}
  {foreachelse}
    {include '_alert.tpl' msg='Non ci sono foto disponibili.'}
  {/foreach}
  </div>
  {append var=synPageScript value=$pageScript scope=parent}