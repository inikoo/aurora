<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  05 February 2020  20:02::04  +0800, Kuala Lumpur, Malaysia
 Copyright (c) 20156 Inikoo

 Version 3

*/

include_once 'ar_web_common_logged_in.php';


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




    case 'find_objects':

        $data = prepare_values(
            $_REQUEST, array(
                         'query'      => array('type' => 'string'),
                         'scope'      => array('type' => 'string'),
                         'action'     => array(
                             'type'     => 'string',
                             'optional' => true
                         ),
                         'parent'     => array(
                             'type'     => 'string',
                             'optional' => true
                         ),
                         'parent_key' => array(
                             'type'     => 'numeric',
                             'optional' => true
                         ),
                         'state'      => array(
                             'type'     => 'json array',
                             'optional' => true
                         ),
                         'metadata'   => array(
                             'type'     => 'json array',
                             'optional' => true
                         )
                     )
        );


        switch ($data['scope']) {


            case 'item':
                $website = get_object('Website', $_SESSION['website_key']);

                find_products($db, $data['query'] ,$website->get('Website Store Key'));

              break;

            default:
                $response = array(
                    'state' => 405,
                    'resp'  => 'Scope not found '.$data['scope']
                );
                echo json_encode($response);
                exit;
        }


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



function find_products($db, $q, $store_key) {


    $max_results = 5;


    if ($q == '') {
        $response = array(
            'state'   => 200,
            'results' => 0,
            'data'    => ''
        );
        echo json_encode($response);

        return;
    }




        $where = sprintf("  and `Product Store Key`=%d and  `Product Status` not in ( 'Suspended','Discontinued')  ",$store_key);






        $candidates = array();

        $candidates_data = array();


        $sql = sprintf(
            "select `Product ID`,`Product Code`,`Product Name`,`Product Current Key`,`Product Availability` from `Product Dimension` where  `Product Code` like '%s%%' %s order by `Product Code` limit $max_results ", $q, $where
        );


        if ($result = $db->query($sql)) {
            foreach ($result as $row) {

                if ($row['Product Code'] == $q) {
                    $candidates[$row['Product ID']] = 1000;
                } else {

                    $len_name                       = strlen(
                        $row['Product ID']
                    );
                    $len_q                          = strlen($q);
                    $factor                         = $len_q / $len_name;
                    $candidates[$row['Product ID']] = 500 * $factor;
                }

                $candidates_data[$row['Product ID']] = array(
                    'Product Code'        => $row['Product Code'],
                    'Product Name'        => $row['Product Name'].', <span style="font-style: italic"  class="'.($row['Product Availability'] <= 0 ? 'error' : '').'" >'._('Stock').': '.number($row['Product Availability']).'</span>',
                    'Product Current Key' => $row['Product Current Key']

                );

            }
        } else {
            print_r($error_info = $db->errorInfo());
            exit;
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
        foreach ($candidates as $product_sku => $candidate) {

            $results[$product_sku] = array(
                'code'              => $candidates_data[$product_sku]['Product Code'],
                'description'       => $candidates_data[$product_sku]['Product Name'],
                'item_historic_key' => $candidates_data[$product_sku]['Product Current Key'],

                'value'           => $product_sku,
                'formatted_value' => $candidates_data[$product_sku]['Product Code']
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

