<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 2 November 2015 at 11:32:44 CET, Lido (Venice) , Italy

 Copyright (c) 2015, Inikoo

 Version 3.0
*/


function get_accounting_server_dashboard_navigation($data, $user, $smarty) {


    $sections = get_sections('accounting_server','all');

    $sections['dashboard']['selected'] = true;

    $title = _('Accounting');

    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => array(),
        'right_buttons'  => array(),
        'title'          => $title,
        'search'         => array(
            'show'        => false,
            'placeholder' => _('Search accounting')
        )

    );
    $smarty->assign('_content', $_content);

    $html = $smarty->fetch('navigation.tpl');

    return $html;
}


function get_payments_navigation($data, $user, $smarty, $db) {



    $right_buttons = array();
    $left_buttons  = array();

    switch ($data['parent']) {
        case 'account':


            $title                            = _('Payments').' ('._('All stores').')';
            $sections                         = get_sections('accounting_server', 'all');
            $sections['payments']['selected'] = true;

            break;

        case 'store':
            $store = new Store($data['parent_key']);

            $sections     = get_sections('accounting', $store->id);
            $up_button    = array();
            $button_label = _('Payments %s');
            $block_view   = 'accounting';
            if ($user->stores > 1) {
                $up_button = array(
                    'icon'      => 'arrow-up',
                    'title'     => _("Payments"),
                    'reference' => 'payments/all'
                );

                list($prev_key, $next_key) = get_prev_next(
                    $store->id, $user->stores
                );

                $sql = sprintf(
                    "SELECT `Store Code` FROM `Store Dimension` WHERE `Store Key`=%d", $prev_key
                );

                if ($result = $db->query($sql)) {
                    if ($row = $result->fetch()) {
                        $prev_title = sprintf($button_label, $row['Store Code']);

                    } else {
                        $prev_title = '';

                    }
                } else {
                    print_r($error_info = $db->errorInfo());
                    print "$sql\n";
                    exit;
                }


                $sql = sprintf(
                    "SELECT `Store Code` FROM `Store Dimension` WHERE `Store Key`=%d", $next_key
                );


                if ($result = $db->query($sql)) {
                    if ($row = $result->fetch()) {
                        $next_title = sprintf($button_label, $row['Store Code']);

                    } else {
                        $next_title = '';

                    }
                } else {
                    print_r($error_info = $db->errorInfo());
                    print "$sql\n";
                    exit;
                }


                $left_buttons[] = array(
                    'icon'      => 'arrow-left',
                    'title'     => $prev_title,
                    'reference' => $block_view.'/'.$prev_key
                );
                $left_buttons[] = $up_button;

                $left_buttons[] = array(
                    'icon'      => 'arrow-right',
                    'title'     => $next_title,
                    'reference' => $block_view.'/'.$next_key
                );


                $title = _('Payments').' <span class="id" title="'.$store->get('Name').'">'.$store->get('Code').'</span>';


                $sections['accounting']['selected'] = true;

            }

            $sections['accounting']['selected'] = true;

            break;
    }



    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => $title,
        'search'         => array(
            'show'        => false,
            'placeholder' => _('Search payments')
        )

    );
    $smarty->assign('_content', $_content);

    $html = $smarty->fetch('navigation.tpl');

    return $html;
}


function get_payment_service_provider_navigation($data, $user, $smarty, $db) {

    global $smarty;

    $object        = $data['_object'];
    $left_buttons  = array();
    $right_buttons = array();

    if ($data['parent']) {

        switch ($data['parent']) {
            case 'account':
                $tab      = 'payment_service_providers';
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
            if ($row2 = $result2->fetch()) {
                $sql = sprintf(
                    "select `Payment Service Provider Name` object_name,PSP.`Payment Service Provider Key` as object_key from $table   $where $wheref
	                and ($_order_field < %s OR ($_order_field = %s AND PSP.`Payment Service Provider Key` < %d))  order by $_order_field desc , PSP.`Payment Service Provider Key` desc limit 1",

                    prepare_mysql($_order_field_value), prepare_mysql($_order_field_value), $object->id
                );


                if ($result = $db->query($sql)) {
                    if ($row = $result->fetch()) {
                        $prev_key   = $row['object_key'];
                        $prev_title = _("Payment option").' '.$row['object_name'].' ('.$row['object_key'].')';
                    }
                } else {
                    print_r($error_info = $db->errorInfo());
                    print "$sql\n";
                    exit;
                }


                $sql = sprintf(
                    "select `Payment Service Provider Name` object_name,PSP.`Payment Service Provider Key` as object_key from $table   $where $wheref
	                and ($_order_field  > %s OR ($_order_field  = %s AND PSP.`Payment Service Provider Key` > %d))  order by $_order_field   , PSP.`Payment Service Provider Key`  limit 1", prepare_mysql($_order_field_value), prepare_mysql($_order_field_value),
                    $object->id
                );

                if ($result = $db->query($sql)) {
                    if ($row = $result->fetch()) {

                    }
                    $next_key   = $row['object_key'];
                    $next_title = _("Payment option").' '.$row['object_name'].' ('.$row['object_key'].')';
                } else {
                    print_r($error_info = $db->errorInfo());
                    print "$sql\n";
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
            print "$sql\n";
            exit;
        }


        if ($data['parent'] == 'account') {


            $up_button = array(
                'icon'      => 'arrow-up',
                'title'     => _("Account payment options"),
                'reference' => 'account'
            );

            if ($prev_key) {
                $left_buttons[] = array(
                    'icon'      => 'arrow-left',
                    'title'     => $prev_title,
                    'reference' => 'account/payment_service_provider/'.$prev_key
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
                    'reference' => 'account/payment_service_provider/'.$next_key
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
        exit('');

    }

    $sections = get_sections('account', '');


    $sections['account']['selected'] = true;


    $title = _('Payment option').' <span class="id">'.$data['_object']->get(
            'Payment Service Provider Name'
        ).'</span>';


    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => $title,
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search payments')
        )

    );
    $smarty->assign('_content', $_content);


    $html = $smarty->fetch('navigation.tpl');

    return $html;

}


function get_payment_account_navigation($data, $user, $smarty, $db) {


    $object        = $data['_object'];
    $left_buttons  = array();
    $right_buttons = array();

    if ($data['parent']) {

        switch ($data['parent']) {
            case 'account':
                $tab      = 'payment_accounts';
                $_section = 'account';
                $sections = get_sections('accounting', 'all');

                break;
            case 'store':
                $tab      = 'payment_accounts';
                $_section = 'account';


                $sections = get_sections('accounting', $data['parent_key']);

                break;
            case 'payment_service_provider':
                $tab      = 'payment_service_provider.accounts';
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


        $_order_field = $order;
        $order        = preg_replace('/^.*\.`/', '', $order);
        $order        = preg_replace('/^`/', '', $order);
        $order        = preg_replace('/`$/', '', $order);


        $_order_field_value = $object->get($order);


        $prev_title = '';
        $next_title = '';
        $prev_key   = 0;
        $next_key   = 0;
        $sql        = trim($sql_totals." $wheref");


        if ($result2 = $db->query($sql)) {
            if ($row2 = $result2->fetch()) {
                $sql = sprintf(
                    "select `Payment Account Name` object_name,PA.`Payment Account Key` as object_key from $table   $where $wheref
	                and ($_order_field < %s OR ($_order_field = %s AND PA.`Payment Account Key` < %d))  order by $_order_field desc , PA.`Payment Account Key` desc limit 1",

                    prepare_mysql($_order_field_value), prepare_mysql($_order_field_value), $object->id
                );

                if ($result = $db->query($sql)) {
                    if ($row = $result->fetch()) {
                        $prev_key   = $row['object_key'];
                        $prev_title = _("Payment option").' '.$row['object_name'].' ('.$row['object_key'].')';
                    }
                } else {
                    print_r($error_info = $db->errorInfo());
                    print "$sql\n";
                    exit;
                }


                $sql = sprintf(
                    "select `Payment Account Name` object_name,PA.`Payment Account Key` as object_key from $table   $where $wheref
	                and ($_order_field  > %s OR ($_order_field  = %s AND PA.`Payment Account Key` > %d))  order by $_order_field   , PA.`Payment Account Key`  limit 1", prepare_mysql($_order_field_value), prepare_mysql($_order_field_value), $object->id
                );

                if ($result = $db->query($sql)) {
                    if ($row = $result->fetch()) {

                    }
                    $next_key   = $row['object_key'];
                    $next_title = _("Payment option").' '.$row['object_name'].' ('.$row['object_key'].')';
                } else {
                    print_r($error_info = $db->errorInfo());
                    print "$sql\n";
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


        if ($data['parent'] == 'account') {


            $up_button = array(
                'icon'      => 'arrow-up',
                'title'     => _("Payment accounts").' ('._(
                        'All stores'
                    ).')',
                'reference' => 'payment_accounts/all'
            );

            if ($prev_key) {
                $left_buttons[] = array(
                    'icon'      => 'arrow-left',
                    'title'     => $prev_title,
                    'reference' => 'payment_account/'.$prev_key
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
                    'reference' => 'payment_account/'.$next_key
                );

            } else {
                $left_buttons[] = array(
                    'icon'  => 'arrow-right disabled',
                    'title' => '',
                    'url'   => ''
                );

            }


        } elseif ($data['parent'] == 'payment_service_provider') {
            include_once 'class.Payment_Service_Provider.php';
            $psp = new Payment_Service_Provider($data['parent_key']);

            $up_button = array(
                'icon'      => 'arrow-up',
                'title'     => _("Payment option").' '.$psp->get(
                        'Payment Service Provider Name'
                    ),
                'reference' => 'account/payment_service_provider/'.$data['parent_key']
            );

            if ($prev_key) {
                $left_buttons[] = array(
                    'icon'      => 'arrow-left',
                    'title'     => $prev_title,
                    'reference' => 'payment_service_provider/'.$data['parent_key'].'/payment_account/'.$prev_key
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
                    'reference' => 'payment_service_provider/'.$data['parent_key'].'/payment_account/'.$next_key
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
        exit('xx');

    }


    $sections['payment_accounts']['selected'] = true;


    $title = _('Payment account').' <span class="id">'.$data['_object']->get(
            'Payment Account Name'
        ).'</span>';


    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => $title,
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search payments')
        )

    );
    $smarty->assign('_content', $_content);


    $html = $smarty->fetch('navigation.tpl');

    return $html;

}


function get_payment_account_server_navigation($data, $user, $smarty, $db) {


    $object        = $data['_object'];
    $left_buttons  = array();
    $right_buttons = array();


    $sections = get_sections('accounting_server');

    if ($data['parent']) {

        switch ($data['parent']) {
            case 'account':
                $tab = 'account.payment_accounts';

                break;

            case 'payment_service_provider':
                $tab = 'payment_service_provider.accounts';
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


        $_order_field_value = $object->get($order);


        $prev_title = '';
        $next_title = '';
        $prev_key   = 0;
        $next_key   = 0;
        $sql        = trim($sql_totals." $wheref");


        if ($result2 = $db->query($sql)) {
            if ($row2 = $result2->fetch()) {
                $sql = sprintf(
                    "select `Payment Account Name` object_name,PA.`Payment Account Key` as object_key from $table   $where $wheref
	                and ($_order_field < %s OR ($_order_field = %s AND PA.`Payment Account Key` < %d))  order by $_order_field desc , PA.`Payment Account Key` desc limit 1",

                    prepare_mysql($_order_field_value), prepare_mysql($_order_field_value), $object->id
                );

                if ($result = $db->query($sql)) {
                    if ($row = $result->fetch()) {
                        $prev_key   = $row['object_key'];
                        $prev_title = _("Payment option").' '.$row['object_name'].' ('.$row['object_key'].')';
                    }
                } else {
                    print_r($error_info = $db->errorInfo());
                    print "$sql\n";
                    exit;
                }


                $sql = sprintf(
                    "select `Payment Account Name` object_name,PA.`Payment Account Key` as object_key from $table   $where $wheref
	                and ($_order_field  > %s OR ($_order_field  = %s AND PA.`Payment Account Key` > %d))  order by $_order_field   , PA.`Payment Account Key`  limit 1", prepare_mysql($_order_field_value), prepare_mysql($_order_field_value), $object->id
                );

                if ($result = $db->query($sql)) {
                    if ($row = $result->fetch()) {

                    }
                    $next_key   = $row['object_key'];
                    $next_title = _("Payment option").' '.$row['object_name'].' ('.$row['object_key'].')';
                } else {
                    print_r($error_info = $db->errorInfo());
                    print "$sql\n";
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


        if ($data['parent'] == 'account') {


            $up_button = array(
                'icon'      => 'arrow-up',
                'title'     => _("Payment accounts").' ('._(
                        'All stores'
                    ).')',
                'reference' => 'payment_accounts/all'
            );

            if ($prev_key) {
                $left_buttons[] = array(
                    'icon'      => 'arrow-left',
                    'title'     => $prev_title,
                    'reference' => 'payment_account/'.$prev_key
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
                    'reference' => 'payment_account/'.$next_key
                );

            } else {
                $left_buttons[] = array(
                    'icon'  => 'arrow-right disabled',
                    'title' => '',
                    'url'   => ''
                );

            }


        } elseif ($data['parent'] == 'payment_service_provider') {
            include_once 'class.Payment_Service_Provider.php';
            $psp = new Payment_Service_Provider($data['parent_key']);

            $up_button = array(
                'icon'      => 'arrow-up',
                'title'     => _("Payment option").' '.$psp->get(
                        'Payment Service Provider Name'
                    ),
                'reference' => 'account/payment_service_provider/'.$data['parent_key']
            );

            if ($prev_key) {
                $left_buttons[] = array(
                    'icon'      => 'arrow-left',
                    'title'     => $prev_title,
                    'reference' => 'payment_service_provider/'.$data['parent_key'].'/payment_account/'.$prev_key
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
                    'reference' => 'payment_service_provider/'.$data['parent_key'].'/payment_account/'.$next_key
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
        exit('xx');

    }


    $sections['payment_accounts']['selected'] = true;


    $title = _('Payment account').' <span class="id">'.$data['_object']->get(
            'Payment Account Name'
        ).'</span>';


    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => $title,
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search payments')
        )

    );
    $smarty->assign('_content', $_content);

    $html = $smarty->fetch('navigation.tpl');

    return $html;

}


function get_payment_accounts_navigation($data, $user, $smarty, $db) {


    $left_buttons  = array();
    $right_buttons = array();


    switch ($data['parent']) {
        case 'account':
            $tab      = 'payment_accounts';
            $_section = 'payment_accounts';

            $sections                                 = get_sections('accounting_server', 'all');
            $sections['payment_accounts']['selected'] = true;
            $title                                    = _('Payments accounts').' ('._('All stores').')';
            break;
        case 'store':
            $tab      = 'payment_accounts';
            $_section = 'payment_accounts';

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


            $store = new Store($data['parent_key']);

            $sections     = get_sections('accounting', $store->id);
            $up_button    = array();
            $button_label = _('Payment accounts %s');
            $block_view   = 'payment_accounts';
            if ($user->stores > 1) {
                $up_button = array(
                    'icon'      => 'arrow-up',
                    'title'     => _("Payments"),
                    'reference' => 'payments/all'
                );

                list($prev_key, $next_key) = get_prev_next(
                    $store->id, $user->stores
                );

                $sql = sprintf(
                    "SELECT `Store Code` FROM `Store Dimension` WHERE `Store Key`=%d", $prev_key
                );

                if ($result = $db->query($sql)) {
                    if ($row = $result->fetch()) {
                        $prev_title = sprintf($button_label, $row['Store Code']);
                    } else {
                        $prev_title = '';
                    }
                } else {
                    print_r($error_info = $db->errorInfo());
                    print "$sql\n";
                    exit;
                }


                $sql = sprintf(
                    "SELECT `Store Code` FROM `Store Dimension` WHERE `Store Key`=%d", $next_key
                );
                if ($result = $db->query($sql)) {
                    if ($row = $result->fetch()) {
                        $next_title = sprintf($button_label, $row['Store Code']);
                    } else {
                        $next_title = '';
                    }
                } else {
                    print_r($error_info = $db->errorInfo());
                    print "$sql\n";
                    exit;
                }


                $left_buttons[] = array(
                    'icon'      => 'arrow-left',
                    'title'     => $prev_title,
                    'reference' => $block_view.'/'.$prev_key
                );
                $left_buttons[] = $up_button;

                $left_buttons[] = array(
                    'icon'      => 'arrow-right',
                    'title'     => $next_title,
                    'reference' => $block_view.'/'.$next_key
                );


                $title                                    = _(
                        'Payment accounts'
                    ).' <span class="id" title="'.$store->get('Name').'">'.$store->get('Code').'</span>';
                $sections['payment_accounts']['selected'] = true;

            }


            break;
        case 'payment_service_provider':
            $tab      = 'payment_service_provider.accounts';
            $_section = 'payment_accounts';

            $tab      = 'payment_accounts';
            $_section = 'payment_accounts';

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

            include_once 'class.Payment_Service_Provider.php';
            $psp = new Payment_Service_Provider($data['parent_key']);

            $up_button = array(
                'icon'      => 'arrow-up',
                'title'     => _("Payment option").' '.$psp->get(
                        'Payment Service Provider Name'
                    ),
                'reference' => 'account/payment_service_provider/'.$data['parent_key']
            );

            if ($prev_key) {
                $left_buttons[] = array(
                    'icon'      => 'arrow-left',
                    'title'     => $prev_title,
                    'reference' => 'payment_service_provider/'.$data['parent_key'].'/payment_account/'.$prev_key
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
                    'reference' => 'payment_service_provider/'.$data['parent_key'].'/payment_account/'.$next_key
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


    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => $title,
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search payments')
        )

    );
    $smarty->assign('_content', $_content);


    $html = $smarty->fetch('navigation.tpl');

    return $html;

}


function get_payment_navigation($data, $user, $smarty, $db) {


    $object        = $data['_object'];
    $left_buttons  = array();
    $right_buttons = array();

    if ($data['parent']) {

        switch ($data['parent']) {
            case 'account':
                $tab      = 'payments';
                $_section = 'accounting';
                break;
            case 'payment_service_provider':
                $tab      = 'payment_service_provider.payments';
                $_section = 'accounting';
                break;
            case 'payment_account':
                $tab      = 'payment_account.payments';
                $_section = 'accounting';
                break;
            case 'store':
                $tab      = 'payments';
                $_section = 'accounting';
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


        $_order_field = $order;
        $order        = preg_replace('/^.*\.`/', '', $order);
        $order        = preg_replace('/^`/', '', $order);
        $order        = preg_replace('/`$/', '', $order);


        $_order_field_value = $object->get($order);


        $prev_title = '';
        $next_title = '';
        $prev_key   = 0;
        $next_key   = 0;
        $sql        = trim($sql_totals." $wheref");


        $sql = sprintf(
            "select `Payment Key` object_name,P.`Payment Key` as object_key from $table   $where $wheref
	                and ($_order_field < %s OR ($_order_field = %s AND P.`Payment Key` < %d))  order by $_order_field desc , P.`Payment Key` desc limit 1",

            prepare_mysql($_order_field_value), prepare_mysql($_order_field_value), $object->id
        );


        if ($result = $db->query($sql)) {
            if ($row = $result->fetch()) {
                $prev_key   = $row['object_key'];
                $prev_title = _("Payment option").' '.$row['object_name'].' ('.$row['object_key'].')';
            }
        } else {
            print_r($error_info = $db->errorInfo());
            print "$sql\n";
            exit;
        }


        $sql = sprintf(
            "select `Payment Key` object_name,P.`Payment Key` as object_key from $table   $where $wheref
	                and ($_order_field  > %s OR ($_order_field  = %s AND P.`Payment Key` > %d))  order by $_order_field   , P.`Payment Key`  limit 1", prepare_mysql($_order_field_value), prepare_mysql($_order_field_value), $object->id
        );


        if ($result = $db->query($sql)) {
            if ($row = $result->fetch()) {
                $next_key   = $row['object_key'];
                $next_title = _("Payment option").' '.$row['object_name'].' ('.$row['object_key'].')';
            }
        } else {
            print_r($error_info = $db->errorInfo());
            print "$sql\n";
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


        if ($data['parent'] == 'account') {


            $up_button = array(
                'icon'      => 'arrow-up',
                'title'     => _("Account payments"),
                'reference' => 'payments/all'
            );

            if ($prev_key) {
                $left_buttons[] = array(
                    'icon'      => 'arrow-left',
                    'title'     => $prev_title,
                    'reference' => 'payment/'.$prev_key
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
                    'reference' => 'payment/'.$next_key
                );

            } else {
                $left_buttons[] = array(
                    'icon'  => 'arrow-right disabled',
                    'title' => '',
                    'url'   => ''
                );

            }


        } elseif ($data['parent'] == 'payment_service_provider') {
            include_once 'class.Payment_Service_Provider.php';
            $psp = new Payment_Service_Provider($data['parent_key']);

            $up_button = array(
                'icon'      => 'arrow-up',
                'title'     => _("Payment option").' '.$psp->get(
                        'Payment Service Provider Name'
                    ),
                'reference' => 'account/payment_service_provider/'.$data['parent_key']
            );

            if ($prev_key) {
                $left_buttons[] = array(
                    'icon'      => 'arrow-left',
                    'title'     => $prev_title,
                    'reference' => 'payment_service_provider/'.$data['parent_key'].'/payment/'.$prev_key
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
                    'reference' => 'payment_service_provider/'.$data['parent_key'].'/payment/'.$next_key
                );

            } else {
                $left_buttons[] = array(
                    'icon'  => 'arrow-right disabled',
                    'title' => '',
                    'url'   => ''
                );

            }


        } elseif ($data['parent'] == 'payment_account') {
            include_once 'class.Payment_Account.php';
            $payment_account = new Payment_Account($data['parent_key']);

            $up_button = array(
                'icon'      => 'arrow-up',
                'title'     => _("Payment account").' '.$payment_account->get(
                        'Payment Account Name'
                    ),
                'reference' => 'account/payment_account/'.$data['parent_key']
            );

            if ($prev_key) {
                $left_buttons[] = array(
                    'icon'      => 'arrow-left',
                    'title'     => $prev_title,
                    'reference' => 'payment_account/'.$data['parent_key'].'/payment/'.$prev_key
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
                    'reference' => 'payment_account/'.$data['parent_key'].'/payment/'.$next_key
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
        exit('');

    }

    $sections = get_sections('accounting', '');


    $sections[$_section]['selected'] = true;


    if ($data['_object']->get('Payment Method') == 'Account') {
        $customer = get_object('Customer', $data['_object']->get('Payment Customer Key'));
        $title    = sprintf(_('Credit for customer %s'), '<span class="link id" onclick="change_view(\'customers/'.$customer->get('Customer Store Key').'/'.$customer->id.'\')">'.$customer->get('Name')).'</span>';
    } else {

        $title = _('Payment').' <span class="id">'.$data['_object']->get('Payment Key').' '.($data['_object']->get('Payment Transaction ID') != '' ? '('.$data['_object']->get('Payment Transaction ID').')' : '').' </span>';

    }


    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => $title,
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search payments')
        )

    );
    $smarty->assign('_content', $_content);


    $html = $smarty->fetch('navigation.tpl');

    return $html;

}


function get_credits_navigation($data, $user, $smarty, $db) {
    global $smarty;

    $right_buttons = array();
    $left_buttons  = array();

    switch ($data['parent']) {
        case 'account':


            $title                           = _('Credits').' ('._('All stores').')';
            $sections                        = get_sections('accounting_server', 'all');
            $sections['credits']['selected'] = true;

            break;

        case 'store':
            $store = new Store($data['parent_key']);

            $sections     = get_sections('accounting', $store->id);
            $button_label = _('Payments %s');

            if ($user->stores > 1) {
                $up_button = array(
                    'icon'      => 'arrow-up',
                    'title'     => _("Credits (All stores)"),
                    'reference' => 'credits/all'
                );

                list($prev_key, $next_key) = get_prev_next(
                    $store->id, $user->stores
                );

                $sql = sprintf(
                    "SELECT `Store Code` FROM `Store Dimension` WHERE `Store Key`=%d", $prev_key
                );

                if ($result = $db->query($sql)) {
                    if ($row = $result->fetch()) {
                        $prev_title = sprintf($button_label, $row['Store Code']);
                    } else {
                        $prev_title = '';
                    }
                } else {
                    print_r($error_info = $db->errorInfo());
                    print "$sql\n";
                    exit;
                }


                $sql = sprintf(
                    "SELECT `Store Code` FROM `Store Dimension` WHERE `Store Key`=%d", $next_key
                );

                if ($result = $db->query($sql)) {
                    if ($row = $result->fetch()) {
                        $next_title = sprintf($button_label, $row['Store Code']);
                    } else {
                        $next_title = '';
                    }
                } else {
                    print_r($error_info = $db->errorInfo());
                    print "$sql\n";
                    exit;
                }


                $left_buttons[] = array(
                    'icon'      => 'arrow-left',
                    'title'     => $prev_title,
                    'reference' => 'credits/'.$prev_key
                );
                $left_buttons[] = $up_button;

                $left_buttons[] = array(
                    'icon'      => 'arrow-right',
                    'title'     => $next_title,
                    'reference' => 'credits/'.$next_key
                );


                $title = _('Customers with credits').' <span class="id" title="'.$store->get('Name').'">'.$store->get('Code').'</span>';


            }

            $sections['credits']['selected'] = true;

            break;
    }


    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => $title,
        'search'         => array(
            'show'        => false,
            'placeholder' => _('Search payments')
        )

    );
    $smarty->assign('_content', $_content);

    $html = $smarty->fetch('navigation.tpl');

    return $html;
}

function get_payments_by_store_navigation($data, $user) {
    global $smarty;

    $right_buttons = array();
    $left_buttons  = array();

    switch ($data['parent']) {
        case 'account':


            $title                                     = _('Payments by store');
            $sections                                  = get_sections('accounting_server', 'all');
            $sections['payments_by_store']['selected'] = true;

            break;

        case 'store':
            $store = new Store($data['parent_key']);

            $sections     = get_sections('accounting', $store->id);
            $up_button    = array();
            $button_label = _('Payments %s');
            $block_view   = 'accounting';
            if ($user->stores > 1) {
                $up_button = array(
                    'icon'      => 'arrow-up',
                    'title'     => _("Payments"),
                    'reference' => 'payments/all'
                );

                list($prev_key, $next_key) = get_prev_next(
                    $store->id, $user->stores
                );

                $sql = sprintf(
                    "SELECT `Store Code` FROM `Store Dimension` WHERE `Store Key`=%d", $prev_key
                );
                $res = mysql_query($sql);
                if ($row = mysql_fetch_assoc($res)) {
                    $prev_title = sprintf($button_label, $row['Store Code']);
                } else {
                    $prev_title = '';
                }
                $sql = sprintf(
                    "SELECT `Store Code` FROM `Store Dimension` WHERE `Store Key`=%d", $next_key
                );
                $res = mysql_query($sql);
                if ($row = mysql_fetch_assoc($res)) {
                    $next_title = sprintf($button_label, $row['Store Code']);
                } else {
                    $next_title = '';
                }


                $left_buttons[] = array(
                    'icon'      => 'arrow-left',
                    'title'     => $prev_title,
                    'reference' => $block_view.'/'.$prev_key
                );
                $left_buttons[] = $up_button;

                $left_buttons[] = array(
                    'icon'      => 'arrow-right',
                    'title'     => $next_title,
                    'reference' => $block_view.'/'.$next_key
                );


                $title                            = _('Payments').' <span class="id" title="'.$store->get(
                        'Name'
                    ).'">'.$store->get('Code').'</span>';
                $sections['accounting']['selected'] = true;

            }

            $sections['accounting']['selected'] = true;

            break;
    }


    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => $title,
        'search'         => array(
            'show'        => false,
            'placeholder' => _('Search payments')
        )

    );
    $smarty->assign('_content', $_content);

    $html = $smarty->fetch('navigation.tpl');

    return $html;
}

function get_invoices_server_navigation($data, $smarty, $user, $db, $account) {


    $block_view = $data['section'];


    $sections = get_sections('accounting_server','all');
    switch ($block_view) {

        case 'invoices':
            $sections_class = '';
            $title          = _('Invoices').' ('._('All stores').')';

            $button_label = _('Invoices %s');
            break;

        case 'payments':
            $sections_class = '';
            $title          = _('Payments').' ('._('All stores').')';

            $button_label = _('Payments %s');
            break;
    }

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
            'placeholder' => _('Search invoices')
        )

    );
    $smarty->assign('_content', $_content);

    $html = $smarty->fetch('navigation.tpl');

    return $html;

}


function get_invoices_categories_server_navigation($data, $smarty, $user, $db, $account) {




    global $user, $smarty;



    $block_view = $data['section'];


    $sections = get_sections('accounting_server','all');

    $sections_class = '';
    $title          = _("Invoice's categories").' ('._('All stores').')';

    $button_label = _('Payments %s');


    // $up_button=array('icon'=>'arrow-up', 'title'=>_("Order's index"), 'reference'=>'account/orders');
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
            'placeholder' => _('Search invoices')
        )

    );
    $smarty->assign('_content', $_content);

    $html = $smarty->fetch('navigation.tpl');

    return $html;

}


function get_invoices_category_server_navigation($data, $smarty, $user, $db, $account) {



    $left_buttons = array();


    $right_buttons = array();


    $sections = get_sections('accounting_server','all');

    $sections_class = '';

    if($data['_object']->get('Category Branch Type')=='Root'){
        $title=_("Invoice's categories");
    }else{


        $up_button = array(
            'icon'      => 'arrow-up',
            'title'     => _("Invoice's categories"),
            'reference' => 'invoices/all/categories'
        );


        $title          = _("Invoice's category").' <span class="Category_Label id">'.$data['_object']->get('Label').'</span>';



        $left_buttons[] = $up_button;

    }




    // $up_button=array('icon'=>'arrow-up', 'title'=>_("Order's index"), 'reference'=>'account/orders');



    $sections['categories']['selected'] = true;


    $_content = array(
        'sections_class' => $sections_class,
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => $title,
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search invoices')
        )

    );
    $smarty->assign('_content', $_content);

    $html = $smarty->fetch('navigation.tpl');

    return $html;

}



function get_invoices_navigation($data, $smarty, $user, $db, $account) {

    global $user, $smarty;

    switch ($data['parent']) {
        case 'store':
            $store = get_object('Store',$data['parent_key']);
            break;
        default:

            break;
    }

    $block_view = $data['section'];


    $sections = get_sections('accounting', $store->id);
    switch ($block_view) {

        case 'invoices':
            $sections_class = '';
            $title          = _('Invoices').' <span class="id">'.$store->get('Store Code').'</span>';

            $up_button    = array(
                'icon'      => 'arrow-up',
                'title'     => _('Invoices').' ('._('All stores').')',
                'reference' => 'invoices/all'
            );
            $button_label = _('Invoices %s');
            break;

        case 'payments':
            $sections_class = '';
            $title          = _('Payments').' <span class="id">'.$store->get(
                    'Store Code'
                ).'</span>';

            $up_button    = array(
                'icon'      => 'arrow-up',
                'title'     => _('Payments').' ('._('All stores').')',
                'reference' => 'invoices/payments/all'
            );
            $button_label = _('Payments %s');
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
                $prev_title = _('Store').' '.$row['Store Code'];
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
            'reference' => $block_view.'/'.$prev_key
        );
        $left_buttons[] = $up_button;

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
            'placeholder' => _('Search invoices')
        )

    );
    $smarty->assign('_content', $_content);

    $html = $smarty->fetch('navigation.tpl');

    return $html;

}


function get_invoice_navigation($data, $smarty, $user, $db, $account) {


    $object        = $data['_object'];
    $left_buttons  = array();
    $right_buttons = array();

    if ($data['parent']) {

        switch ($data['parent']) {
            case 'customer':
                $tab      = 'customer.orders';
                $_section = 'customers';
                break;
            case 'store':
                $tab      = 'invoices';
                $_section = 'invoices';
                break;
            case 'order':
                $tab      = 'order.invoices';
                $_section = 'orders';
                break;
            case 'delivery_note':
                $tab      = 'delivery_note.invoices';
                $_section = 'delivery_notes';
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
        $sql        = trim($sql_totals." $wheref");

        if ($result2 = $db->query($sql)) {
            if ($row2 = $result2->fetch()) {
                if ($row2['num'] > 1) {


                    $sql = sprintf(
                        "select `Invoice Public ID` object_name,I.`Invoice Key` as object_key from $table   $where $wheref
	                and ($_order_field < %s OR ($_order_field = %s AND I.`Invoice Key` < %d))  order by $_order_field desc , I.`Invoice Key`desc limit 1",

                        prepare_mysql($_order_field_value), prepare_mysql($_order_field_value), $object->id
                    );

                    if ($result = $db->query($sql)) {
                        if ($row = $result->fetch()) {
                            $prev_key   = $row['object_key'];
                            $prev_title = _("Invoice").' '.$row['object_name'].' ('.$row['object_key'].')';

                        }
                    } else {
                        print_r($error_info = $db->errorInfo());
                        print $sql;
                        exit;
                    }


                    $sql = sprintf(
                        "select `Invoice Public ID` object_name,I.`Invoice Key` as object_key from $table   $where $wheref
	                and ($_order_field  > %s OR ($_order_field  = %s AND I.`Invoice Key` > %d))  order by $_order_field   , I.`Invoice Key`  limit 1", prepare_mysql($_order_field_value), prepare_mysql($_order_field_value), $object->id
                    );

                    if ($result = $db->query($sql)) {
                        if ($row = $result->fetch()) {
                            $next_key   = $row['object_key'];
                            $next_title = _("Invoice").' '.$row['object_name'].' ('.$row['object_key'].')';

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


        if ($data['parent'] == 'customer') {


            $up_button = array(
                'icon'      => 'arrow-up',
                'title'     => _("Customer").' '.$object->get(
                        'Order Customer Name'
                    ),
                'reference' => 'customers/'.$object->get(
                        'Order Store Key'
                    ).'/'.$object->get('Order Customer Key')
            );

            if ($prev_key) {
                $left_buttons[] = array(
                    'icon'      => 'arrow-left',
                    'title'     => $prev_title,
                    'reference' => 'customer/'.$object->get(
                            'Order Customer Key'
                        ).'/order/'.$prev_key
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
                    'reference' => 'customer/'.$object->get(
                            'Order Customer Key'
                        ).'/order/'.$next_key
                );

            } else {
                $left_buttons[] = array(
                    'icon'  => 'arrow-right disabled',
                    'title' => '',
                    'url'   => ''
                );

            }
            $sections = get_sections(
                'customers', $object->get('Order Store Key')
            );


        } elseif ($data['parent'] == 'store') {
            $store     = get_object('Store',$data['parent_key']);
            $up_button = array(
                'icon'      => 'arrow-up',
                'title'     => _("Invoices").' ('.$store->get(
                        'Store Code'
                    ).')',
                'reference' => 'invoices/'.$data['parent_key']
            );

            if ($prev_key) {
                $left_buttons[] = array(
                    'icon'      => 'arrow-left',
                    'title'     => $prev_title,
                    'reference' => 'invoices/'.$data['parent_key'].'/'.$prev_key
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
                    'reference' => 'invoices/'.$data['parent_key'].'/'.$next_key
                );

            } else {
                $left_buttons[] = array(
                    'icon'  => 'arrow-right disabled',
                    'title' => '',
                    'url'   => ''
                );

            }


            $sections = get_sections('accounting', $data['parent_key']);


        } elseif ($data['parent'] == 'order') {
            $order     = new Order($data['parent_key']);
            $up_button = array(
                'icon'      => 'arrow-up',
                'title'     => _("Order").' ('.$order->get(
                        'Order Public ID'
                    ).')',
                'reference' => '/orders/'.$order->get(
                        'Order Store Key'
                    ).'/'.$data['parent_key']
            );

            if ($prev_key) {
                $left_buttons[] = array(
                    'icon'      => 'arrow-left',
                    'title'     => $prev_title,
                    'reference' => 'order/'.$data['parent_key'].'/invoice/'.$prev_key
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
                    'reference' => 'order/'.$data['parent_key'].'/invoice/'.$next_key
                );

            } else {
                $left_buttons[] = array(
                    'icon'  => 'arrow-right disabled',
                    'title' => '',
                    'url'   => ''
                );

            }


            $sections = get_sections('orders', $order->get('Order Store Key'));


        } elseif ($data['parent'] == 'delivery_note') {
            $delivery_note = new DeliveryNote($data['parent_key']);
            $up_button     = array(
                'icon'      => 'arrow-up',
                'title'     => _("Delivery Note").' ('.$delivery_note->get(
                        'Delivery Note ID'
                    ).')',
                'reference' => '/delivery_notes/'.$delivery_note->get('Delivery Note Store Key').'/'.$data['parent_key']
            );

            if ($prev_key) {
                $left_buttons[] = array(
                    'icon'      => 'arrow-left',
                    'title'     => $prev_title,
                    'reference' => 'delivery_note/'.$data['parent_key'].'/invoice/'.$prev_key
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
                    'reference' => 'delivery_note/'.$data['parent_key'].'/invoice/'.$next_key
                );

            } else {
                $left_buttons[] = array(
                    'icon'  => 'arrow-right disabled',
                    'title' => '',
                    'url'   => ''
                );

            }


            $sections = get_sections(
                'orders', $delivery_note->get('Delivery Note Store Key')
            );


        }
    } else {


    }


    if (isset($sections[$_section])) {
        $sections[$_section]['selected'] = true;
    }

    if ($object->get('Invoice Type') == 'Refund') {
        $title = _('Refund').' <span class="id Invoice_Public_ID">'.$object->get('Invoice Public ID').'</span>';

    } else {
        $title = _('Invoice').' <span class="id Invoice_Public_ID">'.$object->get('Invoice Public ID').'</span>';

    }


    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => $left_buttons,
        'right_buttons'  => $right_buttons,
        'title'          => $title,
        'search'         => array(
            'show'        => true,
            'placeholder' => _('Search invoices')
        )

    );
    $smarty->assign('_content', $_content);


    $html = $smarty->fetch('navigation.tpl');

    return $html;

}



?>
