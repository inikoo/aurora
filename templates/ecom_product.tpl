<div id="bd" style="padding:5px 20px 15px 20px">
	<div class="content" >
		
		<div class="product" stxyle="border:1px solid #ccc;background-color:#fff">
			<div class="images" style="float:left;width:310px;">
				
				<div style="border:1px solid #ccc;background:#FFF">
				<div class="wraptocenter">
			<img src="{$product.img}"> 
			</div>
				</div>
				
			</div>
			
			{*}
			xborder:1px solid red;background-color:rgba(101,77,42,.6);border-radius:5px;padding:7px;margin:10px 5px 0 10px 
			<div id="images_container">
				
				<div class="image_container" id="image_container_" style="margin-top:5px;">
					{foreach from=$product.images item=image name=product_images} 
					<img src="{$image.src}" alt="{$image.src}" style="width:73px;border:1px solid #ccc;cursor:pointer" onClick="change_image({$image.key},{$product.id})"> 
					{/foreach} 
				</div>
				
				
				</div>
			{*}	
			
			<div class="information">
				<h1 style="padding-top:5px;margin:2px 0;font-size:190%">
					{$product.name}</span> 
				</h1>
				<div class="highlight_box">
					<div style="float:right;margin-right:4px">
						{t}Product code{/t}: <span class="code">{$product.code}</span> 
					</div>
						{$product.price}
				</div>
				<h3>
					{$product.rrp} 
				</h3>
				{*}
				<h3>
					{$product.description} 
				</h3>
				<div>
				
					<table class="choose_product" id="choose_product">
						{foreach from=$family.products item=product name=product_list} 
						<input type="hidden" id="product_code_{$product.id}" value="{$product.code}"> 
						<input type="hidden" id="product_price_{$product.id}" value="{$product.price}"> 
						<input type="hidden" id="product_description_{$product.id}" value="{$product.description}"> 
						<input type="hidden" id="product_id_{$product.id}" value="{$product.id}"> 
						<tr class="product_option {if $smarty.foreach.product_list.first}checked{/if}" id="product_tr_{$product.id}">
							<td> 
							<input onclick="product_selected(this,{$product.id})" name="group1" type="radio" {if $smarty.foreach.product_list.first}checked{/if} style="margin-right:10px"></td>
							<td><span style="color:red;font-family: 'Ubuntu',Helvetica,Arial,sans-serif;margin-right:5px">£{$product.price}</span> </td>
							<td>{$product.code}</td>
							<td><span style="font-family: 'Ubuntu',Helvetica,Arial,sans-serif;margin:0 5px">{$product.special_char}</span> </td>
						</tr>
						{/foreach} 
					</table>
				
				</div>
				{*}
				<div style="margin-left:15px">
					{$product.button}
				</div>
				<div id="product_long_descriptions">
				{$product.description}
				</div>
			</div>
			{*}
			{if $number_families>0} 
			<div style="clear:both;xborder:1px solid red;padding:10px 0 0 10px">
				<h2 style="padding-bottom:0px;margin-bottom:7.5px">
					Similar Products 
				</h2>
				{foreach from=$families item=family} 
				<div class="block four product_showcase {if !$family.first}first{/if} family" style="margin-bottom:20px;position:relative;width:145px">
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
				<div style="clear:both">
				</div>
			</div>
			{/if}
			{*}
		</div>
	</div>
	
	<div style="clear:both">
	</div>
</div>
