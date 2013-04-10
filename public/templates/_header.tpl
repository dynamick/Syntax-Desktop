<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it" lang="it">
  <head profile="http://www.w3.org/2005/10/profile">
    <title>{if isset($smarty.get.title)}{$smarty.get.title|replace:'-':' '|capitalize} > {/if}{$synPageTitle|htmlspecialchars} > Syntax Demo Site</title>
    <meta http-equiv="content-type"         content="text/html; charset=utf-8" />
    <meta http-equiv="content-language"     content="{$smarty.session.synSiteLangInitial}" />
    <meta http-equiv="imagetoolbar"         content="no" />
    <meta http-equiv="msthemecompatible"    content="yes" />
    <meta http-equiv="content-script-type"  content="text/javascript" />
    <meta name="author"                     content="info@syntaxdesktop.com" />
    <meta name="robots"                     content="index, follow" />
    <link type="image/vnd.microsoft.icon"   href="/favicon.ico" rel="shortcut icon" />
    <link type="image/png"                  href="/favicon.png" rel="icon" />
    <link type="text/css"                   href="{$synPublicPath}/css/style.css" rel="stylesheet" media="screen" />
    <script type="text/javascript"          src="{$synPublicPath}/js/monolith.php"></script>
{if isset($smarty.get._next_page) && $smarty.get._next_page neq ''}
    <link rel="canonical"                   href="/{$smarty.get.parent}/" />
{/if}
  </head>
  <body>
    <div id="wrapper">
    <div id="innerwrapper">
      <div id="header">
        <form action="">
          <input value="Search" tabindex="10"/>
        </form>
        <h1><a href="/">SyntaxDesktop</a></h1>
        <h2>A Demo site for <a href="http://www.syntaxdesktop.com">SyntaxDesktop CMS</a></h2>
{menu|indent:10}

{if $synPageLevel neq 0}
 {submenu|indent:10 startPage=$synPageNode1}
{/if}
        <div id="lang">
{lang|indent:10 attr="height=10"}
        </div>

        <ul id="servicemenu">
{if !isset($smarty.cookies.web_user) || $smarty.cookies.web_user eq ""}
          <li><a href="/account/">{trad label="login"}</a></li>
          <li><a href="/account/?action=reg">{trad label="registrati"}</a></li>
{else}
          <li>{trad label="benvenuto"}! <a href="/public/server/setcookies.php?act=logoff">{trad label="logout"}</a></li>
{/if}
        </ul>
      </div>
