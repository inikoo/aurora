<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 25 October 2016 at 13:38:52 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/
/** @var User $user */
/** @var Smarty $smarty */
/** @var \Account $account */
/** @var array $state */

$tab     = 'customer.invoices';
$ar_file = 'ar_accounting_tables.php';
$tipo    = 'invoices';

$default = $user->get_tab_defaults($tab);

$table_views = array();


$table_filters = array(
    'number' => array(
        'label' => _('Number'),
        'title' => _('Invoice number')
    ),

);

$parameters = array(
    'parent'     => $state['object'],
    'parent_key' => $state['key'],
    'version'    => 'v2'

);

include('utils/get_table_html.php');

