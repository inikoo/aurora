{include file='header.tpl'} 
<div id="bd" >
	
		<input type="hidden" value="{$user->id}" id="user_key"> 
		<input type="hidden" value="0" id="dashboard_key"> 
		<div class="branch" style="clear:left;">
			<span><a href="index.php">{t}Dashboard{/t}</a> &rarr; {t}Dashboard Configuration{/t}</span> 
		</div>
		<div class="top_page_menu" style="margin-top:10px">
		
			<div class="buttons" style="float:left">
										<button id="widget_list" onclick="window.location='widgets.php'"><img src="art/icons/bricks.png" alt=""> {t}Widget List{/t}</button> 

			</div>
			<div class="buttons" style="float:right">
							<button onclick="window.location='index.php'"><img src="art/icons/door_out.png" alt=""> {t}Exit Configuration{/t}</button> 

				<button  style="{if $number_dashboards>=5}display:none;{/if}" id="add_dashboard"><img src="art/icons/add.png" alt=""> {t}Add Dashboard{/t}</button> 
			</div>
			<div style="clear:both">
			</div>
		</div>
	
		<div style="clear:both;margin-bottom:15px">
			{foreach from=$dashboard_data item=dashboard key=key}
			<div style="width:165px;float:left; margin:10px 8px ">
			<div id="edit_dashboard{$key}" style="margin-bottom:5px;">
					{if $default_dashboard_key==$key}	
					 <img  src="art/icons/bullet_star.png"  /> 
					 {else}
					<img style="cursor:pointer" src="art/icons/bullet_gray_star.png" onclick="set_as_default({$key})" /> 
					 {/if}
					 
					 <img style="cursor:pointer" src="art/icons/brick_add.png" id="widget_add" onclick="add_widget(this, {$key})" /> 
					 <img style="cursor:pointer" src="art/icons/application_edit.png" onclick="window.location='edit_dashboard.php?id={$key}'" /> 
					 <img style="{if $number_dashboards==1}display:none;{/if}cursor:pointer;margin-left:86px" src="art/icons/cross.png" onclick="delete_dashboard({$key})" />
				</div>
			<div style="border:1px solid #ccc; width:160px; height:120px; ">
				
				
			</div>
			<span style="cursor:pointer" onclick="window.location='edit_dashboard.php?id={$key}'" >Number of Widgets: {$dashboard.number_of_widgets}</span>
			</div>
			{/foreach}
			<div style="clear:both;"></div>
		</div>
		
		
	
</div>
{include file='footer.tpl'} 
<div id="dialog_widget_list">
	<div class="splinter_cell" style="padding:10px 15px 10px 0;border:none">
		<div id="the_table" class="data_table">
			<span class="clean_table_title">{t}Widget List{/t}</span> {include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0} 
			<div id="table0" class="data_table_container dtable btable">
			</div>
		</div>
	</div>
</div>
