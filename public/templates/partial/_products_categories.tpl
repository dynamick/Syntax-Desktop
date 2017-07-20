{products_categories}
{if !empty($products_categories)}
  <div class="row">
    <div class="col-lg-12">
      <h1 class="page-header">
        {$synPageTitle}
      </h1>
      {include file="partial/_breadcrumbs.tpl"}
    </div>
  </div>  
  <div class="row">  
    {foreach $products_categories as $cp}
      <div class="col-md-4">
        {if !empty($cp.src)}
          <a href="{$cp.url}">
            <img class="img-responsive img-thumbnail" src="{thumb src=$cp.src w=800 h=500 zc=1}" alt="{$cp.alt}">
          </a>
        {/if}
        {if !empty($cp.titolo)}
          <h3>{$cp.titolo}</h3>
        {/if}
        {if !empty($cp.descrizione)}
          {$cp.descrizione}
          <br>
          <br>
        {/if}
        <a class="btn btn-primary" href="{$cp.url}">Scopri categoria</a>
      </div>
    {/foreach}
  </div>
  {if !empty($pagination)}
    <ul class="pagination">
    {foreach from=$pagination item=p}{$p}{/foreach}
    </ul>
  {/if}
{/if}