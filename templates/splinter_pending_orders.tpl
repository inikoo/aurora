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
<input type="hidden" id="period_title_1m" value="{t}1 Month{/t} <span style='font-style:italic'>({t}Since{/t} {'- 1 month'|date_format:'%x'})</span>"/>
<input type="hidden" id="period_title_10d" value="{t}10 Days{/t} <span style='font-style:italic'>({t}Since{/t} {'- 10 days'|date_format:'%a %x'})</span>"/>
<input type="hidden" id="period" value="{$conf_data.sales.period}"/>


<div id="block_table">

<div  id="title" class="title" style="height:22px">
<img id="configuration" style="cursor:pointer;position:relative;top:3px;float:right" src="art/icons/cog.png"/>
<h1>{t}Pending Orders{/t}, <span  id="period_title"></span></h1>
</div>
<div style="border:1px solid #ccc;padding:20px;font-size:40px;font-weight:800;width:100px">
{$number_pending_orders}
</div>


</body>
</html>
 
 