<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang='en' xml:lang='en' xmlns="http://www.w3.org/1999/xhtml"  style="background-image:url('')">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>{t}Sales Overview{/t}</title>
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
<input type="hidden" value="{$conf_data.sales.currency}" id="sales_currency" />
<input type="hidden" value="{$conf_data.sales.type}" id="sales_type" />
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
<input type="hidden" id="period_title_1w" value="{t}1 Week{/t} <span style='font-style:italic'>({t}Since{/t} {'- 7 days'|date_format:'%a %x'})</span>" />
<input type="hidden" id="period_title_10d" value="{t}10 Days{/t} <span style='font-style:italic'>({t}Since{/t} {'- 10 days'|date_format:'%a %x'})</span>" />
<input type="hidden" id="period_title_1m" value="{t}1 Month{/t} <span style='font-style:italic'>({t}Since{/t} {'- 1 month'|date_format:'%x'})</span>" />
<input type="hidden" id="period_title_1q" value="{t}1 Quarter{/t} <span style='font-style:italic'>({t}Since{/t} {'- 3 month'|date_format:'%x'})</span>" />
<input type="hidden" id="period_title_6m" value="{t}6 Months{/t} <span style='font-style:italic'>({t}Since{/t} {'- 6 month'|date_format:'%x'})</span>" />
<input type="hidden" id="period_title_1y" value="{t}1 Year{/t} <span style='font-style:italic'>({t}Since{/t} {'- 1 year'|date_format:'%x'})</span>" />


<input type="hidden" id="period_title_3y" value="{t}3 Years{/t} <span style='font-style:italic'>({t}Since{/t} {'- 3 years'|date_format:'%x'})</span>" />



<input type="hidden" id="period" value="{$conf_data.sales.period}" />
<div id="block_table">
	<div id="title" class="title" style="height:22px">
		<div class="home_splinter_options" style="float:right;font-size:80%;">
			<span id="ytd" class="{if $conf_data.sales.period=='ytd'}selected{/if}" style="margin-right:2px">{t}YTD{/t}</span> <span id="mtd" class="{if $conf_data.sales.period=='mtd'}selected{/if}" style="margin-right:2px">{t}MTD{/t}</span> <span id="wtd" class="{if $conf_data.sales.period=='wtd'}selected{/if}" style="margin-right:2px">{t}WTD{/t}</span> <span id="today" class="{if $conf_data.sales.period=='today'}selected{/if}" style="margin-right:2px">{t}today{/t}</span> <span id="yesterday" class="{if $conf_data.sales.period=='yesterday'}selected{/if}" style="margin-right:2px">{t}yesterday{/t}</span> <span id="last_w" class="{if $conf_data.sales.period=='last_w'}selected{/if}" style="margin-right:2px">{t}last w{/t}</span> <span id="last_m" class="{if $conf_data.sales.period=='last_m'}selected{/if}" style="margin-right:2px">{t}last m{/t}</span> <span id="3y" class="{if $conf_data.sales.period=='3y'}selected{/if}" style="margin-right:2px">{t}3y{/t}</span> <span id="1y" class="{if $conf_data.sales.period=='1y'}selected{/if}" style="margin-right:2px">{t}1y{/t}</span> <span id="6m" class="{if $conf_data.sales.period=='6m'}selected{/if}" style="margin-right:2px">{t}6m{/t}</span> <span id="1q" class="{if $conf_data.sales.period=='1q'}selected{/if}" style="margin-right:2px">{t}1q{/t}</span> <span id="1m" class="{if $conf_data.sales.period=='1m'}selected{/if}" style="margin-right:2px">{t}1m{/t}</span> <span id="10d" class="{if $conf_data.sales.period=='10d'}selected{/if}" style="margin-right:2px">{t}10d{/t}</span> <span id="1w" class="{if $conf_data.sales.period=='1w'}selected{/if}" style="margin-right:10px">{t}1w{/t}</span> 

			<span id="type_sales_representative" class="{if $conf_data.sales.type=='sales_representative'}selected{/if}" style="margin-right:2px;display:none">{t}SRep{/t}</span> 

			<span id="type_stores" class="{if $conf_data.sales.type=='stores'}selected{/if}" style="margin-right:2px">{t}Stores{/t}</span> 
			<span id="type_invoice_categories" class="{if $conf_data.sales.type=='invoice_categories'}selected{/if}" style="margin-right:10px">{t}Categories{/t}</span> <span id="currency_stores" class="{if $conf_data.sales.currency=='store'}selected{/if}" style="margin-right:0px">(£$€)</span> <span id="currency_corporate" class="{if $conf_data.sales.currency=='corporate'}selected{/if}" style="margin-right:2px">[£]</span> 
		</div>
		<h1>
			{t}Overview Sales{/t} 
		</h1>
	</div>
	
	<div style="text-align:left;margin-right:18px;border:1px solid #e7e7e7;padding:5px;margin-bottom:10px;width:918px">
		<span style="position:relative;top:-4px;font-style:italic;padding-left:4px;font-size:80%" id="period_title"></span> 
		{include file='table_splinter.tpl' table_id=0 filter_name='' filter_value='' no_filter=1} 
		<div id="table0" class="data_table_container dtable btable with_total">
		</div>
	</div>
</div>
</body>
</html>
