<div class="delivery_note">
<div id="contact_data" class="block" style="float:left;padding:20px 20px;max-width:500px;min-height:170px;">
	<div class="data_container">
		<div class="data_field">
			<i class="fa fa-user"></i> <span>{$delivery_note->get('Delivery Note Customer Name')}</span> 
		</div>
		
	</div>
	
	
	<div style="clear:both">
	</div>
	<div id="billing_address_container" class="data_container" style="">
		<div style="min-height:80px;float:left;width:16px">
			<i class="fa fa-map-marker"></i></i> 
		</div>
		<div style="font-size:90%;float:left;min-width:150px;max-width:220px;">
			{$delivery_note->get('Delivery Note XHTML Ship To')} 
		</div>
	</div>
	
	
	<div style="clear:both">
	</div>
</div>



<div id="totals" class="block totals" >

<table border="0" class="info_block">
				<tr id="edit_weight_tr" >
					<td class="aright"> {t}Weight{/t}:</td>
					<td class="aright"><span id="formated_parcels_weight">
					{if $weight==''}<span onclick="show_dialog_set_dn_data()" style="font-style:italic;color:#777;cursor:pointer">{t}Set weight{/t}</span>
					{else}{$weight}{/if}</span></td>
				</tr>
				<tr id="edit_parcels_tr">
					<td class="aright"> {t}Parcels{/t}:</td>
					<td class="aright"><span id="formated_number_parcels">{if $parcels==''}<span onclick="show_dialog_set_dn_data()" style="font-style:italic;color:#777;cursor:pointer">{t}Set parcels{/t}</span>{else}{$parcels}{/if}</span></td>
				</tr>
				<tr id="edit_consignment_tr">
					<td class="aright"> {t}Courier{/t}:</td>
					<td class="aright"><span id="formated_consignment">{if $consignment==''}<span onclick="show_dialog_set_dn_data()" style="font-style:italic;color:#777;cursor:pointer">{t}Set consignment{/t}</span>{else}{$consignment}{/if}</span></td>
				</tr>
				{if $delivery_note->get('Delivery Note Date Start Picking')!='' or $delivery_note->get('Delivery Note Picker Assigned Alias')!=''} 
				<tr>
					<td class="aright"> {if $delivery_note->get('Delivery Note Date Finish Picking')==''}{t}Picking by{/t}{else}{t}Picked by{/t}{/if}: </td>
					<td width="200px" class="aright">{$delivery_note->get('Delivery Note Assigned Picker Alias')} </td>
				</tr>
					{if $delivery_note->get('Delivery Note Date Finish Picking')!=''} 
					<tr>
						<td class="aright">{t}Finish picking{/t}:</td>
						<td class="aright">{$delivery_note->get('Date Finish Picking')}</td>
					</tr>
					{else if $delivery_note->get('Delivery Note Date Finish Picking')!=''} 
					<tr>
						<td class="aright">{t}Start picking{/t}:</td>
						<td class="aright">{$delivery_note->get('Date Start Picking')}</td>
					</tr>
					{/if} 
				
				{/if} {if $delivery_note->get('Delivery Note Date Start Packing')!='' or $delivery_note->get('Delivery Note Packer Assigned Alias')!=''} 
				<tr>
					<td class="aright"> {if $delivery_note->get('Delivery Note Date Finish Packing')==''}{t}Packing by{/t}{else}{t}Packed by{/t}{/if}: </td>
					<td width="200px" class="aright">{$delivery_note->get('Delivery Note XHTML Packers')} </td>
				</tr>
					{if $delivery_note->get('Delivery Note Date Finish Packing')!=''} 
					<tr>
						<td class="aright">{t}Finish packing{/t}:</td>
						<td class="aright">{$delivery_note->get('Date Finish Packing')}</td>
					</tr>
					{else if $delivery_note->get('Delivery Note Date Finish Packing')!=''} 
					<tr>
						<td class="aright">{t}Start packing{/t}:</td>
						<td class="aright">{$delivery_note->get('Date Start Packing')}</td>
					</tr>
					{/if} 
				
				{/if} 
			</table>

<div id="sticky_note_div" class="sticky_note pink" style="position:relative;left:-20px;width:270px;{if $delivery_note->get('Sticky Note')==''}display:none{/if}">
	<img id="sticky_note_bis" style="float:right;cursor:pointer" src="/art/icons/edit.gif"> 
	<div id="sticky_note_content" style="padding:10px 15px 10px 15px;">
		{$delivery_note->get('Sticky Note')} 
	</div>
</div>

 
<div style="clear:both">
</div>
</div>
<div id="dates" class="block dates" style="float:right;border-left:1px solid #ccc;height:100%;;width:300px">
	<table border="0" class="date_and_state">
		<tr>
		<tr>
			<td colspan="2" class="date" title="{$delivery_note->get('Date')}">{$delivery_note->get_date('Delivery Note Date')}</td>
		</tr>
		<tr>
			<td colspan="2" class="state">{$delivery_note->get_formated_state()}</td>
		</tr>
		<tr class="state two-columns">
			<td id="pick_aid_container{$delivery_note->id}"><span class="link" onclick="delivery_note('order/{$delivery_note->id}/pick_aid')">{t}Picking Aid{/t}</span> <a class="pdf_link" target='_blank' href="pdf/order_pick_aid.pdf.php?id={$delivery_note->id}"> <img src="/art/pdf.gif"></a> </td>
			<td id="pack_aid_container{$delivery_note->id}"><span class="link" onclick="change_view('delivery_note/{$delivery_note->id}/pack_aid')">{t}Pack Aid{/t}</span></td>
		</tr>
	</table>
	<table id="orders" border="0" class="ul_table">
		{foreach from=$delivery_note->get_orders_objects() item=order} 
		<tr>
			<td class="icon"><i class="fa fa-fw fa-shopping-cart"></i> </td>
			<td> <span class="link" onclick="change_view('delivery_note/{$delivery_note->id}/order/{$order->id}')">{$order->get('Order Public ID')}</span> </td>
			
		</tr>
		
		{/foreach} 
	</table>
		<table id="invoices" border="0" class="ul_table">
		{foreach from=$delivery_note->get_invoices_objects() item=invoice} 
		<tr>
			<td class="icon"><i class="fa fa-fw fa-usd"></i> </td>
			<td> <span class="link" onclick="change_view('delivery_note/{$delivery_note->id}/invoice/{$invoice->id}')">{$invoice->get('Invoice Public ID')}</span> <a class="pdf_link" target='_blank' href="/pdf/invoice.pdf.php?id={$invoice->id}"> <img src="/art/pdf.gif"></a> </td>
			<td style="text-align:right;padding-right:10px;font-size:80%;"> {$invoice->get_formated_payment_state()} </td>
		</tr>
		<tr>
			<td colspan="2" class="right" style="text-align:right" id="operations_container{$invoice->id}">{$invoice->get_operations($user,'delivery_note',$delivery_note->id)}</td>
		</tr>
		{/foreach} 
	</table>
</div>
<div style="clear:both">
</div>

</div>
<script>

$('#totals').height( $('#object_showcase').height() )
$('#dates').height( $('#object_showcase').height() )
</script>