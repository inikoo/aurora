<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 16 June 2016 at 13:07:02 BST, Sheffield UK
 Copyright (c) 2015, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'utils/ar_common.php';
require_once 'utils/table_functions.php';


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
    case 'attendance':
        attendance(get_table_parameters(), $db, $user);
        break;
    case 'fire':
        fire(get_table_parameters(), $db, $user);
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


function attendance($_data, $db, $user) {

    $rtext_label = 'employee';
    include_once 'prepare_table/init.php';
    include_once 'utils/natural_language.php';

    $sql
           = "select $fields from $table $where $wheref $group_by order by $order $order_direction ";
    $adata = array();


    if ($result = $db->query($sql)) {

        foreach ($result as $data) {

            switch ($data['status']) {
                case 'Work':
                    $status = sprintf(
                        '<span class="success padding_right_10">%s</span>', _('On Premises')
                    );

                    break;
                case 'Home':
                    $status = sprintf(
                        '<span class="error padding_right_10">%s</span>', _('Working at home')
                    );
                    break;
                case 'Outside':
                    $status = sprintf(
                        '<span class="error padding_right_10">%s</span>', _('Working outside')
                    );
                    break;
                case 'Outside':
                    $status = sprintf(
                        '<span class="error padding_right_10">%s</span>', _('Working outside')
                    );
                    break;
                case 'Off':
                    $status = sprintf(
                        '<span class="disabled padding_right_10">%s</span>', _('Off')
                    );
                    break;
                default:
                    $status = $data['status'];
                    break;
            }



            $adata[] = array(
                'staff_key'        => $data['Timesheet Staff Key'],
                'name'             => $data['Staff Name'],
                'clocking_records' => number($data['clocking_records']),
                'status'           => $status,


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

function fire($_data, $db, $user) {

    $rtext_label = 'employee';
    include_once 'prepare_table/init.php';
    include_once 'utils/natural_language.php';

    $sql
           = "select $fields from $table $where $wheref $group_by order by $order $order_direction ";
    $adata = array();


    if ($result = $db->query($sql)) {

        foreach ($result as $data) {

            switch ($data['status']) {
                case 'In':
                    $status = sprintf(
                        '<span class="success padding_right_10">%s</span>', _('In')
                    );

                    break;
                case 'Out':
                    $status = sprintf(
                        '<span class="error padding_right_10">%s</span>', _('Out')
                    );
                    break;
                case 'Off':
                    $status = sprintf(
                        '<span class="disabled padding_right_10">%s</span>', _('Off')
                    );
                    break;
                default:
                    $status = $data['status'];
                    $used   = '';
                    break;
            }


            $check = '<div id="check_'.$data['Timesheet Key'].'" onClick="toggle_check_record('.$data['Timesheet Key']
                .')" class="disabled align_center width_100 unchecked" style="margin:0px 20px"><i class="fa fa-star"></i></div>';

            $adata[] = array(
                'staff_key'        => $data['Timesheet Staff Key'],
                'name'             => $data['Staff Name'],
                'clocking_records' => number($data['clocking_records']),
                'status'           => $status,
                'check'            => $check


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



