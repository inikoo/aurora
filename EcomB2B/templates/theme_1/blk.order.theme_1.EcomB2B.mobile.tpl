{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 29 August 2018 at 13:48:24 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3
-->
*}

<style>


    @media only screen  and (max-width: 1240px) {

        #basket_continue_shopping {
            display: none
        }
    }

    .strong {
        font-weight: bold;
    }

    .deal_info{
        opacity: .8;font-size: smaller;color:seagreen;
    }

</style>
<div style="border-bottom:1px solid #ccc;width: 100%;margin-bottom: 10px;padding-bottom: 5px">
    <i class="hide fa fa-arrow-left" style="margin-right: 20px;margin-left: 20px"></i>
    <span class="button" onclick="go_back_orders()"><i class="fa fa-arrow-up" style="margin:0px 5px"></i>{t}Orders{/t}</span>
    <i class="hide fa fa-arrow-right" style="margin-left: 20px"></i>
</div>



<div class="container">

<div style="padding: 0px 20px">
    <h2>{if isset($labels._order_number_label) and $labels._order_number_label!=''}{$labels._order_number_label}{else}{t}Order number{/t}{/if} <span class="order_number">{$order->get('Public ID')}</span></h2>
    {$order->get('State')}<br>
    {$order->get('Date')}<br>

    <div class="" style="display: flex;    line-height: 1.5;">

    <div style="font-size: x-small;padding:0px 4px;flex-grow:1;margin-bottom: 20px">

            <span id="_delivery_address_label" class="strong website_localized_label">{if !empty($labels._delivery_address_label) }{$labels._delivery_address_label}{else}{t}Delivery address{/t}{/if}</span>


            {$order->get('Order Delivery Address Formatted')}

    </div>

        <div style="font-size: x-small;padding:0px 4px;flex-grow:1">

            <span id="_invoice_address_label" class="strong website_localized_label">{if !empty($labels._invoice_address_label) }{$labels._invoice_address_label}{else}{t}Invoice address{/t}{/if}</span>





            {$order->get('Order Invoice Address Formatted')}

    </div>
    </div>

</div>

        <table class="table">


            <tbody>
            <tr>
                <td>{if isset($labels._items_gross) and $labels._items_gross!=''}{$labels._items_gross}{else}{t}Items gross{/t}{/if}</td>

                <td class="text-right order_items_gross ">{$order->get('Items Gross Amount')}</td>
            </tr>
            <tr class="order_items_discount_container {if $order->get('Order Items Discount Amount')==0 }hide{/if}">
                <td>{if isset($labels._items_discounts) and $labels._items_discounts!=''}{$labels._items_discounts}{else}{t}Items discounts{/t}{/if}</td>

                <td class="text-right order_items_discount">{$order->get('Items Discount Amount')}</td>
            </tr>
            <tr>
                <td>{if isset($labels._items_net) and $labels._items_net!=''}{$labels._items_net}{else}{t}Items net{/t}{/if}</td>

                <td class="text-right order_items_net">{$order->get('Items Net Amount')}</td>
            </tr>
            <tr class="order_charges_container {if $order->get('Order Charges Net Amount')==0 }hide{/if}">
                <td>{if isset($labels._items_charges) and $labels._items_charges!=''}{$labels._items_charges}{else}{t}Charges{/t}{/if}</td>

                <td class="text-right order_charges">{$order->get('Charges Net Amount')}</td>
            </tr>
            <tr>
                <td>{if isset($labels._items_shipping) and $labels._items_shipping!=''}{$labels._items_shipping}{else}{t}Shipping{/t}{/if}</td>

                <td class="text-right order_shipping">{$order->get('Shipping Net Amount')}</td>
            </tr>
            <tr>
                <td>{if isset($labels._total_net) and $labels._total_net!=''}{$labels._total_net}{else}{t}Total net{/t}{/if}</td>

                <td class="text-right order_net">{$order->get('Total Net Amount')}</td>
            </tr>
            <tr>
                <td>{if isset($labels._total_tax) and $labels._total_tax!=''}{$labels._total_tax}{else}{t}Tax{/t}{/if}</td>

                <td class="text-right order_tax">{$order->get('Total Tax Amount')}</td>
            </tr>
            <tr>
                <td>{if isset($labels._total) and $labels._total!=''}{$labels._total}{else}{t}Total{/t}{/if}</td>

                <td class="text-right order_total">{$order->get('Total')}</td>
            </tr>

            </tbody>
        </table>



    <table class="order_items" style="margin-bottom: 0px">
        <thead>
        <tr >
            <th colspan="2" class="text-left padding_left_10">{t}Items{/t}</th>

        </tr>
        </thead>
        <tbody>

        {foreach from=$items_data item="item" }g

            <tr>
                <td style="text-align: left">{$item.code_description}</td>
                <td class="text-right">{$item.qty}</td>


                <td class="text-right">{$item.amount}</td>
            </tr>


        {/foreach}



        </tbody>
    </table>




</div>


<div class="clearfix "></div>








<div class="clearfix marb6"></div>


       

