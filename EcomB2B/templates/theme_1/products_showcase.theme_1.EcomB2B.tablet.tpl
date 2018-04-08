{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 21 March 2018 at 14:35:43 GMT+8, Sanur, Indonesia, Bali
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}{include file="theme_1/_head.theme_1.EcomB2B.tablet.tpl"}
<body>{include file="analytics.tpl"}
<div id="xpage-transitions">
    {include file="theme_1/header.theme_1.EcomB2B.tablet.tpl"}
    <div id="page-content" class="page-content">
        {*
        {assign 'prev_family'  $category->get_prev_category('data') }
        {assign 'next_family'  $category->get_next_category('data') }
*}


        <div id="page-content-scroll" class="header-clear"><!--Enables this element to be scrolled -->
            <div class="menu-bar" style="margin:0px;height:50px;position: relative;top:-5px;border-bottom:1px solid #ccc">

                    <em class="menu-bar-text-1   ">
                        <a href="/" style="color:#1f2f1f"> <i class="fa fa-home" aria-hidden="true"></i></a> <i class="fa fa-angle-double-right padding_left_5 padding_right_5" aria-hidden="true"></i>
                         <a href="{$parent.code|strtolower}" style="color:#1f2f1f"> {$parent.label|truncate:35:" ..."}</a> <i class="fa fa-angle-double-right padding_left_5 padding_right_5" aria-hidden="true"></i>
                    </em>

                <em class="menu-bar-text-2   " >

                    {$category->get('Code')}
                    {*
                    {if $prev_family}<a href="{$prev_family.webpage_code}" class="color-black " style="margin-right: 10px"><i class="fa fa-arrow-left"></i></a>{/if}

                    {if $next_family}<a href="{$next_family.webpage_code}" class="color-black" style="margin-left: 10px"><i class="fa fa-arrow-right"></i></a>{/if}
*}
                </em>


                <div class="menu-bar-title" style="position: relative;"></div>
            </div>


            {if !$logged_in}
                <a href="login.sys">
                    <div class="notification-medium bg-yellow-light animate-fade">
                        <strong class="bg-yellow-dark"><i class="ion-alert-circled"></i></strong>
                        <h1>{if empty($labels.trade_only)}{t}Trade only website{/t}{else}{$labels.trade_only}{/if}</h1>
                        <p>
                            {if empty($labels._login_to_see)}{t}For prices, please login or register{/t}{else}{$labels._login_to_see}{/if}
                        </p>

                    </div>
                </a>
            {/if}

            <div class="content">

                <div class="   fr-view">
                        {foreach from=$content_data.description_block.blocks key=id item=data name=foo}


                            {if $data.type=='text' and $data.content!=''}
                                {$data.content|replace:'<p><br></p>':''}
                            {elseif $data.type=='image'}
                                <img src="{$data.image_src}" style="width:40%;;{if $smarty.foreach.foo.iteration%2} float:left;margin-right:15px;{else}float:right;margin-left:15px;{/if}"
                                     title="{if isset($data.caption)}{$data.caption}{/if}"/>
                            {/if}
                        {/foreach}


                </div>
                <div class="clear"  style="margin-bottom: 20px"></div>

                {foreach from=$products item=product_data key=stack_index}


                    {if $product_data.type=='product'}
                        {assign 'product' $product_data.object}
                        <div class="store-item-list">
                    <span class="sub_wrap" style="">



                        <div  class="description" style="line-height: normal;text-align: ">{$product->get('Code')} </div>

                        <a href="{$product->get('Code')|strtolower}" ><img  src="{$product->get('Image Mobile In Family Webpage')}" alt="{$product->get('Name')|escape}"></a>


                        <em style="margin-left:185px;padding-left: 0px;" class="single_line_height">


                            <div class="description"  {if ($product->get('Name')|count_characters)>40} style="font-size: 80% {elseif ($product->get('Name')|count_characters)>35}{/if}">{$product->get('Name')}</div>
                            {if $logged_in}
                                <div  style="margin-top: 5px">
                                {$product->get('Price')}
                            </div>
                                {if $product->get('RRP')!=''}
                                <div class="pricex" style="color:#888">
                                  {t}RRP{/t}: {$product->get('RRP')}
                            </div>
{/if}
                            {if $product->get('Web State')=='Out of Stock'}

                                <div style="margin-top:10px;"><span style="padding:5px 10px" class="{if $product->get('Out of Stock Class')=='launching_soon'}highlight-green color-white{else}highlight-red color-white{/if}">{$product->get('Out of Stock Label')}</span></div>
                            {elseif $product->get('Web State')=='For Sale'}
                               {assign 'quantity_ordered' $product->get('Ordered Quantity',$order_key) }
                                <div class="mobile_ordering" style="font-size: 14px" data-settings='{ "pid":{$product->id} }'>
                                <i onclick="save_item_qty_change(this)" class="ordering_button one_less fa fa-fw  fa-minus-circle color-red-dark"></i>
                                <input type="number" min="0" value="{$quantity_ordered}" class="needsclick order_qty">
                                <i onclick="save_item_qty_change(this)" style="display:none" class="ordering_button save far fa-save fa-fw color-blue-dark"></i>
                                <i onclick="save_item_qty_change(this)" class="ordering_button add_one fa fa-fw  fa-plus-circle color-green-dark"></i>
                                </div>
                            {/if}

                            {/if}
                        </em>


                    </span>


                        </div>
                    {/if}
                {/foreach}

            </div>
            <div class="clear"  style="margin-bottom: 20px"></div>

            {include file="theme_1/footer.theme_1.EcomB2B.tablet.tpl"}

        </div>
    </div>

    <a href="#" class="back-to-top-badge"><i class="fas fa-arrow-circle-up"></i></a>

    
</div>
</body>{include file="theme_1/bottom_scripts.theme_1.EcomB2B.mobile.tpl"}</body></html>
