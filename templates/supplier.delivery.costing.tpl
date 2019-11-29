{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 30 April 2018 at 15:17:42 BST, Sheffield, UK
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}

<style>
    .booking_in_barcode_feedback_block {
        margin-left: 70px
    }
</style>

<div  class="control_supplier_delivery_costing_container"  style="border-bottom:1px solid #ccc;padding:20px;position: relative;min-height: 60px">

    <div style="float: left;width: 800px">
        <table class="control_supplier_delivery_costing" data-currency_symbol="{$currency_symbol}" data-delivery_key="{$delivery->id}">
            <tr class="{if $delivery->get('Supplier Delivery Currency Code')==$account->get('Currency Code')}hide{/if}">
                <td>
                    {t}Exchange{/t} 1{$account->get('Currency Code')}=
                </td>
                <td style="padding-left: 10px">
                    <input class="edit_exchange" value="{math equation="1/x" x=$delivery->get('Supplier Delivery Currency Exchange') format="%.5f"}">{$delivery->get('Supplier Delivery Currency Code')}
                </td>
            </tr>

            <tr style="height: 10px">
                <td colspan="2"></td>

            </tr>
            <tr class="{if $delivery->get('Supplier Delivery Currency Code')==$account->get('Currency Code')}hide{/if}">
                <td>
                    {t}Set extra costs{/t} ({$delivery->get('Supplier Delivery Currency Code')})
                </td>
                <td style="padding-left: 10px">
                    <input class="edit_extra_cost_for_distribution" id="edit_extra_delivery_currency" value="">
                    <span onclick="toggle_exclude_zero_placed_items(this)" class="{if $number_zero_placed_items==0}hide{/if} small button ">
                        <i class="far fa-check-square exclude_zeros"></i> {t}exclude 0 SKOs in{/t}
                    </span>

                </td>
                <td>
                       <span class=" margin_left_10"> {t}Distribute{/t}:
                        <i data-type="extra_amount_input" data-distribution_type="equal" title="{t}Distribute equally each item{/t}" class="distribute_extra_costs fas fa-equals save margin_left_10"></i>
                        <i data-type="extra_amount_input" data-distribution_type="cost" title="{t}Distribute depending on value{/t}" class="distribute_extra_costs fas fa-dollar-sign save margin_left_10"></i>
                    </span>
                </td>
            </tr>
            <tr>
                <td>
                    {t}Set extra costs{/t} ({$account->get('Currency Code')})
                </td>
                <td style="padding-left: 10px">
                    <input class="edit_extra_cost_for_distribution" id="edit_extra_account_currency" value="">
                    <span onclick="toggle_exclude_zero_placed_items(this)" class="{if $number_zero_placed_items==0}hide{/if} small button ">
                        <i class="far fa-check-square exclude_zeros"></i> {t}exclude 0 SKOs in{/t}
                    </span>
                </td>
                <td>
                    <span class=" margin_left_10"> {t}Distribute{/t}:
                        <i data-type="extra_amount_account_currency" data-distribution_type="equal" title="{t}Distribute equally each item{/t}" class="distribute_extra_costs fas fa-equals save margin_left_10"></i>
                        <i data-type="extra_amount_account_currency" data-distribution_type="cost" title="{t}Distribute depending on value{/t}" class="distribute_extra_costs fas fa-dollar-sign save margin_left_10"></i>
                    </span>

                </td>
            </tr>

        </table>

    </div>

    <span  class="save_button save valid changed" style="float:right" > {t}Finish costing{/t} <i class="fa fa-cloud  " aria-hidden="true"></i></span>


    <div style="clear: both">


    </div>

</div>

<div style="clear: both">


</div>
