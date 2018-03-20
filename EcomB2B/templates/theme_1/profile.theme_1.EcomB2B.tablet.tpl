﻿{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 13 March 2018 at 14:33:48 GMT+8, Kuala Lumpur Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}{include file="theme_1/_head.theme_1.EcomB2B.tablet.tpl"}
<body>{include file="analytics.tpl"}
<div id="">
    {include file="theme_1/header.theme_1.EcomB2B.mobile.tpl"}
    <div id="page-content" class="page-content ">
        <div id="page-content-scroll" class="header-clear"><!--Enables this element to be scrolled -->


            <div class="heading-strip bg-1" style="padding: 10px 20px;margin-bottom: 10px">
                <h3>{$content._customer_profile_title|strip_tags}</h3>
                <i class="ion-android-person" style="top:-27.5px"></i>
                <div class="overlay dark-overlay"></div>
            </div>


            <div class="menu-bottom-bar menu-bottom-bar-{if empty($poll_queries)}four{else}five{/if} color-menu-bar menu-bottom-bar-text flat-menu-bar">





                    <a  class="like_button profile_button no-smoothState bg-black border-orange-dark  "  data-tab="_contact_details">
                        <i class="fa fa-user  color-orange-dark" aria-hidden="true" style="margin-top: 7px"></i>
                        <em style="font-size: 11px">{$content._contact_details_title}</em>
                    </a>


                <a  class="like_button profile_button no-smoothState bg-black border-black color-gray-light  "  data-tab="_poll_details">
                    <i class="fa fa-sign-in  color-gray-light" aria-hidden="true"  style="margin-top: 7px"></i>
                    <em style="font-size: 11px">{if empty($content._poll_title)}{t}Poll{/t}{else}{$content._poll_title}{/if}</em>
                </a>


                <a  class="like_button profile_button no-smoothState bg-black border-black color-gray-light  "  data-tab="_login_details">
                    <i class="fa fa-sign-in  color-gray-light" aria-hidden="true"  style="margin-top: 7px"></i>
                    <em style="font-size: 11px">{$content._login_details_title}</em>
                </a>
                <a  class="like_button profile_button no-smoothState bg-black border-black color-gray-light  "   data-tab="_invoice_address_details">
                    <i class="fa fa-dollar color-gray-light" aria-hidden="true"  style="margin-top: 7px"></i>
                    <em style="font-size: 11px">{$content._invoice_address_title}</em>
                </a>
                <a  class="like_button profile_button no-smoothState bg-black border-black color-gray-light  "  data-tab="_delivery_addresses_details">
                    <i class="fa fa-truck color-gray-light" aria-hidden="true"  style="margin-top: 7px"></i>
                    <em style="font-size: 11px">{$content._delivery_addresses_title}</em>
                </a>

            </div>

            <div class="clear"></div>

            <div id="_contact_details" class="profile_block profile_form">
                <form id="contact_details" class="sky-form">


                    <header class="mirror_master" id="_contact_details_title">{$content._contact_details_title}</header>

                    <fieldset>


                        <section>
                            <label class="label">{$content._company_label}</label>
                            <label class="input">
                                <i id="company" class="icon-append icon-briefcase"></i>
                                <input class="register_field" type="text" name="company" value="{$customer->get('Customer Company Name')}" placeholder="{$content._company_placeholder}">
                                <b id="_company_tooltip" class="tooltip tooltip-bottom-right">{$content._company_tooltip}</b>
                            </label>
                        </section>


                        <section>
                            <label class="label">{$content._contact_name_label}</label>
                            <label class="input">
                                <i id="contact_name" class="icon-append icon-user"></i>
                                <input class="register_field" type="text" name="contact_name" value="{$customer->get('Customer Main Contact Name')}" placeholder="{$content._contact_name_placeholder}">
                                <b id="_contact_name_tooltip" class="tooltip tooltip-bottom-right">{$content._contact_name_tooltip}</b>
                            </label>
                        </section>


                        <section>
                            <label class="label">{$content._mobile_label}</label>
                            <label class="input">
                                <i class="icon-append fa fa-mobile" ></i>
                                <input class="register_field" type="text" name="mobile"  value="{$customer->get('Customer Main Plain Mobile')}"  placeholder="{$content._mobile_placeholder}">
                                <b id="_mobile_tooltip" class="tooltip tooltip-bottom-right">{$content._mobile_tooltip}</b>
                            </label>
                        </section>

                        <section>
                            <label class="label">{$content._email_label}</label>
                            <label class="input">
                                <i class="icon-append fa fa-envelope"></i>
                                <input class="register_field" type="email" name="email" id="_email_placeholder" value="{$customer->get('Customer Main Plain Email')}" placeholder="{$content._email_placeholder}">
                                <b id="_email_tooltip" class="tooltip tooltip-bottom-right">{$content._email_tooltip}</b>
                            </label>
                        </section>

                    </fieldset>


                    <fieldset>

                        <section>
                            <label class="label">{$content._registration_number_label}</label>

                            <label class="input">
                                <i class="icon-append icon-gavel"><i class="fa fa-building" aria-hidden="true"></i>
                                </i>
                                <input class="register_field" type="text" name="registration_number"   value="{$customer->get('Customer Registration Number')}" placeholder="{$content._registration_number_placeholder}">
                                <b id="_registration_number_tooltip" class="tooltip tooltip-bottom-right">{$content._registration_number_tooltip}</b>
                            </label>
                        </section>

                        <section>
                            <label class="label">{$content._tax_number_label}</label>

                            <label class="input">
                                <i id="_tax_number" onclick="show_edit_input(this)" class="icon-append icon-gavel"><i class="fa fa-gavel" aria-hidden="true"></i>
                                </i>
                                <input class="register_field" type="text" name="tax_number" id="_tax_number_placeholder"  value="{$customer->get('Customer Tax Number')}" placeholder="{$content._tax_number_placeholder}">
                                <b id="_tax_number_tooltip" class="tooltip tooltip-bottom-right">{$content._tax_number_tooltip}</b>
                            </label>
                            <label class="label">{$customer->get('Tax Number Valid')}</label>

                        </section>


                    </fieldset>

                    <fieldset>

                        <section>
                            <label class="label">{t}Subscriptions{/t}</label>
                            <label style="border:none" class="toggle "><input type="checkbox" {if $customer->get('Customer Send Newsletter')=='Yes'}checked{/if} name="newsletter"><i></i>{t}Newsletter{/t}</label>
                            <label style="border:none" class="toggle "><input type="checkbox" {if $customer->get('Customer Send Email Marketing')=='Yes'}checked{/if} name="email_marketing"><i></i>{t}Email marketing{/t}</label>
                            <label style="border:none" class="toggle "><input type="checkbox" {if $customer->get('Customer Send Postal Marketing')=='Yes'}checked{/if} name="postal_marketing"><i></i>{t}Postal marketing{/t}</label>
                        </section>




                    </fieldset>


                    <footer>
                        <button id="save_contact_details_button" type="submit" class="button  " >{$content._save_contact_details_label} <i  class="margin_left_10 fa fa-fw fa-save" aria-hidden="true"></i> </button>
                    </footer>
                </form>
            </div>


            <div id="_poll_details" class="profile_block hide profile_form">
                <form  id="poll_details" class="sky-form">
                    <header class="mirror_master" id="_poll_details_title" contenteditable="true">{if empty($content._poll_details_title)}{t}Poll{/t}{else}{$content._poll_details_title}{/if}</header>

                    <fieldset>
                        <section>

                            <label class="input">
                                        <span id="_poll_info" contenteditable="true">{if empty($content._poll_info)}{t}Please let know you better so we can serve you better{/t}{else}{$content._poll_info}{/if}
                            </label>
                        </section>



                        {foreach from=$poll_queries item=query}

                            {if $query['Customer Poll Query Type']=='Open'}
                                <section>
                                    <label  class="label poll_query_label" >{$query['Customer Poll Query Label']}</label>
                                    <label class="textarea">
                                        <textarea rows="4"  name="poll_{$query['Customer Poll Query Key']}"  id="poll_{$query['Customer Poll Query Key']}">{$query['Reply']}</textarea>
                                    </label>
                                </section>
                            {else}
                                <section>
                                    <label data-query_key="{$query['Customer Poll Query Key']}" class="label poll_query_label" >{$query['Customer Poll Query Label']}</label>
                                    <label class="select">
                                        <select name="poll_{$query['Customer Poll Query Key']}">
                                            <option value="0" selected disabled>{if !empty($labels._choose_one)}{$labels._choose_one}{else}{t}{t}Please choose one{/t}{/t}{/if}</option>

                                            {foreach from=$query['Options'] item=option}
                                                <option value="{$option['Customer Poll Query Option Key']}"   {if $option['Customer Poll Query Option Key']==$query['Reply']}selected{/if}   >{$option['Customer Poll Query Option Label']}</option>
                                            {/foreach}


                                        </select>
                                        <i></i>
                                    </label>
                                </section>

                            {/if}

                        {/foreach}




                    </fieldset>
                    <footer>
                        <button type="submit" class="button " id="save_poll_details" >{if empty($content._save_poll_details_label)}{t}Save{/t}{else}{$content._save_poll_details_label}{/if} <i  class="margin_left_10 fa fa-fw fa-save" aria-hidden="true"></i> </button>
                    </footer>
                </form>
            </div>

            <div id="_login_details" class="profile_block hide profile_form">
                <form id="login_details" class="sky-form">
                    <header class="mirror_master" id="_login_details_title">{$content._login_details_title}</header>

                    <fieldset>
                        <section>
                            <label class="input">
                                        <span id="_username_info">{$content._username_info}
                            </label>
                        </section>

                        <section>
                            <label class="input">
                                <i id="_password" onclick="show_edit_input(this)" class="icon-append icon-lock"></i>
                                <input class="register_field" type="password" name="pwd" id="password" placeholder="{$content._password_placeholder}">
                                <b id="_password_tooltip" class="tooltip tooltip-bottom-right">{$content._password_tooltip}</b>
                            </label>
                        </section>

                        <section>
                            <label class="input">
                                <i id="_password_conform" onclick="show_edit_input(this)" class="icon-append icon-lock"></i>
                                <input class="register_field ignore" type="password" name="password_confirm" placeholder="{$content._password_confirm_placeholder}">
                                <b id="_password_conform_tooltip" class="tooltip tooltip-bottom-right">{$content._password_conform_tooltip}</b>
                            </label>
                        </section>


                    </fieldset>
                    <footer>
                        <button type="submit" class="button " id="save_login_details_button">{$content._save_login_details_label} <i  class="margin_left_10 fa fa-fw fa-save" aria-hidden="true"></i> </button>

                    </footer>
                </form>
            </div>

            <div id="_invoice_address_details" class="profile_block hide profile_form">


                <div class="address_form" >
                    <form id="invoice_address_form" class="sky-form">
                        <header id="_title">{$content._invoice_address_title}</header>





                        <fieldset id="invoice_address_fields" style="position:relative">



                            <section id="invoice_addressLine1" class="{if 'addressLine1'|in_array:$invoice_used_address_fields}{else}hide{/if}">

                                <label for="file" class="input">
                                    <label class="label">{if isset($labels.address_addressLine1) and $labels.address_addressLine1!=''}{$labels.address_addressLine1}{else}{t}Address Line 1{/t}{/if}</label>

                                    <input value="{$customer->get('Customer Invoice Address Line 1')}" type="text"  name="addressLine1" class="{if 'addressLine1'|in_array:$invoice_used_address_fields}{else}ignore{/if}" placeholder="{if isset($labels.address_addressLine1) and $labels.address_addressLine1!=''}{$labels.address_addressLine1}{else}{t}Address Line 1{/t}{/if}">
                                    <b class="tooltip tooltip-bottom-right">{if isset($labels.address_addressLine1) and $labels.address_addressLine1!=''}{$labels.address_addressLine1}{else}{t}Address Line 1{/t}{/if}</b>
                                </label>
                            </section>

                            <section id="invoice_addressLine2" class="{if 'addressLine2'|in_array:$invoice_used_address_fields}{else}hide{/if}">
                                <label for="file" class="input">
                                    <label class="label">{if isset($labels.address_addressLine2) and $labels.address_addressLine2!=''}{$labels.address_addressLine2}{else}{t}Address Line 2{/t}{/if}</label>

                                    <input  value="{$customer->get('Customer Invoice Address Line 2')}"  type="text" name="addressLine2" class="{if 'addressLine2'|in_array:$invoice_used_address_fields}{else}ignore{/if}" placeholder="{if isset($labels.address_addressLine2) and $labels.address_addressLine2!=''}{$labels.address_addressLine2}{else}{t}Address Line 2{/t}{/if}">
                                    <b class="tooltip tooltip-bottom-right">{if isset($labels.address_addressLine2) and $labels.address_addressLine2!=''}{$labels.address_addressLine2}{else}{t}Address Line 2{/t}{/if}</b>
                                </label>
                            </section>



                            <div id="invoice_sortingCode" class="row {if 'sortingCode'|in_array:$invoice_used_address_fields}{else}hide{/if}">
                                <section class="col col-6 " >
                                    <label class="input">
                                        <label class="label">{if isset($labels.address_sorting_code) and $labels.address_sorting_code!=''}{$labels.address_sorting_code}{else}{t}Sorting code{/t}{/if}</label>

                                        <input value="{$customer->get('Customer Invoice Address Sorting Code')}"  type="text" name="sortingCode" class="{if 'sortingCode'|in_array:$invoice_used_address_fields}{else}ignore{/if}" placeholder="{if isset($labels.address_sorting_code) and $labels.address_sorting_code!=''}{$labels.address_sorting_code}{else}{t}Sorting code{/t}{/if}">
                                        <b class="tooltip tooltip-bottom-right">{if isset($labels.address_sorting_code) and $labels.address_sorting_code!=''}{$labels.address_sorting_code}{else}{t}Sorting code{/t}{/if}</b>
                                    </label>
                                </section>


                            </div>

                            <div id="invoice_postalCode" class="row {if 'postalCode'|in_array:$invoice_used_address_fields}{else}hide{/if}">
                                <section class="col col-6 " >
                                    <label class="input">
                                        <label class="label">{if isset($labels["postalCode_`$invoice_address_labels.postalCode.code`"]) and $labels["postalCode_`$invoice_address_labels.postalCode.code`"]!=''}{$labels["postalCode_`$invoice_address_labels.postalCode.code`"]}{else}{$invoice_address_labels.postalCode.label}{/if}</label>

                                        <input value="{$customer->get('Customer Invoice Address Postal Code')}"  type="text" name="postalCode" class="{if 'postalCode'|in_array:$invoice_used_address_fields}{else}ignore{/if}" placeholder="{if isset($labels["postalCode_`$invoice_address_labels.postalCode.code`"]) and $labels["postalCode_`$invoice_address_labels.postalCode.code`"]!=''}{$labels["postalCode_`$invoice_address_labels.postalCode.code`"]}{else}{$invoice_address_labels.postalCode.label}{/if}">
                                        <b class="tooltip tooltip-bottom-right">{if isset($labels["postalCode_`$invoice_address_labels.postalCode.code`"]) and $labels["postalCode_`$invoice_address_labels.postalCode.code`"]!=''}{$labels["postalCode_`$invoice_address_labels.postalCode.code`"]}{else}{$invoice_address_labels.postalCode.label}{/if}</b>
                                    </label>
                                </section>


                            </div>

                            <div id="invoice_dependentLocality" class="row {if 'dependentLocality'|in_array:$invoice_used_address_fields}{else}hide{/if}">
                                <section class="col col-6 " >
                                    <label class="input">
                                        <label class="label">{if isset($labels["dependentLocality_`$invoice_address_labels.dependentLocality.code`"]) and $labels["dependentLocality_`$invoice_address_labels.dependentLocality.code`"]!=''}{$labels["dependentLocality_`$invoice_address_labels.dependentLocality.code`"]}{else}{$invoice_address_labels.dependentLocality.label}{/if}</label>

                                        <input value="{$customer->get('Customer Invoice Address Dependent Locality')}"  type="text" name="dependentLocality" class="{if 'dependentLocality'|in_array:$invoice_used_address_fields}{else}ignore{/if}" placeholder="{if isset($labels["dependentLocality_`$invoice_address_labels.dependentLocality.code`"]) and $labels["dependentLocality_`$invoice_address_labels.dependentLocality.code`"]!=''}{$labels["dependentLocality_`$invoice_address_labels.dependentLocality.code`"]}{else}{$invoice_address_labels.dependentLocality.label}{/if}">
                                        <b class="tooltip tooltip-bottom-right">{if isset($labels["dependentLocality_`$invoice_address_labels.dependentLocality.code`"]) and $labels["dependentLocality_`$invoice_address_labels.dependentLocality.code`"]!=''}{$labels["dependentLocality_`$invoice_address_labels.dependentLocality.code`"]}{else}{$invoice_address_labels.dependentLocality.label}{/if}</b>
                                    </label>
                                </section>

                            </div>

                            <div id="invoice_locality" class="row {if 'locality'|in_array:$invoice_used_address_fields}{else}hide{/if}">
                                <section class="col col-6 " >
                                    <label class="input">
                                        <label class="label">{if isset($labels["locality_`$invoice_address_labels.locality.code`"]) and $labels["locality_`$invoice_address_labels.locality.code`"]!=''}{$labels["locality_`$invoice_address_labels.locality.code`"]}{else}{$invoice_address_labels.locality.label}{/if}</label>

                                        <input value="{$customer->get('Customer Invoice Address Locality')}"  type="text" name="locality" class="{if 'locality'|in_array:$invoice_used_address_fields}{else}ignore{/if}" placeholder="{if isset($labels["locality_`$invoice_address_labels.locality.code`"]) and $labels["locality_`$invoice_address_labels.locality.code`"]!=''}{$labels["locality_`$invoice_address_labels.locality.code`"]}{else}{$invoice_address_labels.locality.label}{/if}">
                                        <b class="tooltip tooltip-bottom-right">{if isset($labels["locality_`$invoice_address_labels.locality.code`"]) and $labels["locality_`$invoice_address_labels.locality.code`"]!=''}{$labels["locality_`$invoice_address_labels.locality.code`"]}{else}{$invoice_address_labels.locality.label}{/if}</b>

                                    </label>
                                </section>

                            </div>


                            <div id="invoice_administrativeArea" class="row {if 'administrativeArea'|in_array:$invoice_used_address_fields}{else}hide{/if}">
                                <section class="col col-6 " >
                                    <label class="input">
                                        <label class="label">{if isset($labels["administrativeArea_`$invoice_address_labels.administrativeArea.code`"]) and $labels["administrativeArea_`$invoice_address_labels.administrativeArea.code`"]!=''}{$labels["administrativeArea_`$invoice_address_labels.administrativeArea.code`"]}{else}{$invoice_address_labels.administrativeArea.label}{/if}</label>

                                        <input value="{$customer->get('Customer Invoice Address Administrative Area')}"  type="text" name="administrativeArea" class="{if 'administrativeArea'|in_array:$invoice_used_address_fields}{else}ignore{/if}" placeholder="{if isset($labels["administrativeArea_`$invoice_address_labels.administrativeArea.code`"]) and $labels["administrativeArea_`$invoice_address_labels.administrativeArea.code`"]!=''}{$labels["administrativeArea_`$invoice_address_labels.administrativeArea.code`"]}{else}{$invoice_address_labels.administrativeArea.label}{/if}">
                                        <b class="tooltip tooltip-bottom-right">{if isset($labels["administrativeArea_`$invoice_address_labels.administrativeArea.code`"]) and $labels["administrativeArea_`$invoice_address_labels.administrativeArea.code`"]!=''}{$labels["administrativeArea_`$invoice_address_labels.administrativeArea.code`"]}{else}{$invoice_address_labels.administrativeArea.label}{/if}</b>
                                    </label>
                                </section>

                            </div>


                            <div class="row" >
                                <section class="col col-5">
                                    <label class="select">
                                        <select id="invoice_country_select" name="country">
                                            <option value="0" selected disabled>{if isset($labels.address_country) and $labels.address_country!=''}{$labels.address_country}{else}{t}Country{/t}{/if}</option>

                                            {foreach from=$countries item=country}
                                                <option value="{$country.2alpha}" {if $country.2alpha==$customer->get('Customer Invoice Address Country 2 Alpha Code')}selected{/if} >{$country.name}</option>
                                            {/foreach}


                                            <select><i></i>
                                    </label>
                                </section>


                            </div>


                        </fieldset>


                        <footer>
                            <button type="submit" class="button " id="save_invoice_address_details_button" >{$content._save_invoice_address_details_label} <i  class="margin_left_10 fa fa-fw fa-save" aria-hidden="true"></i> </button>
                        </footer>
                    </form>
                </div>


            </div>

            <div id="_delivery_addresses_details" class="profile_block hide profile_form">
                <div class="address_form" >
                    <form id="delivery_address_form" class="sky-form">
                        <header id="_title">{$content._delivery_addresses_title}</header>


                        <fieldset >


                            <section>
                                <label class="checkbox"><input class="ignored " type="checkbox"   {if $customer->get('Customer Delivery Address Link')=='Billing'}checked{/if} name="delivery_address_link" id="delivery_address_link"><i></i>{if empty($content._delivery_address_link)}{t}Deliver to invoice address{/t}{else}{$content._delivery_address_link}{/if}</label>
                                </a> </label>


                            </section>



                        </fieldset>


                        <fieldset id="delivery_address_fields" class="{if $customer->get('Customer Delivery Address Link')=='Billing'}hide{/if}" style="position:relative">



                            <section id="delivery_addressLine1" class="{if 'addressLine1'|in_array:$delivery_used_address_fields}{else}hide{/if}">

                                <label for="file" class="input">
                                    <label class="label">{if isset($labels.address_addressLine1) and $labels.address_addressLine1!=''}{$labels.address_addressLine1}{else}{t}Address Line 1{/t}{/if}</label>

                                    <input value="{$customer->get('Customer Delivery Address Line 1')}" type="text"  name="addressLine1" class="{if 'addressLine1'|in_array:$delivery_used_address_fields}{else}ignore{/if}" placeholder="{if isset($labels.address_addressLine1) and $labels.address_addressLine1!=''}{$labels.address_addressLine1}{else}{t}Address Line 1{/t}{/if}">
                                    <b class="tooltip tooltip-bottom-right">{if isset($labels.address_addressLine1) and $labels.address_addressLine1!=''}{$labels.address_addressLine1}{else}{t}Address Line 1{/t}{/if}</b>
                                </label>
                            </section>

                            <section id="delivery_addressLine2" class="{if 'addressLine2'|in_array:$delivery_used_address_fields}{else}hide{/if}">
                                <label for="file" class="input">
                                    <label class="label">{if isset($labels.address_addressLine2) and $labels.address_addressLine2!=''}{$labels.address_addressLine2}{else}{t}Address Line 2{/t}{/if}</label>

                                    <input  value="{$customer->get('Customer Delivery Address Line 2')}"  type="text" name="addressLine2" class="{if 'addressLine2'|in_array:$delivery_used_address_fields}{else}ignore{/if}" placeholder="{if isset($labels.address_addressLine2) and $labels.address_addressLine2!=''}{$labels.address_addressLine2}{else}{t}Address Line 2{/t}{/if}">
                                    <b class="tooltip tooltip-bottom-right">{if isset($labels.address_addressLine2) and $labels.address_addressLine2!=''}{$labels.address_addressLine2}{else}{t}Address Line 2{/t}{/if}</b>
                                </label>
                            </section>



                            <div id="delivery_sortingCode" class="row {if 'sortingCode'|in_array:$delivery_used_address_fields}{else}hide{/if}">
                                <section class="col col-6 " >
                                    <label class="input">
                                        <label class="label">{if isset($labels.address_sorting_code) and $labels.address_sorting_code!=''}{$labels.address_sorting_code}{else}{t}Sorting code{/t}{/if}</label>

                                        <input value="{$customer->get('Customer Delivery Address Sorting Code')}"  type="text" name="sortingCode" class="{if 'sortingCode'|in_array:$delivery_used_address_fields}{else}ignore{/if}" placeholder="{if isset($labels.address_sorting_code) and $labels.address_sorting_code!=''}{$labels.address_sorting_code}{else}{t}Sorting code{/t}{/if}">
                                        <b class="tooltip tooltip-bottom-right">{if isset($labels.address_sorting_code) and $labels.address_sorting_code!=''}{$labels.address_sorting_code}{else}{t}Sorting code{/t}{/if}</b>
                                    </label>
                                </section>


                            </div>

                            <div id="delivery_postalCode" class="row {if 'postalCode'|in_array:$delivery_used_address_fields}{else}hide{/if}">
                                <section class="col col-6 " >
                                    <label class="input">
                                        <label class="label">{if isset($labels["postalCode_`$delivery_address_labels.postalCode.code`"]) and $labels["postalCode_`$delivery_address_labels.postalCode.code`"]!=''}{$labels["postalCode_`$delivery_address_labels.postalCode.code`"]}{else}{$delivery_address_labels.postalCode.label}{/if}</label>

                                        <input value="{$customer->get('Customer Delivery Address Postal Code')}"  type="text" name="postalCode" class="{if 'postalCode'|in_array:$delivery_used_address_fields}{else}ignore{/if}" placeholder="{if isset($labels["postalCode_`$delivery_address_labels.postalCode.code`"]) and $labels["postalCode_`$delivery_address_labels.postalCode.code`"]!=''}{$labels["postalCode_`$delivery_address_labels.postalCode.code`"]}{else}{$delivery_address_labels.postalCode.label}{/if}">
                                        <b class="tooltip tooltip-bottom-right">{if isset($labels["postalCode_`$delivery_address_labels.postalCode.code`"]) and $labels["postalCode_`$delivery_address_labels.postalCode.code`"]!=''}{$labels["postalCode_`$delivery_address_labels.postalCode.code`"]}{else}{$delivery_address_labels.postalCode.label}{/if}</b>
                                    </label>
                                </section>


                            </div>

                            <div id="delivery_dependentLocality" class="row {if 'dependentLocality'|in_array:$delivery_used_address_fields}{else}hide{/if}">
                                <section class="col col-6 " >
                                    <label class="input">
                                        <label class="label">{if isset($labels["dependentLocality_`$delivery_address_labels.dependentLocality.code`"]) and $labels["dependentLocality_`$delivery_address_labels.dependentLocality.code`"]!=''}{$labels["dependentLocality_`$delivery_address_labels.dependentLocality.code`"]}{else}{$delivery_address_labels.dependentLocality.label}{/if}</label>

                                        <input value="{$customer->get('Customer Delivery Address Dependent Locality')}"  type="text" name="dependentLocality" class="{if 'dependentLocality'|in_array:$delivery_used_address_fields}{else}ignore{/if}" placeholder="{if isset($labels["dependentLocality_`$delivery_address_labels.dependentLocality.code`"]) and $labels["dependentLocality_`$delivery_address_labels.dependentLocality.code`"]!=''}{$labels["dependentLocality_`$delivery_address_labels.dependentLocality.code`"]}{else}{$delivery_address_labels.dependentLocality.label}{/if}">
                                        <b class="tooltip tooltip-bottom-right">{if isset($labels["dependentLocality_`$delivery_address_labels.dependentLocality.code`"]) and $labels["dependentLocality_`$delivery_address_labels.dependentLocality.code`"]!=''}{$labels["dependentLocality_`$delivery_address_labels.dependentLocality.code`"]}{else}{$delivery_address_labels.dependentLocality.label}{/if}</b>
                                    </label>
                                </section>

                            </div>

                            <div id="delivery_locality" class="row {if 'locality'|in_array:$delivery_used_address_fields}{else}hide{/if}">
                                <section class="col col-6 " >
                                    <label class="input">
                                        <label class="label">{if isset($labels["locality_`$delivery_address_labels.locality.code`"]) and $labels["locality_`$delivery_address_labels.locality.code`"]!=''}{$labels["locality_`$delivery_address_labels.locality.code`"]}{else}{$delivery_address_labels.locality.label}{/if}</label>

                                        <input value="{$customer->get('Customer Delivery Address Locality')}"  type="text" name="locality" class="{if 'locality'|in_array:$delivery_used_address_fields}{else}ignore{/if}" placeholder="{if isset($labels["locality_`$delivery_address_labels.locality.code`"]) and $labels["locality_`$delivery_address_labels.locality.code`"]!=''}{$labels["locality_`$delivery_address_labels.locality.code`"]}{else}{$delivery_address_labels.locality.label}{/if}">
                                        <b class="tooltip tooltip-bottom-right">{if isset($labels["locality_`$delivery_address_labels.locality.code`"]) and $labels["locality_`$delivery_address_labels.locality.code`"]!=''}{$labels["locality_`$delivery_address_labels.locality.code`"]}{else}{$delivery_address_labels.locality.label}{/if}</b>
                                    </label>
                                </section>

                            </div>


                            <div id="delivery_administrativeArea" class="row {if 'administrativeArea'|in_array:$delivery_used_address_fields}{else}hide{/if}">
                                <section class="col col-6 " >
                                    <label class="input">
                                        <label class="label">{if isset($labels["administrativeArea_`$delivery_address_labels.administrativeArea.code`"]) and $labels["administrativeArea_`$delivery_address_labels.administrativeArea.code`"]!=''}{$labels["administrativeArea_`$delivery_address_labels.administrativeArea.code`"]}{else}{$delivery_address_labels.administrativeArea.label}{/if}</label>

                                        <input value="{$customer->get('Customer Delivery Address Administrative Area')}"  type="text" name="administrativeArea" class="{if 'administrativeArea'|in_array:$delivery_used_address_fields}{else}ignore{/if}" placeholder="{if isset($labels["administrativeArea_`$delivery_address_labels.administrativeArea.code`"]) and $labels["administrativeArea_`$delivery_address_labels.administrativeArea.code`"]!=''}{$labels["administrativeArea_`$delivery_address_labels.administrativeArea.code`"]}{else}{$delivery_address_labels.administrativeArea.label}{/if}">
                                        <b class="tooltip tooltip-bottom-right">{if isset($labels["administrativeArea_`$delivery_address_labels.administrativeArea.code`"]) and $labels["administrativeArea_`$delivery_address_labels.administrativeArea.code`"]!=''}{$labels["administrativeArea_`$delivery_address_labels.administrativeArea.code`"]}{else}{$delivery_address_labels.administrativeArea.label}{/if}</b>
                                    </label>
                                </section>

                            </div>


                            <div class="row" >
                                <section class="col col-5">
                                    <label class="select">
                                        <select id="delivery_country_select" name="country">
                                            <option value="0" selected disabled>{if isset($labels.address_country) and $labels.address_country!=''}{$labels.address_country}{else}{t}Country{/t}{/if}</option>

                                            {foreach from=$countries item=country}
                                                <option value="{$country.2alpha}" {if $country.2alpha==$customer->get('Customer Delivery Address Country 2 Alpha Code')}selected{/if} >{$country.name}</option>
                                            {/foreach}


                                            <select><i></i>
                                    </label>
                                </section>


                            </div>


                        </fieldset>


                        <footer>
                            <button type="submit" class="button "  id="save_delivery_address_details_button" >{$content._save_delivery_address_details_label} <i  class="margin_left_10 fa fa-fw fa-save" aria-hidden="true"></i> </button>

                        </footer>
                    </form>
                </div>

            </div>



           


            {include file="theme_1/footer.theme_1.EcomB2B.mobile.tpl"}
        </div>
    </div>

    <a href="#" class="back-to-top-badge"><i class="ion-ios-arrow-up"></i></a>

    <div class="share-bottom share-light">
        <h3>Share Page</h3>
        <div class="share-socials-bottom">
            <a href="https://www.facebook.com/sharer/sharer.php?u=http://www.themeforest.net/">
                <i class="ion-social-facebook facebook-bg"></i>
                Facebook
            </a>
            <a href="https://twitter.com/home?status=Check%20out%20ThemeForest%20http://www.themeforest.net">
                <i class="ion-social-twitter twitter-bg"></i>
                Twitter
            </a>
            <a href="https://plus.google.com/share?url=http://www.themeforest.net">
                <i class="ion-social-googleplus google-bg"></i>
                Google
            </a>
            <a href="https://pinterest.com/pin/create/button/?url=http://www.themeforest.net/&media=https://0.s3.envato.com/files/63790821/profile-image.jpg&description=Themes%20and%20Templates">
                <i class="ion-social-pinterest-outline pinterest-bg"></i>
                Pinterest
            </a>
            <a href="sms:">
                <i class="ion-ios-chatboxes-outline sms-bg"></i>
                Text
            </a>
            <a href="mailto:?&subject=Check this page out!&body=http://www.themeforest.net">
                <i class="ion-ios-email-outline mail-bg"></i>
                Email
            </a>
            <div class="clear"></div>
        </div>
    </div>
</div>
</body>
<script>


    function change_block(element) {

        $('.block').addClass('hide')
        $('#' + $(element).attr('block')).removeClass('hide')

        $('.sidebar_widget .block_link').removeClass('selected')
        $(element).addClass('selected')
    }



    $("form").on('submit', function (e) {

        e.preventDefault();
        e.returnValue = false;

    });


    $("#contact_details").validate(
        {

            submitHandler: function(form)
            {


                var button=$('#save_contact_details_button');

                if(button.hasClass('wait')){
                    return;
                }

                button.addClass('wait')
                button.find('i').removeClass('fa-save').addClass('fa-spinner fa-spin')


                var register_data={ }

                $("#contact_details input:not(.ignore)").each(function(i, obj) {
                    if(!$(obj).attr('name')==''){

                        if($(obj).attr('type')=='checkbox'){
                            register_data[$(obj).attr('name')]=$(obj).is(':checked')
                        }else{
                            register_data[$(obj).attr('name')]=$(obj).val()
                        }


                    }

                });

                $("#contact_details select:not(.ignore)").each(function(i, obj) {
                    if(!$(obj).attr('name')==''){

                        register_data[$(obj).attr('name')]=$(obj).val()
                    }

                });



                var ajaxData = new FormData();

                ajaxData.append("tipo", 'contact_details')
                ajaxData.append("data", JSON.stringify(register_data))


                $.ajax({
                    url: "/ar_web_profile.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,
                    complete: function () {
                    }, success: function (data) {

                        console.log(data)

                        if (data.state == '200') {





                        } else if (data.state == '400') {
                            swal("{t}Error{/t}!", data.msg, "error")
                        }


                        button.removeClass('wait')
                        button.find('i').addClass('fa-save').removeClass('fa-spinner fa-spin')


                    }, error: function () {
                        button.removeClass('wait')
                        button.find('i').addClass('fa-save').removeClass('fa-spinner fa-spin')

                    }
                });


            },

            // Rules for form validation
            rules:
                {

                    email:
                        {
                            required: true,
                            email: true,
                            remote: {
                                url: "ar_web_validate.php",
                                data: {
                                    tipo:'validate_update_email'
                                }
                            }

                        },

                    contact_name:
                        {
                            required: true,

                        },
                    mobile:
                        {
                            required: true,

                        },


                },

            // Messages for form validation
            messages:
                {

                    email:
                        {
                            required: '{if empty($labels._validation_required)}{t}Required field{/t}{else}{$labels._validation_required|escape}{/if}',
                            email: '{if empty($labels._validation_email_invalid)}{t}Invalid email{/t}{else}{$labels._validation_email_invalid|escape}{/if}',
                            remote: '{if empty($labels._validation_handle_registered)}{t}Email address is already in registered{/t}{else}{$labels._validation_handle_registered|escape}{/if}',



                        },

                    contact_name:
                        {
                            required: '{if empty($labels._validation_required)}{t}Required field{/t}{else}{$labels._validation_required|escape}{/if}',
                        },
                    mobile:
                        {
                            required: '{if empty($labels._validation_required)}{t}Required field{/t}{else}{$labels._validation_required|escape}{/if}',
                        }




                },

            // Do not change code below
            errorPlacement: function(error, element)
            {
                error.insertAfter(element.parent());
            }
        });





    $(document).on('keyup paste change', "#contact_details :input", function(ev){
        $('#save_contact_details_button')
    });

    $("#login_details").validate(
        {

            submitHandler: function(form)
            {

                var button=$('#save_login_details_button');

                if(button.hasClass('wait')){
                    return;
                }

                button.addClass('wait')
                button.find('i').removeClass('fa-save').addClass('fa-spinner fa-spin')


                var register_data={ }

                $("#login_details input:not(.ignore)").each(function(i, obj) {
                    if(!$(obj).attr('name')==''){
                        register_data[$(obj).attr('name')]=$(obj).val()
                    }

                });

                $("#login_details select:not(.ignore)").each(function(i, obj) {
                    if(!$(obj).attr('name')==''){


                        register_data[$(obj).attr('name')]=$(obj).val()
                    }

                });


                //  register_data['pwd']=sha256_digest(register_data['pwd']);

                var ajaxData = new FormData();

                ajaxData.append("tipo", 'update_password')
                ajaxData.append("pwd", sha256_digest(register_data['pwd']))


                $.ajax({
                    url: "/ar_web_profile.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,
                    complete: function () {
                    }, success: function (data) {

                        console.log(data)

                        if (data.state == '200') {




                        } else if (data.state == '400') {
                            swal("{t}Error{/t}!", data.msg, "error")
                        }

                        button.removeClass('wait')
                        button.find('i').addClass('fa-save').removeClass('fa-spinner fa-spin')

                    }, error: function () {
                        button.removeClass('wait')
                        button.find('i').addClass('fa-save').removeClass('fa-spinner fa-spin')
                    }
                });


            },

            // Rules for form validation
            rules: {


                password: {
                    required: true, minlength: 8


                }, password_confirm: {
                    required: true, minlength: 8, equalTo: "#password"
                },
            },

            // Messages for form validation
            messages:
                {


                    password:
                        {


                            required: '{if empty($labels._validation_required)}{t}Required field{/t}{else}{$labels._validation_required|escape}{/if}',
                            minlength: '{if empty($labels._validation_minlength_password)}{t}Enter at least 8 characters{/t}{else}{$labels._validation_minlength_password|escape}{/if}',



                        },
                    password_confirm:
                        {
                            required: '{if empty($labels._validation_required)}{t}Required field{/t}{else}{$labels._validation_required|escape}{/if}',
                            equalTo: '{if empty($labels._validation_same_password)}{t}Enter the same password as above{/t}{else}{$labels._validation_same_password|escape}{/if}',

                            minlength: '{if empty($labels._validation_minlength_password)}{t}Enter at least 8 characters{/t}{else}{$labels._validation_minlength_password|escape}{/if}',
                        }



                },

            // Do not change code below
            errorPlacement: function(error, element)
            {
                error.insertAfter(element.parent());
            }
        });


    $(document).on('keyup paste change', "#login_details :input", function(ev){
        $('#save_login_details_button')
    });

    $(document).on('keyup paste change', "#invoice_address_form :input", function(ev){
        $('#save_invoice_address_details_button')
    });

    $(document).on('keyup paste change', "#delivery_address_form :input", function(ev){
        $('#save_delivery_address_details_button')
    });


    $(document).on('change', "#delivery_address_link", function(ev){

        if($(this).is(':checked')){
            $('#delivery_address_fields').addClass('hide')

        }else{
            $('#delivery_address_fields').removeClass('hide')

        }
    });

    $( "#invoice_country_select" ).change(function() {

        var selected=$( "#invoice_country_select option:selected" )
        // console.log(selected.val())

        var request= "ar_web_addressing.php?tipo=address_format&country_code="+selected.val()+'&website_key={$website->id}'

        console.log(request)
        $.getJSON(request, function( data ) {
            console.log(data)
            $.each(data.hidden_fields, function(index, value) {
                $('#invoice_'+value).addClass('hide')
                $('#invoice_'+value).find('input').addClass('ignore')

            });

            $.each(data.used_fields, function(index, value) {
                $('#invoice_'+value).removeClass('hide')
                $('#invoice_'+value).find('input').removeClass('ignore')

            });

            $.each(data.labels, function(index, value) {
                $('#invoice_'+index).find('input').attr('placeholder',value)
                $('#invoice_'+index).find('b').html(value)

            });

            $.each(data.no_required_fields, function(index, value) {


                // console.log(value)

                $('#invoice_'+value+' input').rules( "remove" );




            });

            $.each(data.required_fields, function(index, value) {
                console.log($('#'+value))
                //console.log($('#'+value+' input').rules())

                $('#invoice_'+value+' input').rules( "add", { required: true});

            });


        });


    });





    $("#invoice_address_form").validate(
        {

            submitHandler: function(form)
            {

                var button=$('#save_invoice_address_details_button');

                if(button.hasClass('wait')){
                    return;
                }

                button.addClass('wait')
                button.find('i').removeClass('fa-save').addClass('fa-spinner fa-spin')



                var register_data={ }

                $("#invoice_address_form input:not(.ignore)").each(function(i, obj) {
                    if(!$(obj).attr('name')==''){
                        register_data[$(obj).attr('name')]=$(obj).val()
                    }

                });

                $("#invoice_address_form select:not(.ignore)").each(function(i, obj) {
                    if(!$(obj).attr('name')==''){


                        register_data[$(obj).attr('name')]=$(obj).val()
                    }

                });



                var ajaxData = new FormData();

                ajaxData.append("tipo", 'invoice_address')
                ajaxData.append("data", JSON.stringify(register_data))


                $.ajax({
                    url: "/ar_web_profile.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,
                    complete: function () {
                    }, success: function (data) {

                        console.log(data)

                        if (data.state == '200') {




                        } else if (data.state == '400') {
                            swal("{t}Error{/t}!", data.msg, "error")
                        }

                        button.removeClass('wait')
                        button.find('i').addClass('fa-save').removeClass('fa-spinner fa-spin')

                    }, error: function () {

                        button.removeClass('wait')
                        button.find('i').addClass('fa-save').removeClass('fa-spinner fa-spin')

                    }
                });


            },

            // Rules for form validation
            rules:
                {



    {foreach from=$invoice_required_fields item=required_field }
    {$required_field}: { required: true },
    {/foreach}

    {foreach from=$invoice_no_required_fields item=no_required_field }
    {$no_required_field}:{   required: false},
    {/foreach}

    },

    // Messages for form validation
    messages:
    {


        administrativeArea:
        {
            required: '{if empty($labels._validation_required)}{t}Required field{/t}{else}{$labels._validation_required|escape}{/if}',
        },
        locality:
        {
            required: '{if empty($labels._validation_required)}{t}Required field{/t}{else}{$labels._validation_required|escape}{/if}',
        },
        dependentLocality:
        {
            required: '{if empty($labels._validation_required)}{t}Required field{/t}{else}{$labels._validation_required|escape}{/if}',
        },
        postalCode:
        {
            required: '{if empty($labels._validation_required)}{t}Required field{/t}{else}{$labels._validation_required|escape}{/if}',
        },
        addressLine1:
        {
            required: '{if empty($labels._validation_required)}{t}Required field{/t}{else}{$labels._validation_required|escape}{/if}',
        },
        addressLine2:
        {
            required: '{if empty($labels._validation_required)}{t}Required field{/t}{else}{$labels._validation_required|escape}{/if}',
        },
        sortingCode:
        {
            required: '{if empty($labels._validation_required)}{t}Required field{/t}{else}{$labels._validation_required|escape}{/if}',
        }




    },

    // Do not change code below
    errorPlacement: function(error, element)
    {
        error.insertAfter(element.parent());
    }
    });


    $( "#delivery_country_select" ).change(function() {




        var selected=$( "#delivery_country_select option:selected" )
        // console.log(selected.val())

        var request= "ar_web_addressing.php?tipo=address_format&country_code="+selected.val()+'&website_key={$website->id}'

        console.log(request)
        $.getJSON(request, function( data ) {
            console.log(data)
            $.each(data.hidden_fields, function(index, value) {
                $('#delivery_'+value).addClass('hide')
                $('#delivery_'+value).find('input').addClass('ignore')

            });

            $.each(data.used_fields, function(index, value) {
                $('#delivery_'+value).removeClass('hide')
                $('#delivery_'+value).find('input').removeClass('ignore')

            });

            $.each(data.labels, function(index, value) {
                $('#delivery_'+index).find('input').attr('placeholder',value)
                $('#delivery_'+index).find('b').html(value)

            });

            $.each(data.no_required_fields, function(index, value) {


                // console.log(value)

                $('#delivery_'+value+' input').rules( "remove" );




            });

            $.each(data.required_fields, function(index, value) {
                console.log($('#'+value))
                //console.log($('#'+value+' input').rules())

                $('#delivery_'+value+' input').rules( "add", { required: true});

            });


        });


    });





    $("#delivery_address_form").validate(
        {

            submitHandler: function(form)
            {


                var button=$('#save_delivery_address_details_button');

                if(button.hasClass('wait')){
                    return;
                }

                button.addClass('wait')
                button.find('i').removeClass('fa-save').addClass('fa-spinner fa-spin')


                var register_data={ }

                $("#delivery_address_form input:not(.ignore)").each(function(i, obj) {
                    if(!$(obj).attr('name')==''){
                        register_data[$(obj).attr('name')]=$(obj).val()
                    }

                });

                $("#delivery_address_form select:not(.ignore)").each(function(i, obj) {
                    if(!$(obj).attr('name')==''){


                        register_data[$(obj).attr('name')]=$(obj).val()
                    }

                });

                register_data['delivery_address_link']=$('#delivery_address_link').is(':checked')


                console.log(register_data)

                var ajaxData = new FormData();

                ajaxData.append("tipo", 'delivery_address')
                ajaxData.append("data", JSON.stringify(register_data))


                $.ajax({
                    url: "/ar_web_profile.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,
                    complete: function () {
                    }, success: function (data) {

                        console.log(data)

                        if (data.state == '200') {




                        } else if (data.state == '400') {
                            swal("{t}Error{/t}!", data.msg, "error")
                        }

                        button.removeClass('wait')
                        button.find('i').addClass('fa-save').removeClass('fa-spinner fa-spin')

                    }, error: function () {
                        button.removeClass('wait')
                        button.find('i').addClass('fa-save').removeClass('fa-spinner fa-spin')
                    }
                });


            },

            // Rules for form validation
            rules:
                {



    {foreach from=$delivery_required_fields item=required_field }
    {$required_field}: { required: true },
    {/foreach}

    {foreach from=$delivery_no_required_fields item=no_required_field }
    {$no_required_field}:{   required: false},
    {/foreach}

    },

    // Messages for form validation
    messages:
    {


        administrativeArea:
        {
            required: '{if empty($labels._validation_required)}{t}Required field{/t}{else}{$labels._validation_required|escape}{/if}',
        },
        locality:
        {
            required: '{if empty($labels._validation_required)}{t}Required field{/t}{else}{$labels._validation_required|escape}{/if}',
        },
        dependentLocality:
        {
            required: '{if empty($labels._validation_required)}{t}Required field{/t}{else}{$labels._validation_required|escape}{/if}',
        },
        postalCode:
        {
            required: '{if empty($labels._validation_required)}{t}Required field{/t}{else}{$labels._validation_required|escape}{/if}',
        },
        addressLine1:
        {
            required: '{if empty($labels._validation_required)}{t}Required field{/t}{else}{$labels._validation_required|escape}{/if}',
        },
        addressLine2:
        {
            required: '{if empty($labels._validation_required)}{t}Required field{/t}{else}{$labels._validation_required|escape}{/if}',
        },
        sortingCode:
        {
            required: '{if empty($labels._validation_required)}{t}Required field{/t}{else}{$labels._validation_required|escape}{/if}',
        }




    },

    // Do not change code below
    errorPlacement: function(error, element)
    {
        error.insertAfter(element.parent());
    }
    });








</script>
{include file="theme_1/bottom_scripts.theme_1.EcomB2B.mobile.tpl"}</body>
</html>

