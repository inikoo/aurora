<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>

 Copyright (c) 2018, Inikoo
 Created: 11 May 2018 at 11:01:33 CEST, Mijas Costa, Spain

 Version 2.0
*/

$account = get_object('Account', 1);


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
                $part_location->update(array('Part Location Can Pick' => 'Yes'));
            } else {
                $part_location->update(array('Part Location Can Pick' => 'No'));
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

    case 'get_delivery_note_from_public_id':

        if (!isset($_REQUEST['public_id'])) {
            $response = array(
                'state' => 'Error',
                'msg'   => 'public_id needed'
            );
            echo json_encode($response);
            exit;
        }


        include_once 'class.DeliveryNote.php';

        $delivery_note = new DeliveryNote('public_id', $_REQUEST['public_id']);

        if (!$delivery_note->id) {
            $response = array(
                'state' => 'Error',
                'msg'   => 'delivery note not found'
            );
            echo json_encode($response);
            exit;
        }

        $data = $delivery_note->data;

        $response = array(
            'state' => 'OK',
            'data'  => $data
        );
        echo json_encode($response);
        exit;
        break;


    case 'unset_picker':
    case 'unset_packer':
        if (!isset($_REQUEST['delivery_note_key'])) {
            $response = array(
                'state' => 'Error',
                'msg'   => 'delivery_note_key needed'
            );
            echo json_encode($response);
            exit;
        }

        if (!is_numeric($_REQUEST['delivery_note_key']) or $_REQUEST['delivery_note_key'] <= 0) {
            $response = array(
                'state' => 'Error',
                'msg'   => 'invalid delivery_note_key: '.$_REQUEST['delivery_note_key']
            );
            echo json_encode($response);
            exit;
        }


        $delivery_note = get_object('DeliveryNote', $_REQUEST['delivery_note_key']);

        if (!$delivery_note->id) {
            $response = array(
                'state' => 'Error',
                'msg'   => 'delivery note not found'
            );
            echo json_encode($response);
            exit;
        }


        if ($_REQUEST['action'] == 'unset_picker') {

            $type = 'Picker';
        } else {
            $type = 'Packer';
        }

        $delivery_note->update(
            array(
                'Delivery Note Assigned '.$type.' Key'   => '',
                'Delivery Note Assigned '.$type.' Alias' => ''
            )
        );


        $response = array(
            'state' => 'OK',
            'data'  => $delivery_note->get_update_metadata()
        );
        echo json_encode($response);
        exit;
        break;

    case 'set_picker':
    case 'set_packer':

        if (!isset($_REQUEST['delivery_note_key'])) {
            $response = array(
                'state' => 'Error',
                'msg'   => 'delivery_note_key needed'
            );
            echo json_encode($response);
            exit;
        }

        if (!is_numeric($_REQUEST['delivery_note_key']) or $_REQUEST['delivery_note_key'] <= 0) {
            $response = array(
                'state' => 'Error',
                'msg'   => 'invalid delivery_note_key: '.$_REQUEST['delivery_note_key']
            );
            echo json_encode($response);
            exit;
        }


        if (!isset($_REQUEST['staff_key'])) {
            $response = array(
                'state' => 'Error',
                'msg'   => 'staff_key needed'
            );
            echo json_encode($response);
            exit;
        }

        if (!is_numeric($_REQUEST['staff_key']) or $_REQUEST['staff_key'] <= 0) {
            $response = array(
                'state' => 'Error',
                'msg'   => 'invalid staff_key: '.$_REQUEST['staff_key']
            );
            echo json_encode($response);
            exit;
        }

        $delivery_note = get_object('DeliveryNote', $_REQUEST['delivery_note_key']);

        if (!$delivery_note->id) {
            $response = array(
                'state' => 'Error',
                'msg'   => 'delivery note not found'
            );
            echo json_encode($response);
            exit;
        }

        $staff = get_object('staff', $_REQUEST['staff_key']);
        if (!$staff->id) {
            $response = array(
                'state' => 'Error',
                'msg'   => 'staff not found'
            );
            echo json_encode($response);
            exit;

        }


        if ($_REQUEST['action'] == 'set_picker') {

            $type = 'Picker';
        } else {
            $type = 'Packer';
        }

        $delivery_note->update(
            array(
                'Delivery Note Assigned '.$type.' Key'   => $staff->id,
                'Delivery Note Assigned '.$type.' Alias' => $staff->get('Alias')
            )
        );


        $response = array(
            'state' => 'OK',
            'data'  => $delivery_note->get_update_metadata()
        );
        echo json_encode($response);
        exit;
        break;


    case 'start_picking':

        if (!isset($_REQUEST['delivery_note_key'])) {
            $response = array(
                'state' => 'Error',
                'msg'   => 'delivery_note_key needed'
            );
            echo json_encode($response);
            exit;
        }

        if (!is_numeric($_REQUEST['delivery_note_key']) or $_REQUEST['delivery_note_key'] <= 0) {
            $response = array(
                'state' => 'Error',
                'msg'   => 'invalid delivery_note_key: '.$_REQUEST['delivery_note_key']
            );
            echo json_encode($response);
            exit;
        }

        $delivery_note = get_object('DeliveryNote', $_REQUEST['delivery_note_key']);
        $delivery_note->update_state('Picking');

        $response = array(
            'state' => 'OK',
            'data'  => $delivery_note->get_update_metadata()
        );
        echo json_encode($response);
        exit;
        break;


    case 'pick_item':





         if (!isset($_REQUEST['staff_key'])) {
             $response = array(
                 'state' => 'Error',
                 'msg'   => 'staff_key needed'
             );
             echo json_encode($response);
             exit;
         }

        if (!isset($_REQUEST['delivery_note_key'])) {
            $response = array(
                'state' => 'Error',
                'msg'   => 'delivery_note_key needed'
            );
            echo json_encode($response);
            exit;
        }

        if (!isset($_REQUEST['itf_key'])) {
            $response = array(
                'state' => 'Error',
                'msg'   => 'itf_key needed'
            );
            echo json_encode($response);
            exit;
        }


        if (!isset($_REQUEST['quantity'])) {
            $response = array(
                'state' => 'Error',
                'msg'   => 'quantity needed'
            );
            echo json_encode($response);
            exit;
        }

        if (!is_numeric($_REQUEST['delivery_note_key']) or $_REQUEST['delivery_note_key'] <= 0) {
            $response = array(
                'state' => 'Error',
                'msg'   => 'invalid delivery_note_key: '.$_REQUEST['delivery_note_key']
            );
            echo json_encode($response);
            exit;
        }

        if (!is_numeric($_REQUEST['staff_key']) or $_REQUEST['staff_key'] <= 0) {
            $response = array(
                'state' => 'Error',
                'msg'   => 'invalid staff_key: '.$_REQUEST['staff_key']
            );
            echo json_encode($response);
            exit;
        }





        if (!is_numeric($_REQUEST['itf_key']) or $_REQUEST['itf_key'] <= 0) {
            $response = array(
                'state' => 'Error',
                'msg'   => 'invalid itf_key: '.$_REQUEST['itf_key']
            );
            echo json_encode($response);
            exit;
        }


        $qty=intval($_REQUEST['quantity']);


        if ( $qty <= 0) {
            $response = array(
                'state' => 'Error',
                'msg'   => 'invalid quantity: '.$_REQUEST['quantity'] .'=>',$qty
            );
            echo json_encode($response);
            exit;
        }


        $delivery_note = get_object('DeliveryNote', $_REQUEST['delivery_note_key']);
        $delivery_note->update_item_picked_quantity(
            array(
                'transaction_key' => $_REQUEST['itf_key'],
                'qty'             => $qty,
                'picker_key' => $_REQUEST['staff_key'],

            )
        );

        $response = array(
            'state' => 'OK',
            'data'  => $delivery_note->get_update_metadata()
        );
        echo json_encode($response);
        exit;
        break;

    case 'get_delivery_note_items':

        if (!isset($_REQUEST['delivery_note_key'])) {
            $response = array(
                'state' => 'Error',
                'msg'   => 'delivery_note_key needed'
            );
            echo json_encode($response);
            exit;
        }

        if (!is_numeric($_REQUEST['delivery_note_key']) or $_REQUEST['delivery_note_key'] <= 0) {
            $response = array(
                'state' => 'Error',
                'msg'   => 'invalid delivery_note_key: '.$_REQUEST['delivery_note_key']
            );
            echo json_encode($response);
            exit;
        }

        include_once 'class.PartLocation.php';
        $items = array();

        $sql =
            sprintf('select * from `Inventory Transaction Fact` ITF left join `Part Dimension` P on (P.`Part SKU`=ITF.`Part SKU`) left join `Location Dimension` L on (L.`Location Key`=ITF.`Location Key`)  where `Delivery Note Key`=%d ', $_REQUEST['delivery_note_key']);

        if ($result = $db->query($sql)) {
            foreach ($result as $row) {

                $part_location_data = array();
                $sql                = sprintf('select * from `Part Location Dimension`  where `Part SKU`=%d and `Location Key`=%d ', $row['Part SKU'], $row['Location Key']);

                if ($result2 = $db->query($sql)) {
                    foreach ($result2 as $row2) {
                        $part_location_data = $row2;
                    }
                }


                $part_location = new PartLocation($row['Part SKU'], $row['Location Key']);

                $items[] = array(
                    'item'          => $row,
                    'part_location' => $part_location_data
                );
            }
        } else {
            print_r($error_info = $db->errorInfo());
            //print "$sql\n";
            exit;
        }


        $response = array(
            'state' => 'OK',
            'data'  => $items
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


        $_response = search_locations($db, $account, $data, 'data');

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
            ), $editor['Date']
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
