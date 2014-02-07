<div id="dialog_new_category" style="padding:10px;width:350px">
	<input id="new_category_store_key" value="{if isset($store_id)}{$store_id}{else}0{/if}" type="hidden" />
	<input id="new_category_warehouse_key" value="{if isset($warehouse_id)}{$warehouse_id}{else}0{/if}" type="hidden" />
	<input id="new_category_subject" value="{$subject}" type="hidden" />
	<table style="margin:10px;width:330px;margin-right:30px" class="edit">
		<tr class="title">
			<td colspan="2">{t}New category{/t}</td>
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
		<tr id="new_category_wrong_max_deep_msg" style="display:none">
			<td colspan="2"> 
			<div class="error_message">
				{t}Max Deep should be a number bigger than 1{/t} 
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
		<tr id="category_form_chooser">
			<td colspan="2"> 
			<div class="buttons left">
				<button onclick="show_simple_category_form()">{t}Simple 1 Level Category{/t}</button><button onclick="show_custom_category_form()">{t}Custom Category{/t}</button> 
			</div>
			</td>
		</tr>
		<tbody id="simple_category_form" style="display:none">
			<tr>
				<td>{t}Code{/t}:</td>
				<td> 
				<input id="new_category_code" style="width:100%" />
				</td>
			</tr>
			<tr>
				<td>{t}Label{/t}:</td>
				<td> 
				<input id="new_category_label" style="width:100%" />
				</td>
			</tr>
		</tbody>
		<tbody id="custom_category_form" style="display:none">
			<tr style="{if $subject!='Customer'}display:none{/if}">
				<td>{t}Allow Other{/t}:</td>
				<td> 
				<input id="new_category_allow_other" type="hidden" value="{if $subject=='Customer'}Yes{else}No{/if}" ovalue="{if $subject=='Customer'}Yes{else}No{/if}"/>
				<div class="buttons left small">
					<button id="set_allow_other_Yes" onclick="set_allow_other('Yes')"  class="{if $subject=='Customer'}selected{/if}"> {t}Yes{/t}</button><button id="set_allow_other_No" onclick="set_allow_other('No')" class="{if $subject!='Customer'}selected{/if}">{t}No{/t}</button> 
				</div>
				</td>
			</tr>
			<tr>
				<td>{t}Multiplicity{/t}:</td>
				<td> 
				<input id="new_category_multiplicity" type="hidden" value="No" />
				<div class="buttons left small">
					<button id="set_multiplicity_Yes" onclick="set_multiplicity('Yes')"> {t}Yes{/t}</button><button id="set_multiplicity_No" onclick="set_multiplicity('No')" class="selected">{t}No{/t}</button> 
				</div>
				</td>
			</tr>
			<tr>
				<td>{t}Max Deep{/t}:</td>
				<td> 
				<input style="width:40px;text-align:right" id="new_category_max_deep" value="2" />
				</td>
			</tr>
			<tbody id="new_category_show_options" style="display:none">
				<tr class="top" style="{if $subject!='Customer'}display:none{/if}">
					<td>{t}Show in Public Registration{/t}:</td>
					<td> 
					<input id="new_category_show_registration" type="hidden" value="No" />
					<div class="buttons left small">
						<button id="set_show_registration_Yes" onclick="set_show_registration('Yes')"> {t}Yes{/t}</button><button id="set_show_registration_No" onclick="set_show_registration('No')" class="selected">{t}No{/t}</button> 
					</div>
					</td>
				</tr>
				<tr style="{if $subject!='Customer'}display:none{/if}">
					<td>{t}Show in Public Profile{/t}:</td>
					<td> 
					<input id="new_category_show_profile" type="hidden" value="No" />
					<div class="buttons left small">
						<button id="set_show_profile_Yes" onclick="set_show_profile('Yes')"> {t}Yes{/t}</button><button id="set_show_profile_No" onclick="set_show_profile('No')" class="selected">{t}No{/t}</button> 
					</div>
					</td>
				</tr>
				<tr >
					<td>{t}Show in UI{/t}:</td>
					<td> 
					<input id="new_category_show_ui" type="hidden" value="Yes" />
					<div class="buttons left small">
						<button id="set_show_ui_Yes" onclick="set_show_ui('Yes')" class="selected"> {t}Yes{/t}</button><button id="set_show_ui_No" onclick="set_show_ui('No')" >{t}No{/t}</button> 
					</div>
					</td>
				</tr>
			</tbody>
		</tbody>
		<tbody id="new_category_save_buttons" style="display:none">
			<tr style="height:10px">
				<td colspan="2"></td>
			</tr>
			<tr>
				<td colspan="2"> 
				<div class="buttons">
					<button id="new_category_save" class=" positive">{t}Save{/t}</button> <button id="new_category_cancel" class="negative">{t}Cancel{/t}</button> 
				</div>
				</td>
			</tr>
		</tbody>
	</table>
</div>
