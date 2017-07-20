<!DOCTYPE html>
<html lang="{$synLangInitial}">
{include file="partial/_head.tpl"}
<body>
  {include file="partial/_header.tpl"}

  <!-- Page Content -->
  <div class="section">
    <div class="container">

      <!-- Page Heading/Breadcrumbs -->
      <div class="row">
        <div class="col-lg-12">
          <h1 class="page-header">
            {$synPageTitle}
            <small>Subheading</small>
          </h1>
          {include file="partial/_breadcrumbs.tpl"}
        </div>
      </div>
      <!-- /.row -->

      <!-- Intro Content -->
      <div class="row">
        <div class="col-md-8">
          <div class="rich-text">
          {$synPageText}
          </div>
{pageGallery}
          {if !empty($pagePhotos)}
            <hr>
            <div class="row">
            {foreach from=$pagePhotos item=p}
              <div class="col-xs-6 col-md-3 text-center">
                <a href="{$p.src}" class="thumbnail popup" title="{$p.alt}">
                  <img class="img-responsive img-hover" src="{thumb src=$p.src w=300 h=300 zc=1}" alt="{$p.alt}" />
                </a>
              </div>
              {if ($k+1) % 4 == 0}
            </div><div class="row">
              {/if}
            {/foreach}
            </div>
          {/if}
          
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

        </div>
        <div class="col-md-4">
        {include file='partial/_sidebar.tpl'}
        </div>
      </div>
      <!-- /.row -->
    </div>
  </div>
  <!-- /.container -->

  {include file="partial/_cta.tpl"}
  {include file="partial/_footer.tpl"}
  {include file="partial/_scripts.tpl"}
</body>
</html>
