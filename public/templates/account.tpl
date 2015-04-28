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
    {flashMessage}
    <!-- Intro Content -->
    <div class="row">
      <div class="col-md-12">
        {account}
        {$content}
      </div>
    </div>
    <!-- /.row -->
  </div>
  <!-- /.container -->

  {include file="partial/_footer.tpl"}
  {include file="partial/_scripts.tpl"}
</body>
</html>