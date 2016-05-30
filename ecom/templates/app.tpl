{if $_DEVEL}{strip}{/if}
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
  <link rel="stylesheet" href="css/bootstrap.css">
  <script type="text/javascript" src="/js/libs/jquery-2.2.1.js"></script> 
  <script type="text/javascript" src="/js/libs/bootstrap.js"></script> 
  {else}
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
  {/if}
  

</head>
<body>

<div class="container">
  <h1>Header</h1>
  <p>This is some text.</p> 
</div>

</body>
</html>
{if $_DEVEL}{/strip}{/if}
