{include file="_header.tpl"}

    <section class="main-section" id="{$synPageSlug}">
      <div class="wrapper">
        <hgroup class="page-header2">
          <h1>{$synPageTitle}</h1>
        </hgroup>
        
        <footer class="page-footer">
          <div class="searchform">
            <form action="" method="post">
              <input type="text" name="q" placeholder="Cerchi qualcosa?" tabindex="1"/>
              <button type="submit">Ok</button>
            </form>
          </div>

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
  

{include file='_footer.tpl'}