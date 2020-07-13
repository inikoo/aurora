<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 29 September 2015 13:01:56 BST, Sheffield, UK

 Copyright (c) 2015, Inikoo

 Version 3.0
*/

include_once 'class.Warehouse.php';

function get_production_deliveries_navigation($data, $smarty) {

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
        'title'          => _('Production deliveries'),
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search warehouse')
        )

    );
    $smarty->assign('_content', $_content);

    return array(
        $_content['search'],
        $smarty->fetch('top_menu.tpl'),
        $smarty->fetch('au_header.tpl')
    );
}

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
        'title'          => _('Warehouse dashboard'),
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search warehouse')
        )

    );
    $smarty->assign('_content', $_content);

    return array(
        $_content['search'],
        $smarty->fetch('top_menu.tpl'),
        $smarty->fetch('au_header.tpl')
    );

}


function get_warehouses_navigation($data, $smarty, $user, $db, $account) {


    $block_view = $data['section'];


    $left_buttons = array();


    $right_buttons = array();
    $sections      = array();

    if (isset($sections[$data['section']])) {
        $sections[$data['section']]['selected'] = true;
    }


    $_content = array(

        'sections_class' => '',
        'sections'       => $sections,

        'left_buttons'  => $left_buttons,
        'right_buttons' => $right_buttons,
        'title'         => _('Warehouses'),
        'search'        => array(
            'show'        => true,
            'placeholder' => _(
                'Search inventory all warehouses'
            )
        )

    );
    $smarty->assign('_content', $_content);

    return array(
        $_content['search'],
        $smarty->fetch('top_menu.tpl'),
        $smarty->fetch('au_header.tpl')
    );

}


function get_new_warehouse_navigation($data, $smarty, $user, $db, $account) {


    $left_buttons = array();

    $left_buttons[] = array(
        'icon'      => 'arrow-up',
        'title'     => _('Warehouses'),
        'reference' => 'warehouses',
        'parent'    => ''
    );


    $right_buttons = array();
    $sections      = array();

    if (isset($sections[$data['section']])) {
        $sections[$data['section']]['selected'] = true;
    }


    $_content = array(

        'sections_class' => '',
        'sections'       => $sections,

        'left_buttons'  => $left_buttons,
        'right_buttons' => $right_buttons,
        'title'         => _('New Warehouse'),
        'search'        => array(
            'show'        => true,
            'placeholder' => _('Search inventory all warehouses')
        )

    );
    $smarty->assign('_content', $_content);

    return array(
        $_content['search'],
        $smarty->fetch('top_menu.tpl'),
        $smarty->fetch('au_header.tpl')
    );

}


function get_new_warehouse_area_navigation($data, $smarty, $user, $db, $account) {


    $left_buttons = array();

    $left_buttons[] = array(
        'icon'      => 'arrow-up',
        'title'     => _('Warehouse areas'),
        'reference' => 'warehouse/'.$data['parent_key'].'/areas',
        'parent'    => ''
    );


    $right_buttons = array();
    $sections      = array();

    if (isset($sections[$data['section']])) {
        $sections[$data['section']]['selected'] = true;
    }


    $_content = array(

        'sections_class' => '',
        'sections'       => $sections,

        'left_buttons'  => $left_buttons,
        'right_buttons' => $right_buttons,
        'title'         => _('New Warehouse area'),
        'search'        => array(
            'show'        => true,
            'placeholder' => _('Search warehouse')
        )

    );
    $smarty->assign('_content', $_content);

    return array(
        $_content['search'],
        $smarty->fetch('top_menu.tpl'),
        $smarty->fetch('au_header.tpl')
    );

}


function get_warehouse_navigation($data, $smarty, $user, $db, $account) {


    $warehouse = new Warehouse($data['key']);


    $left_buttons   = array();
    $left_buttons[] = array(
        'icon'      => 'arrow-up',
        'title'     => _('Warehouses'),
        'reference' => 'warehouses',
        'parent'    => ''
    );


    $right_buttons = array();
    $sections      = get_sections($data['module'], $warehouse->id);

    if (isset($sections[$data['section']])) {
        $sections[$data['section']]['selected'] = true;
    }


    $title = _('Warehouse').' <span  class="id Warehouse_Code" >'.$warehouse->get('Code').'</span>';


    if (!$user->can_view('locations')) {


        $title = _('Access forbidden').' <i class="fa fa-lock "></i>';
    } elseif (!in_array($data['key'], $user->warehouses)) {


        $title = ' <i class="fa fa-lock padding_right_10"></i>'.$title;
    }

    $_content = array(

        'sections_class' => '',
        'sections'       => $sections,

        'left_buttons'  => $left_buttons,
        'right_buttons' => $right_buttons,
        'title'         => $title,
        'search'        => array(
            'show'        => true,
            'placeholder' => _('Search locations')
        )

    );
    $smarty->assign('_content', $_content);

    return array(
        $_content['search'],
        $smarty->fetch('top_menu.tpl'),
        $smarty->fetch('au_header.tpl')
    );

}


function get_warehouse_areas_navigation($data, $smarty, $user, $db, $account) {


    $warehouse = new Warehouse($data['key']);


    $left_buttons = array();


    $right_buttons = array();
    $sections      = get_sections($data['module'], $warehouse->id);

    if (isset($sections[$data['section']])) {
        $sections[$data['section']]['selected'] = true;
    }


    $title = sprintf(_('Warehouse areas for %s'), ' <span  class="id Warehouse_Code" >'.$warehouse->get('Code').'</span>');


    if (!$user->can_view('locations')) {


        $title = _('Access forbidden').' <i class="fa fa-lock "></i>';
    } elseif (!in_array($data['key'], $user->warehouses)) {


        $title = ' <i class="fa fa-lock padding_right_10"></i>'.$title;
    }

    $_content = array(


        'sections_class' => '',
        'sections'       => $sections,

        'left_buttons'  => $left_buttons,
        'right_buttons' => $right_buttons,
        'title'         => $title,
        'search'        => array(
            'show'        => true,
            'placeholder' => _('Search locations')
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
        'title'         => _('Locations').' <span class="id">'.$warehouse->get('Warehouse Code').'</span>',
        'search'        => array(
            'show'        => true,
            'placeholder' => _('Search locations')
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
            'placeholder' => _('Search locations')
        )

    );
    $smarty->assign('_content', $_content);

    return array(
        $_content['search'],
        $smarty->fetch('top_menu.tpl'),
        $smarty->fetch('au_header.tpl')
    );

}


function get_locations_new_main_category_navigation($data, $smarty, $user, $db, $account) {


    $sections       = get_sections('warehouses', $data['parent_key']);
    $left_buttons   = array();
    $left_buttons[] = array(
        'icon'      => 'arrow-up',
        'title'     => _('Categories'),
        'reference' => 'warehouses/'.$data['parent_key'].'/categories',
        'parent'    => ''
    );

    $right_buttons = array();

    $sections['categories']['selected'] = true;


    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => _("New main locations's category"),
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search locations')
        )

    );
    $smarty->assign('_content', $_content);

    return array(
        $_content['search'],
        $smarty->fetch('top_menu.tpl'),
        $smarty->fetch('au_header.tpl')
    );


}


function get_new_location_navigation($data, $smarty, $user, $db, $account) {


    $sections     = get_sections('warehouses', $data['_parent']->get('Warehouse Key'));
    $left_buttons = array();


    $right_buttons = array();

    $sections['locations']['selected'] = true;


    switch ($data['_parent']->get_object_name()) {
        case 'Warehouse Area':
            $title          = sprintf(_("New location in area %s"), '<span class="id">'.$data['_parent']->get('Code').'</span>');
            $left_buttons[] = array(
                'icon'      => 'arrow-up',
                'title'     => _('Warehouse area'),
                'reference' => 'warehouse/'.$data['_parent']->get('Warehouse Key').'/areas/'.$data['parent_key'],
                'parent'    => ''
            );
            break;
        case 'Warehouse':
            $title          = sprintf(_("New location in warehouse %s"), '<span class="id">'.$data['_parent']->get('Code').'</span>');
            $left_buttons[] = array(
                'icon'      => 'arrow-up',
                'title'     => _('Locations'),
                'reference' => 'locations/'.$data['parent_key'],
                'parent'    => ''
            );
            break;
    }


    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => $title,
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search locations')
        )

    );
    $smarty->assign('_content', $_content);

    return array(
        $_content['search'],
        $smarty->fetch('top_menu.tpl'),
        $smarty->fetch('au_header.tpl')
    );


}


function get_categories_navigation($data, $smarty, $user, $db, $account) {


    require_once 'class.Store.php';

    switch ($data['parent']) {
        case '':

            break;
        default:

            break;
    }

    $block_view = $data['section'];


    $left_buttons = array();


    $right_buttons = array();

    // $right_buttons[]=array('icon'=>'edit', 'title'=>_('Edit'), 'url'=>"edit_customer_categories.php?store_id=");

    $sections = get_sections('warehouses', '');
    if (isset($sections[$data['section']])) {
        $sections[$data['section']]['selected'] = true;
    }

    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => _("Locations's Categories"),
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search locations')
        )

    );
    $smarty->assign('_content', $_content);

    return array(
        $_content['search'],
        $smarty->fetch('top_menu.tpl'),
        $smarty->fetch('au_header.tpl')
    );

}

function get_locations_category_navigation($data, $smarty, $user, $db, $account) {

    $category = $data['_object'];

    $left_buttons  = array();
    $right_buttons = array();

    switch ($data['parent']) {
        case 'category':

            $parent_category = new Category($data['parent_key']);


            $up_button = array(
                'icon'      => 'arrow-up',
                'title'     => _("Location's Categories"),
                'reference' => 'warehouses/category/'.$parent_category->id
            );

            if ($data['tab'] == 'category.subjects') {
                $tab = 'subject_categories';

            } else {

                $tab = 'category.categories';
            }
            $parent_categories = $parent_category->get('Category Position');
            break;
        case 'account':


            $up_button         = array(
                'icon'      => 'arrow-up',
                'title'     => _(
                    "Locations's Categories"
                ),
                'reference' => 'warehouses/categories'
            );
            $tab               = 'locations.categories';
            $parent_categories = '';
            break;

        default:

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


    $_order_field = $order;
    $order        = preg_replace('/^.*\.`/', '', $order);
    $order        = preg_replace('/^`/', '', $order);
    $order        = preg_replace('/`$/', '', $order);


    $_order_field_value = $category->get($order);
    $extra_field        = '';


    $prev_title             = '';
    $next_title             = '';
    $prev_key               = 0;
    $next_key               = 0;
    $prev_extra_field_value = '';
    $next_extra_field_value = '';


    $sql = trim($sql_totals." $wheref");
    //print $sql;

    if ($result2 = $db->query($sql)) {
        if ($row2 = $result2->fetch() and $row2['num'] > 1) {


            $sql = sprintf(
                "select C.`Category Label` object_name,C.`Category Key` as object_key %s from %s
	                and ($_order_field < %s OR ($_order_field = %s AND C.`Category Key` < %d))  order by $_order_field desc , C.`Category Key` desc limit 1", $extra_field, "$table $where $wheref", prepare_mysql($_order_field_value), prepare_mysql($_order_field_value),
                $category->id
            );

            if ($result = $db->query($sql)) {
                if ($row = $result->fetch()) {
                    $prev_key   = $row['object_key'];
                    $prev_title = _("Location").' '.$row['object_name'].' ('.$row['object_key'].')';
                    if ($extra_field) {
                        $prev_extra_field_value = $row['extra_field'];
                    }
                }
            } else {
                print_r($error_info = $db->errorInfo());
                exit;
            }


            $sql = sprintf(
                "select C.`Category Label` object_name,C.`Category Key` as object_key %s from %s
	                and ($_order_field  > %s OR ($_order_field  = %s AND C.`Category Key`> %d))  order by $_order_field   , C.`Category Key` limit 1", $extra_field, "$table $where $wheref", prepare_mysql($_order_field_value), prepare_mysql($_order_field_value),
                $category->id
            );


            if ($result = $db->query($sql)) {
                if ($row = $result->fetch()) {
                    $next_key   = $row['object_key'];
                    $next_title = _("Location").' '.$row['object_name'].' ('.$row['object_key'].')';
                    if ($extra_field) {
                        $next_extra_field_value = $row['extra_field'];
                    }
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
                if ($extra_field) {
                    $_tmp3                  = $prev_extra_field_value;
                    $prev_extra_field_value = $next_extra_field_value;
                    $next_extra_field_value = $_tmp3;
                }

            }


        }
    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }


    if ($prev_key) {
        $left_buttons[] = array(
            'icon'      => 'arrow-left',
            'title'     => $prev_title,
            'reference' => 'warehouses/category/'.$parent_categories.$prev_key
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
            'reference' => 'warehouses/category/'.$parent_categories.$next_key
        );

    } else {
        $left_buttons[] = array(
            'icon'  => 'arrow-right disabled',
            'title' => '',
            'url'   => ''
        );

    }


    if ($account->get('Account Location Family Category Key') == $data['_object']->get('Category Root Key')) {
        $title = '<span class="Category_Code id">'.$data['_object']->get('Code').'</span>';
    } else {
        $title = _('Category').' <span class="Category_Label">'.$data['_object']->get('Label').'</span> (<span class="Category_Code id">'.$data['_object']->get(
                'Code'
            ).'</span>)';
    }


    $right_buttons[] = array(
        'icon'  => 'sticky-note',
        'title' => _('Sticky note'),
        'id'    => 'sticky_note_button',
        'click' => "show_sticky_note_edit_dialog('sticky_note_button')",
        'class' => ($category->get('Sticky Note') == '' ? '' : 'hide')
    );

    //$right_buttons[]=array('icon'=>'edit', 'title'=>_('Edit'), 'url'=>"edit_product_categories.php?store_id=".$data['store']->id);

    $sections                           = get_sections(
        'warehouses', $data['store']->id
    );
    $sections['categories']['selected'] = true;

    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => $title,
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search locations')
        )

    );
    $smarty->assign('_content', $_content);

    return array(
        $_content['search'],
        $smarty->fetch('top_menu.tpl'),
        $smarty->fetch('au_header.tpl')
    );

}


function get_delivery_notes_navigation($data, $smarty, $user, $db, $account) {


    require_once 'class.Warehouse.php';

    switch ($data['parent']) {
        case 'warehouse':
            $warehouse = new Warehouse($data['parent_key']);
            break;
        default:

            break;
    }

    $block_view = $data['section'];


    $sections = get_sections('warehouses', $warehouse->id);
    switch ($block_view) {

        case 'delivery_notes':
            $sections_class = '';
            $title          = _('Delivery Notes').' <span class="id">'.$warehouse->get('Code').'</span>';


            break;

    }

    $left_buttons = array();


    if (count($user->warehouses) > 1) {

        list($prev_key, $next_key) = get_prev_next($warehouse->id, $user->warehouses);

        $sql = sprintf(
            "SELECT `Warehouse Code` FROM `Warehouse Dimension` WHERE `Warehouse Key`=%d", $prev_key
        );


        if ($result = $db->query($sql)) {
            if ($row = $result->fetch()) {
                $prev_title = _('Warehouse').' '.$row['Warehouse Code'];
            } else {
                $prev_title = '';
            }
        } else {
            print_r($error_info = $db->errorInfo());
            exit;
        }


        $sql = sprintf(
            "SELECT `Warehouse Code` FROM `Warehouse Dimension` WHERE `Warehouse Key`=%d", $next_key
        );

        if ($result = $db->query($sql)) {
            if ($row = $result->fetch()) {
                $next_title = _('Warehouse').' '.$row['Warehouse Code'];
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

    return array(
        $_content['search'],
        $smarty->fetch('top_menu.tpl'),
        $smarty->fetch('au_header.tpl')
    );

}


function get_deleted_location_navigation($data, $smarty, $user, $db, $account) {

    $warehouse = $data['warehouse'];


    $_section     = 'barcodes';
    $object       = $data['_object'];
    $left_buttons = array();

    $right_buttons = array();
    $sections      = get_sections('warehouses', $warehouse->id);
    if (isset($sections[$_section])) {
        $sections[$_section]['selected'] = true;
    }


    $title = _('Deleted location').' <span class="id Supplier_Code">'.$object->get('Code').'</span>';

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

    return array(
        $_content['search'],
        $smarty->fetch('top_menu.tpl'),
        $smarty->fetch('au_header.tpl')
    );

}

function get_leakages_navigation($data, $smarty, $user, $db, $account) {


    $warehouse = new Warehouse($data['key']);


    $left_buttons   = array();
    $left_buttons[] = array(
        'icon'      => 'arrow-up',
        'title'     => _('Warehouse'),
        'reference' => 'warehouse/'.$warehouse->id.'/dashboard',
        'parent'    => ''
    );


    $right_buttons = array();
    $sections      = get_sections($data['module'], $warehouse->id);

    if (isset($sections[$data['section']])) {
        $sections[$data['section']]['selected'] = true;
    }


    $title = _('Stock leakages');

    if (!$user->can_view('locations')) {


        $title = _('Access forbidden').' <i class="fa fa-lock "></i>';
    } elseif (!in_array($data['key'], $user->warehouses)) {


        $title = ' <i class="fa fa-lock padding_right_10"></i>'.$title;
    }

    $_content = array(

        'sections_class' => '',
        'sections'       => $sections,

        'left_buttons'  => $left_buttons,
        'right_buttons' => $right_buttons,
        'title'         => $title,
        'search'        => array(
            'show'        => true,
            'placeholder' => _('Search locations')
        )

    );
    $smarty->assign('_content', $_content);

    return array(
        $_content['search'],
        $smarty->fetch('top_menu.tpl'),
        $smarty->fetch('au_header.tpl')
    );

}


function get_timeseries_record_navigation($data, $smarty, $user, $db, $account) {


    //print_r($data);


    $timeseries = $data['_object'];

    // print_r($timeseries);


    $left_buttons  = array();
    $right_buttons = array();


    switch ($data['parent']) {
        case 'timeseries':
            $tab      = 'stock_leakages';
            $_section = 'dashboard';
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
    $_order_field_value = $timeseries->get($order);


    $prev_title = '';
    $next_title = '';
    $prev_key   = 0;
    $next_key   = 0;


    if ($data['parent'] == 'timeseries') {

        if ($result2 = $db->query($sql)) {
            if ($row2 = $result2->fetch()) {


                $sql = sprintf(
                    "select `Timeseries Record Date` object_name,`Timeseries Record Key` as object_key from %s
	                and ($_order_field < %s OR ($_order_field = %s AND `Timeseries Record Key` < %d))  order by $_order_field desc , `Timeseries Record Key` desc limit 1", "$table $where $wheref", prepare_mysql($_order_field_value), prepare_mysql($_order_field_value),
                    $timeseries->id
                );


                //print $sql;
                if ($result = $db->query($sql)) {
                    if ($row = $result->fetch()) {
                        $prev_key   = $row['object_key'];
                        $prev_title = _("Time series record").' '.$row['object_name'].' ('.$row['object_key'].')';
                    }
                } else {
                    print_r($error_info = $db->errorInfo());
                    exit;
                }


                $sql = sprintf(
                    "select `Timeseries Record Date` object_name,`Timeseries Record Key` as object_key from %s
	                and ($_order_field  > %s OR ($_order_field  = %s AND `Timeseries Record Key` > %d))  order by $_order_field   ,`Timeseries Record Key`  limit 1", "$table $where $wheref", prepare_mysql($_order_field_value), prepare_mysql($_order_field_value),
                    $timeseries->id
                );

                if ($result = $db->query($sql)) {
                    if ($row = $result->fetch()) {
                        $next_key   = $row['object_key'];
                        $next_title = _("Time series record").' '.$row['object_name'].' ('.$row['object_key'].')';

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
                    'title'     => _("Stock leakages"),
                    'reference' => 'warehouse/'.$data['_parent']->get('Timeseries Parent Key').'/leakages/'
                );

                if ($prev_key) {
                    $left_buttons[] = array(
                        'icon'      => 'arrow-left',
                        'title'     => $prev_title,
                        'reference' => 'warehouse/'.$data['_parent']->get('Timeseries Parent Key').'/leakages/'.$data['_parent']->id.'/'.$prev_key
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
                        'reference' => 'warehouse/'.$data['_parent']->get('Timeseries Parent Key').'/leakages/'.$data['_parent']->id.'/'.$next_key
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
            print_r($error_info = $db->errorInfo());
            exit;
        }


    }


    $sections = get_sections('warehouses', $data['_parent']->get('Timeseries Parent Key'));


    if (isset($sections[$_section])) {
        $sections[$_section]['selected'] = true;
    }


    $title = _("Leakages").' <span class="id">'.$timeseries->get('Code').'</span>';


    $right_buttons = array();

    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => $title,
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search locations')
        )

    );
    $smarty->assign('_content', $_content);


    return array(
        $_content['search'],
        $smarty->fetch('top_menu.tpl'),
        $smarty->fetch('au_header.tpl')
    );

}


function get_returns_navigation($data, $smarty, $user, $db, $account) {


    $sections = get_sections('warehouses', $data['_parent']->id);

    $sections_class = '';
    $title          = _('Returns');


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
            'placeholder' => _('Search warehouse')
        )

    );
    $smarty->assign('_content', $_content);

    return array(
        $_content['search'],
        $smarty->fetch('top_menu.tpl'),
        $smarty->fetch('au_header.tpl')
    );

}


function get_shipper_new_navigation($data, $smarty, $user, $db, $account) {


    $sections       = get_sections('warehouses', $data['parent_key']);
    $left_buttons   = array();
    $left_buttons[] = array(
        'icon'      => 'arrow-up',
        'title'     => _('Shipping companies'),
        'reference' => 'warehouse/'.$data['parent_key'],
        'metadata'  => "{'tab':'warehouse.shippers'}",
        'parent'    => ''
    );

    $right_buttons = array();

    $sections['shippers']['selected'] = true;


    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => _("New shipping company"),
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search locations')
        )

    );
    $smarty->assign('_content', $_content);

    return array(
        $_content['search'],
        $smarty->fetch('top_menu.tpl'),
        $smarty->fetch('au_header.tpl')
    );


}


function get_shipper_navigation($data, $smarty, $user, $db, $account) {


    $warehouse = $data['warehouse'];
    $object    = $data['_object'];

    $left_buttons = array();


    switch ($data['parent']) {
        case 'warehouse':
            $tab      = 'shippers';
            $_section = 'warehouse';
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
                    "select `Shipper Code` object_name,`Shipper Key` as object_key from %s %s %s
	                and ($_order_field < %s OR ($_order_field = %s AND `Shipper Key` < %d))  order by $_order_field desc , `Shipper Key` desc limit 1", $table, $where, $wheref, prepare_mysql($_order_field_value), prepare_mysql($_order_field_value), $object->id
                );
                if ($result = $db->query($sql)) {
                    if ($row = $result->fetch()) {
                        $prev_key   = $row['object_key'];
                        $prev_title = _("Shipper").' '.$row['object_name'];
                    }
                }


                //print $sql;
                $sql = sprintf(
                    "select `Shipper Code` object_name,`Shipper Key` as object_key from  %s %s %s
	                and ($_order_field  > %s OR ($_order_field  = %s AND `Shipper Key` > %d))  order by $_order_field   , `Shipper Key`  limit 1", $table, $where, $wheref, prepare_mysql($_order_field_value), prepare_mysql($_order_field_value), $object->id
                );

                if ($result = $db->query($sql)) {
                    if ($row = $result->fetch()) {
                        $next_key   = $row['object_key'];
                        $next_title = _("Shipper").' '.$row['object_name'];
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
                            'title'     => _(
                                'Warehouse'
                            ),
                            'reference' => 'warehouse/'.$data['parent_key'],
                            'parent'    => ''
                        );


                        if ($prev_key) {
                            $left_buttons[] = array(
                                'icon'      => 'arrow-left',
                                'title'     => $prev_title,
                                'reference' => 'warehouse/'.$data['parent_key'].'/shippers/'.$prev_key
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
                                'reference' => 'warehouse/'.$data['parent_key'].'/shippers/'.$next_key
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


    $title = _('Shipper').' <span  class="id Shipper_Code" >'.$data['_object']->get('Code').'</span>';

    if (!$user->can_view('locations')) {


        $title = _('Access forbidden').' <i class="fa fa-lock "></i>';
    } elseif (!in_array($warehouse->id, $user->warehouses)) {


        $title = ' <i class="fa fa-lock padding_right_10"></i>'.$title;
    }

    $_content = array(

        'sections_class' => '',
        'sections'       => $sections,

        'left_buttons'  => $left_buttons,
        'right_buttons' => $right_buttons,
        'title'         => $title,
        'search'        => array(
            'show'        => true,
            'placeholder' => _('Search consignments')
        )

    );
    $smarty->assign('_content', $_content);

    return array(
        $_content['search'],
        $smarty->fetch('top_menu.tpl'),
        $smarty->fetch('au_header.tpl')
    );

}


function get_warehouse_area_navigation($data, $smarty, $user, $db, $account) {


    $warehouse = $data['warehouse'];
    $object    = $data['_object'];

    $left_buttons = array();


    switch ($data['parent']) {
        case 'warehouse':
            $tab      = 'warehouse.areas';
            $_section = 'warehouse_areas';
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
                    "select `Warehouse Area Code` object_name,`Warehouse Area Key` as object_key from %s %s %s
	                and ($_order_field < %s OR ($_order_field = %s AND `Warehouse Area Key` < %d))  order by $_order_field desc , `Warehouse Area Key` desc limit 1", $table, $where, $wheref, prepare_mysql($_order_field_value), prepare_mysql($_order_field_value),
                    $object->id
                );


                if ($result = $db->query($sql)) {
                    if ($row = $result->fetch()) {
                        $prev_key   = $row['object_key'];
                        $prev_title = _("Warehouse area").' '.$row['object_name'].' ('.$row['object_key'].')';
                    }
                }


                $sql = sprintf(
                    "select `Warehouse Area Code` object_name,`Warehouse Area Key` as object_key from  %s %s %s
	                and ($_order_field  > %s OR ($_order_field  = %s AND `Warehouse Area Key` > %d))  order by $_order_field   , `Warehouse Area Key`  limit 1", $table, $where, $wheref, prepare_mysql($_order_field_value), prepare_mysql($_order_field_value), $object->id
                );

                if ($result = $db->query($sql)) {
                    if ($row = $result->fetch()) {
                        $next_key   = $row['object_key'];
                        $next_title = _("Warehouse area").' '.$row['object_name'].' ('.$row['object_key'].')';
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
                            'title'     => _('Warehouse areas'),
                            'reference' => 'warehouse/'.$data['parent_key'].'/areas',
                            'parent'    => ''
                        );


                        if ($prev_key) {
                            $left_buttons[] = array(
                                'icon'      => 'arrow-left',
                                'title'     => $prev_title,
                                'reference' => 'warehouse/'.$data['parent_key'].'/area/'.$prev_key
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
                                'reference' => 'warehouse/'.$data['parent_key'].'/area/'.$next_key
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


    $title = _('Warehouse area').': <span  class="id Warehouse_Area_Code" >'.$data['_object']->get('Code').'</span>';

    $title.=' <i id="_External_Warehouse_icon" title="'._('External warehouse').'" style="color:tomato" class="small padding_left_10  fal  fa-garage-car '.($data['_object']->get('Warehouse Area Place')!='External'?'hide':'').'  "></i>';


    if (!$user->can_view('locations')) {


        $title = _('Access forbidden').' <i class="fa fa-lock "></i>';
    } elseif (!in_array($warehouse->id, $user->warehouses)) {


        $title = ' <i class="fa fa-lock padding_right_10"></i>'.$title;
    }

    $_content = array(

        'sections_class' => '',
        'sections'       => $sections,

        'left_buttons'  => $left_buttons,
        'right_buttons' => $right_buttons,
        'title'         => $title,
        'search'        => array(
            'show'        => true,
            'placeholder' => _('Search warehouse')
        )

    );
    $smarty->assign('_content', $_content);

    return array(
        $_content['search'],
        $smarty->fetch('top_menu.tpl'),
        $smarty->fetch('au_header.tpl')
    );

}


function get_return_navigation($data, $smarty, $user, $db, $account) {


    $object = $data['_object'];


    if (!($object->get('Supplier Delivery Parent') == 'Order' and $object->get('Supplier Delivery Parent Key'))) {
        return '';
    }

    $left_buttons  = array();
    $right_buttons = array();

    if ($data['parent']) {

        switch ($data['parent']) {

            case 'warehouse':
                $tab      = 'warehouse.returns';
                $_section = 'returns';
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


                }


            }
        } else {
            print_r($error_info = $db->errorInfo());
            exit;
        }


        if ($data['parent'] == 'warehouse') {
            $up_button = array(
                'icon'      => 'arrow-up',
                'title'     => _("Returns"),
                'reference' => 'warehouse/'.$data['parent_key'].'/returns'
            );

            if ($prev_key) {
                $left_buttons[] = array(
                    'icon'      => 'arrow-left',
                    'title'     => $prev_title,
                    'reference' => 'warehouse/'.$data['parent_key'].'/returns/'.$prev_key
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
                    'reference' => 'warehouse/'.$data['parent_key'].'/returns/'.$next_key
                );

            } else {
                $left_buttons[] = array(
                    'icon'  => 'arrow-right disabled',
                    'title' => '',
                    'url'   => ''
                );

            }

            $sections           = get_sections('warehouses', $data['parent_key']);
            $search_placeholder = _('Search warehouse');


        }

    } else {
        exit;

    }


    if (isset($sections[$_section])) {
        $sections[$_section]['selected'] = true;
    }


    $order = get_object('order', $object->get('Supplier Delivery Parent Key'));


    $title = sprintf(
        _('Return %s for %s'), '<span class="id Supplier_Delivery_Public_ID">'.$object->get('Public ID').'</span>',
        '<span class="button small "  onClick="change_view(\'/orders/'.$order->get('Order Store Key').'/order/'.$order->id.'\')"  > <i class="fa small fa-shopping-cart " title="'._('Order').'"  ></i> <span class="link Order_Public_ID " >'.$order->get('Order Public ID')
        .'</span></span>'

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


    return array(
        $_content['search'],
        $smarty->fetch('top_menu.tpl'),
        $smarty->fetch('au_header.tpl')
    );

}


function get_upload_navigation($data, $smarty, $user, $db) {


    $left_buttons  = array();
    $right_buttons = array();


    $warehouse = $data['warehouse'];
    $sections  = get_sections('warehouses', $warehouse->id);
    $_section  = 'locations';
    if (isset($sections[$_section])) {
        $sections[$_section]['selected'] = true;
    };


    if ($data['_object']->get('Upload Type') == 'EditObjects') {

        switch ($data['parent']) {
            case 'warehouse':


                switch ($data['_object']->get('Upload Object')) {

                    case 'warehouse_area':

                        $up_button = array(
                            'icon'      => 'arrow-up',
                            'title'     => _('Warehouse areas'),
                            'reference' => 'warehouse/'.$data['parent_key'].'/areas'
                        );

                        $title = sprintf(
                            _('Editing %s from warehouse (%s)'), '<span class="id">'._('warehouse areas').'</span>', '<span>'.$data['_parent']->get('Code').'</span>'
                        );


                        break;
                    case 'location':


                        $up_button = array(
                            'icon'      => 'arrow-up',
                            'title'     => _('Locations'),
                            'reference' => 'warehouse/'.$data['parent_key'].'/locations'
                        );

                        $title = sprintf(
                            _('Editing %s from warehouse (%s)'), '<span class="id">'._('locations').'</span>', '<span>'.$data['_parent']->get('Code').'</span>'
                        );


                        break;
                    default:
                        $title = '';

                }
                break;
            case 'warehouse_area':

                $up_button = array(
                    'icon'      => 'arrow-up',
                    'title'     => _('Warehouse area').' ('.$data['_parent']->get('Code').')',
                    'reference' => 'warehouse/'.$warehouse->id.'/areas/'.$data['parent_key']
                );
                switch ($data['_object']->get('Upload Object')) {


                    case 'location':
                        $title = sprintf(
                            _('Editing %s from warehouse area (%s)'), '<span class="id">'._('locations').'</span>', '<span>'.$data['_parent']->get('Code').'</span>'
                        );


                        break;
                    default:
                        $title = '';

                }
                break;

        }


    } else {

        switch ($data['parent']) {
            case 'warehouse':


                switch ($data['_object']->get('Upload Object')) {

                    case 'warehouse_area':

                        $up_button = array(
                            'icon'      => 'arrow-up',
                            'title'     => _('Warehouse areas'),
                            'reference' => 'warehouse/'.$data['parent_key'].'/areas'
                        );

                        $title = sprintf(
                            _('Adding %s to warehouse (%s)'), '<span class="id">'._('warehouse areas').'</span>', '<span>'.$data['_parent']->get('Code').'</span>'
                        );


                        break;
                    case 'location':


                        $up_button = array(
                            'icon'      => 'arrow-up',
                            'title'     => _('Locations'),
                            'reference' => 'warehouse/'.$data['parent_key'].'/locations'
                        );

                        $title = sprintf(
                            _('Adding %s to warehouse (%s)'), '<span class="id">'._('locations').'</span>', '<span>'.$data['_parent']->get('Code').'</span>'
                        );


                        break;
                    default:
                        $title = '';

                }
                break;
            case 'warehouse_area':

                $up_button = array(
                    'icon'      => 'arrow-up',
                    'title'     => _('Warehouse area').' ('.$data['_parent']->get('Code').')',
                    'reference' => 'warehouse/'.$warehouse->id.'/areas/'.$data['parent_key']
                );
                switch ($data['_object']->get('Upload Object')) {


                    case 'location':
                        $title = sprintf(
                            _('Adding %s to warehouse area (%s)'), '<span class="id">'._('locations').'</span>', '<span>'.$data['_parent']->get('Code').'</span>'
                        );


                        break;
                    default:
                        $title = '';

                }
                break;

        }

    }


    /*
    exit;

    if ($data['parent'] == 'warehouse') {


        $sections = get_sections('warehouses', $data['_parent']->id);


        if ($data['_object']->get('Upload Type') == 'EditObjects') {
            switch ($data['_object']->get('Upload Object')) {

                case 'warehouse_area':
                    $title = sprintf(
                        _('Editing %s (%s) locations'), '<span >'._('warehouse areas').'</span>', '<span class="id">'.$data['_parent']->get('Code').'</span>'
                    );

                    $up_button = array(
                        'icon'      => 'arrow-up',
                        'title'     => _('Warehouse areas').' ('.$data['_parent']->get('Code').')',
                        'reference' => 'warehouse/'.$data['parent_key'].'/areas'
                    );
                    break;
                case 'location':
                    $title     = sprintf(
                        _('Editing %s (%s) locations'), '<span >'._('warehouse areas').'</span>', '<span class="id">'.$data['_parent']->get('Code').'</span>'
                    );
                    $up_button = array(
                        'icon'      => 'arrow-up',
                        'title'     => _('Locations').' ('.$data['_parent']->get('Code').')',
                        'reference' => 'warehouse/'.$data['parent_key'].'/locations'
                    );
                    break;
                default:
                    $title = '';

            }
        } else {
            switch ($data['_object']->get('Upload Object')) {

                case 'warehouse_area':
                    $title = sprintf(
                        _('Upload %s into warehouse %s'), '<span class="id">'._('warehouse areas').'</span>', '<span class="id">'.$data['_parent']->get('Code').'</span>'
                    );

                    $up_button = array(
                        'icon'      => 'arrow-up',
                        'title'     => _('Warehouse areas').' ('.$data['_parent']->get('Code').')',
                        'reference' => 'warehouse/'.$data['parent_key'].'/areas'
                    );
                    break;
                case 'location':
                    $title     = sprintf(
                        _('Upload %s into warehouse %s'), '<span class="id">'._('locations').'</span>', '<span class="id">'.$data['_parent']->get('Code').'</span>'
                    );
                    $up_button = array(
                        'icon'      => 'arrow-up',
                        'title'     => _('Locations').' ('.$data['_parent']->get('Code').')',
                        'reference' => 'warehouse/'.$data['parent_key'].'/locations'
                    );
                    break;
                default:
                    $title = '';

            }
        }


    }

*/
    $left_buttons[] = $up_button;

    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => $title,
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search inventory')
        )

    );
    $smarty->assign('_content', $_content);


    return array(
        $_content['search'],
        $smarty->fetch('top_menu.tpl'),
        $smarty->fetch('au_header.tpl')
    );

}


function get_feedback_navigation($data, $smarty, $user, $db) {


    $left_buttons                     = array();
    $right_buttons                    = array();
    $sections                         = get_sections('warehouses', $data['key']);
    $sections['feedback']['selected'] = true;


    $_content = array(

        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => _('Issues'),
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search warehouse')
        )

    );
    $smarty->assign('_content', $_content);

    return array(
        $_content['search'],
        $smarty->fetch('top_menu.tpl'),
        $smarty->fetch('au_header.tpl')
    );

}


function get_production_delivery_navigation($data, $smarty, $user, $db) {


    $object        = $data['_object'];
    $left_buttons  = array();
    $right_buttons = array();


    $tab      = 'warehouse.production_deliveries.'.$data['extra'];
    $_section = 'production_deliveries';


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
        'reference' => 'warehouse/'.$data['parent_key'].'/production_deliveries/'.$data['extra']
    );

    if ($prev_key) {
        $left_buttons[] = array(
            'icon'      => 'arrow-left',
            'title'     => $prev_title,
            'reference' => 'warehouse/'.$data['parent_key'].'/production_deliveries/'.$data['extra'].'/'.$prev_key
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
            'reference' => 'warehouse/'.$data['parent_key'].'/production_deliveries/'.$data['extra'].'/'.$next_key

        );

    } else {
        $left_buttons[] = array(
            'icon'  => 'arrow-right disabled',
            'title' => '',
            'url'   => ''
        );

    }


    $sections           = get_sections('warehouses', $data['parent_key']);
    $search_placeholder = _('Search warehouses');

    if (isset($sections[$_section])) {
        $sections[$_section]['selected'] = true;
    }


    $title = _('Production delivery').' <span class="id Supplier_Delivery_Public_ID">'.$object->get('Public ID').'</span> ';


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


    return array(
        $_content['search'],
        $smarty->fetch('top_menu.tpl'),
        $smarty->fetch('au_header.tpl')
    );

}


