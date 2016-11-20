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

<table border="0" style="width:100%">
    <tr class="main_title small_row">
        <td colspan="9">
            <div class="widget_types">
                <div id="account" onclick="change_pending_orders_store('orders')"
                     class="widget  left  {if $object_arg==''}selected{/if}">
                    <span class="label"> {t}All stores{/t} </span>
                </div>

                {foreach from=$stores item=store}
                    <div id="store_{$store.key}" onclick="change_pending_orders_store({$store.key})"
                         class="widget  left {if $object_arg==$store.key}selected{/if}">
                        <span class="label">{$store.code} </span>
                    </div>
                {/foreach}




            </div>

          

            <div id="pending_orders_currency_container"
                 class="button  {if $type=='delivery_notes' or ($type=='orders' and $orders_view_type=='numbers')  }hide{/if} "
                 onclick="toggle_pending_orders_currency()" style="float:right;margin-right:10px">
                <i id="pending_orders_currency"
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
        <div class="title">{$object->get('Orders In Basket Number')}</div>
        <div >{$object->get('Orders In Basket Amount Soft Minify')}</div>

    </li>
    <li class="flex-item">
        <span>{t}Submitted{/t}</span>
        <div class="title"><span class="discreet"><span>{$object->get('Orders In Process Not Paid Number')}</span> | </span> <span>{$object->get('Orders In Process Paid Number')}</span> </div>
        <div ><span class="discreet"><span>{$object->get('Orders In Process Not Paid Amount Soft Minify')}</span> | </span> <span>{$object->get('Orders In Process Paid Amount Soft Minify')}</span></div>

    </li>
    <li class="flex-item">
        <span>{t}In warehouse{/t}</span>
        <div class="title"><span class=""><span>{$object->get('Orders In Warehouse Number')}</span> | </span> <span>{$object->get('Orders Packed Number')}</span> </div>
        <div ><span>{$object->get('Orders In Warehouse Amount Soft Minify')}</span> | <span>{$object->get('Orders Packed Amount Soft Minify')}</span></div>

    </li>
    <li class="flex-item">
        <span>{t}Today{/t}</span>

    </li>
</ul>