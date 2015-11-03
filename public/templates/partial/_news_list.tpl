  <div class="row">
    <div class="col-lg-12">
      <h1 class="page-header">{$synPageTitle}</h1>
      {include file="partial/_breadcrumbs.tpl"}
    </div>
  </div>

{foreach $news item=n}
  <article class="row">
    <div class="col-md-4">
      <a href="{$n.url}" class="thumbnail">
        <img src="{thumb src=$n.src w=600 h=300 zc=1}" alt="{$n.alt}" class="img-responsive">
      </a>
    </div>
    <div class="col-md-8">
      <h3>
        <a href="{$n.url}">{$n.title} </a>
      </h3>
      {if $n.social_share neq ''}
      <div class="pull-right">
        <ul class="list-inline">
        {foreach $n.social_share as $social}
          <li>
            <a href="{$social.link}" target="_blank" title="{$social.title}">
              <i class="fa fa-2x fa-{$social.icon}"></i>
            </a>
          </li>
        {/foreach}
        </ul>
      </div>{/if}
      <p>
        <i class="fa fa-calendar-o"></i>
        <time datetime="{$n.date}">{$n.fdate}</time>
      </p>
      <p>{$n.abstract}</p>
      <a class="btn btn-primary" href="{$n.url}">
        Read More
        <i class="fa fa-angle-right"></i>
      </a>
    </div>
  </article>
  <hr>
{foreachelse}
  {include 'partial/_alert.tpl' msg='Non sono disponibili notizie.'}
{/foreach}

{if !empty($pagination)}
  <ul class="pagination">
  {foreach from=$pagination item=p}{$p}{/foreach}
  </ul>
{/if}