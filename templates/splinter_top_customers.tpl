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
<body class="yui-skin-sam inikoo">
<input type="hidden" value="{$block_key}" id="block_key" />
<input type="hidden" value="{$top_customers_index}" id="top_customers_index" />
<input type="hidden" value="{$top_customers_nr}" id="top_customers_nr" />
<input type="hidden" value="{t}Customer Name{/t}" id="label_Customer_Name" />
<input type="hidden" value="{t}Last Order{/t}" id="label_Last_Order" />
<input type="hidden" value="{t}Invoices{/t}" id="label_Invoices" />
<input type="hidden" value="{t}Balance{/t}" id="label_Balance" />
<input type="hidden" value="{t}Status{/t}" id="label_Status" />
<input type="hidden" value="{t}Location{/t}" id="label_Location" />

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
<input type="hidden" id="period_title_all" value="{t}All{/t}" />


<input type="hidden" id="period" value="{$conf_data.top_customers.period}" />


<div id="block_table">
<div id="title" class="title" style="height:22px">
	<div class="home_splinter_options" style="font-size:80%;">
		<span id="top_customers_100" nr="100" class="{if $conf_data.top_customers.nr==100}selected{/if}" style="float:right;margin-left:5px">100</span> <span id="top_customers_50" nr="50" class="{if $conf_data.top_customers.nr==50}selected{/if}" style="float:right;margin-left:5px">50</span> <span id="top_customers_20" nr="20" class="{if $conf_data.top_customers.nr==20}selected{/if}" style="float:right;margin-left:5px">20</span> <span id="top_customers_10" nr="10" class="{if $conf_data.top_customers.nr==10}selected{/if}" style="float:right;margin-left:15px">10</span> <span id="top_customers_1w" period="1w" class="{if $conf_data.top_customers.period=='1w'}selected{/if}" style="float:right;margin-left:7px">{t}1w{/t}</span> <span id="top_customers_10d" period="10d" class="{if $conf_data.top_customers.period=='10d'}selected{/if}" style="float:right;margin-left:7px">{t}10d{/t}</span> <span id="top_customers_1m" period="1m" class="{if $conf_data.top_customers.period=='1m'}selected{/if}" style="float:right;margin-left:7px">{t}1m{/t}</span> <span id="top_customers_1q" period="1q" class="{if $conf_data.top_customers.period=='1q'}selected{/if}" style="float:right;margin-left:7px">{t}1q{/t}</span> <span id="top_customers_6m" period="6m" class="{if $conf_data.top_customers.period=='6m'}selected{/if}" style="float:right;margin-left:7px">{t}6m{/t}</span> <span id="top_customers_1y" period="1y" class="{if $conf_data.top_customers.period=='1y'}selected{/if}" style="float:right;margin-left:7px">{t}1y{/t}</span> <span id="top_customers_3y" period="3y" class="{if $conf_data.top_customers.period=='3y'}selected{/if}" style="float:right;margin-left:7px">{t}3y{/t}</span> <span id="top_customers_last_m" period="last_m" class="{if $conf_data.top_customers.period=='last_m'}selected{/if}" style="float:right;margin-left:7px">{t}last m{/t}</span> <span id="top_customers_last_w" period="last_w" class="{if $conf_data.top_customers.period=='last_w'}selected{/if}" style="float:right;margin-left:7px">{t}last w{/t}</span> <span id="top_customers_yesterday" period="yesterday" class="{if $conf_data.top_customers.period=='yesterday'}selected{/if}" style="float:right;margin-left:7px">{t}yesterday{/t}</span> <span id="top_customers_today" period="today" class="{if $conf_data.top_customers.period=='today'}selected{/if}" style="float:right;margin-left:7px">{t}today{/t}</span> <span id="top_customers_wtd" period="wtd" class="{if $conf_data.top_customers.period=='wtd'}selected{/if}" style="float:right;margin-left:7px">{t}WTD{/t}</span> <span id="top_customers_mtd" period="mtd" class="{if $conf_data.top_customers.period=='mtd'}selected{/if}" style="float:right;margin-left:7px">{t}MTD{/t}</span> <span id="top_customers_ytd" period="ytd" class="{if $conf_data.top_customers.period=='ytd'}selected{/if}" style="float:right;margin-left:7px">{t}YTD{/t}</span> <span id="top_customers_all" period="all" class="{if $conf_data.top_customers.period=='all'}selected{/if}" style="float:right;margin-left:7px">{t}All{/t}</span> 
	</div>
	<h1 id="title_customers">
		{t}Top Customers{/t}
	</h1>
</div>
<div style="margin-right:18px;border:1px solid #e7e7e7;padding:5px;margin-bottom:10px;width:918px;text-align:left">

	
		<span style="position:relative;top:-4px;font-style:italic;padding-left:4px;font-size:80%" id="period_title"></span> 
			{include file='table_splinter.tpl' table_id=1 filter_name=$filter_name filter_value=$filter_value no_filter=1 hide_caption=1} 
			<div id="table1" class="data_table_container dtable btable" style="margin-top:5px;font-size:85%">
			</div>
		
	
</div>


</div>
</html>
