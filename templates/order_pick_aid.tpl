{include file='header.tpl'} 
<div id="bd">
<div id="print">
	{include file='locations_navigation.tpl'}
		<input type="hidden" id="method" value="{$warehouse->get('Warehouse Picking Aid Type')}">
<input type="hidden" id="modify_stock" value="{$modify_stock}"/>
<input type="hidden" id="stock" value=""/>
<input type="hidden" id="page_name" value="pick_aid"/>
<input type="hidden" id="staff_list_parent_dialog" value="assign_packer"/>
	<input value="{$delivery_note->id}" id="assign_packer_dn_key" type="hidden" />



	<input value="{$delivery_note->id}" id="dn_key" type="hidden" />
	<div id="left_nav" class="branch">
		<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home"/></a>&rarr; {if $user->get_number_warehouses()>1}<a href="warehouses.php">{t}Warehouses{/t}</a> &rarr; {/if}<a href="warehouse_parts.php?warehouse_id={$warehouse->id}">{t}Inventory{/t}</a> &rarr; <a href="warehouse_orders.php?id={$warehouse->id}">{t}Pending Orders{/t}</a> &rarr; {$delivery_note->get('Delivery Note ID')} ({t}Pick Aid{/t})</span> 
	</div>
	<div id="top_page_menu" class="top_page_menu">
		<div  style="float:left">
		<span class="main_title">{t}Picking of Delivery Note{/t} <a class="id" href="dn.php?id={$delivery_note->id}">{$delivery_note->get('Delivery Note ID')}</a> </span>
		</div>
		<div class="buttons" style="float:right">
			<a style="height:14px" href="order_pick_aid.pdf.php?id={$delivery_note->id}" target="_blank"><img  style="width:40px;height:12px" src="art/pdf.gif" alt=""></a>
			<button id="pick_all" style="height:24px;{if $delivery_note->get('Delivery Note Faction Picked')==1 or $warehouse->get('Warehouse Picking Aid Type')!='Inikoo'}display:none{/if}"><img id="set_all_as_picked" src="art/icons/accept.png" alt="" /> {t}Set all as Picked{/t}</button> 
			<a id="update_locations" style="height:14px;{if $delivery_note->get('Delivery Note Faction Picked')==1}display:none{/if}" href="order_pick_aid.php?id={$delivery_note->id}&refresh=1"><img src="art/icons/arrow_refresh.png" alt="" /> {t}Update Locations{/t}</a> 
			<button id="start_packing" style="height:24px;{if $delivery_note->get('Delivery Note Faction Picked')==0 or $delivery_note->get('Delivery Note Assigned Packer Key') or $warehouse->get('Warehouse Picking Aid Type')!='Inikoo'}display:none{/if}"><img src="art/icons/package.png" alt="" /> {t}Assign Packer{/t}</button> 
			<a style="height:14px;{if $delivery_note->get('Delivery Note Faction Picked')==0 or !$delivery_note->get('Delivery Note Assigned Packer Key') or $warehouse->get('Warehouse Picking Aid Type')!='Inikoo'}display:none{/if}" href="order_pack_aid.php?id={$delivery_note->id}"><img src="art/icons/package.png" alt="" /> {t}Packing Aid{/t}</a> 
		</div>
		<div style="clear:both">
		</div>
	</div>
</div>
	<div id="control_panel" style="clear:both;margin-top:15px">
		<div style="border:1px solid #ccc;text-align:left;padding:10px;margin: 0px 0 10px 0;xheight:15em">
			<div style="xborder:1px solid #ddd;width:350px;float:left">
				
				<h2 style="padding:0">
					{$delivery_note->get('Delivery Note Customer Name')} (<a href="customer.php?id={$customer->id}">{$customer->get_formated_id()}</a>) {$delivery_note->get('Delivery Note Country 2 Alpha Code')} 
				</h2>
				<div style="clear:both">
				</div>
			</div>
			<div style="border:0px solid #ddd;width:330px;float:right;">
				<table style="xdisplay:none;width:100%;xborder-top:1px solid #333;xborder-bottom:1px solid #333;width:100%,padding:0;margin:0;float:right;margin-left:0px">
					<tbody id="resend" style="xdisplay:none">
						<tr>
							<td class="aright">{t}Picker{/t}:</td>
							<td id="assigned_picker" key="{$delivery_note->get('Delivery Note Assigned Picker Key')}" class="aright">{$delivery_note->get('Delivery Note Assigned Picker Alias')}</td>
						</tr>
						<tr>
							<td class="aright">{t}Transactions{/t}:</td>
							<td class="aright"><span id="number_picked_transactions">{$number_picked_transactions}</span>/<span id="number_transactions">{$number_transactions}</span> <span style="margin-left:10px" id="percentage_picked">{$delivery_note->get('Faction Picked')}</span></td>
						</tr>
					</tbody>
				</table>
			</div>
			<div style="clear:both">
			</div>
		</div>
		<div class="data_table" style="clear:both">
			<span id="table_title" class="clean_table_title">{t}Items{/t}</span> 
			<div id="table_type" style="display:none">
				<span id="set_pending_as_picked" style="float:right;color:brown" class="table_type state_details ">{t}Set pending as Picked{/t}</span> 
			</div>
			<div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999;margin-bottom:15px">
			</div>
			{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0 } 
			<div id="table0" style="font-size:80%" class="data_table_container dtable btable ">
			</div>
		</div>
	</div>
</div>
</div>
</div>
<div id="no_dispatchable_editor_dialog" style="width:260px">
	<input type="hidden" id="todo_itf_key" value="0"> 
	<input type="hidden" id="todo_units" value="0"> 
	<input type="hidden" id="required_units" value="0"> 
	<input type="hidden" id="picked_units" value="0"> 
	<div style="display:none" class="hd">
	</div>
	<div class="bd dt-editor">
		<div style="display:none;margin-top:20px" id="todo_error_msg">
			<p>
				{t}Error, the sum of out of stock and not found units are greater than the number of not picked units{/t} 
			</p>
		</div>
		<table class="edit" border="0" style="margin:0  0 0 10px ;width:220px;padding:10px 20px 10px 10px">
			<tr class="title">
				<td colspan="4">{t}Pending{/t}: <span id="formated_todo_units"></span></td>
			</tr>
			<td colspan=4 style="height:10px">
			</td>
			</tr>
			<tr style="display:none">
				<td style="width:15px;text-align:center"></td>
				<td style="width:15px;text-align:center"></td>
				<td ><span id="to_assign_todo_units" style="width:100%;"></span></td>
				<td>{t}Unspecified{/t}</td>
			</tr>
			<tr>
				<td style="cursor:pointer;width:15px;padding:2px 0;text-align:center" onclick="add_no_dispatchable('out_of_stock_units')">+</td>
				<td style="cursor:pointer;width:15px;padding:2px 0;text-align:center" onclick="remove_no_dispatchable('out_of_stock_units')">-</td>
				<td style="width:30px;"> 
				<input id="out_of_stock_units" type="text" style="width:100%;"></td>
				<td>{t}Out of Stock{/t}</td>
			</tr>
			<tr>
				<td style="cursor:pointer;width:15px;padding:2px 0;text-align:center" onclick="add_no_dispatchable('not_found_units')">+</td>
				<td style="cursor:pointer;width:15px;padding:2px 0;text-align:center" onclick="remove_no_dispatchable('not_found_units')">-</td>
				<td style="width:30px"> 
				<input id="not_found_units" type="text" style="width:100%;"></td>
				<td>{t}Not Found{/t}</td>
			</tr>
			<tr>
				<td style="cursor:pointer;width:15px;padding:2px 0;text-align:center" onclick="add_no_dispatchable('no_picked_other_units')">+</td>
				<td style="cursor:pointer;width:15px;padding:2px 0;text-align:center" onclick="remove_no_dispatchable('no_picked_other_units')">-</td>
				<td style="width:30px"> 
				<input id="no_picked_other_units" type="text" style="width:100%;"></td>
				<td>{t}Other Reason{/t}</td>
			</tr>
			</tr>
			<td colspan=4 style="height:10px">
			</td>
			</tr>
			<tr>
			<td colspan=4>
			<div class="buttons">
			<button onclick="save_no_dispatchable();" class="positive">{t}Save{/t}</button> 
			<button class="negative" onclick="close_no_dispatchable_dialog()">{t}Cancel{/t}</button> 
		</div>
			</td>
			</tr>
		</table>
		
	</div>
</div>



<div id="dialog_locations" style="padding:10px">
<!--	<table style="margin:10px">
		<tr>
			<td>{t}Contact Name:{/t}</td>
		</tr>

	</table>-->
<span id="location_content"></span>
</div>


{include file='assign_picker_packer_splinter.tpl'}

{include file='stock_splinter.tpl'} 
{include file='footer.tpl'} 