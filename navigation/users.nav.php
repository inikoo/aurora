<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 4 February 2019 at 16:22:46 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2019, Inikoo

 Version 3.0
*/


function get_new_api_key_navigation($data, $smarty, $user, $db, $account) {


    $sections                        = get_sections('users', '');
    $sections['account']['selected'] = true;

    $title = _('New API key');

    $up_button      = array(
        'icon'      => 'arrow-up',
        'title'     => _("Staff user"),
        'reference' => 'users/'.$data['parent_key']
    );
    $left_buttons   = array();
    $left_buttons[] = $up_button;
    $_content       = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => array(),
        'title'          => $title,
        'search'         => array(
            'show'        => false,
            'placeholder' => _('Search users')
        )

    );
    $smarty->assign('_content', $_content);

    $html = $smarty->fetch('navigation.tpl');

    return $html;
}

function get_users_navigation($data, $smarty, $user, $db, $account) {


    $sections                      = get_sections('users', '');
    $sections['users']['selected'] = true;
    $title                         = _('Users');

    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => array(),
        'right_buttons'  => array(),
        'title'          => $title,
        'search'         => array(
            'show'        => false,
            'placeholder' => _('Search users')
        )

    );
    $smarty->assign('_content', $_content);

    $html = $smarty->fetch('navigation.tpl');

    return $html;
}

function get_staff_navigation($data, $smarty, $user, $db, $account) {


    $up_button      = array(
        'icon'      => 'arrow-up',
        'title'     => _("Users"),
        'reference' => 'users'
    );
    $left_buttons   = array();
    $left_buttons[] = $up_button;

    $right_buttons = array();
    $sections      = get_sections('users', '');

    $sections['staff']['selected'] = true;


    $_content = array(

        'sections_class' => '',
        'sections'       => $sections,

        'left_buttons'  => $left_buttons,
        'right_buttons' => $right_buttons,
        'title'         => _('Employees users'),
        'search'        => array(
            'show'        => true,
            'placeholder' => _('Search users')
        )

    );
    $smarty->assign('_content', $_content);

    $html = $smarty->fetch('navigation.tpl');

    return $html;

}

function get_contractors_navigation($data, $smarty, $user, $db, $account) {




    $up_button      = array(
        'icon'      => 'arrow-up',
        'title'     => _("Users"),
        'reference' => 'users'
    );
    $left_buttons   = array();
    $left_buttons[] = $up_button;

    $right_buttons = array();
    $sections      = get_sections('users', '');

    $sections['contractors']['selected'] = true;


    $_content = array(

        'sections_class' => '',
        'sections'       => $sections,

        'left_buttons'  => $left_buttons,
        'right_buttons' => $right_buttons,
        'title'         => _('Contractor users'),
        'search'        => array(
            'show'        => true,
            'placeholder' => _('Search users')
        )

    );
    $smarty->assign('_content', $_content);

    $html = $smarty->fetch('navigation.tpl');

    return $html;

}

function get_suppliers_navigation($data, $smarty, $user, $db, $account) {




    $up_button      = array(
        'icon'      => 'arrow-up',
        'title'     => _("Users"),
        'reference' => 'users'
    );
    $left_buttons   = array();
    $left_buttons[] = $up_button;

    $right_buttons = array();
    $sections      = get_sections('users', '');

    $sections['suppliers']['selected'] = true;


    $_content = array(

        'sections_class' => '',
        'sections'       => $sections,

        'left_buttons'  => $left_buttons,
        'right_buttons' => $right_buttons,
        'title'         => _('Suppliers users'),
        'search'        => array(
            'show'        => true,
            'placeholder' => _('Search users')
        )

    );
    $smarty->assign('_content', $_content);

    $html = $smarty->fetch('navigation.tpl');

    return $html;

}

function get_agents_navigation($data, $smarty, $user, $db, $account) {




    $up_button      = array(
        'icon'      => 'arrow-up',
        'title'     => _("Users"),
        'reference' => 'users'
    );
    $left_buttons   = array();
    $left_buttons[] = $up_button;

    $right_buttons = array();
    $sections      = get_sections('users', '');

    $sections['agents']['selected'] = true;


    $_content = array(

        'sections_class' => '',
        'sections'       => $sections,

        'left_buttons'  => $left_buttons,
        'right_buttons' => $right_buttons,
        'title'         => _('Agents users'),
        'search'        => array(
            'show'        => true,
            'placeholder' => _('Search users')
        )

    );
    $smarty->assign('_content', $_content);

    $html = $smarty->fetch('navigation.tpl');

    return $html;

}

function get_user_navigation($data, $smarty, $user, $db, $account) {


    $object        = $data['_object'];
    $left_buttons  = array();
    $right_buttons = array();

    if ($data['parent']) {

        switch ($data['parent']) {
            case 'account':
                $tab      = 'users.staff';
                $_section = 'staff';
                break;
            case 'group':
                $tab      = 'users.staff.groups';
                $_section = 'staff';
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


            switch ($data['_object']->get('User Type')) {
                case 'Staff':
                    $parameters['tab'] = 'users.staff';
                    break;
                case 'Contractor':
                    $parameters['tab'] = 'users.contractors';
                    break;
                case 'Agent':
                    $parameters['tab'] = 'users.agents';
                    break;
                case 'Supplier':
                    $parameters['tab'] = 'users.suppliers';
                    break;
                default:

                    break;
            }

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
                    "select `User Alias` object_name,U.`User Key` as object_key from %s and ($_order_field < %s OR ($_order_field = %s AND U.`User Key` < %d))  order by $_order_field desc , U.`User Key` desc limit 1",
                    "$table $where $wheref", prepare_mysql($_order_field_value), prepare_mysql($_order_field_value), $object->id
                );


                if ($result = $db->query($sql)) {
                    if ($row = $result->fetch()) {
                        $prev_key   = $row['object_key'];
                        $prev_title = _("User").' '.$row['object_name'].' ('.$row['object_key'].')';

                    }
                } else {
                    print_r($error_info = $db->errorInfo());
                    exit;
                }


                $sql = sprintf(
                    "select `User Alias` object_name,U.`User Key` as object_key from %s and ($_order_field  > %s OR ($_order_field  = %s AND U.`User Key` > %d))  order by $_order_field   , U.`User Key`  limit 1",
                    "$table  $where $wheref", prepare_mysql($_order_field_value), prepare_mysql($_order_field_value), $object->id
                );


                if ($result = $db->query($sql)) {
                    if ($row = $result->fetch()) {
                        $next_key   = $row['object_key'];
                        $next_title = _("User").' '.$row['object_name'].' ('.$row['object_key'].')';

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


        if ($data['parent'] == 'group') {


            include_once 'class.Category.php';
            $category = new Category($data['parent_key']);


            $category_keys = preg_split(
                '/\>/', preg_replace('/\>$/', '', $category->data['Category Position'])
            );
            array_pop($category_keys);
            if (count($category_keys) > 0) {
                $sql = sprintf(
                    "SELECT `Category Code`,`Category Key` FROM `Category Dimension` WHERE `Category Key` IN (%s)", join(',', $category_keys)
                );

                if ($result = $this->db->query($sql)) {
                    foreach ($result as $row) {
                        //TODO
                    }
                } else {
                    print_r($error_info = $db->errorInfo());
                    exit;
                }


            }


            $up_button = array(
                'icon'  => 'arrow-up',
                'title' => _(
                        "Category"
                    ).' '.$category->data['Category Code'],
                'url'   => 'supplier_category.php?id='.$category->id
            );

        } else {

            $up_button = array(
                'icon'      => 'arrow-up',
                'title'     => _("Staff users"),
                'reference' => 'users/staff'
            );

            if ($prev_key) {
                $left_buttons[] = array(
                    'icon'      => 'arrow-left',
                    'title'     => $prev_title,
                    'reference' => 'users/'.$prev_key
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
                    'reference' => 'users/'.$next_key
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
        $_section = 'staff';

    }

    $sections = get_sections('users', '');



    $parent_label = $object->get('User Alias');

    switch ($object->get('User Type')) {
        case 'Staff':
            $parent_reference = 'employee/'.$object->get('User Parent Key');
            $parent_icon = '<i style="font-size:80%;padding-left:10px" class="fal fa-user-headset" aria-hidden="true"></i>';
            $sections['staff']['selected'] = true;

            break;
        case 'Contractor':
            $parent_reference = 'contractor/'.$object->get('User Parent Key');
            $parent_icon
                              = '<i style="font-size:80%;padding-left:10px" class="fal   fa-user-hard-hat" aria-hidden="true"></i>';
            $sections['contractors']['selected'] = true;

            break;
        case 'Supplier':
            $parent_reference = 'supplier/'.$object->get('User Parent Key');
            $parent_icon = '<i style="font-size:80%;padding-left:10px" class="fa fa-hand-holding-box " aria-hidden="true"></i>';
            $sections['suppliers']['selected'] = true;

            break;
        case 'Agent':
            $parent_reference = 'agent/'.$object->get('User Parent Key');
            $parent_icon      = '<i style="font-size:80%;padding-left:10px" class="fa fa-user-secret" aria-hidden="true"></i>';
            $agent        = get_object('Agent',$object->get('User Parent Key'));
            $parent_label = $agent->get('Code');
            $sections['agents']['selected'] = true;

            break;
        default:

            break;
    }


    $title = '<span class="id">'.$object->get('User Handle').'</span> <span class="small button" onClick="change_view(\''.$parent_reference.'\')"  > '.$parent_icon.' '.$parent_label.' </span> ';


    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => $title,
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search users')
        )

    );
    $smarty->assign('_content', $_content);


    $html = $smarty->fetch('navigation.tpl');

    return $html;

}

function get_deleted_user_navigation($data, $smarty, $user, $db, $account) {


    $object        = $data['_object'];
    $left_buttons  = array();
    $right_buttons = array();

    if ($data['parent']) {

        switch ($data['parent']) {
            case 'account':
                $tab      = 'deleted.users';
                $_section = 'staff';
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
                    "select `User Deleted Alias` object_name,U.`User Deleted Key` as object_key from %s and ($_order_field < %s OR ($_order_field = %s AND U.`User Deleted Key` < %d))  order by $_order_field desc , U.`User Deleted Key` desc limit 1",
                    "$table $where $wheref", prepare_mysql($_order_field_value), prepare_mysql($_order_field_value), $object->id
                );


                if ($result = $db->query($sql)) {
                    if ($row = $result->fetch()) {
                        $prev_key   = $row['object_key'];
                        $prev_title = _("User").' '.$row['object_name'].' ('.$row['object_key'].')';

                    }
                } else {
                    print_r($error_info = $db->errorInfo());
                    exit;
                }


                $sql = sprintf(
                    "select `User Deleted Alias` object_name,U.`User Deleted Key` as object_key from %s and ($_order_field  > %s OR ($_order_field  = %s AND U.`User Deleted Key` > %d))  order by $_order_field   , U.`User Deleted Key`  limit 1",
                    "$table  $where $wheref", prepare_mysql($_order_field_value), prepare_mysql($_order_field_value), $object->id
                );


                if ($result = $db->query($sql)) {
                    if ($row = $result->fetch()) {
                        $next_key   = $row['object_key'];
                        $next_title = _("User").' '.$row['object_name'].' ('.$row['object_key'].')';

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
            'title'     => _("Staff users"),
            'reference' => 'users/'
        );

        if ($prev_key) {
            $left_buttons[] = array(
                'icon'      => 'arrow-left',
                'title'     => $prev_title,
                'reference' => 'users/'.$prev_key
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
                'reference' => 'users/'.$next_key
            );

        } else {
            $left_buttons[] = array(
                'icon'  => 'arrow-right disabled',
                'title' => '',
                'url'   => ''
            );

        }


    } else {
        $_section = 'staff';

    }

    $sections = get_sections('users', '');


    $sections['users']['selected'] = true;


    $title = '<span class="id">'.$object->get('User Deleted Handle').'</span> ';


    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => $title,
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search users')
        )

    );
    $smarty->assign('_content', $_content);


    $html = $smarty->fetch('navigation.tpl');

    return $html;

}

function get_api_key_navigation($data, $smarty, $user, $db, $account) {

    global $smarty, $user;

    $object        = $data['_object'];
    $left_buttons  = array();
    $right_buttons = array();

    if ($data['parent']) {

        switch ($data['parent']) {
            case 'account':
                $tab      = 'account.api_keys';
                $_section = 'account';
                break;
            case 'user':
                $tab      = 'staff.user.api_keys';
                $_section = 'account';
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
                    "select `API Key Key` object_name,AKD.`API Key Key` as object_key from $table   $where $wheref
	                and ($_order_field < %s OR ($_order_field = %s AND AKD.`API Key Key` < %d))  order by $_order_field desc , AKD.`API Key Key` desc limit 1",

                    prepare_mysql($_order_field_value), prepare_mysql($_order_field_value), $object->id
                );

                if ($result = $db->query($sql)) {
                    if ($row = $result->fetch()) {
                        $prev_key   = $row['object_key'];
                        $prev_title = _("User").' '.$row['object_name'].' ('.$row['object_key'].')';
                    }
                } else {
                    print_r($error_info = $db->errorInfo());
                    exit;
                }


                $sql = sprintf(
                    "select `API Key Key` object_name,AKD.`API Key Key` as object_key from $table   $where $wheref
	                and ($_order_field  > %s OR ($_order_field  = %s AND AKD.`API Key Key` > %d))  order by $_order_field   , AKD.`API Key Key`  limit 1", prepare_mysql($_order_field_value),
                    prepare_mysql($_order_field_value), $object->id
                );

                if ($result = $db->query($sql)) {
                    if ($row = $result->fetch()) {
                        $next_key   = $row['object_key'];
                        $next_title = _("User").' '.$row['object_name'].' ('.$row['object_key'].')';
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


        if ($data['parent'] == 'user') {

            $system_user = new User($data['parent_key']);


            $up_button = array(
                'icon'      => 'arrow-up',
                'title'     => _("Staff user").' '.$system_user->get(
                        'Alias'
                    ),
                'reference' => 'users/'.$system_user->id
            );

            if ($prev_key) {
                $left_buttons[] = array(
                    'icon'      => 'arrow-left',
                    'title'     => $prev_title,
                    'reference' => 'users/'.$system_user->id.'/api_key/'.$prev_key
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
                    'reference' => 'users/'.$system_user->id.'/api_key/'.$next_key
                );

            } else {
                $left_buttons[] = array(
                    'icon'  => 'arrow-right disabled',
                    'title' => '',
                    'url'   => ''
                );

            }

        } else {

            $up_button = array(
                'icon'      => 'arrow-up',
                'title'     => _("Staff users"),
                'reference' => 'users/staff'
            );

            if ($prev_key) {
                $left_buttons[] = array(
                    'icon'      => 'arrow-left',
                    'title'     => $prev_title,
                    'reference' => 'account/api_key/'.$prev_key
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
                    'reference' => 'account/api_key/'.$next_key
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
        $_section = 'staff';

    }

    $sections = get_sections('users', '');


    $sections['users']['selected'] = true;


    $title = _('API key').': <span >'.$object->get('Scope').'</span> (<span class="id">'.$object->get('Code').'</span>)';

    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => $title,
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search users')
        )

    );
    $smarty->assign('_content', $_content);


    $html = $smarty->fetch('navigation.tpl');

    return $html;

}

function get_deleted_api_key_navigation($data, $smarty, $user, $db, $account) {


    $object        = $data['_object'];
    $left_buttons  = array();
    $right_buttons = array();

    if ($data['parent']) {

        switch ($data['parent']) {
            case 'account':
                $tab      = 'account.deleted_api_keys';
                $_section = 'account';
                break;
            case 'user':
                $tab      = 'staff.user.deleted_api_keys';
                $_section = 'account';
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
                    "select `API Key Deleted Key` object_name,AKD.`API Key Deleted Key` as object_key from $table   $where $wheref
	                and ($_order_field < %s OR ($_order_field = %s AND AKD.`API Key Deleted Key` < %d))  order by $_order_field desc , AKD.`API Key Deleted Key` desc limit 1",

                    prepare_mysql($_order_field_value), prepare_mysql($_order_field_value), $object->id
                );

                if ($result = $db->query($sql)) {
                    if ($row = $result->fetch()) {
                        $prev_key   = $row['object_key'];
                        $prev_title = _("User").' '.$row['object_name'].' ('.$row['object_key'].')';
                    }
                } else {
                    print $sql;
                    print_r($error_info = $db->errorInfo());
                    exit;
                }


                $sql = sprintf(
                    "select `API Key Deleted Key` object_name,AKD.`API Key Deleted Key` as object_key from $table   $where $wheref
	                and ($_order_field  > %s OR ($_order_field  = %s AND AKD.`API Key Deleted Key` > %d))  order by $_order_field   , AKD.`API Key Deleted Key`  limit 1", prepare_mysql($_order_field_value),
                    prepare_mysql($_order_field_value), $object->id
                );

                if ($result = $db->query($sql)) {
                    if ($row = $result->fetch()) {
                        $next_key   = $row['object_key'];
                        $next_title = _("User").' '.$row['object_name'].' ('.$row['object_key'].')';
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


        if ($data['parent'] == 'user') {

            $system_user = new User($data['parent_key']);


            $up_button = array(
                'icon'      => 'arrow-up',
                'title'     => _("Staff user").' '.$system_user->get('Alias'),
                'reference' => 'users/'.$system_user->id
            );

            if ($prev_key) {
                $left_buttons[] = array(
                    'icon'      => 'arrow-left',
                    'title'     => $prev_title,
                    'reference' => 'users/'.$system_user->id.'/api_key/'.$prev_key
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
                    'reference' => 'users/'.$system_user->id.'/api_key/'.$next_key
                );

            } else {
                $left_buttons[] = array(
                    'icon'  => 'arrow-right disabled',
                    'title' => '',
                    'url'   => ''
                );

            }

        } else {

            $up_button = array(
                'icon'      => 'arrow-up',
                'title'     => _("Staff users"),
                'reference' => 'users/staff'
            );

            if ($prev_key) {
                $left_buttons[] = array(
                    'icon'      => 'arrow-left',
                    'title'     => $prev_title,
                    'reference' => 'account/api_key/'.$prev_key
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
                    'reference' => 'account/api_key/'.$next_key
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
        $_section = 'staff';

    }

    $sections = get_sections('users', '');


    $sections['users']['selected'] = true;


    $title = _('Deleted API key').': <span >'.$object->get('Deleted Scope').'</span> (<span class="id">'.$object->get('Deleted Code').'</span>)';

    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => $title,
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search users')
        )

    );
    $smarty->assign('_content', $_content);


    $html = $smarty->fetch('navigation.tpl');

    return $html;

}

function get_profile_navigation($data, $smarty, $user, $db, $account) {


    $title = _('My profile');

    $_content = array(
        'sections_class' => '',
        'sections'       => array(),
        'left_buttons'   => array(),
        'right_buttons'  => array(),
        'title'          => $title,
        'search'         => array(
            'show'        => false,
            'placeholder' => _('Search users')
        )

    );
    $smarty->assign('_content', $_content);

    $html = $smarty->fetch('navigation.tpl');

    return $html;
}

function get_profile_new_api_key_navigation($data, $smarty, $user, $db, $account) {


    $title = _('New API key');

    $_content = array(
        'sections_class' => '',
        'sections'       => array(),
        'left_buttons'   => array(),
        'right_buttons'  => array(),
        'title'          => $title,
        'search'         => array(
            'show'        => false,
            'placeholder' => _('Search users')
        )

    );
    $smarty->assign('_content', $_content);

    $html = $smarty->fetch('navigation.tpl');

    return $html;
}


