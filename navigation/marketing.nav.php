<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 1 October 2015 at 16:42:35 BST, Sheffield UK

 Copyright (c) 2015, Inikoo

 Version 3.0
*/


function get_new_deal_navigation($data, $smarty, $user, $db) {


    $left_buttons  = array();
    $right_buttons = array();


    $sections      = get_sections('products', $data['store']->id);


    $sections['offers']['selected'] = true;

    if ($data['parent'] == 'campaign') {

        switch ($data['_parent']->get('Code')) {
            case 'VO':
                $title = _('New voucher');

                break;
            default:
                $title = sprintf(_('New offer for campaign %s'), '<span class="id">'.$data['_parent']->get('Name')).'</span>';

        }


    } else {
        $title = _('New offer');
    }

    $_content = array(

        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => $title,
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search offers')
        )

    );
    $smarty->assign('_content', $_content);

    $html = $smarty->fetch('navigation.tpl');

    return $html;

}


function get_new_deal_component_navigation($data, $smarty, $user, $db) {


    $left_buttons  = array();
    $right_buttons = array();
    $sections      = get_sections('products', $data['parent_key']);


    $sections['offers']['selected'] = true;

    if ($data['parent'] == 'campaign') {

        switch ($data['_parent']->get('Code')) {
            case 'VO':
                $title = _('New voucher');

                break;
            default:
                $title = sprintf(_('New offer for campaign %s'), '<span class="id">'.$data['_parent']->get('Name')).'</span>';

        }


    } elseif ($data['parent'] == 'category') {


        $title = sprintf(_('New offer for category %s'), '<span class="id">'.$data['_parent']->get('Code')).'</span>';


    } else {
        $title = _('New offer');
    }

    $_content = array(

        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => $title,
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search offers')
        )

    );
    $smarty->assign('_content', $_content);

    $html = $smarty->fetch('navigation.tpl');

    return $html;

}


function get_new_campaign_navigation($data, $smarty, $user, $db) {


    $left_buttons  = array();
    $right_buttons = array();
    $sections      = get_sections('products', $data['parent_key']);


    $sections['marketing']['selected'] = true;


    $_content = array(

        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => _('New Campaign'),
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search offers')
        )

    );
    $smarty->assign('_content', $_content);


    $html = $smarty->fetch('navigation.tpl');

    return $html;

}

function get_offers_navigation($data, $smarty, $user, $db) {


    $left_buttons  = array();
    $right_buttons = array();
    $sections      = get_sections('products', $data['parent_key']);

    if (isset($sections[$data['section']])) {
        $sections[$data['section']]['selected'] = true;
    }


    $_content = array(

        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => _('Campaigns & offers').' <span class="id">'.$data['_parent']->get('Code').'</span>',
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search offers')
        )

    );
    $smarty->assign('_content', $_content);

    $html = $smarty->fetch('navigation.tpl');

    return $html;

}








function get_campaign_navigation($data, $smarty, $user, $db) {


    $object = $data['_object'];

    if (!$object->id) {
        return;
    }

    // $block_view = $data['section'];


    $left_buttons  = array();
    $right_buttons = array();

    if ($data['parent']) {

        switch ($data['parent']) {
            case 'store':
                $tab      = 'campaigns';
                $_section = 'offers';
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


        $sql = sprintf(
            "select `Deal Campaign Name` object_name,C.`Deal Campaign Code` as object_key from $table   $where $wheref
	                and ($_order_field < %s OR ($_order_field = %s AND C.`Deal Campaign Key` < %d))  order by $_order_field desc , C.`Deal Campaign Key` desc limit 1",

            prepare_mysql($_order_field_value), prepare_mysql($_order_field_value), $object->id
        );
        if ($result = $db->query($sql)) {
            if ($row = $result->fetch()) {
                $prev_key   = $row['object_key'];
                $prev_title = _("Offer category").' '.$row['object_name'];

            }
        } else {
            print_r($error_info = $db->errorInfo());
            exit;
        }


        $sql = sprintf(
            "select `Deal Campaign Name` object_name,C.`Deal Campaign Code` as object_key from $table   $where $wheref
	                and ($_order_field  > %s OR ($_order_field  = %s AND C.`Deal Campaign Key` > %d))  order by $_order_field   , C.`Deal Campaign Key`  limit 1", prepare_mysql($_order_field_value),
            prepare_mysql($_order_field_value), $object->id
        );

        if ($result = $db->query($sql)) {
            if ($row = $result->fetch()) {
                $next_key   = $row['object_key'];
                $next_title = _("Offer category").' '.$row['object_name'];

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


        if ($data['parent'] == 'store') {

            $up_button = array(
                'icon'      => 'arrow-up',
                'title'     => _(
                        "Offer's categories"
                    ).' '.$data['store']->get('Code'),
                'reference' => 'offers/'.$data['store']->id,
                'metadata'  => '{tab:\'campaigns\'}'
            );

            if ($prev_key) {
                $left_buttons[] = array(
                    'icon'      => 'arrow-left',
                    'title'     => $prev_title,
                    'reference' => 'offers/'.$data['parent_key'].'/'.strtolower($prev_key)
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
                    'reference' => 'offers/'.$data['parent_key'].'/'.strtolower($next_key)

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
        $_section = 'marketing';

    }

    $sections = get_sections('products', $data['store']->id);

    if (isset($sections[$_section])) {


        $sections[$_section]['selected'] = true;
    }


    $title = '<span class="id Deal_Campaign_Name" title="'._('Offer category').'">'.$object->get('Name').'</span> <span class="padding_left_5">'.$object->get('Icon').'</span>';


    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => $title,
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search offers')
        )

    );
    $smarty->assign('_content', $_content);


    $html = $smarty->fetch('navigation.tpl');

    return $html;

}


function get_deal_navigation($data, $smarty, $user, $db) {


    $_section = 'offers';

    $object = $data['_object'];

    if (!$object->id) {
        return;
    }


    $left_buttons  = array();
    $right_buttons = array();


    if ($data['parent']) {

        switch ($data['parent']) {
            case 'store':
                $tab      = 'deals';
                $_section = 'offers';
                break;
            case 'campaign':
                $tab      = 'deals';
                $_section = 'offers';
                break;
            case 'category':
                $tab      = 'category.deals';
                $_section = 'categories';
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

        //print_r($parameters);

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


                if ($order_direction == 'desc') {
                    $sql = sprintf(
                        "select `Deal Name` object_name,D.`Deal Key` as object_key from $table   $where $wheref
	                and ($_order_field > %s OR ($_order_field = %s AND D.`Deal Key` < %d))  order by $_order_field  , D.`Deal Key` desc  limit 1",

                        prepare_mysql($_order_field_value), prepare_mysql($_order_field_value), $object->id

                    );

                } else {
                    $sql = sprintf(
                        "select `Deal Name` object_name,D.`Deal Key` as object_key from $table   $where $wheref
	                and ($_order_field < %s OR ($_order_field = %s AND D.`Deal Key` < %d))  order by $_order_field desc , D.`Deal Key` desc limit 1",

                        prepare_mysql($_order_field_value), prepare_mysql($_order_field_value), $object->id

                    );

                }

                //        print $order_direction;

                //   print $sql;


                if ($result = $db->query($sql)) {
                    if ($row = $result->fetch()) {
                        $prev_key   = $row['object_key'];
                        $prev_title = _("Offer").' '.$row['object_name'].' ('.$row['object_key'].')';

                    }
                } else {
                    print_r($error_info = $db->errorInfo());
                    print 'X1:'.$sql;
                    exit;
                }


                if ($order_direction == 'desc') {
                    $sql = sprintf(
                        "select `Deal Name` object_name,D.`Deal Key` as object_key from $table   $where $wheref
	                and ($_order_field  %s %s OR ($_order_field  = %s AND D.`Deal Key` > %d))  order by $_order_field %s  , D.`Deal Key`  limit 1",
                        ($order_direction == 'desc' ? '<' : '>'),
                        prepare_mysql($_order_field_value),
                        prepare_mysql($_order_field_value), $object->id,
                        $order_direction
                    );

                } else {
                    $sql = sprintf(
                        "select `Deal Name` object_name,D.`Deal Key` as object_key from $table   $where $wheref
	                and ($_order_field  %s %s OR ($_order_field  = %s AND D.`Deal Key` > %d))  order by $_order_field   , D.`Deal Key`   limit 1",
                        ($order_direction == 'desc' ? '<' : '>'),
                        prepare_mysql($_order_field_value),
                        prepare_mysql($_order_field_value), $object->id

                    );

                }


                //print $order_direction;
                //print $sql;

                if ($result = $db->query($sql)) {
                    if ($row = $result->fetch()) {
                        $next_key   = $row['object_key'];
                        $next_title = _("Offer").' '.$row['object_name'].' ('.$row['object_key'].')';

                    }
                } else {
                    print_r($error_info = $db->errorInfo());
                    print 'X2:'.$sql;
                    exit;
                }
                /*

                                if ($order_direction == 'desc') {
                                    $_tmp1      = $prev_key;
                                    $_tmp2      = $prev_title;
                                    $prev_key   = $next_key;
                                    $prev_title = $next_title;
                                    $next_key   = $_tmp1;
                                    $next_title = $_tmp2;
                                }
                */

            }
        } else {
            print_r($error_info = $db->errorInfo());
            exit;
        }


        if ($data['parent'] == 'store') {

            $up_button = array(
                'icon'      => 'arrow-up',
                'title'     => _("Marketing").' '.$data['store']->get('Code'),
                'reference' => 'offers/'.$data['store']->id.'&tab=deals'
            );

            if ($prev_key) {
                $left_buttons[] = array(
                    'icon'      => 'arrow-left',
                    'title'     => $prev_title,
                    'reference' => 'deals/'.$data['parent_key'].'/'.$prev_key
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
                    'reference' => 'deals/'.$data['parent_key'].'/'.$next_key
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
                'reference' => 'offers/'.$data['store']->id.'/'.strtolower($data['_parent']->get('Code'))
            );

            if ($prev_key) {
                $left_buttons[] = array(
                    'icon'      => 'arrow-left',
                    'title'     => $prev_title,
                    'reference' => 'offers/'.$data['store']->id.'/'.strtolower($data['_parent']->get('Code')).'/'.$prev_key

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
                    'reference' => 'offers/'.$data['store']->id.'/'.strtolower($data['_parent']->get('Code')).'/'.$next_key
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
                'title'     => _("Category").' '.$data['_parent']->get('Code'),
                'reference' => 'products/'.$data['store']->id.'/category/'.$data['parent_key']
            );

            if ($prev_key) {
                $left_buttons[] = array(
                    'icon'      => 'arrow-left',
                    'title'     => $prev_title,
                    'reference' => 'products/'.$data['store']->id.'/category/'.$data['parent_key'].'/deal/'.$prev_key
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
                    'reference' => 'products/'.$data['store']->id.'/category/'.$data['parent_key'].'/deal/'.$next_key
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
        $_section = 'offers';

    }

    $sections = get_sections('products', $data['store']->id);


    if (isset($sections[$_section])) {
        $sections[$_section]['selected'] = true;
    }


    $title = '<span class="id"><span class="Deal_Name">'.$object->get('Name').'</span> </span>';


    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => $title,
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search offers')
        )

    );
    $smarty->assign('_content', $_content);


    $html = $smarty->fetch('navigation.tpl');

    return $html;

}



function get_deal_component_navigation($data, $smarty, $user, $db) {


    $_section = 'offers';

    $object = $data['_object'];

    if (!$object->id) {
        return;
    }


    $left_buttons  = array();
    $right_buttons = array();


    if ($data['parent']) {

        switch ($data['parent']) {
            case 'store':
                $tab      = 'deal_components';
                $_section = 'offers';
                break;
            case 'campaign':

                switch ($data['_parent']->get('Code')) {
                    case 'OR':
                        $tab = 'campaign_order_recursion.components';

                        break;
                    default:
                        $tab = 'deal_components';
                }


                $_section = 'offers';


                break;
            case 'category':
                $tab      = 'category.deal_components';
                $_section = 'categories';
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

        //print_r($parameters);

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
        // $sql        = trim($sql_totals." $wheref");


        if ($order_direction == 'desc') {
            $sql = sprintf(
                "select `Deal Name` object_name,DCD.`Deal Component Key` as object_key from $table   $where $wheref
	                and ($_order_field > %s OR ($_order_field = %s AND DCD.`Deal Component Key` < %d))  order by $_order_field  , DCD.`Deal Component Key` desc  limit 1",

                prepare_mysql($_order_field_value), prepare_mysql($_order_field_value), $object->id

            );

        } else {
            $sql = sprintf(
                "select `Deal Name` object_name,DCD.`Deal Component Key` as object_key from $table   $where $wheref
	                and ($_order_field < %s OR ($_order_field = %s AND DCD.`Deal Component Key` < %d))  order by $_order_field desc , DCD.`Deal Component Key` desc limit 1",

                prepare_mysql($_order_field_value), prepare_mysql($_order_field_value), $object->id

            );

        }

        //        print $order_direction;


        if ($result = $db->query($sql)) {
            if ($row = $result->fetch()) {
                $prev_key   = $row['object_key'];
                $prev_title = _("Offer").' '.$row['object_name'];

            }
        } else {
            print_r($error_info = $db->errorInfo());
            print 'X1:'.$sql;
            exit;
        }


        if ($order_direction == 'desc') {
            $sql = sprintf(
                "select `Deal Name` object_name,DCD.`Deal Component Key` as object_key from $table   $where $wheref
	                and ($_order_field  %s %s OR ($_order_field  = %s AND DCD.`Deal Component Key` > %d))  order by $_order_field %s  , DCD.`Deal Component Key`  limit 1",
                ($order_direction == 'desc' ? '<' : '>'),
                prepare_mysql($_order_field_value),
                prepare_mysql($_order_field_value), $object->id,
                $order_direction
            );

        } else {
            $sql = sprintf(
                "select `Deal Name` object_name,DCD.`Deal Component Key` as object_key from $table   $where $wheref
	                and ($_order_field  %s %s OR ($_order_field  = %s AND DCD.`Deal Component Key` > %d))  order by $_order_field   , DCD.`Deal Component Key`   limit 1",
                ($order_direction == 'desc' ? '<' : '>'),
                prepare_mysql($_order_field_value),
                prepare_mysql($_order_field_value), $object->id

            );

        }


        //print $order_direction;
        //print $sql;

        if ($result = $db->query($sql)) {
            if ($row = $result->fetch()) {
                $next_key   = $row['object_key'];
                $next_title = _("Offer").' '.$row['object_name'];

            }
        }


        if ($data['parent'] == 'store') {

            $up_button = array(
                'icon'      => 'arrow-up',
                'title'     => _("Marketing").' '.$data['store']->get('Code'),
                'reference' => 'marketing/'.$data['store']->id.'&tab=deals'
            );

            if ($prev_key) {
                $left_buttons[] = array(
                    'icon'      => 'arrow-left',
                    'title'     => $prev_title,
                    'reference' => 'deals/'.$data['parent_key'].'/'.$prev_key
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
                    'reference' => 'deals/'.$data['parent_key'].'/'.$next_key
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
                'title'     => $data['_parent']->get('Name'),
                'reference' => 'offers/'.$data['store']->id.'/'.strtolower($data['_parent']->get('Code'))
            );

            if($data['_parent']->get('Code')=='OR'){
                if ($prev_key) {
                    $left_buttons[] = array(
                        'icon'      => 'arrow-left',
                        'title'     => $prev_title,
                        'reference' => 'offers/'.$data['store']->id.'/'.strtolower($data['_parent']->get('Code')).'/'.$prev_key
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
                        'reference' => 'offers/'.$data['store']->id.'/'.strtolower($data['_parent']->get('Code')).'/'.$next_key
                    );


                } else {
                    $left_buttons[] = array(
                        'icon'  => 'arrow-right disabled',
                        'title' => '',
                        'url'   => ''
                    );

                }
            }else{
                if ($prev_key) {
                    $left_buttons[] = array(
                        'icon'      => 'arrow-left',
                        'title'     => $prev_title,
                        'reference' => 'offers/'.$data['store']->id.'/'.strtolower($data['_parent']->get('Code')).'/deal_component/'.$prev_key
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
                        'reference' => 'offers/'.$data['store']->id.'/'.strtolower($data['_parent']->get('Code')).'/deal_component/'.$next_key
                    );


                } else {
                    $left_buttons[] = array(
                        'icon'  => 'arrow-right disabled',
                        'title' => '',
                        'url'   => ''
                    );

                }
            }






        } elseif ($data['parent'] == 'category') {

            $up_button = array(
                'icon'      => 'arrow-up',
                'title'     => _("Category").' '.$data['_parent']->get('Code'),
                'reference' => 'products/'.$data['store']->id.'/category/'.$data['parent_key']
            );

            if ($prev_key) {
                $left_buttons[] = array(
                    'icon'      => 'arrow-left',
                    'title'     => $prev_title,
                    'reference' => 'products/'.$data['store']->id.'/category/'.$data['parent_key'].'/deal_component/'.$prev_key
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
                    'reference' => 'products/'.$data['store']->id.'/category/'.$data['parent_key'].'/deal_component/'.$next_key
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
        $_section = 'offers';

    }

    $sections = get_sections('products', $data['store']->id);


    if (isset($sections[$_section])) {
        $sections[$_section]['selected'] = true;
    }
    if ($data['parent'] == 'category') {
        $title = $data['_parent']->get('Code').': <span class="id"><span class="Deal_Component_Name_Label">'.$object->get('Name Label').'</span> </span>';

    }elseif ($data['parent'] == 'campaign') {

        switch ($data['_parent']->get('Code')) {
            case 'OR':
                if ($data['_object']->get('Deal Component Allowance Target') == 'Category') {
                    $target = sprintf(
                        ' <span class="allowance link" onclick="change_view(\'products/%s/category/%s\')">%s</span>',
                        $data['_object']->get('Deal Component Store Key'),
                        $data['_object']->get('Deal Component Allowance Target Key'),
                        $data['_object']->get('Deal Component Allowance Target Label')
                    );
                } else {
                    $target = '';
                }

                $title = '<span class="id"><span class="Deal_Component_Name_Label">'.$object->get('Name Label').'</span>'.$target.'</span>';
                break;
            default:
                $title = '<span class="id"><span class="Deal_Component_Name_Label">'.$object->get('Name Label').'</span> </span>';

        }


    } else {
        $title = '<span class="id"><span class="Deal_Component_Name_Label">'.$object->get('Name Label').'</span> </span>';

    }


    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => $title,
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search offers')
        )

    );
    $smarty->assign('_content', $_content);


    $html = $smarty->fetch('navigation.tpl');

    return $html;

}



