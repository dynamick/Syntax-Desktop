<!DOCTYPE html>
<html lang="{$smarty.session.synSiteLangInitial}">
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
      </div>
      <div class="col-md-4">
      {include file='partial/_sidebar.tpl'}
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