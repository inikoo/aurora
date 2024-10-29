{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 30 August 2017 at 21:34:58 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}
{if empty($data.registration_type) }{assign type 'simple'}{else}{assign type $data.registration_type}{/if}

{if $type=='company_fork'}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
{/if}
<div class="reg_form" style="padding:40px 0px 50px 0px">
    {if $type=='company_fork'}

        <form id="select_main_country" class="sky-form  {if $type!='company_fork'}hide{/if}  ">
            <header id="_title">{$data.labels._title}</header>

            <fieldset class="">


                <div style="margin:0px 15px 15px 15px">{if empty($data.labels._main_select_country_title)}Select country{else}{$data.labels._main_select_country_title}{/if}</div>


                <section class="col col-6">

                    <label class="select">
                        <select id="main_country_select" name="country">
                            <option value="0" selected
                                    disabled>{if isset($labels.address_country) and $labels.address_country!=''}{$labels.address_country}{else}{t}Country{/t}{/if}</option>

                            {foreach from=$countries item=country}
                                <option value="{$country.2alpha}"
                                        {if $country.2alpha==$selected_country}selected{/if} >{$country.name}</option>
                            {/foreach}


                            <select><i></i>
                    </label>
                </section>

                <section class="col col-6">
                    <button id="company_fork_step_2" style="position: relative;top:-12px;float: none" class="button">{if empty($data.labels._main_select_country_button)}Continue{else}{$data.labels._main_select_country_button}{/if} <i style="margin-left: 10px" class="fa fa-arrow-right"></i></button>

                </section>

            </fieldset>

        </form>

        <form id="select_type_company" class="sky-form hide">
            <header id="_title">{$data.labels._title}</header>

            <fieldset class="">
                <div style="margin:0px 15px 15px 15px">{if empty($data.labels._select_company)}Select trader type{else}{$data.labels._select_company}{/if}</div>


                <section class="col col-6">
                    <button style="float: none" id="sole_trader_selected" class=" button">{if empty($data.labels._select_company_sole_trader)}Sole trader{else}{$data.labels._select_company_sole_trader}{/if}</button>

                </section>
                <section class="col col-6">
                    <button style="float: none" id="company_selected" class="button">{if empty($data.labels._select_company_company)}Company{else}{$data.labels._select_company_company}{/if}</button>
                </section>

            </fieldset>

        </form>

        <form id="select_company" class="sky-form hide">
            <header id="_title">{$data.labels._title}</header>

            <fieldset class="">
                <div style="margin:0px 15px 15px 15px;">
                    {if empty($data.labels._search_your_company)}Search for your company{else}{$data.labels._search_your_company}{/if}</div>

                <section class="col col-6">

                    <div style="">
                        <select style="width:100%" class="search-company"></select>
                    </div>

                    <div style="margin-top:20px;font-size: small">
                        {if empty($data.labels._continue_no_search)}If you can not find your company click{else}{$data.labels._continue_no_search}{/if} <span id="bypass_search_company" style="font-weight: 800;cursor: pointer;text-decoration: underline">{if empty($data.labels._continue_no_search_click_here)}here{else}{$data.labels._continue_no_search_click_here}{/if}</span>
                    </div>

                </section>

            </fieldset>

        </form>
    {/if}
    <form id="registration_form" class="{if $type=='company_fork'}hide{/if} sky-form" style="width:650px;margin:auto">
        <header id="_title">{$data.labels._title}</header>

        <fieldset>


            <section>
                <label class="input">
                    <i class="icon-append fa fa-envelope"></i>
                    <input class="register_field" type="email" autocomplete="email" name="email" placeholder="{$data.labels._email_placeholder}">
                    <b id="_email_tooltip" class="tooltip tooltip-bottom-right">{$data.labels._email_tooltip}</b>
                </label>
            </section>

            <section>
                <label class="input">
                    <i id="_password" class="icon-append icon-lock"></i>
                    <input style=" touch-action: none;" class="register_field" type="password" autocomplete="new-password" name="new-password"  id="register_password" placeholder="{$data.labels._password_placeholder}">
                    <b id="_password_tooltip" class="tooltip tooltip-bottom-right">{$data.labels._password_tooltip}</b>
                </label>
            </section>

            <section>
                <label class="input">
                    <i id="_password_confirm" class="icon-append icon-lock"></i>
                    <input class="register_field ignore" type="password" autocomplete="new-password" name="password_confirm" placeholder="{$data.labels._password_confirm_placeholder}">
                    <b id="_password_confirm_tooltip" class="tooltip tooltip-bottom-right">{$data.labels._password_confirm_tooltip}</b>
                </label>
            </section>
        </fieldset>

        <fieldset>

            <section>
                <label class="input">
                    <i class="icon-append fa fa-mobile"></i>
                    <input class="register_field" type="text" autocomplete="tel"  name="tel" placeholder="{$data.labels._mobile_placeholder}">
                    <b id="_mobile_tooltip" class="tooltip tooltip-bottom-right">{$data.labels._mobile_tooltip}</b>
                </label>
            </section>

            <section>
                <label class="input">
                    <i class="icon-append icon-user"></i>
                    <input class="register_field" type="text" autocomplete="name" name="name" placeholder="{$data.labels._contact_name_placeholder}">
                    <b id="_contact_name_tooltip" class="tooltip tooltip-bottom-right">{$data.labels._contact_name_tooltip}</b>
                </label>
            </section>

            <section>
                <label class="input">
                    <i class="icon-append icon-briefcase"></i>
                    <input class="register_field" type="text" autocomplete="organization" name="organization"  placeholder="{$data.labels._company_placeholder}">
                    <b id="_company_tooltip" class="tooltip tooltip-bottom-right">{$data.labels._company_tooltip}</b>
                </label>
            </section>

            <div class="row">
                <section class="col col-6 ">
                    <label id="_tax_number" class="input">
                        <input type="text" name="tax_number" placeholder="{if !empty($data.labels._tax_number) }{$data.labels._tax_number}{else}{t}Tax number{/t}{/if}">
                        <b id="_tax_number_tooltip" class="tooltip tooltip-bottom-right">{if !empty($data.labels._tax_number) }{$data.labels._tax_number}{else}{t}Tax number{/t}{/if}</b>

                    </label>
                </section>
                <section class="col col-6">
                    <label id="_registration_number" class="input" ">
                    <input type="text" name="registration_number"
                           placeholder="{if !empty($data.labels._registration_number) }{$data.labels._registration_number}{else}{t}Registration number{/t}{/if}">
                    <b id="_registration_number_tooltip"
                       class="tooltip tooltip-bottom-right">{if !empty($data.labels._registration_number)}{$data.labels._registration_number}{else}{t}Registration number{/t}{/if}</b>

                    </label>
                </section>
            </div>

        </fieldset>

        <fieldset id="address_fields" style="position:relative">


            <section id="addressLine1" class="{if 'addressLine1'|in_array:$used_address_fields}{else}hide{/if}">

                <label for="file" class="input">
                    <input type="text" name="addressLine1" class="{if 'addressLine1'|in_array:$used_address_fields}{else}ignore{/if}"
                           placeholder="{if !empty($labels.address_addressLine1) }{$labels.address_addressLine1}{else}{t}Address Line 1{/t}{/if}">
                    <b class="tooltip tooltip-bottom-right">{if !empty($labels.address_addressLine1)}{$labels.address_addressLine1}{else}{t}Address Line 1{/t}{/if}</b>
                </label>
            </section>

            <section id="addressLine2" class="{if 'addressLine2'|in_array:$used_address_fields}{else}hide{/if}">
                <label for="file" class="input">
                    <input type="text" name="addressLine2" class="{if 'addressLine2'|in_array:$used_address_fields}{else}ignore{/if}"
                           placeholder="{if !empty($labels.address_addressLine2) }{$labels.address_addressLine2}{else}{t}Address Line 2{/t}{/if}">
                    <b class="tooltip tooltip-bottom-right">{if !empty($labels.address_addressLine2) }{$labels.address_addressLine2}{else}{t}Address Line 2{/t}{/if}</b>
                </label>
            </section>


            <div id="sortingCode" class="row {if 'sortingCode'|in_array:$used_address_fields}{else}hide{/if}">
                <section class="col col-6 ">
                    <label class="input">
                        <input type="text" name="sortingCode" class="{if 'sortingCode'|in_array:$used_address_fields}{else}ignore{/if}"
                               placeholder="{if !empty($labels.address_sorting_code) }{$labels.address_sorting_code}{else}{t}Sorting code{/t}{/if}">
                        <b class="tooltip tooltip-bottom-right">{if !empty($labels.address_sorting_code) }{$labels.address_sorting_code}{else}{t}Sorting code{/t}{/if}</b>
                    </label>
                </section>


            </div>

            <div id="postalCode" class="row {if 'postalCode'|in_array:$used_address_fields}{else}hide{/if}">
                <section class="col col-6 ">
                    <label class="input">
                        <input type="text" name="postalCode" class="{if 'postalCode'|in_array:$used_address_fields}{else}ignore{/if}"
                               placeholder="{if !empty($labels["postalCode_`$address_labels.postalCode.code`"]) }{$labels["postalCode_`$address_labels.postalCode.code`"]}{else}{$address_labels.postalCode.label}{/if}">
                        <b class="tooltip tooltip-bottom-right">{if !empty($labels["postalCode_`$address_labels.postalCode.code`"]) }{$labels["postalCode_`$address_labels.postalCode.code`"]}{else}{$address_labels.postalCode.label}{/if}</b>
                    </label>
                </section>


            </div>

            <div id="dependentLocality" class="row {if 'dependentLocality'|in_array:$used_address_fields}{else}hide{/if}">
                <section class="col col-6 ">
                    <label class="input">
                        <input type="text" name="dependentLocality" class="{if 'dependentLocality'|in_array:$used_address_fields}{else}ignore{/if}"
                               placeholder="{if !empty($labels["dependentLocality_`$address_labels.dependentLocality.code`"]) }{$labels["dependentLocality_`$address_labels.dependentLocality.code`"]}{else}{$address_labels.dependentLocality.label}{/if}">
                        <b class="tooltip tooltip-bottom-right">{if !empty($labels["dependentLocality_`$address_labels.dependentLocality.code`"]) }{$labels["dependentLocality_`$address_labels.dependentLocality.code`"]}{else}{$address_labels.dependentLocality.label}{/if}</b>
                    </label>
                </section>

            </div>

            <div id="locality" class="row {if 'locality'|in_array:$used_address_fields}{else}hide{/if}">
                <section class="col col-6 ">
                    <label class="input">
                        <input type="text" name="locality" class="{if 'locality'|in_array:$used_address_fields}{else}ignore{/if}"
                               placeholder="{if !empty($labels["locality_`$address_labels.locality.code`"])}{$labels["locality_`$address_labels.locality.code`"]}{else}{$address_labels.locality.label}{/if}">
                        <b class="tooltip tooltip-bottom-right"></b>
                    </label>
                </section>

            </div>


            <div id="administrativeArea" class="row {if 'administrativeArea'|in_array:$used_address_fields}{else}hide{/if}">
                <section class="col col-6 ">
                    <label class="input">
                        <input type="text" name="administrativeArea" class="{if 'administrativeArea'|in_array:$used_address_fields}{else}ignore{/if}"
                               placeholder="{if !empty($labels["administrativeArea_`$address_labels.administrativeArea.code`"])}{$labels["administrativeArea_`$address_labels.administrativeArea.code`"]}{else}{$address_labels.administrativeArea.label}{/if}">
                        <b class="tooltip tooltip-bottom-right">{if !empty($labels["administrativeArea_`$address_labels.administrativeArea.code`"]) }{$labels["administrativeArea_`$address_labels.administrativeArea.code`"]}{else}{$address_labels.administrativeArea.label}{/if}</b>
                    </label>
                </section>

            </div>


            <div class="row">
                <section class="col col-5">
                    <label class="select">
                        <select id="country_select" name="country">
                            <option value="0" selected disabled>{if !empty($labels.address_country) }{$labels.address_country}{else}{t}Country{/t}{/if}</option>

                            {foreach from=$countries item=country}
                                <option value="{$country.2alpha}" {if $country.2alpha==$selected_country}selected{/if} >{$country.name}</option>
                            {/foreach}


                            <select><i></i>
                    </label>
                </section>


            </div>


        </fieldset>


        {if !empty($poll_queries)}
            <fieldset>
                <section>

                    <label class="input">
                                        <span id="_poll_info">{if empty($data.labels._poll_info)}{t}Please let know you better so we can serve you better{/t}{else}{$data.labels._poll_info}{/if}
                    </label>
                </section>


                {foreach from=$poll_queries item=query}


                    {if $query['Customer Poll Query Type']=='Open'}
                        <section>
                            <label data-query_key="{$query['Customer Poll Query Key']}" class="label poll_query_label">{$query['Customer Poll Query Label']}</label>
                            <label class="textarea">
                                <textarea rows="4" name="poll_{$query['Customer Poll Query Key']}" id="poll_{$query['Customer Poll Query Key']}"></textarea>
                            </label>
                        </section>
                    {else}
                        <section>
                            <label class="label poll_query_label">{$query['Customer Poll Query Label']}</label>
                            <label class="select">
                                <select name="poll_{$query['Customer Poll Query Key']}">
                                    <option value="0" selected disabled>{if !empty($labels._choose_one)}{$labels._choose_one}{else}{t}Please choose one{/t}{/if}</option>

                                    {foreach from=$query['Options'] item=option}
                                        <option value="{$option['Customer Poll Query Option Key']}">{$option['Customer Poll Query Option Label']}</option>
                                    {/foreach}


                                </select>
                                <i></i>
                            </label>
                        </section>
                    {/if}

                {/foreach}


            </fieldset>
        {/if}


        <fieldset class="last">


            <section>
                <label class="checkbox"><input type="checkbox" name="subscription" id="subscription"><i></i>{$data.labels._subscription}</label>
                <label class="checkbox"><input type="checkbox" name="terms" id="terms"><i></i>{$data.labels._terms} <a href="/tac.sys" target="_blank">
                        <icon class="fa fa-external-link " aria-hidden="true"></icon>
                    </a> </label>


            </section>


        </fieldset>

        {if !empty($settings.fu_key)}
            <footer>
                <div class="cf-turnstile" data-action="register_tablet" data-sitekey="{$settings.fu_key}"></div>
            </footer>
        {/if}

        {if !empty($settings.captcha_client)}
            <footer>
                <div class="g-recaptcha" data-sitekey="{$settings.captcha_client}"></div>
            </footer>
        {/if}

        <footer>
            <button id="register_button" type="submit" class="button"
            ">{$data.labels._submit_label} <i class="fa fa-fw  fa-arrow-right" aria-hidden="true"></i> </button>
        </footer>
    </form>
</div>


{if $type=='company_fork'}
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>



        $('.search-company').select2({
            ajax: {
                url: 'ar_web_search_companies.php',
                dataType: 'json',
                placeholder: "{t}Company name{/t}",
                delay: 250,
                allowClear: true,
                data: function (params) {
                    let query = {
                        tipo:'search_company',
                        name: params.term,
                        country: $('#main_country_select').val()
                    };

                    // Query parameters will be ?search=[term]&type=public
                    return query;
                }

            }
        });


        $('.search-company').on('select2:select', function (e) {
            let data_company = e.params.data.data;

            console.log(data_company)

            $('[name="locality"]').val(data_company.city)
            $('[name="postalCode"]').val(data_company.postcode)

            if(data_company.email){
                $('[name="email"]').val(data_company.email)
            }
            if(data_company.phone){
                $('[name="tel"]').val(data_company.phone)
            }
            $('[name="organization"]').val(data_company.name)



            for (const identifier of data_company.identifiers) {
                if(identifier.idtype==="reg_number"){
                    $('[name="registration_number"]').val(identifier.value)
                }
                if(identifier.idtype==="vat_number"){
                    $('[name="tax_number"]').val(identifier.value)
                }
            }


            let ajaxData = new FormData();

            ajaxData.append("tipo", 'parse_address')
            ajaxData.append("address", data_company.address)
            ajaxData.append("city", data_company.city)
            ajaxData.append("postcode", data_company.postcode)
            ajaxData.append("country", data_company.country)
            ajaxData.append("org", data_company.name)


            $.ajax({
                url: "/ar_web_parse_address.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,
                complete: function () {
                }, success: function (data) {

                    if(data.status===200){
                        Object.keys(data['address_fields']).forEach(key => {
                            $('[name="'+key+'"]').val(data['address_fields'][key])

                        });

                    }






                }, error: function () {


                }
            });

            $('#select_company').addClass('hide')

            $('#registration_form').removeClass('hide')


        });


        $("#select_main_country").on("click", "#company_fork_step_2", function () {
            $('#select_main_country').addClass('hide')
            $('#select_type_company').removeClass('hide')
        });

        $("#select_type_company").on("click", "#company_selected", function () {
            $('#select_type_company').addClass('hide')
            const country=$('#main_country_select').val();
            const valid_countries={$search_company_valid_countries};
            if( inArray(country,valid_countries)     ){
                $('#select_company').removeClass('hide')
            }else{
                $('#registration_form').removeClass('hide')
            }

        });

        function inArray(needle, haystack) {
            const length = haystack.length;
            for(let i = 0; i < length; i++) {
                if(haystack[i] == needle) return true;
            }
            return false;
        }


        $("#select_company").on("click", "#bypass_search_company", function () {
            $('#select_company').addClass('hide')

            $('#registration_form').removeClass('hide')


        });




        $("#select_type_company").on("click", "#sole_trader_selected", function () {

            $('#company_field').addClass('hide')
            $('#registration_number_field').addClass('hide')



            $('#select_type_company').addClass('hide')

            $('#registration_form').removeClass('hide')


        });


        $("#main_country_select").change(function () {

            let selected = $("#main_country_select").val()


            $('#country_select').val(selected).trigger('change');


        });


    </script>
{/if}