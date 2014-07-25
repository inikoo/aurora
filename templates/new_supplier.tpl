{include file='header.tpl'} 
<div id="bd" style="padding:0 20px">
	{include file='suppliers_navigation.tpl'} 
	<div class="branch">
		<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a>&rarr; <a href="suppliers.php">{t}Suppliers{/t}</a> &rarr; {t}New Supplier{/t}</span> 
	</div>
	<div class="top_page_menu">
		<div class="buttons" style="float:left">
			<span class="main_title">
		{t}Adding new supplier{/t} 
	</span>
		</div>
		<div class="buttons">
			<button class="negative" onclick="window.location='suppliers.php'">{t}Cancel{/t}</button> 
		</div>
		<div style="clear:both">
		</div>
	</div>
	
	<div id="form" style="float:left;width:540px;">
		<table class="edit" border="0" style="width:100%;margin-bottom:0px">
			<tr class="title" style="height:10px;">
				<td colspan="3">{t}Supplier Info{/t}</td>
			</tr>
			<tr>
				<td style="width:120px" class="label">{t}Supplier Code{/t}:</td>
				<td style="text-align:left;width:350px"> 
				<div>
					<input style="text-align:left;width:100%" id="Supplier_Code" value="" ovalue="" valid="0"> 
					<div id="Supplier_Code_Container">
					</div>
				</div>
				</td>
				<td style="width:70px"></td>
			</tr>
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
			<tbody id="company_section">
				<tr class="title" style="height:30px">
					<td colspan> {t}Company Info{/t} </td>
					<td colspan="2"> </td>
				</tr>
				<tr class="first">
					<td style="width:120px" class="label">{t}Company Name{/t}:</td>
					<td style="text-align:left;width:350px"> 
					<div>
						<input style="text-align:left;" id="Company_Name" value="" ovalue="" valid="0"> 
						<div id="Company_Name_Container">
						</div>
					</div>
					</td>
					<td style="width:70px"></td>
				</tr>
				<tr>
					<td style="width:120px" class="label">{t}Tax Number{/t}:</td>
					<td style="text-align:left;width:350px"> 
					<div>
						<input style="text-align:left;width:100%" id="Company_Tax_Number" value="" ovalue="" valid="0"> 
						<div id="Company_Tax_Number_Container">
						</div>
					</div>
					</td>
					<td style="width:70px"></td>
				</tr>
				<tr>
					<td style="width:120px" class="label">{t}Registration Number{/t}:</td>
					<td style="text-align:left;width:350px"> 
					<div>
						<input style="text-align:left;width:100%" id="Company_Registration_Number" value="" ovalue="" valid="0"> 
						<div id="Company_Registration_Number_Container">
						</div>
					</div>
					</td>
					<td style="width:70px"></td>
				</tr>
				<tr style="height:10px">
					<td colspan="3"></td>
				</tr>
			</tbody>
			<tr class="title">
				<td> {t}Contact Info{/t} </td>
				<td colspan="2"> </td>
			</tr>
			<tr>
				<td class="label">{t}Contact Name{/t}:</td>
				<td style="text-align:left;width:350px"> 
				<div>
					<input id="Contact_Name" value="" style="width:100%" />
					<div id="Contact_Name_Container">
					</div>
				</div>
				</td>
				<td></td>
				</tr>
				<tr id="tr_Contact_Gender" style="display:none">
					<td class="label">{t}Gender{/t}:</td>
					<td> 
					<input type="hidden" id="Contact_Gender" />
					<span id="Contact_Gender_Male" label="Male" onclick="toggle_Contact_Gender(this)" class="small_button Contact_Gender" style="margin:0">{t}Male{/t}</span> <span id="Contact_Gender_Female" label="Famale" onclick="toggle_Contact_Gender(this)" class="small_button Contact_Gender" style="margin-left:3px">{t}Female{/t}</span> <span id="Contact_Gender_Unknown" label="Unknown" onclick="toggle_Contact_Gender(this)" class="small_button Contact_Gender" style="margin-left:3px">{t}Unknown{/t}</span> </td>
				</tr>
				<tbody style="display:none">
					<tr>
						<td class="label">{t}Salutation{/t}:</td>
						<td> 
						<input id="Contact_Salutation" type="hidden" value="" ovalue=""> {foreach from=$prefix item=s name=foo } <span onclick="update_salutation(this)" label="{$s.txt}" style="{if $smarty.foreach.foo.first}margin:0;{else}margin-left:3px{/if};{if $s.relevance>1};display:none{/if} " class="Contact_Salutation small_button" id="Contact_Salutation_{$s.txt}">{$s.txt}</span> {/foreach} </td>
					</tr>
					<tr>
						<td class="label">{t}First Name(s){/t}:</td>
						<td> 
						<input onkeyup="name_component_change();" onblur="" name="first_name" id="Contact_First_Name" value="" ovalue=""></td>
					</tr>
					<tr>
						<td class="label">{t}Surname(s){/t}:</td>
						<td> 
						<input onkeyup="name_component_change();" onblur="" name="surname" id="Contact_Surname" value="" ovalue=""></td>
					</tr>
					<tr style="display:none">
						<td class="label">{t}Suffix(s){/t}:</td>
						<td> 
						<input onkeyup="name_component_change();" onblur="" name="suffix" id="Contact_Suffix" value="" ovalue=""></td>
					</tr>
				</tbody>
				<tr id="email_mould" style="{if $scope=='corporation'}display:none{/if}">
					<td class="label"><img id="email_warning" title="" src="art/icons/exclamation.png" style="margin-left:5px;visibility:hidden" /> {t}Email{/t}:</td>
					<td> 
					<div>
						<input style="width:100%" id="Email" class="Email" to_delete="0" value="" ovalue="" email_key="" valid="" />
						<div id="Email_Container">
						</div>
					</div>
					</td>
				</tr>
				<tr id="telephone_mould" style="{if $scope=='corporation'}display:none{/if}">
					<td class="label"> <img id="telephone_warning" title="" src="art/icons/exclamation.png" style="margin-left:5px;visibility:hidden" /> {t}Telephone{/t}: </td>
					<td> 
					<div>
						<input style="width:100%" class="Telecom" telecom_key="0" telecom_type="Telephone" id="Telephone" telecom_type_description="" container_key="" value="" ovalue="" to_delete="0" />
						<div id="Telephone_Container">
						</div>
					</div>
					</td>
					<td id="Telephone_msg" class="edit_td_alert"></td>
				</tr>
				<tr class="Telecom_Details" style="display:none">
					<td class="label">{t}Country Code{/t}:</td>
					<td> 
					<input class="Country_Code" style="width:3em" value="" ovalue="" onkeyup="telecom_component_change(this)" id="Telephone_Country_Code" />
					</td>
				</tr>
				<tr class="Telecom_Details" style="display:none">
					<td class="label"><img class="help" src="art/icons/help.png" alt="?" /> {t}NAC{/t}:</td>
					<td> 
					<input id="Telephone_National_Access_Code" class="National_Access_Code" style="text-align:center;width:1em" value="" ovalue="" onkeyup="telecom_component_change(this)" />
					</td>
				</tr>
				<tr class="Telecom_Details" style="display:none">
					<td class="label">{t}Area Code{/t}:</td>
					<td> 
					<input id="Telephone_Area_Code" class="Area_Code" style="width:4em" value="" ovalue="" onkeyup="telecom_component_change(this)" />
					</td>
				</tr>
				<tr class="Telecom_Details" style="display:none">
					<td class="label">{t}Number{/t}:</td>
					<td> 
					<input id="Telephone_Number" class="Number" style="width:7em" value="" ovalue="" onkeyup="telecom_component_change(this)" />
					</td>
				</tr>
				<tr class="Telecom_Details" style="display:none">
					<td class="label">{t}Extension{/t}:</td>
					<td> 
					<input id="Telephone_Extension" class="Extension" style="width:5em" value="" ovalue="" onkeyup="telecom_component_change(this)" />
					</td>
				</tr>
				<tr id="mobile_mould" style="{if $scope=='corporation'}display:none{/if}">
					<td class="label"> <img id="mobile_warning" title="" src="art/icons/exclamation.png" style="margin-left:5px;visibility:hidden" /> {t}Mobile{/t}: </td>
					<td> 
					<div>
						<input style="width:100%" class="Telecom" telecom_key="0" telecom_type="mobile" id="Mobile" telecom_type_description="" container_key="" value="" ovalue="" to_delete="0" />
						<div id="Mobile_Container">
						</div>
					</div>
					</td>
				</tr>
				<tr id="FAX_mould" style="{if $scope=='corporation'}display:none{/if}">
					<td class="label"> <img id="FAX_warning" title="" src="art/icons/exclamation.png" style="margin-left:5px;visibility:hidden" /> {t}Fax{/t}: </td>
					<td> 
					<div>
						<input style="width:100%" class="Telecom" telecom_key="0" telecom_type="FAX" id="FAX" telecom_type_description="" container_key="" value="" ovalue="" to_delete="0" />
						<div id="FAX_Container">
						</div>
					</div>
					</td>
				</tr>
				
				
				
				<tr>
				<td class="label">QQ:</td>
				<td style="text-align:left;width:350px"> 
				<div>
					<input id="QQ" value="" style="width:100%" />
					<div id="QQ_Container">
					</div>
				</div>
				</td>
				<td></td>
				</tr>
				
				
				<tr class="title" style="height:30px">
					<td colspan="3">{t}Address Info{/t}</td>
				</tr>
				{include file='edit_address_splinter.tpl' show_form=1 hide_type=1 hide_description=1 show_default_country=1 default_country_2alpha='GB' hide_buttons=1 address_identifier='' address_type='' function_value='' address_function='' show_components=false show_contact=false show_tel=false close_if_reset=false } 
				<tr style="height:20px">
					<td colspan="3"></td>
				</tr>
				<tr>
					<td colspan="3"> 
					<div style="float:right;display:none" id="creating_message">
						<img src="art/loading.gif" alt="" /> {t}Creating Contact{/t} 
					</div>
					<div id="new_Supplier_buttons" class="buttons">
						<button class="disabled positive" id="save_new_supplier">{t}Save{/t}</button> <button class="negative" id="cancel_add_Supplier">{t}Cancel{/t}</button> 
					</div>
					</td>
				</tr>
			</table>
		</table>
		<div id="Supplier_found_dialog" style="display:none;float:right;border:1px solid #ccc;width:200px;padding:6px 10px;margin-top:3px;font-size:80%;color:#555">
			{t}Another contact has been found with the similar details{/t}. 
			<table style="margin:10px 0">
				<tr>
					<td><span style="cursor:pointer;text-decoration:underline" onclick="edit_founded()" id="pick_founded">{t}Edit the Supplier found{/t} (<span id="founded_name"></span>)</span></td>
				</tr>
				<tr>
					<td><span style="color:red">{t}Creating this supplier is likely to produce duplicate contacts.{/t}</span></br<span style="cursor:pointer;text-decoration:underline;color:red" id="save_when_founded">{t}Create supplier anyway{/t}</span></td>
				</tr>
			</table>
		</div>
		<div id="email_found_dialog" style="display:none;float:right;border:1px solid #ccc;width:200px;padding:6px 10px;margin-top:3px;font-size:80%;color:#555">
			<b>{t}Another contact has the same email{/t}</b>. 
			<table style="margin:10px 0">
				<tr>
					<td style="cursor:pointer;text-decoration:underline" onclick="edit_founded()">{t}Edit the Supplier found{/t} (<span id="email_founded_name"></span>)</td>
				</tr>
				<tr>
					<td><span style="color:red">{t}Creating this supplier will produce duplicate contacts. The email will not be added.{/t}</span></br>
					<span style="cursor:pointer;text-decoration:underline;color:red" id="force_new">{t}Create supplier anyway{/t}</span></td>
				</tr>
			</table>
		</div>
		<div id="email_found_other_store_dialog" style="display:none;float:right;border:1px solid #ccc;width:200px;padding:6px 10px;margin-top:3px;font-size:80%;color:#555">
			<b>{t}A Supplier has the same email{/t}</b>. 
			<table style="margin:10px 0">
				<input type="hidden" value="" id="found_email_other_store_supplier_key"> 
				<tr>
					<td style="cursor:pointer;text-decoration:underline" onclick="clone_founded()">{t}Use contact data to create new supplier{/t}</td>
				</tr>
			</table>
		</div>
		<div style="clear:both;padding:10px;" id="validation">
			<div style="font-size:80%;margin-bottom:10px;display:none" id="mark_Supplier_found">
				{t}Company has been found{/t} 
			</div>
		</div>
	</div>
</div>

{include file='footer.tpl'} 
<div id="dialog_country_list">
	<div class="splinter_cell" style="padding:10px 15px 10px 0;border:none">
		<div id="the_table" class="data_table">
			<span class="clean_table_title">{t}Country List{/t}</span> {include file='table_splinter.tpl' table_id=100 filter_name=$filter_name100 filter_value=$filter_value100} 
			<div id="table100" class="data_table_container dtable btable">
			</div>
		</div>
	</div>
</div>
