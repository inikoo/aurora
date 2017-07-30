{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 29 May 2017 at 19:40:24 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}

<div id="block_{$key}" block="{$data.type}" class="{$data.type} _block  " style="Width:100%;" >

    <div class="clearfix marb5 "></div>
            <div class="container">



                <div class="one_third">
                    <h5>
                    <i class="fa fa-fw fa-truck" aria-hidden="true"></i>
                     <span id="_delivery_address_label" class="website_localized_label" contenteditable="true">{if isset($labels._delivery_address_label) and $labels._delivery_address_label!=''}{$labels._delivery_address_label}{else}{t}Delivery Address{/t}{/if}</span>
                </h5>
                    <p>
                        The Business Centre </br>
                        61 Wellfield Road</br>
                        Roath</br>
                        Cardiff</br>
                        CF24 3DG</br>
                        United Kingdom</br>
                    </p>
                </div>


                <div class="one_third ">

                    <h5>
                        <i class="fa fa-fw fa-usd" aria-hidden="true"></i>
                    <span id="_invoice_address_label" class="website_localized_label" contenteditable="true">{if isset($labels._invoice_address_label) and $labels._invoice_address_label!=''}{$labels._invoice_address_label}{else}{t}Invoice Address{/t}{/if}</span>
                    </h5>
                        <p>
                        The Business Centre</br>
                        61 Wellfield Road</br>
                        Roath</br>
                        Cardiff</br>
                        CF24 3DG </br>
                        United Kingdom</br>
                    </p>
                </div>

                <div class="one_third text-right last" style="padding-left:20px">



                    <table class="table">

                        <tbody>
                        <tr>
                            <td  id="_items_gross"  class="website_localized_label" contenteditable="true" >{if isset($labels._items_gross) and $labels._items_gross!=''}{$labels._items_gross}{else}{t}Items Gross{/t}{/if}</td>

                            <td class="text-right">£295.52</td>
                        </tr>
                        <tr>
                            <td  id="_items_discounts"  class="website_localized_label" contenteditable="true" >{if isset($labels._items_discounts) and $labels._items_discounts!=''}{$labels._items_discounts}{else}{t}Discounts{/t}{/if}</td>


                            <td class="text-right">-£27.20</td>
                        </tr>
                        <tr>
                            <td  id="_items_net"  class="website_localized_label" contenteditable="true" >{if isset($labels._items_net) and $labels._items_net!=''}{$labels._items_net}{else}{t}Items Net{/t}{/if}</td>

                            <td class="text-right">£268.32</td>
                        </tr>
                        <tr>
                            <td  id="_items_charges"  class="website_localized_label" contenteditable="true" >{if isset($labels._items_charges) and $labels._items_charges!=''}{$labels._items_charges}{else}{t}Charges{/t}{/if}</td>


                            <td class="text-right">£0.00</td>
                        </tr>
                        <tr>
                            <td  id="_items_shipping"  class="website_localized_label" contenteditable="true" >{if isset($labels._items_shipping) and $labels._items_shipping!=''}{$labels._items_shipping}{else}{t}Shipping{/t}{/if}</td>


                            <td class="text-right">£0.00</td>
                        </tr>
                        <tr>
                            <td  id="_total_net"  class="website_localized_label" contenteditable="true" >{if isset($labels._total_net) and $labels._total_net!=''}{$labels._total_net}{else}{t}Net{/t}{/if}</td>


                            <td class="text-right">£268.32</td>
                        </tr>
                        <tr>
                            <td  id="_total_tax"  class="website_localized_label" contenteditable="true" >{if isset($labels._total_tax) and $labels._total_tax!=''}{$labels._total_tax}{else}{t}Tax{/t}{/if}</td>


                            <td class="text-right">£53.66</td>
                        </tr>
                        <tr>
                            <td  id="_total"  class="website_localized_label" contenteditable="true" >{if isset($labels._total) and $labels._total!=''}{$labels._total}{else}{t}Total{/t}{/if}</td>


                            <td class="text-right">£321.98</td>
                        </tr>
                        <tr>

                            <td  id="_credit"  class="website_localized_label" contenteditable="true" >{if isset($labels._credit) and $labels._credit!=''}{$labels._credit}{else}{t}Credit{/t}{/if}</td>


                            <td class="text-right">-£20</td>
                        </tr>
                        <tr>
                            <td  id="_total_to_pay"  class="website_localized_label" contenteditable="true" >{if isset($labels._total_to_pay) and $labels._total_to_pay!=''}{$labels._total_to_pay}{else}{t}To pay{/t}{/if}</td>


                            <td class="text-right">£301.98</td>
                        </tr>

                        </tbody>
                    </table>

                </div>

            </div>


                <div class="clearfix "></div>



                <div class="container order">

                    {include file="theme_1/_order.theme_1.tpl" hide_title=true }


                </div>

                <div class="clearfix "></div>

             <div class="container">

                <div class="one_half">

                    <form action="" method="post" enctype="multipart/form-data"  class="sky-form"
                    style="box-shadow: none"


                    <section>

                        <div class="row">
                            <section class="col col-6">
                                <label class="input">
                                    <i class="icon-append fa fa-tag"></i>
                                    <input style="color:lightgrey" type="text" name="name" id="_voucher" value="{$data._voucher}" >
                                </label>
                            </section>
                            <section class="col col-6">
                                <button style="margin:0px" type="submit" class="button" id="_voucher_label" contenteditable="true" > {$data._voucher_label}</button>

                            </section>
                        </div>



                    </section>

                    <section style="border: none">
                                <label class="textarea">

                                    <textarea style="color:lightgrey"  id="_special_instructions" rows="5" name="comment" >{$data._special_instructions}</textarea>
                                </label>
                            </section>
s

                    </form>



                </div>

                <div class="one_half last">

                    <form action="" method="post" enctype="multipart/form-data"  class="sky-form" style="box-shadow: none">

                        <section class="col col-6">
                            <button id="_go_checkout_label" contenteditable="true"  style="margin:0px" class="button">{$data._go_checkout_label}</button>

                        </section>


                    </form>


                </div>




            </div>


</div>

<script>

    $("#_special_instructions,#_voucher").on('input propertychange', function() {
        $('#save_button', window.parent.document).addClass('save button changed valid')

    });

</script>


