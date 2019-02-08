<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 8 February 2019 at 17:48:25 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'class.Part.php';
require_once 'class.PartLocation.php';

require_once 'class.Category.php';


$print_est = true;


$sql = sprintf(
    'SELECT `Part SKU` FROM `Part Dimension` WHERE `Part Reference`="FO-P7" ORDER BY `Part SKU` DESC  '
);
$sql = sprintf(
    'SELECT `Part SKU` FROM `Part Dimension` where `Part Status`= "Not In Use" and `Part Barcode Number`!=""  '
);

if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        $part = new Part($row['Part SKU']);

        $counter        = 0;
        $counter_active = 0;


        $counter_bis        = 0;


        $barcode_key='';

        $sql  = 'select * from `Barcode Asset Bridge` where `Barcode Asset Type`="Part" and `Barcode Asset Key`=? ';
        $stmt = $db->prepare($sql);
        if ($stmt->execute(
            array(
                $part->id
            )
        )) {
            while ($row2 = $stmt->fetch()) {
                $counter++;
                $barcode_key=$row2['Barcode Asset Barcode Key'];
                if ($row2['Barcode Asset Status'] == 'Assigned') {
                    $counter_active++;

                }

            }
        } else {
            print_r($error_info = $db->errorInfo());
            exit();
        }




        if ($counter > 0 and $counter_active == 0    ) {


            $sql  = 'select * from `Barcode Asset Bridge` where `Barcode Asset Barcode Key`=? ';
            $stmt = $db->prepare($sql);
            if ($stmt->execute(
                array(
                    $barcode_key
                )
            )) {
                while ($row3 = $stmt->fetch()) {
                    $counter_bis++;

                }
            } else {
                print_r($error_info = $db->errorInfo());
                exit();
            }


            if($counter_bis==1){
                print $counter.' '.$part->id."\n";

                $sql = sprintf(
                    "UPDATE `Barcode Asset Bridge` SET `Barcode Asset Status`='Assigned',`Barcode Asset Withdrawn Date`='' WHERE `Barcode Asset Barcode Key`=%d and `Barcode Asset Key`=%s ",
                    $barcode_key,
                    $part->id
                );


                print "$sql\n";

                $db->exec($sql);

            }





            // exit;
        }


        /*

                $sql = sprintf(
                    "UPDATE `Barcode Asset Bridge` SET `fixBarcode Asset Status`='Historic',`Barcode Asset Withdrawn Date`=NOW() WHERE `Barcode Asset Status`='Assigned' AND `Barcode Asset Type`='Part' AND `Barcode Asset Key`=%d AND `Barcode Asset Barcode Key`!=%d ;",
                    $part->id, $part->get('Part Barcode Key')
                );


                //  print "$sql\n";

                $db->exec($sql);
        */


    }

} else {
    print_r($error_info = $db->errorInfo());
    exit;
}


?>
