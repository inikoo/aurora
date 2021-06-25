<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 22 Jun 2021 19:19 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/


error_reporting(E_ALL ^ E_DEPRECATED);

require_once 'common.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use PhpOffice\PhpSpreadsheet\Cell\DataType;


if (empty($_REQUEST['id'])) {
    exit();
}

$dn = get_object('DeliveryNote', $_REQUEST['id']);
if (!$dn->id) {
    exit();
}
$store = get_object('Store', $dn->get('Store Key'));

$fileName = 'ShipmentToFile.csv';

$spreadsheet = new Spreadsheet();
$sheet       = $spreadsheet->getActiveSheet();

$rows   = [];
$rows[] = [
    'OurRef',
    'Weight',
    'NoPackages',
    'Company',
    'ContactName',
    'Address1',
    'Address2',
    'Address3',
    'Town',
    'County',
    'PostCode',
    'Phone',
    'Country',
    'Service',
    'BillingOption',
    'BillDutiesTaxes',
    'BillTransportation',
    'PackType',
    'DescriptionOfGoods',
    'EmailAddress',
    'InvoiceFlag',
    'InvoiceCurrencyCode',
    'WorldEase',
    'Declaration'
];
$rows[] = [
    $dn->get('ID').'UP',
    $dn->get('Best Weight'),
    $dn->get('Delivery Note Number Parcels'),
    $dn->get('Delivery Note Customer Name'),
    $dn->get('Delivery Note Customer Contact Name'),
    $dn->get('Delivery Note Address Line 1'),
    $dn->get('Delivery Note Address Line 2'),
    $dn->get('Delivery Note Address Dependent Locality'),
    $dn->get('Delivery Note Address Locality'),
    $dn->get('Delivery Note Address Administrative Area'),
    trim($dn->get('Delivery Note Address Sorting Code').' '.$dn->get('Delivery Note Address Postal Code')),
    (string) $dn->get('Delivery Note Telephone'),
    $dn->get('Delivery Note Address Country 2 Alpha Code'),
    'ST',
    'PP',
    'REC',
    'SHP',
    'CP',
    'giftware',
    $dn->get('Delivery Note Email'),
    'Y',
    $store->get('Store Currency Code'),
    'Y',
    'AW EURO DEC'


];

$j = 1;
foreach ($rows as $row) {
    foreach ($row as $i => $col) {
        $char = number2alpha($i+1);
        $sheet->setCellValueExplicit(
            $char.$j, strip_tags($col), DataType::TYPE_STRING
        );
    }
    $j++;
}
$writer = new Csv($spreadsheet);
$writer->setDelimiter(';');
$writer->setEnclosure('"');
$writer->setLineEnding("\r\n");
$writer->setSheetIndex(0);

header("Content-type: text/csv");
header('Content-Disposition: attachment; filename="'.urlencode($fileName).'"');
$writer->save('php://output');