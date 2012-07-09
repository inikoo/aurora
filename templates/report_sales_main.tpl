{include file='header.tpl'} 
<div id="bd">
<div class="branch" style="width:280px;float:left;margin:0"> 
  <span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home"/></a> &rarr; <a  href="reports.php">{t}Reports{/t}</a> &rarr; {t}Sales{/t}</span>
</div>
	{include file='calendar_splinter.tpl'} 
	<div style="clear:both"></div>
	<h1 style="margin-top:10px">
		{$title}, <span class="id">{$period}</span> <img id="show_calendar_browser" style="cursor:pointer;vertical-align:text-bottom;position:relative;top:-3px;{if $tipo=='f'}display:none{/if}" src="art/icons/calendar.png" alt="calendar" />
	</h1>
	
	
	<table class="report_sales1"  style="width:900px;">
		<tr style="border-bottom:1px solid #ccc;margin-bottom:5px">
			<td colspan="7"> 
			<div style="margin-bottom:5px;color:#999;">
				<span style=";margin-right:20px"> ( <span id="show_profit_table" view="profit" class="state_details{if $view=='profits'} selected{/if}">{t}Profit{/t}</span> | <span class="state_details{if $view=='invoices'} selected{/if}" id="show_invoices_table" view="invoices">{t}Invoices{/t}</span> ) </span> <span style="{if !$mixed_currencies}display:none;{/if}"> ( <span id="invoices_corporate_currency_button" currency="corporate" class="state_details currency_corporate {if $currencies=='corporation'}selected{/if}">{t}HQ Currency{/t}</span> | <span id="invoices_stores_currency_button" currency="stores" class="state_details currency_stores {if $currencies!='corporation'}selected{/if}">{t}Store Currencies{/t}</span> ) </span> 
			</div>
			</td>
		</tr>
	<tbody class="report_sales1" id="report_sales_invoices" style="{if $view!='invoices'}display:none{/if}">
	
		<tr>
			<td style="width:150px">{t}Store{/t}</td>
			<td>{t}Invoices{/t}</td>
			<td class="aleft">% {t}of total{/t}</td>
			<td>&Delta;1{t}Yr{/t}</td>
			<td style="{if $currencies=='corporation'}display:none{/if}">{t}Net Sales{/t}</td>
			<td style="{if $currencies!='corporation'}display:none{/if}">{t}Net Sales{/t}</td>
			<td style="{if $currencies=='corporation'}display:none{/if}" class="aleft"></td>
			<td style="{if $currencies!='corporation'}display:none{/if}" class="aleft">% {t}of total{/t}</td>
			<td>&Delta;1{t}Yr{/t}</td>
			{* 
			<td style="{if $currencies=='corporation'}display:none{/if}">{t}Tax{/t}</td>
			<td style="{if $currencies!='corporation'}display:none{/if}">{t}Tax{/t}</td>
			*} 
		</tr>
		{foreach from=$store_data item=data } 
		<tr {if isset($data.class)}class="{$data.class}"{/if}>
			<td class="label aleft"><span {if isset($data.substore) }style="margin-left:50px" {/if}> {$data.store}{if isset($data.substore)}{$data.substore}{/if}</span></td>
			<td>{$data.invoices}</td>
			<td class="aleft"><span {if isset($data.substore) }style="margin-left:50px" {/if}>{if isset($data.per_invoices)}{$data.per_invoices}{/if}</span></td>
			<td>{$data.last_yr_invoices}</td>
			<td class="currency_stores" style="{if $currencies=='corporation'}display:none{/if}">{$data.net}</td>
			<td class="currency_corporate" style="{if $currencies!='corporation'}display:none{/if}">{$data.eq_net}</td>
			<td class="currency_corporate aleft" style="{if $currencies!='corporation'}display:none;{/if}"><span {if isset($data.substore) }style="margin-left:50px" {/if}>{$data.per_eq_net}</span></td>
			<td class="currency_stores aleft" style="{if $currencies=='corporation'}display:none{/if}"></td>
			<td>{$data.last_yr_net}</td>
		</tr>
		{/foreach} 
			
		</tbody>
	<tbody class="report_sales1" id="report_sales_profit" style="{if $view!='profits'}display:none{/if}">
			
			<tr>
				<td style="width:150px">{t}Store{/t}</td>
				<td></td>
				<td style="{if $currencies=='corporation'}display:none{/if}">{t}Revenue{/t}</td>
				<td style="{if $currencies!='corporation'}display:none{/if}">{t}Revenue{/t}</td>
				<td style="{if $currencies=='corporation'}display:none{/if}">{t}Profit{/t}</td>
				<td style="{if $currencies!='corporation'}display:none{/if}">{t}Profit{/t}</td>
				<td>{t}Margin{/t}</td>
			</tr>
			{foreach from=$store_data_profit item=data } 
			<tr class="{$data.class}">
				<td class="label"> {$data.store}</td>
				<td style="text-align:left">{if isset($data.substore)}{$data.substore}{/if}</td>
				<td class="currency_stores" style="{if $currencies=='corporation'}display:none{/if}">{$data.net}</td>
				<td class="currency_corporate" style="{if $currencies!='corporation'}display:none{/if}">{$data.eq_net}</td>
				<td class="currency_stores" style="{if $currencies=='corporation'}display:none{/if}">{$data.profit}</td>
				<td class="currency_corporate" style="{if $currencies!='corporation'}display:none{/if}">{$data.eq_profit}</td>
				<td>{$data.margin}</td>
			</tr>
			{/foreach} 
		</tbody>
	
	</table>
	
	<div id="plot" class="top_bar" style="position:relative;clear:both;padding:0;margin:0">
			<ul id="plot_chooser" class="tabs" style="margin:0 20px;padding:0 20px ">
				<li> <span class="item {if $plot_tipo=='plot_all_stores'}selected{/if}" onclick="change_plot(this)" id="plot_all_stores" tipo="par_all" > <span>{t}All Stores{/t}</span> </span> </li>
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
		so.addVariable("settings_file", encodeURIComponent("conf/plot_asset_sales.xml.php?tipo=store_sales&store_key={$am_safe_store_keys}&from={$from}&to={$to}"));
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
			<div style="clear:both">
			</div>
		</div>
	</div>
	{include file='footer.tpl'} 