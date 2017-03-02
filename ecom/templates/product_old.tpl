<div id="product_bd" style="padding:5px 20px 15px 20px;clear:both">
	<div class="content">
		<div class="product" stxyle="border:1px solid #ccc;background-color:#fff">
			<div class="images" style="float:left;width:310px;">
				<div style="border:1px solid #ccc;background:#FFF">
					<div class="wraptocenter">
						<a href="{$product.normal_img}" class="imgpop"><img src="{$product.img}"></a> 
					</div>
				</div>
				<div style="{if $product.object->get_number_of_images()<1}xdisplay:none{/if}">
				

				
					<ul class="gallery">
						{foreach from=$product.object->get_images_slidesshow() item=image name=foo} {if $image.subject_order>1  } 

						<li><a href="{$image.normal_url}" class="imgpop"> <img class="thumbs" src="{$image.small_url}" alt="{$image.name}" /> </a> </li>
						{/if} {/foreach} 
					</ul>
				</div>
			</div>
			<div class="information">
				<h1 style="padding-top:5px;margin:2px 0;font-size:190%">
					{$product.units}x  {$product.name} 
				</h1>
				<div class="highlight_box">
					<div style="float:left;margin-right:4px;min-width:200px">
						{t}Product code{/t}: <span class="code">{$product.code} </span> 
					</div>
					<span   {if !$logged}style="font-size:90%"{else}class="price"{/if} >{if $logged}{t}Price{/t}: {/if}{$product.price}</span>
					
					{$product.button_only} 
				</div>
				{if $logged}
				<h3>
					<span class="rrp">{if $product.rrp!=''}{t}RRP{/t}: {$product.rrp}{/if}</span>
				</h3>
				{/if}
				<div style="margin-left:15px">
				</div>
				<div class="product_long_descriptions fr-view">
					{$product.description} 
				</div>
			</div>
			<div class="side_bar">
				<table id="specs" border="0">
				
				
					<tr class="title">
						
					<td colspan="2">{if $product.units==1}{t}1 unit per outer{/t}{else}{t}Unit{/t} ({$product.units}/{t}Outer{/t}){/if}{if $product.unit_weight or $product.unit_dimensions}:{/if} 

						</tr>
						
						 {if $product.unit_weight!=''}
						<tr>
							<td class="icon"></td>
							<td>{$product.unit_weight} <img style="position:relative;top:4px" src="art/icons/weight.png" alt='' title="{t}Net weight{/t}"></td>
						</tr>
						{/if} {if $product.unit_dimensions!=''}
						<tr>
							<td class="icon"></td>
							<td>{$product.unit_dimensions}</td>
						</tr>
						{/if}
						
						 {if $product.ingrediens!=''}
						<tr class="title">
							<td colspan="2">{t}Materials/Ingredients{/t}:</td>
						</tr>
						<tr>
							<td colspan="2" style="font-size:90%">{$product.ingrediens}</td>
						</tr>
						{/if}
						 {if $product.origin!=''}
						<tr class="title">
							<td colspan="2">{t}Origin{/t}:</td>
						</tr>
						<tr>
							<td colspan="2" style="font-size:90%">{$product.origin}</td>
						</tr>
						{/if}
						{if $product.CPNP!=''}
						<tr class="title">
							<td colspan="2">{t}CPNP{/t}:</td>
						</tr>
						<tr>
							<td colspan="2" style="font-size:90%">{$product.CPNP}</td>
						</tr>
						{/if}  
						{if $product.attachments|@count gt 0}
						<tr class="title">
							<td colspan="2">{t}Attachments{/t}:</td>
						</tr>
						{foreach from=$product.attachments item=attachment}

						<tr>
							<td colspan="2" style="font-size:90%"><a href="attachment.php?id={$attachment.id}">{$attachment.label}</a></td>
						</tr>
						{/foreach}
						{/if}  
					</table>
				</div>
			</div>
		</div>
		<div style="clear:both">
		</div>
	</div>
	
