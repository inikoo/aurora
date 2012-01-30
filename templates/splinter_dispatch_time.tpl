<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang='en' xml:lang='en' xmlns="http://www.w3.org/1999/xhtml">
<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>{t}Sales Overview{/t}</title>
    <link href="art/inikoo-icon.png" rel="shortcut icon" type="image/x-icon" />
    {foreach from=$css_files item=i }
    <link rel="stylesheet" href="{$i}" type="text/css" />
    {/foreach}	

    <link rel="stylesheet" href="css/print.css" type="text/css" media="print"/>

    {foreach from=$js_files item=i }
    <script type="text/javascript" src="{$i}"></script>
    {/foreach}
    {if isset($script)}<script type="text/javascript">{$script}</script>{/if}
        
  </head>

  <body class="yui-skin-sam inikoo">

<div id="block_table">

<div  id="title" class="title" style="height:22px">
<img id="configuration" style="display:none;cursor:pointer;position:relative;top:3px;float:right" src="art/icons/cog.png"/>
<h1 style="padding:3px 0px ;font-size:90%">{t}Average Dispatch Time{/t}: <span  id="stores_title">{$scope->code}</span></h1>

</div>
<div style="margin-top:5px;float:left;border:1px solid #ccc;padding:20px;font-size:40px;font-weight:800;width:210px">
	{$scope->get_formated_dispatch_time('1 Month')} {t}hrs{/t}
</div>

<div style="clear:both"></div>
</body>
</html>
 
 