{include file='header.tpl'} 
<input type="hidden" id="store_key" value="{$store->get('Store Key')}" />
<input type="hidden" id="product_pid" value="{$product->pid}" />
<input type="hidden" id="scope" value="product" />
<input type="hidden" id="scope_key" value="{$product->pid}" />
<input type="hidden" id="No_numeric_value" value="{t}Error, no numeric value{/t}" />
<input type="hidden" id="Invalid_value" value="{t}Error, invalid value{/t}" />
<input type="hidden" id="decimal_point" value="{$decimal_point}" />
<input type="hidden" id="thousands_sep" value="{$thousands_sep}" />
<input type="hidden" id="currency_symbol" value="{$store_currency_symbol}" />
<div style="display:none; position:absolute; left:10px; top:200px; z-index:2" id="cal1Container">
</div>
<div id="bd">
	{include file='assets_navigation.tpl'} 
	<div class="branch">
		<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a>&rarr; {if $user->get_number_stores()>1}<a href="stores.php">{t}Stores{/t}</a> &rarr; {/if}<a href="store.php?id={$store->id}" title="{$store->get('Store Name')}">{$store->get('Store Code')}</a> &rarr; <span id="department_branch"><a href="department.php?id={$product->get('Product Main Department Key')}" title="{$product->get('Product Main Department Name')}">{$product->get('Product Main Department Code')}</a></span> &rarr; <span id="family_branch"><a href="family.php?id={$product->get('Product Family Key')}" title="{$product->get('Product Family Name')}">{$product->get('Product Family Code')}</a></span> &rarr; <a href="product.php?pid=$product->pid" title="{$product->get('Product Name')}}">{$product->get('Product Code')}</a> ({t}Editing{/t})</span> 
	</div>
	<div class="top_page_menu">
		<div class="buttons" style="float:left">
			{if isset($prev)}<img class="previous" onmouseover="this.src='art/{if $prev.to_end}prev_to_end.png{else}previous_button.gif{/if}'" onmouseout="this.src='art/{if $prev.to_end}start_bookmark.png{else}previous_button.png{/if}'" title="{$prev.title}" onclick="window.location='{$prev.link}'" src="art/{if $prev.to_end}start_bookmark.png{else}previous_button.png{/if}" alt="{t}Previous{/t}" />{/if} <span class="main_title"><span class="id" id="product_code_title">{$product->get('Product Code')}</span> (<i>{$product->get('Product ID')})</i>, <span id="product_name_title">{$product->get('Product Name')}</span> </span> 
		</div>
		<div class="buttons">
			{if isset($next)}<img class="next" onmouseover="this.src='art/{if $next.to_end}prev_to_end.png{else}next_button.gif{/if}'" onmouseout="this.src='art/{if $next.to_end}prev_to_end.png{else}next_button.png{/if}'" title="{$next.title}" onclick="window.location='{$next.link}'" src="art/{if $next.to_end}prev_to_end.png{else}next_button.png{/if}" alt="{t}Next{/t}" />{/if} <button style="margin-left:0px" onclick="window.location='product.php?id={$product->id}'"><img src="art/icons/door_out.png" alt="" /> {t}Exit Edit{/t}</button> <button style="margin-left:0px" onclick="delete_product()"><img src="art/icons/delete.png" alt="" /> {t}Delete{/t}</button> 
		</div>
		<div style="clear:both">
		</div>
	</div>
	<ul class="tabs" id="chooser_ul">
		<li> <span class="item {if $edit=='description'}selected{/if}" id="description"> <span> {t}Product{/t}</span></span></li>
		<li> <span class="item {if $edit=='parts'}selected{/if}" id="parts"> <span>{t}Parts{/t}</span></span></li>
		<li> <span class="item {if $edit=='web'}selected{/if} " id="web"><span> {t}Web Pages{/t}</span></span></li>
	</ul>
	<div class="tabbed_container no_padding">
		<div style="clear:both;height:.1em;padding:0px 20px;;margin:0px auto;width:770px;" id="description_messages">
			<div style="float:right">
				<span class="save" style="display:none" id="description_save" onclick="save_description()">{t}Save{/t}</span> <span id="description_undo" style="display:none" class="undo" onclick="undo('description')">{t}Cancel{/t}</span> 
			</div>
			<span style="display:none">Number of changes:<span id="description_num_changes">0</span></span> 
			<div id="description_errors">
			</div>
			<div id="description_warnings">
			</div>
			<div style="clear:both">
			</div>
		</div>
		<div class="edit_block" style="{if $edit!='parts' }display:none{/if};" id="d_parts">
			<div class="edit_block_content">
				<table class="edit" style="width:100%" border="0" id="part_editor_table">
					<tr class="title">
						<td colspan="2">{t}Part List{/t}</td>
						<td colspan="2"> 
						<div class="buttons small" id="product_part_items" product_part_key="{$product->get_current_product_part_key()}">
							<button style="margin-right:0px;{if $product->get('Product Type')=='normal' and $num_parts!=0}xdisplay:none{/if}" id="add_part" class="state_details">{t}Add Part to List{/t}</button> <button class="positive" style="visibility:hidden" id="save_edit_part" onclick="save_part()" class="state_details">{t}Save{/t}</button> <button class="negative" style="visibility:hidden" id="reset_edit_part" onclick="reset_part()" class="state_details">{t}Cancel{/t}</button> 
						</div>
						</td>
					</tr>
					{foreach from=$product->get_current_part_list('smarty') key=sku item=part_list} 
					<tr id="part_list{$sku}" sku="{$sku}" class="top title">
						<td id="part_list{$sku}_label1" class="label" style="width:150px;font-weight:200">{t}Part{/t}</td>
						<td id="part_list{$sku}_label2" colspan="2" style="width:120px"><a href="part.php?sku={$part_list.part->sku}" class="id">{$part_list.part->get_sku()}</a> {$part_list.part->get('Part Unit Description')}</td>
						<td style="width:200px;text-align:right"> 
						<div id="part_list{$sku}_controls">
							<span onclick="remove_part({$sku})" style="cursor:pointer"><img src="art/icons/delete_bw.png" /> {t}Remove{/t}</span> <span onclick="show_change_part_dialog({$sku},this)" style="display:none;cursor:pointer;margin-left:15px"><img src="art/icons/arrow_refresh_bw.png" /> {t}Change{/t}</span> 
						</div>
						<div id="part_list{$sku}_controls2" style="display:none">
							<span onclick="unremove_part({$sku})" style="cursor:pointer"><img src="art/icons/arrow_rotate_clockwise.png" /> {t}Restore{/t}</span> 
						</div>
						</td>
					</tr>
					<tr id="sup_tr2_{$sku}">
						<td class="label">{t}Parts Per Product{/t}:</td>
						<td style="text-align:left;" colspan="3"> 
						<input style="padding-left:2px;text-align:left;width:70px" value="{$part_list.Parts_Per_Product}" onblur="part_changed(this)" onkeyup="part_changed(this)" ovalue="{$part_list.Parts_Per_Product}" id="parts_per_product{$sku}"> <span class="edit_td_alert" id="parts_per_product_msg{$sku}"></span></td>
					</tr>
					<tr id="sup_tr3_{$sku}" class="last">
						<td class="label">{t}Note For Pickers{/t}:</td>
						<td style="text-align:left" colspan="3"> 
						<input id="pickers_note{$sku}" style=";width:400px" onblur="part_changed(this)" onkeyup="part_changed(this)" value="{$part_list.Product_Part_List_Note}" ovalue="{$part_list.Product_Part_List_Note}"></td>
					</tr>
					{/foreach} 
				</table>
			</div>
		</div>
		<div class="edit_block" style="{if $edit!='web' }display:none{/if};" id="d_web">
			<div class="edit_block_content">
			</div>
		</div>
		<div class="edit_block" style="{if $edit!='description' }display:none{/if};" id="d_description">
			<div class="buttons small left tabs">
				<button class="item indented {if $edit_description_block=='family'}selected{/if}" id="description_block_family" block_id="family">{t}Family{/t}</button> <button class="item {if $edit_description_block=='type'}selected{/if}" id="description_block_type" block_id="type">{t}Sales Type{/t}</button> <button class="item {if $edit_description_block=='description'}selected{/if}" id="description_block_description" block_id="description">{t}Name, Codes{/t}</button> <button class="item {if $edit_description_block=='info'}selected{/if}" id="description_block_info" block_id="info">{t}Description{/t}</button> <button class="item {if $edit_description_block=='price'}selected{/if}" id="description_block_price" block_id="price">{t}Price, Discounts{/t}</button> <button class="item {if $edit_description_block=='properties'}selected{/if}" id="description_block_properties" block_id="properties">{t}Properties{/t}</button> <button class="item {if $edit_description_block=='health_and_safety'}selected{/if}" id="description_block_health_and_safety" block_id="health_and_safety">{t}Health & Safety{/t}</button> <button class="item {if $edit_description_block=='pictures'}selected{/if}" id="description_block_pictures" block_id="pictures">{t}Pictures{/t}</button> <button style="display:none" class="item {if $edit_description_block=='weight_dimension'}selected{/if}" id="description_block_weight_dimension" block_id="weight_dimension">{t}Weight/Dimensions{/t}</button> 
			</div>
			<div class="tabs_base">
			</div>
			<div class="edit_block_content" style="padding:0">
				<div id="d_description_block_family" style="{if $edit_description_block!='family'}display:none{/if};padding:20px">
											<input type="hidden" id="Product_Family_Key" value="{$product->get('Product Family Key')}" ovalue="{$product->get('Product Family Key')}" oformatedvalue="{$product->get('Product Family Code')}">
											

					<table class="edit" style="width:100%" border=0>
						<tr class="title">
							<td colspan="5">{t}Family{/t}</td>
						</tr>
						<tr class="first">
							<td style="width:180px" class="label">{t}Family{/t}:</td>
							<td style="text-align:left"> <span id="current_family_code">{$product->get('Product Family Code')}</span> <img id="edit_family" id="family" style="margin-left:5px;cursor:pointer" src="art/icons/edit.gif" alt="{t}Edit{/t}" title="{t}Edit{/t}" /s> </td>
							<td style="width:200px" id="Product_Family_Key_msg" class="edit_td_alert"></td>
						</tr>
						
						
						
						
						{foreach from=$categories item=cat key=cat_key name=foo } 
						<tr>
							<td class="label">{t}{$cat.name}{/t}:</td>
							<td> {foreach from=$cat.teeth item=cat2 key=cat2_id name=foo2} 
							<div id="cat_{$cat2_id}" default_cat="{$cat2.default_id}" class="options" style="margin:5px 0">
								{foreach from=$cat2.elements item=cat3 key=cat3_id name=foo3} <span class="catbox {if $cat3.selected}selected{/if}" value="{$cat3.selected}" ovalue="{$cat3.selected}" onclick="checkbox_changed(this)" cat_id="{$cat3_id}" id="cat{$cat3_id}" parent="{$cat3.parent}" position="{$cat3.position}" default="{$cat3.default}">{$cat3.name}</span> {/foreach} 
							</div>
							{/foreach} </td>
						</tr>
						{/foreach} 
						
						
						<tr class="buttons">
							<td></td>
							<td > 
							<div class="buttons" style="float:left">
								<button class="positive disabled" id="save_edit_product_family">{t}Save{/t}</button> 
								<button class="negative disabled" id="reset_edit_product_family">{t}Reset{/t}</button> 
							</div>
							</td>
						</tr>
						
					</table>
				</div>
				<div id="d_description_block_type" style="{if $edit_description_block!='type'}display:none{/if};padding:20px">
					<table class="edit" style="width:100%">
						<tr class="title">
							<td colspan="3">{t}Sales Type{/t}</td>
						</tr>
						<tr class="first">
							<td style="width:180px" class="label">{t}Product Type{/t}:</td>
							<td style="width:600px" class="buttons left small"> 
							<input type="hidden" id="Product_Sales_Type" value=""> 
							<div class="buttons" id="sales_type_options">
								<button id="product_sales_type_Public_Sale" class="{if $sales_type=='Public Sale'}selected{/if}" onclick="change_sales_type('Public Sale',  '{$sales_type}')">{t}Public Sale{/t}</button> <button id="product_sales_type_Private_Sale" class="{if $sales_type=='Private Sale'}selected{/if}" onclick="change_sales_type('Private Sale', '{$sales_type}')">{t}Private Sale{/t}</button> <button id="product_sales_type_Not_for_Sale_Sale" class="{if $sales_type=='Not for Sale'}selected{/if}" onclick="change_sales_type('Not For Sale', '{$sales_type}')">{t}Not For Sale{/t}</button> 
							</div>
							</td>
						</tr>
						<tr class="buttons">
							<td colspan="2"> 
							<div class="buttons" style="float:right">
								<button class="positive disabled" id="save_edit_product_sales_type">{t}Save{/t}</button> <button class="negative disabled" id="reset_edit_product_sales_type">{t}Reset{/t}</button> 
							</div>
							</td>
						</tr>
					</table>
				</div>
				<div id="d_description_block_description" style="{if $edit_description_block!='description' }display:none{/if};padding:20px">
					<table class="edit" style="width:100%" border="0">
						<tr class="title">
							<td colspan="3">{t}Units, code & name{/t}</td>
						</tr>
						<tr class="space10">
							<td style="width:180px" class="label">{t}Units Per Outer{/t}:</td>
							<td style="text-align:left"> 
							<div>
								<input style="text-align:left;width:70px" id="Product_Units_Per_Case" value="{$product->get('Product Units Per Case')}" ovalue="{$product->get('Product Units Per Case')}" valid="0"> 
								<div id="Product_Units_Per_Case_Container">
								</div>
							</div>
							</td>
							<td style="width:200px" id="Product_Units_Per_Case_msg" class="edit_td_alert"></td>
						</tr>
						<tr>
							<td style="width:180px" class="label">{t}Units Type{/t}:</td>
							<td style="text-align:left"> 
							<input type="hidden" id="Product_Unit_Type" value="{$unit_type}" ovalue="{$unit_type}" />
							<select id="Product_Unit_Type_Select" onchange="change_unit_type(this)">
								{foreach from=$unit_type_options key=value item=label} 
								<option label="{$label}" value="{$value}" selected='{if $value==$unit_type}selected{/if}'>{$label}</option>
								{/foreach} 
							</select>
							</td>
							<td id="Product_Unit_Type_msg" class="edit_td_alert"></td>
						</tr>
						<tr class="space5">
							<td style="width:180px" class="label">{t}Product Code{/t}:</td>
							<td style="text-align:left"> 
							<div>
								<input {if !$can_edit_code}disabled="disabled" {/if} style="text-align:left;width:140px" id="Product_Code" value="{$product->get('Product Code')|escape}" ovalue="{$product->get('Product Code')|escape}" valid="0"> {if !$can_edit_code}<img src="art/icons/lock_warning_bw.png" alt="{t}Locked{/t}" style="position:relative;left:150px" title="{t}You can't change code{/t}">{/if} 
								<div id="Product_Code_Container">
								</div>
							</div>
							</td>
							<td style="width:200px" id="Product_Code_msg" class="edit_td_alert"></td>
						</tr>
						<tr class="space10">
							<td style="width:180px" class="label">{t}Product Name{/t}:</td>
							<td style="text-align:left"> 
							<div>
								<input style="text-align:left;" id="Product_Name" value="{$product->get('Product Name')|escape}" ovalue="{$product->get('Product Name')|escape}" valid="0"> 
								<div id="Product_Name_Container">
								</div>
							</div>
							</td>
							<td style="width:200px" id="Product_Name_msg" class="edit_td_alert"></td>
						</tr>
						<tr>
							<td style="width:180px" class="label">{t}Special Characteristic{/t}:</td>
							<td style="text-align:left"> 
							<div>
								<input style="text-align:left;width:250px" id="Product_Special_Characteristic" value="{$product->get('Product Special Characteristic')|escape}" ovalue="{$product->get('Product Special Characteristic')|escape}" valid="0"> 
								<div id="Product_Special_Characteristic_Container">
								</div>
							</div>
							</td>
							<td id="Product_Special_Characteristic_msg" class="edit_td_alert"></td>
						</tr>
						<tr class="title">
							<td colspan="3">{t}Barcode{/t}</td>
						</tr>
						<tr class="space10">
							<td style="width:200px" class="label">{t}Barcode Type{/t}:</td>
							<td style="text-align:left"> 
							<input type="hidden" id="Product_Barcode_Type" value="{$product->get('Product Barcode Type')}" ovalue="{$product->get('Product Barcode Type')}" />
							<div class="buttons left small" id="Product_Barcode_Type_options">
								<button id="Product_Barcode_Type_option_none" class="option {if $product->get('Product Barcode Type')=='none'}selected{/if}" onclick="change_barcode_type(this,'none')">{t}None{/t}</button> <button id="Product_Barcode_Type_option_ean8" class="option {if $product->get('Product Barcode Type')=='ean8'}selected{/if}" onclick="change_barcode_type(this,'ean8')">EAN-8</button> <button id="Product_Barcode_Type_option_ean13" class="option {if $product->get('Product Barcode Type')=='ean13'}selected{/if}" onclick="change_barcode_type(this,'ean13')">EAN-13</button> <button id="Product_Barcode_Type_option_code11" class="option {if $product->get('Product Barcode Type')=='code11'}selected{/if}" onclick="change_barcode_type(this,'code11')">Code 11</button> <button id="Product_Barcode_Type_option_code39" class="option {if $product->get('Product Barcode Type')=='code39'}selected{/if}" onclick="change_barcode_type(this,'code39')">Code 39</button> <button id="Product_Barcode_Type_option_code128" class="option {if $product->get('Product Barcode Type')=='code128'}selected{/if}" onclick="change_barcode_type(this,'code128')">Code 128</button> <button id="Product_Barcode_Type_option_codabar" class="option {if $product->get('Product Barcode Type')=='codabar'}selected{/if}" onclick="change_barcode_type(this,'codabar')">Codebar</button> 
							</div>
							</td>
							<td><span id="Product_Barcode_Type_msg" class="edit_td_alert" style=""></span></td>
						</tr>
						<tr class="space5" id="Product_Barcode_Data_Source_tr" style="{if $product->get('Product Barcode Type')=='none'}display:none{/if}">
							<td style="width:200px" class="label">{t}Barcode Data Source{/t}:</td>
							<td style="text-align:left"> 
							<input type="hidden" id="Product_Barcode_Data_Source" value="{$product->get('Product Barcode Data Source')}" ovalue="{$product->get('Product Barcode Data Source')}" />
							<div class="buttons left small" id="Product_Barcode_Data_Source_options">
								<button id="Product_Barcode_Data_Source_option_ID" class="option {if $product->get('Product Barcode Data Source')=='ID'}selected{/if}" onclick="change_barcode_data_source(this,'ID')">{t}ID{/t}</button> <button id="Product_Barcode_Data_Source_option_COde" class="option {if $product->get('Product Barcode Data Source')=='Code'}selected{/if}" onclick="change_barcode_data_source(this,'Code')">{t}Code{/t}</button> <button id="Product_Barcode_Data_Source_option_Other" class="option {if $product->get('Product Barcode Data Source')=='Other'}selected{/if}" onclick="change_barcode_data_source(this,'Other')">{t}Other{/t}</button> 
							</div>
							<span id="Product_Barcode_Data_Source_msg" class="edit_td_alert"></span> </td>
							<td></td>
						</tr>
						<tr id="Product_Barcode_Data_tr" style="{if $product->get('Product Barcode Data Source')!='Other'}display:none{/if}">
							<td style="width:200px" class="label">{t}Barcode Data{/t}:</td>
							<td style="text-align:left"> 
							<div>
								<input style="text-align:left;width:250px" id="Product_Barcode_Data" value="{$product->get('Product Barcode Data')}" ovalue="{$product->get('Product Barcode Data')}" valid="0"> 
								<div id="Product_Barcode_Data_Container">
								</div>
							</div>
							<span id="Product_Barcode_Data_msg" class="edit_td_alert" style="position:relative;left:260px"></span> </td>
							<td></td>
						</tr>
						<tr class="title space5">
							<td colspan="3" id="product_tariff_code_title_td">{t}Tariff Code{/t} <img id="unlock_product_tariff_code" src="art/icons/link_lock_bw.png" style="cursor:pointer;position:relative;bottom:1px;{if $product->get('Product Use Part Tariff Data')=='No'}display:none{/if}" alt="lock" title="{t}Using part data, click to unlock{/t}"> <img id="lock_product_tariff_code" src="art/icons/link_lock_open_bw.png" style="cursor:pointer;position:relative;bottom:1px;{if $product->get('Product Use Part Tariff Data')=='Yes'}display:none{/if}" alt="unlock" title="{t}Click to lock and use aprt data instead{/t}"> <img id="lock_product_tariff_code_wait" src="art/loading.gif" style="display:none;width:11px;position:relative;bottom:1px;"> <span style="font-weight:normal;font-size:80%;{if $product->get('Product Use Part Tariff Data')=='No'}display:none{/if}" id="product_tariff_code_part_link">{$product->get_xhtml_part_links('Product Use Part Tariff Data')}</span> <span id="product_tariff_code_msg"></span></td>
							</td>
						</tr>
						<tr class="space10">
							<td style="width:200px" class="label">{t}Commodity Code{/t}:</td>
							<td style="text-align:left"> 
							<div>
								<input {if $product->get('Product Use Part Tariff Data')=='Yes'}disabled="disabled"{/if} style="text-align:left;width:250px" id="Product_Tariff_Code" value="{$product->get('Product Tariff Code')}" ovalue="{$product->get('Product Tariff Code')}" valid="0"> 
								<div id="Product_Tariff_Code_Container">
								</div>
							</div>
							<span id="Product_Tariff_Code_msg" class="edit_td_alert" style="position:relative;left:260px"></span> </td>
							<td></td>
						</tr>
						<tr>
							<td style="width:200px" class="label">{t}Duty Rate{/t}:</td>
							<td style="text-align:left"> 
							<div>
								<input {if $product->get('Product Use Part Tariff Data')=='Yes'}disabled="disabled"{/if} style="text-align:left;width:250px" id="Product_Duty_Rate" value="{$product->get('Product Duty Rate')}" ovalue="{$product->get('Product Duty Rate')}" valid="0"> 
								<div id="Product_Duty_Rate_Container">
								</div>
							</div>
							<span id="Product_Duty_Rate_msg" class="edit_td_alert" style="position:relative;left:260px"></span> </td>
							<td></td>
						</tr>
						<tr class="buttons">
							<td colspan="2"> 
							<div class="buttons" style="float:right">
								<button class="positive disabled" id="save_edit_product_description">{t}Save{/t}</button> <button class="negative disabled" id="reset_edit_product_description">{t}Reset{/t}</button> 
							</div>
							</td>
						</tr>
					</table>
				</div>
				<div id="d_description_block_properties" style="{if $edit_description_block!='properties' }display:none{/if};padding:20px">
					<table class="edit" style="width:890px" border="0">
						<tr>
							<td colspan="3"> 
							<div id="product_properties_title_div" style="padding:5px 10px;border:1px dashed #ccc;margin-bottom:5px;width:200px">
								<img id="unlock_product_properties" src="art/icons/link_lock_bw.png" style="cursor:pointer;position:relative;bottom:1px;{if $product->get('Product Use Part Properties')=='No'}display:none{/if}" alt="lock" title="{t}Using part data, click to unlock{/t}" /> <img id="lock_product_properties" src="art/icons/link_lock_open_bw.png" style="cursor:pointer;position:relative;bottom:1px;{if $product->get('Product Use Part Properties')=='Yes'}display:none{/if}" alt="unlock" title="{t}Click to lock and use aprt data instead{/t}" /> <img id="lock_product_properties_wait" src="art/loading.gif" style="display:none;width:11px;position:relative;bottom:1px;" /> <span style="font-weight:normal;font-size:80%;{if $product->get('Product Use Part Properties')=='Yes'}display:none{/if}" id="product_properties_part_unlinked_msg"> {t}Physical properties unlinked{/t}</span> <span style="font-weight:normal;font-size:80%;{if $product->get('Product Use Part Properties')=='No'}display:none{/if}" id="product_properties_part_link">{$product->get_xhtml_part_links('Product Use Part Properties')}</span> <span id="product_properties_msg"></span></td>
							</div>
							</td>
						</tr>
						<tr class="title">
							<td colspan="3">{t}Outer{/t} <span style="font-size:80%">({t}including packing{/t})</span></td>
						</tr>
						<tr class="space5" id="Product_Package_Type_tr">
							<td style="width:200px" class="label">{t}Package Type{/t}:</td>
							<td style="text-align:left"> 
							<input type="hidden" id="Product_Package_Type" value="{$product->get('Product Package Type')}" ovalue="{$product->get('Product Package Type')}" />
							<div style="{if $product->get('Product Use Part Properties')=='Yes'}display:none{/if}" class="buttons left small" id="Product_Package_Type_options">
								<button id="Product_Package_Type_option_Box" class="option {if $product->get('Product Package Type')=='Box'}selected{/if}" onclick="change_package_type(this,'Box')">{t}Box{/t}</button> <button id="Product_Package_Type_option_Bottle" class="option {if $product->get('Product Package Type')=='Bottle'}selected{/if}" onclick="change_package_type(this,'Bottle')">{t}Bottle{/t}</button> <button id="Product_Package_Type_option_Bag" class="option {if $product->get('Product Package Type')=='Bag'}selected{/if}" onclick="change_package_type(this,'Bag')">{t}Bag{/t}</button> <button id="Product_Package_Type_option_None" class="option {if $product->get('Product Package Type')=='None'}selected{/if}" onclick="change_package_type(this,'None')">{t}None{/t}</button> <button id="Product_Package_Type_option_Other" class="option {if $product->get('Product Package Type')=='Other'}selected{/if}" onclick="change_package_type(this,'Other')">{t}Other{/t}</button> 
							</div>
							<input style="width:100px;{if $product->get('Product Use Part Properties')=='No'}display:none{/if}" disabled="disabled" id="Product_Package_Type_locked" value="{$product->get('Product Package Type')}"> <span id="Product_Package_Type_msg" class="edit_td_alert"></span> </td>
							<td></td>
						</tr>
						<tr class="space5">
							<td class="label">{t}Weight{/t}:</td>
							<td style="text-align:left;width:250px"> 
							<div>
								<input {if $product->get('Product Use Part Properties')=='Yes'}disabled="disabled"{/if} style="text-align:left;width:100px" id="Product_XHTML_Package_Weight" value="{$product->get('Product XHTML Package Weight')}" ovalue="{$product->get('Product XHTML Package Weight')}" valid="0"> 
								<div id="Product_XHTML_Package_Weight_Container">
								</div>
							</div>
							</td>
							<td id="Product_XHTML_Package_Weight_msg" class="edit_td_alert"></td>
						</tr>
						<tr>
							<td class="label">{t}Dimensions{/t}:<br />
							</td>
							<td style="text-align:left"> 
							<div>
								<input {if $product->get('Product Use Part Properties')=='Yes' and $product->get('Product Part Ratio')==1 }disabled="disabled"{/if} style="text-align:left;width:200px" id="Product_XHTML_Package_Dimensions" value="{$product->get('Product XHTML Package Dimensions')}" ovalue="{$product->get('Product XHTML Package Dimensions')}" valid="0"> 
								<div id="Product_XHTML_Package_Dimensions_Container">
								</div>
							</div>
							</td>
							<td id="Product_XHTML_Package_Dimensions_msg" class="edit_td_alert"></td>
						</tr>
						<tr class="title">
							<td colspan="3">{t}Unit{/t}</td>
						</tr>
						<tr class="space5">
							<td class="label">{t}Weight{/t}:</td>
							<td style="text-align:left"> 
							<div>
								<input {if $product->get('Product Use Part Properties')=='Yes'}disabled="disabled"{/if} style="text-align:left;width:100px" id="Product_XHTML_Unit_Weight" value="{$product->get('Product XHTML Unit Weight')}" ovalue="{$product->get('Product XHTML Unit Weight')}" valid="0"> 
								<div id="Product_XHTML_Unit_Weight_Container">
								</div>
							</div>
							</td>
							<td id="Product_XHTML_Unit_Weight_msg" class="edit_td_alert"></td>
						</tr>
						<tr>
							<td class="label">{t}Dimensions{/t}:</td>
							<td style="text-align:left;"> 
							<div>
								<input {if $product->get('Product Use Part Properties')=='Yes' and $product->get('Product Part Units Ratio')==1 }disabled="disabled"{/if} style="text-align:left;width:200px" id="Product_XHTML_Unit_Dimensions" value="{$product->get('Product XHTML Unit Dimensions')}" ovalue="{$product->get('Product XHTML Unit Dimensions')}" valid="0"> 
								<div id="Product_XHTML_Unit_Dimensions_Container">
								</div>
							</div>
							</td>
							<td id="Product_XHTML_Unit_Dimensions_msg" class="edit_td_alert"></td>
						</tr>
						<tr class="buttons" {if $product->
							get('Product Use Part Properties')=='Yes' and $product->get('Product Part Ratio')==1 and $product->get('Product Part Units Ratio')==1}display:none{/if} > 
							<td colspan="2"> 
							<div class="buttons" style="float:right">
								<button class="positive disabled" id="save_edit_product_properties">{t}Save{/t}</button> <button class="negative disabled" id="reset_edit_product_properties">{t}Reset{/t}</button> 
							</div>
							</td>
						</tr>
					</table>
				</div>
				<div id="d_description_block_price" style="{if $edit_description_block!='price' }display:none{/if};padding:20px">
					<input id="v_cost" value="{$product->get('Product Cost')}" type="hidden" />
					<table class="edit" border="0" style="width:900px;clear:both">
						<tr class="title">
							<td colspan="5">{t}Price{/t}</td>
						</tr>
						<tr class="first">
							<td class="label" style="width:150px">{t}Price per Outer{/t}:</td>
							<td style="text-align:left;width:150px"> 
							<div>
								<input style="text-align:left;width:100%" id="Product_Price" value="{$product->get('Price')}" ovalue="{$product->get('Price')}" valid="0"> 
								<div id="Product_Price_Container">
								</div>
							</div>
							</td>
							<td style="width:200px" id="price_per_unit" cost="{$product->get('Product Cost')}" old_price="{$product->get('Product Price')}" units="{$product->get('Product Units Per Case')}">{$product->get_formated_price_per_unit()}</td>
							<td style="width:200px" id="price_margin">{t}Margin{/t}: {$product->get('Margin')}</td>
							<td style="width:200px" id="Product_Price_msg" class="edit_td_alert"></td>
						</tr>
						<tr>
							<td class="label">{t}RRP per Unit{/t}:</td>
							<td style="text-align:left"> 
							<div>
								<input style="text-align:left;width:100%" id="Product_RRP" value="{$product->get('RRP')}" ovalue="{$product->get('RRP')}" valid="0"> 
								<div id="Product_RRP_Container">
								</div>
							</div>
							</td>
							<td></td>
							<td id="rrp_margin">{t}Margin{/t}: {$product->get('RRP Margin')}</td>
							<td style="width:200px" id="Product_RRP_msg" class="edit_td_alert"></td>
						</tr>
						<tr class="buttons">
							<td></td>
							<td colspan="1"> 
							<div class="buttons" style="float:right">
								<button class="positive disabled" id="save_edit_product_price">{t}Save{/t}</button> <button class="negative disabled" id="reset_edit_product_price">{t}Reset{/t}</button> 
							</div>
							</td>
						</tr>
					</table>
				</div>
				<div id="d_description_block_pictures" style="{if $edit_description_block!='pictures' }display:none{/if};padding:20px">
					<div style="padding:5px 10px;border:1px dashed #ccc;margin-bottom:5px;width:200px;float:right">
						<img id="unlock_product_tariff_code" src="art/icons/link.png" style="cursor:pointer;position:relative;bottom:-2px;{if $product->get('Product Use Part Pictures')=='No'}display:none{/if}" alt="lock" title="{t}Using part data, click to unlock{/t}"> <img id="lock_product_tariff_code" src="art/icons/link_break.png" style="cursor:pointer;position:relative;bottom:-2px;{if $product->get('Product Use Part Pictures')=='Yes'}display:none{/if}" alt="unlock" title="{t}Click to lock and use aprt data instead{/t}"> <img id="lock_product_tariff_code_wait" src="art/loading.gif" style="display:none;width:11px;position:relative;bottom:1px;"> <span style="font-weight:normal;font-size:80%;{if $product->get('Product Use Part Pictures')=='No'}display:none{/if}" id="product_tariff_code_part_link">{$product->get_xhtml_part_links('Product Use Part Pictures')}</span> <span id="product_tariff_code_msg"></span></td>
					</div>
					{include file='edit_images_splinter.tpl' parent=$product} 
				</div>
				<table id="d_description_block_health_and_safety" class="edit" border="0" style="width:890px;;{if $edit_description_block!='health_and_safety'}display:none{/if};padding:20px">
					<tr class="title">
						<td colspan="2" id="product_health_and_safety_title_td">{t}Health & Safety{/t} <img id="unlock_product_health_and_safety" src="art/icons/link_lock_bw.png" style="cursor:pointer;position:relative;bottom:1px;{if $product->get('Product Use Part H and S')=='No'}display:none{/if}" alt="lock" title="{t}Using part data, click to unlock{/t}"> <img id="lock_product_health_and_safety" src="art/icons/link_lock_open_bw.png" style="cursor:pointer;position:relative;bottom:1px;{if $product->get('Product Use Part H and S')=='Yes'}display:none{/if}" alt="unlock" title="{t}Click to lock and use aprt data instead{/t}"> <img id="lock_product_health_and_safety_wait" src="art/loading.gif" style="display:none;width:11px;position:relative;bottom:1px;"> <span style="font-weight:normal;font-size:80%;{if $product->get('Product Use Part H and S')=='No'}display:none{/if}" id="product_health_and_safety_part_link">{$product->get_xhtml_part_links('Product Use Part H and S')}</span> <span id="product_health_and_safety_msg"></span></td>
					</tr>
					<tr class="first">
						<td style="width:200px" class="label">{t}UN Number{/t}:</td>
						<td style="text-align:left"> 
						<div>
							<input id="Product_UN_Number" {if $product->get('Product Use Part H and S')=='Yes'}disabled="disabled"{/if} style="text-align:left;width:100px" value="{$product->get('Product UN Number')}" ovalue="{$product->get('Product UN Number')}" valid="0"> 
							<div id="Product_UN_Number_Container">
							</div>
						</div>
						<span id="Product_UN_Number_msg" class="edit_td_alert" style="position:relative;left:110px"></span> </td>
						<td></td>
					</tr>
					<tr>
						<td style="width:200px" class="label">{t}UN Number Class{/t}:</td>
						<td style="text-align:left"> 
						<div>
							<input {if $product->get('Product Use Part H and S')=='Yes'}disabled="disabled"{/if} style="text-align:left;width:100px" id="Product_UN_Class" value="{$product->get('Product UN Class')}" ovalue="{$product->get('Product UN Class')}" valid="0"> 
							<div id="Product_UN_Class_Container">
							</div>
						</div>
						<span id="Product_UN_Class_msg" class="edit_td_alert" style="position:relative;left:110px"></span> </td>
						<td></td>
					</tr>
					<tr class="space5" id="Product_Packing_Group_tr">
						<td style="width:200px" class="label">{t}Packing Group{/t}:</td>
						<td style="text-align:left"> 
						<input type="hidden" id="Product_Packing_Group" value="{$product->get('Product Packing Group')}" ovalue="{$product->get('Product Packing Group')}" />
						<div style="{if $product->get('Product Use Part H and S')=='Yes'}display:none{/if}" class="buttons left small" id="Product_Packing_Group_options">
							<button id="Product_Packing_Group_option_None" class="option {if $product->get('Product Packing Group')=='None'}selected{/if}" onclick="change_packing_group(this,'None')">{t}None{/t}</button> <button id="Product_Packing_Group_option_I" class="option {if $product->get('Product Packing Group')=='I'}selected{/if}" onclick="change_packing_group(this,'I')">I</button> <button id="Product_Packing_Group_option_II" class="option {if $product->get('Product Packing Group')=='II'}selected{/if}" onclick="change_packing_group(this,'II')">II</button> <button id="Product_Packing_Group_option_III" class="option {if $product->get('Product Packing Group')=='III'}selected{/if}" onclick="change_packing_group(this,'III')">III</button> 
						</div>
						<input style="width:100px;{if $product->get('Product Use Part H and S')=='No'}display:none{/if}" disabled="disabled" id="Product_Packing_Group_locked" value="{$product->get('Product Packing Group')}"> <span id="Product_Packing_Group_msg" class="edit_td_alert"></span> </td>
						<td></td>
					</tr>
					<tr>
						<td style="width:200px" class="label">{t}Proper Shipping Name{/t}:</td>
						<td style="text-align:left;width:450px"> 
						<div>
							<input {if $product->get('Product Use Part H and S')=='Yes'}disabled="disabled"{/if} style="text-align:left;width:100%" id="Product_Proper_Shipping_Name" value="{$product->get('Product Proper Shipping Name')}" ovalue="{$product->get('Product Proper Shipping Name')}" valid="0"> 
							<div id="Product_Proper_Shipping_Name_Container">
							</div>
						</div>
						<span id="Product_Proper_Shipping_Name_msg" class="edit_td_alert"></span> </td>
						<td></td>
					</tr>
					<tr>
						<td style="width:200px" class="label">{t}Hazard Indentification (HIN){/t}:</td>
						<td style="text-align:left"> 
						<div>
							<input {if $product->get('Product Use Part H and S')=='Yes'}disabled="disabled"{/if} style="text-align:left;width:100px" id="Product_Hazard_Indentification_Number" value="{$product->get('Product Hazard Indentification Number')}" ovalue="{$product->get('Product Hazard Indentification Number')}" valid="0"> 
							<div id="Product_Hazard_Indentification_Number_Container">
							</div>
						</div>
						<span id="Product_Hazard_Indentification_Number_msg" class="edit_td_alert" style="position:relative;left:260px"></span> </td>
						<td></td>
					</tr>
					<tbody id="product_health_and_safety_editor_tbody" style="{if $product->get('Product Use Part H and S')=='Yes'}display:none{/if}">
						<tr class="space10">
							<td style="width:200px" class="label">{t}More info{/t}:</td>
							<td> <span id="Product_Health_And_Safety_msg"></span> 
							<div class="buttons small left">
								<button id="show_product_health_and_safety_editor" style="{if $product->get('Product Health And Safety')!=''}display:none{/if}">{t}Show editor{/t}</button> 
							</div>
							</td>
						</tr>
						<tr style="{if $product->get('Product Health And Safety')==''}display:none{/if}" id="product_health_and_safety_editor_tr">
							<td colspan="3" style="padding:5px 0 0 0 "> 
							<form onsubmit="return false;">
<textarea id="Product_Health_And_Safety" ovalue="{$product->get('Product Health And Safety')|escape}" rows="20" cols="75">{$product->get('Product Health And Safety')|escape}</textarea> 
							</form>
							</td>
						</tr>
					</tbody>
					<tr id="edit_product_health_and_safety_buttons_tr" class="buttons" style="{if $product->get('Product Use Part H and S')=='Yes'}display:none{/if}">
						<td colspan="3"> 
						<div id="edit_product_health_and_safety_buttons" class="buttons left" style="margin-left:{if $product->get('Product Health And Safety')==''}400px{else}700px{/if}" ">
							<button id="reset_edit_product_health_and_safety" class="negative disabled">{t}Reset{/t}</button> <button id="save_edit_product_health_and_safety" class="positive disabled">{t}Save{/t}</button> 
						</div>
						</td>
					</tr>
				</table>
				<div id="d_description_block_info" style="{if $edit_description_block!='info' }display:none{/if};">
					<table class="edit" style="width:890px;padding:20px;margin-left:20px;margin-top:20px">
						<tr class="title space10">
							<td>{t}Product Description{/t} <span id="Product_Description_msg"></span></td>
							<td> 
							<div class="buttons small">
								<button style="margin-right:10px" id="save_edit_product_general_description" class="positive disabled">{t}Save{/t}</button> <button style="margin-right:10px" id="reset_edit_product_general_description" class="negative disabled">{t}Reset{/t}</button> 
							</div>
							</td>
						</tr>
						<tr>
						</tr>
					</table>
					<form onsubmit="return false;" style="position:relative;left:-3px">
<textarea id="Product_Description" ovalue="{$product->get('Product Description')|escape}" rows="20" cols="75">{$product->get('Product Description')|escape}</textarea> 
					</form>
				</div>
			</div>
		</div>
	</div>
	<div class="buttons small" style="margin-top:0">
		<button id="show_history" style="{if $show_history}display:none{/if};margin-right:0px" onclick="show_history()">{t}Show changelog{/t}</button> <button id="hide_history" style="{if !$show_history}display:none{/if};margin-right:0px" onclick="hide_history()">{t}Hide changelog{/t}</button> 
	</div>
	<div id="history_table" class="data_table" style="clear:both;{if !$show_history}display:none{/if}">
		<span class="clean_table_title">{t}Changelog{/t}</span> 
		<div class="table_top_bar space">
		</div>
		{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0 } 
		<div id="table0" class="data_table_container dtable btable">
		</div>
	</div>
</div>
<div id="dialog_family_list">
	<div class="splinter_cell" style="padding:10px 15px 10px 0;border:none">
		<div id="the_table" class="data_table">
			<span class="clean_table_title">{t}Family List{/t}</span> {include file='table_splinter.tpl' table_id=2 filter_name=$filter_name2 filter_value=$filter_value2} 
			<div id="table2" class="data_table_container dtable btable">
			</div>
		</div>
	</div>
</div>
<div id="dialog_link_health_and_safety" style="padding:20px 10px 10px 10px;">
	<h2 style="padding-top:0px">
		{t}Link Health & Safety data to part{/t} 
	</h2>
	<p>
		{t}The product Health & Safety fields will be replaced by the part ones{/t}.<br> {t}Would you like to proceed?{/t} 
	</p>
	<div style="display:none" id="locking_product_health_and_safety_wait">
		<img src="art/loading.gif" alt=""> {t}Linking data, wait please{/t} 
	</div>
	<div id="lock_product_health_and_safety_buttons" class="buttons">
		<button id="save_lock_product_health_and_safety" class="positive">{t}Yes, link it!{/t}</button> <button id="cancel_lock_product_health_and_safety" class="negative">{t}Close{/t}</button> 
	</div>
</div>
<div id="dialog_link_properties" style="padding:20px 10px 10px 10px;">
	<h2 style="padding-top:0px">
		{t}Link physical properties to part{/t} 
	</h2>
	<p>
		{t}The product export tariff fields will be replaced by the part ones{/t}.<br> {t}Would you like to proceed?{/t} 
	</p>
	<div style="display:none" id="locking_product_properties_wait">
		<img src="art/loading.gif" alt=""> {t}Linking data, wait please{/t} 
	</div>
	<div id="lock_product_properties_buttons" class="buttons">
		<button id="save_lock_product_properties" class="positive">{t}Yes, link it!{/t}</button> <button id="cancel_lock_product_properties" class="negative">{t}Close{/t}</button> 
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
{*} 
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
{*} 
<div id="rppmenu1" class="yuimenu">
	<div class="bd">
		<ul class="first-of-type">
			<li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Rows per Page{/t}:</li>
			{foreach from=$paginator_menu1 item=menu } 
			<li class="yuimenuitem"><a class="yuimenuitemlabel" onclick="change_rpp({$menu},1)"> {$menu}</a></li>
			{/foreach} 
		</ul>
	</div>
</div>
<div id="dialog_part_list">
	<div class="splinter_cell" style="padding:10px 15px 10px 0;border:none;width:650px">
		<div id="the_table" class="data_table">
			<span class="clean_table_title">{t}Parts{/t}</span> {include file='table_splinter.tpl' table_id=1 filter_name=$filter_name1 filter_value=$filter_value1} 
			<div id="table1" class="data_table_container dtable btable">
			</div>
		</div>
	</div>
</div>
{include file='footer.tpl'} 