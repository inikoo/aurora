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
		<button class="negative" id="cancel_po"><img src="art/icons/cancel.png"> {t}Cancel{/t}</button> 
		
		<button id="back_to_in_process"><img id="back_to_in_process_icon" src="art/icons/application_ungo.png"> {t}Back to processing{/t}</button>
		<button style="{if $po->get('Purchase Order State')!='Submitted'}display:none{/if}" id="confirm"><img src="art/icons/tick.png"> {t}Confirmed by supplier{/t}</button>
		<button style="{if $po->get('Purchase Order State')!='Confirmed'}display:none{/if}" id="invoice_po"><img id="invoice_po_icon" src="art/icons/money.png"> {t}Match to Invoice{/t}</button>
		<button style="{if $po->get('Purchase Order State')!='Confirmed'}display:none{/if}" id="dn_po"><img id="dn_po_icon" src="art/icons/lorry_go.png"> {t}Match to Delivery Note{/t}</button> 



	</div>
	<div style="clear:both"></div>
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
			<table border="0" class="order_header" style="width:400px;margin-right:30px;float:right">
				
				<tr id="submitted_date_tr"style="{if $po->get('Purchase Order State')!='Submitted'}display:none{/if}">
					<td class="aright" style="padding-right:40px">{t}Submitted{/t}:</td>
					<td>{$po->get('Submitted Date')}</td>
				</tr>
				
					<tr>
					<td colspan="2" class="aright">
					{if $po->get('Purchase Order Main Source Type')=='Other'}
						{t}submitted by{/t} {$po->get('Purchase Order Main Buyer Name')|capitalize:true}
                      {else if $po->get('Purchase Order Main Source Type')=='In Person'}
                      	{t}submitted in person by{/t} {$po->get('Purchase Order Main Buyer Name')|capitalize:true}
 
                        {else}
					{t}submitted via{/t} {$po->get('Main Source Type')} {t}by{/t} {$po->get('Purchase Order Main Buyer Name')|capitalize:true}
					{/if}
					</td>
				</tr>
				<tr id="confirmed_date_tr" style="{if $po->get('Purchase Order State')!='Confirmed'}display:none{/if}">
					<td class="aright" style="padding-right:40px">{t}Confirmed{/t}:</td>
					<td id="confirmed_date">{$po->get('Confirmed Date')}</td>
				</tr>
				<tr id="agreed_date_tr"  style="{if $po->get('Purchase Order State')!='Confirmed'}display:none{/if}">
					<td class="aright" style="padding-right:40px">{t}Agreed Delivery{/t}:</td>
					<td id="agreed_date">{$po->get('Agreed Receiving Date')}</td>
				</tr>
				<tr>
					<td class="aright" style="padding-right:40px"> 
					<div id="estimated_delivery_Container" style="position:absolute;display:none; z-index:2">
					</div>
					<img style="cursor:pointer" id="edit_estimated_delivery" src="art/icons/edit.gif" alt="({t}edit{/t})"> {t}Estimated Delivery{/t}:</td>
					<td  id="estimated_delivery">{$po->get_formated_estimated_delivery_date()}</td>
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
	
	
	
	<div class="prodinfo" style="display:none;margin-top:2px;font-size:85%;border:1px solid #ddd;padding:10px;">
		<table style="width:200px;" class="order_header">
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
			<tr>
				<td>{t}Total{/t}</td>
				<td id="total" class="stock aright ">{$po->get('Total Amount')}</td>
			</tr>
		</table>
		<div style="border:0px solid red;xwidth:290px;float:right">
			<table border="0" class="order_header" style="margin-right:30px;float:right">
				<tr>
					<td class="aright" style="padding-right:40px">{t}Created{/t}:</td>
					<td>{$po->get('Creation Date')}</td>
				</tr>
				<tr>
					<td class="aright" style="padding-right:40px">{t}Submitted{/t}:</td>
					<td>{$po->get('Submitted Date')}</td>
				</tr>
				<tr>
					<td colspan="2" class="aright">{t}via{/t} {$po->get('Purchase Order Main Source Type')} {t}by{/t} {$po->get('Purchase Order Main Buyer Name')}</td>
				</tr>
				<tr>
					<td class="aright" style="padding-right:40px"> 
					<div id="estimated_delivery_Container" style="position:absolute;display:none; z-index:2">
					</div>
					<img style="cursor:pointer" id="edit_estimated_delivery" src="art/icons/edit.gif" alt="({t}edit{/t})"> {t}Estimated Delivery{/t}:</td>
					<td class="aright" id="estimated_delivery">{if $po->get('Purchase Order Estimated Receiving Date')==''}{t}Unknown{/t}{else}{$po->get('Estimated Receiving Date')}{/if}</td>
				</tr>
			</table>
		</div>
		
		<table border="0">
			<tr>
				<td>{t}Purchase Order Id{/t}:</td>
				<td class="aright">{$po->get('Purchase Order Key')}</td>
			</tr>
			<tr>
				<td>{t}Supplier{/t}:</td>
				<td class="aright"><a href="supplier.php?id={$supplier->get('Supplier Key')}">{$supplier->get('Supplier Name')}</a></td>
			</tr>
			<tr>
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
<div id="cancel_dialog" >
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
					<tr><td id="confirm_msg" class="error"></td></tr>

			<tr>
				<td class="label">{t}Agreed delivery date{/t}:</td>
				<td style="width:120px">
				
				
				
				<input id="v_calpop_agreed_delivery_date" type="text" class="text" size="11" maxlength="10" name="from" value="{$po->get_estimated_delivery_date()}" />
				<img id="agreed_delivery_date_pop" class="calpop" src="art/icons/calendar_view_month.png" align="absbottom" alt="choose" /> <br />
                </div>
				
				
				
			</tr>
			<tr class="buttons">
				<td colspan="2" > 
				<div class="buttons">
				<div id="waiting_mark_as_confirmed" style="display:none;text-align:right;padding-right:10px"><img src="art/loading.gif"> <span>{t}Processing request{/t}</span></div>

				<button style="margin-left:50px" onclick="mark_as_confirmed_save()">{t}Mark as confirmed by supplier{/t}</button> 
				</td>
				</buttons>
			</tr>
		</table>
	</div>
</div>
<div id="agreed_delivery_date_Container" style="position:absolute;display:none; z-index:2">
				</div>

<div id="dn_dialog" style="padding:20px">
	<div class="bd">
		<div id="dn_dialog_msg">
		</div>
		<table>
			<tr>
				<td class="label">{t}Supplier Delivery Note Number{/t}:</td>
				<td style="width:100px">
				<input id="dn_number" value=""></td>
			</tr>
			<tr>
				<td class="label">{t}Supplier Delivery Note Date{/t}:</td>
				<td style="width:120px">
				<input id="v_calpop1" style="text-align:right;" class="text" name="submites_date" type="text" size="10" maxlength="10" value="" />
				<img id="calpop1" style="cursor:pointer" src="art/icons/calendar_view_month.png" align="top" alt="" /> 
				
				
				
			</tr>
			<tr class="space10">
				<td colspan="2" > 
				<div class="buttons">
				<button style="margin-left:50px" onclick="dn_order_save()">{t}Match to Delivery Note{/t}</button> 
				</td>
				</buttons>
			</tr>
		</table>
	</div>
</div>
{include file='notes_splinter.tpl'}
{include file='porder_common_splinter.tpl'}
{include file='footer.tpl'} 