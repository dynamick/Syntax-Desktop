    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="index.html">Modern Business</a>
        </div>
{function name=render_main_menu level=1 parent=0}
          <ul {if $level eq 1}class="nav navbar-nav navbar-right"{else}class="dropdown-menu lv{$level}" aria-labelledby="drop{$parent}"{/if}>
{foreach $items as $item}{if isset($item.child) && count($item.child) > 0}{if $level eq 1}
            <li class="dropdown{if $item.active} active{/if}">
              <a href="{$item.link}" data-toggle="dropdown" class="dropdown-toggle" role="button" id="drop{$item.id}">{$item.title} <b class="caret"></b></a>
{else}
            <li class="dropdown-submenu{if $item.active} active{/if}">
              <a href="{$item.link}" data-toggle="dropdown" class="dropdown-toggle" role="button" id="drop{$item.id}">{$item.title}</a>
{/if}            
{if isset($item.child) && count($item.child) > 0}{call name=render_main_menu items=$item.child level=$level+1 parent=$item.id}{/if}
            </li>
{else}
            <li{if $item.active} class="active"{/if}>
              <a href="{$item.link}"{if $item.is_url} target="_blank"{/if}>{$item.title}{if $item.is_url} <img src={$synPublicPath}/img/link_site.gif alt="External Site" />{/if}</a>
            </li>
{/if}{/foreach}
          </ul>
{/function}

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse navbar-ex1-collapse">
          {submenu startPage=22 includeParent=true expand=true}
          {call render_main_menu items=$submenu}
        </div>
        <!-- /.navbar-collapse -->
      </div>
      <!-- /.container -->
    </nav>