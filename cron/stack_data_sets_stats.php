<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 221-06-2019 15:39:44 MYT,  Kuala Lumpur, Malaysia
 Copyright (c) 2019, Inikoo

 Version 3

*/

require_once __DIR__.'/cron_common.php';

$print_est = false;


$editor = array(


    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => 0,
    'Date'         => gmdate('Y-m-d H:i:s'),
    'Subject'      => 'System',
    'Subject Key'  => 0,
    'Author Name'  => 'System (Stack date sets stats)',
    'Author Alias' => 'System (Stack data sets stats)',
    'v'            => 3


);



$lap_time0 = date('U');
$lap_time1 = date('U');

$contador=0;


$sql = sprintf(
    "SELECT `Stack Key`,`Stack Object Key` FROM `Stack Dimension`  where `Stack Operation`='data_sets_stats' "
);

if ($result = $db->query($sql)) {
    foreach ($result as $row) {

        $data_set = get_object('Data_Set', $row['Stack Object Key']);

        if ($data_set->id) {


            $sql = sprintf('select `Stack Key` from `Stack Dimension` where `Stack Key`=%d ', $row['Stack Key']);

            if ($result2 = $db->query($sql)) {
                if ($row2 = $result2->fetch()) {


                    $sql = sprintf('delete from `Stack Dimension`  where `Stack Key`=%d ', $row['Stack Key']);
                    $db->exec($sql);

                    $editor['Date']     = gmdate('Y-m-d H:i:s');
                    $data_set->editor = $editor;


                    $data_set->update_stats();

                    $contador++;
                    $lap_time1 = date('U');

                  


                }
            }

        } else {
            $sql = sprintf('delete from `Stack Dimension`  where `Stack Key`=%d ', $row['Stack Key']);
            // print "$sql\n";
            $db->exec($sql);
        }


    }

} else {
    print_r($error_info = $db->errorInfo());
    exit;
}
if ($contador > 0) {
    printf("%s:  %.2f min Date sets stats\n", gmdate('Y-m-d H:i:s'), ($lap_time1 - $lap_time0) / 60);
}



