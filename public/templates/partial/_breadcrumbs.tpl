{function name=render_breadcrumb}
  <ol class="breadcrumb">
  {foreach $items as $item}{if $item.active}<li class="active">{$item.title}</li>{else}<li><a href="{$item.url}">{$item.title}</a></li>{/if}
  {/foreach}
  </ol>
{/function}

{breadcrumbs}
{call render_breadcrumb items=$breadcrumbs}