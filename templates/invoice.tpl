{include file='header.tpl'} 
<div id="bd" style="{*}{if $invoice->get('Invoice Has Been Paid In Full')=='Yes'}background-image:url('art/stamp.paid.en.png');background-repeat:no-repeat;background-position:280px 50px{/if}{*}">
	<input type="hidden" id="invoice_key" value="{$invoice->id}" />
	<input type="hidden" id="invoice_type" value="{$invoice->get('Invoice Type')}" />
		<input type="hidden" id="corporate_currency" value="{$corporate_currency}" />

	
	
	<input type="hidden" value="{$invoice->get('Invoice Currency')}" id="currency_code" />
	<input type="hidden" value="{$decimal_point}" id="decimal_point" />
	<input type="hidden" value="{$thousands_sep}" id="thousands_sep" />

	{include file='orders_navigation.tpl'} 
	<div class="branch">
		<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a>&rarr; {if $user->get_number_stores()>1}<a href="orders_server.php?view=invoices">&#8704; {t}Invoices{/t}</a> &rarr; {/if} <a href="orders.php?store={$store->id}&view=invoices">{t}Invoices{/t} ({$store->get('Store Code')})</a> &rarr; {$invoice->get('Invoice Public ID')}</span> 
	</div>
	<div class="top_page_menu" style="border:none">
		<div class="buttons" style="float:left">
			<span class="main_title">{$invoice_type_label} <span>{$invoice->get('Invoice Public ID')}</span></span> 
		</div>
		<div class="buttons">
			<span class="state_details" id="done" style="display:none;float:right;margin-left:40px;{if $invoice->get('Invoice Outstanding Total Amount')==0}display:none{/if}"> <span style="display:none;color:#000;font-size:150%">To pay: {$invoice->get('Outstanding Total Amount')}</span> <button style="display:none;margin-left:5px" id="charge"><img id="charge_img" src="art/icons/coins.png" alt=""> {t}Charge{/t}</button></span> <button style="height:24px;" onclick="window.open('invoice.pdf.php?id={$invoice->id}')"><img style="width:40px;height:12px;position:relative;bottom:3px" src="art/pdf.gif" alt=""></button> 
		</div>
		<div style="clear:both">
		</div>
	</div>
	<div id="control_panel">
		<div id="addresses">
			<h2 style="padding:0">
				<img src="art/icons/id.png" style="width:20px;position:relative;bottom:2px"> {$invoice->get('Invoice Customer Name')} <a href="customer.php?id={$invoice->get('Invoice Customer Key')}" style="color:SteelBlue">{$customer_id}</a> 
			</h2>
			<div style="padding-left:26px">
				{$invoice->get('Invoice Tax Number')} 
			</div>
			<div style="float:left;line-height: 1.0em;margin:5px 0px;color:#444">
				{$invoice->get('Invoice XHTML Address')} 
			</div>
			<div style="clear:both">
			</div>
		</div>
		<div id="totals">
			<table border="0" style="width:100%;border-top:1px solid #333;border-bottom:1px solid #333;width:100%,padding:0;margin:0;float:right;margin-left:120px">
				{if $invoice->get('Invoice Items Discount Amount')!=0 } 
				<tr>
					<td class="aright">{t}Items Gross{/t}</td>
					<td width="100" class="aright">{$invoice->get('Items Gross Amount')}</td>
				</tr>
				<tr>
					<td class="aright">{t}Discounts{/t}</td>
					<td width="100" class="aright">-{$invoice->get('Items Discount Amount')}</td>
				</tr>
				{/if} 
				<tr>
					<td class="aright">{t}Items Net{/t}</td>
					<td width="100" class="aright">{$invoice->get('Items Net Amount')}</td>
				</tr>
				
					{if $invoice->get('Invoice Net Amount Off')!=0 } 
				<tr>
					<td class="aright">{t}Amount Off{/t}</td>
					<td width="100" class="aright">{$invoice->get('Net Amount Off')}</td>
				</tr>
				{/if} 
				 
				
				{if $invoice->get('Invoice Refund Net Amount')!=0 } 
				<tr>
					<td class="aright">{t}Credits{/t}</td>
					<td width="100" class="aright">{$invoice->get('Refund Net Amount')}</td>
				</tr>
				{/if} 
				<tr>
					<td class="aright">{t}Charges{/t}</td>
					<td width="100" class="aright">{$invoice->get('Charges Net Amount')}</td>
				</tr>
				{if $invoice->get('Invoice Total Net Adjust Amount')!=0} 
				<tr style="color:red">
					<td class="aright">{t}Adjust Net{/t}</td>
					<td width="100" class="aright">{$invoice->get('Total Net Adjust Amount')}</td>
				</tr>
				{/if} 
				<tr>
					<td class="aright">{t}Shipping{/t}</td>
					<td width="100" class="aright">{$invoice->get('Shipping Net Amount')}</td>
				</tr>
				<tr>
					<td class="aright">{t}Insurance{/t}</td>
					<td width="100" class="aright">{$invoice->get('Insurance Net Amount')}</td>
				</tr>
				{if $invoice->get('Invoice Credit Net Amount')!=0}
				<tr >
					<td class="aright">{t}Credits{/t}</td>
					<td width="100" class="aright">{$invoice->get('Credit Net Amount')}</td>
				</tr>
				{/if}
				
				<tr style="border-top:1px solid #777;border-bottom:1px solid #777">
					<td class="aright">{t}Total Net{/t}</td>
					<td width="100" class="aright">{$invoice->get('Total Net Amount')}</td>
				</tr>
				{foreach from=$tax_data item=tax } 
				<tr>
					<td class="aright">{$tax.name}</td>
					<td width="100" class="aright">{$tax.amount}</td>
				</tr>
				{/foreach} 
				{if $invoice->get('Invoice Total Tax Adjust Amount')!=0} 
				<tr style="color:red">
					<td class="aright">{t}Adjust Tax{/t}</td>
					<td width="100" class="aright">{$invoice->get('Total Tax Adjust Amount')}</td>
				</tr>
				{/if} 
				<tr style="border-top:1px solid #777">
					<td class="aright">{t}Total{/t}</td>
					<td width="100" class="aright"><b>{$invoice->get('Total Amount')}</b></td>
				</tr>
				<tr style="{if $corporate_currency==$invoice->get('Invoice Currency')}display:none{/if}" class="exchange">
					<td class="aright">{$corporate_currency}/{$invoice->get('Invoice Currency')} {(1/$invoice->get('Invoice Currency Exchange'))|string_format:"%.3f"}</td>
					<td width="100" class="aright">{$invoice->get('Corporate Currency Total Amount')}</td>
				</tr>
				
				
				<tr id="tr_order_total_to_pay" style="{if $invoice->get('Invoice Outstanding Total Amount')==0}display:none{/if}">
						<td class="aright"> 
						<div class="buttons small left">
							<button style="{if $invoice->get('Invoice Outstanding Total Amount')==0}display:none{/if}" id="show_add_payment" amount="{$invoice->get('Invoice Outstanding Total Amount')}" onclick="add_payment('invoice','{$invoice->id}')"><img src="art/icons/add.png"> {t}Payment{/t}</button> 
						</div>
						<span style="{if $invoice->get('Invoice Outstanding Total Amount')>0}display:none{/if}" id="to_refund_label">{t}To Refund{/t}</span> 
						<span style="{if $invoice->get('Invoice Outstanding Total Amount')<0}display:none{/if}" id="to_pay_label">{t}To Pay{/t}</span></td>
						<td id="order_total_to_pay" width="100" class="aright" style="font-weight:800">{$invoice->get('Outstanding Total Amount')}</td>
					</tr>
			</table>
		</div>
		<div id="dates">
			{if isset($note)} 
			<div class="notes">
				{$note} 
			</div>
			{/if} 
			<table border="0" class="info_block">
				<tr>
					<td>{t}Date{/t}:</td>
					<td class="aright">{$invoice->get('Date')}</td>
				</tr>
				<tr>
					<td class="aleft" style="font-weight:800;font-size:120%">{$invoice->get('Payment State')}</td>
					<td class="aright">{$invoice->get('Payment Method')}</td>
				</tr>
				<tr style="display:none">
					<td>{t}Sales Rep{/t}:</td>
					<td class="aright">{$invoice->get('Invoice XHTML Sales Representative')}</td>
				</tr>
			</table>
			<table border="0" class="info_block with_title" style="{if $invoice->get_number_orders()==0}display:none{/if}">
				<tr style="border-bottom:1px solid #333;">
					<td colspan="2">{t}Orders{/t}:</td>
				</tr>
				{foreach from=$invoice->get_orders_objects() item=order} 
				<tr>
					<td class="aleft"><a href="order.php?id={$order->id}">{$order->get('Order Public ID')}</a> 
					<td class="aright"></td>
				</tr>
				{/foreach} 
			</table>
			<table border="0" class="info_block with_title" style="{if $invoice->get_number_delivery_notes()==0}display:none{/if}">
				<tr style="border-bottom:1px solid #333;">
					<td colspan="2">{t}Delivery Notes{/t}:</td>
				</tr>
				{foreach from=$invoice->get_delivery_notes_objects() item=dn} 
				<tr>
					<td class="aleft"><a href="dn.php?id={$dn->id}">{$dn->get('Delivery Note ID')}</a> <a target='_blank' href="dn.pdf.php?id={$dn->id}"> <img style="height:10px;vertical-align:0px" src="art/pdf.gif"></a> <img onclick="print_pdf('dn',{$dn->id})" style="cursor:pointer;margin-left:2px;height:10px;vertical-align:0px" src="art/icons/printer.png"> </td>
					<td class="aright">{$dn->get('Delivery Note XHTML State')}</td>
				</tr>
				{/foreach} 
			</table>
		</div>
		<div style="clear:both">
		</div>
		<img id="show_invoice_details" style="cursor:pointer" src="art/icons/arrow_sans_lowerleft.png" /> 
		<div id="invoice_details_panel" style="display:none;border-top:1px solid #ccc;padding-top:10px;margin-top:10px">
			<div class="buttons small right" style="float:right;width:350px">
				<button class="negative" id="delete" onclick="show_delete_invoice()">{t}Delete{/t}</button> 
		   				<button style=""  id="recalculate_totals"><img id="recalculate_totals_img" src="art/icons/arrow_rotate_clockwise.png" alt=""> {t}Recalculate Totals{/t}</button> 

			</div>
			<div style="width:450px">
				<table border="0" class="info_block">
					<tr>
						<td>{t}Date{/t}:</td>
						<td class="aright">{$invoice->get('Date')}</td>
					</tr>
					<tr>
						<td>{t}Paid Date{/t}:</td>
						<td class="aright">{$invoice->get('Paid Date')}</td>
					</tr>
				</table>
				<table border="0" class="info_block">
					<tr>
						<td>{t}Customer Name{/t}:</td>
						<td class="aright">{$invoice->get('Invoice Customer Name')}</td>
					</tr>
					<tr>
						<td>{t}Contact Name{/t}:</td>
						<td class="aright">{$invoice->get('Invoice Customer Contact Name')}</td>
					</tr>
					<tr>
						<td>{t}Tax Number{/t}:</td>
						<td class="aright">{$invoice->get('Invoice Tax Number')}</td>
					</tr>
				</table>
				<table border="0" class="info_block">
					<tr>
						<td>{t}Tax Code{/t}:</td>
						<td class="aright">{$invoice->get('Invoice Tax Code')}</td>
					</tr>
				</table>
			</div>
			<div style="clear:both">
			</div>
			<img id="hide_invoice_details" style="cursor:pointer;position:relative;top:5px" src="art/icons/arrow_sans_topleft.png" /> 
		</div>
	</div>
	<div style="padding: 10px 10px 15px 10px;font-size:85%;;margin-bottom:10px;border:1px solid #ccc;margin-top:20px;{if $invoice->get_number_payments()==0}display:none{/if}">
		<table class="edit" id="pending_payment_confirmations" border="0" style="margin-top:0px;padding-top:0px;width:100%;">
			<tr>
				<td colspan="6"> <span id="table_title_items" class="clean_table_title" ">{t}Payments{/t}</span> </td>
			</tr>
			<tr class="title">
				<td>{t}Payment ID{/t}</td>
				<td>{t}Method{/t}</td>
				<td>{t}Date{/t}</td>
				<td>{t}Status{/t}</td>
				<td>{t}Amount{/t}</td>
				<td>{t}Notes{/t}</td>
			</tr>
			{foreach from=$invoice->get_payment_objects('',true,true) item=payment} 
			<tr id="payment_{$payment->get('Payment Key')}" class="payment" payment_key="{$payment->get('Payment Key')}">
				<td>{$payment->get('Payment Key')}</td>
				<td>{$payment->payment_service_provider->get('Payment Service Provider Name')}</td>
				<td id="payment_date_{$payment->get('Payment Key')}">{$payment->get('Created Date')}</td>
				<td>{$payment->get('Payment Transaction Status')}</td>
				<td id="payment_amount_{$payment->get('Payment Key')}">{$payment->formated_invoice_amount}</td>
				<td style="width:300px"> 
				<div class="buttons small" style="{if $payment->get('Payment Transaction Status')!='Pending'}display:none{/if}">
					<button class="negative" onclick="delete_payment({$payment->get('Payment Key')})">{t}Set as deleteled{/t}</button> <button class="positive" onclick="confirm_payment({$payment->get('Payment Key')})">{t}Set as completed{/t}</button> 
				</div>
				</td>
			</tr>
			{/foreach} 
		</table>
	</div>
	<div style="margin-top:20px">
		<span id="table_title_items" class="clean_table_title" >{t}Items{/t}</span> 
		<div class="table_top_bar ">
		</div>
		{include file='table_splinter.tpl' table_id=0 filter_name='' filter_value='' no_filter=1 } 
		<div id="table0" class="data_table_container dtable btable" style="font-size:90%">
		</div>
	</div>
	{if isset($items_out_of_stock)} 
	<div style="clear:both;margin:30px 0">
		<h2>
			{t}Items Out of Stock{/t} 
		</h2>
		<div id="table1" class="dtable btable" style="margin-bottom:0;font-size:80%">
		</div>
	</div>
	{/if} 
	<div id="dialog_pay_invoice" style="padding:20px 20px 10px 20px">
		<div id="type_of_payment" class="buttons left">
			<button id="pay_by_creditcard"><img src="art/icons/creditcards.png" /> {t}Credit Card{/t}</button> <button id="pay_by_bank_transfer"><img src="art/icons/monitor_go.png" /> {t}Bank Transfer{/t}</button> <button id="pay_by_paypal"><img style="width:37px;height:15px" src="art/icons/paypal.png" /> PayPal</button> <button id="pay_by_cash"><img src="art/icons/money.png" /> {t}Cash{/t}</button> <button id="pay_by_cheque"><img src="art/icons/cheque.png" /> {t}Cheque{/t}</button> <button id="pay_by_other">{t}Other{/t}</button> 
		</div>
		<div style="clear:both;height:10px">
		</div>
		<input type="hidden" value="" id="payment_method"> 
		<input type="hidden" value="{$invoice->get('Invoice Total Amount')}" id="invoce_full_amount"> 
		<table>
			<tr>
				<td>{t}Amount Paid{/t}:</td>
				<td style="text-align:right"><span id="amount_paid_total">{$invoice->get('Total Amount')}</span> 
				<input type="text" style="display:none;text-align:right" id="amount_paid" value="{$invoice->get('Invoice Total Amount')}"></td>
				<td> 
				<div class="buttons small">
					<button id="show_other_amount_field" onclick="show_other_amount_field()">{t}Other Amount{/t}</button> <button id="pay_all" style="display:none" onclick="pay_all()">{t}Pay All{/t}</button> 
				</div>
				</td>
			</tr>
			<tr>
				<td>{t}Reference{/t}:</td>
				<td> 
				<input id="payment_reference"></td>
			</tr>
			<tr style="height:5px">
				<td colspan="2"></td>
			</tr>
			<tr>
				<td colspan="2"> 
				<div class="buttons">
					<button class="positive disabled" id="save_paid" onclick="save_paid">{t}Save{/t}</button> <button class="negative" onclick="hide_dialog_pay_invoice()">{t}Cancel{/t}</button> 
				</div>
				</td>
			</tr>
		</table>
	</div>
</div>
<div id="dialog_delete" style="position:absolute;left:-1000px;padding:15px 20px 5px 10px;width:200px">
	<div id="delete_msg">
	</div>
	<table class="edit" style="width:100%">
		<tr class="title">
			<td colspan="2">{t}Delete{/t}</td>
		</tr>
		<tr style="height:7px">
			<td colspan="2"></td>
		</tr>
		<tr>
			<td colspan="2">{t}Reason of deleting{/t}</td>
		</tr>
		<tr>
			<td colspan="2"> <textarea style="height:100px;width:100%" id="delete_input" onkeyup="change(event,this,'delete')"></textarea> </td>
		</tr>
		<tr id="delete_buttons">
			<td colspan="2"> 
			<div class="buttons">
				<button onclick="save('delete')" id="delete_save" class="positive disabled">{t}Continue{/t}</button> <button class="negative" onclick="close_dialog('delete')">{t}Go Back{/t}</button> 
			</div>
			</td>
		</tr>
		<tr style="height:22px;display:none" id="delete_wait">
			<td colspan="2" style="text-align:right;padding-right:20px"> <img src="art/loading.gif" alt="" /> {t}Processig Request{/t} </td>
		</tr>
	</table>
</div>

{include file='add_payment_splinter.tpl' subject='invoice'} 
{include file='footer.tpl'} 