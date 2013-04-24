{include file="_header.tpl"}
{news}

    <section class="main-section" id="{$synPageSlug}">
      <div class="wrapper">
        <hgroup class="page-header2">
          <h1>{$title|default:$synPageTitle}</h1>
          {$tagline}
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
        
        {$output}
        
        <aside class="sidebar">
          <div class="box">
            {submenu}
          </div>          
          <div>
            <script src="http://www.ohloh.net/p/345/widgets/project_factoids_stats.js"></script>
          </div>
        </aside>      
      </div>
    </section>
  

{include file='_footer.tpl'}