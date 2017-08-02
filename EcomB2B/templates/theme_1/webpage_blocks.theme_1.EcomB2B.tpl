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


<div class="wrapper_boxed">

    <div class="site_wrapper">

        {include file="theme_1/header.EcomB2B.tpl"}

        <div class="content_fullwidth less">

            {foreach from=$content.blocks item=$block key=key}
                {if $block.show}

                    {if $block.type=='basket' and   !isset($order)  }


                        {include file="theme_1/blk.basket_no_order.theme_1.EcomB2B.tpl" data=$block key=$key  }

                    {else}
                        {include file="theme_1/blk.{$block.type}.theme_1.EcomB2B.tpl" data=$block key=$key  }

                    {/if}

                {/if}
            {/foreach}

        </div>


        <div class="clearfix marb12"></div>

        {include file="theme_1/footer.EcomB2B.tpl"}


    </div>

</div>

<script>



    $( document ).ready(function() {
        resize_banners();
    });

    $(window).resize(function() {
        resize_banners();

    });

    function resize_banners(){



        $('.iframe').each(function(i, obj) {
            $(this).css({ height: $(this).width()*$(this).attr('h')/$(this).attr('w') })
        });
    }

</script>


    </body>

</html>

