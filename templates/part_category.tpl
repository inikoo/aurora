{include file='header.tpl'} 
<div id="bd" style="padding:0px">
	<div style="padding:0 20px">
		{include file='locations_navigation.tpl'} 
		<input type="hidden" id="category_key" value="{$category->id}" />
		<input type="hidden" id="state_type" value="{$state_type}" />
		<input type="hidden" id="link_extra_argument" value="&id={$category->id}" />
	<input type="hidden" id="from" value="{$from}" />
	<input type="hidden" id="to" value="{$to}" />
		
		
		<div class="branch">
			<span> <a href="index.php"> <img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /> </a> &rarr; {if $user->get_number_warehouses()>1}<a href="warehouses.php">{t}Warehouses{/t} </a> &rarr; {/if}<a href="warehouse_parts.php?warehouse_id={$warehouse->id}">{t}Inventory{/t} </a> &rarr; <a href="part_categories.php?&warehouse_id={$warehouse->id}"> {t}Parts Categories{/t} </a> &rarr; {$category->get('Category XHTML Branch Tree')} </span> 
		</div>
		<div class="top_page_menu">
			<div class="buttons" style="float:left">
				{if isset($prev)}<img class="previous" onmouseover="this.src='art/previous_button.gif'" onmouseout="this.src='art/previous_button.png'" title="{$prev.title}" onclick="window.location='{$prev.link}'" src="art/previous_button.png" alt="{t}Previous{/t}" />{/if} <span class="main_title"> {t}Category{/t}: <span class="id">{$category->get('Category Label')}</span> {$category->get_icon()}</span> 
			</div>
			<div class="buttons" style="float:right">
				{if isset($next)}<img class="next" onmouseover="this.src='art/next_button.gif'" onmouseout="this.src='art/next_button.png'" title="{$next.title}" onclick="window.location='{$next.link}'" src="art/next_button.png" alt="{t}Next{/t}" />{/if} <button onclick="window.location='edit_part_category.php?id={$category->id}'"> <img src="art/icons/table_edit.png" alt=""> {t}Edit Category{/t} </button> 
			</div>
			<div style="clear:both">
			</div>
		</div>
	</div>
	<ul class="tabs" id="chooser_ul" style="clear:both;margin-top:10px">
		<li> <span class="item {if $block_view=='overview'}selected{/if}" id="overview"> <span> {t}Overview{/t}</span></span> </li>
		<li style="{if !$show_subcategories}display:none{/if}"> <span class="item {if $block_view=='subcategories'}selected{/if}" id="subcategories"> <span> {t}Subcategories{/t} ({$category->get('Number Children')})</span></span> </li>
		<li style="{if !$show_subjects}display:none{/if}"> <span class="item {if $block_view=='subjects'}selected{/if}" id="subjects"> <span> {t}Parts{/t} ({$category->get('Number Subjects')})</span></span> </li>
		<li style="{if !$show_subjects_data}display:none{/if};"> <span class="item {if $block_view=='sales'}selected{/if}" id="sales"> <span> {t}Sales{/t}</span></span> </li>
			<li> <span class="item {if $block_view=='history'}selected{/if}" id="history"> <span> {t}Changelog{/t}</span></span> </li>

	</ul>
	<div style="clear:both;width:100%;border-bottom:1px solid #ccc">
	</div>
	<div id="block_sales" style="{if $block_view!='sales'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px;">
					{include file='calendar_splinter.tpl'} 

			<div style="margin-top:20px;width:900px;{if !$show_subjects_data}display:none{/if}">
			<span><img src="art/icons/clock_16.png" style="height:12px;position:relative;bottom:2px"> {$period}</span> 
			<div style="margin-top:0px">
				<div style="width:200px;float:left;margin-left:0px;">
					<table style="clear:both" class="show_info_product">
						<tbody>
							<tr>
								<td>{t}Sales{/t}:</td>
								<td class="aright" id="sales_amount"><img style="height:14px" src="art/loading.gif" /></td>
							</tr>
							<tr>
								<td>{t}Profit{/t}:</td>
								<td class="aright" id="profits"><img style="height:14px" src="art/loading.gif" /></td>
							</tr>
							<tr>
								<td>{t}Margin{/t}:</td>
								<td class="aright" id="margin"><img style="height:14px" src="art/loading.gif" /></td>
							</tr>
							<tr>
								<td>{t}GMROI{/t}:</td>
								<td class="aright" id="gmroi"><img style="height:14px" src="art/loading.gif" /></td>
							</tr>
						</tbody>
					</table>
				</div>
				<div style="float:left;margin-left:20px">
					<table style="width:200px;clear:both" class="show_info_product">
						<tbody id="no_supplied_tbody" style="display:none">
							<tr>
								<td>{t}Required{/t}:</td>
								<td class="aright" id="required"><img style="height:14px" src="art/loading.gif" /></td>
							</tr>
							<tr>
								<td>{t}Out of Stock{/t}:</td>
								<td class="aright error" id="out_of_stock"><img style="height:14px" src="art/loading.gif" /></td>
							</tr>
							<tr>
								<td>{t}Not Found{/t}:</td>
								<td class="aright error" id="not_found"><img style="height:14px" src="art/loading.gif" /></td>
							</tr>
						</tbody>
						<tbody>
							<tr>
								<td>{t}Sold{/t}:</td>
								<td class="aright" id="sold"><img style="height:14px" src="art/loading.gif" /></td>
							</tr>
							<tr id="given_tr" style="display:none">
								<td>{t}Given for free{/t}:</td>
								<td class="aright"><img style="height:14px" src="art/loading.gif" /></td>
							</tr>
							<tr id="broken_tr" style="display:none">
								<td>{t}Broken{/t}:</td>
								<td class="aright" id="broken"><img style="height:14px" src="art/loading.gif" /></td>
							</tr>
							<tr id="lost_tr" style="display:none">
								<td>{t}Lost{/t}:</td>
								<td class="aright" id="lost"><img style="height:14px" src="art/loading.gif" /></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<div style="clear:both;">
			</div>
		</div>
				
				
				<div id="sales_sub_blocks" style="clear:both;">
			<ul class="tabs" id="chooser_ul" style="margin-top:10px">
				<li> <span class="item {if $sales_sub_block_tipo=='plot_parts_sales'}selected{/if}" onclick="change_sales_sub_block(this)" id="plot_parts_sales" > <span>{t}Sales Chart{/t}</span> </span> </li>
				<li style="display:none"> <span class="item {if $sales_sub_block_tipo=='parts_sales_timeseries'}selected{/if}" onclick="change_sales_sub_block(this)" id="part_sales_timeseries" tipo="store"> <span>{t}Part Sales History{/t}</span> </span> </li>
			</ul>
			<div id="sub_block_plot_parts_sales" style="min-height:400px;clear:both;border:1px solid #ccc;{if $sales_sub_block_tipo!='plot_parts_sales'}display:none{/if}">
<script type="text/javascript" src="external_libs/amstock/amstock/swfobject.js"></script> <script type="text/javascript">
		// <![CDATA[
		var so = new SWFObject("external_libs/amstock/amstock/amstock.swf", "amstock", "905", "500", "8", "#FFFFFF");
		so.addVariable("path", "");
		so.addVariable("settings_file", encodeURIComponent("conf/plot_asset_sales.xml.php?tipo=part_category_sales&category_key={$category->id}"));
		
		so.addVariable("preloader_color", "#999999");
		so.write("sub_block_plot_parts_sales");
		// ]]>
	</script> 
				<div style="clear:both">
				</div>
			</div>
			<div id="sub_block_parts_sales_timeseries" style="padding:20px;min-height:400px;clear:both;border:1px solid #ccc;{if $sales_sub_block_tipo!='parts_sales_timeseries'}display:none{/if}">
				<span class="clean_table_title">{t}Part Sales History{/t}</span> 
				<div>
					<span tipo='year' id="part_sales_history_type_year" style="float:right" class="table_type state_details {if $part_sales_history_type=='year'}selected{/if}">{t}Yearly{/t}</span> <span tipo='month' id="part_sales_history_type_month" style="float:right;margin-right:10px" class="table_type state_details {if $part_sales_history_type=='month'}selected{/if}">{t}Monthly{/t}</span> <span tipo='week' id="part_sales_history_type_week" style="float:right;margin-right:10px" class="table_type state_details {if $part_sales_history_type=='week'}selected{/if}">{t}Weekly{/t}</span> <span tipo='day' id="part_sales_history_type_day" style="float:right;margin-right:10px" class="table_type state_details {if $part_sales_history_type=='day'}selected{/if}">{t}Daily{/t}</span> 
				</div>
				<div  class="table_top_bar"  style="margin-bottom:10px">
				</div>
				{include file='table_splinter.tpl' table_id=4 filter_name=$filter_name4 filter_value=$filter_value4 no_filter=1 } 
				<div id="table4" style="font-size:85%" class="data_table_container dtable btable">
				</div>
			</div>

			<div style="clear:both;">
			</div>
		</div>
			
				
				
				
			
	</div>
	<div id="block_subcategories" style="{if $block_view!='subcategories'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
		<div class="data_table" style="clear:both;margin-bottom:20px">
			<span class="clean_table_title"> {t}Subcategories{/t} </span> 
			<div class="table_top_bar">
			</div>
			<div class="clusters">
				<div class="buttons small left cluster">
					<button class="{if $subcategories_view=='sales'}selected{/if}" id="subcategories_sales" name="sales"> {t}Sales{/t} </button> 
				</div>
				<div class="buttons small left cluster" id="period_options" style="{if $subcategories_view=='general' or $subcategories_view=='locations' };display:none{/if}">
					<button class="{if $subcategories_period=='all'}selected{/if}" period="all" id="subcategories_period_all"> {t}All{/t} </button> <button style="margin-left:4px" class="{if $subcategories_period=='yeartoday'}selected{/if}" period="yeartoday" id="subcategories_period_yeartoday"> {t}YTD{/t} </button> <button class="{if $subcategories_period=='monthtoday'}selected{/if}" period="monthtoday" id="subcategories_period_monthtoday"> {t}MTD{/t} </button> <button class="{if $subcategories_period=='weektoday'}selected{/if}" period="weektoday" id="subcategories_period_weektoday"> {t}WTD{/t} </button> <button class="{if $subcategories_period=='today'}selected{/if}" period="today" id="subcategories_period_today"> {t}Today{/t} </button> <button style="margin-left:4px" class="{if $subcategories_period=='yesterday'}selected{/if}" period="yesterday" id="subcategories_period_yesterday"> {t}YD{/t} </button> <button class="{if $subcategories_period=='last_w'}selected{/if}" period="last_w" id="subcategories_period_last_w"> {t}LW{/t} </button> <button class="{if $subcategories_period=='last_m'}selected{/if}" period="last_m" id="subcategories_period_last_m"> {t}LM{/t} </button> <button style="margin-left:4px" class="{if $subcategories_period=='three_year'}selected{/if}" period="three_year" id="subcategories_period_three_year"> {t}3Y{/t} </button> <button class="{if $subcategories_period=='year'}selected{/if}" period="year" id="subcategories_period_year"> {t}1Yr{/t} </button> <button class="{if $subcategories_period=='six_month'}selected{/if}" period="six_month" id="subcategories_period_six_month"> {t}6M{/t} </button> <button class="{if $subcategories_period=='quarter'}selected{/if}" period="quarter" id="subcategories_period_quarter"> {t}1Qtr{/t} </button> <button class="{if $subcategories_period=='month'}selected{/if}" period="month" id="subcategories_period_month"> {t}1M{/t} </button> <button class="{if $subcategories_period=='ten_day'}selected{/if}" period="ten_day" id="subcategories_period_ten_day"> {t}10D{/t} </button> <button class="{if $subcategories_period=='week'}selected{/if}" period="week" id="subcategories_period_week"> {t}1W{/t} </button> 
				</div>
				<div class="buttons small left cluster" id="avg_options" style="{if $subcategories_view!='sales' };display:none{/if};display:none">
					<button class="{if $subcategories_avg=='totals'}selected{/if}" avg="totals" id="avg_totals"> {t}Totals{/t} </button> <button class="{if $subcategories_avg=='month'}selected{/if}" avg="month" id="avg_month"> {t}M AVG{/t} </button> <button class="{if $subcategories_avg=='week'}selected{/if}" avg="week" id="avg_week"> {t}W AVG{/t} </button> <button class="{if $subcategories_avg=='month_eff'}selected{/if}" style="display:none" avg="month_eff" id="avg_month_eff"> {t}M EAVG{/t} </button> <button class="{if $subcategories_avg=='week_eff'}selected{/if}" style="display:none" avg="week_eff" id="avg_week_eff"> {t}W EAVG{/t} </button> 
				</div>
				<div style="clear:both">
				</div>
			</div>
			{include file='table_splinter.tpl' table_id=1 filter_name=$filter_name1 filter_value=$filter_value1 } 
			<div id="table1" class="data_table_container dtable btable" style="font-size:85%">
			</div>
		</div>
	</div>
	<div id="block_subjects" style="{if $block_view!='subjects'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
		<div id="children_table" class="data_table">
			<span class="clean_table_title"> {t}Parts in this category{/t} <img class="export_data_link" id="export_csv2" label="{t}Export (CSV){/t}" alt="{t}Export (CSV){/t}" src="art/icons/export_csv.gif"> </span> 
			<div id="table_type" class="table_type">
				<div style="font-size:90%" id="transaction_chooser">
					<span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements.NotKeeping}selected{/if} label_part_NotKeeping" id="elements_NotKeeping" table_type="NotKeeping"> {t}NotKeeping{/t} (<span id="elements_orders_number">{$elements_number.NotKeeping}</span>) </span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements.Discontinued}selected{/if} label_part_Discontinued" id="elements_Discontinued" table_type="Discontinued"> {t}Discontinued{/t} (<span id="elements_orders_number">{$elements_number.Discontinued}</span>) </span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements.LastStock}selected{/if} label_part_LastStock" id="elements_LastStock" table_type="LastStock"> {t}LastStock{/t} (<span id="elements_orders_number">{$elements_number.LastStock}</span>) </span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements.Keeping}selected{/if} label_part_Keeping" id="elements_Keeping" table_type="Keeping"> {t}Keeping{/t} (<span id="elements_orders_number">{$elements_number.Keeping}</span>) </span> 
				</div>
			</div>
			<div class="table_top_bar">
			</div>
			<div class="clusters">
				<div class="buttons small left cluster">
					<button class="{if $parts_view=='general'}selected{/if}" id="parts_general" name="general"> {t}Description{/t} </button> <button class="{if $parts_view=='stock'}selected{/if}" id="parts_stock" name="stock"> {t}Stock{/t} </button> <button class="{if $parts_view=='locations'}selected{/if}" id="parts_locations" name="locations"> {t}Locations{/t} </button> <button class="{if $parts_view=='sales'}selected{/if}" id="parts_sales" name="sales"> {t}Sales{/t} </button> <button class="{if $parts_view=='forecast'}selected{/if}" id="parts_forecast" name="forecast"> {t}Forecast{/t} </button> 
				</div>
				<div class="buttons small left cluster" id="part_period_options" style="{if $parts_view=='general' or $parts_view=='locations' };display:none{/if}">
					<button class="{if $parts_period=='all'}selected{/if}" period="all" id="parts_period_all">{t}All{/t}</button> <button style="margin-left:4px" class="{if $parts_period=='yeartoday'}selected{/if}" period="yeartoday" id="parts_period_yeartoday">{t}YTD{/t}</button> <button class="{if $parts_period=='monthtoday'}selected{/if}" period="monthtoday" id="parts_period_monthtoday">{t}MTD{/t}</button> <button class="{if $parts_period=='weektoday'}selected{/if}" period="weektoday" id="parts_period_weektoday">{t}WTD{/t}</button> <button class="{if $parts_period=='today'}selected{/if}" period="today" id="parts_period_today">{t}Today{/t}</button> <button style="margin-left:4px" class="{if $parts_period=='yesterday'}selected{/if}" period="yesterday" id="parts_period_yesterday">{t}YD{/t}</button> <button class="{if $parts_period=='last_w'}selected{/if}" period="last_w" id="parts_period_last_w">{t}LW{/t}</button> <button class="{if $parts_period=='last_m'}selected{/if}" period="last_m" id="parts_period_last_m">{t}LM{/t}</button> <button style="margin-left:4px" class="{if $parts_period=='three_year'}selected{/if}" period="three_year" id="parts_period_three_year">{t}3Y{/t}</button> <button class="{if $parts_period=='year'}selected{/if}" period="year" id="parts_period_year">{t}1Yr{/t}</button> <button class="{if $parts_period=='six_month'}selected{/if}" period="six_month" id="parts_period_six_month">{t}6M{/t}</button> <button class="{if $parts_period=='quarter'}selected{/if}" period="quarter" id="parts_period_quarter">{t}1Qtr{/t}</button> <button class="{if $parts_period=='month'}selected{/if}" period="month" id="parts_period_month">{t}1M{/t}</button> <button class="{if $parts_period=='ten_day'}selected{/if}" period="ten_day" id="parts_period_ten_day">{t}10D{/t}</button> <button class="{if $parts_period=='week'}selected{/if}" period="week" id="parts_period_week">{t}1W{/t}</button> 
				</div>
				<div class="buttons small left cluster" id="avg_options" style="{if $parts_view!='sales' };display:none{/if};display:none">
					<button class="{if $parts_avg=='totals'}selected{/if}" avg="totals" id="avg_totals"> {t}Totals{/t} </button> <button class="{if $parts_avg=='month'}selected{/if}" avg="month" id="avg_month"> {t}M AVG{/t} </button> <button class="{if $parts_avg=='week'}selected{/if}" avg="week" id="avg_week"> {t}W AVG{/t} </button> <button class="{if $parts_avg=='month_eff'}selected{/if}" style="display:none" avg="month_eff" id="avg_month_eff"> {t}M EAVG{/t} </button> <button class="{if $parts_avg=='week_eff'}selected{/if}" style="display:none" avg="week_eff" id="avg_week_eff"> {t}W EAVG{/t} </button> 
				</div>
				<div style="clear:both">
				</div>
			</div>
			{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0} 
			<div id="table0" class="data_table_container dtable btable" style="font-size:90%">
			</div>
		</div>
	</div>
	<div id="block_overview" style="{if $block_view!='overview'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
		
	</div>
	<div id="block_history" style="{if $block_view!='history'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
		<span class="clean_table_title"> {t}Changelog{/t} </span> 
		<div id="table_type" class="table_type">
			<div style="font-size:90%" id="part_type_chooser">
				<span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $history_elements.Change}selected{/if} label_part_Change" id="elements_Change" table_type="Change">{t}Change{/t} (<span id="elements_Change_number">{$history_elements_number.Change}</span>)</span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $history_elements.Assign}selected{/if} label_part_Assign" id="elements_Assign" table_type="Assign">{t}Assig{/t} (<span id="elements_Assign_number">{$history_elements_number.Assign}</span>)</span> 
			</div>
		</div>
		<div class="table_top_bar" style="margin-bottom:15px">
		</div>
		{include file='table_splinter.tpl' table_id=2 filter_name=$filter_name2 filter_value=$filter_value2 } 
		<div id="table2" class="data_table_container dtable btable">
		</div>
	</div>
</div>
<div id="rppmenu0" class="yuimenu">
	<div class="bd">
		<ul class="first-of-type">
			<li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd"> {t}Rows per Page{/t}: </li>
			{foreach from=$paginator_menu0 item=menu } 
			<li class="yuimenuitem"> <a class="yuimenuitemlabel" onclick="change_rpp({$menu},0)"> {$menu}</a> </li>
			{/foreach} 
		</ul>
	</div>
</div>
<div id="filtermenu0" class="yuimenu">
	<div class="bd">
		<ul class="first-of-type">
			<li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd"> {t}Filter options{/t}: </li>
			{foreach from=$filter_menu0 item=menu } 
			<li class="yuimenuitem"> <a class="yuimenuitemlabel" onclick="change_filter('{$menu.db_key}','{$menu.label}',0)"> {$menu.menu_label}</a> </li>
			{/foreach} 
		</ul>
	</div>
</div>
<div id="rppmenu1" class="yuimenu">
	<div class="bd">
		<ul class="first-of-type">
			<li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd"> {t}Rows per Page{/t}: </li>
			{foreach from=$paginator_menu1 item=menu } 
			<li class="yuimenuitem"> <a class="yuimenuitemlabel" onclick="change_rpp({$menu},1)"> {$menu}</a> </li>
			{/foreach} 
		</ul>
	</div>
</div>
<div id="filtermenu1" class="yuimenu">
	<div class="bd">
		<ul class="first-of-type">
			<li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd"> {t}Filter options{/t}: </li>
			{foreach from=$filter_menu1 item=menu } 
			<li class="yuimenuitem"> <a class="yuimenuitemlabel" onclick="change_filter('{$menu.db_key}','{$menu.label}',1)"> {$menu.menu_label}</a> </li>
			{/foreach} 
		</ul>
	</div>
</div>
<div id="rppmenu2" class="yuimenu">
	<div class="bd">
		<ul class="first-of-type">
			<li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd"> {t}Rows per Page{/t}: </li>
			{foreach from=$paginator_menu2 item=menu } 
			<li class="yuimenuitem"> <a class="yuimenuitemlabel" onclick="change_rpp({$menu},2)"> {$menu}</a> </li>
			{/foreach} 
		</ul>
	</div>
</div>
<div id="filtermenu2" class="yuimenu">
	<div class="bd">
		<ul class="first-of-type">
			<li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd"> {t}Filter options{/t}: </li>
			{foreach from=$filter_menu2 item=menu } 
			<li class="yuimenuitem"> <a class="yuimenuitemlabel" onclick="change_filter('{$menu.db_key}','{$menu.label}',2)"> {$menu.menu_label}</a> </li>
			{/foreach} 
		</ul>
	</div>
</div>
{include file='footer.tpl'} 