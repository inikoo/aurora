{include file='header.tpl'}
<div id="bd" >
 {include file='locations_navigation.tpl'}

 <div  class="branch"> 
  <span> <a href="hr.php">{t}Staff{/t} </a>  &rarr;<a href="staff.php?id={$staff->id}"> {$staff->get('Staff Name')} </a></span>
</div>

<div class="top_page_menu">
    <div class="buttons" style="float:right">
     
   
        <button  onclick="window.location='staff.php?id={$staff->id}'" ><img src="art/icons/door_out.png" alt=""> {t}Exit Edit{/t}</button>
        <button  style="display:none" onclick="delete_location()" ><img src="art/icons/cancel.png" alt=""> {t}Delete Area{/t}</button>

    </div>
    <div class="buttons" style="float:left">


 </div>
    <div style="clear:both"></div>
</div>
<input type="hidden" id="staff_key" value="{$staff->id}"/>


<div style="clear:left;margin:0 0px">
    <h1>{t}Editing Staff{/t}: <span id="title_name">{$staff->get('Staff Name')}</span></h1>
</div>
<ul class="tabs" id="chooser_ul" style="clear:both">
  <li> <span class="item {if $edit=='description'}selected{/if}"  id="description">  <span> {t}Description{/t}</span></span></li>
    <li> <span class="item {if $edit=='pin'}selected{/if}"  id="pin">  <span> {t}PIN{/t}</span></span></li>

</ul>
<div class="tabbed_container" > 
  <div id="description_block" style="{if $edit!='description'}display:none{/if}" >
    



      <table style="margin:0; width:100%" class="edit" border=0>


	


	<tr style="display:none"><td class="label">{t}Used for{/t}:</td>
	<td colspan=5>
	<div id="location_used_for" default_cat="{$cat2.default_id}"   class="buttons left" >
	{foreach from=$used_for_list item=cat key=cat_id name=foo}
	<button class="{if $staff->get('Location Mainly Used For')==$cat.name}selected{/if}" onclick="save_location_used_for('used_for','{$cat.name}')" id="used_for_{$cat.name}">{$cat.name}</button> 
	{/foreach}
	</div>
	</td>
	</tr> 
   
	   
	 <tr class="title"><td colspan=5>{t}Details{/t}</td></tr> 
	<div class="buttons" >
		<button  style="margin-right:10px;visibility:hidden"  id="save_edit_staff_description" class="positive">{t}Save{/t}</button>
		<button style="margin-right:10px;visibility:hidden" id="reset_edit_staff_description" class="negative">{t}Reset{/t}</button>
	</div>
	

	 
		<tr class="first"><td class="label">{t}Staff Alias{/t}:</td><td>
		<div>
			<input id="Staff_Alias" changed=0 type='text' class='text' MAXLENGTH="16" value="{$staff->get('Staff Alias')}" ovalue="{$staff->get('Staff Alias')}" />
			<div id="Staff_Alias_Container"  ></div>
			</div>
			</td>
			<td style="width:200px" id="Staff_Alias_msg" class="edit_td_alert"></td>
		</tr>
	  



	</table>
  </div> 
<div id="pin_block" style="{if $edit!='pin'}display:none{/if}" > 
	<table>
	<div class="buttons" >
		<button  style="margin-right:10px;visibility:hidden"  id="save_edit_staff_pin" class="positive">{t}Save{/t}</button>
		<button style="margin-right:10px;visibility:hidden" id="reset_edit_staff_pin" class="negative">{t}Reset{/t}</button>
	</div>
		<tr class="first"><td class="label">{t}Staff PIN{/t}:</td><td>
		<div>
			<input id="Staff_PIN" changed=0 type='text' class='text' style="width:100px"  MAXLENGTH="16" value="{$staff->get('Staff PIN')}" ovalue="{$staff->get('Staff PIN')}" />
			<div id="Staff_PIN_Container"  ></div>
			</div>
			</td>
			<td style="width:200px" id="Staff_PIN_msg" class="edit_td_alert"></td>
		</tr>
		<tr class="first"><td class="label">{t}Confirm PIN{/t}:</td><td>
		<div>
			<input id="Staff_PIN_Confirm" changed=0 type='text' class='text' style="width:100px"  MAXLENGTH="16" value="" ovalue="" />
			<div id="Staff_PIN_Confirm_Container"  ></div>
			</div>
			</td>
			<td style="width:200px" id="Staff_PIN_Confirm_msg" class="edit_td_alert"></td>
		</tr>

	</table>
</div> 
</div>
</div>

{include file='footer.tpl'}
