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
      <h1>{t}Editing contact{/t} {$contact->get(ID)}</h1>

      <div class="chooser2" >
	<ul >
	  <li id="personal" {if $edit=='personal'}class="selected"{/if} ><img src="art/icons/user.png"> {t}Personal{/t}</li>
	  <li id="work" {if $edit=='work'}class="selected"{/if} > <img src="art/icons/building.png"> {t}Work{/t}</li>
	  <li id="pictures" {if $edit=='pictures'}class="selected"{/if} > <img src="art/icons/photos.png"> {t}Pictures{/t}</li>
	  <li id="other" {if $edit=='other'}class="selected"{/if} > <img src="art/icons/information.png"> {t}Other{/t}</li>
	</ul>
      </div>
      <div style="clear:both;height:3em;padding:10px 20px;;margin:20px 0;border-top: 1px solid #cbb;;border-bottom: 1px solid #caa;width:770px;" id="personal_messages">
	<div xstyle="float:left">
	  <span class="save" style="display:none" id="description_save" onclick="save('description')">Save</span><span id="description_reset"  style="display:none"   class="reset" onclick="reset('description')">Reset</span>
	</div>
	<span>Number of changes:<span id="description_num_changes">0</span></span>
	
	<div id="description_errors">
	</div>
	<div id="description_warnings">
	</div>
      </div>
      

      <div  style="{if $edit!="personal"}display:none;{/if}margin:0"  class="edit_block" id="d_prices">
	<table class="edit" border=0>
	  <tr class="title"><td>Name:</td><td colspan="2" style="text-align:left"><input style="text-align:left;width:12em" id="full_name" value="{$contact->get('Contact Name')}"></td>
	  </tr>
	   <tr>
	    <td class="label">{t}Salutation{/t}:</td>
	    <td  style="text-align:left" >
	      <table id="period_options" style="float:none;position:relative;left:-4px;" border=0  class="options_mini" >
		 <tr>
		   
		   {foreach from=$prefix item=s  }
		   
		   <td  {if $contact->get('Contact Salutation')==$s.txt}class="selected"{/if}  {if $s.relevance>1}style="display:none"{/if}     id="salutation{$s.id}" >{$s.txt}</td>
		   {/foreach}

		 </tr>
	       </table>
	    </td>
	  </tr>

	  <tr>
	    <td class="label">{t}First Name(s){/t}:</td>
	    <td  style="text-align:left" ><input  onkeydown=""  onblur="" style="text-align:left;width:12em"  name="first_name" id="v_first_name" value="{$contact->get('Contact First Name')}"  ovalue="{$contact->get('Contact First Name')}" ></td>
	  </tr>
       <tr>
	    <td class="label">{t}Surname(s){/t}:</td>
	    <td  style="text-align:left" ><input  onkeydown=""  onblur="" style="text-align:left;width:12em"  name="surname" id="v_surname" value="{$contact->get('Contact Surname')}"  ovalue="{$contact->get('Contact Surname')}" ></td>
	  </tr>
    
  </table>
</div>



    </div>



</div>
</div>

{include file='footer.tpl'}

