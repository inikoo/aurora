{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 31 August 2017 at 00:18:17 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}


<div class="content-center">
    <div class="page-404">
        <h2 class="animate-zoom animate-time-1000">{if isset($content._empty_basket) and $labels._empty_basket!=''}{$labels._empty_basket}{else}{t}Empty basket{/t}{/if}</h2>
        <p class="animate-fade">
            {if isset($content._no_products_ordered_yet) and $labels._no_products_ordered_yet!=''}{$labels._no_products_ordered_yet}{else}{t}No products has been ordered{/t}{/if}
        </p>


        <a href="index.php" class="color-gray-dark border-gray-dark animate-fade"><i class="fa fa-home"></i></a>
    </div>
</div>

<div class="coverpage-clear"></div>



