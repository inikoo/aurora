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

<table class="table basket" style="width: 100%;margin-bottom: 0px;border-top:0px">
    <thead>
    {if $order->get('Order State')=='InBasket'}
    <tr class="operations" >


        <td colspan=5 class="text-right">


            <div style="" class="add_item_form" >
                <span class="hide add_item_invalid_msg">{t}Invalid value{/t}</span>
                <span class="discreet">{if !empty($labels._add_product_to_basket)}{$labels._add_product_to_basket}{else}{t}Add product{/t}{/if}</span>
                <input  data-device="Desktop" style="margin-right:2px;margin-left: 5px"  class="item " value="" placeholder="{if !empty($labels._add_product_to_basket_code_placeholder)}{$labels._add_product_to_basket_code_placeholder}{else}{t}Product code{/t}{/if}"/>
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


    <tr class="basket_order_ths {if !$order->id or $order->get('Order Number Items')==0  }hide{/if}">
        <th style="width: 50px">
            {if !empty($item.image_website)}
                <img src="{$item.image_website}" style="max-width: 50px;max-height: 50px"/>
            {/if}
        </th>
        <th class="text-left">{if !empty($labels._items_code)}{$labels._items_code}{else}{t}Code{/t}{/if}</th>
        <th class="text-left">{if !empty($labels._items_description) }{$labels._items_description}{else}{t}Description{/t}{/if}</th>
        <th class="text-right">{if !empty($labels._items_quantity)}{$labels._items_quantity}{else}{t}Quantity{/t}{/if}</th>
        <th class="text-right">{if !empty($labels._items_amount_net)}{$labels._items_amount_net}{else}{t}Amount net{/t}{/if}</th>
    </tr>
    </thead>
    <tbody>


    {foreach from=$items_data item="item" }

    <tr class="order_item_otf_{$item.otf_key}" >
        <td style="width: 50px">
            {if !empty($item.image_website)}
                <img src="{$item.image_website}" style="max-width: 50px;max-height: 50px"/>
            {/if}
        </td>

        <td class="item_code">{$item.code}</td>
        <td class="item_description">{$item.description}</td>
        {if $edit }
        <td class="edit_qty text-right">{if $item.price_raw>0}{$item.edit_qty}{/if}</td>
        {else}
            <td class="qty text-right">{$item.qty}</td>

        {/if}
        <td class="amount text-right">{$item.amount}</td>
    </tr>
    {/foreach}


    {if $edit}

    {foreach from=$interactive_deal_component_data item="item" }

        <tr>
            <td></td>
            <td></td>
            <td colspan=2 class="text-right">{$item.description}</td>
            <td class="text-right"></td>
        </tr>
    {/foreach}
    {foreach from=$interactive_charges_data item="item" }

        <tr>
            <td></td>
            <td></td>
            <td  class="text-right">{$item.description}</td>
            <td  class="text-right">{$item.quantity_edit}</td>
            <td class="text-right">{$item.net}</td>
        </tr>
    {/foreach}
    {/if}

    </tbody>
</table>

