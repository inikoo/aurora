<div id="dialog_export_{$id}" style="padding:15px 25px 5px 20px;display:none">
	<table class="edit" style="width:500px" border="0">
		
		<tbody id="dialog_export_form_{$id}">
			<tr>
				<td colspan="3"> 
				<div class="buttons left">
					<button id="export_xls_{$id}" style="width:70px"><img src="art/icons/page_excel.png" alt=""> Excel</button> 
					<button id="export_csv_{$id}" style="width:70px"><img src="art/icons/page_white_text.png" alt=""> CSV</button> 
					<span id="export_no_field_msg_{$id}" style="display:none;" class="error">There isn't any field to export</span>
				</div>
				</td>
			</tr>
			<tr style="height:10px">
				<td colspan="3"></td>
			</tr>
			<tr class="top">
				<td>{t}Map{/t}:<span style="margin-left:20px;font-weight:800">{$map}</span> <span style="color:#777;font-style:italic;display:none" id="map_modified_{$id}">({t}modified{/t})</span></td>
				<td colspan="2">  
				<div class="buttons small">
					<button style="display:none" onClick="show_new_export_map('{$id}')"  id="dialog_export_new_map_{$id}">{t}New map{/t}</button> 
					<button onClick="show_export_map_library('{$id}')" id="dialog_export_map_library_{$id}">{t}Maps{/t}</button>
					<button onClick="show_export_map_fields('{$id}')" id="dialog_export_map_fields_{$id}">{t}Fields{/t}</button> 

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
			<tr style="border-bottom:1px solid #ccc">
				<td>{$export_field.label}</td>
				<td><img class="map_field_{$id}" name="{$export_field.name}" id="export_customer_field_{$field_id}" onClick="update_map_field(this,'{$id}')" style="height:16px"  {if $export_field.checked==1}checked=1 ovalue=1  src="art/icons/checkbox_checked.png"  {else} checked=0 ovalue=0  src="art/icons/checkbox_unchecked.png" {/if} /></td>
				{if $smarty.foreach.fields.first} 
				<td style="width:150px" rowspan="{$number_export_customer_fields}"> 
				<table  style="width:100%" border=0>
					<tr  id="field_map_buttons_{$id}" style="display:none">
						<td> 
						<div class="buttons small">
													<button onClick="reset_export_fields('{$id}')" id="export_fields_reset_{$id}" class="disabled">{t}Reset{/t}</button>

							<button>{t}Save As{/t}</button> 

							<button style="{if $is_map_default}display:none{/if}">{t}Save{/t}</button> 
						</div>
						</td>
						
					</tr>
					<tr id="field_map_save_as_{$id}" style="display:none">
						<td>{t}Save map as:{/t} 
						<div class="buttons small left">
							<input style="width:100px;float:left;margin-right:3px" id="save_as_map" value="" />
							<button>{t}Save{/t}</button>
						</div>
						</td>
						
					</tr>
				</table>
				</td>
				{else}
				<td></td>
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
