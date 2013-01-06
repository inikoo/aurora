{include file='header.tpl'} 
<div id="bd">
	{include file='assets_navigation.tpl'} 
	<div class="branch">
		<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a>&rarr; {t}Stores{/t} ({t}Editing{/t})</span> 
	</div>
	<div class="top_page_menu">
		<div class="buttons left" style="float:left">
			<span class="main_title">{t}Editing Stores{/t}</span> 
		</div>
		<div class="buttons" style="float:right">
			<button style="margin-left:0px" onclick="window.location='stores.php'"><img src="art/icons/door_out.png" alt="" /> {t}Exit Edit{/t}</button>  <button style="{if !$user->can_create('store wide')}display:none{/if}"  onclick="window.location='new_store.php'"><img src="art/icons/add.png" alt=""> {t}Add Store{/t}</button>
		</div>
		<div style="clear:both">
		</div>
	</div>
	<ul class="tabs" id="chooser_ul">
		<li> <span class="item {if $edit=='description'}selected{/if}" id="description"> <span> {t}Headquarters{/t}</span></span></li>
		<li> <span class="item {if $edit=='stores'}selected{/if}" id="stores"><span> {t}Stores{/t}</span></span></li>
	</ul>
	<div class="tabbed_container">
		<div id="edit_messages">
		</div>
		<div class="edit_block" style="margin:0;padding:0 0px;{if $edit!=" description"}display:none{/if}" id="d_description">
			<div class="general_options" style="float:right">
				<span style="margin-right:10px;visibility:hidden" onclick="save_edit_general('corporation')" id="save_edit_corporation" class="state_details">{t}Save{/t}</span> <span style="margin-right:10px;visibility:hidden" onclick="reset_edit_general('corporation')" id="reset_edit_corporation" class="state_details">{t}Reset{/t}</span> 
			</div>
			<table class="edit">
				<tr>
					<td style="width:200px">{t}Corporation Name{/t}:</td>
					<td style="width:200px"> 
					<input id="name" onkeyup="validate_general('corporation','name',this.value)" onmouseup="validate_general('corporation','name',this.value)" onchange="validate_general('corporation','name',this.value)" changed="0" type='text' class='text' style="width:100%" maxlength="256" value="{$corporation->get('Corporation Name')}" ovalue="{$corporation->get('Corporation Name')}" />
					</td>
					<td class="edit_td_alert" id="name_msg"></td>
				</tr>
				<tr>
					<td>{t}Corporation Currency{/t}:</td>
					<td> 
					<input id="currency" onkeyup="validate_general('corporation','currency',this.value)" onmouseup="validate_general('corporation','currency',this.value)" onchange="validate_general('corporation','currency',this.value)" changed="0" type='text' class='text' style="width:3em" maxlength="3" value="{$corporation->get('Corporation Currency')}" ovalue="{$corporation->get('Corporation Currency')}" />
					</td>
					<td class="edit_td_alert" id="currency_msg"></td>
				</tr>
			</table>
		</div>
		<div class="edit_block" style="margin:0;padding:0 0px;{if $edit!="stores"}display:none{/if}" id="d_stores">
		
		<span class="clean_table_title">{t}Store List{/t} </span>
		<div class="table_top_bar">
				</div>
				{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0 } 
				<div id="table0" style="font-size:90%" class="data_table_container dtable btable">
				</div>
		</div>
		
			</div>
		</div>
	</div>
	{include file='footer.tpl'} 