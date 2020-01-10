<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 4 October 2018 at 10:40:39 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/


$parameters = array(
    'parent'     => 'account',
    'parent_key' => 1,

);


$tab     = 'account.mailshots';
$ar_file = 'ar_mailroom_tables.php';
$tipo    = 'account_mailshots';


$default = $user->get_tab_defaults($tab);


$table_views = array(
    'overview' => array(
        'label' => _('Overview'),
        'title' => _('Overview')
    )

);

$table_filters = array(
    'name' => array(
        'label' => _('Name'),
        'title' => _('name')
    )

);

$table_buttons = array();

$smarty->assign('table_buttons', $table_buttons);

include 'utils/get_table_html.php';


