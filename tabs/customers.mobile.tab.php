<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 12 November 2016 at 18:00:24 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/


$customers_by_number_of_orders = array(
    'With Orders'    => 0,
    'Without Orders' => 0


);
$total=0;

$sql = sprintf('SELECT `Customer With Orders`, count(*) as num from `Customer Dimension` group by `Customer WITH Orders`');

if ($result = $db->query($sql)) {
    foreach ($result as $row) {
		if($row['Customer With Orders']=='Yes') {
            $customers_by_number_of_orders['With Orders'] = number($row['num']);
        }else if($row['Customer With Orders']=='No') {
            $customers_by_number_of_orders['Without Orders'] = number($row['num']);
        }
        $total+=$row['num'];
    }
} else {
    print_r($error_info = $db->errorInfo());
    print "$sql\n";
    exit;
}


$smarty->assign('customers_by_number_of_orders', $customers_by_number_of_orders);
$smarty->assign('total', number($total));

$html = $smarty->fetch('customers.mobile.tpl');


?>
