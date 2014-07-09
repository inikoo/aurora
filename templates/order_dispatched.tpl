{include file='header.tpl'} 
<div id="bd">
	<input type="hidden" id="order_key" value="{$order->id}" />
		<input type="hidden" value="{$order->get('Order Currency')}" id="currency_code" />
	<input type="hidden" value="{$decimal_point}" id="decimal_point" />
	<input type="hidden" value="{$thousands_sep}" id="thousands_sep" />
	{include file='orders_navigation.tpl'} 
	<div class="branch">
		<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a>&rarr; {if $user->get_number_stores()>1}<a href="orders_server.php">&#8704; {t}Orders{/t}</a> &rarr; {/if} <a href="orders.php?store={$store->id}&view=orders">{t}Orders{/t} ({$store->get('Store Code')})</a> &rarr; {$order->get('Order Public ID')} ({$order->get_formated_dispatch_state()})</span> 
	</div>
	<div class="top_page_menu" style="border:none">
		<div class="buttons" style="float:left">
			{if isset($order_prev)}<img class="previous" onmouseover="this.src='art/{if $order_prev.to_end}prev_to_end.png{else}previous_button.gif{/if}'" onmouseout="this.src='art/{if $order_prev.to_end}start_bookmark.png{else}previous_button.png{/if}'" title="{$order_prev.title}" onclick="window.location='{$order_prev.link}'" src="art/{if $order_prev.to_end}start_bookmark.png{else}previous_button.png{/if}" alt="{t}Previous{/t}" />{/if} <span class="main_title">{t}Dispatched Order{/t} <span class="id">{$order->get('Order Public ID')}</span></span> 
		</div>
		<div class="buttons">
			{if isset($order_next)}<img class="next" onmouseover="this.src='art/{if $order_next.to_end}prev_to_end.png{else}next_button.gif{/if}'" onmouseout="this.src='art/{if $order_next.to_end}prev_to_end.png{else}next_button.png{/if}'" title="{$order_next.title}" onclick="window.location='{$order_next.link}'" src="art/{if $order_next.to_end}prev_to_end.png{else}next_button.png{/if}" alt="{t}Next{/t}" />{/if} {*}<a style="height:14px" href="order.pdf.php?id={$order->id}" target="_blank"><img style="width:40px;height:12px" src="art/pdf.gif" alt=""></a> {*} <button style="{if $order->get_number_post_order_transactions()}display:none;{/if}" onclick="window.location='new_post_order.php?id={$order->id}'"><img src="art/icons/page_white_edit.png" alt=""> {t}Create Post Dispatch Operations{/t}</button> 
		</div>
		<div style="clear:both">
		</div>
	</div>
	<div id="control_panel">
		<div id="addresses">
			<h2 style="padding:0">
				<img src="art/icons/id.png" style="width:20px;position:relative;bottom:2px"> {$order->get('Order Customer Name')} <a href="customer.php?id={$order->get('order customer key')}"><span class="id">{$customer->get_formated_id()}</span></a> 
			</h2>
			<h3>
				{$customer->get('Customer Main Contact Name')} 
			</h3>
			<div style="float:left;margin:5px 20px 0 0;color:#444;font-size:90%;width:140px">
				<span style="font-weight:500;color:#000">{t}Billing Address{/t}</span>: 
				<div style="margin-top:5px" id="billing_address">
					{$order->get('Order XHTML Billing Tos')} 
				</div>
				<div class="buttons small left" style="{if $order->get('Order Invoiced')=='Yes'}display:none{/if}">
					<button id="change_billing_address" class="state_details" style="display:block;margin-top:10px">{t}Change{/t}</button> 
				</div>
			</div>
			<div style="float:left;margin:5px 0 0 0px;color:#444;font-size:90%;width:140px">
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
					<button id="change_delivery_address">{t}Change{/t}</button> <br />
					<button style="margin-top:3px;{if $store->get('Store Can Collect')=='No'}display:none{/if}" id="set_for_collection" onclick="change_shipping_type('Yes')">{t}Set for collection{/t}</button> 
				</div>
				<div id="for_collection" style="{if $order->get('Order For Collection')=='No' or $order->get('Order Invoiced')=='Yes'}display:none;{/if};margin-top:2px" class="buttons left small">
					<button id="set_for_shipping" class="state_details" onclick="change_shipping_type('No')">{t}Set for delivery{/t}</button> 
				</div>
			</div>
		</div>
		<div id="totals">
			<table border="0" class="info_block">
				<tr {if $order->
					get('Order Items Discount Amount')==0 }style="display:none"{/if} id="tr_order_items_discounts" > 
					<td class="aright">{t}Discounts{/t}</td>
					<td width="100" class="aright">-<span id="order_items_discount">{$order->get('Items Discount Amount')}</span></td>
				</tr>
				{if $order->get('Order Out of Stock Net Amount')!=0 } 
				<tr>
					<td class="aright">{t}Total Ordered (N){/t}</td>
					<td width="100" class="aright">{$order->get('Total Net Amount')}</td>
				</tr>
				<tr>
					<td class="aright">{t}Out of Stock (N){/t}</td>
					<td width="100" class="aright">{$order->get('Out of Stock Net Amount')}</td>
				</tr>
				{/if} 
				<tr>
					<td colspan="2" style="font-size:70%;border-top:1px solid #ccc;border-bottom:1px solid #eee">{t}Invoiced Amounts{/t}</td>
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
				<tr style="border-bottom:1px solid #777">
						<td class="aright">{t}Total{/t}</td>
						<td id="order_total" width="100" class="aright" style="font-weight:800">{$order->get('Balance Total Amount')}</td>
					</tr>
					
				<tr id="tr_order_total_paid" style="border-top:1px solid #777;">
						<td class="aright"><img id="order_paid_info" src="art/icons/information.png" title="{$order->get('Order Current XHTML Payment State')}">  {t}Paid{/t}</td>
						<td id="order_total_paid" width="100" class="aright" >{$order->get('Payments Amount')}</td>
					</tr>
				<tr id="tr_order_total_to_pay" style="{if $order->get('Order To Pay Amount')==0}display:none{/if}">
						<td class="aright"><div id="show_add_payment_to_order"  class="buttons small left" onclick="add_payment('order','{$order->id}','{$order->get('Order To Pay Amount')}')"><button><img  src="art/icons/add.png"> {t}Payment{/t}</button></div>  {t}To Pay{/t}</td>
						<td id="order_total_to_pay" width="100" class="aright" style="font-weight:800">{$order->get('To Pay Amount')}</td>
					</tr>
				
			</table>
		</div>
		<div id="dates">
			{if $order->get_notes()} 
			<div class="notes" style="border:1px solid #ccc;padding:5px;margin-bottom:5px">
				{$order->get_notes()} 
			</div>
			{/if} 
			<table border="0" class="info_block">
				<tr>
					<td>{t}Order Date{/t}:</td>
					<td class="aright">{$order->get('Date')}</td>
				</tr>
				<tr>
					<td>{t}Sales Rep{/t}:</td>
					<td class="aright">{$order->get('Order XHTML Sales Representative')}</td>
				</tr>
			</table>
		<table border="0" class="info_block with_title">
				<tr style="border-bottom:1px solid #333;">
					<td colspan="2">{t}Delivery Note{/t}:</td>
				</tr>
				{foreach from=$dns_data item=dn} 
				<tr>
					<td> <a href="dn.php?id={$dn.key}">{$dn.number}</a> <a target='_blank' href="dn.pdf.php?id={$dn.key}"> <img style="height:10px;vertical-align:0px" src="art/pdf.gif"></a> <img onclick="print_pdf('dn',{$dn.key})" style="cursor:pointer;margin-left:2px;height:10px;vertical-align:0px" src="art/icons/printer.png"> </td>
					<td class="right" style="text-align:right"> {$dn.state} </td>
				</tr>
				<tr style="{if $dn.dispatch_state=='Dispatched'}display:none{/if}">
					<td colspan="2" class="aright" style="text-align:right"> {$dn.data} </td>
				</tr>
				<tr>
					<td colspan="2" class="aright" style="text-align:right" id="operations_container{$dn.key}">{$dn.operations}</td>
				</tr>
				<tr style="{if $dn.dispatch_state=='Dispatched'}display:none{/if}">
					<td colspan="2"> 
					<table style="width:100%;margin:0px;">
						<tr>
							<td style="border:1px solid #eee;width:50%;text-align:center" id="pick_aid_container{$dn.key}"><a href="order_pick_aid.php?id={$dn.key}">{t}Picking Aid{/t}</a> <a target='_blank' href="order_pick_aid.pdf.php?id={$dn.key}"> <img style="height:10px;vertical-align:0px" src="art/pdf.gif"></a> <img onclick="print_pdf('order_pick_aid',{$dn.key})" style="cursor:pointer;margin-left:2px;height:10px;vertical-align:0px" src="art/icons/printer.png"> </td>
							<td style="border:1px solid #eee;width:50%;;text-align:center" class="aright" style="text-align:right" id="pack_aid_container{$dn.key}"><a href="order_pack_aid.php?id={$dn.key}">{t}Pack Aid{/t}</a></td>
							</td>
						</tr>
					</table>
					</td>
				</tr>
				{/foreach} 
			</table>
			<table border="0" class="info_block with_title">
				<tr style="border-bottom:1px solid #333;">
					<td colspan="2">{t}Invoices{/t}:</td>
				</tr>
				{foreach from=$invoices_data item=invoice} 
				<tr>
					<td> <a href="invoice.php?id={$invoice.key}">{$invoice.number}</a> 
					<a target='_blank' href="invoice.pdf.php?id={$invoice.key}"> <img style="height:10px;vertical-align:0px" src="art/pdf.gif"></a> <img onclick="print_pdf('invoice',{$invoice.key})" style="cursor:pointer;margin-left:2px;height:10px;vertical-align:0px" src="art/icons/printer.png">
					</td>
					<td class="right" style="text-align:right"> {$invoice.state} </td>
				</tr>
				<tr>
					<td colspan="2" class="aright" style="text-align:right"> {$invoice.data} </td>
				</tr>
				<tr>
					<td colspan="2" class="right" style="text-align:right" id="operations_container{$invoice.key}">{$invoice.operations}</td>
				</tr>
				{/foreach} 
				<tr style="{if !( $order->get_number_invoices()==0)}display:none{/if}">
					<td colspan="2" class="right" style="text-align:right"> 
					<div class="buttons small right">
						<button id="create_invoice"><img id="create_invoice_img" src="art/icons/money.png" alt=""> {t}Create Invoice{/t}</button> 
					</div>
					</td>
				</tr>
				
			</table>
		
</div>
<div style="clear:both">
</div>
<img id="show_order_details" style="cursor:pointer" src="art/icons/arrow_sans_lowerleft.png" /> 
<div id="delivery_notes_container">
</div>
</div>
{*} 
<div id="msg_dispatched_post_transactions" style="{if !$order->get_number_post_order_transactions()}display:none;{/if}border:1px solid #fd4646;padding:5px 10px;background:#ff6969;color:#fff;xtext-align:center;text-weight:800">
	{t}This order has some post transactions{/t} <span onclick="show_dispatched_post_transactions()" style="font-size:90%;cursor:pointer">({t}Show details){/t}</span> 
</div>
{*} {include file='order_payments_splinter.tpl'} 
<div style="{if !$order->get_number_post_order_transactions()}display:none;{/if}border:1px solid #ccc;padding:5px 5px 10px 5px;" id="dispatched_post_transactions">
	<div class="buttons small">
		<button onclick="window.location='new_post_order.php?id={$order->id}'"><img src="art/icons/page_white_edit.png" alt=""> {t}Post Dispatch Operations{/t}</button> <button id="quick_resend_process" style="{if $order->get('Order Current Post Dispatch State')=='Dispatched'}display:none{/if}"><img src="art/icons/lightning.png" alt=""> {t}Quick Resend Process{/t}</button> 
	</div>
	<h2 style="margin-left:5px">
		{t}Post-Order Transactions{/t} <span class="subtitle" style="font-size:80%">({$order->get('Order Current Post Dispatch State')})</span> 
	</h2>
	<div id="table1" class="dtable btable" style="margin-bottom:0;font-size:80%">
	</div>
</div>
<h2>
	{t}Ordered Items{/t} 
</h2>
<div id="table0" class="dtable btable" style="margin-bottom:0;font-size:80%">
</div>
</div>
<div id="process_order_dialog" style="width:400px;padding:20px 20px 0 20px;display:none">
	<div id="assign_pickers_packers">
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
</div>
{include file='add_payment_splinter.tpl'}
{include file='footer.tpl'} 