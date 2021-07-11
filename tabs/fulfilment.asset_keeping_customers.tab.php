<?php /** @noinspection DuplicatedCode */
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 16 June 2021 19:07 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/

/** @var User $user */
/** @var Smarty $smarty */
/** @var array $state */


if ($user->can_view('fulfilment')) {


    $tab     = 'fulfilment.asset_keeping_customers';
    $ar_file = 'ar_fulfilment_tables.php';
    $tipo    = 'asset_keeping_customers';


    $default = $user->get_tab_defaults($tab);


    $table_views = array(
        'overview' => array(
            'label' => _('Overview'),
            'title' => _('Overview')
        ),
        'contact'  => array(
            'label' => _('Contact'),
            'title' => _('Contact details')
        ),
        'invoices' => array(
            'label' => _('Invoices/Balance'),
            'title' => _('Invoices & Account balance')
        )

    );

    $table_filters = array(
        'name'         => array(
            'label' => _('Name'),
            'title' => _('Customer name')
        ),
        'email'        => array(
            'label' => _('Email'),
            'title' => _('Customer email')
        ),
        'company_name' => array(
            'label' => _('Company name'),
            'title' => _('Company name')
        ),
        'contact_name' => array(
            'label' => _('Contact name'),
            'title' => _('Contact name')
        )

    );

    $parameters = array(
        'parent'     => 'warehouse',
        'parent_key' => $state['parent_key'],

    );


    $table_buttons = array();


    $smarty->assign('store', $state['store']);

    $smarty->assign('table_buttons', $table_buttons);


    include 'utils/get_table_html.php';

} else {
    $html = '<div style="padding: 20px"><i class="fa error fa-octagon " ></i>  '._('Access denied').'</div>';
}