{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 10:45 pm Tuesday, 30 June 2020 (MYT),  Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3
-->
*}


<div id="dashboard_production" style="margin-top:5px;padding:0px" class="dashboard">
    <input id="production_parent" type="hidden" value="{$parent}">
</div>


<ul class="flex-container">



    <li class="flex-item ">
        <span>{t}Parts{/t}</span>
        <div class="title"><span class="Active_Parts button"  onclick="change_view('inventory',{ 'tab':'inventory.parts'})" title="{t}Number active parts{/t}" >{$object->get('Production Active Parts Number')}</span></div>
        <div class="button"  onclick="change_view('inventory',{ 'tab':'inventory.in_process_parts'})">
            <span> <i class="fa fa-fw fa-seedling" title="{t}Parts in process{/t}"  ></i> <span class="In_Process_Parts " title="{t}Parts in process{/t}">{$object->get('Production In Process Parts Number')}</span></span>

            | <span class="button"  onclick="change_view('inventory',{ 'tab':'inventory.discontinuing_parts'})"><i class="far fa-fw fa-skull" title="{t}Discontinuing parts{/t}" ></i> <span class="Discontinuing_Parts " title="{t}Discontinuing parts{/t}">{$object->get('Production Discontinuing Parts Number')}</span>

        </div>
    </li>

    <li class="flex-item ">
        <span>{t}Job orders{/t}</span>
        <div class="title">
            <span class=" button" title="{t}In queue{/t}"  onclick=" change_view('production/{$production_supplier_key}/orders' , { 'tab':'production_supplier.orders',  'parameters':{ elements_type:'state' } ,element:{ state:{ Planning:'',Queued:1,Manufacturing:'',Manufactured:'',QC_Pass:'',Delivered:'',Placed:'',Cancelled:''}} } )"><i style="font-size: 50%" class="fal fa-user-clock " ></i> <span class="Queued_Job_Orders">{$production_job_orders_elements['Queued']}</span></span> |
            <span class=" button" title="{t}Manufacturing{/t}"  onclick=" change_view('production/{$production_supplier_key}/orders' ,  'parameters':{ elements_type:'state' } ,element:{ state:{ Planning:'',Queued:'',Manufacturing:1,Manufactured:'',QC_Pass:'',Delivered:'',Placed:'',Cancelled:''}} } )"><i style="font-size: 50%" class="far fa-fill-drip" ></i> <span class="Manufacturing_Job_Orders">{$production_job_orders_elements['Manufacturing']}</span></span> |
            <span class=" button" title="{t}Manufactured, placing in warehouse{/t}"   onclick=" change_view('production/{$production_supplier_key}/orders' ,{ 'tab':'production_supplier.orders',  'parameters':{ elements_type:'state' } ,element:{ state:{ Planning:'',Queued:'',Manufacturing:'',Manufactured:1,QC_Pass:1,Delivered:1,Placed:'',Cancelled:''}} } )"><span class="Done_Placing__Job_Orders">{$production_job_orders_elements['Done Placing']}</span> <i style="font-size: 50%" class="fa purple fa-hand-holding-heart" ></i></span>
        </div>

    </li>

    <li class="flex-item ">
        <span>{t}Forgotten parts{/t}</span>
        <div class="title">
            <span class=" button" title="{t}Parts with no products associated{/t}" ><i style="font-size: 50%" class=" far fa-ghost " ></i> <span class="Parts_No_Products">{$object->get('Production Parts No Products')}</span></span> |
            <span class=" button" title="{t}Parts forced offline/out of stock on website{/t}"  ><span class="Parts_Forced_not_for_Sale">{$object->get('Production Parts Forced not for Sale')}</span> <i style="font-size: 50%" class="fal fa-globe red" ></i></span>
        </div>

    </li>
    <li class="flex-item invisible">


    </li>

    <li class="flex-item invisible">


    </li>



</ul>
