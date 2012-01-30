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
<input type="hidden" value="0" id="sales_index"  />
<input type="hidden" value="{$conf_data.sales.currency}" id="sales_currency"  />
<input type="hidden" value="{$conf_data.sales.type}" id="sales_type"  />
<input type="hidden" value="{$block_key}" id="block_key"  />

<input type="hidden" value="{t}Store{/t}" id="label_Store"  />
<input type="hidden" value="{t}Invoices{/t}" id="label_Invoices"  />
<input type="hidden" value="% {t}Invoices{/t}" id="label_Invoices_Share"  />
<input type="hidden" value="&Delta;{t}Last Yr Invoices{/t}" id="label_Invoices_Delta"  />

<input type="hidden" value="{t}Sales{/t}" id="label_Sales"  />
<input type="hidden" value="% {t}Sales{/t}" id="label_Sales_Share"  />
<input type="hidden" value="&Delta;{t}Last Yr Sales{/t}" id="label_Sales_Delta"  />

<input type="hidden" id="period_title_ytd" value="{t}Year-to-Date{/t} <span style='font-style:italic'>({$smarty.now|date_format:'%Y'})</span>"/>
<input type="hidden" id="period_title_mtd" value="{t}Month-to-Date{/t} <span style='font-style:italic'>({$smarty.now|date_format:'%B %Y'})</span>"/>
<input type="hidden" id="period_title_wtd" value="{t}Week-to-Date{/t}"/>
<input type="hidden" id="period_title_today" value="{t}Today{/t} <span style='font-style:italic'>({$smarty.now|date_format:'%a %x'})</span>" />
<input type="hidden" id="period_title_yesterday" value="{t}Yesterday{/t} <span style='font-style:italic'>({'- 1 days'|date_format:'%a %x'})</span>"/>
<input type="hidden" id="period_title_last_w" value="{t}Last Week{/t}"/>
<input type="hidden" id="period_title_last_m" value="{t}Last Month{/t} <span style='font-style:italic'>({'- 1 month'|date_format:'%B %Y'})</span>"/>
<input type="hidden" id="period_title_3y" value="{t}3 Years{/t} <span style='font-style:italic'>({t}Since{/t} {'- 3 years'|date_format:'%x'})</span>"/>
<input type="hidden" id="period_title_1y" value="{t}1 Year{/t} <span style='font-style:italic'>({t}Since{/t} {'- 1 year'|date_format:'%x'})</span>"/>
<input type="hidden" id="period_title_1q" value="{t}1 Quarter{/t} <span style='font-style:italic'>({t}Since{/t} {'- 3 month'|date_format:'%x'})</span>"/>

<input type="hidden" id="period_title_1m" value="{t}1 Month{/t} <span style='font-style:italic'>({t}Since{/t} {'- 1 month'|date_format:'%x'})</span>"/>
<input type="hidden" id="period_title_10d" value="{t}10 Days{/t} <span style='font-style:italic'>({t}Since{/t} {'- 10 days'|date_format:'%a %x'})</span>"/>
<input type="hidden" id="period" value="{$conf_data.sales.period}"/>


<div id="block_table">

<div  id="title" class="title" style="height:22px">
<img id="configuration" style="cursor:pointer;position:relative;top:3px;float:right" src="art/icons/cog.png"/>
<h1>{t}Overview Sales{/t}, <span  id="period_title"></span></h1>
</div>

 <div id="block_options" class="block_options" style="display:none;height:22px">
     <div style="float:left;margin-top:2px;;margin-left:10px;" class="buttons left small">
            <button id="ytd"  {if $conf_data.sales.period=='ytd'}class="selected"{/if} style="margin-right:2px" >{t}YTD{/t}</button>
            <button id="mtd" {if $conf_data.sales.period=='mtd'}class="selected"{/if} style="margin-right:2px" >{t}MTD{/t}</button>
            <button id="wtd" {if $conf_data.sales.period=='wtd'}class="selected"{/if} style="margin-right:2px" >{t}WTD{/t}</button>
            <button id="today"  {if $conf_data.sales.period=='today'}class="selected"{/if} style="margin-right:2px" >{t}today{/t}</button>
            <button id="yesterday" {if $conf_data.sales.period=='yesterday'}class="selected"{/if} style="margin-right:2px" >{t}yesterday{/t}</button>
            <button id="last_w"  {if $conf_data.sales.period=='last_w'}class="selected"{/if} style="margin-right:2px" >{t}last w{/t}</button>
            <button id="last_m"  {if $conf_data.sales.period=='last_m'}class="selected"{/if} style="margin-right:2px" >{t}last m{/t}</button>
            <button id="3y" {if $conf_data.sales.period=='3y'}class="selected"{/if} style="margin-right:2px" >{t}3y{/t}</button>
            <button id="1y" {if $conf_data.sales.period=='1y'}class="selected"{/if} style="margin-right:2px" >{t}1y{/t}</button>
            <button id="1q"  {if $conf_data.sales.period=='1q'}class="selected"{/if} style="margin-right:2px" >{t}1q{/t}</button>
            <button id="1m" {if $conf_data.sales.period=='1m'}class="selected"{/if} style="margin-right:2px" >{t}1m{/t}</button>
            <button id="10d" {if $conf_data.sales.period=='10d'}class="selected"{/if} style="margin-right:2px" >{t}10d{/t}</button>
            <button id="1w" {if $conf_data.sales.period=='1w'}class="selected"{/if} style="margin-right:2px" >{t}1w{/t}</button>
</div>
 
    <div style="float:left;margin-top:2px;;;margin-left:10px;" class="buttons left small">
    <button id="type_stores"  {if $conf_data.sales.type=='stores'}class="selected"{/if} style="margin-right:2px" >{t}Stores{/t}</button>
    <button id="type_invoice_categories"  {if $conf_data.sales.type=='invoice_categories'}class="selected"{/if} style="margin-right:2px" >{t}Categories{/t}</button>
</div>

    <div style="float:left;margin-top:2px;;margin-left:10px;" class="buttons left small">
            <button id="currency_corporate"  {if $conf_data.sales.currency=='corporate'}class="selected"{/if} style="margin-right:2px" >{t}Corporate Currency{/t}</button>
            <button id="currency_stores"  {if $conf_data.sales.currency=='store'}class="selected"{/if} style="margin-right:2px" >{t}Store Currencies{/t}</button>
</div>

  <div style="float:right;margin-top:2px;" class="buttons  small">
            <button class="positive" id="done" style="margin:0" >{t}Done{/t}</button>
</div>

 </div>


<div style="border:1px solid #ccc;border-top:none">
       
        {include file='table_splinter.tpl' table_id=0 filter_name='' filter_value='' no_filter=1}
        <div  id="table0"   class="data_table_container dtable btable with_total"> </div>
</div>
</div>

</body>
</html>