<?php
/*
About:
Author: Raul Perusquia <raul@inikoo.com>
Created: 7:33 pm Tuesday, 23 February 2021 (MYT) Time in Kuala Lumpur, Malaysia

Copyright (c) 2021, Inikoo

Version 2.0
*/
/** @var \Account $account */

/** @var \User $user */

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



$tab = 'intrastat';



include_once 'prepare_table/'.$tab.'.ptble.php';

$text = "R00\tT00\r\n";


$sql = "select `Invoice Key` from  $table   $where $wheref ";

exit($sql);


$currency            = $account->get('Account Currency');
$declarationTypeCode = 2; //simplified declaration
$flowCode            = 'D';
$functionCode        = 'O';

$data = [
    'envelopeId'        => 'envelopeId',
    'partyType'         => 'partyType',
    'partyId'           => 'partyId',
    'organizationUnit'  => 'organizationUnit',
    'partyName'         => 'partyName',
    'streetName'        => 'streetName',
    'postalCode'        => 'postalCode',
    'cityName'          => 'cityName',
    'softwareUsed'      => 'softwareUsed',
    'declarationId'     => 'declarationId',
    'referencePeriod'   => 'referencePeriod',
    'PSIId'             => 'PSIId',
    'organizationUnit1' => 'organizationUnit1',
    'partyId1'          => 'partyId1',
    'organizationUnit2' => 'organizationUnit2',
    'partyName1'        => 'partyName1',
    'streetName1'       => 'streetName1',
    'postalCode1'       => 'postalCode1',
    'cityName1'         => 'cityName1',
    'contactPersonName' => 'contactPersonName',
    'streetName2'       => 'streetName2',
    'phoneNumber'       => 'phoneNumber',
    'faxNumber'         => 'faxNumber',
    'e-mail'            => 'e-mail',

];

$totalNumberLines         = 0;
$totalNumberDetailedLines = 0;



$items                    = [
    ['a' => 'x'],
    ['a' => 'y']
];

$array = [
    'Envelope' => [
        'envelopeId'   => $data['envelopeId'],
        'DateTime'     => [
            'date' => date('Y-m-d'),
            'time' => date('H:i:s'),
        ],
        'Party'        => [
            '_attributes'      => [
                'partyType' => $data['partyType'],
                'partyRole' => 'sender'
            ],
            'partyId'          => $data['partyId'],
            'organizationUnit' => $data['organizationUnit'],
            'partyName'        => $data['partyName'],
            'Address'          => [
                'streetName' => $data['streetName'],
                'postalCode' => $data['postalCode'],
                'cityName'   => $data['cityName'],
            ]
        ],
        'softwareUsed' => $data['softwareUsed'],
        'Declaration'  => [
            'declarationId'            => $data['declarationId'],
            'referencePeriod'          => $data['referencePeriod'],
            'PSIId'                    => $data['PSIId'],
            'organizationUnit'         => $data['organizationUnit1'],
            'Party'                    => [
                '_attributes'      => [
                    'partyType' => 'PSI',
                    'partyRole' => 'PSI'
                ],
                'partyId'          => $data['partyId1'],
                'organizationUnit' => $data['organizationUnit2'],
                'partyName'        => $data['partyName1'],
                'Address'          => [
                    'streetName' => $data['streetName1'],
                    'postalCode' => $data['postalCode1'],
                    'cityName'   => $data['cityName1'],
                ],
                'ContactPerson'    => [
                    'contactPersonName' => $data['contactPersonName'],
                    'Address'           => [
                        'streetName'  => $data['streetName2'],
                        'phoneNumber' => $data['phoneNumber'],
                        'faxNumber'   => $data['faxNumber'],
                        'e-mail'      => $data['e-mail']
                    ],
                ]
            ],
            'Function'                 => [
                'functionCode' => $functionCode,

            ],
            'declarationTypeCode'      => $declarationTypeCode,
            'flowCode'                 => $flowCode,
            'currencyCode'             => $currency,
            'Item'                     => $items,
            'totalNumberLines'         => $totalNumberLines,
            'totalNumberDetailedLines' => $totalNumberDetailedLines,


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
 *
 *
 * <Party partyType = "partyType" partyRole = "sender">
<partyId>partyId</partyId>
<organizationUnit>organizationUnit</organizationUnit>
<partyName>partyName</partyName>
<Address>
<streetName>streetName</streetName>
<postalCode>postalCode</postalCode>
<cityName>cityName</cityName>
</Address>
</Party>
 *

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