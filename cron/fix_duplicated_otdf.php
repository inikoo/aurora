<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 7 November 2018 at 13:53:19 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'common.php';
//ini_set('memory_limit', '4024M');


$sql = sprintf('select `Order Key` from `Order Dimension`  order by `Order Key` desc  ' );
if ($result3=$db->query($sql)) {
		foreach ($result3 as $row3) {




            $sql = sprintf('select `Order Transaction Deal Key`,`Order Transaction Fact Key`,`Deal Component Key` from `Order Transaction Deal Bridge`  where `Order Key`=%d order by `Order Transaction Deal Key` desc  ' ,$row3['Order Key']);

            if ($result = $db->query($sql)) {
                foreach ($result as $row) {

                    $sql = sprintf('select `Order Transaction Deal Key` from `Order Transaction Deal Bridge` where `Order Transaction Deal Key`=%d ', $row['Order Transaction Deal Key']);
                    if ($result2 = $db->query($sql)) {
                        if ($row2 = $result2->fetch()) {
                            $sql = sprintf(
                                'delete from `Order Transaction Deal Bridge` where `Order Key`=%d and  `Order Transaction Fact Key`=%d and `Deal Component Key`=%d and   `Order Transaction Deal Key`!=%d',
                                $row3['Order Key'],
                                $row['Order Transaction Fact Key'],
                                $row['Deal Component Key'],
                                $row['Order Transaction Deal Key']
                            );
                            $db->exec($sql);

                        }
                    } else {
                        print_r($error_info = $db->errorInfo());
                        print "$sql\n";
                        exit;
                    }


                }
            } else {
                print_r($error_info = $db->errorInfo());
                print "$sql\n";
                exit;
            }


		}
}else {
		print_r($error_info=$db->errorInfo());
		print "$sql\n";
		exit;
}






?>