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
  
  
  <input type="hidden" value="{$block_key}" id="block_key"  />

<input type="hidden" value="{$top_products_index}" id="top_products_index"  />
<input type="hidden" value="{$top_products_nr}" id="top_products_nr"  />
<input type="hidden" value="{$top_products_type}" id="top_products_type"  />
<input type="hidden" value="{$store_keys}" id="store_keys"  />

<input type="hidden" value="{t}Fam{/t}" id="label_Fam"  />
<input type="hidden" value="{t}Description{/t}" id="label_Product"  />
<input type="hidden" value="{t}Sales{/t}" id="label_Sales"  />
<input type="hidden" value="{t}Description{/t}" id="label_Description"  />


<div  id="title" class="title" style="height:22px">

 <div class="home_splinter_options" style="font-size:80%;">
                      <span id="top_products_fam" type="families" {if $conf_data.top_products.type=='families'}class="selected"{/if} style="float:right;margin-left:5px">{t}Families{/t}</span>
           <span id="top_products_products" type="products" {if $conf_data.top_products.type=='products'}class="selected"{/if} style="float:right;margin-left:15px">{t}Products{/t}</span>

           <span id="top_products_50" nr="50" {if $conf_data.top_products.nr==50}class="selected"{/if} style="float:right;margin-left:5px">50</span>
            <span id="top_products_20" nr="20" {if $conf_data.top_products.nr==20}class="selected"{/if} style="float:right;margin-left:5px">20</span>
            <span id="top_products_10" nr="10" {if $conf_data.top_products.nr==10}class="selected"{/if} style="float:right;margin-left:15px">10</span>
            <span id="top_products_all" period="all" {if $conf_data.top_products.period=='all'}class="selected"{/if} style="float:right;margin-left:5px">{t}All times{/t}</span>
            <span id="top_products_1y" period="1y" {if $conf_data.top_products.period=='1y'}class="selected"{/if} style="float:right;margin-left:5px">{t}1y{/t}</span>
            <span id="top_products_1q" period="1q" {if $conf_data.top_products.period=='1q'}class="selected"{/if} style="float:right;margin-left:5px">{t}1q{/t}</span>
            <span id="top_products_1m" period="1m" {if $conf_data.top_products.period=='1m'}class="selected"{/if} style="float:right;margin-left:5px">{t}1m{/t}</span>
        </div>
   <h1>{t}Top Products{/t}</h1>     
        
</div>



<div style="float:left;width:450px;margin-right:18px;border:1px solid #e7e7e7;padding:5px;margin-bottom:10px;width:918px">



    <div id="the_table" class="data_table" style="font-size:85%">
    <div style="float:left;margin-right:10px;width:450px">
        
       
        {include file='table_splinter.tpl' table_id=1 filter_name=$filter_name filter_value=$filter_value no_filter=1 hide_caption=1}
        <div  id="table1"   class="data_table_container dtable btable" style="margin-top:5px"> </div>
       </div>
    <div style="float:left;margin-left:5px;padding-top:20px" id="plot_orders">
		<strong>You need to upgrade your Flash Player</strong>
	</div>
    <script type="text/javascript">
		// <![CDATA[		
		var so = new SWFObject("external_libs/ampie/ampie/ampie.swf", "ampie", "450", "575", "1", "#FFFFFF");
		so.addVariable("path", "external_libs/ampie/ampie/");
		so.addVariable("settings_file", encodeURIComponent("conf/pie_settings.xml.php"));                // you can set two or more different settings files here (separated by commas)
		so.addVariable("data_file", encodeURIComponent("plot_data.csv.php?tipo=top_families&store_keys={$store_keys}&period={$conf_data.top_products.period}")); 
		so.addVariable("loading_settings", "LOADING SETTINGS");                                         // you can set custom "loading settings" text here
		so.addVariable("loading_data", "LOADING DATA");                                                 // you can set custom "loading data" text here
so.addVariable("chart_id", "ampie");
		so.write("plot_orders");
		
		// ]]>
	</script>
       
        
    </div>
</div>

</html>