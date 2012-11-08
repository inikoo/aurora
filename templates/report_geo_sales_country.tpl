{include file='header.tpl'}

<div id="bd" style="padding:0" >

<div style="padding:0 20px">
{include file='reports_navigation.tpl'}
{include file='calendar_splinter.tpl'}


<div class="branch" style="width:300px;padding-top:5px"> 
   <span ><a  href="report_geo_sales.php?world=1">{t}World{/t}</a> &rarr; <a  href="report_geo_sales.php?continent={$continent_code}">{$continent_name}</a> &rarr; <a  href="report_geo_sales.php?wregion={$wregion_code}">{$wregion_name}</a> &rarr;<a  href="report_geo_sales.php?country={$country_code}">{$country_name}</a></span>

</div>
<h1 style="clear:left">{$title}</h1>

</div>


<ul class="tabs" id="chooser_ul" style="clear:both;margin-top:25px">
    <li> <span class="item {if $view=='details'}selected{/if}"  id="details">  <span> {t}Country Info{/t}</span></span></li>

    <li> <span class="item {if $view=='overview'}selected{/if}"  id="overview">  <span> {t}Sales Overview{/t}</span></span></li>
    <li> <span class="item {if $view=='customers'}selected{/if}"  id="customers">  <span> {t}Customer List{/t}</span></span></li>
    <li> <span class="item {if $view=='invoices'}selected{/if}"  id="invoices">  <span> {t}Invoice List{/t}</span></span></li>
   
</ul>
<div  style="clear:both;width:100%;border-bottom:1px solid #ccc"></div>
<div id="block_details" style="{if $view!='details'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
<div style="float:left;">

	  <h2>{$country_name} [{$country_code}]</h2>
	  <div   style="width:100%;">
	    <div  style="width:100%;font-size:90%"   >
              <div  style="width:200px;float:left;margin-right:20px">
	
		<table    class="show_info_product">
		    <tr>
		      <td>{t}Population{/t}:</td><td  class="price aright">{$country->get('Population')}</td>
		    </tr>
		   <tr>
		      <td>{t}GNP{/t}:</td><td  class="price aright">{$country->get('GNP')}</td>
		    </tr>
		    
		    <tr><td>{t}Sold Since{/t}:</td><td class="aright">{$country->get('For Sale Since Date')} </td>
		      {if $edit} <td   class="aright" ><input style="text-align:right" class="date_input" size="8" type="text"  id="v_invoice_date"  value="{$v_po_date_invoice}" name="invoice_date" /></td>{/if}
		    </tr>
		  
		</table>

	 



	      </div>
              <div  style="width:220px;float:left">

	

	
		
		
		

	 <table   class="show_info_product">
		    <tr ><td>{t}Currency{/t}:</td><td class="aright">{$country->get('Country Currency Name')} ({$country->get('Country Currency Code')})</td></tr>
		    <tr ><td>{t}Exchange{/t}:</td><td class="aright">
		   
		    <table style="float:right">
		    {$country->get_formated_exchange_reverse('GBP',false,'tr')}
		    {$country->get_formated_exchange('GBP',false,'tr')}
		    </table>
		    </td></tr>


		
		  </table>
	  
		  <table  class="show_info_product">
		    <tr ><td>{t}Official Name{/t}:</td><td class="aright">{$country->get('Country Native Name')}</td></tr>
		    <tr ><td>{t}Languages{/t}:</td><td class="aright">{$country->get('Country Languages')}</td></tr>
		    <tr ><td>{t}Capital{/t}:</td><td class="aright">{$country->get('Country Capital Name')}</td></tr>
		    <tr ><td>{t}Government{/t}:</td><td class="aright">{$country->get('Country Goverment Form')}<br>{$country->get('Country Head of State')}</td></tr>

		
		  </table>
	
		
              </div>
	    </div>
	  </div>
	</div>


</div>

<div id="block_overview" style="{if $view!='overview'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">




<div id="plot" class="top_bar" style="position:relative;clear:both;padding:0;margin:0">
    <ul id="plot_chooser" class="tabs" style="margin:0 20px;padding:0 20px "  >
	    <li>
	        <span class="item {if $plot_tipo=='plot_all_stores'}selected{/if}" onClick="change_plot(this)" id="plot_all_stores" tipo="par_all"  >
	            <span>{t}All Stores{/t}</span>
	        </span>
	    </li>
	   <li>
	        <span class="item {if $plot_tipo=='plot_per_store'}selected{/if}" onClick="change_plot(this)" id="plot_per_store" tipo="per_store"  >
	            <span>{t}Invoices per Store{/t}</span>
	        </span>
	    </li>
	    <li>
	        <span class="item {if $plot_tipo=='plot_per_category'}selected{/if}"  id="plot_per_category" onClick="change_plot(this)" tipo="per_category"   >
	            <span>{t}Invoices per Category{/t}</span>
	        </span>
	    </li>
    </ul> 

	<div  id="div_plot_all_stores" style="{if $plot_tipo!='plot_all_stores'}display:none;{/if}clear:both;border:1px solid #ccc" ><strong>{t}You need to upgrade your Flash Player{/t}</strong></div>
	<script type="text/javascript">
		// <![CDATA[
		var so = new SWFObject("external_libs/amstock/amstock/amstock.swf", "amstock", "905", "500", "8", "#FFFFFF");
		so.addVariable("path", "");
		so.addVariable("settings_file", encodeURIComponent("conf/plot_asset_sales.xml.php?tipo=sales_from_country&store_key={$am_safe_store_keys}&from={$from}&to={$to}&country_code={$country_code}"));
		so.addVariable("preloader_color", "#999999");
		so.write("div_plot_all_stores");
		// ]]>
	</script>

	<div id="div_plot_per_store" style="{if $plot_tipo!='plot_per_store'}display:none;{/if}clear:both;border:1px solid #ccc" ><strong>{t}You need to upgrade your Flash Player{/t}</strong></div>
	<script type="text/javascript">
		// <![CDATA[
		var so = new SWFObject("external_libs/amstock/amstock/amstock.swf", "amstock", "905", "500", "8", "#FFFFFF");
		so.addVariable("path", "");
		so.addVariable("settings_file", encodeURIComponent("conf/plot_asset_sales.xml.php?tipo=store_sales&stacked=1&store_key={$am_safe_store_keys}&from={$from}&to={$to}"));
		so.addVariable("preloader_color", "#999999");
		so.write("div_plot_per_store");
		// ]]>
	</script>
	
		<div id="div_plot_per_category" style="{if $plot_tipo!='plot_per_category'}display:none;{/if}clear:both;border:1px solid #ccc" ><strong>{t}You need to upgrade your Flash Player{/t}</strong></div>
	<script type="text/javascript">
		// <![CDATA[
		var so = new SWFObject("external_libs/amstock/amstock/amstock.swf", "amstock", "905", "500", "8", "#FFFFFF");
		so.addVariable("path", "");
		so.addVariable("settings_file", encodeURIComponent("conf/plot_asset_sales.xml.php?tipo=store_sales&stacked=1&per_category=1&store_key={$am_safe_store_keys}&from={$from}&to={$to}"));
		so.addVariable("preloader_color", "#999999");
		so.write("div_plot_per_category");
		// ]]>
	</script>

</div>

 <div style="clear:both"></div>


 <div id="close1">
    {if !$top_countries_in_region}
    {$no_sales_message}
    {$from}
    {t}to{/t}
    {$to}
    {else}
    <h2>{t}Top Countries{/t}</h2>
    <div style="float:right;width:300px">
      <table>
	<tr><td>{t}Country{/t}</td><td>{t}Sales{/t}</td></tr>
	{foreach from = $top_countries_in_region item=data_country}
	<tr>
	  <td>{$data_country.country}</td>
	  <td>{$data_country.sales}</td>
	</tr>
	{/foreach}
      </table>
    </div>
    <div id="plot1" style="float:left;width:500px" >
      <strong>You need to upgrade your Flash Player</strong>
    </div>
    
    <script type="text/javascript">
      // <![CDATA[		
		var so = new SWFObject("external_libs/ampie/ampie/ampie.swf", "ampie", "465", "380", "1", "#FFFFFF");
		so.addVariable("path", "external_libs/ampie/ampie/");
		so.addVariable("settings_file", encodeURIComponent("conf/pie_settings.xml.php"));                // you can set two or more different settings files here (separated by commas)
		so.addVariable("data_file", encodeURIComponent("plot_data.csv.php?tipo=top_countries_sales_in_region&from={$from}&to={$to}&region_id={$wregion_code}")); 
		so.addVariable("loading_settings", "LOADING SETTINGS");                                         // you can set custom "loading settings" text here
		so.addVariable("loading_data", "LOADING DATA");                                                 // you can set custom "loading data" text here

		so.write("plot1");
		// ]]>
    </script>
    {/if}
  </div>

</div>


<div id="block_customers" style="{if $view!='customers'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">

  <div id="the_table" class="data_table" style="clear:both">
      <span class="clean_table_title">{t}Customers List{/t} <img id="export_csv0"   tipo="customers_per_store" style="position:relative;top:0px;left:5px;cursor:pointer;vertical-align:text-bottom;" label="{t}Export (CSV){/t}" alt="{t}Export (CSV){/t}" src="art/icons/export_csv.gif"></span>
      
 
  <div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999"></div>
  <table style="float:left;margin:0 0 0 0px ;padding:0"  class="options" >
	<tr>
	  <td {if $customer_view=='general'}class="selected"{/if} id="general" >{t}General{/t}</td>
	  <td {if $customer_view=='contact'}class="selected"{/if}  id="contact"  >{t}Contact{/t}</td>
	  <td {if $customer_view=='address'}class="selected"{/if}  id="address"  >{t}Address{/t}</td>
	  <td {if $customer_view=='balance'}class="selected"{/if}  id="balance"  >{t}Balance{/t}</td>
	  <td {if $customer_view=='rank'}class="selected"{/if}  id="rank"  >{t}Ranking{/t}</td>
	</tr>
      </table>
{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0  }
 <div  id="table0"  style="font-size:90%"  class="data_table_container dtable btable"> </div>
 </div>

  
</div>




</div>  


<div id="block_invoices" style="{if $view!='invoices'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
    <span id="table_title" class="clean_table_title">{t}Counties{/t}</span>
     <div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999;margin-bottom:15px"></div>
    {include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0}

    <div  id="table0"   class="data_table_container dtable btable"> </div>
  
</div>


     
<div id="photo_container" style="display:none;float:left;border:0px solid #777;width:510px;height:320px">

	    <iframe id="the_map" src ="map.php?country=" frameborder="0" scrolling="no" width="550"  height="420"></iframe>
	   
	    
	    
	  </div>

<div id="rppmenu0" class="yuimenu" >
  <div class="bd">
    <ul class="first-of-type">
       <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Rows per Page{/t}:</li>
      {foreach from=$paginator_menu0 item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_rpp_with_totals({$menu},0)"> {$menu}</a></li>
      {/foreach}
    </ul>
  </div>
</div>
<div id="filtermenu0" class="yuimenu" >
  <div class="bd">
    <ul class="first-of-type">
      <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Filter options{/t}:</li>
      {foreach from=$filter_menu0 item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_filter('{$menu.db_key}','{$menu.label}',0)"> {$menu.menu_label}</a></li>
      {/foreach}
    </ul>
  </div>
</div>
<div id="rppmenu1" class="yuimenu" >
  <div class="bd">
    <ul class="first-of-type">
       <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Rows per Page{/t}:</li>
      {foreach from=$paginator_menu1 item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_rpp_with_totals({$menu},1)"> {$menu}</a></li>
      {/foreach}
    </ul>
  </div>
</div>
<div id="filtermenu1" class="yuimenu" >
  <div class="bd">
    <ul class="first-of-type">
      <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Filter options{/t}:</li>
      {foreach from=$filter_menu1 item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_filter('{$menu.db_key}','{$menu.label}',1)"> {$menu.menu_label}</a></li>
      {/foreach}
    </ul>
  </div>
</div>
<div id="rppmenu2" class="yuimenu" >
  <div class="bd">
    <ul class="first-of-type">
       <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Rows per Page{/t}:</li>
      {foreach from=$paginator_menu2 item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_rpp_with_totals({$menu},2)"> {$menu}</a></li>
      {/foreach}
    </ul>
  </div>
</div>
<div id="filtermenu2" class="yuimenu" >
  <div class="bd">
    <ul class="first-of-type">
      <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Filter options{/t}:</li>
      {foreach from=$filter_menu2 item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_filter('{$menu.db_key}','{$menu.label}',2)"> {$menu.menu_label}</a></li>
      {/foreach}
    </ul>
  </div>
</div>


     
</div>





 
      


 
</div>




</div>{include file='footer.tpl'}

