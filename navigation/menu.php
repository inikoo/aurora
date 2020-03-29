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


if ($user->can_view('customers')) {


    $nav_menu[] = array(
        '<i class="button far fa-users fa-fw"></i>',
        _('Customers'),
        'customers/all',
        'customers',
        'module',
        ''
    );


}


if ($user->can_view('mailroom')) {


    $nav_menu[] = array(
        '<i class="button far fa-mail-bulk fa-fw"></i>',
        _('Mailroom'),
        'mailroom',
        'mailroom',
        'module',
        ''
    );


}

if ($user->can_view('stores')) {


    $nav_menu[] = array(
        '<i class="button far fa-store-alt fa-fw"></i>',
        _('Products'),
        'stores',
        'products',
        'module',
        ''
    );


    $nav_menu[] = array(
        '<i class="button far fa-badge-percent fa-fw"></i>',
        _('Offers'),
        'offers',
        'offers',
        'module',
        ''
    );

    $nav_menu[] = array(
        '<i class="button far fa-globe fa-fw"></i>',
        _('Websites'),
        'websites',
        'websites',
        'module',
        ''
    );


}

$store_blocks = count($nav_menu);

if ($user->can_view('orders')) {


    $nav_menu[] = array(
        '<i class="button far fa-shopping-cart fa-fw"></i>',
        _('Orders'),
        'orders/all/dashboard',
        'orders',
        'module',
        ''
    );

    $store_blocks++;

    $nav_menu[] = array(
        '<i class="button far fa-conveyor-belt-alt fa-fw"></i>',
        _('Delivering'),
        'delivery_notes/all',
        'delivery_notes',
        'module',
        ''
    );

}


if ($user->can_view('locations')) {


    if ($account->get('Account Warehouses') == 1) {


        $sql  = 'SELECT `Warehouse Key` FROM `Warehouse Dimension` WHERE `Warehouse State`=?';
        $stmt = $db->prepare($sql);
        $stmt->execute(
            array('Active')
        );
        if ($row = $stmt->fetch()) {
            $warehouse_key = $row['Warehouse Key'];
        }

        if ($result = $db->query($sql)) {
            if ($row = $result->fetch()) {
                $warehouse_key = $row['Warehouse Key'];
            }
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
        'suppliers/dashboard',
        'suppliers',
        'module',
        ''
    );
}


if ($user->can_view('production') and $account->get('Account Manufacturers') > 0) {


    if ($account->get('Account Manufacturers') == 1) {


        $sql = 'SELECT `Supplier Production Supplier Key` FROM `Supplier Production Dimension` left join `Supplier Dimension` on (`Supplier Key`=`Supplier Production Supplier Key`) WHERE `Supplier Type`!=?';

        $stmt = $db->prepare($sql);
        $stmt->execute(
            array('Archived')
        );
        if ($row = $stmt->fetch()) {
            $manufacturer_key = $row['Supplier Production Supplier Key'];

            $nav_menu[] = array(
                '<i class="button far fa-industry fa-fw"></i>',
                _('Production'),
                'production/'.$manufacturer_key,
                'production',
                'module',
                ''
            );

        }


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


if ($user->can_view('staff')) {
    $nav_menu[] = array(
        '<i class="button far fa-clipboard-user fa-fw"></i>',
        _('Staff'),
        'hr',
        'hr',
        'module',
        ''
    );
}

if ($user->can_view('orders')) {


    $nav_menu[] = array(
        '<i class="button fal fa-abacus fa-fw"></i>',
        _('Accounting'),
        'invoices/per_store',
        'accounting',
        'module',
        ''
    );


}


if ($user->can_view('sales_reports') or $user->can_view('customers_reports') or $user->can_view('suppliers_reports') or $user->can_view('inventory_reports') or $user->can_view('kpis_reports') or $user->can_view('users_reports')) {
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


} elseif ($user->get('User Type') == 'Supplier') {


} elseif ($user->get('User Type') == 'Warehouse') {


} else {


    if (isset($nav_menu[$store_blocks])) {
        $nav_menu[$store_blocks][5] = $nav_menu[$store_blocks][5].' jump';
    }

    $last_block = count($nav_menu);
    if (isset($nav_menu[$last_block])) {
        $nav_menu[$last_block][5] = $nav_menu[$last_block][5].' last';
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


}


$current_item = $data['module'];
if ($current_item == 'customers_server') {
    $current_item = 'customers';
} elseif ($current_item == 'dashboard') {
    $current_item = '_dashboard';
} elseif ($current_item == 'marketing_server') {
    $current_item = 'marketing';
} elseif ($current_item == 'products_server') {
    $current_item = 'products';
} elseif ($current_item == 'orders_server') {
    $current_item = 'orders';
} elseif ($current_item == 'accounting_server') {
    $current_item = 'accounting';
} elseif ($current_item == 'invoices_server') {
    $current_item = 'invoices';
} elseif ($current_item == 'delivery_notes_server') {
    $current_item = 'delivery_notes';
} elseif ($current_item == 'inventory_server') {
    $current_item = 'inventory';
} elseif ($current_item == 'warehouses_server') {
    $current_item = 'warehouses';
} elseif ($current_item == 'production_server') {
    $current_item = 'production';
} elseif ($current_item == 'accounting_server') {
    $current_item = 'payments';
} elseif ($current_item == 'mailroom_server') {
    $current_item = 'mailroom';
} elseif ($current_item == 'websites_server') {
    $current_item = 'websites';
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


