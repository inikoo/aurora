<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 24 July 2018 at 11:00:59 GMT+8, Kuala Lumput, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/


$tab     = 'salesmen';
$ar_file = 'ar_hr_tables.php';
$tipo    = 'salesmen';

$default = $user->get_tab_defaults($tab);

$table_views = array(
    'overview'      => array(
        'label' => _('Overview'),
        'title' => _('Overview')
    ),
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
    'title'     => _('New account'),
    'reference' => "salesmen/new"
);


$smarty->assign('tipo', $tipo);
$smarty->assign('title', _('Employees'));

include('utils/get_table_html.php');


?>
