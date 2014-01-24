<div id="assign_picker_dialog" style="width:400px;padding:20px 10px 0px 10px;display:none">
	<input type="hidden" id="assign_picker_dialog_type" value="assign_picker"> 
	<table class="edit" border="0" style="width:100%">
		<input type="hidden" id="assign_picker_staff_key"> 
		<input type="hidden" id="assign_picker_dn_key"> 
		<tr class="title">
			<td colspan="2"> {t}Assign picker for later{/t} </td>
		</tr>
		<tr>
			<td colspan="2"> 
			<div class="options" style="width:350px;padding:0 10px;text-align:center">
				<table border="0" style="margin:auto" id="assign_picker_buttons">
					{if $number_pickers==0} 
					<tr>
						<td onclick="show_other_staff(this)" id="picker_show_other_staff" td_id="other_staff_pack_it" class="assign_picker_button other" onclick="show_other_staff(this)">{t}Select Picker{/t}</td>
					</tr>
					{else} {foreach from=$pickers item=picker_row name=foo} 
					<tr>
						{foreach from=$picker_row key=row_key item=picker } 
						<td staff_id="{$picker.StaffKey}" id="picker{$picker.StaffKey}" scope="picker" class="assign_picker_button" onclick="select_staff(this,event)">{$picker.StaffAlias}</td>
						{/foreach} 
						<td onclick="show_other_staff(this)" id="picker_show_other_staff" td_id="other_staff_picker" class="assign_picker_button other" onclick="show_other_staff(this)">{t}Other{/t}</td>
					</tr>
					{/foreach} {/if} 
				</table>
			</div>
			</td>
		</tr>
		<tr style="display:none" id="Assign_Picker_Staff_Name_tr">
			<td class="label">{t}Staff Name{/t}:</td>
			<td style="text-align:left"> 
			<div>
				<input style="text-align:left;width:180px" id="Assign_Picker_Staff_Name" value="" ovalue="" valid="0"> 
				<div id="Assign_Picker_Staff_Name_Container">
				</div>
			</div>
			</td>
		</tr>
		<tr id="assign_picker_supervisor_password" style="display:none">
			<td class="label">{t}Supervisor PIN{/t}:</td>
			<td> 
			<input id="assign_picker_sup_password" type="password" />
			</td>
		</tr>
		<tr>
			<td></td>
			<td colspan="2" id="pick_it_msg" class="edit_td_alert"></td>
			<tr>
				<td colspan="2"> 
				<div class="buttons">
					<button class="positive" onclick="assign_picker_save()">{t}Go{/t}</button> <button class="negative" onclick="close_dialog('assign_picker_dialog')">{t}Cancel{/t}</button> 
				</div>
				<td> 
			</tr>
		</tr>
	</table>
</div>
<div id="assign_packer_dialog" style="width:400px;padding:20px 10px 0px 10px;display:none">
	<input type="hidden" id="assign_packer_dialog_type" value="assign_packer"> 
	<table class="edit" border="0" style="width:100%">
		<input type="hidden" id="assign_packer_staff_key"> 
		<input type="hidden" id="assign_packer_dn_key"> 
		<tr class="title">
			<td colspan="2"> {t}Assign packer for later{/t} </td>
		</tr>
		<tr>
			<td colspan="2"> 
			<div class="options" style="width:350px;padding:0 10px;text-align:center">
				<table border="0" style="margin:auto" id="assign_packer_buttons">
					{if $number_packers==0} 
					<tr>
						<td onclick="show_other_staff(this)" id="packer_show_other_staff" td_id="other_staff_pack_it" class="assign_packer_button other" onclick="show_other_staff(this)">{t}Select Packer{/t}</td>
					</tr>
					{else} {foreach from=$packers item=packer_row name=foo} 
					<tr>
						{foreach from=$packer_row key=row_key item=packer } 
						<td staff_id="{$packer.StaffKey}" id="packer{$packer.StaffKey}" scope="packer" class="assign_packer_button" onclick="select_staff(this,event)">{$packer.StaffAlias}</td>
						{/foreach} 
						<td onclick="show_other_staff(this)" id="packer_show_other_staff" td_id="other_staff_packer" class="assign_packer_button other" onclick="show_other_staff(this)">{t}Other{/t}</td>
					</tr>
					{/foreach} {/if} 
				</table>
			</div>
			</td>
		</tr>
		<tr style="display:none" id="Assign_Packer_Staff_Name_tr">
			<td class="label">{t}Staff Name{/t}:</td>
			<td style="text-align:left"> 
			<div>
				<input style="text-align:left;width:180px" id="Assign_Packer_Staff_Name" value="" ovalue="" valid="0"> 
				<div id="Assign_Packer_Staff_Name_Container">
				</div>
			</div>
			</td>
		</tr>
		<tr id="assign_packer_supervisor_password" style="display:none">
			<td class="label">{t}Supervisor PIN{/t}:</td>
			<td> 
			<input id="assign_packer_sup_password" type="password" />
			</td>
		</tr>
		<tr>
			<td></td>
			<td colspan="2" id="pick_it_msg" class="edit_td_alert"></td>
			<tr>
				<td colspan="2"> 
				<div class="buttons">
					<button class="positive" onclick="assign_packer_save()">{t}Go{/t}</button> <button class="negative" onclick="close_dialog('assign_packer_dialog')">{t}Cancel{/t}</button> 
				</div>
				<td> 
			</tr>
		</tr>
	</table>
</div>
<div id="pick_it_dialog" style="width:400px;padding:20px 0px 0px 0px;display:none">
	<table class="edit" border="0">
		<tr>
			<td colspan="2"> 
			<div class="options" style="width:350px;padding:0 10px;text-align:center">
				<table border="0" style="margin:auto" id="pick_it_buttons">
					{if $number_pickers==0} 
					<tr>
						<td onclick="show_other_staff(this)" id="picker_show_other_staff" td_id="other_staff_pick_it" class="assign_picker_button other" onclick="show_other_staff(this)">{t}Select Picker{/t}</td>
					</tr>
					{else} {foreach from=$pickers item=picker_row name=foo} 
					<tr>
						{foreach from=$picker_row key=row_key item=picker } 
						<td staff_id="{$picker.StaffKey}" id="picker{$picker.StaffKey}" scope="pick_it" class="assign_picker_button" onclick="select_staff(this,event)">{$picker.StaffAlias}</td>
						{/foreach} 
						<td onclick="show_other_staff(this)" id="picker_show_other_staff" td_id="other_staff_picker" class="assign_picker_button other" onclick="show_other_staff(this)">{t}Other{/t}</td>
					</tr>
					{/foreach} {/if} 
				</table>
			</div>
			</td>
		</tr>
		<input type="hidden" id="pick_it_staff_key"> 
		<input type="hidden" id="pick_it_dn_key"> 
		<tr id="pick_it_Staff_Name_tr" style="display:none">
			<td class="label" style="width:100px">{t}Picker{/t}:</td>
			<td style="text-align:left;width:220px"> 
			<input type="hidden" id="pick_it_Staff_Name" value="" ovalue="" valid="0"> <span id="pick_it_Staff_Name_label"></span> </td>
		</tr>
		<tr id="pick_it_pin_tr" style="display:none">
			<td class="label">{t}Supervisor PIN{/t}:</td>
			<td> 
			<input id="pick_it_pin" type="password" />
			</td>
		</tr>
		<tr id="pick_it_Staff_Name_msg_tr" style="display:none">
			<td colspan="2" id="pick_it_Staff_Name_msg" class="edit_td_alert"></td>
		</tr>
		<tr>
			<td colspan="3"> 
			<div class="buttons">
				<button class="positive disabled" id="pick_it_save" onclick="pick_it_save()">{t}Go{/t}</button> <button class="negative" onclick="close_dialog('pick_it_dialog')">{t}Cancel{/t}</button> 
			</div>
			<td> 
		</tr>
	</table>
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
<div id="pack_it_dialog" style="width:400px;padding:20px 0px 0px 0px;display:none">
	<table class="edit" border="0">
		<tr>
			<td colspan="2"> 
			<div class="options" style="width:350px;padding:0 10px;text-align:center">
				<table border="0" style="margin:auto" id="pack_it_buttons">
					{if $number_packers==0} 
					<tr>
						<td onclick="show_other_staff(this)" id="packer_show_other_staff" td_id="other_staff_pack_it" class="pack_it_button other" onclick="show_other_staff(this)">{t}Select Packer{/t}</td>
					</tr>
					{else} {foreach from=$packers item=packer_row name=foo} 
					<tr>
						{foreach from=$packer_row key=row_key item=packer } 
						<td staff_id="{$packer.StaffKey}" id="packer{$packer.StaffKey}" scope="pack_it" class="pack_it_button" onclick="select_staff(this,event)">{$packer.StaffAlias}</td>
						{/foreach} 
						<td onclick="show_other_staff(this)" id="packer_show_other_staff" td_id="other_staff_packer" class="assign_packer_button other" onclick="show_other_staff(this)">{t}Other{/t}</td>
					</tr>
					{/foreach} {/if} 
				</table>
			</div>
			</td>
		</tr>
		<input type="hidden" id="pack_it_staff_key"> 
		<input type="hidden" id="pack_it_dn_key"> 
		<tr id="pack_it_Staff_Name_tr" style="display:none">
			<td class="label" style="width:100px">{t}Packer{/t}:</td>
			<td style="text-align:left;width:220px"> 
			<input type="hidden" id="pack_it_Staff_Name" value="" ovalue="" valid="0"> <span id="pack_it_Staff_Name_label"></span> </td>
		</tr>
		<tr id="pack_it_pin_tr" style="display:none">
			<td class="label">{t}Supervisor PIN{/t}:</td>
			<td> 
			<input id="pack_it_sup_password" type="password" />
			</td>
		</tr>
		<tr id="pack_it_Staff_Name_msg_tr" style="display:none">
			<td colspan="2" id="pack_it_Staff_Name_msg" class="edit_td_alert"></td>
		</tr>
		<tr>
			<td colspan="3"> 
			<div class="buttons">
				<button class="positive disabled" id="pack_it_save" onclick="pack_it_save()">{t}Go{/t}</button> <button class="negative" onclick="close_dialog('pack_it_dialog')">{t}Cancel{/t}</button> 
			</div>
			<td> 
		</tr>
	</table>
</div>
<div id="pack_assigned_dialog" style="width:300px;;display:none">
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
		<div class="buttons small left" style="magin-bottom:15px">
			<button style="margin:0;padding:0;" label="{t}Unknown/Other{/t}" onclick="select_unknown_staff(this)">{t}Unknown/Other{/t}</button> 
		</div>
		<div style="clear:both;margin-top:0px;height:5px">
		</div>
		<div id="the_table" class="data_table" style="clear:both;margin-top:10px">
			<span class="clean_table_title">{t}Staff List{/t}</span> {include file='table_splinter.tpl' table_id=2 filter_name=$filter_name2 filter_value=$filter_value2} 
			<div id="table2" class="data_table_container dtable btable">
			</div>
		</div>
	</div>
</div>
