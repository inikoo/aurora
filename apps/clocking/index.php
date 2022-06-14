<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 13 Jun 2022 18:28:56 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 3.0
 */

/** @var Smarty $smarty */
/** @var integer $clocking_machine_key */


require_once 'common.php';
if(!$clocking_machine_key){
    $smarty->display('clocking/login.tpl');
    exit;
}

$smarty->display('clocking/clocking.tpl');