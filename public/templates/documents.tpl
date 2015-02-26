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
                
        <div class="content">
          <div class="rich-text">
          {$synPageText}
          </div>
          {documents}
          {if $documents.document_count > 0}
            {foreach from=$documents item=category}
              {if count($category.documents) > 0}
                <h4>{$category.name}</h4>
                <ul class="item-list">
                  {foreach from=$category.documents item=document}
                    <li>
                      <article>
                        <a href="{$document.link}" class="download">
                          <header>
                            <h1>{$document.title}</h1>
                            <span>{$document.date}</span>
                          </header>
                          <span>{$document.description}</span>
                          <footer class="meta">
                            <strong>{$document.ext}</strong> {$document.size}
                          </footer>
                        </a>
                      </article>
                    </li>   
                  {/foreach}
                </ul>
              {/if}
            {/foreach}
          {else}
            <div class="alert">Nessun elemento disponibile.</div>
          {/if}
        
        <aside class="sidebar">
          <div class="box">
            <h3 class="menu-header">{pageInfo page=$synPageNode1 info='title'}</h3>
{include file='partial/_submenu.tpl'}
          </div>          
          <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam sodales urna non odio egestas tempor. Nunc vel vehicula ante. Etiam bibendum iaculis libero, eget molestie nisl pharetra in. In semper consequat est, eu porta velit mollis nec. Curabitur posuere enim eget turpis feugiat tempor. Etiam ullamcorper lorem dapibus velit suscipit ultrices.</p>
          <div>
            <script src="http://www.ohloh.net/p/345/widgets/project_factoids_stats.js"></script>
          </div>
        </aside>      
      </div>
    </section>
  

{include file='partial/_footer.tpl'}