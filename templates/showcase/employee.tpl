<div class="name_and_categories" >

<span class="strong"><span class="Staff_Name">{$employee->get('Name')}</span> </span>


</div>


<div class="asset_container" >


	<div class="block picture">
		
		<div class="data_container">
			{assign "image_key" $employee->get_main_image_key()} 
			<div id="main_image" class="wraptocenter main_image {if $image_key==''}hide{/if}">
				<img src="/{if $image_key}image_root.php?id={$image_key}&amp;size=small{else}art/nopic.png{/if}"> </span> 
			</div>
			{include file='upload_main_image.tpl' object='Employee' key=$employee->id class="{if $image_key!=''}hide{/if}"} 
		</div>
		
	</div>
	<div class="block info">
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
	<div style="clear:both"></div>
</div>



<script>

function show_images_tab() {
	$('#maintabs #tab_employee\\.images').removeClass('hide')
	change_tab('employee.images')
}



</script>