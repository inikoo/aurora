
   

    <div style="margin-top:10px;float:left;padding:20px 10px 20px 10px;border:1px solid #ddd;width:500px">
      <table class="edit">
	<tr><td class="label">{t}Warehouse{/t}:</td><td><input id="location_warehouse" type="text" value="{$warehouse->get('Warehouse Name')}"/><input type="hidden" id="warehouse_key" value="{$warehouse->id}"></td></tr>
	
	<tr>
	   <td class="label">{t}Area{/t}:</td>
	   <td>
	     <div  style="width:25em;xposition:relative;top:00px" >
	       <input type="hidden" id="location_warehouse_area_key" value="{$warehouse_area_id}"/>
		<input type="hidden" id="window" value="{$window}"/>
		<input type="hidden" id="warehouse_area_id" value="{$warehouse_area_id}"/>
		<input type="hidden" id="warehouse_area_name" value="{$warehouse_area_name}"/>


	       <input width=10  value="{$warehouse_area_name}" id="location_area" type="text"/>
	    <div id="location_area_container"  ></div>
	  </div>
	   </td>
	<td><span class="state_details" id="show_area_list">List</span></td>
	</tr>
	
	<tr><td class="label">{t}Used for{/t}:</td>
	  <td>
	    <div class="buttons small left" id="location_used_for" value="{$used_for}"  ovalue="{$used_for}"   prefix="used_for_" class="options" style="margin:5px 0">
	    {foreach from=$used_for_list item=cat key=cat_id name=foo}
	    <button {if $cat.selected}class="selected"{/if} name="{$cat_id}" onclick="radio_changed(this)" id="used_for_{$cat_id}">{$cat.name}</button>
	    {/foreach}
	    </div>
	  </td>
	 </tr>
	<tr><td class="label">{t}Code{/t}:</td><td><input id="location_code" value="" ovalue="" type="text"/></td></tr>
	<tr><td>{t}Max Slots{/t}:</td><td><input id="location_max_slots" value="" ovalue="" type="text"/></td></tr>
	<tr xstyle="display:none"><td class="label">{t}Shape Type{/t}:</td>
	  <td>
	    <div class="buttons small left" id="location_shape_type" value="{$shape_type}" ovalue="{$shape_type}"  prefix="shape_type_"  class="options" style="margin:5px 0">
	    {foreach from=$shape_type_list item=cat key=cat_id name=foo}
	    <button {if $cat.selected}class="selected"{/if} name="{$cat_id}"   onclick="radio_changed(this);shape_type_changed();"  id="shape_type_{$cat_id}">{$cat.name}</button>
	    {/foreach}
	    </div>
	  </td>
	 </tr>
	
	<tr id="tr_location_width"><td class="label">{t}Width{/t} {t}(cm){/t}:</td><td><input id="location_width" value="" ovalue="" type="text"/></td></tr>
	<tr id="tr_location_Depth"><td class="label">{t}Depth{/t} {t}(cm){/t}:</td><td><input  id="location_Depth" value=""  ovalue="" type="text"/></td></tr>
	<tr id="tr_location_heigth"><td class="label">{t}Heigth{/t} {t}(cm){/t}:</td><td><input id="location_heigth" value=""  ovalue="" type="text"/></td></tr>
	<tr id="tr_location_radius"  style="display:none"><td class="label">{t}Radius{/t} {t}(cm){/t}:</td><td><input id="location_radius" value=""  ovalue="" type="text"/></td></tr>
	<tr><td class="label">{t}Max Weight{/t} {t}(Kg){/t}:</td><td><input id="location_max_weight" value="" ovalue=""  type="text"/></td></tr>


       </table>
	<div class="buttons" style="float:right">      
	<button  onClick="save_add_location_return()" ><img src="art/icons/add.png" alt=""> {t}Create & Stay{/t}</button>  
	<button  onClick="save_add_location()" ><img src="art/icons/door_out.png" alt=""> {t}Create{/t}</button>
	</div>
    </div>
    
    <div id="location_save_block" style="margin:0px 20px;padding:20px 20px;float:right;border:1px solid #ddd;width:350px; display:none">
    <div class="buttons" style="float:right">    
        <button  onClick="exit_add_location()" ><img src="art/icons/door_out.png" alt=""> {t}Exit Edit{/t}</button>    
	<button  onClick="save_add_location_return()" ><img src="art/icons/door_out.png" alt=""> {t}Create & Add Other{/t}</button>  
	<button  onClick="save_add_location()" ><img src="art/icons/add.png" alt=""> {t}Create{/t}</button>
    </div>
    </div>

    <div  id="paper" style="margin:10px 20px;xpadding:20px 20px;float:left;width:300px">
     
    </div>
<div id="dialog_area_list">
    <div class="splinter_cell" style="padding:10px 15px 10px 0;border:none">
        <div id="the_table" class="data_table" >
            <span class="clean_table_title">{t}Area List{/t}</span>
            {include file='table_splinter.tpl' table_id=2 filter_name=$filter_name2 filter_value=$filter_value2}
            <div  id="table2"   class="data_table_container dtable btable"> </div>
        </div>
    </div>
 </div>