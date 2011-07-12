	{include file='header.tpl'}
<input type="hidden" id="Custom_Field_Store_Key" value="{$store_key}">
<input type="hidden" id="Custom_Field_Table" value="Customer">


<div id="bd" style="padding:0 20px">
<h1>{t}Customer Store Configuration{/t}</h1>



<h3>Adding new custom field</h3>
<div style="clear:both;margin-top:0px;margin-right:0px;width:{if $options_box_width}{$options_box_width}{else}700px{/if};float:right;margin-bottom:10px" class="right_box">
  <div class="general_options">
    {foreach from=$general_options_list item=options }
    {if $options.tipo=="url"}
    <span onclick="window.location.href='{$options.url}'" >{$options.label}</span>
    {else}
    <span  id="{$options.id}" state="{$options.state}">{$options.label}</span>
    {/if}
    {/foreach}
  </div>
</div>

<div id="yui-main" >
    
    
    
 
  <div class="search_box" ></div>
  <div   id="contact_messages_div" >
      <span id="contact_messages"></span>
    </div>
  <div >
     <div id="results" style="margin-top:0px;float:right;width:390px;"></div>
      
      <div  style="float:left;width:540px;" >
      
      
      <table class="edit"  border=0 style="width:100%;margin-bottom:0px" >
      <input type="hidden" value="{$store_key}" id="Store_Key"/>
      <input type="hidden" value="{$customer_type}" id="Customer_Type"/>
	  
	  
	<tbody id="company_section">

      
  


	
	<tr class="first">
	<td style="width:120px" class="label">{t}Field Name{/t}:</td>
	  <td  style="text-align:left;width:350px">
	    <div  style="" >
	      <input style="text-align:left;" id="Custom_Field_Name" value="" ovalue="" valid="0">
	      <div id="Custom_Field_Name_Container" style="" ></div>
	    </div>
	  </td>
	  <td style="width:70px"></td>
	  
	</tr>
	<tr>
		<td style="width:120px" class="label">{t}Default Value{/t}:</td>
	  <td  style="text-align:left;width:350px">
	    <div  style="" >
	      <input style="text-align:left;" id="Default_Value" value="" ovalue="" valid="0">
	      <div id="Default_Value_Container" style="" ></div>
	    </div>
	  </td>
	  <td style="width:70px"></td>
	  
	</tr>
	
	<tr>
	 <td class="label" style="width:200px">{t}Custom Field Type{/t}:</td>
	 <input type="hidden" value="varchar" id="Custom_Field_Type"  />
	 <input type="hidden" value="Yes" id="Custom_Field_In_New_Subject"  />
	 <input type="hidden" value="Yes" id="Custom_Field_In_Showcase"  />
	 
	 <td>
	   <div  class="options" style="margin:0">
	   <span class="option selected" onclick="change_allow(this,'Custom_Field_Type','varchar')" >{t}String{/t}</span> 
	   <span class="option" onclick="change_allow(this,'Custom_Field_Type','Mediumint')" >{t}Integer{/t}</span>
	   </div>
	 </td>
	 </tr>
	 
	  <tr>
	 <td class="label" style="width:400px">{t}Custom Field In New Subject{/t}:</td>
	 <td>
	   <div class="options" style="margin:0">
	   <span class="option selected" onclick="change_allow(this,'Custom_Field_In_New_Subject','Yes')" >{t}Yes{/t}</span> 
	   <span class="option" onclick="change_allow(this,'Custom_Field_In_New_Subject','No')" >{t}No{/t}</span>
	   </div>
	 </td>
	 </tr>

	 <tr>
	 <td class="label" style="width:300px">{t}Custom Field In Showcase{/t}:</td>
	 <td>
	   <div class="options" style="margin:0">
	   <span class="option selected" onclick="change_allow(this,'Custom_Field_In_Showcase','Yes')" >{t}Yes{/t}</span> 
	   <span class="option" onclick="change_allow(this,'Custom_Field_In_Showcase','No')" >{t}No{/t}</span>
	   </div>
	 </td>
	 </tr>
	
	 </tbody>



{foreach from=$categories item=cat key=cat_key name=foo  }
 <tr>
 
 <td class="label">{t}{$cat->get('Category Label')}{/t}:</td>
 <td>
  <select id="cat{$cat_key}" cat_key="{$cat_key}"  onChange="update_category(this)">
    {foreach from=$cat->get_children_objects() item=sub_cat key=sub_cat_key name=foo2  }
        {if $smarty.foreach.foo2.first}
        <option  value="">{t}Unknown{/t}</option>
        {/if}
        <option value="{$sub_cat->get('Category Key')}">{$sub_cat->get('Category Label')}</option>
    {/foreach}
  </select>
  
 </td>   
</tr>
{/foreach}

    
    </table>
      <table class="options" border=0 style="font-size:120%;margin-top:20px;;float:right;padding:0">
	<tr>
		<td   id="creating_message" style="border:none;display:none">{t}Creating Contact{/t}</td>

	  <td  class="disabled" id="save_new_custom_field">{t}Save{/t}</td>
	  <td  id="cancel_add_custom_field">{t}Cancel{/t}</td>
	</tr>
      </table>
      <div id="Customer_found_dialog" style="display:none;float:right;border:1px solid #ccc;width:200px;padding:6px 10px;margin-top:3px;font-size:80%;color:#555">
	{t}Another contact has been found with the similar details{/t}.
	<table style="margin:10px 0">
	  <tr><td><span  style="cursor:pointer;text-decoration:underline" onClick="edit_founded()"    id="pick_founded">{t}Edit the Customer found{/t} (<span id="founded_name"></span>)</span></td></tr>
	  <tr><td><span style="color:red">{t}Creating this customer is likely to produce duplicate contacts.{/t}</span></br<span  style="cursor:pointer;text-decoration:underline;color:red"  id="save_when_founded" >{t}Create customer anyway{/t}</span></td></tr>

	</table>
      </div>
      <div id="email_found_dialog" style="display:none;float:right;border:1px solid #ccc;width:200px;padding:6px 10px;margin-top:3px;font-size:80%;color:#555">
	<b>{t}Another contact has the same email{/t}</b>.
	<table style="margin:10px 0">
	  <tr><td style="cursor:pointer;text-decoration:underline" onclick="edit_founded()">{t}Edit the Customer found{/t} (<span id="email_founded_name"></span>)</td></tr>
	  <tr><td><span style="color:red">{t}Creating this customer will produce duplicate contacts. The email will not be added.{/t}</span></br><span  style="cursor:pointer;text-decoration:underline;color:red" id="force_new">{t}Create customer anyway{/t}</span></td></tr>
	</table>
      </div>
      
          <div id="email_found_other_store_dialog" style="display:none;float:right;border:1px solid #ccc;width:200px;padding:6px 10px;margin-top:3px;font-size:80%;color:#555">
	<b>{t}A Customer has the same email in another store{/t}</b>.
	<table style="margin:10px 0">
	<input type="hidden" value="" id="found_email_other_store_customer_key">
	  <tr><td style="cursor:pointer;text-decoration:underline" onclick="clone_founded()">{t}Use contact data to create new customer in this store{/t}</td></tr>
	</table>
      </div>
      
      
      <div style="clear:both;padding:10px;" id="validation">

	<div style="font-size:80%;margin-bottom:10px;display:none" id="mark_Customer_found">{t}Company has been found{/t}</div>
	
      </div>

      </div>
      
      
      <div style="clear:both;height:40px"></div>
	</div>
    
	<hr/>
	
	
<h3>Adding new custom field</h3>
	<div  style="float:left;width:540px;" >
	<table class="edit"  border=0 style="width:100%;margin-bottom:0px" >
	<tr>
	<td>Source Code: </td>
	<td><textarea name="Message"cols=60 rows=30 wrap=virtual> 
	{literal}
	<html>
<body>
<form method="post" action="external_form_store.php"> 
    
   <input type="hidden" name="store_key" value="1">
   <input type="hidden" name="scope" value="customers_store">
    
    <!-- Display our form fields--> 
    <table width="500" align="center" border="0"> 
    
    <tr> 
    <td width="70" valign="top"> 
    <font face="verdana" size="1"> 
    <b> *  Name:</b> 
    </font> 
    </td> 
    <td> 
    <input type="text" size="40" name="name_from"> 
    </td> 
    </tr> 
    <tr> 
    <td width="70" valign="top"> 
    <font face="verdana" size="1"> 
    <b>Company:</b> 
    </font> 
    </td> 
    <td> 
    <input type="text" size="40" name="Senders_Company"> 
    </td> 
    </tr> 
    
    <tr>

    <td><font face="verdana" size="1"> <b>Type of Buseness:</b></font></td>
    <td>
    <select name="Senders_Business">
      <option value="">Select</option>
      <option value="Craft Fairs">Craft Fairs</option>
      <option value="Department Store">Department Store</option>

      <option value="Ebay Seller">Ebay Seller</option>
    <option value="Florists">Florists</option>
      <option value="Garden Centre">Garden Centre</option>
    <option value="Gift Shop">Gift Shop</option>
    <option value="Internet Shop">Internet Shop</option>
    <option value="Market Trader">Market Trader</option>

    <option value="Party Planner">Party Planner</option>
    <option value="Tourist Attraction">Tourist Attraction</option>
    <option value="Wedding Planner">Wedding Planner</option>
    <option value="Wholesaler">Wholesaler</option>
    <option value="Other">Other Business</option>
    </select>

    </td>
    </tr>
    
    <tr> 
    <td width="70" valign="top"> 
    <font face="verdana" size="1"> 
    <b>*Address:</b> 
    </font> 
    </td> 
    <td> 
    <input type="text" size="40" name="Address"> 
    </td> 
    </tr> 

    <tr> 
    <td width="70" valign="top"> 
    <font face="verdana" size="1"> 
    <b>City</b> 
    </font> 
    </td> 
    <td> 
    <input type="text" size="40" name="City"> 
    </td> 
    </tr>

    <tr> 
    <td width="70" valign="top"> 
    <font face="verdana" size="1"> 
    <b>County</b> 
    </font> 
    </td> 
    <td> 
    <input type="text" size="40" name="County"> 
    </td> 
    </tr>
    <tr> 
    <td width="70" valign="top"> 
    <font face="verdana" size="1"> 
    <b>Country</b> 
    </font> 
    </td> 
    <td> 
    <select name="Country"> 
    <option value="" selected="selected">Select Country</option> 
    <option value="United States">United States</option> 
    <option value="United Kingdom">United Kingdom</option> 
    <option value="Afghanistan">Afghanistan</option> 
    <option value="Albania">Albania</option> 
    <option value="Algeria">Algeria</option> 
    <option value="American Samoa">American Samoa</option> 
    <option value="Andorra">Andorra</option> 
    <option value="Angola">Angola</option> 
    <option value="Anguilla">Anguilla</option> 
    <option value="Antarctica">Antarctica</option> 
    <option value="Antigua and Barbuda">Antigua and Barbuda</option> 
    <option value="Argentina">Argentina</option> 
    <option value="Armenia">Armenia</option> 
    <option value="Aruba">Aruba</option> 
    <option value="Australia">Australia</option> 
    <option value="Austria">Austria</option> 
    <option value="Azerbaijan">Azerbaijan</option> 
    <option value="Bahamas">Bahamas</option> 
    <option value="Bahrain">Bahrain</option> 
    <option value="Bangladesh">Bangladesh</option> 
    <option value="Barbados">Barbados</option> 
    <option value="Belarus">Belarus</option> 
    <option value="Belgium">Belgium</option> 
    <option value="Belize">Belize</option> 
    <option value="Benin">Benin</option> 
    <option value="Bermuda">Bermuda</option> 
    <option value="Bhutan">Bhutan</option> 
    <option value="Bolivia">Bolivia</option> 
    <option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option> 
    <option value="Botswana">Botswana</option> 
    <option value="Bouvet Island">Bouvet Island</option> 
    <option value="Brazil">Brazil</option> 
    <option value="British Indian Ocean Territory">British Indian Ocean Territory</option> 
    <option value="Brunei Darussalam">Brunei Darussalam</option> 
    <option value="Bulgaria">Bulgaria</option> 
    <option value="Burkina Faso">Burkina Faso</option> 
    <option value="Burundi">Burundi</option> 
    <option value="Cambodia">Cambodia</option> 
    <option value="Cameroon">Cameroon</option> 
    <option value="Canada">Canada</option> 
    <option value="Cape Verde">Cape Verde</option> 
    <option value="Cayman Islands">Cayman Islands</option> 
    <option value="Central African Republic">Central African Republic</option> 
    <option value="Chad">Chad</option> 
    <option value="Chile">Chile</option> 
    <option value="China">China</option> 
    <option value="Christmas Island">Christmas Island</option> 
    <option value="Cocos (Keeling) Islands">Cocos (Keeling) Islands</option> 
    <option value="Colombia">Colombia</option> 
    <option value="Comoros">Comoros</option> 
    <option value="Congo">Congo</option> 
    <option value="Congo, The Democratic Republic of The">Congo, The Democratic Republic of The</option> 
    <option value="Cook Islands">Cook Islands</option> 
    <option value="Costa Rica">Costa Rica</option> 
    <option value="Cote D'ivoire">Cote D'ivoire</option> 
    <option value="Croatia">Croatia</option> 
    <option value="Cuba">Cuba</option> 
    <option value="Cyprus">Cyprus</option> 
    <option value="Czech Republic">Czech Republic</option> 
    <option value="Denmark">Denmark</option> 
    <option value="Djibouti">Djibouti</option> 
    <option value="Dominica">Dominica</option> 
    <option value="Dominican Republic">Dominican Republic</option> 
    <option value="Ecuador">Ecuador</option> 
    <option value="Egypt">Egypt</option> 
    <option value="El Salvador">El Salvador</option> 
    <option value="Equatorial Guinea">Equatorial Guinea</option> 
    <option value="Eritrea">Eritrea</option> 
    <option value="Estonia">Estonia</option> 
    <option value="Ethiopia">Ethiopia</option> 
    <option value="Falkland Islands (Malvinas)">Falkland Islands (Malvinas)</option> 
    <option value="Faroe Islands">Faroe Islands</option> 
    <option value="Fiji">Fiji</option> 
    <option value="Finland">Finland</option> 
    <option value="France">France</option> 
    <option value="French Guiana">French Guiana</option> 
    <option value="French Polynesia">French Polynesia</option> 
    <option value="French Southern Territories">French Southern Territories</option> 
    <option value="Gabon">Gabon</option> 
    <option value="Gambia">Gambia</option> 
    <option value="Georgia">Georgia</option> 
    <option value="Germany">Germany</option> 
    <option value="Ghana">Ghana</option> 
    <option value="Gibraltar">Gibraltar</option> 
    <option value="Greece">Greece</option> 
    <option value="Greenland">Greenland</option> 
    <option value="Grenada">Grenada</option> 
    <option value="Guadeloupe">Guadeloupe</option> 
    <option value="Guam">Guam</option> 
    <option value="Guatemala">Guatemala</option> 
    <option value="Guinea">Guinea</option> 
    <option value="Guinea-bissau">Guinea-bissau</option> 
    <option value="Guyana">Guyana</option> 
    <option value="Haiti">Haiti</option> 
    <option value="Heard Island and Mcdonald Islands">Heard Island and Mcdonald Islands</option> 
    <option value="Holy See (Vatican City State)">Holy See (Vatican City State)</option> 
    <option value="Honduras">Honduras</option> 
    <option value="Hong Kong">Hong Kong</option> 
    <option value="Hungary">Hungary</option> 
    <option value="Iceland">Iceland</option> 
    <option value="India">India</option> 
    <option value="Indonesia">Indonesia</option> 
    <option value="Iran, Islamic Republic of">Iran, Islamic Republic of</option> 
    <option value="Iraq">Iraq</option> 
    <option value="Ireland">Ireland</option> 
    <option value="Israel">Israel</option> 
    <option value="Italy">Italy</option> 
    <option value="Jamaica">Jamaica</option> 
    <option value="Japan">Japan</option> 
    <option value="Jordan">Jordan</option> 
    <option value="Kazakhstan">Kazakhstan</option> 
    <option value="Kenya">Kenya</option> 
    <option value="Kiribati">Kiribati</option> 
    <option value="Korea, Democratic People's Republic of">Korea, Democratic People's Republic of</option> 
    <option value="Korea, Republic of">Korea, Republic of</option> 
    <option value="Kuwait">Kuwait</option> 
    <option value="Kyrgyzstan">Kyrgyzstan</option> 
    <option value="Lao People's Democratic Republic">Lao People's Democratic Republic</option> 
    <option value="Latvia">Latvia</option> 
    <option value="Lebanon">Lebanon</option> 
    <option value="Lesotho">Lesotho</option> 
    <option value="Liberia">Liberia</option> 
    <option value="Libyan Arab Jamahiriya">Libyan Arab Jamahiriya</option> 
    <option value="Liechtenstein">Liechtenstein</option> 
    <option value="Lithuania">Lithuania</option> 
    <option value="Luxembourg">Luxembourg</option> 
    <option value="Macao">Macao</option> 
    <option value="Macedonia, The Former Yugoslav Republic of">Macedonia, The Former Yugoslav Republic of</option> 
    <option value="Madagascar">Madagascar</option> 
    <option value="Malawi">Malawi</option> 
    <option value="Malaysia">Malaysia</option> 
    <option value="Maldives">Maldives</option> 
    <option value="Mali">Mali</option> 
    <option value="Malta">Malta</option> 
    <option value="Marshall Islands">Marshall Islands</option> 
    <option value="Martinique">Martinique</option> 
    <option value="Mauritania">Mauritania</option> 
    <option value="Mauritius">Mauritius</option> 
    <option value="Mayotte">Mayotte</option> 
    <option value="Mexico">Mexico</option> 
    <option value="Micronesia, Federated States of">Micronesia, Federated States of</option> 
    <option value="Moldova, Republic of">Moldova, Republic of</option> 
    <option value="Monaco">Monaco</option> 
    <option value="Mongolia">Mongolia</option> 
    <option value="Montserrat">Montserrat</option> 
    <option value="Morocco">Morocco</option> 
    <option value="Mozambique">Mozambique</option> 
    <option value="Myanmar">Myanmar</option> 
    <option value="Namibia">Namibia</option> 
    <option value="Nauru">Nauru</option> 
    <option value="Nepal">Nepal</option> 
    <option value="Netherlands">Netherlands</option> 
    <option value="Netherlands Antilles">Netherlands Antilles</option> 
    <option value="New Caledonia">New Caledonia</option> 
    <option value="New Zealand">New Zealand</option> 
    <option value="Nicaragua">Nicaragua</option> 
    <option value="Niger">Niger</option> 
    <option value="Nigeria">Nigeria</option> 
    <option value="Niue">Niue</option> 
    <option value="Norfolk Island">Norfolk Island</option> 
    <option value="Northern Mariana Islands">Northern Mariana Islands</option> 
    <option value="Norway">Norway</option> 
    <option value="Oman">Oman</option> 
    <option value="Pakistan">Pakistan</option> 
    <option value="Palau">Palau</option> 
    <option value="Palestinian Territory, Occupied">Palestinian Territory, Occupied</option> 
    <option value="Panama">Panama</option> 
    <option value="Papua New Guinea">Papua New Guinea</option> 
    <option value="Paraguay">Paraguay</option> 
    <option value="Peru">Peru</option> 
    <option value="Philippines">Philippines</option> 
    <option value="Pitcairn">Pitcairn</option> 
    <option value="Poland">Poland</option> 
    <option value="Portugal">Portugal</option> 
    <option value="Puerto Rico">Puerto Rico</option> 
    <option value="Qatar">Qatar</option> 
    <option value="Reunion">Reunion</option> 
    <option value="Romania">Romania</option> 
    <option value="Russian Federation">Russian Federation</option> 
    <option value="Rwanda">Rwanda</option> 
    <option value="Saint Helena">Saint Helena</option> 
    <option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option> 
    <option value="Saint Lucia">Saint Lucia</option> 
    <option value="Saint Pierre and Miquelon">Saint Pierre and Miquelon</option> 
    <option value="Saint Vincent and The Grenadines">Saint Vincent and The Grenadines</option> 
    <option value="Samoa">Samoa</option> 
    <option value="San Marino">San Marino</option> 
    <option value="Sao Tome and Principe">Sao Tome and Principe</option> 
    <option value="Saudi Arabia">Saudi Arabia</option> 
    <option value="Senegal">Senegal</option> 
    <option value="Serbia and Montenegro">Serbia and Montenegro</option> 
    <option value="Seychelles">Seychelles</option> 
    <option value="Sierra Leone">Sierra Leone</option> 
    <option value="Singapore">Singapore</option> 
    <option value="Slovakia">Slovakia</option> 
    <option value="Slovenia">Slovenia</option> 
    <option value="Solomon Islands">Solomon Islands</option> 
    <option value="Somalia">Somalia</option> 
    <option value="South Africa">South Africa</option> 
    <option value="South Georgia and The South Sandwich Islands">South Georgia and The South Sandwich Islands</option> 
    <option value="Spain">Spain</option> 
    <option value="Sri Lanka">Sri Lanka</option> 
    <option value="Sudan">Sudan</option> 
    <option value="Suriname">Suriname</option> 
    <option value="Svalbard and Jan Mayen">Svalbard and Jan Mayen</option> 
    <option value="Swaziland">Swaziland</option> 
    <option value="Sweden">Sweden</option> 
    <option value="Switzerland">Switzerland</option> 
    <option value="Syrian Arab Republic">Syrian Arab Republic</option> 
    <option value="Taiwan, Province of China">Taiwan, Province of China</option> 
    <option value="Tajikistan">Tajikistan</option> 
    <option value="Tanzania, United Republic of">Tanzania, United Republic of</option> 
    <option value="Thailand">Thailand</option> 
    <option value="Timor-leste">Timor-leste</option> 
    <option value="Togo">Togo</option> 
    <option value="Tokelau">Tokelau</option> 
    <option value="Tonga">Tonga</option> 
    <option value="Trinidad and Tobago">Trinidad and Tobago</option> 
    <option value="Tunisia">Tunisia</option> 
    <option value="Turkey">Turkey</option> 
    <option value="Turkmenistan">Turkmenistan</option> 
    <option value="Turks and Caicos Islands">Turks and Caicos Islands</option> 
    <option value="Tuvalu">Tuvalu</option> 
    <option value="Uganda">Uganda</option> 
    <option value="Ukraine">Ukraine</option> 
    <option value="United Arab Emirates">United Arab Emirates</option> 
    <option value="United Kingdom">United Kingdom</option> 
    <option value="United States">United States</option> 
    <option value="United States Minor Outlying Islands">United States Minor Outlying Islands</option> 
    <option value="Uruguay">Uruguay</option> 
    <option value="Uzbekistan">Uzbekistan</option> 
    <option value="Vanuatu">Vanuatu</option> 
    <option value="Venezuela">Venezuela</option> 
    <option value="Viet Nam">Viet Nam</option> 
    <option value="Virgin Islands, British">Virgin Islands, British</option> 
    <option value="Virgin Islands, U.S.">Virgin Islands, U.S.</option> 
    <option value="Wallis and Futuna">Wallis and Futuna</option> 
    <option value="Western Sahara">Western Sahara</option> 
    <option value="Yemen">Yemen</option> 
    <option value="Zambia">Zambia</option> 
    <option value="Zimbabwe">Zimbabwe</option>

    </select>
     </td> 
    </tr>
    <tr> 
    <td width="70" valign="top"> 
    <font face="verdana" size="1"> 
    <b> Postcode:</b> 
    </font> 
    </td> 
    <td> 
    <input type="text" size="7" name="Postcode"> 
    </td> 
    </tr> 
    
    <tr> 
    <td width="70" valign="top"> 
    <font face="verdana" size="1"> 
    <b> Telephone:</b> 
    </font> 
    </td> 
    <td> 
    <input type="text" size="17" name="Telephone"> 
    </td> 
    </tr> 
    <tr> 
    <td width="70" valign="top"> 
    <font face="verdana" size="1"> 
    <b> * E-mail:</b> 
    </font> 
    </td> 
    <td> 
    <input type="text" size="40" name="email_from"> 
    </td> 
    </tr> 
    <tr>

    <td><font face="verdana" size="1"> <b>How did you hear of us?</b></font></td>
    <td>
    <select name="Advertising">
      <option value="">Select</option>
      <option value="Craft Focus Magazine">Craft Focus Magazine</option>
      <option value="Garden Shop Catalogue">Garden Shop Catalogue</option>

      <option value="Gift Focus Magazine">Gift Focus Magazine</option>
      <option value="Gifts Today">Gifts Today</option>
    <option value="Giftware Review">Giftware Review</option>
    <option value="Heritage Shop Catalogue">Heritage Shop Catalogue</option>
    <option value="Google">Google</option>
    <option value="Yahoo">Yahoo</option>

    <option value="Bing">Bing</option>
    <option value="Market Times">Market Times</option>
    <option value="The Trader Magazine">The Trader Magazine</option>
    <option value="MTN Market Trade News">MTN Market Trade News</option>
    <option value="Progressive Gifts">Progressive Gifts</option>
    <option value="The Trader Website">The Trader Website</option>

    <option value="Other">Other</option>
    <option value="Facebook">Facebook</option>
    <option value="Twitter">Twitter</option>
    </select>
    </td>
    </tr>
     <tr>

    <td>
    <width="70" valign="top"> 
    <font face="verdana" size="1"> 
    <b>Message</b>
    </td>
    <td> 
    <textarea name="Message"cols=32 rows=5 wrap=virtual> </textarea> 
    </td> 
    </tr> 
    
    <tr> 
    <td>  </td> 
    <td> 
    <input type="submit" name="submit" value="Send" > 
    <input type="reset" name="reset" value="Reset"> 
    </td> 
    </tr> 
    </table> 
    
    </form> 
</body>
</html>

	{/literal}
	</textarea> </td>
	</tr>
	</table>
	</div>

    </div>
	
	
</div>
</div>
{include file='footer.tpl'}


