<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 13 May 2016 at 14:12:24 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3
*/

/** @var User $user */
/** @var Smarty $smarty */
/** @var array $state */

$ar_file = 'ar_fulfilment_tables.php';
$tipo    = 'customer.delivery.items';


switch($state['_object']->get('Fulfilment Delivery State')){
    case 'InProcess':
        $tab     = 'supplier.order.items_in_process';

        $table_views = array(
            'ordering' => array('label' => _('Ordering cartons'),),
        );
        break;
    default:
        $tab     = 'supplier.order.items';

        $table_views = array(
            'overview' => array('label' => _('Overview')),
        );
        break;
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


$table_buttons   = array();


$smarty->assign('table_buttons', $table_buttons);


include 'utils/get_table_html.php';

