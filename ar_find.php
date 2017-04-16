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

    case 'new_order_options':

        $data = prepare_values(
            $_REQUEST, array(
                         'customer_key' => array('type' => 'key'),
                     )
        );
        new_order_options($db, $data);
        break;
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
                         'state'      => array('type' => 'json array'),
                         'metadata'   => array(
                             'type'     => 'json array',
                             'optional' => true
                         )
                     )
        );

        $data['user'] = $user;

        switch ($data['scope']) {
            case 'item':

                if ($data['metadata']['scope'] == 'supplier_part') {
                    find_supplier_parts($db, $account, $memcache_ip, $data);

                }


                break;
            case 'employee':
                find_employees($db, $account, $memcache_ip, $data);
                break;
            case 'suppliers':
                find_suppliers($db, $account, $memcache_ip, $data);
                break;
            case 'stores':
                find_stores($db, $account, $memcache_ip, $data);
                break;
            case 'locations':
                find_locations($db, $account, $memcache_ip, $data);
                break;
            case 'parts':
                find_parts($db, $account, $memcache_ip, $data);
                break;
            case 'countries':
                find_countries($db, $account, $memcache_ip, $data);
                break;
            case 'products':
                find_products($db, $account, $memcache_ip, $data);
                break;
            case 'webpages':
                find_webpages($db, $account, $memcache_ip, $data);
                break;

            case 'product_categories':
                find_special_category('product_categories', $db, $account, $memcache_ip, $data);
                break;

            case 'families':
                find_special_category('Family', $db, $account, $memcache_ip, $data);
                break;
            case 'departments':
                find_special_category('Department', $db, $account, $memcache_ip, $data);
                break;
            case 'part_families':
                find_special_category('PartFamily', $db, $account, $memcache_ip, $data);
                break;
            case 'web_node':
                find_web_node($db, $account, $memcache_ip, $data);
                break;
            case 'product_webpages':
                find_product_webpages($db, $account, $memcache_ip, $data);
                break;
            case 'category_webpages':
                find_category_webpages($db, $account, $memcache_ip, $data);
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


function find_suppliers($db, $account, $memcache_ip, $data) {


    $cache       = false;
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


    $memcache_fingerprint = $account->get('Account Code').'SEARCH_SUP'.md5(
            $queries
        );

    $cache = new Memcached();
    $cache->addServer($memcache_ip, 11211);


    if (strlen($queries) <= 2) {
        $memcache_time = 295200;
    }
    if (strlen($queries) <= 3) {
        $memcache_time = 86400;
    }
    if (strlen($queries) <= 4) {
        $memcache_time = 3600;
    } else {
        $memcache_time = 300;

    }


    $results_data = $cache->get($memcache_fingerprint);


    if (!$results_data or true) {


        $candidates = array();

        $query_array    = preg_split('/\s+/', $queries);
        $number_queries = count($query_array);


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


            $sql = sprintf(
                "SELECT `Supplier Key`,`Supplier Code`,`Supplier Name` FROM `Supplier Dimension` WHERE  `Supplier Name`  REGEXP '[[:<:]]%s' LIMIT 100 ", $q
            );

            if ($result = $db->query($sql)) {
                foreach ($result as $row) {
                    if ($row['Supplier Name'] == $q) {
                        $candidates[$row['Supplier Key']] = 55;
                    } else {

                        $len_name                         = strlen(
                            $row['Supplier Name']
                        );
                        $len_q                            = strlen($q);
                        $factor                           = $len_q / $len_name;
                        $candidates[$row['Supplier Key']] = 50 * $factor;
                    }

                }
            } else {
                print_r($error_info = $db->errorInfo());
                exit;
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
        $cache->set($memcache_fingerprint, $results_data, $memcache_time);


    }
    $response = array(
        'state'          => 200,
        'number_results' => $results_data['n'],
        'results'        => $results_data['d'],
        'q'              => $queries
    );

    echo json_encode($response);

}


function find_stores($db, $account, $memcache_ip, $data) {


    $cache       = false;
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

    $memcache_fingerprint = $account->get('Account Code').'SEARCH_STORE'.md5(
            $queries
        );

    $cache = new Memcached();
    $cache->addServer($memcache_ip, 11211);


    if (strlen($queries) <= 2) {
        $memcache_time = 295200;
    }
    if (strlen($queries) <= 3) {
        $memcache_time = 86400;
    }
    if (strlen($queries) <= 4) {
        $memcache_time = 3600;
    } else {
        $memcache_time = 300;

    }


    $results_data = $cache->get($memcache_fingerprint);


    if (!$results_data or true) {


        $candidates = array();

        $query_array    = preg_split('/\s+/', $queries);
        $number_queries = count($query_array);


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


            $sql = sprintf(
                "select `Store Key`,`Store Code`,`Store Name` from `Store Dimension` where true $where_store and `Store Name`  REGEXP '[[:<:]]%s' limit 100 ", $q
            );

            if ($result = $db->query($sql)) {
                foreach ($result as $row) {
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
            } else {
                print_r($error_info = $db->errorInfo());
                exit;
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
        $cache->set($memcache_fingerprint, $results_data, $memcache_time);


    }
    $response = array(
        'state'          => 200,
        'number_results' => $results_data['n'],
        'results'        => $results_data['d'],
        'q'              => $queries
    );

    echo json_encode($response);

}


function find_locations($db, $account, $memcache_ip, $data) {


    $cache       = false;
    $max_results = 10;
    $user        = $data['user'];
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
        ' and `Location Warehouse Key` in (%s)', join(',', $user->warehouses)
    );

    $memcache_fingerprint = $account->get('Account Code').'FIND_LOCATION'.md5(
            $q
        );

    $cache = new Memcached();
    $cache->addServer($memcache_ip, 11211);


    if (strlen($q) <= 2) {
        $memcache_time = 295200;
    }
    if (strlen($q) <= 3) {
        $memcache_time = 86400;
    }
    if (strlen($q) <= 4) {
        $memcache_time = 3600;
    } else {
        $memcache_time = 300;

    }


    $results_data = $cache->get($memcache_fingerprint);


    if (!$results_data or true) {


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
        $cache->set($memcache_fingerprint, $results_data, $memcache_time);


    }
    $response = array(
        'state'          => 200,
        'number_results' => $results_data['n'],
        'results'        => $results_data['d'],
        'q'              => $q
    );

    echo json_encode($response);

}


function find_parts($db, $account, $memcache_ip, $data) {

    $cache       = false;
    $max_results = 5;
    $user        = $data['user'];
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

    $memcache_fingerprint = $account->get('Account Code').'FIND_PART'.md5($q);

    $cache = new Memcached();
    $cache->addServer($memcache_ip, 11211);


    if (strlen($q) <= 2) {
        $memcache_time = 295200;
    }
    if (strlen($q) <= 3) {
        $memcache_time = 86400;
    }
    if (strlen($q) <= 4) {
        $memcache_time = 3600;
    } else {
        $memcache_time = 300;

    }


    $results_data = $cache->get($memcache_fingerprint);


    if (!$results_data or true) {


        $candidates = array();

        $candidates_data = array();


        $sql = sprintf(
            "select `Part SKU`,`Part Reference`,`Part Unit Description` from `Part Dimension` where  `Part Reference` like '%s%%' and `Part Status` in ('In Use','Discontinuing')   order by `Part Reference` limit $max_results ",
            $q
        );


        if ($result = $db->query($sql)) {
            foreach ($result as $row) {

                if ($row['Part Reference'] == $q) {
                    $candidates[$row['Part SKU']] = 1000;
                } else {

                    $len_name                     = strlen($row['Part SKU']);
                    $len_q                        = strlen($q);
                    $factor                       = $len_q / $len_name;
                    $candidates[$row['Part SKU']] = 500 * $factor;
                }

                $candidates_data[$row['Part SKU']] = array(
                    'Part Reference'        => $row['Part Reference'],
                    'Part Unit Description' => $row['Part Unit Description']
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

            $results[$part_sku] = array(
                'code'            => $candidates_data[$part_sku]['Part Reference'],
                'description'     => $candidates_data[$part_sku]['Part Unit Description'],
                'value'           => $part_sku,
                'formatted_value' => $candidates_data[$part_sku]['Part Reference']
            );

        }

        $results_data = array(
            'n' => count($results),
            'd' => $results
        );
        $cache->set($memcache_fingerprint, $results_data, $memcache_time);


    }
    $response = array(
        'state'          => 200,
        'number_results' => $results_data['n'],
        'results'        => $results_data['d'],
        'q'              => $q
    );

    echo json_encode($response);

}


function find_products($db, $account, $memcache_ip, $data) {


    $cache       = false;
    $max_results = 5;
    $user        = $data['user'];
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

    if(isset($data['metadata']['parent'])){
        switch ($data['metadata']['parent']) {
            case 'store':
                $where = sprintf(' and `Product Store Key`=%d', $data['metadata']['parent_key']
                );
                break;
            default:

                break;
        }
    }else{

        switch ($data['parent']) {
            case 'store':
                $where = sprintf(' and `Product Store Key`=%d', $data['parent_key']
                );
                break;
            default:

                break;
        }

    }




    $memcache_fingerprint = $account->get('Account Code').'FIND_PRODUCTS'.md5(
            $q
        );

    $cache = new Memcached();
    $cache->addServer($memcache_ip, 11211);


    if (strlen($q) <= 2) {
        $memcache_time = 295200;
    }
    if (strlen($q) <= 3) {
        $memcache_time = 86400;
    }
    if (strlen($q) <= 4) {
        $memcache_time = 3600;
    } else {
        $memcache_time = 300;

    }


    $results_data = $cache->get($memcache_fingerprint);


    if (!$results_data or true) {


        $candidates = array();

        $candidates_data = array();


        $sql = sprintf(
            "select `Product ID`,`Product Code`,`Product Name` from `Product Dimension` where  `Product Code` like '%s%%' %s order by `Product Code` limit $max_results ", $q, $where
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
                    'Product Code' => $row['Product Code'],
                    'Product Name' => $row['Product Name']
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
                'code'            => $candidates_data[$product_sku]['Product Code'],
                'description'     => $candidates_data[$product_sku]['Product Name'],
                'value'           => $product_sku,
                'formatted_value' => $candidates_data[$product_sku]['Product Code']
            );

        }

        $results_data = array(
            'n' => count($results),
            'd' => $results
        );
        $cache->set($memcache_fingerprint, $results_data, $memcache_time);


    }
    $response = array(
        'state'          => 200,
        'number_results' => $results_data['n'],
        'results'        => $results_data['d'],
        'q'              => $q
    );

    echo json_encode($response);

}


function find_supplier_parts($db, $account, $memcache_ip, $data) {


    $cache       = false;
    $max_results = 5;
    $user        = $data['user'];
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


    if ($data['metadata']['parent'] == 'Supplier') {

        $where = sprintf(
            ' `Supplier Part Supplier Key`=%d and ', $data['metadata']['parent_key']
        );

    }

    if (!isset($data['metadata']['options']['all_parts'])) {
        $where .= " `Part Status` not in ('Not In Use','Discontinuing') and ";
    }
    if (!isset($data['metadata']['options']['all_supplier_parts'])) {
        $where .= " `Supplier Part Status`='Available' and ";
    }


    $memcache_fingerprint = $account->get('Account Code').'FIND_PART'.md5($q);

    $cache = new Memcached();
    $cache->addServer($memcache_ip, 11211);


    if (strlen($q) <= 2) {
        $memcache_time = 295200;
    }
    if (strlen($q) <= 3) {
        $memcache_time = 86400;
    }
    if (strlen($q) <= 4) {
        $memcache_time = 3600;
    } else {
        $memcache_time = 300;

    }


    $results_data = $cache->get($memcache_fingerprint);


    if (!$results_data or true) {


        $candidates = array();

        $candidates_data = array();


        $sql = sprintf(
            "SELECT `Supplier Part Reference`,`Supplier Part Historic Key`,`Supplier Part Key`,`Part Reference`,`Part Unit Description` FROM   `Supplier Part Dimension` SP LEFT JOIN  `Part Dimension` ON (`Supplier Part Part SKU`=`Part SKU`) WHERE %s `Supplier Part Reference` LIKE '%s%%'  ORDER BY `Supplier Part Reference` LIMIT %d ",
            $where, $q, $max_results
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
                    'Part Unit Description'      => $row['Part Unit Description']
                );

            }
        } else {
            print_r($error_info = $db->errorInfo());
            exit;
        }


        $sql = sprintf(
            "SELECT `Supplier Part Key`,`Supplier Part Historic Key`,`Supplier Part Reference`,`Part Reference`,`Part Unit Description` FROM   `Supplier Part Dimension` SP LEFT JOIN  `Part Dimension` ON (`Supplier Part Part SKU`=`Part SKU`) WHERE %s `Part Reference` LIKE '%s%%'  ORDER BY `Part Reference` LIMIT %d ",
            $where, $q, $max_results
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
                    'Part Unit Description'      => $row['Part Unit Description']
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
        foreach ($candidates as $supplier_part_key => $candidate) {


            $description = $candidates_data[$supplier_part_key]['Part Unit Description'];
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
        $cache->set($memcache_fingerprint, $results_data, $memcache_time);


    }
    $response = array(
        'state'          => 200,
        'number_results' => $results_data['n'],
        'results'        => $results_data['d'],
        'q'              => $q
    );

    echo json_encode($response);

}


function find_special_category($type, $db, $account, $memcache_ip, $data) {


    $cache       = false;
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


    $memcache_fingerprint = $account->get('Account Code').'SEARCH_SPCL_CAT'.$type.$root_keys.md5($queries);

    $cache = new Memcached();
    $cache->addServer($memcache_ip, 11211);


    if (strlen($queries) <= 2) {
        $memcache_time = 295200;
    }
    if (strlen($queries) <= 3) {
        $memcache_time = 86400;
    }
    if (strlen($queries) <= 4) {
        $memcache_time = 3600;
    } else {
        $memcache_time = 300;

    }


    $results_data = $cache->get($memcache_fingerprint);


    if (!$results_data or true) {


        $candidates = array();

        $query_array    = preg_split('/\s+/', $queries);
        $number_queries = count($query_array);


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


            $sql = sprintf(
                "select `Category Key`,`Category Code`,`Category Label` from `Category Dimension` where true $where_root_categories and `Category Label`  REGEXP '[[:<:]]%s' limit 100 ", $q
            );

            if ($result = $db->query($sql)) {
                foreach ($result as $row) {
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
            } else {
                print_r($error_info = $db->errorInfo());
                exit;
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

                $code=$row['Category Code'];
                if ($type == 'product_categories') {
                    $code=$row['Category Code'];

                    if($row['Category Root Key']==$store->get('Store Family Category Key')){
                        $code.=' (F)';
                    }else if($row['Category Root Key']==$store->get('Store Department Category Key')){
                        $code.=' (D)';
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
        $cache->set($memcache_fingerprint, $results_data, $memcache_time);


    }
    $response = array(
        'state'          => 200,
        'number_results' => $results_data['n'],
        'results'        => $results_data['d'],
        'q'              => $queries
    );

    echo json_encode($response);

}


function find_countries($db, $account, $memcache_ip, $data) {


    $cache       = false;
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


    $memcache_fingerprint = $account->get('Account Code').'SEARCH_COUNTRY'.md5(
            $queries
        );

    $cache = new Memcached();
    $cache->addServer($memcache_ip, 11211);


    if (strlen($queries) <= 2) {
        $memcache_time = 295200;
    }
    if (strlen($queries) <= 3) {
        $memcache_time = 86400;
    }
    if (strlen($queries) <= 4) {
        $memcache_time = 3600;
    } else {
        $memcache_time = 300;

    }


    $results_data = $cache->get($memcache_fingerprint);


    if (!$results_data or true) {


        $candidates = array();

        $query_array    = preg_split('/\s+/', $queries);
        $number_queries = count($query_array);


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


            $sql = sprintf(
                "SELECT `Country Key`,`Country Code`,`Country Name` FROM kbase.`Country Dimension` WHERE  `Country Name`  REGEXP '[[:<:]]%s' LIMIT 100 ", $q
            );

            if ($result = $db->query($sql)) {
                foreach ($result as $row) {
                    if ($row['Country Name'] == $q) {
                        $candidates[$row['Country Key']] = 55;
                    } else {

                        $len_name                        = strlen(
                            $row['Country Name']
                        );
                        $len_q                           = strlen($q);
                        $factor                          = $len_q / $len_name;
                        $candidates[$row['Country Key']] = 50 * $factor;
                    }

                }
            } else {
                print_r($error_info = $db->errorInfo());
                exit;
            }

            $sql = sprintf(
                "SELECT `Country Key`,`Country Code`,`Country Local Name` FROM kbase.`Country Dimension` WHERE  `Country Local Name`  REGEXP '[[:<:]]%s' LIMIT 100 ", $q
            );

            if ($result = $db->query($sql)) {
                foreach ($result as $row) {
                    if ($row['Country Local Name'] == $q) {
                        $candidates[$row['Country Key']] = 55;
                    } else {

                        $len_name                        = strlen(
                            $row['Country Local Name']
                        );
                        $len_q                           = strlen($q);
                        $factor                          = $len_q / $len_name;
                        $candidates[$row['Country Key']] = 50 * $factor;
                    }

                }
            } else {
                print_r($error_info = $db->errorInfo());
                exit;
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
        $cache->set($memcache_fingerprint, $results_data, $memcache_time);


    }
    $response = array(
        'state'          => 200,
        'number_results' => $results_data['n'],
        'results'        => $results_data['d'],
        'q'              => $queries
    );

    echo json_encode($response);

}


function number_orders_in_process($db, $data) {

    $number_orders_in_process = 0;
    $orders_list              = '';
    $msg                      = '';
    $sql                      = sprintf(
        "SELECT `Purchase Order Key`,`Purchase Order Public ID`,`Purchase Order Store Key`  FROM `Purchase Order Dimension` WHERE `Purchase Order Customer Key`=%d AND `Purchase Order Current Dispatch State`='In Process'",
        $data['customer_key']
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


    $orders_list = preg_replace('/^,\s*/', '', $orders_list);

    if ($number_orders_in_process == 0) {
        $response = array(
            'state'             => 200,
            'orders_in_process' => $number_orders_in_process,
            'msg'               => ''
        );
        echo json_encode($response);
        exit;
    }

    if ($number_orders_in_process == 1) {
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
        'orders_in_process' => $number_orders_in_process,
        'msg'               => $msg,
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
        "SELECT `Purchase Order Key`,`Purchase Order Public ID`,`Purchase Order Warehouse Key` FROM `Purchase Order Dimension` WHERE `Purchase Order Parent`=%s AND `Purchase Order Parent Key`=%d AND `Purchase Order State`='In Process'",
        prepare_mysql($data['parent']), $data['parent_key']
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
        "SELECT `Warehouse Key`,`Warehouse Code`,`Warehouse Name` FROM `Warehouse Dimension` WHERE `Warehouse State`='Active'", prepare_mysql($data['parent']), $data['parent_key']
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


function find_web_node($db, $account, $memcache_ip, $data) {


    $cache       = false;
    $max_results = 5;
    $user        = $data['user'];
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
            $where = sprintf(' and `Page Site Key`=%d', $data['parent_key']);
            break;
        default:

            break;
    }

    $where .= ' and `Page State`="Online" ';

    $memcache_fingerprint = $account->get('Account Code').'FIND_WN'.md5($q);

    $cache = new Memcached();
    $cache->addServer($memcache_ip, 11211);


    if (strlen($q) <= 2) {
        $memcache_time = 295200;
    }
    if (strlen($q) <= 3) {
        $memcache_time = 86400;
    }
    if (strlen($q) <= 4) {
        $memcache_time = 3600;
    } else {
        $memcache_time = 300;

    }


    $results_data = $cache->get($memcache_fingerprint);


    if (!$results_data or true) {


        $candidates = array();

        $candidates_data = array();


        $sql = sprintf(
            "select `Page Key`,`Page Code`,`Page Store Title` from `Page Store Dimension` where  `Page Code` like '%s%%' %s order by `Page Code` limit $max_results ", $q, $where
        );
        if ($result = $db->query($sql)) {
            foreach ($result as $row) {

                if ($row['Page Code'] == $q) {
                    $candidates[$row['Page Key']] = 1000;
                } else {

                    $len_name                     = strlen($row['Page Key']);
                    $len_q                        = strlen($q);
                    $factor                       = $len_q / $len_name;
                    $candidates[$row['Page Key']] = 500 * $factor;
                }

                $candidates_data[$row['Page Key']] = array(
                    'Page Code'        => $row['Page Code'],
                    'Page Store Title' => $row['Page Store Title']
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
                'code'            => $candidates_data[$product_sku]['Page Code'],
                'description'     => $candidates_data[$product_sku]['Page Store Title'],
                'value'           => $product_sku,
                'formatted_value' => $candidates_data[$product_sku]['Page Code']
            );

        }

        $results_data = array(
            'n' => count($results),
            'd' => $results
        );
        $cache->set($memcache_fingerprint, $results_data, $memcache_time);


    }
    $response = array(
        'state'          => 200,
        'number_results' => $results_data['n'],
        'results'        => $results_data['d'],
        'q'              => $q
    );

    echo json_encode($response);

}


function find_webpages($db, $account, $memcache_ip, $data) {


    $cache       = false;
    $max_results = 5;
    $user        = $data['user'];
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
            $where = sprintf(' and `Page Site Key`=%d', $data['parent_key']);
            break;
        default:

            break;
    }


    if (isset($data['metadata']['option'])) {
        switch ($data['metadata']['option']) {
            case 'only_online':
                $where .= sprintf(' and `Page State`="Online"');
                break;
            default:

                break;
        }

    }

    if (isset($data['metadata']['exclude']) and count(
            $data['metadata']['exclude']
        ) > 0
    ) {
        $where .= sprintf(
            ' and `Page Key` not in (%s) ', join(',', $data['metadata']['exclude'])
        );

    }

    $memcache_fingerprint = $account->get('Account Code').'FIND_WEBP'.md5($q);

    $cache = new Memcached();
    $cache->addServer($memcache_ip, 11211);


    if (strlen($q) <= 2) {
        $memcache_time = 295200;
    }
    if (strlen($q) <= 3) {
        $memcache_time = 86400;
    }
    if (strlen($q) <= 4) {
        $memcache_time = 3600;
    } else {
        $memcache_time = 300;

    }


    $results_data = $cache->get($memcache_fingerprint);


    if (!$results_data or true) {

        $candidates      = array();
        $candidates_data = array();


        $sql = sprintf(
            "select `Page Key`,`Page Code`,`Page Store Title` from `Page Store Dimension` where  `Page Code` like '%s%%' %s order by `Page Code` limit $max_results ", $q, $where
        );


        if ($result = $db->query($sql)) {
            foreach ($result as $row) {

                if ($row['Page Code'] == $q) {
                    $candidates[$row['Page Key']] = 1000;
                } else {

                    $len_name                     = strlen($row['Page Key']);
                    $len_q                        = strlen($q);
                    $factor                       = $len_q / $len_name;
                    $candidates[$row['Page Key']] = 500 * $factor;
                }

                $candidates_data[$row['Page Key']] = array(
                    'Page Code'        => $row['Page Code'],
                    'Page Store Title' => $row['Page Store Title']
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
                'code'            => $candidates_data[$product_sku]['Page Code'],
                'description'     => $candidates_data[$product_sku]['Page Store Title'],
                'value'           => $product_sku,
                'formatted_value' => $candidates_data[$product_sku]['Page Code']
            );

        }

        $results_data = array(
            'n' => count($results),
            'd' => $results
        );
        $cache->set($memcache_fingerprint, $results_data, $memcache_time);


    }
    $response = array(
        'state'          => 200,
        'number_results' => $results_data['n'],
        'results'        => $results_data['d'],
        'q'              => $q
    );

    echo json_encode($response);

}


function find_product_webpages($db, $account, $memcache_ip, $data) {


    $cache       = false;
    $max_results = 5;
    $user        = $data['user'];
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
            $where = sprintf(' and `Page Site Key`=%d', $data['parent_key']);
            break;
        default:

            break;
    }


    if (isset($data['metadata']['option'])) {
        switch ($data['metadata']['option']) {
            case 'only_online':
                $where .= sprintf(' and `Page State`="Online"');
                break;
            default:

                break;
        }

    }

    if (isset($data['metadata']['exclude']) and count(
            $data['metadata']['exclude']
        ) > 0
    ) {
        $where .= sprintf(
            ' and `Product ID` not in (%s) ', join(',', $data['metadata']['exclude'])
        );

    }

    $memcache_fingerprint = $account->get('Account Code').'FIND_PWebP'.md5($q);

    $cache = new Memcached();
    $cache->addServer($memcache_ip, 11211);


    if (strlen($q) <= 2) {
        $memcache_time = 295200;
    }
    if (strlen($q) <= 3) {
        $memcache_time = 86400;
    }
    if (strlen($q) <= 4) {
        $memcache_time = 3600;
    } else {
        $memcache_time = 300;

    }


    $results_data = $cache->get($memcache_fingerprint);


    if (!$results_data or true) {

        $candidates      = array();
        $candidates_data = array();


        $sql = sprintf(
            "select `Product ID`,`Product Code`,`Product Name`,`Page Store Title` from `Page Store Dimension`  left join `Product Dimension` on (`Page Parent Key`=`Product ID` and `Page Store Section Type`='Product')  where  `Product Code` like '%s%%' %s order by `Product Code` limit $max_results ",
            $q, $where
        );

        //	print $sql;
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
                    'Product Code' => $row['Product Code'],
                    'Product Name' => $row['Product Name']
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
                'code'            => $candidates_data[$product_sku]['Product Code'],
                'description'     => $candidates_data[$product_sku]['Product Name'],
                'value'           => $product_sku,
                'formatted_value' => $candidates_data[$product_sku]['Product Code']
            );

        }

        $results_data = array(
            'n' => count($results),
            'd' => $results
        );
        $cache->set($memcache_fingerprint, $results_data, $memcache_time);


    }
    $response = array(
        'state'          => 200,
        'number_results' => $results_data['n'],
        'results'        => $results_data['d'],
        'q'              => $q
    );

    echo json_encode($response);

}


function find_category_webpages($db, $account, $memcache_ip, $data) {


    $cache       = false;
    $max_results = 5;
    $user        = $data['user'];
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
            $where = sprintf(' and `Page Site Key`=%d', $data['parent_key']);
            break;
        case 'store':
            $where = sprintf(' and `Page Store Key`=%d', $data['parent_key']);
            break;
        default:

            break;
    }


    if (isset($data['action'])) {
        $action = $data['action'];
    } else {
        $action = '';
    }


    /*

    if (isset($data['metadata']['option'])) {
        switch ($data['metadata']['option']) {
            case 'only_online':
                $where .= sprintf(' and `Page State`="Online"');
                break;
            default:

                break;
        }

    }

    if (isset($data['metadata']['exclude']) and count(
            $data['metadata']['exclude']
        ) > 0
    ) {
        $where .= sprintf(
            ' and `Product Category Key` not in (%s) ', join(',', $data['metadata']['exclude'])
        );

    }

    */

    $memcache_fingerprint = $account->get('Account Code').'FIND_CatWebP'.md5($q);

    $cache = new Memcached();
    $cache->addServer($memcache_ip, 11211);


    if (strlen($q) <= 2) {
        $memcache_time = 295200;
    }
    if (strlen($q) <= 3) {
        $memcache_time = 86400;
    }
    if (strlen($q) <= 4) {
        $memcache_time = 3600;
    } else {
        $memcache_time = 300;

    }


    $results_data = $cache->get($memcache_fingerprint);


    if (!$results_data or true) {

        $candidates      = array();
        $candidates_data = array();


        $sql = sprintf(
            "select `Product Category Public`,`Webpage State`,`Page Key`,`Category Code`,`Category Label`,`Category Subject`,`Category Key`,`Product Category Active Products`,`Product Category Discontinuing Products` ,`Product Category In Process Products`, `Product Category Status` from `Page Store Dimension`  left join `Category Dimension` on (`Webpage Scope Key`=`Category Key` and `Webpage Scope` in ('Category Products','Category Categories')  )   left join `Product Category Dimension` on (`Category Key`=`Product Category Key`)  where  `Category Code` like '%s%%' %s order by `Category Code` limit $max_results ",
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
                    'Category Code'    => $row['Category Code'],
                    'Category Label'   => $row['Category Label'],
                    'Status'           => $row['Product Category Status'],
                    'Products'         => $row['Product Category Active Products'] + $row['Product Category Discontinuing Products'] + $row['Product Category In Process Products'],
                    'Category Subject' => $row['Category Subject'],
                    'Public'           => $row['Product Category Public'],
                    'Webpage State'    => $row['Webpage State'],

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
                        } else {
                            if ($candidates_data[$category_key]['Webpage State'] == 'Offline') {
                                $description .= ' <i class="fa fa-exclamation-circle padding_left_10 error" aria-hidden="true"></i>  <span class="error">'._('Webpage is offline').'</span>';
                                $code        = '<span class="strikethrough">'.$candidates_data[$category_key]['Category Code'].'</span>';
                                $value       = 0;
                            } else {
                                if ($candidates_data[$category_key]['Products'] == 0) {
                                    $description .= ' <i class="fa fa-exclamation-circle padding_left_10 error" aria-hidden="true"></i>  <span class="error">'._(
                                            "Category don't have any product for sale"
                                        ).'</span>';
                                    $code        = '<span class="strikethrough">'.$candidates_data[$category_key]['Category Code'].'</span>';
                                    $value       = 0;
                                }
                            }
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


            $results[$category_key] = array(
                'code'            => $code,
                'description'     => $description,
                'value'           => $value,
                'formatted_value' => $candidates_data[$category_key]['Category Code']
            );

        }

        $results_data = array(
            'n' => count($results),
            'd' => $results
        );
        $cache->set($memcache_fingerprint, $results_data, $memcache_time);


    }
    $response = array(
        'state'          => 200,
        'number_results' => $results_data['n'],
        'results'        => $results_data['d'],
        'q'              => $q
    );

    echo json_encode($response);

}


function find_employees($db, $account, $memcache_ip, $data) {


    $cache       = false;
    $max_results = 5;
    $user        = $data['user'];
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

    //  $where = sprintf("  and `Product Staff Status` in ('Active','Discontinuing') ");
    switch ($data['parent']) {
        case 'account':
            $where = sprintf(' and  true');
            break;

        default:

            break;
    }


    if (isset($data['metadata']['option'])) {
        switch ($data['metadata']['option']) {
            case 'only_working':
                $where .= sprintf(' and `Staff Currently Working`="Yes"');
                break;
            default:

                break;
        }

    }


    /*

    if (isset($data['metadata']['exclude']) and count(
            $data['metadata']['exclude']
        ) > 0
    ) {
        $where .= sprintf(
            ' and `Product Staff Key` not in (%s) ', join(',', $data['metadata']['exclude'])
        );

    }

    */

    $memcache_fingerprint = $account->get('Account Code').'FIND_Staff'.md5($q);

    $cache = new Memcached();
    $cache->addServer($memcache_ip, 11211);


    if (strlen($q) <= 2) {
        $memcache_time = 295200;
    }
    if (strlen($q) <= 3) {
        $memcache_time = 86400;
    }
    if (strlen($q) <= 4) {
        $memcache_time = 3600;
    } else {
        $memcache_time = 300;

    }


    $results_data = $cache->get($memcache_fingerprint);


    if (!$results_data or true) {

        $candidates      = array();
        $candidates_data = array();


        $sql = sprintf(
            "select `Staff Key`,`Staff Alias`,`Staff Name`,`Staff ID`,`Staff Currently Working` from `Staff Dimension`   where  `Staff ID` like '%s%%' %s  limit $max_results ", $q, $where
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
            "select `Staff Key`,`Staff Alias`,`Staff Name`,`Staff ID`,`Staff Currently Working` from `Staff Dimension`   where  `Staff Alias` like '%s%%' %s  limit $max_results ", $q, $where
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

        $sql = sprintf(
            "select `Staff Key`,`Staff Alias`,`Staff Name`,`Staff ID`,`Staff Currently Working` from `Staff Dimension`   where  `Staff Name`   REGEXP '[[:<:]]%s' %s  limit $max_results ", $q, $where
        );

        // print $sql;
        if ($result = $db->query($sql)) {
            foreach ($result as $row) {

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
        $cache->set($memcache_fingerprint, $results_data, $memcache_time);


    }
    $response = array(
        'state'          => 200,
        'number_results' => $results_data['n'],
        'results'        => $results_data['d'],
        'q'              => $q
    );

    echo json_encode($response);

}


?>
