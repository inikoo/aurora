<?php
/*
About:
Author: Raul Perusquia <raul@inikoo.com>
Created: 7:33 pm Tuesday, 23 February 2021 (MYT) Time in Kuala Lumpur, Malaysia

Copyright (c) 2021, Inikoo

Version 2.0
*/

/** @var Account $account */

/** @var User $user */


use Spatie\ArrayToXml\ArrayToXml;

require_once 'common.php';
if ($user->get('User View') != 'Staff') {
    exit;
}
$account->load_properties();


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
$sql = "select $fields from  $table   $where $wheref   and `Product Tariff Code`  is not null and `Product Package Weight` is not null  $group_by  order by `Delivery Note Address Country 2 Alpha Code`,`Product Tariff Code`  ";


$date = strtotime($from);

$currency            = $account->get('Account Currency');
$declarationTypeCode = 1;
$flowCode            = 'D';
$functionCode        = 'O';


$report_ID = 'AA'.date('ym', $date);

$data = [
    'envelopeId'        => $report_ID,
    'partyType'         => $account->properties('intrastat_partyType'),
    'partyId'           => $account->properties('intrastat_partyId'),
    //  'organizationUnit'  => $account->properties('intrastat_organizationUnit'),
    'partyName'         => $account->properties('intrastat_partyName'),
    'streetName'        => $account->properties('intrastat_streetName'),
    'postalCode'        => $account->properties('intrastat_postalCode'),
    'cityName'          => $account->properties('intrastat_cityName'),
    'softwareUsed'      => 'Aurora',
    'declarationId'     => date('m', $date),
    'referencePeriod'   => date('Y-m', $date),
    'PSIId'             => $account->properties('intrastat_PSIId'),
    //    'organizationUnit1' => $account->properties('intrastat_organizationUnit1'),
    'partyId1'          => $account->properties('intrastat_partyId1'),
    // 'organizationUnit2' => $account->properties('intrastat_organizationUnit2'),
    'partyName1'        => $account->properties('intrastat_partyName1'),
    'streetName1'       => $account->properties('intrastat_streetName1'),
    'postalCode1'       => $account->properties('intrastat_postalCode1'),
    'cityName1'         => $account->properties('intrastat_cityName1'),
    'contactPersonName' => $account->properties('intrastat_contactPersonName'),
    'streetName2'       => $account->properties('intrastat_streetName2'),
    'phoneNumber'       => $account->properties('intrastat_phoneNumber'),
    'faxNumber'         => $account->properties('intrastat_faxNumber'),
    'e-mail'            => $account->properties('intrastat_e-mail'),

];


$totalNumberLines         = 0;
$totalNumberDetailedLines = '';

$items = [];
$stmt  = $db->prepare($sql);
$stmt->execute(
    array()
);


while ($row = $stmt->fetch()) {
    if ($row['value'] <= 0 or !$row['value']) {
        continue;
    }

    $tariff_code = trim($row['tariff_code']);
    if ($tariff_code == '') {
        continue;
    }


    if ($row['value'] > 1) {
        $invoiced_amount = floor($row['value']);
    } else {
        $invoiced_amount = 1;
    }


    $country = new Country('code', $row['Product Origin Country Code']);

    $weight = ceil($row['weight']);
    if ($weight <= 0) {
        $weight = 1;
    }

    $invoicedAmount = floor($row['value']);

    if ($invoicedAmount == 0) {
        $invoicedAmount = 1;
    }
    $totalNumberLines++;
    $items[] = [
        'itemNumber'          => $totalNumberLines,
        'CN8'                 => [
            'CN8Code' => $tariff_code,
            'SUCode'  => ''
        ],
        'MSConsDestCode'      => $row['Delivery Note Address Country 2 Alpha Code'],
        'countryOfOriginCode' => $country->get('Country 2 Alpha Code'),
        'netMass'             => $weight,
        'quantityInSU'        => $weight,
        'NatureOfTransaction' => [
            'natureOfTransactionACode' => 1,
            'natureOfTransactionBCode' => 1,

        ],
        'modeOfTransportCode' => 3,
        'DeliveryTerms'       => [
            'TODCode' => 'EXW'
        ],
        'invoicedAmount'      => $invoicedAmount,
        'partnerId'           => ''
    ];
}


$array = [
    'Envelope' => [
        'envelopeId'           => $data['envelopeId'],
        'DateTime'             => [
            'date' => date('Y-m-d'),
            'time' => date('H:i:s'),
        ],
        'Party'                => [
            '_attributes' => [
                'partyType' => $data['partyType'],
                'partyRole' => 'sender'
            ],
            'partyId'     => $data['partyId'],
            //   'organizationUnit' => $data['organizationUnit'],
            'partyName'   => $data['partyName'],
            'Address'     => [
                'streetName' => $data['streetName'],
                'postalCode' => $data['postalCode'],
                'cityName'   => $data['cityName'],
            ]
        ],
        'softwareUsed'         => $data['softwareUsed'],
        'Declaration'          => [
            'declarationId'            => $data['declarationId'],
            'referencePeriod'          => $data['referencePeriod'],
            'PSIId'                    => $data['PSIId'],
            //  'organizationUnit'         => $data['organizationUnit1'],
            'Party'                    => [
                '_attributes'   => [
                    'partyType' => 'PSI',
                    'partyRole' => 'PSI'
                ],
                'partyId'       => $data['partyId1'],
                //    'organizationUnit' => $data['organizationUnit2'],
                'partyName'     => $data['partyName1'],
                'Address'       => [
                    'streetName' => $data['streetName1'],
                    'postalCode' => $data['postalCode1'],
                    'cityName'   => $data['cityName1'],
                ],
                'ContactPerson' => [
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


        ],
        'numberOfDeclarations' => 1

    ]
];

$result = ArrayToXml::convert(
    $array,
    [
        'rootElementName' => 'INSTAT',
        '_attributes'     => [
            'xmlns:xsi'                     => 'http://www.w3.org/2001/XMLSchema-instance',
            'xsi:noNamespaceSchemaLocation' => 'instat62.xsd',

        ]
    ],
    true,
    'UTF-8'
);


header('Content-Type: application/xml; charset=utf-8');
header("Content-Disposition: attachment; filename=intrastat_export_$report_ID.xml");


print $result;

