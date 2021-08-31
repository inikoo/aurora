<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 31 Aug 2021 20:38:42 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 3.0
 */

/** @var array $state */
/** @var User $user */
/** @var Smarty $smarty */

$tab     = 'fulfilment.deliveries';
$ar_file = 'ar_fulfilment_tables.php';
$tipo    = 'deliveries';

$default = $user->get_tab_defaults($tab);

$table_views = array();

$table_filters = array(
    'number' => array('label' => _('Number')),
);


$parameters = array(
    'parent'     => 'warehouse',
    'parent_key' => $state['key'],

);


$table_buttons   = [];


$smarty->assign('table_buttons', $table_buttons);

include 'utils/get_table_html.php';


