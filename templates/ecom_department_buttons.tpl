<div id="bd" style="padding:20px 15px">

	<div id="families" class="content" style="width:780px;float:left;">
		
		
	
		{$page->get_primary_content()}
		
	    {foreach from=$page->get_families_data() item=family} 
		<div class="block four product_showcase {if !$family.first}first{/if}" style="margin-bottom:20px;position:relative">
			<a href="family.php?code={$family.code}&parent={$page_name}"><img src="art/moreinfo_corner{$family.col}.png" style="cursor:pointer;position:absolute;width:40px;top:-1px;left:146px"> <img src="{$family.img}" style="width:100%"> </a>
			<h2 style="font-size:120%">
				{$family.title} 
			</h2>
			<div>
				<div style="height:60px;">
					{$family.name} 
				</div>
				<div style="clear:both">
				</div>
			</div>
			<div style="font-size:120%;clear:both;;text-align:right;margin-top:5px;margin-bottom:5px;margin-right:10px">
				<span style="color:red;font-family: 'Ubuntu',Helvetica,Arial,sans-serif">£<span id="{$family.code}_formated_price">{$family.price}</span></span> {if $family.number_products>1} <span onclick="show_products_dialog('{$family.code}')" style="border:1px solid #ccc;padding:1px 5px;font-size:90%;cursor:pointer"><span id="{$family.code}_formated_special_char">{$family.current_product_special_char}</span> &#x25BC;</span> {else} <span style="border:1px solid #fff;padding:1px 5px;font-size:90%;cursor:pointer;position:relative;bottom:1px">{if $family.current_product_special_char==''}each{else}{$family.current_product_special_char}{/if} </span> {/if} 
			</div>
			<div style="position:absolute">
				<table id="products_dialog_{$family.code}" class="products_dialog" style="padding-top:5px;display:none;border:1px solid #ccc;z-index:1000;background-color:#fff;position:relative;left:-20px;top:-3px">
					{foreach from=$family.products item=product name=product_list} 
					<input type="hidden" id="product_code_{$product.id}" value="{$product.code}"> 
					<input type="hidden" id="product_price_{$product.id}" value="{$product.price}"> 
					<input type="hidden" id="product_description_{$product.id}" value="{$product.description}"> 
					<input type="hidden" id="product_id_{$product.id}" value="{$product.id}"> 
					<input type="hidden" id="product_special_char_{$product.id}" value="{$product.special_char}"> 
					<tr class="product_option {if $smarty.foreach.product_list.first}checked{/if}" id="product_tr_{$product.id}">
						<td>
						<input onclick="product_selected('{$family.code}',{$product.id})" name="group1_{$family.code}" type="radio" {if $smarty.foreach.product_list.first}checked{/if} style="margin-right:10px"></td>
						<td><span style="color:red;font-family: 'Ubuntu',Helvetica,Arial,sans-serif;margin-right:5px">£{$product.price}</span> </td>
						<td>{$product.code}</td>
						<td><span style="font-family: 'Ubuntu',Helvetica,Arial,sans-serif;margin:0 5px">{$product.special_char}</span> </td>
					</tr>
					{/foreach} 
					<tr style="height:30px">
						<td colspan="3"> 
						<div class="buttons small">
							<button onclick="Dom.setStyle('products_dialog_{$family.code}','display','none')">Close</button> 
						</div>
						</td>
					</tr>
				</table>
			</div>
			<div style="xborder:1px solid red;width:100%">
				<input onclick="Dom.setStyle('error_no_qty_{$family.code}','visibility','hidden')" id="{$family.code}_qty" type="text" style="font-size:110%;width:30px" value="1" />
				<img src="art/ordernow.jpg" onclick="ordernow('{$family.code}')" style="cursor:pointer;height:35px;vertical-align:bottom;position:relative;bottom:-5px"> 
			</div>
			<div id="error_no_qty_{$family.code}" style="margin-left:15px;margin-top:3px;color:red;visibility:hidden">
				&#8598 how many?
			</div>
			<input type="hidden" id="{$family.code}_product_pid" value="{$family.current_product_id}"></span> 
			<input type="hidden" id="{$family.code}_product_description" value="{$family.current_product_description}"></span> 
			<input type="hidden" id="{$family.code}_product_code" value="{$family.current_product_code}"></span> 
			<input type="hidden" id="{$family.code}_price" value="{$family.price}"></span> 
		</div>
		{/foreach} 
	</div>
	<div class="lateral_bar" style="width:154px;float:left;margin-left:800px;position: absolute;">
		<div id="minicart" style="xposition: fixed">
			<h2>
				<a class="onBackground" href="http://ww4.aitsafe.com/cf/review.cfm?userid=65410061">My Cart</a> 
			</h2>
			<div id="miniProductList" style="display:none">
			</div>
			<div id="minicartSummary">
				<p>
					Items: <span class="total" id="number_items">{$items}</span> 
				</p>
				<p>
					Total: £<span class="total" id="total">{$total}</span> 
				</p>
				<p style="display:none">
					<a class="onBackground" href="/checkout/shipping">Estimate Shipping</a> 
				</p>
				<div style="text-align:center;magin-bottom:20px">
					<a class="buttonLink" id="minicartCheckout" href="http://ww4.aitsafe.com/cf/review.cfm?userid=65410061" style="width:120px">Checkout Now</a> 
				</div>
			</div>
			<div style="clear:both;margin-bottom:20px">
			</div>
		</div>
	</div>
	<div style="clear:both">
	</div>
</div>
