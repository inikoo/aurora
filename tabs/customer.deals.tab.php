<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 31-05-2019 14:39:51 BST Sheffield, UK
 Copyright (c) 2016, Inikoo

 Version 3

*/

/** @var User $user */
/** @var PDO $db */
/** @var Smarty $smarty */
/** @var array $state */

$tab     = 'customer.deals';
$ar_file = 'ar_marketing_tables.php';
$tipo    = 'customer_deals';

$default = $user->get_tab_defaults($tab);


$table_views = array(
    'overview' => array(
        'label' => _('Overview'),
        'title' => _('Overview')
    ),

);

$table_filters = array(

    'name' => array('label' => _('Name')),

);

$parameters = array(
    'parent'     => $state['object'],
    'parent_key' => $state['key'],

);


$table_buttons   = array();

/*
$table_buttons[] = array(
    'icon'      => 'plus',
    'title'     => _('New offer'),
    'reference' => "customers/".$state['parent_key']."/".$state['key']."/deals/new"
);
*/

$smarty->assign('table_buttons', $table_buttons);


include 'utils/get_table_html.php';

