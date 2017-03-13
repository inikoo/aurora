<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 19 August 2016 at 15:35:15 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/



function get_parts_navigation($data, $smarty, $user, $db, $account) {

    $left_buttons = array();

    $right_buttons = array();
    $sections      = get_sections('agent_client_deliveries', '');

    if (isset($sections[$data['section']])) {
        $sections[$data['section']]['selected'] = true;
    }

    $title = _("Products");

    $_content = array(

        'sections_class' => '',
        'sections'       => $sections,

        'left_buttons'  => $left_buttons,
        'right_buttons' => $right_buttons,
        'title'         => $title,
        'search'        => array(
            'show'        => true,
            'placeholder' => _('Search')
        )

    );
    $smarty->assign('_content', $_content);

    $html = $smarty->fetch('navigation.tpl');

    return $html;

}

function get_deliveries_navigation($data, $smarty, $user, $db, $account) {

    $block_view   = $data['section'];
    $left_buttons = array();

    $right_buttons = array();
    $sections      = get_sections('agent_client_deliveries', '');

    if (isset($sections[$data['section']])) {
        $sections[$data['section']]['selected'] = true;
    }

    $title = _("Deliveries");

    $_content = array(

        'sections_class' => '',
        'sections'       => $sections,

        'left_buttons'  => $left_buttons,
        'right_buttons' => $right_buttons,
        'title'         => $title,
        'search'        => array(
            'show'        => true,
            'placeholder' => _('Search')
        )

    );
    $smarty->assign('_content', $_content);

    $html = $smarty->fetch('navigation.tpl');

    return $html;

}

function get_agent_client_orders_navigation($data, $smarty, $user, $db, $account) {

    $block_view   = $data['section'];
    $left_buttons = array();

    $right_buttons = array();
    $sections      = get_sections('agent_client_orders', '');

    if (isset($sections[$data['section']])) {
        $sections[$data['section']]['selected'] = true;
    }

    $title = _("Client's orders");

    $_content = array(

        'sections_class' => '',
        'sections'       => $sections,

        'left_buttons'  => $left_buttons,
        'right_buttons' => $right_buttons,
        'title'         => $title,
        'search'        => array(
            'show'        => true,
            'placeholder' => _('Search')
        )

    );
    $smarty->assign('_content', $_content);

    $html = $smarty->fetch('navigation.tpl');

    return $html;

}


function get_agent_navigation($data, $smarty, $user, $db, $account) {

    $block_view   = $data['section'];
    $left_buttons = array();

    $right_buttons = array();
    $sections      = get_sections('agent_profile', '');

    if (isset($sections[$data['section']])) {
        $sections[$data['section']]['selected'] = true;
    }

    $title
        = '<i class="fa fa-user-secret" aria-hidden="true"></i> <span class="id Agent_Code">'.$data['_object']->get('Code').'</span>';

    $_content = array(

        'sections_class' => '',
        'sections'       => $sections,

        'left_buttons'  => $left_buttons,
        'right_buttons' => $right_buttons,
        'title'         => $title,
        'search'        => array(
            'show'        => true,
            'placeholder' => _('Search')
        )

    );
    $smarty->assign('_content', $_content);

    $html = $smarty->fetch('navigation.tpl');

    return $html;

}


function get_suppliers_navigation($data, $smarty, $user, $db, $account) {

    $block_view   = $data['section'];
    $left_buttons = array();

    $right_buttons = array();
    $sections      = get_sections('agent_suppliers', '');

    if (isset($sections[$data['section']])) {
        $sections[$data['section']]['selected'] = true;
    }

    $title = _('Suppliers');

    $_content = array(

        'sections_class' => '',
        'sections'       => $sections,

        'left_buttons'  => $left_buttons,
        'right_buttons' => $right_buttons,
        'title'         => $title,
        'search'        => array(
            'show'        => true,
            'placeholder' => _('Search')
        )

    );
    $smarty->assign('_content', $_content);

    $html = $smarty->fetch('navigation.tpl');

    return $html;

}


function get_supplier_navigation($data, $smarty, $user, $db, $account) {

    $supplier = $data['_object'];


    $block_view = $data['section'];


    $left_buttons  = array();
    $right_buttons = array();


    switch ($data['parent']) {
        case 'agent':
            $tab      = 'agent.suppliers';
            $_section = 'suppliers';

            break;
        default:
            return '';

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
    $_order_field_value = $supplier->get($order);


    $prev_title = '';
    $next_title = '';
    $prev_key   = 0;
    $next_key   = 0;
    $sql        = trim($sql_totals." $wheref");


    if ($data['parent'] == 'agent') {

        if ($result2 = $db->query($sql)) {
            if ($row2 = $result2->fetch()) {
                if ($row2['num'] > 1) {


                    $sql = sprintf(
                        "select `Supplier Name` object_name,S.`Supplier Key` as object_key from %s
	                and ($_order_field < %s OR ($_order_field = %s AND S.`Supplier Key` < %d))  order by $_order_field desc , S.`Supplier Key` desc limit 1", "$table $where $wheref",
                        prepare_mysql($_order_field_value), prepare_mysql($_order_field_value), $supplier->id
                    );

                    if ($result = $db->query($sql)) {
                        if ($row = $result->fetch()) {
                            $prev_key   = $row['object_key'];
                            $prev_title = _("Supplier").' '.$row['object_name'].' ('.$row['object_key'].')';
                        }
                    } else {
                        print_r($error_info = $db->errorInfo());
                        exit;
                    }


                    $sql = sprintf(
                        "select `Supplier Name` object_name,S.`Supplier Key` as object_key from %s
	                and ($_order_field  > %s OR ($_order_field  = %s AND S.`Supplier Key` > %d))  order by $_order_field   , S.`Supplier Key`  limit 1", "$table $where $wheref",
                        prepare_mysql($_order_field_value), prepare_mysql($_order_field_value), $supplier->id
                    );

                    if ($result = $db->query($sql)) {
                        if ($row = $result->fetch()) {
                            $next_key   = $row['object_key'];
                            $next_title = _("Supplier").' '.$row['object_name'].' ('.$row['object_key'].')';

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


                    $up_button = array(
                        'icon'      => 'arrow-up',
                        'title'     => _("Suppliers"),
                        'reference' => 'suppliers'
                    );

                    if ($prev_key) {
                        $left_buttons[] = array(
                            'icon'      => 'arrow-left',
                            'title'     => $prev_title,
                            'reference' => 'supplier/'.$prev_key
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
                            'reference' => 'supplier/'.$next_key
                        );

                    } else {
                        $left_buttons[]
                            = array(
                            'icon'  => 'arrow-right disabled',
                            'title' => '',
                            'url'   => ''
                        );

                    }


                } else {
                    $up_button = array(
                        'icon'      => 'arrow-up',
                        'title'     => _("Suppliers"),
                        'reference' => 'suppliers'
                    );


                    $left_buttons[] = $up_button;

                }
            }
        } else {
            print_r($error_info = $db->errorInfo());
            exit;
        }


    }


    $sections = get_sections('agent_suppliers', '');


    if (isset($sections[$_section])) {
        $sections[$_section]['selected'] = true;
    }


    if ($supplier->get('Supplier Type') == 'Archived') {
        $title
            = ' <span class="disabled padding_right_5"><i class="fa fa-archive" aria-hidden="true"></i>  '._('Archived').'</span> <span class="id disabled Supplier_Code">'.$supplier->get('Code')
            .'</span>';


    } else {

        $title = '<span class="id Supplier_Code">'.$supplier->get('Code').'</span>';

    }


    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => $title,
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search')
        )

    );
    $smarty->assign('_content', $_content);


    $html = $smarty->fetch('navigation.tpl');

    return $html;

}

function get_supplier_part_navigation($data, $smarty, $user, $db, $account) {

    $block_view = $data['section'];

    $left_buttons  = array();
    $right_buttons = array();

    if ($data['parent']) {

        switch ($data['parent']) {
            case 'supplier':
                $tab      = 'supplier.supplier_parts';
                $_section = 'suppliers';
                break;

            default:
                return '';

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

        $_order_field = $order;
        $order        = preg_replace('/^.*\.`/', '', $order);
        $order        = preg_replace('/^`/', '', $order);
        $order        = preg_replace('/`$/', '', $order);


        $_order_field_value = $data['_object']->get($order);


        $prev_title = '';
        $next_title = '';
        $prev_key   = 0;
        $next_key   = 0;
        $sql        = trim($sql_totals." $wheref");


        if ($data['parent'] == 'supplier') {

            if ($result2 = $db->query($sql)) {
                if ($row2 = $result2->fetch()) {


                    if ($row2['num'] > 1) {


                        $sql = sprintf(
                            "select `Supplier Part Reference` object_name,`Supplier Part Key` as object_key from %s and ($_order_field < %s OR ($_order_field = %s AND `Supplier Part Key` < %d))  order by $_order_field desc , `Supplier Part Key` desc limit 1",
                            "$table $where $wheref", prepare_mysql($_order_field_value), prepare_mysql($_order_field_value), $data['key']
                        );
                        //print $sql;
                        if ($result = $db->query($sql)) {
                            if ($row = $result->fetch()) {

                                $prev_key   = $row['object_key'];
                                $prev_title = _("Supplier").' '.$row['object_name'].' ('.$row['object_key'].')';
                            }
                        } else {
                            print_r($error_info = $db->errorInfo());
                            exit;
                        }


                        $sql = sprintf(
                            "select `Supplier Part Reference` object_name,`Supplier Part Key` as object_key from %s and ($_order_field  > %s OR ($_order_field  = %s AND `Supplier Part Key` > %d))  order by $_order_field   , `Supplier Part Key`  limit 1",
                            "$table $where $wheref", prepare_mysql($_order_field_value), prepare_mysql($_order_field_value), $data['key']
                        );

                        if ($result = $db->query($sql)) {
                            if ($row = $result->fetch()) {
                                $next_key   = $row['object_key'];
                                $next_title = _("Supplier").' '.$row['object_name'].' ('.$row['object_key'].')';

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


                        $up_button = array(
                            'icon'      => 'arrow-up',
                            'title'     => _("Supplier").' '.$data['_parent']->get('Code'),
                            'reference' => 'supplier/'.$data['_parent']->id
                        );

                        if ($prev_key) {
                            $left_buttons[] = array(
                                'icon'      => 'arrow-left',
                                'title'     => $prev_title,
                                'reference' => 'supplier/'.$data['_parent']->id.'/part/'.$prev_key
                            );

                        } else {
                            $left_buttons[]
                                = array(
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
                                'reference' => 'supplier/'.$data['_parent']->id.'/part/'.$next_key
                            );

                        } else {
                            $left_buttons[]
                                = array(
                                'icon'  => 'arrow-right disabled',
                                'title' => '',
                                'url'   => ''
                            );

                        }


                    }

                }
            } else {
                print_r($error_info = $db->errorInfo());
                exit;
            }


        }

    }

    $sections = get_sections('agent_suppliers', '');


    if (isset($sections[$_section])) {
        $sections[$_section]['selected'] = true;
    }


    $title = _("Supplier's product").' <span class="id Supplier_Part_Reference">'.$data['_object']->get('Reference').'</span>';


    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => $title,
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search suppliers')
        )

    );
    $smarty->assign('_content', $_content);


    $html = $smarty->fetch('navigation.tpl');

    return $html;

}

function get_agent_client_order_navigation($data, $smarty, $user, $db, $account) {


    $object        = $data['_object'];
    $left_buttons  = array();
    $right_buttons = array();

    if ($data['parent']) {

        switch ($data['parent']) {

            case 'account':
                $tab      = 'agent.client_orders';
                $_section = 'agent_orders';
                break;
            default:
                return;

        }


        if (isset($_SESSION['table_state'][$tab])) {
            $number_results  = $_SESSION['table_state'][$tab]['nr'];
            $start_from      = 0;
            $order           = $_SESSION['table_state'][$tab]['o'];
            $order_direction = ($_SESSION['table_state'][$tab]['od'] == 1 ? 'desc' : '');
            $f_value         = $_SESSION['table_state'][$tab]['f_value'];
            $parameters      = $_SESSION['table_state'][$tab];

        } else {

            $default         = $user->get_tab_defaults($tab);
            $number_results  = $default['rpp'];
            $start_from      = 0;
            $order           = $default['sort_key'];
            $order_direction = ($default['sort_order'] == 1 ? 'desc' : '');
            $f_value         = '';
            $parameters      = $default;

        }

        $parameters['parent']     = $data['parent'];
        $parameters['parent_key'] = $data['parent_key'];


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
                        "select `Purchase Order Public ID` object_name,O.`Purchase Order Key` as object_key from %s
	                and ($_order_field < %s OR ($_order_field = %s AND O.`Purchase Order Key` < %d))  order by $_order_field desc , O.`Purchase Order Key` desc limit 1", "$table $where $wheref",
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


                    $sql = sprintf(
                        "select `Purchase Order Public ID` object_name,O.`Purchase Order Key` as object_key from %s
	                and ($_order_field  > %s OR ($_order_field  = %s AND O.`Purchase Order Key` > %d))  order by $_order_field   , O.`Purchase Order Key`  limit 1", "$table $where $wheref",
                        prepare_mysql($_order_field_value), prepare_mysql($_order_field_value), $object->id
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


        if ($data['parent'] == 'supplier') {


            $up_button = array(
                'icon'      => 'arrow-up',
                'title'     => _("Supplier").' '.$data['_parent']->get('Code'),
                'reference' => 'supplier/'.$data['parent_key']
            );

            if ($prev_key) {
                $left_buttons[] = array(
                    'icon'      => 'arrow-left',
                    'title'     => $prev_title,
                    'reference' => 'supplier/'.$data['parent_key'].'/order/'.$prev_key
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
                    'reference' => 'supplier/'.$data['parent_key'].'/order/'.$next_key
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


        } elseif ($data['parent'] == 'account') {
            $up_button = array(
                'icon'      => 'arrow-up',
                'title'     => _("Purchase orders"),
                'reference' => 'suppliers/orders'
            );

            if ($prev_key) {
                $left_buttons[] = array(
                    'icon'      => 'arrow-left',
                    'title'     => $prev_title,
                    'reference' => 'suppliers/order/'.$prev_key
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
                    'reference' => 'suppliers/order/'.$next_key
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
    } else {
        exit;

    }

    $sections           = get_sections('agent_client_orders', '');
    $search_placeholder = _('Search');

    if (isset($sections[$_section])) {
        $sections[$_section]['selected'] = true;
    }


    $right_buttons[] = array(
        'icon'  => 'share-alt',
        'title' => '{t}Share{/t}'
    );

    $right_buttons[] = array(
        'icon'  => 'print',
        'title' => '{t}Print{/t}'
    );


    $title = _("Client's order").' <span class="Purchase_Order_Public_ID id">'.$object->get('Public ID').'</span>';


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

function get_new_supplier_attachment_navigation($data, $smarty, $user, $db) {


    $left_buttons  = array();
    $right_buttons = array();


    $sections = get_sections('agent_suppliers', '');

    $_section = 'suppliers';
    if (isset($sections[$_section])) {
        $sections[$_section]['selected'] = true;
    }

    $supplier = $data['_parent'];

    $up_button = array(
        'icon'      => 'arrow-up',
        'title'     => sprintf(
            _('Supplier: %s'), $supplier->get('Code')
        ),
        'reference' => 'supplier/'.$data['parent_key']
    );


    $left_buttons[] = $up_button;


    $title = '<span>'.sprintf(
            _('New attachment for %s'), '<span onClick="change_view(\'supplier/'.$supplier->id.'\')" class="button id">'.$supplier->get('Name').'</span>'
        ).'</span>';


    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => $title,
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search')
        )

    );
    $smarty->assign('_content', $_content);


    $html = $smarty->fetch('navigation.tpl');

    return $html;

}

function get_supplier_attachment_navigation($data, $smarty, $user, $db) {


    $left_buttons  = array();
    $right_buttons = array();


    $sections = get_sections('agent_suppliers', '');

    $_section = 'suppliers';
    if (isset($sections[$_section])) {
        $sections[$_section]['selected'] = true;
    }

    $supplier = $data['_parent'];


    $up_button = array(
        'icon'      => 'arrow-up',
        'title'     => sprintf(
            _('Supplier: %s'), $supplier->get('Code')
        ),
        'reference' => 'supplier/'.$data['parent_key']
    );

    $right_buttons[] = array(
        'icon'  => 'download',
        'title' => _('Download'),
        'id'    => 'download_button'
    );
    $left_buttons[]  = $up_button;

    $title = _('Attachment').' <span class="id Attachment_Caption">'.$data['_object']->get('Caption').'</span>';


    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => $title,
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search')
        )

    );
    $smarty->assign('_content', $_content);


    $html = $smarty->fetch('navigation.tpl');

    return $html;

}


function get_agent_delivery_navigation($data, $smarty, $user, $db, $account) {


    $object        = $data['_object'];
    $left_buttons  = array();
    $right_buttons = array();

    if ($data['parent']) {

        switch ($data['parent']) {

            case 'account':
                $tab      = 'agent.deliveries';
                $_section = 'deliveries';
                break;
            default:
                return;

        }


        if (isset($_SESSION['table_state'][$tab])) {
            $number_results  = $_SESSION['table_state'][$tab]['nr'];
            $start_from      = 0;
            $order           = $_SESSION['table_state'][$tab]['o'];
            $order_direction = ($_SESSION['table_state'][$tab]['od'] == 1 ? 'desc' : '');
            $f_value         = $_SESSION['table_state'][$tab]['f_value'];
            $parameters      = $_SESSION['table_state'][$tab];

        } else {

            $default         = $user->get_tab_defaults($tab);
            $number_results  = $default['rpp'];
            $start_from      = 0;
            $order           = $default['sort_key'];
            $order_direction = ($default['sort_order'] == 1 ? 'desc' : '');
            $f_value         = '';
            $parameters      = $default;

        }

        $parameters['parent']     = $data['parent'];
        $parameters['parent_key'] = $data['parent_key'];


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
                        "select `Supplier Delivery Public ID` object_name,O.`Supplier Delivery Key` as object_key from %s
	                and ($_order_field < %s OR ($_order_field = %s AND O.`Supplier Delivery Key` < %d))  order by $_order_field desc , O.`Supplier Delivery Key` desc limit 1", "$table $where $wheref",
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


                    $sql = sprintf(
                        "select `Supplier Delivery Public ID` object_name,O.`Supplier Delivery Key` as object_key from %s
	                and ($_order_field  > %s OR ($_order_field  = %s AND O.`Supplier Delivery Key` > %d))  order by $_order_field   , O.`Supplier Delivery Key`  limit 1", "$table $where $wheref",
                        prepare_mysql($_order_field_value), prepare_mysql($_order_field_value), $object->id
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


        if ($data['parent'] == 'supplier') {


            $up_button = array(
                'icon'      => 'arrow-up',
                'title'     => _("Supplier").' '.$data['_parent']->get('Code'),
                'reference' => 'supplier/'.$data['parent_key']
            );

            if ($prev_key) {
                $left_buttons[] = array(
                    'icon'      => 'arrow-left',
                    'title'     => $prev_title,
                    'reference' => 'supplier/'.$data['parent_key'].'/order/'.$prev_key
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
                    'reference' => 'supplier/'.$data['parent_key'].'/order/'.$next_key
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


        }
        elseif ($data['parent'] == 'account') {
            $up_button = array(
                'icon'      => 'arrow-up',
                'title'     => _("Deliveries"),
                'reference' => 'deliveries'
            );

            if ($prev_key) {
                $left_buttons[] = array(
                    'icon'      => 'arrow-left',
                    'title'     => $prev_title,
                    'reference' => 'agent_delivery/'.$prev_key
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
                    'reference' => 'agent_delivery/'.$next_key
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


        }

    } else {
        exit;

    }

    $sections           = get_sections('agent_client_deliveries', '');
    $search_placeholder = _('Search');

    if (isset($sections[$_section])) {
        $sections[$_section]['selected'] = true;
    }


    $right_buttons[] = array(
        'icon'  => 'share-alt',
        'title' => '{t}Share{/t}'
    );

    $right_buttons[] = array(
        'icon'  => 'print',
        'title' => '{t}Print{/t}'
    );


    $title = _("Delivery").' <span class="Supplier_Delivery_Public_ID id">'.$object->get('Public ID').'</span>';


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

?>
