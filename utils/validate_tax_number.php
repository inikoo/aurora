<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>

 Copyright (c) 2014, Inikoo
 Created: 8 June 2014 20:25:03 GMT+1, Sheffield UK

 Version 2.0
*/



function validate_tax_number($tax_number, $country_2alpha_code) {

	if (in_array($country_2alpha_code, array("AT", "BE", "BG", "CY", "CZ", "DE", "DK", "EE", "GR", "ES", "FI", "FR", "GB", "HR", "HU", "IE", "IT", "LT", "LU", "LV", "MT", "NL", "PL", "PT", "RO", "SE", "SI", "SK"))) {

		$tax_number=preg_replace('/^'.$country_2alpha_code.'/i', '', $tax_number);
		$tax_number=preg_replace('/[^a-z^0-9]/i', '', $tax_number);

		if (preg_match('/^gr$/i', $country_2alpha_code)) {
			$country_2alpha_code='EL';
		}

		$tax_number=preg_replace('/^'.$country_2alpha_code.'/i', '', $tax_number);
		$tax_number=preg_replace('/[^a-z^0-9]/i', '', $tax_number);
		return check_european_tax_number($country_2alpha_code, $tax_number);


	}else {
		$response=array(
			'Tax Number Valid'=>$tax_number,
			'Tax Number Valid'=>'Unknown',
			'Tax Number Details Match'=>'Unknown',
			'Tax Number Validation Date'=>'',
			'Tax Number Associated Name'=>'',
			'Tax Number Associated Address'=>'',
			'Tax Number Validation Message'=>_("Can't verify the tax numbers in this country").' ('.$country_2alpha_code.')'
		);
		return $response;



	}

}


function check_european_tax_number($country_code, $tax_number) {

	$response=array(
		'Tax Number'=>$tax_number,
		'Tax Number Valid'=>'Unknown',
		'Tax Number Details Match'=>'Unknown',
		'Tax Number Validation Date'=>'',
		'Tax Number Associated Name'=>'',
		'Tax Number Associated Address'=>'',
		'Tax Number Validation Message'=>''
	);

	try {
		$client = new SoapClient("http://ec.europa.eu/taxation_customs/vies/checkVatService.wsdl");
		$result = $client->checkVat(array('countryCode'=>$country_code, 'vatNumber'=>$tax_number));
	} catch (Exception $e) {
		//  echo "<h2>Exception Error!</h2>";

		$msg=$e->getMessage();


		if (preg_match('/INVALID_INPUT/i', $msg)) {
			$msg=_('Invalid tax number format');
			$response['Tax Number Valid']='No';
			$response['Tax Number Details Match']='No';

			$response['Tax Number Validation Message']=_('Invalid tax number format');
			$response['Tax Number Validation Date']=gmdate('Y-m-d H:i:s');


		}elseif (preg_match('/SERVER_BUSY|MS_UNAVAILABLE/i', $msg)) {
			$response['Tax Number Validation Message']=_('Validations server is busy please try later');



		}else {

			$response['Tax Number Validation Message']=$msg;

		}

		$response['Tax Number Validation Message'].=' VIES';
		return $response;

	}


	if ($result->valid) {



		if (isset($result->address)) {
			$result->address=str_replace("\n\n\n", "\n", $result->address);
			$result->address=str_replace("\n\n", "\n", $result->address);

			$result->address=nl2br($result->address);

		}


		$response=array(
			'Tax Number'=>$country_code.' '.$tax_number,
			'Tax Number Valid'=>'Yes',
			'Tax Number Details Match'=>'Unknown',
			'Tax Number Validation Date'=>gmdate('Y-m-d H:i:s'),
			'Tax Number Associated Name'=>$result->name,
			'Tax Number Associated Address'=>$result->address,
			'Tax Number Validation Message'=>''
		);






	}else {

		$response['Tax Number Valid']='No';
		$response['Tax Number Validation Date']=gmdate('Y-m-d H:i:s');


	}
	$response['Tax Number Validation Message'].=' VIES';
	return $response;

}


?>
