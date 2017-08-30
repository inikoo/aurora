{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 23 May 2017 at 00:09:52 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3
-->
*}


{include file="theme_1/_head.theme_1.tpl"}


<body>


<div id="input_editor" class="hide" style="z-index:100;position:absolute;padding:10px;border:1px solid #ccc;background-color: #fff;width:560px">
    <table style="width:100%;padding:30px">
        <tr>
            <td style="padding:5px 10px;padding-top:20px">{t}Placeholder{/t}
            </td>
            <td><input id="input_editor_placeholder" style="width:100%"/>
            </td>

        </tr>
        <tr>
            <td style="padding:5px 10px">{t}Tooltip{/t}
            </td>
            <td><input id="input_editor_tooltip" style="width:100%"/>
            </td>

        </tr>
        <tr>
            <td></td>
            <td style="padding:20px"><a onclick="save_edit_input()" class="but_minus"><i class="fa fa-check fa-lg"></i>&nbsp; {t}Done{/t}</a>
            </td>

        </tr>

    </table>


</div>


<div id="address_labels_editor" class="hide" style="z-index:100;position:absolute;padding:10px;border:1px solid #ccc;background-color: #fff;width:560px">
    <table style="width:100%;">
        <tr>
            <td >{t}Address Line 1{/t}</td>
            <td><input id="address_addressLine1" class="website_localized_label" style="width:100%" value="{if isset($labels.address_addressLine1) and $labels.address_addressLine1!=''}{$labels.address_addressLine1}{else}{t}Address Line 1{/t}{/if}" /></td>
        </tr>
        <tr>
            <td >{t}Address Line 2{/t}</td>
            <td><input id="address_addressLine2" class="website_localized_label" style="width:100%" value="{if isset($labels.address_addressLine2) and $labels.address_addressLine2!=''}{$labels.address_addressLine2}{else}{t}Address Line 2{/t}{/if}"/></td>
        </tr>


        <tr>
            <td colspan="2"  style="border-bottom:1px solid #eee;padding-top:5px">{t}Dependent locality (City divisions){/t}</td>
        </tr>
        <tr style="height: 5px">
            <td colspan="2"></td>
        </tr>

        <tr>
            <td >{t}Neighborhood{/t}</td>
            <td><input id="dependentLocality_neighborhood" class="website_localized_label" style="width:100%" value="{if isset($labels.dependentLocality_neighborhood) and $labels.dependentLocality_neighborhood!=''}{$labels.dependentLocality_neighborhood}{else}{t}Neighborhood{/t}{/if}" /></td>
        </tr>
        <tr>
            <td >{t}District{/t}</td>
            <td><input id="dependentLocality_district" class="website_localized_label" style="width:100%" value="{if isset($labels.dependentLocality_district) and $labels.dependentLocality_district!=''}{$labels.dependentLocality_district}{else}{t}District{/t}{/if}" /></td>
        </tr>
        <tr>
            <td >{t}Townland{/t}</td>
            <td><input id="dependentLocality_townland" class="website_localized_label" style="width:100%" value="{if isset($labels.dependentLocality_townland) and $labels.dependentLocality_townland!=''}{$labels.dependentLocality_townland}{else}{t}Townland{/t}{/if}" /></td>
        </tr>

        <tr>
            <td >{t}Village (Township){/t}</td>
            <td><input id="dependentLocality_village_township" class="website_localized_label" style="width:100%" value="{if isset($labels.dependentLocality_village_township) and $labels.dependentLocality_village_township!=''}{$labels.dependentLocality_village_township}{else}{t}Village (Township){/t}{/if}" /></td>
        </tr>

        <tr>
            <td >{t}Suburb{/t}</td>
            <td><input id="dependentLocality_suburb" class="website_localized_label" style="width:100%" value="{if isset($labels.dependentLocality_suburb) and $labels.dependentLocality_suburb!=''}{$labels.dependentLocality_suburb}{else}{t}Suburb{/t}{/if}" /></td>
        </tr>


        <tr>
            <td colspan="2"  style="border-bottom:1px solid #eee;padding-top:5px">{t}Locality (City){/t}</td>
        </tr>
        <tr style="height: 5px">
            <td colspan="2"></td>
        </tr>

        <tr>
            <td >{t}City{/t}</td>
            <td><input id="locality_city" class="website_localized_label" style="width:100%" value="{if isset($labels.locality_city) and $labels.locality_city!=''}{$labels.locality_city}{else}{t}City{/t}{/if}" /></td>
        </tr>
        <tr>
            <td >{t}Suburb{/t}</td>
            <td><input id="locality_suburb" class="website_localized_label" style="width:100%" value="{if isset($labels.locality_suburb) and $labels.locality_suburb!=''}{$labels.locality_suburb}{else}{t}Suburb{/t}{/if}" /></td>
        </tr>
        <tr>
            <td >{t}District{/t}</td>
            <td><input id="locality_district" class="website_localized_label" style="width:100%" value="{if isset($labels.locality_district) and $labels.locality_district!=''}{$labels.locality_district}{else}{t}District{/t}{/if}" /></td>
        </tr>

        <tr>
            <td >{t}Post town{/t}</td>
            <td><input id="locality_post_town" class="website_localized_label" style="width:100%" value="{if isset($labels.locality_post_town) and $labels.locality_post_town!=''}{$labels.locality_post_town}{else}{t}Post town{/t}{/if}" /></td>
        </tr>



        <tr>
            <td colspan="2"  style="border-bottom:1px solid #eee;padding-top:5px">{t}Country administrative divisions{/t}</td>
        </tr>
        <tr style="height: 5px">
            <td colspan="2"></td>
        </tr>

        <tr>
            <td >{t}State{/t}</td>
            <td><input id="administrativeArea_state" class="website_localized_label" style="width:100%" value="{if isset($labels.administrativeArea_state) and $labels.administrativeArea_state!=''}{$labels.administrativeArea_state}{else}{t}State{/t}{/if}" /></td>
        </tr>
        <tr>
            <td >{t}Province{/t}</td>
            <td><input id="administrativeArea_province" class="website_localized_label" style="width:100%" value="{if isset($labels.administrativeArea_province) and $labels.administrativeArea_province!=''}{$labels.administrativeArea_province}{else}{t}Province{/t}{/if}" /></td>
        </tr>
        <tr>
            <td >{t}Island{/t}</td>
            <td><input id="administrativeArea_island" class="website_localized_label" style="width:100%" value="{if isset($labels.administrativeArea_island) and $labels.administrativeArea_island!=''}{$labels.administrativeArea_island}{else}{t}Island{/t}{/if}" /></td>
        </tr>
        <tr>
            <td >{t}Department{/t}</td>
            <td><input id="administrativeArea_department" class="website_localized_label" style="width:100%" value="{if isset($labels.administrativeArea_department) and $labels.administrativeArea_department!=''}{$labels.administrativeArea_department}{else}{t}Department{/t}{/if}" /></td>
        </tr>
        <tr>
            <td >{t}County{/t}</td>
            <td><input id="administrativeArea_county" class="website_localized_label" style="width:100%" value="{if isset($labels.administrativeArea_county) and $labels.administrativeArea_county!=''}{$labels.administrativeArea_county}{else}{t}County{/t}{/if}" /></td>
        </tr>
        <tr>
            <td >{t}Area{/t}</td>
            <td><input id="administrativeArea_area" class="website_localized_label" style="width:100%" value="{if isset($labels.administrativeArea_area) and $labels.administrativeArea_area!=''}{$labels.administrativeArea_area}{else}{t}Area{/t}{/if}" /></td>
        </tr>
        <tr>
            <td >{t}Prefecture{/t}</td>
            <td><input id="administrativeArea_prefecture" class="website_localized_label" style="width:100%" value="{if isset($labels.administrativeArea_prefecture) and $labels.administrativeArea_prefecture!=''}{$labels.administrativeArea_prefecture}{else}{t}Prefecture{/t}{/if}" /></td>
        </tr>

        <tr>
            <td >{t}District{/t}</td>
            <td><input id="administrativeArea_district" class="website_localized_label" style="width:100%" value="{if isset($labels.administrativeArea_district) and $labels.administrativeArea_district!=''}{$labels.administrativeArea_district}{else}{t}District{/t}{/if}" /></td>
        </tr>
        <tr>
            <td >{t}Emirate{/t}</td>
            <td><input id="administrativeArea_emirate" class="website_localized_label" style="width:100%" value="{if isset($labels.administrativeArea_emirate) and $labels.administrativeArea_emirate!=''}{$labels.administrativeArea_emirate}{else}{t}Emirate{/t}{/if}" /></td>
        </tr>




        <tr style="height: 15px">
            <td colspan="2" style="border-bottom:1px solid #eee"></td>
        </tr>
        <tr style="height: 5px">
            <td colspan="2"></td>
        </tr>
        <tr>
            <td >{t}Postal code{/t}</td>
            <td><input id="postalCode_postal" class="website_localized_label" style="width:100%" value="{if isset($labels.postalCode_postal) and $labels.postalCode_postal!=''}{$labels.postalCode_postal}{else}{t}Postal code{/t}{/if}" /></td>
        </tr>
        <tr>
        <tr>
            <td >{t}Sorting code{/t}</td>
            <td><input id="address_sorting_code" class="website_localized_label" style="width:100%" value="{if isset($labels.address_sorting_code) and $labels.address_sorting_code!=''}{$labels.address_sorting_code}{else}{t}Sorting code{/t}{/if}" /></td>
        </tr>
        <tr>
            <td >{t}Country{/t}</td>
            <td><input id="address_country" class="website_localized_label" style="width:100%" value="{if isset($labels.address_country) and $labels.address_country!=''}{$labels.address_country}{else}{t}Country{/t}{/if}" /></td>
        </tr>

        <tr>
            <td></td>
            <td style="padding-right:10px;text-align: right"><span style="cursor:pointer" onclick="save_address_labels()" ><i class="fa fa-check "></i>&nbsp; {t}Ok{/t}</span>
            </td>
        </tr>
    </table>
</div>



<div class="wrapper_boxed">

    <div class="site_wrapper">

        <div class="content_fullwidth less2">
            <div class="container">



                <div class="left_sidebar">

                    <div class="sidebar_widget">


                        <div class="sidebar_title"><h4 id="_customer_profile_title" contenteditable="true">{$content._customer_profile_title}</h4></div>

                        <ul class="arrows_list1">

                            <li>
                                <span class="block_link    selected">
                                    <i class="fa fa-angle-right"></i>
                                    <span contenteditable="true" class="_contact_details_title">{$content._contact_details_title}</span>
                                    <i block="_contact_details" onClick="change_block(this)" class="padding_left_10 fa like_button fa-arrow-right"></i>
                                </span>
                            </li>
                            <li>
                                <span class="block_link ">
                                    <i class="fa fa-angle-right"></i>
                                    <span contenteditable="true" class="_login_details_title">{$content._login_details_title}</span>
                                    <i block="_login_details" onClick="change_block(this)" class="padding_left_10 fa like_button fa-arrow-right"></i>
                                </span>
                            </li>

                            <li>
                                <span class="block_link like_button">
                                    <i class="fa fa-angle-right"></i>
                                    <span id="_invoice_address_title" contenteditable="true" class="_invoice_address_title">{$content._invoice_address_title}</span>
                                    <i block="_invoice_address_details" onClick="change_block(this)" class="padding_left_10 fa like_button fa-arrow-right"></i>
                                    </span>
                            </li>

                            <li>
                                <span class="block_link like_button">
                                    <i class="fa fa-angle-right"></i>
                                    <span id="_delivery_addresses_title" co contenteditable="true" class="_delivery_addresses_title">{$content._delivery_addresses_title}</span>
                                    <i block="_delivery_addresses_details" onClick="change_block(this)" class="padding_left_10 fa like_button fa-arrow-right"></i>
                                    </span>
                            </li>



                        </ul>

                        <div class="clearfix marb3"></div>
                        <div class="sidebar_title"><h4 id="_customer_orders_title" contenteditable="true">{$content._customer_orders_title}</h4></div>

                        <ul class="arrows_list1">
                            <li>
                                <span class="block_link    selected">
                                    <i class="fa fa-angle-right"></i>
                                    <span contenteditable="true" class="_orders_title">{$content._orders_title}</span>
                                    <i block="_orders" onClick="change_block(this)" class="padding_left_10 fa like_button fa-arrow-right"></i>
                                </span>
                            </li>



                        </ul>


                    </div><!-- end section -->

                </div>


                <div class="content_right">


                    <div id="_contact_details" class="block reg_form">
                        <form id="sky-form" class="sky-form">
                            <header class="mirror_master" id="_contact_details_title" contenteditable="true">{$content._contact_details_title}</header>

                            <fieldset>


                                <section>
                                    <label id="_company_label"  contenteditable="true" class="label">{$content._company_label}</label>

                                    <label class="input">
                                        <i id="company" onclick="show_edit_input(this)" class="icon-append icon-briefcase"></i>
                                        <input class="register_field" type="text" name="company" id="_company_placeholder" placeholder="{$content._company_placeholder}">
                                        <b id="_company_tooltip" class="tooltip tooltip-bottom-right">{$content._company_tooltip}</b>
                                    </label>
                                </section>


                                <section>
                                    <label id="_contact_name_label"  contenteditable="true" class="label">{$content._contact_name_label}</label>

                                    <label class="input">
                                        <i id="contact_name" onclick="show_edit_input(this)" class="icon-append icon-user"></i>
                                        <input class="register_field" type="text" name="contact_name" id="_contact_name_placeholder" placeholder="{$content._contact_name_placeholder}">
                                        <b id="_contact_name_tooltip" class="tooltip tooltip-bottom-right">{$content._contact_name_tooltip}</b>
                                    </label>
                                </section>


                                <section>
                                    <label id="_mobile_label"  contenteditable="true" class="label">{$content._mobile_label}</label>

                                    <label class="input">
                                        <i id="_mobile" onclick="show_edit_input(this)" class="icon-append icon-mobile-phone"></i>
                                        <input class="register_field" type="text" name="mobile" id="_mobile_placeholder" placeholder="{$content._mobile_placeholder}">
                                        <b id="_mobile_tooltip" class="tooltip tooltip-bottom-right">{$content._mobile_tooltip}</b>
                                    </label>
                                </section>

                                <section>
                                    <label id="_email_label"  contenteditable="true" class="label">{$content._email_label}</label>

                                    <label class="input">
                                        <i id="_email" onclick="show_edit_input(this)" class="icon-append icon-envelope-alt"></i>
                                        <input class="register_field" type="email" name="email" id="_email_placeholder" placeholder="{$content._email_placeholder}">
                                        <b id="_email_tooltip" class="tooltip tooltip-bottom-right">{$content._email_tooltip}</b>
                                    </label>
                                </section>

                            </fieldset>


                            <fieldset>
                                <section>
                                    <label id="_registration_number_label"  contenteditable="true" class="label">{$content._registration_number_label}</label>

                                    <label class="input">
                                        <i id="_registration_number" onclick="show_edit_input(this)" class="icon-append icon-gavel"><i class="fa fa-building-o" aria-hidden="true"></i>
                                        </i>
                                        <input class="register_field" type="text" name="registration_number" id="_registration_number_placeholder" placeholder="{$content._registration_number_placeholder}">
                                        <b id="_registration_number_tooltip" class="tooltip tooltip-bottom-right">{$content._registration_number_tooltip}</b>
                                    </label>
                                </section>

                                <section>
                                    <label id="_tax_number_label"  contenteditable="true" class="label">{$content._tax_number_label}</label>

                                    <label class="input">
                                        <i id="_tax_number" onclick="show_edit_input(this)" class="icon-append icon-gavel"><i class="fa fa-gavel" aria-hidden="true"></i>
                                        </i>
                                        <input class="register_field" type="text" name="tax_number" id="_tax_number_placeholder" placeholder="{$content._tax_number_placeholder}">
                                        <b id="_tax_number_tooltip" class="tooltip tooltip-bottom-right">{$content._tax_number_tooltip}</b>
                                    </label>
                                </section>


                            </fieldset>


                            <footer>
                                <button type="submit" class="button " id="_save_contact_details_label" contenteditable="true">{$content._save_contact_details_label}</button>
                            </footer>
                        </form>
                    </div>
                    <div id="_login_details" class="block hide reg_form">
                        <form id="sky-form" class="sky-form">
                            <header class="mirror_master" id="_login_details_title" contenteditable="true">{$content._login_details_title}</header>

                            <fieldset>
                                <section>

                                    <label class="input">
                                        <span id="_username_info" contenteditable="true">{$content._username_info}
                                    </label>
                                </section>

                                <section>
                                    <label id="_password_label"  contenteditable="true" class="label">{$content._password_label}</label>

                                    <label class="input">
                                        <i id="_password" onclick="show_edit_input(this)" class="icon-append icon-lock"></i>
                                        <input class="register_field" type="password" name="password" id="_password_placeholder" placeholder="{$content._password_placeholder}">
                                        <b id="_password_tooltip" class="tooltip tooltip-bottom-right">{$content._password_tooltip}</b>
                                    </label>
                                </section>

                                <section>
                                    <label id="_password_confirm_label"  contenteditable="true" class="label">{$content._password_confirm_label}</label>

                                    <label class="input">
                                        <i id="_password_conform" onclick="show_edit_input(this)" class="icon-append icon-lock"></i>
                                        <input class="register_field" type="password" name="password" id="_password_confirm_placeholder" placeholder="{$content._password_confirm_placeholder}">
                                        <b id="_password_conform_tooltip" class="tooltip tooltip-bottom-right">{$content._password_conform_tooltip}</b>
                                    </label>
                                </section>


                            </fieldset>
                            <footer>
                                <button type="submit" class="button " id="_save_login_details_label" contenteditable="true">{$content._save_login_details_label}</button>
                            </footer>
                        </form>
                    </div>

                    <div id="_invoice_address_details" class="block hide reg_form">
                        <form id="sky-form" class="sky-form">
                            <header id="_invoice_address_title" contenteditable="true" class="_invoice_address_title" >{$content._invoice_address_title}</header>



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
                                    <section class="col col-6 " >
                                        <label class="input">
                                            <input type="text" name="sortingCode" placeholder="{if isset($labels.address_sorting_code) and $labels.address_sorting_code!=''}{$labels.address_sorting_code}{else}{t}Sorting code{/t}{/if}">
                                            <b class="tooltip tooltip-bottom-right">{if isset($labels.address_sorting_code) and $labels.address_sorting_code!=''}{$labels.address_sorting_code}{else}{t}Sorting code{/t}{/if}</b>
                                        </label>
                                    </section>


                                </div>

                                <div id="postalCode" class="row {if 'postalCode'|in_array:$used_address_fields}{else}hide{/if}">
                                    <section class="col col-6 " >
                                        <label class="input">
                                            <input type="text" name="postalCode" placeholder="{if isset($labels["postalCode_`$address_labels.postalCode.code`"]) and $labels["postalCode_`$address_labels.postalCode.code`"]!=''}{$labels["postalCode_`$address_labels.postalCode.code`"]}{else}{$address_labels.postalCode.label}{/if}">
                                            <b class="tooltip tooltip-bottom-right">{if isset($labels["postalCode_`$address_labels.postalCode.code`"]) and $labels["postalCode_`$address_labels.postalCode.code`"]!=''}{$labels["postalCode_`$address_labels.postalCode.code`"]}{else}{$address_labels.postalCode.label}{/if}</b>
                                        </label>
                                    </section>


                                </div>

                                <div id="dependentLocality" class="row {if 'dependentLocality'|in_array:$used_address_fields}{else}hide{/if}">
                                    <section class="col col-6 " >
                                        <label class="input">
                                            <input type="text" name="dependentLocality" placeholder="{if isset($labels["dependentLocality_`$address_labels.dependentLocality.code`"]) and $labels["dependentLocality_`$address_labels.dependentLocality.code`"]!=''}{$labels["dependentLocality_`$address_labels.dependentLocality.code`"]}{else}{$address_labels.dependentLocality.label}{/if}">
                                            <b class="tooltip tooltip-bottom-right">{if isset($labels["dependentLocality_`$address_labels.dependentLocality.code`"]) and $labels["dependentLocality_`$address_labels.dependentLocality.code`"]!=''}{$labels["dependentLocality_`$address_labels.dependentLocality.code`"]}{else}{$address_labels.dependentLocality.label}{/if}</b>
                                        </label>
                                    </section>

                                </div>

                                <div id="locality" class="row {if 'locality'|in_array:$used_address_fields}{else}hide{/if}">
                                    <section class="col col-6 " >
                                        <label class="input">
                                            <input type="text" name="locality" placeholder="{if isset($labels["locality_`$address_labels.locality.code`"]) and $labels["locality_`$address_labels.locality.code`"]!=''}{$labels["locality_`$address_labels.locality.code`"]}{else}{$address_labels.locality.label}{/if}">
                                            <b class="tooltip tooltip-bottom-right">{if isset($labels["locality_`$address_labels.locality.code`"]) and $labels["locality_`$address_labels.locality.code`"]!=''}{$labels["locality_`$address_labels.locality.code`"]}{else}{$address_labels.locality.label}{/if}</b>
                                        </label>
                                    </section>

                                </div>


                                <div id="administrativeArea" class="row {if 'administrativeArea'|in_array:$used_address_fields}{else}hide{/if}">
                                    <section class="col col-6 " >
                                        <label class="input">
                                            <input type="text" name="locality" placeholder="{if isset($labels["administrativeArea_`$address_labels.administrativeArea.code`"]) and $labels["administrativeArea_`$address_labels.administrativeArea.code`"]!=''}{$labels["administrativeArea_`$address_labels.administrativeArea.code`"]}{else}{$address_labels.administrativeArea.label}{/if}">
                                            <b class="tooltip tooltip-bottom-right">{if isset($labels["administrativeArea_`$address_labels.administrativeArea.code`"]) and $labels["administrativeArea_`$address_labels.administrativeArea.code`"]!=''}{$labels["administrativeArea_`$address_labels.administrativeArea.code`"]}{else}{$address_labels.administrativeArea.label}{/if}</b>
                                        </label>
                                    </section>

                                </div>


                                <div class="row" >
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
                                        <span style="position:absolute;cursor: pointer" onclick="show_address_labels_editor()" ><i  class="fa fa-language" aria-hidden="true"></i>  {t}Address labels{/t} </span>
                                    </section>
                                </div>


                            </fieldset>


                            <footer>
                                <button type="submit" class="button" id="_save_invoice_address_details_label" contenteditable="true">{$content._save_invoice_address_details_label}</button>
                            </footer>
                        </form>

                    </div>

                    <div id="_delivery_addresses_details" class="block hide reg_form">

                        <form id="sky-form" class="sky-form">
                            <header id="_delivery_addresses_title" contenteditable="true" class="_delivery_addresses_title" >{$content._delivery_addresses_title}</header>



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
                                    <section class="col col-6 " >
                                        <label class="input">
                                            <input type="text" name="sortingCode" placeholder="{if isset($labels.address_sorting_code) and $labels.address_sorting_code!=''}{$labels.address_sorting_code}{else}{t}Sorting code{/t}{/if}">
                                            <b class="tooltip tooltip-bottom-right">{if isset($labels.address_sorting_code) and $labels.address_sorting_code!=''}{$labels.address_sorting_code}{else}{t}Sorting code{/t}{/if}</b>
                                        </label>
                                    </section>


                                </div>

                                <div id="postalCode" class="row {if 'postalCode'|in_array:$used_address_fields}{else}hide{/if}">
                                    <section class="col col-6 " >
                                        <label class="input">
                                            <input type="text" name="postalCode" placeholder="{if isset($labels["postalCode_`$address_labels.postalCode.code`"]) and $labels["postalCode_`$address_labels.postalCode.code`"]!=''}{$labels["postalCode_`$address_labels.postalCode.code`"]}{else}{$address_labels.postalCode.label}{/if}">
                                            <b class="tooltip tooltip-bottom-right">{if isset($labels["postalCode_`$address_labels.postalCode.code`"]) and $labels["postalCode_`$address_labels.postalCode.code`"]!=''}{$labels["postalCode_`$address_labels.postalCode.code`"]}{else}{$address_labels.postalCode.label}{/if}</b>
                                        </label>
                                    </section>


                                </div>

                                <div id="dependentLocality" class="row {if 'dependentLocality'|in_array:$used_address_fields}{else}hide{/if}">
                                    <section class="col col-6 " >
                                        <label class="input">
                                            <input type="text" name="dependentLocality" placeholder="{if isset($labels["dependentLocality_`$address_labels.dependentLocality.code`"]) and $labels["dependentLocality_`$address_labels.dependentLocality.code`"]!=''}{$labels["dependentLocality_`$address_labels.dependentLocality.code`"]}{else}{$address_labels.dependentLocality.label}{/if}">
                                            <b class="tooltip tooltip-bottom-right">{if isset($labels["dependentLocality_`$address_labels.dependentLocality.code`"]) and $labels["dependentLocality_`$address_labels.dependentLocality.code`"]!=''}{$labels["dependentLocality_`$address_labels.dependentLocality.code`"]}{else}{$address_labels.dependentLocality.label}{/if}</b>
                                        </label>
                                    </section>

                                </div>

                                <div id="locality" class="row {if 'locality'|in_array:$used_address_fields}{else}hide{/if}">
                                    <section class="col col-6 " >
                                        <label class="input">
                                            <input type="text" name="locality" placeholder="{if isset($labels["locality_`$address_labels.locality.code`"]) and $labels["locality_`$address_labels.locality.code`"]!=''}{$labels["locality_`$address_labels.locality.code`"]}{else}{$address_labels.locality.label}{/if}">
                                            <b class="tooltip tooltip-bottom-right">{if isset($labels["locality_`$address_labels.locality.code`"]) and $labels["locality_`$address_labels.locality.code`"]!=''}{$labels["locality_`$address_labels.locality.code`"]}{else}{$address_labels.locality.label}{/if}</b>
                                        </label>
                                    </section>

                                </div>


                                <div id="administrativeArea" class="row {if 'administrativeArea'|in_array:$used_address_fields}{else}hide{/if}">
                                    <section class="col col-6 " >
                                        <label class="input">
                                            <input type="text" name="locality" placeholder="{if isset($labels["administrativeArea_`$address_labels.administrativeArea.code`"]) and $labels["administrativeArea_`$address_labels.administrativeArea.code`"]!=''}{$labels["administrativeArea_`$address_labels.administrativeArea.code`"]}{else}{$address_labels.administrativeArea.label}{/if}">
                                            <b class="tooltip tooltip-bottom-right">{if isset($labels["administrativeArea_`$address_labels.administrativeArea.code`"]) and $labels["administrativeArea_`$address_labels.administrativeArea.code`"]!=''}{$labels["administrativeArea_`$address_labels.administrativeArea.code`"]}{else}{$address_labels.administrativeArea.label}{/if}</b>
                                        </label>
                                    </section>

                                </div>


                                <div class="row" >
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
                                        <span style="position:absolute;cursor: pointer" onclick="show_address_labels_editor()" ><i  class="fa fa-language" aria-hidden="true"></i>  {t}Address labels{/t} </span>
                                    </section>
                                </div>


                            </fieldset>


                            <footer>
                                <button type="submit" class="button" id="_save_delivery_address_details_label" contenteditable="true">{$content._save_delivery_address_details_label}</button>
                            </footer>
                        </form>
                    </div>


                    <div id="_current_order" class="block hide">

                        <h3 class="mirror_master" id="_current_order_title" contenteditable="true">{$content._current_order_title}</h3>

                        {include file="theme_1/_order.theme_1.tpl"}

                    </div>

                    <div id="_last_order" class="block hide">

                        <h3 class="mirror_master" id="_last_order_title" contenteditable="true">{$content._last_order_title}</h3>

                        {include file="theme_1/_order.theme_1.tpl"}

                    </div>

                    <div id="_orders" class="block hide">

                        <h3 class="mirror_master" id="_orders_title" contenteditable="true">{$content._orders_title}</h3>

                        <table class="table">
                            <thead>
                            <tr>
                                <th>{t}Number{/t}</th>
                                <th>{t}Date{/t}</th>
                                <th>{t}Status{/t}</th>
                                <th class="text-right">{t}Total{/t}</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="like_button">88792</td>
                                <td>{"yesterday"|date_format:"%A, %e %B %Y"}</td>
                                <td>{t}Dispatched{/t}</td>
                                <td class="text-right">£120.40</td>
                            </tr>
                            <tr>
                                <td class="like_button">88233</td>
                                <td>{"-50 days"|date_format:"%A, %e %B %Y"}</td>
                                <td>{t}Dispatched{/t}</td>
                                <td class="text-right">£600.00</td>
                            </tr>
                            <tr>
                                <td class="like_button">87989</td>
                                <td>{"-100 days"|date_format:"%A, %e %B %Y"}</td>
                                <td>{t}Dispatched{/t}</td>
                                <td class="text-right">£75.50</td>
                            </tr>
                            </tbody>
                        </table>


                    </div>


                </div>

            </div>
        </div><!-- end content area -->


        <div class="clearfix marb12"></div>
        <div class="clearfix marb12"></div>

    </div>

</div>
<script>

    $('[contenteditable=true]').on('input paste', function (event) {
        $('#save_button', window.parent.document).addClass('save button changed valid')


        console.log($(this).attr('id'))

        if ($(this).hasClass('mirror_master')) {
            $('.' + $(this).attr('id')).html($(this).html())

        }

    });


    function change_block(element) {

        $('.block').addClass('hide')
        $('#' + $(element).attr('block')).removeClass('hide')

        $('.sidebar_widget .block_link').removeClass('selected')
        $(element).addClass('selected')
    }



    $(document).delegate('a', 'click', function (e) {

        return false
    })


    $("form").on('submit', function (e) {

        e.preventDefault();
        e.returnValue = false;

// do things
    });

    function show_edit_input(element) {
        offset = $(element).closest('section').offset();
        $('#input_editor').removeClass('hide').offset({
            top: offset.top, left: offset.left - 35}).data('element',element)
        $('#input_editor_placeholder').val($(element).closest('label').find('input').attr('placeholder'))
        $('#input_editor_tooltip').val($(element).closest('label').find('b').html())
    }

    function save_edit_input() {

        var element = $('#input_editor').data('element')
        $(element).closest('label').find('input').attr('placeholder', $('#input_editor_placeholder').val())
    $(element).closest('label').find('b').html($('#input_editor_tooltip').val())

        $('#input_editor').addClass('hide')
        $('#save_button', window.parent.document).addClass('save button changed valid')
    }


    $('.order_number').each(function (i, obj) {
        $(obj).html(Math.floor((Math.random() * 30000) + 10000))
    })

    function save() {

        if (!$('#save_button', window.parent.document).hasClass('save')) {
            return;
        }

        $('#save_button', window.parent.document).find('i').addClass('fa-spinner fa-spin')


        content_data = { };
        labels= { };

        $('[contenteditable=true]').each(function (i, obj) {
            content_data[$(obj).attr('id')] = $(obj).html()
        })


        $('.register_field').each(function (i, obj) {
            content_data[$(obj).attr('id')] = $(obj).attr('placeholder')
        })


        $('.tooltip').each(function (i, obj) {
            if($(obj).attr('id')!=undefined)
                content_data[$(obj).attr('id')] = $(obj).html()
        })


        $('.website_localized_label').each(function (i, obj) {
            if($(obj).val()!=''){
                labels[$(obj).attr('id')] = $(obj).val()


            }

        })


       // content_data['redirect']=$('#redirect', window.parent.document).attr('type')
       // content_data['send_email']=$('#send_email', window.parent.document).hasClass('success')

        console.log(content_data)



        var ajaxData = new FormData();

        ajaxData.append("tipo", 'save_webpage_content')
        ajaxData.append("key", '{$webpage->id}')
        ajaxData.append("content_data", JSON.stringify(content_data))
        ajaxData.append("labels", JSON.stringify(labels))


        $.ajax({
            url: "/ar_edit_website.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,
            complete: function () {
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

    function show_address_labels_editor() {
        offset_form = $('.reg_form').offset();
        offset_address_fields = $('#address_fields').offset();


        $('#address_labels_editor').removeClass('hide').offset({
            top: offset_address_fields.top,
            left: offset_form.left
        });

    }

    function save_address_labels() {
        $('#address_labels_editor').addClass('hide')
        var element = $('#' + $('#input_editor').attr('element_id'))

        $('#save_button', window.parent.document).addClass('save button changed valid')
    }


    $('#address_labels_editor input').on('input propertychange', function() {
        $('#save_button', window.parent.document).addClass('save button changed valid')

    });


</script>

{include file="theme_1/bottom_scripts.theme_1.EcomB2B.tpl"}</body>

</html>

