<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang='en' xml:lang='en' xmlns="http://www.w3.org/1999/xhtml">
<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>{t}Top Products{/t}</title>
    <link href="art/inikoo-icon.png" rel="shortcut icon" type="image/x-icon" />
    {foreach from=$css_files item=i }
    <link rel="stylesheet" href="{$i}" type="text/css" />
    {/foreach}	

    <link rel="stylesheet" href="css/print.css" type="text/css" media="print"/>

    {foreach from=$js_files item=i }
    <script type="text/javascript" src="{$i}"></script>
    {/foreach}
    {if isset($script)}<script type="text/javascript">{$script}</script>{/if}
      <base target="_parent" />
  
  </head>

  <body class="yui-skin-sam inikoo">
  
  
  <input type="hidden" value="{$block_key}" id="block_key"  />
<input type="hidden" value="{$top_customers_index}" id="top_customers_index"  />
<input type="hidden" value="{$top_customers_nr}" id="top_customers_nr"  />

<input type="hidden" value="{t}Customer Name{/t}" id="label_Customer_Name"  />
<input type="hidden" value="{t}Last Order{/t}" id="label_Last_Order"  />
<input type="hidden" value="{t}Invoices{/t}" id="label_Invoices"  />
<input type="hidden" value="{t}Balance{/t}" id="label_Balance"  />


<div  id="title" class="title" style="height:22px">

 <div class="home_splinter_options" style="font-size:80%;">
                       <span id="top_customers_100" nr="100" {if $conf_data.top_customers.nr==100}class="selected"{/if} style="float:right;margin-left:5px">100</span>

           <span id="top_customers_50" nr="50" {if $conf_data.top_customers.nr==50}class="selected"{/if} style="float:right;margin-left:5px">50</span>
            <span id="top_customers_20" nr="20" {if $conf_data.top_customers.nr==20}class="selected"{/if} style="float:right;margin-left:5px">20</span>
            <span id="top_customers_10" nr="10" {if $conf_data.top_customers.nr==10}class="selected"{/if} style="float:right;margin-left:15px">10</span>
            <span id="top_customers_all" period="all" {if $conf_data.top_customers.period=='all'}class="selected"{/if} style="float:right;margin-left:5px">{t}All times{/t}</span>
            <span id="top_customers_1y" period="1y" {if $conf_data.top_customers.period=='1y'}class="selected"{/if} style="float:right;margin-left:5px">{t}1y{/t}</span>
            <span id="top_customers_1q" period="1q" {if $conf_data.top_customers.period=='1q'}class="selected"{/if} style="float:right;margin-left:5px">{t}1q{/t}</span>
            <span id="top_customers_1m" period="1m" {if $conf_data.top_customers.period=='1m'}class="selected"{/if} style="float:right;margin-left:5px">{t}1m{/t}</span>
        </div>
   <h1 id="title_customers" >{t}Top Customers{/t}</h1>     
      
</div>



<div style="float:left;width:600px;margin-right:18px;border:1px solid #e7e7e7;padding:5px;margin-bottom:10px;width:918px">



    <div id="the_table" class="data_table" style="font-size:85%">
    <div style="float:left;margin-right:10px;width:600px">
        
       
        {include file='table_splinter.tpl' table_id=1 filter_name=$filter_name filter_value=$filter_value no_filter=1 hide_caption=1}
        <div  id="table1"   class="data_table_container dtable btable" style="margin-top:5px"> </div>
       </div>

       
        
    </div>
</div>

</html>