<input type="hidden" value="{$top_products_index}" id="top_products_index"  />
<input type="hidden" value="{$top_products_nr}" id="top_products_nr"  />
<input type="hidden" value="{$splinters.top_products.type}" id="top_products_type"  />

<input type="hidden" value="{t}Fam{/t}" id="label_Fam"  />
<input type="hidden" value="{t}Description{/t}" id="label_Product"  />
<input type="hidden" value="{t}Sales{/t}" id="label_Sales"  />
<input type="hidden" value="{t}Description{/t}" id="label_Description"  />



<div class="splinter_cell" style="width:910px">
    <div id="the_table" class="data_table" >
    <div style="float:left;margin-right:10px">
        <span class="clean_table_title">{t}Top Products{/t}</span>
        <div class="home_splinter_options">
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
        {include file='table_splinter.tpl' table_id=1 filter_name=$filter_name filter_value=$filter_value no_filter=1}
        <div  id="table1"   class="data_table_container dtable btable "> </div>
       </div>
        <div style="float:left;margin-left:10px" id="plot_orders">
		<strong>You need to upgrade your Flash Player</strong>
	</div>

	<script type="text/javascript">
		// <![CDATA[		
		var so = new SWFObject("external_libs/ampie/ampie/ampie.swf", "ampie", "430", "400", "1", "#FFFFFF");
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