<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 21 January 2016 at 23:12:07 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'utils/ar_common.php';
require_once 'utils/table_functions.php';
require_once 'utils/date_functions.php';
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
    case 'fields_data':
        $data = prepare_values(
            $_REQUEST, array(
                'country_code' => array('type' => 'string')
            )
        );
        get_fields_data($db, $data);
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

function get_fields_data($db, $data) {


    if (strlen($data['country_code']) == 3) {

        include_once 'class.Country.php';
        $country             = new Country('code', $data['country_code']);
        $country_2alpha_code = $country->get('Country 2 Alpha Code');
    } else {
        $country_2alpha_code = $data['country_code'];
    }

    $address_format       = get_address_format($country_2alpha_code);
    $address_subdivisions = get_address_subdivisions(
        $country_2alpha_code, $locale = null
    );

    $required_fields = $address_format->getRequiredFields();


    //print_r($address_format->getUsedSubdivisionFields());

    $_address_format = $address_format->getFormat();
    $_address_format = preg_replace('/\//', ' ', $_address_format);

    $used_fields = preg_split(
        '/\s+/', preg_replace('/(%|,)/', '', $_address_format)
    );
    $labels      = array(
        'postalCode'         => $address_format->getPostalCodeType(),
        'dependentLocality'  => $address_format->getDependentLocalityType(),
        'locality'           => $address_format->getLocalityType(),
        'administrativeArea' => $address_format->getAdministrativeAreaType(),
    );


    $address_fields = array(
        'recipient'          => array(
            'required' => false,
            'render'   => false,
            'label'    => _('Recipient')
        ),
        'organization'       => array(
            'required' => false,
            'render'   => false,
            'label'    => _('Organization')
        ),
        'addressLine1'       => array(
            'required' => false,
            'render'   => false,
            'label'    => _('Line 1')
        ),
        'addressLine2'       => array(
            'required' => false,
            'render'   => false,
            'label'    => _('Line 2')
        ),
        'sortingCode'        => array(
            'required' => false,
            'render'   => false,
            'label'    => _('Sorting code')
        ),
        'postalCode'         => array(
            'required' => false,
            'render'   => false,
            'label'    => _('Postal code')
        ),
        'dependentLocality'  => array(
            'required' => false,
            'render'   => false,
            'label'    => _('Dependent locality')
        ),
        'locality'           => array(
            'required' => false,
            'render'   => false,
            'label'    => _('Locality (City)')
        ),
        'administrativeArea' => array(
            'required' => false,
            'render'   => false,
            'label'    => _('Administrative area')
        ),

    );

    foreach ($used_fields as $used_field) {
        $address_fields[$used_field]['render'] = true;
    }


    foreach ($required_fields as $required_field) {
        $address_fields[$required_field]['required'] = true;
    }

    foreach ($labels as $key => $label) {
        if ($label != '') {
            if ($label == 'postal') {
                $label = _('Postal code');
            } elseif ($label == 'post_town') {
                $label = _('Post town');
            } else {
                $label = ucfirst(_($label));
            }
            $address_fields[$key]['label'] = $label;
        }
    }


    $address_fields = array_merge(array_flip($used_fields), $address_fields);


    $response = array(
        'state'  => 200,
        'fields' => $address_fields

    );

    echo json_encode($response);

}


?>
