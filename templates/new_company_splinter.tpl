 
  <div class="search_box" >
      

    </div>
<div>
      <span id="contact_messages"></span>
    </div>
    
    <div >
    
    
      <div id="results" style="margin-top:0px;float:right;width:390px;xheight:800px">
	
      </div>
      
      <div  style="float:left;" >
      <table class="edit" border=0  style="width:540px;margin-bottom:0px" >
  	{if $scope=='customer'}
<input type="hidden" value="{$store_key}" id="Store_Key"/>
{/if}
	{if $scope=='supplier'}
	<tr class="first"><td style="" class="label">{t}Supplier Code{/t}:</td>
	  <td  style="text-align:left">
	    <div  style="" >
	      <input style="text-align:left;" id="Supplier_Code" value="" ovalue="" valid="0">
	      <div id="Supplier_Code_Container" style="" ></div>
	    </div>
	  </td>
	  <td></td>
	</tr>

	{/if}
	
	<tr class="first">
	<td style="width:120px" class="label">{t}Company Name{/t}:</td>
	  <td  style="text-align:left;width:350px">
	    <div  style="" >
	      <input style="text-align:left;" id="Company_Name" value="" ovalue="" valid="0">
	      <div id="Company_Name_Container" style="" ></div>
	    </div>
	  </td>
	  <td style="width:70px"></td>
	</tr>
	<tr style="{if $scope=='corporation'}display:none{/if}" >
	  
	  <td class="label">{t}Contact Name{/t}:</td>
	  <td>
	    <div>
	    <input id="Contact_Name" value="" style="width:100%" />
	    <div id="Contact_Name_Container" style="" ></div>
	    </div>
	  </td>
	  <td></td>
	  
	  
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
	    <td  style="" >
	      <input id="Contact_Salutation" type="hidden" value="" ovalue="">
	      {foreach from=$prefix item=s name=foo  }
	      <span   onclick="update_salutation(this)"  label="{$s.txt}" style="{if $smarty.foreach.foo.first}margin:0;{else}margin-left:3px{/if};{if $s.relevance>1};display:none{/if} " class="Contact_Salutation small_button"   id="Contact_Salutation_{$s.txt}"  >{$s.txt}</span>
	      {/foreach}
	    </td>
	  </tr>
	  
	  <tr>
	    <td class="label">{t}First Name(s){/t}:</td>
	    <td   ><input  onkeyup="name_component_change();"  onblur="" style=""  name="first_name" id="Contact_First_Name" value=""  ovalue="" ></td>
	  </tr>
	  <tr>
	    <td class="label">{t}Surname(s){/t}:</td>
	    <td   ><input  onkeyup="name_component_change();"  onblur="" style=""  name="surname" id="Contact_Surname" value=""  ovalue="" ></td>
	  </tr>
	  <tr style="display:none">
	    <td class="label">{t}Suffix(s){/t}:</td>
	    <td   ><input  onkeyup="name_component_change();"  onblur="" style=""  name="suffix" id="Contact_Suffix" value=""  ovalue="" ></td>
	  </tr>
	  
	</tbody>
	
	<tr id="email_mould"   style="{if $scope=='corporation'}display:none{/if}"  >
	  <td  class="label"  >{t}Email{/t}:</td>
	  <td >
	    <div>
	    <input style="width:100%" id="Email" class="Email"  to_delete=0 value="" ovalue="" email_key="" valid=""   />
	    <div id="Email_Container" style="" ></div>
	    </div>
	  </td>
	</tr>
 	<tr id="telephone_mould"  style="{if $scope=='corporation'}display:none{/if}" >
	  <td  class="label">
	    
	    {t}Telephone{/t}:
	  </td>
	  <td>
	    <div>
	    <input  style="width:100%" class="Telecom" telecom_key=0 telecom_type="Telephone" id="Telephone" 
		    telecom_type_description="" container_key="" value="" ovalue="" to_delete=0       /> 
	    <div id="Telephone_Container" style="" ></div>
	    </div>
	  </td >
	  
	</tr>	
	<tr class="Telecom_Details" style="display:none" >
	  <td class="label">{t}Country Code{/t}:</td><td >
	    <input class="Country_Code" style="width:3em" value="" ovalue="" onkeyup="telecom_component_change(this)" id="Telephone_Country_Code" /></td></tr>
	<tr class="Telecom_Details" style="display:none">
	  <td  class="label"><img class="help" src="art/icons/help.png" alt="?"/> {t}NAC{/t}:</td><td ><input id="Telephone_National_Access_Code" class="National_Access_Code" style="text-align:center;width:1em" value="" ovalue=""  onkeyup="telecom_component_change(this)" /></td>
	</tr>
	
	<tr class="Telecom_Details" style="display:none">
  <td  class="label">{t}Area Code{/t}:</td><td ><input  id="Telephone_Area_Code" class="Area_Code" style="width:4em" value="" ovalue=""  onkeyup="telecom_component_change(this)"   />
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
		


{include file='edit_address_splinter.tpl' 
show_form=1  
hide_type=1 
hide_description=1 
show_default_country=1 
default_country_2alpha='gb'
hide_buttons=1
}

	<tr id="tr_source"     >
	  <td  class="label"  >{t}Source{/t}:</td>
	  <td >
	    <select>
  <option value="volvo">Volvo</option>
  <option value="saab">Saab</option>
  <option value="mercedes">Mercedes</option>
  <option value="audi">Audi</option>
</select>
	  </td>
	</tr>
	
	<tr id="tr_type"     >
	  <td  class="label"  >{t}Type{/t}:</td>
	  <td >
	    
	  </td>
	</tr>
	
	
	
    
    </table>
      <table class="options" border=0 style="margin-right:90px;float:right;padding:0">
	<tr>
		<td   id="creating_message" style="border:none;display:none">{t}Creating Contact{/t}</td>

	  <td  class="disabled" id="save_new_Company">{t}Save{/t}</td>
	  <td  id="cancel_add_Company" ">{t}Cancel{/t}</td>
	</tr>
      </table>
      <div id="Company_found_dialog" style="display:none;float:right;border:1px solid #ccc;width:200px;padding:6px 10px;margin-top:3px;font-size:80%;color:#555">
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
	  <tr><td><span  style="cursor:pointer;text-decoration:underline" id="force_new">{t}Confirm is new contact and Save{/t}</span><br><span style="color:red">{t}Previous contact data (email) will be deleted to avoid muliplicity{/t}</span></td></tr>
	</table>
      </div>

      <div style="clear:both;padding:10px;" id="validation">

	<div style="font-size:80%;margin-bottom:10px;display:none" id="mark_Company_found">{t}Company has been found{/t}</div>
	
	<table class="form_state" style="display:none">
	  <caption>{t}State of the form{/t}</caption>
	  <tr><th style="width:10em"></th><th style="width:50px">{t}Show{/t}</th><th>{t}Input{/t}</th><th>{t}Valid{/t}</th></tr>
	  <tr><td class="aleft">{t}Company Name{/t}</td><td style="text-align:center"  id="company_name_show"><img src='art/icons/accept.png'></td><td id="company_name_inputed"></td><td id="company_name_valid"></td></tr>
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
    
<div class="star_rating" id="star_rating_template" style="display:none"><img class="star" src="art/icons/star_dim.png" /><img class="star" src="art/icons/star_dim.png" /><img class="star" src="art/icons/star_dim.png" /><img class="star" src="art/icons/star_dim.png" /><img class="star" src="art/icons/star_dim.png" /></div>
