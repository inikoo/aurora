{*} Autor: Raul Perusquia <raul@inikoo.com> Copyright (c) 2009, Inikoo Version 2.0 Created: 25 November 2013 11:15:52 GMT {*} 
<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang='en' xml:lang='en' xmlns="http://www.w3.org/1999/xhtml" style="background-image:url('')">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>{t}Out of stock{/t}</title>
	<link href="art/inikoo_logo_small.png" rel="shortcut icon" type="image/x-icon" />
	{foreach from=$css_files item=i } 
	<link rel="stylesheet" href="{$i}" type="text/css" />
	{/foreach} 
	<link rel="stylesheet" href="css/print.css" type="text/css" media="print" />
	{foreach from=$js_files item=i } <script type="text/javascript" src="{$i}"></script> {/foreach} {if isset($script)}<script type="text/javascript">{$script}</script>{/if} 
	<base target="_parent" />
</head>
<body class="yui-skin-sam inikoo">
<input type="hidden" value="0" id="sales_index" />
<input type="hidden" value="{$block_key}" id="block_key" />
<input type="hidden" value="{t}Store{/t}" id="label_Store" />
<input type="hidden" value="{t}Invoices{/t}" id="label_Invoices" />
<input type="hidden" value="% {t}Invoices{/t}" id="label_Invoices_Share" />
<input type="hidden" value="&Delta;{t}Last Yr Invoices{/t}" id="label_Invoices_Delta" />
<input type="hidden" value="{t}Sales{/t}" id="label_Sales" />
<input type="hidden" value="% {t}Sales{/t}" id="label_Sales_Share" />
<input type="hidden" value="&Delta;{t}Last Yr Sales{/t}" id="label_Sales_Delta" />
<input type="hidden" id="period_title_ytd" value="{t}Year-to-Date{/t} <span style='font-style:italic'>({$smarty.now|date_format:'%Y'})</span>" />
<input type="hidden" id="period_title_mtd" value="{t}Month-to-Date{/t} <span style='font-style:italic'>({$smarty.now|date_format:'%B %Y'})</span>" />
<input type="hidden" id="period_title_wtd" value="{t}Week-to-Date{/t}" />
<input type="hidden" id="period_title_today" value="{t}Today{/t} <span style='font-style:italic'>({$smarty.now|date_format:'%a %x'})</span>" />
<input type="hidden" id="period_title_yesterday" value="{t}Yesterday{/t} <span style='font-style:italic'>({'- 1 days'|date_format:'%a %x'})</span>" />
<input type="hidden" id="period_title_last_w" value="{t}Last Week{/t}" />
<input type="hidden" id="period_title_last_m" value="{t}Last Month{/t} <span style='font-style:italic'>({'- 1 month'|date_format:'%B %Y'})</span>" />
<input type="hidden" id="period_title_3y" value="{t}3 Years{/t} <span style='font-style:italic'>({t}Since{/t} {'- 3 years'|date_format:'%x'})</span>" />
<input type="hidden" id="period_title_1y" value="{t}1 Year{/t} <span style='font-style:italic'>({t}Since{/t} {'- 1 year'|date_format:'%x'})</span>" />
<input type="hidden" id="period_title_6m" value="{t}6 Months{/t} <span style='font-style:italic'>({t}Since{/t} {'- 6 month'|date_format:'%x'})</span>" />
<input type="hidden" id="period_title_1q" value="{t}1 Quarter{/t} <span style='font-style:italic'>({t}Since{/t} {'- 3 month'|date_format:'%x'})</span>" />
<input type="hidden" id="period_title_1m" value="{t}1 Month{/t} <span style='font-style:italic'>({t}Since{/t} {'- 1 month'|date_format:'%x'})</span>" />
<input type="hidden" id="period_title_10d" value="{t}10 Days{/t} <span style='font-style:italic'>({t}Since{/t} {'- 10 days'|date_format:'%a %x'})</span>" />
<input type="hidden" id="period_title_1w" value="{t}1 Week{/t} <span style='font-style:italic'>({t}Since{/t} {'- 7 days'|date_format:'%a %x'})</span>" />
<input type="hidden" id="to" value="{$to}" />
<input type="hidden" id="from" value="{$from}" />
<input type="hidden" id="period" value="{$period}" />
<div id="block_table">
	<div id="title" class="title" style="height:22px">
		<div class="home_splinter_options" style="float:right;font-size:80%;">
			<span id="ytd" class="{if $conf_data.out_of_stock.period=='ytd'}selected{/if}" style="margin-right:2px">{t}YTD{/t}</span> <span id="mtd" class="{if $conf_data.out_of_stock.period=='mtd'}selected{/if}" style="margin-right:2px">{t}MTD{/t}</span> <span id="wtd" class="{if $conf_data.out_of_stock.period=='wtd'}selected{/if}" style="margin-right:2px">{t}WTD{/t}</span> <span id="today" class="{if $conf_data.out_of_stock.period=='today'}selected{/if}" style="margin-right:2px">{t}today{/t}</span> <span id="yesterday" class="{if $conf_data.out_of_stock.period=='yesterday'}selected{/if}" style="margin-right:2px">{t}yesterday{/t}</span> <span id="last_w" class="{if $conf_data.out_of_stock.period=='last_w'}selected{/if}" style="margin-right:2px">{t}last w{/t}</span> <span id="last_m" class="{if $conf_data.out_of_stock.period=='last_m'}selected{/if}" style="margin-right:2px">{t}last m{/t}</span> <span id="1w" class="{if $conf_data.out_of_stock.period=='1w'}selected{/if}" style="margin-right:0px">{t}1w{/t}</span> <span id="10d" class="{if $conf_data.out_of_stock.period=='10d'}selected{/if}" style="margin-right:2px">{t}10d{/t}</span> <span id="1m" class="{if $conf_data.out_of_stock.period=='1m'}selected{/if}" style="margin-right:2px">{t}1m{/t}</span> <span id="1q" class="{if $conf_data.out_of_stock.period=='1q'}selected{/if}" style="margin-right:2px">{t}1q{/t}</span> <span id="6m" class="{if $conf_data.out_of_stock.period=='6m'}selected{/if}" style="display:none;margin-right:2px">{t}6m{/t}</span> <span id="1y" class="{if $conf_data.out_of_stock.period=='1y'}selected{/if}" style="margin-right:2px">{t}1y{/t}</span> <span id="3y" class="{if $conf_data.out_of_stock.period=='3y'}selected{/if}" style="margin-right:2px">{t}3y{/t}</span> 
		</div>
		<h1>
			{t}Out of stock{/t} 
		</h1>
	</div>
	
	
	<div style="text-align:left;margin-right:18px;border:1px solid #e7e7e7;padding:5px;margin-bottom:10px;width:603px">
		<span style="position:relative;top:-4px;font-style:italic;padding-left:4px;font-size:80%" id="period_label">{$period_label}</span> 
		<div style="clean:both;width:100%;min-height:50px;font-size:80%;text-align:center;padding:10px 0">
			<a id="link_parts" href="report_out_of_stock.php?period={$period}&block=parts" target="_parent"> 
		<div style="margin-left:0px;border:1px solid #777;float:left;width:110px;padding:5px 0px">
			{t}Out of Stock Parts{/t} 
			<div id="number_out_of_stock_parts" style="font-size:120%;font-weight:800;margin-top:5px;margin-bottom:5px">
				<span style="visibility:hidden">1</span><img src="art/loading.gif" style="height:14px"><span style="visibility:hidden">1</span> 
			</div>
		</div>
		</a> <a id="link_transactions" href="report_out_of_stock.php?period={$period}&block=transactions" target="_parent"> 
	<div style="margin-left:10px;border:1px solid #777;float:left;width:110px;padding:5px 0px">
		{t}Transactions Afected{/t} 
		<div id="number_out_of_stock_transactions" style="font-size:120%;font-weight:800;margin-top:5px;margin-bottom:5px">
			<span style="visibility:hidden">1</span><img src="art/loading.gif" style="height:14px"><span style="visibility:hidden">1</span> 
		</div>
	</div>
	</a> 
	<a id="link_orders" href="report_out_of_stock.php?period={$period}&block=orders" target="_parent"> 
<div style="margin-left:10px;border:1px solid #777;float:left;width:110px;padding:5px 0px">
{t}Orders Affected{/t} 
<div id="number_out_of_stock_orders" style="font-size:120%;font-weight:800;margin-top:5px;margin-bottom:5px">
	<span style="visibility:hidden">1</span><img src="art/loading.gif" style="height:14px"><span style="visibility:hidden">1</span> 
</div>
</div>
</a> <a id="link_customers" href="report_out_of_stock.php?period={$period}&block=customers" target="_parent"> 
<div style="margin-left:10px;border:1px solid #777;float:left;width:110px;padding:5px 0px">
{t}Customers Affected{/t} 
<div id="number_out_of_stock_customers" style="font-size:120%;font-weight:800;margin-top:5px;margin-bottom:5px">
	<span style="visibility:hidden">1</span><img src="art/loading.gif" style="height:14px"><span style="visibility:hidden">1</span> 
</div>
</div>
</a> <a id="link_revenue" href="report_out_of_stock.php?period={$period}&block=transactions" target="_parent"> 
<div style="margin-left:10px;border:1px solid #777;float:left;width:110px;padding:5px 0px">
{t}Revenue Affected{/t} 
<div id="lost_revenue" style="font-size:120%;font-weight:800;margin-top:5px;margin-bottom:5px">
	<span style="visibility:hidden">1</span><img src="art/loading.gif" style="height:14px"><span style="visibility:hidden">1</span> 
</div>
</div>
</a> 
</div>
</div>
</div>
</body>
</html>
