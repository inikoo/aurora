<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 1 December 2015 at 18:35:53 GMT, Sheffield UK
 Copyright (c) 2015, Inikoo

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
    case 'search':

        $data = prepare_values(
            $_REQUEST, array(
                'query' => array('type' => 'string'),
                'state' => array('type' => 'json array')
            )
        );

        $data['user'] = $user;

        if ($user->get('User Type') == 'Agent') {
            agent_search($db, $account, $user, $memcache_ip, $data);
        } else {

            if ($data['state']['module'] == 'customers') {
                if ($data['state']['current_store']) {
                    $data['scope']     = 'store';
                    $data['scope_key'] = $data['state']['current_store'];
                } else {
                    $data['scope'] = 'stores';
                }
                search_customers($db, $account, $memcache_ip, $data);
            } elseif ($data['state']['module'] == 'orders') {
                if ($data['state']['current_store']) {
                    $data['scope']     = 'store';
                    $data['scope_key'] = $data['state']['current_store'];
                } else {
                    $data['scope'] = 'stores';
                }
                search_orders($db, $account, $memcache_ip, $data);
            } elseif ($data['state']['module'] == 'products') {
                if ($data['state']['current_store']) {
                    $data['scope']     = 'store';
                    $data['scope_key'] = $data['state']['current_store'];
                } else {
                    $data['scope'] = 'stores';
                }
                search_products($db, $account, $memcache_ip, $data);
            } elseif ($data['state']['module'] == 'products_server') {

                $data['scope'] = 'stores';

                search_products($db, $account, $memcache_ip, $data);
            } elseif ($data['state']['module'] == 'inventory') {
                if ($data['state']['current_warehouse']) {
                    $data['scope']     = 'warehouse';
                    $data['scope_key'] = $data['state']['current_warehouse'];
                } else {
                    $data['scope'] = 'warehouses';
                }
                search_inventory($db, $account, $memcache_ip, $data);
            } elseif ($data['state']['module'] == 'hr') {
                search_hr($db, $account, $memcache_ip, $data);

            } elseif ($data['state']['module'] == 'suppliers') {
                search_suppliers($db, $account, $memcache_ip, $data);

            } elseif ($data['state']['module'] == 'delivery_notes') {
                if ($data['state']['current_store']) {
                    $data['scope']     = 'store';
                    $data['scope_key'] = $data['state']['current_store'];
                } else {
                    $data['scope'] = 'stores';
                }
                search_delivery_notes($db, $account, $memcache_ip, $data);
            } elseif ($data['state']['module'] == 'delivery_notes_server') {

                $data['scope'] = 'stores';

                search_delivery_notes($db, $account, $memcache_ip, $data);
            } elseif ($data['state']['module'] == 'orders_server') {
                $data['scope'] = 'stores';
                search_orders($db, $account, $memcache_ip, $data);
            } elseif ($data['state']['module'] == 'invoices_server') {
                $data['scope'] = 'stores';
                search_invoices($db, $account, $memcache_ip, $data);
            } elseif ($data['state']['module'] == 'invoices') {
                if ($data['state']['current_store']) {
                    $data['scope']     = 'store';
                    $data['scope_key'] = $data['state']['current_store'];
                } else {
                    $data['scope'] = 'stores';
                }
                search_invoices($db, $account, $memcache_ip, $data);
            } elseif ($data['state']['module'] == 'warehouses') {
                if ($data['state']['current_warehouse']) {
                    $data['scope']     = 'warehouse';
                    $data['scope_key'] = $data['state']['current_warehouse'];
                } else {
                    $data['scope'] = 'warehouses';
                }
                search_locations($db, $account, $memcache_ip, $data);
            }
        }
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

function search_suppliers($db, $account, $memcache_ip, $data) {


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


    $memcache_fingerprint = $account->get('Account Code').'SEARCH_SUPPLIERS'.md5($queries);

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
                "SELECT `Supplier Key`,`Supplier Code`,`Supplier Type` FROM `Supplier Dimension` WHERE `Supplier Code` LIKE '%s%%' LIMIT 20 ", $q
            );


            if ($result = $db->query($sql)) {
                foreach ($result as $row) {

                    if ($row['Supplier Code'] == $q) {
                        if ($row['Supplier Type'] == 'Archived') {
                            $candidates['S'.$row['Supplier Key']] = 500;

                        } else {
                            $candidates['S'.$row['Supplier Key']] = 1000;
                        }

                    } else {

                        $len_name = strlen($row['Supplier Code']);
                        $len_q    = strlen($q);
                        $factor   = $len_q / $len_name;
                        if ($row['Supplier Type'] == 'Archived') {
                            $candidates['S'.$row['Supplier Key']] = 250 * $factor;

                        } else {
                            $candidates['S'.$row['Supplier Key']] = 500 * $factor;
                        }
                    }

                }
            } else {
                print_r($error_info = $db->errorInfo());
                print $sql;
                exit;
            }

            $sql = sprintf(
                "SELECT `Supplier Key`,`Supplier Name`,`Supplier Type` FROM `Supplier Dimension` WHERE `Supplier Name`  REGEXP '[[:<:]]%s' LIMIT 20 ", $q
            );


            if ($result = $db->query($sql)) {
                foreach ($result as $row) {

                    if ($row['Supplier Name'] == $q) {
                        if ($row['Supplier Type'] == 'Archived') {

                            if (isset($candidates['S'.$row['Supplier Key']])) {
                                $candidates['S'.$row['Supplier Key']] += 400;

                            } else {
                                $candidates['S'.$row['Supplier Key']] = 400;

                            }

                        } else {

                            if (isset($candidates['S'.$row['Supplier Key']])) {
                                $candidates['S'.$row['Supplier Key']] += 800;

                            } else {
                                $candidates['S'.$row['Supplier Key']] = 800;

                            }


                        }
                    } else {

                        $len_name = strlen($row['Supplier Name']);
                        $len_q    = strlen($q);
                        $factor   = $len_q / $len_name;
                        if ($row['Supplier Type'] == 'Archived') {
                            if (isset($candidates['S'.$row['Supplier Key']])) {
                                $candidates['S'.$row['Supplier Key']] += 200 * $factor;

                            } else {
                                $candidates['S'.$row['Supplier Key']] = 200 * $factor;

                            }
                        } else {
                            if (isset($candidates['S'.$row['Supplier Key']])) {
                                $candidates['S'.$row['Supplier Key']] += 400 * $factor;

                            } else {
                                $candidates['S'.$row['Supplier Key']] = 400 * $factor;

                            }

                        }
                    }

                }
            } else {
                print_r($error_info = $db->errorInfo());
                print $sql;
                exit;
            }


            $sql = sprintf(
                "SELECT `Agent Key`,`Agent Code` FROM `Agent Dimension` WHERE `Agent Code` LIKE '%s%%' LIMIT 20 ", $q
            );


            if ($result = $db->query($sql)) {
                foreach ($result as $row) {

                    if ($row['Agent Code'] == $q) {
                        $candidates['A'.$row['Agent Key']] = 1000;
                    } else {

                        $len_name                          = strlen(
                            $row['Agent Code']
                        );
                        $len_q                             = strlen($q);
                        $factor                            = $len_q / $len_name;
                        $candidates['A'.$row['Agent Key']] = 500 * $factor;
                    }

                }
            } else {
                print_r($error_info = $db->errorInfo());
                print $sql;
                exit;
            }

            $sql = sprintf(
                "SELECT `Agent Key`,`Agent Name` FROM `Agent Dimension` WHERE `Agent Name`  REGEXP '[[:<:]]%s' LIMIT 20 ", $q
            );


            if ($result = $db->query($sql)) {
                foreach ($result as $row) {

                    if ($row['Agent Name'] == $q) {

                        if (isset($candidates['A'.$row['Agent Key']])) {
                            $candidates['A'.$row['Agent Key']] += 800;

                        } else {
                            $candidates['A'.$row['Agent Key']] = 800;

                        }

                    } else {

                        $len_name = strlen($row['Agent Name']);
                        $len_q    = strlen($q);
                        $factor   = $len_q / $len_name;

                        if (isset($candidates['A'.$row['Agent Key']])) {
                            $candidates['A'.$row['Agent Key']] += 400 * $factor;

                        } else {
                            $candidates['A'.$row['Agent Key']] = 400 * $factor;

                        }

                    }

                }
            } else {
                print_r($error_info = $db->errorInfo());
                print $sql;
                exit;
            }


            $sql = sprintf(
                "SELECT `Supplier Part Key`,`Supplier Part Reference` FROM `Supplier Part Dimension` WHERE `Supplier Part Reference` LIKE '%s%%' LIMIT 20 ", $q
            );


            if ($result = $db->query($sql)) {
                foreach ($result as $row) {

                    if ($row['Supplier Part Reference'] == $q) {
                        $candidates['P'.$row['Supplier Part Key']] = 1000;
                    } else {

                        $len_name                                  = strlen(
                            $row['Supplier Part Reference']
                        );
                        $len_q                                     = strlen($q);
                        $factor                                    = $len_q / $len_name;
                        $candidates['P'.$row['Supplier Part Key']] = 500 * $factor;
                    }

                }
            } else {
                print_r($error_info = $db->errorInfo());
                print $sql;
                exit;
            }


            $sql = sprintf(
                "SELECT `Supplier Part Key`,`Part Reference` FROM `Supplier Part Dimension`  LEFT JOIN   `Part Dimension`  ON (`Supplier Part Part SKU`=`Part SKU`) WHERE `Part Reference` LIKE '%s%%' LIMIT 20 ",
                $q
            );


            if ($result = $db->query($sql)) {
                foreach ($result as $row) {

                    if ($row['Part Reference'] == $q) {

                        if (isset($candidates['P'.$row['Supplier Part Key']])) {
                            $candidates['P'.$row['Supplier Part Key']] += 750;

                        } else {
                            $candidates['P'.$row['Supplier Part Key']] = 750;

                        }

                    } else {

                        $len_name = strlen($row['Part Reference']);
                        $len_q    = strlen($q);
                        $factor   = $len_q / $len_name;

                        if (isset($candidates['P'.$row['Supplier Part Key']])) {
                            $candidates['P'.$row['Supplier Part Key']] += 375 * $factor;

                        } else {
                            $candidates['P'.$row['Supplier Part Key']] = 375 * $factor;

                        }

                    }

                }
            } else {
                print_r($error_info = $db->errorInfo());
                print $sql;
                exit;
            }


            $sql = sprintf(
                "SELECT `Supplier Part Key`,`Part Unit Description` FROM `Supplier Part Dimension` LEFT JOIN   `Part Dimension`  ON (`Supplier Part Part SKU`=`Part SKU`)  WHERE `Part Unit Description`  REGEXP '[[:<:]]%s' LIMIT 100 ",
                $q
            );

            if ($result = $db->query($sql)) {
                foreach ($result as $row) {
                    if ($row['Part Unit Description'] == $q) {

                        if (isset($candidates['P'.$row['Supplier Part Key']])) {
                            $candidates['P'.$row['Supplier Part Key']] += 55;

                        } else {
                            $candidates['P'.$row['Supplier Part Key']] = 55;

                        }

                    } else {

                        $len_name = strlen($row['Part Unit Description']);
                        $len_q    = strlen($q);
                        $factor   = $len_q / $len_name;

                        if (isset($candidates['P'.$row['Supplier Part Key']])) {
                            $candidates['P'.$row['Supplier Part Key']] += 50 * $factor;

                        } else {
                            $candidates['P'.$row['Supplier Part Key']] = 50 * $factor;

                        }

                    }

                }
            } else {
                print_r($error_info = $db->errorInfo());
                print $sql;
                exit;
            }


            $sql = sprintf(
                "SELECT `Category Key`,`Category Code`,`Category Label` FROM `Category Dimension` WHERE `Category Scope`='Supplier'  AND `Category Code` LIKE '%s%%' LIMIT 20 ", $q
            );

            if ($result = $db->query($sql)) {
                foreach ($result as $row) {

                    if ($row['Category Code'] == $q) {

                        $candidates['C'.$row['Category Key']] = 1000;
                    } else {

                        $len_name                             = strlen(
                            $row['Category Code']
                        );
                        $len_q                                = strlen($q);
                        $factor                               = $len_q / $len_name;
                        $candidates['C'.$row['Category Key']] = 500 * $factor;
                    }

                }
            } else {
                print_r($error_info = $db->errorInfo());
                exit;
            }


            $sql = sprintf(
                "SELECT `Category Key`,`Category Code`,`Category Label` FROM `Category Dimension` WHERE `Category Scope`='Supplier'  AND `Category Label`  REGEXP '[[:<:]]%s' LIMIT 100 ", $q
            );

            if ($result = $db->query($sql)) {
                foreach ($result as $row) {
                    if ($row['Category Label'] == $q) {

                        if (isset($candidates['C'.$row['Category Key']])) {
                            $candidates['C'.$row['Category Key']] += 55;
                        } else {
                            $candidates['C'.$row['Category Key']] = 55;
                        }

                    } else {

                        $len_name = strlen($row['Category Label']);
                        $len_q    = strlen($q);
                        $factor   = $len_q / $len_name;

                        if (isset($candidates['C'.$row['Category Key']])) {
                            $candidates['C'.$row['Category Key']] += 50 * $factor;
                        } else {
                            $candidates['C'.$row['Category Key']] = 50 * $factor;
                        }

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

        $counter                    = 0;
        $supplier_parts_keys        = '';
        $supplier_keys              = '';
        $agent_keys                 = '';
        $category_keys              = '';
        $results                    = array();
        $number_supplier_parts_keys = 0;
        $number_supplier_keys       = 0;
        $number_agent_keys          = 0;
        $number_category_keys       = 0;

        foreach ($candidates as $_key => $val) {
            $counter++;

            if ($_key[0] == 'P') {
                $key = preg_replace('/^P/', '', $_key);
                $supplier_parts_keys .= ','.$key;
                $results[$_key] = '';
                $number_supplier_parts_keys++;

            } elseif ($_key[0] == 'S') {
                $key = preg_replace('/^S/', '', $_key);
                $supplier_keys .= ','.$key;
                $results[$_key] = '';
                $number_supplier_keys++;

            } elseif ($_key[0] == 'A') {
                $key = preg_replace('/^A/', '', $_key);
                $agent_keys .= ','.$key;
                $results[$_key] = '';
                $number_agent_keys++;

            } elseif ($_key[0] == 'C') {
                $key = preg_replace('/^C/', '', $_key);
                $category_keys .= ','.$key;
                $results[$_key] = '';
                $number_category_keys++;

            }

            if ($counter > $max_results) {
                break;
            }
        }
        $supplier_parts_keys = preg_replace('/^,/', '', $supplier_parts_keys);
        $supplier_keys       = preg_replace('/^,/', '', $supplier_keys);
        $agent_keys          = preg_replace('/^,/', '', $agent_keys);
        $category_keys       = preg_replace('/^,/', '', $category_keys);


        if ($number_supplier_parts_keys) {
            $sql = sprintf(
                "SELECT `Supplier Part Key`,`Supplier Part Supplier Key`,`Supplier Part Reference`,`Part Unit Description` FROM `Supplier Part Dimension` LEFT JOIN   `Part Dimension`  ON (`Supplier Part Part SKU`=`Part SKU`)   WHERE `Supplier Part Key` IN (%s)",
                $supplier_parts_keys
            );

            if ($result = $db->query($sql)) {
                foreach ($result as $row) {


                    $results['P'.$row['Supplier Part Key']] = array(
                        'label'   => '<i class="fa fa-stop fa-fw "></i> '.highlightkeyword(
                                sprintf('%s', $row['Supplier Part Reference']), $queries
                            ),
                        'details' => highlightkeyword(
                            $row['Part Unit Description'], $queries
                        ),
                        'view'    => sprintf(
                            'supplier/%d/part/%d', $row['Supplier Part Supplier Key'], $row['Supplier Part Key']
                        )


                    );

                }
            } else {
                print_r($error_info = $db->errorInfo());
                print $sql;
                exit;
            }
        }

        if ($number_supplier_keys) {

            $sql = sprintf(
                "SELECT `Supplier Key`,`Supplier Code`,`Supplier Name`,`Supplier Type` FROM `Supplier Dimension`  WHERE `Supplier Key` IN (%s)", $supplier_keys
            );

            if ($result = $db->query($sql)) {
                foreach ($result as $row) {

                    if ($row['Supplier Type'] == 'Archived') {
                        $icon = '<i class="fa fa-archive fa-fw discreet"></i> ';

                    } else {
                        $icon = '<i class="fa fa-ship fa-fw "></i> ';
                    }


                    $results['S'.$row['Supplier Key']] = array(
                        'label'   => $icon.highlightkeyword(
                                sprintf('%s', $row['Supplier Code']), $queries
                            ),
                        'details' => highlightkeyword(
                            $row['Supplier Name'], $queries
                        ),
                        'view'    => sprintf(
                            'supplier/%d', $row['Supplier Key']
                        )


                    );

                }
            } else {
                print_r($error_info = $db->errorInfo());
                print $sql;
                exit;
            }


        }

        if ($number_agent_keys) {

            $sql = sprintf(
                "SELECT `Agent Key`,`Agent Code`,`Agent Name` FROM `Agent Dimension`  WHERE `Agent Key` IN (%s)", $agent_keys
            );

            if ($result = $db->query($sql)) {
                foreach ($result as $row) {


                    $results['A'.$row['Agent Key']] = array(
                        'label'   => '<i class="fa fa-user-secret fa-fw "></i> '.highlightkeyword(
                                sprintf('%s', $row['Agent Code']), $queries
                            ),
                        'details' => highlightkeyword(
                            $row['Agent Name'], $queries
                        ),
                        'view'    => sprintf('agent/%d', $row['Agent Key'])


                    );

                }
            } else {
                print_r($error_info = $db->errorInfo());
                print $sql;
                exit;
            }


        }

        if ($number_category_keys) {
            $sql = sprintf(
                "SELECT `Category Code`,`Category Store Key`,`Category Key`,`Category Code`,`Category Label` FROM `Category Dimension` WHERE `Category Key` IN (%s)", $category_keys
            );

            if ($result = $db->query($sql)) {
                foreach ($result as $row) {


                    $icon
                        = '<i class="fa fa-sitemap fa-fw padding_right_5" aria-hidden="true" ></i> ';

                    $results['C'.$row['Category Key']] = array(
                        'label'   => $icon.highlightkeyword(
                                sprintf('%s', $row['Category Code']), $queries
                            ),
                        'details' => highlightkeyword(
                            $row['Category Label'], $queries
                        ),
                        'view'    => sprintf(
                            'suppliers/category/%d', $row['Category Key']
                        )


                    );
                }
            } else {
                print_r($error_info = $db->errorInfo());
                print $sql;
                exit;
            }
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


function search_inventory($db, $account, $memcache_ip, $data) {


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


    $memcache_fingerprint = $account->get('Account Code').'SEARCH_INVENTORY'.md5($queries);

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
                "SELECT `Part SKU`,`Part Reference`,`Part Unit Description`,`Part Status` FROM `Part Dimension` WHERE `Part Reference` LIKE '%s%%' LIMIT 20 ", $q
            );


            if ($result = $db->query($sql)) {
                foreach ($result as $row) {

                    if ($row['Part Reference'] == $q) {
                        if ($row['Part Status'] == 'In Use') {
                            $candidates['P'.$row['Part SKU']] = 1000;
                        } else {
                            $candidates['P'.$row['Part SKU']] = 800;
                        }
                    } else {

                        $len_name = strlen($row['Part Reference']);
                        $len_q    = strlen($q);
                        $factor   = $len_q / $len_name;
                        if ($row['Part Status'] == 'In Use') {
                            $candidates['P'.$row['Part SKU']] = 500 * $factor;
                        } else {
                            $candidates['P'.$row['Part SKU']] = 400 * $factor;
                        }
                    }

                }
            } else {
                print_r($error_info = $db->errorInfo());
                print $sql;
                exit;
            }


            $sql = sprintf(
                "SELECT `Part SKU`,`Part Reference`,`Part Unit Description`,`Part Status` FROM `Part Dimension` WHERE `Part Unit Description`  REGEXP '[[:<:]]%s' LIMIT 100 ", $q
            );

            if ($result = $db->query($sql)) {
                foreach ($result as $row) {
                    if ($row['Part Unit Description'] == $q) {
                        if ($row['Part Status'] == 'In Use') {

                            if (isset($candidates['P'.$row['Part SKU']])) {
                                $candidates['P'.$row['Part SKU']] += 55;

                            } else {
                                $candidates['P'.$row['Part SKU']] = 55;

                            }

                        } else {

                            if (isset($candidates['P'.$row['Part SKU']])) {
                                $candidates['P'.$row['Part SKU']] += 35;

                            } else {
                                $candidates['P'.$row['Part SKU']] = 35;

                            }

                        }
                    } else {

                        $len_name = strlen($row['Part Unit Description']);
                        $len_q    = strlen($q);
                        $factor   = $len_q / $len_name;
                        if ($row['Part Status'] == 'In Use') {
                            if (isset($candidates['P'.$row['Part SKU']])) {
                                $candidates['P'.$row['Part SKU']] += 50 * $factor;

                            } else {
                                $candidates['P'.$row['Part SKU']] = 50 * $factor;

                            }
                        } else {

                            if (isset($candidates['P'.$row['Part SKU']])) {
                                $candidates['P'.$row['Part SKU']] += 30 * $factor;

                            } else {
                                $candidates['P'.$row['Part SKU']] = 30 * $factor;

                            }

                        }
                    }

                }
            } else {
                print_r($error_info = $db->errorInfo());
                print $sql;
                exit;
            }


            $sql = sprintf(
                "SELECT `Category Key`,`Category Code`,`Category Label` FROM `Category Dimension` WHERE `Category Scope`='Part'  AND `Category Code` LIKE '%s%%' LIMIT 20 ", $q
            );

            if ($result = $db->query($sql)) {
                foreach ($result as $row) {

                    if ($row['Category Code'] == $q) {

                        $candidates['C'.$row['Category Key']] = 1000;
                    } else {

                        $len_name                             = strlen(
                            $row['Category Code']
                        );
                        $len_q                                = strlen($q);
                        $factor                               = $len_q / $len_name;
                        $candidates['C'.$row['Category Key']] = 500 * $factor;
                    }

                }
            } else {
                print_r($error_info = $db->errorInfo());
                exit;
            }


            $sql = sprintf(
                "SELECT `Category Key`,`Category Code`,`Category Label` FROM `Category Dimension` WHERE `Category Scope`='Part'  AND `Category Label`  REGEXP '[[:<:]]%s' LIMIT 100 ", $q
            );

            if ($result = $db->query($sql)) {
                foreach ($result as $row) {
                    if ($row['Category Label'] == $q) {

                        if (isset($candidates['C'.$row['Category Key']])) {
                            $candidates['C'.$row['Category Key']] += 55;
                        } else {
                            $candidates['C'.$row['Category Key']] = 55;
                        }

                    } else {

                        $len_name = strlen($row['Category Label']);
                        $len_q    = strlen($q);
                        $factor   = $len_q / $len_name;

                        if (isset($candidates['C'.$row['Category Key']])) {
                            $candidates['C'.$row['Category Key']] += 50 * $factor;
                        } else {
                            $candidates['C'.$row['Category Key']] = 50 * $factor;
                        }

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

        $counter       = 0;
        $part_keys     = '';
        $category_keys = '';


        $results = array();

        $number_parts_keys      = 0;
        $number_categories_keys = 0;


        foreach ($candidates as $_key => $val) {
            $counter++;

            if ($_key[0] == 'P') {
                $key = preg_replace('/^P/', '', $_key);
                $part_keys .= ','.$key;
                $results[$_key] = '';
                $number_parts_keys++;

            } elseif ($_key[0] == 'C') {
                $key = preg_replace('/^C/', '', $_key);
                $category_keys .= ','.$key;
                $results[$_key] = '';
                $number_categories_keys++;

            }

            if ($counter > $max_results) {
                break;
            }
        }
        $part_keys     = preg_replace('/^,/', '', $part_keys);
        $category_keys = preg_replace('/^,/', '', $category_keys);


        if ($number_parts_keys) {
            $sql = sprintf(
                "SELECT P.`Part SKU`,`Part Reference`,`Part Unit Description`,`Part Status` FROM `Part Dimension` P  WHERE P.`Part SKU` IN (%s)", $part_keys
            );

            if ($result = $db->query($sql)) {
                foreach ($result as $row) {

                    if ($row['Part Status'] == 'Not In Use') {
                        $status
                            = '<i class="fa fa-square-o fa-fw padding_right_5 very_discreet" aria-hidden="true"></i> ';

                    } elseif ($row['Part Status'] == 'Discontinuing') {
                        $status
                            = '<i class="fa fa-square fa-fw padding_right_5 very_discreet" aria-hidden="true"></i> ';

                    } else {
                        $status
                            = '<i class="fa fa-square fa-fw padding_right_5" aria-hidden="true"></i> ';
                    }

                    $results['P'.$row['Part SKU']] = array(
                        'label'   => $status.highlightkeyword(
                                sprintf('%s', $row['Part Reference']), $queries
                            ),
                        'details' => highlightkeyword(
                            $row['Part Unit Description'], $queries
                        ),
                        'view'    => sprintf('part/%d', $row['Part SKU'])


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


                    $icon
                        = '<i class="fa fa-sitemap fa-fw padding_right_5" aria-hidden="true" ></i> ';

                    $results['C'.$row['Category Key']] = array(
                        'label'   => $icon.highlightkeyword(
                                sprintf('%s', $row['Category Code']), $queries
                            ),
                        'details' => highlightkeyword(
                            $row['Category Label'], $queries
                        ),
                        'view'    => sprintf(
                            'inventory/category/%d', $row['Category Key']
                        )


                    );
                }
            } else {
                print_r($error_info = $db->errorInfo());
                print $sql;
                exit;
            }
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


function search_products($db, $account, $memcache_ip, $data) {


    $cache       = false;
    $max_results = 16;
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


    if ($data['scope'] == 'store') {
        if (in_array($data['scope_key'], $user->stores)) {
            $stores          = $data['scope_key'];
            $where_store     = sprintf(
                ' and `Product Store Key`=%d', $data['scope_key']
            );
            $where_cat_store = sprintf(
                ' and `Category Store Key`=%d', $data['scope_key']
            );

        } else {
            $where_store     = ' and false';
            $where_cat_store = ' and false';
        }
    } else {
        if (count($user->stores) == $account->get('Account Stores')) {
            $where_store     = '';
            $where_cat_store = '';
        } else {
            $where_store     = sprintf(
                ' and `Product Store Key` in (%s)', join(',', $user->stores)
            );
            $where_cat_store = sprintf(
                ' and `Category Store Key` in (%s)', join(',', $user->stores)
            );
        }

        $stores = join(',', $user->stores);
    }
    $memcache_fingerprint = $account->get('Account Code').'SEARCH_PROD'.$stores.md5($queries);

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

        if ($number_queries == 1) {
            $q = $queries;
            if (is_numeric($q)) {
                $sql = sprintf(
                    "select `Product ID`,`Product Code`,`Product Name` from `Product Dimension` where true $where_store and `Product ID`=%d", $q
                );


                if ($result = $db->query($sql)) {
                    if ($row = $result->fetch()) {
                        $candidates['P'.$row['Product ID']] = 2000;
                    }
                } else {
                    print_r($error_info = $db->errorInfo());
                    exit;
                }


            }


        }


        foreach ($query_array as $q) {


            $sql = sprintf(
                "select `Product ID`,`Product Code`,`Product Name`,`Product Status` from `Product Dimension` where true $where_store and `Product Code` like '%s%%' limit 20 ", $q
            );
            if ($result = $db->query($sql)) {
                foreach ($result as $row) {

                    if ($row['Product Code'] == $q) {
                        if ($row['Product Status'] == 'Discontinued') {

                            if (isset($candidates['P'.$row['Product ID']])) {
                                $candidates['P'.$row['Product ID']] += 550;
                            } else {
                                $candidates['P'.$row['Product ID']] = 550;
                            }


                        } else {

                            if (isset($candidates['P'.$row['Product ID']])) {
                                $candidates['P'.$row['Product ID']] += 1000;
                            } else {
                                $candidates['P'.$row['Product ID']] = 1000;
                            }

                        }
                    } else {

                        $len_name = strlen($row['Product Code']);
                        $len_q    = strlen($q);
                        $factor   = $len_q / $len_name;
                        if ($row['Product Status'] == 'Discontinued') {

                            if (isset($candidates['P'.$row['Product ID']])) {
                                $candidates['P'.$row['Product ID']] += 270 * $factor;
                            } else {
                                $candidates['P'.$row['Product ID']] = 270 * $factor;
                            }

                        } else {


                            if (isset($candidates['P'.$row['Product ID']])) {
                                $candidates['P'.$row['Product ID']] += 500 * $factor;
                            } else {
                                $candidates['P'.$row['Product ID']] = 500 * $factor;
                            }
                        }

                    }

                }
            } else {
                print_r($error_info = $db->errorInfo());
                exit;
            }

            $sql = sprintf(
                "select `Product ID`,`Product Code`,`Product Name` from `Product Dimension` where true $where_store and `Product Name`  REGEXP '[[:<:]]%s' limit 100 ", $q
            );

            if ($result = $db->query($sql)) {
                foreach ($result as $row) {
                    if ($row['Product Name'] == $q) {

                        if (isset($candidates['P'.$row['Product ID']])) {
                            $candidates['P'.$row['Product ID']] += 55;
                        } else {
                            $candidates['P'.$row['Product ID']] = 55;
                        }

                    } else {

                        $len_name = strlen($row['Product Name']);
                        $len_q    = strlen($q);
                        $factor   = $len_q / $len_name;

                        if (isset($candidates['P'.$row['Product ID']])) {
                            $candidates['P'.$row['Product ID']] += 50 * $factor;
                        } else {
                            $candidates['P'.$row['Product ID']] = 50 * $factor;
                        }

                    }

                }
            } else {
                print_r($error_info = $db->errorInfo());
                exit;
            }

            $sql = sprintf(
                "select `Category Key`,`Category Code`,`Category Label` from `Category Dimension` where `Category Scope`='Product'   $where_cat_store and `Category Code` like '%s%%' limit 20 ", $q
            );

            if ($result = $db->query($sql)) {
                foreach ($result as $row) {

                    if ($row['Category Code'] == $q) {

                        $candidates['C'.$row['Category Key']] = 1000;
                    } else {

                        $len_name                             = strlen(
                            $row['Category Code']
                        );
                        $len_q                                = strlen($q);
                        $factor                               = $len_q / $len_name;
                        $candidates['C'.$row['Category Key']] = 500 * $factor;
                    }

                }
            } else {
                print_r($error_info = $db->errorInfo());
                exit;
            }


            $sql = sprintf(
                "select `Category Key`,`Category Code`,`Category Label` from `Category Dimension` where `Category Scope`='Product'   $where_cat_store and  `Category Label`  REGEXP '[[:<:]]%s' limit 100 ",
                $q
            );

            if ($result = $db->query($sql)) {
                foreach ($result as $row) {
                    if ($row['Category Label'] == $q) {

                        if (isset($candidates['C'.$row['Category Key']])) {
                            $candidates['C'.$row['Category Key']] += 55;
                        } else {
                            $candidates['C'.$row['Category Key']] = 55;
                        }

                    } else {

                        $len_name = strlen($row['Category Label']);
                        $len_q    = strlen($q);
                        $factor   = $len_q / $len_name;

                        if (isset($candidates['C'.$row['Category Key']])) {
                            $candidates['C'.$row['Category Key']] += 50 * $factor;
                        } else {
                            $candidates['C'.$row['Category Key']] = 50 * $factor;
                        }

                    }

                }
            } else {
                print_r($error_info = $db->errorInfo());
                exit;
            }


        }


        // print_r($candidates);

        arsort($candidates);

        //print_r($candidates);

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

        $counter       = 0;
        $product_keys  = '';
        $category_keys = '';

        $results                = array();
        $number_products_keys   = 0;
        $number_categories_keys = 0;

        foreach ($candidates as $_key => $val) {
            $counter++;

            if ($_key[0] == 'P') {
                $key = preg_replace('/^P/', '', $_key);
                $product_keys .= ','.$key;
                $results[$_key] = '';
                $number_products_keys++;

            } elseif ($_key[0] == 'C') {
                $key = preg_replace('/^C/', '', $_key);
                $category_keys .= ','.$key;
                $results[$_key] = '';
                $number_categories_keys++;

            }

            if ($counter > $max_results) {
                break;
            }
        }
        $product_keys  = preg_replace('/^,/', '', $product_keys);
        $category_keys = preg_replace('/^,/', '', $category_keys);


        if ($number_products_keys) {
            $sql = sprintf(
                "SELECT `Product Status`,`Store Code`,`Store Key`,`Product ID`,`Product Code`,`Product Name` FROM `Product Dimension` LEFT JOIN `Store Dimension` S ON (`Product Store Key`=S.`Store Key`) WHERE `Product ID` IN (%s)",
                $product_keys
            );

            if ($result = $db->query($sql)) {
                foreach ($result as $row) {


                    if ($row['Product Status'] == 'Discontinued') {
                        $icon
                            = '<i class="fa fa-cube fa-fw padding_right_5 error" aria-hidden="true" ></i> ';

                    } elseif ($row['Product Status'] == 'Discontinued') {
                        $icon
                            = '<i class="fa fa-cube fa-fw padding_right_5 warning" aria-hidden="true" ></i> ';

                    } else {
                        $icon
                            = '<i class="fa fa-cube fa-fw padding_right_5" aria-hidden="true" ></i> ';
                    }

                    $results['P'.$row['Product ID']] = array(
                        'store'   => $row['Store Code'],
                        'label'   => $icon.highlightkeyword(
                                sprintf('%s', $row['Product Code']), $queries
                            ),
                        'details' => highlightkeyword(
                            $row['Product Name'], $queries
                        ),
                        'view'    => sprintf(
                            'products/%d/%d', $row['Store Key'], $row['Product ID']
                        )


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
                "SELECT `Category Code`,`Category Store Key`,`Category Key`,`Category Code`,`Category Label`,`Store Code` FROM `Category Dimension` LEFT JOIN `Store Dimension` S ON (`Category Store Key`=S.`Store Key`) WHERE `Category Key` IN (%s)",
                $category_keys
            );

            if ($result = $db->query($sql)) {
                foreach ($result as $row) {


                    $icon
                        = '<i class="fa fa-sitemap fa-fw padding_right_5" aria-hidden="true" ></i> ';

                    $results['C'.$row['Category Key']] = array(
                        'store'   => $row['Store Code'],
                        'label'   => $icon.highlightkeyword(
                                sprintf('%s', $row['Category Code']), $queries
                            ),
                        'details' => highlightkeyword(
                            $row['Category Label'], $queries
                        ),
                        'view'    => sprintf(
                            'products/%d/category/%d', $row['Category Store Key'], $row['Category Key']
                        )


                    );
                }
            } else {
                print_r($error_info = $db->errorInfo());
                print $sql;
                exit;
            }
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
        'q'              => $queries,
        'show_stores'    => ($data['scope'] == 'stores' ? true : false)
    );

    echo json_encode($response);

}


function search_customers($db, $account, $memcache_ip, $data) {


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

    if ($data['scope'] == 'store') {
        if (in_array($data['scope_key'], $user->stores)) {
            $stores      = $data['scope_key'];
            $where_store = sprintf(
                ' and `Customer Store Key`=%d', $data['scope_key']
            );
        } else {
            $where_store = ' and false';
        }
    } else {
        if (count($user->stores) == $account->data['Stores']) {
            $where_store = '';
        } else {
            $where_store = sprintf(
                ' and `Customer Store Key` in (%s)', join(',', $user->stores)
            );
        }

        $stores = join(',', $user->stores);
    }
    $memcache_fingerprint = $account->get('Account Code').'SEARCH_CUST'.$stores.md5($queries);

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


    if (!$results_data or $cache) {


        $candidates = array();

        $q = $queries;

        if (is_numeric($q)) {
            $sql = sprintf(
                "select `Customer Key`,`Customer Name` from `Customer Dimension` where true $where_store and `Customer Key`=%d", $q
            );

            if ($result = $db->query($sql)) {
                if ($row = $result->fetch()) {
                    $candidates[$row['Customer Key']] = 2000;

                }
            } else {
                print_r($error_info = $db->errorInfo());
                exit;
            }


        }
        $q_just_numbers = preg_replace('/[^\d]/', '', $q);
        if (strlen($q_just_numbers) > 4 and strlen($q_just_numbers) <= 6) {

            $sql = sprintf(
                "select `Customer Key`,`Customer Name` from `Customer Dimension` where true $where_store and `Customer Main Plain Telephone` like '%s%%'", $q_just_numbers
            );

            if ($result = $db->query($sql)) {
                foreach ($result as $row) {
                    $candidates[$row['Customer Key']] = 100;
                }
            } else {
                print_r($error_info = $db->errorInfo());
                exit;
            }


            $sql = sprintf(
                "select `Customer Key`,`Customer Name` from `Customer Dimension` where true $where_store and `Customer Main Plain Mobile` like '%s%%'", $q_just_numbers
            );

            if ($result = $db->query($sql)) {
                foreach ($result as $row) {
                    $candidates[$row['Customer Key']] = 100;
                }
            } else {
                print_r($error_info = $db->errorInfo());
                exit;
            }


        }
        if (strlen($q_just_numbers) > 6) {

            $sql = sprintf(
                "select `Customer Key`,`Customer Name` from `Customer Dimension` where true $where_store and `Customer Main Plain Telephone` like '%%%s%%'", $q_just_numbers
            );

            if ($result = $db->query($sql)) {
                foreach ($result as $row) {
                    $candidates[$row['Customer Key']] = 100;
                }
            } else {
                print_r($error_info = $db->errorInfo());
                exit;
            }


            $sql = sprintf(
                "select `Customer Key`,`Customer Name` from `Customer Dimension` where true $where_store and `Customer Main Plain Mobile` like '%%%s%%'", $q_just_numbers
            );

            if ($result = $db->query($sql)) {
                if ($row = $result->fetch()) {
                    $candidates[$row['Customer Key']] = 100;
                }
            } else {
                print_r($error_info = $db->errorInfo());
                exit;
            }


        }

        $sql = sprintf(
            "select `Customer Key`,`Customer Tax Number` from `Customer Dimension` where true $where_store and `Customer Tax Number` like '%s%%' limit 10 ", $q
        );

        if ($result = $db->query($sql)) {
            foreach ($result as $row) {

                if ($row['Customer Tax Number'] == $q) {
                    $candidates[$row['Customer Key']] = 30;
                } else {

                    $len_name                         = strlen(
                        $row['Customer Tax Number']
                    );
                    $len_q                            = strlen($q);
                    $factor                           = $len_q / $len_name;
                    $candidates[$row['Customer Key']] = 20 * $factor;
                }

            }
        } else {
            print_r($error_info = $db->errorInfo());
            exit;
        }


        $sql = sprintf(
            "select `Subject Key`,`Email` from `Email Bridge` EB  left join `Email Dimension` E on (EB.`Email Key`=E.`Email Key`) left join `Customer Dimension` CD on (CD.`Customer Key`=`Subject Key`) where true $where_store and `Subject Type`='Customer' and `Email`  like '%s%%' limit 100 ",
            $q
        );


        if ($result = $db->query($sql)) {
            foreach ($result as $row) {

                if ($row['Email'] == $q) {
                    $candidates[$row['Subject Key']] = 120;
                } else {

                    $len_name                        = strlen($row['Email']);
                    $len_q                           = strlen($q);
                    $factor                          = $len_q / $len_name;
                    $candidates[$row['Subject Key']] = 100 * $factor;
                }

            }
        } else {
            print_r($error_info = $db->errorInfo());
            exit;
        }


        $q_postal_code = preg_replace('/[^a-z^A-Z^\d]/', '', $q);
        if ($q_postal_code != '') {
            $sql = sprintf(
                "select `Customer Key`,`Customer Main Plain Postal Code` from `Customer Dimension`where true $where_store and `Customer Main Plain Postal Code`!='' and `Customer Main Plain Postal Code` like '%s%%' limit 150",
                addslashes($q_postal_code)
            );

            if ($result = $db->query($sql)) {
                foreach ($result as $row) {

                    if ($row['Customer Main Plain Postal Code'] == $q_postal_code) {
                        $candidates[$row['Customer Key']] = 50;
                    } else {
                        $len_name                         = strlen(
                            $row['Customer Main Plain Postal Code']
                        );
                        $len_q                            = strlen(
                            $q_postal_code
                        );
                        $factor                           = $len_q / $len_name;
                        $candidates[$row['Customer Key']] = 20 * $factor;
                    }

                }
            } else {
                print_r($error_info = $db->errorInfo());
                exit;
            }

        }


        $sql = sprintf(
            "select `Subject Key`,`Contact Name`,`Contact Surname` from `Contact Bridge` EB  left join `Contact Dimension` E on (EB.`Contact Key`=E.`Contact Key`) left join `Customer Dimension` CD on (CD.`Customer Key`=`Subject Key`) where true $where_store and `Subject Type`='Customer' and `Contact Name` like '%s%%' limit 20",
            $q
        );

        if ($result = $db->query($sql)) {
            foreach ($result as $row) {

                if ($row['Contact Name'] == $q) {
                    $candidates[$row['Subject Key']] = 120;
                } else {
                    $len_name                        = $row['Contact Name'];
                    $len_q                           = strlen($q);
                    $factor                          = $len_name / $len_q;
                    $candidates[$row['Subject Key']] = 100 * $factor;
                }

            }
        } else {
            print_r($error_info = $db->errorInfo());
            exit;
        }


        $sql = sprintf(
            "select `Subject Key`,`Contact Name`,`Contact Surname` from `Contact Bridge` EB  left join `Contact Dimension` E on (EB.`Contact Key`=E.`Contact Key`) left join `Customer Dimension` CD on (CD.`Customer Key`=`Subject Key`) where true $where_store and `Subject Type`='Customer' and  `Contact Surname`  like '%s%%'  limit 20",
            $q
        );

        if ($result = $db->query($sql)) {
            foreach ($result as $row) {

                if ($row['Contact Surname'] == $q) {
                    $candidates[$row['Subject Key']] = 120;
                } else {
                    $len_name                        = $row['Contact Surname'];
                    $len_q                           = strlen($q);
                    $factor                          = $len_name / $len_q;
                    $candidates[$row['Subject Key']] = 100 * $factor;
                }

            }
        } else {
            print_r($error_info = $db->errorInfo());
            exit;
        }


        $sql = sprintf(
            "select `Customer Key`,`Customer Name` from `Customer Dimension` where true $where_store and `Customer Name` like '%s%%' limit 50", $q
        );

        if ($result = $db->query($sql)) {
            foreach ($result as $row) {

                if ($row['Customer Name'] == $q) {
                    $candidates[$row['Customer Key']] = 55;
                } else {

                    $len_name                         = strlen(
                        $row['Customer Name']
                    );
                    $len_q                            = strlen($q);
                    $factor                           = $len_q / $len_name;
                    $candidates[$row['Customer Key']] = 50 * $factor;
                }

            }
        } else {
            print_r($error_info = $db->errorInfo());
            exit;
        }


        $sql = sprintf(
            "select `Customer Key`,`Customer Name` from `Customer Dimension` where true $where_store and `Customer Name`  REGEXP '[[:<:]]%s' limit 100 ", $q
        );

        if ($result = $db->query($sql)) {
            foreach ($result as $row) {

                if ($row['Customer Name'] == $q) {
                    $candidates[$row['Customer Key']] = 55;
                } else {

                    $len_name                         = strlen(
                        $row['Customer Name']
                    );
                    $len_q                            = strlen($q);
                    $factor                           = $len_q / $len_name;
                    $candidates[$row['Customer Key']] = 50 * $factor;
                }

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

        $counter       = 0;
        $customer_keys = '';
        $results       = array();

        foreach ($candidates as $key => $val) {
            $counter++;
            $customer_keys .= ','.$key;
            $results[$key] = '';
            if ($counter > $max_results) {
                break;
            }
        }
        $customer_keys = preg_replace('/^,/', '', $customer_keys);

        $sql = sprintf(
            "SELECT `Store Code`,`Customer Store Key`,`Customer Main Email Key`, `Customer Main XHTML Telephone`,`Customer Main Telephone Key`,`Customer Main Postal Code`,`Customer Key`,`Customer Main Contact Name`,`Customer Name`,`Customer Type`,`Customer Main Plain Email`,`Customer Main Location`,`Customer Tax Number` FROM `Customer Dimension` LEFT JOIN `Store Dimension` ON (`Customer Store Key`=`Store Key`) WHERE `Customer Key` IN (%s)",
            $customer_keys
        );

        if ($result = $db->query($sql)) {
            foreach ($result as $row) {


                $name = $row['Customer Name'];
                if ($row['Customer Type'] == 'Company' and $row['Customer Main Contact Name'] != '') {
                    $name .= ', '.$row['Customer Main Contact Name'];
                }
                /*
			if ($row['Customer Tax Number']!='') {
				$name.='<br/>'.$row['Customer Tax Number'];
			}
			if ($row['Customer Type']=='Company') {
				$name.= '<br/>'.$row['Customer Main Contact Name'];
			}

			$address=$row['Customer Main Plain Email'];
			if ($row['Customer Main Telephone Key'])$address.='<br/>T: '.$row['Customer Main XHTML Telephone'];
			$address.='<br/>'.$row['Customer Main Location'];
			if ($row['Customer Main Postal Code'])$address.=', '.$row['Customer Main Postal Code'];
			$address=preg_replace('/^\<br\/\>/', '', $address);
			*/
                $results[$row['Customer Key']] = array(
                    'store'   => $row['Store Code'],
                    'label'   => highlightkeyword(
                        sprintf('%06d', $row['Customer Key']), $queries
                    ),
                    'details' => highlightkeyword($name, $queries),
                    'view'    => sprintf(
                        'customers/%d/%d', $row['Customer Store Key'], $row['Customer Key']
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
        'q'              => $q
    );

    echo json_encode($response);

}


function search_orders($db, $account, $memcache_ip, $data) {

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

    if ($data['scope'] == 'store') {
        if (in_array($data['scope_key'], $user->stores)) {
            $stores      = $data['scope_key'];
            $where_store = sprintf(
                ' and `Order Store Key`=%d', $data['scope_key']
            );
        } else {
            $where_store = ' and false';
        }
    } else {
        if (count($user->stores) == $account->get('Account Stores')) {
            $where_store = '';
        } else {
            $where_store = sprintf(
                ' and `Order Store Key` in (%s)', join(',', $user->stores)
            );
        }

        $stores = join(',', $user->stores);
    }
    $memcache_fingerprint = $account->get('Account Code').'SEARCH_ORDER'.$stores.md5($queries);

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


    if (!$results_data or $cache) {


        $candidates = array();

        $q = $queries;


        $sql = sprintf(
            "select `Order Key`,`Order Public ID` from `Order Dimension` where true $where_store and `Order Public ID` like '%s%%'  order by `Order Key` desc limit 10 ", $q
        );

        if ($result = $db->query($sql)) {
            foreach ($result as $row) {

                if ($row['Order Public ID'] == $q) {
                    $candidates[$row['Order Key']] = 30;
                } else {

                    $len_name                      = strlen(
                        $row['Order Public ID']
                    );
                    $len_q                         = strlen($q);
                    $factor                        = $len_q / $len_name;
                    $candidates[$row['Order Key']] = 20 * $factor;
                }

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

        $counter    = 0;
        $order_keys = '';
        $results    = array();

        foreach ($candidates as $key => $val) {
            $counter++;
            $order_keys .= ','.$key;
            $results[$key] = '';
            if ($counter > $max_results) {
                break;
            }
        }
        $order_keys = preg_replace('/^,/', '', $order_keys);

        $sql = sprintf(
            "SELECT `Order Key`,`Store Code`,`Order Store Key`,`Order Public ID`,`Order Current Dispatch State`,`Order Customer Name` FROM `Order Dimension` LEFT JOIN `Store Dimension` ON (`Order Store Key`=`Store Key`) WHERE `Order Key` IN (%s)",
            $order_keys
        );

        if ($result = $db->query($sql)) {
            foreach ($result as $row) {


                switch ($row['Order Current Dispatch State']) {
                    case 'In Process':
                        $details = _('In Process');
                        break;
                    case 'In Process by Customer':
                        $details = _('In Process by Customer');
                        break;
                    case 'Submitted by Customer':
                        $details = _('Submitted by Customer');
                        break;
                    case 'Ready to Pick':
                        $details = _('Ready to Pick');
                        break;
                    case 'Picking & Packing':
                        $details = _('Picking & Packing');
                        break;
                    case 'Packed Done':
                        $details = _('Packed & Checked');
                        break;
                    case 'Ready to Ship':
                        $details = _('Ready to Ship');
                        break;
                    case 'Dispatched':
                        $details = _('Dispatched');
                        break;
                    case 'Unknown':
                        $details = _('Unknown');
                        break;
                    case 'Packing':
                        $details = _('Packing');
                        break;
                    case 'Cancelled':
                        $details = _('Cancelled');
                        break;
                    case 'Suspended':
                        $details = _('Suspended');
                        break;

                    default:
                        $details = $row['Order Current Dispatch State'];
                        break;
                }

                if ($data['scope'] != 'store') {
                    $details = '<span style="float:left;min-width:40px">'.$row['Store Code'].'</span> <span style="float:left;min-width:90px">'.$details.'</span>';
                }

                $details .= ' <span style="">'.$row['Order Customer Name'].'</span>';

                $results[$row['Order Key']] = array(
                    'store'   => $row['Store Code'],
                    'label'   => highlightkeyword(
                        sprintf('%s', $row['Order Public ID']), $queries
                    ),
                    'details' => highlightkeyword($details, $queries),
                    'view'    => sprintf(
                        'orders/%d/%d', $row['Order Store Key'], $row['Order Key']
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
        'q'              => $q
    );

    echo json_encode($response);

}


function search_delivery_notes($db, $account, $memcache_ip, $data) {

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


    if ($data['scope'] == 'store') {
        if (in_array($data['scope_key'], $user->stores)) {
            $stores      = $data['scope_key'];
            $where_store = sprintf(
                ' and `Delivery Note Store Key`=%d', $data['scope_key']
            );
        } else {
            $where_store = ' and false';
        }
    } else {
        if (count($user->stores) == $account->get('Account Stores')) {
            $where_store = '';
        } else {
            $where_store = sprintf(
                ' and `Delivery Note Store Key` in (%s)', join(',', $user->stores)
            );
        }

        $stores = join(',', $user->stores);
    }
    $memcache_fingerprint = $account->get('Account Code').'SEARCH_DN'.$stores.md5($queries);

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


    if (!$results_data or $cache) {


        $candidates = array();

        $q = $queries;


        $sql = sprintf(
            "select `Delivery Note Key`,`Delivery Note ID` from `Delivery Note Dimension` where true $where_store and `Delivery Note ID` like '%s%%'  order by `Delivery Note Key` desc limit 10 ", $q
        );

        if ($result = $db->query($sql)) {
            foreach ($result as $row) {

                if ($row['Delivery Note ID'] == $q) {
                    $candidates[$row['Delivery Note Key']] = 30;
                } else {

                    $len_name                              = strlen(
                        $row['Delivery Note ID']
                    );
                    $len_q                                 = strlen($q);
                    $factor                                = $len_q / $len_name;
                    $candidates[$row['Delivery Note Key']] = 20 * $factor;
                }

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

        $counter            = 0;
        $delivery_note_keys = '';
        $results            = array();

        foreach ($candidates as $key => $val) {
            $counter++;
            $delivery_note_keys .= ','.$key;
            $results[$key] = '';
            if ($counter > $max_results) {
                break;
            }
        }
        $delivery_note_keys = preg_replace('/^,/', '', $delivery_note_keys);

        $sql = sprintf(
            "SELECT `Delivery Note Key`,`Store Code`,`Delivery Note Store Key`,`Delivery Note ID`,`Delivery Note State` FROM `Delivery Note Dimension` LEFT JOIN `Store Dimension` ON (`Delivery Note Store Key`=`Store Key`) WHERE `Delivery Note Key` IN (%s)",
            $delivery_note_keys
        );

        if ($result = $db->query($sql)) {
            foreach ($result as $row) {


                switch ($row['Delivery Note State']) {

                    case 'Picker & Packer Assigned':
                        $details = _('Picker & packer assigned');
                        break;
                    case 'Picking & Packing':
                        $details = _('Picking & packing');
                        break;
                    case 'Packer Assigned':
                        $details = _('Packer assigned');
                        break;
                    case 'Ready to be Picked':
                        $details = _('Ready to be picked');
                        break;
                    case 'Picker Assigned':
                        $details = _('Picker assigned');
                        break;
                    case 'Picking':
                        $details = _('Picking');
                        break;
                    case 'Picked':
                        $details = _('Picked');
                        break;
                    case 'Packing':
                        $details = _('Packing');
                        break;
                    case 'Packed':
                        $details = _('Packed');
                        break;
                    case 'Approved':
                        $details = _('Approved');
                        break;
                    case 'Dispatched':
                        $details = _('Dispatched');
                        break;
                    case 'Cancelled':
                        $details = _('Cancelled');
                        break;
                    case 'Cancelled to Restock':
                        $details = _('Cancelled to restock');
                        break;
                    case 'Packed Done':
                        $details = _('Packed done');
                        break;
                    default:
                        $details = $row['Delivery Note State'];
                        break;
                }

                if ($data['scope'] != 'store') {
                    $details = '<span style="float:left;min-width:40px">'.$row['Store Code'].'</span> '.$details;
                }

                //$details.='<span class="padding_left_20">'.$row['Delivery Note Customer Name'].'</span>';

                $results[$row['Delivery Note Key']] = array(
                    'store'   => $row['Store Code'],
                    'label'   => highlightkeyword(
                        sprintf('%s', $row['Delivery Note ID']), $queries
                    ),
                    'details' => highlightkeyword($details, $queries),
                    'view'    => sprintf(
                        'delivery_notes/%d/%d', $row['Delivery Note Store Key'], $row['Delivery Note Key']
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
        'q'              => $q
    );

    echo json_encode($response);

}


function search_invoices($db, $account, $memcache_ip, $data) {

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

    if ($data['scope'] == 'store') {
        if (in_array($data['scope_key'], $user->stores)) {
            $stores      = $data['scope_key'];
            $where_store = sprintf(
                ' and `Invoice Store Key`=%d', $data['scope_key']
            );
        } else {
            $where_store = ' and false';
        }
    } else {
        if (count($user->stores) == $account->get('Account Stores')) {
            $where_store = '';
        } else {
            $where_store = sprintf(
                ' and `Invoice Store Key` in (%s)', join(',', $user->stores)
            );
        }

        $stores = join(',', $user->stores);
    }
    $memcache_fingerprint = $account->get('Account Code').'SEARCH_INVOICE'.$stores.md5($queries);

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


    if (!$results_data or $cache) {


        $candidates = array();

        $q = $queries;


        $sql = sprintf(
            "select `Invoice Key`,`Invoice Public ID` from `Invoice Dimension` where true $where_store and `Invoice Public ID` like '%s%%'  order by `Invoice Key` desc limit 10 ", $q
        );

        if ($result = $db->query($sql)) {
            foreach ($result as $row) {

                if ($row['Invoice Public ID'] == $q) {
                    $candidates[$row['Invoice Key']] = 30;
                } else {

                    $len_name                        = strlen(
                        $row['Invoice Public ID']
                    );
                    $len_q                           = strlen($q);
                    $factor                          = $len_q / $len_name;
                    $candidates[$row['Invoice Key']] = 20 * $factor;
                }

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

        $counter      = 0;
        $invoice_keys = '';
        $results      = array();

        foreach ($candidates as $key => $val) {
            $counter++;
            $invoice_keys .= ','.$key;
            $results[$key] = '';
            if ($counter > $max_results) {
                break;
            }
        }
        $invoice_keys = preg_replace('/^,/', '', $invoice_keys);

        $sql = sprintf(
            "SELECT `Invoice Key`,`Store Code`,`Invoice Store Key`,`Invoice Public ID`,`Invoice Paid`,`Invoice Total Amount`,`Invoice Currency`,`Invoice Customer Name` FROM `Invoice Dimension` LEFT JOIN `Store Dimension` ON (`Invoice Store Key`=`Store Key`) WHERE `Invoice Key` IN (%s)",
            $invoice_keys
        );
        if ($result = $db->query($sql)) {
            foreach ($result as $row) {


                switch ($row['Invoice Paid']) {
                    case 'Yes':
                        $details = _('Paid');
                        break;
                    case 'No':
                        $details = _('Not paid');
                        break;
                    case 'Partially':
                        $details = _('Partially paid');
                        break;
                    default:
                        $details = $row['Invoice Paid'];
                        break;
                }

                if ($data['scope'] != 'store') {
                    $details = '<span style="float:left;min-width:40px">'.$row['Store Code'].'</span> '.$details;
                }

                $details .= ' <span style="padding-left:20px">'.$row['Invoice Customer Name'].'</span>';

                $results[$row['Invoice Key']] = array(
                    'store'   => $row['Store Code'],
                    'label'   => highlightkeyword(
                        sprintf('%s', $row['Invoice Public ID']), $queries
                    ),
                    'details' => highlightkeyword($details, $queries),
                    'view'    => sprintf(
                        'invoices/%d/%d', $row['Invoice Store Key'], $row['Invoice Key']
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
        'q'              => $q
    );

    echo json_encode($response);

}


function search_hr($db, $account, $memcache_ip, $data) {


    $cache       = false;
    $max_results = 10;
    $user        = $data['user'];
    $queries     = _trim($data['query']);

    if ($queries == '') {
        $response = array(
            'state'   => 200,
            'results' => 0,
            'data'    => ''
        );
        echo json_encode($response);

        return;
    }


    $memcache_fingerprint = $account->get('Account Code').'SEARCH_HR'.md5(
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


    if (!$results_data or $cache) {


        $candidates = array();

        // print_r(preg_split('/\s+/', $queries));

        foreach (preg_split('/\s+/', $queries) as $q) {


            $sql = sprintf(
                "SELECT `Staff Key` FROM `Staff Dimension` WHERE   `Staff ID`=%s", prepare_mysql($q)
            );

            if ($result = $db->query($sql)) {

                if ($row = $result->fetch()) {
                    $candidates['S '.$row['Staff Key']] = 2000;
                }
            } else {
                print_r($error_info = $db->errorInfo());
                exit;

            }


            $sql = sprintf(
                "SELECT `Staff Key`,`Staff Alias` FROM `Staff Dimension` WHERE `Staff Currently Working`='Yes' AND   `Staff Alias` LIKE '%s%%' LIMIT 20 ", $q
            );


            if ($result = $db->query($sql)) {

                foreach ($result as $row) {
                    if ($row['Staff Alias'] == $q) {
                        $candidates['S '.$row['Staff Key']] = 70;
                    } else {

                        $len_name                           = strlen(
                            $row['Staff Alias']
                        );
                        $len_q                              = strlen($q);
                        $factor                             = $len_q / $len_name;
                        $candidates['S '.$row['Staff Key']] = 60 * $factor;
                    }
                }
            } else {
                print_r($error_info = $db->errorInfo());
                exit;
            }


            $sql = sprintf(
                "SELECT `Staff Key`,`Staff Name` FROM `Staff Dimension` WHERE `Staff Currently Working`='Yes' AND  `Staff Name`  REGEXP '[[:<:]]%s' LIMIT 100 ", $q
            );


            if ($result = $db->query($sql)) {

                foreach ($result as $row) {
                    if ($row['Staff Name'] == $q) {
                        $candidates['S '.$row['Staff Key']] = 55;
                    } else {

                        $len_name                           = strlen(
                            $row['Staff Name']
                        );
                        $len_q                              = strlen($q);
                        $factor                             = $len_q / $len_name;
                        $candidates['S '.$row['Staff Key']] = 60 * $factor;
                    }
                }
            } else {
                print_r($error_info = $db->errorInfo());
                exit('b');
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


        $counter = 0;
        $results = array();


        $staff_keys = '';


        foreach ($candidates as $key => $val) {
            $_key = preg_split('/ /', $key);
            if ($_key[0] == 'S') {

                $sql = sprintf(
                    "SELECT `Staff Key`,`Staff ID`,`Staff Alias`,`Staff Name` FROM `Staff Dimension` WHERE  `Staff Key`=%d", $_key[1]
                );
                if ($result = $db->query($sql)) {
                    if ($row = $result->fetch()) {
                        $results[$row['Staff Key']] = array(
                            'label'   => highlightkeyword(
                                $row['Staff Alias'], $queries
                            ),
                            'details' => highlightkeyword(
                                $row['Staff Name'], $queries
                            ),
                            'view'    => sprintf(
                                'employee/%d', $row['Staff Key']
                            ),
                            'score'   => $val
                        );
                    }
                } else {
                    print $sql;
                    print_r($error_info = $db->errorInfo());
                    exit('a');
                }

            }

            $counter++;

            if ($counter > $max_results) {
                break;
            }
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


function search_locations($db, $account, $memcache_ip, $data) {


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

    if ($data['scope'] == 'warehouse') {
        if (in_array($data['scope_key'], $user->stores)) {
            $warehouses      = $data['scope_key'];
            $where_warehouse = sprintf(
                ' and `Location Warehouse Key`=%d', $data['scope_key']
            );
        } else {
            $where_warehouse = ' and false';
        }
    } else {
        if (count($user->stores) == $account->data['Warehouses']) {
            $where_warehouse = '';
        } else {
            $where_warehouse = sprintf(
                ' and `Location Warehouse Key` in (%s)', join(',', $user->stores)
            );
        }

        $warehouses = join(',', $user->stores);
    }
    $memcache_fingerprint = $account->get('Account Code').'SEARCH_LOC'.$warehouses.md5($queries);

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


    if (!$results_data or $cache) {


        $candidates = array();

        $q = $queries;


        $sql = sprintf(
            "select `Location Key`,`Location Code` from `Location Dimension` where true $where_warehouse and `Location Code` like '%s%%' limit 20 ", $q
        );

        if ($result = $db->query($sql)) {
            foreach ($result as $row) {

                if ($row['Location Code'] == $q) {
                    $candidates[$row['Location Key']] = 30;
                } else {

                    $len_name                         = strlen(
                        $row['Location Code']
                    );
                    $len_q                            = strlen($q);
                    $factor                           = $len_q / $len_name;
                    $candidates[$row['Location Key']] = 20 * $factor;
                }

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

        $counter       = 0;
        $customer_keys = '';
        $results       = array();

        foreach ($candidates as $key => $val) {
            $counter++;
            $customer_keys .= ','.$key;
            $results[$key] = '';
            if ($counter > $max_results) {
                break;
            }
        }
        $customer_keys = preg_replace('/^,/', '', $customer_keys);

        $sql = sprintf(
            "SELECT `Location Code`,`Location Warehouse Key`,`Location Key`, `Warehouse Code` FROM `Location Dimension` LEFT JOIN `Warehouse Dimension` ON (`Location Warehouse Key`=`Warehouse Key`) WHERE `Location Key` IN (%s)",
            $customer_keys
        );

        if ($result = $db->query($sql)) {
            foreach ($result as $row) {


                $results[$row['Location Key']] = array(
                    'warehouse' => $row['Warehouse Code'],
                    'label'     => '<i class="fa fa-map-marker padding_right_10" aria-hidden="true"></i> '.highlightkeyword($row['Location Code'], $queries),
                    'details'   => '',
                    'view'      => sprintf(
                        'locations/%d/%d', $row['Location Warehouse Key'], $row['Location Key']
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
        'q'              => $q
    );

    echo json_encode($response);

}


function agent_search($db, $account, $user, $memcache_ip, $data) {

    $agent_key = $user->get('User Parent Key');

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


    $memcache_fingerprint = $account->get('Account Code').'AGENTSERCH'.md5(
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
                "SELECT `Supplier Key`,`Supplier Code` FROM `Supplier Dimension` LEFT JOIN `Agent Supplier Bridge` ON (`Supplier Key`=`Agent Supplier Supplier Key`) WHERE `Agent Supplier Agent Key`=%d  AND `Supplier Code` LIKE '%s%%' LIMIT 20 ",
                $agent_key, $q
            );


            if ($result = $db->query($sql)) {
                foreach ($result as $row) {

                    if ($row['Supplier Code'] == $q) {
                        $candidates['S'.$row['Supplier Key']] = 1000;
                    } else {

                        $len_name                             = strlen(
                            $row['Supplier Code']
                        );
                        $len_q                                = strlen($q);
                        $factor                               = $len_q / $len_name;
                        $candidates['S'.$row['Supplier Key']] = 500 * $factor;
                    }

                }
            } else {
                print_r($error_info = $db->errorInfo());
                print $sql;
                exit;
            }

            $sql = sprintf(
                "SELECT `Supplier Key`,`Supplier Name` FROM `Supplier Dimension` LEFT JOIN `Agent Supplier Bridge` ON (`Supplier Key`=`Agent Supplier Supplier Key`) WHERE `Agent Supplier Agent Key`=%d  AND  `Supplier Name`  REGEXP '[[:<:]]%s' LIMIT 20 ",
                $agent_key, $q
            );


            if ($result = $db->query($sql)) {
                foreach ($result as $row) {

                    if ($row['Supplier Name'] == $q) {
                        $candidates['S'.$row['Supplier Key']] = 800;
                    } else {

                        $len_name                             = strlen(
                            $row['Supplier Name']
                        );
                        $len_q                                = strlen($q);
                        $factor                               = $len_q / $len_name;
                        $candidates['S'.$row['Supplier Key']] = 400 * $factor;
                    }

                }
            } else {
                print_r($error_info = $db->errorInfo());
                print $sql;
                exit;
            }


            $sql = sprintf(
                "SELECT `Supplier Part Key`,`Supplier Part Reference` FROM `Supplier Part Dimension` LEFT JOIN `Agent Supplier Bridge` ON (`Supplier Part Supplier Key`=`Agent Supplier Supplier Key`) WHERE `Agent Supplier Agent Key`=%d  AND  `Supplier Part Reference` LIKE '%s%%' LIMIT 20 ",
                $agent_key, $q
            );


            if ($result = $db->query($sql)) {
                foreach ($result as $row) {

                    if ($row['Supplier Part Reference'] == $q) {
                        $candidates['P'.$row['Supplier Part Key']] = 1000;
                    } else {

                        $len_name                                  = strlen(
                            $row['Supplier Part Reference']
                        );
                        $len_q                                     = strlen($q);
                        $factor                                    = $len_q / $len_name;
                        $candidates['P'.$row['Supplier Part Key']] = 500 * $factor;
                    }

                }
            } else {
                print_r($error_info = $db->errorInfo());
                print $sql;
                exit;
            }


            $sql = sprintf(
                "SELECT `Supplier Part Key`,`Part Reference` FROM `Supplier Part Dimension`  LEFT JOIN `Agent Supplier Bridge` ON (`Supplier Part Supplier Key`=`Agent Supplier Supplier Key`)  LEFT JOIN   `Part Dimension`  ON (`Supplier Part Part SKU`=`Part SKU`) WHERE `Agent Supplier Agent Key`=%d  AND  `Part Reference` LIKE '%s%%' LIMIT 20 ",
                $agent_key, $q
            );
            //print $sql;

            if ($result = $db->query($sql)) {
                foreach ($result as $row) {

                    if ($row['Part Reference'] == $q) {
                        $candidates['P'.$row['Supplier Part Key']] = 750;
                    } else {

                        $len_name                                  = strlen(
                            $row['Part Reference']
                        );
                        $len_q                                     = strlen($q);
                        $factor                                    = $len_q / $len_name;
                        $candidates['P'.$row['Supplier Part Key']] = 375 * $factor;
                    }

                }
            } else {
                print_r($error_info = $db->errorInfo());
                print $sql;
                exit;
            }


            $sql = sprintf(
                "SELECT `Supplier Part Key`,`Part Unit Description` FROM `Supplier Part Dimension`  LEFT JOIN `Agent Supplier Bridge` ON (`Supplier Part Supplier Key`=`Agent Supplier Supplier Key`) LEFT JOIN   `Part Dimension`  ON (`Supplier Part Part SKU`=`Part SKU`)  WHERE `Agent Supplier Agent Key`=%d  AND  `Part Unit Description`  REGEXP '[[:<:]]%s' LIMIT 100 ",
                $agent_key, $q
            );

            if ($result = $db->query($sql)) {
                foreach ($result as $row) {
                    if ($row['Part Unit Description'] == $q) {
                        $candidates['P'.$row['Supplier Part Key']] = 55;
                    } else {

                        $len_name                                  = strlen(
                            $row['Part Unit Description']
                        );
                        $len_q                                     = strlen($q);
                        $factor                                    = $len_q / $len_name;
                        $candidates['P'.$row['Supplier Part Key']] = 50 * $factor;
                    }

                }
            } else {
                print_r($error_info = $db->errorInfo());
                print $sql;
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

        $counter                    = 0;
        $supplier_parts_keys        = '';
        $supplier_keys              = '';
        $agent_keys                 = '';
        $results                    = array();
        $number_supplier_parts_keys = 0;
        $number_supplier_keys       = 0;
        $number_agent_keys          = 0;

        foreach ($candidates as $_key => $val) {
            $counter++;

            if ($_key[0] == 'P') {
                $key = preg_replace('/^P/', '', $_key);
                $supplier_parts_keys .= ','.$key;
                $results[$_key] = '';
                $number_supplier_parts_keys++;

            } elseif ($_key[0] == 'S') {
                $key = preg_replace('/^S/', '', $_key);
                $supplier_keys .= ','.$key;
                $results[$_key] = '';
                $number_supplier_keys++;

            } elseif ($_key[0] == 'A') {
                $key = preg_replace('/^A/', '', $_key);
                $agent_keys .= ','.$key;
                $results[$_key] = '';
                $number_agent_keys++;

            }

            if ($counter > $max_results) {
                break;
            }
        }
        $supplier_parts_keys = preg_replace('/^,/', '', $supplier_parts_keys);
        $supplier_keys       = preg_replace('/^,/', '', $supplier_keys);
        $agent_keys          = preg_replace('/^,/', '', $agent_keys);


        if ($number_supplier_parts_keys) {
            $sql = sprintf(
                "SELECT `Supplier Part Key`,`Supplier Part Supplier Key`,`Supplier Part Reference`,`Part Unit Description` FROM `Supplier Part Dimension` LEFT JOIN   `Part Dimension`  ON (`Supplier Part Part SKU`=`Part SKU`)   WHERE `Supplier Part Key` IN (%s)",
                $supplier_parts_keys
            );

            if ($result = $db->query($sql)) {
                foreach ($result as $row) {


                    $results['P'.$row['Supplier Part Key']] = array(
                        'label'   => '<i class="fa fa-stop fa-fw "></i> '.highlightkeyword(
                                sprintf('%s', $row['Supplier Part Reference']), $queries
                            ),
                        'details' => highlightkeyword(
                            $row['Part Unit Description'], $queries
                        ),
                        'view'    => sprintf(
                            'supplier/%d/part/%d', $row['Supplier Part Supplier Key'], $row['Supplier Part Key']
                        )


                    );

                }
            } else {
                print_r($error_info = $db->errorInfo());
                print $sql;
                exit;
            }
        }

        if ($number_supplier_keys) {

            $sql = sprintf(
                "SELECT `Supplier Key`,`Supplier Code`,`Supplier Name` FROM `Supplier Dimension`  WHERE `Supplier Key` IN (%s)", $supplier_keys
            );

            if ($result = $db->query($sql)) {
                foreach ($result as $row) {


                    $results['S'.$row['Supplier Key']] = array(
                        'label'   => '<i class="fa fa-ship fa-fw "></i> '.highlightkeyword(
                                sprintf('%s', $row['Supplier Code']), $queries
                            ),
                        'details' => highlightkeyword(
                            $row['Supplier Name'], $queries
                        ),
                        'view'    => sprintf(
                            'supplier/%d', $row['Supplier Key']
                        )


                    );

                }
            } else {
                print_r($error_info = $db->errorInfo());
                print $sql;
                exit;
            }


        }

        if ($number_agent_keys) {

            $sql = sprintf(
                "SELECT `Agent Key`,`Agent Code`,`Agent Name` FROM `Agent Dimension`  WHERE `Agent Key` IN (%s)", $agent_keys
            );

            if ($result = $db->query($sql)) {
                foreach ($result as $row) {


                    $results['A'.$row['Agent Key']] = array(
                        'label'   => '<i class="fa fa-user-secret fa-fw "></i> '.highlightkeyword(
                                sprintf('%s', $row['Agent Code']), $queries
                            ),
                        'details' => highlightkeyword(
                            $row['Agent Name'], $queries
                        ),
                        'view'    => sprintf('agent/%d', $row['Agent Key'])


                    );

                }
            } else {
                print_r($error_info = $db->errorInfo());
                print $sql;
                exit;
            }


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


?>
