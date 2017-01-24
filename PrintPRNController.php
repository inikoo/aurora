<?php

include 'WebClientPrint.php';

use Neodynamic\SDK\Web\WebClientPrint;
use Neodynamic\SDK\Web\DefaultPrinter;
use Neodynamic\SDK\Web\InstalledPrinter;
use Neodynamic\SDK\Web\ClientPrintJob;



//===
require_once 'class.interlink.php';

$data=array(
    'url'=>'https://api.dpd.co.uk',
    'user'=>'ancient',
    'password'=>'wisdom',
    'account_code'=>'462302'
);

$shipping = new interlink($data['url'],$data['user'],$data['password'],$data['account_code']);

$dataArray = array(
    'collectionDetails' => [
        'address' => [
            'locality' => 'Birmingham',
            'county' => 'West Midlands',
            'postcode' => 'B661BY',
            'countryCode' => 'GB'
        ],
    ],
    'deliveryDetails' => [
        'address' => [
            'locality' => 'Birmingham',
            'county' => 'West Midlands',
            'postcode' => 'B11AA',
            'countryCode' => 'GB'
        ],
    ],
    'deliveryDirection' => 1,
    'numberOfParcels' => 1,
    'totalWeight' => 5,
    'shipmentType' => 0
);


//print_r($shipping->getShipping($dataArray));

//exit;
$shippingArray = array( 'job_id' => NULL,
                        'collectionOnDelivery' => NULL,
                        'invoice'=> NULL,
                        'collectionDate' => '2017-1-23T05:00:00',
                        'consolidate' => NULL,
                        'consignment' => [[
                                              'consignmentNumber' => NULL,
                                              'consignmentRef' => NULL,
                                              'parcels' => [],
                                              'collectionDetails' => [
                                                  'contactDetails' => [
                                                      'contactName' => 'My Contact',
                                                      'telephone' => '0121 500 2500'
                                                  ],
                                                  'address' => [
                                                      'organisation' => 'GeoPostUK Ltd',
                                                      'countryCode' => 'GB',
                                                      'postcode' => 'B66 1BY',
                                                      'street' => 'Roebuck Lane',
                                                      'locality' => 'Smethwick',
                                                      'town' => 'Birmingham',
                                                      'county' => 'West Midlands'
                                                  ]
                                              ],
                                              'deliveryDetails'=> [
                                                  'contactDetails'=> [
                                                      'contactName'=> 'My Contact',
                                                      'telephone'=> '0121 500 2500'
                                                  ],
                                                  'address'=> [
                                                      'organisation'=> 'GeoPostUK Ltd',
                                                      'countryCode'=> 'GB',
                                                      'postcode'=> 'B66 1BY',
                                                      'street'=> 'Roebuck Lane',
                                                      'locality'=> 'Smethwick',
                                                      'town'=> 'Birmingham',
                                                      'county'=> 'West Midlands'
                                                  ],
                                                  'notificationDetails' => [
                                                      'email'=> 'my.email@geopostuk.com',
                                                      'mobile'=> '07921000001'
                                                  ]
                                              ],
                                              'networkCode'=> '1^01',
                                              'numberOfParcels'=> 1,
                                              'totalWeight'=> 5,
                                              'shippingRef1'=> 'My Ref 1',
                                              'shippingRef2'=> 'My Ref 2',
                                              'shippingRef3'=> 'My Ref 3',
                                              'customsValue'=> NULL,
                                              'deliveryInstructions'=> 'Please deliver with neighbour',
                                              'parcelDescription'=> NULL,
                                              'liabilityValue'=> NULL,
                                              'liability'=> NULL
                                          ]]
);

// 168108576





//print_r($shipping->insertShipping($shippingArray));
$shippmentId='168108576';
$returnFormat='text/vnd.citizen-clp';

//$returnFormat='text/html';

//header("Content-Type: $returnFormat");


 $shipping->getLabel($shippmentId, $returnFormat);




//===




// Process request
// Generate ClientPrintJob? only if clientPrint param is in the query string
$urlParts = parse_url($_SERVER['REQUEST_URI']);

if (isset($urlParts['query'])) {
    $rawQuery = $urlParts['query'];
    parse_str($rawQuery, $qs);
    if (isset($qs[WebClientPrint::CLIENT_PRINT_JOB])) {

        $useDefaultPrinter = ($qs['useDefaultPrinter'] === 'checked');
        $printerName = urldecode($qs['printerName']);

        //Create a ClientPrintJob obj that will be processed at the client side by the WCPP
		$cpj = new ClientPrintJob();
		//set PRN commands to print...



        //=====


        //====


		$cpj->printerCommands = $shipping->getLabel($shippmentId, $returnFormat);
        
		if ($useDefaultPrinter || $printerName === 'null') {
			$cpj->clientPrinter = new DefaultPrinter();
		} else {
			$cpj->clientPrinter = new InstalledPrinter($printerName);
		}


		print $cpj->printerCommands;
		exit;

		//Send ClientPrintJob back to the client
		ob_start();
		ob_clean();
		header('Content-type: application/octet-stream');
		echo $cpj->sendToClient();
		ob_end_flush();
		exit();
        
    }
}