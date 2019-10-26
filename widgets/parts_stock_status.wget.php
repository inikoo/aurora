<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 18 October 2018 at 13:46:53 GMT+8, Kuala Lumpur,  Malaysia

 Copyright (c) 2018, Inikoo

 Version 3.0
*/


function get_dashboard_parts_stock_status( $user, $smarty, $parent = '', $display_device_version = 'desktop') {


    include_once 'utils/date_functions.php';

    $smarty->assign('user', $user);


    if ($parent != '') {

        $object = get_object('Warehouse',$parent);

        //  print_r($_object);
        $title = sprintf(_('Warehouse %d'),$object->get('Name'));

    } else {
        $object = new Account();
        $object->load_acc_data();
        $title = _('Warehouse');
    }



    $smarty->assign('store_title', $title);
    $smarty->assign('object', $object);
    $smarty->assign('parent', $parent);


    if ($display_device_version == 'mobile') {
        return $smarty->fetch('dashboard/parts_stock_status.mobile.dbard.tpl');
    } else {
        return $smarty->fetch('dashboard/parts_stock_status.dbard.tpl');
    }
}



