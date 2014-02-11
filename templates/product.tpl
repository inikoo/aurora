{include file='header.tpl'} 
<input id="product_pid" value="{$product->pid}" type="hidden" />
<input type="hidden" id="link_extra_argument" value="&pid={$product->pid}" />
<input type="hidden" id="from" value="{$from}" />
<input type="hidden" id="to" value="{$to}" />
<input type="hidden" id="history_table_id" value="2"> 
<input type="hidden" id="subject" value="product"> 
<input type="hidden" id="subject_key" value="{$product->pid}"> 
<input type="hidden" id="barcode_data" value="{$product->get_barcode_data()}"> 
<input type="hidden" id="barcode_type" value="{$product->get('Product Barcode Type')}"> 
		<input type="hidden" id="calendar_id" value="{$calendar_id}" />
<div id="bd" style="padding:0px;{if $product->get('Product Record Type')=='Discontinued'}background-position:300px 30px;background-image:url('art/stamp.discontinued.en.png');background-repeat:no-repeat;{/if}">
	<div style="padding:0 20px">
		{include file='assets_navigation.tpl'} 
		<div class="branch">
			<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a>&rarr; {if $user->get_number_stores()>1}<a href="stores.php">{t}Stores{/t}</a> &rarr; {/if}<a href="store.php?id={$store->id}">{$store->get('Store Code')}</a> &rarr; <a href="department.php?id={$product->get('Product Main Department Key')}">{$product->get('Product Main Department Code')}</a> &rarr; <a href="family.php?id={$product->get('Product Family Key')}">{$product->get('Product Family Code')}</a> &rarr; {$product->get('Product Code')}</span> 
		</div>
		<div class="top_page_menu">
			<div class="buttons" style="float:right">
				{if isset($next)}<img class="next" onmouseover="this.src='art/{if $next.to_end}prev_to_end.png{else}next_button.gif{/if}'" onmouseout="this.src='art/{if $next.to_end}prev_to_end.png{else}next_button.png{/if}'" title="{$next.title}" onclick="window.location='{$next.link}'" src="art/{if $next.to_end}prev_to_end.png{else}next_button.png{/if}" alt="{t}Next{/t}" />{/if} <button onclick="window.location='edit_product.php?pid={$product->pid}'"><img src="art/icons/vcard_edit.png" alt=""> {t}Edit Product{/t}</button> 
			</div>
			<div class="buttons" style="float:left">
				{if isset($prev)}<img class="previous" onmouseover="this.src='art/{if $prev.to_end}prev_to_end.png{else}previous_button.gif{/if}'" onmouseout="this.src='art/{if $prev.to_end}start_bookmark.png{else}previous_button.png{/if}'" title="{$prev.title}" onclick="window.location='{$prev.link}'" src="art/{if $prev.to_end}start_bookmark.png{else}previous_button.png{/if}" alt="{t}Previous{/t}" />{/if} <span class="main_title"><span class="id">{$product->get('Product Code')}</span> (<i>{$product->get('Product ID')})</i>, {$product->get('Product Name')} </span> {if $product->get('Product Record Type')=='Historic'} {t}Historic Product{/t}{/if} 
			</div>
			<div style="clear:both">
			</div>
		</div>
		<div style="clear:both;margin-bottom:10px">
		</div>
		<div id="block_info" style="width:940px;position:relative">
			<div style="float:right">
				<div class="buttons" style="float:right;">
					<button id="sticky_note_button"><img src="art/icons/note.png" alt=""> {t}Note{/t}</button> <button id="note"><img src="art/icons/add.png" alt=""> {t}History Note{/t}</button> <button id="attach"><img src="art/icons/add.png" alt=""> {t}Attachment{/t}</button> 
				</div>
				<div id="sticky_note_div" class="sticky_note" style="clear:both;margin-top:10px;margin-right:5px">
					<img id="sticky_note_bis" style="float:right;cursor:pointer" src="art/icons/edit.gif"> 
					<div id="sticky_note_content" style="padding:10px 15px 10px 15px;">
						{$sticky_note} 
					</div>
				</div>
				<div style="width:300px;clear:both;margin-top:20px;padding-top:20px;margin-left:20px;font-size:90%">
					<table class="show_info_product">
						<tr>
							<td>{t}Outer Weight{/t}:</td>
							<td class="aright">{$product->get('Product XHTML Package Weight')}</td>
						</tr>
						<tr>
							<td>{t}Unit Weight{/t}:</td>
							<td class="aright">{$product->get('Product XHTML Unit Weight')}</td>
						</tr>
						<tr>
							<td>{t}Outer Dimensions{/t}:</td>
							<td class="aright">{$product->get('Product XHTML Package Dimensions')}</td>
						</tr>
						<tr>
							<td>{t}Unit Dimensions{/t}:</td>
							<td class="aright">{$product->get('Product XHTML Unit Dimensions')}</td>
						</tr>
					</table>
				</div>
			</div>
			<div id="photo_container" style="margin-top:0px;float:left">
				<div id="barcode" style="margin:0px auto;margin-bottom:4px">
				</div>
				<div style="border:1px solid #ddd;padding-stop:0;width:220px;xheight:230px;text-align:center;margin:0 10px 0 0px">
					<div id="imagediv" style="border:1px solid #ddd;width:190px;padding:5px 5px;xborder:none;cursor:pointer;xbackground:red;margin: 10px 0 10px 9px;vertical-align:middle">
						<img id="main_image" src="{$product->get('Product Main Image')}" style="vertical-align:middle;display:block;margin:0px auto;width:190px" valign="center" border="1" id="image" alt="{t}Image{/t}" /> 
					</div>
				</div>
				<div style="width:160px;margin:auto;padding-top:5px;{if $product->get_number_of_images()<=1}display:none{/if}">
					<ul class="gallery clearfix">
						{foreach from=$product->get_images_slidesshow() item=image name=foo} {if $image.is_principal==0} 
						<li><a href="{$image.normal_url}" rel="prettyPhoto[gallery1]"><img style="float:left;border:1px solid#ccc;padding:2px;margin:2px;cursor:pointer" src="{$image.thumbnail_url}" alt="{$image.name}" /></a> {/if} {/foreach} 
					</ul>
				</div>
			</div>
			<div style="float:left;margin-left:20px;font-size:90%;width:350px;">
				{if $product->get('Product Main Type')=='Discontinued'} 
				<table class="discontinued" style="margin:0;padding:5px 10px;width:100%;">
					<tr>
						<td style="font-weight:800;font-size:160%;text-align:center">{t}Discontinued{/t}</td>
					</tr>
				</table>
				{/if} {if $product->get('Product Main Type')=='Private'} 
				<table style="margin:0;padding:5px 10px;border-top:1px solid #c7cbe0;width:100%;background:#deceb2">
					<tr>
						<td style="font-weight:800;font-size:160%;text-align:center">{t}Not for Public Sale{/t}</td>
					</tr>
				</table>
				{/if} {if $product->get('Product Main Type')=='NoSale'} 
				<table style="margin:0;padding:5px 10px;border-top:1px solid #c7cbe0;width:100%;background:#deceb2">
					<tr>
						<td style="font-weight:800;font-size:160%;text-align:center">{t}Not for Sale{/t}</td>
					</tr>
				</table>
				{/if} {if $product->get('Product Main Type')=='Historic'} 
				<table style="margin:0;padding:5px 10px;border-top:1px solid #c7cbe0;width:100%;background:#deceb2">
					<tr>
						<td style="font-weight:800;font-size:160%;text-align:center">{t}Historic{/t}</td>
					</tr>
				</table>
				{/if} {if $product->get('Product Main Type')=='NoSale'} 
				<table style="margin:0;padding:5px 10px;border-top:1px solid #c7cbe0;width:100%;background:#deceb2">
					<tr>
						<td style="font-weight:800;font-size:160%;text-align:center">{t}Not for Sale{/t}</td>
					</tr>
				</table>
				{/if} 
				<table class="show_info_product" style="{if $product->get('Product Record Type')=='Historic'}display:none{/if}">
					<tr>
						<td>{t}Available{/t}: 
						<td class="stock aright" id="stock">{$product->get('Product Availability')}</td>
					</tr>
					
					
					<tr style="{if $product->get('Product Next Supplier Shipment')==''}display:none{/if}">
					<td>{t}Next Shipment{/t}:</td>
					<td class="aright">{$product->get('Next Supplier Shipment') }</td>
					</tr>
					
					
					{if $product->get('Product XHTML Next Supplier Shipment') } 
					<tr>
						<td rowspan="2" style="font-size:90%;text-align:right">{$product->get('Product XHTML Next Supplier Shipment')}</td>
					</tr>
					{/if} {if $product->get('Product Availability Type')=='Discontinued' } 
					<tr>
						<td rowspan="2" style="font-size:75%">{t}Discontinued{/t}</td>
					</tr>
					{/if} 
				</table>
				<table class="show_info_product" border="0">
					<tr style="{if $product->get('Product Record Type')=='Normal'}display:none{/if}">
						<td colspan="3">{$product->get('Product Record Type')}</td>
					</tr>
					<tr style="{if $product->get('Product Sales Type')=='Public Sale'}display:none{/if}">
						<td>{t}Sale Type{/t}:</td>
						<td colspan=2 class="aright">{$product->get_formated_sales_type() }</td>
					</tr>
					<tr>
						<td>{t}Web Status{/t}:</td>
						<td style="text-align:right;width:50px" id="product_web_state_{$product->get('Product ID')}"> {if $product->get('Product Number Web Pages')==0} <img src="art/icons/world_light_bw.png" title="{t}Not in website{/t}" /> {else if $product->get('Product Web State')=='For Sale'}<img src="art/icons/world.png" /> {else if $product->get('Product Web State')=='Out of Stock'}<img src="art/icons/no_stock.jpg" /> {else}<img src="art/icons/sold_out.gif" />{/if} </td>
						<td style="text-align:right;width:100px"> <span style="cursor:pointer" id="product_web_configuration_{$product->get('Product ID')}" onclick="change_web_configuration(this,{$product->get('Product ID')})">{if $product->get('Product Web Configuration')=='Online Auto'}{t}Automatic{/t}{elseif $product->get('Product Web Configuration')=='Offline'}<img src="art/icons/police_hat.jpg" style="height:16px;vertical-align:top" /> {t}Offline{/t} {elseif $product->get('Product Web Configuration')=='Online Force Out of Stock'}<img src="art/icons/police_hat.jpg" style="height:16px;vertical-align:top" /> {t}Out of stock{/t} {elseif $product->get('Product Web Configuration')=='Online Force For Sale'}<img src="art/icons/police_hat.jpg" style="height:16px;vertical-align:top" /> {t}Online{/t} {/if} </span> </td>
					</tr>
				</table>
				<table border="0" class="show_info_product" style="{if $product->get('Product Record Type')=='Historic'}display:none{/if};float:right;width:100%">
					<tr>
						<td>{t}Parts{/t}:</td>
						<td class="aright">{$product->get('Product XHTML Parts')}</td>
					</tr>
					<tr>
						<td>{t}Locations{/t}:</td>
						<td style="text-align:right"> 
						<table style="float:right;padding:0px;margin:0px" border="0">
							{foreach from=$product->get_part_locations(true) item=part_location name=foo } 
							<tr>
								<td style="{if $number_parts<=1}visibility:hidden{/if};padding-right:10px"><a href="part.php?sku={$part_location.PartSKU}">{$part_location.PartFormatedSKU}</a></td>
								<td class="aright"> <a href="location.php?id={$part_location.LocationKey}"><b>{$part_location.LocationCode}</b></a> ({$part_location.QuantityOnHand})</td>
							</tr>
							{/foreach} 
						</table>
						</td>
					</tr>
				</table>
				<table class="show_info_product">
					<tr>
						<td>{t}Sell Price{/t}:</td>
						<td class="price aright">{$product->get_formated_price()}</td>
					</tr>
					
					<tr>
					<td colspan=2 class="aright">{$product->get_formated_discounts()}</td>
					</tr>
					
					<tr {if $product->get('Product RRP')==''}style="display:none"{/if} > 
						<td>{t}RRP{/t}:</td>
						<td class="aright">{$product->get('RRP Per Unit')} {t}each{/t}</td>
					</tr>
					<tr>
						<td>{t}Sold Since{/t}:</td>
						<td class="aright">{$product->get('For Sale Since Date')} </td>
					</tr>
				</table>
			</div>
			<div style="clear:both">
			</div>
		</div>
	</div>
	<ul class="tabs" id="chooser_ul" style="clear:both;margin-top:15px">
		<li> <span class="item {if $block_view=='details'}selected{/if}" id="details"> <span> {t}Details{/t}</span></span></li>
		<li> <span class="item {if $block_view=='history'}selected{/if}" id="history"> <span> {t}History/Notes{/t}</span></span></li>
		<li> <span class="item {if $block_view=='sales'}selected{/if}" id="sales"> <span> {t}Sales{/t}</span></span></li>
		<li> <span class="item {if $block_view=='customers'}selected{/if}" id="customers" style="{if !$view_customers}display:none{/if}"><span>{t}Customers{/t}</span></span></li>
		<li> <span class="item {if $block_view=='orders'}selected{/if}" id="orders" style="{if !$view_orders}display:none{/if}"> <span> {t}Orders{/t}</span></span></li>
		<li> <span class="item {if $block_view=='web'}selected{/if}" id="web"> <span> {t}Web pages{/t}</span></span></li>
		<li> <span class="item {if $block_view=='timeline'}selected{/if}" id="timeline"> <span> {t}Sister products{/t}</span></span></li>
		<li> <span class="item {if $block_view=='pictures'}selected{/if}" id="pictures"> <span> {t}Pictures{/t}</span></span></li>
	</ul>
	<div style="clear:both;width:100%;border-bottom:1px solid #ccc">
	</div>
	<div style="padding:0 20px;min-height:400px">
		<div id="block_details" style="{if $block_view!='details'}display:none;{/if}clear:both;margin:10px 0 40px 0;padding-top:10px">
			
			<div class="description" style="width:500px;font-size:90%;float:left;margin-right:20px">
				{$product->get('Product Description')}
			</div>
			
			<div style="width:300px;font-size:80%;float:left;">
				<table class="show_info_product">
					<tr>
						<td>{t}Commodity Code{/t}:</td>
						<td>{$product->get('Product Tariff Code')}</td>
					</tr>
					<tr>
						<td>{t}Duty Rate{/t}:</td>
						<td>{$product->get('Product Duty Rate')}</td>
					</tr>
					<tr>
						<td>{t}Categories{/t}:</td>
						<td></td>
					</tr>
					<tr>
						<td>{t}Material{/t}:</td>
						<td></td>
					</tr>
					<tr>
						<td>{t}Ingredients{/t}:</td>
						<td></td>
					</tr>
				</table>
				{if $view_suppliers} 
				<table class="show_info_product">
					<tr>
						<td>{t}Suppliers{/t}:</td>
						<td class="aright">{$product->get('Product XHTML Supplied By')}</td>
					</tr>
				</table>
				{/if} 
			</div>
		</div>
		<div id="block_sales" style="{if $block_view!='sales'}display:none;{/if}clear:both;margin:5px 0 40px 0;">
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
								<td class=" aright" id="number_customers"><img style="height:14px" src="art/loading.gif" /></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<div id="plots" style="clear:both">
				<ul class="tabs" id="chooser_ul" style="margin-top:25px">
					<li> <span class="item {if $sales_sub_block_tipo=='plot_product_sales'}selected{/if}" onclick="change_sales_sub_block(this)" id="plot_product_sales" tipo="store"> <span>{t}Product Sales Graph{/t}</span> </span> </li>
					<li> <span class="item {if $sales_sub_block_tipo=='product_sales_timeseries'}selected{/if}" onclick="change_sales_sub_block(this)" id="product_sales_timeseries" tipo="store"> <span>{t}Product Sales History{/t}</span> </span> </li>
				</ul>
				<div id="sub_block_plot_product_sales" style="clear:both;border:1px solid #ccc;{if $sales_sub_block_tipo!='plot_product_sales'  }display:none{/if};min-height:300px">
					{if $product->get('Product Total Acc Invoiced Amount')!=0} 
					
					<script type="text/javascript" src="external_libs/amstock/amstock/swfobject.js"></script> <script type="text/javascript">
		// <![CDATA[
		var so = new SWFObject("external_libs/amstock/amstock/amstock.swf", "amstock", "905", "500", "8", "#FFFFFF");
		so.addVariable("path", "");
		so.addVariable("settings_file", encodeURIComponent("conf/plot_asset_sales.xml.php?tipo=product_id_sales&product_id={$product->pid}"));
		so.addVariable("preloader_color", "#999999");
		so.write("sub_block_plot_product_sales");
		// ]]>
	</script> 
	
	{/if} 
				</div>
				<div id="sub_block_product_sales_timeseries" style="min-height:400px;clear:both;border:1px solid #ccc;padding:20px;{if $sales_sub_block_tipo!='product_sales_timeseries'}display:none{/if}">
					<span class="clean_table_title">{t}Product Sales History{/t}</span> 
					<div class="table_top_bar">
				</div>
				<div class="clusters">
					<div class="buttons small cluster group">
						<button id="change_sales_history_timeline_group"> &#x21b6 {$sales_history_timeline_group_label}</button> 
					</div>
					<div style="clear:both;margin-bottom:5px">
					</div>
				</div>
					{include file='table_splinter.tpl' table_id=4 filter_name=$filter_name4 filter_value=$filter_value4 no_filter=1 } 
					<div id="table4" style="font-size:85%" class="data_table_container dtable btable">
					</div>
				</div>
				<div style="clear:both">
				</div>
			</div>
		</div>
		<div id="block_timeline" style="{if $block_view!='timeline'}display:none;{/if}clear:both;margin:10px 0 40px 0;padding-top:10px">
			<span id="table_title" class="clean_table_title">{t}Product Code Timeline{/t}</span> 
			<div class="table_top_bar space">
			</div>
			{include file='table_splinter.tpl' table_id=3 filter_name=$filter_name3 filter_value=$filter_value3} 
			<div id="table3" class="data_table_container dtable btable" style="font-size:90%">
			</div>
		</div>
		<div id="block_history" style="{if $block_view!='history'}display:none;{/if}clear:both;margin:10px 0 40px 0;padding-top:10px">
			<span id="table_title" class="clean_table_title">{t}History/Notes{/t}</span> 
				<div class="elements_chooser">

				
					<span style="float:right;margin-left:20px;" class=" table_type transaction_type state_details {if $elements_product_history.Changes}selected{/if} label_product_history_changes" id="elements_product_history_changes" table_type="elements_changes">{t}Changes History{/t} (<span id="elements_changes_number">{$elements_product_history_number.Changes}</span>)</span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_product_history.Notes}selected{/if} label_product_history_notes" id="elements_product_history_notes" table_type="elements_notes">{t}Staff Notes{/t} (<span id="elements_notes_number">{$elements_product_history_number.Notes}</span>)</span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_product_history.Attachments}selected{/if} label_product_history_attachments" id="elements_product_history_attachments" table_type="elements_attachments">{t}Attachments{/t} (<span id="elements_notes_number">{$elements_product_history_number.Attachments}</span>)</span> 
				</div>
			
			<div class="table_top_bar space">
			</div>
			{include file='table_splinter.tpl' table_id=2 filter_name=$filter_name2 filter_value=$filter_value2} 
			<div id="table2" class="data_table_container dtable btable">
			</div>
		</div>
		<div id="block_web" style="{if $block_view!='web'}display:none;{/if}clear:both;margin:10px 0 40px 0;padding-top:10px">
			<span class="clean_table_title">{t}Pages{/t}</span> 
			<div class="table_top_bar space">
			</div>
			{include file='table_splinter.tpl' table_id=5 filter_name=$filter_name5 filter_value=$filter_value5 no_filter=1 } 
			<div id="table5" class="data_table_container dtable btable" style="font-size:85%">
			</div>
		</div>
		<div id="block_orders" class="data_table" style="{if $block_view!='orders'}display:none;{/if}clear:both;margin:10px 0 40px 0;padding-top:10px">
			{if $view_orders} <span id="table_title" class="clean_table_title">{t}Orders with this Product{/t}</span> 
			<div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999;margin-bottom:15px">
			</div>
			{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0} 
			<div id="table0" class="data_table_container dtable btable">
			</div>
			{/if} 
		</div>
		<div id="block_customers" class="data_table" style="{if $block_view!='customers'}display:none;{/if}clear:both;margin:10px 0 40px 0;;padding-top:10px">
			<span id="table_title" class="clean_table_title">{t}Customers who order this Product{/t}</span> 
			<div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999;margin-bottom:15px">
			</div>
			{include file='table_splinter.tpl' table_id=1 filter_name=$filter_name1 filter_value=$filter_value1} 
			<div id="table1" class="data_table_container dtable btable">
			</div>
		</div>
		<div id="block_pictures" class="data_table" style="{if $block_view!='pictures'}display:none;{/if}clear:both;margin:10px 0 40px 0;;padding-top:10px">
			{include file='splinter_images.tpl' parent=$product} 
		</div>
	</div>
	<div style="clear:both">
		&nbsp;
	</div>
</div>
<div id="web_status_menu" class="yuimenu">
	<div class="bd">
		<ul class="first-of-type">
			{foreach from=$web_status_menu key=status_id item=menu } 
			<li class="yuimenuitem"><a class="yuimenuitemlabel" onclick="change_web_status('{$status_id}')"> {$menu}</a></li>
			{/foreach} 
		</ul>
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
<div id="dialog_edit_web_state" style="padding:20px 20px 10px 20px ">
	<div id="new_customer_msg">
	</div>
	<input type="hidden" value="" id="product_pid"> 
	<div id="edit_web_state_wait" style="text-align:right;display:none">
		<img src="art/loading.gif" /> {t}Processing Request{/t} 
	</div>
	<div class="buttons" id="edit_web_state_buttons">
		<button onclick="set_web_configuration('Offline')">{t}Sold Out{/t}</button> <button onclick="set_web_configuration('Online Force Out of Stock')">{t}Out of Stock{/t}</button> <button onclick="set_web_configuration('Online Force For Sale')">{t}In Stock{/t}</button> <button onclick="set_web_configuration('Online Auto')">{t}Automatic{/t}</button> 
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
					<button id="sales_history_timeline_group_{$menu.mode}" class="timeline_group {if $sales_history_timeline_group==$menu.mode}selected{/if}" style="float:none;margin:0px auto;min-width:120px" onclick="change_timeline_group(4,'sales_history','{$menu.mode}','{$menu.label}')"> {$menu.label}</button> 
				</div>
				</td>
			</tr>
			{/foreach} 
		</tbody>
	</table>
</div>


{include file='notes_splinter.tpl'} {include file='footer.tpl'} 