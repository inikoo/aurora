{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 21 July 2017 at 14:33:40 CEST, Tranava, Slovakia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}


<h2 class="{if $hide_title}hide{/if}"  >{t}Order number{/t} <span class="order_number">342342</span></h2>

<table class="table">
    <thead>
    <tr>
        <th class="text-left">{t}Code{/t}</th>
        <th class="text-left">{t}Description{/t}</th>
        <th class="text-right">{t}Quantity{/t}</th>
        <th class="text-right">{t}Amount net{/t}</th>
    </tr>
    </thead>
    <tbody>

    {foreach from=$order->get_items() item="item" }

    <tr>
        <td>{$item.code}</td>
        <td>{$item.description}</td>
        <td class="text-right">{$item.edit_qty}</td>
        <td class="text-right">{$item.amount}</td>
    </tr>


    {/foreach}
    </tbody>
</table>

<script>




</script>
