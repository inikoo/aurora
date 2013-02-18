{include file='header.tpl'} 
<div id="bd" style="padding:0px">
<script type="text/javascript" src="external_libs/amstock/amstock/swfobject.js"></script> 
	<input type="hidden" id="site_key" value="{$site->id}" />
	<input type="hidden" id="site_id" value="{$site->id}" />
	<input type="hidden" id="page_key" value="{$page->id}" />
	<div style="padding:0 20px">
		{include file='assets_navigation.tpl'} 
		<div class="branch">
			<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home"/></a>&rarr; {if $user->get_number_websites()>1}<a href="sites.php">{t}Websites{/t}</a>  &rarr; {/if}<img style="vertical-align:0px;margin-right:1px" src="art/icons/hierarchy.gif" alt="" /> <a href="site.php?id={$site->id}">{$site->get('Site URL')}</a> (<a href="store.php?id={$store->id}">{$store->get('Store Code')}</a>) &rarr; <img style="vertical-align:-1px;" src="art/icons/layout_bw.png" alt="" /> {$page->get('Page Code')}</span> 
		
		</div>
		<div class="top_page_menu">
			<div class="buttons" style="float:right">
				{if isset($next)}<img class="next" onmouseover="this.src='art/next_button.gif'" onmouseout="this.src='art/next_button.png'" title="{$next.title}" onclick="window.location='{$next.link}&update_heights=1'" src="art/next_button.png" alt="{t}Next{/t}" />{/if} {if $modify}
				<button onclick="window.location='edit_page.php?id={$page->id}'"><img src="art/icons/vcard_edit.png" alt=""> {t}Edit Page{/t}</button>{/if} <button onclick="window.location='page_preview.php?id={$page->id}&logged=1&update_heights=1'"><img src="art/icons/layout.png" alt=""> {t}View Page{/t}</button> 
    
                <a href="page.zip.php?id={$page->id}">{t}Export{/t}</a>
			</div>
			<div class="buttons" style="float:left">
				{if isset($prev)}<img class="previous" onmouseover="this.src='art/previous_button.gif'" onmouseout="this.src='art/previous_button.png'" title="{$prev.title}" onclick="window.location='{$prev.link}&update_heights=1'" src="art/previous_button.png" alt="{t}Previous{/t}" />{/if} 
				
				<span class="main_title"><img src="art/icons/page_bw.png" style="height:18px;position:relative;bottom:2px"/>
			<span class="id">{$page->get('Page Code')}</span> <span style="font-size:90%;color:#777">{$page->get('Page URL')}</span>
		</span>
				
				
			</div>
			<div style="clear:both">
			</div>
		</div>
		
	</div>
	<ul class="tabs" id="chooser_ul" style="clear:both;margin-top:15px">
		<li> <span class="item {if $block_view=='details'}selected{/if}" id="details"> <span> {t}Overview{/t}</span></span></li>
		<li> <span class="item {if $block_view=='hits'}selected{/if}" id="hits"> <span> {t}Hits{/t}</span></span></li>
		<li> <span style="display:none" class="item {if $block_view=='visitors'}selected{/if}" id="visitors"> <span> {t}Visitors{/t}</span></span></li>
		<li> <span class="item {if $block_view=='users'}selected{/if}" id="users"> <span> {t}Users{/t}</span></span></li>
	</ul>
	<div style="clear:both;width:100%;border-bottom:1px solid #ccc">
	</div>
	<div id="block_users" style="{if $block_view!='users'}display:none;{/if}clear:both;margin:25px 0 40px 0;padding:0 20px">
		{include file='table_splinter.tpl' table_id=1 filter_name=$filter_name1 filter_value=$filter_value1 no_filter=1  }
		<div  id="table1"   class="data_table_container dtable btable" style="font-size:85%"> </div>
	</div>

	<div id="block_details" style="{if $block_view!='details'}display:none;{/if}clear:both;margin:25px 0 40px 0;padding:0 20px">
		<div style="width:450px;float:left;margin-top:0">
			<table id="page_info" class="show_info_product">
				<tr>
					<td style="width:140px">{t}Type{/t}:</td>
					<td>{$page->get_formated_store_section()}</td>
				</tr>
				<tr>
					<td style="width:140px">{t}Header Title{/t}:</td>
					<td>{$page->get('Page Store Title')}</td>
				</tr>
				<tr>
					<td style="width:140px">{t}URL{/t}:</td>
					<td>{$page->get('Page URL')}</td>
				</tr>
				{foreach from=$page->get_all_redirects_data(true) item=redirect}
					<tr>
					<td style="width:140px"></td>
					<td style="font-size:80%;color:#777">{$redirect.Source} (303)</td>
				</tr>
				{/foreach}
				<tr>
					<td style="width:140px">{t}Link Label{/t}:</td>
					<td>{$page->get('Page Short Title')}</td>
				</tr>
			</table>
			<table border="0" style="width:100%;margin:0px;height:20">
					<tr>
						<td style="width:150px"></td>
						<td class="aright" style="width:100px">{t}All{/t}</td>
						<td class="aright" style="width:100px">{t}Users{/t}</td>
						<td style="width:40px"></td>
					</tr>
				</table>
			<table border="0" id="table_total_visitors" class="show_info_product">
					<tr>
						<td style="width:150px">{t}Page Hits{/t}:</td>
						<td style="width:100px" class="number aright">{$page->get('Total Acc Requests')}</td>
						<td style="width:100px" class="number aright">{$page->get('Total Acc Users Requests')}</td>
						<td style="width:40px"></td>
					</tr>
					<tr>
						<td>{t}Sessions{/t}:</td>
						<td class="number aright">{$page->get('Total Acc Sessions')}</td>
						<td class="number aright">{$page->get('Total Acc Users Sessions')}</td>
						<td style="width:40px"></td>
					</tr>
					<tr>
						<td>{t}Visitors{/t}:</td>
						<td class="number aright">{$page->get('Total Acc Visitors')}</td>
						<td class="number aright">{$page->get('Total Acc Users')}</td>
						<td style="width:40px"></td>
					</tr>
			</table>
			<table border="0" id="table_1day_visitors" class="show_info_product">
				<tr>
						<td style="width:150px">{t}Last 24h Hits{/t}:</td>
						<td style="width:100px" class="number aright"> {$page->get('1 Day Acc Requests')} </td>
						<td style="width:100px" class="number aright">{$page->get('1 Day Acc Users Requests')}</td>
						<td style="width:40px"></td>
					</tr>
					<tr>
						<td>{t}Last 24h Sessions{/t}:</td>
						<td class="number aright"> {$page->get('1 Day Acc Sessions')} </td>
						<td class="number aright"> {$page->get('1 Day Acc Users Sessions')} </td>
						<td style="width:40px"></td>
					</tr>
					<tr>
						<td>{t}Last 24h Visitors{/t}:</td>
						<td class="number aright"> {$page->get('1 Day Acc Visitors')} </td>
						<td class="number aright">{$page->get('1 Day Acc Users')}</td>
						<td style="width:40px"></td>
					</tr>
				<tr>
					<td style="width:140px">{t}Current Visitors{/t}:</td>
					<td class="number">
					<div>
						{$page->get('Current Visitors')}
					</div>
					</td>
				</tr>
			</table>
			<table class="show_info_product">
				<tr>
					<td style="width:100px">{t}Parent Pages{/t}:</td>
					<td> 
					<table >
						{foreach from=$page->get_found_in($site->get('Site URL')) item=found_in_page} 
						<tr>
							<td style="padding:0">{$found_in_page.found_in_label} <span class="id">(<a href="page.php?id={$found_in_page.found_in_key}">{$found_in_page.found_in_code}</a>)</span></td>
						</tr>
						{/foreach} 
					</table>
					</td>
				</tr>
				<tr>
					<td>{t}Related Pages{/t}:</td>
					<td> 
					<table style="font-size:80%">
						{foreach from=$page->get_see_also($site->get('Site URL')) item=see_also_page} 
						<tr>
							<td style="padding:0">{$see_also_page.see_also_label} <span class="id">(<a href="page.php?id={$see_also_page.see_also_key}">{$see_also_page.see_also_code}</a>)</span></td>
							<td style="padding-left:10px;font-style:italic;color:#777">{$see_also_page.see_also_correlation_formated} {$see_also_page.see_also_correlation_formated_value}</td>
						</tr>
						{/foreach} 
					</table>
					</td>
				</tr>
			</table>
		</div>
		<div style="{if $page->get('Page Upload State')!='Upload'}display:none;{/if}margin-left:20px;width:450px;float:left;position:relative;top:-12px">
			<span style="font-size:11px;color:#777;">{t}Live snapshot{/t}, {$page->get_snapshot_date()}</span> <img id="recapture_page" style="position:relative;top:-1px;cursor:pointer" src="art/icons/camera_bw.png" alt="recapture" /> <img style="width:470px" src="image.php?id={$page->get('Page Snapshot Image Key')}" alt="" /> 
		</div>
		<div style="{if $page->get('Page Upload State')=='Upload'}display:none;{/if}margin-left:20px;width:450px;float:left;position:relative;top:-12px">
			<span style="font-size:11px;color:#777;">{t}Preview snapshot{/t}<span id="capture_preview_date">, {$page->get_preview_snapshot_date()}</span></span> <img id="recapture_preview" style="position:relative;top:-1px;cursor:pointer" src="art/icons/camera_bw.png" alt="recapture" /><img id="recapture_preview_processing" style="display:none;height:12.5px;position:relative;top:-1px;" src="art/loading.png" /> <img id="page_preview_snapshot" style="width:470px" src="image.php?id={$page->get('Page Preview Snapshot Image Key')}" alt="" /> 
		</div>
		<div style="clear:both;margin-bottom:20px">
		</div>
	</div>
	<div id="block_hits" style="{if $block_view!='hits'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px"">

		{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0 no_filter=0  }
		<div  id="table0"   class="data_table_container dtable btable" style="font-size:85%"> </div>


{*}
		<div id="plot1" style="clear:both;border:1px solid #ccc">
			<div id="single_data_set">
				<strong>You need to upgrade your Flash Player</strong> 
			</div>
		</div>
<script type="text/javascript">
		// <![CDATA[
		var so = new SWFObject("external_libs/amstock/amstock/amstock.swf", "amstock", "905", "500", "8", "#FFFFFF");
		so.addVariable("path", "");
		so.addVariable("settings_file", encodeURIComponent("conf/plot_general_timeseries.xml.php?tipo=site_hits&site_key={$site->id}"));
		so.addVariable("preloader_color", "#999999");
		so.write("plot1");
		// ]]>
	</script> 

{*}
	</div>
	<div id="block_visitors" style="{if $block_view!='visitors'}display:none;{/if}clear:both;margin:20px 0 40px 0">
		<div id="plot2" style="clear:both;border:1px solid #ccc">
			<div id="single_data_set">
				<strong>You need to upgrade your Flash Player</strong> 
			</div>
		</div>
<script type="text/javascript">
		// <![CDATA[
		var so = new SWFObject("external_libs/amstock/amstock/amstock.swf", "amstock", "905", "500", "8", "#FFFFFF");
		so.addVariable("path", "");
		so.addVariable("settings_file", encodeURIComponent("conf/plot_general_timeseries.xml.php?tipo=site_visitors&site_key={$site->id}"));
		so.addVariable("preloader_color", "#999999");
		so.write("plot2");
		// ]]>
	</script> 
	</div>
</div>
{include file='footer.tpl'} 
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
