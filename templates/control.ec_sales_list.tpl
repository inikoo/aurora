{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 7 June 2018 at 11:27:11 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3
-->
*}

<style>

    .intrastat_totals {
        min-width: 400px;
        border-top: 1px solid #ccc;
        float: right;
    }

    .intrastat_totals tr {
        border-bottom: 1px solid #ccc;
    }

</style>

<div style="padding:20px;border-bottom:1px solid #ccc" class="control_panel">




    <table class="intrastat_totals">


        <tr>
            <td>{t}Net{/t}</td>
            <td class="aright total_amount_net"></td>
        </tr>

        <tr>
            <td>{t}Tax{/t}</td>
            <td class="aright total_amount_tax"></td>
        </tr>

        <tr>
            <td>{t}Total{/t}</td>
            <td class="aright total_amount_total"></td>
        </tr>

    </table>

    <table class="intrastat_totals" style="margin-right: 40px">
        <tr>
            <td>{t}Customers{/t}</td>
            <td class="aright total_customers"></td>
        </tr>
        <tr>
            <td>{t}Invoices{/t}</td>
            <td class="aright total_invoices"></td>
        </tr>

        <tr>
            <td>{t}Refunds{/t}</td>
            <td class="aright total_refunds"></td>
        </tr>


    </table>


    <div style="clear: both"></div>

</div>

<script>





    function get_ec_sales_list_totals() {
        var request = "/ar_reports_tables.php?tipo=ec_sales_list_totals"
        $.getJSON(request, function (data) {
            if (data.state == 200) {
                for (key in data.totals) {


                    $("." + key).html(data.totals[key])


                }


            }
        })

    }

</script>