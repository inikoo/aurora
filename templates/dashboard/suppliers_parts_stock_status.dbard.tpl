{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 16-09-2019 00:20:30 MYT,  Kuala Lumpur, Malaysia
 Copyright (c) 2019, Inikoo

 Version 3
-->
*}


<ul class="flex-container">


    <li class="flex-item ">
        <span>{t}Surplus{/t}</span>
        <div class="title"><span class="Active_Parts_Stock_Surplus_Number button"  onclick="change_view('inventory' , { 'tab':'inventory.parts',  'parameters':{ elements_type:'stock_status' } ,element:{ stock_status:{ Surplus:1,Optimal:'',Low:'',Critical:'',Error:'',Out_Of_Stock:''}} } )"   title="{t}Number active parts with surplus stock{/t}">{$account->get('Active Suppliers Parts Stock Surplus Number')}</span></div>
        <div>
             <span class="Active_Parts_Stock_Surplus_Stock_Value_Minify " title="{t}Stock value{/t}">{$account->get('Active Suppliers Parts Stock Surplus Stock Value Minify')}</span>

            | <i class="fa fa-fw fa-truck" title="{t}Current purchase orders{/t}"></i> <span class="Active_Parts_Stock_Surplus_Deliveries_Number " title="{t}Current purchase orders{/t}">{$account->get('Active Suppliers Parts Stock Surplus Deliveries Number')}</span>

        </div>
    </li>
    <li class="flex-item ">
        <span>{t}OK{/t}</span>
        <div class="title"><span class="Active_Parts_Stock_OK_Number button" onclick="change_view('inventory' , { 'tab':'inventory.parts',  'parameters':{ elements_type:'stock_status' } ,element:{ stock_status:{ Surplus:'',Optimal:1,Low:'',Critical:'',Error:'',Out_Of_Stock:''}} } )"  title="{t}Number active parts with normal stock{/t}">{$account->get('Active Suppliers Parts Stock OK Number')}</span></div>
        <div>
            <span class="Active_Parts_Stock_OK_Stock_Value_Minify " title="{t}Stock value{/t}">{$account->get('Active Suppliers Parts Stock OK Stock Value Minify')}</span>

            | <i class="fa fa-fw fa-truck" title="{t}Current purchase orders{/t}"></i> <span class="Active_Parts_Stock_OK_Deliveries_Number " title="{t}Current purchase orders{/t}">{$account->get('Active Suppliers Parts Stock OK Deliveries Number')}</span>

        </div>
    </li>

    <li class="flex-item ">
        <span>{t}Low{/t}</span>
        <div class="title"><span class="Active_Parts_Stock_Low_Number button" onclick="change_view('inventory' , { 'tab':'inventory.parts',  'parameters':{ elements_type:'stock_status' } ,element:{ stock_status:{ Surplus:'',Optimal:'',Low:1,Critical:'',Error:'',Out_Of_Stock:''}} } )"  title="{t}Number active parts with low stock{/t}">{$account->get('Active Suppliers Parts Stock Low Number')}</span></div>
        <div>
            <span class="Active_Parts_Stock_Low_Stock_Value_Minify " title="{t}Stock value{/t}">{$account->get('Active Suppliers Parts Stock Low Stock Value Minify')}</span>

            | <i class="fa fa-fw fa-truck" title="{t}Current purchase orders{/t}"></i> <span class="Active_Parts_Stock_Low_Deliveries_Number " title="{t}Current purchase orders{/t}">{$account->get('Active Suppliers Parts Stock Low Deliveries Number')}</span>

        </div>
    </li>

    <li class="flex-item ">
        <span>{t}Critical{/t}</span>
        <div class="title"><span class="Active_Parts_Stock_Critical_Number button" onclick="change_view('inventory' , { 'tab':'inventory.parts',  'parameters':{ elements_type:'stock_status' } ,element:{ stock_status:{ Surplus:'',Optimal:'',Low:'',Critical:1,Error:'',Out_Of_Stock:''}} } )"  title="{t}Number active parts with critical stock{/t}">{$account->get('Active Suppliers Parts Stock Critical Number')}</span></div>
        <div>
            <span class="Active_Parts_Stock_Critical_Stock_Value_Minify " title="{t}Stock value{/t}">{$account->get('Active Suppliers Parts Stock Critical Stock Value Minify')}</span>

            | <i class="fa fa-fw fa-truck" title="{t}Current purchase orders{/t}"></i> <span class="Active_Parts_Stock_Critical_Deliveries_Number " title="{t}Current purchase orders{/t}">{$account->get('Active Suppliers Parts Stock Critical Deliveries Number')}</span>

        </div>
    </li>

    <li class="flex-item ">
        <span>{t}Out of stock{/t}</span>
        <div class="title"><span class="Active_Parts_Stock_Zero_Number button" onclick="change_view('inventory' , { 'tab':'inventory.parts',  'parameters':{ elements_type:'stock_status' } ,element:{ stock_status:{ Surplus:'',Optimal:'',Low:'',Critical:'',Error:'',Out_Of_Stock:1}} } )"  title="{t}Number of out of stock parts{/t}">{$account->get('Active Suppliers Parts Stock Zero Number')}</span></div>
        <div>
             <i class="fa fa-fw fa-truck" title="{t}Current purchase orders{/t}"></i> <span class="Active_Parts_Stock_Zero_Deliveries_Number " title="{t}Current purchase orders{/t}">{$account->get('Active Suppliers Parts Stock Zero Deliveries Number')}</span>

        </div>
    </li>
</ul>

