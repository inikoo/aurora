<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 2 October 2015 at 12:24:43 BST, Sheffield UK
 Copyright (c) 2015, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'utils/ar_common.php';
require_once 'utils/table_functions.php';


if (!$user->can_view('stores')) {
    echo json_encode(
        array(
            'state' => 405,
            'resp'  => 'Forbidden'
        )
    );
    exit;
}


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
    case 'marketing_server':
        marketing_server(get_table_parameters(), $db, $user);
        break;
    case 'campaigns':
        campaigns(get_table_parameters(), $db, $user);
        break;
    case 'deals':
        deals(get_table_parameters(), $db, $user);
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


function marketing_server($_data, $db, $user) {

    $rtext_label = 'store';
    include_once 'prepare_table/init.php';

    $sql
           = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $adata = array();

    // print $sql;

    if ($result = $db->query($sql)) {
        foreach ($result as $data) {


            $adata[] = array(
                'id' => (integer)$data['Store Key'],

                'code'      => sprintf('<span class="link" onClick="change_view(\'marketing/%d\')">%s</span>',$data['Store Key'],$data['Store Code']),
                'name'      => $data['Store Name'],
                'campaigns' => sprintf('<span class="link" onClick="change_view(\'campaigns/%d\')">%s</span>',$data['Store Key'],$data['campaigns']),
                'deals'     => sprintf('<span class="link" onClick="change_view(\'deals/%d\')">%s</span>',$data['Store Key'],$data['deals']),
            );


        }
    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }


    $response = array(
        'resultset' => array(
            'state'         => 200,
            'data'          => $adata,
            'rtext'         => $rtext,
            'sort_key'      => $_order,
            'sort_dir'      => $_dir,
            'total_records' => $total

        )
    );
    echo json_encode($response);
}


function deals($_data, $db, $user) {

    $rtext_label = 'offer';
    include_once 'prepare_table/init.php';

    $sql
           = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $adata = array();

    // print $sql;

    if ($result = $db->query($sql)) {
        foreach ($result as $data) {


            switch ($data['Deal Status']) {
                case 'Waiting':
                    $status = sprintf(
                        '<i class="fa fa-clock-o discreet fa-fw" aria-hidden="true" title="%s" ></i>', _('Waiting')
                    );
                    break;
                case 'Active':
                    $status = sprintf(
                        '<i class="fa fa-play success fa-fw" aria-hidden="true" title="%s" ></i>', _('Active')
                    );
                    break;
                case 'Suspended':
                    $status = sprintf(
                        '<i class="fa fa-pause error fa-fw" aria-hidden="true" title="%s" ></i>', _('Suspended')
                    );
                    break;
                case 'Finish':
                    $status = sprintf(
                        '<i class="fa fa-stop discreet fa-fw" aria-hidden="true" title="%s" ></i>', _('Finished')
                    );
                    break;
                default:
                    $status = $data['Deal Status'];
            }

            $duration = '';
            if ($data['Deal Expiration Date'] == '' and $data['Deal Begin Date'] == '') {
                $duration = _('Permanent');
            } else {

                if ($data['Deal Begin Date'] != '') {
                    $duration = strftime(
                        "%x", strtotime($data['Deal Begin Date']." +00:00")
                    );

                }
                $duration .= ' - ';
                if ($data['Deal Expiration Date'] != '') {
                    $duration .= strftime(
                        "%x", strtotime($data['Deal Expiration Date']." +00:00")
                    );

                } else {
                    $duration .= _('Present');
                }

            }

            if ($data['Deal Expiration Date'] != '') {
                $to = strftime(
                    "%x", strtotime($data['Deal Expiration Date']." +00:00")
                );
            } else {
                $to = _('Permanent');
            }


            if ($data['Deal Begin Date'] != '') {
                $from = strftime(
                    "%x", strtotime($data['Deal Begin Date']." +00:00")
                );
            } else {
                $from = '';
            }

            if (strlen(strip_tags($data['Deal Term Allowances Label'])) > 75) {
                $description_class = 'super_small';
            } elseif (strlen(strip_tags($data['Deal Term Allowances Label'])) > 60) {
                $description_class = 'very_small';
            } elseif (strlen(strip_tags($data['Deal Term Allowances Label'])) > 50) {
                $description_class = 'small';
            } else {
                $description_class = '';
            }
            $adata[] = array(
                'id'          => (integer)$data['Deal Key'],
                'store_key'   => (integer)$data['Deal Store Key'],
                'status'      => $status,
                'name'        => $data['Deal Name'],
                'description' => sprintf(
                    '<span class="%s" title="%s">%s</span>', $description_class, strip_tags($data['Deal Term Allowances']), $data['Deal Term Allowances Label']
                ),
                'from'        => $from,
                'to'          => $to,
                'orders'      => number($data['Deal Total Acc Used Orders']),
                'customers'   => number($data['Deal Total Acc Used Customers'])

            );


        }
    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }


    $response = array(
        'resultset' => array(
            'state'         => 200,
            'data'          => $adata,
            'rtext'         => $rtext,
            'sort_key'      => $_order,
            'sort_dir'      => $_dir,
            'total_records' => $total

        )
    );
    echo json_encode($response);
}

function campaigns($_data, $db, $user) {

    $rtext_label = 'campaign';
    include_once 'prepare_table/init.php';

    $sql
           = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $adata = array();

    // print $sql;

    if ($result = $db->query($sql)) {
        foreach ($result as $data) {


            switch ($data['Deal Campaign Status']) {
                case 'Waiting':
                    $status = sprintf(
                        '<i class="fa fa-clock-o discreet fa-fw" aria-hidden="true" title="%s" ></i>', _('Waiting')
                    );
                    break;
                case 'Active':
                    $status = sprintf(
                        '<i class="fa fa-play success fa-fw" aria-hidden="true" title="%s" ></i>', _('Active')
                    );
                    break;
                case 'Suspended':
                    $status = sprintf(
                        '<i class="fa fa-pause error fa-fw" aria-hidden="true" title="%s" ></i>', _('Suspended')
                    );
                    break;
                case 'Finish':
                    $status = sprintf(
                        '<i class="fa fa-stop discreet fa-fw" aria-hidden="true" title="%s" ></i>', _('Finished')
                    );
                    break;
                default:
                    $status = $data['Deal Campaign Status'];
            }

            $duration = '';
            if ($data['Deal Campaign Valid To'] == '' and $data['Deal Campaign Valid From'] == '') {
                $duration = _('Permanent');
            } else {

                if ($data['Deal Campaign Valid From'] != '') {
                    $duration = strftime(
                        "%x", strtotime($data['Deal Campaign Valid From']." +00:00")
                    );

                }
                $duration .= ' - ';
                if ($data['Deal Campaign Valid To'] != '') {
                    $duration .= strftime(
                        "%x", strtotime($data['Deal Campaign Valid To']." +00:00")
                    );

                } else {
                    $duration .= _('Present');
                }

            }

            if ($data['Deal Campaign Valid To'] != '') {
                $to = strftime(
                    "%x", strtotime($data['Deal Campaign Valid To']." +00:00")
                );
            } else {
                $to = _('Permanent');
            }


            if ($data['Deal Campaign Valid From'] != '') {
                $from = strftime(
                    "%x", strtotime($data['Deal Campaign Valid From']." +00:00")
                );
            } else {
                $from = '';
            }

            $adata[] = array(
                'id'        => (integer)$data['Deal Campaign Key'],
                'store_key' => (integer)$data['Deal Campaign Store Key'],
                'status'    => $status,
                'name'      => $data['Deal Campaign Name'],
                'from'      => $from,
                'to'        => $to,
                'deals'     => number(
                    $data['Deal Campaign Number Current Deals']
                ),

                'orders'    => number(
                    $data['Deal Campaign Total Acc Used Orders']
                ),
                'customers' => number(
                    $data['Deal Campaign Total Acc Used Customers']
                )

            );


        }
    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }


    $response = array(
        'resultset' => array(
            'state'         => 200,
            'data'          => $adata,
            'rtext'         => $rtext,
            'sort_key'      => $_order,
            'sort_dir'      => $_dir,
            'total_records' => $total

        )
    );
    echo json_encode($response);
}


?>
