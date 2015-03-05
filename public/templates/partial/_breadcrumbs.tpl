{function name=render_breadcrumb}
  <ol class="breadcrumb">
  {foreach $items as $item}{if $item.active}<li class="active">{$item.title}</li>{else}<li><a href="{$item.url}">{$item.title}</a></li>{/if}
  {/foreach}
  </ol>
{/function}

{breadcrumbs base_title='<span class="fa fa-home"></span>'}
{call render_breadcrumb items=$breadcrumbs}