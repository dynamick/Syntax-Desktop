      <div class="row">
        <div class="col-lg-12">
          <h1 class="page-header">
            {$item.title} <small>{$synPageTitle}</small>
          </h1>
          {include file="partial/_breadcrumbs.tpl"}
        </div>
      </div>

      <div class="row">
        <div class="col-lg-8">
          <hr>
          {if $item.social_share neq ''}
          <div class="pull-right">
            <ul class="list-inline">
            {foreach $item.social_share as $social}
              <li>
                <a href="{$social.link}" target="_blank" title="{$social.title}">
                  <i class="fa fa-2x fa-{$social.icon}"></i>
                </a>
              </li>
            {/foreach}
            </ul>
          </div>{/if}
          <p>
            <i class="fa fa-clock-o"></i>
            Posted on <time datetime="{$item.date}">{$item.fdate}</time>
          </p>
          {if $item.src}<hr>
          <figure>
            <img class="img-responsive img-thumbnail" src="{$synPublicPath}/thumb.php?src={$item.src}&amp;w=900&amp;h=300&amp;zc=1" alt="{$item.alt}" />
          </figure>{/if}

          <hr>

          <div class="rich-text">
          {$item.text}
          </div>

          <hr>

          <ul class="pager">
          {foreach from=$item.navlinks item=nav}
            <li class="{if $nav.type eq 'prev'}previous{else}next{/if}">
              <a href="{$nav.url}">
                {if $nav.type eq 'prev'}<i class="fa fa-arrow-left"></i>{/if}
                {$nav.title}
                {if $nav.type eq 'next'}<i class="fa fa-arrow-right"></i>{/if}
              </a>
            </li>
          {/foreach}
          </ul>
        </div>

        <div class="col-lg-4">
        {include file="partial/_sidebar.tpl"}
        </div>
      </div>
