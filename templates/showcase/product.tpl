
<div class="asset_profile container" >


	<div id="asset_data">
		<div class="data_container">
			{assign "family" $product->get('Family')}
			<div class="data_field small {if !$family}hide{/if}" >
			    <input type="hidden" id="Product_Family_Key" value="{if $family}{$family->id}{/if}" />
				<i class="fa fa-pagelines" aria-hidden="true"></i> <span   onClick="category_view()" class="button id bold Product_Family_Code">{if $family}{$family->get('Code')}{/if}</span>, <span class="Product_Family_Label">{if $family}{$family->get('Label')}{/if}</span>
			</div>
			<div class="data_field small discreet {if $family}hide{/if}" >
				<i class="fa fa-pagelines" aria-hidden="true"></i> <span class="button italic" >{t}Not set{/t}</span>
			</div>
			
			<div class="data_field" >
				<h1>
				<span class="Product_Units_Per_Case">{$product->get('Units Per Case')}</span>x  <span class="Product_Name">{$product->get('Name')}</span> </h1>
			</div>
			
		</div>
		<div class="data_container">
			
			
		</div>
		<div style="clear:both">
		</div>
		
		<div class="data_container">
			
			
			{assign "image_key" $product->get_main_image_key()}
			<div id="main_image" class="wraptocenter main_image {if $image_key==''}hide{/if}" >	
				<img src="/{if $image_key}image_root.php?id={$image_key}&size=small{else}art/nopic.png{/if}"  >
				</span>
			</div>	
			{include file='upload_main_image.tpl' object='Product'  key=$product->id class="{if $image_key!=''}hide{/if}"}
		</div>
		
	
		{include file='sticky_note.tpl' object='Category'  key=$product->id sticky_note_field='Store_Product_Sticky_Note' _object=$product}

	
		
		<div style="clear:both">
		</div>
	</div>
	<div id="info" >
		<div id="overviews">
		
		<table border="0" class="overview">
 
				<tr>
					<td>{t}From{/t}:</td>
					<td class="aright Product_Web_State">{$product->get('Valid From')}</td>
				</tr>
				
			</table>
		
			<table border="0" class="overview" style="">
				<tr id="account_balance_tr" class="main">
					<td >{t}Price{/t}</td>
					<td class="aright highlight Product_Price">{$product->get('Price')} </td>
				</tr>
				
				
			</table>
			<table border="0" class="overview">
 
				<tr>
					<td>{t}Stock{/t}:</td>
					<td class="aright Product_Availability" >{$product->get('Availability')}</td>
				</tr>
				
			</table>
			
			<table border="0" class="overview">
 
				<tr>
					<td>{t}Web status{/t}:</td>
					<td class="aright Product_Web_State">{$product->get('Web State')}</td>
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