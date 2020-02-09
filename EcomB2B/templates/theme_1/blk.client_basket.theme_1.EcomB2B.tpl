{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  24 November 2019  14:15::38  +0100, Mijas Coasta, Spain
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}

<style>
    .basket{
        font-size: 16px;
    }

    @media only screen  and (max-width: 1240px) {
        #basket_continue_shopping {
            display: none
        }
    }
</style>


{if isset($data.top_margin)}{assign "top_margin" $data.top_margin}{else}{assign "top_margin" "30"}{/if}
{if isset($data.bottom_margin)}{assign "bottom_margin" $data.bottom_margin}{else}{assign "bottom_margin" "30"}{/if}
{assign "items_data" $order->get_items()}
{assign "interactive_charges_data" $order->get_interactive_charges_data()}
{assign "interactive_deal_component_data" $order->get_interactive_deal_component_data()}


<div id="block_{$key}" data-block_key="{$key}"  block="{$data.type}" data-client_key="{$customer_client->id}" data-order_key="{$order->id}"  class="{$data.type}   {if !$data.show}hide{/if}"  style="min-height:500px;padding-top:{$top_margin}px;padding-bottom:{$bottom_margin}px"  >


    <div class="order_header  text_blocks  text_template_21">


                <div class="text_block '">
                    <h2 class="order_number  {if !$order->id}hide{/if}">{if !empty($labels._order_number_label)}{$labels._order_number_label}{else}{t}Order number{/t}{/if} <span class="order_number">{$order->get('Public ID')}</span></h2>
                    <div class="text_blocks text_template_2">
                <div class="text_block ">
                    <h5>
                        <span id="delivery_label" class="{if $order->get('Order For Collection')=='Yes'}hide{/if}">
                        <i id="_delivery_address_icon" class="fa fa-fw fa-truck   " aria-hidden="true"></i>
                        <span id="_delivery_address_label"  >{if !empty($labels._delivery_address_label) }{$labels._delivery_address_label}{else}{t}Delivery Address:{/t}{/if}</span>
                        </span>


                    </h5>
                    <p ><div class="formatted_delivery_address">
                        {if !$order->id}
                            {$customer_client->get('Customer Client Contact Address Formatted')}
                        {else}
                            {$order->get('Order Delivery Address Formatted')}
                        {/if}
                    </div></p>
                </div>


                 </div>
                </div>
                <div class="totals text_block">
                    <table >
                        <tbody>
                        <tr class="order_items_gross_container {if $order->get('Order Items Discount Amount')==0 }hide{/if}">
                            <td>{if !empty($labels._items_gross) }{$labels._items_gross}{else}{t}Items Gross{/t}{/if}</td>

                            <td class="text-right order_items_gross ">{$order->get('Items Gross Amount')}</td>
                        </tr>
                        <tr class="order_items_discount_container before_total {if $order->get('Order Items Discount Amount')==0 }hide{/if}">
                            <td>{if !empty($labels._items_discounts) }{$labels._items_discounts}{else}{t}Items Discounts{/t}{/if}</td>

                            <td class="text-right order_items_discount">{$order->get('Basket Items Discount Amount')}</td>
                        </tr>
                        <tr>
                            <td>{if !empty($labels._items_net)}{$labels._items_net}{else}{t}Items Net{/t}{/if}</td>

                            <td class="text-right order_items_net">{$order->get('Items Net Amount')}</td>
                        </tr>


                        <tr class="Deal_Amount_Off_tr  {if $order->get('Order Deal Amount Off')==0}hide{/if}">
                            <td>{if !empty($labels._amount_off)}{$labels._amount_off}{else}{t}Amount off{/t}{/if}</td>
                            <td class="aright Deal_Amount_Off">{$order->get('Deal Amount Off')}</td>
                        </tr>


                        <tr class="order_charges_container {if $order->get('Order Charges Net Amount')==0 }very_discreet{/if}">
                            <td><i class="button order_charges_info fa fa-info-circle padding_right_5 info {if $order->get('Order Charges Net Amount')==0 }hide{/if}"    style="color: #007fff;" onclick="show_charges_info()" ></i>  {if !empty($labels._items_charges)}{$labels._items_charges}{else}{t}Charges{/t}{/if}</td>

                            <td class="text-right order_charges">{$order->get('Charges Net Amount')}</td>
                        </tr>
                        <tr class="before_total">
                            <td>{if !empty($labels._items_shipping) }{$labels._items_shipping}{else}{t}Shipping{/t}{/if}</td>

                            <td class="text-right order_shipping">{if $order->get('Shipping Net Amount')=='TBC'}<i class="fa error fa-exclamation-circle" title="" aria-hidden="true"></i> <small>{if !empty($labels._we_will_contact_you)}{$labels._we_will_contact_you}{else}{t}We will contact you{/t}{/if}</small>{else}{$order->get('Shipping Net Amount')}    {/if}  </td>
                        </tr>

                        <tr>
                            <td>{if !empty($labels._total_net) }{$labels._total_net}{else}{t}Total Net{/t}{/if}</td>

                            <td class="text-right order_net">{$order->get('Total Net Amount')}</td>
                        </tr>
                        <tr class="before_total">
                            <td>{if !empty($labels._total_tax) }{$labels._total_tax}{else}{t}Tax{/t}{/if}</td>

                            <td class="text-right order_tax">{$order->get('Total Tax Amount')}</td>
                        </tr>
                        <tr class="total">
                            <td>{if !empty($labels._total) }{$labels._total}{else}{t}Total{/t}{/if}</td>

                            <td class="text-right order_total">{$order->get('Total')}</td>
                        </tr>
                        <tr class="payments_amount_tr {if $order->get('Order Payments Amount')==0}hide{/if}" >
                            <td>{if !empty($labels._order_paid_amount) }{$labels._order_paid_amount}{else}{t}Paid{/t}{/if}</td>

                            <td class="text-right payments_amount">{$order->get('Basket Payments Amount')}</td>
                        </tr>
                        <tr class="available_credit_amount_tr {if $order->get('Order Available Credit Amount')==0}hide{/if}" >
                            <td>{if !empty($labels._order_available_credit_amount) }{$labels._order_available_credit_amount}{else}{t}Credit{/t}{/if}</td>

                            <td class="text-right available_credit_amount ">{$order->get('Available Credit Amount')}</td>
                        </tr>
                        <tr class="to_pay_amount_tr total {if $order->get('Order Payments Amount')==0 and $order->get('Order Available Credit Amount')==0 }hide{/if}" >
                            <td>{if !empty($labels._order_to_pay_amount) }{$labels._order_to_pay_amoount}{else}{t}To pay{/t}{/if}</td>

                            <td class="text-right to_pay_amount">{$order->get('Basket To Pay Amount')}</td>
                        </tr>

                        </tbody>
                    </table>




                </div>

            </div>

                <div class="container order basket   " style="margin-bottom: 30px">
                     <span class="basket_order_items" data-scope="client" data-scope_key="{$customer_client->id}"   >
                    {include file="theme_1/_order_items.theme_1.EcomB2B.tpl" edit=true hide_title=true  items_data=$items_data }
                     </span>

                </div>




         <div class="order_header container text_blocks  text_template_2  order_basket_footer   {if !$order->id   or $order->get('Order Number Items')==0  }hide{/if} ">
             <div class="text_block" >
                 <form action="" method="post" enctype="multipart/form-data"  class="sky-form"  style="box-shadow: none"
                    <section style="border: none">
                                <label class="textarea">

                                    <textarea id="special_instructions" rows="5" name="comment" placeholder="{$data._special_instructions}">{$order->get('Order Customer Message')}</textarea>
                                </label>
                            </section>
                 </form>



                </div>

                <div class="text_block ">

                    <form action="" method="post" enctype="multipart/form-data"  class="sky-form" style="box-shadow: none">

                        <section class="col col-11">
                            <button id="basket_go_to_checkout"   style="margin:0px;{if $order->get('Products')==0 }display:none{/if}" type="submit" class="button"><b>{$data._go_checkout_label}</b> <i  class=" fa fa-fw fa-arrow-right" aria-hidden="true"></i> </button>



                        </section>


                    </form>

                </div>




            </div>




</div>


<script>

    $('#basket_go_to_checkout').on('click',function() {
        $(this).find('i').addClass('fa-spinner fa-spin');
        window.location = 'checkout.sys?order_key='+$('.client_basket').data('order_key')
    });

    $("form").on('submit',function(e) {
        e.preventDefault();
        e.returnValue = false;
    });

    {foreach from=$items_data item="item" }
    ga('auTracker.ec:addProduct',{$item.analytics_data} );
    {/foreach}

    ga('auTracker.send', 'event', 'Order', 'basket');


    ga('auTracker.ec:setAction','checkout', {
        'step': 1,
    });
    ga('auTracker.send', 'pageview');


</script>