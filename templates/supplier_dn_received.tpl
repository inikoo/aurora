{include file='header.tpl'}
<input id="dn_key" value="{$supplier_dn->id}" type="hidden" />
<input type="hidden" value="{$session_data}" id="session_data" />

<input type="hidden" id="history_table_id" value="3"> 
<input type="hidden" id="subject" value="supplier_dn"> 
<input type="hidden" id="subject_key" value="{$supplier_dn->id}"> 
<input type="hidden" id="warehouse_key" value="{$warehouse->id}"> 
<input id="supplier_deliver_note_key" value="{$supplier_dn->id}" type="hidden"/>


<div id="time2_picker" class="time_picker_div"></div>
<div id="bd" class="no_padding">
<div style="padding:0px 20px">
	{include file='suppliers_navigation.tpl'} 


<div class="branch">
		<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a>&rarr; <a href="suppliers.php">{t}Suppliers{/t}</a> &rarr; <a href="supplier.php?id={$supplier->id}">{$supplier->get('Supplier Name')}</a> &rarr; {$supplier_dn->get('Supplier Delivery Note Public ID')} ({$supplier_dn->get('Supplier Delivery Note Current State')})</span> 
	</div>
	<div class="top_page_menu" style="border:none">
		<div class="buttons" style="float:left">
			<span class="main_title no_buttons">{t}Supplier Delivery Note{/t} <span class="id">{$supplier_dn->get('Supplier Delivery Note Public ID')}</span></span> 
		</div>
		<div class="buttons small" style="position:relative;top:5px">
			<button style="{if $supplier_dn->get('Supplier Delivery Note Current State')!='In Process'}display:none{/if}" id="save_inputted_dn"><img id="save_inputted_dn_icon" src="art/icons/tick.png"> {t}Authorise delivery{/t}</button> 
			<button style="{if $supplier_dn->get('Supplier Delivery Note Current State')!='Inputted'}display:none{/if}" id="mark_as_received"><img id="mark_as_received_icon" src="art/icons/lorry.png"> {t}Mark as Received{/t}</button> 
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
	</div>
<ul class="tabs" id="items_chooser" >
		<li style="display:none"> <span class="item {if $products_display_type=='all_products'}selected{/if}" id="all_products"> <span> {t}Products{/t} (<span style="display:inline;padding:0px" id="all_products_number">{$supplier->get_formated_number_products_to_buy()}</span>)</span></span></li>
		<li> <span class="item {if $products_display_type=='ordered_products'}selected{/if}" id="ordered_products"> <span> {t}Order Items{/t} (<span style="display:inline;padding:0px" id="ordered_products_number">{$supplier_dn->get('Number Items')}</span>)</span></span></li>
	</ul>
	<div class="tabs_base">
	</div>

<div id="the_table" class="data_table" style="margin:20px 0px;clear:both;padding:0px 20px">
	<span class="clean_table_title">{t}Supplier Products{/t}</span> 
			
			<div class="buttons"></div>
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
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_filter('{$menu.db_key}','{$menu.label}',0)"> {$menu.menu_label}</a></li>
      {/foreach}
    </ul>
  </div>
</div>

<div id="rppmenu0" class="yuimenu">
  <div class="bd">
    <ul class="first-of-type">
      <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Rows per Page{/t}:</li>
      {foreach from=$paginator_menu0 item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_rpp({$menu},0)"> {$menu}</a></li>
      {/foreach}
    </ul>
  </div>
</div>





<div id="checked_dialog" class="yuimoenu" style="border:none;padding:10px">
<div class="db" style="border:1px solid #777" >
  <div id="checked_dialog_msg"></div>
  <table>
    <tr>
      <td class="aright" style="width:100px"></td><td>
	<div class="options" style="margin:0px 0;width:200px" id="checked_method_container">
      </td>
    </tr>
    <input type="hidden" id="date_type" value="now"/>
   
        <input type="hidden" id="checked_by" value="{$user_staff_key}"/>

      <td class="aright">{t}Checked By{/t}:</td><td style="position:relative"> <span id="get_checker" class="state_details" style="position:absolute;left:200px">{t}Modify{/t}</span><span id="checked_by_alias"></span></td>
    </tr>

    <tr><td colspan=2 style="border-top:1px solid #ddd;text-align:center;padding:10px 0 0 0">
	<span style="margin-left:50px" class="state_details" onClick="checked_order_save(this)"  >Save</span>
    
    </td>
</tr>
  </table>
  </div>
</div>

<div id="staff_dialog" class="yuimenu options_list"  >
  <div class="bd">
    <table border=1>
      {foreach from=$staff item=_staff name=foo}
      {if $_staff.mod==0}<tr>{/if}
	<td staff_id="{$_staff.id}" id="chekers{$_staff.id}" onClick="select_staff(this,event)" >{$_staff.alias}</td>
	{if $_staff.mod==$staff_cols}</tr>{/if}
      {/foreach}
    </table>
<span class="state_details" style="float:right" onClick="close_dialog('staff')" >{t}Close{/t}</span>
  </div>
</div>





{include file='footer.tpl'}

