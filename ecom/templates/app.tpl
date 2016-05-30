<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 30 May 2016 at 14:10:01 CEST, Mijas Costa, Spain
 Copyright (c) 2016, Inikoo

 Version 3
-->


<html lang="en">
<head>
  <title>Bootstrap Example</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  {if $_DEVEL} 
  <link rel="stylesheet" href="css/bootstrap.3.0.0.css">
  <link rel="stylesheet" href="css/font-awesome.css" > 
  <link rel="stylesheet" href="css/structures.css" > 
  <link rel="stylesheet" href="css/paneltools.css" />
  <link rel="stylesheet" href="css/app.css" />

  
  <script type="text/javascript" src="/js/libs/jquery-2.2.1.js"></script> 
  <script type="text/javascript" src="/js/libs/bootstrap.js"></script> 
   <script type="text/javascript" src="/js/app.js"></script>
  
  <link href='http://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
  <link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:200,300,400,600,700' rel='stylesheet' type='text/css'>

  
  {else}
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
  {/if}
  

</head>

<body class="common-home page-home layout-fullwidth">
<input type="hidden" id="_request" value="{$_request}">

<div class="row-offcanvas row-offcanvas-left">
	<div id="page">


<div id="header"></div>
<div id="content"></div>

<footer id="footer"></footer>


  </div>
  <div class="sidebar-offcanvas  visible-xs visible-sm">
  </div>
</div>

</body>
</html>
