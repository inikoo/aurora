 
<div id="report_config" class="export_dialog hide" style="width:400px">
	<table border=0>
		{foreach from=$stores_data item=store_field key=_key} 
		<tr class="small_row">
			<td>{$store_field.label}</td>
			<td style="width_20" class="field_store"> <i id="field_store_{$_key}" onclick="toggle_store_field({$_key})" key="{$_key}" original_val="{if $store_field.checked }fa-check-square-o{else}fa-square-o{/if}" class="button fa {if $store_field.checked }fa-check-square-o{else}fa-square-o{/if}"></i> </td>
		</tr>
		{/foreach} 
		
		<tr><td  class="aright padding_right_10  "><span id="report_config_save_label" onclick="apply_changes()"class="valid save hide">{t}Apply changes{/t}</span></td>
		<td >
		<i onclick="apply_changes()" id="report_config_save" class="fa fa-cloud-upload disabled"></i>
		</td>
		</tr>
		
	</table>
	
	

</div>
