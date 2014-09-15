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
	<input type="hidden" value="{$order->get('Order Customer Key')}" id="subject_key" />
	<input type="hidden" value="customer" id="subject" />
	<input type="hidden" value="{$store->get('Store Home Country Code 2 Alpha')}" id="default_country_2alpha" />
	<input type="hidden" id="to_pay_label_amount" value="{$order->get('Order To Pay Amount')}"> <iframe id="invoice_pdf_printout" width="0" height="0" style="position:absolute;top:-100px"></iframe> <iframe id="dn_pdf_printout" width="0" height="0" style="position:absolute;top:-100px"></iframe> <iframe id="order_pick_aid_pdf_printout" width="0" height="0" style="position:absolute;top:-100px"></iframe> 
	<div class="branch ">
		<span>{if $user->get_number_stores()>1}<a href="orders_server.php">{t}Orders{/t}</a> &rarr; {/if}<a href="orders.php?store={$store->id}&view=orders">{$store->get('Store Code')} {t}Orders{/t}</a> &rarr; {$order->get('Order Public ID')} ({$order->get_formated_dispatch_state()})</span> 
	</div>
	<div class="top_page_menu" style="border:none;">
		<div class="buttons" style="float:left;">
			{if isset($order_prev)}<img class="previous" onmouseover="this.src='art/{if $order_prev.to_end}prev_to_end.png{else}previous_button.gif{/if}'" onmouseout="this.src='art/{if $order_prev.to_end}start_bookmark.png{else}previous_button.png{/if}'" title="{$order_prev.title}" onclick="window.location='{$order_prev.link}'" src="art/{if $order_prev.to_end}start_bookmark.png{else}previous_button.png{/if}" alt="{t}Previous{/t}" />{/if} <span class="main_title {if $order->get('Order Invoiced')=='Yes'}no_buttons{/if} ">Order <span>{$order->get('Order Public ID')}</span> <span class="subtitle">({$order->get_formated_dispatch_state()})</span> </span> 
		</div>
		<div class="buttons">
			{if isset($order_next)}<img class="next" onmouseover="this.src='art/{if $order_next.to_end}prev_to_end.png{else}next_button.gif{/if}'" onmouseout="this.src='art/{if $order_next.to_end}prev_to_end.png{else}next_button.png{/if}'" title="{$order_next.title}" onclick="window.location='{$order_next.link}'" src="art/{if $order_next.to_end}prev_to_end.png{else}next_button.png{/if}" alt="{t}Next{/t}" />{/if} <button onclick="window.open('proforma.pdf.php?id={$order->id}')"><img style="width:40px;height:12px;vertical-align:1px" src="art/pdf.gif" alt=""> {t}Proforma{/t}</button> <button style="height:24px;display:none" onclick="window.location='order.pdf.php?id={$order->id}'"><img style="width:40px;height:12px;position:relative;bottom:3px" src="art/pdf.gif" alt=""></button> {if $order->get_number_invoices()==0} <button id="modify_order"><img style='position:relative;top:1px' src='art/icons/cart_edit.png'> {t}Amend Order{/t}</button> {/if} <button style="display:none" id="process_order">{t}Process Order{/t}</button> 
		</div>
		<div style="clear:both">
		</div>
	</div>
	<div style="clear:both">
	</div>
	<div id="control_panel">
		<div id="addresses">
			<h2 style="padding:0">
				<img src="art/icons/id.png" style="width:20px;position:relative;bottom:2px"> {$order->get('Order Customer Name')} <a href="customer.php?id={$order->get('order customer key')}"><span class="id">{$customer->get_formated_id()}</span></a> 
			</h2>
			<div style="float:left;margin:5px 20px 0 0;color:#444;font-size:90%;width:140px">
				<div id="title_billing_address" style="border-bottom:1px solid #ccc;margin-bottom:5px">
					{t}Billing to{/t}: 
				</div>
				<div style="margin-top:5px" id="billing_address">
					{$order->get('Order XHTML Billing Tos')} 
				</div>
				<div class="buttons small left" style="{if $order->get('Order Invoiced')=='Yes'}display:none{/if}">
					<button id="change_billing_address" class="state_details" style="display:block;margin-top:10px">{t}Change{/t}</button> 
				</div>
			</div>
			<div style="float:left;margin:5px 0 0 0px;color:#444;font-size:90%;width:140px">
				<div id="title_delivery_address" style="border-bottom:1px solid #ccc;{if $order->get('Order For Collection')=='Yes'}display:none;{/if};margin-bottom:5px">
					{t}Delivering to{/t}: 
				</div>
				<div id="title_for_collection" style="border-bottom:1px solid #ccc;{if $order->get('Order For Collection')=='No'}display:none;{/if};margin-bottom:5px">
					&nbsp; 
				</div>
				<div class="address_box" id="delivery_address">
					{$order->get('Order XHTML Ship Tos')} 
				</div>
				<div id="shipping_address" style="{if $order->get('Order For Collection')=='Yes'  or $order->get('Order Invoiced')=='Yes'}display:none{/if};margin-top:10px" class="buttons left small">
					<button id="change_delivery_address">{t}Change{/t}</button> <br />
					<button style="margin-top:3px;{if $store->get('Store Can Collect')=='No'}display:none{/if}" id="set_for_collection" onclick="change_shipping_type('Yes')">{t}Set for collection{/t}</button> 
				</div>
				<div id="for_collection" style="{if $order->get('Order For Collection')=='No' or $order->get('Order Invoiced')=='Yes'}display:none;{/if};margin-top:10px" class="buttons left small">
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
					<tr {if $order->
						get('Order Out of Stock Net Amount')==0 }style="display:none"{/if} id="tr_order_items_out_of_stock" > 
						<td class="aright">{t}Out of stock{/t}</td>
						<td width="100" class="aright"><span id="order_items_out_of_stock">{$order->get('Out of Stock Net Amount')}</span></td>
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
					<tr {if $order->
						get('Order Insurance Net Amount')==0 }style="display:none"{/if} id="tr_order_insurance" > 
						<td class="aright"> {t}Insurance{/t}</td>
						<td id="order_insurance" width="100" class="aright">{$order->get('Insurance Net Amount')}</td>
					</tr>
					<tr style="border-top:1px solid #777">
						<td class="aright">{t}Net{/t}</td>
						<td id="order_net" width="100" class="aright">{$order->get('Balance Net Amount')}</td>
					</tr>
					<tr id="tr_order_tax" style="border-bottom:1px solid #777">
						<td class="aright"><img style="visibility:hidden;cursor:pointer" src="art/icons/edit.gif" id="edit_button_tax" /> <span id="tax_info">{$order->get_formated_tax_info_with_operations()}</span></td>
						<td id="order_tax" width="100" class="aright">{$order->get('Balance Tax Amount')}</td>
					</tr>
					<tr style="border-bottom:1px solid #777">
						<td class="aright">{t}Total{/t}</td>
						<td id="order_total" width="100" class="aright" style="font-weight:800">{$order->get('Balance Total Amount')}</td>
					</tr>
					<tr id="tr_order_total_paid" style="border-top:1px solid #777;">
						<td class="aright"><img id="order_paid_info" style="height:14px;vertical-align:-1.5px" src="art/icons/information.png" title="{$order->get('Order Current XHTML Payment State')}"> {t}Paid{/t}</td>
						<td id="order_total_paid" width="100" class="aright">{$order->get('Payments Amount')}</td>
					</tr>
					<tr id="tr_order_total_to_pay" style="{if $order->get('Order To Pay Amount')==0}display:none{/if}">
						<td class="aright"> 
						<div class="buttons small left">
							<button style="{if $order->get('Order To Pay Amount')<0}display:none{/if}" id="show_add_payment_to_order" amount="{$order->get('Order To Pay Amount')}" onclick="add_payment('order','{$order->id}')"><img src="art/icons/add.png"> {t}Payment{/t}</button> 
						</div>
						<span style="{if $order->get('Order To Pay Amount')>0}display:none{/if}" id="to_refund_label">{t}To Refund{/t}</span> <span style="{if $order->get('Order To Pay Amount')<0}display:none{/if}" id="to_pay_label">{t}To Pay{/t}</span></td>
						<td id="order_total_to_pay" width="100" class="aright" style="font-weight:800">{$order->get('To Pay Amount')}</td>
					</tr>
				</table>
				<div class="buttons small" style="display:none;{if $has_credit}display:none;{/if}clear:both;margin:0px;padding-top:10px">
					<button id="add_credit" style="margin:0px;">{t}Add debit/credit{/t}</button> 
				</div>
			</div>
			<div style="{if $order->data['Order Invoiced']=='No'}display:none{/if}">
				<table border="0" class="info_block">
					<tr>
						<td class="aright">{t}Total Ordered{/t}</td>
						<td width="100" class="aright">{$order->get('Total Net Amount')}</td>
					</tr>
					{if $order->get('Order Out of Stock Net Amount')!=0 } 
					<tr>
						<td class="aright">{t}Out of Stock{/t}</td>
						<td width="100" class="aright">{$order->get('Out of Stock Net Amount')}</td>
					</tr>
					{/if} 
					<tr style="font-size:70%;border-top:1px solid #ccc;border-bottom:1px solid #eee;">
						<td style="text-align:right">{t}Invoiced Amounts{/t}</td>
						<td></td>
					</tr>
					<tr>
						<td class="aright">{t}Items{/t}</td>
						<td width="100" class="aright">{$order->get('Invoiced Items Amount')}</td>
					</tr>
					<tr>
						<td class="aright">{t}Shipping{/t}</td>
						<td width="100" class="aright">{$order->get('Invoiced Shipping Amount')}</td>
					</tr>
					{if $order->get('Order Invoiced Charges Amount')!=0} 
					<tr>
						<td class="aright">{t}Charges{/t}</td>
						<td width="100" class="aright">{$order->get('Invoiced Charges Amount')}</td>
					</tr>
					{/if} 
					<tr {if $order->
						get('Order Invoiced Insurance Amount')==0 }style="display:none"{/if} > 
						<td class="aright"> {t}Insurance{/t}</td>
						<td id="order_insurance" width="100" class="aright">{$order->get('Invoiced Insurance Amount')}</td>
					</tr>
					{if $order->get('Order Invoiced Refund Net Amount')!=0} 
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
						<td class="aright">{t}Total Net{/t}</td>
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
					<tr id="tr_order_total_paid_invoiced" style="border-top:1px solid #777;">
						<td class="aright"><img id="order_paid_info_invoiced" src="art/icons/information.png" style="height:14px;vertical-align:-1.5px" title="{$order->get('Order Current XHTML Payment State')}"> {t}Paid{/t}</td>
						<td id="order_total_paid_invoiced" width="100" class="aright">{$order->get('Payments Amount')}</td>
					</tr>
					<tr id="tr_order_total_to_pay_invoiced" style="{if $order->get('Order To Pay Amount')==0}display:none{/if}">
						<td class="aright"> 
						<div class="buttons small left">
							<button style="{if $order->get('Order To Pay Amount')<0}display:none{/if}" id="show_add_payment_to_order_invoiced" amount="{$order->get('Order To Pay Amount')}" onclick="add_payment('order','{$order->id}')"><img src="art/icons/add.png"> {t}Payment{/t}</button> 
						</div>
						<span style="{if $order->get('Order To Pay Amount')>0}display:none{/if}" id="to_refund_label_invoiced">{t}To Refund{/t}</span> <span style="{if $order->get('Order To Pay Amount')<0}display:none{/if}" id="to_pay_label_invoiced">{t}To Pay{/t}</span></td>
						<td id="order_total_to_pay_invoiced" width="100" class="aright" style="font-weight:800">{$order->get('To Pay Amount')}</td>
					</tr>
				</table>
			</div>
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
					<td>{t}Payment Method{/t}:</td>
					<td class="aright">{$order->get_formated_payment_state()}</td>
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
				<tr>
					<td colspan="2" class="aright" style="text-align:right"> {$dn.data} </td>
				</tr>
				<tr>
					<td colspan="2" class="aright" style="text-align:right" id="operations_container{$dn.key}">{$dn.operations}</td>
				</tr>
				<tr>
					<td colspan="2"> 
					<table style="width:100%;margin:0px;">
						<tr>
							<td style="border:1px solid #eee;width:50%;text-align:center" id="pick_aid_container{$dn.key}"><a href="order_pick_aid.php?id={$dn.key}&order_key={$order->id}">{t}Picking Aid{/t}</a> <span id="print_picking_aid" style="{if !($dn.dispatch_state=='Picker & Packer Assigned' or $dn.dispatch_state=='Packer Assigned' or $dn.dispatch_state=='Ready to be Picked' or $dn.dispatch_state=='Picker Assigned' )   }display:none{/if}"><a target='_blank' href="order_pick_aid.pdf.php?id={$dn.key}"> <img style="height:10px;vertical-align:0px" src="art/pdf.gif"></a> <img onclick="print_pdf('order_pick_aid',{$dn.key})" style="cursor:pointer;margin-left:2px;height:10px;vertical-align:0px" src="art/icons/printer.png"></span> </td>
							<td style="border:1px solid #eee;width:50%;;text-align:center" class="aright" style="text-align:right" id="pack_aid_container{$dn.key}"><a href="order_pack_aid.php?id={$dn.key}&order_key={$order->id}">{t}Pack Aid{/t}</a></td>
							</td>
						</tr>
					</table>
					</td>
				</tr>
				{/foreach} 
			</table>
			<table border="0" class="info_block with_title">
				{if $number_invoices>0 or $order->get('Order Current Dispatch State')=='Packed Done' } 
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
				<tr style="{if !($order->get('Order Current Dispatch State')=='Packed Done' and $order->get_number_invoices()==0)}display:none{/if}">
					<td colspan="2" class="right" style="text-align:right"> 
					<div class="buttons small right">
						<button id="create_invoice"><img id="create_invoice_img" src="art/icons/money.png" alt=""> {t}Create Invoice{/t}</button> 
					</div>
					</td>
				</tr>
				{/if} 
			</table>
		</div>
		<div style="clear:both">
		</div>
		<img id="show_order_details" style="cursor:pointer" src="art/icons/arrow_sans_lowerleft.png" /> 
		<div id="order_details_panel" style="display:none;border-top:1px solid #ccc;padding-top:10px;margin-top:10px">
			<div class="buttons small right">
				<button style="{if $order->get('Order Apply Auto Customer Account Payment')=='No'}display:none{/if}" onclick="update_auto_account_payments('No')">{t}Don't add account credits{/t}</button> 
				<button style="{if $order->get('Order Apply Auto Customer Account Payment')=='Yes'}display:none{/if}" onclick="update_auto_account_payments('Yes')">{t}Add account credits{/t}</button> 			
				<button style="{if $order->get('Order Invoiced')=='Yes'}display:none{/if}" id="cancel" class="negative">{t}Cancel Order{/t}</button> 

			</div>
			<div style="width:550px">
				<table border="0" class="info_block">
					<tr>
						<td>{t}Created{/t}:</td>
						<td class="aright">{$order->get('Created Date')}</td>
					</tr>
					{if $order->get('Order Submitted by Customer Date')!='' } 
					<tr>
						<td>{t}Submited{/t}:</td>
						<td class="aright">{$order->get('Submitted by Customer Date')}</td>
					</tr>
					{/if} 
					<tr>
						<td>{t}Send to warehouse{/t}:</td>
						<td class="aright">{$order->get('Send to Warehouse Date')}</td>
					</tr>
					<tr>
						<td>{t}Picking & packing done{/t}:</td>
						<td class="aright">{$order->get('Packed Done Date')}</td>
					</tr>
				</table>
				<table border="0" class="info_block">
					<tr>
						<td>{t}Customer Fiscal Name{/t}:</td>
						<td class="aright">{$order->get('Order Customer Fiscal Name')}</td>
					</tr>
					<tr>
						<td>{t}Customer Name{/t}:</td>
						<td class="aright">{$order->get('Order Customer Name')}</td>
					</tr>
					<tr>
						<td>{t}Contact Name{/t}:</td>
						<td class="aright">{$order->get('Order Customer Contact Name')}</td>
					</tr>
					<tr>
						<td>{t}Telephone{/t}:</td>
						<td class="aright">{$order->get('Order Telephone')}</td>
					</tr>
					<tr>
						<td>{t}Email{/t}:</td>
						<td class="aright">{$order->get('Order Email')}</td>
					</tr>
				</table>
				<table border="0" class="info_block">
					<tr>
						<td>{t}Tax Code{/t}:</td>
						<td class="aright">{$order->get('Order Tax Code')} {$order->get('Tax Rate')} </td>
					</tr>
					<tr>
						<td>{t}Tax Info{/t}:</td>
						<td class="aright">{$order->get('Order Tax Name')}</td>
					</tr>
				</table>
				<table border="0" class="info_block">
					<tr>
						<td>{t}Weight {/t}:</td>
						<td class="aright">{$order->get('Weight')}</td>
					</tr>
				</table>
			</div>
			<div style="clear:both">
			</div>
			<img id="hide_order_details" style="cursor:pointer;position:relative;top:5px" src="art/icons/arrow_sans_topleft.png" /> 
		</div>
		<div style="clear:both">
		</div>
	</div>
	<div id="payments_list">
		{include file='order_payments_splinter.tpl'} 
	</div>
	<div style="margin-top:20px">
		<span id="table_title" class="clean_table_title">{t}Items{/t}</span> 
		<div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999;margin-bottom:15px">
		</div>
		{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0 } 
		<div id="table0" style="font-size:80%" class="data_table_container dtable btable">
		</div>
		<div style="clear:both;padding-top:10px">
			{foreach from=$order->get_insurances() item=insurance} 
			<div class="insurance_row">
				{$insurance['Insurance Description']} (<b>{$insurance['Insurance Formated Net Amount']}</b>) <span style="widht:100px"> <img insurance_key="{$insurance['Insurance Key']}" onptf_key="{$insurance['Order No Product Transaction Fact Key']}" id="insurance_checked_{$insurance['Insurance Key']}" onclick="remove_insurance(this)" style="{if !$insurance['Order No Product Transaction Fact Key']}display:none{/if}" class="checkbox" src="art/icons/checkbox_checked.png" /> <img insurance_key="{$insurance['Insurance Key']}" id="insurance_unchecked_{$insurance['Insurance Key']}" onclick="add_insurance(this)" style="{if $insurance['Order No Product Transaction Fact Key']}display:none{/if}" class="checkbox" src="art/icons/checkbox_unchecked.png" /> </span> <img insurance_key="{$insurance['Insurance Key']}" id="insurance_wait_{$insurance['Insurance Key']}" style="display:none" class="checkbox" src="art/loading.gif" /> 
			</div>
			{/foreach} 
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
{include file='order_not_dispatched_dialogs_splinter.tpl'} {include file='add_payment_splinter.tpl'} {include file='assign_picker_packer_splinter.tpl'} {include file='footer.tpl'} 