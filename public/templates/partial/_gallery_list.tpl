  <!-- Page Heading/Breadcrumbs -->
  <div class="row">
    <div class="col-lg-12">
      <h1 class="page-header">
        {$synPageTitle}
      </h1>
      {include file="partial/_breadcrumbs.tpl"}
    </div>
  </div>
  <!-- /.row -->

  <div class="row">
  {foreach from=$albums key=k item=a}
    <div class="col-md-4 img-portfolio">
        <a href="{$a.url}">
          <img class="img-responsive img-hover" src="{thumb src=$a.src w=700 h=400 zc=1}" alt="{$a.alt}" />
        </a>
        <h3>
          <a href="{$a.url}">{$a.title}</a>
        </h3>
        <small class="text-muted">{$a.fdate}</small>
    </div>
    {if ($k+1) % 3 == 0}
  </div><div class="row">
    {/if}
  {foreachelse}
    {include 'partial/_alert.tpl' msg='Non sono disponibili album.'}
  {/foreach}
  </div>

  {if !empty($pagination)}
    <ul class="pagination">
    {foreach from=$pagination item=p}
      {$p}
    {/foreach}
    </ul>
  {/if}