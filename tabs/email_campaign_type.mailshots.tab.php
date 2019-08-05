<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 28 June 2018 at 19:08:54 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/


$parameters = array(
    'parent'     => $state['object'],
    'parent_key' => $state['key'],

);


$tab     = 'email_campaign_type.mailshots';
$ar_file = 'ar_mailshots_tables.php';
$tipo    = 'mailshots';


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

if ($state['_object']->get('Code') == 'Newsletter') {


    $table_buttons[] = array(
        'icon'  => 'plus',
        'title' => _('New newsletter'),
        'id'    => 'new_newsletter',
        'attr'  => array(
            'parent'     => 'Store',
            'parent_key' => $state['_object']->get('Store Key'),

        )

    );

    $smarty->assign(
        'js_code', 'js/injections/new_newsletter.'.(_DEVEL ? '' : 'min.').'js'
    );


} elseif ($state['_object']->get('Code') == 'Marketing') {

    $table_buttons[] = array(
        'icon'      => 'plus',
        'title'     => _('New mailshot'),
        'reference' => "marketing/".$state['parent_key']."/emails/".$state['key'].'/mailshot/new'

    );

/*
    $table_buttons[] = array(
        'icon'  => 'plus',
        'title' => _('New marketing mailshot'),
        'id'    => 'new_mailshot',
        'attr'  => array(
            'parent'     => 'Store',
            'parent_key' => $state['_object']->get('Store Key'),

        )

    );
*/

  //  $smarty->assign('js_code', 'js/injections/new_marketing_mailshot.'.(_DEVEL ? '' : 'min.').'js');


} elseif ($state['_object']->get('Code') == 'AbandonedCart') {

    $table_buttons[] = array(
        'icon'  => 'plus',
        'title' => _('New mailshot for orders in basket'),
        'id'    => 'new_orders_in_website_mailshot',
        'attr'  => array(
            'parent'     => 'Store',
            'parent_key' => $state['_object']->get('Store Key'),

        )

    );

    $smarty->assign(
        'js_code', 'js/injections/new_abandoned_cart_mailshot.'.(_DEVEL ? '' : 'min.').'js'
    );

}


$smarty->assign('table_buttons', $table_buttons);


include 'utils/get_table_html.php';


?>
