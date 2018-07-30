<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 30 July 2018 at 22:11:29 GMT+8, Kuala Lumpur, Malaysis
 Copyright (c) 2016, Inikoo

 Version 3

*/


$tab     = 'timeseries_types';
$ar_file = 'ar_account_tables.php';
$tipo    = 'timeseries_types';

$default = $user->get_tab_defaults($tab);


$table_views = array();

$table_filters = array(
    'code' => array(
        'label' => _('Type'),
        'title' => _('Timeseries Type')
    ),

);

$parameters = array(
    'parent'     => '',
    'parent_key' => '',
);


include('utils/get_table_html.php');


?>
