<div id="dialog_cancel" style="padding:15px 20px 5px 10px;width:200px">
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
				<button onclick="save('cancel')" id="cancel_save" class="positive disabled">{t}Continue{/t}</button> <button class="negative" onclick="close_dialog('cancel')">{t}Go Back{/t}</button> 
			</div>
			</td>
		</tr>
		<tr style="height:22px;display:none" id="cancel_wait">
			<td colspan="2" style="text-align:right;padding-right:20px"> <img src="art/loading.gif" alt="" /> {t}Processig Request{/t} </td>
		</tr>
	</table>
</div>
<div id="dialog_edit_shipping" style="border:1px solid #ccc;text-align:left;padding:10px;">
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
			<div class="buttons">
				<img id="save_set_shipping_wait" style="display:none;position:relative;left:20px" src="art/loading.gif" alt="" /><button id="save_set_shipping">{t}Save{/t}</button> 
			</div>
			</td>
		</tr>
	</table>
</div>

<div id="dialog_edit_items_charges" style="border:1px solid #ccc;text-align:left;padding:10px;">
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
				<img id="save_set_items_charges_wait" style="display:none;position:relative;left:20px" src="art/loading.gif" alt="" /><button id="save_set_items_charges">{t}Save{/t}</button> 
			</div>
			</td>
		</tr>
	</table>
</div>


<div id="dialog_sending_to_warehouse" style="border:1px solid #ccc;text-align:left;padding:20px;">
	
	<div id="sending_to_warehouse_waiting"><img src="art/loading.gif" alt=""/> {t}Processing Request{/t}</div>
	<div id="sending_to_warehouse_msg"></div>
	
</div>



<div id="change_staff_discount" style="padding:10px 20px 0px 10px">
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
<div id="edit_delivery_address_splinter_dialog" class="edit_block" style="width:870px;padding:5px 20px 20px 20px;background:#fff;" id="edit_address_dialog">
	<div style="text-align:right;margin-bottom:15px">
		<span onclick="close_edit_delivery_address_dialog()" class="state_details">{t}Close{/t}</span> 
	</div>
	{include file='edit_delivery_address_splinter.tpl'} 
</div>
