{include file='header.tpl'} 
<div id="bd">
	<input type="hidden" value="{$user_key}" id="user_key">
		<input type="hidden" value="{$dashboard_key}" id="dashboard_key">

	<div class="branch" style="clear:left;">
		<span><a href="index.php">{t}Dashboard{/t}</a> &rarr; <a href="edit_dashboards.php">{t}Dashboards Configuration{/t}</a> &rarr; {t}Edit Dashboard{/t}</span> 
	</div>
	<div class="top_page_menu" style="margin-top:10px">
		<div class="buttons" style="float:left">
			<button onclick="window.location='widgets.php'"><img src="art/icons/bricks.png" alt=""> {t}Widget List{/t}</button> <button onclick="window.location='edit_dashboards.php'"><img src="art/icons/cog.png" alt=""> {t}Dashboards Configuration{/t}</button> 
		</div>
		<div class="buttons" style="float:right">
			<button onclick="window.location='index.php'"><img src="art/icons/door_out.png" alt=""> {t}Exit Edit{/t}</button> 
			 <button id="add_widget"><img src="art/icons/add.png" alt=""> {t}Add Widget{/t}</button> 
		</div>
		<div style="clear:both">
		</div>
	</div>
	<div style="clear:both;margin-top:20px">
		<span class="clean_table_title">{t}Dashboard's Widget List{/t} </span> 
		<div class="table_top_bar space">
		</div>
		{include file='table_splinter.tpl' table_id=1 filter_name=$filter_name1 filter_value=$filter_value1 } 
		<div id="table1" style="font-size:90%" class="data_table_container dtable btable">
		</div>
	</div>
</div>


<div id="dialog_delete_widget_in_dashboard" style="padding:10px 10px 10px 10px;">
	<h2 style="padding-top:0px">
		{t}Remove widget from dashboard{/t} 
	</h2>
	<h2 style="padding-top:0px" id="dialog_delete_widget_in_dashboard_data">
	</h2>
	<input type="hidden" id="dialog_delete_widget_in_dashboard_key" value=""> 
	<input type="hidden" id="dialog_delete_widget_in_dashboard_table_id" value=""> 
	<input type="hidden" id="dialog_delete_widget_in_dashboard_recordIndex" value=""> 
	
	<div style="display:none" id="deleting">
		<img src="art/loading.gif" alt=""> {t}removing widget from dashboard, wait please{/t} 
	</div>
	<div id="delete_widget_in_dashboard_buttons" class="buttons">
		<button onclick="save_delete('delete','widget_in_dashboard')" class="positive">{t}Yes, remove it!{/t}</button> <button onclick="cancel_delete('delete','widget_in_dashboard')" class="negative">{t}No, I dont want to remove it{/t}</button> 
	</div>
</div>

<div id="dialog_widget_list">
	<div class="splinter_cell" style="padding:10px 15px 10px 0;border:none">
		<div id="the_table" class="data_table">
			<span class="clean_table_title">{t}Widget List{/t}</span> {include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0} 
			<div id="table0" class="data_table_container dtable btable">
			</div>
		</div>
	</div>
</div>{include file='footer.tpl'} 