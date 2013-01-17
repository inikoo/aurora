<div id="dialog_note" style="padding:20px 20px 10px 20px">
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
	<div id="attach_msg">
	</div>
	
	<form enctype="multipart/form-data" method="post" id="upload_attach_form">
		<table>
			<tr>
				<td>{t}File{/t}:</td>
				<td> 
				<input id="upload_attach_file" style="border:1px solid #ddd;" type="file" name="attach" />
				</td>
			</tr>
			<tr>
				<td>{t}Caption{/t}</td>
				<td> 
				<input style="width:100%" value='' id='attachment_caption' name="caption"> </td>
			</tr>
			<tr>
				<td colspan="2"> 
				<div class="buttons">
					<button class="positive" onclick="save('attach')">{t}Upload{/t}</button> <button onclick="close_dialog('attach')" class="negative">{t}Cancel{/t}</button><br />
				</div>
				</td>
			</tr>
		</table>
	</form>
</div>
