<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 14 December 2015 at 12:35:17 GMT, Sheffield UK

 Copyright (c) 2015, Inikoo

 Version 3.0
*/

$account->load_acc_data();
$html = '';
foreach ($user->get_dashboard_items() as $item) {

    if ($item == 'sales_overview') {

        $period = '1y';

        include_once 'widgets/sales_overview.wget.php';

        if (isset($_SESSION['dashboard_state']['sales_overview']['type'])) {
            $type = $_SESSION['dashboard_state']['sales_overview']['type'];
        } else {
            $type = 'invoices';
        }

        if (isset($_SESSION['dashboard_state']['sales_overview']['$sub_type'])) {
            $sub_type = $_SESSION['dashboard_state']['sales_overview']['$sub_type'];
        } else {

            if ($type == 'invoices' or $type == 'invoice_categories') {
                $sub_type = 'sales';

            } else {
                $sub_type = '';
            }
        }


        if (isset($_SESSION['dashboard_state']['sales_overview']['period'])) {
            $period = $_SESSION['dashboard_state']['sales_overview']['period'];
        } else {
            $period = 'ytd';
        }
        if (isset($_SESSION['dashboard_state']['sales_overview']['currency'])) {
            $currency = $_SESSION['dashboard_state']['sales_overview']['currency'];
        } else {
            $currency = 'account';
        }
        if (isset($_SESSION['dashboard_state']['sales_overview']['orders_view_type'])) {
            $orders_view_type = $_SESSION['dashboard_state']['sales_overview']['orders_view_type'];
        } else {
            $orders_view_type = 'numbers';
        }


        $html .= get_dashboard_sales_overview($db, $account, $user, $smarty, $type, $sub_type, $period, $currency, $orders_view_type, (!empty($_SESSION['display_device_version'])?$_SESSION['display_device_version']:'desktop'));

    } elseif ($item == 'pending_orders_and_customers') {

        $period = '1y';

        include_once 'widgets/pending_orders.wget.php';
        include_once 'widgets/customers.wget.php';

        if (isset($_SESSION['dashboard_state']['pending_orders']['parent'])) {
            $parent = $_SESSION['dashboard_state']['pending_orders']['parent'];
        } else {
            $parent = '';
        }

        if (isset($_SESSION['dashboard_state']['pending_orders']['currency'])) {
            $currency = $_SESSION['dashboard_state']['pending_orders']['currency'];
        } else {
            $currency = 'account';
        }


        $html .= get_dashboard_pending_orders($db, $account, $user, $smarty, $parent, $currency, (!empty($_SESSION['display_device_version'])?$_SESSION['display_device_version']:'desktop'));
        $html .= get_dashboard_customers($db, $account, $user, $smarty, $parent, $currency, (!empty($_SESSION['display_device_version'])?$_SESSION['display_device_version']:'desktop'));

    } elseif ($item == 'inventory_warehouse') {


        include_once 'widgets/parts_stock_status.wget.php';
        include_once 'widgets/inventory.wget.php';

        $currency = 'account';


        if (isset($_SESSION['dashboard_state']['parts_stock_status']['parent'])) {
            $parent = $_SESSION['dashboard_state']['parts_stock_status']['parent'];
        } else {

            $parent = '';


        }


        $html .= get_dashboard_parts_stock_status($user, $smarty, $parent, (!empty($_SESSION['display_device_version'])?$_SESSION['display_device_version']:'desktop'));
        $html .= get_dashboard_inventory($user, $smarty, $parent, (!empty($_SESSION['display_device_version'])?$_SESSION['display_device_version']:'desktop'));

    } elseif ($item == 'kpis') {

        $period = '1y';

        include_once 'widgets/kpis.wget.php';

        if (isset($_SESSION['dashboard_state']['kpis']['period'])) {
            $period = $_SESSION['dashboard_state']['kpis']['period'];
        } else {
            $period = 'mtd';
        }


        $html .= get_dashboard_kpis($db, $account, $user, $smarty, $period, (!empty($_SESSION['display_device_version'])?$_SESSION['display_device_version']:'desktop'));

    }elseif ($item == 'dispatching_times') {


        $period = '1y';

        include_once 'widgets/dispatching_times.wget.php';

        if (isset($_SESSION['dashboard_state']['dispatching_times']['parent'])) {
            $parent = $_SESSION['dashboard_state']['dispatching_times']['parent'];
        } else {
            $parent = '';
        }




        $html .= get_dashboard_dispatching_times($db, $account, $user, $smarty, $parent, (!empty($_SESSION['display_device_version'])?$_SESSION['display_device_version']:'desktop'));


    }

}



