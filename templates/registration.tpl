<input type="hidden" id="store_key" value="{$store->id}"> 
<input type="hidden" id="site_key" value="{$site->id}"> {if $welcome} 
<div style="padding:20px">
	{include file="string:{$site->get_welcome_template()}" } 
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
				<td colspan="2">{t}Sorry, an automatic password reset could not be done, try later or call us{/t}.<br><br><button style="margin-bottom:10px" id="hide_email_in_db_dialog3">Close</button></td>
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
				<td class="label">{t}Country{/t}: </td>
				<td> 
				<select size="1" id="register_address_country_2alpha_code">
					<option value="XX">{t}Select One{/t}</option>
					<option value="GB">{t}United Kingdom{/t}</option>
					<option value="XX">----------</option>
					<option value="AF">{t}Afghanistan{/t}</option>
					<option value="AL">{t}Albania{/t}</option>
					<option value="DZ">{t}Algeria{/t}</option>
					<option value="AS">{t}American Samoa{/t}</option>
					<option value="AD">{t}Andorra{/t}</option>
					<option value="AO">{t}Angola{/t}</option>
					<option value="AI">{t}Anguilla{/t}</option>
					<option value="AQ">{t}Antarctica{/t}</option>
					<option value="AG">{t}Antigua and Barbuda{/t}</option>
					<option value="AR">{t}Argentina{/t}</option>
					<option value="AM">{t}Armenia{/t}</option>
					<option value="AW">{t}Aruba{/t}</option>
					<option value="AU">{t}Australia{/t}</option>
					<option value="AT">{t}Austria{/t}</option>
					<option value="AZ">{t}Azerbaidjan{/t}</option>
					<option value="BS">{t}Bahamas{/t}</option>
					<option value="BH">{t}Bahrain{/t}</option>
					<option value="BD">{t}Bangladesh{/t}</option>
					<option value="BB">{t}Barbados{/t}</option>
					<option value="BY">{t}Belarus{/t}</option>
					<option value="BE">{t}Belgium{/t}</option>
					<option value="BZ">{t}Belize{/t}</option>
					<option value="BJ">{t}Benin{/t}</option>
					<option value="BM">{t}Bermuda{/t}</option>
					<option value="BT">{t}Bhutan{/t}</option>
					<option value="BO">{t}Bolivia{/t}</option>
					<option value="BA">{t}Bosnia-Herzegovina{/t}</option>
					<option value="BW">{t}Botswana{/t}</option>
					<option value="BV">{t}Bouvet Island{/t}</option>
					<option value="BR">{t}Brazil{/t}</option>
					<option value="IO">{t}British Indian Ocean Territory{/t}</option>
					<option value="BN">{t}Brunei Darussalam{/t}</option>
					<option value="BG">{t}Bulgaria{/t}</option>
					<option value="BF">{t}Burkina Faso{/t}</option>
					<option value="BI">{t}Burundi{/t}</option>
					<option value="KH">{t}Cambodia{/t}</option>
					<option value="CM">{t}Cameroon{/t}</option>
					<option value="CA">{t}Canada{/t}</option>
					<option value="CV">{t}Cape Verde{/t}</option>
					<option value="KY">{t}Cayman Islands{/t}</option>
					<option value="CF">{t}Central African Republic{/t}</option>
					<option value="TD">{t}Chad{/t}</option>
					<option value="CL">{t}Chile{/t}</option>
					<option value="CN">{t}China{/t}</option>
					<option value="CX">{t}Christmas Island{/t}</option>
					<option value="CC">{t}Cocos (Keeling) Islands{/t}</option>
					<option value="CO">{t}Colombia{/t}</option>
					<option value="KM">{t}Comoros{/t}</option>
					<option value="CG">{t}Congo{/t}</option>
					<option value="CK">{t}Cook Islands{/t}</option>
					<option value="CR">{t}Costa Rica{/t}</option>
					<option value="HR">{t}Croatia{/t}</option>
					<option value="CU">{t}Cuba{/t}</option>
					<option value="CY">{t}Cyprus{/t}</option>
					<option value="CZ">{t}Czech Republic{/t}</option>
					<option value="DK">{t}Denmark{/t}</option>
					<option value="DJ">{t}Djibouti{/t}</option>
					<option value="DM">{t}Dominica{/t}</option>
					<option value="DO">{t}Dominican Republic{/t}</option>
					<option value="TP">{t}East Timor{/t}</option>
					<option value="EC">{t}Ecuador{/t}</option>
					<option value="EG">{t}Egypt{/t}</option>
					<option value="SV">{t}El Salvador{/t}</option>
					<option value="GQ">{t}Equatorial Guinea{/t}</option>
					<option value="ER">{t}Eritrea{/t}</option>
					<option value="EE">{t}Estonia{/t}</option>
					<option value="ET">{t}Ethiopia{/t}</option>
					<option value="FK">{t}Falkland Islands{/t}</option>
					<option value="FO">{t}Faroe Islands{/t}</option>
					<option value="FJ">{t}Fiji{/t}</option>
					<option value="FI">{t}Finland{/t}</option>
					<option value="CS">{t}Former Czechoslovakia{/t}</option>
					<option value="SU">{t}Former USSR{/t}</option>
					<option value="FR">{t}France{/t}</option>
					<option value="FX">{t}France (European Territory){/t}</option>
					<option value="GF">{t}French Guyana{/t}</option>
					<option value="TF">{t}French Southern Territories{/t}</option>
					<option value="GA">{t}Gabon{/t}</option>
					<option value="GM">{t}Gambia{/t}</option>
					<option value="GE">{t}Georgia{/t}</option>
					<option value="DE">{t}Germany{/t}</option>
					<option value="GH">{t}Ghana{/t}</option>
					<option value="GI">{t}Gibraltar{/t}</option>
					<option value="GB">{t}Great Britain{/t}</option>
					<option value="GR">{t}Greece{/t}</option>
					<option value="GL">{t}Greenland{/t}</option>
					<option value="GD">{t}Grenada{/t}</option>
					<option value="GP">{t}Guadeloupe (French){/t}</option>
					<option value="GU">{t}Guam (USA){/t}</option>
					<option value="GT">{t}Guatemala{/t}</option>
					<option value="GN">{t}Guinea{/t}</option>
					<option value="GW">{t}Guinea Bissau{/t}</option>
					<option value="GY">{t}Guyana{/t}</option>
					<option value="HT">{t}Haiti{/t}</option>
					<option value="HM">{t}Heard and McDonald Islands{/t}</option>
					<option value="HN">{t}Honduras{/t}</option>
					<option value="HK">{t}Hong Kong{/t}</option>
					<option value="HU">{t}Hungary{/t}</option>
					<option value="IS">{t}Iceland{/t}</option>
					<option value="IN">{t}India{/t}</option>
					<option value="ID">{t}Indonesia{/t}</option>
					<option value="INT">{t}International{/t}</option>
					<option value="IR">{t}Iran{/t}</option>
					<option value="IQ">{t}Iraq{/t}</option>
					<option value="IE">{t}Ireland{/t}</option>
					<option value="IL">{t}Israel{/t}</option>
					<option value="IT">{t}Italy{/t}</option>
					<option value="CI">{t}Ivory Coast (Cote D&#39;Ivoire){/t}</option>
					<option value="JM">{t}Jamaica{/t}</option>
					<option value="JP">{t}Japan{/t}</option>
					<option value="JO">{t}Jordan{/t}</option>
					<option value="KZ">{t}Kazakhstan{/t}</option>
					<option value="KE">{t}Kenya{/t}</option>
					<option value="KI">{t}Kiribati{/t}</option>
					<option value="KW">{t}Kuwait{/t}</option>
					<option value="KG">{t}Kyrgyzstan{/t}</option>
					<option value="LA">{t}Laos{/t}</option>
					<option value="LV">{t}Latvia{/t}</option>
					<option value="LB">{t}Lebanon{/t}</option>
					<option value="LS">{t}Lesotho{/t}</option>
					<option value="LR">{t}Liberia{/t}</option>
					<option value="LY">{t}Libya{/t}</option>
					<option value="LI">{t}Liechtenstein{/t}</option>
					<option value="LT">{t}Lithuania{/t}</option>
					<option value="LU">{t}Luxembourg{/t}</option>
					<option value="MO">{t}Macau{/t}</option>
					<option value="MK">{t}Macedonia{/t}</option>
					<option value="MG">{t}Madagascar{/t}</option>
					<option value="MW">{t}Malawi{/t}</option>
					<option value="MY">{t}Malaysia{/t}</option>
					<option value="MV">{t}Maldives{/t}</option>
					<option value="ML">{t}Mali{/t}</option>
					<option value="MT">{t}Malta{/t}</option>
					<option value="MH">{t}Marshall Islands{/t}</option>
					<option value="MQ">{t}Martinique (French){/t}</option>
					<option value="MR">{t}Mauritania{/t}</option>
					<option value="MU">{t}Mauritius{/t}</option>
					<option value="YT">{t}Mayotte{/t}</option>
					<option value="MX">{t}Mexico{/t}</option>
					<option value="FM">{t}Micronesia{/t}</option>
					<option value="MD">{t}Moldavia{/t}</option>
					<option value="MC">{t}Monaco{/t}</option>
					<option value="MN">{t}Mongolia{/t}</option>
					<option value="MS">{t}Montserrat{/t}</option>
					<option value="MA">{t}Morocco{/t}</option>
					<option value="MZ">{t}Mozambique{/t}</option>
					<option value="MM">{t}Myanmar{/t}</option>
					<option value="NA">{t}Namibia{/t}</option>
					<option value="NR">{t}Nauru{/t}</option>
					<option value="NP">{t}Nepal{/t}</option>
					<option value="NL">{t}Netherlands{/t}</option>
					<option value="AN">{t}Netherlands Antilles{/t}</option>
					<option value="NT">{t}Neutral Zone{/t}</option>
					<option value="NC">{t}New Caledonia (French){/t}</option>
					<option value="NZ">{t}New Zealand{/t}</option>
					<option value="NI">{t}Nicaragua{/t}</option>
					<option value="NE">{t}Niger{/t}</option>
					<option value="NG">{t}Nigeria{/t}</option>
					<option value="NU">{t}Niue{/t}</option>
					<option value="NF">{t}Norfolk Island{/t}</option>
					<option value="KP">{t}North Korea{/t}</option>
					<option value="MP">{t}Northern Mariana Islands{/t}</option>
					<option value="NO">{t}Norway{/t}</option>
					<option value="OM">{t}Oman{/t}</option>
					<option value="PK">{t}Pakistan{/t}</option>
					<option value="PW">{t}Palau{/t}</option>
					<option value="PA">{t}Panama{/t}</option>
					<option value="PG">{t}Papua New Guinea{/t}</option>
					<option value="PY">{t}Paraguay{/t}</option>
					<option value="PE">{t}Peru{/t}</option>
					<option value="PH">{t}Philippines{/t}</option>
					<option value="PN">{t}Pitcairn Island{/t}</option>
					<option value="PL">{t}Poland{/t}</option>
					<option value="PF">{t}Polynesia (French){/t}</option>
					<option value="PT">{t}Portugal{/t}</option>
					<option value="PR">{t}Puerto Rico{/t}</option>
					<option value="QA">{t}Qatar{/t}</option>
					<option value="RE">{t}Reunion (French){/t}</option>
					<option value="RO">{t}Romania{/t}</option>
					<option value="RU">{t}Russian Federation{/t}</option>
					<option value="RW">{t}Rwanda{/t}</option>
					<option value="GS">{t}S. Georgia & S. Sandwich I.{/t}</option>
					<option value="SH">{t}Saint Helena{/t}</option>
					<option value="KN">{t}Saint Kitts & Nevis Anguilla{/t}</option>
					<option value="LC">{t}Saint Lucia{/t}</option>
					<option value="PM">{t}St Pierre and Miquelon{/t}</option>
					<option value="ST">{t}St Tome & Principe{/t}</option>
					<option value="VC">{t}St Vincent & Grenadines{/t}</option>
					<option value="WS">{t}Samoa{/t}</option>
					<option value="SM">{t}San Marino{/t}</option>
					<option value="SA">{t}Saudi Arabia{/t}</option>
					<option value="SN">{t}Senegal{/t}</option>
					<option value="SC">{t}Seychelles{/t}</option>
					<option value="SL">{t}Sierra Leone{/t}</option>
					<option value="SG">{t}Singapore{/t}</option>
					<option value="SK">{t}Slovak Republic{/t}</option>
					<option value="SI">{t}Slovenia{/t}</option>
					<option value="SB">{t}Solomon Islands{/t}</option>
					<option value="SO">{t}Somalia{/t}</option>
					<option value="ZA">{t}South Africa{/t}</option>
					<option value="KR">{t}South Korea{/t}</option>
					<option value="ES">{t}Spain{/t}</option>
					<option value="LK">{t}Sri Lanka{/t}</option>
					<option value="SD">{t}Sudan{/t}</option>
					<option value="SR">{t}Suriname{/t}</option>
					<option value="SJ">{t}Svalbard and Jan Mayen I.{/t}</option>
					<option value="SZ">{t}Swaziland{/t}</option>
					<option value="SE">{t}Sweden{/t}</option>
					<option value="CH">{t}Switzerland{/t}</option>
					<option value="SY">{t}Syria{/t}</option>
					<option value="TJ">{t}Tadjikistan{/t}</option>
					<option value="TW">{t}Taiwan{/t}</option>
					<option value="TZ">{t}Tanzania{/t}</option>
					<option value="TH">{t}Thailand{/t}</option>
					<option value="TG">{t}Togo{/t}</option>
					<option value="TK">{t}Tokelau{/t}</option>
					<option value="TO">{t}Tonga{/t}</option>
					<option value="TT">{t}Trinidad and Tobago{/t}</option>
					<option value="TN">{t}Tunisia{/t}</option>
					<option value="TR">{t}Turkey{/t}</option>
					<option value="TM">{t}Turkmenistan{/t}</option>
					<option value="TC">{t}Turks and Caicos Islands{/t}</option>
					<option value="TV">{t}Tuvalu{/t}</option>
					<option value="UG">{t}Uganda{/t}</option>
					<option value="UA">{t}Ukraine{/t}</option>
					<option value="AE">{t}United Arab Emirates{/t}</option>
					<option value="GB">{t}United Kingdom{/t}</option>
					<option value="US">{t}United States{/t}</option>
					<option value="UY">{t}Uruguay{/t}</option>
					<option value="UM">{t}USA Minor Outlying Islands{/t}</option>
					<option value="UZ">{t}Uzbekistan{/t}</option>
					<option value="VU">{t}Vanuatu{/t}</option>
					<option value="VA">{t}Vatican City State{/t}</option>
					<option value="VE">{t}Venezuela{/t}</option>
					<option value="VN">{t}Vietnam{/t}</option>
					<option value="VG">{t}Virgin Islands (British){/t}</option>
					<option value="VI">{t}Virgin Islands (USA){/t}</option>
					<option value="WF">{t}Wallis and Futuna Islands{/t}</option>
					<option value="EH">{t}Western Sahara{/t}</option>
					<option value="YE">{t}Yemen{/t}</option>
					<option value="YU">{t}Yugoslavia{/t}</option>
					<option value="ZR">{t}Zaire{/t}</option>
					<option value="ZM">{t}Zambia{/t}</option>
					<option value="ZW">{t}Zimbabwe{/t}</option>
				</select>
				</td>
			</tr>
			<tr class="title">
				<td colspan="3">Other: </td>
			</tr>
			{if isset($categories)} {foreach from=$categories item=cat key=cat_key name=foo } 
			<tr>
				<td class="label">{t}{$cat->get('Category Label')}{/t}:</td>
				<td> 
				<select id="cat{$cat_key}" cat_key="{$cat_key}" onchange="update_category(this)">
					{foreach from=$cat->get_children_objects_public_new_subject() item=sub_cat key=sub_cat_key name=foo2 } {if $smarty.foreach.foo2.first} 
					<option value="">{t}Unknown{/t}</option>
					{/if} ` 
					<option other="{if $sub_cat->get('Is Category Field Other')=='Yes'}{t}true{/t}{else}{t}false{/t}{/if}" value="{$sub_cat->get('Category Key')}">{$sub_cat->get('Category Name')}</option>
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
	</div>
	<div id="message_register_fields_missing" class="warning_block" style="display:none;width:300px;float:left;margin-left:30px;margin-bottom:10px">
		{t}Fill all fields please{/t}. 
	</div>
	<div id="register_error_password_not_march" class="warning_block" style="display:none;width:300px;float:left;margin-left:30px;margin-bottom:10px">
		{t}Passwords don't match{/t}. 
	</div>
	<div id="register_error_password_too_short" class="warning_block" style="display:none;width:300px;float:left;margin-left:30px;margin-bottom:10px">
		{t}Password is too short{/t}. 
	</div>
	<div id="message_register_error_captcha" class="warning_block" style="display:none;width:300px;float:left;margin-left:30px;margin-bottom:10px">
		{t}The Captcha field is incorrect{/t}. 
	</div>
	<div id="processing_register" class="info_block" style="display:none;width:300px;float:left;margin-left:30px;margin-bottom:10px">
		<img style="vertical-align:top" src="art/loading.gif" alt=""> {t}Creating your account{/t} 
	</div>
	<div id="message_register_error" class="info_block" style="display:none;width:300px;float:left;margin-left:30px;margin-bottom:10px">
		{t}An error incurred during your registration please try again later{/t}. 
	</div>
</div>
<div style="clear:both;margin-bottom:30px">
</div>
{/if}