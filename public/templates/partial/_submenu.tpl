{function name=render_menu level=1}
  <ul class="menu lv{$level}">
  {foreach $items as $item}
    <li>
      <a href="{$item.link}"{if $item.is_url} target="_blank"{/if} class="{if $item.active}active{/if}">
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