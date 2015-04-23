{include file='header.tpl'} 
<div id="bd" class="no_padding">
	<div style="padding:0 20px">
		<input type="hidden" id="family_key" value="{$family->id}" />
		<input type="hidden" id="link_extra_argument" value="&id={$family->id}" />
		<input type="hidden" id="from" value="{$from}" />
		<input type="hidden" id="to" value="{$to}" />
		<input type="hidden" id="valid_from" value="{$family->get('Product Family Valid From')}" />
		<input type="hidden" id="valid_to" value="{$family->get_valid_to()}" />		
		<input type="hidden" id="history_table_id" value="5"> 
		<input type="hidden" id="products_table_id" value="0"> 
		<input type="hidden" id="subject" value="family"> 
		<input type="hidden" id="subject_key" value="{$family->id}"> 
		<input type="hidden" id="calendar_id" value="{$calendar_id}" />
		<input type="hidden" id="sales_max_sample_domain" value="{$sales_max_sample_domain}"> 

		{include file='assets_navigation.tpl'} 
		<div class="branch">
			<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a>&rarr; {if $user->get_number_stores()>1}<a href="stores.php">{t}Stores{/t}</a> &rarr; {/if}<a href="store.php?id={$store->id}">{$store->get('Store Code')}</a> &rarr; <a href="department.php?id={$department->id}">{$department->get('Product Department Name')}</a> &rarr; {$family->get('Product Family Code')}</span> 
		</div>
		<div class="top_page_menu">
			<div class="buttons" style="float:right">
				{if isset($family_next)}<img class="next" onmouseover="this.src='art/{if $family_next.to_end}prev_to_end.png{else}next_button.gif{/if}'" onmouseout="this.src='art/{if $family_next.to_end}prev_to_end.png{else}next_button.png{/if}'" title="{$family_next.title}" onclick="window.location='{$family_next.link}'" src="art/{if $family_next.to_end}prev_to_end.png{else}next_button.png{/if}" alt="{t}Next{/t}" />{/if}
				{if $modify}<button onclick="edit_family()"><img src="art/icons/vcard_edit.png" alt=""> {t}Edit Family{/t}</button>{/if} 
			</div>
			<div class="buttons" style="float:left">
				{if isset($family_prev)}<img class="previous" onmouseover="this.src='art/{if $family_prev.to_end}prev_to_end.png{else}previous_button.gif{/if}'" onmouseout="this.src='art/{if $family_prev.to_end}start_bookmark.png{else}previous_button.png{/if}'" title="{$family_prev.title}" onclick="window.location='{$family_prev.link}'" src="art/{if $family_prev.to_end}start_bookmark.png{else}previous_button.png{/if}" alt="{t}Previous{/t}" />{/if} <span class="main_title"><img src="art/icons/family.png" style="height:18px;position:relative;bottom:2px" /> {$family->get('Product Family Name')} <span class="id">({$family->get('Product Family Code')})</span></span> 
			</div>
			<div style="clear:both">
			</div>
		</div>
	</div>
	<div id="overview_top" style="padding:10px 20px">
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
		<div id="photo_container" style="margin-top:0px;float:left">
			<div style="border:1px solid #ddd;padding-stop:0;width:220px;xheight:230px;text-align:center;margin:0 10px 0 0px">
				<div id="imagediv" style="border:1px solid #ddd;width:190px;padding:5px 5px;xborder:none;cursor:pointer;xbackground:red;margin: 10px 0 10px 9px;vertical-align:middle">
					<img id="main_image" src="{$family->get('Product Family Main Image')}" style="vertical-align:middle;display:block;margin:0px auto;width:190px" valign="center" border="1" id="image" alt="{t}Image{/t}" /> 
				</div>
			</div>
			<div style="width:160px;margin:auto;padding-top:5px;{if $family->get_number_of_images()<=1}display:none{/if}">
				<ul class="gallery clearfix">
					{foreach from=$family->get_images_slidesshow() item=image name=foo} {if $image.is_principal==0} 
					<li><a href="{$image.normal_url}" rel="prettyPhoto[gallery1]"><img style="float:left;border:1px solid#ccc;padding:2px;margin:2px;cursor:pointer" src="{$image.thumbnail_url}" alt="{$image.name}" /></a> {/if} {/foreach} 
					</ul>
				</div>
			</div>
			<div style="width:350px;float:left">
				<table class="show_info_product">
					<tr>
						<td style="width:100px">{t}Record Type{/t}:</td>
						<td>{$family->get('Product Family Record Type')}</td>
					</tr>
					<tr>
						<td>{t}Similar{/t}:</td>
						<td style="font-size:90%">{$family->get('Similar Families')}</td>
					</tr>
					<tr>
						<td>{t}Also bought{/t}:</td>
						<td style="font-size:90%">{$family->get('Sales Correlated Families')}</td>
					</tr>
					<tr style="display:none">
						<td>{t}Categories{/t}:</td>
						<td>{$family->get('Categories')}</td>
					</tr>
					<tr>
						<td>{t}Sold in{/t}: <img src="art/icons/layout_bw.png" style="position:relative;bottom:2px;right:2px"></td>
						<td>{$family->get('Sold in Pages')}</td>
					</tr>
				</table>
				<table border=0 >
					<tr>
						<td colspan=2 class="aright">{$family->get_formated_discounts()}</td>
					</tr>
				</table>
				<div id="offers_information">
				</div>
			</div>
			<div style="clear:both;">
			</div>
		</div>
		
	<ul class="tabs" id="chooser_ul" style="clear:both;margin-top:10px">
			<li> <span class="item {if $block_view=='details'}selected{/if}" id="details"> <span> {t}Details{/t}</span></span></li>
			<li> <span class="item {if $block_view=='notes'}selected{/if}" id="notes"> <span> {t}History/Notes{/t}</span></span></li>
			<li> <span class="item {if $block_view=='sales'}selected{/if}" id="sales"> <span> {t}Sales{/t}</span></span></li>
			<li style="display:none"> <span class="item {if $block_view=='categories'}selected{/if}" id="categories"> <span> {t}Categories{/t}</span></span></li>
			<li> <span class="item {if $block_view=='products'}selected{/if}" id="products"><span> {t}Products{/t}</span></span></li>
			<li> <span class="item {if $block_view=='deals'}selected{/if}" id="deals"> <span> {t}Offers{/t}</span></span></li>
			<li> <span class="item {if $block_view=='web'}selected{/if}" id="web"> <span> {t}Web Pages{/t}</span></span></li>
		</ul>
		<div class="tabs_base">
		</div>
	
	<div style="padding:0px 20px 10px 20px">
		<div id="block_sales" style="{if $block_view!='sales'}display:none;{/if}clear:both;padding-top:0;margin:0px 0 40px 0;">
				<div id="calendar_container" style="padding:0 20px;padding-bottom:0px;margin-top:0px;border:1px solid white">
					<div id="period_label_container" style="{if $period==''}display:none{/if}">
						<img src="art/icons/clock_16.png"> <span id="period_label">{$period_label}</span> 
					</div>
					{include file='calendar_splinter.tpl' calendar_id='sales' calendar_link='part.php'} 
					<div style="clear:both">
					</div>
				</div>
				<div style="margin-top:20px;width:900px;">
					<div style="margin-top:0px">
						<table class="show_info_product" style="float:left;width:250px">
							<tbody>
								<tr>
									<td>{t}Sales{/t}:</td>
									<td class=" aright" id="sales_amount"><img style="height:14px" src="art/loading.gif" /></td>
								</tr>
								<tr>
									<td>{t}Profit{/t}:</td>
									<td class=" aright" id="profits"><img style="height:14px" src="art/loading.gif" /></td>
								</tr>
								<tr>
									<td>{t}Outers{/t}:</td>
									<td class="aright" id="outers"><img style="height:14px" src="art/loading.gif" /></td>
								</tr>
							</tbody>
						</table>
						<table class="show_info_product" style="float:left;width:250px;margin-left:20px">
							<tbody>
								<tr>
									<td>{t}Invoices{/t}:</td>
									<td class="aright" id="invoices"><img style="height:14px" src="art/loading.gif" /></td>
								</tr>
								<tr>
									<td>{t}Customers{/t}:</td>
									<td class=" aright" id="customers"><img style="height:14px" src="art/loading.gif" /></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<div id="sales_sub_blocks" style="clear:both">
					<ul class="tabs" id="chooser_ul" style="margin-top:25px">
						<li> <span class="item {if $sales_sub_block_tipo=='plot_family_sales'}selected{/if}" onclick="change_sales_sub_block(this)" id="plot_family_sales" tipo="store"> <span>{t}Sales Chart{/t}</span> </span> </li>
						<li> <span class="item {if $sales_sub_block_tipo=='family_sales_timeseries'}selected{/if}" onclick="change_sales_sub_block(this)" id="family_sales_timeseries" tipo="store"> <span>{t}Family Sales History{/t}</span> </span> </li>
						<li> <span class="item {if $sales_sub_block_tipo=='family_product_sales'}selected{/if}" onclick="change_sales_sub_block(this)" id="family_product_sales" tipo="list" forecast="" interval=""> <span>{t}Product's Sales{/t}</span> </span> </li>
						<li> <span class="item {if $sales_sub_block_tipo=='family_sales_calendar'}selected{/if}" onclick="change_sales_sub_block(this)" id="family_sales_calendar" tipo="store"> <span>{t}Family Sales Calendar{/t}</span> </span> </li>

					</ul>
					<div id="sub_block_plot_family_sales" style="min-height:400px;clear:both;border:1px solid #ccc;{if $sales_sub_block_tipo!='plot_family_sales'}display:none{/if}">
						{if $family->get('Product Family Total Acc Invoiced Gross Amount')!=0} <script type="text/javascript" src="external_libs/amstock/amstock/swfobject.js"></script> <script type="text/javascript">
				// <![CDATA[
				var so = new SWFObject("external_libs/amstock/amstock/amstock.swf", "amstock", "905", "500", "8", "#FFFFFF");
				so.addVariable("path", "");
				so.addVariable("settings_file", encodeURIComponent("conf/plot_asset_sales.xml.php?tipo=family_sales&family_key={$family->id}"));
				so.addVariable("preloader_color", "#999999");
				so.write("sub_block_plot_family_sales");
				// ]]>
			</script> {/if} 
		</div>
		<div id="sub_block_family_product_sales" style="min-height:400px;clear:both;border:1px solid #ccc;padding:20px;{if $sales_sub_block_tipo!='family_product_sales'}display:none{/if}">
			<div class="data_table" style="margin-top:0px;clear:both">
				<span id="table_title" class="clean_table_title">{t}Products Sold{/t} <img style="display:none" id="export_csv1" class="export_data_link" label="{t}Export (CSV/XML){/t}" alt="{t}Export (CSV/XML){/t}" src="art/icons/export_csv.gif"></span> 
				<div class="table_top_bar space">
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
			<div class="table_top_bar">
			</div>
			<div class="clusters">
				<div class="buttons small cluster group">
					<button id="change_sales_history_timeline_group"> &#x21b6 {$sales_history_timeline_group_label}</button> 
				</div>
				<div style="clear:both;margin-bottom:5px">
				</div>
			</div>
			{include file='table_splinter.tpl' table_id=2 filter_name=$filter_name2 filter_value=$filter_value2 no_filter=1 } 
			<div id="table2" style="font-size:85%" class="data_table_container dtable btable">
			</div>
		</div>
		<div id="sub_block_family_sales_calendar" style="min-height:400px;clear:both;border:1px solid #ccc;padding:20px 0px;{if $sales_sub_block_tipo!='family_sales_calendar'}display:none{/if}">
			<div id="d3_calendar_asset_sales" class="d3_calendar" >

			</div>
			
		</div>

		<div style="clear:both">
		</div>
	</div>
</div>
		
		<div id="block_details" style="{if $block_view!='details'}display:none;{/if}clear:both;margin:10px 0 40px 0">
			<span class="clean_table_title">{t}Description{/t}</span> 
			<div class="table_top_bar space">
			</div>
			<div>
				{$family->get('Product Family Description')} 
			</div>
		</div>
		
		<div id="block_notes" style="{if $block_view!='notes'}display:none;{/if}clear:both;margin:10px 0 40px 0">
	<div style="clear:both;">
		<span class="clean_table_title">{t}History/Notes{/t}</span> 
		<div class="elements_chooser">
			<span style="float:right;margin-left:20px;" class=" table_type transaction_type state_details {if $elements_family_history.Changes}selected{/if} label_family_history_changes" id="elements_family_history_changes" table_type="elements_changes">{t}Changes History{/t} (<span id="elements_changes_number">{$elements_family_history_number.Changes}</span>)</span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_family_history.Notes}selected{/if} label_family_history_notes" id="elements_family_history_notes" table_type="elements_notes">{t}Staff Notes{/t} (<span id="elements_notes_number">{$elements_family_history_number.Notes}</span>)</span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_family_history.Attachments}selected{/if} label_family_history_attachments" id="elements_family_history_attachments" table_type="elements_attachments">{t}Attachments{/t} (<span id="elements_notes_number">{$elements_family_history_number.Attachments}</span>)</span> 
		</div>
		<div class="table_top_bar space">
		</div>
		{include file='table_splinter.tpl' table_id=5 filter_name=$filter_name5 filter_value=$filter_value5 } 
		<div id="table5" class="data_table_container dtable btable">
		</div>
	</div>
</div>
		<div id="block_web" style="{if $block_view!='web'}display:none;{/if}clear:both;margin:10px 0 40px 0">
	<span class="clean_table_title">{t}Pages{/t}</span> 
	<div class="table_top_bar space">
	</div>
	{include file='table_splinter.tpl' table_id=4 filter_name=$filter_name4 filter_value=$filter_value4 no_filter=1 } 
	<div id="table4" class="data_table_container dtable btable" style="font-size:85%">
	</div>
</div>
		<div id="block_products" style="{if $block_view!='products'}display:none;{/if}clear:both;margin:10px 0 40px 0">
	<div class="data_table" style="margin-top:10px;clear:both">
		<span id="table_title" class="clean_table_title">{t}Products{/t} 
	            <img id="export_products" class="export_data_link" label="{t}Export (CSV/XML){/t}" alt="{t}Export (CSV/XML){/t}" src="art/icons/export_csv.gif"> 
		</span> 
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
	</div>
	<div class="table_top_bar">
	</div>
	<input type="hidden" id="products_view" value="{$product_view}"> 
	<div class="clusters" id="table_view_menu0">
		<div id="table_view_menu_tabs0" style="{if $products_table_type=='thumbnails'}display:none{/if}">
			<div class="buttons small left cluster">
				<button class="table_option {if $product_view=='general'}selected{/if}" id="product_general">{t}Overview{/t}</button> 
				<button class="table_option {if $product_view=='timeline'}selected{/if}" id="product_timeline">{t}Timeline{/t}</button>

				<button class="table_option {if $product_view=='stock'}selected{/if}" id="product_stock" style="{if !$view_stock}display:none{/if}">{t}Stock{/t}</button> 
				<button class="table_option {if $product_view=='sales'}selected{/if}" id="product_sales" style="{if !$view_sales}display:none{/if}">{t}Sales{/t}</button>
				<button class="table_option {if $product_view=='parts'}selected{/if}" id="product_parts" style="{if !$view_sales}display:none{/if}">{t}Parts{/t}</button> 
				<button class="table_option {if $product_view=='properties'}selected{/if}" id="product_properties">{t}Properties{/t}</button> 
				<button class="table_option {if $product_view=='reorder'}selected{/if}" id="product_reorder">{t}Reorder{/t}</button> 

				<button class="table_option {if $product_view=='cats'}selected{/if}" id="product_cats" style="display:none;{if !$view_sales}display:none{/if}">{t}Groups{/t}</button> 
			</div>
			<div id="product_period_options" class="buttons small left cluster" style="display:{if !($product_view=='sales' or $product_view=='reorder') }none{else}block{/if};">
				<button class="table_option {if $product_period=='all'}selected{/if}" period="all" id="product_period_all">{t}All{/t}</button> <button class="table_option {if $product_period=='three_year'}selected{/if}" period="three_year" id="product_period_three_year">{t}3Y{/t}</button> <button class="table_option {if $product_period=='year'}selected{/if}" period="year" id="product_period_year">{t}1Yr{/t}</button> <button class="table_option {if $product_period=='yeartoday'}selected{/if}" period="yeartoday" id="product_period_yeartoday">{t}YTD{/t}</button> <button class="table_option {if $product_period=='six_month'}selected{/if}" period="six_month" id="product_period_six_month">{t}6M{/t}</button> <button class="table_option {if $product_period=='quarter'}selected{/if}" period="quarter" id="product_period_quarter">{t}1Qtr{/t}</button> <button class="table_option {if $product_period=='month'}selected{/if}" period="month" id="product_period_month">{t}1M{/t}</button> <button class="table_option {if $product_period=='ten_day'}selected{/if}" period="ten_day" id="product_period_ten_day">{t}10D{/t}</button> <button class="table_option {if $product_period=='week'}selected{/if}" period="week" id="product_period_week">{t}1W{/t}</button> 
			</div>
			<div id="product_avg_options" class="buttons small left cluster" style="display:{if $product_view!='sales'  }none{else}block{/if};">
				<button title="{t}Totals{/t}" class="table_option {if $product_avg=='totals'}selected{/if}" avg="totals" id="product_avg_totals">&Sigma;<sub>x</sub></button> 
				<button title="{t}Monthy average{/t}" class="table_option {if $product_avg=='month'}selected{/if}" avg="month" id="product_avg_month">x&#772;<sub>m</sub></button> 
				<button title="{t}Weekly average{/t}" class="table_option {if  $product_avg=='week'}selected{/if}" avg="week" id="product_avg_week">x&#772;<sub>w</sub></button> 
				<button title="{t}Monthy effective average{/t}"  class="table_option {if  $product_avg=='month_eff'}selected{/if}"  avg="month_eff" id="product_avg_month_eff">x&#772;<sub>em</sub></button> 
				<button title="{t}Weekly effective average{/t}" class="table_option {if $product_avg=='week_eff'}selected{/if}"  avg="week_eff" id="product_avg_week_eff">x&#772;<sub>ew</sub></button> 
			</div>
			<div id="product_reorder_avg_reorder_options" class="buttons small left cluster" style="display:{if $product_view!='reorder' }none{else}block{/if};">
				<button title="{t}Totals{/t}" class="table_option {if $product_avg_reorder=='totals'}selected{/if}" avg="totals" id="product_reorder_avg_totals">&Sigma;<sub>x</sub></button> 
				<button title="{t}Monthy average{/t}" class="table_option {if $product_avg_reorder=='month'}selected{/if}" avg="month" id="product_reorder_avg_month">x&#772;<sub>m</sub></button> 
				<button title="{t}Weekly average{/t}" class="table_option {if $product_avg_reorder=='week'}selected{/if}" avg="week" id="product_reorder_avg_week">x&#772;<sub>w</sub></button> 
			</div>
			
			
		</div>
		<div class="buttons small cluster group">
			<button style="{if $products_table_type=='thumbnails'  or  $product_view!='sales' }display:none{/if}  " id="change_products_display_mode">&#x21b6 {$display_products_mode_label}</button> <button id="change_products_table_type">&#x21b6 {$products_table_type_label}</button> 
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
	
	<div id="block_deals" style="{if $block_view!='deals'}display:none;{/if}clear:both;margin:0px 0 40px 0">
	<div style="padding:0px 20px">
		<span class="clean_table_title">Offers</span> 
		<div class="buttons small left">
			<button id="new_deal" onclick="new_deal()" class="positive"><img src="art/icons/add.png"> {t}New{/t}</button> 
		</div>
		<div class="table_top_bar space">
		</div>
		{include file='table_splinter.tpl' table_id=10 filter_name=$filter_name10 filter_value=$filter_value10 } 
		<div id="table10" class="data_table_container dtable btable" style="font-size:85%">
		</div>
	</div>	

</div>
	<div id="block_categories" style="{if $block_view!='categories'}display:none;{/if}clear:both;margin:10px 0 40px 0">
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
{include file='assert_elements_splinter.tpl'}
{include file='export_splinter.tpl' id='products' export_fields=$export_products_fields map=$export_products_map is_map_default={$export_products_map_is_default}} 

{include file='notes_splinter.tpl'} {include file='footer.tpl'} 