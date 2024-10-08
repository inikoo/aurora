<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 22 June 2018 at 15:08:31 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/

require_once __DIR__.'/cron_common.php';
require_once 'utils/new_fork.php';
/** @var PDO $db */
/** @var Redis $redis */
/** @var Account $account */


$time = gmdate('H:i');

real_time_users_operations($db, $redis, $account);
send_periodic_email_mailshots($time, $db, $account);
send_second_wave_mailshots( $db, $account);
send_scheduled_mailshots( $db, $account);

switch ($time) {
    case '00:00':


        $db->exec("update `Order Dimension`set  `Order Replacements Dispatched Today`=0 ");


        new_housekeeping_fork(
            'au_long_operations', array(
            'type' => 'redo_day_ISF',
            'date' => gmdate('Y-m-d')

        ), $account->get('Account Code')
        );

        new_housekeeping_fork(
            'au_long_operations', array(
            'type' => 'update_deals_status_from_dates',

        ), $account->get('Account Code')
        );

        new_housekeeping_fork(
            'au_long_operations', array(
            'type' => 'create_yesterday_timeseries',

        ), $account->get('Account Code')
        );


        update_staff_attendance($db);

        $account->load_acc_data();
        $account->update_orders();
        $account->update_orders_dispatched_today();

        $account->update(
            array(
                'Account Today Start Orders In Warehouse Number' => $account->get('Account Orders In Warehouse Number') + $account->get('Account Orders Packed Number') + $account->get('Account Orders Dispatch Approved Number')
            )

        );


        $sql = "SELECT `Store Key` FROM `Store Dimension`";
        if ($result = $db->query($sql)) {
            foreach ($result as $row) {
                /** @var Store $store */
                $store = get_object('Store', $row['Store Key']);
                $store->load_acc_data();
                $store->update_orders();
                $store->fast_update(
                    array('Store Today Start Orders In Warehouse Number' => $store->get('Store Orders In Warehouse Number') + $store->get('Store Orders Packed Number') + $store->get('Store Orders Dispatch Approved Number'))

                );

                $store->update_new_products();
                $store->update_orders_dispatched_today();

            }
        }

        $msg = new_housekeeping_fork(
            'au_asset_sales', array(
            'type'     => 'update_stores_sales_data',
            'interval' => 'Today',
            'mode'     => array(
                true,
                true
            )
        ), $account->get('Account Code')
        );

        $msg = new_housekeeping_fork(
            'au_asset_sales', array(
            'type'     => 'update_invoices_categories_sales_data',
            'interval' => 'Today',
            'mode'     => array(
                true,
                true
            )
        ), $account->get('Account Code')
        );


        $msg = new_housekeeping_fork(
            'au_asset_sales', array(
            'type'     => 'update_stores_sales_data',
            'interval' => 'Yesterday',
            'mode'     => array(
                true,
                true
            )
        ), $account->get('Account Code')
        );

        $msg = new_housekeeping_fork(
            'au_asset_sales', array(
            'type'     => 'update_invoices_categories_sales_data',
            'interval' => 'Yesterday',
            'mode'     => array(
                true,
                true
            )
        ), $account->get('Account Code')
        );


        $intervals = array(
            'Year To Day',
            'Quarter To Day',
            'Month To Day',
            'Week To Day',

        );
        foreach ($intervals as $interval) {


            $msg = new_housekeeping_fork(
                'au_asset_sales', array(
                'type'     => 'update_stores_sales_data',
                'interval' => $interval,
                'mode'     => array(
                    false,
                    true
                )
            ), $account->get('Account Code')
            );

            $msg = new_housekeeping_fork(
                'au_asset_sales', array(
                'type'     => 'update_invoices_categories_sales_data',
                'interval' => $interval,
                'mode'     => array(
                    false,
                    true
                )
            ), $account->get('Account Code')
            );


            $msg = new_housekeeping_fork(
                'au_asset_sales', array(
                'type'     => 'update_products_sales_data',
                'interval' => $interval,
                'mode'     => array(
                    false,
                    true
                )
            ), $account->get('Account Code')
            );

            $msg = new_housekeeping_fork(
                'au_asset_sales', array(
                'type'     => 'update_parts_sales_data',
                'interval' => $interval,
                'mode'     => array(
                    false,
                    true
                )
            ), $account->get('Account Code')
            );

            $msg = new_housekeeping_fork(
                'au_asset_sales', array(
                'type'     => 'update_part_categories_sales_data',
                'interval' => $interval,
                'mode'     => array(
                    false,
                    true
                )
            ), $account->get('Account Code')
            );

            $msg = new_housekeeping_fork(
                'au_asset_sales', array(
                'type'     => 'update_product_categories_sales_data',
                'interval' => $interval,
                'mode'     => array(
                    false,
                    true
                )
            ), $account->get('Account Code')
            );


            $msg = new_housekeeping_fork(
                'au_asset_sales', array(
                'type'     => 'update_suppliers_data',
                'interval' => $interval,
                'mode'     => array(
                    false,
                    true
                )
            ), $account->get('Account Code')
            );


            $msg = new_housekeeping_fork(
                'au_asset_sales', array(
                'type'     => 'update_supplier_categories_sales_data',
                'interval' => $interval,
                'mode'     => array(
                    false,
                    true
                )
            ), $account->get('Account Code')
            );


        }


        $intervals = array(

            '1 Year',
            '1 Quarter',
            '1 Month',
            '1 Week'
        );
        foreach ($intervals as $interval) {


            $msg = new_housekeeping_fork(
                'au_asset_sales', array(
                'type'     => 'update_stores_sales_data',
                'interval' => $interval,
                'mode'     => array(
                    true,
                    true
                )
            ), $account->get('Account Code')
            );

            $msg = new_housekeeping_fork(
                'au_asset_sales', array(
                'type'     => 'update_invoices_categories_sales_data',
                'interval' => $interval,
                'mode'     => array(
                    true,
                    true
                )
            ), $account->get('Account Code')
            );


            $msg = new_housekeeping_fork(
                'au_asset_sales', array(
                'type'     => 'update_products_sales_data',
                'interval' => $interval,
                'mode'     => array(
                    true,
                    true
                )
            ), $account->get('Account Code')
            );

            $msg = new_housekeeping_fork(
                'au_asset_sales', array(
                'type'     => 'update_parts_sales_data',
                'interval' => $interval,
                'mode'     => array(
                    true,
                    true
                )
            ), $account->get('Account Code')
            );

            $msg = new_housekeeping_fork(
                'au_asset_sales', array(
                'type'     => 'update_part_categories_sales_data',
                'interval' => $interval,
                'mode'     => array(
                    true,
                    true
                )
            ), $account->get('Account Code')
            );

            $msg = new_housekeeping_fork(
                'au_asset_sales', array(
                'type'     => 'update_product_categories_sales_data',
                'interval' => $interval,
                'mode'     => array(
                    true,
                    true
                )
            ), $account->get('Account Code')
            );


            $msg = new_housekeeping_fork(
                'au_asset_sales', array(
                'type'     => 'update_suppliers_data',
                'interval' => $interval,
                'mode'     => array(
                    true,
                    true
                )
            ), $account->get('Account Code')
            );


            $msg = new_housekeeping_fork(
                'au_asset_sales', array(
                'type'     => 'update_supplier_categories_sales_data',
                'interval' => $interval,
                'mode'     => array(
                    false,
                    true
                )
            ), $account->get('Account Code')
            );


        }
        break;
    case '01:00':

        $sql = 'SELECT `Part SKU` FROM `Part Dimension`  ORDER BY `Part SKU`  DESC ';

        if ($result = $db->query($sql)) {
            foreach ($result as $row) {
                /** @var Part $part */
                $part = get_object('Part', $row['Part SKU']);
                $part->update_next_deliveries_data();

            }

        } else {
            print_r($error_info = $db->errorInfo());
            exit;

        }
        break;
    case '01:30':
        //just in case
        update_staff_attendance($db);
        break;
    case '03:00':
        new_housekeeping_fork(
            'au_long_operations', array(
            'type' => 'redo_day_ISF',
            'date' => gmdate('Y-m-d', strtotime('yesterday +0000'))

        ), $account->get('Account Code')
        );
        break;
    case '04:00':
        $sql  = "select `Customer Key` from `Customer Dimension` where `Customer Type by Activity`!='Lost' ";
        $stmt = $db->prepare($sql);
        $stmt->execute(
            array()
        );
        while ($row = $stmt->fetch()) {
            /** @var Customer $customer */
            $customer = get_object('Customer', $row['Customer Key']);
            if ($customer->id) {
                $customer->update_orders();
                $customer->update_activity();
            }

        }
        $sql  = "select `Store Key` from `Store Dimension`   ";
        $stmt = $db->prepare($sql);
        $stmt->execute(
            array()
        );
        while ($row = $stmt->fetch()) {
            $store = get_object('Store', $row['Store Key']);
            $store->update_customers_data();

        }

        $sql  = "select `Website Key` from `Website Dimension`   ";
        $stmt = $db->prepare($sql);
        $stmt->execute(
            array()
        );
        while ($row = $stmt->fetch()) {
            /** @var Website $website */
            $website = get_object('Website', $row['Website Key']);
            $website->update_sitemap();

        }
        break;

    case '05:00':

        new_housekeeping_fork(
            'au_housekeeping', array(
            'type' => 'update_parts_cost'
        ), $account->get('Account Code')
        );
        break;

    case '22:00':
        new_housekeeping_fork(
            'au_housekeeping', array(
            'type' => 'update_active_parts_commercial_value'
        ), $account->get('Account Code')
        );
        break;


    default:


        break;
}



function real_time_users_operations($db, $redis, $account) {


    require_once 'utils/real_time_functions.php';

    $redis->zRemRangeByScore('_IU'.$account->get('Code'), 0, gmdate('U') - 600);

    $real_time_users = get_users_read_time_data($redis, $account);


    $sql  = 'select `Website Key` from `Website Dimension`';
    $stmt = $db->prepare($sql);
    $stmt->execute(
        array()
    );

    $objects_data            = array();
    $real_time_website_users = array();
    while ($row = $stmt->fetch()) {


        $items_to_delete = $redis->zRangeByScore('_WU'.$account->get('Code').'|'.$row['Website Key'], 0, gmdate('U') - 300, ['withscores' => true]);

        foreach ($items_to_delete as $key => $value) {

            $customer_key = preg_replace('/^.*\|/', '', $key);
            if ($customer_key > 0) {


               // $customer_web_info_log_out = '<span class="italic discreet">'._('Customer not logged in').'</span>';

                $last_visit                = strftime("%H:%M:%S %Z", $value);
                //$customer_web_info_log_out .= ' <span class="small italic discreet padding_left_5">('.sprintf('Last seen %s', $last_visit).')</span>';


                $objects_data[] = array(
                    'object'          => 'customer',
                    'key'             => $customer_key,
                    'update_metadata' => array(
                        'class_html' => array(
                            'customer_online_icon' => '<i title="'._('Offline').'" class="fal super_discreet fa-globe"></i>',
                            'customer_web_info'    => '<span class="italic discreet">'.$last_visit.'</span>'
                        )
                    )
                );
            }
        }


        //continue;

        $deleted_values = $redis->zRemRangeByScore('_WU'.$account->get('Code').'|'.$row['Website Key'], 0, gmdate('U') - 300);

        if ($deleted_values > 0) {
            $real_time_website_users_data = get_website_users_read_time_data($redis, $account, $row['Website Key']);

            $real_time_website_users[] = array(
                'type'        => 'current_website_users',
                'website_key' => $row['Website Key'],
                'data'        => $real_time_website_users_data
            );


        }


    }

    include_once 'utils/send_zqm_message.class.php';
    send_zqm_message(
        json_encode(
            array(
                'channel' => 'real_time.'.strtolower($account->get('Account Code')),

                'iu'      => $real_time_users,
                'd3'      => $real_time_website_users,
                'objects' => $objects_data

            )
        )
    );


}



function send_periodic_email_mailshots($time, $db, $account) {

    $sql  = "select `Email Campaign Type Code`,`Email Campaign Type Metadata`,`Email Campaign Type Key` from `Email Campaign Type Dimension` where `Email Campaign Type Status`='Active' ";
    $stmt = $db->prepare($sql);
    $stmt->execute(
        array()
    );
    while ($row = $stmt->fetch()) {
        if ($row['Email Campaign Type Metadata'] != '') {
            $metadata = json_decode($row['Email Campaign Type Metadata'], true);

            if (isset($metadata['Schedule'])) {

                date_default_timezone_set($metadata['Schedule']['Timezone']);


                if ($metadata['Schedule']['Time'] == $time.':00') {
                    if (isset($metadata['Schedule']['Days'])) {
                        if ($metadata['Schedule']['Days'][iso_860_to_day_name(date('N'))] == 'Yes') {

                            new_housekeeping_fork(
                                'au_send_mailshots', array(
                                'type'                    => 'create_and_send_mailshot',
                                'email_template_type_key' => $row['Email Campaign Type Key'],

                            ), $account->get('Account Code')
                            );


                        }
                    }
                }


            }


        }
    }


}


function iso_860_to_day_name($num) {
    switch ($num) {
        case 1:
            return 'Monday';
        case 2:
            return 'Tuesday';
        case 3:
            return 'Wednesday';
        case 4:
            return 'Thursday';
        case 5:
            return 'Friday';
        case 6:
            return 'Saturday';
        case 7:
            return 'Sunday';
        default:
            break;
    }

}


function update_staff_attendance($db) {
    $sql  = "SELECT `Staff Key` FROM `Staff Dimension` where `Staff Currently Working`='Yes'";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    while ($row = $stmt->fetch()) {
        /** @var Staff $staff */
        $staff = get_object('Staff', $row['Staff Key']);
        $staff->update_attendance();
    }


}

function send_second_wave_mailshots( $db, $account) {

    $sql  = "select `Email Campaign Key` from `Email Campaign Dimension` where `Email Campaign Wave Type`='Yes' and `Email Campaign Second Wave Date`<=NOW() and `Email Campaign State`='Sent' ";
    $stmt = $db->prepare($sql);
    $stmt->execute(
        array()
    );
    while ($row = $stmt->fetch()) {

       

        new_housekeeping_fork(
            'au_send_mailshots', array(
            'type'                    => 'create_and_send_second_wave_mailshot',
            'mailshot_key' => $row['Email Campaign Key'],

        ), $account->get('Account Code')
        );



    }


}

function send_scheduled_mailshots( $db, $account) {

    $editor = array(
        'Author Type'  => '',
        'Author Key'   => '',
        'User Key'     => 0,
        'Date'         => gmdate('Y-m-d H:i:s'),
        'Subject'      => 'System',
        'Subject Key'  => 0,
        'Author Name'  => 'System (Scheduled mailshot)',
        'Author Alias' => 'System (Scheduled mailshot)',
        'v'            => 3


    );


    $sql  = "select `Email Campaign Key` from `Email Campaign Dimension` where `Email Campaign State`='Scheduled' and `Email Campaign Scheduled Date`<=NOW()  ";
    $stmt = $db->prepare($sql);
    $stmt->execute(
        array()
    );
    while ($row = $stmt->fetch()) {

        $mailshot = get_object('Email_Campaign', $row['Email Campaign Key']);
        $mailshot->update_state('Sending');

        new_housekeeping_fork(
            'au_send_mailshots', array(
            'type'         => 'send_mailshot',
            'mailshot_key' => $mailshot->id,
            'editor'      => $editor

        ), $account->get('Account Code')
        );
    }

}