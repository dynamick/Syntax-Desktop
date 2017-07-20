{products}
{if !empty($products)}
  <div class="row">
    <div class="col-lg-12">
      <h1 class="page-header">
        {$category} <small>{$synPageTitle}</small>
      </h1>
      {include file="partial/_breadcrumbs.tpl"}
    </div>
  </div>
  <div class="row">
    {foreach $products as $p}
      <div class="col-md-3">
        {if !empty($p.src)}
          <a href="{$p.url}" alt="{$p.alt}">
            <img class="img-responsive img-thumbnail" src="{thumb src=$p.src w=800 h=500 zc=1}">
          </a>
        {/if}
        {if !empty($p.titolo)}
          <h3>{$p.titolo}</h3>
        {/if}
        {if !empty($p.abstract)}
          <p>{$p.abstract}</p>
        {/if}
        <a class="btn btn-primary" href="{$p.url}">Scopri</a>
      </div>
    {/foreach}
  </div>
{/if}