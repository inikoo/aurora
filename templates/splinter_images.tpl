
<div id="images" class="show_images" principal="{$parent->get_main_image_key()}">
	{foreach from=$parent->get_images_slidesshow() item=image name=foo} 
	<div id="image_container{$image.id}"  class="image" image_id="{$image.id}" is_principal="{$image.is_principal}" style="{if $image.ratio>1}height:250px{/if}">
		<div class="image_name" id="image_name{$image.id}"   style="font-size:70%;">
			 {$image.width}x{$image.height} ({$image.size})
		</div>
		<img class="picture" src="{$image.small_url}" style="{if $image.ratio<1}height:250px{else}width:250px{/if}" /> 
		<div class="operations">
			<img id="img_principal{$image.id}" style="{if $image.is_principal=='Yes'}{else}display:none{/if}" title="{t}Main Image{/t}" src="art/icons/bullet_star.png"> 
			<img id="img_set_principal{$image.id}" style="{if $image.is_principal=='Yes'}display:none{else}{/if}" onclick="set_image_as_principal(this)" title="{t}Set as the principal image{/t}" image_id="{$image.id}" src="art/icons/bullet_gray_star.png">
			<img id="img_edit_caption{$image.id}" onclick="edit_caption(this)" src="art/icons/caption.gif" alt="{t}Edit Caption{/t}" title="{t}Edit Caption{/t}"> 
			<img id="img_save_caption{$image.id}" style="display:none" onclick="save_caption(this)" src="art/icons/bullet_gray_disk.png" alt="{t}Save Caption{/t}" title="{t}Save Caption{/t}">
			<img id="img_reset_caption{$image.id}" style="display:none" onclick="reset_caption(this)" src="art/icons/bullet_come.png" alt="{t}Reset Caption{/t}" title="{t}Reset Caption{/t}"> 
		</div>
		<span class="caption" id="caption{$image.id}">{$image.caption}</span> <textarea class="edit_caption" style="display:none" onkeyup="caption_changed(this)" id="edit_caption{$image.id}" image_id="{$image.id}" ovalue="{$image.caption}">{$image.caption}</textarea> 
	</div>
	{/foreach} 
	<div id="image_footer" style="clear:both">
	</div>
</div>
