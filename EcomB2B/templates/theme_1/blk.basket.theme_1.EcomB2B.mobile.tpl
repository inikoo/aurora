{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 4 September 2017 at 13:03:01 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}


{assign "items_data" $order->get_items()}
{assign "interactive_charges_data" $order->get_interactive_charges_data()}
{assign "interactive_deal_component_data" $order->get_interactive_deal_component_data()}

<div id="block_{$key}"  class="{$data.type} _block  " style="Width:100%;margin-top: 10px" >
    <div class="content" style="padding: 0px 10px">
        <h4>{if !empty($labels._order_number_label)}{$labels._order_number_label}{else}{t}Order number{/t}{/if} <span class="order_number">{$order->get('Public ID')}</span></h4>
        <div class="one-half">
                    <h5 style="font-size: 90%;font-weight: 800;color: #333">

                        <span id="delivery_label" class="{if $order->get('Order For Collection')=='Yes'}hide{/if}">
                        <i id="_delivery_address_icon" class="fal fa-fw fa-clipboard   " aria-hidden="true"></i>
                        <span id="_delivery_address_label"  >{if !empty($labels._delivery_address_label) }{$labels._delivery_address_label}{else}{t}Delivery Address:{/t}{/if}</span>
                        </span>
                        <span  id="collection_label" class="{if $order->get('Order For Collection')=='No'}hide{/if} "">
                        <i id="_delivery_address_icon" class="fa fa-fw fa-hand-rock   aria-hidden="true"></i>
                        <span id="_delivery_address_label"  >{if !empty($labels._for_collecion_label) }{$labels._for_collecion_label}{else}{t}To be collected at:{/t}{/if}</span>

                        </span>
                        <a href="#order_delivery_address_form" class="modal-opener"><i class="fa fa-fw fa-pencil padding_left_5 discreet_on_hover like_button" aria-hidden="true"></i></a>


                    </h5>
                    <div class="formatted_delivery_address single_line_height">{$order->get('Order Delivery Address Formatted')}</div>



                </div>
        <div class="one-half last-column">
                    <h5 style="position: relative;left:-5px;;font-size: 90%;font-weight: 800;color: #333">
                        <i id="_invoice_address_icon" class="fa fa-fw fa-dollar-sign" aria-hidden="true"></i>
                        <span id="_invoice_address_label"  >{if !empty($labels._invoice_address_label) }{$labels._invoice_address_label}{else}{t}Invoice Address{/t}{/if}</span>

                        <a href="#order_invoice_address_form" class="modal-opener"><i class="fa fa-fw fa-pencil padding_left_5 discreet_on_hover like_button" aria-hidden="true"></i></a>


                    </h5>

                    <div class="formatted_invoice_address single_line_height">{$order->get('Order Invoice Address Formatted')}</div>

                </div>
        <div class="clear"></div>
        <table class="order_totals">




                        <tbody>
                        <tr class="order_items_gross_container  {if $order->get('Order Items Discount Amount')==0 }hide{/if}">
                            <td>{if !empty($labels._items_gross) }{$labels._items_gross}{else}{t}Items Gross{/t}{/if}</td>

                            <td class=" text-right order_items_gross ">{$order->get('Items Gross Amount')}</td>
                        </tr>
                        <tr class="order_items_discount_container last_items {if $order->get('Order Items Discount Amount')==0 }hide{/if}">
                            <td>{if !empty($labels._items_discounts) }{$labels._items_discounts}{else}{t}Items Discounts{/t}{/if}</td>

                            <td class="text-right order_items_discount">{$order->get('Basket Items Discount Amount')}</td>
                        </tr>
                        <tr>
                            <td>{if !empty($labels._items_net) }{$labels._items_net}{else}{t}Items Net{/t}{/if}</td>

                            <td class="text-right order_items_net">{$order->get('Items Net Amount')}</td>
                        </tr>
                        <tr class="Deal_Amount_Off_tr  {if $order->get('Order Deal Amount Off')==0}hide{/if}">
                            <td>{if !empty($labels._amount_off)}{$labels._amount_off}{else}{t}Amount off{/t}{/if}</td>
                            <td class="text-right Deal_Amount_Off">{$order->get('Deal Amount Off')}</td>
                        </tr>

                        <tr class="order_charges_container {if $order->get('Order Charges Net Amount')==0 }very_discreet{/if}">
                            <td>

                                {if !empty($labels._items_charges)}{$labels._items_charges}{else}{t}Charges{/t}{/if}  <i class=" order_charges_info fa fa-info-circle padding_left_5  info {if $order->get('Order Charges Net Amount')==0 }hide{/if}"    style="color: #007fff;" onclick="show_charges_info()" ></i>
                            </td>

                            <td class="text-right order_charges">{$order->get('Charges Net Amount')}</td>
                        </tr>


                        <tr class="last_items">
                            <td>{if !empty($labels._items_shipping) }{$labels._items_shipping}{else}{t}Shipping{/t}{/if}</td>
                            <td class="text-right order_shipping">{if $order->get('Shipping Net Amount')=='TBC'}<i class="fa error fa-exclamation-circle" title="" aria-hidden="true"></i> <small>{if !empty($labels._we_will_contact_you)}{$labels._we_will_contact_you}{else}{t}We will contact you{/t}{/if}</small>{else}{$order->get('Shipping Net Amount')}{/if}</td>

                        </tr>
                        <tr class="net">
                            <td>{if !empty($labels._total_net) }{$labels._total_net}{else}{t}Total Net{/t}{/if}</td>

                            <td class="text-right order_net">{$order->get('Total Net Amount')}</td>
                        </tr>
                        <tr class="tax ">
                            <td>{if !empty($labels._total_tax) }{$labels._total_tax}{else}{t}Tax{/t}{/if}<div class="tax_description" style="font-size: small">{$order->get('Tax Description')}</div></td>

                            <td class="text-right order_tax">{$order->get('Total Tax Amount')}</td>
                        </tr>
                        <tr class="total">
                            <td>{if !empty($labels._total) }{$labels._total}{else}{t}Total{/t}{/if}</td>

                            <td class="text-right order_total">{$order->get('Total')}</td>
                        </tr>

                        <tr class="payments_amount_tr {if $order->get('Order Payments Amount')==0}hide{/if}" >
                            <td>{if !empty($labels._order_paid_amount) }{$labels._order_paid_amount}{else}{t}Paid{/t}{/if}</td>

                            <td class="text-right payments_amount">{$order->get('Basket Payments Amount')}</td>
                        </tr>
                        <tr class="available_credit_amount_tr tax  {if $order->get('Order Available Credit Amount')==0}hide{/if}" >
                            <td>{if !empty($labels._order_available_credit_amount) }{$labels._order_available_credit_amount}{else}{t}Credit{/t}{/if}</td>

                            <td class="text-right available_credit_amount ">{$order->get('Available Credit Amount')}</td>
                        </tr>
                        <tr class="to_pay_amount_tr total {if $order->get('Order Payments Amount')==0 and $order->get('Order Available Credit Amount')==0 }hide{/if} " >
                            <td>{if !empty($labels._order_to_pay_amount) }{$labels._order_to_pay_amoount}{else}{t}To pay{/t}{/if}</td>

                            <td class="text-right to_pay_amount">{$order->get('Basket To Pay Amount')}</td>
                        </tr>

                        </tbody>
                    </table>
        {assign "voucher_info" $order->voucher_formatted_info()}

        <div class="container order basket   " style="margin-bottom: 20px">
            <span class="basket_order_items" data-scope="customer">
            {include file="theme_1/_order_items.theme_1.EcomB2B.mobile.tpl" edit=true hide_title=true   items_data=$items_data }
            </span>
            <div style="margin-top:5px">
                {$voucher_info}

            </div>


        </div>
        <div class="container" >

                <div class="one_half">

                    <form action="" method="post" enctype="multipart/form-data"  class="sky-form"  style="box-shadow: none">



                    {assign "voucher_code" $order->get_voucher_code()}





                    <section >

                        <div class="row"  id="voucher"  >

                            <section class="col col-6" style="width: 50%;float: left">
                                <label class="input">
                                    <input type="text" name="voucher_code" id="voucher_code"  value="{$voucher_code}"  data-old_value="{$voucher_code}" placeholder="{$data._voucher}">
                                    <i class="icon-append fal fa-money-bill-wave"></i>

                                </label>
                            </section>
                            <section class="col col-6" style="width: 50%;;float: left">
                                <button  style="margin:0px;display: none" type="submit" class="button "
                                         data-add_label="{if empty($data._voucher_label)}{t}Add Voucher{/t}{else}{$data._voucher_label}{/if}"
                                         data-update_label="{if empty($data._change_voucher_label)}{t}Update Voucher{/t}{else}{$data._change_voucher_label}{/if}"
                                         data-remove_label="{if empty($data._remove_voucher_label)}{t}Remove Voucher{/t}{else}{$data._remove_voucher_label}{/if}"
                                >
                                    <span>
                                    {if $voucher_code==''}
                                        {if empty($data._voucher_label)}{t}Add Voucher{/t}{else}{$data._voucher_label}{/if}
                                    {else}
                                        {if empty($data._change_voucher_label)}{t}Update Voucher{/t}{else}{$data._change_voucher_label}{/if}
                                    {/if}</span> <i style="margin-left: 5px" class=" fa fa-fw {if $voucher_code==''}fa-plus{else}fa-sync-alt{/if}"></i> </button>

                            </section>
                        </div>




                    </section>


                    <section style="display:none" >

                        <div class="row"    >
                            <section class="col col-6">
                                <label class="input">
                                    <i class="icon-append fa fa-tag"></i>
                                    <input type="text" name="name" id="name" placeholder="{$data._voucher}">
                                </label>
                            </section>
                            <section class="col col-6">
                                <button style="margin:0px" type="submit" class="button">{$data._voucher_label}</button>

                            </section>
                        </div>




                    </section>



                    <section style="border: none">
                                <label class="textarea">

                                    <textarea id="special_instructions" rows="5" style="height: 60px" name="comment" placeholder="{$data._special_instructions}">{$order->get('Order Customer Message')}</textarea>
                                </label>
                            </section>


                    </form>



                </div>

                <div class="one_half last">

                    <form action="" method="post" enctype="multipart/form-data"  class="sky-form " style="box-shadow: none">

                        <section class="col col-6   ">
                            <button id="basket_go_to_checkout" onclick="$(this).find('i').addClass('fa-spinner fa-spin'); window.location = 'checkout.sys'"  style="margin:0px;{if $order->get('Products')==0 }display:none{/if}" type="submit" class="button  ">{$data._go_checkout_label} <i  class=" fa fa-fw fa-arrow-right" aria-hidden="true"></i> </button>

                            <button id="basket_continue_shopping" onclick="$(this).find('i').addClass('fa-spinner fa-spin'); window.location = '/'"  style="margin:0px;{if $order->get('Products')!=0 }display:none{/if}" type="submit" class="button"><i  class=" fa fa-fw fa-arrow-left" aria-hidden="true"></i> {if !empty($data._go_shop_label)}{$data._go_shop_label}{else}{t}Continue shopping{/t}{/if}  </button>

                        </section>


                    </form>

                </div>




            </div>
    </div>
</div>

<div style="z-index: 3001" class="address_form" >
    <form id="order_delivery_address_form" class="sky-form sky-form-modal">
        <header id="_title">{if !empty($labels._delivery_address_label) }{$labels._delivery_address_label}{else}{t}Delivery Address{/t}{/if}</header>

        <fieldset  class="{if $store->get('Store Can Collect')=='No' or $store->get('Store Collect Address Country 2 Alpha Code')==''   }hide{/if} "  >


            <section>
                <label class="checkbox"><input class="ignored " type="checkbox"   {if  $store->get('Store Can Collect')=='Yes' and  $order->get('Order For Collection')=='Yes'}checked{/if} name="order_for_collection" id="order_for_collection"><i></i>{if empty($content._order_for_collection)}{t}Order for collection{/t}{else}{$content._order_for_collection}{/if}</label>
                </a> </label>


            </section>



        </fieldset>




        <fieldset id="order_delivery_address_fields" class=" {if $order->get('Order For Collection')=='Yes'  and  $store->get('Store Can Collect')=='Yes'}hide{/if}" style="position:relative">



            <section id="order_delivery_addressLine1" class="{if 'addressLine1'|in_array:$delivery_used_address_fields}{else}hide{/if}">

                <label for="file" class="input">
                    <label class="label">{if !empty($labels.address_addressLine1) }{$labels.address_addressLine1}{else}{t}Address Line 1{/t}{/if}</label>

                    <input value="{$order->get('Order Delivery Address Line 1')}" type="text"  name="addressLine1" class="{if 'addressLine1'|in_array:$delivery_used_address_fields}{else}ignore{/if}" placeholder="{if !empty($labels.address_addressLine1) }{$labels.address_addressLine1}{else}{t}Address Line 1{/t}{/if}">
                    <b class="tooltip tooltip-bottom-right">{if !empty($labels.address_addressLine1)}{$labels.address_addressLine1}{else}{t}Address Line 1{/t}{/if}</b>
                </label>
            </section>

            <section id="order_delivery_addressLine2" class="{if 'addressLine2'|in_array:$delivery_used_address_fields}{else}hide{/if}">
                <label for="file" class="input">
                    <label class="label">{if !empty($labels.address_addressLine2) }{$labels.address_addressLine2}{else}{t}Address Line 2{/t}{/if}</label>

                    <input  value="{$order->get('Order Delivery Address Line 2')}"  type="text" name="addressLine2" class="{if 'addressLine2'|in_array:$delivery_used_address_fields}{else}ignore{/if}" placeholder="{if !empty($labels.address_addressLine2) }{$labels.address_addressLine2}{else}{t}Address Line 2{/t}{/if}">
                    <b class="tooltip tooltip-bottom-right">{if !empty($labels.address_addressLine2) }{$labels.address_addressLine2}{else}{t}Address Line 2{/t}{/if}</b>
                </label>
            </section>



            <div id="order_delivery_sortingCode" class="row {if 'sortingCode'|in_array:$delivery_used_address_fields}{else}hide{/if}">
                <section class="col col-6 " >
                    <label class="input">
                        <label class="label">{if !empty($labels.address_sorting_code) }{$labels.address_sorting_code}{else}{t}Sorting code{/t}{/if}</label>

                        <input value="{$order->get('Order Delivery Address Sorting Code')}"  type="text" name="sortingCode" class="{if 'sortingCode'|in_array:$delivery_used_address_fields}{else}ignore{/if}" placeholder="{if !empty($labels.address_sorting_code) }{$labels.address_sorting_code}{else}{t}Sorting code{/t}{/if}">
                        <b class="tooltip tooltip-bottom-right">{if !empty($labels.address_sorting_code)}{$labels.address_sorting_code}{else}{t}Sorting code{/t}{/if}</b>
                    </label>
                </section>


            </div>

            <div id="order_delivery_postalCode" class="row {if 'postalCode'|in_array:$delivery_used_address_fields}{else}hide{/if}">
                <section class="col col-6 " >
                    <label class="input">
                        <label class="label">{if !empty($labels["postalCode_`$delivery_address_labels.postalCode.code`"]) }{$labels["postalCode_`$delivery_address_labels.postalCode.code`"]}{else}{$delivery_address_labels.postalCode.label}{/if}</label>

                        <input value="{$order->get('Order Delivery Address Postal Code')}"  type="text" name="postalCode" class="{if 'postalCode'|in_array:$delivery_used_address_fields}{else}ignore{/if}"
                               placeholder="{if !empty($labels["postalCode_`$delivery_address_labels.postalCode.code`"]) }{$labels["postalCode_`$delivery_address_labels.postalCode.code`"]}{else}{$delivery_address_labels.postalCode.label}{/if}"
                        >
                        <b class="tooltip tooltip-bottom-right">{if !empty($labels["postalCode_`$delivery_address_labels.postalCode.code`"]) }{$labels["postalCode_`$delivery_address_labels.postalCode.code`"]}{else}{$delivery_address_labels.postalCode.label}{/if}</b>
                    </label>
                </section>


            </div>

            <div id="order_delivery_dependentLocality" class="row {if 'dependentLocality'|in_array:$delivery_used_address_fields}{else}hide{/if}">
                <section class="col col-6 " >
                    <label class="input">
                        <label class="label">{if !empty($labels["dependentLocality_`$delivery_address_labels.dependentLocality.code`"]) }{$labels["dependentLocality_`$delivery_address_labels.dependentLocality.code`"]}{else}{$delivery_address_labels.dependentLocality.label}{/if}</label>

                        <input value="{$order->get('Order Delivery Address Dependent Locality')}"  type="text" name="dependentLocality" class="{if 'dependentLocality'|in_array:$delivery_used_address_fields}{else}ignore{/if}" placeholder="{if !empty($labels["dependentLocality_`$delivery_address_labels.dependentLocality.code`"])}{$labels["dependentLocality_`$delivery_address_labels.dependentLocality.code`"]}{else}{$delivery_address_labels.dependentLocality.label}{/if}">
                        <b class="tooltip tooltip-bottom-right">{if !empty($labels["dependentLocality_`$delivery_address_labels.dependentLocality.code`"]) }{$labels["dependentLocality_`$delivery_address_labels.dependentLocality.code`"]}{else}{$delivery_address_labels.dependentLocality.label}{/if}</b>
                    </label>
                </section>

            </div>

            <div id="order_delivery_locality" class="row {if 'locality'|in_array:$delivery_used_address_fields}{else}hide{/if}">
                <section class="col col-6 " >
                    <label class="input">
                        <label class="label">{if !empty($labels["locality_`$delivery_address_labels.locality.code`"]) }{$labels["locality_`$delivery_address_labels.locality.code`"]}{else}{$delivery_address_labels.locality.label}{/if}</label>

                        <input value="{$order->get('Order Delivery Address Locality')}"  type="text" name="locality" class="{if 'locality'|in_array:$delivery_used_address_fields}{else}ignore{/if}" placeholder="{if !empty($labels["locality_`$delivery_address_labels.locality.code`"]) }{$labels["locality_`$delivery_address_labels.locality.code`"]}{else}{$delivery_address_labels.locality.label}{/if}">
                        <b class="tooltip tooltip-bottom-right">{if !empty($labels["locality_`$delivery_address_labels.locality.code`"]) }{$labels["locality_`$delivery_address_labels.locality.code`"]}{else}{$delivery_address_labels.locality.label}{/if}</b>
                    </label>
                </section>

            </div>


            <div id="order_delivery_administrativeArea" class="row {if 'administrativeArea'|in_array:$delivery_used_address_fields}{else}hide{/if}">
                <section class="col col-6 " >
                    <label class="input">
                        <label class="label">{if !empty($labels["administrativeArea_`$delivery_address_labels.administrativeArea.code`"]) }{$labels["administrativeArea_`$delivery_address_labels.administrativeArea.code`"]}{else}{$delivery_address_labels.administrativeArea.label}{/if}</label>
                        <input value="{$order->get('Order Delivery Address Administrative Area')}"  type="text" name="administrativeArea" class="{if 'administrativeArea'|in_array:$delivery_used_address_fields}{else}ignore{/if}" placeholder="{if !empty($labels["administrativeArea_`$delivery_address_labels.administrativeArea.code`"]) }{$labels["administrativeArea_`$delivery_address_labels.administrativeArea.code`"]}{else}{$delivery_address_labels.administrativeArea.label}{/if}">
                        <b class="tooltip tooltip-bottom-right">{if !empty($labels["administrativeArea_`$delivery_address_labels.administrativeArea.code`"]) }{$labels["administrativeArea_`$delivery_address_labels.administrativeArea.code`"]}{else}{$delivery_address_labels.administrativeArea.label}{/if}</b>
                    </label>
                </section>

            </div>


            <div class="row" >
                <section class="col col-5">
                    <label class="select">
                        <select id="order_delivery_country_select" name="country">
                            <option value="0" selected disabled>{if !empty($labels.address_country) }{$labels.address_country}{else}{t}Country{/t}{/if}</option>

                            {foreach from=$countries item=country}
                                <option value="{$country.2alpha}" {if $country.2alpha==$order->get('Order Delivery Address Country 2 Alpha Code')}selected{/if} >{$country.name}</option>
                            {/foreach}


                            <select><i></i>
                    </label>
                </section>


            </div>


        </fieldset>


        <footer>
            <button type="submit" class="button "  id="save_order_delivery_address_button" >{if !empty($labels._save) }{$labels._save}{else}{t}Save{/t}{/if}  <i  class="margin_left_10 fa fa-fw fa-save" aria-hidden="true"></i> </button>
            <a href="#" class="button button-secondary modal-closer">{if !empty($labels._close) }{$labels._close}{else}{t}Close{/t}{/if}</a>
        </footer>
    </form>
</div>
<div style="z-index: 3001" class="address_form" >
    <form id="order_invoice_address_form" class="sky-form sky-form-modal">
        <header id="_title">{if !empty($labels._invoice_address_label)}{$labels._invoice_address_label}{else}{t}Invoice address{/t}{/if}</header>






        <fieldset id="order_invoice_address_fields" class=" " style="position:relative">



            <section id="order_invoice_addressLine1" class="{if 'addressLine1'|in_array:$invoice_used_address_fields}{else}hide{/if}">

                <label for="file" class="input">
                    <label class="label">{if !empty($labels.address_addressLine1) }{$labels.address_addressLine1}{else}{t}Address Line 1{/t}{/if}</label>

                    <input value="{$order->get('Order Invoice Address Line 1')}" type="text"  name="addressLine1" class="{if 'addressLine1'|in_array:$invoice_used_address_fields}{else}ignore{/if}" placeholder="{if !empty($labels.address_addressLine1) }{$labels.address_addressLine1}{else}{t}Address Line 1{/t}{/if}">
                    <b class="tooltip tooltip-bottom-right">{if !empty($labels.address_addressLine1) }{$labels.address_addressLine1}{else}{t}Address Line 1{/t}{/if}</b>
                </label>
            </section>

            <section id="order_invoice_addressLine2" class="{if 'addressLine2'|in_array:$invoice_used_address_fields}{else}hide{/if}">
                <label for="file" class="input">
                    <label class="label">{if !empty($labels.address_addressLine2) }{$labels.address_addressLine2}{else}{t}Address Line 2{/t}{/if}</label>

                    <input  value="{$order->get('Order Invoice Address Line 2')}"  type="text" name="addressLine2" class="{if 'addressLine2'|in_array:$invoice_used_address_fields}{else}ignore{/if}" placeholder="{if !empty($labels.address_addressLine2) }{$labels.address_addressLine2}{else}{t}Address Line 2{/t}{/if}">
                    <b class="tooltip tooltip-bottom-right">{if !empty($labels.address_addressLine2)} {$labels.address_addressLine2}{else}{t}Address Line 2{/t}{/if}</b>
                </label>
            </section>



            <div id="order_invoice_sortingCode" class="row {if 'sortingCode'|in_array:$invoice_used_address_fields}{else}hide{/if}">
                <section class="col col-6 " >
                    <label class="input">
                        <label class="label">{if !empty($labels.address_sorting_code) }{$labels.address_sorting_code}{else}{t}Sorting code{/t}{/if}</label>

                        <input value="{$order->get('Order Invoice Address Sorting Code')}"  type="text" name="sortingCode" class="{if 'sortingCode'|in_array:$invoice_used_address_fields}{else}ignore{/if}" placeholder="{if !empty($labels.address_sorting_code)}{$labels.address_sorting_code}{else}{t}Sorting code{/t}{/if}">
                        <b class="tooltip tooltip-bottom-right">{if !empty($labels.address_sorting_code) }{$labels.address_sorting_code}{else}{t}Sorting code{/t}{/if}</b>
                    </label>
                </section>


            </div>

            <div id="order_invoice_postalCode" class="row {if 'postalCode'|in_array:$invoice_used_address_fields}{else}hide{/if}">
                <section class="col col-6 " >
                    <label class="input">
                        <label class="label">{if !empty($labels["postalCode_`$invoice_address_labels.postalCode.code`"]) }{$labels["postalCode_`$invoice_address_labels.postalCode.code`"]}{else}{$invoice_address_labels.postalCode.label}{/if}</label>

                        <input value="{$order->get('Order Invoice Address Postal Code')}"  type="text" name="postalCode" class="{if 'postalCode'|in_array:$invoice_used_address_fields}{else}ignore{/if}" placeholder="{if !empty($labels["postalCode_`$invoice_address_labels.postalCode.code`"]) }{$labels["postalCode_`$invoice_address_labels.postalCode.code`"]}{else}{$invoice_address_labels.postalCode.label}{/if}">
                        <b class="tooltip tooltip-bottom-right">{if !empty($labels["postalCode_`$invoice_address_labels.postalCode.code`"]) }{$labels["postalCode_`$invoice_address_labels.postalCode.code`"]}{else}{$invoice_address_labels.postalCode.label}{/if}</b>
                    </label>
                </section>


            </div>

            <div id="order_invoice_dependentLocality" class="row {if 'dependentLocality'|in_array:$invoice_used_address_fields}{else}hide{/if}">
                <section class="col col-6 " >
                    <label class="input">
                        <label class="label">{if !empty($labels["dependentLocality_`$invoice_address_labels.dependentLocality.code`"]) }{$labels["dependentLocality_`$invoice_address_labels.dependentLocality.code`"]}{else}{$invoice_address_labels.dependentLocality.label}{/if}</label>

                        <input value="{$order->get('Order Invoice Address Dependent Locality')}"  type="text" name="dependentLocality" class="{if 'dependentLocality'|in_array:$invoice_used_address_fields}{else}ignore{/if}" placeholder="{if !empty($labels["dependentLocality_`$invoice_address_labels.dependentLocality.code`"]) }{$labels["dependentLocality_`$invoice_address_labels.dependentLocality.code`"]}{else}{$invoice_address_labels.dependentLocality.label}{/if}">
                        <b class="tooltip tooltip-bottom-right">{if !empty($labels["dependentLocality_`$invoice_address_labels.dependentLocality.code`"]) }{$labels["dependentLocality_`$invoice_address_labels.dependentLocality.code`"]}{else}{$invoice_address_labels.dependentLocality.label}{/if}</b>
                    </label>
                </section>

            </div>

            <div id="order_invoice_locality" class="row {if 'locality'|in_array:$invoice_used_address_fields}{else}hide{/if}">
                <section class="col col-6 " >
                    <label class="input">
                        <label class="label">{if !empty($labels["locality_`$invoice_address_labels.locality.code`"]) }{$labels["locality_`$invoice_address_labels.locality.code`"]}{else}{$invoice_address_labels.locality.label}{/if}</label>

                        <input value="{$order->get('Order Invoice Address Locality')}"  type="text" name="locality" class="{if 'locality'|in_array:$invoice_used_address_fields}{else}ignore{/if}" placeholder="{if !empty($labels["locality_`$invoice_address_labels.locality.code`"]) }{$labels["locality_`$invoice_address_labels.locality.code`"]}{else}{$invoice_address_labels.locality.label}{/if}">
                        <b class="tooltip tooltip-bottom-right">{if !empty($labels["locality_`$invoice_address_labels.locality.code`"]) }{$labels["locality_`$invoice_address_labels.locality.code`"]}{else}{$invoice_address_labels.locality.label}{/if}</b>
                    </label>
                </section>

            </div>


            <div id="order_invoice_administrativeArea" class="row {if 'administrativeArea'|in_array:$invoice_used_address_fields}{else}hide{/if}">
                <section class="col col-6 " >
                    <label class="input">
                        <label class="label">{if !empty($labels["administrativeArea_`$invoice_address_labels.administrativeArea.code`"]) }{$labels["administrativeArea_`$invoice_address_labels.administrativeArea.code`"]}{else}{$invoice_address_labels.administrativeArea.label}{/if}</label>

                        <input value="{$order->get('Order Invoice Address Administrative Area')}"  type="text" name="administrativeArea" class="{if 'administrativeArea'|in_array:$invoice_used_address_fields}{else}ignore{/if}" placeholder="{if !empty($labels["administrativeArea_`$invoice_address_labels.administrativeArea.code`"]) }{$labels["administrativeArea_`$invoice_address_labels.administrativeArea.code`"]}{else}{$invoice_address_labels.administrativeArea.label}{/if}">
                        <b class="tooltip tooltip-bottom-right">{if !empty($labels["administrativeArea_`$invoice_address_labels.administrativeArea.code`"]) }{$labels["administrativeArea_`$invoice_address_labels.administrativeArea.code`"]}{else}{$invoice_address_labels.administrativeArea.label}{/if}</b>
                    </label>
                </section>

            </div>


            <div class="row" >
                <section class="col col-5">
                    <label class="select">
                        <select id="order_invoice_country_select" name="country">
                            <option value="0" selected disabled>{if !empty($labels.address_country) }{$labels.address_country}{else}{t}Country{/t}{/if}</option>

                            {foreach from=$countries item=country}
                                <option value="{$country.2alpha}" {if $country.2alpha==$order->get('Order Invoice Address Country 2 Alpha Code')}selected{/if} >{$country.name}</option>
                            {/foreach}


                            <select><i></i>
                    </label>
                </section>


            </div>


        </fieldset>


        <footer>
            <button type="submit" class="button "  id="save_order_invoice_address_button" >{if !empty($labels._save) }{$labels._save}{else}{t}Save{/t}{/if}  <i  class="margin_left_10 fa fa-fw fa-save" aria-hidden="true"></i>  </button>
            <a href="#" class="button button-secondary modal-closer">{if !empty($labels._close) }{$labels._close}{else}{t}Close{/t}{/if}</a>
        </footer>
    </form>
</div>

<script>




    $("form").submit(function(e) {

        e.preventDefault();
        e.returnValue = false;

        // do things
    });



    $(document).on('input propertychange,change', '#voucher_code', function (evt) {


        var button = $('#voucher .button')

        button.css({ 'display': 'block'})

        if ($(this).data('old_value') != '') {
            if ($(this).val() == '') {
                button.find('span').html(button.data('remove_label'))
                button.find('i').addClass('fa-trash-alt').removeClass('fa-sync-alt fa-plus')
            } else {
                button.find('span').html(button.data('update_label'))
                button.find('i').addClass('fa-sync-alt').removeClass('fa-trash-alt fa-plus')

            }
        }


    });

    $(document).on('click', "#voucher .button", function(ev){



        var button=$(this)


        if( button.hasClass('wait')){
            return;
        }

        button.addClass('wait')
        button.find('i').addClass('fa-spin fa-spinner')

        var input=$(this).closest('div').find('input')

        var voucher=input.val()



        var ajaxData = new FormData();

        ajaxData.append("tipo", 'update_voucher')
        ajaxData.append("voucher",voucher)





        $.ajax({
            url: "/ar_web_voucher.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,
            complete: function () {
            }, success: function (data) {


                if (data.state == '200') {


                    switch (data.action) {
                        case 'deleted':

                            button.find('span').html(button.data('add_label'))
                            button.find('i').addClass('fa-plus').removeClass('fa-trash-alt fa-sync-alt')
                            input.val('').data('old_value','')
                            button.css({ 'display': 'none'})
                            location.reload();
                            break;
                        case 'add':

                            button.find('span').html(button.data('update_label'))
                            button.find('i').addClass('fa-sync-alt').removeClass('fa-trash-alt fa-plus')
                            button.css({ 'display': 'block'})
                            location.reload();
                            break;
                    }


                    for (var key in data.metadata.class_html) {


                        $('.' + key).html(data.metadata.class_html[key])
                    }






                } else if (data.state == '400') {
                    swal("{t}Error{/t}!", data.msg, "error")
                }


                button.removeClass('wait')
                button.find('i').removeClass('fa-spinner fa-spin')
            }, error: function () {
                button.removeClass('wait')
                button.find('i').removeClass('fa-spinner fa-spin')
            }
        });

    });



    $("#order_invoice_address_form").validate(
        {

            submitHandler: function(form)
            {




                var button=$('#save_order_invoice_address_button');

                if(button.hasClass('wait')){
                    return;
                }

                button.addClass('wait')
                button.find('i').removeClass('fa-arrow-right').addClass('fa-spinner fa-spin')


                var register_data={ }

                $("#order_invoice_address_form input:not(.ignore)").each(function(i, obj) {
                    if(!$(obj).attr('name')==''){
                        register_data[$(obj).attr('name')]=$(obj).val()
                    }

                });

                $("#order_invoice_address_form select:not(.ignore)").each(function(i, obj) {
                    if(!$(obj).attr('name')==''){


                        register_data[$(obj).attr('name')]=$(obj).val()
                    }

                });



                var ajaxData = new FormData();

                ajaxData.append("tipo", 'invoice_address')
                ajaxData.append("data", JSON.stringify(register_data))


                $.ajax({
                    url: "/ar_web_basket.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,
                    complete: function () {
                    }, success: function (data) {


                        $('#sky-form-modal-overlay').fadeOut();
                        $('.sky-form-modal').fadeOut();
                        $('#page-transitions').removeClass('hide')
                        if (data.state == '200') {

                            for (var key in data.metadata.class_html) {


                                $('.' + key).html(data.metadata.class_html[key])
                            }



                        } else if (data.state == '400') {
                            swal("{t}Error{/t}!", data.msg, "error")
                        }


                        button.removeClass('wait')
                        button.find('i').addClass('fa-arrow-right').removeClass('fa-spinner fa-spin')

                    }, error: function () {
                        button.removeClass('wait')
                        button.find('i').addClass('fa-arrow-right').removeClass('fa-spinner fa-spin')
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




    $("#order_delivery_address_form").validate(
        {

            submitHandler: function(form)
            {

                var button=$('#save_order_deivery_address_button');

                if(button.hasClass('wait')){
                    return;
                }

                button.addClass('wait')
                button.find('i').removeClass('fa-arrow-right').addClass('fa-spinner fa-spin')



                var register_data={ }

                $("#order_delivery_address_form input:not(.ignore)").each(function(i, obj) {
                    if(!$(obj).attr('name')==''){
                        register_data[$(obj).attr('name')]=$(obj).val()
                    }

                });

                $("#order_delivery_address_form select:not(.ignore)").each(function(i, obj) {
                    if(!$(obj).attr('name')==''){


                        register_data[$(obj).attr('name')]=$(obj).val()
                    }

                });

                register_data['order_for_collection']=$('#order_for_collection').is(':checked')



                var ajaxData = new FormData();

                ajaxData.append("tipo", 'delivery_address')
                ajaxData.append("data", JSON.stringify(register_data))


                $.ajax({
                    url: "/ar_web_basket.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,
                    complete: function () {
                    }, success: function (data) {

                        $('#sky-form-modal-overlay').fadeOut();
                        $('.sky-form-modal').fadeOut();
                        $('#page-transitions').removeClass('hide')
                        if (data.state == '200') {

                            for (var key in data.metadata.class_html) {

                                console.log('.' + key)

                                console.log($('.' + key).html())
                                // $('.' + key).html('');
                                $('.' + key).html(data.metadata.class_html[key])
                            }


                            if(data.for_collection=='Yes'){
                                $('#delivery_label').addClass('hide')
                                $('#collection_label').removeClass('hide')

                            }else{
                                $('#delivery_label').removeClass('hide')
                                $('#collection_label').addClass('hide')
                            }


                        } else if (data.state == '400') {
                            swal("{t}Error{/t}!", data.msg, "error")
                        }


                        button.removeClass('wait')
                        button.find('i').addClass('fa-arrow-right').removeClass('fa-spinner fa-spin')
                    }, error: function () {
                        button.removeClass('wait')
                        button.find('i').addClass('fa-arrow-right').removeClass('fa-spinner fa-spin')
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


    {foreach from=$items_data item="item" }
    ga('auTracker.ec:addProduct',{$item.analytics_data} );
    {/foreach}

    ga('auTracker.send', 'event', 'Order', 'basket');


    ga('auTracker.ec:setAction','checkout', {
        'step': 1,
    });
    ga('auTracker.send', 'pageview');

</script>