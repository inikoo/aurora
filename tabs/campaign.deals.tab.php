<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 10 May 2016 at 14:50:27 GMT+8, Kual Lumput Malaydia
 Copyright (c) 2016, Inikoo

 Version 3

*/


$tab     = 'deals';
$ar_file = 'ar_marketing_tables.php';
$tipo    = 'deals';

$default = $user->get_tab_defaults($tab);


$table_views = array(
    'overview' => array(
        'label' => _('Overview'),
        'title' => _('Overview')
    ),

);

$table_filters = array(

    'name' => array('label' => _('Name')),

);

$parameters = array(
    'parent'     => $state['object'],
    'parent_key' => $state['key'],

);


$table_buttons   = array();
$table_buttons[] = array(
    'icon'      => 'plus',
    'title'     => _('New offer'),
    'reference' => "campaigns/".$state['parent_key']."/".$state['key']."/new"
);
$smarty->assign('table_buttons', $table_buttons);


include 'utils/get_table_html.php';


?>
