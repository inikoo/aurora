<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 18 October 2018 at 13:27:55 GMT+8, Kuala Lumpur,  Malaysia

 Copyright (c) 2018, Inikoo

 Version 3.0
*/

/**
 * @param        $user   \User
 * @param        $smarty \Smarty
 * @param string $parent
 * @param string $display_device_version
 *
 * @return mixed
 * @throws \SmartyException
 */
function get_dashboard_inventory($user, $smarty, $parent = '', $display_device_version = 'desktop') {


    include_once 'utils/date_functions.php';

    $smarty->assign('user', $user);

    if ($parent != '') {

        $object = get_object('Warehouse', $parent);
        $title  = sprintf(_('Warehouse %d'), $object->get('Name'));

    } else {
        $object = get_object('Account',1);
        $object->load_acc_data();
        $title = _('Warehouse');
    }


    $smarty->assign('store_title', $title);
    $smarty->assign('object', $object);
    $smarty->assign('parent', $parent);

    
    if ($display_device_version == 'mobile') {
        return $smarty->fetch('dashboard/inventory.mobile.dbard.tpl');
    } else {
        return $smarty->fetch('dashboard/inventory.dbard.tpl');
    }
}



