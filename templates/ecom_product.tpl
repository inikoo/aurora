<div id="product_bd" style="padding:5px 20px 15px 20px;clear:both">
	<div class="content">
		<div class="product" stxyle="border:1px solid #ccc;background-color:#fff">
			<div class="images" style="float:left;width:310px;">
				<div style="border:1px solid #ccc;background:#FFF">
					<div class="wraptocenter">
						<img src="{$product.img}"> 
					</div>
				</div>
			</div>
			<div class="information">
				<h1 style="padding-top:5px;margin:2px 0;font-size:190%">
					{$product.name}
				</h1>
				<div class="highlight_box">
					<div style="float:right;margin-right:4px">
						{t}Product code{/t}: <span class="code">{$product.code}</span> 
					</div>
					{$product.price}
					{$product.button_only}
				</div>
				<h3>
					{$product.rrp} 
				</h3>
				<div style="margin-left:15px">
					
				</div>
				<div class="product_long_descriptions">
					{$product.description} 
				</div>
			</div>
			<div class="side_bar">
				<table id="specs" border=0>


			{*}
			{if $product.package_weight!=''}<tr><td class="icon"><img src="art/icons/weight_package.png"  alt='' title="{t}Package gross weight{/t}"></td><td>{$product.package_weight}</td></tr>{/if}
			{*}
				{if $product.unit_weight!='' or $product.unit_dimensions!=''}					
					<tr class="title"><td colspan="2" >{t}Unit{/t} ({$product.units}/{t}Outer{/t}):
				{/if}

				{if $product.unit_weight!=''}<tr><td class="icon"></td><td>{$product.unit_weight} <img style="position:relative;top:4px" src="art/icons/weight.png"  alt='' title="{t}Net weight{/t}"></td></tr>{/if}
				{if $product.unit_dimensions!=''}<tr><td class="icon"></td><td>{$product.unit_dimensions}</td></tr>{/if}
				{if $product.ingrediens!=''}<tr class="title"><td colspan="2" >{t}Materials/Ingredients{/t}:</td></tr><tr><td colspan="2" style="font-size:90%">{$product.ingrediens}</td></tr>{/if}
				{if $product.origin!=''}<tr class="title"><td colspan="2" >{t}Origin{/t}:</td></tr><tr><td colspan="2" style="font-size:90%">{$product.origin}</td></tr>{/if}
			</table>
		</div>

	</div>
</div>
<div style="clear:both">
</div>
</div>
