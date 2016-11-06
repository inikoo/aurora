<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 31 March 2016 at 00:23:56 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/


$tab     = 'hr.uploads';
$ar_file = 'ar_upload_tables.php';
$tipo    = 'uploads';

$default = $user->get_tab_defaults($tab);

$table_views = array();

$table_filters = array();


$parameters = array(
    'parent'     => 'employees',
    'parent_key' => $state['parent_key'],

);

$smarty->assign('upload_objects', 'employees');

//$smarty->assign('title',_('Uploads'));
//$smarty->assign('view_position', _('Employee uploads'));

include('utils/get_table_html.php');

?>
