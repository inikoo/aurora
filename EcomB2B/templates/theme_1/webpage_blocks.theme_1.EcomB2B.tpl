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
    {* Start of Announcement *}
    <script>
        window.addEventListener('message', function(event) {
            console.log('received message', event.data)

            if (event?.data?.height) {
                document.getElementById('wowsbar_announcement_iframe').style.height = event.data.height
            }
        })
    </script>

    {if  $account_code=='INDO'  }
        {if $logged_in}
            <iframe
                id="wowsbar_announcement_iframe"
                src="https://delivery-staging.wowsbar.com/announcement?logged_in=true&domain={$smarty.server.HTTP_HOST}{$smarty.server.REQUEST_URI}"
                frameBorder="0"
                allowTransparency="true"
                class="tw-border-none tw-w-full tw-block tw-bg-transparent tw-isolate tw-relative"
                style="height: 0px"
            >
            </iframe>
        {else}
            <iframe
                id="wowsbar_announcement_iframe"
                src="https://delivery-staging.wowsbar.com/announcement?logged_in=false&domain={$smarty.server.HTTP_HOST}{$smarty.server.REQUEST_URI}"
                frameBorder="0"
                allowTransparency="true"
                class="tw-border-none tw-w-full tw-block tw-bg-transparent tw-isolate tw-relative"
                style="height: 0px"
            >
            </iframe>
        {/if}
    {else}
        {if $logged_in}
            <iframe
                id="wowsbar_announcement_iframe"
                src="https://delivery.wowsbar.com/announcement?logged_in=true&domain={$smarty.server.HTTP_HOST}{$smarty.server.REQUEST_URI}"
                frameBorder="0"
                allowTransparency="true"
                class="tw-border-none tw-w-full tw-block tw-bg-transparent tw-isolate tw-relative"
                style="height: 0px"
            >
            </iframe>
        {else}
            <iframe
                id="wowsbar_announcement_iframe"
                src="https://delivery.wowsbar.com/announcement?logged_in=false&domain={$smarty.server.HTTP_HOST}{$smarty.server.REQUEST_URI}"
                frameBorder="0"
                allowTransparency="true"
                class="tw-border-none tw-w-full tw-block tw-bg-transparent tw-isolate tw-relative"
                style="height: 0px"
            >
            </iframe>
        {/if}
    {/if}
    {* End of Announcement *}

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
            {assign "with_custom_design_products" false}
            {assign "with_customer_discounts" false}
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
            {assign "with_blackboard" false}

            {if $webpage->get('Webpage Scope')=='Category Products'}
                {if $website->get('Website Type')=='EcomDS' and $logged_in}
                    <div class="top_menu" >
                        <span><i class="fal fa-database"></i> {t}Families' products data feed{/t}  (
                            <a href="ar_web_catalog_data_feed.php?output=CSV&scope=category&scope_key={$webpage->get('Webpage Scope Key')}">.cvs</a>,
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
                            <a class="catalogue_data_feed_json" href="ar_web_catalog_data_feed.php?output=Json&scope=website&scope_key={$website->id}">json</a>
                            )</span>


                    </div>



            {/if}

            {if $webpage->get('Webpage Code')=='portfolio.sys' and $logged_in}

                    <div class="top_menu" >



                        <div class="portfolio_data_feeds hide">
                        <span><i class="fal fa-database"></i> {t}Portfolio products data feed{/t}  (
                            <a class="csv" href="">.cvs</a>,
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
                                <span style="display:none" id="show_error"  data-show="{if isset($with_payment_error)}{$with_payment_error}{/if}"  ></span>
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


                                <span style="display:none" id="show_error"  data-show="{if isset($with_payment_error)}{$with_payment_error}{/if}"  ></span>

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
                        {elseif $block.type=='custom_design_products'}
                            {if $logged_in}
                                {assign "with_custom_design_products" 1}
                                {assign "with_category_products" 1}
                                <div id="custom_design_products">
                                    <div style="text-align: center">
                                        <i style="font-size: 60px;padding:100px" class="fa fa-spinner fa-spin"></i>
                                    </div>
                                </div>
                            {else}
                                {include file="theme_1/blk.forbidden.theme_1.EcomB2B.tpl" data=$block key=$key   }
                            {/if}
                        {elseif $block.type=='customer_discounts'}
                            {if $logged_in}
                                {assign "with_customer_discounts" 1}
                                {assign "with_category_products" 1}
                                <div id="customer_discounts">
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
                            {if $block.type=='text'    }{assign "with_blackboard" 1}{/if}
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
            {if $webpage->get('Webpage Scope')=='Product'   }
                {assign reviews_data  $store->get('reviews_data')}

                {if isset($reviews_data['provider']) and $reviews_data['provider']=='reviews.io' and isset($reviews_data['data']['store'])  and false   }

                    <div style="padding:20px 40px">
                        <div id="ReviewsWidget"></div>


                    </div>

                    <script src="https://widget.reviews.co.uk/polaris/build.js"></script>
                    <script>
                        new ReviewsWidget('#ReviewsWidget', {
//Your REVIEWS.io Store ID and widget type:
                                            store : '{$reviews_data['data']['store']}',
                                            widget: 'polaris',

//Content settings (store_review,product_review,third_party_review,questions). Choose what to display in this widget:
                                            options: {
                                              types: 'product_review,questions',
                                              lang : 'en',
                                              //Possible layout options: bordered, large and reverse.
                                              layout: '',
                                              //How many reviews & questions to show per page?
                                              per_page          : 5,
                                              store_review      : {
                                                hide_if_no_results: true,
                                              },
                                              third_party_review: {
                                                hide_if_no_results: true,
                                              },
                                              //Product specific settings. Provide product SKU for which reviews should be displayed:
                                              product_review: {
                                                //Display product reviews - include multiple product SKUs seperated by Semi-Colons (Main Indentifer in your product catalog )
                                                sku               : '{$product->get('Code')}',
                                                hide_if_no_results: true,
                                              },
                                              //Questions settings:
                                              questions: {
                                                hide_if_no_results : false,
                                                enable_ask_question: true,
                                                show_dates         : true,
                                                //Display group questions by providing a grouping variable, new questions will be assigned to this group.
                                                grouping: '[Group questions by providing a grouping variable here or a specific product SKU]'
                                              },
                                              //Header settings:
                                              header: {
                                                enable_summary            : true, //Show overall rating & review count
                                                enable_ratings            : true,
                                                enable_attributes         : true,
                                                enable_image_gallery      : true, //Show photo & video gallery
                                                enable_percent_recommended: false, //Show what percentage of reviewers recommend it
                                                enable_write_review       : {if $logged_in}true{else}false{/if}, //Show "Write Review" button
                                                enable_ask_question       : true, //Show "Ask Question" button
                                                enable_sub_header         : true, //Show subheader
                                                rating_decimal_places     : 2,
                                              },

                                              //Filtering settings:
                                              filtering: {
                                                enable                                : true, //Show filtering options
                                                enable_text_search                    : false, //Show search field
                                                enable_sorting                        : false, //Show sorting options (most recent, most popular)
                                                enable_media_filter                   : false, //Show reviews with images/video/media options
                                                enable_overall_rating_filter          : false, //Show overall rating breakdown filter
                                                enable_language_filter                : false, // Filter by review language
                                                enable_language_filter_language_change: false, // Update widget language based on language selected
                                                enable_ratings_filters                : false, //Show product attributes filter
                                                enable_attributes_filters             : false, //Show author attributes filter
                                              },

                                              //Review settings:
                                              reviews: {
                                                enable_avatar             : true, //Show author avatar
                                                enable_reviewer_name      : false, //Show author name
                                                enable_reviewer_address   : true, //Show author location
                                                reviewer_address_format   : 'city, country', //Author location display format
                                                enable_verified_badge     : true, //Show "Verified Customer" badge
                                                review_content_filter     : 'undefined', //Filter content
                                                enable_reviewer_recommends: true, //Show "I recommend it" badge
                                                enable_attributes         : true, //Show author attributes
                                                enable_product_name       : true, //Show display product name
                                                enable_review_title       : undefined, //Show review title
                                                enable_replies            : undefined, //Show review replies
                                                enable_images             : false, //Show display review photos
                                                enable_ratings            : false, //Show product attributes (additional ratings)
                                                enable_share              : false, //Show share buttons
                                                enable_helpful_vote       : true, //Show "was this helpful?" section
                                                enable_helpful_display    : true, //Show how many times times review upvoted
                                                enable_report             : false, //Show report button
                                                enable_date               : true, //Show when review was published
                                              },
                                            },
//Translation settings
                                            translations: {
                                              'Verified Customer': 'Verified Customer'
                                            },

                                            styles: {
                                              //Base font size is a reference size for all text elements. When base value gets changed, all TextHeading and TexBody elements get proportionally adjusted.
                                              '--base-font-size': '16px',
                                              //Button styles (shared between buttons):
                                              '--common-button-font-family'
                                                  : 'inherit', '--common-button-font-size':'12px', '--common-button-font-weight':'200', '--common-button-letter-spacing':'0', '--common-button-text-transform':'none', '--common-button-vertical-padding':'10px', '--common-button-horizontal-padding':'20px', '--common-button-border-width':'2px', '--common-button-border-radius':'0px',
                                              //Primary button styles:
                                              '--primary-button-bg-color': '#4B5058', '--primary-button-border-color': '#4B5058', '--primary-button-text-color': '#ffffff',
                                              //Secondary button styles:
                                              '--secondary-button-bg-color': 'transparent', '--secondary-button-border-color': '#4B5058', '--secondary-button-text-color': '#0E1311',
                                              //Star styles:
                                              '--common-star-color': '#0E1311', '--common-star-disabled-color': 'rgba(0,0,0,0.25)', '--medium-star-size': '22px', '--small-star-size': '19px',
                                              //Heading styles:
                                              '--heading-text-color': '#0E1311', '--heading-text-font-weight': '600', '--heading-text-font-family': 'inherit', '--heading-text-line-height': '2.4', '--heading-text-letter-spacing': '0', '--heading-text-transform': 'none',
                                              //Body text styles:
                                              '--body-text-color': '#4B5058', '--body-text-font-weight': '100', '--body-text-font-family': 'inherit', '--body-text-line-height': '1.2', '--body-text-letter-spacing': '0px', '--body-text-transform': 'none',
                                              //Input field styles:
                                              '--inputfield-text-font-family': 'inherit', '--input-text-font-size': '14px', '--inputfield-text-font-weight': '400', '--inputfield-text-color': '#4B5058', '--inputfield-border-color': 'rgba(0,0,0,0.2)', '--inputfield-background-color': 'transparent', '--inputfield-border-width': '1px', '--inputfield-border-radius': '0px', '--common-border-color': 'rgba(0,0,0,0.15)', '--common-border-width': '1px', '--common-sidebar-width': '190px',
                                              //Slider indicator (for attributes) styles:
                                              '--slider-indicator-bg-color': 'rgba(0,0,0,0.1)', '--slider-indicator-button-color': '#4B5058', '--slider-indicator-width': '190px',
                                              //Badge styles:
                                              '--badge-icon-color': '#4B5058', '--badge-icon-font-size': 'inherit', '--badge-text-color': '#4B5058', '--badge-text-font-size': 'inherit', '--badge-text-letter-spacing': 'inherit', '--badge-text-transform': 'capitalize',
                                              //Author styles:
                                              '--author-font-size': 'inherit', '--author-text-transform': 'none',
                                              //Author avatar styles:
                                              '--avatar-thumbnail-size': '30px', '--avatar-thumbnail-border-radius': '30px', '--avatar-thumbnail-text-color': '#FFFFFF', '--avatar-thumbnail-bg-color': '4B5058',
                                              //Product photo or review photo styles:
                                              '--photo-video-thumbnail-size': '20px', '--photo-video-thumbnail-border-radius': '0px',
                                              //Media (photo & video) slider styles:
                                              '--mediaslider-scroll-button-icon-color': '#0E1311', '--mediaslider-scroll-button-bg-color': 'rgba(255, 255, 255, 0.85)', '--mediaslider-overlay-text-color': '#ffffff', '--mediaslider-overlay-bg-color': 'rgba(0, 0, 0, 0.8))', '--mediaslider-item-size': '110px',
                                              //Pagination & tabs styles (normal):
                                              '--pagination-tab-text-color': '#0E1311', '--pagination-tab-text-transform': 'none', '--pagination-tab-text-letter-spacing': '0', '--pagination-tab-text-font-size': '12px', '--pagination-tab-text-font-weight': '600',
                                              //Pagination & tabs styles (active):
                                              '--pagination-tab-active-text-color': '#0E1311', '--pagination-tab-active-text-font-weight': '600', '--pagination-tab-active-border-color': '#0E1311', '--pagination-tab-border-width': '3px',

                                            }
                                          }
                          );





                </script>

                {/if}


            {elseif $webpage->get('Webpage Scope')=='Category Products'   }
                {assign reviews_data  $store->get('reviews_data')}
            {if isset($reviews_data['provider']) and $reviews_data['provider']=='reviews.io' and isset($reviews_data['data']['store'])  and false  }


                <div style="padding:20px 40px">
                    <div id="ReviewsWidget"></div>


                </div>

                <script src="https://widget.reviews.co.uk/polaris/build.js"></script>
                <script>
                    new ReviewsWidget('#ReviewsWidget',
                                      {
//Your REVIEWS.io Store ID and widget type:
                                        store : '{$reviews_data['data']['store']}',
                                        widget: 'polaris',

//Content settings (store_review,product_review,third_party_review,questions). Choose what to display in this widget:
                                        options: {
                                          types: 'product_review,questions',
                                          lang : 'en',
                                          //Possible layout options: bordered, large and reverse.
                                          layout: '',
                                          //How many reviews & questions to show per page?
                                          per_page          : 5,
                                          store_review      : {
                                            hide_if_no_results: true,
                                          },
                                          third_party_review: {
                                            hide_if_no_results: true,
                                          },
                                          //Product specific settings. Provide product SKU for which reviews should be displayed:
                                          product_review: {
                                            //Display product reviews - include multiple product SKUs seperated by Semi-Colons (Main Indentifer in your product catalog )
                                            sku               : '{$webpage->get('assets_for_reviews')}',
                                            hide_if_no_results: true,
                                          },
                                          //Questions settings:
                                          questions: {
                                            hide_if_no_results : false,
                                            enable_ask_question: true,
                                            show_dates         : true,
                                            //Display group questions by providing a grouping variable, new questions will be assigned to this group.
                                            grouping: '[Group questions by providing a grouping variable here or a specific product SKU]'
                                          },
                                          //Header settings:
                                          header: {
                                            enable_summary            : true, //Show overall rating & review count
                                            enable_ratings            : true,
                                            enable_attributes         : true,
                                            enable_image_gallery      : true, //Show photo & video gallery
                                            enable_percent_recommended: false, //Show what percentage of reviewers recommend it
                                            enable_write_review       : {if $logged_in}true{else}false{/if}, //Show "Write Review" button
                                            enable_ask_question       : true, //Show "Ask Question" button
                                            enable_sub_header         : true, //Show subheader
                                            rating_decimal_places     : 2,
                                          },

                                          //Filtering settings:
                                          filtering: {
                                            enable                                : true, //Show filtering options
                                            enable_text_search                    : false, //Show search field
                                            enable_sorting                        : false, //Show sorting options (most recent, most popular)
                                            enable_media_filter                   : false, //Show reviews with images/video/media options
                                            enable_overall_rating_filter          : false, //Show overall rating breakdown filter
                                            enable_language_filter                : false, // Filter by review language
                                            enable_language_filter_language_change: false, // Update widget language based on language selected
                                            enable_ratings_filters                : false, //Show product attributes filter
                                            enable_attributes_filters             : false, //Show author attributes filter
                                          },

                                          //Review settings:
                                          reviews: {
                                            enable_avatar             : true, //Show author avatar
                                            enable_reviewer_name      : false, //Show author name
                                            enable_reviewer_address   : true, //Show author location
                                            reviewer_address_format   : 'city, country', //Author location display format
                                            enable_verified_badge     : true, //Show "Verified Customer" badge
                                            review_content_filter     : 'undefined', //Filter content
                                            enable_reviewer_recommends: true, //Show "I recommend it" badge
                                            enable_attributes         : true, //Show author attributes
                                            enable_product_name       : true, //Show display product name
                                            enable_review_title       : undefined, //Show review title
                                            enable_replies            : undefined, //Show review replies
                                            enable_images             : false, //Show display review photos
                                            enable_ratings            : false, //Show product attributes (additional ratings)
                                            enable_share              : false, //Show share buttons
                                            enable_helpful_vote       : true, //Show "was this helpful?" section
                                            enable_helpful_display    : true, //Show how many times times review upvoted
                                            enable_report             : false, //Show report button
                                            enable_date               : true, //Show when review was published
                                          },
                                        },
//Translation settings
                                        translations: {
                                          'Verified Customer': 'Verified Customer'
                                        },
                                        styles      : {
                                          //Base font size is a reference size for all text elements. When base value gets changed, all TextHeading and TexBody elements get proportionally adjusted.
                                          '--base-font-size': '16px',
                                          //Button styles (shared between buttons):
                                          '--common-button-font-family'
                                              : 'inherit', '--common-button-font-size':'12px', '--common-button-font-weight':'200', '--common-button-letter-spacing':'0', '--common-button-text-transform':'none', '--common-button-vertical-padding':'10px', '--common-button-horizontal-padding':'20px', '--common-button-border-width':'2px', '--common-button-border-radius':'0px',
                                          //Primary button styles:
                                          '--primary-button-bg-color': '#4B5058', '--primary-button-border-color': '#4B5058', '--primary-button-text-color': '#ffffff',
                                          //Secondary button styles:
                                          '--secondary-button-bg-color': 'transparent', '--secondary-button-border-color': '#4B5058', '--secondary-button-text-color': '#0E1311',
                                          //Star styles:
                                          '--common-star-color': '#0E1311', '--common-star-disabled-color': 'rgba(0,0,0,0.25)', '--medium-star-size': '22px', '--small-star-size': '19px',
                                          //Heading styles:
                                          '--heading-text-color': '#0E1311', '--heading-text-font-weight': '600', '--heading-text-font-family': 'inherit', '--heading-text-line-height': '2.4', '--heading-text-letter-spacing': '0', '--heading-text-transform': 'none',
                                          //Body text styles:
                                          '--body-text-color': '#4B5058', '--body-text-font-weight': '100', '--body-text-font-family': 'inherit', '--body-text-line-height': '1.2', '--body-text-letter-spacing': '0px', '--body-text-transform': 'none',
                                          //Input field styles:
                                          '--inputfield-text-font-family': 'inherit', '--input-text-font-size': '14px', '--inputfield-text-font-weight': '400', '--inputfield-text-color': '#4B5058', '--inputfield-border-color': 'rgba(0,0,0,0.2)', '--inputfield-background-color': 'transparent', '--inputfield-border-width': '1px', '--inputfield-border-radius': '0px', '--common-border-color': 'rgba(0,0,0,0.15)', '--common-border-width': '1px', '--common-sidebar-width': '190px',
                                          //Slider indicator (for attributes) styles:
                                          '--slider-indicator-bg-color': 'rgba(0,0,0,0.1)', '--slider-indicator-button-color': '#4B5058', '--slider-indicator-width': '190px',
                                          //Badge styles:
                                          '--badge-icon-color': '#4B5058', '--badge-icon-font-size': 'inherit', '--badge-text-color': '#4B5058', '--badge-text-font-size': 'inherit', '--badge-text-letter-spacing': 'inherit', '--badge-text-transform': 'capitalize',
                                          //Author styles:
                                          '--author-font-size': 'inherit', '--author-text-transform': 'none',
                                          //Author avatar styles:
                                          '--avatar-thumbnail-size': '30px', '--avatar-thumbnail-border-radius': '30px', '--avatar-thumbnail-text-color': '#FFFFFF', '--avatar-thumbnail-bg-color': '4B5058',
                                          //Product photo or review photo styles:
                                          '--photo-video-thumbnail-size': '20px', '--photo-video-thumbnail-border-radius': '0px',
                                          //Media (photo & video) slider styles:
                                          '--mediaslider-scroll-button-icon-color': '#0E1311', '--mediaslider-scroll-button-bg-color': 'rgba(255, 255, 255, 0.85)', '--mediaslider-overlay-text-color': '#ffffff', '--mediaslider-overlay-bg-color': 'rgba(0, 0, 0, 0.8))', '--mediaslider-item-size': '110px',
                                          //Pagination & tabs styles (normal):
                                          '--pagination-tab-text-color': '#0E1311', '--pagination-tab-text-transform': 'none', '--pagination-tab-text-letter-spacing': '0', '--pagination-tab-text-font-size': '12px', '--pagination-tab-text-font-weight': '600',
                                          //Pagination & tabs styles (active):
                                          '--pagination-tab-active-text-color': '#0E1311', '--pagination-tab-active-text-font-weight': '600', '--pagination-tab-active-border-color': '#0E1311', '--pagination-tab-border-width': '3px',


                                        }

                                      }
                    );
                </script>

            {/if}
             {/if}

        </div>



        {include file="theme_1/footer.theme_1.EcomB2B.tpl" }


    </div>

</div>

{include file="theme_1/scripts_webpage_blocks.theme_1.EcomB2B.tpl"}

<script type="text/x-template" id="template-result-default">
    <div class="lb-result lb-result--default">
        <div class="lb-result__inner">
            <a
                :href="resultUrl"
                :data-lb-id="dataLbId"
                class="lb-result__aside"
                @click="ga && ga.sendGAClick(type, url)"
            >
                <div class="lb-result__image-wrapper">
                    <img
                        :src="attributes.image_link"
                        :alt="attributes.title"
                        class="lb-result__image"
                    >
                </div>
            </a>
            <div class="lb-result__main">
                <h2 class="lb-result__title">
                    <a
                        :href="resultUrl"
                        :data-lb-id="dataLbId"
                        class="lb-result__title-link lb-search-text-color-primary-clickable"
                        @click="ga && ga.sendGAClick(type, url)"
                    >
                        {{ attributes.title }}
                    </a>
                </h2>

                <p class="lb-result__description">
                    {{ attributes.description }}
                </p>
                
                <div class="lb-result__actions">
                    <div
                        v-if="attributes.formatted_price"
                        class="lb-result__action-item lb-result__prices"
                    >
                        <div class="lb-result__price">
                            {{ attributes.formatted_price }}
                        </div>
                    </div>

                    <div class="lb-result__action-buttons">
                        <a
                            :href="resultUrl"
                            :data-lb-id="dataLbId"
                            class="lb-btn lb-result__btn-buy lb-result__action-item lb-search-bg-color-primary-clickable"
                            @click="ga && ga.sendGAClick(type, url)"
                        >
                            {{ trans('resultDefault.actionButton')}}
                        </a>
                    </div>
                </div>
                <div
                    v-if="attributes.availability === 0"
                    class="lb-result__availability lb-result__availability--0"
                >
                    {{ trans('resultDefault.availability.0') }}
                </div>
            </div>
        </div>
    </div>
</script>



{if  $account_code=='AW'  }
    {if $website->get('Website Code')=='AW.biz'}
        <script type="module" src="../../js/vika_luigi.js?v=39&trackerId=483878-588294&device_type=desktop&color1=4b5058&color2=957a65&color3=e87928&logged_in={if $logged_in}true{else}false{/if}&language=en&currency_symbol=£"></script>

    {elseif $website->get('Website Code')=='AWD'}
        <script type="module" src="../../js/vika_luigi.js?v=39&trackerId=483878-621865&device_type=desktop&color1=4b5058&color2=957a65&color3=e87928&logged_in={if $logged_in}true{else}false{/if}&language=en&currency_symbol=£"></script>
    {/if}

{elseif  $account_code=='AROMA'  }
    {if $website->get('Website Code')=='Aroma'}
        <script type="module" src="../../js/vika_luigi.js?v=39&trackerId=483878-621871&device_type=desktop&color1=4b5058&color2=957a65&color3=e87928&logged_in={if $logged_in}true{else}false{/if}&language=en&currency_symbol=£"></script>
    {elseif $website->get('Website Code')=='AC'}
        <script type="module" src="../../js/vika_luigi.js?v=39&trackerId=483878-621949&device_type=desktop&color1=4b5058&color2=957a65&color3=e87928&logged_in={if $logged_in}true{else}false{/if}&language=en&currency_symbol=£"></script>
    {/if}

{elseif  $account_code=='AWEU'  }
    {if $website->get('Website Code')=='aw.eu'}
        <script type="module" src="../../js/vika_luigi.js?v=39&trackerId=483878-622491&device_type=desktop&color1=4b5058&color2=957a65&color3=e87928&logged_in={if $logged_in}true{else}false{/if}&language=en&currency_symbol=€"></script>
    {elseif $website->get('Website Code')=='AWD'}
        <script type="module" src="../../js/vika_luigi.js?v=39&trackerId=483878-622636&device_type=desktop&color1=4b5058&color2=957a65&color3=e87928&logged_in={if $logged_in}true{else}false{/if}&language=en&currency_symbol=€"></script>
    {elseif $website->get('Website Code')=='HR'}
        <script type="module" src="../../js/vika_luigi.js?v=39&trackerId=483878-622391&device_type=desktop&color1=4b5058&color2=957a65&color3=e87928&logged_in={if $logged_in}true{else}false{/if}&language=hr&currency_symbol=€"></script>
    {elseif $website->get('Website Code')=='it.eu'}
        <script type="module" src="../../js/vika_luigi.js?v=39&trackerId=483878-622449&device_type=desktop&color1=4b5058&color2=957a65&color3=e87928&logged_in={if $logged_in}true{else}false{/if}&language=it&currency_symbol=€"></script>
    {elseif $website->get('Website Code')=='RO'}
        <script type="module" src="../../js/vika_luigi.js?v=39&trackerId=483878-622387&device_type=desktop&color1=4b5058&color2=957a65&color3=e87928&logged_in={if $logged_in}true{else}false{/if}&language=ro&currency_symbol=lei"></script>
    {elseif $website->get('Website Code')=='hu.eu'}
        <script type="module" src="../../js/vika_luigi.js?v=39&trackerId=483878-622237&device_type=desktop&color1=4b5058&color2=957a65&color3=e87928&logged_in={if $logged_in}true{else}false{/if}&language=hu&currency_symbol=Ft"></script>
    {elseif $website->get('Website Code')=='fr.eu'}
        <script type="module" src="../../js/vika_luigi.js?v=39&trackerId=483878-622603&device_type=desktop&color1=4b5058&color2=957a65&color3=e87928&logged_in={if $logged_in}true{else}false{/if}&language=fr&currency_symbol=€"></script>
    {elseif $website->get('Website Code')=='sk.com'}
        <script type="module" src="../../js/vika_luigi.js?v=39&trackerId=483878-622511&device_type=desktop&color1=4b5058&color2=957a65&color3=e87928&logged_in={if $logged_in}true{else}false{/if}&language=sk&currency_symbol=€"></script>
    {elseif $website->get('Website Code')=='cz.eu'}
        <script type="module" src="../../js/vika_luigi.js?v=39&trackerId=483878-622467&device_type=desktop&color1=4b5058&color2=957a65&color3=e87928&logged_in={if $logged_in}true{else}false{/if}&language=cs&currency_symbol=Kč"></script>
    {elseif $website->get('Website Code')=='pl.eu'}
        <script type="module" src="../../js/vika_luigi.js?v=39&trackerId=483878-622535&device_type=desktop&color1=4b5058&color2=957a65&color3=e87928&logged_in={if $logged_in}true{else}false{/if}&language=pl&currency_symbol=zł"></script>
    {elseif $website->get('Website Code')=='NL'}
        <script type="module" src="../../js/vika_luigi.js?v=39&trackerId=483878-622609&device_type=desktop&color1=4b5058&color2=957a65&color3=e87928&logged_in={if $logged_in}true{else}false{/if}&language=nl&currency_symbol=€"></script>
    {elseif $website->get('Website Code')=='de.eu'}
        <script type="module" src="../../js/vika_luigi.js?v=39&trackerId=483878-622457&device_type=desktop&color1=4b5058&color2=957a65&color3=e87928&logged_in={if $logged_in}true{else}false{/if}&language=de&currency_symbol=€"></script>
    {elseif $website->get('Website Code')=='SE'}
        <script type="module" src="../../js/vika_luigi.js?v=39&trackerId=483878-622292&device_type=desktop&color1=4b5058&color2=957a65&color3=e87928&logged_in={if $logged_in}true{else}false{/if}&language=en&currency_symbol=kr"></script>
    {/if}

{elseif  $account_code=='ES'  }
    {if $website->get('Website Code')=='ADE'}
        <script type="module" src="../../js/vika_luigi.js?v=39&trackerId=483878-622183&device_type=desktop&color1=4b5058&color2=957a65&color3=e87928&logged_in={if $logged_in}true{else}false{/if}&language=de&currency_symbol=€"></script>
    {elseif $website->get('Website Code')=='EU'}
        <script type="module" src="../../js/vika_luigi.js?v=39&trackerId=483878-622130&device_type=desktop&color1=4b5058&color2=957a65&color3=e87928&logged_in={if $logged_in}true{else}false{/if}&language=en&currency_symbol=€"></script>
    {elseif $website->get('Website Code')=='PT'}
        <script type="module" src="../../js/vika_luigi.js?v=39&trackerId=483878-622079&device_type=desktop&color1=4b5058&color2=957a65&color3=e87928&logged_in={if $logged_in}true{else}false{/if}&language=pt&currency_symbol=€"></script>
    {elseif $website->get('Website Code')=='ES'}
        <script type="module" src="../../js/vika_luigi.js?v=39&trackerId=483878-622012&device_type=desktop&color1=4b5058&color2=957a65&color3=e87928&logged_in={if $logged_in}true{else}false{/if}&language=es&currency_symbol=€"></script>
    {elseif $website->get('Website Code')=='FR'}
        <script type="module" src="../../js/vika_luigi.js?v=39&trackerId=483878-621970&device_type=desktop&color1=4b5058&color2=957a65&color3=e87928&logged_in={if $logged_in}true{else}false{/if}&language=fr&currency_symbol=€"></script>
    {elseif $website->get('Website Code')=='DSE'}
        <script type="module" src="../../js/vika_luigi.js?v=39&trackerId=483878-622153&device_type=desktop&color1=4b5058&color2=957a65&color3=e87928&logged_in={if $logged_in}true{else}false{/if}&language=en&currency_symbol=€"></script>
    {/if}
{/if}

<div id="__search_test"></div>
</body></html>