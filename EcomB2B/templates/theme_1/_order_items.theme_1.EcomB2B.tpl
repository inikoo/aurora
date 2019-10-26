{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 21 July 2017 at 14:33:40 CEST, Tranava, Slovakia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}


<h2 class="{if $hide_title}hide{/if}"  >{t}Order number{/t} <span class="order_number">{$order->get('Order Public ID')}</span></h2>

<table class="table" style="width: 100%;margin-bottom: 0px">
    <thead>
    <tr>
        <th class="text-left">{t}Code{/t}</th>
        <th class="text-left">{t}Description{/t}</th>
        <th class="text-right">{t}Quantity{/t}</th>
        <th class="text-right">{t}Amount net{/t}</th>
    </tr>
    </thead>
    <tbody>


    {foreach from=$items_data item="item" }

    <tr>
        <td class="item_code">{$item.code}</td>
        <td class="item_description">{$item.description}</td>
        {if $edit }
        <td class="text-right">{if $item.price_raw>0}{$item.edit_qty}{/if}</td>
        {else}
            <td class="text-right">{$item.qty}</td>

        {/if}
        <td class="text-right">{$item.amount}</td>
    </tr>
    {/foreach}


    {if $edit}

    {foreach from=$interactive_deal_component_data item="item" }

        <tr>
            <td></td>
            <td colspan=2 class="text-right">{$item.description}</td>
            <td class="text-right"></td>
        </tr>
    {/foreach}
    {foreach from=$interactive_charges_data item="item" }

        <tr>
            <td></td>
            <td  class="text-right">{$item.description}</td>
            <td  class="text-right">{$item.quantity_edit}</td>
            <td class="text-right">{$item.net}</td>
        </tr>
    {/foreach}
    {/if}

    </tbody>
</table>

