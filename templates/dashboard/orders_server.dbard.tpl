{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 18 August 2017 at 08:42:15 CEST, Tranava Slovakia
 Copyright (c) 2016, Inikoo

 Version 3
-->
*}

<ul class="flex-container">

    <li class="flex-item">

        <span>{t}In basket{/t}</span>
        <div class="title"><span class="Orders_In_Basket_Number button"  onClick="get_widget_details(this,'orders.website.wget',{ parent: 'account','parent_key':1})"> {$account->get('Orders In Basket Number')}</span></div>
        <div ><span class="Orders_In_Basket_Amount" title="{if $currency=='account'}{$account->get('DC Orders In Basket Amount')}{else}{$account->get('Orders In Basket Amount')}{/if}">{if $currency=='account'}{$account->get('DC Orders In Basket Amount Minify')}{else}{$account->get('Orders In Basket Amount Minify')}{/if}</span></div>

    </li>
    <li class="flex-item">
        <span>{t}Submitted{/t}</span>
        <div class="title">
            <span class="" >
                <span class="Orders_In_Process_Not_Paid_Number button" title="{t}Unpaid submitted orders{/t}" onclick="get_widget_details(this,'orders.in_process.not_paid.wget',{ parent: 'account','parent_key':1})">
                    <i style="font-size: 50%" class="fa fa-usd discreet" aria-hidden="true"></i> {$account->get('Orders In Process Not Paid Number')}
                </span> | </span>
            <span class="Orders_In_Process_Paid_Number button" title="{t}Paid submitted orders{/t}"
                  onclick="get_widget_details(this,'orders.in_process.paid.wget',{ parent: 'account','parent_key':1})">{$account->get('Orders In Process Paid Number')} <i style="font-size: 50%" class="fa fa-usd success" aria-hidden="true"></i> </span>  </div>
        <div >
            <span class=""><span class="Orders_In_Process_Not_Paid_Amount" title="{if $currency=='account'}{$account->get('DC Orders In Process Not Paid Amount')}{else}{$account->get('Orders In Process Not Paid Amount')}{/if}">{if $currency=='account'}{$account->get('DC Orders In Process Not Paid Amount Minify')}{else}{$account->get('Orders In Process Not Paid Amount Minify')}{/if}</span> | </span>
            <span class="Orders_In_Process_Paid_Amount" title="{if $currency=='account'}{$account->get('DC Orders In Process Paid Amount')}{else}{$account->get('Orders In Process Paid Amount')}{/if}">{if $currency=='account'}{$account->get('DC Orders In Process Paid Amount Minify')}{else}{$account->get('Orders In Process Paid Amount Minify')}{/if}</span></div>

    </li>
    <li class="flex-item">
        <span>{t}In warehouse{/t}</span>
        <div class="title">
            <span class="" >
            <span class="Orders_In_Warehouse_No_Alerts_Number button" title="{t}Orders in warehouse without alerts{/t}" onclick="get_widget_details(this,'orders.in_warehouse_no_alerts.wget',{ parent: 'account','parent_key':1})">
               <i style="font-size: 50%" class="fa fa-bell invisible" aria-hidden="true"></i>  {$account->get('Orders In Warehouse No Alerts Number')}
            </span>
            | </span>
            <span class="Orders_In_Warehouse_With_Alerts_Number button" title="{t}Orders in warehouse with alerts{/t}" onclick="get_widget_details(this,'orders.in_warehouse_with_alerts.wget',{ parent: 'account','parent_key':1})">
                {$account->get('Orders In Warehouse With Alerts Number')} <i style="font-size: 50%" class="fa fa-bell error" aria-hidden="true"></i>
            </span>
        </div>

        </div>
        <div >
            <span class="Orders_In_Warehouse_No_Alerts_Amount" title="{if $currency=='account'}{$account->get('DC Orders In Warehouse No Alerts Amount')}{else}{$account->get('Orders In Warehouse No Alerts Amount')}{/if}">{if $currency=='account'}{$account->get('DC Orders In Warehouse No Alerts Amount Minify')}{else}{$account->get('Orders In Warehouse No Alerts Amount Minify')}{/if}</span> |
            <span class="Orders_In_Warehouse_With_Alerts_Amount" title="{if $currency=='account'}{$account->get('DC Orders In Warehouse With Alerts Amount')}{else}{$account->get('Orders In Warehouse With Alerts Amount')}{/if}">{if $currency=='account'}{$account->get('DC Orders In Warehouse With Alerts Amount Minify')}{else}{$account->get('Orders In Warehouse With Alerts Amount Minify')}{/if}</span>

        </div>

    </li>


    <li class="flex-item">

        <span>{t}To invoice{/t}</span>
        <div class="title"><span class="Orders_Packed_Number button"  onClick="get_widget_details(this,'orders.packed_done.wget',{ parent: 'account','parent_key':1})"> {$account->get('Orders Packed Number')}</span></div>
        <div ><span class="Orders_Packed_Amount" title="{if $currency=='account'}{$account->get('DC Orders Packed Amount')}{else}{$account->get('Orders Packed Amount')}{/if}">{if $currency=='account'}{$account->get('DC Orders Packed Amount Minify')}{else}{$account->get('Orders Packed Amount Minify')}{/if}</span></div>

    </li>



    <li class="flex-item">
        <span>{t}Dispatch area{/t}</span>
        <div class="title"><span class="" >
                <span class="Orders_In_Dispatch_Area_Number button" title="{t}Invoiced orders waiting to be dispatched{/t}"  onclick="get_widget_details(this,'orders.approved.wget',{ parent: 'account','parent_key':1})" > <i style="font-size: 50%" class="fa fa-file-text-o" aria-hidden="true"></i> {$account->get('Orders In Dispatch Area Number')}</span> | </span>
            <span class="Orders_Dispatched_Today_Number button" title="{t}Today's dispatched orders{/t}"  onclick="get_widget_details(this,'orders.dispatched_today.wget',{ parent: 'account','parent_key':1})">{$account->get('Orders Dispatched Today Number')} <i style="font-size: 50%" class="fa fa-paper-plane " aria-hidden="true"></i> </span> </div>
        <div >
            <span class=""><span class="Orders_In_Dispatch_Area_Amount" title="{if $currency=='account'}{$account->get('DC Orders In Process Not Paid Amount')}{else}{$account->get('Orders In Dispatch Area Amount')}{/if}">{if $currency=='account'}{$account->get('DC Orders In Dispatch Area Amount Minify')}{else}{$account->get('Orders In Dispatch Area Amount Minify')}{/if}</span> | </span>
            <span class="Orders_Dispatched_Today_Amount" title="{if $currency=='account'}{$account->get('DC Orders Dispatched Today Amount')}{else}{$account->get('Orders Dispatched Today Amount')}{/if}">{if $currency=='account'}{$account->get('DC Orders Dispatched Today Amount Minify')}{else}{$account->get('Orders Dispatched Today Amount Minify')}{/if}</span></div>


    </li>
</ul>


<script>
    function get_pending_orders_data(parent,  currency) {

        var request = "/ar_dashboard.php?tipo=pending_orders&parent=" + parent + '&currency=' + currency
        console.log(request)
        $.getJSON(request, function (r) {


            $('#pending_orders_parent').val(parent)

            for (var record in r.data) {

                console.log(record)
                console.log(r.data[record].value)

                $('.' + record).html(r.data[record].value)

                if(r.data[record].title!= undefined ) {
                    $('.' + record).prop('title', r.data[record].title);
                }




            }


        });

    }

    function go_to_orders(metadata){

      

        change_view('orders/'+{$account->id},metadata)

    }
    
    </script>
