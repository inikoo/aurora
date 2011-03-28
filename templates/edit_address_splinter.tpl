<tbody id="{$address_identifier}address_form" style="{if !$show_form}display:none{/if}">
    <input type="hidden" id="{$address_identifier}address_key" value="" ovalue="" >
    <input type="hidden" id="{$address_identifier}address_fuzzy" value="Yes" ovalue="Yes" >
    <input id="{$address_identifier}address_country_code" value="" type="hidden">
    <input id="{$address_identifier}address_country_2acode" value="" type="hidden">
    
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
    <td class="label">{t}Address Function{/t}:</td><td  style="text-align:left"   id="{$address_identifier}address_function" value="{$function_value}" ovalue=""  >
      <span id="{$address_identifier}address_function_Contact" label="Contact" onclick="toggle_address_function(this)" class="small_button address_function" style="margin:0">Contact</span>
      <span id="{$address_identifier}address_function_Billing" label="Billing" onclick="toggle_address_function(this)" class="small_button address_function" style="margin-left:3px">Billing</span>
      <span id="{$address_identifier}address_function_Shipping" label="Shipping" onclick="toggle_address_function(this)" class="small_button address_function" style="margin-left:3px">Shipping</span>
      <span id="{$address_identifier}address_function_Other" label="Other" onclick="toggle_address_function(this)" class="small_button address_function" style="margin-left:3px">Other</span>
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
    <td ></td><td style="text-align:left"><div  style="text-align:left;float:left;height:10px;width:210px;border-top:1px solid #ddd"></div></td>
  </tr>
   
    <tbody id="{$address_identifier}address_components" style="display:none">
    
    <tr id="{$address_identifier}tr_address_internal">
    <td class="label" >{t}Internal{/t}:</td>
    <td  style="text-align:left">
        <input style="text-align:left;width:100%" id="{$address_identifier}address_internal" value="" ovalue="" />
    </td>
  </tr>
   <tr id="{$address_identifier}tr_address_building">
    <td class="label" >{t}Building{/t}:</td><td  style="text-align:left"><input style="text-align:left;width:100%" id="{$address_identifier}address_building" value="" ovalue="" /></td>
  </tr>
   <tr id="{$address_identifier}tr_address_street">
    <td class="label" >{t}Street/Number{/t}:</td>
    <td  style="text-align:left"><input style="text-align:left;width:100%" id="{$address_identifier}address_street" value="" ovalue="" /></td>
    </tr>
     <tr id="{$address_identifier}tr_address_town_d1" style="display:none">
    <td class="label"  >
	      <span id="{$address_identifier}show_town_d2" onclick="toggle_town_d2()" class="small_button" style="padding:0 1px;font-size:50%;position:relative;top:-2px">x</span> {t}City 1st Div{/t}:</td>
    <td  style="text-align:left"><input style="text-align:left;width:100%" id="{$address_identifier}address_town_d1" value="" ovalue="" /></td>
  </tr>
    <tr id="{$address_identifier}tr_address_town_d2" style="display:none">
    <td class="label" >{t}City 2nd Div{/t}:</td><td  style="text-align:left"><input style="text-align:left;width:100%" id="{$address_identifier}address_town_d2" value="" ovalue="" /></td>
  </tr>
      <tr id="{$address_identifier}tr_address_town">
    <td class="label" >
      <span id="{$address_identifier}show_town_d1" onclick="toggle_town_d1()" class="small_button" style="padding:0 1px;font-size:50%;position:relative;top:-2px">+</span> {t}City{/t}:</td>
    <td  style="text-align:left">
     <div >
    <input style="text-align:left;width:100%" id="{$address_identifier}address_town" value="" ovalue="" />
      <input id="{$address_identifier}address_town_code" value="" type="hidden"/>
<div id="{$address_identifier}address_town_container" style="" ></div>
	      </div>
    </td>
  </tr>
    <tr id="{$address_identifier}tr_address_country_d1">
	    <td class="label" >
	    <span id="{$address_identifier}show_country_d2" onclick="toggle_country_d2()" 
	        class="small_button" style="padding:0 1px;font-size:50%;position:relative;top:0px;display:none">+</span> 
	    <span id="{$address_identifier}label_address_country_d1">{t}Region{/t}</span>:</td>
	    <td  style="text-align:left">
	     <div style="" >
	      <input style="text-align:left;width:100%" id="{$address_identifier}address_country_d1" value="" ovalue="" />
	      <input id="{$address_identifier}address_country_d1_code" value="" type="hidden"/>
	    <div id="{$address_identifier}address_country_d1_container" style="" ></div>
	      </div>
	      </td>
  </tr>
    <tr id="{$address_identifier}tr_address_country_d2">
    <td class="label" ><span id="{$address_identifier}label_address_country_d2">{t}Subregion{/t}</span>:</td>
        <td  style="text-align:left">
        <div style="" >
	    <input style="text-align:left;width:100%" id="{$address_identifier}address_country_d2" value="" ovalue="" />
	    	      <input id="{$address_identifier}address_country_d2_code" value="" type="hidden"/>
<div id="{$address_identifier}address_country_d2_container" style="" ></div>
	      </div>
	    </td>
  </tr>
  
     <tr id="tr_address_country_d3" style="display:none">
	  <td class="label" style=""><span id="label_address_country_d3">{t}3rd Division{/t}</span>:</td><td  style="text-align:left">
	    <div class="AutoComplete" style="" >
	      <input id="address_country_d3_code" value="" type="hidden">
	      <input style="text-align:left;" id="address_country_d3" value="" ovalue="" >
	      <div id="address_country_d3_container" style="" ></div>
	    </div>
	  </td>
	</tr>
    <tr id="tr_address_country_d4" style="display:none">
	  <td class="label" style=""><span id="label_address_country_d4">{t}4rd Division{/t}</span>:</td><td  style="text-align:left">
	    <div class="AutoComplete" style="" >
	      <input id="address_country_d4_code" value="" type="hidden">
	      <input style="text-align:left;" id="address_country_d4" value="" ovalue="" >
	      <div id="address_country_d4_container" style="" ></div>
	    </div>
	  </td>
	</tr>
	  <tr id="tr_address_country_d5" style="display:none">
	  <td class="label" style=""><span id="label_address_country_d5">{t}5rd Division{/t}</span>:</td><td  style="text-align:left">
	    <div class="AutoComplete" style="" >
	      <input id="address_country_d5_code" value="" type="hidden">
	      <input style="text-align:left;" id="address_country_d5" value="" ovalue="" >
	      <div id="address_country_d5_container" style="" ></div>
	    </div>
	  </td>
	</tr>
	
  
    <tr id="{$address_identifier}tr_address_postal_code">
    <td    id="{$address_identifier}label_address_postal_code"     class="label" >{t}Postal Code{/t}:<img  id="address_postal_code_warning" title=""  src="art/icons/exclamation.png" style="margin-left:5px;visibility:hidden" />
    </td>
    <td  style="text-align:left">
    <div>
    <input style="text-align:left;" id="{$address_identifier}address_postal_code" value="" ovalue=""  />
     <input id="{$address_identifier}address_postal_code_code" value="" type="hidden"/>
<div id="{$address_identifier}address_postal_code_container" style="" ></div>
	      </div>
    </td>
  </tr>
  
   
   
   
    
    </tbody>
     <tr id="{$address_identifier}tr_address_country" class="first">
        <td class="label" >
            <span id="{$address_identifier}show_country_d1" onclick="toggle_country_d1()" class="small_button" style="padding:0 1px;font-size:50%;display:none">+</span>
            {t}Country{/t}:</td>
        <td>
        <div id="{$address_identifier}myAutoComplete" >
	        <input id="{$address_identifier}address_country" style="text-align:left;width:100%" type="text"/>
	        <div id="{$address_identifier}address_country_container" ></div>
        </div>
        
    </td>
    <td>
    <div id="{$address_identifier}country_options" class="general_options" >
        <span style="margin-left:0px;;float:none" id="{$address_identifier}default_country_selector" onClick="select_default_country('{$address_identifier}','{$default_country_2alpha}')"  ><img style="cursor:pointer;vertical-align:-1px;"  src="art/flags/{$default_country_2alpha}.gif" alt="({$default_country_2alpha})"/></span>	
	    <span  style="margin-left:0px;;float:none"   id="{$address_identifier}browse_countries"  onClick="show_countries_list('{$address_identifier}')"   class="state_details">{t}Browse{/t}</span>

      </div>
       <div id="{$address_identifier}show_country_options"  class="general_options" style="display:none">
  
        <span  style="margin-left:0px;;float:none"   id="{$address_identifier}change_country_address" onClick="show_country_options('{$address_identifier}')"  class="state_details">{t}Change{/t}</span>

      </div>

</td>
       
    </tr>  

    <tr {if $hide_buttons==true}style="display:none"{/if}>
  <td colspan=2 style="text-align:right"><button close_if_reset="{if $close_if_reset}Yes{else}No{/if}" address_key="" style="{if !$close_if_reset}visibility:hidden{/if}" id="{$address_identifier}reset_address_button">{t}Cancel{/t}</button><button address_key="" style="visibility:hidden;margin-left:10px"id="{$address_identifier}save_address_button">{t}Save{/t}</button></td>
  </tr>
</tbody>


<div id="dialog_country_list">
    <div class="splinter_cell" style="padding:10px 15px 10px 0;border:none">
        <div id="the_table" class="data_table" >
            <span class="clean_table_title">{t}Country List{/t}</span>
            {include file='table_splinter.tpl' table_id=2 filter_name=$filter_name2 filter_value=$filter_value2}
            <div  id="table2"   class="data_table_container dtable btable "> </div>
        </div>
    </div>
 </div>
