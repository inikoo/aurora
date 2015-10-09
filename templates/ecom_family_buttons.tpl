<div id="family_body">
	{if $family->data['Product Family Description']!=''} 
	<div class="description_block">
		<img id="main_image" class="image" src="{$family->get('Product Family Main Image')}" /> 
		<div class="content">
			<h1>
				{$family->get('Product Family Code')}
			</h1>
			<h2>
				{$family->get('Product Family Name')}
			</h2>
			<div class="description">
				{$family->get('Product Family Description')} 
			</div>
		</div>
		<div style="clear:both">
		</div>
	</div>
	
	{/if} 
	<div id="products" class="content" style="clear:both">
		{foreach from=$_products item=product} 
		<div class="block four product_showcase {if $product.col==1}first{/if}" style="margin-bottom:20px;position:relative">
			
			
			{assign var="corner_icon_default" value="art/moreinfo_corner{$product.col}.png"}
			{assign var="corner_icon" value="art/moreinfo_corner{$product.col}_{$site->get('Site Locale')}.png"}

			<a href="page.php?id={$product.page_id}"><img class="more_info" src="{if  file_exists($corner_icon)}{$corner_icon}{else}{$corner_icon_default}{/if}"> </a> 
			<div class="wraptocenter">
				<img src="{$product.img}" /> 
			</div>
			
			{$product.button}  
		</div>
		{/foreach} 
	</div>
	<div style="clear:both">
	</div>
</div>
