<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 11 November 2016 at 15:35:50 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/



$parts_by_status=array(
    'In Process'=>0,
    'In Use'=>0,
    'Discontinuing'=>0,
    'Not In Use'=>0,

);


$sql=sprintf('select `Part Status`, count(*) as num from `Part Dimension` group by `Part Status`');

if ($result=$db->query($sql)) {
		foreach ($result as $row) {
            $parts_by_status[$row['Part Status']]=number($row['num']);
		}
}else {
		print_r($error_info=$db->errorInfo());
		print "$sql\n";
		exit;
}



$smarty->assign('parts_by_status',$parts_by_status);

$html=$smarty->fetch('inventory.mobile.tpl');


?>
