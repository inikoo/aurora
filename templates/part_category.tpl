{include file='header.tpl'} 
<div id="bd" style="padding:0px">
	<div style="padding:0 20px">
		{include file='locations_navigation.tpl'} 
		<input type="hidden" id="category_key" value="{$category->id}" />
		<div class="branch">
			<span>{if $user->get_number_warehouses()>1}<a href="warehouses.php">{t}Warehouses{/t}</a> &rarr; {/if}<a href="warehouse_parts.php">{t}Inventory{/t}</a> &rarr; {$category->get_smarty_tree('part_categories.php')}</span> 
		</div>
		<div class="top_page_menu">
			<div class="buttons" style="float:left">
				{if isset($parent_category)} <button onclick="window.location='part_categories.php?warehouse_id={$warehouse->id}&id={$parent_category->id}'"><img src="art/icons/arrow_up.png" alt=""> {$parent_category->get('Category Name')}</button> {/if} <button onclick="window.location='part_categories.php?warehouse_id={$warehouse->id}&id=0'"><img src="art/icons/house.png" alt=""> {t}Customers Categories{/t}</button> 
			</div>
			<div class="buttons" style="float:right">
				<button onclick="window.location='edit_part_category.php?id={$category->id}'"><img src="art/icons/table_edit.png" alt=""> {t}Edit Category{/t}</button> <button id="new_category"><img src="art/icons/add.png" alt=""> {t}Add Subcategory{/t}</button> 
			</div>
			<div style="clear:both">
			</div>
		</div>
		<div style="clear:left;">
			<h1>
				{t}Category{/t}: {$category->get('Category Label')}
			</h1>
		</div>
	</div>
	<ul class="tabs" id="chooser_ul" style="clear:both;margin-top:5px">
		<li> <span class="item {if $block_view=='subcategories'}selected{/if}" id="subcategories"> <span> {t}Subcategories{/t}</span></span></li>
		<li> <span class="item {if $block_view=='subjects'}selected{/if}" id="subjects"> <span> {t}Parts{/t}</span></span></li>
		<li> <span class="item {if $block_view=='subcategories_charts'}selected{/if}" id="subcategories_charts"> <span> {t}Charts{/t}</span></span></li>
		<li> <span class="item {if $block_view=='history'}selected{/if}" id="history"> <span> {t}History{/t}</span></span></li>
	</ul>
	<div style="clear:both;width:100%;border-bottom:1px solid #ccc">
	</div>
	<div id="block_subcategories" style="{if $block_view!='subcategories'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
		<div class="data_table" style="clear:both;margin-bottom:20px">
			<span class="clean_table_title">Subcategories</span> {include file='table_splinter.tpl' table_id=1 filter_name=$filter_name0 filter_value=$filter_value0 } 
			<div id="table1" class="data_table_container dtable btable ">
			</div>
		</div>
	</div>
	<div id="block_subjects" style="{if $block_view!='subjects'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
		<div id="children_table" class="data_table">
			<span class="clean_table_title">{t}Parts in this category{/t} <img class="export_data_link" id="export_csv2" label="{t}Export (CSV){/t}" alt="{t}Export (CSV){/t}" src="art/icons/export_csv.gif"></span> 
			<div id="table_type" class="table_type">
				<div style="font-size:90%" id="transaction_chooser">
					<span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements.NotKeeping}selected{/if} label_part_NotKeeping" id="elements_NotKeeping" table_type="NotKeeping">{t}NotKeeping{/t} (<span id="elements_orders_number">{$elements_number.NotKeeping}</span>)</span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements.Discontinued}selected{/if} label_part_Discontinued" id="elements_Discontinued" table_type="Discontinued">{t}Discontinued{/t} (<span id="elements_orders_number">{$elements_number.Discontinued}</span>)</span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements.LastStock}selected{/if} label_part_LastStock" id="elements_LastStock" table_type="LastStock">{t}LastStock{/t} (<span id="elements_orders_number">{$elements_number.LastStock}</span>)</span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements.Keeping}selected{/if} label_part_Keeping" id="elements_Keeping" table_type="Keeping">{t}Keeping{/t} (<span id="elements_orders_number">{$elements_number.Keeping}</span>)</span> 
				</div>
			</div>
			<div class="table_top_bar">
			</div>
			<div class="clusters">
				<div class="buttons small left cluster">
					<button class="{if $parts_view=='general'}selected{/if}" id="parts_general" name="general">{t}Description{/t}</button> 
					<button class="{if $parts_view=='stock'}selected{/if}" id="parts_stock" name="stock">{t}Stock{/t}</button> 
					<button class="{if $parts_view=='locations'}selected{/if}" id="parts_locations" name="locations">{t}Locations{/t}</button> 

					<button class="{if $parts_view=='sales'}selected{/if}" id="parts_sales" name="sales">{t}Sales{/t}</button> 
					<button class="{if $parts_view=='forecast'}selected{/if}" id="parts_forecast" name="forecast">{t}Forecast{/t}</button> 
				</div>
				<div class="buttons small left cluster" id="period_options" style="{if $parts_view=='general' or $parts_view=='locations' };display:none{/if}">
					<button class="{if $parts_period=='all'}selected{/if}" period="all" id="parts_period_all">{t}All{/t}</button> 
					<button class="{if $parts_period=='three_year'}selected{/if}" period="three_year" id="parts_period_three_year">{t}3Y{/t}</button> 
					<button class="{if $parts_period=='year'}selected{/if}" period="year" id="parts_period_year">{t}1Yr{/t}</button> 
					<button class="{if $parts_period=='six_month'}selected{/if}" period="six_month" id="parts_period_six_month">{t}6M{/t}</button>
					<button class="{if $parts_period=='quarter'}selected{/if}" period="quarter" id="parts_period_quarter">{t}1Qtr{/t}</button>
					<button class="{if $parts_period=='month'}selected{/if}" period="month" id="parts_period_month">{t}1M{/t}</button> 
					<button class="{if $parts_period=='ten_day'}selected{/if}" period="ten_day" id="parts_period_ten_day">{t}10D{/t}</button> 
					<button class="{if $parts_period=='week'}selected{/if}" period="week" id="parts_period_week">{t}1W{/t}</button> 
					<button class="{if $parts_period=='yeartoday'}selected{/if}" period="yeartoday" id="parts_period_yeartoday">{t}YTD{/t}</button> 
					<button class="{if $parts_period=='monthtoday'}selected{/if}" period="monthtoday" id="parts_period_monthtoday">{t}MTD{/t}</button>
					<button class="{if $parts_period=='weektoday'}selected{/if}" period="weektoday" id="parts_period_weektoday">{t}WTD{/t}</button> 
					<button class="{if $parts_period=='today'}selected{/if}" period="today" id="parts_period_today">{t}Today{/t}</button> 
				</div>
				<div class="buttons small left cluster" id="avg_options" style="{if $parts_view!='sales' };display:none{/if};display:none">
					<button class="{if $parts_avg=='totals'}selected{/if}" avg="totals" id="avg_totals">{t}Totals{/t}</button>
					<button class="{if $parts_avg=='month'}selected{/if}" avg="month" id="avg_month">{t}M AVG{/t}</button> 
					<button class="{if $parts_avg=='week'}selected{/if}" avg="week" id="avg_week">{t}W AVG{/t}</button> 
					<button class="{if $parts_avg=='month_eff'}selected{/if}" style="display:none" avg="month_eff" id="avg_month_eff">{t}M EAVG{/t}</button> 
					<button class="{if $parts_avg=='week_eff'}selected{/if}" style="display:none" avg="week_eff" id="avg_week_eff">{t}W EAVG{/t}</button> 
				</div>
				<div style="clear:both">
				</div>
			</div>
			{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0} 
			<div id="table0" class="data_table_container dtable btable " style="font-size:90%">
			</div>
		</div>
	</div>
	<div id="block_subcategories_charts" style="{if $block_view!='subcategories_charts'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
		<div style="float:left" id="plot_referral_1">
			<strong>You need to upgrade your Flash Player</strong> 
		</div>
<script type="text/javascript">
		// <![CDATA[		
		var so = new SWFObject("external_libs/ampie/ampie/ampie.swf", "ampie", "350", "300", "1", "#FFFFFF");
		so.addVariable("path", "external_libs/ampie/ampie/");
		so.addVariable("settings_file", encodeURIComponent("conf/pie_settings.xml.php"));                // you can set two or more different settings files here (separated by commas)
		so.addVariable("data_file", encodeURIComponent("plot_data.csv.php?tipo=category&category_key={$category->id}")); 
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
		so.addVariable("data_file", encodeURIComponent("plot_data.csv.php?tipo=category_subjects&category_key={$category->id}")); 
		so.addVariable("loading_settings", "LOADING SETTINGS");
		so.addVariable("loading_settings", "LOADING SETTINGS");  // you can set custom "loading settings" text here
		so.addVariable("loading_data", "LOADING DATA");                                                 // you can set custom "loading data" text here

		so.write("plot_referral_2");
		// ]]>
	</script> 
	</div>
	<div id="block_history" style="{if $block_view!='history'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
		<span class="clean_table_title">{t}History{/t}</span> {include file='table_splinter.tpl' table_id=2 filter_name=$filter_name2 filter_value=$filter_value2 } 
		<div id="table2" class="data_table_container dtable btable ">
		</div>
	</div>
</div>
{include file='footer.tpl'} {include file='new_category_splinter.tpl'} 