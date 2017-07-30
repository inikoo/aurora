{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 30 May 2017 at 10:05:39 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}



{include file="theme_1/_head.theme_1.tpl"}


<body>

<div class="wrapper_boxed">

    <div class="site_wrapper">

        <div class="content_fullwidth less2">


            <div class="container">

                <h2><span id="_order_number_label" class="website_localized_label" contenteditable="true">{if isset($labels._order_number_label) and $labels._order_number_label!=''}{$labels._order_number_label}{else}{t}Order number{/t}{/if}</span> <span class="order_number">342342</span>
                </h2>


                <div class="one_third">
                    <h5>
                        <i class="fa fa-fw fa-truck" aria-hidden="true"></i>
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


                <div class="one_third ">

                    <h5>
                        <i class="fa fa-fw fa-usd" aria-hidden="true"></i>
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

                <div class="one_third text-right last" style="padding-left:20px">


                    <table class="table">

                        <tbody>

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


            <div class="clearfix "></div>


            <div class="container">

                <ul class="tabs3">
                    <li><a href="#example-3-tab-1" target="_self"><i class="fa fa-credit-card" aria-hidden="true"></i> <span contenteditable="true" id="_credit_card_label">{$content._credit_card_label}</span></a></li>
                    <li><a href="#example-3-tab-2" target="_self"><i class="fa fa-paypal" aria-hidden="true"></i>&nbsp; Paypal</a></li>
                    <li><a href="#example-3-tab-3" target="_self"><i class="fa fa-university" aria-hidden="true"></i>&nbsp; <span contenteditable="true" id="_bank_label">{{$content._bank_label}}</span></a></li>
                </ul>

                <div class="tabs-content3 two">

                    <div id="example-3-tab-1" class="tabs-panel3">


                        <form action="" class="sky-form" style="max-width: 500px;">
                            <header id="_form_title_credit_card" contenteditable="true">{$content._form_title_credit_card}</header>


                            <fieldset>


                                <div class="row">
                                    <section class="col col-9">
                                        <label class="input">
                                            <input type="text" id="_credit_card_number" style="color:lightgrey" value="{$content._credit_card_number}">
                                        </label>
                                    </section>
                                    <section class="col col-3">
                                        <label class="input">
                                            <input type="text" maxlength="4" id="_credit_card_ccv" style="color:lightgrey" value="{$content._credit_card_ccv}">
                                        </label>
                                    </section>
                                </div>

                                <div class="row">
                                    <label class="label col col-4" id="_credit_card_expiration_date" contenteditable="true">{$content._credit_card_expiration_date}</label>
                                    <section class="col col-5">
                                        <label class="input">
                                            <input type="text" id="_credit_card_expiration_date_month_label" style="color:lightgrey" value="{$content._credit_card_expiration_date_month_label}">
                                        </label>
                                    </section>
                                    <section class="col col-3">
                                        <label class="input">
                                            <input type="text" maxlength="4" id="_credit_card_expiration_date_year_label" style="color:lightgrey" value="{$content._credit_card_expiration_date_year_label}">
                                        </label>
                                    </section>
                                </div>


                                <div class="row">

                                    <section class="col col-5">
                                        <label class="checkbox"><input type="checkbox" name="subscription" id="subscription"><i></i> </label>
                                        <span style="margin-left:27px;	font: 14px/1.55 'Open Sans', Helvetica, Arial, sans-serif;position:relative;top:-22px;font-size:15px;color:#404040" id="_credit_card_save"
                                              contenteditable="true">{$content._credit_card_save}</span>

                                    </section>


                                </div>


                            </fieldset>

                            <footer>
                                <button class="button" id="_place_order_from_credit_card" contenteditable="true">{$content._place_order_from_credit_card}</button>
                            </footer>
                        </form>


                    </div><!-- end tab 1 -->

                    <div id="example-3-tab-2" class="tabs-panel3">

                        <form action="" class="sky-form" style="max-width: 500px;">
                            <header id="_form_title_bank" contenteditable="true">{$content._form_title_paypal}</header>


                            <fieldset>


                                <img src="/art/paypal_mockup_button.png">

                            </fieldset>

                            <footer>

                            </footer>
                        </form>

                    </div><!-- end tab 2 -->

                    <div id="example-3-tab-3" class="tabs-panel3">


                        <form action="" class="sky-form" style="max-width: 500px;">
                            <header id="_form_title_bank" contenteditable="true">{$content._form_title_bank}</header>


                            <div style="padding:20px">
                                <p id="_bank_header" contenteditable="true">{$content._bank_header}</p>


                                </h2>


                                <br>
                                <span id="_bank_beneficiary_label" class="website_localized_label" contenteditable="true">{if isset($labels._bank_beneficiary_label) and $labels._bank_beneficiary_label!=''}{$labels._bank_beneficiary_label}{else}{t}Beneficiary{/t}{/if}</span>: XXX<br/>
                                <span id="_bank_account_number_label" class="website_localized_label" contenteditable="true">{if isset($labels._bank_account_number_label) and $labels._bank_account_number_label!=''}{$labels._bank_account_number_label}{else}{t}Account Number{/t}{/if}</span>: XXX<br/>
                                <span>IBAN</span>: XXX<br/>


                                <span id="_bank_name_label" class="website_localized_label" contenteditable="true">{if isset($labels.website_localized_label) and $labels.website_localized_label!=''}{$labels.website_localized_label}{else}{t}Bank{/t}{/if}</span>: <b>XXX</b><br/>
                                <span id="_bank_sort_code" class="website_localized_label" contenteditable="true">{if isset($labels._bank_sort_code) and $labels._bank_sort_code!=''}{$labels._bank_sort_code}{else}{t}Bank Code{/t}{/if}</span>: XXX<br/>
                                <span>Swift</span>: XXX<br/>
                                <span id="_bank_address_label" class="website_localized_label" contenteditable="true">{if isset($labels._bank_address_label) and $labels._bank_address_label!=''}{$labels._bank_address_label}{else}{t}Address{/t}{/if}</span>: XXX<br/>

                                </span>

                                <br>
                                <p id="_bank_footer" contenteditable="true">{$content._bank_footer}</p>
                            </div>


                            <footer>
                                <button class="button" id="_place_order_from_bank" contenteditable="true">{$content._place_order_from_bank}</button>
                            </footer>
                        </form>


                    </div><!-- end tab 3 -->



                </div><!-- end all tabs -->


            </div>


            <div class="clearfix marb12"></div>

        </div>
    </div>

    <script>

        $('[contenteditable=true]').on('input paste', function (event) {
            $('#save_button', window.parent.document).addClass('save button changed valid')
        });


        $(document).delegate('a', 'click', function (e) {

            return false
        })


        $("form").on('submit', function (e) {
            e.preventDefault();
            e.returnValue = false;
        });


        function save() {

            if (!$('#save_button', window.parent.document).hasClass('save')) {
                return;
            }

            $('#save_button', window.parent.document).find('i').addClass('fa-spinner fa-spin')


            var content_data = { }
            var labels= { };

                $('[contenteditable=true]').each(function (i, obj) {

                    if($(obj).hasClass('website_localized_label')){
                        labels[$(obj).attr('id')] = $(obj).html()
                    }else{
                        content_data[$(obj).attr('id')] = $(obj).html()
                    }



                })


            content_data['_credit_card_number'] = $('#_credit_card_number').val()
            content_data['_credit_card_ccv'] = $('#_credit_card_ccv').val()
            content_data['_credit_card_expiration_date_month_label'] = $('#_credit_card_expiration_date_month_label').val()
            content_data['_credit_card_expiration_date_year_label'] = $('#_credit_card_expiration_date_year_label').val()


            var ajaxData = new FormData();

            ajaxData.append("tipo", 'save_webpage_content')
            ajaxData.append("key", '{$webpage->id}')
            ajaxData.append("content_data", JSON.stringify(content_data))
            ajaxData.append("labels", JSON.stringify(labels))


            $.ajax({
                url: "/ar_edit_website.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false, complete: function () {
                }, success: function (data) {

                    if (data.state == '200') {

                        $('#save_button', window.parent.document).removeClass('save').find('i').removeClass('fa-spinner fa-spin')

                    } else if (data.state == '400') {
                        swal({
                            title: data.title, text: data.msg, confirmButtonText: "OK"
                        });
                    }


                }, error: function () {

                }
            });


        }


    </script>


</body>

</html>


