<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 10 October 2015 at 18:23:56 CEST, Mijas Costa, Spain
 Copyright (c) 2015, Inikoo

 Version 3

*/

$tab     = 'product.history';
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

?>
