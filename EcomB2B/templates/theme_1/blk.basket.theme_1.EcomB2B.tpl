{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 21 July 2017 at 14:16:53 CEST, Tranava, Slovakia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}




<div id="block_{$key}" block="{$data.type}" class="{$data.type} _block  " style="Width:100%;" >




            <div class="container">



                <div class="one_third">
                    <h5 id="_invoice_address_label" contenteditable="true">{$data._invoice_address_label}</h5>
                    <p>
                        The Business Centre </br>
                        61 Wellfield Road</br>
                        Roath</br>
                        Cardiff</br>
                        CF24 3DG</br>
                        United Kingdom</br>
                    </p>
                </div><!-- end section -->


                <div class="one_third ">
                    <h5 id="_delivery_address_label" contenteditable="true">{$data._delivery_address_label}</h5>
                    <p>
                        The Business Centre</br>
                        61 Wellfield Road</br>
                        Roath</br>
                        Cardiff</br>
                        CF24 3DG </br>
                        United Kingdom</br>
                    </p>
                </div><!-- end section -->

                <div class="one_third text-right last" style="padding-left:20px">



                    <table class="table">

                        <tbody>
                        <tr>
                            <td>{$data._items_gross}</td>

                            <td class="text-right order_items_gross ">{$order->get('Items Gross Amount')}</td>
                        </tr>
                        <tr class="order_items_discount_container {if $order->get('Order Items Discount Amount')==0 }hide{/if}">
                            <td>{$data._discounts}</td>

                            <td class="text-right order_items_discount">{$order->get('Items Discount Amount')}</td>
                        </tr>
                        <tr>
                            <td>{$data._items_net}</td>

                            <td class="text-right">{$order->get('Items Net Amount')}</td>
                        </tr>
                        <tr class="order_charges_container {if $order->get('Order Charges Net Amount')==0 }hide{/if}">
                            <td>{$data._charges}</td>

                            <td class="text-right order_charges">{$order->get('Charges Net Amount')}</td>
                        </tr>
                        <tr>
                            <td>{$data._shipping}</td>

                            <td class="text-right">{$order->get('Shipping Net Amount')}</td>
                        </tr>
                        <tr>
                            <td>{$data._net}</td>

                            <td class="text-right">{$order->get('Total Net Amount')}</td>
                        </tr>
                        <tr>
                            <td>{$data._tax}</td>

                            <td class="text-right">{$order->get('Total Tax Amount')}</td>
                        </tr>
                        <tr>
                            <td>{$data._total}</td>

                            <td class="text-right">{$order->get('Total')}</td>
                        </tr>

                        </tbody>
                    </table>

                </div><!-- end section -->


                <div class="clearfix "></div>



                <div class="container order">

                    {include file="theme_1/_order_items.theme_1.tpl" hide_title=true }


                </div>

                <div class="clearfix marb6"></div>

                <div class="one_half">

                    <form action="" method="post" enctype="multipart/form-data"  class="sky-form"
                    style="box-shadow: none"


                    <section>

                        <div class="row">
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

                                    <textarea rows="5" name="comment" placeholder="{$data._special_instructions}"></textarea>
                                </label>
                            </section>


                    </form>



                </div>

                <div class="one_half last">


                    <form action="" method="post" enctype="multipart/form-data"  class="sky-form"
                          style="box-shadow: none"




                    </form>

                </div>




            </div>


        <div class="clearfix marb12"></div>

</div>