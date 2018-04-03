{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 30 August 2017 at 23:51:36 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}{include file="theme_1/_head.theme_1.EcomB2B.mobile.tpl"}
<body>{include file="analytics.tpl"}
<div id="page-transitions">
    {include file="theme_1/header.theme_1.EcomB2B.mobile.tpl"}
    <div id="page-content" class="page-content">
        <div id="page-content-scroll" class="header-clear"><!--Enables this element to be scrolled -->

            {if $webpage->get('Webpage Code')=='welcome.sys'}
            <div class="heading-strip bg-1" style="padding: 10px 20px;margin-bottom: 10px">
                <h3>{t}Welcome{/t}</h3>
                <i class="ion-android-happy" style="top:-27.5px"></i>
                <div class="overlay dark-overlay"></div>
            </div>
            {/if}




            {foreach from=$content.blocks item=$block key=key}
            {if $block.show}

                {if $block.type=='basket' and   !isset($order)  }


                    {include file="theme_1/blk.basket_no_order.theme_1.EcomB2B.mobile.tpl" data=$block key=$key  }

                {else}
                    {include file="theme_1/blk.{$block.type}.theme_1.EcomB2B.mobile.tpl" data=$block key=$key  }

                {/if}

            {/if}
            {/foreach}





            {include file="theme_1/footer.theme_1.EcomB2B.mobile.tpl"}
        </div>
    </div>

    <a href="#" class="back-to-top-badge"><i class="ion-ios-arrow-up"></i></a>

   
</div>


</body>




{include file="theme_1/bottom_scripts.theme_1.EcomB2B.mobile.tpl"}</body>
</html>
