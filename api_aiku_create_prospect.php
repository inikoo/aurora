<?php
include_once 'keyring/key.php';

require_once 'class.Prospect.php';

echo json_encode($_REQUEST);
exit;

if (isset($_REQUEST['prospect_key']) && $_REQUEST['prospect_key']) {
    $prospect = get_object('Prospect', $_REQUEST['prospect_key']);
    if ($prospect) {
        $prospect->fast_update([
            'from_aiku_id' => $_REQUEST['aiku_id'],
        ]);
    }
}


$sql = "select `Prospect Key` from `Prospect Dimension` where `from_aiku_id`=? ";

$stmt = $db->prepare($sql);
$stmt->execute(
    [
        $_REQUEST['aiku_id'],
    ]
);
if ($row = $stmt->fetch()) {
    $prospect = get_object('Prospect', $row['Prospect Key']);
    $prospect->fast_update([
        'Prospect Status'      => $_REQUEST['status'] ? 'Yes' : 'No',
        'Prospect Main Plain Email'     => $_REQUEST['email'],
        'Prospect Created Date' => $_REQUEST['created_at'] ?? '',
        'Prospect Main Contact Name'    => $_REQUEST['company_name'] ?? '',
        'Prospect Company Name'         => $_REQUEST['contact_name'] ?? '',
    ]);

    $response = array(
        'state'        => 'Found',
        'msg'          => 'prospect found and updated',
        'prospect_key' => $prospect->id,
        'data'         => [
            'Prospect Status'           => $_REQUEST['status'],
            'Prospect Main Plain Email' => $_REQUEST['email'],
            'Prospect Created Date'     => $_REQUEST['created_at']
        ]
    );
    echo json_encode($response);
    exit;
}


$shop_key = $_REQUEST['store_key'];

$store = get_object('Store', $shop_key);


$data = array(
    'Prospect Main Plain Email' => $_REQUEST['email'],
    'Prospect Status'           => $_REQUEST['status'],
    'Prospect Opt In'           => $_REQUEST['opt_in'],

);

if (array_key_exists('customer_key', $_REQUEST)) {
    $data['Prospect Customer Key'] = $_REQUEST['customer_key'];
}

if (array_key_exists('phone', $_REQUEST)) {
    $data['Prospect Main Plain Mobile'] = $_REQUEST['phone'];
}


if (array_key_exists('contact_name', $_REQUEST)) {
    $data['Prospect Main Contact Name'] = $_REQUEST['contact_name'];
}

if (array_key_exists('company_name', $_REQUEST)) {
    $data['Prospect Company Name'] = $_REQUEST['company_name'];
}

$data['editor']             = $editor;
$data['Prospect Store Key'] = $store->id;

if (array_key_exists('locality', $_REQUEST)) {
    $data['Prospect Contact Address locality'] = $_REQUEST['locality'];
}

if (array_key_exists('postal_code', $_REQUEST)) {
    $data['Prospect Contact Address postalCode'] = $_REQUEST['postal_code'];
}

if (array_key_exists('address_line_1', $_REQUEST)) {
    $data['Prospect Contact Address addressLine1'] = $_REQUEST['address_line_1'];
}

if (array_key_exists('address_line_2', $_REQUEST)) {
    $data['Prospect Contact Address addressLine2'] = $_REQUEST['address_line_2'];
}

if (array_key_exists('administrative_area', $_REQUEST)) {
    $data['Prospect Contact Address administrativeArea'] = $_REQUEST['administrative_area'];
}

if (array_key_exists('dependent_locality', $_REQUEST)) {
    $data['Prospect Contact Address dependentLocality'] = $_REQUEST['dependent_locality'];
}

if (array_key_exists('sorting_code', $_REQUEST)) {
    $data['Prospect Contact Address sortingCode'] = $_REQUEST['sorting_code'];
}

if (array_key_exists('country_code', $_REQUEST)) {
    $data['Prospect Contact Address country'] = $_REQUEST['country_code'];
}


$prospect = $store->create_prospect($data);


if ($store->new_prospect_id) {
    $prospect = get_object('Prospect', $store->new_prospect_id);

    $prospect->fast_update([
        'from_aiku_id'          => $_REQUEST['aiku_id'],
        'Prospect Created Date' => $_REQUEST['created_at']
    ]);
}


$response = array(
    'prospect_key' => $store->new_prospect_id,
    'error'        => $store->error,
    'error_info'   => $store->msg,
    'aiku_id'      => $_REQUEST['aiku_id'],
    'email'        => $_REQUEST['email'],
);

echo json_encode($response);
exit;

