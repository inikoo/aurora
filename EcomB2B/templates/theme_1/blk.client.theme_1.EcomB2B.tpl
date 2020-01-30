{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 31 July 2017 at 10:41:58 CEST, 
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}

<style>
.client_showcase{
    display:flex;
    border-bottom:1px solid #ccc;
    padding:5px 15px

}

    .client_name{
        font-weight:400;
        color:#555
    }

</style>

{if isset($data.top_margin)}{assign "top_margin" $data.top_margin}{else}{assign "top_margin" "0"}{/if}

{if isset($data.bottom_margin)}{assign "bottom_margin" $data.bottom_margin}{else}{assign "bottom_margin" "0"}{/if}


<div id="block_{$key}" data-block_key="{$key}"  data-client_key="{$customer_client->id}"  block="{$data.type}" class="{$data.type}  {if !$data.show}hide{/if}" style="padding-bottom:{$bottom_margin}px">


    <div class="client_showcase" style="margin-bottom:{$top_margin}px">
            <div>
                <span class="client_name">{$customer_client->get('Customer Client Name')}</span>
            </div>

    </div>



    <div class="text_blocks container"   >

        <div style="width: 250px" id="client_menu" class="text_block ">

            <h4>{if !empty($data.labels._customer_client_title)}{$data.labels._customer_client_title}{else}{t}Customer details{/t}{/if}</h4>
            <ul class="clean">
                <li>
                 <span data-block="_contact_details" onClick="change_block(this)" class="block_link  like_button  selected" style="cursor: pointer">
                                    <i class="fa fa-angle-right"></i>
                                    <span>{if !empty($data.labels.menu_contact_details_title)}{$data.labels.menu_contact_details_title}{else}{t}Customer details{/t}{/if}</span>
                                </span>
                </li>


                <li>
                    <span data-block="_delivery_addresses_details" onClick="change_block(this)" class="block_link like_button " style="cursor: pointer">
                                    <i class="fa fa-angle-right"></i>
                                    <span>{if !empty($data.labels.menu_delivery_addresses_title)}{$data.labels.menu_delivery_addresses_title}{else}{t}Delivery address{/t}{/if}</span>

                                    </span>
                </li>




            </ul>
                <div>

                    <h4>{if !empty($data.labels._customer_orders_title)}{$data.labels._customer_orders_title}{else}{t}Customer's orders{/t}{/if}</h4>

                <ul class="clean">
                    <li>


                        <span data-block="_orders_details" onClick="change_block(this)" class="block_link like_button " style="cursor: pointer">
                                    <i class="fa fa-angle-right"></i>
                                    <span class="_orders_address_title">{if empty($data.labels._orders_title)}{t}Orders{/t}{else}{$data.labels._orders_title}{/if}</span>
                                    </span>

                    </li>


                </ul>
            </div>


        </div>

        <div style="width: 100%" class="text_block  ">


            <div id="_contact_details" class="block reg_form">
                <form id="contact_details" class="sky-form" y>
                    <header class="mirror_master" id="_contact_details_title">{if !empty($data.labels._contact_details_title)}{$data.labels._contact_details_title}{else}{t}Customer details{/t}{/if}</header>




                    <fieldset>

                        <section>
                            <label class="label">{if !empty($data.labels._customer_code_label)}{$data.labels._customer_code_label}{else}{t}Code{/t}{/if}</label>
                            <label class="input">
                                <i class="icon-append "><i class="far fa-fingerprint" aria-hidden="true"></i></i>
                                <input class="register_field" type="text" name="code" value="{$customer_client->get('Customer Client Code')}" placeholder="{if !empty($data.labels._customer_code_placeholder)}{$data.labels._customer_code_placeholder}{else}{t}Code{/t}{/if}">
                                <b  class="tooltip tooltip-bottom-right">{if !empty($data.labels._customer_code_tooltip)}{$data.labels._customer_code_tooltip}{else}{t}Unique customer code{/t}{/if}</b>
                            </label>
                        </section>

                        <section>
                            <label class="label">{if !empty($data.labels._company_label)}{$data.labels._company_label}{else}{t}Company{/t}{/if}</label>
                            <label class="input">
                                <i class="icon-append "><i class="far fa-store-alt" aria-hidden="true"></i></i>
                                <input class="register_field" type="text" name="company" value="{$customer_client->get('Customer Client Company Name')}" placeholder="{if !empty($data.labels._company_placeholder)}{$data.labels._company_placeholder}{else}{t}Company{/t}{/if}">
                                <b  class="tooltip tooltip-bottom-right">{if !empty($data.labels._company_tooltip)}{$data.labels._company_tooltip}{else}{t}Company{/t}{/if}</b>
                            </label>
                        </section>


                        <section>
                            <label class="label">{if !empty($data.labels._contact_name_label)}{$data.labels._contact_name_label}{else}{t}Contact name{/t}{/if}</label>
                            <label class="input">
                                <i class="icon-append "><i class="far fa-user" aria-hidden="true"></i></i>
                                <input class="register_field" type="text" name="contact_name" value="{$customer_client->get('Customer Client Main Contact Name')}" placeholder="{if !empty($data.labels._contact_name_placeholder)}{$data.labels._contact_name_placeholder}{else}{t}Name{/t}{/if}">
                                <b  class="tooltip tooltip-bottom-right">{if !empty($data.labels._contact_name_tooltip)}{$data.labels._contact_name_tooltip}{else}{t}Name{/t}{/if}</b>
                            </label>
                        </section>


                        <section>
                            <label class="label">{if !empty($data.labels._mobile_label)}{$data.labels._mobile_label}{else}{t}Mobile{/t}{/if}</label>
                            <label class="input">
                                <i class="icon-append "><i class="far fa-mobile" aria-hidden="true"></i></i>
                                <input class="register_field" type="text" name="mobile" value="{$customer_client->get('Customer Client Main Plain Mobile')}" placeholder="{if !empty($data.labels._mobile_placeholder)}{$data.labels._mobile_placeholder}{else}{t}Mobile{/t}{/if}">
                                <b i class="tooltip tooltip-bottom-right">{if !empty($data.labels._mobile_tooltip)}{$data.labels._mobile_tooltip}{else}{t}Mobile{/t}{/if}</b>
                            </label>
                        </section>

                        <section>
                            <label class="label">{if !empty($data.labels._email_label)}{$data.labels._email_label}{else}{t}Email{/t}{/if}</label>
                            <label class="input">
                                <i class="icon-append "><i class="far fa-envelope" aria-hidden="true"></i></i>
                                <input class="register_field" type="email" name="email" id="_email_placeholder" value="{$customer_client->get('Customer Client Main Plain Email')}" placeholder="{if !empty($data.labels._email_placeholder)}{$data.labels._email_placeholder}{else}{t}Email{/t}{/if}">
                                <b i class="tooltip tooltip-bottom-right">{if !empty($data.labels._email_tooltip)}{$data.labels._email_tooltip}{else}{t}Email{/t}{/if}</b>
                            </label>
                        </section>

                    </fieldset>




                    <footer>
                        <button id="save_contact_details_button" type="submit" class="button invisible ">{if !empty($data.labels._save_contact_details_label)}{$data.labels._save_contact_details_label}{else}{t}Save{/t}{/if}<i class="margin_left_10 fa fa-fw fa-save" aria-hidden="true"></i>
                        </button>
                    </footer>
                </form>
            </div>

            
            <div id="_delivery_addresses_details" class="block hide reg_form">
                <div class="address_form">
                    <form id="delivery_address_form" class="sky-form">
                        <header >{if !empty($data.labels._delivery_addresses_title)}{$data.labels._delivery_addresses_title}{else}{t}Delivery address{/t}{/if}</header>




                        <fieldset id="delivery_address_fields" class="{if $customer_client->get('Customer Client Contact Address Link')=='Billing'}hide{/if}" style="position:relative">


                            <section id="delivery_addressLine1" class="{if 'addressLine1'|in_array:$delivery_used_address_fields}{else}hide{/if}">

                                <label for="file" class="input">
                                    <label class="label">{if !empty($labels.address_addressLine1)}{$labels.address_addressLine1}{else}{t}Address Line 1{/t}{/if}</label>

                                    <input value="{$customer_client->get('Customer Client Contact Address Line 1')}" type="text" name="addressLine1" class="{if 'addressLine1'|in_array:$delivery_used_address_fields}{else}ignore{/if}"
                                           placeholder="{if !empty($labels.address_addressLine1) }{$labels.address_addressLine1}{else}{t}Address Line 1{/t}{/if}">
                                    <b class="tooltip tooltip-bottom-right">{if !empty($labels.address_addressLine1) }{$labels.address_addressLine1}{else}{t}Address Line 1{/t}{/if}</b>
                                </label>
                            </section>

                            <section id="delivery_addressLine2" class="{if 'addressLine2'|in_array:$delivery_used_address_fields}{else}hide{/if}">
                                <label for="file" class="input">
                                    <label class="label">{if !empty($labels.address_addressLine2)}{$labels.address_addressLine2}{else}{t}Address Line 2{/t}{/if}</label>

                                    <input value="{$customer_client->get('Customer Contact Delivery Address Line 2')}" type="text" name="addressLine2" class="{if 'addressLine2'|in_array:$delivery_used_address_fields}{else}ignore{/if}"
                                           placeholder="{if !empty($labels.address_addressLine2) }{$labels.address_addressLine2}{else}{t}Address Line 2{/t}{/if}">
                                    <b class="tooltip tooltip-bottom-right">{if !empty($labels.address_addressLine2) }{$labels.address_addressLine2}{else}{t}Address Line 2{/t}{/if}</b>
                                </label>
                            </section>


                            <div id="delivery_sortingCode" class="row {if 'sortingCode'|in_array:$delivery_used_address_fields}{else}hide{/if}">
                                <section class="col col-6 ">
                                    <label class="input">
                                        <label class="label">{if !empty($labels.address_sorting_code) }{$labels.address_sorting_code}{else}{t}Sorting code{/t}{/if}</label>

                                        <input value="{$customer_client->get('Customer Client Contact Address Sorting Code')}" type="text" name="sortingCode"
                                               class="{if 'sortingCode'|in_array:$delivery_used_address_fields}{else}ignore{/if}"
                                               placeholder="{if !empty($labels.address_sorting_code) }{$labels.address_sorting_code}{else}{t}Sorting code{/t}{/if}">
                                        <b class="tooltip tooltip-bottom-right">{if !empty($labels.address_sorting_code) }{$labels.address_sorting_code}{else}{t}Sorting code{/t}{/if}</b>
                                    </label>
                                </section>


                            </div>

                            <div id="delivery_postalCode" class="row {if 'postalCode'|in_array:$delivery_used_address_fields}{else}hide{/if}">
                                <section class="col col-6 ">
                                    <label class="input">
                                        <label class="label">{if !empty($labels["postalCode_`$delivery_address_labels.postalCode.code`"]) }{$labels["postalCode_`$delivery_address_labels.postalCode.code`"]}{else}{$delivery_address_labels.postalCode.label}{/if}</label>

                                        <input value="{$customer_client->get('Customer Client Contact Address Postal Code')}" type="text" name="postalCode"
                                               class="{if 'postalCode'|in_array:$delivery_used_address_fields}{else}ignore{/if}"
                                               placeholder="{if !empty($labels["postalCode_`$delivery_address_labels.postalCode.code`"]) }{$labels["postalCode_`$delivery_address_labels.postalCode.code`"]}{else}{$delivery_address_labels.postalCode.label}{/if}">
                                        <b class="tooltip tooltip-bottom-right">{if !empty($labels["postalCode_`$delivery_address_labels.postalCode.code`"]) }{$labels["postalCode_`$delivery_address_labels.postalCode.code`"]}{else}{$delivery_address_labels.postalCode.label}{/if}</b>
                                    </label>
                                </section>


                            </div>

                            <div id="delivery_dependentLocality" class="row {if 'dependentLocality'|in_array:$delivery_used_address_fields}{else}hide{/if}">
                                <section class="col col-6 ">
                                    <label class="input">
                                        <label class="label">{if !empty($labels["dependentLocality_`$delivery_address_labels.dependentLocality.code`"]) }{$labels["dependentLocality_`$delivery_address_labels.dependentLocality.code`"]}{else}{$delivery_address_labels.dependentLocality.label}{/if}</label>

                                        <input value="{$customer_client->get('Customer Client Contact Address Dependent Locality')}" type="text" name="dependentLocality"
                                               class="{if 'dependentLocality'|in_array:$delivery_used_address_fields}{else}ignore{/if}"
                                               placeholder="{if !empty($labels["dependentLocality_`$delivery_address_labels.dependentLocality.code`"])}{$labels["dependentLocality_`$delivery_address_labels.dependentLocality.code`"]}{else}{$delivery_address_labels.dependentLocality.label}{/if}">
                                        <b class="tooltip tooltip-bottom-right">{if !empty($labels["dependentLocality_`$delivery_address_labels.dependentLocality.code`"])}{$labels["dependentLocality_`$delivery_address_labels.dependentLocality.code`"]}{else}{$delivery_address_labels.dependentLocality.label}{/if}</b>
                                    </label>
                                </section>

                            </div>

                            <div id="delivery_locality" class="row {if 'locality'|in_array:$delivery_used_address_fields}{else}hide{/if}">
                                <section class="col col-6 ">
                                    <label class="input">
                                        <label class="label">{if !empty($labels["locality_`$delivery_address_labels.locality.code`"]) }{$labels["locality_`$delivery_address_labels.locality.code`"]}{else}{$delivery_address_labels.locality.label}{/if}</label>

                                        <input value="{$customer_client->get('Customer Client Contact Address Locality')}" type="text" name="locality" class="{if 'locality'|in_array:$delivery_used_address_fields}{else}ignore{/if}"
                                               placeholder="{if !empty($labels["locality_`$delivery_address_labels.locality.code`"]) }{$labels["locality_`$delivery_address_labels.locality.code`"]}{else}{$delivery_address_labels.locality.label}{/if}">
                                        <b class="tooltip tooltip-bottom-right">{if !empty($labels["locality_`$delivery_address_labels.locality.code`"]) }{$labels["locality_`$delivery_address_labels.locality.code`"]}{else}{$delivery_address_labels.locality.label}{/if}</b>
                                    </label>
                                </section>

                            </div>


                            <div id="delivery_administrativeArea" class="row {if 'administrativeArea'|in_array:$delivery_used_address_fields}{else}hide{/if}">
                                <section class="col col-6 ">
                                    <label class="input">
                                        <label class="label">{if !empty($labels["administrativeArea_`$delivery_address_labels.administrativeArea.code`"]) }{$labels["administrativeArea_`$delivery_address_labels.administrativeArea.code`"]}{else}{$delivery_address_labels.administrativeArea.label}{/if}</label>

                                        <input value="{$customer_client->get('Customer Client Contact Address Administrative Area')}" type="text" name="administrativeArea"
                                               class="{if 'administrativeArea'|in_array:$delivery_used_address_fields}{else}ignore{/if}"
                                               placeholder="{if !empty($labels["administrativeArea_`$delivery_address_labels.administrativeArea.code`"])}{$labels["administrativeArea_`$delivery_address_labels.administrativeArea.code`"]}{else}{$delivery_address_labels.administrativeArea.label}{/if}">
                                        <b class="tooltip tooltip-bottom-right">{if !empty($labels["administrativeArea_`$delivery_address_labels.administrativeArea.code`"]) }{$labels["administrativeArea_`$delivery_address_labels.administrativeArea.code`"]}{else}{$delivery_address_labels.administrativeArea.label}{/if}</b>
                                    </label>
                                </section>

                            </div>


                            <div class="row">
                                <section class="col col-5">
                                    <label class="select">
                                        <select id="delivery_country_select" name="country">
                                            <option value="0" selected disabled>{if !empty($labels.address_country) }{$labels.address_country}{else}{t}Country{/t}{/if}</option>

                                            {foreach from=$countries item=country}
                                                <option value="{$country['2alpha']}" {if $country['2alpha']==$customer_client->get('Customer Client Contact Address Country 2 Alpha Code')}selected{/if} >{$country.name}</option>
                                            {/foreach}


                                            <select>
                                    </label>
                                </section>


                            </div>


                        </fieldset>


                        <footer>
                            <button type="submit" class="button invisible" id="save_delivery_address_details_button">{if !empty($data.labels._save_delivery_address_details_label)}{$data.labels._save_delivery_address_details_label}{else}{t}Save{/t}{/if}
                                <i class="margin_left_10 fa fa-fw fa-save" aria-hidden="true"></i></button>

                        </footer>
                    </form>
                </div>

            </div>

            <div id="_orders_details" class="block hide">


                {assign "orders" $customer_client->get_orders_data()}

                <div style="display:flex;width: 100%">
                    <div style="flex-grow:1">
                        <h1 class="orders_title">
                            {if $orders|@count gt 0}
                            {if !empty($data.labels._orders_title)}{$data.labels._orders_title}{else}{t}Orders{/t}{/if}
                            {else}
                                {if !empty($data.labels._orders_title)}{$data.label.client_no_orders}{else}{t}Customer hasn't any order yet{/t}{/if}

                            {/if}

                        </h1>
                    </div>

                    <div style="flex-grow:1;text-align: right">
                        <a href="#new_client_form" class="modal-opener">
                            <button class="empty"  onclick="window.location.href = 'client_basket.sys?client_id={$customer_client->id}';" style="cursor:pointer;line-height30px;padding:10px 20px;text-align: center;border:none;position: relative;top:-20px;font-size: 16px"> <i class="fa fa-plus padding_right_5"></i>
                                {if empty($labels._add_customer_client)}{t}New order{/t}{else}{$labels._new_order}{/if}</span>
                            </button>
                        </a>
                    </div>
                </div>


                <table class="orders  {if $orders|@count eq 0}hide{/if}  ">
                    <thead>
                    <tr>
                        <th class="text-left" id="_orders_th_number">{if empty($data.labels._orders_th_number)}{t}Number{/t}{else}{$data.labels._orders_th_number}{/if}</th>
                        <th class="text-left" id="_orders_th_date">{if empty($data.labels._orders_th_date)}{t}Date{/t}{else}{$data.labels._orders_th_date}{/if}</th>
                        <th class="text-left" id="_orders_th_status">{if empty($data.labels._orders_th_status)}{t}Status{/t}{else}{$data.labels._orders_th_status}{/if}</th>
                        <th class="text-right" id="_orders_th_total">{if empty($data.labels._orders_th_total)}{t}Total{/t}{else}{$data.labels._orders_th_total}{/if}</th>
                        <th></th>


                    </tr>
                    </thead>
                    <tbody>
                    {foreach from=$orders item=_order}

                            <tr>


                                <td class="like_link" onclick="go_to_order({$_order.key})"><span>{$_order.number}</span></td>
                                <td>{$_order.date}</td>
                                <td>{$_order.state}</td>
                                <td class="text-right">{$_order.total}</td>
                                <td>
                                    <a target="_blank" href="invoice.pdf.php?id={$_order.invoice_key}"><img class="button  {if !$_order.invoice_key}hide{/if}"
                                                                                                            style="margin-left:50px;width: 50px;height:16px;position: relative;top:2px" src="/art/pdf.gif"></a>
                                </td>
                            </tr>

                    {/foreach}
                    </tbody>
                </table>


            </div>

            <div id="_order_details" class="block hide">


            </div>


        </div>


    </div>

</div>
<script>

    function go_to_order(order_key){
        $('.block').addClass('hide')

        var ajaxData = new FormData();

        ajaxData.append("tipo", 'get_order_html')
        ajaxData.append("order_key", order_key)
        ajaxData.append("device_prefix", '')


        $.ajax({
            url: "/ar_web_order.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false, complete: function () {
            }, success: function (data) {

                console.log(data)

                if (data.state == '200') {

                 $('#_order_details').html(data.html).removeClass('hide')


                } else if (data.state == '400') {
                    swal("{t}Error{/t}!", data.msg, "error")
                }




            }, error: function () {

            }
        });

    }

    function go_back_orders() {

        $('.block').addClass('hide')
        $('#_orders_details').removeClass('hide')

    }

    function change_block(element) {

        $('.block').addClass('hide')
        $('#' + $(element).data('block')).removeClass('hide')

        $('.sidebar_widget .block_link').removeClass('selected')
        $(element).addClass('selected')
    }




    $("form").on('submit', function (e) {

        e.preventDefault();
        e.returnValue = false;

    });


    $("#contact_details").validate({

        submitHandler: function (form) {


            var button = $('#save_contact_details_button');

            if (button.hasClass('wait')) {
                return;
            }

            button.addClass('wait')
            button.find('i').removeClass('fa-save').addClass('fa-spinner fa-spin')


            var register_data = {}

            $("#contact_details input:not(.ignore)").each(function (i, obj) {
                if (!$(obj).attr('name') == '') {


                    if ($(obj).attr('type') == 'checkbox') {
                        register_data[$(obj).attr('name')] = $(obj).is(':checked')
                    } else {
                        register_data[$(obj).attr('name')] = $(obj).val()
                    }

                }

            });

            $("#contact_details select:not(.ignore)").each(function (i, obj) {
                if (!$(obj).attr('name') == '') {


                    register_data[$(obj).attr('name')] = $(obj).val()
                }

            });


            var ajaxData = new FormData();

            ajaxData.append("tipo", 'update_customer_client_details')
            ajaxData.append("key", $('.client').data('client_key'))

            ajaxData.append("data", JSON.stringify(register_data))


            $.ajax({
                url: "/ar_web_client.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false, complete: function () {
                }, success: function (data) {


                    if (data.state == '200') {

                        for (var key in data.metadata.class_html) {
                            $('.' + key).html(data.metadata.class_html[key])
                        }


                    } else if (data.state == '400') {
                        swal("{t}Error{/t}!", data.msg, "error")
                    }


                    button.removeClass('wait').addClass('invisible')
                    button.find('i').addClass('fa-save').removeClass('fa-spinner fa-spin')


                }, error: function () {
                    button.removeClass('wait')
                    button.find('i').addClass('fa-save').removeClass('fa-spinner fa-spin')

                }
            });


        },

        // Rules for form validation
        rules: {

            email: {
                required: false, email: true, remote: {
                    url: "ar_web_validate.php", data: {
                        tipo: 'validate_update_email'
                    }
                }

            },

            contact_name: {
                required: false,

            }, mobile: {
                required: false,

            },


        },

        // Messages for form validation
        messages: {

            email: {
                required: '{if empty($labels._validation_required)}{t}Required field{/t}{else}{$labels._validation_required|escape}{/if}',
                email: '{if empty($labels._validation_email_invalid)}{t}Invalid email{/t}{else}{$labels._validation_email_invalid|escape}{/if}',
                remote: '{if empty($labels._validation_handle_registered)}{t}Email address is already in registered{/t}{else}{$labels._validation_handle_registered|escape}{/if}',


            },

            contact_name: {
                required: '{if empty($labels._validation_required)}{t}Required field{/t}{else}{$labels._validation_required|escape}{/if}',
            }, mobile: {
                required: '{if empty($labels._validation_required)}{t}Required field{/t}{else}{$labels._validation_required|escape}{/if}',
            }


        },

        // Do not change code below
        errorPlacement: function (error, element) {
            error.insertAfter(element.parent());
        }
    });


    $(document).on('keyup paste change', "#contact_details :input", function (ev) {
        $('#save_contact_details_button').removeClass('invisible')
    });



    $(document).on('keyup paste change', "#delivery_address_form :input", function (ev) {
        $('#save_delivery_address_details_button').removeClass('invisible')
    });


    $(document).on('change', "#delivery_address_link", function (ev) {

        if ($(this).is(':checked')) {
            $('#delivery_address_fields').addClass('hide')

        } else {
            $('#delivery_address_fields').removeClass('hide')

        }
    });





    $("#delivery_country_select").change(function () {


        var selected = $("#delivery_country_select option:selected")
        // console.log(selected.val())

        var request = "ar_web_addressing.php?tipo=address_format&country_code=" + selected.val() + '&website_key={$website->id}'

        console.log(request)
        $.getJSON(request, function (data) {
            console.log(data)
            $.each(data.hidden_fields, function (index, value) {
                $('#delivery_' + value).addClass('hide')
                $('#delivery_' + value).find('input').addClass('ignore')

            });

            $.each(data.used_fields, function (index, value) {
                $('#delivery_' + value).removeClass('hide')
                $('#delivery_' + value).find('input').removeClass('ignore')

            });

            $.each(data.labels, function (index, value) {
                $('#delivery_' + index).find('input').attr('placeholder', value)
                $('#delivery_' + index).find('b').html(value)
                $('#delivery_' + index).find('label.label').html(value)

            });

            $.each(data.no_required_fields, function (index, value) {


                // console.log(value)

                $('#delivery_' + value + ' input').rules("remove");


            });

            $.each(data.required_fields, function (index, value) {
                console.log($('#' + value))
                //console.log($('#'+value+' input').rules())

                $('#delivery_' + value + ' input').rules("add", {
                    required: true});

            });


        });


    });


    $("#delivery_address_form").validate({

        submitHandler: function (form) {


            var button = $('#save_delivery_address_details_button');

            if (button.hasClass('wait')) {
                return;
            }

            button.addClass('wait')
            button.find('i').removeClass('fa-save').addClass('fa-spinner fa-spin')


            var register_data = {}

            $("#delivery_address_form input:not(.ignore)").each(function (i, obj) {
                if (!$(obj).attr('name') == '') {
                    register_data[$(obj).attr('name')] = $(obj).val()
                }

            });

            $("#delivery_address_form select:not(.ignore)").each(function (i, obj) {
                if (!$(obj).attr('name') == '') {


                    register_data[$(obj).attr('name')] = $(obj).val()
                }

            });

            register_data['delivery_address_link'] = $('#delivery_address_link').is(':checked')


            console.log(register_data)

            var ajaxData = new FormData();

            ajaxData.append("tipo", 'update_contact_address')
            ajaxData.append("key", $('.client').data('client_key'))

            ajaxData.append("data", JSON.stringify(register_data))


            $.ajax({
                url: "/ar_web_client.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false, complete: function () {
                }, success: function (data) {

                    console.log(data)

                    if (data.state == '200') {


                    } else if (data.state == '400') {
                        swal("{t}Error{/t}!", data.msg, "error")
                    }

                    button.removeClass('wait').addClass('invisible')
                    button.find('i').addClass('fa-save').removeClass('fa-spinner fa-spin')

                }, error: function () {
                    button.removeClass('wait').addClass('invisible')
                    button.find('i').addClass('fa-save').removeClass('fa-spinner fa-spin')
                }
            });


        },

        // Rules for form validation
        rules: {



    {foreach from=$delivery_required_fields item=required_field }
    {$required_field}:
    {
        required: true
    }
    ,
    {/foreach}

    {foreach from=$delivery_no_required_fields item=no_required_field }
    {$no_required_field}:
    {
        required: false
    }
    ,
    {/foreach}

    },

    // Messages for form validation
    messages:
    {


        administrativeArea:
        {
            required: '{if empty($labels._validation_required)}{t}Required field{/t}{else}{$labels._validation_required|escape}{/if}',
        }
    ,
        locality:
        {
            required: '{if empty($labels._validation_required)}{t}Required field{/t}{else}{$labels._validation_required|escape}{/if}',
        }
    ,
        dependentLocality:
        {
            required: '{if empty($labels._validation_required)}{t}Required field{/t}{else}{$labels._validation_required|escape}{/if}',
        }
    ,
        postalCode:
        {
            required: '{if empty($labels._validation_required)}{t}Required field{/t}{else}{$labels._validation_required|escape}{/if}',
        }
    ,
        addressLine1:
        {
            required: '{if empty($labels._validation_required)}{t}Required field{/t}{else}{$labels._validation_required|escape}{/if}',
        }
    ,
        addressLine2:
        {
            required: '{if empty($labels._validation_required)}{t}Required field{/t}{else}{$labels._validation_required|escape}{/if}',
        }
    ,
        sortingCode:
        {
            required: '{if empty($labels._validation_required)}{t}Required field{/t}{else}{$labels._validation_required|escape}{/if}',
        }


    }
    ,

    // Do not change code below
    errorPlacement: function (error, element) {
        error.insertAfter(element.parent());
    }
    })
    ;


</script>
