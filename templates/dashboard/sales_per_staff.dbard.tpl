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

    <input id="sales_per_staff_parent" type="hidden" >

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
    </table>
</div>


<ul class="flex-container">

    <li class="flex-item">

        <span>Sales</span>

        <div class="title " ><span class="formatted_sitting_time_avg " title="{$sales_per_staff_title}">
                {$sales_per_staff}</span></div>
        <div style="visibility: hidden"  ><span class="sitting_time_samples" title="{t}Orders sitting in the warehouse{/t}">{$account->get('sitting_time_samples')}</span> <i
                    class="fal fa-truck"></i></div>

    </li>

    <li class="flex-item">
        <span style="color:purple">Produced</span>

        <div class="title"><span class="formatted_dispatch_time_avg " style="color:purple"
                                 title="{$produced_per_staff_title}">
                {$produced_per_staff}</span></div>


        <div style="visibility: hidden"><span class="dispatch_time_samples" title="{t}Order dispatched (last 30 days){/t}">{$account->get('dispatch_time_samples','1 Month')}</span> <i class="fal fa-truck"></i></div>

    </li>

    <li class="flex-item" style="width: 400px;display: flex">


        <div style="flex-grow:1">

            <span>Artisans</span>
            <div class="title "><span >{$teams_data['Artisan']['staff_percentage']}</span></div>
            <div><span class="">{$teams_data['Artisan']['staff']}</div>


        </div>

        <div style="flex-grow:1">

            <span>Support</span>
            <div class="title "><span >{$teams_data['Support']['staff_percentage']}</span></div>
            <div><span class="">{$teams_data['Support']['staff']}</div>



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


