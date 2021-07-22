<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 20 November 2016 at 12:31:50 GMT+8, Cyberjaya, Malaysia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/


function get_dashboard_pending_orders($db, $account, $user, $smarty, $parent, $currency) {

    include_once 'utils/date_functions.php';

    include_once 'utils/prepare_smarty_for_dashboard.php';

    $smarty=prepare_smarty_for_dashboard($db, $account, $user, $smarty, $parent, $currency);

    return $smarty->fetch('dashboard/pending_orders.dbard.tpl');

}


