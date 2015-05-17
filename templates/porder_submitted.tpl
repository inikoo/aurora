{include file='header.tpl'} 
<input type="hidden" value="{$session_data}" id="session_data" />
<input type="hidden" value="{$products_display_type}" id="products_display_type"> 
<input type="hidden" value="{$po->id}" id="po_key"> 
<input type="hidden" value="{$supplier->id}" id="supplier_key"> 
<input type="hidden" value="{$number_buyers}" id="number_buyers"> 
<input type="hidden" id="history_table_id" value="3"> 
<input type="hidden" id="subject" value="porder"> 
<input type="hidden" id="subject_key" value="{$po->id}"> 
<input type="hidden" id="warehouse_key" value="{$warehouse->id}"> 

<div id="time2_picker" class="time_picker_div">
</div>
<div id="bd">
	<div id="cal1Container" style="position:absolute;left:610px;top:120px;display:none;z-index:3">
	</div>
	{include file='suppliers_navigation.tpl'} 
	<div class="branch">
		<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a>&rarr; <a href="suppliers.php">{t}Suppliers{/t}</a> &rarr; <a href="supplier.php?id={$supplier->id}">{$supplier->get('Supplier Name')}</a> &rarr; {$po->get('Purchase Order Public ID')} (<span id="po_state">{$po->get('State')}</span>)</span> 
	</div>
	<div class="top_page_menu" style="border:none">
		<div class="buttons" style="float:left">
			<span class="main_title no_buttons">{t}Purchase Order{/t} <span class="id">{$po->get('Purchase Order Public ID')}</span></span> 
		</div>
		<div class="buttons small" style="position:relative;top:5px">
			<button class="negative" id="cancel_po"><img src="art/icons/cancel.png"> {t}Cancel{/t}</button> <button id="back_to_in_process"><img id="back_to_in_process_icon" src="art/icons/application_ungo.png"> {t}Back to processing{/t}</button> <button style="{if $po->get('Purchase Order State')!='Submitted'}display:none{/if}" id="confirm"><img src="art/icons/tick.png"> {t}Confirmed by supplier{/t}</button> <button style="display:none;{if $po->get('Purchase Order State')!='Confirmed'}display:none{/if}" id="invoice_po"><img id="invoice_po_icon" src="art/icons/money.png"> {t}Match to Invoice{/t}</button> 
		</div>
		<div style="clear:both">
		</div>
	</div>
	<div id="order_header">
		<div class="content">
			<div class="totals column">
				<table border="0">
					<tbody id="incoterm_data">
						<tr id="incoterm_tr" style="{if $po->get('Purchase Order Incoterm')==''}display:none{/if}">
							<td>{t}Incoterm{/t}:</td>
							<td id="incoterm" class="aright">{$po->get('Purchase Order Incoterm')}</td>
						</tr>
						<tr id="export_port_tr" style="{if $po->get('Purchase Order Port of Export')==''}display:none{/if}">
							<td>{t}Export Port{/t}:</td>
							<td id="export_port" class="aright">{$po->get('Purchase Order Port of Export')}</td>
						</tr>
						<tr id="import_port_tr" style="{if $po->get('Purchase Order Port of Import')==''}display:none{/if}">
							<td>{t}Import Port{/t}:</td>
							<td id="import_port" class="aright">{$po->get('Purchase Order Port of Import')}</td>
						</tr>
					</tbody>
					<tr class="currency_tr">
						<td>{t}Currency{/t}</td>
						<td class=" aright">{$po->get('Purchase Order Currency Code')}</td>
					</tr>
					<tbody id="po_amounts" style="display:none">
						<tr>
							<td>{t}Goods{/t}:</td>
							<td id="goods" class="aright">{$po->get('Items Net Amount')}</td>
						</tr>
						<tr>
							<td>{t}Shipping{/t}:</td>
							<td class="aright" id="shipping">{$po->get('Shipping Net Amount')}</td>
						</tr>
						<tr>
							<td>{t}Tax{/t}:</td>
							<td id="vat" class="aright">{$po->get('Total Tax Amount')}</td>
						</tr>
					</tbody>
					<tr>
						<td>{t}Total{/t}</td>
						<td id="total" class="total aright ">{$po->get('Total Amount')}</td>
					</tr>
					<tr style="{if $corporate_currency==$po->get('Purchase Order Currency Code')}display:none{/if}">
						<td></td>
						<td id="total_corporate_currency" class="total_corporate_currency aright ">{$po->get('Total Amount Corporate Currency')}</td>
					</tr>
				</table>
			</div>
			<div class="dates column">
				<table border="0">
					<tr id="submitted_date_tr" style="{if $po->get('Purchase Order State')!='Submitted'}display:none{/if}">
						<td class="label">{t}Submitted{/t}:</td>
						<td class="aright">{$po->get('Submitted Date')}</td>
					</tr>
					<tr>
						<td colspan="2" class="aright"> {if $po->get('Purchase Order Main Source Type')=='Other'} {t}submitted by{/t} {$po->get('Purchase Order Main Buyer Name')|capitalize:true} {else if $po->get('Purchase Order Main Source Type')=='In Person'} {t}submitted in person by{/t} {$po->get('Purchase Order Main Buyer Name')|capitalize:true} {else} {t}submitted via{/t} {$po->get('Main Source Type')} {t}by{/t} {$po->get('Purchase Order Main Buyer Name')|capitalize:true} {/if} </td>
					</tr>
					<tr id="confirmed_date_tr" style="{if $po->get('Purchase Order State')!='Confirmed'}display:none{/if}">
						<td class="label">{t}Confirmed{/t}:</td>
						<td class="aright" id="confirmed_date">{$po->get('Confirmed Date')}</td>
					</tr>
					<tr id="agreed_date_tr" style="{if $po->get('Purchase Order State')!='Confirmed'}display:none{/if}">
						<td class="label">{t}Agreed Delivery{/t}:</td>
						<td class="aright" id="agreed_date">{$po->get('Agreed Receiving Date')}</td>
					</tr>
					<tr class="last">
						<td class="label"> 
						<div id="estimated_delivery_Container" style="position:absolute;display:none; z-index:2">
						</div>
						<img style="cursor:pointer" id="edit_estimated_delivery" src="art/icons/edit.gif" alt="({t}edit{/t})"> {t}Estimated Delivery{/t}:</td>
						<td class="aright" id="estimated_delivery">{$po->get_formated_estimated_delivery_date()}</td>
					</tr>
				</table>
			</div>
			<div class="supplier column">
				<table>
					<tr class="last">
						<td>{t}Supplier{/t}:</td>
						<td class="aright"><a href="supplier.php?id={$supplier->get('Supplier Key')}">{$supplier->get('Supplier Name')}</a></td>
					</tr>
				</table>
				<table id="sdns_info" border="0" class="related_objects" style="{if $po->get('Purchase Order State')!='Confirmed'}display:none{/if}">
					<tr class="title">
						<td colspan="2"> 
						<div class="buttons small right">
							<button style="{if $po->get('Purchase Order State')!='Confirmed'}display:none{/if}" id="dn_po"><img id="dn_po_icon" src="art/icons/lorry_go.png"> {t}Match to Delivery Note{/t}</button> 
						</div>
						{if $number_dsns==1}{t}Delivery Note{/t}{else}Deliveries{/if}: </td>
					</tr>
					{foreach from=$sdns_data item=sdn} 
					<tr>
						<td> <a href="supplier_dn.php?id={$sdn.key}">{$sdn.number}</a> </td>
						<td class="right" style="text-align:right"> {$sdn.state} </td>
					</tr>
					{/foreach} 
				</table>
				<div style="clear:both">
				</div>
			</div>
			<div style="clear:both">
			</div>
		</div>
		<ul class="tabs" id="chooser_ul" style="clear:both;margin-top:15px">
			<li> <span class="item " id="tandc"> <span> {t}Terms & Condition{/t}</span> </span> </li>
			<li> <span class="item " id="attachments"> <span id="attachments_label"> {t}Attachments{/t} {if $number_attachments!=0} ({$number_attachments}){/if}</span> </span> </li>
			<li> <span class="item " id="notes"> <span> {t}History/Notes{/t}</span></span></li>
		</ul>
		<div id="order_details_panel" style="display:none;clear:both;border-top:1px solid #ccc;padding:10px 10px 10px; 10px;;">
			<div id="block_tandc" class="block_details" style="display:none">
				<table class="terms_and_conditions edit" border="0">
					<tr id="terms_and_conditions_tr" class="first">
						<td class="label">{t}Terms & Conditions{/t}:</td>
						<td class="input"> 
						<div id="terms_and_conditions_formated">
							{$po->get('Purchase Order Terms and Conditions')} 
						</div>
						</td>
						<td></td>
					</tr>
					<tbody id="edit_tc" style="display:none">
						<tr class=" textarea_big_tr">
							<td class="label">{t}Terms & Conditions{/t}:</td>
							<td class="input"> 
							<div>
								<textarea id="terms_and_conditions" changed="0" value="{$po->get('Purchase Order Terms and Conditions')}" ovalue="{$po->get('Purchase Order Terms and Conditions')}">{$po->get('Purchase Order Terms and Conditions')}</textarea> 
								<div id="terms_and_conditions_Container">
								</div>
							</div>
							</td>
							<td id="terms_and_conditions_msg" class="edit_td_alert"></td>
						</tr>
						<tr class="buttons">
							<td colspan="2"> 
							<div class="buttons">
								<button style="margin-right:10px;" id="save_edit_terms_and_conditions" class="positive disabled">{t}Save{/t}</button> <button style="margin-right:10px;" id="reset_edit_terms_and_conditions" class="negative">{t}Cancel{/t}</button> 
							</div>
							</td>
							<td></td>
						</tr>
					</tbody>
				</table>
			</div>
			<div id="block_attachments" class="block_details" style="display:none">
				<div class="buttons small">
					<button id="attach_bis"><img src="art/icons/add.png"> {t}Attachment{/t}</button> 
				</div>
				<div id="attachments_showcase">
					{include file='attachments_showcase_splinter.tpl' attachments=$po->get_attachments_data()} 
				</div>
			</div>
			<div id="block_notes" class="block_notes" style="display:none;margin-top:10px;margin-bottom:20px">
				<span id="table_title" class="clean_table_title" style="margin-right:10px">{t}History/Notes{/t}</span> 
				<div class="buttons small left">
					<button id="note"><img src="art/icons/add.png" alt=""> {t}History Note{/t}</button> <button id="attach"><img src="art/icons/add.png" alt=""> {t}Attachment{/t}</button> 
				</div>
				<div class="elements_chooser">
					<span style="float:right;margin-left:20px;" class=" table_type transaction_type state_details {if $elements_po_history.Changes}selected{/if} label_part_history_Changes" id="elements_po_history_Changes" table_type="elements_Changes">{t}Changes History{/t} (<span id="elements_history_Changes_number">{$elements_po_history_number.Changes}</span>)</span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_po_history.Notes}selected{/if} label_part_history_Notes" id="elements_po_history_notes" table_type="elements_Notes">{t}Staff Notes{/t} (<span id="elements_history_Notes_number">{$elements_po_history_number.Notes}</span>)</span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_po_history.Attachments}selected{/if} label_part_history_Attachments" id="elements_po_history_Attachments" table_type="elements_Attachments">{t}Attachments{/t} (<span id="elements_history_Attachments_number">{$elements_po_history_number.Attachments}</span>)</span> 
				</div>
				<div class="table_top_bar space">
				</div>
				{include file='table_splinter.tpl' table_id=3 filter_name=$filter_name3 filter_value=$filter_value3} 
				<div id="table3" class="data_table_container dtable btable">
				</div>
			</div>
			<img id="hide_order_details" style="cursor:pointer;position:relative;top:5px" src="art/icons/arrow_sans_topleft.png" /> 
			<div style="clear:both">
			</div>
		</div>
		<div style="clear:both">
		</div>
	</div>
	<div id="the_table" class="data_table" style="margin:20px 0px;clear:both">
		<span class="clean_table_title">{t}Supplier products ordered{/t}</span> 
		<div class="table_top_bar space">
		</div>
		{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0} 
		<div id="table0" style="font-size:80%" class="data_table_container dtable btable">
		</div>
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
<div id="cancel_dialog">
	<div class="bd" style="padding-bottom:0px">
		<div id="cancel_dialog_msg">
		</div>
		<table>
			<tr>
				<td style="width:100px">{t}Note{/t}:</td>
				<td style="width:100px"></td>
			</tr>
			<tr>
				<td colspan="2"> <textarea style="width:100%;margin-bottom:10px" id="cancel_note"></textarea> </td>
			</tr>
			<tr>
				<td colspan="2" style="border-top:1px solid #ddd;text-align:center;padding:10px 0 0 0"> 
				<div class="buttons">
					<button style="margin-left:50px" class="state_details" onclick="cancel_order_save()">{t}Cancel Purchase Order{/t}</button> 
				</div>
				</td>
			</tr>
		</table>
	</div>
</div>
<div id="confirm_dialog" style="padding:20px">
	<div class="bd">
		<div id="confirm_dialog_msg">
		</div>
		<table class="edit" style="width:100%">
			<tr>
				<td id="confirm_msg" class="error"></td>
			</tr>
			<tr>
				<td class="label">{t}Agreed delivery date{/t}:</td>
				<td style="width:120px"> 
				<input id="v_calpop_agreed_delivery_date" type="text" class="text" size="11" maxlength="10" name="from" value="{$po->get_estimated_delivery_date()}" />
				<img id="agreed_delivery_date_pop" class="calpop" src="art/icons/calendar_view_month.png" align="absbottom" alt="choose" /> <br />
			</div>
		</tr>
		<tr class="buttons">
			<td colspan="2"> 
			<div class="buttons">
				<div id="waiting_mark_as_confirmed" style="display:none;text-align:right;padding-right:10px">
					<img src="art/loading.gif"> <span>{t}Processing request{/t}</span> 
				</div>
				<button style="margin-left:50px" onclick="mark_as_confirmed_save()">{t}Mark as confirmed by supplier{/t}</button> </td>
				</buttons> 
			</tr>
		</table>
	</div>
</div>
<div id="agreed_delivery_date_Container" style="position:absolute;display:none; z-index:2">
</div>
<div id="dn_dialog" style="padding:20px">
	<div class="bd">
		<table class="edit" style="width:100%">
			<tr class="title">
				<td colspan="2">{t}Supplier Delivery Note{/t}</td>
			</tr>
			<tr>
				<td class="label">{t}Delivery note number{/t}:</td>
				<td class="input"> 
				<input id="dn_number" value=""></td>
			</tr>
			<tr style="display:none">
				<td class="label">{t}Delivery note date{/t}:</td>
				<td class="input"> 
				<input id="v_calpop1" style="text-align:right;" class="text" name="submites_date" type="text" size="10" maxlength="10" value="" />
				<img id="calpop1" style="cursor:pointer" src="art/icons/calendar_view_month.png" align="top" alt="" /> </td>
			</tr>
			<tr>
				<td class="label">{t}Delivery note identical to PO{/t}</td>
				<td> 
 				<input type="hidden" value="Yes" id="input_sdn"> <img id="input_sdn_Yes" style="display:none" class="checkbox" src="art/icons/checkbox_unchecked.png"> <img id="input_sdn_No" class="checkbox"  src="art/icons/checkbox_checked.png"></td>
			</tr>
			<tr>
				<td class="label">{t}Mark as received{/t}</td>
				<td> 
								<input type="hidden" value="No" id="mark_as_received"> <img id="mark_as_received_Yes" class="checkbox"  src="art/icons/checkbox_unchecked.png"> <img id="mark_as_received_No" style="display:none" class="checkbox" src="art/icons/checkbox_checked.png"> </td>

			</tr>
			<tbody id="receiving_fields" style="display:none">
			<tr>
				<input type="hidden" id="received_by" value="{$user->get_staff_key()}" />
				<td class="label"><img class="edit_icon" src="art/icons/edit.gif" alt="{t}Edit{/t}" id="get_receiver" /> {t}Received by{/t}:</td>
				<td><span style="cursor:pointer"  id="received_by_alias">{$user->get_staff_name()}</span></td>
			</tr>
			<tr>
				<input type="hidden" id="location_key" value="{$default_loading_location_key}" />
				<td class="label"><img class="edit_icon" src="art/icons/edit.gif" alt="{t}Edit{/t}" id="get_location" /> {t}Receiving location{/t}:</td>
				<td> <span style="cursor:pointer"  id="location_code">{$default_loading_location_code}</span> </td>
			</tr>
			</tbody>
			<tr class="buttons">
				<td class="label "> <span class="error" id="dn_dialog_msg"> </span> </td>
				<td> 
				<div class="buttons left">
					<div id="wait_match_to_dn" style="display:none;text-align:right;padding-right:10px">
						<img src="art/loading.gif"> <span>{t}Processing request{/t}</span> 
					</div>
					<button id="match_to_dn_save" onclick="match_to_dn_save()">{t}Match to Delivery Note{/t}</button> </td>
					</buttons> 
				</div>
				</td>
			</tr>
		</table>
	</div>
</div>
<div id="staff_dialog">
	<input type="hidden" id="staff_list_parent_dialog" value=""> 
	<div class="splinter_cell" style="padding:10px 15px 10px 0;border:none">
		<div class="buttons small left" style="margin:15px 0px">
			{foreach from=$operators item=operator} <button class="quick_choose_button" staff_key="{$operator.Key}" onclick="select_staff_from_button(this)">{$operator.Name}</button> {/foreach} <button class="quick_choose_button" staff_key="0" onclick="select_staff_from_button(this)">{t}Unknown/Other{/t}</button> 
		</div>
		<div style="clear:both;margin-top:0px;height:5px">
		</div>
		<div id="the_table" class="data_table" style="clear:both;margin-top:10px">
			<span class="clean_table_title">{t}Staff{/t}</span> {include file='table_splinter.tpl' table_id=2 filter_name='code' filter_value=''} 
			<div id="table2" class="data_table_container dtable btable">
			</div>
		</div>
	</div>
</div>
<div id="location_dialog">
	<input type="hidden" id="location_list_parent_dialog" value=""> 
	<div class="splinter_cell" style="padding:10px 15px 10px 0;border:none">
		<div class="buttons small left" style="margin:15px 0px">
			{foreach from=$loading_locations item=location} <button class="quick_choose_button" location_key="{$location.Key}" onclick="select_location_from_button(this)">{$location.Code}</button> {/foreach} 
		</div>
		<div style="clear:both;margin-top:0px;height:5px">
		</div>
		<div id="the_table" class="data_table" style="clear:both;margin-top:10px">
			<span class="clean_table_title">{t}Locations{/t}</span> {include file='table_splinter.tpl' table_id=1 filter_name='code' filter_value=''} 
			<div id="table1" class="data_table_container dtable btable">
			</div>
		</div>
	</div>
</div>
{include file='notes_splinter.tpl'} {include file='porder_common_splinter.tpl'} {include file='footer.tpl'} 