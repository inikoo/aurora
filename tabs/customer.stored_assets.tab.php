<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 02 Sep 2021 01:27:40 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 3.0
 */


/** @var array $state */
/** @var User $user */
/** @var Smarty $smarty */

/**
 * @var $customer Customer
 */
$customer = $state['_object'];

$ar_file       = 'ar_fulfilment_tables.php';
$table_buttons = [];

if ($state['_object']->get('Customer Fulfilment Type') == 'Dropshipping') {

    // todo
    $html='';
    /*
    $tab         = 'customer.assets';
    $tipo        = 'customer.assets';
    $table_views = array('overview' => array('label' => _('Overview')),);

    $table_filters = array(
        'reference'   => array('label' => _('Reference')),
        'description' => array('label' => _('Description')),
    );
    */
} else {
    $tab         = 'fulfilment.customer.assets';
    $tipo        = 'customer_assets';
    $table_views = array('overview' => array('label' => _('Overview')),);


    $table_filters = array(
        'id'        => array('label' => _('Id/Reference')),
    );
}


$smarty->assign('table_buttons', $table_buttons);

$default = $user->get_tab_defaults($tab);


$parameters = array(
    'parent'     => $state['object'],
    'parent_key' => $state['key'],

);


include 'utils/get_table_html.php';

