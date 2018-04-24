{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 13 March 2018 at 14:52:38 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3
-->
*}




{if isset($data.top_margin)}{assign "top_margin" $data.top_margin}{else}{assign "top_margin" "0"}{/if}
{if isset($data.bottom_margin)}{assign "bottom_margin" $data.bottom_margin}{else}{assign "bottom_margin" "0"}{/if}


<div id="block_{$key}" data-block_key="{$key}"  block="{$data.type}" class="{$data.type}  {if !$data.show}hide{/if}"  style="padding-top:{$top_margin}px;padding-bottom:{$bottom_margin}px"  >


            <div class="content" style="margin-bottom: 10px">
                <table class="order_totals" style="margin-bottom: 0px;width: 400px;margin:auto">

                    <tbody>

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
                    <tr class="tax available_credit_amount_tr {if $order->get('Order Available Credit Amount')==0}hide{/if}" >
                        <td>{if isset($labels._order_available_credit_amount) and $labels._order_available_credit_amount!=''}{$labels._order_available_credit_amount}{else}{t}Credit{/t}{/if}</td>

                        <td class="text-right available_credit_amount ">{$order->get('Available Credit Amount')}</td>
                    </tr>
                    <tr class="to_pay_amount_tr {if $order->get('Order Available Credit Amount')!=0}total{/if} {if $order->get('Order Payments Amount')==0 and $order->get('Order Available Credit Amount')==0 }hide{/if}" >
                        <td>{if isset($labels._order_to_pay_amount) and $_order_to_pay_amoount._total!=''}{$labels._order_to_pay_amoount}{else}{t}To pay{/t}{/if}</td>

                        <td class="text-right to_pay_amount">{$order->get('Basket To Pay Amount')}</td>
                    </tr>

                    </tbody>
                </table>

                <form action="" method="post" enctype="multipart/form-data"  class="sky-form {if $order->get('Order Basket To Pay Amount')!=0}hide{/if}" style="box-shadow: none">
                    <footer>
                        <button  data-settings='{ "tipo":"place_order_pay_later", "payment_account_key":"{$store->get('Store Customer Payment Account Key')}", "order_key":"{$order->id}"}' onclick="place_order(this)" class="button" id="_place_order_from_bank">{$data.labels._place_order_from_bank} <i class="margin_left_10 fa fa-fw fa-arrow-right" aria-hidden="true"></i> </button>
                    </footer>

                </form>

            </div>



            {if  $order->get('Order Basket To Pay Amount')!=0}

            {assign "payment_accounts" $website->get_payment_accounts($order->get('Order Delivery Address Country 2 Alpha Code'))  }
                {assign "number_payment_accounts"  $payment_accounts|count  }




            <div style="margin: auto;width: 445px">
            <div class="menu-bottom-bar menu-bottom-bar-{if $number_payment_accounts==1}one{elseif $number_payment_accounts==2 }two{elseif $number_payment_accounts==3 }three{elseif $number_payment_accounts==4 }four{else}five{/if} color-menu-bar menu-bottom-bar-text flat-menu-bar">

                {foreach from=$payment_accounts item=payment_account key=key name=foo}




                    <a  class="but like_button payment_method_button no-smoothState {if $smarty.foreach.foo.first}bg-blue-light border-blue-dark{else}bg-black border-gray-dark{/if}" {if !$smarty.foreach.foo.first} style="opacity: .2"{/if}  data-tab="payment_account_item_{$payment_account.object->get('Block')}">
                        <i class="{$payment_account.icon}" aria-hidden="true"></i>
                        <em>{if $payment_account.tab_label==''}{$data.labels[$payment_account.tab_label_index]}{else}{$payment_account.tab_label}{/if}</em>
                    </a>


                {/foreach}


            </div>
            </div>
            <div class="clear"></div>

            {foreach from=$payment_accounts item=payment_account key=key name=foo}
                {assign "block" $payment_account.object->get("Block")  }

                <div id="payment_account_item_{$payment_account.object->get('Block')}" class="payment_method_block {if !$smarty.foreach.foo.first}hide{/if}" style="margin:auto;width: 440px">

                        {if $block=='BTree' }
                            <form id="BTree_credit_card_form" action="" class="sky-form" style="max-width: 500px;">



                                {assign "saved_cards" $customer->get_saved_credit_cards($order->get('Order Delivery Address Checksum'),$order->get('Order Invoice Address Checksum'))}

                                {assign "number_saved_cards" count($saved_cards)  }



                                <fieldset    class=" credit_card_form  {if $number_saved_cards>0}hide{/if}" >



                                    <div class="row">
                                        <section class="col col-9">
                                            <label class="input">
                                                <input type="text" name="credit_card_number" id="credit_card_number" value="" placeholder="{$data.labels._credit_card_number}">
                                            </label>
                                        </section>
                                        <section class="col col-3">
                                            <label class="input">
                                                <input type="text" maxlength="4" name="credit_card_ccv" id="credit_card_ccv" value=""  placeholder="{$data.labels._credit_card_ccv}">
                                            </label>
                                        </section>
                                    </div>

                                    <div class="row">
                                        <label class="label col col-4">{$data.labels._credit_card_expiration_date}</label>
                                        <section class="col col-5">
                                            <label class="select">
                                                <select name="credit_card_util_month" id="credit_card_util_month">
                                                    <option value="0" selected disabled>{$data.labels._credit_card_expiration_date_month_label}</option>

                                                    <option value="1">{'2000-01-01'|date_format:"%B"}</option>
                                                    <option value="2">{'2000-02-01'|date_format:"%B"}</option>
                                                    <option value="3">{'2000-03-01'|date_format:"%B"}</option>
                                                    <option value="4">{'2000-04-01'|date_format:"%B"}</option>
                                                    <option value="5">{'2000-05-01'|date_format:"%B"}</option>
                                                    <option value="6">{'2000-06-01'|date_format:"%B"}</option>
                                                    <option value="7">{'2000-07-01'|date_format:"%B"}</option>
                                                    <option value="8">{'2000-08-01'|date_format:"%B"}</option>
                                                    <option value="9">{'2000-09-01'|date_format:"%B"}</option>
                                                    <option value="10">{'2000-10-01'|date_format:"%B"}</option>
                                                    <option value="11">{'2000-11-01'|date_format:"%B"}</option>
                                                    <option value="12">{'2000-12-01'|date_format:"%B"}</option>
                                                </select>
                                                <i></i>
                                            </label>
                                        </section>
                                        <section class="col col-3">
                                            <label class="input">
                                                <input type="text" maxlength="4" name="credit_card_util_year" id="credit_card_util_year" value="" placeholder="{$data.labels._credit_card_expiration_date_year_label}">
                                            </label>
                                        </section>
                                    </div>

                                    <div class="row hide">

                                        <section class="col col-5" >
                                            <label class="checkbox"><input type="checkbox"  id="save_card"><i></i> </label>
                                            <span style="margin-left:27px;	font: 14px/1.55 'Open Sans', Helvetica, Arial, sans-serif;position:relative;top:-22px;font-size:15px;color:#404040" id="_credit_card_save"
                                            >{$data.labels._credit_card_save}</span>

                                        </section>


                                    </div>

                                    <div class="row">




                                    </div>

                                </fieldset>

                                <footer>

                                    <section  class="col col-5 like_button show_saved_cards_list hide "  onclick="show_saved_cards()" >

                                        {if isset($data.labels._show_saved_cards) and $labels._show_saved_cards!=''}{$labels._show_saved_cards}{else}{t}Show saved cards list{/t}{/if}
                                    </section>
                                    <button id="place_order_braintree" type="submit" class="button  {if $number_saved_cards>0} state-disabled{/if}  ">{$data.labels._place_order_from_credit_card}  <i class="margin_left_10 fa fa-fw fa-arrow-right" aria-hidden="true"></i>  </button>
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


                                <fieldset style="min-height: 280px">

                                    <iframe style="border:none;width:100%" src="/braintree_paypal_iframe.php?tipo=a&payment_account_key={$payment_account['object']->id}"></iframe>


                                </fieldset>

                                <footer>

                                </footer>
                            </form>


                        {elseif $block=='Bank'}


                            <form action="" class="sky-form" style="max-width: 500px;">


                                <div style="padding:20px">

                                    {include  file='payment_bank_details.inc.mobile.tpl'  bank_payment_account=$payment_account.object }


                                </div>


                                <footer>
                                    <button  data-settings='{ "tipo":"place_order_pay_later", "payment_account_key":"{$payment_account.object->id}", "order_key":"{$order->id}"}' onclick="place_order(this)" class="button" id="_place_order_from_bank">{$data.labels._place_order_from_bank} <i class="margin_left_10 fa fa-fw fa-arrow-right" aria-hidden="true"></i> </button>
                                </footer>
                            </form>


                        {elseif $block=='ConD'}


                            <form action="" class="sky-form" style="max-width: 500px;">


                                <div style="padding:20px">

                                    {if isset($data.labels._cash_on_delivery_text)}{{$data.labels._cash_on_delivery_text}}{else}Pay on delivery{/if}

                                </div>


                                <footer>
                                    <button  data-settings='{ "tipo":"place_order_pay_later", "payment_account_key":"{$payment_account.object->id}", "order_key":"{$order->id}"}' onclick="place_order(this)" class="button" id="_place_order_from_cash_on_delivery">{$data.labels._place_order_from_cash_on_delivery} <i class="margin_left_10 fa fa-fw fa-arrow-right" aria-hidden="true"></i> </button>
                                </footer>
                            </form>

                        {/if}


                </div>

            {/foreach}


            {/if}






</div>


            <div class="coverpage-clear"></div>




<script>
    $(document).on('click', '.payment_method_button', function (evt) {


        $('.payment_method_button').addClass('discreet bg-gray-light border-gray-dark').removeClass('bg-blue-light border-blue-dark').css({ 'opacity':.2})

        $(this).removeClass('discreet bg-gray-light border-gray-dark').addClass('bg-blue-light border-blue-dark').css({ 'opacity':1})

        $('.payment_method_block').addClass('hide')
        $('#'+$(this).data('tab')).removeClass('hide')
    });
</script>


