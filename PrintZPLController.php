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

        //Create ZPL commands for sample label
		$cmds =  "^XA";
		$cmds .= "^FO20,30^GB750,1100,4^FS";
		$cmds .= "^FO20,30^GB750,200,4^FS";
		$cmds .= "^FO20,30^GB750,400,4^FS";
		$cmds .= "^FO20,30^GB750,700,4^FS";
		$cmds .= "^FO20,226^GB325,204,4^FS";
		$cmds .= "^FO30,40^ADN,36,20^FDShip to:^FS";
		$cmds .= "^FO30,260^ADN,18,10^FDPart number #^FS";
		$cmds .= "^FO360,260^ADN,18,10^FDDescription:^FS";
		$cmds .= "^FO30,750^ADN,36,20^FDFrom:^FS";
		$cmds .= "^FO150,125^ADN,36,20^FDAcme Printing^FS";
		$cmds .= "^FO60,330^ADN,36,20^FD14042^FS";
		$cmds .= "^FO400,330^ADN,36,20^FDScrew^FS";
		$cmds .= "^FO70,480^BY4^B3N,,200^FD12345678^FS";
		$cmds .= "^FO150,800^ADN,36,20^FDMacks Fabricating^FS";
		$cmds .= "^XZ";

		//Create a ClientPrintJob obj that will be processed at the client side by the WCPP
		$cpj = new ClientPrintJob();
		//set ZPL commands to print...
		$cpj->printerCommands = $cmds;
        $cpj->formatHexValues = true;
		
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