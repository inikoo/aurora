{include file='header.tpl'} 
<input type="hidden" value="{$session_data}" id="session_data" />
<input type="hidden" value="{$part_families_root_category_key}" id="part_families_root_category_key" />
<input type="hidden" value="{$parent}" id="parent" />
<input type="hidden" value="{$supplier_id}" id="supplier_id" />



<div id="bd">
	{if $parent=='parts_family'} {include file='locations_navigation.tpl'} 
	<div class="branch">
		<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a>&rarr; {if $user->get_number_stores()>1}<a href="customers_server.php">{t}Customers{/t}</a> &rarr; {/if}<a href="customers.php?store={$store->id}">{$store->get('Store Code')} {t}Customers{/t}</a> &rarr; {t}Adding Customer{/t}</span> 
	</div>
	<div style="clear:both;width:100%;border-bottom:1px solid #ccc;padding-bottom:3px;margin-bottom:15px">
		<div class="buttons" style="float:left">
			<span class="main_title"> {t}Adding new part{/t}, <span class="id">{$store->get('Store Code')}</span> </span> 
		</div>
		<div class="buttons">
			<button class="negative" onclick="window.location='customers.php?store={$store->id}'">{t}Cancel{/t}</button> 
		</div>
		<div style="clear:both">
		</div>
	</div>
	{else} {include file='suppliers_navigation.tpl'} 
	<div class="branch">
		<span><a href="suppliers.php">{t}Suppliers{/t}</a> &rarr; <a href="supplier.php?id={$supplier->id}">{$supplier->get('Supplier Name')}</a> &rarr; <a href="supplier_product.php?pid={$supplier_product->pid}">{$supplier_product->get('Supplier Product Code')}</a> &rarr; {t}Adding Part{/t}</span> 
	</div>
	<div style="clear:both;width:100%;border-bottom:1px solid #ccc;padding-bottom:3px;margin-bottom:15px">
		<div class="buttons" style="float:left">
			<span class="main_title"> {t}Associating new part{/t}, <span class="id">{$supplier_product->get('Supplier Product Code')}</span> </span> 
		</div>
		<div class="buttons">
			<button class="negative" onclick="window.location='supplier_product.php?pid={$supplier_product->pid}'">{t}Cancel{/t}</button> 
		</div>
		<div style="clear:both">
		</div>
	</div>
	{/if} 
	<div>
		<div id="results" style="margin-top:0px;float:right;width:600px;">
		</div>
		<div style="float:left;width:600px;">
			<input id="sp_pid" type="hidden" value="{if isset($supplier_product)}{$supplier_product->pid}{/if}"  />
			<input id="part_family_key" type="hidden" value="{if isset($part_family)}{$part_family->id}{/if}"  />

			<table id="new_part" class="edit" border="0" >
				<tr class="title">
					<td colspan="3">{t}Supplier Product{/t}</td>
				</tr>
				<tr>
					<td class="label">{t}Supplier{/t}:</td>
					<td class="input_field"><span id="supplier_name">{if isset($supplier_product)}{$supplier->get('Supplier Name')}{/if}</span> </td>
					<td class="messages"></td>
				</tr>
				<tr>
					<td class="label">{t}Supplier product{/t}:</td>
					<td class="input_field"><span id="supplier_product">{if isset($supplier_product)}{$supplier_product->get('Supplier Product Code')}{/if}</span> </td>
					<td class="messages"></td>
				</tr>
				
				<tr class="title">
					<td colspan="3">{t}Part Family{/t}</td>
				</tr>
				<tr>
					<td class="label">{t}Part Family{/t}:</td>
					<td class="input_field"><span id="part_family"  style="float:left;margin-right:10px">{if isset($part_family)}{$part_family->get('Category Label')}{else}{/if}</span> 
					<div class="buttons small">
					<button id="show_part_families_dialog" onClick="show_part_families_dialog()">{t}Select Family{/t}</button>
					</div>
					</td>
					<td class="messages"></td>
				</tr>
				
				
				<tr class="title">
					<td colspan="3">{t}Part Info{/t}</td>
				</tr>
				<tr>
					<td class="label">{t}Part Reference{/t}:</td>
					<td class="input_field"> 
					<div>
						<input style="width:100%" id="part_reference" changed="0" type='text' maxlength="255" class='text' value="{$supplier_product->get('Supplier Product Code')}" />
						<div id="part_reference_Container">
						</div>
					</div>
					</td>
					<td class="message edit_td_alert" id="part_reference_msg"></td>
				</tr>
				
				<tr>
					<td class="label">{t}Part Description{/t}:</td>
					<td class="input_field"> 
					<div>
						<input style="width:100%" id="part_description" changed="0" type='text' maxlength="255" class='text' value="{if isset($supplier_product)}{$supplier_product->get('Supplier Product Description')}{/if}" />
						<div id="part_description_Container">
						</div>
					</div>
					</td>
					<td class="message edit_td_alert" id="part_description_msg"></td>
				</tr>
				
				<tr class="buttons">
					<td colspan="2"> 
					<div class="buttons">
						<button style="margin-right:10px;visibility:" id="save_new_part" class="positive disabled">{t}Save{/t}</button> <button style="margin-right:10px;visibility:" id="reset_new_part" class="negative">{t}Reset{/t}</button> 
					</div>
					</td>
				</tr>
			</table>
		</div>
		<div style="clear:both;height:40px">
		</div>
	</div>
</div>
<div id="dialog_part_families_list" style="position:absolute;left:-1000;top:0">
	<div class="splinter_cell" style="padding:10px 15px 10px 0;border:none">
		<div class="data_table">
			<span class="clean_table_title">{t}Part Families{/t}</span> {include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0} 
			<div id="table0" class="data_table_container dtable btable">
			</div>
		</div>
	</div>
</div>
{include file='footer.tpl'}