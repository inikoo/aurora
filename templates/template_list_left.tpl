{literal}
			<script type="text/javascript" charset="utf-8">
			$(document).ready(function(){
				$("area[rel^='prettyPhoto']").prettyPhoto();
				
				$(".gallery:first a[rel^='prettyPhoto']").prettyPhoto({animation_speed:'normal',theme:'light_square',slideshow:3000, autoplay_slideshow: true,default_width: 344});
				//$(".gallery:gt(0) a[rel^='prettyPhoto']").prettyPhoto({animation_speed:'fast',slideshow:10000, hideflash: true});
		
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

<div style="float:left; width: 300px; margin: 10px 10px 10px 10px; border:solid 1px;border-color: #c4c4c4">
	{$page->display_list()} 
</div>

<div style="float:left; width:600px; margin: 10px 10px 10px 40px">

<ul class="gallery clearfix">	
	{foreach from=$page->get_all_products() item=image name=foo}
		<li>
		<table style="display:inline"><tr><td>
			<a  style="border:none;text-decoration:none" href="{$image.normal_url}" rel="prettyPhoto[gallery1]" >
			<img style="float:left;border:0px solid#ccc;padding:2px;margin:2px;cursor:pointer;width:150px" src="{$image.small_url}" alt="{$image.code}" />
			</a></td></tr>
			<!--<ul style="display:table-row"><li style="display:table-row">{$image.code}</li></ul>-->
			<tr><td align="center">{$image.code}</td></tr>
		</table>
		</li>
	{/foreach} 
</ul>


</div>

