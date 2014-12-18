<div id="bd" style="padding:20px 15px">

	<div id="products" class="content" style="xwidth:780px;">
		
		
	
		{$page->get_primary_content()}
		
	    {foreach from=$_products item=product} 
		<div class="block four product_showcase {if $product.col==1}first{/if}" style="margin-bottom:20px;position:relative">
			<a href="product.php?code={$product.code}&parent={$page->get('Page Code')}"><img class="more_info" src="art/moreinfo_corner{$product.col}.png"> </a>
			
			<div class="wraptocenter">
			<img src="{$product.img}" style="	"> 
			</div>
			
			{$product.button}
			
		</div>
		{/foreach} 
	</div>
	
	<div style="clear:both">
	</div>
</div>
