<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 19 August 2016 at 13:38:32 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/
/** @var array $state */
/** @var Smarty $smarty */
/** @var User $user */


$tab     = 'agent.users';
$ar_file = 'ar_users_tables.php';
$tipo    = 'users';

$default = $user->get_tab_defaults($tab);


$table_views = array(
    'overview' => array('label' => _('Overview')),
    'weblog'      => array('label' => _('Syslog')),

);

$table_filters = array(
    'handle' => array('label' => _('Handle')),
    'name'   => array('label' => _('Name')),

);

$parameters = array(
    'parent'     => $state['object'],
    'parent_key' => $state['key'],

);

$table_buttons   = array();
$table_buttons[] = array(
    'icon'      => 'plus',
    'title'     => _('New user'),
    'reference' => "agent/".$state['key']."/user/new"
);
$smarty->assign('table_buttons', $table_buttons);


include('utils/get_table_html.php');

