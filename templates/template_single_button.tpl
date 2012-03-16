{literal}
<script type="text/javascript" charset="utf-8">
			$(document).ready(function(){
				$("area[rel^='prettyPhoto']").prettyPhoto();
				
				$(".gallery:first a[rel^='prettyPhoto']").prettyPhoto({animation_speed:'normal',theme:'light_square',slideshow:3000, autoplay_slideshow: false});
				$(".gallery:gt(0) a[rel^='prettyPhoto']").prettyPhoto({animation_speed:'fast',slideshow:10000, hideflash: true});
		
				$("#custom_content a[rel^='prettyPhoto']:first").prettyPhoto({
					custom_markup: '<div id="map_canvas" style="width:260px; height:265px"></div>',
					changepicturecallback: function(){ initialize(); }
				});

				$("#custom_content a[rel^='prettyPhoto']:last").prettyPhoto({
					custom_markup: '<div id="bsap_1259344" class="bsarocks bsap_d49a0984d0f377271ccbf01a33f2b6d6"></div><div id="bsap_1237859" class="bsarocks bsap_d49a0984d0f377271ccbf01a33f2b6d6" style="height:260px"></div><div id="bsap_1251710" class="bsarocks bsap_d49a0984d0f377271ccbf01a33f2b6d6"></div>',
					changepicturecallback: function(){ _bsap.exec(); }
				});
			});
			</script>


{/literal}

<style>
ul li { display: inline; }
</style>


<div style="float:left; width:950px; margin: 10px 10px 10px 10px; border:solid 1px; border-color: #c4c4c4">
Description	
</div>

<div style="clear:both"></div>

<div style="float:left; width: 950px; margin: 10px 10px 10px 10px; border:solid 1px;border-color: #c4c4c4">
	

		{foreach from=$page->get_all_products() item=product name=foo}
		<div style="display:inline; float:left; margin:5px 5px 5px 5px; align:center;">
			{$page->display_product_image($product.code)}</br>
			{$page->display_button($product.code)}

		</div>
		{/foreach} 



</div>
