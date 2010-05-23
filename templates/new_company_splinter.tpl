 
  <div class="search_box" >
      

    </div>
<div>
      <span id="contact_messages"></span>
    </div>
    
    <div >
      <div id="results" style="margin-top:0px;xborder:1px solid #777;float:right;width:500px;xheight:800px">
	
      </div>
      
      <div  style="float:left;width:370px" >
      <table class="edit" border=0 >
	{if $scope=='customer'}
<input type="hidden" value="{$store_key}" id="Store_Key"/>
{/if}
	{if $scope=='supplier'}
	<tr class="first"><td style="" class="label">{t}Supplier Code{/t}:</td>
	  <td  style="text-align:left">
	    <div  style="width:15em;position:relative;top:00px" >
	      <input style="text-align:left;width:18em" id="Supplier_Code" value="" ovalue="" valid="0">
	      <div id="Supplier_Code_Container" style="" ></div>
	    </div>
	  </td>
	</tr>

	{/if}
	
	<tr class="first"><td style="" class="label">{t}Company Name{/t}:</td>
	  <td  style="text-align:left">
	    <div  style="width:15em;position:relative;top:00px" >
	      <input style="text-align:left;width:18em" id="Company_Name" value="" ovalue="" valid="0">
	      <div id="Company_Name_Container" style="" ></div>
	    </div>
	  </td>
	</tr>
	<tr style="{if $scope=='corporation'}display:none{/if}" >
	  
	  <td class="label">{t}Contact Name{/t}:</td>
	  <td>
	    
	    <input style="width:18em" id="Contact_Name" value="" >
	    <div id="Contact_Name_Container" style="" ></div>
	    
	  </td>
	  
	  
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
	
	<tr id="email_mould"   style="{if $scope=='corporation'}display:none{/if}"  >
	  <td  class="label"  >{t}Email{/t}:</td>
	  <td >
	    <input style="width:18em" id="Email" class="Email"  to_delete=0 value="" ovalue="" email_key="" valid=""   />
	    <div id="Email_Container" style="" ></div>
	    
	  </td>
	</tr>
 	<tr id="telephone_mould"  style="{if $scope=='corporation'}display:none{/if}" >
	  <td  class="label">
	    
	    {t}Telephone{/t}:
	  </td>
	  <td>
	    
	    <input  style="width:18em" class="Telecom" telecom_key=0 telecom_type="Telephone" id="Telephone" 
		    telecom_type_description="" container_key="" value="" ovalue="" to_delete=0       /> 
	    <div id="Telephone_Container" style="" ></div>
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
<tr class="first">
	    
  <td class="label" style="width:160px">
    <span id="show_country_d1" onclick="toggle_country_d1()" class="small_button" 
	  style="padding:0 1px;font-size:50%;position:relative;top:0px;display:none">+</span>
    Country:</td>
  <td  style="text-align:left">
	      <div  style="width:15em;position:relative;top:00px" >
		<input id="address_country" style="text-align:left;width:18em" type="text">
		<div id="address_country_container" style="" ></div>
	      </div>
	    </td>
	  </tr>
	    <input id="address_country_code" value="UNK" type="hidden">
	    <input id="address_country_2acode" value="XX" type="hidden">

<tr id="tr_address_country_d1">
	    <td class="label" style="width:160px">
	    <span id="show_country_d2" onclick="toggle_country_d2()" 
	        class="small_button" style="padding:0 1px;font-size:50%;position:relative;top:0px;display:none">+</span> 
	    <span id="label_address_country_d1">{t}Region{/t}</span>:</td>
	    <td  style="text-align:left">
	     <div id="myAutoComplete" style="width:15em;" >
	      <input style="text-align:left;width:18em" id="address_country_d1" value="" ovalue="" >
	      <input id="address_country_d1_code" value="" type="hidden">
	    <div id="address_country_d1_container" style="" ></div>
	      </div>
	      </td>
	  </tr>
	  <tr id="tr_address_country_d2" style="display:none">
	    <td class="label" style="width:160px"><span id="label_address_country_d2">{t}Subregion{/t}</span>:</td><td  style="text-align:left">
	     <div class="AutoComplete" style="width:15em;" >
	      <input id="address_country_d2_code" value="" type="hidden">
	      <input style="text-align:left;width:18em" id="address_country_d2" value="" ovalue="" >
	      <div id="address_country_d2_container" style="" >
	      </div>
	     </div>
	    </td>
	  </tr>
	<tr id="tr_address_country_d3" style="display:none">
	  <td class="label" style="width:160px"><span id="label_address_country_d3">{t}3d Division{/t}</span>:</td><td  style="text-align:left">
	    <div class="AutoComplete" style="width:15em;" >
	      <input id="address_country_d3_code" value="" type="hidden">
	      <input style="text-align:left;width:18em" id="address_country_d3" value="" ovalue="" >
	      <div id="address_country_d3_container" style="" ></div>
	    </div>
	  </td>
	</tr>
	<tr id="tr_address_country_d4" style="display:none">
	  <td class="label" style="width:160px"><span id="label_address_country_d4">{t}4d Division{/t}</span>:</td><td  style="text-align:left">
	    <div class="AutoComplete" style="width:15em;" >
	      <input id="address_country_d4_code" value="" type="hidden">
	      <input style="text-align:left;width:18em" id="address_country_d4" value="" ovalue="" >
	      <div id="address_country_d4_container" style="" ></div>
	    </div>
	  </td>
	</tr>	
	<tr id="tr_address_country_d5" style="display:none">
	  <td class="label" style="width:160px"><span id="label_address_country_d5">{t}5d Division{/t}</span>:</td><td  style="text-align:left">
	    <div class="AutoComplete" style="width:15em;" >
	      <input id="address_country_d5_code" value="" type="hidden">
	      <input style="text-align:left;width:18em" id="address_country_d5" value="" ovalue="" >
	      <div id="address_country_d5_container" style="" ></div>
	    </div>
	  </td>
	</tr>	
	
	

	<tr   id="tr_address_postal_code">
	  <td class="label"  style="width:160px;background:none"><img  id="address_postal_code_warning" title=""  src="art/icons/exclamation.png" style="float:left;visibility:hidden" /> {t}Postal Code{/t}:</td>
	  <td  style="text-align:left">
	    <input style="text-align:left;width:18em" id="address_postal_code" value="" ovalue="" valid="0"  >
	    <div id="address_postal_code_container" style="" ></div>
	  </td>
	</tr>
	
	  <tr>
	    <td class="label" style="width:160px">
	      <span id="show_town_d1" onclick="toggle_town_d1()" class="small_button" style="padding:0 1px;font-size:50%;position:relative;top:-2px">+</span> {t}Town/City{/t}:</td>
	      <td  style="text-align:left">
		<div class="AutoComplete" style="width:15em;" >
		<input style="text-align:left;width:18em" id="address_town" value="" ovalue="" >
		 <div id="address_town_container" style="" ></div>
	      </div>
	      </td>
	  </tr>
	  <tr style="display:none" id="tr_address_town_d1">
	    <td class="label" style="width:160px" >
	      <span id="show_town_d2" onclick="toggle_town_d2()" class="small_button" style="padding:0 1px;font-size:50%;position:relative;top:-2px">x</span> {t}City 1st Div{/t}:</td>
	      <td  style="text-align:left">
		<input style="text-align:left;width:18em" id="address_town_d1" value="" ovalue="" >
		<div id="address_town_d1_container" style="" ></div>
	      </td>
	  </tr>
	  <tr style="display:none;" id="tr_address_town_d2">
	    <td class="label" style="width:160px">{t}City 2nd Div{/t}:</td><td  style="text-align:left"><input style="text-align:left;width:18em" id="address_town_d2" value="" ovalue="" ></td>
	  </tr>
	  <tr>
	    <td class="label" style="width:160px">{t}Street/Number{/t}:</td><td  style="text-align:left"><input style="text-align:left;width:18em" id="address_street" value="" ovalue="" ></td>
	  <tr>
	    <td class="label" style="width:160px">{t}Building{/t}:</td><td  style="text-align:left"><input style="text-align:left;width:18em" id="address_building" value="" ovalue="" ></td>
	  </tr>
	  <tr >
	    <td class="label" style="width:160px">{t}Internal{/t}:</td><td  style="text-align:left"><input style="text-align:left;width:18em" id="address_internal" value="" ovalue="" ></td>
	  </tr>
	  
      </table>





      <table class="options" style="float:right;padding:0;margin:0">
	<tr>
	  <td  class="disabled" id="save_new_company">{t}Save{/t}</td>
	  <td  id="cancel_add_company" onClick="window.location='companies.php?edit=1'">{t}Cancel{/t}</td>
	</tr>
      </table>
      <div id="company_found_dialog" style="display:none;float:right;border:1px solid #ccc;width:200px;padding:6px 10px;margin-top:3px;font-size:80%;color:#555">
	{t}Another contact has been found with the similar details{/t}.
	<table style="margin:10px 0">
	  <tr><td><span  style="cursor:pointer;text-decoration:underline" id="pick_founded">{t}Pick contact founded{/t} (<span id="founded_name"></span>)</span></td></tr>
	  <tr><td><span  style="cursor:pointer;text-decoration:underline"    onClick="force_new()" >{t}Confirm is new contact and Save{/t}</span></td></tr>
	</table>
      </div>
      <div id="email_found_dialog" style="display:none;float:right;border:1px solid #ccc;width:200px;padding:6px 10px;margin-top:3px;font-size:80%;color:#555">
	{t}Another contact has the same email{/t}.
	<table style="margin:10px 0">
	  <tr><td style="cursor:pointer" onclick="edit_contact_founded()">{t}Edit contact founded{/t}</td></tr>
	  <tr><td><span  style="cursor:pointer;text-decoration:underline" id="force_new" onClick="force_new()">{t}Confirm is new contact and Save{/t}</span><br><span style="color:red">{t}Founded contact data will be deleted to avoid muliplicity{/t}</span></td></tr>
	</table>
      </div>

      <div style="clear:both;padding:10px;" id="validation">

	<div style="font-size:80%;margin-bottom:10px;display:none" id="mark_company_found">{t}Company found{/t}</div>
	
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
      </div>
<div class="star_rating" id="star_rating_template" style="display:none"><img class="star" src="art/icons/star_dim.png" /><img class="star" src="art/icons/star_dim.png" /><img class="star" src="art/icons/star_dim.png" /><img class="star" src="art/icons/star_dim.png" /><img class="star" src="art/icons/star_dim.png" /></div>
