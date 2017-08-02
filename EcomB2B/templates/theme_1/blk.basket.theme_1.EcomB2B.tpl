{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 21 July 2017 at 14:16:53 CEST, Tranava, Slovakia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}




<div id="block_{$key}" block="{$data.type}" class="{$data.type} _block  " style="Width:100%;" >

    <div class="clearfix marb6"></div>


            <div class="container">


                <div class="one_third ">
                    <h5>
                        <i class="fa fa-fw fa-truck" aria-hidden="true"></i>
                        <span id="_delivery_address_label" class="website_localized_label" contenteditable="true">{if isset($labels._delivery_address_label) and $labels._delivery_address_label!=''}{$labels._delivery_address_label}{else}{t}Delivery Address{/t}{/if}</span>
                    </h5>
                    <p>
                        {$order->get('Order Delivery Address Formatted')}
                    </p>
                </div>

                <div class="one_third">
                    <h5>
                        <i class="fa fa-fw fa-usd" aria-hidden="true"></i>
                        <span id="_invoice_address_label" class="website_localized_label" contenteditable="true">{if isset($labels._invoice_address_label) and $labels._invoice_address_label!=''}{$labels._invoice_address_label}{else}{t}Invoice Address{/t}{/if}</span>
                    </h5>
                    <p>
                        {$order->get('Order Invoice Address Formatted')}
                    </p>
                </div>

                <div class="one_third text-right last" style="padding-left:20px">






                    <table class="table">




                        <tbody>
                        <tr>
                            <td>{if isset($labels._items_gross) and $labels._items_gross!=''}{$labels._items_gross}{else}{t}Items Gross{/t}{/if}</td>

                            <td class="text-right order_items_gross ">{$order->get('Items Gross Amount')}</td>
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
                        <tr>
                            <td>{if isset($labels._items_shipping) and $labels._items_shipping!=''}{$labels._items_shipping}{else}{t}Shipping{/t}{/if}</td>

                            <td class="text-right order_shipping">{$order->get('Shipping Net Amount')}</td>
                        </tr>
                        <tr>
                            <td>{if isset($labels._total_net) and $labels._total_net!=''}{$labels._total_net}{else}{t}Total Net{/t}{/if}</td>

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

                </div>

            </div>



                <div class="clearfix "></div>



                <div class="container order">

                    {include file="theme_1/_order_items.theme_1.tpl" edit=true hide_title=true }


                </div>

                <div class="clearfix marb6"></div>


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

                                    <textarea id="_special_instructions" rows="5" name="comment" placeholder="{$data._special_instructions}"></textarea>
                                </label>
                            </section>


                    </form>



                </div>

                <div class="one_half last">

                    <form action="" method="post" enctype="multipart/form-data"  class="sky-form" style="box-shadow: none">

                        <section class="col col-6">
                            <button onclick="window.location = 'checkout.sys'"  style="margin:0px" type="submit" class="button">{$data._go_checkout_label}</button>

                        </section>


                    </form>

                </div>




            </div>


        <div class="clearfix marb12"></div>

</div>

<script>
    $("form").submit(function(e) {

        e.preventDefault();
        e.returnValue = false;

        // do things
    });






</script>