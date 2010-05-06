<tbody id="{$address_identifier}address_form" style="display:none"   >
 
 <tr id="{$address_identifier}tr_address_type" {if $hide_type}style="display:none"{/if}>
    <td class="label">
      <span id="{$address_identifier}show_description" onclick="show_description()" class="small_button" style="padding:0 1px;font-size:50%;position:relative;top:-2px;">+</span> {t}Address Type{/t}:</td><td  style="text-align:left"   id="{$address_identifier}address_type" value="" ovalue=""  >
      <span id="{$address_identifier}address_type_Office" label="Office" onclick="toggle_address_type(this)" class="small_button address_type" style="margin:0">Office</span>
      <span id="{$address_identifier}address_type_Shop" label="Shop" onclick="toggle_address_type(this)" class="small_button  address_type" style="margin-left:3px">Shop</span>
      <span id="{$address_identifier}address_type_Warehouse" label="Warehouse" onclick="toggle_address_type(this)" class="small_button  address_type" style="margin-left:3px">Warehouse</span>
      <span id="{$address_identifier}address_type_Other" label="Other" onclick="toggle_address_type(this)" class="small_button  address_type" style="margin-left:3px">Other</span>
    </td>
	    </tr>
  
  <tr id="{$address_identifier}tr_address_function" style="{if  $hide_description}display:none;{/if}" >
    <td class="label">{t}Address Function{/t}:</td><td  style="text-align:left"   id="{$address_identifier}address_function" value="" ovalue=""  >
      <span id="{$address_identifier}address_function_Contact" label="Contact" onclick="toggle_address_function(this)" class="small_button address_function" style="margin:0">Contact</span>
      <span id="{$address_identifier}address_function_Billing" label="Billing" onclick="toggle_address_function(this)" class="small_button address_function" style="margin-left:3px">Billing</span>
      <span id="{$address_identifier}address_function_Shipping" label="Shipping" onclick="toggle_address_function(this)" class="small_button address_function" style="margin-left:3px">Shipping</span>
      <span id="{$address_identifier}address_function_Other" label="Other" onclick="toggle_address_function(this)" class="small_button address_function" style="margin-left:3px">Other</span>
    </td>
  </tr>
  
  
  
  <tr id="{$address_identifier}tr_address_description" style="display:none"> 
    <td class="label"><span id="{$address_identifier}hide_description" onclick="hide_description()" class="small_button" style="padding:0 1px;font-size:50%;position:relative;top:-2px;">x</span> {t}Description{/t}:</td><td  style="text-align:left"    ><input style="text-align:left" id="{$address_identifier}address_description" value="" ovalue=""   ></td>
  </tr>
  
  <tr {if $hide_type and  $hide_description}style="display:none"{/if}  >
    <td ></td><td style="text-align:left"><div  style="text-align:left;float:left;height:10px;width:210px;border-top:1px solid #ddd"></div></td>
  </tr>
  
  <input type="hidden" id="{$address_identifier}address_key" value="" ovalue="" >
  <input type="hidden" id="{$address_identifier}address_fuzzy" value="Yes" ovalue="Yes" >
  
  
  <tr class="first">
    
    <td class="label" style="width:160px">
      <span id="{$address_identifier}show_country_d1" onclick="toggle_country_d1()" class="small_button" style="padding:0 1px;font-size:50%;position:relative;top:-2px;display:none">+</span>
      Country:</td>
    <td  style="text-align:left">
      <div id="{$address_identifier}myAutoComplete" style="width:15em;position:relative;top:0px" >
	<input id="{$address_identifier}address_country" style="text-align:left;width:18em" type="text">
	<div id="{$address_identifier}address_country_container" style="position:relative;top:18px" ></div>
	
      </div>
    </td>
	  </tr>
  <input id="{$address_identifier}address_country_code" value="" type="hidden">
  <input id="{$address_identifier}address_country_2acode" value="" type="hidden">
  
  
  <tr id="{$address_identifier}tr_address_country_d1">
	    <td class="label" style="width:160px">
	      <span id="{$address_identifier}show_country_d2" onclick="toggle_country_d2()" class="small_button" style="padding:0 1px;font-size:50%;position:relative;top:-2px;display:none">+</span> 
	      <span id="{$address_identifier}label_address_country_d1">{t}Region{/t}</span>:</td><td  style="text-align:left"><input style="text-align:left;width:18em" id="{$address_identifier}address_country_d1" value="" ovalue="" ></td>
  </tr>
  <tr id="{$address_identifier}tr_address_country_d2">
    <td class="label" style="width:160px"><span id="{$address_identifier}label_address_country_d2">{t}Subregion{/t}</span>:</td><td  style="text-align:left">
	    <input style="text-align:left;width:18em" id="{$address_identifier}address_country_d2" value="" ovalue="" ></td>
  </tr>
  
  <tr id="{$address_identifier}tr_address_postal_code">
    <td class="label" style="width:160px">{t}Postal Code{/t}:</td><td  style="text-align:left"><input style="text-align:left;width:18em" id="{$address_identifier}address_postal_code" value="" ovalue=""  ></td>
  </tr>
  
  <tr>
    <td class="label" style="width:160px">
      <span id="{$address_identifier}show_town_d1" onclick="toggle_town_d1()" class="small_button" style="padding:0 1px;font-size:50%;position:relative;top:-2px">+</span> {t}City{/t}:</td>
    <td  style="text-align:left"><input style="text-align:left;width:18em" id="{$address_identifier}address_town" value="" ovalue="" ></td>
  </tr>
  <tr style="display:none" id="{$address_identifier}tr_address_town_d1">
    <td class="label" style="width:160px" >
	      <span id="{$address_identifier}show_town_d2" onclick="toggle_town_d2()" class="small_button" style="padding:0 1px;font-size:50%;position:relative;top:-2px">x</span> {t}City 1st Div{/t}:</td>
    <td  style="text-align:left"><input style="text-align:left;width:18em" id="{$address_identifier}address_town_d1" value="" ovalue="" ></td>
  </tr>
  <tr style="display:none;" id="{$address_identifier}tr_address_town_d2">
    <td class="label" style="width:160px">{t}City 2nd Div{/t}:</td><td  style="text-align:left"><input style="text-align:left;width:18em" id="{$address_identifier}address_town_d2" value="" ovalue="" ></td>
  </tr>
  <tr>
    <td class="label" style="width:160px">{t}Street/Number{/t}:</td><td  style="text-align:left"><input style="text-align:left;width:18em" id="{$address_identifier}address_street" value="" ovalue="" ></td>
  <tr>
    <td class="label" style="width:160px">{t}Building{/t}:</td><td  style="text-align:left"><input style="text-align:left;width:18em" id="{$address_identifier}address_building" value="" ovalue="" ></td>
  </tr>
  <tr >
    <td class="label" style="width:160px">{t}Internal{/t}:</td><td  style="text-align:left"><input style="text-align:left;width:18em" id="{$address_identifier}address_internal" value="" ovalue="" ></td>
  </tr>
  <tr><td colspan=2 style="text-align:right"><button address_key="" style="visibility:hidden" id="{$address_identifier}reset_address_button">{t}Reset{/t}</button><button address_key="" style="visibility:hidden;margin-left:10px"id="{$address_identifier}save_address_button">{t}Save{/t}</button></td></tr>
</tbody>
