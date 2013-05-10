{include file="_header.tpl"}

    <section class="main-section" id="{$synPageSlug}">
      <div class="wrapper">
        <hgroup class="page-header2">
          <h1>{$synPageTitle}</h1>
        </hgroup>
        
        <footer class="page-footer">
          <div class="searchform">
            <form action="{createPath page=57}" method="get">
              <input type="text" name="q" placeholder="Cerchi qualcosa?" tabindex="1"/>
              <button type="submit">Ok</button>
            </form>
          </div>

          <nav class="breadcrumb">
            {breadcrumb}
          </nav>          
        </footer>          
                
        <div class="content">
          {$synPageText}
        </div>
        
        <aside class="sidebar">
          <div class="box">
            {submenu}
          </div>          
          <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam sodales urna non odio egestas tempor. Nunc vel vehicula ante. Etiam bibendum iaculis libero, eget molestie nisl pharetra in. In semper consequat est, eu porta velit mollis nec. Curabitur posuere enim eget turpis feugiat tempor. Etiam ullamcorper lorem dapibus velit suscipit ultrices.</p>
          <div>
            <script src="http://www.ohloh.net/p/345/widgets/project_factoids_stats.js"></script>
          </div>
        </aside>      
      </div>
    </section>
  

{include file='_footer.tpl'}