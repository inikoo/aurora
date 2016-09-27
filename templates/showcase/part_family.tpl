<div class="asset_profile container" >

	<div id="asset_data">
		<div class="data_container">
			
			<div class="data_field" >
				<h1 class="Category_Label">{$category->get('Label')}</h1>
			</div>
			
		</div>
		<div class="data_container">
			
			
		</div>
		<div style="clear:both">
		</div>
		<div class="data_container">
			
			
			{assign "image_key" $category->get_main_image_key()}
			<div id="main_image" class="wraptocenter main_image {if $image_key==''}hide{/if}" >	
				<img src="/{if $image_key}image_root.php?id={$image_key}&size=small{else}art/nopic.png{/if}"  >
				</span>
			</div>	
			{include file='upload_main_image.tpl' object='Category'  key=$category->id class="{if $image_key!=''}hide{/if}"}
		</div>
		{include file='sticky_note.tpl' object='Category'  key=$category->id sticky_note_field='Category_Sticky_Note' _object=$category}

	
		
		
		<div style="clear:both">
		</div>
	</div>
	<div id="info">
		<div id="overviews">
			<table border="0" class="overview" style="">
				<tr  class="main">
					<td >{t}Sales{/t}</td>
					<td class="aright">{$category->get('1 Year Acc Invoiced Amount')} </td>
				</tr>
				
				
			</table>
			
		</div>
	</div>
	<div style="clear:both">
	</div>
</div>


<script>

function show_images_tab() {
	change_tab('category.images')
}


</script>