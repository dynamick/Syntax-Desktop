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
        <img src="{$synPublicPath}/thumb.php?src={$n.src}&amp;w=600&amp;h=300&amp;zc=1" alt="{$n.alt}" class="img-responsive">
      </a>
    </div>
    <div class="col-md-8">
      <h3>
        <a href="{$n.url}">{$n.title} </a>
      </h3>
      <div class="pull-right">
        <ul class="list-inline">
          <li><a href="#"><i class="fa fa-2x fa-facebook-square"></i></a></li>
          <li><a href="#"><i class="fa fa-2x fa-linkedin-square"></i></a></li>
          <li><a href="#"><i class="fa fa-2x fa-twitter-square"></i></a></li>
        </ul>
      </div>
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