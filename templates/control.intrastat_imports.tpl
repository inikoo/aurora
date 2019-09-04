{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 13-08-2019 13:29:58 MYT Kuala Lumpur, Malaysia
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



    <table class="intrastat_totals" style="float: left;margin-right: 20px">
        <tr>
            <td>{t}Deliveries{/t}</td>
            <td class="aright element_total total_orders"></td>
        </tr>

        <tr>
            <td>{t}Parts{/t}</td>
            <td class="aright element_total total_parts"></td>
        </tr>



    </table>


    <table class="intrastat_totals">


        <tr>
            <td>{t}Amount{/t}</td>
            <td class="aright element_total total_amount"></td>
        </tr>

        <tr>
            <td>{t}Weight{/t}</td>
            <td class="aright element_total total_weight"></td>
        </tr>

    </table>

    <div style="clear: both"></div>

</div>

<script>



    function get_intrastat_imports_totals() {
        var request = "/ar_reports_tables.php?tipo=intrastat_imports_totals"
        $.getJSON(request, function (data) {
            if (data.state == 200) {
                for (key in data.totals) {


                    $("." + key).html(data.totals[key])


                }


            }
        })

    }

</script>