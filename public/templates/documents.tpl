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
              <h3 class="page-header" id="document-cat{$group.id}">{$category.name}</h3>
              <div class="row">
              {foreach from=$category.documents key=k item=document}
                <div class="col-sm-4">
                  <div class="panel panel-default text-center">
                    <div class="panel-heading">
                      <strong>{$document.title}</strong>
                    </div>
                    <div class="panel-body">
                      <p>
                        {$document.description}<br />
                        <small class="text-muted"><b>.{$document.ext}</b>, {$document.size}</small>
                      </p>
                      <a href="{$document.link}" class="btn btn-block btn-large btn-success" role="button">
                        <i class="fa fa-download fa-2x"></i><br />
                        Download
                      </a>
                    </div>
                  </div>
                </div>
                {if ($k+1) % 3 == 0}
              </div>
              <div class="row">
                {/if}
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