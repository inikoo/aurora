<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 21 July 2017 at 09:38:41 CEST, Trnava, Slavakia
 Copyright (c) 2016, Inikoo

 Version 3

*/

include_once 'ar_web_common_logged_in.php';

$account = get_object('Account', 1);

$website = get_object('Website', $_SESSION['website_key']);


if (!isset($_REQUEST['tipo'])) {
    $response = array(
        'state' => 407,
        'resp'  => 'Non acceptable request (t)'
    );
    echo json_encode($response);
    exit;
}

$tipo = $_REQUEST['tipo'];

switch ($tipo) {


    case 'find_product':
        $data = prepare_values(
            $_REQUEST, array(
                         'product_code' => array(
                             'type' => 'string',
                         )
                     )
        );

        find_products($db, $website, $customer, $data);


        break;

}


function find_products($db, $website, $customer, $data) {


    $max_results = 5;
    $q           = trim($data['product_code']);


    if ($q == '') {
        $response = array(
            'state'   => 200,
            'results' => 0,
            'data'    => ''
        );
        echo json_encode($response);

        return;
    }


    $candidates = array();

    $candidates_data = array();


    $sql =
        "select `Product ID`,`Product Code`,`Product Name`,`Product Current Key`,`Product Availability`,`Product Web State`,`Customer Portfolio Reference` from `Product Dimension`  left join  `Customer Portfolio Fact` on (`Product ID`=`Customer Portfolio Product ID`)  where `Product Store Key`=? and  `Product Web State` in ('For Sale','Out of Stock') and `Product Code` like ? order by  `Product Code` limit "
        .$max_results * 2;

    $stmt = $db->prepare($sql);
    $stmt->execute(
        array(
            $website->get('Website Store Key'),
            $q.'%'

        )
    );


    while ($row = $stmt->fetch()) {


        if ($row['Product Code'] == $q) {
            $candidates[$row['Product ID']] = 1000;
        } else {

            $len_name = strlen($row['Product ID']);
            $len_q    = strlen($q);
            $factor   = $len_q / $len_name;


            $candidates[$row['Product ID']] = 500 * $factor;
        }


        if ($row['Product Web State'] == 'Out of Stock') {
            $candidates[$row['Product ID']] *= 0.75;
            $name                           = $row['Product Name'].', <span style="font-style: italic;"  class="error">'._('Out of stock').'</span>';

        } else {
            $name = $row['Product Name'];
        }


        $candidates_data[$row['Product ID']] = array(
            'Product Code'        => $row['Product Code'].($row['Customer Portfolio Reference']!=''?' ('.$row['Customer Portfolio Reference'].')':''),
            'Product Name'        => $name,
            'Product Current Key' => $row['Product Current Key'],
            'Product Web State'   => $row['Product Web State']

        );
    }

    $sql =
        "select `Product ID`,`Product Code`,`Product Name`,`Product Current Key`,`Product Availability`,`Product Web State`,`Customer Portfolio Reference` from  `Customer Portfolio Fact` left join    `Product Dimension`  on (`Product ID`=`Customer Portfolio Product ID`)    where `Customer Portfolio Customer Key`=? and  `Product Web State` in ('For Sale','Out of Stock') and `Customer Portfolio Reference` like ? order by  `Customer Portfolio Reference` limit "
        .$max_results * 2;

    $stmt = $db->prepare($sql);
    $stmt->execute(
        array(
            $customer->id,
            $q.'%'

        )
    );

    while ($row = $stmt->fetch()) {


        if ($row['Customer Portfolio Reference'] == $q) {
            $candidates[$row['Product ID']] = 1000;
        } else {

            $len_name = strlen($row['Product ID']);
            $len_q    = strlen($q);
            $factor   = $len_q / $len_name;


            $candidates[$row['Product ID']] = 500 * $factor;
        }


        if ($row['Product Web State'] == 'Out of Stock') {
            $candidates[$row['Product ID']] *= 0.75;
            $name                           = $row['Product Name'].', <span style="font-style: italic;"  class="error">'._('Out of stock').'</span>';

        } else {
            $name = $row['Product Name'];
        }


        $candidates_data[$row['Product ID']] = array(
            'Product Code'        => $row['Product Code'].' ('.$row['Customer Portfolio Reference'].')',
            'Product Name'        => $name,
            'Product Current Key' => $row['Product Current Key'],
            'Product Web State'   => $row['Product Web State']

        );
    }

    arsort($candidates);


    $total_candidates = count($candidates);

    if ($total_candidates == 0) {
        $response = array(
            'state'   => 200,
            'results' => 0,
            'data'    => ''
        );
        echo json_encode($response);

        return;
    }


    $results = array();
    foreach ($candidates as $product_id => $candidate) {

        $results[$product_id] = array(
            'code'              => $candidates_data[$product_id]['Product Code'],
            'description'       => $candidates_data[$product_id]['Product Name'],
            'item_historic_key' => $candidates_data[$product_id]['Product Current Key'],
            'state'             => ($candidates_data[$product_id]['Product Web State'] == 'Out of Stock' ? 'out_of_stock' : 'ok'),

            'value'           => $product_id,
            'formatted_value' => $candidates_data[$product_id]['Product Code']
        );

    }

    $results_data = array(
        'n' => count($results),
        'd' => $results
    );


    $response = array(
        'state'          => 200,
        'number_results' => $results_data['n'],
        'results'        => $results_data['d'],
        'q'              => $q
    );

    echo json_encode($response);

}
