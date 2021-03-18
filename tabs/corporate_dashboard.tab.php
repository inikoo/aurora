<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 3:44 pm Saturday, 13 February 2021 (MYT) Time in Kuala Lumpur, Federal Territory of Kuala Lumpur, Malaysia

 Copyright (c) 2021, Inikoo

 Version 3.0
*/

$account->load_acc_data();
$html = '';
foreach ($user->get_corporate_dashboard_items() as $item) {

    if ($item == 'accounts_overview') {

        $period = '1y';

        include_once 'widgets/accounts_overview.wget.php';

        if (isset($_SESSION['dashboard_state']['accounts_overview']['type'])) {
            $type = $_SESSION['dashboard_state']['accounts_overview']['type'];
        } else {
            $type = 'invoices';
        }

        if (isset($_SESSION['dashboard_state']['accounts_overview']['$sub_type'])) {
            $sub_type = $_SESSION['dashboard_state']['accounts_overview']['$sub_type'];
        } else {

            if ($type == 'invoices' or $type == 'invoice_categories') {
                $sub_type = 'sales';

            } else {
                $sub_type = '';
            }
        }


        if (isset($_SESSION['dashboard_state']['accounts_overview']['period'])) {
            $period = $_SESSION['dashboard_state']['accounts_overview']['period'];
        } else {
            $period = 'ytd';
        }
        if (isset($_SESSION['dashboard_state']['accounts_overview']['currency'])) {
            $currency = $_SESSION['dashboard_state']['accounts_overview']['currency'];
        } else {
            $currency = 'account';
        }
        if (isset($_SESSION['dashboard_state']['accounts_overview']['orders_view_type'])) {
            $orders_view_type = $_SESSION['dashboard_state']['accounts_overview']['orders_view_type'];
        } else {
            $orders_view_type = 'numbers';
        }


        $html .= get_dashboard_accounts_overview($db, $redis,$account, $user, $smarty, $type, $sub_type, $period, $currency, $orders_view_type, (!empty($_SESSION['display_device_version']) ? $_SESSION['display_device_version'] : 'desktop'));

    }


}



