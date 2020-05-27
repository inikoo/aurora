{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  05 March 2020  00:51::11  +0800, Kuala Lumpur,  Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}


<div class="orders_control_panel in_warehouse {$table_top_lower_template_parameters.parent}_{$table_top_lower_template_parameters.parent_key}"
     data-parent="{$table_top_lower_template_parameters.parent}" data-parent_key="{$table_top_lower_template_parameters.parent_key}" style="padding:10px;padding-left:15px;border-bottom:1px solid #ccc;display:flex;padding-bottom: 0px;height: 30px">
    <div style="float:left;text-align: left;">

        <i class="far fa-square button select_all_orders"></i>
    </div>

    <div style="float:left;margin-left:20px" class="orders_operations ">

        <span class="orders_picking_sheets orders_op orders_pdf super_discreet" data-type="orders_picking_sheets"> {t}Picking sheets{/t}
            <i data-source="ui_box"  data-type="picking_aid" class="fal fa-fw fa-clipboard-list-check"></i>
            <i data-source="ui_box" data-type="picking_aid_with_labels" class="fal fa-fw fa-pager"></i> </span>
    </div>







    <div style="float:left;margin-left:50px" class="orders_operations hide">x

        <span class="process_orders_in_warehouse super_discreet" data-type="process_orders_in_warehouse"> {t}Process orders in bulk{/t} <i class="fal fa-layer-group"></i>

    </div>

    {include file='control_order_operation_progress_bar.tpl'  }

</div>


<div class="hide">

    {include file='input_picking_sheet.tpl'  }


</div>




