<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:20 July 2016 at 22:22:07 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/

$tab     = 'payments';
$ar_file = 'ar_payments_tables.php';
$tipo    = 'payments';

$default = $user->get_tab_defaults($tab);


$table_views = array(
    'overview' => array(
        'label' => _('Overview'),
        'title' => _('Overview')
    ),

);

$table_filters = array(
    'reference' => array(
        'label' => _('Reference'),
        'title' => _('Reference')
    ),

);

$parameters = array(
    'parent'     => $state['object'],
    'parent_key' => $state['key'],

);


$smarty->assign(
    'js_code', 'js/injections/edit_payments.'.(_DEVEL ? '' : 'min.').'js'
);

$smarty->assign('table_top_template', 'edit_payments_dialogs.tpl');

include('utils/get_table_html.php');


?>
