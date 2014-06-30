<div id="dialog_set_dn_data" style="width:710px;padding:20px 20px 0 20px;">
	<table class="edit" border="0" style="margin-bottom:5px;width:700px">
		<tr class="title">
			<td>{t}Parcels{/t}</td>
		</tr>
		<tr class="first">
			<td class="label" style="width:65px"><span id="parcels_weight_msg" class="edit_td_alert"></span> {t}Weight{/t}:</td>
			<td style="width:200px" colspan="2"> 
			<div>
				<input style="width:100px" id="parcels_weight" changed="0" type='text' class='text' value="{$delivery_note->get('Weight For Edit')}" ovalue="{$delivery_note->get('Weight For Edit')}" />
				<span style="margin-left:110px">Kg</span> 
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
				<input style="width:30px" id="number_parcels"  onclick="select()" changed="0" type='text' class='text' value="{$delivery_note->get('Delivery Note Number Parcels')}" ovalue="{$delivery_note->get('Delivery Note Number Parcels')}" />
				<div id="number_parcels_Container">
				</div>
			</div>
			<td style="width:325px"> 
			<input id="parcel_type" value="{$delivery_note->get('Delivery Note Parcel Type')}" ovalue="{$delivery_note->get('Delivery Note Parcel Type')}" type="hidden" />
			<div class="buttons small left" id="parcel_type_options">
				<button onclick="change_parcel_type(this)" class="parcel_type {if $delivery_note->get('Delivery Note Parcel Type')=='Pallet'}selected{/if}" id="parcel_Pallet" valor="Pallet">{t}Pallet{/t}</button> <button onclick="change_parcel_type(this)" class="parcel_type {if $delivery_note->get('Delivery Note Parcel Type')=='Envelope'}selected{/if}" id="parcel_Envelope" valor="Envelope">{t}Envelope{/t}</button> <button onclick="change_parcel_type(this)" class="parcel_type {if $delivery_note->get('Delivery Note Parcel Type')=='Small Parcel'}selected{/if}" id="parcel_Small Parcel" valor="Small Parcel">{t}Small Parcel{/t}</button> <button onclick="change_parcel_type(this)" class="parcel_type {if $delivery_note->get('Delivery Note Parcel Type')=='Box'}selected{/if}" id="parcel_Box" valor="Box">{t}Box{/t}</button> <button onclick="change_parcel_type(this)" class="parcel_type {if $delivery_note->get('Delivery Note Parcel Type')=='None'}selected{/if}" id="parcel_None" valor="None">{t}None{/t}</button> <button onclick="change_parcel_type(this)" class="parcel_type {if $delivery_note->get('Delivery Note Parcel Type')=='Other'}selected{/if}" id="parcel_Other" valor="Other">{t}Other{/t}</button> 
			</div>
			<span id="parcel_type_msg" class="edit_td_alert"></span> </td>
		</tr>
		<tr class="title">
			<td>{t}Courier{/t}</td>
		</tr>
		<tr>
			<td class="label" style="width:65px">{t}Company{/t}:</td>
			<td colspan="2"> 
			<input type="hidden" id="shipper_code" value="{$delivery_note->get('Delivery Note Shipper Code')}" ovalue="{$delivery_note->get('Delivery Note Shipper Code')}"> 
			<div class="buttons small left" id="shipper_code_options">
				{foreach from=$shipper_data item=item key=key } <button style="margin-bottom:5px;min-width:120px" class="{if $item.selected>0}selected{/if} option" id="shipper_code_{$item.code}" onclick="change_shipper('{$item.code}')">{$item.code}</button> {/foreach} 
			</div>
		</div>
		<span id="shipper_code_msg" class="edit_td_alert"></span> </td>
	</tr>
	<tr>
		<td class="label" style="width:65px"> <span id="consignment_number_msg" class="edit_td_alert"></span> {t}Consignment{/t}:</td>
		<td style="width:200px" colspan="2"> 
		<div>
			<input style="width:250px" id="consignment_number" changed="0" type='text' class='text' value="{$delivery_note->get('Delivery Note Shipper Consignment')}" ovalue="{$delivery_note->get('Delivery Note Shipper Consignment')}" />
			<div id="consignment_number_Container">
			</div>
		</div>
		</td>
	</tr>
	<tr class="buttons">
		<td></td>
		<td colspan="2"> 
		<div class="buttons left">
			<button style="margin-right:10px;" id="reset_edit_delivery_note" class="negative disabled">{t}Reset{/t}</button> <button style="margin-right:10px;" id="save_edit_delivery_note" class="positive disabled">{t}Save{/t}</button> 
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
