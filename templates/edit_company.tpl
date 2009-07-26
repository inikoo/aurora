{include file='header.tpl'}


<div id="bd" style="padding:0 20px">
<span class="nav2 onleft"><a  href="customers.php">{t}Customers{/t}</a></span>
<span class="nav2 onleft"><a href="companies.php">{t}Companies{/t}</a></span>
<span class="nav2 onleft"><a   href="contacts.php">{t}Personal Contacts{/t}</a></span>
<span class="nav2 onright"><a href="search_customers.php">{t}Advanced Search{/t}</a></span>


<span class="nav2"><a href="contacts.php">{$home}</a></span>


  <div id="yui-main" >
    
    <div class="search_box" >
      
      <span id="but_show_details" state="{$details}" atitle="{if $details==0}{t}Hide Details{/t}{else}{t}Show Details{/t}{/if}" class="state_details"   >{if $details==1}{t}Hide Details{/t}{else}{t}Show Details{/t}{/if}</span>
      <br/><a  href="contact.php?edit=0"  id="but_edit" title="{t}Edit Contact Data{/t}" class="state_details"   >{t}Exit Edit{/t}</a>
    </div>
    
    <div >
      <h1>{t}Editing company{/t} {$company->get(ID)}</h1>

      <div class="chooser2" >
	<ul >
	  <li id="details" {if $edit=='details'}class="selected"{/if} ><img src="art/icons/information.png"> {t}Details{/t}</li>
	  <li id="address" {if $edit=='address'}class="selected"{/if} > <img src="art/icons/building.png"> {t}Address{/t}</li>
	  <li id="contacts" {if $edit=='contacts'}class="selected"{/if} ><img src="art/icons/user.png"> {t}Contacts{/t}</li>


	</ul>
      </div>


      <div style="clear:both;height:3em;padding:10px 20px;;margin:20px 0;border-top: 1px solid #cbb;;border-bottom: 1px solid #caa;width:770px;" id="contacts_messages">
	<div xstyle="float:left">
	  <span class="save" style="display:none" id="description_save" onclick="save('description')">Save</span><span id="description_reset"  style="display:none"   class="reset" onclick="reset('description')">Reset</span>
	</div>
	<span class="details">Number of changes:<span id="contacts_num_changes">0</span></span>
	
	<div id="description_errors">
	</div>
	<div id="description_warnings">
	</div>
      </div>
      
  <div  style="{if $edit!="details"}display:none;{/if}margin:0"  class="edit_block" id="d_details">
	<table class="edit" border=0>
	  
	  <tr class="title">
	    <td colspan="2" style="width:160px">Details: <span class="state_details" id="details_messages"></span></td>
	    <td  style="text-align:right">
	      <span style="display:none" class="small_button" id="cancel_save_details_button" >Cancel</span>
	      <span  style="display:none" class="small_button" id="save_details_button" >Save</span></td>
	  </tr>
	  <tr class="first"><td style="width:160px">Public Name:</td><td  style="text-align:left"><input style="text-align:left;width:12em" id="name" value="{$company->get('Company Name')}" ovalue="{$company->get('Company Name')}"></td>
	  </tr>
	  <tr class="first"><td style="width:160px">Fiscal Name:</td><td style="text-align:left"><input style="text-align:left;width:12em" id="fiscal_name" value="{$company->get('Company Fiscal Name')}" ovalue="{$company->get('Company Fiscal Name')}" ></td>
	  </tr>
	  <tr class="first"><td style="width:160px">Tax Number:</td><td  style="text-align:left"><input style="text-align:left;width:12em" id="tax_number" value="{$company->get('Company Tax Number')}" ovalue="{$company->get('Company Tax Number')}" ></td>
	  </tr>
	  <tr class="first"><td style="width:160px">Registration Number:</td><td style="text-align:left"><input style="text-align:left;width:12em" id="registration_number" value="{$company->get('Company Registration Number')}" ovalue="{$company->get('Company Registration Number')}" ></td>
	  </tr>
	</table>
      </div>

  <div  style="{if $edit!="address"}xdisplay:none;{/if}margin:0"  class="edit_block" id="d_address">

	<table class="edit" border=0 >
	  
	  <tr class="title">
	    <td style="width:160px">Address: <span class="state_details"  id="address_messages"></span>
	    </td>
	    <td  style="text-align:right">
	      <span class="small_button" id="save_address_button" style="display:none" address_key="" >Save Address</span>
	      <span class="small_button" id="cancel_edit_address"  style="display:none" onClick="cancel_edit_address()" address_key=""  >Cancel Edit Address</span>
	      <span class="small_button" id="move_address_button" >Move to New Address</span><span class="small_button" address_id="0"   id="add_address_button" >Add Address</span>
	    </td>
	  </tr>
	  <tr id="address_showcase">
	    <td colspan=2 style="border:1px solid black">
	      <div  style="" class="address_container"  id="address_container0">
		<div class="address_display" id="address_display0"></div>
		<div  class="address_buttons">
		  <span class="small_button small_button_edit" id="edit_address_button0" address_id="0" onclick="edit_address(event,this)" >{t}Edit Address{/t}</span>
		</div>
	      </div>

	      {foreach from=$addresses item=address key=key }
	      <div class="address_container"  id="address_container{$address->id}">
		<div class="address_display"  id="address_display{$address->id}">{$address->display('xhtml')}</div>
		<div class="address_buttons">
		  <span class="small_button small_button_edit" id="edit_address_button{$address->id}" address_id="{$address->id}" onclick="edit_address(event,this)" >{t}Edit Address{/t}</span>
		</div>
	      </div>
	      {/foreach}
	      </td>
	    </tr>
	  </tr>
	 
	  <tbody id="address_form" style="display:none"   >
	    
	   

	    <tr id="tr_address_type">
	      <td><span id="show_description" onclick="show_description()" class="small_button" style="padding:0 1px;font-size:50%;position:relative;top:-2px;">+</span> {t}Address Type{/t}:</td><td  style="text-align:left"   id="address_type" value="" ovalue=""  >
		<span id="address_type_Office" label="Office" onclick="toggle_address_type(this)" class="small_button address_type" style="margin:0">Office</span>
		<span id="address_type_Shop" label="Shop" onclick="toggle_address_type(this)" class="small_button  address_type" style="margin-left:3px">Shop</span>
		<span id="address_type_Warehouse" label="Warehouse" onclick="toggle_address_type(this)" class="small_button  address_type" style="margin-left:3px">Warehouse</span>
		<span id="address_type_Other" label="Other" onclick="toggle_address_type(this)" class="small_button  address_type" style="margin-left:3px">Other</span>
	      </td>
	    </tr>

	    <tr id="tr_address_function" style="{if $scope=='Company'}display:none;{/if}">
	      <td>{t}Address Function{/t}:</td><td  style="text-align:left"   id="address_function" value="" ovalue=""  >
		<span id="address_function_Contact" label="Contact" onclick="toggle_address_function(this)" class="small_button address_function" style="margin:0">Contact</span>
		<span id="address_function_Billing" label="Billing" onclick="toggle_address_function(this)" class="small_button address_function" style="margin-left:3px">Billing</span>
		<span id="address_function_Shipping" label="Shipping" onclick="toggle_address_function(this)" class="small_button address_function" style="margin-left:3px">Shipping</span>
		<span id="address_function_Other" label="Other" onclick="toggle_address_function(this)" class="small_button address_function" style="margin-left:3px">Other</span>
	      </td>
	    </tr>

	    

	    <tr id="tr_address_description" style="display:none"> 
	      <td><span id="hide_description" onclick="hide_description()" class="small_button" style="padding:0 1px;font-size:50%;position:relative;top:-2px;">x</span> {t}Description{/t}:</td><td  style="text-align:left"    ><input style="text-align:left" id="address_description" value="" ovalue=""   ></td>
	    </tr>
	    
	    <tr >
	      <td ></td><td style="text-align:left"><div  style="text-align:left;float:left;height:10px;width:210px;border-top:1px solid #ddd"></div></td>
	    </tr>

	  <input type="hidden" id="address_key" value="" ovalue="" >
	  <input type="hidden" id="address_fuzzy" value="Yes" ovalue="Yes" >


	    <tr class="first">
	    
	    <td style="width:160px">
	      <span id="show_country_d1" onclick="toggle_country_d1()" class="small_button" style="padding:0 1px;font-size:50%;position:relative;top:-2px;display:none">+</span>
	      Country:</td>
	    <td  style="text-align:left">
	      <div id="myAutoComplete" style="width:15em;position:relative;top:-10px" >
		<input id="address_country" style="text-align:left;width:12em" type="text">
		<div id="address_country_container" style="position:relative;top:18px" ></div>
		
	      </div>
	    </td>
	  </tr>
	    <input id="address_country_code" value="" type="hidden">
	  <tr id="tr_address_country_d1">
	    <td style="width:160px"><span id="show_country_d2" onclick="toggle_country_d2()" class="small_button" style="padding:0 1px;font-size:50%;position:relative;top:-2px;display:none">+</span> <span id="label_address_country_d1">{t}Region{/t}</span>:</td><td  style="text-align:left"><input style="text-align:left;width:12em" id="address_country_d1" value="" ovalue="" ></td>
	  </tr>
	  <tr id="tr_address_country_d2">
	    <td style="width:160px"><span id="label_address_country_d2">{t}Subregion{/t}</span>:</td><td  style="text-align:left"><input style="text-align:left;width:12em" id="address_country_d2" value="" ovalue="" ></td>
	  </tr>
	  
	  <tr id="tr_address_postal_code">
	    <td style="width:160px">{t}Postal Code{/t}:</td><td  style="text-align:left"><input style="text-align:left;width:12em" id="address_postal_code" value="" ovalue=""  ></td>
	  </tr>

	  <tr>
	    <td style="width:160px">
	      <span id="show_town_d1" onclick="toggle_town_d1()" class="small_button" style="padding:0 1px;font-size:50%;position:relative;top:-2px">+</span> {t}City{/t}:</td><td  style="text-align:left"><input style="text-align:left;width:12em" id="address_town" value="" ovalue="" ></td>
	  </tr>
	  <tr style="display:none" id="tr_address_town_d1">
	    <td style="width:160px" >
	      <span id="show_town_d2" onclick="toggle_town_d2()" class="small_button" style="padding:0 1px;font-size:50%;position:relative;top:-2px">x</span> {t}City 1st Div{/t}:</td><td  style="text-align:left"><input style="text-align:left;width:12em" id="address_town_d1" value="" ovalue="" ></td>
	  </tr>
	  <tr style="display:none;" id="tr_address_town_d2">
	    <td style="width:160px">{t}City 2nd Div{/t}:</td><td  style="text-align:left"><input style="text-align:left;width:12em" id="address_town_d2" value="" ovalue="" ></td>
	  </tr>
	  <tr>
	    <td style="width:160px">{t}Street/Number{/t}:</td><td  style="text-align:left"><input style="text-align:left;width:12em" id="address_street" value="" ovalue="" ></td>
	  <tr>
	    <td style="width:160px">{t}Building{/t}:</td><td  style="text-align:left"><input style="text-align:left;width:12em" id="address_building" value="" ovalue="" ></td>
	  </tr>
	  <tr >
	    <td style="width:160px">{t}Internal{/t}:</td><td  style="text-align:left"><input style="text-align:left;width:12em" id="address_internal" value="" ovalue="" ></td>
	  </tr>

 

	</table>

      </div>

      <div  style="{if $edit!="contacts"}xdisplay:none;{/if}margin:0"  class="edit_block" id="d_contacts">
	<table class="edit" border=1>
	  <tr class="title"><td>Contacts:</td><td  style="text-align:right"><span class="small_button" id="cancel_add_contact_button" >Cancel Adding New Contact</span><span class="small_button" id="save_add_contact_button" >Save New Contact</span><span class="small_button" id="add_contact_button" >Add Contact</span></td></tr>
	  
	  <tr id="add_contact_block" style="background:#f0fbff">
	    <td colspan=2>
	      <table border=2 class="edit">
		<tr ><td style="width:120px;vertical-align: top;">Name:</td><td style="text-align:left;vertical-align: top;"><input style="text-align:left;width:12em" id="full_name" value=""></td>
		  <td  style="text-align:left;vertical-align: top;">
		    <table border=1 class="edit" style="position:relative;top:-6px;" >	  
		      <tr>
			<td class="label" >{t}Salutation{/t}:</td>
			<td  style="text-align:left" >
			  <table id="period_options" style="float:none;position:relative;left:-4px;" border=0  class="options_mini" >
			    <tr>
			      
			      {foreach from=$prefix item=s  }
			      
			      <td   onclick="update_salutation(this)"  style="background:#fff{if $s.relevance>1};display:none{/if} "    id="salutation{$s.id}" >{$s.txt}</td>
			      {/foreach}
			      
			    </tr>
			  </table>
			</td>
		      </tr>
	   
		      <tr>
			<td class="label">{t}First Name(s){/t}:</td>
			<td  style="text-align:left" ><input  onkeyup="update_full_address()"  onblur="" style="text-align:left;width:12em"  name="first_name" id="v_first_name" value=""  ovalue="" ></td>
		      </tr>
		      <tr>
			<td class="label">{t}Surname(s){/t}:</td>
			<td  style="text-align:left" ><input  onkeyup="update_full_address()"  onblur="" style="text-align:left;width:12em"  name="surname" id="v_surname" value=""  ovalue="" ></td>
	  </tr>
		      
		    </table>
		  </td>
		</tr>
		<tr>
		  <td style="vertical-align: top;">{t}Email{/t}:</td><td style="text-align:left"><input style="text-align:left" value="" ovalue="" /> </td>
		</tr>
		<tr>
		  <td style="vertical-align: top;">{t}Telephone{/t}:</td><td style="vertical-align: top;text-align:left"><input style="text-align:left" value="" ovalue="" /> </td>
		  <td style="text-align:left"  >
		    <table border=1 class="edit">
		      <tr valign="top"><td valign="top">{t}Country Code{/t}:</td><td style="text-align:left"><input style="text-align:left;width:3em" value="" ovalue="" /></td></tr>
		      <tr><td>{t}National Access Code{/t}:</td><td style="text-align:left"><input style="text-align:center;width:1em" value="" ovalue="" /></td></tr>
		      <tr><td>{t}Area Code{/t}:</td><td style="text-align:left"><input style="text-align:left;width:4em" value="" ovalue="" /></td></tr>
		      <tr><td>{t}Number{/t}:</td><td style="text-align:left"><input style="text-align:left;width:7em" value="" ovalue="" /></td></tr>
		      <tr><td>{t}Extension{/t}:</td><td style="text-align:left"><input style="text-align:left;width:3em" value="" ovalue="" /></td></tr>
		      </table>
		  </td>
		</tr>
		<tr>
		  <td style="vertical-align: top;">{t}Fax{/t}:</td><td style="vertical-align: top;text-align:left"><input style="text-align:left" value="" ovalue="" /> </td>
		   <td style="text-align:left"  >
		    <table border=1 class="edit">
		      <tr valign="top"><td valign="top">{t}Country Code{/t}:</td><td style="text-align:left"><input style="text-align:left;width:3em" value="" ovalue="" /></td></tr>
		      <tr><td>{t}National Access Code{/t}:</td><td style="text-align:left"><input style="text-align:center;width:1em" value="" ovalue="" /></td></tr>
		      <tr><td>{t}Area Code{/t}:</td><td style="text-align:left"><input style="text-align:left;width:4em" value="" ovalue="" /></td></tr>
		      <tr><td>{t}Number{/t}:</td><td style="text-align:left"><input style="text-align:left;width:7em" value="" ovalue="" /></td></tr>
		      <tr><td>{t}Extension{/t}:</td><td style="text-align:left"><input style="text-align:left;width:3em" value="" ovalue="" /></td></tr>
		      </table>
		  </td>
		</tr>
	      </table>
	    
	    </td>
	  </tr>
	  
	  
	  
	  {foreach from=$company->get_contacts() item=contact  name=foo }
	  <tr style="text-align:left" ><td  style="width:160px;vertical-align: top;"><img src="art/icons/vcard.png"/> {$contact.name}:</td><td  style="text-align:left" >
		<table>
		  <tr><td>{t}Email{/t}</td><td><input style="text-align:left"  value="{$contact.email}"></td></tr>
		  <tr><td>{t}Telephone{/t}</td><td><input style="text-align:left"  value="{$contact.telephone}"></td></tr>
		  <tr><td>{t}Fax{/t}</td><td><input style="text-align:left"  value="{$contact.fax}"></td></tr>

	    </table>
	    </td></tr>
	    {/foreach}
	

 

	</table>
	</div>
      </div>
      

    </div>



</div>
</div>

{include file='footer.tpl'}

