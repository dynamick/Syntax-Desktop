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
