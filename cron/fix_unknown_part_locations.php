<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 5 December 2017 at 10:55:09 CET, Mijas Costa, Spain
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'class.Part.php';
require_once 'class.PartLocation.php';

require_once 'class.Category.php';


$print_est = true;



$sql=sprintf('select `Part SKU` from `Part Location Dimension` where `Location Key`=1 and `Quantity On Hand`=0 and `Quantity In Process`=0 ');
if ($result=$db->query($sql)) {
		foreach ($result as $row) {
		    //print_r($row);
           $part_location=new PartLocation($row['Part SKU'].'_1');
           $part_location->disassociate();
           //exit;
		}
}else {
		print_r($error_info=$db->errorInfo());
		print "$sql\n";
		exit;
}



$sql=sprintf('select `Part SKU` from `Part Location Dimension` where `Location Key`=1 ');
if ($result=$db->query($sql)) {
    foreach ($result as $row) {

        $part=get_object('Part',$row['Part SKU']);
        $part->update_unknown_location();

    }
}else {
    print_r($error_info=$db->errorInfo());
    print "$sql\n";
    exit;
}


exit;


    $sql = sprintf(
        'SELECT `Part SKU` FROM `Part Dimension` WHERE `Part Reference`="FO-P7" ORDER BY `Part SKU` DESC  '
    );
    $sql = sprintf(
        'SELECT `Part SKU` FROM `Part Dimension`  ORDER BY `Part SKU`  DESC '
    );

    if ($result = $db->query($sql)) {
        foreach ($result as $row) {
            $part = new Part($row['Part SKU']);

            /*
                        $part->activate();


                       // $part->update_cost();
                        $part->update_products_data();
                        $part->update_history_records_data();
                        $part->update_attachments_data();
                        $part->update_images_data();
            */


            $sql = sprintf(
                "UPDATE `Barcode Asset Bridge` SET `Barcode Asset Status`='Historic',`Barcode Asset Withdrawn Date`=NOW() WHERE `Barcode Asset Status`='Assigned' AND `Barcode Asset Type`='Part' AND `Barcode Asset Key`=%d AND `Barcode Asset Barcode Key`!=%d ;",
                $part->id, $part->get('Part Barcode Key')
            );


            //  print "$sql\n";

            $db->exec($sql);



            $part->validate_barcode();

            if ($part->get('Part Cost') <= 0 and $part->get('Part Status') != 'Not In Use') {
                //   print $part->get('Reference')." ".$part->get('Part Cost')."\n";


            }


        }

    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }



?>
