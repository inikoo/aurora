<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 29 July 2017 at 11:59:00 CEST, Trnava, Slocakia
 Copyright (c) 2015, Inikoo

 Version 3

*/

$tab     = 'payment_account.stores';
$ar_file = 'ar_payments_tables.php';
$tipo    = 'stores';

$default = $user->get_tab_defaults($tab);


$table_views = array(
    'overview' => array(
        'label' => _('Overview'),
        'title' => _('Overview')
    ),

);

$table_filters = array(
    'code' => array(
        'label' => _('Code'),
        'title' => _('Store code')
    ),

);

$parameters = array(
    'parent'     => $state['object'],
    'parent_key' => $state['key'],

);


include('utils/get_table_html.php');


?>
