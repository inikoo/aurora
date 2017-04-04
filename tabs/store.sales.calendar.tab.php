<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 18 August 2016 at 16:30:28 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/



$category                = $state['_object'];
$sales_max_sample_domain = 1;
$timeseries_key          = '';
$number_records          = 0;

$sql = sprintf(
    'SELECT `Timeseries Key`,`Timeseries Number Records` FROM `Timeseries Dimension` WHERE `Timeseries Parent`="Store" AND `Timeseries Parent Key`=%s AND `Timeseries Frequency`="Daily" AND  `Timeseries Type`="StoreSales" ',
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
            'valid_from'              => $category->get('Store Valid From'),
            'valid_to'                => ($category->get('Store State') == 'Closed' ? $category->get('Store Valid To') : gmdate("Y-m-d H:i:s")),
            'sales_max_sample_domain' => $sales_max_sample_domain,
            'parent'                  => 'store',
            'parent_key'              => $state['key']
        )
    )
);


$smarty->assign('data', $data);
$html = $smarty->fetch('calendar.tpl');



