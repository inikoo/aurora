{include file='header.tpl'} 
<div id="bd">
	{include file='users_navigation.tpl'} 
	<div class="branch">
		<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home"/></a>&rarr; <a href="users.php">{t}Users{/t}</a> &rarr; <a href="users_staff.php">{t}Staff Users{/t}</a> &rarr; {t}Editing Staff Users{/t}</span> 
	</div>
	<div class="top_page_menu">
		<div class="buttons" style="float:right">
					<button onclick="window.location='users_staff.php'"><img src="art/icons/door_out.png" alt=""> {t}Exit Edit{/t}</button> 

		</div>
		<div class="buttons" style="float:left">
		</div>
		<div style="clear:both">
		</div>
	</div>
	<ul class="tabs" id="chooser_ul" style="clear:both">
		<li> <span class="item selected" id="users"> <span> {t}Users{/t}</span></span></li>
	</ul>
	<div class="tabbed_container">
		<span class="clean_table_title">{t}Staff Users{/t}</span> 
		<div style="font-size:90%" id="transaction_chooser">
			<span style="float:right;margin-left:20px;" class=" table_type transaction_type state_details {if $elements.InactiveNotWorking}selected{/if} label_page_type" id="elements_InactiveNotWorking">{t}Inactive Not Working{/t} (<span id="elements_InactiveNotWorking_number">{$elements_number.InactiveNotWorking}</span>)</span> <span style="float:right;margin-left:20px;" class=" table_type transaction_type state_details {if $elements.InactiveWorking}selected{/if} label_page_type" id="elements_InactiveWorking">{t}Inactive Working{/t} (<span id="elements_InactiveWorking_number">{$elements_number.InactiveWorking}</span>)</span> <span style="float:right;margin-left:20px;" class=" table_type transaction_type state_details {if $elements.ActiveNotWorking}selected{/if} label_page_type" id="elements_ActiveNotWorking">{t}Active Not Working{/t} (<span id="elements_ActiveNotWorking_number">{$elements_number.ActiveNotWorking}</span>)</span> <span style="float:right;margin-left:20px;" class=" table_type transaction_type state_details {if $elements.ActiveWorking}selected{/if} label_page_type" id="elements_ActiveWorking">{t}Active Working{/t} (<span id="elements_ActiveWorking_number">{$elements_number.ActiveWorking}</span>)</span> 
		</div>
		<div class="table_top_bar" style="margin-bottom:15px">
		</div>
		{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0 } 
		<div id="table0" class="data_table_container dtable btable" style="font-size:85%">
		</div>
	</div>
</div>
{include file='footer.tpl'} 
<div id="change_staff_password" style="width:400px;padding:10px 20px 10px 20px;font-size:90%">
	<div class="bd">
		<table class="edit" border="0" style="width:100%">
			<tr class="title">
				<td colspan="2">{t}Change Password for{/t}: <span user_id='' id="change_staff_password_alias"></span></td>
			</tr>
			
			<tr style="height:30px;border-top:1px solid #777">
				<td colspan="2" id="change_staff_password_meter" style="padding:0 40px 8px 40px;;vertical-align:bottom"> 
				<div style="float:right;" id="change_staff_password_meter_str">
				</div>
				<div id="change_staff_password_meter_bar" style="visibility:hidden;;height:12px;border:1px solid #555; background:#bd0e00;width:0%;font-size:10px;text-align:left;">
					&nbsp; 
				</div>
				</td>
			</tr>
			<tbody id="change_staff_auto_dialog" style="display:none">
				<tr style="height:50px" class="bottom">
					<td>{t}Password{/t}:</td>
					<td style="text-align:left"><span style="font-weight:800" id="change_staff_passwd"></span> <img id="change_staff_user_defined_pwd_but"  onclick="user_defined_pwd('change_staff')"   style="margin-left:10px;cursor:pointer" src="art/icons/delete.gif" alt="{t}Close{/t}"/></td>
				</tr>
			</tbody>
			<tbody id="change_staff_user_defined_dialog">
				<tr style="height:10px">
					<td>{t}Password{/t}:</td>
					<td style="text-align:left"> 
					<input onkeyup="change_meter(this.value,'change_staff')" style="width:100%" type="password" id="change_staff_passwd1" value="" />
					</td>
				</tr>
				<tr style="height:30px" id="change_staff_repeat_password" class="bottom">
					<td style="vertical-align:top;text-align:left">{t}Repeat Password{/t}:<img id="change_staff_error_passwd2" style="visibility:hidden" src="art/icons/exclamation.png" alt="!" /></td>
					<td style="text-align:left"> 
					<input onkeyup="match_passwd(this.value,'change_staff_passwd1','change_staff')" style="width:100%" type="password" id="change_staff_passwd2" value="" />
					</td>
				</tr>
			</tbody>
			<tr style="height:20px">
			<td colspan=2>
			</td>
			</tr>
			<tr>
			<td colspan=2>
			<div class="buttons">
			<button onclick="change_staff_pwd()" id="change_staff_save" class="positive disabled" >{t}Save{/t}</button>
					<button id="change_staff_auto_pwd_but" onclick="auto_pwd('change_staff')">{t}Random Password{/t}</button> 

						<button id="change_staff_cancel" style="margin-left:30px" class="negative" onclick="close_change_password_dialog()">{t}Cancel{/t}</button>



</div>
			</td>
							</tr>
		</table>
	</div>
</div>
