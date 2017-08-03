<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 10 July 2017 at 00:35:25 GMT+8, Cyberjaya, Malaysia
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


//print_r($_REQUEST);

switch ($tipo) {

    case 'contact_details':
        $data = prepare_values(
            $_REQUEST, array(
                         'data' => array('type' => 'json array'),

                     )
        );
        contact_details($db, $data, $customer, $editor);
        break;

    case 'invoice_address':
        $data = prepare_values(
            $_REQUEST, array(
                         'data' => array('type' => 'json array'),

                     )
        );
        invoice_address($db, $data, $customer, $editor);
        break;
    case 'delivery_address':
        $data = prepare_values(
            $_REQUEST, array(
                         'data' => array('type' => 'json array'),

                     )
        );
        delivery_address($db, $data, $customer, $editor);
        break;


    case 'update_password':
        $data = prepare_values(
            $_REQUEST, array(
                         'pwd' => array('type' => 'string'),

                     )
        );
        update_password($db, $data, $editor);
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

function invoice_address($db, $data, $customer, $editor) {





    $address_data=array(
        'Address Line 1'=>'',
        'Address Line 2'=>'',
        'Address Sorting Code'=>'',
        'Address Postal Code'=>'',
        'Address Dependent Locality'=>'',
        'Address Locality'=>'',
        'Address Administrative Area'=>'',
        'Address Country 2 Alpha Code'=>'',
    );


    foreach($data['data'] as $key=>$value){

        if ($key == 'addressLine1') {
            $key = 'Address Line 1';
        } elseif ($key == 'addressLine2') {
            $key = 'Address Line 2';
        } elseif ($key == 'sortingCode') {
            $key = 'Address Sorting Code';
        } elseif ($key == 'postalCode') {
            $key = 'Address Postal Code';
        } elseif ($key == 'dependentLocality') {
            $key = 'Address Dependent Locality';
        } elseif ($key == 'locality') {
            $key = 'Address Locality';
        }elseif ($key == 'administrativeArea') {
            $key = 'Address Administrative Area';
        }elseif ($key == 'country') {
            $key = 'Address Country 2 Alpha Code';
        }

            $address_data[$key]=$value;

    }





    $customer->editor = $editor;
    $customer->update(array('Customer Invoice Address'=>json_encode($address_data)));


    echo json_encode(
        array(
            'state' => 200
        )
    );


}

function delivery_address($db, $data, $customer, $editor) {


    $customer->editor = $editor;


    if($data['data']['delivery_address_link']){
        $customer->update(array('Customer Delivery Address Link'=>'Billing'));

    }else{
        $customer->update(array('Customer Delivery Address Link'=>'None'));

        $address_data=array(
            'Address Line 1'=>'',
            'Address Line 2'=>'',
            'Address Sorting Code'=>'',
            'Address Postal Code'=>'',
            'Address Dependent Locality'=>'',
            'Address Locality'=>'',
            'Address Administrative Area'=>'',
            'Address Country 2 Alpha Code'=>'',
        );


        foreach($data['data'] as $key=>$value){

            if ($key == 'addressLine1') {
                $key = 'Address Line 1';
            } elseif ($key == 'addressLine2') {
                $key = 'Address Line 2';
            } elseif ($key == 'sortingCode') {
                $key = 'Address Sorting Code';
            } elseif ($key == 'postalCode') {
                $key = 'Address Postal Code';
            } elseif ($key == 'dependentLocality') {
                $key = 'Address Dependent Locality';
            } elseif ($key == 'locality') {
                $key = 'Address Locality';
            }elseif ($key == 'administrativeArea') {
                $key = 'Address Administrative Area';
            }elseif ($key == 'country') {
                $key = 'Address Country 2 Alpha Code';
            }

            $address_data[$key]=$value;

        }

        $customer->update(array('Customer Delivery Address'=>json_encode($address_data)));

    }











    echo json_encode(
        array(
            'state' => 200
        )
    );


}

function contact_details($db, $data, $customer, $editor) {



    $update_data = array();

    foreach ($data['data'] as $key => $value) {

        if ($key == 'company') {
            $key = 'Customer Company Name';
        } elseif ($key == 'contact_name') {
            $key = 'Customer Main Contact Name';
        } elseif ($key == 'email') {
            $key = 'Customer Main Plain Email';
        } elseif ($key == 'mobile') {
            $key = 'Customer Main Plain Mobile';
        } elseif ($key == 'registration_number') {
            $key = 'Customer Registration Number';
        } elseif ($key == 'tax_number') {
            $key = 'Customer Tax Number';


        }
        $update_data[$key] = $value;

    }


    $customer->editor = $editor;
    $customer->update($update_data);


    echo json_encode(
        array(
            'state' => 200
        )
    );


}

function update_password($db, $data, $editor) {


    $website_user         = get_object('User', $_SESSION['website_user_key']);
    $website_user->editor = $editor;


    if ($website_user->id) {

        $website_user->update(
            array(
                'Website User Password Hash' => password_hash($data['pwd'], PASSWORD_DEFAULT, array('cost' => 12)),


            )
        );

        $website_user->update(
            array(
                'Website User Password'      => $data['pwd'],


            ),'no_history'
        );


        echo json_encode(
            array(
                'state' => 200
            )
        );
        exit;

    } else {
        echo json_encode(
            array(
                'state'      => 400,
                'error_type' => 'User not found'
            )
        );
        exit;
    }


}


?>
