<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang='en' xml:lang='en' xmlns="http://www.w3.org/1999/xhtml" style="background-image:url('')">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>{t}Top Products{/t}</title>
	<link href="art/inikoo_logo_small.png" rel="shortcut icon" type="image/x-icon" />
	{foreach from=$css_files item=i } 
	<link rel="stylesheet" href="{$i}" type="text/css" />
	{/foreach} 
	<link rel="stylesheet" href="css/print.css" type="text/css" media="print" />
	{foreach from=$js_files item=i } <script type="text/javascript" src="{$i}"></script> {/foreach} {if isset($script)}<script type="text/javascript">{$script}</script>{/if} 
	<base target="_parent" />
</head>
<body class="yui-skin-sam inikoo" >

<input type="hidden" value="{$block_key}" id="block_key" />
<input type="hidden" value="{$top_products_index}" id="top_products_index" />
<input type="hidden" value="{$top_products_nr}" id="top_products_nr" />
<input type="hidden" value="{$top_products_type}" id="top_products_type" />
<input type="hidden" value="{$top_products_period}" id="top_products_period" />
<input type="hidden" value="{$store_keys}" id="store_keys" />
<input type="hidden" value="{t}Fam{/t}" id="label_Fam" />
<input type="hidden" value="{t}Description{/t}" id="label_Product" />
<input type="hidden" value="{t}Sales{/t}" id="label_Sales" />
<input type="hidden" value="{t}Description{/t}" id="label_Description" />
<input type="hidden" value="{t}Stock{/t}" id="label_Stock" />

<div id="block_table">
<div id="title" class="title" style="height:22px">
	
	<div class="home_splinter_options" style="font-size:80%;">
		<span id="top_parts_categories" type="parts_categories" class="{if $conf_data.top_products.type=='parts_categories'}selected {/if}" style="float:right;margin-left:5px">{t}Part Groups{/t}</span> 
		<span id="top_parts" type="parts" class="{if $conf_data.top_products.type=='parts'}selected {/if}" style="float:right;margin-left:5px">{t}Parts{/t}</span> 
		<span id="top_products_fam" type="families" class="{if $conf_data.top_products.type=='families'}selected {/if}" style="float:right;margin-left:5px">{t}Families{/t}</span> 
		<span id="top_products_products" type="products" class="{if $conf_data.top_products.type=='products'}selected {/if}" style="float:right;margin-left:15px">{t}Products{/t}</span> 
		<span id="top_products_100" nr="100" class="{if $conf_data.top_products.nr==50}selected {/if}" style="float:right;margin-left:5px">100</span> 
		<span id="top_products_50" nr="50" class="{if $conf_data.top_products.nr==50}selected {/if}" style="float:right;margin-left:5px">50</span> 
		<span id="top_products_20" nr="20" class="{if $conf_data.top_products.nr==20}selected {/if}" style="float:right;margin-left:5px">20</span> 
		<span id="top_products_10" nr="10" class="{if $conf_data.top_products.nr==10}selected {/if}" style="float:right;margin-left:15px">10</span> 
		<span id="top_products_1w" period="1w" class="{if $conf_data.top_products.period=='1w'}selected{/if}" style="float:right;margin-left:7px">{t}1w{/t}</span> 
		<span id="top_products_10d" period="10d" class="{if $conf_data.top_products.period=='10d'}selected{/if}" style="float:right;margin-left:7px">{t}10d{/t}</span> 
		<span id="top_products_1m" period="1m" class="{if $conf_data.top_products.period=='1m'}selected{/if}" style="float:right;margin-left:7px">{t}1m{/t}</span> 
		<span id="top_products_1q" period="1q" class="{if $conf_data.top_products.period=='1q'}selected{/if}" style="float:right;margin-left:7px">{t}1q{/t}</span> 
		<span id="top_products_6m" period="6m" class="{if $conf_data.top_products.period=='6m'}selected{/if}" style="float:right;margin-left:7px">{t}6m{/t}</span> 
		<span id="top_products_1y" period="1y" class="{if $conf_data.top_products.period=='1y'}selected{/if}" style="float:right;margin-left:7px">{t}1y{/t}</span> 
		<span id="top_products_3y" period="3y" class="{if $conf_data.top_products.period=='3y'}selected{/if}" style="float:right;margin-left:7px">{t}3y{/t}</span> <span id="top_products_last_m" period="last_m" class="{if $conf_data.top_products.period=='last_m'}selected{/if}" style="float:right;margin-left:7px">{t}last m{/t}</span> <span id="top_products_last_w" period="last_w" class="{if $conf_data.top_products.period=='last_w'}selected{/if}" style="float:right;margin-left:7px">{t}last w{/t}</span> <span id="top_products_yesterday" period="yesterday" class="{if $conf_data.top_products.period=='yesterday'}selected{/if}" style="float:right;margin-left:7px">{t}yesterday{/t}</span> <span id="top_products_today" period="today" class="{if $conf_data.top_products.period=='today'}selected{/if}" style="float:right;margin-left:7px">{t}today{/t}</span> <span id="top_products_wtd" period="wtd" class="{if $conf_data.top_products.period=='wtd'}selected{/if}" style="float:right;margin-left:7px">{t}WTD{/t}</span> <span id="top_products_mtd" period="mtd" class="{if $conf_data.top_products.period=='mtd'}selected{/if}" style="float:right;margin-left:7px">{t}MTD{/t}</span> <span id="top_products_ytd" period="ytd" class="{if $conf_data.top_products.period=='ytd'}selected{/if}" style="float:right;margin-left:7px">{t}YTD{/t}</span> <span id="top_products_all" period="all" class="{if $conf_data.top_products.period=='all'}selected{/if}" style="float:right;margin-left:7px">{t}All{/t}</span> 
	</div>

	<h1 id="title_products" style="{if $conf_data.top_products.type!='products'}display:none{/if}">
		{t}Top Products{/t}
	</h1>
	<h1 id="title_families" style="{if $conf_data.top_products.type!='families'}display:none{/if}">
		{t}Top Families{/t}
	</h1>
	<h1 id="title_parts" style="{if $conf_data.top_products.type!='parts'}display:none{/if}">
		{t}Top Parts{/t}
	</h1>
	<h1 id="title_parts_categories" style="{if $conf_data.top_products.type!='parts_categories'}display:none{/if}">
		{t}Top Parts Groups{/t}
	</h1>
</div>

<div style="float:left;width:450px;margin-right:18px;border:1px solid #e7e7e7;padding:5px;margin-bottom:10px;width:918px">
	<div id="the_table" class="data_table" style="font-size:85%">
		<div style="float:left;margin-right:10px;width:450px">
			{include file='table_splinter.tpl' table_id=1 filter_name=$filter_name filter_value=$filter_value no_filter=1 hide_caption=1} 
			<div id="table1" class="data_table_container dtable btable" style="margin-top:5px">
			</div>
		</div>
		<div style="float:left;margin-left:5px;padding-top:20px" id="plot_orders">
			<strong>You need to upgrade your Flash Player</strong> 
		</div>
<script type="text/javascript">
		// <![CDATA[		
		var so = new SWFObject("external_libs/ampie/ampie/ampie.swf", "ampie", "450", "575", "1", "#FFFFFF");
		so.addVariable("path", "external_libs/ampie/ampie/");
		so.addVariable("settings_file", encodeURIComponent("conf/pie_settings.xml.php"));                // you can set two or more different settings files here (separated by commas)
		{if $conf_data.top_products.type=='families'}
		so.addVariable("data_file", encodeURIComponent("plot_data.csv.php?tipo=top_families&store_keys={$store_keys}&period={$conf_data.top_products.period}&nr={$conf_data.top_products.nr}")); 
		{elseif $conf_data.top_products.type=='products'}
		so.addVariable("data_file", encodeURIComponent("plot_data.csv.php?tipo=top_products&store_keys={$store_keys}&period={$conf_data.top_products.period}&nr={$conf_data.top_products.nr}")); 
		{elseif $conf_data.top_products.type=='parts'}
		so.addVariable("data_file", encodeURIComponent("plot_data.csv.php?tipo=top_parts&period={$conf_data.top_products.period}&nr={$conf_data.top_products.nr}")); 
{elseif $conf_data.top_products.type=='parts_categories'}
		so.addVariable("data_file", encodeURIComponent("plot_data.csv.php?tipo=top_parts_categories&period={$conf_data.top_products.period}&nr={$conf_data.top_products.nr}")); 

		{/if}


		so.addVariable("loading_settings", "LOADING SETTINGS");                                         // you can set custom "loading settings" text here
		so.addVariable("loading_data", "LOADING DATA");                                                 // you can set custom "loading data" text here
		so.addVariable("chart_id", "ampie");
		so.write("plot_orders");
		
		// ]]>
	</script> 
	</div>
</div>
</div>
</body>
</html>
