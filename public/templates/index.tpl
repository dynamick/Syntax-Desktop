{include file="_header.tpl"}
{include file="_left.tpl"}		
{include file="_right.tpl"}		
		
		<div id="content">
      {if $smarty.get.id eq ""}
        <h2 id="Intro"><a href="#">{$synPageTitle}</a></h2>
        {$synPageText}
      {else}
        {news}
      {/if}
		</div>

{include file='_footer.tpl'}