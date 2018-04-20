<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>

 Copyright (c) 2014, Inikoo
 Created: 12 March 2018 at 22:07:23 GMT+8, Kuala Lumpur, Malaysia

 Version 2.0
*/

if (empty($_REQUEST['action'])) {
    $response = log_api_key_access_failure(
        $db, $api_key_key, 'Fail_Operation', "Action missing"
    );
    echo json_encode($response);
    exit;
}

switch ($_REQUEST['action']) {
    case 'get_user_data':
        $response = array(
            'state' => 'OK',
            'data'  => $user->data
        );
        echo json_encode($response);
        exit;
        break;
    case 'get_employee_data':
        $staff = get_object('staff', $user->get_staff_key());
        $data  = $staff->data;
        unset($data['Staff Salary']);
        unset($data['Staff PIN']);
        $response = array(
            'state' => 'OK',
            'data'  => $data
        );
        echo json_encode($response);
        exit;
        break;
    case 'get_part_data_from_barcode':

        include_once 'class.Part.php';

        $part=new Part('barcode',$_REQUEST['barcode']);

        if (!$part->id) {
            $response = array(
                'state' => 'Error',
                'msg'   => 'part not found'
            );
            echo json_encode($response);
            exit;
        }

        $data = $part->data;

        $response = array(
            'state' => 'OK',
            'data'  => $data
        );
        echo json_encode($response);
        exit;
        break;
    case 'get_part_data':
        $part = get_object('part', $_REQUEST['part_sku']);

        if (!$part->id) {
            $response = array(
                'state' => 'Error',
                'msg'   => 'part not found'
            );
            echo json_encode($response);
            exit;
        }

        $data = $part->data;

        $response = array(
            'state' => 'OK',
            'data'  => $data
        );
        echo json_encode($response);
        exit;
        break;
    case 'get_location_data':
        $location = get_object('location', $_REQUEST['location_key']);

        if (!$location->id) {
            $response = array(
                'state' => 'Error',
                'msg'   => 'location not found'
            );
            echo json_encode($response);
            exit;
        }

        $data = $location->data;

        $response = array(
            'state' => 'OK',
            'data'  => $data
        );
        echo json_encode($response);
        exit;
        break;

    case 'search_location_by_code':

        if (empty($_REQUEST['query'])) {
            $_REQUEST['query'] = '';
        }

        include_once 'search_functions.php';

        global $account, $memcache_ip;

        $user->read_warehouses();



        $data=array(
            'user'=>$user,
            'query'=>$_REQUEST['query'],
            'scope'=>''
        );


        search_locations($db, $account, $memcache_ip, $data);

        $response = array(
            'state' => 'OK',
            'data'  => ''
        );
        echo json_encode($response);
        exit;
        break;

    default:


        $response = array(
            'state' => 'Error',
            'msg'   => "Action ".$_REQUEST['action'].' not found'
        );
        echo json_encode($response);
        exit;


        //$response = log_api_key_access_failure($db, $api_key_key, 'Fail_Operation', "Action ".$_REQUEST['action'].' not found');
        echo json_encode($response);
        exit;

}

?>
