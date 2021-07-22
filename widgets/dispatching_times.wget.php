<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  19 December 2019  14:27::00  +0800, Kuala Lumpur, Malaysia

 Copyright (c) 2019, Inikoo

 Version 3.0
*/


function get_dashboard_dispatching_times($db, $account, $user, $smarty, $parent) {

    include_once 'utils/date_functions.php';
    include_once 'utils/prepare_smarty_for_dashboard.php';

    $smarty=prepare_smarty_for_dashboard($db, $account, $user, $smarty, $parent, '');

    return $smarty->fetch('dashboard/dispatching_times.dbard.tpl');

}



