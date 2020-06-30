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
        <div class="title"><span class="Active_Parts_Stock_Surplus_Number button"  onclick="change_view('suppliers/supplier_parts' , { 'subtab':'suppliers.supplier_parts.surplus',  } )"   title="{t}Number active parts with surplus stock{/t}">{$account->get('Active Suppliers Parts Stock Surplus Number')}</span></div>
        <div>
             <span class="Active_Parts_Stock_Surplus_Stock_Value_Minify " title="{t}Stock value{/t}">{$account->get('Active Suppliers Parts Stock Surplus Stock Value Minify')}</span>

            | <i class="fal fa-fw fa-clipboard" title="{t}Parts within a purchase order{/t}"></i> <span class="Active_Parts_Stock_Surplus_Deliveries_Number " title="{t}Parts within a purchase order{/t}">{$account->get('Active Suppliers Parts Stock Surplus Deliveries Number')}</span>

        </div>
    </li>
    <li class="flex-item ">
        <span>{t}OK{/t}</span>
        <div class="title"><span class="Active_Parts_Stock_OK_Number button" onclick="change_view('suppliers/supplier_parts' , { 'subtab':'suppliers.supplier_parts.ok',  } )"  title="{t}Number active parts with normal stock{/t}">{$account->get('Active Suppliers Parts Stock OK Number')}</span></div>
        <div>
            <span class="Active_Parts_Stock_OK_Stock_Value_Minify " title="{t}Stock value{/t}">{$account->get('Active Suppliers Parts Stock OK Stock Value Minify')}</span>

            | <i class="fal fa-fw fa-clipboard" title="{t}Parts within a purchase order{/t}"></i> <span class="Active_Parts_Stock_OK_Deliveries_Number " title="{t}Parts within a purchase order{/t}">{$account->get('Active Suppliers Parts Stock OK Deliveries Number')}</span>

        </div>
    </li>

    <li class="flex-item ">
        <span>{t}Low{/t}</span>
        <div class="title"><span class="Active_Parts_Stock_Low_Number button"  onclick="change_view('suppliers/supplier_parts' , { 'subtab':'suppliers.supplier_parts.low',  } )" title="{t}Number active parts with low stock{/t}">{$account->get('Active Suppliers Parts Stock Low Number')}</span></div>
        <div>
            <span class="Active_Parts_Stock_Low_Stock_Value_Minify " title="{t}Stock value{/t}">{$account->get('Active Suppliers Parts Stock Low Stock Value Minify')}</span>

            | <i class="fal fa-fw fa-clipboard" title="{t}Parts within a purchase order{/t}"></i> <span class="Active_Parts_Stock_Low_Deliveries_Number " title="{t}Parts within a purchase order{/t}">{$account->get('Active Suppliers Parts Stock Low Deliveries Number')}</span>

        </div>
    </li>

    <li class="flex-item ">
        <span>{t}Critical{/t}</span>
        <div class="title"><span class="Active_Parts_Stock_Critical_Number button"  onclick="change_view('suppliers/supplier_parts' , { 'subtab':'suppliers.supplier_parts.critical',  } )"  title="{t}Number active parts with critical stock{/t}">{$account->get('Active Suppliers Parts Stock Critical Number')}</span></div>
        <div>
            <span class="Active_Parts_Stock_Critical_Stock_Value_Minify " title="{t}Stock value{/t}">{$account->get('Active Suppliers Parts Stock Critical Stock Value Minify')}</span>

            | <i class="fal fa-fw fa-clipboard" title="{t}Parts within a purchase order{/t}"></i> <span class="Active_Parts_Stock_Critical_Deliveries_Number " title="{t}Parts within a purchase order{/t}">{$account->get('Active Suppliers Parts Stock Critical Deliveries Number')}</span>

        </div>
    </li>

    <li class="flex-item ">
        <span>{t}Out of stock{/t}</span>
        <div class="title"><span class="Active_Parts_Stock_Zero_Number button"  onclick="change_view('suppliers/supplier_parts' , { 'subtab':'suppliers.supplier_parts.out_of_stock',  } )"  title="{t}Number of out of stock parts{/t}">{$account->get('Active Suppliers Parts Stock Zero Number')}</span></div>
        <div>
             <i class="fal fa-fw fa-clipboard" title="{t}Parts within a purchase order{/t}"></i> <span class="Active_Parts_Stock_Zero_Deliveries_Number " title="{t}Parts within a purchase order{/t}">{$account->get('Active Suppliers Parts Stock Zero Deliveries Number')}</span>

        </div>
    </li>
</ul>

