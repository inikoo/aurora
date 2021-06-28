<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 22 Jun 2021 21:11 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/


error_reporting(E_ALL ^ E_DEPRECATED);

require_once 'common.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Csv;


if (empty($_REQUEST['id'])) {
    exit('a');
}

$dn = get_object('DeliveryNote', $_REQUEST['id']);
if (!$dn->id) {
    exit('b');
}
$store = get_object('Store', $dn->get('Store Key'));
$account=get_object('Account',1);
$account->load_properties();
$fileName = 'OrderInvoiceFile.csv';

$spreadsheet = new Spreadsheet();
$sheet       = $spreadsheet->getActiveSheet();

$rows   = [];
$rows[] = [

    'Referenceno1',
    'CountryOfOrigin',
    'PartNumber',
    'ProductDescription',
    'Units',
    'UnitPrice',
    'GoodsCurrencyCode',
    'TermOfSale',
    'ReasonForExport',
    'UnitofMeasure',
    'TariffCode',
    'EEI_COGROSSWEIGHT',
    'EEI_CO'


];


$sql = "SELECT
	`Part Reference`,
	GROUP_CONCAT(DISTINCT `Delivery Note ID`),
	`Part Recommended Product Unit Name`,
	`Part UN Number`,
	`Part Tariff Code`,
	sum(- 1.0 * `Part Units` * `Inventory Transaction Quantity`) AS units_invoiced,
sum(?*`Amount In`/`Invoice Currency Exchange Rate`*`Order Transaction Gross Amount`/`Order Transaction Amount`) AS net,
	round(sum(`Inventory Transaction Weight`), 3) AS weight,
	`Part Origin Country Code`,`Invoice Currency Exchange Rate`,`Country 2 Alpha Code`,`Amount In`,`Invoice Currency Exchange Rate`,`Order Transaction Gross Amount`,`Order Transaction Amount`,`Order Transaction Total Discount Amount`

FROM
	`Inventory Transaction Fact` ITF
	LEFT JOIN `Delivery Note Dimension` DN ON (DN. `Delivery Note Key` = ITF. `Delivery Note Key`)
	    Left join `Order Transaction Fact` OTF on (`Map To Order Transaction Fact Key`=`Order Transaction Fact Key`)
	LEFT JOIN `Part Dimension` P ON (P. `Part SKU` = ITF. `Part SKU`)
	LEFT JOIN kbase.`Country Dimension`  ON (`Country Code` = `Part Origin Country Code`)

WHERE
	ITF.`Delivery Note Key` = ?
	AND `Inventory Transaction Quantity` < 0
GROUP BY
	P. `Part SKU`;";

$stmt = $db->prepare($sql);
$stmt->execute(
    array($account->properties('ups_price_factor'),$dn->id)
);
while ($row = $stmt->fetch()) {
    $rows[] = [
        $dn->get('ID').'UP',
        $row['Country 2 Alpha Code'],
        $row['Part Reference'],
        $row['Part Recommended Product Unit Name'],
        $row['units_invoiced'],
        round($row['net'] / $row['units_invoiced'],2),
        $store->get('Store Currency Code'),
        'DAP','SALE','EA',
        $row['Part Tariff Code'],
        $row['weight'],
        'k'


    ];
}


$j = 0;
foreach ($rows as $row) {
    foreach ($row as $i => $col) {

        $sheet->setCellValueByColumnAndRow(($i + 1), $j + 1, $col);

    }
    $j++;
}
$writer = new Csv($spreadsheet);
$writer->setDelimiter(',');
$writer->setEnclosure('');
$writer->setLineEnding("\r\n");
$writer->setSheetIndex(0);

header("Content-type: text/csv");
header('Content-Disposition: attachment; filename="'.urlencode($fileName).'"');
$writer->save('php://output');