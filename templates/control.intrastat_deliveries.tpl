{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 6 June 2018 at 23:59:35 GMT+8 Kuala Lumpur, Malaysia
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
            <td>{t}Parts{/t}</td>
            <td class="aright " ><span class=" intrastat_orders_total_parts link" onclick="change_view('{$link_parts}')"></span></td>

        </tr>

        <tr>
            <td>{t}Amount{/t}</td>
            <td class="aright intrastat_orders_total_amount"></td>
        </tr>

        <tr>
            <td>{t}Weight{/t}</td>
            <td class="aright intrastat_orders_total_weight"></td>
        </tr>

    </table>

    <div style="clear: both"></div>

</div>

<script>






    function get_intrastat_orders_totals() {
        var request = "/ar_reports_tables.php?tipo=intrastat_imports_deliveries_totals"
        $.getJSON(request, function (data) {
            if (data.state == 200) {
                for (key in data.totals) {


                    $("." + key).html(data.totals[key])


                }


            }
        })

    }

</script>