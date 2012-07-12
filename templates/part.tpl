{include file='header.tpl'} 
<div id="bd" class="{if $part->get('Part Available')=='No' or $part->get('Part Status')=='Not In Use' }discontinued{/if}" style="padding:0;">
	<input type="hidden" id="part_sku" value="{$part->sku}" />
	<input type="hidden" id="page_name" value="part" />
	<input type="hidden" id="part_location" value="" />
	<div style="padding: 0 20px;">
		<input type="hidden" id="modify_stock" value="{$modify_stock}" />
		{include file='locations_navigation.tpl'} 
		<div class="branch">
			<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a>&rarr; {if $user->get_number_warehouses()>1}<a href="warehouses.php">{t}Warehouses{/t}</a> &rarr; {/if}<a href="warehouse_parts.php?warehouse_id={$warehouse->id}">{t}Inventory{/t}</a> &rarr; {$part->get_sku()}</span> 
		</div>
		<div class="top_page_menu">
			<div class="buttons" style="float:right">
				{if isset($next) }<img class="next" onmouseover="this.src='art/next_button.gif'" onmouseout="this.src='art/next_button.png'" title="{$next.title}" onclick="window.location='{$next.link}'" src="art/next_button.png" alt="{t}Next{/t}" />{/if} {if $modify } <button onclick="window.location='edit_part.php?id={$part->sku}'"><img src="art/icons/cog.png" alt=""> {t}Edit Part{/t}</button> {/if} 
			</div>
			<div class="buttons" style="float:left;">
				{if isset($prev)}<img style="vertical-align:bottom;float:none" class="previous" onmouseover="this.src='art/previous_button.gif'" onmouseout="this.src='art/previous_button.png'" title="{$prev.title}" onclick="window.location='{$prev.link}'" src="art/previous_button.png" alt="{t}Previous{/t}" />{/if} <span style="font-size:140%;width:600px;position:relative;bottom:-5px;left:-5px"><span style="font-weight:800"><span class="id">{$part->get_sku()}</span></span> {$part->get('Part Unit Description')} </span> 
			</div>
			<div style="clear:both">
			</div>
		</div>
		<div style="display:none;clear:left">
			<h1 style="padding:10px 0 0 0 ;font-size:140%">
				<span style="font-weight:800"><span class="id">{$part->get_sku()}</span></span> {$part->get('Part Unit Description')} 
			</h1>
			<h3 style="padding:0">
				{t}Sold as{/t}: {$part->get('Part XHTML Currently Used In')} 
			</h3>
		</div>
		<div id="block_info" style="margin-top:10px;width:900px;">
			<div id="photo_container" style="float:left">
				<div style="border:1px solid #ddd;padding-stop:0;width:220px;xheight:230px;text-align:center;margin:0 10px 0 0px">
					<div id="imagediv" style="border:1px solid #ddd;width:190px;;padding:5px 5px;xborder:none;cursor:pointer;xbackground:red;margin: 10px 0 10px 9px;vertical-align:middle">
						<img id="main_image" src="{$part->get('Part Main Image')}" style="vertical-align:middle;display:block;;width:190px;;margin:0px auto" valign="center" border="1" id="image" alt="{t}Image{/t}" /> 
					</div>
				</div>
				<div style="width:160px;margin:auto;padding-top:5px">
					{foreach from=$part->get_images_slidesshow() item=image name=foo} {if $image.is_principal==0} <img style="float:left;border:1px solid#ccc;padding:2px;margin:2px;cursor:pointer" src="{$image.thumbnail_url}" title="" alt="" /> {/if} {/foreach} 
				</div>
			</div>
			<div style="width:290px;float:left;margin-left:20px">
				<table class="show_info_product">
					<tr>
						<td>{t}Weight{/t}:</td>
						<td>{$part->get('Weight')}</td>
					</tr>
					<tr>
						<td>{t}Commodity Code{/t}:</td>
						<td>{$part->get('Part Tariff Code')}</td>
					</tr>
					<tr>
						<td>{t}Duty Rate{/t}:</td>
						<td>{$part->get('Part Duty Rate')}</td>
					</tr>
					
					
									{foreach from=$part->get_categories() item=category name=foo } 

				<tr>
						<td>{t}Category{/t}:</td>
						<td><a href="part_categories.php?id={$category.category_key}">{$category.category_label}</a></td>
					</tr>
					{/foreach}
				</table>
				{t}Products{/t}: 
				<table border="0" id="products" class="show_info_product" style=";margin-top:0px">
					{foreach from=$part->get_current_products() item=product name=foo } 
					<tr id="product_tr_{$product.ProductID}">
						<td><a href="store.php?id={$product.StoreKey}">{$product.StoreCode} </a> </td>
						<td><a href="product.php?pid={$product.ProductID}">{$product.ProductCode} </a> </td>
						<td style="text-align:center" id="product_web_state_{$product.ProductID}">{if $product.ProductWebState=='For Sale'}<img src="art/icons/world.png" style="margin:0px auto" />{else if $product.ProductWebState=='Out of Stock'}<img src="art/icons/no_stock.jpg" />{else}<img src="art/icons/sold_out.gif" />{/if} </td>
						<td><span style="cursor:pointer" id="product_web_configuration_{$product.ProductID}" onClick="change_web_configuration(this,{$product.ProductID})">{if $product.ProductWebConfiguration=='Online Auto'}{t}Automatic{/t}{elseif $product.ProductWebConfiguration=='Offline'}<img src="art/icons/police_hat.jpg" style="height:18px" /> {t}Offline{/t} {elseif $product.ProductWebConfiguration=='Online Force Out of Stock'}<img src="art/icons/police_hat.jpg" style="height:18px" /> {t}Out of stock{/t} {elseif $product.ProductWebConfiguration=='Online Force For Sale'}<img src="art/icons/police_hat.jpg" style="height:18px" /> {t}Online{/t} {/if} <span> </td>
					</tr>
					{/foreach} 
				</table>
			</div>
			{if $part->get('Part Status')=='In Use'} 
			<div style="width:290px;float:left;margin-left:20px">
				<table class="show_info_product" style="width:270px">
					<tr>
						<td>{t}Stock{/t}: <span>({$part->get_unit($part->get('Part Current On Hand Stock'))})</span></td>
						<td class="stock aright" id="stock">{$part->get('Part Current On Hand Stock')}</td>
					</tr>
					<tr>
						<td class="aright" colspan="2" style="padding-top:0;color:#777;font-size:90%"><b>{$part->get('Part Current Stock')}</b><b>-[{$part->get('Part Current Stock Picked')}]</b> -({$part->get('Part Current Stock In Process')}) &rarr; {$part->get('Current Stock Available')}</td>
					</tr>
					<tr>
						<td style="{if $part->get('Part XHTML Available For Forecast')==''}display:none{/if}">{t}Available for{/t}:</td>
						<td class="stock aright">{$part->get('Part XHTML Available For Forecast')}</td>
					</tr>
					{foreach from=$part->get_next_shipments() item=shipments } 
					<tr>
						<td rowspan="2">{t}Next shipment{/t}:</td>
						<td>{$data.next_buy}</td>
					</tr>
					<tr>
						<td class="noborder">{$data.nextbuy_when}</td>
						{/foreach} 
					</tr>
				</table>
				{t}Locations{/t}: 
				<table border="0" id="part_locations" class="show_info_product" style="width:270px;margin-top:0px">
					{foreach from=$part->get_locations(true) item=location name=foo } 
					<tr id="part_location_tr_{$location.PartSKU}_{$location.LocationKey}">
						<td><a href="location.php?id={$location.LocationKey}">{$location.LocationCode} </a> 
						<img style="{if $modify_stock}cursor:pointer{/if}"  sku_formated="{$part->get_sku()}" location="{$location.LocationCode}" id="part_location_can_pick_{$location.PartSKU}_{$location.LocationKey}"  can_pick="{if $location.CanPick=='Yes'}No{else}Yes{/if}" src="{if $location.CanPick=='Yes'}art/icons/basket.png{else}art/icons/box.png{/if}"  alt="can_pick" onclick="save_can_pick({$location.PartSKU},{$location.LocationKey})" /> </td>
						
						<td 
						id="picking_limit_quantities_{$location.PartSKU}_{$location.LocationKey}" 
						min_value='{if isset($location.MinimumQuantity)}{$location.MinimumQuantity}{/if}' 
						max_value='{if isset($location.MaximumQuantity)}{$location.MaximumQuantity}{/if}'
						style="cursor:pointer; color:#808080;{if $location.CanPick =='No'}display:none{/if}"
						onClick="show_picking_limit_quantities(this, {$location.LocationKey} )"
						>
						{literal}{{/literal}<span id="picking_limit_min_{$location.PartSKU}_{$location.LocationKey}">{if isset($location.MinimumQuantity)}{$location.MinimumQuantity}{else}{t}?{/t}{/if}</span>,<span id="picking_limit_max_{$location.PartSKU}_{$location.LocationKey}">{if isset($location.MaximumQuantity)}{$location.MaximumQuantity}{else}{t}?{/t}{/if}</span>{literal}}{/literal}
						</td>
					
						<td id="store_limit_quantities_{$location.PartSKU}_{$location.LocationKey}" style="cursor:pointer; color:#808080;{if $location.CanPick =='Yes'}display:none{/if}" onClick="show_move_quantities(this, {$location.LocationKey}, {if isset($location.MovingQty)}{$location.MovingQty}{else}0{/if})" >[{if isset($location.MovingQty)}{$location.MovingQty}{else}{t}?{/t}{/if}]</td>
						
						<td class="quantity" id="part_location_quantity_{$location.PartSKU}_{$location.LocationKey}" quantity="{$location.QuantityOnHand}">{$location.FormatedQuantityOnHand}</td>
						<td style="{if !$modify_stock}display:none{/if}" class="button"><img style="cursor:pointer" id="part_location_audit_{$location.PartSKU}_{$location.LocationKey}" src="art/icons/note_edit.png" title="{t}audit{/t}" alt="{t}audit{/t}" onclick="audit({$location.PartSKU},{$location.LocationKey})" /></td>
						<td style="{if !$modify_stock}display:none{/if}" class="button"> <img style="cursor:pointer" sku_formated="{$part->get_sku()}" location="{$location.LocationCode}" id="part_location_add_stock_{$location.PartSKU}_{$location.LocationKey}" src="art/icons/lorry.png" title="{t}add stock{/t}" alt="{t}add stock{/t}" onclick="add_stock_part_location({$location.PartSKU},{$location.LocationKey})" /></td>
						<td style="{if !$modify_stock}display:none{/if}" class="button"> <img style="{if $location.QuantityOnHand!=0}display:none;{/if}cursor:pointer" sku_formated="{$part->get_sku()}" location="{$location.LocationCode}" id="part_location_delete_{$location.PartSKU}_{$location.LocationKey}" src="art/icons/cross_bw.png" title="{t}delete{/t}" alt="{t}delete{/t}" onclick="delete_part_location({$location.PartSKU},{$location.LocationKey})" /><img style="{if $location.QuantityOnHand==0}display:none;{/if}cursor:pointer" id="part_location_lost_items_{$location.PartSKU}_{$location.LocationKey}" src="art/icons/package_delete.png" title="{t}lost{/t}" alt="{t}lost{/t}" onclick="lost({$location.PartSKU},{$location.LocationKey})" /></td>
						<td style="{if !$modify_stock}display:none{/if}" class="button"><img style="cursor:pointer" sku_formated="{$part->get_sku()}" location="{$location.LocationCode}" id="part_location_move_items_{$location.PartSKU}_{$location.LocationKey}" src="art/icons/package_go.png" title="{t}move{/t}" alt="{t}move{/t}" onclick="move({$location.PartSKU},{$location.LocationKey})" /></td>
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
			<div style="width:280px;float:left;margin-left:20px">
				<table class="show_info_product discontinued" style="margin:0;padding:5px 10px;width:100%;">
					<tr>
						<td style="font-weight:800;font-size:160%;text-align:center">{t}No longer keeped in Warehouse{/t}</td>
					</tr>
				</table>
			</div>
			{/if} 
			<div style="clear:both">
			</div>
		</div>
	</div>
	<div style="clear:both">
	</div>
	<ul class="tabs" id="chooser_ul" style="clear:both;margin-top:15px">
		<li><span class="item {if $view=='description'}selected{/if}" id="description"> <span> {t}Description{/t}</span></span></li>
		<li><span class="item {if $view=='sales'}selected{/if}" id="sales"> <span> {t}Sales{/t}</span></span></li>
		<li><span class="item {if $view=='transactions'}selected{/if}" id="transactions"> <span> {t}Stock Transactions{/t}</span></span></li>
		<li><span class="item {if $view=='history'}selected{/if}" id="history"> <span> {t}Stock History{/t}</span></span></li>
		<li><span class="item {if $view=='delivery_notes'}selected{/if}" id="delivery_notes"> <span> {t}Delivery Notes{/t}</span></span></li>
		<li><span class="item {if $view=='purchase_orders'}selected{/if}" id="purchase_orders"> <span> {t}Purchase Orders{/t}</span></span></li>
	</ul>
	<div style="clear:both;width:100%;border-bottom:1px solid #ccc">
	</div>
	<div id="block_transactions" class="block data_table" style="{if $view!='transactions'}display:none;{/if}clear:both;margin-top:20px;;padding:0 20px 20px 30px">
		<span class="clean_table_title">{t}Part Stock Transactions{/t}</span> 
		<div id="table_type" class="table_type">
			<div style="font-size:90%" id="transaction_chooser">
				<span style="float:right;margin-left:20px" class="table_type transaction_type state_details {if $transaction_type=='all_transactions'}selected{/if}" id="restrictions_all_transactions" table_type="all_transactions">{t}All{/t} ({$transactions.all_transactions})</span> <span style="float:right;margin-left:20px" class="table_type transaction_type state_details {if $transaction_type=='oip_transactions'}selected{/if}" id="restrictions_oip_transactions" table_type="oip_transactions">{t}OIP{/t} ({$transactions.oip_transactions})</span> <span style="float:right;margin-left:20px" class="table_type transaction_type state_details {if $transaction_type=='out_transactions'}selected{/if}" id="restrictions_out_transactions" table_type="out_transactions">{t}Out{/t} ({$transactions.out_transactions})</span> <span style="float:right;margin-left:20px" class="table_type transaction_type state_details {if $transaction_type=='in_transactions'}selected{/if}" id="restrictions_in_transactions" table_type="in_transactions">{t}In{/t} ({$transactions.in_transactions})</span> <span style="float:right;margin-left:20px" class="table_type transaction_type state_details {if $transaction_type=='audit_transactions'}selected{/if}" id="restrictions_audit_transactions" table_type="audit_transactions">{t}Audits{/t} ({$transactions.audit_transactions})</span> <span style="float:right;margin-left:20px" class="table_type transaction_type state_details {if $transaction_type=='move_transactions'}selected{/if}" id="restrictions_move_transactions" table_type="move_transactions">{t}Movements{/t} ({$transactions.move_transactions})</span> 
			</div>
		</div>
		<div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999;margin-bottom:10px">
		</div>
		{include file='table_splinter.tpl' table_id=1 filter_name=$filter_name1 filter_value=$filter_value1 } 
		<div style="font-size:85%" id="table1" class="data_table_container dtable btable ">
		</div>
	</div>
	<div id="block_history" class="block data_table" style="{if $view!='history'}display:none;{/if}clear:both;margin-top:20px;;padding:0 20px 30px 20px ">
		<span class="clean_table_title">{t}Stock History Chart{/t} <img id="hide_stock_history_chart" alt="{t}hide{/t}" title="{t}Hide Chart{/t}" style="{if !$show_stock_history_chart}display:none;{/if}cursor:pointer;vertical-align:middle;position:relative;bottom:1px" src="art/icons/hide_button.png" /> <img id="show_stock_history_chart" alt="{t}show{/t}" title="{t}Show Chart{/t}" style="{if $show_stock_history_chart}display:none;{/if}cursor:pointer;vertical-align:middle" src="art/icons/show_button.png" /> </span> 
	<div  class="buttons small"><button id="change_plot">&#x21b6 <span id="change_plot_label_value" style="{if $stock_history_chart_output!='stock'}display:none{/if}">{t}Stock{/t}</span><span id="change_plot_label_stock" style="{if $stock_history_chart_output!='value'}display:none{/if}">{t}Value{/t}</span></button></div>
	<div id="stock_history_plot" style="{if !$show_stock_history_chart}display:none;{/if}">
			<strong>You need to upgrade your Flash Player</strong> 
		</div>
<script type="text/javascript">
		// <![CDATA[
		var so = new SWFObject("external_libs/amstock/amstock/amstock.swf", "amstock", "930", "500", "8", "#FFFFFF");
		so.addVariable("path", "");
		so.addVariable("settings_file", encodeURIComponent("conf/plot_general_candlestick.xml.php?tipo=part_stock_history&output={$stock_history_chart_output}&parent=part&parent_key={$part->sku}"));
		so.addVariable("preloader_color", "#999999");
		so.write("stock_history_plot");
		// ]]>
	</script> <span class="clean_table_title" style="clear:both;margin-top:20px">{t}Stock History{/t} 
		<div id="stock_history_type" style="display:inline;color:#aaa">
			<span id="stock_history_type_day" table_type="day" style="margin-left:10px;font-size:80%;" class="table_type state_details {if $stock_history_type=='day'}selected{/if}">{t}Daily{/t}</span> <span id="stock_history_type_week" table_type="week" style="margin-left:5px;font-size:80%;" class="table_type state_details {if $stock_history_type=='week'}selected{/if}">{t}Weekly{/t}</span> <span id="stock_history_type_month" table_type="month" style="margin-left:5px;font-size:80%;" class="table_type state_details {if $stock_history_type=='month'}selected{/if}">{t}Monthly{/t}</span> 
		</div>
		</span> 
		<div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999;margin-bottom:10px">
		</div>
		{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0 no_filter=1 } 
		<div id="table0" style="font-size:85%" class="data_table_container dtable btable ">
		</div>
	
	
	</div>
	<div id="block_description" class="block data_table" style="{if $view!='description'}display:none;{/if}clear:both;margin-top:20px;;padding:0 20px 30px 20px ">
		<div style="width:340px;float:left;margin-left:10px;">
			<table class="show_info_product">
				<td class="aright"> 
				<tr style="display:none">
					<td>{t}Status{/t}:</td>
					<td>{$part->get('Part Status')}</td>
				</tr>
				<tr style="display:none">
					<td>{t}Availability{/t}:</td>
					<td>{$part->get('Part Available')}</td>
				</tr>
				<tr>
					<td>{t}Keeping since{/t}:</td>
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
					<td>{$part->get('Cost')}</td>
				</tr>
				{/if} {foreach from=$show_case key=name item=value} 
				<tr>
					<td>{$name}:</td>
					<td>{$value}</td>
				</tr>
				{/foreach} 
			</table>
		</div>
		<div style="float:left;margin-left:20px;width:450px">
			<table class="show_info_product">
				<tr>
					<td>{t}Weight{/t}:</td>
					<td>{$part->get('Weight')}</td>
				</tr>
				<tr>
					<td>{t}Volume{/t}:</td>
					<td>{$part->get('Volume')}</td>
				</tr>
				<tr>
					<td>{t}Commodity Code{/t}:</td>
					<td>{$part->get('Part Tariff Code')}</td>
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
			<div style="margin-top:5px">
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
	</div>
	<div id="block_sales" class="block data_table" style="{if $view!='sales'}display:none;{/if}clear:both;margin-top:20px;;padding:0 20px 30px 20px ">
		<div style="margin-top:20px;width:900px">
			<div class="clusters">
					<div class="buttons small left cluster">
					<button class="{if $parts_period=='all'}class=&quot;selected&quot;{/if}" period="all" id="parts_period_all" style="padding-left:7px;padding-right:7px">{t}All{/t}</button>
				</div>
				<div class="buttons small left cluster">				<tr>
					<button class="{if $parts_period=='yeartoday'}selected{/if}" period="yeartoday" id="parts_period_yeartoday">{t}YTD{/t}</button>
					<button class="{if $parts_period=='monthtoday'}selected{/if}" period="monthtoday" id="parts_period_monthtoday">{t}MTD{/t}</button>
					<button class="{if $parts_period=='weektoday'}selected{/if}" period="weektoday" id="parts_period_weektoday">{t}WTD{/t}</button>
					<button class="{if $parts_period=='today'}selected{/if}" period="today" id="parts_period_today">{t}Today{/t}</button>
					</div>
					
						<div class="buttons small left cluster">				<tr>
					<button class="{if $parts_period=='yesterday'}selected{/if}" period="yesterday" id="parts_period_yesterday">{t}Yesterday{/t}</button>
					<button class="{if $parts_period=='last_w'}selected{/if}" period="last_w" id="parts_period_last_w">{t}Last Week{/t}</button>
					<button class="{if $parts_period=='last_m'}selected{/if}" period="last_m" id="parts_period_last_m">{t}Last Month{/t}</button>
					</div>
					
					<div class="buttons small left cluster">				<tr>
					<button class="{if $parts_period=='three_year'}selected{/if}" period="three_year" id="parts_period_three_year">{t}3Y{/t}</button>
					<button class="{if $parts_period=='year'}selected{/if}" period="year" id="parts_period_year">{t}1Yr{/t}</button>
					<button class="{if $parts_period=='six_month'}selected{/if}" period="six_month" id="parts_period_six_month">{t}6M{/t}</button>
					<button class="{if $parts_period=='quarter'}selected{/if}" period="quarter" id="parts_period_quarter">{t}1Qtr{/t}</button>
					<button class="{if $parts_period=='month'}selected{/if}" period="month" id="parts_period_month">{t}1M{/t}</button>
					<button class="{if $parts_period=='ten_day'}selected{/if}" period="ten_day" id="parts_period_ten_day">{t}10D{/t}</button>
					<button class="{if $parts_period=='week'}selected{/if}" period="week" id="parts_period_week">{t}1W{/t}</button>
				
				</div>

			<div style="clear:both"></div>
	
	</div>
			<div style="margin-top:20px">
				<div style="width:200px;float:left;margin-left:0px;">
				<table style="clear:both" class="show_info_product">
					
					{foreach from=$period_tags item=period }
					<tbody id="info_{$period.key}" style="{if $parts_period!=$period.key}display:none{/if}">
						<tr>
							<td>{t}Sales{/t}:</td>
							<td class="aright">{$part->get_period($period.db,"Acc Sold Amount")}</td>
						</tr>
						<tr>
							<td>{t}Profit{/t}:</td>
							<td class="aright">{$part->get_period($period.db,'Acc Profit')}</td>
						</tr>
						<tr>
							<td>{t}Margin{/t}:</td>
							<td class="aright">{$part->get_period($period.db,'Acc Margin')}</td>
						</tr>
						<tr>
							<td>{t}GMROI{/t}:</td>
							<td class="aright">{$part->get_period($period.db,'Acc GMROI')}</td>
						</tr>
					</tbody>
					{/foreach}
		
				</table>
			</div>
			<div style="float:left;margin-left:20px">
				<table style="width:200px;clear:both" class="show_info_product">
				{foreach from=$period_tags item=period }
					<tbody id="info2_{$period.key}" style="{if $parts_period!=$period.key}display:none{/if}">
						{if $part->get_period($period.db,'Acc No Supplied')!=0} 
						<tr>
							<td>{t}Required{/t}:</td>
							<td class="aright">{$part->get_period($period.db,'Acc Required')}</td>
						</tr>
						<tr>
							<td>{t}No Supplied{/t}:</td>
							<td class="aright error">{$part->get_period($period.db,'Acc No Supplied')}</td>
						</tr>
						{/if} 
						<tr>
							<td>{t}Sold{/t}:</td>
							<td class="aright">{$part->get_period($period.db,'Acc Sold')}</td>
						</tr>
						{if $part->get_period($period.db,'Acc Given')!=0} 
						<tr>
							<td>{t}Given for free{/t}:</td>
							<td class="aright">{$part->get_period($period.db,'Acc Given')}</td>
						</tr>
						{/if} {if $part->get_period($period.db,'Acc Given')!=0} 
						<tr>
							<td>{t}Broken{/t}:</td>
							<td class="aright">{$part->get('Total Acc Broken')}</td>
						</tr>
						{/if} {if $part->get_period($period.db,'Acc Given')!=0} 
						<tr>
							<td>{t}Lost{/t}:</td>
							<td class="aright">{ $part->get_period($period.db,'Acc Lost')}</td>
						</tr>
						{/if} 
					</tbody>
					{/foreach}
				</table>
			</div>
		</div>	
		</div>
		
		
		<div id="sales_plots" style="clear:both">
				<ul class="tabs" id="chooser_ul" style="margin-top:25px">
					<li> <span class="item {if $plot_tipo=='sales'}selected{/if}" onclick="change_plot(this)" id="plot_sales" tipo="sales"> <span>{t}Part Sales{/t}</span> </span> </li>
				</ul>
<script type="text/javascript" src="external_libs/amstock/amstock/swfobject.js"></script> 
				<div id="plot_part_sales" style="clear:both;border:1px solid #ccc">
					<div id="single_data_set">
						<strong>{t}You need to upgrade your Flash Player{/t}</strong> 
					</div>
				</div>
<script type="text/javascript">
		// <![CDATA[
		var so = new SWFObject("external_libs/amstock/amstock/amstock.swf", "amstock", "905", "500", "8", "#FFFFFF");
		so.addVariable("path", "");
		so.addVariable("settings_file", encodeURIComponent("conf/plot_asset_sales.xml.php?tipo=part_sales&part_sku={$part->sku}"));
		so.addVariable("preloader_color", "#999999");
		so.write("plot_part_sales");
		// ]]>
	</script> 
				<div style="clear:both">
				</div>
			</div>
		
		
	</div>
	<div id="block_purchase_orders" class="block data_table" style="{if $view!='puchase_orders'}display:none;{/if}clear:both;margin-top:20px;;padding:0 20px 30px 20px ">
	</div>
	<div id="block_delivery_notes" class="block data_table" style="{if $view!='delivery_notes'}display:none;{/if}clear:both;margin-top:20px;;padding:0 20px 30px 20px ">
		{include file='table_splinter.tpl' table_id=2 filter_name=$filter_name2 filter_value=$filter_value2 no_filter=5 } 
		<div class="clean_table_controls">
			<div>
				<span style="margin:0 5px" id="paginator2"></span> 
			</div>
		</div>
		<div id="table2" style="font-size:85%" class="data_table_container dtable btable ">
		</div>
	</div>
</div>
</div>
</div>
{include file='footer.tpl'} {include file='stock_splinter.tpl'} 
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
<div id="dialog_qty" style="padding:10px">
	<table style="margin:10px">
		<tr>
			<td>{t}Min Qty:{/t}</td>
			<td>
			<input type="text" value="" id="min_qty" />
			</td>
		</tr>
		<tr>
			<td>{t}Max Qty:{/t}</td>
			<td>
			<input type="text" value="" id="max_qty" />
			</td>
		</tr>
		<tr>
			<td colspan="2"> 
			<div class="buttons" style="margin-top:10px">
				<button class="positive" onclick="save_picking_quantity_limits()">{t}Save{/t}</button> <button class="negative" id="close_qty">{t}Cancel{/t}</button> 
			</div>
			</td>
		</tr>
	</table>
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

<div id="dialog_move_qty" style="padding:10px">
	<table style="margin:10px">
		<tr>
			<td>{t}Moving Qty:{/t}</td><td><input type="text" value="" id="move_qty_part"/></td>
		</tr>
		<tr>
			<td colspan="2"> 
			<div class="buttons" style="margin-top:10px">
				<button class="positive" onclick="save_move_qty()">{t}Save{/t}</button> <button class="negative" id="close_move_qty">{t}Cancel{/t}</button> 
			</div>
			</td>
		</tr>	

	</table>

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
				<button style="float:none;margin:0px auto;min-width:120px" onclick="change_plot('stock')"> {t}Stock{/t}</button> 
				<button style="float:none;margin:0px auto;min-width:120px" onclick="change_plot('value')"> {t}Value{/t}</button> 
			</div>
			</td>
		</tr>
		
	</table>
</div>