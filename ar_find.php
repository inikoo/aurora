<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 1 March 2016 at 10:37:32 GMT+8, Yuwu, China
 Copyright (c) 20156 Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'utils/ar_common.php';
require_once 'utils/table_functions.php';
require_once 'utils/text_functions.php';
require_once 'utils/object_functions.php';


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


    case 'new_purchase_order_options':

        $data = prepare_values(
            $_REQUEST, array(
                         'parent'     => array('type' => 'string'),
                         'parent_key' => array('type' => 'key'),
                     )
        );
        new_purchase_order_options($db, $data);

        break;
    case 'new_agent_delivery_options':

        $data = prepare_values(
            $_REQUEST, array(
                         'parent'     => array('type' => 'string'),
                         'parent_key' => array('type' => 'key'),
                     )
        );
        new_agent_delivery_options($db, $data);

        break;

    case 'find_object':

        $data = prepare_values(
            $_REQUEST, array(
                         'query'      => array('type' => 'string'),
                         'scope'      => array('type' => 'string'),
                         'field'      => array(
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
                         'state'      => array('type' => 'json array'),
                         'metadata'   => array(
                             'type'     => 'json array',
                             'optional' => true
                         )
                     )
        );

        $data['user'] = $user;

        switch ($data['scope']) {

            case 'part':
                find_part($db, $data);
                break;
            case 'location':
                find_location($db, $data);
                break;

            default:
                $response = array(
                    'state' => 405,
                    'resp'  => 'Scope not found: '.$data['scope']
                );
                echo json_encode($response);
                exit;
        }


        break;

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


        $data['user'] = $user;
        switch ($data['scope']) {

            case 'parts':
            case 'part_in_supplier_delivery':

                find_parts($db, $data);

                break;

            case 'item':

                if ($data['metadata']['scope'] == 'supplier_part') {
                    find_supplier_parts($db, $data);

                } elseif ($data['metadata']['scope'] == 'product') {
                    find_products($db, $data);


                } elseif ($data['metadata']['scope'] == 'part') {
                    find_parts($db, $data);


                }
                break;
            case 'allowance_target':
                find_allowance_targets($db, $data);
                break;
            case 'customers':
                find_customers($db, $data);
                break;
            case 'customer_lists':
                find_customer_lists($db, $data);
                break;
            case 'assets_on_sale':
                find_assets_on_sale($db, $data);
                break;
                break;
            case 'employee':
                find_employees($db, $data);
                break;
            case 'suppliers':
                find_suppliers($db, $data);
                break;
            case 'stores':
                find_stores($db, $data);
                break;
            case 'locations':
                find_locations($db, $data, $user);
                break;
            case 'warehouse_areas':
                find_warehouse_areas($db, $data);
                break;
            case 'countries':
                find_countries($db, $data);
                break;
            case 'products':
                find_products($db, $data);
                break;
            case 'families':
                find_families($db, $data);
                break;
            case 'webpages':
                find_webpages($db, $data);
                break;

            case 'product_categories':
                find_special_category('product_categories', $db, $account, $data);
                break;


            case 'departments':
                find_special_category('Department', $db, $account, $data);
                break;
            case 'part_families':
                find_special_category('PartFamily', $db, $account, $data);
                break;

            case 'product_webpages':
                find_product_webpages($db, $data);
                break;
            case 'category_webpages':
                find_category_webpages($db, $data, $smarty);
                break;
            case 'users':
                find_users($db, $data);
                break;
            case 'raw_materials':
                find_raw_materials($db, $data);
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
    case 'orders_in_process':

        $data = prepare_values(
            $_REQUEST, array(
                         'customer_key' => array('type' => 'key'),
                     )
        );
        orders_in_process($db, $data);
        break;

    case 'users_with_right':
        $data = prepare_values(
            $_REQUEST, array(
                         'right' => array('type' => 'string'),

                     )
        );
        users_with_right($db, $data);
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

function users_with_right($db, $data) {


    if (preg_match('/^(.+)-(\d+)$/', $data['right'], $matches)) {
        $right_code = $matches[1];
        $scope_key  = $matches[2];

        $scope = 'Store';

        $users_data = array();
        $sql        = 'select U.`User Key`,`User Alias`,`User Inikoo Rep` as UIR,`Scope`,`Scope Key` 
                from `User Dimension` U left join `User Rights Bridge` URB on (URB.`User Key`=U.`User Key`)   left join `User Right Scope Bridge`  URSB on (URSB.`User Key`=U.`User Key`)   
                where `Right Code`=?  and `Scope`=? and `Scope Key`=? ';
        $stmt       = $db->prepare($sql);
        $stmt->execute(
            array(
                $right_code,
                $scope,
                $scope_key
            )
        );
        while ($row = $stmt->fetch()) {


            $users_data[] = $row;
        }

    } else {

        $users_data = array();
        $sql        = 'select U.`User Key`,`User Alias`,`User Inikoo Rep` as UIR from `User Dimension` U left join `User Rights Bridge` URB on (URB.`User Key`=U.`User Key`) where `Right Code`=?  ';


        $stmt = $db->prepare($sql);
        $stmt->execute(
            array($data['right'])
        );
        while ($row = $stmt->fetch()) {
            $users_data[] = $row;
        }

    }


    $response = array(
        'state'      => 200,
        'users_data' => $users_data
    );
    echo json_encode($response);

}

function find_users($db, $data) {


    $max_results = 10;

    $queries = trim($data['query']);

    if ($queries == '') {
        $response = array(
            'state'   => 200,
            'results' => 0,
            'data'    => ''
        );
        echo json_encode($response);

        return;
    }


    if (!empty($data['metadata']['for_mixed_recipients'])) {
        $scope = 'for_mixed_recipients';

    } else {
        $scope = '';
    }


    $candidates = array();

    $query_array = preg_split('/\s+/', $queries);


    foreach ($query_array as $q) {


        $sql = "SELECT `User Key`,`User Handle` FROM `User Dimension` WHERE  `User Active`='Yes' and `User Type` in ('Staff','Contractor') and  `User Handle` LIKE ? LIMIT 20 ";

        $stmt = $db->prepare($sql);
        if ($stmt->execute(
            array($q.'%')
        )) {
            while ($row = $stmt->fetch()) {

                if ($row['User Handle'] == $q) {
                    $candidates[$row['User Key']] = 1000;
                } else {

                    $len_name                     = strlen($row['User Handle']);
                    $len_q                        = strlen($q);
                    $factor                       = $len_q / $len_name;
                    $candidates[$row['User Key']] = 500 * $factor;
                }
            }
        } else {
            print_r($error_info = $db->errorInfo());
            exit();
        }


        $sql = "SELECT `User Key`,`User Alias` FROM `User Dimension` WHERE  `User Active`='Yes' and `User Type` in ('Staff','Contractor') and  `User Alias`  REGEXP ? LIMIT 100 ";


        $stmt = $db->prepare($sql);
        if ($stmt->execute(
            array(
                '\\b'.$q
            )
        )) {
            while ($row = $stmt->fetch()) {
                if ($row['User Alias'] == $q) {
                    $candidates[$row['User Key']] = 55;
                } else {

                    $len_name                     = strlen($row['User Alias']);
                    $len_q                        = strlen($q);
                    $factor                       = $len_q / $len_name;
                    $candidates[$row['User Key']] = 50 * $factor;
                }
            }
        } else {
            print_r($error_info = $db->errorInfo());
            exit();
        }


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


    $candidates = array_slice(array_keys($candidates), 0, $max_results);
    $sql        = 'SELECT `User Handle`,`User Key`,`User Alias`,`User Password Recovery Email` FROM `User Dimension`  WHERE `User Key` IN ('.implode(',', array_fill(1, count($candidates), '?')).')';


    $stmt = $db->prepare($sql);
    if ($stmt->execute(
        $candidates
    )) {
        while ($row = $stmt->fetch()) {


            if ($scope == 'for_mixed_recipients') {
                $formatted_value = $row['User Alias'];
            } else {
                $formatted_value = $row['User Alias'].($row['User Password Recovery Email'] == '' ? ' <i class="fa padding_left_10 error fa-exclamation-circle"></i> <span class="error very_discreet italic">'._('No email set').'</span>'
                        : ' <span class="italic padding_left_5 discreet">('.$row['User Password Recovery Email'].')</span>');
            }


            $results[$row['User Key']] = array(
                'code'        => highlightkeyword(sprintf('%s', $row['User Handle']), $queries),
                'description' => highlightkeyword($row['User Alias'], $queries),

                'value'           => $row['User Key'],
                'formatted_value' => $formatted_value,
                'metadata'        => array(
                    'email'  => $row['User Password Recovery Email'],
                    'handle' => $row['User Handle']
                )


            );
        }
    } else {
        print_r($error_info = $db->errorInfo());
        exit();
    }


    $results_data = array(
        'n' => count($results),
        'd' => $results
    );

    $response = array(
        'state'          => 200,
        'number_results' => $results_data['n'],
        'results'        => $results_data['d'],
        'q'              => $queries
    );

    echo json_encode($response);

}

function find_suppliers($db, $data) {


    $max_results = 10;
    $queries     = trim($data['query']);

    if ($queries == '') {
        $response = array(
            'state'   => 200,
            'results' => 0,
            'data'    => ''
        );
        echo json_encode($response);

        return;
    }


    $candidates = array();

    $query_array = preg_split('/\s+/', $queries);


    foreach ($query_array as $q) {


        $sql = sprintf(
            "SELECT `Supplier Key`,`Supplier Code`,`Supplier Name` FROM `Supplier Dimension` WHERE  `Supplier Code` LIKE '%s%%' LIMIT 20 ", $q
        );


        if ($result = $db->query($sql)) {
            foreach ($result as $row) {

                if ($row['Supplier Code'] == $q) {
                    $candidates[$row['Supplier Key']] = 1000;
                } else {

                    $len_name                         = strlen(
                        $row['Supplier Code']
                    );
                    $len_q                            = strlen($q);
                    $factor                           = $len_q / $len_name;
                    $candidates[$row['Supplier Key']] = 500 * $factor;
                }

            }
        } else {
            print_r($error_info = $db->errorInfo());
            exit;
        }


        $sql  = "SELECT `Supplier Key`,`Supplier Code`,`Supplier Name` FROM `Supplier Dimension` WHERE  `Supplier Name`  REGEXP ? LIMIT 100 ";
        $stmt = $db->prepare($sql);
        $stmt->execute(
            array(
                '\\b'.$q
            )
        );
        while ($row = $stmt->fetch()) {
            if ($row['Supplier Name'] == $q) {
                $candidates[$row['Supplier Key']] = 55;
            } else {

                $len_name                         = strlen($row['Supplier Name']);
                $len_q                            = strlen($q);
                $factor                           = $len_q / $len_name;
                $candidates[$row['Supplier Key']] = 50 * $factor;
            }
        }


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

    $counter      = 0;
    $product_keys = '';
    $results      = array();

    foreach ($candidates as $key => $val) {
        $counter++;
        $product_keys  .= ','.$key;
        $results[$key] = '';
        if ($counter > $max_results) {
            break;
        }
    }
    $product_keys = preg_replace('/^,/', '', $product_keys);

    $sql = sprintf(
        "SELECT `Supplier Code`,`Supplier Key`,`Supplier Name`,`Supplier Default Currency Code` FROM `Supplier Dimension` S WHERE `Supplier Key` IN (%s)", $product_keys
    );

    if ($result = $db->query($sql)) {
        foreach ($result as $row) {

            $results[$row['Supplier Key']] = array(
                'code'        => highlightkeyword(
                    sprintf('%s', $row['Supplier Code']), $queries
                ),
                'description' => highlightkeyword(
                    $row['Supplier Name'], $queries
                ),

                'value'           => $row['Supplier Key'],
                //'formatted_value'=>$row['Supplier Name'].(($row['Supplier Code']!='' and $row['Supplier Code']!=$row['Supplier Name'])?' ('.$row['Supplier Code'].')':''),
                'formatted_value' => $row['Supplier Code'],
                'metadata'        => array(
                    'other_fields' => array(
                        'Supplier_Part_Unit_Cost'       => array(
                            'field'           => 'Supplier_Part_Unit_Cost',
                            'render'          => true,
                            'placeholder'     => sprintf(
                                _('amount in %s'), $row['Supplier Default Currency Code']
                            ),
                            'value'           => '',
                            'formatted_value' => '',
                            'locked'          => false


                        ),
                        'Supplier_Part_Unit_Extra_Cost' => array(
                            'field'           => 'Supplier_Part_Unit_Extra_Cost_Percentage',
                            'render'          => true,
                            'placeholder'     => '%',
                            'value'           => '',
                            'formatted_value' => '',
                            'locked'          => false


                        ),
                        'Supplier_Part_Reference'       => array(
                            'field'             => 'Supplier_Part_Reference',
                            'render'            => true,
                            'value'             => '',
                            'formatted_value'   => '',
                            'locked'            => false,
                            'server_validation' => json_encode(
                                array(
                                    'tipo'       => 'check_for_duplicates',
                                    'parent'     => 'supplier',
                                    'parent_key' => $row['Supplier Key']
                                )
                            ),


                        )
                    )
                )


            );

        }
    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }


    $results_data = array(
        'n' => count($results),
        'd' => $results
    );

    $response = array(
        'state'          => 200,
        'number_results' => $results_data['n'],
        'results'        => $results_data['d'],
        'q'              => $queries
    );

    echo json_encode($response);

}

function find_stores($db, $data) {


    $max_results = 10;
    $user        = $data['user'];
    $queries     = trim($data['query']);

    if ($queries == '') {
        $response = array(
            'state'   => 200,
            'results' => 0,
            'data'    => ''
        );
        echo json_encode($response);

        return;
    }


    $where_store = sprintf(
        ' and `Store Key` in (%s)', join(',', $user->stores)
    );


    $candidates = array();

    $query_array = preg_split('/\s+/', $queries);


    foreach ($query_array as $q) {


        $sql = sprintf(
            "select `Store Key`,`Store Code`,`Store Name` from `Store Dimension` where true $where_store and `Store Code` like '%s%%' limit 20 ", $q
        );


        if ($result = $db->query($sql)) {
            foreach ($result as $row) {

                if ($row['Store Code'] == $q) {
                    $candidates[$row['Store Key']] = 1000;
                } else {

                    $len_name                      = strlen(
                        $row['Store Code']
                    );
                    $len_q                         = strlen($q);
                    $factor                        = $len_q / $len_name;
                    $candidates[$row['Store Key']] = 500 * $factor;
                }

            }
        } else {
            print_r($error_info = $db->errorInfo());
            exit;
        }


        $sql  = "select `Store Key`,`Store Code`,`Store Name` from `Store Dimension` where true $where_store and `Store Name`  REGEXP ? limit 100 ";
        $stmt = $db->prepare($sql);
        $stmt->execute(
            array(
                '\\b'.$q
            )
        );
        while ($row = $stmt->fetch()) {
            if ($row['Store Name'] == $q) {
                $candidates[$row['Store Key']] = 55;
            } else {

                $len_name                      = strlen(
                    $row['Store Name']
                );
                $len_q                         = strlen($q);
                $factor                        = $len_q / $len_name;
                $candidates[$row['Store Key']] = 50 * $factor;
            }
        }


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

    $counter      = 0;
    $product_keys = '';
    $results      = array();

    foreach ($candidates as $key => $val) {
        $counter++;
        $product_keys  .= ','.$key;
        $results[$key] = '';
        if ($counter > $max_results) {
            break;
        }
    }
    $product_keys = preg_replace('/^,/', '', $product_keys);

    $sql = sprintf(
        "SELECT `Store Code`,`Store Key`,`Store Name` FROM `Store Dimension` S WHERE `Store Key` IN (%s)", $product_keys
    );

    if ($result = $db->query($sql)) {
        foreach ($result as $row) {


            $results[$row['Store Key']] = array(
                'code'        => highlightkeyword(
                    sprintf('%s', $row['Store Code']), $queries
                ),
                'description' => highlightkeyword(
                    $row['Store Name'], $queries
                ),

                'value'           => $row['Store Key'],
                'formatted_value' => $row['Store Name'].' ('.$row['Store Code'].')'


            );

        }
    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }


    $results_data = array(
        'n' => count($results),
        'd' => $results
    );


    $response = array(
        'state'          => 200,
        'number_results' => $results_data['n'],
        'results'        => $results_data['d'],
        'q'              => $queries
    );

    echo json_encode($response);

}

function find_locations($db, $data, $user) {


    $max_results = 10;
    $q           = trim($data['query']);


    if ($q == '') {
        $response = array(
            'state'   => 200,
            'results' => 0,
            'data'    => ''
        );
        echo json_encode($response);

        return;
    }


    $where_warehouses = sprintf(
        ' and `Location Warehouse Key` in (%s) and `Location Type`!="Unknown"', join(',', $user->warehouses)
    );


    $candidates = array();

    $candidates_data = array();


    $sql = sprintf(
        "select `Location Key`,`Location Code`,`Warehouse Key`,`Warehouse Code` from `Location Dimension` left join `Warehouse Dimension` on (`Warehouse Key`=`Location Warehouse Key`) where true $where_warehouses and `Location Code` like '%s%%' order by `Location File As` limit $max_results ",
        $q
    );


    if ($result = $db->query($sql)) {
        foreach ($result as $row) {

            if ($row['Location Code'] == $q) {
                $candidates[$row['Location Key']] = 1000;
            } else {

                $len_name                         = strlen(
                    $row['Location Code']
                );
                $len_q                            = strlen($q);
                $factor                           = $len_q / $len_name;
                $candidates[$row['Location Key']] = 500 * $factor;
            }

            $candidates_data[$row['Location Key']] = array(
                'Location Code'  => $row['Location Code'],
                'Warehouse Code' => $row['Warehouse Code']
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
    foreach ($candidates as $location_key => $candidate) {


        $results[$location_key] = array(
            'code'        => $candidates_data[$location_key]['Warehouse Code'],
            'description' => highlightkeyword(
                sprintf(
                    '%s', $candidates_data[$location_key]['Location Code']
                ), $q
            ),


            'value'           => $location_key,
            'formatted_value' => $candidates_data[$location_key]['Location Code']


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

function find_warehouse_areas($db, $data) {


    $max_results = 10;
    $q           = trim($data['query']);


    if ($q == '') {
        $response = array(
            'state'   => 200,
            'results' => 0,
            'data'    => ''
        );
        echo json_encode($response);

        return;
    }


    $where_warehouses = sprintf(
        ' and `Warehouse Area Warehouse Key`=%d', $data['parent_key']
    );


    $candidates = array();

    $candidates_data = array();


    $sql = sprintf(
        "select `Warehouse Area Key`,`Warehouse Area Code`,`Warehouse Area Name`,`Warehouse Key`,`Warehouse Code` from `Warehouse Area Dimension` left join `Warehouse Dimension` on (`Warehouse Key`=`Warehouse Area Warehouse Key`) where true $where_warehouses and `Warehouse Area Code` like '%s%%' order by `Warehouse Area Code` limit $max_results ",
        $q
    );


    if ($result = $db->query($sql)) {
        foreach ($result as $row) {

            if ($row['Warehouse Area Code'] == $q) {
                $candidates[$row['Warehouse Area Key']] = 1000;
            } else {

                $len_name                               = strlen(
                    $row['Warehouse Area Code']
                );
                $len_q                                  = strlen($q);
                $factor                                 = $len_q / $len_name;
                $candidates[$row['Warehouse Area Key']] = 500 * $factor;
            }

            $candidates_data[$row['Warehouse Area Key']] = array(
                'Warehouse Area Code' => $row['Warehouse Area Code'],
                'Warehouse Code'      => $row['Warehouse Code'],
                'Warehouse Area Name' => $row['Warehouse Area Name']
            );

        }
    }


    $sql =
        "select `Warehouse Area Key`,`Warehouse Area Code`,`Warehouse Area Name`,`Warehouse Key`,`Warehouse Code` from `Warehouse Area Dimension` left join `Warehouse Dimension` on (`Warehouse Key`=`Warehouse Area Warehouse Key`) where true $where_warehouses and  `Warehouse Area Name` REGEXP ?    order by `Warehouse Area Code` limit $max_results ";

    $stmt = $db->prepare($sql);
    $stmt->execute(
        array(
            '\\b'.$q
        )
    );
    while ($row = $stmt->fetch()) {
        if ($row['Warehouse Area Name'] == $q) {
            $candidates[$row['Warehouse Area Key']] = 700;
        } else {

            $len_name                               = strlen($row['Warehouse Area Name']);
            $len_q                                  = strlen($q);
            $factor                                 = $len_q / $len_name;
            $candidates[$row['Warehouse Area Key']] = 200 * $factor;
        }

        $candidates_data[$row['Warehouse Area Key']] = array(
            'Warehouse Area Code' => $row['Warehouse Area Code'],
            'Warehouse Code'      => $row['Warehouse Code'],
            'Warehouse Area Name' => $row['Warehouse Area Name']
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
    foreach ($candidates as $location_key => $candidate) {


        $results[$location_key] = array(
            'code'        => highlightkeyword($candidates_data[$location_key]['Warehouse Area Code'], $q),
            'description' => highlightkeyword(
                sprintf(
                    '%s', $candidates_data[$location_key]['Warehouse Area Name']
                ), $q
            ),


            'value'           => $location_key,
            'formatted_value' => $candidates_data[$location_key]['Warehouse Area Code']


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

function find_customers($db, $data) {


    $max_results = 10;
    $q           = trim($data['query']);


    if ($q == '') {
        $response = array(
            'state'   => 200,
            'results' => 0,
            'data'    => ''
        );
        echo json_encode($response);

        return;
    }


    $where_stores = sprintf(
        ' and `Customer Store Key`=%d', $data['parent_key']
    );


    $candidates = array();

    $candidates_data = array();


    if (is_numeric($q)) {
        $sql = sprintf(
            "select `Customer Key`,`Customer Name`,`Store Key`,`Store Code` from `Customer Dimension` left join `Store Dimension` on (`Store Key`=`Customer Store Key`) where true $where_stores and `Customer ID`=%d  ", $q
        );


        if ($result = $db->query($sql)) {
            foreach ($result as $row) {


                $candidates[$row['Customer Key']]      = 1001;
                $candidates_data[$row['Customer Key']] = array(
                    'Customer Name' => $row['Customer Name'],
                    'Store Code'    => $row['Store Code']
                );

            }
        } else {
            print_r($error_info = $db->errorInfo());
            exit;
        }


    }

    $sql  =
        "select `Customer Key`,`Customer Name`,`Store Key`,`Store Code` from `Customer Dimension` left join `Store Dimension` on (`Store Key`=`Customer Store Key`) where true $where_stores and `Customer Name` REGEXP ?  order by `Customer File As` limit $max_results ";
    $stmt = $db->prepare($sql);
    $stmt->execute(
        array(
            '\\b'.$q
        )
    );
    while ($row = $stmt->fetch()) {
        if ($row['Customer Name'] == $q) {
            $candidates[$row['Customer Key']] = 1000;
        } else {

            $len_name                         = strlen($row['Customer Name']);
            $len_q                            = strlen($q);
            $factor                           = $len_q / $len_name;
            $candidates[$row['Customer Key']] = 500 * $factor;
        }

        $candidates_data[$row['Customer Key']] = array(
            'Customer Name' => $row['Customer Name'],
            'Store Code'    => $row['Store Code']
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
    foreach ($candidates as $customer_key => $candidate) {


        $results[$customer_key] = array(
            'code'        => sprintf('%05d', $customer_key),
            'description' => highlightkeyword(
                sprintf(
                    '%s', $candidates_data[$customer_key]['Customer Name']
                ), $q
            ),


            'value'           => $customer_key,
            'formatted_value' => $candidates_data[$customer_key]['Customer Name']


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

function find_customer_lists($db, $data) {


    $max_results = 10;
    $q           = trim($data['query']);


    if ($q == '') {
        $response = array(
            'state'   => 200,
            'results' => 0,
            'data'    => ''
        );
        echo json_encode($response);

        return;
    }

    // print_r($data);

    $where_stores = sprintf(
        ' and  `List Scope`="Customer" and  `List Parent Key`=%d', $data['parent_key']
    );


    $candidates = array();

    $candidates_data = array();


    $sql  = "select `List Key`,`List Name`,`List Type`,`List Number Items` from `List Dimension`  where true $where_stores and `List Name` REGEXP ?   limit $max_results ";
    $stmt = $db->prepare($sql);
    $stmt->execute(
        array(
            '\\b'.$q
        )
    );
    while ($row = $stmt->fetch()) {
        if ($row['List Name'] == $q) {
            $candidates[$row['List Key']] = 1000;
        } else {

            $len_name                     = strlen($row['List Name']);
            $len_q                        = strlen($q);
            $factor                       = $len_q / $len_name;
            $candidates[$row['List Key']] = 500 * $factor;
        }

        $candidates_data[$row['List Key']] = array(
            'List Name' => $row['List Name'],
            'List Type' => ($row['List Type'] == 'Static' ? _('Static') : _('Dynamic')),
            'Customers' => $row['List Number Items'],
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
    foreach ($candidates as $list_key => $candidate) {


        $results[$list_key] = array(
            'code'        => $candidates_data[$list_key]['List Type'],
            'description' => sprintf(
                '%s', highlightkeyword($candidates_data[$list_key]['List Name'], $q).' <span class="discreet">('.sprintf(ngettext('%s customer', '%s customers', $candidates_data[$list_key]['Customers']), number($candidates_data[$list_key]['Customers'])).')</span>'

            ),


            'value'           => $list_key,
            'formatted_value' => $candidates_data[$list_key]['List Name']


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

function find_parts($db, $data) {


    $max_results = 10;
    $q           = trim($data['query']);


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


    if (!empty($data['metadata']['with_no_sko_barcodes'])) {
        $scope = 'barcodes';

    } elseif (!empty($data['metadata']['for_bill_of_materials'])) {
        $scope = 'bill_of_materials';

    } else {
        $scope = '';
    }


    $where = " and `Part Status` in ('In Use','Discontinuing','In Process')";
    $sql   = sprintf(
        "select `Part SKU`,`Part Reference`,`Part Package Description`,`Part Units Per Package`,`Part Recommended Product Unit Name`,`Part SKO Barcode` from `Part Dimension`  where  `Part Reference` like '%s%%'  %s   order by `Part Reference` limit $max_results ", $q,
        $where
    );


    if ($result = $db->query($sql)) {
        foreach ($result as $row) {

            if ($row['Part Reference'] == $q) {
                $candidates[$row['Part SKU']] = 1000;
            } else {

                $len_name                     = strlen($row['Part Reference']);
                $len_q                        = strlen($q);
                $factor                       = $len_q / $len_name;
                $candidates[$row['Part SKU']] = 500 * $factor;
            }

            $candidates_data[$row['Part SKU']] = array(
                'Part Reference'                     => $row['Part Reference'],
                'Part Package Description'           => $row['Part Package Description'],
                'Part Recommended Product Unit Name' => $row['Part Recommended Product Unit Name'].($row['Part Units Per Package'] > 1 ? ' <span class="discreet" >(<span style="letter-spacing: -1px;">1/'.$row['Part Units Per Package'].'</span>)<span>' : ''),

                'Part SKO Barcode' => $row['Part SKO Barcode']
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
    foreach ($candidates as $part_sku => $candidate) {


        if ($scope == 'barcodes') {
            $description = '<span class="'.($candidates_data[$part_sku]['Part SKO Barcode'] != '' ? 'strikethrough  discreet ' : '').'" >'.$candidates_data[$part_sku]['Part Package Description'].' '.($candidates_data[$part_sku]['Part SKO Barcode'] == '' ? ''
                    : '  <i class="fa fa-barcode" aria-hidden="true"></i> '.$candidates_data[$part_sku]['Part SKO Barcode']).'  </span>';
            $code        = '<span class="'.($candidates_data[$part_sku]['Part SKO Barcode'] != '' ? 'strikethrough  discreet ' : '').'" >'.$candidates_data[$part_sku]['Part Reference'].'</span>';

        } elseif ($scope == 'bill_of_materials') {
            $description = $candidates_data[$part_sku]['Part Recommended Product Unit Name'];
            $code        = $candidates_data[$part_sku]['Part Reference'];
        } else {
            $description = $candidates_data[$part_sku]['Part Package Description'];
            $code        = $candidates_data[$part_sku]['Part Reference'];
        }


        $results[$part_sku] = array(
            'code'            => $code,
            'description'     => $description,
            'value'           => $part_sku,
            'formatted_value' => $candidates_data[$part_sku]['Part Reference'],
            'barcode'         => $candidates_data[$part_sku]['Part SKO Barcode']
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

function find_raw_materials($db, $data) {


    $max_results = 10;
    $q           = trim($data['query']);


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


    $where = " ";
    $sql   = sprintf(
        "select `Raw Material Unit Label`,`Raw Material Key`,`Raw Material Code`,`Raw Material Description`,`Raw Material Unit` from `Raw Material Dimension`  where  `Raw Material Code` like '%s%%'  %s   order by `Raw Material Code` limit $max_results ", $q, $where
    );


    if ($result = $db->query($sql)) {
        foreach ($result as $row) {

            if ($row['Raw Material Code'] == $q) {
                $candidates[$row['Raw Material Key']] = 1000;
            } else {

                $len_name                             = strlen($row['Raw Material Code']);
                $len_q                                = strlen($q);
                $factor                               = $len_q / $len_name;
                $candidates[$row['Raw Material Key']] = 500 * $factor;
            }

            $candidates_data[$row['Raw Material Key']] = array(
                'Code'       => $row['Raw Material Code'],
                'Name'       => $row['Raw Material Description'],
                'Unit'       => $row['Raw Material Unit'],
                'Unit_Label' => $row['Raw Material Unit Label']

            );

        }
    }


    $where = " ";
    $sql   = sprintf(
        "select `Raw Material Unit Label`,`Raw Material Key`,`Raw Material Code`,`Raw Material Description`,`Raw Material Unit` from `Raw Material Dimension`  where  `Raw Material Description` REGEXP '\\\\b%s'  %s   limit $max_results ", $q, $where
    );


    if ($result = $db->query($sql)) {
        foreach ($result as $row) {


            $candidates[$row['Raw Material Key']] = 250;


            $candidates_data[$row['Raw Material Key']] = array(
                'Code' => $row['Raw Material Code'],
                'Name' => $row['Raw Material Description'],

                'Unit'       => $row['Raw Material Unit'],
                'Unit_Label' => $row['Raw Material Unit Label']

            );

        }
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
    foreach ($candidates as $raw_material_key => $candidate) {


        $description = $candidates_data[$raw_material_key]['Name'];
        $code        = $candidates_data[$raw_material_key]['Code'];


        $results[$raw_material_key] = array(
            'code'            => $code,
            'description'     => $description,
            'value'           => $raw_material_key,
            'formatted_value' => $code,
            'metadata'        => [
                'unit'       => $candidates_data[$raw_material_key]['Unit'],
                'unit_label' => $candidates_data[$raw_material_key]['Unit_Label'],
                'description'      => $candidates_data[$raw_material_key]['Name'],

            ]


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

function find_products($db, $data) {


    $max_results = 5;
    $q           = trim($data['query']);


    if ($q == '') {
        $response = array(
            'state'   => 200,
            'results' => 0,
            'data'    => ''
        );
        echo json_encode($response);

        return;
    }


    $where = '';


    if (isset($data['metadata']['parent'])) {
        switch ($data['metadata']['parent']) {
            case 'Store':
            case 'store':

                $where = sprintf(
                    ' and `Product Store Key`=%d', $data['metadata']['parent_key']
                );
                break;
            default:

                break;
        }
    } else {

        switch ($data['parent']) {
            case 'store':
                $where = sprintf(
                    ' and `Product Store Key`=%d', $data['parent_key']
                );
                break;
            default:

                break;
        }

    }

    if (!isset($data['metadata']['options']['for_order'])) {
        $where .= "  and  `Product Status` not in ( 'Suspended','Discontinued')  ";
    }


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

function find_assets_on_sale($db, $data) {


    $max_results = 5;
    $q           = trim($data['query']);


    if ($q == '') {
        $response = array(
            'state'   => 200,
            'results' => 0,
            'data'    => ''
        );
        echo json_encode($response);

        return;
    }


    $where            = '';
    $where_product    = '';
    $where_categories = '';

    if (isset($data['metadata']['parent'])) {
        switch ($data['metadata']['parent']) {
            case 'Store':
            case 'store':

                $where_product    = sprintf(
                    ' and `Product Store Key`=%d', $data['metadata']['parent_key']
                );
                $where_categories = sprintf(
                    ' and `Category Store Key`=%d', $data['metadata']['parent_key']
                );
                break;
            default:

                break;
        }
    } else {

        switch ($data['parent']) {
            case 'store':
                $where_product    = sprintf(
                    ' and `Product Store Key`=%d', $data['parent_key']
                );
                $where_categories = sprintf(
                    ' and `Category Store Key`=%d', $data['parent_key']
                );
                break;
            default:

                break;
        }

    }

    if (!isset($data['metadata']['options']['for_order'])) {
        $where_product .= "  and  `Product Status` not in ( 'Suspended','Discontinued')  ";
    }


    $candidates = array();

    $candidates_data = array();


    $sql = sprintf(
        "select `Product ID`,`Product Code`,`Product Name`,`Product Current Key`,`Product Availability`,`Product Availability State` from `Product Dimension` where  `Product Code` like '%s%%' %s %s order by `Product Code` limit $max_results ", $q, $where, $where_product
    );


    if ($result = $db->query($sql)) {
        foreach ($result as $row) {

            if ($row['Product Code'] == $q) {
                $candidates['P'.$row['Product ID']] = 1000;
            } else {

                $len_name                           = strlen(
                    $row['Product ID']
                );
                $len_q                              = strlen($q);
                $factor                             = $len_q / $len_name;
                $candidates['P'.$row['Product ID']] = 500 * $factor;
            }


            if ($row['Product Availability State'] == 'OnDemand') {
                $stock = ' ('._('on demand').')';
            } else {
                $stock = _('Stock').': '.number($row['Product Availability']);
            }


            $candidates_data['P'.$row['Product ID']] = array(
                'Code'        => $row['Product Code'],
                'Name'        => $row['Product Name'].', <span style="font-style: italic"  class="'.($row['Product Availability'] <= 0 ? 'error' : '').'" >'.$stock.'</span>',
                'Current Key' => $row['Product Current Key']

            );

        }
    } else {
        print_r($error_info = $db->errorInfo());
        print $sql;
        exit;
    }


    $sql = sprintf(
        "select `Category Key`,`Category Code`,`Category Label`,`Category Number Active Subjects`,`Category Subject` from `Category Dimension` where  `Category Code` like '%s%%' %s  %s limit $max_results ", $q, $where, $where_categories
    );


    if ($result = $db->query($sql)) {
        foreach ($result as $row) {

            if ($row['Category Code'] == $q) {
                $candidates['C'.$row['Category Key']] = 1000;
            } else {

                $len_name                             = strlen($row['Category Code']);
                $len_q                                = strlen($q);
                $factor                               = $len_q / $len_name;
                $candidates['C'.$row['Category Key']] = 500 * $factor;
            }

            $candidates_data['C'.$row['Category Key']] = array(
                'Code'        => $row['Category Code'],
                'Name'        => $row['Category Label'].', <span style="font-style: italic"  >('.($row['Category Subject'] == 'Category' ? _('Department') : _('Family')).') <i class="fa fa-fw fa-cube"></i>: '.number($row['Category Number Active Subjects']).'</span>',
                'Current Key' => $row['Category Key']

            );

        }
    } else {
        print_r($error_info = $db->errorInfo());
        print $sql;
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


    $product_ids            = '';
    $number_product_ids     = 0;
    $category_keys          = '';
    $number_categories_keys = 0;
    $counter                = 0;
    foreach ($candidates as $_key => $val) {
        $counter++;

        if ($_key[0] == 'P') {
            $key            = preg_replace('/^P/', '', $_key);
            $product_ids    .= ','.$key;
            $results[$_key] = '';
            $number_product_ids++;

        } elseif ($_key[0] == 'C') {
            $key            = preg_replace('/^C/', '', $_key);
            $category_keys  .= ','.$key;
            $results[$_key] = '';
            $number_categories_keys++;

        }

        if ($counter > $max_results) {
            break;
        }
    }
    $product_ids   = preg_replace('/^,/', '', $product_ids);
    $category_keys = preg_replace('/^,/', '', $category_keys);


    if ($number_product_ids) {
        $sql = sprintf(
            "SELECT P.`Product ID`,`Product Code`,`Product Name`,`Product Availability`,`Product Availability State` FROM `Product Dimension` P  WHERE P.`Product ID` IN (%s)", $product_ids
        );

        if ($result = $db->query($sql)) {
            foreach ($result as $row) {


                if ($row['Product Availability State'] == 'OnDemand') {
                    $stock = _('On demand');
                } else {
                    $stock = _('Stock').': '.number($row['Product Availability']);
                }


                $results['P'.$row['Product ID']] = array(
                    'label'   => highlightkeyword(sprintf('%s', $row['Product Code']), $q),
                    'details' => highlightkeyword($row['Product Name'], $q).', <span style="font-style: italic"  class="'.($row['Product Availability'] <= 0 ? 'error' : '').'" >'.$stock.'</span>',


                );

            }
        } else {
            print_r($error_info = $db->errorInfo());
            print $sql;
            exit;
        }

    }

    if ($number_categories_keys) {
        $sql = sprintf(
            "SELECT `Category Code`,`Category Store Key`,`Category Key`,`Category Code`,`Category Label` FROM `Category Dimension` WHERE `Category Key` IN (%s)", $category_keys
        );

        if ($result = $db->query($sql)) {
            foreach ($result as $row) {


                $icon = '<i class="fa fa-sitemap fa-fw padding_right_5" aria-hidden="true" ></i> ';

                $results['C'.$row['Category Key']] = array(
                    'label'   => $icon.highlightkeyword(
                            sprintf('%s', $row['Category Code']), $q
                        ),
                    'details' => highlightkeyword(
                        $row['Category Label'], $q
                    ),


                );
            }
        } else {
            print_r($error_info = $db->errorInfo());
            print $sql;
            exit;
        }
    }


    $results = array();
    foreach ($candidates as $product_sku => $candidate) {


        $results[$product_sku] = array(
            'code'              => $candidates_data[$product_sku]['Code'],
            'description'       => $candidates_data[$product_sku]['Name'],
            'item_historic_key' => $candidates_data[$product_sku]['Current Key'],

            'value'           => $product_sku,
            'formatted_value' => $candidates_data[$product_sku]['Code']
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


function find_supplier_parts($db, $data) {


    $max_results = 5;

    $q = trim($data['query']);


    if ($q == '') {
        $response = array(
            'state'   => 200,
            'results' => 0,
            'data'    => ''
        );
        echo json_encode($response);

        return;
    }

    $where = '';
    $table = "FROM   `Supplier Part Dimension` SP LEFT JOIN  `Part Dimension` ON (`Supplier Part Part SKU`=`Part SKU`) ";

    if ($data['metadata']['parent'] == 'Supplier') {

        $where = sprintf(
            ' `Supplier Part Supplier Key`=%d and ', $data['metadata']['parent_key']
        );

    } elseif (strtolower($data['metadata']['parent']) == 'agent') {

        $table = "FROM   `Supplier Part Dimension` SP  left join `Agent Supplier Bridge` on (SP.`Supplier Part Supplier Key`=`Agent Supplier Supplier Key`) LEFT JOIN  `Part Dimension` ON (`Supplier Part Part SKU`=`Part SKU`) ";


        $where = sprintf(
            ' `Agent Supplier Agent Key`=%d and ', $data['metadata']['parent_key']
        );

    }

    if (!isset($data['metadata']['options']['all_parts'])) {
        $where .= " `Part Status` not in ('Not In Use','Discontinuing') and ";
    }
    if (!isset($data['metadata']['options']['all_supplier_parts'])) {
        $where .= " `Supplier Part Status`='Available' and ";
    }


    $candidates = array();

    $candidates_data = array();


    $sql = sprintf(
        "SELECT `Supplier Part Reference`,`Supplier Part Historic Key`,`Supplier Part Key`,`Part Reference`,`Supplier Part Description` %s WHERE %s `Supplier Part Reference` LIKE '%s%%'  ORDER BY `Supplier Part Reference` LIMIT %d ", $table, $where, $q, $max_results
    );


    if ($result = $db->query($sql)) {
        foreach ($result as $row) {

            if ($row['Supplier Part Reference'] == $q) {
                $candidates[$row['Supplier Part Key']] = 1000;
            } else {

                $len_name                              = strlen(
                    $row['Supplier Part Reference']
                );
                $len_q                                 = strlen($q);
                $factor                                = $len_q / $len_name;
                $candidates[$row['Supplier Part Key']] = 500 * $factor;
            }

            $candidates_data[$row['Supplier Part Key']] = array(
                'Supplier Part Historic Key' => $row['Supplier Part Historic Key'],
                'Supplier Part Reference'    => $row['Supplier Part Reference'],
                'Part Reference'             => $row['Part Reference'],
                'Supplier Part Description'  => $row['Supplier Part Description']
            );

        }
    } else {
        print "$sql\n";
        print_r($error_info = $db->errorInfo());
        exit;
    }


    $sql = sprintf(
        "SELECT `Supplier Part Key`,`Supplier Part Historic Key`,`Supplier Part Reference`,`Part Reference`,`Supplier Part Description`   %s WHERE %s `Part Reference` LIKE '%s%%'  ORDER BY `Part Reference` LIMIT %d ", $table, $where, $q, $max_results
    );

    if ($result = $db->query($sql)) {
        foreach ($result as $row) {

            if ($row['Part Reference'] == $q) {
                $candidates[$row['Supplier Part Key']] = 1000;
            } else {

                $len_name                              = strlen(
                    $row['Part Reference']
                );
                $len_q                                 = strlen($q);
                $factor                                = $len_q / $len_name;
                $candidates[$row['Supplier Part Key']] = 500 * $factor;
            }

            $candidates_data[$row['Supplier Part Key']] = array(
                'Supplier Part Historic Key' => $row['Supplier Part Historic Key'],
                'Supplier Part Reference'    => $row['Supplier Part Reference'],
                'Part Reference'             => $row['Part Reference'],
                'Supplier Part Description'  => $row['Supplier Part Description']
            );

        }
    } else {
        print "** $sql\n";
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
    foreach ($candidates as $supplier_part_key => $candidate) {


        $description = $candidates_data[$supplier_part_key]['Supplier Part Description'];
        if ($candidates_data[$supplier_part_key]['Part Reference'] != $candidates_data[$supplier_part_key]['Supplier Part Reference']) {
            $description .= ' ('.highlightkeyword(
                    $candidates_data[$supplier_part_key]['Part Reference'], $q
                ).')';
        }


        $results[$supplier_part_key] = array(
            'code'              => highlightkeyword(
                $candidates_data[$supplier_part_key]['Supplier Part Reference'], $q
            ),
            'description'       => $description,
            'value'             => $supplier_part_key,
            'item_historic_key' => $candidates_data[$supplier_part_key]['Supplier Part Historic Key'],
            'formatted_value'   => $candidates_data[$supplier_part_key]['Supplier Part Reference']
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


function find_special_category($type, $db, $account, $data) {


    $max_results = 10;
    $user        = $data['user'];
    $queries     = trim($data['query']);


    if ($queries == '') {
        $response = array(
            'state'   => 200,
            'results' => 0,
            'data'    => ''
        );
        echo json_encode($response);

        return;
    }

    $root_keys = '';

    if ($type == 'product_categories') {


        $where_root_categories = sprintf(' and `Category Branch Type`="Head"  and `Category Scope`="Product"  and `Category Store Key`=%d ', $data['parent_key']);


    } elseif ($type == 'PartFamily') {
        $root_keys             = $account->get('Account Part Family Category Key');
        $where_root_categories = sprintf(' and `Category Root Key`=%d', $root_keys);
    } else {

        if ($data['parent'] == 'store') {
            $store_keys = $data['parent_key'];
        } else {
            $store_keys = join(',', $user->stores);
        }

        $sql = sprintf(
            "SELECT GROUP_CONCAT(`Store %s Category Key`) AS root_keys FROM  `Store Dimension` WHERE `Store Key` IN (%s)  ", addslashes($type), $store_keys
        );
        if ($result = $db->query($sql)) {
            if ($row = $result->fetch()) {
                $root_keys = $row['root_keys'];
            }
        } else {
            print_r($error_info = $db->errorInfo());
            print $sql;
            exit;
        }


        if ($root_keys != '') {
            $where_root_categories = sprintf(
                ' and `Category Root Key` in (%s)', $root_keys
            );
        } else {
            $response = array(
                'state'   => 200,
                'results' => 0,
                'data'    => ''
            );
            echo json_encode($response);

            return;
        }
    }


    $candidates = array();

    $query_array = preg_split('/\s+/', $queries);


    foreach ($query_array as $q) {


        $sql = sprintf(
            "SELECT `Category Key`,`Category Code`,`Category Label` FROM `Category Dimension` WHERE TRUE %s AND `Category Code` LIKE '%s%%' LIMIT 20 ", $where_root_categories, $q
        );


        if ($result = $db->query($sql)) {
            foreach ($result as $row) {

                if ($row['Category Code'] == $q) {
                    $candidates[$row['Category Key']] = 1000;
                } else {

                    $len_name                         = strlen($row['Category Code']);
                    $len_q                            = strlen($q);
                    $factor                           = $len_q / $len_name;
                    $candidates[$row['Category Key']] = 500 * $factor;
                }

            }
        } else {
            print_r($error_info = $db->errorInfo());
            exit;
        }


        $sql = "select `Category Key`,`Category Code`,`Category Label` from `Category Dimension` where true $where_root_categories and `Category Label`  REGEXP  ? limit 100 ";

        $stmt = $db->prepare($sql);
        $stmt->execute(
            array(
                '\\b'.$q
            )
        );
        while ($row = $stmt->fetch()) {
            if ($row['Category Label'] == $q) {
                $candidates[$row['Category Key']] = 55;
            } else {

                $len_name                         = strlen(
                    $row['Category Label']
                );
                $len_q                            = strlen($q);
                $factor                           = $len_q / $len_name;
                $candidates[$row['Category Key']] = 50 * $factor;
            }
        }


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

    $counter      = 0;
    $product_keys = '';
    $results      = array();

    foreach ($candidates as $key => $val) {
        $counter++;
        $product_keys  .= ','.$key;
        $results[$key] = '';
        if ($counter > $max_results) {
            break;
        }
    }
    $product_keys = preg_replace('/^,/', '', $product_keys);


    if ($type == 'product_categories') {

        include_once('class.Store.php');
        $store = new Store($data['parent_key']);
    }


    $sql = sprintf(
        "SELECT `Category Code`,`Category Key`,`Category Label`,`Category Root Key` FROM `Category Dimension` C WHERE `Category Key` IN (%s)", $product_keys
    );

    if ($result = $db->query($sql)) {
        foreach ($result as $row) {

            $code = $row['Category Code'];
            if ($type == 'product_categories') {
                $code = $row['Category Code'];

                if ($row['Category Root Key'] == $store->get('Store Family Category Key')) {
                    $code .= ' (F)';
                } else {
                    if ($row['Category Root Key'] == $store->get('Store Department Category Key')) {
                        $code .= ' (D)';
                    }
                }


            }


            $results[$row['Category Key']] = array(
                'value'           => $row['Category Key'],
                'formatted_value' => $row['Category Code'],
                'code'            => $code,
                'description'     => $row['Category Label'],
                'metadata'        => array()

            );

        }
    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }


    $results_data = array(
        'n' => count($results),
        'd' => $results
    );

    $response = array(
        'state'          => 200,
        'number_results' => $results_data['n'],
        'results'        => $results_data['d'],
        'q'              => $queries
    );

    echo json_encode($response);

}


function find_countries($db, $data) {


    $max_results = 10;
    $queries     = trim($data['query']);

    if ($queries == '') {
        $response = array(
            'state'   => 200,
            'results' => 0,
            'data'    => ''
        );
        echo json_encode($response);

        return;
    }


    $candidates = array();

    $query_array = preg_split('/\s+/', $queries);


    foreach ($query_array as $q) {


        if (strlen($q) <= 3) {


            $sql = sprintf(
                "SELECT `Country Key`,`Country Code`,`Country Name` FROM kbase.`Country Dimension` WHERE `Country Code` LIKE '%s%%' LIMIT 20 ", $q
            );


            if ($result = $db->query($sql)) {
                foreach ($result as $row) {

                    if ($row['Country Code'] == $q) {
                        $candidates[$row['Country Key']] = 1000;
                    } else {

                        $len_name                        = strlen(
                            $row['Country Code']
                        );
                        $len_q                           = strlen($q);
                        $factor                          = $len_q / $len_name;
                        $candidates[$row['Country Key']] = 500 * $factor;
                    }

                }
            } else {
                print_r($error_info = $db->errorInfo());
                exit;
            }


        }

        if (strlen($q) == 2) {


            $sql = sprintf(
                "SELECT `Country Key`,`Country Code`,`Country Name` FROM kbase.`Country Dimension` WHERE  `Country 2 Alpha Code` LIKE '%s%%' LIMIT 20 ", $q
            );


            if ($result = $db->query($sql)) {
                foreach ($result as $row) {

                    if ($row['Country Code'] == $q) {
                        $candidates[$row['Country Key']] = 1000;
                    } else {

                        $len_name                        = strlen(
                            $row['Country Code']
                        );
                        $len_q                           = strlen($q);
                        $factor                          = $len_q / $len_name;
                        $candidates[$row['Country Key']] = 500 * $factor;
                    }

                }
            } else {
                print_r($error_info = $db->errorInfo());
                exit;
            }


        }


        $sql = "SELECT `Country Key`,`Country Code`,`Country Name` FROM kbase.`Country Dimension` WHERE  `Country Name`  REGEXP ? LIMIT 100 ";

        $stmt = $db->prepare($sql);
        $stmt->execute(
            array(
                '\\b'.$q
            )
        );
        while ($row = $stmt->fetch()) {
            if ($row['Country Name'] == $q) {
                $candidates[$row['Country Key']] = 55;
            } else {

                $len_name                        = strlen($row['Country Name']);
                $len_q                           = strlen($q);
                $factor                          = $len_q / $len_name;
                $candidates[$row['Country Key']] = 50 * $factor;
            }
        }


        $sql = "SELECT `Country Key`,`Country Code`,`Country Local Name` FROM kbase.`Country Dimension` WHERE  `Country Local Name`  REGEXP ? LIMIT 100 ";

        $stmt = $db->prepare($sql);
        $stmt->execute(
            array(
                '\\b'.$q
            )
        );
        while ($row = $stmt->fetch()) {
            if ($row['Country Local Name'] == $q) {
                $candidates[$row['Country Key']] = 55;
            } else {

                $len_name                        = strlen($row['Country Local Name']);
                $len_q                           = strlen($q);
                $factor                          = $len_q / $len_name;
                $candidates[$row['Country Key']] = 50 * $factor;
            }
        }


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

    $counter      = 0;
    $product_keys = '';
    $results      = array();

    foreach ($candidates as $key => $val) {
        $counter++;
        $product_keys  .= ','.$key;
        $results[$key] = '';
        if ($counter > $max_results) {
            break;
        }
    }
    $product_keys = preg_replace('/^,/', '', $product_keys);

    $sql = sprintf(
        "SELECT `Country Code`,`Country Key`,`Country Name` FROM kbase.`Country Dimension` C WHERE `Country Key` IN (%s)", $product_keys
    );

    if ($result = $db->query($sql)) {
        foreach ($result as $row) {


            $results[$row['Country Key']] = array(
                'code'        => highlightkeyword(
                    sprintf('%s', $row['Country Code']), $queries
                ),
                'description' => highlightkeyword(
                    $row['Country Name'], $queries
                ),

                'value'           => $row['Country Code'],
                'formatted_value' => $row['Country Name'].' ('.$row['Country Code'].')'


            );

        }
    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }


    $results_data = array(
        'n' => count($results),
        'd' => $results
    );

    $response = array(
        'state'          => 200,
        'number_results' => $results_data['n'],
        'results'        => $results_data['d'],
        'q'              => $queries
    );

    echo json_encode($response);

}


function orders_in_process($db, $data) {

    $number_orders_in_process = 0;
    $orders_list              = '';
    $msg                      = '';
    $sql                      = sprintf(
        "SELECT `Order Key`,`Order Public ID`,`Order Store Key`  FROM `Order Dimension` WHERE `Order Customer Key`=%d AND `Order State`='InBasket'", $data['customer_key']
    );


    if ($result = $db->query($sql)) {
        foreach ($result as $row) {


            $orders_list .= sprintf(
                ", <span class='link'  onClick=\"change_view('orders/%d/%d')\" >%s</span>", $row['Order Store Key'], $row['Order Key'], $row['Order Public ID']
            );
            $number_orders_in_process++;
            $order_public_id = $row['Order Public ID'];

            if ($number_orders_in_process == 10) {
                break;
            }


        }
    }

    $orders_list = preg_replace('/^,\s*/', '', $orders_list);

    if ($number_orders_in_process == 0) {
        $response = array(
            'state'             => 200,
            'orders_in_process' => $number_orders_in_process,
            'clone_msg'         => _('New order will be created with this items'),
            'msg'               => ''
        );
        echo json_encode($response);
        exit;
    }

    if ($number_orders_in_process == 1) {
        $orders_list = _('Current order in process').": ".$orders_list;
        $msg         = _('This customer has already one order in process. Are you sure you want to create a new one?');
        $clone_msg   = sprintf(_('Order %s will be top up with this items'), '<b>'.$order_public_id.'</b>');
    } elseif ($number_orders_in_process > 1) {
        $orders_list = _('Current orders in process').": ".$orders_list;
        $msg         = _('This customer has already several orders in process. Are you sure you want to create a new one?');
        $clone_msg   = _("Can't clone this order because there is several orders in process");
    }
    $response = array(
        'state'             => 200,
        'orders_in_process' => $number_orders_in_process,
        'msg'               => $msg,
        'clone_msg'         => $clone_msg,
        'orders_list'       => $orders_list
    );
    echo json_encode($response);
    exit;

}

function new_agent_delivery_options($db, $data) {


    $warehouse_options = array();
    $warehouse_key     = false;


    $sql = sprintf("SELECT `Warehouse Key`,`Warehouse Code`,`Warehouse Name` FROM `Warehouse Dimension` WHERE `Warehouse State`='Active'");
    if ($result = $db->query($sql)) {
        foreach ($result as $row) {
            if (!$warehouse_key) {
                $warehouse_key = $row['Warehouse Key'];
            }
            $warehouse_options[$row['Warehouse Key']] = array('code' => $row['Warehouse Code']);
        }
    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }


    $response = array(
        'state'             => 200,
        'warehouses'        => count($warehouse_options),
        'warehouse_key'     => $warehouse_key,
        'warehouse_options' => $warehouse_options,

        // 'msg'               => $msg,
    );
    echo json_encode($response);
    exit;

}


function new_purchase_order_options($db, $data) {

    $number_orders_in_process = 0;
    $orders_list              = '';
    $warehouse_options        = array();
    $warehouse_key            = false;
    $sql                      = sprintf(
        "SELECT `Purchase Order Key`,`Purchase Order Public ID`,`Purchase Order Warehouse Key` FROM `Purchase Order Dimension` WHERE `Purchase Order Parent`=%s AND `Purchase Order Parent Key`=%d AND `Purchase Order State`='In Process'", prepare_mysql($data['parent']),
        $data['parent_key']
    );
    if ($result = $db->query($sql)) {
        foreach ($result as $row) {
            $orders_list .= sprintf(
                ", <span class='link'  onClick=\"change_view('orders/%d/%d')\" >%s</span>", $row['Order store Key'], $row['Order Key'], $row['Order Public ID']
            );
            $number_orders_in_process++;
            if ($number_orders_in_process == 10) {
                break;
            }
        }
    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }


    $sql = sprintf(
        "SELECT `Warehouse Key`,`Warehouse Code`,`Warehouse Name` FROM `Warehouse Dimension` WHERE `Warehouse State`='Active'"
    );
    if ($result = $db->query($sql)) {
        foreach ($result as $row) {
            if (!$warehouse_key) {
                $warehouse_key = $row['Warehouse Key'];
            }
            $warehouse_options[$row['Warehouse Key']] = array('code' => $row['Warehouse Code']);
        }
    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }


    $orders_list = preg_replace('/^,\s*/', '', $orders_list);

    if ($number_orders_in_process == 0) {
        $msg         = '';
        $orders_list = '';
    } elseif ($number_orders_in_process == 1) {
        $orders_list = _('Current order in process').": ".$orders_list;
        $msg         = _(
            'This customer has already one order in process. Are you sure you want to create a new one?'
        );
    } elseif ($number_orders_in_process > 1) {
        $orders_list = _('Current orders in process').": ".$orders_list;
        $msg         = _(
            'This customer has already several orders in process. Are you sure you want to create a new one?'
        );

    }
    $response = array(
        'state'             => 200,
        'warehouses'        => count($warehouse_options),
        'warehouse_key'     => $warehouse_key,
        'warehouse_options' => $warehouse_options,
        'orders_in_process' => $number_orders_in_process,
        'msg'               => $msg,
        'orders_list'       => $orders_list
    );
    echo json_encode($response);
    exit;

}


function find_webpages($db, $data) {


    $max_results = 5;
    $q           = trim($data['query']);


    if ($q == '') {
        $response = array(
            'state'   => 200,
            'results' => 0,
            'data'    => ''
        );
        echo json_encode($response);

        return;
    }


    $where = '';
    switch ($data['parent']) {
        case 'website':
            $where = sprintf(' and `Webpage Website Key`=%d', $data['parent_key']);
            break;
        default:

            break;
    }


    if (isset($data['metadata']['option'])) {
        switch ($data['metadata']['option']) {
            case 'only_online':
                $where .= sprintf(' and `Webpage State`="Online"');
                break;
            case 'only_online_and_in_process':
                $where .= sprintf(' and `Webpage State` in  ("Online" ,"InProcess") ');
                break;
            default:

                break;
        }

    }

    if (isset($data['metadata']['exclude']) and count(
            $data['metadata']['exclude']
        ) > 0) {
        $where .= sprintf(
            ' and `Page Key` not in (%s) ', join(',', $data['metadata']['exclude'])
        );

    }


    $candidates      = array();
    $candidates_data = array();


    $sql = sprintf(
        "select `Page Key`,`Webpage Code`,`Webpage Name` from `Page Store Dimension` where  `Webpage Code` like '%s%%' %s order by `Webpage Code` limit $max_results ", $q, $where
    );


    //   print $sql;


    if ($result = $db->query($sql)) {
        foreach ($result as $row) {

            if ($row['Webpage Code'] == $q) {
                $candidates[$row['Page Key']] = 1000;
            } else {

                $len_name                     = strlen($row['Page Key']);
                $len_q                        = strlen($q);
                $factor                       = $len_q / $len_name;
                $candidates[$row['Page Key']] = 500 * $factor;
            }

            $candidates_data[$row['Page Key']] = array(
                'Webpage Code' => $row['Webpage Code'],
                'Webpage Name' => $row['Webpage Name']
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
            'code'            => $candidates_data[$product_sku]['Webpage Code'],
            'description'     => $candidates_data[$product_sku]['Webpage Name'],
            'value'           => $product_sku,
            'formatted_value' => $candidates_data[$product_sku]['Webpage Code']
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


function find_product_webpages($db, $data) {


    $max_results = 5;
    $q           = trim($data['query']);


    if ($q == '') {
        $response = array(
            'state'   => 200,
            'results' => 0,
            'data'    => ''
        );
        echo json_encode($response);

        return;
    }


    $where = sprintf("  and `Product Status` in ('Active','Discontinuing') ");
    switch ($data['parent']) {
        case 'website':
            $where = sprintf(' and `Webpage Website Key`=%d', $data['parent_key']);
            break;
        default:

            break;
    }

    if (isset($data['action'])) {
        $action = $data['action'];
    } else {
        $action = '';
    }


    if (isset($data['metadata']['option'])) {
        switch ($data['metadata']['option']) {
            case 'only_online':
                $where .= sprintf(' and `Webpage State`="Online"');
                break;

            case 'only_online_and_in_process':
                $where .= sprintf(' and `Webpage State` in  ("Online" ,"InProcess") ');
                break;
            default:

                break;
        }

    }

    if (isset($data['metadata']['exclude']) and count(
            $data['metadata']['exclude']
        ) > 0) {
        $where .= sprintf(
            ' and `Product ID` not in (%s) ', join(',', $data['metadata']['exclude'])
        );

    }

    if (isset($data['metadata']['parent_category_key'])) {
        $parent_category_key = $data['metadata']['parent_category_key'];
        $sql                 = sprintf("SELECT `Subject Key`   FROM `Category Bridge` WHERE `Category Key`=%d ", $parent_category_key);
        //  print $sql;

        $already_in_parent = array();
        if ($result = $db->query($sql)) {
            foreach ($result as $row) {
                $already_in_parent[] = $row['Subject Key'];
            }
        } else {
            print_r($error_info = $db->errorInfo());
            exit;
        }

    } else {
        $already_in_parent = array();
    }


    $candidates      = array();
    $candidates_data = array();


    $sql = sprintf(
        "select `Product ID`,`Product Code`,`Product Name`,`Webpage Name`,`Product Current Key` ,`Product Web State`,`Product Public` from `Page Store Dimension`  left join `Product Dimension` on (`Webpage Scope Key`=`Product ID` and `Webpage Scope`='Product')  where  `Product Code` like '%s%%' %s order by `Product Code` limit $max_results ",
        $q, $where
    );

    // print $sql;
    if ($result = $db->query($sql)) {
        foreach ($result as $row) {

            if ($row['Product Code'] == $q) {
                $candidates[$row['Product ID']] = 1000;
            } else {

                $len_name                       = strlen($row['Product Code']);
                $len_q                          = strlen($q);
                $factor                         = $len_q / $len_name;
                $candidates[$row['Product ID']] = 500 * $factor;
            }

            $candidates_data[$row['Product ID']] = array(
                'Product Code'  => $row['Product Code'],
                'Product Name'  => $row['Product Name'],
                'Webpage State' => $row['Product Web State'],
                'Public'        => $row['Product Public'],
            );

        }
    } else {
        print_r($error_info = $db->errorInfo());

        print $sql;
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
    foreach ($candidates as $product_id => $candidate) {


        $value       = $product_id;
        $description = $candidates_data[$product_id]['Product Name'];
        $code        = $candidates_data[$product_id]['Product Code'];

        if ($action == 'add_product_to_webpage') {


            if ($candidates_data[$product_id]['Public'] == 'No') {
                $description .= ' <i class="fa fa-exclamation-circle padding_left_10 error" aria-hidden="true"></i>  <span class="error">'._('Product is not public').'</span>';
                $code        = '<span class="strikethrough">'.$candidates_data[$product_id]['Product Code'].'</span>';
                $value       = 0;
            } elseif ($candidates_data[$product_id]['Webpage State'] == 'Offline') {
                $description .= ' <i class="fa fa-exclamation-circle padding_left_10 error" aria-hidden="true"></i>  <span class="error">'._('Webpage is offline').'</span>';
                $code        = '<span class="strikethrough">'.$candidates_data[$product_id]['Product Code'].'</span>';
                $value       = 0;
            } elseif ($candidates_data[$product_id]['Webpage State'] == 'Out of Stock') {
                $description .= ' <i class="fa fa-exclamation-circle padding_left_10 error" aria-hidden="true"></i>  <span class="error">'._('Product out of stock').'</span>';
                $code        = '<span >'.$candidates_data[$product_id]['Product Code'].'</span>';

            } elseif (in_array($product_id, $already_in_parent)) {
                $description .= ' <i class="fa fa-exclamation-circle padding_left_10 error" aria-hidden="true"></i>  <span class="error">'._("Product already in this family").'</span>';
                $code        = '<span class="strikethrough">'.$candidates_data[$product_id]['Product Code'].'</span>';
                $value       = 0;
            }

        }


        $product = get_object('Public_Product', $product_id);
        $product->load_webpage();


        $results[$product_id] = array(
            'code'            => $code,
            'description'     => $description,
            'value'           => $value,
            'formatted_value' => $code,
            'metadata'        => json_encode(
                array(
                    'product_id'           => $product->id,
                    'web_state'            => $product->get('Web State'),
                    'price'                => $product->get('Price'),
                    'rrp'                  => $product->get('RRP'),
                    'header_text'          => '',
                    'code'                 => $product->get('Code'),
                    'name'                 => $product->get('Name'),
                    'link'                 => $product->webpage->get('URL'),
                    'webpage_code'         => $product->webpage->get('Webpage Code'),
                    'webpage_key'          => $product->webpage->id,
                    'image_src'            => $product->get('Image'),
                    'image_mobile_website' => '',
                    'image_website'        => '',
                    'out_of_stock_class'   => $product->get('Out of Stock Class'),
                    'out_of_stock_label'   => $product->get('Out of Stock Label'),
                    'sort_code'            => $product->get('Code File As'),
                    'sort_name'            => mb_strtolower($product->get('Product Name')),
                )
            )
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


function find_category_webpages($db, $data, $smarty) {

    include_once('utils/image_functions.php');


    $max_results = 5;
    $q           = trim($data['query']);


    if ($q == '') {
        $response = array(
            'state'   => 200,
            'results' => 0,
            'data'    => ''
        );
        echo json_encode($response);

        return;
    }

    switch ($data['parent']) {
        case 'website':
            $where = sprintf(' and `Webpage Website Key`=%d', $data['parent_key']);
            break;
        case 'store':
            $where = sprintf(' and `Webpage Store Key`=%d', $data['parent_key']);
            break;
        default:

            break;
    }


    if (isset($data['action'])) {
        $action = $data['action'];
    } else {
        $action = '';
    }


    if (isset($data['metadata']['parent_category_key'])) {
        $parent_category_key = $data['metadata']['parent_category_key'];
        $sql                 = sprintf(
            "SELECT `Subject Key`   FROM `Category Bridge` WHERE `Category Key`=%d ", $parent_category_key
        );

        $already_in_parent = array();
        if ($result = $db->query($sql)) {
            foreach ($result as $row) {
                $already_in_parent[] = $row['Subject Key'];
            }
        } else {
            print_r($error_info = $db->errorInfo());
            exit;
        }

    } else {
        $already_in_parent = array();
    }


    $candidates      = array();
    $candidates_data = array();


    $sql = sprintf(
        "select `Webpage URL`,`Webpage Code`,`Product Category Webpage Key`,`Category Main Image Key`,`Category Parent Key`,`Product Category Public`,`Webpage State`,`Page Key`,`Category Code`,`Category Label`,`Category Subject`,`Category Key`,`Product Category Active Products`,`Product Category Discontinuing Products` ,`Product Category In Process Products`, `Product Category Status` 
                      from `Page Store Dimension`  left join `Category Dimension` on (`Webpage Scope Key`=`Category Key` and `Webpage Scope` in ('Category Products','Category Categories')  )   left join `Product Category Dimension` on (`Category Key`=`Product Category Key`)  where  `Category Code` like '%s%%' %s order by `Category Code` limit $max_results ",
        $q, $where
    );

    //	print $sql;
    if ($result = $db->query($sql)) {
        foreach ($result as $row) {

            if ($row['Category Code'] == $q) {
                $candidates[$row['Category Key']] = 1000;
            } else {

                $len_name                         = strlen($row['Category Code']);
                $len_q                            = strlen($q);
                $factor                           = $len_q / $len_name;
                $candidates[$row['Category Key']] = 500 * $factor;
            }


            $candidates_data[$row['Category Key']] = array(
                'Category Code'           => $row['Category Code'],
                'Category Label'          => $row['Category Label'],
                'Status'                  => $row['Product Category Status'],
                'Products'                => $row['Product Category Active Products'] + $row['Product Category Discontinuing Products'] + $row['Product Category In Process Products'],
                'Category Subject'        => $row['Category Subject'],
                'Public'                  => $row['Product Category Public'],
                'Webpage State'           => $row['Webpage State'],
                'Category Parent Key'     => $row['Category Parent Key'],
                'Category Webpage Key'    => $row['Product Category Webpage Key'],
                'Category Main Image Key' => $row['Category Main Image Key'],
                'Webpage URL'             => $row['Webpage URL'],
                'Webpage Code'            => strtolower($row['Webpage Code']),

            );

        }
    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }
    //print $sql;

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
    foreach ($candidates as $category_key => $candidate) {

        //  print $candidates_data[$category_key]['Status'];

        if (in_array(
            $candidates_data[$category_key]['Status'], array(
                                                         'Active',
                                                         'Discontinuing'
                                                     )
        )) {
            $value       = $category_key;
            $description = $candidates_data[$category_key]['Category Label'];
            $code        = $candidates_data[$category_key]['Category Code'];

            if ($candidates_data[$category_key]['Category Subject'] == 'Product') {
                $description .= ' <span class="discreet italic">('.$candidates_data[$category_key]['Products'].' <i class="fa fa-cube" aria-hidden="true"></i>)</span>';

                if ($action == 'add_category_to_webpage') {


                    if ($candidates_data[$category_key]['Public'] == 'No') {
                        $description .= ' <i class="fa fa-exclamation-circle padding_left_10 error" aria-hidden="true"></i>  <span class="error">'._('Category is not public').'</span>';
                        $code        = '<span class="strikethrough">'.$candidates_data[$category_key]['Category Code'].'</span>';
                        $value       = 0;
                    } elseif ($candidates_data[$category_key]['Webpage State'] == 'Offline') {
                        $description .= ' <i class="fa fa-exclamation-circle padding_left_10 error" aria-hidden="true"></i>  <span class="error">'._('Webpage is offline').'</span>';
                        $code        = '<span class="strikethrough">'.$candidates_data[$category_key]['Category Code'].'</span>';
                        $value       = 0;
                    } elseif ($candidates_data[$category_key]['Products'] == 0) {
                        $description .= ' <i class="fa fa-exclamation-circle padding_left_10 error" aria-hidden="true"></i>  <span class="error">'._(
                                "Category don't have any product for sale"
                            ).'</span>';
                        $code        = '<span class="strikethrough">'.$candidates_data[$category_key]['Category Code'].'</span>';
                        $value       = 0;
                    } elseif (in_array($category_key, $already_in_parent)) {
                        $description .= ' <i class="fa fa-exclamation-circle padding_left_10 error" aria-hidden="true"></i>  <span class="error">'._(
                                "Family already in this department"
                            ).'</span>';
                        $code        = '<span class="strikethrough">'.$candidates_data[$category_key]['Category Code'].'</span>';
                        $value       = 0;
                    }

                }


            } else {
                $description .= ' <span class="discreet italic">('._('Category').')</span>';

            }

        } else {
            $value       = 0;
            $description = '<span style="text-decoration: line-through;">'.$candidates_data[$category_key]['Category Label'].'</span>';
            $code        = '<span style="text-decoration: line-through;">'.$candidates_data[$category_key]['Category Code'].'</span>';
        }


        $image_key = $candidates_data[$category_key]['Category Main Image Key'];


        if ($image_key) {
            $image = '/wi.php?s=320x280&id='.$image_key;
        } else {
            $image = '/art/nopic.png';

        }

        $html = '';
        if (!empty($data['metadata']['splinter'])) {
            switch ($data['metadata']['splinter']) {
                case 'see_also_item':

                    $image_src = $image;


                    if (preg_match('/id=(\d+)/', $image_src, $matches)) {
                        $image_key = $matches[1];


                        $image_mobile_website = 'wi.php?id='.$image_key.'&s=320x200';
                        $image_website        = 'wi.php?id='.$image_key.'&s='.get_image_size($image_key, 432, 330, 'fit_highest');


                    } else {
                        $image_mobile_website = $image_src;
                        $image_website        = $image_src;
                    }


                    $see_also = array(
                        'type'                 => 'category',
                        'category_key'         => $category_key,
                        'header_text'          => $candidates_data[$category_key]['Category Label'],
                        'image_src'            => $image_src,
                        'image_mobile_website' => $image_mobile_website,
                        'image_website'        => $image_website,
                        'webpage_key'          => $candidates_data[$category_key]['Category Webpage Key'],
                        'webpage_code'         => strtolower($candidates_data[$category_key]['Webpage Code']),
                        'category_code'        => $candidates_data[$category_key]['Category Code'],
                        'number_products'      => $candidates_data[$category_key]['Products'],
                        'link'                 => $candidates_data[$category_key]['Webpage URL'],

                    );
                    $smarty->assign('item_data', $see_also);
                    $html .= $smarty->fetch('splinters/see_also_item.splinter.tpl');


                    //print $html;
                    //exit;
                    break;

            }
        }

        //print_r($data);


        $results[$category_key] = array(
            'code'            => $code,
            'description'     => $description,
            'value'           => $value,
            'formatted_value' => $candidates_data[$category_key]['Category Code'],
            'metadata'        => json_encode(
                array(
                    'html'                  => $html,
                    'key'                   => $category_key,
                    'code'                  => $candidates_data[$category_key]['Category Code'],
                    'title'                 => $candidates_data[$category_key]['Category Label'],
                    'image'                 => $image,
                    'number_products'       => $candidates_data[$category_key]['Products'],
                    'category_webpage_key'  => $candidates_data[$category_key]['Category Webpage Key'],
                    'category_key'          => $category_key,
                    'category_webpage_code' => $candidates_data[$category_key]['Webpage Code'],
                    'category_webpage_link' => $candidates_data[$category_key]['Webpage URL'],
                )
            )
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


function find_employees($db, $data) {


    $max_results = 5;
    $q           = trim($data['query']);

    $where = '';

    if ($q == '') {
        $response = array(
            'state'   => 200,
            'results' => 0,
            'data'    => ''
        );
        echo json_encode($response);

        return;
    }

    switch ($data['parent']) {
        case 'account':
            $where = sprintf(' and  true');
            break;

        default:

            break;
    }

    $join_tables = '';

    if (isset($data['metadata']['option'])) {
        switch ($data['metadata']['option']) {
            case 'only_working':
                $where .= sprintf(' and `Staff Currently Working`="Yes"');
                break;
            default:

                break;
        }

    }
    if (isset($data['metadata']['role'])) {
        $join_tables = ' left join `Staff Role Bridge` B on (S.`Staff Key`=B.`Staff Key`)';
        $where       .= "and `Role Code`='".addslashes($data['metadata']['role'])."'";


    }


    $candidates      = array();
    $candidates_data = array();


    $sql = sprintf(
        "select S.`Staff Key`,`Staff Alias`,`Staff Name`,`Staff ID`,`Staff Currently Working` from `Staff Dimension` S $join_tables  where  `Staff ID` like '%s%%' %s  limit $max_results ", $q, $where
    );


    if ($result = $db->query($sql)) {
        foreach ($result as $row) {

            if ($row['Staff Alias'] == $q) {
                $candidates[$row['Staff Key']] = 1000;
            } else {

                $len_name                      = strlen($row['Staff Alias']);
                $len_q                         = strlen($q);
                $factor                        = $len_q / $len_name;
                $candidates[$row['Staff Key']] = 500 * $factor;
            }

            $candidates_data[$row['Staff Key']] = array(
                'Staff Alias' => $row['Staff Alias'],
                'Staff Name'  => $row['Staff Name'],
                'Staff ID'    => $row['Staff ID'],
                'Status'      => $row['Staff Currently Working']

            );

        }
    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }


    $sql = sprintf(
        "select S.`Staff Key`,`Staff Alias`,`Staff Name`,`Staff ID`,`Staff Currently Working` from `Staff Dimension` S $join_tables   where  `Staff Alias` like '%s%%' %s  limit $max_results ", $q, $where
    );

    //print $sql;
    if ($result = $db->query($sql)) {
        foreach ($result as $row) {

            if ($row['Staff Alias'] == $q) {
                $candidates[$row['Staff Key']] = 500;
            } else {

                $len_name                      = strlen($row['Staff Alias']);
                $len_q                         = strlen($q);
                $factor                        = $len_q / $len_name;
                $candidates[$row['Staff Key']] = 250 * $factor;
            }

            $candidates_data[$row['Staff Key']] = array(
                'Staff Alias' => $row['Staff Alias'],
                'Staff Name'  => $row['Staff Name'],
                'Staff ID'    => $row['Staff ID'],
                'Status'      => $row['Staff Currently Working']

            );

        }
    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }

    $sql = "select S.`Staff Key`,`Staff Alias`,`Staff Name`,`Staff ID`,`Staff Currently Working` from `Staff Dimension` S $join_tables   where  `Staff Name`   REGEXP ? $where  limit $max_results ";


    $stmt = $db->prepare($sql);
    $stmt->execute(
        array(
            '\\b'.$q
        )
    );
    while ($row = $stmt->fetch()) {
        if ($row['Staff Alias'] == $q) {
            $candidates[$row['Staff Key']] = 400;
        } else {

            $len_name                      = strlen($row['Staff Alias']);
            $len_q                         = strlen($q);
            $factor                        = $len_q / $len_name;
            $candidates[$row['Staff Key']] = 200 * $factor;
        }

        $candidates_data[$row['Staff Key']] = array(
            'Staff Alias' => $row['Staff Alias'],
            'Staff Name'  => $row['Staff Name'],
            'Staff ID'    => $row['Staff ID'],
            'Status'      => $row['Staff Currently Working']

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
    foreach ($candidates as $staff_key => $candidate) {

        //  print $candidates_data[$staff_key]['Status'];

        if ($candidates_data[$staff_key]['Status'] = 'Yes') {
            $value       = $staff_key;
            $description = $candidates_data[$staff_key]['Staff Name'].' ('.$candidates_data[$staff_key]['Staff Alias'].')';
            $code        = $candidates_data[$staff_key]['Staff ID'];


        } else {
            $value       = 0;
            $description = '<span style="text-decoration: line-through;">'.$candidates_data[$staff_key]['Staff Name'].' ('.$candidates_data[$staff_key]['Staff Alias'].')</span>';
            $code        = '<span style="text-decoration: line-through;">'.$candidates_data[$staff_key]['Staff Id'].'</span>';
        }


        $results[$staff_key] = array(
            'code'            => $code,
            'description'     => $description,
            'value'           => $value,
            'formatted_value' => $candidates_data[$staff_key]['Staff Alias']
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


function find_families($db, $data) {


    $max_results = 5;
    $q           = trim($data['query']);


    if ($q == '') {
        $response = array(
            'state'   => 200,
            'results' => 0,
            'data'    => ''
        );
        echo json_encode($response);

        return;
    }


    $where = '';

    if (isset($data['metadata']['parent'])) {
        switch ($data['metadata']['parent']) {
            case 'root_key':


                $where = sprintf(' and `Category Root Key`=%d and `Category Branch Type`="Head" ', $data['metadata']['parent_key']);
                break;

            case 'store':


                $where = sprintf(" and `Category Store Key`=%d and  `Category Scope`='Product'  and `Category Branch Type`='Head'", $data['metadata']['parent_key']);
                break;
            case 'campaign':


                $where = sprintf(" and `Category Store Key`=%d and  `Category Scope`='Product'  and `Category Branch Type`='Head'", $data['metadata']['store_key']);
                break;
            default:

                break;
        }
    } else {

        switch ($data['parent']) {
            case 'store':
                $where = sprintf(" and `Category Store Key`=%d   and `Category Scope`='Product'  and `Category Branch Type`='Head' ", $data['parent_key']);
                break;
            default:

                break;
        }

    }


    $candidates = array();

    $candidates_data = array();


    $sql = sprintf(
        "select `Category Key`,`Category Code`,`Category Label` from `Category Dimension` where   `Category Code` like '%s%%' %s order by `Category Code` limit $max_results ", $q, $where
    );

    //    print $sql;

    if ($result = $db->query($sql)) {
        foreach ($result as $row) {

            if ($row['Category Code'] == $q) {
                $candidates[$row['Category Key']] = 1000;
            } else {

                $len_name                         = strlen(
                    $row['Category Key']
                );
                $len_q                            = strlen($q);
                $factor                           = $len_q / $len_name;
                $candidates[$row['Category Key']] = 500 * $factor;
            }

            $candidates_data[$row['Category Key']] = array(
                'Category Code'  => $row['Category Code'],
                'Category Label' => $row['Category Label']
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
    foreach ($candidates as $category_key => $candidate) {

        $results[$category_key] = array(
            'code'            => $candidates_data[$category_key]['Category Code'],
            'description'     => $candidates_data[$category_key]['Category Label'],
            'value'           => $category_key,
            'formatted_value' => $candidates_data[$category_key]['Category Code']
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


function find_part($db, $data) {


    $q = trim($data['query']);


    if ($q == '') {
        $response = array(
            'state'   => 200,
            'results' => 0,
            'data'    => ''
        );
        echo json_encode($response);

        return;
    }


    switch ($data['field']) {
        case 'barcode':
            $sql = sprintf(
                "SELECT `Part SKU`,`Part Reference`,`Part Package Description` FROM `Part Dimension` WHERE  `Part SKO Barcode` =%s  ", prepare_mysql($q)
            );
            break;
        default:
            $response = array(
                'state' => 405,
                'resp'  => 'Field not found '.$data['field']
            );
            echo json_encode($response);
            exit;

    }


    if ($result = $db->query($sql)) {
        if ($row = $result->fetch()) {


            $part = get_object('Part', $row['Part SKU']);

            $object_data = array(
                'key'         => $row['Part SKU'],
                'reference'   => $row['Part Reference'],
                'description' => $row['Part Package Description'],
                'image'       => $part->get('Package Description Image')

            );

            $response = array(
                'state'   => 200,
                'results' => 1,
                'data'    => $object_data
            );
            echo json_encode($response);

            return;
        } else {
            $response = array(
                'state'   => 200,
                'results' => 0,
                'data'    => ''
            );
            echo json_encode($response);

            return;
        }
    } else {
        print_r($error_info = $db->errorInfo());
        print "$sql\n";
        exit;
    }


}


function find_location($db, $data) {


    $q = trim($data['query']);


    if ($q == '') {
        $response = array(
            'state'   => 200,
            'results' => 0,
            'data'    => ''
        );
        echo json_encode($response);

        return;
    }


    switch ($data['field']) {
        case 'code':
            $sql = sprintf(
                "SELECT `Location Key`,`Location Code` FROM `Location Dimension` WHERE  `Location Code` =%s  ", prepare_mysql($q)
            );
            break;

        default:
            $response = array(
                'state' => 405,
                'resp'  => 'Field not found '.$data['field']
            );
            echo json_encode($response);
            exit;

    }


    if ($result = $db->query($sql)) {
        if ($row = $result->fetch()) {


            $object_data = array(
                'code' => $row['Location Code'],
                'key'  => $row['Location Key'],

            );

            $response = array(
                'state'   => 200,
                'results' => 1,
                'data'    => $object_data
            );
            echo json_encode($response);

            return;
        } else {
            $response = array(
                'state'   => 200,
                'results' => 0,
                'data'    => ''
            );
            echo json_encode($response);

            return;
        }
    } else {
        print_r($error_info = $db->errorInfo());
        print "$sql\n";
        exit;
    }


}


function find_allowance_targets($db, $data) {


    $max_results = 5;
    $q           = trim($data['query']);


    if ($q == '') {
        $response = array(
            'state'   => 200,
            'results' => 0,
            'data'    => ''
        );
        echo json_encode($response);

        return;
    }


    $where = '';

    if (isset($data['metadata']['parent'])) {
        switch ($data['metadata']['parent']) {
            case 'campaign':


                $where = sprintf(" and `Category Store Key`=%d and  `Category Scope`='Product'  and `Category Branch Type`='Head'", $data['metadata']['store_key']);
                break;
            default:

                break;
        }
    } else {

        switch ($data['parent']) {
            case 'store':
                $where = sprintf(" and `Category Store Key`=%d   and `Category Scope`='Product'  and `Category Branch Type`='Head' ", $data['parent_key']);
                break;
            default:

                break;
        }

    }


    $candidates = array();

    $candidates_data = array();


    $sql = sprintf(
        "select `Category Key`,`Category Code`,`Category Label`,`Deal Component Key` from `Category Dimension` left join `Deal Component Dimension` on (`Deal Component Allowance Target Key`=`Category Key` and `Deal Component Allowance Target`='Category' and `Deal Component Campaign Key`=%d) where   `Category Code` like '%s%%' %s   and `Deal Component Key` is null order by `Category Code` limit $max_results ",


        $data['metadata']['store_key'], $q, $where
    );

    //print $sql;

    if ($result = $db->query($sql)) {
        foreach ($result as $row) {

            if ($row['Category Code'] == $q) {
                $candidates[$row['Category Key']] = 1000;
            } else {

                $len_name                         = strlen(
                    $row['Category Key']
                );
                $len_q                            = strlen($q);
                $factor                           = $len_q / $len_name;
                $candidates[$row['Category Key']] = 500 * $factor;
            }

            $candidates_data[$row['Category Key']] = array(
                'Category Code'  => $row['Category Code'],
                'Category Label' => $row['Category Label']
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
    foreach ($candidates as $category_key => $candidate) {

        $results[$category_key] = array(
            'code'            => $candidates_data[$category_key]['Category Code'],
            'description'     => $candidates_data[$category_key]['Category Label'],
            'value'           => $category_key,
            'formatted_value' => $candidates_data[$category_key]['Category Code']
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


