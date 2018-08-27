<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 27 August 2018 at 20:11:49 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/


$parameters = array(
    'parent'     => $state['parent'],
    'parent_key' => $state['parent_key'],

);


$ar_file = 'ar_products_tables.php';

$tab     = 'back_to_stock_notification_request.products';
$tipo    = 'back_to_stock_notification_request.products';




$default = $user->get_tab_defaults($tab);


$table_views = array(
    'overview' => array(
        'label' => _('Overview'),
        'title' => _('Overview')
    )

);

$table_filters = array(
    'code'  => array(
        'label' => _('Code'),
        'title' => _('Code')
    )

);


$table_buttons = array();

include 'utils/get_table_html.php';


?>
