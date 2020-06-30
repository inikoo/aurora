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
                    <div style="margin-left: 20px;">
                        <span title="Inventory excluding production" class="label">{if $object->get('Account Manufacturers')>0}<span style="color:white">{t}Inventory{/t}</span> <span class="italic">({t}excluding production{/t})</span>{else}{t}Inventory{/t}{/if} </span>
                    </div>


                </div>


            </td>
        </tr>
    </table>

</div>


<ul class="flex-container">


    <li class="flex-item ">
        <span>{t}Surplus{/t}</span>
        <div class="title"><span class="Active_Parts_Stock_Surplus_Number_Excluding_Production button"  onclick="change_view('inventory' , { 'tab':'inventory.parts',  'parameters':{ elements_type:'stock_status' } ,element:{ stock_status:{ Surplus:1,Optimal:'',Low:'',Critical:'',Error:'',Out_Of_Stock:''}} } )"   title="{t}Number active parts with surplus stock{/t}">{$object->get('Active Parts Stock Surplus Number Excluding Production')}</span></div>
        <div>
             <span class="Active_Parts_Stock_Surplus_Stock_Value_Minify_Excluding_Production " title="{t}Stock value{/t}">{$object->get('Active Parts Stock Surplus Stock Value Minify Excluding Production')}</span>

            | <i class="fal fa-fw fa-clipboard" title="{t}Parts within a purchase order{/t}"></i> <span class="Active_Parts_Stock_Surplus_Deliveries_Number_Excluding_Production " title="{t}Parts within a purchase order{/t}">{$object->get('Active Parts Stock Surplus Deliveries Number Excluding Production')}</span>

        </div>
    </li>
    <li class="flex-item ">
        <span>{t}OK{/t}</span>
        <div class="title"><span class="Active_Parts_Stock_OK_Number_Excluding_Production button" onclick="change_view('inventory' , { 'tab':'inventory.parts',  'parameters':{ elements_type:'stock_status' } ,element:{ stock_status:{ Surplus:'',Optimal:1,Low:'',Critical:'',Error:'',Out_Of_Stock:''}} } )"  title="{t}Number active parts with normal stock{/t}">{$object->get('Active Parts Stock OK Number Excluding Production')}</span></div>
        <div>
            <span class="Active_Parts_Stock_OK_Stock_Value_Minify_Excluding_Production " title="{t}Stock value{/t}">{$object->get('Active Parts Stock OK Stock Value Minify Excluding Production')}</span>

            | <i class="fal fa-fw fa-clipboard" title="{t}Parts within a purchase order{/t}"></i> <span class="Active_Parts_Stock_OK_Deliveries_Number_Excluding_Production " title="{t}Parts within a purchase order{/t}">{$object->get('Active Parts Stock OK Deliveries Number Excluding Production')}</span>

        </div>
    </li>

    <li class="flex-item ">
        <span>{t}Low{/t}</span>
        <div class="title"><span class="Active_Parts_Stock_Low_Number_Excluding_Production button" onclick="change_view('inventory' , { 'tab':'inventory.parts',  'parameters':{ elements_type:'stock_status' } ,element:{ stock_status:{ Surplus:'',Optimal:'',Low:1,Critical:'',Error:'',Out_Of_Stock:''}} } )"  title="{t}Number active parts with low stock{/t}">{$object->get('Active Parts Stock Low Number Excluding Production')}</span></div>
        <div>
            <span class="Active_Parts_Stock_Low_Stock_Value_Minify_Excluding_Production " title="{t}Stock value{/t}">{$object->get('Active Parts Stock Low Stock Value Minify Excluding Production')}</span>

            | <i class="fal fa-fw fa-clipboard" title="{t}Parts within a purchase order{/t}"></i> <span class="Active_Parts_Stock_Low_Deliveries_Number_Excluding_Production " title="{t}Parts within a purchase order{/t}">{$object->get('Active Parts Stock Low Deliveries Number Excluding Production')}</span>

        </div>
    </li>

    <li class="flex-item ">
        <span>{t}Critical{/t}</span>
        <div class="title"><span class="Active_Parts_Stock_Critical_Number_Excluding_Production button" onclick="change_view('inventory' , { 'tab':'inventory.parts',  'parameters':{ elements_type:'stock_status' } ,element:{ stock_status:{ Surplus:'',Optimal:'',Low:'',Critical:1,Error:'',Out_Of_Stock:''}} } )"  title="{t}Number active parts with critical stock{/t}">{$object->get('Active Parts Stock Critical Number Excluding Production')}</span></div>
        <div>
            <span class="Active_Parts_Stock_Critical_Stock_Value_Minify_Excluding_Production " title="{t}Stock value{/t}">{$object->get('Active Parts Stock Critical Stock Value Minify Excluding Production')}</span>

            | <i class="fal fa-fw fa-clipboard" title="{t}Parts within a purchase order{/t}"></i> <span class="Active_Parts_Stock_Critical_Deliveries_Number_Excluding_Production " title="{t}Parts within a purchase order{/t}">{$object->get('Active Parts Stock Critical Deliveries Number Excluding Production')}</span>

        </div>
    </li>

    <li class="flex-item ">
        <span>{t}Out of stock{/t}</span>
        <div class="title"><span class="Active_Parts_Stock_Zero_Number_Excluding_Production button" onclick="change_view('inventory' , { 'tab':'inventory.parts',  'parameters':{ elements_type:'stock_status' } ,element:{ stock_status:{ Surplus:'',Optimal:'',Low:'',Critical:'',Error:'',Out_Of_Stock:1}} } )"  title="{t}Number of out of stock parts{/t}">{$object->get('Active Parts Stock Zero Number Excluding Production')}</span></div>
        <div>
             <i class="fal fa-fw fa-clipboard" title="{t}Parts within a purchase order{/t}"></i> <span class="Active_Parts_Stock_Zero_Deliveries_Number_Excluding_Production " title="{t}Parts within a purchase order{/t}">{$object->get('Active Parts Stock Zero Deliveries Number Excluding Production')}</span>

        </div>
    </li>
</ul>

