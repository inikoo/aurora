<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 02-10-2019 15:14:08 MYT, Kuala Lumpur, Malaysia
 Copyright (c) 2019, Inikoo

 Version 3

*/

if ($state['store']->get('Store Type') == 'Dropshipping') {


    if (in_array($state['store']->id, $user->stores) and $user->can_view('customers')) {


        $tab     = 'customer_clients';
        $ar_file = 'ar_customers_tables.php';
        $tipo    = 'customer_clients';

        $default = $user->get_tab_defaults($tab);


        $table_views = array(
            'overview' => array(
                'label' => _('Overview'),
                'title' => _('Overview')
            ),
            'sales' => array(
                'label' => _('Sales'),
                'title' => _('Sales')
            ),
            'contact' => array(
                'label' => _('Contact'),
                'title' => _('Contact')
            ),


        );

        $table_filters = array(
            'code'  => array(
                'label' => _('Reference'),
                'title' => _("Customer's client reference")
            ),
            'name'  => array(
                'label' => _('Name'),
                'title' => _("Customer's client name")
            ),
            'email' => array(
                'label' => _('Email'),
                'title' => _("Customer's client email")
            ),


        );

        $parameters = array(
            'parent'     => $state['object'],
            'parent_key' => $state['key'],

        );


        $table_buttons = array();

        if(!($state['_object']->get('Customer Type by Activity')=='Rejected' or $state['_object']->get('Customer Type by Activity')=='ToApprove')){
            $table_buttons[] = array(
                'icon'      => 'plus',
                'title'     => _('New client'),
                'reference' => "customers/".$state['_object']->get('Store Key')."/".$state['key']."/client/new"
            );
        }




        $smarty->assign('table_buttons', $table_buttons);


        include 'utils/get_table_html.php';

    } else {
        $html = '<div style="padding: 20px"><i class="fa error fa-octagon " ></i>  '._('Access denied').'</div>';
    }
} else {
    $html = 'you should not be here';
}