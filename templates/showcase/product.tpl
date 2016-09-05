
<div class="asset_profile container" >


	<div id="asset_data">
	<div class="data_container">
		<ul class="tags Categories">
			{foreach from=$product->get_category_data() item=item key=key} 
			<li><span class="button" onclick="change_view('category/{$item.category_key}')" title="{$item.label}">{$item.code}</span></li>
			{/foreach} 
		</ul>
		<div class="data_field" style="clear:both">
			<h1 style=" max-width: 600px;">
				<span class="Product_Units_Per_Case">{$product->get('Units Per Case')}</span>x <span class="Product_Name">{$product->get('Name')}</span> 
			</h1>
		</div>
	</div>
	
	<div style="clear:both">
	</div>
	<div class="data_container">
		{assign "image_key" $product->get_main_image_key()} 
		<div id="main_image" class="wraptocenter main_image {if $image_key==''}hide{/if}">
			<img src="/{if $image_key}image_root.php?id={$image_key}&amp;size=small{else}art/nopic.png{/if}"> </span> 
		</div>
		{include file='upload_main_image.tpl' object='Product' key=$product->id class="{if $image_key!=''}hide{/if}"} 
	</div>
	{include file='sticky_note.tpl' object='Category' key=$product->id sticky_note_field='Store_Product_Sticky_Note' _object=$product} 
	<div style="clear:both">
	</div>
</div>

	<div id="info" >
		<div id="overviews">
		
		<table border="0" class="overview">
 <tr>
					<td class=" Product_Status" title="{t}Status{/t}">{$product->get('Status')}</td>
					<td class="aright Product_Web_State" title="{t}Web state{/t}">{$product->get('Web State')}</td>
				</tr>
 
				
				
			</table>
		
		<table border="0" class="overview {if $product->get('Product Status')=='Discontinued'}super_discreet{/if} {if $product->get('Product Status')=='Discontinued' and $product->get('Product Availability')==0}hide{/if}">
 
				<tr id="stock_available"  class="{if $product->get('Product Number of Parts')==0}hide{/if}">
					<td>{t}Stock available{/t}:</td>
					<td class="aright Product_Availability" >{$product->get('Availability')}</td>
				</tr>
				
			</table>
			
				<table border="0" class="overview" style="">
				<tr  class="main">
					<td >{t}Price{/t}</td>
					<td class="aright  Product_Price">{$product->get('Price')} </td>
				</tr>
				<tr id="rrp" class="{if $product->get('Product RRP')==''}hide{/if}">
					<td >{t}RRP{/t}</td>
					<td class="aright  Product_Unit_RRP">{$product->get('Unit RRP')} </td>
				</tr>
				
			</table>
			
		
		<table border="0" class="overview">
 
				<tr>
					<td>{t}From{/t}:</td>
					<td class="aright Product_Valid_From">{$product->get('Valid From')}</td>
				</tr>
				<tr id="valid_to" class="{if $product->get('Product Status')!='Discontinued'}hide{/if}">
					<td>{t}To{/t}:</td>
					<td class="aright Product_Valid_To">{$product->get('Valid To')}</td>
				</tr>
				<tr id="suspended_date" class="{if $product->get('Product Status')!='Suspended'}hide{/if}">
					<td>{t}To{/t}:</td>
					<td class="aright Product_Valid_To">{$product->get('Valid To')}</td>
				</tr>
				
			</table>
		
		
			
			
			
			
		
		</div>
	</div>
	<div style="clear:both">
	</div>
</div>


<script>

function category_view(){
    change_view('products/{$product->get('Product Store Key')}/category/'+$('#Product_Family_Key').val())
}



</script>