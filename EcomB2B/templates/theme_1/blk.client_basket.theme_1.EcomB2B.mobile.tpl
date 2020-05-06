{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:   23 February 2020  01:40::18  +0800, Kuala Lumpur, Malaysia
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


<div id="block_{$key}" data-block_key="{$key}"  block="{$data.type}" data-client_key="{$customer_client->id}" data-order_key="{$order->id}"  class="{$data.type}   {if !$data.show}hide{/if}"  style="min-height:500px;padding-top:10px;padding-bottom:{$bottom_margin}px"  >
    <div class="content" style="padding: 0px 10px">
        <div class="one-half">
        <h5  >
            <span>{$order->get('Public ID')}</span>
        </h5>
        <h4  >


                   <span style="background-color: black;color:white;padding:2px 12px 2px 4px" class="Customer_Client_Code"> <i class="fal fa-user"></i> {$customer_client->get('Customer Client Code')}</span>


        </h4>
        </div>
        <div class="one-half last-column">


        <div style="margin-top: 10px" class="formatted_delivery_address single_line_height">{$order->get('Order Delivery Address Formatted')}</div>
        </div>
        <div class="clear" style="margin-bottom: 10px"></div>


        <table class="order_totals" style="border-top: none">


            <tr style="border-bottom:1px solid #ccc;">
                <td style="border-bottom:1px solid #ccc;">{if !empty($labels._weight) }{$labels._weight}{else}{t}Weight{/t}{/if}</td>

                <td style="border-bottom:1px solid #ccc;" class="text-right order_estimated_weight ">{$order->get('Estimated Weight')}</td>
            </tr>

            <tbody>
            <tr class="order_items_gross_container  {if $order->get('Order Items Discount Amount')==0 }hide{/if}">
                <td>{if !empty($labels._items_gross) }{$labels._items_gross}{else}{t}Items Gross{/t}{/if}</td>

                <td class=" text-right order_items_gross ">{$order->get('Items Gross Amount')}</td>
            </tr>
            <tr class="order_items_discount_container last_items {if $order->get('Order Items Discount Amount')==0 }hide{/if}">
                <td>{if !empty($labels._items_discounts) }{$labels._items_discounts}{else}{t}Items Discounts{/t}{/if}</td>

                <td class="text-right order_items_discount">{$order->get('Basket Items Discount Amount')}</td>
            </tr>
            <tr>
                <td>{if !empty($labels._items_net) }{$labels._items_net}{else}{t}Items Net{/t}{/if}</td>

                <td class="text-right order_items_net">{$order->get('Items Net Amount')}</td>
            </tr>
            <tr class="Deal_Amount_Off_tr  {if $order->get('Order Deal Amount Off')==0}hide{/if}">
                <td>{if !empty($labels._amount_off)}{$labels._amount_off}{else}{t}Amount off{/t}{/if}</td>
                <td class="text-right Deal_Amount_Off">{$order->get('Deal Amount Off')}</td>
            </tr>

            <tr class="order_charges_container {if $order->get('Order Charges Net Amount')==0 }hide{/if}">
                <td>

                    {if !empty($labels._items_charges)}{$labels._items_charges}{else}{t}Charges{/t}{/if}  <i class=" order_charges_info fa fa-info-circle padding_left_5   info {if $order->get('Order Charges Net Amount')==0 }hide{/if}"    style="color: #007fff;" onclick="show_client_charges_info({$order->id})" ></i>
                </td>

                <td class="text-right order_charges">{$order->get('Charges Net Amount')}</td>
            </tr>


            <tr class="last_items">
                <td>{if !empty($labels._items_shipping) }{$labels._items_shipping}{else}{t}Shipping{/t}{/if}</td>
                <td class="text-right order_shipping">{if $order->get('Shipping Net Amount')=='TBC'}<i class="fa error fa-exclamation-circle" title="" aria-hidden="true"></i> <small>{if !empty($labels._we_will_contact_you)}{$labels._we_will_contact_you}{else}{t}We will contact you{/t}{/if}</small>{else}{$order->get('Shipping Net Amount')}{/if}</td>

            </tr>
            <tr class="net">
                <td>{if !empty($labels._total_net) }{$labels._total_net}{else}{t}Total Net{/t}{/if}</td>

                <td class="text-right order_net">{$order->get('Total Net Amount')}</td>
            </tr>
            <tr class="tax ">
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
            <tr class="available_credit_amount_tr tax  {if $order->get('Order Available Credit Amount')==0}hide{/if}" >
                <td>{if !empty($labels._order_available_credit_amount) }{$labels._order_available_credit_amount}{else}{t}Credit{/t}{/if}</td>

                <td class="text-right available_credit_amount ">{$order->get('Available Credit Amount')}</td>
            </tr>
            <tr class="to_pay_amount_tr total {if $order->get('Order Payments Amount')==0 and $order->get('Order Available Credit Amount')==0 }hide{/if} " >
                <td>{if !empty($labels._order_to_pay_amount) }{$labels._order_to_pay_amoount}{else}{t}To pay{/t}{/if}</td>

                <td class="text-right to_pay_amount">{$order->get('Basket To Pay Amount')}</td>
            </tr>

            </tbody>
        </table>
    </div>

    <div class="clear"></div>
    <div class="container order basket   " style="margin-bottom: 20px">
            <span class="basket_order_items"  data-scope="client" data-scope_key="{$customer_client->id}">
            {include file="theme_1/_order_items.theme_1.EcomB2B.mobile.tpl" edit=true hide_title=true   items_data=$items_data  client_key=$customer_client->id  }
            </span>



    </div>




    <div class="container" >

        <div class="one_half">

            <form action="" method="post" enctype="multipart/form-data"  class="sky-form" style="box-shadow: none">
                <section style="border: none">
                <label class="textarea">

                    <textarea id="special_instructions"  data-order_key="{$order->id}" rows="5" style="height: 60px" name="comment" placeholder="{$data._special_instructions}">{$order->get('Order Customer Message')}</textarea>
                </label>
            </section>
            </form>



        </div>

        <div class="one_half last">

            <form action="" method="post" enctype="multipart/form-data"  class="sky-form " style="box-shadow: none">

                <section class="col col-6   ">
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