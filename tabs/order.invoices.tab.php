<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  20 October 2015 at 16:06:04 BST, London UK
 Copyright (c) 2015, Inikoo

 Version 3

*/
/** @var User $user */
/** @var Smarty $smarty */
/** @var \Account $account */
/** @var array $state */

$tab     = 'order.invoices';
$ar_file = 'ar_accounting_tables.php';
$tipo    = 'invoices';

$default = $user->get_tab_defaults($tab);

$table_views = array();

$table_filters = array();

$parameters = array(
    'parent'     => $state['object'],
    'parent_key' => $state['key'],
    'version'    => 'v2',
);


include('utils/get_table_html.php');

