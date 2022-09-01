<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 15 September 2015 13:12:32 GMT+8 Kuala Lumpur

 Copyright (c) 2015, Inikoo

 Version 3.0
*/

function get_store_navigation($data, $smarty, $user, $db, $account)
{
    require_once 'class.Store.php';

    $store = $data['_object'];


    switch ($data['section']) {
        case 'settings':
            $link    = '/settings';
            $section = 'settings';
            $title   = sprintf(_('Settings for store %s'), ' <span class="Store_Code id">'.$store->get('Code').'</span>');

            break;
        default:
            $link    = '';
            $section = 'store';
            $title   = _('Store').' <span class="Store_Code id">'.$store->get('Code').'</span>';
    }


    $left_buttons = array();
    if ($user->stores > 1) {
        list($prev_key, $next_key) = get_prev_next($store->id, $user->stores);

        $sql = sprintf(
            "SELECT `Store Code` FROM `Store Dimension` WHERE `Store Key`=%d",
            $prev_key
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
            "SELECT `Store Code` FROM `Store Dimension` WHERE `Store Key`=%d",
            $next_key
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
            'reference' => 'store/'.$prev_key.$link
        );
        $left_buttons[] = array(
            'icon'      => 'arrow-up',
            'title'     => _('Stores'),
            'reference' => 'stores',
            'parent'    => ''
        );
        $left_buttons[] = array(
            'icon'      => 'arrow-right',
            'title'     => $next_title,
            'reference' => 'store/'.$next_key.$link
        );
    }


    $right_buttons = array();

    foreach ($store->get_websites('data') as $website_data) {
        $right_buttons[] = array(
            'icon'     => 'globe',
            'title'    => $website_data['Website Name'],
            'click'    => "change_view('/website/".$website_data['Website Key']."')",
            'pre_text' => $website_data['Website Code'],
            'class'    => 'text width_250'
        );
    }

    $sections = get_sections('products', $store->id);
    $_section = $section;
    if (isset($sections[$_section])) {
        $sections[$_section]['selected'] = true;
    }


    if (!in_array($data['key'], $user->stores)) {
        $title = ' <i class="fa fa-lock padding_right_10"></i>'.$title;
    }

    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => $title,
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search products').' '.$store->get('Store Code')
        )

    );
    $smarty->assign('_content', $_content);

    return array(
        $_content['search'],
        $smarty->fetch('top_menu.tpl'),
        $smarty->fetch('au_header.tpl')
    );
}


function get_store_dashboard_navigation($data, $smarty, $user, $db, $account)
{
    require_once 'class.Store.php';

    $store = new Store($data['key']);

    $block_view = $data['section'];


    $left_buttons = array();
    if ($user->stores > 1) {
        list($prev_key, $next_key) = get_prev_next($store->id, $user->stores);

        $sql = sprintf(
            "SELECT `Store Code` FROM `Store Dimension` WHERE `Store Key`=%d",
            $prev_key
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
            "SELECT `Store Code` FROM `Store Dimension` WHERE `Store Key`=%d",
            $next_key
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
            'reference' => 'store/'.$prev_key
        );
        $left_buttons[] = array(
            'icon'      => 'arrow-up',
            'title'     => _('Stores'),
            'reference' => 'stores',
            'parent'    => ''
        );

        $left_buttons[] = array(
            'icon'      => 'arrow-right',
            'title'     => $next_title,
            'reference' => 'store/'.$next_key
        );
    }


    $right_buttons   = array();
    $right_buttons[] = array(
        'icon'      => 'edit',
        'title'     => _('Edit store'),
        'reference' => 'store/'.$store->id.'/edit'
    );
    $right_buttons[] = array(
        'icon'  => 'plus',
        'title' => _('New store'),
        'id'    => "new_store"
    );
    $sections        = get_sections('products', $store->id);
    $_section        = 'dashboard';
    if (isset($sections[$_section])) {
        $sections[$_section]['selected'] = true;
    }


    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,

        'left_buttons'  => $left_buttons,
        'right_buttons' => $right_buttons,
        'title'         => _("Store's dashboard").' <span class="id">'.$store->get('Code').'</span>',
        'search'        => array(
            'show'        => true,
            'placeholder' => _('Search products').' '.$store->get('Store Code')
        )

    );
    $smarty->assign('_content', $_content);

    return array(
        $_content['search'],
        $smarty->fetch('top_menu.tpl'),
        $smarty->fetch('au_header.tpl')
    );
}


function get_products_categories_navigation($data, $smarty, $user, $db, $account)
{
    require_once 'class.Store.php';

    switch ($data['parent']) {
        case 'store':
            $store = new Store($data['parent_key']);
            break;
        default:

            break;
    }

    $block_view = $data['section'];


    $left_buttons = array();
    if ($user->stores > 1) {
        list($prev_key, $next_key) = get_prev_next($store->id, $user->stores);

        $sql = sprintf(
            "SELECT `Store Code` FROM `Store Dimension` WHERE `Store Key`=%d",
            $prev_key
        );
        if ($result = $db->query($sql)) {
            if ($row = $result->fetch()) {
                $prev_title = _("Product's Categories").' '.$row['Store Code'];
            } else {
                $prev_title = '';
            }
        } else {
            print_r($error_info = $db->errorInfo());
            exit;
        }


        $sql = sprintf(
            "SELECT `Store Code` FROM `Store Dimension` WHERE `Store Key`=%d",
            $next_key
        );

        if ($result = $db->query($sql)) {
            if ($row = $result->fetch()) {
                $next_title = _("Product's Categories").' '.$row['Store Code'];
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
            'reference' => 'products/'.$next_key.'/categories'
        );

        $left_buttons[] = array(
            'icon'      => 'arrow-right',
            'title'     => $next_title,
            'reference' => 'products/'.$next_key.'/categories'
        );
    }


    $right_buttons = array();


    foreach ($data['store']->get_websites('data') as $website_data) {
        $right_buttons[] = array(
            'icon'     => 'browser',
            'title'    => sprintf(_('%s webpages'), $website_data['Website Name']),
            'click'    => "change_view('/webpages/".$website_data['Website Key']."')",
            'pre_text' => $website_data['Website Code'],
            'class'    => 'text'
        );
    }

    //$right_buttons[]=array('icon'=>'edit', 'title'=>_('Edit'), 'url'=>"edit_customer_categories.php?store_id=".$store->id);

    $sections = get_sections('products', $store->id);
    if (isset($sections[$data['section']])) {
        $sections[$data['section']]['selected'] = true;
    }

    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => _("Product's Categories").' <span class="id">'.$store->get('Store Code').'</span>',
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search products')
        )

    );
    $smarty->assign('_content', $_content);

    return array(
        $_content['search'],
        $smarty->fetch('top_menu.tpl'),
        $smarty->fetch('au_header.tpl')
    );
}


function get_stores_navigation($data, $smarty, $user, $db, $account)
{
    $block_view = $data['section'];


    $sections = get_sections('products_server');


    $left_buttons = array();


    $right_buttons = array();

    if (isset($sections[$data['section']])) {
        $sections[$data['section']]['selected'] = true;
    }


    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => _('Stores'),
        'search'         => array(
            'show'        => true,
            'placeholder' => _(
                'Search products all stores'
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


function get_products_all_stores_navigation($data, $smarty, $user, $db, $account)
{
    $block_view = $data['section'];


    $sections = get_sections('products_server');


    $left_buttons = array();


    $right_buttons = array();

    if (isset($sections[$data['section']])) {
        $sections[$data['section']]['selected'] = true;
    }


    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => _('Product').' ('._('All stores').')',
        'search'         => array(
            'show'        => true,
            'placeholder' => _(
                'Search products all stores'
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


function get_products_category_navigation($data, $smarty, $user, $db)
{
    $category = $data['_object'];

    $left_buttons  = array();
    $right_buttons = array();


    switch ($data['parent']) {
        case 'category':

            $parent_category = new Category($data['parent_key']);


            $up_button = array(
                'icon'      => 'arrow-up',
                'title'     => _("Product's Categories").' '.$data['store']->data['Store Code'],
                'reference' => 'products/'.$data['store']->id.'/category/'.$parent_category->id
            );

            if ($data['_parent']->id == $data['_parent']->get(
                    'Category Root Key'
                )) {
                $tab = 'category.product_categories.categories';
            } else {
                $tab = 'subject_categories';
            }

            $parent_categories = $parent_category->get('Category Position');
            break;
        case 'store':


            $up_button         = array(
                'icon'      => 'arrow-up',
                'title'     => _("Product's Categories").' '.$data['store']->data['Store Code'],
                'reference' => 'products/'.$data['store']->id.'/categories'
            );
            $tab               = 'products.categories';
            $parent_categories = '';
            break;

        default:

            break;
    }


    //print $tab;

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
    $parameters['subject']    = 'product';
    include_once 'prepare_table/'.$tab.'.ptble.php';


    $_order_field = $order;


    if ($_order_field == 'products') {
        $_order_field = '(`Product Category Active Products`+`Product Category Discontinuing Products`)';
    }

    $order = preg_replace('/^.*\.`/', '', $order);
    $order = preg_replace('/^`/', '', $order);
    $order = preg_replace('/`$/', '', $order);


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
            //   print $order_direction;

            $sql = sprintf(
                "select C.`Category Label` object_name,C.`Category Key` as object_key %s from %s and ($_order_field < %s OR ($_order_field = %s AND C.`Category Key` < %d))  order by $_order_field desc , C.`Category Key` desc limit 1",
                $extra_field,
                "$table   $where $wheref",
                prepare_mysql($_order_field_value),
                prepare_mysql($_order_field_value),
                $category->id
            );

            // print $sql;

            if ($result = $db->query($sql)) {
                if ($row = $result->fetch()) {
                    $prev_key   = $row['object_key'];
                    $prev_title = _("Product").' '.$row['object_name'].' ('.$row['object_key'].')';
                    if ($extra_field) {
                        $prev_extra_field_value = $row['extra_field'];
                    }
                }
            } else {
                print_r($error_info = $db->errorInfo());
                print $sql;
                exit;
            }

            //

            $sql = sprintf(
                "select C.`Category Label` object_name,C.`Category Key` as object_key %s from %s
	                and ($_order_field  > %s OR ($_order_field  = %s AND C.`Category Key`> %d))  order by $_order_field   , C.`Category Key` limit 1",
                $extra_field,
                "$table $where $wheref",
                prepare_mysql($_order_field_value),
                prepare_mysql($_order_field_value),
                $category->id
            );


            if ($result = $db->query($sql)) {
                if ($row = $result->fetch()) {
                    $next_key   = $row['object_key'];
                    $next_title = _("Product").' '.$row['object_name'].' ('.$row['object_key'].')';
                    if ($extra_field) {
                        $next_extra_field_value = $row['extra_field'];
                    }
                }
            } else {
                print_r($error_info = $db->errorInfo());
                exit;
            }


            // print $order_direction;


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
            'reference' => 'products/'.$data['store']->id.'/category/'.$parent_categories.$prev_key
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
            'reference' => 'products/'.$data['store']->id.'/category/'.$parent_categories.$next_key
        );
    } else {
        $left_buttons[] = array(
            'icon'  => 'arrow-right disabled',
            'title' => '',
            'url'   => ''

        );
    }


    if ($data['store']->get('Store Department Category Key') == $data['_object']->get('Category Root Key')) {
        if ($data['_object']->get('Category Root Key') != $data['_object']->id) {
            $category_title_label = _('Department').' ';
            $title                = $category_title_label.'<span class="Category_Code id">'.$data['_object']->get('Code').'</span>';
        } else {
            $title = sprintf(_('%s departments'), $data['store']->get('Code'));
        }
    } elseif ($data['store']->get('Store Family Category Key') == $data['_object']->get('Category Root Key')) {
        if ($data['_object']->get('Category Root Key') != $data['_object']->id) {
            $category_title_label = _('Family').' ';
            $title                = $category_title_label.'<span class="Category_Code id">'.$data['_object']->get('Code').'</span>';
        } else {
            $title = sprintf(_('%s families'), $data['store']->get('Code'));
        }
    } else {
        $category_title_label = _('Category');
        $title                = $category_title_label.' <span class="Category_Code id">'.$data['_object']->get('Code').'</span>';
    }


    if ($data['_object']->get('Category Branch Type') == 'Head' and $data['_object']->get('Product Category Status') != 'Active') {
        $title .= ' ('.$data['_object']->get('Status').')';
    }


    //$right_buttons[]=array('icon'=>'edit', 'title'=>_('Edit'), 'url'=>"edit_product_categories.php?store_id=".$data['store']->id);

    $sections                           = get_sections('products', $data['store']->id);
    $sections['categories']['selected'] = true;

    $right_buttons[] = array(

        'icon'  => 'sticky-note',
        'title' => _('Sticky note'),
        'class' => 'open_sticky_note  square_button right object_sticky_note  '.($data['_object']->get('Sticky Note') == '' ? '' : 'hide')
    );


    if ($data['_object']->get('Product Category Webpage Key') > 0) {
        $right_buttons[] = array(

            'icon'      => 'browser',
            'reference' => 'website/'.$data['store']->get('Store Website Key').'/webpage/'.$data['_object']->get('Product Category Webpage Key'),
            'title'     => _('Webpage'),
            'class'     => 'button'
        );
    } else {
        $right_buttons[] = array(
            'html_icon' => '<i data-category_key="'.$data['_object']->id.'" class="fal fa-browser"></i>',

            'title' => _('Create webpage'),
            'id'    => 'create_website',
            'class' => 'button create_category_webpage'
        );
    }


    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => $title,
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search products')
        )

    );
    $smarty->assign('_content', $_content);

    return array(
        $_content['search'],
        $smarty->fetch('top_menu.tpl'),
        $smarty->fetch('au_header.tpl')
    );
}


function get_new_store_navigation($data, $smarty, $user, $db, $account)
{
    $block_view = $data['section'];


    $sections = get_sections('products_server');


    $left_buttons = array();

    $left_buttons[] = array(
        'icon'      => 'arrow-up',
        'title'     => _('Stores'),
        'reference' => 'stores',
        'parent'    => ''
    );


    $right_buttons = array();

    if (isset($sections[$data['section']])) {
        $sections[$data['section']]['selected'] = true;
    }


    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => _('New store'),
        'search'         => array(
            'show'        => true,
            'placeholder' => _(
                'Search products all stores'
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

function get_products_navigation($data, $smarty, $user, $db, $account)
{
    if ($data['parent'] != 'store') {
        exit('error PNav');
    }


    $left_buttons = array();
    if ($user->stores > 1) {
        list($prev_key, $next_key) = get_prev_next(
            $data['store']->id,
            $user->stores
        );

        $sql = sprintf(
            "SELECT `Store Code` FROM `Store Dimension` WHERE `Store Key`=%d",
            $prev_key
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
            "SELECT `Store Code` FROM `Store Dimension` WHERE `Store Key`=%d",
            $next_key
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
            'reference' => 'products/'.$prev_key
        );
        $left_buttons[] = array(
            'icon'      => 'arrow-up',
            'title'     => _('Stores'),
            'reference' => 'stores',
            'parent'    => ''
        );

        $left_buttons[] = array(
            'icon'      => 'arrow-right',
            'title'     => $next_title,
            'reference' => 'products/'.$next_key
        );
    }


    $right_buttons = array();
    //$right_buttons[]=array('icon'=>'edit', 'title'=>_('Edit store'), 'reference'=>'store/'.$data['store']->id.'/edit');
    //$right_buttons[]=array('icon'=>'plus', 'title'=>_('New store'), 'id'=>"new_store");


    foreach ($data['store']->get_websites('data') as $website_data) {
        $right_buttons[] = array(
            'icon'     => 'browser',
            'title'    => sprintf(_('%s webpages'), $website_data['Website Name']),
            'click'    => "change_view('/webpages/".$website_data['Website Key']."')",
            'pre_text' => $website_data['Website Code'],
            'class'    => 'text'
        );
    }


    $sections = get_sections('products', $data['store']->id);
    $_section = 'products';
    if (isset($sections[$_section])) {
        $sections[$_section]['selected'] = true;
    }


    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,

        'left_buttons'  => $left_buttons,
        'right_buttons' => $right_buttons,
        'title'         => _('Products').' <span class="id">'.$data['store']->get('Code').'</span>',
        'search'        => array(
            'show'        => true,
            'placeholder' => _('Search products').' '.$data['store']->get('Store Code')
        )

    );
    $smarty->assign('_content', $_content);

    return array(
        $_content['search'],
        $smarty->fetch('top_menu.tpl'),
        $smarty->fetch('au_header.tpl')
    );
}


function get_product_navigation($data, $smarty, $user, $db, $account)
{
    $object = $data['_object'];


    $left_buttons = array();


    if ($data['parent']) {
        switch ($data['parent']) {
            case 'store':
                $tab      = 'store.products';
                $_section = 'products';
                break;
            case 'part':
                $tab      = 'part.products';
                $_section = 'inventory';
                break;
            case 'department':
                $tab      = 'department.products';
                $_section = 'products';
                break;
            case 'family':
                $tab      = 'family.products';
                $_section = 'products';
                break;
            case 'order':
                $tab      = 'order.items';
                $_section = 'orders';
                break;
            case 'category':
                $tab      = 'category.products';
                $_section = 'products';
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

        $_order_field = $order;
        $order        = preg_replace('/^.*\.`/', '', $order);
        $order        = preg_replace('/^`/', '', $order);
        $order        = preg_replace('/`$/', '', $order);

        if ($data['parent'] == 'order') {
            $_order_field_value = $data['otf'];
            $extra_field        = ',OTF.`Order Transaction Fact Key` as extra_field';
        } else {
            $_order_field_value = $object->get($order);
            $extra_field        = '';
        }

        $prev_title             = '';
        $next_title             = '';
        $prev_key               = 0;
        $next_key               = 0;
        $prev_extra_field_value = '';
        $next_extra_field_value = '';

        $sql = trim($sql_totals." $wheref");


        if ($result2 = $db->query($sql)) {
            if ($row2 = $result2->fetch() and $row2['num'] > 1) {
                $sql = sprintf(
                    "select P.`Product Code` object_name,P.`Product ID` as object_key %s from %s
	                and ($_order_field < %s OR ($_order_field = %s AND P.`Product ID` < %d))  order by $_order_field desc , P.`Product ID` desc limit 1",
                    $extra_field,
                    " $table   $where $wheref ",
                    prepare_mysql($_order_field_value),
                    prepare_mysql($_order_field_value),
                    $object->id
                );


                if ($result = $db->query($sql)) {
                    if ($row = $result->fetch()) {
                        $prev_key   = $row['object_key'];
                        $prev_title = _("Product").' '.$row['object_name'].' ('.$row['object_key'].')';
                        if ($extra_field) {
                            $prev_extra_field_value = $row['extra_field'];
                        }
                    }
                } else {
                    print_r($error_info = $db->errorInfo());
                    exit;
                }


                $sql = sprintf(
                    "select P.`Product Code` object_name,P.`Product ID` as object_key %s from %s
	                and ($_order_field  > %s OR ($_order_field  = %s AND P.`Product ID` > %d))  order by $_order_field   , P.`Product ID`  limit 1",
                    $extra_field,
                    " $table   $where $wheref ",
                    prepare_mysql($_order_field_value),
                    prepare_mysql($_order_field_value),
                    $object->id
                );


                if ($result = $db->query($sql)) {
                    if ($row = $result->fetch()) {
                        $next_key   = $row['object_key'];
                        $next_title = _("Product").' '.$row['object_name'].' ('.$row['object_key'].')';
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
    } else {
        $_section = 'products';
    }


    switch ($data['parent']) {
        case 'store':


            $up_button = array(
                'icon'      => 'arrow-up',
                'title'     => _("Store").' ('.$data['store']->get(
                        'Code'
                    ).')',
                'reference' => 'products/'.$data['store']->id
            );

            if ($prev_key) {
                $left_buttons[] = array(
                    'icon'      => 'arrow-left',
                    'title'     => $prev_title,
                    'reference' => 'products/'.$data['parent_key'].'/'.$prev_key
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
                    'reference' => 'products/'.$data['parent_key'].'/'.$next_key
                );
            } else {
                $left_buttons[] = array(
                    'icon'  => 'arrow-right disabled',
                    'title' => '',
                    'url'   => ''
                );
            }

            break;
        case 'part':


            $up_button = array(
                'icon'      => 'arrow-up',
                'title'     => _("Part").' ('.$data['_parent']->get(
                        'SKU'
                    ).')',
                'reference' => 'part/'.$data['_parent']->id
            );

            if ($prev_key) {
                $left_buttons[] = array(
                    'icon'      => 'arrow-left',
                    'title'     => $prev_title,
                    'reference' => 'part/'.$data['_parent']->id.'/product/'.$prev_key
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
                    'reference' => 'part/'.$data['_parent']->id.'/product/'.$next_key
                );
            } else {
                $left_buttons[] = array(
                    'icon'  => 'arrow-right disabled',
                    'title' => '',
                    'url'   => ''
                );
            }

            break;
        case 'order':
            $order     = new Order($data['parent_key']);
            $up_button = array(
                'icon'      => 'arrow-up',
                'title'     => _("Order").' ('.$order->get(
                        'Order Public ID'
                    ).')',
                'reference' => 'orders/'.$order->get(
                        'Order Store Key'
                    ).'/'.$order->id
            );

            if ($prev_key) {
                $left_buttons[] = array(
                    'icon'      => 'arrow-left',
                    'title'     => $prev_title,
                    'reference' => 'order/'.$data['parent_key'].'/item/'.$prev_extra_field_value
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
                    'reference' => 'order/'.$data['parent_key'].'/item/'.$next_extra_field_value
                );
            } else {
                $left_buttons[] = array(
                    'icon'  => 'arrow-right disabled',
                    'title' => '',
                    'url'   => ''
                );
            }

            break;

        case 'category':


            $up_button = array(
                'icon'      => 'arrow-up',
                'title'     => $data['_parent']->get('Code'),
                'reference' => 'products/'.$data['_parent']->get(
                        'Category Store Key'
                    ).'/category/'.$data['_parent']->id
            );

            if ($prev_key) {
                $left_buttons[] = array(
                    'icon'      => 'arrow-left',
                    'title'     => $prev_title,
                    'reference' => 'products/'.$data['_parent']->get(
                            'Category Store Key'
                        ).'/category/'.$data['_parent']->get(
                            'Category Position'
                        ).'/product/'.$prev_key
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
                    'reference' => 'products/'.$data['_parent']->get(
                            'Category Store Key'
                        ).'/category/'.$data['_parent']->get(
                            'Category Position'
                        ).'/product/'.$next_key
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

    $right_buttons = array();
    //$right_buttons[]=array('icon'=>'edit','title'=>_('Edit store'),'reference'=>'store/'.$data['store']->id.'/edit');
    $sections = get_sections($_section, $data['store']->id);
    //$_section='products';
    if (isset($sections[$_section])) {
        $sections[$_section]['selected'] = true;
    }

    $overlay_title=_('Product');
    $icon = 'fa-cube';
    if ($object->get('Product Type') == 'Service') {
        $icon = 'fa-spa';
        $overlay_title=_('Service');

    }




    $title = '<i class="fa '.$icon.'" aria-hidden="true" title="'.$overlay_title.'"></i> <span class="id Product_Code Service_Code">'.$object->get('Code').'</span>';



    if($object->get('is_variant')=='Yes'){
        $title='<i class="fal fa-stream"></i> '.$title;
        $parent_product=get_object('Product',$object->get('variant_parent_id'));
        $title.=' <a class="purple" href="'.sprintf('/products/%d/%d',$parent_product->get('Store Key'),$parent_product->id).'"><i class="padding_left_20 fa fa-bring-forward "></i> '.$parent_product->get('Code').'</a>';
    }

    $product_parts = $object->get_parts('objects');

    if (count($product_parts) == 1) {
        $part  = array_values($product_parts)[0];
        $title .= ' <small class="padding_left_10"> <i class="fa fa-long-arrow-left padding_left_10"></i> <i class="fa fa-box button" title="'._('Part').'" onCLick="change_view(\'/part/'.$part->id
            .'\')" ></i> <span class="Part_Reference button"  onCLick="change_view(\'part/'.$part->id.'\')">'.$part->get('Reference').'</small>';
    } elseif (count($product_parts) > 1) {
        $title .= '<span class="small discreet padding_left_20">'._(
                'Multiple parts'
            ).'</span>';
    }


    if ($object->get('Product Customer Key')) {
        $title .= '<small  class="padding_left_20 purple"> <i title="'._('Customer custom product').'" class="far fa-user-shield padding_left_5" aria-hidden="true"></i> '.$object->get('Customer Key').'</small>';
    }


    $right_buttons[] = array(
        'icon'  => 'sticky-note',
        'title' => _('Sticky note'),
        'class' => 'open_sticky_note  square_button right object_sticky_note  '.($object->get('Sticky Note') == '' ? '' : 'hide')
    );


    if ($data['_object']->get('Product Webpage Key') > 0) {
        $right_buttons[] = array(
            'html_icon' => '<i class="far fa-browser"></i>',

            'reference' => 'website/'.$data['store']->get('Store Website Key').'/online/webpage/'.$data['_object']->get('Product Webpage Key'),
            'title'     => _('Webpage'),
            'class'     => 'button'
        );
    }

    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,

        'left_buttons'  => $left_buttons,
        'right_buttons' => $right_buttons,
        'title'         => $title,
        'search'        => array(
            'show'        => true,
            'placeholder' => _('Search products').' '.$object->get('Product Store Code')
        )

    );
    $smarty->assign('_content', $_content);

    return array(
        $_content['search'],
        $smarty->fetch('top_menu.tpl'),
        $smarty->fetch('au_header.tpl')
    );
}


function get_new_product_navigation($data, $smarty, $user, $db, $account)
{
    $left_buttons = array();

    $block_view = $data['section'];


    switch ($data['parent']) {
        case 'store':
            $title                            = sprintf(
                _('New product for %s'),
                '<span class="id">'.$data['store']->get('Code').'</span>'
            );
            $sections                         = get_sections(
                'products',
                $data['parent_key']
            );
            $left_buttons[]                   = array(
                'icon'      => 'arrow-up',
                'title'     => _(
                        'Store'
                    ).': '.$data['store']->get(
                        'Code'
                    ),
                'reference' => 'products/'.$data['store']->id,
                'parent'    => ''
            );
            $sections['products']['selected'] = true;
            break;
        default:
            exit('error in products.nav.php');
            break;
    }


    $right_buttons = array();


    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => $title,
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search products')
        )

    );
    $smarty->assign('_content', $_content);

    return array(
        $_content['search'],
        $smarty->fetch('top_menu.tpl'),
        $smarty->fetch('au_header.tpl')
    );
}

function get_services_navigation($data, $smarty, $user, $db, $account)
{
    require_once 'class.Store.php';


    if ($data['parent'] == 'store') {
    } else {
        exit('');
    }

    $block_view = $data['section'];


    $left_buttons = array();
    if ($user->stores > 1) {
        list($prev_key, $next_key) = get_prev_next(
            $data['store']->id,
            $user->stores
        );

        $sql = sprintf(
            "SELECT `Store Code` FROM `Store Dimension` WHERE `Store Key`=%d",
            $prev_key
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
            "SELECT `Store Code` FROM `Store Dimension` WHERE `Store Key`=%d",
            $next_key
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
            'reference' => 'services/'.$prev_key
        );
        $left_buttons[] = array(
            'icon'      => 'arrow-up',
            'title'     => _('Stores'),
            'reference' => 'stores',
            'parent'    => ''
        );

        $left_buttons[] = array(
            'icon'      => 'arrow-right',
            'title'     => $next_title,
            'reference' => 'services/'.$next_key
        );
    }


    $right_buttons = array();
    //$right_buttons[]=array('icon'=>'edit', 'title'=>_('Edit store'), 'reference'=>'store/'.$data['store']->id.'/edit');
    //$right_buttons[]=array('icon'=>'plus', 'title'=>_('New store'), 'id'=>"new_store");

    $sections = get_sections('products', $data['store']->id);
    $_section = 'services';
    if (isset($sections[$_section])) {
        $sections[$_section]['selected'] = true;
    }


    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,

        'left_buttons'  => $left_buttons,
        'right_buttons' => $right_buttons,
        'title'         => _('Services').' <span class="id">'.$data['store']->get('Code').'</span>',
        'search'        => array(
            'show'        => true,
            'placeholder' => _('Search services').' '.$data['store']->get('Store Code')
        )

    );
    $smarty->assign('_content', $_content);

    return array(
        $_content['search'],
        $smarty->fetch('top_menu.tpl'),
        $smarty->fetch('au_header.tpl')
    );
}


function get_service_navigation($data, $smarty, $user, $db, $account)
{
    $object = $data['_object'];

    $block_view = $data['section'];


    $left_buttons = array();


    if ($data['parent']) {
        switch ($data['parent']) {
            case 'store':
                $tab      = 'store.services';
                $_section = 'services';
                break;
            case 'part':
                $tab      = 'part.services';
                $_section = 'inventory';
                break;
            case 'department':
                $tab      = 'department.services';
                $_section = 'services';
                break;
            case 'family':
                $tab      = 'family.services';
                $_section = 'services';
                break;
            case 'order':
                $tab      = 'order.items';
                $_section = 'orders';
                break;
            case 'category':
                $tab      = 'category.services';
                $_section = 'services';
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

        $_order_field = $order;
        $order        = preg_replace('/^.*\.`/', '', $order);
        $order        = preg_replace('/^`/', '', $order);
        $order        = preg_replace('/`$/', '', $order);

        if ($data['parent'] == 'order') {
            $_order_field_value = $data['otf'];
            $extra_field        = ',OTF.`Order Transaction Fact Key` as extra_field';
        } else {
            $_order_field_value = $object->get($order);
            $extra_field        = '';
        }

        $prev_title             = '';
        $next_title             = '';
        $prev_key               = 0;
        $next_key               = 0;
        $prev_extra_field_value = '';
        $next_extra_field_value = '';

        $sql = trim($sql_totals." $wheref");


        if ($result2 = $db->query($sql)) {
            if ($row2 = $result2->fetch() and $row2['num'] > 1) {
                $sql = sprintf(
                    "select P.`Product Code` object_name,P.`Product ID` as object_key %s from $table   $where $wheref
	                and ($_order_field < %s OR ($_order_field = %s AND P.`Product ID` < %d))  order by $_order_field desc , P.`Product ID` desc limit 1",
                    $extra_field,
                    prepare_mysql($_order_field_value),
                    prepare_mysql($_order_field_value),
                    $object->id
                );

                if ($result = $db->query($sql)) {
                    if ($row = $result->fetch()) {
                        $prev_key   = $row['object_key'];
                        $prev_title = _("Service").' '.$row['object_name'].' ('.$row['object_key'].')';
                        if ($extra_field) {
                            $prev_extra_field_value = $row['extra_field'];
                        }
                    }
                } else {
                    print_r($error_info = $db->errorInfo());
                    exit;
                }


                $sql = sprintf(
                    "select P.`Product Code` object_name,P.`Product ID` as object_key %s from $table   $where $wheref
	                and ($_order_field  > %s OR ($_order_field  = %s AND P.`Product ID` > %d))  order by $_order_field   , P.`Product ID`  limit 1",
                    $extra_field,
                    prepare_mysql($_order_field_value),
                    prepare_mysql($_order_field_value),
                    $object->id
                );


                if ($result = $db->query($sql)) {
                    if ($row = $result->fetch()) {
                        $next_key   = $row['object_key'];
                        $next_title = _("Service").' '.$row['object_name'].' ('.$row['object_key'].')';
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
    } else {
        $_section = 'services';
    }


    switch ($data['parent']) {
        case 'store':


            $up_button = array(
                'icon'      => 'arrow-up',
                'title'     => _("Store").' ('.$data['store']->get(
                        'Code'
                    ).')',
                'reference' => 'services/'.$data['store']->id
            );

            if ($prev_key) {
                $left_buttons[] = array(
                    'icon'      => 'arrow-left',
                    'title'     => $prev_title,
                    'reference' => 'services/'.$data['parent_key'].'/'.$prev_key
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
                    'reference' => 'services/'.$data['parent_key'].'/'.$next_key
                );
            } else {
                $left_buttons[] = array(
                    'icon'  => 'arrow-right disabled',
                    'title' => '',
                    'url'   => ''
                );
            }

            break;
        case 'part':


            $up_button = array(
                'icon'      => 'arrow-up',
                'title'     => _("Part").' ('.$data['_parent']->get(
                        'SKU'
                    ).')',
                'reference' => 'part/'.$data['_parent']->id
            );

            if ($prev_key) {
                $left_buttons[] = array(
                    'icon'      => 'arrow-left',
                    'title'     => $prev_title,
                    'reference' => 'part/'.$data['_parent']->id.'/service/'.$prev_key
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
                    'reference' => 'part/'.$data['_parent']->id.'/service/'.$next_key
                );
            } else {
                $left_buttons[] = array(
                    'icon'  => 'arrow-right disabled',
                    'title' => '',
                    'url'   => ''
                );
            }

            break;
        case 'order':
            $order     = new Order($data['parent_key']);
            $up_button = array(
                'icon'      => 'arrow-up',
                'title'     => _("Order").' ('.$order->get(
                        'Order Public ID'
                    ).')',
                'reference' => 'orders/'.$order->get(
                        'Order Store Key'
                    ).'/'.$order->id
            );

            if ($prev_key) {
                $left_buttons[] = array(
                    'icon'      => 'arrow-left',
                    'title'     => $prev_title,
                    'reference' => 'order/'.$data['parent_key'].'/item/'.$prev_extra_field_value
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
                    'reference' => 'order/'.$data['parent_key'].'/item/'.$next_extra_field_value
                );
            } else {
                $left_buttons[] = array(
                    'icon'  => 'arrow-right disabled',
                    'title' => '',
                    'url'   => ''
                );
            }

            break;

        case 'category':


            $up_button = array(
                'icon'      => 'arrow-up',
                'title'     => $data['_parent']->get('Code'),
                'reference' => 'services/'.$data['_parent']->get(
                        'Category Store Key'
                    ).'/category/'.$data['_parent']->id
            );

            if ($prev_key) {
                $left_buttons[] = array(
                    'icon'      => 'arrow-left',
                    'title'     => $prev_title,
                    'reference' => 'services/'.$data['_parent']->get(
                            'Category Store Key'
                        ).'/category/'.$data['_parent']->get(
                            'Category Position'
                        ).'/service/'.$prev_key
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
                    'reference' => 'services/'.$data['_parent']->get(
                            'Category Store Key'
                        ).'/category/'.$data['_parent']->get(
                            'Category Position'
                        ).'/service/'.$next_key
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

    $right_buttons = array();
    //$right_buttons[]=array('icon'=>'edit','title'=>_('Edit store'),'reference'=>'store/'.$data['store']->id.'/edit');
    $sections = get_sections($_section, $data['store']->id);


    //$_section='services';
    if (isset($sections[$_section])) {
        $sections[$_section]['selected'] = true;
    }


    $title = '<i class="fa fa-cube" aria-hidden="true" title="'._('Service').'"></i> <span class="id Service_Code">'.$object->get('Code').'</span>';

    $service_parts = $object->get_parts('objects');

    if (count($service_parts) == 1) {
        $part  = array_values($service_parts)[0];
        $title .= ' <small class="padding_left_10"> <i class="fa fa-long-arrow-left padding_left_10"></i> <i class="fa fa-stop button" title="'._('Part').'" onCLick="change_view(\'/part/'.$part->id
            .'\')" ></i> <span class="Part_Reference button"  onCLick="change_view(\'part/'.$part->id.'\')">'.$part->get('Reference').'</small>';
    } elseif (count($service_parts) > 1) {
        $title .= '<span class="small discreet padding_left_20">'._(
                'Multiple parts'
            ).'</span>';
    }


    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,

        'left_buttons'  => $left_buttons,
        'right_buttons' => $right_buttons,
        'title'         => $title,
        'search'        => array(
            'show'        => true,
            'placeholder' => _('Search services').' '.$object->get('Product Store Code')
        )

    );
    $smarty->assign('_content', $_content);

    return array(
        $_content['search'],
        $smarty->fetch('top_menu.tpl'),
        $smarty->fetch('au_header.tpl')
    );
}


function get_new_service_navigation($data, $smarty, $user, $db, $account)
{
    $left_buttons = array();

    $block_view = $data['section'];


    switch ($data['parent']) {
        case 'store':
            $title                            = sprintf(
                _('New service for %s'),
                '<span class="id">'.$data['store']->get('Code').'</span>'
            );
            $sections                         = get_sections(
                'services',
                $data['parent_key']
            );
            $left_buttons[]                   = array(
                'icon'      => 'arrow-up',
                'title'     => _(
                        'Store'
                    ).': '.$data['store']->get(
                        'Code'
                    ),
                'reference' => 'services/'.$data['store']->id,
                'parent'    => ''
            );
            $sections['services']['selected'] = true;
            break;
        default:
            exit('error in services.nav.php');
            break;
    }


    $right_buttons = array();


    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => $title,
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search services')
        )

    );
    $smarty->assign('_content', $_content);

    return array(
        $_content['search'],
        $smarty->fetch('top_menu.tpl'),
        $smarty->fetch('au_header.tpl')
    );
}


function get_products_new_main_category_navigation($data, $smarty, $user, $db, $account)
{
    $block_view     = $data['section'];
    $sections       = get_sections('products', $data['parent_key']);
    $left_buttons   = array();
    $left_buttons[] = array(
        'icon'      => 'arrow-up',
        'title'     => _('Categories'),
        'reference' => 'products/'.$data['parent_key'].'/categories',
        'parent'    => ''
    );

    $right_buttons = array();

    if (isset($sections[$data['section']])) {
        $sections[$data['section']]['selected'] = true;
    }


    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => _("New main product's category"),
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search products')
        )

    );
    $smarty->assign('_content', $_content);

    return array(
        $_content['search'],
        $smarty->fetch('top_menu.tpl'),
        $smarty->fetch('au_header.tpl')
    );
}

function get_order_navigation($data, $smarty, $user, $db, $account)
{
    $object        = $data['_object'];
    $left_buttons  = array();
    $right_buttons = array();

    if ($data['parent']) {
        switch ($data['parent']) {
            case 'product':
                $tab      = 'product.orders';
                $_section = 'products';
                break;
            case 'campaign':
                $tab      = 'campaign.orders';
                $_section = 'marketing';
                break;
            case 'deal':
                $tab      = 'deal.orders';
                $_section = 'marketing';
                break;
            case 'charge':
                $tab      = 'charge.orders';
                $_section = 'products';
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
                        "select O.`Order Public ID` object_name,O.`Order Key` as object_key from $table   $where $wheref
	                and ($_order_field < %s OR ($_order_field = %s AND O.`Order Key` < %d))  order by $_order_field desc , O.`Order Key` desc limit 1",

                        prepare_mysql($_order_field_value),
                        prepare_mysql($_order_field_value),
                        $object->id
                    );


                    if ($result = $db->query($sql)) {
                        if ($row = $result->fetch()) {
                            $prev_key   = $row['object_key'];
                            $prev_title = _("Order").' '.$row['object_name'].' ('.$row['object_key'].')';
                        }
                    } else {
                        print_r($error_info = $db->errorInfo());
                        print $sql;
                        exit;
                    }


                    $sql = sprintf(
                        "select O.`Order Public ID` object_name,O.`Order Key` as object_key from $table   $where $wheref
	                and ($_order_field  > %s OR ($_order_field  = %s AND O.`Order Key` > %d))  order by $_order_field   , O.`Order Key`  limit 1",
                        prepare_mysql($_order_field_value),
                        prepare_mysql($_order_field_value),
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


        if ($data['parent'] == 'product') {
            $up_button = array(
                'icon'      => 'arrow-up',
                'title'     => _("Product").' '.$data['_parent']->get('Code'),
                'reference' => 'product/'.$data['_parent']->id
            );

            if ($prev_key) {
                $left_buttons[] = array(
                    'icon'      => 'arrow-left',
                    'title'     => $prev_title,
                    'reference' => 'product/'.$data['_parent']->id.'/order/'.$prev_key
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
                    'reference' => 'product/'.$data['_parent']->id.'/order/'.$next_key
                );
            } else {
                $left_buttons[] = array(
                    'icon'  => 'arrow-right disabled',
                    'title' => '',
                    'url'   => ''
                );
            }
            $sections           = get_sections(
                'products',
                $data['_parent']->get('Product Store Key')
            );
            $search_placeholder = _('Search products');
        } elseif ($data['parent'] == 'campaign') {
            $up_button = array(
                'icon'      => 'arrow-up',
                'title'     => _("Campaign").' '.$data['_parent']->get('Code'),
                'reference' => 'offers/'.$data['_parent']->get('Store Key').'/'.strtolower($data['_parent']->get('Code'))
            );

            if ($prev_key) {
                $left_buttons[] = array(
                    'icon'      => 'arrow-left',
                    'title'     => $prev_title,
                    'reference' => 'offers/'.$data['_parent']->get('Store Key').'/'.strtolower($data['_parent']->get('Code')).'/order/'.$prev_key

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
                    'reference' => 'offers/'.$data['_parent']->get('Store Key').'/'.strtolower($data['_parent']->get('Code')).'/order/'.$next_key

                );
            } else {
                $left_buttons[] = array(
                    'icon'  => 'arrow-right disabled',
                    'title' => '',
                    'url'   => ''
                );
            }
            $sections           = get_sections(
                'products',
                $data['_parent']->get('Product Store Key')
            );
            $search_placeholder = _('Search marketing');
        } elseif ($data['parent'] == 'deal') {
            $up_button = array(
                'icon'      => 'arrow-up',
                'title'     => _("Offer").' '.$data['_parent']->get('Code'),
                'reference' => 'deals/'.$data['_parent']->get('Store Key').'/'.$data['_parent']->id
            );

            if ($prev_key) {
                $left_buttons[] = array(
                    'icon'      => 'arrow-left',
                    'title'     => $prev_title,
                    'reference' => 'deals/'.$data['_parent']->get('Store Key').'/'.$data['_parent']->id.'/order/'.$prev_key
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
                    'reference' => 'deals/'.$data['_parent']->get('Store Key').'/'.$data['_parent']->id.'/order/'.$next_key
                );
            } else {
                $left_buttons[] = array(
                    'icon'  => 'arrow-right disabled',
                    'title' => '',
                    'url'   => ''
                );
            }
            $sections           = get_sections(
                'products',
                $data['_parent']->get('Product Store Key')
            );
            $search_placeholder = _('Search marketing');
        } elseif ($data['parent'] == 'charge') {
            $up_button = array(
                'icon'      => 'arrow-up',
                'title'     => _("Charge").' '.$data['_parent']->get('Code'),
                'reference' => 'store/'.$data['_parent']->get('Store Key').'/charge/'.$data['_parent']->id
            );


            if ($prev_key) {
                $left_buttons[] = array(
                    'icon'      => 'arrow-left',
                    'title'     => $prev_title,
                    'reference' => 'charge/'.$data['_parent']->id.'/order/'.$prev_key
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
                    'reference' => 'charge/'.$data['_parent']->id.'/order/'.$next_key
                );
            } else {
                $left_buttons[] = array(
                    'icon'  => 'arrow-right disabled',
                    'title' => '',
                    'url'   => ''
                );
            }
            $sections           = get_sections(
                'products',
                $data['_parent']->get('Product Store Key')
            );
            $search_placeholder = _('Search marketing');
        }
    } else {
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


    return array(
        $_content['search'],
        $smarty->fetch('top_menu.tpl'),
        $smarty->fetch('au_header.tpl')
    );
}


function get_charge_navigation($data, $smarty, $user, $db, $account)
{
    $object        = $data['_object'];
    $left_buttons  = array();
    $right_buttons = array();


    if ($data['parent']) {
        switch ($data['parent']) {
            case 'store':
                $tab      = 'store.charges';
                $_section = 'products';
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
                        "select C.`Charge Name` object_name,C.`Charge Key` as object_key from $table   $where $wheref
	                and ($_order_field < %s OR ($_order_field = %s AND C.`Charge Key` < %d))  order by $_order_field desc , C.`Charge Key`desc limit 1",

                        prepare_mysql($_order_field_value),
                        prepare_mysql($_order_field_value),
                        $object->id
                    );


                    if ($result = $db->query($sql)) {
                        if ($row = $result->fetch()) {
                            $prev_key   = $row['object_key'];
                            $prev_title = _("Order").' '.$row['object_name'].' ('.$row['object_key'].')';
                        }
                    } else {
                        print_r($error_info = $db->errorInfo());
                        print $sql;
                        exit;
                    }


                    $sql = sprintf(
                        "select  C.`Charge Name` object_name,C.`Charge Key` as object_key from $table   $where $wheref
	                and ($_order_field  > %s OR ($_order_field  = %s AND C.`Charge Key` > %d))  order by $_order_field   , C.`Charge Key` limit 1",
                        prepare_mysql($_order_field_value),
                        prepare_mysql($_order_field_value),
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


        if ($data['parent'] == 'store') {
            $up_button = array(
                'icon'      => 'arrow-up',
                'title'     => _("Store").' '.$data['_parent']->get('Code'),
                'reference' => 'store/'.$data['_parent']->id
            );

            if ($prev_key) {
                $left_buttons[] = array(
                    'icon'      => 'arrow-left',
                    'title'     => $prev_title,
                    'reference' => 'store/'.$data['_parent']->id.'/charge/'.$prev_key
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
                    'reference' => 'store/'.$data['_parent']->id.'/charge/'.$next_key
                );
            } else {
                $left_buttons[] = array(
                    'icon'  => 'arrow-right disabled',
                    'title' => '',
                    'url'   => ''
                );
            }


            $sections = get_sections('products', $data['_parent']->id);
            $_section = 'store';


            $search_placeholder = _('Search products');
        }
    } else {
    }


    if (isset($sections[$_section])) {
        $sections[$_section]['selected'] = true;
    }


    $title = _('Charge').' <span class="id Charge_Name">'.$object->get('Charge Name').'</span>';


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


function get_shipping_zone_new_navigation($data, $smarty, $user, $db, $account)
{
    $left_buttons = array();


    switch ($data['parent']) {
        case 'store':
            $title                            = sprintf(_('New shipping zone for %s'), '<span class="id">'.$data['store']->get('Code').'</span>');
            $sections                         = get_sections('products', $data['parent_key']);
            $left_buttons[]                   = array(
                'icon'      => 'arrow-up',
                'title'     => _('Store').': '.$data['store']->get('Code'),
                'reference' => 'products/'.$data['store']->id,
                'parent'    => ''
            );
            $sections['products']['selected'] = true;
            break;
        default:
            exit('error in products.nav.php');
            break;
    }


    $right_buttons = array();


    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => $title,
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search products')
        )

    );
    $smarty->assign('_content', $_content);

    return array(
        $_content['search'],
        $smarty->fetch('top_menu.tpl'),
        $smarty->fetch('au_header.tpl')
    );
}


function get_charge_new_navigation($data, $smarty, $user, $db, $account)
{
    $left_buttons = array();


    switch ($data['parent']) {
        case 'store':
            $title                            = sprintf(_('New charge for %s'), '<span class="id">'.$data['store']->get('Code').'</span>');
            $sections                         = get_sections('products', $data['parent_key']);
            $left_buttons[]                   = array(
                'icon'      => 'arrow-up',
                'title'     => _('Store').': '.$data['store']->get('Code'),
                'reference' => 'products/'.$data['store']->id,
                'parent'    => ''
            );
            $sections['products']['selected'] = true;
            break;
        default:
            exit('error in products.nav.php');
            break;
    }


    $right_buttons = array();


    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => $title,
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search products')
        )

    );
    $smarty->assign('_content', $_content);

    return array(
        $_content['search'],
        $smarty->fetch('top_menu.tpl'),
        $smarty->fetch('au_header.tpl')
    );
}


function get_shipping_option_new_navigation($data, $smarty, $user, $db, $account)
{
    $left_buttons = array();


    switch ($data['parent']) {
        case 'store':
            $title                            = sprintf(_('New shipping option for %s'), '<span class="id">'.$data['store']->get('Code').'</span>');
            $sections                         = get_sections('products', $data['parent_key']);
            $left_buttons[]                   = array(
                'icon'      => 'arrow-up',
                'title'     => _('Store').': '.$data['store']->get('Code'),
                'reference' => 'products/'.$data['store']->id,
                'parent'    => ''
            );
            $sections['products']['selected'] = true;
            break;
        default:
            exit('error in products.nav.php');
            break;
    }


    $right_buttons = array();


    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => $title,
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search products')
        )

    );
    $smarty->assign('_content', $_content);

    return array(
        $_content['search'],
        $smarty->fetch('top_menu.tpl'),
        $smarty->fetch('au_header.tpl')
    );
}


function get_shipping_zone_navigation($data, $smarty, $user, $db, $account)
{
    $object        = $data['_object'];
    $left_buttons  = array();
    $right_buttons = array();


    if ($data['parent']) {
        switch ($data['parent']) {
            case 'store':
                $tab      = 'store.shipping_zones_schemas';
                $_section = 'products';
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
                        "select SZ.`Shipping Zone Schema Label` object_name,SZ.`Shipping Zone Schema Key` as object_key from $table   $where $wheref
	                and ($_order_field < %s OR ($_order_field = %s AND SZ.`Shipping Zone Schema Key` < %d))  order by $_order_field desc , SZ.`Shipping Zone Schema Key`desc limit 1",

                        prepare_mysql($_order_field_value),
                        prepare_mysql($_order_field_value),
                        $object->id
                    );


                    if ($result = $db->query($sql)) {
                        if ($row = $result->fetch()) {
                            $prev_key   = $row['object_key'];
                            $prev_title = _("Shipping zone schema").' '.$row['object_name'];
                        }
                    } else {
                        print_r($error_info = $db->errorInfo());
                        print $sql;
                        exit;
                    }


                    $sql = sprintf(
                        "select  SZ.`Shipping Zone Schema Label` object_name,SZ.`Shipping Zone Schema Key` as object_key from $table   $where $wheref
	                and ($_order_field  > %s OR ($_order_field  = %s AND SZ.`Shipping Zone Schema Key` > %d))  order by $_order_field   , SZ.`Shipping Zone Schema Key` limit 1",
                        prepare_mysql($_order_field_value),
                        prepare_mysql($_order_field_value),
                        $object->id
                    );

                    if ($result = $db->query($sql)) {
                        if ($row = $result->fetch()) {
                            $next_key   = $row['object_key'];
                            $next_title = _("Shipping zone schema\"").' '.$row['object_name'];
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


        if ($data['parent'] == 'store') {
            $up_button = array(
                'icon'      => 'arrow-up',
                'title'     => _("Store").' '.$data['_parent']->get('Code'),
                'reference' => 'store/'.$data['_parent']->id
            );

            if ($prev_key) {
                $left_buttons[] = array(
                    'icon'      => 'arrow-left',
                    'title'     => $prev_title,
                    'reference' => 'store/'.$data['_parent']->id.'/charge/'.$prev_key
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
                    'reference' => 'store/'.$data['_parent']->id.'/charge/'.$next_key
                );
            } else {
                $left_buttons[] = array(
                    'icon'  => 'arrow-right disabled',
                    'title' => '',
                    'url'   => ''
                );
            }


            $sections = get_sections('products', $data['_parent']->id);
            $_section = 'store';


            $search_placeholder = _('Search products');
        }
    } else {
    }


    if (isset($sections[$_section])) {
        $sections[$_section]['selected'] = true;
    }


    $title = ' <span class="id Shipping_Zone_Schema_Label">'.$object->get('Label').'</span>';


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


function get_shipping_zone_schema_navigation($data, $smarty, $user, $db)
{
    $object = $data['_object'];

    $left_buttons = array();


    switch ($data['parent']) {
        case 'store':
            $tab      = 'store.shipping_zones_schemas';
            $_section = 'products';
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
                    "select SZ.`Shipping Zone Schema Label` object_name,SZ.`Shipping Zone Schema Key` as object_key from $table   $where $wheref
	                and ($_order_field < %s OR ($_order_field = %s AND SZ.`Shipping Zone Schema Key` < %d))  order by $_order_field desc , SZ.`Shipping Zone Schema Key`desc limit 1",

                    prepare_mysql($_order_field_value),
                    prepare_mysql($_order_field_value),
                    $object->id
                );


                if ($result = $db->query($sql)) {
                    if ($row = $result->fetch()) {
                        $prev_key   = $row['object_key'];
                        $prev_title = _("Shipping zone schema").' '.$row['object_name'];
                    }
                } else {
                    print_r($error_info = $db->errorInfo());
                    print $sql;
                    exit;
                }


                $sql = sprintf(
                    "select  SZ.`Shipping Zone Schema Label` object_name,SZ.`Shipping Zone Schema Key` as object_key from $table   $where $wheref
	                and ($_order_field  > %s OR ($_order_field  = %s AND SZ.`Shipping Zone Schema Key` > %d))  order by $_order_field   , SZ.`Shipping Zone Schema Key` limit 1",
                    prepare_mysql($_order_field_value),
                    prepare_mysql($_order_field_value),
                    $object->id
                );

                if ($result = $db->query($sql)) {
                    if ($row = $result->fetch()) {
                        $next_key   = $row['object_key'];
                        $next_title = _("Shipping zone schema").' '.$row['object_name'];
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


    if ($data['parent'] == 'store') {
        $up_button = array(
            'icon'      => 'arrow-up',
            'title'     => _("Store").' '.$data['_parent']->get('Code'),
            'reference' => 'store/'.$data['_parent']->id.'/settings'
        );

        if ($prev_key) {
            $left_buttons[] = array(
                'icon'      => 'arrow-left',
                'title'     => $prev_title,
                'reference' => 'store/'.$data['_parent']->id.'/shipping_zone_schema/'.$prev_key
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
                'reference' => 'store/'.$data['_parent']->id.'/shipping_zone_schema/'.$next_key
            );
        } else {
            $left_buttons[] = array(
                'icon'  => 'arrow-right disabled',
                'title' => '',
                'url'   => ''
            );
        }


        $sections = get_sections('products', $data['_parent']->id);
        $_section = 'store';


        $search_placeholder = _('Search products');
    }


    $right_buttons = array();
    //$right_buttons[]=array('icon'=>'edit', 'title'=>_('Edit store'), 'reference'=>'store/'.$data['store']->id.'/edit');
    //$right_buttons[]=array('icon'=>'plus', 'title'=>_('New store'), 'id'=>"new_store");

    $sections = get_sections('products', $data['store']->id);
    $_section = 'settings';
    if (isset($sections[$_section])) {
        $sections[$_section]['selected'] = true;
    }


    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,

        'left_buttons'  => $left_buttons,
        'right_buttons' => $right_buttons,
        'title'         => _('Shipping schema').' <span class="id">'.$data['_object']->get('Label').'</span>',
        'search'        => array(
            'show'        => true,
            'placeholder' => _('Search products').' '.$data['store']->get('Store Code')
        )

    );
    $smarty->assign('_content', $_content);

    return array(
        $_content['search'],
        $smarty->fetch('top_menu.tpl'),
        $smarty->fetch('au_header.tpl')
    );
}


function get_settings_navigation($data, $smarty): array
{
    $left_buttons  = array();
    $right_buttons = array();
    $sections      = get_sections('products', $data['key']);

    if (isset($sections[$data['section']])) {
        $sections[$data['section']]['selected'] = true;
    }


    $_content = array(

        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => _('Settings').' <span class="id">'.$data['_object']->get('Store Code').'</span>',
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search store').' '.$data['_object']->get('Store Code')
        )

    );
    $smarty->assign('_content', $_content);

    return array(
        $_content['search'],
        $smarty->fetch('top_menu.tpl'),
        $smarty->fetch('au_header.tpl')
    );
}


function get_website_new_navigation($data, $smarty): array
{
    $sections_class = '';
    $title          = sprintf(_('New website for %s'), '<span class="id">'.$data['store']->get('Code')).'</span>';

    $left_buttons = array();


    $right_buttons = array();


    $sections = get_sections('websites_server');
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
            'placeholder' => _('Search store').' '.$data['store']->get('Store Code')
        )

    );
    $smarty->assign('_content', $_content);

    return array(
        $_content['search'],
        $smarty->fetch('top_menu.tpl'),
        $smarty->fetch('au_header.tpl')
    );
}

function get_picking_pipeline_navigation($data, $smarty): array
{
    $sections_class = '';
    if ($data['_object']->id) {
        $title = sprintf(_('Picking pipeline'), '<span class="id Picking_Pipeline_Name">'.$data['_object']->get('Name')).'</span>';
    } else {
        $title = sprintf(_('Setting up picking pipeline for %s'), '<span class="id">'.$data['store']->get('Code')).'</span>';
    }

    $left_buttons  = array();
    $right_buttons = array();


    $sections = get_sections('products', $data['store']->id);

    $sections['picking_pipeline']['selected'] = true;


    $_content = array(
        'sections_class' => $sections_class,
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => $title,
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search store').' '.$data['store']->get('Store Code')
        )

    );
    $smarty->assign('_content', $_content);

    return array(
        $_content['search'],
        $smarty->fetch('top_menu.tpl'),
        $smarty->fetch('au_header.tpl')
    );
}


function get_new_product_variant_navigation($data, $smarty, $user, $db, $account)
{
    $left_buttons = array();

    $block_view = $data['section'];


    $title                            = sprintf(
        _('New variant for %s'),
        '<span class="id">'.$data['_parent']->get('Code').'</span>'
    );
    $sections                         = get_sections(
        'products',
        $data['_parent']->get('Store Key')
    );
    $left_buttons[]                   = array(
        'icon'      => 'arrow-up',
        'title'     => _(
                'Product'
            ).': '.$data['_parent']->get(
                'Code'
            ),
        'reference' => 'products/'.$data['_parent']->get('Store Key').'/'.$data['_parent']->id,
        'parent'    => ''
    );
    $sections['products']['selected'] = true;


    $right_buttons = array();


    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => $title,
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search products')
        )

    );
    $smarty->assign('_content', $_content);

    return array(
        $_content['search'],
        $smarty->fetch('top_menu.tpl'),
        $smarty->fetch('au_header.tpl')
    );
}

