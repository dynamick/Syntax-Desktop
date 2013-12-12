{include file="_header.tpl"}

    <section class="main-section" id="{$synPageSlug}">
      <div class="wrapper">
        <header class="page-header2">
          <h1>{$synPageTitle}</h1>
          {lang}
        </header>
        
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
          <div class="rich-text">
          {$synPageText}
          </div>
        </div>
        
        <aside class="sidebar">
          <div class="box">
            <h3 class="menu-header">{pageInfo page=$synPageNode1 info='title'}</h3>
            
{function name=render_menu level=1}
  <ul class="menu lv{$level}">
  {foreach $items as $item}
    <li>
      <a href="{$item.link}" {if $item.is_url}onclick="window.open(this.href); return false;"{/if} class="{if $item.active}active{/if}">
      {$item.title}
      {if $item.is_url}
        <img src={$synPublicPath}/img/link_site.gif alt="External Site" />
      {/if}
      </a>
      {if count($item.child) > 0}
        {call name=render_menu items=$item.child level=$level+1}
      {/if}
    </li>
  {/foreach}
  </ul>
{/function}

{submenu}
{call render_menu items=$submenu}
          </div>          
          <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam sodales urna non odio egestas tempor. Nunc vel vehicula ante. Etiam bibendum iaculis libero, eget molestie nisl pharetra in. In semper consequat est, eu porta velit mollis nec. Curabitur posuere enim eget turpis feugiat tempor. Etiam ullamcorper lorem dapibus velit suscipit ultrices.</p>
          <div>
            <script src="http://www.ohloh.net/p/345/widgets/project_factoids_stats.js"></script>
          </div>
        </aside>      
      </div>
    </section>
  

{include file='_footer.tpl'}