<ul class="tabs" id="chooser_ul" style="clear:both;margin-top:15px">
	<li> <span class="item " id="details"> <span> {t}Details/Operations{/t}</span></span></li>
	<li> <span class="item " id="notes"> <span> {t}History/Notes{/t}</span></span></li>
	<li> <span class="item " id="customer_data"> <span> {t}Customer Data{/t}</span></span></li>
</ul>
<div id="order_details_panel" style="display:none">
	<div id="block_details" class="details_block" style="display:none;">
		<div class="order_data" >
			<table border="0" class="info_block" style="display:none">
				
				<tr>
					<td>{t}Created{/t}:</td>
					<td class="aright">{$order->get('Created Date')}</td>
					<td></td>
				</tr>
				<tr style="{if $order->get('Order Submitted by Customer Date')==''}display:none{/if}">
					<td>{t}Submitted{/t}:</td>
					<td class="aright">{$order->get('Submitted by Customer Date')}</td>
					<td class="aright">{$order->get('Submitted by Customer Interval')}</td>
				</tr>
				<tr style="{if $order->get('Order Send to Warehouse Date')==''}display:none{/if}">
					<td>{t}Send to Warehouse{/t}:</td>
					<td class="aright">{$order->get('Send to Warehouse Date')}</td>
					<td class="aright">{$order->get('Send to Warehouse Interval')}</td>
				</tr>
				<tr style="{if $order->get('Order Packed Done Date')==''}display:none{/if}">
					<td>{t}Packed{/t}:</td>
					<td class="aright">{$order->get('Packed Done Date')}</td>
					<td class="aright">{$order->get('Packed Done Interval')}</td>
				</tr>
				<tr style="{if $order->get('Order Dispatched Date')==''}display:none{/if}">
					<td>{t}Dispatched{/t}:</td>
					<td class="aright">{$order->get('Dispatched Date')}</td>
					<td class="aright">{$order->get('Dispatched Interval')}</td>
				</tr>
				<tr style="{if $order->get('Order Suspended Date')==''}display:none{/if}">
					<td>{t}Suspended{/t}:</td>
					<td colspan="2" class="aright">{$order->get('Order Suspended Date')}</td>
				</tr>
				<tr style="{if $order->get('Order Cancelled Date')==''}display:none{/if}">
					<td>{t}Cancelled{/t}:</td>
					<td colspan="2" class="aright">{$order->get('Order Cancelled Date')}</td>
				</tr>
				<tr style="{if $order->get('Order Post Transactions Dispatched Date')==''}display:none{/if}">
					<td>{t}Replacements Dispatched{/t}:</td>
					<td colspan="2" class="aright">{$order->get('Post Transactions Dispatched Date')}</td>
				</tr>
			</table>
			<table border="0" class="info_block">
				<tr>
					<td id="order_customer_fiscal_name_label">{t}Customer Fiscal Name{/t}:</td>
					<td id="order_customer_fiscal_name" class="aright">{$order->get('Order Customer Fiscal Name')}</td>
					<td class="aright"><img id="update_customer_fiscal_name" style="cursor:pointer" src="art/icons/edit.gif"></td>
				</tr>
				<tr>
					<td>{t}Tax Number{/t}:</td>
					<td class="aright" id="update_order_tax_number_value">{$order->get('Order Tax Number')}</td>
					<td class="aright"><img id="update_order_tax_number" onclick="show_set_tax_number_dialog_from_details()" style="cursor:pointer" src="art/icons/edit.gif"></td>
				</tr>
				<tr  id="order_customer_name_label">
					<td>{t}Customer Name{/t}:</td>
					<td class="aright" id="order_customer_name" >{$order->get('Order Customer Name')}</td>
					<td class="aright"><img id="update_customer_name" style="cursor:pointer" src="art/icons/edit.gif"></td>
				</tr>
				<tr id="order_customer_contact_name_label">
					<td>{t}Contact Name{/t}:</td>
					<td class="aright" id="order_customer_contact_name">{$order->get('Order Customer Contact Name')}</td>
					<td class="aright"><img id="update_customer_contact_name" style="cursor:pointer" src="art/icons/edit.gif"></td>
				</tr>
				<tr id="order_customer_telephone_label">
					<td>{t}Telephone{/t}:</td>
					<td class="aright" id="order_customer_telephone">{$order->get('Order Telephone')}</td>
					<td class="aright"><img id="update_customer_telephone" style="cursor:pointer" src="art/icons/edit.gif"></td>
				</tr>
				<tr id="order_customer_email_label">
					<td>{t}Email{/t}:</td>
					<td class="aright" id="order_customer_enail">{$order->get('Order Email')}</td>
					<td class="aright"><img id="update_customer_email" style="cursor:pointer" src="art/icons/edit.gif"></td>
				</tr>
			</table>
			<table border="0" class="info_block">
				<tr>
					<td>{t}Tax Code{/t}:</td>
					<td class="aright">{$order->get('Order Tax Code')} {$order->get('Tax Rate')} </td>
				</tr>
				<tr>
					<td>{t}Tax Info{/t}:</td>
					<td class="aright">{$order->get('Order Tax Name')}</td>
				</tr>
			</table>
			<table border="0" class="info_block">
				<tr>
					<td>{t}Weight {/t}:</td>
					<td class="aright">{$order->get('Weight')}</td>
				</tr>
			</table>
		</div>
		
		<div class="order_operations" >
		
		<div id="operations_msg" style="display:none"></div>
		
		    <div class="buttons small right">


		    {if $order->get('Order Current Dispatch State')=='In Process by Customer'  }
		    		    <button id="cancel" class="negative">{t}Cancel Order{/t}</button> 

{else if  $order->get('Order Current Dispatch State')=='Waiting for Payment Confirmation' }
		    
{else if  $order->get('Order Current Dispatch State')=='In Process'   }		    
			<button id="send_to_basket"><img id="send_to_basket_img" src="art/icons/basket_back.png" alt=""> {t}Send to basket{/t}</button> 
					<button style="{if $order->get('Order Apply Auto Customer Account Payment')=='Yes'}display:none{/if}" onclick="update_auto_account_payments('Yes')">{t}Add account credits{/t}</button>
				     <button  id="cancel" class="negative">{t}Cancel order{/t}</button> 
					<button style="{if $order->get('Order Apply Auto Customer Account Payment')=='No'}display:none{/if}" onclick="update_auto_account_payments('No')">{t}Don't add account credits{/t}</button> 
	    
{else if    $order->get('Order Current Dispatch State')=='Submitted by Customer' }
					<button style="{if $order->get('Order Apply Auto Customer Account Payment')=='Yes'}display:none{/if}" onclick="update_auto_account_payments('Yes')">{t}Add account credits{/t}</button>
				     <button  id="cancel" class="negative">{t}Cancel order{/t}</button> 
					<button style="{if $order->get('Order Apply Auto Customer Account Payment')=='No'}display:none{/if}" onclick="update_auto_account_payments('No')">{t}Don't add account credits{/t}</button> 

{else if  $order->get('Order Current Dispatch State')=='In Warehouse'  }

{else if  $order->get('Order Current Dispatch State')=='Dispatched' }

				<button style="margin-bottom:10px;clear:both;{if {$order->get('Order Number Products')}==0    or $order->get('Order Current Dispatch State')!='In Process'}display:none{/if} " id="send_to_basket"><img id="send_to_warehouse_img" src="art/icons/basket_back.png" alt=""> {t}Send to basket{/t}</button> <button style="margin-bottom:10px;clear:both;{if $order->get('Order Apply Auto Customer Account Payment')=='No'}display:none{/if}" onclick="update_auto_account_payments('No')">{t}Don't add account credits{/t}</button> <button style="{if $order->get('Order Apply Auto Customer Account Payment')=='Yes'}display:none{/if}" onclick="update_auto_account_payments('Yes')">{t}Add account credits{/t}</button> <button style="margin-top:5px;margin-bottom:10px;clear:both" id="cancel" class="negative">{t}Cancel order{/t}</button> 


{else if  $order->get('Order Current Dispatch State')=='Cancelled' or $order->get('Order Current Dispatch State')=='Cancelled by Customer'}

				<button style="margin-top:5px;margin-bottom:10px;clear:both" id="undo_cancel" onclick="undo_cancel()"><img id="undo_cancel_img" style="width:12px" src="art/icons/arrow_rotate_anticlockwise.png"> {t}Undo Cancel{/t}</button> 
{else if  $order->get('Order Current Dispatch State')=='Suspened' }

				<button style="margin-top:5px;margin-bottom:10px;clear:both" id="undo_cancel" onclick="undo_cancel()"><img id="undo_cancel_img" style="width:12px" src="art/icons/arrow_rotate_anticlockwise.png"> {t}Undo Cancel{/t}</button> 
{else}
<button style="{if $order->get('Order Apply Auto Customer Account Payment')=='No'}display:none{/if}" onclick="update_auto_account_payments('No')">{t}Don't add account credits{/t}</button> 
				<button style="{if $order->get('Order Apply Auto Customer Account Payment')=='Yes'}display:none{/if}" onclick="update_auto_account_payments('Yes')">{t}Add account credits{/t}</button>
				 <button style="{if $order->get('Order Invoiced')=='Yes'}display:none{/if}" id="cancel" class="negative">{t}Cancel Order{/t}</button> 


{/if}
		    
		   				<button style=""  id="recalculate_totals"><img id="recalculate_totals_img" src="art/icons/arrow_rotate_clockwise.png" alt=""> {t}Recalculate Totals{/t}</button> 
 
				
			</div>
		</div>
		<div style="clear:both"></div>
		
		<div id="dialog_quick_edit_Order_Customer_Fiscal_Name" style="padding:10px">
			<table style="margin:10px" border=0>
				<tr>
					<td>{t}Customer Fiscal Name{/t}:</td>
					<td> 
					<div>
						<input style="width:300px" type="text" id="Order_Customer_Fiscal_Name" value="{$order->get('Order Customer Fiscal Name')}" ovalue="{$order->get('Order Customer Fiscal Name')}" valid="0"> 
						<div id="Order_Customer_Fiscal_Name_Container">
						</div>
					</div>
					</td>
				</tr>
				<tr>
					<td colspan="2"> 
					<div class="buttons" style="margin-top:10px;text-align:right">
					    <span id="Customer_Fiscal_Name_wait" style="display:none"><img src="art/loading.gif"> {t}Processing request{/t}</span>
						<span id="Order_Customer_Fiscal_Name_msg"></span> <button class="positive" id="save_Customer_Fiscal_Name">{t}Save{/t}</button> <button class="negative" id="reset_Customer_Fiscal_Name">{t}Cancel{/t}</button> 
					</div>
					</td>
				</tr>
			</table>
		</div>
		
		
		<div id="dialog_quick_edit_Order_Customer_Name" style="padding:10px">
			<table style="margin:10px" border=0>
				<tr>
					<td>{t}Customer name{/t}:</td>
					<td> 
					<div>
						<input style="width:300px" type="text" id="Order_Customer_Name" value="{$order->get('Order Customer Name')}" ovalue="{$order->get('Order Customer Name')}" valid="0"> 
						<div id="Order_Customer_Name_Container">
						</div>
					</div>
					</td>
				</tr>
				<tr>
					<td colspan="2"> 
					<div class="buttons" style="margin-top:10px;text-align:right">
					    <span id="Customer_Name_wait" style="display:none"><img src="art/loading.gif"> {t}Processing request{/t}</span>
						<span id="Order_Customer_Name_msg"></span> <button class="positive" id="save_Customer_Name">{t}Save{/t}</button> <button class="negative" id="reset_Customer_Name">{t}Cancel{/t}</button> 
					</div>
					</td>
				</tr>
			</table>
		</div>
		
		<div id="dialog_quick_edit_Order_Customer_Contact_Name" style="padding:10px">
			<table style="margin:10px" border=0>
				<tr>
					<td>{t}Contact name{/t}:</td>
					<td> 
					<div>
						<input style="width:300px" type="text" id="Order_Customer_Contact_Name" value="{$order->get('Order Customer Contact Name')}" ovalue="{$order->get('Order Customer Contact Name')}" valid="0"> 
						<div id="Order_Customer_Contact_Name_Container">
						</div>
					</div>
					</td>
				</tr>
				<tr>
					<td colspan="2"> 
					<div class="buttons" style="margin-top:10px;text-align:right">
					    <span id="Customer_Contact_Name_wait" style="display:none"><img src="art/loading.gif"> {t}Processing request{/t}</span>
						<span id="Order_Customer_Contact_Name_msg"></span> <button class="positive" id="save_Customer_Contact_Name">{t}Save{/t}</button> <button class="negative" id="reset_Customer_Contact_Name">{t}Cancel{/t}</button> 
					</div>
					</td>
				</tr>
			</table>
		</div>
		
			<div id="dialog_quick_edit_Order_Customer_Telephone" style="padding:10px">
			<table style="margin:10px" border=0>
				<tr>
					<td>{t}Customer name{/t}:</td>
					<td> 
					<div>
						<input style="width:300px" type="text" id="Order_Customer_Telephone" value="{$order->get('Order Telephone')}" ovalue="{$order->get('Order Telephone')}" valid="0"> 
						<div id="Order_Customer_Telephone_Container">
						</div>
					</div>
					</td>
				</tr>
				<tr>
					<td colspan="2"> 
					<div class="buttons" style="margin-top:10px;text-align:right">
					    <span id="Customer_Telephone_wait" style="display:none"><img src="art/loading.gif"> {t}Processing request{/t}</span>
						<span id="Order_Customer_Telephone_msg"></span> <button class="positive" id="save_Customer_Telephone">{t}Save{/t}</button> <button class="negative" id="reset_Customer_Telephone">{t}Cancel{/t}</button> 
					</div>
					</td>
				</tr>
			</table>
		</div>
		
			<div id="dialog_quick_edit_Order_Customer_Email" style="padding:10px">
			<table style="margin:10px" border=0>
				<tr>
					<td>{t}Customer name{/t}:</td>
					<td> 
					<div>
						<input style="width:300px" type="text" id="Order_Customer_Email" value="{$order->get('Order Email')}" ovalue="{$order->get('Order Email')}" valid="0"> 
						<div id="Order_Customer_Email_Container">
						</div>
					</div>
					</td>
				</tr>
				<tr>
					<td colspan="2"> 
					<div class="buttons" style="margin-top:10px;text-align:right">
					    <span id="Customer_Email_wait" style="display:none"><img src="art/loading.gif"> {t}Processing request{/t}</span>
						<span id="Order_Customer_Email_msg"></span> <button class="positive" id="save_Customer_Email">{t}Save{/t}</button> <button class="negative" id="reset_Customer_Email">{t}Cancel{/t}</button> 
					</div>
					</td>
				</tr>
			</table>
		</div>
		
		
	</div>
	<div id="block_notes" class="details_block" style="display:none;">
		<span class="clean_table_title with_elements" style="margin-right:10px">{t}History/Notes{/t}</span> 
		<div class="buttons small left">
			<button id="note"><img src="art/icons/add.png" alt=""> {t}History Note{/t}</button> <button id="attach"><img src="art/icons/add.png" alt=""> {t}Attachment{/t}</button> 
		</div>
		<div class="elements_chooser">
			<span style="float:right;margin-left:20px;" class=" table_type transaction_type state_details {if $elements_order_history.Changes}selected{/if} label_part_history_Changes" id="elements_order_history_changes" table_type="elements_Changes">{t}Changes History{/t} (<span id="elements_history_Changes_number">{$elements_order_history_number.Changes}</span>)</span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_order_history.Notes}selected{/if}" id="elements_order_history_notes" table_type="elements_Notes">{t}Staff Notes{/t} (<span id="elements_history_Notes_number">{$elements_order_history_number.Notes}</span>)</span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_order_history.Attachments}selected{/if}" id="elements_order_history_attachments" table_type="elements_Attachments">{t}Attachments{/t} (<span id="elements_history_Attachments_number">{$elements_order_history_number.Attachments}</span>)</span> 
		</div>
		<div class="table_top_bar space">
		</div>
		{include file='table_splinter.tpl' table_id=3 filter_name=$filter_name3 filter_value=$filter_value3} 
		<div id="table3" class="data_table_container dtable btable">
		</div>
	</div>
	<div id="block_customer_data" class="details_block" style="display:none;">
		<span class="clean_table_title with_elements">{t}Customer History & Notes{/t}</span> 
		<div class="elements_chooser">
			<span style="float:right;margin-left:20px;" class=" table_type transaction_type state_details {if $elements_customer_data.Changes}selected{/if} label_customer_history_changes" id="elements_customer_data_changes" table_type="changes">{t}Changes History{/t} (<span id="elements_customer_data_history_Changes_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_customer_data.Orders}selected{/if} label_customer_history_orders" id="elements_customer_data_orders" table_type="orders">{t}Order History{/t} (<span id="elements_customer_data_history_Orders_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_customer_data.Notes}selected{/if} label_customer_history_notes" id="elements_customer_data_notes" table_type="notes">{t}Staff Notes{/t} (<span id="elements_customer_data_history_Notes_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_customer_data.Attachments}selected{/if} label_customer_history_attachments" id="elements_customer_data_attachments" table_type="attachments">{t}Attachments{/t} (<span id="elements_customer_data_history_Attachments_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_customer_data.Emails}selected{/if} label_customer_history_emails" id="elements_customer_data_emails" table_type="emails">{t}Emails{/t} (<span id="elements_customer_data_history_Emails_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements_customer_data.WebLog}selected{/if} label_customer_history_weblog" id="elements_customer_data_weblog" table_type="weblog">{t}WebLog{/t} (<span id="elements_customer_data_history_WebLog_number"><img src="art/loading.gif" style="height:12.9px" /></span>)</span> 
		</div>
		<div class="table_top_bar space">
		</div>
		{include file='table_splinter.tpl' table_id=2 filter_name=$filter_name2 filter_value=$filter_value2 } 
		<div id="table2" class="data_table_container dtable btable">
		</div>
	</div>
	<img id="hide_order_details" src="art/icons/arrow_sans_topleft.png" /> 
	<div style="clear:both">
	</div>
</div>
<div style="clear:both">
</div>
