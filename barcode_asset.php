<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 7 April 2016 at 12:58:45 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/

require_once 'vendor/autoload.php';
require_once 'utils/sentry.php';

if (empty($_REQUEST['number'])) {
    header('Content-Type: image/png');
    echo base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABAQMAAAAl21bKAAAAA1BMVEUAAACnej3aAAAAAXRSTlMAQObYZgAAAApJREFUCNdjYAAAAAIAAeIhvDMAAAAASUVORK5CYII=');

    exit;

}
$number = $_REQUEST['number'];



if (isset($_REQUEST['type'])) {
    $type = $_REQUEST['type']  ;
} else {
    $type = 'ean';
}


if (isset($_REQUEST['scale']) and is_numeric($_REQUEST['scale'])) {
    $scale = ceil($_REQUEST['scale']);
} else {
    $scale = null;
}


if ($type == 'ean') {

    if (!is_numeric($number)) {
        exit;

    }
    include_once('external_libs/barcodes/ean.php');
    $ean = new EAN(substr($number, 0, 12), $scale);
    $ean->display();
}if ($type == 'code128') {

    include_once 'external_libs/barcodes/BarcodeGeneratorPNG.php';

    $generator = new BarcodeGeneratorPNG();
    header('Content-Type: image/png');

    echo $generator->getBarcode($number, $generator::TYPE_CODE_128,2,80);



}



?>