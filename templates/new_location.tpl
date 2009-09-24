{include file='header.tpl'}
<div style="display:none; position:absolute; left:10px; top:200px; z-index:2" id="cal1Container"></div>
<div id="bd" >

  <h1 id="wellcome" style="padding:10px 20px">{t}Choose a location type{/t}</h1>
  <div id="the_chooser" class="chooser" style="margin:0px 20px">
    <ul id="chooser_ul">
      <li id="individual" class="show"  > {t}Individual{/t}</li>
      <li id="shelf" class="show" > {t}Shelf{/t}</li>
      <li id="rack" class="show" > {t}Pallet Rack{/t}</li>
      <li id="floor" class="show"   > {t}Floor Space{/t}</li>
    </ul>
  </div>
  

  <div id="block_individual"  style="xdisplay:none;margin:0px 20px;clear:both;">
    <div style="float:left;padding:20px;border:1px solid #ddd;width:400px">
      <table class="edit">
	<tr><td class="label">{t}Warehouse{/t}:</td><td><input id="location_warehouse" type="text" value="{$warehouse->get('Warehouse Name')}"/><input type="hidden" id="warehouse_key" value="{$warehouse->id}"></td></tr>
	
	<tr>
	   <td class="label">{t}Area{/t}:</td>
	   <td>
	     <div  style="width:15em;xposition:relative;top:00px" >
	       <input type="hidden" id="location_warehouse_area_key" value=""/>
	       <input width=10  id="location_area" type="text"/>
	    <div id="location_area_container" style="" ></div>
	  </div>
	   </td>
	</tr>
	
	<tr><td class="label">{t}Used for{/t}:</td>
	  <td>
	    <div id="location_used_for" value="{$used_for}"  ovalue="{$used_for}"   prefix="used_for_" class="options" style="margin:5px 0">
	    {foreach from=$used_for_list item=cat key=cat_id name=foo}
	    <span {if $cat.selected}class="selected"{/if} name="{$cat_id}" onclick="radio_changed(this)" id="used_for_{$cat_id}">{$cat.name}</span>
	    {/foreach}
	    </div>
	  </td>
	 </tr>
	<tr><td class="label">{t}Code{/t}:</td><td><input id="location_code" value="" ovalue="" type="text"/></td></tr>
	<tr><td>{t}Max Slots{/t}:</td><td><input id="location_max_slots" value="" ovalue="" type="text"/></td></tr>
	<tr xstyle="display:none"><td class="label">{t}Shape Type{/t}:</td>
	  <td>
	    <div id="location_shape_type" value="{$shape_type}" ovalue="{$shape_type}"  prefix="shape_type_"  class="options" style="margin:5px 0">
	    {foreach from=$shape_type_list item=cat key=cat_id name=foo}
	    <span {if $cat.selected}class="selected"{/if} name="{$cat_id}"   onclick="radio_changed(this);shape_type_changed();"  id="shape_type_{$cat_id}">{$cat.name}</span>
	    {/foreach}
	    </div>
	  </td>
	 </tr>
	
	<tr id="tr_location_width"><td class="label">{t}Width{/t} {t}(cm){/t}:</td><td><input id="location_width" value="" ovalue="" type="text"/></td></tr>
	<tr id="tr_location_deepth"><td class="label">{t}Deepth{/t} {t}(cm){/t}:</td><td><input  id="location_deepth" value=""  ovalue="" type="text"/></td></tr>
	<tr id="tr_location_heigth"><td class="label">{t}Heigth{/t} {t}(cm){/t}:</td><td><input id="location_heigth" value=""  ovalue="" type="text"/></td></tr>
	<tr id="tr_location_radius"  style="display:none"><td class="label">{t}Radius{/t} {t}(cm){/t}:</td><td><input id="location_radius" value=""  ovalue="" type="text"/></td></tr>
	<tr><td class="label">{t}Max Weight{/t} {t}(Kg){/t}:</td><td><input id="location_max_weight" value="" ovalue=""  type="text"/></td></tr>


       </table>
      
    </div>
    
    <div id="location_save_block" style="margin:0px 20px;padding:20px 20px;float:left;border:1px solid #ddd;width:300px">
      <span id="add_location" class="button">{t}Save{/t}</span>
      
      <span style="margin-right:10px" id="cancel_edit_location" class="button">{t}Cancel{/t}</span>

    </div>
    <div  id="paper" style="margin:10px 20px;xpadding:20px 20px;float:left;width:300px">
     
    </div>
    
  </div>





    <div style="clear:both"></div>
  
  <div id="the_table" class="data_table" style="margin:20px 20px 20px 20px;clear:both">
    <span class="clean_table_title">{t}Locations{/t}</span>
    <div  class="clean_table_caption"  style="clear:both;">
      <div style="float:left;"><div id="table_info0" class="clean_table_info"><span id="rtext0"></span> <span class="filter_msg"  id="filter_msg0"></span></div></div>
      <div class="clean_table_filter" id="clean_table_filter0"><div class="clean_table_info"><span id="filter_name0">{$filter_name}</span>: <input style="border-bottom:none" id='f_input0' value="{$filter_value}" size=10/><div id='f_container'></div></div></div>
      <div class="clean_table_controls" style="" ><div><span  style="margin:0 5px" id="paginator"></span></div></div>
    </div>
    <div  id="table0"   class="data_table_container dtable btable "> </div>
  </div>
    



</div>
{include file='footer.tpl'}

