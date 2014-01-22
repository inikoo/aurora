{include file='header.tpl'} 
<div id="bd">
	<div class="branch">
			<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a>&rarr; {$account_label} ({t}Editing{/t})</span> 
	</div>
	
	
	<div class="top_page_menu" style="margin-top:10px">
			<div class="buttons" style="float:right">
				 <button onclick="window.location='hr.php'"><img src="art/icons/door_out.png" alt=""> {t}Exit Edit{/t}</button> 
				
			</div>
			<div class="buttons" style="float:left">
				<span class="main_title">{t}Editing{/t}: {$account_name}</span> 
			</div>
			<div style="clear:both">
			</div>
		</div>
	
	
	<ul class="tabs" id="chooser_ul" style="clear:both">
		<li> <span class="item {if $edit=='staff'}selected{/if}" id="staff"> <span> {t}Staff{/t}</span></span></li>
	</ul>
	<div class="tabbed_container">
		<div class="edit_block" style="{if $edit!='staff'}display:none{/if}" id="d_staff">
			
		
			<div class="data_table" style="clear:both">
				<span class="clean_table_title">{t}Employees{/t}
				</span> 
				<div class="buttons small left" style="position:relative;left:10px;top:3px">
				<button id="new_employee"><img src="art/icons/add.png" alt=""> {t}Add Employee{/t}</button> 
			</div>
				
				
				<div class="table_top_bar space">
			</div>
				{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0 } 

				<div id="table0" class="data_table_container dtable btable">
				</div>
			</div>
		</div>
	</div>
	
</div>
<div id="filtermenu0" class="yuimenu">
	<div class="bd">
		<ul class="first-of-type">
			<li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Filter options{/t}:</li>
			{foreach from=$filter_menu0 item=menu } 
			<li class="yuimenuitem"><a class="yuimenuitemlabel" onclick="change_filter('{$menu.db_key}','{$menu.label}',0)"> {$menu.menu_label}</a></li>
			{/foreach} 
		</ul>
	</div>
</div>
<div id="rppmenu0" class="yuimenu">
	<div class="bd">
		<ul class="first-of-type">
			<li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Rows per Page{/t}:</li>
			{foreach from=$paginator_menu0 item=menu } 
			<li class="yuimenuitem"><a class="yuimenuitemlabel" onclick="change_rpp({$menu},0)"> {$menu}</a></li>
			{/foreach} 
		</ul>
	</div>
</div>
<div id="dialog_new_employee" style="padding:20px 20px 10px 20px ">
	<div id="new_employee_msg">
	</div>
	<div class="buttons">
		<button class="positive" onclick="window.location='new_employee.php?ref=ehr'">{t}Manually{/t}</button> <button class="positive" onclick="window.location='import.php?subject=staff'">{t}Import from file{/t}</button> <button class="negative" onclick="dialog_new_employee.hide()">{t}Cancel{/t}</button> 
	</div>
</div>
{include file='footer.tpl'} 