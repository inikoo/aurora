{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  11 February 2020  20:50::58  +0800, Kuala Lumpur, Malaysia
 Copyright (c) 2020, Inikoo

 Version 3
-->
*}




{if isset($data.top_margin)}{assign "top_margin" $data.top_margin}{else}{assign "top_margin" "0"}{/if}
{if isset($data.bottom_margin)}{assign "bottom_margin" $data.bottom_margin}{else}{assign "bottom_margin" "0"}{/if}

<style>
.new_order_options{
    display:flex;min-height:400px;
}
.new_order_option{
    flex-grow:1;
}
    .table_top{
        text-align:center    }

</style>

<div id="block_{$key}" data-block_key="{$key}" block="{$data.type}" class="{$data.type} {if !$data.show}hide{/if}" style="padding-top:0;padding-bottom:{$bottom_margin}px">

    <div class="type_new_order_chooser">
    <div class="sky-form" style="width: 100%;text-align: center;box-shadow: none;">
        <button id="order_for_new_customer"  style="float:none;display: inline;margin-top:40px" type="submit" class="button">{if !empty($data._order_for_new_customer)}{$data._order_for_new_customer}{else}{t}Create order for new customer{/t}{/if} <i  class=" far fa-fw fa-shopping-cart" aria-hidden="true"></i> </button>
        <div style="clear: both"></div>
    </div>

    <div class="acenter italic" >
        {t}or{/t}
    </div>

    <div class="sky-form" style="width: 100%;text-align: center;box-shadow: none;">
        <button id="order_for_existing_customer"  style="float:none;display: inline;margin-top:5px" type="submit" class="button">{if !empty($data._order_existing_customers)}{$data._order_existing_customers}{else}{t}Create order for existing customer{/t}{/if} <i  class=" far fa-fw fa-users" aria-hidden="true"></i> </button>
        <div style="clear: both"></div>
    </div>
    </div>


<div class="new_order_options">
    <div class="new_order_option order_for_existing_customer hide" >
        <div class="table_top">
            <span class="title" >{t}Order for existing customers{/t}</span>
        </div>
        <div id="table_container"></div>
    </div>

    <div class="new_order_option hide order_for_new_customer" >
        <div class="table_top">
            <span class="title" s>{t}Order for new customer{/t}</span>
        </div>





        <div class="reg_form hide" style="margin-top:0px;margin-bottom:60px;" >
            <form id="order_for_new_customer_form" class="sky-form">

                <fieldset>
                    <section >
                        <label  class="input " style="cursor:pointer" >

                            <i class="icon-append far fa-fingerprint" style="cursor:pointer"></i>
                            <input class="new_client_field" name="client_reference"
                                   placeholder="{if !empty($data.labels._client_reference_placeholder)}{$data.labels._client_reference_placeholder}{else}{t}Your customer id{/t}{/if}">
                            <b  class="tooltip tooltip-bottom-right">{if !empty($data.labels._client_reference_tooltip)}{$data.labels._client_reference_tooltip}{else}{t}Unique id associated with this customers{/t}{/if}</b>
                        </label>
                    </section>

                    <section >
                        <label class="input">
                            <i class="icon-append far fa-user"></i>
                            <input class="register_field" type="text" autocomplete="name" name="name"  placeholder="{if !empty($data.labels._contact_name_placeholder) }{$data.labels._contact_name_placeholder}{else}{t}Contact name{/t}{/if}">
                            <b   class="tooltip tooltip-bottom-right">{if !empty($data.labels._contact_name_tooltip)}{$data.labels._contact_name_tooltip}{else}{t}Contact name{/t}{/if}</b>
                        </label>
                    </section>

                    <section>
                        <label class="input">
                            <i class="icon-append far fa-store-alt"></i>
                            <input class="register_field" type="text" autocomplete="organization" name="organization"  placeholder="{if !empty($data.labels._company_placeholder)}{$data.labels._company_placeholder}{else}{t}Company name{/t}{/if}">
                            <b  class="tooltip tooltip-bottom-right">{if !empty($data.labels._company_tooltip)}{$data.labels._company_tooltip}{else}{t}Company name{/t}{/if}</b>
                        </label>
                    </section>

                    <section>
                        <label class="input">
                            <i class="icon-append far fa-envelope" ></i>
                            <input class="register_field" type="email" autocomplete="email"  name="email" placeholder="{if !empty($data.labels._email_placeholder)}{$data.labels._email_placeholder}{else}{t}Email{/t}{/if}">
                            <b   class="tooltip tooltip-bottom-right">{if !empty($data.labels._email_tooltip)}{$data.labels._email_tooltip}{else}{t}Email{/t}{/if}</b>
                        </label>
                    </section>
                    <section>
                        <label class="input">
                            <i class="icon-append far fa-mobile" ></i>
                            <input class="register_field" type="text" autocomplete="tel"  name="tel" placeholder="{if !empty($data.labels._mobile_placeholder)}{$data.labels._mobile_placeholder}{else}{t}Mobile{/t}{/if}">
                            <b class="tooltip tooltip-bottom-right">{if !empty($data.labels._mobile_tooltip)}{$data.labels._mobile_tooltip}{else}{t}Mobile{/t}{/if}</b>
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
                    <button  id="save_order_for_new_customer_button" type="submit" class="button" ">{if empty($labels._new_client_order)}{t}New order{/t}{else}{$labels._new_client_order}{/if} <i class="fa fa-fw fa-arrow-right"></i> </button>
                </footer>
                <div style="clear: both"></div>
            </form>
        </div>
    </div>
</div>




</div>




