<div class="asset_container" >

    <div class="block name_and_picture">
	<div class="data_container">
		<div style="width:100%">
			<ul class="tags Categories">
				{foreach from=$part->get_category_data() item=item key=key} 
				<li><span class="button" onclick="change_view('category/{$item.category_key}')" title="{$item.label}">{$item.code}</span></li>
				{/foreach} 
			</ul>
		</div>
		<div class="data_field " style="clear:both">
			<span class="strong Part_Unit_Description">{$part->get('Part Package Description')}</span> 
		</div>
	</div>
	<div class="data_container">
	</div>
	<div style="clear:both">
	</div>
	<div class="data_container">
		{assign "image_key" $part->get_main_image_key()} 
		<div id="main_image" class="wraptocenter main_image {if $image_key==''}hide{/if}">
			<img src="/{if $image_key}image_root.php?id={$image_key}&amp;size=small{else}art/nopic.png{/if}"> </span> 
		</div>
		{include file='upload_main_image.tpl' object='Part' key=$part->id class="{if $image_key!=''}hide{/if}"} 
	</div>
	<div style="clear:both">
	</div>
</div>
    <div class="block sales_data">
	<table>
		<tr class="header">
			<td>{$year_data.0.header}</td>
			<td>{$year_data.1.header}</td>
			<td>{$year_data.2.header}</td>
			<td>{$year_data.3.header}</td>
			<td>{$year_data.4.header}</td>
		</tr>
		<tr>
			<td><span title="{$part->get('Year To Day Acc Invoiced Amount')}">{$part->get('Year To Day Acc Invoiced Amount Minify')}</span> <span title="{$year_data.0.invoiced_amount_delta_title}">{$year_data.0.invoiced_amount_delta}</span></td>
			<td><span title="{$part->get('1 Year Ago Invoiced Amount')}">{$part->get('1 Year Ago Invoiced Amount Minify')}</span> <span title="{$year_data.1.invoiced_amount_delta_title}">{$year_data.1.invoiced_amount_delta}</span></td>
			<td><span title="{$part->get('2 Year Ago Invoiced Amount')}">{$part->get('2 Year Ago Invoiced Amount Minify')}</span> <span title="{$year_data.2.invoiced_amount_delta_title}">{$year_data.2.invoiced_amount_delta}</span></td>
			<td><span title="{$part->get('3 Year Ago Invoiced Amount')}">{$part->get('3 Year Ago Invoiced Amount Minify')}</span> <span title="{$year_data.3.invoiced_amount_delta_title}">{$year_data.3.invoiced_amount_delta}</span></td>
			<td><span title="{$part->get('4 Year Ago Invoiced Amount')}">{$part->get('4 Year Ago Invoiced Amount Minify')}</span> <span title="{$year_data.4.invoiced_amount_delta_title}">{$year_data.4.invoiced_amount_delta}</span></td>
		</tr>
		<tr>
			<td><span title="{$part->get('Year To Day Acc Dispatched')}">{$part->get('Year To Day Acc Dispatched Minify')}</span> <span title="{$year_data.0.dispatched_delta_title}">{$year_data.0.dispatched_delta}</span></td>
			<td><span title="{$part->get('1 Year Ago Dispatched')}">{$part->get('1 Year Ago Dispatched Minify')}</span> <span title="{$year_data.1.dispatched_delta_title}">{$year_data.1.dispatched_delta}</span></td>
			<td><span title="{$part->get('2 Year Ago Dispatched')}">{$part->get('2 Year Ago Dispatched Minify')}</span> <span title="{$year_data.2.dispatched_delta_title}">{$year_data.2.dispatched_delta}</span></td>
			<td><span title="{$part->get('3 Year Ago Dispatched')}">{$part->get('3 Year Ago Dispatched Minify')}</span> <span title="{$year_data.3.dispatched_delta_title}">{$year_data.3.dispatched_delta}</span></td>
			<td><span title="{$part->get('4 Year Ago Dispatched')}">{$part->get('4 Year Ago Dispatched Minify')}</span> <span title="{$year_data.4.dispatched_delta_title}">{$year_data.4.dispatched_delta}</span></td>
		</tr>
		<tr class="space">
			<td colspan="5"></td>
		</tr>
		<tr class="header">
			<td>{$quarter_data.0.header}</td>
			<td>{$quarter_data.1.header}</td>
			<td>{$quarter_data.2.header}</td>
			<td>{$quarter_data.3.header}</td>
			<td>{$quarter_data.4.header}</td>
		</tr>
		<tr>
			<td><span title="{$part->get('Quarter To Day Acc Invoiced Amount')}">{$part->get('Quarter To Day Acc Invoiced Amount Minify')}</span> <span title="{$quarter_data.0.invoiced_amount_delta_title}">{$quarter_data.0.invoiced_amount_delta}</span></td>
			<td><span title="{$part->get('1 Quarter Ago Invoiced Amount')}">{$part->get('1 Quarter Ago Invoiced Amount Minify')}</span> <span title="{$quarter_data.1.invoiced_amount_delta_title}">{$quarter_data.1.invoiced_amount_delta}</span></td>
			<td><span title="{$part->get('2 Quarter Ago Invoiced Amount')}">{$part->get('2 Quarter Ago Invoiced Amount Minify')}</span> <span title="{$quarter_data.2.invoiced_amount_delta_title}">{$quarter_data.2.invoiced_amount_delta}</span></td>
			<td><span title="{$part->get('3 Quarter Ago Invoiced Amount')}">{$part->get('3 Quarter Ago Invoiced Amount Minify')}</span> <span title="{$quarter_data.3.invoiced_amount_delta_title}">{$quarter_data.3.invoiced_amount_delta}</span></td>
			<td><span title="{$part->get('4 Quarter Ago Invoiced Amount')}">{$part->get('4 Quarter Ago Invoiced Amount Minify')}</span> <span title="{$quarter_data.4.invoiced_amount_delta_title}">{$quarter_data.4.invoiced_amount_delta}</span></td>
		</tr>
		<tr>
			<td><span title="{$part->get('Quarter To Day Acc Dispatched')}">{$part->get('Quarter To Day Acc Dispatched Minify')}</span> <span title="{$quarter_data.0.dispatched_delta_title}">{$quarter_data.0.dispatched_delta}</span></td>
			<td><span title="{$part->get('1 Quarter Ago Dispatched')}">{$part->get('1 Quarter Ago Dispatched Minify')}</span> <span title="{$quarter_data.1.dispatched_delta_title}">{$quarter_data.1.dispatched_delta}</span></td>
			<td><span title="{$part->get('2 Quarter Ago Dispatched')}">{$part->get('2 Quarter Ago Dispatched Minify')}</span> <span title="{$quarter_data.2.dispatched_delta_title}">{$quarter_data.2.dispatched_delta}</span></td>
			<td><span title="{$part->get('3 Quarter Ago Dispatched')}">{$part->get('3 Quarter Ago Dispatched Minify')}</span> <span title="{$quarter_data.3.dispatched_delta_title}">{$quarter_data.3.dispatched_delta}</span></td>
			<td><span title="{$part->get('4 Quarter Ago Dispatched')}">{$part->get('4 Quarter Ago Dispatched Minify')}</span> <span title="{$quarter_data.4.dispatched_delta_title}">{$quarter_data.4.dispatched_delta}</span></td>
		</tr>
	</table>
	{$customers} 
</div>
	<div class="block info">
	
	
	
	
		<div id="overviews">
		
			<table id="stock_table" border="0" class="overview">
				<tbody class="info">
				
				
				
				<tr  class="main ">
					
					<td class=" highlight Part_Status">{$part->get('Status')} </td>
					
					<td class="aright " >
					
					<span class="big highlight  Current_On_Hand_Stock"  >{$part->get('Current On Hand Stock')}</span> 
					<span class="big highlight Stock_Status_Icon">{$part->get('Stock Status Icon')}</span>
					
					</td>
					
				</tr>
				<tr >
					
						<td class="aright" colspan="2" style="padding-top:0;color:#777;font-size:90%"> <b class="Current_Stock" title="{t}Current stock{/t}">{$part->get('Current Stock')}</b> <b>-[<span class="Current_Stock_Picked" title="{t}Stock picked{/t}">{$part->get('Current Stock Picked')}</span>]</b> -(<span id="Current_Stock_In_Process" title="{t}Waiting to be picked{/t}">{$part->get('Current Stock In Process')}</span>) &rarr; <span title="{t}Available to sell{/t}" class="Current_Stock_Available">{$part->get('Current Stock Available')}</span></td>
					
				</tr>
				<tr >
				<td class="Products_Web_State">{$part->get('Products Web Status')}</td>
				<td class="aright Available_Forecast">{$part->get('Available Forecast')}</td>
				</tr>
				</tbody>
				
					<tr  class="main hide edit_controls" id="edit_stock_controls">
					
					<td   colspan=2> 
					
					<table style="width:100%">                                                       
					<tr >
					<td class="super_discreet highlight Current_On_Hand_Stock" style="font-size:200%">{$part->get('Current On Hand Stock')}</td>
					<td id="stock_diff" class="acenter"> </td>
					<td id="new_stock" class="aright highlight" style="font-size:200%"></td>
					</tr>
					<tr>
					<td><i class="fa  fa-times button discreet" aria-hidden="true" title="{t}Close edit{/t}"  onClick="close_edit_stock()"></i> </td>
					<td></td>
					<td  id="saving_buttons" class="aright discreet  ">
					<span  id="saving_buttons" class="aright discreet   ><span class="save">{t}Save{/t}</span ><i class="fa  fa-cloud   save " aria-hidden="true" title="{t}Save{/t}" id="save_stock" onClick="save_stock()" ></i></span>
					 </td>

					</tr>
					
					</table>
					
					</td>
					
				</tr>
				
			</table>
			
			
			<table style="width:100%">
			<td>
			 <i id="close_edit_stock" class="fa fa-sign-out fa-flip-horizontal button hide" aria-hidden="true" title="{t}Exit edit stock{/t}" onClick="close_edit_stock()"></i></td>
			<td class="aright">
			<i  id="open_edit_stock"  class="fa fa-pencil button very_discreet " aria-hidden="true" title="{t}Edit stock{/t}" onClick="open_edit_stock()" ></i>
			<span  id="edit_stock_saving_buttons" class="aright discreet hide"  ><span class="save">{t}Save{/t}</span ><i class="fa  fa-cloud   save padding_left_5" aria-hidden="true" title="{t}Save{/t}" id="save_stock" onClick="save_stock()" ></i></span>
			</td>
			</table>
			
			<table id="locations_table" border="0" class="overview" part_sku="{$part->id}">
			
			<tr id="move_stock_tr" class="discreet button hide " style="border-bottom:1px solid #ccc" max="">
				<td colspan=2 ><span id="move_from"></span> <i class="fa fa-fw fa-caret-square-o-right " aria-hidden="true"  ></i>  <span id="move_to"></span></td> 
					
			<td class="aright" >
			<i class="fa fa-fw fa-times button discreet" aria-hidden="true" title="{t}Close{/t}" onClick="close_move()"></i>
			<input  id="move_stock_qty" style="width:80px" value="" placeholder="{t}Move stock{/t}"> 
			<i  id="move_stock" class="fa fa-fw fa-level-down button save  " aria-hidden="true" title="{t}Move from{/t}" onClick="apply_move()" ></i> 
			</td>
			
				</tr>
				
			
				
			<tbody id="part_locations" class="Part_Locations">
			{include file='part_locations.edit.tpl' locations_data=$part->get_locations('data')}
			</tbody>	
			
				
			</table>
			
			<table id="barcode_data" border="0" class="overview {if $part->get('Part Barcode Number')==''}hide{/if} ">
				<tr  class="main">
					<td class="label" ><i   {if $part->get('Part Barcode Key')} class="fa fa-barcode button" onClick="change_view('inventory/barcode/{$part->get('Part Barcode Key')}')"{else}  class="fa fa-barcode"{/if}   ></i></td>
					<td class="Part_Barcode_Number highlight">{$part->get('Part Barcode Number')} </td>
					<td class="barcode_labels aright {if !$part->get('Part Barcode Key')}hide{/if}" >
					<a title="{t}Stock keeping unit (Outer){/t}" href="/asset_label.php?object=part&key={$part->id}&type=package"><i class="fa fa-tag "></i></a>
					<a class="padding_left_10" title="{t}Commercial unit label{/t}" href="/asset_label.php?object=part&key={$part->id}&type=unit"><i class="fa fa-tags "></i></a>
                    </td>
					
				</tr>
				
				
			</table>
			
		</div>
	</div>
	<div style="clear:both">
</div>
	
	
	
</div>


<script>

function category_view(){
    change_view('category/'+$('#Part_Family_Key').val())
}


var movements = []

//open_edit_stock()
 $('#locations_table  input.stock ').each(function(i, obj) {

        stock_changed($(obj))
    })
//open_add_location()




$(document).on('input propertychange', '.min_max', function(evt) {

    if (window.event && event.type == "propertychange" && event.propertyName != "value") return;
    min_max_changed($(this))
});

$(document).on('input propertychange', '.recommended_move', function(evt) {

    if (window.event && event.type == "propertychange" && event.propertyName != "value") return;
    recommended_move_changed($(this))
});


$(document).on('input propertychange', '.stock', function(evt) {

    if (window.event && event.type == "propertychange" && event.propertyName != "value") return;
    stock_changed($(this))
});

$(document).on('input propertychange', '#move_stock_qty', function(evt) {

    if (window.event && event.type == "propertychange" && event.propertyName != "value") return;
    move_qty_changed($(this))
});


$(document).on('input propertychange', '#add_location_tr', function(evt) {


    var delay = 100;
    if (window.event && event.type == "propertychange" && event.propertyName != "value") return;
    delayed_on_change_add_location_field($(this), delay)
});





</script>
