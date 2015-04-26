{include file='header.tpl'}
	<input type="hidden" id="calendar_id" value="sales" />

<div id="bd" style="padding:0">
	<div style="padding:0 20px">
		{include file='reports_navigation.tpl'} 

		<div class="branch" style="width:300px;padding-top:5px">
			<span><a href="report_geo_sales.php?world=1">{t}World{/t}</a> &rarr; <a href="report_geo_sales.php?continent={$continent_code}">{$continent_name}</a> &rarr; <a href="report_geo_sales.php?wregion={$wregion_code}">{$wregion_name}</a> &rarr;<a href="report_geo_sales.php?country={$country_code}">{$country_name}</a></span> 
		</div>
		<h1 style="clear:left">
			{$title}
		</h1>
	</div>
	<ul class="tabs" id="chooser_ul" style="clear:both;margin-top:25px">
		<li> <span class="item {if $view=='details'}selected{/if}" id="details"> <span> {t}Country Info{/t}</span></span></li>
		<li> <span class="item {if $view=='overview'}selected{/if}" id="overview"> <span> {t}Sales Overview{/t}</span></span></li>
		<li> <span class="item {if $view=='customers'}selected{/if}" id="customers"> <span> {t}Customer List{/t}</span></span></li>
		<li> <span class="item {if $view=='invoices'}selected{/if}" id="invoices"> <span> {t}Invoice List{/t}</span></span></li>
	</ul>
	<div style="clear:both;width:100%;border-bottom:1px solid #ccc">
	</div>
	<div id="block_details" style="{if $view!='details'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
		
		
		<div style="float:left;">
			<h2>
				{$country_name} [{$country_code}]
			</h2>
			<div style="width:100%;">
				<div style="width:100%;font-size:90%">
					<div style="width:200px;float:left;margin-right:20px">
						<table class="show_info_product">
							<tr>
								<td>{t}Population{/t}:</td>
								<td class="price aright">{$country->get('Population')}</td>
							</tr>
							<tr>
								<td>{t}GNP{/t}:</td>
								<td class="price aright">{$country->get('GNP')}</td>
							</tr>
							
						</table>
					</div>
					<div style="width:220px;float:left">
						<table class="show_info_product">
							<tr>
								<td>{t}Currency{/t}:</td>
								<td class="aright">{$country->get('Country Currency Name')} ({$country->get('Country Currency Code')})</td>
							</tr>
							<tr>
								<td>{t}Exchange{/t}:</td>
								<td class="aright"> 
								<table style="float:right">
									{$country->get_formated_exchange_reverse('GBP',false,'tr')} {$country->get_formated_exchange('GBP',false,'tr')} 
								</table>
								</td>
							</tr>
						</table>
						<table class="show_info_product">
							<tr>
								<td>{t}Official Name{/t}:</td>
								<td class="aright">{$country->get('Country Native Name')}</td>
							</tr>
							<tr>
								<td>{t}Languages{/t}:</td>
								<td class="aright">{$country->get('Country Languages')}</td>
							</tr>
							<tr>
								<td>{t}Capital{/t}:</td>
								<td class="aright">{$country->get('Country Capital Name')}</td>
							</tr>
							<tr>
								<td>{t}Government{/t}:</td>
								<td class="aright">{$country->get('Country Goverment Form')}<br>{$country->get('Country Head of State')}</td>
							</tr>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div id="block_overview" style="{if $view!='overview'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
	
					{include file='calendar_splinter.tpl' calendar_id='sales' calendar_link='report_geo_sales_country.php'} 

	
		<div id="plot" class="top_bar" style="position:relative;clear:both;padding:0;margin:0">
			<ul id="plot_chooser" class="tabs" style="margin:0 20px;padding:0 20px ">
				<li> <span class="item {if $plot_tipo=='plot_all_stores'}selected{/if}" onclick="change_plot(this)" id="plot_all_stores" tipo="par_all"> <span>{t}All Stores{/t}</span> </span> </li>
				<li> <span class="item {if $plot_tipo=='plot_per_store'}selected{/if}" onclick="change_plot(this)" id="plot_per_store" tipo="per_store"> <span>{t}Invoices per Store{/t}</span> </span> </li>
				<li> <span class="item {if $plot_tipo=='plot_per_category'}selected{/if}" id="plot_per_category" onclick="change_plot(this)" tipo="per_category"> <span>{t}Invoices per Category{/t}</span> </span> </li>
			</ul>
			<div id="div_plot_all_stores" style="{if $plot_tipo!='plot_all_stores'}display:none;{/if}clear:both;border:1px solid #ccc">
				<strong>{t}You need to upgrade your Flash Player{/t}</strong>
			</div>
<script type="text/javascript">
		// <![CDATA[
		var so = new SWFObject("external_libs/amstock/amstock/amstock.swf", "amstock", "905", "500", "8", "#FFFFFF");
		so.addVariable("path", "");
		so.addVariable("settings_file", encodeURIComponent("conf/plot_asset_sales.xml.php?tipo=sales_from_country&store_key={$am_safe_store_keys}&from={$from}&to={$to}&country_code={$country_code}"));
		so.addVariable("preloader_color", "#999999");
		so.write("div_plot_all_stores");
		// ]]>
	</script> 
			<div id="div_plot_per_store" style="{if $plot_tipo!='plot_per_store'}display:none;{/if}clear:both;border:1px solid #ccc">
				<strong>{t}You need to upgrade your Flash Player{/t}</strong>
			</div>
<script type="text/javascript">
		// <![CDATA[
		var so = new SWFObject("external_libs/amstock/amstock/amstock.swf", "amstock", "905", "500", "8", "#FFFFFF");
		so.addVariable("path", "");
		so.addVariable("settings_file", encodeURIComponent("conf/plot_asset_sales.xml.php?tipo=store_sales&stacked=1&store_key={$am_safe_store_keys}&from={$from}&to={$to}"));
		so.addVariable("preloader_color", "#999999");
		so.write("div_plot_per_store");
		// ]]>
	</script> 
			<div id="div_plot_per_category" style="{if $plot_tipo!='plot_per_category'}display:none;{/if}clear:both;border:1px solid #ccc">
				<strong>{t}You need to upgrade your Flash Player{/t}</strong>
			</div>
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
		<div style="clear:both">
		</div>
		<div id="close1">
			{if !$top_countries_in_region} {$no_sales_message} {$from} {t}to{/t} {$to} {else} 
			<h2>
				{t}Top Countries{/t}
			</h2>
			<div style="float:right;width:300px">
				<table>
					<tr>
						<td>{t}Country{/t}</td>
						<td>{t}Sales{/t}</td>
					</tr>
					{foreach from = $top_countries_in_region item=data_country} 
					<tr>
						<td>{$data_country.country}</td>
						<td>{$data_country.sales}</td>
					</tr>
					{/foreach} 
				</table>
			</div>
			<div id="plot1" style="float:left;width:500px">
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
    </script> {/if} 
		</div>
	</div>
	<div id="block_customers" style="{if $view!='customers'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
		<div style="clear:both">
				<span class="clean_table_title">{t}Customers List{/t} 
				<img  id="export_customers" class="export_data_link" label="{t}Export Table{/t}" alt="{t}Export Table{/t}" src="art/icons/export_csv.gif">
				</span> 
				
					<div class="elements_chooser" id="customer_type_chooser">
									<img class="menu" id="customer_element_chooser_menu_button" title="{t}Group by menu{/t}" src="art/icons/list.png" /> 

						<div id="customer_activity_chooser" style="{if $elements_customers_elements_type!='activity'}display:none{/if}">
							<span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_activity.Lost}selected{/if} label_all_contacts_lost" id="elements_Lost" table_type="lost">{t}Lost{/t} (<span id="elements_Lost_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:20px;" class=" table_type transaction_type state_details {if $elements_activity.Losing}selected{/if} label_all_contacts_losing" id="elements_Losing" table_type="losing">{t}Losing{/t} (<span id="elements_Losing_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:20px;" class=" table_type transaction_type state_details {if $elements_activity.Active}selected{/if} label_all_contacts_active" id="elements_Active" table_type="active">{t}Active{/t} (<span id="elements_Active_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> 
						</div>
						<div id="customer_level_type_chooser" style="{if $elements_customers_elements_type!='level_type'}display:none{/if}">
							<span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_level_type.VIP}selected{/if} label_customer_VIP" id="elements_VIP" table_type="VIP">{t}VIP{/t} (<span id="elements_VIP_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_level_type.Partner}selected{/if} label_customer_Partner" id="elements_Partner" table_type="Partner">{t}Partner{/t} (<span id="elements_Partner_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_level_type.Staff}selected{/if} label_customer_Staff" id="elements_Staff" table_type="Staff">{t}Staff{/t} (<span id="elements_Staff_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_level_type.Normal}selected{/if} label_customer_Normal" id="elements_Normal" table_type="Normal">{t}Normal{/t} (<span id="elements_Normal_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> 
						</div>
						<div id="customer_location_chooser" style="{if $elements_customers_elements_type!='location'}display:none{/if}">
							<span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_location.Export}selected{/if} label_customer_Export" id="elements_Export" table_type="Export">{t}Export{/t} (<span id="elements_Export_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span>

							<span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_location.Domestic}selected{/if} label_customer_Domestic" id="elements_Domestic" table_type="Domestic">{t}Domestic{/t} (<span id="elements_Domestic_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> 
						</div>
						<div id="customer_orders_chooser">
							<span style="float:right;margin-left:2px;margin-right:10px" class=" table_type transaction_type state_details">]</span> <span style="float:right;margin-left:2px" class=" table_type transaction_type state_details {if  $orders_type=='contacts_with_orders'}selected{/if}" id="elements_orders_type_contacts_with_orders" table_type="contacts_with_orders" title="{t}Contacts with Orders{/t}">{t}With Orders{/t}</span> <span style="float:right;margin-left:2px" class=" table_type transaction_type state_details">|</span> <span style="float:right;margin-left:2px" class=" table_type transaction_type state_details {if $orders_type=='all_contacts'}selected{/if}" id="elements_orders_type_all_contacts" table_type="all_contacts" title="{t}All Contacts{/t}">{t}All{/t}</span> <span style="float:right;margin-left:0px" class=" table_type transaction_type state_details">[</span> 
						</div>
					</div>
				
				<div class="table_top_bar">
				</div>
				<div class="clusters">
					<div class="buttons small left cluster">
						<button class="table_option {if $customer_view=='general'}selected{/if}" id="general">{t}General{/t}</button> <button class="table_option {if $customer_view=='contact'}selected{/if}" id="contact">{t}Contact{/t}</button> <button class="table_option {if $customer_view=='address'}selected{/if}" id="address">{t}Address{/t}</button> <button class="table_option {if $customer_view=='balance'}selected{/if}" id="balance">{t}Balance{/t}</button> <button class="table_option {if $customer_view=='rank'}selected{/if}" id="rank">{t}Ranking{/t}</button> <button class="table_option {if $customer_view=='weblog'}selected{/if}" id="weblog">{t}WebLog{/t}</button> 
					</div>
					<div style="clear:both">
					</div>
				</div>
				{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0 } 
				<div id="table0" style="font-size:90%" class="data_table_container dtable btable">
				</div>
			</div>
	</div>
	<div id="block_invoices" style="{if $view!='invoices'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
	<span id="table_title" class="clean_table_title">{t}Counties{/t}</span> 
	<div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999;margin-bottom:15px">
	</div>
	{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0} 
	<div id="table0" class="data_table_container dtable btable">
	</div>
</div>
</div>

<div id="photo_container" style="display:none;float:left;border:0px solid #777;width:510px;height:320px">
	<iframe id="the_map" src="map.php?country=" frameborder="0" scrolling="no" width="550" height="420"></iframe> 
</div>
<div id="rppmenu0" class="yuimenu">
	<div class="bd">
		<ul class="first-of-type">
			<li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Rows per Page{/t}:</li>
			{foreach from=$paginator_menu0 item=menu } 
			<li class="yuimenuitem"><a class="yuimenuitemlabel" onclick="change_rpp_with_totals({$menu},0)"> {$menu}</a></li>
			{/foreach} 
		</ul>
	</div>
</div>
<div id="filtermenu0" class="yuimenu">
	<div class="bd">
		<ul class="first-of-type">
			<li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Filter options{/t}:</li>
			{foreach from=$filter_menu0 item=menu } 
			<li class="yuimenuitem"><a class="yuimenuitemlabel" onclick="change_filter('{$menu.db_key}','{$menu.label}',0)"> {$menu.menu_label}</a></li>
			{/foreach} 
		</ul>
	</div>
</div>
<div id="rppmenu1" class="yuimenu">
	<div class="bd">
		<ul class="first-of-type">
			<li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Rows per Page{/t}:</li>
			{foreach from=$paginator_menu1 item=menu } 
			<li class="yuimenuitem"><a class="yuimenuitemlabel" onclick="change_rpp_with_totals({$menu},1)"> {$menu}</a></li>
			{/foreach} 
		</ul>
	</div>
</div>
<div id="filtermenu1" class="yuimenu">
	<div class="bd">
		<ul class="first-of-type">
			<li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Filter options{/t}:</li>
			{foreach from=$filter_menu1 item=menu } 
			<li class="yuimenuitem"><a class="yuimenuitemlabel" onclick="change_filter('{$menu.db_key}','{$menu.label}',1)"> {$menu.menu_label}</a></li>
			{/foreach} 
		</ul>
	</div>
</div>
<div id="rppmenu2" class="yuimenu">
	<div class="bd">
		<ul class="first-of-type">
			<li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Rows per Page{/t}:</li>
			{foreach from=$paginator_menu2 item=menu } 
			<li class="yuimenuitem"><a class="yuimenuitemlabel" onclick="change_rpp_with_totals({$menu},2)"> {$menu}</a></li>
			{/foreach} 
		</ul>
	</div>
</div>
<div id="filtermenu2" class="yuimenu">
	<div class="bd">
		<ul class="first-of-type">
			<li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Filter options{/t}:</li>
			{foreach from=$filter_menu2 item=menu } 
			<li class="yuimenuitem"><a class="yuimenuitemlabel" onclick="change_filter('{$menu.db_key}','{$menu.label}',2)"> {$menu.menu_label}</a></li>
			{/foreach} 
		</ul>
	</div>
</div>

{include file='footer.tpl'} 