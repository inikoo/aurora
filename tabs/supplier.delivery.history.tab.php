<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 12 June 2020  01:23::13  +0800, Kuala Lumpur, Malaysia
 Copyright (c) 2020, Inikoo

 Version 3

*/

$tab     = 'supplier.delivery.history';
$ar_file = 'ar_history_tables.php';
$tipo    = 'object_history';

$default = $user->get_tab_defaults($tab);

$table_views = array();

$table_filters = array(
    'note' => array(
        'label' => _('Notes'),
        'title' => _('Notes')
    ),
);

$parameters = array(
    'parent'     => $state['object'],
    'parent_key' => $state['key'],

);

include('utils/get_table_html.php');


