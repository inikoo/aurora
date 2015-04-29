<div id="bd" style="padding:20px 15px">

	<div id="products" class="content">
		
		
	
		{$page->get_primary_content()}
		
	    {foreach from=$_products item=product} 
		<div class="block four product_showcase {if $product.col==1}first{/if}" style="margin-bottom:20px;position:relative">
			{*}<a href="product.php?id={$product.page_id}"><img class="more_info" src="art/moreinfo_corner{$product.col}.png"> </a>{*}
			<a href="page.php?id={$product.page_id}"><img class="more_info" src="art/moreinfo_corner{$product.col}.png"> </a>


			<div class="wraptocenter">
			<img src="{$product.img}"/> 
			</div>
			
			{$product.button}
			
		</div>
		{/foreach} 
	</div>
	
	<div style="clear:both">
	</div>
</div>
