{include file='header.tpl'} 
<div id="bd" class="{if $part->get('Part Available')=='No' or $part->get('Part Status')=='Not In Use' }discontinued{/if}" style="padding:0;">
	<input type="hidden" id="part_sku" value="{$part->sku}" />
	<input type="hidden" id="page_name" value="part" />
	<input type="hidden" id="part_location" value="" />
	<input type="hidden" id="link_extra_argument" value="&sku={$part->sku}" />
	<input type="hidden" id="from" value="{$from}" />
	<input type="hidden" id="to" value="{$to}" />
	<input type="hidden" id="history_table_id" value="3"> 
	<input type="hidden" id="subject" value="part"> 
	<input type="hidden" id="subject_key" value="{$part->sku}"> 
	<input type="hidden" id="barcode_data" value="{$part->get_barcode_data()}"> 
	<input type="hidden" id="barcode_type" value="{$part->get('Part Barcode Type')}"> 
	<input type="hidden" id="calendar_id" value="sales" />
	<div style="padding: 0 20px;">
		<input type="hidden" id="modify_stock" value="{$modify_stock}" />
		{include file='locations_navigation.tpl'} 
		<div class="branch">
			<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a>&rarr; {if $user->get_number_warehouses()>1}<a href="warehouses.php">{t}Warehouses{/t}</a> &rarr; {/if}<a href="inventory.php?warehouse_id={$warehouse->id}">{t}Inventory{/t}</a> &rarr; {$part->get_sku()}</span> 
		</div>
		<div class="top_page_menu">
			<div class="buttons" style="float:right">
				{if isset($next_sku) }<img class="next" onmouseover="this.src='art/next_button.gif'" onmouseout="this.src='art/next_button.png'" title="{$next_sku.title}" onclick="window.location='{$next_sku.link}'" src="art/next_button.png" alt="{t}Next{/t}" />{/if} {if $modify } <button onclick="window.location='edit_part.php?sku={$part->sku}'"><img src="art/icons/cog.png" alt=""> {t}Edit Part{/t}</button> {/if} 
			</div>
			<div class="buttons" style="float:left;">
				{if isset($prev_sku)}<img style="vertical-align:bottom;float:none" class="previous" onmouseover="this.src='art/previous_button.gif'" onmouseout="this.src='art/previous_button.png'" title="{$prev_sku.title}" onclick="window.location='{$prev_sku.link}'" src="art/previous_button.png" alt="{t}Previous{/t}" />{/if}<span class="main_title"><img src="art/icons/part.png" style="height:18px;position:relative;bottom:2px" /> <span style="font-weight:800"><span class="id">{$part->get_sku()}</span></span> {if $part->get('Part Reference')!=''}<span style="font-weight:600">{$part->get('Part Reference')}</span>, {/if} {$part->get('Part Unit Description')} </span> 
			</div>
			<div style="clear:both">
			</div>
		</div>
		<div id="block_info" style="margin-top:10px;width:930px;">
			<div style="float:right;width:105px;">
				<div class="buttons small">
					<button id="attach" style="width:105px;margin:0"><img src="art/icons/add.png" alt=""> {t}Attachment{/t}</button> <button id="note" style="width:105px;margin:0;margin-top:7px"><img src="art/icons/add.png" alt=""> {t}History Note{/t}</button> <button id="sticky_note_button" style="width:105px;margin:0;margin-top:7px"><img src="art/icons/note.png" alt=""> {t}Note{/t}</button> 
				</div>
				<div id="sticky_note_div" class="sticky_note" style="margin:0px;margin-top:10px;margin-right:5px;width:105px">
					<img id="sticky_note_bis" style="float:right;cursor:pointer" src="art/icons/edit.gif"> 
					<div id="sticky_note_content" style="padding:5px 5px 5px 10px;font-size:75%">
						{$sticky_note} 
					</div>
				</div>
			</div>
			<div id="photo_container" style="float:left">
				<div id="barcode_container" style="width:220px;height:7px;overflow:hidden;margin-bottom:3px;cursor:pointer" onclick="show_barcode()" barcode='hidden'>
					<div id="barcode" style="margin:auto;">
					</div>
				</div>
				<div style="border:1px solid #ddd;padding-stop:0;width:220px;xheight:230px;text-align:center;margin:0 10px 0 0px">
					<div id="imagediv" style="border:1px solid #ddd;width:190px;;padding:5px 5px;xborder:none;cursor:pointer;xbackground:red;margin: 10px 0 10px 9px;vertical-align:middle">
						<img id="main_image" src="{$part->get('Part Main Image')}" style="vertical-align:middle;display:block;;width:190px;;margin:0px auto" valign="center" border="1" id="image" alt="{t}Image{/t}" /> 
					</div>
				</div>
				<div style="width:160px;margin:auto;padding-top:5px;{if $part->get_number_of_images()<2}display:none{/if}">
					{foreach from=$part->get_images_slidesshow() item=image name=foo} {if $image.is_principal==0} <img style="float:left;border:1px solid#ccc;padding:2px;margin:2px;cursor:pointer" src="{$image.thumbnail_url}" title="" alt="" /> {/if} {/foreach} 
				</div>
			</div>
			<div style="width:280px;float:left;margin-left:5px">
				<table class="show_info_product">
					{foreach from=$part->get_categories() item=category name=foo } 
					<tr>
						<td>{if $smarty.foreach.foo.first}{t}Category{/t}:{/if}</td>
						<td><a href="part_category.php?id={$category.category_key}">{$category.category_label}</a></td>
					</tr>
					{/foreach} 
				</table>
				{t}Products Availability{/t}: 
				
				<table border="0" id="products" class="show_info_product" style=";margin-top:0px;margin-bottom:2px">

					<tr id="product_availability_tr" style="{if $show_products_web_state}border-bottom:1px solid #ccc{/if}">
					<td colspan=2>Availability <img style="height:12.9px;position:relative;bottom:1px;display:none" id="product_availability_wait"  src="art/loading.gif"></td>
					<td  colspan=2>
					<div class="buttons small" id="available_for_products_buttons">
					<button id="available_for_products_Automatic" class="item {if $part->get('Part Available for Products Configuration')=='Automatic'}selected{/if}">{t}Automatic{/t}</button>
					<button id="available_for_products_No" class="item {if $part->get('Part Available for Products Configuration')=='No'}selected{/if}">{t}No{/t}</button>
					<button id="available_for_products_Yes" class="item {if $part->get('Part Available for Products Configuration')=='Yes'}selected{/if}">{t}Yes{/t}</button>
					</div>
					</td>
					</tr>
					<tbody id="products_web_state_tbody" style="{if !$show_products_web_state}display:none{/if}">
					{foreach from=$part->get_current_products() item=product name=foo } 
					<tr id="product_tr_{$product.ProductID}">
						<td><a href="store.php?id={$product.StoreKey}">{$product.StoreCode} </a> </td>
						<td><a href="product.php?pid={$product.ProductID}">{$product.ProductCode} </a> </td>
						<td style="text-align:center" id="product_web_state_{$product.ProductID}"> 
						{if $product.ProductNumberWebPages==0} <img src="art/icons/world_light_bw.png" title="{t}Not in website{/t}" /> 
						{elseif $product.ProductWebState=='For Sale'} 
						<div style="position:relative">
							<img class="icon" src="art/icons/world.png" /> {if $product.ProductNumberWebPages>1} <span style="position:absolute;left:16px;top:6px;font-size:8px;background:red;color:white;padding:1px 1.7px 1px 2.2px;opacity:0.8;border-radius:30%">3</span> {/if} 
						</div>
						{else if $product.ProductWebState=='Out of Stock'}<img src="art/icons/no_stock.jpg" /> {else}<img src="art/icons/sold_out.gif" />{/if} </td>
						<td style="text-align:right;padding-right:10px" id="product_web_state_configuration_{$product.ProductID}"> 
						
						<span id="product_web_configuration_{$product.ProductID}" >
						<span style="cursor:pointer"  state="{$product.ProductWebConfiguration}" product_id="{$product.ProductID}"  onclick="change_web_configuration(this)">
						{if $product.ProductWebConfiguration=='Online Auto'}{t}Link to part{/t}
						{elseif $product.ProductWebConfiguration=='Offline'}<img src="art/icons/police_hat.jpg"  style="height:18px;;vertical-align:top" /> {t}Offline{/t} 
						{elseif $product.ProductWebConfiguration=='Online Force Out of Stock'}<img src="art/icons/police_hat.jpg" style="height:18px;;vertical-align:top" /> {t}Out of stock{/t} 
						{elseif $product.ProductWebConfiguration=='Online Force For Sale'}<img src="art/icons/police_hat.jpg" style="height:18px;;vertical-align:top" /> {t}Online{/t} {/if} 
						</span>
						</span> 
						</td>
						
					</tr>
					{/foreach}
					</tbody>
					
				</table>
				<div class="buttons small" style="text-align:right">
				<button style="{if $show_products_web_state}display:none{/if}" id="show_products_web_state" >{t}Show products web state{/t}</button>
				<button  style="{if !$show_products_web_state}display:none{/if}" id="hide_products_web_state">{t}Hide products web state{/t}</button>
				</div>
			</div>
			{if $part->get('Part Status')=='In Use'} 
			<div style="width:280px;float:left;margin-left:15px">
				<table class="show_info_product" style="width:270px" border=0>
					<tr>
						<td>{t}Stock{/t}: <span>({$part->get_unit($part->get('Part Current On Hand Stock'))})</span></td>
						<td class="stock aright" id="stock">{$part->get('Part Current On Hand Stock')}</td>
					</tr>
					<tr>
						<td class="aright" colspan="2" style="padding-top:0;color:#777;font-size:90%"> <b id="current_stock">{$part->get('Part Current Stock')}</b> <b>-[<span id="current_stock_picked" title="{t}Stock picked{/t}">{$part->get('Part Current Stock Picked')}</span>]</b> -(<span id="current_stock_in_process" title="{t}Waiting to be picked{/t}">{$part->get('Part Current Stock In Process')}</span>) &rarr; <span id="current_stock_available">{$part->get('Current Stock Available')}</span></td>
					</tr>
					<tbody style="font-size:80%">
						<tr>
							<td>{t}Value at Cost{/t}:</td>
							<td class="aright" id="value_at_cost">{$part->get_current_formated_value_at_cost()}</td>
						</tr>
						<tr>
							<td>{t}Value at Current Cost{/t}:</td>
							<td class="aright" id="value_at_current_cost">{$part->get_current_formated_value_at_current_cost()}</td>
						</tr>
						<tr>
							<td>{t}Commercial Value{/t}:</td>
							<td class="aright" id="commercial_value">{$part->get_current_formated_commercial_value()}</td>
						</tr>
					</tbody>
					<tr>
						<td style="{if $part->get('Part XHTML Available For Forecast')==''}display:none{/if}">{t}Available for{/t}:</td>
						<td class="stock aright" id="available_for_forecast">{$part->get('Part XHTML Available For Forecast')}</td>
					</tr>
					<tbody style="font-size:90%">
					<tr id="next_set_shipment_tr" >
						<td ><span style="float:left;margin-right:5px">{t}Next shipment{/t}: </span> <img id="show_dialog_set_up_shipment_date_bis" style="cursor:pointer;{if $part->get('Part Next Supplier Shipment')==''}display:none{/if}" src="art/icons/edit.gif"  />
						
						<td class="aright">
							<span id="next_set_shipment" style="{if $part->get('Part Next Supplier Shipment')==''}display:none{/if}">{$part->get('Next Supplier Shipment')}</span>
							<div id="next_set_shipment_setup" class="buttons small" style="position:relative;bottom:1px;{if $part->get('Part Next Supplier Shipment')!=''}display:none{/if}"><button id="show_dialog_set_up_shipment_date">{t}Setup{/t}</button></div>
							
							</td>
						</td>
						</tr>
						<tr>
						<td colspan="2" class="aright">{$part->get('Part XHTML Next Supplier Shipment')}</td>
					</tr>
					
					
				</table>
				{t}Locations{/t}: 
				<table border="0" id="part_locations" class="show_info_product" style="width:270px;margin-top:0px">
					{foreach from=$part->get_locations(true) item=location_data name=foo } 
					<tr id="part_location_tr_{$location_data.PartSKU}_{$location_data.LocationKey}">
						<td><a href="location.php?id={$location_data.LocationKey}">{$location_data.LocationCode} </a> <img style="{if $modify_stock}cursor:pointer{/if}" sku_formated="{$part->get_sku()}" location="{$location_data.LocationCode}" id="part_location_can_pick_{$location_data.PartSKU}_{$location_data.LocationKey}" can_pick="{if $location_data.CanPick=='Yes'}No{else}Yes{/if}" src="{if $location_data.CanPick=='Yes'}art/icons/basket.png{else}art/icons/box.png{/if}" alt="can_pick" onclick="save_can_pick({$location_data.PartSKU},{$location_data.LocationKey})" /> </td>
						<td id="picking_limit_quantities_{$location_data.PartSKU}_{$location_data.LocationKey}" min_value='{if isset($location_data.MinimumQuantity)}{$location_data.MinimumQuantity}{/if}' max_value='{if isset($location_data.MaximumQuantity)}{$location_data.MaximumQuantity}{/if}' location_key='{$location_data.LocationKey}' part_sku='{$part->sku}'  style="cursor:pointer; color:#808080;{if $location_data.CanPick =='No'}display:none{/if}" onclick="show_picking_limit_quantities(this)"> {literal}{{/literal}<span id="picking_limit_min_{$location_data.PartSKU}_{$location_data.LocationKey}">{if isset($location_data.MinimumQuantity)}{$location_data.MinimumQuantity}{else}?{/if}</span>,<span id="picking_limit_max_{$location_data.PartSKU}_{$location_data.LocationKey}">{if isset($location_data.MaximumQuantity)}{$location_data.MaximumQuantity}{else}?{/if}</span>{literal}}{/literal} </td>
						<td id="store_limit_quantities_{$location_data.PartSKU}_{$location_data.LocationKey}" move_qty='{if isset($location_data.MovingQuantity)}{$location_data.MovingQuantity}{/if}' location_key='{$location_data.LocationKey}' style="cursor:pointer; color:#808080;{if $location_data.CanPick =='Yes'}display:none{/if}" onclick="show_move_quantities(this)"> [<span id="store_limit_move_qty_{$location_data.PartSKU}_{$location_data.LocationKey}">{if isset($location_data.MovingQuantity)}{$location_data.MovingQuantity}{else}?{/if}</span>] </td>
						<td class="quantity" id="part_location_quantity_{$location_data.PartSKU}_{$location_data.LocationKey}" quantity="{$location_data.QuantityOnHand}">{$location_data.FormatedQuantityOnHand}</td>
						<td style="{if !$modify_stock}display:none{/if}" class="button"><img style="cursor:pointer" id="part_location_audit_{$location_data.PartSKU}_{$location_data.LocationKey}" src="art/icons/note_edit.png" title="{t}audit{/t}" alt="{t}audit{/t}" onclick="audit({$location_data.PartSKU},{$location_data.LocationKey})" /></td>
						<td style="{if !$modify_stock}display:none{/if}" class="button"> <img style="cursor:pointer" sku_formated="{$part->get_sku()}" location="{$location_data.LocationCode}" id="part_location_add_stock_{$location_data.PartSKU}_{$location_data.LocationKey}" src="art/icons/lorry.png" title="{t}add stock{/t}" alt="{t}add stock{/t}" onclick="add_stock_part_location({$location_data.PartSKU},{$location_data.LocationKey})" /></td>
						<td style="{if !$modify_stock}display:none{/if}" class="button"> <img style="{if $location_data.QuantityOnHand!=0}display:none;{/if}cursor:pointer" sku_formated="{$part->get_sku()}" location="{$location_data.LocationCode}" id="part_location_delete_{$location_data.PartSKU}_{$location_data.LocationKey}" src="art/icons/cross_bw.png" title="{t}delete{/t}" alt="{t}delete{/t}" onclick="delete_part_location({$location_data.PartSKU},{$location_data.LocationKey})" /><img style="{if $location_data.QuantityOnHand==0}display:none;{/if}cursor:pointer" id="part_location_lost_items_{$location_data.PartSKU}_{$location_data.LocationKey}" src="art/icons/package_delete.png" title="{t}lost{/t}" alt="{t}lost{/t}" onclick="lost({$location_data.PartSKU},{$location_data.LocationKey})" /></td>
						<td style="{if !$modify_stock}display:none{/if}" class="button"><img style="cursor:pointer" sku_formated="{$part->get_sku()}" location="{$location_data.LocationCode}" id="part_location_move_items_{$location_data.PartSKU}_{$location_data.LocationKey}" src="art/icons/package_go.png" title="{t}move{/t}" alt="{t}move{/t}" onclick="move({$location_data.PartSKU},{$location_data.LocationKey})" /></td>
					</tr>
					{/foreach} 
					<tr style="{if !$modify_stock}display:none{/if}">
						<td colspan="7"> 
						<div id="add_location_button" class="buttons small left">
							<button onclick="add_location({$part->sku})">{t}Add Location{/t}</button> 
						</div>
						</td>
					</tr>
				</table>
			</div>
			{else} 
			<div style="width:280px;float:left;margin-left:15px">
				<table class="show_info_product " style="margin:0;padding:5px 10px;width:100%;">
					<tr>
						<td colspan="2" class="discontinued" style="font-weight:800;font-size:160%;text-align:center">{t}No longer keeped in Warehouse{/t}</td>
					</tr>
					<tr>
						<td>{t}Discontinued{/t}:</td>
						<td>{$part->get('Valid To')}</td>
					</tr>
				</table>
			</div>
			{/if} 
			<div style="clear:both">
			</div>
		</div>
	</div>
	<ul class="tabs" id="chooser_ul" style="clear:both;margin-top:15px">
		<li><span class="item {if $view=='description'}selected{/if}" id="description"> <span> {t}Description{/t}</span></span></li>
		<li><span class="item {if $view=='notes'}selected{/if}" id="notes"> <span> {t}History/Notes{/t}</span></span></li>
		<li><span class="item {if $view=='sales'}selected{/if}" id="sales"> <span> {t}Sales{/t}</span></span></li>
		<li style="display:none"><span class="item {if $view=='transactions'}selected{/if}" id="transactions"> <span> {t}Stock Transactions{/t}</span></span></li>
		<li><span class="item {if $view=='history'}selected{/if}" id="history"> <span> {t}Stock{/t}</span></span></li>
		<li><span class="item {if $view=='delivery_notes'}selected{/if}" id="delivery_notes"> <span> {t}Delivery Notes{/t}</span></span></li>
		<li><span class="item {if $view=='purchase_orders'}selected{/if}" id="purchase_orders"> <span> {t}Purchase Orders{/t}</span></span></li>
	</ul>
	<div class="tabbed_container no_padding blocks" style="min-height:400px;;margin-bottom:0px">
		<div id="calendar_container" style="padding:0 20px;padding-bottom:0px;{if $view=='description' or  $view=='notes'}display:none{/if}">
			<div id="period_label_container" style="{if $period==''}display:none{/if}">
				<img src="art/icons/clock_16.png"> <span id="period_label">{$period_label}</span> 
			</div>
			{include file='calendar_splinter.tpl' } 
			<div style="clear:both">
			</div>
		</div>
		
		<div id="block_history" class="block" style="{if $view!='history'}display:none;{/if}clear:both">
			<div class="buttons small left tabs">
				<button class="indented item {if $stock_history_block=='transactions'}selected{/if}" id="history_block_transactions" block_id="transactions">{t}Stock Transactions{/t}</button> 
				<button class="item {if $stock_history_block=='list'}selected{/if}" id="history_block_list" block_id="list">{t}Stock History{/t}</button> 
				<button class="item {if $stock_history_block=='plot'}selected{/if}" id="history_block_plot" block_id="plot">{t}Stock History Plot{/t}</button> 
					<button class="item {if $stock_history_block=='avalability'}selected{/if}" id="history_block_avalability" block_id="avalability">{t}Availability History{/t}</button> 
	
		
		</div>
			<div class="tabs_base">
			</div>
						<div id="stock_history_avalability_subblock" class="block data_table" style="{if $stock_history_block!='avalability'}display:none;{/if}clear:both;margin-top:20px;;padding:0 20px 20px 20px">

<span class="clean_table_title with_elements">{t}Availability{/t}</span> 
			
			<div class="table_top_bar space">
			</div>
			{include file='table_splinter.tpl' table_id=6 filter_name=$filter_name6 filter_value=$filter_value6 } 
			<div style="font-size:85%" id="table6" class="data_table_container dtable btable">
			</div>

</div>
			
			<div id="stock_history_transactions_subblock" class="block data_table" style="{if $stock_history_block!='transactions'}display:none;{/if}clear:both;margin-top:20px;;padding:0 20px 20px 20px">
			<span class="clean_table_title with_elements">{t}Part Stock Transactions{/t}</span> 
			<div class="elements_chooser">
				<span style="float:right;margin-left:20px" class="table_type transaction_type state_details {if $transaction_type=='all_transactions'}selected{/if}" id="restrictions_all_transactions" table_type="all_transactions">{t}All{/t} (<span id="transactions_all_transactions"></span><img id="transactions_all_transactions_wait" src="art/loading.gif" style="height:11px">)</span> <span style="float:right;margin-left:20px" class="table_type transaction_type state_details {if $transaction_type=='oip_transactions'}selected{/if}" id="restrictions_oip_transactions" table_type="oip_transactions">{t}OIP{/t} (<span id="transactions_oip_transactions"></span><img id="transactions_oip_transactions_wait" src="art/loading.gif" style="height:11px">)</span> <span style="float:right;margin-left:20px" class="table_type transaction_type state_details {if $transaction_type=='out_transactions'}selected{/if}" id="restrictions_out_transactions" table_type="out_transactions">{t}Out{/t} (<span id="transactions_out_transactions"></span><img id="transactions_out_transactions_wait" src="art/loading.gif" style="height:11px">)</span> <span style="float:right;margin-left:20px" class="table_type transaction_type state_details {if $transaction_type=='in_transactions'}selected{/if}" id="restrictions_in_transactions" table_type="in_transactions">{t}In{/t} (<span id="transactions_in_transactions"></span><img id="transactions_in_transactions_wait" src="art/loading.gif" style="height:11px">)</span> <span style="float:right;margin-left:20px" class="table_type transaction_type state_details {if $transaction_type=='audit_transactions'}selected{/if}" id="restrictions_audit_transactions" table_type="audit_transactions">{t}Audits{/t} (<span id="transactions_audit_transactions"></span><img id="transactions_audit_transactions_wait" src="art/loading.gif" style="height:11px">)</span> <span style="float:right;margin-left:20px" class="table_type transaction_type state_details {if $transaction_type=='move_transactions'}selected{/if}" id="restrictions_move_transactions" table_type="move_transactions">{t}Movements{/t} (<span id="transactions_move_transactions"></span><img id="transactions_move_transactions_wait" src="art/loading.gif" style="height:11px">)</span> 
			</div>
			<div class="table_top_bar space">
			</div>
			{include file='table_splinter.tpl' table_id=1 filter_name=$filter_name1 filter_value=$filter_value1 } 
			<div style="font-size:85%" id="table1" class="data_table_container dtable btable">
			</div>
		</div>
			
			
			<div id="stock_history_plot_subblock" class="edit_block_content" style="{if $stock_history_block!='plot'}display:none{/if}">
				<div class="buttons small">
					<button id="change_plot">&#x21b6 <span id="change_plot_label_value" style="{if $stock_history_chart_output!='stock'}display:none{/if}">{t}Stock{/t}</span> <span id="change_plot_label_stock" style="{if $stock_history_chart_output!='value'}display:none{/if}">{t}Value at Cost{/t}</span> <span id="change_plot_label_end_day_value" style="{if $stock_history_chart_output!='end_day_value'}display:none{/if}">{t}Cost Value (end day){/t}</span> <span id="change_plot_label_commercial_value" style="{if $stock_history_chart_output!='commercial_value'}display:none{/if}">{t}Commercial Value{/t}</span> </button> 
				</div>
				<div id="stock_history_plot">
					<strong>You need to upgrade your Flash Player</strong> 
				</div>
<script type="text/javascript">
		// <![CDATA[
		var so = new SWFObject("external_libs/amstock/amstock/amstock.swf", "part_history_plot_object", "930", "500", "8", "#FFFFFF");
		so.addVariable("path", "");
				so.addVariable("chart_id", "part_history_plot_object");
		so.addVariable("settings_file", encodeURIComponent("conf/plot_general_candlestick.xml.php?tipo=part_stock_history&output={$stock_history_chart_output}&parent=part&parent_key={$part->sku}"));
		so.addVariable("preloader_color", "#999999");
		so.write("stock_history_plot");
		// ]]>
	</script> <script>

var flashMovie;

function reloadSettings(file) {
  flashMovie.reloadSettings(file);
}

	function amChartInited(chart_id){

  flashMovie = document.getElementById(chart_id);
  
  }
	</script> 
			</div>
			<div id="stock_history_list_subblock" class="edit_block_content" style="{if $stock_history_block!='list'}display:none{/if}">
				<span class="clean_table_title" style="clear:both;">{t}Stock History{/t} </span> 
				<div class="table_top_bar">
				</div>
				<div class="clusters">
					<div class="buttons small cluster group">
						<button id="change_stock_history_timeline_group"> &#x21b6 {$stock_history_timeline_group_label}</button> 
					</div>
					<div style="clear:both;margin-bottom:5px">
					</div>
				</div>
				{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0 no_filter=1 } 
				<div id="table0" style="font-size:85%" class="data_table_container dtable btable">
				</div>
			</div>
		</div>
		<div id="block_description" class="block data_table" style="{if $view!='description'}display:none;{/if}clear:both;margin-top:20px;;padding:0 20px 30px 20px;min-height:300px">
			<div style="width:500px;float:left;margin-left:0px;">
				<table border="0" class="show_info_product" id="description_info">
					<tr>
						<td>{t}Referece{/t}:</td>
						<td>{$part->get('Part Reference')}</td>
					</tr>
					<tr>
						<td style="width:150px">{t}Commodity Code{/t}:</td>
						<td>{$part->get('Part Tariff Code')}</td>
					</tr>
					<tr>
						<td>{t}Duty Rate{/t}:</td>
						<td>{$part->get('Part Duty Rate')}</td>
					</tr>
				</table>
				<table class="show_info_product">
					<tr>
						<td style="width:150px">{t}Keeping since{/t}:</td>
						<td>{$part->get('Valid From Datetime')}</td>
					</tr>
					<tr>
						<td>{t}Sold as{/t}:</td>
						<td>{$part->get('Part XHTML Currently Used In')}</td>
					</tr>
					{if $part->get('Part Available')=='No'} 
					<tr class="discontinued">
						<td colspan="2" style="font-weight:800;font-size:160%;text-align:center">{t}Can't restock{/t}</td>
					</tr>
					{else} 
					<tr>
						<td>{t}Supplied by{/t}:</td>
						<td>{$part->get('Part XHTML Currently Supplied By')}</td>
					</tr>
					<tr>
						<td>{t}Cost{/t}:</td>
						<td>{$part->get_formated_unit_cost()}</td>
					</tr>
					{/if} {foreach from=$show_case key=name item=value} 
					<tr>
						<td>{$name}:</td>
						<td>{$value}</td>
					</tr>
					{/foreach} 
				</table>
			</div>
			<div style="float:left;margin-left:20px;width:400px">
				<table border="0" class="show_info_product" id="propierties_info">
					<tr>
						<td style="width:180px">{t}Package Type{/t}:</td>
						<td>{$part->get('Part Package Type')}</td>
					</tr>
					<tr>
						<td style="width:180px">{t}Package Weight{/t}:</td>
						<td>{$part->get('Package Weight')}</td>
					</tr>
					<tr>
						<td>{t}Package Dimensions{/t}:</td>
						<td>{$part->get('Part Package XHTML Dimensions')}</td>
					</tr>
					<tr>
						<td>{t}Package Volume{/t}:</td>
						<td>{$part->get('Package Volume')}</td>
					</tr>
					<tr>
						<td style="width:180px">{t}Individual Item Weight{/t}:</td>
						<td>{$part->get('Unit Weight')}</td>
					</tr>
					<tr>
						<td>{t}Individual Item Dimensions{/t}:</td>
						<td>{$part->get('Part Unit XHTML Dimensions')}</td>
					</tr>
				</table>
			</div>
			<div style="float:left;width:450px;margin-left:10px;{if !$number_part_custom_fields}display:none{/if}">
				<h2 style="clear:both">
					{t}Custom Fields{/t} 
				</h2>
				<table class="show_info_product">
					{foreach from=$part_custom_fields key=name item=value} 
					<tr>
						<td>{$name}:</td>
						<td>{$value}</td>
					</tr>
					{/foreach} 
				</table>
			</div>
			<div style="clear:both;{if !$part->get('Part General Description')}display:none{/if}">
				<h2>
					{t}General Description{/t} 
				</h2>
				<div style="margin-top:5px;width:450px">
					{$part->get('Part General Description')} 
				</div>
			</div>
			<div style="clear:both;{if !$part->get('Part Health And Safety')}display:none{/if}">
				<h2>
					{t}Health & Safety{/t} 
				</h2>
				<div style="margin-top:5px">
					{$part->get('Part Health And Safety')} 
				</div>
			</div>
			<div style="clear:both">
			</div>
		</div>
		<div id="block_sales" class="block data_table" style="{if $view!='sales'}display:none;{/if}clear:both;margin-top:5px;padding:0 20px 30px 20px ">
			<div style="clear:both">
			</div>
			<div style="margin-top:20px;width:900px;">
				<div style="margin-top:5px">
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
									<td class="aright" id="given"><img style="height:14px" src="art/loading.gif" /></td>
								</tr>
								<tr id="dispatched_tr" style="display:none">
									<td>{t}Total Dispatched{/t}:</td>
									<td class="aright" id="dispatched" style="font-weight:800"><img style="height:14px" src="art/loading.gif" /></td>
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
					<li> <span class="item {if $sales_sub_block_tipo=='plot_part_sales'}selected{/if}" onclick="change_sales_sub_block(this)" id="plot_part_sales" tipo="store"> <span>{t}Sales Chart{/t}</span> </span> </li>
					<li> <span class="item {if $sales_sub_block_tipo=='part_sales_timeseries'}selected{/if}" onclick="change_sales_sub_block(this)" id="part_sales_timeseries" tipo="store"> <span>{t}Part Sales History{/t}</span> </span> </li>
					<li> <span style="display:none;" class="item {if $sales_sub_block_tipo=='product_breakdown_sales'}selected{/if}" onclick="change_sales_sub_block(this)" id="product_breakdown_sales" tipo="list" forecast="" interval=""> <span>{t}Products Sales Breakdown{/t}</span> </span> </li>
				</ul>
				<div id="sub_block_plot_part_sales" style="min-height:400px;clear:both;border:1px solid #ccc;{if $sales_sub_block_tipo!='plot_part_sales'}display:none{/if}">
				<script type="text/javascript" src="external_libs/amstock/amstock/swfobject.js"></script> <script type="text/javascript">
					// <![CDATA[
						var so = new SWFObject("external_libs/amstock/amstock/amstock.swf", "amstock", "905", "500", "8", "#FFFFFF");
						so.addVariable("path", "");
						so.addVariable("settings_file", encodeURIComponent("conf/plot_asset_sales.xml.php?tipo=part_sales&part_sku={$part->sku}"));
		so.addVariable("preloader_color", "#999999");
		so.write("sub_block_plot_part_sales");
		// ]]>
	</script> 
					<div style="clear:both">
					</div>
				</div>
				<div id="sub_block_part_sales_timeseries" style="padding:20px;min-height:400px;clear:both;border:1px solid #ccc;{if $sales_sub_block_tipo!='part_sales_timeseries'}display:none{/if}">
					<span class="clean_table_title">{t}Part Sales History{/t}</span> 
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
				<div id="sub_block_product_breakdown_sales" style="min-height:400px;clear:both;border:1px solid #ccc;{if $sales_sub_block_tipo!='product_breakdown_sales'}display:none{/if}">
					<span class="clean_table_title">{t}Product Breakdown{/t}</span> 
					<div class="table_top_bar space">
					</div>
					{include file='table_splinter.tpl' table_id=5 filter_name=$filter_name5 filter_value=$filter_value5 no_filter=1 } 
					<div id="table5" style="font-size:85%" class="data_table_container dtable btable">
					</div>
				</div>
				<div style="clear:both;">
				</div>
			</div>
		</div>
		<div id="block_notes" style="{if $view!='notes'}display:none;{/if}clear:both;margin-top:20px;;padding:0 20px 30px 20px">
			<span id="table_title" class="clean_table_title">{t}History/Notes{/t}</span> 
			<div class="elements_chooser">
				<span style="float:right;margin-left:20px;" class=" table_type transaction_type state_details {if $elements_part_history.Changes}selected{/if} label_part_history_changes" id="elements_part_history_changes" table_type="elements_changes">{t}Changes History{/t} (<span id="elements_changes_number_changes">{$elements_part_history_number.Changes}</span>)</span> 
				<span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_part_history.Notes}selected{/if} label_part_history_notes" id="elements_part_history_notes" table_type="elements_notes">{t}Staff Notes{/t} (<span id="elements_notes_number_notes">{$elements_part_history_number.Notes}</span>)</span> 
				<span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_part_history.Attachments}selected{/if} label_part_history_attachments" id="elements_part_history_attachments" table_type="elements_attachments">{t}Attachments{/t} (<span id="elements_notes_number_attachments">{$elements_part_history_number.Attachments}</span>)</span> 
				<span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_part_history.Products}selected{/if} label_part_history_products_changes" id="elements_part_history_products_changes" table_type="elements_products_changes">{t}Product Changes{/t} (<span id="elements_notes_number_products_changes">{$elements_part_history_number.Products}</span>)</span> 

			</div>
			<div class="table_top_bar space">
			</div>
			{include file='table_splinter.tpl' table_id=3 filter_name=$filter_name3 filter_value=$filter_value3} 
			<div id="table3" class="data_table_container dtable btable" style="font-size:90%">
			</div>
		</div>
		<div id="block_purchase_orders" class="block data_table" style="{if $view!='puchase_orders'}display:none;{/if}clear:both;margin-top:20px;;padding:0 20px 30px 20px ">
		</div>
		<div id="block_delivery_notes" class="block data_table" style="{if $view!='delivery_notes'}display:none;{/if}clear:both;margin-top:20px;;padding:0 20px 30px 20px ">
			<span class="clean_table_title">{t}Delivery Note List{/t} <img id="export_dn" class="export_data_link" label="{t}Export Table{/t}" alt="{t}Export Table{/t}" src="art/icons/export_csv.gif"> </span> 
			<div class="elements_chooser">
				<img class="menu" id="dn_element_chooser_menu_button" title="{t}Group by menu{/t}" src="art/icons/list.png" /> 
				<div id="dn_type_chooser" style="{if $elements_dn_elements_type!='type'}display:none{/if}">
					<span style="float:right;margin-left:15px" class=" table_type transaction_type state_details {if $elements_dn_type.Order}selected{/if} label_elements_type_Order" id="elements_dn_type_Order" table_type="Order">{t}Orders{/t} (<span id="elements_dn_type_Order_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:15px" class=" table_type transaction_type state_details {if $elements_dn_type.Sample}selected{/if} label_elements_type_Sample" id="elements_dn_type_Sample" table_type="Sample">{t}Samples{/t} (<span id="elements_dn_type_Sample_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:15px" class=" table_type transaction_type state_details {if $elements_dn_type.Donation}selected{/if} label_elements_type_Donation" id="elements_dn_type_Donation" table_type="Donation">{t}Donations{/t} (<span id="elements_dn_type_Donation_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:15px" class=" table_type transaction_type state_details {if $elements_dn_type.Replacements}selected{/if} label_elements_type_Replacements" id="elements_dn_type_Replacements" table_type="Replacements">{t}Replacements{/t} (<span id="elements_dn_type_Replacements_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:15px" class=" table_type transaction_type state_details {if $elements_dn_type.Shortages}selected{/if} label_elements_type_Shortages" id="elements_dn_type_Shortages" table_type="Shortages">{t}Shortages{/t} (<span id="elements_dn_type_Shortages_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> 
				</div>
				<div id="dn_dispatch_chooser" style="{if $elements_dn_elements_type!='dispatch'}display:none{/if}">
					<span style="float:right;margin-left:15px" class=" table_type transaction_type state_details {if $elements_dn_dispatch.Ready}selected{/if} label_elements_dispatch_Ready" id="elements_dn_dispatch_Ready" table_type="Ready">{t}Ready{/t} (<span id="elements_dn_dispatch_Ready_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:15px" class=" table_type transaction_type state_details {if $elements_dn_dispatch.Picking}selected{/if} label_elements_dispatch_Picking" id="elements_dn_dispatch_Picking" table_type="Picking">{t}Picking{/t} (<span id="elements_dn_dispatch_Picking_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:15px" class=" table_type transaction_type state_details {if $elements_dn_dispatch.Packing}selected{/if} label_elements_dispatch_Packing" id="elements_dn_dispatch_Packing" table_type="Packing">{t}Packing{/t} (<span id="elements_dn_dispatch_Packing_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:15px" class=" table_type transaction_type state_details {if $elements_dn_dispatch.Done}selected{/if} label_elements_dispatch_Done" id="elements_dn_dispatch_Done" table_type="Done">{t}Done{/t} (<span id="elements_dn_dispatch_Done_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:15px" class=" table_type transaction_type state_details {if $elements_dn_dispatch.Send}selected{/if} label_elements_dispatch_Send" id="elements_dn_dispatch_Send" table_type="Send">{t}Send{/t} (<span id="elements_dn_dispatch_Send_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:15px" class=" table_type transaction_type state_details {if $elements_dn_dispatch.Returned}selected{/if} label_elements_dispatch_Returned" id="elements_dn_dispatch_Returned" table_type="Returned">{t}Returned{/t} (<span id="elements_dn_dispatch_Returned_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> 
				</div>
			</div>
			<div class="table_top_bar space">
			</div>
			{include file='table_splinter.tpl' table_id=2 filter_name=$filter_name2 filter_value=$filter_value2 no_filter=0 } 
			<div id="table2" style="font-size:85%" class="data_table_container dtable btable">
			</div>
		</div>
	</div>
</div>
{include file='stock_splinter.tpl'   } {include file='notes_splinter.tpl'} 
<div id="rppmenu0" class="yuimenu">
	<div class="bd">
		<ul class="first-of-type">
			<li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Rows per Page{/t}:</li>
			{foreach from=$paginator_menu0 item=menu } 
			<li class="yuimenuitem"><a class="yuimenuitemlabel" onclick="change_rpp({$menu},0)"> {$menu}</a></li>
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
<div id="dialog_edit_web_state" style="padding:20px 20px 10px 20px ">
	<div id="new_customer_msg">
	</div>
	<input type="hidden" value="" id="product_pid"> 
	<div id="edit_web_state_wait" style="text-align:right;display:none">
		<img src="art/loading.gif" /> {t}Processing Request{/t} 
	</div>
	<div class="buttons" id="edit_web_state_buttons">
	
	
	
		<button id="edit_web_state_Offline" class="edit_web_state_button" onclick="set_web_configuration('Offline')">{t}Offline{/t}</button> 
		<button id="edit_web_state_OnlineForceOutofStock" class="edit_web_state_button" onclick="set_web_configuration('Online Force Out of Stock')">{t}Force Out of Stock{/t}</button> 
		<button id="edit_web_state_OnlineForceForSale" class="edit_web_state_button" onclick="set_web_configuration('Online Force For Sale')">{t}Force Online{/t}</button> 
		<button id="edit_web_state_OnlineAuto" class="edit_web_state_button" onclick="set_web_configuration('Online Auto')">{t}Link to part{/t}</button> 
	</div>
</div>
<div id="change_plot_menu" style="padding:10px 20px 0px 10px">
	<table class="edit" border="0" style="width:200px">
		<tr class="title">
			<td>{t}Choose chart{/t}:</td>
		</tr>
		<tr style="height:5px">
			<td></td>
		</tr>
		<tr>
			<td> 
			<div class="buttons">
				<button style="float:none;margin:0px auto;min-width:140px" onclick="change_plot('stock')"> {t}Stock{/t}</button> <button style="float:none;margin:0px auto;min-width:140px" onclick="change_plot('value')"> {t}Value at Cost{/t}</button> <button style="float:none;margin:0px auto;min-width:140px" onclick="change_plot('end_day_value')"> {t}Cost Value (end day){/t}</button> <button style="float:none;margin:0px auto;min-width:140px" onclick="change_plot('commercial_value')"> {t}Commercial Value{/t}</button> 
			</div>
			</td>
		</tr>
	</table>
</div>
<div id="dialog_stock_history_timeline_group" style="padding:10px 20px 0px 10px">
	<table class="edit" border="0" style="width:200px">
		<tr style="height:5px">
			<td></td>
		</tr>
		<tbody id="stock_history_timeline_group_options">
			{foreach from=$timeline_group_stock_history_options item=menu } 
			<tr>
				<td> 
				<div class="buttons small">
					<button id="stock_history_timeline_group_{$menu.mode}" class="timeline_group {if $stock_history_timeline_group==$menu.mode}selected{/if}" style="float:none;margin:0px auto;min-width:120px" onclick="change_timeline_group(0,'stock_history','{$menu.mode}','{$menu.label}')"> {$menu.label}</button> 
				</div>
				</td>
			</tr>
			{/foreach} 
		</tbody>
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
					<button id="sales_history_timeline_group_{$menu.mode}" class="timeline_group {if $sales_history_timeline_group==$menu.mode}selected{/if}" style="float:none;margin:0px auto;min-width:120px" onclick="change_timeline_group(4,'sales_history','{$menu.mode}','{$menu.label}')"> {$menu.label}</button> 
				</div>
				</td>
			</tr>
			{/foreach} 
		</tbody>
	</table>
</div>

<div id="dialog_set_up_shipment_date" style="padding:20px;padding-bottom:0px">
	<div class="bd">
		<div id="dialog_set_up_shipment_date_msg">
		</div>
		<table border=0 class="edit" style="width:100%">
			
			<tr>
				<td class="label">{t}Next Shipment Date{/t}:</td>
				<td style="width:120px">
				<input id="v_calpop1" style="text-align:right;" class="text" type="text" size="10" maxlength="10" value="" />
				<img id="calpop1" style="cursor:pointer;text-align:right;position:relative;bottom:1px" src="art/icons/calendar_view_month.png" align="top" alt="" /> 
				</td>
			</tr>
			<tr class="space10">
				<td colspan="2" > 
				<div class="buttons" style="text-align:right;margin-right:20px;">
				<img  id="next_set_shipment_wait" src="art/loading.gif" style="display:none">
				<div id="next_set_shipment_buttons" >
				<button class="positive"  onclick="save_set_up_shipment_date()">{t}Save{/t}</button> 
				<button class="negative" onclick="cancel_set_up_shipment_date()">{t}Cancel{/t}</button> 
				</div>
				</td>
				</buttons>
			</tr>
		</table>
	</div>
</div>
<div id="cal1Container" style="position:absolute;display:none;z-index:3"></div>	

{include file='footer.tpl'} 