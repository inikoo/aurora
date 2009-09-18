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
  

  <div id="block_individual"  style="display:none;margin:0px 20px;clear:both;">
    <div style="float:left;padding:20px;border:1px solid #ddd;width:400px">
      <table class="edit">
	<tr><td class="label">{t}Warehouse{/t}:</td><td><input id="location_warehouse" type="text" value="{$warehouse->get('Warehouse Name')}"/><input type="hidden" id="warehouse_key" value="{$warehouse->id}"></td></tr>
	<tr><td class="label">{t}Area{/t}:</td><td><input  id="location_area" type="text"/></td></tr>
	
	<tr><td class="label">{t}Used for{/t}:</td>
	  <td>
	    <div id="location_used_for" class="options" style="margin:5px 0">
	    {foreach from=$used_for item=cat key=cat_id name=foo}
	    <span {if $cat.selected}class="selected"{/if} value="{$cat.selected}" ovalue="{$cat.selected}" onclick="checkbox_changed(this)" id="cat_use{$cat_id}">{$cat.name}</span>
	    {/foreach}
	    </div>
	  </td>
	 </tr>
	<tr><td class="label">{t}Code{/t}:</td><td><input id="location_code" type="text"/></td></tr>
	<tr><td>{t}Max Slots{/t}:</td><td><input id="location_max_slots" type="text"/></td></tr>
	<tr xstyle="display:none"><td class="label">{t}Shape Type{/t}:</td>
	  <td>
	    <div id="location_shape_type" class="options" style="margin:5px 0">
	    {foreach from=$shape_type item=cat key=cat_id name=foo}
	    <span {if $cat.selected}class="selected"{/if} value="{$cat.selected}" ovalue="{$cat.selected}" onclick="checkbox_changed(this)" id="cat_use{$cat_id}">{$cat.name}</span>
	    {/foreach}
	    </div>
	  </td>
	 </tr>
	
	<tr><td class="label">{t}Width{/t} {t}(cm){/t}:</td><td><input id="location_width" type="text"/></td></tr>
	<tr><td class="label">{t}Deepth{/t} {t}(cm){/t}:</td><td><input  id="location_deepth" type="text"/></td></tr>
	<tr><td class="label">{t}Heigth{/t} {t}(cm){/t}:</td><td><input id="location_heigth" type="text"/></td></tr>
	<tr xstyle="display:none"><td class="label">{t}Radius{/t} {t}(cm){/t}:</td><td><input id="location_radius" type="text"/></td></tr>
	<tr><td class="label">{t}Max Weight{/t} {t}(Kg){/t}:</td><td><input id="location_weight" type="text"/></td></tr>


       </table>
      
    </div>
    
    <div id="location_save_block" style="margin:0px 20px;padding:20px 20px;float:left;border:1px solid #ddd;width:300px">
      <span id="save_location" class="button">{t}Save{/t}</span>
      
      <span style="margin-right:10px" id="cancel_edit_lcoation" class="button">{t}Cancel{/t}</span>

    </div>
    
    
  </div>





   
    



</div>
{include file='footer.tpl'}

