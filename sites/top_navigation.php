<link rel="stylesheet" type="text/css" href="../top_navigation.css" />
<script type="text/javascript" src="../external_libs/yui/2.9/build/utilities/utilities.js"></script>
<script type="text/javascript" src="../external_libs/yui/2.9/build/dragdrop/dragdrop-min.js"></script>
<script type="text/javascript" src="../external_libs/yui/2.9/build/json/json-min.js"></script>
<script type="text/javascript" src="../external_libs/yui/2.9/build/element/element-min.js"></script>

<script type="text/javascript" src="../js/sha256.js"></script>
<script type="text/javascript" src="../js/aes.js"></script>

<script type="text/javascript" src="../js/login.js"></script>
<script type="text/javascript" src="../basket.js"></script>

<?php if($logged_in){?>
<script type="text/javascript" src="../top_navigation_login.js.php"></script>
<?php }else{?>
<script type="text/javascript" src="../top_navigation_logout.js.php"></script>
<?php }?>

<input type="hidden" value="<?php echo $store_key?>" id="store_key">
<input type="hidden" value="<?php echo $site->id?>" id="site_key">



<div id="top_navigator" style="width:855px">
<?php if($logged_in){?>
<div style="width:20px;float:left;margin-right:10px"><img style="height:20px" src="../art/basket.jpg"/></div>

<div style="width:600px;float:left;text-align:left;xborder:1px solid red;"> Items: <span id="basket_items">0</span> Total: <span id="basket_total">£0.00</span>  <span class="soft_link" style="margin:0 15px 0 5px;xfont-style:italic; ">see basket</span> <button id="checkout">Check Out</button> </div>
<span>Hello, <?php print $user->data['User Alias']." (".$customer->id.")"?></span>
<button id="logout">Log Out</button>
<?php }else{ ?>
<button id="show_register_dialog">Register</button>
<button id="show_login_dialog">Log In</button>
<?php } ?>

</div>

<?php if(!$logged_in){?>

<div id="dialog_login"    class="dialog"    style="position:relative;left:525px;top:-22px;width:350px">
<input type="hidden" value="<?php echo $St?>" id="ep">
<h2>Login</h2>
<table>

<tr><td class="label">Email: </td><td><input id="login_handle"></td></tr>
<tr><td  class="label">Password: </td><td><input type="password"  id="login_password"></td></tr>
<tr class="button" style=""><td colspan="2"><span id="invalid_credentials" style="display:none">Wrong credentials!</span>  <button id="submit_login">Log In</button></td></tr>
<tr class="link"><td colspan=2>Forgot your password? <span class="link"   id="link_forgot_password_from_login" >Click Here</span></td></tr>
<tr class="link"><td colspan=2>First visit? <span class="link" id="link_register_from_login">Register Here</span></td></tr>
<tr class="button" ><td></td><td ><button id="hide_login_dialog">Close</button></td></tr>
</table>
</div>

<div id="dialog_register"    class="dialog"    style="position:relative;left:525px;width:350px;top:-22px;">
<h2>Registration</h2>
<table>
<tr><td class="label">Email: </td><td><input id="register_email"></td></tr>
<tr class="button" ><td colspan=2 ><button id="hide_register_dialog">Close</button> <button id="submit_check_email">Continue</button></td></tr>
</table>
</div>


<div id="dialog_register_part_2"    class="dialog"    style="position:relative;left:525px;width:350px;top:-22px;">
<h2>Registration</h2>
<table>
<tr><td class="label">Email: </td><td id="confirmed_register_email"></td></tr>
<tr><td>Password: </td><td><input type="password" id="register_password1"></td></tr>
<tr><td>Password: </td><td><input type="password" id="register_password2"></td></tr>
<input id="epw2" value="" type="hidden"/>

<tr class="title" ><td colspan="2">Contact Info: </td></tr>
<tr><td>Contact Name: </td><td><input id="register_contact_name"></td></tr>

<tr><td class="label">Company: </td><td><input id="register_company_name"></td></tr>
<tr><td>Telephone: </td><td><input id="register_telephone"></td></tr>

<tbody id="tbody_register_address"  >
<tr class="title" ><td colspan="2">Address: </td></tr>

<tr><td  class="label">Line 1: </td><td><input id="register_telephone"></td></tr>
<tr><td class="label">Line 2: </td><td><input id="register_telephone"></td></tr>
<tr><td class="label">Town: </td><td><input id="register_telephone"></td></tr>
<tr><td class="label">Postal Code: </td><td><input id="register_telephone"></td></tr>

<tr><td class="label">Country: </td><td><select size="1" id="country" name="country">
<option value="">Select One</option>
<option value="GB">United Kingdom</option>
<option value="">----------</option>
<option value="AF">Afghanistan</option>
<option value="AL">Albania</option>
<option value="DZ">Algeria</option>
<option value="AS">American Samoa</option>
<option value="AD">Andorra</option>
<option value="AO">Angola</option>
<option value="AI">Anguilla</option>
<option value="AQ">Antarctica</option>
<option value="AG">Antigua and Barbuda</option>
<option value="AR">Argentina</option>
<option value="AM">Armenia</option>
<option value="AW">Aruba</option>
<option value="AU">Australia</option>
<option value="AT">Austria</option>
<option value="AZ">Azerbaidjan</option>
<option value="BS">Bahamas</option>
<option value="BH">Bahrain</option>
<option value="BD">Bangladesh</option>
<option value="BB">Barbados</option>
<option value="BY">Belarus</option>
<option value="BE">Belgium</option>
<option value="BZ">Belize</option>
<option value="BJ">Benin</option>
<option value="BM">Bermuda</option>
<option value="BT">Bhutan</option>
<option value="BO">Bolivia</option>
<option value="BA">Bosnia-Herzegovina</option>
<option value="BW">Botswana</option>
<option value="BV">Bouvet Island</option>
<option value="BR">Brazil</option>
<option value="IO">British Indian Ocean Territory</option>
<option value="BN">Brunei Darussalam</option>
<option value="BG">Bulgaria</option>
<option value="BF">Burkina Faso</option>
<option value="BI">Burundi</option>
<option value="KH">Cambodia</option>
<option value="CM">Cameroon</option>
<option value="CA">Canada</option>

<option value="CV">Cape Verde</option>
<option value="KY">Cayman Islands</option>
<option value="CF">Central African Republic</option>
<option value="TD">Chad</option>
<option value="CL">Chile</option>
<option value="CN">China</option>
<option value="CX">Christmas Island</option>
<option value="CC">Cocos (Keeling) Islands</option>
<option value="CO">Colombia</option>
<option value="KM">Comoros</option>
<option value="CG">Congo</option>
<option value="CK">Cook Islands</option>
<option value="CR">Costa Rica</option>
<option value="HR">Croatia</option>
<option value="CU">Cuba</option>
<option value="CY">Cyprus</option>
<option value="CZ">Czech Republic</option>
<option value="DK">Denmark</option>
<option value="DJ">Djibouti</option>
<option value="DM">Dominica</option>
<option value="DO">Dominican Republic</option>
<option value="TP">East Timor</option>
<option value="EC">Ecuador</option>
<option value="EG">Egypt</option>
<option value="SV">El Salvador</option>
<option value="GQ">Equatorial Guinea</option>
<option value="ER">Eritrea</option>
<option value="EE">Estonia</option>
<option value="ET">Ethiopia</option>
<option value="FK">Falkland Islands</option>
<option value="FO">Faroe Islands</option>
<option value="FJ">Fiji</option>
<option value="FI">Finland</option>
<option value="CS">Former Czechoslovakia</option>
<option value="SU">Former USSR</option>
<option value="FR">France</option>
<option value="FX">France (European Territory)</option>
<option value="GF">French Guyana</option>
<option value="TF">French Southern Territories</option>
<option value="GA">Gabon</option>
<option value="GM">Gambia</option>
<option value="GE">Georgia</option>
<option value="DE">Germany</option>
<option value="GH">Ghana</option>
<option value="GI">Gibraltar</option>
<option value="GB">Great Britain</option>
<option value="GR">Greece</option>
<option value="GL">Greenland</option>
<option value="GD">Grenada</option>
<option value="GP">Guadeloupe (French)</option>
<option value="GU">Guam (USA)</option>
<option value="GT">Guatemala</option>
<option value="GN">Guinea</option>
<option value="GW">Guinea Bissau</option>
<option value="GY">Guyana</option>
<option value="HT">Haiti</option>
<option value="HM">Heard and McDonald Islands</option>
<option value="HN">Honduras</option>
<option value="HK">Hong Kong</option>
<option value="HU">Hungary</option>
<option value="IS">Iceland</option>
<option value="IN">India</option>
<option value="ID">Indonesia</option>
<option value="INT">International</option>
<option value="IR">Iran</option>
<option value="IQ">Iraq</option>
<option value="IE">Ireland</option>
<option value="IL">Israel</option>
<option value="IT">Italy</option>
<option value="CI">Ivory Coast (Cote D&#39;Ivoire)</option>
<option value="JM">Jamaica</option>
<option value="JP">Japan</option>
<option value="JO">Jordan</option>
<option value="KZ">Kazakhstan</option>
<option value="KE">Kenya</option>
<option value="KI">Kiribati</option>
<option value="KW">Kuwait</option>
<option value="KG">Kyrgyzstan</option>
<option value="LA">Laos</option>
<option value="LV">Latvia</option>
<option value="LB">Lebanon</option>
<option value="LS">Lesotho</option>
<option value="LR">Liberia</option>
<option value="LY">Libya</option>
<option value="LI">Liechtenstein</option>
<option value="LT">Lithuania</option>
<option value="LU">Luxembourg</option>
<option value="MO">Macau</option>
<option value="MK">Macedonia</option>
<option value="MG">Madagascar</option>
<option value="MW">Malawi</option>
<option value="MY">Malaysia</option>
<option value="MV">Maldives</option>
<option value="ML">Mali</option>
<option value="MT">Malta</option>
<option value="MH">Marshall Islands</option>
<option value="MQ">Martinique (French)</option>
<option value="MR">Mauritania</option>
<option value="MU">Mauritius</option>
<option value="YT">Mayotte</option>
<option value="MX">Mexico</option>
<option value="FM">Micronesia</option>
<option value="MD">Moldavia</option>
<option value="MC">Monaco</option>
<option value="MN">Mongolia</option>
<option value="MS">Montserrat</option>
<option value="MA">Morocco</option>
<option value="MZ">Mozambique</option>
<option value="MM">Myanmar</option>
<option value="NA">Namibia</option>
<option value="NR">Nauru</option>
<option value="NP">Nepal</option>
<option value="NL">Netherlands</option>
<option value="AN">Netherlands Antilles</option>
<option value="NT">Neutral Zone</option>
<option value="NC">New Caledonia (French)</option>
<option value="NZ">New Zealand</option>
<option value="NI">Nicaragua</option>
<option value="NE">Niger</option>
<option value="NG">Nigeria</option>
<option value="NU">Niue</option>
<option value="NF">Norfolk Island</option>
<option value="KP">North Korea</option>
<option value="MP">Northern Mariana Islands</option>
<option value="NO">Norway</option>
<option value="OM">Oman</option>
<option value="PK">Pakistan</option>
<option value="PW">Palau</option>
<option value="PA">Panama</option>
<option value="PG">Papua New Guinea</option>
<option value="PY">Paraguay</option>
<option value="PE">Peru</option>
<option value="PH">Philippines</option>
<option value="PN">Pitcairn Island</option>
<option value="PL">Poland</option>
<option value="PF">Polynesia (French)</option>
<option value="PT">Portugal</option>
<option value="PR">Puerto Rico</option>
<option value="QA">Qatar</option>
<option value="RE">Reunion (French)</option>
<option value="RO">Romania</option>
<option value="RU">Russian Federation</option>
<option value="RW">Rwanda</option>
<option value="GS">S. Georgia & S. Sandwich I.</option>
<option value="SH">Saint Helena</option>
<option value="KN">Saint Kitts & Nevis Anguilla</option>
<option value="LC">Saint Lucia</option>
<option value="PM">St Pierre and Miquelon</option>
<option value="ST">St Tome & Principe</option>
<option value="VC">St Vincent & Grenadines</option>
<option value="WS">Samoa</option>
<option value="SM">San Marino</option>
<option value="SA">Saudi Arabia</option>
<option value="SN">Senegal</option>
<option value="SC">Seychelles</option>
<option value="SL">Sierra Leone</option>
<option value="SG">Singapore</option>
<option value="SK">Slovak Republic</option>
<option value="SI">Slovenia</option>
<option value="SB">Solomon Islands</option>
<option value="SO">Somalia</option>
<option value="ZA">South Africa</option>
<option value="KR">South Korea</option>
<option value="ES">Spain</option>
<option value="LK">Sri Lanka</option>
<option value="SD">Sudan</option>
<option value="SR">Suriname</option>
<option value="SJ">Svalbard and Jan Mayen I.</option>
<option value="SZ">Swaziland</option>
<option value="SE">Sweden</option>
<option value="CH">Switzerland</option>
<option value="SY">Syria</option>
<option value="TJ">Tadjikistan</option>
<option value="TW">Taiwan</option>
<option value="TZ">Tanzania</option>
<option value="TH">Thailand</option>
<option value="TG">Togo</option>
<option value="TK">Tokelau</option>
<option value="TO">Tonga</option>
<option value="TT">Trinidad and Tobago</option>
<option value="TN">Tunisia</option>
<option value="TR">Turkey</option>
<option value="TM">Turkmenistan</option>
<option value="TC">Turks and Caicos Islands</option>
<option value="TV">Tuvalu</option>
<option value="UG">Uganda</option>
<option value="UA">Ukraine</option>
<option value="AE">United Arab Emirates</option>
<option value="GB">United Kingdom</option>
<option value="US">United States</option>

<option value="UY">Uruguay</option>

<option value="UM">USA Minor Outlying Islands</option>
<option value="UZ">Uzbekistan</option>
<option value="VU">Vanuatu</option>
<option value="VA">Vatican City State</option>
<option value="VE">Venezuela</option>
<option value="VN">Vietnam</option>
<option value="VG">Virgin Islands (British)</option>
<option value="VI">Virgin Islands (USA)</option>
<option value="WF">Wallis and Futuna Islands</option>
<option value="EH">Western Sahara</option>
<option value="YE">Yemen</option>
<option value="YU">Yugoslavia</option>
<option value="ZR">Zaire</option>
<option value="ZM">Zambia</option>
<option value="ZW">Zimbabwe</option>
</select></td></tr>

<tr class="button" style=""><td colspan=2><span style="display:none" id="register_error_no_password">Please, create a password</span><span style="display:none" id="register_error_password_not_march">Passwords don't match</span><span style="display:none" id="register_error_password_too_short">Password is too short</span> <button id="hide_register_part_2_dialog">Close</button><button id="submit_register">Register</button></td></tr>
</table>
</div>


<div id="dialog_forgot_password"    class="dialog"    style="position:relative;left:525px;width:350px;top:-22px;">
<h2>Forgotten password</h2>
<table>
<tr><td  class="label">Email: </td><td><input id="forgot_password_handle"></td></tr>
<tr class="button" style=""><td></td><td><button id="submit_forgot_password">Continue</button></td></tr>

<tr class="button" ><td></td><td ><button id="hide_forgot_password_dialog">Close</button></td></tr>
</table>
</div>
<?php } ?>
