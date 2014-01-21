{include file='header.tpl'} 
<div id="bd" class="no_padding">
	<div style="padding:0 20px">
		{include file='locations_navigation.tpl'} 
		<input type="hidden" id="store_key" value="{$store->id}" />
		<input type="hidden" id="category_key" value="{$category->id}" />
		<input type="hidden" id="state_type" value="{$state_type}" />
		<input type="hidden" id="modify_stock" value="{$modify_stock}" />

		<input type="hidden" id="link_extra_argument" value="&id={$category->id}" />
		<input type="hidden" id="from" value="{$from}" />
		<input type="hidden" id="to" value="{$to}" />
		<input type="hidden" id="families_table_id" value="0" />
		<input type="hidden" id="parent" value="category" />
		<input type="hidden" id="parent_key" value="{$category->id}" />
		<input type="hidden" id="calendar_id" value="sales" />
		<input type="hidden" id="subject" value="category"> 
		<input type="hidden" id="subject_key" value="{$category->id}"> 

		<div class="branch">
					<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a>&rarr; {if $user->get_number_stores()>1}<a href="stores.php">{t}Stores{/t}</a> &rarr; {/if}<a href="store.php?id={$store->id}">{$store->get('Store Code')}</a> &rarr; <a href="family_categories.php?&store_id={$store->id}"> {t}Family's Categories{/t} </a> &rarr; {$category->get('Category XHTML Branch Tree')} </span> 

		</div>
		<div class="top_page_menu">
			<div class="buttons" style="float:left">
				{if isset($navigation_prev)}<img class="previous" onmouseover="this.src='art/previous_button.gif'" onmouseout="this.src='art/previous_button.png'" title="{$navigation_prev.title}" onclick="window.location='{$navigation_prev.link}'" src="art/previous_button.png" alt="{t}Previous{/t}" />{/if} <span class="main_title"> {t}Category{/t}: <span class="id">{$category->get('Category Label')}</span> {$category->get_icon()}</span> 
			</div>
			<div class="buttons" style="float:right">
				{if isset($navigation_next)}<img class="next" onmouseover="this.src='art/next_button.gif'" onmouseout="this.src='art/next_button.png'" title="{$navigation_next.title}" onclick="window.location='{$navigation_next.link}'" src="art/next_button.png" alt="{t}Next{/t}" />{/if} <button onclick="window.location='edit_family_category.php?id={$category->id}'"> <img src="art/icons/table_edit.png" alt=""> {t}Edit Category{/t} </button> 
			</div>
			<div style="clear:both">
			</div>
		</div>
	</div>
	<ul class="tabs" id="chooser_ul" style="clear:both;margin-top:10px">
		<li> <span class="item {if $block_view=='overview'}selected{/if}" id="overview"> <span> {t}Overview{/t}</span></span> </li>
		<li style="{if !$show_subcategories}xdisplay:none{/if}"> <span class="item {if $block_view=='subcategories'}selected{/if}" id="subcategories"> <span> {t}Subcategories{/t} ({$category->get('Number Children')})</span></span> </li>
		<li style="{if !$show_subjects}xdisplay:none{/if}"> <span class="item {if $block_view=='subjects'}selected{/if}" id="subjects"> <span> {t}Families{/t} ({$category->get('Number Subjects')})</span></span> </li>
		<li style="{if !$show_subjects_data}xdisplay:none{/if};"> <span class="item {if $block_view=='sales'}selected{/if}" id="sales"> <span> {t}Sales{/t}</span></span> </li>
		<li> <span class="item {if $block_view=='history'}selected{/if}" id="history"> <span> {t}Changeslog{/t}</span></span> </li>
	</ul>
	<div style="clear:both;width:100%;border-bottom:1px solid #ccc">
	</div>
	<div id="block_sales" style="{if $block_view!='sales'}display:none;{/if}clear:both;margin:10px 0 40px 0;padding:0 20px;">
		{include file='calendar_splinter.tpl' calendar_id='sales' calendar_link='part.php'} 
		<div style="float:left;margin-top:5px;font-size:90%"><img src="art/icons/clock_16.png" style="height:12px;position:relative;bottom:2px"> {$period}</div> 
		<div style="clear:both"></div>
	
	
		<div style="margin-top:20px;width:900px;{if !$show_subjects_data}display:none{/if}">
		
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
				<li> <span class="item {if $sales_sub_block_tipo=='plot_famolies_sales'}selected{/if}" onclick="change_sales_sub_block(this)" id="plot_families_sales"> <span>{t}Sales Chart{/t}</span> </span> </li>
				<li style="display:none"> <span class="item {if $sales_sub_block_tipo=='families_sales_timeseries'}selected{/if}" onclick="change_sales_sub_block(this)" id="families_sales_timeseries" tipo="store"> <span>{t}Family's Category Sales History{/t}</span> </span> </li>
			</ul>
			<div id="sub_block_plot_products_sales" style="min-height:400px;clear:both;border:1px solid #ccc;{if $sales_sub_block_tipo!='plot_products_sales'}display:none{/if}">
<script type="text/javascript" src="external_libs/amstock/amstock/swfobject.js"></script> <script type="text/javascript">
		// <![CDATA[
		var so = new SWFObject("external_libs/amstock/amstock/amstock.swf", "amstock", "905", "500", "8", "#FFFFFF");
		so.addVariable("path", "");
		so.addVariable("settings_file", encodeURIComponent("conf/plot_asset_sales.xml.php?tipo=product_category_sales&category_key={$category->id}"));
		
		so.addVariable("preloader_color", "#999999");
		so.write("sub_block_plot_products_sales");
		// ]]>
	</script> 
				<div style="clear:both">
				</div>
			</div>
			<div id="sub_block_products_sales_timeseries" style="padding:20px;min-height:400px;clear:both;border:1px solid #ccc;{if $sales_sub_block_tipo!='products_sales_timeseries'}display:none{/if}">
				<span class="clean_table_title">{t}Family's Category Sales History{/t}</span> 
				<div>
					<span tipo='year' id="product_sales_history_type_year" style="float:right" class="table_type state_details {if $product_sales_history_type=='year'}selected{/if}">{t}Yearly{/t}</span> <span tipo='month' id="product_sales_history_type_month" style="float:right;margin-right:10px" class="table_type state_details {if $product_sales_history_type=='month'}selected{/if}">{t}Monthly{/t}</span> <span tipo='week' id="product_sales_history_type_week" style="float:right;margin-right:10px" class="table_type state_details {if $product_sales_history_type=='week'}selected{/if}">{t}Weekly{/t}</span> <span tipo='day' id="product_sales_history_type_day" style="float:right;margin-right:10px" class="table_type state_details {if $product_sales_history_type=='day'}selected{/if}">{t}Daily{/t}</span> 
				</div>
				<div class="table_top_bar" style="margin-bottom:10px">
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
		
				<span class="clean_table_title">{t}Families{/t} <img id="export_csv1" tipo="families_in_department" style="position:relative;top:0px;left:5px;cursor:pointer;vertical-align:text-bottom;" label="{t}Export (CSV){/t}" alt="{t}Export (CSV){/t}" src="art/icons/export_csv.gif"></span> 
				<div class="elements_chooser">
					<span style="float:right;margin-left:20px;" class=" table_type transaction_type state_details {if $elements_family.NoSale}selected{/if} label_family_products_nosale" id="elements_family_nosale" table_type="nosale">{t}No Sale{/t} (<span id="elements_family_NoSale_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> 
					<span style="float:right;margin-left:20px;" class=" table_type transaction_type state_details {if $elements_family.Discontinued}selected{/if} label_family_products_discontinued" id="elements_family_discontinued" table_type="discontinued">{t}Discontinued{/t} (<span id="elements_family_Discontinued_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> 
					<span style="float:right;margin-left:20px;" class=" table_type transaction_type state_details {if $elements_family.Discontinuing}selected{/if} label_family_products_discontinued" id="elements_family_discontinuing" table_type="discontinuing">{t}Discontinuing{/t} (<span id="elements_family_Discontinuing_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> 
					<span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_family.Normal}selected{/if} label_family_products_normal" id="elements_family_normal" table_type="normal">{t}For Sale{/t} (<span id="elements_family_Normal_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> 
					<span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_family.InProcess}selected{/if} label_family_products_inprocess" id="elements_family_inprocess" table_type="inprocess">{t}In Process{/t} (<span id="elements_family_InProcess_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> 
				</div>
				<div class="table_top_bar">
				</div>
				<input type="hidden" id="families_view" value="{$family_view}">
				<div class="clusters">
				<div id="table_view_menu1" style="{if $families_table_type=='thumbnails'}display:none{/if}">
					<div class="buttons small left cluster">
						<button class="table_option {if $family_view=='general'}selected{/if}" id="family_general">{t}Overview{/t}</button> <button class="table_option {if $family_view=='stock'}selected{/if}" id="family_stock" {if !$view_stock}style="display:none" {/if}>{t}Stock{/t}</button> <button class="table_option {if $family_view=='sales'}selected{/if}" id="family_sales" {if !$view_sales}style="display:none" {/if}>{t}Sales{/t}</button> 
					</div>
					<div id="family_period_options" class="buttons small left cluster" style="display:{if $family_view!='sales' }none{else}block{/if};">
						<button class="table_option {if $family_period=='all'}selected{/if}" period="all" id="family_period_all">{t}All{/t}</button> <button class="table_option {if $family_period=='three_year'}selected{/if}" period="three_year" id="family_period_three_year">{t}3Y{/t}</button> <button class="table_option {if $family_period=='year'}selected{/if}" period="year" id="family_period_year">{t}1Yr{/t}</button> <button class="table_option {if $family_period=='yeartoday'}selected{/if}" period="yeartoday" id="family_period_yeartoday">{t}YTD{/t}</button> <button class="table_option {if $family_period=='six_month'}selected{/if}" period="six_month" id="family_period_six_month">{t}6M{/t}</button> <button class="table_option {if $family_period=='quarter'}selected{/if}" period="quarter" id="family_period_quarter">{t}1Qtr{/t}</button> <button class="table_option {if $family_period=='month'}selected{/if}" period="month" id="family_period_month">{t}1M{/t}</button> <button class="table_option {if $family_period=='ten_day'}selected{/if}" period="ten_day" id="family_period_ten_day">{t}10D{/t}</button> <button class="table_option {if $family_period=='week'}selected{/if}" period="week" id="family_period_week">{t}1W{/t}</button> 
					</div>
					<div id="family_avg_options" class="buttons small left cluster" style="display:{if $family_view!='sales' }none{else}block{/if};">
						<button class="table_option {if $family_avg=='totals'}selected{/if}" avg="totals" id="family_avg_totals">{t}Totals{/t}</button> <button class="table_option {if $family_avg=='month'}selected{/if}" avg="month" id="family_avg_month">{t}M AVG{/t}</button> <button class="table_option {if $family_avg=='week'}selected{/if}" avg="week" id="family_avg_week">{t}W AVG{/t}</button> 
					</div>
					</div>
					<div class="buttons small cluster group">
						<button id="change_families_display_mode" style="{if $families_table_type=='thumbnails' or $family_view!='sales'}display:none{/if}">&#x21b6 {$display_families_mode_label}</button> 
						<button id="change_families_table_type">&#x21b6 {if $families_table_type=='list'}{t}List{/t}{else}{t}Thumbnails{/t}{/if}</button> 
					</div>
					<div style="clear:both">
					</div>
				</div>
				{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0} 
				<div id="thumbnails0" class="thumbnails" style="border-top:1px solid SteelBlue;clear:both;{if $families_table_type!='thumbnails'}display:none{/if}">
				</div>
				<div id="table0" class="data_table_container dtable btable with_total" style="{if $families_table_type=='thumbnails'}display:none;{/if}font-size:85%">
				</div>
			
		</div>
	</div>
	<div id="block_overview" style="{if $block_view!='overview'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
	
	{$category->get('Product Family Category XHTML Description')}
	</div>
	<div id="block_history" style="{if $block_view!='history'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
		<span class="clean_table_title"> {t}Changeslog{/t} </span> 
		<div class="elements_chooser">
				<span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $history_elements.Changes}selected{/if} label_product_Changes" id="elements_Changes" table_type="Changes">{t}Changes{/t} (<span id="elements_Changes_number">{$history_elements_number.Changes}</span>)</span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $history_elements.Assign}selected{/if} label_product_Assign" id="elements_Assign" table_type="Assign">{t}Assig{/t} (<span id="elements_Assign_number">{$history_elements_number.Assign}</span>)</span> 
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