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

    <tr class=" small_row   ">
        <td colspan="7">
            <div class="date_chooser {if $type=='orders'}invisible{/if}">
                <div style="visibility:hidden" id="kpi_interval"
                     class="interval {if  $kpis_period=='interval'}selected{/if}">
                    <img src="/art/icons/mini-calendar_interval.png"/> {t}Interval{/t}
                </div>
                <div style="visibility:hidden" id="kpi_date" class="day {if  $kpis_period=='date'}selected{/if}">
                    <img src="/art/icons/mini-calendar.png"/> {t}Day{/t}
                </div>
                <div onclick="change_kpis_period('ytd')" period="ytd" id="kpi_ytd"
                     class="fixed_interval {if  $kpis_period=='ytd'}selected{/if}" title="{t}Year to day{/t}">
                    {t}YTD{/t}
                </div>
                <div onclick="change_kpis_period('mtd')" period="mtd" id="kpi_mtd"
                     class="fixed_interval {if  $kpis_period=='mtd'}selected{/if}" title="{t}Month to day{/t}">
                    {t}MTD{/t}
                </div>
                <div onclick="change_kpis_period('wtd')" period="wtd" id="kpi_wtd"
                     class="fixed_interval {if  $kpis_period=='wtd'}selected{/if}" title="{t}Week to day{/t}">
                    {t}WTD{/t}
                </div>
                <div onclick="change_kpis_period('today')" period="today" id="kpi_today"
                     class="fixed_interval {if  $kpis_period=='today'}selected{/if}" title="{t}Today{/t}">
                    {t}Today{/t}
                </div>
                <div onclick="change_kpis_period('yesterday')" period="yesterday" id="kpi_yesterday"
                     class="fixed_interval {if  $kpis_period=='yesterday'}selected{/if}" title="{t}Yesterday{/t}">
                    {t}Y'day{/t}
                </div>
                <div onclick="change_kpis_period('last_w')" period="last_w" id="kpi_last_w"
                     class="fixed_interval {if  $kpis_period=='last_w'}selected{/if}" title="{t}Last week{/t}">
                    {t}Last W{/t}
                </div>
                <div onclick="change_kpis_period('last_m')" period="last_m" id="kpi_last_m"
                     class="fixed_interval {if  $kpis_period=='last_m'}selected{/if}" title="{t}Last month{/t}">
                    {t}Last M{/t}
                </div>
                <div onclick="change_kpis_period('1w')" period="1w" id="kpi_1w"
                     class="fixed_interval {if  $kpis_period=='1w'}selected{/if}" title="{t}1 week{/t}">
                    {t}1W{/t}
                </div>
                <div onclick="change_kpis_period('1m')" period="1m" id="kpi_1m"
                     class="fixed_interval {if  $kpis_period=='1m'}selected{/if}" title="{t}1 month{/t}">
                    {t}1m{/t}
                </div>
                <div onclick="change_kpis_period('1q')" period="1q" id="kpi_1q"
                     class="fixed_interval {if  $kpis_period=='1q'}selected{/if}" title="{t}1 quarter{/t}">
                    {t}1q{/t}
                </div>
                <div onclick="change_kpis_period('1y')" period="1y" id="kpi_1y"
                     class="fixed_interval {if  $kpis_period=='1y'}selected{/if}" title="{t}1 year{/t}">
                    {t}1Y{/t}
                </div>
                <div onclick="change_kpis_period('all')" period="all" id="kpi_all"
                     class="fixed_interval {if  $kpis_period=='all'}selected{/if}">
                    {t}All{/t}
                </div>
            </div>
        </td>
    </tr>


 </table>
</div>
<ul id="kpis" class=" flex-container">

    <li class="flex-item  kpi    "  type="WPM" parent="warehouse" parent_key="{$warehouse->id}"  >
        {assign "kpi" $warehouse->get_kpi($kpis_period)}

        <span title="{t}Warehouse productivity metric{/t}"> WPM </span>
        <div class="title">
            <span class="kpi_value" title="{t}Net sales per man hour{/t}">{$kpi.formatted_kpi}</span>
        </div>
        <div >
            <span class="aux_kpi_data" title="">{$kpi.formatted_aux_kpi_data}</span>

        </div>

    </li>

    <li class="flex-item  kpi    "  type="PPM" parent="supplier_production" parent_key="{$supplier_production->id}"  >
        {assign "ppm_kpi" $supplier_production->get_kpi($kpis_period)}

        <span title="{t}Production productivity metric{/t}"> PPM </span>
        <div class="title">
            <span class="kpi_value  button" title="{t}Production sales per man hour{/t}">{$ppm_kpi.formatted_kpi}</span>
        </div>
        <div >
            <span class="aux_kpi_data" title="">{$ppm_kpi.formatted_aux_kpi_data}</span>

        </div>

    </li>

</ul>


<script>



    function change_kpis_period(period) {


        $('#dashboard_kpis .date_chooser .fixed_interval').removeClass('selected')
        $('#kpi_' + period).addClass('selected')
        
        $('#kpis li.kpi').each(function(i, obj) {

            var kpi_element=$(obj);

            var request = "/ar_dashboard.php?tipo=kpi&type=" + $(obj).attr('type') + '&parent=' + $(obj).attr('parent') + '&parent_key=' + $(obj).attr('parent_key')+'&period='+period
            console.log(request)

            $.getJSON(request, function (r) {



        console.log(r.kpi.formatted_kpi)
                $(obj).find('.kpi_value').html( r.kpi.formatted_kpi)

                $(obj).find('.aux_kpi_data').html( r.kpi.formatted_aux_kpi_data)

            });

        });



    }


    

 </script>