<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 08 Sep 2021 17:50:16 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 3.0
 */

/** @var PDO $db */

require_once __DIR__.'/cron_common.php';



$editor = array(
    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => 0,
    'Date'         => gmdate('Y-m-d H:i:s'),
    'Subject'      => 'System',
    'Subject Key'  => 0,
    'Author Name'  => 'System (Script)',
    'Author Alias' => 'System (Script)',
    'v'            => 3


);


//$customer=get_object('Customer_Fulfilment',406154);
//$customer->editor=$editor;
//$customer->update_rent_order();

$sql="select `Customer Fulfilment Customer Key`  from `Customer Fulfilment Dimension`  "   ;
$stmt = $db->prepare($sql);
$stmt->execute();
while ($row = $stmt->fetch()) {
    $customer=get_object('Customer_Fulfilment',$row['Customer Fulfilment Customer Key']);
    $customer->editor=$editor;

    $customer->update_rent_order();
}

