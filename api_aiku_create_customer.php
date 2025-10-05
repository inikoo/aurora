<?php


$shop_key = $_REQUEST['store_key'];

$store = get_object('Store', $shop_key);


$data = array(
    'Customer Main Contact Name'    => $_REQUEST['contact_name'],
    'Customer Company Name'         => $_REQUEST['company_name'],
    'Customer Registration Number'  => $_REQUEST['identity_document_number'],
    'Customer Tax Number'           => $_REQUEST['tax_number'],
    'Customer Main Plain Email'     => $_REQUEST['email'],
    'Customer Main Plain Mobile'    => $_REQUEST['phone'],
    'Customer Send Newsletter'      => $_REQUEST['send_newsletter'] ? 'Yes' : 'No',
    'Customer Send Email Marketing' => $_REQUEST['send_marketing'] ? 'Yes' : 'No',

);


$data['editor']                         = $editor;
$data['Customer Store Key']             = $store->id;
$data['Customer Billing Address Link']  = 'Contact';
$data['Customer Delivery Address Link'] = 'Billing';


$data['Customer Contact Address locality']           = $_REQUEST['locality'];
$data['Customer Contact Address postalCode']         = $_REQUEST['postal_code'];
$data['Customer Contact Address addressLine1']       = $_REQUEST['address_line_1'];
$data['Customer Contact Address addressLine2']       = $_REQUEST['address_line_2'];
$data['Customer Contact Address administrativeArea'] = $_REQUEST['administrative_area'];
$data['Customer Contact Address dependentLocality']  = $_REQUEST['dependent_locality'];
$data['Customer Contact Address sortingCode']        = $_REQUEST['sorting_code'];
$data['Customer Contact Address country']            = $_REQUEST['country_code'];


list($customer, $website_user) = $store->create_customer($data, array('Website User Password' => random_string_a(16)));


$response = array(
    'customer_key'  => $customer->id,
    'website_user_key'   => $website_user->id,
    'customer_data' => $data,


);
echo json_encode($response);
exit;

function random_string_a(int $length = 32, string $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'): string
{
    $result   = '';
    $maxIndex = strlen($alphabet) - 1;
    for ($i = 0; $i < $length; $i++) {
        $idx    = random_int(0, $maxIndex); // cryptographically secure
        $result .= $alphabet[$idx];
    }

    return $result;
}

//$customer = new Public_Customer('new', $data, $address_fields);