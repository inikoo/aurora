<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 10 October 2018 at 13:03:22 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'utils/natural_language.php';
require_once 'utils/date_functions.php';
require_once 'utils/object_functions.php';
require_once 'utils/ip_geolocation.php';
require_once 'utils/natural_language.php';


require_once 'utils/parse_email_status_codes.php';


$editor = array(
    'Author Name'  => '',
    'Author Alias' => '',
    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => 0,
    'Date'         => gmdate('Y-m-d H:i:s'),
    'Subject'      => 'System',
    'Subject Key'  => 0,
    'Author Name'  => 'Email tracker'
);



$purchase_orders = array();


$sql = sprintf(
    'select `Purchase Order Key`,`Purchase Order Transaction Fact Key` ,`Purchase Order Submitted Units Per SKO`,`Purchase Order Submitted SKOs Per Carton`,`Supplier Part Reference`,
`Supplier Part Unit Cost`,`Purchase Order Ordering Units`,`Supplier Part Unit Extra Cost`,POTF.`Supplier Part Key`,SPD.`Supplier Part Historic Key`

from `Purchase Order Transaction Fact` POTF left join `Supplier Part Dimension` SPD  on (POTF.`Supplier Part Key`=SPD.`Supplier Part Key`) where `Supplier Delivery Key` is null   '

);

if ($result = $db->query($sql)) {
    foreach ($result as $row) {

     //   print_r($row);

        $po=get_object('PurchaseOrder',$row['Purchase Order Key']);


        if($po->id) {


            $supplier_part = get_object('Supplier_Part', $row['Supplier Part Key']);

            if ($supplier_part->id) {
                if ($po->get('Purchase Order State') == 'InProcess') {
                    $sql = sprintf(
                        'UPDATE `Purchase Order Transaction Fact` SET 
                            
                            `Purchase Order Submitted Units`=%d ,`Purchase Order Submitted Unit Cost`=%f,`Purchase Order Submitted Units Per SKO`=NULL,`Purchase Order Submitted SKOs Per Carton`=NULL,`Purchase Order Submitted Unit Extra Cost Percentage`=NULL,
                                              `Purchase Order Net Amount`=%.2f ,`Purchase Order Extra Cost Amount`=%.2f ,`Supplier Part Historic Key`=%d 
                            
                            WHERE `Purchase Order Transaction Fact Key`=%d ', $row['Purchase Order Ordering Units'], $row['Supplier Part Unit Cost'],

                        $row['Supplier Part Unit Cost'] * $row['Purchase Order Ordering Units'], $row['Supplier Part Unit Extra Cost'] * $row['Purchase Order Ordering Units'], $row['Supplier Part Historic Key'],

                        $row['Purchase Order Transaction Fact Key']

                    );
                   //  print "$sql\n";
                    $db->exec($sql);
                } else {


                    if ($supplier_part->get('Supplier Part Unit Extra Cost Percentage') == '') {
                        $extra_cost_percentage = 0;
                    } else {
                        $extra_cost_percentage = floatval($supplier_part->get('Supplier Part Unit Extra Cost Fraction'));

                    }


                    $sql = sprintf(
                        'update `Purchase Order Transaction Fact` set `Purchase Order Submitted Units`=%f ,`Purchase Order Submitted Unit Cost`=%f,`Purchase Order Submitted Units Per SKO`=%d,`Purchase Order Submitted SKOs Per Carton`=%d ,`Purchase Order Submitted Unit Extra Cost Percentage`=%f ,`Supplier Part Historic Key`=%d ,
 `Purchase Order Net Amount`=%.2f ,`Purchase Order Extra Cost Amount`=%.2f 

where `Purchase Order Transaction Fact Key`=%d  ',

                        $row['Purchase Order Ordering Units'], $supplier_part->get('Supplier Part Unit Cost'), $supplier_part->part->get('Part Units Per Package'), $supplier_part->get('Supplier Part Packages Per Carton'), $extra_cost_percentage,

                        $supplier_part->get('Supplier Part Historic Key'),
                        $row['Supplier Part Unit Cost'] * $row['Purchase Order Ordering Units'], $row['Supplier Part Unit Extra Cost'] * $row['Purchase Order Ordering Units'],

                        $row['Purchase Order Transaction Fact Key']
                    );


                    $db->exec($sql);

                    $purchase_orders[$row['Purchase Order Key']]=$row['Purchase Order Key'];

                }
            } else {
                $sql = sprintf('delete from `Purchase Order Transaction Fact`  WHERE `Purchase Order Transaction Fact Key`=%d  ', $row['Purchase Order Transaction Fact Key']);
                $db->exec($sql);

            }


        }else{
            $sql = sprintf('delete from `Purchase Order Transaction Fact`  WHERE `Purchase Order Transaction Fact Key`=%d  ', $row['Purchase Order Transaction Fact Key']);
            $db->exec($sql);
        }






        // exit;
    }
} else {
    print_r($error_info = $db->errorInfo());
    print "$sql\n";
    exit;
}



foreach ($purchase_orders as $purchase_order_key) {
    $purchase_order = get_object('Purchase Order', $purchase_order_key);
    $purchase_order->update_totals();
}

exit;



$sql = sprintf('select * from `Purchase Order Transaction Fact` POTF left join `Supplier Part Dimension` SPD  on (POTF.`Supplier Part Key`=SPD.`Supplier Part Key`) ');

if ($result = $db->query($sql)) {
    foreach ($result as $row) {


       // $supplier_part = get_object('Supplier_Part', $row['Supplier Part Key']);


        $purchase_order = get_object('Purchase Order', $row['Purchase Order Key']);

        switch ($row['Purchase Order Transaction State']) {
            case 'InProcess':
            case 'Cancelled':

                break;
            default:

                $sql = sprintf(
                    'update `Purchase Order Transaction Fact` set `Purchase Order Submitted Unit Cost`=%s,`Purchase Order Submitted Unit Extra Cost Percentage`=%s  where `Purchase Order Transaction Fact Key`=%d  ',

                    prepare_mysql(($row['Purchase Order Submitted Units']>0?$row['Purchase Order Net Amount']/$row['Purchase Order Submitted Units']:'')),
                    prepare_mysql(($row['Purchase Order Submitted Units']>0?$row['Purchase Order Extra Cost Amount']/$row['Purchase Order Submitted Units']:'')),

                    $row['Purchase Order Transaction Fact Key']
                );

                //print "$sql\n";
                $db->exec($sql);

                break;

        }





        // exit;
    }
} else {
    print_r($error_info = $db->errorInfo());
    print "$sql\n";
    exit;
}



exit;

$sql = sprintf('select * from `Purchase Order Transaction Fact` ');

if ($result = $db->query($sql)) {
    foreach ($result as $row) {


        $supplier_part = get_object('Supplier_Part', $row['Supplier Part Key']);

        $sql = sprintf('select * from `Supplier Part Historic Dimension` where `Supplier Part Historic Key`=%d ', $row['Supplier Part Historic Key']);
        if ($result2 = $db->query($sql)) {
            if ($row2 = $result2->fetch()) {
                // print_r($row2);
                $submitted_units_per_sko = $row2['Supplier Part Historic Units Per Package'];

                $submitted_skos_per_carton = $row2['Supplier Part Historic Packages Per Carton'];
                $submited_unit_cost        = $row2['Supplier Part Historic Unit Cost'];

            } else {
                exit('ups error');
            }
        } else {
            print_r($error_info = $db->errorInfo());
            print "$sql\n";
            exit;
        }
        $purchase_order = get_object('Purchase Order', $row['Purchase Order Key']);
        if ($purchase_order->get('State Index') != 10) {
            $sql = sprintf(
                'update `Purchase Order Transaction Fact` set `Purchase Order Submitted Units Per SKO`=%d,`Purchase Order Submitted SKOs Per Carton`=%d ,`Purchase Order Submitted Unit Cost`=%f where `Purchase Order Transaction Fact Key`=%d', $submitted_units_per_sko,
                $submitted_skos_per_carton, $submited_unit_cost, $row['Purchase Order Transaction Fact Key']

            );

            $db->exec($sql);

        }

        /*
        // too complicated, what if is half placed will fail in that case
        if($row['Supplier Delivery Placed Quantity']>0){
            print_r($row);

            $metadata=json_decode($row['Metadata'],true);
            print_r($metadata);

            exit;



            if(isset($metadata['placement_data']) and is_array($metadata['placement_data']) ){
                $skos_placed=0;

                foreach ($metadata['placement_data'] as $_data){
                    $skos_placed+=$_data['qty'];
                }


            }



        }else{

        }
        */


        if($row['Purchase Order Quantity']!=''){
            $sql = sprintf(
                'update `Purchase Order Transaction Fact` set `Purchase Order Ordering Units`=%d  where `Purchase Order Transaction Fact Key`=%d',

                $row['Purchase Order Quantity'] * $supplier_part->get('Supplier Part Packages Per Carton') * $supplier_part->part->get('Part Units Per Package'),

                $row['Purchase Order Transaction Fact Key']

            );

            $db->exec($sql);
        }

        if($row['Purchase Order Quantity']!='' and $purchase_order->get('State Index')>10){
            $sql = sprintf(
                'update `Purchase Order Transaction Fact` set `Purchase Order Submitted Units`=%d  where `Purchase Order Transaction Fact Key`=%d',

                $row['Purchase Order Quantity'] * $supplier_part->get('Supplier Part Packages Per Carton') * $supplier_part->part->get('Part Units Per Package'),

                $row['Purchase Order Transaction Fact Key']

            );

            $db->exec($sql);
        }

        $sql = sprintf(
            'update `Purchase Order Transaction Fact` set `Supplier Delivery Units`=%d  where `Purchase Order Transaction Fact Key`=%d',

            $row['Supplier Delivery Quantity'] * $supplier_part->get('Supplier Part Packages Per Carton') * $supplier_part->part->get('Part Units Per Package'),

            $row['Purchase Order Transaction Fact Key']

        );

        $db->exec($sql);

        if($row['Supplier Delivery Checked Quantity']!=''){
            $sql = sprintf(
                'update `Purchase Order Transaction Fact` set `Supplier Delivery Checked Units`=%d  where `Purchase Order Transaction Fact Key`=%d',

                $row['Supplier Delivery Checked Quantity'] * $supplier_part->get('Supplier Part Packages Per Carton') * $supplier_part->part->get('Part Units Per Package'),

                $row['Purchase Order Transaction Fact Key']

            );

            $db->exec($sql);
        }


        if($row['Supplier Delivery Checked Quantity']!=''){
            $sql = sprintf(
                'update `Purchase Order Transaction Fact` set `Supplier Delivery Checked Units`=%d  where `Purchase Order Transaction Fact Key`=%d',

                $row['Supplier Delivery Checked Quantity'] * $supplier_part->get('Supplier Part Packages Per Carton') * $supplier_part->part->get('Part Units Per Package'),

                $row['Purchase Order Transaction Fact Key']

            );

            $db->exec($sql);
        }


        if($row['Supplier Delivery Placed Quantity']!=''){
            $sql = sprintf(
                'update `Purchase Order Transaction Fact` set `Supplier Delivery Placed SKOs`=%f,  `Supplier Delivery Placed Units`=%d  where `Purchase Order Transaction Fact Key`=%d',
                $row['Supplier Delivery Placed Quantity'] *$supplier_part->get('Supplier Part Packages Per Carton') ,
                $row['Supplier Delivery Placed Quantity'] * $supplier_part->get('Supplier Part Packages Per Carton') * $supplier_part->part->get('Part Units Per Package'),

                $row['Purchase Order Transaction Fact Key']

            );

            $db->exec($sql);
        }

        // exit;
    }
} else {
    print_r($error_info = $db->errorInfo());
    print "$sql\n";
    exit;
}
