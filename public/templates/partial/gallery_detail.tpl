  <!-- Page Heading/Breadcrumbs -->
  <div class="row">
    <div class="col-lg-12">
      <div class="page-header">
        <h1>{$item.title} <small>{$synPageTitle}</small></h1>
      </div>
      {include file="partial/_breadcrumbs.tpl"}
    </div>
  </div>
  <!-- /.row -->
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
  <hr>  
  <div class="row">
  {foreach from=$photos key=k item=p}
    <div class="col-xs-6 col-md-3 text-center">
      <a href="{$p.src}" class="thumbnail popup" title="{$p.alt}">
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
  
{capture name='pageScript'}
  <script type="text/javascript">
  $(document).ready(function(){
    $('.popup').colorbox({
        maxWidth:'90%'
      , maxHeight:'90%'
      , slideshow:true
      , slideshowAuto:false
      , slideshowSpeed:4000
      , transition: 'elastic'
      , scrolling: false
      , loop: false
      , rel: 'album{$item.id}'
      , previous : '<i class="fa fa-chevron-left"></i>'
      , next : '<i class="fa fa-chevron-right"></i>'
      , current : '{ldelim}current{rdelim}/{ldelim}total{rdelim}'
      , slideshowStart : '<i class="fa fa-play"></i>'
      , slideshowStop : '<i class="fa fa-pause"></i>'
      , close : '<i class="fa fa-remove"></i>'
    });
  });
  </script>
{/capture}      
{append var=synPageScript value=$smarty.capture.pageScript scope=parent}