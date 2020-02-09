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
    .client_showcase {
        display: flex;
        border-bottom: 1px solid #ccc;
        padding: 5px 15px;

    }

    .client_name {
        font-weight: 400;
        color: #555
    }

</style>

{if isset($data.top_margin)}{assign "top_margin" $data.top_margin}{else}{assign "top_margin" "0"}{/if}

{if isset($data.bottom_margin)}{assign "bottom_margin" $data.bottom_margin}{else}{assign "bottom_margin" "0"}{/if}


<div id="block_{$key}" data-block_key="{$key}" data-client_key="{$customer_client->id}" block="{$data.type}" class="{$data.type}  {if !$data.show}hide{/if}" style="padding-bottom:{$bottom_margin}px">
    <div class="table_top" style="margin-top: 4px">
        <span class="title"> <span class="margin_right_10">{t}Customer{/t}:</span> <span class="Customer_Client_Name">{$customer_client->get('Customer Client Name')}</span>  <span style="font-size: 15px;position: relative;bottom: 1px">(<span class="Customer_Client_Code">{$customer_client->get('Customer Client Code')}</span>)</span> </span>
    </div>

    <div class="client_showcase" style="padding-top:10px">


        <div class="data_container">

            <div class="data_field" style="min-width: 270px;">
                <i title="{t}Customer code{/t}" class="fa fa-fw  fa-fingerprint padding_right_10"></i> <span style="font-weight: 800" class="Customer_Client_Code">{$customer_client->get('Customer Client Code')}</span>
            </div>
            <div class="data_field Customer_Client_Company_Name_display {if !$customer_client->get('Customer Client Company Name')}hide{/if}" style="min-width: 270px;">
                <i title="{t}Company name{/t}" class="fa fa-fw  fa-store padding_right_10"></i> <span class="Customer_Client_Company_Name">{$customer_client->get('Customer Client Company Name')}</span>
            </div>
            <div class="data_field  Customer_Client_Main_Contact_Name_display {if !$customer_client->get('Customer Client Main Contact Name')}hide{/if}" style="min-width: 270px;">
                <i title="{t}Contact name{/t}" class="fa fa-fw  fa-male padding_right_10"></i> <span class="Customer_Client_Main_Contact_Name">{$customer_client->get('Customer Client Main Contact Name')}</span>
            </div>


            <div id="Customer_Client_Main_Plain_Email_display" class="data_field Subject_Email_display  {if !$customer_client->get('Customer Client Main Plain Email')}hide{/if}">
                <i class="fa fa-fw fa-at padding_right_10"></i> <span class="Subject_Email" id="Customer_Client_Other_Email_mailto">{if $customer_client->get('Customer Client Main Plain Email')}{mailto address=$customer_client->get('Main Plain Email')}{/if}</span>
            </div>


            <span id="display_telephones"></span> {if $customer_client->get('Customer Client Preferred Contact Number')=='Mobile'}
                <div id="Customer_Client_Main_Plain_Mobile_display" class="data_field  Subject_Mobile_display  {if $customer_client->get('Main Plain Mobile')==''}hide{/if} ">
                    <i class="far fa-fw fa-mobile padding_right_10"></i> <span class="Customer_Client_Main_Plain_Mobile Subject_Mobile">{$customer_client->get('Main XHTML Mobile')}</span>
                </div>
                <div id="Customer_Client_Main_Plain_Telephone_display" class="data_field Subject_Telephone_display {if !$customer_client->get('Customer Client Main Plain Telephone')}hide{/if}">
                    <i class="fa fa-fw fa-phone padding_right_10"></i> <span class="Customer_Client_Main_Plain_Telephone Subject_Telephone">{$customer_client->get('Main XHTML Telephone')}</span>
                </div>
            {else}
                <div id="Customer_Client_Main_Plain_Telephone_display" class="data_field Subject_Telephone_display {if !$customer_client->get('Customer Client Main Plain Telephone')}hide{/if}">
                    <i title="Telephone" class="fa fa-fw fa-phone padding_right_10"></i> <span class="Customer_Client_Main_Plain_Telephone Subject_Telephone">{$customer_client->get('Main XHTML Telephone')}</span>
                </div>
                <div id="Customer_Client_Main_Plain_Mobile_display" class="data_field Subject_Mobile_display {if !$customer_client->get('Customer Client Main Plain Mobile')}hide{/if}">
                    <i title="Mobile" class="fa fa-fw fa-mobile padding_right_10"></i> <span class="Customer_Client_Main_Plain_Mobile Subject_Mobile">{$customer_client->get('Main XHTML Mobile')}</span>
                </div>
            {/if}

            <div style="padding-top: 10px;padding-left:30px;font-style: italic;cursor: pointer" class="discreet small">
                <span data-href="#contact_details" class="modal-opener"> <i class="far fa-pencil-alt"></i> {t}Edit customer details{/t}</span>
            </div>


        </div>

        <div class="data_container">
            <div style="min-height:80px;float:left;width:28px">
                <i class="fa fa-fw fa-map-marker-alt"></i>
            </div>
            <div class="Customer_Client_Contact_Address" style="float:left;min-width:242px">
                {$customer_client->get('Contact Address Formatted')}
            </div>

            <div style="padding-top:5px;padding-left:20px;font-style: italic;cursor: pointer;clear: both" class="discreet small">
                <span data-href="#delivery_address_form" class="modal-opener"> <i class="far fa-pencil-alt"></i> {t}Edit address{/t}</span>
            </div>


        </div>


    </div>



    <div id="table_container"></div>

    <div class="text_blocks container">

        <div style="width: 100%" class="text_block  ">


            <div id="_contact_details" class="block reg_form">
                <form id="contact_details" class="sky-form sky-form-modal">
                    <header class="mirror_master" id="_contact_details_title">{if !empty($data.labels._contact_details_title)}{$data.labels._contact_details_title}{else}{t}Customer details{/t}{/if}</header>


                    <fieldset>

                        <section>
                            <label class="label">{if !empty($data.labels._customer_code_label)}{$data.labels._customer_code_label}{else}{t}Code{/t}{/if}</label>
                            <label class="input">
                                <i class="icon-append "><i class="far fa-fingerprint" aria-hidden="true"></i></i>
                                <input class="register_field" type="text" name="code" value="{$customer_client->get('Customer Client Code')}"
                                       placeholder="{if !empty($data.labels._customer_code_placeholder)}{$data.labels._customer_code_placeholder}{else}{t}Code{/t}{/if}">
                                <b class="tooltip tooltip-bottom-right">{if !empty($data.labels._customer_code_tooltip)}{$data.labels._customer_code_tooltip}{else}{t}Unique customer code{/t}{/if}</b>
                            </label>
                        </section>

                        <section>
                            <label class="label">{if !empty($data.labels._company_label)}{$data.labels._company_label}{else}{t}Company{/t}{/if}</label>
                            <label class="input">
                                <i class="icon-append "><i class="far fa-store-alt" aria-hidden="true"></i></i>
                                <input class="register_field" type="text" name="company" value="{$customer_client->get('Customer Client Company Name')}"
                                       placeholder="{if !empty($data.labels._company_placeholder)}{$data.labels._company_placeholder}{else}{t}Company{/t}{/if}">
                                <b class="tooltip tooltip-bottom-right">{if !empty($data.labels._company_tooltip)}{$data.labels._company_tooltip}{else}{t}Company{/t}{/if}</b>
                            </label>
                        </section>


                        <section>
                            <label class="label">{if !empty($data.labels._contact_name_label)}{$data.labels._contact_name_label}{else}{t}Contact name{/t}{/if}</label>
                            <label class="input">
                                <i class="icon-append "><i class="far fa-user" aria-hidden="true"></i></i>
                                <input class="register_field" type="text" name="contact_name" value="{$customer_client->get('Customer Client Main Contact Name')}"
                                       placeholder="{if !empty($data.labels._contact_name_placeholder)}{$data.labels._contact_name_placeholder}{else}{t}Name{/t}{/if}">
                                <b class="tooltip tooltip-bottom-right">{if !empty($data.labels._contact_name_tooltip)}{$data.labels._contact_name_tooltip}{else}{t}Name{/t}{/if}</b>
                            </label>
                        </section>


                        <section>
                            <label class="label">{if !empty($data.labels._mobile_label)}{$data.labels._mobile_label}{else}{t}Mobile{/t}{/if}</label>
                            <label class="input">
                                <i class="icon-append "><i class="far fa-mobile" aria-hidden="true"></i></i>
                                <input class="register_field" type="text" name="mobile" value="{$customer_client->get('Customer Client Main Plain Mobile')}"
                                       placeholder="{if !empty($data.labels._mobile_placeholder)}{$data.labels._mobile_placeholder}{else}{t}Mobile{/t}{/if}">
                                <b i class="tooltip tooltip-bottom-right">{if !empty($data.labels._mobile_tooltip)}{$data.labels._mobile_tooltip}{else}{t}Mobile{/t}{/if}</b>
                            </label>
                        </section>

                        <section>
                            <label class="label">{if !empty($data.labels._email_label)}{$data.labels._email_label}{else}{t}Email{/t}{/if}</label>
                            <label class="input">
                                <i class="icon-append "><i class="far fa-envelope" aria-hidden="true"></i></i>
                                <input class="register_field" type="email" name="email" id="_email_placeholder" value="{$customer_client->get('Customer Client Main Plain Email')}"
                                       placeholder="{if !empty($data.labels._email_placeholder)}{$data.labels._email_placeholder}{else}{t}Email{/t}{/if}">
                                <b i class="tooltip tooltip-bottom-right">{if !empty($data.labels._email_tooltip)}{$data.labels._email_tooltip}{else}{t}Email{/t}{/if}</b>
                            </label>
                        </section>

                    </fieldset>


                    <footer>
                        <button id="save_contact_details_button" type="submit"
                                class="button invisible ">{if !empty($data.labels._save_contact_details_label)}{$data.labels._save_contact_details_label}{else}{t}Save{/t}{/if} <i class="margin_left_10 fa fa-fw fa-save"
                                                                                                                                                                                   aria-hidden="true"></i>
                        </button>
                    </footer>
                </form>
            </div>


            <div id="_delivery_addresses_details" class="block  reg_form">
                <div class="address_form">
                    <form id="delivery_address_form" class="sky-form sky-form-modal">
                        <header>{if !empty($data.labels._delivery_addresses_title)}{$data.labels._delivery_addresses_title}{else}{t}Delivery address{/t}{/if}</header>


                        <fieldset id="delivery_address_fields" class="{if $customer_client->get('Customer Client Contact Address Link')=='Billing'}hide{/if}" style="position:relative">


                            <section id="delivery_addressLine1" class="{if 'addressLine1'|in_array:$delivery_used_address_fields}{else}hide{/if}">

                                <label for="file" class="input">
                                    <label class="label">{if !empty($labels.address_addressLine1)}{$labels.address_addressLine1}{else}{t}Address Line 1{/t}{/if}</label>

                                    <input value="{$customer_client->get('Customer Client Contact Address Line 1')}" type="text" name="addressLine1"
                                           class="{if 'addressLine1'|in_array:$delivery_used_address_fields}{else}ignore{/if}"
                                           placeholder="{if !empty($labels.address_addressLine1) }{$labels.address_addressLine1}{else}{t}Address Line 1{/t}{/if}">
                                    <b class="tooltip tooltip-bottom-right">{if !empty($labels.address_addressLine1) }{$labels.address_addressLine1}{else}{t}Address Line 1{/t}{/if}</b>
                                </label>
                            </section>

                            <section id="delivery_addressLine2" class="{if 'addressLine2'|in_array:$delivery_used_address_fields}{else}hide{/if}">
                                <label for="file" class="input">
                                    <label class="label">{if !empty($labels.address_addressLine2)}{$labels.address_addressLine2}{else}{t}Address Line 2{/t}{/if}</label>

                                    <input value="{$customer_client->get('Customer Client Contact Address Line 2')}" type="text" name="addressLine2"
                                           class="{if 'addressLine2'|in_array:$delivery_used_address_fields}{else}ignore{/if}"
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

                                        <input value="{$customer_client->get('Customer Client Contact Address Locality')}" type="text" name="locality"
                                               class="{if 'locality'|in_array:$delivery_used_address_fields}{else}ignore{/if}"
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
                                                <option value="{$country['2alpha']}"
                                                        {if $country['2alpha']==$customer_client->get('Customer Client Contact Address Country 2 Alpha Code')}selected{/if} >{$country.name}</option>
                                            {/foreach}


                                            <select>
                                    </label>
                                </section>


                            </div>


                        </fieldset>


                        <footer>
                            <button type="submit" class="button invisible"
                                    id="save_delivery_address_details_button">{if !empty($data.labels._save_delivery_address_details_label)}{$data.labels._save_delivery_address_details_label}{else}{t}Save{/t}{/if}
                                <i class="margin_left_10 fa fa-fw fa-save" aria-hidden="true"></i></button>

                        </footer>
                    </form>
                </div>

            </div>


        </div>


    </div>


</div>

</div>
<script>

    $('.modal-opener').on('click', function () {
        if (!$('#sky-form-modal-overlay').length) {
            $('body').append('<div id="sky-form-modal-overlay" class="sky-form-modal-overlay"></div>');
        }

        $('#sky-form-modal-overlay').on('click', function () {
            $('#sky-form-modal-overlay').fadeOut();
            $('.sky-form-modal').fadeOut();
        });

        form = $($(this).data('href'));
        $('#sky-form-modal-overlay').fadeIn();
        form.css('top', '50%').css('left', '50%').css('margin-top', -form.outerHeight() / 2).css('margin-left', -form.outerWidth() / 2).fadeIn();

        return false;
    });

    $('.modal-closer').on('click', function()
    {
        $('#sky-form-modal-overlay').fadeOut();
        $('.sky-form-modal').fadeOut();

        return false;
    });


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

                        $('#sky-form-modal-overlay').fadeOut();
                        $('.sky-form-modal').fadeOut();

                        for (var key in data.metadata.class_html) {
                            $('.' + key).html(data.metadata.class_html[key])
                        }

                        for (var key in data.metadata.hide) {
                            $('.' + data.metadata.hide[key]).addClass('hide')
                        }

                        for (var key in data.metadata.show) {
                            $('.' + data.metadata.show[key]).removeClass('hide')
                        }

                        $('.breadcrumbs .client_nav').html(data.client_nav.label)
                        $('.breadcrumbs .client_nav').attr('title',data.client_nav.title)

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
                    required: true
                });

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



                    if (data.state == '200') {

                        $('#sky-form-modal-overlay').fadeOut();
                        $('.sky-form-modal').fadeOut();
                        for (var key in data.metadata.class_html) {
                            $('.' + key).html(data.metadata.class_html[key])
                        }

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
