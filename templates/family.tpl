{include file='header.tpl'} 
<div id="bd" style="padding:0px">
	<div style="padding:0 20px">
		<input type="hidden" id="family_key" value="{$family->id}" />
		<input type="hidden" id="link_extra_argument" value="&id={$family->id}" />
		<input type="hidden" id="from" value="{$from}" />
		<input type="hidden" id="to" value="{$to}" />
		{include file='assets_navigation.tpl'} 
		<div class="branch">
			<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a>&rarr; {if $user->get_number_stores()>1}<a href="stores.php">{t}Stores{/t}</a> &rarr; {/if}<a href="store.php?id={$store->id}">{$store->get('Store Name')}</a> &rarr; <a href="department.php?id={$department->id}">{$department->get('Product Department Name')}</a> &rarr; {$family->get('Product Family Code')}</span> 
		</div>
		<div class="top_page_menu">
			<div class="buttons" style="float:right">
				{if isset($family_next)}<img class="next" onmouseover="this.src='art/{if $family_next.to_end}prev_to_end.png{else}next_button.gif{/if}'" onmouseout="this.src='art/{if $family_next.to_end}prev_to_end.png{else}next_button.png{/if}'" title="{$family_next.title}" onclick="window.location='{$family_next.link}'" src="art/{if $family_next.to_end}prev_to_end.png{else}next_button.png{/if}" alt="{t}Next{/t}" />{/if} {if $modify}<button onclick="edit_family()"><img src="art/icons/vcard_edit.png" alt=""> {t}Edit Family{/t}</button>{/if} 
			</div>
			<div class="buttons" style="float:left">
				{if isset($family_prev)}<img class="previous" onmouseover="this.src='art/{if $family_prev.to_end}prev_to_end.png{else}previous_button.gif{/if}'" onmouseout="this.src='art/{if $family_prev.to_end}start_bookmark.png{else}previous_button.png{/if}'" title="{$family_prev.title}" onclick="window.location='{$family_prev.link}'" src="art/{if $family_prev.to_end}start_bookmark.png{else}previous_button.png{/if}" alt="{t}Previous{/t}" />{/if} <span class="main_title">{t}Family{/t}: {$family->get('Product Family Name')} <span class="id">({$family->get('Product Family Code')})</span></span> 
			</div>
			<div style="clear:both">
			</div>
		</div>
	</div>
	<ul class="tabs" id="chooser_ul" style="clear:both;margin-top:10px">
		<li> <span class="item {if $block_view=='details'}selected{/if}" id="details"> <span> {t}Details{/t}</span></span></li>
		<li> <span class="item {if $block_view=='sales'}selected{/if}" id="sales"> <span> {t}Sales{/t}</span></span></li>
		<li style="display:none"> <span class="item {if $block_view=='categories'}selected{/if}" id="categories"> <span> {t}Categories{/t}</span></span></li>
		<li> <span class="item {if $block_view=='products'}selected{/if}" id="products"><span> {t}Products{/t}</span></span></li>
		<li> <span class="item {if $block_view=='deals'}selected{/if}" id="deals"> <span> {t}Offers{/t}</span></span></li>
		<li> <span class="item {if $block_view=='web'}selected{/if}" id="web"> <span> {t}Web Pages{/t}</span></span></li>
	</ul>
	<div style="clear:both;width:100%;border-bottom:1px solid #ccc">
	</div>
	<div style="padding:0px 20px 10px 20px">
		<div id="block_sales" style="{if $block_view!='sales'}display:none;{/if}clear:both;padding-top:0;margin:0px 0 40px 0;">
			{include file='calendar_splinter.tpl'} 
			<div style="width:900px;float:left;margin-left:20px;">
				<span><img src="art/icons/clock_16.png" style="height:12px;position:relative;bottom:2px"> {$period}</span> {*} All cluster dates 
				<div class="clusters">
					<div class="buttons small left cluster">
						<button class="{if $products_period=='all'}selected{/if}" period="all" id="products_period_all" style="padding-left:7px;padding-right:7px">{t}All{/t}</button> 
					</div>
					<div class="buttons small left cluster">
						<button class="{if $products_period=='yeartoday'}selected{/if}" period="yeartoday" id="products_period_yeartoday">{t}YTD{/t}</button> <button class="{if $products_period=='monthtoday'}selected{/if}" period="monthtoday" id="products_period_monthtoday">{t}MTD{/t}</button> <button class="{if $products_period=='weektoday'}selected{/if}" period="weektoday" id="products_period_weektoday">{t}WTD{/t}</button> <button class="{if $products_period=='today'}selected{/if}" period="today" id="products_period_today">{t}Today{/t}</button> 
					</div>
					<div class="buttons small left cluster">
						<button class="{if $products_period=='yesterday'}selected{/if}" period="yesterday" id="products_period_yesterday">{t}Yesterday{/t}</button> <button class="{if $products_period=='last_w'}selected{/if}" period="last_w" id="products_period_last_w">{t}Last Week{/t}</button> <button class="{if $products_period=='last_m'}selected{/if}" period="last_m" id="products_period_last_m">{t}Last Month{/t}</button> 
					</div>
					<div class="buttons small left cluster">
						<button class="{if $products_period=='three_year'}selected{/if}" period="three_year" id="products_period_three_year">{t}3Y{/t}</button> <button class="{if $products_period=='year'}selected{/if}" period="year" id="products_period_year">{t}1Yr{/t}</button> <button class="{if $products_period=='six_month'}selected{/if}" period="six_month" id="products_period_six_month">{t}6M{/t}</button> <button class="{if $products_period=='quarter'}selected{/if}" period="quarter" id="products_period_quarter">{t}1Qtr{/t}</button> <button class="{if $products_period=='month'}selected{/if}" period="month" id="products_period_month">{t}1M{/t}</button> <button class="{if $products_period=='ten_day'}selected{/if}" period="ten_day" id="products_period_ten_day">{t}10D{/t}</button> <button class="{if $products_period=='week'}selected{/if}" period="week" id="products_period_week">{t}1W{/t}</button> 
						<div class="buttons small left cluster">
							<button style="margin-left:20px" period="custome" id="custome_period">{t}Custome Dates{/t}</button> 
						</div>
					</div>
					<div style="clear:both">
					</div>
				</div>
				{*} 
				<div style="margin-top:0px">
					<table class="show_info_product" style="float:left;width:250px">
						<tbody>
							<tr>
								<td>{t}Sales{/t}:</td>
								<td class=" aright">{$sales}</td>
							</tr>
							<tr>
								<td>{t}Profit{/t}:</td>
								<td class=" aright">{$profits}</td>
							</tr>
							<tr>
								<td>{t}Outers{/t}:</td>
								<td class="aright">{$outers}</td>
							</tr>
						</tbody>
						{*} {foreach from=$period_tags item=period } 
						<tbody id="info_{$period.key}" style="{if $period_type!=$period.key}display:none{/if}">
							<tr>
								<td>{t}Sales{/t}:</td>
								<td class=" aright">{$family->get_period({$period.db},'Acc Invoiced Amount')}</td>
							</tr>
							<tr>
								<td>{t}Profit{/t}:</td>
								<td class=" aright">{$family->get_period({$period.db},'Acc Profit')}</td>
							</tr>
							<tr>
								<td>{t}Outers{/t}:</td>
								<td class="aright">{$family->get_period({$period.db},'Acc Quantity Delivered')}</td>
							</tr>
						</tbody>
						{/foreach} 
						<tbody id="info_other" style="display:none">
							<tr>
								<td>{t}Sales{/t}:</td>
								<td class=" aright"><span id="other_sales"></span><img style="display:none" id="waiting_other_sales" src="art/loading.gif"></td>
							</tr>
							<tr>
								<td>{t}Profit{/t}:</td>
								<td class=" aright"><span id="other_profits"></span><img style="display:none" id="waiting_other_profits" src="art/loading.gif"></td>
							</tr>
							<tr>
								<td>{t}Outers{/t}:</td>
								<td class="aright"><span id="other_outers"></span><img style="display:none" id="waiting_other_outers" src="art/loading.gif"></td>
							</tr>
						</tbody>
						{*} 
					</table>
					<table class="show_info_product" style="float:left;width:250px;margin-left:20px">
						<tbody>
							<tr>
								<td>{t}Invoices{/t}:</td>
								<td class="aright">{$invoices}</td>
							</tr>
							<tr>
								<td>{t}Customers{/t}:</td>
								<td class=" aright">{$customers}</td>
							</tr>
						</tbody>
						{*} {foreach from=$period_tags item=period } 
						<tbody id="info2_{$period.key}" style="{if $period_type!=$period.key}display:none{/if}">
							<tr>
								<td>{t}Invoices{/t}:</td>
								<td class="aright">{$family->get_period({$period.db},'Acc Invoices')}</td>
							</tr>
							<tr>
								<td>{t}Customers{/t}:</td>
								<td class=" aright">{$family->get_period({$period.db},'Acc Customers')}</td>
							</tr>
						</tbody>
						{/foreach} 
						<tbody id="info2_other" style="display:none">
							<tr>
								<td>{t}Invoices{/t}:</td>
								<td class="aright"><span id="other_invoices"></span><img style="display:none" id="waiting_other_invoices" src="art/loading.gif"></td>
							</tr>
							<tr>
								<td>{t}Customers{/t}:</td>
								<td class=" aright"><span id="other_customers"></span><img style="display:none" id="waiting_other_customers" src="art/loading.gif"></td>
							</tr>
						</tbody>
						{*} 
					</table>
				</div>
			</div>
			<div id="sales_sub_blocks" style="clear:both">
				<ul class="tabs" id="chooser_ul" style="margin-top:25px">
					<li> <span class="item {if $sales_sub_block_tipo=='plot_family_sales'}selected{/if}" onclick="change_sales_sub_block(this)" id="plot_family_sales" tipo="store"> <span>{t}Sales Chart{/t}</span> </span> </li>
					<li> <span class="item {if $sales_sub_block_tipo=='family_sales_timeseries'}selected{/if}" onclick="change_sales_sub_block(this)" id="family_sales_timeseries" tipo="store"> <span>{t}Family Sales History{/t}</span> </span> </li>
					<li> <span class="item {if $sales_sub_block_tipo=='children_list'}selected{/if}" onclick="change_sales_sub_block(this)" id="children_list" tipo="list" forecast="" interval=""> <span>{t}Products Sold{/t}</span> </span> </li>
				</ul>
				<div id="sub_block_plot_family_sales" style="min-height:400px;clear:both;border:1px solid #ccc;{if $sales_sub_block_tipo!='plot_family_sales'}display:none{/if}">
<script type="text/javascript" src="external_libs/amstock/amstock/swfobject.js"></script> <script type="text/javascript">
		// <![CDATA[
		var so = new SWFObject("external_libs/amstock/amstock/amstock.swf", "amstock", "905", "500", "8", "#FFFFFF");
		so.addVariable("path", "");
		so.addVariable("settings_file", encodeURIComponent("conf/plot_asset_sales.xml.php?tipo=family_sales&family_key={$family->id}"));
		so.addVariable("preloader_color", "#999999");
		so.write("sub_block_plot_family_sales");
		// ]]>
	</script> 
				</div>
				<div id="sub_block_children_list" style="min-height:400px;clear:both;border:1px solid #ccc;padding:20px;{if $sales_sub_block_tipo!='children_list'}display:none{/if}">
					<div class="data_table" style="margin-top:0px;clear:both">
						<span id="table_title" class="clean_table_title" style="position:relative;bottom:-3px">{t}Products Sold{/t} <span style="font-size:75%"><img src="art/icons/clock_16.png" style="height:11px;position:relative;bottom:3px"> {$period_tag}</span> <img id="export_csv1" class="export_data_link" label="{t}Export (CSV/XML){/t}" alt="{t}Export (CSV/XML){/t}" src="art/icons/export_csv.gif"></span> 
						<div id="table_type" class="table_type">
							<div style="font-size:90%" id="transaction_chooser">
								<span style="float:right;margin-left:15px;" class=" table_type transaction_type state_details {if $product_sales_elements.Historic}selected{/if} label_family_products_changes" id="elements_product_sales_historic" table_type="historic">{t}Historic{/t} (<span id="elements_product_sales_historic_number">{$product_sales_elements_number.Historic}</span>)</span> <span style="float:right;margin-left:15px;" class=" table_type transaction_type state_details {if $product_sales_elements.Discontinued}selected{/if} label_family_products_discontinued" id="elements_product_sales_discontinued" table_type="discontinued">{t}Discontinued{/t} (<span id="elements_product_sales_discontinued_number">{$product_sales_elements_number.Discontinued}</span>)</span> <span style="float:right;margin-left:15px" class=" table_type transaction_type state_details {if $product_sales_elements.Private}selected{/if} label_family_products_private" id="elements_product_sales_private" table_type="private">{t}Private Sale{/t} (<span id="elements_product_sales_private_number">{$product_sales_elements_number.Private}</span>)</span> <span style="float:right;margin-left:15px" class=" table_type transaction_type state_details {if $product_sales_elements.NoSale}selected{/if} label_family_products_nosale" id="elements_product_sales_nosale" table_type="nosale">{t}Not for Sale{/t} (<span id="elements_product_sales_nosale_number">{$product_sales_elements_number.NoSale}</span>)</span> <span style="float:right;margin-left:15px" class=" table_type transaction_type state_details {if $product_sales_elements.Sale}selected{/if} label_family_products_sale" id="elements_product_sales_sale" table_type="sale">{t}Public Sale{/t} (<span id="elements_product_sales_notes_number">{$product_sales_elements_number.Sale}</span>)</span> 
							</div>
						</div>
						<div class="table_top_bar" style="margin-bottom:15px">
						</div>
						{include file='table_splinter.tpl' table_id=1 filter_name=$filter_name1 filter_value=$filter_value1 } 
						<div id="table1" class="data_table_container dtable btable" style="font-size:90%">
						</div>
					</div>
					<div style="clear:both">
					</div>
				</div>
				<div id="sub_block_family_sales_timeseries" style="min-height:400px;clear:both;border:1px solid #ccc;padding:20px;{if $sales_sub_block_tipo!='family_sales_timeseries'}display:none{/if}">
					<span class="clean_table_title">{t}Family Sales History{/t}</span> 
					<div>
						<span tipo='year' id="product_sales_history_type_year" style="float:right" class="table_type state_details {if $product_sales_history_type=='year'}selected{/if}">{t}Yearly{/t}</span>
						<span tipo='month'  id="product_sales_history_type_month" style="float:right;margin-right:10px" class="table_type state_details {if $product_sales_history_type=='month'}selected{/if}">{t}Monthly{/t}</span>
						<span tipo='week'  id="product_sales_history_type_week" style="float:right;margin-right:10px" class="table_type state_details {if $product_sales_history_type=='week'}selected{/if}">{t}Weekly{/t}</span>
						<span tipo='day'  id="product_sales_history_type_day" style="float:right;margin-right:10px" class="table_type state_details {if $product_sales_history_type=='day'}selected{/if}">{t}Daily{/t}</span> 
					</div>
					<div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999;margin-bottom:10px">
					</div>
					{include file='table_splinter.tpl' table_id=2 filter_name=$filter_name2 filter_value=$filter_value2 no_filter=1 } 
					
					<div id="table2" style="font-size:85%" class="data_table_container dtable btable">
					</div>
				</div>
				<div style="clear:both">
				</div>
			</div>
			<div style="clear:both">
			</div>
		</div>
		<div id="block_details" style="{if $block_view!='details'}display:none;{/if}clear:both;margin:10px 0 40px 0">
			<div id="photo_container" style="margin-top:0px;float:left">
				<div style="border:1px solid #ddd;padding-stop:0;width:220px;text-align:center;margin:0 10px 0 0px">
					<div id="imagediv" style="border:1px solid #ddd;width:190px;padding:5px 5px;xborder:none;cursor:pointer;xbackground:red;margin: 10px 0 10px 9px;vertical-align:middle">
						<img src="{$family->get('Product Family Main Image')}" style="vertical-align:middle;display:block;" valign="center" border="1" id="image" alt="{t}Image{/t}" /> 
					</div>
				</div>
				<div style="width:160px;margin:auto;padding-top:5px">
					{foreach from=$family->get_images_slidesshow() item=image name=foo} {if $image.is_principal==0} <img style="float:left;border:1px solid#ccc;padding:2px;margin:2px;cursor:pointer" src="{$image.thumbnail_url}" title="" alt="" /> {/if} {/foreach} 
				</div>
			</div>
			<h2 style="margin:20px 0 0 0 ;padding:0">
				Family Information 
			</h2>
			<div style="width:350px;float:left">
				<table class="show_info_product">
					<tr>
						<td>{t}Code{/t}:</td>
						<td class="price">{$family->get('Product Family Code')}</td>
					</tr>
					<tr>
						<td>{t}Name{/t}:</td>
						<td>{$family->get('Product Family Name')}</td>
					</tr>
					<tr>
						<td>{t}Record Type{/t}:</td>
						<td>{$family->get('Product Family Record Type')}</td>
					</tr>
					<tr>
						<td>{t}Similar{/t}:</td>
						<td>{$family->get('Similar Families')}</td>
					</tr>
					<tr>
						<td>{t}Categories{/t}:</td>
						<td>{$family->get('Categories')}</td>
					</tr>
					<tr>
						<td>{t}Web Page{/t}:</td>
						<td>{$family->get('Web Page Links')}</td>
					</tr>
				</table>
			</div>
		</div>
		<div id="block_web" style="{if $block_view!='web'}display:none;{/if}clear:both;margin:10px 0 40px 0">
			<span class="clean_table_title">{t}Pages{/t}</span> 
			<div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999;margin-bottom:15px">
			</div>
			{include file='table_splinter.tpl' table_id=4 filter_name=$filter_name4 filter_value=$filter_value4 no_filter=1 } 
			<div id="table4" class="data_table_container dtable btable" style="font-size:85%">
			</div>
		</div>
		<div id="block_products" style="{if $block_view!='products'}display:none;{/if}clear:both;margin:10px 0 40px 0">
			<div class="data_table" style="margin-top:10px;clear:both">
				<span id="table_title" class="clean_table_title">{t}Products{/t} <img id="export_csv0" class="export_data_link" label="{t}Export (CSV/XML){/t}" alt="{t}Export (CSV/XML){/t}" src="art/icons/export_csv.gif"></span> 
				<div id="table_type" class="table_type">
					<div style="font-size:90%" id="transaction_chooser">
						<span style="float:right;margin-left:20px;" class=" table_type transaction_type state_details {if $elements.Historic}selected{/if} label_family_products_changes" id="elements_historic" table_type="historic">{t}Historic{/t} (<span id="elements_historic_number">{$elements_number.Historic}</span>)</span> <span style="float:right;margin-left:20px;" class=" table_type transaction_type state_details {if $elements.Discontinued}selected{/if} label_family_products_discontinued" id="elements_discontinued" table_type="discontinued">{t}Discontinued{/t} (<span id="elements_discontinued_number">{$elements_number.Discontinued}</span>)</span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements.Private}selected{/if} label_family_products_private" id="elements_private" table_type="private">{t}Private Sale{/t} (<span id="elements_private_number">{$elements_number.Private}</span>)</span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements.NoSale}selected{/if} label_family_products_nosale" id="elements_nosale" table_type="nosale">{t}Not for Sale{/t} (<span id="elements_nosale_number">{$elements_number.NoSale}</span>)</span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements.Sale}selected{/if} label_family_products_sale" id="elements_sale" table_type="sale">{t}Public Sale{/t} (<span id="elements_notes_number">{$elements_number.Sale}</span>)</span> 
					</div>
				</div>
				<div class="table_top_bar">
				</div>
				<div class="buttons small clusters">
					<button class="selected" id="change_products_display_mode">{$display_products_mode_label}</button> <button class="selected" id="change_products_table_type">{$products_table_type_label}</button> 
				</div>
				<div class="clusters" id="table_view_menu0" style="{if $products_table_type=='thumbnails'}display:none{/if}">
					<div class="buttons small left cluster">
						<button class="table_option {if $product_view=='general'}selected{/if}" id="product_general">{t}Overview{/t}</button> <button class="table_option {if $product_view=='stock'}selected{/if}" id="product_stock" {if !$view_stock}style="display:none" {/if}>{t}Stock{/t}</button> <button class="table_option {if $product_view=='sales'}selected{/if}" id="product_sales" {if !$view_sales}style="display:none" {/if}>{t}Sales{/t}</button> <button class="table_option {if $product_view=='parts'}selected{/if}" id="product_parts" {if !$view_sales}style="display:none" {/if}>{t}Parts{/t}</button> <button class="table_option {if $product_view=='cats'}selected{/if}" id="product_cats" {if !$view_sales}style="display:none" {/if}>{t}Groups{/t}</button> 
					</div>
					<div id="product_period_options" class="buttons small left cluster" style="display:{if $product_view!='sales' }none{else}block{/if};">
						<button class="table_option {if $product_period=='all'}selected{/if}" period="all" id="product_period_all">{t}All{/t}</button> <button class="table_option {if $product_period=='three_year'}selected{/if}" period="three_year" id="product_period_three_year">{t}3Y{/t}</button> <button class="table_option {if $product_period=='year'}selected{/if}" period="year" id="product_period_year">{t}1Yr{/t}</button> <button class="table_option {if $product_period=='yeartoday'}selected{/if}" period="yeartoday" id="product_period_yeartoday">{t}YTD{/t}</button> <button class="table_option {if $product_period=='six_month'}selected{/if}" period="six_month" id="product_period_six_month">{t}6M{/t}</button> <button class="table_option {if $product_period=='quarter'}selected{/if}" period="quarter" id="product_period_quarter">{t}1Qtr{/t}</button> <button class="table_option {if $product_period=='month'}selected{/if}" period="month" id="product_period_month">{t}1M{/t}</button> <button class="table_option {if $product_period=='ten_day'}selected{/if}" period="ten_day" id="product_period_ten_day">{t}10D{/t}</button> <button class="table_option {if $product_period=='week'}selected{/if}" period="week" id="product_period_week">{t}1W{/t}</button> 
					</div>
					<div id="product_avg_options" class="buttons small left cluster" style="display:{if $product_view!='sales' }none{else}block{/if};">
						<button class="table_option {if $product_avg=='totals'}selected{/if}" avg="totals" id="product_avg_totals">{t}Totals{/t}</button> <button class="table_option {if $product_avg=='month'}selected{/if}" avg="month" id="product_avg_month">{t}M AVG{/t}</button> <button class="table_option {if $product_avg=='week'}selected{/if}" avg="week" id="product_avg_week">{t}W AVG{/t}</button> <button class="table_option {if $product_avg=='month_eff'}selected{/if}" style="display:none" avg="month_eff" id="product_avg_month_eff">{t}M EAVG{/t}</button> <button class="table_option {if $product_avg=='week_eff'}selected{/if}" style="display:none" avg="week_eff" id="product_avg_week_eff">{t}W EAVG{/t}</button> 
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
		<div id="block_deals" style="{if $block_view!='deals'}display:none;{/if}clear:both;margin:10px 0 40px 0">
		</div>
		<div id="block_categories" style="{if $block_view!='categories'}display:none;{/if}clear:both;margin:10px 0 40px 0">
		</div>
	</div>
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
			<li class="yuimenuitem"><a class="yuimenuitemlabel" onclick="change_rpp({$menu},1)"> {$menu}</a></li>
			{/foreach} 
		</ul>
	</div>
</div>
<div id="rppmenu2" class="yuimenu">
	<div class="bd">
		<ul class="first-of-type">
			<li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Rows per Page{/t}:</li>
			{foreach from=$paginator_menu2 item=menu } 
			<li class="yuimenuitem"><a class="yuimenuitemlabel" onclick="change_rpp({$menu},2)"> {$menu}</a></li>
			{/foreach} 
		</ul>
	</div>
</div>

<div id="info_period_menu" class="yuimenu">
	<div class="bd">
		<ul class="first-of-type">
			<li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Period{/t}:</li>
			{foreach from=$info_period_menu item=menu } 
			<li class="yuimenuitem"><a class="yuimenuitemlabel" onclick="change_info_period('{$menu.period}','{$menu.title}')"> {$menu.label}</a></li>
			{/foreach} 
		</ul>
	</div>
</div>
<div id="change_products_display_menu" style="padding:10px 20px 0px 10px">
	<table class="edit" border="0" style="width:200px">
		<tr class="title">
			<td>{t}Display Mode Options{/t}:</td>
		</tr>
		<tr style="height:5px">
			<td></td>
		</tr>
		{foreach from=$products_mode_options_menu item=menu } 
		<tr>
			<td> 
			<div class="buttons">
				<button style="float:none;margin:0px auto;min-width:120px" onclick="change_display_mode('products','{$menu.mode}','{$menu.label}',0)"> {$menu.label}</button> 
			</div>
			</td>
		</tr>
		{/foreach} 
	</table>
</div>
<div id="change_products_table_type_menu" style="padding:10px 20px 0px 10px">
	<table class="edit" border="0" style="width:200px">
		<tr class="title">
			<td>{t}View items as{/t}:</td>
		</tr>
		<tr style="height:5px">
			<td></td>
		</tr>
		{foreach from=$products_table_type_menu item=menu } 
		<tr>
			<td> 
			<div class="buttons">
				<button style="float:none;margin:0px auto;min-width:120px" onclick="change_table_type('products','{$menu.mode}','{$menu.label}',0)"> {$menu.label}</button> 
			</div>
			</td>
		</tr>
		{/foreach} 
	</table>
</div>
{include file='footer.tpl'} 