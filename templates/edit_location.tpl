{include file='header.tpl'}
<div id="bd" >
 {include file='locations_navigation.tpl'}`
<input type="hidden" id="location_key" value="{$location->id}"/>
<div style="clear:left;margin:0 0px">
    <h1>{t}Editing Location{/t}: <span id="title_name">{$location->get('Location Code')}</span></h1>
</div>
<ul class="tabs" id="chooser_ul" style="clear:both">
  <li> <span class="item {if $edit=='description'}selected{/if}"  id="description">  <span> {t}Description{/t}</span></span></li>
</ul>
<div class="tabbed_container" > 
  <div id="description_block" style="{if $edit!='description'}display:none{/if}" >
    



      <table style="margin:0;" class="edit" border=0>
	   <div class="general_options" style="float:right">
	<span  style="margin-right:10px;visibility:hidden"  id="save_edit_location_description" class="state_details">{t}Save{/t}</span>
	<span style="margin-right:10px;visibility:hidden" id="reset_edit_location_description" class="state_details">{t}Reset{/t}</span>
</div>
	  
	  
		<tr><td class="label">{t}Location Code{/t}:</td><td>
		<div>
			<input id="Location_Code" changed=0 type='text' class='text' MAXLENGTH="16" value="{$location->get('Location Code')}" ovalue="{$location->get('Location Code')}" />
			<div id="Location_Code_Container" style="" ></div>
			</div>
			</td>
			<td style="width:200px" id="Location_Code_msg" class="edit_td_alert"></td>
		</tr>
	  

  
	<tr><td class="label">{t}Used for{/t}:</td>
 
	<td>
		<div id="location_used_for" default_cat="{$cat2.default_id}"   class="options" style="margin:0">
		{foreach from=$used_for_list item=cat key=cat_id name=foo}
		<span class="{if $location->get('Location Mainly Used For')==$cat.name}selected{/if}" onclick="save_location('used_for','{$cat.name}')" id="used_for_{$cat.name}">{$cat.name}</span> 
	    {/foreach}
		
		
		</div>
	</td>
	
	 </tr>
	<tr><td class="label">{t}Shape{/t}:</td>

	<td>
		<div id="location_shape_type" default_cat="{$cat2.default_id}"   class="options" style="margin:0">
		{foreach from=$shape_type_list item=cat key=cat_id name=foo}
		<span class="{if $location->get('Location Shape Type')==$cat.name}selected{/if}" onclick="save_location('shape','{$cat.name}')" id="shape_{$cat.name}">{$cat.name}</span> 
	    {/foreach}
		
		
		</div>
	</td>
	
	 </tr>
	 
<tr><td style="width:180px" class="label">{t}Location Radius{/t}:</td>
   <td  style="text-align:left">
     <div   >
       <input style="text-align:left;" id="Location_Radius" value="{$location->get('Location Radius')}" ovalue="{$location->get('Location Radius')}" valid="0">
       <div id="Location_Radius_Container" style="" ></div>
     </div>
   </td>
   <td style="width:200px" id="Location_Radius_msg" class="edit_td_alert"></td>
 </tr>
 
 
<tr><td style="width:180px" class="label">{t}Location Deep{/t}:</td>
   <td  style="text-align:left">
     <div   >
       <input style="text-align:left;" id="Location_Deep" value="{$location->get('Location Deep')}" ovalue="{$location->get('Location Deep')}" valid="0">
       <div id="Location_Deep_Container" style="" ></div>
     </div>
   </td>
   <td style="width:200px" id="Location_Deep_msg" class="edit_td_alert"></td>
 </tr> 
 
<tr><td style="width:180px" class="label">{t}Location Height{/t}:</td>
   <td  style="text-align:left">
     <div   >
       <input style="text-align:left;" id="Location_Height" value="{$location->get('Location Height')}" ovalue="{$location->get('Location Height')}" valid="0">
       <div id="Location_Height_Container" style="" ></div>
     </div>
   </td>
   <td style="width:200px" id="Location_Height_msg" class="edit_td_alert"></td>
 </tr> 	
 
 <tr><td style="width:180px" class="label">{t}Location Width{/t}:</td>
   <td  style="text-align:left">
     <div   >
       <input style="text-align:left;" id="Location_Width" value="{$location->get('Location Width')}" ovalue="{$location->get('Location Width')}" valid="0">
       <div id="Location_Width_Container" style="" ></div>
     </div>
   </td>
   <td style="width:200px" id="Location_Width_msg" class="edit_td_alert"></td>
 </tr> 		
  <tr><td style="width:180px" class="label">{t}Location Max Weight{/t}:</td>
   <td  style="text-align:left">
     <div   >
       <input style="text-align:left;" id="Location_Max_Weight" value="{$location->get('Location Max Weight')}" ovalue="{$location->get('Location Max Weight')}" valid="0">
       <div id="Location_Max_Weight_Container" style="" ></div>
     </div>
   </td>
   <td style="width:200px" id="Location_Max_Weight_msg" class="edit_td_alert"></td>
 </tr> 

  <tr><td style="width:180px" class="label">{t}Location Max Volume{/t}:</td>
   <td  style="text-align:left">
     <div   >
       <input style="text-align:left;" id="Location_Max_Volume" value="{$location->get('Location Max Volume')}" ovalue="{$location->get('Location Max Volume')}" valid="0">
       <div id="Location_Max_Volume_Container" style="" ></div>
     </div>
   </td>
   <td style="width:200px" id="Location_Max_Volume_msg" class="edit_td_alert"></td>
 </tr> 	 
 
   <tr><td style="width:180px" class="label">{t}Location Max Slots{/t}:</td>
   <td  style="text-align:left">
     <div   >
       <input style="text-align:left;" id="Location_Max_Slots" value="{$location->get('Location Max Slots')}" ovalue="{$location->get('Location Max Slots')}" valid="0">
       <div id="Location_Max_Slots_Container" style="" ></div>
     </div>
   </td>
   <td style="width:200px" id="Location_Max_Slots_msg" class="edit_td_alert"></td>
 </tr> 	 
    <tr><td style="width:180px" class="label">{t}Location Distinct Parts{/t}:</td>
   <td  style="text-align:left">
     <div   >
       <input style="text-align:left;" id="Location_Distinct_Parts" value="{$location->get('Location Distinct Parts')}" ovalue="{$location->get('Location Distinct Parts')}" valid="0">
       <div id="Location_Distinct_Parts_Container" style="" ></div>
     </div>
   </td>
   <td style="width:200px" id="Location_Distinct_Parts_msg" class="edit_td_alert"></td>
 </tr> 	

	<tr><td class="label">{t}Location Has Stock{/t}:</td>

	<td>
		<div id="location_has_stock_type" default_cat="{$cat2.default_id}"   class="options" style="margin:0">
		{foreach from=$has_stock_list item=cat key=cat_id name=foo}
		<span class="{if $location->get('Location Has Stock')==$cat.name}selected{/if}" onclick="save_location('has_stock','{$cat.name}')" id="has_stock_{$cat.name}">{$cat.name}</span> 
	    {/foreach}
		
		
		</div>
	</td>
	
	 </tr>
 
     <tr><td style="width:180px" class="label">{t}Location Stock Value{/t}:</td>
   <td  style="text-align:left">
     <div   >
       <input style="text-align:left;" id="Location_Stock_Value" value="{$location->get('Location Stock Value')}" ovalue="{$location->get('Location Stock Value')}" valid="0">
       <div id="Location_Stock_Value_Container" style="" ></div>
     </div>
   </td>
   <td style="width:200px" id="Location_Stock_Value_msg" class="edit_td_alert"></td>
 </tr> 	 
	</table>
  </div> 

</div>
</div>


{include file='footer.tpl'}
