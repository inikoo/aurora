<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 28 November 2018 at 23:35:24 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/

$tab     = 'store.payment_accounts';
$ar_file = 'ar_accounting_tables.php';
$tipo    = 'accounts';

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
        'title' => _('Account code')
    ),
    'name' => array(
        'label' => _('Name'),
        'title' => _('Account name')
    ),

);

$parameters = array(
    'parent'     => $state['parent'],
    'parent_key' => $state['parent_key'],

);


$smarty->assign('title', _('Payment accounts').' <span class=\"id\" title=\"'.$state['_parent']->get('Name').'\" >'.$state['_parent']->get('Code').'</span>');
$smarty->assign(
    'view_position', '<span onclick=\"change_view(\'payment_accounts/all\')\">'._('Payment accounts').' ('._('All stores').')</span><i class=\"fa fa-angle-double-right separator\"></i>    <span onclick=\"change_view(\'payment_accounts/'.$state['_parent']->id.'\')\">'._(
                       'Payment accounts'
                   ).'  <span id=\"id\">('.$state['_parent']->get('Code').')</span></span>'
);


include('utils/get_table_html.php');


?>
