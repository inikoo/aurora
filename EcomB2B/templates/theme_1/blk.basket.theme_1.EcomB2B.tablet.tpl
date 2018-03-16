{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 12 March 2018 at 16:53:32 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3
-->
*}



<div id="block_{$key}"  class="{$data.type} _block  " style="Width:100%;" >

    <div class="heading-strip bg-1" style="padding: 10px 20px;margin-bottom: 10px">
        <h3>{t}Basket{/t}</h3>
        <i class="ion-android-cart" style="top:-27.5px"></i>
        <div class="overlay dark-overlay"></div>
    </div>


    <div class="content">



                <div class="one-third-responsive">
                    <a href="#order_delivery_address_form" class="modal-opener" style="color:#777">
                    <h5 style="position: relative;left:-10px;font-size: 90%;font-weight: 800;color: #333">

                        <span id="delivery_label" class="{if $order->get('Order For Collection')=='Yes'}hide{/if}">
                        <i id="_delivery_address_icon" class="fa fa-fw fa-truck   " aria-hidden="true"></i>
                        <span id="_delivery_address_label"  >{if isset($labels._delivery_address_label) and $labels._delivery_address_label!=''}{$labels._delivery_address_label}{else}{t}Delivery Address:{/t}{/if}</span>
                        </span>
                        <span  id="collection_label" class="{if $order->get('Order For Collection')=='No'}hide{/if} "">
                        <i id="_delivery_address_icon" class="fa fa-fw fa-hand-rock-o   aria-hidden="true"></i>
                        <span id="_delivery_address_label"  >{if isset($labels._for_collecion_label) and $labels._for_collecion_label!=''}{$labels._for_collecion_label}{else}{t}To be collected at:{/t}{/if}</span>

                        </span>


                    </h5>
                    <div class="formatted_delivery_address single_line_height">{$order->get('Order Delivery Address Formatted')}</div>
                    </a>


                </div>
        <div class="one-third-responsive">
                    <a href="#order_invoice_address_form" class="modal-opener " style="color:#777">
                    <h5 style="position: relative;left:-10px;;font-size: 90%;font-weight: 800;color: #333">
                        <i id="_invoice_address_icon" class="fa fa-fw fa-usd" aria-hidden="true"></i>
                        <span id="_invoice_address_label"  >{if isset($labels._invoice_address_label) and $labels._invoice_address_label!=''}{$labels._invoice_address_label}{else}{t}Invoice Address{/t}{/if}</span>



                    </h5>
                   <div class="formatted_invoice_address single_line_height">{$order->get('Order Invoice Address Formatted')}</div>
                    </a>
                </div>


        <div class="one-third-responsive last-column">
            <table class="order_totals">




                <tbody>
                <tr class="order_items_gross_container  {if $order->get('Order Items Discount Amount')==0 }hide{/if}">
                    <td>{if isset($labels._items_gross) and $labels._items_gross!=''}{$labels._items_gross}{else}{t}Items Gross{/t}{/if}</td>

                    <td class=" text-right order_items_gross ">{$order->get('Items Gross Amount')}</td>
                </tr>
                <tr class="order_items_discount_container {if $order->get('Order Items Discount Amount')==0 }hide{/if}">
                    <td>{if isset($labels._items_discounts) and $labels._items_discounts!=''}{$labels._items_discounts}{else}{t}Items Discounts{/t}{/if}</td>

                    <td class="text-right order_items_discount">{$order->get('Items Discount Amount')}</td>
                </tr>
                <tr>
                    <td>{if isset($labels._items_net) and $labels._items_net!=''}{$labels._items_net}{else}{t}Items Net{/t}{/if}</td>

                    <td class="text-right order_items_net">{$order->get('Items Net Amount')}</td>
                </tr>
                <tr class="order_charges_container {if $order->get('Order Charges Net Amount')==0 }hide{/if}">
                    <td>{if isset($labels._items_charges) and $labels._items_charges!=''}{$labels._items_charges}{else}{t}Charges{/t}{/if}</td>

                    <td class="text-right order_charges">{$order->get('Charges Net Amount')}</td>
                </tr>
                <tr class="last_items">
                    <td>{if isset($labels._items_shipping) and $labels._items_shipping!=''}{$labels._items_shipping}{else}{t}Shipping{/t}{/if}</td>
                    <td class="text-right order_shipping">{if $order->get('Shipping Net Amount')=='TBC'}<i class="fa error fa-exclamation-circle" title="" aria-hidden="true"></i> <small>{if !empty($labels._we_will_contact_you)}{$labels._we_will_contact_you}{else}{t}We will contact you{/t}{/if}</small>{else}{$order->get('Shipping Net Amount')}{/if}</td>

                </tr>
                <tr class="net">
                    <td>{if isset($labels._total_net) and $labels._total_net!=''}{$labels._total_net}{else}{t}Total Net{/t}{/if}</td>

                    <td class="text-right order_net">{$order->get('Total Net Amount')}</td>
                </tr>
                <tr class="tax">
                    <td>{if isset($labels._total_tax) and $labels._total_tax!=''}{$labels._total_tax}{else}{t}Tax{/t}{/if}</td>

                    <td class="text-right order_tax">{$order->get('Total Tax Amount')}</td>
                </tr>
                <tr class="total">
                    <td>{if isset($labels._total) and $labels._total!=''}{$labels._total}{else}{t}Total{/t}{/if}</td>

                    <td class="text-right order_total">{$order->get('Total')}</td>
                </tr>
                <tr class="payments_amount_tr {if $order->get('Order Payments Amount')==0}hide{/if}" >
                    <td>{if isset($labels._order_paid_amount) and $labels._order_paid_amount!=''}{$labels._order_paid_amount}{else}{t}Paid{/t}{/if}</td>

                    <td class="text-right payments_amount">{$order->get('Payments Amount')}</td>
                </tr>
                <tr class="available_credit_amount_tr tax {if $order->get('Order Available Credit Amount')==0}hide{/if}" >
                    <td>{if isset($labels._order_available_credit_amount) and $labels._order_available_credit_amount!=''}{$labels._order_available_credit_amount}{else}{t}Credit{/t}{/if}</td>

                    <td class="text-right available_credit_amount ">{$order->get('Available Credit Amount')}</td>
                </tr>
                <tr class="to_pay_amount_tr {if $order->get('Order Available Credit Amount')!=0}total{/if} {if $order->get('Order Payments Amount')==0 and $order->get('Order Available Credit Amount')==0 }hide{/if}" >
                    <td>{if isset($labels._order_to_pay_amount) and $_order_to_pay_amoount._total!=''}{$labels._order_to_pay_amoount}{else}{t}To pay{/t}{/if}</td>

                    <td class="text-right to_pay_amount">{$order->get('Basket To Pay Amount')}</td>
                </tr>

                </tbody>
            </table>
        </div>


                <div class="clear"></div>







                <table class="order_items">
                    <thead>
                    <tr >
                        <th colspan="2" class="text-left padding_left_10">{t}Items{/t}</th>

                    </tr>
                    </thead>
                    <tbody>

                    {foreach from=$order->get_items() item="item" }

                        <tr>
                            <td style="text-align: left">{$item.code_description}







                            </td>
<td>

    <div class="mobile_ordering"  data-settings='{ "pid":{$item.pid},"basket":true }'>
        <i onclick="save_item_qty_change(this)" class="ordering_button one_less fa fa-fw  fa-minus-circle color-red-dark"></i>
        <input  type="number" min="0" value="{$item.qty_raw}" class="needsclick order_qty">
        <i onclick="save_item_qty_change(this)" style="display:none" class="ordering_button save fa fa-fw fa-floppy-o color-blue-dark"></i>
        <i onclick="save_item_qty_change(this)" class="ordering_button add_one fa fa-fw  fa-plus-circle color-green-dark"></i>
    </div>
</td>


                            <td class="text-right">{$item.amount}</td>
                        </tr>


                    {/foreach}
                    </tbody>
                </table>




            </div>



         <div class="container">

                <div class="one_half">

                    <form action="" method="post" enctype="multipart/form-data"  class="sky-form"
                    style="box-shadow: none"




                    <section >

                        <div class="row"   style="display:none"  >
                            <section class="col col-6">
                                <label class="input">
                                    <i class="icon-append fa fa-tag"></i>
                                    <input type="text" name="name" id="name" placeholder="{$data._voucher}">
                                </label>
                            </section>
                            <section class="col col-6">
                                <button style="margin:0px" type="submit" class="button">{$data._voucher_label}</button>

                            </section>
                        </div>




                    </section>


                    <section style="border: none">
                                <label class="textarea">

                                    <textarea id="special_instructions" rows="5" name="comment" placeholder="{$data._special_instructions}">{$order->get('Order Customer Message')}</textarea>
                                </label>
                            </section>


                    </form>



                </div>

                <div class="one_half last">

                    <form action="" method="post" enctype="multipart/form-data"  class="sky-form" style="box-shadow: none">

                        <section class="col col-6">
                            <button onclick="$(this).find('i').addClass('fa-spinner fa-spin'); window.location = 'checkout.sys'"  style="margin:0px" type="submit" class="button">{$data._go_checkout_label} <i  class=" fa fa-fw fa-arrow-right" aria-hidden="true"></i> </button>

                        </section>


                    </form>

                </div>




            </div>


        <div class="clearfix marb12"></div>

</div>

