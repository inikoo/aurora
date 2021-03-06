<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 20 November 2016 at 12:31:50 GMT+8, Cyberjaya, Malaysia

 Copyright (c) 2016, Inikoo

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
function get_dashboard_pending_orders($db, $account, $user, $smarty, $parent, $currency, $display_device_version = 'desktop') {

    include_once 'utils/date_functions.php';

    include_once 'utils/prepare_smarty_for_dashboard.php';

    $smarty=prepare_smarty_for_dashboard($db, $account, $user, $smarty, $parent, $currency);

    if ($display_device_version == 'mobile') {
        return $smarty->fetch('dashboard/pending_orders.mobile.dbard.tpl');
    } else {
        return $smarty->fetch('dashboard/pending_orders.dbard.tpl');
    }
}


