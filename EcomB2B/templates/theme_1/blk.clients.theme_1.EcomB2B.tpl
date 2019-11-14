{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: Fri 25 Oct 2019 21:24:48 +0800 MYT, Kuala Lumpur , Malaysia
 Copyright (c) 2019, Inikoo

 Version 3
-->
*}




{if isset($data.top_margin)}{assign "top_margin" $data.top_margin}{else}{assign "top_margin" "0"}{/if}
{if isset($data.bottom_margin)}{assign "bottom_margin" $data.bottom_margin}{else}{assign "bottom_margin" "0"}{/if}


<div id="block_{$key}" data-block_key="{$key}" block="{$data.type}" class="{$data.type}   {if !$data.show}hide{/if}" style="padding-top:{$top_margin}px;padding-bottom:{$bottom_margin}px">
    <div class=" container ">

        <div style="display:flex;width: 100%">
            <div style="flex-grow:1">
                <h1>
                    {if !empty($data.labels.title)}{$data.labels.title}{else}{t}Customers{/t}{/if}
                </h1>
            </div>

            <div style="flex-grow:1;text-align: right">
                <a href="#new_client_form" class="modal-opener">
                    <button class="empty" style="cursor:pointer;line-height30px;padding:10px 20px;text-align: center;border:none;position: relative;top:-20px;font-size: 16px"> <i class="fa fa-plus padding_right_5"></i>
                        {if empty($labels._add_customer_client)}{t}Add customer{/t}{else}{$labels._add_customer_client}{/if}</span>
                    </button>
                </a>


            </div>
        </div>
        <table id="clients" class="display" style="width:100%">
            <thead>
            <tr>
                <th>Code</th>
                <th>Name</th>
            </tr>
            </thead>

        </table>

    </div>

</div>



<div class="reg_form" >
    <form id="new_client_form" class="sky-form sky-form-modal">
        <header >{if empty($labels._new_customer_client)}{t}New customer{/t}{else}{$labels._new_customer_client}{/if}</header>


        <fieldset>

            <section>
                <label " class="input " style="cursor:pointer" >

                    <i class="icon-append far fa-fingerprint" style="cursor:pointer"></i>
                    <input class="new_client_field" name="client_reference"
                           placeholder="{if !empty($data.labels._client_reference_placeholder)}{$data.labels._client_reference_placeholder}{else}{t}Unique customer reference{/t}{/if}">
                    <b  class="tooltip tooltip-bottom-right">{if !empty($data.labels._client_reference_tooltip)}{$data.labels._client_reference_tooltip}{else}{t}Reference{/t}{/if}</b>
                </label>
            </section>

        </fieldset>

        <fieldset>



        </fieldset>

        <fieldset>
            <section>
                <label class="input">
                    <i class="icon-append far fa-user"></i>
                    <input class="register_field" type="text" autocomplete="name" name="name"  placeholder="{if !empty($labels._contact_name_placeholder) }{$data.labels._contact_name_placeholder}{else}{t}Contact name{/t}{/if}">
                    <b   class="tooltip tooltip-bottom-right">{$data.labels._contact_name_tooltip}</b>
                </label>
            </section>

            <section>
                <label class="input">
                    <i class="icon-append far fa-store-alt"></i>
                    <input class="register_field" type="text" autocomplete="organization" name="organization"  placeholder="{$data.labels._company_placeholder}">
                    <b  class="tooltip tooltip-bottom-right">{$data.labels._company_tooltip}</b>
                </label>
            </section>
            <section>
                <label class="input">
                    <i class="icon-append far fa-envelope" ></i>
                    <input class="register_field" type="email" autocomplete="email"  name="email" placeholder="{$data.labels._email_placeholder}">
                    <b   class="tooltip tooltip-bottom-right">{$data.labels._email_tooltip}</b>
                </label>
            </section>
            <section>
                <label class="input">
                    <i class="icon-append far fa-mobile" ></i>
                    <input class="register_field" type="text" autocomplete="tel"  name="tel" placeholder="{$data.labels._mobile_placeholder}">
                    <b class="tooltip tooltip-bottom-right">{$data.labels._mobile_tooltip}</b>
                </label>
            </section>


        </fieldset>
        <fieldset id="address_fields" style="position:relative">
            <section id="addressLine1" class="{if 'addressLine1'|in_array:$used_address_fields}{else}hide{/if}">
                <label for="file" class="input">
                    <input type="text"  name="addressLine1" class="{if 'addressLine1'|in_array:$used_address_fields}{else}ignore{/if}" placeholder="{if !empty($labels.address_addressLine1) }{$labels.address_addressLine1}{else}{t}Address Line 1{/t}{/if}">
                    <b class="tooltip tooltip-bottom-right">{if !empty($labels.address_addressLine1) }{$labels.address_addressLine1}{else}{t}Address Line 1{/t}{/if}</b>
                </label>
            </section>
            <section id="addressLine2" class="{if 'addressLine2'|in_array:$used_address_fields}{else}hide{/if}">
                <label for="file" class="input">
                    <input type="text" name="addressLine2" class="{if 'addressLine2'|in_array:$used_address_fields}{else}ignore{/if}" placeholder="{if !empty($labels.address_addressLine2) }{$labels.address_addressLine2}{else}{t}Address Line 2{/t}{/if}">
                    <b class="tooltip tooltip-bottom-right">{if !empty($labels.address_addressLine2) }{$labels.address_addressLine2}{else}{t}Address Line 2{/t}{/if}</b>
                </label>
            </section>
            <div id="sortingCode" class="row {if 'sortingCode'|in_array:$used_address_fields}{else}hide{/if}">
                <section class="col col-6 " >
                    <label class="input">
                        <input type="text" name="sortingCode" class="{if 'sortingCode'|in_array:$used_address_fields}{else}ignore{/if}" placeholder="{if !empty($labels.address_sorting_code) }{$labels.address_sorting_code}{else}{t}Sorting code{/t}{/if}">
                        <b class="tooltip tooltip-bottom-right">{if !empty($labels.address_sorting_code) }{$labels.address_sorting_code}{else}{t}Sorting code{/t}{/if}</b>
                    </label>
                </section>
            </div>
            <div id="postalCode" class="row {if 'postalCode'|in_array:$used_address_fields}{else}hide{/if}">
                <section class="col col-6 " >
                    <label class="input">
                        <input type="text" name="postalCode" class="{if 'postalCode'|in_array:$used_address_fields}{else}ignore{/if}" placeholder="{if !empty($labels["postalCode_`$address_labels.postalCode.code`"]) }{$labels["postalCode_`$address_labels.postalCode.code`"]}{else}{$address_labels.postalCode.label}{/if}">
                        <b class="tooltip tooltip-bottom-right">{if !empty($labels["postalCode_`$address_labels.postalCode.code`"]) }{$labels["postalCode_`$address_labels.postalCode.code`"]}{else}{$address_labels.postalCode.label}{/if}</b>
                    </label>
                </section>
            </div>
            <div id="dependentLocality" class="row {if 'dependentLocality'|in_array:$used_address_fields}{else}hide{/if}">
                <section class="col col-6 " >
                    <label class="input">
                        <input type="text" name="dependentLocality" class="{if 'dependentLocality'|in_array:$used_address_fields}{else}ignore{/if}" placeholder="{if !empty($labels["dependentLocality_`$address_labels.dependentLocality.code`"]) }{$labels["dependentLocality_`$address_labels.dependentLocality.code`"]}{else}{$address_labels.dependentLocality.label}{/if}">
                        <b class="tooltip tooltip-bottom-right">{if !empty($labels["dependentLocality_`$address_labels.dependentLocality.code`"]) }{$labels["dependentLocality_`$address_labels.dependentLocality.code`"]}{else}{$address_labels.dependentLocality.label}{/if}</b>
                    </label>
                </section>

            </div>
            <div id="locality" class="row {if 'locality'|in_array:$used_address_fields}{else}hide{/if}">
                <section class="col col-6 " >
                    <label class="input">
                        <input type="text" name="locality" class="{if 'locality'|in_array:$used_address_fields}{else}ignore{/if}" placeholder="{if !empty($labels["locality_`$address_labels.locality.code`"]) }{$labels["locality_`$address_labels.locality.code`"]}{else}{$address_labels.locality.label}{/if}">
                        <b class="tooltip tooltip-bottom-right"></b>
                    </label>
                </section>

            </div>
            <div id="administrativeArea" class="row {if 'administrativeArea'|in_array:$used_address_fields}{else}hide{/if}">
                <section class="col col-6 " >
                    <label class="input">
                        <input type="text" name="administrativeArea" class="{if 'administrativeArea'|in_array:$used_address_fields}{else}ignore{/if}" placeholder="{if !empty($labels["administrativeArea_`$address_labels.administrativeArea.code`"])}{$labels["administrativeArea_`$address_labels.administrativeArea.code`"]}{else}{$address_labels.administrativeArea.label}{/if}">
                        <b class="tooltip tooltip-bottom-right">{if !empty($labels["administrativeArea_`$address_labels.administrativeArea.code`"]) }{$labels["administrativeArea_`$address_labels.administrativeArea.code`"]}{else}{$address_labels.administrativeArea.label}{/if}</b>
                    </label>
                </section>

            </div>
            <div class="row" >
                <section class="col col-5">
                    <label class="select">
                        <select id="country_select" name="country">
                            <option value="0" selected disabled>{if !empty($labels.address_country) }{$labels.address_country}{else}{t}Country{/t}{/if}</option>
                            {foreach from=$countries item=country}
                                <option value="{$country.2alpha}" {if $country.2alpha==$selected_country}selected{/if} >{$country.name}</option>
                            {/foreach}

                            <select>
                    </label>
                </section>
            </div>
        </fieldset>

        <footer>
            <button  id="save_new_client_button" type="submit" class="button" ">{$data.labels._submit_label}  <i class="fa fa-fw fa-arrow-right"></i> </button>
        </footer>
    </form>
</div>

