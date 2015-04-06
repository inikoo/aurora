{include file='header.tpl'} 
<div id="bd">
	{include file='locations_navigation.tpl'} 
	<input type="hidden" value="{$warehouse->id}" id="warehouse_id" />
	<input type="hidden" value="{$warehouse->id}" id="warehouse_key" />
	<input type="hidden" value="{$warehouse->id}" id="parent_key" />
	<input type="hidden" id="Custom_Field_Warehouse_Key" value="{$warehouse->id}"> 
	<input type="hidden" id="Custom_Field_Table" value="Part"> 
	<div class="branch">
		<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a>&rarr; {if $user->get_number_warehouses()>1}<a href="warehouses.php">{t}Warehouses{/t}</a> &rarr; {/if}{t}Inventory{/t}</span> 
	</div>
	<div class="top_page_menu">
		<div class="buttons" style="float:right">
			<button onclick="window.location='inventory.php?id={$warehouse->id}'"><img src="art/icons/door_out.png" alt=""> {t}Exit Edit{/t}</button> 
		</div>
		<div class="buttons" style="float:left">
			<span class="main_title"><img src="art/icons/warehouse.png" style="height:18px;position:relative;bottom:2px" /> <span class="id">{$warehouse->get('Warehouse Name')}</span> {t}Inventory Configuration{/t} </span> 
		</div>
		<div style="clear:both">
		</div>
	</div>
	<ul class="tabs" id="chooser_ul">
		<li><span class="item {if $edit=='setup'}selected{/if}" id="description"> <span> {t}Inventory Setup{/t}</span></span></li>
		<li><span class="item {if $edit=='description'}selected{/if}" id="products"> <span>{t}Warehouse Description{/t}</span></span></li>
		<li><span class="item {if $edit=='custom_fields'}selected{/if}" id="suppliers"> <span>{t}Parts Custom Fields{/t}</span></span></li>
	</ul>
	<div class="tabbed_container">
		<div class="edit_block" {if $edit!="setup" }style="display:none" {/if} id="d_setup">
		</div>
		<div class="edit_block" {if $edit!="description" }style="display:none" {/if} id="d_description">
		</div>
		<div class="edit_block" {if $edit!="custom_fields" }style="display:none" {/if} id="d_custom_fields">
			<div style="clear:both;margin-top:0px;margin-right:0px;width:{if $options_box_width}{$options_box_width}{else}700px{/if};float:right;margin-bottom:10px" class="right_box">
				<div class="general_options">
					{foreach from=$general_options_list item=options } {if $options.tipo=="url"} <span onclick="window.location.href='{$options.url}'">{$options.label}</span> {else} <span id="{$options.id}" state="{$options.state}">{$options.label}</span> {/if} {/foreach} 
				</div>
			</div>
			<div>
				<div class="search_box">
				</div>
				<div id="contact_messages_div">
					<span id="contact_messages"></span> 
				</div>
				<div>
					<div id="results" style="margin-top:0px;float:right;width:390px;">
					</div>
					<div style="float:left;width:540px;">
						<table class="edit" border="0" style="width:100%;margin-bottom:0px">
							<input type="hidden" value="{$store_key}" id="Store_Key" />
							<input type="hidden" value="{$customer_type}" id="Customer_Type" />
							<tbody id="company_section">
								<tr class="first">
									<td style="width:120px" class="label">{t}Field Name{/t}:</td>
									<td style="text-align:left;width:350px"> 
									<div>
										<input style="text-align:left;" id="Custom_Field_Name" value="" ovalue="" valid="0"> 
										<div id="Custom_Field_Name_Container">
										</div>
									</div>
									</td>
									<td style="width:70px"></td>
								</tr>
								<tr>
									<td style="width:120px" class="label">{t}Default Value{/t}:</td>
									<td style="text-align:left;width:350px"> 
									<div>
										<input style="text-align:left;" id="Default_Value" value="" ovalue="" valid="0"> 
										<div id="Default_Value_Container">
										</div>
									</div>
									</td>
									<td style="width:70px"></td>
								</tr>
								<tr>
									<td class="label" style="width:200px">{t}Custom Field Type{/t}:</td>
									<input type="hidden" value="varchar" id="Custom_Field_Type" />
									<input type="hidden" value="Yes" id="Custom_Field_In_New_Subject" />
									<input type="hidden" value="Yes" id="Custom_Field_In_Showcase" />
									<td> 
									<div class="options" style="margin:0">
										<span class="option selected" onclick="change_allow(this,'Custom_Field_Type','varchar')">{t}String{/t}</span> <span class="option" onclick="change_allow(this,'Custom_Field_Type','Mediumint')">{t}Integer{/t}</span> 
									</div>
									</td>
								</tr>
								<tr>
									<td class="label" style="width:400px">{t}Custom Field In New Subject{/t}:</td>
									<td> 
									<div class="options" style="margin:0">
										<span class="option selected" onclick="change_allow(this,'Custom_Field_In_New_Subject','Yes')">{t}Yes{/t}</span> <span class="option" onclick="change_allow(this,'Custom_Field_In_New_Subject','No')">{t}No{/t}</span> 
									</div>
									</td>
								</tr>
								<tr>
									<td class="label" style="width:300px">{t}Custom Field In Showcase{/t}:</td>
									<td> 
									<div class="options" style="margin:0">
										<span class="option selected" onclick="change_allow(this,'Custom_Field_In_Showcase','Yes')">{t}Yes{/t}</span> <span class="option" onclick="change_allow(this,'Custom_Field_In_Showcase','No')">{t}No{/t}</span> 
									</div>
									</td>
								</tr>
							</tbody>
							{foreach from=$categories item=cat key=cat_key name=foo } 
							<tr>
								<td class="label">{t}{$cat->get('Category Label')}{/t}:</td>
								<td> 
								<select id="cat{$cat_key}" cat_key="{$cat_key}" onchange="update_category(this)">
									{foreach from=$cat->get_children_objects() item=sub_cat key=sub_cat_key name=foo2 } {if $smarty.foreach.foo2.first} 
									<option value="">{t}Unknown{/t}</option>
									{/if} 
									<option value="{$sub_cat->get('Category Key')}">{$sub_cat->get('Category Label')}</option>
									{/foreach} 
								</select>
								</td>
							</tr>
							{/foreach} 
						</table>
					</div>
					<div style="clear:both;height:40px">
					</div>
				</div>
				<hr />
			</div>
		</div>
	
	</div>
</div>

{include file='footer.tpl'} 