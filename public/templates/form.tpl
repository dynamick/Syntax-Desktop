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
        </h1>
        {include file="partial/_breadcrumbs.tpl"}
      </div>
    </div>
    <!-- /.row -->

    <!-- Intro Content -->
    <div class="row">
      <div class="col-md-8">
      {form page=$synPageId}
      {append var=synPageScript value=$pageScript scope=parent}
      </div>

      <div class="col-md-4">
      {include file="partial/_sidebar.tpl"}
      </div>
    </div>
    <!-- /.row -->

    {include file="partial/_footer.tpl"}

  </div>
  <!-- /.container -->

  {include file="partial/_scripts.tpl"}
</body>
</html>