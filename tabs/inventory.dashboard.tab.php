<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 13 January 2017 at 15:45:41 GMT, Sheffield, UK

 Copyright (c) 2017, Inikoo

 Version 3.0
*/


$account->load_acc_data();
//$account->update_parts_data();


include_once 'widgets/inventory_alerts.wget.php';

$html = '';


$html .= '<div class="widget_container">'.get_inventory_alerts( $db, $account, $user, $smarty).'</div>';

$html .= '<div id="widget_details" class="hide" style="clear:both;font-size:90%;border-top:1px solid #ccc"><div>';


?>
