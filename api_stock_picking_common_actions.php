<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>

 Copyright (c) 2014, Inikoo
 Created: 23 May 2018 at 22:37:15 GMT+8, Kuala Lumpur, Malaysia

 Version 2.0
*/

switch ($_REQUEST['action']) {


    case 'update_location_flag':


        if (empty($_REQUEST['location_key'])) {
            $response = array(
                'state' => 'Error',
                'msg'   => 'location_key needed'
            );
            echo json_encode($response);
            exit;
        }

        if (empty($_REQUEST['flag'])) {
            $response = array(
                'state' => 'Error',
                'msg'   => 'flag needed'
            );
            echo json_encode($response);
            exit;
        }


        $location         = get_object('location', $_REQUEST['Location Key']);
        $location->editor = $editor;
        if (!$location->id) {
            $response = array(
                'state' => 'Error',
                'msg'   => 'location not found'
            );
            echo json_encode($response);
            exit;
        }

        $sql = sprintf('select `Warehouse Flag Key` from `Warehouse Flag Dimension`F  where `Warehouse Flag Color`=%s  ', prepare_mysql($_REQUEST['flag']));

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $flag_key = $row['Warehouse Flag Key'];
            } else {
                $response = array(
                    'state' => 'Error',
                    'msg'   => 'flag not found'
                );
                echo json_encode($response);
                exit;
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }

        $location->update(
            array(
                'Location Warehouse Flag Key' => $flag_key
            )
        );


        $response = array(
            'state' => 'OK',

        );
        echo json_encode($response);
        exit;
        break;


    case 'get_locations_by_flag':

        if (empty($_REQUEST['flag'])) {
            $response = array(
                'state' => 'Error',
                'msg'   => 'flag needed'
            );
            echo json_encode($response);
            exit;
        }


        $sql            = sprintf(
            'select `Location Key`,`Location Code` from `Location Dimension` L left join `Warehouse Flag Dimension`F  on (F.`Warehouse Flag Key`=L.`Location Warehouse Flag Key`) where `Warehouse Flag Color`=%s order by `Location File As` ',
            prepare_mysql($_REQUEST['flag'])
        );
        $locations_data = array();
        if ($result = $db->query($sql)) {
            foreach ($result as $row) {
                $locations_data[] = $row;
            }
        } else {
            print_r($error_info = $db->errorInfo());
            print "$sql\n";
            exit;
        }

        $response = array(
            'state' => 'OK',
            'data'  => $locations_data
        );
        echo json_encode($response);
        exit;
        break;


    case 'get_empty_locations':

        $sql            = sprintf('select `Location Key`,`Location Code` from `Location Dimension` where `Location Distinct Parts`=0 ');
        $locations_data = array();
        if ($result = $db->query($sql)) {
            foreach ($result as $row) {
                $locations_data[] = $row;
            }
        } else {
            print_r($error_info = $db->errorInfo());
            print "$sql\n";
            exit;
        }

        $response = array(
            'state' => 'OK',
            'data'  => $locations_data
        );
        echo json_encode($response);
        exit;
        break;

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

    case 'set_as_picking_location':


        if (empty($_REQUEST['part_sku'])) {
            $response = array(
                'state' => 'Error',
                'msg'   => 'part_sku needed'
            );
            echo json_encode($response);
            exit;
        }


        if (empty($_REQUEST['location_key'])) {
            $response = array(
                'state' => 'Error',
                'msg'   => 'location_key needed'
            );
            echo json_encode($response);
            exit;
        }


        include_once 'class.PartLocation.php';

        $editor = array(
            'Author Name'  => $user->data['User Alias'].' (via App)',
            'Author Alias' => $user->data['User Alias'].' (via App)',
            'Author Type'  => $user->data['User Type'],
            'Author Key'   => $user->data['User Parent Key'],
            'User Key'     => $user->id,
            'Date'         => gmdate('Y-m-d H:i:s')
        );


        $part_location         = new PartLocation($_REQUEST['part_sku'], $_REQUEST['location_key']);
        $part_location->editor = $editor;

        if (!$part_location->ok) {
            $response = array(
                'state' => 'Error',
                'msg'   => 'location part not associated'
            );


            echo json_encode($response);
            exit;

        }


        foreach ($part_location->part->get_locations('part_location_object') as $part_location) {


            if ($part_location->location_key == $_REQUEST['location_key']) {
                $part_location->update(array('Can Pick' => 'Yes'));
            } else {
                $part_location->update(array('Can Pick' => 'No'));
            }


        }

        $response = array(
            'state' => 'OK'

        );
        echo json_encode($response);
        exit;
        break;

    case 'get_part_data_from_barcode':

        include_once 'class.Part.php';

        $part = new Part('barcode', $_REQUEST['barcode']);

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
    case 'get_part_products_data':
        $part = get_object('part', $_REQUEST['part_sku']);

        if (!$part->id) {
            $response = array(
                'state' => 'Error',
                'msg'   => 'part not found'
            );
            echo json_encode($response);
            exit;
        }

        $data = $part->get_products('products_data');

        $response = array(
            'state' => 'OK',
            'data'  => $data
        );
        echo json_encode($response);
        exit;
        break;

    case 'get_parts_from_location_key':

        if (!isset($_REQUEST['location_key'])) {
            $response = array(
                'state' => 'Error',
                'msg'   => 'location_key needed'
            );
            echo json_encode($response);
            exit;
        }


        $location = get_object('location', $_REQUEST['location_key']);

        if (!$location->id) {
            $response = array(
                'state' => 'Error',
                'msg'   => 'location not found ('.$_REQUEST['location_key'].')  '
            );
            echo json_encode($response);
            exit;
        }


        $response = array(
            'state' => 'OK',
            'data'  => $location->get_parts('data')
        );
        echo json_encode($response);
        exit;
        break;

    case 'get_locations_from_part_sku':

        if (!isset($_REQUEST['part_sku'])) {
            $response = array(
                'state' => 'Error',
                'msg'   => 'part_sku needed'
            );
            echo json_encode($response);
            exit;
        }


        $part = get_object('part', $_REQUEST['part_sku']);

        if (!$part->id) {
            $response = array(
                'state' => 'Error',
                'msg'   => 'part not found ('.$_REQUEST['part_sku'].')  '
            );
            echo json_encode($response);
            exit;
        }

        $data = $part->data;

        $response = array(
            'state' => 'OK',
            'data'  => $part->get_locations('data')
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

        include_once('utils/text_functions.php');

        if (empty($_REQUEST['query'])) {
            $_REQUEST['query'] = '';
        }

        include_once 'search_functions.php';

        $account = get_object('Account', 1);

        $user->read_warehouses();

        $user->read_stores();


        $data = array(
            'user'  => $user,
            'query' => $_REQUEST['query'],
            'scope' => ''
        );


        $_response = search_locations($db, $account, $user,$data, 'data');

        $response = array(
            'state' => 'OK',
            'data'  => array(
                'results'        => $_response['results'],
                'number_results' => $_response['number_results'],

            )
        );
        echo json_encode($response);
        exit;
        break;

    case 'search_part_by_code':

        include_once('utils/text_functions.php');

        if (empty($_REQUEST['query'])) {
            $_REQUEST['query'] = '';
        }

        include_once 'search_functions.php';


        $user->read_warehouses();

        $user->read_stores();


        $data = array(
            'user'  => $user,
            'query' => $_REQUEST['query'],
            'scope' => ''
        );


        $_response = search_parts($db, $account, $data, 'data');

        $response = array(
            'state' => 'OK',
            'data'  => array(
                'results'        => $_response['results'],
                'number_results' => $_response['number_results'],

            )
        );
        echo json_encode($response);
        exit;
        break;


    case 'link_part_location':


        if (empty($_REQUEST['part_sku'])) {
            $response = array(
                'state' => 'Error',
                'msg'   => 'part_sku needed'
            );
            echo json_encode($response);
            exit;
        }


        if (empty($_REQUEST['location_key'])) {
            $response = array(
                'state' => 'Error',
                'msg'   => 'location_key needed'
            );
            echo json_encode($response);
            exit;
        }


        $part = get_object('Part', $_REQUEST['part_sku']);
        if (!$part->sku) {
            $response = array(
                'state' => 'Error',
                'msg'   => 'part not found'
            );
            echo json_encode($response);
            exit;
        }

        $location = get_object('Location', $_REQUEST['location_key']);
        if (!$location->id) {
            $response = array(
                'state' => 'Error',
                'msg'   => 'location not found'
            );
            echo json_encode($response);
            exit;
        }




        include_once 'class.PartLocation.php';

        $editor = array(
            'Author Name'  => $user->data['User Alias'].' (via App)',
            'Author Alias' => $user->data['User Alias'].' (via App)',
            'Author Type'  => $user->data['User Type'],
            'Author Key'   => $user->data['User Parent Key'],
            'User Key'     => $user->id,
            'Date'         => gmdate('Y-m-d H:i:s')
        );


        $part_location_data = array(
            'Location Key' => $_REQUEST['location_key'],
            'Part SKU'     => $_REQUEST['part_sku'],
            'editor'       => $editor
        );


        $part_location = new PartLocation('find', $part_location_data, 'create');


        $response = array(
            'state' => 'OK',
            'data'  => $part_location->data
        );
        echo json_encode($response);
        exit;
        break;


    case 'unlink_part_location':


        if (empty($_REQUEST['part_sku'])) {
            $response = array(
                'state' => 'Error',
                'msg'   => 'part_sku needed'
            );
            echo json_encode($response);
            exit;
        }


        if (empty($_REQUEST['location_key'])) {
            $response = array(
                'state' => 'Error',
                'msg'   => 'location_key needed'
            );
            echo json_encode($response);
            exit;
        }


        include_once 'class.PartLocation.php';

        $editor = array(
            'Author Name'  => $user->data['User Alias'].' (via App)',
            'Author Alias' => $user->data['User Alias'].' (via App)',
            'Author Type'  => $user->data['User Type'],
            'Author Key'   => $user->data['User Parent Key'],
            'User Key'     => $user->id,
            'Date'         => gmdate('Y-m-d H:i:s')
        );


        $part_location         = new PartLocation($_REQUEST['part_sku'], $_REQUEST['location_key']);
        $part_location->editor = $editor;

        if (!$part_location->ok) {
            $response = array(
                'state' => 'OK'
            );


            echo json_encode($response);
            exit;

        } else {

            if ($part_location->get('Quantity On Hand') != 0) {

                $response = array(
                    'state' => 'Error',
                    'msg'   => 'location part has stock'
                );


                echo json_encode($response);
                exit;

            } else {


                $part_location->disassociate();


                $response = array(
                    'state' => 'OK'

                );
                echo json_encode($response);

            }


        }


        exit;
        break;


    case 'audit_stock':


        if (empty($_REQUEST['part_sku'])) {
            $response = array(
                'state' => 'Error',
                'msg'   => 'part_sku needed'
            );
            echo json_encode($response);
            exit;
        }


        if (empty($_REQUEST['location_key'])) {
            $response = array(
                'state' => 'Error',
                'msg'   => 'location_key needed'
            );
            echo json_encode($response);
            exit;
        }

        if (empty($_REQUEST['qty'])) {
            $response = array(
                'state' => 'Error',
                'msg'   => 'qty needed'
            );
            echo json_encode($response);
            exit;
        }
        if (!is_numeric($_REQUEST['qty']) or $_REQUEST['qty'] < 0) {
            $response = array(
                'state' => 'Error',
                'msg'   => 'qty has t be a positive number'
            );
            echo json_encode($response);
            exit;
        }

        include_once 'class.PartLocation.php';

        $editor = array(
            'Author Name'  => $user->data['User Alias'],
            'Author Alias' => $user->data['User Alias'].' (via App)',
            'Author Type'  => $user->data['User Type'],
            'Author Key'   => $user->data['User Parent Key'],
            'User Key'     => $user->id,
            'Date'         => gmdate('Y-m-d H:i:s')
        );


        $part_location         = new PartLocation($_REQUEST['part_sku'], $_REQUEST['location_key']);
        $part_location->editor = $editor;

        if (!$part_location->ok) {
            $response = array(
                'state' => 'Error',
                'msg'   => 'location part not associated'
            );


            echo json_encode($response);
            exit;

        }


        $part_location->audit($_REQUEST['qty'], (isset($_REQUEST['note']) ? $_REQUEST['note'] : ''));


        $response = array(
            'state' => 'OK',
            'data'  => $part_location->data
        );
        echo json_encode($response);
        exit;
        break;

    case 'move_stock':


        if (empty($_REQUEST['part_sku'])) {
            $response = array(
                'state' => 'Error',
                'msg'   => 'part_sku needed'
            );
            echo json_encode($response);
            exit;
        }


        if (empty($_REQUEST['location_from_key'])) {
            $response = array(
                'state' => 'Error',
                'msg'   => 'location_from_key needed'
            );
            echo json_encode($response);
            exit;
        }

        if (empty($_REQUEST['location_to_key'])) {
            $response = array(
                'state' => 'Error',
                'msg'   => 'location_to_key needed'
            );
            echo json_encode($response);
            exit;
        }


        if ($_REQUEST['location_to_key'] == $_REQUEST['location_from_key']) {
            $response = array(
                'state' => 'Error',
                'msg'   => 'location_from_key and location_to_key can not be same'
            );
            echo json_encode($response);
            exit;
        }

        if (empty($_REQUEST['qty'])) {
            $response = array(
                'state' => 'Error',
                'msg'   => 'qty needed'
            );
            echo json_encode($response);
            exit;
        }
        if (!is_numeric($_REQUEST['qty']) or $_REQUEST['qty'] < 0) {
            $response = array(
                'state' => 'Error',
                'msg'   => 'qty has t be a positive number'
            );
            echo json_encode($response);
            exit;
        }

        include_once 'class.PartLocation.php';

        $editor = array(
            'Author Name'  => $user->data['User Alias'],
            'Author Alias' => $user->data['User Alias'].' (via App)',
            'Author Type'  => $user->data['User Type'],
            'Author Key'   => $user->data['User Parent Key'],
            'User Key'     => $user->id,
            'Date'         => gmdate('Y-m-d H:i:s')
        );


        $part_location_from         = new PartLocation($_REQUEST['part_sku'], $_REQUEST['location_from_key']);
        $part_location_from->editor = $editor;

        if (!$part_location_from->ok) {
            $response = array(
                'state' => 'Error',
                'msg'   => 'location_from part not associated'
            );


            echo json_encode($response);
            exit;

        }


        if ($part_location_from->get('Quantity On Hand') < $_REQUEST['qty']) {

            $response = array(
                'state' => 'Error',
                'msg'   => 'location_from part has less stock the moving qty'
            );


            echo json_encode($response);
            exit;

        }


        $part_location_to         = new PartLocation($_REQUEST['part_sku'], $_REQUEST['location_to_key']);
        $part_location_to->editor = $editor;


        if (!$part_location_from->ok) {
            $response = array(
                'state' => 'Error',
                'msg'   => 'location_to part not associated'
            );


            echo json_encode($response);
            exit;

        }


        $part_location_from->move_stock(
            array(
                'Destination Key'  => $_REQUEST['location_to_key'],
                'Quantity To Move' => $_REQUEST['qty']
            )
        );


        $part_location_to->get_data();


        $response = array(
            'state' => 'OK',
            'data'  => array(
                'from' => $part_location_from->data,
                'to'   => $part_location_to->data,
            )
        );
        echo json_encode($response);
        exit;
        break;



    default:


}


