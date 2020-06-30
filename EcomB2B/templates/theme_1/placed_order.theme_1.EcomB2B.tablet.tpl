{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 13 March 2018 at 15:19:50 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3
-->
*}

<style>
    h5{
        margin-bottom: 5px
    }
</style>

<div class="container">


    <h2>{if isset($labels._order_number_label) and $labels._order_number_label!=''}{$labels._order_number_label}{else}{t}Order number{/t}{/if} <span class="order_number">{$placed_order->get('Public ID')}</span></h2>


    <div class="one-third-responsive ">
        <h5 >
            <i class="fal fa-fw fa-clipboard" aria-hidden="true"></i>
            <span id="_delivery_address_label" class="website_localized_label"
                  >{if !empty($labels._delivery_address_label) }{$labels._delivery_address_label}{else}{t}Delivery address{/t}{/if}</span>
        </h5>

            {$placed_order->get('Order Delivery Address Formatted')}

    </div>

    <div class="one-third-responsive">
        <h5>
            <i class="fa fa-fw fa-dollar-sign" aria-hidden="true"></i>
            <span id="_invoice_address_label" class="website_localized_label"
                  >{if !empty($labels._invoice_address_label) }{$labels._invoice_address_label}{else}{t}Invoice address{/t}{/if}</span>



        </h5>

            {$placed_order->get('Order Invoice Address Formatted')}

    </div>

    <div class="one-third-responsive text-right last-column  " style="padding-left:20px">


        <table class="order_totals" style="width: 100%">


            <tbody>
            <tr>
                <td>{if isset($labels._items_gross) and $labels._items_gross!=''}{$labels._items_gross}{else}{t}Items gross{/t}{/if}</td>

                <td class="text-right order_items_gross ">{$placed_order->get('Items Gross Amount')}</td>
            </tr>
            <tr class="order_items_discount_container {if $placed_order->get('Order Items Discount Amount')==0 }hide{/if}">
                <td>{if isset($labels._items_discounts) and $labels._items_discounts!=''}{$labels._items_discounts}{else}{t}Items discounts{/t}{/if}</td>

                <td class="text-right order_items_discount">{$placed_order->get('Items Discount Amount')}</td>
            </tr>
            <tr>
                <td>{if isset($labels._items_net) and $labels._items_net!=''}{$labels._items_net}{else}{t}Items net{/t}{/if}</td>

                <td class="text-right order_items_net">{$placed_order->get('Items Net Amount')}</td>
            </tr>
            <tr class="order_charges_container {if $placed_order->get('Order Charges Net Amount')==0 }hide{/if}">
                <td>{if isset($labels._items_charges) and $labels._items_charges!=''}{$labels._items_charges}{else}{t}Charges{/t}{/if}</td>

                <td class="text-right order_charges">{$placed_order->get('Charges Net Amount')}</td>
            </tr>
            <tr>
                <td>{if isset($labels._items_shipping) and $labels._items_shipping!=''}{$labels._items_shipping}{else}{t}Shipping{/t}{/if}</td>

                <td class="text-right order_shipping">{$placed_order->get('Shipping Net Amount')}</td>
            </tr>
            <tr>
                <td>{if isset($labels._total_net) and $labels._total_net!=''}{$labels._total_net}{else}{t}Total net{/t}{/if}</td>

                <td class="text-right order_net">{$placed_order->get('Total Net Amount')}</td>
            </tr>
            <tr>
                <td>{if isset($labels._total_tax) and $labels._total_tax!=''}{$labels._total_tax}{else}{t}Tax{/t}{/if}</td>

                <td class="text-right order_tax">{$placed_order->get('Total Tax Amount')}</td>
            </tr>
            <tr>
                <td>{if isset($labels._total) and $labels._total!=''}{$labels._total}{else}{t}Total{/t}{/if}</td>

                <td class="text-right order_total">{$placed_order->get('Total')}</td>
            </tr>

            </tbody>
        </table>

    </div>
<div class="order">
    {include file="theme_1/_order_items.theme_1.EcomB2B.tpl" edit=false hide_title=true   order=$placed_order items_data=$placed_order->get_items()  }
</div>

</div>


<div class="clear "></div>


<div class="container order">



</div>

<div class="clearfix marb6"></div>


        