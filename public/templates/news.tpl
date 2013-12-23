{include file="partial/_header.tpl"}
{news}

    <section class="main-section" id="{$synPageSlug}">
      <div class="wrapper">
        <hgroup class="page-header2">
          <h1>{$title|default:$synPageTitle}</h1>
          {$tagline}
        </hgroup>
        
        <footer class="page-footer">
{include file='partial/_searchform.tpl'}

          <nav class="breadcrumb">
            {breadcrumb}
          </nav>          
        </footer>          
        
        {$output}
        
        <aside class="sidebar">
          <div class="box">
            <h3 class="menu-header">{pageInfo page=$synPageNode1 info='title'}</h3>
{include file='partial/_submenu.tpl'}
          </div>          
          <div>
            <script src="http://www.ohloh.net/p/345/widgets/project_factoids_stats.js"></script>
          </div>
        </aside>      
      </div>
    </section>
  

{include file='partial/_footer.tpl'}