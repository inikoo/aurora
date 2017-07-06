<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 5 July 2017 at 15:57:46 GMT+8, Cyerberjaya, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3

*/

$tab     = 'register.email_blueprints';
$ar_file = 'ar_email_template_tables.php';
$tipo    = 'email_blueprints';

$default = $user->get_tab_defaults($tab);

$table_views = array();

$table_filters = array(
    'name' => array('label' => _('Name')),
);

$parameters = array(
    'parent'     => 'Welcome',
    'parent_key' => $state['key'],

);





include 'utils/get_table_html.php';

?>
