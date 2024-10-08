<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 17 Jul 2021 05:00:37 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 3.0
 */

/** @var \User $user */
/** @var \Smarty $smarty */
/** @var array $state */



include_once 'helpers/attachments/attachments_tab_snippet.php';
$tab     = 'order.attachments';
$ar_file = 'ar_attachments_tables.php';
$tipo    = 'attachments';
$default = $user->get_tab_defaults($tab);
list($table_views,$table_filters,$parameters,$smarty)=prepare_attachments_tab($state,$smarty,$state['request']);
include('utils/get_table_html.php');

