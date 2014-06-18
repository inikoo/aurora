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
		<input type="hidden" value="{$current_delivery_note_key}" id="current_delivery_note_key" />
		
				<input type="hidden" value="{$order->get('Order Currency')}" id="currency_code" />
				<input type="hidden" value="{$decimal_point}" id="decimal_point" />
				<input type="hidden" value="{$thousands_sep}" id="thousands_sep" />

		

	<div class="branch ">
		<span>{if $user->get_number_stores()>1}<a href="orders_server.php">{t}Orders{/t}</a> &rarr; {/if}<a href="orders.php?store={$store->id}&view=orders">{$store->get('Store Code')} {t}Orders{/t}</a> &rarr; {$order->get('Order Public ID')} ({$order->get_formated_dispatch_state()})</span> 
	</div>
	<div class="top_page_menu" style="border:none">
		<div class="buttons" style="float:left">
			{if isset($order_prev)}<img class="previous" onmouseover="this.src='art/{if $order_prev.to_end}prev_to_end.png{else}previous_button.gif{/if}'" onmouseout="this.src='art/{if $order_prev.to_end}start_bookmark.png{else}previous_button.png{/if}'" title="{$order_prev.title}" onclick="window.location='{$order_prev.link}'" src="art/{if $order_prev.to_end}start_bookmark.png{else}previous_button.png{/if}" alt="{t}Previous{/t}" />{/if} <span class="main_title">Order <span>{$order->get('Order Public ID')}</span> <span class="subtitle">({$order->get_formated_dispatch_state()})</span> </span> 
		</div>
		<div class="buttons">
			{if isset($order_next)}<img class="next" onmouseover="this.src='art/{if $order_next.to_end}prev_to_end.png{else}next_button.gif{/if}'" onmouseout="this.src='art/{if $order_next.to_end}prev_to_end.png{else}next_button.png{/if}'" title="{$order_next.title}" onclick="window.location='{$order_next.link}'" src="art/{if $order_next.to_end}prev_to_end.png{else}next_button.png{/if}" alt="{t}Next{/t}" />{/if} <button style="height:24px;display:none" onclick="window.location='order.pdf.php?id={$order->id}'"><img style="width:40px;height:12px;position:relative;bottom:3px" src="art/pdf.gif" alt=""></button> {if $order->get_number_invoices()==0} <button id="modify_order"><img style='position:relative;top:1px' src='art/icons/cart_edit.png'> {t}Amend Order{/t}</button> {/if}   <button style="display:none" id="process_order">{t}Process Order{/t}</button>  <button id="cancel" class="negative">{t}Cancel Order{/t}</button> 
		</div>
		<div style="clear:both">
		</div>
	</div>
	<div style="clear:both">
	</div>
	<div id="control_panel" >
	<div id="addresses">
				<h2 style="padding:0">
					<img src="art/icons/id.png" style="width:20px;position:relative;bottom:2px"> {$order->get('Order Customer Name')} <a href="customer.php?id={$order->get('order customer key')}"><span class="id">{$customer->get_formated_id()}</span></a> 
				</h2>
				<h3>{$customer->get('Customer Main Contact Name')} </h3>
				
				<div style="float:left;margin:5px 20px 0 0;color:#444;font-size:90%;width:140px">
					
					<span style="font-weight:500;color:#000">{t}Billing Address{/t}</span>: 
					<div style="margin-top:5px" id="billing_address">
						{$order->get('Order XHTML Billing Tos')}
					</div>
					<div class="buttons small left" style="{if $order->get('Order Invoiced')=='Yes'}display:none{/if}">
						<button id="change_billing_address" class="state_details" style="display:block;margin-top:10px">{t}Change{/t}</button> 
					</div>
				</div>
				
				
				
				
				
				
				
				<div  style="float:left;margin:5px 0 0 0px;color:#444;font-size:90%;width:140px">

				<div id="title_delivery_address" style="{if $order->get('Order For Collection')=='Yes'}display:none;{/if};margin-bottom:5px">
					{t}Delivery Address{/t}: 
				</div>
				<div id="title_for_collection" style="{if $order->get('Order For Collection')=='No'}display:none;{/if};margin-bottom:5px">
					<b>{t}For collection{/t}</b> 
				</div>
				<div class="address_box" id="delivery_address">
					{$order->get('Order XHTML Ship Tos')} 
				</div>
				<div id="shipping_address" style="{if $order->get('Order For Collection')=='Yes' or $order->get('Order Invoiced')=='Yes'}display:none{/if};margin-top:2px" class="buttons left small">
					<button id="change_delivery_address">{t}Change{/t}</button> <br/><button style="margin-top:3px;{if $store->get('Store Can Collect')=='No'}display:none{/if}" id="set_for_collection" onclick="change_shipping_type('Yes')">{t}Set for collection{/t}</button> 
				</div>
				<div id="for_collection" style="{if $order->get('Order For Collection')=='No' or $order->get('Order Invoiced')=='Yes'}display:none;{/if};margin-top:2px" class="buttons left small">
					<button id="set_for_shipping" class="state_details" onclick="change_shipping_type('No')">{t}Set for delivery{/t}</button> 
				</div>
				
				</div>
				
				
				
				</div>
		<div id="totals">
			<div style="{if $order->data['Order Invoiced']=='Yes'}display:none{/if}">
				<table border="0" class="info_block">
					<tr {if $order->
						get('Order Items Discount Amount')==0 }style="display:none"{/if} id="tr_order_items_gross" > 
						<td class="aright">{t}Items Gross{/t}</td>
						<td width="100" class="aright" id="order_items_gross">{$order->get('Items Gross Amount')}</td>
					</tr>
					<tr {if $order->
						get('Order Items Discount Amount')==0 }style="display:none"{/if} id="tr_order_items_discounts" > 
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
					<tr style="border-bottom:1px solid #777">
						<td class="aright" >{t}Total{/t}</td>
						<td id="order_total" width="100" class="aright" style="font-weight:800;">{$order->get('Balance Total Amount')}</td>
					</tr>
					
					<tr style="color:#777">
						<td class="aright">{t}Paid{/t}</td>
						<td id="order_total" width="100" class="aright" >{$order->get('Payments Amount')}</td>
					</tr>
						<tr style="color:#777">
						<td class="aright"><div id="show_add_payment_to_order"  class="buttons small left" onclick="add_payment('order','{$order->id}','{$order->get('Order To Pay Amount')}')"><button><img  src="art/icons/add.png"> {t}Payment{/t}</button></div>  {t}To Pay{/t}</td>
						<td id="order_total" width="100" class="aright" >{$order->get('To Pay Amount')}</td>
					</tr>
					
					
				</table>
				<div class="buttons small" style="display:none;{if $has_credit}display:none;{/if}clear:both;margin:0px;padding-top:10px">
					<button id="add_credit" style="margin:0px;">{t}Add debit/credit{/t}</button> 
				</div>
			</div>
			<div style="{if $order->data['Order Invoiced']=='No'}display:none{/if}">
				<table border="0" class="info_block">
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
					<tr style="font-size:70%;border-top:1px solid #ccc;border-bottom:1px solid #eee;">
						<td style="text-align:right">{t}Invoiced Amounts{/t}</td>
						<td></td>
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
		<div id="dates">
			{if $order->get_notes()} 
			<div class="notes">
				{ $order->get_notes()} 
			</div>
			{/if} 
			<table border="0" class="info_block"  >
				<tr>
					<td>{t}Order Date{/t}:</td>
					<td class="aright">{$order->get('Date')}</td>
				</tr>
				
				</table>
				
			<table border="0" class="info_block with_title"  >

				
				<tr style="border-bottom:1px solid #333;">
					<td colspan=2>{t}Delivery Note{/t}:</td>
				</tr>
				{foreach from=$dns_data item=dn}
				<tr>
				<td>
				<a href="dn.php?id={$dn.key}">{$dn.number}</a>
				</td>
				<td class="right" style="text-align:right">
				{$dn.state}
				
				</td>
				</tr>
				<tr>
				<td colspan=2 class="aright" style="text-align:right">
				{$dn.data}
				</td>
				</tr>
				
				
				<tr>
				<td colspan="2"  class="right" style="text-align:right" id="operations_container{$dn.key}" >{$dn.operations}</td>
				</tr>
				{/foreach}
				</table>
					<table border="0" class="info_block with_title"  >

				
				<tr style="border-bottom:1px solid #333;">
					<td colspan=2>{t}Invoices{/t}:</td>
				</tr>
				{foreach from=$invoices_data item=invoice}
				<tr>
				<td>
				<a href="invoice.php?id={$invoice.key}">{$invoice.number}</a>
				</td>
				<td class="right" style="text-align:right">
				{$invoice.state}
				
				</td>
				</tr>
				<tr>
				<td colspan=2 class="aright" style="text-align:right">
				{$invoice.data}
				</td>
				</tr>
				<tr>
				<td colspan="2"  class="right" style="text-align:right" id="operations_container{$invoice.key}" >{$invoice.operations}</td>
				</tr>
				{/foreach}
				
				
				
				<tr style="{if !($order->get('Order Current Dispatch State')=='Packed Done' and $order->get_number_invoices()==0)}display:none{/if}">
				<td colspan="2"  class="right" style="text-align:right"  >
				<div class="buttons small right">
				<button id="create_invoice"><img id="create_invoice_img" src="art/icons/money.png" alt=""> {t}Create Invoice{/t}</button>
				</div>
				</td>
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
{include file='order_not_dispatched_dialogs_splinter.tpl'} 
{*}
<div id="process_order_dialog" style="width:450px;padding:20px 20px 0 20px;">
	<table id="process_order_buttons" class="edit" style="width:100%;text-align:center" border="0">
		<tr>
			<td> 
			<div class="buttons left">
				<button class="negative" onclick="close_process_order_dialog()">{t}Cancel{/t}</button> <button class="positive" onclick="show_quick_invoice_dialog()">{t}Quick Invoice{/t}</button> 
				
				
				<button class="positive" id="pick_it" onclick="pick_it_()" style="{if !$current_delivery_note_key}display:none{/if}">{t}Pick it{/t}</button> 
				<button class="positive" id="assign_picker_dialog_button" onclick="assign_picker()" style="{if !$current_delivery_note_key}display:none{/if}">{t}Assign Picker{/t}</button> 
			</div>
			</td>
		</tr>
	</table>
	<div id="assign_pickers_packers" style="display:none">
		<table class="edit" border="0" style="margin-bottom:5px;width:400px">
			<input type="hidden" id="assign_picker_staff_key"> 
			<input type="hidden" id="assign_picker_dn_key"> 
			<tr class="title">
				<td>{t}Pickers{/t}</td>
			</tr>
			<tr>
				<td colspan="3"> 
				<div class="options" style="width:350px;padding:0 10px;text-align:center">
					<table border="0" style="margin:auto" id="assign_picker_buttons">
					
					{if $number_packers==0}
						<tr>
							<td onclick="show_other_staff(this)" id="picker_show_other_staff" td_id="other_staff_picker" class="assign_picker_button other" onclick="show_other_staff(this)">{t}Select Picker{/t}</td>
					
					</tr>
					{else}
						{foreach from=$pickers item=picker_row name=foo} 
						<tr>
							{foreach from=$picker_row key=row_key item=picker } 
							<td staff_id="{$picker.StaffKey}" id="picker{$picker.StaffKey}" scope="picker" class="assign_picker_button" onclick="select_staff(this,event)">{$picker.StaffAlias}</td>
							{/foreach} 
							<td onclick="show_other_staff(this)" id="picker_show_other_staff" td_id="other_staff_picker" class="assign_picker_button other" onclick="show_other_staff(this)">{t}Other{/t}</td>
						</tr>
						{/foreach} 
						{/if}
					</table>
				</div>
				</td>
			</tr>
			<tr id="Assign_Picker_Staff_Name_tr" style="display:none">
				<td class="label" style="width:65px">{t}Picker{/t}:</td>
				<td colspan="2"  style="text-align:left;width:220px"> 
				<input type="hidden" id="Assign_Picker_Staff_Name" value="" ovalue="" valid="0"> 
				<span id="Assign_Picker_Staff_Name_label"></span>
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
			<tr class="title">
				<td>{t}Packers{/t}</td>
			</tr>
				<tr>
				<td colspan="3"> 
				<div class="options" style="width:350px;padding:0 10px;text-align:center">
					<table border="0" style="margin:auto" id="assign_packer_buttons">
						
						{if $number_packers==0}
						<tr>
								<td onclick="show_other_staff(this)"  id="packer_show_other_staff" td_id="other_staff_packer" class="assign_packer_button other" onclick="show_other_staff(this)">{t}Select Packer{/t}</td>
					
					</tr>
						{else}
						{foreach from=$packers item=packer_row name=foo} 
						<tr>
							{foreach from=$packer_row key=row_key item=packer } 
							<td staff_id="{$packer.StaffKey}" id="packer{$packer.StaffKey}" scope="packer" class="assign_packer_button" onclick="select_staff(this,event)">{$packer.StaffAlias}</td>
							{/foreach} 
							<td onclick="show_other_staff(this)"  id="packer_show_other_staff" td_id="other_staff_packer" class="assign_packer_button other" onclick="show_other_staff(this)">{t}Other{/t}</td>
						</tr>
						{/foreach} 
						{/if}
						
						
					</table>
				</div>
				</td>
			</tr>
			<input type="hidden" id="assign_packer_staff_key"> 
			<input type="hidden" id="assign_packer_dn_key"> 
			<tr id="Assign_Packer_Staff_Name_tr" style="display:none">
				<td class="label" style="width:65px">{t}Packer{/t}:</td>
				<td colspan="2"  style="text-align:left;width:220px"> 
				<input type="hidden" id="Assign_Packer_Staff_Name" value="" ovalue="" valid="0"> 
				<span id="Assign_Packer_Staff_Name_label"></span>
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
			
			<tr class="title">
				<td>{t}Parcels{/t}</td>
			</tr>	
	
			<tr class="first">
				<td class="label" style="width:65px">{t}Weight{/t}:</td>
				<td style="text-align:left;width:335px" colspan="2"> 
				<input id="parcels_weight" value="" style="width:60px"> Kg</td>
			</tr>
			<tr style="height:5px">
				<td colspan="3"></td>
			</tr>
			<tr>
				<td class="label" style="width:65px">{t}Packing{/t}:</td>
				<td style="text-align:left;width:30px"> 
				<input id="number_parcels" value="1" style="width:30px"></td>
				<td style="width:325px"> 
				<input id="parcel_type" value="Box" type="hidden" />
				<div class="buttons small" id="parcel_type_options">
					<button onclick="change_parcel_type(this)" class='parcel_type' id="parcel_Pallet" valor="Pallet">{t}Pallet{/t}</button> <button onclick="change_parcel_type(this)" class='parcel_type' id="parcel_Envelope" valor="Envelope">{t}Envelope{/t}</button> <button onclick="change_parcel_type(this)" class='parcel_type' id="parcel_Pallet" valor="Small Parcel">{t}Small Parcel{/t}</button> <button onclick="change_parcel_type(this)" class="parcel_type selected" id="parcel_Box" valor="Box">{t}Box{/t}</button> <button onclick="change_parcel_type(this)" class='parcel_type' style="margin-top:5px;" id="parcel_None" valor="None">{t}None{/t}</button> <button onclick="change_parcel_type(this)" class='parcel_type' style="margin-top:5px;clear:left" id="parcel_Other" valor="Envelope">{t}Other{/t}</button> 
				</div>
				</td>
			</tr>
			<tr style="height:5px">
				<td colspan="3"></td>
			</tr>
		</table>
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
</div>
{*}


<div id="dialog_set_dn_data" style="width:710px;padding:20px 20px 0 20px;">


		<table class="edit" border="0" style="margin-bottom:5px;width:700px">
			
			
			<tr class="title">
				<td>{t}Parcels{/t}</td>
			</tr>	
	
			<tr class="first">
				<td class="label" style="width:65px"><span id="parcels_weight_msg" class="edit_td_alert"></span> {t}Weight{/t}:</td>
				
				
				
				<td style="width:200px"  colspan=2> 
							<div>
								<input style="width:100px" id="parcels_weight" changed="0" type='text' class='text' value="" ovalue="" /> 
								<span style="margin-left:10px">Kg</span>
								<div id="parcels_weight_Container">
								</div>
							</div>
							
							</td>
							
				
				
				
				
			</tr>
			<tr style="height:5px">
				<td colspan="3"></td>
			</tr>
			<tr>
				<td class="label" style="width:65px"><span id="number_parcels_msg" class="edit_td_alert"></span> {t}Parcels{/t}:</td>
				<td style="text-align:left;width:30px"> 
				
				<div>
					<input style="width:30px" id="number_parcels" changed="0" type='text' class='text' value="" ovalue="" />
								<div id="number_parcels_Container">
								</div>
							</div>
					

				
				
				<td style="width:325px"> 
				<input id="parcel_type" value="" ovalue="" type="hidden" />
				<div class="buttons small left" id="parcel_type_options">
					<button onclick="change_parcel_type(this)" class="parcel_type" id="parcel_Pallet" valor="Pallet">{t}Pallet{/t}</button> 
					<button onclick="change_parcel_type(this)"  class="parcel_type " id="parcel_Envelope" valor="Envelope">{t}Envelope{/t}</button> 
					<button onclick="change_parcel_type(this)"  class="parcel_type " id="parcel_Small Parcel" valor="Small Parcel">{t}Small Parcel{/t}</button> 
					<button onclick="change_parcel_type(this)"  class="parcel_type " id="parcel_Box" valor="Box">{t}Box{/t}</button> 
					<button onclick="change_parcel_type(this)"  class="parcel_type "  id="parcel_None" valor="None">{t}None{/t}</button> 
					<button onclick="change_parcel_type(this)"  class="parcel_type "  id="parcel_Other" valor="Other">{t}Other{/t}</button> 
				</div>
				<span id="parcel_type_msg" class="edit_td_alert"></span>

				</td>
			</tr>
				<tr class="title">
				<td>{t}Courier{/t}</td>
			</tr>	
			
			<tr>
							<td class="label" style="width:65px">{t}Company{/t}:</td>

				<td colspan=2> 

				
				<input type="hidden" id="shipper_code" value="" ovalue="">

				<div class="buttons small left" id="shipper_code_options">
								{foreach from=$shipper_data item=item key=key } 
								<button style="margin-bottom:5px;min-width:120px"  class="{if $item.selected>0}selected{/if} option" id="shipper_code_{$item.code}" onclick="change_shipper('{$item.code}')"  >{$item.code}</button>
								{/foreach} 
								</div>
				
				</div>
								<span id="shipper_code_msg" class="edit_td_alert"></span>

				</td>
			</tr>
			
			
						<tr>
				<td class="label" style="width:65px"> <span id="consignment_number_msg" class="edit_td_alert"></span> {t}Consignment{/t}:</td>
				
					<td style="width:200px"  colspan=2> 
							<div>
								<input style="width:250px" id="consignment_number" changed="0" type='text' class='text' value="" ovalue="" /> 
								<div id="consignment_number_Container">
								</div>
							</div>
							
							</td>
				
			
				
				
				
				
				
				
				</tr>
				
			<tr class="buttons">
							<td></td>
							<td colspan="2"> 
							<div class="buttons left">
								<button style="margin-right:10px;" id="reset_edit_delivery_note"  onClick="reset_edit_delivery_note()"  class="negative">{t}Close{/t}</button> 

								<button style="margin-right:10px;" id="save_edit_delivery_note" onClick="save_edit_delivery_note()"  class="positive">{t}Save{/t}</button> 
							</div>
							</td>
			</tr>
		</table>
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


{include file='splinter_add_payment.tpl'}

{include file='assign_picker_packer_splinter.tpl'}
{include file='footer.tpl'} 