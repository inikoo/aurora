{include file='header.tpl'} 
<div id="bd" class="no_padding">
	<div style="padding:0 20px">
		{include file='suppliers_navigation.tpl'} 
		<input type="hidden" id="category_key" value="{$category->id}" />
				<input type="hidden" id="state_type" value="{$state_type}" />

		<div class="branch">
			<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a>&rarr; <a href="suppliers.php">{t}Suppliers{/t}</a> &rarr; <a href="supplier_categories.php">{t}Suppliers Categories{/t}</a>  &rarr; {$category->get('Category XHTML Branch Tree')}</span> 

		</div>
		<div class="top_page_menu">
			<div class="buttons" style="float:left">
											{if isset($prev)}<img class="previous" onmouseover="this.src='art/previous_button.gif'" onmouseout="this.src='art/previous_button.png'" title="{$prev.title}" onclick="window.location='{$prev.link}'" src="art/previous_button.png" alt="{t}Previous{/t}" />{/if} 

				<span class="main_title"> {t}Category{/t}: <span class="id">{$category->get('Category Label')}</span> {$category->get_icon()}</span> 
			</div>
			<div class="buttons" style="float:right">
										{if isset($next)}<img class="next" onmouseover="this.src='art/next_button.gif'" onmouseout="this.src='art/next_button.png'" title="{$next.title}" onclick="window.location='{$next.link}'" src="art/next_button.png" alt="{t}Next{/t}" />{/if} 

				<button onclick="window.location='edit_supplier_category.php?id={$category->id}'"> <img src="art/icons/table_edit.png" alt=""> {t}Edit Category{/t} </button> 
			</div>
			<div style="clear:both">
			</div>
		</div>
	</div>
	<ul class="tabs" id="chooser_ul" style="clear:both;margin-top:10px">
		<li> <span class="item {if $block_view=='overview'}selected{/if}" id="overview"> <span> {t}Overview{/t}</span></span> </li>
		<li style="{if !$show_subcategories}display:none{/if}"> <span class="item {if $block_view=='subcategories'}selected{/if}" id="subcategories"> <span> {t}Subcategories{/t} ({$category->get('Number Children')})</span></span> </li>
		<li style="{if !$show_subjects}display:none{/if}"> <span class="item {if $block_view=='subjects'}selected{/if}" id="subjects"> <span> {t}Suppliers{/t} ({$category->get('Number Subjects')})</span></span> </li>
		<li style="{if !$show_subjects_data}display:none{/if};display:none"> <span class="item {if $block_view=='sales'}selected{/if}" id="sales"> <span> {t}Sales{/t}</span></span> </li>
		<li> <span  class="item {if $block_view=='history'}selected{/if}" id="history"> <span> {t}Changelog{/t}</span></span> </li>
	</ul>
	<div style="clear:both;width:100%;border-bottom:1px solid #ccc">
	</div>
	<div id="block_sales" style="{if $block_view!='sales'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px;display:none;"></div>
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
			<span class="clean_table_title"> {t}Suppliers in this category{/t} <img class="export_data_link" id="export_csv2" label="{t}Export (CSV){/t}" alt="{t}Export (CSV){/t}" src="art/icons/export_csv.gif"> </span> 
			{*}
			<div id="table_type" class="table_type">
				<div style="font-size:90%" id="transaction_chooser">
					<span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements.NotKeeping}selected{/if} label_supplier_NotKeeping" id="elements_NotKeeping" table_type="NotKeeping"> {t}NotKeeping{/t} (<span id="elements_orders_number">{$elements_number.NotKeeping}</span>) </span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements.Discontinued}selected{/if} label_supplier_Discontinued" id="elements_Discontinued" table_type="Discontinued"> {t}Discontinued{/t} (<span id="elements_orders_number">{$elements_number.Discontinued}</span>) </span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements.LastStock}selected{/if} label_supplier_LastStock" id="elements_LastStock" table_type="LastStock"> {t}LastStock{/t} (<span id="elements_orders_number">{$elements_number.LastStock}</span>) </span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements.Keeping}selected{/if} label_supplier_Keeping" id="elements_Keeping" table_type="Keeping"> {t}Keeping{/t} (<span id="elements_orders_number">{$elements_number.Keeping}</span>) </span> 
				</div>
			</div>
			{*}
			<div class="table_top_bar">
			</div>
				<div class="clusters">
					<div class="buttons small left cluster">
						<button class="{if $suppliers_view=='general'}selected{/if}" id="suppliers_general">{t}General{/t}</button>
						<button class="{if $suppliers_view=='contact'}selected{/if}" id="suppliers_contact">{t}Contact{/t}</button>
						<button class="{if $suppliers_view=='products'}selected{/if}" id="suppliers_products">{t}Products{/t}</button>
						<button style="{if !$view_stock}display:none{/if}" class="{if $suppliers_view=='stock'}selected{/if}" id="suppliers_stock">{t}Parts Stock{/t}</button>
						<button style="{if !$view_sales}display:none{/if}" class="{if $suppliers_view=='sales'}selected{/if}" id="suppliers_sales">{t}Parts Sales{/t}</button>
						<button style="{if !$view_sales}display:none{/if}" class="{if $suppliers_view=='profit'}selected{/if}" id="suppliers_profit">{t}Profit{/t}</button>
						
					</div>
					
					<div class="buttons small left cluster"  id="suppliers_period_options" style="{if $suppliers_view!='sales' and  $suppliers_view!='profit'};display:none{/if}">
					
					
					
					<button class="table_option {if $suppliers_period=='all'}selected{/if}" period="all" id="suppliers_period_all">{t}All{/t}</button>
					<button style="margin-left:4px" class="table_option {if $suppliers_period=='yeartoday'}selected{/if}" period="yeartoday" id="suppliers_period_yeartoday">{t}YTD{/t}</button> 
					<button class="table_option {if $suppliers_period=='monthtoday'}selected{/if}" period="monthtoday" id="suppliers_period_monthtoday">{t}MTD{/t}</button> 
					<button class="table_option {if $suppliers_period=='weektoday'}selected{/if}" period="weektoday" id="suppliers_period_weektoday">{t}WTD{/t}</button> 
					<button class="table_option {if $suppliers_period=='today'}selected{/if}" period="today" id="suppliers_period_today">{t}Today{/t}</button> 
					<button style="margin-left:4px" class="table_option {if $suppliers_period=='yesterday'}selected{/if}" period="yesterday" id="suppliers_period_yesterday">{t}YD{/t}</button> 
					<button class="table_option {if $suppliers_period=='last_w'}selected{/if}" period="last_w" id="suppliers_period_last_w">{t}LW{/t}</button> 
					<button class="table_option {if $suppliers_period=='last_m'}selected{/if}" period="last_m" id="suppliers_period_last_m">{t}LM{/t}</button> 
					<button style="margin-left:4px" class="table_option {if $suppliers_period=='three_year'}selected{/if}" period="three_year" id="suppliers_period_three_year">{t}3Y{/t}</button> 
					<button class="table_option {if $suppliers_period=='year'}selected{/if}" period="year" id="suppliers_period_year">{t}1Yr{/t}</button> 
					<button class="table_option {if $suppliers_period=='six_month'}selected{/if}" period="six_month" id="suppliers_period_six_month">{t}6M{/t}</button> 
					<button class="table_option {if $suppliers_period=='quarter'}selected{/if}" period="quarter" id="suppliers_period_quarter">{t}1Qtr{/t}</button> 
					<button class="table_option {if $suppliers_period=='month'}selected{/if}" period="month" id="suppliers_period_month">{t}1M{/t}</button> 
					<button class="table_option {if $suppliers_period=='ten_day'}selected{/if}" period="ten_day" id="suppliers_period_ten_day">{t}10D{/t}</button> 
					<button class="table_option {if $suppliers_period=='week'}selected{/if}" period="week" id="suppliers_period_week">{t}1W{/t}</button> 
		
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
	
	<div id="sales_info"  style="{if !$show_subjects_data}display:none{/if}" >
	
	
		<div style="margin-top:20px;width:900px">
			<div class="clusters">
				<div class="buttons small left cluster">
					<button class="{if $category_period=='all'}selected{/if}" period="all" id="category_period_all" style="padding-left:7px;padding-right:7px"> {t}All{/t} </button> 
				</div>
				<div class="buttons small left cluster">
					<button class="{if $category_period=='yeartoday'}selected{/if}" period="yeartoday" id="category_period_yeartoday"> {t}YTD{/t} </button> <button class="{if $category_period=='monthtoday'}selected{/if}" period="monthtoday" id="category_period_monthtoday"> {t}MTD{/t} </button> <button class="{if $category_period=='weektoday'}selected{/if}" period="weektoday" id="category_period_weektoday"> {t}WTD{/t} </button> <button class="{if $category_period=='today'}selected{/if}" period="today" id="category_period_today"> {t}Today{/t} </button> 
				</div>
				<div class="buttons small left cluster">
					<button class="{if $category_period=='yesterday'}selected{/if}" period="yesterday" id="category_period_yesterday"> {t}Yesterday{/t} </button> <button class="{if $category_period=='last_w'}selected{/if}" period="last_w" id="category_period_last_w"> {t}Last Week{/t} </button> <button class="{if $category_period=='last_m'}selected{/if}" period="last_m" id="category_period_last_m"> {t}Last Month{/t} </button> 
				</div>
				<div class="buttons small left cluster">
					<button class="{if $category_period=='three_year'}selected{/if}" period="three_year" id="category_period_three_year"> {t}3Y{/t} </button> <button class="{if $category_period=='year'}selected{/if}" period="year" id="category_period_year"> {t}1Yr{/t} </button> <button class="{if $category_period=='six_month'}selected{/if}" period="six_month" id="category_period_six_month"> {t}6M{/t} </button> <button class="{if $category_period=='quarter'}selected{/if}" period="quarter" id="category_period_quarter"> {t}1Qtr{/t} </button> <button class="{if $category_period=='month'}selected{/if}" period="month" id="category_period_month"> {t}1M{/t} </button> <button class="{if $category_period=='ten_day'}selected{/if}" period="ten_day" id="category_period_ten_day"> {t}10D{/t} </button> <button class="{if $category_period=='week'}selected{/if}" period="week" id="category_period_week"> {t}1W{/t} </button> 
				</div>
				<div class="buttons small left cluster">
					<button class="{if $category_period=='custom'}selected{/if}" period="custom" id="category_period_custom"> {t}Custom Dates{/t} </button> 
				</div>
				<div style="clear:both">
				</div>
			</div>
			<div style="margin-top:20px">
				<div style="width:200px;float:left;margin-left:0px;">
					<table style="clear:both" class="show_info_product">
						{foreach from=$period_tags item=period } 
						<tbody id="info_{$period.key}" style="{if $category_period!=$period.key}display:none{/if}">
							<tr>
								<td> {t}Sales{/t}: </td>
								<td class="aright"> {$category->get_period($period.db,"Acc Sold Amount")} </td>
							</tr>
							<tr style="display:none">
								<td> {t}Profit{/t}: </td>
								<td class="aright"> {$category->get_period($period.db,'Acc Profit')} </td>
							</tr>
							<tr style="display:none">
								<td> {t}Margin{/t}: </td>
								<td class="aright"> {$category->get_period($period.db,'Acc Margin')} </td>
							</tr>
							<tr style="display:none">
								<td> {t}GMROI{/t}: </td>
								<td class="aright"> {$category->get_period($period.db,'Acc GMROI')} </td>
							</tr>
						</tbody>
						{/foreach} 
					</table>
				</div>
				<div style="float:left;margin-left:20px">
					<table style="width:200px;clear:both" class="show_info_product">
						{foreach from=$period_tags item=period } 
						<tbody id="info2_{$period.key}" style="{if $category_period!=$period.key}display:none{/if}">
							{if $category->get_period($period.db,'Acc No Supplied')!=0} 
							<tr>
								<td> {t}Required{/t}: </td>
								<td class="aright"> {$category->get_period($period.db,'Acc Required')} </td>
							</tr>
							<tr style="display:none">
								<td> {t}No Supplied{/t}: </td>
								<td class="aright error"> {$category->get_period($period.db,'Acc No Supplied')} </td>
							</tr>
							{/if} 
							<tr>
								<td> {t}Sold{/t}: </td>
								<td class="aright"> {$category->get_period($period.db,'Acc Sold')} </td>
							</tr>
							{if $category->get_period($period.db,'Acc Given')!=0} 
							<tr>
								<td> {t}Given for free{/t}: </td>
								<td class="aright"> {$category->get_period($period.db,'Acc Given')} </td>
							</tr>
							{/if} {if $category->get_period($period.db,'Acc Given')!=0} 
							<tr>
								<td> {t}Broken{/t}: </td>
								<td class="aright"> {$category->get('Total Acc Broken')} </td>
							</tr>
							{/if} {if $category->get_period($period.db,'Acc Given')!=0} 
							<tr>
								<td> {t}Lost{/t}: </td>
								<td class="aright"> { $category->get_period($period.db,'Acc Lost')} </td>
							</tr>
							{/if} 
						</tbody>
						{/foreach} 
					</table>
				</div>
			</div>
			<div id="sales_plots" style="clear:both;{if $category->get_period('Total','Acc Sold Amount')==0}display:none{/if}">
				<ul class="tabs" id="chooser_ul" style="margin-top:25px">
					<li> <span class="item {if $plot_tipo=='store'}selected{/if}" onclick="change_plot(this)" id="plot_store" tipo="store"> <span> {t}Suppliers Sales{/t} </span> </span> </li>
					{* 
					<li> <span class="item {if $plot_tipo=='top_desupplierments'}selected{/if}" id="plot_top_desupplierments" onclick="change_plot(this)" tipo="top_desupplierments"> <span> {t}Top Products{/t} </span> </span> </li>
					<li> <span class="item {if $plot_tipo=='pie'}selected{/if}" onclick="change_plot(this)" id="plot_pie" tipo="pie" forecast="{$plot_data.pie.forecast}" interval="{$plot_data.pie.interval}"> <span> {t}Products{/t} </span> </span> </li>
					*} 
				</ul>
<script type="text/javascript" src="external_libs/amstock/amstock/swfobject.js"></script> 
				<div id="sales_plot" style="clear:both;border:1px solid #ccc">
					<div id="single_data_set">
						<strong> You need to upgrade your Flash Player </strong> 
					</div>
				</div>
<script type="text/javascript">
		// <![CDATA[
		var so = new SWFObject("external_libs/amstock/amstock/amstock.swf", "amstock", "905", "500", "8", "#FFFFFF");
		so.addVariable("path", "");
		so.addVariable("settings_file", encodeURIComponent("conf/plot_asset_sales.xml.php?tipo=supplier_category_sales&category_key={$category->id}"));
		so.addVariable("preloader_color", "#999999");
		so.write("sales_plot");
		// ]]>
	</script> 
				<div style="clear:both">
				</div>
			</div>
		</div>
	
	
	</div>
	
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
				<span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $history_elements.Change}selected{/if} label_supplier_Change" id="elements_Change" table_type="Change">{t}Change{/t} (<span id="elements_Change_number">{$history_elements_number.Change}</span>)</span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $history_elements.Assign}selected{/if} label_supplier_Assign" id="elements_Assign" table_type="Assign">{t}Assig{/t} (<span id="elements_Assign_number">{$history_elements_number.Assign}</span>)</span> 
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


{include file='footer.tpl'} 