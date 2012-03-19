{include file='header.tpl'} 
<div id="bd">
	{include file='locations_navigation.tpl'} 
	<div class="branch">
  <span ><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home"/></a>&rarr; {if $user->get_number_warehouses()>1}<a href="warehouses.php">{t}Warehouses{/t}</a>  &rarr; {/if} <a href="edit_warehouse.php?id={$warehouse->id}">{t}Locations{/t} ({t}Editing Warehouse{/t})</a> &rarr; {t}New Warehouse Area{/t}</span>

	</div>
	
	<div class="top_page_menu">
    <div class="buttons" style="float:right">
       
        <button  onclick="window.location='edit_warehouse.php?id={$warehouse->id}'" class="negative" ><img src="art/icons/door_out.png" alt=""> {t}Cancel{/t}</button>

        
       
    </div>
    <div class="buttons" style="float:left">
      
    <span class="main_title">{t}New Warehouse Area{/t}  (<span id="title_code">{$warehouse->get('Warehouse Code')}</span>)</span></span>


 </div>
    <div style="clear:both"></div>
</div>
	
	
	
	<div>
		<table class="edit" style="margin-top:10px;width:500px">
			<tr class="top">
				<td class="label" style="width:150px">{t}Warehouse{/t}:</td>
				<td><span style="font-weight:800">{$warehouse->get('Warehouse Name')}</span> 
				<input type="hidden" id="warehouse_key" ovalue="{$warehouse->id}" value="{$warehouse->id}"></td>
			</tr>
			<tr>
				<td class="label">{t}Area Code{/t}:</td>
				<td> 
				<input style="width:100%" id="area_code" ovalue="" type="text" />
				</td>
			</tr>
			<tr>
				<td class="label">{t}Area Name{/t}:</td>
				<td> 
				<input style="width:100%;" id="area_name" ovalue="" type="text" />
				</td>
			</tr>
			<tr>
				<td class="label">{t}Area Description{/t}:</td>
				<td><textarea style="width:100%;height:100px" ovalue="" id="area_description"></textarea></td>
			</tr>
			<tr style="height:10px">
				<td colspan="2"></td>
			</tr>
			<tr>
				<td colspan="2"> 
				<div class="buttons">
					<button id="add_area" class="button">{t}Save{/t}</button> <button style="margin-right:10px" id="reset_add_area" class="button">{t}Cancel{/t}</button> 
				</div>
				</td>
			</tr>
		</table>
	</div>
	<div style="clear:both">
	</div>
	<div id="the_table" class="data_table" style="margin:20px 0px;clear:both;display:none">
		<span class="clean_table_title">{t}Warehouse Areas{/t}</span> {include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0 } 
		<div id="table0" class="data_table_container dtable btable ">
		</div>
	</div>
</div>
{include file='footer.tpl'} 