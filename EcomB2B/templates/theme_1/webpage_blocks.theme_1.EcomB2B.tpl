{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 17 July 2017 at 10:04:33 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}

{include file="theme_1/_head.theme_1.EcomB2B.tpl"}
<body xmlns="http://www.w3.org/1999/html" data-device_prefix="" class="pweb desktop {$website->get('background_type')}"  data-ws="n" data-ws_key="" >
{include file="analytics.tpl"}

{if $logged_in}
    <span id="ordering_settings" class="hide" data-website_key="{$website->id}" data-labels='{
    "zero_money":"{$zero_money}",
    "ordered":"<i class=\"fa fa-thumbs-up fa-flip-horizontal fa-fw \" aria-hidden=\"true\"></i> <span class=\"order_button_text\"> {if empty($labels._ordering_ordered)}{t}Ordered{/t}{else}{$labels._ordering_ordered}{/if}</span>",
    "order":"<i class=\"fa fa-hand-pointer fa-fw \" aria-hidden=\"true\"></i>  <span class=\"order_button_text\">{if empty($labels._ordering_order_now)}{t}Order now{/t}{else}{$labels._ordering_order_now}{/if}</span>",
    "update":"<i class=\"fa fa-hand-pointer fa-fw \" aria-hidden=\"true\"></i>  <span class=\"order_button_text\">{if empty($labels._ordering_updated)}{t}Updated{/t}{else}{$labels._ordering_updated}{/if}</span>"
    }'></span>
{/if}
<div class="wrapper_boxed">
    <div class="site_wrapper">
        {include file="theme_1/header.theme_1.EcomB2B.tpl"}
        <div id="body" class="{$website->get('content_background_type')}">
            {if $navigation.show}
                <div class="navigation top_body">
                    <div class="breadcrumbs">
                        {foreach from=$navigation.breadcrumbs item=$breadcrumb name=breadcrumbs}
                            <span class="breadcrumb {if isset($breadcrumb.class)}{$breadcrumb.class}{/if} "><a href="{$breadcrumb.link}" title="{$breadcrumb.title}">{$breadcrumb.label}</a> </span>
                            {if !$smarty.foreach.breadcrumbs.last}
                                <i class="fas padding_left_10 padding_right_10 fa-angle-double-right arrows_{$smarty.foreach.breadcrumbs.iteration}"></i>
                            {/if}
                        {/foreach}
                    </div>
                    <div class="nav">{if $navigation.prev}<a href="{$navigation.prev.link}" title="{$navigation.prev.title}"><i class="fas fa-arrow-left"></i></a>{/if} {if $navigation.next}<a
                            href="{$navigation.next.link}" title="{$navigation.next.title}"><i class="fas fa-arrow-right next"></i></a>{/if}</div>
                    <div style="clear:both"></div>
                </div>
            {/if}
            {if isset($discounts) and count($discounts.deals)>0 }
                <div class="discounts top_body">
                    {foreach from=$discounts.deals item=deal_data }
                        <div class="discount_card" key="{$deal_data.key}">
                            <div class="discount_icon">{$deal_data.icon}</div>
                            <span class="discount_name">{$deal_data.name}</span>
                            {if  $deal_data.until!=''}
                                <small class="padding_left_10"><span id="_offer_valid_until" class="website_localized_label">
                                {if !empty($labels._offer_valid_until)}{$labels._offer_valid_until}{else}{t}Valid until{/t}{/if}</span>: {$deal_data.until_formatted}
                                </small>
                            {/if}
                            <br/>
                            <span class="discount_term">{$deal_data.term}</span>
                            <span class="discount_allowance">{$deal_data.allowance}</span>
                        </div>
                    {/foreach}
                    <div style="clear:both"></div>
                </div>
            {/if}
            {assign "with_iframe" false}
            {assign "with_login" false}
            {assign "with_register" false}
            {assign "with_basket" false}
            {assign "with_client_basket" false}
            {assign "with_checkout" false}
            {assign "with_profile" false}
            {assign "with_favourites" false}
            {assign "with_client" false}
            {assign "with_portfolio" false}
            {assign "with_products_portfolio" false}
            {assign "with_balance" false}
            {assign "with_clients" false}
            {assign "with_clients_orders" false}
            {assign "with_client_order" false}
            {assign "with_client_order_new" false}
            {assign "with_search" false}
            {assign "with_thanks" false}
            {assign "with_gallery" false}
            {assign "with_product_order_input" false}
            {assign "with_reset_password" false}
            {assign "with_unsubscribe" false}
            {assign "with_category_products" false}
            {assign "with_datatables" false}
            {assign "with_catalogue" false}
            {assign "with_top_up" false}

            {if $webpage->get('Webpage Scope')=='Category Products'}
                {if $website->get('Website Type')=='EcomDS' and $logged_in}
                    <div class="top_menu" >
                        <span><i class="fal fa-database"></i> {t}Families’ products data feed{/t}  (
                            <a href="ar_web_catalog_data_feed.php?output=CSV&scope=category&scope_key={$webpage->get('Webpage Scope Key')}">.cvs</a>,
                            <a href="ar_web_catalog_data_feed.php?output=XLSX&scope=category&scope_key={$webpage->get('Webpage Scope Key')}">.xlsx</a>,
                            <a href="ar_web_catalog_data_feed.php?output=Json&scope=category&scope_key={$webpage->get('Webpage Scope Key')}">json</a>
                            )</span> <span style="font-size: xx-small" class=" discreet">UTF-8 encoding</span>
                        <span style="margin-left: 30px" title="{t}Families' images (including products){/t}"><i class="fal fa-images"></i> {t}Images{/t} </span>( <a href="catalog_images.zip.php?scope=category&scope_key={$webpage->get('Webpage Scope Key')}">.zip</a> )

                        <div class="portfolio_in_family hide" style="float:right" ><span title="{t}Items in portfolio{/t}"><i class="fa fa-store-alt "></i> <span class="number_products_in_portfolio_in_family"></span>/<span class="number_products_in_family"></span></span> <span data-category_key="{$webpage->get('Webpage Scope Key')}" class="add_all_family_to_portfolio small like_button  padding_left_10  "><i  class="fa  fa-plus smaller "></i>
                                <span class="hide add_rest_label">{if empty($labels._add_rest_family_to_portfolio)}{t}Add rest of family to portfolio{/t}{else}{$labels._add_rest_family_to_portfolio}{/if}</span>
                                <span class="hide add_family_label">{if empty($labels._add_family_to_portfolio)}{t}Add family to portfolio{/t}{else}{$labels._add_family_to_portfolio}{/if}</span>
                            </span>
                        </div>
                    </div>
                {/if}
            {/if}
            {if $webpage->get('Webpage Code')=='catalogue.sys' and $logged_in}

                    <div class="top_menu" >
                        <span><i class="fal fa-database"></i> <span class="catalogue_data_feed_title">{t}All products data feed{/t}</span>  (
                            <a class="catalogue_data_feed_csv" href="ar_web_catalog_data_feed.php?output=CSV&scope=website&scope_key={$website->id}">.cvs</a>,
                            <a class="catalogue_data_feed_xlsx" href="ar_web_catalog_data_feed.php?output=XLSX&scope=website&scope_key={$website->id}">.xlsx</a>,
                            <a class="catalogue_data_feed_json" href="ar_web_catalog_data_feed.php?output=Json&scope=website&scope_key={$website->id}">json</a>
                            )</span>


                    </div>



            {/if}

            {if $webpage->get('Webpage Code')=='portfolio.sys' and $logged_in}

                    <div class="top_menu" >



                        <div class="portfolio_data_feeds hide">
                        <span><i class="fal fa-database"></i> {t}Portfolio products data feed{/t}  (
                            <a class="csv" href="">.cvs</a>,
                            <a class="xlsx" href="">.xlsx</a>,
                            <a class="json" href="">json</a>
                            )</span>  <span style="font-size: xx-small" class=" discreet">UTF-8 encoding</span>
                        <span style="margin-left: 30px" title="{t}Portfolio images (including products){/t}"><i class="fal fa-images"></i> {t}Images{/t} </span>( <a class="images_zip" href="">.zip</a> )
                        </div>



                        <div class="portfolio_right_menu small " style="float:right" >
                            <span class="like_button open_notifications hide"><i class="fa fa-bell"></i> {t}Notifications{/t}</span>
                        </div>
                    </div>




            {/if}

            {if !empty($content.blocks) and  $content.blocks|is_array}
                {foreach from=$content.blocks item=$block key=key}

                    

                    {if $block.show}

                        {if $block.type=='basket' }
                            {if $logged_in}{assign "with_basket" 1}
                                <div id="basket">
                                    <div style="text-align: center">
                                        <i style="font-size: 60px;padding:100px" class="fa fa-spinner fa-spin"></i>
                                    </div>

                                </div>
                            {else}
                                {include file="theme_1/blk.forbidden.theme_1.EcomB2B.tpl" data=$block key=$key   }
                            {/if}
                        {elseif $block.type=='client_basket'}
                            {if $logged_in}{assign "with_client_basket" 1}
                                <div id="client_basket">
                                    <div style="text-align: center">
                                        <i style="font-size: 60px;padding:100px" class="fa fa-spinner fa-spin"></i>
                                    </div>

                                </div>
                            {else}
                                {include file="theme_1/blk.forbidden.theme_1.EcomB2B.tpl" data=$block key=$key   }
                            {/if}
                        {elseif $block.type=='profile'}
                            {if $logged_in}
                                {assign "with_profile" 1}
                                <div id="profile">
                                    <div style="text-align: center">
                                        <i style="font-size: 60px;padding:100px" class="fa fa-spinner fa-spin"></i>
                                    </div>
                                </div>
                            {else}
                                {include file="theme_1/blk.forbidden.theme_1.EcomB2B.tpl" data=$block key=$key   }
                            {/if}
                        {elseif $block.type=='client'}
                            {if $logged_in}
                                {assign "with_client" 1}
                                <div id="client">
                                    <div style="text-align: center">
                                        <i style="font-size: 60px;padding:100px" class="fa fa-spinner fa-spin"></i>
                                    </div>
                                </div>
                            {else}
                                {include file="theme_1/blk.forbidden.theme_1.EcomB2B.tpl" data=$block key=$key   }
                            {/if}



                        {elseif $block.type=='checkout'}
                            {if $logged_in}{assign "with_checkout" 1}
                                <div id="checkout">
                                    <div style="text-align: center">
                                        <i style="font-size: 60px;padding:100px" class="fa fa-spinner fa-spin"></i>
                                    </div>
                                </div>
                            {else}
                                {include file="theme_1/blk.forbidden.theme_1.EcomB2B.tpl" data=$block key=$key   }
                            {/if}
                        {elseif $block.type=='top_up'}
                            {if $logged_in}{assign "with_top_up" 1}


                                <div id="top_up">
                                    <div style="text-align: center">
                                        <i style="font-size: 60px;padding:100px" class="fa fa-spinner fa-spin"></i>
                                    </div>
                                </div>
                            {else}
                                {include file="theme_1/blk.forbidden.theme_1.EcomB2B.tpl" data=$block key=$key   }
                            {/if}
                        {elseif $block.type=='favourites'}

                            {if $logged_in}
                                {assign "with_favourites" 1}
                                {assign "with_category_products" 1}
                                <div id="favourites">
                                    <div style="text-align: center">
                                        <i style="font-size: 60px;padding:100px" class="fa fa-spinner fa-spin"></i>
                                    </div>
                                </div>
                            {else}
                                {include file="theme_1/blk.forbidden.theme_1.EcomB2B.tpl" data=$block key=$key   }
                            {/if}
                        {elseif $block.type=='portfolio'}

                            {if $logged_in}
                                {assign "with_portfolio" 1}
                                {assign "with_datatables" 1}
                                {include file="theme_1/blk.{$block.type}.theme_1.EcomB2B.tpl" data=$block key=$key  }

                            {else}
                                {include file="theme_1/blk.forbidden.theme_1.EcomB2B.tpl" data=$block key=$key   }
                            {/if}
                        {elseif $block.type=='balance'}

                            {if $logged_in}
                                {assign "with_balance" 1}
                                {assign "with_datatables" 1}
                                {include file="theme_1/blk.{$block.type}.theme_1.EcomB2B.tpl" data=$block key=$key  }

                            {else}
                                {include file="theme_1/blk.forbidden.theme_1.EcomB2B.tpl" data=$block key=$key   }
                            {/if}
                        {elseif $block.type=='catalogue'}
                            {assign "with_datatables" 1}
                            {assign "with_catalogue" 1}

                                {include file="theme_1/blk.{$block.type}.theme_1.EcomB2B.tpl" data=$block key=$key  }


                        {elseif $block.type=='clients'}

                            {if $logged_in}
                                {assign "with_clients" 1}
                                {assign "with_datatables" 1}
                                {include file="theme_1/blk.{$block.type}.theme_1.EcomB2B.tpl" data=$block key=$key  }

                            {else}
                                {include file="theme_1/blk.forbidden.theme_1.EcomB2B.tpl" data=$block key=$key   }
                            {/if}
                        {elseif $block.type=='clients_orders'}

                            {if $logged_in}
                                {assign "with_clients_orders" 1}
                                {assign "with_datatables" 1}
                                {include file="theme_1/blk.{$block.type}.theme_1.EcomB2B.tpl" data=$block key=$key  }

                            {else}
                                {include file="theme_1/blk.forbidden.theme_1.EcomB2B.tpl" data=$block key=$key   }
                            {/if}

                        {elseif $block.type=='client_order_new'}

                            {if $logged_in}
                                {assign "with_client_order_new" 1}
                                {assign "with_datatables" 1}
                                {include file="theme_1/blk.{$block.type}.theme_1.EcomB2B.tpl" data=$block key=$key  }

                            {else}
                                {include file="theme_1/blk.forbidden.theme_1.EcomB2B.tpl" data=$block key=$key   }
                            {/if}
                        {elseif $block.type=='client_order'}

                            {if $logged_in}
                                {assign "with_client_order" 1}
                                {assign "with_datatables" 1}
                                <div id="client_order">
                                    <div style="text-align: center">
                                        <i style="font-size: 60px;padding:100px" class="fa fa-spinner fa-spin"></i>
                                    </div>
                                </div>

                            {else}
                                {include file="theme_1/blk.forbidden.theme_1.EcomB2B.tpl" data=$block key=$key   }
                            {/if}

                        {elseif $block.type=='thanks'}



                            {if $logged_in}{assign "with_thanks" 1}
                                <div id="thanks">
                                    <div style="text-align: center">
                                        <i style="font-size: 60px;padding:100px" class="fa fa-spinner fa-spin"></i>
                                    </div>

                                </div>
                            {else}
                                {include file="theme_1/blk.forbidden.theme_1.EcomB2B.tpl" data=$block key=$key   }
                            {/if}
                        {elseif $block.type=='login'}

                            {if !$logged_in}


                                {assign "with_login" 1}
                                {include file="theme_1/blk.{$block.type}.theme_1.EcomB2B.tpl" data=$block key=$key  }

                            {else}
                                {include file="theme_1/blk.already_logged_in.theme_1.EcomB2B.tpl" data=$block key=$key   }
                            {/if}
                        {elseif $block.type=='register'}

                            {if !$logged_in}
                                {assign "with_register" 1}
                                {include file="theme_1/blk.{$block.type}.theme_1.EcomB2B.tpl" data=$block key=$key  }

                            {else}
                                {include file="theme_1/blk.already_logged_in.theme_1.EcomB2B.tpl" data=$block key=$key   }
                            {/if}
                        {else}
                            {if $block.type=='search'   }{assign "with_search" 1}{/if}
                            {if $block.type=='iframe'   }{assign "with_iframe" 1}{/if}
                            {if $block.type=='product'   }{assign "with_gallery" 1}{/if}
                            {if $block.type=='reset_password' }{assign "with_reset_password" 1}{/if}
                            {if $block.type=='unsubscribe'}{assign "with_unsubscribe" 1}{/if}

                            {if $block.type=='category_products' or   $block.type=='products'  or   $block.type=='product' }


                                {if $store->get('Store Type')=='Dropshipping'}
                                    {assign "with_products_portfolio" 1}
                                {else}
                                    {assign "with_product_order_input" 1}
                                {/if}


                            {/if}
                            {if $block.type=='category_products'  }


                                {if $store->get('Store Type')=='Dropshipping'}
                                    {assign "with_products_portfolio" 1}
                                {else}
                                    {assign "with_category_products" 1}
                                {/if}

                            {/if}



                            {include file="theme_1/blk.{$block.type}.theme_1.EcomB2B.tpl" data=$block key=$key  }

                        {/if}

                    {/if}
                {/foreach}
            {/if}

        </div>


        {include file="theme_1/footer.theme_1.EcomB2B.tpl" }


    </div>

</div>

{include file="theme_1/scripts_webpage_blocks.theme_1.EcomB2B.tpl"}
</body></html>