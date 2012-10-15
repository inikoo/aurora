
{include file='header.tpl'}
<div id="bd" >
 {include file='locations_navigation.tpl'}
<input type="hidden" id="location_name" value="{$location->get('Location Code')}"/>
<input type="hidden" id="location_key" value="{$location->id}"/>
<input type="hidden" id="area_key" value="{$location->get('Location Warehouse Area Key')}"/>


<div class="branch"> 
  <span ><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home"/></a>&rarr; {if $user->get_number_warehouses()>1}<a href="warehouses.php">{t}Warehouses{/t}</a> &rarr; <a href="inventory.php?id={$location->get('Location Warehouse Key')}">{$location->get('Warehouse Name')} {t}Inventory{/t}</a> {/if}<a href="warehouse.php?id={$location->get('Location Warehouse Key')}">{t}Locations{/t}</a>  &rarr; <a  href="warehouse_area.php?id={$location->get('Location Warehouse Area Key')}">{$location->get('Warehouse Area Name')} {t}Area{/t}</a> {if $location->get('Location Shelf Key')} &rarr; <a  href="shelf.php?id={$location->get('Location Shelf Key')}">{t}Shelf{/t} {$location->get('Shelf Code')}</a>{/if} &rarr; {$location->get('Location Code')}</span>
</div>

<div class="top_page_menu">
    <div class="buttons" style="float:right">
     
   
        <button  onclick="window.location='location.php?id={$location->id}'" ><img src="art/icons/door_out.png" alt=""> {t}Exit Edit{/t}</button>
        <button  {if $location_id==1 || $location_id==2}style="display:none"{/if} id="show_dialog_delete_location" onclick="show_dialog_delete_location()" ><img src="art/icons/cancel.png" alt=""> {t}Delete Location{/t}</button>

    </div>
    <div class="buttons" style="float:left">


 </div>
    <div style="clear:both"></div>
</div>

<div style="clear:left;margin:0 0px">
    <h1>{t}Editing Location{/t}: <span id="title_name">{$location->get('Location Code')}</span></h1>
</div>
<ul class="tabs" id="chooser_ul" style="clear:both">
  <li> <span class="item {if $edit=='description'}selected{/if}"  id="description">  <span> {t}Description{/t}</span></span></li>
    <li> <span class="item {if $edit=='parts'}selected{/if}"  id="parts">  <span> {t}Parts{/t}</span></span></li>

</ul>
<div class="tabbed_container" > 
  <div id="description_block" style="{if $edit!='description'}display:none{/if}" >
    
	<div class="buttons" >
		<button  style="margin-right:10px"  id="save_edit_location_description" class="positive disabled">{t}Save{/t}</button>
		<button style="margin-right:10px" id="reset_edit_location_description" class="negative disabled">{t}Reset{/t}</button>
	</div>


      <table style="margin:0; width:100%" class="edit" border=0>


	


	   		<tr><td class="label">{t}Used for{/t}:</td>
 
	<td colspan=5>
		<div id="location_used_for"   class="buttons left" >
		{foreach from=$used_for_list item=cat key=cat_id name=foo}
		<button class="{if $location->get('Location Mainly Used For')==$cat.name}selected{/if}" onclick="save_location_used_for('used_for','{$cat.name}')" id="used_for_{$cat.name}">{$cat.name}</button> 
	    {/foreach}
		</div>
	</td>
	 
	 </tr> 
	   
	  <tr><td class="label">{t}Flag{/t}:</td>
 
	<td colspan=5>
		<div id="location_used_for"    class="buttons left" >
		{foreach from=$flag_list item=cat key=cat_id name=foo}
		<button class="{if $location->get('Location Flag')==$cat.name}selected{/if}" onclick="save_location_flag('flag','{$cat.name}')" id="flag_{$cat.name}">{$cat.name}</button> 
	    {/foreach}
		</div>
	</td>
	 
	 </tr> 
	   

	

	 
		<tr><td class="label">{t}Location Code{/t}:</td><td>
		<div>
			<input id="Location_Code" changed=0 type='text' class='text' MAXLENGTH="16" value="{$location->get('Location Code')}" ovalue="{$location->get('Location Code')}" />
			<div id="Location_Code_Container"  ></div>
			</div>
			</td>
			<td style="width:200px" id="Location_Code_msg" class="edit_td_alert"></td>
		</tr>
	  

  

	<tr style="display:none"><td class="label">{t}Shape{/t}:</td>

	<td>
		<div id="location_shape_type"  class="buttons" style="margin:0">
		{foreach from=$shape_type_list item=cat key=cat_id name=foo}
		<button class="{if $location->get('Location Shape Type')==$cat.name}selected{/if}" onclick="save_location('shape','{$cat.name}')" id="shape_{$cat.name}">{$cat.name}</button> 
	    {/foreach}
		
		
		</div>
	</td>
	
	 </tr> 


<tr style="display:none"><td style="width:180px" class="label">{t}Location Radius{/t}:</td>
   <td  style="text-align:left">
     <div   >
       <input style="text-align:left;" id="Location_Radius" value="{$location->get('Location Radius')}" ovalue="{$location->get('Location Radius')}" valid="0">
       <div id="Location_Radius_Container"  ></div>
     </div>
   </td>
   <td style="width:200px" id="Location_Radius_msg" class="edit_td_alert"></td>
 </tr>
 
 
<tr style="display:none"><td style="width:180px" class="label">{t}Location Deep{/t}:</td>
   <td  style="text-align:left">
     <div   >
       <input style="text-align:left;" id="Location_Deep" value="{$location->get('Location Deep')}" ovalue="{$location->get('Location Deep')}" valid="0">
       <div id="Location_Deep_Container"  ></div>
     </div>
   </td>
   <td style="width:200px" id="Location_Deep_msg" class="edit_td_alert"></td>
 </tr> 
 
<tr style="display:none"><td style="width:180px" class="label">{t}Location Height{/t}:</td>
   <td  style="text-align:left">
     <div   >
       <input style="text-align:left;" id="Location_Height" value="{$location->get('Location Height')}" ovalue="{$location->get('Location Height')}" valid="0">
       <div id="Location_Height_Container"  ></div>
     </div>
   </td>
   <td style="width:200px" id="Location_Height_msg" class="edit_td_alert"></td>
 </tr> 	
 
 <tr style="display:none"><td style="width:180px" class="label">{t}Location Width{/t}:</td>
   <td  style="text-align:left">
     <div   >
       <input style="text-align:left;" id="Location_Width" value="{$location->get('Location Width')}" ovalue="{$location->get('Location Width')}" valid="0">
       <div id="Location_Width_Container"  ></div>
     </div>
   </td>
   <td style="width:200px" id="Location_Width_msg" class="edit_td_alert"></td>
 </tr> 		
  <tr ><td style="width:180px" class="label">{t}Location Max Weight{/t}:</td>
   <td  style="text-align:left">
     <div   >
       <input style="text-align:left;" id="Location_Max_Weight" value="{$location->get('Location Max Weight')}" ovalue="{$location->get('Location Max Weight')}" valid="0">
       <div id="Location_Max_Weight_Container"  ></div>
     </div>
   </td>
   <td style="width:200px" id="Location_Max_Weight_msg" class="edit_td_alert"></td>
 </tr> 

  <tr ><td style="width:180px" class="label">{t}Location Max Volume{/t}:</td>
   <td  style="text-align:left">
     <div   >
       <input style="text-align:left;" id="Location_Max_Volume" value="{$location->get('Location Max Volume')}" ovalue="{$location->get('Location Max Volume')}" valid="0">
       <div id="Location_Max_Volume_Container"  ></div>
     </div>
   </td>
   <td style="width:200px" id="Location_Max_Volume_msg" class="edit_td_alert"></td>
 </tr> 	 
 
   <tr style="display:none" ><td style="width:180px" class="label">{t}Location Max Slots{/t}:</td>
   <td  style="text-align:left">
     <div   >
       <input style="text-align:left;" id="Location_Max_Slots" value="{$location->get('Location Max Slots')}" ovalue="{$location->get('Location Max Slots')}" valid="0">
       <div id="Location_Max_Slots_Container"  ></div>
     </div>
   </td>
   <td style="width:200px" id="Location_Max_Slots_msg" class="edit_td_alert"></td>
 </tr> 	 

 
 <tr class="first"><td style="width:180px" class="label">{t}Area{/t}:</td>
   <td  style="text-align:left">
 <span id="current_department_code">{$location->get('Warehouse Area Name')}</span> <img id="edit_location_area" id="family" style="margin-left:5px;cursor:pointer" src="art/icons/edit.gif" alt="{t}Edit{/t}" title="{t}Edit{/t}" /s>
   </td>
   <td style="width:200px" id="Edit_Location_Area_msg" class="edit_td_alert"></td>
 </tr>

	</table>
  </div> 
<div id="parts_block" style="{if $edit!='parts'}display:none{/if}" > 

  <span class="clean_table_title">{t}Parts{/t}</span>
            {include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0}
            <div  id="table0"   class="data_table_container dtable btable "> </div>
</div> 
</div>
</div>

<div id="dialog_area_list">
    <div class="splinter_cell" style="padding:10px 15px 10px 0;border:none">
        <div id="the_table" class="data_table" >
            <span class="clean_table_title">{t}Area List{/t}</span>
            {include file='table_splinter.tpl' table_id=2 filter_name=$filter_name2 filter_value=$filter_value2}
            <div  id="table2"   class="data_table_container dtable btable "> </div>
        </div>
    </div>
 </div>


<div id="dialog_delete_location" style="padding:10px">
	<table style="margin:10px">
		<tr>
			<td> {t}This location has {/t}{$number_of_parts}{t} part(s). Parts will be moved to unknown location{/t}</td>
		</tr>
		<tr>
			<td> {t}Are you sure want to delete this location ?{/t}</td>
		</tr>
		<tr>
			<td colspan="2"> 
			<div class="buttons" style="margin-top:10px">
				<button class="negative" id="delete_location">{t}Delete{/t}</button>
				<button class="positive" id="close_dialog_delete_location">{t}Cancel{/t}</button> 
			</div>
			</td>
		</tr>
	</table>
</div>

{include file='footer.tpl'}
