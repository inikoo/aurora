<?php

include 'WebClientPrint.php';

use Neodynamic\SDK\Web\WebClientPrint;
use Neodynamic\SDK\Web\DefaultPrinter;
use Neodynamic\SDK\Web\InstalledPrinter;
use Neodynamic\SDK\Web\ClientPrintJob;

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
		$cpj->printerCommands = file_get_contents('tests/y');
        
		if ($useDefaultPrinter || $printerName === 'null') {
			$cpj->clientPrinter = new DefaultPrinter();
		} else {
			$cpj->clientPrinter = new InstalledPrinter($printerName);
		}

		//Send ClientPrintJob back to the client
		ob_start();
		ob_clean();
		header('Content-type: application/octet-stream');
		echo $cpj->sendToClient();
		ob_end_flush();
		exit();
        
    }
}