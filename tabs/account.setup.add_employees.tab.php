<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 9 March 2016 at 17:23:23 GMT+8, Yiwu, China
 Copyright (c) 2015, Inikoo

 Version 3

*/

include_once('class.User.php');

$user = new User('Administrator');

$tab     = 'employees';
$ar_file = 'ar_setup.php';
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
    'parent'     => 'account',
    'parent_key' => 1,

);


$table_buttons   = array();
$table_buttons[] = array(
    'icon'      => 'plus',
    'title'     => _('New employee'),
    'reference' => "account/setup/add_employee"
);
$smarty->assign('table_buttons', $table_buttons);

$smarty->assign('tipo', $tipo);
$smarty->assign(
    'upload_file', array(
        'tipo'       => 'upload_objects',
        'parent'     => $state['parent'],
        'parent_key' => $state['parent_key'],
        'object'     => 'employee',

        'label' => _('Upload employees')

    )
);


include('utils/get_table_html.php');


?>
