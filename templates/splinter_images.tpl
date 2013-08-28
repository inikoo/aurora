
<div id="images" class="show_images" principal="{$parent->get_main_image_key()}">
	{foreach from=$parent->get_images_slidesshow() item=image name=foo} 
	<div id="image_container{$image.id}"  class="image" image_id="{$image.id}" is_principal="{$image.is_principal}" style="{if $image.ratio>1}height:250px{/if}">
		<div class="image_name" id="image_name{$image.id}"   style="font-size:70%;">
			 {$image.width}x{$image.height} ({$image.size}) 
		</div>
		<img class="principal" id="img_principal{$image.id}" style="{if $image.is_principal=='Yes'}{else}display:none{/if}" title="{t}Main Image{/t}" src="art/icons/bullet_star.png"> 
		<img class="picture" src="{$image.small_url}" style="{if $image.ratio<1}{if $image.height>250}height:250px{/if}{else}{if $image.width>250}width:250px{/if}{/if}" /> 
		

	
		<div class="caption" id="caption{$image.id}" >
		{$image.caption}</div> 
		
	</div>
	{/foreach} 
	<div id="image_footer" style="clear:both">
	</div>
</div>
