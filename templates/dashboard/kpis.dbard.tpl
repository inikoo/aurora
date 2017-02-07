{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 8 November 2016 at 23:25:56 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3
-->
*}


<div id="dashboard_kpis" style="margin-top:20px;padding:0px" class="dashboard">
    

<table border="0" style="width:100%">
    <tr class="main_title small_row">
        <td colspan="9">
            <span style="padding-left:20px">{t}KPIs{/t}</span>


        </td>
    </tr>
 </table>
</div>
<ul class="flex-container">

    <li class="flex-item">
        {assign "kpi" $warehouse->get_kpi('Month To Day')}

        <span title="{t}Warehouse productivity metric{/t}"> WPM <span class="very_discreet">(MTD)</span></span>
        <div class="title">
            <span class=" button" title="{t}Net sales per man hour{/t}">{$kpi.formatted_kpi}</span>
        </div>
        <div >
            <span class="" title="">{$kpi.formatted_hrs}</span>

        </div>

    </li>

</ul>


<script>



    function get_kpis_data(parent,  currency) {

        var request = "/ar_dashboard.php?tipo=pending_orders&parent=" + parent + '&currency=' + currency
        console.log(request)
        $.getJSON(request, function (r) {


            $('#pending_orders_parent').val(parent)

            for (var record in r.data) {

                console.log(record)
                console.log(r.data[record].value)

                $('.' + record).html(r.data[record].value)

                if(r.data[record].title!= undefined ) {
                    $('.' + record).prop('title', r.data[record].title);
                }




            }


        });

    }


    

 </script>