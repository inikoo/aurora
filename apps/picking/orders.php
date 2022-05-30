<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 28 May 2022 11:50:31 Central European Summer Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 3.0
 */

/** @var Smarty $smarty */
/** @var User $user */
/** @var PDO $db */

chdir('../../');

require_once 'vendor/autoload.php';
require_once 'common.php';

$sql="select * from `Delivery Note Dimension` where `Delivery Note State` in ('Ready to be Picked','Picker Assigned','Picking','Picked') ";
$stmt = $db->prepare($sql);
$stmt->execute();
$orders=[];
while ($row = $stmt->fetch()) {
    $orders[]=[
        'id'=>$row['Delivery Note Key'],
        'number'=>$row['Delivery Note ID'],
        'date'=>strftime('%a, %e %b %Y',strtotime($row['Delivery Note Order Date Placed'].' +0:00'))

    ];
}

echo json_encode(
    [
        'orders'=>$orders,
        'number_orders'=>count($orders),
    ]
);
