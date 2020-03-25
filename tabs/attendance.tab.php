<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  25 March 2020  16:03::29  +0800, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/

$tab     = 'attendance';
$ar_file = 'ar_attendance_tables.php';
$tipo    = 'attendance';


$default = $user->get_tab_defaults($tab);


$table_views = array(
    'overview'    => array('label' => _('Overview')),
);

$table_filters = array(
    'alias' => array(
        'label' => _('Alias'),
        'title' => _('Employee alias')
    ),
    'name'  => array(
        'label' => _('Name'),
        'title' => _('Employee name')
    ),

);

$parameters = array(
    'parent'     => $state['parent'],
    'parent_key' => $state['parent_key'],


);

include 'utils/get_table_html.php';


