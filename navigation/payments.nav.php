<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 2 November 2015 at 11:32:44 CET, Lido (Venice) , Italy

 Copyright (c) 2015, Inikoo

 Version 3.0
*/


function get_payment_service_providers_navigation($data, $user, $smarty) {


    $sections                                          = get_sections(
        'payments', ($user->data['User Hooked Store Key'] ? $user->data['User Hooked Store Key'] : 'all')
    );
    $sections['payment_service_providers']['selected'] = true;
    $title                                             = _(
        'Payment service providers'
    );

    $_content = array(
        'sections_class' => '',
        'sections'       => $sections,
        'left_buttons'   => array(),
        'right_buttons'  => array(),
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


function get_payments_navigation($data, $user) {
    global $smarty;

    $right_buttons = array();
    $left_buttons  = array();

    switch ($data['parent']) {
        case 'account':


            $title                            = _('Payments');
            $sections                         = get_sections('payments', 'all');
            $sections['payments']['selected'] = true;

            break;

        case 'store':
            $store = new Store($data['parent_key']);

            $sections     = get_sections('payments', $store->id);
            $up_button    = array();
            $button_label = _('Payments %s');
            $block_view   = 'payments';
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
                $sections['payments']['selected'] = true;

            }

            $sections['payments']['selected'] = true;

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


function get_payment_service_provider_navigation($data, $user) {

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

        $res2 = mysql_query($sql);
        if ($row2 = mysql_fetch_assoc($res2) and $row2['num'] > 1) {

            $sql = sprintf(
                "select `Payment Service Provider Name` object_name,PSP.`Payment Service Provider Key` as object_key from $table   $where $wheref
	                and ($_order_field < %s OR ($_order_field = %s AND PSP.`Payment Service Provider Key` < %d))  order by $_order_field desc , PSP.`Payment Service Provider Key` desc limit 1",

                prepare_mysql($_order_field_value), prepare_mysql($_order_field_value), $object->id
            );


            $res = mysql_query($sql);
            if ($row = mysql_fetch_assoc($res)) {
                $prev_key   = $row['object_key'];
                $prev_title = _("Payment option").' '.$row['object_name'].' ('.$row['object_key'].')';

            }

            $sql = sprintf(
                "select `Payment Service Provider Name` object_name,PSP.`Payment Service Provider Key` as object_key from $table   $where $wheref
	                and ($_order_field  > %s OR ($_order_field  = %s AND PSP.`Payment Service Provider Key` > %d))  order by $_order_field   , PSP.`Payment Service Provider Key`  limit 1",
                prepare_mysql($_order_field_value), prepare_mysql($_order_field_value), $object->id
            );


            $res = mysql_query($sql);
            if ($row = mysql_fetch_assoc($res)) {
                $next_key   = $row['object_key'];
                $next_title = _("Payment option").' '.$row['object_name'].' ('.$row['object_key'].')';

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


function get_payment_account_navigation($data, $user, $smarty) {


    $object        = $data['_object'];
    $left_buttons  = array();
    $right_buttons = array();

    if ($data['parent']) {

        switch ($data['parent']) {
            case 'account':
                $tab      = 'payment_accounts';
                $_section = 'account';
                $sections = get_sections('payments', 'all');

                break;
            case 'store':
                $tab      = 'payment_accounts';
                $_section = 'account';


                $sections = get_sections('payments', $state['parent_key']);

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

        $res2 = mysql_query($sql);
        if ($row2 = mysql_fetch_assoc($res2) and $row2['num'] > 1) {

            $sql = sprintf(
                "select `Payment Account Name` object_name,PA.`Payment Account Key` as object_key from $table   $where $wheref
	                and ($_order_field < %s OR ($_order_field = %s AND PA.`Payment Account Key` < %d))  order by $_order_field desc , PA.`Payment Account Key` desc limit 1",

                prepare_mysql($_order_field_value), prepare_mysql($_order_field_value), $object->id
            );


            $res = mysql_query($sql);
            if ($row = mysql_fetch_assoc($res)) {
                $prev_key   = $row['object_key'];
                $prev_title = _("Payment option").' '.$row['object_name'].' ('.$row['object_key'].')';

            }

            $sql = sprintf(
                "select `Payment Account Name` object_name,PA.`Payment Account Key` as object_key from $table   $where $wheref
	                and ($_order_field  > %s OR ($_order_field  = %s AND PA.`Payment Account Key` > %d))  order by $_order_field   , PA.`Payment Account Key`  limit 1",
                prepare_mysql($_order_field_value), prepare_mysql($_order_field_value), $object->id
            );


            $res = mysql_query($sql);
            if ($row = mysql_fetch_assoc($res)) {
                $next_key   = $row['object_key'];
                $next_title = _("Payment option").' '.$row['object_name'].' ('.$row['object_key'].')';

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


function get_payment_accounts_navigation($data, $user, $smarty) {


    $left_buttons  = array();
    $right_buttons = array();


    switch ($data['parent']) {
        case 'account':
            $tab      = 'payment_accounts';
            $_section = 'payment_accounts';

            $sections                                 = get_sections(
                'payments', 'all'
            );
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

            $sections     = get_sections('payments', $store->id);
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


function get_payment_navigation($data) {

    global $smarty, $user;

    $object        = $data['_object'];
    $left_buttons  = array();
    $right_buttons = array();

    if ($data['parent']) {

        switch ($data['parent']) {
            case 'account':
                $tab      = 'payments';
                $_section = 'payments';
                break;
            case 'payment_service_provider':
                $tab      = 'payment_service_provider.payments';
                $_section = 'payments';
                break;
            case 'payment_account':
                $tab      = 'payment_account.payments';
                $_section = 'payments';
                break;
            case 'store':
                $tab      = 'payments';
                $_section = 'payments';
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
        $res2       = mysql_query($sql);

        if ($row2 = mysql_fetch_assoc($res2) and $row2['num'] > 1) {

            $sql = sprintf(
                "select `Payment Key` object_name,P.`Payment Key` as object_key from $table   $where $wheref
	                and ($_order_field < %s OR ($_order_field = %s AND P.`Payment Key` < %d))  order by $_order_field desc , P.`Payment Key` desc limit 1",

                prepare_mysql($_order_field_value), prepare_mysql($_order_field_value), $object->id
            );

            $res = mysql_query($sql);
            if ($row = mysql_fetch_assoc($res)) {
                $prev_key   = $row['object_key'];
                $prev_title = _("Payment option").' '.$row['object_name'].' ('.$row['object_key'].')';

            }

            $sql = sprintf(
                "select `Payment Key` object_name,P.`Payment Key` as object_key from $table   $where $wheref
	                and ($_order_field  > %s OR ($_order_field  = %s AND P.`Payment Key` > %d))  order by $_order_field   , P.`Payment Key`  limit 1", prepare_mysql($_order_field_value),
                prepare_mysql($_order_field_value), $object->id
            );


            $res = mysql_query($sql);
            if ($row = mysql_fetch_assoc($res)) {
                $next_key   = $row['object_key'];
                $next_title = _("Payment option").' '.$row['object_name'].' ('.$row['object_key'].')';

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

        if ($data['parent'] == 'account') {


            $up_button = array(
                'icon'      => 'arrow-up',
                'title'     => _("Account payments"),
                'reference' => 'account'
            );

            if ($prev_key) {
                $left_buttons[] = array(
                    'icon'      => 'arrow-left',
                    'title'     => $prev_title,
                    'reference' => 'account/payment/'.$prev_key
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
                    'reference' => 'account/payment/'.$next_key
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

    $sections = get_sections('payments', '');


    $sections[$_section]['selected'] = true;


    $title = _('Payment').' <span class="id">'.$data['_object']->get(
            'Payment Key'
        ).' '.($data['_object']->get('Payment Transaction ID') != '' ? '('.$data['_object']->get('Payment Transaction ID').')' : '').' </span>';


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


?>
