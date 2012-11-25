{include file='header.tpl'} 
<div id="bd" style="padding:0 20px">
	{include file='contacts_navigation.tpl'} 
	<div class="branch">
		<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a>&rarr; {if $user->get_number_stores()>1}<a href="customers_server.php">{t}Customers{/t}</a> &rarr; {/if}<a href="customers.php?store={$store->id}">{$store->get('Store Code')} {t}Customers{/t}</a> &rarr; {t}Adding Customer{/t}</span> 
	</div>
	<div style="clear:both;width:100%;border-bottom:1px solid #ccc;padding-bottom:3px;margin-bottom:15px">
		<div class="buttons" style="float:left">
			<span class="main_title"> {t}Adding new customer{/t}, <span class="id">{$store->get('Store Code')}</span> </span> 
		</div>
		<div class="buttons">
			<button class="negative" onclick="window.location='customers.php?store={$store->id}'">{t}Cancel{/t}</button> 
		</div>
		<div style="clear:both">
		</div>
	</div>
	<div>
		<div id="results" style="margin-top:0px;float:right;width:390px;">
		</div>
		<div id="form" style="float:left;width:540px;">
			<table class="edit" border="0" style="width:100%;margin-bottom:0px">
				<input type="hidden" value="{$store_key}" id="Store_Key" />
				<input type="hidden" value="{$customer_type}" id="Customer_Type" />
					
				<tbody id="company_section">
					<tr class="title">
						<td colspan> {t}Company Info{/t} </td>
						<td colspan="2"> 
						<div class="buttons small">
							<button onclick="customer_is_a_person()">{t}Set as a person{/t}</button> 
						</div>
						</td>
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
				<tbody>
				<tr class="title">
					<td > {t}Contact Info{/t} </td>
					<td colspan="2"> 
					<div class="buttons small">
						<button style="{if $customer_type=='Company'}display:none;{/if}" id="set_as_company" onclick="customer_is_a_company()">{t}Set as a company{/t}</button> 
					</div>
					</td>
				</tr>
				<tr class="first">
					<td style="width:120px" class="label">{t}Contact Name{/t}:</td>
					<td style="text-align:left;width:350px"> 
					<div>
						<input id="Contact_Name" value="" style="width:100%" />
						<div id="Contact_Name_Container">
						</div>
					</div>
					</td>
					<td style="width:70px"></td>
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
				</tbody>
				
				<tr class="title">
				<td colspan="3">{t}Address Info{/t}</td>
				</tr>
				<tr  >
					<td colspan="3"></td>
				</tr>
				
				
				{include file='edit_address_splinter.tpl' show_form=1 hide_type=1 hide_description=1 show_default_country=1 default_country_2alpha=$store->get('Store Home Country Code 2 Alpha') hide_buttons=1 address_identifier='' address_type='' function_value='' address_function='' show_components=false show_contact=false show_tel=false close_if_reset=false } 
				
				<tr style="height:10px" >
					<td colspan="3"></td>
				</tr>
				<tr class="title">
					<td colspan="3">{t}Other Info{/t}</td>
				</tr>
					
				{foreach from=$categories item=cat key=cat_key name=foo } 
				<tr>
					<td class="label">{t}{$cat->get('Category Label')}{/t}:</td>
					<td> 
					<select id="cat{$cat_key}" cat_key="{$cat_key}" onchange="update_category(this)">
						{foreach from=$cat->get_children_objects_new_subject() item=sub_cat key=sub_cat_key name=foo2 } {if $smarty.foreach.foo2.first} 
						<option value="">{t}Unknown{/t}</option>
						{/if} ` 
						<option other="{if $sub_cat->get('Is Category Field Other')=='Yes'}{t}true{/t}{else}{t}false{/t}{/if}" value="{$sub_cat->get('Category Key')}">{$sub_cat->get('Category Code')}</option>
						{/foreach} 
					</select>
					</td>
				</tr>
				<tbody id="other_tbody_{$cat_key}" style="display:none">
					<tr>
						<td></td>
						<td><textarea rows='2' cols="20" id="category_other_value_textarea_{$cat_key}"></textarea></td>
					</tr>
				</tbody>
				{/foreach} 
				<tr>
					<td class="label" style="width:200px;font-size:90%">{t}Send Newsletter{/t}:</td>
					<input type="hidden" value="Yes" id="allow_newsletter" />
					<input type="hidden" value="Yes" id="allow_marketing_email" />
					<input type="hidden" value="Yes" id="allow_marketing_postal" />
					<input type="hidden" value="No" id="re" />
					<td> 
					<div class="buttons small left">
						<button class="option selected " onclick="change_allow(this,'allow_newsletter','Yes')">{t}Yes{/t}</button> <button class="option " onclick="change_allow(this,'allow_newsletter','No')">{t}No{/t}</button> 
					</div>
					</td>
				</tr>
				<tr>
					<td class="label" style="width:200px;font-size:90%">{t}Send Marketing Emails{/t}:</td>
					<td> 
					<div class="buttons small left">
						<button class="option selected " onclick="change_allow(this,'allow_marketing_email','Yes')">{t}Yes{/t}</button> <button class="option " onclick="change_allow(this,'allow_marketing_email','No')">{t}No{/t}</button> 
					</div>
					</td>
				</tr>
				<tr>
					<td class="label" style="width:200px;font-size:90%">{t}Send Marketing Post{/t}:</td>
					<td> 
					<div class="buttons small left">
						<button class="option selected " onclick="change_allow(this,'allow_marketing_postal','Yes')">{t}Yes{/t}</button> <button class="option " onclick="change_allow(this,'allow_marketing_postal','No')">{t}No{/t}</button> 
					</div>
					</td>
				</tr>
				{if $hq_country=='ESP'} 
				<tr>
					<td class="label" style="width:200px;font-size:90%">Recargo de Equivalencia:</td>
					<td> 
					<div class="buttons small left">
						<button class="option  " onclick="change_allow(this,'re','Yes')">{t}Yes{/t}</button> <button class="option selected" onclick="change_allow(this,'re','No')">{t}No{/t}</button> 
					</div>
					</td>
				</tr>
				{/if} {foreach from=$new_subject item=custom_fields} 
				<tr class="first">
					<td style="width:120px" class="label">{$custom_fields.custom_field_name}:</td>
					<td style="text-align:left;width:350px"> 
					<div>
						<input style="text-align:left;" id="{$custom_fields.custom_field_name}" value="" ovalue="" valid="0"> 
						<div id="{$custom_fields.custom_field_name}_Container">
						</div>
					</div>
					</td>
					<td style="width:70px"></td>
				</tr>
				{/foreach} 
				<tr>
					<td colspan="3"> 
					<div style="float:right;display:none" id="creating_message">
						<img src="art/loading.gif" alt="" /> {t}Creating Contact{/t} 
					</div>
					<div id="new_Customer_buttons" class="buttons">
						<button class="disabled positive" id="save_new_Customer">{t}Save{/t}</button> <button class="negative" id="cancel_add_Customer">{t}Cancel{/t}</button> 
					</div>
					</td>
				</tr>
			</table>
		</div>
		<div id="Customer_found_dialog" style="display:none;float:right;border:1px solid #ccc;width:200px;padding:6px 10px;margin-top:3px;font-size:80%;color:#555">
			{t}Another contact has been found with the similar details{/t}. 
			<table style="margin:10px 0">
				<tr>
					<td><span style="cursor:pointer;text-decoration:underline" onclick="edit_founded()" id="pick_founded">{t}Edit the Customer found{/t} (<span id="founded_name"></span>)</span></td>
				</tr>
				<tr>
					<td><span style="color:red">{t}Creating this customer is likely to produce duplicate contacts.{/t}</span></br<span style="cursor:pointer;text-decoration:underline;color:red" id="save_when_founded">{t}Create customer anyway{/t}</span></td>
				</tr>
			</table>
		</div>
		<div id="email_found_dialog" style="display:none;float:right;border:1px solid #ccc;width:200px;padding:6px 10px;margin-top:3px;font-size:80%;color:#555">
			<b>{t}Another contact has the same email{/t}</b>. 
			<table style="margin:10px 0">
				<tr>
					<td style="cursor:pointer;text-decoration:underline" onclick="edit_founded()">{t}Edit the Customer found{/t} (<span id="email_founded_name"></span>)</td>
				</tr>
				<tr>
					<td><span style="color:red">{t}Creating this customer will produce duplicate contacts. The email will not be added.{/t}</span></br>
					<span style="cursor:pointer;text-decoration:underline;color:red" id="force_new">{t}Create customer anyway{/t}</span></td>
				</tr>
			</table>
		</div>
		<div id="email_found_other_store_dialog" style="display:none;float:right;border:1px solid #ccc;width:200px;padding:6px 10px;margin-top:3px;font-size:80%;color:#555">
			<b>{t}A Customer has the same email in another store{/t}</b>. 
			<table style="margin:10px 0">
				<input type="hidden" value="" id="found_email_other_store_customer_key"> 
				<tr>
					<td style="cursor:pointer;text-decoration:underline" onclick="clone_founded()">{t}Use contact data to create new customer in this store{/t}</td>
				</tr>
			</table>
		</div>
		<div style="clear:both;padding:10px;" id="validation">
			<div style="font-size:80%;margin-bottom:10px;display:none" id="mark_Customer_found">
				{t}Company has been found{/t} 
			</div>
		</div>
	</div>
	<div style="clear:both;height:40px">
	</div>
</div>

<div id="dialog_country_list">
	<div class="splinter_cell" style="padding:10px 15px 10px 0;border:none">
		<div id="the_table" class="data_table">
			<span class="clean_table_title">{t}Country List{/t}</span> {include file='table_splinter.tpl' table_id=100 filter_name=$filter_name100 filter_value=$filter_value100} 
			<div id="table100" class="data_table_container dtable btable">
			</div>
		</div>
	</div>
</div>
<div class="star_rating" id="star_rating_template" style="display:none">
	<img class="star" src="art/icons/star_dim.png" /><img class="star" src="art/icons/star_dim.png" /><img class="star" src="art/icons/star_dim.png" /><img class="star" src="art/icons/star_dim.png" /><img class="star" src="art/icons/star_dim.png" /> 
</div>
{include file='footer.tpl'} 