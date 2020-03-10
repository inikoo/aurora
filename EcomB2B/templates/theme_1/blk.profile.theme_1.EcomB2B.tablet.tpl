{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 13 March 2018 at 14:33:48 GMT+8, Kuala Lumpur Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}


{if isset($data.top_margin)}{assign "top_margin" $data.top_margin}{else}{assign "top_margin" "0"}{/if}
{if isset($data.bottom_margin)}{assign "bottom_margin" $data.bottom_margin}{else}{assign "bottom_margin" "0"}{/if}


<div id="block_{$key}" class="{if !$data.show}hide{/if}" style="padding-bottom:{$bottom_margin}px">

    <div class="table_top">
        <span class="title"><span class="Customer_Name">{$customer->get('Customer Name')}</span> <span class="small italic padding_left_10">{t}Customer ID{/t}: {$customer->id}</span></span>
    </div>


            <div class="menu-bottom-bar menu-bottom-bar-{if empty($poll_queries)}three{else}four{/if} color-menu-bar menu-bottom-bar-text flat-menu-bar">

                <a  class="like_button profile_button no-smoothState bg-black border-orange-dark  "  data-tab="_contact_details">
                        <i class="fa fa-user  color-orange-dark" aria-hidden="true" style="margin-top: 7px"></i>
                        <em style="font-size: 11px">{$data.labels._contact_details_title}</em>
                    </a>
                <a  class="like_button profile_button no-smoothState bg-black border-black color-gray-light  "  data-tab="_poll_details">
                    <i class="fa fa-question-circle  color-gray-light" aria-hidden="true"  style="margin-top: 7px"></i>
                    <em style="font-size: 11px">{if empty($data.labels._poll_title)}{t}Poll{/t}{else}{$data.labels._poll_title}{/if}</em>
                </a>
                <a  class="like_button profile_button no-smoothState bg-black border-black color-gray-light  "  data-tab="_login_details">
                    <i class="fa fa-sign-in  color-gray-light" aria-hidden="true"  style="margin-top: 7px"></i>
                    <em style="font-size: 11px">{$data.labels._login_details_title}</em>
                </a>
                <a  class="like_button profile_button no-smoothState bg-black border-black color-gray-light  "   data-tab="_invoice_address_details">
                    <i class="fa fa-dollar-sign color-gray-light" aria-hidden="true"  style="margin-top: 7px"></i>
                    <em style="font-size: 11px">{$data.labels._invoice_address_title}</em>
                </a>
                {if $store->get('Store Type')!='Dropshipping'}
                <a  class="like_button profile_button no-smoothState bg-black border-black color-gray-light  "  data-tab="_delivery_addresses_details">
                    <i class="fa fa-truck color-gray-light" aria-hidden="true"  style="margin-top: 7px"></i>
                    <em style="font-size: 11px">{$data.labels._delivery_addresses_title}</em>
                </a>
                <a  class="like_button profile_button no-smoothState bg-black border-black color-gray-light  "  data-tab="_orders_details">
                    <i class="fa fa-shopping-cart color-gray-light" aria-hidden="true"  style="margin-top: 7px"></i>
                    <em style="font-size: 11px">{$data.labels._orders_title}</em>
                </a>
                {/if}

            </div>

            <div class="clear"></div>

            <div id="_contact_details" class="profile_block profile_form">
                <form id="contact_details" class="sky-form">


                    <header class="mirror_master" id="_contact_details_title">{$data.labels._contact_details_title}</header>

                    <fieldset>


                        <section>
                            <label class="label">{$data.labels._company_label}</label>
                            <label class="input">
                                <i id="company" class="icon-append fa fa-briefcase"></i>
                                <input class="register_field" type="text" name="company" value="{$customer->get('Customer Company Name')}" placeholder="{$data.labels._company_placeholder}">
                                <b id="_company_tooltip" class="tooltip tooltip-bottom-right">{$data.labels._company_tooltip}</b>
                            </label>
                        </section>


                        <section>
                            <label class="label">{$data.labels._contact_name_label}</label>
                            <label class="input">
                                <i id="contact_name" class="icon-append icon-user"></i>
                                <input class="register_field" type="text" name="contact_name" value="{$customer->get('Customer Main Contact Name')}" placeholder="{$data.labels._contact_name_placeholder}">
                                <b id="_contact_name_tooltip" class="tooltip tooltip-bottom-right">{$data.labels._contact_name_tooltip}</b>
                            </label>
                        </section>


                        <section>
                            <label class="label">{$data.labels._mobile_label}</label>
                            <label class="input">
                                <i class="icon-append fa fa-mobile" ></i>
                                <input class="register_field" type="text" name="mobile"  value="{$customer->get('Customer Main Plain Mobile')}"  placeholder="{$data.labels._mobile_placeholder}">
                                <b id="_mobile_tooltip" class="tooltip tooltip-bottom-right">{$data.labels._mobile_tooltip}</b>
                            </label>
                        </section>

                        <section>
                            <label class="label">{$data.labels._email_label}</label>
                            <label class="input">
                                <i class="icon-append fa fa-envelope"></i>
                                <input class="register_field" type="email" name="email" id="_email_placeholder" value="{$customer->get('Customer Main Plain Email')}" placeholder="{$data.labels._email_placeholder}">
                                <b id="_email_tooltip" class="tooltip tooltip-bottom-right">{$data.labels._email_tooltip}</b>
                            </label>
                        </section>

                    </fieldset>


                    <fieldset>

                        <section>
                            <label class="label">{$data.labels._registration_number_label}</label>

                            <label class="input">
                                <i class="icon-append icon-gavel"><i class="fa fa-building" aria-hidden="true"></i>
                                </i>
                                <input class="register_field" type="text" name="registration_number"   value="{$customer->get('Customer Registration Number')}" placeholder="{$data.labels._registration_number_placeholder}">
                                <b id="_registration_number_tooltip" class="tooltip tooltip-bottom-right">{$data.labels._registration_number_tooltip}</b>
                            </label>
                        </section>

                        <section>
                            <label class="label">{$data.labels._tax_number_label}</label>

                            <label class="input">
                                <i id="_tax_number" onclick="show_edit_input(this)" class="icon-append icon-gavel"><i class="fa fa-gavel" aria-hidden="true"></i>
                                </i>
                                <input class="register_field" type="text" name="tax_number" id="_tax_number_placeholder"  value="{$customer->get('Customer Tax Number')}" placeholder="{$data.labels._tax_number_placeholder}">
                                <b id="_tax_number_tooltip" class="tooltip tooltip-bottom-right">{$data.labels._tax_number_tooltip}</b>
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
                        <button id="save_contact_details_button" type="submit" class="button  " >{$data.labels._save_contact_details_label} <i  class="margin_left_10 fa fa-fw fa-save" aria-hidden="true"></i> </button>
                    </footer>
                </form>
            </div>


            <div id="_poll_details" class="profile_block hide profile_form">
                <form  id="poll_details" class="sky-form">
                    <header class="mirror_master" id="_poll_details_title" contenteditable="true">{if empty($data.labels._poll_details_title)}{t}Poll{/t}{else}{$data.labels._poll_details_title}{/if}</header>

                    <fieldset>
                        <section>

                            <label class="input">
                                        <span id="_poll_info" contenteditable="true">{if empty($data.labels._poll_info)}{t}Please let know you better so we can serve you better{/t}{else}{$data.labels._poll_info}{/if}
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
                                            <option value="0" selected disabled>{if !empty($labels._choose_one)}{$labels._choose_one}{else}{t}Please choose one{/t}{/if}</option>

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
                        <button type="submit" class="button " id="save_poll_details" >{if empty($data.labels._save_poll_details_label)}{t}Save{/t}{else}{$data.labels._save_poll_details_label}{/if} <i  class="margin_left_10 fa fa-fw fa-save" aria-hidden="true"></i> </button>
                    </footer>
                </form>
            </div>

            <div id="_login_details" class="profile_block hide profile_form">
                <form id="login_details" class="sky-form">
                    <header class="mirror_master" id="_login_details_title">{$data.labels._login_details_title}</header>

                    <fieldset>
                        <section>
                            <label class="input">
                                        <span id="_username_info">{$data.labels._username_info}
                            </label>
                        </section>

                        <section>
                            <label class="input">
                                <i id="_password" onclick="show_edit_input(this)" class="icon-append icon-lock"></i>
                                <input class="register_field" type="password" name="pwd" id="password" placeholder="{$data.labels._password_placeholder}">
                                <b id="_password_tooltip" class="tooltip tooltip-bottom-right">{$data.labels._password_tooltip}</b>
                            </label>
                        </section>

                        <section>
                            <label class="input">
                                <i id="_password_conform" onclick="show_edit_input(this)" class="icon-append icon-lock"></i>
                                <input class="register_field ignore" type="password" name="password_confirm" placeholder="{$data.labels._password_confirm_placeholder}">
                                <b id="_password_conform_tooltip" class="tooltip tooltip-bottom-right">{$data.labels._password_conform_tooltip}</b>
                            </label>
                        </section>


                    </fieldset>
                    <footer>
                        <button type="submit" class="button " id="save_login_details_button">{$data.labels._save_login_details_label} <i  class="margin_left_10 fa fa-fw fa-save" aria-hidden="true"></i> </button>

                    </footer>
                </form>
            </div>

            <div id="_invoice_address_details" class="profile_block hide profile_form">


                <div class="address_form" >
                    <form id="invoice_address_form" class="sky-form">
                        <header id="_title">{$data.labels._invoice_address_title}</header>





                        <fieldset id="invoice_address_fields" style="position:relative">



                            <section id="invoice_addressLine1" class="{if 'addressLine1'|in_array:$invoice_used_address_fields}{else}hide{/if}">

                                <label for="file" class="input">
                                    <label class="label">{if !empty($labels.address_addressLine1)}{$labels.address_addressLine1}{else}{t}Address Line 1{/t}{/if}</label>

                                    <input value="{$customer->get('Customer Invoice Address Line 1')}" type="text"  name="addressLine1" class="{if 'addressLine1'|in_array:$invoice_used_address_fields}{else}ignore{/if}" placeholder="{if !empty($labels.address_addressLine1) }{$labels.address_addressLine1}{else}{t}Address Line 1{/t}{/if}">
                                    <b class="tooltip tooltip-bottom-right">{if !empty($labels.address_addressLine1) }{$labels.address_addressLine1}{else}{t}Address Line 1{/t}{/if}</b>
                                </label>
                            </section>

                            <section id="invoice_addressLine2" class="{if 'addressLine2'|in_array:$invoice_used_address_fields}{else}hide{/if}">
                                <label for="file" class="input">
                                    <label class="label">{if !empty($labels.address_addressLine2) }{$labels.address_addressLine2}{else}{t}Address Line 2{/t}{/if}</label>

                                    <input  value="{$customer->get('Customer Invoice Address Line 2')}"  type="text" name="addressLine2" class="{if 'addressLine2'|in_array:$invoice_used_address_fields}{else}ignore{/if}" placeholder="{if !empty($labels.address_addressLine2) }{$labels.address_addressLine2}{else}{t}Address Line 2{/t}{/if}">
                                    <b class="tooltip tooltip-bottom-right">{if !empty($labels.address_addressLine2) }{$labels.address_addressLine2}{else}{t}Address Line 2{/t}{/if}</b>
                                </label>
                            </section>



                            <div id="invoice_sortingCode" class="row {if 'sortingCode'|in_array:$invoice_used_address_fields}{else}hide{/if}">
                                <section class="col col-6 " >
                                    <label class="input">
                                        <label class="label">{if !empty($labels.address_sorting_code) }{$labels.address_sorting_code}{else}{t}Sorting code{/t}{/if}</label>

                                        <input value="{$customer->get('Customer Invoice Address Sorting Code')}"  type="text" name="sortingCode" class="{if 'sortingCode'|in_array:$invoice_used_address_fields}{else}ignore{/if}" placeholder="{if !empty($labels.address_sorting_code) }{$labels.address_sorting_code}{else}{t}Sorting code{/t}{/if}">
                                        <b class="tooltip tooltip-bottom-right">{if !empty($labels.address_sorting_code) }{$labels.address_sorting_code}{else}{t}Sorting code{/t}{/if}</b>
                                    </label>
                                </section>


                            </div>

                            <div id="invoice_postalCode" class="row {if 'postalCode'|in_array:$invoice_used_address_fields}{else}hide{/if}">
                                <section class="col col-6 " >
                                    <label class="input">
                                        <label class="label">{if !empty($labels["postalCode_`$invoice_address_labels.postalCode.code`"]) }{$labels["postalCode_`$invoice_address_labels.postalCode.code`"]}{else}{$invoice_address_labels.postalCode.label}{/if}</label>

                                        <input value="{$customer->get('Customer Invoice Address Postal Code')}"  type="text" name="postalCode" class="{if 'postalCode'|in_array:$invoice_used_address_fields}{else}ignore{/if}" placeholder="{if !empty($labels["postalCode_`$invoice_address_labels.postalCode.code`"]) }{$labels["postalCode_`$invoice_address_labels.postalCode.code`"]}{else}{$invoice_address_labels.postalCode.label}{/if}">
                                        <b class="tooltip tooltip-bottom-right">{if !empty($labels["postalCode_`$invoice_address_labels.postalCode.code`"]) }{$labels["postalCode_`$invoice_address_labels.postalCode.code`"]}{else}{$invoice_address_labels.postalCode.label}{/if}</b>
                                    </label>
                                </section>


                            </div>

                            <div id="invoice_dependentLocality" class="row {if 'dependentLocality'|in_array:$invoice_used_address_fields}{else}hide{/if}">
                                <section class="col col-6 " >
                                    <label class="input">
                                        <label class="label">{if !empty($labels["dependentLocality_`$invoice_address_labels.dependentLocality.code`"]) }{$labels["dependentLocality_`$invoice_address_labels.dependentLocality.code`"]}{else}{$invoice_address_labels.dependentLocality.label}{/if}</label>

                                        <input value="{$customer->get('Customer Invoice Address Dependent Locality')}"  type="text" name="dependentLocality" class="{if 'dependentLocality'|in_array:$invoice_used_address_fields}{else}ignore{/if}" placeholder="{if !empty($labels["dependentLocality_`$invoice_address_labels.dependentLocality.code`"]) }{$labels["dependentLocality_`$invoice_address_labels.dependentLocality.code`"]}{else}{$invoice_address_labels.dependentLocality.label}{/if}">
                                        <b class="tooltip tooltip-bottom-right">{if !empty($labels["dependentLocality_`$invoice_address_labels.dependentLocality.code`"]) }{$labels["dependentLocality_`$invoice_address_labels.dependentLocality.code`"]}{else}{$invoice_address_labels.dependentLocality.label}{/if}</b>
                                    </label>
                                </section>

                            </div>

                            <div id="invoice_locality" class="row {if 'locality'|in_array:$invoice_used_address_fields}{else}hide{/if}">
                                <section class="col col-6 " >
                                    <label class="input">
                                        <label class="label">{if !empty($labels["locality_`$invoice_address_labels.locality.code`"]) }{$labels["locality_`$invoice_address_labels.locality.code`"]}{else}{$invoice_address_labels.locality.label}{/if}</label>

                                        <input value="{$customer->get('Customer Invoice Address Locality')}"  type="text" name="locality" class="{if 'locality'|in_array:$invoice_used_address_fields}{else}ignore{/if}" placeholder="{if !empty($labels["locality_`$invoice_address_labels.locality.code`"]) }{$labels["locality_`$invoice_address_labels.locality.code`"]}{else}{$invoice_address_labels.locality.label}{/if}">
                                        <b class="tooltip tooltip-bottom-right">{if !empty($labels["locality_`$invoice_address_labels.locality.code`"])}{$labels["locality_`$invoice_address_labels.locality.code`"]}{else}{$invoice_address_labels.locality.label}{/if}</b>

                                    </label>
                                </section>

                            </div>


                            <div id="invoice_administrativeArea" class="row {if 'administrativeArea'|in_array:$invoice_used_address_fields}{else}hide{/if}">
                                <section class="col col-6 " >
                                    <label class="input">
                                        <label class="label">{if !empty($labels["administrativeArea_`$invoice_address_labels.administrativeArea.code`"]) }{$labels["administrativeArea_`$invoice_address_labels.administrativeArea.code`"]}{else}{$invoice_address_labels.administrativeArea.label}{/if}</label>

                                        <input value="{$customer->get('Customer Invoice Address Administrative Area')}"  type="text" name="administrativeArea" class="{if 'administrativeArea'|in_array:$invoice_used_address_fields}{else}ignore{/if}" placeholder="{if !empty($labels["administrativeArea_`$invoice_address_labels.administrativeArea.code`"]) }{$labels["administrativeArea_`$invoice_address_labels.administrativeArea.code`"]}{else}{$invoice_address_labels.administrativeArea.label}{/if}">
                                        <b class="tooltip tooltip-bottom-right">{if !empty($labels["administrativeArea_`$invoice_address_labels.administrativeArea.code`"]) }{$labels["administrativeArea_`$invoice_address_labels.administrativeArea.code`"]}{else}{$invoice_address_labels.administrativeArea.label}{/if}</b>
                                    </label>
                                </section>

                            </div>


                            <div class="row" >
                                <section class="col col-5">
                                    <label class="select">
                                        <select id="invoice_country_select" name="country">
                                            <option value="0" selected disabled>{if !empty($labels.address_country) }{$labels.address_country}{else}{t}Country{/t}{/if}</option>

                                            {foreach from=$countries item=country}
                                                <option value="{$country.2alpha}" {if $country.2alpha==$customer->get('Customer Invoice Address Country 2 Alpha Code')}selected{/if} >{$country.name}</option>
                                            {/foreach}


                                            <select><i></i>
                                    </label>
                                </section>


                            </div>


                        </fieldset>


                        <footer>
                            <button type="submit" class="button " id="save_invoice_address_details_button" >{$data.labels._save_invoice_address_details_label} <i  class="margin_left_10 fa fa-fw fa-save" aria-hidden="true"></i> </button>
                        </footer>
                    </form>
                </div>


            </div>

            <div id="_delivery_addresses_details" class="profile_block hide profile_form">
                <div class="address_form" >
                    <form id="delivery_address_form" class="sky-form">
                        <header id="_title">{$data.labels._delivery_addresses_title}</header>


                        <fieldset >


                            <section>
                                <label class="checkbox"><input class="ignored " type="checkbox"   {if $customer->get('Customer Delivery Address Link')=='Billing'}checked{/if} name="delivery_address_link" id="delivery_address_link"><i></i>{if empty($data.labels._delivery_address_link)}{t}Deliver to invoice address{/t}{else}{$data.labels._delivery_address_link}{/if}</label>
                                </a> </label>


                            </section>



                        </fieldset>


                        <fieldset id="delivery_address_fields" class="{if $customer->get('Customer Delivery Address Link')=='Billing'}hide{/if}" style="position:relative">



                            <section id="delivery_addressLine1" class="{if 'addressLine1'|in_array:$delivery_used_address_fields}{else}hide{/if}">

                                <label for="file" class="input">
                                    <label class="label">{if !empty($labels.address_addressLine1) }{$labels.address_addressLine1}{else}{t}Address Line 1{/t}{/if}</label>

                                    <input value="{$customer->get('Customer Delivery Address Line 1')}" type="text"  name="addressLine1" class="{if 'addressLine1'|in_array:$delivery_used_address_fields}{else}ignore{/if}" placeholder="{if !empty($labels.address_addressLine1) }{$labels.address_addressLine1}{else}{t}Address Line 1{/t}{/if}">
                                    <b class="tooltip tooltip-bottom-right">{if !empty($labels.address_addressLine1) }{$labels.address_addressLine1}{else}{t}Address Line 1{/t}{/if}</b>
                                </label>
                            </section>

                            <section id="delivery_addressLine2" class="{if 'addressLine2'|in_array:$delivery_used_address_fields}{else}hide{/if}">
                                <label for="file" class="input">
                                    <label class="label">{if !empty($labels.address_addressLine2) }{$labels.address_addressLine2}{else}{t}Address Line 2{/t}{/if}</label>

                                    <input  value="{$customer->get('Customer Delivery Address Line 2')}"  type="text" name="addressLine2" class="{if 'addressLine2'|in_array:$delivery_used_address_fields}{else}ignore{/if}" placeholder="{if !empty($labels.address_addressLine2) }{$labels.address_addressLine2}{else}{t}Address Line 2{/t}{/if}">
                                    <b class="tooltip tooltip-bottom-right">{if !empty($labels.address_addressLine2)}{$labels.address_addressLine2}{else}{t}Address Line 2{/t}{/if}</b>
                                </label>
                            </section>



                            <div id="delivery_sortingCode" class="row {if 'sortingCode'|in_array:$delivery_used_address_fields}{else}hide{/if}">
                                <section class="col col-6 " >
                                    <label class="input">
                                        <label class="label">{if !empty($labels.address_sorting_code) }{$labels.address_sorting_code}{else}{t}Sorting code{/t}{/if}</label>

                                        <input value="{$customer->get('Customer Delivery Address Sorting Code')}"  type="text" name="sortingCode" class="{if 'sortingCode'|in_array:$delivery_used_address_fields}{else}ignore{/if}" placeholder="{if !empty($labels.address_sorting_code) }{$labels.address_sorting_code}{else}{t}Sorting code{/t}{/if}">
                                        <b class="tooltip tooltip-bottom-right">{if !empty($labels.address_sorting_code) }{$labels.address_sorting_code}{else}{t}Sorting code{/t}{/if}</b>
                                    </label>
                                </section>


                            </div>

                            <div id="delivery_postalCode" class="row {if 'postalCode'|in_array:$delivery_used_address_fields}{else}hide{/if}">
                                <section class="col col-6 " >
                                    <label class="input">
                                        <label class="label">{if !empty($labels["postalCode_`$delivery_address_labels.postalCode.code`"]) }{$labels["postalCode_`$delivery_address_labels.postalCode.code`"]}{else}{$delivery_address_labels.postalCode.label}{/if}</label>

                                        <input value="{$customer->get('Customer Delivery Address Postal Code')}"  type="text" name="postalCode" class="{if 'postalCode'|in_array:$delivery_used_address_fields}{else}ignore{/if}" placeholder="{if !empty($labels["postalCode_`$delivery_address_labels.postalCode.code`"]) }{$labels["postalCode_`$delivery_address_labels.postalCode.code`"]}{else}{$delivery_address_labels.postalCode.label}{/if}">
                                        <b class="tooltip tooltip-bottom-right">{if !empty($labels["postalCode_`$delivery_address_labels.postalCode.code`"]) }{$labels["postalCode_`$delivery_address_labels.postalCode.code`"]}{else}{$delivery_address_labels.postalCode.label}{/if}</b>
                                    </label>
                                </section>


                            </div>

                            <div id="delivery_dependentLocality" class="row {if 'dependentLocality'|in_array:$delivery_used_address_fields}{else}hide{/if}">
                                <section class="col col-6 " >
                                    <label class="input">
                                        <label class="label">{if !empty($labels["dependentLocality_`$delivery_address_labels.dependentLocality.code`"]) }{$labels["dependentLocality_`$delivery_address_labels.dependentLocality.code`"]}{else}{$delivery_address_labels.dependentLocality.label}{/if}</label>

                                        <input value="{$customer->get('Customer Delivery Address Dependent Locality')}"  type="text" name="dependentLocality" class="{if 'dependentLocality'|in_array:$delivery_used_address_fields}{else}ignore{/if}" placeholder="{if !empty($labels["dependentLocality_`$delivery_address_labels.dependentLocality.code`"]) }{$labels["dependentLocality_`$delivery_address_labels.dependentLocality.code`"]}{else}{$delivery_address_labels.dependentLocality.label}{/if}">
                                        <b class="tooltip tooltip-bottom-right">{if !empty($labels["dependentLocality_`$delivery_address_labels.dependentLocality.code`"]) }{$labels["dependentLocality_`$delivery_address_labels.dependentLocality.code`"]}{else}{$delivery_address_labels.dependentLocality.label}{/if}</b>
                                    </label>
                                </section>

                            </div>

                            <div id="delivery_locality" class="row {if 'locality'|in_array:$delivery_used_address_fields}{else}hide{/if}">
                                <section class="col col-6 " >
                                    <label class="input">
                                        <label class="label">{if !empty($labels["locality_`$delivery_address_labels.locality.code`"]) }{$labels["locality_`$delivery_address_labels.locality.code`"]}{else}{$delivery_address_labels.locality.label}{/if}</label>

                                        <input value="{$customer->get('Customer Delivery Address Locality')}"  type="text" name="locality" class="{if 'locality'|in_array:$delivery_used_address_fields}{else}ignore{/if}" placeholder="{if !empty($labels["locality_`$delivery_address_labels.locality.code`"]) }{$labels["locality_`$delivery_address_labels.locality.code`"]}{else}{$delivery_address_labels.locality.label}{/if}">
                                        <b class="tooltip tooltip-bottom-right">{if !empty($labels["locality_`$delivery_address_labels.locality.code`"]) }{$labels["locality_`$delivery_address_labels.locality.code`"]}{else}{$delivery_address_labels.locality.label}{/if}</b>
                                    </label>
                                </section>

                            </div>


                            <div id="delivery_administrativeArea" class="row {if 'administrativeArea'|in_array:$delivery_used_address_fields}{else}hide{/if}">
                                <section class="col col-6 " >
                                    <label class="input">
                                        <label class="label">{if !empty($labels["administrativeArea_`$delivery_address_labels.administrativeArea.code`"]) }{$labels["administrativeArea_`$delivery_address_labels.administrativeArea.code`"]}{else}{$delivery_address_labels.administrativeArea.label}{/if}</label>

                                        <input value="{$customer->get('Customer Delivery Address Administrative Area')}"  type="text" name="administrativeArea" class="{if 'administrativeArea'|in_array:$delivery_used_address_fields}{else}ignore{/if}" placeholder="{if !empty($labels["administrativeArea_`$delivery_address_labels.administrativeArea.code`"]) }{$labels["administrativeArea_`$delivery_address_labels.administrativeArea.code`"]}{else}{$delivery_address_labels.administrativeArea.label}{/if}">
                                        <b class="tooltip tooltip-bottom-right">{if !empty($labels["administrativeArea_`$delivery_address_labels.administrativeArea.code`"]) }{$labels["administrativeArea_`$delivery_address_labels.administrativeArea.code`"]}{else}{$delivery_address_labels.administrativeArea.label}{/if}</b>
                                    </label>
                                </section>

                            </div>


                            <div class="row" >
                                <section class="col col-5">
                                    <label class="select">
                                        <select id="delivery_country_select" name="country">
                                            <option value="0" selected disabled>{if !empty($labels.address_country)}{$labels.address_country}{else}{t}Country{/t}{/if}</option>

                                            {foreach from=$countries item=country}
                                                <option value="{$country.2alpha}" {if $country.2alpha==$customer->get('Customer Delivery Address Country 2 Alpha Code')}selected{/if} >{$country.name}</option>
                                            {/foreach}


                                            <select><i></i>
                                    </label>
                                </section>


                            </div>


                        </fieldset>


                        <footer>
                            <button type="submit" class="button "  id="save_delivery_address_details_button" >{$data.labels._save_delivery_address_details_label} <i  class="margin_left_10 fa fa-fw fa-save" aria-hidden="true"></i> </button>

                        </footer>
                    </form>
                </div>

            </div>


    <div id="_orders_details" class="profile_block hide" style="width: auto">

        <h3 class="mirror_master" >{$data.labels._orders_title}</h3>

        <table class="orders">
            <thead>
            <tr>
                <th  class="text-left" id="_orders_th_number" >{if empty($data.labels._orders_th_number)}{t}Number{/t}{else}{$data.labels._orders_th_number}{/if}</th>
                <th  class="text-left" id="_orders_th_date" >{if empty($data.labels._orders_th_date)}{t}Date{/t}{else}{$data.labels._orders_th_date}{/if}</th>
                <th  class="text-left" id="_orders_th_status" >{if empty($data.labels._orders_th_status)}{t}Status{/t}{else}{$data.labels._orders_th_status}{/if}</th>
                <th  class="text-right" id="_orders_th_total" >{if empty($data.labels._orders_th_total)}{t}Total{/t}{else}{$data.labels._orders_th_total}{/if}</th>
                <th></th>


            </tr>
            </thead>
            <tbody>
            {assign "current_order_key"  $customer->get_order_in_process_key()}
            {foreach from=$customer->get_orders_data() item=_order}
                {if $current_order_key!=$_order.key}
                    <tr>




                        <td class="like_link" onclick="go_to_order({$_order.key})"><span >{$_order.number}</span></td>
                        <td>{$_order.date}</td>
                        <td>{$_order.state}</td>
                        <td class="text-right">{$_order.total}</td>
                        <td>
                            <a target="_blank" href="invoice.pdf.php?id={$_order.invoice_key}"><img class="button  {if !$_order.invoice_key}hide{/if}"  style="margin-left:50px;height:16px;position: relative;top:6px" src="/art/pdf.gif"></a>
                        </td>
                    </tr>
                {/if}
            {/foreach}
            </tbody>
        </table>


    </div>
    <div id="_order_details" class="profile_block hide" style="width: auto">



    </div>

           

<script>


    function go_to_order(order_key){
        $('.profile_block').addClass('hide')

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

        $('.profile_block').addClass('hide')
        $('#_orders_details').removeClass('hide')

    }

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




    $("#poll_details").validate(
        {

            submitHandler: function(form)
            {

                var button=$('#save_poll_details');

                if(button.hasClass('wait')){
                    return;
                }

                button.addClass('wait')
                button.find('i').removeClass('fa-save').addClass('fa-spinner fa-spin')


                var poll_data={ }

                $("#poll_details textarea:not(.ignore)").each(function(i, obj) {
                    if(!$(obj).attr('name')==''){
                        poll_data[$(obj).attr('name')]=$(obj).val()
                    }

                });

                $("#poll_details select:not(.ignore)").each(function(i, obj) {
                    if(!$(obj).attr('name')==''){


                        poll_data[$(obj).attr('name')]=$(obj).val()
                    }

                });


                //  register_data['pwd']=sha256_digest(register_data['pwd']);

                var ajaxData = new FormData();

                ajaxData.append("tipo", 'poll')

                ajaxData.append("data", JSON.stringify(poll_data))

                $.ajax({
                    url: "/ar_web_profile.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,
                    complete: function () {
                    }, success: function (data) {


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



            // Do not change code below
            errorPlacement: function(error, element)
            {
                error.insertAfter(element.parent());
            }
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


                        if (data.state == '200') {


                            for (var key in data.metadata.class_html) {
                                $('.' + key).html(data.metadata.class_html[key])
                            }


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
                            required: false,

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
                $('#invoice_'+index).find('label.label').html(value)

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


                        if (data.state == '200') {


                            for (var key in data.metadata.class_html) {
                                $('.' + key).html(data.metadata.class_html[key])
                            }


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
                $('#delivery_'+index).find('label.label').html(value)
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





    $(document).on('click', '.profile_button', function (evt) {


        $('.profile_button').addClass(' border-black color-gray-light ').removeClass(' border-orange-dark ').find('i').addClass('color-gray-light').removeClass('color-orange-dark')

        $(this).removeClass(' border-black color-gray-light ').addClass(' border-orange-dark ').find('i').removeClass('color-gray-light').addClass('color-orange-dark')

        $('.profile_block').addClass('hide')
        $('#'+$(this).data('tab')).removeClass('hide')
    });





</script>
