<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 2 December 2017 at 12:21:21 GMT+7 , Bankok, Thailand
 Copyright (c) 2015, Inikoo

 Version 3

*/

$tab     = 'payments';
$ar_file = 'ar_payments_tables.php';
$tipo    = 'payments';




$default = $user->get_tab_defaults($tab);


$table_views = array(
    'overview' => array(
        'label' => _('Overview'),
        'title' => _('Overview')
    ),

);

$table_filters = array(
    'reference' => array(
        'label' => _('Reference'),
        'title' => _('Reference')
    ),

);

$parameters = array(
    'parent'     => $state['parent'],
    'parent_key' => $state['parent_key'],

);


include('utils/get_table_html.php');


?>
