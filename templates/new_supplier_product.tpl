{include file='header.tpl'} 
<div id="bd" class="no_padding">
<input type="hidden" value="{$session_data}" id="session_data" />
	<input type="hidden" id="supplier_key" value="{$supplier->id}" />


	{include file='suppliers_navigation.tpl'} 
	<div style="padding:0px 20px;">
		<div class="branch">
			<span > <a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a>&rarr; <a href="suppliers.php">{t}Suppliers{/t}</a> &rarr; <a href="supplier.php?id={$supplier->id}">{$supplier->get('Supplier Code')}</a> &rarr; {t}New supplier product{/t}</span> 
		</div>
		<div class="top_page_menu">
			<div class="buttons" style="float:left">
				<span class="main_title">{t}New Supplier Product{/t} @  {$supplier->get('Supplier Name')}</span> 
			</div>
			<div class="buttons" style="float:right">
				<button onclick="window.location='supplier.php?id={$supplier->id}'" class="negative"> {t}Cancel{/t}</button> 
			</div>
			<div style="clear:both">
			</div>
		</div>
		<div id="contact_messages_div">
			<span id="contact_messages"></span> 
		</div>
		<div style="margin-top:20px">
			<div id="results" style="margin-top:0px;float:right;width:600px;">
			</div>
			<div style="float:left;width:800px;">
				<input type="hidden" value="{$supplier_key}" id="supplier_key" />
				<table id="new_supplier_product" class="edit" border="0" >
					<tr class="title">
						<td colspan="3">{t}Supplier Product Info{/t}</td>
					</tr>
					<tr>
						<td class="label">{t}Code{/t}:</td>
						<td class="input"> 
						<div>
							<input class="short"  id="product_code" changed="0" type='text' maxlength="255" class='text' value="" />
							<div id="product_code_Container">
							</div>
						</div>
						</td>
						<td id="product_code_msg" class="message edit_td_alert"></td>
					</tr>
					
					<tr>
						<td class="label">{t}Units per carton{/t}:</td>
						<td class="input very_short"> 
						<div>
							<input class="very_short" id="units_per_case" changed="0" type='text' maxlength="255" class='text' value="" />
							<div id="units_per_case_Container">
							</div>
						</div>
						</td>
						<td id="units_per_case_msg" class="message edit_td_alert"></td>
					</tr>
					<tr>
						<td  class="label">{t}Cost per carton{/t}:</td>
						<td class="input"> 
						<div>
							<input class="short" id="case_cost" changed="0" type='text' maxlength="255" class='text' value="" />
							<div id="case_cost_Container">
							</div>
						</div>
						</td>
						<td id="case_cost_msg" class="edit_td_alert"></td>
					</tr>
					<tr>
						<td  class="label">{t}Unit description{/t}:</td>
						<td class="input"> 
						<div>
							<input  id="product_name" changed="0" type='text' maxlength="255" class='text' value="" />
							<div id="product_name_Container">
							</div>
						</div>
						</td>
						<td id="product_name_msg" class="edit_td_alert"></td>
					</tr>
					<tbody id="extra_fields" style='display:none'>
					
					<tr>
						<td style="width:200px" class="label">{t}Extended description{/t}:</td>
						<td style="width:370px"> 
						<div>
							<input style="width:100%" id="product_description" changed="0" type='text' maxlength="255" class='text' value="" />
							<div id="product_description_Container">
							</div>
						</div>
						</td>
						<td id="product_description_msg" class="edit_td_alert"></td>
					</tr>
					</tbody>
					<tr style="height:10px">
						<td colspan="2"> </td>
					</tr>
					<tr>
						<td colspan="2"> 
						<div class="buttons">
							<button style="margin-right:10px;visibility:" id="save_new_product" class="positive disabled">{t}Save{/t}</button> 
							<button style="margin-right:10px;visibility:" onclick="window.location='supplier.php?id={$supplier->id}'" class="negative">{t}Cancel{/t}</button> 
						</div>
						</td>
					</tr>
				</table>
				
			</div>
			<div style="clear:both;height:40px">
			</div>
		</div>
	</div>
</div>
{include file='footer.tpl'}