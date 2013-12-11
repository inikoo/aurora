{include file='header.tpl'} 
<div id="bd">
<input type="hidden" id="area_key" value="{$company_area->id}"> 
		
		
	<div class="branch">
			<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a>&rarr; <a href="hr.php">{$account_label}</a> &rarr; {t}Area{/t}: {$company_area->get('Company Area Code')} ({t}Editing{/t})</span> 
		</div>
		<div class="top_page_menu" style="margin-top:10px">
			<div class="buttons" style="float:right">
				 <button onclick="window.location='company_area.php?id={$company_area->id}'"><img src="art/icons/cog.png" alt=""> {t}Exit Edit{/t}</button> 
			</div>
			<div class="buttons" style="float:left">
				<span class="main_title">{t}Editing Company Area{/t}: {$company_area->get('Company Area Name')} [{$company_area->get('Company Area Code')}]</span> 
			</div>
			<div style="clear:both">
			</div>
		</div>	
		
		
	<ul class="tabs" id="chooser_ul" style="clear:both">
		<li> <span class="item {if $edit=='details'}selected{/if}" id="details"> <span> {t}Details{/t}</span></span></li>
		<li> <span class="item {if $edit=='departments'}selected{/if}" id="departments"> <span> {t}Departments{/t}</span></span></li>

	</ul>
	<div class="tabbed_container">
		<div class="edit_block" style="{if $edit!='details'}display:none{/if}" id="d_details">
			

			<table class="edit" border=0 style="width:100%">
			
			<tr class="title">
				<td>Company Area</td>
			</tr>
			
				<tr >
					<td style="width:200px" class="label">Area Code:</td>
					<td style="text-align:left;width:600px"> 
					<div >
						<input style="text-align:left;width:150px" id="Company_Area_Code" value="{$company_area->get('Company Area Code')}" ovalue="{$company_area->get('Company Area Code')}"> 
						<div id="Company_Area_Code_Container">
						</div>
					</div>
					</td>
					<td id="Company_Area_Code_msg" class="edit_td_alert" style="width:600px"></td>
				</tr>
				<tr >
					<td class="label">{t}Area Name{/t}:</td>
					<td style="text-align:left"> 
					<div>
						<input style="text-align:left;width:100%" id="Company_Area_Name" value="{$company_area->get('Company Area Name')}" ovalue="{$company_area->get('Company Area Name')}"> 
						<div id="Company_Area_Name_Container">
						</div>
					</div>
					</td>
					<td id="Company_Area_Name_msg" class="edit_td_alert"></td>
				</tr>
			</tr>
			<tr >
				<td class="label">{t}Area Description{/t}:</td>
				<td style="text-align:left"> 
				<div>
					<input style="text-align:left;;width:100%" id="Company_Area_Description" value="{$company_area->get('Company Area Description')}" ovalue="{$company_area->get('Company Area Description')}"> 
					<div id="Company_Area_Description_Container">
					</div>
				</div>
				</td>
				<td id="Company_Area_Description_msg" class="edit_td_alert"></td>
			</tr>
			
			<tr class="buttons">
			<td colspan=2>
			<div class="buttons">
				<button  id="save_edit_company_area" class="disabled">{t}Save{/t}</button> 
				<button  id="reset_edit_company_area" class="disabled">{t}Reset{/t}</button> 
			</div>
			</td>
			</tr>
			
			
		</table>
	</div>
	<div class="edit_block" style="{if $edit!='departments'}display:none{/if}" id="d_departments"></div>
	</div>

<div class="buttons small">
		<button id="show_history" style="{if $show_history}display:none{/if};margin-right:0px" onclick="show_history()">{t}Show changelog{/t}</button> <button id="hide_history" style="{if !$show_history}display:none{/if};margin-right:0px" onclick="hide_history()">{t}Hide changelog{/t}</button> 
	</div>
	<div id="history_table" class="data_table" style="clear:both;{if !$show_history}display:none{/if}">
		<span class="clean_table_title">{t}Changelog{/t}</span> 
		<div class="table_top_bar space">
		</div>
		{include file='table_splinter.tpl' table_id=1 filter_name=$filter_name1 filter_value=$filter_value1 } 
		<div id="table1" class="data_table_container dtable btable history">
		</div>
	</div>

</div>






<div id="filtermenu1" class="yuimenu">
	<div class="bd">
		<ul class="first-of-type">
			<li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Filter options{/t}:</li>
			{foreach from=$filter_menu1 item=menu } 
			<li class="yuimenuitem"><a class="yuimenuitemlabel" onclick="change_filter('{$menu.db_key}','{$menu.label}',1)"> {$menu.menu_label}</a></li>
			{/foreach} 
		</ul>
	</div>
</div>
<div id="rppmenu1" class="yuimenu">
	<div class="bd">
		<ul class="first-of-type">
			<li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Rows per Page{/t}:</li>
			{foreach from=$paginator_menu1 item=menu } 
			<li class="yuimenuitem"><a class="yuimenuitemlabel" onclick="change_rpp({$menu},1)"> {$menu}</a></li>
			{/foreach} 
		</ul>
	</div>
</div>
{include file='footer.tpl'} 