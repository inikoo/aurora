<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 7 March 2016 at 14:48:21 GMT+8, Yiwu, China

 Copyright (c) 2016, Inikoo

 Version 3.0
*/


$account    = new Account();
$setup_data = $account->get('Setup Metadata');

$done = true;
foreach ($setup_data['steps'] as $step_code => $step_data) {
    if (!$step_data['setup']) {
        $done = false;
        break;
    }
}

if ($done) {
    $smarty->assign('title', _('Account set up completed'));

    $html = $smarty->fetch('setup_completed.tpl');
} else {

    $html = $smarty->fetch('setup_overview.tpl');
}


?>
