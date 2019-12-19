<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  19 December 2019  14:27::00  +0800, Kuala Lumpur, Malaysia

 Copyright (c) 2019, Inikoo

 Version 3.0
*/

/**
 * @param        $db      \PDO
 * @param        $account \Account
 * @param        $user    \User
 * @param        $smarty  \Smarty
 * @param string $parent
 * @param        $currency
 * @param string $display_device_version
 *
 * @return mixed
 */
function get_dashboard_dispatching_times($db, $account, $user, $smarty, $parent, $display_device_version = 'desktop') {

    include_once 'utils/date_functions.php';
    include_once 'utils/prepare_smarty_for_dashboard.php';

    $smarty=prepare_smarty_for_dashboard($db, $account, $user, $smarty, $parent, '');

    if ($display_device_version == 'mobile') {
        return $smarty->fetch('dashboard/dispatching_times.mobile.dbard.tpl');
    } else {
        return $smarty->fetch('dashboard/dispatching_times.dbard.tpl');
    }
}



