{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 29 August 2018 at 13:58:04 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3
-->
*}



<table class="order_items" style="margin-bottom: 0px">
    <thead>
    <tr >
        <th colspan="2" class="text-left padding_left_10">{t}Items{/t}</th>

    </tr>
    </thead>
    <tbody>

    {foreach from=$items_data item="item" }g

        <tr>
            <td style="text-align: left">{$item.code_description}

                {if $edit}
                {if $item.state!='Out of Stock in Basket'}
                    {if $item.price_raw>0}
                    <div class="mobile_ordering"  data-settings='{ "pid":{$item.pid},"basket":true }'>
                        <i onclick="save_item_qty_change(this)" class="ordering_button one_less fa fa-fw  fa-minus-circle color-red-dark"></i>
                        <input  type="number" min="0" value="{$item.qty_raw}" class="needsclick order_qty">
                        <i onclick="save_item_qty_change(this)" class="hide ordering_button save fa fa-save fa-fw color-blue-dark"></i>
                        <i onclick="save_item_qty_change(this)" class="ordering_button add_one fa fa-fw  fa-plus-circle color-green-dark"></i>
                    </div>
                    {/if}

                {/if}
                {/if}

            </td>


            <td class="text-right">{if !$edit}{$item.qty}<br>{/if}{$item.amount}</td>
        </tr>


    {/foreach}


    {if $edit}
    {foreach from=$interactive_deal_component_data item="item" }

        <tr>

            <td class="text-right" style="line-height: 35px">{$item.description}</td>
            <td class="text-right"></td>
        </tr>
    {/foreach}
    {foreach from=$interactive_charges_data item="item" }

        <tr>

            <td  class="text-right">{$item.description}<br>{$item.quantity_edit}</td>
            <td class="text-right">{$item.net}</td>
        </tr>
    {/foreach}
    {/if}
    </tbody>
</table>
