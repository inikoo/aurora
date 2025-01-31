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
    {if $order->get('Order State')=='InBasket'}
    <tr class="operations" >

        <td colspan=4 class="text-right">


            <div  class="add_item_form" >
                <span class="hide add_item_invalid_msg">{t}Invalid value{/t}</span>
                <span class="discreet">{if !empty($labels._add_product_to_basket)}{$labels._add_product_to_basket}{else}{t}Add product{/t}{/if}</span>
                <input  data-device="Tablet" style="margin-right:2px;margin-left: 5px"  class="item " value="" placeholder="{if !empty($labels._add_product_to_basket_code_placeholder)}{$labels._add_product_to_basket_code_placeholder}{else}{t}Product code{/t}{/if}"/>
                <input style="margin-right:2px;width: 150px" class="qty  " value="" placeholder="{if !empty($labels._add_product_to_basket_qty_placeholder)}{$labels._add_product_to_basket_qty_placeholder}{else}{t}Quantity{/t}{/if}"/>
                <div  class="search_results_container hide" >
                    <table class="add_item_results" >
                        <tr class="hide add_item_search_result_template"  data-item_key="" data-item_historic_key=""
                            data-formatted_value="" onClick="select_add_item_option(this)">
                            <td class="code" style="padding-left:5px;"></td>
                            <td class="label" style="padding-left:5px;"></td>
                        </tr>
                    </table>
                </div>
                <i data-item_key="" data-item_historic_key="" class="add_item_save save fa fa-cloud super_discreet" onClick="save_add_item(this)"></i>
            </div>


        </td>
    </tr>
    {/if}
    <tr >
        <th></th>
        <th colspan="3" class="text-left padding_left_10">{if !empty($labels._items_description) }{$labels._items_description}{else}{t}Description{/t}{/if}</th>

    </tr>
    </thead>
    <tbody>

    {foreach from=$items_data item="item" }

        <tr>
            <td style="width: 50px">
                {if !empty($item.image_key)}
                    <img src="rwi/100x100_{$item.image_key}.jpeg" style="max-width: 50px;max-height: 50px"/>
                {/if}
            </td>
            <td style="text-align: left">{$item.code_description}</td>
            <td style="min-width: 10em;" >

                {if $item.state=='Out of Stock in Basket'}
                    0
                {else}
                {if $edit}
                    {if $item.price_raw>0}
                    <div class="mobile_ordering"   data-settings='{ "pid":{$item.pid},"basket":true }'>
                        <i onclick="save_item_qty_change(this{if isset($client_key)},{ type:'client_order',client_key:{$client_key},order_key:{$order->id}}{/if})" class="ordering_button one_less fa fa-fw  fa-minus-circle color-red-dark"></i>
                        <input  type="number" min="0" value="{$item.qty_raw}" class="needsclick order_qty">
                        <i onclick="save_item_qty_change(this{if isset($client_key)},{ type:'client_order',client_key:{$client_key},order_key:{$order->id}}{/if})" class="hide ordering_button save fa fa-save fa-fw color-blue-dark"></i>
                        <i onclick="save_item_qty_change(this{if isset($client_key)},{ type:'client_order',client_key:{$client_key},order_key:{$order->id}}{/if})" class="ordering_button add_one fa fa-fw  fa-plus-circle color-green-dark"></i>
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

            <td colspan=3 class="text-right" style="line-height: 35px">{$item.description}</td>
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

