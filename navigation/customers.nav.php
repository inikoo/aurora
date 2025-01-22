<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 28 August 2015 18:15:18 GMT+8 Singapore

 Copyright (c) 2015, Inikoo

 Version 3.0
*/

include_once 'utils/navigation_functions.php';

/**
 * @param $data
 * @param $smarty \Smarty
 * @param $user
 * @param $db     \PDO
 *
 * @return mixed
 * @throws \SmartyException
 */
function get_customers_navigation($data, $smarty, $user, $db) {


    require_once 'class.Store.php';


    $left_buttons = array();
    if ($user->stores > 1) {


        list($prev_key, $next_key) = get_prev_next(
            $data['store']->id, $user->stores
        );

        $sql = sprintf(
            "SELECT `Store Code` FROM `Store Dimension` WHERE `Store Key`=%d", $prev_key
        );
        if ($result = $db->query($sql)) {
            if ($row = $result->fetch()) {
                $prev_title = _('Customers').' '.$row['Store Code'];
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
                $next_title = _('Customers').' '.$row['Store Code'];
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
            'reference' => 'customers/'.$prev_key
        );
        $left_buttons[] = array(
            'icon'      => 'arrow-up',
            'title'     => _('Customers (All stores)'),
            'reference' => 'customers/all',
            'parent'    => ''
        );

        $left_buttons[] = array(
            'icon'      => 'arrow-right',
            'title'     => $next_title,
            'reference' => 'customers/'.$next_key
        );
    }


    $right_buttons = array();
    $sections      = get_sections('customers', $data['store']->id);

    if (isset($sections[$data['section']])) {
        $sections[$data['section']]['selected'] = true;
    }


    $_content = array(

        'sections_class' => '',
        'sections'       => $sections,

        'left_buttons'  => $left_buttons,
        'right_buttons' => $right_buttons,
        'title'         => _('Customers').' <span class="id">'.$data['store']->get('Store Code').'</span>',
        'search'        => array(
            'show'        => true,
            'placeholder' => _('Search customers')
        )

    );
    $smarty->assign('_content', $_content);

    return array(
        $_content['search'],
        $smarty->fetch('top_menu.tpl'),
        $smarty->fetch('au_header.tpl')
    );

}


function get_customers_list_navigation($data, $smarty, $user, $db) {


    require_once 'class.Store.php';

    include_once 'class.List.php';


    $list  = new SubjectList($data['key']);
    $store = new Store($list->get('List Parent Key'));


    $left_buttons = array();


    $tab = 'customers.lists';


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
    $_order_field_value = $list->get($order);


    $prev_title = '';
    $next_title = '';
    $prev_key   = 0;
    $next_key   = 0;
    $sql        = trim($sql_totals." $wheref");


    if ($result2 = $db->query($sql)) {
        if ($row2 = $result2->fetch() and $row2['num'] > 1) {


            $sql = sprintf(
                "select `List Name` object_name,`List Key` as object_key from $table   $where $wheref
	                and ($_order_field < %s OR ($_order_field = %s AND `List Key` < %d))  order by $_order_field desc , `List Key` desc limit 1",

                prepare_mysql($_order_field_value), prepare_mysql($_order_field_value), $list->id
            );

            if ($result = $db->query($sql)) {
                if ($row = $result->fetch()) {
                    $prev_key   = $row['object_key'];
                    $prev_title = _("List").' '.$row['object_name'].' ('.$row['object_key'].')';

                }
            } else {
                print_r($error_info = $db->errorInfo());
                exit;
            }


            $sql = sprintf(
                "select `List Name` object_name,`List Key` as object_key from $table   $where $wheref
	                and ($_order_field  > %s OR ($_order_field  = %s AND `List Key` > %d))  order by $_order_field   , `List Key`  limit 1", prepare_mysql($_order_field_value), prepare_mysql($_order_field_value), $list->id
            );
            if ($result = $db->query($sql)) {
                if ($row = $result->fetch()) {
                    $next_key   = $row['object_key'];
                    $next_title = _("List").' '.$row['object_name'].' ('.$row['object_key'].')';

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


    $up_button = array(
        'icon'      => 'arrow-up',
        'title'     => _(
                "Customer's lists"
            ).' '.$store->data['Store Code'],
        'reference' => 'customers/'.$store->id.'/lists'
    );

    if ($prev_key) {
        $left_buttons[] = array(
            'icon'      => 'arrow-left',
            'title'     => $prev_title,
            'reference' => 'customers/list/'.$prev_key
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
            'reference' => 'customers/list/'.$next_key
        );

    } else {
        $left_buttons[] = array(
            'icon'  => 'arrow-right disabled',
            'title' => '',
            'url'   => ''
        );

    }


    $right_buttons = array();
    $sections      = get_sections('customers', $store->id);

    $sections['lists']['selected'] = true;


    $_content = array(

        'sections_class' => '',
        'sections'       => $sections,

        'left_buttons'  => $left_buttons,
        'right_buttons' => $right_buttons,
        'title'         => _("Customer's List").' <span class="id List_Name">'.$list->get('List Name').'</span>',
        'search'        => array(
            'show'        => true,
            'placeholder' => _('Search customers')
        )

    );
    $smarty->assign('_content', $_content);

    return array(
        $_content['search'],
        $smarty->fetch('top_menu.tpl'),
        $smarty->fetch('au_header.tpl')
    );

}


function get_customers_categories_navigation($data, $smarty, $user, $db) {


    require_once 'class.Store.php';

    switch ($data['parent']) {
        case 'store':
            $store = new Store($data['parent_key']);
            break;
        default:

            break;
    }

    //  $block_view = $data['section'];


    $left_buttons = array();
    if ($user->stores > 1) {


        list($prev_key, $next_key) = get_prev_next($store->id, $user->stores);

        $sql = sprintf(
            "SELECT `Store Code` FROM `Store Dimension` WHERE `Store Key`=%d", $prev_key
        );
        if ($result = $db->query($sql)) {
            if ($row = $result->fetch()) {
                $prev_title = _("Customer's Categories").' '.$row['Store Code'];
            } else {
                $prev_title = '';
            }
        }


        $sql = sprintf(
            "SELECT `Store Code` FROM `Store Dimension` WHERE `Store Key`=%d", $next_key
        );
        if ($result = $db->query($sql)) {
            if ($row = $result->fetch()) {
                $next_title = _("Customer's Categories").' '.$row['Store Code'];
            } else {
                $next_title = '';
            }
        }


        $left_buttons[] = array(
            'icon'      => 'arrow-left',
            'title'     => $prev_title,
            'reference' => 'customers/categories/'.$prev_key
        );
        //$left_buttons[]=array('icon'=>'arrow-up','title'=>_('Customers').' '.$store->data['Store Code'],'reference'=>'customers/'.$store->id);

        $left_buttons[] = array(
            'icon'      => 'arrow-right',
            'title'     => $next_title,
            'reference' => 'customers/categories/'.$next_key
        );
    }


    $right_buttons = array();

    $sections = get_sections('customers', $store->id);
    if (isset($sections[$data['section']])) {
        $sections[$data['section']]['selected'] = true;
    }

    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => _("Customer's Categories").' <span class="id">'.$store->get('Store Code').'</span>',
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search customers')
        )

    );
    $smarty->assign('_content', $_content);

    return array(
        $_content['search'],
        $smarty->fetch('top_menu.tpl'),
        $smarty->fetch('au_header.tpl')
    );

}


function get_customers_category_navigation($data, $smarty, $user, $db) {


    require_once 'class.Category.php';
    require_once 'class.Store.php';


    $store = $data['store'];

    $left_buttons  = array();
    $right_buttons = array();

    switch ($data['_object']->get('Category Branch Type')) {
        case 'Node':


            $left_buttons[] = array(
                'icon'      => 'arrow-up',
                'title'     => _("Customer's Categories").' '.$store->data['Store Code'],
                'reference' => 'customers/'.$store->id.'/categories'
            );
            break;
        case 'Head':

            $left_buttons[] = array(
                'icon'      => 'arrow-up',
                'title'     => _("Customer's Categories").' '.$data['_parent']->get('Code'),
                'reference' => 'customers/'.$store->id.'/categories/'.$data['_parent']->id
            );


            break;

        default:

            break;
    }


    $title = ' <span class="Category_Label">'.$data['_object']->get('Label').'</span>';
    $title .= '<span class="Category_Code_Container '.($data['_object']->get('Label') == $data['_object']->get('Code') ? 'hide' : '').' ">(<span class="Category_Code id">'.$data['_object']->get('Code').'</span>)</span>';


    //$right_buttons[]=array('icon'=>'edit', 'title'=>_('Edit'), 'url'=>"edit_customer_categories.php?store_id=".$store->id);

    $sections                           = get_sections('customers', $store->id);
    $sections['categories']['selected'] = true;

    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => $title,
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search customers')
        )

    );
    $smarty->assign('_content', $_content);

    return array(
        $_content['search'],
        $smarty->fetch('top_menu.tpl'),
        $smarty->fetch('au_header.tpl')
    );

}


function get_customers_lists_navigation($data, $smarty, $user, $db) {


    require_once 'class.Store.php';

    switch ($data['parent']) {
        case 'store':
            $store = new Store($data['parent_key']);
            break;
        default:

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
                $prev_title = _("Customer's Lists").' '.$row['Store Code'];
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
                $next_title = _("Customer's Lists").' '.$row['Store Code'];
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
            'reference' => 'customers/'.$prev_key.'/lists'
        );
        //$left_buttons[]=array('icon'=>'arrow-up','title'=>_('Customers').' '.$store->data['Store Code'],'reference'=>'customers/'.$store->id);

        $left_buttons[] = array(
            'icon'      => 'arrow-right',
            'title'     => $next_title,
            'reference' => 'customers/'.$next_key.'/lists'
        );
    }


    $right_buttons = array();


    $sections = get_sections('customers', $store->id);
    if (isset($sections[$data['section']])) {
        $sections[$data['section']]['selected'] = true;
    }


    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => _("Customer's Lists").' <span class="id">'.$store->get('Store Code').'</span>',
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search customers')
        )

    );
    $smarty->assign('_content', $_content);

    return array(
        $_content['search'],
        $smarty->fetch('top_menu.tpl'),
        $smarty->fetch('au_header.tpl')
    );

}


function get_customers_dashboard_navigation($data, $smarty, $user, $db) {


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
            "SELECT `Store Code` FROM `Store Dimension` WHERE `Store Key`=%d", $prev_key
        );
        if ($result = $db->query($sql)) {
            if ($row = $result->fetch()) {
                $prev_title = _("Customer's Dashboard").' '.$row['Store Code'];
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
                $next_title = _("Customer's Dashboard").' '.$row['Store Code'];
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
            'reference' => 'customers/dashboard/'.$prev_key
        );
        //$left_buttons[]=array('icon'=>'arrow-up','title'=>_('Customers').' '.$store->data['Store Code'],'reference'=>'customers/'.$store->id);

        $left_buttons[] = array(
            'icon'      => 'arrow-right',
            'title'     => $next_title,
            'reference' => 'customers/dashboard/'.$next_key
        );
    }


    $right_buttons = array();


    $sections = get_sections('customers', $store->id);
    if (isset($sections[$data['section']])) {
        $sections[$data['section']]['selected'] = true;
    }

    $_content = array(

        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => _("Customer's Dashboard").' <span class="id">'.$store->get('Store Code').'</span>',
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search customers')
        )

    );
    $smarty->assign('_content', $_content);


    return array(
        $_content['search'],
        $smarty->fetch('top_menu.tpl'),
        $smarty->fetch('au_header.tpl')
    );

}


function get_customers_insights_navigation($data, $smarty, $user, $db) {


    require_once 'class.Store.php';

    switch ($data['parent']) {
        case 'store':
            $store = new Store($data['parent_key']);
            break;
        default:

            break;
    }




    $title=_("Customer's insights").' <span class="id">'.$store->get('Store Code').'</span>';

    $block_view = $data['section'];


    $left_buttons = array();
    if ($user->stores > 1) {


        list($prev_key, $next_key) = get_prev_next($store->id, $user->stores);

        $sql = sprintf(
            "SELECT `Store Code` FROM `Store Dimension` WHERE `Store Key`=%d", $prev_key
        );
        if ($result = $db->query($sql)) {
            if ($row = $result->fetch()) {
                $prev_title = _("Customer's insights").' '.$row['Store Code'];
            } else {
                $prev_title = '';
            }
        }

        $sql = sprintf(
            "SELECT `Store Code` FROM `Store Dimension` WHERE `Store Key`=%d", $next_key
        );
        if ($result = $db->query($sql)) {
            if ($row = $result->fetch()) {
                $next_title = _("Customer's insights").' '.$row['Store Code'];
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
            'reference' => 'customers/'.$prev_key.'/insights/'
        );
        //$left_buttons[]=array('icon'=>'arrow-up','title'=>_('Customers').' '.$store->data['Store Code'],'reference'=>'customers/'.$store->id);

        $left_buttons[] = array(
            'icon'      => 'arrow-right',
            'title'     => $next_title,
            'reference' => 'customers/'.$next_key.'/insights/'
        );
    }


    $right_buttons = array();


    $sections = get_sections('customers', $store->id);
    if (isset($sections[$data['section']])) {
        $sections[$data['section']]['selected'] = true;
    }




    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => $title,
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search customers')
        )

    );
    $smarty->assign('_content', $_content);


    return array(
        $_content['search'],
        $smarty->fetch('top_menu.tpl'),
        $smarty->fetch('au_header.tpl')
    );

}


function get_customers_server_navigation($data, $smarty): array {


    require_once 'class.Store.php';

    $branch = array(
        array(
            'label'     => '',
            'icon'      => 'home',
            'reference' => ''
        )
    );


    $left_buttons = array();

    if ($data['section'] == 'customers') {
        $title = _('Customers');
    } elseif ($data['section'] == 'insights') {
        $title = _('Customers insights');
    } else {
        $title = _('Pending orders');
    }

    $title.=" <small class='padding_left_10'>"._('All stores')."</small>";

    $right_buttons = array();
    $sections      = get_sections('customers_server');
    if (isset($sections[$data['section']])) {
        $sections[$data['section']]['selected'] = true;
    }

    $_content = array(
        'branch'         => $branch,
        'sections_class' => '',
        'sections'       => $sections,

        'left_buttons'  => $left_buttons,
        'right_buttons' => $right_buttons,
        'title'         => $title,
        'search'        => array(
            'show'        => true,
            'placeholder' => _(
                'Search customers all stores'
            )
        )

    );
    $smarty->assign('content', $_content);
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

    if ($data['parent']) {

        switch ($data['parent']) {
            case 'store':
                $tab      = 'customers';
                $_section = 'customers';
                break;
            case 'category':
                $tab      = 'customer_category.customers';
                $_section = 'categories';
                break;
            case 'list':
                $tab      = 'customers.list';
                $_section = 'lists';
                break;
            case 'campaign':
                $tab      = 'campaign.customers';
                $_section = 'marketing';
                break;
            case 'deal':
                $tab      = 'deal.customers';
                $_section = 'marketing';
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


        if ($data['parent'] == 'list') {

            include_once 'class.List.php';
            $list = new SubjectList($data['parent_key']);

            $up_button = array(
                'icon'      => 'arrow-up',
                'title'     => _("List").' '.$list->data['List Name'],
                'reference' => 'customers/list/'.$list->id
            );

            if ($prev_key) {
                $left_buttons[] = array(
                    'icon'      => 'arrow-left',
                    'title'     => $prev_title,
                    'reference' => 'customers/list/'.$data['parent_key'].'/'.$prev_key
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
                    'reference' => 'customers/list/'.$data['parent_key'].'/'.$next_key
                );

            } else {
                $left_buttons[] = array(
                    'icon'  => 'arrow-right disabled',
                    'title' => '',
                    'url'   => ''
                );

            }


        } elseif ($data['parent'] == 'category') {

            $up_button = array(
                'icon'      => 'arrow-up',
                'title'     => _("Category").' '.$data['_parent']->data['Category Code'],
                'reference' => 'customers/'.$store->id.'/category/'.$data['_parent']->get('Category Parent Key').'/'.$data['_parent']->id
            );
            if ($prev_key) {
                $left_buttons[] = array(
                    'icon'      => 'arrow-left',
                    'title'     => $prev_title,
                    'reference' => 'customers/'.$store->id.'/category/'.$data['_parent']->get('Category Parent Key').'/'.$data['_parent']->id.'/customer/'.$prev_key
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
                    'reference' => 'customers/'.$store->id.'/category/'.$data['_parent']->get('Category Parent Key').'/'.$data['_parent']->id.'/customer/'.$next_key
                );

            } else {
                $left_buttons[] = array(
                    'icon'  => 'arrow-right disabled',
                    'title' => '',
                    'url'   => ''
                );

            }


        } elseif ($data['parent'] == 'campaign') {


            $up_button = array(
                'icon'      => 'arrow-up',
                'title'     => _("Campaign").' '.$data['_parent']->get('Name'),
                'reference' => 'campaigns/'.$store->id.'/'.$data['_parent']->id
            );

            if ($prev_key) {
                $left_buttons[] = array(
                    'icon'      => 'arrow-left',
                    'title'     => $prev_title,
                    'reference' => 'campaign/'.$data['parent_key'].'/customer/'.$prev_key
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
                    'reference' => 'campaign/'.$data['parent_key'].'/customer/'.$next_key
                );

            } else {
                $left_buttons[] = array(
                    'icon'  => 'arrow-right disabled',
                    'title' => '',
                    'url'   => ''
                );

            }
            $sections = get_sections('products', $store->id);

            $placeholder = _('Search marketing');


        } elseif ($data['parent'] == 'deal') {


            $up_button = array(
                'icon'      => 'arrow-up',
                'title'     => _("Deal").' '.$data['_parent']->get('Name'),
                'reference' => 'campaigns/'.$store->id.'/'.$data['_parent']->get('Deal Campaign Key').'/deal/'.$data['_parent']->id
            );

            if ($prev_key) {
                $left_buttons[] = array(
                    'icon'      => 'arrow-left',
                    'title'     => $prev_title,
                    'reference' => 'deal/'.$data['parent_key'].'/customer/'.$prev_key
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
                    'reference' => 'deal/'.$data['parent_key'].'/customer/'.$next_key
                );

            } else {
                $left_buttons[] = array(
                    'icon'  => 'arrow-right disabled',
                    'title' => '',
                    'url'   => ''
                );

            }
            $sections = get_sections('products', $store->id);

            $placeholder = _('Search marketing');


        } else {

            $up_button = array(
                'icon'      => 'arrow-up',
                'title'     => _("Customers").' '.$store->data['Store Code'],
                'reference' => 'customers/'.$store->id
            );


            if ($prev_key) {
                $left_buttons[] = array(
                    'icon'      => 'arrow-left',
                    'title'     => $prev_title,
                    'reference' => 'customers/'.$data['parent_key'].'/'.$prev_key
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
                    'reference' => 'customers/'.$data['parent_key'].'/'.$next_key
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
        $_section = 'customers';

    }


    if (!($store->get('Store Type') == 'External' or $store->get('Store Type') == 'Dropshipping')) {

        if($store->get('Store Type') != 'Fulfilment' ) {
            $right_buttons[] = array(
                'icon' => 'shopping-cart',
                'title' => _('New order'),
                'id' => 'take_order'
            );
        }
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


    //  {if $customer->get_image_src()} <img id="avatar" src="{$customer->get_image_src()}" style="cursor:pointer;border:1px solid #eee;height:45px;max-width:100px"> {else} <img id="avatar" src="/art/avatar.jpg" style="cursor:pointer;"> {/if} {if $customer->get('Customer Level Type')=='VIP'}<img src="/art/icons/shield.png" style="position:absolute;xtop:-36px;left:40px">{/if} {if $customer->get('Customer Level Type')=='Partner'}<img src="/art/icons/group.png" style="position:absolute;xtop:-36px;left:40px">{/if}
    // $avatar = '<div class="square_button"></div>';
    // $avatar = '<div class="square_button left"><img id="avatar" style="height:100%" src="/art/avatar.jpg" style="cursor:pointer;"> </div> ';
    $avatar = '';


    $title = '<span class="Customer_Level_Type_Icon">'.$customer->get('Level Type Icon').'</span>';
    $title .= '<span class="id"><span class="Customer_Name_Truncated Name_Truncated">'.(strlen($customer->get('Customer Name')) > 50 ? substrwords($customer->get('Customer Name'), 55) : $customer->get('Customer Name')).'</span> ('.$customer->get_formatted_id()
        .')</span>';
    if ($customer->get('Customer Type by Activity') == 'ToApprove') {
        $title .= ' <span class="error padding_left_10"><i class="far fa-exclamation-circle"></i> '._('To be approved').'</span>';
    } elseif ($customer->get('Customer Type by Activity') == 'Rejected') {
        $title .= ' <span class="error padding_left_10"><i class="far fa-times"></i> '._('Rejected').'</span>';
    }


    $title .= '<span class="Customer_Fulfilment_Icon">'.$customer->get('Fulfilment Icon').'</span>';


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


    return array(
        $_content['search'],
        $smarty->fetch('top_menu.tpl'),
        $smarty->fetch('au_header.tpl')
    );

}

function get_deleted_customer_navigation($data, $smarty, $user, $db) {


    $left_buttons  = array();
    $right_buttons = array();


    $sections = get_sections('customers', $data['parent_key']);

    $_section = 'customers';
    if (isset($sections[$_section])) {
        $sections[$_section]['selected'] = true;
    }

    $up_button = array(
        'icon'      => 'arrow-up',
        'title'     => _("Customers"),
        'reference' => 'customers/'.$data['parent_key']
    );


    $left_buttons[] = $up_button;


    $title = _('Deleted Customer').' <span class="id">'.$data['_object']->get('Deleted Name').'</span>';


    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => $title,
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search customers')
        )

    );
    $smarty->assign('_content', $_content);


    return array(
        $_content['search'],
        $smarty->fetch('top_menu.tpl'),
        $smarty->fetch('au_header.tpl')
    );

}

function get_new_customer_navigation($data, $smarty, $user, $db) {


    $left_buttons  = array();
    $right_buttons = array();


    $sections = get_sections('customers', $data['parent_key']);

    $_section = 'customers';
    if (isset($sections[$_section])) {
        $sections[$_section]['selected'] = true;
    }

    $up_button = array(
        'icon'      => 'arrow-up',
        'title'     => _("Customers"),
        'reference' => 'customers/'.$data['parent_key']
    );


    $left_buttons[] = $up_button;


    $title = _('New Customer').' ('._('Store').' <span class="id">'.$data['store']->get('Code').'</span>)';


    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => $title,
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search customers')
        )

    );
    $smarty->assign('_content', $_content);


    return array(
        $_content['search'],
        $smarty->fetch('top_menu.tpl'),
        $smarty->fetch('au_header.tpl')
    );

}


function get_new_list_navigation($data, $smarty, $user, $db) {


    $left_buttons  = array();
    $right_buttons = array();


    $sections = get_sections('customers', $data['parent_key']);

    $_section = 'lists';
    if (isset($sections[$_section])) {
        $sections[$_section]['selected'] = true;
    }

    $up_button = array(
        'icon'      => 'arrow-up',
        'title'     => _("Customers lists"),
        'reference' => 'customers/'.$data['parent_key'].'/lists'
    );


    $left_buttons[] = $up_button;


    $title = _('New Customers list').' ('._('Store').' <span class="id">'.$data['store']->get('Code').'</span>)';


    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => $title,
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search customers')
        )

    );
    $smarty->assign('_content', $_content);


    return array(
        $_content['search'],
        $smarty->fetch('top_menu.tpl'),
        $smarty->fetch('au_header.tpl')
    );

}


function get_customers_new_poll_query_navigation($data, $smarty, $user, $db) {


    $left_buttons  = array();
    $right_buttons = array();


    $store = get_object('Store', $data['parent_key']);

    $sections = get_sections('customers', $data['parent_key']);

    $_section = 'insights';
    if (isset($sections[$_section])) {
        $sections[$_section]['selected'] = true;
    }


    $up_button = array(
        'icon'      => 'arrow-up',
        'title'     => sprintf(_("Customer's insights %s"), $store->get('Code')),
        'reference' => 'customers/'.$store->id.'/insights'
    );


    $left_buttons[] = $up_button;


    $title = _('New poll query').' ('._('Store').' <span class="id">'.$data['store']->get('Code').'</span>)';


    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => $title,
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search customers')
        )

    );
    $smarty->assign('_content', $_content);


    return array(
        $_content['search'],
        $smarty->fetch('top_menu.tpl'),
        $smarty->fetch('au_header.tpl')
    );

}

function get_customers_poll_query_navigation($data, $smarty, $user, $db) {


    $poll_query = $data['_object'];

    $store = $data['store'];

    if (!$poll_query->id) {
        return;
    }


    $left_buttons  = array();
    $right_buttons = array();

    if ($data['parent']) {

        switch ($data['parent']) {
            case 'store':
                $tab      = 'customers_poll.queries';
                $_section = 'customers';
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
        $_order_field_value = $poll_query->get($order);


        $prev_title = '';
        $next_title = '';
        $prev_key   = 0;
        $next_key   = 0;
        $sql        = trim($sql_totals." $wheref");

        if ($result2 = $db->query($sql)) {
            if ($row2 = $result2->fetch() and $row2['num'] > 1) {


                $sql = sprintf(
                    "select `Customer Poll Query Name` object_name,`Customer Poll Query Key` as object_key from $table   $where $wheref
	                and ($_order_field < %s OR ($_order_field = %s AND `Customer Poll Query Key` < %d))  order by $_order_field desc , `Customer Poll Query Key` desc limit 1",

                    prepare_mysql($_order_field_value), prepare_mysql($_order_field_value), $poll_query->id
                );


                if ($result = $db->query($sql)) {
                    if ($row = $result->fetch()) {
                        $prev_key   = $row['object_key'];
                        $prev_title = _("Poll query").' '.$row['object_name'];

                    }
                } else {
                    print_r($error_info = $db->errorInfo());
                    exit;
                }


                $sql = sprintf(
                    "select `Customer Poll Query Name` object_name,`Customer Poll Query Key` as object_key from $table   $where $wheref
	                and ($_order_field  > %s OR ($_order_field  = %s AND `Customer Poll Query Key` > %d))  order by $_order_field   , `Customer Poll Query Key`  limit 1", prepare_mysql($_order_field_value), prepare_mysql($_order_field_value), $poll_query->id
                );

                if ($result = $db->query($sql)) {
                    if ($row = $result->fetch()) {
                        $next_key   = $row['object_key'];
                        $next_title = _("Poll query").' '.$row['object_name'];

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


        $placeholder = _('Search customers');
        $sections    = get_sections('customers', $poll_query->get('Customer Poll Query Store Key'));


        $up_button = array(
            'icon'      => 'arrow-up',
            'title'     => sprintf(_("Customer's insights %s"), $store->get('Code')),
            'reference' => 'customers/'.$store->id.'/insights'
        );

        if ($prev_key) {
            $left_buttons[] = array(
                'icon'      => 'arrow-left',
                'title'     => $prev_title,
                'reference' => 'customers/'.$store->id.'/poll_query/'.$prev_key
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
                'reference' => 'customers/'.$store->id.'/poll_query/'.$next_key
            );

        } else {
            $left_buttons[] = array(
                'icon'  => 'arrow-right disabled',
                'title' => '',
                'url'   => ''
            );

        }


    } else {
        $_section = 'customers';

    }


    $sections['insights']['selected'] = true;


    $title = '<span class="id"><span class="Customer_Poll_Query_Name">'.$poll_query->get('Name').'</span></span>';


    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => $title,
        'search'         => array(
            'show'        => true,
            'placeholder' => $placeholder
        )

    );
    $smarty->assign('_content', $_content);


    return array(
        $_content['search'],
        $smarty->fetch('top_menu.tpl'),
        $smarty->fetch('au_header.tpl')
    );

}


function get_customers_new_poll_query_option_navigation($data, $smarty, $user, $db) {


    $left_buttons  = array();
    $right_buttons = array();


    $sections = get_sections('customers', $data['_parent']->get('Store Key'));

    $_section = 'insights';
    if (isset($sections[$_section])) {
        $sections[$_section]['selected'] = true;
    }

    $up_button = array(
        'icon'      => 'arrow-up',
        'title'     => sprintf(_('Poll query %s'), $data['_parent']->get('Name')),
        'reference' => 'customers/'.$data['_parent']->get('Store Key').'/poll_query/'.$data['_parent']->id
    );


    $left_buttons[] = $up_button;


    $title = _('New option').' ('._('Query').' <span class="id">'.$data['_parent']->get('Name').'</span>)';


    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => $title,
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search customers')
        )

    );
    $smarty->assign('_content', $_content);


    return array(
        $_content['search'],
        $smarty->fetch('top_menu.tpl'),
        $smarty->fetch('au_header.tpl')
    );

}


function get_customers_deleted_poll_query_option_navigation($data, $smarty, $user, $db) {


    $left_buttons  = array();
    $right_buttons = array();


    $sections = get_sections('customers', $data['_parent']->get('Store Key'));

    $_section = 'insights';
    if (isset($sections[$_section])) {
        $sections[$_section]['selected'] = true;
    }

    $up_button = array(
        'icon'      => 'arrow-up',
        'title'     => sprintf(_('Poll query %s'), $data['_parent']->get('Name')),
        'reference' => 'customers/'.$data['_parent']->get('Store Key').'/poll_query/'.$data['_parent']->id
    );


    $left_buttons[] = $up_button;


    $title = '<span class="error">'.sprintf(_('Deleted option: %s'), '<span class="id">'.$data['_parent']->get('Name').'</span>').'</span>';


    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => $title,
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search customers')
        )

    );
    $smarty->assign('_content', $_content);


    return array(
        $_content['search'],
        $smarty->fetch('top_menu.tpl'),
        $smarty->fetch('au_header.tpl')
    );

}


function get_customers_poll_query_option_navigation($data, $smarty, $user, $db) {


    $poll_option = $data['_object'];

    $store = $data['store'];

    if (!$poll_option->id) {
        return;
    }


    $left_buttons  = array();
    $right_buttons = array();


    if ($data['parent']) {

        switch ($data['parent']) {
            case 'customer_poll_query':
                $tab = 'customers_poll.query.options';

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
        $_order_field_value = $poll_option->get($order);


        $prev_title = '';
        $next_title = '';
        $prev_key   = 0;
        $next_key   = 0;
        $sql        = trim($sql_totals." $wheref");

        if ($result2 = $db->query($sql)) {
            if ($row2 = $result2->fetch() and $row2['num'] > 1) {


                $sql = sprintf(
                    "select `Customer Poll Query Option Name` object_name,`Customer Poll Query Option Key` as object_key from $table   $where $wheref
	                and ($_order_field < %s OR ($_order_field = %s AND `Customer Poll Query Option Key` < %d))  order by $_order_field desc , `Customer Poll Query Option Key` desc limit 1",

                    prepare_mysql($_order_field_value), prepare_mysql($_order_field_value), $poll_option->id
                );


                if ($result = $db->query($sql)) {
                    if ($row = $result->fetch()) {
                        $prev_key   = $row['object_key'];
                        $prev_title = _("Poll option").' '.$row['object_name'];

                    }
                } else {
                    print_r($error_info = $db->errorInfo());
                    exit;
                }


                $sql = sprintf(
                    "select `Customer Poll Query Option Name` object_name,`Customer Poll Query Option Key` as object_key from $table   $where $wheref
	                and ($_order_field  > %s OR ($_order_field  = %s AND `Customer Poll Query Option Key` > %d))  order by $_order_field   , `Customer Poll Query Option Key`  limit 1", prepare_mysql($_order_field_value), prepare_mysql($_order_field_value),
                    $poll_option->id
                );

                if ($result = $db->query($sql)) {
                    if ($row = $result->fetch()) {
                        $next_key   = $row['object_key'];
                        $next_title = _("Poll option").' '.$row['object_name'];

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


        $placeholder = _('Search customers');
        $sections    = get_sections('customers', $poll_option->get('Customer Poll Query Option Store Key'));


        $up_button = array(
            'icon'      => 'arrow-up',
            'title'     => sprintf(_("Poll query %s"), $data['_parent']->get('Name')),
            'reference' => 'customers/'.$store->id.'/poll_query/'.$data['_parent']->id
        );

        if ($prev_key) {
            $left_buttons[] = array(
                'icon'      => 'arrow-left',
                'title'     => $prev_title,
                'reference' => 'customers/'.$store->id.'/poll_query/'.$data['_parent']->id.'/option/'.$prev_key
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
                'reference' => 'customers/'.$store->id.'/poll_query/'.$data['_parent']->id.'/option/'.$next_key
            );

        } else {
            $left_buttons[] = array(
                'icon'  => 'arrow-right disabled',
                'title' => '',
                'url'   => ''
            );

        }


    }


    $sections['insights']['selected'] = true;


    $title = sprintf(_('Poll option: %s'), '<span class="id"><span class="Customer_Poll_Query_Option_Name">'.$poll_option->get('Name').'</span></span>');


    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => $title,
        'search'         => array(
            'show'        => true,
            'placeholder' => $placeholder
        )

    );
    $smarty->assign('_content', $_content);


    return array(
        $_content['search'],
        $smarty->fetch('top_menu.tpl'),
        $smarty->fetch('au_header.tpl')
    );

}


function get_prospects_navigation($data, $smarty, $user, $db) {


    require_once 'class.Store.php';

    switch ($data['parent']) {
        case 'store':

            break;
        default:

            break;
    }


    $left_buttons = array();
    if ($user->stores > 1) {


        list($prev_key, $next_key) = get_prev_next(
            $data['store']->id, $user->stores
        );

        $sql = sprintf(
            "SELECT `Store Code` FROM `Store Dimension` WHERE `Store Key`=%d", $prev_key
        );
        if ($result = $db->query($sql)) {
            if ($row = $result->fetch()) {
                $prev_title = _('Prospects').' '.$row['Store Code'];
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
                $next_title = _('Prospects').' '.$row['Store Code'];
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
            'reference' => 'prospects/'.$prev_key
        );


        $left_buttons[] = array(
            'icon'      => 'arrow-right',
            'title'     => $next_title,
            'reference' => 'prospects/'.$next_key
        );
    }


    $right_buttons = array();
    $sections      = get_sections('customers', $data['store']->id);

    if (isset($sections[$data['section']])) {
        $sections[$data['section']]['selected'] = true;
    }


    $_content = array(

        'sections_class' => '',
        'sections'       => $sections,

        'left_buttons'  => $left_buttons,
        'right_buttons' => $right_buttons,
        'title'         => _('Prospects').' <span class="id">'.$data['store']->get('Store Code').'</span>',
        'search'        => array(
            'show'        => true,
            'placeholder' => _('Search prospects')
        )

    );
    $smarty->assign('_content', $_content);

    return array(
        $_content['search'],
        $smarty->fetch('top_menu.tpl'),
        $smarty->fetch('au_header.tpl')
    );

}


function get_prospect_navigation($data, $smarty, $user, $db) {


    $receiver = $data['_object'];

    if (!$receiver->id) {
        return;
    }


    $left_buttons  = array();
    $right_buttons = array();

    if ($data['parent']) {

        switch ($data['parent']) {
            case 'store':
                $tab      = 'prospects';
                $_section = 'prospects';
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
        $_order_field_value = $receiver->get($order);


        $prev_title = '';
        $next_title = '';
        $prev_key   = 0;
        $next_key   = 0;
        $sql        = trim($sql_totals." $wheref");

        if ($result2 = $db->query($sql)) {
            if ($row2 = $result2->fetch() and $row2['num'] > 1) {


                $sql = sprintf(
                    "select `Prospect Name` object_name,P.`Prospect Key` as object_key from $table   $where $wheref
	                and ($_order_field < %s OR ($_order_field = %s AND P.`Prospect Key` < %d))  order by $_order_field desc , P.`Prospect Key` desc limit 1",

                    prepare_mysql($_order_field_value), prepare_mysql($_order_field_value), $receiver->id
                );

                if ($result = $db->query($sql)) {
                    if ($row = $result->fetch()) {
                        $prev_key   = $row['object_key'];
                        $prev_title = _("Prospect").' '.$row['object_name'].' ('.$row['object_key'].')';

                    }
                } else {
                    print_r($error_info = $db->errorInfo());
                    exit;
                }


                $sql = sprintf(
                    "select `Prospect Name` object_name,P.`Prospect Key` as object_key from $table   $where $wheref
	                and ($_order_field  > %s OR ($_order_field  = %s AND P.`Prospect Key` > %d))  order by $_order_field   , P.`Prospect Key`  limit 1", prepare_mysql($_order_field_value), prepare_mysql($_order_field_value), $receiver->id
                );

                if ($result = $db->query($sql)) {
                    if ($row = $result->fetch()) {
                        $next_key   = $row['object_key'];
                        $next_title = _("Prospect").' '.$row['object_name'].' ('.$row['object_key'].')';

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


        $placeholder = _('Search prospects');
        $sections    = get_sections('customers', $receiver->data['Prospect Store Key']);


        $up_button = array(
            'icon'      => 'arrow-up',
            'title'     => _("Prospects").' '.$store->data['Store Code'],
            'reference' => 'prospects/'.$store->id
        );

        if ($prev_key) {
            $left_buttons[] = array(
                'icon'      => 'arrow-left',
                'title'     => $prev_title,
                'reference' => 'prospects/'.$data['parent_key'].'/'.$prev_key
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
                'reference' => 'prospects/'.$data['parent_key'].'/'.$next_key
            );

        } else {
            $left_buttons[] = array(
                'icon'  => 'arrow-right disabled',
                'title' => '',
                'url'   => ''
            );

        }


    } else {
        $_section = 'prospects';

    }

    $right_buttons[] = array(
        'icon'  => 'sticky-note',
        'title' => _('Sticky note'),
        'id'    => 'sticky_note_button',
        'class' => ($receiver->get('Sticky Note') == '' ? '' : 'hide')
    );


    if (isset($sections[$_section])) {
        $sections[$_section]['selected'] = true;
    }


    $title = '<span class="id"><span class="Prospect_Name">'.$receiver->get('Prospect Name').'</span> </span><span class="Status_Label">'.$receiver->get('Status Label').'</span>';


    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => $title,
        'search'         => array(
            'show'        => true,
            'placeholder' => $placeholder
        )

    );
    $smarty->assign('_content', $_content);


    return array(
        $_content['search'],
        $smarty->fetch('top_menu.tpl'),
        $smarty->fetch('au_header.tpl')
    );

}


function get_new_prospect_navigation($data, $smarty, $user, $db) {


    $left_buttons  = array();
    $right_buttons = array();


    $sections = get_sections('customers', $data['parent_key']);

    $_section = 'prospects';
    if (isset($sections[$_section])) {
        $sections[$_section]['selected'] = true;
    }

    $up_button = array(
        'icon'      => 'arrow-up',
        'title'     => _("Prospects"),
        'reference' => 'prospects/'.$data['parent_key']
    );


    $left_buttons[] = $up_button;


    $title = _('New Prospect').' ('._('Store').' <span class="id">'.$data['store']->get('Code').'</span>)';


    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => $title,
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search prospects')
        )

    );
    $smarty->assign('_content', $_content);


    return array(
        $_content['search'],
        $smarty->fetch('top_menu.tpl'),
        $smarty->fetch('au_header.tpl')
    );

}


function get_new_prospect_compose_email_navigation($data, $smarty, $user, $db) {


    $left_buttons  = array();
    $right_buttons = array();


    $sections = get_sections('customers', $data['_parent']->get('Store Key'));

    $_section = 'prospects';
    if (isset($sections[$_section])) {
        $sections[$_section]['selected'] = true;
    }

    $up_button = array(
        'icon'      => 'arrow-up',
        'title'     => _("Prospects"),
        'reference' => 'prospects/'.$data['_parent']->get('Store Key').'/'.$data['_parent']->id
    );


    $left_buttons[] = $up_button;


    $title = sprintf(_('New invitation for %s'), '<span class="id">'.$data['_parent']->get('Name').'</span>');


    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => $title,
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search prospects')
        )

    );
    $smarty->assign('_content', $_content);


    return array(
        $_content['search'],
        $smarty->fetch('top_menu.tpl'),
        $smarty->fetch('au_header.tpl')
    );

}


function get_email_tracking_navigation($data, $smarty, $user, $db) {


    if (!$data['_parent']->id) {
        return;
    }


    $left_buttons  = array();
    $right_buttons = array();

    if ($data['parent']) {

        switch ($data['parent']) {
            case 'prospect':
                $tab      = 'prospect.sent_emails';
                $_section = 'prospects';
                break;
            case 'email_campaign_type':
                $tab      = 'email_campaign_type.sent_emails';
                $_section = 'customer_notifications';
                break;
            case 'mailshot':
                $tab      = 'mailshot.sent_emails';
                $_section = 'customer_notifications';
                break;


            case 'customer':
                $tab      = 'customer.sent_emails';
                $_section = 'customers';
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
        $_order_field_value = $data['_object']->get($order);


        $prev_title = '';
        $next_title = '';
        $prev_key   = 0;
        $next_key   = 0;


        $sql = sprintf(
            "select `Email Tracking Email` object_name, `Email Tracking Created Date` as object_date,  `Email Tracking Key` as object_key from $table   $where $wheref
	                and ($_order_field < %s OR ($_order_field = %s AND `Email Tracking Key` < %d))  order by $_order_field desc , `Email Tracking Key` desc limit 1",

            prepare_mysql($_order_field_value), prepare_mysql($_order_field_value), $data['_object']->id
        );


        if ($result = $db->query($sql)) {
            if ($row = $result->fetch()) {
                $prev_key   = $row['object_key'];
                $prev_title = _("Email tracking").' '.$row['object_name'].' ('.strftime("%a, %e %b %Y %R:%S", strtotime($row['object_date']." +00:00")).')';

            }
        } else {
            print_r($error_info = $db->errorInfo());
            exit;
        }


        $sql = sprintf(
            "select `Email Tracking Email` object_name, `Email Tracking Created Date` as object_date,`Email Tracking Key` as object_key from $table   $where $wheref
	                and ($_order_field  > %s OR ($_order_field  = %s AND `Email Tracking Key` > %d))  order by $_order_field   , `Email Tracking Key`  limit 1", prepare_mysql($_order_field_value), prepare_mysql($_order_field_value), $data['_object']->id
        );

        // print $sql;


        if ($result = $db->query($sql)) {
            if ($row = $result->fetch()) {
                $next_key   = $row['object_key'];
                $prev_title = _("Email tracking").' '.$row['object_name'].' ('.strftime("%a, %e %b %Y %R:%S", strtotime($row['object_date']." +00:00")).')';

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


        switch ($data['parent']) {
            case 'customer':

                $receiver    = $data['_parent'];
                $placeholder = _('Search customers');
                $sections    = get_sections('customers', $receiver->get('Store Key'));


                $up_button = array(
                    'icon'      => 'arrow-up',
                    'title'     => $receiver->get('Name'),
                    'reference' => strtolower($receiver->get_object_name()).'s/'.$receiver->get('Store Key').'/'.$receiver->id
                );

                if ($prev_key) {
                    $left_buttons[] = array(
                        'icon'      => 'arrow-left',
                        'title'     => $prev_title,
                        'reference' => strtolower($receiver->get_object_name()).'s/'.$receiver->get('Store Key').'/'.$receiver->id.'/email/'.$prev_key
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
                        'reference' => strtolower($receiver->get_object_name()).'s/'.$receiver->get('Store Key').'/'.$receiver->id.'/email/'.$next_key
                    );

                } else {
                    $left_buttons[] = array(
                        'icon'  => 'arrow-right disabled',
                        'title' => '',
                        'url'   => ''
                    );

                }

                $title = sprintf(_('Invitation email for %s'), '<span class="id">'.$receiver->get('Name').'</span>');

                break;

            case 'prospect':

                $receiver    = $data['_parent'];
                $placeholder = _('Search prospects');
                $sections    = get_sections('customers', $receiver->get('Store Key'));


                $up_button = array(
                    'icon'      => 'arrow-up',
                    'title'     => $receiver->get('Name'),
                    'reference' => strtolower($receiver->get_object_name()).'s/'.$receiver->get('Store Key').'/'.$receiver->id
                );

                if ($prev_key) {
                    $left_buttons[] = array(
                        'icon'      => 'arrow-left',
                        'title'     => $prev_title,
                        'reference' => strtolower($receiver->get_object_name()).'s/'.$receiver->get('Store Key').'/'.$receiver->id.'/email/'.$prev_key
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
                        'reference' => strtolower($receiver->get_object_name()).'s/'.$receiver->get('Store Key').'/'.$receiver->id.'/email/'.$next_key
                    );

                } else {
                    $left_buttons[] = array(
                        'icon'  => 'arrow-right disabled',
                        'title' => '',
                        'url'   => ''
                    );

                }

                $title = sprintf(_('Invitation email for %s'), '<span class="id">'.$receiver->get('Name').'</span>');

                break;

            case 'email_campaign_type':


                switch ($data['_parent']->get('Email Campaign Type Scope')) {
                    case 'Customer Notification':


                        $placeholder = _('Search emails');
                        $sections    = get_sections('customers', $data['_parent']->get('Store Key'));


                        $up_button = array(
                            'icon'      => 'arrow-up',
                            'title'     => $data['_parent']->get('Label'),
                            'reference' => 'customers/'.$data['_parent']->get('Store Key').'/notifications/'.$data['_parent']->id
                        );

                        if ($prev_key) {
                            $left_buttons[] = array(
                                'icon'      => 'arrow-left',
                                'title'     => $prev_title,
                                'reference' => 'customers/'.$data['_parent']->get('Store Key').'/notifications/'.$data['_parent']->id.'/tracking/'.$prev_key
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
                                'reference' => 'customers/'.$data['_parent']->get('Store Key').'/notifications/'.$data['_parent']->id.'/tracking/'.$next_key
                            );

                        } else {
                            $left_buttons[] = array(
                                'icon'  => 'arrow-right disabled',
                                'title' => '',
                                'url'   => ''
                            );

                        }


                        $title = sprintf(_('Tracking email sent to %s'), '<span class="id">'.$data['_object']->get('Email Tracking Email').'</span>');

                        break;
                }


                break;

            case 'mailshot':

                $email_campaign_type = get_object('email_campaign_type', $data['_parent']->get('Email Campaign Email Template Type Key'));


                $placeholder = _('Search emails');
                $sections    = get_sections('customers', $email_campaign_type->get('Store Key'));


                $up_button = array(
                    'icon'      => 'arrow-up',
                    'title'     => $data['_parent']->get('Label'),
                    'reference' => 'customers/'.$email_campaign_type->get('Store Key').'/notifications/'.$email_campaign_type->id.'/mailshot/'.$data['_parent']->id
                );

                if ($prev_key) {
                    $left_buttons[] = array(
                        'icon'      => 'arrow-left',
                        'title'     => $prev_title,
                        'reference' => 'customers/'.$email_campaign_type->get('Store Key').'/notifications/'.$email_campaign_type->id.'/mailshot/'.$data['_parent']->id.'/tracking/'.$prev_key
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
                        'reference' => 'customers/'.$email_campaign_type->get('Store Key').'/notifications/'.$email_campaign_type->id.'/mailshot/'.$data['_parent']->id.'/tracking/'.$next_key
                    );

                } else {
                    $left_buttons[] = array(
                        'icon'  => 'arrow-right disabled',
                        'title' => '',
                        'url'   => ''
                    );

                }


                $title = sprintf(_('Tracking email sent to %s'), '<span class="id">'.$data['_object']->get('Email Tracking Email').'</span>');


                break;

        }


    }


    if (isset($sections[$_section])) {
        $sections[$_section]['selected'] = true;
    }


    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => $title,
        'search'         => array(
            'show'        => true,
            'placeholder' => $placeholder
        )

    );
    $smarty->assign('_content', $_content);


    return array(
        $_content['search'],
        $smarty->fetch('top_menu.tpl'),
        $smarty->fetch('au_header.tpl')
    );

}


function get_prospects_new_template_navigation($data, $smarty, $user, $db) {


    $left_buttons  = array();
    $right_buttons = array();


    $sections = get_sections('customers', $data['parent_key']);

    $_section = 'prospects';
    if (isset($sections[$_section])) {
        $sections[$_section]['selected'] = true;
    }

    $up_button = array(
        'icon'      => 'arrow-up',
        'title'     => _("Prospects"),
        'reference' => 'prospects/'.$data['parent_key']
    );


    $left_buttons[] = $up_button;


    $title = _('New prospect invitation template').' ('._('Store').' <span class="id">'.$data['store']->get('Code').'</span>)';


    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => $title,
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search prospects')
        )

    );
    $smarty->assign('_content', $_content);


    return array(
        $_content['search'],
        $smarty->fetch('top_menu.tpl'),
        $smarty->fetch('au_header.tpl')
    );

}

function get_prospects_email_template_navigation($data, $smarty, $user, $db) {


    $email_template = $data['_object'];

    if (!$email_template->id) {
        return;
    }


    $left_buttons  = array();
    $right_buttons = array();

    if ($data['parent']) {

        switch ($data['parent']) {
            case 'store':
                $tab      = 'prospects.email_templates';
                $_section = 'prospects';
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
        $_order_field_value = $email_template->get($order);


        $prev_title = '';
        $next_title = '';
        $prev_key   = 0;
        $next_key   = 0;
        $sql        = trim($sql_totals." $wheref");

        if ($result2 = $db->query($sql)) {
            if ($row2 = $result2->fetch() and $row2['num'] > 1) {


                $sql = sprintf(
                    "select `Email Template Name` object_name,`Email Template Key` as object_key from $table   $where $wheref
	                and ($_order_field < %s OR ($_order_field = %s AND `Email Template Key` < %d))  order by $_order_field desc , `Email Template Key` desc limit 1",

                    prepare_mysql($_order_field_value), prepare_mysql($_order_field_value), $email_template->id
                );

                if ($result = $db->query($sql)) {
                    if ($row = $result->fetch()) {
                        $prev_key   = $row['object_key'];
                        $prev_title = _("Email template").' '.$row['object_name'];

                    }
                } else {
                    print_r($error_info = $db->errorInfo());
                    exit;
                }


                $sql = sprintf(
                    "select `Email Template Name` object_name,`Email Template Key` as object_key from $table   $where $wheref
	                and ($_order_field  > %s OR ($_order_field  = %s AND `Email Template Key` > %d))  order by $_order_field   , `Email Template Key`  limit 1", prepare_mysql($_order_field_value), prepare_mysql($_order_field_value), $email_template->id
                );

                if ($result = $db->query($sql)) {
                    if ($row = $result->fetch()) {
                        $next_key   = $row['object_key'];
                        $next_title = _("Email Template").' '.$row['object_name'];

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


        $placeholder = _('Search prospects');
        $sections    = get_sections('customers', $data['store']->id);


        $up_button = array(
            'icon'      => 'arrow-up',
            'title'     => _("Email Templates").' '.$data['store']->data['Store Code'],
            'reference' => 'prospects/'.$data['store']->id.''
        );

        if ($prev_key) {
            $left_buttons[] = array(
                'icon'      => 'arrow-left',
                'title'     => $prev_title,
                'reference' => 'prospects/'.$data['parent_key'].'/'.$prev_key
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
                'reference' => 'prospects/'.$data['parent_key'].'/'.$next_key
            );

        } else {
            $left_buttons[] = array(
                'icon'  => 'arrow-right disabled',
                'title' => '',
                'url'   => ''
            );

        }


    } else {
        $_section = 'prospects';

    }


    if (isset($sections[$_section])) {
        $sections[$_section]['selected'] = true;
    }


    $title = '<span class="id"><span class="Email Template_Name">'.$email_template->get('Email Template Name').'</span> </span>';


    if ($email_template->get('Email Template State') == 'Suspended') {
        $title .= ' <span class="error small margin_left_10"><i class="fa fa-stop "></i> '._('Suspended').'</span>';
    }

    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => $title,
        'search'         => array(
            'show'        => true,
            'placeholder' => $placeholder
        )

    );
    $smarty->assign('_content', $_content);


    return array(
        $_content['search'],
        $smarty->fetch('top_menu.tpl'),
        $smarty->fetch('au_header.tpl')
    );

}


function get_customer_product_navigation($data, $smarty, $user, $db) {


    $customer = $data['_parent'];
    $product  = $data['_object'];
    $store    = get_object('Store', $customer->get('Store Key'));
    if (!$customer->id) {
        return;
    }


    $left_buttons  = array();
    $right_buttons = array();


    switch ($data['parent']) {
        case 'customer':
            $tab      = 'customer.marketing.products';
            $_section = 'customers';
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


    // todo find way to do this
    if ($order == 'amount' or $order == 'qty' or $order == 'invoices') {
        $order = 'P.`Product Code`';
    }


    $_order_field = $order;
    $order        = preg_replace('/^.*\.`/', '', $order);
    $order        = preg_replace('/^`/', '', $order);
    $order        = preg_replace('/`$/', '', $order);


    $_order_field_value = $product->get($order);


    $prev_title = '';
    $next_title = '';
    $prev_key   = 0;
    $next_key   = 0;
    $sql        = trim($sql_totals." $wheref");


    if ($result2 = $db->query($sql)) {
        if ($row2 = $result2->fetch() and $row2['num'] > 1) {


            $sql = sprintf(
                "select P.`Product Code` object_name,P.`Product ID` as object_key from $table   $where $wheref
	                and ($_order_field < %s OR ($_order_field = %s AND P.`Product ID` < %d))  group by P.`Product ID` order by $_order_field desc , P.`Product ID`desc limit 1",

                prepare_mysql($_order_field_value), prepare_mysql($_order_field_value), $product->id
            );


            // print $sql;

            if ($result = $db->query($sql)) {
                if ($row = $result->fetch()) {
                    // print_r($row);

                    $prev_key   = $row['object_key'];
                    $prev_title = _("Product").' '.$row['object_name'];

                }
            } else {
                print_r($error_info = $db->errorInfo());
                exit;
            }


            $sql = sprintf(
                "select P.`Product Code` object_name,P.`Product ID` as object_key from $table   $where $wheref
	                and ($_order_field  > %s OR ($_order_field  = %s AND P.`Product ID` > %d))  group by P.`Product ID` order by $_order_field   , P.`Product ID`  limit 1", prepare_mysql($_order_field_value), prepare_mysql($_order_field_value), $product->id
            );
            //print $sql;
            if ($result = $db->query($sql)) {
                if ($row = $result->fetch()) {
                    //print_r($row);
                    $next_key   = $row['object_key'];
                    $next_title = _("Product").' '.$row['object_name'];

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


    $placeholder = _('Search customers');
    $sections    = get_sections('customers', $customer->data['Customer Store Key']);


    $up_button = array(
        'icon'      => 'arrow-up',
        'title'     => _("Customer").' '.$customer->get('Customer Name'),
        'reference' => 'customers/'.$store->id.'/'.$customer->id
    );

    if ($prev_key) {
        $left_buttons[] = array(
            'icon'      => 'arrow-left',
            'title'     => $prev_title,
            'reference' => 'customers/'.$store->id.'/'.$data['parent_key'].'/product/'.$prev_key
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
            'reference' => 'customers/'.$store->id.'/'.$data['parent_key'].'/product/'.$next_key
        );

    } else {
        $left_buttons[] = array(
            'icon'  => 'arrow-right disabled',
            'title' => '',
            'url'   => ''
        );

    }


    if (isset($sections[$_section])) {
        $sections[$_section]['selected'] = true;
    }

    $avatar = '';


    $title = '<span class="Customer_Level_Type_Icon">'.$customer->get('Level Type Icon').'</span>';
    $title .= '<span ><span class="Customer_Name">'.$customer->get('Customer Name').'</span> ('.$customer->get_formatted_id().')</span>';
    $title .= '<span class="id Product_Code margin_left_10">'.$product->get('Code').'</span>';


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


    return array(
        $_content['search'],
        $smarty->fetch('top_menu.tpl'),
        $smarty->fetch('au_header.tpl')
    );

}


function get_mailshot_navigation($data, $smarty, $user, $db, $account) {


    $object        = $data['_object'];
    $left_buttons  = array();
    $right_buttons = array();


    $_section = 'customer_notifications';

    switch ($data['section']) {


        case 'mailshot':
            $tab = 'email_campaign_type.mailshots';
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


    $sql = sprintf(
        "select `Email Campaign Name` object_name,`Email Campaign Key` as object_key from $table   $where $wheref
	                and ($_order_field < %s OR ($_order_field = %s AND `Email Campaign Key` < %d))  order by $_order_field desc , `Email Campaign Key` desc limit 1",

        prepare_mysql($_order_field_value), prepare_mysql($_order_field_value), $object->id
    );


    if ($result = $db->query($sql)) {
        if ($row = $result->fetch()) {
            $prev_key = $row['object_key'];


            $prev_title = _("Mailshot").' '.$row['object_name'];


        }
    } else {
        print $sql;

        print_r($error_info = $db->errorInfo());
        exit;
    }


    $sql = sprintf(
        "select `Email Campaign Name` object_name,`Email Campaign Key` as object_key from $table   $where $wheref
	                and ($_order_field  > %s OR ($_order_field  = %s AND `Email Campaign Key` > %d))  order by $_order_field   , `Email Campaign Key`  limit 1", prepare_mysql($_order_field_value), prepare_mysql($_order_field_value), $object->id
    );

    if ($result = $db->query($sql)) {
        if ($row = $result->fetch()) {
            $next_key = $row['object_key'];

            $next_title = _("Mailshot").' '.$row['object_name'];


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


    $title     = _('Mailshot').' <span class="id Email_Campaign_Name">'.$object->get('Name').'</span>';
    $up_button = array(
        'icon'      => 'arrow-up',
        'title'     => _("Mailshots").' ('.$data['store']->get('Code').')',
        'reference' => 'customers/'.$data['_parent']->get('Store Key').'/notifications/'.$data['_parent']->id
    );


    if ($prev_key) {
        $left_buttons[] = array(
            'icon'      => 'arrow-left',
            'title'     => $prev_title,
            'reference' => 'customers/'.$data['_parent']->get('Store Key').'/notifications/'.$data['_parent']->id.'/mailshot/'.$prev_key
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
            'reference' => 'customers/'.$data['_parent']->get('Store Key').'/notifications/'.$data['_parent']->id.'/mailshot/'.$next_key
        );

    } else {
        $left_buttons[] = array(
            'icon'  => 'arrow-right disabled',
            'title' => '',
            'url'   => ''
        );

    }


    $sections = get_sections('customers', $data['_parent']->get('Store Key'));


    if (isset($sections[$_section])) {
        $sections[$_section]['selected'] = true;
    }


    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => $title,
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search customers')
        )

    );
    $smarty->assign('_content', $_content);


    return array(
        $_content['search'],
        $smarty->fetch('top_menu.tpl'),
        $smarty->fetch('au_header.tpl')
    );

}

/**
 * @param $data   array
 * @param $smarty \Smarty
 * @param $user
 * @param $db     \PDO
 *
 * @return mixed
 * @throws \SmartyException
 */
function get_new_customer_client_navigation($data, $smarty, $user, $db) {


    $left_buttons  = array();
    $right_buttons = array();


    $sections = get_sections('customers', $data['parent_key']);

    $_section = 'customers';
    if (isset($sections[$_section])) {
        $sections[$_section]['selected'] = true;
    }

    $up_button = array(
        'icon'      => 'arrow-up',
        'title'     => $data['_parent']->get('Name'),
        'reference' => 'customers/'.$data['_parent']->get('Store Key').'/'.$data['parent_key']
    );


    $left_buttons[] = $up_button;


    $title = _("New customer's client").' (<span class="id">'.$data['_parent']->get('Formatted ID').'</span> <span class="small">'.$data['_parent']->get('Name').'</span> )';


    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => $title,
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search customers')
        )

    );
    $smarty->assign('_content', $_content);


    return array(
        $_content['search'],
        $smarty->fetch('top_menu.tpl'),
        $smarty->fetch('au_header.tpl')
    );

}

/**
 * @param $data
 * @param $smarty \Smarty
 * @param $user   \User
 * @param $db     \PDO
 *
 * @return string
 */
function get_customer_client_navigation($data, $smarty, $user, $db) {


    $client   = $data['_object'];
    $customer = $data['_parent'];

    if (!$client->id) {
        return '';
    }


    $left_buttons  = array();
    $right_buttons = array();


    $tab      = 'customer_clients';
    $_section = 'customer';

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
    $_order_field_value = $client->get($order);


    $prev_title = '';
    $next_title = '';
    $prev_key   = 0;
    $next_key   = 0;


    $sql = sprintf(
        "select `Customer Client Code` object_name,`Customer Client Key` as object_key from $table $where $wheref
	                and ($_order_field < %s OR ($_order_field = %s AND CC.`Customer Client Key` < %d))  order by $_order_field desc , CC.`Customer Client Key` desc limit 1",

        prepare_mysql($_order_field_value), prepare_mysql($_order_field_value), $client->id
    );

    if ($result = $db->query($sql)) {
        if ($row = $result->fetch()) {
            $prev_key   = $row['object_key'];
            $prev_title = _("Customer's client").' '.$row['object_name'].' ('.$row['object_key'].')';

        }
    }


    $sql = sprintf(
        "select `Customer Client Code` object_name,`Customer Client Key` as object_key from $table   $where $wheref
	                and ($_order_field  > %s OR ($_order_field  = %s AND CC.`Customer Client Key` > %d))  order by $_order_field   , CC.`Customer Client Key`  limit 1", prepare_mysql($_order_field_value), prepare_mysql($_order_field_value), $client->id
    );

    if ($result = $db->query($sql)) {
        if ($row = $result->fetch()) {
            $next_key   = $row['object_key'];
            $next_title = _("Customer's client").' '.$row['object_name'].' ('.$row['object_key'].')';

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


    $placeholder = _('Search customers');
    $sections    = get_sections('customers', $client->data['Customer Client Store Key']);


    $up_button = array(
        'icon'      => 'arrow-up',
        'title'     => _("Customer").' '.$customer->get('Name'),
        'reference' => 'customers/'.$customer->get('Store Key').'/'.$customer->id
    );

    if ($prev_key) {
        $left_buttons[] = array(
            'icon'      => 'arrow-left',
            'title'     => $prev_title,
            'reference' => 'customers/'.$customer->get('Store Key').'/'.$customer->id.'/client/'.$prev_key
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
            'reference' => 'customers/'.$customer->get('Store Key').'/'.$customer->id.'/client/'.$next_key
        );

    } else {
        $left_buttons[] = array(
            'icon'  => 'arrow-right disabled',
            'title' => '',
            'url'   => ''
        );

    }


    $right_buttons[] = array(
        'html_icon' => '<i class="fas fa-cart-arrow-down"></i>',
        'title'     => _("New dropshipping order"),
        'id'        => 'take_customer_client_order'
    );


    if (isset($sections[$_section])) {
        $sections[$_section]['selected'] = true;
    }


    $title = '<i class="far fa-address-book"></i> <span class="id Formatted_Client_Code">['.$client->get('Formatted Client Code').']</span> <span class="Name_Truncated">'.$client->get('Name Truncated').'</span>';
    if ($client->get('Customer Client Status') == 'Inactive') {
        $title .= ' <span class="warning"><i class="fal fa-eye-slash"></i> '._('Removed').'</span>';
    }
    $title .= ' <span class="very_small button padding_left_20" onclick="change_view(\'customers/'.$customer->get('Customer Store Key').'/'.$customer->id.'\')"><i class="fal fa-level-up"></i> <i class="fal fa-user"></i> '.$customer->get('Formatted ID').'</span>';

    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => $title,
        'search'         => array(
            'show'        => true,
            'placeholder' => $placeholder
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


    $sections = get_sections('customers', $data['parent_key']);


    if ($data['_object']->get('Upload Object') == 'prospect') {

        $_section = 'prospects';
        if (isset($sections[$_section])) {
            $sections[$_section]['selected'] = true;
        }
        $up_button = array(
            'icon'      => 'arrow-up',
            'title'     => sprintf(
                _('Prospects: %s'), $data['_parent']->get('Code')
            ),
            'reference' => 'prospects/'.$data['parent_key']
        );

        $title = sprintf(
            _('Upload into prospects %s'), $data['_parent']->get('Code')
        );

    }

    $left_buttons[] = $up_button;

    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => $title,
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search customers')
        )

    );
    $smarty->assign('_content', $_content);


    return array(
        $_content['search'],
        $smarty->fetch('top_menu.tpl'),
        $smarty->fetch('au_header.tpl')
    );

}

function get_new_customer_attachment_navigation($data, $smarty): array {


    $left_buttons  = array();
    $right_buttons = array();


    $sections = get_sections($data['module'], $data['_parent']->get('Store Key'));


    $link = 'customers/'.$data['_parent']->get('Store Key').'/'.$data['_parent']->id;

    $up_button = array(
        'icon'      => 'arrow-up',
        'title'     => _('Customer').' '.$data['_parent']->get('Name'),
        'reference' => $link,
        'parent'    => ''
    );

    $left_buttons[] = $up_button;


    $title = '<span>'.sprintf(
            _('New attachment for %s'), '<span onClick="change_view(\''.$link.'\')" class="button id">'.$data['_parent']->get('Name').'</span>'
        ).'</span>';


    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => $title,
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search customers')
        )

    );
    $smarty->assign('_content', $_content);


    return array(
        $_content['search'],
        $smarty->fetch('top_menu.tpl'),
        $smarty->fetch('au_header.tpl')
    );

}

function get_customer_attachment_navigation($data, $smarty, $user, $db, $account): array {


    $sections = get_sections($data['module'], $data['_parent']->get('Store Key'));

    $tab  = 'customer.attachments';
    $link = 'customers/'.$data['_parent']->get('Store Key').'/'.$data['_parent']->id;

    $up_button = array(
        'icon'      => 'arrow-up',
        'title'     => _('Customer').' '.$data['_parent']->get('Name'),
        'reference' => $link,
        'parent'    => ''
    );


    include_once 'prepare_table/attachments.ptc.php';
    $table = new prepare_table_attachments($db, $account, $user);

    $left_buttons = get_navigation_buttons(
        $table->get_navigation($data['_object'], $tab, $data), $up_button, $link.'/attachment/%d'

    );

    $right_buttons = array();

    $right_buttons[] = array(
        'icon'  => 'download',
        'title' => _('Download'),
        'id'    => 'download_button'
    );

    $title = _('Attachment').' <span class="id Attachment_Caption">'.$data['_object']->get('Caption').'</span>';


    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => $title,
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search customers')
        )

    );
    $smarty->assign('_content', $_content);


    return array(
        $_content['search'],
        $smarty->fetch('top_menu.tpl'),
        $smarty->fetch('au_header.tpl')
    );

}

