<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 3 February 2019 at 16:01:04 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2019, Inikoo

 Version 3.0
*/


function get_invoice_navigation($data, $smarty, $user, $db, $account) {

    $search_placeholder= _('Search invoices');


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
            case 'account':
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
	                and ($_order_field  > %s OR ($_order_field  = %s AND I.`Invoice Key` > %d))  order by $_order_field   , I.`Invoice Key`  limit 1", prepare_mysql($_order_field_value), prepare_mysql($_order_field_value), $object->id
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
                    'reference' => 'customer/'.$object->get('Order Customer Key').'/order/'.$next_key
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
            $up_button = array(
                'icon'      => 'arrow-up',
                'title'     => _("Invoices").' ('.$data['_parent']->get('Code').')',
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


            $sections = get_sections('accounting', $data['parent_key']);
            $search_placeholder.=' '.$data['_parent']->get('Code');


        } elseif ($data['parent'] == 'account') {

            $up_button = array(
                'icon'      => 'arrow-up',
                'title'     => _("Invoices").' ('._('All stores').')',
                'reference' => 'invoices/all'
            );

            if ($prev_key) {
                $left_buttons[] = array(
                    'icon'      => 'arrow-left',
                    'title'     => $prev_title,
                    'reference' => 'invoice/'.$prev_key
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
                    'reference' => 'invoice/'.$next_key
                );

            } else {
                $left_buttons[] = array(
                    'icon'  => 'arrow-right disabled',
                    'title' => '',
                    'url'   => ''
                );

            }


            $sections = get_sections('accounting_server', '');


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

    if ($object->get('Invoice Type') == 'Refund') {

        if($object->get('Invoice Tax Type')=='Tax_Only'){
            $title = _('Tax refund').' <span class="id Invoice_Public_ID">'.$object->get('Invoice Public ID').'</span>';

        }else{
            $title = _('Refund').' <span class="id Invoice_Public_ID">'.$object->get('Invoice Public ID').'</span>';

        }


    } else {
        $title = _('Invoice').' <span class="id Invoice_Public_ID">'.$object->get('Invoice Public ID').'</span>';

    }


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


function get_deleted_invoice_navigation($data, $smarty, $user, $db, $account) {

    $search_placeholder= _('Search invoices');


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
            case 'account':
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





        $prev_title = '';
        $next_title = '';
        $prev_key   = 0;
        $next_key   = 0;




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
                    'reference' => 'customer/'.$object->get('Order Customer Key').'/order/'.$next_key
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


        }
        elseif ($data['parent'] == 'store') {
            $up_button = array(
                'icon'      => 'arrow-up',
                'title'     => _("Invoices").' ('.$data['_parent']->get('Code').')',
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


            $sections = get_sections('accounting', $data['parent_key']);
            $search_placeholder.=' '.$data['_parent']->get('Code');


        }
        elseif ($data['parent'] == 'account') {

            $up_button = array(
                'icon'      => 'arrow-up',
                'title'     => _("Invoices").' ('._('All stores').')',
                'reference' => 'invoices/all'
            );

            if ($prev_key) {
                $left_buttons[] = array(
                    'icon'      => 'arrow-left',
                    'title'     => $prev_title,
                    'reference' => 'invoice/'.$prev_key
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
                    'reference' => 'invoice/'.$next_key
                );

            } else {
                $left_buttons[] = array(
                    'icon'  => 'arrow-right disabled',
                    'title' => '',
                    'url'   => ''
                );

            }


            $sections = get_sections('accounting_server', '');


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

    if ($object->get('Invoice Type') == 'Refund') {
        $title = _('Deleted refund').' <span class="strong error Invoice_Public_ID">'.$object->get('Invoice Public ID').'</span>';

    } else {
        $title = _('Deleted invoice').' <span class="strong error Invoice_Public_ID">'.$object->get('Invoice Public ID').'</span>';

    }


    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => '<span class="error">'.$title.'</span>',
        'search'         => array(
            'show'        => true,
            'placeholder' => $search_placeholder
        )

    );
    $smarty->assign('_content', $_content);


    $html = $smarty->fetch('navigation.tpl');

    return $html;

}
