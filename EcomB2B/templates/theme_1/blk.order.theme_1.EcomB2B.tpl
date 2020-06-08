{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 29 August 2018 at 00:11:11 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3
-->
*}

<style>


    @media only screen  and (max-width: 1240px) {

        #basket_continue_shopping {
            display: none;
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
<div >



    <div class="container  text_blocks  text_template_21">

        <div class="text_block ">
            <span style="float:right;margin-right: 40px ">{$order->get('Date')}</span>

            <h1 style="margin-bottom: 10px">{$order->get('Order Public ID')} <span style="margin-left: 10px;font-weight: normal;font-size: smaller">{$order->get('State')}</span></h1>
            <div style="margin-bottom: 10px;border-bottom: 1px solid #eee;width: 90%;">
                {if $order->get('Order Invoice Key')}
                <span >{t}Invoice{/t} <a  target="_blank" href="ar_web_invoice.pdf.php?id={$order->get('Order Invoice Key')}"><img style="margin-left: 10px" src="art/pdf.gif"></a></span>
                {/if}
            </div>
            <div class=" text_blocks  text_template_2">
        <div class="text_block ">
        <span class="strong">

                        <span id="delivery_label" class="{if $order->get('Order For Collection')=='Yes'}hide{/if}">
                        <span id="_delivery_address_label">{if isset($labels._delivery_address_label) and $labels._delivery_address_label!=''}{$labels._delivery_address_label}{else}{t}Delivery Address:{/t}{/if}</span>
                        </span>
            <span id="collection_label" class="{if $order->get('Order For Collection')=='No'}hide{/if} "">
            <span>{if isset($labels._for_collecion_label) and $labels._for_collecion_label!=''}{$labels._for_collecion_label}{else}{t}To be collected at:{/t}{/if}</span>

            </span>


            </span>
            <p>
            <div class="formatted_delivery_address">{$order->get('Order Delivery Address Formatted')}</div>
            </p>
        </div>

        <div class="text_block">
        <span class="strong">
            <span id="_invoice_address_label">{if isset($labels._invoice_address_label) and $labels._invoice_address_label!=''}{$labels._invoice_address_label}{else}{t}Invoice Address{/t}{/if}</span>


        </span>
            <p>
            <div class="formatted_invoice_address">{$order->get('Order Invoice Address Formatted')}</div>
            </p>
        </div>
            </div>
        </div>

        <div class="totals text_block">


            <table>


                <tbody>
                <tr>
                    <td>{if isset($labels._items_gross) and $labels._items_gross!=''}{$labels._items_gross}{else}{t}Items Gross{/t}{/if}</td>

                    <td class="text-right order_items_gross ">{$order->get('Items Gross Amount')}</td>
                </tr>
                <tr class="order_items_discount_container {if $order->get('Order Items Discount Amount')==0 }hide{/if}">
                    <td >{if isset($labels._items_discounts) and $labels._items_discounts!=''}{$labels._items_discounts}{else}{t}Items Discounts{/t}{/if}</td>

                    <td class="text-right order_items_discount">{$order->get('Items Discount Amount')}</td>
                </tr>
                <tr>
                    <td>{if isset($labels._items_net) and $labels._items_net!=''}{$labels._items_net}{else}{t}Items Net{/t}{/if}</td>

                    <td class="text-right order_items_net">{$order->get('Items Net Amount')}</td>
                </tr>
                <tr class="order_charges_container {if $order->get('Order Charges Net Amount')==0 }hide{/if}">
                    <td><i class="button fa fa-info-circle padding_right_5 info" style="color: #007fff;"
                           onclick="show_charges_info()"></i> {if !empty($labels._items_charges)}{$labels._items_charges}{else}{t}Charges{/t}{/if}
                    </td>

                    <td class="text-right order_charges">{$order->get('Charges Net Amount')}</td>
                </tr>
                <tr>
                    <td>{if isset($labels._items_shipping) and $labels._items_shipping!=''}{$labels._items_shipping}{else}{t}Shipping{/t}{/if}</td>

                    <td class="text-right order_shipping">{if $order->get('Shipping Net Amount')=='TBC'}
                            <i class="fa error fa-exclamation-circle" title="" aria-hidden="true"></i>
                            <small>{if !empty($labels._we_will_contact_you)}{$labels._we_will_contact_you}{else}{t}We will contact you{/t}{/if}</small>{else}{$order->get('Shipping Net Amount')}    {/if}  </td>
                </tr>
                <tr>
                    <td>{if isset($labels._total_net) and $labels._total_net!=''}{$labels._total_net}{else}{t}Total Net{/t}{/if}</td>

                    <td class="text-right order_net">{$order->get('Total Net Amount')}</td>
                </tr>
                <tr>
                    <td>{if isset($labels._total_tax) and $labels._total_tax!=''}{$labels._total_tax}{else}{t}Tax{/t}{/if}</td>

                    <td class="text-right order_tax">{$order->get('Total Tax Amount')}</td>
                </tr>
                <tr class="total">
                    <td>{if isset($labels._total) and $labels._total!=''}{$labels._total}{else}{t}Total{/t}{/if}</td>

                    <td class="text-right order_total">{$order->get('Total')}</td>
                </tr>
                <tr class="payments_amount_tr {if $order->get('Order Payments Amount')==0}hide{/if}">
                    <td>{if isset($labels._order_paid_amount) and $labels._order_paid_amount!=''}{$labels._order_paid_amount}{else}{t}Paid{/t}{/if}</td>

                    <td class="text-right payments_amount">{$order->get('Payments Amount')}</td>
                </tr>
                <tr class="available_credit_amount_tr {if $order->get('Order Available Credit Amount')==0}hide{/if}">
                    <td>{if isset($labels._order_available_credit_amount) and $labels._order_available_credit_amount!=''}{$labels._order_available_credit_amount}{else}{t}Credit{/t}{/if}</td>

                    <td class="text-right available_credit_amount ">{$order->get('Available Credit Amount')}</td>
                </tr>
                <tr class="to_pay_amount_tr {if $order->get('Order Payments Amount')==0 and $order->get('Order Available Credit Amount')==0 }hide{/if}">
                    <td>{if isset($labels._order_to_pay_amount) and $_order_to_pay_amoount._total!=''}{$labels._order_to_pay_amoount}{else}{t}To pay{/t}{/if}</td>

                    <td class="text-right to_pay_amount">{$order->get('Basket To Pay Amount')}</td>
                </tr>

                </tbody>
            </table>

        </div>

    </div>
    <div class="container order">
        {include file="theme_1/_order_items.theme_1.EcomB2B.tpl" edit=false hide_title=true  items_data=$order->get_items() }
    </div>
</div>



