<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 29 August 2015 13:51:15 GMT+8 Singapore

 Copyright (c) 2015, Inikoo

 Version 3.0
*/




function get_websites_navigation($data, $smarty, $user, $db, $account) {


    $block_view = $data['section'];


    $sections_class = '';
    $title          = _('Websites');

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
            'placeholder' => _('Search websites (all)')
        )

    );
    $smarty->assign('_content', $_content);

    $html = $smarty->fetch('navigation.tpl');

    return $html;

}


function get_website_navigation($data, $smarty, $user, $db, $account) {


    $website = $data['_object'];

    $block_view = $data['section'];


    $sections_class = '';
    $title          = _('Website').' <span class="id">'.$website->get('Code').'</span>';

    $left_buttons  = array();
    $right_buttons = array();


    if ($user->websites > 1) {


        list($prev_key, $next_key) = get_prev_next(
            $website->get('Website Store Key'), $user->stores
        );
        $sql = sprintf(
            "SELECT `Website Code` FROM `Website Dimension` WHERE `Website Store Key`=%d", $prev_key
        );


        if ($result = $db->query($sql)) {
            if ($row = $result->fetch()) {
                $prev_title = _('Website').' '.$row['Website Code'];
            } else {
                $prev_title = '';
            }
        } else {
            print_r($error_info = $db->errorInfo());
            exit;
        }


        $sql = sprintf(
            "SELECT `Website Code` FROM `Website Dimension` WHERE `Website Store Key`=%d", $next_key
        );
        if ($result = $db->query($sql)) {
            if ($row = $result->fetch()) {
                $next_title = _('Website').' '.$row['Website Code'];
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
            'reference' =>'store/'.$prev_key.'/website'
        );
        $left_buttons[] = array(
            'icon'      => 'arrow-up',
            'title'     => _('Websites'),
            'reference' => 'websites',
            'parent'    => ''
        );

        $left_buttons[] = array(
            'icon'      => 'arrow-right',
            'title'     => $next_title,
            'reference' => 'store/'.$next_key.'/website'
        );
    }


    $sections = get_sections('products', $website->get('Store Key'));
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
            'placeholder' => _('Search website')
        )

    );
    $smarty->assign('_content', $_content);

    $html = $smarty->fetch('navigation.tpl');

    return $html;

}


function get_webpage_navigation($data, $smarty, $user, $db, $account) {


    $object = $data['_object'];




    $block_view = 'website';


    $sections_class = '';


    $left_buttons  = array();
    $right_buttons = array();



    if (preg_match('/online/', $data['request'])) {
        $request_prefix='online/';
        switch ($data['parent']) {

            case 'website':
                $tab      = 'website.online_webpages';
                $_section = 'websites';
                $title    = _('Webpage').' <span class="id Webpage_Code">'.$object->get('Code').'</span>';
                break;

        }


    }
    elseif (preg_match('/offline/', $data['request'])) {

    }
    elseif (preg_match('/in_process/', $data['request'])) {


        $request_prefix='in_process/';
        switch ($data['parent']) {

            case 'website':
                $tab      = 'website.in_process_webpages';
                $_section = 'websites';
                $title    = _('Webpage').' <span class="id Webpage_Code">'.$object->get('Code').'</span>';
                break;

        }


    }
    elseif (preg_match('/ready/', $data['request'])) {


        $request_prefix='ready/';
        switch ($data['parent']) {

            case 'website':
                $tab      = 'website.ready_webpages';
                $_section = 'websites';
                $title    = _('Webpage').' <span class="id Webpage_Code">'.$object->get('Code').'</span>';
                break;

        }


    }
    else {
        $request_prefix='';
        switch ($data['parent']) {

            case 'website':
                $tab      = 'website.webpages';
                $_section = 'websites';
                $title    = _('Webpage').' <span class="id Webpage_Code">'.$object->get('Code').'</span>';
                break;

        }
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


    $order=preg_replace('/Webpage Key/','Page Key',$order);



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



   // print $sql;

    if ($result2 = $db->query($sql)) {
        if ($row2 = $result2->fetch()) {
            if ($row2['num'] > 1) {


                $sql = sprintf(
                    "select `Webpage Code` object_name,`Page Key` as object_key from $table   $where $wheref
	                and ($_order_field < %s OR ($_order_field = %s AND `Page Key` < %d))  order by $_order_field desc , `Page Key` desc limit 1",

                    prepare_mysql($_order_field_value), prepare_mysql($_order_field_value), $object->id
                );


                if ($result = $db->query($sql)) {
                    if ($row = $result->fetch()) {
                        $prev_key   = $row['object_key'];
                        $prev_title = _("Web page").' '.$row['object_name'];
                    }
                } else {
                    print $sql;
                    print_r($error_info = $db->errorInfo());
                    exit;
                }


                $sql = sprintf(
                    "select `Webpage Code` object_name,`Page Key` as object_key from $table   $where $wheref
	                and ($_order_field  > %s OR ($_order_field  = %s AND `Page Key` > %d))  order by $_order_field   , `Page Key`  limit 1", prepare_mysql($_order_field_value),
                    prepare_mysql($_order_field_value), $object->id
                );


                if ($result = $db->query($sql)) {
                    if ($row = $result->fetch()) {
                        $next_key   = $row['object_key'];
                        $next_title = _("Web page").' '.$row['object_name'];
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
                    case 'website':


                        $up_button = array(
                            'icon'      => 'arrow-up',
                            'title'     => _("Website").' ('.$data['_parent']->get('Code').')',
                            'reference' => 'store/'.$object->get('Webpage Store Key').'/website'
                        );

                        if ($prev_key) {
                            $left_buttons[] = array(
                                'icon'      => 'arrow-left',
                                'title'     => $prev_title,
                            'reference' => 'website/'.$data['parent_key'].'/'.$request_prefix.'webpage/'.$prev_key


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
                                'reference' => 'website/'.$data['parent_key'].'/'.$request_prefix.'webpage/'.$next_key
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
    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }


    $sections = get_sections('products', $object->get('Webpage Store Key'));
    if (isset($sections[$block_view])) {
        $sections[$block_view]['selected'] = true;
    }


    $_content = array(
        'sections_class' => $sections_class,
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => $title,
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search website')
        )

    );
    $smarty->assign('_content', $_content);

    $html = $smarty->fetch('navigation.tpl');

    return $html;

}


function get_page_version_navigation($data, $smarty, $user, $db, $account) {


    $object = $data['_object'];


    $block_view = $data['section'];


    $sections_class = '';


    $left_buttons  = array();
    $right_buttons = array();


    if ($data['parent']) {

        switch ($data['parent']) {

            case 'page':
                $tab      = 'page.versions';
                $_section = 'websites';
                $title    = _('Webpage').' <span class="id Webpage_Code">'.$data['_parent']->get('Code').'</span> '._('Version').' <span class="id Webpage_Code">'.$object->get('Code').'</span>';
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
                        "select `Webpage Code` object_name,N.`Webpage Key` as object_key from $table   $where $wheref
	                and ($_order_field < %s OR ($_order_field = %s AND N.`Webpage Key` < %d))  order by $_order_field desc , N.`Webpage Key` desc limit 1",

                        prepare_mysql($_order_field_value), prepare_mysql($_order_field_value), $object->id
                    );


                    if ($result = $db->query($sql)) {
                        if ($row = $result->fetch()) {
                            $prev_key   = $row['object_key'];
                            $prev_title = _("Product").' '.$row['object_name'].' ('.$row['object_key'].')';
                        }
                    } else {
                        print_r($error_info = $db->errorInfo());
                        exit;
                    }


                    $sql = sprintf(
                        "select `Webpage Code` object_name,N.`Webpage Key` as object_key from $table   $where $wheref
	                and ($_order_field  > %s OR ($_order_field  = %s AND N.`Webpage Key` > %d))  order by $_order_field   , N.`Webpage Key`  limit 1", prepare_mysql($_order_field_value),
                        prepare_mysql($_order_field_value), $object->id
                    );


                    if ($result = $db->query($sql)) {
                        if ($row = $result->fetch()) {
                            $next_key   = $row['object_key'];
                            $next_title = _("Product").' '.$row['object_name'].' ('.$row['object_key'].')';
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
                        case 'website':


                            $up_button = array(
                                'icon'      => 'arrow-up',
                                'title'     => _(
                                        "Website"
                                    ).' ('.$data['_parent']->get(
                                        'Code'
                                    ).')',
                                'reference' => 'website/'.$object->get(
                                        'Page Site Key'
                                    )
                            );

                            if ($prev_key) {
                                $left_buttons[] = array(
                                    'icon'      => 'arrow-left',
                                    'title'     => $prev_title,
                                    'reference' => 'website/'.$data['parent_key'].'/page/'.$prev_key
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
                                    'reference' => 'website/'.$data['parent_key'].'/page/'.$next_key
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
        } else {
            print_r($error_info = $db->errorInfo());
            exit;
        }


    } else {
        $_section = 'products';

    }


    $sections = get_sections('websites', $object->get('Page Site Key'));
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
            'placeholder' => _('Search website')
        )

    );
    $smarty->assign('_content', $_content);

    $html = $smarty->fetch('navigation.tpl');

    return $html;

}


function get_user_navigation($data, $smarty, $user, $db, $account) {


    global $smarty;

    $object        = $data['_object'];
    $left_buttons  = array();
    $right_buttons = array();

    if ($data['parent']) {

        switch ($data['parent']) {
            case 'website':
                $tab      = 'website.users';
                $_section = '';
                break;
            case 'customer':
                $tab      = 'customer.users';
                $_section = '';
                break;
            case 'page':
                $tab      = 'page.users';
                $_section = '';
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
                        "select `User Handle` object_name,U.`User Key` as object_key from $table   $where $wheref
	                and ($_order_field < %s OR ($_order_field = %s AND U.`User Key` < %d))  order by $_order_field desc , U.`User Key` desc limit 1",

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
                        "select `User Handle` object_name,U.`User Key` as object_key from $table   $where $wheref
	                and ($_order_field  > %s OR ($_order_field  = %s AND U.`User Key` > %d))  order by $_order_field   , U.`User Key`  limit 1", prepare_mysql($_order_field_value),
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

            }
        } else {
            print_r($error_info = $db->errorInfo());
            exit;
        }


        if ($data['parent'] == 'website') {

            $up_button = array(
                'icon'      => 'arrow-up',
                'title'     => _("Website"),
                'reference' => 'website/'.$data['parent_key']
            );

            if ($prev_key) {
                $left_buttons[] = array(
                    'icon'      => 'arrow-left',
                    'title'     => $prev_title,
                    'reference' => 'website/'.$data['parent_key'].'/user/'.$prev_key
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
                    'reference' => 'website/'.$data['parent_key'].'/user/'.$next_key
                );

            } else {
                $left_buttons[] = array(
                    'icon'  => 'arrow-right disabled',
                    'title' => '',
                    'url'   => ''
                );

            }


            $sections = get_sections('websites', $data['parent_key']);


        } elseif ($data['parent'] == 'page') {

            $page = new Page($data['parent_key']);

            $website = new Site($page->get('Page Site Key'));

            $up_button = array(
                'icon'      => 'arrow-up',
                'title'     => _("Page"),
                'reference' => 'website/'.$page->get(
                        'Page Site Key'
                    ).'/page/'.$data['parent_key']
            );

            if ($prev_key) {
                $left_buttons[] = array(
                    'icon'      => 'arrow-left',
                    'title'     => $prev_title,
                    'reference' => 'page/'.$data['parent_key'].'/user/'.$prev_key
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
                    'reference' => 'page/'.$data['parent_key'].'/user/'.$next_key
                );

            } else {
                $left_buttons[] = array(
                    'icon'  => 'arrow-right disabled',
                    'title' => '',
                    'url'   => ''
                );

            }


            $sections = get_sections('websites', '');


        }
    } else {


    }


    if (isset($sections[$_section])) {
        $sections[$_section]['selected'] = true;
    }


    $title = _('User').' <span class="id">'.$object->get('User Handle').' ('.$object->get_formatted_id().')</span>';


    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => $title,
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search websites')
        )

    );
    $smarty->assign('_content', $_content);


    $html = $smarty->fetch('navigation.tpl');

    return $html;


}


function get_node_navigation($data, $smarty, $user, $db, $account) {


    $object = $data['_object'];


    $block_view = $data['section'];


    $sections_class = '';

    $left_buttons  = array();
    $right_buttons = array();


    if ($data['parent']) {

        switch ($data['parent']) {
            case 'website':
                $tab      = 'website.nodes';
                $_section = 'websites';
                break;
            case 'node':
                $tab      = 'website.node.nodes';
                $_section = 'websites';
                break;

        }


        if (isset($_SESSION['table_state'][$tab])) {
            $number_results           = $_SESSION['table_state'][$tab]['nr'];
            $start_from               = 0;
            $order                    = $_SESSION['table_state'][$tab]['o'];
            $order_direction          = ($_SESSION['table_state'][$tab]['od'] == 1 ? 'desc' : '');
            $f_value                  = $_SESSION['table_state'][$tab]['f_value'];
            $parameters               = $_SESSION['table_state'][$tab];
            $parameters['parent']     = $data['parent'];
            $parameters['parent_key'] = $data['parent_key'];
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
                        "select `Webpage Code` object_name, N.`Website Node Key` as object_key from $table   $where $wheref
	                and ($_order_field < %s OR ($_order_field = %s AND N.`Website Node Key` < %d))  order by $_order_field desc ,N.`Website Node Key` desc limit 1",

                        prepare_mysql($_order_field_value), prepare_mysql($_order_field_value), $object->id
                    );


                    if ($result = $db->query($sql)) {
                        if ($row = $result->fetch()) {
                            $prev_key   = $row['object_key'];
                            $prev_title = _("Node").' '.$row['object_name'];
                        }
                    } else {
                        print_r($error_info = $db->errorInfo());
                        exit;
                    }


                    $sql = sprintf(
                        "select `Webpage Code` object_name,N.`Website Node Key` as object_key from $table   $where $wheref
	                and ($_order_field  > %s OR ($_order_field  = %s AND N.`Website Node Key` > %d))  order by $_order_field   , N.`Website Node Key`  limit 1", prepare_mysql($_order_field_value),
                        prepare_mysql($_order_field_value), $object->id
                    );


                    if ($result = $db->query($sql)) {
                        if ($row = $result->fetch()) {
                            $next_key   = $row['object_key'];
                            $next_title = _("Node").' '.$row['object_name'];
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


        switch ($data['parent']) {
            case 'website':


                $up_button = array(
                    'icon'      => 'arrow-up',
                    'title'     => _("Website").' ('.$data['_parent']->get('Code').')',
                    'reference' => 'website/'.$object->get(
                            'Website Key'
                        )
                );

                if ($prev_key) {
                    $left_buttons[] = array(
                        'icon'      => 'arrow-left',
                        'title'     => $prev_title,
                        'reference' => 'website/'.$object->get('Website Key').'/node/'.$prev_key
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
                        'reference' => 'website/'.$object->get('Website Key').'/node/'.$next_key
                    );

                } else {
                    $left_buttons[] = array(
                        'icon'  => 'arrow-right disabled',
                        'title' => '',
                        'url'   => ''
                    );

                }

                break;


            case 'node':


                if ($object->get('Website Node Key') == $object->get(
                        'Website Node Parent Key'
                    )
                ) {
                    $up_button = array(
                        'icon'      => 'arrow-up',
                        'title'     => $data['website']->get(
                                'Name'
                            ).' ('.$data['website']->get('Code').')',
                        'reference' => 'website/'.$data['website']->id
                    );

                } else {

                    $up_button = array(
                        'icon'      => 'arrow-up',
                        'title'     => $data['_parent']->get(
                                'Name'
                            ).' ('.$data['_parent']->get('Code').')',
                        'reference' => 'node/'.$data['_parent']->get('Website Node Parent Key').'/node/'.$data['_parent']->id
                    );

                }
                if ($prev_key) {
                    $left_buttons[] = array(
                        'icon'      => 'arrow-left',
                        'title'     => $prev_title,
                        'reference' => 'node/'.$object->get(
                                'Website Node Parent Key'
                            ).'/node/'.$prev_key
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
                        'reference' => 'node/'.$object->get(
                                'Website Node Parent Key'
                            ).'/node/'.$next_key
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


    } else {
        $_section = 'products';

    }


    $title = '<span class="id Website_Node_Name">'.$object->get('Name').'</span>';


    $sections = get_sections('websites', $object->get('Website Key'));
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
            'placeholder' => _('Search website')
        )

    );


    $smarty->assign('_content', $_content);

    $html = $smarty->fetch('navigation.tpl');

    return $html;

}


function get_webpages_navigation($data, $smarty, $user, $db, $account) {


    $website = $data['website'];


    $sections_class = '';
    $title          = _('Web pages').' <span class="id">'.$website->get('Code').'</span>';

    $left_buttons  = array();
    $right_buttons = array();


    if ($user->websites > 1) {


        list($prev_key, $next_key) = get_prev_next(
            $website->id, $user->websites
        );
        $sql = sprintf(
            "SELECT `Website Code` FROM `Website Dimension` WHERE `Website Key`=%d", $prev_key
        );


        if ($result = $db->query($sql)) {
            if ($row = $result->fetch()) {
                $prev_title = _('Website').' '.$row['Website Code'];
            } else {
                $prev_title = '';
            }
        } else {
            print_r($error_info = $db->errorInfo());
            exit;
        }


        $sql = sprintf(
            "SELECT `Website Code` FROM `Website Dimension` WHERE `Website Key`=%d", $next_key
        );
        if ($result = $db->query($sql)) {
            if ($row = $result->fetch()) {
                $next_title = _('Website').' '.$row['Website Code'];
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
            'reference' => 'website/'.$prev_key
        );
        $left_buttons[] = array(
            'icon'      => 'arrow-up',
            'title'     => _('Websites'),
            'reference' => 'websites',
            'parent'    => ''
        );

        $left_buttons[] = array(
            'icon'      => 'arrow-right',
            'title'     => $next_title,
            'reference' => 'website/'.$next_key
        );
    }


    $sections = get_sections('websites', $website->id);
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
            'placeholder' => _('Search website')
        )

    );
    $smarty->assign('_content', $_content);

    $html = $smarty->fetch('navigation.tpl');

    return $html;

}


function get_webpage_type_navigation($data, $smarty, $user, $db, $account) {


    $object = $data['_object'];


    //  $block_view = $data['section'];


    $sections_class = '';


    $left_buttons  = array();
    $right_buttons = array();


    if ($data['parent']) {

        switch ($data['parent']) {

            case 'website':
                $tab      = 'website.webpage.types';
                $_section = 'websites';
                $title    = _('Webpage type').' <span class="id">'.$object->get('Label').'</span>';
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

        // print $order;

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
                        "select `Webpage Type Code` object_name,`Webpage Type Key` as object_key from $table   $where $wheref
	                and ($_order_field < %s OR ($_order_field = %s AND `Webpage Type Key` < %d))  order by $_order_field desc , `Webpage Type Key` desc limit 1",

                        prepare_mysql($_order_field_value), prepare_mysql($_order_field_value), $object->id
                    );

                    //  print $sql;

                    if ($result = $db->query($sql)) {
                        if ($row = $result->fetch()) {
                            $prev_key   = $row['object_key'];
                            $prev_title = _("Webpage type").' '.$row['object_name'].' ('.$row['object_key'].')';
                        }
                    } else {
                        print_r($error_info = $db->errorInfo());
                        exit;
                    }


                    $sql = sprintf(
                        "select `Webpage Type Code` object_name,`Webpage Type Key` as object_key from $table   $where $wheref
	                and ($_order_field  > %s OR ($_order_field  = %s AND `Webpage Type Key` > %d))  order by $_order_field   , `Webpage Type Key`  limit 1", prepare_mysql($_order_field_value),
                        prepare_mysql($_order_field_value), $object->id
                    );


                    if ($result = $db->query($sql)) {
                        if ($row = $result->fetch()) {
                            $next_key   = $row['object_key'];
                            $next_title = _("Webpage type").' '.$row['object_name'].' ('.$row['object_key'].')';
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
                        case 'website':


                            $up_button = array(
                                'icon'      => 'arrow-up',
                                'title'     => _("Website").' ('.$data['_parent']->get('Code').')',
                                'reference' => 'webpages/'.$object->get('Website Key')
                            );

                            if ($prev_key) {
                                $left_buttons[] = array(
                                    'icon'      => 'arrow-left',
                                    'title'     => $prev_title,
                                    'reference' => 'webpages/'.$data['parent_key'].'/type/'.$prev_key
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
                                    'reference' => 'webpages/'.$data['parent_key'].'/type/'.$next_key
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
        } else {
            print_r($error_info = $db->errorInfo());
            exit;
        }


    }


    $sections = get_sections('websites', $object->get('Website Key'));
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
            'placeholder' => _('Search website')
        )

    );
    $smarty->assign('_content', $_content);

    $html = $smarty->fetch('navigation.tpl');

    return $html;

}


function get_no_website_navigation($data, $smarty, $user, $db, $account) {

    $sections_class = '';
    $title          = sprintf('%s website', '<span class="id">'.$data['store']->get('Code').'</span>');

    $left_buttons  = array();
    $right_buttons = array();


    $sections                        = get_sections('products', $data['store']->id);
    $sections['website']['selected'] = true;


    $_content = array(
        'sections_class' => $sections_class,
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => $title,
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search products').' '.$data['store']->get('Code')
        )

    );
    $smarty->assign('_content', $_content);

    $html = $smarty->fetch('navigation.tpl');

    return $html;

}


function get_website_new_navigation($data, $smarty, $user, $db, $account) {


    $website = $data['_object'];

    $block_view = $data['section'];


    $sections_class = '';
    $title          = _('New website');

    $left_buttons  = array();
    $right_buttons = array();


    if ($user->websites > 1) {


        list($prev_key, $next_key) = get_prev_next(
            $website->id, $user->websites
        );
        $sql = sprintf(
            "SELECT `Website Code` FROM `Website Dimension` WHERE `Website Key`=%d", $prev_key
        );


        if ($result = $db->query($sql)) {
            if ($row = $result->fetch()) {
                $prev_title = _('Website').' '.$row['Website Code'];
            } else {
                $prev_title = '';
            }
        } else {
            print_r($error_info = $db->errorInfo());
            exit;
        }


        $sql = sprintf(
            "SELECT `Website Code` FROM `Website Dimension` WHERE `Website Key`=%d", $next_key
        );
        if ($result = $db->query($sql)) {
            if ($row = $result->fetch()) {
                $next_title = _('Website').' '.$row['Website Code'];
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
            'reference' => 'website/'.$prev_key
        );
        $left_buttons[] = array(
            'icon'      => 'arrow-up',
            'title'     => _('Websites'),
            'reference' => 'websites',
            'parent'    => ''
        );

        $left_buttons[] = array(
            'icon'      => 'arrow-right',
            'title'     => $next_title,
            'reference' => 'website/'.$next_key
        );
    }


    $sections = get_sections('products', $website->get('Store Key'));
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
            'placeholder' => _('Search products').' '.$data['store']->get('Code')
        )

    );
    $smarty->assign('_content', $_content);

    $html = $smarty->fetch('navigation.tpl');

    return $html;

}

function get_deleted_webpage_navigation($data, $smarty, $user, $db, $account) {




    $sections_class = '';
    $title          = '<span class="error">'._('Deleted webpage').': '.$data['_object']->get('Page Title').'</span>';

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
            'placeholder' => _('Search websites (all)')
        )

    );
    $smarty->assign('_content', $_content);

    $html = $smarty->fetch('navigation.tpl');

    return $html;

}


?>
