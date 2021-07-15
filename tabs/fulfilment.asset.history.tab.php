<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 14 Jul 2021 19:32:21 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 3.0
 */

/** @var \User $user */
/** @var \Smarty $smarty */
/** @var array $state */
include_once 'helpers/history/history_tab_snippet.php';
$tab     = 'fulfilment.asset.history';
$ar_file = 'ar_history_tables.php';
$tipo    = 'object_history';
$default = $user->get_tab_defaults($tab);
list($table_views,$table_filters,$parameters,$smarty)=prepare_history_tab($state,$smarty);
include('utils/get_table_html.php');


