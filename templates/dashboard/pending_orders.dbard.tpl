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
        <div class="title"><span class="Orders_In_Basket_Number">{$object->get('Orders In Basket Number')}</span></div>
        <div ><span class="Orders_In_Basket_Amount">{if $currency=='account'}{$object->get('DC Orders In Basket Amount Minify')}{else}{$object->get('Orders In Basket Amount Minify')}{/if}</span></div>

    </li>
    <li class="flex-item">
        <span>{t}Submitted{/t}</span>
        <div class="title"><span class="discreet" ><span class="Orders_In_Process_Not_Paid_Number" title="{t}Not paid submitted orders{/t}">{$object->get('Orders In Process Not Paid Number')}</span> | </span> <span class="Orders_In_Process_Paid_Number" title="{t}Paid submitted orders{/t}">{$object->get('Orders In Process Paid Number')}</span> </div>
        <div ><span class="discreet"><span class="Orders_In_Process_Not_Paid_Amount">{if $currency=='account'}{$object->get('DC Orders In Process Not Paid Amount Minify')}{else}{$object->get('Orders In Process Not Paid Amount Minify')}{/if}</span> | </span> <span class="Orders_In_Process_Paid_Amount">{if $currency=='account'}{$object->get('DC Orders In Process Paid Amount Minify')}{else}{$object->get('Orders In Process Paid Amount Minify')}{/if}</span></div>

    </li>
    <li class="flex-item">
        <span>{t}In warehouse{/t}</span>
        <div class="title">
            <span class="Orders_In_Warehouse_Number">{$object->get('Orders In Warehouse Number')}</span> |
            <span class="Orders_Packed_Number">{$object->get('Orders Packed Number')}</span> |
            <span class="Orders_In_Dispatch_Area_Number">{$object->get('Orders In Dispatch Area Number')}</span>
        </div>
        <div >
            <span class="Orders_In_Warehouse_Amount">{if $currency=='account'}{$object->get('DC Orders In Warehouse Amount Minify')}{else}{$object->get('Orders In Warehouse Amount Minify')}{/if}</span> |
            <span class="Orders_Packed_Amount">{if $currency=='account'}{$object->get('DC Orders Packed Amount Minify')}{else}{$object->get('Orders Packed Amount Minify')}{/if}</span> |
            <span class="Orders_In_Dispatch_Area_Amount">{if $currency=='account'}{$object->get('DC Orders In Dispatch Area Amount Minify')}{else}{$object->get('Orders In Dispatch Area Amount Minify')}{/if}</span>

        </div>

    </li>
    <li class="flex-item">
        <span>{t}Today{/t}</span>

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


            }


        });

    }
    

 </script>