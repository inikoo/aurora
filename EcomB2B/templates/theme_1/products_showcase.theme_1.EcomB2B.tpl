{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 3 July 2017 at 20:07:25 GMT+8, Cyberjaya, Malaysia
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
{assign 'content_data' $webpage->get('Content Published Data')}


{include file="style.tpl" css=$webpage->get('Published CSS') }


<div id="page_content" class="asset_container">

    {if $category->get('Product Category Status')=='Discontinued'}
        <div  class="section description_block alert alert-error alert-title" style="text-align:center">
            <i class="fa fa-frown-o padding_right_20" aria-hidden="true"></i> {t}Discontinued{/t} <i class="fa fa-frown-o padding_left_20" aria-hidden="true"></i>
        </div>
    {/if}

<span id="ordering_settings" class="hide" data-labels='{
    "ordered":"<i class=\"fa fa-thumbs-o-up fa-flip-horizontal fa-fw \" aria-hidden=\"true\"></i> <span class=\"order_button_text\">{t}Ordered{/t}</span>",
    "order":"<i class=\"fa fa-hand-pointer-o fa-fw \" aria-hidden=\"true\"></i>  <span class=\"order_button_text\">{t}Order now{/t}</span>",
    "update":"<i class=\"fa fa-hand-pointer-o fa-fw \" aria-hidden=\"true\"></i>  <span class=\"order_button_text\">{t}Updated{/t}</span>"
    }'></span>


    <div id="description_block" class="description_block {$content_data.description_block.class}" >


        {foreach from=$content_data.description_block.blocks key=id item=data}


        {if $data.type=='text'}

        <div id="{$id}" class="webpage_content_header fr-view text">
            {$data.content}
        </div>
        {elseif $data.type=='image'}
            <div  id="{$id}" class="webpage_content_header webpage_content_header_image"  >
                <img  src="{$data.image_src}"  style="width:100%"  title="{if isset($data.caption)}{$data.caption}{/if}" />
            </div>
        {/if}
        {/foreach}






        <div style="clear:both"></div>
    </div>

    {if $category->get('Product Category Status')=='Discontinued'}
        <div  class="section description_block alert alert-error alert-title" style="text-align:center">
            {t}Sorry, but all products in this web page are discontinued{/t}
        </div>
    {/if}


    <div class="warp">
    {foreach from=$products item=product_data key=stack_index}


        <div class="warp_element">



            {if $product_data.type=='product'}
            {assign 'product' $product_data.object}
            <div id="product_target_div_{$stack_index}" stack_index="{$stack_index}"  product_code="{$product->get('Code')}" product_id="{$product->id}"  class="product_block product_showcase product_container "
                 style="position:relative;border-bottom:none;">



                <a href="{$product->get('Code')|lower}">

                    <i class="fa fa-info-circle more_info" aria-hidden="true" title="More info"></i>
                </a>

                {assign 'favourite_key' {$product->get('Favourite Key',{$customer->id})} }
                <span style="position:absolute;top:5px;left:5px" class="  favourite  " favourite_key={$favourite_key} ><i class="fa {if $favourite_key}fa-heart marked{else}fa-heart-o{/if}" aria-hidden="true"></i>  </span>



                <div class="product_header_text fr-view" >
                    {$product_data.header_text}
                </div>


               <a href="{$product->get('Code')|lower}">
               <div class="wrap_to_center product_image" >


                    <img  src="{$product->get('Image')}" />
                 </div>
                </a>

                <div class="product_description"  >
                    <span class="code">{$product->get('Code')}</span>
                    <div class="name item_name">{$product->get('Name')}</div>

                </div>

                {if $logged_in}
                <div class="product_prices log_in " >
                    <div class="product_price">{t}Price{/t}: {$product->get('Price')}</div>
                    {assign 'rrp' $product->get('RRP')}
                    {if $rrp!=''}<div>{t}RRP{/t}: {$rrp}</div>{/if}
                </div>
                {else}
                <div class="product_prices log_out" >
                    <div >{t}For prices, please login or register{/t}</div>
                 </div>
                {/if}






                {if $logged_in}

                {if $product->get('Web State')=='Out of Stock'}


                    {assign 'reminder_key' {$product->get('Reminder Key',{$user->id})} }
                    <div  class="out_of_stock_row {$product->get('Out of Stock Class')}"  >
                        <span class="label">
                        {$product->get('Out of Stock Label')}
                        <span  class="label sim_button " > <i reminder_key="{$reminder_key}" title="{if $reminder_key>0}{t}Click to remove notification{/t}{else}{t}Click to be notified by email{/t}{/if}"   class="reminder fa {if $reminder_key>0}fa-envelope{else}fa-envelope-o{/if}" aria-hidden="true"></i>  </span>
                        </span>
                    </div>

                {elseif $product->get('Web State')=='For Sale'}
                    {assign 'quantity_ordered' $product->get('Ordered Quantity',$order_key) }
                <div class="order_row {if $quantity_ordered!=''}ordered{else}empty{/if}"      >
                    <input maxlength=6  style="" class='order_input ' id='but_qty{$product->id}'   type="text"' size='2'  value='{$quantity_ordered}' ovalue='{$quantity_ordered}'>
                    {if $quantity_ordered==''}
                        <div class="label sim_button" style="margin-left:57px"  ><i class="fa fa-hand-pointer-o fa-fw" aria-hidden="true"></i> <span class="">{t}Order now{/t}</span></div>
                    {else}
                        <span class="label sim_button"><i class="fa  fa-thumbs-o-up fa-flip-horizontal fa-fw" aria-hidden="true"></i> <span class="">{t}Ordered{/t}</span></span>
                    {/if}

                </div>





                {/if}


                {else}
                <div class=" order_row " style="display:flex;" >
                    <div class="mark_on_hover" style="flex-grow:1;text-align:center;border-right:1px solid #fff;  font-weight: 800;" ><span onClick="location.href='login.php?from={$page->id}'" class="sim_button" >{t}Login{/t}</span></div>
                    <div  class="mark_on_hover" style="flex-grow:1;text-align:center;border-left:1px solid #fff;  font-weight: 800;" ><span onClick="location.href='registration.php'" class="sim_button" >{t}Register{/t}</span></div>
                </div>
                {/if}

            </div>
            {else}
            {if $product_data.data.type=='text'}
                <div id="{$product_data.data.id}" style="position:relative" class=" panel  panel_{$product_data.data.size} {$product_data.data.class}">


                    <div class="panel_content fr-view">
                        {$product_data.data.content}
                    </div>





                </div>
            {elseif $product_data.data.type=='code'}
                <div id="{$product_data.data.id}" code_key="{$product_data.data.key}" style="position:relative;" class=" panel image panel_{$product_data.data.size}">




                    <iframe class="" src="/panel_code.php?id={$product_data.data.key}"      style="position: absolute; height: 100%;width: 100%;padding:0px;margin:0px;background-color: white "
                            marginwidth="0"
                            marginheight="0"
                            hspace="0"
                            vspace="0"
                            frameborder="0"
                            scrolling="no"
                            sandbox="allow-scripts allow-same-origin allow-popups allow-top-navigation"

                    >

                    </iframe>

                </div>




            {elseif $product_data.data.type=='image'}
                    <div id="{$product_data.data.id}" style="position:relative" class=" panel image panel_{$product_data.data.size}">


                        {if $product_data.data.link!=''}
                            <a href="{$product_data.data.link}"><img  src="{$product_data.data.image_src}"  title="{$product_data.data.caption}" /></a>
                        {else}
                            <img  src="{$product_data.data.image_src}"  title="{$product_data.data.caption}" />
                        {/if}






                    </div>
                {/if}
            {/if}
        </div>
    {/foreach}
    </div>

    <div   class=" {if $related_products|@count eq 0}hide{/if}">
        <div class="title" style="margin-left:20px;ztext-align: center">{t}Related products{/t}</div>

        <div class="warp">
        {foreach from=$related_products item=product_data key=stack_index}


            <div class="warp_element">




                    {assign 'product' $product_data.object}
                    <div id="product_target_div_{$stack_index}" stack_index="{$stack_index}"  product_code="{$product->get('Code')}" product_id="{$product->id}"  class="product_block product_showcase product_container"
                         style="position:relative;border-bottom:none;">

                        <a href="{$product->get('Code')|lower}">

                        <i class="fa fa-info-circle more_info" aria-hidden="true" title="More info"></i>
                        </a>
                        {assign 'favourite_key' {$product->get('Favourite Key',{$customer->id})} }
                        <span style="position:absolute;top:5px;left:5px" class="  favourite  " favourite_key={$favourite_key} ><i class="fa {if $favourite_key}fa-heart marked{else}fa-heart-o{/if}" aria-hidden="true"></i>  </span>



                        <div class="product_header_text fr-view" >
                            {$product_data.header_text}
                        </div>


                        <a href="{$product->get('Code')|lower}">
                            <div class="wrap_to_center product_image" >


                                <img  src="{$product->get('Image')}" />
                            </div>
                        </a>

                        <div class="product_description"  >
                            <span class="code">{$product->get('Code')}</span>
                            <div class="name item_name">{$product->get('Name')}</div>

                        </div>
                        {if $logged_in}
                            <div class="product_prices log_in " >
                                <div class="product_price">{t}Price{/t}: {$product->get('Price')}</div>
                                {assign 'rrp' $product->get('RRP')}
                                {if $rrp!=''}<div>{t}RRP{/t}: {$rrp}</div>{/if}
                            </div>
                        {else}
                            <div class="product_prices log_out" >
                                <div >{t}For prices, please login or register{/t}</div>
                            </div>
                        {/if}

                        {if $logged_in}

                            {if $product->get('Web State')=='Out of Stock'}


                                {assign 'reminder_key' {$product->get('Reminder Key',{$user->id})} }
                                <div  class="out_of_stock_row {$product->get('Out of Stock Class')}"  >
    <span class="label">
    {$product->get('Out of Stock Label')}
        <span  class="label sim_button " > <i reminder_key="{$reminder_key}" title="{if $reminder_key>0}{t}Click to remove notification{/t}{else}{t}Click to be notified by email{/t}{/if}"   class="reminder fa {if $reminder_key>0}fa-envelope{else}fa-envelope-o{/if}" aria-hidden="true"></i>  </span>
    </span>
                                </div>






                            {else if $product->get('Web State')=='For Sale'}
                                {assign 'quantity_ordered' $product->get('Ordered Quantity',$order_key) }
                                <div class="order_row {if $quantity_ordered!=''}ordered{else}empty{/if}"      >
                                    <input maxlength=6  style="" class='order_input ' id='but_qty{$product->id}'   type="text"' size='2'  value='{$quantity_ordered}' ovalue='{$quantity_ordered}'>
                                    {if $quantity_ordered==''}
                                        <div class="label sim_button   " style="margin-left:57px"  ><i class="fa fa-hand-pointer-o fa-fw" aria-hidden="true"></i> <span class="">{t}Order now{/t}</span></div>
                                    {else}
                                        <span class="label sim_button"><i class="fa  fa-thumbs-o-up fa-flip-horizontal fa-fw" aria-hidden="true"></i> <span class="">{t}Ordered{/t}</span></span>
                                    {/if}

                                </div>





                            {/if}
                        {else}
                            <div class=" order_row " style="display:flex;" >
                                <div class="mark_on_hover"  style="flex-grow:1;text-align:center;border-right:1px solid #fff;  font-weight: 800;" ><span onClick="location.href='login.php?from={$page->id}'" class="sim_button" >{t}Login{/t}</span></div>
                                <div class="mark_on_hover"  style="flex-grow:1;text-align:center;border-left:1px solid #fff;  font-weight: 800;" ><span onClick="location.href='registration.php'" class="sim_button" >{t}Register{/t}</span></div>
                            </div>
                        {/if}

                    </div>


            </div>

        {/foreach}
        <div style="clear:both"></div>
    </div>




    </div>




    <div id="bottom_see_also"  class="{if $see_also|@count eq 0}hide{/if}">
        <div class="title">{t}See also{/t}:</div>
        <div>
            {foreach from=$see_also item=see_also_item name=foo}
                <div class="item" >
                    <div class="image_container" >
                        <a href="http://{$see_also_item->get('URL')}"> <img src="{$see_also_item->get('Image')}" style="" /> </a>
                    </div>
                    <div class="label" >
                        {$see_also_item->get('Name')}
                    </div>
                </div>
            {/foreach}
        </div>
        <div style="clear:both">
        </div>

    </div>





</div>
            </div>


            <div class="clearfix marb12"></div>

            {include file="theme_1/footer.EcomB2B.tpl"}

        </div>

    </div>


</body>

</html>