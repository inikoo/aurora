{include file='header.tpl'} 
<div id="bd">
<input type="hidden" id="label_invalid_alias" value="{t}Invalid Staff Alias{/t}">
<input type="hidden" id="label_invalid_name" value="{t}Invalid Staff Name{/t}">
<input type="hidden" id="label_invalid_pin" value="{t}Invalid PIN{/t}">
<input type="hidden" id="staff_position_data" value="{$staff_position_data}">



	<div class="branch">
		<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a> &rarr; <a href="hr.php">{$account_label}</a> &rarr;<a href="staff.php?id={$staff->id}"> {$staff->get('Staff Name')} </a> ({t}Editing{/t})</span> 
	</div>
	<div class="top_page_menu" style="margin-top:10px">
		<div class="buttons" style="float:right">
			<button onclick="window.location='employee.php?id={$staff->id}'"><img src="art/icons/door_out.png" alt=""> {t}Exit Edit{/t}</button> <button style="display:none" onclick="delete_location()"><img src="art/icons/cancel.png" alt=""> {t}Delete Area{/t}</button> 
		</div>
		<div class="buttons" style="float:left">
			<span class="main_title"> {t}Editing Staff{/t}: <span id="title_name">{$staff->get('Staff Name')}</span> </span> 
		</div>
		<div style="clear:both">
		</div>
	</div>
	<input type="hidden" id="staff_key" value="{$staff->id}" />
	<div style="clear:left;margin:0 0px">
	</div>
	<ul class="tabs" id="chooser_ul" style="clear:both">
		<li> <span class="item {if $block=='description'}selected{/if}" id="description"> <span> {t}Employee{/t}</span></span></li>
		<li> <span class="item {if $block=='user'}selected{/if}" id="user"> <span> {t}User{/t}</span></span></li>
	</ul>
	<div class="tabbed_container no_padding">
		<div class="edit_block" id="description_block" style="{if $block!='description'}display:none{/if}">
			<div class="buttons small left tabs">
				<button style="margin-left:30px" class="item first {if $edit_description_block=='id'}selected{/if}" id="description_block_id" block_id="id">{t}Employee Id{/t}</button> 
				<button class="item  {if $edit_description_block=='pin'}selected{/if}" id="description_block_pin" block_id="pin">{t}Employee PIN{/t}</button> 
				<button class="item {if $edit_description_block=='position'}selected{/if}" id="description_block_position" block_id="position">{t}Employment details{/t}</button> 
				<button class="item  {if $edit_description_block=='contact'}selected{/if}" id="description_block_contact" block_id="contact">{t}Contact{/t}</button> 
			</div>
			<div class="tabs_base">
			</div>
			<div class="edit_block_content">
				<div id="d_description_block_id" style="{if $edit_description_block!='id'}display:none{/if}">
					<table style="margin:0; width:100%" class="edit" border="0">
						<tr class="first">
							<td class="label" style="width:200px">{t}Staff Code (Handle){/t}:</td>
							<td style="width:200px"> 
							<div>
								<input id="Staff_Alias" changed="0" type='text' class='text' value="{$staff->get('Staff Alias')}" ovalue="{$staff->get('Staff Alias')}" />
								<div id="Staff_Alias_Container">
								</div>
							</div>
							</td>
							<td id="Staff_Alias_msg" class="edit_td_alert"></td>
						</tr>
						<tr class="buttons">
							<td colspan="2"> 
							<div class="buttons">
								<button style="margin-right:10px;" id="save_edit_staff_description" class="positive disabled">{t}Save{/t}</button> <button style="margin-right:10px;" id="reset_edit_staff_description" class="negative">{t}Reset{/t}</button> 
							</div>
							</td>
						</tr>
					</table>
				</div>
				<div id="d_description_block_contact" style="{if $edit_description_block!='contact'}display:none{/if}">
					<table style="margin:0; width:100%" class="edit" border="0">
						<tr class="first">
							<td class="label" style="width:200px">{t}Staff Name{/t}:</td>
							<td style="width:250px"> 
							<div>
								<input id="Staff_Name" changed="0" type='text' class='text' value="{$staff->get('Staff Name')}" ovalue="{$staff->get('Staff Name')}" />
								<div id="Staff_Name_Container">
								</div>
							</div>
							</td>
							<td  id="Staff_Name_msg" class="edit_td_alert"></td>
						</tr>
				
						<tr class="buttons">
							<td colspan="2"> 
							<div class="buttons">
								<button style="margin-right:10px;" id="save_edit_staff_contact" class="positive disabled">{t}Save{/t}</button> <button style="margin-right:10px;" id="reset_edit_staff_contact" class="negative">{t}Reset{/t}</button> 
							</div>
							</td>
						</tr>
					</table>
				</div>
				
				<div id="d_description_block_pin" style="{if $edit_description_block!='pin'}display:none{/if}">
					
						<table border=0 class="edit">
						
						<tr class="title">
							<td colspan=2>
							{t}Change PIN{/t}
														
							</td>
							
							</tr>
						
							<tr >
								<td style="width:200px" class="label">{t}Staff PIN{/t}:</td>
								<td style="width:300px"> 
								<div>
									<input id="Staff_PIN" changed="0" type='password' class='text' style="width:100px" maxlength="4" value="" ovalue="" />
									<div id="Staff_PIN_Container">
									</div>
								</div>
								</td>
								<td style="width:200px" id="Staff_PIN_msg" class="edit_td_alert"></td>
							</tr>
							<tr >
								<td class="label">{t}Confirm PIN{/t}:</td>
								<td> 
								<div>
									<input id="Staff_PIN_Confirm" changed="0" type='password' class='text' style="width:100px" maxlength="4" value="" ovalue="" />
									<div id="Staff_PIN_Confirm_Container">
									</div>
								</div>
								</td>
								<td style="width:200px" id="Staff_PIN_Confirm_msg" class="edit_td_alert"></td>
							</tr>
							<tr class="buttons">
							<td colspan=2>
							
								<div class="buttons">
								<button style="margin-right:10px;" id="save_edit_staff_pin" class="positive disabled">{t}Save{/t}</button> <button style="margin-right:10px;" id="reset_edit_staff_pin" class="negative disabled">{t}Cancel{/t}</button> 
							</div>
							
							</td>
							
							</tr>
							
						</table>
					
				</div>
				
				
				<div id="d_description_block_position" style="{if $edit_description_block!='position'}display:none{/if}">
					<table style="margin:0; width:100%" class="edit" border="0">
					
						<tr>
							<td class="label" style="width:150px">{t}Employes is working{/t}:</td>
							<td class="label" style="width:700px">
							<input type="hidden" id="Staff_Currently_Working" value="{$staff->get('Staff Currently Working')}" ovalue="{$staff->get('Staff Currently Working')}">
							<div class="buttons small left" style="float:left" id="Staff_Currently_Working_options">
								<button class="{if $staff->get('Staff Currently Working')=='Yes'}selected{/if} option" onclick="change_is_working('Yes')" id="Staff_Currently_Working_Yes">{t}Yes{/t}</button> <button class="{if $staff->get('Staff_Currently_Working')=='No'}selected{/if} option" onclick="change_is_working('No')" id="Staff_Currently_Working_No">{t}No{/t}</button> 
							</div>
							</td>
							<td><span id="Staff_Currently_Working_msg"></span></td>
						</tr>
					
						<tr class="space10">
							<td class="label">{t}Staff Type{/t}:</td>
							<td>
							<input type="hidden" id="Staff_Type" value="{$staff->get('Staff Type')}" ovalue="{$staff->get('Staff Type')}">

							<div class="buttons small left" style="float:left" class="options" id="Staff_Type_options">
								<button style="margin-bottom:5px;min-width:100px" class="{if $staff->get('Staff Type')=='Employee'}selected{/if} option" onclick="change_employee_type('Employee')" id="Staff_Type_Employee">{t}Employee{/t}</button> 
								<button style="margin-bottom:5px;min-width:100px" class="{if $staff->get('Staff Type')=='Volunteer'}selected{/if} option" onclick="change_employee_type('Volunteer')" id="Staff_Type_Volunteer">{t}Volunteer{/t}</button> 
								<button style="margin-bottom:5px;min-width:100px" class="{if $staff->get('Staff Type')=='Contractor'}selected{/if} option" onclick="change_employee_type('Contractor')" id="Staff_Type_Contractor">{t}Contractor{/t}</button> 
								<button style="margin-bottom:5px;min-width:100px" class="{if $staff->get('Staff Type')=='Temporal Worker'}selected{/if} option" onclick="change_employee_type('Temporal Worker')" id="Staff_Type_Temporal Worker">{t}Temporal Worker{/t}</button> 
								<button style="margin-bottom:5px;min-width:100px" class="{if $staff->get('Staff Type')=='Work Experience'}selected{/if} option" onclick="change_employee_type('Work Experience')" id="Staff_Type_Work Experience">{t}Work Experience{/t}</button> 
							</div>
							</td>
														<td><span id="Staff_Type_msg"></span></td>

						</tr>
						<tr class="space10">
							<td class="label"> 
							
								{t}Staff Position{/t}: 
							
							</td>
							<td> 
							    <input type="hidden" id="Staff_Position" value="{$staff_position_data}" ovalue="{$staff_position_data}">

								<div class="buttons small left">
								{foreach from=$staff_position item=item key=key } 
								<button style="margin-bottom:5px;min-width:120px"  class="{if $item.selected>0}selected{/if} option" id="position_{$key}" onclick="change_employee_position('{$key}')"  >{$item.label}</button>
								{/foreach} 
								</div>
							</td>
							<td><span id="Staff_Position_msg"></span></td>

						</tr>
						
						<tr class="buttons">
							<td></td>
							<td colspan="2"> 
							<div class="buttons left">
								<button style="margin-right:10px;" id="reset_edit_staff_employment" class="negative disabled">{t}Reset{/t}</button> 

								<button style="margin-right:10px;" id="save_edit_staff_employment" class="positive disabled">{t}Save{/t}</button> 
							</div>
							</td>
						</tr>
					</table>
				</div>
			</div>
		</div>
		<div class="edit_block" id="user_block" style="{if $block!='user'}display:none{/if}">
			<div class="buttons small left tabs">
				<button style="margin-left:30px" class="item first {if $edit_description_block=='id'}selected{/if}" id="description_block_id" block_id="id">{t}User Info{/t}</button> <button class="item  {if $edit_description_block=='contact'}selected{/if}" id="description_block_contact" block_id="contact">{t}Permisions{/t}</button> 
			</div>
			<div class="tabs_base">
				
			</div>
		</div>
	</div>
</div>
{include file='footer.tpl'} 