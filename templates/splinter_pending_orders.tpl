<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang='en' xml:lang='en' xmlns="http://www.w3.org/1999/xhtml" style="background-image:url('')">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>{t}Pending Orders{/t}</title>
	<link href="art/inikoo_logo_small.png" rel="shortcut icon" type="image/x-icon" />
	{foreach from=$css_files item=i } 
	<link rel="stylesheet" href="{$i}" type="text/css" />
	{/foreach} 
	<link rel="stylesheet" href="css/print.css" type="text/css" media="print" />
	{foreach from=$js_files item=i } <script type="text/javascript" src="{$i}"></script> {/foreach} {if isset($script)}<script type="text/javascript">{$script}</script>{/if} 
<base target="_parent" />
</head>
<body class="yui-skin-sam inikoo">
<input type="hidden" id="parent" value="{$parent}">
<input type="hidden" id="parent_key" value="{$parent_key}">

<div id="block_table">
	<div id="title" class="title" style="height:22px">
		<div id="pending_orders_store_chooser" class="home_splinter_options" style="float:right;font-size:80%;">
			<span id="pending_orders_all_stores" onClick="change_pending_orders_store(0)" class="option {if $all_selected}selected{/if}" style="margin-right:2px" title="{t}All stores{/t}">&#8704;</span> 
			{foreach from=$stores item=store} 
			<span id="pending_orders_store_{$store.id}" onClick="change_pending_orders_store({$store.id})" class="option {if $store.selected}selected{/if}" style="margin-right:2px" title="{$store.name}">{$store.code}</span> {/foreach} 
		</div>
		<h1>
			{t}Pending Orders{/t} 
		</h1>
	</div>
	<div style="border:1px solid #ccc;border-top:none;padding:5px 10px 0px 10px">
		<table class="edit" border="1" style="width:100%">
			<tr>
				<td style="text-align:center;width:25%"> 
				<h2>
					{t}In Basket{/t}
				</h2>
				<div class="number_orders" id="in_basket_number_orders"></div> 
				<div>
				&lang;<span class="avg_age" id="in_basket_avg_age"></span>&rang;  {literal}{{/literal}<span class="avg_processing_time" id="in_basket_avg_processing_time"></span>{literal}}{/literal}
				</div>
				<div>
				<span class="sum_total_balance" id="in_basket_sum_total_balance"></span>  &lang;<span class="avg_total_balance" id="in_basket_avg_total_balance"></span>&rang; 
				</div>
				
				
				<table>
					<tr>
						<td></td>
					</tr>
				</table>
				</td>
				<td style="text-align:center;width:25%"> 	
				<h2>{t}In Process{/t}</h2> 
				<div class="number_orders">
				<span  id="in_process_number_orders"></span><span class="in_process_internal">+<span  id="in_process_internal_number_orders"></span>=<span id="in_process_total_orders"></span></span>
				</div>
				<div>
				&lang;<span class="avg_age" id="in_process_avg_age"></span>&rang;  {literal}{{/literal}<span class="avg_processing_time" id="in_process_avg_processing_time"></span>{literal}}{/literal}
				</div>
				<div>
				<span class="sum_total_balance" id="in_process_sum_total_balance"></span>  &lang;<span class="avg_total_balance" id="in_process_avg_total_balance"></span>&rang; 
				</div>
				
				</td>
				<td style="text-align:center;width:25%"> <h2>{t}In Warehouse{/t}</h2>
				
				<div class="number_orders">
				<span  id="in_warehouse_number_orders"></span> 
				</div>
				<div>
				&lang;<span class="avg_age" id="in_warehouse_avg_age"></span>&rang;  {literal}{{/literal}<span class="avg_processing_time" id="in_warehouse_avg_processing_time"></span>{literal}}{/literal}
				</div>
				<div>
				<span class="sum_total_balance" id="in_warehouse_sum_total_balance"></span>  &lang;<span class="avg_total_balance" id="in_warehouse_avg_total_balance"></span>&rang; 
				</div>
				
				</td>
				<td style="text-align:center;width:25%"> <h2>{t}Packed{/t}</h2>
				
				<div class="number_orders">
				<span  id="packed_number_orders"></span> 
				</div>
				<div>
				&lang;<span class="avg_age" id="packed_avg_age"></span>&rang;  {literal}{{/literal}<span class="avg_processing_time" id="packed_avg_processing_time"></span>{literal}}{/literal}
				</div>
				<div>
				<span class="sum_total_balance" id="packed_sum_total_balance"></span>  &lang;<span class="avg_total_balance" id="packed_avg_total_balance"></span>&rang; 
				</div>
				
				</td>
			</tr>
		</table>
	</div>
	<div style="clear:both">
	</div>
</div>
</body>
</html>
