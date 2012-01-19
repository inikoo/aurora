<div class="search_box" ></div>
<div id="contact_messages_div" >
      <span id="contact_messages"></span>
    </div>
<div >
     <div id="results" style="margin-top:0px;float:right;width:390px;"></div>
	 <div  style="float:left;width:540px;" >
    <table class="edit"  border="0" style="width:100%;margin-bottom:0px" >
	
 <tr class="title">
      <td colspan=3>{t}Contact Info{/t}</td>
      </tr>
	  
	  <td style=";" class="label">{t}Contact Name{/t}:</td>
	  <td>
	    
	    <input style="width:18em" id="Contact_Name" value="" >
	    <div id="Contact_Name_Container"  ></div>
	    
	  </td>
	  	{if $scope=='customer'}
<input type="hidden" value="{$store_key}" id="Store_Key"/>
{/if}
	  
	<tr id="tr_Contact_Gender" style="display:none">
	  <td class="label">{t}Gender{/t}:</td>
	  <td> 
	    <input type="hidden" id="Contact_Gender"/>
	    <span id="Contact_Gender_Male" label="Male" onclick="toggle_Contact_Gender(this)" class="small_button Contact_Gender" style="margin:0">{t}Male{/t}</span>
	    <span id="Contact_Gender_Female" label="Famale" onclick="toggle_Contact_Gender(this)" class="small_button Contact_Gender" style="margin-left:3px">{t}Female{/t}</span>
	    <span id="Contact_Gender_Unknown" label="Unknown" onclick="toggle_Contact_Gender(this)" class="small_button Contact_Gender" style="margin-left:3px">{t}Unknown{/t}</span>
	  </td>
	</tr>
	
	<tbody style="display:none">
	  <tr>
	    <td class="label" >{t}Salutation{/t}:</td>
	    <td   >
	      <input id="Contact_Salutation" type="hidden" value="" ovalue="">
	      {foreach from=$prefix item=s name=foo  }
	      <span   onclick="update_salutation(this)"  label="{$s.txt}" style="{if $smarty.foreach.foo.first}margin:0;{else}margin-left:3px{/if};{if $s.relevance>1};display:none{/if} " class="Contact_Salutation small_button"   id="Contact_Salutation_{$s.txt}"  >{$s.txt}</span>
	      {/foreach}
	    </td>
	  </tr>
	  
	  <tr>
	    <td class="label">{t}First Name(s){/t}:</td>
	    <td   ><input  onkeyup="name_component_change();"  onblur="" style="width:12em"  name="first_name" id="Contact_First_Name" value=""  ovalue="" ></td>
	  </tr>
	  <tr>
	    <td class="label">{t}Surname(s){/t}:</td>
	    <td   ><input  onkeyup="name_component_change();"  onblur="" style="width:12em"  name="surname" id="Contact_Surname" value=""  ovalue="" ></td>
	  </tr>
	  <tr style="display:none">
	    <td class="label">{t}Suffix(s){/t}:</td>
	    <td   ><input  onkeyup="name_component_change();"  onblur="" style="width:12em"  name="suffix" id="Contact_Suffix" value=""  ovalue="" ></td>
	  </tr>
	  
	</tbody>
	
	<tr id="email_mould" >
	  <td  class="label"  >{t}Email{/t}:<img  id="{$address_identifier}email_warning" title=""  src="art/icons/exclamation.png" style="margin-left:5px;visibility:hidden" /></td>
	  <td >
	    <input style="width:18em" id="Email" class="Email"  to_delete=0 value="" ovalue="" email_key="" valid=""   />
	    <div id="Email_Container"  ></div>
	    
	  </td>
	</tr>
 


	<tr id="telephone_mould" c>
	  <td  class="label">
	    
	    {t}Telephone{/t}:<img  id="{$address_identifier}telephone_warning" title=""  src="art/icons/exclamation.png" style="margin-left:5px;visibility:hidden" />
	  </td>
	  <td>
	    
	    <input  style="width:18em" class="Telecom" telecom_key=0 telecom_type="Telephone" id="Telephone" 
		    telecom_type_description="" container_key="" value="" ovalue="" to_delete=0       /> 
	    <div id="Telephone_Container"  ></div>
	  </td >
	  
	</tr>	
	<tr class="Telecom_Details" style="display:none" >
	  <td class="label">{t}Country Code{/t}:</td><td >
	    <input class="Country_Code" style="width:3em" value="" ovalue="" onkeyup="telecom_component_change(this)" id="Telephone_Country_Code" /></td></tr>
	<tr class="Telecom_Details" style="display:none">
	  <td  class="label"><img class="help" src="art/icons/help.png" alt="?"/> {t}NAC{/t}:</td><td ><input id="Telephone_National_Access_Code" class="National_Access_Code" style="text-align:center;width:1em" value="" ovalue=""  onkeyup="telecom_component_change(this)" /></td>
	</tr>
	
	<tr class="Telecom_Details" style="display:none">
  <td  class="label">{t}Area Code{/t}:</td><td ><input  id="Teleohone_Area_Code" class="Area_Code" style="width:4em" value="" ovalue=""  onkeyup="telecom_component_change(this)"   />
		  </td>
	</tr>
	  <tr class="Telecom_Details" style="display:none">
	    <td  class="label">{t}Number{/t}:</td><td ><input  id="Telephone_Number" class="Number" style="width:7em" value="" ovalue=""  onkeyup="telecom_component_change(this)"   />
	    </td>
	  </tr>
	  <tr class="Telecom_Details" style="display:none">
	    <td  class="label">{t}Extension{/t}:</td><td ><input id="Telephone_Extension" class="Extension" style="width:5em" value="" ovalue=""  onkeyup="telecom_component_change(this)"   />
	    </td>
	  </tr>
	  
	  

	<tr id="mobile_mould" c>
	  <td  class="label">
	    
	    {t}Mobile{/t}:
	  </td>
	  <td>
	    
	    <input  style="width:18em" class="Telecom" telecom_key=0 telecom_type="Mobile" id="Mobile" 
		    telecom_type_description="" container_key="" value="" ovalue="" to_delete=0       /> 
	    <div id="Mobile_Container"  ></div>
	  </td >
	  
	</tr>	
	<tr class="Telecom_Details" style="display:none" >
	  <td class="label">{t}Country Code{/t}:</td><td >
	    <input class="Country_Code" style="width:3em" value="" ovalue="" onkeyup="telecom_component_change(this)" id="Mobile_Country_Code" /></td></tr>
	<tr class="Telecom_Details" style="display:none">
	  <td  class="label"><img class="help" src="art/icons/help.png" alt="?"/> {t}NAC{/t}:</td><td ><input id="Mobile_National_Access_Code" class="National_Access_Code" style="text-align:center;width:1em" value="" ovalue=""  onkeyup="telecom_component_change(this)" /></td>
	</tr>
	
	<tr class="Telecom_Details" style="display:none">
  <td  class="label">{t}Area Code{/t}:</td><td ><input  id="Teleohone_Area_Code" class="Area_Code" style="width:4em" value="" ovalue=""  onkeyup="telecom_component_change(this)"   />
		  </td>
	</tr>
	  <tr class="Telecom_Details" style="display:none">
	    <td  class="label">{t}Number{/t}:</td><td ><input  id="Mobile_Number" class="Number" style="width:7em" value="" ovalue=""  onkeyup="telecom_component_change(this)"   />
	    </td>
	  </tr>
	  <tr class="Telecom_Details" style="display:none">
	    <td  class="label">{t}Extension{/t}:</td><td ><input id="Mobile_Extension" class="Extension" style="width:5em" value="" ovalue=""  onkeyup="telecom_component_change(this)"   />
	    </td>
	  </tr>
	  		
 <tr class="title">
      <td colspan=3>{t}Address Info{/t}</td>
      </tr>
{include file='edit_address_splinter.tpl' 
show_form=1  
hide_type=1 
hide_description=1 
show_default_country=1 
default_country_2alpha='gb'
hide_buttons=1
}


{if $scope=='customer'}
<tr class="title">
      <td colspan=3>{t}Other Info{/t}</td>
      </tr>

{foreach from=$categories item=cat key=cat_key name=foo  }
 <tr>
 
 <td class="label">{t}{$cat->get('Category Name')}{/t}:</td>
 <td>
  <select id="cat{$cat_key}" cat_key="{$cat_key}"  onChange="update_category(this)">
    {foreach from=$cat->get_children_objects() item=sub_cat key=sub_cat_key name=foo2  }
        {if $smarty.foreach.foo2.first}
        <option  value="">{t}Unknown{/t}</option>
        {/if}
        <option value="{$sub_cat->get('Category Key')}">{$sub_cat->get('Category Name')}</option>
    {/foreach}
  </select>
  
 </td>   
</tr>
{/foreach}
{/if}



	  
      </table>
  <table class="options" style="float:right;padding:0;margin:0">
	<tr>
	<td   id="creating_message" style="border:none;display:none">{t}Creating Contact{/t}</td>
	  <td  class="disabled" id="save_new_Contact">{t}Save{/t}</td>
	  <td  id="cancel_add_Contact" >{t}Cancel{/t}</td>
	</tr>
      </table>
    
        <div id="Customer_found_dialog" style="display:none;float:right;border:1px solid #ccc;width:200px;padding:6px 10px;margin-top:3px;font-size:80%;color:#555">
	{t}Another customer has been found with the similar details{/t}.
	<table style="margin:10px 0">
	  <tr><td><span  style="cursor:pointer;text-decoration:underline" onClick="edit_founded_customer()"    id="pick_founded_customer">{t}Edit the located customer{/t} (<span id="founded_name_customer"></span>)</span></td></tr>
	  <tr><td><span  style="cursor:pointer;text-decoration:underline"  id="save_when_founded_customer" >{t}Confirm is new customer and Save{/t}</span></td></tr>
	</table>
      </div>
    
    
    <div id="Contact_found_dialog" style="display:none;float:right;border:1px solid #ccc;width:200px;padding:6px 10px;margin-top:3px;font-size:80%;color:#555">
	{t}Another contact has been found with the similar details{/t}.
	<table style="margin:10px 0">
	  <tr><td><span  style="cursor:pointer;text-decoration:underline" onClick="edit_founded()"    id="pick_founded">{t}Edit the located contact{/t} (<span id="founded_name"></span>)</span></td></tr>
	  <tr><td><span  style="cursor:pointer;text-decoration:underline"  id="save_when_founded" >{t}Confirm is new contact and Save{/t}</span></td></tr>
	</table>
      </div>
   
   
   
    <div id="email_found_dialog" style="display:none;float:right;border:1px solid #ccc;width:200px;padding:6px 10px;margin-top:3px;font-size:80%;color:#555">
	{t}Another contact has the same email{/t}.
	
	<table style="margin:10px 0">
	  <tr><td style="cursor:pointer;text-decoration:underline" onclick="edit_founded()">{t}Edit the located contact{/t}</td></tr>
	  <tr><td><span  style="cursor:pointer;text-decoration:underline" id="force_new">{t}Confirm is new contact and Save{/t}</span><br><span style="color:red">{t}Previous contact data will be deleted to avoid muliplicity{/t}</span></td></tr>
	</table>
      </div>

      <div style="clear:both;padding:10px;display:none" id="validation">

	<div style="font-size:80%;margin-bottom:10px;display:none" id="mark_contact_found">{t}Contact found{/t}</div>
	
	<table class="form_state">
	  <caption>{t}State of the form{/t}</caption>
	  <tr><th style="width:10em"></th><th style="width:50px">{t}Show{/t}</th><th>{t}Input{/t}</th><th>{t}Valid{/t}</th></tr>
	  <tr><td class="aleft">{t}Contact Name{/t}</td><td id="contact_name_show"><img src='art/icons/accept.png'></td><td id="contact_name_inputed"></td><td id="contact_name_valid"></td></tr>
	  <tr><td class="aleft">{t}Email{/t}</td><td id="email_show"><img src='art/icons/accept.png'></td><td id="email_inputed"></td><td id="email_valid"></td></tr>
	  <tr><td class="aleft">{t}Telephone{/t}</td><td id="telephone_show"><img src='art/icons/accept.png'></td><td id="telephone_inputed"></td><td id="telephone_valid"></td></tr>
	  <tr><td class="aleft">{t}Address{/t}</td><td id="address_show"></td><td id="address_inputed"></td><td id="address_valid"></td></tr>
	  <tr><td class="aright">{t}Country{/t}</td><td id="country_show"><img src='art/icons/accept.png'></td><td id="country_inputed"></td><td id="country_valid"></td></tr>
	  <tr><td class="aright">{t}Country{/t} 1D</td><td id="country_d1_show"><img src='art/icons/accept.png'></td><td id="country_d1_inputed"></td><td id="country_d1_valid"></td></tr>
	  <tr><td class="aright">{t}Country{/t} 2D</td><td id="country_d2_show"></td><td id="country_d1_inputed"></td><td id="country_d2_valid"></td></tr>
	  <tr><td class="aright">{t}Country{/t} 3D</td><td id="country_d3_show"></td><td id="country_d1_inputed"></td><td id="country_d3_valid"></td></tr>
	  <tr><td class="aright">{t}Country{/t} 4D</td><td id="country_d4_show"></td><td id="country_d1_inputed"></td><td id="country_d4_valid"></td></tr>
	  <tr><td class="aright">{t}Country{/t} 5D</td><td id="country_d5_show"></td><td id="country_d1_inputed"></td><td id="country_d5_valid"></td></tr>
	  <tr><td class="aright">{t}Postal Code{/t}</td><td id="postal_code_show"><img src='art/icons/accept.png'></td><td id="postal_code_inputed"></td><td id="postal_code_valid"></td></tr>
	  <tr><td class="aright">{t}Town{/t}</td><td id="town_show"><img src='art/icons/accept.png'></td><td id="town_inputed"></td><td id="town_valid"></td></tr>

	</table>
      </div>

      </div>
      <div style="clear:both;height:40px"></div>
	</div>
      </div>
<div class="star_rating" id="star_rating_template" style="display:none"><img class="star" src="art/icons/star_dim.png" /><img class="star" src="art/icons/star_dim.png" /><img class="star" src="art/icons/star_dim.png" /><img class="star" src="art/icons/star_dim.png" /><img class="star" src="art/icons/star_dim.png" /></div>
