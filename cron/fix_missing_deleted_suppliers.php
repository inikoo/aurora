<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 14 Jun 2021 00:55 MYT Kuala Lumpur, Malaysia
 Copyright (c) 2019, Inikoo

 Version 3

*/

require_once __DIR__.'/cron_common.php';


$editor = array(
    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => 0,
    'Date'         => gmdate('Y-m-d H:i:s'),
    'Subject'      => 'System',
    'Subject Key'  => 0,
    'Author Name'  => 'System (Script)',
    'Author Alias' => 'System (Script)',
    'v'            => 3


);

$sql = "select  * from `Purchase Order Dimension` where `Purchase Order Parent`='Supplier'          ";
/** @var TYPE_NAME $db */
$stmt = $db->prepare($sql);
$stmt->execute(
    [

    ]
);
while ($row = $stmt->fetch()) {
    $supplier = get_object('Supplier', $row['Purchase Order Parent Key']);
    if (!$supplier->id) {
        $supplier = new Supplier('deleted', $row['Purchase Order Parent Key']);
        if (!$supplier->id) {
           // print_r($row);

            $data = [
                'Supplier Key'          => $row['Purchase Order Parent Key'],
                'Supplier Code'         => $row['Purchase Order Parent Code'],
                'Supplier Name'         => $row['Purchase Order Parent Name'],
                'Supplier Company Name' => $row['Purchase Order Parent Name'],

                'Supplier Type'                                 => 'Free',
                'Supplier Main Contact Name'                    => $row['Purchase Order Parent Contact Name'],
                'Supplier Main Plain Email'                     => $row['Purchase Order Parent Email'],
                'Supplier Main Plain Telephone'                 => $row['Purchase Order Parent Telephone'],
                'Supplier Contact Address Country 2 Alpha Code' => $row['Purchase Order Parent Country Code'],
                'Supplier Default Currency Code'                => $row['Purchase Order Currency Code'],
                'address'                                       => $row['Purchase Order Parent Address'],
                'fixed_from_po'                                 => $row['Purchase Order Key'],

            ];



            $sql = 'INSERT INTO `Supplier Deleted Dimension`  (`Supplier Deleted Key`,`Supplier Deleted Code`,`Supplier Deleted Name`,`Supplier Deleted Date`,`Supplier Deleted Metadata`) VALUES (?,?,?,?,?) ';

            $db->prepare($sql)->execute(
                array(
                    $row['Purchase Order Parent Key'],
                    $row['Purchase Order Parent Code'],
                    $row['Purchase Order Parent Name'],
                    gmdate('Y-m-d H:i:s'),
                    gzcompress(json_encode($data), 9)
                )
            );
        }
    }
}
