<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 21:26:32 MYT Friday, 10 July 2020
 Copyright (c) 2020, Inikoo

 Version 3

*/


require_once __DIR__.'/cron_common.php';


$editor = array(
    'Author Name'  => '',
    'Author Alias' => '',
    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => 0,
    'Date'         => gmdate('Y-m-d H:i:s'),
    'Subject'      => 'System',
    'Subject Key'  => 0,
    'Author Name'  => 'Script (Restore discontinuing products)'
);


$counter = 1;

$account->update_suppliers_data();

$sql  = "select  `Part SKU`   from `Part Dimension` where  `Part Status`='Discontinuing'  and (`Part Current On Hand Stock`-`Part Current Stock Ordered Paid`-`Part Current Stock In Process`)>0  order by `Part Reference` ";
$stmt = $db->prepare($sql);
$stmt->execute();
while ($row = $stmt->fetch()) {
    /**
     * @var $part \Part
     */
    $part         = get_object('Part', $row['Part SKU']);
    $part->editor = $editor;


    foreach ($part->get_products('objects') as $product) {

        if (($product->get('Product Status') == 'Discontinued' or $product->get('Product Status') == 'Suspended') and $product->get('Store Status') == 'Normal' and strtolower($product->get('Code')) == strtolower($part->get('Reference'))) {
            $editor['Date']  = gmdate('Y-m-d H:i:s');
            $product->editor = $editor;


            $sql   = "select count(*) as num from `Product Dimension` where `Product Code`=? and `Product Store Key`=? and `Product Status` in ('Active','Discontinuing')  ";
            $stmt2 = $db->prepare($sql);
            $stmt2->execute(
                array(
                    $product->get('Code'),
                    $product->get('Store Key'),
                )
            );
            if ($row2 = $stmt2->fetch()) {

                if ($row2['num'] == 0) {
                    print $counter.' '.$product->get('Code')."\n";

                    $product->update(['Product Status'=>'Active']);

                    $counter++;
                }

            }




        }
    }


}
