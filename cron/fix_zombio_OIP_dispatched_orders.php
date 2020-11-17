<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 21:35:46 Friday, 10 July 2020
 Copyright (c) 2020, Inikoo

 Version 3

*/


require_once 'common.php';


$editor = array(
    'Author Name'  => '',
    'Author Alias' => '',
    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => 0,
    'Date'         => gmdate('Y-m-d H:i:s'),
    'Subject'      => 'System',
    'Subject Key'  => 0,
    'Author Name'  => 'Script (Fix IOP dispatched orders)'
);

$counter=1;

$sql  = "select  `Delivery Note State`,`Delivery Note ID`,DN.`Delivery Note Key`,`Inventory Transaction Key`,`Required`,`Picked`,`Packed`,`Out of Stock`,`Inventory Transaction Quantity`,`Inventory Transaction Amount`,`Inventory Transaction Weight` ,`Part SKU`   from  `Inventory Transaction Fact` ITF  left join `Delivery Note Dimension` DN on (DN.`Delivery Note Key`=ITF.`Delivery Note Key`) 
    where `Delivery Note State` in ('Dispatched','Cancelled') and `Inventory Transaction Section`='OIP' 
";
$stmt = $db->prepare($sql);
$stmt->execute();
while ($row = $stmt->fetch()) {


    //print_r($row);


    $part         = get_object('Part', $row['Part SKU']);
    $part->editor = $editor;
    $part->update_stock();

    print $counter.' '.$row['Delivery Note State'].'  '.$row['Delivery Note ID'].' '.$row['Required'].' '.$part->get('Reference')."\n";



    if($row['Delivery Note State']=='Dispatched') {

        $out_of_stock = $row['Required'];

        $transaction_record_type = 'Info';
        $transaction_type        = 'No Dispatched';
        $transaction_section     = 'NoDispatched';


        $sql = 'UPDATE  `Inventory Transaction Fact`  SET  `Inventory Transaction Record Type`=?,`Date Picked`=null ,`Date Packed`=null ,`Inventory Transaction Type`=? ,`Inventory Transaction Section`=? ,
                                      `Inventory Transaction Quantity`=0, `Inventory Transaction Amount`=0,`Inventory Transaction Weight`=0,
                                      `Picked`=0,`Packed`=0,`Out of Stock`=? WHERE `Inventory Transaction Key`=? ';


        $db->prepare($sql)->execute(
            [
                $transaction_record_type,
                $transaction_type,
                $transaction_section,
                $out_of_stock,
                $row['Inventory Transaction Key']

            ]
        );
    }elseif($row['Delivery Note State']=='Cancelled') {

        $sql = sprintf("UPDATE `Inventory Transaction Fact`  SET `Date Picked`=null ,`Date Packed`=null ,  `Picked`=0,`Packed`=0, `Inventory Transaction Type`='FailSale' , `Inventory Transaction Section`='NoDispatched'  WHERE `Inventory Transaction Key`=%d  ", $row['Inventory Transaction Key']);
        $db->exec($sql);

    }


    /**
     * @var $part \Part
     */


    $part->update_stock();
    $counter++;



    //print_r($part);


     //exit;


}
