<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 4 October 2016 at 23:37:31 GMT+8, Kuala Lumpur, Malaysia
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


$editor = array(
    'Author Name'  => '',
    'Author Alias' => '',
    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => 0,
    'Date'         => gmdate('Y-m-d H:i:s')
);

$timeseries=get_time_series_config();


$sql = sprintf('SELECT `Category Key` FROM `Category Dimension` WHERE `Category Scope`="Part" ORDER BY  `Category Key` DESC');

if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        $category = new Category($row['Category Key']);
        if ($category->get('Part Category Status') != 'NotInUse' or date('Y-m-d') == date('Y-m-d', strtotime($category->get('Part Category Valid To').' +0:00'))) {
            if (!array_key_exists($category->get('Category Scope').'Category', $timeseries)) {
                continue;
            }

            $timeseries_data = $timeseries[$category->get('Category Scope').'Category'];
            //print_r($timeseries_data);
            foreach ($timeseries_data as $timeserie_data) {

                $editor['Date']                          = gmdate('Y-m-d H:i:s');
                $timeserie_data['editor']                = $editor;
                $timeserie_data['Timeseries Parent']     = 'Category';
                $timeserie_data['Timeseries Parent Key'] = $category->id;
                $timeseries                              = new Timeseries(
                    'find', $timeserie_data, 'create'
                );
                $category->update_part_timeseries_record($timeseries, gmdate('Y-m-d', strtotime('now -1 day')), gmdate('Y-m-d', strtotime('now -1 day')));
            }
        }
    }

} else {
    print_r($error_info = $db->errorInfo());
    print $sql;
    exit;
}


$sql = sprintf('SELECT `Category Key` FROM `Category Dimension` WHERE `Category Scope`="Product" ORDER BY  `Category Key` DESC');

if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        $category = new Category($row['Category Key']);
        $category->update_product_category_new_products();
        if ($category->get('Product Category Status') != 'Discontinued' or date('Y-m-d') == date('Y-m-d', strtotime($category->get('Product Category Valid To').' +0:00'))) {
            if (!array_key_exists($category->get('Category Scope').'Category', $timeseries)) {
                continue;
            }

            $timeseries_data = $timeseries[$category->get('Category Scope').'Category'];
            //print_r($timeseries_data);
            foreach ($timeseries_data as $timeserie_data) {

                $editor['Date']                          = gmdate('Y-m-d H:i:s');
                $timeserie_data['editor']                = $editor;
                $timeserie_data['Timeseries Parent']     = 'Category';
                $timeserie_data['Timeseries Parent Key'] = $category->id;
                $timeseries                              = new Timeseries('find', $timeserie_data, 'create');
                $category->update_product_timeseries_record($timeseries, gmdate('Y-m-d', strtotime('now -1 day')), gmdate('Y-m-d', strtotime('now -1 day')));
            }
        }
    }

} else {
    print_r($error_info = $db->errorInfo());
    print $sql;
    exit;
}


$sql = sprintf('UPDATE `Store Data` SET `Store Yesterday Acc Invoiced Discount Amount`=`Store Today Acc Invoiced Discount Amount` ,`Store Today Acc Invoiced Discount Amount`=0  ');
$db->exec($sql);
$sql = sprintf('UPDATE `Store Data` SET `Store Yesterday Acc Invoiced Amount`=`Store Today Acc Invoiced Amount` ,`Store Today Acc Invoiced Amount`=0  ');
$db->exec($sql);
$sql = sprintf('UPDATE `Store Data` SET `Store Yesterday Acc Profit`=`Store Today Acc Profit` ,`Store Today Acc Profit`=0  ');
$db->exec($sql);
$sql = sprintf('UPDATE `Store Data` SET `Store Yesterday Acc Orders`=`Store Today Acc Orders` ,`Store Today Acc Orders`=0  ');
$db->exec($sql);
$sql = sprintf('UPDATE `Store Data` SET `Store Yesterday Acc Invoices`=`Store Today Acc Invoices` ,`Store Today Acc Invoices`=0  ');
$db->exec($sql);
$sql = sprintf('UPDATE `Store Data` SET `Store Yesterday Acc Replacements`=`Store Today Acc Replacements` ,`Store Today Acc Replacements`=0  ');
$db->exec($sql);
$sql = sprintf('UPDATE `Store Data` SET `Store Yesterday Acc Delivery Notes`=`Store Today Acc Delivery Notes` ,`Store Today Acc Delivery Notes`=0  ');
$db->exec($sql);
$sql = sprintf('UPDATE `Store Data` SET `Store Yesterday Acc Refunds`=`Store Today Acc Refunds` ,`Store Today Acc Refunds`=0  ');
$db->exec($sql);
$sql = sprintf('UPDATE `Store Data` SET `Store Yesterday Acc Contacts`=`Store Today Acc Contacts` ,`Store Today Acc Contacts`=0  ');
$db->exec($sql);
$sql = sprintf('UPDATE `Store Data` SET `Store Yesterday Acc Customers`=`Store Today Acc Customers` ,`Store Today Acc Customers`=0  ');
$db->exec($sql);
$sql = sprintf('UPDATE `Store Data` SET `Store Yesterday Acc Repeat Customers`=`Store Today Acc Repeat Customers` ,`Store Today Acc Repeat Customers`=0  ');
$db->exec($sql);
$sql = sprintf('UPDATE `Store Data` SET `Store Yesterday Acc Lost Contacts`=`Store Today Acc Lost Contacts` ,`Store Today Acc Lost Contacts`=0  ');
$db->exec($sql);
$sql = sprintf('UPDATE `Store Data` SET `Store Yesterday Acc Lost Customers`=`Store Today Acc Lost Customers` ,`Store Today Acc Lost Customers`=0  ');
$db->exec($sql);
$sql = sprintf('UPDATE `Store Data` SET `Store Yesterday Acc Average Dispatch Time`=`Store Today Acc Average Dispatch Time` ,`Store Today Acc Average Dispatch Time`=0  ');
$db->exec($sql);

$sql = sprintf('UPDATE `Store Data` SET `Store Yesterday Acc 1YB Invoiced Discount Amount`=`Store Today Acc 1YB Invoiced Discount Amount` ,`Store Today Acc 1YB Invoiced Discount Amount`=0  ');
$db->exec($sql);
$sql = sprintf('UPDATE `Store Data` SET `Store Yesterday Acc 1YB Invoiced Amount`=`Store Today Acc 1YB Invoiced Amount` ,`Store Today Acc 1YB Invoiced Amount`=0  ');
$db->exec($sql);
$sql = sprintf('UPDATE `Store Data` SET `Store Yesterday Acc 1YB Profit`=`Store Today Acc 1YB Profit` ,`Store Today Acc 1YB Profit`=0  ');
$db->exec($sql);
$sql = sprintf('UPDATE `Store Data` SET `Store Yesterday Acc 1YB Orders`=`Store Today Acc 1YB Orders` ,`Store Today Acc 1YB Orders`=0  ');
$db->exec($sql);
$sql = sprintf('UPDATE `Store Data` SET `Store Yesterday Acc 1YB Invoices`=`Store Today Acc 1YB Invoices` ,`Store Today Acc 1YB Invoices`=0  ');
$db->exec($sql);
$sql = sprintf('UPDATE `Store Data` SET `Store Yesterday Acc 1YB Replacements`=`Store Today Acc 1YB Replacements` ,`Store Today Acc 1YB Replacements`=0  ');
$db->exec($sql);
$sql = sprintf('UPDATE `Store Data` SET `Store Yesterday Acc 1YB Delivery Notes`=`Store Today Acc 1YB Delivery Notes` ,`Store Today Acc 1YB Delivery Notes`=0  ');
$db->exec($sql);
$sql = sprintf('UPDATE `Store Data` SET `Store Yesterday Acc 1YB Refunds`=`Store Today Acc 1YB Refunds` ,`Store Today Acc 1YB Refunds`=0  ');
$db->exec($sql);
$sql = sprintf('UPDATE `Store Data` SET `Store Yesterday Acc 1YB Contacts`=`Store Today Acc 1YB Contacts` ,`Store Today Acc 1YB Contacts`=0  ');
$db->exec($sql);
$sql = sprintf('UPDATE `Store Data` SET `Store Yesterday Acc 1YB Customers`=`Store Today Acc 1YB Customers` ,`Store Today Acc 1YB Customers`=0  ');
$db->exec($sql);
$sql = sprintf('UPDATE `Store Data` SET `Store Yesterday Acc 1YB Repeat Customers`=`Store Today Acc 1YB Repeat Customers` ,`Store Today Acc 1YB Repeat Customers`=0  ');
$db->exec($sql);
$sql = sprintf('UPDATE `Store Data` SET `Store Yesterday Acc 1YB Lost Contacts`=`Store Today Acc 1YB Lost Contacts` ,`Store Today Acc 1YB Lost Contacts`=0  ');
$db->exec($sql);
$sql = sprintf('UPDATE `Store Data` SET `Store Yesterday Acc 1YB Lost Customers`=`Store Today Acc 1YB Lost Customers` ,`Store Today Acc 1YB Lost Customers`=0  ');
$db->exec($sql);
$sql = sprintf('UPDATE `Store Data` SET `Store Yesterday Acc 1YB Average Dispatch Time`=`Store Today Acc 1YB Average Dispatch Time` ,`Store Today Acc 1YB Average Dispatch Time`=0  ');
$db->exec($sql);


$sql = sprintf(
    'UPDATE `Store DC Data` SET `Store DC Yesterday Acc Invoiced Discount Amount`=`Store DC Today Acc Invoiced Discount Amount` ,`Store DC Today Acc Invoiced Discount Amount`=0  '
);
$db->exec($sql);
$sql = sprintf(
    'UPDATE `Store DC Data` SET `Store DC Yesterday Acc Invoiced Amount`=`Store DC Today Acc Invoiced Amount` ,`Store DC Today Acc Invoiced Amount`=0  '
);
$db->exec($sql);
$sql = sprintf(
    'UPDATE `Store DC Data` SET `Store DC Yesterday Acc Profit`=`Store DC Today Acc Profit` ,`Store DC Today Acc Profit`=0  '
);
$db->exec($sql);

$sql = sprintf(
    'UPDATE `Store DC Data` SET `Store DC Yesterday Acc 1YB Invoiced Discount Amount`=`Store DC Today Acc 1YB Invoiced Discount Amount` ,`Store DC Today Acc 1YB Invoiced Discount Amount`=0  '
);
$db->exec($sql);
$sql = sprintf(
    'UPDATE `Store DC Data` SET `Store DC Yesterday Acc 1YB Invoiced Amount`=`Store DC Today Acc 1YB Invoiced Amount` ,`Store DC Today Acc 1YB Invoiced Amount`=0  '
);
$db->exec($sql);
$sql = sprintf(
    'UPDATE `Store DC Data` SET `Store DC Yesterday Acc 1YB Profit`=`Store DC Today Acc 1YB Profit` ,`Store DC Today Acc 1YB Profit`=0  '
);
$db->exec($sql);


$sql = sprintf("SELECT `Store Key` FROM `Store Dimension`");
if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        $store = new Store('id', $row['Store Key']);

        $store->load_acc_data();
        $store->update_sales_from_invoices('Today', false, true);
        $store->update_new_products();
    }
} else {
    print_r($error_info = $db->errorInfo());
    exit;
}


$sql = sprintf('UPDATE `Invoice Category Data` SET `Invoice Category Yesterday Acc Discount Amount`=`Invoice Category Today Acc Discount Amount` ,`Invoice Category Today Acc Discount Amount`=0  ');
$db->exec($sql);
$sql = sprintf('UPDATE `Invoice Category Data` SET `Invoice Category Yesterday Acc Amount`=`Invoice Category Today Acc Amount` ,`Invoice Category Today Acc Amount`=0  ');
$db->exec($sql);
$sql = sprintf('UPDATE `Invoice Category Data` SET `Invoice Category Yesterday Acc Refunded Amount`=`Invoice Category Today Acc Refunded Amount` ,`Invoice Category Today Acc Refunded Amount`=0  ');
$db->exec($sql);
$sql = sprintf('UPDATE `Invoice Category Data` SET `Invoice Category Yesterday Acc Invoices`=`Invoice Category Today Acc Invoices` ,`Invoice Category Today Acc Invoices`=0  ');
$db->exec($sql);
$sql = sprintf('UPDATE `Invoice Category Data` SET `Invoice Category Yesterday Acc Refunds`=`Invoice Category Today Acc Refunds` ,`Invoice Category Today Acc Refunds`=0  ');
$db->exec($sql);

$sql = sprintf('UPDATE `Invoice Category DC Data` SET `Invoice Category DC Yesterday Acc Discount Amount`=`Invoice Category DC Today Acc Discount Amount` ,`Invoice Category DC Today Acc Discount Amount`=0  ');
$db->exec($sql);
$sql = sprintf('UPDATE `Invoice Category DC Data` SET `Invoice Category DC Yesterday Acc Amount`=`Invoice Category DC Today Acc Amount` ,`Invoice Category DC Today Acc Amount`=0  ');
$db->exec($sql);
$sql = sprintf('UPDATE `Invoice Category DC Data` SET `Invoice Category DC Yesterday Acc Refunded Amoun`=`Invoice Category DC Today Acc Refunded Amoun` ,`Invoice Category DC Today Acc Refunded Amoun`=0  ');
$db->exec($sql);
$sql = sprintf('UPDATE `Invoice Category DC Data` SET `Invoice Category DC Yesterday Acc Profit`=`Invoice Category DC Today Acc Profit` ,`Invoice Category DC Today Acc Profit`=0  ');
$db->exec($sql);

$sql = sprintf('UPDATE `Invoice Category Data` SET `Invoice Category Yesterday Acc 1YB Discount Amount`=`Invoice Category Today Acc 1YB Discount Amount` ,`Invoice Category Today Acc 1YB Discount Amount`=0  ');
$db->exec($sql);
$sql = sprintf('UPDATE `Invoice Category Data` SET `Invoice Category Yesterday Acc 1YB Amount`=`Invoice Category Today Acc 1YB Amount` ,`Invoice Category Today Acc 1YB Amount`=0  ');
$db->exec($sql);
$sql = sprintf('UPDATE `Invoice Category Data` SET `Invoice Category Yesterday Acc 1YB Refunded Amount`=`Invoice Category Today Acc 1YB Refunded Amount` ,`Invoice Category Today Acc 1YB Refunded Amount`=0  ');
$db->exec($sql);
$sql = sprintf('UPDATE `Invoice Category Data` SET `Invoice Category Yesterday Acc 1YB Invoices`=`Invoice Category Today Acc 1YB Invoices` ,`Invoice Category Today Acc 1YB Invoices`=0  ');
$db->exec($sql);
$sql = sprintf('UPDATE `Invoice Category Data` SET `Invoice Category Yesterday Acc 1YB Refunds`=`Invoice Category Today Acc 1YB Refunds` ,`Invoice Category Today Acc 1YB Refunds`=0  ');
$db->exec($sql);

$sql = sprintf('UPDATE `Invoice Category DC Data` SET `Invoice Category DC Yesterday Acc 1YB Discount Amount`=`Invoice Category DC Today Acc 1YB Discount Amount` ,`Invoice Category DC Today Acc 1YB Discount Amount`=0  ');
$db->exec($sql);
$sql = sprintf('UPDATE `Invoice Category DC Data` SET `Invoice Category DC Yesterday Acc 1YB Amount`=`Invoice Category DC Today Acc 1YB Amount` ,`Invoice Category DC Today Acc 1YB Amount`=0  ');
$db->exec($sql);
$sql = sprintf('UPDATE `Invoice Category DC Data` SET `Invoice Category DC Yesterday Acc 1YB Refunded Amoun`=`Invoice Category DC Today Acc 1YB Refunded Amoun` ,`Invoice Category DC Today Acc 1YB Refunded Amoun`=0  ');
$db->exec($sql);
$sql = sprintf('UPDATE `Invoice Category DC Data` SET `Invoice Category DC Yesterday Acc 1YB Profit`=`Invoice Category DC Today Acc 1YB Profit` ,`Invoice Category DC Today Acc 1YB Profit`=0  ');
$db->exec($sql);


$sql = sprintf('SELECT `Category Key` FROM `Category Dimension` WHERE `Category Scope`="Invoice"  ');

if ($result = $db->query($sql)) {
    foreach ($result as $row) {

        $category = new Category($row['Category Key']);

        $category->update_invoice_category_sales('Today', false, true);


    }

} else {
    print_r($error_info = $db->errorInfo());
    exit;
}


/*

$sql=sprintf('update `Product Data` set `Product Yesterday Acc Invoiced Amount`=`Product Today Acc Invoiced Amount` ,`Product Today Acc Invoiced Amount`=0  ');$db->exec($sql);
$sql=sprintf('update `Product Data` set `Product Yesterday Acc Profit`=`Product Today Acc Profit`, `Product Today Acc Profit`=0 ');$db->exec($sql);
$sql=sprintf('update `Product Data` set `Product Yesterday Acc Quantity Ordered`=`Product Today Acc Quantity Ordered`, `Product Today Acc Quantity Ordered`=0');$db->exec($sql);
$sql=sprintf('update `Product Data` set `Product Yesterday Acc Quantity Invoiced`=`Product Today Acc Quantity Invoiced`,`Product Today Acc Quantity Invoiced`=0');$db->exec($sql);
$sql=sprintf('update `Product Data` set `Product Yesterday Acc Quantity Delivered`=`Product Today Acc Quantity Delivered`');$db->exec($sql);
$sql=sprintf('update `Product Data` set `Product Yesterday Acc Days On Sale`=`Product Today Acc Days On Sale`,`Product Today Acc Days On Sale`=0');$db->exec($sql);
$sql=sprintf('update `Product Data` set `Product Yesterday Acc Days Available`=`Product Today Acc Days Available`,`Product Today Acc Days Available`=0');$db->exec($sql);
$sql=sprintf('update `Product Data` set `Product Yesterday Acc Invoices`=`Product Today Acc Invoices`,`Product Today Acc Invoices`=0');$db->exec($sql);
$sql=sprintf('update `Product Data` set `Product Yesterday Acc Customers`=`Product Today Acc Customers`,`Product Today Acc Customers`=0');$db->exec($sql);
$sql=sprintf('update `Product Data` set `Product Yesterday Acc Repeat Customers`=`Product Today Acc Repeat Customers`,`Product Today Acc Repeat Customers`=0');$db->exec($sql);

$sql=sprintf('update `Product DC Data` set `Product DC Yesterday Acc Invoiced Amount`=`Product DC Today Acc Invoiced Amount` ,`Product DC Today Acc Invoiced Amount`=0  ');$db->exec($sql);
$sql=sprintf('update `Product DC Data` set `Product DC Yesterday Acc Profit`=`Product DC Today Acc Profit`, `Product DC Today Acc Profit`=0 ');$db->exec($sql);

$sql=sprintf('update `Product Category Data` set `Product Category Yesterday Acc Invoiced Amount`=`Product Category Today Acc Invoiced Amount` ,`Product Category Today Acc Invoiced Amount`=0  ');$db->exec($sql);
$sql=sprintf('update `Product Category Data` set `Product Category Yesterday Acc Profit`=`Product Category Today Acc Profit`, `Product Category Today Acc Profit`=0 ');$db->exec($sql);
$sql=sprintf('update `Product Category Data` set `Product Category Yesterday Acc Quantity Ordered`=`Product Category Today Acc Quantity Ordered`, `Product Category Today Acc Quantity Ordered`=0');$db->exec($sql);
$sql=sprintf('update `Product Category Data` set `Product Category Yesterday Acc Quantity Invoiced`=`Product Category Today Acc Quantity Invoiced`,`Product Category Today Acc Quantity Invoiced`=0');$db->exec($sql);
$sql=sprintf('update `Product Category Data` set `Product Category Yesterday Acc Quantity Delivered`=`Product Category Today Acc Quantity Delivered`');$db->exec($sql);
$sql=sprintf('update `Product Category Data` set `Product Category Yesterday Acc Days On Sale`=`Product Category Today Acc Days On Sale`,`Product Category Today Acc Days On Sale`=0');$db->exec($sql);
$sql=sprintf('update `Product Category Data` set `Product Category Yesterday Acc Days Available`=`Product Category Today Acc Days Available`,`Product Category Today Acc Days Available`=0');$db->exec($sql);
$sql=sprintf('update `Product Category Data` set `Product Category Yesterday Acc Invoices`=`Product Category Today Acc Invoices`,`Product Category Today Acc Invoices`=0');$db->exec($sql);
$sql=sprintf('update `Product Category Data` set `Product Category Yesterday Acc Customers`=`Product Category Today Acc Customers`,`Product Category Today Acc Customers`=0');$db->exec($sql);
$sql=sprintf('update `Product Category Data` set `Product Category Yesterday Acc Repeat Customers`=`Product Category Today Acc Repeat Customers`,`Product Category Today Acc Repeat Customers`=0');$db->exec($sql);

$sql=sprintf('update `Product Category DC Data` set `Product Category DC Yesterday Acc Invoiced Amount`=`Product Category DC Today Acc Invoiced Amount` ,`Product Category DC Today Acc Invoiced Amount`=0  ');$db->exec($sql);
$sql=sprintf('update `Product Category DC Data` set `Product Category DC Yesterday Acc Profit`=`Product Category DC Today Acc Profit`, `Product Category DC Today Acc Profit`=0 ');$db->exec($sql);

$sql=sprintf('update `Part Data` set `Part Yesterday Acc Invoiced Amount`=`Part Today Acc Invoiced Amount` ,`Part Today Acc Invoiced Amount`=0  ');$db->exec($sql);
$sql=sprintf('update `Part Data` set `Part Yesterday Acc Profit`=`Part Today Acc Profit`, `Part Today Acc Profit`=0 ');$db->exec($sql);
$sql=sprintf('update `Part Data` set `Part Yesterday Acc Required`=`Part Today Acc Required`, `Part Today Acc Required`=0');$db->exec($sql);
$sql=sprintf('update `Part Data` set `Part Yesterday Acc Dispatched`=`Part Today Acc Dispatched`,`Part Today Acc Dispatched`=0');$db->exec($sql);
$sql=sprintf('update `Part Data` set `Part Yesterday Acc Keeping Days`=`Part Today Acc Keeping Days`,`Part Today Acc Keeping Days`=0');$db->exec($sql);
$sql=sprintf('update `Part Data` set `Part Yesterday Acc With Stock Days`=`Part Today Acc With Stock Days`,`Part Today Acc With Stock Days`=0');$db->exec($sql);
$sql=sprintf('update `Part Data` set `Part Yesterday Acc Deliveries`=`Part Today Acc Deliveries`,`Part Today Acc Deliveries`=0');$db->exec($sql);
$sql=sprintf('update `Part Data` set `Part Yesterday Acc Customers`=`Part Today Acc Customers`,`Part Today Acc Customers`=0');$db->exec($sql);
$sql=sprintf('update `Part Data` set `Part Yesterday Acc Repeat Customers`=`Part Today Acc Repeat Customers`,`Part Today Acc Repeat Customers`=0');$db->exec($sql);

$sql=sprintf('update `Part Category Data` set `Part Category Yesterday Acc Invoiced Amount`=`Part Category Today Acc Invoiced Amount` ,`Part Category Today Acc Invoiced Amount`=0  ');$db->exec($sql);
$sql=sprintf('update `Part Category Data` set `Part Category Yesterday Acc Profit`=`Part Category Today Acc Profit`, `Part Category Today Acc Profit`=0 ');$db->exec($sql);
$sql=sprintf('update `Part Category Data` set `Part Category Yesterday Acc Required`=`Part Category Today Acc Required`, `Part Category Today Acc Required`=0');$db->exec($sql);
$sql=sprintf('update `Part Category Data` set `Part Category Yesterday Acc Dispatched`=`Part Category Today Acc Dispatched`,`Part Category Today Acc Dispatched`=0');$db->exec($sql);
$sql=sprintf('update `Part Category Data` set `Part Category Yesterday Acc Keeping Days`=`Part Category Today Acc Keeping Days`,`Part Category Today Acc Keeping Days`=0');$db->exec($sql);
$sql=sprintf('update `Part Category Data` set `Part Category Yesterday Acc With Stock Days`=`Part Category Today Acc With Stock Days`,`Part Category Today Acc With Stock Days`=0');$db->exec($sql);
$sql=sprintf('update `Part Category Data` set `Part Category Yesterday Acc Deliveries`=`Part Category Today Acc Deliveries`,`Part Category Today Acc Deliveries`=0');$db->exec($sql);
$sql=sprintf('update `Part Category Data` set `Part Category Yesterday Acc Customers`=`Part Category Today Acc Customers`,`Part Category Today Acc Customers`=0');$db->exec($sql);
$sql=sprintf('update `Part Category Data` set `Part Category Yesterday Acc Repeat Customers`=`Part Category Today Acc Repeat Customers`,`Part Category Today Acc Repeat Customers`=0');$db->exec($sql);

*/


$intervals = array(
    'Year To Day',
    'Quarter To Day',
    'Month To Day',
    'Week To Day',
    '1 Year',
    '1 Quarter',
    '1 Month',
    '1 Week'
);
foreach ($intervals as $interval) {


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


    $sql = sprintf('SELECT `Category Key` FROM `Category Dimension` WHERE `Category Scope`="Invoice"  ');

    if ($result = $db->query($sql)) {
        foreach ($result as $row) {
            $category = new Category($row['Category Key']);
            $category->update_invoice_category_sales($interval);
        }

    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }


    $sql = sprintf("SELECT `Store Key` FROM `Store Dimension`");
    if ($result = $db->query($sql)) {
        foreach ($result as $row) {
            $store = new Store('id', $row['Store Key']);

            $store->load_acc_data();
            $store->update_sales_from_invoices($interval);


        }

    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }


    $account->load_acc_data();
    $account->update_sales_from_invoices($interval);


}


?>
