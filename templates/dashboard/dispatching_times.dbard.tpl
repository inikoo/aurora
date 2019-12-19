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


<h2 class="hide dashboard" style="margin-top: 10px">Orders in process</h2>

<ul class="flex-container">

    <li class="flex-item">

        <span>{t}Sitting time{/t} <i style="position: relative;bottom: 1.5px" class="fal small fa-warehouse-alt"></i></span>

        <div class="title"><span class="formatted_sitting_time_avg button"   title="{t}Average sitting time{/t}: {$object->get('formatted_bis_sitting_time_avg')}" >{$object->get('formatted_sitting_time_avg')}</span></div>
        <div ><span class="sitting_time_samples" title="{t}Orders sitting in the warehouse{/t}">{$object->get('sitting_time_samples')}</span> <i class="fa fa-truck"></i></div>

    </li>

    <li class="flex-item">
        <span>{t}Dispatch time{/t}</span>

        <div class="title"><span class="formatted_dispatch_time_avg button"   title="{t}Average dispatch time (last 30 days){/t}: {$object->get('formatted_bis_dispatch_time_avg','1 Month')}" >{$object->get('formatted_dispatch_time_avg','1 Month')}</span></div>
        <div ><span class="dispatch_time_samples" title="{t}Order dispatched (last 30 days){/t}">{$object->get('dispatch_time_samples','1 Month')}</span> <i class="fa fa-truck"></i></div>

    </li>

    <li style="visibility: hidden" class="flex-item">

    </li>
    <li style="visibility: hidden" class="flex-item">

    </li>
    <li style="visibility: hidden" class="flex-item">

    </li>

</ul>


<script>




    function change_dispatching_times_parent(parent) {


        $('.widget_types .widget').removeClass('selected')
        $('#store_' + parent).addClass('selected')


        get_dashboard_dispatching_times_data(parent)


    }


    function get_dashboard_dispatching_times_data(parent) {

        var request = "/ar_dashboard.php?tipo=dispatching_times&parent=" + parent
        $.getJSON(request, function (r) {
            $('#dispatching_times_parent').val(parent)

            for (var record in r.data) {
                $('.' + record).html(r.data[record].value)
                if(r.data[record].title!= undefined ) {
                    $('.' + record).prop('title', r.data[record].title);
                }
            }
        });

    }


    

 </script>