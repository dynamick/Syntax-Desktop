<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>{if isset($smarty.get.title)}{$smarty.get.title|replace:'-':' '|capitalize} > {/if}{$synPageTitle|htmlspecialchars} > Syntax Demo Site</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width">
    <meta name="author" content="info@syntaxdesktop.com">
    <meta name="robots" content="index, follow">
{if isset($smarty.get._next_page) && $smarty.get._next_page neq ''}
    <link rel="canonical" href="/{$smarty.get.parent}/" />
{/if}    
		<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:300,800">
    <link rel="stylesheet" href="{$synPublicPath}/css/normalize.min.css">
    <link rel="stylesheet" href="{$synPublicPath}/css/colorbox.css">
    <link rel="stylesheet" href="{$synPublicPath}/css/custom.css">

    <script src="{$synPublicPath}/js/vendor/modernizr-2.6.2.min.js"></script>
  </head>
  <body>
    <!--[if lt IE 7]>
        <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
    <![endif]-->

		<header class="main-header" role="banner">
      <div class="wrapper">
        <div class="hgroup">
          <h1><a class="logo" href="#">{* $synWebsiteTitle *} Syntax Demo</a></h1>
          <h2>A Demo site for SyntaxDesktop CMS</h2>
        </div>
        
        <nav role="navigation">
          {menu|indent:10 includeParent=false}
        </nav>
        {* <div id="lang">{lang}</div> *}
      </div>
		</header>
