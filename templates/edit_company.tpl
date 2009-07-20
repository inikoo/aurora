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
	  
	  <tr class="title"><td colspan="2" style="width:160px">Details:</td>
	  </tr>
	  <tr class="first"><td style="width:160px">Public Name:</td><td  style="text-align:left"><input style="text-align:left;width:12em" id="full_name" value="{$company->get('Company Name')}" ovalue="{$company->get('Company Name')}"></td>
	  </tr>
	  <tr class="first"><td style="width:160px">Fiscal Name:</td><td style="text-align:left"><input style="text-align:left;width:12em" id="fiscal_name" value="{$company->get('Company Fiscal Name')}" ovalue="{$company->get('Company Fiscal Name')}" ></td>
	  </tr>
	  <tr class="first"><td style="width:160px">Tax Number:</td><td  style="text-align:left"><input style="text-align:left;width:12em" id="fiscal_name" value="{$company->get('Company Tax Number')}" ovalue="{$company->get('Company Tax Number')}" ></td>
	  </tr>
	  <tr class="first"><td style="width:160px">Registration Number:</td><td style="text-align:left"><input style="text-align:left;width:12em" id="fiscal_name" value="{$company->get('Company Registration Number')}" ovalue="{$company->get('Company Registration Number')}" ></td>
	  </tr>
	</table>
      </div>

 <div  style="{if $edit!="address"}xdisplay:none;{/if}margin:0"  class="edit_block" id="d_address">
	<table class="edit" border=0>
	  
	  <tr class="title"><td colspan="2" style="width:160px">Address:</td>
	  </tr>
	  <tr class="first"><td style="width:160px">Country:</td><td  style="text-align:left"><input style="text-align:left;width:12em" id="main_address_country" value="{$address->get('Address Country Name')}" ovalue="{$address->get('Address Country Name')}"></td>
	  </tr>
	   <tr ><td style="width:160px">Postal Code:</td><td  style="text-align:left"><input style="text-align:left;width:12em" id="main_address_country_d1" value="{$address->get('Address Postal Code')}" ovalue="{$address->get('Address Postal Code')}"></td>
	  </tr>
	  <tr ><td style="width:160px">Region:</td><td  style="text-align:left"><input style="text-align:left;width:12em" id="main_address_country_d1" value="{$address->get('Address Country Primary Division')}" ovalue="{$address->get('Address Country Primary Division')}"></td>
	  </tr>
	  <tr ><td style="width:160px">Subregion:</td><td  style="text-align:left"><input style="text-align:left;width:12em" id="main_address_country_d1" value="{$address->get('Address Country Secondary Division')}" ovalue="{$address->get('Address Country Secondary Division')}"></td>
	  </tr>
	  <tr ><td style="width:160px">City:</td><td  style="text-align:left"><input style="text-align:left;width:12em" id="main_address_country_d1" value="{$address->get('Address Town')}" ovalue="{$address->get('Address Town')}"></td>
	  <tr ><td style="width:160px">Street/Number:</td><td  style="text-align:left"><input style="text-align:left;width:12em" id="main_address_country_d1" value="{$address->display('street')}" ovalue="{$address->display('street')}"></td>
	  <tr ><td style="width:160px">Building:</td><td  style="text-align:left"><input style="text-align:left;width:12em" id="main_address_country_d1" value="{$address->get('Address Building')}" ovalue="{$address->get('Address Building')}"></td>
	  </tr>
	  <tr ><td style="width:160px">Internal:</td><td  style="text-align:left"><input style="text-align:left;width:12em" id="main_address_country_d1" value="{$address->get('Address Internal')}" ovalue="{$address->get('Address Internal')}"></td>
	  </tr>
	</table>
      </div>

      <div  style="{if $edit!="contacts"}xdisplay:none;{/if}margin:0"  class="edit_block" id="d_contacts">
	<table class="edit" border=1>
	  <tr class="title"><td  colspan="2" >Contacts:</td></tr>
	    
	    {foreach from=$company->get_contacts() item=contact  name=foo }
	    <tr style="text-align:left" ><td  style="width:160px;vertical-align: top;"><img src="art/icons/vcard.png"/> {$contact.name}:</td><td>cc</td></tr>
	    {/foreach}
	

 

	</table>
	</div>
      </div>
      

    </div>



</div>
</div>

{include file='footer.tpl'}

