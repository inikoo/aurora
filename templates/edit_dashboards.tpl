{include file='header.tpl'} 
<div id="bd" >
	
		<input type="hidden" value="{$user->id}" id="user_key"> 
		<div class="branch" style="clear:left;">
			<span><a href="index.php">{t}Dashboard{/t}</a> &rarr; {t}Dashboard Configuration{/t}</span> 
		</div>
		<div class="top_page_menu" style="margin-top:10px">
		
			<div class="buttons" style="float:left">
										<button onclick="window.location='widgets.php'"><img src="art/icons/bricks.png" alt=""> {t}Widget List{/t}</button> 

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
					 
					 <img style="cursor:pointer" src="art/icons/brick_add.png" onclick="add_widget({$key})" /> 
					 <img style="cursor:pointer" src="art/icons/application_edit.png" onclick="window.location='edit_dashboard.php?id={$key}'" /> 
					 <img style="{if $number_dashboards==1}display:none;{/if}cursor:pointer;margin-left:86px" src="art/icons/cross.png" onclick="delete_dashboard({$key})" />
				</div>
			<div style="border:1px solid #ccc; width:160px; height:120px; ">
				
				
			</div>
			</div>
			{/foreach} 
			<div style="clear:both;"></div>
		</div>
		
			<div style="clear:both;">
		  <span class="clean_table_title">{t}Widget List{/t} </span>
 <div class="table_top_bar" style="margin-bottom:10px"></div>
		{include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0 } 
		<div id="table0" style="font-size:90%" class="data_table_container dtable btable ">
		</div>
		</div>
	
</div>
{include file='footer.tpl'} 