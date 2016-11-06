<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 6 December 2015 at 19:13:10 GMT Sheffield UK
 Copyright (c) 2015, Inikoo

 Version 3

*/

$tab     = 'timesheets.weeks';
$ar_file = 'ar_hr_tables.php';
$tipo    = 'weeks';


$default = $user->get_tab_defaults($tab);


$table_views = array();

$table_filters = array();

$parameters = array(
    'parent'     => $state['parent'],
    'parent_key' => $state['parent_key'],
    'group_by'   => 'week',

);


//$smarty->assign('title',   sprintf(_("Employee's calendar %s"), date('Y',$default['year'])   )    );


include('utils/get_table_html.php');

?>
