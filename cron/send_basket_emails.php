<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 10 Jun 2022 09:05:54 Central European Summer Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 3.0
 */

require_once __DIR__.'/cron_common.php';
include_once 'utils/send_baskets_emails.php';

/** @var PDO $db */


send_basket_first_email($db);

send_basket_second_email($db);

send_basket_third_email($db);