<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 29 July 2017 at 12:00:01 CEST, Trnava, Slovakia
 Copyright (c) 2017, Inikoo

 Version 3

*/

$tab     = 'payment_account.websites';
$ar_file = 'ar_payments_tables.php';
$tipo    = 'websites';

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
        'title' => _('Code')
    ),

);

$parameters = array(
    'parent'     => $state['object'],
    'parent_key' => $state['key'],

);


include('utils/get_table_html.php');


?>
