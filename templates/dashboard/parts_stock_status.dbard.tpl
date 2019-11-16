{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 18 October 2018 at 13:47:37 GMT+8,  Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3
-->
*}


<div id="dashboard_inventory" style="margin-top:5px;padding:0px" class="dashboard">

    <input id="inventory_parent" type="hidden" value="{$parent}">


    <table style="width:100%">
        <tr class="main_title small_row">
            <td colspan="9">
                <div class="widget_types">
                    <div id="store_" onclick="change_pending_orders_parent('')" class="widget  left  {if $parent==''}selected{/if}">
                        <span class="label"> {t}All warehouses{/t} </span>
                    </div>


                </div>


            </td>
        </tr>
    </table>

</div>


<ul class="flex-container">


    <li class="flex-item ">
        <span>{t}Surplus{/t}</span>
        <div class="title"><span class="Active_Parts_Stock_Surplus_Number button"  onclick="change_view('inventory' , { 'tab':'inventory.parts',  'parameters':{ elements_type:'stock_status' } ,element:{ stock_status:{ Surplus:1,Optimal:'',Low:'',Critical:'',Error:'',Out_Of_Stock:''}} } )"   title="{t}Number active parts with surplus stock{/t}">{$object->get('Active Parts Stock Surplus Number')}</span></div>
        <div>
             <span class="Active_Parts_Stock_Surplus_Stock_Value_Minify " title="{t}Stock value{/t}">{$object->get('Active Parts Stock Surplus Stock Value Minify')}</span>

            | <i class="fa fa-fw fa-truck" title="{t}Parts within a purchase order{/t}"></i> <span class="Active_Parts_Stock_Surplus_Deliveries_Number " title="{t}Parts within a purchase order{/t}">{$object->get('Active Parts Stock Surplus Deliveries Number')}</span>

        </div>
    </li>
    <li class="flex-item ">
        <span>{t}OK{/t}</span>
        <div class="title"><span class="Active_Parts_Stock_OK_Number button" onclick="change_view('inventory' , { 'tab':'inventory.parts',  'parameters':{ elements_type:'stock_status' } ,element:{ stock_status:{ Surplus:'',Optimal:1,Low:'',Critical:'',Error:'',Out_Of_Stock:''}} } )"  title="{t}Number active parts with normal stock{/t}">{$object->get('Active Parts Stock OK Number')}</span></div>
        <div>
            <span class="Active_Parts_Stock_OK_Stock_Value_Minify " title="{t}Stock value{/t}">{$object->get('Active Parts Stock OK Stock Value Minify')}</span>

            | <i class="fa fa-fw fa-truck" title="{t}Parts within a purchase order{/t}"></i> <span class="Active_Parts_Stock_OK_Deliveries_Number " title="{t}Parts within a purchase order{/t}">{$object->get('Active Parts Stock OK Deliveries Number')}</span>

        </div>
    </li>

    <li class="flex-item ">
        <span>{t}Low{/t}</span>
        <div class="title"><span class="Active_Parts_Stock_Low_Number button" onclick="change_view('inventory' , { 'tab':'inventory.parts',  'parameters':{ elements_type:'stock_status' } ,element:{ stock_status:{ Surplus:'',Optimal:'',Low:1,Critical:'',Error:'',Out_Of_Stock:''}} } )"  title="{t}Number active parts with low stock{/t}">{$object->get('Active Parts Stock Low Number')}</span></div>
        <div>
            <span class="Active_Parts_Stock_Low_Stock_Value_Minify " title="{t}Stock value{/t}">{$object->get('Active Parts Stock Low Stock Value Minify')}</span>

            | <i class="fa fa-fw fa-truck" title="{t}Parts within a purchase order{/t}"></i> <span class="Active_Parts_Stock_Low_Deliveries_Number " title="{t}Parts within a purchase order{/t}">{$object->get('Active Parts Stock Low Deliveries Number')}</span>

        </div>
    </li>

    <li class="flex-item ">
        <span>{t}Critical{/t}</span>
        <div class="title"><span class="Active_Parts_Stock_Critical_Number button" onclick="change_view('inventory' , { 'tab':'inventory.parts',  'parameters':{ elements_type:'stock_status' } ,element:{ stock_status:{ Surplus:'',Optimal:'',Low:'',Critical:1,Error:'',Out_Of_Stock:''}} } )"  title="{t}Number active parts with critical stock{/t}">{$object->get('Active Parts Stock Critical Number')}</span></div>
        <div>
            <span class="Active_Parts_Stock_Critical_Stock_Value_Minify " title="{t}Stock value{/t}">{$object->get('Active Parts Stock Critical Stock Value Minify')}</span>

            | <i class="fa fa-fw fa-truck" title="{t}Parts within a purchase order{/t}"></i> <span class="Active_Parts_Stock_Critical_Deliveries_Number " title="{t}Parts within a purchase order{/t}">{$object->get('Active Parts Stock Critical Deliveries Number')}</span>

        </div>
    </li>

    <li class="flex-item ">
        <span>{t}Out of stock{/t}</span>
        <div class="title"><span class="Active_Parts_Stock_Zero_Number button" onclick="change_view('inventory' , { 'tab':'inventory.parts',  'parameters':{ elements_type:'stock_status' } ,element:{ stock_status:{ Surplus:'',Optimal:'',Low:'',Critical:'',Error:'',Out_Of_Stock:1}} } )"  title="{t}Number of out of stock parts{/t}">{$object->get('Active Parts Stock Zero Number')}</span></div>
        <div>
             <i class="fa fa-fw fa-truck" title="{t}Parts within a purchase order{/t}"></i> <span class="Active_Parts_Stock_Zero_Deliveries_Number " title="{t}Parts within a purchase order{/t}">{$object->get('Active Parts Stock Zero Deliveries Number')}</span>

        </div>
    </li>
</ul>

