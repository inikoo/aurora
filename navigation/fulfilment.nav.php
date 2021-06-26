<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 14 Jun 2021 02:03  +0800, Kuala Lumpur Malaysia

 Copyright (c) 2015, Inikoo

 Version 3.0
*/




function get_dashboard_navigation($data, $smarty, $user, $db) {


    $left_buttons  = array();
    $right_buttons = array();
    $sections      = get_sections($data['module'], $data['key']);

    if (isset($sections[$data['section']])) {
        $sections[$data['section']]['selected'] = true;
    }


    $_content = array(

        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => _('Fulfilment dashboard'),
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search fulfilment')
        )

    );
    $smarty->assign('_content', $_content);

    return array(
        $_content['search'],
        $smarty->fetch('top_menu.tpl'),
        $smarty->fetch('au_header.tpl')
    );

}





function get_locations_navigation($data, $smarty, $user, $db, $account) {


    switch ($data['parent']) {
        case 'warehouse':
            $warehouse = new Warehouse($data['parent_key']);
            break;
        default:
            break;
    }


    $left_buttons = array();


    $right_buttons = array();
    $sections      = get_sections($data['module'], $warehouse->id);

    if (isset($sections[$data['section']])) {
        $sections[$data['section']]['selected'] = true;
    }


    $_content = array(

        'sections_class' => '',
        'sections'       => $sections,

        'left_buttons'  => $left_buttons,
        'right_buttons' => $right_buttons,
        'title'         => _('Fulfilment locations').' <span class="id small hide">('.$warehouse->get('Warehouse Code').')</span>',
        'search'        => array(
            'show'        => true,
            'placeholder' => _('Search fulfilment')
        )

    );
    $smarty->assign('_content', $_content);

    return array(
        $_content['search'],
        $smarty->fetch('top_menu.tpl'),
        $smarty->fetch('au_header.tpl')
    );

}


function get_location_navigation($data, $smarty, $user, $db, $account) {


    $warehouse = $data['warehouse'];
    $object    = $data['_object'];

    $left_buttons = array();


    switch ($data['parent']) {
        case 'warehouse':
            $tab      = 'warehouse.locations';
            $_section = 'locations';
            break;
        case 'warehouse_area':
            $tab      = 'warehouse_area.locations';
            $_section = 'locations';
            break;
        default:

            exit('location navigation no parent');
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
                    "select `Location Code` object_name,L.`Location Key` as object_key from %s %s %s
	                and ($_order_field < %s OR ($_order_field = %s AND L.`Location Key` < %d))  order by $_order_field desc , L.`Location Key` desc limit 1", $table, $where, $wheref, prepare_mysql($_order_field_value), prepare_mysql($_order_field_value), $object->id
                );
                if ($result = $db->query($sql)) {
                    if ($row = $result->fetch()) {
                        $prev_key   = $row['object_key'];
                        $prev_title = _("Location").' '.$row['object_name'].' ('.$row['object_key'].')';
                    }
                }


                $sql = sprintf(
                    "select `Location Code` object_name,L.`Location Key` as object_key from  %s %s %s
	                and ($_order_field  > %s OR ($_order_field  = %s AND L.`Location Key` > %d))  order by $_order_field   , L.`Location Key`  limit 1", $table, $where, $wheref, prepare_mysql($_order_field_value), prepare_mysql($_order_field_value), $object->id
                );

                if ($result = $db->query($sql)) {
                    if ($row = $result->fetch()) {
                        $next_key   = $row['object_key'];
                        $next_title = _("Location").' '.$row['object_name'].' ('.$row['object_key'].')';
                    }
                }


                if ($order_direction == 'desc') {
                    $_tmp1      = $prev_key;
                    $_tmp2      = $prev_title;
                    $prev_key   = $next_key;
                    $prev_title = $next_title;
                    $next_key   = $_tmp1;
                    $next_title = $_tmp2;
                }


                switch ($data['parent']) {
                    case 'warehouse':

                        $up_button = array(
                            'icon'      => 'arrow-up',
                            'title'     => _('Locations'),
                            'reference' => 'warehouse/'.$data['parent_key'].'/locations',
                            'parent'    => ''
                        );


                        if ($prev_key) {
                            $left_buttons[] = array(
                                'icon'      => 'arrow-left',
                                'title'     => $prev_title,
                                'reference' => 'locations/'.$data['parent_key'].'/'.$prev_key
                            );

                        } else {
                            $left_buttons[] = array(
                                'icon'  => 'arrow-left disabled',
                                'title' => ''
                            );

                        }
                        $left_buttons[] = $up_button;


                        if ($next_key) {
                            $left_buttons[] = array(
                                'icon'      => 'arrow-right',
                                'title'     => $next_title,
                                'reference' => 'locations/'.$data['parent_key'].'/'.$next_key
                            );

                        } else {
                            $left_buttons[] = array(
                                'icon'  => 'arrow-right disabled',
                                'title' => '',
                                'url'   => ''
                            );

                        }

                        break;
                    case 'warehouse_area':

                        $up_button = array(
                            'icon'      => 'arrow-up',
                            'title'     => _('Warehouse area').' '.$data['_parent']->get('Code'),
                            'reference' => 'warehouse/'.$data['_parent']->get('Warehouse Key').'/areas/'.$data['_parent']->id,
                            'parent'    => ''
                        );


                        if ($prev_key) {
                            $left_buttons[] = array(
                                'icon'      => 'arrow-left',
                                'title'     => $prev_title,
                                'reference' => 'warehouse/'.$data['_parent']->get('Warehouse Key').'/areas/'.$data['_parent']->id.'/location/'.$prev_key,
                            );

                        } else {
                            $left_buttons[] = array(
                                'icon'  => 'arrow-left disabled',
                                'title' => ''
                            );

                        }
                        $left_buttons[] = $up_button;


                        if ($next_key) {
                            $left_buttons[] = array(
                                'icon'      => 'arrow-right',
                                'title'     => $next_title,
                                'reference' => 'warehouse/'.$data['_parent']->get('Warehouse Key').'/areas/'.$data['_parent']->id.'/location/'.$next_key,
                            );

                        } else {
                            $left_buttons[] = array(
                                'icon'  => 'arrow-right disabled',
                                'title' => '',
                                'url'   => ''
                            );

                        }

                        break;
                }
            }
        }

    }


    $right_buttons = array();
    $sections      = get_sections($data['module'], $warehouse->id);

    if (isset($sections[$_section])) {
        $sections[$_section]['selected'] = true;
    }


    $title = _('Location').' <span  class="id Location_Code" >'.$data['_object']->get('Code').'</span>';
    $title.=' <i id="_External_Warehouse_icon" title="'._('External warehouse').'" style="color:tomato" class="small padding_left_10  fal  fa-garage-car '.($data['_object']->get('Location Place')!='External'?'hide':'').'  "></i>';


    if (!$user->can_view('locations')) {


        $title = _('Access forbidden').' <i class="fa fa-lock "></i>';
    } elseif (!in_array($warehouse->id, $user->warehouses)) {


        $title = ' <i class="fa fa-lock padding_right_10"></i>'.$title;
    }


    $right_buttons[] = array(
        'icon'  => 'sticky-note',
        'title' => _('Sticky note'),
        'class' => 'open_sticky_note  square_button right object_sticky_note  '.($data['_object']->get('Sticky Note') == '' ? '' : 'hide')

    );


    $_content = array(

        'sections_class' => '',
        'sections'       => $sections,

        'left_buttons'  => $left_buttons,
        'right_buttons' => $right_buttons,
        'title'         => $title,
        'search'        => array(
            'show'        => true,
            'placeholder' => _('Search fulfilment')
        )

    );
    $smarty->assign('_content', $_content);

    return array(
        $_content['search'],
        $smarty->fetch('top_menu.tpl'),
        $smarty->fetch('au_header.tpl')
    );

}



function get_customers_navigation($data, $smarty, $user, $db, $account) {


    switch ($data['parent']) {
        case 'warehouse':
            $warehouse = new Warehouse($data['parent_key']);
            break;
        default:
            break;
    }


    $left_buttons = array();


    $right_buttons = array();
    $sections      = get_sections($data['module'], $warehouse->id);

    if (isset($sections[$data['section']])) {
        $sections[$data['section']]['selected'] = true;
    }


    $_content = array(

        'sections_class' => '',
        'sections'       => $sections,

        'left_buttons'  => $left_buttons,
        'right_buttons' => $right_buttons,
        'title'         => _('Fulfilment customers').' <span class="id small hide">('.$warehouse->get('Warehouse Code').')</span>',
        'search'        => array(
            'show'        => true,
            'placeholder' => _('Search fulfilment')
        )

    );
    $smarty->assign('_content', $_content);

    return array(
        $_content['search'],
        $smarty->fetch('top_menu.tpl'),
        $smarty->fetch('au_header.tpl')
    );

}

function get_stored_parts_navigation($data, $smarty, $user, $db, $account) {


    switch ($data['parent']) {
        case 'warehouse':
            $warehouse = new Warehouse($data['parent_key']);
            break;
        default:
            break;
    }


    $left_buttons = array();


    $right_buttons = array();
    $sections      = get_sections($data['module'], $warehouse->id);

    if (isset($sections[$data['section']])) {
        $sections[$data['section']]['selected'] = true;
    }


    $_content = array(

        'sections_class' => '',
        'sections'       => $sections,

        'left_buttons'  => $left_buttons,
        'right_buttons' => $right_buttons,
        'title'         => _('Stored fulfilment parts').' <span class="id small hide">('.$warehouse->get('Warehouse Code').')</span>',
        'search'        => array(
            'show'        => true,
            'placeholder' => _('Search fulfilment')
        )

    );
    $smarty->assign('_content', $_content);

    return array(
        $_content['search'],
        $smarty->fetch('top_menu.tpl'),
        $smarty->fetch('au_header.tpl')
    );

}



function get_customer_navigation($data, $smarty, $user, $db) {


    $customer = $data['_object'];
    $store    = $data['store'];



    if (!$customer->id) {
        return;
    }


    $left_buttons  = array();
    $right_buttons = array();




    $tab      = 'fulfilment.'.$data['extra'];
    $_section = 'customers';




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

        }
        $parameters['parent']     = $data['parent'];
        $parameters['parent_key'] = $data['parent_key'];
        include_once 'prepare_table/'.$tab.'.ptble.php';



        $_order_field       = $order;
        $order              = preg_replace('/^.*\.`/', '', $order);
        $order              = preg_replace('/^`/', '', $order);
        $order              = preg_replace('/`$/', '', $order);
        $_order_field_value = $customer->get($order);


        $prev_title = '';
        $next_title = '';
        $prev_key   = 0;
        $next_key   = 0;
        $sql        = trim($sql_totals." $wheref");

        if ($result2 = $db->query($sql)) {
            if ($row2 = $result2->fetch() and $row2['num'] > 1) {


                $sql = sprintf(
                    "select `Customer Name` object_name,C.`Customer Key` as object_key from $table   $where $wheref
	                and ($_order_field < %s OR ($_order_field = %s AND C.`Customer Key` < %d))  order by $_order_field desc , C.`Customer Key` desc limit 1",

                    prepare_mysql($_order_field_value), prepare_mysql($_order_field_value), $customer->id
                );

                if ($result = $db->query($sql)) {
                    if ($row = $result->fetch()) {
                        $prev_key   = $row['object_key'];
                        $prev_title = _("Customer").' '.$row['object_name'].' ('.$row['object_key'].')';

                    }
                }

                $sql = sprintf(
                    "select `Customer Name` object_name,C.`Customer Key` as object_key from $table   $where $wheref
	                and ($_order_field  > %s OR ($_order_field  = %s AND C.`Customer Key` > %d))  order by $_order_field   , C.`Customer Key`  limit 1", prepare_mysql($_order_field_value), prepare_mysql($_order_field_value), $customer->id
                );

                if ($result = $db->query($sql)) {
                    if ($row = $result->fetch()) {
                        $next_key   = $row['object_key'];
                        $next_title = _("Customer").' '.$row['object_name'].' ('.$row['object_key'].')';

                    }
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


        $placeholder = _('Search customers');
        $sections    = get_sections('customers', $customer->data['Customer Store Key']);





            $up_button = array(
                'icon'      => 'arrow-up',
                'title'     => _("Customers"),
                'reference' => 'fulfilment/'.$data['parent_key'].'/customers/'
            );


            if ($prev_key) {
                $left_buttons[] = array(
                    'icon'      => 'arrow-left',
                    'title'     => $prev_title,
                    'reference' => 'fulfilment/'.$data['parent_key'].'/customers/'.$prev_key
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
                    'reference' => 'fulfilment/'.$data['parent_key'].'/customers/'.$next_key
                );

            } else {
                $left_buttons[] = array(
                    'icon'  => 'arrow-right disabled',
                    'title' => '',
                    'url'   => ''
                );

            }




    $right_buttons[] = array(
        'icon'  => 'sticky-note',
        'title' => _('Note for warehouse'),
        'class' => 'open_sticky_note square_button right delivery_note_sticky_note '.($customer->get('Customer Delivery Sticky Note') == '' ? '' : 'hide')
    );


    $right_buttons[] = array(
        'icon'  => 'sticky-note',
        'title' => _('Note for orders'),
        'class' => 'open_sticky_note square_button right order_sticky_note '.($customer->get('Customer Order Sticky Note') == '' ? '' : 'hide')
    );


    $right_buttons[] = array(
        'icon'  => 'sticky-note',
        'title' => _('Sticky note'),
        'class' => 'open_sticky_note  square_button right customer_sticky_note  '.($customer->get('Sticky Note') == '' ? '' : 'hide')
    );


    if (isset($sections[$_section])) {
        $sections[$_section]['selected'] = true;
    }



    $avatar = '';


    $title = '<span class="Customer_Level_Type_Icon">'.$customer->get('Level Type Icon').'</span>';
    $title .= '<span class="id"><span class="Customer_Name_Truncated Name_Truncated">'.(strlen($customer->get('Customer Name')) > 50 ? substrwords($customer->get('Customer Name'), 55) : $customer->get('Customer Name')).'</span> ('.$customer->get_formatted_id().')</span>';
    if ($customer->get('Customer Type by Activity') == 'ToApprove') {
        $title .= ' <span class="error padding_left_10"><i class="far fa-exclamation-circle"></i> '._('To be approved').'</span>';
    } elseif ($customer->get('Customer Type by Activity') == 'Rejected') {
        $title .= ' <span class="error padding_left_10"><i class="far fa-times"></i> '._('Rejected').'</span>';
    }

    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'avatar'         => $avatar,
        'title'          => $title,
        'search'         => array(
            'show'        => true,
            'placeholder' => $placeholder
        )

    );
    $smarty->assign('_content', $_content);


    return array($_content['search'],$smarty->fetch('top_menu.tpl'),$smarty->fetch('au_header.tpl'));

}