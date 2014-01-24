<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang='en' xml:lang='en' xmlns="http://www.w3.org/1999/xhtml" style="background-image:url('')">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>{t}Sales Overview{/t}</title>
	<link href="art/inikoo_logo_small.png" rel="shortcut icon" type="image/x-icon" />
	{foreach from=$css_files item=i } 
	<link rel="stylesheet" href="{$i}" type="text/css" />
	{/foreach} 
	<link rel="stylesheet" href="css/print.css" type="text/css" media="print" />
	{foreach from=$js_files item=i } <script type="text/javascript" src="{$i}"></script> {/foreach} {if isset($script)}<script type="text/javascript">{$script}</script>{/if} 
</head>
<body class="yui-skin-sam inikoo">
<div id="block_table">
	<div id="title" class="title" style="height:22px">
		<img id="configuration" style="display:none;cursor:pointer;position:relative;top:3px;float:right" src="art/icons/cog.png" /> 
		<h1 style="padding:3px 0px ;font-size:90%">
			{t}Pending Orders{/t} {$warehouse->get('Warehouse Code')}, (<a href="warehouse_orders.php?id={$warehouse->id}" target='_parent' style="color:#FFF">{$total_pending_orders}</a>) <a href="report_pending_orders.php" target='_parent' style="color:#FFF">{$total_pending_orders_amount}</a></span> 
		</h1>
	</div>
	
	
	<div style="border:1px solid #ccc;border-top:none;padding:5px 10px 0px 10px">
	<table class="edit" >
	{foreach from=$pending_orders_data item=pending_orders}
	<tr>
	<td style="width:90px">{$pending_orders.store}</td>
	<td class="aright">{$pending_orders.number}</td>
	<td style="width:120px" class="aright">{$pending_orders.amount}</td>

	</tr>
	{/foreach}
	</table>
	</div>


<div style="clear:both">
</div>
</div>
</body>
</html>
