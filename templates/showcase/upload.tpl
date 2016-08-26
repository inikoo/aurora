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
					<td class="label">{t}File{/t}</td>
					<td>{$upload->get('Upload File Name')} ({$upload->get('File Size')})</td>
				</tr>
				<tr>
					<td class="label">{t}State{/t}</td>
					<td class="Upload_State">{$upload->get('State')}</td>
				</tr>
				<tr>
					<td class="label">{t}Date{/t}</td>
					<td class="Upload_Date">{$upload->get('Date')}</td>
				</tr>
			</table>
		</div>
		<div style="clear:both">
		</div>
		<div style="clear:both">
		</div>
	</div>
	<div style="margin-top:3px;max-width:370px;float:right;margin-right:60px">

{if $upload->get('Update Type')=='NewObjects'}

<table>
<tr>
<td  rowspan="2" style="border:1px solid #ccc;padding:10px 20px;font-size:200%"  class="Upload_Records" >{$upload->get('Records')}</td>
<td class="Upload_OK success" style="border:1px solid #ccc;padding:10px 20px">{$upload->get('OK')}</td>  </tr>
<tr>
<td  class="Upload_Errors error" style="border:1px solid #ccc;padding:10px 20px">{$upload->get('Errors')}</td>
</tr>
</table>
{else}
<table>
<tr>
<td  rowspan="4" style="border:1px solid #ccc;padding:10px 20px;font-size:200%"  class="Upload_Records" >{$upload->get('Records')}</td>
<td class="Upload_OK success" style="border:1px solid #ccc;padding:10px 20px">{$upload->get('OK')}</td>  </tr>
<tr>
<td  class="Upload_No_Change very_discreet" style="border:1px solid #ccc;padding:10px 20px">{$upload->get('No Change')}</td>
</tr>
<tr>
<td  class="Upload_Warnings warning" style="border:1px solid #ccc;padding:10px 20px">{$upload->get('Warnings')}</td>
</tr>
<tr>
<td  class="Upload_Errors error" style="border:1px solid #ccc;padding:10px 20px">{$upload->get('Errors')}</td>
</tr>
</table>
{/if}


	</div>
	<div style="clear:both">
	</div>
</div>

<script>
(function() {
    // do some stuff
    var refreshIntervalId = setInterval(reload_upload_data, 1000);

    function reload_upload_data() {

        var request = '/ar_upload.php?tipo=get_data&object=Upload&key={$upload->id}'

        //  console.log(request)
        $.getJSON(request, function(data) {

            if (data.state == 200) {

                if (data.upload.state == 'Finished' || data.upload.state == 'Cancelled') {
                    clearInterval(refreshIntervalId)
                }

                for (var key in data.upload.class_html) {
                    $('.' + key).html(data.upload.class_html[key])
                }

                if(state.tab=='upload.records'){
                     rows.fetch({
                reset: true
            });
                }

            } else {
                clearInterval(refreshIntervalId)
            }
        })

    }

})();


</script>