
{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 30 July 2017 at 18:14:27 CEST, Tranava, Slovakia
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
                        {$customer->get('Customer Delivery Address Formatted')}
                    </p>
                </div>

                <div class="one_third">
                    <h5>
                        <i class="fa fa-fw fa-usd" aria-hidden="true"></i>
                        <span id="_invoice_address_label" class="website_localized_label" contenteditable="true">{if isset($labels._invoice_address_label) and $labels._invoice_address_label!=''}{$labels._invoice_address_label}{else}{t}Invoice Address{/t}{/if}</span>
                    </h5>
                    <p>
                        {$customer->get('Customer Invoice Address Formatted')}
                    </p>
                </div>

                <div class="one_third text-right last" style="padding-left:20px">






                    <table class="table">




                        <tbody>


                        <tr>
                            <td>{if isset($labels._items_shipping) and $labels._items_shipping!=''}{$labels._items_shipping}{else}{t}Shipping{/t}{/if}</td>

                            <td class="text-right order_shipping">{$zero_amount}</td>
                        </tr>
                        <tr>
                            <td>{if isset($labels._total_net) and $labels._total_net!=''}{$labels._total_net}{else}{t}Total Net{/t}{/if}</td>

                            <td class="text-right order_net">{$zero_amount}</td>
                        </tr>
                        <tr>
                            <td>{if isset($labels._total_tax) and $labels._total_tax!=''}{$labels._total_tax}{else}{t}Tax{/t}{/if}</td>

                            <td class="text-right order_tax">{$zero_amount}</td>
                        </tr>
                        <tr>
                            <td>{if isset($labels._total) and $labels._total!=''}{$labels._total}{else}{t}Total{/t}{/if}</td>

                            <td class="text-right order_total">{$zero_amount}</td>
                        </tr>

                        </tbody>
                    </table>

                </div>

            </div>



                <div class="clearfix marb12"></div>


    <div class="container">
        <h3>
        {if isset($content._no_products_ordered_yet) and $labels._no_products_ordered_yet!=''}{$labels._no_products_ordered_yet}{else}{t}No products has been ordered{/t}{/if}
            </h3>
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