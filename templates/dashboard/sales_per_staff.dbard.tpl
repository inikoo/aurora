{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:   20 Oact 10:47, Sheffield UK
 Copyright (c) 2019, Inikoo

 Version 3
-->
*}


<div id="dashboard_sales_per_staff" style="margin-top:20px;padding:0px" class="dashboard">
    <input type="hidden" id="sales_per_staff_period" value="{$period}">


    <table style="width:100%">
        <tr class="main_title small_row">
            <td colspan="9">
                <div class="widget_types">
                    <div
                         class="widget  left  selected">
                        <span class="label"> {$report_title}</span>
                    </div>




                </div>


            </td>
        </tr>

        <tr class=" small_row   ">


            <td colspan="7">

                <div class="date_chooser  date_chooser_change_sales_overview_period ">

                   
                    <div onclick="change_sales_per_staff_period('ytd')" period="ytd" id="sales_per_staff_period_ytd"
                         class=" fixed_interval {if  $period=='ytd'}selected{/if}" title="{t}Year to day{/t}">
                        {t}YTD{/t}
                    </div>
                    <div onclick="change_sales_per_staff_period('mtd')" period="mtd" id="sales_per_staff_period_mtd"
                         class="hide fixed_interval {if  $period=='mtd'}selected{/if}" title="{t}Month to day{/t}">
                        {t}MTD{/t}
                    </div>
                    <div onclick="change_sales_per_staff_period('wtd')" period="wtd" id="sales_per_staff_period_wtd"
                         class="hide fixed_interval {if  $period=='wtd'}selected{/if}" title="{t}Week to day{/t}">
                        {t}WTD{/t}
                    </div>
                    <div onclick="change_sales_per_staff_period('today')" period="today" id="sales_per_staff_period_today"
                         class="hide fixed_interval {if  $period=='today'}selected{/if}" title="{t}Today{/t}">
                        {t}Today{/t}
                    </div>
                    <div onclick="change_sales_per_staff_period('yesterday')" period="yesterday" id="sales_per_staff_period_yesterday"
                         class="hide fixed_interval {if  $period=='yesterday'}selected{/if}" title="{t}Yesterday{/t}">
                        {t}Y'day{/t}
                    </div>
                    <div onclick="change_sales_per_staff_period('last_w')" period="last_w" id="sales_per_staff_period_last_w"
                         class="fixed_interval {if  $period=='last_w'}selected{/if}" title="{t}Last week{/t}">
                        {t}Last W{/t}
                    </div>
                    <div onclick="change_sales_per_staff_period('last_m')" period="last_m" id="sales_per_staff_period_last_m"
                         class="fixed_interval {if  $period=='last_m'}selected{/if}" title="{t}Last month{/t}">
                        {t}Last M{/t}
                    </div>
                    <div onclick="change_sales_per_staff_period('1w')" period="1w" id="sales_per_staff_period_1w"
                         class="fixed_interval {if  $period=='1w'}selected{/if}" title="{t}1 week{/t}">
                        {t}1W{/t}
                    </div>
                    <div onclick="change_sales_per_staff_period('1m')" period="1m" id="sales_per_staff_period_1m"
                         class="fixed_interval {if  $period=='1m'}selected{/if}" title="{t}1 month{/t}">
                        {t}1m{/t}
                    </div>
                    <div onclick="change_sales_per_staff_period('1q')" period="1q" id="sales_per_staff_period_1q"
                         class="fixed_interval {if  $period=='1q'}selected{/if}" title="{t}1 quarter{/t}">
                        {t}1q{/t}
                    </div>
                    <div onclick="change_sales_per_staff_period('1y')" period="1y" id="sales_per_staff_period_1y"
                         class="fixed_interval {if  $period=='1y'}selected{/if}" title="{t}1 year{/t}">
                        {t}1Y{/t}
                    </div>
                    
                </div>
            </td>
        </tr>

    </table>
</div>


<ul class="flex-container">

    <li class="flex-item">

        <span>Sales/all</span>

        <div class="title " ><span id="sales_per_staff"  title="{$sales_per_staff_title}">
                {$sales_per_staff}</span></div>
        <div   ><span id="number_staff" >{$number_staff}</span> </div>

    </li>

    <li class="flex-item">
        <span style="color:purple">Sales/Warehouse</span>

        <div class="title"><span id="sales_per_warehouse" style="color:purple"
                                 title="{$sales_per_warehouse_title}">
                {$sales_per_warehouse}</span></div>


        <div ><span id="" title="number_warehouse_staff">{$number_warehouse_staff}</span> </div>

    </li>

    <li class="flex-item  {if !$show_production}hide{/if}  " >
        <span style="color:purple">Sales/Artisan & S. </span>

        <div class="title"><span id="produced_per_staff"  style="color:purple"
                                 title="{$produced_per_staff_title}">
                {$produced_per_staff}</span></div>


        <div ><span id="number_production_staff">{$number_production_staff}</span> </div>

    </li>

    <li class="flex-item" style="width: 400px;display: flex">


        <div style="flex-grow:1" class="{if !$show_production}hide{/if}"  >

            <span>Artisans</span>
            <div class="title "><span >{$teams_data['Artisan']['staff_percentage']}</span></div>
            <div><span class="">{$teams_data['Artisan']['staff']}</div>


        </div>

        <div style="flex-grow:1" class="{if !$show_production}hide{/if}" >

            <span>Support</span>
            <div class="title "><span >{$teams_data['Support']['staff_percentage']}</span></div>
            <div><span class="">{$teams_data['Support']['staff']}</div>



        </div>

        <div style="flex-grow:1">

            <span>Warehouse</span>
            <div class="title "><span >{$teams_data['Warehouse']['staff_percentage']}</span></div>
            <div><span class="">{$teams_data['Warehouse']['staff']}</div>

        </div>


        <div style="flex-grow:1">

            <span>Admin</span>
            <div class="title "><span >{$teams_data['Admin']['staff_percentage']}</span></div>
            <div><span class="">{$teams_data['Admin']['staff']}</div>

        </div>
        <div style="flex-grow:1">

            <span>Sales</span>
            <div class="title "><span >{$teams_data['Sales']['staff_percentage']}</span></div>
            <div><span class="">{$teams_data['Sales']['staff']}</div>

        </div>



    </li>
    <li style="visibility: hidden;width: 320px" class="flex-item">

    </li>


</ul>


