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
    {if $order->get('Order State')=='InBasket'}
    <tr class="operations">

        <td colspan=2>


            <div class="add_item_form">
                <div style="margin-bottom: 5px">
                    <span class="hide add_item_invalid_msg  error">{t}Invalid value{/t}</span>
                    <span class="discreet">{if !empty($labels._add_product_to_basket)}{$labels._add_product_to_basket}{else}{t}Add product{/t}{/if}</span>
                </div>
                <div style="display: flex">
                    <div >
                        <input data-device="Mobile" style="margin-right:2px;margin-left: 5px;width: width: 100%" class="item " value=""
                               placeholder="{if !empty($labels._add_product_to_basket_code_placeholder)}{$labels._add_product_to_basket_code_placeholder}{else}{t}Product code{/t}{/if}"/>
                    </div>
                    <div>
                        <input style="margin-right:2px;width: 100%" class="qty  " value=""
                               placeholder="{if !empty($labels._add_product_to_basket_qty_placeholder)}{$labels._add_product_to_basket_qty_placeholder}{else}{t}Quantity{/t}{/if}"/>
                    </div>
                    <div style="line-height: 30px;padding-left: 5px">
                        <i data-item_key="" data-item_historic_key="" class="add_item_save save fa fa-cloud super_discreet"
                           onClick="save_add_item(this)"
                        ></i>
                    </div>
                </div>

                <div class="search_results_container hide">
                    <table class="add_item_results">
                        <tr class="hide add_item_search_result_template" data-item_key="" data-item_historic_key=""
                            data-formatted_value="" onClick="select_add_item_option(this)">
                            <td class="code" style="padding-left:5px;"></td>
                            <td class="label" style="padding-left:5px;"></td>
                        </tr>
                    </table>
                </div>


            </div>


        </td>
    </tr>
    {/if}
    <tr >
        <th colspan="2" class="text-left padding_left_5">{if !empty($labels._items_description) }{$labels._items_description}{else}{t}Description{/t}{/if}</th>

    </tr>
    </thead>
    <tbody>

    {foreach from=$items_data item="item" }

        <tr>
            <td style="text-align: left">

                <div class="tw-flex">
    
                    {if !empty($item.image_key)}
                        <img class=""  src="rwi/100x100_{$item.image_key}.jpeg" style="max-width: 50px;max-height: 50px"/>
                    {/if}
    
    
                    <div>{$item.code_description}</div>
                </div>

                {if $edit}
                {if $item.state!='Out of Stock in Basket'}
                    {if $item.price_raw>0}
                    <div class="mobile_ordering"  data-settings='{ "pid":{$item.pid},"basket":true }'>
                        <i onclick="save_item_qty_change(this{if isset($client_key)},{ type:'client_order',client_key:{$client_key},order_key:{$order->id}}{/if})" class="ordering_button one_less fa fa-fw  fa-minus-circle color-red-dark"></i>
                        <input  type="number" min="0" value="{$item.qty_raw}" class="needsclick order_qty">
                        <i onclick="save_item_qty_change(this{if isset($client_key)},{ type:'client_order',client_key:{$client_key},order_key:{$order->id}}{/if})" class="hide ordering_button save fa fa-save fa-fw color-blue-dark"></i>
                        <i onclick="save_item_qty_change(this{if isset($client_key)},{ type:'client_order',client_key:{$client_key},order_key:{$order->id}}{/if})" class="ordering_button add_one fa fa-fw  fa-plus-circle color-green-dark"></i>
                    </div>
                    {/if}

                {/if}
                {/if}

            </td>


            <td class="text-right">{if !$edit}{$item.qty}<br>{/if}{$item.amount}
            </td>
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
