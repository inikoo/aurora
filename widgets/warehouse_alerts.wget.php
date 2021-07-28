<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 5 February 2017 at 21:48:12 GMT+8, Cyberjaya, Kuala Lumpur
 Copyright (c) 2017, Inikoo

 Version 3.0
*/
include_once 'helpers/widgets/widgets_functions.php';

function get_warehouse_alerts($db, $warehouse, $smarty): string {


    $html = '';


    $data = get_widget_data(
        $warehouse->get('Warehouse Part Locations Errors'), $warehouse->get('Warehouse Part Locations'), $warehouse->get('Warehouse Tolerable Percentage Part Locations Errors'), $warehouse->get('Warehouse Max Percentage Part Locations Errors')

    );
    if ($data['ok']) {


        $smarty->assign('data', $data);
        $html .= $smarty->fetch(
            'dashboard/warehouse.locations_with_errors.dbard.tpl'
        );
    }


    $data = get_widget_data(
        $warehouse->get('Warehouse Part Location Unknown Locations'), $warehouse->get('Warehouse Number Parts'), $warehouse->get('Warehouse Tolerable Percentage Part Location Unknown Locations'),
        $warehouse->get('Warehouse Max Percentage Part Location Unknown Locations'),

    );
    if ($data['ok']) {


        $smarty->assign('data', $data);
        $html .= $smarty->fetch(
            'dashboard/warehouse.parts_with_unknown_location.dbard.tpl'
        );
    }


    $data = get_widget_data(
        $warehouse->get('Warehouse Paid Ordered Parts To Replenish'), $warehouse->get('Warehouse Paid Ordered Parts'), $warehouse->get('Warehouse Tolerable Percentage Paid Ordered Parts To Replenish'),
        $warehouse->get('Warehouse Max Percentage Paid Ordered Parts To Replenish')

    );
    if ($data['ok']) {


        $smarty->assign('data', $data);
        $html .= $smarty->fetch(
            'dashboard/warehouse.parts_to_replenish_urgent.dbard.tpl'
        );
    }


    $data = get_widget_data(
        $warehouse->get('Warehouse Part Locations To Replenish'), $warehouse->get('Warehouse Replenishable Part Locations'), $warehouse->get('Warehouse Tolerable Percentage Part Locations To Replenish'),
        $warehouse->get('Warehouse Max Percentage Part Locations To Replenish')

    );
    if ($data['ok']) {


        $smarty->assign('data', $data);
        $html .= $smarty->fetch('dashboard/warehouse.location_parts_to_replenish.dbard.tpl');
    }


    $data = get_widget_data(
        $warehouse->get('Warehouse Paid Ordered Parts To Replenish External Warehouse'), $warehouse->get('Warehouse Paid Ordered Parts'), 1, 1

    );
    if ($data['ok']) {
        $smarty->assign('data', $data);
        $html .= $smarty->fetch(
            'dashboard/warehouse.parts_to_replenish_external_warehouse.dbard.tpl'
        );
    }

    $sql  = "select `Picking Pipeline Key` from `Picking Pipeline Dimension`  where `Picking Pipeline Warehouse Key`=? ";
    $stmt = $db->prepare($sql);
    $stmt->execute(
        array(
            $warehouse->id
        )
    );
    while ($row = $stmt->fetch()) {
        $pipeline = get_object('Picking_Pipeline', $row['Picking Pipeline Key']);
        $data     = get_widget_data(
            $pipeline->get('Picking Pipeline Part Locations To Replenish'), $pipeline->get('Picking Pipeline Replenishable Part Locations'), $warehouse->get('Warehouse Tolerable Percentage Part Locations To Replenish'),
            $warehouse->get('Warehouse Max Percentage Part Locations To Replenish')

        );
        if ($data['ok']) {
            $smarty->assign('pipeline', $pipeline);
            $smarty->assign('data', $data);
            $html .= $smarty->fetch('dashboard/warehouse.parts_to_replenish_pipeline.dbard.tpl');
        }

    }


    return $html;


}

