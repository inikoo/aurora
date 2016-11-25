{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 8 November 2016 at 23:25:56 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3
-->
*}


<div id="dashboard_pending_orders" style="margin-top:20px;padding:0px" class="dashboard">
    
    <input id="pending_orders_currency" type="hidden" value="{$currency}">
    <input id="pending_orders_parent" type="hidden" value="{$parent}">

<table border="0" style="width:100%">
    <tr class="main_title small_row">
        <td colspan="9">
            <div class="widget_types">
                <div id="store_" onclick="change_pending_orders_parent('')"
                     class="widget  left  {if $parent==''}selected{/if}">
                    <span class="label"> {t}All stores{/t} </span>
                </div>

                {foreach from=$stores item=store}
                    <div id="store_{$store.key}" onclick="change_pending_orders_parent({$store.key})"
                         class="widget  left {if $parent==$store.key}selected{/if}">
                        <span class="label">{$store.code} </span>
                    </div>
                {/foreach}




            </div>

          

            <div id="pending_orders_currency_container" class="button  {if $parent==''   }hide{/if} "
                 onclick="toggle_pending_orders_currency()" style="float:right;margin-right:10px">
                <i id="pending_orders_currency_switch"
                   class="fa {if $currency=='store'}fa-toggle-on{else}fa-toggle-off{/if}"></i> {t}Store currency{/t}
            </div>


        </td>
    </tr>
 </table>
</div>
<ul class="flex-container">
    <li class="flex-item">
        <span>{t}Website{/t}</span>

    </li>
    <li class="flex-item">

        <span>{t}In basket{/t}</span>
        <div class="title"><span class="Orders_In_Basket_Number button"  onclick="go_to_orders(
                    
                       { parameters:{ period:'all',elements_type:'flow' } ,element:{ flow:{ Basket:1,Submitted_Unpaid:'',Submitted_Paid:'',InWarehouse:'',Packed:'',Dispatch_Ready:'',Dispatched_Today:''}}}
                    )">{$object->get('Orders In Basket Number')}</span></div>
        <div ><span class="Orders_In_Basket_Amount" title="{if $currency=='account'}{$object->get('DC Orders In Basket Amount')}{else}{$object->get('Orders In Basket Amount')}{/if}">{if $currency=='account'}{$object->get('DC Orders In Basket Amount Minify')}{else}{$object->get('Orders In Basket Amount Minify')}{/if}</span></div>

    </li>
    <li class="flex-item">
        <span>{t}Submitted{/t}</span>
        <div class="title"><span class="discreet" >
                <span class="Orders_In_Process_Not_Paid_Number button" title="{t}Unpaid submitted orders{/t}"  onclick="go_to_orders(
                        
                        { parameters:{ period:'all',elements_type:'flow' } ,element:{ flow:{ Basket:'',Submitted_Unpaid:1,Submitted_Paid:'',InWarehouse:'',Packed:'',Dispatch_Ready:'',Dispatched_Today:''}}}
                        )" >{$object->get('Orders In Process Not Paid Number')}</span> | </span>
            <span class="Orders_In_Process_Paid_Number button" title="{t}Paid submitted orders{/t}"  onclick="go_to_orders(

                    { parameters:{ period:'all',elements_type:'flow' } ,element:{ flow:{ Basket:'',Submitted_Unpaid:'',Submitted_Paid:1,InWarehouse:'',Packed:'',Dispatch_Ready:'',Dispatched_Today:''}}}
                    )">{$object->get('Orders In Process Paid Number')}</span> </div>
        <div >
            <span class="discreet"><span class="Orders_In_Process_Not_Paid_Amount" title="{if $currency=='account'}{$object->get('DC Orders In Process Not Paid Amount')}{else}{$object->get('Orders In Process Not Paid Amount')}{/if}">{if $currency=='account'}{$object->get('DC Orders In Process Not Paid Amount Minify')}{else}{$object->get('Orders In Process Not Paid Amount Minify')}{/if}</span> | </span>
            <span class="Orders_In_Process_Paid_Amount" title="{if $currency=='account'}{$object->get('DC Orders In Process Paid Amount')}{else}{$object->get('Orders In Process Paid Amount')}{/if}">{if $currency=='account'}{$object->get('DC Orders In Process Paid Amount Minify')}{else}{$object->get('Orders In Process Paid Amount Minify')}{/if}</span></div>

    </li>
    <li class="flex-item">
        <span>{t}In warehouse{/t}</span>
        <div class="title">
            <span class="Orders_In_Warehouse_Number button" title="{t}Orders in warehouse{/t}"
                  onclick="go_to_orders(

                    { parameters:{ period:'all',elements_type:'flow' } ,element:{ flow:{ Basket:'',Submitted_Unpaid:'',Submitted_Paid:'',InWarehouse:1,Packed:'',Dispatch_Ready:'',Dispatched_Today:''}}}
                    )"
            >{$object->get('Orders In Warehouse Number')}</span> |
            <span class="Orders_Packed_Number button" title="{t}Packed orders{/t}"  onclick="go_to_orders(

                    { parameters:{ period:'all',elements_type:'flow' } ,element:{ flow:{ Basket:'',Submitted_Unpaid:'',Submitted_Paid:'',InWarehouse:0,Packed:1,Dispatch_Ready:'',Dispatched_Today:''}}}
                    )">{$object->get('Orders Packed Number')}</span> |
            <span class="Orders_In_Dispatch_Area_Numbe button" title="{t}Orders ready to dispatch{/t}"  onclick="go_to_orders(

                    { parameters:{ period:'all',elements_type:'flow' } ,element:{ flow:{ Basket:'',Submitted_Unpaid:'',Submitted_Paid:'',InWarehouse:'',Packed:'',Dispatch_Ready:1,Dispatched_Today:''}}}
                    )">{$object->get('Orders In Dispatch Area Number')}</span>
        </div>
        <div >
            <span class="Orders_In_Warehouse_Amount" title="{if $currency=='account'}{$object->get('DC Orders In Warehouse Amount')}{else}{$object->get('Orders In Warehouse Amount')}{/if}">{if $currency=='account'}{$object->get('DC Orders In Warehouse Amount Minify')}{else}{$object->get('Orders In Warehouse Amount Minify')}{/if}</span> |
            <span class="Orders_Packed_Amount" title="{if $currency=='account'}{$object->get('DC Orders Packed Amount')}{else}{$object->get('Orders Packed Amount')}{/if}">{if $currency=='account'}{$object->get('DC Orders Packed Amount Minify')}{else}{$object->get('Orders Packed Amount Minify')}{/if}</span> |
            <span class="Orders_In_Dispatch_Area_Amount" title="{if $currency=='account'}{$object->get('DC Orders In Dispatch Area Amount')}{else}{$object->get('Orders In Dispatch Area Amount')}{/if}">{if $currency=='account'}{$object->get('DC Orders In Dispatch Area Amount Minify')}{else}{$object->get('Orders In Dispatch Area Amount Minify')}{/if}</span>

        </div>

    </li>
    <li class="flex-item">
        <span>{t}Today{/t}</span>
        <div class="title">
            <span class="Delta_Today_Start_Orders_In_Warehouse_Number" title="Today's difference of orders in warehouse">{$object->get('Delta Today Start Orders In Warehouse Number')}</span>
            <span class="padding_left_10 Today_Orders_Dispatched button" title="Today's dispatched orders"  onclick="go_to_orders(
                   
                    { parameters:{ period:'all',elements_type:'flow' } ,element:{ flow:{ Basket:'',Submitted_Unpaid:'',Submitted_Paid:'',InWarehouse:'',Packed:'',Dispatch_Ready:'',Dispatched_Today:1}}}
                    )">{$object->get('Today Orders Dispatched')}</span>

        </div>

    </li>
</ul>


<script>


    function toggle_pending_orders_currency() {
        if ($('#pending_orders_currency_switch').hasClass('fa-toggle-off')) {
            var currency = 'store'
            $('#pending_orders_currency_switch').removeClass('fa-toggle-off').addClass('fa-toggle-on')
        } else {
            var currency = 'account'
            $('#pending_orders_currency_switch').addClass('fa-toggle-off').removeClass('fa-toggle-on')
        }
        $('#pending_orders_currency').val(currency)

        get_pending_orders_data($('#pending_orders_parent').val(), $('#pending_orders_currency').val())

    }


    function change_pending_orders_parent(parent) {


        $('.widget_types .widget').removeClass('selected')
        $('#store_' + parent).addClass('selected')

if(parent==''){
    $('#pending_orders_currency_container').addClass('hide')

}else{
    $('#pending_orders_currency_container').removeClass('hide')

}

        get_pending_orders_data(parent, $('#pending_orders_currency').val())


    }


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

        var parent_key= $('#pending_orders_parent').val();
        if(parent_key==''){
            parent_key='all'

        }

        change_view('orders/'+parent_key,metadata)

    }
    

 </script>