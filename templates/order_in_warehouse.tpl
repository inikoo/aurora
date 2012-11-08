{include file='header.tpl'} 
<div id="bd">
	{include file='assets_navigation.tpl'} 
	<input type="hidden" value="{$order->get('Order Shipping Method')}" id="order_shipping_method" />
	<input type="hidden" value="{$store->id}" id="store_id" />
	<input type="hidden" value="{$store->id}" id="store_key" />
	<input type="hidden" value="{$order->id}" id="order_key" />
	<input type="hidden" value="{$order->get('Order Current Dispatch State')}" id="dispatch_state" />
	<input type="hidden" value="{$order->get('Order Customer Key')}" id="customer_key" />
	<input type="hidden" value="{$referral}" id="referral" />
	<input type="hidden" value="{$products_display_type}" id="products_display_type" />
	<div class="branch ">
		<span>{if $user->get_number_stores()>1}<a href="orders_server.php">{t}Orders{/t}</a> &rarr; {/if}<a href="orders.php?store={$store->id}&view=orders">{$store->get('Store Code')} {t}Orders{/t}</a> &rarr; {$order->get('Order Public ID')} ({$order->get('Current Dispatch State')})</span> 
	</div>
	<div class="top_page_menu" style="border:none">
		<div class="buttons" style="float:left">
			{*} {if $referral=='store_pending_orders' or $order->get('Order Current Dispatch State')=='Ready to Pick' or $order->get('Order Current Dispatch State')=='Picking & Packing' or $order->get('Order Current Dispatch State')=='Packed' } <button onclick="window.location='customers_pending_orders.php?store={$store->id}'"><img src="art/icons/basket.png" alt=""> {t}Pending Orders{/t}</button> {/if} <button onclick="window.location='orders.php?store={$store->id}&view=orders'"><img src="art/icons/house.png" alt=""> {t}Orders{/t}</button> {/*} <span class="main_title">Order <class class="id">{$order->get('Order Public ID')}</span> ({$order->get('Current Dispatch State')})</span> 
		</div>
		<div class="buttons">
			<button style="height:24px;display:none" onclick="window.location='order.pdf.php?id={$order->id}'"><img style="width:40px;height:12px;position:relative;bottom:3px" src="art/pdf.gif" alt=""></button> {if $order->get_number_invoices()==0} <button id="modify_order">{t}Modify Order{/t}</button> {/if} {if $order->get('Order Current Dispatch State')=='Ready to Ship'} <button id="set_as_dispatched"><img id="set_as_dispatched_img" src="art/icons/lorry_go.png" alt=""> {t}Set as Dispatched{/t}</button> {elseif $order->get('Order Current Dispatch State')=='Packed Done'} {if $order->get_number_invoices()==0} <button id="create_invoice"><img id="create_invoice_img" src="art/icons/money.png" alt=""> {t}Create Invoice{/t}</button> {else} <button id="aprove_dispatching"><img id="aprove_dispatching_img" src="art/icons/package_green.png" alt=""> {t}Aprove Dispatching{/t}</button> {/if} {else} <button id="process_order">{t}Process Order{/t}</button> {/if} <button id="cancel" class="negative">{t}Cancel Order{/t}</button> 
		</div>
		<div style="clear:both">
		</div>
	</div>
	<div style="clear:both">
	</div>
	<div style="border:1px solid #ccc;text-align:left;padding:10px;">
		<div style="width:340px;float:left">
			<h2 style="padding:0">
				{$order->get('Order Customer Name')} <a href="customer.php?id={$order->get('order customer key')}"><span class="id">{$customer->get_formated_id()}</span></a> 
			</h2>
			<div style="float:left;line-height: 1.0em;margin:5px 20px 0 0;color:#444;font-size:80%;width:140px">
				{$customer->get('Customer Main Contact Name')} 
				<div style="margin-top:5px">
					{$customer->get('Customer Main XHTML Address')} 
				</div>
			</div>
			<div id="shipping_address" style="{if $order->get('Order For Collection')=='Yes'}display:none;{/if}float:left;line-height: 1.0em;margin:5px 0 0 0px;color:#444;font-size:80%;width:140px">
				<span style="font-weight:500;color:#000">{t}Shipping Address{/t}</span>: 
				<div style="margin-top:5px" id="delivery_address">
					{$order->get('Order XHTML Ship Tos')} 
				</div>
				<div class="buttons small left">
					<button id="change_delivery_address" class="state_details" style="display:block;margin-top:10px">{t}Change Delivery Address{/t}</button> <button id="set_for_collection" class="state_details" style="display:block;margin-top:4px" value="Yes">{t}Set for collection{/t}</button> 
				</div>
			</div>
			<div id="for_collection" style="{if $order->get('Order For Collection')=='No'}display:none;{/if}float:left;line-height: 1.0em;margin:5px 0 0 0px;color:#444;font-size:80%;width:140px">
				<span>{t}For collection{/t}</span> 
				<div class="buttons small left">
					<button id="set_for_shipping" class="state_details" style="display:block;margin-top:4px" value="No">{t}Set for shipping{/t}</button> 
				</div>
			</div>
			<div style="clear:both">
			</div>
		</div>
		<div style="width:240px;float:right">
				
				<div style="{if $order->data['Order Invoiced']=='Yes'}display:none{/if}">
				<table border="0" style="width:100%;border-top:1px solid #333;border-bottom:1px solid #333;width:100%,padding:0;margin:0;float:right;margin-left:0px">
				<tr {if $order->get('Order Items Discount Amount')==0 }style="display:none"{/if} id="tr_order_items_gross" > 
					<td class="aright">{t}Items Gross{/t}</td>
					<td width="100" class="aright" id="order_items_gross">{$order->get('Items Gross Amount')}</td>
				</tr>
				<tr {if $order->get('Order Items Discount Amount')==0 }style="display:none"{/if} id="tr_order_items_discounts" > 
					<td class="aright">{t}Discounts{/t}</td>
					<td width="100" class="aright">-<span id="order_items_discount">{$order->get('Items Discount Amount')}</span></td>
				</tr>
				<tr>
					<td class="aright">{t}Items Net{/t}</td>
					<td width="100" class="aright" id="order_items_net">{$order->get('Items Net Amount')}</td>
				</tr>
				<tr id="tr_order_credits" {if $order->
					get('Order Net Credited Amount')==0}style="display:none"{/if}> 
					<td class="aright"><img style="visibility:hidden;cursor:pointer" src="art/icons/edit.gif" id="edit_button_credits" /> {t}Credits{/t}</td>
					<td width="100" class="aright" id="order_credits">{$order->get('Net Credited Amount')}</td>
				</tr>
				<tr id="tr_order_items_charges">
					<td class="aright"><img style="visibility:hidden;cursor:pointer" src="art/icons/edit.gif" id="edit_button_items_charges" /> {t}Charges{/t}</td>
					<td id="order_charges" width="100" class="aright">{$order->get('Charges Net Amount')}</td>
				</tr>
				<tr id="tr_order_shipping">
					<td class="aright"> <img style="{if $order->get('Order Shipping Method')=='On Demand'}visibility:visible{else}visibility:hidden{/if};cursor:pointer" src="art/icons/edit.gif" id="edit_button_shipping" /> {t}Shipping{/t}</td>
					<td id="order_shipping" width="100" class="aright">{$order->get('Shipping Net Amount')}</td>
				</tr>
				<tr style="border-top:1px solid #777">
					<td class="aright">{t}Net{/t}</td>
					<td id="order_net" width="100" class="aright">{$order->get('Balance Net Amount')}</td>
				</tr>
				<tr id="tr_order_tax" style="border-bottom:1px solid #777">
					<td class="aright"><img style="visibility:hidden;cursor:pointer" src="art/icons/edit.gif" id="edit_button_tax" /> <span id="tax_info">{$order->get_formated_tax_info()}</span></td>
					<td id="order_tax" width="100" class="aright">{$order->get('Balance Tax Amount')}</td>
				</tr>
				<tr>
					<td class="aright">{t}Total{/t}</td>
					<td id="order_total" width="100" class="aright" style="font-weight:800">{$order->get('Balance Total Amount')}</td>
				</tr>
			</table>
			<div class="buttons small" style="{if $has_credit}display:none;{/if}clear:both;margin:0px;padding-top:10px">
				<button id="add_credit" style="margin:0px;">{t}Add debit/credit{/t}</button>
			</div>
			</div>
	
		
		
		<div style="{if $order->data['Order Invoiced']=='No'}display:none{/if}">
		<table border="0" style="width:100%;border-top:1px solid #333;border-bottom:1px solid #333;width:100%,padding:0;margin:0;float:right;margin-left:0px">
				<tr>
					<td class="aright">{t}Total Ordered (N){/t}</td>
					<td width="100" class="aright">{$order->get('Total Net Amount')}</td>
				</tr>
				{if $order->get('Order Out of Stock Net Amount')!=0 } 
				<tr>
					<td class="aright">{t}Out of Stock (N){/t}</td>
					<td width="100" class="aright">{$order->get('Out of Stock Net Amount')}</td>
				</tr>
				{/if} 
				<tr style="font-size:70%;border-top:1px solid #ccc;border-bottom:1px solid #eee;" >
					<td  style="text-align:right">{t}Invoiced Amounts{/t}</td><td></td>
				</tr>
				<tr>
					<td class="aright">{t}Items (N){/t}</td>
					<td width="100" class="aright">{$order->get('Invoiced Items Amount')}</td>
				</tr>
				<tr>
					<td class="aright">{t}Shipping (N){/t}</td>
					<td width="100" class="aright">{$order->get('Invoiced Shipping Amount')}</td>
				</tr>
				{if $order->get('Order Invoiced Charges Amount')!=0} 
				<tr>
					<td class="aright">{t}Charges (N){/t}</td>
					<td width="100" class="aright">{$order->get('Invoiced Charges Amount')}</td>
				</tr>
				{/if} {if $order->get('Order Invoiced Refund Net Amount')!=0} 
				<tr>
					<td class="aright"><i>{t}Refunds (N){/t}</i></td>
					<td width="100" class="aright">{$order->get('Invoiced Refund Net Amount')}</td>
				</tr>
				{/if} {if $order->get('Order Invoiced Total Net Adjust Amount')!=0} 
				<tr class="adjust" style="color:red">
					<td class="aright">{t}Adjusts (N){/t}</td>
					<td width="100" class="aright">{$order->get('Invoiced Total Net Adjust Amount')}</td>
				</tr>
				{/if} 
				<tr style="border-top:1px solid #bbb">
					<td class="aright">{t}Total (N){/t}</td>
					<td width="100" class="aright">{$order->get('Invoiced Total Net Amount')}</td>
				</tr>
				{if $order->get('Order Invoiced Refund Tax Amount')!=0} 
				<tr>
					<td class="aright"><i>{t}Refunds (Tax){/t}</i></td>
					<td width="100" class="aright">{$order->get('Invoiced Refund Tax Amount')}</td>
				</tr>
				{/if} 
				<tr>
					<td class="aright">{t}Tax{/t}</td>
					<td width="100" class="aright">{$order->get('Invoiced Total Tax Amount')}</td>
				</tr>
				{if $order->get('Order Invoiced Total Tax Adjust Amount')!=0} 
				<tr class="adjust" style="color:red">
					<td class="aright">{t}Tax Adjusts{/t}</td>
					<td width="100" class="aright">{$order->get('Invoiced Total Tax Adjust Amount')}</td>
				</tr>
				{/if} 
				<tr>
					<td class="aright">{t}Total{/t}</td>
					<td width="100" class="aright"><b>{$order->get('Invoiced Total Amount')}</b></td>
				</tr>
			</table>
		</div>
			</div>
		
		<div style="width:320px;float:right">
			{if $order->get_notes()} 
			<div class="notes">
				{ $order->get_notes()} 
			</div>
			{/if} 
			<table border="0" style="border-top:1px solid #333;border-bottom:1px solid #333;width:260px;padding-right:0px;margin-right:20px;float:right">
				<tr>
					<td>{t}Order Date{/t}:</td>
					<td class="aright">{$order->get('Date')}</td>
				</tr>
				<tr>
					<td>{t}Delivery Note{/t}:</td>
					<td class="aright">{$order->get('Order XHTML Delivery Notes')}</td>
				</tr>
				<tr style="{if $order->get('Order XHTML Invoices')==''}display:none{/if}">
					<td>{t}Invoice{/t}:</td>
					<td class="aright">{$order->get('Order XHTML Invoices')}</td>
				</tr>
				<tr >
					<td></td>
					<td class="aright">{$order->get('Order Current XHTML Dispatch State')}</td>
				</tr>
			</table>
		</div>
		<div style="clear:both">
		</div>
	</div>
	<div class="data_table" style="clear:both;margin-top:10px">
		<span id="table_title" class="clean_table_title">{t}Items{/t}</span> 
		<div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999;margin-bottom:15px">
		</div>
		{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0 } 
		<div id="table0" style="font-size:80%" class="data_table_container dtable btable">
		</div>
	</div>
</div>
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
</div>
{include file='order_not_dispatched_dialogs_splinter.tpl'} 
<div id="process_order_dialog" style="width:400px;;padding:20px 20px 0 20px;">
	<table id="process_order_buttons" class="edit" style="width:100%;text-align:center" border="0">
		<tr>
			<td> 
			<div class="buttons left">
				<button class="negative" onclick="close_process_order_dialog()">{t}Cancel{/t}</button> <button class="positive" onclick="show_quick_invoice_dialog()">{t}Quick Invoice{/t}</button> <button class="positive" onclick="show_step_by_step_invoice_dialog()">{t}Invoice Order{/t}</button> 
			</div>
			</td>
		</tr>
	</table>
	<div id="assign_pickers_packers" style="display:none">
		<div class="options" style="width:350px;padding:0 10px;text-align:center">
			<table border="0" style="margin:auto" id="assign_picker_buttons">
				{foreach from=$pickers item=picker_row name=foo} 
				<tr>
					{foreach from=$picker_row key=row_key item=picker } 
					<td staff_id="{$picker.StaffKey}" id="picker{$picker.StaffKey}" class="assign_picker_button" onclick="select_staff(this,event)">{$picker.StaffAlias}</td>
					{/foreach} 
				</tr>
				{/foreach} 
			</table>
		</div>
		<table class="edit" border="0" style="margin-bottom:5px;width:400px">
			<input type="hidden" id="assign_picker_staff_key"> 
			<input type="hidden" id="assign_picker_dn_key"> 
			<tr class="first">
				<td class="label" style="width:65px">{t}Picker{/t}:</td>
				<td style="text-align:left;width:220px"> 
				<div style="width:x190px;position:relative;top:00px">
					<input style="text-align:left;width:100%" id="Assign_Picker_Staff_Name" value="" ovalue="" valid="0"> 
					<div id="Assign_Picker_Staff_Name_Container">
					</div>
				</div>
				</td>
				<td class="assign_picker_button" style="width:115px"> 
				<div class="buttons small left">
					<button onclick="show_other_staff(this)" td_id="other_staff_picker">{t}Other{/t}</button> 
				</div>
				</td>
			</tr>
			<tr style="{if $user->can_edit('assign_pp')}display:none{/if};display:none">
				<td class="label">{t}Supervisor PIN{/t}:</td>
				<td> 
				<input id="assign_picker_sup_password" type="password" />
				</td>
			</tr>
			<tr>
				<td></td>
				<td colspan="2" id="pick_it_msg" class="edit_td_alert"></td>
			</tr>
		</table>
		<div class="options" style="width:350px;padding:0 10px;text-align:center;margin:0px">
			<table style="margin:auto" id="assign_packer_buttons">
				{foreach from=$packers item=packer_row name=foo} 
				<tr>
					{foreach from=$packer_row key=row_key item=packer } 
					<td staff_id="{$packer.StaffKey}" id="packer{$packer.StaffKey}" class="assign_packer_button" onclick="select_staff_assign_packer(this,event)">{$packer.StaffAlias}</td>
					{/foreach} 
				</tr>
				{/foreach} 
			</table>
		</div>
		<table class="edit" border="0" style="width:400px">
			<input type="hidden" id="assign_packer_staff_key"> 
			<input type="hidden" id="assign_packer_dn_key"> 
			<tr class="first">
				<td class="label" style="width:65px">{t}Packer{/t}:</td>
				<td style="text-align:left;width:220px"> 
				<div style="xwidth:190px;position:relative;top:00px">
					<input style="text-align:left;width:100%" id="Assign_Packer_Staff_Name" value="" ovalue="" valid="0"> 
					<div id="Assign_Packer_Staff_Name_Container">
					</div>
				</div>
				</td>
				<td class="assign_packer_button" style="width:115px"> 
				<div class="buttons small left">
					<button onclick="show_other_staff(this)" td_id="other_staff_packer">{t}Other{/t}</button> 
				</div>
				</td>
			</tr>
			<tr style="{if $user->can_edit('assign_pp')}display:none{/if};display:none">
				<td>{t}Supervisor PIN{/t}:</td>
				<td colspan="2"> 
				<input id="assign_packer_sup_password" type="password" />
				</td>
			</tr>
			<tr>
				<td colspan="3" id="Assign_Packer_Staff_Name_msg" class="edit_td_alert"></td>
			</tr>
		</table>
		<table class="edit" border="0" style="width:400px">
			<tr>
				<td class="label" style="width:65px">{t}Weight{/t}:</td>
				<td style="text-align:left;width:335px" colspan="2">
				<input id="parcels_weight" value="" style="width:60px"> Kg</td>
			</tr>
			<tr>
				<tr style="height:5px">
					<td colspan="3"></td>
					<tr>
						<td class="label" style="width:65px">{t}Packing{/t}:</td>
						<td style="text-align:left;width:30px">
						<input id="number_parcels" value="1" style="width:30px"></td>
						<td style="width:325px"> 
						<input id="parcel_type" value="Box" type="hidden" />
						<div class="buttons small" id="parcel_type_options">
							<button onclick="change_parcel_type(this)" class='parcel_type' id="parcel_Pallet" valor="Pallet">{t}Pallet{/t}</botton> <button onclick="change_parcel_type(this)" class='parcel_type' id="parcel_Envelope" valor="Envelope">{t}Envelope{/t}</botton> <button onclick="change_parcel_type(this)" class='parcel_type' id="parcel_Pallet" valor="Small Parcel">{t}Small Parcel{/t}</botton> <button onclick="change_parcel_type(this)" class="parcel_type selected" id="parcel_Box" valor="Box">{t}Box{/t}</botton> <button onclick="change_parcel_type(this)" class='parcel_type' style="margin-top:5px;" id="parcel_None" valor="None">{t}None{/t}</botton> <button onclick="change_parcel_type(this)" class='parcel_type' style="margin-top:5px;clear:left" id="parcel_Other" valor="Envelope">{t}Other{/t}</botton> 
						</div>
						</td>
					</tr>
					<tr style="height:5px">
						<td colspan="3"></td>
						<tr>
						</table>
					</div>
					<table id="quick_invoice_buttons" class="edit" style="width:100%;text-align:center;display:none" border="0">
						<tr id="quick_invoice_invoice_buttons_tr">
							<td> 
							<div class="buttons">
								<button class="positive" onclick="quick_invoice()">{t}Create Invoice{/t}</button> <button class="negative" onclick="close_process_order_dialog()">{t}Cancel{/t}</button> 
							</div>
							</td>
						</tr>
						<tr>
							<td style="text-align:right;"> 
							<div style="display:none" id="quick_invoice_invoice_wait">
								<span style="padding-right:10px"><img src="art/loading.gif" /> {t}Processing Request{/t}</span>
							</div>
							</td>
						</tr>
					</table>
					<table id="step_by_step_invoice_buttons" class="edit" style="width:100%;text-align:center;display:none" border="0">
						<tr id="step_by_step_invoice_buttons_tr">
							<td> 
							<div class="buttons">
								<button class="positive" onclick="step_by_step_invoice()">{t}Create Invoice (Step by Step){/t}</button> <button class="negative" onclick="close_process_order_dialog()">{t}Cancel{/t}</button> 
							</div>
							</td>
						</tr>
						<tr>
							<td style="text-align:right;"> 
							<div style="display:none" id="step_by_step_invoice_wait">
								<span style="padding-right:10px"><img src="art/loading.gif" /> {t}Processing Request{/t}</span>
							</div>
							</td>
						</tr>
					</table>
				</div>
				<div id="dialog_other_staff">
					<input type="hidden" id="staff_list_parent_dialog" value=""> 
					<div class="splinter_cell" style="padding:10px 15px 10px 0;border:none">
						<div id="the_table" class="data_table">
							<span class="clean_table_title">{t}Staff List{/t}</span> {include file='table_splinter.tpl' table_id=2 filter_name=$filter_name2 filter_value=$filter_value2} 
							<div id="table2" class="data_table_container dtable btable">
							</div>
						</div>
					</div>
				</div>
				{include file='footer.tpl'} 