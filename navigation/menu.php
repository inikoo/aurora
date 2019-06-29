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


if ($user->get('Type') != 'Administrator') {

    $nav_menu[] = array(
        '<i class="button far fa-tachometer-alt fa-fw"></i>',
        _('Dashboard'),
        '/dashboard',
        '_dashboard',
        'module',
        ''
    );
}

if ($user->can_view('customers')) {


    if ($user->get('User Hooked Store Key')) {
        $nav_menu[] = array(
            '<i class="button far fa-users fa-fw"></i>',
            _('Customers'),
            'customers/'.$user->get('User Hooked Store Key').'/dashboard',
            'customers',
            'module',
            ''
        );

    } else {
        $nav_menu[] = array(
            '<i class="button far fa-users fa-fw"></i>',
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
        $nav_menu[] = array('<i class="button far fa-'.$section['icon'].' fa-fw"></i>',$section['label'], $section['reference'], $key, 'section', '');
    }
    */
}

if ($user->can_view('stores')) {

    if ($user->get('User Hooked Store Key')) {
        $nav_menu[] = array(
            '<i class="button far fa-store-alt fa-fw"></i>',
            _('Products'),
            'store/'.$user->get('User Hooked Store Key'),
            'products',
            'module',
            ''
        );

    } else {
        $nav_menu[] = array(
            '<i class="button far fa-store-alt fa-fw"></i>',
            _('Stores'),
            'stores',
            'products',
            'module',
            ''
        );
    }

    /*
    $sections=get_sections('products', $data['parent_key']);
    foreach ($sections as $key=>$section ) {
        $nav_menu[] = array('<i class="button far fa-'.$section['icon'].' fa-fw"></i>',$section['label'], $section['reference'], $key, 'section', '');
    }
*/


}


if ($user->can_view('orders')) {

    if ($user->get('User Hooked Store Key')) {
        $nav_menu[] = array(
            '<i class="button far fa-shopping-cart fa-fw"></i>',
            _('Orders'),
            'orders/'.$user->get('User Hooked Store Key').'/dashboard',
            'orders',
            'module',
            ''
        );
    } else {
        $nav_menu[] = array(
            '<i class="button far fa-shopping-cart fa-fw"></i>',
            _('Orders'),
            'orders/all/dashboard',
            'orders',
            'module',
            ''
        );
    }
    /*
    $sections=get_sections('orders', $data['parent_key']);
    foreach ($sections as $key=>$section ) {
        $nav_menu[] = array('<i class="button far fa-'.$section['icon'].' fa-fw"></i>',$section['label'], $section['reference'], $key, 'section', '');
    }
*/


    $nav_menu[] = array(
        '<i class="button far fa-conveyor-belt-alt fa-fw"></i>',
        _('Delivering'),
        'delivery_notes/all',
        'delivery_notes',
        'module',
        ''
    );




if ($user->can_view('locations')) {


    if ($user->get('User Hooked Warehouse Key')) {

        $nav_menu[] = array(
            '<i class="button far fa-warehouse-alt fa-fw"></i>',
            _('Warehouse'),
            'warehouse/'.$user->get('User Hooked Warehouse Key').'/dashboard',
            'warehouses',
            'module',
            ''
        );
    } elseif ($account->get('Account Warehouses') == 1) {


        $sql = sprintf('SELECT `Warehouse Key` FROM `Warehouse Dimension` WHERE `Warehouse State`="Active" ');

        if ($result = $db->query($sql)) {
            if ($row = $result->fetch()) {
                $warehouse_key = $row['Warehouse Key'];
            }
        } else {
            print_r($error_info = $db->errorInfo());
            print "$sql\n";
            exit;
        }

        $nav_menu[] = array(
            '<i class="button far fa-warehouse-alt fa-fw"></i>',
            _('Warehouse'),
            'warehouse/'.$warehouse_key.'/dashboard',
            'warehouses',
            'module',
            ''
        );
    } else {

        $nav_menu[] = array(
            '<i class="button far fa-forklift fa-fw"></i>',
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
        '<i class="button far fa-box fa-fw"></i>',
        _('Inventory'),
        'inventory/dashboard',
        'inventory',
        'module',
        ''
    );


}


if ($user->can_view('suppliers')) {
    $nav_menu[] = array(
        '<i class="button far fa-hand-holding-box fa-fw"></i>',
        _('Suppliers'),
        'suppliers',
        'suppliers',
        'module',
        ''
    );
}


if ($user->can_view('production') and $account->get('Account Manufacturers') > 0) {


    if ($user->get('User Hooked Production Key')) {
        $nav_menu[] = array(
            '<i class="button far fa-industry fa-fw"></i>',
            _('Production'),
            'production/'.$user->get('User Hooked Production Key'),
            'production',
            'module',
            ''
        );
    } elseif ($account->get('Account Manufacturers') == 1) {


        $sql = sprintf('SELECT `Supplier Production Supplier Key` FROM `Supplier Production Dimension` left join `Supplier Dimension` on (`Supplier Key`=`Supplier Production Supplier Key`) WHERE `Supplier Type`!="Archived"  ');

        if ($result = $db->query($sql)) {
            if ($row = $result->fetch()) {
                $manufacturer_key = $row['Supplier Production Supplier Key'];
            }
        } else {
            print_r($error_info = $db->errorInfo());
            print "$sql\n";
            exit;
        }


        $nav_menu[] = array(
            '<i class="button far fa-industry fa-fw"></i>',
            _('Production'),
            'production/'.$manufacturer_key,
            'production',
            'module',
            ''
        );
    } else {
        $nav_menu[] = array(
            '<i class="button far fa-industry fa-fw"></i>',
            _('Production'),
            'production/all',
            'production',
            'module',
            ''
        );


    }

}


if ($user->can_view('orders')) {

    if ($user->get('User Hooked Store Key')) {
        $nav_menu[] = array(
            '<i class="button fal fa-abacus fa-fw"></i>',
            _('Accounting'),
            'accounting/'.$user->get('User Hooked Store Key'),
            'accounting',
            'module',
            ''
        );
    } else {


        $nav_menu[] = array(
            '<i class="button fal fa-abacus fa-fw"></i>',
            _('Accounting'),
            'invoices/per_store',
            'accounting',
            'module',
            ''
        );

    }
}

if ($user->can_view('staff')) {
    $nav_menu[] = array(
        '<i class="button far fa-sitemap fa-fw"></i>',
        _('Manpower'),
        'hr',
        'hr',
        'module',
        ''
    );
}


if ($user->can_view('reports')) {
    $nav_menu[] = array(
        '<i class="button far fa-chart-line fa-fw"></i>',
        _('Reports'),
        'reports',
        'reports',
        'module',
        ''
    );
}


if ($user->get('User Type') == 'Agent') {


    $nav_menu[] = array(
        '<i class="button far fa-clipboard fa-fw"></i>',
        _("Client's orders"),
        'orders',
        'agent_client_orders',
        'module',
        ''
    );


    $nav_menu[] = array(
        '<i class="button far fa-truck-container fa-fw"></i>',
        _('Deliveries'),
        'agent_deliveries',
        'agent_client_deliveries',
        'module',
        ''
    );

    $nav_menu[] = array(
        '<i class="button far fa-industry fa-fw"></i>',
        _('Suppliers'),
        'suppliers',
        'agent_suppliers',
        'module',
        ''
    );

    $nav_menu[] = array(
        '<i class="button far fa-box fa-fw"></i>',
        _('Products'),
        'agent_parts',
        'agent_parts',
        'module',
        ''
    );
    $nav_menu[] = array(
        '<i class="button far fa-user fa-fw"></i>',
        _('My profile'),
        'profile',
        'agent_profile',
        'module',
        'jump'

    );


} elseif ($user->get('User Type') == 'Supplier') {


} elseif ($user->get('User Type') == 'Warehouse') {


} else {


    $nav_menu[] = array(
        '<i class="button fa fa-user-circle fa-fw"></i>',
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
        '<i class="button fal fa-users-class fa-fw"></i>',
        _('Users'),
        '/users',
        'users',
        'module',
        ''
    );


    $nav_menu[] = array(
        '<i class="button far fa-toolbox fa-fw"></i>',
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
if ($current_item == 'accounting_server') {
    $current_item = 'accounting';
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

if ($current_item == 'accounting_server') {
    $current_item = 'payments';
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


$smarty->assign('current_item', $current_item);
$smarty->assign('current_section', $current_section);


$smarty->assign('nav_menu', $nav_menu);

$html = $smarty->fetch('menu.tpl');


?>
