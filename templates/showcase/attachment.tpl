<div class="subject_profile">
	<div id="contact_data" style="float:left;width:600px">
		<div class="showcase">
			<div class="data_field" style="padding-bottom:20px">
				<span class="Public_Info">{$attachment->get('Public Info')}</span> 
			</div>
			<table class="">
				<tr>
					<td class="label">{$attachment->get_field_label('Attachment Subject Type')|capitalize}</td>
					<td class="Attachment_Subject_Type">{$attachment->get('Subject Type')}</td>
				</tr>
				<tr>
					<td class="label">{$attachment->get_field_label('Attachment Caption')|capitalize}</td>
					<td class="Attachment_Caption">{$attachment->get('Caption')}</td>
				</tr>
				<tr>
					<td class="label">{$attachment->get_field_label('Attachment File Original Name')|capitalize}</td>
					<td>{$attachment->get('File Original Name')}</td>
				</tr>
				<tr>
					<td class="label">{$attachment->get_field_label('Attachment Type')|capitalize}</td>
					<td>{$attachment->get('Type')}</td>
				</tr>
				<tr>
					<td class="label">{$attachment->get_field_label('Attachment File Size')|capitalize}</td>
					<td>{$attachment->get('File Size')}</td>
				</tr>
			</table>
		</div>
		<div style="clear:both">
		</div>
		<div style="clear:both">
		</div>
	</div>
	<div style="margin-top:3px;max-width:370px;float:right;margin-right:60px">
	<a href="/attachment.php?id={$attachment->get('Attachment Bridge Key')}"   ><img style="max-width:350px"  src="{$attachment->get('Preview')}&size=original"></a>
	</div>
	<div style="clear:both">
	</div>
</div>

	<a href="/attachment.php?id={$attachment->get('Attachment Bridge Key')}"  id="download" download hidden ></a>


<script>
$('#navigation').on('click', '#download_button', function() {

$( "#download" )[0].click();
    


});
</script>

