{include file="partial/_header.tpl"}

    <section class="main-section" id="{$synPageSlug}">
      <div class="wrapper">
        <hgroup class="page-header2">
          <h1>{$synPageTitle}</h1>
        </hgroup>
        
        <footer class="page-footer">
{include file='partial/_searchform.tpl'}

          <nav class="breadcrumb">
            {breadcrumb}
          </nav>          
        </footer>
        
        <div class="content1">
{search}          
          <!--div class="pagination">
            <nav>
              <span class="disabled">&larr;</span>
              <span class="active">1</span>
              <a href="#">2</a>
              <a href="#">3</a>
              <a href="#" class="next">&rarr;</a>
            </nav>
          </div-->          
        </div>                
      </div>
    </section>
  

{include file='partial/_footer.tpl'}