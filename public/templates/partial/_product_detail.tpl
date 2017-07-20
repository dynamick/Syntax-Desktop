{product}
{if !empty($product)}
  <div class="row">
    <div class="col-lg-12">
      <h1 class="page-header">
        {$product.titolo} <small>{$synPageTitle}</small>
      </h1>
      {include file="partial/_breadcrumbs.tpl"}
    </div>
  </div>
  <div class="row">
    <div class="col-lg-8">
      {if !empty($product.src)}
        <div class="img-products{if $product.src|@count > 1} owl-carousel owl-theme dots-on-image{/if}">
          {foreach $product.src as $img}
            <div class="item">
              <figure>
                <img class="img-responsive img-thumbnail" src="{thumb src=$img.src w=900 h=700 zc=1}" alt="{$item.alt}" />
              </figure>
            </div>
          {/foreach}
        </div>
      {/if}
      {if !empty($product.descrizione)}
        <hr>
        <div class="rich-text">
          {$product.descrizione}
        </div>
      {/if}
      {if !empty($product.allegato)}
        <hr>
        <a class="btn btn-primary" href="{$product.allegato}" target="_blank"> <i class="fa fa-download"></i> Scarica allegato</a>
      {/if}
    </div>

    <div class="col-lg-4">
    {include file="partial/_sidebar.tpl"}
    </div>
  </div>
{/if}