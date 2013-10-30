{include file='header.tpl'} 
<div id="bd">
	{include file='suppliers_navigation.tpl'} 
	<div class="branch">
		<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a>&rarr; <a href="suppliers.php">{t}Suppliers{/t}</a> &rarr; {$supplier->get('Supplier Name')}</span> 
	</div>
	<div class="top_page_menu">
		<div class="buttons" style="float:left">
			<span class="main_title"> {t}Edit Supplier{/t}: <span id="title_name">{$supplier->get('Supplier Name')}</span> (<span id="title_code">{$supplier->get('Supplier Code')}</span>) </span> 
		</div>
		<div class="buttons">
			<button onclick="window.location='supplier.php?id={$supplier->id}'"><img src="art/icons/door_out.png" alt=""> {t}Exit Edit{/t}</button> 
		</div>
		<div style="clear:both">
		</div>
	</div>
	<ul class="tabs" id="chooser_ul" style="clear:both">
		<li> <span class="item {if $edit=='details'}selected{/if}" id="details"> <span> {t}Supplier Details{/t}</span></span></li>
		<li style="display:none"> <span class="item {if $edit=='company'}selected{/if}" id="company"> <span> {t}Company Details{/t}</span></span></li>
		<li> <span class="item {if $edit=='products'}selected{/if}" id="products"> <span> {t}Supplier Products{/t}</span></span></li>
		<li> <span class="item {if $edit=='categories'}selected{/if}" id="categories"> <span> {t}Categories{/t}</span></span></li>
	</ul>
	<div class="tabbed_container">
		<div class="edit_block" style="{if $edit!='details'}display:none{/if}" id="d_details">
			
			<table class="edit" border="0" style="clear:both;width:100%">
				<tr class="first">
					<td style="width:150px" class="label">Supplier Code:</td>
					<td style="text-align:left;width:300px"> 
					<div >
						<input style="text-align:left;" id="Supplier_Code" value="{$supplier->get('Supplier Code')}" ovalue="{$supplier->get('Supplier Code')}" valid="0"> 
						<div id="Supplier_Code_Container">
						</div>
					</div>
					</td>
					<td  id="Supplier_Code_msg" class="edit_td_alert"></td>
				</tr>
				<tr class="first">
					<td class="label">{t}Company Name{/t}:</td>
					<td style="text-align:left"> 
					<div >
						<input style="text-align:left;" id="Supplier_Name" value="{$supplier->get('Supplier Name')}" ovalue="{$supplier->get('Supplier Name')}" valid="0"> 
						<div id="Supplier_Name_Container">
						</div>
					</div>
					</td>
					<td id="Supplier_Name_msg" class="edit_td_alert"></td>
				</tr>
				<tr>
					<td style=";width:12em" class="label">{t}Contact Name{/t}:</td>
					<td style="text-align:left;"> 
					<div >
						<input style="text-align:left;" id="Supplier_Main_Contact_Name" value="{$supplier->get('Supplier Main Contact Name')}" ovalue="{$supplier->get('Supplier Main Contact Name')}" valid="0"> 
						<div id="Supplier_Main_Contact_Name_Container">
						</div>
					</div>
					</td>
					<td id="Supplier_Main_Contact_Name_msg" class="edit_td_alert"></td>
				</tr>
				<tr>
					<td class="label">{t}Contact Email{/t}:</td>
					<td style="text-align:left"> 
					<div>
						<input style="text-align:left;" id="Supplier_Main_Email" value="{$supplier->get('Supplier Main Plain Email')}" ovalue="{$supplier->get('Supplier Main Plain Email')}" valid="0"> 
						<div id="Supplier_Main_Email_Container">
						</div>
					</div>
					</td>
					<td id="Supplier_Main_Email_msg" class="edit_td_alert"></td>
				</tr>
				<tr>
					<td class="label">{t}Telephone{/t}:</td>
					<td style="text-align:left"> 
					<div >
						<input style="text-align:left;" id="Supplier_Main_Telephone" value="{$supplier->get('Supplier Main XHTML Telephone')}" ovalue="{$supplier->get('Supplier Main XHTML Telephone')}" valid="0"> 
						<div id="Supplier_Main_Telephone_Container">
						</div>
					</div>
					</td>
					<td id="Supplier_Main_Telephone_msg" class="edit_td_alert"></td>
				</tr>
				<tr>
					<td class="label">{t}Fax{/t}:</td>
					<td style="text-align:left"> 
					<div >
						<input style="text-align:left;" id="Supplier_Main_Fax" value="{$supplier->get('Supplier Main XHTML FAX')}" ovalue="{$supplier->get('Supplier Main XHTML FAX')}" valid="0"> 
						<div id="Supplier_Main_Fax_Container">
						</div>
					</div>
					</td>
					<td id="Supplier_Main_Fax_msg" class="edit_td_alert"></td>
				</tr>
				<tr>
					<td class="label">{t}Web Page{/t}:</td>
					<td style="text-align:left"> 
					<div >
						<input style="text-align:left;s" id="Supplier_Main_Web_Site" value="{$supplier->get('Supplier Website')}" ovalue="{$supplier->get('Supplier Website')}" valid="0"> 
						<div id="Supplier_Main_Web_Site_Container">
						</div>
					</div>
					</td>
					<td id="Supplier_Main_Web_Site_msg" class="edit_td_alert"></td>
				</tr>
				
				<tr style="height:10px"><td colspan=3>
				
				</td></tr>
				
				<tr><td colspan=2>
				<div class="buttons" >
				<button style="margin-right:10px;" id="save_edit_supplier" onclick="save_edit_general('supplier')" class="positive disabled">{t}Save{/t}</button> 
				<button style="margin-right:10px;" id="reset_edit_supplier" onclick="reset_edit_general('supplier')" class="negative disabled">{t}Reset{/t}</button> 
			</div>
				</td><td></td></tr>
				
			</table>
			<table class="edit" border="0" style="clear:both;margin-top:10px;width:100%">
				<tr class="title">
					<td colspan="2"> {t}Contact Address{/t} </td>
				</tr>
				<tr style="height:1px">
					<td style="width:150px"> </td>
					<td style="width:300px"> </td>
					<td> </td>
				</tr>
				{include file='edit_address_splinter.tpl' address_identifier='contact_' hide_type=true hide_description=true show_components=true hide_buttons=false default_country_2alpha="$default_country_2alpha" show_form=1 show_default_country=1 address_type=false function_value='' address_function='' show_contact=false show_tel=false close_if_reset=false } 
			</table>
			<div style="clear:both">
			</div>
		</div>
		<div class="edit_block" style="{if $edit!='company'}display:none{/if}" id="d_company">
			<div class="general_options" style="float:right">
				<span style="margin-right:10px;display:none" id="save_new_supplier" class="state_details">{t}Save{/t}</span> <span style="margin-right:10px;display:none" id="close_add_supplier" class="state_details">{t}Reset{/t}</span> 
			</div>
			<div id="new_supplier_messages" class="messages_block">
			</div>
			{include file='edit_company_splinter.tpl'} 
		</div>
		<div class="edit_block" style="{if $edit!='products'}display:none{/if}" id="d_products">
			<div class="general_options" style="float:right; text-align:right; ">
				<span style="margin-right:10px;" id="show_new_product_dialog_button" onclick="show_new_product_dialog()" class="state_details">{t}New Product{/t}</span> <span style="margin-right:10px;" id="import_new_product" class="state_details">{t}Import Products (CSV){/t}</span> <span style="margin-right:10px;visibility:hidden" id="save_new_product" onclick="save_new_general('product')" class="state_details">{t}Save New Product{/t}</span> <span style="margin-right:10px;visibility:hidden" id="cancel_new_product" onclick="cancel_new_general('product')" class="state_details">{t}Cancel New Product{/t}</span> 
			</div>
			<div class="data_table" style="clear:both">
				<div id="suppliers_product_list">
					<span class="clean_table_title">{t}Suppliers Product List{/t}</span> 
					<div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999;margin-bottom:10px">
					</div>
					{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0 } 
					<div id="table0" class="data_table_container dtable btable" style="font-size:85%">
					</div>
				</div>
			</div>
		</div>
		<div class="edit_block" style="{if $edit!='categories'}display:none{/if}" id="d_categories">
			<table class="edit">
				<tr class="title">
					<td colspan="5">{t}Categories{/t}</td>
				</tr>
				{foreach from=$categories item=cat key=cat_key name=foo } 
				<tr>
					<td class="label">{t}{$cat->get('Category Code')}{/t}:</td>
					<td> 
					<select id="cat{$cat_key}" cat_key="{$cat_key}" onchange="save_category(this)">
						{foreach from=$cat->get_children_objects() item=sub_cat key=sub_cat_key name=foo2 } {if $smarty.foreach.foo2.first} 
						<option {if $categories_value[$cat_key]="=''" }selected="selected" {/if} value="">{t}Unknown{/t}</option>
						{/if} 
						<option {if $categories_value[$cat_key]="=$sub_cat_key" }selected="selected" {/if} value="{$sub_cat->get('Category Key')}">{$sub_cat->get('Category Code')}</option>
						{/foreach} 
					</select>
					</td>
				</tr>
				{/foreach} 
			</table>
		</div>
	</div>
	<div class="buttons small" style="margin-top:0">
		<button id="show_history" style="{if $show_history}display:none{/if};margin-right:0px" onClick="show_history()" >{t}Show changelog{/t}</button>
		<button id="hide_history" style="{if !$show_history}display:none{/if};margin-right:0px" onClick="hide_history()">{t}Hide changelog{/t}</button>
		</div>
	<div id="history_table" class="data_table" style="clear:both;{if !$show_history}display:none{/if}">
		<span class="clean_table_title">{t}Changelog{/t}</span> 
		<div id="table_type" class="table_type">
			<div style="font-size:90%" id="part_type_chooser">
				<span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $history_elements.Change}selected{/if} label_part_Change" id="elements_Change" table_type="Change">{t}Change{/t} (<span id="elements_Change_number">{$history_elements_number.Change}</span>)</span> <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $history_elements.Assign}selected{/if} label_part_Assign" id="elements_Assign" table_type="Assign">{t}Assign{/t} (<span id="elements_Assign_number">{$history_elements_number.Assign}</span>)</span> 
			</div>
		</div>
		<div class="table_top_bar space">
		</div>
		{include file='table_splinter.tpl' table_id='1' filter_name=$filter_name1 filter_value=$filter_value1 } 
		<div id="table1" class="data_table_container dtable btable">
		</div>
	</div>



</div>
<div id="rppmenu1" class="yuimenu">
	<div class="bd">
		<ul class="first-of-type">
			<li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Rows per Page{/t}:</li>
			{foreach from=$paginator_menu1 item=menu } 
			<li class="yuimenuitem"><a class="yuimenuitemlabel" onclick="change_rpp_with_totals({$menu},1)"> {$menu}</a></li>
			{/foreach} 
		</ul>
	</div>
</div>
<div id="filtermenu1" class="yuimenu">
	<div class="bd">
		<ul class="first-of-type">
			<li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Filter options{/t}:</li>
			{foreach from=$filter_menu1 item=menu } 
			<li class="yuimenuitem"><a class="yuimenuitemlabel" onclick="change_filter('{$menu.db_key}','{$menu.label}',1)"> {$menu.menu_label}</a></li>
			{/foreach} 
		</ul>
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
{include file='footer.tpl'} 
<div id="dialog_country_list" style="position:absolute;left:-1000;top:0">
	<div class="splinter_cell" style="padding:10px 15px 10px 0;border:none">
		<div id="the_table" class="data_table">
			<span class="clean_table_title">{t}Country List{/t}</span> {include file='table_splinter.tpl' table_id=100 filter_name=$filter_name100 filter_value=$filter_value100} 
			<div id="table100" class="data_table_container dtable btable">
			</div>
		</div>
	</div>
</div>
