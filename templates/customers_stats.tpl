{include file='header.tpl'}
<div id="bd" style="padding:0px">
<div style="padding:0 20px">
{include file='contacts_navigation.tpl'}
<div> 
  <span   class="branch">{if $user->get_number_stores()>1}<a  href="customers_server.php">{t}Customers{/t}</a> &rarr; {/if}<a  href="customers.php?store={$store->id}">{$store->get('Store Code')} {t}Customers{/t}</a> &rarr; {t}Statistics{/t}</span>
</div>
<h2 style="clear:left">{t}Customers Information{/t} ({$store->get('Store Code')})</h2>


</div>

<ul class="tabs" id="chooser_ul" style="clear:both;margin-top:25px">
    <li> <span class="item {if $view=='population'}selected{/if}"  id="population">  <span> {t}Contacts{/t}</span></span></li>
    <li> <span class="item {if $view=='customers'}selected{/if}"  id="customers">  <span> {t}Customers{/t}</span></span></li>
    <li> <span class="item {if $view=='orders'}selected{/if}"  id="orders">  <span> {t}Orders{/t}</span></span></li>
    <li> <span class="item {if $view=='data'}selected{/if}" id="data"  ><span>  {t}Data Integrity{/t}</span></span></li>
    <li> <span class="item {if $view=='referral'}selected{/if}" id="referral"  ><span>  {t}Referral{/t}</span></span></li>
    <li> <span class="item {if $view=='type_business'}selected{/if}" id="type_business"  ><span>  {t}Type Business{/t}</span></span></li>
    <li> <span class="item {if $view=='geo'}selected{/if}"  id="geo">  <span> {t}Geographic Distribution{/t}</span></span></li>
    <li> <span class="item {if $view=='correlations'}selected{/if}"  id="correlations">  <span> {t}Correlations{/t}</span></span></li>
</ul>

<div  style="clear:both;width:100%;border-bottom:1px solid #ccc"></div>

<div id="block_correlations" style="{if $view!='correlations'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
<div id="the_table" class="data_table" style="clear:both;padding:0 10px">
      <span class="clean_table_title">{t}Customers Correlation (Possible Duplicates){/t}</span>
      
   <div  style="font-size:90%">
   

     </div>
  <div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999;margin-bottom:15px"></div>
 
{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0  }
 <div  id="table0"  style="font-size:90%"  class="data_table_container dtable btable "> </div>
 </div>
</div>


<div id="block_referral" style="{if $view!='referral'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
	<div style="float:left" id="plot_referral_1">
		<strong>You need to upgrade your Flash Player</strong>
	</div>

	<script type="text/javascript">
		// <![CDATA[		
		var so = new SWFObject("external_libs/ampie/ampie/ampie.swf", "ampie", "350", "300", "1", "#FFFFFF");
		so.addVariable("path", "external_libs/ampie/ampie/");
		so.addVariable("settings_file", encodeURIComponent("conf/pie_settings.xml.php"));                // you can set two or more different settings files here (separated by commas)
		so.addVariable("data_file", encodeURIComponent("plot_data.csv.php?tipo=customer_referral_assigned_pie&store_key={$store->id}")); 
		so.addVariable("loading_settings", "LOADING SETTINGS"); 
			
		// you can set custom "loading settings" text here
		so.addVariable("loading_data", "LOADING DATA");                                                 // you can set custom "loading data" text here

		so.write("plot_referral_1");
		// ]]>
	</script>

<div style="float:left" id="plot_referral_2">
		<strong>You need to upgrade your Flash Player</strong>
	</div>

	<script type="text/javascript">
		// <![CDATA[		
		var so = new SWFObject("external_libs/ampie/ampie/ampie.swf", "ampie", "550", "550", "8", "#FFFFFF");
		so.addVariable("path", "external_libs/ampie/ampie/");
		so.addVariable("settings_file", encodeURIComponent("conf/pie_settings.xml.php"));                // you can set two or more different settings files here (separated by commas)
		so.addVariable("data_file", encodeURIComponent("plot_data.csv.php?tipo=customer_referral_pie&store_key={$store->id}")); 
		so.addVariable("loading_settings", "LOADING SETTINGS");
		so.addVariable("loading_settings", "LOADING SETTINGS");  // you can set custom "loading settings" text here
		so.addVariable("loading_data", "LOADING DATA");                                                 // you can set custom "loading data" text here

		so.write("plot_referral_2");
		// ]]>
	</script>

</div>
<div id="block_type_business" style="{if $view!='type_business'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
	<div style="float:left" id="plot_business_type_1">
		<strong>You need to upgrade your Flash Player</strong>
	</div>

	<script type="text/javascript">
		// <![CDATA[		
		var so = new SWFObject("external_libs/ampie/ampie/ampie.swf", "ampie", "350", "300", "1", "#FFFFFF");
		so.addVariable("path", "external_libs/ampie/ampie/");
		so.addVariable("settings_file", encodeURIComponent("conf/pie_settings.xml.php"));                // you can set two or more different settings files here (separated by commas)
		so.addVariable("data_file", encodeURIComponent("plot_data.csv.php?tipo=customer_business_type_assigned_pie&store_key={$store->id}")); 
		so.addVariable("loading_settings", "LOADING SETTINGS");                                         // you can set custom "loading settings" text here
		so.addVariable("loading_data", "LOADING DATA");                                                 // you can set custom "loading data" text here

		so.write("plot_business_type_1");
		// ]]>
	</script>

<div style="float:left" id="plot_business_type_2">
		<strong>You need to upgrade your Flash Player</strong>
	</div>

	<script type="text/javascript">
		// <![CDATA[		
		var so = new SWFObject("external_libs/ampie/ampie/ampie.swf", "ampie", "550", "550", "8", "#FFFFFF");
		so.addVariable("path", "external_libs/ampie/ampie/");
		so.addVariable("settings_file", encodeURIComponent("conf/pie_settings.xml.php"));                // you can set two or more different settings files here (separated by commas)
		so.addVariable("data_file", encodeURIComponent("plot_data.csv.php?tipo=customer_business_type_pie&store_key={$store->id}")); 
		so.addVariable("loading_settings", "LOADING SETTINGS");                                         // you can set custom "loading settings" text here
		so.addVariable("loading_data", "LOADING DATA");                                                 // you can set custom "loading data" text here

		so.write("plot_business_type_2");
		// ]]>
	</script>

</div>
<div id="block_customers" style="{if $view!='customers'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">

<div style="width:25em;float:left;margin-left:20px">
  <table    class="show_info_product">
       <tbody id="info_all" style="">
	 <tr >
	  <td>{t}Active Customers{/t}:</td><td class="aright">{$store->get('Active Customers')}</td>
	</tr>
	 	<tr >
	  <td>{t}New Customer (1 month){/t}:</td><td class="aright">{$store->get('1 Month New Customers')}</td>
	</tr>
	<tr >
	  <td>{t}Lost Customers (1 month){/t}:</td><td class=" aright">{$store->get('1 Month Lost Customers')}</td>
	</tr>
    </tbody>
      
      </table>
</div>

	<div id="number_of_customers"  >
		<strong>You need to upgrade your Flash Player</strong>
	</div>

<script type="text/javascript">
		// <![CDATA[
		var so = new SWFObject("external_libs/amstock/amstock/amstock.swf", "amstock", "905", "500", "8", "#FFFFFF");
		so.addVariable("path", "");
		so.addVariable("settings_file", encodeURIComponent("conf/plot_general_candlestick.xml.php?tipo=number_of_customers&store_key={$store->id}"));
		so.addVariable("preloader_color", "#999999");
		so.write("number_of_customers");
		// ]]>
	</script>
</div>
<div id="block_orders" style="{if $view!='orders'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
	<div style="float:left" id="plot_orders">
		<strong>You need to upgrade your Flash Player</strong>
	</div>

	<script type="text/javascript">
		// <![CDATA[		
		var so = new SWFObject("external_libs/ampie/ampie/ampie.swf", "ampie", "550", "550", "1", "#FFFFFF");
		so.addVariable("path", "external_libs/ampie/ampie/");
		so.addVariable("settings_file", encodeURIComponent("conf/pie_settings.xml.php"));                // you can set two or more different settings files here (separated by commas)
		so.addVariable("data_file", encodeURIComponent("plot_data.csv.php?tipo=customers_orders_pie&store_key={$store->id}")); 
		so.addVariable("loading_settings", "LOADING SETTINGS");                                         // you can set custom "loading settings" text here
		so.addVariable("loading_data", "LOADING DATA");                                                 // you can set custom "loading data" text here

		so.write("plot_orders");
		// ]]>
	</script>
</div>
<div id="block_population" style="{if $view!='population'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
<div style="width:25em;float:left;margin-left:20px">
  <table    class="show_info_product">
       <tbody id="info_all" style="">
	 <tr >
	  <td>{t}Contacts{/t}:</td><td class="aright">{$store->get('Total Customer Contacts')}</td>
	</tr>
	 	<tr >
	  <td>{t}New Contacts (1 month){/t}:</td><td class="aright">{$store->get('1 Month New Customers Contacts')}</td>
	</tr>
	
    </tbody>
      
      </table>
</div>

	<div id="number_of_contacts"  >
		<strong>You need to upgrade your Flash Player</strong>
	</div>

<script type="text/javascript">
		// <![CDATA[
		var so = new SWFObject("external_libs/amstock/amstock/amstock.swf", "amstock", "905", "500", "8", "#FFFFFF");
		so.addVariable("path", "");
		so.addVariable("settings_file", encodeURIComponent("conf/plot_general_timeseries.xml.php?tipo=number_of_contacts&store_key={$store->id}"));
		so.addVariable("preloader_color", "#999999");
		so.write("number_of_contacts");
		// ]]>
	</script>


</div>
<div id="block_data" style="{if $view!='data'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
	<div style="float:left" id="plot_data">
		<strong>You need to upgrade your Flash Player</strong>
	</div>
	<script type="text/javascript">
		// <![CDATA[		
		var so = new SWFObject("external_libs/ampie/ampie/ampie.swf", "ampie", "500", "350", "1", "#FFFFFF");
		so.addVariable("path", "external_libs/ampie/ampie/");
		so.addVariable("settings_file", encodeURIComponent("conf/pie_settings.xml.php?y=50%&radius=40%&data_labels_radius=10%"));                // you can set two or more different settings files here (separated by commas)
		so.addVariable("data_file", encodeURIComponent("plot_data.csv.php?tipo=customers_data_completeness_pie&store_key={$store->id}")); 
		so.addVariable("loading_settings", "LOADING SETTINGS");                                         // you can set custom "loading settings" text here
		so.addVariable("loading_data", "LOADING DATA");                                                 // you can set custom "loading data" text here

		so.write("plot_data");
		// ]]>
	</script>




</div>
<div id="block_geo" style="{if $view!='geo'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">

<div id="map_countries" style="width:700px;height:480px;">
		<strong>You need to upgrade your Flash Player</strong>
	</div>
<script type="text/javascript">
		// <![CDATA[		
		var so = new SWFObject("external_libs/ammap/ammap/ammap.swf", "ammap", "100%", "100%", "8", "#FFFFFF");
        so.addVariable("path", "external_libs/ammap/ammap/");
		so.addVariable("data_file", escape("map_data_world_countries.xml.php?report=customer_total_contacts&store_key={$store->id}"));
        so.addVariable("settings_file", escape("conf/world_heatmap_settings.xml"));		
		so.addVariable("preloader_color", "#999999");
		so.write("map_countries");
		
		
		
		// ]]>
	</script>
</div>

</div> 


{include file='footer.tpl'}
