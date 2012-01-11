{include file='header.tpl'}
<div id="bd" >
 {include file='locations_navigation.tpl'}


  <div  id="orders_table" class="data_table" style="clear:both;margin-top:23px">
    <span class="clean_table_title">{t}Orders In Warehouse{/t} 
    <img id="export_csv0"   tipo="customers_per_store" style="position:relative;top:0px;left:5px;cursor:pointer;vertical-align:text-bottom;" label="{t}Export (CSV){/t}" alt="{t}Export (CSV){/t}" src="art/icons/export_csv.gif"></span>
     

  <div id="table_type" class="table_type">
        <div  style="font-size:90%"   id="transaction_chooser" >


                              <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements.ReadytoShip}selected{/if} label_dn_state_ready_to_ship"  id="elements_ready_to_ship" table_type="ready_to_ship"   >{t}Ready to Ship{/t} (<span id="elements_notes_number">{$elements_number.ReadytoShip}</span>)</span>

                              <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements.PickingAndPacking}selected{/if} label_dn_state_picking_and_packing"  id="elements_picking_and_packing" table_type="picking_and_packing"   >{t}Picking/Packing{/t} (<span id="elements_notes_number">{$elements_number.PickingAndPacking}</span>)</span>
                       <span style="float:right;margin-left:20px;{if !$elements_number.ReadytoRestock}display:none{/if}" class=" table_type transaction_type state_details {if $elements.ReadytoRestock}selected{/if} label_dn_state_ready_to_restock"  id="elements_ready_to_restock" table_type="ready_to_restock"   >{t}Ready to Restock{/t} (<span id="elements_notes_number">{$elements_number.ReadytoRestock}</span>)</span>

       
                     <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements.ReadytoPack}selected{/if} label_dn_state_ready_to_pack"  id="elements_ready_to_pack" table_type="ready_to_pack"   >{t}Ready to Pack{/t} (<span id="elements_notes_number">{$elements_number.ReadytoPack}</span>)</span>

                     <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements.ReadytoPick}selected{/if} label_dn_state_ready_to_pick"  id="elements_ready_to_pick" table_type="ready_to_pick"   >{t}Ready to Pick{/t} (<span id="elements_notes_number">{$elements_number.ReadytoPick}</span>)</span>

     
     </div>
     </div>
     
  <div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999"></div>
  
  <table style="float:left;margin:0 0 0 0px ;padding:0;height:15px;"  class="options">
	<tr>
	 

	</tr>
      </table>
{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0  }
    <div  id="table0" style="font-size:90%"  class="data_table_container dtable btable "> </div>
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

{include file='footer.tpl'}


<div id="assign_picker_dialog" style="width:300px;">
<div class="options" style="width:300px;padding:10px;text-align:center" >

   <table border=1 style="margin:auto" id="assign_picker_buttons">
      {foreach from=$pickers item=picker_row name=foo}
      <tr>
	 {foreach from=$picker_row key=row_key item=picker }
	
	<td staff_id="{$picker.StaffKey}" id="picker{$picker.StaffKey}" class="assign_picker_button" onClick="select_staff(this,event)" >{$picker.StaffAlias}</td>
	{/foreach}
	</tr>
      {/foreach}
    </table>


</div>
<table class="edit">
<input type="hidden" id="assign_picker_staff_key">
<input type="hidden" id="assign_picker_dn_key">

<tr class="first"><td  class="label">{t}Staff Name{/t}:</td>
   <td  style="text-align:left">
     <div  style="width:190px;position:relative;top:00px" >
       <input style="text-align:left;width:180px" id="Assign_Picker_Staff_Name" value="" ovalue="" valid="0">
       <div id="Assign_Picker_Staff_Name_Container"  ></div>
     </div>
   </td>
   <td id="Assign_Picker_Staff_Name_msg" class="edit_td_alert"></td>
 </tr>
<tr><td>{t}Supervisor PIN{/t}:</td><td><input id="assign_picker_sup_password" type="password" /></td></tr>
</table>
<table class="edit" style="margin-top:10px;float:right">
  
  <tr><td colspan="2">
  <span class="button" onclick="close_dialog('assign_picker_dialog')">Cancel</span>
  <span class="button" onclick="assign_picker_save()" >Go</span><td></tr>
</table>
</div>
<div id="pick_it_dialog" style="width:300px;">
<div class="options" style="width:300px;padding:10px;text-align:center" >

   <table border=1 style="margin:auto" id="pick_it_buttons">
      {foreach from=$pickers item=picker_row name=foo}
      <tr>
	 {foreach from=$picker_row key=row_key item=picker }
	
	<td staff_id="{$picker.StaffKey}" id="picker_pick_it{$picker.StaffKey}" class="pick_it_button" onClick="select_staff_pick_it(this,event)" >{$picker.StaffAlias}</td>
	{/foreach}
	</tr>
      {/foreach}
    </table>


</div>
<table class="edit">
<input type="hidden" id="pick_it_staff_key">
<input type="hidden" id="pick_it_dn_key">

<tr class="first"><td  class="label">{t}Staff Name{/t}:</td>
   <td  style="text-align:left">
     <div  style="width:190px;position:relative;top:00px" >
       <input style="text-align:left;width:180px" id="pick_it_Staff_Name" value="" ovalue="" valid="0">
       <div id="pick_it_Staff_Name_Container"  ></div>
     </div>
   </td>
   <td id="pick_it_Staff_Name_msg" class="edit_td_alert"></td>
 </tr>
<tr id="pick_it_pin_tr" style="visibility:hidden"><td><span id="pick_it_pin_alias"></span> {t}PIN{/t}:</td><td><input id="pick_it_password" type="password" /></td></tr>
</table>
<table class="edit" style="margin-top:10px;float:right">
  
  <tr><td colspan="2">
  <span class="button" onclick="close_dialog('pick_it_dialog')">Cancel</span>
  <span class="button" onclick="pick_it_save()" >Go</span><td></tr>
</table>
</div>
<div id="pick_assigned_dialog" style="width:300px;">

<table class="edit">
<input type="hidden" id="pick_assigned_staff_key">
<input type="hidden" id="pick_assigned_dn_key">


<tr><td>{t}PIN{/t} (<span id="pick_assigned_pin_alias"></span>):</td><td><input id="pick_assigned_password" type="password" /></td></tr>
</table>
<table class="edit" style="margin-top:10px;float:right">
  
  <tr><td colspan="2">
  <span class="button" onclick="close_dialog('pick_assigned_dialog')">Cancel</span>
  <span class="button" onclick="pick_assigned_save()" >Go</span><td></tr>
</table>
</div>

<div id="assign_packer_dialog" style="width:300px;">
<div class="options" style="width:300px;padding:10px;text-align:center" >

   <table border=1 style="margin:auto" id="assign_packer_buttons">
      {foreach from=$packers item=packer_row name=foo}
      <tr>
	 {foreach from=$packer_row key=row_key item=packer }
	
	<td staff_id="{$packer.StaffKey}" id="packer{$packer.StaffKey}" class="assign_packer_button" onClick="select_staff(this,event)" >{$packer.StaffAlias}</td>
	{/foreach}
	</tr>
      {/foreach}
    </table>


</div>
<table class="edit">
<input type="hidden" id="assign_packer_staff_key">
<input type="hidden" id="assign_packer_dn_key">

<tr class="first"><td  class="label">{t}Staff Name{/t}:</td>
   <td  style="text-align:left">
     <div  style="width:190px;position:relative;top:00px" >
       <input style="text-align:left;width:180px" id="Assign_packer_Staff_Name" value="" ovalue="" valid="0">
       <div id="Assign_packer_Staff_Name_Container"  ></div>
     </div>
   </td>
   <td id="Assign_packer_Staff_Name_msg" class="edit_td_alert"></td>
 </tr>
<tr><td>{t}Supervisor PIN{/t}:</td><td><input id="assign_packer_sup_password" type="password" /></td></tr>
</table>
<table class="edit" style="margin-top:10px;float:right">
  
  <tr><td colspan="2">
  <span class="button" onclick="close_dialog('assign_packer_dialog')">Cancel</span>
  <span class="button" onclick="assign_packer_save()" >Go</span><td></tr>
</table>
</div>
<div id="pack_it_dialog" style="width:300px;">
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
<table class="edit">
<input type="hidden" id="pack_it_staff_key">
<input type="hidden" id="pack_it_dn_key">

<tr class="first"><td  class="label">{t}Staff Name{/t}:</td>
   <td  style="text-align:left">
     <div  style="width:190px;position:relative;top:00px" >
       <input style="text-align:left;width:180px" id="pack_it_Staff_Name" value="" ovalue="" valid="0">
       <div id="pack_it_Staff_Name_Container"  ></div>
     </div>
   </td>
   <td id="pack_it_Staff_Name_msg" class="edit_td_alert"></td>
 </tr>
<tr id="pack_it_pin_tr" style="visibility:hidden"><td><span id="pack_it_pin_alias"></span> {t}PIN{/t}:</td><td><input id="pack_it_password" type="password" /></td></tr>
</table>
<table class="edit" style="margin-top:10px;float:right">
  
  <tr><td colspan="2">
  <span class="button" onclick="close_dialog('pack_it_dialog')">Cancel</span>
  <span class="button" onclick="pack_it_save()" >Go</span><td></tr>
</table>
</div>
<div id="pack_assigned_dialog" style="width:300px;">

<table class="edit">
<input type="hidden" id="pack_assigned_staff_key">
<input type="hidden" id="pack_assigned_dn_key">


<tr><td>{t}PIN{/t} (<span id="pack_assigned_pin_alias"></span>):</td><td><input id="pack_assigned_password" type="password" /></td></tr>
</table>
<table class="edit" style="margin-top:10px;float:right">
  
  <tr><td colspan="2">
  <span class="button" onclick="close_dialog('pack_assigned_dialog')">Cancel</span>
  <span class="button" onclick="pack_assigned_save()" >Go</span><td></tr>
</table>
</div>



{include file='export_csv_menu_splinter.tpl' id=0 cols=$export_csv_table_cols session_address="ready_to_pick_orders-table-csv_export" export_options=$csv_export_options }
