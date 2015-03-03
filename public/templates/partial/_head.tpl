<head>{meta}
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="{$meta_description}">
  <meta name="keywords" content="{$meta_keywords}" />
  <meta name="author" content="">
{if $meta_hreflang neq ''}
  {foreach $meta_hreflang as $link}<link rel="alternate" hreflang="{$link.lang}" href="{$link.url}" />{/foreach}
{/if}
  {$meta_canonical}
  <title>{$meta_title}</title>

  <!-- Bootstrap Core CSS -->
  <link href="{$synPublicPath}/css/vendor/bootstrap.min.css" rel="stylesheet" type="text/css">
  <!-- Custom Fonts -->
  <link href="{$synPublicPath}/css/vendor/font-awesome.min.css" rel="stylesheet" type="text/css">
  <!-- Custom CSS -->
  <link href="{$synPublicPath}/css/modern-business.css" rel="stylesheet" type="text/css">
  <link href="{$synPublicPath}/css/syntax.css" rel="stylesheet" type="text/css">

<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
  <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
</head>