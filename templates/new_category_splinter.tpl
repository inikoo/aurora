<div id="dialog_new_category" style="padding:10px">
	<input id="new_category_parent_key" value="{$category_key}" type="hidden" />
	<input id="new_category_store_key" value="{if isset($store_id)}{$store_id}{/if}" type="hidden" />
	<input id="new_category_warehouse_key" value="{if isset($warehouse_id)}{$warehouse_id}{/if}" type="hidden" />
	<input id="new_category_subject" value="{$subject}" type="hidden" />
	<table style="margin:10px" class="edit">
		<tr class="title">
			<td colspan="2">{t}New subcategory{/t} <span style="display:none" id="new_category_other_title">({t}Other{/t})</span></td>
		</tr>
		<tr id="new_category_no_code_msg" style="display:none">
			<td colspan="2">
			<div class="error_message">
				{t}Category Code Required{/t}
			</div>
			</td>
		</tr>
		<tr id="new_category_no_label_msg" style="display:none">
			<td colspan="2">
			<div class="error_message">
				{t}Category Label Required{/t}
			</div>
			</td>
		</tr>
		<tr id="new_category_msg" style="display:none">
			<td colspan="2">
			<div id="new_category_msg_text" class="error_message">
			</div>
			</td>
		</tr>
		<tr style="height:10px">
			<td colspan="2"></td>
		</tr>
		<tr id="is_category_other_tr" style="{if $category->get('Category Can Have Other')=='No' or  $category->get('Category Children Other')=='Yes'}display:none{/if}">
			<input id="new_category_other" value="No" type="hidden" />

		<td colspan="2"> 
			<div class="buttons small">
				<button id="set_new_category_as_other" onclick="set_new_category_as_other()">{t}Set as other{/t}</button> <button style="display:none" id="set_new_category_as_normal" onclick="set_new_category_as_normal()">{t}Set as normal{/t}</button> 
			</div>
			</td>
			</td>
		</tr>
		<tr>
			<td class="label">{t}Code{/t}:</td>
			<td> 
			<input id="new_category_code" />
			</td>
		</tr>
		<tr id="new_category_label_tr">
			<td class="label">{t}Label{/t}:</td>
			<td> 
			<input id="new_category_label" />
			</td>
		</tr>
		<tr style="height:10px">
			<td colspan="2"></td>
		</tr>
		<tr>
			<td colspan="2"> 
			<div class="buttons">
				<button id="new_category_save" class="positive">{t}Save{/t}</button> <button id="new_category_cancel" class="negative">{t}Cancel{/t}</button> 
			</div>
			</td>
		</tr>
	</table>
</div>
