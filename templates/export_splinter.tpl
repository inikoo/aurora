<div id="dialog_export_{$id}" style="padding:15px 25px 5px 20px;display:none">
	<table class="edit" style="width:500px" border="0">
		
		<tbody id="dialog_export_form_{$id}">
			<tr>
				<td colspan="3"> 
				<div class="buttons left">
					<button id="export_xls_{$id}" style="width:70px"><img src="art/icons/page_excel.png" alt=""> Excel</button> 
					<button id="export_csv_{$id}" style="width:70px"><img src="art/icons/page_white_text.png" alt=""> CSV</button> 
				</div>
				</td>
			</tr>
			<tr style="height:10px">
				<td colspan="3"></td>
			</tr>
			<tr class="top">
				<td>{t}Map{/t}:</td>
				<td colspan="2"> <span style="float:left">Default</span> 
				<div class="buttons small">
					<button id="dialog_export_new_map_{$id}">{t}New map{/t}</button> <button id="dialog_export_map_library_{$id}">{t}Maps{/t}</button> 
				</div>
				</td>
			</tr>
		</tbody>
		<tbody id="dialog_export_maps_{$id}" style="display:none">
			<tr>
				<td>{t}Map Library{/t}:</td>
				<td colspan="2"> 
				<div class="buttons small">
					<button id="dialog_export_new_map_bis_{$id}">{t}New map{/t}</button> 
				</div>
				</td>
			</tr>
			<tr>
				<td>{t}Default Map{/t}:</td>
				<td colspan> 
				<div class="buttons small left">
					<button>{t}Select{/t}</button> 
				</div>
				</td>
			</tr>
		</tbody>
		<tbody id="dialog_export_fields_{$id}" style="display:none">
			<tr class="title">
				<td colspan="2"></td>
			</tr>
			{foreach from=$export_fields key=field_id item=export_field name=fields} 
			<tr>
				<td>{$export_field.label}</td>
				<td><img class="map_field" id="export_customer_field_{$field_id}" onClick="update_map_field(this)" style="height:16px" {if $export_field.checked}checked=1 ovalue=1  src="art/icons/checkbox_checked.png"  {else} checked=0 ovalue=0  src="art/icons/checkbox_unchecked.png" {/if} /></td>
				{if $smarty.foreach.fields.first} 
				<td rowspan="{$number_export_customer_fields}"> 
				<table  style="width:100%" border=1>
					<tr  id="field_map_buttons" style="visibility:hidden">
						<td> 
						<div class="buttons small left">
							<button>{t}Save{/t}</button> <button>{t}Save As{/t}</button> <button>{t}Reset{/t}</button>
						</div>
						</td>
						
					</tr>
					<tr id="field_map_save_as" style="visibility:hidden">
						<td>{t}Save map as:{/t} 
						<div class="buttons small left">
							<input style="width:100px;float:left;margin-right:3px" id="save_as_map" value="" />
							<button>{t}Save{/t}</button>
						</div>
						</td>
						
					</tr>
				</table>
				</td>
				{/if}
				
			</tr>
			{/foreach} 
		</tbody>
		
		<tbody id="dialog_export_result_{$id}" style="display:none">
			<tr id="export_result_wait_{$id}" >
		<td>
		<img src="art/loading.gif"> <span>{t}Processing Request{/t}</span> <span style="margin-left:20px" id="dialog_export_progress_{$id}"></span>
		</td>
		</tr>
		<tr id="export_result_download_{$id}" style="display:none">
		<td>
		<div class="buttons left"><a id="export_result_download_link_{$id}"  href=""   >{t}Download{/t}</a></div>
		</td>
		</tr>
		</tbody>
		
	</table>
</div>
