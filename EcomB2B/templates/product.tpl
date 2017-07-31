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





	<div id="product_bd" style="padding:5px 20px 0px 20px;clear:both;" class="product_container" product_id="{$public_product->id}" >


        {if $public_product->get('Status')=='Discontinued' }
			<div  class="section description_block alert alert-error alert-title" style="text-align:center">
				<i class="fa fa-frown-o padding_right_20" aria-hidden="true"></i> {t}Discontinued{/t} <i class="fa fa-frown-o padding_left_20" aria-hidden="true"></i>
			</div>
        {/if}



		<div class="product" style="display: flex; ;">




			<div style="float:left;width:400px" >




				<div class="fotorama"  data-nav="thumbs"  data-width="400">
                    {foreach from=$public_product->get_images_slidesshow() item=image name=foo}
						<a href="/{$image.normal_url}"><img src="/{$image.small_url}"></a>
                    {/foreach}


				</div>



			</div>

			<div class="information" style="float:left;margin-left:30px;width:510px;">
				<h1 style="padding-top:5px;margin:2px 0;font-size:150%">
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
				<div class="ordering-container  log_in" style="margin-top:40px;" >

					<div class=" product_price " style="margin-left:0px;padding-left:0px;width:250px;margin-bottom:10px" >
						<div class="product_price">{t}Price{/t}: {$public_product->get('Price')}</div>
                        {assign 'rrp' $public_product->get('RRP')}
                        {if $rrp!=''}<div class="product_price" style="margin-top:4px">{t}RRP{/t}: {$rrp}</div>{/if}
						<div style="clear:both"></div>
					</div>






                        {if $public_product->get('Web State')=='Out of Stock'}
						<div class="ordering log_in can_not_order {$public_product->get('Out of Stock Class')} " style="width:350px;position:relative;margin-top:40px">



                            {assign 'reminder_key' {$public_product->get('Reminder Key',{$user->id})} }
							<div  class="out_of_stock_row {$public_product->get('Out of Stock Class')}"  >
    <span class="label">
    {$public_product->get('Out of Stock Label')}
		<span  class="label sim_button " > <i reminder_key="{$reminder_key}" title="{if $reminder_key>0}{t}Click to remove notification{/t}{else}{t}Click to be notified by email{/t}{/if}"   class="reminder fa {if $reminder_key>0}fa-envelope{else}fa-envelope-o{/if}" aria-hidden="true"></i>  </span>
    </span>
							</div>




						</div>
                        {else if $public_product->get('Web State')=='For Sale'}

						<div class="ordering log_in " style="width:200px;position:relative;margin-top:40px" >

                            {assign 'quantity_ordered' $public_product->get('Ordered Quantity',$order->id) }
							<div class="order_row {if $quantity_ordered!=''}ordered{else}empty{/if}"      >
								<input maxlength=6  style="border-left:1px solid #ccc" class='order_input ' id='but_qty{$public_product->id}'   type="text"' size='2'  value='{$quantity_ordered}' ovalue='{$quantity_ordered}'>
                                {if $quantity_ordered==''}
									<div class="label sim_button" style="margin-left:57px"  ><i class="fa fa-hand-pointer-o fa-fw" aria-hidden="true"></i> <span class="">{t}Order now{/t}</span></div>
                                {else}
									<span class="label sim_button"><i class="fa  fa-thumbs-o-up fa-flip-horizontal fa-fw" aria-hidden="true"></i> <span class="">{t}Ordered{/t}</span></span>
                                {/if}

							</div>





						</div>

						{/if}







				</div>
                    {if $public_product->get('Status')=='Discontinued' }
						<br>
						<div  class="section description_block alert alert-error alert-title" style=";margin-top:20px;margin-left:0;text-align:center">
                            {t}Sorry, but this product is discontinued{/t}
						</div>
                    {/if}

				{else}
				<div class="product_prices log_out " style="clear:both;margin-top:40px;width:500px;text-align: left">



					<div >{t}For prices, please login or register{/t}</div>


					<div class=" log_in_buttons_individual_product " style="margin-top:10px;" >
						<div class="mark_on_hover" ><span onClick="location.href='login.php?from={$public_product->webpage->id}'"  >{t}Login{/t}</span></div>
						<div class="mark_on_hover"  ><span onClick="location.href='registration.php'"  style="height: 30px"  >{t}Register{/t}</span></div>
					</div>



                    {if $public_product->get('Status')=='Discontinued' }
						<div  class="section description_block alert alert-error alert-title" style="text-align:center">
                            {t}Sorry, this product is discontinued{/t}
						</div>
                    {/if}





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
				<tr class="{if $Origin==''}hide{/if}"> <td >{t}Origin{/t}</td> <td>{$Origin}</td></tr>

				<tr class="{if $Weight==''}hide{/if}"> <td>{$public_product->get_field_label('Product Unit Weight')|ucfirst}</td> <td>{$Weight}</td></tr>
				<tr class="{if $Dimensions==''}hide{/if}">  <td>{$public_product->get_field_label('Product Unit Dimensions')|ucfirst}</td> <td>{$Dimensions}</td></tr>
				<tr class="{if $Materials==''}hide{/if}"> <td>{t}Materials{/t}/{t}Ingredients{/t}</td> <td> <section style="width:70%"> {$Materials}</section></td></tr>
				<tr class="{if $CPNP==''}hide{/if}"> <td title="{t}Cosmetic Products Notification Portal{/t} - Europa.eu">CPNP</td> <td>{$CPNP}</td></tr>
				<tr class="{if $Barcode==''}hide{/if}"> <td>{$public_product->get_field_label('Product Barcode Number')|ucfirst}</td> <td>{$Barcode}</td></tr>

                {foreach from=$product_attachments item=attachment}
					<tr>
						<td>{$attachment.label} <i class="fa fa-paperclip" style="margin-left:5px" aria-hidden="true"></i></td>
						<td  >   <a href="attachment.php?id={$attachment.id}" target="_blank">{$attachment.name}</a></td>
					</tr>
                {/foreach}

			</table>


		</div>


		<input id="tab-new" type="radio" name="grp" />
		<label  class="hide" for="tab-new">{t}New tab{/t}</label>
		<div>
			bla bla bla
		</div>



	</section>






<script>



	</script>