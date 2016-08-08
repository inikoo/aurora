
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
			<div style="min-height:80px;float:left;width:28px">
				<i class="fa fa-camera-retro"></i> 
			</div>
			
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
			
			{if $product->get('Customer Send Newsletter')=='No' or $product->get('Customer Send Email Marketing')=='No' or $product->get('Customer Send Postal Marketing')=='No'} 
			<table border="0" class="overview compact">
				<tr class="{if $product->get('Customer Send Newsletter')=='Yes'}hide{/if}">
					<td colspan="2"> <i class="fa fa-ban"></i> <span>{t}Don't send newsletters{/t}</span> </td>
				</tr>
				<tr class="{if $product->get('Customer Send Email Marketing')=='Yes'}hide{/if}">
					<td colspan="2"> <i class="fa fa-ban"></i> <span>{t}Don't send marketing by email{/t}</span> </td>
				</tr>
				<tr class="{if $product->get('Customer Send Postal Marketing')=='Yes'}hide{/if}">
					<td colspan="2"> <i class="fa fa-ban"></i> <span>{t}Don't send marketing by post{/t}</span> </td>
				</tr>
			</table>
			{/if} {if $product->get('Customer Orders')>0} 
			<table class="overview">
				{if $product->get('Customer Type by Activity')=='Lost'} 
				<tr>
					<td><span style="color:white;background:black;padding:1px 10px">{t}Lost Customer{/t}</span></td>
				</tr>
				{/if} {if $product->get('Customer Type by Activity')=='Losing'} 
				<tr>
					<td><span style="color:white;background:black;padding:1px 10px">{t}Warning!, loosing product{/t}</span></td>
				</tr>
				{/if} 
				<tr>
					<td class="text"> {if $product->get('Customer Orders')==1} 
					<p>
						{$product->get('Name')} {t}has place one order{/t}.
					</p>
					{elseif $product->get('Customer Orders')>1 } {$product->get('Name')} {if $product->get('Customer Type by Activity')=='Lost'}{t}placed{/t}{else}{t}has placed{/t}{/if} <b>{$product->get('Customer Orders')}</b> {if $product->get('Customer Type by Activity')=='Lost'}{t}orders{/t}{else}{t}orders so far{/t}{/if}, {t}which amounts to a total of{/t} <b>{$product->get('Net Balance')}</b> {t}plus tax{/t} ({t}an average of{/t} {$product->get('Total Net Per Order')} {t}per order{/t}). {if $product->get('Customer Orders Invoiced')}
					</p>
					<p>
						{if $product->get('Customer Type by Activity')=='Lost'}{t}This product used to place an order every{/t}{else}{t}This product usually places an order every{/t}{/if} {$product->get('Order Interval')}.{/if} {else} Customer has not place any order yet. {/if}
					</p>
					</td>
				</tr>
			</table>
			{/if} 
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