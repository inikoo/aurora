<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 229 January 2018 at 13:21:09 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'common.php';


require_once 'class.Product.php';
require_once 'class.Category.php';


$editor = array(
    'Author Name'  => '',
    'Author Alias' => '',
    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => 0,
    'Date'         => gmdate('Y-m-d H:i:s')
);


$sql = sprintf(
    "SELECT `Product ID` FROM   `Inventory Transaction Fact` ITF left join `Order Transaction Fact` on (`Map To Order Transaction Fact Key`=`Order Transaction Fact Key`) where ITF.`Date`>%s  and `Product ID`>0 group by `Product ID`",
prepare_mysql(gmdate('Y-m-d H:i:s',strtotime('now -4 days')))

);
$number_products=0;
if ($result = $db->query($sql)) {
    foreach ($result as $row) {

        $number_products++;
       


        $product       = new Product($row['Product ID']);
        $web_state_old = $product->get_web_state();
        $product->update_availability();
        $web_state_new = $product->get_web_state();

        if ($web_state_old != $web_state_new) {
            print $product->get('Store Key').' '.$product->get('Code')." $web_state_old $web_state_new \n";
        }


    }

} else {
    print_r($error_info = $db->errorInfo());
    exit;
}

print "$number_products\n";

?>
