<?php

require_once __DIR__.'/cron_common.php';
require_once 'utils/natural_language.php';
require_once 'utils/order_functions.php';


$row = 1;
if (($handle = fopen("cron/gpsr.csv", "r")) !== false) {
    while (($data = fgetcsv($handle, 1000, ",")) !== false) {
        if ($row > 1) {
            if ($data[0] != '') {
                if (empty($data[2]) or  $data[2] == '') {
                    $data[2] = '';
                }
                if (empty($data[3]) or  $data[3] == '') {
                    $data[3] = '';
                }

                if (empty($data[4]) or  $data[4] == '') {
                    $data[4] = '';
                }

                if (empty($data[6]) or  $data[6] == '') {
                    $data[6] = '';
                }


                //  print_r($data);
                //  exit;

                $sql = "update `Part Dimension` set `Part GPSR EU Responsable`=? ,`Part GPSR Manual`=?  ,
                            `Part GPSR Languages`=? ,`Part GPSR Warnings`=?
                        
                        where `Part Reference`=?";

                $db->prepare($sql)->execute(
                    array(
                        $data[2],
                        $data[3],
                        $data[4],
                        $data[6],
                        $data[0],
                    )
                );



            }
        }


        $row++;

        //        $num = count($data);
        //        echo "<p> $num fields in line $row: <br /></p>\n";
        //        $row++;
        //        for ($c=0; $c < $num; $c++) {
        //            echo $data[$c] . "<br />\n";
        //        }
        //        exit;
    }
    fclose($handle);
}