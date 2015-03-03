
<div class="well">
{include file="partial/_searchform.tpl"}
</div>

{function name=render_submenu level=1 parent=0 parent_active=false}
    {if $level eq 1}<ul id="sidebar-nav" class="list-group sidebar-nav-v1 fa-fixed">
    {else}<ul id="collapse-content-{$parent}" class="collapse{if $parent_active} in{/if}">
    {/if}
  {foreach $items as $item}
    {if count($item.child) > 0}
        <li class="list-group-item list-toggle{if $item.active} active{/if}">
            <a class="accordion-toggle" href="#collapse-content-{$item.id}" data-toggle="collapse">{$item.title}</a>
            {call name=render_submenu items=$item.child level=$level+1 parent=$item.id parent_active=$item.active}
        </li>
    {else}
        <li class="{if $level eq 1}list-group-item {/if}{if $item.active}active{/if}">
            <a href="{$item.link}">{$item.title}</a>
        </li>
    {/if}
  {/foreach}
  </ul>
{/function}

{submenu expand=true}
{call render_submenu items=$submenu}

<div>
    <script src="http://www.ohloh.net/p/345/widgets/project_factoids_stats.js"></script>
</div>