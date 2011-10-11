{include file='header.tpl'}
<div id="bd" style="padding:0 20px">
<span class="nav2 onright"><a   href="{$scope}.php?id={$scope_key}">{t}Exit Edit{/t}</a></span>
<span class="nav2"><a href="contacts.php">{$home}</a></span>
<div id="yui-main" >
    
    <div class="search_box" >
      

    </div>
    
    <div >
      <h1>{t}Editing company{/t} {$company->get(ID)}</h1>

      <div class="chooser2" style="display:none">
	<ul >
	  <li id="details" {if $edit=='details'}class="selected"{/if} ><img src="art/icons/information.png"> {t}Details{/t}</li>
	  <li id="address" {if $edit=='address'}class="selected"{/if} > <img src="art/icons/building.png"> {t}Address{/t}</li>
	  <li id="contacts" {if $edit=='contacts'}class="selected"{/if} ><img src="art/icons/user.png"> {t}Contacts{/t}</li>


	</ul>
      </div>


      <div style="display:none;clear:both;height:3em;padding:10px 20px;;margin:20px 0;border-top: 1px solid #cbb;;border-bottom: 1px solid #caa;width:770px;" id="contacts_messages">
	<div xstyle="float:left">
	  <span class="save" style="display:none" id="description_save" onclick="save('description')">Save</span><span id="description_reset"  style="display:none"   class="reset" onclick="reset('description')">Reset</span>
	</div>
	<span class="details">Number of changes:<span id="contacts_num_changes">0</span></span>
	
	<div id="description_errors">
	</div>
	<div id="description_warnings">
	</div>
      </div>
      
  <div  style="margin:0"  class="edit_block" id="d_details">
	<table class="edit" border=0>
	  
	  <tr class="title">
	    <td colspan="2" style="width:160px">Company Details: <span class="state_details" id="details_messages"></span></td>
	    <td  style="text-align:right">
	      <span style="display:none" class="small_button" id="cancel_save_details_button" >Cancel</span>
	      <span  style="display:none" class="small_button" id="save_details_button" >Save</span></td>
	  </tr>
	  <tr class="first"><td style="width:160px">Public Name:</td><td  style="text-align:left">
	  <input style="text-align:left;width:12em" id="name" value="{$company->get('Name','addslashes')}" ovalue="{$company->get('Company Name')}"></td>
	  </tr>
	  <tr class="first"><td style="width:160px">Fiscal Name:</td><td style="text-align:left"><input style="text-align:left;width:12em" id="fiscal_name" value="{$company->get('Company Fiscal Name')}" ovalue="{$company->get('Company Fiscal Name')}" ></td>
	  </tr>
	  <tr class="first"><td style="width:160px">Tax Number:</td><td  style="text-align:left"><input style="text-align:left;width:12em" id="tax_number" value="{$company->get('Company Tax Number')}" ovalue="{$company->get('Company Tax Number')}" ></td>
	  </tr>
	  <tr class="first"><td style="width:160px">Registration Number:</td><td style="text-align:left"><input style="text-align:left;width:12em" id="registration_number" value="{$company->get('Company Registration Number')}" ovalue="{$company->get('Company Registration Number')}" ></td>
	  </tr>
	</table>
      </div>

  <div  style="margin:0"  class="edit_block" id="d_address">

	<table class="edit" border=0 >
	  
	  <tr class="title">
	    <td style="width:160px">Address: <span class="state_details"  id="address_messages"></span>
	    </td>
	    <td  style="text-align:right">
	      <span class="small_button" id="save_address_button" style="display:none" address_key="" >Save Address</span>
	      <span class="small_button" id="cancel_edit_address"  style="display:none" onClick="cancel_edit_address()" address_key=""  >Cancel Edit Address</span>
	      <span style="display:none" class="small_button" id="move_address_button" >Move to New Address</span>
	      <span class="small_button" address_id="0"   id="add_address_button" >Add Address</span>
	      <span style="display:none" class="small_button" address_id="0"   id="cancel_delete_address_button" >Cancel Delete</span>
	      <span style="display:none" class="small_button" address_id="0"   id="confirm_delete_address_button" >Confirm Delete</span>


	    </td>
	  </tr>
	  <tr id="tr_address_showcase">
	    <td colspan=2 style="xborder:1px solid black"  id="address_showcase">
	      <div  style="display:none" class="address_container"  id="address_container0">
		<div class="address_display" id="address_display0"></div>
		<div  class="address_buttons" id="address_buttons0" >
		  <span class="small_button small_button_edit" style="float:left" id="contacts_address_button0" address_id="0" onclick="contacts_address(event,this)" ><img src="art/icons/person.png" alt="{t}Contacts{/t}"/></span>
		  
		  <input type="checkbox" class='Is_Main' /> {t}Main{/t}
		  <span class="small_button small_button_edit" id="delete_address_button0" address_id="0" onclick="delete_address(event,this)" >{t}Remove{/t}</span>
		  <span class="small_button small_button_edit" id="edit_address_button0" address_id="0" onclick="edit_address(event,this)" >{t}Edit{/t}</span>
		</div>
	      </div>
	      
	      {foreach from=$addresses item=address key=key }
	      <div class="address_container"  id="address_container{$address->id}">
		<div class="address_display"  id="address_display{$address->id}">{$address->display('xhtml')}</div>
		<div class="address_buttons" id="address_buttons{$address->id}">
		  
		  <span  style="float:left" id="contacts_address_button{$address->id}" address_id="{$address->id}" onclick="contacts_address(event,this)" >
		  <img src="art/icons/user.png" alt="{t}Contacts{/t}"/></span>
		  <span  style="float:left;margin-left:5px;cursor:pointer" id="contacts_address_button{$address->id}" address_id="{$address->id}" onclick="contacts_address(event,this)" >
		  <img src="art/icons/telephone.png" alt="{t}Telephones{/t}"/></span>

		  <input type="checkbox" class='Is_Main' {if $address->is_main()}checked="checked" value="Yes" ovalue="Yes"{else}value="No" ovalue="No"{/if} /> {t}Main{/t}
		  <span class="small_button small_button_edit" id="delete_address_button{$address->id}" address_id="{$address->id}" onclick="delete_address(event,this)" >{t}Remove{/t}</span>
		  <span class="small_button small_button_edit" id="edit_address_button{$address->id}" address_id="{$address->id}" onclick="edit_address(event,this)" >{t}Edit{/t}</span>
		</div>
	      </div>
	      {/foreach}
	    </td>
	  </tr>
</tr>

   {foreach from=$addresses item=address key=key }
<tr>
  
</tr>

 {/foreach}

<tbody id="address_form" style="display:none"   >
	    
	   

	    <tr id="tr_address_type">
	      <td class="label">
		<span id="show_description" onclick="show_description()" class="small_button" style="padding:0 1px;font-size:50%;position:relative;top:-2px;">+</span> {t}Address Type{/t}:</td><td  style="text-align:left"   id="address_type" value="" ovalue=""  >
		<span id="address_type_Office" label="Office" onclick="toggle_address_type(this)" class="small_button address_type" style="margin:0">Office</span>
		<span id="address_type_Shop" label="Shop" onclick="toggle_address_type(this)" class="small_button  address_type" style="margin-left:3px">Shop</span>
		<span id="address_type_Warehouse" label="Warehouse" onclick="toggle_address_type(this)" class="small_button  address_type" style="margin-left:3px">Warehouse</span>
		<span id="address_type_Other" label="Other" onclick="toggle_address_type(this)" class="small_button  address_type" style="margin-left:3px">Other</span>
	      </td>
	    </tr>

	    <tr id="tr_address_function" style="{if $scope=='Company'}display:none;{/if}">
	      <td class="label">{t}Address Function{/t}:</td><td  style="text-align:left"   id="address_function" value="" ovalue=""  >
		<span id="address_function_Contact" label="Contact" onclick="toggle_address_function(this)" class="small_button address_function" style="margin:0">Contact</span>
		<span id="address_function_Billing" label="Billing" onclick="toggle_address_function(this)" class="small_button address_function" style="margin-left:3px">Billing</span>
		<span id="address_function_Shipping" label="Shipping" onclick="toggle_address_function(this)" class="small_button address_function" style="margin-left:3px">Shipping</span>
		<span id="address_function_Other" label="Other" onclick="toggle_address_function(this)" class="small_button address_function" style="margin-left:3px">Other</span>
	      </td>
	    </tr>

	    

	    <tr id="tr_address_description" style="display:none"> 
	      <td class="label"><span id="hide_description" onclick="hide_description()" class="small_button" style="padding:0 1px;font-size:50%;position:relative;top:-2px;">x</span> {t}Description{/t}:</td><td  style="text-align:left"    ><input style="text-align:left" id="address_description" value="" ovalue=""   ></td>
	    </tr>
	    
	    <tr >
	      <td ></td><td style="text-align:left"><div  style="text-align:left;float:left;height:10px;width:210px;border-top:1px solid #ddd"></div></td>
	    </tr>

	  <input type="hidden" id="address_key" value="" ovalue="" >
	  <input type="hidden" id="address_fuzzy" value="Yes" ovalue="Yes" >


	    <tr class="first">
	    
	    <td class="label" style="width:160px">
	      <span id="show_country_d1" onclick="toggle_country_d1()" class="small_button" style="padding:0 1px;font-size:50%;position:relative;top:-2px;display:none">+</span>
	      Country:</td>
	    <td  style="text-align:left">
	      <div id="myAutoComplete" style="width:15em;position:relative;top:-10px" >
		<input id="address_country" style="text-align:left;width:18em" type="text">
		<div id="address_country_container" style="position:relative;top:18px" ></div>
		
	      </div>
	    </td>
	  </tr>
	    <input id="address_country_code" value="" type="hidden">
	     <input id="address_country_2acode" value="" type="hidden">

	    
	  <tr id="tr_address_country_d1">
	    <td class="label" style="width:160px">
	    <span id="show_country_d2" onclick="toggle_country_d2()" class="small_button" style="padding:0 1px;font-size:50%;position:relative;top:-2px;display:none">+</span> 
	    <span id="label_address_country_d1">{t}Region{/t}</span>:</td><td  style="text-align:left"><input style="text-align:left;width:18em" id="address_country_d1" value="" ovalue="" ></td>
	  </tr>
	  <tr id="tr_address_country_d2">
	    <td class="label" style="width:160px"><span id="label_address_country_d2">{t}Subregion{/t}</span>:</td><td  style="text-align:left">
	    <input style="text-align:left;width:18em" id="address_country_d2" value="" ovalue="" ></td>
	  </tr>
	  
	  <tr id="tr_address_postal_code">
	    <td class="label" style="width:160px">{t}Postal Code{/t}:</td><td  style="text-align:left"><input style="text-align:left;width:18em" id="address_postal_code" value="" ovalue=""  ></td>
	  </tr>

	  <tr>
	    <td class="label" style="width:160px">
	      <span id="show_town_d1" onclick="toggle_town_d1()" class="small_button" style="padding:0 1px;font-size:50%;position:relative;top:-2px">+</span> {t}City{/t}:</td>
	      <td  style="text-align:left"><input style="text-align:left;width:18em" id="address_town" value="" ovalue="" ></td>
	  </tr>
	  <tr style="display:none" id="tr_address_town_d1">
	    <td class="label" style="width:160px" >
	      <span id="show_town_d2" onclick="toggle_town_d2()" class="small_button" style="padding:0 1px;font-size:50%;position:relative;top:-2px">x</span> {t}City 1st Div{/t}:</td>
	      <td  style="text-align:left"><input style="text-align:left;width:18em" id="address_town_d1" value="" ovalue="" ></td>
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

      </div>

      <div  style="margin:0"  class="edit_block" id="d_contacts">
	<table class="edit" border=0>
	  <tr class="title"><td>{t}Contacts{/t}: 
	      <span class="state_details" id="personal_num_changes"></span>
	      <span class="state_details" id="email_num_changes"></span>
	      <span class="state_details" id="mobile_num_changes"></span>
	      <span class="state_details" id="telephone_num_changes"></span>
	      <span class="state_details" id="fax_num_changes"></span>
	      <span class="state_details" id="address_num_changes"></span>
	      <span class="state_details" id="contact_messages"></span>
	      
	    </td>
	    <td  style="text-align:right">
	      <span style="display:none" class="small_button" id="save_contact_button" onclick="save_contact()">Save Contact</span>
	      <span style="display:none" contact_key="" class="small_button" id="cancel_edit_contact_button" onclick="cancel_edit_contact()">Cancel Edit Contact</span>
	    
	     
	      <span class="small_button" id="add_contact_button" >Add Contact</span></td>
	  </tr>
	  

	  <tr id="tr_contact_case">
	    <td colspan=2 style="xborder:1px solid black"  id="contact_showcase">
	     
	      <div  style="display:none" class="contact_container"  id="contact_container0">
			
			<div class="contact_display" id="contact_display0"></div>
			<div  class="contact_buttons" id="contact_buttons0" >
			<input type="checkbox" id="is_main_contact0" value="No" ovalue="No" /> {t}Main{/t}
		  <span class="small_button small_button_edit" id="delete_contact_button0" contact_id="0" onclick="delete_contact(event,this)" >{t}Delete{/t}</span>
		  <span class="small_button small_button_edit" id="edit_contact_button0" contact_id="0" onclick="edit_contact(event,this)" >{t}Edit{/t}</span>
		</div>
	      </div>
	      
	      {foreach from=$contacts item=contact key=key }
	      <div class="contact_container"  id="contact_container{$contact->id}">
	      
		<div class="contact_display"  id="contact_display{$contact->id}">{$contact->display('card')}</div>
		<div class="contact_buttons" id="contact_buttons{$contact->id}">
			<input type="checkbox" id="is_main_contact{$contact->id}" {if $contact->is_main() }value="Yes" ovalue="Yes" checked="checked" {else}value="No" ovalue="No"{/if} /> {t}Main{/t}
		 	<span class="small_button small_button_edit" id="delete_contact_button{$contact->id}" contact_id="{$contact->id}" onclick="delete_contact(event,this)" >{t}Delete{/t}</span>
		  	<span class="small_button small_button_edit" id="edit_contact_button{$contact->id}" contact_id="{$contact->id}" onclick="edit_contact(event,this)" >{t}Edit{/t}</span>
		</div>
	      </div>
	      {/foreach}
	    </td>
	  </tr>

	  <tr id="contact_form" style="display:none">
	    <td colspan=2 >
	      <table border=1 class="edit" style="margin-top:50px" id="edit_contact_table">
		<tr >
		  <input type="hidden" id="Contact_Key" value="" ovalue=""  />
		  <td style="xwidth:160px;" class="label">Name:</td>
		  <td><input style="width:16em" id="Contact_Name" value="" onkeyup="contact_name_changed(this)"></td>
		  <td >
		    <table border=0 class="edit" style="xdisplay:none;position:relative;top:-6px;" >

		      	<tr id="tr_Contact_Gender" style="display:none">
			  <td class="label">{t}Gender{/t}:</td>
			  <td 
			    <input type="hidden" id="Contact_Gender">
			    <span id="Contact_Gender_Male" label="Male" onclick="toggle_Contact_Gender(this)" class="small_button Contact_Gender" style="margin:0">{t}Male{/t}</span>
			    <span id="Contact_Gender_Female" label="Famale" onclick="toggle_Contact_Gender(this)" class="small_button Contact_Gender" style="margin-left:3px">{t}Female{/t}</span>
			    <span id="Contact_Gender_Unknown" label="Unknown" onclick="toggle_Contact_Gender(this)" class="small_button Contact_Gender" style="margin-left:3px">{t}Unknown{/t}</span>
			    
			  </td>
			</tr>

	  
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


		    </table>
		  </td>
		</tr>


	
		
		<tr style="display:none">
		  <td class="label">{t}Profession{/t}:</td><td><input id="Contact_Profession"></td>
		</tr>
		<tr  style="display:none">
		  <td class="label">{t}Title{/t}:</td><td><input id="Contact_Title"></td>
		</tr>
		
		<tr class="title">
		  <td colspan=3 >{t}Contact Email & Mobiles{/t}:
		    <span style="display:none" class="small_button" id="cancel_edit_contact_button"  onclick="cancel_save_email()" >Cancel Edit Email</span>
		    <span style="display:none" class="small_button" id="cancel_edit_contact_button"  onclick="cancel_save_email()">Cancel Edit Mobile</span>
		    <span style="display:none" class="small_button" id="save_add_email_button" onclick="save_email()" >Save Email</span>
		    <span style="display:none" class="small_button" id="save_add_mobile_button" onclick="save_telecom('mobile')" >Save Mobile</span>
		    <span class="small_button" id="add_email_button"  onclick="add_email()" >Add Email</span>
		    <span class="small_button" id="add_mobile_button" container_key='' telecom_type='mobile' onclick="add_telecom(this)" >Add Mobile</span>

		  </td>
		</tr>
		
		
		<tr id="email_mould" class="mould" style="display:none;">
		  <td  class="label"  >{t}Email{/t}:</td>
		  <td >
		    <span class="email_to_delete" style="display:none;text-decoration:line-through"></span>
		    <input style="width:90%" class="Email"  to_delete=0 value="" ovalue="" email_key="" valid=""   onkeyup="validate_email(this);email_change()" /><br/>

		  </td>
		  <td>
		    <input class="Email_Is_Main" type="checkbox" ovalue="No"  onclick="update_is_main_email(this)"/><span>{t}Main{/t}</span>

		    <span class="small_button undelete_email"  style="display:none" email_key="" onclick="unmark_email_to_delete(this)">{t}Cancel Delete{/t}</span>
		    <span class="small_button delete_email"  email_key="" onclick="mark_email_to_delete(this)">{t}Delete{/t}</span>
		    <span class="small_button show_details_email" style="display:none" email_key="" action="Show" onclick="show_details_email(this)">{t}Edit Details{/t}</span>

		    </br><table border=0 class="edit" style="margin-top:10px;display:none" >
		      <tr style="display:none">
			<td class="label">{t}Email Type{/t}:</td>
			<td >
			  <input type="hidden" class="Email_Description">
			  <span  class="Email_Description small_button"  label="Work" style="margin:0" >{t}Work{/t}</span>
			  <span  class="Email_Description small_button"  label="Personal"  >{t}Personal{/t}</span>
			  
			</td>
		      </tr>
		      <tr>
			<td class="label">{t}Email Contact Name{/t}:</td>
			<td >
			  <input style="width:20em" class="Email_Contact_Name" onkeyup="email_change()" />
			</td>
		      </tr>
		    </table>
		  </td>
		</tr>
		<tr id="mobile_mould" class="mould" style="display:none;xbackground:red">
	      <td  class="label">
		<span id="show_description" onclick="show_details_telecom(this)" 
		class="show_details_telecom" telecom_type="mobile" action="Show" style="padding:0 1px;cursor:pointer">
			<img src='art/icons/application_put.png' alt="D"/>
		</span>
		 {t}Mobile{/t}:
	      </td>
	      <td>
		<span class="telecom_to_delete" style="display:none;text-decoration:line-through"></span>
		<input  class="Telecom" telecom_type="Mobile" telecom_type_description=""  container_key="" value="" ovalue="" to_delete=0  telecom_key=0 onkeyup="validate_telecom(this);telecom_change()"     /> 
	      </td >
	      <td>
		<input class="Telecom_Is_Main" type="checkbox" ovalue="No"  telecom_type="mobile" onclick="update_is_main_telecom(this)"/><span>{t}Main{/t}</span>
		
		<span class="small_button undelete_telecom"  style="display:none" mobile_key="" onclick="unmark_telecom_to_delete(this)">{t}Cancel Delete{/t}</span>
		<span class="small_button delete_telecom"   mobile_key="" onclick="mark_telcom_to_delete(this)">{t}Delete{/t}</span>
		
	      </td>
	    </tr>
	    <tr id="mobile_Country_Code_mould" class="Telecom_Details"  style="display:none" >
	      <td class="label">{t}Country Code{/t}:</td><td ><input class="Country_Code" style="width:3em" value="" ovalue="" onkeyup="telecom_component_change(this)"  /></td></tr>
	    <tr id="mobile_National_Access_Code_mould"   class="Telecom_Details" style="display:none">
	      <td  class="label"><img class="help" src="art/icons/help.png" alt="?"/> {t}NAC{/t}:</td><td ><input class="National_Access_Code" style="text-align:center;width:1em" value="" ovalue=""  onkeyup="telecom_component_change(this)" /></td>
	    </tr>
	    <tr id="mobile_Number_mould" class="Telecom_Details" style="display:none">
	      <td  class="label">{t}Number{/t}:</td><td ><input  class="Number" style="width:7em" value="" ovalue=""  onkeyup="telecom_component_change(this)"   />
	      </td>
	    </tr>
	    <tr class="title" >
	    <td colspan=3 >{t}Contact Address & Landlines{/t}:
	      <span   class="small_button" id="add_address_to_contact_button" >Add Address</span>
	    </td>
	  </tr>
	  <tr id="address_mould" class="mould" style="display:none">
	    <td class="Address" style="font-size:70%"></td>
	    <td colspan=2>
		<input type="checkbox"  class="Is_Main"/> {t}Main{/t}
	      <span style="display:none" tel_key="" class="small_button" id="cancel_edit_tel_button" >Cancel Edit Telephone</span>
	      <span style="display:none" fax_key="" class="small_button" id="cancel_edit_fax_button" >Cancel Edit Fax</span>
	      
	       <span style="display:none" class="small_button" id="cancel_add_tel_button" >Cancel Adding New Telephone</span>
	      <span style="display:none" class="small_button" id="cancel_add_fax_button" >Cancel Adding New Fax</span>
	      
	      <span style="display:none" class="small_button" id="save_add_tel_button" >Save New Telephone</span>
	      <span style="display:none" class="small_button" id="save_add_fax_button" >Save New Fax</span>
	      <span class="small_button" id="add_move_button" >Move from Address</span>
	      <span class="small_button Add_Telecom" container_key='' telecom_type='telephone' onclick="add_telecom(this)">Add Telephone</span>
	      <span class="small_button Add_Telecom" container_key='' telecom_type='fax' onclick="add_telecom(this)">Add Fax</span>
	      <table style="margin-top:10px;width:100%" border=1>
	   	   	<tr id="telephone_mould" class="tr_telecom mould" style="display:none;">
		  <td  class="label">
		    <span id="show_description" onclick="show_details_telecom(this)" 
		    class="show_details_telecom" 
		    telecom_type="telephone" action="Show" 
		    style="padding:0 1px;cursor:pointer">
		    	<img src='art/icons/application_put.png' alt="D"/>
		    </span>
		     {t}Telephone{/t}:
		  </td>
		  <td>
		    <span class="telecom_to_delete" style="display:none;text-decoration:line-through"></span>
		    <input  class="Telecom" telecom_key=0 telecom_type="Telephone" telecom_type_description="" container_key="" value="" ovalue="" to_delete=0  onkeyup="validate_telecom(this);telecom_change(this)"     /> 
		  </td >
		  <td>
		    <input class="Telecom_Is_Main" type="checkbox" ovalue="No"  telecom_type="telephone" onclick="update_is_main_telecom(this)"/><span>{t}Main{/t}</span>
		    <span class="small_button undelete_telecom"  style="display:none" email_key="" onclick="unmark_telecom_to_delete(this)">{t}Cancel Delete{/t}</span>
		    <span class="small_button delete_telecom"   telephone_key="" onclick="mark_telcom_to_delete(this)">{t}Delete{/t}</span>
		    
		  </td>
		</tr>	
			<tr class="Telecom_Details" style="display:none" >
		  <td class="label">{t}Country Code{/t}:</td><td ><input class="Country_Code" style="width:3em" value="" ovalue="" onkeyup="telecom_component_change(this)"  /></td></tr>
			<tr class="Telecom_Details" style="display:none">
		  <td  class="label"><img class="help" src="art/icons/help.png" alt="?"/> {t}NAC{/t}:</td><td ><input class="National_Access_Code" style="text-align:center;width:1em" value="" ovalue=""  onkeyup="telecom_component_change(this)" /></td>
		</tr>
			<tr class="Telecom_Details" style="display:none">
		  <td  class="label">{t}Area Code{/t}:</td><td ><input  class="Area_Code" style="width:4em" value="" ovalue=""  onkeyup="telecom_component_change(this)"   />
		  </td>
		</tr>
			<tr class="Telecom_Details" style="display:none">
		  <td  class="label">{t}Number{/t}:</td><td ><input  class="Number" style="width:7em" value="" ovalue=""  onkeyup="telecom_component_change(this)"   />
		  </td>
		</tr>
			<tr class="Telecom_Details" style="display:none">
		  <td  class="label">{t}Extension{/t}:</td><td ><input  class="Extension" style="width:5em" value="" ovalue=""  onkeyup="telecom_component_change(this)"   />
		  </td>
		</tr>
			<tr id="fax_mould" class="tr_telecom mould" style="display:none;">
		  <td  class="label">
		    <span id="show_description" 
		    onclick="show_details_telecom(this)"
		    class="show_details_telecom" 
		    telecom_type="fax" 
		    action="Show" 
		    style="padding:0 1px;cursor:pointer">
		    <img src='art/icons/application_put.png' alt="D"/>
		    </span>
		    {t}Fax{/t}:
		  </td>
		  <td>
		    <span class="telecom_to_delete" style="display:none;text-decoration:line-through"></span>
		    <input  class="Telecom" value=""  telecom_type="Fax" container_key="" telecom_type_description=""  telecom_key=0 ovalue="" to_delete=0  onkeyup="validate_telecom(this);telecom_change()"     /> 
		  </td >
		  <td>
		    <input class="Telecom_Is_Main" type="checkbox" ovalue="No"  telecom_type="fax" onclick="update_is_main_telecom(this)"/><span>{t}Main{/t}</span>
		    <span class="small_button undelete_telecom"  style="display:none" email_key="" onclick="unmark_telecom_to_delete(this)">{t}Cancel Delete{/t}</span>
		    <span class="small_button delete_telecom"   fax_key="" onclick="mark_telcom_to_delete(this)">{t}Delete{/t}</span>
		    
		  </td>
		</tr>	
			<tr class="Telecom_Details"  style="display:none" >
		  <td class="label">{t}Country Code{/t}:</td><td ><input class="Country_Code" style="width:3em" value="" ovalue="" onkeyup="telecom_component_change(this)"  /></td></tr>
			<tr class="Telecom_Details" style="display:none">
		  <td  class="label"><img class="help" src="art/icons/help.png" alt="?"/> {t}NAC{/t}:</td><td ><input class="National_Access_Code" style="text-align:center;width:1em" value="" ovalue=""  onkeyup="telecom_component_change(this)" /></td>
		</tr>
			<tr class="Telecom_Details" style="display:none">
		  <td  class="label">{t}Area Code{/t}:</td><td ><input  class="Area_Code" style="width:4em" value="" ovalue=""  onkeyup="telecom_component_change(this)"   />
		  </td>
		</tr>
			<tr class="Telecom_Details" style="display:none">
		  <td  class="label">{t}Number{/t}:</td><td ><input  class="Number" style="width:7em" value="" ovalue=""  onkeyup="telecom_component_change(this)"   />
		  </td>
		</tr>
			<tr id="after_fax" class="tr_telecom"></tr>
		</table>
	    </td>
	  </tr>
	  
	  

	  <tr id="last_tr" style="display:none"></tr>
	  
	  </table>
	  
</td>
	  </tr>



	  
	

 

	</table>
	<div style="clear:both;height:40px"></div>
	</div>
      </div>
      

    </div>
</div>
</div>
{include file='footer.tpl'}

