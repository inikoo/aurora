{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 29 August 2018 at 13:58:04 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3
-->
*}




<table class="order_items" border="1">
    <thead>
    <tr >
        <th colspan="2" class="text-left padding_left_10">{if !empty($labels._items_description) }{$labels._items_description}{else}{t}Description{/t}{/if}</th>

    </tr>
    </thead>
    <tbody>

    {foreach from=$items_data item="item" }

        <tr>
            <td style="text-align: left">{$item.code_description}</td>
            <td style="min-width: 10em;" >

                {if $item.state=='Out of Stock in Basket'}
                    0
                {else}
                {if $edit}
                    {if $item.price_raw>0}
                    <div class="mobile_ordering"  data-settings='{ "pid":{$item.pid},"basket":true }'>
                        <i onclick="save_item_qty_change(this)" class="ordering_button one_less fa fa-fw  fa-minus-circle color-red-dark"></i>
                        <input  type="number" min="0" value="{$item.qty_raw}" class="needsclick order_qty">
                        <i onclick="save_item_qty_change(this)" class="hide ordering_button save fa fa-save fa-fw color-blue-dark"></i>
                        <i onclick="save_item_qty_change(this)" class="ordering_button add_one fa fa-fw  fa-plus-circle color-green-dark"></i>
                    </div>
                        {/if}
                    {else}
                    {$item.qty}
                    {/if}
                {/if}
            </td>


            <td style="min-width: 5em;" class="text-right">{$item.amount}</td>
        </tr>


    {/foreach}


    {if $edit}
    {foreach from=$interactive_deal_component_data item="item" }

        <tr>

            <td colspan=2 class="text-right" style="line-height: 35px">{$item.description}</td>
            <td class="text-right"></td>
        </tr>
    {/foreach}
    {foreach from=$interactive_charges_data item="item" }

        <tr>

            <td  class="text-right">{$item.description}</td>
            <td  class="text-right">{$item.quantity_edit}</td>
            <td class="text-right">{$item.net}</td>
        </tr>
    {/foreach}
    {/if}

    </tbody>
</table>

