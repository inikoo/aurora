{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 13 April 2018 at 21:46:49 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}

{if isset($data.top_margin)}{assign "top_margin" $data.top_margin}{else}{assign "top_margin" "20"}{/if}
{if isset($data.bottom_margin)}{assign "bottom_margin" $data.bottom_margin}{else}{assign "bottom_margin" "20"}{/if}


<div id="block_{$key}" data-block_key="{$key}" block="{$data.type}" class="{$data.type} _block {if !$data.show}hide{/if}" top_margin="{$top_margin}" bottom_margin="{$bottom_margin}"
     style="padding-top:{$top_margin}px;padding-bottom:{$bottom_margin}px">


    <div class="container">

        <h2><span id="_order_number_label" class="website_localized_label" contenteditable="true">{if isset($labels._order_number_label) and $labels._order_number_label!=''}{$labels._order_number_label}{else}{t}Order number{/t}{/if}</span> <span class="order_number">342342</span></h2>

        <div class="order_header  text_blocks  text_template_3">
        <div class="text_block">
            <h5>
                <i class="fal fa-fw fa-clipboard" aria-hidden="true"></i>
                <span id="_delivery_address_label" class="website_localized_label"
                      contenteditable="true">{if isset($labels._delivery_address_label) and $labels._delivery_address_label!=''}{$labels._delivery_address_label}{else}{t}Delivery Address{/t}{/if}</span>
            </h5>
            <p>
                The Business Centre </br>
                61 Wellfield Road</br>
                Roath</br>
                Cardiff</br>
                CF24 3DG</br>
                United Kingdom</br>
            </p>
        </div>


        <div class="text_block ">

            <h5>
                <i class="fa fa-fw fa-dollar-sign" aria-hidden="true"></i>
                <span id="_invoice_address_label" class="website_localized_label"
                      contenteditable="true">{if isset($labels._invoice_address_label) and $labels._invoice_address_label!=''}{$labels._invoice_address_label}{else}{t}Invoice Address{/t}{/if}</span>
            </h5>
            <p>
                The Business Centre</br>
                61 Wellfield Road</br>
                Roath</br>
                Cardiff</br>
                CF24 3DG </br>
                United Kingdom</br>
            </p>
        </div>

        <div class="totals text_block" >


            <table class="table">

                <tbody>
                <tr>
                    <td  id="_items_net"  class="website_localized_label" contenteditable="true" >{if isset($labels._items_net) and $labels._items_net!=''}{$labels._items_net}{else}{t}Items Net{/t}{/if}</td>

                    <td class="text-right">£268.32</td>
                </tr>
                <tr>
                    <td id="_items_charges" class="website_localized_label"
                        contenteditable="true">{if isset($labels._items_charges) and $labels._items_charges!=''}{$labels._items_charges}{else}{t}Charges{/t}{/if}</td>


                    <td class="text-right">£0.00</td>
                </tr>
                <tr>
                    <td id="_items_shipping" class="website_localized_label"
                        contenteditable="true">{if isset($labels._items_shipping) and $labels._items_shipping!=''}{$labels._items_shipping}{else}{t}Shipping{/t}{/if}</td>


                    <td class="text-right">£0.00</td>
                </tr>
                <tr>
                    <td id="_total_net" class="website_localized_label" contenteditable="true">{if isset($labels._total_net) and $labels._total_net!=''}{$labels._total_net}{else}{t}Net{/t}{/if}</td>


                    <td class="text-right">£268.32</td>
                </tr>
                <tr>
                    <td id="_total_tax" class="website_localized_label" contenteditable="true">{if isset($labels._total_tax) and $labels._total_tax!=''}{$labels._total_tax}{else}{t}Tax{/t}{/if}</td>


                    <td class="text-right">£53.66</td>
                </tr>
                <tr>
                    <td id="_total" class="website_localized_label" contenteditable="true">{if isset($labels._total) and $labels._total!=''}{$labels._total}{else}{t}Total{/t}{/if}</td>


                    <td class="text-right">£321.98</td>
                </tr>
                <tr>

                    <td id="_credit" class="website_localized_label" contenteditable="true">{if isset($labels._credit) and $labels._credit!=''}{$labels._credit}{else}{t}Credit{/t}{/if}</td>


                    <td class="text-right">-£20</td>
                </tr>
                <tr>
                    <td id="_total_to_pay" class="website_localized_label"
                        contenteditable="true">{if isset($labels._total_to_pay) and $labels._total_to_pay!=''}{$labels._total_to_pay}{else}{t}To pay{/t}{/if}</td>


                    <td class="text-right">£301.98</td>
                </tr>

                </tbody>
            </table>

        </div>

    </div>




        <div class="clear" style="margin-bottom: 30px"> </div>

    <div class="container clear">

        <ul class="tabs3">
            <li><a href="#example-3-tab-1" target="_self"><i class="fa fa-credit-card" aria-hidden="true"></i> <span contenteditable="true" id="_credit_card_label">{if empty($data.labels._credit_card_label) }{t}Credit card{/t}{else}{$data.labels._credit_card_label}{/if}</span></a></li>
            <li><a href="#example-3-tab-2" target="_self"><i class="fab fa-paypal" aria-hidden="true"></i>&nbsp; Paypal</a></li>
            <li><a href="#example-3-tab-3" target="_self"><i class="fa fa-university" aria-hidden="true"></i>&nbsp; <span contenteditable="true" id="_bank_label">{if empty($data.labels._bank_label) }{t}Bank{/t}{else}{$data.labels._bank_label}{/if}</span></a></li>
            <li><a href="#example-3-tab-4" target="_self"><i class="fa fa-handshake" aria-hidden="true"></i>&nbsp; <span contenteditable="true" id="_cash_on_delivery_label">{if empty($data.labels._cash_on_delivery_label) }{t}Cash on delivery{/t}{else}{$data.labels._cash_on_delivery_label}{/if}</span></a></li>
            <li ><a href="#example-3-tab-5" target="_self"><i class="fa fa-hand-peace" aria-hidden="true"></i> <span >Sofort</span></a></li>

        </ul>

        <div class="tabs-content3 two">

            <div id="example-3-tab-1" class="tabs-panel3">


                <form action="" class="sky-form" style="max-width: 500px;">
                    <header id="_form_title_credit_card" contenteditable="true">{$data.labels._form_title_credit_card}</header>


                    <fieldset>


                        <div class="row">
                            <section class="col col-9">
                                <label class="input">
                                    <input type="text" id="_credit_card_number" style="color:lightgrey" value="{$data.labels._credit_card_number}">
                                </label>
                            </section>
                            <section class="col col-3">
                                <label class="input">
                                    <input type="text" maxlength="4" id="_credit_card_ccv" style="color:lightgrey" value="{$data.labels._credit_card_ccv}">
                                </label>
                            </section>
                        </div>

                        <div class="row">
                            <label class="label col col-4" id="_credit_card_expiration_date" contenteditable="true">{$data.labels._credit_card_expiration_date}</label>
                            <section class="col col-5">
                                <label class="input">
                                    <input type="text" id="_credit_card_expiration_date_month_label" style="color:lightgrey" value="{$data.labels._credit_card_expiration_date_month_label}">
                                </label>
                            </section>
                            <section class="col col-3">
                                <label class="input">
                                    <input type="text" maxlength="4" id="_credit_card_expiration_date_year_label" style="color:lightgrey" value="{$data.labels._credit_card_expiration_date_year_label}">
                                </label>
                            </section>
                        </div>


                        <div class="row">

                            <section class="col col-5">
                                <label class="checkbox"><input type="checkbox" name="subscription" id="subscription"><i></i> </label>
                                <span style="margin-left:27px;	font: 14px/1.55 'Open Sans', Helvetica, Arial, sans-serif;position:relative;top:-22px;font-size:15px;color:#404040" id="_credit_card_save"
                                      contenteditable="true">{$data.labels._credit_card_save}</span>

                            </section>


                        </div>


                    </fieldset>

                    <footer>
                        <button class="button"><span  id="_place_order_from_credit_card" contenteditable="true">{if empty($data.labels._place_order_from_credit_card) } &nbsp;{else}{$data.labels._place_order_from_credit_card}{/if}</span></button>
                    </footer>
                </form>


            </div><!-- end tab 1 -->

            <div id="example-3-tab-2" class="tabs-panel3">

                <form action="" class="sky-form" style="max-width: 500px;">
                    <header id="_form_title_paypal" contenteditable="true">{if isset($data.labels._form_title_paypal) }{$data.labels._form_title_paypal}{else}{t}Checkout form{/t}{/if}</header>


                    <fieldset>


                        <img src="/art/paypal_mockup_button.png">

                    </fieldset>

                    <footer>

                    </footer>
                </form>

            </div><!-- end tab 2 -->

            <div id="example-3-tab-3" class="tabs-panel3">


                <form action="" class="sky-form" style="max-width: 500px;">
                    <header id="_form_title_bank" contenteditable="true">{$data.labels._form_title_bank}</header>


                    <div style="padding:20px">
                        <p id="_bank_header" contenteditable="true">{$data.labels._bank_header}</p>
                        <br>
                        <span id="_bank_beneficiary_label" class="website_localized_label" contenteditable="true">{if isset($labels._bank_beneficiary_label) and $labels._bank_beneficiary_label!=''}{$labels._bank_beneficiary_label}{else}{t}Beneficiary{/t}{/if}</span>: XXX<br/>
                        <span id="_bank_account_number_label" class="website_localized_label" contenteditable="true">{if isset($labels._bank_account_number_label) and $labels._bank_account_number_label!=''}{$labels._bank_account_number_label}{else}{t}Account Number{/t}{/if}</span>: XXX<br/>
                        <span>IBAN</span>: XXX<br/>


                        <span id="_bank_name_label" class="website_localized_label" contenteditable="true">{if isset($labels.website_localized_label) and $labels.website_localized_label!=''}{$labels.website_localized_label}{else}{t}Bank{/t}{/if}</span>: <b>XXX</b><br/>
                        <span id="_bank_sort_code" class="website_localized_label" contenteditable="true">{if isset($labels._bank_sort_code) and $labels._bank_sort_code!=''}{$labels._bank_sort_code}{else}{t}Bank Code{/t}{/if}</span>: XXX<br/>
                        <span>Swift</span>: XXX<br/>
                        <span id="_bank_address_label" class="website_localized_label" contenteditable="true">{if isset($labels._bank_address_label) and $labels._bank_address_label!=''}{$labels._bank_address_label}{else}{t}Address{/t}{/if}</span>: XXX<br/>
                        <br>
                        <p id="_bank_footer" contenteditable="true">{$data.labels._bank_footer}</p>
                    </div>


                    <footer>
                        <button class="button" id="_place_order_from_bank" contenteditable="true">{$data.labels._place_order_from_bank}</button>
                    </footer>
                </form>


            </div><!-- end tab 3 -->


            <div id="example-3-tab-4" class="tabs-panel3">


                <form action="" class="sky-form" style="max-width: 500px;">
                    <header id="_form_title_cash_on_delivery" contenteditable="true">{if !empty($data.labels._form_title_cash_on_delivery)}{{$data.labels._form_title_cash_on_delivery}}{else}Checkout form{/if}</header>


                    <div style="padding:20px">
                        <p id="_cash_on_delivery_text" contenteditable="true">{if !empty($data.labels._cash_on_delivery_text)}{{$data.labels._cash_on_delivery_text}}{else}Pay on delivery{/if}</p>

                    </div>


                    <footer>
                        <button class="button" id="_place_order_from_cash_on_delivery" contenteditable="true">{if !empty($data.labels._place_order_from_cash_on_delivery)}{{$data.labels._place_order_from_cash_on_delivery}}{else}Place Order{/if}</button>
                    </footer>
                </form>


            </div>

            <div id="example-3-tab-5" class="tabs-panel3">


                <form action="" class="sky-form" style="max-width: 500px;">
                    <header id="_form_title_online_bank_transfer" contenteditable="true">{if !empty($data.labels._form_title_online_bank_transfer)}{{$data.labels._form_title_online_bank_transfer}}{else}Checkout form{/if}</header>


                    <div style="padding:20px">
                        <p id="_online_bank_transfer_text" contenteditable="true" >{if !empty($data.labels._online_bank_transfer_text)}{{$data.labels._online_bank_transfer_text}}{else}Pay with Sofort{/if}</p>

                    </div>


                    <footer>
                        <button class="button" id="_place_order_from_online_bank_transfer" contenteditable="true">{if !empty($data.labels._place_order_from_online_bank_transfer)}{{$data.labels._place_order_from_online_bank_transfer}}{else}Place Order{/if}</button>
                    </footer>
                </form>


            </div>


        </div><!-- end all tabs -->


    </div>


        <div class="clear"> </div>
</div>

<script>
    function jQueryTabs3() {
        $(".tabs3").each(function () {
            $(".tabs-panel3").not(":first").hide(), $("li", this).removeClass("active"), $("li:first-child", this).addClass("active"), $(".tabs-panel:first-child").show(), $("li", this).on('click',function (t){
                var i=$("a",this).attr("href");
                $(this).siblings().removeClass("active"),$(this).addClass("active"),$(i).siblings().hide(),$(i).fadeIn(400),t.preventDefault()}), $(window).width() < 100 && $(".tabs-panel3").show()
        })
    }
    jQueryTabs3();
    $(".tabs3 li a").each(function(){
        var t=$(this).attr("href"),i=$(this).html();$(t+" .tab-title3").prepend("<p><strong>"+i+"</strong></p>")})
    $(window).resize(function (){
        jQueryTabs3()

    })
</script>

