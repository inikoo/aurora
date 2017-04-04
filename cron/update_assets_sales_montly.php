<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 31 October 2016 at 09:54:48 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'utils/aes.php';
require_once 'utils/new_fork.php';
require_once 'conf/timeseries.php';


$default_DB_link = @mysql_connect($dns_host, $dns_user, $dns_pwd);
if (!$default_DB_link) {
    print "Error can not connect with database server\n";
}
$db_selected = mysql_select_db($dns_db, $default_DB_link);
if (!$db_selected) {
    print "Error can not access the database\n";
    exit;
}
mysql_set_charset('utf8');
mysql_query("SET time_zone='+0:00'");


require_once 'class.Product.php';
require_once 'class.Category.php';
require_once 'class.Timeserie.php';
require_once 'class.Store.php';
require_once 'class.Part.php';

$timeseries=get_time_series_config();


$editor = array(
    'Author Name'  => '',
    'Author Alias' => '',
    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => 0,
    'Date'         => gmdate('Y-m-d H:i:s')
);

//shortcuts($db);





$intervals = array(
    'Last Month',
    'Month To Day'
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




?>
