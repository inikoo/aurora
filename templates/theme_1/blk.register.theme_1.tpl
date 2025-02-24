{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 13 April 2018 at 17:27:42 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3
-->
*}

{if isset($data.top_margin)}{assign "top_margin" $data.top_margin}{else}{assign "top_margin" "20"}{/if}
{if isset($data.bottom_margin)}{assign "bottom_margin" $data.bottom_margin}{else}{assign "bottom_margin" "20"}{/if}


<div id="input_editor" class="hide" style="z-index:100;position:absolute;padding:10px;border:1px solid #ccc;background-color: #fff;width:560px">
    <table style="width:100%;">
        <tr>
            <td>{t}Placeholder{/t}
            </td>
            <td><input id="input_editor_placeholder" style="width:100%"/>
            </td>

        </tr>
        <tr>
            <td>{t}Tooltip{/t}
            </td>
            <td><input id="input_editor_tooltip" style="width:100%"/>
            </td>

        </tr>
        <tr>
            <td></td>
            <td style="padding-right:10px;text-align: right"><span style="cursor:pointer" onclick="save_edit_input()"><i class="fa fa-check "></i>&nbsp; {t}Ok{/t}</span>
            </td>
        </tr>
    </table>
</div>

<div id="address_labels_editor" class="hide" style="z-index:100;position:absolute;padding:10px;border:1px solid #ccc;background-color: #fff;width:560px">
    <table style="width:100%;">
        <tr>
            <td>{t}Address Line 1{/t}</td>
            <td><input id="address_addressLine1" class="website_localized_label" style="width:100%"
                       value="{if isset($labels.address_addressLine1) and $labels.address_addressLine1!=''}{$labels.address_addressLine1}{else}{t}Address Line 1{/t}{/if}"/></td>
        </tr>
        <tr>
            <td>{t}Address Line 2{/t}</td>
            <td><input id="address_addressLine2" class="website_localized_label" style="width:100%"
                       value="{if isset($labels.address_addressLine2) and $labels.address_addressLine2!=''}{$labels.address_addressLine2}{else}{t}Address Line 2{/t}{/if}"/></td>
        </tr>


        <tr>
            <td colspan="2" style="border-bottom:1px solid #eee;padding-top:5px">{t}Dependent locality (City divisions){/t}</td>
        </tr>
        <tr style="height: 5px">
            <td colspan="2"></td>
        </tr>

        <tr>
            <td>{t}Neighborhood{/t}</td>
            <td><input id="dependentLocality_neighborhood" class="website_localized_label" style="width:100%"
                       value="{if isset($labels.dependentLocality_neighborhood) and $labels.dependentLocality_neighborhood!=''}{$labels.dependentLocality_neighborhood}{else}{t}Neighborhood{/t}{/if}"/></td>
        </tr>
        <tr>
            <td>{t}District{/t}</td>
            <td><input id="dependentLocality_district" class="website_localized_label" style="width:100%"
                       value="{if isset($labels.dependentLocality_district) and $labels.dependentLocality_district!=''}{$labels.dependentLocality_district}{else}{t}District{/t}{/if}"/></td>
        </tr>
        <tr>
            <td>{t}Townland{/t}</td>
            <td><input id="dependentLocality_townland" class="website_localized_label" style="width:100%"
                       value="{if isset($labels.dependentLocality_townland) and $labels.dependentLocality_townland!=''}{$labels.dependentLocality_townland}{else}{t}Townland{/t}{/if}"/></td>
        </tr>

        <tr>
            <td>{t}Village (Township){/t}</td>
            <td><input id="dependentLocality_village_township" class="website_localized_label" style="width:100%"
                       value="{if isset($labels.dependentLocality_village_township) and $labels.dependentLocality_village_township!=''}{$labels.dependentLocality_village_township}{else}{t}Village (Township){/t}{/if}"/>
            </td>
        </tr>

        <tr>
            <td>{t}Suburb{/t}</td>
            <td><input id="dependentLocality_suburb" class="website_localized_label" style="width:100%"
                       value="{if isset($labels.dependentLocality_suburb) and $labels.dependentLocality_suburb!=''}{$labels.dependentLocality_suburb}{else}{t}Suburb{/t}{/if}"/></td>
        </tr>


        <tr>
            <td colspan="2" style="border-bottom:1px solid #eee;padding-top:5px">{t}Locality (City){/t}</td>
        </tr>
        <tr style="height: 5px">
            <td colspan="2"></td>
        </tr>

        <tr>
            <td>{t}City{/t}</td>
            <td><input id="locality_city" class="website_localized_label" style="width:100%" value="{if isset($labels.locality_city) and $labels.locality_city!=''}{$labels.locality_city}{else}{t}City{/t}{/if}"/></td>
        </tr>
        <tr>
            <td>{t}Suburb{/t}</td>
            <td><input id="locality_suburb" class="website_localized_label" style="width:100%"
                       value="{if isset($labels.locality_suburb) and $labels.locality_suburb!=''}{$labels.locality_suburb}{else}{t}Suburb{/t}{/if}"/></td>
        </tr>
        <tr>
            <td>{t}District{/t}</td>
            <td><input id="locality_district" class="website_localized_label" style="width:100%"
                       value="{if isset($labels.locality_district) and $labels.locality_district!=''}{$labels.locality_district}{else}{t}District{/t}{/if}"/></td>
        </tr>

        <tr>
            <td>{t}Post town{/t}</td>
            <td><input id="locality_post_town" class="website_localized_label" style="width:100%"
                       value="{if !empty($labels.locality_post_town)}{$labels.locality_post_town}{else}{t}Post town{/t}{/if}"/></td>
        </tr>


        <tr>
            <td colspan="2" style="border-bottom:1px solid #eee;padding-top:5px">{t}Country administrative divisions{/t}</td>
        </tr>
        <tr style="height: 5px">
            <td colspan="2"></td>
        </tr>

        <tr>
            <td>{t}State{/t}</td>
            <td><input id="administrativeArea_state" class="website_localized_label" style="width:100%"
                       value="{if isset($labels.administrativeArea_state) and $labels.administrativeArea_state!=''}{$labels.administrativeArea_state}{else}{t}State{/t}{/if}"/></td>
        </tr>
        <tr>
            <td>{t}Province{/t}</td>
            <td><input id="administrativeArea_province" class="website_localized_label" style="width:100%"
                       value="{if isset($labels.administrativeArea_province) and $labels.administrativeArea_province!=''}{$labels.administrativeArea_province}{else}{t}Province{/t}{/if}"/></td>
        </tr>
        <tr>
            <td>{t}Island{/t}</td>
            <td><input id="administrativeArea_island" class="website_localized_label" style="width:100%"
                       value="{if isset($labels.administrativeArea_island) and $labels.administrativeArea_island!=''}{$labels.administrativeArea_island}{else}{t}Island{/t}{/if}"/></td>
        </tr>
        <tr>
            <td>{t}Department{/t}</td>
            <td><input id="administrativeArea_department" class="website_localized_label" style="width:100%"
                       value="{if isset($labels.administrativeArea_department) and $labels.administrativeArea_department!=''}{$labels.administrativeArea_department}{else}{t}Department{/t}{/if}"/></td>
        </tr>
        <tr>
            <td>{t}County{/t}</td>
            <td><input id="administrativeArea_county" class="website_localized_label" style="width:100%"
                       value="{if isset($labels.administrativeArea_county) and $labels.administrativeArea_county!=''}{$labels.administrativeArea_county}{else}{t}County{/t}{/if}"/></td>
        </tr>
        <tr>
            <td>{t}Area{/t}</td>
            <td><input id="administrativeArea_area" class="website_localized_label" style="width:100%"
                       value="{if isset($labels.administrativeArea_area) and $labels.administrativeArea_area!=''}{$labels.administrativeArea_area}{else}{t}Area{/t}{/if}"/></td>
        </tr>
        <tr>
            <td>{t}Prefecture{/t}</td>
            <td><input id="administrativeArea_prefecture" class="website_localized_label" style="width:100%"
                       value="{if isset($labels.administrativeArea_prefecture) and $labels.administrativeArea_prefecture!=''}{$labels.administrativeArea_prefecture}{else}{t}Prefecture{/t}{/if}"/></td>
        </tr>

        <tr>
            <td>{t}District{/t}</td>
            <td><input id="administrativeArea_district" class="website_localized_label" style="width:100%"
                       value="{if isset($labels.administrativeArea_district) and $labels.administrativeArea_district!=''}{$labels.administrativeArea_district}{else}{t}District{/t}{/if}"/></td>
        </tr>
        <tr>
            <td>{t}Emirate{/t}</td>
            <td><input id="administrativeArea_emirate" class="website_localized_label" style="width:100%"
                       value="{if isset($labels.administrativeArea_emirate) and $labels.administrativeArea_emirate!=''}{$labels.administrativeArea_emirate}{else}{t}Emirate{/t}{/if}"/></td>
        </tr>


        <tr style="height: 15px">
            <td colspan="2" style="border-bottom:1px solid #eee"></td>
        </tr>
        <tr style="height: 5px">
            <td colspan="2"></td>
        </tr>
        <tr>
            <td>{t}Postal code{/t}</td>
            <td><input id="postalCode_postal" class="website_localized_label" style="width:100%"
                       value="{if isset($labels.postalCode_postal) and $labels.postalCode_postal!=''}{$labels.postalCode_postal}{else}{t}Postal code{/t}{/if}"/></td>
        </tr>
        <tr>
        <tr>
            <td>{t}Sorting code{/t}</td>
            <td><input id="address_sorting_code" class="website_localized_label" style="width:100%"
                       value="{if isset($labels.address_sorting_code) and $labels.address_sorting_code!=''}{$labels.address_sorting_code}{else}{t}Sorting code{/t}{/if}"/></td>
        </tr>
        <tr>
            <td>{t}Country{/t}</td>
            <td><input id="address_country" class="website_localized_label" style="width:100%"
                       value="{if isset($labels.address_country) and $labels.address_country!=''}{$labels.address_country}{else}{t}Country{/t}{/if}"/></td>
        </tr>

        <tr>
            <td></td>
            <td style="padding-right:10px;text-align: right"><span style="cursor:pointer" onclick="save_address_labels()"><i class="fa fa-check "></i>&nbsp; {t}Ok{/t}</span>
            </td>
        </tr>
    </table>
</div>


<div id="block_{$key}" data-block_key="{$key}" block="{$data.type}" class="{$data.type} _block {if !$data.show}hide{/if}" top_margin="{$top_margin}" bottom_margin="{$bottom_margin}"
     style="padding-top:{$top_margin}px;padding-bottom:{$bottom_margin}px">


    {if empty($data.registration_type) }{assign type 'simple'}{else}{assign type $data.registration_type}{/if}


    <div class="reg_form">
        <form id="sky-form" class="sky-form"  data-type="{$type}"  >
            <header id="_title" contenteditable="true">{$data.labels._title}</header>


            <fieldset class="company_fork {if $type!='company_fork'}hide{/if}">


                    <div  id="_main_select_country_title" contenteditable="true"  style="margin:15px">{if empty($data.labels._main_select_country_title)}Select country{else}{$data.labels._main_select_country_title}{/if}</div>



                <section class="col col-6">

                    <label class="select">
                        <select id="main_country_select" name="country">
                            <option value="0" selected disabled>{if isset($labels.address_country) and $labels.address_country!=''}{$labels.address_country}{else}{t}Country{/t}{/if}</option>

                            {foreach from=$countries item=country}
                                <option value="{$country.2alpha}" {if $country.2alpha==$selected_country}selected{/if} >{$country.name}</option>
                            {/foreach}


                            <select><i></i>
                    </label>
                </section>

                <section class="col col-4">
                    <button id="_main_select_country_button"  contenteditable="true"  style="position: relative;top:-12px" class="button">{if empty($data.labels._main_select_country_button)}Continue{else}{$data.labels._main_select_country_button}{/if}</button>

                </section>

            </fieldset>


            <fieldset   class="company_fork {if $type!='company_fork'}hide{/if}">


                <div id="_select_company" contenteditable="true" style="margin:15px">{if empty($data.labels._select_company)}Select trader type{else}{$data.labels._select_company}{/if}</div>

                <section class="col col-6">
                    <button id="_select_company_sole_trader" contenteditable="true" style="float: none" class="button">{if empty($data.labels._select_company_sole_trader)}Sole Trader{else}{$data.labels._select_company_sole_trader}{/if}</button>

                </section>



                <section class="col col-6">
                    <button id="_select_company_company" contenteditable="true" style="float: none" class="button">{if empty($data.labels._select_company_company)}Company{else}{$data.labels._select_company_company}{/if}</button>

                </section>



            </fieldset>


            <fieldset   class="company_fork {if $type!='company_fork'}hide{/if}">


                <div id="_search_your_company" contenteditable="true" style="margin:15px">{if empty($data.labels._search_your_company)}Search for your company{else}{$data.labels._search_your_company}{/if}</div>


                    <label id="_search_company_form" class="input" style="cursor:pointer" ">
                        <input class="register_field" id="_search_company_form" type="text">
                      
                    </label>

                    <div style="margin-top:20px;margin-bottom:20px;margin-left: 15px;font-size: small">
                        <span id="_continue_no_search" contenteditable="true">{if empty($data.labels._continue_no_search)}If you can not find your company click{else}{$data.labels._continue_no_search}{/if}</span> <span id="_continue_no_search_click_here" contenteditable="true" style="font-weight: 800;cursor: pointer;text-decoration: underline">{if empty($data.labels._continue_no_search_click_here)}here{else}{$data.labels._continue_no_search_click_here}{/if}</span>
                    </div>



            </fieldset>

            <fieldset>


                <section>
                    <label id="_email" class="input " style="cursor:pointer" onclick="show_edit_input(this)">

                        <i class="icon-append far fa-envelope" style="cursor:pointer"></i>
                        <input class="register_field" type="email" name="email" id="_email_placeholder" placeholder="{$data.labels._email_placeholder}">
                        <b id="_email_tooltip" class="tooltip tooltip-bottom-right">{$data.labels._email_tooltip}</b>
                    </label>
                </section>

                <section>
                    <label id="_password" class="input " style="cursor:pointer" onclick="show_edit_input(this)">
                        <i class="icon-append far fa-lock" style=" cursor:pointer" ></i>
                        <input class="register_field" type="password" name="password" id="_password_placeholder" placeholder="{$data.labels._password_placeholder}">
                        <b id="_password_tooltip" class="tooltip tooltip-bottom-right">{$data.labels._password_tooltip}</b>
                    </label>
                </section>

                <section>
                    <label id="_password_confirm" class="input " style="cursor:pointer" onclick="show_edit_input(this)">
                        <i class="icon-append far fa-lock" style="cursor:pointer"></i>
                        <input class="register_field" type="password" name="password_confirm" id="_password_confirm_placeholder" placeholder="{$data.labels._password_confirm_placeholder}">
                        <b id="_password_confirm_tooltip" class="tooltip tooltip-bottom-right">{$data.labels._password_confirm_tooltip}</b>
                    </label>
                </section>
            </fieldset>

            <fieldset>

                <section>
                    <label id="_mobile" class="input " style="cursor:pointer" onclick="show_edit_input(this)">
                        <i class="icon-append far fa-mobile"></i>
                        <input class="register_field" type="text" name="mobile" id="_mobile_placeholder" placeholder="{$data.labels._mobile_placeholder}">
                        <b id="_mobile_tooltip" class="tooltip tooltip-bottom-right">{$data.labels._mobile_tooltip}</b>
                    </label>
                </section>

                <section>
                    <label id="_contact_name" class="input " style="cursor:pointer" onclick="show_edit_input(this)">
                        <i class="icon-append  far fa-user" style="cursor:pointer"></i>

                        <input class="register_field" type="text" name="contact_name" id="_contact_name_placeholder" placeholder="{$data.labels._contact_name_placeholder}">
                        <b id="_contact_name_tooltip" class="tooltip tooltip-bottom-right">{$data.labels._contact_name_tooltip}</b>
                    </label>
                </section>

                <section>
                    <label id="_company" class="input " style="cursor:pointer" onclick="show_edit_input(this)">

                        <i class="icon-append far fa-briefcase" style="cursor:pointer"></i>

                        <input class="register_field" type="text" name="company" id="_company_placeholder" placeholder="{$data.labels._company_placeholder}">
                        <b id="_company_tooltip" class="tooltip tooltip-bottom-right">{$data.labels._company_tooltip}</b>
                    </label>
                </section>

                <div class="row">
                    <section class="col col-6 ">
                        <label id="_tax_number" class="input" style="cursor:pointer" onclick="show_edit_input(this)">
                            <input class="register_field" id="_tax_number" type="text" name="tax_number"
                                   placeholder="{if isset($data.labels._tax_number) and $data.labels._tax_number!=''}{$data.labels._tax_number}{else}{t}Tax number{/t}{/if}">
                            <b id="_tax_number_tooltip"
                               class="tooltip tooltip-bottom-right">{if isset($data.labels._tax_number_tooltip) and $data.labels._tax_number_tooltip!=''}{$data.labels._tax_number_tooltip}{else}{t}Tax number{/t}{/if}</b>

                        </label>
                    </section>
                    <section class="col col-6">
                        <label id="_registration_number" class="input" style="cursor:pointer" onclick="show_edit_input(this)">
                            <input class="register_field" id="_registration_number" type="text" name="registration_number"
                                   placeholder="{if isset($data.labels._registration_number) and $data.labels._registration_number!=''}{$data.labels._registration_number}{else}{t}Registration number{/t}{/if}">
                            <b id="_registration_number_tooltip"
                               class="tooltip tooltip-bottom-right">{if isset($data.labels._registration_number_tooltip) and $data.labels._registration_number_tooltip!=''}{$data.labels._registration_number_tooltip}{else}{t}Registration number{/t}{/if}</b>

                        </label>
                    </section>
                </div>

            </fieldset>

            <fieldset id="address_fields" style="position:relative">


                <section id="addressLine1" class="{if 'addressLine1'|in_array:$used_address_fields}{else}hide{/if}">

                    <label for="file" class="input">
                        <input type="text" name="addressLine1" placeholder="{if isset($labels.address_addressLine1) and $labels.address_addressLine1!=''}{$labels.address_addressLine1}{else}{t}Address Line 1{/t}{/if}">
                        <b class="tooltip tooltip-bottom-right">{if isset($labels.address_addressLine1) and $labels.address_addressLine1!=''}{$labels.address_addressLine1}{else}{t}Address Line 1{/t}{/if}</b>
                    </label>
                </section>

                <section id="addressLine2" class="{if 'addressLine2'|in_array:$used_address_fields}{else}hide{/if}">
                    <label for="file" class="input">
                        <input type="text" name="addressLine2" placeholder="{if isset($labels.address_addressLine2) and $labels.address_addressLine2!=''}{$labels.address_addressLine2}{else}{t}Address Line 2{/t}{/if}">
                        <b class="tooltip tooltip-bottom-right">{if isset($labels.address_addressLine2) and $labels.address_addressLine2!=''}{$labels.address_addressLine2}{else}{t}Address Line 2{/t}{/if}</b>
                    </label>
                </section>


                <div id="sortingCode" class="row {if 'sortingCode'|in_array:$used_address_fields}{else}hide{/if}">
                    <section class="col col-6 ">
                        <label class="input">
                            <input type="text" name="sortingCode" placeholder="{if isset($labels.address_sorting_code) and $labels.address_sorting_code!=''}{$labels.address_sorting_code}{else}{t}Sorting code{/t}{/if}">
                            <b class="tooltip tooltip-bottom-right">{if isset($labels.address_sorting_code) and $labels.address_sorting_code!=''}{$labels.address_sorting_code}{else}{t}Sorting code{/t}{/if}</b>
                        </label>
                    </section>


                </div>

                <div id="postalCode" class="row {if 'postalCode'|in_array:$used_address_fields}{else}hide{/if}">
                    <section class="col col-6 ">
                        <label class="input">
                            <input type="text" name="postalCode"
                                   placeholder="{if !empty($labels["postalCode_`$address_labels.postalCode.code`"]) and $labels["postalCode_`$address_labels.postalCode.code`"]!=''}{$labels["postalCode_`$address_labels.postalCode.code`"]}{else}{$address_labels.postalCode.label}{/if}">
                            <b class="tooltip tooltip-bottom-right">{if isset($labels["postalCode_`$address_labels.postalCode.code`"]) and $labels["postalCode_`$address_labels.postalCode.code`"]!=''}{$labels["postalCode_`$address_labels.postalCode.code`"]}{else}{$address_labels.postalCode.label}{/if}</b>
                        </label>
                    </section>


                </div>

                <div id="dependentLocality" class="row {if 'dependentLocality'|in_array:$used_address_fields}{else}hide{/if}">
                    <section class="col col-6 ">
                        <label class="input">
                            <input type="text" name="dependentLocality"
                                   placeholder="{if isset($labels["dependentLocality_`$address_labels.dependentLocality.code`"]) and $labels["dependentLocality_`$address_labels.dependentLocality.code`"]!=''}{$labels["dependentLocality_`$address_labels.dependentLocality.code`"]}{else}{$address_labels.dependentLocality.label}{/if}">
                            <b class="tooltip tooltip-bottom-right">{if isset($labels["dependentLocality_`$address_labels.dependentLocality.code`"]) and $labels["dependentLocality_`$address_labels.dependentLocality.code`"]!=''}{$labels["dependentLocality_`$address_labels.dependentLocality.code`"]}{else}{$address_labels.dependentLocality.label}{/if}</b>
                        </label>
                    </section>

                </div>

                <div id="locality" class="row {if 'locality'|in_array:$used_address_fields}{else}hide{/if}">
                    <section class="col col-6 ">
                        <label class="input">
                            <input type="text" name="locality"
                                   placeholder="{if !empty($labels["locality_`$address_labels.locality.code`"]) }{$labels["locality_`$address_labels.locality.code`"]}{else}{$address_labels.locality.label}{/if}">
                            <b class="tooltip tooltip-bottom-right">{if isset($labels["locality_`$address_labels.locality.code`"]) and $labels["locality_`$address_labels.locality.code`"]!=''}{$labels["locality_`$address_labels.locality.code`"]}{else}{$address_labels.locality.label}{/if}</b>
                        </label>
                    </section>

                </div>


                <div id="administrativeArea" class="row {if 'administrativeArea'|in_array:$used_address_fields}{else}hide{/if}">
                    <section class="col col-6 ">
                        <label class="input">
                            <input type="text" name="locality"
                                   placeholder="{if isset($labels["administrativeArea_`$address_labels.administrativeArea.code`"]) and $labels["administrativeArea_`$address_labels.administrativeArea.code`"]!=''}{$labels["administrativeArea_`$address_labels.administrativeArea.code`"]}{else}{$address_labels.administrativeArea.label}{/if}">
                            <b class="tooltip tooltip-bottom-right">{if isset($labels["administrativeArea_`$address_labels.administrativeArea.code`"]) and $labels["administrativeArea_`$address_labels.administrativeArea.code`"]!=''}{$labels["administrativeArea_`$address_labels.administrativeArea.code`"]}{else}{$address_labels.administrativeArea.label}{/if}</b>
                        </label>
                    </section>

                </div>


                <div class="row">
                    <section class="col col-5">
                        <label class="select">
                            <select id="country_select" name="country">
                                <option value="0" selected disabled>{if isset($labels.address_country) and $labels.address_country!=''}{$labels.address_country}{else}{t}Country{/t}{/if}</option>

                                {foreach from=$countries item=country}
                                    <option value="{$country.2alpha}" {if $country.2alpha==$selected_country}selected{/if} >{$country.name}</option>
                                {/foreach}


                                <select><i></i>
                        </label>
                    </section>

                    <section class="col col-5">
                        <span style="position:absolute;cursor: pointer" onclick="show_address_labels_editor()"><i class="fa fa-language" aria-hidden="true"></i> {t}Address labels{/t} </span>
                    </section>
                </div>


            </fieldset>

            {if !empty($poll_queries)}
                <fieldset class="poll_queries">
                    <section>

                        <label class="input">
                                        <span id="_poll_info" contenteditable="true">{if empty($data.labels._poll_info)}{t}Please let know you better so we can serve you better{/t}{else}{$data.labels._poll_info}{/if}
                        </label>
                    </section>


                    {foreach from=$poll_queries item=query}

                        <section class="poll_query_section" style="position: relative">
                        <i style="position: absolute;cursor:move" class="far very_discreet fa-hand-rock handle"></i>
                        <label style="margin-left:22px" data-query_key="{$query['Customer Poll Query Key']}" class="label poll_query_label" contenteditable="true">{$query['Customer Poll Query Label']}</label>
                        {if $query['Customer Poll Query Type']=='Open'}

                                <label class="textarea">
                                    <textarea rows="4" name="message" id="message"></textarea>
                                </label>
                            </section>
                        {else}
                            <label class="select">
                                    <select name="gender">
                                        <option value="0" selected disabled>{if !empty($labels._choose_one)}{$labels._choose_one}{else}{t}Please choose one{/t}{/if}</option>

                                        {foreach from=$query['Options'] item=option}
                                            <option value="{$option['Customer Poll Query Option Key']}">{$option['Customer Poll Query Option Label']}</option>
                                        {/foreach}


                                    </select>
                                    <i></i>
                                </label>

                        {/if}
                        </section>
                    {/foreach}


                </fieldset>
            {/if}

            <fieldset class="last">

                <section>
                    <label class="checkbox" style="position:relative;top:-22px;" ><input type="checkbox" name="subscription" id="subscription"><i></i> </label>
                    <span style="margin-left:27px;	font: 14px/1.55 'Open Sans', Helvetica, Arial, sans-serif;position:relative;top:-22px;font-size:15px;color:#404040" id="_subscription"
                          contenteditable="true">{$data.labels._subscription}</span>
                    <label class="checkbox"  style="position:relative;top:-20px;"><input type="checkbox" name="terms" id="terms"><i></i> </label>
                    <span style="margin-left:27px;	font: 14px/1.55 'Open Sans', Helvetica, Arial, sans-serif;position:relative;top:-22px;font-size:15px;color:#404040" id="_terms"
                          contenteditable="true">{$data.labels._terms}</span>


                </section>
            </fieldset>
            <footer>
                <button type="submit" class="button" id="_submit_label" contenteditable="true">{$data.labels._submit_label}</button>
            </footer>
        </form>
    </div>


</div>

<script>

    function update_toggle_form_type(type){

        $('#sky-form').data('type',type)
        if(type=='company_fork'){
            $('.company_fork').removeClass('hide')
        }else{
            $('.company_fork').addClass('hide')

        }

    }

    function show_edit_input(element) {
        offset = $(element).closest('section').offset();
        $('#input_editor').removeClass('hide').offset({
            top: offset.top, left: offset.left - 35
        }).attr('element_id', $(element).attr('id'));
        $('#input_editor_placeholder').val($(element).find('input').attr('placeholder'))
        $('#input_editor_tooltip').val($(element).find('b').html())
    }

    function save_edit_input() {
        $('#input_editor').addClass('hide')
        var element = $('#' + $('#input_editor').attr('element_id'))
        element.find('input').attr('placeholder', $('#input_editor_placeholder').val())
        element.find('b').html($('#input_editor_tooltip').val())
        $('#save_button', window.parent.document).addClass('save button changed valid')
    }

    function show_address_labels_editor() {
        offset_form = $('.reg_form').offset();
        offset_address_fields = $('#address_fields').offset();


        $('#address_labels_editor').removeClass('hide').offset({
            top: offset_address_fields.top, left: offset_form.left
        });

    }

    function save_address_labels() {



        $('#address_labels_editor').addClass('hide')

        $('#save_button', window.parent.document).addClass('save button changed valid')
    }


    $('#address_labels_editor input').on('input propertychange', function () {
        $('#save_button', window.parent.document).addClass('save button changed valid')

    });


    $("#country_select").change(function () {

        var selected = $("#country_select option:selected")
        console.log(selected.val())

        var request = "ar_address.php?tipo=address_format&country_code=" + selected.val() + '&website_key={$website->id}'

        console.log(request)
        $.getJSON(request, function (data) {
            console.log(data)
            $.each(data.hidden_fields, function (index, value) {
                $('#' + value).addClass('hide')

            });

            $.each(data.used_fields, function (index, value) {
                $('#' + value).removeClass('hide')

            });

            $.each(data.labels, function (index, value) {
                $('#' + index).find('input').attr('placeholder', value)
                $('#' + index).find('b').html(value)
                $('#' + index).find('label.label').html(value)
            });


        });


    });


    $('.poll_queries').sortable({

        items:".poll_query_section",
        handle: '.handle',

         stop: function (event, ui) {

             $('#save_button', window.parent.document).addClass('save button changed valid')

         }

    })


</script>





