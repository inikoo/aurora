{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 8 November 2016 at 23:25:56 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3
-->
*}


<ul class="flex-container" >

    <li id="order_flow_website" data-current_order_flow="{$order_flow}" class="flex-item order_flow  {if $order_flow=='website'}selected{/if}  " >

        <span>{t}In basket{/t}</span>
        <div class="title"><span id="Orders_In_Basket_Number" class="{if  $order_flow=='website'}blue{/if} Orders_In_Basket_Number button"  onClick="get_orders_table('website',{ parent: 'store','parent_key':{$store->id}})"> {$store->get('Orders In Basket Number')}</span></div>
        <div ><span class="{if  $order_flow=='website'}blue{/if} Orders_In_Basket_Amount button" onClick="get_orders_table('website',{ parent: 'store','parent_key':{$store->id}})" title="{if $currency=='account'}{$store->get('DC Orders In Basket Amount')}{else}{$store->get('Orders In Basket Amount')}{/if}">{if $currency=='account'}{$store->get('DC Orders In Basket Amount Minify')}{else}{$store->get('Orders In Basket Amount Minify')}{/if}</span></div>

    </li>
    <li id="order_flow_submitted" class="flex-item order_flow {if $order_flow=='submitted_not_paid' or  $order_flow=='submitted' }selected{/if}" >
        <span>{t}Submitted{/t}</span>
        <div class="title">
            <span >
                <span class="Orders_In_Process_Not_Paid_Number button {if  $order_flow=='submitted_not_paid'}blue{/if}" title="{t}Unpaid submitted orders{/t}" onclick="get_orders_table('submitted_not_paid',{ parent: 'store','parent_key':{$store->id}})">
                    <i style="font-size: 50%" class="fa fa-dollar-sign discreet" aria-hidden="true"></i> {$store->get('Orders In Process Not Paid Number')}
                </span> | </span>
            <span class="Orders_In_Process_Paid_Number button {if  $order_flow=='submitted'}blue{/if}" title="{t}Paid submitted orders{/t}"
                  onclick="get_orders_table('submitted',{ parent: 'store','parent_key':{$store->id}})">{$store->get('Orders In Process Paid Number')} <i style="font-size: 50%" class="fa fa-dollar-sign success" aria-hidden="true"></i> </span>  </div>
        <div >
            <span ><span onclick="get_orders_table('submitted_not_paid',{ parent: 'store','parent_key':{$store->id}})" class="{if  $order_flow=='submitted_not_paid'}blue{/if} button Orders_In_Process_Not_Paid_Amount" title="{if $currency=='account'}{$store->get('DC Orders In Process Not Paid Amount')}{else}{$store->get('Orders In Process Not Paid Amount')}{/if}">{if $currency=='account'}{$store->get('DC Orders In Process Not Paid Amount Minify')}{else}{$store->get('Orders In Process Not Paid Amount Minify')}{/if}</span> | </span>
            <span  onclick="get_orders_table('submitted',{ parent: 'store','parent_key':{$store->id}})" class="{if  $order_flow=='submitted'}blue{/if}  button Orders_In_Process_Paid_Amount" title="{if $currency=='account'}{$store->get('DC Orders In Process Paid Amount')}{else}{$store->get('Orders In Process Paid Amount')}{/if}">{if $currency=='account'}{$store->get('DC Orders In Process Paid Amount Minify')}{else}{$store->get('Orders In Process Paid Amount Minify')}{/if}</span></div>

    </li>
    <li id="order_flow_in_warehouse" class="flex-item order_flow {if $order_flow=='in_warehouse' or  $order_flow=='in_warehouse_with_alerts' }selected{/if}">
        <span>{t}In Warehouse{/t}</span>
        <div class="title">
            <span >
            <span class="Orders_In_Warehouse_No_Alerts_Number button {if  $order_flow=='in_warehouse'}blue{/if} " title="{t}Orders in warehouse without alerts{/t}" onclick="get_orders_table('in_warehouse',{ parent: 'store','parent_key':{$store->id}})">
               <i style="font-size: 50%" class="fa fa-bell invisible" aria-hidden="true"></i>  {$store->get('Orders In Warehouse No Alerts Number')}
            </span>
            | </span>
            <span class="Orders_In_Warehouse_With_Alerts_Number button {if  $order_flow=='in_warehouse_with_alerts'}blue{/if} " title="{t}Orders in warehouse with alerts{/t}" onclick="get_orders_table('in_warehouse_with_alerts',{ parent: 'store','parent_key':{$store->id}})">
                {$store->get('Orders In Warehouse With Alerts Number')} <i style="font-size: 50%" class="fa fa-bell error" aria-hidden="true"></i>
            </span>
        </div>

        </div>
        <div >
            <span onclick="get_orders_table('in_warehouse',{ parent: 'store','parent_key':{$store->id}})" class="button {if  $order_flow=='in_warehouse'}blue{/if} Orders_In_Warehouse_No_Alerts_Amount" title="{if $currency=='account'}{$store->get('DC Orders In Warehouse No Alerts Amount')}{else}{$store->get('Orders In Warehouse No Alerts Amount')}{/if}">{if $currency=='account'}{$store->get('DC Orders In Warehouse No Alerts Amount Minify')}{else}{$store->get('Orders In Warehouse No Alerts Amount Minify')}{/if}</span> |
            <span onclick="get_orders_table('in_warehouse_with_alerts',{ parent: 'store','parent_key':{$store->id}})" class="button {if  $order_flow=='in_warehouse_with_alerts'}blue{/if} Orders_In_Warehouse_With_Alerts_Amount" title="{if $currency=='account'}{$store->get('DC Orders In Warehouse With Alerts Amount')}{else}{$store->get('Orders In Warehouse With Alerts Amount')}{/if}">{if $currency=='account'}{$store->get('DC Orders In Warehouse With Alerts Amount Minify')}{else}{$store->get('Orders In Warehouse With Alerts Amount Minify')}{/if}</span>

        </div>

    </li>


    <li id="order_flow_packed" class="flex-item order_flow {if $order_flow=='packed_done' or  $order_flow=='approved' }selected{/if}">

        <span>{t}Invoicing{/t}</span>
        <div class="title">
            <span  id="order_flow_packed_done" class="{if  $order_flow=='packed_done'}blue{/if} Orders_Packed_Number button" title="{t}Closed orders waiting to be invoiced{/t}" onClick="get_orders_table('packed_done',{ 'parent': 'store','parent_key':{$store->id}})"> <i style="font-size: 50%" class="fa fa-archive" aria-hidden="true"></i> {$store->get('Orders Packed Number')}</span>
            |
            <span  id="order_flow_approved"  class="{if  $order_flow=='approved'}blue{/if}  Orders_Dispatch_Approved_Number button" title="{t}Invoiced orders waiting to be dispatched{/t}"  onclick="get_orders_table('approved',{ 'parent': 'store','parent_key':{$store->id}})" >{$store->get('Orders Dispatch Approved Number')} <i style="font-size: 50%" class="fal fa-file-invoice-dollar" aria-hidden="true"></i> </span>

        </div >

        <span onClick="get_orders_table('packed_done',{ 'parent': 'store','parent_key':{$store->id}})" class="button Orders_Packed_Amount" title="{if $currency=='account'}{$store->get('DC Orders Packed Amount')}{else}{$store->get('Orders Packed Amount')}{/if}">{if $currency=='account'}{$store->get('DC Orders Packed Amount Minify')}{else}{$store->get('Orders Packed Amount Minify')}{/if}</span> |
        <span onclick="get_orders_table('approved',{ 'parent': 'store','parent_key':{$store->id}})" class="button Orders_Dispatch_Approved_Amount" title="{if $currency=='account'}{$store->get('DC Orders Dispatch Approved Amount')}{else}{$store->get('Orders Dispatch Approved Amount')}{/if}">{if $currency=='account'}{$store->get('DC Orders Dispatch Approved Amount Minify')}{else}{$store->get('Orders Dispatch Approved Amount Minify')}{/if}</span>

        </div>

    </li>

    <li id="order_flow_dispatched" class="flex-item order_flow {if $order_flow=='dispatched_today'}selected{/if}">
        <span>{t}Dispatched today{/t}</span>
        <div class="title"><span >

            <span class="Orders_Dispatched_Today_Number button" title="{t}Today's dispatched orders{/t}"  onclick="get_orders_table('dispatched_today',{ 'parent': 'store','parent_key':{$store->id}})">{$store->get('Orders Dispatched Today Number')} <i style="font-size: 50%" class="hide fa fa-paper-plane " aria-hidden="true"></i> </span> </div>
        <div >
            <span onclick="get_orders_table('dispatched_today',{ 'parent': 'store','parent_key':{$store->id}})" class="Orders_Dispatched_Today_Amount button" title="{if $currency=='account'}{$store->get('DC Orders Dispatched Today Amount')}{else}{$store->get('Orders Dispatched Today Amount')}{/if}">{if $currency=='account'}{$store->get('DC Orders Dispatched Today Amount Minify')}{else}{$store->get('Orders Dispatched Today Amount Minify')}{/if}</span>
        </div>


    </li>



</ul>
<div id="widget_details" class="hide" style="clear:both;margin-top:-1px;border-top:1px solid #ccc"></div>

        <script>

            get_orders_table('{$order_flow}',{ 'parent': 'store','parent_key':{$store->id}},'Yes')
        </script>
