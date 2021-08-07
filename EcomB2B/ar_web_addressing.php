<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 30 June 2017 at 13:15:34 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'utils/ar_web_common.php';
require_once 'utils/get_addressing.php';


if (!isset($_REQUEST['tipo'])) {
    $response = array(
        'state' => 405,
        'resp'  => 'Non acceptable request (t)'
    );
    echo json_encode($response);
    exit;
}



$tipo = $_REQUEST['tipo'];

switch ($tipo) {
    case 'address_format':
        $data = prepare_values(
            $_REQUEST, array(
                'country_code' => array('type' => 'string'),
                'website_key' => array('type' => 'key')
            )
        );
        /** @var Public_Website $website */
        address_format($website, $data);
        break;


    default:
        $response = array(
            'state' => 405,
            'resp'  => 'Tipo not found '.$tipo
        );
        echo json_encode($response);
        exit;

}

function address_format($website, $data) {


    if (strlen($data['country_code']) == 3) {

        include_once 'class.Country.php';
        $country             = new Country('code', $data['country_code']);
        $country_2alpha_code = $country->get('Country 2 Alpha Code');
    } else {
        $country_2alpha_code = $data['country_code'];
    }

    website_address_format($website, $country_2alpha_code);
}

