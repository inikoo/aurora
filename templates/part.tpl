{include file='header.tpl'} 
<div id="bd" class="{if $part->get('Part Available')=='No' or $part->get('Part Status')=='Not In Use' }discontinued{/if}" style="padding:0;">
	<input type="hidden" id="part_sku" value="{$part->sku}"/>
	<input type="hidden" id="page_name" value="part"/>
	<div style="padding: 0 20px;">
	<input type="hidden" id="modify_stock" value="{$modify_stock}"/>
		{include file='locations_navigation.tpl'} 
		<div class="branch">
			<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home"/></a>&rarr;  {if $user->get_number_warehouses()>1}<a href="warehouses.php">{t}Warehouses{/t}</a> &rarr; {/if}<a href="warehouse_parts.php?warehouse_id={$warehouse->id}">{t}Inventory{/t}</a> &rarr; {$part->get_sku()}</span> 
		</div>
		<div class="top_page_menu">
			<div class="buttons" style="float:right">
				{if isset($next)}<img class="next" onmouseover="this.src='art/next_button.gif'" onmouseout="this.src='art/next_button.png'" title="{$next.title}" onclick="window.location='{$next.link}'" src="art/next_button.png" alt="{t}Next{/t}" />{/if} 
				{if $modify} <button onclick="window.location='edit_part.php?id={$part->sku}'"><img src="art/icons/cog.png" alt=""> {t}Edit Part{/t}</button> {/if} 
			</div>
			<div class="buttons" style="float:left">
				{if isset($prev)}<img class="previous" onmouseover="this.src='art/previous_button.gif'" onmouseout="this.src='art/previous_button.png'" title="{$prev.title}" onclick="window.location='{$prev.link}'" src="art/previous_button.png" alt="{t}Previous{/t}" />{/if} 
				
			</div>
			<div style="clear:both">
			</div>
		</div>
		<div style="clear:left">
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
						<img src="{$part->get('Part Main Image')}" style="vertical-align:middle;display:block;;width:190px" valign="center" border="1" id="image" alt="{t}Image{/t}" /> 
					</div>
				</div>
				<div style="width:160px;margin:auto;padding-top:5px">
					{foreach from=$part->get_images_slidesshow() item=image name=foo} {if $image.is_principal==0} <img style="float:left;border:1px solid#ccc;padding:2px;margin:2px;cursor:pointer" src="{$image.thumbnail_url}" title="" alt="" /> {/if} {/foreach} 
				</div>
			</div>
			<div style="width:340px;float:left;margin-left:10px;">
				<table class="show_info_product">
					<td class="aright"> 
					<tr>
						<td>{t}Status{/t}:</td>
						<td>{$part->get('Part Status')}</td>
					</tr>
					<tr>
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
					<tr>
						<td>{t}Supplied by{/t}:</td>
						<td>{$part->get('Part XHTML Currently Supplied By')}</td>
					</tr>
					<tr>
						<td>{t}Cost{/t}:</td>
						<td>{$part->get('Cost')}</td>
					</tr>
					{foreach from=$show_case key=name item=value} 
					<tr>
						<td>{$name}:</td>
						<td>{$value}</td>
					</tr>
					{/foreach} 
				</table>
			</div>
			<div style="width:280px;float:left;margin-left:20px">
				<table class="show_info_product" style="width:260px">
					<tr>
						<td>{t}Stock{/t}:<br>({$part->get_unit($part->get('Part Current Stock'))})</td>
						<td class="stock aright" id="stock">{$part->get('Part Current Stock')}</td>
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
				<table border="0" id="part_locations" class="show_info_product" style="width:260px;margin-top:0px">
					{foreach from=$part->get_locations(true) item=location name=foo } 
					<tr id="part_location_tr_{$location.PartSKU}_{$location.LocationKey}">
						<td><a href="location.php?id={$location.LocationKey}">{$location.LocationCode} </a> 
						<img style="{if $modify_stock}cursor:pointer{/if}"  sku_formated="{$part->get_sku()}" location="{$location.LocationCode}" id="part_location_can_pick_{$location.PartSKU}_{$location.LocationKey}"  can_pick="{if $location.CanPick=='Yes'}No{else}Yes{/if}" src="{if $location.CanPick=='Yes'}art/icons/basket.png{else}art/icons/box.png{/if}"  alt="can_pick" onclick="save_can_pick({$location.PartSKU},{$location.LocationKey})" /> </td>
						<td class="quantity" id="part_location_quantity_{$location.PartSKU}_{$location.LocationKey}" quantity="{$location.QuantityOnHand}">{$location.FormatedQuantityOnHand}</td>
						<td style="{if !$modify_stock}display:none{/if}" class="button"><img style="cursor:pointer" id="part_location_audit_{$location.PartSKU}_{$location.LocationKey}" src="art/icons/note_edit.png" title="{t}audit{/t}" alt="{t}audit{/t}" onclick="audit({$location.PartSKU},{$location.LocationKey})" /></td>
						<td style="{if !$modify_stock}display:none{/if}" class="button"> <img style="cursor:pointer" sku_formated="{$part->get_sku()}" location="{$location.LocationCode}" id="part_location_add_stock_{$location.PartSKU}_{$location.LocationKey}" src="art/icons/lorry.png" title="{t}add stock{/t}" alt="{t}add stock{/t}" onclick="add_stock_part_location({$location.PartSKU},{$location.LocationKey})" /></td>
						<td style="{if !$modify_stock}display:none{/if}" class="button"> <img style="{if $location.QuantityOnHand!=0}display:none;{/if}cursor:pointer" sku_formated="{$part->get_sku()}" location="{$location.LocationCode}" id="part_location_delete_{$location.PartSKU}_{$location.LocationKey}" src="art/icons/cross_bw.png" title="{t}delete{/t}" alt="{t}delete{/t}" onclick="delete_part_location({$location.PartSKU},{$location.LocationKey})" /><img style="{if $location.QuantityOnHand==0}display:none;{/if}cursor:pointer" id="part_location_lost_items_{$location.PartSKU}_{$location.LocationKey}" src="art/icons/package_delete.png" title="{t}lost{/t}" alt="{t}lost{/t}" onclick="lost({$location.PartSKU},{$location.LocationKey})" /></td>
						<td style="{if !$modify_stock}display:none{/if}" class="button"><img style="cursor:pointer" sku_formated="{$part->get_sku()}" location="{$location.LocationCode}" id="part_location_move_items_{$location.PartSKU}_{$location.LocationKey}" src="art/icons/package_go.png" title="{t}move{/t}" alt="{t}move{/t}" onclick="move({$location.PartSKU},{$location.LocationKey})" /></td>
					</tr>
					{/foreach} 
					<tr style="{if !$modify_stock}display:none{/if}">
						<td colspan="6"> 
						<div id="add_location_button" class="buttons small left">
							<button onclick="add_location({$part->sku})">{t}Add Location{/t}</button> 
						</div>
						</td>
					</tr>
				</table>
			</div>
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
		<div class="clean_table_controls">
			<div>
				<span style="margin:0 5px" id="paginator1"></span> 
			</div>
		</div>
		<div style="font-size:85%" id="table1" class="data_table_container dtable btable ">
		</div>
	</div>
	<div id="block_history" class="block data_table" style="{if $view!='history'}display:none;{/if}clear:both;margin-top:20px;;padding:0 20px 30px 20px ">
		<span class="clean_table_title">{t}Stock History Chart{/t} <img id="hide_stock_history_chart" alt="{t}hide{/t}" title="{t}Hide Chart{/t}" style="{if !$show_stock_history_chart}display:none;{/if}cursor:pointer;vertical-align:middle;" src="art/icons/hide_button.png" /> <img id="show_stock_history_chart" alt="{t}show{/t}" title="{t}Show Chart{/t}" style="{if $show_stock_history_chart}display:none;{/if}cursor:pointer;vertical-align:middle" src="art/icons/show_button.png" /> </span> 
		<div id="stock_history_plot" style="{if !$show_stock_history_chart}display:none;{/if}">
			<strong>You need to upgrade your Flash Player</strong> 
		</div>
<script type="text/javascript">
		// <![CDATA[
		var so = new SWFObject("external_libs/amstock/amstock/amstock.swf", "amstock", "930", "500", "8", "#FFFFFF");
		so.addVariable("path", "");
		so.addVariable("settings_file", encodeURIComponent("conf/plot_general_candlestick.xml.php?tipo=part_stock_history&sku={$part->sku}"));
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
		<div class="clean_table_controls">
			<div>
				<span style="margin:0 5px" id="paginator0"></span> 
			</div>
		</div>
		<div id="table0" style="font-size:85%" class="data_table_container dtable btable ">
		</div>
	</div>
	<div id="block_description" class="block data_table" style="{if $view!='description'}display:none;{/if}clear:both;margin-top:20px;;padding:0 20px 30px 20px ">
		<div style="float:left;width:450px">
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
	<div id="block_sales" class="block ata_table" style="{if $view!='sales'}display:none;{/if}clear:both;margin-top:20px;;padding:0 20px 30px 20px ">
		<div style="margin-top:20px;width:900px">
			<table id="period_options" style="float:left;margin:0 0 20px 10px ;padding:0 0  0px 0" class="options_mini">
				<tr>
					<td class="{if $parts_period=='all'}class=&quot;selected&quot;{/if}" period="all" id="parts_period_all" style="padding-left:7px;padding-right:7px">{t}All{/t}</td>
				</tr>
			</table>
			<table id="period_options" style="float:left;margin:0 0 20px 10px ;padding:0 0  0px 0" class="options_mini">
				<tr>
					<td class="{if $parts_period=='three_year'}selected{/if}" period="three_year" id="parts_period_three_year">{t}3Y{/t}</td>
					<td class="{if $parts_period=='year'}selected{/if}" period="year" id="parts_period_year">{t}1Yr{/t}</td>
					<td class="{if $parts_period=='six_month'}selected{/if}" period="six_month" id="parts_period_six_month">{t}6M{/t}</td>
					<td class="{if $parts_period=='quarter'}selected{/if}" period="quarter" id="parts_period_quarter">{t}1Qtr{/t}</td>
					<td class="{if $parts_period=='month'}selected{/if}" period="month" id="parts_period_month">{t}1M{/t}</td>
					<td class="{if $parts_period=='ten_day'}selected{/if}" period="ten_day" id="parts_period_ten_day">{t}10D{/t}</td>
					<td class="{if $parts_period=='week'}selected{/if}" period="week" id="parts_period_week">{t}1W{/t}</td>
				</tr>
			</table>
			<table id="period_options" style="float:left;margin:0 0 20px 10px ;padding:0 0  0px 0" class="options_mini">
				<tr>
					<td class="{if $parts_period=='yeartoday'}selected{/if}" period="yeartoday" id="parts_period_yeartoday">{t}YTD{/t}</td>
					<td class="{if $parts_period=='monthtoday'}selected{/if}" period="monthtoday" id="parts_period_monthtoday">{t}MTD{/t}</td>
					<td class="{if $parts_period=='weektoday'}selected{/if}" period="weektoday" id="parts_period_weektoday">{t}WTD{/t}</td>
					<td class="{if $parts_period=='today'}selected{/if}" period="today" id="parts_period_today">{t}Today{/t}</td>
				</tr>
			</table>
			<div style="clear:both;width:200px;float:left;margin-left:10px">
				<table style="clear:both" class="show_info_product">
					<tbody id="info_all" style="{if $parts_period!='all'}display:none{/if}">
						<tr>
							<td>{t}Sales{/t}:</td>
							<td class="aright">{$part->get('Total Acc Sold Amount')}</td>
						</tr>
						<tr>
							<td>{t}Profit{/t}:</td>
							<td class="aright">{$part->get('Total Acc Profit')}</td>
						</tr>
						<tr>
							<td>{t}Margin{/t}:</td>
							<td class="aright">{$part->get('Total Acc Margin')}</td>
						</tr>
						<tr>
							<td>{t}GMROI{/t}:</td>
							<td class="aright">{$part->get('Total Acc GMROI')}</td>
						</tr>
					</tbody>
					<tbody id="info_three_year" style="{if $parts_period!='three_year'}display:none{/if}">
						<tr>
							<td>{t}Sales{/t}:</td>
							<td class="aright">{$part->get('3 Year Acc Sold Amount')}</td>
						</tr>
						<tr>
							<td>{t}Profit{/t}:</td>
							<td class="aright">{$part->get('3 Year Acc Profit')}</td>
						</tr>
						<tr>
							<td>{t}Margin{/t}:</td>
							<td class="aright">{$part->get('3 Year Acc Margin')}</td>
						</tr>
						<tr>
							<td>{t}GMROI{/t}:</td>
							<td class="aright">{$part->get('3 Year Acc GMROI')}</td>
						</tr>
					</tbody>
					<tbody id="info_year" style="{if $parts_period!='year'}display:none{/if}">
						<tr>
							<td>{t}Sales{/t}:</td>
							<td class="aright">{$part->get('1 Year Acc Sold Amount')}</td>
						</tr>
						<tr>
							<td>{t}Profit{/t}:</td>
							<td class="aright">{$part->get('1 Year Acc Profit')}</td>
						</tr>
						<tr>
							<td>{t}Margin{/t}:</td>
							<td class="aright">{$part->get('1 Year Acc Margin')}</td>
						</tr>
						<tr>
							<td>{t}GMROI{/t}:</td>
							<td class="aright">{$part->get('1 Year Acc GMROI')}</td>
						</tr>
					</tbody>
					<tbody id="info_six_month" style="{if $parts_period!='six_month'}display:none{/if}">
						<tr>
							<td>{t}Sales{/t}:</td>
							<td class="aright">{$part->get('6 Month Acc Sold Amount')}</td>
						</tr>
						<tr>
							<td>{t}Profit{/t}:</td>
							<td class="aright">{$part->get('6 Month Acc Profit')}</td>
						</tr>
						<tr>
							<td>{t}Margin{/t}:</td>
							<td class="aright">{$part->get('6 Month Acc Margin')}</td>
						</tr>
						<tr>
							<td>{t}GMROI{/t}:</td>
							<td class="aright">{$part->get('6 Month Acc GMROI')}</td>
						</tr>
					</tbody>
					<tbody id="info_quarter" style="{if $parts_period!='quarter'}display:none{/if}">
						<tr>
							<td>{t}Sales{/t}:</td>
							<td class="aright">{$part->get('1 Quarter Acc Sold Amount')}</td>
						</tr>
						<tr>
							<td>{t}Profit{/t}:</td>
							<td class="aright">{$part->get('1 Quarter Acc Profit')}</td>
						</tr>
						<tr>
							<td>{t}Margin{/t}:</td>
							<td class="aright">{$part->get('1 Quarter Acc Margin')}</td>
						</tr>
						<tr>
							<td>{t}GMROI{/t}:</td>
							<td class="aright">{$part->get('1 Quarter Acc GMROI')}</td>
						</tr>
					</tbody>
					<tbody id="info_month" style="{if $parts_period!='month'}display:none{/if}">
						<tr>
							<td>{t}Sales{/t}:</td>
							<td class="aright">{$part->get('1 Month Acc Sold Amount')}</td>
						</tr>
						<tr>
							<td>{t}Profit{/t}:</td>
							<td class="aright">{$part->get('1 Month Acc Profit')}</td>
						</tr>
						<tr>
							<td>{t}Margin{/t}:</td>
							<td class="aright">{$part->get('1 Month Acc Margin')}</td>
						</tr>
						<tr>
							<td>{t}GMROI{/t}:</td>
							<td class="aright">{$part->get('1 Month Acc GMROI')}</td>
						</tr>
					</tbody>
					<tbody id="info_ten_day" style="{if $parts_period!='ten_day'}display:none{/if}">
						<tr>
							<td>{t}Sales{/t}:</td>
							<td class="aright">{$part->get('10 Day Acc Sold Amount')}</td>
						</tr>
						<tr>
							<td>{t}Profit{/t}:</td>
							<td class="aright">{$part->get('10 Day Acc Profit')}</td>
						</tr>
						<tr>
							<td>{t}Margin{/t}:</td>
							<td class="aright">{$part->get('10 Day Acc Margin')}</td>
						</tr>
						<tr>
							<td>{t}GMROI{/t}:</td>
							<td class="aright">{$part->get('10 Day Acc GMROI')}</td>
						</tr>
					</tbody>
					<tbody id="info_week" style="{if $parts_period!='week'}display:none{/if}">
						<tr>
							<td>{t}Sales{/t}:</td>
							<td class="aright">{$part->get('1 Week Acc Sold Amount')}</td>
						</tr>
						<tr>
							<td>{t}Profit{/t}:</td>
							<td class="aright">{$part->get('1 Week Acc Profit')}</td>
						</tr>
						<tr>
							<td>{t}Margin{/t}:</td>
							<td class="aright">{$part->get('1 Week Acc Margin')}</td>
						</tr>
						<tr>
							<td>{t}GMROI{/t}:</td>
							<td class="aright">{$part->get('1 Week Acc GMROI')}</td>
						</tr>
					</tbody>
					<tbody id="info_yeartoday" style="{if $parts_period!='yeartoday'}display:none{/if}">
						<tr>
							<td>{t}Sales{/t}:</td>
							<td class="aright">{$part->get('Year To Day Acc Sold Amount')}</td>
						</tr>
						<tr>
							<td>{t}Profit{/t}:</td>
							<td class="aright">{$part->get('Year To Day Acc Profit')}</td>
						</tr>
						<tr>
							<td>{t}Margin{/t}:</td>
							<td class="aright">{$part->get('Year To Day Acc Margin')}</td>
						</tr>
						<tr>
							<td>{t}GMROI{/t}:</td>
							<td class="aright">{$part->get('Year To Day Acc GMROI')}</td>
						</tr>
					</tbody>
					<tbody id="info_monthtoday" style="{if $parts_period!='monthtoday'}display:none{/if}">
						<tr>
							<td>{t}Sales{/t}:</td>
							<td class="aright">{$part->get('Month To Day Acc Sold Amount')}</td>
						</tr>
						<tr>
							<td>{t}Profit{/t}:</td>
							<td class="aright">{$part->get('Month To Day Acc Profit')}</td>
						</tr>
						<tr>
							<td>{t}Margin{/t}:</td>
							<td class="aright">{$part->get('Month To Day Acc Margin')}</td>
						</tr>
						<tr>
							<td>{t}GMROI{/t}:</td>
							<td class="aright">{$part->get('Month To Day Acc GMROI')}</td>
						</tr>
					</tbody>
					<tbody id="info_weektoday" style="{if $parts_period!='weektoday'}display:none{/if}">
						<tr>
							<td>{t}Sales{/t}:</td>
							<td class="aright">{$part->get('Week To Day Acc Sold Amount')}</td>
						</tr>
						<tr>
							<td>{t}Profit{/t}:</td>
							<td class="aright">{$part->get('Week To Day Acc Profit')}</td>
						</tr>
						<tr>
							<td>{t}Margin{/t}:</td>
							<td class="aright">{$part->get('v Acc Margin')}</td>
						</tr>
						<tr>
							<td>{t}GMROI{/t}:</td>
							<td class="aright">{$part->get('Week To Day Acc GMROI')}</td>
						</tr>
					</tbody>
					<tbody id="info_today" style="{if $parts_period!='today'}display:none{/if}">
						<tr>
							<td>{t}Sales{/t}:</td>
							<td class="aright">{$part->get('Today Acc Sold Amount')}</td>
						</tr>
						<tr>
							<td>{t}Profit{/t}:</td>
							<td class="aright">{$part->get('Today Acc Profit')}</td>
						</tr>
						<tr>
							<td>{t}Margin{/t}:</td>
							<td class="aright">{$part->get('Today Acc Margin')}</td>
						</tr>
						<tr>
							<td>{t}GMROI{/t}:</td>
							<td class="aright">{$part->get('Today Acc GMROI')}</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div style="float:left;margin-left:20px">
				<table style="width:200px;clear:both" class="show_info_product">
					<tbody id="info2_all" style="{if $parts_period!='all'}display:none{/if}">
						{if $part->get('Part Total Acc No Supplied')!=0} 
						<tr>
							<td>{t}Required{/t}:</td>
							<td class="aright">{$part->get('Total Acc Required')}</td>
						</tr>
						<tr>
							<td>{t}No Supplied{/t}:</td>
							<td class="aright error">{$part->get('Total Acc No Supplied')}</td>
						</tr>
						{/if} 
						<tr>
							<td>{t}Sold{/t}:</td>
							<td class="aright">{$part->get('Total Acc Sold')}</td>
						</tr>
						{if $part->get('Part Total Acc Given')!=0} 
						<tr>
							<td>{t}Given for free{/t}:</td>
							<td class="aright">{$part->get('Total Acc Given')}</td>
						</tr>
						{/if} {if $part->get('Part Total Acc Given')!=0} 
						<tr>
							<td>{t}Broken{/t}:</td>
							<td class="aright">{$part->get('Total Acc Broken')}</td>
						</tr>
						{/if} {if $part->get('Part Total Acc Given')!=0} 
						<tr>
							<td>{t}Lost{/t}:</td>
							<td class="aright">{$part->get('Total Acc Lost')}</td>
						</tr>
						{/if} 
					</tbody>
					<tbody id="info2_three_year" style="{if $parts_period!='three_year'}display:none{/if}">
						{if $part->get('Part 3 Year Acc No Supplied')!=0} 
						<tr>
							<td>{t}Required{/t}:</td>
							<td class="aright">{$part->get('3 Year Acc Required')}</td>
						</tr>
						<tr>
							<td>{t}No Supplied{/t}:</td>
							<td class="aright error">{$part->get('3 Year Acc No Supplied')}</td>
						</tr>
						{/if} 
						<tr>
							<td>{t}Sold{/t}:</td>
							<td class="aright">{$part->get('3 Year Acc Sold')}</td>
						</tr>
						{if $part->get('Part 3 Year Acc Given')!=0} 
						<tr>
							<td>{t}Given for free{/t}:</td>
							<td class="aright">{$part->get('3 Year Acc Given')}</td>
						</tr>
						{/if} {if $part->get('Part 3 Year Acc Given')!=0} 
						<tr>
							<td>{t}Broken{/t}:</td>
							<td class="aright">{$part->get('3 Year Acc Broken')}</td>
						</tr>
						{/if} {if $part->get('Part 3 Year Acc Given')!=0} 
						<tr>
							<td>{t}Lost{/t}:</td>
							<td class="aright">{$part->get('3 Year Acc Lost')}</td>
						</tr>
						{/if} 
					</tbody>
					<tbody id="info2_year" style="{if $parts_period!='year'}display:none{/if}">
						{if $part->get('Part 1 Year Acc No Supplied')!=0} 
						<tr>
							<td>{t}Required{/t}:</td>
							<td class="aright">{$part->get('1 Year Acc Required')}</td>
						</tr>
						<tr>
							<td>{t}No Supplied{/t}:</td>
							<td class="aright error">{$part->get('1 Year Acc No Supplied')}</td>
						</tr>
						{/if} 
						<tr>
							<td>{t}Sold{/t}:</td>
							<td class="aright">{$part->get('1 Year Acc Sold')}</td>
						</tr>
						{if $part->get('Part 1 Year Acc Given')!=0} 
						<tr>
							<td>{t}Given for free{/t}:</td>
							<td class="aright">{$part->get('1 Year Acc Given')}</td>
						</tr>
						{/if} {if $part->get('Part 1 Year Acc Given')!=0} 
						<tr>
							<td>{t}Broken{/t}:</td>
							<td class="aright">{$part->get('1 Year Acc Broken')}</td>
						</tr>
						{/if} {if $part->get('Part 1 Year Acc Given')!=0} 
						<tr>
							<td>{t}Lost{/t}:</td>
							<td class="aright">{$part->get('1 Year Acc Lost')}</td>
						</tr>
						{/if} 
					</tbody>
					<tbody id="info2_six_month" style="{if $parts_period!='six_month'}display:none{/if}">
						{if $part->get('Part 6 Month Acc No Supplied')!=0} 
						<tr>
							<td>{t}Required{/t}:</td>
							<td class="aright">{$part->get('6 Month Acc Required')}</td>
						</tr>
						<tr>
							<td>{t}No Supplied{/t}:</td>
							<td class="aright error">{$part->get('6 Month Acc No Supplied')}</td>
						</tr>
						{/if} 
						<tr>
							<td>{t}Sold{/t}:</td>
							<td class="aright">{$part->get('6 Month Acc Sold')}</td>
						</tr>
						{if $part->get('Part 6 Month Acc Given')!=0} 
						<tr>
							<td>{t}Given for free{/t}:</td>
							<td class="aright">{$part->get('6 Month Acc Given')}</td>
						</tr>
						{/if} {if $part->get('Part 6 Month Acc Given')!=0} 
						<tr>
							<td>{t}Broken{/t}:</td>
							<td class="aright">{$part->get('6 Month Acc Broken')}</td>
						</tr>
						{/if} {if $part->get('Part 6 Month Acc Given')!=0} 
						<tr>
							<td>{t}Lost{/t}:</td>
							<td class="aright">{$part->get('6 Month Acc Lost')}</td>
						</tr>
						{/if} 
					</tbody>
					<tbody id="info2_quarter" style="{if $parts_period!='quarter'}display:none{/if}">
						{if $part->get('Part 1 Quarter Acc No Supplied')!=0} 
						<tr>
							<td>{t}Required{/t}:</td>
							<td class="aright">{$part->get('1 Quarter Acc Required')}</td>
						</tr>
						<tr>
							<td>{t}No Supplied{/t}:</td>
							<td class="aright error">{$part->get('1 Quarter Acc No Supplied')}</td>
						</tr>
						{/if} 
						<tr>
							<td>{t}Sold{/t}:</td>
							<td class="aright">{$part->get('1 Quarter Acc Sold')}</td>
						</tr>
						{if $part->get('Part 1 Quarter Acc Given')!=0} 
						<tr>
							<td>{t}Given for free{/t}:</td>
							<td class="aright">{$part->get('1 Quarter Acc Given')}</td>
						</tr>
						{/if} {if $part->get('Part 1 Quarter Acc Given')!=0} 
						<tr>
							<td>{t}Broken{/t}:</td>
							<td class="aright">{$part->get('1 Quarter Acc Broken')}</td>
						</tr>
						{/if} {if $part->get('Part 1 Quarter Acc Given')!=0} 
						<tr>
							<td>{t}Lost{/t}:</td>
							<td class="aright">{$part->get('1 Quarter Acc Lost')}</td>
						</tr>
						{/if} 
					</tbody>
					<tbody id="info2_month" style="{if $parts_period!='month'}display:none{/if}">
						{if $part->get('Part 1 Month Acc No Supplied')!=0} 
						<tr>
							<td>{t}Required{/t}:</td>
							<td class="aright">{$part->get('1 Month Acc Required')}</td>
						</tr>
						<tr>
							<td>{t}No Supplied{/t}:</td>
							<td class="aright error">{$part->get('1 Month Acc No Supplied')}</td>
						</tr>
						{/if} 
						<tr>
							<td>{t}Sold{/t}:</td>
							<td class="aright">{$part->get('1 Month Acc Sold')}</td>
						</tr>
						{if $part->get('Part 1 Month Acc Given')!=0} 
						<tr>
							<td>{t}Given for free{/t}:</td>
							<td class="aright">{$part->get('1 Month Acc Given')}</td>
						</tr>
						{/if} {if $part->get('Part 1 Month Acc Given')!=0} 
						<tr>
							<td>{t}Broken{/t}:</td>
							<td class="aright">{$part->get('1 Month Acc Broken')}</td>
						</tr>
						{/if} {if $part->get('Part 1 Month Acc Given')!=0} 
						<tr>
							<td>{t}Lost{/t}:</td>
							<td class="aright">{$part->get('1 Month Acc Lost')}</td>
						</tr>
						{/if} 
					</tbody>
					<tbody id="info2_ten_day" style="{if $parts_period!='ten_day'}display:none{/if}">
						{if $part->get('Part 10 Day Acc No Supplied')!=0} 
						<tr>
							<td>{t}Required{/t}:</td>
							<td class="aright">{$part->get('10 Day Acc Required')}</td>
						</tr>
						<tr>
							<td>{t}No Supplied{/t}:</td>
							<td class="aright error">{$part->get('10 Day Acc No Supplied')}</td>
						</tr>
						{/if} 
						<tr>
							<td>{t}Sold{/t}:</td>
							<td class="aright">{$part->get('10 Day Acc Sold')}</td>
						</tr>
						{if $part->get('Part 10 Day Acc Given')!=0} 
						<tr>
							<td>{t}Given for free{/t}:</td>
							<td class="aright">{$part->get('10 Day Acc Given')}</td>
						</tr>
						{/if} {if $part->get('Part 10 Day Acc Given')!=0} 
						<tr>
							<td>{t}Broken{/t}:</td>
							<td class="aright">{$part->get('10 Day Acc Broken')}</td>
						</tr>
						{/if} {if $part->get('Part 10 Day Acc Given')!=0} 
						<tr>
							<td>{t}Lost{/t}:</td>
							<td class="aright">{$part->get('10 Day Acc Lost')}</td>
						</tr>
						{/if} 
					</tbody>
					<tbody id="info2_week" style="{if $parts_period!='week'}display:none{/if}">
						{if $part->get('Part 1 Week Acc No Supplied')!=0} 
						<tr>
							<td>{t}Required{/t}:</td>
							<td class="aright">{$part->get('1 Week Acc Required')}</td>
						</tr>
						<tr>
							<td>{t}No Supplied{/t}:</td>
							<td class="aright error">{$part->get('1 Week Acc No Supplied')}</td>
						</tr>
						{/if} 
						<tr>
							<td>{t}Sold{/t}:</td>
							<td class="aright">{$part->get('1 Week Acc Sold')}</td>
						</tr>
						{if $part->get('Part 1 Week Acc Given')!=0} 
						<tr>
							<td>{t}Given for free{/t}:</td>
							<td class="aright">{$part->get('1 Week Acc Given')}</td>
						</tr>
						{/if} {if $part->get('Part 1 Week Acc Given')!=0} 
						<tr>
							<td>{t}Broken{/t}:</td>
							<td class="aright">{$part->get('1 Week Acc Broken')}</td>
						</tr>
						{/if} {if $part->get('Part 1 Week Acc Given')!=0} 
						<tr>
							<td>{t}Lost{/t}:</td>
							<td class="aright">{$part->get('1 Week Acc Lost')}</td>
						</tr>
						{/if} 
					</tbody>
					<tbody id="info2_yeartoday" style="{if $parts_period!='yeartoday'}display:none{/if}">
						{if $part->get('Part Year To Day Acc No Supplied')!=0} 
						<tr>
							<td>{t}Required{/t}:</td>
							<td class="aright">{$part->get('Year To Day Acc Required')}</td>
						</tr>
						<tr>
							<td>{t}No Supplied{/t}:</td>
							<td class="aright error">{$part->get('Year To Day Acc No Supplied')}</td>
						</tr>
						{/if} 
						<tr>
							<td>{t}Sold{/t}:</td>
							<td class="aright">{$part->get('Year To Day Acc Sold')}</td>
						</tr>
						{if $part->get('Part Year To Day Acc Given')!=0} 
						<tr>
							<td>{t}Given for free{/t}:</td>
							<td class="aright">{$part->get('Year To Day Acc Given')}</td>
						</tr>
						{/if} {if $part->get('Part Year To Day Acc Given')!=0} 
						<tr>
							<td>{t}Broken{/t}:</td>
							<td class="aright">{$part->get('Year To Day Acc Broken')}</td>
						</tr>
						{/if} {if $part->get('Part Year To Day Acc Given')!=0} 
						<tr>
							<td>{t}Lost{/t}:</td>
							<td class="aright">{$part->get('Year To Day Acc Lost')}</td>
						</tr>
						{/if} 
					</tbody>
					<tbody id="info2_monthtoday" style="{if $parts_period!='monthtoday'}display:none{/if}">
						{if $part->get('Part Month To Day Acc No Supplied')!=0} 
						<tr>
							<td>{t}Required{/t}:</td>
							<td class="aright">{$part->get('Month To Day Acc Required')}</td>
						</tr>
						<tr>
							<td>{t}No Supplied{/t}:</td>
							<td class="aright error">{$part->get('Month To Day Acc No Supplied')}</td>
						</tr>
						{/if} 
						<tr>
							<td>{t}Sold{/t}:</td>
							<td class="aright">{$part->get('Month To Day Acc Sold')}</td>
						</tr>
						{if $part->get('Part Month To Day Acc Given')!=0} 
						<tr>
							<td>{t}Given for free{/t}:</td>
							<td class="aright">{$part->get('Month To Day Acc Given')}</td>
						</tr>
						{/if} {if $part->get('Part Month To Day Acc Given')!=0} 
						<tr>
							<td>{t}Broken{/t}:</td>
							<td class="aright">{$part->get('Month To Day Acc Broken')}</td>
						</tr>
						{/if} {if $part->get('Part Month To Day Acc Given')!=0} 
						<tr>
							<td>{t}Lost{/t}:</td>
							<td class="aright">{$part->get('Month To Day Acc Lost')}</td>
						</tr>
						{/if} 
					</tbody>
					<tbody id="info2_weektoday" style="{if $parts_period!='weektoday'}display:none{/if}">
						{if $part->get('Part Week To Day Acc No Supplied')!=0} 
						<tr>
							<td>{t}Required{/t}:</td>
							<td class="aright">{$part->get('Week To Day Acc Required')}</td>
						</tr>
						<tr>
							<td>{t}No Supplied{/t}:</td>
							<td class="aright error">{$part->get('Week To Day Acc No Supplied')}</td>
						</tr>
						{/if} 
						<tr>
							<td>{t}Sold{/t}:</td>
							<td class="aright">{$part->get('Week To Day Acc Sold')}</td>
						</tr>
						{if $part->get('Part Week To Day Acc Given')!=0} 
						<tr>
							<td>{t}Given for free{/t}:</td>
							<td class="aright">{$part->get('Week To Day Acc Given')}</td>
						</tr>
						{/if} {if $part->get('Part Week To Day Acc Given')!=0} 
						<tr>
							<td>{t}Broken{/t}:</td>
							<td class="aright">{$part->get('Week To Day Acc Broken')}</td>
						</tr>
						{/if} {if $part->get('Part Week To Day Acc Given')!=0} 
						<tr>
							<td>{t}Lost{/t}:</td>
							<td class="aright">{$part->get('Week To Day Acc Lost')}</td>
						</tr>
						{/if} 
					</tbody>
					<tbody id="info2_today" style="{if $parts_period!='today'}display:none{/if}">
						{if $part->get('Part Today Acc No Supplied')!=0} 
						<tr>
							<td>{t}Required{/t}:</td>
							<td class="aright">{$part->get('Today Acc Required')}</td>
						</tr>
						<tr>
							<td>{t}No Supplied{/t}:</td>
							<td class="aright error">{$part->get('Today Acc No Supplied')}</td>
						</tr>
						{/if} 
						<tr>
							<td>{t}Sold{/t}:</td>
							<td class="aright">{$part->get('Today Acc Sold')}</td>
						</tr>
						{if $part->get('Part Today Acc Given')!=0} 
						<tr>
							<td>{t}Given for free{/t}:</td>
							<td class="aright">{$part->get('Today Acc Given')}</td>
						</tr>
						{/if} {if $part->get('Part Today Acc Given')!=0} 
						<tr>
							<td>{t}Broken{/t}:</td>
							<td class="aright">{$part->get('Today Acc Broken')}</td>
						</tr>
						{/if} {if $part->get('Part Today Acc Given')!=0} 
						<tr>
							<td>{t}Lost{/t}:</td>
							<td class="aright">{$part->get('Today Acc Lost')}</td>
						</tr>
						{/if} 
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div id="block_purchase_orders" class="block data_table" style="{if $view!='purchase_orders'}display:none;{/if}clear:both;margin-top:20px;;padding:0 20px 30px 20px ">
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
