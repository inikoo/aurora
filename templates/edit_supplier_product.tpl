{include file='header.tpl'} 
<div style="display:none; position:absolute; left:10px; top:200px; z-index:2" id="cal1Container">
</div>
<div id="bd">
	{include file='locations_navigation.tpl'} 
	<input type="hidden" id="supplier_product_pid" value="{$supplier_product->pid}"> 
	<input type="hidden" id="scope" value="supplier_product" />
	<input type="hidden" id="scope_key" value="{$supplier_product->pid}" />
	<input type="hidden" id="transactions_table_id" value="3"> 
	<input type="hidden" id="from" value="" />
	<input type="hidden" id="to" value="" />
	<div class="branch">
		<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a>&rarr; <a href="suppliers.php">{t}Suppliers{/t}</a> &rarr; <a id="supplier_branch_link" href="supplier.php?id={$supplier->id}" title="{$supplier->get('Supplier Name')}">{$supplier->get('Supplier Code')}</a> &rarr; <a href="edit_supplier_product.php?pid={$supplier_product->pid}" title="{$supplier_product->get('Supplier Product Name')}">{$supplier_product->get('Supplier Product Code')}</a> ({t}Editing{/t})</span> 
	</div>
	<div class="top_page_menu">
		<div class="buttons" style="float:right">
			{if isset($next_pid) }<img class="next" onmouseover="this.src='art/next_button.gif'" onmouseout="this.src='art/next_button.png'" title="{$next_pid.title}" onclick="window.location='{$next_pid.link}'" src="art/next_button.png" alt="{t}Next{/t}" />{/if} <button onclick="window.location='supplier_product.php?pid={$supplier_product->pid}'"><img src="art/icons/door_out.png" alt=""> {t}Exit Edit{/t}</button> 
		</div>
		<div class="buttons" style="float:left">
			{if isset($prev_pid)}<img style="vertical-align:bottom;float:none" class="previous" onmouseover="this.src='art/previous_button.gif'" onmouseout="this.src='art/previous_button.png'" title="{$prev_pid.title}" onclick="window.location='{$prev_pid.link}'" src="art/previous_button.png" alt="{t}Previous{/t}" />{/if} <span style="font-size:140%;width:600px;position:relative;bottom:-5px;left:-5px"> <span style="font-weight:800"><span class="id">{$supplier_product->get('Supplier Product Code')}</span></span> <span style="font-size:75%"> <span id="supplier_product_code_title" style="font-weight:800">{$supplier_product->get('Supplier Product Name')}</span> <span id="supplier_product_description_title"> {$supplier_product->get('Supplier Product Unit Description')}</span> </span> </span> 
		</div>
		<div style="clear:both">
		</div>
	</div>
	<div style="display:none;clear:left;margin:0 0px">
		<h1>
			<span style="padding:0;font-size:80%">{t}Sold as{/t}: {$supplier_product->get('Supplier Product XHTML Currently Used In')}</span> 
		</h1>
	</div>
	<ul class="tabs" id="chooser_ul">
		<li><span class="item {if $edit=='description'}selected{/if}" id="description"> <span> {t}Supplier Product{/t}</span></span></li>
		<li><span class="item {if $edit=='parts'}selected{/if}" id="parts"> <span>{t}Parts{/t}</span></span></li>
	</ul>
	<div class="tabbed_container no_padding">
		<div class="edit_block" style="{if $edit!='parts' }display:none{/if};" id="d_parts">
			<div class="edit_block_content">
				<span class="clean_table_title">{t}Current parts{/t}</span> 
				<div class="table_top_bar space">
				</div>
				{include file='table_splinter.tpl' table_id=2 filter_name=$filter_name2 filter_value=$filter_value2 } 
				<div id="table2" class="data_table_container dtable btable" style="font-size:85%">
				</div>
				<div id="historic_supplier_products" style="margin-top:20px;{if $supplier_product->get_number_historic_parts()==0}display:none{/if}">
					<span class="clean_table_title">{t}Historic parts{/t}</span> 
					<div class="table_top_bar space">
					</div>
					{include file='table_splinter.tpl' table_id=5 filter_name=$filter_name5 filter_value=$filter_value5 } 
					<div id="table5" class="data_table_container dtable btable" style="font-size:85%">
					</div>
				</div>
			</div>
		</div>
		<div class="edit_block" style="{if $edit!='description' }display:none{/if};" id="d_description">
			<div class="buttons small left tabs">
				<button class="indented item {if $description_block=='status'}selected{/if}" id="description_block_status" block_id="status">{t}Status{/t}</button> 
				<button class="item {if $description_block=='supplier'}selected{/if}" id="description_block_supplier" block_id="supplier">{t}Supplier{/t}</button> 
				<button class="item {if $description_block=='cost'}selected{/if}" id="description_block_cost" block_id="cost">{t}Units, Cost{/t}</button> 
				<button class="item {if $description_block=='description'}selected{/if}" id="description_block_description" block_id="description">{t}Name, Codes{/t}</button> 
				<button class="item {if $description_block=='info'}selected{/if}" id="description_block_info" block_id="info">{t}Description{/t}</button> <button class="item {if $description_block=='properties'}selected{/if}" id="description_block_properties" block_id="properties">{t}Properties{/t}</button> <button class="item {if $description_block=='health_and_safety'}selected{/if}" id="description_block_health_and_safety" block_id="health_and_safety">{t}Health & Safety{/t}</button> <button style="display:none" class="item {if $description_block=='weight_dimension'}selected{/if}" id="description_block_weight_dimension" block_id="weight_dimension">{t}Weight/Dimensions{/t}</button> <button class="item {if $description_block=='pictures'}selected{/if}" id="description_block_pictures" block_id="pictures">{t}Pictures{/t}</button> 
			</div>
			<div class="tabs_base">
			</div>
			<div class="edit_block_content">
			
			<table id="d_description_block_cost" border=0  class="edit" style="width:890px;{if $description_block!='cost'}display:none{/if}">
					
					<tr class="title">
						<td colspan="3">{t}Units{/t}</td>
					</tr>
					<tr class="space10">
							<td style="width:180px" class="label">{t}Units per outer{/t}:</td>
							<td style="text-align:left"> 
							<div>
								<input style="text-align:left;width:70px" id="Supplier_Product_Units_Per_Case" value="{$supplier_product->get('Supplier Product Units Per Case')}" ovalue="{$supplier_product->get('Supplier Product Units Per Case')}" valid="0"> 
								<div id="Supplier_Product_Units_Per_Case_Container">
								</div>
							</div>
							</td>
							<td style="width:200px" id="Supplier_Product_Units_Per_Case_msg" class="edit_td_alert"></td>
						</tr>
					<tr class="title">
						<td colspan="3">{t}Cost{/t}</td>
					</tr>
				
					<tr>
						<td style="width:200px" class="label">{t}Cost per outer{/t}:</td>
						<td style="text-align:left"> 
						<div>
							<input style="text-align:right;width:150px" id="Supplier_Product_Cost_Per_Case" value="{$supplier_product->get('Supplier Product Cost Per Case')}" ovalue="{$supplier_product->get('Supplier Product Cost Per Case')}" valid="0"> <span style="margin-left:160px">{$corporate_currency_symbol}</span>
							<div id="Supplier_Product_Cost_Per_Case_Container">
							</div>
						</div>
						<span id="Supplier_Product_Cost_Per_Case_msg" class="edit_td_alert" style="position:relative;left:260px"></span> </td>
						<td></td>
					</tr>
				
							<tr class="buttons">
						<td colspan="2"> 
						<div class="buttons left" style="margin-left:270px">
							<button id="reset_edit_supplier_product_cost" class="negative disabled">{t}Reset{/t}</button> 
							<button id="save_edit_supplier_product_cost" class="positive disabled">{t}Save{/t}</button> 
						</div>
						</td>
					</tr>
				</table>
			
				<table id="d_description_block_supplier" class="edit" style="width:800px;{if $description_block!='supplier'}display:none{/if}">
					<input type="hidden" id="Supplier_Product_Supplier_Key" value="{$supplier->id}" ovalue="{$supplier->id}" oformatedvalue="{$supplier->get('Supplier Code')}" oformatedvalue_bis="{$supplier->get('Supplier Name')}"> 
					<tr class="title">
						<td colspan="6">{t}Supplier{/t}</td>
					</tr>
					<tr class="first">
						<td style="width:100px" class="label">{t}Supplier{/t}:</td>
						<td style="text-align:left"> <span id="current_supplier_code">{$supplier->get('Supplier Code')}</span>, <span id="current_supplier_name">{$supplier->get('Supplier Name')}</span> <img id="edit_supplier_product_supplier" style="margin-left:5px;cursor:pointer" src="art/icons/edit.gif" alt="{t}Edit{/t}" title="{t}Edit{/t}"> </td>
						<td style="width:200px" id="Supplier_Product_Supplier_Key_msg" class="edit_td_alert"></td>
					</tr>
					<tr class="buttons">
						<td></td>
						<td> 
						<div class="buttons left">
							<button id="reset_edit_supplier_product_supplier" class="negative disabled">{t}Reset{/t}</button> <button id="save_edit_supplier_product_supplier" class="positive disabled">{t}Save{/t}</button> 
						</div>
						</td>
					</tr>
				</table>
	
	<table id="d_description_block_status" class="edit" style="width:800px;{if $description_block!='status'}display:none{/if}">
					<tr class="title">
						<td colspan="6">{t}Status{/t}</td>
					</tr>
					<tr>
						<td class="label" style="width:200px">{t}Supplier State{/t}:</td>
						<td> 
						<input type="hidden" id="Supplier_Product_State" value="{$supplier_product->get('Supplier Product State')}" ovalue="{$supplier_product->get('Supplier Product State')}" />
						<div class="buttons small" id="Supplier_Product_State_options">
							<button class="option {if $supplier_product->get('Supplier Product State')=='Discontinued'}selected{/if} " onclick="change_state('Discontinued')" id="Supplier_Product_State_Discontinued">{t}Discontinued{/t}</button> <button class="option {if $supplier_product->get('Supplier Product State')=='NoAvailable'}selected{/if} " onclick="change_state('NoAvailable')" id="Supplier_Product_State_NoAvailable">{t}Out of stock{/t}</button> <button class="option {if $supplier_product->get('Supplier Product State')=='Available'}selected{/if} " onclick="change_state('Available')" id="Supplier_Product_State_Available">{t}Available{/t}</button> 
						</div>
						</td>
						<td style="width:300px" id="Supplier_Product_State_msg"></td>
					</tr>
					<tr class="buttons">
						<td colspan="2"> 
						<div class="buttons">
							<button id="save_edit_supplier_product_state" class="positive disabled">{t}Save{/t}</button> <button id="reset_edit_supplier_product_state" class="negative disabled">{t}Reset{/t}</button> 
						</div>
						</td>
					</tr>
				</table>
				<table id="d_description_block_description" class="edit" style="width:890px;{if $description_block!='description'}display:none{/if}">
					
					
					
					
					<tr class="title">
						<td colspan="3">{t}Description{/t}</td>
					</tr>
					
					<tr>
						<td style="width:200px" class="label">{t}Code{/t}:</td>
						<td style="text-align:left"> 
						<div>
							<input style="text-align:left;width:250px" id="Supplier_Product_Code" value="{$supplier_product->get('Supplier Product Code')}" ovalue="{$supplier_product->get('Supplier Product Code')}" valid="0"> 
							<div id="Supplier_Product_Code_Container">
							</div>
						</div>
						<span id="Supplier_Product_Code_msg" class="edit_td_alert" style="position:relative;left:260px"></span> </td>
						<td></td>
					</tr>
					<tr>
						<td class="label">{t}Name{/t}:</td>
						<td style="text-align:left"> 
						<div>
							<input style="text-align:left;;width:500px" id="Supplier_Product_Name" value="{$supplier_product->get('Supplier Product Name')}" ovalue="{$supplier_product->get('Supplier Product Name')}" valid="0"> 
							<div id="Supplier_Product_Name_Container">
							</div>
						</div>
						<span id="Supplier_Product_Name_msg" class="edit_td_alert" style="position:relative;left:510px"></span> </td>
						<td></td>
					</tr>
					<tr>
						<td style="width:200px" class="label">{t}Commodity Code{/t}:</td>
						<td style="text-align:left"> 
						<div>
							<input style="text-align:left;width:250px" id="Supplier_Product_Tariff_Code" value="{$supplier_product->get('Supplier Product Tariff Code')}" ovalue="{$supplier_product->get('Supplier Product Tariff Code')}" valid="0"> 
							<div id="Supplier_Product_Tariff_Code_Container">
							</div>
						</div>
						<span id="Supplier_Product_Tariff_Code_msg" class="edit_td_alert" style="position:relative;left:260px"></span> </td>
						<td></td>
					</tr>
					<tr>
						<td style="width:200px" class="label">{t}Duty Rate{/t}:</td>
						<td style="text-align:left"> 
						<div>
							<input style="text-align:left;width:250px" id="Supplier_Product_Duty_Rate" value="{$supplier_product->get('Supplier Product Duty Rate')}" ovalue="{$supplier_product->get('Supplier Product Duty Rate')}" valid="0"> 
							<div id="Supplier_Product_Duty_Rate_Container">
							</div>
						</div>
						<span id="Supplier_Product_Duty_Rate_msg" class="edit_td_alert" style="position:relative;left:260px"></span> </td>
						<td></td>
					</tr>
					<tr class="title">
						<td colspan="3">{t}Origin{/t}</td>
					</tr>
					<tr>
						<td style="width:200px" class="label">{t}Origin{/t}:</td>
						<td style="text-align:left"> 
						<input type="hidden" id="Supplier_Product_Origin_Country_Code" value="{$supplier_product->get('Supplier Product Origin Country Code')}" ovalue="{$supplier_product->get('Supplier Product Origin Country Code')}" ovalue_formated="{$supplier_product->get('Origin Country Code')}" />
						<div class="buttons small left">
							<span style="float:left;margin-right:10px" id="Supplier_Product_Origin_Country_Code_formated">{$supplier_product->get('Origin Country Code')}</span> <button class="negative" style="{if $supplier_product->get('Supplier Product Origin Country Code')==''}display:none{/if}" id="delete_Supplier_Product_Origin_Country_Code" onclick="delete_origin_country_code()">{t}Remove{/t}</button> <button style="{if $supplier_product->get('Supplier Product Origin Country Code')==''}display:none{/if}" id="update_Supplier_Product_Origin_Country_Code">{t}Change Origin{/t}</button> <button style="{if $supplier_product->get('Supplier Product Origin Country Code')!=''}display:none{/if}" id="set_Supplier_Product_Origin_Country_Code">{t}Set Origin{/t}</button> 
						</div>
						<span id="Supplier_Product_Origin_Country_Code_msg" class="edit_td_alert" style="position:relative;left:260px"></span> </td>
						<td></td>
					</tr>
						<tr class="title">
						<td colspan="3">{t}Bar Code{/t}</td>
					</tr>
					<tr class="space10">
						<td style="width:200px" class="label">{t}Barcode Type{/t}:</td>
						<td style="text-align:left"> 
						<input type="hidden" id="Supplier_Product_Barcode_Type" value="{$supplier_product->get('Supplier Product Barcode Type')}" ovalue="{$supplier_product->get('Supplier Product Barcode Type')}" />
						<div class="buttons left small" id="Supplier_Product_Barcode_Type_options">
							<button id="Supplier_Product_Barcode_Type_option_none" class="option {if $supplier_product->get('Supplier Product Barcode Type')=='none'}selected{/if}" onclick="change_barcode_type(this,'none')">{t}None{/t}</button> <button id="Supplier_Product_Barcode_Type_option_ean8" class="option {if $supplier_product->get('Supplier Product Barcode Type')=='ean8'}selected{/if}" onclick="change_barcode_type(this,'ean8')">EAN-8</button> <button id="Supplier_Product_Barcode_Type_option_ean13" class="option {if $supplier_product->get('Supplier Product Barcode Type')=='ean13'}selected{/if}" onclick="change_barcode_type(this,'ean13')">EAN-13</button> <button id="Supplier_Product_Barcode_Type_option_code11" class="option {if $supplier_product->get('Supplier Product Barcode Type')=='code11'}selected{/if}" onclick="change_barcode_type(this,'code11')">Code 11</button> <button id="Supplier_Product_Barcode_Type_option_code39" class="option {if $supplier_product->get('Supplier Product Barcode Type')=='code39'}selected{/if}" onclick="change_barcode_type(this,'code39')">Code 39</button> <button id="Supplier_Product_Barcode_Type_option_code128" class="option {if $supplier_product->get('Supplier Product Barcode Type')=='code128'}selected{/if}" onclick="change_barcode_type(this,'code128')">Code 128</button> <button id="Supplier_Product_Barcode_Type_option_codabar" class="option {if $supplier_product->get('Supplier Product Barcode Type')=='codabar'}selected{/if}" onclick="change_barcode_type(this,'codabar')">Codebar</button> 
						</div>
						<span id="Supplier_Product_Barcode_Type_msg" class="edit_td_alert" style=""></span> </td>
						<td></td>
					</tr>
					<tr class="space5" id="Supplier_Product_Barcode_Data_Source_tr">
						<td style="width:200px" class="label">{t}Barcode Data Source{/t}:</td>
						<td style="text-align:left"> 
						<input type="hidden" id="Supplier_Product_Barcode_Data_Source" value="{$supplier_product->get('Supplier Product Barcode Data Source')}" ovalue="{$supplier_product->get('Supplier Product Barcode Data Source')}" />
						<div class="buttons left small" id="Supplier_Product_Barcode_Data_Source_options">
							<button id="Supplier_Product_Barcode_Data_Source_option_Code" class="option {if $supplier_product->get('Supplier Product Barcode Data Source')=='Code'}selected{/if}" onclick="change_barcode_data_source(this,'Code')">{t}Code{/t}</button> <button id="Supplier_Product_Barcode_Data_Source_option_Other" class="option {if $supplier_product->get('Supplier Product Barcode Data Source')=='Other'}selected{/if}" onclick="change_barcode_data_source(this,'Other')">{t}Other{/t}</button> 
						</div>
						<span id="Supplier_Product_Barcode_Data_Source_msg" class="edit_td_alert"></span> </td>
						<td></td>
					</tr>
					<tr id="Supplier_Product_Barcode_Data_tr" style="{if $supplier_product->get('Supplier Product Barcode Data Source')!='Other'}display:none{/if}">
						<td style="width:200px" class="label">{t}Barcode Data{/t}:</td>
						<td style="text-align:left"> 
						<div>
							<input style="text-align:left;width:250px" id="Supplier_Product_Barcode_Data" value="{$supplier_product->get('Supplier Product Barcode Data')}" ovalue="{$supplier_product->get('Supplier Product Barcode Data')}" valid="0"> 
							<div id="Supplier_Product_Barcode_Data_Container">
							</div>
						</div>
						<span id="Supplier_Product_Barcode_Data_msg" class="edit_td_alert" style="position:relative;left:260px"></span> </td>
						<td></td>
					</tr>
					<tr class="buttons">
						<td colspan="2"> 
						<div class="buttons" style="margin-right:360px">
							<button id="save_edit_supplier_product_unit" class="positive disabled">{t}Save{/t}</button> <button id="reset_edit_supplier_product_unit" class="negative disabled">{t}Reset{/t}</button> 
						</div>
						</td>
					</tr>
				</table>
				<table id="d_description_block_properties" class="edit" style="width:890px;{if $description_block!='properties'}display:none{/if}">
					<tr class="title">
						<td colspan="3">{t}Outer{/t} <span style="font-size:80%">({t}including packing{/t})</span></td>
					</tr>
					<tr class="space5" id="Supplier_Product_Package_Type_tr">
						<td style="width:200px" class="label">{t}Package Type{/t}:</td>
						<td style="text-align:left"> 
						<input type="hidden" id="Supplier_Product_Package_Type" value="{$supplier_product->get('Supplier Product Package Type')}" ovalue="{$supplier_product->get('Supplier Product Package Type')}" />
						<div class="buttons left small" id="Supplier_Product_Package_Type_options">
							<button id="Supplier_Product_Package_Type_option_Box" class="option {if $supplier_product->get('Supplier Product Package Type')=='Box'}selected{/if}" onclick="change_package_type(this,'Box')">{t}Box{/t}</button> <button id="Supplier_Product_Package_Type_option_Bottle" class="option {if $supplier_product->get('Supplier Product Package Type')=='Bottle'}selected{/if}" onclick="change_package_type(this,'Bottle')">{t}Bottle{/t}</button> <button id="Supplier_Product_Package_Type_option_Bag" class="option {if $supplier_product->get('Supplier Product Package Type')=='Bag'}selected{/if}" onclick="change_package_type(this,'Bag')">{t}Bag{/t}</button> <button id="Supplier_Product_Package_Type_option_None" class="option {if $supplier_product->get('Supplier Product Package Type')=='None'}selected{/if}" onclick="change_package_type(this,'None')">{t}None{/t}</button> <button id="Supplier_Product_Package_Type_option_Other" class="option {if $supplier_product->get('Supplier Product Package Type')=='Other'}selected{/if}" onclick="change_package_type(this,'Other')">{t}Other{/t}</button> 
						</div>
						<span id="Supplier_Product_Package_Type_msg" class="edit_td_alert"></span> </td>
						<td></td>
					</tr>
					<tbody id="Supplier_Product_Package_Weight_and_Dimensions_tbody">
						<tr class="space5">
							<td class="label">{t}Weight{/t}:</td>
							<td style="text-align:left;width:300px"> 
							<input type="hidden" id="Supplier_Product_Package_Weight_Display_Units" value="{$supplier_product->get('Supplier Product Package Weight Display Units')}" ovalue="{$supplier_product->get('Supplier Product Package Weight Display Units')}"> 
							<div>
								<input style="text-align:left;width:150px" id="Supplier_Product_Package_Weight_Display" value="{$supplier_product->get('Supplier Product Package Weight Display')}" ovalue="{$supplier_product->get('Supplier Product Package Weight Display')}" valid="0" />
								<div id="Supplier_Product_Package_Weight_Display_Container">
								</div>
							</div>
							<div class="buttons small left units" style="margin-left:155px;">
								<button id="Supplier_Product_Package_Weight_Display_Units_button" field="Supplier_Product_Package_Weight_Display_Units">&#x21b6 {$supplier_product->get('Supplier Product Package Weight Display Units')}</button> <span id="Supplier_Product_Package_Weight_Display_Units_msg" class="edit_td_alert"></span> 
							</div>
							</td>
							<td id="Supplier_Product_Package_Weight_Display_msg" class="edit_td_alert"></td>
						</tr>
						<tr class="space10">
							<td class="label"> {t}Form factor{/t}: </td>
							<td style="text-align:left"> 
							<input type="hidden" id="Supplier_Product_Package_Dimensions_Type" value="{$supplier_product->get('Supplier Product Package Dimensions Type')}" ovalue="{$supplier_product->get('Supplier Product Package Dimensions Type')}" />
							<div class="buttons left small" id="Supplier_Product_Package_Dimensions_Type_options">
								<button id="Supplier_Product_Package_Dimensions_Type_option_Rectangular" class="option {if $supplier_product->get('Supplier Product Package Dimensions Type')=='Rectangular'}selected{/if}" onclick="change_dimensions_shape_type(this,'Rectangular','Package')"><img src="art/icons/regtangular.png"> {t}Rectangular{/t}</button> <button id="Supplier_Product_Package_Dimensions_Type_option_Cilinder" class="option {if $supplier_product->get('Supplier Product Package Dimensions Type')=='Cilinder'}selected{/if}" onclick="change_dimensions_shape_type(this,'Cilinder','Package')"><img src="art/icons/database.png"> {t}Cilinder{/t}</button> <button id="Supplier_Product_Package_Dimensions_Type_option_Sphere" class="option {if $supplier_product->get('Supplier Product Package Dimensions Type')=='Sphere'}selected{/if}" onclick="change_dimensions_shape_type(this,'Sphere','Package')"><img src="art/icons/sport_golf.png" style="height:11px;width:11px;position:relative;bottom:-1px"> {t}Sphere{/t}</button> 
							</div>
							<img src="art/icons/run.png" style="display:none;height:12.9px;border:1px solid #ccc;padding:1px 3px;border-radius:3px;margin-left:10px;cursor:pointer" title="{t}Dimensions fast field{/t}"> <span id="Supplier_Product_Package_Dimensions_Type_msg" class="edit_td_alert"></span> </td>
							<td></td>
						</tr>
						<input id="Supplier_Product_Package_Dimensions_Display_Units" value="{$supplier_product->get('Supplier Product Package Dimensions Display Units')}" ovalue="{$supplier_product->get('Supplier Product Package Dimensions Display Units')}" type="hidden"> 
						<tr id="Supplier_Product_Package_Dimensions_Width_tr" style="{if $supplier_product->get('Supplier Product Package Dimensions Type')!='Rectangular' }display:none{/if}">
							<td class="label">{t}Width{/t}:</td>
							<td style="text-align:left;width:300px"> 
							<div>
								<input style="text-align:left;width:150px" id="Supplier_Product_Package_Dimensions_Width_Display" value="{$supplier_product->get('Supplier Product Package Dimensions Width Display')}" ovalue="{$supplier_product->get('Supplier Product Package Dimensions Width Display')}" valid="0"> 
								<div id="Supplier_Product_Package_Dimensions_Width_Display_Container">
								</div>
							</div>
							<div class="buttons small left units" style="margin-left:155px;">
								<button id="Supplier_Product_Package_Dimensions_Display_Units_Width" parent="Package" field="Supplier_Product_Package_Dimensions_Display_Units" style="{if $supplier_product->get('Supplier Product Package Dimensions Type')!='Rectangular'}display:none{/if}">&#x21b6 {$supplier_product->get('Supplier Product Package Dimensions Display Units')}</button> 
							</div>
							</td>
							<td id="Supplier_Product_Package_Dimensions_Width_Display_msg" class="edit_td_alert"></td>
						</tr>
						<tr id="Supplier_Product_Package_Dimensions_Depth_tr" style="{if $supplier_product->get('Supplier Product Package Dimensions Type')!='Rectangular'}display:none{/if}">
							<td class="label">{t}Depth{/t}:</td>
							<td style="text-align:left;width:300px"> 
							<div>
								<input style="text-align:left;width:150px" id="Supplier_Product_Package_Dimensions_Depth_Display" value="{$supplier_product->get('Supplier Product Package Dimensions Depth Display')}" ovalue="{$supplier_product->get('Supplier Product Package Dimensions Depth Display')}" valid="0"> 
								<div id="Supplier_Product_Package_Dimensions_Depth_Display_Container">
								</div>
							</div>
							</td>
							<td id="Supplier_Product_Package_Dimensions_Depth_Display_msg" class="edit_td_alert"></td>
						</tr>
						<tr id="Supplier_Product_Package_Dimensions_Length_tr" style="{if $supplier_product->get('Supplier Product Package Dimensions Type')=='Sphere'}display:none{/if}">
							<td class="label">{t}Length (High){/t}:</td>
							<td style="text-align:left;width:300px"> 
							<div>
								<input style="text-align:left;width:150px" id="Supplier_Product_Package_Dimensions_Length_Display" value="{$supplier_product->get('Supplier Product Package Dimensions Length Display')}" ovalue="{$supplier_product->get('Supplier Product Package Dimensions Length Display')}" valid="0"> 
								<div id="Supplier_Product_Package_Dimensions_Length_Display_Container">
								</div>
							</div>
							<div class="buttons small left units" style="margin-left:155px;">
								<button id="Supplier_Product_Package_Dimensions_Display_Units_Length" parent="Package" field="Supplier_Product_Package_Dimensions_Display_Units" style="{if $supplier_product->get('Supplier Product Package Dimensions Type')!='Cilinder'}display:none{/if}">&#x21b6 {$supplier_product->get('Supplier Product Package Dimensions Display Units')}</button> 
							</div>
							<span id="Supplier_Product_Package_Dimensions_Display_Units_msg" class="edit_td_alert"></span> </td>
							<td id="Supplier_Product_Package_Dimensions_Length_Display_msg" class="edit_td_alert"></td>
						</tr>
						<tr id="Supplier_Product_Package_Dimensions_Diameter_tr" style="{if $supplier_product->get('Supplier Product Package Dimensions Type')=='Rectangular'}display:none{/if}">
							<td class="label">{t}Diameter{/t}:</td>
							<td style="text-align:left;width:300px"> 
							<div>
								<input style="text-align:left;width:150px" id="Supplier_Product_Package_Dimensions_Diameter_Display" value="{$supplier_product->get('Supplier Product Package Dimensions Diameter Display')}" ovalue="{$supplier_product->get('Supplier Product Package Dimensions Diameter Display')}" valid="0"> 
								<div id="Supplier_Product_Package_Dimensions_Diameter_Display_Container">
								</div>
							</div>
							<div class="buttons small left units" style="margin-left:155px;">
								<button id="Supplier_Product_Package_Dimensions_Display_Units_Diameter" parent="Package" field="Supplier_Product_Package_Dimensions_Display_Units" style="{if $supplier_product->get('Supplier Product Package Dimensions Type')!='Sphere'}display:none{/if}">&#x21b6 {$supplier_product->get('Supplier Product Package Dimensions Display Units')}</button> 
							</div>
							</td>
							<td id="Supplier_Product_Package_Dimensions_Diameter_Display_msg" class="edit_td_alert"></td>
						</tr>
					</tbody>
					<tr class="title">
						<td colspan="3">{t}Individual Item{/t}</td>
					</tr>
					<tbody id="Supplier_Product_Unit_Weight_and_Dimensions_tbody">
						<tr class="space5">
							<td class="label">{t}Weight{/t}:</td>
							<td style="text-align:left;width:300px"> 
							<div>
								<input style="text-align:left;width:150px" id="Supplier_Product_Unit_Weight_Display" value="{$supplier_product->get('Supplier Product Unit Weight Display')}" ovalue="{$supplier_product->get('Supplier Product Unit Weight Display')}" valid="0" />
								<div id="Supplier_Product_Unit_Weight_Display_Container">
								</div>
							</div>
							<input type="hidden" id="Supplier_Product_Unit_Weight_Display_Units" value="{$supplier_product->get('Supplier Product Unit Weight Display Units')}" ovalue="{$supplier_product->get('Supplier Product Unit Weight Display Units')}"> 
							<div class="buttons small left units" style="margin-left:155px;">
								<button style="height:17px" id="Supplier_Product_Unit_Weight_Display_Units_button" field="Supplier_Product_Unit_Weight_Display_Units">&#x21b6 {$supplier_product->get('Supplier Product Unit Weight Display Units')}</button> <span id="Supplier_Product_Unit_Weight_Display_Units_msg" class="edit_td_alert"></span> 
							</div>
							</td>
							<td id="Supplier_Product_Unit_Weight_Display_msg" class="edit_td_alert"></td>
						</tr>
						<tr class="space10">
							<td class="label"> {t}Form factor{/t}: </td>
							<td style="text-align:left" colspan="2"> 
							<input type="hidden" id="Supplier_Product_Unit_Dimensions_Type" value="{$supplier_product->get('Supplier Product Unit Dimensions Type')}" ovalue="{$supplier_product->get('Supplier Product Unit Dimensions Type')}" />
							<div class="buttons left small" id="Supplier_Product_Unit_Dimensions_Type_options">
								<button id="Supplier_Product_Unit_Dimensions_Type_option_Rectangular" class="option {if $supplier_product->get('Supplier Product Unit Dimensions Type')=='Rectangular'}selected{/if}" onclick="change_dimensions_shape_type(this,'Rectangular','Unit')"><img src="art/icons/regtangular.png"> {t}Rectangular{/t}</button> <button id="Supplier_Product_Unit_Dimensions_Type_option_Cilinder" class="option {if $supplier_product->get('Supplier Product Unit Dimensions Type')=='Cilinder'}selected{/if}" onclick="change_dimensions_shape_type(this,'Cilinder','Unit')"><img src="art/icons/database.png"> {t}Cilinder{/t}</button> <button id="Supplier_Product_Unit_Dimensions_Type_option_Sphere" class="option {if $supplier_product->get('Supplier Product Unit Dimensions Type')=='Sphere'}selected{/if}" onclick="change_dimensions_shape_type(this,'Sphere','Unit')"><img src="art/icons/sport_golf.png" style="height:11px;width:11px;position:relative;bottom:-1px"> {t}Sphere{/t}</button> <button id="Supplier_Product_Unit_Dimensions_Type_option_String" class="option {if $supplier_product->get('Supplier Product Unit Dimensions Type')=='String'}selected{/if}" onclick="change_dimensions_shape_type(this,'String','Unit')"><img src="art/icons/string.png" style="height:11px;width:11px;position:relative;bottom:-1px"> {t}String{/t}</button> <button id="Supplier_Product_Unit_Dimensions_Type_option_Sheet" class="option {if $supplier_product->get('Supplier Product Unit Dimensions Type')=='Sheet'}selected{/if}" onclick="change_dimensions_shape_type(this,'Sheet','Unit')"><img src="art/icons/sheet.png" style="height:11px;width:11px;position:relative;bottom:-1px"> {t}Sheet{/t}</button> 
							</div>
							<img src="art/icons/run.png" style="display:none;height:12.9px;border:1px solid #ccc;padding:1px 3px;border-radius:3px;margin-left:10px;cursor:pointer" title="{t}Dimensions fast field{/t}"> <span id="Supplier_Product_Unit_Dimensions_Type_msg" class="edit_td_alert"></span> </td>
							<td></td>
						</tr>
						<input id="Supplier_Product_Unit_Dimensions_Display_Units" value="{$supplier_product->get('Supplier Product Unit Dimensions Display Units')}" ovalue="{$supplier_product->get('Supplier Product Unit Dimensions Display Units')}" type="hidden"> 
						<tr id="Supplier_Product_Unit_Dimensions_Width_tr" style="{if $supplier_product->get('Supplier Product Unit Dimensions Type')!='Rectangular' and  $supplier_product->get('Supplier Product Unit Dimensions Type')!='Sheet' }display:none{/if}">
							<td class="label">{t}Width{/t}:</td>
							<td style="text-align:left;width:300px"> 
							<div>
								<input style="text-align:left;width:150px" id="Supplier_Product_Unit_Dimensions_Width_Display" value="{$supplier_product->get('Supplier Product Unit Dimensions Width Display')}" ovalue="{$supplier_product->get('Supplier Product Unit Dimensions Width Display')}" valid="0"> 
								<div id="Supplier_Product_Unit_Dimensions_Width_Display_Container">
								</div>
							</div>
							<div class="buttons small left units" style="margin-left:155px;">
								<button id="Supplier_Product_Unit_Dimensions_Display_Units_Width" parent="Unit" field="Supplier_Product_Unit_Dimensions_Display_Units" style="{if $supplier_product->get('Supplier Product Unit Dimensions Type')!='Rectangular' and  $supplier_product->get('Supplier Product Unit Dimensions Type')!='Sheet'}display:none{/if}">&#x21b6 {$supplier_product->get('Supplier Product Unit Dimensions Display Units')}</button> <span id="Supplier_Product_Unit_Dimensions_Display_Units_msg"></span> 
							</div>
							</td>
							<td id="Supplier_Product_Unit_Dimensions_Width_Display_msg" class="edit_td_alert"></td>
						</tr>
						<tr id="Supplier_Product_Unit_Dimensions_Depth_tr" style="{if $supplier_product->get('Supplier Product Unit Dimensions Type')!='Rectangular' }display:none{/if}">
							<td class="label">{t}Depth{/t}:</td>
							<td style="text-align:left;width:300px"> 
							<div>
								<input style="text-align:left;width:150px" id="Supplier_Product_Unit_Dimensions_Depth_Display" value="{$supplier_product->get('Supplier Product Unit Dimensions Depth Display')}" ovalue="{$supplier_product->get('Supplier Product Unit Dimensions Depth Display')}" valid="0"> 
								<div id="Supplier_Product_Unit_Dimensions_Depth_Display_Container">
								</div>
							</div>
							</td>
							<td id="Supplier_Product_Unit_Dimensions_Depth_Display_msg" class="edit_td_alert"></td>
						</tr>
						<tr id="Supplier_Product_Unit_Dimensions_Length_tr" style="{if $supplier_product->get('Supplier Product Unit Dimensions Type')=='Sphere' }display:none{/if}">
							<td class="label">{t}Length (High){/t}:</td>
							<td style="text-align:left;width:300px"> 
							<div>
								<input style="text-align:left;width:150px" id="Supplier_Product_Unit_Dimensions_Length_Display" value="{$supplier_product->get('Supplier Product Unit Dimensions Length Display')}" ovalue="{$supplier_product->get('Supplier Product Unit Dimensions Length Display')}" valid="0"> 
								<div id="Supplier_Product_Unit_Dimensions_Length_Display_Container">
								</div>
							</div>
							<div class="buttons small left units" style="margin-left:155px;">
								<button id="Supplier_Product_Unit_Dimensions_Display_Units_Length" parent="Unit" field="Supplier_Product_Unit_Dimensions_Display_Units" style="{if $supplier_product->get('Supplier Product Unit Dimensions Type')!='Cilinder' and  $supplier_product->get('Supplier Product Unit Dimensions Type')!='String'}display:none{/if}">&#x21b6 {$supplier_product->get('Supplier Product Unit Dimensions Display Units')}</button> 
							</div>
							</td>
							<td id="Supplier_Product_Unit_Dimensions_Length_Display_msg" class="edit_td_alert"></td>
						</tr>
						<tr id="Supplier_Product_Unit_Dimensions_Diameter_tr" style="{if $supplier_product->get('Supplier Product Unit Dimensions Type')!='Sphere' and $supplier_product->get('Supplier Product Unit Dimensions Type')!='Cilinder' }display:none{/if}">
							<td class="label">{t}Diameter{/t}:</td>
							<td style="text-align:left;width:300px"> 
							<div>
								<input style="text-align:left;width:150px" id="Supplier_Product_Unit_Dimensions_Diameter_Display" value="{$supplier_product->get('Supplier Product Unit Dimensions Diameter Display')}" ovalue="{$supplier_product->get('Supplier Product Unit Dimensions Diameter Display')}" valid="0"> 
								<div id="Supplier_Product_Unit_Dimensions_Diameter_Display_Container">
								</div>
							</div>
							<div class="buttons small left units" style="margin-left:155px;">
								<button id="Supplier_Product_Unit_Dimensions_Display_Units_Diameter" parent="Unit" field="Supplier_Product_Unit_Dimensions_Display_Units" style="{if $supplier_product->get('Supplier Product Unit Dimensions Type')!='Sphere'}display:none{/if}">&#x21b6 {$supplier_product->get('Supplier Product Unit Dimensions Display Units')}</button> 
							</div>
							</td>
							<td id="Supplier_Product_Unit_Dimensions_Diameter_Display_msg" class="edit_td_alert"></td>
						</tr>
					</tbody>
					<tr class="buttons">
						<td colspan="2"> 
						<div class="buttons">
							<button id="save_edit_supplier_product_properties" class="positive disabled">{t}Save{/t}</button> <button id="reset_edit_supplier_product_properties" class="negative disabled">{t}Reset{/t}</button> 
						</div>
						</td>
					</tr>
				</table>
				<table id="d_description_block_info" class="edit" border="0" style="width:890px;{if $description_block!='info'}display:none{/if}">
					<tr class="title">
						<td>{t}Information{/t} <span id="supplier_product_general_description_msg"></span></td>
					</tr>
					<tr>
						<td style="padding:5px 0 0 0 "> 
						<form onsubmit="return false;">
<textarea id="supplier_product_general_description" ovalue="{$supplier_product->get('Supplier Product Description')|escape}" rows="20" cols="75">{$supplier_product->get('Supplier Product Description')|escape}</textarea> 
						</form>
						</td>
					</tr>
					<tr>
						<td> 
						<div class="buttons">
							<button id="save_edit_supplier_product_description" class="positive disabled">{t}Save{/t}</button> <button id="reset_edit_supplier_product_description" class="negative disabled">{t}Reset{/t}</button> 
						</div>
						</td>
					</tr>
				</table>
				<table id="d_description_block_health_and_safety" class="edit" border="0" style="width:890px;;{if $description_block!='health_and_safety'}display:none{/if}">
					<tr class="title">
						<td>{t}Attachments{/t}</td>
					</tr>
					<tr class="first">
						<td style="width:200px" class="label">{t}MSDS File{/t}:</td>
						<td colspan="2"> <span id="MSDS_File" style="float:left;margin-right:20px">{$supplier_product->get('Supplier Product MSDS Attachment XHTML Info')}</span> 
						<form id="upload_MSDS_File_form" style="{if $supplier_product->get('Supplier Product MSDS Attachment Bridge Key')}display:none{/if}" enctype="multipart/form-data" method="post">
							<input id="upload_MSDS_File_file" style="float:left;border:1px solid #ddd;position:relative;bottom:3px;margin-right:10px" type="file" name="attach" />
						</form>
						<div class="buttons small left" style="display:inline">
							<button id="delete_MSDS_File" style="{if !$supplier_product->get('Supplier Product MSDS Attachment Bridge Key')}display:none{/if}" class="negative">{t}Delete{/t}</button> <button id="replace_MSDS_File" style="{if !$supplier_product->get('Supplier Product MSDS Attachment Bridge Key')}display:none{/if}" class="">{t}Replace{/t}</button> <button id="cancel_replace_MSDS_File" style="display:none" class="negative">{t}Cancel{/t}</button> <button id="upload_MSDS_File_button" style="{if $supplier_product->get('Supplier Product MSDS Attachment Bridge Key')}display:none{/if}" class="disabled">{t}Upload{/t}</button> 
						</div>
						<span id="MSDS_File_msg" class="error"></span> </td>
					</tr>
					<tr class="title">
						<td>{t}Health & Safety{/t} <span id="supplier_product_health_and_safety_msg"></span></td>
					</tr>
					<tr class="space10">
						<td style="width:200px" class="label">{t}UN Number{/t}:</td>
						<td style="text-align:left"> 
						<div>
							<input style="text-align:left;width:100px" id="Supplier_Product_UN_Number" value="{$supplier_product->get('Supplier Product UN Number')}" ovalue="{$supplier_product->get('Supplier Product UN Number')}" valid="0"> 
							<div id="Supplier_Product_UN_Number_Container">
							</div>
						</div>
						<span id="Supplier_Product_UN_Number_msg" class="edit_td_alert" style="position:relative;left:260px"></span> </td>
						<td></td>
					</tr>
					<tr>
						<td style="width:200px" class="label">{t}UN Number Class{/t}:</td>
						<td style="text-align:left"> 
						<div>
							<input style="text-align:left;width:100px" id="Supplier_Product_UN_Number_Class" value="{$supplier_product->get('Supplier Product UN Class')}" ovalue="{$supplier_product->get('Supplier Product UN Class')}" valid="0"> 
							<div id="Supplier_Product_UN_Number_Class_Container">
							</div>
						</div>
						<span id="Supplier_Product_UN_Number_Class_msg" class="edit_td_alert" style="position:relative;left:260px"></span> </td>
						<td></td>
					</tr>
					<tr class="space5" id="Supplier_Product_Packing_Group_tr">
						<td style="width:200px" class="label">{t}Packing Group{/t}:</td>
						<td style="text-align:left"> 
						<input type="hidden" id="Supplier_Product_Packing_Group" value="{$supplier_product->get('Supplier Product Packing Group')}" ovalue="{$supplier_product->get('Supplier Product Packing Group')}" />
						<div class="buttons left small" id="Supplier_Product_Packing_Group_options">
							<button id="Supplier_Product_Packing_Group_option_None" class="option {if $supplier_product->get('Supplier Product Packing Group')=='None'}selected{/if}" onclick="change_packing_group(this,'None')">{t}None{/t}</button> <button id="Supplier_Product_Packing_Group_option_I" class="option {if $supplier_product->get('Supplier Product Packing Group')=='I'}selected{/if}" onclick="change_packing_group(this,'I')">I</button> <button id="Supplier_Product_Packing_Group_option_II" class="option {if $supplier_product->get('Supplier Product Packing Group')=='II'}selected{/if}" onclick="change_packing_group(this,'II')">II</button> <button id="Supplier_Product_Packing_Group_option_III" class="option {if $supplier_product->get('Supplier Product Packing Group')=='III'}selected{/if}" onclick="change_packing_group(this,'III')">III</button> 
						</div>
						<span id="Supplier_Product_Packing_Group_msg" class="edit_td_alert"></span> </td>
						<td></td>
					</tr>
					<tr>
						<td style="width:200px" class="label">{t}Proper Shipping Name{/t}:</td>
						<td style="text-align:left;width:450px"> 
						<div>
							<input style="text-align:left;width:100%" id="Supplier_Product_Proper_Shipping_Name" value="{$supplier_product->get('Supplier Product Proper Shipping Name')}" ovalue="{$supplier_product->get('Supplier Product Proper Shipping Name')}" valid="0"> 
							<div id="Supplier_Product_Proper_Shipping_Name_Container">
							</div>
						</div>
						<span id="Supplier_Product_Proper_Shipping_Name_msg" class="edit_td_alert"></span> </td>
						<td></td>
					</tr>
					<tr>
						<td style="width:200px" class="label">{t}Hazard Indentification (HIN){/t}:</td>
						<td style="text-align:left"> 
						<div>
							<input style="text-align:left;width:100px" id="Supplier_Product_Hazard_Indentification_Number" value="{$supplier_product->get('Supplier Product Hazard Indentification Number')}" ovalue="{$supplier_product->get('Supplier Product Hazard Indentification Number')}" valid="0"> 
							<div id="Supplier_Product_Hazard_Indentification_Number_Container">
							</div>
						</div>
						<span id="Supplier_Product_Hazard_Indentification_Number_msg" class="edit_td_alert" style="position:relative;left:260px"></span> </td>
						<td></td>
					</tr>
					<tr class="space10">
						<td style="width:200px" class="label">{t}More info{/t}:</td>
						<td> 
						<div class="buttons small left">
							<button id="show_supplier_product_health_and_safety_editor" style="{if $supplier_product->get('Supplier Product Health And Safety')!=''}display:none{/if}">{t}Show editor{/t}</button> 
						</div>
						</td>
					</tr>
					<tr style="{if $supplier_product->get('Supplier Product Health And Safety')==''}display:none{/if}" id="supplier_product_health_and_safety_editor_tr">
						<td colspan="3" style="padding:5px 0 0 0 "> 
						<form onsubmit="return false;">
<textarea id="supplier_product_health_and_safety" ovalue="{$supplier_product->get('Supplier Product Health And Safety')|escape}" rows="20" cols="75">{$supplier_product->get('Supplier Product Health And Safety')|escape}</textarea> 
						</form>
						</td>
					</tr>
					<tr class="buttons">
						<td colspan="3"> 
						<div id="edit_supplier_product_health_and_safety_buttons" class="buttons left" style="margin-left:{if $supplier_product->get('Supplier Product Health And Safety')==''}400px{else}700px{/if}">
							<button id="reset_edit_supplier_product_health_and_safety" class="negative disabled">{t}Reset{/t}</button> <button id="save_edit_supplier_product_health_and_safety" class="positive disabled">{t}Save{/t}</button> 
						</div>
						</td>
					</tr>
				</table>
				<table id="d_description_block_pictures" class="edit" border="0" style="width:890px;;{if $description_block!='pictures'}display:none{/if}">
					<tr class="title">
						<td>{t}Pictures{/t} <span id="supplier_product_pictures_msg"></span></td>
					</tr>
					<tr class="space10">
						<td> {include file='edit_images_splinter.tpl' parent=$supplier_product} </td>
					</tr>
				</table>
				{*} 
				<table class="edit">
					<tr class="title">
						<td colspan="5">{t}Categories{/t}</td>
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
				</table>
				<div class="buttons">
					<button style="margin-right:10px;" id="save_edit_supplier_product_custom_field" class="positive disabled">{t}Save{/t}</button> <button style="margin-right:10px;" id="reset_edit_supplier_product_custom_field" class="negative disabled">{t}Reset{/t}</button> 
				</div>
				<table class="edit">
					<tr class="title">
						<td colspan="5">{t}Custom Fields{/t}</td>
					</tr>
					{foreach from=$show_case key=custom_field_key item=custom_field_value } 
					<tr id="tr_{$custom_field_value.lable}">
						<td class="label">{$custom_field_key}:</td>
						<td style="text-align:left"> 
						<div>
							<input style="text-align:left;width:100%" id="Supplier_Product_{$custom_field_value.lable}" value="{$custom_field_value.value}" ovalue="{$custom_field_value.value}" valid="0"> 
							<div id="Supplier_Product_{$custom_field_value.lable}_Container">
							</div>
						</div>
						</td>
						<td> <span id="Supplier_Product_{$custom_field_value.lable}_msg" class="edit_td_alert"></span> </td>
					</tr>
					{/foreach} 
				</table>
				{*} 
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
		<div id="table0" class="data_table_container dtable btable history" style="font-size:85%">
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
<div id="filtermenu3" class="yuimenu">
	<div class="bd">
		<ul class="first-of-type">
			<li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Filter options{/t}:</li>
			{foreach from=$filter_menu3 item=menu } 
			<li class="yuimenuitem"><a class="yuimenuitemlabel" onclick="change_filter('{$menu.db_key}','{$menu.label}',3)"> {$menu.menu_label}</a></li>
			{/foreach} 
		</ul>
	</div>
</div>
<div id="rppmenu3" class="yuimenu">
	<div class="bd">
		<ul class="first-of-type">
			<li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Rows per Page{/t}:</li>
			{foreach from=$paginator_menu3 item=menu } 
			<li class="yuimenuitem"><a class="yuimenuitemlabel" onclick="change_rpp({$menu},3)"> {$menu}</a></li>
			{/foreach} 
		</ul>
	</div>
</div>
<div id="filtermenu4" class="yuimenu">
	<div class="bd">
		<ul class="first-of-type">
			<li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Filter options{/t}:</li>
			{foreach from=$filter_menu4 item=menu } 
			<li class="yuimenuitem"><a class="yuimenuitemlabel" onclick="change_filter('{$menu.db_key}','{$menu.label}',4)"> {$menu.menu_label}</a></li>
			{/foreach} 
		</ul>
	</div>
</div>
<div id="rppmenu4" class="yuimenu">
	<div class="bd">
		<ul class="first-of-type">
			<li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Rows per Page{/t}:</li>
			{foreach from=$paginator_menu4 item=menu } 
			<li class="yuimenuitem"><a class="yuimenuitemlabel" onclick="change_rpp({$menu},4)"> {$menu}</a></li>
			{/foreach} 
		</ul>
	</div>
</div>
<div id="dialog_delete_transaction" style="display:none;border:1px solid #ccc;text-align:left;padding:10px;">
	<div id="delete_transaction_msg">
	</div>
	<table style="margin:10px" border="0">
		<tr>
			<td style="padding-top:10px">{t}Are you sure you want to delet this transaction{/t}:</td>
		</tr>
		<tr>
			<td> 
			<div class="buttons">
				<img id="save_delete_transaction_wait" style="display:none;position:relative;left:20px" src="art/loading.gif" alt="" /> <button id="save_delete_transaction" class="positive">{t}Yes delete it{/t}</button> <button id="cancel_delete_transaction" class="negative">{t}No{/t}</button> 
			</div>
			</td>
		</tr>
	</table>
</div>
<div id="dialog_delete_MSDS_File" style="padding:10px 10px 10px 10px;">
	<h2 style="padding-top:0px">
		{t}Delete File{/t} 
	</h2>
	<p>
		{t}This operation cannot be undone{/t}.<br> {t}Would you like to proceed?{/t} 
	</p>
	<div style="display:none" id="deleting">
		<img src="art/loading.gif" alt=""> {t}Deleting file, wait please{/t} 
	</div>
	<div class="buttons">
		<button id="save_delete_MSDS_File" class="positive">{t}Yes, delete it!{/t}</button> <button id="cancel_delete_MSDS_File" class="negative">{t}No i dont want to delete it{/t}</button> 
	</div>
</div>
<div id="dialog_change_weight_units" style="padding:10px 20px 0px 10px">
	<input type="hidden" id="change_weight_units_field" value=""> 
	<input type="hidden" id="change_weight_units_field_parent" value=""> 
	<input type="hidden" id="change_weight_units_id" value=""> 
	<table class="edit" border="0" style="width:100px">
		{foreach from=$weight_units item=unit } 
		<tr>
			<td> 
			<div class="buttons small">
				<button style="float:none;margin:0px auto;min-width:60px" onclick="change_units('{$unit}','weight')"> {$unit}</button> 
			</div>
			</td>
		</tr>
		{/foreach} 
	</table>
</div>
<div id="dialog_change_lenght_units" style="padding:10px 20px 0px 10px">
	<input type="hidden" id="change_lenght_units_field" value=""> 
	<input type="hidden" id="change_lenght_units_field_parent" value=""> 
	<input type="hidden" id="change_lenght_units_id" value=""> 
	<table class="edit" border="0" style="width:100px">
		{foreach from=$lenght_units item=unit } 
		<tr>
			<td> 
			<div class="buttons small">
				<button style="float:none;margin:0px auto;min-width:60px" onclick="change_units('{$unit}','lenght')"> {$unit}</button> 
			</div>
			</td>
		</tr>
		{/foreach} 
	</table>
</div>
<div id="dialog_supplier_list">
	<div class="splinter_cell" style="padding:10px 15px 10px 0;border:none">
		<div class="data_table">
			<span class="clean_table_title">{t}Supplier List{/t}</span> {include file='table_splinter.tpl' table_id=3 filter_name=$filter_name3 filter_value=$filter_value3} 
			<div id="table3" class="data_table_container dtable btable">
			</div>
		</div>
	</div>
</div>
<div id="dialog_country_list">
	<div class="splinter_cell" style="padding:10px 15px 10px 0;border:none">
		<div class="data_table">
			<span class="clean_table_title">{t}Country List{/t}</span> {include file='table_splinter.tpl' table_id=4 filter_name=$filter_name4 filter_value=$filter_value4} 
			<div id="table4" class="data_table_container dtable btable">
			</div>
		</div>
	</div>
</div>
<div id="dialog_edit_supplier_product_availability" style="padding:20px 20px 5px 20px">
	<table>
		<tr>
			<td> 
			<input id="edit_supplier_product_availability_spp_key" value="" type="hidden"> 
			<input id="edit_supplier_product_availability_table_record_index" value="" type="hidden"> 
			<input id="edit_supplier_product_availability_table_id" value="" type="hidden"> 
			<div id="supplier_product_availability_operations" class="buttons small">
				<button class="buttons" onclick="save_supplier_product_availability('Unlink')" id="supplier_product_availability_Unlink"><img src="art/icons/link_break.png"> {t}Remove link{/t}</button> <button class="buttons selected" onclick="save_supplier_product_availability('Link')" id="supplier_product_availability_Link"><img src="art/icons/link.png"> {t}Linked to part{/t}</button> 
			</div>
			</td>
		</tr>
	</table>
</div>
{include file='footer.tpl'} 