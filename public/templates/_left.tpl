      <div id="sidebar">
{if !isset($smarty.get.id) || $smarty.get.id eq ""}
        <h2>Latest News</h2>
        {news_sidebar}
{/if}
      </div>
