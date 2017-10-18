<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 18 October 2017 at 17:50:37 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'class.Part.php';
require_once 'class.Category.php';
include_once 'class.Barcode.php';



$sql = sprintf(
    'SELECT `Part SKU` FROM `Part Dimension`  WHERE `Part Barcode Number`!=""  '
);

if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        $part = new Part($row['Part SKU']);

        $value=$part->get('Part Barcode Number');
        if ($part->get('Part Barcode Key') > 0) {
           //Check if Barcode and part barcode are same


            $barcode         = new Barcode($part->get('Part Barcode Key'));
            if(!$barcode->id){
                print $part->get('Reference')." Barcode object not fount  ".$part->get('Part Barcode Key')."  \n";

            }else{

                if($value!=$barcode->get('Barcode Number')){
                    print $part->get('Reference')." Error Different barcode numbers  ".$part->get('Part Barcode Key')."  $value  ".$barcode->get('Barcode Number')."   \n";


                    $asset_data = array(
                        'Barcode Asset Type' => 'Part',
                        'Barcode Asset Key'  => $part->id
                    );

                    $barcode->withdrawn_asset($asset_data);
                    $part->fast_update(array('Part Barcode Key'=>''));

                }

            }



        }else{
            //Check if barcode exists in Barcode Dimension

            $sql = sprintf(
                "SELECT `Barcode Key` ,`Barcode Status` ,`Barcode Sticky Note` FROM `Barcode Dimension` WHERE `Barcode Number`=%s", prepare_mysql($value)
            );

            if ($result2=$db->query($sql)) {
                if ($row2 = $result2->fetch()) {
                    print $part->get('Reference')." Need to assign barcode object  ".$row2['Barcode Key']."  \n";




                    $barcode         = new Barcode($row2['Barcode Key']);

                    if($barcode->get('Barcode Status')=='Available' or $barcode->get('Barcode Status')=='Reserved'){
                        // Take it
                        if( $barcode->get('Barcode Status')=='Reserved') {
                            $barcode->fast_update(array('Barcode Status'=>'Available'));

                        }

                        $asset_data = array(
                            'Barcode Asset Type'          => 'Part',
                            'Barcode Asset Key'           => $part->id,
                            'Barcode Asset Assigned Date' => gmdate('Y-m-d H:i:s')
                        );


                        $barcode->assign_asset_to_barcode($asset_data);
                        $part->fast_update(array('Part Barcode Key'=>$barcode->id));

                    }

                    print $barcode->get('Barcode Status').' -->>'.$barcode->get('Parts')."<---    (".$barcode->get('Barcode Sticky Note').")\n------------------------\n";


                }else{
                    // is ok
                }
            }else {
            	print_r($error_info=$db->errorInfo());
            	print "$sql\n";
            	exit;
            }


        }


    }

} else {
    print_r($error_info = $db->errorInfo());
    exit;
}


$sql = sprintf(
    'SELECT `Part SKU` FROM `Part Dimension`  WHERE `Part Barcode Number`="" OR `Part Barcode Number` IS NULL '
);

if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        $part = new Part($row['Part SKU']);


        if ($part->get('Part Barcode Key') > 0) {
            print 'Error need to remove barcode link '.$part->get('Reference')." ".$part->get('Part Barcode Key')."  \n";


            $barcode         = new Barcode($part->get('Part Barcode Key'));

            if ($barcode->id) {
                $asset_data = array(
                    'Barcode Asset Type' => 'Part',
                    'Barcode Asset Key'  => $part->id
                );

                $barcode->withdrawn_asset($asset_data);

                $part->fast_update(array('Part Barcode Key'=>''));

            }

        }


    }

} else {
    print_r($error_info = $db->errorInfo());
    exit;
}


?>
