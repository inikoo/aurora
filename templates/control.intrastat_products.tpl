{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 7 June 2018 at 01:26:06 GMT+8 Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3
-->
*}

<style>

    .intrastat_totals {
        width: 400px;
        border-top: 1px solid #ccc;
        float: right;
    }

    .intrastat_totals tr {
        border-bottom: 1px solid #ccc;
    }

    .info{
        margin-left:40px;
        width: 300px;
        border-top: 1px solid #ccc;
        float: left;

    }
    .info tr {
        border-bottom: 1px solid #ccc;
    }

</style>

<div style="padding:20px;border-bottom:1px solid #ccc" class="control_panel">


    <table style="float: left">
        <tr>
            <td><span class=" {if $table_state['invoices_no_vat']==0}very_discreet{/if}" ><i id="icon_invoices_no_vat"
                                                                                                                class="fa {if $table_state['invoices_no_vat']==1}fa-check success{else}fa-check super_discreet {/if} fa-fw"></i>
                    {t}Invoices with no VAT{/t}
                    <small class="discreet">({t}Customers with valid tax number{/t})</small></span>
            </td>
        </tr>
        <tr>
            <td><span class=" {if $table_state['invoices_vat']==0}very_discreet{/if}" ><i id="icon_invoices_vat"
                                                                                                             class="fa {if $table_state['invoices_vat']==1}fa-check success{else}fa-check super_discreet{/if} fa-fw"></i> {t}Invoices with VAT{/t}

                    </span></td>
        </tr>

        <tr>
            <td><span class=" {if $table_state['invoices_null']==0}very_discreet{/if}" ><i id="icon_invoices_null"
                                                                                                              class="fa {if $table_state['invoices_null']==1}fa-check success {else}fa-check super_discreet{/if} fa-fw"></i> {t}Replacements/Samples{/t}</span>
            </td>
        </tr>

    </table>
    <table class="info" >
        <tr>
            <td>{t}Country{/t}</td>
            <td class="aright">{$country->get('Flag')} {$country->get('Country Name')}</td>
        </tr>
        <tr>
            <td>{t}Commodity code{/t}</td>
            <td class="aright">{$commodity_code}</td>
        </tr>
    </table>

    <table class="intrastat_totals">

        <tr>
            <td>{t}Orders{/t}</td>
            <td class="aright " ><span class=" intrastat_products_total_orders link" onclick="change_view('{$link_orders}')"></span></td>
        </tr>

        <tr>
            <td>{t}Amount{/t}</td>
            <td class="aright intrastat_products_total_amount"></td>
        </tr>

        <tr>
            <td>{t}Weight{/t}</td>
            <td class="aright intrastat_products_total_weight"></td>
        </tr>

    </table>

    <div style="clear: both"></div>

</div>

<script>






    function get_intrastat_products_totals() {
        var request = "/ar_reports_tables.php?tipo=intrastat_products_totals"
        $.getJSON(request, function (data) {
            if (data.state == 200) {
                for (key in data.totals) {


                    $("." + key).html(data.totals[key])


                }


            }
        })

    }

</script>