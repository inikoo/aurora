<input type="hidden" id="user_key" value="{$user->id}" />
<input type="hidden" id="store_key" value="{$store->id}" />
<input type="hidden" id="site_key" value="{$site->id}" />
<input type="hidden" id="customer_key" value="{$page->customer->id}" />
<input type="hidden" id="parent_category_key" value="0" />
<input type="hidden" id="category_key" value="0" />
{foreach from=$other_value item=other key=key} 
<input type="hidden" id="other_value_{$key}" value="{$other}" />
{/foreach} {foreach from=$enable_other item=other key=key} 
<input type="hidden" id="enable_other_{$key}" value="{$other}" />
{/foreach} {include file='profile_header.tpl' select='contact'} 

{if $site->get('Show Site Badges')=='Yes'} 
<div style="border:0px solid #ccc;padding:0px 20px;width:890px;font-size:15px;margin:0px auto;margin-top:20px">
	<div style="float:left;;xborder:1px solid #ccc;;height:60px;width:100px;;padding:5px 20px" id="show_upload_image">
		<img id="avatar" src="{if $user->get_image_src()}{$user->get_image_src()}{else}art/siluet.jpg{/if}" style="height:100px"> 
	</div>
	{include file='customer_badges.tpl' customer=$page->customer} 
	<div style="float:left;;border:1px solid #ccc;;height:60px;width:100px;;padding:5px 20px;margin-left:20px">
		Thank you for trading with us! 
	</div>
	<div style="clear:both">
	</div>
</div>
{/if} 

<div style="padding:0px 20px;float:left">
	<h2 style="padding-top:10px">
		{t}Contact Details{/t} 
	</h2>
	<div style="border:1px solid #ccc;padding:20px;width:400px;font-size:15px">
		<h3>
			{$page->customer->get('Customer Name')} ({$page->customer->get_formated_id()}) 
		</h3>
		<table id="customer_data" border="0" style="width:100%;margin-top:20px">
			<tr style="{if !($page->customer->get('Customer Type')=='Company')}display:none{/if}">
				<td>{t}Company{/t}:</td>
				<td><img id="show_edit_name" style="cursor:pointer" src="art/edit.gif" alt="{t}Edit{/t}" /></td>
				<td class="aright">{$page->customer->get('Customer Company Name')}</td>
			</tr>
			<tr>
				<td>{t}Name{/t}:</td>
				<td><img style="cursor:pointer" id="show_edit_contact" src="art/edit.gif" alt="{t}Edit{/t}" /></td>
				<td class="aright">{$page->customer->get('Customer Main Contact Name')}</td>
			</tr>
			{if $page->customer->get('Customer Main Email Key')} 
			<tr id="main_email_tr">
				<td>{t}Email{/t}:</td>
				<td><img src="art/lock.png"></td>
				<td id="main_email" class="aright">{$page->customer->get('Customer Main Plain Email')}</td>
			</tr>
			{/if} {foreach from=$page->customer->get_other_emails_data() item=other_email key=key name=foo} 
			<tr id="other_email_tr">
				<td>{t}Email{/t}:</td>
				<td><img src="art/lock.png"></td>
				<td id="email{$key}" class="aright">{$other_email.email}</td>
			</tr>
			{/foreach} 
			<tr>
				<td>{t}Telephone{/t}:</td>
				<td><img src="art/edit.gif" id="show_edit_telephone" alt="{t}Edit{/t}" /></td>
				<td class="aright">{$page->customer->get('Customer Main Plain Telephone')}</td>
			</tr>
			{foreach from=$custom_fields item=custom_field key=key} 
			<tr>
				<td>{$custom_field.name}:</td>
				<td><img src="art/edit.gif" id="show_edit_{$custom_field.name}" alt="{t}Edit{/t}" /></td>
				<td class="aright">{$custom_field.value}</td>
			</tr>
			{/foreach} 
			<tr>
				<td> 
				<div class="buttons">
					<button style="display:none" onclick="window.location='client.php'"><img src="art/icons/chart_pie.png" alt=""> {t}Edit Profile{/t}</button> 
				</div>
				</td>
			</tr>
		</table>
	</div>
</div>
<div style="padding:0px 20px;float:right;display:none">
	<h2 style="padding-top:10px">
		{t}Notes{/t} 
	</h2>
	<div style="border:1px solid #ccc;padding:20px;width:400px;font-size:15px">
	</div>
</div>
<div style="padding:0px 20px;float:right">
	<h2 style="padding-top:10px">
		{t}Communication{/t} 
	</h2>
	<div style="border:1px solid #ccc;padding:20px;width:400px;font-size:15px">
		<table class="edit" style="width:390px" border="0">
			<tr class="title">
				<td colspan="5">{t}Marketing Emails{/t}</td>
			</tr>
			<tr style="height:10px">
				<td colspan="3"></td>
			</tr>
			<tr style="height:30px;">
				<td class="label" style="width:200px">{t}Receive Newsletter{/t}:</td>
				<td> 
				<div class="buttons small">
					<button class="{if $page->customer->get('Customer Send Newsletter')=='Yes'}selected{/if} positive" onclick="save_comunications('Customer Send Newsletter','Yes')" id="Customer Send Newsletter_Yes">{t}Yes{/t}</button> <button class="{if $page->customer->get('Customer Send Newsletter')=='No'}selected{/if} negative" onclick="save_comunications('Customer Send Newsletter','No')" id="Customer Send Newsletter_No">{t}No{/t}</button> 
				</div>
				</td>
			</tr>
			<tr style="height:30px;">
				<td class="label" style="width:200px">{t}Email Offers & Updates{/t}:</td>
				<td> 
				<div class="buttons small">
					<button class="{if $page->customer->get('Customer Send Email Marketing')=='Yes'}selected{/if} positive" onclick="save_comunications('Customer Send Email Marketing','Yes')" id="Customer Send Email Marketing_Yes">{t}Yes{/t}</button> <button class="{if $page->customer->get('Customer Send Email Marketing')=='No'}selected{/if} negative" onclick="save_comunications('Customer Send Email Marketing','No')" id="Customer Send Email Marketing_No">{t}No{/t}</button> 
				</div>
				</td>
			</tr>
			<tr class="title">
				<td colspan="5">{t}Post{/t}</td>
			</tr>
			<tr style="height:10px">
				<td colspan="3"></td>
			</tr>
			<tr style="height:30px;">
				<td class="label" style="width:200px">{t}Offers & Information by post{/t}:</td>
				<td> 
				<div class="buttons small">
					<button class="{if $page->customer->get('Customer Send Postal Marketing')=='Yes'}selected{/if} positive" onclick="save_comunications('Customer Send Postal Marketing','Yes')" id="Customer Send Postal Marketing_Yes">{t}Yes{/t}</button> <button class="{if $page->customer->get('Customer Send Postal Marketing')=='No'}selected{/if} negative" onclick="save_comunications('Customer Send Postal Marketing','No')" id="Customer Send Postal Marketing_No">{t}No{/t}</button> 
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
					<div class="buttons small">
						<button class="{if $page->customer->get('Send Post Status')=='To Send'}selected{/if} positive" onclick="save_comunications_send_post('Send Post Status','To Send')" id="Send Post Status_To Send">{t}Yes{/t}</button> <button class="{if $page->customer->get('Send Post Status')=='Cancelled'}selected{/if} negative" onclick="save_comunications_send_post('Send Post Status','Cancelled')" id="Send Post Status_Cancelled">{t}No{/t}</button> 
					</div>
					</td>
				</tr>
				<tr>
					<td class="label" style="width:200px">{t}Post Type{/t}:</td>
					<td> 
					<div class="buttons small">
						<button class="{if $page->customer->get('Post Type')=='Letter'}selected{/if} positive" onclick="save_comunications_send_post('Post Type','Letter')" id="Post Type_Letter">{t}Letter{/t}</button> <button class="{if $page->customer->get('Post Type')=='Catalogue'}selected{/if} negative" onclick="save_comunications_send_post('Post Type','Catalogue')" id="Post Type_Catalogue">{t}Catalogue{/t}</button> 
					</div>
					</td>
				</tr>
			</tbody>
			<tbody id="social_media" style="{if $site->get('Site Show Twitter')=='No' && $site->get('Site Show Facebook')=='No'}display:none{/if}">
				<tr class="title">
					<td colspan="5">{t}Social Media{/t}</td>
				</tr>
				<tr style="height:10px">
					<td colspan="3"></td>
				</tr>
				<tr style="height:30px;{if $site->get('Site Show Twitter')=='No'}display:none{/if}">
					<td class="label" style="width:200px">{t}Follower on Twitter{/t}:</td>
					<td> 
					<div class="buttons small">
						<button class="{if $page->customer->get('Customer Follower On Twitter')=='Yes'}selected{/if} positive" onclick="save_comunications('Customer Follower On Twitter','Yes')" id="Customer Follower On Twitter_Yes">{t}Yes{/t}</button> <button class="{if $page->customer->get('Customer Follower On Twitter')=='No'}selected{/if} negative" onclick="save_comunications('Customer Follower On Twitter','No')" id="Customer Follower On Twitter_No">{t}No{/t}</button> 
					</div>
					</td>
				</tr>
				<tr style="height:30px;{if $site->get('Site Show Facebook')=='No'}display:none{/if}">
					<td class="label" style="width:200px">{t}Friend on Facebook{/t}:</td>
					<td> 
					<div class="buttons small">
						<button class="{if $page->customer->get('Customer Friend On Facebook')=='Yes'}selected{/if} positive" onclick="save_comunications('Customer Friend On Facebook','Yes')" id="Customer Friend On Facebook_Yes">{t}Yes{/t}</button> <button class="{if $page->customer->get('Customer Friend On Facebook')=='No'}selected{/if} negative" onclick="save_comunications('Customer Friend On Facebook','No')" id="Customer Friend On Facebook_No">{t}No{/t}</button> 
					</div>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
<div style="clear:both">
</div>
<div style="padding:0px 20px 20px 20px;float:left">
	<h2 style="padding-top:10px">
		{t}About you{/t} 
	</h2>
	<div style="border:1px solid #ccc;padding:20px;width:400px;font-size:15px;">
		<table style="margin:10px">
			{foreach from=$categories item=cat key=cat_key name=foo } 
			<tr>
				<td class="label"> 
				<div style="width:150px">
					{t}{$cat->get('Category Label')}{/t}: 
				</div>
				</td>
				<td> 
				<select id="cat{$cat_key}" cat_key="{$cat_key}" onchange="save_category(this)">
					{foreach from=$cat->get_children_objects_public_edit() item=sub_cat key=sub_cat_key name=foo2 } {if $smarty.foreach.foo2.first} 
					<option value="">{t}Unknown{/t}</option>
					{/if} 
					<option {if $categories_value[$cat_key]==$sub_cat_key }selected='selected' {/if} other="{if $sub_cat->get('Is Category Field Other')=='Yes'}{t}true{/t}{else}{t}false{/t}{/if}" value="{$sub_cat->get('Category Key')}">{$sub_cat->get('Category Label')}</option>
					{/foreach} 
				</select>
				</td>
			</tr>
			<tbody id="show_other_tbody_{$cat_key}" style="{if !$cat->number_of_children_with_other_value('Customer',$page->customer->id) || !$cat->get_children_key_is_other_value_public_edit()}display:none{/if}">
				<tr>
					<td> 
					<div class="buttons small">
						<button onclick="show_save_other({$cat_key})">{t}Edit{/t}</button> 
					</div>
					</td>
					<td style="border:1px solid #ccc;">{$cat->get_other_value('Customer',$page->customer->id)} </td>
				</tr>
			</tbody>
			<tbody id="other_tbody_{$cat_key}" style="display:none">
				<tr>
					<td></td>
					<td><textarea rows='2' cols="20" id="other_textarea_{$cat_key}">{$cat->get_other_value('Customer',$page->customer->id)}</textarea></td>
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
</div>

<div style="clear:both;margin-bottom:25px">
</div>


<div style="top:180px;left:490px;position:absolute;display:none;background-image:url('art/background_badge_info.jpg');width:200px;height:223px;" id="gold_reward_badge_info">
	<p style="padding:40px 20px;font-size:20px;margin:20px auto">
		bla bla bla <br />
		<a href="">More Info</a> 
	</p>
</div>


<div id="dialog_quick_edit_Customer_Name" style="padding:10px">
	<table style="margin:10px">
		<tr>
			<td>{t}Customer Name:{/t}</td>
			<td> 
			<div style="width:220px">
				<input type="text" id="Customer_Name" value="{$page->customer->get('Customer Company Name')}" ovalue="{$page->customer->get('Customer Company Name')}" valid="0"> 
				<div id="Customer_Name_Container">
				</div>
			</div>
			</td>
		</tr>
		<tr>
			<td colspan="2"> 
			<div class="buttons" style="margin-top:10px">
				<span id="Customer_Name_msg"></span> <button class="positive" onclick="save_quick_edit_name()">{t}Save{/t}</button> <button class="negative" id="close_quick_edit_name">{t}Cancel{/t}</button> 
			</div>
			</td>
		</tr>
	</table>
</div>
<div id="dialog_quick_edit_Customer_Contact" style="padding:10px">
	<table style="margin:10px">
		<tr>
			<td>{t}Name:{/t}</td>
			<td> 
			<div style="width:220px">
				<input type="text" id="Customer_Contact" value="{$page->customer->get('Customer Main Contact Name')}" ovalue="{$page->customer->get('Customer Main Contact Name')}" valid="0"> 
				<div id="Customer_Contact_Container">
				</div>
			</div>
			</td>
		</tr>
		<tr>
			<td colspan="2"> 
			<div class="buttons" style="margin-top:10px">
				<span id="Customer_Contact_msg"></span> <button class="positive" onclick="save_quick_edit_contact()">{t}Save{/t}</button> <button class="negative" id="close_quick_edit_contact">{t}Cancel{/t}</button> 
			</div>
			</td>
		</tr>
	</table>
</div>
<div id="dialog_quick_edit_Customer_Telephone" style="padding:10px">
	<table style="margin:10px">
		<tr>
			<td>{t}Telephone:{/t}</td>
			<td> 
			<div style="width:220px">
				<input type="text" id="Customer_Telephone" value="{$page->customer->get('Customer Main Plain Telephone')}" ovalue="{$page->customer->get('Customer Main Plain Telephone')}" valid="0"> 
				<div id="Customer_Telephone_Container">
				</div>
			</div>
			</td>
		</tr>
		<tr>
			<td colspan="2"> 
			<div class="buttons" style="margin-top:10px">
				<span id="Customer_Telephone_msg"></span> <button class="positive" onclick="save_quick_edit_telephone()">{t}Save{/t}</button> <button class="negative" id="close_quick_edit_telephone">{t}Cancel{/t}</button> 
			</div>
			</td>
		</tr>
	</table>
</div>
{foreach from=$custom_fields item=custom_field key=key} {if $custom_field.type=='Enum'} 
<div id="dialog_quick_edit_Customer_{$custom_field.name}" style="padding:10px">
	<table style="margin:10px">
		<tr>
			<td class="label" style="width:">{t}{$custom_field.name}{/t}:</td>
			<td> 
			<div class="buttons">
				<button class="{if $custom_field.value=='Yes'}selected{/if} positive" onclick="save_custom_enum('{$custom_field.name}','Yes')" id="{$custom_field.name}_Yes">{t}Yes{/t}</button> <button class="{if $custom_field.value=='No'}selected{/if} negative" onclick="save_custom_enum('{$custom_field.name}','No')" id="{$custom_field.name}_No">{t}No{/t}</button> 
			</div>
			</td>
		</tr>
	</table>
</div>
{else} 
<div id="dialog_quick_edit_Customer_{$custom_field.name}" style="padding:10px">
	<table style="margin:10px">
		<tr>
			<td>{t}{$custom_field.name}:{/t}</td>
			<td> 
			<div style="width:220px">
				<input type="text" id="Customer_{$custom_field.name}" value="{$custom_field.value}" ovalue="{$custom_field.value}" valid="0"> 
				<div id="Customer_{$custom_field.name}_Container">
				</div>
			</div>
			</td>
		</tr>
		<tr>
			<td colspan="2"> 
			<div class="buttons" style="margin-top:10px">
				<span id="Customer_{$custom_field.name}_msg"></span> <button class="positive" onclick="save_quick_edit_{$custom_field.name}()">{t}Save{/t}</button> <button class="negative" id="close_quick_edit_{$custom_field.name}">{t}Cancel{/t}</button> 
			</div>
			</td>
		</tr>
	</table>
</div>
{/if} {/foreach} {section name=foo loop=5} 
<div id="dialog_badge_info_{$smarty.section.foo.iteration}" style="padding:10px">
	<table style="margin:10px">
		<tr>
			<td>{$page->customer->badge_info($smarty.section.foo.iteration)}</td>
			<td></td>
		</tr>
		<tr>
			<td> 
			<div class="buttons" style="margin-top:10px">
				<button class="negative" id="close_badge_info_{$smarty.section.foo.iteration}">{t}Cancel{/t}</button> 
			</div>
			</td>
		</tr>
	</table>
</div>
{/section} 
<div id="dialog_image_upload" style="padding:10px">
	<table>
		<tr>
			<td>{t}Upload Image{/t}</td>
		</tr>
		<tr>
			<td> 
			<div class="image" image_id="{$user->get_image_key()}">
				<img {if $user->get_image_src()}style="display:''"{else}style="display:none"{/if} class="delete" src="art/icons/delete.png" alt="{t}Delete{/t}" title="{t}Delete{/t}" onClick="delete_image(this)"> 
			</div>
			</td>
		</tr>
		<tr>
			<td> 
			<form action="upload.php" enctype="multipart/form-data" method="post" id="testForm">
				<input id="upload_image_input" style="border:1px solid #ddd;" type="file" name="testFile" />
			</form>
			</td>
			<td> 
			<div class="buttons left">
				<button id="uploadButton" class="positive">{t}Upload{/t}</button> 
			</div>
			</td>
		</tr>
	</table>
</div>
