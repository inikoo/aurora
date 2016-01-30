<div class="invoice">
<div id="contact_data" class="block" style="float:left;padding:20px 20px;max-width:500px;">
	<div class="data_container">
		<div class="data_field">
			<i class="fa fa-user"></i> <span>{$invoice->get('Invoice Customer Name')}</span> 
		</div>
		<div class="data_field ">
			<i class="fa fa-black-tie"></i></i> <span>{$invoice->get('Invoice Tax Number')}</span> 
		</div>
	</div>
	<div class="data_container">
		<div class="data_field {if !$invoice->get('Customer Main Plain Email')}hide{/if}">
			<i class="fa fa-at"></i> <span>{$invoice->get('Customer Main XHTML Email')}</span> 
		</div>
		<div class="data_field {if !$invoice->get('Customer Main XHTML Telephone')}hide{/if}">
			<i class="fa fa-phone"></i> <span>{$invoice->get('Customer Main XHTML Telephone')}</span> 
		</div>
		<div class="data_field {if !$invoice->get('Customer Main XHTML Mobile')}hide{/if}">
			<i class="fa fa-mobile"></i> <span>{$invoice->get('Customer Main XHTML Mobile')}</span> 
		</div>
	</div>
	<div style="clear:both">
	</div>
	<div id="billing_address_container" class="data_container" style="">
		<div style="min-height:80px;float:left;width:16px">
			<i class="fa fa-map-marker"></i></i> 
		</div>
		<div style="font-size:90%;float:left;min-width:150px;max-width:220px;">
			{$invoice->get('Invoice XHTML Address')} 
		</div>
	</div>
	
	
	<div style="clear:both">
	</div>
</div>



<div id="totals" class="block totals">


<table border="0" >
				{if $invoice->get('Invoice Items Discount Amount')!=0 } 
				<tr>
					<td class="aright">{t}Items Gross{/t}</td>
					<td  class="aright">{$invoice->get('Items Gross Amount')}</td>
				</tr>
				<tr>
					<td class="aright">{t}Discounts{/t}</td>
					<td  class="aright">-{$invoice->get('Items Discount Amount')}</td>
				</tr>
				{/if} 
				<tr>
					<td class="aright">{t}Items Net{/t}</td>
					<td  class="aright">{$invoice->get('Items Net Amount')}</td>
				</tr>
				
					{if $invoice->get('Invoice Net Amount Off')!=0 } 
				<tr>
					<td class="aright">{t}Amount Off{/t}</td>
					<td class="aright">{$invoice->get('Net Amount Off')}</td>
				</tr>
				{/if} 
				 
				
				{if $invoice->get('Invoice Refund Net Amount')!=0 } 
				<tr>
					<td class="aright">{t}Credits{/t}</td>
					<td class="aright">{$invoice->get('Refund Net Amount')}</td>
				</tr>
				{/if} 
				<tr>
					<td class="aright">{t}Charges{/t}</td>
					<td class="aright">{$invoice->get('Charges Net Amount')}</td>
				</tr>
				{if $invoice->get('Invoice Total Net Adjust Amount')!=0} 
				<tr style="color:red">
					<td class="aright">{t}Adjust Net{/t}</td>
					<td class="aright">{$invoice->get('Total Net Adjust Amount')}</td>
				</tr>
				{/if} 
				<tr>
					<td class="aright">{t}Shipping{/t}</td>
					<td class="aright">{$invoice->get('Shipping Net Amount')}</td>
				</tr>
				<tr>
					<td class="aright">{t}Insurance{/t}</td>
					<td class="aright">{$invoice->get('Insurance Net Amount')}</td>
				</tr>
				{if $invoice->get('Invoice Credit Net Amount')!=0}
				<tr >
					<td class="aright">{t}Credits{/t}</td>
					<td class="aright">{$invoice->get('Credit Net Amount')}</td>
				</tr>
				{/if}
				
				<tr class="top-border">
					<td class="aright">{t}Total Net{/t}</td>
					<td class="aright">{$invoice->get('Total Net Amount')}</td>
				</tr>
				{foreach from=$tax_data item=tax } 
				<tr>
					<td class="aright">{$tax.name}</td>
					<td class="aright">{$tax.amount}</td>
				</tr>
				{/foreach} 
				{if $invoice->get('Invoice Total Tax Adjust Amount')!=0} 
				<tr style="color:red">
					<td class="aright">{t}Adjust Tax{/t}</td>
					<td class="aright">{$invoice->get('Total Tax Adjust Amount')}</td>
				</tr>
				{/if} 
				<tr class="top-strong-border {if $account->get('Account Currency')==$invoice->get('Invoice Currency')}bottom-strong-border{/if}">
					<td class="aright">{t}Total{/t}</td>
					<td class="aright"><b>{$invoice->get('Total Amount')}</b></td>
				</tr>
				<tr style="{if $account->get('Account Currency')==$invoice->get('Invoice Currency')}display:none{/if}" class="exchange bottom-strong-border">
					<td class="aright">{$account->get('Account Currency')}/{$invoice->get('Invoice Currency')} {(1/$invoice->get('Invoice Currency Exchange'))|string_format:"%.3f"}</td>
					<td class="aright">{$invoice->get('Corporate Currency Total Amount')}</td>
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

<div id="sticky_note_div" class="sticky_note pink" style="position:relative;left:-20px;width:270px;{if $invoice->get('Sticky Note')==''}display:none{/if}">
	<img id="sticky_note_bis" style="float:right;cursor:pointer" src="/art/icons/edit.gif"> 
	<div id="sticky_note_content" style="padding:10px 15px 10px 15px;">
		{$invoice->get('Sticky Note')} 
	</div>
</div>


<div style="clear:both">
</div>

</div>
<div id="dates" class="block dates">
	<table border="0" class="date_and_state" >
	<tr>
		<tr class="date">
			<td   title="{$invoice->get('Date')}">{$invoice->get_date('Invoice Date')}</td>
		</tr>
		<tr class="state">
			<td >{$invoice->get_formatted_payment_state()}</td>
		</tr>
		</table>
		
		<table id="orders" border="0" class="ul_table">
		{foreach from=$invoice->get_orders_objects() item=order} 
		<tr>
			<td class="icon"><i class="fa fa-fw fa-shopping-cart"></i> </td>
			<td> <span class="link" onclick="change_view('invoice/{$invoice->id}/order/{$order->id}')">{$order->get('Order Public ID')}</span> </td>
			
		</tr>
		
		{/foreach} 
	</table>
		
		<table id="delivery_notes" border="0" class="ul_table" >
			{foreach from=$invoice->get_delivery_notes_objects() item=dn} 
			<tr>
				<td class="icon" ><i class="fa fa-fw fa-truck"></i> </td>
				<td colspan="2"> <span class="link" onclick="change_view('invoice/{$invoice->id}/delivery_note/{$dn->id}')" ">{$dn->get('Delivery Note ID')}</span> <a class="pdf_link" target='_blank' href="/dn.pdf.php?id={$dn->id}"> <img style="" src="/art/pdf.gif"></a> </td>
				<td class="state" >{$dn->get('Delivery Note XHTML State')} </td>
			</tr>
			<tr>
				<td class="more_dn_opertions">  </td>
				<td colspan="3" class="state"> {$dn->get_info()} </td>
			</tr>
			<tr id="dn_operations_tr_{$dn->id}" style="{if $dn->get('Delivery Note State')=='Dispatched'}display:none{/if}">
				<td colspan="3" class="state" id="operations_container{$dn->id}">{$dn->get_operations($user,'invoice',$invoice->id)}</td>
			</tr>
			<tr style="{if $dn->get('Delivery Note State')=='Dispatched'}display:none{/if};border-bottom:1px solid #ccc;border-top:1px solid #eee" >
				<td colspan="4"> 
				<table border=0 style="width:100%;margin:0px;font-size:80%;">
					<tr>
						<td style="border-right:1px solid #eee;width:50%;text-align:center" id="pick_aid_container{$dn->id}"><span class="link" onClick="change_view('delivery_note/{$dn->id}/pick_aid')" >{t}Picking Aid{/t}</span> <a class="pdf_link"  target='_blank' href="pdf/order_pick_aid.pdf.php?id={$dn->id}"> <img  src="/art/pdf.gif"></a>  </td>
						<td style="text-align:center" class="aright" id="pack_aid_container{$dn->id}"><span  class="link" onClick="change_view('delivery_note/{$dn->id}/pack_aid')">{t}Pack Aid{/t}</span></td>
					</tr>
				</table>
				</td>
			</tr>
			{/foreach} 
		</table>
		
		
	</table>
</div>




<div style="clear:both">
</div>
</div>
<script>

$('#totals').height( $('#object_showcase').height() )
$('#dates').height( $('#object_showcase').height() )
</script>