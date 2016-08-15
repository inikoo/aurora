{assign "subcategories_status" $category->get_subcategories_status_numbers('Formatted')}
<div class="asset_profile container" >

	
	<div id="info">
		<div id="overviews">
			<table border="0" class="overview" style="">
				<tr  class="top">
					<td class="aright">{t}Active{/t}</td>
					<td class="aright">{t}Discontinued{/t}</td>
					<td style="width:40%"class="aright">{t}Total{/t}</td>
				</tr>
					<tr  class="main">
					<td class="aright">{$subcategories_status['InUse']} </td>
					<td class="aright">{$subcategories_status['NotInUse']} </td>
					<td class="aright">{$category->get('Children')} </td>
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