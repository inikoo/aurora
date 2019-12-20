{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  19 December 2019  14:44::59  +0800, Kuala :umpur Malaysia
 Copyright (c) 2019, Inikoo

 Version 3
-->
*}


<div id="dashboard_dispatching_times" style="margin-top:20px;padding:0px" class="dashboard">

    <input id="dispatching_times_parent" type="hidden" value="{$parent}">

    <table style="width:100%">
        <tr class="main_title small_row">
            <td colspan="9">
                <div class="widget_types">
                    <div id="store_" onclick="change_dispatching_times_parent('')"
                         class="widget  left  {if $parent==''}selected{/if}">
                        <span class="label"> {t}All stores{/t} </span>
                    </div>

                    {foreach from=$stores item=store}
                        <div id="store_{$store.key}" onclick="change_dispatching_times_parent({$store.key})"
                             class="widget  left {if $parent==$store.key}selected{/if}">
                            <span class="label">{$store.code} </span>
                        </div>
                    {/foreach}


                </div>


            </td>
        </tr>
    </table>
</div>


<ul class="flex-container">

    <li class="flex-item">

        <span>{t}Sitting time{/t} <i style="position: relative;bottom: 1.5px" class="fal small fa-warehouse-alt"></i></span>

        <div class="title button" onclick="go_to_pending_delivery_notes()"><span class="formatted_sitting_time_avg " title="{t}Average sitting time{/t}: {$object->get('formatted_bis_sitting_time_avg')}">{$object->get('formatted_sitting_time_avg')}</span></div>
        <div class="button" onclick="go_to_pending_delivery_notes()"><span class="sitting_time_samples" title="{t}Orders sitting in the warehouse{/t}">{$object->get('sitting_time_samples')}</span> <i
                    class="fal fa-truck"></i></div>

    </li>

    <li class="flex-item">
        <span>{t}Dispatch time{/t}</span>

        <div class="title"><span class="formatted_dispatch_time_avg "
                                 title="{t}Average dispatch time (last 30 days){/t}: {$object->get('formatted_bis_dispatch_time_avg','1 Month')}">{$object->get('formatted_dispatch_time_avg','1 Month')}</span></div>
        <div><span class="dispatch_time_samples" title="{t}Order dispatched (last 30 days){/t}">{$object->get('dispatch_time_samples','1 Month')}</span> <i class="fal fa-truck"></i></div>

    </li>

    <li class="flex-item" style="width: 400px;display: flex">


        <div style="flex-grow:1">

            <span>24 {t}hrs{/t}</span>
            <div class="title "><span class="percentage_dispatch_time_day_0 ">{$object->get('percentage_dispatch_time_histogram',[0,'1 Month'])}</span></div>
            <div><span class="dispatch_time_day_0">{$object->get('dispatch_time_histogram',[0,'1 Month'])}</span></div>


        </div>

        <div style="flex-grow:1">

            <span>48 {t}hrs{/t}</span>
            <div class="title"><span class="percentage_dispatch_time_day_1 ">{$object->get('percentage_dispatch_time_histogram',[1,'1 Month'])}</span></div>
            <div><span class="dispatch_time_day_1">{$object->get('dispatch_time_histogram',[1,'1 Month'])}</span></div>

        </div>
        <div style="flex-grow:1">

            <span>72 {t}hrs{/t}</span>
            <div class="title"><span class="percentage_dispatch_time_day_2 ">{$object->get('percentage_dispatch_time_histogram',[2,'1 Month'])}</span></div>
            <div><span class="dispatch_time_day_2">{$object->get('dispatch_time_histogram',[2,'1 Month'])}</span></div>

        </div>
        <div style="flex-grow:1">

            <span>96 {t}hrs{/t}</span>
            <div class="title"><span class="percentage_dispatch_time_day_3 ">{$object->get('percentage_dispatch_time_histogram',[3,'1 Month'])}</span></div>
            <div><span class="dispatch_time_day_3">{$object->get('dispatch_time_histogram',[3,'1 Month'])}</span></div>

        </div>

    </li>
    <li style="visibility: hidden;width: 320px" class="flex-item">

    </li>


</ul>


