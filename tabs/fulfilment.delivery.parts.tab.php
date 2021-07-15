<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 10 Jul 2021 02:21:43 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 3.0
 */

/** @var array $state */
/** @var \User $user */
/** @var \Smarty $smarty */


$ar_file = 'ar_suppliers_tables.php';
$tipo    = 'supplier.order.items';


if ($state['_object']->get('Fulfilment Delivery Type') == 'Part') {
    switch ($state['_object']->get('Customer Delivery State')) {
        case 'InProcess':
            $tab         = 'fulfilment.delivery.parts_in_process';
            $table_views = array('overview' => array('label' => _('Overview')),);
            $table_views = array('overview' => array('label' => _('Overview')),);
            break;
        default:
            $tab = 'fulfilment.delivery.parts';

            $table_views = array('overview' => array('label' => _('Overview')),);
            break;
    }
} else {
    switch ($state['_object']->get('Customer Delivery State')) {
        case 'InProcess':
            $tab         = 'fulfilment.delivery.assets_in_process';
            $table_views = array('overview' => array('label' => _('Overview')),);
            break;
        default:
            $tab = 'fulfilment.delivery.assets';

            $table_views = array('overview' => array('label' => _('Overview')),);
            break;
    }
}


$default = $user->get_tab_defaults($tab);

$table_filters = array(
    'code' => array('label' => _('Code')),
    'name' => array('label' => _('Name')),
);

$parameters = array(
    'parent'     => $state['object'],
    'parent_key' => $state['key'],

);

$table_buttons = array();
$smarty->assign('table_buttons', $table_buttons);
include 'utils/get_table_html.php';

