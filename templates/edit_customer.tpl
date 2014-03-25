{include file='header.tpl'} 
<div id="bd">
	{include file='contacts_navigation.tpl'} 
	<div class="branch">
		<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home"/></a>&rarr; {if $user->get_number_stores()>1}<a href="customers_server.php">{t}Customers{/t}</a> &rarr; {/if}<a href="customers.php?store={$store->id}">{$store->get('Store Code')} {t}Customers{/t}</a> &rarr; <a href="customer.php?id={$customer->id}">{$id}</a> ({t}Editing{/t})</span> 
	</div>
	<input type="hidden" value="{$customer->id}" id="customer_key" />
	<input type="hidden" value="{$registered_email}" id="registered_email" />
	<input type="hidden" value="{$store_key}" id="store_key" />
	<input type="hidden" id="parent_category_key" value="0" />
	<input type="hidden" id="category_key" value="0" />
	{foreach from=$enable_other item=other key=key} 
	<input type="hidden" id="enable_other_{$key}" value="{$other}" />
	{/foreach} {foreach from=$other_value item=other key=key} 
	<input type="hidden" id="other_value_{$key}" value="{$other}" />
	{/foreach} 
	<div class="top_page_menu">
	
		
		<div class="buttons">
				 {if isset($parent_list)}<img onmouseover="this.src='art/next_button.gif'" onmouseout="this.src='art/next_button.png'" title="{t}Next Customer{/t} {$next.name}" onclick="window.location='edit_customer.php?{$parent_info}id={$next.id}{if $parent_list}&p={$parent_list}{/if}'" src="art/next_button.png" alt=">" style="float:right;height:22px;cursor:pointer;position:relative;top:2px" />{/if} 

		<button style="margin-left:10px" onclick="window.location='customer.php?id={$customer->id}{if isset($parent_list)}&p={$parent_list}{/if}'"><img src="art/icons/door_out.png" alt="" /> {t}Exit Edit{/t}</button> 
		 <button id="convert_to_person" {if $customer_type!='Company' }style="display:none" {/if}>{t}Convert to Person{/t}</button> <button id="convert_to_company" class="state_details" style="{if $customer_type=='Company'}display:none{/if}">{t}Convert to Company{/t}</button>
		<button id="delete_customer" class="negative {if $customer->get('Customer With Orders')=='Yes'}disabled{/if}" {if $customer->get('Customer With Orders')=='Yes' }style="text-decoration: line-through;"{/if}>{t}Delete Customer{/t}</button>
		
		</div>
		<div class="buttons left">
{if isset($parent_list)}<img onmouseover="this.src='art/previous_button.gif'" onmouseout="this.src='art/previous_button.png'" title="{t}Previous Customer{/t} {$prev.name}" onclick="window.location='edit_customer.php?{$parent_info}id={$prev.id}{if $parent_list}&p={$parent_list}{/if}'" src="art/previous_button.png" alt="<" style="margin-right:10px;float:left;height:22px;cursor:pointer;position:relative;top:2px" />{/if}
		<span class="main_title">
		<span style="color:SteelBlue">{$id}</span> <span id="title_name">{$customer->get('Customer Name')}</span> 
	</span>
		</div>
		<div style="clear:both">
		</div>
	</div>
	
	<div style="padding:10px;background-color:#FAF8CC;width:300px;{if $recent_merges==''}display:none{/if}">
		{$recent_merges} 
	</div>
	<ul class="tabs" id="chooser_ul" style="clear:both;">
		<li> <span class="item {if $edit=='details'}selected{/if}" id="details"> <span> {t}Customer Details{/t}</span></span></li>
		<li> <span class="item {if $edit=='billing'}selected{/if}" id="billing"> <span> {t}Billing Information{/t}</span></span></li>
		<li> <span class="item {if $edit=='delivery'}selected{/if}" id="delivery"> <span> {t}Delivery Options{/t}</span></span></li>
		<li> <span class="item {if $edit=='categories'}selected{/if}" id="categories"> <span> {t}Categories{/t}</span></span></li>
		<li> <span class="item {if $edit=='communications'}selected{/if}" id="communications"> <span> {t}Communications{/t}</span></span></li>
		<li> <span class="item {if $edit=='merge'}selected{/if}" id="merge"> <span> {t}Merge{/t}</span></span></li>
		{if $no_of_sites>0} 
		<li> <span class="item {if $edit=='password'}selected{/if}" id="password" style="display:"> <span> {t}Website User{/t}</span></span></li>
		{/if} 
	</ul>
	<div class="tabbed_container">
		<div class="edit_block" style="{if $edit!='password'}display:none{/if};min-height:260px" id="d_password">
			{foreach from=$store->get_sites_data(true) item=site} 
			<table class="edit" border="0" style="width:100%">
				<tr class="title">
					<td colspan="5">{t}Site{/t}: {$site.SiteName}</td>
				</tr>
				<tr style="height:5px">
					<td colspan="5"></td>
				</tr>
				<tr style="{if !$customer->get('Customer Main Email Key')}display:none{/if}">
					<td>
					{if $customer->get_main_email_user_key()}
					<a href="site_user.php?id={$customer->get_main_email_user_key()}"><img src="art/icons/world.png" style="width:12px" title="{t}Register User{/t}" alt="{t}Register User{/t}"></a>
					{/if}
					{$customer->get('Customer Main Plain Email')}
					
					</td>
					<td style="{if $customer->get_main_email_user_key()}visibility:hidden{/if}"> 
					<div class="buttons">
						<button style="margin-left:10px" onclick="register_email(this,{$customer->get('Customer Main Email Key')},{$site.SiteKey} )">{t}Create Website User{/t}</button> 
					</div>
					</td>
					<td style="{if !$customer->get_main_email_user_key()}visibility:hidden{/if}"> 
					<div class="buttons">
						<button style="margin-left:10px" onclick="send_reset_password(this,{$customer->get_main_email_user_key()},{$site.SiteKey},'{$site.SiteURL}')">{t}Send Reset Password Email{/t}</button> 
					</div>
					</td>
					<td style="{if !$customer->get_main_email_user_key()}visibility:hidden{/if}"> 
					<div class="buttons">
						<button style="margin-left:10px" onclick="show_change_password_dialog(this, {$customer->get_main_email_user_key()})">{t}Set Password{/t}</button> 
					</div>
					</td>
					
				</tr>
				
				{foreach from=$customer->get_other_emails_data() item=other_email key=key} 
				<tr style="height:5px" >
					<td colspan="5"></td>
				</tr>
				<tr style="height:5px" class="top">
					<td colspan="2"></td>
				</tr>
				
				<tr >
					<td>{if $other_email.user_key}<a href="site_user.php?id={$other_email.user_key}"><img src="art/icons/world.png" style="width:12px" title="{t}Register User{/t}" alt="{t}Register User{/t}"></a>{/if} {$other_email.xhtml}</td>
					<td style="{if $other_email.user_key}visibility:hidden{/if}"> 
					<div class="buttons">
						<button style="margin-left:10px" onclick="register_email(this,{$other_email.key},{$site.SiteKey})">{t}Create Website User{/t}</button> 
					</div>
					</td>
					<td style="{if !$other_email.user_key}visibility:hidden{/if}"> 
					<div class="buttons">
						<button style="margin-left:10px" onclick="send_reset_password(this,{$other_email.user_key},{$site.SiteKey},'{$site.SiteURL}')">{t}Send Reset Password Email{/t}</button> 
					</div>
					</td>
					<td style="{if !$other_email.user_key}visibility:hidden{/if}"> 
					<div class="buttons">
						<button style="margin-left:10px" onclick="show_change_password_dialog({$other_email.user_key})">{t}Set Password{/t}</button> 
					</div>
					</td>
				</tr>
			
				{/foreach} 
				
				
			</table>
			{/foreach} 
		</div>
		<div class="edit_block" style="{if $edit!='merge'}display:none{/if};min-height:260px" id="d_merge">
			<table class="edit" border="0" style="width:700px">
				<tr>
					<td style="width:200px">{t}Merge with: (Customer ID){/t}</td>
					<td style="width:200px"> 
					<div>
						<input style="text-align:left;width:100%" id="customer_b_id" value="" ovalue=""> 
						<div id="customer_b_id_Container">
						</div>
					</div>
					</td>
					<td style="width:300px"> 
					<div class="buttons">
						<a id="go_merge" class="positive" style="display:none" href="">{t}Go{/t}</a> 
					</div>
					<span id="merge_msg" class="error" style="display:none"></span> </td>
				</tr>
			</table>
		</div>
		<div class="edit_block" style="{if $edit!='billing'}display:none{/if};min-height:260px" id="d_billing">
			
			<div id="customer_billing_address" style="float:left;xborder:1px solid #ddd;width:500px;margin-bottom:20px;">
			
				<table border="0" class="edit" style="width:100%">
					<tr class="title">
					<td colspan=2>{t}Billing Information{/t}</td>
					</tr>
					
					<tr>
						<td class="label">{t}Tax Number{/t}:</td>
						<td style="text-align:left;width:280px"> 
						<div>
							<input style="text-align:left;width:100%" id="Customer_Tax_Number" value="{$customer->get('Customer Tax Number')}" ovalue="{$customer->get('Customer Tax Number')}" valid="0"> 
							<div id="Customer_Tax_Number_Container">
							</div>
						</div>
						</td>
						<td id="Customer_Tax_Number_msg" class="edit_td_alert"></td>
					</tr>
					<tr style="{if $customer->get('Customer Type')!='Company'}display:none{/if}">
						<td class="label">{t}Fiscal Name{/t}:</td>
						<td style="text-align:left;"> 
						<div>
							<input style="text-align:left;" id="Customer_Fiscal_Name" value="{$customer->get('Customer Fiscal Name')}" ovalue="{$customer->get('Customer Fiscal Name')}" valid="0"> 
							<div id="Customer_Fiscal_Name_Container">
							</div>
						</div>
						</td>
						<td id="Customer_Fiscal_Name_msg" class="edit_td_alert"></td>
					</tr>
					
					{if  $hq_country!='ESP'} 
					<tr style="display:none">
						<td>{t}Tax Code{/t}:</td>
						<td> 
						<select id="tax_code">
							{foreach from=$tax_codes item=sub_cat key=sub_cat_key name=foo2 } 
							<option {if $customer->get('Customer Tax Category Code')==$sub_cat.code }selected="selected"{/if} value="{$sub_cat.code}">{$sub_cat.name}</option>
							{/foreach} 
						</select>
						</td>
					</tr>
					{/if} 
					
					<tr>
					<td colspan=2>
					<div class="buttons">
				<button  id="save_edit_billing_data" class="positive disabled">{t}Save{/t}</button> <button  id="reset_edit_billing_data" class="negative disabled">{t}Reset{/t}</button> 
			</div>
					</td>
					</tr>
					
					<tr>
					<td colspan="3">
					{if $hq_country=='ESP'} 
					<tr>
						<td class="label">RE:</td>
						<td> 
						<div class="buttons left">
							<button class="positive {if $customer->get('Recargo Equivalencia')=='Yes'}selected{/if}" onclick="save_comunications('Recargo Equivalencia','Yes')" id="Recargo Equivalencia_Yes">{t}Yes{/t}</button> <button class="negative {if $customer->get('Recargo Equivalencia')=='No'}selected{/if}" onclick="save_comunications('Recargo Equivalencia','No')" id="Recargo Equivalencia_No">{t}No{/t}</button> 
						</div>
						</td> 
					</tr>
					{/if}
					</td>
					</tr>
					
					
				</table>
			</div>
			<div style="clear:both">
				{include file='edit_billing_information_splinter.tpl'} 
			</div>
		</div>
		<div class="edit_block" style="{if $edit!='communications'}display:none{/if};min-height:260px" id="d_communications">
			<table class="edit">
				<tr class="title">
					<td colspan="5">{t}Emails{/t}</td>
				</tr>
				<tr>
					<td class="label" style="width:200px">{t}Send Newsletter{/t}:</td>
					<td> 
					<div class="buttons">
						<button class="{if $customer->get('Customer Send Newsletter')=='Yes'}selected{/if} positive" onclick="save_comunications('Customer Send Newsletter','Yes')" id="Customer Send Newsletter_Yes">{t}Yes{/t}</button> <button class="{if $customer->get('Customer Send Newsletter')=='No'}selected{/if} negative" onclick="save_comunications('Customer Send Newsletter','No')" id="Customer Send Newsletter_No">{t}No{/t}</button> 
					</div>
					</td>
				</tr>
				<tr>
					<td class="label" style="width:200px">{t}Send Marketing Emails{/t}:</td>
					<td> 
					<div class="buttons">
						<button class="{if $customer->get('Customer Send Email Marketing')=='Yes'}selected{/if} positive" onclick="save_comunications('Customer Send Email Marketing','Yes')" id="Customer Send Email Marketing_Yes">{t}Yes{/t}</button> <button class="{if $customer->get('Customer Send Email Marketing')=='No'}selected{/if} negative" onclick="save_comunications('Customer Send Email Marketing','No')" id="Customer Send Email Marketing_No">{t}No{/t}</button> 
					</div>
					</td>
				</tr>
				<tr class="title">
					<td colspan="5">{t}Post{/t}</td>
				</tr>
				<tr>
					<td class="label" style="width:200px">{t}Send Marketing Post{/t}:</td>
					<td> 
					<div class="buttons">
						<button class="{if $customer->get('Customer Send Postal Marketing')=='Yes'}selected{/if} positive" onclick="save_comunications('Customer Send Postal Marketing','Yes')" id="Customer Send Postal Marketing_Yes">{t}Yes{/t}</button> <button class="{if $customer->get('Customer Send Postal Marketing')=='No'}selected{/if} negative" onclick="save_comunications('Customer Send Postal Marketing','No')" id="Customer Send Postal Marketing_No">{t}No{/t}</button> 
					</div>
					</td>
				</tr>
				<tbody id="add_to_post_cue" style="display:none">
					<tr class="title">
						<td colspan="5">{t}Send Post {/t}</td>
					</tr>
					<tr>
						<td class="label" style="width:200px">{t}Add Customer To Send Post{/t}:</td>
						<td> 
						<div class="buttons">
							<button class="{if $customer->get('Send Post Status')=='To Send'}selected{/if} positive" onclick="save_comunications_send_post('Send Post Status','To Send')" id="Send Post Status_To Send">{t}Yes{/t}</button> <button class="{if $customer->get('Send Post Status')=='Cancelled'}selected{/if} negative" onclick="save_comunications_send_post('Send Post Status','Cancelled')" id="Send Post Status_Cancelled">{t}No{/t}</button> 
						</div>
						</td>
					</tr>
					<tr>
						<td class="label" style="width:200px">{t}Post Type{/t}:</td>
						<td> 
						<div class="buttons">
							<button class="{if $customer->get('Post Type')=='Letter'}selected{/if} positive" onclick="save_comunications_send_post('Post Type','Letter')" id="Post Type_Letter">{t}Letter{/t}</button> <button class="{if $customer->get('Post Type')=='Catalogue'}selected{/if} negative" onclick="save_comunications_send_post('Post Type','Catalogue')" id="Post Type_Catalogue">{t}Catalogue{/t}</button> 
						</div>
						</td>
					</tr>
				</tbody>
				{*} {foreach from=$categories item=cat key=cat_key name=foo } 
				<tr>
					<td class="label">{t}{$cat.name}{/t}:</td>
					<td> {foreach from=$cat.teeth item=cat2 key=cat2_id name=foo2} 
					<div id="cat_{$cat2_id}" default_cat="{$cat2.default_id}" class="options" style="margin:0">
						{foreach from=$cat2.elements item=cat3 key=cat3_id name=foo3} <span class="catbox {if $cat3.selected}selected{/if}" value="{$cat3.selected}" ovalue="{$cat3.selected}" onclick="save_radio(this)" cat_id="{$cat3_id}" id="cat{$cat3_id}" parent="{$cat3.parent}" position="{$cat3.position}" default="{$cat3.default}">{$cat3.name}</span> {/foreach} 
					</div>
					{/foreach} </td>
				</tr>
				{/foreach} {*} 
			</table>
		</div>
		<div class="edit_block" style="{if $edit!='categories'}display:none{/if};min-height:260px" id="d_categories">
			
				<table class="edit" border="0">
				<tr class="title">
					<td colspan="5">{t}Customer Type{/t}</td>
				</tr>
	<tr>
						<td class="label">Type:</td>
						<td> 
						<div class="buttons left">
							<button class=" {if $customer->get('Customer Level Type')=='Normal'}selected{/if}" onclick="save_comunications('Customer Level Type','Normal')" id="Customer_Level_Type_Normal">{t}Normal{/t}</button> 
							<button class=" {if $customer->get('Customer Level Type')=='VIP'}selected{/if}" onclick="save_comunications('Customer Level Type','VIP')" id="Customer_Level_Type_VIP">{t}VIP{/t}</button> 
							<button class=" {if $customer->get('Customer Level Type')=='Partner'}selected{/if}" onclick="save_comunications('Customer Level Type','Partner')" id="Customer_Level_Type_Partner">{t}Partner{/t}</button> 

						</div>
						</td> 
					</tr>
	
	
			</table>
			
			
			<table class="edit" border="0">
				<tr class="title">
					<td colspan="5">{t}Categories{/t}</td>
				</tr>
				{foreach from=$categories item=cat key=cat_key name=foo } 
				<tr>
					<td class="label" title="{$cat->get('Category Code')}">
					<div style="width:150px">
						{$cat->get('Category Label')}:
					</div>
					</td>
					<td> 
					
					<select id="cat{$cat_key}" cat_key="{$cat_key}" onchange="save_category(this)" ovalue="{$categories_value[$cat_key]}">
						{foreach from=$cat->get_children_objects() item=sub_cat key=sub_cat_key name=foo2 } {if $smarty.foreach.foo2.first} 
						<option value="">{t}Unknown{/t}</option>
						{/if} 
						<option {if $categories_value[$cat_key]==$sub_cat_key}selected='selected'{/if} other="{if $sub_cat->get('Is Category Field Other')=='Yes'}true{else}false{/if}" value="{$sub_cat->get('Category Key')}">{$sub_cat->get('Category Code')}</option>
						{/foreach} 
					</select>
					</td>
				</tr>
				<tbody id="show_other_tbody_{$cat_key}" style="{if !$cat->number_of_children_with_other_value('Customer',$customer->id)}display:none{/if}">
					<tr>
						<td>
						<div class="buttons small">
							<button onclick="show_save_other({$cat_key})">{t}Edit{/t}</button> 
						</div>
						</td>
						<td style="xborder:1px solid #ccc;">{if $cat->get_other_value('Customer',$customer->id)=='' }{t}No value set{/t}{else}{$cat->get_other_value('Customer',$customer->id)}{/if}</td>
					</tr>
				</tbody>
				<tbody id="other_tbody_{$cat_key}" style="display:none">
					<tr>
						<td></td>
						<td><textarea style="width:100%" rows='2' cols="20" id="other_textarea_{$cat_key}">{$cat->get_other_value('Customer',$customer->id)}</textarea></td>
					</tr>
					<tr>
						<td></td>
						<td>
						<div class="buttons small left">
							<button onclick="save_category_other_value({$cat->get_children_key_is_other_value()},{$cat->id})">{t}Save{/t}</button>
						</div>
						</td>
					</tr>
				</tbody>
				<tr style="height:15px">
					<td colspan="2"></td>
				</tr>
				{/foreach} 
			</table>
		</div>
		<div class="edit_block" style="{if $edit!='delivery'}display:none{/if};min-height:260px" id="d_delivery">
			{include file='edit_delivery_address_splinter.tpl' parent='customer' order_key=0} 
		</div>
		<div class="edit_block" style="{if $edit!='details'}display:none{/if};" id="d_details">
			<table class="edit" border="0" style="clear:both;width:100%">
				<tr class="title">
					<td colspan=2>
					{t}Contact Details{/t}
					</td>
				</tr>
		<tr style="height:1px">
					<td style="width:150px">
					</td>
					<td style="width:300px">
					</td>
					
					<td>
					
					</td>
				</tr>
				<tr {if $customer_type!='Company' }style="display:none" {/if} class="first">
					<td style="width:150px" class="label">{t}Company Name{/t}:</td>
					<td style="width:300px" style="text-align:left;"> 
					<div>
						<input style="text-align:left;width:100%" id="Customer_Name" value="{$customer->get('Customer Name')}" ovalue="{$customer->get('Customer Name')}" valid="0"> 
						<div id="Customer_Name_Container">
						</div>
					</div>
					</td>
					<td id="Customer_Name_msg" class="edit_td_alert"></td>
				</tr>
				<tr class="first">
					<td style="width:150px" class="label">{if $customer_type=='Company'}{t}Registration Number{/t}{else}{t}Identification Number{/t}{/if}:</td>
					<td style="width:300px" style="text-align:left;"> 
					<div>
						<input style="text-align:left;width:100%" id="Customer_Registration_Number" value="{$customer->get('Customer Registration Number')}" ovalue="{$customer->get('Customer Registration Number')}" valid="0"> 
						<div id="Customer_Registration_Number_Container">
						</div>
					</div>
					</td>
					<td id="Customer_Registration_Number_msg" class="edit_td_alert"></td>
				</tr>
				<tr>
					<td class="label">{t}Contact Name{/t}:</td>
					<td style="text-align:left;"> 
					<div>
						<input style="text-align:left;width:100%" id="Customer_Main_Contact_Name" value="{$customer->get('Customer Main Contact Name')}" ovalue="{$customer->get('Customer Main Contact Name')}" valid="0"> 
						<div id="Customer_Main_Contact_Name_Container">
						</div>
					</div>
					</td>
					<td id="Customer_Main_Contact_Name_msg" class="edit_td_alert"></td>
				</tr>
				<tr>
					<td class="label">{if $customer->get_main_email_user_key()}<img  src="art/icons/world.png" alt="{t}Registered User{/t}" title="{t}Registered User{/t}"  > {/if}<img id="comment_icon_email" src="{if $customer->get_principal_email_comment()==''}art/icons/comment.gif{else}art/icons/comment_filled.gif{/if}" style="cursor:pointer;{if $customer->get('Customer Main Email Key')==''}display:none{/if}" onclick="change_comment(this,'email',{$customer->get('Customer Main Email Key')})"> {t}Contact Email{/t}:</td>
					<td style="text-align:left"> 
					<div>
						<input style="text-align:left;width:100%" id="Customer_Main_Email" value="{$customer->get('Customer Main Plain Email')}" ovalue="{$customer->get('Customer Main Plain Email')}" valid="0"> 
						<div id="Customer_Main_Email_Container">
						</div>
					</div>
					</td>
					<td> <span id="display_add_other_email" class="state_details" style="font-size:80%;color:#777;{if $customer->get('Customer Main Plain Email')==''}display:none{/if}">{t}Add other Email{/t}</span> <span id="Customer_Main_Email_msg" class="edit_td_alert">{$main_email_warning}</span> </td>
				</tr>
				{foreach from=$customer->get_other_emails_data() key=other_email_key item=other_email } 
				<tr id="tr_other_email{$other_email_key}">
					<td class="label"> {if $other_email.user_key}<img  src="art/icons/world.png" alt="{t}Registered User{/t}" title="{t}Registered User{/t}"  > {/if}<img src="art/icons/edit.gif" style="cursor:pointer" onclick="change_other_field_label(this,'email',{$other_email_key})"> <span id="tr_other_email_label{$other_email_key}">{if $other_email.label==''}{t}Other Email{/t}{else}{$other_email.label} (Email){/if}:</span> </td>
					<td style="text-align:left"> 
					<div>
						<input style="text-align:left;width:100%" id="Customer_Email{$other_email_key}" value="{$other_email.email}" ovalue="{$other_email.email}" valid="0"> 
						<div id="Customer_Email{$other_email_key}_Container">
						</div>
					</div>
					</td>
					<td> <span id="Customer_Email{$other_email_key}_msg" class="edit_td_alert"></span> </td>
				</tr>
				{/foreach} 
				<tr id="tr_add_other_email" style="display:none">
					<td class="label">{t}Other Email{/t}:</td>
					<td style="text-align:left"> 
					<div>
						<input style="text-align:left;width:100%" id="Customer_Other_Email" value="" ovalue="" valid="0"> 
						<div id="Customer_Other_Email_Container">
						</div>
					</div>
					</td>
					<td id="Customer_Other_Email_msg" class="edit_td_alert"></td>
				</tr>
				<tr>
					<td class="label"><img id="comment_icon_telephone" src="{if $customer->get_principal_telecom_comment('Telephone')==''}art/icons/comment.gif{else}art/icons/comment_filled.gif{/if}" style="cursor:pointer;{if $customer->get('Customer Main Telephone Key')==''}display:none{/if}" onclick="change_comment(this,'telephone',{$customer->get('Customer Main Telephone Key')})"> {t}Contact Telephone{/t}:</td>
					<td style="text-align:left"> 
					<div>
						<input style="text-align:left;width:100%" id="Customer_Main_Telephone" value="{$customer->get('Customer Main XHTML Telephone')}" ovalue="{$customer->get('Customer Main XHTML Telephone')}" valid="0"> 
						<div id="Customer_Main_Telephone_Container">
						</div>
					</div>
					</td>
					<td> <span id="display_add_other_telephone" class="state_details" style="font-size:80%;color:#777;{if $customer->get('Customer Main XHTML Telephone')==''}display:none{/if}">{t}Add other Telephone{/t}</span> <span id="Customer_Main_Telephone_msg" class="edit_td_alert">{$main_telephone_warning.$main_telephone_warning_key}</span> </td>
				</tr>
				{foreach from=$customer->get_other_telephones_data() key=other_telephone_key item=other_telephone } 
				<tr id="tr_other_telephone{$other_telephone_key}">
					<td class="label"> <img src="art/icons/edit.gif" style="cursor:pointer" onclick="change_other_field_label(this,'telephone',{$other_telephone_key})"> <span id="tr_other_telephone_label{$other_telephone_key}">{if $other_telephone.label==''}{t}Other Telephone{/t}{else}{$other_telephone.label} (Telephone){/if}:</span></td>
					<td style="text-align:left"> 
					<div>
						<input style="text-align:left;width:100%" id="Customer_Telephone{$other_telephone_key}" value="{$other_telephone.xhtml}" ovalue="{$other_telephone.xhtml}" valid="0"> 
						<div id="Customer_Telephone{$other_telephone_key}_Container">
						</div>
					</div>
					</td>
					<td> <span id="Customer_Telephone{$other_telephone_key}_msg" class="edit_td_alert">{$other_telephone_warning.$other_telephone_key}</span> </td>
				</tr>
				{/foreach} 
				<tr id="tr_add_other_telephone" style="display:none">
					<td class="label">{t}Other Telephone{/t}:</td>
					<td style="text-align:left"> 
					<div>
						<input style="text-align:left;width:100%" id="Customer_Other_Telephone" value="" ovalue="" valid="0"> 
						<div id="Customer_Other_Telephone_Container">
						</div>
					</div>
					</td>
					<td id="Customer_Other_Telephone_msg" class="edit_td_alert"></td>
				</tr>
				<tr>
					<td class="label"><img id="comment_icon_mobile" src="{if $customer->get_principal_telecom_comment('Mobile')==''}art/icons/comment.gif{else}art/icons/comment_filled.gif{/if}" style="cursor:pointer;{if $customer->get('Customer Main Mobile Key')==''}display:none{/if}" onclick="change_comment(this,'mobile',{$customer->get('Customer Main Mobile Key')})"> {t}Contact Mobile{/t}:</td>
					<td style="text-align:left"> 
					<div>
						<input style="text-align:left;width:100%" id="Customer_Main_Mobile" value="{$customer->get('Customer Main XHTML Mobile')}" ovalue="{$customer->get('Customer Main XHTML Mobile')}" valid="0"> 
						<div id="Customer_Main_Mobile_Container">
						</div>
					</div>
					</td>
					<td> <span id="display_add_other_mobile" class="state_details" style="font-size:80%;color:#777;{if $customer->get('Customer Main XHTML Mobile')==''}display:none{/if}">{t}Add other Mobile{/t}</span> <span id="Customer_Main_Mobile_msg" class="edit_td_alert">{$main_mobile_warning.$main_mobile_warning_key}</span> </td>
				</tr>
				{foreach from=$customer->get_other_mobiles_data() key=other_mobile_key item=other_mobile } 
				<tr id="tr_other_mobile{$other_mobile_key}">
					<td class="label"><img src="art/icons/edit.gif" style="cursor:pointer" onclick="change_other_field_label(this,'mobile',{$other_mobile_key})"> <span id="tr_other_mobile_label{$other_mobile_key}">{if $other_mobile.label==''}{t}Other Mobile{/t}{else}{$other_mobile.label} (Mobile){/if}:</span></td>
					<td style="text-align:left"> 
					<div>
						<input style="text-align:left;width:100%" id="Customer_Mobile{$other_mobile_key}" value="{$other_mobile.number}" ovalue="{$other_mobile.number}" valid="0"> 
						<div id="Customer_Mobile{$other_mobile_key}_Container">
						</div>
					</div>
					</td>
					<td> <span id="Customer_Mobile{$other_mobile_key}_msg" class="edit_td_alert">{$other_mobile_warning.$other_mobile_key}</span> </td>
				</tr>
				{/foreach} 
				<tr id="tr_add_other_mobile" style="display:none">
					<td class="label">{t}Other Mobile{/t}:</td>
					<td style="text-align:left"> 
					<div>
						<input style="text-align:left;width:100%" id="Customer_Other_Mobile" value="" ovalue="" valid="0"> 
						<div id="Customer_Other_Mobile_Container">
						</div>
					</div>
					</td>
					<td id="Customer_Other_Mobile_msg" class="edit_td_alert"></td>
				</tr>
				<tr>
					<td class="label"><img id="comment_icon_fax" src="{if $customer->get_principal_telecom_comment('FAX')==''}art/icons/comment.gif{else}art/icons/comment_filled.gif{/if}" style="cursor:pointer;{if $customer->get('Customer Main FAX Key')==''}display:none{/if}" onclick="change_comment(this,'fax',{if $customer->get('Customer Main FAX Key') == NULL}{0}{else}{$customer->get('Customer Main FAX Key')}{/if})"> {t}Contact Fax{/t}:</td>
					<td style="text-align:left"> 
					<div>
						<input style="text-align:left;width:100%" id="Customer_Main_FAX" value="{$customer->get('Customer Main XHTML FAX')}" ovalue="{$customer->get('Customer Main XHTML FAX')}" valid="0"> 
						<div id="Customer_Main_FAX_Container">
						</div>
					</div>
					</td>
					<td> <span id="display_add_other_fax" class="state_details" style="font-size:80%;color:#777;{if $customer->get('Customer Main XHTML FAX')==''}display:none{/if}">{t}Add other Fax{/t}</span> <span id="Customer_Main_FAX_msg" class="edit_td_alert">{$main_fax_warning.$main_fax_warning_key}</span> </td>
				</tr>
				{foreach from=$customer->get_other_faxes_data() key=other_fax_key item=other_fax } 
				<tr id="tr_other_fax{$other_fax_key}">
					<td class="label"><img src="art/icons/edit.gif" style="cursor:pointer" onclick="change_other_field_label(this,'fax',{$other_fax_key})"> <span id="tr_other_fax_label{$other_fax_key}">{if $other_fax.label==''}{t}Other Fax{/t}{else}{$other_fax.label} (Fax){/if}:</span></td>
					<td style="text-align:left"> 
					<div>
						<input style="text-align:left;width:100%" id="Customer_FAX{$other_fax_key}" value="{$other_fax.number}" ovalue="{$other_fax.number}" valid="0"> 
						<div id="Customer_FAX{$other_fax_key}_Container">
						</div>
					</div>
					</td>
					<td> <span id="Customer_FAX{$other_fax_key}_msg" class="edit_td_alert">{$other_fax_warning.$other_fax_key}</span> </td>
				</tr>
				{/foreach} 
				<tr id="tr_add_other_fax" style="display:none">
					<td class="label">{t}Other Fax{/t}:</td>
					<td style="text-align:left"> 
					<div>
						<input style="text-align:left;width:100%" id="Customer_Other_FAX" value="" ovalue="" valid="0"> 
						<div id="Customer_Other_FAX_Container">
						</div>
					</div>
					</td>
					<td id="Customer_Other_FAX_msg" class="edit_td_alert"></td>
				</tr>
				{*Edit custom fields*} {foreach from=$show_case key=custom_field_key item=custom_field_value } 
				<tr id="tr_{$custom_field_value.lable}">
					<td class="label">{$custom_field_key}:</td>
					<td style="text-align:left"> 
					<div>
						<input style="text-align:left;width:100%" id="Customer_{$custom_field_value.lable}" value="{$custom_field_value.value}" ovalue="{$custom_field_value.value}" valid="0"> 
						<div id="Customer_{$custom_field_value.lable}_Container">
						</div>
					</div>
					</td>
					<td> <span id="Customer_{$custom_field_value.lable}_msg" class="edit_td_alert"></span> </td>
				</tr>
				{/foreach} 
				<tr id="tr_Customer_Preferred_Contact_Number" style="{if $customer->get('Customer Main XHTML Mobile')=='' or $customer->get('Customer Main XHTML Telephone')==''   }display:none{/if}">
					<td class="label" style="width:200px">{t}Preferred contact number{/t}:</td>
					<td> 
					<div class="options" style="margin:0">
						<span class="{if $customer->get('Customer Preferred Contact Number')=='Telephone'}selected{/if}" onclick="save_preferred(this,'Telephone')" id="Customer_Preferred_Contact_Number_Telephone">{t}Telephone{/t}</span> <span class="{if $customer->get('Customer Preferred Contact Number')=='Mobile'}selected{/if}" onclick="save_preferred(this,'Mobile')" id="Customer_Preferred_Contact_Number_Mobile">{t}Mobile{/t}</span> 
					</div>
					</td>
				</tr>



				<tr>
					<td class="label">{t}Website{/t}:</td>
					<td style="text-align:left"> 
					<div>
						<input style="text-align:left;width:100%" id="Customer_Website" value="{$customer->get('Customer Website')}" ovalue="{$customer->get('Customer Website')}" valid="0"> 
						<div id="Customer_Website_Container">
						</div>
					</div>
					</td>
					<td>
					<span id="Customer_Website_msg" class="edit_td_alert"></span> 
					</td>
				</tr>

<tr>
<td colspan="2"> 
					<div class="buttons" style="margin-top:10px">
						<button  id="save_edit_customer" class="positive disabled">{t}Save{/t}</button> <button id="reset_edit_customer" class="negative disabled">{t}Reset{/t}</button> 
					</div>
					</td>
</tr>




			</table>
			
			<table class="edit" border="0" style="clear:both;margin-top:10px;width:100%">
			<tr class="title">
					<td colspan=2>
					{t}Contact Address{/t}
					</td>
				</tr>
				<tr style="height:1px">
					<td style="width:150px">
					</td>
					<td style="width:300px">
					</td>
					
					<td>
					
					</td>
				</tr>
				
								{include file='edit_address_splinter.tpl' address_identifier='contact_' hide_type=true hide_description=true show_components=true hide_buttons=false default_country_2alpha="$default_country_2alpha" show_form=1 show_default_country=1 address_type=false function_value='' address_function='' show_contact=false show_tel=false close_if_reset=false } 

				</table>
			
		
			
		</div>
		
	</div>
	<div class="buttons small">
	<button id="show_history" style="{if $show_history}display:none{/if};margin-right:0px" onclick="show_history()">{t}Show changelog{/t}</button> <button id="hide_history" style="{if !$show_history}display:none{/if};margin-right:0px" onclick="hide_history()">{t}Hide changelog{/t}</button> 
</div>
<div id="history_table" class="data_table" style="clear:both;{if !$show_history}display:none{/if}">
	<span class="clean_table_title">{t}Changelog{/t}</span> 
	<div class="table_top_bar space">
	</div>
	{include file='table_splinter.tpl' table_id=1 filter_name=$filter_name1 filter_value=$filter_value1 } 
	<div id="table1" class="data_table_container dtable btable history">
	</div>
</div>
</div>
<div id="dialog_country_list" style="position:absolute;left:-1000;top:0">
	<div class="splinter_cell" style="padding:10px 15px 10px 0;border:none">
		<div id="the_table" class="data_table">
			<span class="clean_table_title">{t}Country List{/t}</span> {include file='table_splinter.tpl' table_id=100 filter_name=$filter_name100 filter_value=$filter_value100} 
			<div id="table100" class="data_table_container dtable btable">
			</div>
		</div>
	</div>
</div>
<div id="dialog_other_field_label">
	<div id="other_field_label_msg">
	</div>
	<input type="hidden" value="" id="other_field_label_scope" />
	<input type="hidden" value="" id="other_field_label_scope_key" />
	<table style="padding:20px;margin:20px 10px 10px 5px">
		<tr>
			<td colspan="2"> 
			<input id="other_field_label" value="" />
			(<span id="other_field_label_scope_name"></span>) </td>
		</tr>
		<tr class="buttons" style="font-size:100%;">
			<td style="text-align:center;width:50%"> <span class="unselectable_text button disabled" >{t}Cancel{/t}</span></td>
			<td style="text-align:center;width:50%"> <span style="display:block;margin-top:5px" onclick="save_other_field_label()" id="note_save" class="disabled unselectable_text button">{t}Save{/t}</span></td>
		</tr>
	</table>
</div>
<div id="dialog_comment">
	<div id="comment_msg">
	</div>
	<input type="hidden" value="" id="comment_scope" />
	<input type="hidden" value="" id="comment_scope_key" />
	<input type="hidden" value="{$customer->get_principal_telecom_comment('Telephone')}" id="comment_telephone" />
	<input type="hidden" value="{$customer->get_principal_telecom_comment('FAX')}" id="comment_fax" />
	<input type="hidden" value="{$customer->get_principal_telecom_comment('Mobile')}" id="comment_mobile" />
	<input type="hidden" value="{$customer->get_principal_email_comment()}" id="comment_email" />
	<table style="padding:20px;margin:20px 10px 10px 5px">
		<tr>
			<td>{t}Comment{/t}:</td>
		</tr>
		<tr>
			<td colspan="2"> 
			<input id="comment" value="" ovalue="" />
			</td>
		</tr>
		<tr>
			<td> 
			<div class="buttons">
				<button onclick="save_comment()" id="comment_save" class="positive">{t}Save{/t}</button> <button class="cancel" onclick="cancel_comment()">{t}Cancel{/t}</button> </td>
			</div>
			</td>
		</tr>
	</table>
</div>



<div id="dialog_delete_customer" style="padding:20px 10px 10px 10px;">
	<h2 style="padding-top:0px">
		{t}Delete Customer{/t} 
	</h2>
	<p>
		{t}This operation cannot be undone{/t}.<br> {t}Would you like to proceed?{/t} 
	</p>
	<div style="display:none" id="deleting">
		<img src="art/loading.gif" alt=""> {t}Deleting customer, wait please{/t} 
	</div>
	<div id="delete_customer_buttons" class="buttons">
		<button id="save_delete_customer" class="positive">{t}Yes, delete it!{/t}</button> <button id="cancel_delete_customer" class="negative">{t}No i dont want to delete it{/t}</button> 
	</div>
</div>
<div id="dialog_convert_to_person" style="padding:20px 10px 10px 10px;">
	<p>
		{t}Setting the contact as a person will delete the company name{/t} 
	</p>
	<input type="hidden" value="{$delete_button_tooltip}" id="delete_button_tooltip"> 
	<div class="buttons">
		<button id="save_convert_to_person" class="negative">{t}Convert to Person{/t}</button> <button id="cancel_convert_to_person" class="positive">{t}Cancel{/t}</button> 
	</div>
	<div style="clear:both">
	</div>
</div>
<div id="dialog_convert_to_company" style="padding:20px 10px 10px 10px;width:400px">
	<table class="edit" style="width:400px" border="0">
		<tr class="first">
			<td style="width:100px" class="label">{t}Company Name{/t}:</td>
			<td style="text-align:left;width:200px"> 
			<div>
				<input style="text-align:left;width:100%" id="New_Company_Name" value="" ovalue="" valid="0"> 
				<div id="New_Company_Name_Container">
				</div>
			</div>
			</td>
		</tr>
		<tr>
			<tr style="height:10px">
				<td colspan="2"></td>
			</tr>
			<td colspan="2"> <span style="float:left" id="New_Company_Name_msg" style="text-align:left;width:50px" class="edit_td_alert"></span> 
			<div id="convert_to_person_buttons" class="buttons">
				<button id="save_convert_to_company">{t}Convert to Company{/t}</button> <button id="cancel_convert_to_company" class="negative">{t}Cancel{/t}</button> 
			</div>
			<div style="">
				<span id="convert_to_company_processing" style="display:none;float:right"><img src="art/loading.gif" /> {t}Processing your request{/t}</span> 
			</div>
			</td>
		</tr>
	</table>
</div>
<input id="user_key" value="" type="hidden" />
<div id="dialog_set_password" style="width:300px;padding:20px 20px 10px 20px ">
	<table border="0" class="edit" style="width:100%" >
		<tr class="title">
			<td colspan="2">{t}Change Password{/t} </td>
		</tr>
		<tr>
			<td style="width:120px" class="label">{t}Password{/t}: </td>
			<td> 
			<input type="password" id="change_password_password1"></td>
		</tr>
		<tr>
			<td style="width:120px" class="label">{t}Confirm{/t}: </td>
			<td> 
			<input type="password" id="change_password_password2"></td>
		</tr>
		<input id="epwcp1" value="" type="hidden" />
		<input id="epwcp2" value="" type="hidden" />
		
		<tr style="height:10px">
			<td colspan="2"> 
			
			</td>
		</tr>
		<tr id="tr_change_password_buttons" class="button space">
			<td colspan="2"> 
			<div class="buttons">
				<button id="submit_change_password" class="positive">{t}Change Password{/t}</button> <button id="cancel_change_password" class="negative">{t}Cancel{/t}</button> 
			</div>
			</td>
		</tr>
		<tr id="tr_change_password_messages">
			<td colspan="2" style="text-align:right;padding-right:10px"><span style="display:none" id="change_password_error_no_password">{t}Write new password{/t}</span><span style="display:none" id="change_password_error_password_not_march">{t}Passwords don't match{/t}</span><span style="display:none" id="change_password_error_password_too_short">{t}Password is too short{/t}</span><span> </span> </td>
		</tr>
		<tr id="tr_change_password_error_message" style="display:none">
			<td colspan="2" style="text-align:right;padding-right:10px" id="change_password_error_message"></td>
		</tr>
		<tr id="tr_change_password_wait" style="display:none" class="button">
			<td colspan="2" class="aright"><img style="weight:24px" src="art/loading.gif"> <span style="position:relative;top:-5px">{t}Submitting changes{/t}</span></td>
		</tr>
	</table>
</div>
{include file='footer.tpl'} 