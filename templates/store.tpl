{include file='header.tpl'} 
<div id="bd" class="no_padding">
	<div style="padding:0 20px">
		{include file='assets_navigation.tpl'} 
		<input type="hidden" id="link_extra_argument" value="&id={$store->id}" />
		<input type="hidden" id="from" value="{$from}" />
		<input type="hidden" id="to" value="{$to}" />
		<input type="hidden" id="store_key" value="{$store->id}"> 
		<input type="hidden" id="history_table_id" value="5"> 
		<input type="hidden" id="subject" value="store"> 
		<input type="hidden" id="subject_key" value="{$store->id}"> 
		<input type="hidden" id="products_table_id" value="2"> 
		<div class="branch">
			<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a>&rarr; {if $user->get_number_stores()>1}<a href="stores.php">{t}Stores{/t}</a> &rarr; {/if}{$store->get('Store Name')}</span> 
		</div>
		<div class="top_page_menu">
			<div class="buttons" style="float:right">
				{if $modify} <button onclick="window.location='edit_store.php?id={$store->id}'"><img src="art/icons/vcard_edit.png" alt=""> {t}Edit Store{/t}</button> {/if} <button style="display:none" onclick="window.location='store_stats.php?store={$store->id}'"><img src="art/icons/chart_pie.png" alt=""> {t}Statistics{/t}</button> <button style="display:none" onclick="window.location='store_deals.php?store={$store->id}'"><img src="art/icons/money.png" alt=""> {t}Offers{/t}</button> <button style="display:none" onclick="window.location='products_lists.php?store={$store->id}'"><img src="art/icons/table.png" alt=""> {t}Lists{/t}</button> <button onclick="window.location='product_categories.php?id=0&store={$store->id}'"><img src="art/icons/chart_organisation.png" alt=""> {t}Categories{/t}</button> {if $store->get('Store Websites')} <button style="display:none" onclick="window.location='sites.php?store={$store->id}'"><img src="art/icons/world.png" alt=""> {if $store->get('Store Websites')>1}{t}Websites{/t}{else}{t}Website{/t}{/if}</button> {/if} 
			</div>
			<div class="buttons" style="float:left">
				<span class="main_title"> <img src="art/icons/store.png" style="height:18px;position:relative;bottom:2px"/> {$store->get('Store Name')} ({$store->get('Store Code')}) </span> 
			</div>
			<div style="clear:both">
			</div>
		</div>
	</div>
	<ul class="tabs" id="chooser_ul" style="clear:both;margin-top:15px">
		<li> <span class="item {if $block_view=='details'}selected{/if}" id="details"> <span> {t}Overview{/t}</span></span></li>
		<li> <span class="item {if $block_view=='sales'}selected{/if}" id="sales"> <span> {t}Sales{/t}</span></span></li>
		<li> <span class="item {if $block_view=='categories'}selected{/if}" style="display:none" id="categories"> <span> {t}Categories{/t}</span></span></li>
		<li> <span class="item {if $block_view=='departments'}selected{/if}" id="departments"> <span> {t}Departments{/t}</span></span></li>
		<li> <span class="item {if $block_view=='families'}selected{/if}" id="families"> <span> {t}Families{/t}</span></span></li>
		<li> <span class="item {if $block_view=='products'}selected{/if}" id="products"><span> {t}Products{/t}</span></span></li>
		<li {if $store->get('Store Websites')<2}style="display:none"{/if}> <span class="item {if $block_view=='sites'}selected{/if}" id="sites"> <span> {t}Websites{/t}</span></span></li>
		<li> <span class="item {if $block_view=='deals'}selected{/if}" style="display:none" id="deals"> <span> {t}Offers{/t}</span></span></li>
		<li> <span class="item {if $block_view=='pages'}selected{/if}" style="{if !$number_sites}display:none{/if}" id="pages"> <span> {t}Webpages{/t}</span></span></li>
	</ul>
	<div style="clear:both;width:100%;border-bottom:1px solid #ccc">
	</div>
	<div style="padding:0 20px">
		<div id="block_sales" style="{if $block_view!='sales'}display:none;{/if}clear:both;padding-top:0;margin:0px 0 40px 0;">
			{include file='calendar_splinter.tpl'} 
			<div style="width:900px;float:left;margin-left:20px;">
				<span><img src="art/icons/clock_16.png" style="height:12px;position:relative;bottom:2px"> {$period}</span> 
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
						</tbody>
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
					</table>
				</div>
			</div>
			<div id="sales_sub_blocks" style="clear:both">
				<ul class="tabs" id="chooser_ul" style="margin-top:25px">
					<li> <span class="item {if $sales_sub_block_tipo=='plot_store_sales'}selected{/if}" onclick="change_sales_sub_block(this)" id="plot_store_sales" tipo="store"> <span>{t}Sales Chart{/t}</span> </span> </li>
					<li> <span class="item {if $sales_sub_block_tipo=='store_sales_timeseries'}selected{/if}" onclick="change_sales_sub_block(this)" id="store_sales_timeseries" tipo="store"> <span>{t}Store Sales History{/t}</span> </span> </li>
					<li> <span class="item {if $sales_sub_block_tipo=='store_department_sales'}selected{/if}" onclick="change_sales_sub_block(this)" id="store_department_sales" tipo="list" forecast="" interval=""> <span>{t}Department Sales{/t}</span> </span> </li>
					<li style="display:none"> <span class="item {if $sales_sub_block_tipo=='store_family_sales'}selected{/if}" onclick="change_sales_sub_block(this)" id="store_family_sales" tipo="list" forecast="" interval=""> <span>{t}Family Sales{/t}</span> </span> </li>
					<li style="display:none"> <span class="item {if $sales_sub_block_tipo=='store_product_sales'}selected{/if}" onclick="change_sales_sub_block(this)" id="store_product_sales" tipo="list" forecast="" interval=""> <span>{t}Product Sales{/t}</span> </span> </li>
				</ul>
				<div id="sub_block_plot_store_sales" style="min-height:400px;clear:both;border:1px solid #ccc;{if $sales_sub_block_tipo!='plot_store_sales'}display:none{/if}">
<script type="text/javascript" src="external_libs/amstock/amstock/swfobject.js"></script> <script type="text/javascript">
		// <![CDATA[
		var so = new SWFObject("external_libs/amstock/amstock/amstock.swf", "amstock", "905", "500", "8", "#FFFFFF");
		so.addVariable("path", "");
		so.addVariable("settings_file", encodeURIComponent("conf/plot_asset_sales.xml.php?tipo=store_sales&store_key={$store->id}"));
		so.addVariable("preloader_color", "#999999");
		so.write("sub_block_plot_store_sales");
		// ]]>
				</script> 
				</div>
				<div id="sub_block_store_department_sales" style="min-height:400px;clear:both;border:1px solid #ccc;padding:20px;{if $sales_sub_block_tipo!='store_department_sales'}display:none{/if}">
					<div class="data_table" style="margin-top:0px;clear:both">
						<span id="table_title" class="clean_table_title" style="position:relative;bottom:-3px">{t}Departments Sales{/t} <span style="font-size:75%"><img src="art/icons/clock_16.png" style="height:11px;position:relative;bottom:3px"> {$period_tag}</span> <img id="export_csv1" class="export_data_link" label="{t}Export (CSV/XML){/t}" alt="{t}Export (CSV/XML){/t}" src="art/icons/export_csv.gif"></span> 
						<div class="table_top_bar space">
						</div>
						{include file='table_splinter.tpl' table_id=7 filter_name=$filter_name7 filter_value=$filter_value7 } 
						<div id="table7" class="data_table_container dtable btable" style="font-size:90%">
						</div>
					</div>
					<div style="clear:both">
					</div>
				</div>
				<div id="sub_block_store_family_sales" style="min-height:400px;clear:both;border:1px solid #ccc;padding:20px;{if $sales_sub_block_tipo!='store_family_sales'}display:none{/if}">
					<div class="data_table" style="margin-top:0px;clear:both">
						<span id="table_title" class="clean_table_title" style="position:relative;bottom:-3px">{t}Families Sales{/t} <span style="font-size:75%"><img src="art/icons/clock_16.png" style="height:11px;position:relative;bottom:3px"> {$period_tag}</span> <img id="export_csv1" class="export_data_link" label="{t}Export (CSV/XML){/t}" alt="{t}Export (CSV/XML){/t}" src="art/icons/export_csv.gif"></span> {*} 
						<div id="table_type" class="table_type">
							<div style="font-size:90%" id="transaction_chooser">
								<span style="float:right;margin-left:15px;" class=" table_type transaction_type state_details {if $department_sales_elements.Historic}selected{/if} label_store_departments_changes" id="elements_department_sales_historic" table_type="historic">{t}Historic{/t} (<span id="elements_department_sales_Historic_number"><img src="art/loading.gif" style="height:12px;position:relative;bottom:1px"></span>)</span> <span style="float:right;margin-left:15px;" class=" table_type transaction_type state_details {if $department_sales_elements.Discontinued}selected{/if} label_store_departments_discontinued" id="elements_department_sales_discontinued" table_type="discontinued">{t}Discontinued{/t} (<span id="elements_department_sales_Discontinued_number"><img src="art/loading.gif" style="height:12px;position:relative;bottom:1px"></span>)</span> <span style="float:right;margin-left:15px" class=" table_type transaction_type state_details {if $department_sales_elements.Private}selected{/if} label_store_departments_private" id="elements_department_sales_private" table_type="private">{t}Private Sale{/t} (<span id="elements_department_sales_Private_number"><img src="art/loading.gif" style="height:12px;position:relative;bottom:1px" /></span>)</span> <span style="float:right;margin-left:15px" class=" table_type transaction_type state_details {if $department_sales_elements.NoSale}selected{/if} label_store_departments_nosale" id="elements_department_sales_nosale" table_type="nosale">{t}Not for Sale{/t} (<span id="elements_department_sales_NoSale_number"><img src="art/loading.gif" style="height:12px;position:relative;bottom:1px" /></span>)</span> <span style="float:right;margin-left:15px" class=" table_type transaction_type state_details {if $department_sales_elements.Sale}selected{/if} label_store_departments_sale" id="elements_department_sales_sale" table_type="sale">{t}Public Sale{/t} (<span id="elements_department_sales_Sale_number"><img src="art/loading.gif" style="height:12px;position:relative;bottom:1px" /></span>)</span> 
							</div>
						</div>
						{*} 
						<div class="table_top_bar space">
						</div>
						{include file='table_splinter.tpl' table_id=8 filter_name=$filter_name8 filter_value=$filter_value8 } 
						<div id="table8" class="data_table_container dtable btable" style="font-size:90%">
						</div>
					</div>
					<div style="clear:both">
					</div>
				</div>
				<div id="sub_block_store_product_sales" style="min-height:400px;clear:both;border:1px solid #ccc;padding:20px;{if $sales_sub_block_tipo!='store_product_sales'}display:none{/if}">
					<div class="data_table" style="margin-top:0px;clear:both">
						<span id="table_title" class="clean_table_title" style="position:relative;bottom:-3px">{t}Products Sold{/t} <span style="font-size:75%"><img src="art/icons/clock_16.png" style="height:11px;position:relative;bottom:3px"> {$period_tag}</span> <img id="export_csv1" class="export_data_link" label="{t}Export (CSV/XML){/t}" alt="{t}Export (CSV/XML){/t}" src="art/icons/export_csv.gif"></span> {*} 
						<div id="table_type" class="table_type">
							<div style="font-size:90%" id="transaction_chooser">
								<span style="float:right;margin-left:15px;" class=" table_type transaction_type state_details {if $product_sales_elements.Historic}selected{/if} label_store_departments_changes" id="elements_department_sales_historic" table_type="historic">{t}Historic{/t} (<span id="elements_department_sales_Historic_number"><img src="art/loading.gif" style="height:12px;position:relative;bottom:1px"></span>)</span> <span style="float:right;margin-left:15px;" class=" table_type transaction_type state_details {if $product_sales_elements.Discontinued}selected{/if} label_store_departments_discontinued" id="elements_department_sales_discontinued" table_type="discontinued">{t}Discontinued{/t} (<span id="elements_department_sales_Discontinued_number"><img src="art/loading.gif" style="height:12px;position:relative;bottom:1px"></span>)</span> <span style="float:right;margin-left:15px" class=" table_type transaction_type state_details {if $product_sales_elements.Private}selected{/if} label_store_departments_private" id="elements_department_sales_private" table_type="private">{t}Private Sale{/t} (<span id="elements_department_sales_Private_number"><img src="art/loading.gif" style="height:12px;position:relative;bottom:1px" /></span>)</span> <span style="float:right;margin-left:15px" class=" table_type transaction_type state_details {if $product_sales_elements.NoSale}selected{/if} label_store_departments_nosale" id="elements_department_sales_nosale" table_type="nosale">{t}Not for Sale{/t} (<span id="elements_department_sales_NoSale_number"><img src="art/loading.gif" style="height:12px;position:relative;bottom:1px" /></span>)</span> <span style="float:right;margin-left:15px" class=" table_type transaction_type state_details {if $product_sales_elements.Sale}selected{/if} label_store_departments_sale" id="elements_department_sales_sale" table_type="sale">{t}Public Sale{/t} (<span id="elements_department_sales_Sale_number"><img src="art/loading.gif" style="height:12px;position:relative;bottom:1px" /></span>)</span> 
							</div>
						</div>
						{*} 
						<div class="table_top_bar space">
						</div>
						{include file='table_splinter.tpl' table_id=9 filter_name=$filter_name9 filter_value=$filter_value9 } 
						<div id="table9" class="data_table_container dtable btable" style="font-size:90%">
						</div>
					</div>
					<div style="clear:both">
					</div>
				</div>
				<div id="sub_block_store_sales_timeseries" style="min-height:400px;clear:both;border:1px solid #ccc;padding:20px;{if $sales_sub_block_tipo!='store_sales_timeseries'}display:none{/if}">
					<span class="clean_table_title">{t}Store Sales History{/t}</span> 
					<div>
						<span tipo='year' id="store_sales_history_type_year" style="float:right" class="table_type state_details {if $store_sales_history_type=='year'}selected{/if}">{t}Yearly{/t}</span> <span tipo='month' id="store_sales_history_type_month" style="float:right;margin-right:10px" class="table_type state_details {if $store_sales_history_type=='month'}selected{/if}">{t}Monthly{/t}</span> <span tipo='week' id="store_sales_history_type_week" style="float:right;margin-right:10px" class="table_type state_details {if $store_sales_history_type=='week'}selected{/if}">{t}Weekly{/t}</span> <span tipo='day' id="store_sales_history_type_day" style="float:right;margin-right:10px" class="table_type state_details {if $store_sales_history_type=='day'}selected{/if}">{t}Daily{/t}</span> 
					</div>
					<div class="table_top_bar space"></div>
					{include file='table_splinter.tpl' table_id=6 filter_name=$filter_name6 filter_value=$filter_value6 no_filter=1 } 
					<div id="table6" style="font-size:85%" class="data_table_container dtable btable">
					</div>
				</div>
				<div style="clear:both">
				</div>
			</div>
		</div>
		<div id="block_details" style="{if $block_view!='details'}display:none;{/if}clear:both;margin:10px 0 40px 0">
			<div style="float:right">
				<div class="buttons " style="float:right;">
					<button id="sticky_note_button"><img src="art/icons/note.png" alt=""> {t}Note{/t}</button> <button id="note"><img src="art/icons/add.png" alt=""> {t}History Note{/t}</button> <button id="attach"><img src="art/icons/add.png" alt=""> {t}Attachment{/t}</button> 
				</div>
				<div id="sticky_note_div" class="sticky_note" style="clear:both;margin-top:10px;margin-right:5px">
					<img id="sticky_note_bis" style="float:right;cursor:pointer" src="art/icons/edit.gif"> 
					<div id="sticky_note_content" style="padding:10px 15px 10px 15px;">
						{$sticky_note} 
					</div>
				</div>
			</div>
			<div style="margin-bottom:20px">
				<h2 style="margin:0;padding:0">
					{t}Store Information{/t} 
				</h2>
				<div style="width:350px;float:left">
					<table class="show_info_product">
						<tr>
							<td>{t}Code{/t}:</td>
							<td class="price">{$store->get('Store Code')}</td>
						</tr>
						<tr>
							<td>{t}Name{/t}:</td>
							<td>{$store->get('Store Name')}</td>
						</tr>
						{if $store->get('Store Websites')} <button style="display:none" onclick="window.location='sites.php?store={$store->id}'"><img src="art/icons/world.png" alt=""> {if $store->get('Store Websites')>1}{t}Websites{/t}{else}{t}Website{/t}{/if}</button> {/if} {foreach from=$store->get_sites_data(true) item=site name=sites} 
						<tr>
							<td>{if $smarty.foreach.sites.first}{t}Web Page{/t}:{/if}</td>
							<td> <a href="site.php?id={$site.SiteKey}">{$site.SiteURL}</a> </td>
						</tr>
						{/foreach} 
					</table>
				</div>
				<div style="width:200px;float:left;margin-left:20px">
					<table class="show_info_product">
						<tr>
							<td>{t}Departments{/t}:</td>
							<td class="number"> 
							<div>
								{$store->get('Departments')} 
							</div>
							</td>
						</tr>
						<tr>
							<td>{t}Families{/t}:</td>
							<td class="number"> 
							<div>
								{$store->get('Families')} 
							</div>
							</td>
						</tr>
						<tr>
							<td>{t}Products{/t}:</td>
							<td class="number"> 
							<div>
								{$store->get('For Public Sale Products')} 
							</div>
							</td>
						</tr>
					</table>
				</div>
				<div style="clear:both;">
				</div>
			</div>
			<div style="clear:both;">
				<span class="clean_table_title">{t}History/Notes{/t}</span> 
				<div id="table_type" class="table_type">
					<div style="font-size:90%" id="store_history_transaction_chooser">
						<span style="float:right;margin-left:20px;" class=" table_type transaction_type state_details {if $elements_store_history.Changes}selected{/if} label_store_history_changes" id="elements_store_history_changes" table_type="elements_changes">{t}Changes History{/t} (<span id="elements_changes_number">{$elements_store_history_number.Changes}</span>)</span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_store_history.Notes}selected{/if} label_store_history_notes" id="elements_store_history_notes" table_type="elements_notes">{t}Staff Notes{/t} (<span id="elements_notes_number">{$elements_store_history_number.Notes}</span>)</span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_store_history.Attachments}selected{/if} label_store_history_attachments" id="elements_store_history_attachments" table_type="elements_attachments">{t}Attachments{/t} (<span id="elements_notes_number">{$elements_store_history_number.Attachments}</span>)</span> 
					</div>
				</div>
				<div class="table_top_bar space">
				</div>
				{include file='table_splinter.tpl' table_id=5 filter_name=$filter_name5 filter_value=$filter_value5 } 
				<div id="table5" class="data_table_container dtable btable">
				</div>
			</div>
		</div>
		<div id="block_sites" style="{if $block_view!='sites'}display:none;{/if}clear:both;margin:20px 0 40px 0">
			<span class="clean_table_title">{t}Web Sites{/t}</span> 
			<div id="table_type">
				<span id="table_type_list" style="float:right" class="table_type state_details {if $table_type=='list'}selected{/if}">{t}List{/t}</span> <span id="table_type_thumbnail" style="float:right;margin-right:10px" class="table_type state_details {if $table_type=='thumbnails'}selected{/if}">{t}Thumbnails{/t}</span> 
			</div>
			<div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999;margin-bottom:15px">
			</div>
			{include file='table_splinter.tpl' table_id=3 filter_name=$filter_name3 filter_value=$filter_value3 no_filter=1 } 
			<div id="table3" class="data_table_container dtable btable">
			</div>
		</div>
		<div id="block_departments" style="{if $block_view!='departments'}display:none;{/if}clear:both;margin:10px 0 40px 0">
			<div class="data_table" style="clear:both;">
				<span class="clean_table_title">{t}Departments{/t} <img class="export_data_link" id="export_csv0" label="{t}Export (CSV){/t}" alt="{t}Export (CSV){/t}" src="art/icons/export_csv.gif"></span> 
				<div class="table_top_bar">
				</div>
				
				<div class="clusters">
					<div class="buttons small left cluster">
						<button class="table_option {if $department_view=='general'}selected{/if}" id="department_general">{t}Overview{/t}</button> <button class="table_option {if $department_view=='stock'}selected{/if}" id="department_stock" {if !$view_stock}style="display:none" {/if}>{t}Stock{/t}</button> <button class="table_option {if $department_view=='sales'}selected{/if}" id="department_sales" {if !$view_sales}style="display:none" {/if}>{t}Sales{/t}</button> 
					</div>
					<div id="department_period_options" class="buttons small left cluster" style="display:{if $department_view!='sales' }none{else}block{/if};">
						<button class="table_option {if $department_period=='all'}selected{/if}" period="all" id="department_period_all">{t}All{/t}</button> <button class="table_option {if $department_period=='three_year'}selected{/if}" period="three_year" id="department_period_three_year">{t}3Y{/t}</button> <button class="table_option {if $department_period=='year'}selected{/if}" period="year" id="department_period_year">{t}1Yr{/t}</button> <button class="table_option {if $department_period=='yeartoday'}selected{/if}" period="yeartoday" id="department_period_yeartoday">{t}YTD{/t}</button> <button class="table_option {if $department_period=='six_month'}selected{/if}" period="six_month" id="department_period_six_month">{t}6M{/t}</button> <button class="table_option {if $department_period=='quarter'}selected{/if}" period="quarter" id="department_period_quarter">{t}1Qtr{/t}</button> <button class="table_option {if $department_period=='month'}selected{/if}" period="month" id="department_period_month">{t}1M{/t}</button> <button class="table_option {if $department_period=='ten_day'}selected{/if}" period="ten_day" id="department_period_ten_day">{t}10D{/t}</button> <button class="table_option {if $department_period=='week'}selected{/if}" period="week" id="department_period_week">{t}1W{/t}</button> 
					</div>
					<div id="department_avg_options" class="buttons small left cluster" style="display:{if $department_view!='sales' }none{else}block{/if};">
						<button class="table_option {if $department_avg=='totals'}selected{/if}" avg="totals" id="department_avg_totals">{t}Totals{/t}</button> <button class="table_option {if $department_avg=='month'}selected{/if}" avg="month" id="department_avg_month">{t}M AVG{/t}</button> <button class="table_option {if $department_avg=='week'}selected{/if}" avg="week" id="department_avg_week">{t}W AVG{/t}</button> 
					</div>
					<div class="buttons small cluster group">
					<button id="change_departments_display_mode">&#x21b6 {$display_departments_mode_label}</button> <button  id="change_departments_table_type">&#x21b6 {if $departments_table_type=='list'}{t}List{/t}{else}{t}Thumbnails{/t}{/if}</button> 
				</div>
					<div style="clear:both">
					</div>
				</div>
				{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0 } 
				<div id="table0" class="data_table_container dtable btable with_total" style="font-size:85%">
				</div>
			</div>
		</div>
		<div id="block_families" style="{if $block_view!='families'}display:none;{/if}clear:both;margin:10px 0 40px 0">
			<div class="data_table" style="margin:0px;clear:both">
				<span class="clean_table_title">{t}Families{/t} <img id="export_csv1" tipo="families_in_department" style="position:relative;top:0px;left:5px;cursor:pointer;vertical-align:text-bottom;" label="{t}Export (CSV){/t}" alt="{t}Export (CSV){/t}" src="art/icons/export_csv.gif"></span> 
				<div  class="elements_chooser">
					
						<span style="float:right;margin-left:20px;" class=" table_type transaction_type state_details {if $elements_family.NoSale}selected{/if} label_family_products_nosale" id="elements_family_nosale" table_type="nosale">{t}No Sale{/t} (<span id="elements_family_nosale_number">{$elements_family_number.NoSale}</span>)</span> <span style="float:right;margin-left:20px;" class=" table_type transaction_type state_details {if $elements_family.Discontinued}selected{/if} label_family_products_discontinued" id="elements_family_discontinued" table_type="discontinued">{t}Discontinued{/t} (<span id="elements_family_discontinued_number">{$elements_family_number.Discontinued}</span>)</span> <span style="float:right;margin-left:20px;" class=" table_type transaction_type state_details {if $elements_family.Discontinuing}selected{/if} label_family_products_discontinued" id="elements_family_discontinuing" table_type="discontinuing">{t}Discontinuing{/t} (<span id="elements_family_discontinuing_number">{$elements_family_number.Discontinuing}</span>)</span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_family.Normal}selected{/if} label_family_products_normal" id="elements_family_normal" table_type="normal">{t}For Sale{/t} (<span id="elements_family_notes_number">{$elements_family_number.Normal}</span>)</span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_family.InProcess}selected{/if} label_family_products_inprocess" id="elements_family_inprocess" table_type="inprocess">{t}In Process{/t} (<span id="elements_family_notes_number">{$elements_family_number.InProcess}</span>)</span> 
					
				</div>
				<div class="table_top_bar">
				</div>
				
				<div class="clusters">
					<div class="buttons small left cluster">
						<button class="table_option {if $family_view=='general'}selected{/if}" id="family_general">{t}Overview{/t}</button> <button class="table_option {if $family_view=='stock'}selected{/if}" id="family_stock" {if !$view_stock}style="display:none" {/if}>{t}Stock{/t}</button> <button class="table_option {if $family_view=='sales'}selected{/if}" id="family_sales" {if !$view_sales}style="display:none" {/if}>{t}Sales{/t}</button> 
					</div>
					<div id="family_period_options" class="buttons small left cluster" style="display:{if $family_view!='sales' }none{else}block{/if};">
						<button class="table_option {if $family_period=='all'}selected{/if}" period="all" id="family_period_all">{t}All{/t}</button> <button class="table_option {if $family_period=='three_year'}selected{/if}" period="three_year" id="family_period_three_year">{t}3Y{/t}</button> <button class="table_option {if $family_period=='year'}selected{/if}" period="year" id="family_period_year">{t}1Yr{/t}</button> <button class="table_option {if $family_period=='yeartoday'}selected{/if}" period="yeartoday" id="family_period_yeartoday">{t}YTD{/t}</button> <button class="table_option {if $family_period=='six_month'}selected{/if}" period="six_month" id="family_period_six_month">{t}6M{/t}</button> <button class="table_option {if $family_period=='quarter'}selected{/if}" period="quarter" id="family_period_quarter">{t}1Qtr{/t}</button> <button class="table_option {if $family_period=='month'}selected{/if}" period="month" id="family_period_month">{t}1M{/t}</button> <button class="table_option {if $family_period=='ten_day'}selected{/if}" period="ten_day" id="family_period_ten_day">{t}10D{/t}</button> <button class="table_option {if $family_period=='week'}selected{/if}" period="week" id="family_period_week">{t}1W{/t}</button> 
					</div>
					<div id="family_avg_options" class="buttons small left cluster" style="display:{if $family_view!='sales' }none{else}block{/if};">
						<button class="table_option {if $family_avg=='totals'}selected{/if}" avg="totals" id="family_avg_totals">{t}Totals{/t}</button> <button class="table_option {if $family_avg=='month'}selected{/if}" avg="month" id="family_avg_month">{t}M AVG{/t}</button> <button class="table_option {if $family_avg=='week'}selected{/if}" avg="week" id="family_avg_week">{t}W AVG{/t}</button> 
					</div>
					<div class="buttons small cluster group">
					<button  id="change_families_display_mode">{$display_families_mode_label}</button> <button  id="change_families_table_type">{if $families_table_type=='list'}{t}List{/t}{else}{t}Thumbnails{/t}{/if}</button> 
				</div>
					<div style="clear:both">
					</div>
				</div>
				{include file='table_splinter.tpl' table_id=1 filter_name=$filter_name1 filter_value=$filter_value1} 
				<div id="table1" class="data_table_container dtable btable with_total" style="font-size:85%">
				</div>
			</div>
		</div>
		<div id="block_products" style="{if $block_view!='products'}display:none;{/if}clear:both;margin:10px 0 40px 0">
			<span class="clean_table_title">{t}Products{/t} <img id="export_csv2" class="export_data_link" label="{t}Export (CSV/XML){/t}" alt="{t}Export (CSV/XML){/t}" src="art/icons/export_csv.gif"></span> 
			<div  class="elements_chooser">
				
					<img class="menu"  id="product_element_chooser_menu_button" title="{t}Group by menu{/t}" src="art/icons/list.png" /> 
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
			
			<div class="clusters">
				<div class="buttons small left cluster">
					<button class="table_option {if $product_view=='general'}selected{/if}" id="product_general">{t}Overview{/t}</button> <button class="table_option {if $product_view=='stock'}selected{/if}" id="product_stock" {if !$view_stock}style="display:none" {/if}>{t}Stock{/t}</button> <button class="table_option {if $product_view=='sales'}selected{/if}" id="product_sales" {if !$view_sales}style="display:none" {/if}>{t}Sales{/t}</button> <button class="table_option {if $product_view=='parts'}selected{/if}" id="product_parts" {if !$view_sales}style="display:none" {/if}>{t}Parts{/t}</button> <button class="table_option {if $product_view=='cats'}selected{/if}" id="product_cats" {if !$view_sales}style="display:none" {/if}>{t}Groups{/t}</button> 
				</div>
				<div id="product_period_options" class="buttons small left cluster" style="display:{if $product_view!='sales' }none{else}block{/if};">
					<button class="table_option {if $product_period=='all'}selected{/if}" period="all" id="product_period_all">{t}All{/t}</button> <button class="table_option {if $product_period=='three_year'}selected{/if}" period="three_year" id="product_period_three_year">{t}3Y{/t}</button> <button class="table_option {if $product_period=='year'}selected{/if}" period="year" id="product_period_year">{t}1Yr{/t}</button> <button class="table_option {if $product_period=='yeartoday'}selected{/if}" period="yeartoday" id="product_period_yeartoday">{t}YTD{/t}</button> <button class="table_option {if $product_period=='six_month'}selected{/if}" period="six_month" id="product_period_six_month">{t}6M{/t}</button> <button class="table_option {if $product_period=='quarter'}selected{/if}" period="quarter" id="product_period_quarter">{t}1Qtr{/t}</button> <button class="table_option {if $product_period=='month'}selected{/if}" period="month" id="product_period_month">{t}1M{/t}</button> <button class="table_option {if $product_period=='ten_day'}selected{/if}" period="ten_day" id="product_period_ten_day">{t}10D{/t}</button> <button class="table_option {if $product_period=='week'}selected{/if}" period="week" id="product_period_week">{t}1W{/t}</button> 
				</div>
				<div id="product_avg_options" class="buttons small left cluster" style="display:{if $product_view!='sales' }none{else}block{/if};">
					<button class="table_option {if $product_avg=='totals'}selected{/if}" avg="totals" id="product_avg_totals">{t}Totals{/t}</button> <button class="table_option {if $product_avg=='month'}selected{/if}" avg="month" id="product_avg_month">{t}M AVG{/t}</button> <button class="table_option {if $product_avg=='week'}selected{/if}" avg="week" id="product_avg_week">{t}W AVG{/t}</button> <button class="table_option {if $product_avg=='month_eff'}selected{/if}" style="display:none" avg="month_eff" id="product_avg_month_eff">{t}M EAVG{/t}</button> <button class="table_option {if $product_avg=='week_eff'}selected{/if}" style="display:none" avg="week_eff" id="product_avg_week_eff">{t}W EAVG{/t}</button> 
				</div>
				<div class="buttons small cluster group">
				<button  id="change_products_display_mode">{$display_products_mode_label}</button> <button  id="change_products_table_type">{if $products_table_type=='list'}{t}List{/t}{else}{t}Thumbnails{/t}{/if}</button> 
			</div>
				<div style="clear:both">
				</div>
			</div>
			{include file='table_splinter.tpl' table_id=2 filter_name=$filter_name2 filter_value=$filter_value2 } 
			<div id="table2" class="data_table_container dtable btable" style="font-size:85%">
			</div>
		</div>
		<div id="block_deals" style="{if $block_view!='deals'}display:none;{/if}clear:both;margin:20px 0 40px 0">
		</div>
		<div id="block_pages" style="{if $block_view!='pages'}display:none;{/if}clear:both;margin:20px 0 40px 0">
			<span class="clean_table_title">{t}Pages{/t}</span> 
			<div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999;margin-bottom:15px">
			</div>
			{include file='table_splinter.tpl' table_id=4 filter_name=$filter_name4 filter_value=$filter_value4 no_filter=1 } 
			<div id="table4" class="data_table_container dtable btable" style="font-size:85%">
			</div>
		</div>
	</div>
</div>
<div id="change_families_display_menu" style="padding:10px 20px 0px 10px">
	<table class="edit" border="0" style="width:200px">
		<tr class="title">
			<td>{t}Display Mode Options{/t}:</td>
		</tr>
		<tr style="height:5px">
			<td></td>
		</tr>
		{foreach from=$families_mode_options_menu item=menu } 
		<tr>
			<td> 
			<div class="buttons">
				<button style="float:none;margin:0px auto;min-width:120px" onclick="change_display_mode('families','{$menu.mode}','{$menu.label}',0)"> {$menu.label}</button> 
			</div>
			</td>
		</tr>
		{/foreach} 
	</table>
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
<div id="change_departments_display_menu" style="padding:10px 20px 0px 10px">
	<table class="edit" border="0" style="width:200px">
		<tr class="title">
			<td>{t}Display Mode Options{/t}:</td>
		</tr>
		<tr style="height:5px">
			<td></td>
		</tr>
		{foreach from=$departments_mode_options_menu item=menu } 
		<tr>
			<td> 
			<div class="buttons">
				<button style="float:none;margin:0px auto;min-width:120px" onclick="change_display_mode('departments','{$menu.mode}','{$menu.label}',0)"> {$menu.label}</button> 
			</div>
			</td>
		</tr>
		{/foreach} 
	</table>
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
<div id="rppmenu4" class="yuimenu">
	<div class="bd">
		<ul class="first-of-type">
			<li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Rows per Page{/t}:</li>
			{foreach from=$paginator_menu4 item=menu } 
			<li class="yuimenuitem"><a class="yuimenuitemlabel" onclick="change_rpp_with_totals({$menu},4)"> {$menu}</a></li>
			{/foreach} 
		</ul>
	</div>
</div>
<div id="filtermenu4" class="yuimenu">
	<div class="bd">
		<ul class="first-of-type">
			<li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Filter options{/t}:</li>
			{foreach from=$filter_menu4 item=menu } 
			<li class="yuimenuitem"><a class="yuimenuitemlabel" onclick="change_filter('{$menu.db_key}','{$menu.label}',4)"> {$menu.menu_label}</a></li>
			{/foreach} 
		</ul>
	</div>
</div>
{include file='assert_elements_splinter.tpl'} {include file='notes_splinter.tpl'} 
{include file='footer.tpl'} 