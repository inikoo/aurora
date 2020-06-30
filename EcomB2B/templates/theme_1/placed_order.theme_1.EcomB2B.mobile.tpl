{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 2 August 2017 at 20:42:16 CEST, Tranava, Slovakia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}

<style>
    table.totals td{
        padding: 3px 5px ;text-align: right;

    }
</style>

<div class="container" style="margin-bottom: 0px">


    <h2>{if isset($labels._order_number_label) and $labels._order_number_label!=''}{$labels._order_number_label}{else}{t}Order number{/t}{/if} <span class="order_number">{$placed_order->get('Public ID')}</span></h2>


    <div class="one_third ">
        <h5>
            <i class="fal fa-fw fa-clipboard" aria-hidden="true"></i>
            <span id="_delivery_address_label" class="website_localized_label"
                  >{if !empty($labels._delivery_address_label) }{$labels._delivery_address_label}{else}{t}Delivery address{/t}{/if}</span>
        </h5>
        <p>
            {$placed_order->get('Order Delivery Address Formatted')}
        </p>
    </div>

    <div class="one_third">
        <h5>
            <i class="fa fa-fw fa-dollar-sign" aria-hidden="true"></i>
            <span id="_invoice_address_label" class="website_localized_label"
                  >{if !empty($labels._invoice_address_label) }{$labels._invoice_address_label}{else}{t}Invoice address{/t}{/if}</span>



        </h5>
        <p>
            {$placed_order->get('Order Invoice Address Formatted')}
        </p>
    </div>


    <table class="order_totals">




        <tbody>
        <tr class="order_items_gross_container  {if $placed_order->get('Order Items Discount Amount')==0 }hide{/if}">
            <td>{if isset($labels._items_gross) and $labels._items_gross!=''}{$labels._items_gross}{else}{t}Items Gross{/t}{/if}</td>

            <td class=" text-right order_items_gross ">{$placed_order->get('Items Gross Amount')}</td>
        </tr>
        <tr class="order_items_discount_container last_items {if $placed_order->get('Order Items Discount Amount')==0 }hide{/if}">
            <td>{if isset($labels._items_discounts) and $labels._items_discounts!=''}{$labels._items_discounts}{else}{t}Items Discounts{/t}{/if}</td>

            <td class="text-right order_items_discount">{$placed_order->get('Basket Items Discount Amount')}</td>
        </tr>
        <tr>
            <td>{if isset($labels._items_net) and $labels._items_net!=''}{$labels._items_net}{else}{t}Items Net{/t}{/if}</td>

            <td class="text-right order_items_net">{$placed_order->get('Items Net Amount')}</td>
        </tr>


        <tr class="order_charges_container {if $placed_order->get('Order Charges Net Amount')==0 }very_discreet{/if}">
            <td>

                {if !empty($labels._items_charges)}{$labels._items_charges}{else}{t}Charges{/t}{/if}
            </td>

            <td class="text-right order_charges">{$placed_order->get('Charges Net Amount')}</td>
        </tr>


        <tr class="last_items">
            <td>{if isset($labels._items_shipping) and $labels._items_shipping!=''}{$labels._items_shipping}{else}{t}Shipping{/t}{/if}</td>
            <td class="text-right order_shipping">{if $placed_order->get('Shipping Net Amount')=='TBC'}<i class="fa error fa-exclamation-circle" title="" aria-hidden="true"></i> <small>{if !empty($labels._we_will_contact_you)}{$labels._we_will_contact_you}{else}{t}We will contact you{/t}{/if}</small>{else}{$placed_order->get('Shipping Net Amount')}{/if}</td>

        </tr>
        <tr class="net">
            <td>{if isset($labels._total_net) and $labels._total_net!=''}{$labels._total_net}{else}{t}Total Net{/t}{/if}</td>

            <td class="text-right order_net">{$placed_order->get('Total Net Amount')}</td>
        </tr>
        <tr class="tax ">
            <td>{if isset($labels._total_tax) and $labels._total_tax!=''}{$labels._total_tax}{else}{t}Tax{/t}{/if}</td>

            <td class="text-right order_tax">{$placed_order->get('Total Tax Amount')}</td>
        </tr>
        <tr class="total">
            <td>{if isset($labels._total) and $labels._total!=''}{$labels._total}{else}{t}Total{/t}{/if}</td>

            <td class="text-right order_total">{$placed_order->get('Total')}</td>
        </tr>

        <tr class="payments_amount_tr {if $placed_order->get('Order Payments Amount')==0}hide{/if}" >
            <td>{if isset($labels._order_paid_amount) and $labels._order_paid_amount!=''}{$labels._order_paid_amount}{else}{t}Paid{/t}{/if}</td>

            <td class="text-right payments_amount">{$placed_order->get('Basket Payments Amount')}</td>
        </tr>
        <tr class="available_credit_amount_tr tax  {if $placed_order->get('Order Available Credit Amount')==0}hide{/if}" >
            <td>{if isset($labels._order_available_credit_amount) and $labels._order_available_credit_amount!=''}{$labels._order_available_credit_amount}{else}{t}Credit{/t}{/if}</td>

            <td class="text-right available_credit_amount ">{$placed_order->get('Available Credit Amount')}</td>
        </tr>
        <tr class="to_pay_amount_tr total {if $placed_order->get('Order Payments Amount')==0 and $placed_order->get('Order Available Credit Amount')==0 }hide{/if} " >
            <td>{if isset($labels._order_to_pay_amount) and $_order_to_pay_amoount._total!=''}{$labels._order_to_pay_amoount}{else}{t}To pay{/t}{/if}</td>

            <td class="text-right to_pay_amount">{$placed_order->get('Basket To Pay Amount')}</td>
        </tr>

        </tbody>
    </table>





</div>


<div class="clearfix "></div>


<div class="container order">

    {include file="theme_1/_order_items.theme_1.EcomB2B.mobile.tpl" edit=false hide_title=true order=$placed_order items_data=$placed_order->get_items() }


</div>

<div class="clearfix marb6"></div>


        