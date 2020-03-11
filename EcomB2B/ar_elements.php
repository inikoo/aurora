<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  04 February 2020  21:38::45  +0800, Kuala Lumpur, Malaysia
 Copyright (c) 2020, Inikoo

 Version 3

*/

include_once 'ar_web_common_logged_in.php';


if (!isset($_REQUEST['tab'])) {
    $response = array(
        'state' => 405,
        'resp'  => 'Non acceptable request (t)'
    );
    echo json_encode($response);
    exit;
}


$tab = $_REQUEST['tab'];

switch ($tab) {

    case 'portfolio':
        $data = prepare_values(
            $_REQUEST, array(
                         'parameters' => array('type' => 'json array')
                     )
        );
        get_portfolio_elements($db, $data['parameters'], $customer->id);
        break;
    default:
        $response = array(
            'state' => 405,
            'resp'  => 'Tab not found '.$tab
        );
        echo json_encode($response);
        exit;
        break;
}


/**
 * @param $db \PDO
 * @param $data
 */
function get_portfolio_elements($db, $data, $customer_key) {

    //todo remove availability_state, still here becuse old sessions still need it 11 March 2020

    $elements_numbers = array(
        'status_availability_state' => array(
            'OutofStock'    => 0,
            'VeryLow'       => 0,
            'Low'           => 0,
            'Ok'            => 0,
            'Discontinuing' => 0,
            'Discontinued'  => 0
        ),
        'availability_state'        => array(
            'OutofStock' => 0,
            'VeryLow'    => 0,
            'Low'        => 0,
            'Ok'         => 0,
        ),

    );


    $sql  =
        "select count(*) as number,`Product Availability State` as element from  `Customer Portfolio Fact` CPF left join    `Product Dimension` P  on (`Customer Portfolio Product ID`=P.`Product ID`) where  `Customer Portfolio Customer Key`=? and  `Customer Portfolio Customers State`='Active' group by `Product Availability State` ";
    $stmt = $db->prepare($sql);
    $stmt->execute(
        array(
            $customer_key
        )
    );


    while ($row = $stmt->fetch()) {

        if ($row['element'] == '') {
            continue;
        }

        if ($row['element'] == 'Error') {
            $row['element'] = 'OutofStock';
        } elseif ($row['element'] == 'Excess' or $row['element'] == 'Normal' or $row['element'] == 'OnDemand') {
            $row['element'] = 'Ok';
        }


        $elements_numbers['availability_state'][$row['element']] += $row['number'];
    }


    foreach ($elements_numbers['availability_state'] as $key => $value) {

        $elements_numbers['availability_state'][$key] = number($value);

    }

    $sql  =
        "select count(*) as number,`Product Status Availability State` as element from  `Customer Portfolio Fact` CPF left join    `Product Dimension` P  on (`Customer Portfolio Product ID`=P.`Product ID`) where  `Customer Portfolio Customer Key`=? and  `Customer Portfolio Customers State`='Active' group by `Product Status Availability State` ";
    $stmt = $db->prepare($sql);
    $stmt->execute(
        array(
            $customer_key
        )
    );


    while ($row = $stmt->fetch()) {

        $elements_numbers['availability_state'][$row['element']] = number($row['number']);
    }


    $response = array(
        'state'            => 200,
        'elements_numbers' => $elements_numbers
    );
    echo json_encode($response);


}

