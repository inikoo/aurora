<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 22 February 2017 at 22:43:03 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'class.Category.php';

$from = date('Y-m-d H:i:s', strtotime('-14 months'));


$where = " where `Category Key`=11899 ";
$where = "where true";


$sql = sprintf(
    "select `Part Category Key` from `Part Category Dimension`  left join `Category Dimension`  on (`Category Key`=`Part Category Key`)   $where and `Part Category Valid From` <%s  and `Category Branch Type`='Head' and `Part Category Status` in ('InUse','InProcess')  ",
    prepare_mysql($from)
);

//print "$sql\n";

if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        $category = new Category($row['Part Category Key']);
        $category->load_acc_data();


        if ($category->get('Part Category 1 Year Acc Dispatched') == 0) {
            $category->update_part_category_sales('1 Year');
        }


        if ($category->get('Part Category 1 Year Acc Dispatched') == 0) {
            $category->update(array('Part Category Status Including Parts'=>'Discontinuing'),'no_history');
            print $category->get('Code')."\n ";
          //  exit;
        }

    }

} else {
    print_r($error_info = $db->errorInfo());
    exit;
}


?>
