<?php

require_once 'class.Customer.php';


$sql = "select `Customer Key` from `Customer Dimension` where `from_aiku_id`=? ";

$stmt = $db->prepare($sql);
$stmt->execute(
    [
        $_REQUEST['aiku_id'],
    ]
);
if ($row = $stmt->fetch()) {
    $customer = get_object('Customer', $row['Customer Key']);
    $customer->fast_update([
        'Customer Send Newsletter'      => $_REQUEST['send_newsletter'] ? 'Yes' : 'No',
        'Customer Send Email Marketing' => $_REQUEST['send_marketing'] ? 'Yes' : 'No',
        'Customer Main Plain Email'     => $_REQUEST['email'],
        'Customer First Contacted Date' => $_REQUEST['created_at']
    ]);

    $response = array(
        'state'        => 'Found',
        'msg'          => 'customer found and updated',
        'customer_key' => $customer->id
    );
    echo json_encode($response);
    exit;
}


$shop_key = $_REQUEST['store_key'];

$store = get_object('Store', $shop_key);


$data = array(
    'Customer Main Plain Email'     => $_REQUEST['email'],
    'Customer Send Newsletter'      => $_REQUEST['send_newsletter'] ? 'Yes' : 'No',
    'Customer Send Email Marketing' => $_REQUEST['send_marketing'] ? 'Yes' : 'No',

);


if (array_key_exists('phone', $_REQUEST)) {
    $data['Customer Main Plain Mobile'] = $_REQUEST['phone'];
}

if (array_key_exists('tax_number', $_REQUEST)) {
    $data['Customer Tax Number'] = $_REQUEST['tax_number'];
}

if (array_key_exists('identity_document_number', $_REQUEST)) {
    $data['Customer Registration Number'] = $_REQUEST['identity_document_number'];
}

if (array_key_exists('company_name', $_REQUEST)) {
    $data['Customer Main Contact Name'] = $_REQUEST['company_name'];
}

if (array_key_exists('company_name', $_REQUEST)) {
    $data['Customer Company Name'] = $_REQUEST['company_name'];
}

$data['editor']                         = $editor;
$data['Customer Store Key']             = $store->id;
$data['Customer Billing Address Link']  = 'Contact';
$data['Customer Delivery Address Link'] = 'Billing';

if (array_key_exists('locality', $_REQUEST)) {
    $data['Customer Contact Address locality'] = $_REQUEST['locality'];
}

if (array_key_exists('postal_code', $_REQUEST)) {
    $data['Customer Contact Address postalCode'] = $_REQUEST['postal_code'];
}

if (array_key_exists('address_line_1', $_REQUEST)) {
    $data['Customer Contact Address addressLine1'] = $_REQUEST['address_line_1'];
}

if (array_key_exists('address_line_2', $_REQUEST)) {
    $data['Customer Contact Address addressLine2'] = $_REQUEST['address_line_2'];
}

if (array_key_exists('administrative_area', $_REQUEST)) {
    $data['Customer Contact Address administrativeArea'] = $_REQUEST['administrative_area'];
}

if (array_key_exists('dependent_locality', $_REQUEST)) {
    $data['Customer Contact Address dependentLocality'] = $_REQUEST['dependent_locality'];
}

if (array_key_exists('sorting_code', $_REQUEST)) {
    $data['Customer Contact Address sortingCode'] = $_REQUEST['sorting_code'];
}

if (array_key_exists('country_code', $_REQUEST)) {
    $data['Customer Contact Address country'] = $_REQUEST['country_code'];
}


list($customer, $website_user) = $store->create_customer($data, array('Website User Password' => random_string_a(16)));


if ($store->new_customer_id) {
    $customer = get_object('Customer', $store->new_customer_id);

    $customer->fast_update([
        'from_aiku_id'                  => $_REQUEST['aiku_id'],
        'Customer First Contacted Date' => $_REQUEST['created_at']
    ]);
}


$response = array(
    'customer_key'     => $store->new_customer_id,
    'error'            => $store->error,
    'error_info'       => $store->msg,
    //  'customer_key'     => $customer->id,
    'website_user_key' => $website_user->id,
    //  'customer_data'    => $data,


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