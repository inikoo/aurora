{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 27 July 2017 at 17:26:03 CEST, Tranava, Slovakia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}{include file="theme_1/_head.theme_1.EcomB2B.tpl"}

<body xmlns="http://www.w3.org/1999/html">
{include file="analytics.tpl"}

<div class="wrapper_boxed">

    <div class="site_wrapper">

        {include file="theme_1/header.EcomB2B.tpl"}

        <div class="content_fullwidth less2">
            <div class="container">
                <h2>{if isset($labels._order_number_label) and $labels._order_number_label!=''}{$labels._order_number_label}{else}{t}Order number{/t}{/if} <span class="order_number">{$order->get('Public ID')}</span></h2>


                <div class="one_third ">
                    <h5>
                        <i class="fa fa-fw fa-truck" aria-hidden="true"></i>
                        <span id="_delivery_address_label" class="website_localized_label"
                             >{if isset($labels._delivery_address_label) and $labels._delivery_address_label!=''}{$labels._delivery_address_label}{else}{t}Delivery Address{/t}{/if}</span>
                    </h5>
                    <p>
                        {$order->get('Order Delivery Address Formatted')}
                    </p>
                </div>

                <div class="one_third">
                    <h5 >
                        <i class="fa fa-fw fa-usd" aria-hidden="true" ></i>
                        <span id="_invoice_address_label" class="website_localized_label"
                             >{if isset($labels._invoice_address_label) and $labels._invoice_address_label!=''}{$labels._invoice_address_label}{else}{t}Invoice Address{/t}{/if}</span>
                    </h5>
                    <p>
                        {$order->get('Order Invoice Address Formatted')}
                    </p>
                </div>

                <div class="one_third text-right last" style="padding-left:20px">


                    <table class="table">


                        <tbody>

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
                        <tr class="payments_amount_tr {if $order->get('Order Payments Amount')==0}hide{/if}" >
                            <td>{if isset($labels._order_paid_amount) and $labels._order_paid_amount!=''}{$labels._order_paid_amount}{else}{t}Paid{/t}{/if}</td>

                            <td class="text-right payments_amount">{$order->get('Payments Amount')}</td>
                        </tr>
                        <tr class="available_credit_amount_tr {if $order->get('Order Available Credit Amount')==0}hide{/if}" >
                            <td>{if isset($labels._order_available_credit_amount) and $labels._order_available_credit_amount!=''}{$labels._order_available_credit_amount}{else}{t}Credit{/t}{/if}</td>

                            <td class="text-right available_credit_amount ">{$order->get('Available Credit Amount')}</td>
                        </tr>
                        <tr class="to_pay_amount_tr {if $order->get('Order Payments Amount')==0 and $order->get('Order Available Credit Amount')==0 }hide{/if}" >
                            <td>{if isset($labels._order_to_pay_amount) and $_order_to_pay_amount._total!=''}{$labels._order_to_pay_amount}{else}{t}To pay{/t}{/if}</td>

                            <td class="text-right to_pay_amount">{$order->get('Basket To Pay Amount')}</td>
                        </tr>

                        </tbody>
                    </table>

                </div>


            </div>


            <div class="clearfix marb6"></div>

            <div class="container">


                {assign "payment_accounts" $website->get_payment_accounts()  }

                <ul class="tabs3">

                    {foreach from=$payment_accounts item=payment_account key=key}


                        <li><a href="#payment_account_item_{$payment_account.object->get('Block')}" target="_self"><i class="fa {$payment_account.icon}" aria-hidden="true"></i> <span>
            {if $payment_account.tab_label==''}
                {$content[$payment_account.tab_label_index]}

            {else}
                {$payment_account.tab_label}
            {/if}
            </span></a></li>
                    {/foreach}

                </ul>

                <div class="tabs-content3 two">

                    {foreach from=$payment_accounts item=payment_account key=key}

                        {assign "block" $payment_account.object->get("Block")  }


                        <div id="payment_account_item_{$block}" class="tabs-panel3" >


                            {if $block=='BTree' }
                                <form id="BTree_credit_card_form" action="" class="sky-form" style="max-width: 500px;">
                                    <header>{$content._form_title_credit_card}</header>




                                    {assign "saved_cards" $customer->get_saved_credit_cards($order->get('Order Delivery Address Checksum'),$order->get('Order Invoice Address Checksum'))}

                                    {assign "number_saved_cards" count($saved_cards)  }


                                    <fieldset  class="credit_cards_list {if $number_saved_cards==0}hide{/if}" >




                                        {foreach from=$saved_cards item=saved_card }

                                            <div class="row">
                                                <section class="col col-1">
                                                <i onclick="delete_this_credit_card(this)" title="{t}Delete card{/t}" class="delete_this_credit_card fa fa-trash "></i>
                                                    <i onclick="cancel_use_this_card(this)" title="{t}Go back{/t}" class="hide like_button cancel_use_this_card fa fa-chevron-left "></i>

                                                </section>
                                                <section class="col col-6 like_button" onclick="use_this_credit_card(this)">
                                                    {$saved_card['Card Number']} <span style="margin-left:10px">({$saved_card['Card Expiration']})</span>
                                                </section>
                                                <section class="col col-1 like_button" onclick="use_this_credit_card(this)">
                                                    <i  class="fa fa-circle-o  check_icon_saved_card" title="{t}Use this card{/t}"></i>

                                                </section>
                                                <section class="col col-3 invisible cvv_for_saved_card " style="position: relative;top:-7px">
                                                    <label class="input">
                                                        <input type="text" maxlength="4" placeholder="{$content._credit_card_ccv}">
                                                    </label>
                                                </section>
                                            </div>


                                        {/foreach}

                                        <div class="row">
                                            <section class="col col-1">
                                            </section>
                                            <section class="col col-6 like_button"  onclick="use_other_credit_card()" >
                                                {if isset($content._pay_with_other_card) and $labels._pay_with_other_card!=''}{$labels._pay_with_other_card}{else}{t}Pay with other card{/t}{/if}
                                            </section>
                                            <section class="col col-1 like_button"  onclick="use_other_credit_card()" >
                                                <i  title="{if isset($content._pay_with_other_card) and $labels._pay_with_other_card!=''}{$labels._pay_with_other_card}{else}{t}Pay with other card{/t}{/if}" class="fa fa-circle-o "></i>

                                            </section>

                                        </div>


                                    </fieldset>

                                    <fieldset    class=" credit_card_form  {if $number_saved_cards>0}hide{/if}" >



                                        <div class="row">
                                            <section class="col col-9">
                                                <label class="input">
                                                    <input type="text" name="credit_card_number" id="credit_card_number" value="" placeholder="{$content._credit_card_number}">
                                                </label>
                                            </section>
                                            <section class="col col-3">
                                                <label class="input">
                                                    <input type="text" maxlength="4" name="credit_card_ccv" id="credit_card_ccv" value=""  placeholder="{$content._credit_card_ccv}">
                                                </label>
                                            </section>
                                        </div>

                                        <div class="row">
                                            <label class="label col col-4">{$content._credit_card_expiration_date}</label>
                                            <section class="col col-5">
                                                <label class="select">
                                                    <select name="credit_card_util_month" id="credit_card_util_month">
                                                        <option value="0" selected disabled>{$content._credit_card_expiration_date_month_label}</option>

                                                        <option value="1">{'2000-01-01'|date_format:"%B"}</option>
                                                        <option value="2">{'2000-02-01'|date_format:"%B"}</option>
                                                        <option value="3">{'2000-03-01'|date_format:"%B"}</option>
                                                        <option value="4">{'2000-04-01'|date_format:"%B"}</option>
                                                        <option value="5">{'2000-05-01'|date_format:"%B"}</option>
                                                        <option value="6">{'2000-06-01'|date_format:"%B"}</option>
                                                        <option value="7">{'2000-07-01'|date_format:"%B"}</option>
                                                        <option value="8">{'2000-08-01'|date_format:"%B"}</option>
                                                        <option value="9">{'2000-08-01'|date_format:"%B"}</option>
                                                        <option value="10">{'2000-10-01'|date_format:"%B"}</option>
                                                        <option value="11">{'2000-11-01'|date_format:"%B"}</option>
                                                        <option value="12">{'2000-12-01'|date_format:"%B"}</option>
                                                    </select>
                                                    <i></i>
                                                </label>
                                            </section>
                                            <section class="col col-3">
                                                <label class="input">
                                                    <input type="text" maxlength="4" name="credit_card_util_year" id="credit_card_util_year" value="" placeholder="{$content._credit_card_expiration_date_year_label}">
                                                </label>
                                            </section>
                                        </div>

                                        <div class="row hide">

                                            <section class="col col-5" >
                                                <label class="checkbox"><input type="checkbox"  id="save_card"><i></i> </label>
                                                <span style="margin-left:27px;	font: 14px/1.55 'Open Sans', Helvetica, Arial, sans-serif;position:relative;top:-22px;font-size:15px;color:#404040" id="_credit_card_save"
                                                     >{$content._credit_card_save}</span>

                                            </section>


                                        </div>

                                        <div class="row">




                                        </div>

                                    </fieldset>

                                    <footer>

                                        <section  class="col col-5 like_button show_saved_cards_list hide "  onclick="show_saved_cards()" >

                                            {if isset($content._show_saved_cards) and $labels._show_saved_cards!=''}{$labels._show_saved_cards}{else}{t}Show saved cards list{/t}{/if}
                                        </section>
                                        <button id="place_order_braintree" type="submit" class="button  {if $number_saved_cards>0} state-disabled{/if}  ">{$content._place_order_from_credit_card}  <i class="margin_left_10 fa fa-fw fa-arrow-right" aria-hidden="true"></i>  </button>
                                    </footer>
                                </form>

                                <input type="hidden" id="braintree_credit_card_token" value="{$payment_account.object->get('Block Data')}"/>
                                <input type="hidden" id="credit_card_postal_code" value="{$order->get('Order Invoice Address Postal Code')}"/>


                                <script>

                                    var braintree_client = new braintree.api.Client({
                                        clientToken: $('#braintree_credit_card_token').val()
                                    });





                                    $("#BTree_credit_card_form").validate(
                                        {

                                            submitHandler: function(form)
                                            {



                                                var button=$('#place_order_braintree');

                                                if(button.hasClass('wait')){
                                                    return;
                                                }

                                                button.addClass('wait')
                                                button.find('i').removeClass('fa-arrow-right').addClass('fa-spinner fa-spin')




                                                braintree_client.tokenizeCard({
                                                    number: $("#credit_card_number").val(),
                                                    expirationMonth: $('#credit_card_util_month').val(),
                                                    expirationYear: $('#credit_card_util_year').val(),
                                                    cvv: $("#credit_card_ccv").val(),
                                                    billingAddress: {
                                                        postalCode: $('#credit_card_postal_code').val()
                                                    }

                                                }, onCardTokenization);






                                            },

                                            // Rules for form validation
                                            rules:
                                                {
                                                    credit_card_number:{
                                                        required: true,
                                                        creditcard: true
                                                    },

                                                    credit_card_ccv:{
                                                        required: true,
                                                        digits: true,
                                                        minlength: 3,
                                                        maxlength: 4
                                                    },
                                                    credit_card_util_year:{
                                                        required: true,
                                                        digits: true,
                                                        minlength: 2,
                                                        maxlength: 4
                                                    },
                                                    credit_card_util_month:{
                                                        required: true
                                                    }


                                    },

                                    // Messages for form validation
                                    messages:
                                    {






                                    },

                                    // Do not change code below
                                    errorPlacement: function(error, element)
                                    {
                                        error.insertAfter(element.parent());
                                    }
                                    });


                                    function onCardTokenization(err, nonce) {
                                        if (err) {
                                            return;
                                        }


                                        var register_data={ }


                                        register_data['nonce']=nonce

                                        register_data['save_card']=$('#save_card').is(':checked')


                                        var ajaxData = new FormData();

                                        ajaxData.append("tipo", 'place_order_pay_braintree')

                                        ajaxData.append("payment_account_key",'{$payment_account.object->id}' )
                                        ajaxData.append("data", JSON.stringify(register_data))





                                        console.log(JSON.stringify(register_data))


                                        $.ajax({
                                            url: "/ar_web_checkout.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,
                                            complete: function () {
                                            }, success: function (data) {




                                                if (data.state == '200') {
                                                    $('.ordered_products_number').html('0')
                                                    $('.order_total').html('')

                                                    window.location.replace("thanks.sys?order_key="+data.order_key);


                                                } else if (data.state == '400') {
                                                    var button=$('#place_order_braintree');
                                                    button.removeClass('wait')
                                                    button.find('i').addClass('fa-arrow-right').removeClass('fa-spinner fa-spin')

                                                    swal({ title:"{t}Error{/t}!", text:data.msg, type:"error", html: true}
                                                )

                                                }



                                            }, error: function () {
                                                var button=$('#place_order_braintree');
                                                button.removeClass('wait')
                                                button.find('i').addClass('fa-arrow-right').removeClass('fa-spinner fa-spin')

                                            }
                                        });







                                    }


                                </script>

                            {elseif $block=='BTreePaypal'}
                                <form action="" class="sky-form" style="max-width: 500px;">
                                    <header >{$content._form_title_paypal}</header>


                                    <fieldset style="min-height: 280px">

                                        <iframe style="border:none;width:100%" src="/braintree_paypal_iframe.php?data={$payment_account['object']->get('Block Data',$order)}"></iframe>


                                    </fieldset>

                                    <footer>

                                    </footer>
                                </form>


                            {elseif $block=='Bank'}


                                <form action="" class="sky-form" style="max-width: 500px;">
                                    <header id="_form_title_bank">{$content._form_title_bank}</header>


                                    <div style="padding:20px">

                                        {include  file='payment_bank_details.inc.tpl'  bank_payment_account=$payment_account.object }


                                    </div>


                                    <footer>
                                        <button  data-settings='{ "tipo":"place_order_pay_later", "payment_account_key":"{$payment_account.object->id}", "order_key":"{$order->id}"}' onclick="place_order(this)" class="button" id="_place_order_from_bank">{$content._place_order_from_bank} <i class="margin_left_10 fa fa-fw fa-arrow-right" aria-hidden="true"></i> </button>
                                    </footer>
                                </form>
                                

                            {elseif $block=='ConD'}


                            <form action="" class="sky-form" style="max-width: 500px;">
                                <header id="_form_title_bank">{$content._form_title_cash_on_delivery}</header>


                                <div style="padding:20px">

                                    {if isset($content._cash_on_delivery_text)}{{$content._cash_on_delivery_text}}{else}Pay on delivery{/if}

                                </div>


                                <footer>
                                    <button  data-settings='{ "tipo":"place_order_pay_later", "payment_account_key":"{$payment_account.object->id}", "order_key":"{$order->id}"}' onclick="place_order(this)" class="button" id="_place_order_from_cash_on_delivery">{$content._place_order_from_cash_on_delivery} <i class="margin_left_10 fa fa-fw fa-arrow-right" aria-hidden="true"></i> </button>
                                </footer>
                            </form>

                            {/if}

                        </div>
                    {/foreach}




                    </div><!-- end tab 3 -->


                </div>




            <script>






            </script>


            </div>


            <div class="clearfix marb12"></div>
        </div>
        <div class="clearfix marb12"></div>
        {include file="theme_1/footer.EcomB2B.tpl"}
    </div>

</div>
</body></html>


