<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 8 December 2015 at 12:26:22 GMT Sheffield UK
 Copyright (c) 2015, Inikoo

 Version 3

*/

$tab     = 'fire';
$ar_file = 'ar_fire_tables.php';
$tipo    = 'fire';


$default = $user->get_tab_defaults($tab);


$table_views = array();

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

$smarty->assign('js_code', 'js/injections/fire.'.(_DEVEL ? '' : 'min.').'js');

include 'utils/get_table_html.php';

?>
