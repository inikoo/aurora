
<div class="asset_profile container">
	<div id="asset_data">
		<div class="data_container">
			{assign "family" $employee->get('Family')} 
			<div class="data_field">
				<h1>
					<span class="Product_Name">{$employee->get('Name')}</span> 
				</h1>
			</div>
		</div>
		<div class="data_container">
		</div>
		<div style="clear:both">
		</div>
		<div class="data_container">
			<div style="min-height:80px;float:left;width:28px">
				<i onclick="show_images_tab()" class="fa fa-camera-retro button"></i> 
			</div>
			{assign "image_key" $employee->get_main_image_key()} 
			<div class="wraptocenter main_image {if $image_key==''}hide{/if}">
				<img src="/{if $image_key}image_root.php?id={$image_key}&amp;size=small{else}art/nopic.png{/if}"> </span> 
			</div>
			{include file='upload_main_image.tpl' object='Employee' key=$employee->id class="{if $image_key!=''}hide{/if}"} 
		</div>
		{include file='sticky_note.tpl' object='Employee' key=$employee->id sticky_note_field='Staff_Sticky_Note' _object=$employee} 
		<div style="clear:both">
		</div>
	</div>
	<div id="info">
		<div id="overviews">
			<table border="0" class="overview" style="">
			<tr class="main {if $employee->get('Staff Currently Working')=='Yes'}hide{/if} ">
					<td class="aright title">{t}Ex-employee{/t}</td>
				</tr>
			<tr class="main {if $employee->get('Staff Currently Working')=='Yes'}hide{/if} ">
					<td class="aright ">{$employee->get('Valid From')} - {$employee->get('Valid To')}</td>
				</tr>
				<tr class="main {if $employee->get('Staff Currently Working')=='No'}hide{/if} ">
					<td class="aright  Staff_Clocking_Data">{$employee->get('Clocking Data')}</td>
				</tr>
				
				
				
			</table>
		</div>
	</div>
	<div style="clear:both">
	</div>
</div>



<script>

function show_images_tab() {
	$('#maintabs #tab_employee\\.images').removeClass('hide')
	change_tab('employee.images')
}



</script>