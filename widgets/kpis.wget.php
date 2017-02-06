<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 6 February 2017 at 12:23:16 GMT+8, Kuala Lumpur, , Malaysia

 Copyright (c) 2017, Inikoo

 Version 3.0
*/


function get_dashboard_kpis($db, $account, $user, $smarty, $parent = '', $currency, $display_device_version = 'desktop') {


    include_once 'utils/date_functions.php';


    include_once 'class.Warehouse.php';


    $smarty->assign('user', $user);



    $warehouse=new Warehouse(1);
    $smarty->assign('warehouse', $warehouse);


    if ($display_device_version == 'mobile') {
        return $smarty->fetch('dashboard/kpis.mobile.dbard.tpl');
    } else {
        return $smarty->fetch('dashboard/kpis.dbard.tpl');
    }
}


?>
