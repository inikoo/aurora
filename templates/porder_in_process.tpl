{include file='header.tpl'} 
<input type="hidden" value="{$session_data}" id="session_data" />
<input type="hidden" value="{$products_display_type}" id="products_display_type"> 
<input type="hidden" value="{$po->id}" id="po_key"> 
<input type="hidden" value="{$supplier->id}" id="supplier_key"> 
<input type="hidden" value="{$number_buyers}" id="number_buyers">
<input type="hidden" id="history_table_id" value="3"> 
<input type="hidden" id="subject" value="porder"> 
<input type="hidden" id="subject_key" value="{$po->id}"> 
<div id="time2_picker" class="time_picker_div">
</div>
<div id="bd">
	{include file='suppliers_navigation.tpl'} 
	<div id="cal1Container" style="position:absolute;left:610px;top:120px;display:none;z-index:3">
	</div>
	<div class="branch ">
		<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a>&rarr; <a href="suppliers.php">{t}Suppliers{/t}</a> &rarr; <a href="supplier.php?id={$supplier->id}">{$supplier->get('Supplier Name')}</a> &rarr; {$po->get('Purchase Order Public ID')} ({$po->get('Purchase Order Current Dispatch State')})</span> 
	</div>
	<div class="top_page_menu" style="border:none">
		<div class="buttons" style="float:left">
			<span class="main_title no_buttons">{t}Purchase Order{/t} <span class="id">{$po->get('Purchase Order Public ID')}</span> <span class="subtitle">({t}In process{/t})</span></span> 
		</div>
		<div class="buttons small" style="position:relative;top:5px">
			<button style="height:18px;display:none" onclick="window.open('porder.pdf.php?id={$po->id}')"><img style="width:40px;height:12px;position:relative;top:-1px" src="art/pdf.gif" alt=""></button> <button class="positive {if $po->get('Purchase Order Number Items')==0}disabled{/if}" id="submit_po">{t}Submit{/t}</button> <button class="negative" id="delete_po">{t}Delete{/t}</button> 
		</div>
		<div style="clear:both">
		</div>
	</div>
	<div style="clear:both">
	</div>
	<div id="order_header">
	    <div class="content">
		<table style="width:200px;" class="order_header" border="0">
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
		<div style="border:0px solid red;width:290px;float:right">
			<table border="0" class="order_header" style="margin-right:30px;float:right">
				<tr>
					<td class="aright" style="padding-right:40px">{t}Created{/t}:</td>
					<td>{$po->get('Creation Date')}</td>
				</tr>
			</table>
		</div>
		<table border="0">
			<tr>
				<td>{t}Supplier{/t}:</td>
				<td class="aright"><a href="supplier.php?id={$supplier->get('Supplier Key')}">{$supplier->get('Supplier Name')}</a></td>
				<td></td>
			</tr>
			<tbody id="incoterm_data">
				<tr id="incoterm_tr">
					<td>{t}Incoterm{/t}:</td>
					<td id="incoterm" class="aright">{$po->get('Purchase Order Incoterm')}</td>
					<td class="aright" style="width:20px"><img id="edit_incoterm" onclick="show_edit_incoterm_dialog()" style="display:none;cursor:pointer;height:15px" src="art/icons/edit.gif"></td>
				</tr>
				<tr id="export_port_tr">
					<td>{t}Export Port{/t}:</td>
					<td id="export_port" class="aright">{$po->get('Purchase Order Port of Export')}</td>
					<td></td>
				</tr>
				<tr id="import_port_tr">
					<td>{t}Import Port{/t}:</td>
					<td id="import_port" class="aright">{$po->get('Purchase Order Port of Import')}</td>
					<td></td>
				</tr>
			</tbody>
			<tr style="display:none">
				<td>{t}Items{/t}:</td>
				<td class="aright" id="distinct_products">{$po->get('Number Items')}</td>
			</tr>
		</table>
		<table style="clear:both;border:none;display:none" class="notes">
			<tr>
				<td style="border:none">{t}Notes{/t}:</td>
				<td style="border:none"><textarea id="v_note" rows="2" cols="60"></textarea></td>
			</tr>
		</table>
		<div style="clear:both">
		</div>
		</div>
		
		
	
		<ul class="tabs" id="chooser_ul" style="clear:both;margin-top:15px">
		<li> 
		    <span class="item "  id="tandc"> 
		        <span> {t}Terms & Condition{/t}</span>
		    </span>
		</li>
		<li> <span class="item "  id="attachments" > 
		    <span id="attachments_label"> {t}Attachments{/t} {if $number_attachments!=0} ({$number_attachments}){/if}</span>
		   
		    </span>
		</li>
		<li> <span class="item "  id="notes" > <span> {t}History/Notes{/t}</span></span></li>

		</ul>
		
		
		<div  id="order_details_panel" style="display:none;clear:both;border-top:1px solid #ccc;padding:10px 10px 10px; 10px;;">
			
			<div id="block_tandc" class="block_details" style="display:none" >
			<table class="terms_and_conditions edit" border="0">
				
				<tr id="terms_and_conditions_tr" class="first">
					<td class="label">{t}Terms & Conditions{/t}:</td>
					<td class="input"> 
					<div id="terms_and_conditions_formated">
						{$po->get('Purchase Order Terms and Conditions')} 
					</div>
					</td>
					<td><img onclick="show_edit_tc()" style="cursor:pointer" src="art/icons/edit.gif"></td>
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
			<div id="block_attachments"  class="block_details" style="display:none">
			<div class="buttons small"><button id="attach_bis"><img src="art/icons/add.png"> {t}Attachment{/t}</button></div>
			<div id="attachments_showcase">
			{include file='attachments_showcase_splinter.tpl' attachments=$po->get_attachments_data()}
			</div>
			
			</div>
            <div id="block_notes"  class="block_notes" style="display:none;margin-top:10px;margin-bottom:20px">
            
				<span id="table_title" class="clean_table_title" style="margin-right:10px">{t}History/Notes{/t}</span> 
				
				<div class="buttons small left">
				    <button id="note"><img src="art/icons/add.png" alt=""> {t}History Note{/t}</button> 
				<button id="attach"><img src="art/icons/add.png" alt=""> {t}Attachment{/t}</button> 
				</div>
				
				<div class="elements_chooser">
					<span style="float:right;margin-left:20px;" class=" table_type transaction_type state_details {if $elements_po_history.Changes}selected{/if} label_part_history_Changes" id="elements_po_history_Changes" table_type="elements_Changes">{t}Changes History{/t} (<span id="elements_history_Changes_number">{$elements_po_history_number.Changes}</span>)</span> 
					<span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_po_history.Notes}selected{/if} label_part_history_Notes" id="elements_po_history_notes" table_type="elements_Notes">{t}Staff Notes{/t} (<span id="elements_history_Notes_number">{$elements_po_history_number.Notes}</span>)</span> 
					<span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_po_history.Attachments}selected{/if} label_part_history_Attachments" id="elements_po_history_Attachments" table_type="elements_Attachments">{t}Attachments{/t} (<span id="elements_history_Attachments_number">{$elements_po_history_number.Attachments}</span>)</span> 
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
		<span class="clean_table_title">{t}Supplier products to order{/t}</span> 
		<div class="elements_chooser">
			<span style="float:right;margin-left:20px;" class=" table_type transaction_type state_details {if $products_display_type=='all_products'}selected{/if} label_all_products" id="all_products">{t}Supplier Products{/t} (<span id="all_products_number">{$supplier->get_formated_number_products_to_buy()}</span>)</span> <span style="float:right;margin-left:20px;" class=" table_type transaction_type state_details {if $products_display_type=='ordered_products'}selected{/if} label_ordered_products" id="ordered_products">{t}Ordered Products{/t} (<span id="ordered_products_number">{$po->get('Number Items')}</span>)</span> 
		</div>
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
<div id="delete_dialog" class="nicebox">
	<div class="bd">
		<div id="delete_dialog_msg" class="dialog_msg" style="padding:0 0 10px 0 ">
			{t}Note: this action can not be undone{/t}. 
		</div>
		<table style="width:250px">
			<tr>
				<td style="border-top:1px solid #ddd;text-align:center;padding:10px 0 0 0"> 
				<div class="buttons">
					<button class="negative" onclick="delete_order()">{t}Delete Purchase Order{/t}</button> 
				</div>
				</td>
			</tr>
		</table>
	</div>
</div>
<div id="submit_dialog" style="padding-top:20px">
	<div class="bd">
		<div id="submit_dialog_msg">
		</div>
		<table class="edit" style="width:400px" border="0">
			<tr>
				<td class="label">{t}Preview PDF{/t}</td>
				<td> 
				<div class="buttons small left" style="position:relative;top:-2px">
					<button style="height:18px" onclick="window.open('porder.pdf.php?id={$po->id}')"><img style="width:40px;height:12px;position:relative;top:-1px" src="art/pdf.gif" alt=""></button> 
				</div>
				</td>
			</tr>
			<tr class="first title">
				<td colspan="2">{t}Submit purchse order{/t}</td>
			</tr>
			<tr>
				<td class="label">{t}Submit Method{/t}:</td>
				<td> 
				<div class="buttons small" style="margin:0px 0;width:250px" id="submit_method_container">
					<input type="hidden" value="{$default_submit_method}" ovalue="{$default_submit_method}" id="submit_method"> {foreach from=$submit_method item=unit_tipo key=name} <button style="float:left;margin-bottom:5px;margin-right:5px" onclick="change_submit_method(this)" class="radio{if $default_submit_method==$name} selected{/if}" id="radio_shelf_type_{$name}" radio_value="{$name}">{$unit_tipo.fname}</button> {/foreach} 
				</div>
				</td>
			</tr>
			<input type="hidden" id="date_type" value="now" />
			<tr style="display:none" id="tr_manual_submit_date">
				<td class="label">{t}Submit Date{/t}:</td>
				<td> 
				<input id="v_calpop1" style="text-align:right;" class="text" name="submites_date" type="text" size="10" maxlength="10" value="{$date}" />
				<img id="calpop1" style="cursor:pointer" src="art/icons/calendar_view_month.png" align="top" alt="" /> </td>
			</tr>
			<tr>
				<input type="hidden" id="submitted_by" value="{$user_staff_key}" />
				<td class="label"><img style="cursor:pointer" class="edit_mini_button" id="get_submiter" src="art/icons/edit.gif" alt="({t}edit{/t})" /> {t}Submit By{/t}:</td>
				<td><span id="submited_by_alias" class="value">{$user_alias}</span></td>
			</tr>
			<tr class="buttons">
				<td colspan="2"> 
				<div class="buttons" style="margin-right:20px;text-align:right">
					<span style="display:none" id="submit_order_wait"><img src="art/loading.gif" /> {t}Processing Request{/t}</span> <button id="submit_order_button" class="positive" onclick="submit_order_save(this)">{t}Submit{/t}</button> <button style="display:none" onclick="cancel_order_save(this)">{t}Cancel{/t}</button> 
				</div>
				</td>
			</tr>
		</table>
	</div>
</div>
<div id="staff_dialog" style="width:400px;padding:20px 10px 20px 10px;xdisplay:none">
	<input type="hidden" id="staff_dialog_type" value="assign_buyer"> 
	<table class="edit" border="0" style="width:100%">
		<input type="hidden" id="assign_buyer_staff_key"> 
		<input type="hidden" id="assign_buyer_dn_key"> 
		<tr class="title">
			<td colspan="2"> {t}Submitter{/t} </td>
		</tr>
		<tr>
			<td colspan="2"> 
			<div class="options" style="width:350px;padding:0 10px;text-align:center">
				<table border="0" style="margin:auto" id="assign_buyer_buttons">
					{if $number_buyers==0} 
					<tr>
						<td onclick="show_other_staff(this)" id="buyer_show_other_staff" td_id="other_staff_pack_it" class="assign_buyer_button other" onclick="show_other_staff(this)">{t}Employees list{/t}</td>
					</tr>
					{else} {foreach from=$buyers item=buyer_row name=foo} 
					<tr>
						{foreach from=$buyer_row key=row_key item=buyer } 
						<td staff_id="{$buyer.StaffKey}" id="buyer{$buyer.StaffKey}" scope="buyer" class="assign_buyer_button" onclick="select_staff(this,event)">{$buyer.StaffAlias}</td>
						{/foreach} 
						<td onclick="show_other_staff(this)" id="buyer_show_other_staff" td_id="other_staff_buyer" class="assign_buyer_button other" onclick="show_other_staff(this)">{t}Other{/t}</td>
					</tr>
					{/foreach} {/if} 
				</table>
			</div>
			</td>
		</tr>
	</table>
</div>
<div id="dialog_other_staff">
	<input type="hidden" id="staff_list_parent_dialog" value=""> 
	<div class="splinter_cell" style="padding:10px 15px 10px 0;border:none">
		<div class="buttons small left" style="magin-bottom:15px">
			<button style="margin:0;padding:0;" label="{t}Unknown/Other{/t}" onclick="select_unknown_staff(this)">{t}Unknown/Other{/t}</button> 
		</div>
		<div style="clear:both;margin-top:0px;height:5px">
		</div>
		<div id="the_table" class="data_table" style="clear:both;margin-top:10px">
			<span class="clean_table_title">{t}Staff List{/t}</span> {include file='table_splinter.tpl' table_id=2 filter_name=$filter_name2 filter_value=$filter_value2} 
			<div id="table2" class="data_table_container dtable btable">
			</div>
		</div>
	</div>
</div>
<div id="edit_incoterm_dialog" style="padding-top:20px;width:500px">
	<div class="bd">
		<table id="po_settings" class="edit" border="0">
			<tr class="first">
				<td class="label">{t}Incoterm{/t}:</td>
				<td style="text-align:left"> 
				<input type="hidden" id="Purchase_Order_Incoterm" value="{$po->get('Purchase Order Incoterm')}" ovalue="{$po->get('Purchase Order Incoterm')}" ovalue_formated="{$po->get('Purchase Order Incoterm')}" />
				<div class="buttons small left">
					<span style="float:left;margin-right:10px" id="Purchase_Order_Incoterm_formated">{$po->get('Purchase Order Incoterm')}</span> <button class="negative" style="{if $po->get('Purchase Order Incoterm')==''}display:none{/if}" id="delete_Purchase_Order_Incoterm" onclick="delete_incoterm()">{t}Remove{/t}</button> <button style="{if $po->get('Purchase Order Incoterm')==''}display:none{/if}" id="update_Purchase_Order_Incoterm">{t}Change Incoterm{/t}</button> <button style="{if $po->get('Purchase Order Incoterm')!=''}display:none{/if}" id="set_Purchase_Order_Incoterm">{t}Set Incoterm{/t}</button> 
				</div>
				</td>
				<td class="message"><span id="Purchase_Order_Incoterm_msg" class="edit_td_alert"></span></td>
			</tr>
			<tr class="space10">
				<td class="label">Port of export:</td>
				<td class="input"> 
				<div>
					<input id="Purchase_Order_Port_of_Export" value="{$po->get('Purchase Order Port of Export')}" ovalue="{$po->get('Purchase Order Port of Export')}" valid="0"> 
					<div id="Purchase_Order_Port_of_Export_Container">
					</div>
				</div>
				</td>
				<td class="message"><span id="Purchase_Order_Port_of_Export_msg" class="edit_td_alert"></span></td>
			</tr>
			<tr>
				<td class="label">Port of import:</td>
				<td class="input"> 
				<div>
					<input id="Purchase_Order_Port_of_Import" value="{$po->get('Purchase Order Port of Import')}" ovalue="{$po->get('Purchase Order Port of Import')}" valid="0"> 
					<div id="Purchase_Order_Port_of_Import_Container">
					</div>
				</div>
				</td>
				<td class="message"><span id="Purchase_Order_Port_of_Import_msg" class="edit_td_alert"></span></td>
			</tr>
			<tr class="buttons">
				<td colspan="2"> 
				<div class="buttons">
					<button style="margin-right:10px;" id="save_edit_incoterm" class="positive disabled">{t}Save{/t}</button> <button style="margin-right:10px;" id="reset_edit_incoterm" class="negative disabled">{t}Reset{/t}</button> 
				</div>
				</td>
				<td></td>
			</tr>
		</table>
	</div>
</div>
<div id="dialog_incoterm_list">
	<div class="splinter_cell" style="padding:10px 15px 10px 0;border:none">
		<div class="data_table">
			<span class="clean_table_title">{t}Incoterms{/t}</span> {include file='table_splinter.tpl' table_id=6 filter_name=$filter_name6 filter_value=$filter_value6} 
			<div id="table6" class="data_table_container dtable btable">
			</div>
		</div>
	</div>
</div>
<div id="dialog_sticky_note_for_supplier" style="padding:20px 20px 0px 20px;width:340px">
	<input type="hidden" id="sticky_note_for_supplier_potfk" value=""> 
	<div id="sticky_note_for_supplier_msg">
	</div>
	<table>
		<tr>
			<td> <textarea style="width:330px;height:125px" id="sticky_note_for_supplier_input"></textarea> </td>
		</tr>
		<tr>
			<td> 
			<div class="buttons">
				<button class="positive" onclick="save_sticky_note_for_supplier()">{t}Save{/t}</button> <button class="negative" onclick="close_dialog_sticky_note_for_supplier()">{t}Cancel{/t}</button> 
			</div>
			</td>
		</tr>
	</table>
</div>
{include file='notes_splinter.tpl'}

{include file='footer.tpl'} 