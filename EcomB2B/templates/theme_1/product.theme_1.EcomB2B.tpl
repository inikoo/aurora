{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 3 July 2017 at 21:09:09 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}{include file="theme_1/_head.theme_1.EcomB2B.tpl"  extra_style=$webpage->get('Published CSS') }



<body xmlns="http://www.w3.org/1999/html">
{include file="analytics.tpl"}

<div class="wrapper_boxed">

    <div class="site_wrapper">

        {include file="theme_1/header.theme_1.EcomB2B.tpl"}

        <div class="content_fullwidth less3">
            <div class="container">


                {assign 'see_also'  $webpage->get_see_also() }


                <span id="ordering_settings" class="hide"
                      data-labels='{ "ordered":"<i class=\"fa fa-thumbs-o-up fa-flip-horizontal \" aria-hidden=\"true\"></i> {if empty($labels._ordering_ordered)}{t}Ordered{/t}{else}{$labels._ordering_ordered}{/if}", "order":"<i class=\"fa fa-hand-pointer-o\" aria-hidden=\"true\"></i>  {if empty($labels._ordering_order_now)}{t}Order now{/t}{else}{$labels._ordering_order_now}{/if}", "update":"<i class=\"fa fa-hand-pointer-o\" aria-hidden=\"true\"></i>  {if empty($labels._ordering_click_to_update)}{t}Click to update{/t}{else}{$labels._ordering_click_to_update}{/if}"  }'></span>

                <div id="page_content" style="position:relative">


                    <div class="description_block">


                        {assign 'parent_family'  $product->get_parent_category('data') }
                        {assign 'prev_product'  $product->get_prev_product('data') }
                        {assign 'next_product'  $product->get_next_product('data') }
                        <div style="float: left">
                        <div style="position: relative;top:-10px;width: 200px;">
                            {if $parent_family}
                                <span onclick="location.href = '/{$parent_family.webpage_code}'" class="like_button padding_right_5" title="{$parent_family.code}  {$parent_family.label}">
                                     {$parent_family.code}
                                </span>
                                <i class="fa fa-angle-double-right padding_right_5" aria-hidden="true"></i>

                            {/if}

                            {if empty($labels._product_code)}{t}Product{/t}{else}{$labels._product_code}{/if}: <span class="code">{$product->get('Code')} </span>
                        </div>

                        {if $prev_product}
                            <a class="parent_up" href="{$prev_product.webpage_code}">
                                <i class="fa fa-arrow-left" aria-hidden="true" title="" style="margin-right: 10px;"></i>

                            </a>
                        {/if}


                        {if $parent_family}
                            <a href="/{$parent_family.webpage_code}" class="parent_up" title="{$parent_family.code}  {$parent_family.label}">
                                <i class="fa fa-arrow-up" aria-hidden="true"></i> {$parent_family.code}
                            </a>
                        {/if}





                        {if $next_product}
                            <a class="parent_up" href="{$next_product.webpage_code}">
                                <i class="fa fa-arrow-right" aria-hidden="true" title="" style="margin-left: 10px;"></i>

                            </a>
                        {/if}

                        </div>

                        {foreach from=$product->get_deal_components('objects') item=item key=key}
                            <div class="discount_card" style="float: right">
                                <span class="discount_icon">{$item->get('Deal Component Icon')}</span>

                                <span class="discount_name">{$item->get('Deal Component Name Label')}</span><br/>
                                <span class="discount_term">{$item->get('Deal Component Term Label')}</span>

                                <span class="discount_allowance">{$item->get('Deal Component Allowance Label')}</span>

                            </div>
                        {/foreach}


                        <div style="clear: both"></div>

                    </div>


                    <div id="product_bd" style="padding:5px 20px 0px 20px;clear:both;" class="product_container" data-product_id="{$product->id}">


                        {if $product->get('Status')=='Discontinued' }
                            <div class="section description_block alert alert-error alert-title" style="text-align:center">
                                <i class="fa fa-frown-o padding_right_20" aria-hidden="true"></i> {t}Discontinued{/t} <i class="fa fa-frown-o padding_left_20" aria-hidden="true"></i>
                            </div>
                        {/if}


                        <div class="product" style="display: flex; ;">


                            <div style="float:left;width:400px">


                                <div class="fotorama" data-nav="thumbs" data-width="400">
                                    {foreach from=$product->get_images_slidesshow() item=image name=foo}
                                        <a href="/{$image.normal_url}"><img alt="" src="/{$image.small_url}"></a>
                                    {/foreach}


                                </div>


                            </div>

                            <div class="information" style="float:left;margin-left:30px;width:510px;">
                                <h1 style="padding-top:5px;margin:2px 0;font-size:150%">
                                    {$product->get('Name')}
                                    {if !empty($customer)}
                                        {assign 'favourite_key' {$product->get('Favourite Key',{$customer->id})} }
                                        <span class="favourite" data-product_id="{$product->id}" data-favourite_key="{$favourite_key}">
                                            <i style="" class="fa {if $favourite_key}fa-heart marked{else}fa-heart-o{/if}" aria-hidden="true"></i>  </span>
                                    {/if}

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

                                                {if isset($customer)}
                                                    {assign 'reminder_key' {$product->get('Reminder Key',{$customer->id})} }
                                                    <div class="out_of_stock_row {$product->get('Out of Stock Class')}">
                                  <span class="label">
                                    {$product->get('Out of Stock Label')}
                                       <span class="label sim_button "> <i reminder_key="{$reminder_key}" title="{if $reminder_key>0}{t}Click to remove notification{/t}{else}{t}Click to be notified by email{/t}{/if}"
                                            class="reminder hide  fa {if $reminder_key>0}fa-envelope{else}fa-envelope-o{/if}" aria-hidden="true"></i>  </span>
                                            </span>
                                                    </div>
                                                {/if}

                                            </div>
                                        {elseif $product->get('Web State')=='For Sale'}
                                            <div class="ordering log_in " style="width:200px;position:relative;margin-top:40px">

                                                {assign 'quantity_ordered' $product->get('Ordered Quantity',$order_key) }
                                                <div class="order_row {if $quantity_ordered!=''}ordered{else}empty{/if}">

                                                    <input maxlength=6 style="border-left:1px solid #ccc;" class='order_input ' id='but_qty{$product->id}' type="text"' size='2' value='{$quantity_ordered}'
                                                    data-ovalue='{$quantity_ordered}'>

                                                    {if $quantity_ordered==''}
                                                        <div class="label sim_button" style="margin-left:57px"><i class="fa fa-hand-pointer-o fa-fw" aria-hidden="true"></i> <span
                                                                    class="">{if empty($labels._ordering_order_now)}{t}Order now{/t}{else}{$labels._ordering_order_now}{/if}</span></div>
                                                    {else}
                                                        <span class="label sim_button"><i class="fa  fa-thumbs-o-up fa-flip-horizontal fa-fw" aria-hidden="true"></i> <span
                                                                    class="">{if empty($labels._ordering_ordered)}{t}Ordered{/t}{else}{$labels._ordering_ordered}{/if}</span></span>
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
                                            <div onclick='window.location.href = "/login.sys"' class="mark_on_hover"><span>{if empty($labels._Login)}{t}Login{/t}{else}{$labels._Login}{/if}</span></div>
                                            <div onclick='window.location.href = "/register.sys"' class="mark_on_hover"><span
                                                        style="height: 30px">{if empty($labels._Register)}{t}Register{/t}{else}{$labels._Register}{/if}</span></div>
                                        </div>


                                        {if $product->get('Status')=='Discontinued' }
                                            <div class="section description_block alert alert-error alert-title" style="text-align:center">
                                                {t}Sorry, this product is discontinued{/t}
                                            </div>
                                        {/if}


                                    </div>
                                {/if}


                                <div id="product_description" style="margin-top:20px;padding-top:20px;position: relative;top:10px" class="product_description_block fr-view {$content.description_block.class}">
                                    {$content.description_block.content}
                                </div>


                            </div>

                            <div style="clear: both;height: 10px"></div>


                        </div>


                    </div>

                    <script type="application/ld+json">
                        {
                          "@context": "http://schema.org",
                          "@type": "Product",

                          "image": "{$product->get('Image')}",
                          "name": "{$product->get('Name')|escape}",
                          "sku": "{$product->get('Code')|escape}"


                        }


                    </script>

                    <section class="product_tabs" style="margin-top:20px;margin-bottom: 40px">
                        <h6 class="hide">{t}Properties{/t}</h6>

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
                                        <p style="width:70%;margin:0px"> {$Materials}</p>
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
                    {assign 'see_also'  $webpage->get_see_also() }


                    <div class=" {if $see_also|@count eq 0}hide{/if}">
                        <div class="title" style="margin-left:20px;ztext-align: center">{t}See also{/t}</div>

                        <div class="warp">
                            {foreach from=$see_also item=see_also_item name=foo}
                                <div class="warp_element see_also">
                                    <div class="image" >
                                        <a href="{$see_also_item->get('URL')}">
                                            <img src="{$see_also_item->get('Image')}" alt=""/>
                                        </a>


                                    </div>
                                    <div class="link" style="">
                                        <a href="{$see_also_item->get('URL')}">{$see_also_item->get('Name')}</a>
                                    </div>
                                </div>
                            {/foreach}
                        </div>
                    </div>


                </div>
            </div>




            <div class="clearfix marb12"></div>

            {include file="theme_1/footer.theme_1.EcomB2B.tpl"}

        </div>

    </div>


</div>

    <script>
        $(function () {
            $('.fotorama').fotorama();
        });
    </script>


{include file="theme_1/bottom_scripts.theme_1.EcomB2B.tpl"}</body>

</html>