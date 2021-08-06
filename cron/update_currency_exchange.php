<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 3 April 2019 at 15:58:51 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2019, Inikoo

 Version 3

*/

require_once __DIR__.'/cron_common.php';
include_once 'utils/new_fork.php';

new_housekeeping_fork(
    'au_housekeeping', array(
    'type'        => 'update_currency_exchange',
    'currency_from' => 'EUR',
    'currency_to'   => 'GBP',
), $account->get('Account Code'), $db
);

