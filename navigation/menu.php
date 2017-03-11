<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 26 August 2015 23:49:27 GMT+8 Singapore

 Copyright (c) 2015, Inikoo

 Version 3.0
*/


$current_section = $data['section'];

$nav_menu = array();

$nav_menu[] = array(
    '<i class="fa fa-dashboard fa-fw"></i>',
    _('Dashboard'),
    '/dashboard',
    '_dashboard',
    'module',
    ''
);


if ($user->can_view('customers')) {


    if ($user->get('User Hooked Store Key')) {
        $nav_menu[] = array(
            '<i class="fa fa-users fa-fw"></i>',
            _('Customers'),
            'customers/'.$user->get('User Hooked Store Key').'/dashboard',
            'customers',
            'module',
            ''
        );

    } else {
        $nav_menu[] = array(
            '<i class="fa fa-users fa-fw"></i>',
            _('Customers'),
            'customers/all',
            'customers',
            'module',
            ''
        );
    }


    /*
    $sections=get_sections('customers', $data['parent_key']);
    foreach ($sections as $key=>$section ) {
        $nav_menu[] = array('<i class="fa fa-'.$section['icon'].' fa-fw"></i>',$section['label'], $section['reference'], $key, 'section', '');
    }
    */
}


if ($user->can_view('orders')) {

    if ($user->get('User Hooked Store Key')) {
        $nav_menu[] = array(
            '<i class="fa fa-shopping-cart fa-fw"></i>',
            _('Orders'),
            'orders/'.$user->get('User Hooked Store Key').'/dashboard',
            'orders',
            'module',
            ''
        );
    } else {
        $nav_menu[] = array(
            '<i class="fa fa-shopping-cart fa-fw"></i>',
            _('Orders'),
            'orders/all',
            'orders',
            'module',
            ''
        );
    }
    /*
    $sections=get_sections('orders', $data['parent_key']);
    foreach ($sections as $key=>$section ) {
        $nav_menu[] = array('<i class="fa fa-'.$section['icon'].' fa-fw"></i>',$section['label'], $section['reference'], $key, 'section', '');
    }
*/

    if ($user->get('User Hooked Store Key')) {
        $nav_menu[] = array(
            '<i class="fa fa-truck fa-flip-horizontal fa-fw"></i>',
            _('Delivery notes'),
            'delivery_notes',
            'delivery_notes',
            'module',
            ''
        );
    } else {
        $nav_menu[] = array(
            '<i class="fa fa-truck fa-flip-horizontal fa-fw"></i>',
            _('Delivery notes'),
            'delivery_notes/all',
            'delivery_notes',
            'module',
            ''
        );
    }
    /*
    $sections=get_sections('invoices', $data['parent_key']);
    foreach ($sections as $key=>$section ) {
        $nav_menu[] = array('<i class="fa fa-'.$section['icon'].' fa-fw"></i>',$section['label'], $section['reference'], $key, 'section', '');
    }
*/


    if ($user->get('User Hooked Store Key')) {
        $nav_menu[] = array(
            '<i class="fa fa-file-text-o fa-fw"></i>',
            _('Invoices'),
            'invoices',
            'invoices',
            'module',
            ''
        );
    } else {
        $nav_menu[] = array(
            '<i class="fa fa-file-text-o fa-fw"></i>',
            _('Invoices'),
            'invoices/all',
            'invoices',
            'module',
            ''
        );
    }
    /*
    $sections=get_sections('invoices', $data['parent_key']);
    foreach ($sections as $key=>$section ) {
        $nav_menu[] = array('<i class="fa fa-'.$section['icon'].' fa-fw"></i>',$section['label'], $section['reference'], $key, 'section', '');
    }
*/

    if ($user->get('User Hooked Store Key')) {
        $nav_menu[] = array(
            '<i class="fa fa-usd fa-fw"></i>',
            _('Payments'),
            'payments',
            'payments',
            'module',
            ''
        );
    } else {
        $nav_menu[] = array(
            '<i class="fa fa-usd fa-fw"></i>',
            _('Payments'),
            'payments/all',
            'payments',
            'module',
            ''
        );
    }
    /*
    $sections=get_sections('payments', $data['parent_key']);
    foreach ($sections as $key=>$section ) {
        $nav_menu[] = array('<i class="fa fa-'.$section['icon'].' fa-fw"></i>',$section['label'], $section['reference'], $key, 'section', '');
    }
    */
}

if ($user->can_view('sites')) {


    if ($user->data['User Hooked Site Key']) {
        $nav_menu[] = array(
            '<i class="fa fa-globe fa-fw"></i>',
            _('Websites'),
            'website/'.$user->data['User Hooked Site Key'],
            'websites',
            'module',
            ''
        );
    } else {
        $nav_menu[] = array(
            '<i class="fa fa-globe fa-fw"></i>',
            _('Websites'),
            'websites',
            'websites',
            'module',
            ''
        );
    }
    /*
    $sections=get_sections('websites', $data['parent_key']);
    foreach ($sections as $key=>$section ) {
        $nav_menu[] = array('<i class="fa fa-'.$section['icon'].' fa-fw"></i>',$section['label'], $section['reference'], $key, 'section', '');
    }
*/

}

if ($user->can_view('marketing')) {

    if ($user->get('User Hooked Store Key')) {
        $nav_menu[] = array(
            '<i class="fa fa-bullhorn fa-fw"></i>',
            _('Marketing'),
            'campaigns/'.$user->get('User Hooked Store Key'),
            'marketing',
            'module',
            ''
        );

    } else {
        $nav_menu[] = array(
            '<i class="fa fa-bullhorn fa-fw"></i>',
            _('Marketing'),
            'marketing/all',
            'marketing',
            'module',
            ''
        );
    }

    /*
    $sections=get_sections('marketing', $data['parent_key']);
    foreach ($sections as $key=>$section ) {
        $nav_menu[] = array('<i class="fa fa-'.$section['icon'].' fa-fw"></i>',$section['label'], $section['reference'], $key, 'section', '');
    }
*/


}

if ($user->can_view('stores')) {

    if ($user->get('User Hooked Store Key')) {
        $nav_menu[] = array(
            '<i class="fa fa-cube fa-fw"></i>',
            _('Products'),
            'store/'.$user->get('User Hooked Store Key'),
            'products',
            'module',
            ''
        );

    } else {
        $nav_menu[] = array(
            '<i class="fa fa-cube fa-fw"></i>',
            _('Products'),
            'stores',
            'products',
            'module',
            ''
        );
    }

    /*
    $sections=get_sections('products', $data['parent_key']);
    foreach ($sections as $key=>$section ) {
        $nav_menu[] = array('<i class="fa fa-'.$section['icon'].' fa-fw"></i>',$section['label'], $section['reference'], $key, 'section', '');
    }
*/


}


if ($user->can_view('locations')) {


    if ($user->get('User Hooked Warehouse Key')) {

        $nav_menu[] = array(
            '<i class="fa fa-map fa-fw"></i>',
            _('Warehouse'),
            'warehouse/'.$user->get('User Hooked Warehouse Key').'/dashboard',
            'warehouses',
            'module',
            ''
        );
    } else {

        $nav_menu[] = array(
            '<i class="fa fa-map fa-fw"></i>',
            _('Warehouse'),
            'warehouses',
            'warehouses',
            'module',
            ''
        );
    }

}

if ($user->can_view('parts')) {


    $nav_menu[] = array(
        '<i class="fa fa-th-large fa-fw"></i>',
        _('Inventory'),
        'inventory',
        'inventory',
        'module',
        ''
    );


}


if ($user->can_view('suppliers')) {
    $nav_menu[] = array(
        '<i class="fa fa-ship fa-fw"></i>',
        _('Suppliers'),
        'suppliers',
        'suppliers',
        'module',
        ''
    );
}


if ($user->can_view('production')) {


    if ($user->get('User Hooked Production Key')) {
        $nav_menu[] = array(
            '<i class="fa fa-industry fa-fw"></i>',
            _('Production'),
            'production/'.$user->get(
                'User Hooked Production Key'
            ),
            'production',
            'module',
            ''
        );
    } else {
        $nav_menu[] = array(
            '<i class="fa fa-industry fa-fw"></i>',
            _('Production'),
            'production/all',
            'production',
            'module',
            ''
        );


    }

}


if ($user->can_view('staff')) {
    $nav_menu[] = array(
        '<i class="fa fa-hand-rock-o fa-fw"></i>',
        _('Manpower'),
        'hr',
        'hr',
        'module',
        ''
    );
}


if ($user->can_view('reports')) {
    $nav_menu[] = array(
        '<i class="fa fa-line-chart fa-fw"></i>',
        _('Reports'),
        'reports',
        'reports',
        'module',
        ''
    );
}


if ($user->get('User Type') == 'Agent') {




    $nav_menu[] = array(
        '<i class="fa fa-clipboard fa-fw"></i>',
        _("Client's orders"),
        'orders',
        'agent_client_orders',
        'module',
        ''
    );



    $nav_menu[] = array(
        '<i class="fa fa-ship fa-fw"></i>',
        _('Deliveries'),
        'deliveries',
        'agent_client_deliveries',
        'module',
        ''
    );

    $nav_menu[] = array(
        '<i class="fa fa-industry fa-fw"></i>',
        _('Suppliers'),
        'suppliers',
        'agent_suppliers',
        'module',
        ''
    );
    $nav_menu[] = array(
        '<i class="fa fa-user fa-fw"></i>',
        _('My profile'),
        'profile',
        'agent_profile',
        'module',
        'jump'
    );


} elseif ($user->get('User Type') == 'Supplier') {


    //$nav_menu[] = array(_('Orders'), 'suppliers.php?orders'  ,'orders');
    $nav_menu[] = array(
        _('Products'),
        'suppliers.php',
        'suppliers',
        'module',
        ''
    );
    $nav_menu[] = array(
        _('Dashboard'),
        'index.php',
        'home',
        'module',
        ''
    );
} elseif ($user->get('User Type') == 'Warehouse') {

    $nav_menu[] = array(
        _('Pending Orders'),
        'warehouse_orders.php?id='.$user->data['User Parent Key'],
        'orders',
        'module',
        'last'
    );


} else {


    $nav_menu[] = array(
        '<i class="fa fa-star fa-fw"></i>',
        _('My profile'),
        '/profile',
        'profile',
        'module',
        'jump'
    );
    $prev_index = count($nav_menu) - 2;

    if (isset($nav_menu[$prev_index])) {
        $nav_menu[$prev_index][5] = $nav_menu[$prev_index][5].' last';
    }
}

if ($user->can_view('account')) {


    $nav_menu[] = array(
        '<i class="fa fa-certificate fa-fw"></i>',
        _('Account'),
        '/account',
        'account',
        'module',
        ''
    );
}



$current_item = $data['module'];
if ($current_item == 'customers_server') {
    $current_item = 'customers';
}
if ($current_item == 'dashboard') {
    $current_item = '_dashboard';
}
if ($current_item == 'marketing_server') {
    $current_item = 'marketing';
}
if ($current_item == 'products_server') {
    $current_item = 'products';
}
if ($current_item == 'orders_server') {
    $current_item = 'orders';
}
if ($current_item == 'invoices_server') {
    $current_item = 'invoices';
}
if ($current_item == 'delivery_notes_server') {
    $current_item = 'delivery_notes';
}
if ($current_item == 'inventory_server') {
    $current_item = 'inventory';
}
if ($current_item == 'warehouses_server') {
    $current_item = 'warehouses';
}
if ($current_item == 'production_server') {
    $current_item = 'production';
}

if ($data['object'] == 'order') {
    if ($data['parent'] == 'customer') {
        $current_item = 'customers';
    }

} elseif ($data['object'] == 'product') {
    if ($data['parent'] == 'order') {
        $current_item = 'orders';
    }

}
//print_r($nav_menu);

$smarty->assign('current_item', $current_item);
$smarty->assign('current_section', $current_section);


$smarty->assign('nav_menu', $nav_menu);

$html = $smarty->fetch('menu.tpl');


?>
