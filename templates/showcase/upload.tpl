<div class="subject_profile">
	<div id="contact_data" style="float:left;width:600px">
		<div class="showcase">
			
			<table class="">
				<tr>
					<td class="label">{$upload->get_field_label('Upload Object')|capitalize}</td>
					<td class="Attachment_Subject_Type">{$upload->get('Object')}</td>
				</tr>
				
				<tr>
					<td class="label">{t}Uploaded by{/t}</td>
					<td>{$upload->get('User Alias')} </td>
				</tr>
				<tr>
					<td class="label">{t}Date{/t}</td>
					<td>{$upload->get('Date')}</td>
				</tr>
				<tr>
					<td class="label">{t}File{/t}</td>
					<td>{$upload->get('Upload File Name')} ({$upload->get('File Size')})</td>
				</tr>
				
			</table>
		</div>
		<div style="clear:both">
		</div>
		<div style="clear:both">
		</div>
	</div>
	<div style="margin-top:3px;max-width:370px;float:right;margin-right:60px">

<table>
<tr><td  rowspan="2" style="border:1px solid #ccc;padding:10px 20px;font-size:200%">{$upload->get('Records')}</td><td class="success" style="border:1px solid #ccc;padding:10px 20px">{$upload->get('OK')}</td>  </tr>
<tr><td  class="error" style="border:1px solid #ccc;padding:10px 20px">{$upload->get('Errors')}</td></tr>
</table>
	</div>
	<div style="clear:both">
	</div>
</div>
