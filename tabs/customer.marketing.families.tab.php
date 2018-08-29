<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 29 August 2018 at 16:12:04 GMT+8, Kuala Lumpur Malysia
 Copyright (c) 2018, Inikoo

 Version 3

*/

$tab     = 'customer.marketing.families';
$ar_file = 'ar_customers_tables.php';
$tipo    = 'families';

$default = $user->get_tab_defaults($tab);


$table_views = array(
    'overview' => array(
        'label' => _('Overview')),


);

$table_filters = array(
    'code' => array(
        'label' => _('Code'),
        'title' => _('Category code')
    ),
  

);

$parameters = array(
    'parent'     => $state['object'],
    'parent_key' => $state['key'],

);


include('utils/get_table_html.php');


?>
