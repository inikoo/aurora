<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 6 December 2015 at 19:18:17 GMT Sheffield UK
 Copyright (c) 2015, Inikoo

 Version 3

*/

$tab     = 'timesheets.days';
$ar_file = 'ar_hr_tables.php';
$tipo    = 'days';


$default = $user->get_tab_defaults($tab);


$table_views = array();

$table_filters = array();

$parameters = array(
    'parent'     => $state['parent'],
    'parent_key' => $state['parent_key'],
    'group_by'   => 'day',

);


$smarty->assign(
    'js_code', 'js/injections/timesheets_days.'.(_DEVEL ? '' : 'min.').'js'
);

include 'utils/get_table_html.php';

?>
