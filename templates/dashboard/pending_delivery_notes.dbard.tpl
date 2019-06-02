{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 17 December 2018 at 12:41:43 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3
-->
*}

<ul class="flex-container" >

    <li id="delivery_note_flow_ready_to_pick"  data-current_delivery_note_flow="{$delivery_note_flow}" class="flex-item delivery_note_flow  {if $delivery_note_flow=='ready_to_pick'}selected{/if}  " >

        <span>{t}Staging area{/t}</span>
        <div class="title">
            <span class="" >
                <span class="Delivery_Notes_Ready_to_Pick_Number button discreet" title="{t}New delivery notes{/t}" onclick="get_delivery_notes_table('ready_to_pick',{ parent: 'warehouse','parent_key':{$warehouse->id}})">
                    <i style="font-size: 50%" class="fa fa-seedling fa-fw " aria-hidden="true"></i> {$warehouse->get('formatted_ready_to_pick_number')}
                </span> | </span>
            <span class="Delivery_Notes_Assigned_Number button " title="{t}Delivery note assigned{/t}"
                  onclick="get_delivery_notes_table('assigned',{ parent: 'warehouse','parent_key':{$warehouse->id}})"> {$warehouse->get('formatted_assigned_number')} <i style="font-size: 50%" class="fa fa-chalkboard-teacher fa-fw" aria-hidden="true"></i> </span>  </div>
        <div >
            <span class=""><span onclick="get_delivery_notes_table('ready_to_pick',{ parent: 'warehouse','parent_key':{$warehouse->id}})" class="button discreet Delivery_Notes_Ready_to_Pick_Weight" title=""> {$warehouse->get('formatted_ready_to_pick_weight')} </span> | </span>
            <span  onclick="get_delivery_notes_table('assigned',{ parent: 'warehouse','parent_key':{$warehouse->id}})" class="Delivery_Notes_Assigned_Weight  button " title=""> {$warehouse->get('formatted_assigned_weight')} </span>
        </div>


    </li>
    <li id="delivery_note_flow_submitted" class="flex-item delivery_note_flow {if $delivery_note_flow=='submitted_not_paid' or  $delivery_note_flow=='submitted' }selected{/if}" >
        <span>{t}Waiting{/t}</span>
        <div class="title">
            <span class="" >
                <span class="Orders_In_Process_Not_Paid_Number button {if  $delivery_note_flow=='submitted_not_paid'}blue{/if}" title="{t}Waiting for customer{/t}" onclick="get_delivery_notes_table('submitted_not_paid',{ parent: 'warehouse','parent_key':{$warehouse->id}})">
                    <i style="font-size: 50%" class="far fa-user discreet" aria-hidden="true"></i> {$warehouse->get('Orders In Process Not Paid Number')}
                </span> | </span>
            <span class="Orders_In_Process_Paid_Number button " title="{t}Waiting for restock{/t}"
                  onclick="get_delivery_notes_table('submitted',{ parent: 'warehouse','parent_key':{$warehouse->id}})">{$warehouse->get('Orders In Process Paid Number')} <i style="font-size: 50%" class="fa fa-forklift " aria-hidden="true"></i> </span>

            </span> | </span>
            <span class="Orders_In_Process_Paid_Number button {if  $delivery_note_flow=='submitted'}blue{/if}" title="{t}Waiting for production{/t}"
                  onclick="get_delivery_notes_table('submitted',{ parent: 'warehouse','parent_key':{$warehouse->id}})">{$warehouse->get('Orders In Process Paid Number')} <i style="font-size: 50%" class="fa fa-industry" aria-hidden="true"></i> </span>

        </div>
        <div >
            <span class=""><span onclick="get_delivery_notes_table('submitted_not_paid',{ parent: 'warehouse','parent_key':{$warehouse->id}})" class="{if  $delivery_note_flow=='submitted_not_paid'}blue{/if} button Orders_In_Process_Not_Paid_Amount" title="{if $currency=='account'}{$warehouse->get('DC Orders In Process Not Paid Amount')}{else}{$warehouse->get('Orders In Process Not Paid Amount')}{/if}">{if $currency=='account'}{$warehouse->get('DC Orders In Process Not Paid Amount Minify')}{else}{$warehouse->get('Orders In Process Not Paid Amount Minify')}{/if}</span> | </span>
            <span  onclick="get_delivery_notes_table('submitted',{ parent: 'warehouse','parent_key':{$warehouse->id}})" class="{if  $delivery_note_flow=='submitted'}blue{/if}  button Orders_In_Process_Paid_Amount" title="{if $currency=='account'}{$warehouse->get('DC Orders In Process Paid Amount')}{else}{$warehouse->get('Orders In Process Paid Amount')}{/if}">{if $currency=='account'}{$warehouse->get('DC Orders In Process Paid Amount Minify')}{else}{$warehouse->get('Orders In Process Paid Amount Minify')}{/if}</span>
        </div>

    </li>
    <li id="delivery_note_flow_in_warehouse" class="flex-item delivery_note_flow {if $delivery_note_flow=='in_warehouse' or  $delivery_note_flow=='in_warehouse_with_alerts' }selected{/if}">
        <span>{t}Picking{/t}</span>
        <div class="title">
            <span class="" >
            <span class="Orders_In_Warehouse_No_Alerts_Number button {if  $delivery_note_flow=='in_warehouse'}blue{/if} " title="{t}Orders in warehouse without alerts{/t}" onclick="get_delivery_notes_table('in_warehouse',{ parent: 'warehouse','parent_key':{$warehouse->id}})">
               <i style="font-size: 50%" class="fa fa-bell invisible" aria-hidden="true"></i>  {$warehouse->get('Orders In Warehouse No Alerts Number')}
            </span> <span class="small"> <i style="font-size: 50%" class="fa fa-bell error" aria-hidden="true"></i></span>

        </div>

        </div>
        <div >
            <span onclick="get_delivery_notes_table('in_warehouse',{ parent: 'warehouse','parent_key':{$warehouse->id}})" class="button {if  $delivery_note_flow=='in_warehouse'}blue{/if} Orders_In_Warehouse_No_Alerts_Amount" title="{if $currency=='account'}{$warehouse->get('DC Orders In Warehouse No Alerts Amount')}{else}{$warehouse->get('Orders In Warehouse No Alerts Amount')}{/if}">{if $currency=='account'}{$warehouse->get('DC Orders In Warehouse No Alerts Amount Minify')}{else}{$warehouse->get('Orders In Warehouse No Alerts Amount Minify')}{/if}</span> |
            <span onclick="get_delivery_notes_table('in_warehouse_with_alerts',{ parent: 'warehouse','parent_key':{$warehouse->id}})" class="button {if  $delivery_note_flow=='in_warehouse_with_alerts'}blue{/if} Orders_In_Warehouse_With_Alerts_Amount" title="{if $currency=='account'}{$warehouse->get('DC Orders In Warehouse With Alerts Amount')}{else}{$warehouse->get('Orders In Warehouse With Alerts Amount')}{/if}">{if $currency=='account'}{$warehouse->get('DC Orders In Warehouse With Alerts Amount Minify')}{else}{$warehouse->get('Orders In Warehouse With Alerts Amount Minify')}{/if}</span>

        </div>

    </li>


    <li id="delivery_note_flow_packed" class="flex-item delivery_note_flow {if $delivery_note_flow=='packed_done' or  $delivery_note_flow=='approved' }selected{/if}">

        <span>{t}Packing{/t}</span>
        <div class="title">
            <span  id="delivery_note_flow_packed_done" class="{if  $delivery_note_flow=='packed_done'}blue{/if} Orders_Packed_Number button" title="{t}Closed orders waiting to be invoiced{/t}" onClick="get_delivery_notes_table('packed_done',{ parent: 'warehouse','parent_key':{$warehouse->id}})"> <i style="font-size: 50%" class="fa fa-archive" aria-hidden="true"></i> {$warehouse->get('Orders Packed Number')}</span>
            |
            <span  id="delivery_note_flow_approved"  class="{if  $delivery_note_flow=='approved'}blue{/if}  Orders_Dispatch_Approved_Number button" title="{t}Invoiced orders waiting to be dispatched{/t}"  onclick="get_delivery_notes_table('approved',{ parent: 'warehouse','parent_key':{$warehouse->id}})" >{$warehouse->get('Orders Dispatch Approved Number')} <i style="font-size: 50%" class="fal fa-file-alt" aria-hidden="true"></i> </span>

        </div >

        <span onClick="get_delivery_notes_table('packed_done',{ parent: 'warehouse','parent_key':{$warehouse->id}})" class="button Orders_Packed_Amount" title="{if $currency=='account'}{$warehouse->get('DC Orders Packed Amount')}{else}{$warehouse->get('Orders Packed Amount')}{/if}">{if $currency=='account'}{$warehouse->get('DC Orders Packed Amount Minify')}{else}{$warehouse->get('Orders Packed Amount Minify')}{/if}</span> |
        <span onclick="get_delivery_notes_table('approved',{ parent: 'warehouse','parent_key':{$warehouse->id}})" class="button Orders_Dispatch_Approved_Amount" title="{if $currency=='account'}{$warehouse->get('DC Orders Dispatch Approved Amount')}{else}{$warehouse->get('Orders Dispatch Approved Amount')}{/if}">{if $currency=='account'}{$warehouse->get('DC Orders Dispatch Approved Amount Minify')}{else}{$warehouse->get('Orders Dispatch Approved Amount Minify')}{/if}</span>

            </div>

    </li>

    <li id="delivery_note_flow_dispatched" class="flex-item delivery_note_flow {if $delivery_note_flow=='dispatched_today'}selected{/if}">
        <span>{t}Delivery area{/t}</span>
        <div class="title"><span class="" >

            <span class="Orders_Dispatched_Today_Number button" title="{t}Today's dispatched orders{/t}"  onclick="get_delivery_notes_table('dispatched_today',{ parent: 'warehouse','parent_key':{$warehouse->id}})">{$warehouse->get('Orders Dispatched Today Number')} <i style="font-size: 50%" class="hide fa fa-paper-plane " aria-hidden="true"></i> </span> </div>
        <div >
            <span onclick="get_delivery_notes_table('dispatched_today',{ parent: 'warehouse','parent_key':{$warehouse->id}})" class="Orders_Dispatched_Today_Amount button" title="{if $currency=='account'}{$warehouse->get('DC Orders Dispatched Today Amount')}{else}{$warehouse->get('Orders Dispatched Today Amount')}{/if}">{if $currency=='account'}{$warehouse->get('DC Orders Dispatched Today Amount Minify')}{else}{$warehouse->get('Orders Dispatched Today Amount Minify')}{/if}</span>
        </div>


    </li>
    

   
</ul>
<div id="widget_details" class="hide" style="clear:both;margin-top:-1px;border-top:1px solid #ccc"></div>

<script>
    var current_delivery_note_flow='';

    get_delivery_notes_table('{$delivery_note_flow}',{ parent: 'warehouse','parent_key':{$warehouse->id}},'Yes')



</script>


