<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  27 December 2019  16:01::25  +0800, Kuala Lumpur Malaysia

 Copyright (c) 2015, Inikoo

 Version 3.0
*/
/**
 * @param $data
 * @param $smarty \Smarty
 *
 * @return mixed
 */
function get_group_by_store_server_navigation($data, $smarty) {


    $branch = array(
        array(
            'label'     => '',
            'icon'      => 'home',
            'reference' => ''
        )
    );


    $left_buttons = array();

    $title = _("Mailroom").' ('._('All stores').')';


    $right_buttons                          = array();
    $sections                               = get_sections('mailroom_server');
    $sections['group_by_store']['selected'] = true;


    $_content = array(
        'branch'         => $branch,
        'sections_class' => '',
        'sections'       => $sections,

        'left_buttons'  => $left_buttons,
        'right_buttons' => $right_buttons,
        'title'         => $title,
        'search'        => array(
            'show'        => true,
            'placeholder' => _('Search mailroom')
        )

    );
    $smarty->assign('content', $_content);
    $smarty->assign('_content', $_content);

    return array($_content['search'],$smarty->fetch('top_menu.tpl'),$smarty->fetch('au_header.tpl'));

}


function get_notifications_server_navigation($data, $smarty) {


    $branch = array(
        array(
            'label'     => '',
            'icon'      => 'home',
            'reference' => ''
        )
    );


    $left_buttons = array();

    $title = _("Customers notifications");


    $right_buttons = array();
    $sections      = get_sections('mailroom_server');
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
                'Search mailroom'
            )
        )

    );
    $smarty->assign('content', $_content);
    $smarty->assign('_content', $_content);

    return array($_content['search'],$smarty->fetch('top_menu.tpl'),$smarty->fetch('au_header.tpl'));

}


function get_subject_notifications_navigation($subject, $data, $smarty, $user, $db) {


    $store = $data['store'];

    $left_buttons  = array();
    $right_buttons = array();

    if ($subject == 'marketing') {
        $link    = '';
        $label   = _("Marketing emails");
        $section = 'marketing';
    } elseif ($subject == 'customers') {
        $link    = 'notifications';
        $label   = _("Customers notifications");
        $section = 'customers_notifications';
    } else {
        $link    = 'staff_notifications';
        $label   = _("Staff notifications");
        $section = 'user_notifications';
    }


    if ($user->stores > 1) {

        $left_buttons[] = array(
            'icon'      => 'arrow-up',
            'title'     => _('Mailroom').' ('._('All stores').')',
            'reference' => 'mailroom/all/'.$link
        );


        if ($user->settings('inter_store_nav')) {


            list($prev_key, $next_key) = get_prev_next($store->id, $user->stores);

            $sql = sprintf(
                "SELECT `Store Code` FROM `Store Dimension` WHERE `Store Key`=%d", $prev_key
            );
            if ($result = $db->query($sql)) {
                if ($row = $result->fetch()) {
                    $prev_title = $label.' '.$row['Store Code'];
                } else {
                    $prev_title = '';
                }
            }
            $sql = sprintf(
                "SELECT `Store Code` FROM `Store Dimension` WHERE `Store Key`=%d", $next_key
            );
            if ($result = $db->query($sql)) {
                if ($row = $result->fetch()) {
                    $next_title = $label.' '.$row['Store Code'];
                } else {
                    $next_title = '';
                }
            }

            $left_buttons[] = array(
                'icon'      => 'arrow-left',
                'title'     => $prev_title,
                'reference' => 'mailroom/'.$prev_key.'/'.$link
            );

            $left_buttons[] = array(
                'icon'      => 'arrow-right',
                'title'     => $next_title,
                'reference' => 'mailroom/'.$next_key.'/'.$link
            );

        }


    }


    $sections                       = get_sections('mailroom', $store->id);
    $sections[$section]['selected'] = true;


    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => $label.' <span class="id">'.$store->get('Store Code').'</span>',
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search mailroom')
        )

    );
    $smarty->assign('_content', $_content);


    return array($_content['search'],$smarty->fetch('top_menu.tpl'),$smarty->fetch('au_header.tpl'));

}


function get_user_navigation($data, $smarty, $user, $db) {

    $store = $data['store'];

    $left_buttons  = array();
    $right_buttons = array();

    if ($user->stores > 1) {

        $left_buttons[] = array(
            'icon'      => 'arrow-up',
            'title'     => _('Mailroom').' ('._('All stores').')',
            'reference' => 'mailroom/all/marketing'
        );


        if ($user->settings('inter_store_nav')) {


            list($prev_key, $next_key) = get_prev_next($store->id, $user->stores);

            $sql = sprintf(
                "SELECT `Store Code` FROM `Store Dimension` WHERE `Store Key`=%d", $prev_key
            );
            if ($result = $db->query($sql)) {
                if ($row = $result->fetch()) {
                    $prev_title = _("Customer notifications").' '.$row['Store Code'];
                } else {
                    $prev_title = '';
                }
            }
            $sql = sprintf(
                "SELECT `Store Code` FROM `Store Dimension` WHERE `Store Key`=%d", $next_key
            );
            if ($result = $db->query($sql)) {
                if ($row = $result->fetch()) {
                    $next_title = _("Customer notifications").' '.$row['Store Code'];
                } else {
                    $next_title = '';
                }
            }

            $left_buttons[] = array(
                'icon'      => 'arrow-left',
                'title'     => $prev_title,
                'reference' => 'mailroom/'.$prev_key
            );

            $left_buttons[] = array(
                'icon'      => 'arrow-right',
                'title'     => $next_title,
                'reference' => 'mailroom/'.$next_key
            );

        }


    }


    $sections = get_sections('mailroom', $data['parent_key']);


    $sections['marketing']['selected'] = true;


    $_content = array(

        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => _('Marketing emails').' <span class="id">'.$store->get('Code').'</span>',
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search marketing emails')
        )

    );
    $smarty->assign('_content', $_content);

    return array($_content['search'],$smarty->fetch('top_menu.tpl'),$smarty->fetch('au_header.tpl'));

}


function get_marketing_email_campaign_type_navigation($data, $smarty, $user, $db) {


    $email_campaign_type = $data['_object'];

    if (!$email_campaign_type->id) {
        return;
    }


    $left_buttons  = array();
    $right_buttons = array();


    switch ($data['parent']) {
        case 'store':
            $tab      = 'marketing_emails';
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
    $_order_field_value = $email_campaign_type->get($order);


    $prev_title = '';
    $next_title = '';
    $prev_key   = 0;
    $next_key   = 0;


    $sql = sprintf(
        "select `Email Campaign Type Code` object_name,`Email Campaign Type Key` as object_key from $table   $where $wheref
	                and ($_order_field < %s OR ($_order_field = %s AND `Email Campaign Type Key` < %d))  order by $_order_field desc , `Email Campaign Type Key` desc limit 1",

        prepare_mysql($_order_field_value), prepare_mysql($_order_field_value), $email_campaign_type->id
    );
    if ($result = $db->query($sql)) {
        if ($row = $result->fetch()) {

            //print $sql;


            $prev_key   = $row['object_key'];
            $prev_title = $row['object_name'];

        }
    }


    $sql = sprintf(
        "select `Email Campaign Type Code` object_name,`Email Campaign Type Key` as object_key from $table   $where $wheref
	                and ($_order_field  > %s OR ($_order_field  = %s AND `Email Campaign Type Key` > %d))  order by $_order_field   , `Email Campaign Type Key`  limit 1", prepare_mysql($_order_field_value), prepare_mysql($_order_field_value), $email_campaign_type->id
    );

    if ($result = $db->query($sql)) {
        if ($row = $result->fetch()) {


            $next_key   = $row['object_key'];
            $prev_title = $row['object_name'];

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


    $placeholder = _('Search mailroom');
    $sections    = get_sections('mailroom', $email_campaign_type->data['Email Campaign Type Store Key']);


    $up_button = array(
        'icon'      => 'arrow-up',
        'title'     => _('Marketing emails'),
        'reference' => 'mailroom/'.$email_campaign_type->get('Email Campaign Type Store Key').'/marketing'
    );

    if ($prev_key) {
        $left_buttons[] = array(
            'icon'      => 'arrow-left',
            'title'     => $prev_title,
            'reference' => 'mailroom/'.$email_campaign_type->get('Email Campaign Type Store Key').'/marketing/'.$prev_key
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
            'reference' => 'mailroom/'.$email_campaign_type->get('Email Campaign Type Store Key').'/marketing/'.$next_key
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


    $title = '<span ><i class="fa far fa-'.$email_campaign_type->get('Icon').'"></i>   '.$email_campaign_type->get('Label').'</span>';

    $title .= '<span class="Status_Label padding_left_10 small">'.$email_campaign_type->get('Status Label').'</span>';


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


    return array($_content['search'],$smarty->fetch('top_menu.tpl'),$smarty->fetch('au_header.tpl'));

}

function get_user_notification_email_campaign_type_navigation($data, $smarty, $user, $db) {


    $email_campaign_type = $data['_object'];

    if (!$email_campaign_type->id) {
        return;
    }


    $left_buttons  = array();
    $right_buttons = array();


    switch ($data['parent']) {
        case 'store':
            $tab      = 'user_notifications';
            $_section = 'store';
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
    $_order_field_value = $email_campaign_type->get($order);


    $prev_title = '';
    $next_title = '';
    $prev_key   = 0;
    $next_key   = 0;


    $sql = sprintf(
        "select `Email Campaign Type Code` object_name,`Email Campaign Type Key` as object_key from $table   $where $wheref
	                and ($_order_field < %s OR ($_order_field = %s AND `Email Campaign Type Key` < %d))  order by $_order_field desc , `Email Campaign Type Key` desc limit 1",

        prepare_mysql($_order_field_value), prepare_mysql($_order_field_value), $email_campaign_type->id
    );
    if ($result = $db->query($sql)) {
        if ($row = $result->fetch()) {

            //print $sql;


            $prev_key   = $row['object_key'];
            $prev_title = $row['object_name'];

        }
    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }


    $sql = sprintf(
        "select `Email Campaign Type Code` object_name,`Email Campaign Type Key` as object_key from $table   $where $wheref
	                and ($_order_field  > %s OR ($_order_field  = %s AND `Email Campaign Type Key` > %d))  order by $_order_field   , `Email Campaign Type Key`  limit 1", prepare_mysql($_order_field_value), prepare_mysql($_order_field_value), $email_campaign_type->id
    );

    if ($result = $db->query($sql)) {
        if ($row = $result->fetch()) {


            $next_key   = $row['object_key'];
            $prev_title = $row['object_name'];

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


    $placeholder = _('Search mailroom');
    $sections    = get_sections('mailroom', $email_campaign_type->data['Email Campaign Type Store Key']);


    $up_button = array(
        'icon'      => 'arrow-up',
        'title'     => _("User's notifications"),
        'reference' => 'mailroom/'.$email_campaign_type->get('Email Campaign Type Store Key').'/staff_notifications',
        'metadata'  => '{tab:\'store.notifications\'}'
    );

    if ($prev_key) {
        $left_buttons[] = array(
            'icon'      => 'arrow-left',
            'title'     => $prev_title,
            'reference' => 'mailroom/'.$email_campaign_type->get('Email Campaign Type Store Key').'/staff_notifications/'.$prev_key
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
            'reference' => 'mailroom/'.$email_campaign_type->get('Email Campaign Type Store Key').'/staff_notifications/'.$next_key
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


    $title = '<span ><i class="fa far fa-'.$email_campaign_type->get('Icon').'"></i>   '.$email_campaign_type->get('Label').'</span>';


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


    return array($_content['search'],$smarty->fetch('top_menu.tpl'),$smarty->fetch('au_header.tpl'));

}


function get_mailshot_navigation($data, $smarty, $user, $db, $account) {


    $object        = $data['_object'];
    $left_buttons  = array();
    $right_buttons = array();


    $_section = 'marketing';

    switch ($data['section']) {

        case 'newsletter':
            $tab = 'email_campaigns.newsletters';
            break;
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

            if ($object->get('Email Campaign Type') == 'Newsletter') {
                $prev_title = _("Newsletter").' '.$row['object_name'];
            } else {
                $prev_title = _("Mailshot").' '.$row['object_name'];

            }

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
            if ($object->get('Email Campaign Type') == 'Newsletter') {
                $next_title = _("Newsletter").' '.$row['object_name'];
            } else {
                $next_title = _("Mailshot").' '.$row['object_name'];

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
    }


    if ($object->get('Email Campaign Type') == 'Newsletter') {
        $title     = _('Newsletter').' <span class="id Email_Campaign_Name">'.$object->get('Name').'</span>';
        $up_button = array(
            'icon'      => 'arrow-up',
            'title'     => _("Newsletters").' ('.$data['store']->get('Code').')',
            'reference' => 'mailroom/'.$data['_parent']->get('Store Key').'/marketing/'.$data['_parent']->id
        );

    } else {
        $title     = _('Mailshot').' <span class="id Email_Campaign_Name">'.$object->get('Name').'</span>';
        $up_button = array(
            'icon'      => 'arrow-up',
            'title'     => _("Mailshots").' ('.$data['store']->get('Code').')',
            'reference' => 'mailroom/'.$data['_parent']->get('Store Key').'/marketing/'.$data['_parent']->id
        );

    }


    if ($prev_key) {
        $left_buttons[] = array(
            'icon'      => 'arrow-left',
            'title'     => $prev_title,
            'reference' => 'mailroom/'.$data['_parent']->get('Store Key').'/marketing/'.$data['_parent']->id.'/mailshot/'.$prev_key
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
            'reference' => 'mailroom/'.$data['_parent']->get('Store Key').'/marketing/'.$data['_parent']->id.'/mailshot/'.$next_key
        );

    } else {
        $left_buttons[] = array(
            'icon'  => 'arrow-right disabled',
            'title' => '',
            'url'   => ''
        );

    }


    $sections = get_sections('mailroom', $data['_parent']->get('Store Key'));


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
            'placeholder' => _('Search mailroom')
        )

    );
    $smarty->assign('_content', $_content);


    return array($_content['search'],$smarty->fetch('top_menu.tpl'),$smarty->fetch('au_header.tpl'));

}


function get_email_tracking_navigation($data, $smarty, $user, $db) {


    if (!$data['_parent']->id) {
        return;
    }

    $_section = 'marketing';

    $left_buttons  = array();
    $right_buttons = array();

    if ($data['parent']) {


        switch ($data['parent']) {

            case 'email_campaign_type':
                $tab = 'email_campaign_type.sent_emails';
                break;
            case 'mailshot':
                $tab                 = 'mailshot.sent_emails';
                $email_campaign_type = get_object('email_campaign_type', $data['_parent']->get('Email Campaign Email Template Type Key'));


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

            case 'email_campaign_type':

                if ($data['_parent']->get('Email Campaign Type Scope') == 'Marketing') {
                    $parent_section = 'marketing';
                } elseif ($data['_parent']->get('Email Campaign Type Scope') == 'Customer Notification') {
                    $parent_section = 'notifications';
                } else {
                    $parent_section = 'staff_notifications';

                }

                $placeholder = _('Search mailroom');
                $sections    = get_sections('products', $data['_parent']->get('Store Key'));


                $up_button = array(
                    'icon'      => 'arrow-up',
                    'title'     => $data['_parent']->get('Label'),
                    'reference' => 'mailroom/'.$data['_parent']->get('Store Key').'/'.$parent_section.'/'.$data['_parent']->id
                );

                if ($prev_key) {
                    $left_buttons[] = array(
                        'icon'      => 'arrow-left',
                        'title'     => $prev_title,
                        'reference' => 'mailroom/'.$data['_parent']->get('Store Key').'/'.$parent_section.'/'.$data['_parent']->id.'/tracking/'.$prev_key
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
                        'reference' => 'mailroom/'.$data['_parent']->get('Store Key').'/'.$parent_section.'/'.$data['_parent']->id.'/tracking/'.$next_key
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


            case 'mailshot':

                $email_campaign_type = get_object('email_campaign_type', $data['_parent']->get('Email Campaign Email Template Type Key'));


                $placeholder = _('Search mailroom');
                $sections    = get_sections('mailroom', $email_campaign_type->get('Store Key'));


                if ($email_campaign_type->get('Email Campaign Type Scope') == 'Marketing') {
                    $parent_section = 'marketing';
                } elseif ($email_campaign_type->get('Email Campaign Type Scope') == 'Customer Notification') {
                    $parent_section = 'notifications';
                } else {
                    $parent_section = 'staff_notifications';

                }


                $up_button = array(
                    'icon'      => 'arrow-up',
                    'title'     => $data['_parent']->get('Label'),
                    'reference' => 'mailroom/'.$email_campaign_type->get('Store Key').'/'.$parent_section.'/'.$email_campaign_type->id.'/mailshot/'.$data['_parent']->id
                );

                if ($prev_key) {
                    $left_buttons[] = array(
                        'icon'      => 'arrow-left',
                        'title'     => $prev_title,
                        'reference' => 'mailroom/'.$email_campaign_type->get('Store Key').'/'.$parent_section.'/'.$email_campaign_type->id.'/mailshot/'.$data['_parent']->id.'/tracking/'.$prev_key

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
                        'reference' => 'mailroom/'.$email_campaign_type->get('Store Key').'/'.$parent_section.'/'.$email_campaign_type->id.'/mailshot/'.$data['_parent']->id.'/tracking/'.$next_key
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


    return array($_content['search'],$smarty->fetch('top_menu.tpl'),$smarty->fetch('au_header.tpl'));

}


function get_customer_notification_email_campaign_type_navigation($data, $smarty, $user, $db) {


    $email_campaign_type = $data['_object'];

    if (!$email_campaign_type->id) {
        return;
    }


    $left_buttons  = array();
    $right_buttons = array();

    if ($data['parent']) {

        switch ($data['parent']) {
            case 'store':
                $tab      = 'customer_notifications';
                $_section = 'customer_notifications';
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
        $_order_field_value = $email_campaign_type->get($order);


        $prev_title = '';
        $next_title = '';
        $prev_key   = 0;
        $next_key   = 0;
        $sql        = trim($sql_totals." $wheref");

        if ($result2 = $db->query($sql)) {
            if ($row2 = $result2->fetch() and $row2['num'] > 1) {


                $sql = sprintf(
                    "select `Email Campaign Type Code` object_name,`Email Campaign Type Key` as object_key from $table   $where $wheref
	                and ($_order_field < %s OR ($_order_field = %s AND `Email Campaign Type Key` < %d))  order by $_order_field desc , `Email Campaign Type Key` desc limit 1",

                    prepare_mysql($_order_field_value), prepare_mysql($_order_field_value), $email_campaign_type->id
                );
                if ($result = $db->query($sql)) {
                    if ($row = $result->fetch()) {

                        //print $sql;


                        $prev_key   = $row['object_key'];
                        $prev_title = _("Operational email type").' '.$row['object_name'];

                    }
                }


                $sql = sprintf(
                    "select `Email Campaign Type Code` object_name,`Email Campaign Type Key` as object_key from $table   $where $wheref
	                and ($_order_field  > %s OR ($_order_field  = %s AND `Email Campaign Type Key` > %d))  order by $_order_field   , `Email Campaign Type Key`  limit 1", prepare_mysql($_order_field_value), prepare_mysql($_order_field_value), $email_campaign_type->id
                );

                if ($result = $db->query($sql)) {
                    if ($row = $result->fetch()) {


                        $next_key   = $row['object_key'];
                        $prev_title = _("Operational email type").' '.$row['object_name'];

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


        $placeholder = _('Search mailroom');
        $sections    = get_sections('mailroom', $email_campaign_type->data['Email Campaign Type Store Key']);


        $up_button = array(
            'icon'      => 'arrow-up',
            'title'     => _("Operational email types"),
            'reference' => 'mailroom/'.$email_campaign_type->get('Email Campaign Type Store Key').'/notifications'
        );

        if ($prev_key) {
            $left_buttons[] = array(
                'icon'      => 'arrow-left',
                'title'     => $prev_title,
                'reference' => 'mailroom/'.$email_campaign_type->get('Email Campaign Type Store Key').'/notifications/'.$prev_key
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
                'reference' => 'mailroom/'.$email_campaign_type->get('Email Campaign Type Store Key').'/notifications/'.$next_key
            );

        } else {
            $left_buttons[] = array(
                'icon'  => 'arrow-right disabled',
                'title' => '',
                'url'   => ''
            );

        }


    } else {
        $_section = 'email_campaigns';

    }


    if (isset($sections[$_section])) {
        $sections[$_section]['selected'] = true;
    }


    $title = '<span ><i class="fa far fa-'.$email_campaign_type->get('Icon').'"></i>   '.$email_campaign_type->get('Label').'</span>';

    $title .= '<span class="Status_Label padding_left_10 small">'.$email_campaign_type->get('Status Label').'</span>';


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


    return array($_content['search'],$smarty->fetch('top_menu.tpl'),$smarty->fetch('au_header.tpl'));

}


function get_mailshot_new_navigation($data, $smarty) {

    $left_buttons = array();


    switch ($data['parent']) {
        case 'email_campaign_type':
            $title                             = _('New mailshot');
            $sections                          = get_sections('mailroom', $data['_parent']->get('Store Key'));
            $left_buttons[]                    = array(
                'icon'      => 'arrow-up',
                'title'     => _('Store').': '.$data['store']->get('Code'),
                'reference' => 'mailroom/'.$data['store']->id.'/marketing/'.$data['_parent']->id,
                'parent'    => ''
            );
            $sections['marketing']['selected'] = true;
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
            'placeholder' => _('Search mailroom')
        )

    );
    $smarty->assign('_content', $_content);

    return array($_content['search'],$smarty->fetch('top_menu.tpl'),$smarty->fetch('au_header.tpl'));


}


