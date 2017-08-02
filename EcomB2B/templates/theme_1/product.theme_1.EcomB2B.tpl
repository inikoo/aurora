{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 3 July 2017 at 21:09:09 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}{include file="theme_1/_head.theme_1.EcomB2B.tpl"}

{include file="style.tpl" css=$webpage->get('Published CSS') }


<body xmlns="http://www.w3.org/1999/html">


<div class="wrapper_boxed">

    <div class="site_wrapper">

        {include file="theme_1/header.EcomB2B.tpl"}

        <div class="content_fullwidth less2">
            <div class="container">


                {assign 'see_also'  $webpage->get_see_also() }
                {include file="style.tpl" css=$webpage->get('Published CSS') }


                <span id="ordering_settings" class="hide"
                      data-labels='{ "ordered":"<i class=\"fa fa-thumbs-o-up fa-flip-horizontal \" aria-hidden=\"true\"></i> {if empty($labels._ordering_ordered)}{t}Ordered{/t}{else}{$labels._ordering_ordered}{/if}", "order":"<i class=\"fa fa-hand-pointer-o\" aria-hidden=\"true\"></i>  {if empty($labels._ordering_order_now)}{t}Order now{/t}{else}{$labels._ordering_order_now}{/if}", "update":"<i class=\"fa fa-hand-pointer-o\" aria-hidden=\"true\"></i>  {if empty($labels._ordering_click_to_update)}{t}Click to update{/t}{else}{$labels._ordering_click_to_update}{/if}"  }'></span>

                <div id="page_content" style="position:relative">
                <div id="product_bd" style="padding:5px 20px 0px 20px;clear:both;" class="product_container" product_id="{$product->id}">


                    {if $product->get('Status')=='Discontinued' }
                        <div class="section description_block alert alert-error alert-title" style="text-align:center">
                            <i class="fa fa-frown-o padding_right_20" aria-hidden="true"></i> {t}Discontinued{/t} <i class="fa fa-frown-o padding_left_20" aria-hidden="true"></i>
                        </div>
                    {/if}


                    <div class="product" style="display: flex; ;">


                        <div style="float:left;width:400px">


                            <div class="fotorama" data-nav="thumbs" data-width="400">
                                {foreach from=$product->get_images_slidesshow() item=image name=foo}
                                    <a href="/{$image.normal_url}"><img src="/{$image.small_url}"></a>
                                {/foreach}


                            </div>


                        </div>

                        <div class="information" style="float:left;margin-left:30px;width:510px;">
                            <h1 style="padding-top:5px;margin:2px 0;font-size:150%">
                                {$product->get('Name')}

                                {assign 'favourite_key' {$product->get('Favourite Key',{$customer->id})} }
                                <span class="invisible  favourite  " favourite_key={$favourite_key}><i style="font-size:70%;position:relative;top:-2px" class="fa {if $favourite_key}fa-heart marked{else}fa-heart-o{/if}"
                                                                                              aria-hidden="true"></i>  </span>

                            </h1>
                            <div class="">
                                <div style="float:left;margin-right:4px;min-width:200px">
                                    {if empty($labels._product_code)}{t}Product code{/t}{else}{$labels._product_code}{/if}: <span class="code">{$product->get('Code')} </span>
                                </div>

                            </div>

                            {if $logged_in}
                                <div class="ordering-container  log_in" style="margin-top:40px;">

                                    <div class=" product_price " style="margin-left:0px;padding-left:0px;width:250px;margin-bottom:10px">
                                        <div class="product_price">{if empty($labels._product_price)}{t}Price{/t}{else}{$labels._product_price}{/if}: {$product->get('Price')}</div>
                                        {assign 'rrp' $product->get('RRP')}
                                        {if $rrp!=''}
                                            <div class="product_price" style="margin-top:4px">{if empty($labels._product_rrp)}{t}RRP{/t}{else}{$labels._product_rrp}{/if}: {$rrp}</div>{/if}
                                        <div style="clear:both"></div>
                                    </div>


                                    {if $product->get('Web State')=='Out of Stock'}
                                        <div class="ordering log_in can_not_order {$product->get('Out of Stock Class')} " style="width:350px;position:relative;margin-top:40px">


                                            {assign 'reminder_key' {$product->get('Reminder Key',{$user->id})} }
                                            <div class="out_of_stock_row {$product->get('Out of Stock Class')}">
    <span class="label">
    {$product->get('Out of Stock Label')}
        <span class="label sim_button "> <i reminder_key="{$reminder_key}" title="{if $reminder_key>0}{t}Click to remove notification{/t}{else}{t}Click to be notified by email{/t}{/if}"
                                            class="reminder fa {if $reminder_key>0}fa-envelope{else}fa-envelope-o{/if}" aria-hidden="true"></i>  </span>
    </span>
                                            </div>


                                        </div>
                                    {elseif $product->get('Web State')=='For Sale'}
                                        <div class="ordering log_in " style="width:200px;position:relative;margin-top:40px">

                                            {assign 'quantity_ordered' $product->get('Ordered Quantity',$order->id) }
                                            <div   class="order_row {if $quantity_ordered!=''}ordered{else}empty{/if}">

                                                <input maxlength=6 style="border-left:1px solid #ccc;"    class='order_input ' id='but_qty{$product->id}' type="text"' size='2' value='{$quantity_ordered}'
                                                ovalue='{$quantity_ordered}'>

                                                {if $quantity_ordered==''}
                                                    <div class="label sim_button" style="margin-left:57px"><i class="fa fa-hand-pointer-o fa-fw" aria-hidden="true"></i> <span class="">{if empty($labels._ordering_order_now)}{t}Order now{/t}{else}{$labels._ordering_order_now}{/if}</span></div>
                                                {else}
                                                    <span class="label sim_button"><i class="fa  fa-thumbs-o-up fa-flip-horizontal fa-fw" aria-hidden="true"></i> <span class="">{if empty($labels._ordering_ordered)}{t}Ordered{/t}{else}{$labels._ordering_ordered}{/if}</span></span>
                                                {/if}

                                            </div>


                                        </div>
                                    {/if}


                                </div>
                                {if $product->get('Status')=='Discontinued' }
                                    <br>
                                    <div class="section description_block alert alert-error alert-title" style=";margin-top:20px;margin-left:0;text-align:center">
                                        {t}Sorry, but this product is discontinued{/t}
                                    </div>
                                {/if}

                            {else}
                                <div class="product_prices log_out " style="clear:both;margin-top:40px;width:500px;text-align: left">


                                    <div>{if empty($labels._login_to_see)}{t}For prices, please login or register{/t}{else}{$labels._login_to_see}{/if}</div>


                                    <div class=" log_in_buttons_individual_product " style="margin-top:10px;">
                                        <div   onclick='window.location.href = "/login.sys"' class="mark_on_hover"><span  >{if empty($labels._Login)}{t}Login{/t}{else}{$labels._Login}{/if}</span></div>
                                        <div  onclick='window.location.href = "/register.sys"' class="mark_on_hover"><span  style="height: 30px">{if empty($labels._Register)}{t}Register{/t}{else}{$labels._Register}{/if}</span></div>
                                    </div>


                                    {if $product->get('Status')=='Discontinued' }
                                        <div class="section description_block alert alert-error alert-title" style="text-align:center">
                                            {t}Sorry, this product is discontinued{/t}
                                        </div>
                                    {/if}


                                </div>
                            {/if}


                            <div id="product_description"  style="margin-top:20px;padding-top:20px;position: relative;top:10px" class="product_description_block fr-view {$content.description_block.class}">
                                {$content.description_block.content}
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
                            <tr class="{if $Origin==''}hide{/if}">
                                <td>{if empty($labels._product_origin)}{t}Origin{/t}{else}{$labels._product_origin}{/if}</td>
                                <td>{$Origin}</td>
                            </tr>

                            <tr class="{if $Weight==''}hide{/if}">
                                <td>{if empty($labels._product_weight)}{t}Weight{/t}{else}{$labels._product_weight}{/if}</td>
                                <td>{$Weight}</td>
                            </tr>
                            <tr class="{if $Dimensions==''}hide{/if}">
                                <td>{if empty($labels._product_dimensions)}{t}Dimensions{/t}{else}{$labels._product_dimensions}{/if}</td>
                                <td>{$Dimensions}</td>
                            </tr>
                            <tr class="{if $Materials==''}hide{/if}">
                                <td>{if empty($labels._product_materials)}{t}Materials{/t}/{t}Ingredients{/t}{else}{$labels._product_materials}{/if}</td>
                                <td>
                                    <section style="width:70%"> {$Materials}</section>
                                </td>
                            </tr>
                            <tr class="{if $CPNP==''}hide{/if}">
                                <td title="{if empty($labels._product_cpnp)}{t}Cosmetic Products Notification Portal{/t}{else}{$labels._product_cpnp}{/if} - Europa.eu">CPNP</td>
                                <td>{$CPNP}</td>
                            </tr>
                            <tr class="{if $Barcode==''}hide{/if}">
                                <td>{if empty($labels._product_barcode)}{t}Barcode{/t}{else}{$labels._product_barcode}{/if}</td>
                                <td>{$Barcode}</td>

                            </tr>

                            {foreach from=$product_attachments item=attachment}
                                <tr>
                                    <td>{$attachment.label} <i class="fa fa-paperclip" style="margin-left:5px" aria-hidden="true"></i></td>
                                    <td><a href="attachment.php?id={$attachment.id}" target="_blank">{$attachment.name}</a></td>
                                </tr>
                            {/foreach}

                        </table>


                    </div>





                </section>

                </div>
            </div>


            <div class="clearfix marb12"></div>

            {include file="theme_1/footer.EcomB2B.tpl"}

        </div>

    </div>

<script>
    $(function () {
        $('.fotorama').fotorama();
    });
    </script>


</body>

</html>