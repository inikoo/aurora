{include file='header.tpl'} 
<input type="hidden" value="{$session_data}" id="session_data" />
<input type="hidden" id="po_keys" value="{$supplier_dn->get('Supplier Delivery Note POs')}"> 
<input id="supplier_delivery_note_key" value="{$supplier_dn->id}" type="hidden" />
<input id="supplier_key" value="{$supplier->id}" type="hidden" />
<input type="hidden" value="{$products_display_type}" id="products_display_type"> 
<input type="hidden" id="history_table_id" value="3"> 
<input type="hidden" id="subject" value="supplier_dn"> 
<input type="hidden" id="subject_key" value="{$supplier_dn->id}"> 
<input type="hidden" id="warehouse_key" value="{$warehouse->id}"> 

<div id="time2_picker" class="time_picker_div">
</div>
<div id="bd">
	{include file='suppliers_navigation.tpl'} 
	<div class="branch">
		<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a>&rarr; <a href="suppliers.php">{t}Suppliers{/t}</a> &rarr; <a href="supplier.php?id={$supplier->id}">{$supplier->get('Supplier Name')}</a> &rarr; {$supplier_dn->get('Supplier Delivery Note Public ID')} ({$supplier_dn->get('Supplier Delivery Note Current State')})</span> 
	</div>
	<div class="top_page_menu" style="border:none">
		<div class="buttons" style="float:left">
			<span class="main_title no_buttons">{t}Supplier Delivery Note{/t} <span class="id">{$supplier_dn->get('Supplier Delivery Note Public ID')}</span></span> 
		</div>
		<div class="buttons small" style="position:relative;top:5px">
			<button class="negative" id="delete_dn"><img id="delete_dn_icon" src="art/icons/cross.png"> {t}Delete{/t}</button> <button style="{if $supplier_dn->get('Supplier Delivery Note Current State')!='In Process'}display:none{/if}" id="save_inputted_dn"><img id="save_inputted_dn_icon" src="art/icons/tick.png"> {t}Authorise delivery{/t}</button> <button style="{if $supplier_dn->get('Supplier Delivery Note Current State')!='Inputted'}display:none{/if}" id="mark_as_received"><img id="mark_as_received_icon" src="art/icons/lorry.png"> {t}Mark as Received{/t}</button> 
		</div>
		<div style="clear:both">
		</div>
	</div>
	<div id="order_header">
		<div class="content">
			<div class="totals column">
				<table>
					<tr>
						<td>{t}PO Items{/t}</td>
						<td id="ordered_products_number" class="total aright ">{$supplier_dn->get('Number Items')}</td>
					</tr>
					<tr>
						<td>{t}Items without PO{/t}</td>
						<td id="products_without_po_number" class=" aright ">{$supplier_dn->get('Number Items Without PO')}</td>
					</tr>
				</table>
			</div>
			<div class="dates column">
				<table border="0">
					<tr class="last">
						<td class="label">{t}Created{/t}:</td>
						<td class="aright">{$supplier_dn->get('Creation Date')}</td>
					</tr>
				</table>
			</div>
			<div class="supplier column">
				<table border="0">
					<tr class="last">
						<td>{t}Supplier{/t}:</td>
						<td class="aright"><a href="supplier.php?id={$supplier->get('Supplier Key')}">{$supplier->get('Supplier Name')}</a></td>
						<td></td>
					</tr>
				</table>
				<table border="0" class="related_objects" style="{if $number_pos==0}display:none{/if}">
					<tr class="title">
						<td colspan="2">{if $number_pos==1}{t}Purchase Order{/t}{else}Purchase Orders{/if}:</td>
					</tr>
					{foreach from=$pos_data item=po} 
					<tr>
						<td> <a href="porder.php?id={$po.key}">{$po.number}</a> <a target='_blank' href="porder.pdf.php?id={$po.key}"> <img style="height:10px;vertical-align:0px" src="art/pdf.gif"></a> <img onclick="print_pdf('po',{$po.key})" style="cursor:pointer;margin-left:2px;height:10px;vertical-align:0px" src="art/icons/printer.png"> </td>
						<td class="right" style="text-align:right"> {$po.state} </td>
					</tr>
					{/foreach} 
				</table>
			</div>
			<div style="clear:both">
			</div>
		</div>
		<ul class="tabs" id="chooser_ul" style="clear:both;margin-top:15px">
			<li> <span class="item " id="attachments"> <span id="attachments_label"> {t}Attachments{/t} {if $number_attachments!=0} ({$number_attachments}){/if}</span> </span> </li>
			<li> <span class="item " id="notes"> <span> {t}History/Notes{/t}</span></span></li>
		</ul>
		<div id="order_details_panel" style="display:none;clear:both;border-top:1px solid #ccc;padding:10px 10px 10px; 10px;;">
			<div id="block_attachments" class="block_details" style="display:none">
				<div class="buttons small">
					<button id="attach_bis"><img src="art/icons/add.png"> {t}Attachment{/t}</button> 
				</div>
				<div id="attachments_showcase">
					{include file='attachments_showcase_splinter.tpl' attachments=$supplier_dn->get_attachments_data()} 
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
		<span class="clean_table_title">{t}Supplier Products{/t}</span> 
		<div class="elements_chooser">
			<span style="float:right;margin-left:20px;" class=" table_type transaction_type state_details {if $products_display_type=='all_products'}selected{/if} label_all_products" id="all_products">{t}Supplier Products{/t} (<span id="all_products_number">{$supplier->get_formated_number_products_to_buy()}</span>)</span> <span style="float:right;margin-left:20px;" class=" table_type transaction_type state_details {if $products_display_type=='ordered_products'}selected{/if} label_ordered_products" id="ordered_products">{t}Products in Delivery Note{/t} (<span id="products_number">{$supplier_dn->get('Number Items')}</span>)</span> 
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
	<div class="db">
		<div id="delete_dialog_msg" class="dialog_msg" style="padding:0 0 10px 0 ">
			{t}Note: this action can not be undone{/t}. 
		</div>
		<table style="width:250px">
			<tr>
				<td style="border-top:1px solid #ddd;text-align:center;padding:10px 0 0 0"> 
				<div class="buttons">
					<button class="negative" onclick="delete_supplier_dn()">{t}Delete Supplier Delivery Note{/t}</button> 
				</div>
				</td>
			</tr>
		</table>
	</div>
</div>
<div id="received_dialog" style="padding:10px 15px">
	<div id="received_dialog_msg">
	</div>
	<div class="options" style="margin:0px 0;width:200px" id="received_method_container">
	</div>
	<table class="edit" style="width:100%">
		<input type="hidden" id="date_type" value="now" />
		<tr class="title">
			<td colspan="2">{t}Receive delivery{/t}</td>
		</tr>
		<tr id="tr_manual_received_date" style="display:none">
			<td class="aright" style="width:150px"><img class="edit_icon" src="art/icons/edit.gif" alt="{t}Edit{/t}" onclick="submit_date_manually()" /> {t}Received Date{/t}:</td>
			<td style="width:150px;padding-left:5px">{t}Now{/t}</td>
		</tr>
		<tbody style="display:none" id="tbody_manual_received_date">
			<tr>
				<td class="aright">{t}Received Date{/t}:</td>
				<td> 
				<input id="v_calpop1" style="text-align:right;" class="text" name="submites_date" type="text" size="10" maxlength="10" value="" />
				<img id="calpop1" style="cursor:pointer" src="art/icons/calendar_view_month.png" align="top" alt="" /> 
				<div id="cal1Container" style="position:absolute;display:none; z-index:2">
				</div>
				</td>
			</tr>
			<tr>
				<td class="aright">{t}Time{/t}:</td>
				<td> 
				<input id="v_time" style="text-align:right;" class="text" name="expected_date" type="text" size="5" maxlength="5" value="" />
				<img id="calpop1" style="cursor:pointer" src="art/icons/time.png" align="top" alt="" /> </td>
			</tr>
		</tbody>
		<tr class="first">
			<input type="hidden" id="received_by" value="{$user->get_staff_key()}" />
			<td class="aright"><img class="edit_icon" src="art/icons/edit.gif" alt="{t}Edit{/t}" id="get_receiver" /> {t}Received By{/t}:</td>
			<td style="width:150px;padding-left:5px"><span style="cursor:pointer" onclick="show_staff_dialog()" id="received_by_alias">{$user->get_staff_name()}</span></td>
		</tr>
		<input type="hidden" id="location_key" value="{$default_loading_location_key}" />
		<tr>
			<td class="aright"><img class="edit_icon" src="art/icons/edit.gif" alt="{t}Edit{/t}" id="get_location" /> {t}Receiving Location{/t}:</td>
			<td style="width:150px;padding-left:5px"> <span style="cursor:pointer" onclick="show_location_dialog()"  id="location_code">{$default_loading_location_code}</span> </td>
		</tr>
		<tr class="buttons">
			<td></td>
			<td> 
			<div class="buttons left">
				<button onclick="received_order_save()" class="positive">{t}Save{/t}</button> 
			</div>
			</td>
		</tr>
	</table>
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
{include file='notes_splinter.tpl'} {include file='footer.tpl'} 