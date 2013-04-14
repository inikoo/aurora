{include file='header.tpl'} 
<div id="bd">
	{include file='locations_navigation.tpl'} 
	<input type="hidden" id="warehouse_key" value="{$warehouse->id}" />
	<div class="branch">
			<span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home" /></a>&rarr; {if $user->get_number_warehouses()>1}<a href="warehouses.php">{t}Warehouses{/t}</a> &rarr; {/if}{t}Warehouse{/t}: <span class="warehouse_name">{$warehouse->get('Warehouse Name')}</span> ({t}Editing{/t})</span> 
	</div>
	<div class="top_page_menu">
		<div class="buttons" style="float:right">
			<button onclick="window.location='warehouse.php?id={$warehouse->id}'"><img src="art/icons/door_out.png" alt=""> {t}Exit Edit{/t}</button> <button onclick="window.location='new_warehouse_area.php?warehouse_id={$warehouse->id}'"><img src="art/icons/add.png" alt=""> {t}Add Area{/t}</button> <button onclick="window.location='new_location.php?warehouse_id={$warehouse->id}&window=1'"><img src="art/icons/add.png" alt=""> {t}Add Location{/t}</button> 
		</div>
		<div class="buttons" style="float:left">
			<span class="main_title">{t}Editing Warehouse{/t}: <span class="id"><span class="warehouse_name">{$warehouse->get('Warehouse Name')}</span> (<span class="warehouse_code">{$warehouse->get('Warehouse Code')}</span>)</span></span> 
		</div>
		<div style="clear:both">
		</div>
	</div>
	<ul class="tabs" id="chooser_ul" style="clear:both">
		<li> <span class="item {if $edit=='description'}selected{/if}" id="description"> <span> {t}Description{/t}</span></span></li>
		<li> <span class="item {if $edit=='areas'}selected{/if}" id="areas"> <span> {t}Areas{/t}</span></span></li>
		<li style="display:none"> <span class="item {if $edit=='shelfs'}selected{/if}" id="shelfs"> <span> {t}Shelfs{/t}</span></span></li>
		<li> <span class="item {if $edit=='locations'}selected{/if}" id="locations"> <span> {t}Locations{/t}</span></span></li>
		<li style="display:none"> <span class="item {if $edit=='shelf_types'}selected{/if}" id="shelf_types"> <span> {t}Shelf Types{/t}</span></span></li>
		<li style="display:none"> <span class="item {if $edit=='location_types'}selected{/if}" id="location_types"> <span> {t}Location Types{/t}</span></span></li>
	</ul>
	<div class="tabbed_container">
		<div id="description_block" class="edit_block"  style="{if $edit!='description'}display:none{/if}">
			<div id="new_warehouse_area_block" style="font-size:80%;float:left;padding:10px 15px;border:1px solid #ddd;width:200px;margin-bottom:15px;margin-left:10px;display:none">
			</div>
			
			<table style="margin:0;width:100%" class="edit" border="0">
				<tr class="title">
					<td colspan=3>
					{t}Warehouse Description{/t}
					</td>
				</tr>
			
				<tr class="first">
					<td style="width:100px" class="label">{t}Warehouse Code{/t}:</td>
					<td style="width:250px"> 
					<div >
						<input type="text" id="warehouse_code" value="{$warehouse->get('Warehouse Code')}" ovalue="{$warehouse->get('Warehouse Code')}" valid="0"> 
						<div id="warehouse_code_Container">
						</div>
					</div>
					</td>
					<td style="width:250px"> <span id="warehouse_code_msg"></span> </td>
				</tr>
				<tr>
					<td class="label">{t}Warehouse Name{/t}:</td>
					<td> 
					<div >
						<input type="text" id="warehouse_name" value="{$warehouse->get('Warehouse Name')}" ovalue="{$warehouse->get('Warehouse Name')}" valid="0"> 
						<div id="warehouse_name_Container">
						</div>
					</div>
					</td>
					<td> <span id="warehouse_name_msg"></span> </td>
				</tr>
				<tr class="buttons">
				<td colspan=2>
				<div class="buttons">
					<button class="positive disabled" id="save_new_warehouse" onclick="save_edit_warehouse()">{t}Save{/t}</button> <button id="description_reset" class="negative" onclick="reset_description_data()">{t}Cancel{/t}</button> 
				</div>
				</td>
				</tr>
				
				<tr class="title">
					<td colspan=3>
					{t}Location Flags{/t}
					</td>
				</tr>
				
				{foreach from=$flags item=flag}
				<tr>
					<td class="label"><img style="opacity:{if $flag.display=='Yes'}1{else}0.5{/if}" id="location_flag_icon_{$flag.id}" src="art/icons/flag_{$flag.color}.png"/></td>
					<td>
					<table border=0 style="margin:0;padding:0">
					<tr>
					<td style="width:100px">
					<div >
						<input style="width:100%" type="text" id="location_flag_label_{$flag.id}" value="{$flag.label}" ovalue="{$flag.label}" valid="1"> 
						<div id="location_flag_label_{$flag.id}_Container">
						</div>
					</div>
					</td>
					<td style="width:16px;padding:0px" id="location_flag_label_{$flag.id}_msg" class="edit_td_alert">
					
					</td>
					
					<td>
					<input  id="location_flag_active_{$flag.id}"  type="hidden" flag_id={$flag.id} value="{$flag.display}" ovalue="{$flag.display}"/>

					<div class="buttons small left">
						<button id="location_flag_display_{$flag.id}_Yes" class="location_flag_display_{$flag.id}" value="Yes" onClick="change_flag_display(this,{$flag.id})"  style="{if $flag.display!='Yes'}display:none{/if}"  ><img src="art/icons/bullet_green.png">{t}Enabled{/t}</button>
						<button id="location_flag_display_{$flag.id}_No"  class="location_flag_display_{$flag.id}" value="No" onClick="change_flag_display(this,{$flag.id})"  style="{if $flag.display=='Yes'}display:none{/if}"  ><img src="art/icons/bullet_red.png">{t}Disabled{/t}</button>

					</div>
					</td>
					<td id="location_flag_active_{$flag.id}_msg" class="edit_td_alert">
					
					</td>
					</tr>
					</table>
					</td>
					<td> <span id="flag_label_{$flag.id}_msg"></span> </td>
				</tr>
				{/foreach}
				<tr class="buttons">
				<td colspan=2>
				<div class="buttons">
					<button id="save_edit_location_flags"  class="positive disabled" >{t}Save{/t}</button> 
					<button id="reset_edit_location_flags" class="negative disabled" >{t}Cancel{/t}</button> 
				</div>
				</td>
				</tr>
			</table>
		</div>
		<div id="areas_block" class="edit_block"  style="{if $edit!='areas'}display:none{/if}">
			<div class="buttons small">
				<button id="add_area_here" class="state_details">Add Area</button> <button style="display:none" id="save_area" class="state_details">{t}Save{/t}</button> <button style="display:none" id="close_add_area" class="state_details">{t}Close Dialog{/t}</button> 
			</div>
			<div id="new_warehouse_area_messages" style="float:left;padding:5px;border:1px solid #ddd;width:400px;margin-bottom:15px;display:none">
				<table class="edit">
					<tr>
						<td class="label">{t}Warehouse{/t}:</td>
						<td><span style="font-weight:800">{$warehouse->get('Warehouse Name')}</span>
						<input type="hidden" id="warehouse_key" ovalue="{$warehouse->id}" value="{$warehouse->id}"></td>
					</tr>
					<tr>
						<td class="label">{t}Area Code{/t}:</td>
						<td>
						<input id="area_code" ovalue="" type="text" />
						</td>
					</tr>
					<tr>
						<td class="label">{t}Area Name{/t}:</td>
						<td>
						<input id="area_name" ovalue="" type="text" />
						</td>
					</tr>
					<tr>
						<td class="label">{t}Area Description{/t}:</td>
						<td><textarea ovalue="" id="area_description"></textarea></td>
					</tr>
				</table>
			</div>
			<div id="new_warehouse_area_block" style="font-size:80%;float:left;padding:10px 15px;border:1px solid #ddd;width:200px;margin-bottom:15px;margin-left:10px;display:none">
				Messages 
			</div>
			<div style="clear:both">
			</div>
			<div id="the_table1" class="data_table" style="margin:0px 0px;clear:left;">
				<span class="clean_table_title">{t}Warehouse Areas{/t}</span> 
				<div class="table_top_bar" style="margin-bottom:15px">
				</div>
				{include file='table_splinter.tpl' table_id=1 filter_name=$filter_name1 filter_value=$filter_value1} 
				<div id="table1" class="data_table_container dtable btable">
				</div>
			</div>
		</div>
		<div id="locations_block" class="edit_block"  style="{if $edit!='locations'}display:none{/if}">
			<div id="the_table0" class="data_table" style="margin:20px 0px;clear:both">
				<span class="clean_table_title">{t}Locations{/t}</span> {include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0} 
				<div id="table0" class="data_table_container dtable btable">
				</div>
			</div>
		</div>
		<div id="shelfs_block" class="edit_block"  style="{if $edit!='shelfs'}display:none{/if}">
			{*} 
			<div class="general_options" style="float:right">
				<span style="margin-right:10px" id="add_shelf" class="state_details">Add Shelf</span> <span style="margin-right:10px;display:none" id="save_shelf" class="state_details disabled">{t}Save{/t}</span> <span style="margin-right:10px;display:none" id="close_add_shelf" class="state_details">{t}Close Dialog{/t}</span> 
			</div>
			{include file='new_shelf_splinter.tpl'} 
			<div class="shelf_locations" id="shelf_locations_layout">
			</div>
			<div id="the_table2" class="data_table" style="margin:0px 0px;clear:left;">
				<span class="clean_table_title">{t}Warehouse Shelfs{/t}</span> {include file='table_splinter.tpl' table_id=2 filter_name=$filter_name2 filter_value=$filter_value2} 
				<div id="table2" class="data_table_container dtable btable">
				</div>
			</div>
			{*} 
		</div>
		{*} 
		<div id="shelf_types_block" class="edit_block"  style="{if $edit!='shelf_types'}display:none{/if}">
			<div class="general_options" style="float:right">
				<span style="margin-right:10px" id="add_shelf_type" class="state_details">Create Type</span> <span style="margin-right:10px;display:none" id="save_shelf_type" class="state_details">{t}Save{/t}</span> <span style="margin-right:10px;display:none" id="close_add_shelf_type" class="state_details">{t}Close Dialog{/t}</span> 
			</div>
			<div id="new_warehouse_shelf_type_messages" style="float:left;padding:5px;border:1px solid #ddd;width:480px;margin-bottom:15px;display:none">
				<table class="edit">
					<tr>
						<td class="label">{t}Warehouse{/t}:</td>
						<td><span style="font-weight:800">{$warehouse->get('Warehouse Name')}</span>
						<input type="hidden" id="warehouse_key" ovalue="{$warehouse->id}" value="{$warehouse->id}"></td>
					</tr>
					<tr>
						<td class="label">{t}Shelf Type Name{/t}:</td>
						<td>
						<input id="shelf_type_name" ovalue="" type="text" />
						</td>
					</tr>
					<tr>
						<td class="label">{t}Description{/t}:</td>
						<td><textarea ovalue="" id="shelf_type_description"></textarea></td>
					</tr>
					<tr>
						<td class="label">{t}Type{/t}:</td>
						<td> 
						<div class="options" style="margin:5px 0" id="shelf_type_type_container">
							<input type="hidden" value="{$shelf_default_type}" ovalue="{$shelf_default_type}" id="shelf_type_type"> {foreach from=$shelf_types item=unit_tipo key=name} <span class="radio{if $unit_tipo.selected} selected{/if}" id="radio_shelf_type_{$name}" radio_value="{$name}">{$unit_tipo.fname}</span> {/foreach} 
						</div>
						</td>
					</tr>
					<tr>
						<td class="label">{t}Typical Layout{/t}:</td>
						<td>{t}Columns{/t}:
						<input style="width:2em" id="shelf_type_columns" ovalue="" type="text" />
						{t}Rows{/t}:
						<input style="width:2em" id="shelf_type_rows" ovalue="" type="text" />
						</td>
					</tr>
					<tr>
						<td class="label">{t}Average Location{/t}</td>
						<td></td>
					</tr>
					<tr>
						<td class="label">{t}Length{/t}:</td>
						<td>
						<input id="shelf_type_length" ovalue="" type="text" />
						</td>
					</tr>
					<tr>
						<td class="label">{t}Deep{/t}:</td>
						<td>
						<input id="shelf_type_deep" ovalue="" type="text" />
						</td>
					</tr>
					<tr>
						<td class="label">{t}Height{/t}:</td>
						<td>
						<input id="shelf_type_height" ovalue="" type="text" />
						</td>
					</tr>
					<tr>
						<td class="label">{t}Max Weight{/t}:</td>
						<td>
						<input id="shelf_type_weight" ovalue="" type="text" />
						</td>
					</tr>
					<tr>
						<td class="label">{t}Max Volume{/t}:</td>
						<td>
						<input id="shelf_type_volume" ovalue="" type="text" />
						</td>
					</tr>
				</table>
			</div>
			<div id="new_warehouse_shelf_type_block" style="font-size:80%;float:left;padding:10px 15px;border:1px solid #ddd;width:200px;margin-bottom:15px;margin-left:10px;display:none">
				Messages 
			</div>
			<div id="the_table3" class="data_table" style="margin:0px 0px;clear:left;">
				<span class="clean_table_title">{t}Shelf Types{/t}</span> 
				<div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999">
				</div>
				<table style="float:left;margin:0 0 5px 0px ;padding:0" class="options">
					<tr>
						<td {if $shelf_type_view="='general'}class=&quot;selected&quot;{/if}" tipo="general" id="shelf_type_general_view">{t}General{/t}</td>
						<td {if $shelf_type_view="='dimensions'}class=&quot;selected&quot;{/if}" tipo="dimensions" id="shelf_type_dimensions_view">{t}Dimensions{/t}</td>
					</tr>
				</table>
				{include file='table_splinter.tpl' table_id=3 filter_name=$filter_name3 filter_value=$filter_value3} 
				<div id="table3" class="data_table_container dtable btable">
				</div>
			</div>
		</div>
		<div id="location_types_block" class="edit_block"  style="{if $edit!='location_types'}display:none{/if}">
		{*} 
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
<div id="area_dialog" style="width:300px;">
	{*} 
	<div class="options" style="width:300px;padding:10px;text-align:center">
		<table border="1" style="margin:auto" id="pack_it_buttons">
			{foreach from=$packers item=packer_row name=foo} 
			<tr>
				{foreach from=$packer_row key=row_key item=packer } 
				<td staff_id="{$packer.StaffKey}" id="packer_pack_it{$packer.StaffKey}" class="pack_it_button" onclick="select_staff_pack_it(this,event)">{$packer.StaffAlias}</td>
				{/foreach} 
			</tr>
			{/foreach} 
		</table>
	</div>
	{*} 
	<table class="edit">
		<input type="hidden" id="area_key"> 
		<input type="hidden" id="warehouse_key" value="$warehouse->id"> 
		<input type="hidden" id="location_key" value=""> 
		<input type="hidden" id="record_index" value=""> 
		<tr class="first">
			<td class="label">{t}Area{/t}:</td>
			<td style="text-align:left"> 
			<div style="width:190px;position:relative;top:00px">
				<input style="text-align:left;width:180px" id="Area_Code" value="" ovalue="" valid="0"> 
				<div id="Area_Code_Container">
				</div>
			</div>
			</td>
			<td id="Area_Code_msg" class="edit_td_alert"></td>
		</tr>
	</table>
	<table class="edit" style="margin-top:10px;float:right">
		<tr>
			<td colspan="2"> <span class="button" onclick="close_area_dialog()">Cancel</span> <span class="button" onclick="change_area_save()">Go</span>
			<td>
		</tr>
	</table>
</div>
{include file='footer.tpl'} 