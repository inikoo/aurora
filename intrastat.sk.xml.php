<?php
/*
About:
Author: Raul Perusquia <raul@inikoo.com>
Created: 7:33 pm Tuesday, 23 February 2021 (MYT) Time in Kuala Lumpur, Malaysia

Copyright (c) 2021, Inikoo

Version 2.0
*/

use Spatie\ArrayToXml\ArrayToXml;

require_once 'common.php';

require_once 'utils/object_functions.php';


$tab = 'intrastat';

if (isset($_SESSION['table_state'][$tab])) {
    $number_results  = $_SESSION['table_state'][$tab]['nr'];
    $start_from      = 0;
    $order           = $_SESSION['table_state'][$tab]['o'];
    $order_direction = ($_SESSION['table_state'][$tab]['od'] == 1 ? 'desc' : '');
    $f_value         = $_SESSION['table_state'][$tab]['f_value'];
    $parameters      = $_SESSION['table_state'][$tab];
} else {

    $default = $user->get_tab_defaults($tab);

    $number_results  = $default['rpp'];
    $start_from      = 0;
    $order           = $default['sort_key'];
    $order_direction = ($default['sort_order'] == 1 ? 'desc' : '');
    $f_value         = '';
    $parameters      = $default;


}

$array = [
    'Envelope' => [
        'envelopeId'   => '',
        'DateTime' => [
            'date'=>date('Y-m-d'),
            'time'=>date('H:i:s'),
        ]
    ]
];

$result = ArrayToXml::convert(
    $array, [
              'rootElementName' => 'INSTAT',
              '_attributes'     => [
                  'xmlns:xsi'                     => 'http://www.w3.org/2001/XMLSchema-instance',
                  'xsi:noNamespaceSchemaLocation' => 'instat62.xsd',

              ]
          ], true, 'ISO-8859-2'
);


header('Content-Type: application/xml; charset=utf-8');
//header("Content-Disposition: attachment; filename=intrastat_export.xml");


print $result;


/*

$tab = 'intrastat';



include_once 'prepare_table/'.$tab.'.ptble.php';

$text = "R00\tT00\r\n";


$sql = "select `Invoice Key` from  $table   $where $wheref ";

$stmt = $db->prepare($sql);
$stmt->execute(
    array()
);
while ($row = $stmt->fetch()) {

    $invoice = get_object('Invoice', $row['Invoice Key']);
    $text    .= get_omega_export_text($invoice);

}


$encoded_text = iconv(mb_detect_encoding($text), 'ISO-8859-15//TRANSLIT', utf8_encode($text));


*/