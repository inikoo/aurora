{include file='header.tpl'} 
<div id="bd" class="no_padding">
	<input type="hidden" id="category_key" value="{$category->id}" />
	<input type="hidden" id="state_type" value="{$state_type}" />
	<input type="hidden" id="from" value="{$from}" />
	<input type="hidden" id="to" value="{$to}" />
	<input type="hidden" id="subject_key" value="{$category->id}" />
	<input type="hidden" id="subject" value="supplier_categories" />
	<input type="hidden" id="calendar_id" value="sales" />
	<div style="padding:0 20px">
		{include file='suppliers_navigation.tpl'} 
		<div class="branch">
			<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a>&rarr; <a href="suppliers.php">{t}Suppliers{/t}</a> &rarr; <a href="supplier_categories.php">{t}Suppliers Categories{/t}</a> &rarr; {$category->get('Category XHTML Branch Tree')}</span> 
		</div>
		<div class="top_page_menu">
			<div class="buttons" style="float:left">
				{if isset($prev)}<img class="previous" onmouseover="this.src='art/previous_button.gif'" onmouseout="this.src='art/previous_button.png'" title="{$prev.title}" onclick="window.location='{$prev.link}'" src="art/previous_button.png" alt="{t}Previous{/t}" />{/if} <span class="main_title"> {t}Category{/t}: <span class="id">{$category->get('Category Label')}</span> {$category->get_icon()}</span> 
			</div>
			<div class="buttons" style="float:right">
				{if isset($next)}<img class="next" onmouseover="this.src='art/next_button.gif'" onmouseout="this.src='art/next_button.png'" title="{$next.title}" onclick="window.location='{$next.link}'" src="art/next_button.png" alt="{t}Next{/t}" />{/if} <button onclick="window.location='edit_supplier_category.php?id={$category->id}'"> <img src="art/icons/table_edit.png" alt=""> {t}Edit Category{/t} </button> 
			</div>
			<div style="clear:both">
			</div>
		</div>
	</div>
	<ul class="tabs" id="chooser_ul" style="clear:both;margin-top:10px">
		<li> <span class="item {if $block_view=='overview'}selected{/if}" id="overview"> <span> {t}Overview{/t}</span></span> </li>
		<li style="{if !$show_subcategories}display:none{/if}"> <span class="item {if $block_view=='subcategories'}selected{/if}" id="subcategories"> <span> {t}Subcategories{/t} ({$category->get('Number Children')})</span></span> </li>
		<li style="{if !$show_subjects}display:none{/if}"> <span class="item {if $block_view=='subjects'}selected{/if}" id="subjects"> <span> {t}Suppliers{/t} ({$category->get('Number Subjects')})</span></span> </li>
		<li style="{if !$show_subjects_data}display:none{/if};"> <span class="item {if $block_view=='sales'}selected{/if}" id="sales"> <span> {t}Sales{/t}</span></span> </li>
		<li> <span class="item {if $block_view=='history'}selected{/if}" id="history"> <span> {t}Changelog{/t}</span></span> </li>
	</ul>
	<div class="tabs_base">
	</div>
	<div id="block_sales" style="{if $block_view!='sales'}display:none;{/if}">
		<div style="padding:0px 20px">
			<div id="calendar_container" style="padding:0 20px;padding-bottom:0px;margin-top:0px;border:1px solid white">
				<div id="period_label_container" style="{if $period==''}display:none{/if}">
					<img src="art/icons/clock_16.png"> <span id="period_label">{$period_label}</span> 
				</div>
				{include file='calendar_splinter.tpl' calendar_id='sales' calendar_link='part.php'} 
				<div style="clear:both">
				</div>
			</div>
			<div style="margin-top:20px;width:900px">
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
					<li> <span class="item {if $sales_block=='plot_supplier_sales'}selected{/if}" onclick="change_sales_sub_block(this)" id="plot_supplier_sales"> <span>{t}Sales Chart{/t}</span> </span> </li>
					<li> <span class="item {if $sales_block=='supplier_timeseries'}selected{/if}" onclick="change_sales_sub_block(this)" id="supplier_timeseries"> <span>{t}In/Out History{/t}</span> </span> </li>
					<li> <span class="item {if $sales_block=='supplier_product_sales'}selected{/if}" onclick="change_sales_sub_block(this)" id="supplier_product_sales" tipo="list" forecast="" interval=""> <span>{t}Supplier Product's Sold{/t}</span> </span> </li>
				</ul>
				<div id="sub_block_plot_supplier_sales" style="min-height:400px;clear:both;border:1px solid #ccc;{if $sales_block!='plot_supplier_sales'}display:none{/if}">

					<div style="clear:both" id='plot_supplier_sales_div'>x
					</div>
				</div>
				<div id="sub_block_supplier_timeseries" style="padding:20px;min-height:400px;clear:both;border:1px solid #ccc;{if $sales_block!='supplier_timeseries'}display:none{/if}">
					<span class="clean_table_title">{t}Sales History{/t}</span> 
					<div class="table_top_bar">
					</div>
					<div class="clusters">
						<div class="buttons small cluster group">
							<button id="change_sales_history_timeline_group"> &#x21b6 {$sales_history_timeline_group_label}</button> 
						</div>
						<div style="clear:both;margin-bottom:5px">
						</div>
					</div>
					{include file='table_splinter.tpl' table_id=7 filter_name=$filter_name7 filter_value=$filter_value7 no_filter=1 } 
					<div id="table7" style="font-size:85%" class="data_table_container dtable btable">
					</div>
				</div>
				<div id="sub_block_supplier_product_sales" style="padding:20px;min-height:400px;clear:both;border:1px solid #ccc;{if $sales_block!='supplier_product_sales'}display:none{/if}">
					<span class="clean_table_title">{t}Supplier Product's Sold{/t}</span> 
					<div class="table_top_bar space">
					</div>
					{include file='table_splinter.tpl' table_id=6 filter_name=$filter_name6 filter_value=$filter_value6 no_filter=1 } 
					<div id="table6" style="font-size:85%" class="data_table_container dtable btable">
					</div>
				</div>
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
			<span class="clean_table_title"> {t}Suppliers in this category{/t} <img class="export_data_link" id="export_csv2" label="{t}Export (CSV){/t}" alt="{t}Export (CSV){/t}" src="art/icons/export_csv.gif"> </span> {*} 
			<div class="elements_chooser">
				<span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements.NotKeeping}selected{/if} label_supplier_NotKeeping" id="elements_NotKeeping" table_type="NotKeeping"> {t}NotKeeping{/t} (<span id="elements_orders_number">{$elements_number.NotKeeping}</span>) </span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements.Discontinued}selected{/if} label_supplier_Discontinued" id="elements_Discontinued" table_type="Discontinued"> {t}Discontinued{/t} (<span id="elements_orders_number">{$elements_number.Discontinued}</span>) </span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements.LastStock}selected{/if} label_supplier_LastStock" id="elements_LastStock" table_type="LastStock"> {t}LastStock{/t} (<span id="elements_orders_number">{$elements_number.LastStock}</span>) </span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements.Keeping}selected{/if} label_supplier_Keeping" id="elements_Keeping" table_type="Keeping"> {t}Keeping{/t} (<span id="elements_orders_number">{$elements_number.Keeping}</span>) </span> 
			</div>
			{*} 
			<div class="table_top_bar">
			</div>
			<div class="clusters">
				<div class="buttons small left cluster">
					<button class="{if $suppliers_view=='general'}selected{/if}" id="suppliers_general">{t}General{/t}</button> <button class="{if $suppliers_view=='contact'}selected{/if}" id="suppliers_contact">{t}Contact{/t}</button> <button class="{if $suppliers_view=='products'}selected{/if}" id="suppliers_products">{t}Products{/t}</button> <button style="{if !$view_stock}display:none{/if}" class="{if $suppliers_view=='stock'}selected{/if}" id="suppliers_stock">{t}Parts Stock{/t}</button> <button style="{if !$view_sales}display:none{/if}" class="{if $suppliers_view=='sales'}selected{/if}" id="suppliers_sales">{t}Part's Sales{/t}</button> <button style="{if !$view_sales}display:none{/if}" class="{if $suppliers_view=='profit'}selected{/if}" id="suppliers_profit">{t}Profit{/t}</button> 
				</div>
				<div class="buttons small left cluster" id="suppliers_period_options" style="{if $suppliers_view!='sales' and  $suppliers_view!='profit'};display:none{/if}">
					<button class="table_option {if $suppliers_period=='all'}selected{/if}" period="all" id="suppliers_period_all">{t}All{/t}</button> <button style="margin-left:4px" class="table_option {if $suppliers_period=='yeartoday'}selected{/if}" period="yeartoday" id="suppliers_period_yeartoday">{t}YTD{/t}</button> <button class="table_option {if $suppliers_period=='monthtoday'}selected{/if}" period="monthtoday" id="suppliers_period_monthtoday">{t}MTD{/t}</button> <button class="table_option {if $suppliers_period=='weektoday'}selected{/if}" period="weektoday" id="suppliers_period_weektoday">{t}WTD{/t}</button> <button class="table_option {if $suppliers_period=='today'}selected{/if}" period="today" id="suppliers_period_today">{t}Today{/t}</button> <button style="margin-left:4px" class="table_option {if $suppliers_period=='yesterday'}selected{/if}" period="yesterday" id="suppliers_period_yesterday">{t}YD{/t}</button> <button class="table_option {if $suppliers_period=='last_w'}selected{/if}" period="last_w" id="suppliers_period_last_w">{t}LW{/t}</button> <button class="table_option {if $suppliers_period=='last_m'}selected{/if}" period="last_m" id="suppliers_period_last_m">{t}LM{/t}</button> <button style="margin-left:4px" class="table_option {if $suppliers_period=='three_year'}selected{/if}" period="three_year" id="suppliers_period_three_year">{t}3Y{/t}</button> <button class="table_option {if $suppliers_period=='year'}selected{/if}" period="year" id="suppliers_period_year">{t}1Yr{/t}</button> <button class="table_option {if $suppliers_period=='six_month'}selected{/if}" period="six_month" id="suppliers_period_six_month">{t}6M{/t}</button> <button class="table_option {if $suppliers_period=='quarter'}selected{/if}" period="quarter" id="suppliers_period_quarter">{t}1Qtr{/t}</button> <button class="table_option {if $suppliers_period=='month'}selected{/if}" period="month" id="suppliers_period_month">{t}1M{/t}</button> <button class="table_option {if $suppliers_period=='ten_day'}selected{/if}" period="ten_day" id="suppliers_period_ten_day">{t}10D{/t}</button> <button class="table_option {if $suppliers_period=='week'}selected{/if}" period="week" id="suppliers_period_week">{t}1W{/t}</button> 
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
		{if $category->get('Category Deep')==1} 
		<div style="float:left" id="plot_referral_1" style="border:1px solid #ccc">
			<strong> You need to upgrade your Flash Player </strong> 
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
			<strong> You need to upgrade your Flash Player </strong> 
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
	</script> {/if} 
	</div>
	<div id="block_history" style="{if $block_view!='history'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
		<span class="clean_table_title"> {t}Changelog{/t} </span> 
		<div id="table_type" class="table_type">
			<div style="font-size:90%" id="supplier_type_chooser">
				<span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $history_elements.Changes}selected{/if} label_supplier_Changes" id="elements_Changes" table_type="Changes">{t}Change{/t} (<span id="elements_Changes_number">{$history_elements_number.Changes}</span>)</span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $history_elements.Assign}selected{/if} label_supplier_Assign" id="elements_Assign" table_type="Assign">{t}Assig{/t} (<span id="elements_Assign_number">{$history_elements_number.Assign}</span>)</span> 
			</div>
		</div>
		<div class="table_top_bar space">
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
					<button id="sales_history_timeline_group_{$menu.mode}" class="timeline_group {if $sales_history_timeline_group==$menu.mode}selected{/if}" style="float:none;margin:0px auto;min-width:120px" onclick="change_timeline_group(7,'sales_history','{$menu.mode}','{$menu.label}')"> {$menu.label}</button> 
				</div>
				</td>
			</tr>
			{/foreach} 
		</tbody>
	</table>
</div>
{include file='footer.tpl'} 