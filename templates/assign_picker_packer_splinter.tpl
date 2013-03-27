<div id="assign_picker_dialog" style="width:400px;padding:20px 10px 0px 10px">
	<input type="hidden" id="assign_picker_dialog_type" value="assign_picker"> 
	<div class="options" style="padding:10px;text-align:center;margin:auto">
		<table border="0" style="margin:auto" id="assign_picker_buttons">
			{foreach from=$pickers item=picker_row name=foo} 
			<tr>
				{foreach from=$picker_row key=row_key item=picker } 
				<td staff_id="{$picker.StaffKey}" id="picker{$picker.StaffKey}" scope="picker" class="assign_picker_button" onclick="select_staff(this,event)">{$picker.StaffAlias}</td>
				{/foreach}
				<td id="picker_show_other_staff"  class="assign_picker_button" scope="picker" onclick="show_other_staff(this)" td_id="other_staff_picker"><i>{t}Other{/t}</i></td> 
			</tr>
			{/foreach} 
		</table>
	</div>
	<table class="edit" border="0" style="width:100%">
		<input type="hidden" id="assign_picker_staff_key"> 
		<input type="hidden" id="assign_picker_dn_key"> 
		<tr class="first">
			<td class="label">{t}Staff Name{/t}:</td>
			<td style="text-align:left"> 
			<div>
				<input style="text-align:left;width:180px" id="Assign_Picker_Staff_Name" value="" ovalue="" valid="0"> 
				<div id="Assign_Picker_Staff_Name_Container">
				</div>
			</div>
			</td>
			
		</tr>
		<tr style="{if !($user->can_edit('assign_pp') and $warehouse->get('Warehouse Assign Operations Locked')=='Yes')  }display:none{/if}">
			<td class="label">{t}Supervisor PIN{/t}:</td>
			<td> 
			<input id="assign_picker_sup_password" type="password" />
			</td>
		</tr>
		<tr>
			<td></td>
			<td colspan="2" id="pick_it_msg" class="edit_td_alert"></td>
		</tr>
	</table>
	<table class="edit" style="margin-top:0px;float:right">
		<tr>
			<td colspan="2"> 
			<div class="buttons">
				<button class="positive" onclick="assign_picker_save()">{t}Go{/t}</button> <button class="negative" onclick="close_dialog('assign_picker_dialog')">{t}Cancel{/t}</button> 
			</div>
			<td> 
		</tr>
	</table>
</div>

<div id="assign_packer_dialog" style="width:400px;padding:20px 10px 0px 10px">
	<input type="hidden" id="assign_packer_dialog_type" value="assign_packer"> 
	<div class="options" style="padding:10px;text-align:center;margin:auto">
		<table border="0" style="margin:auto" id="assign_packer_buttons">
			{foreach from=$packers item=packer_row name=foo} 
			<tr>
				{foreach from=$packer_row key=row_key item=packer } 
				<td staff_id="{$packer.StaffKey}" id="packer{$packer.StaffKey}" scope="packer" class="assign_packer_button" onclick="select_staff(this,event)">{$packer.StaffAlias}</td>
				{/foreach}
				<td  id="packer_show_other_staff" class="assign_packer_button" scope="packer" onclick="show_other_staff(this)" td_id="other_staff_packer"><i>{t}Other{/t}</i></td> 
			</tr>
			{/foreach} 
		</table>
	</div>
	<table class="edit" border="0" style="width:100%">
		<input type="hidden" id="assign_packer_staff_key"> 
		<input type="hidden" id="assign_packer_dn_key"> 
		<tr class="first">
			<td class="label">{t}Staff Name{/t}:</td>
			<td style="text-align:left"> 
			<div>
				<input style="text-align:left;width:180px" id="Assign_Packer_Staff_Name" value="" ovalue="" valid="0"> 
				<div id="Assign_Packer_Staff_Name_Container">
				</div>
			</div>
			</td>
			
		</tr>
		<tr style="{if !($user->can_edit('assign_pp') and $warehouse->get('Warehouse Assign Operations Locked')=='Yes')  }display:none{/if}">
			<td class="label">{t}Supervisor PIN{/t}:</td>
			<td> 
			<input id="assign_packer_sup_password" type="password" />
			</td>
		</tr>
		<tr>
			<td></td>
			<td colspan="2" id="pick_it_msg" class="edit_td_alert"></td>
		</tr>
	</table>
	<table class="edit" style="margin-top:0px;float:right">
		<tr>
			<td colspan="2"> 
			<div class="buttons">
				<button class="positive" onclick="assign_packer_save()">{t}Go{/t}</button> <button class="negative" onclick="close_dialog('assign_packer_dialog')">{t}Cancel{/t}</button> 
			</div>
			<td> 
		</tr>
	</table>
</div>

<div id="pick_it_dialog" style="width:390px;padding:10px 20px 10px 10px">
	<div class="options" style="width:100%;padding:10px;text-align:center">
		<table border="0" style="margin:auto;padding:0 10px" id="pick_it_buttons">
			{foreach from=$pickers item=picker_row name=foo} 
			<tr>
				{foreach from=$picker_row key=row_key item=picker } 
				<td staff_id="{$picker.StaffKey}" id="picker_pick_it{$picker.StaffKey}" class="pick_it_button" onclick="select_staff_pick_it(this,event)">{$picker.StaffAlias}</td>
				{/foreach} 
			</tr>
			{/foreach} 
		</table>
	</div>
	<div>
		<table class="edit" border="0" style="margin:auto;padding:0 10px;width:100%">
			<input type="hidden" id="pick_it_staff_key"> 
			<input type="hidden" id="pick_it_dn_key"> 
			<tr id="staff_name_pick_tr" class="first">
				<td class="label">{t}Staff Name{/t}:</td>
				<td style="text-align:left"> 
				<div>
					<input style="text-align:left;width:100%" id="pick_it_Staff_Name" value="" ovalue="" valid="0"> 
					<div id="pick_it_Staff_Name_Container">
					</div>
				</div>
				</td>
				<td> 
				<div class="buttons small left">
					<button onclick="show_other_staff(this)">{t}Other{/t}</button> 
				</div>
				<span id="pick_it_Staff_Name_msg"></span> </td>
			</tr>
			<tr id="pick_it_pin_tr" style="display:none">
				<td><span id="pick_it_pin_alias"></span> {t}PIN{/t}:</td>
				<td> 
				<input id="pick_it_password" type="password" />
				</td>
				<td id="pick_it_password_msg" class="edit_td_alert"></td>
			</tr>
			<tr style="height:10px">
				<td colspan="2" id="pick_it_msg" class="aright edit_td_alert"></td>
			</tr>
			<tr>
				<td colspan="2"> 
				<div class="buttons">
					<button class="positive" onclick="pick_it_save()">{t}Go{/t}</button> <button class="negative" onclick="close_dialog('pick_it_dialog')">{t}Cancel{/t}</button> 
				</div>
				</td>
				<td></td>
			</tr>
		</table>
	</div>
</div>
<div id="pick_assigned_dialog" style="width:300px;">
	<table class="edit" border="0">
		<input type="hidden" id="pick_assigned_staff_key"> 
		<input type="hidden" id="pick_assigned_dn_key"> 
		<tr>
			<td>{t}PIN{/t} (<span id="pick_assigned_pin_alias"></span>):</td>
			<td> 
			<input id="pick_assigned_password" type="password" />
			</td>
		</tr>
	</table>
	<table class="edit" style="margin-top:10px;float:right">
		<tr>
			<td colspan="2"> 
			<div class="buttons">
				<button class="negative" onclick="close_dialog('pick_assigned_dialog')">{t}Cancel{/t}</button> <button class="positive" onclick="pick_assigned_save()">{t}Go{/t}</button> 
			</div>
			<td> 
		</tr>
	</table>
</div>
<div id="pack_it_dialog" style="width:300px;">
	<div class="options" style="width:300px;padding:10px;text-align:center">
		<table border="0" style="margin:auto" id="pack_it_buttons">
			{foreach from=$packers item=packer_row name=foo} 
			<tr>
				{foreach from=$packer_row key=row_key item=packer } 
				<td staff_id="{$packer.StaffKey}" id="packer_pack_it{$packer.StaffKey}" class="pack_it_button" onclick="select_staff_pack_it(this,event)">{$packer.StaffAlias}</td>
				{/foreach} 
			</tr>
			{/foreach} 
		</table>
	</div>
	<table class="edit">
		<input type="hidden" id="pack_it_staff_key"> 
		<input type="hidden" id="pack_it_dn_key"> 
		<tr id="staff_name_tr" class="first">
			<td class="label">{t}Staff Name{/t}:</td>
			<td style="text-align:left"> 
			<div style="width:190px;position:relative;top:00px">
				<input style="text-align:left;width:180px" id="pack_it_Staff_Name" value="" ovalue="" valid="0"> 
				<div id="pack_it_Staff_Name_Container">
				</div>
			</div>
			</td>
			<td class="pack_it_button" td_id="other_staff_packer" onclick="show_other_staff(this)">{t}Other{/t}</td>
			<td id="pack_it_Staff_Name_msg" class="edit_td_alert"></td>
		</tr>
		<tr id="pack_it_pin_tr" style="visibility:hidden">
			<td><span id="pack_it_pin_alias"></span> {t}PIN{/t}:</td>
			<td> 
			<input id="pack_it_password" type="password" />
			</td>
		</tr>
	</table>
	<table class="edit" style="margin-top:10px;float:right">
		<tr>
			<td colspan="2"> 
			<div class="buttons">
				<button class="positive" onclick="pack_it_save()">{t}Go{/t}</button> <button class="negative" onclick="close_dialog('pack_it_dialog')">{t}Cancel{/t}</button> 
			</div>
			<td> 
		</tr>
	</table>
</div>
<div id="pack_assigned_dialog" style="width:300px;">
	<table class="edit">
		<input type="hidden" id="pack_assigned_staff_key"> 
		<input type="hidden" id="pack_assigned_dn_key"> 
		<tr>
			<td>{t}PIN{/t} (<span id="pack_assigned_pin_alias"></span>):</td>
			<td> 
			<input id="pack_assigned_password" type="password" />
			</td>
		</tr>
	</table>
	<table class="edit" style="margin-top:10px;float:right">
		<tr>
			<td colspan="2"> 
			<div class="buttons">
				<button class="positive" onclick="pack_assigned_save()">{t}Go{/t}</button> <button class="negative" onclick="close_dialog('pack_assigned_dialog')">{t}Cancel{/t}</button> 
			</div>
			<td> 
		</tr>
	</table>
</div>
<div id="dialog_other_staff">
	<input type="hidden" id="staff_list_parent_dialog" value=""> 
	<div class="splinter_cell" style="padding:10px 15px 10px 0;border:none">
		<div id="the_table" class="data_table">
			<span class="clean_table_title">{t}Staff List{/t}</span> {include file='table_splinter.tpl' table_id=2 filter_name=$filter_name2 filter_value=$filter_value2} 
			<div id="table2" class="data_table_container dtable btable">
			</div>
		</div>
	</div>
</div>
