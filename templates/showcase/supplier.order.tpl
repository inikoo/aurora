<div class="order ">
	<div id="contact_data" class="block" style="float:left;padding:20px 20px;max-width:500px;">
		<div class="data_container">
			<div class="data_field">
				<i class="fa fa-ship fa-fw" aria-hidden="true" title="{t}Supplier{/t}"></i> <span  onClick="change_view('{if $order->get('Purchase Order Parent')=='Supplier'}supplier{else}agent{/if}/{$order->get('Purchase Order Parent Key')}')"  class="link Purchase_Order_Parent_Name">{$order->get('Purchase Order Parent Name')}</span> 
			</div>
			<div class="data_field">
				<i class="fa fa-share fa-fw" aria-hidden="true" title="Incoterm"></i> <span class="Purchase_Order_Incoterm" >{$order->get('Purchase Order Incoterm')}</span> 
			</div>
			<div class="data_field">
				<i class="fa fa-arrow-circle-right fa-fw" aria-hidden="true" title="{t}Port of export{/t}"></i> <span class="Purchase_Order_Port_of_Export" >{$order->get('Port of Export')}</span> 
			</div>
			<div class="data_field">
				<i class="fa fa-arrow-circle-left fa-fw" aria-hidden="true" title="{t}Port of import{/t}"></i> <span class="Purchase_Order_Port_of_Import">{$order->get('Port of Import')}</span> 
			</div>
		</div>
		<div style="clear:both">
		</div>
		<div style="clear:both">
		</div>
	</div>
	<div id="totals" class="block totals" style="min-width:250px">
		<table border="0" class="info_block">
			
			<tr>
				<td class="label">{t}Cost{/t} ({$order->get('Purchase Order Currency Code')}) </td>
				<td class="aright Purchase_Order_Total_Amount">{$order->get('Total Amount')}</td>
			</tr>
			<tr class="{if $account->get('Account Currency')==$order->get('Purchase Order Currency Code')}hide{/if}">
				<td colspan="2" class="Purchase_Order_Total_Amount_Account_Currency aright ">{$order->get('Total Amount Account Currency')}</td>
			</tr>
			<tr>
				<td class="label">{t}Weight{/t}</td>
				<td class="aright Purchase_Order_Weight">{$order->get('Weight')}</td>
			</tr>
			<tr>
				<td class="label">{t}CBM{/t}</td>
				<td class="aright Purchase_Order_CBM">{$order->get('CBM')}</td>
			</tr>
			
		</table>
		<div style="clear:both">
		</div>
	</div>
	<div id="totals" class="block totals">
		<div id="sticky_note_div" class="sticky_note pink" style="position:relative;left:-20px;width:270px;{if $order->get('Sticky Note')==''}display:none{/if}">
			<img id="sticky_note_bis" style="float:right;cursor:pointer" src="/art/icons/edit.gif"> 
			<div id="sticky_note_content" style="padding:10px 15px 10px 15px;">
				{$order->get('Sticky Note')} 
			</div>
		</div>
		<div style="clear:both">
		</div>
	</div>
	<div id="dates" class="block dates" style="min-width:200px">
		<table border="0" class="date_and_state">
			<tr>
				<td>{$order->get('Creation Date')}</td>
			</tr>
			<tr>
				<td> {$order->get('State')} </td>
			</tr>
			<tr>
				<td> <span class="discrete" title="{t}Estimated delivery date{/t}">&#x212e</span> <span class="Purchase_Order_Estimated_Receiving_Date">{$order->get_formatted_estimated_delivery_date()}</span></td>
			</tr>
		</table>
		<table id="delivery_notes" border="0" class="ul_table">
			{foreach from=$order->get_sdn_objects() item=dn} 
			<tr>
				<td class="icon"><i class="fa fa-fw fa-truck"></i> </td>
				<td colspan="2"> <span class="link" onclick="change_view('order/{$order->id}/delivery_note/{$dn->id}')" ">{$dn->get('Delivery Note ID')}</span> <a class="pdf_link" target='_blank' href="/dn.pdf.php?id={$dn->id}"> <img style="" src="/art/pdf.gif"></a> </td>
				<td class="state">{$dn->get('Delivery Note XHTML State')} </td>
			</tr>
			<tr>
				<td class="more_dn_opertions"> </td>
				<td colspan="3" class="state"> {$dn->get_info()} </td>
			</tr>
			<tr id="dn_operations_tr_{$dn->id}" style="{if $dn->get('Delivery Note State')=='Dispatched'}display:none{/if}">
				<td colspan="3" class="state" id="operations_container{$dn->id}">{$dn->get_operations($user,'order',$order->id)}</td>
			</tr>
			<tr style="{if $dn->get('Delivery Note State')=='Dispatched'}display:none{/if};border-bottom:1px solid #ccc;border-top:1px solid #eee">
				<td colspan="4"> 
				<table border="0" style="width:100%;margin:0px;font-size:80%;">
					<tr>
						<td style="border-right:1px solid #eee;width:50%;text-align:center" id="pick_aid_container{$dn->id}"><span class="link" onclick="change_view('order/{$order->id}/pick_aid/{$dn->id}')">{t}Picking Aid{/t}</span> <a class="pdf_link" target='_blank' href="pdf/order_pick_aid.pdf.php?id={$dn->id}"> <img src="/art/pdf.gif"></a> </td>
						<td style="text-align:center" class="aright" id="pack_aid_container{$dn->id}"><span class="link" onclick="change_view('order/{$order->id}/pack_aid/{$dn->id}')">{t}Pack Aid{/t}</span></td>
					</tr>
				</table>
				</td>
			</tr>
			{/foreach} 
		</table>
		<table id="invoices" border="0" class="ul_table">
			{foreach from=$order->get_invoices_objects() item=invoice} 
			<tr>
				<td class="icon"><i class="fa fa-fw fa-usd"></i> </td>
				<td> <span class="link" onclick="change_view('order/{$order->id}/invoice/{$invoice->id}')">{$invoice->get('Invoice Public ID')}</span> <a class="pdf_link" target='_blank' href="/pdf/invoice.pdf.php?id={$invoice->id}"> <img src="/art/pdf.gif"></a> </td>
				<td style="text-align:right;padding-right:10px;font-size:80%;"> {$invoice->get_formatted_payment_state()} </td>
			</tr>
			<tr>
				<td colspan="2" class="right" style="text-align:right" id="operations_container{$invoice->id}">{$invoice->get_operations($user,'order',$order->id)}</td>
			</tr>
			{/foreach} 
		</table>
	</div>
	<div style="clear:both">
	</div>
</div>
<div style="clear:both">
</div>
<script>

$('#totals').height( $('#object_showcase').height() )
$('#dates').height( $('#object_showcase').height() )





</script>