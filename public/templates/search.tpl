<!DOCTYPE html>
<html lang="{$synLangInitial}">
{include file="partial/_head.tpl"}
<body>
  {include file="partial/_header.tpl"}


  <!-- Page Content -->
  <div class="container">

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

    <!-- Intro Content -->
    <div class="row">
      <div class="col-md-12">
        {search maxitems=10}
        <div class="jumbotron">
          {include file='partial/_searchform.tpl'}
        </div>
        <span class="results-number">Hai cercato <mark>{$needle}</mark>, trovati {$found} risultati.</span>
        <hr>
        {foreach $items as $item}
        <div class="inner-results">
          <h3><a href="{$item.url}">{$item.title}</a></h3>
          <p class="text-muted">{$item.permalink}&lrm;</p>
          <p>{$item.abstract}</p>
          <ul class="list-inline down-ul">
            <li>
              <ul class="list-inline star-vote">
                <li><i class="color-green fa fa-star"></i></li>
                <li><i class="color-green fa fa-star"></i></li>
                <li><i class="color-green fa fa-star"></i></li>
                <li><i class="color-green fa fa-star"></i></li>
                <li><i class="color-green fa fa-star-half-o"></i></li>
              </ul>
            </li>
            <li><a>{$item.type}</a></li>
          </ul>
        </div>
        <hr>
        {/foreach}

        {if !empty($pagination)}
        <div class="text-center">
          <ul class="pagination pagination-lg">
          {foreach from=$pagination item=p}
            {$p}
          {/foreach}
          </ul>
        </div>
        {/if}

      </div>
    </div>
    <!-- /.row -->

    <hr>

    <!-- Call to Action Section -->
    <div class="well">
      <div class="row">
        <div class="col-md-8">
          <p class="lead">Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
          <p>Molestias, expedita, saepe, vero rerum deleniti beatae veniam harum neque nemo praesentium.</p>
        </div>
        <div class="col-md-4">
          <a class="btn btn-lg btn-default btn-block" href="#">Call to Action</a>
        </div>
      </div>
    </div>

    {include file="partial/_footer.tpl"}

  </div>
  <!-- /.container -->

  {include file="partial/_scripts.tpl"}
</body>
</html>