<div class="order">
<div id="contact_data" class="" style=";">
	<div class="data_container">
		<div class="data_field">
			<i class="fa fa-user"></i> <span>{$order->get('Order Customer Name')}</span> 
		</div>
		<div class="data_field ">
			<i class="fa fa-black-tie"></i></i> <span>{$order->get('Order Tax Number')}</span> 
		</div>
	</div>
	<div class="data_container">
		<div class="data_field {if !$order->get('Customer Main Plain Email')}hide{/if}">
			<i class="fa fa-at"></i> <span>{$order->get('Customer Main XHTML Email')}</span> 
		</div>
		<div class="data_field {if !$order->get('Customer Main XHTML Telephone')}hide{/if}">
			<i class="fa fa-phone"></i> <span>{$order->get('Customer Main XHTML Telephone')}</span> 
		</div>
		<div class="data_field {if !$order->get('Customer Main XHTML Mobile')}hide{/if}">
			<i class="fa fa-mobile"></i> <span>{$order->get('Customer Main XHTML Mobile')}</span> 
		</div>
	</div>
	<div style="clear:both">
	</div>
	<div id="billing_address_container" class="data_container">
		<div style="min-height:80px;float:left;width:24px;text-align:left">
			<i style="position:relative;right:5px" class="fa fa-usd fa-fw"></i> 
		</div>
		<div style="font-size:90%;float:left;min-width:150px;max-width:210px;">
			{$order->get('Order XHTML Billing Tos')} 
		</div>
	</div>
	<div id="delivery_address_container" class="data_container" style="">
		<div style="min-height:80px;float:left;">
			<i class="fa fa-truck  fa-fw"></i> 
		</div>
		<div style="font-size:90%;float:left;min-width:150px;max-width:210px;">
			{$order->get('Order XHTML Ship Tos')} 
		</div>
	</div>
	<div style="clear:both">
	</div>
</div>
<div id="totals" >
<div style="{if $order->data['Order Invoiced']=='Yes'}display:none{/if}">
				<table border="0" class="info_block">
						<tr {if $order->
						get('Order Out of Stock Net Amount')==0 }style="display:none"{/if} id="tr_order_items_out_of_stock" > 
						<td class="aright">{t}Out of stock{/t}</td>
						<td width="100" class="aright"><span id="order_items_out_of_stock">{$order->get('Out of Stock Net Amount')}</span></td>
					</tr>
					
					<tr {if $order->
						get('Order Items Discount Amount')==0 }style="display:none"{/if} id="tr_order_items_gross" > 
						<td class="aright">{t}Items Gross{/t}</td>
						<td width="100" class="aright" id="order_items_gross">{$order->get('Items Gross Amount After No Shipped')}</td>
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

                    <tr {if $order->get('Order Deal Amount Off')==0 }style="display:none"{/if} id="tr_order_amount_off" > 
						<td class="aright">{t}Ammount Off{/t}</td>
						<td width="100" class="aright"><span id="order_amount_off">{$order->get('Deal Amount Off')}</span></td>
					</tr>
					{*}
					<tr id="tr_order_credits" {if $order->
						get('Order Net Credited Amount')==0}style="display:none"{/if}> 
						<td class="aright"><img style="visibility:hidden;cursor:pointer" src="/art/icons/edit.gif" id="edit_button_credits" /> {t}Credits{/t}</td>
						<td width="100" class="aright" id="order_credits">{$order->get('Net Credited Amount')}</td>
					</tr>
					{*}
					<tr id="tr_order_items_charges">
						<td class="aright"><img style="visibility:hidden;cursor:pointer" src="/art/icons/edit.gif" id="edit_button_items_charges" /> {t}Charges{/t}</td>
						<td id="order_charges" width="100" class="aright">{$order->get('Charges Net Amount')}</td>
					</tr>
					<tr id="tr_order_shipping">
						<td class="aright"> <img style="{if $order->get('Order Shipping Method')=='On Demand'}visibility:visible{else}visibility:hidden{/if};cursor:pointer" src="/art/icons/edit.gif" id="edit_button_shipping" /> {t}Shipping{/t}</td>
						<td id="order_shipping" width="100" class="aright">{$order->get('Shipping Net Amount')}</td>
					</tr>
					<tr {if $order->get('Order Insurance Net Amount')==0 }style="display:none"{/if} id="tr_order_insurance" > 
						<td class="aright"> {t}Insurance{/t}</td>
						<td id="order_insurance" width="100" class="aright">{$order->get('Insurance Net Amount')}</td>
					</tr>
					<tr class="top-border" >
						<td class="aright">{t}Net{/t}</td>
						<td id="order_net" width="100" class="aright">{$order->get('Balance Net Amount')}</td>
					</tr>
					<tr id="tr_order_tax" class="top-border">
						<td class="aright"><img style="visibility:hidden;cursor:pointer" src="/art/icons/edit.gif" id="edit_button_tax" /> <span style="display:none" id="tax_info">{$order->get_formated_tax_info_with_operations()}</span></td>
						<td id="order_tax" width="100" class="aright">{$order->get('Balance Tax Amount')}</td>
					</tr>
					<tr class="top-strong-border {if $account->get('Account Currency')==$order->get('Order Currency')}bottom-strong-border{/if}">
						<td class="aright">{t}Total{/t}</td>
						<td id="order_total" width="100" class="aright" style="font-weight:800">{$order->get('Balance Total Amount')}</td>
					</tr>
					<tr>
					<tr style="{if $account->get('Account Currency')==$order->get('Order Currency')}display:none{/if}" class="exchange bottom-strong-border">

					    <td class="aright">{$account->get('Account Currency')}/{$order->get('Order Currency')} {if $order->get('Order Currency Exchange')!=0}{(1/$order->get('Order Currency Exchange'))|string_format:"%.3f"}{else}ND!{/if}</td>


					<td width="100" class="aright">{$order->get('Corporate Currency Balance Total Amount')}</td>
				</tr>
					
					
					<tr id="tr_order_total_paid" >
						<td class="aright"><img id="order_paid_info" style="height:14px;vertical-align:-1.5px" src="/art/icons/information.png" title="{$order->get('Order Current XHTML Payment State')}"> {t}Paid{/t}</td>
						<td id="order_total_paid" width="100" class="aright">{$order->get('Payments Amount')}</td>
					</tr>
					<tr id="tr_order_total_to_pay" style="{if $order->get('Order To Pay Amount')==0}display:none{/if}">
						<td class="aright"> 
						
						<i class="fa fa-money" id="show_add_payment" amount="{$order->get('Order To Pay Amount')}" onclick="add_payment('order','{$order->id}')"></i>

						
						
						<span style="{if $order->get('Order To Pay Amount')>0}display:none{/if}" id="to_refund_label">{t}To Refund{/t}</span> 
						<span style="{if $order->get('Order To Pay Amount')<0}display:none{/if}" id="to_pay_label">{t}To Pay{/t}</span></td>
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
					  <tr {if $order->get('Order Deal Amount Off')==0 }style="display:none"{/if} id="tr_order_amount_off" > 
						<td class="aright">{t}Ammount Off{/t}</td>
						<td width="100" class="aright"><span id="order_items_discount">{$order->get('Deal Amount Off')}</span></td>
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
					
					
					 {if $order->get('Order Invoiced Total Net Adjust Amount')!=0} 
					<tr class="adjust" style="color:red">
						<td class="aright">{t}Adjusts (N){/t}</td>
						<td width="100" class="aright">{$order->get('Invoiced Total Net Adjust Amount')}</td>
					</tr>
					{/if} 
					<tr style="border-top:1px solid #bbb">
						<td class="aright">{t}Total Net{/t}</td>
						<td width="100" class="aright">{$order->get('Invoiced Total Net Amount')}</td>
					</tr>
					
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
					<tr>
					    <tr style="{if $account->get('Account Currency')==$order->get('Order Currency')}display:none{/if}" class="exchange">
					    <td class="aright">{$account->get('Account Currency')}/{$order->get('Order Currency')} {if $order->get('Order Currency Exchange')!=0}{(1/$order->get('Order Currency Exchange'))|string_format:"%.3f"}{else}ND!{/if}</td>
					    <td width="100" class="aright">{$order->get('Corporate Currency Invoiced Total Amount')}</td>
				    </tr>
					
					{if $order->get('Order Invoiced Refund Net Amount')!=0} 
					<tr style="border-top:1px solid #777;">
						<td class="aright"><i>{t}Refunds (N){/t}</i></td>
						<td width="100" class="aright">{$order->get('Invoiced Refund Net Amount')}</td>
					</tr>
					{/if}
					{if $order->get('Order Invoiced Refund Tax Amount')!=0} 
					<tr >
						<td class="aright"><i>{t}Refunds (Tax){/t}</i></td>
						<td width="100" class="aright">{$order->get('Invoiced Refund Tax Amount')}</td>
					</tr>
					{/if} 
					{if $order->get('Order Invoiced Refund Net Amount')!=0 or $order->get('Order Invoiced Refund Tax Amount')!=0  } 
					<tr style="border-bottom:1px solid #777;">
						<td class="aright"><i>{t}Refunds Total{/t}</i></td>
						<td width="100" class="aright">{$order->get('Invoiced Refund Total Amount')}</td>
					</tr>
					{/if} 
					
					<tr id="tr_order_total_paid_invoiced" style="border-top:1px solid #777;">
						<td class="aright"><img id="order_paid_info_invoiced" src="/art/icons/information.png" style="height:14px;vertical-align:-1.5px" title="{$order->get('Order Current XHTML Payment State')}"> {t}Paid{/t}</td>
						<td id="order_total_paid_invoiced" width="100" class="aright">{$order->get('Payments Amount')}</td>
					</tr>
					
						<tr id="tr_order_total_paid_refunded" style="display:none">
						<td class="aright"> {t}Refunded{/t}</td>
						<td id="order_total_paid_refunded" width="100" class="aright">{$order->get('Paid Refunds Amount')}</td>
					</tr>
					
					<tr id="tr_order_total_to_pay_invoiced" style="{if $order->get('Order To Pay Amount')==0}display:none{/if}">
						<td class="aright"> 
						
						<span style="{if $order->get('Order To Pay Amount')>0}display:none{/if}" id="to_refund_label_invoiced">{t}To Refund{/t}</span> 
						<span style="{if $order->get('Order To Pay Amount')<0}display:none{/if}" id="to_pay_label_invoiced">{t}To Pay{/t}</span></td>
						<td id="order_total_to_pay_invoiced" width="100" class="aright" style="font-weight:800">{$order->get('To Pay Amount')}</td>
					</tr>
					
					
				</table>
			</div>

<div id="sticky_note_div" class="sticky_note pink" style="position:relative;left:-20px;width:270px;{if $order->get('Sticky Note')==''}display:none{/if}">
	<img id="sticky_note_bis" style="float:right;cursor:pointer" src="/art/icons/edit.gif"> 
	<div id="sticky_note_content" style="padding:10px 15px 10px 15px;">
		{$order->get('Sticky Note')} 
	</div>
</div>


<div style="clear:both">
</div>

</div>
<div id="dates" >
	<table border="0" class="date_and_state" >
		
			<tr>
				<td colspan="2" class="date" title="{$order->get('Date')}">{$order->get_date('Order Date')}</td>
			</tr>
			<tr class="state two-columns" >
				<td>{$order->get_formated_dispatch_state()}</td>
				<td>{$order->get_formated_payment_state()}</td>
			</tr>
		</table>
		<table id="delivery_notes" border="0" class="ul_table" >
			{foreach from=$order->get_delivery_notes_objects() item=dn} 
			<tr>
				<td class="icon" ><i class="fa fa-fw fa-truck"></i> </td>
				<td colspan="2"> <span class="link" onclick="change_view('order/{$order->id}/delivery_note/{$dn->id}')" ">{$dn->get('Delivery Note ID')}</span> <a class="pdf_link" target='_blank' href="/dn.pdf.php?id={$dn->id}"> <img style="" src="/art/pdf.gif"></a> </td>
				<td class="state" >{$dn->get('Delivery Note XHTML State')} </td>
			</tr>
			<tr>
				<td class="more_dn_opertions">  </td>
				<td colspan="3" class="state"> {$dn->get_info()} </td>
			</tr>
			<tr id="dn_operations_tr_{$dn->id}" style="{if $dn->get('Delivery Note State')=='Dispatched'}display:none{/if}">
				<td colspan="3" class="state" id="operations_container{$dn->id}">{$dn->get_operations($user,'order',$order->id)}</td>
			</tr>
			<tr style="{if $dn->get('Delivery Note State')=='Dispatched'}display:none{/if};border-bottom:1px solid #ccc;border-top:1px solid #eee" >
				<td colspan="4"> 
				<table border=0 style="width:100%;margin:0px;font-size:80%;">
					<tr>
						<td style="border-right:1px solid #eee;width:50%;text-align:center" id="pick_aid_container{$dn->id}"><span class="link" onClick="change_view('order/{$order->id}/pick_aid/{$dn->id}')" >{t}Picking Aid{/t}</span> <a class="pdf_link"  target='_blank' href="pdf/order_pick_aid.pdf.php?id={$dn->id}"> <img  src="/art/pdf.gif"></a>  </td>
						<td style="text-align:center" class="aright" id="pack_aid_container{$dn->id}"><span  class="link" onClick="change_view('order/{$order->id}/pack_aid/{$dn->id}')">{t}Pack Aid{/t}</span></td>
					</tr>
				</table>
				</td>
			</tr>
			{/foreach} 
		</table>
		<table id="invoices" border="0" class="ul_table" >
			{foreach  from=$order->get_invoices_objects() item=invoice} 
			<tr>
				<td class="icon" ><i class="fa fa-fw fa-usd"></i> </td>
				<td> <span class="link" onclick="change_view('order/{$order->id}/invoice/{$invoice->id}')">{$invoice->get('Invoice Public ID')}</span> <a class="pdf_link" target='_blank' href="/pdf/invoice.pdf.php?id={$invoice->id}"> <img  src="/art/pdf.gif"></a>  </td>
				<td style="text-align:right;padding-right:10px;font-size:80%;"> {$invoice->get_formated_payment_state()} </td>
			</tr>
			
			<tr>
				<td colspan="2" class="right" style="text-align:right" id="operations_container{$invoice->id}">{$invoice->get_operations($user,'order',$order->id)}</td>
			</tr>
			{/foreach} 
		</table>
		
	</div>
</div>



<div style="clear:both">
</div>

<script>

$('#totals').height( $('#object_showcase').height() )
$('#dates').height( $('#object_showcase').height() )
</script>