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
            <span >
                <span class="formatted_ready_to_pick_number button discreet" title="{t}New delivery notes{/t}" onclick="get_delivery_notes_table('ready_to_pick',{ parent: 'warehouse','parent_key':{$warehouse->id}})">
                    <i style="font-size: 50%" class="fa fa-seedling fa-fw " aria-hidden="true"></i> {$warehouse->get('formatted_ready_to_pick_number')}
                </span> | </span>
            <span class="formatted_assigned_number button " title="{t}Delivery note assigned{/t}"
                  onclick="get_delivery_notes_table('assigned',{ parent: 'warehouse','parent_key':{$warehouse->id}})"> {$warehouse->get('formatted_assigned_number')} <i style="font-size: 50%" class="fa fa-chalkboard-teacher fa-fw" aria-hidden="true"></i> </span>  </div>
        <div >
            <span ><span onclick="get_delivery_notes_table('ready_to_pick',{ parent: 'warehouse','parent_key':{$warehouse->id}})" class="button discreet formatted_ready_to_pick_weight" title=""> {$warehouse->get('formatted_ready_to_pick_weight')} </span> | </span>
            <span  onclick="get_delivery_notes_table('assigned',{ parent: 'warehouse','parent_key':{$warehouse->id}})" class="formatted_assigned_weight  button " title=""> {$warehouse->get('formatted_assigned_weight')} </span>
        </div>


    </li>
    <li id="delivery_note_flow_waiting" class="flex-item delivery_note_flow {if $delivery_note_flow=='waiting_not_paid' or  $delivery_note_flow=='waiting' }selected{/if}" >
        <span>{t}Waiting{/t}</span>
        <div class="title">
            <span >
                <span class="formatted_waiting_for_customer_number button {if  $delivery_note_flow=='waiting_not_paid'}blue{/if}" title="{t}Waiting for customer{/t}" onclick="get_delivery_notes_table('waiting_for_customer',{ parent: 'warehouse','parent_key':{$warehouse->id}})">
                    <i style="font-size: 50%" class="far fa-user discreet" aria-hidden="true"></i> {$warehouse->get('formatted_waiting_for_customer_number')}
                </span> | </span>
            <span class="formatted_waiting_for_restock_number button " title="{t}Waiting for restock{/t}"
                  onclick="get_delivery_notes_table('waiting_for_restock',{ parent: 'warehouse','parent_key':{$warehouse->id}})">{$warehouse->get('formatted_waiting_for_restock_number')} <i style="font-size: 50%" class="fa fa-forklift " aria-hidden="true"></i> </span>

            </span> | </span>
            <span class="formatted_waiting_for_production_number button {if  $delivery_note_flow=='waiting'}blue{/if}" title="{t}Waiting for production{/t}"
                  onclick="get_delivery_notes_table('waiting_for_production',{ parent: 'warehouse','parent_key':{$warehouse->id}})">{$warehouse->get('formatted_waiting_for_production_number')} <i style="font-size: 50%" class="fa fa-industry" aria-hidden="true"></i> </span>

        </div>
        <div >
             </div>

    </li>
    <li id="delivery_note_flow_in_warehouse" class="flex-item delivery_note_flow {if $delivery_note_flow=='picking'  }selected{/if}">
        <span>{t}Picking{/t}</span>
        <div class="title">
            <span >
            <span class="formatted_picking_number button {if  $delivery_note_flow=='in_warehouse'}blue{/if} " title="{t}Orders been picked{/t}" onclick="get_delivery_notes_table('picking',{ parent: 'warehouse','parent_key':{$warehouse->id}})">
              {$warehouse->get('formatted_picking_number')}
            </span>

        </div>

        </div>
        <div >

        </div>

    </li>


    <li id="delivery_note_flow_packed" class="flex-item delivery_note_flow {if $delivery_note_flow=='packed_done' or  $delivery_note_flow=='approved' }selected{/if}">

        <span>{t}Packing{/t}</span>
        <div class="title">
            <span   class="{if  $delivery_note_flow=='packing'}blue{/if} Orders_Packed_Number button" title="{t}Orders been packed{/t}" onClick="get_delivery_notes_table('packing',{ parent: 'warehouse','parent_key':{$warehouse->id}})"> <i style="font-size: 50%" class="far fa-box-full" aria-hidden="true"></i> {$warehouse->get('formatted_packing_number')}</span>
            |
            <span  class="{if  $delivery_note_flow=='packed_done'}blue{/if}  Orders_Dispatch_Approved_Number button" title="{t}Orders closed waiting for paperwork{/t}"  onclick="get_delivery_notes_table('packed_done',{ parent: 'warehouse','parent_key':{$warehouse->id}})" >{$warehouse->get('formatted_packed_done_number')} <i style="font-size: 50%" class="fal fa-archive" aria-hidden="true"></i> </span>

        </div >


            </div>

    </li>

    <li id="delivery_note_flow_dispatched" class="flex-item delivery_note_flow {if $delivery_note_flow=='dispatched_today'}selected{/if}">
        <span>{t}Delivery area{/t}</span>
        <div class="title"><span >

            <span class=" button" title="{t}Order ready to be dispatched{/t}"  onclick="get_delivery_notes_table('approved',{ parent: 'warehouse','parent_key':{$warehouse->id}})">{$warehouse->get('formatted_approved_number')} <i style="font-size: 50%" class=" far fa-truck-loading" aria-hidden="true"></i> </span> </div>
        <div >
        </div>


    </li>
    

   
</ul>
<div id="widget_details" class="hide" style="clear:both;margin-top:-1px;border-top:1px solid #ccc"></div>

<script>
    var current_delivery_note_flow='';

    get_delivery_notes_table('{$delivery_note_flow}',{ parent: 'warehouse','parent_key':{$warehouse->id}},'Yes')



</script>


