<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 23 Jul 2021 19:41:46 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 3.0
 */


/** @var \User $user */
/** @var \Smarty $smarty */
/** @var array $state */
include_once 'helpers/history/history_tab_snippet.php';
$tab     = 'picking_pipeline.history';
$ar_file = 'ar_history_tables.php';
$tipo    = 'object_history';
$default = $user->get_tab_defaults($tab);
list($table_views,$table_filters,$parameters,$smarty)=prepare_history_tab($state,$smarty);
include('utils/get_table_html.php');


