{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 30 August 2017 at 23:51:36 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}
{include file="theme_1/_head.theme_1.EcomB2B.mobile.tpl"}
<body>{include file="analytics.tpl"}
<div id="page-transitions">
    {include file="theme_1/header.theme_1.EcomB2B.mobile.tpl"}
    <div id="page-content" class="page-content">
        <div id="page-content-scroll" class="header-clear"><!--Enables this element to be scrolled -->
            {if $navigation.show}
                <div class="menu-bar" style="margin:0px;height:50px;position: relative;top:-5px;border-bottom:1px solid #ccc">
                    <em class="menu-bar-text-1   ">
                        {foreach from=$navigation.breadcrumbs item=$breadcrumb name=breadcrumbs}
                            <span class="breadcrumbs"><a href="{$breadcrumb.link}"style="color:#1f2f1f" title="{$breadcrumb.title}">{$breadcrumb.label}</a> {if !$smarty.foreach.breadcrumbs.last}<i class="fas padding_left_10 padding_right_10 fa-angle-double-right"></i>{/if}</span>
                        {/foreach}
                    </em>
                    <em class="menu-bar-text-2   " >
                        {if $navigation.prev}<a style="color:#1f2f1f" href="{$navigation.prev.link}" title="{$navigation.prev.title}"><i class="fas fa-arrow-left"></i></a>{/if} {if $navigation.next}<a style="color:#1f2f1f" href="{$navigation.next.link}" title="{$navigation.next.title}"><i style="margin-left:20px" class="fas fa-arrow-right next"></i></a>{/if}
                    </em>
                    <div class="menu-bar-title" style="position: relative;"></div>
                </div>
            {/if}
            {assign "with_iframe" 0}
            {assign "with_basket" 0}
            {assign "with_blackboard" 0}
            {assign "with_product_block" 0}

            {foreach from=$content.blocks item=$block key=key}
                {if $block.show}

                    {if $block.type=='basket' and   !isset($order)  }
                        {include file="theme_1/blk.basket_no_order.theme_1.EcomB2B.mobile.tpl" data=$block key=$key  }

                    {else}

                        {if $block.type=='category_products' or   $block.type=='products' }
                            {assign "with_product_block" 1}
                        {elseif $block.type=='basket'   }{assign "with_basket" 1}
                        {elseif $block.type=='blackboard'   }{assign "with_blackboard" 1}

                        {elseif $block.type=='iframe'   }{assign "with_iframe" 1}{/if}


                        {include file="theme_1/blk.{$block.type}.theme_1.EcomB2B.mobile.tpl" data=$block key=$key  }

                    {/if}

                {/if}
            {/foreach}





            {include file="theme_1/footer.theme_1.EcomB2B.mobile.tpl"}
        </div>
    </div>

    <a href="#" class="back-to-top-badge"><i class="fas fa-arrow-circle-up"></i></a>


</div>




<script>
    {if $with_iframe==1}
    $(document).ready(function () {
        resize_banners();
    });

    $(window).resize(function () {

        resize_banners();

    });

    function resize_banners() {

        console.log('cacas')

        $('.iframe').each(function (i, obj) {
            $(this).css({
                height: $(this).width() * $(this).data('h') / $(this).data('w')
            })
        });
    }


    {/if}

    {if $with_blackboard==1}


    $(".asset_description .show_all").click(function() {

        totalHeight = 0

        $el = $(this);
        $p  = $el.parent();
        $up = $p.parent();
        $ps = $up.find("p:not('.read-more')");

        $ps.each(function() {
            totalHeight += $(this).outerHeight();
        });



        h=$(this).closest('.asset_description').find('.asset_description_wrap').outerHeight();

        $up.css({
                "height": $up.height(),
                "max-height": 9999
            })
            .animate({
                "height": h+30
            });

        // fade out read-more
        $p.fadeOut();

        // prevent jump-down
        return false;

    });{/if}

    {if $logged_in and $with_product_block==1}

    $.getJSON("ar_web_customer_products.php?tipo=category_products&webpage_key={$webpage->id}", function (data) {

        console.log(data)


        $.each(data.ordered_products, function (index, value) {
            $('.order_qty_' + index).val(value)
        });


        $.each(data.favourite, function (index, value) {
            $('.favourite_' + index).removeClass('far').addClass('marked fas').data('favourite_key', value)
        });


    });

    {/if}



</script>
{if $with_basket==1}
    {include file="theme_1/basket_bottom_script.mobile.tpl"}

{/if}
</body>
</html>
