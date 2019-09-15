{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:18 October 2018 at 13:35:10 GMT+8,  Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3
-->
*}


<div id="dashboard_inventory" style="margin-top:5px;padding:0px" class="dashboard">

    <input id="inventory_parent" type="hidden" value="{$parent}">



</div>


<ul class="flex-container">

   

    <li class="flex-item ">
        <span>{t}Parts{/t}</span>
        <div class="title"><span class="Active_Parts button"  onclick="change_view('inventory',{ 'tab':'inventory.parts'})" title="{t}Number active parts{/t}" >{$object->get('Active Parts Number')}</span></div>
        <div class="button"  onclick="change_view('inventory',{ 'tab':'inventory.in_process_parts'})">
            <span> <i class="fa fa-fw fa-seedling" title="{t}Parts in process{/t}"  ></i> <span class="In_Process_Parts " title="{t}Parts in process{/t}">{$object->get('In Process Parts Number')}</span></span>

            | <span class="button"  onclick="change_view('inventory',{ 'tab':'inventory.discontinuing_parts'})"><i class="far fa-fw fa-skull" title="{t}Discontinuing parts{/t}" ></i> <span class="Discontinuing_Parts " title="{t}Discontinuing parts{/t}">{$object->get('Discontinuing Parts Number')}</span>

        </div>
    </li>

    <li class="flex-item invisible">


    </li>

    <li class="flex-item invisible">


    </li>

    <li class="flex-item invisible">


    </li>

    <li class="flex-item invisible">


    </li>


</ul>


<script>




    function get_dashboard_inventory_data(parent,  currency) {

        var request = "/ar_dashboard.php?tipo=inventory&parent=" + parent 
        console.log(request)
        $.getJSON(request, function (r) {


            $('#inventory_parent').val(parent)

            for (var record in r.data) {

                $('.' + record).html(r.data[record].value)

                if(r.data[record].title!= undefined ) {
                    $('.' + record).prop('title', r.data[record].title);
                }




            }


        });

    }



 </script>