{include file='header.tpl'} 
<div id="bd">
	<input type="hidden" id="order_key" value="{$order->id}" />
	<input type="hidden" value="{$order->get('Order Currency')}" id="currency_code" />
	<input type="hidden" value="{$decimal_point}" id="decimal_point" />
	<input type="hidden" value="{$thousands_sep}" id="thousands_sep" />
	<input type="hidden" value="{$order->get('Order Customer Key')}" id="subject_key" />
	<input type="hidden" value="customer" id="subject" />
	<input type="hidden" id="to_pay_label_amount" value="{$order->get('Order To Pay Amount')}"> {include file='orders_navigation.tpl'} 
	<div class="branch ">
		<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a>&rarr; {if $referral=='spo'} {if $user->get_number_stores()>1}<a href="pending_orders.php">&#8704; {t}Pending Orders{/t}</a> &rarr; {/if} <a href="store_pending_orders.php?id={$store->id}">{t}Pending Orders{/t} ({$store->get('Store Code')})</a> {else if $referral=='po'} {if $user->get_number_stores()>1}<a href="pending_orders.php">&#8704; {t}Pending Orders{/t}</a> {/if} {else}{if $user->get_number_stores()>1}<a href="orders_server.php">&#8704; {t}Orders{/t}</a> &rarr; {/if} <a href="orders.php?store={$store->id}&view=orders">{t}Orders{/t} ({$store->get('Store Code')})</a> {/if} &rarr; {$order->get('Order Public ID')}</span> 
	</div>
	<div class="top_page_menu" style="border:none">
		<div class="buttons" style="float:left">
			{if $order_prev.id}<img class="previous" onmouseover="this.src='art/{if $order_prev.to_end}prev_to_end.png{else}previous_button.gif{/if}'" onmouseout="this.src='art/{if $order_prev.to_end}start_bookmark.png{else}previous_button.png{/if}'" title="{$order_prev.title}" onclick="window.location='{$order_prev.link}'" src="art/{if $order_prev.to_end}start_bookmark.png{else}previous_button.png{/if}" alt="{t}Previous{/t}" />{/if} <span class="main_title no_buttons">{t}Order{/t} <span>{$order->get('Order Public ID')}</span> <span class="subtitle">({$order->get_formated_dispatch_state()})</span></span> 
		</div>

			{if $order_next.id}<img class="next" onmouseover="this.src='art/{if $order_next.to_end}prev_to_end.png{else}next_button.gif{/if}'" onmouseout="this.src='art/{if $order_next.to_end}prev_to_end.png{else}next_button.png{/if}'" title="{$order_next.title}" onclick="window.location='{$order_next.link}'" src="art/{if $order_next.to_end}prev_to_end.png{else}next_button.png{/if}" alt="{t}Next{/t}" />{/if} 

			<div class="buttons small" style="position:relative;top:5px">
			{*}<a style="height:14px" href="order.pdf.php?id={$order->id}" target="_blank"><img style="width:40px;height:12px" src="art/pdf.gif" alt=""></a> {*} 
			<button style="{if $order->get_number_post_order_transactions()}display:none;{/if}" onclick="window.location='new_post_order.php?id={$order->id}'"><img src="art/icons/page_white_edit.png" alt=""> {t}Create Post Dispatch Operations{/t}</button> 
				<button id="sticky_note_button"><img src="art/icons/note_pink.png" alt=""> {t}Note{/t}</button> 

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
			{include file='order_totals_splinter.tpl'} {include file='order_sticky_note_splinter.tpl'} 
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
					<td>{t}Dispatched Date{/t}:</td>
					<td class="aright">{$order->get('Dispatched Date')}</td>
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
					<td> <a href="invoice.php?id={$invoice.key}">{$invoice.number}</a> <a target='_blank' href="invoice.pdf.php?id={$invoice.key}"> <img style="height:10px;vertical-align:0px" src="art/pdf.gif"></a> <img onclick="print_pdf('invoice',{$invoice.key})" style="cursor:pointer;margin-left:2px;height:10px;vertical-align:0px" src="art/icons/printer.png"> </td>
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
			<div id="deals_div" style="clear:both">
						{include file='order_deals_splinter.tpl'} 

					</div>
					<div id="vouchers_div">
						{include file='order_vouchers_splinter.tpl' modify_voucher=false} 
					</div>
		</div>
		<div style="clear:both">
		</div>
		<img id="show_order_details" style="cursor:pointer" src="art/icons/arrow_sans_lowerleft.png" /> 
		<div id="order_details_panel" style="display:none;border-top:1px solid #ccc;padding-top:10px;margin-top:10px">
			<div class="buttons small right" style="float:right;width:350px">
				<button style="margin-bottom:10px;clear:both;{if {$order->get('Order Number Products')}==0    or $order->get('Order Current Dispatch State')!='In Process'}display:none{/if} " id="send_to_basket"><img id="send_to_warehouse_img" src="art/icons/basket_back.png" alt=""> {t}Send to basket{/t}</button> <button style="margin-bottom:10px;clear:both;{if $order->get('Order Apply Auto Customer Account Payment')=='No'}display:none{/if}" onclick="update_auto_account_payments('No')">{t}Don't add account credits{/t}</button> <button style="{if $order->get('Order Apply Auto Customer Account Payment')=='Yes'}display:none{/if}" onclick="update_auto_account_payments('Yes')">{t}Add account credits{/t}</button> <button style="margin-top:5px;margin-bottom:10px;clear:both" id="cancel" class="negative">{t}Cancel order{/t}</button> 
			</div>
			{include file='order_details_splinter.tpl'} 
			<div style="clear:both">
			</div>
			<img id="hide_order_details" style="cursor:pointer;position:relative;top:5px" src="art/icons/arrow_sans_topleft.png" /> 
		</div>
	</div>
	<div id="payments_list">
		{include file='order_payments_splinter.tpl'} 
	</div>
	<div style="{if !$order->get_number_post_order_transactions()}display:none;{/if}border:1px solid #ccc;padding:5px 5px 10px 5px;clear:both;margin:20px 0px" id="dispatched_post_transactions">
		<div class="buttons small">
			<button onclick="window.location='new_post_order.php?id={$order->id}'"><img src="art/icons/page_white_edit.png" alt=""> {t}Post Dispatch Operations{/t}</button> <button id="quick_resend_process" style="{if $order->get('Order Current Post Dispatch State')=='Dispatched'}display:none{/if}"><img src="art/icons/lightning.png" alt=""> {t}Quick Resend Process{/t}</button> 
		</div>
		<span class="clean_table_title with_elements_chooser">{t}Post-Order Transactions{/t} <span class="subtitle" style="font-size:80%">({$order->get('Order Current Post Dispatch State')})</span></span> 
		<div class="table_top_bar space">
		</div>
		{include file='table_splinter.tpl' table_id=1 filter_name=$filter_name1 filter_value=$filter_value1 } 
		<div id="table1" class="data_table_container dtable btable" style="margin-bottom:0;font-size:80%">
		</div>
	</div>
	<div class="data_table" style="margin:0px;clear:both;">
		<span class="clean_table_title with_elements_chooser">{t}Ordered Items{/t} </span> 
		<div class="table_top_bar space">
		</div>
		{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0 } 
		<div id="table0" class="data_table_container dtable btable" style="margin-bottom:0;font-size:80%">
		</div>
	</div>
	<div style="clear:both;padding-top:10px;{if $order->get('Order Invoiced')=='Yes'}display:none{/if}">
		{foreach from=$order->get_insurances() item=insurance} 
		<div class="insurance_row">
			{$insurance['Insurance Description']} (<b>{$insurance['Insurance Formated Net Amount']}</b>) <span style="widht:100px"> <img insurance_key="{$insurance['Insurance Key']}" onptf_key="{$insurance['Order No Product Transaction Fact Key']}" id="insurance_checked_{$insurance['Insurance Key']}" onclick="remove_insurance(this)" style="{if !$insurance['Order No Product Transaction Fact Key']}display:none{/if}" class="checkbox" src="art/icons/checkbox_checked.png" /> <img insurance_key="{$insurance['Insurance Key']}" id="insurance_unchecked_{$insurance['Insurance Key']}" onclick="add_insurance(this)" style="{if $insurance['Order No Product Transaction Fact Key']}display:none{/if}" class="checkbox" src="art/icons/checkbox_unchecked.png" /> </span> <img insurance_key="{$insurance['Insurance Key']}" id="insurance_wait_{$insurance['Insurance Key']}" style="display:none" class="checkbox" src="art/loading.gif" /> 
		</div>
		{/foreach} 
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
{include file='add_payment_splinter.tpl' subject='order'} {include file='footer.tpl'} 