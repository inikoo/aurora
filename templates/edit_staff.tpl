{include file='header.tpl'} 
<div id="bd">
	<div class="branch">
		<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home"/></a>  &rarr; <a href="hr.php">{t}Staff{/t} </a> &rarr;<a href="staff.php?id={$staff->id}"> {$staff->get('Staff Name')} </a> &rarr; {t}Editing{/t}</span> 
	</div>
	<div class="top_page_menu" style="margin-top:10px">
		<div class="buttons" style="float:right">
			<button onclick="window.location='staff.php?id={$staff->id}'"><img src="art/icons/door_out.png" alt=""> {t}Exit Edit{/t}</button> <button style="display:none" onclick="delete_location()"><img src="art/icons/cancel.png" alt=""> {t}Delete Area{/t}</button> 
		</div>
		<div class="buttons" style="float:left">
		<span class="main_title">
			{t}Editing Staff{/t}: <span id="title_name">{$staff->get('Staff Name')}</span>
		</span>
		</div>
		<div style="clear:both">
		</div>
	</div>
	<input type="hidden" id="staff_key" value="{$staff->id}" />
	<div style="clear:left;margin:0 0px">
		
	</div>
	<ul class="tabs" id="chooser_ul" style="clear:both">
		<li> <span class="item {if $edit=='description'}selected{/if}" id="description"> <span> {t}Description{/t}</span></span></li>
		<li> <span class="item {if $edit=='pin'}selected{/if}" id="pin"> <span> {t}PIN{/t}</span></span></li>
	</ul>
	<div class="tabbed_container">
		<div id="description_block" style="{if $edit!='description'}display:none{/if}">
			<table style="margin:0; width:100%" class="edit" border="0">
				
				<tr class="title">
					<td colspan="5">{t}Details{/t}</td>
				</tr>
				<div class="buttons">
					<button style="margin-right:10px;visibility:hidden" id="save_edit_staff_description" class="positive">{t}Save{/t}</button> <button style="margin-right:10px;visibility:hidden" id="reset_edit_staff_description" class="negative">{t}Reset{/t}</button> 
				</div>
				<tr class="first">
					<td class="label">{t}Staff Alias{/t}:</td>
					<td> 
					<div>
						<input id="Staff_Alias" changed="0" type='text' class='text' value="{$staff->get('Staff Alias')}" ovalue="{$staff->get('Staff Alias')}" />
						<div id="Staff_Alias_Container">
						</div>
					</div>
					</td>
					<td style="width:200px" id="Staff_Alias_msg" class="edit_td_alert"></td>
				</tr>
				<tr class="first">
					<td class="label">{t}Staff Name{/t}:</td>
					<td> 
					<div>
						<input id="Staff_Name" changed="0" type='text' class='text' value="{$staff->get('Staff Name')}" ovalue="{$staff->get('Staff Name')}" />
						<div id="Staff_Name_Container">
						</div>
					</div>
					</td>
					<td style="width:200px" id="Staff_Name_msg" class="edit_td_alert"></td>
				</tr>
				<tr>
					<td class="label" style="width:200px">{t}Staff Working{/t}:</td>
					<td> 
					<div class="buttons small left" style="float:left">
						<button class="{if $staff->get('Staff Currently Working')=='Yes'}selected{/if} positive" onclick="save_staff_status('Staff Currently Working','Yes')" id="Staff Currently Working_Yes">{t}Yes{/t}</button> <button class="{if $staff->get('Staff Currently Working')=='No'}selected{/if} negative" onclick="save_staff_status('Staff Currently Working','No')" id="Staff Currently Working_No">{t}No{/t}</button> 
					</div>
					</td>
				</tr>
				<tr>
					<td class="label" style="width:200px">{t}Staff Supervisor{/t}:</td>
					<td> 
					<div class="buttons small left" style="float:left">
						<button class="{if $staff->get('Staff Is Supervisor')=='Yes'}selected{/if} positive" onclick="save_staff_status('Staff Is Supervisor','Yes')" id="Staff Is Supervisor_Yes">{t}Yes{/t}</button> <button class="{if $staff->get('Staff Is Supervisor')=='No'}selected{/if} negative" onclick="save_staff_status('Staff Is Supervisor','No')" id="Staff Is Supervisor_No">{t}No{/t}</button> 
					</div>
					</td>
				</tr>
				<tr>
					<td class="label" style="width:200px">{t}Staff Type{/t}:</td>
					<td> 
					<div class="buttons small left" style="float:left" class="options">
						<button class="{if $staff->get('Staff Type')=='Employee'}selected{/if} " onclick="save_staff_status('Staff Type','Employee')" id="Staff Type_Employee">{t}Employee{/t}</button> <button class="{if $staff->get('Staff Type')=='Volunteer'}selected{/if} " onclick="save_staff_status('Staff Type','Volunteer')" id="Staff Type_Volunteer">{t}Volunteer{/t}</button> <button class="{if $staff->get('Staff Type')=='Contractor'}selected{/if} " onclick="save_staff_status('Staff Type','Contractor')" id="Staff Type_Contractor">{t}Contractor{/t}</button> <button class="{if $staff->get('Staff Type')=='Temporal Worker'}selected{/if} " onclick="save_staff_status('Staff Type','Temporal Worker')" id="Staff Type_Temporal Worker">{t}Temporal Worker{/t}</button> <button class="{if $staff->get('Staff Type')=='Work Experience'}selected{/if} " onclick="save_staff_status('Staff Type','Work Experience')" id="Staff Type_Work Experience">{t}Work Experience{/t}</button> 
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
					<select id="staff_position_" onchange="save_position(this)">
						{foreach from=$staff_position item=item key=key } 
						<option {if $key="=$staff_position_key" }selected="selected" {/if} value="{$key}">{$item}</option>
						{/foreach} 
					</select>
					</td>
				</tr>
			</table>
		</div>
		<div id="pin_block" style="{if $edit!='pin'}display:none{/if}">
			<table>
				<div class="buttons">
					<button style="margin-right:10px;visibility:hidden" id="save_edit_staff_pin" class="positive">{t}Save{/t}</button> <button style="margin-right:10px;visibility:hidden" id="reset_edit_staff_pin" class="negative">{t}Reset{/t}</button> 
				</div>
				<tr class="first">
					<td class="label">{t}Staff PIN{/t}:</td>
					<td> 
					<div>
						<input id="Staff_PIN" changed="0" type='password' class='text' style="width:100px" maxlength="16" value="{$staff->get('Staff PIN')}" ovalue="{$staff->get('Staff PIN')}" />
						<div id="Staff_PIN_Container">
						</div>
					</div>
					</td>
					<td style="width:200px" id="Staff_PIN_msg" class="edit_td_alert"></td>
				</tr>
				<tr class="first">
					<td class="label">{t}Confirm PIN{/t}:</td>
					<td> 
					<div>
						<input id="Staff_PIN_Confirm" changed="0" type='password' class='text' style="width:100px" maxlength="16" value="" ovalue="" />
						<div id="Staff_PIN_Confirm_Container">
						</div>
					</div>
					</td>
					<td style="width:200px" id="Staff_PIN_Confirm_msg" class="edit_td_alert"></td>
				</tr>
			</table>
		</div>
	</div>
</div>
{include file='footer.tpl'} 