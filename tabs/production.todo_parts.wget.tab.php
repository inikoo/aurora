<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 13 October 2016 at 00:04:05 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

$tab     = 'production.todo_parts.wget';
$ar_file = 'ar_production_tables.php';
$tipo    = 'todo_parts';

$default = $user->get_tab_defaults($tab);

$table_views = array();

$table_filters = array(
    'reference' => array('label' => _('Reference')),
);

$parameters = array(
    'parent'     => $state['parent'],
    'parent_key' => $state['parent_key']
);

include 'utils/get_table_html.php';

