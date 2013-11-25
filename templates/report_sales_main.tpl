{include file='header.tpl'} 
<div id="bd" style="padding:0">
			<input type="hidden" id="calendar_id" value="{$calendar_id}" />
			<input type="hidden" id="from" value="{$from}" />
			<input type="hidden" id="to" value="{$to}" />
			<input type="hidden" id="subject" value="report_sales" />
			<input type="hidden" id="subject_key" value="" />
			<input type="hidden" id="sales_currency" value="{$sales_currency}" />
			<input type="hidden" value="0" id="sales_index" />
			<input type="hidden" value="{t}Store{/t}" id="label_Store" />
			<input type="hidden" value="{t}Category{/t}" id="label_Category" />
			<input type="hidden" value="{t}Invoices{/t}" id="label_Invoices" />
			<input type="hidden" value="% {t}Invoices{/t}" id="label_Invoices_Share" />
			<input type="hidden" value="&Delta;{t}Last Yr Invoices{/t}" id="label_Invoices_Delta" />
			<input type="hidden" value="{t}Sales{/t}" id="label_Sales" />
			<input type="hidden" value="% {t}Sales{/t}" id="label_Sales_Share" />
			<input type="hidden" value="&Delta;{t}Last Yr Sales{/t}" id="label_Sales_Delta" />
			<input type="hidden" value="{t}Customers{/t}" id="label_Customers" />
			<input type="hidden" value="{t}Date{/t}" id="label_Date" />
			<input type="hidden" id="label_paginator_Page" value="{t}Page{/t}" />
			<input type="hidden" id="label_paginator_of" value="{t}of{/t}" />
			<input type="hidden" id="state_data" value="{$state_data}" />

	<div style="padding:0 20px">
		<div class="branch" style="width:280px;float:left;margin:0">
			<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a> &rarr; <a href="reports.php">{t}Reports{/t}</a> &rarr; {t}Sales{/t}</span> 
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
		<li><span class="item {if $block=='stores'}selected{/if}" id="stores"> <span> {t}Stores{/t}</span></span></li>
		<li><span class="item {if $block=='categories'}selected{/if}" id="categories"> <span> {t}Categories{/t}</span></span></li>
		<li><span class="item {if $block=='history'}selected{/if}" id="history"> <span> {t}Sales per period{/t}</span></span></li>
	</ul>
	<div class="tabbed_container no_padding blocks" style="min-height:400px;;margin-bottom:0px">
		<div id="calendar_container" style="padding:0 20px;padding-bottom:0px;">
			<div id="period_label_container" style="{if $period==''}display:none{/if}">
				<img src="art/icons/clock_16.png"> <span id="period_label">{$period_label}</span> 
			</div>
			{include file='calendar_splinter.tpl' } 
			<div style="clear:both">
			</div>
		</div>
		<div id="block_stores" class="block data_table" style="{if $block!='stores'}display:none;{/if}clear:both;margin-top:0px; ">
			<div style="display:none">
				<div class="buttons small left tabs">
					<button class="first item {if $stores_subblock=='sales'}selected{/if}" id="stores_subblock_sales" block_id="sales">{t}Sales{/t}</button> <button class="item {if $stores_subblock=='overview'}selected{/if}" id="stores_subblock_overview" block_id="overview">{t}Overview{/t}</button> 
				</div>
				<div class="tabs_base">
				</div>
			</div>
			<div id="subblock_stores_sales" class="edit_block_content" style="{if $stores_subblock!='sales'}display:none{/if}">
				<span class="clean_table_title">{t}Sales per store{/t} </span> 
				<div class="table_top_bar space">
				</div>
				{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0 no_filter=1} 
				<div id="table0" class="data_table_container dtable btable with_total" style="font-size:90%">
				</div>
			</div>
			<div id="subblock_stores_plot" class="edit_block_content" style="{if $stores_subblock!='plot'}display:none{/if}">
			</div>
		</div>
		<div id="block_categories" class="block data_table" style="{if $block!='categories'}display:none;{/if}clear:both;margin-top:0px;">
			<div style="display:none">
				<div class="buttons small left tabs">
					<button class="first item {if $categories_subblock=='sales'}selected{/if}" id="categories_subblock_sales" block_id="sales">{t}Sales{/t}</button> <button class="item {if $categories_subblock=='overview'}selected{/if}" id="categories_subblock_overview" block_id="overview">{t}Overview{/t}</button> 
				</div>
				<div class="tabs_base">
				</div>
			</div>
			<div id="subblock_categories_sales" class="edit_block_content" style="{if $categories_subblock!='sales'}display:none{/if}">

				<span class="clean_table_title">{t}Sales per invoice category{/t} </span> 
				<div class="table_top_bar space">
				</div>
				{include file='table_splinter.tpl' table_id=1 filter_name=$filter_name1 filter_value=$filter_value1 no_filter=1} 
				<div id="table1" class="data_table_container dtable btable with_total" style="font-size:90%">
				</div>
			
			</div>
			<div id="subblock_categories_plot" class="edit_block_content" style="{if $categories_subblock!='plot'}display:none{/if}">
			</div>
		</div>
		<div id="block_history" class="block data_table" style="{if $block!='history'}display:none;{/if}clear:both;margin-top:0px; ">
			<div class="buttons small left tabs">
				<button class="first item {if $history_subblock=='list'}selected{/if}" id="history_subblock_list" block_id="list">{t}List{/t}</button> <button class="item {if $history_subblock=='stores_plot'}selected{/if}" id="history_subblock_stores_plot" block_id="stores_plot">{t}Store's Sales Plot{/t}</button> <button class="item {if $history_subblock=='categories_plot'}selected{/if}" id="history_subblock_categories_plot" block_id="categories_plot">{t}Categorie's Sales Plot{/t}</button> 
			</div>
			<div class="tabs_base">
			</div>
			<div id="subblock_history_list" class="edit_block_content" style="{if $history_subblock!='list'}display:none{/if}">
			
			
			
					<span class="clean_table_title">{t}Sales History{/t}</span> 
					<div class="table_top_bar">
				</div>
				<div class="clusters">
					<div class="buttons small cluster group">
						<button id="change_sales_history_timeline_group"> &#x21b6 {$sales_history_timeline_group_label}</button> 
					</div>
					<div style="clear:both;margin-bottom:0px">
					</div>
				</div>
					{include file='table_splinter.tpl' table_id=2 filter_name=$filter_name2 filter_value=$filter_value2 no_filter=1 } 
					<div id="table2" style="font-size:85%" class="data_table_container dtable btable">
					</div>
				
			
			</div>
			<div id="subblock_history_stores_plot" class="edit_block_content" style="{if $history_subblock!='stores_plot'}display:none{/if}">
				<div id="div_plot_per_store" style="clear:both;border:1px solid #ccc">
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
			</div>
			<div id="subblock_history_categories_plot" class="edit_block_content" style="{if $history_subblock!='categories_plot'}display:none{/if}">
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
		</div>
	</div>
</div>

<div id="dialog_sales_history_timeline_group" style="padding:10px 20px 0px 10px">
	<table class="edit" border="0" style="width:200px">
		<tr style="height:5px">
			<td></td>
		</tr>
		<tbody id="sales_history_timeline_group_options">
			{foreach from=$timeline_group_sales_history_options item=menu } 
			<tr>
				<td> 
				<div class="buttons small">
					<button id="sales_history_timeline_group_{$menu.mode}" class="timeline_group {if $sales_history_timeline_group==$menu.mode}selected{/if}" style="float:none;margin:0px auto;min-width:120px" onclick="change_timeline_group(2,'sales_history','{$menu.mode}','{$menu.label}')"> {$menu.label}</button> 
				</div>
				</td>
			</tr>
			{/foreach} 
		</tbody>
	</table>
</div>


{include file='footer.tpl'} 