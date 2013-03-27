<div id="dialog_export" style="padding:15px 25px 5px 20px;;display:none">
	<table class="edit" style="width:500px" border=1>
		<tr>
			<td></td>
		</tr>
	
		<tbody id="dialog_export_form">
		<tr>
			<td colspan="3"> 
			<div class="buttons left">
				<button id="export_xls" style="width:70px"><img src="art/icons/page_excel.png" alt=""> Excel</button> <button id="export_csv" style="width:70px"><img src="art/icons/page_white_text.png" alt=""> CSV</button> 
			</div>
			</td>
		</tr>
		
		
		<tr style="height:10px">
			<td colspan="3"></td>
		</tr>
		<tr>
			<td>{t}Map{/t}:</td>
			<td colspan=2>
			<span style="float:left">Default</span>
			 
			<div class="buttons small">
				<button id="dialog_export_customers_new_map">{t}New map{/t}</button> 
				<button id="dialog_export_customers_map_library">{t}Maps{/t}</button> 

			</div>
			</td>
		</tr>
		
		</tbody>
		
		
			<tbody id="maps">
			<tr>
			<td>{t}Map Library{/t}:</td>
			<td colspan=2>
			
			 
			<div class="buttons small">
				<button id="dialog_export_customers_new_map_bis">{t}New map{/t}</button> 

			</div>
			</td>
		</tr>
		<tr>
			<td>{t}Default Map{/t}:</td>
			<td colspan>
			
			 
			<div class="buttons small left">
				<button >{t}Select{/t}</button> 

			</div>
			</td>
		
		</tr>
		</tbody>
		
		<tbody>
		<tr class="title">
			<td colspan=2></td>
		</tr>
		{foreach from=$export_customer_fields item=export_field name=fields}
		<tr>
		<td>{$export_field.label}</td><td><img style="height:16px" src="art/icons/{if $export_field.checked}checkbox_checked.png{else}checkbox_unchecked.png{/if}"></td>
		{if $smarty.foreach.fields.first}
		<td rowspan={$number_export_customer_fields}>
		<table>
		<tr>
		<td>
		<div class="buttons small left"><button>{t}Save{/t}</button> <button>{t}Save As{/t}</button> <button>{t}Reset{/t}</button></div></td>
		</td>
		</tr>
		<tr>
		<td>
		{t}Save map as:{/t}  <div class="buttons small left"><input style="width:100px;float:left;margin-right:3px" id="save_as_map" value=""/> <button>{t}Save{/t}</button></div></td>
		</td>
		</tr>
		</table>
		
		{/if}
		</tr>
		
		{/foreach}
		</tbody>
		
	
		
	</table>
</div>