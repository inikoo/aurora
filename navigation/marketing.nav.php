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
    $sections      = get_sections('products', $data['parent_key']);


    $sections['campaigns']['selected'] = true;

    if($data['parent']=='campaign'){
        $title=sprintf(_('New offer for campaign %s'),'<span class="id">'.$data['_parent']->get('Name')).'</span>';

    }else{
        $title=_('Nre offer');
    }

    $_content = array(

        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => $title,
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search marketing')
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


    $sections['campaigns']['selected'] = true;


    $_content = array(

        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => _('New Campaign'),
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search marketing')
        )

    );
    $smarty->assign('_content', $_content);

    $html = $smarty->fetch('navigation.tpl');

    return $html;

}

function get_marketing_navigation($data, $smarty, $user, $db) {


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
        'title'          => _('Marketing').' <span class="id">'.$data['_parent']->get('Code').'</span>',
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search marketing')
        )

    );
    $smarty->assign('_content', $_content);

    $html = $smarty->fetch('navigation.tpl');

    return $html;

}

function get_campaigns_navigation($data, $smarty, $user, $db) {


    $block_view = $data['section'];

    $left_buttons  = array();
    $right_buttons = array();


    if (count($user->stores) > 1) {


        list($prev_key, $next_key) = get_prev_next($data['store']->id, $user->stores);


        if ($prev_key) {

            $sql = sprintf(
                "SELECT `Store Code` FROM `Store Dimension` WHERE `Store Key`=%d", $prev_key
            );
            if ($result = $db->query($sql)) {
                if ($row = $result->fetch()) {
                    $prev_title     = _('Campaigns').' '.$row['Store Code'];
                    $left_buttons[] = array(
                        'icon'      => 'arrow-left',
                        'title'     => $prev_title,
                        'reference' => 'campaigns/'.$prev_key
                    );
                } else {
                    $left_buttons[] = array(
                        'icon'      => 'arrow-left disabled',
                        'title'     => '',
                        'reference' => ''
                    );
                }
            } else {
                print_r($error_info = $db->errorInfo());
                exit;
            }


        } else {
            $left_buttons[] = array(
                'icon'      => 'arrow-left disabled',
                'title'     => '',
                'reference' => ''
            );
        }


        $left_buttons[] = array(
            'icon'      => 'arrow-up',
            'title'     => _('Marketing (All stores)'),
            'reference' => 'marketing/all',
            'parent'    => ''
        );

        if ($next_key) {
            $sql = sprintf(
                "SELECT `Store Code` FROM `Store Dimension` WHERE `Store Key`=%d", $next_key
            );
            if ($result = $db->query($sql)) {
                if ($row = $result->fetch()) {
                    $next_title     = _('Campaigns').' '.$row['Store Code'];
                    $left_buttons[] = array(
                        'icon'      => 'arrow-right',
                        'title'     => $next_title,
                        'reference' => 'campaigns/'.$next_key
                    );
                } else {
                    $left_buttons[] = array(
                        'icon'      => 'arrow-right disabled',
                        'title'     => '',
                        'reference' => ''
                    );
                }
            } else {
                print_r($error_info = $db->errorInfo());
                exit;
            }
        } else {
            $left_buttons[] = array(
                'icon'      => 'arrow-right disabled',
                'title'     => '',
                'reference' => ''
            );
        }


    }


    $sections = get_sections('products', $data['store']->id);

    if (isset($sections[$data['section']])) {
        $sections[$data['section']]['selected'] = true;
    }


    $_content = array(

        'sections_class' => '',
        'sections'       => $sections,

        'left_buttons'  => $left_buttons,
        'right_buttons' => $right_buttons,
        'title'         => _('Campaigns').sprintf(' <span class="Store_Code id">%s</span>', $data['store']->get('Code')),
        'search'        => array(
            'show'        => true,
            'placeholder' => _('Search marketing')
        )

    );
    $smarty->assign('_content', $_content);

    $html = $smarty->fetch('navigation.tpl');

    return $html;

}


function get_deals_navigation($data, $smarty, $user, $db) {


    $block_view = $data['section'];

    $left_buttons  = array();
    $right_buttons = array();


    if (count($user->stores) > 1) {


        list($prev_key, $next_key) = get_prev_next($data['store']->id, $user->stores);


        if ($prev_key) {

            $sql = sprintf(
                "SELECT `Store Code` FROM `Store Dimension` WHERE `Store Key`=%d", $prev_key
            );
            if ($result = $db->query($sql)) {
                if ($row = $result->fetch()) {
                    $prev_title     = _('Offers').' '.$row['Store Code'];
                    $left_buttons[] = array(
                        'icon'      => 'arrow-left',
                        'title'     => $prev_title,
                        'reference' => 'deals/'.$prev_key
                    );
                } else {
                    $left_buttons[] = array(
                        'icon'      => 'arrow-left disabled',
                        'title'     => '',
                        'reference' => ''
                    );
                }
            } else {
                print_r($error_info = $db->errorInfo());
                exit;
            }


        } else {
            $left_buttons[] = array(
                'icon'      => 'arrow-left disabled',
                'title'     => '',
                'reference' => ''
            );
        }


        $left_buttons[] = array(
            'icon'      => 'arrow-up',
            'title'     => _('Marketing (All stores)'),
            'reference' => 'marketing/all',
            'parent'    => ''
        );

        if ($next_key) {
            $sql = sprintf(
                "SELECT `Store Code` FROM `Store Dimension` WHERE `Store Key`=%d", $next_key
            );
            if ($result = $db->query($sql)) {
                if ($row = $result->fetch()) {
                    $next_title     = _('Offers').' '.$row['Store Code'];
                    $left_buttons[] = array(
                        'icon'      => 'arrow-right',
                        'title'     => $next_title,
                        'reference' => 'deals/'.$next_key
                    );
                } else {
                    $left_buttons[] = array(
                        'icon'      => 'arrow-right disabled',
                        'title'     => '',
                        'reference' => ''
                    );
                }
            } else {
                print_r($error_info = $db->errorInfo());
                exit;
            }
        } else {
            $left_buttons[] = array(
                'icon'      => 'arrow-right disabled',
                'title'     => '',
                'reference' => ''
            );
        }


    }

    $sections = get_sections('products', $data['store']->id);

    if (isset($sections[$data['section']])) {
        $sections[$data['section']]['selected'] = true;
    }


    $_content = array(

        'sections_class' => '',
        'sections'       => $sections,

        'left_buttons'  => $left_buttons,
        'right_buttons' => $right_buttons,
        'title'         => _('Offers').sprintf(
                ' <span class="Store_Code id">%s</span>', $data['store']->get('Code')
            ),
        'search'        => array(
            'show'        => true,
            'placeholder' => _('Search marketing')
        )

    );
    $smarty->assign('_content', $_content);

    $html = $smarty->fetch('navigation.tpl');

    return $html;

}


function get_marketing_server_navigation($data) {


    global $user, $smarty;

    $block_view = $data['section'];

    $left_buttons  = array();
    $right_buttons = array();
    $sections      = get_sections('marketing_server', '');

    if (isset($sections[$data['section']])) {
        $sections[$data['section']]['selected'] = true;
    }


    $_content = array(

        'sections_class' => '',
        'sections'       => $sections,

        'left_buttons'  => $left_buttons,
        'right_buttons' => $right_buttons,
        'title'         => _('Marketing (All stores)'),
        'search'        => array(
            'show'        => true,
            'placeholder' => _(
                'Search marketing all stores'
            )
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

    $block_view = $data['section'];


    $left_buttons  = array();
    $right_buttons = array();

    if ($data['parent']) {

        switch ($data['parent']) {
            case 'store':
                $tab      = 'campaigns';
                $_section = 'campaigns';
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
                    "select `Deal Campaign Name` object_name,C.`Deal Campaign Key` as object_key from $table   $where $wheref
	                and ($_order_field < %s OR ($_order_field = %s AND C.`Deal Campaign Key` < %d))  order by $_order_field desc , C.`Deal Campaign Key` desc limit 1",

                    prepare_mysql($_order_field_value), prepare_mysql($_order_field_value), $object->id
                );
                if ($result = $db->query($sql)) {
                    if ($row = $result->fetch()) {
                        $prev_key   = $row['object_key'];
                        $prev_title = _("Camapign").' '.$row['object_name'].' ('.$row['object_key'].')';

                    }
                } else {
                    print_r($error_info = $db->errorInfo());
                    exit;
                }


                $sql = sprintf(
                    "select `Deal Campaign Name` object_name,C.`Deal Campaign Key` as object_key from $table   $where $wheref
	                and ($_order_field  > %s OR ($_order_field  = %s AND C.`Deal Campaign Key` > %d))  order by $_order_field   , C.`Deal Campaign Key`  limit 1", prepare_mysql($_order_field_value),
                    prepare_mysql($_order_field_value), $object->id
                );

                if ($result = $db->query($sql)) {
                    if ($row = $result->fetch()) {
                        $next_key   = $row['object_key'];
                        $next_title = _("Camapign").' '.$row['object_name'].' ('.$row['object_key'].')';

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


        if ($data['parent'] == 'store') {

            $up_button = array(
                'icon'      => 'arrow-up',
                'title'     => _(
                        "Campaigns"
                    ).' '.$data['store']->get('Code'),
                'reference' => 'campaigns/'.$data['store']->id
            );

            if ($prev_key) {
                $left_buttons[] = array(
                    'icon'      => 'arrow-left',
                    'title'     => $prev_title,
                    'reference' => 'campaigns/'.$data['parent_key'].'/'.$prev_key
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
                    'reference' => 'campaigns/'.$data['parent_key'].'/'.$next_key
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
        $_section = 'campaigns';

    }
    //$right_buttons[]=array('icon'=>'edit', 'title'=>_('Edit customer'), 'url'=>'edit_customer.php?id='.$object->id);
    //$right_buttons[]=array('icon'=>'sticky-note-o', 'title'=>_('History note'), 'id'=>'note');
    //$right_buttons[]=array('icon'=>'paperclip', 'title'=>_('Attachement'), 'id'=>'attach');
    //$right_buttons[]=array('icon'=>'shopping-cart', 'title'=>_('New order'), 'id'=>'take_order');
    //$right_buttons[]=array('icon'=>'sticky-note', 'title'=>_('Sticky note'), 'id'=>'sticky_note_button', 'class'=> ($object->get('Sticky Note')==''?'':'hide'));

    $sections = get_sections('products', $data['store']->id);


    if (isset($sections[$_section])) {
        $sections[$_section]['selected'] = true;
    }


    $title = '<span class="id"><span class="Deal_Campaign_Name">'.$object->get(
            'Name'
        ).'</span> </span>';


    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => $title,
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search marketing')
        )

    );
    $smarty->assign('_content', $_content);


    $html = $smarty->fetch('navigation.tpl');

    return $html;

}


function get_deal_navigation($data, $smarty, $user, $db) {


    $_section = 'marketing';

    $object = $data['_object'];

    if (!$object->id) {
        return;
    }

    $block_view = $data['section'];


    $left_buttons  = array();
    $right_buttons = array();

    if ($data['parent']) {

        switch ($data['parent']) {
            case 'store':
                $tab = 'deals';

                break;
            case 'campaign':
                $tab = 'campaign.deals';
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
        //print $sql;

        if ($result2 = $db->query($sql)) {
            if ($row2 = $result2->fetch() and $row2['num'] > 1) {


                $sql = sprintf(
                    "select `Deal Name` object_name,D.`Deal Key` as object_key from $table   $where $wheref
	                and ($_order_field < %s OR ($_order_field = %s AND D.`Deal Key` < %d))  order by $_order_field desc , D.`Deal Key` desc limit 1",

                    prepare_mysql($_order_field_value), prepare_mysql($_order_field_value), $object->id
                );
                if ($result = $db->query($sql)) {
                    if ($row = $result->fetch()) {
                        $prev_key   = $row['object_key'];
                        $prev_title = _("Offer").' '.$row['object_name'].' ('.$row['object_key'].')';

                    }
                } else {
                    print_r($error_info = $db->errorInfo());
                    exit;
                }


                $sql = sprintf(
                    "select `Deal Name` object_name,D.`Deal Key` as object_key from $table   $where $wheref
	                and ($_order_field  > %s OR ($_order_field  = %s AND D.`Deal Key` > %d))  order by $_order_field   , D.`Deal Key`  limit 1", prepare_mysql($_order_field_value),
                    prepare_mysql($_order_field_value), $object->id
                );
                if ($result = $db->query($sql)) {
                    if ($row = $result->fetch()) {
                        $next_key   = $row['object_key'];
                        $next_title = _("Offer").' '.$row['object_name'].' ('.$row['object_key'].')';

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


        if ($data['parent'] == 'store') {

            $up_button = array(
                'icon'      => 'arrow-up',
                'title'     => _(
                        "Campaigns"
                    ).' '.$data['store']->get('Code'),
                'reference' => 'campaigns/'.$data['store']->id.'&tab=deals'
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
                'title'     => _(
                        "Campaigns"
                    ).' '.$data['store']->get('Code'),
                'reference' => 'campaigns/'.$data['store']->id.'/'.$data['parent_key']
            );

            if ($prev_key) {
                $left_buttons[] = array(
                    'icon'      => 'arrow-left',
                    'title'     => $prev_title,
                    'reference' => 'campaigns/'.$data['store']->id.'/'.$data['parent_key'].'/deal/'.$prev_key
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
                    'reference' => 'campaigns/'.$data['store']->id.'/'.$data['parent_key'].'/deal/'.$next_key
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
    //$right_buttons[]=array('icon'=>'edit', 'title'=>_('Edit customer'), 'url'=>'edit_customer.php?id='.$object->id);
    //$right_buttons[]=array('icon'=>'sticky-note-o', 'title'=>_('History note'), 'id'=>'note');
    //$right_buttons[]=array('icon'=>'paperclip', 'title'=>_('Attachement'), 'id'=>'attach');
    //$right_buttons[]=array('icon'=>'shopping-cart', 'title'=>_('New order'), 'id'=>'take_order');
    //$right_buttons[]=array('icon'=>'sticky-note', 'title'=>_('Sticky note'), 'id'=>'sticky_note_button', 'class'=> ($object->get('Sticky Note')==''?'':'hide'));

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
            'placeholder' => _('Search marketing')
        )

    );
    $smarty->assign('_content', $_content);


    $html = $smarty->fetch('navigation.tpl');

    return $html;

}


?>
