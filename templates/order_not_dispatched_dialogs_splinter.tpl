<div id="dialog_send_to_warehouse" style="position:absolute;left:-1000px;padding:5px 20px 5px 10px;width:250px">
	<div id="send_to_warehouse_msg">
	</div>
	<table class="edit" style="width:100%" border=0>
	<tr id="send_to_warehouse_buttons">
			<td colspan="2"> 
			<div class="buttons" style="margin-right:0px;position:relative;left:15px">
				<button onclick="save('send_to_warehouse')" id="send_to_warehouse_save" class="positive"><img id="send_to_warehouse_img" src="art/icons/cart_go.png" alt="">  {t}Send to Warehouse{/t}</button> <button class="negative" onclick="close_dialog('send_to_warehouse')">{t}Close{/t}</button> 
			</div>
			</td>
		</tr>
	<tr style="height:22px;display:none" id="send_to_warehouse_wait">
			<td colspan="2" style="text-align:right;padding-right:20px"> <img src="art/loading.gif" alt="" /> {t}Processig Request{/t} </td>
		</tr>
	
		<tr class="title">
			<td colspan="2">{t}Send to warehouse{/t}</td>
		</tr>
		<tr style="height:7px">
			<td colspan="2"></td>
		</tr>
		<tr>
			<td colspan="2">{t}Notes{/t}</td>
		</tr>
		<tr>
			<td colspan="2"> <textarea style="height:100px;width:100%" id="send_to_warehouse_input" onkeyup="change(event,this,'send_to_warehouse')"></textarea> </td>
		</tr>
		
		
	</table>
</div>

<div id="dialog_cancel" style="position:absolute;left:-1000px;padding:15px 20px 5px 10px;width:200px">
	<div id="cancel_msg">
	</div>
	<table class="edit" style="width:100%">
		<tr class="title">
			<td colspan="2">{t}Cancel Order{/t}</td>
		</tr>
		<tr style="height:7px">
			<td colspan="2"></td>
		</tr>
		<tr>
			<td colspan="2">{t}Reason of cancellation{/t}</td>
		</tr>
		<tr>
			<td colspan="2"> <textarea style="height:100px;width:100%" id="cancel_input" onkeyup="change(event,this,'cancel')"></textarea> </td>
		</tr>
		<tr id="cancel_buttons">
			<td colspan="2"> 
			<div class="buttons">
				<button onclick="save('cancel')" id="cancel_save" class="positive disabled">{t}Continue{/t}</button> <button class="negative" onclick="close_dialog('cancel')">{t}Close{/t}</button> 
			</div>
			</td>
		</tr>
		<tr style="height:22px;display:none" id="cancel_wait">
			<td colspan="2" style="text-align:right;padding-right:20px"> <img src="art/loading.gif" alt="" /> {t}Processig Request{/t} </td>
		</tr>
	</table>
</div>

<div id="dialog_edit_shipping" style="position:absolute;left:-1000px;border:1px solid #ccc;text-align:left;padding:10px;">
	<div id="edit_shipping_msg">
	</div>
	<table style="margin:10px" border="0">
		<tr id="calculated_shipping_tr">
			<td colspan="3" style="text-align:right;border-bottom:1px solid #ccc"> 
			<div class="buttons small">
				<button id="use_calculate_shipping">{t}Use auto value{/t}</button> 
			</div>
			</td>
		</tr>
		<tr>
			<td style="padding-top:10px">{t}Set Shipping{/t}:</td>
			<td style="padding-top:10px;width:100px"> 
			<input id="shipping_amount" style="text-align:right" value="{$order->get('Order Shipping Net Amount')}" />
			</td>
			<td style="padding-top:10px;width:65px"> 
			<div class="buttons small">
				<img id="save_set_shipping_wait" style="display:none" src="art/loading.gif" alt="" /><button id="save_set_shipping" class="positive">{t}Save{/t}</button> 
			</div>
			</td>
		</tr>
	</table>
</div>

<div id="dialog_edit_items_charges" style="position:absolute;left:-1000px;border:1px solid #ccc;text-align:left;padding:10px;">
	<div id="edit_items_charges_msg">
	</div>
	<table style="margin:10px" border="0">
		<tr id="calculated_items_charges_tr">
			<td colspan="3" style="text-align:right;border-bottom:1px solid #ccc"> 
			<div class="buttons small">
				<button id="use_calculate_items_charges">{t}Use auto value{/t}</button> 
			</div>
			</td>
		</tr>
		<tr>
			<td style="padding-top:10px">{t}Set Charges{/t}:</td>
			<td style="padding-top:10px;width:100px"> 
			<input id="items_charges_amount" style="text-align:right" value="{$order->get('Order Charges Net Amount')}" />
			</td>
			<td style="padding-top:10px;width:65px"> 
			<div class="buttons">
				<img id="save_set_items_charges_wait" style="display:none;left:20px" src="art/loading.gif" alt="" /><button id="save_set_items_charges">{t}Save{/t}</button> 
			</div>
			</td>
		</tr>
	</table>
</div>

<div id="dialog_sending_to_warehouse" style="position:absolute;left:-1000px;border:1px solid #ccc;text-align:left;padding:20px;">
	<div id="sending_to_warehouse_waiting">
		<img src="art/loading.gif" alt="" /> {t}Processing Request{/t}
	</div>
	<div id="sending_to_warehouse_msg">
	</div>
</div>

<div id="change_staff_discount" style="position:absolute;left:-1000px;padding:10px 20px 0px 10px">
	<input type="hidden" id="change_discount_transaction_key" value="" />
	<input type="hidden" id="change_discount_record_key" value="" />
	<div class="bd">
		<table class="edit" border="0">
			<tr class="title">
				<td colspan="3">{t}Product Discount{/t}</td>
			</tr>
			<tr style="height:10px">
				<td colspan="3"></td>
			</tr>
			<tr style="height:20px; border:none; ">
				<td style="padding-right:25px ">{t}Discount{/t}: </td>
				<td style="text-align:right;padding:0"> 
				<input style="text-align:right;padding-right:2px" onkeyup="validate_discount_percentage(this)" type="text" id="change_discount_value" value="" />
				</td>
				<td style="text-align:left;padding-left:2px">%</td>
			</tr>
			<tr style="height:10px">
				<td colspan="3"></td>
			</tr>
			<tr id="change_staff_discount_buttons">
				<td colspan="3"> 
				<div class="buttons">
					<button id="change_discount_save" class="positive disabled">{t}Save{/t}</button> <button id="change_discount_cancel" class="negative">{t}Cancel{/t}</button> 
				</div>
				</td>
			</tr>
			<tr id="change_staff_discount_waiting" style="display:none">
				<td colspan="3" style="text-align:right;"> <img src="art/loading.gif"> {t}Processing your request{/t} </td>
			</tr>
		</table>
	</div>
</div>

<div id="edit_delivery_address_splinter_dialog" class="edit_block" style="position:absolute;left:-1000px;width:890px;padding:20px 20px 20px 20px;background:#fff;">
	
	{include file='edit_delivery_address_splinter.tpl' default_country_2alpha=$store->get('Store Home Country Code 2 Alpha') parent='order' order_key={$order->id}} 
	<div class="buttons">
		<button id="close_edit_delivery_address_dialog" onclick="close_edit_delivery_address_dialog()" class="negative">{t}Close{/t}</button> 
	</div>
</div>

<div id="edit_billing_address_splinter_dialog" class="edit_block" style="position:absolute;left:-1000px;width:890px;padding:20px 20px 20px 20px;background:#fff;" >
	
	{include file='edit_billing_address_splinter.tpl' default_country_2alpha=$store->get('Store Home Country Code 2 Alpha') parent='order' order_key={$order->id}} 
	<div class="buttons">
		<button id="close_edit_billing_address_dialog" onclick="close_edit_billing_address_dialog()" class="negative">{t}Close{/t}</button> 
	</div>
</div>

<div id="dialog_check_tax_number" style="position:absolute;left:-1000px;padding:10px 20px 10px 10px;width:300px">
		<table style="width:100%;margin:5px auto;padding:0px 10px" class="edit">
			<tr class="title">
				<td colspan="2">{t}Tax Number:{/t} <span id="tax_number_to_check">{$customer->get('Customer Tax Number')}</span> </td>
			</tr>
			<tr id="check_tax_number_result_tr" style="display:none">
				<td colspan="2" id="check_tax_number_result"> </td>
			</tr>
			<tr id="check_tax_number_name_tr" style="display:none">
				<td>{t}Name:{/t}</td>
				<td id="check_tax_number_name"> </td>
			</tr>
			<tr id="check_tax_number_address_tr" style="display:none">
				<td>{t}Address:{/t}</td>
				<td id="check_tax_number_address"> </td>
			</tr>
			<tr id="check_tax_number_wait">
				<td colspan="2"> <img src="art/loading.gif" alt=""> {t}Processing Request{/t} </td>
			</tr>
			<tr id="check_tax_number_buttons" style="display:none">
				<td colspan="2"> 
				<div class="buttons" style="margin-top:10px">
					<button id="save_tax_details_match">{t}Details Match{/t}</button> <button id="save_tax_details_not_match">{t}Details not match{/t}</button> <button id="close_check_tax_number">{t}Close{/t}</button> 
				</div>
				</td>
			</tr>
		</table>
	</div>

<div id="dialog_add_credit" style="position:absolute;left:-1000px;border:1px solid #ccc;text-align:left;padding:10px;padding-top:20px">
	<div id="edit_shipping_msg">
	</div>
	<table class="edit" style="margin:10px;width:400px" border="0">
		<tr>
			<td class="label" style="padding-top:10px">{t}Tax{/t}:</td>
			<td style="padding-top:10px;"> 
			<div class="buttons left small" id="add_credit_tax_categories_options">
				<input id="add_credit_tax_category" value="{$order->get('Order Tax Code')}" type="hidden" />
				{foreach from=$tax_categories item=tax_category} <button tax_category="{$tax_category.code}" onclick="change_tax_category_add_credit(this)" class="item {if $tax_category.selected}selected{/if}">{$tax_category.label}</button> {/foreach} 
			</div>
			</td>
		</tr>
		<tr>
			<td class="label" style="padding-top:4px;width:80px">{t}Net Amount{/t}:</td>
			<td style="padding-top:4px;"> 
			<input id="add_credit_amount" style="text-align:right;width:80px" value="" />
			</td>
		</tr>
		<tr>
			<td class="label" style="padding-bottom:10px">{t}Description{/t}:</td>
			<td style="padding-bottom:10px;width:300px"> 
			<input id="add_credit_description" style="width:95%" value="" />
			</td>
		</tr>
		<tr>
			<td colspan="2"> 
			<div class="buttons">
				<img id="save_add_credit_wait" style="display:none;position:relative;left:20px" src="art/loading.gif" alt="" /> <button id="save_add_credit" class="positive">{t}Save{/t}</button> <button id="close_add_credit" class="negative" onclick="close_dialog_add_credit()">{t}Close{/t}</button> 
			</div>
			</td>
		</tr>
	</table>
</div>

<div id="dialog_edit_credits" style="position:absolute;left:-1000px;border:1px solid #ccc;text-align:left;padding:10px;padding-top:20px">
	<div id="edit_shipping_msg">
	</div>
	<table class="edit" style="margin:10px;width:400px" border="0">
		<input id="credit_transaction_key" value="{$credit.transaction_key}" type="hidden" />
		<tr class="top title">
			<td>{t}Credit{/t}</td>
			<td>
			<div class="buttons small">
				<button id="remove_credit" class="negative">{t}Remove Credit{/t}</button>
			</div>
			</td>
		</tr>
		<tr>
			<td class="label" style="padding-top:10px">{t}Tax{/t}:</td>
			<td style="padding-top:10px;"> 
			<div class="buttons left small" id="edit_credit_tax_categories_options">
				<input id="edit_credit_tax_category" value="{$credit.tax_code}" type="hidden" />
				{foreach from=$tax_categories item=tax_category} <button tax_category="{$tax_category.code}" onclick="change_tax_category_edit_credit(this)" class="item {if $tax_category.code==$credit.tax_code}selected{/if}">{$tax_category.label}</button> {/foreach} 
			</div>
			</td>
		</tr>
		<tr>
			<td class="label" style="padding-top:4px;width:80px">{t}Net Amount{/t}:</td>
			<td style="padding-top:4px;"> 
			<input id="edit_credit_amount" style="text-align:right;width:80px" value="{$credit.net}" />
			</td>
		</tr>
		<tr>
			<td class="label" style="padding-bottom:10px">{t}Description{/t}:</td>
			<td style="padding-bottom:10px;width:300px"> 
			<input id="edit_credit_description" style="width:95%" value="{$credit.description}" />
			</td>
		</tr>
		<tr>
			<td colspan="2"> 
			<div class="buttons">
				<img id="save_edit_credit_wait" style="display:none;position:relative;left:20px" src="art/loading.gif" alt="" /> <button id="save_edit_credit" class="positive">{t}Save{/t}</button> <button id="close_edit_credit" class="negative" onclick="close_dialog_edit_credit()">{t}Close{/t}</button> 
			</div>
			</td>
		</tr>
		<tr style="height:10px">
			<td colspan="2"></td>
		</tr>
		<tr class="top title">
			<td colspan="2">{t}Credit from previous orders{/t}</td>
		</tr>
	</table>
</div>

<div id="dialog_edit_tax_category" style="position:absolute;left:-1000px;border:1px solid #ccc;text-align:left;padding:10px;padding-top:20px;width:350px">
	<div class="buttons small">
		<input type="hidden" id="original_tax_code" value="{$order->get('Order Tax Code')}"> {foreach from=$tax_categories item=tax_category} <button tax_category="{$tax_category.code}" onclick="change_tax_category(this)" class="item {if $tax_category.selected}selected{/if}">{$tax_category.label}</button> {/foreach} 
	</div>
</div>

<div id="dialog_set_tax" style="position:absolute;left:-1000px;padding:10px;width:400px">


<input type="hidden" id="invalid_tax_number_label" value="{t}Invalid tax number{/t}"> 
		<table style="margin:10px" border=0>
			<tr>
				<td>{t}Tax Number:{/t}</td>
				<td style="width:220px"> 
				<div >
					<input style="width:100%" type="text" id="Order_Tax_Number" value="{$order->get('Order Tax Number')}" ovalue="{$customer->get('Order Tax Number')}" valid="0"> 
					<div id="Order_Tax_Number_Container">
					</div>
				</div>
				</td>
			</tr>
			<tr>
				<td colspan="2"> 
				<div class="buttons" style="margin-top:10px">
					<span id="Order_Tax_Number_msg" class="edit_td_alert"></span> <button class="positive" onClick="save_quick_edit_tax_number()">{t}Save{/t}</button> <button class="negative" onClick="close_quick_edit_tax_number()">{t}Cancel{/t}</button> 
				</div>
				</td>
			</tr>
		</table>
	</div>


<div id="dialog_process_delivery_note" style="width:400px;padding:20px 10px 0px 10px;display:none">
	<input type="hidden" id="dn_key" value="assign_picker"> 
	<input type="hidden" id="picker_key" value=""> 
	<input type="hidden" id="packer_key" value=""> 

		
	<table class="edit" border="0" style="width:100%">
		<tbody id="picker_data">
		<tr class="title">
			<td colspan="2"> {t}Picker{/t} </td>
		</tr>
		<tr>
			<td colspan="2"> 
			<div class="options" style="width:350px;padding:0 10px;text-align:center">
				<table border="0" style="margin:auto" id="assign_picker_buttons">
					{if !isset($number_pickers) or $number_pickers==0} 
					<tr>
						<td onclick="show_other_staff(this)" id="picker_show_other_staff" td_id="other_staff_pack_it" class="assign_picker_button other" onclick="show_other_staff(this)">{t}Select Picker{/t}</td>
					</tr>
					{else} {foreach from=$pickers item=picker_row name=foo} 
					<tr>
						{foreach from=$picker_row key=row_key item=picker } 
						<td staff_id="{$picker.StaffKey}" id="picker{$picker.StaffKey}" scope="picker" class="assign_picker_button" onclick="select_staff(this,event)">{$picker.StaffAlias}</td>
						{/foreach} 
						<td onclick="show_other_staff(this)" id="picker_show_other_staff" td_id="other_staff_picker" class="assign_picker_button other" onclick="show_other_staff(this)">{t}Other{/t}</td>
					</tr>
					{/foreach} {/if} 
				</table>
			</div>
			</td>
		</tr>
		<tr style="display:none" id="Picker_Staff_Name_tr">
			<td class="label">{t}Staff Name{/t}:</td>
			<td style="text-align:left"> 
			<div>
				<input style="text-align:left;width:180px" id="Picker_Staff_Name" value="" ovalue="" valid="0"> 
				<div id="Picker_Staff_Name_Container">
				</div>
			</div>
			</td>
		</tr>
		</tbody>
		
		<tbody id="packer_data">
		<tr class="title">
			<td colspan="2"> {t}Packer{/t} </td>
		</tr>
		<tr>
			<td colspan="2"> 
			<div class="options" style="width:350px;padding:0 10px;text-align:center">
				<table border="0" style="margin:auto" id="assign_packer_buttons">
					{if !isset($number_packers) or $number_packers==0} 
					<tr>
						<td onclick="show_other_staff(this)" id="packer_show_other_staff" td_id="other_staff_pack_it" class="assign_packer_button other" onclick="show_other_staff(this)">{t}Select Picker{/t}</td>
					</tr>
					{else} {foreach from=$packers item=packer_row name=foo} 
					<tr>
						{foreach from=$packer_row key=row_key item=packer } 
						<td staff_id="{$packer.StaffKey}" id="packer{$packer.StaffKey}" scope="packer" class="assign_packer_button" onclick="select_staff(this,event)">{$packer.StaffAlias}</td>
						{/foreach} 
						<td onclick="show_other_staff(this)" id="packer_show_other_staff" td_id="other_staff_packer" class="assign_packer_button other" onclick="show_other_staff(this)">{t}Other{/t}</td>
					</tr>
					{/foreach} {/if} 
				</table>
			</div>
			</td>
		</tr>
		<tr style="display:none" id="Packer_Staff_Name_tr">
			<td class="label">{t}Staff Name{/t}:</td>
			<td style="text-align:left"> 
			<div>
				<input style="text-align:left;width:180px" id="Packer_Staff_Name" value="" ovalue="" valid="0"> 
				<div id="Packer_Staff_Name_Container">
				</div>
			</div>
			</td>
		</tr>
		</tbody>
		
		<tbody id="parcels">
		
				<tr class="title">
			<td>{t}Parcels{/t}</td>
		</tr>
		<tr class="first">
			<td class="label" style="width:65px"><span id="parcels_weight_msg" class="edit_td_alert"></span> {t}Weight{/t}:</td>
			<td style="width:200px" colspan="2"> 
			<div>
				<input style="width:100px" id="parcels_weight" changed="0" type='text' class='text' value="" ovalue="" />
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
				<input style="width:30px" id="number_parcels" onclick="select()" changed="0" type='text' class='text' value="" ovalue="" />
				<div id="number_parcels_Container">
				</div>
			</div>
			<td style="width:325px"> 
			<input id="parcel_type" value="" ovalue="" type="hidden" />
			<div class="buttons small left" id="parcel_type_options">
				<button onclick="change_parcel_type(this)" class="parcel_type" id="parcel_Pallet" valor="Pallet">{t}Pallet{/t}</button> 
				<button onclick="change_parcel_type(this)" class="parcel_type" id="parcel_Envelope" valor="Envelope">{t}Envelope{/t}</button> 
				<button onclick="change_parcel_type(this)" class="parcel_type" id="parcel_Small Parcel" valor="Small Parcel">{t}Small Parcel{/t}</button> 
				<button onclick="change_parcel_type(this)" class="parcel_type" id="parcel_Box" valor="Box">{t}Box{/t}</button> 
				<button onclick="change_parcel_type(this)" class="parcel_type" id="parcel_None" valor="None">{t}None{/t}</button> 
				<button onclick="change_parcel_type(this)" class="parcel_type" id="parcel_Other" valor="Other">{t}Other{/t}</button> 
			</div>
			<span id="parcel_type_msg" class="edit_td_alert"></span> </td>
		</tr>
		<tr class="title">
			<td>{t}Courier{/t}</td>
		</tr>
		<tr>
			<td class="label" style="width:65px">{t}Company{/t}:</td>
			<td colspan="2"> 
			<input type="hidden" id="shipper_code" value="" ovalue=""> 
			<div class="buttons small left" id="shipper_code_options">
				{foreach from=$shipper_data item=item key=key } <button style="margin-bottom:5px;min-width:120px" class="{if $item.selected>0}selected{/if} option" id="shipper_code_{$item.code}" onclick="change_shipper('{$item.code}')">{$item.code}</button> {/foreach} 
			</div>
			<span id="shipper_code_msg" class="edit_td_alert"></span> </td>
		</tr>
		<tr>
			<td class="label" style="width:65px"> <span id="consignment_number_msg" class="edit_td_alert"></span> {t}Consignment{/t}:</td>
			<td style="width:200px" colspan="2"> 
			<div>
				<input style="width:250px" id="consignment_number" changed="0" type='text' class='text' value="" ovalue="" />
				<div id="consignment_number_Container">
				</div>
			</div>
			</td>
		</tr>
		</tbody>
		
		<tr class="buttons">
			<td></td>
			<td colspan="2" id="pick_it_msg" class="edit_td_alert"></td>
			<tr>
				<td colspan="2"> 
				<div class="buttons">
					<button class="positive" onclick="assign_picker_save()">{t}Go{/t}</button> <button class="negative" onclick="close_dialog('assign_picker_dialog')">{t}Cancel{/t}</button> 
				</div>
				<td> 
			</tr>
		</tr>
	</table>
</div>