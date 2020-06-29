<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 16 December 2015 at 23:02:14 CET , Barcelona Airport , Spain

 Copyright (c) 2015, Inikoo

 Version 3.0
*/

function get_dashboard_navigation($data, $smarty, $user, $db) {


    $left_buttons  = array();
    $right_buttons = array();
    $sections      = get_sections('production', $data['key']);

    if (isset($sections[$data['section']])) {
        $sections[$data['section']]['selected'] = true;
    }


    $_content = array(

        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => sprintf(
            _('Production %s dashboard'), '<span class="id Supplier_Code">'.$data['_object']->get('Code').'</span>'
        ),
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search production')
        )

    );
    $smarty->assign('_content', $_content);

    return array($_content['search'],$smarty->fetch('top_menu.tpl'),$smarty->fetch('au_header.tpl'));

}

function get_materials_navigation($data, $smarty, $user, $db) {


    $left_buttons  = array();
    $right_buttons = array();
    $sections      = get_sections('production', $data['key']);

    if (isset($sections[$data['section']])) {
        $sections[$data['section']]['selected'] = true;
    }


    $_content = array(

        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => sprintf(
            _('Production %s materials'), '<span class="id Supplier_Code">'.$data['_object']->get('Code').'</span>'
        ),
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search production')
        )

    );
    $smarty->assign('_content', $_content);

    return array($_content['search'],$smarty->fetch('top_menu.tpl'),$smarty->fetch('au_header.tpl'));

}

function get_production_parts_navigation($data, $smarty, $user, $db) {


    $left_buttons  = array();
    $right_buttons = array();
    $sections      = get_sections('production', $data['key']);

    if (isset($sections[$data['section']])) {
        $sections[$data['section']]['selected'] = true;
    }


    $_content = array(

        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => sprintf(
            _('Production %s parts'), '<span class="id Supplier_Code">'.$data['_object']->get('Code').'</span>'
        ),
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search production')
        )

    );
    $smarty->assign('_content', $_content);

    return array($_content['search'],$smarty->fetch('top_menu.tpl'),$smarty->fetch('au_header.tpl'));

}


function get_settings_navigation($data, $smarty, $user, $db) {


    $left_buttons  = array();
    $right_buttons = array();
    $sections      = get_sections('production', $data['key']);

    if (isset($sections[$data['section']])) {
        $sections[$data['section']]['selected'] = true;
    }


    $_content = array(

        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => _('Settings'),
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search production')
        )

    );
    $smarty->assign('_content', $_content);

    return array($_content['search'],$smarty->fetch('top_menu.tpl'),$smarty->fetch('au_header.tpl'));

}

function get_suppliers_navigation($data, $smarty, $user, $db) {


    $left_buttons  = array();
    $right_buttons = array();
    $sections      = get_sections('production_server', '');

    if (isset($sections[$data['section']])) {
        $sections[$data['section']]['selected'] = true;
    }


    $_content = array(

        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => _('Manufacturers'),
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search production')
        )

    );
    $smarty->assign('_content', $_content);

    return array($_content['search'],$smarty->fetch('top_menu.tpl'),$smarty->fetch('au_header.tpl'));

}


function get_manufacture_tasks_navigation($data, $smarty, $user, $db) {

    $left_buttons  = array();
    $right_buttons = array();
    $sections      = get_sections('production', $data['key']);

    if (isset($sections[$data['section']])) {
        $sections[$data['section']]['selected'] = true;
    }


    $_content = array(

        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => _('Manufacture Tasks'),
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search production')
        )

    );
    $smarty->assign('_content', $_content);

    return array($_content['search'],$smarty->fetch('top_menu.tpl'),$smarty->fetch('au_header.tpl'));

}


function get_operatives_navigation($data, $smarty, $user, $db) {

    $left_buttons  = array();
    $right_buttons = array();
    $sections      = get_sections('production', $data['key']);

    if (isset($sections[$data['section']])) {
        $sections[$data['section']]['selected'] = true;
    }

    $_content = array(

        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => _('Workers'),
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search production')
        )

    );
    $smarty->assign('_content', $_content);

    return array($_content['search'],$smarty->fetch('top_menu.tpl'),$smarty->fetch('au_header.tpl'));

}


function get_batches_navigation($data, $smarty, $user, $db) {

    global $user, $smarty;


    $left_buttons  = array();
    $right_buttons = array();
    $sections      = get_sections('production', $data['key']);

    if (isset($sections[$data['section']])) {
        $sections[$data['section']]['selected'] = true;
    }


    $_content = array(

        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => _('Batches'),
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search production')
        )

    );
    $smarty->assign('_content', $_content);

    return array($_content['search'],$smarty->fetch('top_menu.tpl'),$smarty->fetch('au_header.tpl'));

}


function get_new_manufacture_task_navigation($data, $smarty, $user, $db) {

    $left_buttons  = array();
    $right_buttons = array();


    $sections = get_sections('production', $data['supplier']->id);

    $_section = 'manufacture_tasks';
    if (isset($sections[$_section])) {
        $sections[$_section]['selected'] = true;
    }

    $up_button = array(
        'icon'      => 'arrow-up',
        'title'     => _("Manufacture tasks"),
        'reference' => 'production/manufacture_tasks'
    );


    $left_buttons[] = $up_button;


    $title = '<span >'._('New manufacture task').'</span>';


    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => $title,
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search production')
        )

    );
    $smarty->assign('_content', $_content);


    return array($_content['search'],$smarty->fetch('top_menu.tpl'),$smarty->fetch('au_header.tpl'));

}


function get_manufacture_task_navigation($data, $smarty, $user, $db) {


    $object        = $data['_object'];
    $left_buttons  = array();
    $right_buttons = array();

    if ($data['parent']) {

        switch ($data['parent']) {
            case 'account':
                $tab      = 'manufacture_tasks';
                $_section = 'manufacture_tasks';
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
            if ($row2 = $result2->fetch() and $row2['num'] > 1) {


                $sql = sprintf(
                    "select `Manufacture Task Name` object_name,MT.`Manufacture Task Key` as object_key from $table $where $wheref
	                and ($_order_field < %s OR ($_order_field = %s AND MT.`Manufacture Task Key` < %d))  order by $_order_field desc , MT.`Manufacture Task Key` desc limit 1",

                    prepare_mysql($_order_field_value), prepare_mysql($_order_field_value), $object->id
                );

                if ($result = $db->query($sql)) {
                    if ($row = $result->fetch()) {
                        $prev_key   = $row['object_key'];
                        $prev_title = _("Manufacture task").' '.$row['object_name'].' ('.$row['object_key'].')';
                    }
                } else {
                    print_r($error_info = $db->errorInfo());
                    exit;
                }


                $sql = sprintf(
                    "select `Manufacture Task Name` object_name,MT.`Manufacture Task Key` as object_key from $table   $where $wheref
	                and ($_order_field  > %s OR ($_order_field  = %s AND MT.`Manufacture Task Key` > %d))  order by $_order_field   , MT.`Manufacture Task Key`  limit 1", prepare_mysql($_order_field_value), prepare_mysql($_order_field_value), $object->id
                );


                if ($result = $db->query($sql)) {
                    if ($row = $result->fetch()) {
                        $next_key   = $row['object_key'];
                        $next_title = _("Manufacture task").' '.$row['object_name'].' ('.$row['object_key'].')';

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
        } else {
            print_r($error_info = $db->errorInfo());
            exit;
        }


        if ($data['parent'] == 'account') {

            $up_button = array(
                'icon'      => 'arrow-up',
                'title'     => _("Manufacture tasks"),
                'reference' => 'production/manufacture_tasks'
            );

            if ($prev_key) {
                $left_buttons[] = array(
                    'icon'      => 'arrow-left',
                    'title'     => $prev_title,
                    'reference' => 'manufacture_task/'.$prev_key
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
                    'reference' => 'manufacture_task/'.$next_key
                );

            } else {
                $left_buttons[] = array(
                    'icon'  => 'arrow-right disabled',
                    'title' => '',
                    'url'   => ''
                );

            }


        }
    } else {


    }

    $sections = get_sections('production', $data['supplier']->id);


    if (isset($sections[$_section])) {
        $sections[$_section]['selected'] = true;
    }


    $title = _('Production task').': <span class="id Manufacture_Task_Code">'.$object->get('Code').'</span>';


    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => $title,
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search production')
        )

    );
    $smarty->assign('_content', $_content);


    return array($_content['search'],$smarty->fetch('top_menu.tpl'),$smarty->fetch('au_header.tpl'));

}

function get_production_part_navigation($data, $smarty, $user, $db, $account) {

    $block_view = $data['section'];

    $left_buttons  = array();
    $right_buttons = array();


    if ($data['parent']) {

        switch ($data['parent']) {
            case 'supplier_production':
                $tab      = 'production.production_parts';
                $_section = 'production_parts';
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


        if ($data['parent'] == 'supplier_production') {

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
                                $prev_title = _("Production").' '.$row['object_name'].' ('.$row['object_key'].')';
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
                                $next_title = _("Production").' '.$row['object_name'].' ('.$row['object_key'].')';

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
                            'title'     => _("Production").' '.$data['_parent']->get('Code'),
                            'reference' => 'production/'.$data['_parent']->id.'/parts'
                        );

                        if ($prev_key) {
                            $left_buttons[] = array(
                                'icon'      => 'arrow-left',
                                'title'     => $prev_title,
                                'reference' => 'production/'.$data['_parent']->id.'/part/'.$prev_key
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
                                'reference' => 'production/'.$data['_parent']->id.'/part/'.$next_key
                            );

                        } else {
                            $left_buttons[] = array(
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

    $sections = get_sections('production', $data['_parent']->id);


    if (isset($sections[$_section])) {
        $sections[$_section]['selected'] = true;
    }


    $title = _("Production's part").' <span class="id Supplier_Part_Reference">'.$data['_object']->get('Reference').'</span>';
    $title .= ' <small class="padding_left_10"> <i class="fa fa-long-arrow-right padding_left_10"></i> <i class="fa fa-box button" title="'._('Part').'" onCLick="change_view(\'/part/'.$data['_object']->part->id
        .'\')" ></i> <span class="Part_Reference button"  onCLick="change_view(\'part/'.$data['_object']->part->id.'\')">'.$data['_object']->part->get(
            'Reference'
        ).'</small>';


    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => $title,
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search production')
        )

    );
    $smarty->assign('_content', $_content);


    return array($_content['search'],$smarty->fetch('top_menu.tpl'),$smarty->fetch('au_header.tpl'));

}


function get_production_supplier_purchase_orders_navigation($data, $smarty, $user, $db, $account) {

    $left_buttons  = array();
    $right_buttons = array();
    $sections      = get_sections('production', $data['key']);


    $sections['production_supplier_orders']['selected'] = true;


    $_content = array(

        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => _('Job orders'),
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search production')
        )

    );
    $smarty->assign('_content', $_content);
    return array($_content['search'],$smarty->fetch('top_menu.tpl'),$smarty->fetch('au_header.tpl'));

}


function get_production_supplier_deliveries_navigation($data, $smarty, $user, $db, $account) {

    $left_buttons  = array();
    $right_buttons = array();
    $sections      = get_sections('production', $data['key']);

    if (isset($sections[$data['section']])) {
        $sections[$data['section']]['selected'] = true;
    }



    $_content = array(

        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => _('Deliveries'),
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search production')
        )

    );
    $smarty->assign('_content', $_content);
    return array($_content['search'],$smarty->fetch('top_menu.tpl'),$smarty->fetch('au_header.tpl'));

}


function get_delivery_navigation($data, $smarty, $user, $db, $account) {


    $object        = $data['_object'];
    $left_buttons  = array();
    $right_buttons = array();


    $tab      = 'production_supplier.deliveries';
    $_section = 'suppliers';


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


    $sql = sprintf(
        "select `Supplier Delivery Public ID` object_name,D.`Supplier Delivery Key` as object_key from %s
	                and ($_order_field < %s OR ($_order_field = %s AND D.`Supplier Delivery Key` < %d))  order by $_order_field desc , D.`Supplier Delivery Key` desc limit 1",


        "$table $where $wheref", prepare_mysql($_order_field_value), prepare_mysql($_order_field_value), $object->id
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
        "select `Supplier Delivery Public ID` object_name,D.`Supplier Delivery Key` as object_key from %s
	                and ($_order_field  > %s OR ($_order_field  = %s AND D.`Supplier Delivery Key` > %d))  order by $_order_field   , D.`Supplier Delivery Key`  limit 1", "$table $where $wheref", prepare_mysql($_order_field_value), prepare_mysql($_order_field_value),
        $object->id
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


    $up_button = array(
        'icon'      => 'arrow-up',
        'title'     => _("Deliveries"),
        'reference' => 'production/'.$data['parent_key'].'/deliveries'
    );

    if ($prev_key) {
        $left_buttons[] = array(
            'icon'      => 'arrow-left',
            'title'     => $prev_title,
            'reference' => 'production/'.$data['parent_key'].'/delivery/'.$prev_key
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
            'reference' => 'production/'.$data['parent_key'].'/delivery/'.$next_key
        );

    } else {
        $left_buttons[] = array(
            'icon'  => 'arrow-right disabled',
            'title' => '',
            'url'   => ''
        );

    }


    $sections           = get_sections('production', $data['parent_key']);
    $search_placeholder = _('Search production');

    if (isset($sections[$_section])) {
        $sections[$_section]['selected'] = true;
    }


    $title = _('Production sheet').' <span class="id Supplier_Delivery_Public_ID">'.$object->get('Public ID').'</span> ';


    if ($object->get('Purchase Order Key')) {

        $title .= ' <small class="padding_left_10 button"  onClick="change_view(\'/production/'.$object->get('Purchase Order Parent Key').'/order/'.$object->get('Purchase Order Key').'\')"  > <i class="fal fa-clipboard button" title="'._('Purchase order')
            .'"  ></i> <span class="Purchase_Order_Public_ID " >'.$object->get('Purchase Order Public ID').'</small>';
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


    return array($_content['search'],$smarty->fetch('top_menu.tpl'),$smarty->fetch('au_header.tpl'));

}


function get_purchase_order_navigation($data, $smarty, $user, $db, $account) {

    $object        = $data['_object'];
    $left_buttons  = array();
    $right_buttons = array();


    $tab      = 'production_supplier.orders';


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


    $sql = sprintf(
        "select `Purchase Order Public ID` object_name,O.`Purchase Order Key` as object_key from %s
	                and ($_order_field < %s OR ($_order_field = %s AND O.`Purchase Order Key` < %d))  order by $_order_field desc , O.`Purchase Order Key` desc limit 1", "$table $where $wheref", prepare_mysql($_order_field_value), prepare_mysql($_order_field_value),
        $object->id
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
	                and ($_order_field  > %s OR ($_order_field  = %s AND O.`Purchase Order Key` > %d))  order by $_order_field   , O.`Purchase Order Key`  limit 1", "$table $where $wheref", prepare_mysql($_order_field_value), prepare_mysql($_order_field_value),
        $object->id
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


    $up_button = array(
        'icon'      => 'arrow-up',
        'title'     => _("Job orders"),
        'reference' => 'production/'.$data['parent_key'].'/orders'
    );

    if ($prev_key) {
        $left_buttons[] = array(
            'icon'      => 'arrow-left',
            'title'     => $prev_title,
            'reference' => 'production/'.$data['parent_key'].'/order/'.$prev_key
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
            'reference' => 'production/'.$data['parent_key'].'/order/'.$next_key
        );

    } else {
        $left_buttons[] = array(
            'icon'  => 'arrow-right disabled',
            'title' => '',
            'url'   => ''
        );

    }

    $sections           = get_sections('production', $data['parent_key']);
    $sections['production_supplier_orders']['selected'] = true;

    $search_placeholder = _('Search production');


    $title = _('Job order').' <span class="Purchase_Order_Public_ID id">'.$object->get('Public ID').'</span>';


    //  class="order_operation {if ( $order->get('State Index') < 30 or ($order->get('Purchase Order Ordered Number Items')-$order->get('Purchase Order Number Supplier Delivery Items'))==0) or $parent->get('Parent Skip Inputting')=='No' }hide{/if}">

    $items_not_in_delivery=$object->get('Purchase Order Ordered Number Items')-$object->get('Purchase Order Number Supplier Delivery Items');
    $right_buttons[]=array(
        'icon'  => 'plus',
        'title' => _('Create production sheet'),
        'click'=>"show_create_delivery()",
        'pre_text'=> _('Create production sheet'),
        'class'=>'text width_auto quick_create_delivery_button '.(  ($items_not_in_delivery==0 or $object->get('State Index')<40 ) ?'hide':''  )
    );


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


    return array($_content['search'],$smarty->fetch('top_menu.tpl'),$smarty->fetch('au_header.tpl'));

}


function get_new_production_part_navigation($data, $smarty, $user, $db, $account) {

    $left_buttons  = array();
    $right_buttons = array();


    $sections           = get_sections('production', $data['parent_key']);
    $sections['production_parts']['selected'] = true;


    $up_button = array(
        'icon'      => 'arrow-up',
        'title'     => _("Production").' ('.$data['_parent']->get('Code').')',
        'reference' => 'production/'.$data['parent_key'].'/parts'
    );


    $left_buttons[] = $up_button;


    $title = '<span class="id ">'._("New production's part").'</span>';


    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => $title,
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search production')
        )

    );
    $smarty->assign('_content', $_content);


    return array($_content['search'],$smarty->fetch('top_menu.tpl'),$smarty->fetch('au_header.tpl'));

}



function get_upload_navigation($data, $smarty, $user, $db) {


    $left_buttons  = array();
    $right_buttons = array();


    $sections           = get_sections('production', $data['parent_key']);
    $sections['production_parts']['selected'] = true;


    $title = '<span class="id ">'._("New production's parts").'</span>';


    $up_button = array(
        'icon'      => 'arrow-up',
        'title'     => _("Production").' ('.$data['_parent']->get('Code').')',
        'reference' => 'production/'.$data['parent_key'].'/parts'
    );


    $left_buttons[] = $up_button;

    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => $title,
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search production')
        )

    );
    $smarty->assign('_content', $_content);


    return array($_content['search'],$smarty->fetch('top_menu.tpl'),$smarty->fetch('au_header.tpl'));

}

function get_operative_navigation($data, $smarty, $user, $db, $account) {

    $object        = $data['_object'];
    $left_buttons  = array();
    $right_buttons = array();


    $tab      = 'operatives';


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

    $parameters['parent']     = 'account';
    $parameters['parent_key'] = 1;


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


    $sql = sprintf(
        "select `Staff Alias` object_name,`Staff Operative Key` as object_key from %s
	                and ($_order_field < %s OR ($_order_field = %s AND `Staff Operative Key` < %d))  order by $_order_field desc , `Staff Operative Key` desc limit 1", "$table $where $wheref", prepare_mysql($_order_field_value), prepare_mysql($_order_field_value),
        $object->id
    );


    if ($result = $db->query($sql)) {
        if ($row = $result->fetch()) {
            $prev_key   = $row['object_key'];
            $prev_title =  _("Worker").' '.$row['object_name'];
        }
    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }


    $sql = sprintf(
        "select `Staff Alias` object_name,`Staff Operative Key` as object_key from %s
	                and ($_order_field  > %s OR ($_order_field  = %s AND `Staff Operative Key` > %d))  order by $_order_field   , `Staff Operative Key`  limit 1", "$table $where $wheref", prepare_mysql($_order_field_value), prepare_mysql($_order_field_value),
        $object->id
    );

    if ($result = $db->query($sql)) {
        if ($row = $result->fetch()) {
            $next_key   = $row['object_key'];
            $next_title = _("Worker").' '.$row['object_name'];

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
        'title'     => _("Production Workers"),
        'reference' => 'production/'.$data['parent_key'].'/operatives'
    );

    if ($prev_key) {
        $left_buttons[] = array(
            'icon'      => 'arrow-left',
            'title'     => $prev_title,
            'reference' => 'production/'.$data['parent_key'].'/operatives/'.$prev_key
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
            'reference' => 'production/'.$data['parent_key'].'/operatives/'.$next_key
        );

    } else {
        $left_buttons[] = array(
            'icon'  => 'arrow-right disabled',
            'title' => '',
            'url'   => ''
        );

    }

    $sections           = get_sections('production', $data['parent_key']);
    $sections['operatives']['selected'] = true;

    $search_placeholder = _('Search production');


    $title = '<i class="fa fa-digging" title=""'._('Production worker').'"/></i> <span class="Staff_Alias id ">'.$object->get('Alias').'</span> <span class="italic ">(<span class="Staff_Name">'.$object->get('Name').'</span>)</span>';




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


    return array($_content['search'],$smarty->fetch('top_menu.tpl'),$smarty->fetch('au_header.tpl'));

}

