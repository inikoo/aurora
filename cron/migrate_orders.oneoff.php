<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 15 February 2017 at 12:19:26 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'common.php';


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


require_once 'utils/parse_natural_language.php';

require_once 'class.Account.php';


require_once 'class.Order.php';


$editor = array(
    'Author Name'  => '',
    'Author Alias' => '',
    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => 0,
    'Date'         => gmdate('Y-m-d H:i:s')
);

$account = new Account();


update_orders_class($db);


function update_orders_class($db) {


    $sql = sprintf('SELECT `Order Key` FROM `Order Dimension` order by `Order Key` desc ');

    if ($result = $db->query($sql)) {
        foreach ($result as $row) {

            $order = new Order($row['Order Key']);
            switch ($order->get('Order Current Dispatch State')) {
                case  'In Process by Customer':
                case  'Waiting for Payment Confirmation':
                    $class = 'InWebsite';
                    break;
                case  'Dispatched':
                case  'Cancelled':
                case  'Suspended':
                case  'Cancelled by Customer':
                    $class = 'Archived';
                    break;
                default:
                    $class = 'InProcess';
                    break;
            }


            $order->update(
                array('Order Class' =>$class), 'no_history'
            );


        }

    } else {
        print_r($error_info = $db->errorInfo());
        print $sql;
        exit;
    }
}


?>
