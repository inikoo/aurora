<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 15 June 2021 21:11 GMT+8, Cyberjaya , Malaysia
 Copyright (c) 2017, Inikoo

 Version 3.0
*/
/** @var User $user */
/** @var \Smarty $smarty */
/** @var array $state */
/** @var \Account $account */

$account->load_acc_data();

/**
 * @var $warehouse \Warehouse
 */
$warehouse=$state['_object'];


$html='';

