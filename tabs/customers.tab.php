<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 13 September 2015 18:30:16 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/

if (in_array($state['store']->id, $user->stores) and $user->can_view('customers')) {


    $store = $state['store'];

    if($state['store']->get('Type')=='Dropshipping'){
        $tab     = 'customers_dropshipping';
        $ar_file = 'ar_customers_tables.php';
        $tipo    = 'customers_dropshipping';
    }else{
        $tab     = 'customers';
        $ar_file = 'ar_customers_tables.php';
        $tipo    = 'customers';
    }



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
        'parent'     => 'store',
        'parent_key' => $state['parent_key'],

    );



    $table_buttons = array();


    if ($state['store']->get('Store Type') != 'External'  and $store->get('Store Type') != 'Fulfilment' ) {
        $table_buttons[] = array(
            'icon'      => 'plus',
            'title'     => _('New customer'),
            'reference' => "customers/".$state['parent_key']."/new"
        );
    }
    $smarty->assign('store', $state['store']);

    $smarty->assign('table_buttons', $table_buttons);


    include 'utils/get_table_html.php';

} else {
    try {
        $html = $smarty->fetch('access_denied.tpl');
    } catch (Exception $e) {
    }
}