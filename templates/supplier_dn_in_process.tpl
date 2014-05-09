{include file='header.tpl'} 
<div id="time2_picker" class="time_picker_div">
</div>
	<input id="supplier_delivery_note_key" value="{$supplier_dn->id}" type="hidden" />
	<input id="supplier_key" value="{$supplier->id}" type="hidden" />
<input type="hidden" value="{$products_display_type}" id="products_display_type"> 

<div id="bd">
	{include file='suppliers_navigation.tpl'} 

		<div class="branch">
		<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a>&rarr; <a href="suppliers.php">{t}Suppliers{/t}</a> &rarr; <a href="supplier.php?id={$supplier->id}">{$supplier->get('Supplier Name')}</a> &rarr; {$supplier_dn->get('Supplier Delivery Note Public ID')} ({$supplier_dn->get('Supplier Delivery Note Current State')})</span> 
		</div>
	
	<div class="top_page_menu" style="border:none">
	<div class="buttons" style="float:left">
			<span class="main_title">{t}Supplier Delivery Note{/t} <span class="id">{$supplier_dn->get('Supplier Delivery Note Public ID')}</span></span> 
		</div>
	<div class="buttons">
		<button class="negative" id="delete_dn">{t}Delete{/t}</button> 
		<button id="save_inputted_dn">{t}Save Delivery Note{/t}</button>
	</div>
	<div style="clear:both"></div>
	</div>
	
	

	<div class="prodinfo" style="margin-top:2px;font-size:85%;border:1px solid #ddd;padding:10px">
		<div style="border:0px solid red;width:290px;float:right">
			<table border="0" class="order_header" style="margin-right:30px;float:right">
				<tr>
					<td class="aright" style="padding-right:40px">{t}Created{/t}:</td>
					<td>{$supplier_dn->get('Creation Date')}</td>
				</tr>
			</table>
		</div>
		
		<table border="0">
			<tr>
				<td>{t}Supplier Delivery Note Key{/t}:</td>
				<td class="aright">{$supplier_dn->get('Supplier Delivery Note Key')}</td>
			</tr>
			<tr>
				<td>{t}Supplier{/t}:</td>
				<td class="aright"><a href="supplier.php?id={$supplier->get('Supplier Key')}">{$supplier->get('Supplier Name')}</a></td>
			</tr>
			<tr>
				<td>{t}Ordered items{/t}:</td>
				<td class="aright" id="ordered_products_number">{$supplier_dn->get('Number Ordered Items')}</td>
			</tr>
			<tr>
				<td>{t}Items without PO{/t}:</td>
				<td class="aright" id="products_without_po_number">{$supplier_dn->get('Number Items Without PO')}</td>
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
		<span class="clean_table_title">{t}Supplier Products{/t}</span> 
		
		<div class="elements_chooser">
			<span style="float:right;margin-left:20px;" class=" table_type transaction_type state_details {if $products_display_type=='all_products'}selected{/if} label_all_products" id="all_products">{t}Supplier Products{/t} (<span id="all_products_number">{$supplier->get_formated_number_products_to_buy()}</span>)</span> 
			<span style="float:right;margin-left:20px;" class=" table_type transaction_type state_details {if $products_display_type=='ordered_products'}selected{/if} label_ordered_products" id="ordered_products">{t}Products in Delivery Note{/t} (<span id="ordered_products_number">{$supplier_dn->get('Number Items')}</span>)</span> 
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
				<td style="border-top:1px solid #ddd;text-align:center;padding:10px 0 0 0"> <span class="state_details" onclick="close_dialog('delete')">{t}Cancel{/t}</span> <span style="margin-left:50px" class="state_details" onclick="delete_order()">{t}Delete Supplier Delivery Note{/t}</span> </td>
			</tr>
		</table>
	</div>
</div>
{include file='footer.tpl'} 