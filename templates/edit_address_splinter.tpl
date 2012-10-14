<tbody id="{$address_identifier}address_form" style="{if !$show_form}display:none{/if}">
    <input type="hidden" id="{$address_identifier}address_key" value="" ovalue="" />
    <input type="hidden" id="{$address_identifier}address_fuzzy" value="Yes" ovalue="Yes" />
    <input id="{$address_identifier}address_country_code" value="" type="hidden"/>
    <input id="{$address_identifier}address_country_2acode" value="" type="hidden"/>
    
    <tr id="{$address_identifier}tr_address_type" {if $hide_type}style="display:none"{/if}>
    <td class="label">
      <span id="{$address_identifier}show_description" onclick="show_description()" class="small_button" style="padding:0 1px;font-size:50%;position:relative;top:-2px;">+</span> {t}Address Type{/t}:</td><td  style="text-align:left"   id="{$address_identifier}address_type" value="" ovalue=""  >
      <span id="{$address_identifier}address_type_Office" label="Office" onclick="toggle_address_type(this)" class="small_button address_type {if $address_type=='Office'}selected{/if}" style="margin:0">{t}Office{/t}</span>
      <span id="{$address_identifier}address_type_Shop" label="Shop" onclick="toggle_address_type(this)" class="small_button  address_type {if $address_type=='Shop'}selected{/if}" style="margin-left:3px">{t}Shop{/t}</span>
      <span id="{$address_identifier}address_type_Warehouse" label="Warehouse" onclick="toggle_address_type(this)" class="small_button  address_type {if $address_type=='Warehouse'}selected{/if}" style="margin-left:3px">{t}Warehouse{/t}</span>
      <span id="{$address_identifier}address_type_Other" label="Other" onclick="toggle_address_type(this)" class="small_button  address_type {if $address_type=='Other'}selected{/if}" style="margin-left:3px">{t}Other{/t}</span>
    </td>
	    </tr>
    <tr id="{$address_identifier}tr_address_function" style="{if  $hide_description}display:none;{/if}" >
    <td class="label">{t}Address Function{/t}:</td><td  style="text-align:left"   id="{$address_identifier}address_function" value="{$function_value}" ovalue=""  >
      <span id="{$address_identifier}address_function_Contact" label="Contact" onclick="toggle_address_function(this)" class="small_button address_function {if $address_function=='Contact'}selected{/if}" style="margin:0">{t}Contact{/t}</span>
      <span id="{$address_identifier}address_function_Billing" label="Billing" onclick="toggle_address_function(this)" class="small_button address_function {if $address_function=='Billing'}selected{/if}" style="margin-left:3px">{t}Billing{/t}</span>
      <span id="{$address_identifier}address_function_Shipping" label="Shipping" onclick="toggle_address_function(this)" class="small_button address_function {if $address_function=='Shipping'}selected{/if}" style="margin-left:3px">{t}Shipping{/t}</span>
      <span id="{$address_identifier}address_function_Other" label="Other" onclick="toggle_address_function(this)" class="small_button address_function {if $address_function=='Other'}selected{/if}" style="margin-left:3px">{t}Other{/t}</span>
    </td>
  </tr>
    <tr id="{$address_identifier}tr_address_description" style="display:none"> 
    <td class="label">
        <span id="{$address_identifier}hide_description" onclick="hide_description()" class="small_button" style="padding:0 1px;font-size:50%;position:relative;top:-2px;">x</span> {t}Description{/t}:
        </td>
    <td  style="text-align:left">
        <input style="text-align:left" id="{$address_identifier}address_description" value="" ovalue="" />
    </td>
  </tr>
    <tr id="{$address_identifier}tr_address_description" {if $hide_type and  $hide_description}style="display:none"{/if}  >
    <td ></td><td style="text-align:left"><div  style="text-align:left;float:left;height:10px;border-top:1px solid #ddd"></div></td>
  </tr>
</tbody>

<tbody id="{$address_identifier}address_components"  style="{if !$show_components}display:none{/if}">

    <tr id="{$address_identifier}tr_address_contact"  style="{if !$show_contact}display:none{/if}">
    <td class="label" >{t}Contact{/t}:</td>
    <td  style="text-align:left">
    <div>
        <input type="hidden" id="{$address_identifier}address_use_contact" value="{$show_contact}"   ovalue="{$show_contact}"  />
        <input style="text-align:left;width:100%" id="{$address_identifier}address_contact" value="" ovalue="" />
        <div id="{$address_identifier}address_contact_container"  ></div>
	</div>
    </td>
  </tr>
    <tr id="{$address_identifier}tr_address_telephone"  style="{if !$show_tel}display:none{/if}">
    <td class="label" >{t}Telephone{/t}:</td>
    <td  style="text-align:left">
    <div>
        <input type="hidden" id="{$address_identifier}address_use_tel" value="{$show_tel}"   ovalue="{$show_tel}"  />
        <input style="text-align:left;width:100%" id="{$address_identifier}address_telephone" value="" ovalue="" />
        <div id="{$address_identifier}address_telephone_container"  ></div>
	</div>
    </td>
  </tr>
    <tr id="{$address_identifier}tr_address_internal">
    <td class="label">{t}Internal{/t}:</td>
    <td  style="text-align:left">
        <input style="text-align:left;width:100%" id="{$address_identifier}address_internal" value="" ovalue="" />
    </td>
  </tr>
    <tr id="{$address_identifier}tr_address_building">
    <td class="label" >{t}Building{/t}:</td><td  style="text-align:left"><input style="text-align:left;width:100%" id="{$address_identifier}address_building" value="" ovalue="" /></td>
  </tr>
    <tr id="{$address_identifier}tr_address_street">
    <td class="label" >{t}Street{/t}:</td>
    <td  style="text-align:left"><input style="text-align:left;width:100%" id="{$address_identifier}address_street" value="" ovalue="" /></td>
    </tr>
    <tr id="{$address_identifier}tr_address_town_d2" style="display:none">
    <td class="label" >{t}City 2nd Div{/t}:</td><td  style="text-align:left"><input style="text-align:left;width:100%" id="{$address_identifier}address_town_d2" value="" ovalue="" /></td>
   </tr>
    <tr id="{$address_identifier}tr_address_town_d1" style="display:none">
    <td class="label"  >{t}City 1st Div{/t}:</td>
    <td  style="text-align:left"><input style="text-align:left;width:100%" id="{$address_identifier}address_town_d1" value="" ovalue="" /></td>
  </tr>
    <tr id="{$address_identifier}tr_address_town">
    <td class="label" >
      <span id="{$address_identifier}show_town_subdivisions" onclick="show_town_subdivisions('{$address_identifier}')" style="cursor:pointer">&oplus;</span> {t}City{/t}:</td>
    <td  style="text-align:left">
     <div >
    <input style="text-align:left;width:100%" id="{$address_identifier}address_town" value="" ovalue="" />
      <input id="{$address_identifier}address_town_code" value="" type="hidden"/>
<div id="{$address_identifier}address_town_container"  ></div>
	      </div>
    </td>
  </tr>
    <tr id="{$address_identifier}tr_address_postal_code">
    <td    id="{$address_identifier}label_address_postal_code"     class="label" ><img  id="{$address_identifier}address_postal_code_warning" title=""  src="art/icons/exclamation.png" style="margin-left:5px;visibility:hidden" /> {t}Postal Code{/t}:
    </td>
    <td  style="text-align:left">
    <div >
    <input style="text-align:left;width:100%" id="{$address_identifier}address_postal_code" value="" ovalue=""  />
     <input id="{$address_identifier}address_postal_code_code" value="" type="hidden"/>
        <div id="{$address_identifier}address_postal_code_container"  ></div>
	      </div>
    </td>
  </tr>
    <tr id="{$address_identifier}tr_address_country_d5" style="display:none">
	  <td class="label" ><span id="{$address_identifier}label_address_country_d5">{t}5rd Division{/t}</span>:</td><td  style="text-align:left">
	    <div  >
	      <input id="{$address_identifier}address_country_d5_code" value="" type="hidden"/>
	      <input style="text-align:left;width:100%" id="{$address_identifier}address_country_d5" value="" ovalue="" />
	      <div id="{$address_identifier}address_country_d5_container"  ></div>
	    </div>
	  </td>
	</tr>
    <tr id="{$address_identifier}tr_address_country_d4" style="display:none">
	  <td class="label" ><span id="{$address_identifier}label_address_country_d4">{t}4rd Division{/t}</span>:</td><td  style="text-align:left">
	    <div>
	      <input id="{$address_identifier}address_country_d4_code" value="" type="hidden"/>
	      <input style="text-align:left;width:100%" id="{$address_identifier}address_country_d4" value="" ovalue="" />
	      <div id="{$address_identifier}address_country_d4_container"  ></div>
	    </div>
	  </td>
	</tr>
    <tr id="{$address_identifier}tr_address_country_d3" style="display:none">
	  <td class="label" ><span id="{$address_identifier}label_address_country_d3">{t}3rd Division{/t}</span>:</td><td  style="text-align:left">
	    <div   >
	      <input id="{$address_identifier}address_country_d3_code" value="" type="hidden"/>
	      <input style="text-align:left;width:100%" id="{$address_identifier}address_country_d3" value="" ovalue="" />
	      <div id="{$address_identifier}address_country_d3_container"  ></div>
	    </div>
	  </td>
	</tr>
    <tr id="{$address_identifier}tr_address_country_d2">
    <td class="label" ><span id="{$address_identifier}label_address_country_d2">{t}Subregion{/t}</span>:</td>
        <td  style="text-align:left">
        <div  >
	    <input style="text-align:left;width:100%" id="{$address_identifier}address_country_d2" value="" ovalue="" />
	    	      <input id="{$address_identifier}address_country_d2_code" value="" type="hidden"/>
            <div id="{$address_identifier}address_country_d2_container"  ></div>
	      </div>
	    </td>
  </tr>
    <tr id="{$address_identifier}tr_address_country_d1">
	    <td class="label" >
	    
	    <span id="{$address_identifier}label_address_country_d1">{t}Region{/t}</span>:</td>
	    <td  style="text-align:left">
	     <div  >
	      <input style="text-align:left;width:100%" id="{$address_identifier}address_country_d1" value="" ovalue="" />
	      <input id="{$address_identifier}address_country_d1_code" value="" type="hidden"/>
	    <div id="{$address_identifier}address_country_d1_container"  ></div>
	      </div>
	      </td>
  </tr>
</tbody>

<tr id="{$address_identifier}tr_address_country"  class="first">
        <td class="label" >
<span 
	        id="{$address_identifier}show_country_subregions" 
	        onclick="show_country_subregions('{$address_identifier}')" 
	        
	        style="cursor:pointer;display:none">&oplus;</span> 
            {t}Country{/t}:</td>
        <td>
        <div id="{$address_identifier}myAutoComplete" >
	        <input id="{$address_identifier}address_country" style="text-align:left;width:100%" type="text"/>
	        <div id="{$address_identifier}address_country_container" ></div>
        </div>
        
    </td>
    <td >
   
        {if $default_country_2alpha}
        <span style="margin-left:0px;;float:none" id="{$address_identifier}default_country_selector" onClick="select_default_country('{$address_identifier}','{$default_country_2alpha}')"  ><img style="cursor:pointer;vertical-align:-1px;"  src="art/flags/{$default_country_2alpha|lower}.gif" alt="({$default_country_2alpha})"/></span>	
	    <span  id="{$address_identifier}default_country_selector"></span>
	    {else}
	    {/if}
	    <span  style="margin-left:0px;;float:none"   id="{$address_identifier}browse_countries"  onClick="show_countries_list(this,'{$address_identifier}')"   class="state_details">{t}List{/t}</span>
   
  

</td>
       
    </tr>  
<tr {if $hide_buttons==true}style="display:none"{/if}>
  <td colspan=2 >
  <div class="buttons" style="margin-top:10px">
    <button address_key="" class="positive disabled" style="margin-right:10px"id="{$address_identifier}save_address_button">{t}Save{/t}</button>

  <button  class="negative disabled" close_if_reset="{if $close_if_reset}Yes{else}No{/if}" address_key="" style="{if !$close_if_reset}xvisibility:hidden{/if}" id="{$address_identifier}reset_address_button">{t}Cancel{/t}</button>
  </div>
  </td>
  </tr>
  

