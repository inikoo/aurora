{include file='header.tpl'} 
<input type="hidden" value="{$products_display_type}" id="products_display_type"> 
<input type="hidden" value="{$po->id}" id="po_key"> 
<input type="hidden" value="{$supplier->id}" id="supplier_key"> 
<div id="time2_picker" class="time_picker_div">
</div>
<div id="bd">
	<div id="cal1Container" style="position:absolute;left:610px;top:120px;display:none;z-index:3">
	</div>
	
	{include file='suppliers_navigation.tpl'} 
		<div class="branch">
			<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a>&rarr; <a href="suppliers.php">{t}Suppliers{/t}</a> &rarr; {$supplier->get('Supplier Name')}</span> 
		</div>
	
	<div class="top_page_menu" style="border:none">
	<div class="buttons" style="float:left">
			<span class="main_title">{t}Purchase Order{/t} <span class="id">{$po->get('Purchase Order Public ID')}</span></span> 
		</div>
	<div class="buttons">
		<button id="cancel_po">{t}Cancel{/t}</button> 
		<button id="invoice_po">{t}Match to Invoice{/t}</button>
		<button id="dn_po">{t}Match to Delivery Note{/t}</button> 
	</div>
	<div style="clear:both"></div>
	</div>
	<div class="prodinfo" style="margin-top:2px;font-size:85%;border:1px solid #ddd;padding:10px;">
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
				<td style="width:100px">
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
<div id="edit_estimated_delivery_dialog" >
	<div class="bd" style="padding-bottom:0px;margin-top:20px">
		<table class="edit" style="width:100%" border=0>
			<tr>
				<td colspan="2"> <span>{t}Estimated Delivery{/t}:</span> 
				<input id="v_calpop_estimated_delivery" type="text" class="text" size="11" maxlength="10" name="from" value="{$po->get('Estimated Receiving Date For Edition')}" />
				<img id="estimated_delivery_pop" class="calpop" src="art/icons/calendar_view_month.png" align="absbottom" alt="choose" /> <br />
				</td>
			</tr>
			<tr class="space10">
				<td colspan="2" > 
				<div class="buttons">
				<button style="margin-left:50px" onclick="submit_edit_estimated_delivery(this)">Save</button> 
				</div>
				</td>
			</tr>
		</table>
	</div>
</div>
<div id="staff_dialog" class="yuimenu options_list">
	<div class="bd">
		<table border="1">
			{foreach from=$staff item=_staff name=foo} {if $_staff.mod==0}
			<tr>
				{/if} 
				<td staff_id="{$_staff.id}" id="receivers{$_staff.id}" onclick="select_staff(this,event)">{$_staff.alias}</td>
				{if $_staff.mod==$staff_cols}
			</tr>
			{/if} {/foreach} 
		</table>
		<span class="state_details" style="float:right" onclick="close_dialog('staff')">{t}Close{/t}</span> 
	</div>
</div>
{include file='footer.tpl'} 