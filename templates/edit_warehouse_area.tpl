{include file='header.tpl'}
<div id="bd" >
{include file='locations_navigation.tpl'}
<input type="hidden" id="warehouse_key" value="{$warehouse->id}" />
<input type="hidden" id="warehouse_area_key" value="{$warehouse_area->id}" />

<div class="branch"> 
 {if $user->get_number_warehouses()>1}<a href="warehouses.php">{t}Warehouses{/t}</a> &rarr; <a href="inventory.php?id={$warehouse_area->get('Warehouse Key')}">{$location->get('Warehouse Name')} {t}Inventory{/t}</a> {/if} <a href="warehouse.php?id={$warehouse_area->get('Warehouse Key')}">{t}Locations{/t}</a>  &rarr; {$warehouse_area->get('Warehouse Area Name')} 
</div>


<div class="top_page_menu">
    <div class="buttons" style="float:right">
       
        <button  onclick="window.location='warehouse.php?id={$warehouse->id}'" ><img src="art/icons/door_out.png" alt=""> {t}Exit Edit{/t}</button>
        <button  onclick="window.location='new_location.php?warehouse_area_id={$warehouse_area->id}'" ><img src="art/icons/add.png" alt=""> {t}Add Location{/t}</button>

        
       
    </div>
    <div class="buttons" style="float:left">
      

 </div>
    <div style="clear:both"></div>
</div>



<div style="clear:left;margin:0 0px">
    <h1>{t}Editing Warehouse Area{/t}: <span id="title_name">{$warehouse_area->get('Warehouse Area Name')}</span> (<span id="title_code">{$warehouse_area->get('Warehouse Area Code')}</span>)</h1>
</div>
<ul class="tabs" id="chooser_ul" style="clear:both">
    <li> <span class="item {if $edit=='description'}selected{/if}"  id="description">  <span> {t}Description{/t}</span></span></li>
    <li> <span class="item {if $edit=='locations'}selected{/if}"  id="locations">  <span> {t}Locations{/t}</span></span></li>
</ul>
<div class="tabbed_container" > 
   <div id="description_block" style="{if $edit!='description'}display:none{/if}" >
		<div id="new_warehouse_area_block" style="font-size:80%;float:left;padding:10px 15px;border:1px solid #ddd;width:200px;margin-bottom:15px;margin-left:10px;display:none">
     </div>												   
     <div class="buttons">
	
	<button class="positive" id="save_new_warehouse_area" onclick="save_edit_warehouse_area()">{t}Save{/t}</button>
	<button id="description_reset" class="negative" onclick="reset_description_data_area()">{t}Cancel{/t}</button>
	</div>

      <table style="margin:0;" class="edit" border=0>
	<tr>
	<td class="label">{t}Warehouse Area Code{/t}:</td>
	<td>
	<div style="width:220px">
	<input type="text" id="warehouse_area_code" value="{$warehouse_area->get('Warehouse Area Code')}" ovalue="{$warehouse_area->get('Warehouse Area Code')}" valid="0">
	<div id="warehouse_area_code_Container"  ></div>
	</div>
	</td><td>
	<span id="warehouse_area_code_msg" ></span>	
	</td>

	</tr>

	<tr>
	<td class="label">{t}Warehouse Area Name{/t}:</td>
	<td>
	<div style="width:220px">
	<input type="text" id="warehouse_area_name" value="{$warehouse_area->get('Warehouse Area Name')}" ovalue="{$warehouse_area->get('Warehouse Area Name')}" valid="0">
	<div id="warehouse_area_name_Container"  ></div>
	</div>
	</td><td>
	<span id="warehouse_area_name_msg" ></span>	
	</td>

	</tr>

	</table>

  </div>
   <div id="locations_block" style="{if $edit!='locations'}display:none{/if}" >
 
 
 
 
 <div id="the_table0" class="data_table" style="margin:20px 0px;clear:both">
       <span class="clean_table_title">{t}Locations{/t}</span>
       {include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0}
       <div  id="table0"   class="data_table_container dtable btable "> </div>
     </div>
   </div>
 

</div>

</div>


</div>

<div id="filtermenu0" class="yuimenu">
  <div class="bd">
    <ul class="first-of-type">
       <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Filter options{/t}:</li>
      {foreach from=$filter_menu0 item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_filter('{$menu.db_key}','{$menu.label}',0)"> {$menu.menu_label}</a></li>
      {/foreach}
    </ul>
  </div>
</div>
<div id="rppmenu0" class="yuimenu">
  <div class="bd">
    <ul class="first-of-type">
       <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Rows per Page{/t}:</li>
      {foreach from=$paginator_menu0 item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_rpp({$menu},0)"> {$menu}</a></li>
      {/foreach}
    </ul>
  </div>
</div>

<div id="area_dialog" style="width:300px;">
<div class="options" style="width:300px;padding:10px;text-align:center" >

   <table border=1 style="margin:auto" id="pack_it_buttons">
      {foreach from=$packers item=packer_row name=foo}
      <tr>
	 {foreach from=$packer_row key=row_key item=packer }
	
	<td staff_id="{$packer.StaffKey}" id="packer_pack_it{$packer.StaffKey}" class="pack_it_button" onClick="select_staff_pack_it(this,event)" >{$packer.StaffAlias}</td>
	{/foreach}
	</tr>
      {/foreach}
    </table>


</div>

</div>

{include file='footer.tpl'}
