<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sun, 12 Jun 2022 11:59:50 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 3.0
 */

include_once 'ar_web_common_logged_out.php';
include_once 'hokodo/api_call.php';


$website = get_object('Website', $_SESSION['website_key']);
$api_key = $website->get_api_key('Hokodo');

$name    = '';
$country = '';
if (!empty($_REQUEST['name'])) {
    $name = trim($_REQUEST['name']);
}
if (!empty($_REQUEST['country'])) {
    $country = trim($_REQUEST['country']);
}


if ($name == '' or !in_array($country, ['GB','FR','ES','BE','NL'])) {
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


$raw_results = api_post_call('companies/search', $data);


$results = [];
foreach ($raw_results['matches'] as $raw_result) {
    $results[] = [
        'id'   => $raw_result['id'],
        'text' => $raw_result['name'],
        'data' => $raw_result
    ];
}

echo json_encode(
    [
        'results' => $results
    ]
);

