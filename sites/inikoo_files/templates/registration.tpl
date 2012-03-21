<input type="hidden" id="store_key" value="{$store->id}"> 
<input type="hidden" id="site_key" value="{$site->id}"> 
{if $welcome } 
<div style="padding:20px">
	{include file="string:{$site->get_welcome_template()}"} 
</div>
{else} 
<div id="dialog_check_email" class="dialog_inikoo">
	<h2>
		{t}Registration{/t} 
	</h2>
	<div style="border:1px solid #ccc;padding:20px;width:500px;float:left">
		<table border="0">
			<tr>
				<td class="label">{t}Email{/t}:</td>
				<td> 
				<input id="check_email" />
				</td>
			</tr>
			<tr class="button space">
				<td colspan="2"> 
				<div class="buttons">
					<button id="submit_check_email" class="positive">{t}Continue{/t}</button> 
				</div>
				</td>
			</tr>
		</table>
	</div>
	<div id="message_check_email_fields_missing" class="warning_block" style="display:none;width:300px;float:left;margin-left:30px;margin-bottom:10px">
		{t}We need your email address{/t}. 
	</div>
	<div id="message_check_email_wrong_email" class="warning_block" style="display:none;width:300px;float:left;margin-left:30px;margin-bottom:10px">
		{t}Email address invalid{/t}. 
	</div>
</div>
<div id="dialog_email_in_db" class="dialog_inikoo" style="display:none">
	<h2>
		{t}Email already in our Database{/t} 
	</h2>
	<p id="email_in_db_instructions" style="width:440px;margin-bottom:10px">
		<span id="email_in_db"></span> {t}is already in our records, fill the anti-spam field and we will send you an email with instructions of how to access your account{/t}. 
	</p>
	<div style="float:left;border:1px solid #ccc;padding:20px;width:500px">
		<table border="0" style="margin:0 auto;">
			<tr id="tr_email_in_db_captcha">
				<td class="label" style="text-align:left"> <img id="captcha3" src="art/x.png" alt="CAPTCHA Image" /> <br> <a class="captcha_show_other" href="#" onclick="document.getElementById('captcha3').src ='securimage_show.php?'+ Math.random(); return false">{t}Different Image{/t}</a> </td>
				<td style="vertical-align:top"> <span class="captcha_instructions">{t}input the letters shown on the left{/t}</span><br />
				<input type="text" id="captcha_code3" name="captcha_code" style="width:50%" />
				</td>
			</tr>
			<tr id="tr_email_in_db_buttons" class="button space">
				<td colspan="2"> 
				<div class="buttons">
					<button id="submit_forgot_password_from_email_in_db" class="positive">{t}Send Instructions{/t}</button> <button id="hide_email_in_db_dialog" class="negative">{t}Cancel{/t}</button> 
				</div>
				</td>
			</tr>
			<tr id="tr_forgot_password_wait2" style="display:none" class="button">
				<td colspan="2"><img style="weight:24px" src="art/loading.gif"> <span style="position:relative;top:-5px">{t}Processing request{/t}</span></td>
			</tr>
			<tr id="tr_forgot_password_send2" style="display:none" class="button" style="">
				<td colspan="2"> 
				<p style="width:300px">
					{t}An email has been sent to you with instructions how to access your account{/t}
				</p>
				<div style="margin-top:20px" class="buttons"><button  id="hide_email_in_db_dialog2">{t}Close{/t}</button>
				</div>
				</td>
			</tr>
			<tr id="tr_forgot_password_error2" style="display:none" class="button" style="">
				<td colspan="2">{t}Sorry, an automatic password reset could not be done, try later or call us{/t}.<br><div class="buttons"><button  id="hide_email_in_db_dialog3">{t}Close{/t}</button></div></td>
			</tr>
		</table>
	</div>
	<div id="message_email_in_db_missing_captcha" class="warning_block" style="display:none;width:300px;float:left;margin-left:30px;margin-bottom:10px">
		{t}Please fill the Captcha{/t}. 
	</div>
</div>
<div id="dialog_register" class="dialog_inikoo" style="position:relative; display:none;margin-bottom:60px">
	<input id="epw2" value="" type="hidden" />
	<h2>
		{t}Registration{/t} 
	</h2>
	<div style="border:1px solid #ccc;padding:20px;width:500px;float:left">
		<table border="0" class="edit">
			<tbody>
				<tr class="title">
					<td colspan="3">{t}Login info{/t}: </td>
				</tr>
				<tr>
					<td class="label">{t}Email{/t}: </td>
					<td id="confirmed_register_email"></td>
					<td style="width:40px"></td>
				</tr>
				<tr>
					<td class="label">{t}Password{/t}: </td>
					<td> 
					<input type="password" id="register_password1"></td>
				</tr>
				<tr>
					<td class="label">{t}Confirm pwd{/t}: </td>
					<td> 
					<input type="password" id="register_password2"></td>
				</tr>
			</tbody>
			<tr class="title">
				<td colspan="3">{t}Contact Info{/t}: </td>
			</tr>
			<tr>
				<td class="label">{t}Contact Name{/t}: </td>
				<td> 
				<input id="register_contact_name"></td>
			</tr>
			<tr>
				<td class="label">{t}Company{/t}: </td>
				<td> 
				<input id="register_company_name"></td>
			</tr>
			<tr>
				<td class="label">{t}Registration Number{/t}: </td>
				<td> 
				<input id="register_registration_number"></td>
			</tr>
	<tr>
				<td class="label">{t}VAT Number{/t}: </td>
				<td> 
				<input id="register_tax_number"></td>
			</tr>		
			<tr>
				<td class="label">{t}Telephone{/t}: </td>
				<td> 
				<input id="register_telephone"></td>
			</tr>
			<tr class="title">
				<td colspan="3">{t}Address{/t}: </td>
			</tr>
			<tr>
				<td class="label">{t}Line 1{/t}: </td>
				<td> 
				<input id="register_address_line1" />
				</td>
			</tr>
			<tr>
				<td class="label">{t}Line 2{/t}: </td>
				<td> 
				<input id="register_address_line2" />
				</td>
			</tr>
			<tr>
				<td class="label">{t}Town{/t}: </td>
				<td> 
				<input id="register_address_town" />
				</td>
			</tr>
			<tr>
				<td class="label">{t}Postal Code{/t}: </td>
				<td> 
				<input id="register_address_postcode" />
				</td>
			</tr>
			<tr>
				<td class="label">{t}Country{/t}:</td>
				<td> 
				<select size="1" id="register_address_country_2alpha_code">
					<option value="XX">{t}Select One{/t}</option>
					{if $site->get('Site Locale')=='en_GB'}
					
					<option value="GB">{t}United Kingdom{/t}</option>
					<option value="IE">{t}Ireland{/t}</option>
					<option value="XX">----------</option>
					{include file="country_select.tpl"}
					
					{elseif $site->get('Site Locale')=='de_DE'}
					<option value="DE">{t}Germany{/t}</option>
					<option value="AT">{t}Austria{/t}</option>
					<option value="CH">{t}Switzerland{/t}</option>
					<option value="NL">{t}Netherlands{/t}</option>
					<option value="XX">----------</option>
					{include file="country_select.de_DE.tpl"}
					{else}
					<option value="XX">----------</option>
					{include file="country_select.tpl"}
					{/if}
					
						
				</select>
				</td>
			</tr>
			<tr class="title">
				<td colspan="3">{t}Other Details{/t}: </td>
			</tr>
			{if isset($categories)} {foreach from=$categories item=cat key=cat_key name=foo } 
			<tr>
				<td class="label">{t}{$cat->get('Category Label')}{/t}:</td>
				<td> 
				<select id="cat{$cat_key}" cat_key="{$cat_key}" onchange="update_category(this)">
					{foreach from=$cat->get_children_objects_public_new_subject() item=sub_cat key=sub_cat_key name=foo2 } {if $smarty.foreach.foo2.first} 
					<option value="">{t}Unknown{/t}</option>
					{/if} ` 
					<option other="{if $sub_cat->get('Is Category Field Other')=='Yes'}{t}true{/t}{else}{t}false{/t}{/if}" value="{$sub_cat->get('Category Key')}">{$sub_cat->get('Category Label')}</option>
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
			{/foreach} {/if} 
			<tr>
				<td class="label">{t}Catalogue (post){/t}: </td>
				<td> 
				<input checked="checked" onchange="change_allow(this)" style="width:10px;border:none" type="radio" name="catalogue" value="Yes"> {t}Yes{/t} 
				<input onchange="change_allow(this)" style="width:10px;border:none" type="radio" name="catalogue" value="No"> {t}No{/t} </td>
			</tr>
			<tr>
				<td class="label">{t}Offers by email & e-Newsletter{/t}: </td>
				<td style="vertical-align:top;"> 
				<input checked="checked" onchange="change_allow(this)" style="border:none;width:10px" type="radio" name="newsletter" value="Yes"> {t}Yes{/t} 
				<input onchange="change_allow(this)" style="width:10px;border:none" type="radio" name="newsletter" value="No"> {t}No{/t} </td>
			</tr>
			<tr class="title">
				<td colspan="3">CAPTCHA: </td>
			</tr>
			<tr>
				<td class="label"> <img id="captcha" src="art/x.png" /> <br> <a href="#" style="color:black" onclick="document.getElementById('captcha').src = 'securimage_show.php?' + Math.random(); return false">{t}Different Image{/t}</a> </td>
				<td> 
				<input type="text" id="captcha_code" name="captcha_code" style="width:50%" />
				</td>
			</tr>
			<tr id="tr_register_part_2_buttons" class="button space">
				<td colspan="2"> 
				<div class="buttons">
					<button id="submit_register" class="positive">{t}Register{/t}</button> <button id="cancel_register" class="negative">{t}Cancel{/t}</button> 
				</div>
				</td>
			</tr>
			<tr id="tr_register_part_2_wait" style="display:none" class="button">
				<td colspan="2"><img style="weight:24px" src="art/loading.gif"> <span style="position:relative;top:-5px">{t}Creating your account{/t}</span></td>
			</tr>
		</table>
		
	<div style="margin-top:20px">	
	<div id="message_register_fields_missing" class="warning_block" style="display:none;width:300px;float:right;margin-left:30px;margin-bottom:10px">
		{t}Fill all fields please{/t}. 
	</div>
	<div id="register_error_password_not_march" class="warning_block" style="display:none;width:300px;float:right;margin-left:30px;margin-bottom:10px">
		{t}Passwords don't match{/t}. 
	</div>
	<div id="register_error_password_too_short" class="warning_block" style="display:none;width:300px;float:right;margin-left:30px;margin-bottom:10px">
		{t}Password is too short{/t}. 
	</div>
	<div id="message_register_error_captcha" class="warning_block" style="display:none;width:300px;float:right;margin-left:30px;margin-bottom:10px">
		{t}The Captcha field is incorrect{/t}. 
	</div>
	<div id="processing_register" class="info_block" style="display:none;width:300px;float:right;margin-left:30px;margin-bottom:10px">
		<img style="vertical-align:top" src="art/loading.gif" alt=""> {t}Creating your account{/t} 
	</div>
	<div id="message_register_error" class="info_block" style="display:none;width:300px;float:right;margin-left:30px;margin-bottom:10px">
		{t}An error incurred during your registration please try again later{/t}. 
	</div>
</div>
	</div>

</div>
<div style="clear:both;margin-bottom:30px">
</div>
{/if}