<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 05 Aug 2021 23:26:04 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 3.0
 */

/** @var array $state */
/** @var Smarty $smarty */
/** @var User $user */


$tab     = 'supplier.users';
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
    'reference' => "supplier/".$state['key']."/user/new"
);
$smarty->assign('table_buttons', $table_buttons);


include('utils/get_table_html.php');

