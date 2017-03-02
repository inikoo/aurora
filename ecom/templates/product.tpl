{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 2 March 2017 at 11:53:11 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3
-->
*}


{assign 'see_also'  $public_product->webpage->get_see_also() }

{include file="style.tpl" css=$public_product->webpage->get('Published CSS') }



<span id="ordering_settings" class="hide" data-labels='{ "ordered":"<i class=\"fa fa-thumbs-o-up fa-flip-horizontal \" aria-hidden=\"true\"></i> {t}Ordered{/t}", "order":"<i class=\"fa fa-hand-pointer-o\" aria-hidden=\"true\"></i>  {t}Order now{/t}", "update":"<i class=\"fa fa-hand-pointer-o\" aria-hidden=\"true\"></i>  {t}Update{/t}"  }'></span>


<div id="page_content" style="position:relative">



	<div id="product_bd" style="padding:5px 20px 0px 20px;clear:both;border:none" class="product_showcase" product_id="{$public_product->id}" >

		<div class="product" style="display: flex; ">
			<div class="images" style="width:300px">
				<div style="border:1px solid #ccc;background:#FFF">
					<div class="wraptocenter">
						<a href="{$public_product->get('Image')}" class="imgpop"><img src="{$public_product->get('Image')}"></a>
					</div>
				</div>
				<ul class="gallery">
                    {foreach from=$public_product->get_images_slidesshow() item=image name=foo}
                        {if $image.subject_order>0   }
							<li><a href="/{$image.normal_url}" class="imgpop"> <img class="thumbs" src="/{$image.small_url}" alt="{$image.name}" /> </a> </li>
                        {/if}
                    {/foreach}
				</ul>




			</div>
			<div class="information" style="margin-left:40px;margin-right:20px;width:600px">
				<h1 style="padding-top:5px;margin:2px 0;font-size:190%">
                    {$public_product->get('Name')}

                    {assign 'favourite_key' {$public_product->get('Favourite Key',{$customer->id})} }
					<span class="  favourite  " favourite_key={$favourite_key} ><i style="font-size:70%;position:relative;top:-2px" class="fa {if $favourite_key}fa-heart marked{else}fa-heart-o{/if}" aria-hidden="true"></i>  </span>

				</h1>
				<div class="">
					<div style="float:left;margin-right:4px;min-width:200px">
                        {t}Product code{/t}: <span class="code">{$public_product->get('Code')} </span>
					</div>

				</div>

                {if $logged}
				<div class="ordering-container  log_in" style="display: flex;margin-top:40px;" >

					<div class="product_prices log_in " style="margin-left:0px;padding-left:0px;font-size: 120%;width:250px" >
						<div class="product_price">{t}Price{/t}: {$public_product->get('Price')}</div>
                        {assign 'rrp' $public_product->get('RRP')}
                        {if $rrp!=''}<div style="margin-top:4px">{t}RRP{/t}: {$rrp}</div>{/if}
					</div>

					<div style="margin-left:10px;">


                        {if $public_product->get('Web State')=='Out of Stock'}
						<div class="ordering log_in can_not_order {$public_product->get('Out of Stock Class')} ">

                            {assign 'reminder_key' {$public_product->get('Reminder Key',{$user->id})} }

							<span class="product_footer label ">{$public_product->get('Out of Stock Label')}</span>
							<span class="product_footer reminder" reminder_key="{$reminder_key}"><i class="fa {if $reminder_key>0}fa-envelope{else}fa-envelope-o{/if}" aria-hidden="true"></i>  </span>


						</div>
                        {else if $public_product->get('Web State')=='For Sale'}



						<div class="ordering log_in " >

                            {assign 'quantity_ordered' $public_product->get('Ordered Quantity',$order->id) }
							<input style="border-left:1px solid #ccc" maxlength=6  class='order_input ' id='but_qty{$public_product->id}'   type="text"' size='2'  value='{$quantity_ordered}' ovalue='{$quantity_ordered}'>
                            {if $quantity_ordered==''}
								<span class="product_footer order_button "><i class="fa fa-hand-pointer-o fa-fw" aria-hidden="true"></i> <span class="order_button_text">{t}Order now{/t}</span></span>
                            {else}
								<span class="product_footer order_button ordered"><i class="fa  fa-thumbs-o-up fa-flip-horizontal fa-fw" aria-hidden="true"></i> <span class="order_button_text">{t}Ordered{/t}</span></span>
                            {/if}





						</div>

						{/if}

					</div>

				</div>

				{else}
				<div class="product_prices log_out " style="clear:both;margin-top:40px">
					<div >{t}For prices, please login or register{/t}</div>
				</div>
{/if}




				<div id="product_description" class="product_description_block fr-view {$content_data.description_block.class}">
                    {$content_data.description_block.content}
				</div>


			</div>

			<div style="clear: both;height: 10px"></div>



		</div>
	</div>

	<section class="product_tabs" style="margin-top:20px">

		<input id="tab-properties" type="radio" name="grp" class="{if !$has_properties_tab}hide{/if}" {if $has_properties_tab}checked="checked"{/if} />
		<label for="tab-properties">{t}Properties{/t}</label>
		<div>



			<table class="properties">
				<tr class="{if $Weight==''}hide{/if}"> <td>{$public_product->get_field_label('Product Unit Weight')|ucfirst}</td> <td>{$Weight}</td></tr>
				<tr class="{if $Dimensions==''}hide{/if}">  <td>{$public_product->get_field_label('Product Unit Dimensions')|ucfirst}</td> <td>{$Dimensions}</td></tr>
				<tr class="{if $Materials==''}hide{/if}"> <td>{t}Materials{/t}/{t}Ingredients{/t}</td> <td> <section style="width:70%"> {$Materials}</section></td></tr>
				<tr class="{if $CPNP==''}hide{/if}"> <td title="{t}Cosmetic Products Notification Portal{/t} - Europa.eu">CPNP</td> <td>{$CPNP}</td></tr>
			</table>


		</div>


		<input id="tab-new" type="radio" name="grp" />
		<label  class="hide" for="tab-new">{t}New tab{/t}</label>
		<div>
			bla bla bla
		</div>



	</section>




	<div style="clear:both"></div>

</div>

{include file="order_products.js.tpl" }
