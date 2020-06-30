
{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 30 July 2017 at 18:14:27 CEST, Tranava, Slovakia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}



<style>


    .basket{
        font-size: 16px;
    }



    @media only screen  and (max-width: 1240px) {

        #basket_continue_shopping {
            display: none
        }
    }

</style>


{if isset($data.top_margin)}{assign "top_margin" $data.top_margin}{else}{assign "top_margin" "30"}{/if}
{if isset($data.bottom_margin)}{assign "bottom_margin" $data.bottom_margin}{else}{assign "bottom_margin" "30"}{/if}


<div id="block_{$key}" data-block_key="{$key}"  block="{$data.type}" class="{$data.type}  {if !$data.show}hide{/if}"  style="padding-top:{$top_margin}px;padding-bottom:{$bottom_margin}px"  >


    <div class="hide order_header  text_blocks  text_template_3">
        <div class="text_block ">
                    <h5>
                        <i class="fal fa-fw fa-clipboard" aria-hidden="true"></i>
                        <span id="_delivery_address_label" class="website_localized_label" >{if isset($labels._delivery_address_label) and $labels._delivery_address_label!=''}{$labels._delivery_address_label}{else}{t}Delivery Address{/t}{/if}</span>
                    </h5>
                    <p>
                        {$customer->get('Customer Delivery Address Formatted')}
                    </p>
                </div>

        <div class="text_block">
                    <h5>
                        <i class="fa fa-fw fa-dollar-sign" aria-hidden="true"></i>
                        <span id="_invoice_address_label" class="website_localized_label" >{if isset($labels._invoice_address_label) and $labels._invoice_address_label!=''}{$labels._invoice_address_label}{else}{t}Invoice Address{/t}{/if}</span>
                    </h5>
                    <p>
                        {$customer->get('Customer Invoice Address Formatted')}
                    </p>
                </div>

        <div class="totals text_block">



            <table >
                        <tbody>


                        <tr>
                            <td>{if isset($labels._items_shipping) and $labels._items_shipping!=''}{$labels._items_shipping}{else}{t}Shipping{/t}{/if}</td>

                            <td class="text-right order_shipping">{$zero_amount}</td>
                        </tr>
                        <tr>
                            <td>{if isset($labels._total_net) and $labels._total_net!=''}{$labels._total_net}{else}{t}Total Net{/t}{/if}</td>

                            <td class="text-right order_net">{$zero_amount}</td>
                        </tr>
                        <tr>
                            <td>{if isset($labels._total_tax) and $labels._total_tax!=''}{$labels._total_tax}{else}{t}Tax{/t}{/if}</td>

                            <td class="text-right order_tax">{$zero_amount}</td>
                        </tr>
                        <tr>
                            <td>{if isset($labels._total) and $labels._total!=''}{$labels._total}{else}{t}Total{/t}{/if}</td>

                            <td class="text-right order_total">{$zero_amount}</td>
                        </tr>

                        </tbody>
                    </table>

                </div>

            </div>



             

<div class="clear"></div>
    <div class="container" style="margin-top: 120px;margin-bottom: 200px;text-align: center">
        <h3>
        {if isset($content._no_products_ordered_yet) and $labels._no_products_ordered_yet!=''}{$labels._no_products_ordered_yet}{else}{t}Empty basket{/t}{/if}
            </h3>
    </div>






</div>




<div class="hide order_header container text_blocks  text_template_2">

    <div class="text_block">



    </div>

    <div class="text_block ">

        <form action="" method="post" enctype="multipart/form-data"  class="sky-form" style="box-shadow: none">

            <section class="col col-11">

                <button id="" onclick="$(this).find('i').addClass('fa-spinner fa-spin'); window.location = '/'"  style="margin:0px;margin-right:30px;" type="submit" class="button"><i  class=" fa fa-fw fa-arrow-left" aria-hidden="true"></i> {if !empty($data._go_shop_label)}{$data._go_shop_label}{else}{t}Continue shopping{/t}{/if}  </button>


            </section>


        </form>

    </div>




</div>


<div class="clear"></div>

<script>
    $("form").submit(function(e) {

        e.preventDefault();
        e.returnValue = false;

        // do things
    });





</script>