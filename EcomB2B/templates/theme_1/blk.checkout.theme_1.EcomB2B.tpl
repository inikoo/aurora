﻿{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 27 July 2017 at 17:26:03 CEST, Tranava, Slovakia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}



{if isset($data.top_margin)}{assign "top_margin" $data.top_margin}{else}{assign "top_margin" "0"}{/if}
{if isset($data.bottom_margin)}{assign "bottom_margin" $data.bottom_margin}{else}{assign "bottom_margin" "0"}{/if}


<div id="block_{$key}" data-block_key="{$key}"  block="{$data.type}" class="{$data.type}  {if !$data.show}hide{/if}"  style="padding-top:{$top_margin}px;padding-bottom:{$bottom_margin}px"  >

    <div class="container">




        <div class="order_header  text_blocks  text_template_21">

            <div class="text_block '">
                <h2>{if !empty($labels._order_number_label)}{$labels._order_number_label}{else}{t}Order number{/t}{/if} <span class="order_number">{$order->get('Public ID')}</span></h2>
                <div class="text_blocks text_template_2">
            <div class="text_block ">
                <h5 >

                        <span id="delivery_label" class="{if $order->get('Order For Collection')=='Yes'}hide{/if}">
                        <i id="_delivery_address_icon" class="fal fa-fw fa-clipboard   " aria-hidden="true"></i>
                        <span id="_delivery_address_label">{if isset($labels._delivery_address_label) and $labels._delivery_address_label!=''}{$labels._delivery_address_label}{else}{t}Delivery Address:{/t}{/if}</span>
                        </span>
                    <span id="collection_label" class="{if $order->get('Order For Collection')=='No'}hide{/if} "">
                    <i id="_delivery_address_icon" class="far fa-fw fa-hand-rock  aria-hidden=" true"></i>
                    <span id="_delivery_address_label">{if isset($labels._for_collecion_label) and $labels._for_collecion_label!=''}{$labels._for_collecion_label}{else}{t}To be collected at:{/t}{/if}</span>

                    </span>


                </h5>
                <p>
                <div class="formatted_delivery_address">{$order->get('Order Delivery Address Formatted')}</div>
                </p>
            </div>

            <div class="text_block">
                <h5 style="position: relative;left:-10px">
                    <i id="_invoice_address_icon" class="fa fa-fw fa-dollar-sign" aria-hidden="true"></i>
                    <span id="_invoice_address_label">{if isset($labels._invoice_address_label) and $labels._invoice_address_label!=''}{$labels._invoice_address_label}{else}{t}Invoice Address{/t}{/if}</span>


                </h5>
                <p>
                <div class="formatted_invoice_address">{$order->get('Order Invoice Address Formatted')}</div>
                </p>
            </div>
                </div>
            </div>

            <div class="totals text_block">


                <table class="table">


                    <tbody>


                    <tr>
                        <td>{if isset($labels._items_net) and $labels._items_net!=''}{$labels._items_net}{else}{t}Items Net{/t}{/if}</td>

                        <td class="text-right order_items_net">{$order->get('Items Net Amount')}</td>
                    </tr>
                    <tr class="order_charges_container {if $order->get('Order Charges Net Amount')==0 }hide{/if}">
                        <td><i class="button fa fa-info-circle padding_right_5 info" style="color: #007fff;"
                                    {if $website->get('Website Type')=='EcomDS'}onclick="show_client_charges_info({$order->id})"{else}onclick="show_charges_info()"{/if}
                            ></i>  {if !empty($labels._items_charges)}{$labels._items_charges}{else}{t}Charges{/t}{/if}</td>

                        <td class="text-right order_charges">{$order->get('Charges Net Amount')}</td>
                    </tr>
                    <tr>
                        <td>{if isset($labels._items_shipping) and $labels._items_shipping!=''}{$labels._items_shipping}{else}{t}Shipping{/t}{/if}</td>

                        <td class="text-right order_shipping">{if $order->get('Shipping Net Amount')=='TBC'}<i class="fa error fa-exclamation-circle" title="" aria-hidden="true"></i> <small>{if !empty($labels._we_will_contact_you)}{$labels._we_will_contact_you}{else}{t}We will contact you{/t}{/if}</small>{else}{$order->get('Shipping Net Amount')}    {/if}  </td>
                    </tr>



                    <tr>
                        <td>{if isset($labels._total_net) and $labels._total_net!=''}{$labels._total_net}{else}{t}Total Net{/t}{/if}</td>

                        <td class="text-right order_net">{$order->get('Total Net Amount')}</td>
                    </tr>
                    <tr>
                        <td>{if isset($labels._total_tax) and $labels._total_tax!=''}{$labels._total_tax}{else}{t}Tax{/t}{/if}<div class="tax_description" style="font-size: small">{$order->get('Tax Description')}</div></td>

                        <td class="text-right order_tax">{$order->get('Total Tax Amount')}</td>
                    </tr>
                    <tr class="total">
                        <td>{if isset($labels._total) and $labels._total!=''}{$labels._total}{else}{t}Total{/t}{/if}</td>

                        <td class="text-right order_total">{$order->get('Total')}</td>
                    </tr>
                    <tr class="payments_amount_tr {if $order->get('Order Payments Amount')==0}hide{/if}">
                        <td>{if isset($labels._order_paid_amount) and $labels._order_paid_amount!=''}{$labels._order_paid_amount}{else}{t}Paid{/t}{/if}</td>

                        <td class="text-right payments_amount">{$order->get('Basket Payments Amount')}</td>
                    </tr>
                    <tr class="available_credit_amount_tr {if $order->get('Order Available Credit Amount')==0}hide{/if}">
                        <td>{if isset($labels._order_available_credit_amount) and $labels._order_available_credit_amount!=''}{$labels._order_available_credit_amount}{else}{t}Credit{/t}{/if}</td>

                        <td class="text-right available_credit_amount ">{$order->get('Available Credit Amount')}</td>
                    </tr>
                    <tr class="to_pay_amount_tr total {if $order->get('Order Payments Amount')==0 and $order->get('Order Available Credit Amount')==0 }hide{/if}">
                        <td>{if isset($labels._order_to_pay_amount) and $_order_to_pay_amount._total!=''}{$labels._order_to_pay_amount}{else}{t}To pay{/t}{/if}</td>

                        <td class="text-right to_pay_amount">{$order->get('Basket To Pay Amount')}</td>
                    </tr>

                    </tbody>
                </table>


            </div>

        </div>


        <form action="" method="post" enctype="multipart/form-data" class="sky-form {if $order->get('Order Basket To Pay Amount')!=0}hide{/if}" style="box-shadow: none">

            <footer>
                <button data-settings='{ "tipo":"place_order_pay_later", "payment_account_key":"{$store->get('Store Customer Payment Account Key')}", "order_key":"{$order->id}"}' onclick="place_order(this)"
                        class="button" >{$data.labels._place_order_from_bank} <i class="margin_left_10 fa fa-fw fa-arrow-right" aria-hidden="true"></i></button>
            </footer>

        </form>

    </div>

    <div class="clear" style="margin-bottom: 30px"> </div>
    {if $order->get('Order Basket To Pay Amount')>0}
    <div class="container clear  {if $order->get('Order Basket To Pay Amount')==0}hide{/if}">

                {assign "payment_accounts" $website->get_payment_accounts(
                $order->get('Order Delivery Address Country 2 Alpha Code'),
                $order->get('Order Invoice Address Country 2 Alpha Code'),
                $customer

                )  }

                <ul class="tabs3">

                    {foreach from=$payment_accounts item=payment_account key=key}

                        {if $payment_account.hide!='yes'}



                        <li id="payment_tab_header_{$payment_account.block}" class="{if $payment_account.block=='Hokodo' and  $payment_account.count>1}hide{/if}">
                            <a href="#payment_account_item_{$payment_account.object->get('Block')}" target="_self" data-analytics_label="{$payment_account.analytics_label}" class="payment_option_chooser" >


                                {if $payment_account.block=='Hokodo'}
                                <svg  style="margin-right: 5px;position: relative;top:7px" height="24" viewBox="0 0 71 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect width="70.6667" height="30" rx="4" fill="#64CDC8"/>
                                    <path d="M21.5564 20.0001C19.0102 20.0001 17.0625 18.1018 17.0625 15.3338C17.0625 12.5658 19.014 10.6667 21.5564 10.6667C24.1156 10.6667 26.0633 12.5651 26.0633 15.3338C26.0633 18.1026 24.1156 20.0001 21.5564 20.0001ZM21.5564 12.5651C20.1367 12.5651 19.2328 13.6638 19.2328 15.3338C19.2328 17.0038 20.1367 18.1018 21.5564 18.1018C22.9891 18.1018 23.8931 17.003 23.8931 15.3338C23.8931 13.6646 22.9891 12.5651 21.5564 12.5651Z" fill="#132E34"/>
                                    <path d="M32.7132 19.8286L30.0287 16.1044L29.2083 17.0029V19.8286H27.0928V10.8381H29.2076V14.648L32.5879 10.8381H35.0225L31.5586 14.6909L35.2451 19.8286H32.7132Z" fill="#132E34"/>
                                    <path d="M39.4319 20.0001C36.8857 20.0001 34.938 18.1018 34.938 15.3338C34.938 12.5658 36.8857 10.6667 39.4319 10.6667C41.9911 10.6667 43.9388 12.5651 43.9388 15.3338C43.9388 18.1026 41.9911 20.0001 39.4319 20.0001ZM39.4319 12.5651C38.0122 12.5651 37.1082 13.6638 37.1082 15.3338C37.1082 17.0038 38.0122 18.1018 39.4319 18.1018C40.8646 18.1018 41.7685 17.003 41.7685 15.3338C41.7685 13.6646 40.8646 12.5651 39.4319 12.5651Z" fill="#132E34"/>
                                    <path d="M44.9692 10.8381H48.364C51.2437 10.8381 53.0243 12.5221 53.0243 15.3337C53.0243 18.1454 51.2437 19.8286 48.364 19.8286H44.9692V10.8381ZM48.225 18.1165C49.8802 18.1165 50.8427 17.0887 50.8427 15.3189C50.8427 13.5781 49.8825 12.5502 48.2273 12.5502H47.0878V18.1165H48.225Z" fill="#132E34"/>
                                    <path d="M58.1988 20.0001C55.6532 20.0001 53.7056 18.1018 53.7056 15.3338C53.7056 12.5658 55.6532 10.6667 58.1988 10.6667C60.7587 10.6667 62.7064 12.5651 62.7064 15.3338C62.7064 18.1026 60.7587 20.0001 58.1988 20.0001ZM58.1988 12.5651C56.7798 12.5651 55.8758 13.6638 55.8758 15.3338C55.8758 17.0038 56.7798 18.1018 58.1988 18.1018C59.6314 18.1018 60.5361 17.003 60.5361 15.3338C60.5361 13.6646 59.6314 12.5651 58.1988 12.5651Z" fill="#132E34"/>
                                    <path d="M13.8429 10.8381V14.2093H10.9214V16.4575H13.8429V19.8286H16.0337V10.8381H13.8429Z" fill="#132E34"/>
                                    <path d="M8 19.8286H10.1908V16.4575H9.46076V14.2093H10.1908V10.8381H8V19.8286Z" fill="#132E34"/>
                                </svg>
                                {else}
                                <i class="{$payment_account.icon}" aria-hidden="true"></i>
                                {/if}
                                <span>{if $payment_account.tab_label==''}{if isset($data.labels[$payment_account.tab_label_index])} {$data.labels[$payment_account.tab_label_index]}{/if}{else}{$payment_account.tab_label}{/if}  </span>
                            </a>
                        </li>
                        {/if}
                    {/foreach}

                </ul>

                <div class="tabs-content3 two">

                    {assign "braintree_data" ""}
                    {foreach from=$payment_accounts item=payment_account key=key}
                        {assign "block" $payment_account.object->get("Block")  }
                        {if $block=='BTree' or  $block=='BTreePaypal'}
                            {if $braintree_data==''}
                            {assign "braintree_data" $payment_account.object->get('Block Data',$order->get('Order Customer Key'))  }
                            {/if}
                        {/if}

                        {if $block=='BTree'}
                          <script> var BTree_account_key={$payment_account.object->id}</script>
                        {/if}

                        {if $block=='BTreePaypal'}
                         <script>var BTreePaypal_account_key={$payment_account.object->id}</script>
                        {/if}



                    {/foreach}



                    {foreach from=$payment_accounts item=payment_account key=key}

                        {assign "block" $payment_account.object->get("Block")  }



                        <div id="payment_account_item_{$block}" class="tabs-panel3" >

                            {if $block=='Checkout' }
                                <iframe src="ar_web_payment_account_checkout_iframe.php?order_key={$order->id}" title="Checkout" style="width: 100%;border:none;"></iframe>
                            {elseif $block=='Pastpay' }
                                <iframe src="ar_web_payment_account_pastpay_iframe.php?order_key={$order->id}" title="Pastpay" style="height:500px;width:100%;border:none;overflow:hidden;" ></iframe>
                            {elseif $block=='Paypal' }
                                <div id="paypal-button-container" style="width: 400px"></div>

                                  <script>

                                    jQuery(function() {

                                    const FUNDING_SOURCES = [
                                    paypal.FUNDING.PAYPAL,
                                    paypal.FUNDING.PAYLATER,
                                    paypal.FUNDING.IDEAL,
                                    ];

                                    FUNDING_SOURCES.forEach(fundingSource => {
                                    paypal.Buttons({
                                                     fundingSource,

                                                     style: {
                                                       layout: 'vertical',
                                                       shape: 'rect',
                                                       color: (fundingSource == paypal.FUNDING.PAYLATER) ? 'gold' : '',
                                                     },

                                                     createOrder: async (data, actions) => {
                                                       try {

                                                         const response =
                                                                   await fetch("ar_web_paypal_get_order_id.php?payment_account_id={$payment_account.object->id}&order_id={$order->id}", {
                                                           method: "GET"
                                                         });

                                                         const details = await response.json();
                                                         console.log(details)

                                                         console.log(details.data.id)
                                                         return details.data.id;

                                                       }catch (e) {
                                                         console.log(e)
                                                       }
                                                     },
                                                     onApprove: async (data, actions) => {
                                                       try {
                                                         console.log(data)
                                                         const response = await fetch(`ar_web_paypal_capture_payment.php?ds_order_key={$order->id}&order_id=`+data.orderID, {
                                                           method: "GET"
                                                         });

                                                         const details = await response.json();
                                                         // Three cases to handle:
                                                         //   (1) Recoverable INSTRUMENT_DECLINED -> call actions.restart()
                                                         //   (2) Other non-recoverable errors -> Show a failure message
                                                         //   (3) Successful transaction -> Show confirmation or thank you message

                                                         // This example reads a v2/checkout/orders capture response, propagated from the server
                                                         // You could use a different API or structure for your 'orderData'
                                                         const errorDetail = Array.isArray(details.details) && details.details[0];

                                                         if (errorDetail && errorDetail.issue === 'INSTRUMENT_DECLINED') {
                                                           return actions.restart();
                                                           // https://developer.paypal.com/docs/checkout/integration-features/funding-failure/
                                                         }

                                                         if (errorDetail) {
                                                           let msg = 'Sorry, your transaction could not be processed.';
                                                           msg += errorDetail.description ? ' ' + errorDetail.description : '';
                                                           msg += details.debug_id ? ' (' + details.debug_id + ')' : '';
                                                           alert(msg);
                                                           return;
                                                         }

                                                         // Successful capture! For demo purposes:
                                                         console.log('Capture result', details, JSON.stringify(details, null, 2));
                                                         const transaction = details.purchase_units[0].payments.captures[0];



                                                            if(details.status==='COMPLETED') {

                                                              $.ajax({
                                                                       type   : 'POST',
                                                                       url    : 'ar_web_paypal_process_payment.php?order_id={$order->id}',
                                                                       data   : details,
                                                                       success: function(raw_data) {
                                                                         $('#processing').addClass('hidden');

                                                                         console.log(raw_data)
                                                                         const data = JSON.parse(raw_data);

                                                                         console.log(data)

                                                                         $('.ordered_products_number').html('0')
                                                                         $('.order_total').html('')

                                                                         var d = new Date();
                                                                         var timestamp = d.getTime()
                                                                         d.setTime(timestamp + 300000);
                                                                         var expires = "expires=" + d.toUTCString();
                                                                         document.cookie = "au_pu_" + data.order_key + "=" + data.order_key + ";" + expires + ";path=/";
                                                                         window.location.replace("thanks.sys?order_key=" + data.order_key + '&t=' + timestamp);

                                                                       },
                                                                     });

                                                            }else{
                                                              alert('Error, please try other payment method');
                                                            }

                                                        // alert('Transaction ' + transaction.status + ': ' + transaction.id + 'See console for all available details');
                                                       } catch (error) {
                                                         console.error(error);
                                                         // Handle the error or display an appropriate error message to the user
                                                       }
                                                     },
                                                   }).render("#paypal-button-container");
                                  })

                                    });

                                </script>


                             {elseif $block=='Hokodo' }
                                <iframe src="ar_web_payment_account_hokodo_sdk_iframe.php?order_key={$order->id}" title="Hokodo"


                                        style="height:1800px;width:100%;border:none;overflow:hidden;"  ></iframe>


                                        <script>
                                            function showHokodoTab(){
                                                $('#payment_tab_header_Hokodo').removeClass('hide')
                                            }
                                        </script>


                            {elseif $block=='BTree' }


                                <form id="BTree_saved_credit_cards_form" action="" class="sky-form {if $braintree_data.number_saved_credit_cards==0}hide{/if}" style="max-width: 500px;">
                                    <header>{$data.labels._form_title_credit_card}</header>


                                    <fieldset  class="credit_cards_list " >

                                        <div class="row credit_card_input_row hide" >
                                            <section class="col col-1">

                                            </section>




                                            <section class="col col-6 card_info" data-token=""  style="margin-left: 10px"></section>

                                            <section class="col col-4  cvv_for_saved_card  " style="position: relative;top:-7px;">
                                                <label class="input">
                                                    <div id="saved_credit_card_ccv"  style="border:1px solid #ccc;height: 36px"></div>
                                                </label>
                                            </section>
                                        </div>


                                        {foreach from=$braintree_data.credit_cards item=saved_card }

                                            <div class="row credit_cards_row" >
                                                <section class="col col-1">
                                                    <i onclick="delete_this_credit_card(this)" title="{t}Delete card{/t}" style="position: relative;top:2px;" class="like_button delete_this_credit_card far fa-trash-alt "></i>

                                                </section>




                                                <section class="col col-6 like_button card_info" data-nonce="{$saved_card['nonce']}" data-token="{$saved_card['Token']}" style="margin-left: 10px" onclick="use_this_credit_card(this)">
                                                    <div style="float: left;margin-right: 10px"><img style="width: 40px;" src="{$saved_card['Image']}"/> </div> <div style="borxder: 1px solid red"> {$saved_card['Masked Number']}  ({$saved_card['Formatted Expiration Date']})   </div></section>
                                                </section>
                                                <section class="col col-4 like_button" style="text-align: right"  onclick="use_this_credit_card(this)">
                                                    <span style="position: relative;top:2px;">{t}Use this card{/t}</span>

                                                </section>

                                            </div>


                                        {/foreach}

                                        <div class="row submit_row ">
                                            <section class="col col-1">

                                            </section>
                                            <section class="col col-11 like_button "  onclick="use_other_credit_card()" >
                                                 {if isset($data.labels._pay_with_other_card) and $labels._pay_with_other_card!=''}{$labels._pay_with_other_card}{else}{t}Pay with other card{/t}{/if}
                                            </section>



                                        </div>


                                    </fieldset>

                                    <footer>

                                        <section  class="col col-5  "  >
                                            <span class="like_button show_saved_cards_list hide" style="color:#666" onclick="show_saved_cards()" >{if isset($data.labels._show_saved_cards) and $labels._show_saved_cards!=''}{$labels._show_saved_cards}{else}{t}Show saved cards list{/t}{/if}</span>
                                        </section>
                                        <button id="place_order_saved_card_braintree" type="submit" class="button state-disabled">{$data.labels._place_order_from_credit_card}  <i class="margin_left_10 fa fa-fw fa-arrow-right" aria-hidden="true"></i>  </button>
                                    </footer>
                                </form>

                                <form id="BTree_credit_card_form" action="" class="sky-form  {if $braintree_data.number_saved_credit_cards>0}hide{/if}" style="max-width: 500px;">
                                <header>{$data.labels._form_title_credit_card}</header>

                                {assign "saved_cards" $customer->get_saved_credit_cards($order->get('Order Delivery Address Checksum'),$order->get('Order Invoice Address Checksum'))}
                                {assign "number_saved_cards" count($saved_cards)  }



                                <fieldset  class="credit_card_form" >



                                    <div class="row">
                                        <section class="col col-9">
                                            <label class="input">
                                                <div  id="credit_card_number" style="border:1px solid #ccc;height: 36px" ></div>
                                            </label>
                                        </section>
                                        <section class="col col-3">
                                            <label class="input">
                                                <div ty id="credit_card_ccv" value=""  style="border:1px solid #ccc;height: 36px"></div>
                                            </label>
                                        </section>
                                    </div>

                                    <div class="row">
                                        <label class="label col col-4">{$data.labels._credit_card_expiration_date}</label>
                                        <section class="col col-5">
                                            <label class="select">
                                                <div id="credit_card_valid_until" style="border:1px solid #ccc;height: 36px">

                                            </label>
                                        </section>
                                        <section class="col col-3">
                                            <label class="input">



                                            </label>
                                        </section>
                                    </div>

                                    <div class="row ">

                                        <section class="col col-5" >
                                            <label class="checkbox"><input type="checkbox"  id="save_card"><i></i> </label>
                                            <span style="margin-left:27px;position:relative;bottom:2px	" >{$data.labels._credit_card_save}</span>

                                        </section>


                                    </div>

                                    <div class="row">


                                    </div>

                                </fieldset>

                                <footer>

                                    <section  class="col col-5  "   >
                                        <span class="like_button show_saved_cards_list {if $braintree_data.number_saved_credit_cards==0}hide{/if}"" style="color:#666" onclick="show_saved_cards()" >{if isset($data.labels._show_saved_cards) and $labels._show_saved_cards!=''}{$labels._show_saved_cards}{else}{t}Show saved cards list{/t}{/if}</span>

                                    </section>
                                    <button id="place_order_braintree" type="submit" class="button  {if $braintree_data.number_saved_credit_cards>0} state-disabled{/if}  ">{$data.labels._place_order_from_credit_card}  <i class="margin_left_10 fa fa-fw fa-arrow-right" aria-hidden="true"></i>  </button>
                                    </footer>
                            </form>

                                <script>






                                    function delete_this_credit_card(element){

                                        $(element).removeClass('fa-trash-alt').addClass('fa-spinner fa-spin')

                                        var ajaxData = new FormData();

                                        ajaxData.append("tipo", 'delete_braintree_saved_card')
                                        ajaxData.append("token", $(element).closest('.credit_cards_row').find('.card_info').data('token'))
                                        ajaxData.append("payment_account_key", BTree_account_key)

                                        $.ajax({
                                            url: "/ar_web_checkout.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,
                                            complete: function () {
                                            }, success: function (data) {




                                                if (data.state == '200') {

                                                    $(element).closest('.credit_cards_row').remove();


                                                } else if (data.state == '400') {
                                                    $(element).removeClass('fa-spinner fa-spin').addClass('fa-trash-alt')

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



                                    var saved_card_form = document.querySelector('#BTree_saved_credit_cards_form');
                                    var saved_card_submit = document.querySelector('#place_order_saved_card_braintree');

                                    var form = document.querySelector('#BTree_credit_card_form');
                                    var submit = document.querySelector('#place_order_braintree');






                                </script>

                            {elseif $block=='Sofort'}


                                <form id="Sofort_form" action="" class="sky-form" style="max-width: 500px;">
                                    <header >{$data.labels._form_title_online_bank_transfer}</header>


                                    <div style="padding:20px">
                                        <p id="_online_bank_transfer_text" contenteditable="true" >{if !empty($data.labels._online_bank_transfer_text)}{{$data.labels._online_bank_transfer_text}}{else}Pay with Sofort{/if}</p>

                                    </div>


                                    <footer>
                                        <button   class="button" id="place_order_from_Sofort">{$data.labels._place_order_from_online_bank_transfer} <i class="margin_left_10 fa fa-fw fa-arrow-right" aria-hidden="true"></i> </button>
                                    </footer>
                                </form>



                                <script>


                                    $("#Sofort_form").validate(
                                        {

                                            submitHandler: function(form)
                                            {



                                                var button=$('#place_order_from_Sofort');

                                                if(button.hasClass('wait')){
                                                    return;
                                                }

                                                button.addClass('wait')
                                                button.find('i').removeClass('fa-arrow-right').addClass('fa-spinner fa-spin')



                                                var ajaxData = new FormData();

                                                ajaxData.append("tipo", 'place_order_pay_sofort')

                                                ajaxData.append("payment_account_key",'{$payment_account.object->id}' )







                                                $.ajax({
                                                    url: "/ar_web_sofort.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,
                                                    complete: function () {
                                                    }, success: function (data) {




                                                        if (data.state == '200') {


                                                            window.location.replace(data.redirect);


                                                        } else if (data.state == '400') {
                                                            var button=$('#place_order_from_Sofort');
                                                            button.removeClass('wait')
                                                            button.find('i').addClass('fa-arrow-right').removeClass('fa-spinner fa-spin')

                                                            swal({ title:"{t}Error{/t}!", text:data.msg, type:"error", html: true}
                                                            )

                                                        }



                                                    }, error: function () {
                                                        var button=$('#place_order_from_Sofort');
                                                        button.removeClass('wait')
                                                        button.find('i').addClass('fa-arrow-right').removeClass('fa-spinner fa-spin')

                                                    }
                                                });





                                            },

                                            // Do not change code below
                                            errorPlacement: function(error, element)
                                            {
                                                error.insertAfter(element.parent());
                                            }
                                        });


                                </script>

                            {elseif $block=='BTreePaypal'}


                                <form action="" class="sky-form" style="max-width: 500px;">
                                    <header >{if isset($data.labels._form_title_paypal) }{$data.labels._form_title_paypal}{else}{t}Checkout form{/t}{/if}</header>


                                    <fieldset style="min-height: 280px">

                                        <div  id="loading_paypal-button"><i class="fa fa-spinner fa-spin" style="margin-right: 10px"></i>  {t}Loading paypal{/t}</div>
                                        <div  id="paying_paypal" class="hide"><i class="fab fa-paypal" style="margin-right: 10px"></i>  {t}Processing payment{/t}</div>
                                        <div  id="processing_paypal" class="hide"><i class="fa fa-spinner fa-spin" style="margin-right: 10px"></i>  {t}Processing payment, please wait{/t}</div>




                                        <div class="hide" id="paypal-button"></div>


                                    </fieldset>

                                    <footer>

                                    </footer>
                                </form>






                            {elseif $block=='Bank'}
                                <form action="" class="sky-form" style="max-width: 500px;">
                                    <header id="_form_title_bank">{$data.labels._form_title_bank}</header>


                                    <div style="padding:20px">

                                        {include  file='payment_bank_details.inc.tpl'  bank_payment_account=$payment_account.object content=$data.labels}


                                    </div>


                                    <footer>
                                        <button  data-settings='{ "tipo":"place_order_pay_later", "payment_account_key":"{$payment_account.object->id}", "order_key":"{$order->id}"}' onclick="place_order(this)" class="button" id="_place_order_from_bank">{$data.labels._place_order_from_bank} <i class="margin_left_10 fa fa-fw fa-arrow-right" aria-hidden="true"></i> </button>
                                    </footer>
                                </form>






                            {elseif $block=='ConD'}


                            <form action="" class="sky-form" style="max-width: 500px;">

                                <header id="_form_title_bank">{$data.labels._form_title_cash_on_delivery}</header>


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


                    {assign "secure_3d" true}
                    {if $braintree_data!=''}


                    {if $secure_3d}
                        <script>

                            var threeDSecure;
                            var deviceData;



                            braintree.client.create({
                                    authorization: '{$braintree_data['client_token']}'
                                },
                                function (clientErr, clientInstance) {
                                    if (clientErr) {
                                        console.error(clientErr);
                                        Sentry.captureException(clientErr);

                                        return;
                                    }


                                    braintree.dataCollector.create({
                                        client: clientInstance,
                                        paypal: true
                                    }, function (err, dataCollectorInstance) {
                                        if (err) {
                                            // Handle error in creation of data collector
                                            Sentry.captureException(err);
                                            return;
                                        }
                                        // At this point, you should access the dataCollectorInstance.deviceData value and provide it
                                        // to your server, e.g. by injecting it into your form as a hidden input.
                                        deviceData = dataCollectorInstance.deviceData;


                                    });


                                    braintree.threeDSecure.create({
                                        version: 2,
                                        client: clientInstance
                                    }, function (threeDSecureErr, threeDSecureInstance) {
                                        if (threeDSecureErr) {
                                            Sentry.captureException(threeDSecureErr);
                                            console.error(threeDSecureErr);
                                            return;
                                        }

                                        threeDSecure = threeDSecureInstance;
                                    });


                                    braintree.hostedFields.create({
                                            client: clientInstance,
                                            styles: {
                                                'input': {
                                                    'font-size': '16px', 'padding': '0px 0px 0px 10px','font-family':'Ubuntu, sans-serif'
                                                },
                                                'input.invalid': {
                                                    'color': 'red'
                                                },
                                                'input.valid': {
                                                    'color': 'green'
                                                }
                                            },
                                            fields: {

                                                cvv: {
                                                    selector: '#saved_credit_card_ccv',
                                                    placeholder: '{$data.labels._credit_card_ccv}'
                                                },

                                            }
                                        },
                                        function (hostedFieldsErr, hostedFieldsInstance) {
                                            if (hostedFieldsErr) {
                                                Sentry.captureException(hostedFieldsErr);
                                                console.error(hostedFieldsErr);
                                                return;
                                            }


                                            hostedFieldsInstance.on('validityChange', function (event) {

                                                if(event.fields.cvv.isValid){
                                                    $('#place_order_saved_card_braintree').removeClass('state-disabled')

                                                }else{
                                                    $('#place_order_saved_card_braintree').addClass('state-disabled')

                                                }

                                            });


                                            saved_card_submit.removeAttribute('disabled');

                                            saved_card_form.addEventListener('submit', function (event) {
                                                event.preventDefault();

                                                var button=$('#place_order_saved_card_braintree');

                                                if(button.hasClass('wait')){
                                                    return;
                                                }

                                                button.addClass('wait')
                                                button.find('i').removeClass('fa-arrow-right').addClass('fa-spinner fa-spin')


                                                hostedFieldsInstance.tokenize(function (tokenizeErr, payload) {


                                                    var nonce_for_card_verification=payload.nonce

                                                    if (tokenizeErr) {


                                                        var button=$('#place_order_saved_card_braintree');
                                                        button.removeClass('wait')
                                                        button.find('i').addClass('fa-arrow-right').removeClass('fa-spinner fa-spin')

                                                        if($('#BTree_saved_credit_cards_form .credit_card_input_row').hasClass('hide')){
                                                            swal({ title:"{t}Error{/t}!", text:'{t}Choose a saved credit card or click in pay with other card{/t}', type:"error", html: true})
                                                        }else{

                                                            switch (tokenizeErr.code) {
                                                                case 'HOSTED_FIELDS_FIELDS_EMPTY':
                                                                    var _msg='{t}Please fill the CVV field{/t}';
                                                                    break;
                                                                case 'HOSTED_FIELDS_FIELDS_INVALID':
                                                                    var _msg='{t}CVV is invalid{/t}';
                                                                    break;
                                                                default:
                                                                    var _msg=tokenizeErr.message
                                                                    break;
                                                            }

                                                            swal({ title:"{t}Error{/t}!", text:_msg, type:"error", html: true})


                                                        }


                                                        //console.error(tokenizeErr);
                                                        return;
                                                    }



                                                    threeDSecure.verifyCard({
                                                        amount: '{$order->get('Order Basket To Pay Amount')}',
                                                        nonce: $('#BTree_saved_credit_cards_form .credit_card_input_row .card_info').data('nonce'),
                                                        bin: payload.details.bin,
                                                        email: '{$customer->get('Customer Main Plain Email')}',
                                                        {if $customer->get('Customer Main Plain Mobile')}
                                                        mobilePhoneNumber: '{$customer->get('Customer Main Plain Mobile')}'.replace(/\D/g,''),
                                                        {/if}


                                                        billingAddress: {
                                                            locality: '{$customer->get('Customer Invoice Address Locality')}',
                                                            {if $customer->get('Customer Invoice Address Postal Code')}
                                                            postalCode: '{$customer->get('Customer Invoice Address Postal Code')}',
                                                            {/if}
                                                            countryCodeAlpha2: '{$customer->get('Customer Invoice Address Country 2 Alpha Code')}'
                                                        },

                                                        onLookupComplete: function (data, next) {
                                                            // use `data` here, then call `next()`
                                                            next();
                                                        }
                                                    }, function (err, payload) {
                                                        if (err) {
                                                            Sentry.captureException(err);
                                                            console.error(err);
                                                            return;
                                                        }

                                                        console.log(payload.liabilityShifted)
                                                        console.log(payload.liabilityShiftPossible)
                                                        console.log(payload)


                                                        var transaction_ok=false

                                                        if (payload.liabilityShifted) {
                                                            transaction_ok=true;
                                                        } else if (
                                                            payload.threeDSecureInfo.status=='authentication_unavailable' ||
                                                            payload.threeDSecureInfo.status=='lookup_bypassed' ||
                                                            payload.threeDSecureInfo.status=='lookup_error' ||
                                                            payload.threeDSecureInfo.status=='lookup_not_enrolled' ||
                                                            payload.threeDSecureInfo.status=='unsupported_card'

                                                        ) {
                                                            transaction_ok=true;
                                                        } else {
                                                            // Liability has not shifted and will not shift
                                                            // Decide if you want to submit the nonce
                                                        }


                                                        if(transaction_ok){




                                                            var register_data={ }
                                                            register_data['nonce']=payload.nonce
                                                            register_data['nonce_for_card_verification']=nonce_for_card_verification
                                                            register_data['token']=$('#BTree_saved_credit_cards_form .credit_card_input_row .card_info').data('token')
                                                            register_data['secure_3d']=true;
                                                            register_data['deviceData']=deviceData;

                                                            var ajaxData = new FormData();

                                                            ajaxData.append("tipo", 'place_order_pay_braintree_using_saved_card')
                                                            ajaxData.append("payment_account_key",BTree_account_key )
                                                            ajaxData.append("data", JSON.stringify(register_data))
                                                            ajaxData.append("order_key",'{$order->id}' )


                                                            $.ajax({
                                                                url: "/ar_web_checkout.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,
                                                                complete: function () {
                                                                }, success: function (data) {




                                                                    if (data.state == '200') {




                                                                        $('.ordered_products_number').html('0')
                                                                        $('.order_total').html('')

                                                                        var d = new Date();
                                                                        var timestamp=d.getTime()
                                                                        d.setTime(timestamp + 300000);
                                                                        var expires = "expires="+ d.toUTCString();
                                                                        document.cookie = "au_pu_"+ data.order_key+"=" + data.order_key + ";" + expires + ";path=/";
                                                                        window.location.replace("thanks.sys?order_key="+data.order_key+'&t='+timestamp);


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
                                                        else{
                                                            var button=$('#place_order_braintree');
                                                            button.removeClass('wait')
                                                            button.find('i').addClass('fa-arrow-right').removeClass('fa-spinner fa-spin')



                                                            var secure_3d_msg='{t}Attempt the transaction again or use other payment method{/t}';
                                                            swal({ title:"{t}3D Secure verification failed{/t}!", text:secure_3d_msg, type:"error", html: true})

                                                            Sentry.addBreadcrumb({

                                                                message: "3D Secure verification failed",
                                                                data: payload
                                                            });
                                                            return;


                                                        }


                                                    });









                                                });
                                            }, false);
                                        });

                                    braintree.hostedFields.create({
                                            client: clientInstance,
                                            styles: {
                                                'input': {
                                                    'font-size': '16px', 'padding': '0px 0px 0px 10px','font-family':'Ubuntu, sans-serif'
                                                },
                                                'input.invalid': {
                                                    'color': 'red'
                                                },
                                                'input.valid': {
                                                    'color': 'green'
                                                }
                                            },
                                            fields: {
                                                number: {
                                                    selector: '#credit_card_number',
                                                    placeholder: '{$data.labels._credit_card_number}'
                                                },
                                                cvv: {
                                                    selector: '#credit_card_ccv',
                                                    placeholder: '{$data.labels._credit_card_ccv}'
                                                },
                                                expirationDate: {
                                                    selector: '#credit_card_valid_until',
                                                    placeholder: '{$data.labels._credit_card_expiration_date_month_label}/{$data.labels._credit_card_expiration_date_year_label}'
                                                }
                                            }
                                        },
                                        function (hostedFieldsErr, hostedFieldsInstance) {
                                            if (hostedFieldsErr) {
                                                Sentry.captureException(hostedFieldsErr);

                                                console.error(hostedFieldsErr);
                                                return;
                                            }

                                            submit.removeAttribute('disabled');

                                            form.addEventListener('submit', function (event) {
                                                event.preventDefault();

                                                var button=$('#place_order_braintree');

                                                if(button.hasClass('wait')){
                                                    return;
                                                }

                                                button.addClass('wait')
                                                button.find('i').removeClass('fa-arrow-right').addClass('fa-spinner fa-spin')





                                                hostedFieldsInstance.tokenize(function (tokenizeErr, payload) {
                                                    if (tokenizeErr) {
                                                        //console.error(tokenizeErr);

                                                        var button=$('#place_order_braintree');
                                                        button.removeClass('wait')
                                                        button.find('i').addClass('fa-arrow-right').removeClass('fa-spinner fa-spin')
                                                        var _msg='';
                                                        switch (tokenizeErr.code) {
                                                            case 'HOSTED_FIELDS_FIELDS_EMPTY':
                                                                 _msg='{t}All fields are empty{/t}';
                                                                break;
                                                            case 'HOSTED_FIELDS_FIELDS_INVALID':
                                                                 _msg='{t}Some payment input fields are invalid{/t}';
                                                                break;
                                                            default:
                                                                 _msg=tokenizeErr.message
                                                                break;
                                                        }

                                                        swal({ title:"{t}Error{/t}!", text:_msg, type:"error", html: true})
                                                        return;
                                                    }





                                                    threeDSecure.verifyCard({
                                                        amount: '{$order->get('Order Basket To Pay Amount')}',
                                                        nonce: payload.nonce,
                                                        bin: payload.details.bin,
                                                        email: '{$customer->get('Customer Main Plain Email')}',
                                                        {if $customer->get('Customer Main Plain Mobile')}
                                                        mobilePhoneNumber: '{$customer->get('Customer Main Plain Mobile')}'.replace(/\D/g,''),
                                                        {/if}

                                                        billingAddress: {
                                                            locality: '{$customer->get('Customer Invoice Address Locality')}',
                                                            {if $customer->get('Customer Invoice Address Postal Code')}
                                                            postalCode: '{$customer->get('Customer Invoice Address Postal Code')}',
                                                            {/if}
                                                            countryCodeAlpha2: '{$customer->get('Customer Invoice Address Country 2 Alpha Code')}'
                                                        },

                                                        onLookupComplete: function (data, next) {
                                                            // use `data` here, then call `next()`
                                                            next();
                                                        }
                                                    }, function (err, payload) {
                                                        if (err) {
                                                            Sentry.captureException(err);

                                                            console.error(err);
                                                            return;
                                                        }

                                                        console.log(payload.liabilityShifted)
                                                        console.log(payload.liabilityShiftPossible)
                                                        console.log(payload)


                                                        var transaction_ok=false

                                                        if (payload.liabilityShifted) {
                                                            transaction_ok=true;
                                                        } else if (
                                                            payload.threeDSecureInfo.status=='authentication_unavailable' ||
                                                            payload.threeDSecureInfo.status=='lookup_bypassed' ||
                                                            payload.threeDSecureInfo.status=='lookup_error' ||
                                                            payload.threeDSecureInfo.status=='lookup_not_enrolled' ||
                                                            payload.threeDSecureInfo.status=='unsupported_card'

                                                        ) {
                                                            transaction_ok=true;
                                                        } else {
                                                            // Liability has not shifted and will not shift
                                                            // Decide if you want to submit the nonce
                                                        }


                                                        if(transaction_ok){




                                                            var register_data={ }
                                                            register_data['nonce']=payload.nonce
                                                            register_data['save_card']=$('#save_card').is(':checked')
                                                            register_data['secure_3d']=true;
                                                            register_data['deviceData']=deviceData;

                                                            var ajaxData = new FormData();

                                                            ajaxData.append("tipo", 'place_order_pay_braintree')
                                                            ajaxData.append("payment_account_key",BTree_account_key )
                                                            ajaxData.append("data", JSON.stringify(register_data))
                                                            ajaxData.append("order_key",'{$order->id}' )


                                                            $.ajax({
                                                                url: "/ar_web_checkout.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,
                                                                complete: function () {
                                                                }, success: function (data) {




                                                                    if (data.state == '200') {
                                                                        $('.ordered_products_number').html('0')
                                                                        $('.order_total').html('')


                                                                        var d = new Date();
                                                                        var timestamp=d.getTime()
                                                                        d.setTime(timestamp + 300000);
                                                                        var expires = "expires="+ d.toUTCString();
                                                                        document.cookie = "au_pu_"+ data.order_key+"=" + data.order_key + ";" + expires + ";path=/";
                                                                        window.location.replace("thanks.sys?order_key="+data.order_key+'&t='+timestamp);


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
                                                        else{
                                                            var button=$('#place_order_braintree');
                                                            button.removeClass('wait')
                                                            button.find('i').addClass('fa-arrow-right').removeClass('fa-spinner fa-spin')



                                                            var secure_3d_msg='{t}Attempt the transaction again or use other payment method{/t}';
                                                            swal({ title:"{t}3D Secure verification failed{/t}!", text:secure_3d_msg, type:"error", html: true})

                                                            Sentry.addBreadcrumb({

                                                                message: "3D Secure verification failed",
                                                                data: payload
                                                            });
                                                            return;


                                                        }


                                                    });









                                                });
                                            }, false);
                                        });

                                    braintree.paypalCheckout.create({
                                            client: clientInstance
                                        },
                                        function (paypalCheckoutErr, paypalCheckoutInstance) {


                                            if (paypalCheckoutErr) {
                                                console.error('Error creating PayPal Checkout:', paypalCheckoutErr);
                                                return;
                                            }





                                            paypal.Button.render({
                                                env: 'production',
                                                commit: true,
                                                payment: function () {
                                                    return paypalCheckoutInstance.createPayment({
                                                        flow: 'checkout', // Required
                                                        amount: '{$order->get('Order Basket To Pay Amount')}', // Required
                                                        currency: '{$order->get('Order Currency')}', // Required
                                                        // Your PayPal options here. For available options, see
                                                        // http://braintree.github.io/braintree-web/current/PayPalCheckout.html#createPayment
                                                    });
                                                },

                                                onAuthorize: function (data, actions) {
                                                    return paypalCheckoutInstance.tokenizePayment(data, function (err, payload) {




                                                        $('#processing_paypal').removeClass('hide')
                                                        $('#paying_paypal').addClass('hide')



                                                        var ajaxData = new FormData();
                                                        ajaxData.append("tipo", 'place_order_pay_braintree_paypal')

                                                        ajaxData.append("payment_account_key",BTreePaypal_account_key )
                                                        ajaxData.append("amount",'{$order->get('Order Basket To Pay Amount')}' )
                                                        ajaxData.append("order_key",'{$order->id}' )
                                                        ajaxData.append("nonce",payload.nonce )

                                                        $.ajax({
                                                                url: "/ar_web_checkout.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,
                                                                complete: function () {
                                                                },
                                                                success: function (data) {




                                                                    if (data.state == '200') {

                                                                        var d = new Date();
                                                                        var timestamp=d.getTime()
                                                                        d.setTime(timestamp + 300000);
                                                                        var expires = "expires="+ d.toUTCString();
                                                                        document.cookie = "au_pu_"+ data.order_key+"=" + data.order_key + ";" + expires + ";path=/";
                                                                        window.location.replace("thanks.sys?order_key="+data.order_key+'&t='+timestamp);

                                                                    } else if (data.state == '400') {

                                                                        $('#processing_paypal').addClass('hide')
                                                                        $('#paying_paypal').addClass('hide')
                                                                        $('#paypal-button').removeClass('hide')

                                                                        swal({ title:"{t}Error{/t}!", text:data.msg, type:"error", html: true}
                                                                        )

                                                                    }



                                                                }

                                                            }
                                                        );




                                                    });
                                                },

                                                onCancel: function (data) {


                                                    $('#processing_paypal').addClass('hide')
                                                    $('#paying_paypal').addClass('hide')
                                                    $('#paypal-button').removeClass('hide')

                                                    console.log('checkout.js payment cancelled', JSON.stringify(data, 0, 2));
                                                },

                                                onError: function (err) {

                                                    $('#processing_paypal').addClass('hide')
                                                    $('#paying_paypal').addClass('hide')


                                                    $('#paypal-button').removeClass('hide')

                                                    console.error('checkout.js error', err);
                                                },


                                                onClick: function (err) {
                                                    $('#processing_paypal').removeClass('hide')
                                                    $('#paypal-button').addClass('hide')
                                                }


                                            }, '#paypal-button').then(function () {



                                                $('#loading_paypal-button').addClass('hide')
                                                $('#paypal-button').removeClass('hide')
                                                // The PayPal button will be rendered in an html element with the id
                                                // `paypal-button`. This function will be called when the PayPal button
                                                // is set up and ready to be used.
                                            });

                                        });


                                });


                        </script>
                    {else}
                    <script>



                        braintree.client.create({
                                authorization: '{$braintree_data['client_token']}'
                            },
                            function (clientErr, clientInstance) {
                                if (clientErr) {
                                    console.error(clientErr);
                                    return;
                                }

                                braintree.hostedFields.create({
                                        client: clientInstance,
                                        styles: {
                                            'input': {
                                                'font-size': '16px', 'padding': '0px 0px 0px 10px','font-family':'Ubuntu, sans-serif'
                                            },
                                            'input.invalid': {
                                                'color': 'red'
                                            },
                                            'input.valid': {
                                                'color': 'green'
                                            }
                                        },
                                        fields: {

                                            cvv: {
                                                selector: '#saved_credit_card_ccv',
                                                placeholder: '{$data.labels._credit_card_ccv}'
                                            },

                                        }
                                    },
                                    function (hostedFieldsErr, hostedFieldsInstance) {
                                        if (hostedFieldsErr) {
                                            console.error(hostedFieldsErr);
                                            return;
                                        }


                                        hostedFieldsInstance.on('validityChange', function (event) {


                                            console.log(event.fields.cvv)

                                            if(event.fields.cvv.isValid){
                                                $('#place_order_saved_card_braintree').removeClass('state-disabled')

                                            }else{
                                                $('#place_order_saved_card_braintree').addClass('state-disabled')

                                            }

                                        })


                                        saved_card_submit.removeAttribute('disabled');

                                        saved_card_form.addEventListener('submit', function (event) {
                                            event.preventDefault();

                                            var button=$('#place_order_saved_card_braintree');

                                            if(button.hasClass('wait')){
                                                return;
                                            }

                                            button.addClass('wait')
                                            button.find('i').removeClass('fa-arrow-right').addClass('fa-spinner fa-spin')


                                            hostedFieldsInstance.tokenize(function (tokenizeErr, payload) {




                                                if (tokenizeErr) {


                                                    var button=$('#place_order_saved_card_braintree');
                                                    button.removeClass('wait')
                                                    button.find('i').addClass('fa-arrow-right').removeClass('fa-spinner fa-spin')

                                                    if($('#BTree_saved_credit_cards_form .credit_card_input_row').hasClass('hide')){
                                                        swal({ title:"{t}Error{/t}!", text:'{t}Choose a saved credit card or click in pay with other card{/t}', type:"error", html: true})
                                                    }else{

                                                        switch (tokenizeErr.code) {
                                                            case 'HOSTED_FIELDS_FIELDS_EMPTY':
                                                                var _msg='{t}Please fill the CVV field{/t}';
                                                                break;
                                                            case 'HOSTED_FIELDS_FIELDS_INVALID':
                                                                var _msg='{t}CVV is invalid{/t}';
                                                                break;
                                                            default:
                                                                var _msg=tokenizeErr.message
                                                                break;
                                                        }

                                                        swal({ title:"{t}Error{/t}!", text:_msg, type:"error", html: true})


                                                    }


                                                    //console.error(tokenizeErr);
                                                    return;
                                                }


                                                var register_data={ }
                                                register_data['nonce']=payload.nonce
                                                register_data['token']=$('#BTree_saved_credit_cards_form .credit_card_input_row .card_info').data('token')

                                                //console.log($('#BTree_saved_credit_cards_form .credit_card_input_row .card_info').data('token'))



                                                var ajaxData = new FormData();

                                                ajaxData.append("tipo", 'place_order_pay_braintree_using_saved_card')
                                                ajaxData.append("payment_account_key",BTree_account_key )
                                                ajaxData.append("data", JSON.stringify(register_data))
                                                ajaxData.append("order_key",'{$order->id}' )


                                                $.ajax({
                                                    url: "/ar_web_checkout.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,
                                                    complete: function () {
                                                    }, success: function (data) {




                                                        if (data.state == '200') {




                                                            $('.ordered_products_number').html('0')
                                                            $('.order_total').html('')

                                                            var d = new Date();
                                                            var timestamp=d.getTime()
                                                            d.setTime(timestamp + 300000);
                                                            var expires = "expires="+ d.toUTCString();
                                                            document.cookie = "au_pu_"+ data.order_key+"=" + data.order_key + ";" + expires + ";path=/";
                                                            window.location.replace("thanks.sys?order_key="+data.order_key+'&t='+timestamp);


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



                                            });
                                        }, false);
                                    });

                                braintree.hostedFields.create({
                                        client: clientInstance,
                                        styles: {
                                            'input': {
                                                'font-size': '16px', 'padding': '0px 0px 0px 10px','font-family':'Ubuntu, sans-serif'
                                            },
                                            'input.invalid': {
                                                'color': 'red'
                                            },
                                            'input.valid': {
                                                'color': 'green'
                                            }
                                        },
                                        fields: {
                                            number: {
                                                selector: '#credit_card_number',
                                                placeholder: '{$data.labels._credit_card_number}'
                                            },
                                            cvv: {
                                                selector: '#credit_card_ccv',
                                                placeholder: '{$data.labels._credit_card_ccv}'
                                            },
                                            expirationDate: {
                                                selector: '#credit_card_valid_until',
                                                placeholder: '{$data.labels._credit_card_expiration_date_month_label}/{$data.labels._credit_card_expiration_date_year_label}'
                                            }
                                        }
                                    },
                                    function (hostedFieldsErr, hostedFieldsInstance) {
                                        if (hostedFieldsErr) {
                                            console.error(hostedFieldsErr);
                                            return;
                                        }

                                        submit.removeAttribute('disabled');

                                        form.addEventListener('submit', function (event) {
                                            event.preventDefault();

                                            var button=$('#place_order_braintree');

                                            if(button.hasClass('wait')){
                                                return;
                                            }

                                            button.addClass('wait')
                                            button.find('i').removeClass('fa-arrow-right').addClass('fa-spinner fa-spin')


                                            hostedFieldsInstance.tokenize(function (tokenizeErr, payload) {
                                                if (tokenizeErr) {
                                                    //console.error(tokenizeErr);

                                                    var button=$('#place_order_braintree');
                                                    button.removeClass('wait')
                                                    button.find('i').addClass('fa-arrow-right').removeClass('fa-spinner fa-spin')

                                                    switch (tokenizeErr.code) {
                                                        case 'HOSTED_FIELDS_FIELDS_EMPTY':
                                                            var _msg='{t}All fields are empty{/t}';
                                                            break;
                                                        case 'HOSTED_FIELDS_FIELDS_INVALID':
                                                            var _msg='{t}Some payment input fields are invalid{/t}';
                                                            break;
                                                        default:
                                                            var _msg=tokenizeErr.message
                                                            break;
                                                    }

                                                    swal({ title:"{t}Error{/t}!", text:_msg, type:"error", html: true})
                                                    return;
                                                }


                                                var register_data={ }
                                                register_data['nonce']=payload.nonce
                                                register_data['save_card']=$('#save_card').is(':checked')


                                                var ajaxData = new FormData();

                                                ajaxData.append("tipo", 'place_order_pay_braintree')
                                                ajaxData.append("payment_account_key",BTree_account_key )
                                                ajaxData.append("data", JSON.stringify(register_data))
                                                ajaxData.append("order_key",'{$order->id}' )


                                                $.ajax({
                                                    url: "/ar_web_checkout.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,
                                                    complete: function () {
                                                    }, success: function (data) {




                                                        if (data.state == '200') {
                                                            $('.ordered_products_number').html('0')
                                                            $('.order_total').html('')


                                                            var d = new Date();
                                                            var timestamp=d.getTime()
                                                            d.setTime(timestamp + 300000);
                                                            var expires = "expires="+ d.toUTCString();
                                                            document.cookie = "au_pu_"+ data.order_key+"=" + data.order_key + ";" + expires + ";path=/";
                                                            window.location.replace("thanks.sys?order_key="+data.order_key+'&t='+timestamp);


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



                                            });
                                        }, false);
                                    });

                                braintree.paypalCheckout.create({
                                    client: clientInstance
                                },
                                    function (paypalCheckoutErr, paypalCheckoutInstance) {


                                    if (paypalCheckoutErr) {
                                        console.error('Error creating PayPal Checkout:', paypalCheckoutErr);
                                        return;
                                    }





                                    paypal.Button.render({
                                        env: 'production', // or 'production'
                                        commit: true,
                                        payment: function () {
                                            return paypalCheckoutInstance.createPayment({
                                                flow: 'checkout', // Required
                                                amount: '{$order->get('Order Basket To Pay Amount')}', // Required
                                                currency: '{$order->get('Order Currency')}', // Required
                                                // Your PayPal options here. For available options, see
                                                // http://braintree.github.io/braintree-web/current/PayPalCheckout.html#createPayment
                                            });
                                        },

                                        onAuthorize: function (data, actions) {
                                            return paypalCheckoutInstance.tokenizePayment(data, function (err, payload) {




                                                $('#processing_paypal').removeClass('hide')
                                                $('#paying_paypal').addClass('hide')



                                                var ajaxData = new FormData();
                                                ajaxData.append("tipo", 'place_order_pay_braintree_paypal')

                                                ajaxData.append("payment_account_key",BTreePaypal_account_key )
                                                ajaxData.append("amount",'{$order->get('Order Basket To Pay Amount')}' )
                                                ajaxData.append("order_key",'{$order->id}' )
                                                ajaxData.append("nonce",payload.nonce )

                                                $.ajax({
                                                        url: "/ar_web_checkout.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,
                                                        complete: function () {
                                                        },
                                                        success: function (data) {




                                                            if (data.state == '200') {

                                                                var d = new Date();
                                                                var timestamp=d.getTime()
                                                                d.setTime(timestamp + 300000);
                                                                var expires = "expires="+ d.toUTCString();
                                                                document.cookie = "au_pu_"+ data.order_key+"=" + data.order_key + ";" + expires + ";path=/";
                                                                window.location.replace("thanks.sys?order_key="+data.order_key+'&t='+timestamp);

                                                            } else if (data.state == '400') {

                                                                $('#processing_paypal').addClass('hide')
                                                                $('#paying_paypal').addClass('hide')
                                                                $('#paypal-button').removeClass('hide')

                                                                swal({ title:"{t}Error{/t}!", text:data.msg, type:"error", html: true}
                                                                )

                                                            }



                                                        }

                                                    }
                                                );




                                            });
                                        },

                                        onCancel: function (data) {


                                            $('#processing_paypal').addClass('hide')
                                            $('#paying_paypal').addClass('hide')
                                            $('#paypal-button').removeClass('hide')

                                            console.log('checkout.js payment cancelled', JSON.stringify(data, 0, 2));
                                        },

                                        onError: function (err) {

                                            $('#processing_paypal').addClass('hide')
                                            $('#paying_paypal').addClass('hide')


                                            $('#paypal-button').removeClass('hide')

                                            console.error('checkout.js error', err);
                                        },


                                        onClick: function (err) {
                                            $('#processing_paypal').removeClass('hide')
                                            $('#paypal-button').addClass('hide')
                                        }


                                    }, '#paypal-button').then(function () {



                                        $('#loading_paypal-button').addClass('hide')
                                        $('#paypal-button').removeClass('hide')
                                        // The PayPal button will be rendered in an html element with the id
                                        // `paypal-button`. This function will be called when the PayPal button
                                        // is set up and ready to be used.
                                    });

                                });


                            });


                    </script>
                    {/if}
                    {/if}


                    </div><!-- end tab 3 -->


                </div>
    {/if}
    <div class="clear"> </div>
</div>

<script>


    $( ".payment_option_chooser" ).click(function() {

        ga('auTracker.ec:setAction', 'checkout_option', {
            'step': 2,
            'option': $(this).data('analytics_label')
        });

    });

    ga('auTracker.send', 'event', 'Order', 'checkout');



    {foreach from=$order->get_items() item="item" }
        ga('auTracker.ec:addProduct',{$item.analytics_data} );
    {/foreach}
    ga('auTracker.ec:setAction','checkout', {
        'step': 2,
    });
    ga('auTracker.send', 'pageview');


    if($('#show_error').data('show')=='yes' && '{$error_msg}'!=='' ){
        swal({ title:"{t}Payment error{/t}!", text:'{$error_msg}', type:"error", html: true})
    }



</script>
