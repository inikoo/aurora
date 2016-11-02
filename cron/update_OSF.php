<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 16 August 2016 at 19:02:58 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/

require_once 'common.php';
require_once 'class.Product.php';


$from = date("Y-m-d", strtotime('now '));
$to   = date("Y-m-d", strtotime('now '));

create_otf($from, $to);


function create_otf($from, $to) {


    $sql = sprintf(
        "SELECT `Date` FROM kbase.`Date Dimension` WHERE `Date`>=%s AND `Date`<=%s ORDER BY `Date` DESC", prepare_mysql($from), prepare_mysql($to)
    );


    if ($result = $db->query($sql)) {
        foreach ($result as $row) {


            $where = sprintf(
                " `Product Status`='Discontinued'  and  `Product Valid From`<=%s and `Product Valid To`>=%s ", prepare_mysql($row['Date'].' 00:00:00'), prepare_mysql($row['Date'].' 23:59:59')

            );
            $sql   = sprintf(
                'SELECT `Product ID`  FROM `Product Dimension` WHERE %s     ', $where
            );

            $count = 0;
            if ($result2 = $db->query($sql)) {
                foreach ($result2 as $row2) {

                    $product = new Product($row2['Product ID']);
                    $product->create_time_series($row['Date']);


                }
            } else {
                print_r($error_info = $db->errorInfo());
                exit;
            }

            $count = 0;


            $where = sprintf(
                "   `Product Status`!='Discontinued'  and  `Product Valid From`<=%s  ", prepare_mysql($row['Date'].' 00:00:00')
            );
            $sql   = sprintf(
                'SELECT `Product ID`  FROM `Product Dimension` WHERE %s     ', $where
            );


            if ($resul2t = $db->query($sql)) {
                foreach ($result2 as $row2) {

                    $product = new Product($row2['Product ID']);
                    $product->create_time_series($row['Date']);


                }
            } else {
                print_r($error_info = $db->errorInfo());
                exit;
            }


        }
    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }


}


?>
