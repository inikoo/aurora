{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 17 July 2017 at 10:04:33 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}{include file="theme_1/_head.theme_1.EcomB2B.tpl"}

<body xmlns="http://www.w3.org/1999/html">
{include file="analytics.tpl"}

{if $logged_in}
    <span id="ordering_settings" class="hide" data-labels='{
    "ordered":"<i class=\"fa fa-thumbs-up fa-flip-horizontal fa-fw \" aria-hidden=\"true\"></i> <span class=\"order_button_text\"> {if empty($labels._ordering_ordered)}{t}Ordered{/t}{else}{$labels._ordering_ordered}{/if}</span>",
    "order":"<i class=\"fa fa-hand-pointer fa-fw \" aria-hidden=\"true\"></i>  <span class=\"order_button_text\">{if empty($labels._ordering_order_now)}{t}Order now{/t}{else}{$labels._ordering_order_now}{/if}</span>",
    "update":"<i class=\"fa fa-hand-pointer fa-fw \" aria-hidden=\"true\"></i>  <span class=\"order_button_text\">{if empty($labels._ordering_updated)}{t}Updated{/t}{else}{$labels._ordering_updated}{/if}</span>"
    }'></span>
{/if}
<div class="wrapper_boxed">

    <div class="site_wrapper">

        {include file="theme_1/header.theme_1.EcomB2B.tpl"}

        <div class="content_fullwidth less">

            {assign "with_iframe" 0}
            {assign "with_product_block" false}
            {foreach from=$content.blocks item=$block key=key}
                {if $block.show}

                    {if $block.type=='basket' and   !isset($order)  }


                        {include file="theme_1/blk.basket_no_order.theme_1.EcomB2B.tpl" data=$block key=$key  }

                    {else}

                        {if $block.type=='iframe'   }{assign "with_iframe" 1}{/if}
                        {if $block.type=='category_products' or   $block.type=='products' }{assign "with_product_block" 1}{/if}

                        {include file="theme_1/blk.{$block.type}.theme_1.EcomB2B.tpl" data=$block key=$key  }

                    {/if}

                {/if}
            {/foreach}

        </div>


        {include file="theme_1/footer.theme_1.EcomB2B.tpl"}


    </div>

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


        $('.iframe').each(function (i, obj) {
            $(this).css({
                height: $(this).width() * $(this).attr('h') / $(this).attr('w')})
        });
    }


    {/if}



    {if $logged_in and $with_product_block==1}

    $.getJSON("ar_web_customer_products.php?tipo=category_products&webpage_key={$webpage->id}", function (data) {

       // console.log(data)


        $('.order_row i').removeClass('hide')
        $('.order_row span').removeClass('hide')

        $.each(data.ordered_products, function (index, value) {
            $('.order_row_' + index).find('input').val(value).data('ovalue', value)
            $('.order_row_' + index).find('i').removeClass('fa-hand-pointer fa-flip-horizontal').addClass('fa-thumbs-up fa-flip-horizontal')
            $('.order_row_' + index).find('.label span').html('{if empty($labels._ordering_ordered)}{t}Ordered{/t}{else}{$labels._ordering_ordered}{/if}')
        });


        $.each(data.favourite, function (index, value) {
            $('.favourite_' + index).removeClass('far').addClass('marked fas').data('favourite_key', value)
        });


    });

    {/if}


</script>



</html>

