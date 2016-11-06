<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 26 September 2016 at 18:48:59 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'class.Customer.php';

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


$editor = array(
    'Author Name'  => '',
    'Author Alias' => '',
    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => 0,
    'Date'         => gmdate('Y-m-d H:i:s')
);

$print_est = true;

print date('l jS \of F Y h:i:s A')."\n";

update_customer_asset_brige($db, $print_est);


function update_customer_asset_brige($db, $print_est) {

    $where = 'where `Customer Key`=9032';
    $where = '';

    $sql = sprintf("select count(*) as num from `Customer Dimension` $where");
    if ($result = $db->query($sql)) {
        if ($row = $result->fetch()) {
            $total = $row['num'];
        } else {
            $total = 0;
        }
    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }

    $lap_time0 = date('U');
    $contador  = 0;


    $sql = sprintf(
        "select `Customer Key` from `Customer Dimension` $where order by `Customer Key` desc "
    );


    if ($result = $db->query($sql)) {
        foreach ($result as $row) {
            $customer = new Customer('id', $row['Customer Key']);


            $customer->update_product_bridge();
            $customer->update_part_bridge();
            //$customer->update_category_part_bridge();


            $contador++;
            $lap_time1 = date('U');

            if ($print_est) {
                print 'P   '.percentage($contador, $total, 3)."  lap time ".sprintf("%.4f", ($lap_time1 - $lap_time0) / $contador)." EST  ".sprintf(
                        "%.4f", (($lap_time1 - $lap_time0) / $contador) * ($total - $contador) / 60
                    )."m  ($contador/$total) \r";
            }

        }

    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }


}


?>
