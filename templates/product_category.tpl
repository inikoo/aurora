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
		<input type="hidden" id="products_table_id" value="0" />
		<input type="hidden" id="parent" value="category" />
		<input type="hidden" id="parent_key" value="{$category->id}" />
		<input type="hidden" id="calendar_id" value="sales" />
		<input type="hidden" id="subject" value="category"> 
		<input type="hidden" id="subject_key" value="{$category->id}"> 

		<div class="branch">
					<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a>&rarr; {if $user->get_number_stores()>1}<a href="stores.php">{t}Stores{/t}</a> &rarr; {/if}<a href="store.php?id={$store->id}">{$store->get('Store Code')}</a> &rarr; <a href="product_categories.php?&store_id={$store->id}"> {t}Products Categories{/t} </a> &rarr; {$category->get('Category XHTML Branch Tree')} </span> 

		</div>
		<div class="top_page_menu">
			<div class="buttons" style="float:left">
				{if isset($navigation_prev)}<img class="previous" onmouseover="this.src='art/previous_button.gif'" onmouseout="this.src='art/previous_button.png'" title="{$navigation_prev.title}" onclick="window.location='{$navigation_prev.link}'" src="art/previous_button.png" alt="{t}Previous{/t}" />{/if} <span class="main_title"> {t}Category{/t}: <span class="id">{$category->get('Category Label')}</span> {$category->get_icon()}</span> 
			</div>
			<div class="buttons" style="float:right">
				{if isset($navigation_next)}<img class="next" onmouseover="this.src='art/next_button.gif'" onmouseout="this.src='art/next_button.png'" title="{$navigation_next.title}" onclick="window.location='{$navigation_next.link}'" src="art/next_button.png" alt="{t}Next{/t}" />{/if} <button onclick="window.location='edit_product_category.php?id={$category->id}'"> <img src="art/icons/table_edit.png" alt=""> {t}Edit Category{/t} </button> 
			</div>
			<div style="clear:both">
			</div>
		</div>
	</div>
	<ul class="tabs" id="chooser_ul" style="clear:both;margin-top:10px">
		<li> <span class="item {if $block_view=='overview'}selected{/if}" id="overview"> <span> {t}Overview{/t}</span></span> </li>
		<li style="{if !$show_subcategories}xdisplay:none{/if}"> <span class="item {if $block_view=='subcategories'}selected{/if}" id="subcategories"> <span> {t}Subcategories{/t} ({$category->get('Number Children')})</span></span> </li>
		<li style="{if !$show_subjects}xdisplay:none{/if}"> <span class="item {if $block_view=='subjects'}selected{/if}" id="subjects"> <span> {t}Products{/t} ({$category->get('Number Subjects')})</span></span> </li>
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
				<li> <span class="item {if $sales_sub_block_tipo=='plot_products_sales'}selected{/if}" onclick="change_sales_sub_block(this)" id="plot_products_sales"> <span>{t}Sales Chart{/t}</span> </span> </li>
				<li style="display:none"> <span class="item {if $sales_sub_block_tipo=='products_sales_timeseries'}selected{/if}" onclick="change_sales_sub_block(this)" id="product_sales_timeseries" tipo="store"> <span>{t}Product Sales History{/t}</span> </span> </li>
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
				<span class="clean_table_title">{t}Product Sales History{/t}</span> 
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

		<span class="clean_table_title">{t}Products in category{/t} <img id="export_csv0" class="export_data_link" label="{t}Export (CSV/XML){/t}" alt="{t}Export (CSV/XML){/t}" src="art/icons/export_csv.gif"> </span> 
			<div class="elements_chooser">
				<img class="menu" id="product_element_chooser_menu_button" title="{t}Group by menu{/t}" src="art/icons/list.png" /> 
				<div id="product_type_chooser" style="{if $elements_product_elements_type!='type'}display:none{/if}">
					<span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_type.Historic}selected{/if} label_product_Historic" id="elements_type_Historic" table_type="Historic">{t}Historic{/t} (<span id="elements_type_Historic_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_type.Discontinued}selected{/if} label_product_Discontinued" id="elements_type_Discontinued" table_type="Discontinued">{t}Discontinued{/t} (<span id="elements_type_Discontinued_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_type.Private}selected{/if} label_product_Private" id="elements_type_Private" table_type="Private">{t}Private{/t} (<span id="elements_type_Private_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_type.NoSale}selected{/if} label_product_NoSale" id="elements_type_NoSale" table_type="NoSale">{t}No Sale{/t} (<span id="elements_type_NoSale_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_type.Sale}selected{/if} label_product_Sale" id="elements_type_Sale" table_type="Sale">{t}Sale{/t} (<span id="elements_type_Sale_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> 
				</div>
				<div id="product_web_chooser" style="{if $elements_product_elements_type!='web'}display:none{/if}">
					<span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_web.ForSale}selected{/if} label_product_ForSale" id="elements_web_ForSale" table_type="ForSale">{t}Online{/t} (<span id="elements_web_ForSale_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_web.OutofStock}selected{/if} label_product_OutofStock" id="elements_web_OutofStock" table_type="OutofStock">{t}Out of Stock{/t} (<span id="elements_web_OutofStock_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_web.Discontinued}selected{/if} label_product_Discontinued" id="elements_web_Discontinued" table_type="Discontinued">{t}Discontinued{/t} (<span id="elements_web_Discontinued_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_web.Offline}selected{/if} label_product_Offline" id="elements_web_Offline" table_type="Offline">{t}Offline{/t} (<span id="elements_web_Offline_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> 
				</div>
				<div id="product_stock_chooser" style="{if $elements_product_elements_type!='stock'}display:none{/if}">
					<span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_stock.Error}selected{/if} label_product_Error" id="elements_stock_Error" table_type="Error">{t}Error{/t} (<span id="elements_stock_Error_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_stock.OutofStock}selected{/if} label_product_OutofStock" id="elements_stock_OutofStock" table_type="OutofStock">{t}Out of Stock{/t} (<span id="elements_stock_OutofStock_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_stock.VeryLow}selected{/if} label_product_VeryLow" id="elements_stock_VeryLow" table_type="VeryLow">{t}Very Low{/t} (<span id="elements_stock_VeryLow_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_stock.Low}selected{/if} label_product_Low" id="elements_stock_Low" table_type="Low">{t}Low{/t} (<span id="elements_stock_Low_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_stock.Normal}selected{/if} label_product_Normal" id="elements_stock_Normal" table_type="Normal">{t}Normal{/t} (<span id="elements_stock_Normal_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_stock.Excess}selected{/if} label_product_Excess" id="elements_stock_Excess" table_type="Excess">{t}Excess{/t} (<span id="elements_stock_Excess_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:2px" class=" table_type transaction_type state_details">]</span> <span style="float:right;margin-left:2px" class=" table_type transaction_type state_details {if $elements_stock_aux=='InWeb'}selected{/if}" id="elements_stock_aux_InWeb" table_type="InWeb" title="{t}InWeb Products{/t}">{t}In Web{/t}</span> <span style="float:right;margin-left:2px" class=" table_type transaction_type state_details">|</span> <span style="float:right;margin-left:2px" class=" table_type transaction_type state_details {if $elements_stock_aux=='ForSale'}selected{/if}" id="elements_stock_aux_ForSale" table_type="ForSale" title="{t}ForSale Products{/t}">{t}For Sale{/t}</span> <span style="float:right;margin-left:2px" class=" table_type transaction_type state_details">|</span> <span style="float:right;margin-left:2px" class=" table_type transaction_type state_details {if $elements_stock_aux=='All'}selected{/if}" id="elements_stock_aux_All" table_type="All" title="{t}All Products{/t}">{t}All{/t}</span> <span style="float:right;margin-left:0px" class=" table_type transaction_type state_details">[</span> 
				</div>
			</div>
			<div class="table_top_bar">
			</div>
			<input type="hidden" id="products_view" value="{$product_view}">
			<div class="clusters">
				<div id="table_view_menu0" style="{if $products_table_type=='thumbnails'}display:none{/if}">
					<div class="buttons small left cluster">
						<button class="table_option {if $product_view=='general'}selected{/if}" id="product_general">{t}Overview{/t}</button> <button class="table_option {if $product_view=='stock'}selected{/if}" id="product_stock" {if !$view_stock}style="display:none" {/if}>{t}Stock{/t}</button> <button class="table_option {if $product_view=='sales'}selected{/if}" id="product_sales" {if !$view_sales}style="display:none" {/if}>{t}Sales{/t}</button> <button class="table_option {if $product_view=='parts'}selected{/if}" id="product_parts" {if !$view_sales}style="display:none" {/if}>{t}Parts{/t}</button> <button class="table_option {if $product_view=='cats'}selected{/if}" id="product_cats" {if !$view_sales}style="display:none" {/if}>{t}Groups{/t}</button> 
					</div>
					<div id="product_period_options" class="buttons small left cluster" style="display:{if $product_view!='sales' }none{else}block{/if};">
						<button class="table_option {if $product_period=='all'}selected{/if}" period="all" id="product_period_all">{t}All{/t}</button> <button class="table_option {if $product_period=='three_year'}selected{/if}" period="three_year" id="product_period_three_year">{t}3Y{/t}</button> <button class="table_option {if $product_period=='year'}selected{/if}" period="year" id="product_period_year">{t}1Yr{/t}</button> <button class="table_option {if $product_period=='yeartoday'}selected{/if}" period="yeartoday" id="product_period_yeartoday">{t}YTD{/t}</button> <button class="table_option {if $product_period=='six_month'}selected{/if}" period="six_month" id="product_period_six_month">{t}6M{/t}</button> <button class="table_option {if $product_period=='quarter'}selected{/if}" period="quarter" id="product_period_quarter">{t}1Qtr{/t}</button> <button class="table_option {if $product_period=='month'}selected{/if}" period="month" id="product_period_month">{t}1M{/t}</button> <button class="table_option {if $product_period=='ten_day'}selected{/if}" period="ten_day" id="product_period_ten_day">{t}10D{/t}</button> <button class="table_option {if $product_period=='week'}selected{/if}" period="week" id="product_period_week">{t}1W{/t}</button> 
					</div>
					<div id="product_avg_options" class="buttons small left cluster" style="display:{if $product_view!='sales' }none{else}block{/if};">
						<button class="table_option {if $product_avg=='totals'}selected{/if}" avg="totals" id="product_avg_totals">{t}Totals{/t}</button> <button class="table_option {if $product_avg=='month'}selected{/if}" avg="month" id="product_avg_month">{t}M AVG{/t}</button> <button class="table_option {if $product_avg=='week'}selected{/if}" avg="week" id="product_avg_week">{t}W AVG{/t}</button> <button class="table_option {if $product_avg=='month_eff'}selected{/if}" style="display:none" avg="month_eff" id="product_avg_month_eff">{t}M EAVG{/t}</button> <button class="table_option {if $product_avg=='week_eff'}selected{/if}" style="display:none" avg="week_eff" id="product_avg_week_eff">{t}W EAVG{/t}</button> 
					</div>
				</div>
				<div class="buttons small cluster group">
					<button id="change_products_display_mode" style="{if $products_table_type=='thumbnails' or  $product_view!='sales'}display:none{/if}">&#x21b6 {$display_products_mode_label}</button> 
					<button id="change_products_table_type">&#x21b6 {if $products_table_type=='list'}{t}List{/t}{else}{t}Thumbnails{/t}{/if}</button> 
				</div>
				<div style="clear:both">
				</div>
			</div>
			{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0 } 
			<div id="thumbnails0" class="thumbnails" style="border-top:1px solid SteelBlue;clear:both;{if $products_table_type!='thumbnails'}display:none{/if}">
			</div>
			<div id="table0" class="data_table_container dtable btable with_total " style="{if $products_table_type=='thumbnails'}display:none{/if};font-size:90%">
			</div>


		</div>
	</div>
	<div id="block_overview" style="{if $block_view!='overview'}display:none;{/if}clear:both;margin:20px 0 40px 0;padding:0 20px">
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
<div id="dialog_change_products_element_chooser" style="padding:10px 20px 0px 10px">
	<table class="edit" border="0" style="width:200px">
		<tr class="title">
			<td>{t}Group products by{/t}:</td>
		</tr>
		<tr style="height:5px">
			<td></td>
		</tr>
		<tr>
			<td> 
			<div class="buttons small">
				<button id="products_element_chooser_use" style="float:none;margin:0px auto;min-width:120px" onclick="change_products_element_chooser('use')" class="{if $elements_product_elements_type=='use'}selected{/if}"> State</button> 
			</div>
			</td>
		</tr>
		<tr>
			<td> 
			<div class="buttons small">
				<button id="products_element_chooser_state" style="float:none;margin:0px auto;min-width:120px" onclick="change_products_element_chooser('state')" class="{if $elements_product_elements_type=='state'}selected{/if}"> State/Availability</button> 
			</div>
			</td>
		</tr>
		<tr>
			<td> 
			<div class="buttons small">
				<button id="products_element_chooser_stock_state" style="float:none;margin:0px auto;min-width:120px" onclick="change_products_element_chooser('stock_state')" class="{if $elements_product_elements_type=='stock_state'}selected{/if}"> Stock Level</button> 
			</div>
			</td>
		</tr>
	</table>
</div>
{include file='export_splinter.tpl' id='products' export_fields=$export_products_fields map=$export_products_map is_map_default={$export_products_map_is_default}}

 {include file='stock_splinter.tpl'}
{include file='footer.tpl'} 