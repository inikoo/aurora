<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 13 January 2017 at 15:45:41 GMT, Sheffield, UK

 Copyright (c) 2017, Inikoo

 Version 3.0
*/

$account->load_acc_data();
$account->update_parts_data();
$smarty->assign('show_widget', $state['extra']);

include_once 'widgets/inventory_alerts.wget.php';

$html = '';
$html .= '<div class="widget_container">'.get_inventory_alerts($db, $account, $user, $smarty).'</div>';
$html .= '<div id="widget_details" class="hide" style="clear:both;font-size:90%;border-top:1px solid #ccc"><div>';


switch ($state['extra']) {
    case 'barcode_errors':
    case 'barcodes_errors':
        $html .= "<script>get_widget_details($('#inventory_parts_barcode_errors_wget'),'inventory.parts_barcode_errors.wget',{ parent: 'account','parent_key':1})</script>";
        break;
    case 'missing_sko_barcodes':
        $html .= "<script>get_widget_details($('#inventory_parts_no_sko_barcode_wget'),'inventory.parts_no_sko_barcode.wget',{ parent: 'account','parent_key':1})</script>";
        break;
    case 'weight_errors':
        $html .= "<script>get_widget_details($('#inventory_parts_weight_errors_wget'),'inventory.parts_weight_errors.wget',{ parent: 'account','parent_key':1})</script>";
        break;
    default:
        break;
}



