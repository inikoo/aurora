<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  25 January 2017 at 09:50:49 GMT, Plane London - Kuala Lumpur
 Copyright (c) 2017, Inikoo

 Version 3

*/
$tab     = 'pending_delivery_notes';
$ar_file = 'ar_orders_tables.php';
$tipo    = 'pending_delivery_notes';

$default = $user->get_tab_defaults($tab);

$table_views = array();

$table_filters = array(
    'customer' => array(
        'label' => _('Customer')
            ),
    'number'   => array(
        'label' => _('Number')
            ),

);

$parameters = array(
    'parent'     => $state['parent'],
    'parent_key' => $state['parent_key'],
);


include 'utils/get_table_html.php';


?>
