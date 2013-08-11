{include file='header.tpl'} 
<input type="hidden" id="users_view" value="{$users_view}">
<div id="bd" class="no_padding">
	<div style="padding:0 20px">
		{include file='users_navigation.tpl'} 
		<div class="branch">
			<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a>&rarr; <a href="users.php">{t}Users{/t}</a> &rarr; {t}Staff Users{/t} </span> 
		</div>
		<div class="top_page_menu">
			<div class="buttons" style="float:right">
				{if $modify} <button onclick="window.location='edit_users_staff.php'"><img src="art/icons/vcard_edit.png" alt=""> {t}Edit Users{/t}</button> {/if} 
			</div>
			<div class="buttons" style="float:left">
				<span class="main_title">{t}Staff Users{/t}</span> 
			</div>
			<div style="clear:both">
			</div>
		</div>
	</div>
	<ul class="tabs" id="chooser_ul" style="clear:both;margin-top:10px">
		<li> <span class="item {if $block_view=='users'}selected{/if}" id="users"> <span> {t}Users List{/t}</span></span></li>
		<li> <span class="item {if $block_view=='groups'}selected{/if}" id="groups"> <span> {t}Groups{/t}</span></span></li>
		<li> <span class="item {if $block_view=='login_history'}selected{/if}" id="login_history"> <span> {t}Login History{/t}</span></span></li>
	</ul>
	<div style="clear:both;width:100%;border-bottom:1px solid #ccc;">
	</div>
	<div style="padding:0 20px;clear:both">
		<div id="block_users" style="{if $block_view!='users'}display:none;{/if}clear:both;margin:10px 0 40px 0">
			<span class="clean_table_title">{t}Users List{/t}</span> 
			<div style="font-size:90%" id="transaction_chooser">
				<span style="float:right;margin-left:20px;" class=" table_type transaction_type state_details {if $elements.NotWorking}selected{/if} label_page_type" id="elements_NotWorking">{t}Not Working{/t} (<span id="elements_NotWorking_number"><img src="art/loading.gif" style="height:12px;position:relative;bottom:1px" /></span>)</span> 
				<span style="float:right;margin-left:20px;" class=" table_type transaction_type state_details {if $elements.Working}selected{/if} label_page_type" id="elements_Working">{t}Working{/t} (<span id="elements_Working_number"><img src="art/loading.gif" style="height:12px;position:relative;bottom:1px" /></span>)</span> 
			
									<span style="float:right;margin-left:2px" class=" table_type transaction_type state_details ">]</span> <span style="float:right;margin-left:2px" class=" table_type transaction_type state_details {if $users_staff_state.Inactive}selected{/if}"  id="users_staff_state_Inactive" table_type="Inactive" title="{t}Inactive{/t}">{t}Inactive{/t}</span> <span style="float:right;margin-left:2px" class=" table_type transaction_type state_details ">|</span> <span style="float:right;margin-left:2px" class=" table_type transaction_type state_details {if $users_staff_state.Active}selected{/if} label_part_InUse"  id="users_staff_state_Active" table_type="Active" title="{t}Active{/t}">{t}Active{/t}</span> <span style="float:right;margin-left:0px" class=" table_type transaction_type state_details">[</span> 

			
			</div>
			<div class="table_top_bar" >
			</div>
			<div class="clusters">
					<div class="buttons small left cluster">
						<button class="table_option {if $users_view=='general'}selected{/if}" id="general">{t}General{/t}</button> <button class="table_option {if $users_view=='weblog'}selected{/if}" id="weblog">{t}Weblog{/t}</button> 
					
					</div>
					<div style="clear:both">
					</div>
				</div>
			
			{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0 } 
			<div id="table0" class="data_table_container dtable btable" style="font-size:90%">
			</div>
		</div>
		<div id="block_groups" style="{if $block_view!='groups'}display:none;{/if}clear:both;margin:10px 0 40px 0">
			<div class="data_table" style="margin-top:25px;">
				<span class="clean_table_title">{t}Groups{/t}</span> 
				<div class="table_top_bar space">
				</div>
				{include file='table_splinter.tpl' table_id=1 filter_name='' filter_value='' no_filter=1 } 
				<div id="table1" class="data_table_container dtable btable">
				</div>
			</div>
		</div>
		<div id="block_login_history" style="{if $block_view!='login_history'}display:none;{/if}clear:both;margin:10px 0 40px 0">
			<div class="data_table" style="margin-top:25px">
				<span class="clean_table_title">{t}Staff User Login History{/t}</span> 
				<div class="table_top_bar space">
				</div>
				{include file='table_splinter.tpl' table_id=2 filter_name=$filter_name2 filter_value=$filter_value2 } 
				<div id="table2" class="data_table_container dtable btable">
				</div>
			</div>
		</div>
	</div>
</div>
{include file='footer.tpl'} 