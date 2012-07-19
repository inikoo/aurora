{include file='header.tpl'} 
<div id="bd">
	<div class="branch">
		<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a> &rarr; <a href="hr.php">{t}Staff{/t} </a> &rarr; {t}New Staff{/t} </span> 
	</div>
	<div id="top_page_menu" class="top_page_menu" style="margin-top:10px">
		<div class="buttons" style="float:left">
			<span class="main_title">{t}New Staff{/t}</span> 
		</div>
		<div class="buttons" style="float:right">
			<button class="negative" onclick="window.location='{$link_back}'"><img src="art/icons/vcard_edit.png" alt=""> {t}Cancel{/t}</button> 
		</div>
		<div style="clear:both">
		</div>
	</div>
	<div class="search_box">
	</div>
	<div id="contact_messages_div">
		<span id="contact_messages"></span> 
	</div>
	<div>
		<div id="results" style="margin-top:0px;float:right;width:600px;">
		</div>
		<div style="float:left;width:900px;">
			<table class="edit" border="0" style="width:100%;margin-bottom:0px;margin-top:10px">
				<tr class="title" style="display:none">
					<td colspan="3">{t}Staff Info{/t}</td>
				</tr>
				<tr>
					<td style="width:150px" class="label">{t}Staff Code (Handle){/t}:</td>
					<td style="width:300px"> 
					<div>
						<input style="width:150px" id="Staff_Alias" changed="0" type='text' maxlength="255" class='text' value="" />
						<div id="Staff_Alias_Container">
						</div>
					</div>
					</td>
					<td style="width:250px" id="Staff_Alias_msg" class="edit_td_alert"></td>
				</tr>
				<tr>
					<td style="width:150px" class="label">{t}Staff Name{/t}:</td>
					<td > 
					<div>
						<input style="width:100%" id="Staff_Name" changed="0" type='text' maxlength="255" class='text' value="" />
						<div id="Staff_Name_Container">
						</div>
					</div>
					</td>
					<td id="Staff_Name_msg" class="edit_td_alert"></td>
				</tr>
				<tr style="display:none">
					<td class="label">{t}Staff Working{/t}:</td>
					<td> 
					<div class="buttons small left" id="staff_working" value="Yes" ovalue="Yes" prefix="staff_working_" class="options" style="margin:5px 0">
						<button class="positive" name="1" onclick="radio_changed(this)" id="staff_working_1">{t}Yes{/t}</button> <button class="negative" name="2" onclick="radio_changed(this)" id="staff_working_2">{t}No{/t}</button> 
					</div>
					</td>
				</tr>

				<tr>
					<td class="label">{t}Staff Type{/t}:</td>
					<td colspan="2"> 
					<div class="buttons small left" id="staff_type" value="" ovalue="" prefix="staff_type_" class="options" style="margin:5px 0">
						<button name="Employee" class="selected" onclick="radio_changed_staff(this, 'staff_type')" id="staff_type_Employee">{t}Employee{/t}</button> <button name="Temporal Worker" class="" onclick="radio_changed_staff(this, 'staff_type')" id="staff_type_Temporal Worker">{t}Temporal Worker{/t}</button> <button name="Volunteer" class="" onclick="radio_changed_staff(this, 'staff_type')" id="staff_type_Volunteer">{t}Volunteer{/t}</button> <button name="Contractor" class="" onclick="radio_changed_staff(this, 'staff_type')" id="staff_type_Contractor">{t}Contractor{/t}</button> <button name="Work Experience" class="" onclick="radio_changed_staff(this, 'staff_type')" id="staff_type_Work Experience">{t}Work Experience{/t}</button> 
					</div>
					</td>
				</tr>
				<tr>
					<td class="label">
					<div>
						{t}Staff Position{/t}:
					</div>
					</td>
					<td> 
					<select id="staff_position">
						{foreach from=$staff_position item=item key=key } 
						<option value="{$key}">{$item}</option>
						{/foreach} 
					</select>
					</td>
				</tr>
				<tr>
					<td class="label">
					<div>
						{t}Staff Department{/t}:
					</div>
					</td>
					<td> 
					<select id="staff_department">
						{foreach from=$staff_department item=item key=key } 
						<option value="{$key}">{$item}</option>
						{/foreach} 
					</select>
					</td>
				</tr>
				<tr>
					<td class="label">
					<div>
						{t}Staff Area{/t}:
					</div>
					</td>
					<td> 
					<select id="staff_area">
						{foreach from=$staff_area item=item key=key } 
						<option value="{$key}">{$item}</option>
						{/foreach} 
					</select>
					</td>
				</tr>
				<tr>
					<td class="label">{t}Staff Supervisor{/t}:</td>
					<td> 
					<div class="buttons small left" id="staff_supervisor" value="No" ovalue="No" prefix="staff_supervisor_" class="options" style="margin:5px 0">
						<button name="Yes" class="positive" onclick="radio_changed_staff(this, 'staff_supervisor')" id="staff_supervisor_Yes">{t}Yes{/t}</button> <button name="No" selected='selected' class="negative selected" onclick="radio_changed_staff(this, 'staff_supervisor')" id="staff_supervisor_No">{t}No{/t}</button> 
					</div>
					</td>
					<td id="staff_supervisor_msg" class="edit_td_alert"></td>
				</tr>
				
				<tr>
					<td></td>
					<td style="text-align:right">
					<span style="display:none" id="waiting"><img src='art/loading.gif' alt=''> {t}Processing Request{/t}</span>
					<div id="form_buttons" class="buttons">
						<button style="margin-right:10px;visibility:" id="save_new_staff" class="positive disabled">{t}Save{/t}</button> <button style="margin-right:10px;visibility:" id="reset_new_staff" onclick="window.location='{$link_back}'" class="negative">{t}Cancel{/t}</button> 
					</div>
					</td>
					<td></td>
				</tr>
			</table>
		</div>
		<div style="clear:both;height:40px">
		</div>
	</div>
</div>

</div>
{include file='footer.tpl'}