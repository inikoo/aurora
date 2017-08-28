<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 28 August 2015 18:30:51 GMT+8 Singapore

 Copyright (c) 2015, Inikoo

 Version 3.0
*/





function get_orders_server_dashboard_navigation($data, $smarty, $user, $db, $account) {


    $sections = get_sections('orders_server');


    $left_buttons = array();


    $right_buttons = array();

    if (isset($sections[$data['section']])) {
        $sections[$data['section']]['selected'] = true;
    }

    $title = _("Orders control panel");

    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => $title,
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search orders')
        )

    );
    $smarty->assign('_content', $_content);

    $html = $smarty->fetch('navigation.tpl');

    return $html;

}


function get_dashboard_navigation($data, $smarty, $user, $db, $account) {



    $sections = get_sections('orders', $data['parent_key']);


    $left_buttons = array();


    $right_buttons = array();

    if (isset($sections[$data['section']])) {
        $sections[$data['section']]['selected'] = true;
    }

    $title = _("Orders control panel").' '.$data['_parent']->get('Code');

    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => $title,
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search orders')
        )

    );
    $smarty->assign('_content', $_content);

    $html = $smarty->fetch('navigation.tpl');

    return $html;

}


function get_pending_orders_navigation($data, $smarty, $user, $db, $account) {


    $sections = get_sections('orders', $data['parent_key']);


    $left_buttons = array();


    $right_buttons = array();

    if (isset($sections[$data['section']])) {
        $sections[$data['section']]['selected'] = true;
    }

    $title = _("Pending orders").' <span class="id">'.$data['store']->get('Code').'</span>';

    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => $title,
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search orders')
        )

    );
    $smarty->assign('_content', $_content);

    $html = $smarty->fetch('navigation.tpl');

    return $html;

}



function get_archived_orders_navigation($data, $smarty, $user, $db, $account) {


    $sections = get_sections('orders', $data['parent_key']);


    $left_buttons = array();


    $right_buttons = array();

    if (isset($sections[$data['section']])) {
        $sections[$data['section']]['selected'] = true;
    }



    $title = _("Archived orders").' <span class="id">'.$data['store']->get('Code').'</span>';

    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => $title,
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search orders')
        )

    );
    $smarty->assign('_content', $_content);

    $html = $smarty->fetch('navigation.tpl');

    return $html;

}


function get_basket_orders_navigation($data, $smarty, $user, $db, $account) {


    $sections = get_sections('orders', $data['parent_key']);


    $left_buttons = array();


    $right_buttons = array();

    if (isset($sections[$data['section']])) {
        $sections[$data['section']]['selected'] = true;
    }

    $title = _("Orders in website").' <span class="id">'.$data['store']->get('Code').'</span>';

    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => $title,
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search orders')
        )

    );
    $smarty->assign('_content', $_content);

    $html = $smarty->fetch('navigation.tpl');

    return $html;

}

function get_orders_navigation($data, $smarty, $user, $db, $account) {

    global $user, $smarty;
    require_once 'class.Store.php';

    switch ($data['parent']) {
        case 'store':
            $store = new Store($data['parent_key']);
            break;
        default:

            break;
    }

    $block_view = $data['section'];

    $sections = get_sections('orders', $store->id);
    switch ($block_view) {
        case 'orders':

            //array_pop($sections);
            $sections_class = '';
            $title          = _('Orders').' <span class="id">'.$store->get(
                    'Store Code'
                ).'</span>';

            $up_button    = array(
                'icon'      => 'arrow-up',
                'title'     => _('Orders').' ('._('All stores').')',
                'reference' => 'orders/all'
            );
            $button_label = _('Orders %s');
            break;
        case 'invoices':
            $sections_class = '';
            $title          = _('Invoices').' <span class="id">'.$store->get(
                    'Store Code'
                ).'</span>';

            $up_button    = array(
                'icon'      => 'arrow-up',
                'title'     => _('Invoices').' ('._(
                        'All stores'
                    ).')',
                'reference' => 'invoices/all'
            );
            $button_label = _('Invoices %s');
            break;
        case 'delivery_notes':
            $sections_class = '';
            $title          = _('Delivery Notes').' <span class="id">'.$store->get('Store Code').'</span>';

            $up_button    = array(
                'icon'      => 'arrow-up',
                'title'     => _('Delivery Notes').' ('._(
                        'All stores'
                    ).')',
                'reference' => 'delivery_notes/all'
            );
            $button_label = _('Delivery Notes %s');
            break;
        case 'payments':
            $sections_class = '';
            $title          = _('Payments').' <span class="id">'.$store->get(
                    'Store Code'
                ).'</span>';

            $up_button    = array(
                'icon'      => 'arrow-up',
                'title'     => _('Payments').' ('._(
                        'All stores'
                    ).')',
                'reference' => 'payments/all'
            );
            $button_label = _('Payments %s');
            break;
    }


    $left_buttons = array();
    if ($user->stores > 1) {

        list($prev_key, $next_key) = get_prev_next($store->id, $user->stores);

        $sql = sprintf(
            "SELECT `Store Code` FROM `Store Dimension` WHERE `Store Key`=%d", $prev_key
        );


        if ($result = $db->query($sql)) {
            if ($row = $result->fetch()) {
                $prev_title = sprintf($button_label, $row['Store Code']);
            } else {
                $prev_title = '';
            }
        } else {
            print_r($error_info = $db->errorInfo());
            exit;
        }


        $sql = sprintf(
            "SELECT `Store Code` FROM `Store Dimension` WHERE `Store Key`=%d", $next_key
        );


        if ($result = $db->query($sql)) {
            if ($row = $result->fetch()) {
                $next_title = sprintf($button_label, $row['Store Code']);
            } else {
                $next_title = '';
            }
        } else {
            print_r($error_info = $db->errorInfo());
            exit;
        }


        $left_buttons[] = array(
            'icon'      => 'arrow-left',
            'title'     => $prev_title,
            'reference' => $block_view.'/'.$prev_key
        );
        $left_buttons[] = $up_button;

        $left_buttons[] = array(
            'icon'      => 'arrow-right',
            'title'     => $next_title,
            'reference' => $block_view.'/'.$next_key
        );
    }


    $right_buttons = array();

    if (isset($sections[$data['section']])) {
        $sections[$data['section']]['selected'] = true;
    }


    $_content = array(
        'sections_class' => $sections_class,
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => $title,
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search orders')
        )

    );
    $smarty->assign('_content', $_content);

    $html = $smarty->fetch('navigation.tpl');

    return $html;

}


function get_delivery_notes_navigation($data, $smarty, $user, $db, $account) {

    global $user, $smarty;
    require_once 'class.Store.php';

    switch ($data['parent']) {
        case 'store':
            $store = new Store($data['parent_key']);
            break;
        default:

            break;
    }

    $block_view = $data['section'];


    $sections = get_sections('delivery_notes', $store->id);
    switch ($block_view) {

        case 'delivery_notes':
            $sections_class = '';
            $title          = _('Delivery Notes').' <span class="id">'.$store->get('Store Code').'</span>';

            $up_button    = array(
                'icon'      => 'arrow-up',
                'title'     => _('Delivery Notes').' ('._(
                        'All stores'
                    ).')',
                'reference' => 'delivery_notes/all'
            );
            $button_label = _('Delivery Notes %s');
            break;

    }

    $left_buttons = array();
    if ($user->stores > 1) {

        list($prev_key, $next_key) = get_prev_next($store->id, $user->stores);

        $sql = sprintf(
            "SELECT `Store Code` FROM `Store Dimension` WHERE `Store Key`=%d", $prev_key
        );


        if ($result = $db->query($sql)) {
            if ($row = $result->fetch()) {
                $prev_title = _('Store').' '.$row['Store Code'];
            } else {
                $prev_title = '';
            }
        } else {
            print_r($error_info = $db->errorInfo());
            exit;
        }


        $sql = sprintf(
            "SELECT `Store Code` FROM `Store Dimension` WHERE `Store Key`=%d", $next_key
        );

        if ($result = $db->query($sql)) {
            if ($row = $result->fetch()) {
                $next_title = _('Store').' '.$row['Store Code'];
            } else {
                $next_title = '';
            }
        } else {
            print_r($error_info = $db->errorInfo());
            exit;
        }


        $left_buttons[] = array(
            'icon'      => 'arrow-left',
            'title'     => $prev_title,
            'reference' => $block_view.'/'.$prev_key
        );
        $left_buttons[] = $up_button;

        $left_buttons[] = array(
            'icon'      => 'arrow-right',
            'title'     => $next_title,
            'reference' => $block_view.'/'.$next_key
        );
    }


    $right_buttons = array();

    if (isset($sections[$data['section']])) {
        $sections[$data['section']]['selected'] = true;
    }


    $_content = array(
        'sections_class' => $sections_class,
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => $title,
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search delivery notes')
        )

    );
    $smarty->assign('_content', $_content);

    $html = $smarty->fetch('navigation.tpl');

    return $html;

}


function get_invoices_navigation($data, $smarty, $user, $db, $account) {

    global $user, $smarty;
    require_once 'class.Store.php';

    switch ($data['parent']) {
        case 'store':
            $store = new Store($data['parent_key']);
            break;
        default:

            break;
    }

    $block_view = $data['section'];


    $sections = get_sections('invoices', $store->id);
    switch ($block_view) {

        case 'invoices':
            $sections_class = '';
            $title          = _('Invoices').' <span class="id">'.$store->get(
                    'Store Code'
                ).'</span>';

            $up_button    = array(
                'icon'      => 'arrow-up',
                'title'     => _('Invoices').' ('._(
                        'All stores'
                    ).')',
                'reference' => 'invoices/all'
            );
            $button_label = _('Invoices %s');
            break;

        case 'payments':
            $sections_class = '';
            $title          = _('Payments').' <span class="id">'.$store->get(
                    'Store Code'
                ).'</span>';

            $up_button    = array(
                'icon'      => 'arrow-up',
                'title'     => _('Payments').' ('._(
                        'All stores'
                    ).')',
                'reference' => 'invoices/payments/all'
            );
            $button_label = _('Payments %s');
            break;
    }

    $left_buttons = array();
    if ($user->stores > 1) {

        list($prev_key, $next_key) = get_prev_next($store->id, $user->stores);

        $sql = sprintf(
            "SELECT `Store Code` FROM `Store Dimension` WHERE `Store Key`=%d", $prev_key
        );


        if ($result = $db->query($sql)) {
            if ($row = $result->fetch()) {
                $prev_title = _('Store').' '.$row['Store Code'];
            } else {
                $prev_title = '';
            }
        } else {
            print_r($error_info = $db->errorInfo());
            exit;
        }


        $sql = sprintf(
            "SELECT `Store Code` FROM `Store Dimension` WHERE `Store Key`=%d", $next_key
        );

        if ($result = $db->query($sql)) {
            if ($row = $result->fetch()) {
                $next_title = _('Store').' '.$row['Store Code'];
            } else {
                $next_title = '';
            }
        } else {
            print_r($error_info = $db->errorInfo());
            exit;
        }


        $left_buttons[] = array(
            'icon'      => 'arrow-left',
            'title'     => $prev_title,
            'reference' => $block_view.'/'.$prev_key
        );
        $left_buttons[] = $up_button;

        $left_buttons[] = array(
            'icon'      => 'arrow-right',
            'title'     => $next_title,
            'reference' => $block_view.'/'.$next_key
        );
    }


    $right_buttons = array();

    if (isset($sections[$data['section']])) {
        $sections[$data['section']]['selected'] = true;
    }


    $_content = array(
        'sections_class' => $sections_class,
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => $title,
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search invoices')
        )

    );
    $smarty->assign('_content', $_content);

    $html = $smarty->fetch('navigation.tpl');

    return $html;

}


function get_orders_server_navigation($data, $smarty, $user, $db, $account) {

    global $user, $smarty;


    $block_view = $data['section'];


    $sections = get_sections('orders_server');
    switch ($block_view) {
        case 'orders':

            $sections_class = '';
            $title          = _('Orders').' ('._('All stores').')';

            $button_label = _('Orders %s');
            break;
        case 'invoices':
            $sections_class = '';
            $title          = _('Invoices').' ('._('All stores').')';

            $button_label = _('Invoices %s');
            break;
        case 'delivery_notes':
            $sections_class = '';
            $title          = _('Delivery Notes').' ('._('All stores').')';

            $button_label = _('Delivery Notes %s');
            break;
        case 'payments':
            $sections_class = '';
            $title          = _('Payments').' ('._('All stores').')';

            $button_label = _('Payments %s');
            break;
    }

    $left_buttons = array();


    $right_buttons = array();

    if (isset($sections[$data['section']])) {
        $sections[$data['section']]['selected'] = true;
    }


    $_content = array(
        'sections_class' => $sections_class,
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => $title,
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search orders')
        )

    );
    $smarty->assign('_content', $_content);

    $html = $smarty->fetch('navigation.tpl');

    return $html;

}


function get_invoices_server_navigation($data, $smarty, $user, $db, $account) {

    global $user, $smarty;


    $block_view = $data['section'];


    $sections = get_sections('invoices_server');
    switch ($block_view) {

        case 'invoices':
            $sections_class = '';
            $title          = _('Invoices').' ('._('All stores').')';

            $button_label = _('Invoices %s');
            break;

        case 'payments':
            $sections_class = '';
            $title          = _('Payments').' ('._('All stores').')';

            $button_label = _('Payments %s');
            break;
    }

    $left_buttons = array();


    $right_buttons = array();

    if (isset($sections[$data['section']])) {
        $sections[$data['section']]['selected'] = true;
    }


    $_content = array(
        'sections_class' => $sections_class,
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => $title,
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search invoices')
        )

    );
    $smarty->assign('_content', $_content);

    $html = $smarty->fetch('navigation.tpl');

    return $html;

}


function get_invoices_categories_server_navigation($data, $smarty, $user, $db, $account) {

    global $user, $smarty;


    $block_view = $data['section'];


    $sections = get_sections('invoices_server');

    $sections_class = '';
    $title          = _("Invoice's categories").' ('._('All stores').')';

    $button_label = _('Payments %s');


    // $up_button=array('icon'=>'arrow-up', 'title'=>_("Order's index"), 'reference'=>'account/orders');
    $left_buttons = array();


    $right_buttons = array();

    if (isset($sections[$data['section']])) {
        $sections[$data['section']]['selected'] = true;
    }


    $_content = array(
        'sections_class' => $sections_class,
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => $title,
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search invoices')
        )

    );
    $smarty->assign('_content', $_content);

    $html = $smarty->fetch('navigation.tpl');

    return $html;

}


function get_invoices_category_server_navigation($data, $smarty, $user, $db, $account) {

    global $user, $smarty;


    $block_view = $data['section'];


    $sections = get_sections('invoices_server');

    $sections_class = '';
    $title          = ' <span class="Category_Label">'.$data['_object']->get(
            'Label'
        ).'</span> <span class="Category_Code id">'.$data['_object']->get(
            'Code'
        ).'</span>';

    $button_label = _('Payments %s');


    // $up_button=array('icon'=>'arrow-up', 'title'=>_("Order's index"), 'reference'=>'account/orders');
    $left_buttons = array();


    $right_buttons = array();

    if (isset($sections[$data['section']])) {
        $sections[$data['section']]['selected'] = true;
    }


    $_content = array(
        'sections_class' => $sections_class,
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => $title,
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search invoices')
        )

    );
    $smarty->assign('_content', $_content);

    $html = $smarty->fetch('navigation.tpl');

    return $html;

}


function get_delivery_notes_server_navigation($data, $smarty, $user, $db, $account) {

    global $user, $smarty;


    $block_view = $data['section'];


    $sections = get_sections('orders_server');
    switch ($block_view) {

        case 'delivery_notes':
            $sections_class = '';
            $title          = _('Delivery Notes').' ('._('All stores').')';

            $button_label = _('Delivery Notes %s');
            break;

    }

    $left_buttons = array();


    $right_buttons = array();

    if (isset($sections[$data['section']])) {
        $sections[$data['section']]['selected'] = true;
    }


    $_content = array(
        'sections_class' => $sections_class,
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => $title,
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search delivery notes')
        )

    );
    $smarty->assign('_content', $_content);

    $html = $smarty->fetch('navigation.tpl');

    return $html;

}


function get_order_navigation($data, $smarty, $user, $db, $account) {


    $object        = $data['_object'];
    $left_buttons  = array();
    $right_buttons = array();


    $search_placeholder = _('Search orders');


    switch ($data['parent']) {
        case 'account':
            $tab      = 'orders_server';
            $_section = 'orders';
            $sections = get_sections('orders_server');

            break;
        case 'customer':
            $tab      = 'customer.orders';
            $_section = 'customers';
            break;
        case 'store':


            switch ($object->get('Order Class')) {
                case 'Archived':
                    $tab = 'orders.archived';
                    break;
                case 'InProcess':

                    if($object->get('Order To Pay Amount')==0){
                        $tab = 'orders.in_process.paid';
                    }else{
                        $tab = 'orders.in_process.not_paid';
                    }


                    break;
                case 'InWebsite':
                    $tab = 'orders.website';
                    break;
                default:
                    exit("Error order don't have class");
                    break;
            }


            $_section = 'orders';
            break;
        case 'delivery_note':
            $tab      = 'delivery_note.orders';
            $_section = 'delivery_notes';
            break;
        case 'invoice':
            $tab      = 'invoice.orders';
            $_section = 'invoices';
            break;
    }


    if (isset($_SESSION['table_state'][$tab])) {
        $number_results  = $_SESSION['table_state'][$tab]['nr'];
        $start_from      = 0;
        $order           = $_SESSION['table_state'][$tab]['o'];
        $order_direction = ($_SESSION['table_state'][$tab]['od'] == 1 ? 'desc' : '');
        $f_value         = $_SESSION['table_state'][$tab]['f_value'];
        $parameters      = $_SESSION['table_state'][$tab];
    } else {

        $default                  = $user->get_tab_defaults($tab);
        $number_results           = $default['rpp'];
        $start_from               = 0;
        $order                    = $default['sort_key'];
        $order_direction          = ($default['sort_order'] == 1 ? 'desc' : '');
        $f_value                  = '';
        $parameters               = $default;
        $parameters['parent']     = $data['parent'];
        $parameters['parent_key'] = $data['parent_key'];
    }


    include_once 'prepare_table/'.$tab.'.ptble.php';

    $_order_field       = $order;
    $order              = preg_replace('/^.*\.`/', '', $order);
    $order              = preg_replace('/^`/', '', $order);
    $order              = preg_replace('/`$/', '', $order);
    $_order_field_value = $object->get($order);


    $prev_title = '';
    $next_title = '';
    $prev_key   = 0;
    $next_key   = 0;
    $sql        = trim($sql_totals." $wheref");


    if ($result2 = $db->query($sql)) {
        if ($row2 = $result2->fetch()) {


            if ($row2['num'] > 1) {


                $sql = sprintf(
                    "select `Order Public ID` object_name,O.`Order Key` as object_key from $table   $where $wheref
	                and ($_order_field < %s OR ($_order_field = %s AND O.`Order Key` < %d))  order by $_order_field desc , O.`Order Key` desc limit 1",

                    prepare_mysql($_order_field_value), prepare_mysql($_order_field_value), $object->id
                );

                if ($result = $db->query($sql)) {
                    if ($row = $result->fetch()) {
                        $prev_key   = $row['object_key'];
                        $prev_title = _("Order").' '.$row['object_name'].' ('.$row['object_key'].')';
                    }
                } else {
                    print_r($error_info = $db->errorInfo());
                    exit;
                }

                // print "$sql ";

                $sql = sprintf(
                    "select `Order Public ID` object_name,O.`Order Key` as object_key from $table   $where $wheref
	                and ($_order_field  > %s OR ($_order_field  = %s AND O.`Order Key` > %d))  order by $_order_field   , O.`Order Key`  limit 1", prepare_mysql($_order_field_value),
                    prepare_mysql($_order_field_value), $object->id
                );

                if ($result = $db->query($sql)) {
                    if ($row = $result->fetch()) {
                        $next_key   = $row['object_key'];
                        $next_title = _("Order").' '.$row['object_name'].' ('.$row['object_key'].')';

                    }
                } else {
                    print_r($error_info = $db->errorInfo());
                    exit;
                }
                //  print "$sql ";

                if ($order_direction == 'desc') {
                    $_tmp1      = $prev_key;
                    $_tmp2      = $prev_title;
                    $prev_key   = $next_key;
                    $prev_title = $next_title;
                    $next_key   = $_tmp1;
                    $next_title = $_tmp2;
                }


            }


        }
    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }


    if ($data['parent'] == 'customer') {


        $up_button = array(
            'icon'      => 'arrow-up',
            'title'     => _("Customer").' '.$object->get(
                    'Order Customer Name'
                ),
            'reference' => 'customers/'.$object->get(
                    'Order Store Key'
                ).'/'.$object->get('Order Customer Key')
        );

        if ($prev_key) {
            $left_buttons[] = array(
                'icon'      => 'arrow-left',
                'title'     => $prev_title,
                'reference' => 'customer/'.$object->get(
                        'Order Customer Key'
                    ).'/order/'.$prev_key
            );

        } else {
            $left_buttons[] = array(
                'icon'  => 'arrow-left disabled',
                'title' => '',
                'url'   => ''
            );

        }
        $left_buttons[] = $up_button;


        if ($next_key) {
            $left_buttons[] = array(
                'icon'      => 'arrow-right',
                'title'     => $next_title,
                'reference' => 'customer/'.$object->get(
                        'Order Customer Key'
                    ).'/order/'.$next_key
            );

        } else {
            $left_buttons[] = array(
                'icon'  => 'arrow-right disabled',
                'title' => '',
                'url'   => ''
            );

        }
        $sections           = get_sections(
            'customers', $object->get('Order Store Key')
        );
        $search_placeholder = _('Search customers');


    } elseif ($data['parent'] == 'store') {
        $store = new Store($data['parent_key']);


        switch ($object->get('Order Class')) {
            case 'Archived':
                $up_button = array(
                    'icon'      => 'arrow-up',
                    'title'     => _("Archived orders").' ('.$store->get('Store Code').')',
                    'reference' => 'orders/'.$data['parent_key']
                );

                break;
            case 'InProcess':
                $up_button = array(
                    'icon'      => 'arrow-up',
                    'title'     => _("Pending orders").' ('.$store->get('Store Code').')',
                    'reference' => 'orders/'.$data['parent_key'].'/flow'
                );

                break;
            case 'InWebsite':
                $up_button = array(
                    'icon'      => 'arrow-up',
                    'title'     => _("Orders in website").' ('.$store->get('Store Code').')',
                    'reference' => 'orders/'.$data['parent_key'].'/website'
                );

                break;
            default:
                exit("Error order don't have class");
                break;
        }


        if ($prev_key) {
            $left_buttons[] = array(
                'icon'      => 'arrow-left',
                'title'     => $prev_title,
                'reference' => 'orders/'.$data['parent_key'].'/'.$prev_key
            );

        } else {
            $left_buttons[] = array(
                'icon'  => 'arrow-left disabled',
                'title' => '',
                'url'   => ''
            );

        }
        $left_buttons[] = $up_button;


        if ($next_key) {
            $left_buttons[] = array(
                'icon'      => 'arrow-right',
                'title'     => $next_title,
                'reference' => 'orders/'.$data['parent_key'].'/'.$next_key
            );

        } else {
            $left_buttons[] = array(
                'icon'  => 'arrow-right disabled',
                'title' => '',
                'url'   => ''
            );

        }


        $sections = get_sections('orders', $object->get('Order Store Key'));

        $search_placeholder = _('Search orders');


    } elseif ($data['parent'] == 'delivery_note') {
        $delivery_note = new DeliveryNote($data['parent_key']);
        $up_button     = array(
            'icon'      => 'arrow-up',
            'title'     => _("Delivery Note").' ('.$delivery_note->get(
                    'Delivery Note ID'
                ).')',
            'reference' => '/delivery_notes/'.$delivery_note->get('Delivery Note Store Key').'/'.$data['parent_key']
        );

        if ($prev_key) {
            $left_buttons[] = array(
                'icon'      => 'arrow-left',
                'title'     => $prev_title,
                'reference' => 'order/'.$data['parent_key'].'/invoice/'.$prev_key
            );

        } else {
            $left_buttons[] = array(
                'icon'  => 'arrow-left disabled',
                'title' => '',
                'url'   => ''
            );

        }
        $left_buttons[] = $up_button;


        if ($next_key) {
            $left_buttons[] = array(
                'icon'      => 'arrow-right',
                'title'     => $next_title,
                'reference' => 'order/'.$data['parent_key'].'/invoice/'.$next_key
            );

        } else {
            $left_buttons[] = array(
                'icon'  => 'arrow-right disabled',
                'title' => '',
                'url'   => ''
            );

        }


        $sections           = get_sections(
            'delivery_notes', $delivery_note->get('Delivery Note Store Key')
        );
        $search_placeholder = _('Search delivery notes');


    } elseif ($data['parent'] == 'invoice') {
        $invoice   = new Invoice($data['parent_key']);
        $up_button = array(
            'icon'      => 'arrow-up',
            'title'     => _("Invoice").' ('.$invoice->get(
                    'Invoice Public ID'
                ).')',
            'reference' => '/delivery_notes/'.$invoice->get('Invoice Store Key').'/'.$data['parent_key']
        );

        if ($prev_key) {
            $left_buttons[] = array(
                'icon'      => 'arrow-left',
                'title'     => $prev_title,
                'reference' => 'order/'.$data['parent_key'].'/invoice/'.$prev_key
            );

        } else {
            $left_buttons[] = array(
                'icon'  => 'arrow-left disabled',
                'title' => '',
                'url'   => ''
            );

        }
        $left_buttons[] = $up_button;


        if ($next_key) {
            $left_buttons[] = array(
                'icon'      => 'arrow-right',
                'title'     => $next_title,
                'reference' => 'order/'.$data['parent_key'].'/invoice/'.$next_key
            );

        } else {
            $left_buttons[] = array(
                'icon'  => 'arrow-right disabled',
                'title' => '',
                'url'   => ''
            );

        }


        $sections = get_sections(
            'invoices', $invoice->get('Invoice Store Key')
        );

        $search_placeholder = _('Search invoices');

    }
    elseif ($data['parent'] == 'account') {


        $account = get_object('Account', 1);


        $up_button = array(
            'icon'      => 'arrow-up',
            'title'     => _("Orders").' ('._('All stores').')',
            'reference' => 'orders/all'
        );


        if ($prev_key) {
            $left_buttons[] = array(
                'icon'      => 'arrow-left',
                'title'     => $prev_title,
                'reference' => 'account/'.$data['parent_key'].'/order/'.$prev_key
            );

        } else {
            $left_buttons[] = array(
                'icon'  => 'arrow-left disabled',
                'title' => '',
                'url'   => ''
            );

        }
        $left_buttons[] = $up_button;


        if ($next_key) {
            $left_buttons[] = array(
                'icon'      => 'arrow-right',
                'title'     => $next_title,
                'reference' => 'account/'.$data['parent_key'].'/order/'.$next_key
            );

        } else {
            $left_buttons[] = array(
                'icon'  => 'arrow-right disabled',
                'title' => '',
                'url'   => ''
            );

        }

        //    print $data['parent'];


    }


    if (isset($sections[$_section])) {
        $sections[$_section]['selected'] = true;
    }






    $title = _('Order').' <span class="id">'.$object->get('Order Public ID').'</span>';


    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => $title,
        'search'         => array(
            'show'        => true,
            'placeholder' => $search_placeholder
        )

    );
    $smarty->assign('_content', $_content);


    $html = $smarty->fetch('navigation.tpl');

    return $html;

}


function get_delivery_note_navigation($data, $smarty, $user, $db, $account) {

    global $user, $smarty;

    $object        = $data['_object'];
    $left_buttons  = array();
    $right_buttons = array();

    if ($data['parent']) {

        switch ($data['parent']) {
            case 'customer':
                $tab      = 'customer.orders';
                $_section = 'customers';
                break;
            case 'store':
                $tab      = 'delivery_notes';
                $_section = 'delivery_notes';
                break;
            case 'order':
                $tab      = 'order.delivery_notes';
                $_section = 'delivery_notes';
                break;
            case 'invoice':
                $tab      = 'invoice.delivery_notes';
                $_section = 'invoices';
                break;
        }


        if (isset($_SESSION['table_state'][$tab])) {
            $number_results  = $_SESSION['table_state'][$tab]['nr'];
            $start_from      = 0;
            $order           = $_SESSION['table_state'][$tab]['o'];
            $order_direction = ($_SESSION['table_state'][$tab]['od'] == 1 ? 'desc' : '');
            $f_value         = $_SESSION['table_state'][$tab]['f_value'];
            $parameters      = $_SESSION['table_state'][$tab];
        } else {

            $default = $user->get_tab_defaults($tab);

            $number_results           = $default['rpp'];
            $start_from               = 0;
            $order                    = $default['sort_key'];
            $order_direction          = ($default['sort_order'] == 1 ? 'desc' : '');
            $f_value                  = '';
            $parameters               = $default;
            $parameters['parent']     = $data['parent'];
            $parameters['parent_key'] = $data['parent_key'];

        }


        include_once 'prepare_table/'.$tab.'.ptble.php';

        $_order_field       = $order;
        $order              = preg_replace('/^.*\.`/', '', $order);
        $order              = preg_replace('/^`/', '', $order);
        $order              = preg_replace('/`$/', '', $order);
        $_order_field_value = $object->get($order);


        $prev_title = '';
        $next_title = '';
        $prev_key   = 0;
        $next_key   = 0;
        $sql        = trim($sql_totals." $wheref");

        if ($result2 = $db->query($sql)) {
            if ($row2 = $result2->fetch()) {
                if ($row2['num'] > 1) {


                    $sql = sprintf(
                        "select `Delivery Note ID` object_name,D.`Delivery Note Key` as object_key from $table   $where $wheref
	                and ($_order_field < %s OR ($_order_field = %s AND D.`Delivery Note Key` < %d))  order by $_order_field desc , D.`Delivery Note Key` desc limit 1",

                        prepare_mysql($_order_field_value), prepare_mysql($_order_field_value), $object->id
                    );


                    //  print $sql;


                    if ($result = $db->query($sql)) {
                        if ($row = $result->fetch()) {
                            $prev_key   = $row['object_key'];
                            $prev_title = _("Delivery note").' '.$row['object_name'].' ('.$row['object_key'].')';
                        }
                    } else {
                        print_r($error_info = $db->errorInfo());
                        exit;
                    }


                    $sql = sprintf(
                        "select `Delivery Note ID` object_name,D.`Delivery Note Key` as object_key from $table   $where $wheref
	                and ($_order_field  > %s OR ($_order_field  = %s AND D.`Delivery Note Key` > %d))  order by $_order_field   , D.`Delivery Note Key`  limit 1", prepare_mysql($_order_field_value),
                        prepare_mysql($_order_field_value), $object->id
                    );

                    if ($result = $db->query($sql)) {
                        if ($row = $result->fetch()) {
                            $next_key   = $row['object_key'];
                            $next_title = _("Delivery note").' '.$row['object_name'].' ('.$row['object_key'].')';
                        }
                    } else {
                        print_r($error_info = $db->errorInfo());
                        exit;
                    }


                    if ($order_direction == 'desc') {
                        $_tmp1      = $prev_key;
                        $_tmp2      = $prev_title;
                        $prev_key   = $next_key;
                        $prev_title = $next_title;
                        $next_key   = $_tmp1;
                        $next_title = $_tmp2;
                    }


                }
            }
        } else {
            print_r($error_info = $db->errorInfo());
            exit;
        }


        if ($data['parent'] == 'customer') {


            $up_button = array(
                'icon'      => 'arrow-up',
                'title'     => _("Customer").' '.$object->get(
                        'Order Customer Name'
                    ),
                'reference' => 'customers/'.$object->get(
                        'Order Store Key'
                    ).'/'.$object->get('Order Customer Key')
            );

            if ($prev_key) {
                $left_buttons[] = array(
                    'icon'      => 'arrow-left',
                    'title'     => $prev_title,
                    'reference' => 'customer/'.$object->get(
                            'Order Customer Key'
                        ).'/order/'.$prev_key
                );

            } else {
                $left_buttons[] = array(
                    'icon'  => 'arrow-left disabled',
                    'title' => '',
                    'url'   => ''
                );

            }
            $left_buttons[] = $up_button;


            if ($next_key) {
                $left_buttons[] = array(
                    'icon'      => 'arrow-right',
                    'title'     => $next_title,
                    'reference' => 'customer/'.$object->get(
                            'Order Customer Key'
                        ).'/order/'.$next_key
                );

            } else {
                $left_buttons[] = array(
                    'icon'  => 'arrow-right disabled',
                    'title' => '',
                    'url'   => ''
                );

            }
            $sections = get_sections(
                'customers', $object->get('Order Store Key')
            );


        } elseif ($data['parent'] == 'store') {
            $store     = new Store($data['parent_key']);
            $up_button = array(
                'icon'      => 'arrow-up',
                'title'     => _("Delivery notes").' ('.$store->get(
                        'Store Code'
                    ).')',
                'reference' => 'delivery_notes/'.$data['parent_key']
            );

            if ($prev_key) {
                $left_buttons[] = array(
                    'icon'      => 'arrow-left',
                    'title'     => $prev_title,
                    'reference' => 'delivery_notes/'.$data['parent_key'].'/'.$prev_key
                );

            } else {
                $left_buttons[] = array(
                    'icon'  => 'arrow-left disabled',
                    'title' => '',
                    'url'   => ''
                );

            }
            $left_buttons[] = $up_button;


            if ($next_key) {
                $left_buttons[] = array(
                    'icon'      => 'arrow-right',
                    'title'     => $next_title,
                    'reference' => 'delivery_notes/'.$data['parent_key'].'/'.$next_key
                );

            } else {
                $left_buttons[] = array(
                    'icon'  => 'arrow-right disabled',
                    'title' => '',
                    'url'   => ''
                );

            }


            $sections = get_sections('delivery_notes', $data['parent_key']);


        } elseif ($data['parent'] == 'order') {
            $order     = new Order($data['parent_key']);
            $up_button = array(
                'icon'      => 'arrow-up',
                'title'     => _("Order").' ('.$order->get(
                        'Order Public ID'
                    ).')',
                'reference' => '/orders/'.$order->get(
                        'Order Store Key'
                    ).'/'.$data['parent_key']
            );

            if ($prev_key) {
                $left_buttons[] = array(
                    'icon'      => 'arrow-left',
                    'title'     => $prev_title,
                    'reference' => 'order/'.$data['parent_key'].'/delivery_note/'.$prev_key
                );

            } else {
                $left_buttons[] = array(
                    'icon'  => 'arrow-left disabled',
                    'title' => '',
                    'url'   => ''
                );

            }
            $left_buttons[] = $up_button;


            if ($next_key) {
                $left_buttons[] = array(
                    'icon'      => 'arrow-right',
                    'title'     => $next_title,
                    'reference' => 'order/'.$data['parent_key'].'/delivery_note/'.$next_key
                );

            } else {
                $left_buttons[] = array(
                    'icon'  => 'arrow-right disabled',
                    'title' => '',
                    'url'   => ''
                );

            }


            $sections = get_sections('orders', $order->get('Order Store Key'));


        } elseif ($data['parent'] == 'invoice') {
            $invoice   = new Invoice($data['parent_key']);
            $up_button = array(
                'icon'      => 'arrow-up',
                'title'     => _("Invoice").' ('.$invoice->get(
                        'Invoice Public ID'
                    ).')',
                'reference' => '/invoices/'.$invoice->get('Invoice Store Key').'/'.$data['parent_key']
            );

            if ($prev_key) {
                $left_buttons[] = array(
                    'icon'      => 'arrow-left',
                    'title'     => $prev_title,
                    'reference' => 'invoice/'.$data['parent_key'].'/delivery_note/'.$prev_key
                );

            } else {
                $left_buttons[] = array(
                    'icon'  => 'arrow-left disabled',
                    'title' => '',
                    'url'   => ''
                );

            }
            $left_buttons[] = $up_button;


            if ($next_key) {
                $left_buttons[] = array(
                    'icon'      => 'arrow-right',
                    'title'     => $next_title,
                    'reference' => 'invoice/'.$data['parent_key'].'/delivery_note/'.$next_key
                );

            } else {
                $left_buttons[] = array(
                    'icon'  => 'arrow-right disabled',
                    'title' => '',
                    'url'   => ''
                );

            }


            $sections = get_sections(
                'invoices', $invoice->get('Invoice Store Key')
            );


        }
    } else {
        $_section = 'staff';
        $sections = get_sections('delivery_notes', '');


    }


    if (isset($sections[$_section])) {
        $sections[$_section]['selected'] = true;
    }


    $title = _('Delivery Note').' <span class="id">'.$object->get(
            'Delivery Note ID'
        ).'</span>';


    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => $title,
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search orders')
        )

    );
    $smarty->assign('_content', $_content);


    $html = $smarty->fetch('navigation.tpl');



    return $html;

}


function get_invoice_navigation($data, $smarty, $user, $db, $account) {

    global $user, $smarty;

    $object        = $data['_object'];
    $left_buttons  = array();
    $right_buttons = array();

    if ($data['parent']) {

        switch ($data['parent']) {
            case 'customer':
                $tab      = 'customer.orders';
                $_section = 'customers';
                break;
            case 'store':
                $tab      = 'invoices';
                $_section = 'invoices';
                break;
            case 'order':
                $tab      = 'order.invoices';
                $_section = 'orders';
                break;
            case 'delivery_note':
                $tab      = 'delivery_note.invoices';
                $_section = 'delivery_notes';
                break;
        }

        if (isset($_SESSION['table_state'][$tab])) {
            $number_results  = $_SESSION['table_state'][$tab]['nr'];
            $start_from      = 0;
            $order           = $_SESSION['table_state'][$tab]['o'];
            $order_direction = ($_SESSION['table_state'][$tab]['od'] == 1 ? 'desc' : '');
            $f_value         = $_SESSION['table_state'][$tab]['f_value'];
            $parameters      = $_SESSION['table_state'][$tab];
        } else {

            $default = $user->get_tab_defaults($tab);

            $number_results           = $default['rpp'];
            $start_from               = 0;
            $order                    = $default['sort_key'];
            $order_direction          = ($default['sort_order'] == 1 ? 'desc' : '');
            $f_value                  = '';
            $parameters               = $default;
            $parameters['parent']     = $data['parent'];
            $parameters['parent_key'] = $data['parent_key'];

        }


        include_once 'prepare_table/'.$tab.'.ptble.php';
        $_order_field       = $order;
        $order              = preg_replace('/^.*\.`/', '', $order);
        $order              = preg_replace('/^`/', '', $order);
        $order              = preg_replace('/`$/', '', $order);
        $_order_field_value = $object->get($order);


        $prev_title = '';
        $next_title = '';
        $prev_key   = 0;
        $next_key   = 0;
        $sql        = trim($sql_totals." $wheref");

        if ($result2 = $db->query($sql)) {
            if ($row2 = $result2->fetch()) {
                if ($row2['num'] > 1) {


                    $sql = sprintf(
                        "select `Invoice Public ID` object_name,I.`Invoice Key` as object_key from $table   $where $wheref
	                and ($_order_field < %s OR ($_order_field = %s AND I.`Invoice Key` < %d))  order by $_order_field desc , I.`Invoice Key`desc limit 1",

                        prepare_mysql($_order_field_value), prepare_mysql($_order_field_value), $object->id
                    );

                    if ($result = $db->query($sql)) {
                        if ($row = $result->fetch()) {
                            $prev_key   = $row['object_key'];
                            $prev_title = _("Invoice").' '.$row['object_name'].' ('.$row['object_key'].')';

                        }
                    } else {
                        print_r($error_info = $db->errorInfo());
                        print $sql;
                        exit;
                    }


                    $sql = sprintf(
                        "select `Invoice Public ID` object_name,I.`Invoice Key` as object_key from $table   $where $wheref
	                and ($_order_field  > %s OR ($_order_field  = %s AND I.`Invoice Key` > %d))  order by $_order_field   , I.`Invoice Key`  limit 1", prepare_mysql($_order_field_value),
                        prepare_mysql($_order_field_value), $object->id
                    );

                    if ($result = $db->query($sql)) {
                        if ($row = $result->fetch()) {
                            $next_key   = $row['object_key'];
                            $next_title = _("Invoice").' '.$row['object_name'].' ('.$row['object_key'].')';

                        }
                    } else {
                        print_r($error_info = $db->errorInfo());
                        exit;
                    }


                    if ($order_direction == 'desc') {
                        $_tmp1      = $prev_key;
                        $_tmp2      = $prev_title;
                        $prev_key   = $next_key;
                        $prev_title = $next_title;
                        $next_key   = $_tmp1;
                        $next_title = $_tmp2;
                    }


                }
            }
        } else {
            print_r($error_info = $db->errorInfo());
            exit;
        }


        if ($data['parent'] == 'customer') {


            $up_button = array(
                'icon'      => 'arrow-up',
                'title'     => _("Customer").' '.$object->get(
                        'Order Customer Name'
                    ),
                'reference' => 'customers/'.$object->get(
                        'Order Store Key'
                    ).'/'.$object->get('Order Customer Key')
            );

            if ($prev_key) {
                $left_buttons[] = array(
                    'icon'      => 'arrow-left',
                    'title'     => $prev_title,
                    'reference' => 'customer/'.$object->get(
                            'Order Customer Key'
                        ).'/order/'.$prev_key
                );

            } else {
                $left_buttons[] = array(
                    'icon'  => 'arrow-left disabled',
                    'title' => '',
                    'url'   => ''
                );

            }
            $left_buttons[] = $up_button;


            if ($next_key) {
                $left_buttons[] = array(
                    'icon'      => 'arrow-right',
                    'title'     => $next_title,
                    'reference' => 'customer/'.$object->get(
                            'Order Customer Key'
                        ).'/order/'.$next_key
                );

            } else {
                $left_buttons[] = array(
                    'icon'  => 'arrow-right disabled',
                    'title' => '',
                    'url'   => ''
                );

            }
            $sections = get_sections(
                'customers', $object->get('Order Store Key')
            );


        } elseif ($data['parent'] == 'store') {
            $store     = new Store($data['parent_key']);
            $up_button = array(
                'icon'      => 'arrow-up',
                'title'     => _("Invoices").' ('.$store->get(
                        'Store Code'
                    ).')',
                'reference' => 'invoices/'.$data['parent_key']
            );

            if ($prev_key) {
                $left_buttons[] = array(
                    'icon'      => 'arrow-left',
                    'title'     => $prev_title,
                    'reference' => 'invoices/'.$data['parent_key'].'/'.$prev_key
                );

            } else {
                $left_buttons[] = array(
                    'icon'  => 'arrow-left disabled',
                    'title' => '',
                    'url'   => ''
                );

            }
            $left_buttons[] = $up_button;


            if ($next_key) {
                $left_buttons[] = array(
                    'icon'      => 'arrow-right',
                    'title'     => $next_title,
                    'reference' => 'invoices/'.$data['parent_key'].'/'.$next_key
                );

            } else {
                $left_buttons[] = array(
                    'icon'  => 'arrow-right disabled',
                    'title' => '',
                    'url'   => ''
                );

            }


            $sections = get_sections('orders', $data['parent_key']);


        } elseif ($data['parent'] == 'order') {
            $order     = new Order($data['parent_key']);
            $up_button = array(
                'icon'      => 'arrow-up',
                'title'     => _("Order").' ('.$order->get(
                        'Order Public ID'
                    ).')',
                'reference' => '/orders/'.$order->get(
                        'Order Store Key'
                    ).'/'.$data['parent_key']
            );

            if ($prev_key) {
                $left_buttons[] = array(
                    'icon'      => 'arrow-left',
                    'title'     => $prev_title,
                    'reference' => 'order/'.$data['parent_key'].'/invoice/'.$prev_key
                );

            } else {
                $left_buttons[] = array(
                    'icon'  => 'arrow-left disabled',
                    'title' => '',
                    'url'   => ''
                );

            }
            $left_buttons[] = $up_button;


            if ($next_key) {
                $left_buttons[] = array(
                    'icon'      => 'arrow-right',
                    'title'     => $next_title,
                    'reference' => 'order/'.$data['parent_key'].'/invoice/'.$next_key
                );

            } else {
                $left_buttons[] = array(
                    'icon'  => 'arrow-right disabled',
                    'title' => '',
                    'url'   => ''
                );

            }


            $sections = get_sections('orders', $order->get('Order Store Key'));


        } elseif ($data['parent'] == 'delivery_note') {
            $delivery_note = new DeliveryNote($data['parent_key']);
            $up_button     = array(
                'icon'      => 'arrow-up',
                'title'     => _("Delivery Note").' ('.$delivery_note->get(
                        'Delivery Note ID'
                    ).')',
                'reference' => '/delivery_notes/'.$delivery_note->get('Delivery Note Store Key').'/'.$data['parent_key']
            );

            if ($prev_key) {
                $left_buttons[] = array(
                    'icon'      => 'arrow-left',
                    'title'     => $prev_title,
                    'reference' => 'delivery_note/'.$data['parent_key'].'/invoice/'.$prev_key
                );

            } else {
                $left_buttons[] = array(
                    'icon'  => 'arrow-left disabled',
                    'title' => '',
                    'url'   => ''
                );

            }
            $left_buttons[] = $up_button;


            if ($next_key) {
                $left_buttons[] = array(
                    'icon'      => 'arrow-right',
                    'title'     => $next_title,
                    'reference' => 'delivery_note/'.$data['parent_key'].'/invoice/'.$next_key
                );

            } else {
                $left_buttons[] = array(
                    'icon'  => 'arrow-right disabled',
                    'title' => '',
                    'url'   => ''
                );

            }


            $sections = get_sections(
                'orders', $delivery_note->get('Delivery Note Store Key')
            );


        }
    } else {


    }


    if (isset($sections[$_section])) {
        $sections[$_section]['selected'] = true;
    }


    $title = _('Invoice').' <span class="id Invoice_Public_ID">'.$object->get('Invoice Public ID').'</span>';


    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => $title,
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search invoices')
        )

    );
    $smarty->assign('_content', $_content);


    $html = $smarty->fetch('navigation.tpl');

    return $html;

}


function get_pick_aid_navigation($data, $smarty, $user, $db, $account) {

    global $user, $smarty;


    $object = $data['_object'];

    $left_buttons  = array();
    $right_buttons = array();


    if ($data['parent'] == 'order') {
        $order     = new Order($data['parent_key']);
        $up_button = array(
            'icon'      => 'arrow-up',
            'title'     => _("Order").' ('.$order->get(
                    'Order Public ID'
                ).')',
            'reference' => '/orders/'.$order->get(
                    'Order Store Key'
                ).'/'.$data['parent_key']
        );


        $sections = get_sections('orders', $order->get('Order Store Key'));
        $_section = 'orders';


    } elseif ($data['parent'] == 'delivery_note') {
        $order     = new Order($data['parent_key']);
        $up_button = array(
            'icon'      => 'arrow-up',
            'title'     => _("Delivery Note").' ('.$order->get(
                    'Delivery Note ID'
                ).')',
            'reference' => '/delivery_notes/'.$order->get('Delivery Note Store Key').'/'.$data['parent_key']
        );


        $sections = get_sections('orders', $order->get('Order Store Key'));
        $_section = 'delivery_notes';


    } elseif ($data['parent'] == 'invoice') {
        $invoice   = new Invoice($data['parent_key']);
        $up_button = array(
            'icon'      => 'arrow-up',
            'title'     => _("Invoice").' ('.$invoice->get(
                    'Invoice Public ID'
                ).')',
            'reference' => '/invoice/'.$invoice->get(
                    'Invoice Store Key'
                ).'/'.$data['parent_key']
        );


        $sections = get_sections('orders', $invoice->get('Invoice Store Key'));

        $_section = 'invoices';

    }

    $left_buttons[] = $up_button;


    if (isset($sections[$_section])) {
        $sections[$_section]['selected'] = true;
    }


    $title = _('Picking aid').' <span class="id">'.$object->get(
            'Delivery Note ID'
        ).'</span>';


    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => $title,
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search orders')
        )

    );
    $smarty->assign('_content', $_content);


    $html = $smarty->fetch('navigation.tpl');

    return $html;

}


function get_payment_navigation($data, $smarty, $user, $db, $account) {

    global $user, $smarty;

    $object        = $data['_object'];
    $left_buttons  = array();
    $right_buttons = array();

    if ($data['parent']) {

        switch ($data['parent']) {

            case 'order':
                $tab      = 'payments';
                $_section = 'orders';
                break;
            case 'invoice':
                $tab      = 'invoice.payments';
                $_section = 'invoices';
                break;
        }

        if (isset($_SESSION['table_state'][$tab])) {
            $number_results  = $_SESSION['table_state'][$tab]['nr'];
            $start_from      = 0;
            $order           = $_SESSION['table_state'][$tab]['o'];
            $order_direction = ($_SESSION['table_state'][$tab]['od'] == 1 ? 'desc' : '');
            $f_value         = $_SESSION['table_state'][$tab]['f_value'];
            $parameters      = $_SESSION['table_state'][$tab];
        } else {

            $default = $user->get_tab_defaults($tab);

            $number_results           = $default['rpp'];
            $start_from               = 0;
            $order                    = $default['sort_key'];
            $order_direction          = ($default['sort_order'] == 1 ? 'desc' : '');
            $f_value                  = '';
            $parameters               = $default;
            $parameters['parent']     = $data['parent'];
            $parameters['parent_key'] = $data['parent_key'];

        }


        include_once 'prepare_table/'.$tab.'.ptble.php';
        $_order_field       = $order;
        $order              = preg_replace('/^.*\.`/', '', $order);
        $order              = preg_replace('/^`/', '', $order);
        $order              = preg_replace('/`$/', '', $order);
        $_order_field_value = $object->get($order);


        $prev_title = '';
        $next_title = '';
        $prev_key   = 0;
        $next_key   = 0;
        $sql        = trim($sql_totals." $wheref");

        if ($result2 = $db->query($sql)) {
            if ($row2 = $result2->fetch()) {
                if ($row2['num'] > 1) {


                    $sql = sprintf(
                        "select `Payment Transaction ID` object_name,P.`Payment Key` as object_key from $table   $where $wheref
	                and ($_order_field < %s OR ($_order_field = %s AND P.`Payment Key` < %d))  order by $_order_field desc , P.`Payment Key` desc limit 1",

                        prepare_mysql($_order_field_value), prepare_mysql($_order_field_value), $object->id
                    );



                    if ($result = $db->query($sql)) {
                        if ($row = $result->fetch()) {
                            $prev_key   = $row['object_key'];
                            $prev_title = _("Payment").' '.$row['object_name'].' ('.$row['object_key'].')';

                        }
                    } else {
                        print $sql;

                        print_r($error_info = $db->errorInfo());
                        exit;
                    }


                    $sql = sprintf(
                        "select `Payment Transaction ID` object_name,P.`Payment Key` as object_key from $table   $where $wheref
	                and ($_order_field  > %s OR ($_order_field  = %s AND P.`Payment Key` > %d))  order by $_order_field   , P.`Payment Key`  limit 1", prepare_mysql($_order_field_value),
                        prepare_mysql($_order_field_value), $object->id
                    );

                    if ($result = $db->query($sql)) {
                        if ($row = $result->fetch()) {
                            $next_key   = $row['object_key'];
                            $next_title = _("Payment").' '.$row['object_name'].' ('.$row['object_key'].')';

                        }
                    } else {
                        print_r($error_info = $db->errorInfo());
                        exit;
                    }


                    if ($order_direction == 'desc') {
                        $_tmp1      = $prev_key;
                        $_tmp2      = $prev_title;
                        $prev_key   = $next_key;
                        $prev_title = $next_title;
                        $next_key   = $_tmp1;
                        $next_title = $_tmp2;
                    }


                }
            }
        } else {
            print_r($error_info = $db->errorInfo());
            exit;
        }

        if ($data['parent'] == 'order') {
            $order     = new Order($data['parent_key']);
            $up_button = array(
                'icon'      => 'arrow-up',
                'title'     => _("Order").' ('.$order->get('Order Public ID').')',
                'reference' => '/orders/'.$order->get('Order Store Key').'/'.$data['parent_key']
            );

            if ($prev_key) {
                $left_buttons[] = array(
                    'icon'      => 'arrow-left',
                    'title'     => $prev_title,
                    'reference' => 'order/'.$data['parent_key'].'/payment/'.$prev_key
                );

            } else {
                $left_buttons[] = array(
                    'icon'  => 'arrow-left disabled',
                    'title' => '',
                    'url'   => ''
                );

            }
            $left_buttons[] = $up_button;


            if ($next_key) {
                $left_buttons[] = array(
                    'icon'      => 'arrow-right',
                    'title'     => $next_title,
                    'reference' => 'order/'.$data['parent_key'].'/payment/'.$next_key
                );

            } else {
                $left_buttons[] = array(
                    'icon'  => 'arrow-right disabled',
                    'title' => '',
                    'url'   => ''
                );

            }


            $sections = get_sections('orders', $order->get('Order Store Key'));


        } elseif ($data['parent'] == 'invoice') {
            $delivery_note = new DeliveryNote($data['parent_key']);
            $up_button     = array(
                'icon'      => 'arrow-up',
                'title'     => _("Invoice").' ('.$delivery_note->get(
                        'Invoice Public ID'
                    ).')',
                'reference' => '/invoices/'.$delivery_note->get('Delivery Note Store Key').'/'.$data['parent_key']
            );

            if ($prev_key) {
                $left_buttons[] = array(
                    'icon'      => 'arrow-left',
                    'title'     => $prev_title,
                    'reference' => 'invoice/'.$data['parent_key'].'/payment/'.$prev_key
                );

            } else {
                $left_buttons[] = array(
                    'icon'  => 'arrow-left disabled',
                    'title' => '',
                    'url'   => ''
                );

            }
            $left_buttons[] = $up_button;


            if ($next_key) {
                $left_buttons[] = array(
                    'icon'      => 'arrow-right',
                    'title'     => $next_title,
                    'reference' => 'invoices/'.$data['parent_key'].'/payment/'.$next_key
                );

            } else {
                $left_buttons[] = array(
                    'icon'  => 'arrow-right disabled',
                    'title' => '',
                    'url'   => ''
                );

            }


            $sections = get_sections(
                'orders', $delivery_note->get('Delivery Note Store Key')
            );


        }
    } else {


    }


    if (isset($sections[$_section])) {
        $sections[$_section]['selected'] = true;
    }


    $title = _('Payment').' <span class="id">'.$object->get('Payment Transaction ID').'</span>';


    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => $title,
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search orders')
        )

    );
    $smarty->assign('_content', $_content);


    $html = $smarty->fetch('navigation.tpl');

    return $html;

}


?>
