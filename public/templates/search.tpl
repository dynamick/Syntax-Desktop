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
          <p>Hai cercato <mark class="search-result">pippo</mark>, trovate 12 occorrenze.</p>
          <ol class="item-list">
            <li>
              <article>
                <a href="#" class="block">
                  <header>
                    <h1>Titolo <mark class="search-result">Pippo</mark></h1>
                    <time class="item-subheader" datetime="2010-01-24T05:00" pubdate>01 Aprile 2013</time>
                  </header>
                  <p>
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ex sunt consequuntur cum unde praesentium facere natus rem vel totam incidunt at veniam ipsa porro beatae non officia fugiat nemo perferendis. ipsum dolor sit amet. 
                    <span class="follow">continua &rarr;</span>
                  </p>
                </a>
              </article>
            </li>
            <li>
              <article>
                <a href="#" class="block">
                  <header>
                    <h1>Titolo</h1>
                    <time class="item-subheader" datetime="2010-01-24T05:00" pubdate>01 Aprile 2013</time>
                  </header>
                  <p>
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ex sunt consequuntur cum unde praesentium facere natus rem vel totam incidunt at veniam ipsa porro beatae non officia fugiat nemo perferendis. ipsum dolor sit amet. 
                    <span class="follow">continua &rarr;</span>
                  </p>
                </a>
              </article>
            </li>
            <li>
              <article>
                <a href="#" class="block">
                  <header>
                    <h1>Titolo</h1>
                    <time class="item-subheader" datetime="2010-01-24T05:00" pubdate>01 Aprile 2013</time>
                  </header>
                  <p>
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ex sunt consequuntur cum unde praesentium facere natus rem vel totam incidunt at veniam ipsa porro beatae non officia fugiat nemo perferendis. ipsum dolor sit amet. 
                    <span class="follow">continua &rarr;</span>
                  </p>
                </a>
              </article>
            </li>
            <li>
              <article>
                <a href="#" class="block">
                  <header>
                    <h1>Titolo</h1>
                    <span class="item-subheader">path/alla/pagina/</span>
                  </header>
                  <p>
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit. <mark class="search-result">Pippo</mark>Ex sunt consequuntur cum unde praesentium facere natus rem vel totam incidunt at veniam ipsa porro beatae non officia fugiat nemo perferendis. ipsum dolor sit amet. 
                    <span class="follow">continua &rarr;</span>
                  </p>
                </a>
              </article>
            </li>
          </ol>            
          
          <div class="pagination">
            <nav>
              <span class="disabled">&larr;</span>
              <span class="active">1</span>
              <a href="#">2</a>
              <a href="#">3</a>
              <a href="#" class="next">&rarr;</a>
            </nav>
          </div>          
        </div>                
      </div>
    </section>
  

{include file='_footer.tpl'}