<div id="dialog_note" style="padding:20px 20px 10px 20px;">
	<div id="note_msg">
	</div>
	<table>
		<tr>
			<td> 
			<div class="buttons" id="note_type" prefix="note_type_" value="deletable">
				<button id="note_type_permanent" onclick="radio_changed(this)" name="permanent">{t}Permanent{/t}</button> <button class="selected" id="note_type_deletable" onclick="radio_changed(this)" name="deletable">{t}Deletable{/t}</button> 
			</div>
			</td>
		</tr>
		<tr>
			<td> <textarea style="width:200px;height:100px" id="note_input" onkeyup="change(event,this,'note')"></textarea> </td>
		</tr>
		<tr>
			<td> 
			<div class="buttons">
				<button onclick="save('note')" id="note_save" class="positive disabled">{t}Save{/t}</button> <button onclick="close_dialog('note')" class="negative">{t}Cancel{/t}</button> 
			</div>
			</td>
		</tr>
	</table>
</div>
<div id="dialog_edit_note" style="position:absolute;top-100px;left:-500px">
	<div id="edit_note_msg">
	</div>
	<input type="hidden" value="" id="edit_note_history_key"> 
	<input type="hidden" value="" id="record_index"> 
	<table style="padding:10px;margin:10px">
		<tr>
			<td colspan="2"> 
			<div id="edit_note_date" class="buttons left" prefix="note_date_" value="keep_date">
				<button class="selected" id="note_date_keep_date" onclick="radio_changed(this)" name="keep_date">{t}Keep Date{/t}</button> <button id="note_date_update_date" onclick="radio_changed(this)" name="update_date">{t}Update Date{/t}</button> 
			</div>
			</td>
		</tr>
		<tr>
			<td colspan="2"> <textarea style="width:200px;height:100px" id="edit_note_input" onkeyup="change(event,this,'edit_note')"></textarea> </td>
		</tr>
		<tr>
			<td colspan="2"> 
			<div class="buttons">
				<button class="positive" onclick="save('edit_note')" id="edit_note_save">{t}Save{/t}</button> <button class="negative" onclick="close_dialog('edit_note')">{t}Cancel{/t}</button> 
			</div>
			</td>
		</tr>
	</table>
</div>
<div id="dialog_sticky_note" style="padding:20px 20px 0px 20px;width:340px">
	<div id="sticky_note_msg">
	</div>
	<table>
		<tr>
			<td> <textarea style="width:330px;height:125px" id="sticky_note_input" onkeyup="change(event,this,'sticky_note')">{$sticky_note}</textarea> </td>
		</tr>
		<tr>
			<td> 
			<div class="buttons">
				<button class="positive" onclick="save('sticky_note')">{t}Save{/t}</button> <button class="negative" onclick="close_dialog('sticky_note')">{t}Cancel{/t}</button> 
			</div>
			</td>
		</tr>
	</table>
</div>
<div id="dialog_attach" style="padding:20px 20px 0px 20px">
	<div id="attach_msg" class="error" style="width:300px;padding:0 10px 10px 10px ">
	</div>
	
		<table>
			<form enctype="multipart/form-data" method="post" id="upload_attach_form">

			<tr>
				<td>{t}File{/t}:</td>
				<td> 
				<input id="upload_attach_file" style="border:1px solid #ddd;" type="file" name="attach" />
				</td>
			</tr>
			<tr>
				<td>{t}Caption{/t}:</td>
				<td> 
				<input style="width:100%" value='' id='attachment_caption' name="caption"> </td>
			</tr>
			</form>
			<tr>
				<td colspan="2"> 
				<div class="buttons">
					<button id="save_attach_button" class="positive disabled" >{t}Upload{/t}</button> <button onclick="close_dialog('attach')" class="negative">{t}Cancel{/t}</button><br />
				</div>
				</td>
			</tr>
		</table>
	
</div>
<div id="dialog_delete_history_record_from_list" style="padding:5px 10px 10px 10px;">
	<input type="hidden" id="delete_from_list_history_key" value=''> 
	<input type="hidden" id="delete_from_list_record_index" value=''> 
	<input type="hidden" id="delete_from_list_table_id" value=''> 
	
	
	<h2 style="padding-top:0px">
		{t}Delete Record{/t} <span class="id" id="delete_from_list_category_code"></span> 
	</h2>
	<p>
		{t}This operation cannot be undone{/t}.<br> {t}Would you like to proceed?{/t} 
	</p>
	<div id='delete_history_record_msg_from_list'>
	</div>
	<div style="display:none" id="deleting_from_list">
		<img src="art/loading.gif" alt=""> {t}Deleting category, wait please{/t} 
	</div>
	<div id="delete_history_record_buttons_from_list" class="buttons">
		<button id="save_delete_history_record_from_list" onclick="save_delete_history_record_from_list()" class="positive">{t}Yes, delete it!{/t}</button> <button onclick="cancel_delete_history_record_from_list()" id="cancel_delete_history_record_from_list" class="negative">{t}No i dont want to delete it{/t}</button> 
	</div>
</div>
