{include file='header.tpl'} 
<div id="bd">
	{include file='locations_navigation.tpl'} 
	<div class="branch">
				<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a>&rarr; {if $user->get_number_warehouses()>1}<a href="warehouses.php">{t}Warehouses{/t}</a> &rarr; {/if}{t}Warehouse{/t}: <span class="warehouse_name">{$warehouse->get('Warehouse Name')}</span> ({t}Editing{/t}) &rarr; {t}New Warehouse Area{/t}</span> 

	</div>
	<div class="top_page_menu">
		<div class="buttons" style="float:right">
			<button onclick="window.location='edit_warehouse.php?id={$warehouse->id}'" class="negative"><img src="art/icons/door_out.png" alt=""> {t}Cancel{/t}</button> 
		</div>
		<div class="buttons" style="float:left">
			<span class="main_title">{t}New Warehouse Area{/t} (<span id="title_code">{$warehouse->get('Warehouse Code')}</span>)</span></span> 
		</div>
		<div style="clear:both">
		</div>
	</div>
	<div>
		<table class="edit" style="width:600px">
			<tr class="first">
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
			<tr class="first">
				<td class="label">{t}After creation{/t}:</td>
				<td> 
				<div class="buttons small left">
					<button id="on_creation_follow_go_back" class="button {if $on_creation=='go_back'}selected{/if}">{t}Go back{/t}</button> <button id="on_creation_follow_new" class="button {if $on_creation=='follow_new'}selected{/if}">{t}Edit new area{/t}</button> <button id="on_creation_stay" class="button {if $on_creation=='stay'}selected{/if}">{t}Create other area{/t}</button> 
				</div>
				</td>
			</tr>
			<tr class="buttons">
				<td colspan="2"> 
				<div class="buttons">
					<button id="add_area" class="button positive">{t}Save{/t}</button> <button  id="cancel_add_area" class="button negative">{t}Cancel{/t}</button> 
				</div>
				</td>
			</tr>
		</table>
	</div>

</div>
{include file='footer.tpl'} 