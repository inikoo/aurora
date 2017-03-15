<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 18 November 2015 at 20:42:58 GMT, Sheffield, UK
 Copyright (c) 2015, Inikoo

 Version 3

*/


$tab     = 'contractors';
$ar_file = 'ar_hr_tables.php';
$tipo    = 'contractors';

$default = $user->get_tab_defaults($tab);

$table_views = array(
    'overview'      => array(
        'label' => _('Overview'),
        'title' => _('Overview')
    ),
    'personal_info' => array('label' => _('Contact information')),
    'employment'    => array('label' => _('Contract')),
    'system_roles'  => array('label' => _('System roles')),
    'system_user'   => array('label' => _('System user'))

);

$table_filters = array(
    'name' => array(
        'label' => _('Name'),
        'title' => _('Contractor name')
    ),

);

$parameters = array(
    'parent'     => 'account',
    'parent_key' => 1,

);


$table_buttons   = array();
$table_buttons[] = array(
    'icon'      => 'plus',
    'title'     => _('New contractor'),
    'reference' => "contractor/new"
);



$smarty->assign('table_buttons', $table_buttons);

$smarty->assign('tipo', $tipo);


$smarty->assign('title', _('Contractors'));

include('utils/get_table_html.php');


?>
