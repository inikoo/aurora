<?php


$shop_key = $_REQUEST['store_key'];

$store = get_object('Store', $shop_key);


$data = array(
    'Customer Main Contact Name'   => $_REQUEST['contact_name'],
    'Customer Company Name'        => $_REQUEST['company_name'],
    'Customer Registration Number' => $_REQUEST['identity_document_number'],
    'Customer Tax Number'          => $_REQUEST['tax_number'],
    'Customer Main Plain Email'    => $_REQUEST['email'],
    'Customer Main Plain Mobile'   => $_REQUEST['phone'],

);


$data['editor']                         = $editor;
$data['Customer Store Key']             = $store->id;
$data['Customer Billing Address Link']  = 'Contact';
$data['Customer Delivery Address Link'] = 'Billing';

$address_fields = array(
    'Address Recipient'            => $_REQUEST['contact_name'],
    'Address Organization'         => $_REQUEST['company_name'],
    'Address Line 1'               => $_REQUEST['address_line_1'],
    'Address Line 2'               => $_REQUEST['address_line_2'],
    'Address Sorting Code'         => $_REQUEST['sorting_code'],
    'Address Postal Code'          => $_REQUEST['postal_code'],
    'Address Dependent Locality'   => $_REQUEST['dependent_locality'],
    'Address Locality'             => $_REQUEST['locality'],
    'Address Administrative Area'  => $_REQUEST['administrative_area'],
    'Address Country 2 Alpha Code' => $_REQUEST['country_code'],

);


$response = array(
    'customer_data' => $data,
    'address_data'  => $address_fields,


);
echo json_encode($response);
exit;


//$customer = new Public_Customer('new', $data, $address_fields);