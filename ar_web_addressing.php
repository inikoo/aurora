<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 30 June 2017 at 13:15:34 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'utils/ar_common.php';
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
        address_format($db, $data);
        break;


    default:
        $response = array(
            'state' => 405,
            'resp'  => 'Tipo not found '.$tipo
        );
        echo json_encode($response);
        exit;
        break;
}

function address_format($db, $data) {

    include_once 'class.Website.php';

    if (strlen($data['country_code']) == 3) {

        include_once 'class.Country.php';
        $country             = new Country('code', $data['country_code']);
        $country_2alpha_code = $country->get('Country 2 Alpha Code');
    } else {
        $country_2alpha_code = $data['country_code'];
    }

    $website=new Website($data['website_key']);
    $locale=$website->get('Website Locale');

    $country_2alpha_code=strtoupper($country_2alpha_code);

    list($address_format,$address_labels,$used_fields,$hidden_fields,$required_fields,$no_required_fields)=get_address_form_data($country_2alpha_code,$locale );




    $website_localised_labels=$website->get('Localised Labels');

    $labels=array();
    foreach($address_labels as $key=>$value){
        $labels[$key]=(isset($website_localised_labels[$key.'_'.$value['code']])?$website_localised_labels[$key.'_'.$value['code']]:$value['label']  );
    }


    $response = array(
        'state'  => 200,
        'address_format' => $address_format,
        'address_labels' => $address_labels,
        'used_fields' => $used_fields,
        'hidden_fields' => $hidden_fields,
        'required_fields' => $required_fields,
        'no_required_fields' => $no_required_fields,

        'labels' => $labels,

    );

    echo json_encode($response);

}


?>
