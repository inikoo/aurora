{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:30 July 2017 at 18:07:22 CEST, Tranava, Slovakia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}

<div class="container" style="margin:100px auto;text-align: center">
    <h3>
        {if isset($content._no_products_ordered_yet) and $labels._no_products_ordered_yet!=''}{$labels._no_products_ordered_yet}{else}{t}No products has been ordered{/t}{/if}
    </h3>
</div>



