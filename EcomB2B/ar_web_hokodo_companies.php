<?php
include_once 'ar_web_common_logged_in.php';

include_once 'hokodo/api_call.php';


$website = get_object('Website', $_SESSION['website_key']);
$api_key = $website->get_api_key('Hokodo');


$name='';
$country='';
if(!empty($_REQUEST['name'])){
    $name=trim($_REQUEST['name']);

}
if(!empty($_REQUEST['country'])) {
    $country=trim($_REQUEST['country']);

}


if($name=='' or !in_array($country,['GB'])){
    echo json_encode(
        [
            'results' => [
                'id'   => '',
                'text' => ''
            ]
        ]
    );
    exit;
}

$data = array("name" => $name, "country" => $country);

$raw_results = api_post_call('companies/search', $data, $api_key);



$results = [];
foreach ($raw_results['matches'] as $raw_result) {
    $results[] = [
        'id'   => $raw_result['id'],
        'text' => $raw_result['name']
    ];
}

echo json_encode(
    [
        'results' => $results
    ]
);

