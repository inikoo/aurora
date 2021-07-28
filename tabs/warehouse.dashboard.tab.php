<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 5 February 2017 at 18:38:11 GMT+8, Cyberjaya , Malaysia
 Copyright (c) 2017, Inikoo

 Version 3.0
*/
/** @var array $state */
/** @var \Account $account */
/** @var \Smarty $smarty */
/** @var PDO $db */
/** @var \User $user */


$account->load_acc_data();

/**
 * @var $warehouse \Warehouse
 */
$warehouse = $state['_object'];

// todo update real time when qty change
$warehouse->update_stock_amount();
$warehouse->update_warehouse_aggregations();
$warehouse->update_warehouse_paid_ordered_parts();
$warehouse->update_warehouse_part_locations_to_replenish();
$warehouse->update_warehouse_aggregations();

$sql           = "select `Picking Pipeline Key` from   `Picking Pipeline Dimension` where `Picking Pipeline Warehouse Key`=?";
$stmt = $db->prepare($sql);
$stmt->execute(
    array(
        $warehouse->id
    )
);
while ($row = $stmt->fetch()) {
    /** @var $pipeline \Picking_Pipeline */
    $pipeline=get_object('Picking_Pipeline',$row['Picking Pipeline Key']);
    $pipeline->update_pipeline_part_locations_to_replenish();
}

include_once 'widgets/warehouse_alerts.wget.php';
$smarty->assign('warehouse', $state['_object']);

try {
    $html = $smarty->fetch('warehouse.kpi.tpl');
    $html .= '<div class="widget_container">'.get_warehouse_alerts($db,$warehouse,$smarty).'</div>';
    $html .= '<div id="widget_details" class="hide" style="clear:both;font-size:90%;border-top:1px solid #ccc"><div>';

} catch (Exception $e) {
}


