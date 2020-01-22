{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 30 August 2017 at 21:34:58 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}

<div class="reg_form">
    <form id="registration_form" class="sky-form">
        <header id="_title">{$data.labels._title}</header>

        <fieldset>


            <section>
                <label class="input">
                    <i class="icon-append fa fa-envelope"></i>
                    <input class="register_field" type="email" autocomplete="email"  name="email" placeholder="{$data.labels._email_placeholder}">
                    <b id="_email_tooltip" class="tooltip tooltip-bottom-right">{$data.labels._email_tooltip}</b>
                </label>
            </section>

            <section>
                <label class="input">
                    <i id="_password" class="icon-append icon-lock"></i>
                    <input style=" touch-action: none;" class="register_field" type="password" autocomplete="new-password" name="new-password" id="register_password" placeholder="{$data.labels._password_placeholder}">
                    <b id="_password_tooltip" class="tooltip tooltip-bottom-right">{$data.labels._password_tooltip}</b>
                </label>
            </section>

            <section>
                <label class="input">
                    <i id="_password_confirm" class="icon-append icon-lock"></i>
                    <input class="register_field ignore" type="password" autocomplete="new-password"  name="password_confirm" placeholder="{$data.labels._password_confirm_placeholder}">
                    <b id="_password_confirm_tooltip" class="tooltip tooltip-bottom-right">{$data.labels._password_confirm_tooltip}</b>
                </label>
            </section>
        </fieldset>

        <fieldset>

            <section>
                <label class="input">
                    <i class="icon-append fa fa-mobile"></i>
                    <input class="register_field" type="text" autocomplete="tel"  name="tel"   placeholder="{$data.labels._mobile_placeholder}">
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
                        <input type="text" name="tax_number" placeholder="{if !empty($data.labels._tax_number)}{$data.labels._tax_number}{else}{t}Tax number{/t}{/if}">
                        <b id="_tax_number_tooltip" class="tooltip tooltip-bottom-right">{if !empty($data.labels._tax_number) }{$data.labels._tax_number}{else}{t}Tax number{/t}{/if}</b>

                    </label>
                </section>
                <section class="col col-6">
                    <label id="_registration_number" class="input" ">
                    <input type="text" name="registration_number"
                           placeholder="{if !empty($data.labels._registration_number) }{$data.labels._registration_number}{else}{t}Registration number{/t}{/if}">
                    <b id="_registration_number_tooltip"
                       class="tooltip tooltip-bottom-right">{if !empty($data.labels._registration_number) }{$data.labels._registration_number}{else}{t}Registration number{/t}{/if}</b>

                    </label>
                </section>
            </div>

        </fieldset>

        <fieldset id="address_fields" style="position:relative">


            <section id="addressLine1" class="{if 'addressLine1'|in_array:$used_address_fields}{else}hide{/if}">

                <label for="file" class="input">
                    <input type="text" name="addressLine1" class="{if 'addressLine1'|in_array:$used_address_fields}{else}ignore{/if}"
                           placeholder="{if !empty($labels.address_addressLine1) }{$labels.address_addressLine1}{else}{t}Address Line 1{/t}{/if}">
                    <b class="tooltip tooltip-bottom-right">{if !empty($labels.address_addressLine1) }{$labels.address_addressLine1}{else}{t}Address Line 1{/t}{/if}</b>
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
                               placeholder="{if !empty($labels.address_sorting_code)}{$labels.address_sorting_code}{else}{t}Sorting code{/t}{/if}">
                        <b class="tooltip tooltip-bottom-right">{if !empty($labels.address_sorting_code) }{$labels.address_sorting_code}{else}{t}Sorting code{/t}{/if}</b>
                    </label>
                </section>


            </div>

            <div id="postalCode" class="row {if 'postalCode'|in_array:$used_address_fields}{else}hide{/if}">
                <section class="col col-6 ">
                    <label class="input">
                        <input type="text" name="postalCode" class="{if 'postalCode'|in_array:$used_address_fields}{else}ignore{/if}"
                               placeholder="{if !empty($labels["postalCode_`$address_labels.postalCode.code`"]) }{$labels["postalCode_`$address_labels.postalCode.code`"]}{else}{$address_labels.postalCode.label}{/if}">
                        <b class="tooltip tooltip-bottom-right">{if !empty($labels["postalCode_`$address_labels.postalCode.code`"])}{$labels["postalCode_`$address_labels.postalCode.code`"]}{else}{$address_labels.postalCode.label}{/if}</b>
                    </label>
                </section>


            </div>

            <div id="dependentLocality" class="row {if 'dependentLocality'|in_array:$used_address_fields}{else}hide{/if}">
                <section class="col col-6 ">
                    <label class="input">
                        <input type="text" name="dependentLocality" class="{if 'dependentLocality'|in_array:$used_address_fields}{else}ignore{/if}"
                               placeholder="{if !empty($labels["dependentLocality_`$address_labels.dependentLocality.code`"])}{$labels["dependentLocality_`$address_labels.dependentLocality.code`"]}{else}{$address_labels.dependentLocality.label}{/if}">
                        <b class="tooltip tooltip-bottom-right">{if !empty($labels["dependentLocality_`$address_labels.dependentLocality.code`"]) }{$labels["dependentLocality_`$address_labels.dependentLocality.code`"]}{else}{$address_labels.dependentLocality.label}{/if}</b>
                    </label>
                </section>

            </div>

            <div id="locality" class="row {if 'locality'|in_array:$used_address_fields}{else}hide{/if}">
                <section class="col col-6 ">
                    <label class="input">
                        <input type="text" name="locality" class="{if 'locality'|in_array:$used_address_fields}{else}ignore{/if}"
                               placeholder="{if !empty($labels["locality_`$address_labels.locality.code`"]) }{$labels["locality_`$address_labels.locality.code`"]}{else}{$address_labels.locality.label}{/if}">
                        <b class="tooltip tooltip-bottom-right"></b>
                    </label>
                </section>

            </div>


            <div id="administrativeArea" class="row {if 'administrativeArea'|in_array:$used_address_fields}{else}hide{/if}">
                <section class="col col-6 ">
                    <label class="input">
                        <input type="text" name="administrativeArea" class="{if 'administrativeArea'|in_array:$used_address_fields}{else}ignore{/if}"
                               placeholder="{if !empty($labels["administrativeArea_`$address_labels.administrativeArea.code`"]) }{$labels["administrativeArea_`$address_labels.administrativeArea.code`"]}{else}{$address_labels.administrativeArea.label}{/if}">
                        <b class="tooltip tooltip-bottom-right">{if !empty($labels["administrativeArea_`$address_labels.administrativeArea.code`"])}{$labels["administrativeArea_`$address_labels.administrativeArea.code`"]}{else}{$address_labels.administrativeArea.label}{/if}</b>
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

           
