{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:30 July 2017 at 18:07:22 CEST, Tranava, Slovakia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}{include file="theme_1/_head.theme_1.EcomB2B.tpl"}

<body xmlns="http://www.w3.org/1999/html">
{include file="analytics.tpl"}

<div class="wrapper_boxed">

    <div class="site_wrapper">

        {include file="theme_1/header.theme_1.EcomB2B.tpl"}

        <div class="content_fullwidth less2">


            <div class="container">
                <h3>
                    {if isset($content._no_products_ordered_yet) and $labels._no_products_ordered_yet!=''}{$labels._no_products_ordered_yet}{else}{t}No products has been ordered{/t}{/if}
                </h3>
            </div>


        </div>

        <div class="clearfix marb12"></div>

    </div>

    {include file="theme_1/footer.theme_1.EcomB2B.tpl"}
</div>

</div>
{include file="theme_1/bottom_scripts.theme_1.EcomB2B.tpl"}</body></html>


