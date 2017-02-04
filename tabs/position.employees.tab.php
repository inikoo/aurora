<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 4 February 2017 at 14:58:58 GMT+8, Cyberjaya, Malysia
 Copyright (c) 2016, Inikoo

 Version 3

*/


$tab     = 'position.employees';
$ar_file = 'ar_hr_tables.php';
$tipo    = 'employees';

$default = $user->get_tab_defaults($tab);

$table_views = array(
    'overview'      => array(
        'label' => _('Overview'),
        'title' => _('Overview')
    ),
    'personal_info' => array(
        'label' => _('Personal information'),
        'title' => _('Personal information')
    ),
    'employment'    => array(
        'label' => _('Employment'),
        'title' => _('Employment')
    ),
    'system_roles'  => array('label' => _('System roles')),
    'system_user'   => array('label' => _('System user'))

);

$table_filters = array(
    'name' => array(
        'label' => _('Name'),
        'title' => _('Employee name')
    ),

);

$parameters = array(
    'parent'     => 'position',
    'parent_key' => $state['key'],

);


$table_buttons   = array();

$smarty->assign('table_buttons', $table_buttons);


$smarty->assign('tipo', $tipo);



include('utils/get_table_html.php');


?>
