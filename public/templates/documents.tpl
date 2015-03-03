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
        {documents}
        {if $documents.document_count > 0}
          {foreach from=$documents item=category}
            {if count($category.documents) > 0}
              <h4>{$category.name}</h4>
              <hr>
              <div class="row">
              {foreach from=$category.documents item=document}
                <div class="col-sm-6 col-md-4">
                  <div class="thumbnail text-center">
                    <div class="caption">
                      <h3>{$document.title}</h3>
                      <p>{$document.description}</p>
                      <p>
                        <a href="{$document.link}" class="btn btn-primary btn-block">
                          <i class="fa fa-download fa-2x"></i><br>
                          <strong>{$document.ext}</strong>, {$document.size}
                        </a>
                      </p>
                    </div>
                  </div>
                </div>
              {/foreach}
              </div>
            {/if}
          {/foreach}
        {else}
          {include 'partial/_alert.tpl' msg='Nessun elemento disponibile.'}
        {/if}
      </div>

      <div class="col-md-4">
      {include file='partial/_sidebar.tpl'}
      </div>
    </div>
    <!-- /.row -->

    {include file="partial/_footer.tpl"}
  </div>
  <!-- /.container -->

  {include file="partial/_scripts.tpl"}
</body>
</html>