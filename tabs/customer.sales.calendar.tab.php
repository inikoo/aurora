<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 26 July 2018 at 14:05:36 GMT+8, Kuala Lumpur, Malysia
 Copyright (c) 2016, Inikoo

 Version 3

*/


$customer = $state['_object'];

$sales_max_sample_domain = 1;

/*
if ($customer->get('Product Max Day Sales')>0) {
	$top_range=$customer->get('Product Avg with Sale Day Sales')+(3*$customer->get('Product STD with Sale Day Sales'));
	if ($customer->get('Product Max Day Sales')<$top_range) {
		$sales_max_sample_domain=$customer->get('Product Max Day Sales');
	}else {
		$sales_max_sample_domain=$top_range;
	}

}
*/
$timeseries_key = '';
$number_records = 0;
$sql            = sprintf(
    'SELECT `Timeseries Key`,`Timeseries Number Records` FROM `Timeseries Dimension` WHERE `Timeseries Parent`="Customer" AND `Timeseries Parent Key`=%s AND `Timeseries Frequency`="Daily" AND  `Timeseries Type`="CustomerSales" ',
    $state['key']
);
if ($result = $db->query($sql)) {
    if ($row = $result->fetch()) {
        $timeseries_key = $row['Timeseries Key'];
        $number_records = $row['Timeseries Number Records'];
    }
} else {
    print_r($error_info = $db->errorInfo());
    exit;
}


$sql = sprintf(
    "SELECT  `Timeseries Record Float A` AS value FROM  `Timeseries Record Dimension`  WHERE `Timeseries Record Timeseries Key`=%d   ORDER BY `Timeseries Record Float A` DESC LIMIT %d ,1",
    $timeseries_key, $number_records / 20
);

if ($result = $db->query($sql)) {
    if ($row = $result->fetch()) {
        $sales_max_sample_domain = $row['value'];
    }
} else {
    print_r($error_info = $db->errorInfo());
    exit;
}


$data = base64_encode(
    json_encode(
        array(
            'valid_from'              => $customer->get('Customer First Contacted Date'),
            'valid_to'                => gmdate("Y-m-d H:i:s"),
            'sales_max_sample_domain' => $sales_max_sample_domain,
            'parent'                  => 'customer',
            'parent_key'              => $state['key']
        )
    )
);


$smarty->assign('data', $data);
$html = $smarty->fetch('calendar.tpl');


?>
