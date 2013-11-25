{include file='header.tpl'} 
<input type="hidden" id="calendar_id" value="sales" />
<div id="bd" style="padding:0">
	<div style="padding:0 20px">
		{include file='reports_navigation.tpl'} 
		<div class="branch" style="width:300px">
			<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a> &rarr; <a href="reports.php">{t}Reports{/t}</a> &rarr; {t}Sales by locacation{/t} </span> 
		</div>
		<div class="top_page_menu">
			<div class="buttons" style="float:right">
			</div>
			<div class="buttons">
				<span class="main_title no_buttons"> {$title}</span> 
			</div>
			<div style="clear:both">
			</div>
		</div>
	</div>
	<ul class="tabs" id="chooser_ul" style="clear:both;margin-top:10px">
		<li> <span class="item {if $view=='overview'}selected{/if}" id="overview"> <span> {t}Sales Overview{/t}</span></span></li>
		<li> <span class="item {if $view=='map'}selected{/if}" id="map"> <span> {t}Map{/t}</span></span></li>
		<li> <span class="item {if $view=='continents'}selected{/if}" id="continents"> <span> {t}Continents{/t}</span></span></li>
		<li> <span class="item {if $view=='wregions'}selected{/if}" id="wregions"> <span> {t}Word Regions{/t}</span></span></li>
		<li> <span class="item {if $view=='countries'}selected{/if}" id="countries"> <span> {t}Countries{/t}</span></span></li>
	</ul>
	<div class="tabs_base">
	</div>
	<div style="border:1px solid white">
	<div id="calendar_container">
		<div id="period_label_container" style="{if $period==''}display:none{/if}">
			<img src="art/icons/clock_16.png"> <span id="period_label">{$period_label}</span> 
		</div>
		{include file='calendar_splinter.tpl' } 
		<div style="clear:both">
		</div>
	</div>
	<div id="block_overview" style="{if $view!='overview'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
		<div id="close1">
			{if !$top_countries} {$no_sales_message} {$from} {t}to{/t} {$to} {else} 
			<h2>
				{t}Top Countries{/t} 
			</h2>
			<div style="float:right;width:340px">
				<table class="edit" style="width:300px" border="0">
					<tr class="title">
						<td>{t}Country{/t}</td>
						<td class="aright">{t}Sales{/t}</td>
					</tr>
					{foreach from = $top_countries item=data_country} 
					<tr>
						<td>{$data_country.country}</td>
						<td style="text-align:right">{$data_country.sales}</td>
					</tr>
					{/foreach} 
				</table>
			</div>
			<div id="plot1" style="float:left;width:500px">
				<strong>You need to upgrade your Flash Player</strong> 
			</div>
<script type="text/javascript">
      // <![CDATA[		
		var so = new SWFObject("external_libs/ampie/ampie/ampie.swf", "ampie", "400", "400", "1", "#FFFFFF");
		so.addVariable("path", "external_libs/ampie/ampie/");
		so.addVariable("settings_file", encodeURIComponent("conf/pie_settings.xml.php"));                // you can set two or more different settings files here (separated by commas)
		so.addVariable("data_file", encodeURIComponent("plot_data.csv.php?tipo=top_countries_sales&from={$from}&to={$to}")); 
		so.addVariable("loading_settings", "LOADING SETTINGS");                                         // you can set custom "loading settings" text here
		so.addVariable("loading_data", "LOADING DATA");                                                 // you can set custom "loading data" text here

		so.write("plot1");
		// ]]>
    </script> {/if} 
		</div>
		<div id="close2" style="clear:both">
			{if !$top_regions} {else} 
			<h2>
				{t}Top Regions{/t} 
			</h2>
			<div style="float:right;width:340px">
				<table class="edit" style="width:300px" border="0">
					<tr class="title">
						<td>{t}Region{/t}</td>
						<td class="aright">{t}Sales{/t}</td>
					</tr>
					{foreach from = $top_regions item=data_region} 
					<tr>
						<td>{$data_region.region}</td>
						<td style="text-align:right">{$data_region.sales}</td>
					</tr>
					{/foreach} 
				</table>
			</div>
			<div id="plot2" style="float:left;width:500px">
				<strong>You need to upgrade your Flash Player</strong> 
			</div>
<script type="text/javascript">
    // <![CDATA[		
		var so = new SWFObject("external_libs/ampie/ampie/ampie.swf", "ampie", "400", "500", "1", "#FFFFFF");
		so.addVariable("path", "external_libs/ampie/ampie/");
		so.addVariable("settings_file", encodeURIComponent("conf/pie_settings.xml.php"));                // you can set two or more different settings files here (separated by commas)
		so.addVariable("data_file", encodeURIComponent("plot_data.csv.php?tipo=top_regions_sales&from={$from}&to={$to}")); 
		so.addVariable("loading_settings", "LOADING SETTINGS");                                         // you can set custom "loading settings" text here
		so.addVariable("loading_data", "LOADING DATA");                                                 // you can set custom "loading data" text here

		so.write("plot2");
		// ]]>
  </script> {/if} 
		</div>
	</div>
	<div id="block_map" style="{if $view!='map'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
		<div class="branch" style="margin:0px;float:right;width:100px;text-align:right">
			<span id="map_links_continents" class="{if $map_links=='continents'}selected{/if}">{t}Continents{/t} &crarr;</span> <span id="map_links_wregions" class="{if $map_links=='wregions'}selected{/if}" style="display:block;margin-top:10px;">{t}World Regions{/t} &crarr;</span> <span id="map_links_countries" class="{if $map_links=='countries'}selected{/if}" style="display:block;margin-top:10px;">{t}Countries{/t} &crarr;</span> 
		</div>
		<div style="border:1px solid #ccc;padding:10px;margin-top:5px;width:800px">
			<div id="map_countries" style="{if $map_links!='countries'}display:none;{/if}width:700px;height:480px;">
				<strong>You need to upgrade your Flash Player</strong> 
			</div>
			<div id="map_wregions" style="{if $map_links!='wregions'}display:none;{/if}width:700px;height:480px;">
				<strong>You need to upgrade your Flash Player</strong> 
			</div>
			<div id="map_continents" style="{if $map_links!='continents'}display:none;{/if}width:700px;height:480px;">
				<strong>You need to upgrade your Flash Player</strong> 
			</div>
<script type="text/javascript">
		// <![CDATA[		
		var so = new SWFObject("{$ammap_path}/ammap/ammap.swf", "ammap", "100%", "100%", "8", "#FFFFFF");
        so.addVariable("path", "{$ammap_path}/ammap/");
		so.addVariable("data_file", escape("map_data_world_countries.xml.php?report=sales&from={$from}&to={$to}"));
        so.addVariable("settings_file", escape("{$settings_file}"));		
		so.addVariable("preloader_color", "#999999");
		so.write("map_countries");
		
		var so = new SWFObject("{$ammap_path}/ammap/ammap.swf", "ammap", "100%", "100%", "8", "#FFFFFF");
        so.addVariable("path", "{$ammap_path}/ammap/");
		so.addVariable("data_file", escape("map_data_world_wregions.xml.php?report=sales&from={$from}&to={$to}"));
        so.addVariable("settings_file", escape("{$settings_file}"));		
		so.addVariable("preloader_color", "#999999");
		so.write("map_wregions");
		
				var so = new SWFObject("{$ammap_path}/ammap/ammap.swf", "ammap", "100%", "100%", "8", "#FFFFFF");
        so.addVariable("path", "{$ammap_path}/ammap/");
		so.addVariable("data_file", escape("map_data_world_continents.xml.php?report=sales&from={$from}&to={$to}"));
        so.addVariable("settings_file", escape("{$settings_file}"));		
		so.addVariable("preloader_color", "#999999");
		so.write("map_continents");
		
		// ]]>
	</script> 
		</div>
	</div>
	<div id="block_countries" style="{if $view!='countries'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
		<span id="table_title" class="clean_table_title">{t}Counties{/t}</span> 
		<div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999;margin-bottom:15px">
		</div>
		{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0} 
		<div id="table0" class="data_table_container dtable btable" style="font-size:85%">
		</div>
	</div>
	<div id="block_wregions" style="{if $view!='wregions'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
		<span id="table_title" class="clean_table_title">{t}World Regions{/t}</span> 
		<div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999;margin-bottom:15px">
		</div>
		{include file='table_splinter.tpl' table_id=1 filter_name=$filter_name1 filter_value=$filter_value1} 
		<div id="table1" class="data_table_container dtable btable" style="font-size:85%">
		</div>
	</div>
	<div id="block_continents" style="{if $view!='continents'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
		<span id="table_title" class="clean_table_title">{t}Continents{/t}</span> 
		<div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999;margin-bottom:15px">
		</div>
		{include file='table_splinter.tpl' table_id=2 filter_name=$filter_name2 filter_value=$filter_value2} 
		<div id="table2" class="data_table_container dtable btable" style="font-size:85%">
		</div>
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