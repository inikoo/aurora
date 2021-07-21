<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 25 August 2016 at 19:05:00 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/


/**
 * @throws \SmartyException
 */
function fork_upload_edit($job) {

    global $account, $db;


    include_once 'class.User.php';
    include_once 'class.Upload.php';
    include_once 'utils/get_export_edit_template_fields.php';
    include_once 'utils/invalid_messages.php';
    include_once 'utils/object_functions.php';


    if (!$_data = get_fork_data($job)) {
        print "error reading fork data\n";

        return false;

    }

    $fork_data = $_data['fork_data'];
    $fork_key  = $_data['fork_key'];
    $db        = $_data['db'];


    $account = new Account($db);


    $user = new User('id', $fork_data['user_key']);

    $upload = new Upload('id', $fork_data['upload_key']);
    $upload->load_file_data();

    $editor = array(
        'Author Name'  => $user->data['User Alias'],
        'Author Alias' => $user->data['User Alias'],
        'Author Type'  => $user->data['User Type'],
        'Author Key'   => $user->data['User Parent Key'],
        'User Key'     => $user->id,
        'Upload Key'   => $fork_data['upload_key'],
        'Upload Label' => $upload->get('Upload File Name')
    );


    $upload->update(array('Upload State' => 'InProcess'));

    $sql = sprintf(
        "UPDATE `Fork Dimension` SET `Fork State`='In Process' ,`Fork Start Date`=NOW() WHERE `Fork Key`=%d ", $fork_key
    );
    $db->exec($sql);


    // print_r($upload);

    //exit($upload->get('Upload Object'));

    switch ($upload->get('Upload Object')) {
        case 'supplier_part':
            include_once 'class.SupplierPart.php';
            $valid_keys   = array('Supplier Part Key');
            $valid_fields = get_export_edit_template_fields('supplier_part');
            $object_name  = 'supplier_part';
            break;
        case 'production_part':
            include_once 'class.ProductionPart.php';
            $valid_keys   = array('Supplier Part Key');
            $valid_fields = get_export_edit_template_fields('supplier_part');
            $object_name  = 'supplier_part';
            break;
        case 'part':
            include_once 'class.Part.php';
            $valid_keys   = array('Part SKU');
            $valid_fields = get_export_edit_template_fields('part');
            $object_name  = 'part';
            break;
        case 'location':
            include_once 'class.Location.php';
            $valid_keys   = array('Location Key');
            $valid_fields = get_export_edit_template_fields('location');
            $object_name  = 'location';
            break;
        case 'product':
            include_once 'class.Product.php';
            $valid_keys   = array('Product ID');
            $valid_fields = get_export_edit_template_fields('product');
            $object_name  = 'product';
            break;
        case 'supplier':
            include_once 'class.Supplier.php';
            $valid_keys   = array('Supplier Key');
            $valid_fields = get_export_edit_template_fields('supplier');
            $object_name  = 'supplier';
            break;

        case 'warehouse_area':
            include_once 'class.WarehouseArea.php';
            $valid_keys   = array('Warehouse Area Key');
            $valid_fields = get_export_edit_template_fields('warehouse_area');
            $object_name  = 'warehouse_area';
            break;
        case 'prospect':
            include_once 'class.Prospect.php';
            $valid_keys   = array('Prospect Key');
            $valid_fields = get_export_edit_template_fields('prospect');
            $object_name  = 'prospect';
            break;
        case 'fulfilment_asset':
            include_once 'class.Fulfilment_Asset.php';
            $valid_keys   = array('Fulfilment Asset Key');
            $valid_fields = get_export_edit_template_fields('fulfilment_asset');
            $object_name  = 'fulfilment_asset';
            break;
        default:
            print 'error, Upload Object not set up, check: upload.edit.fork.php';

            return false;

    }


    $upload_metadata = $upload->get('Metadata');
    $fields = $upload_metadata['fields'];

    $key_index     = -1;
    $valid_indexes = array();


    foreach ($fields as $key => $value) {
        $value = _trim($value);

        if (preg_match('/^Id\s*:\s*(.+)$/', _trim($value), $matches)) {
            if (in_array($matches[1], $valid_keys)) {
                $key_index = $key;
            }
        } else {

            $_key = array_search($value, array_column($valid_fields, 'header'));
            if (is_numeric($_key)

            ) {


                $valid_indexes[$key] = $valid_fields[$_key]['name'];
            }

        }
    }


    if ($key_index < 0) {
        //$error_code     = 'missing_required_field';
        //$error_metadata = json_encode(array());

        $sql = sprintf(
            "UPDATE `Upload Record Dimension` SET `Upload Record Date`=%s ,`Upload Record State`='Error', `Upload Record Status`='Done'  WHERE `Upload Record Upload Key`=%d AND `Upload Record State`='InProcess' ", prepare_mysql(gmdate('Y-m-d H:i:s')), $upload->id
        );
        $db->exec($sql);
        update_upload_edit_stats($fork_key, $upload->id, $db);

        $sql = sprintf(
            "UPDATE `Fork Dimension` SET `Fork Finished Date`=NOW(),`Fork Result`=%s ,`Fork Operations Errors`=(`Fork Operations Total Operations`-`Fork Operations Done`-`Fork Operations No Changed`-`Fork Operations Errors`) WHERE `Fork Key`=%d ",
            prepare_mysql('error'), $fork_key
        );
        $db->exec($sql);

        $sql = sprintf(
            "UPDATE `Upload Dimension` SET `Upload State`='Finished' WHERE `Upload Key`=%d ", $upload->id
        );
        $db->exec($sql);

        print 'index not found';

        return false;

    }


    //---


    $sql = sprintf(
        "SELECT `Upload Record Key`, uncompress(`Upload Record Data`) AS data  FROM `Upload Record Dimension` WHERE `Upload Record Upload Key`=%d AND `Upload Record Status`='InProcess' ", $upload->id
    );


    if ($result = $db->query($sql)) {
        foreach ($result as $row) {


            $sql = sprintf(
                'SELECT `Fork State` FROM `Fork Dimension` WHERE `Fork Key`=%d', $fork_key
            );


            if ($result2 = $db->query($sql)) {
                if ($row2 = $result2->fetch()) {
                    if ($row2['Fork State'] == 'Cancelled') {

                        $sql = sprintf(
                            "UPDATE `Upload Record Dimension` SET `Upload Record Date`=%s ,`Upload Record State`='Cancelled', `Upload Record Status`='Done'  WHERE `Upload Record Upload Key`=%d AND `Upload Record State`='InProcess' ",
                            prepare_mysql(gmdate('Y-m-d H:i:s')), $upload->id
                        );
                        $db->exec($sql);
                        update_upload_edit_stats($fork_key, $upload->id, $db);

                        $sql = sprintf(
                            "UPDATE `Fork Dimension` SET `Fork Finished Date`=NOW(),`Fork Cancelled Date`=NOW(),`Fork Result`=%s `Fork Operations Cancelled`=(`Fork Operations Total Operations`-`Fork Operations Done`-`Fork Operations No Changed`-`Fork Operations Errors`) WHERE `Fork Key`=%d ",
                            prepare_mysql('imported cancelled'), $fork_key
                        );
                        $db->exec($sql);

                        $sql = sprintf(
                            "UPDATE `Upload Dimension` SET `Upload State`='Cancelled' WHERE `Upload Key`=%d ", $upload->id
                        );
                        $db->exec($sql);


                        return false;
                    }
                }
            }


            $record_data = json_decode($row['data'], true);


            if (strtolower($record_data[$key_index]) == 'new') {


                $fields_data = array();


                foreach ($valid_indexes as $index => $field) {


                    $fields_data[$field] = trim($record_data[$index]);


                }


                if ($upload->get('Upload Parent') == 'supplier_product') {

                    $sql = sprintf(
                        "UPDATE `Fork Dimension` SET `Fork State`='Finished' ,`Fork Finished Date`=NOW(),`Fork Result`=%s WHERE `Fork Key`=%d ", prepare_mysql('imported'), $fork_key
                    );

                    $db->exec($sql);

                    $sql = sprintf(
                        "UPDATE `Upload Dimension` SET  `Upload State`='Finished'  WHERE `Upload Key`=%d ", $upload->id
                    );
                    $db->exec($sql);

                    return true;
                }


                $_data = array(
                    'parent'            => $upload->get('Upload Parent'),
                    'parent_key'        => $upload->get('Upload Parent Key'),
                    'object'            => $upload->get('Upload Object'),
                    'upload_record_key' => $row['Upload Record Key'],
                    'fields_data'       => $fields_data
                );


                if ($upload->get('Upload Object') == 'supplier_part') {

                    if (isset($fork_data['allow_duplicate_part_reference'])) {
                        $_data['allow_duplicate_part_reference'] = $fork_data['allow_duplicate_part_reference'];

                    } else {
                        $_data['allow_duplicate_part_reference'] = 'No';
                    }

                }


                new_object($db, $editor, $_data, $upload, $fork_key);


            } else {
                if ($record_data[$key_index] == '') {

                    $sql = sprintf(
                        "UPDATE `Upload Record Dimension` SET `Upload Record Date`=%s ,`Upload Record Message Code`=%s ,`Upload Record Message Metadata`=%s ,`Upload Record Status`='Done' ,`Upload Record State`=%s  WHERE `Upload Record Key`=%d ",
                        prepare_mysql(gmdate('Y-m-d H:i:s')), prepare_mysql('skip'), prepare_mysql(''), prepare_mysql('Warning'), $row['Upload Record Key']
                    );
                    $db->exec($sql);
                    update_upload_edit_stats($fork_key, $upload->id, $db);
                    continue;

                } else {

                    $object         = get_object($object_name, $record_data[$key_index]);
                    $object->editor = $editor;

                    if (!$object->id) {

                        $sql = sprintf(
                            "UPDATE `Upload Record Dimension` SET `Upload Record Date`=%s ,`Upload Record Message Code`=%s ,`Upload Record Message Metadata`=%s ,`Upload Record Status`='Done' ,`Upload Record State`=%s  WHERE `Upload Record Key`=%d ",
                            prepare_mysql(gmdate('Y-m-d H:i:s')), prepare_mysql('object_not_found'), prepare_mysql(''), prepare_mysql('Error'), $row['Upload Record Key']
                        );
                        $db->exec($sql);
                        update_upload_edit_stats($fork_key, $upload->id, $db);
                        continue;

                    }


                    $errors          = 0;
                    $updated_records = 0;
                    $msg             = '';
                    $message_code    = '';

                    //print_r($object->data);

                    //print $object->id;

                    foreach ($valid_indexes as $index => $field) {

                        // print "$field ->".$record_data[$index]."  ".$object->get_object_name()." \n";

                        $object->update(array($field => trim($record_data[$index])));


                        if ($object->updated) {
                            //print "$field ".$record_data[$index]." ";
                            //print "updated\n";
                            $msg .= '<i class="fa fa-check success fa-fw" aria-hidden="true" title="'.$field.'"></i>, ';
                            $updated_records++;
                        } elseif ($object->error) {
                            //print "$field ".$record_data[$index]." ";
                            //print "error ".$object->msg."\n";

                            $msg .= '<span class="error"><i class="fa fa-exclamation-circle fa-fw" aria-hidden="true" title="'.$field.'"></i> '.$object->msg.'</span>, ';
                            $errors++;
                            $message_code .= $object->msg;


                            // print_r($object);

                        } else {
                            //print "$field ".$record_data[$index]." ";
                            //print "no_change \n";
                            $msg .= '<i class="fa fa-minus very_discreet fa-fw" aria-hidden="true" title="'.$field.'"></i>, ';
                        }


                    }


                    // print "\n";
                    $msg = preg_replace('/, $/', '', $msg);


                    if ($errors) {
                        // exit();
                        if ($updated_records == 0) {
                            $record_state = 'Error';
                        } else {
                            $record_state = 'Warning';
                        }


                    } else {
                        $record_state = 'OK';
                    }

                    if ($errors == 0 and $updated_records == 0) {
                        $message_code = 'no_change';
                        $record_state = 'NoChange';
                    } else {
                        //exit();
                        if ($updated_records and $errors == 0) {
                            $message_code = 'updated';
                        }

                    }


                    $sql = sprintf(
                        "UPDATE `Upload Record Dimension` SET `Upload Record Date`=%s ,`Upload Record Message Code`=%s ,`Upload Record Message Metadata`=%s ,`Upload Record Status`='Done' ,`Upload Record State`=%s,`Upload Record Object Key`=%d WHERE `Upload Record Key`=%d ",
                        prepare_mysql(gmdate('Y-m-d H:i:s')), prepare_mysql($message_code), prepare_mysql($msg), prepare_mysql($record_state), $object->id, $row['Upload Record Key']

                    );

                    //print "$sql\n";
                    $db->exec($sql);

                }
            }

            update_upload_edit_stats($fork_key, $upload->id, $db);


        }
    }


    update_upload_edit_stats($fork_key, $upload->id, $db);


    $sql = sprintf(
        "UPDATE `Fork Dimension` SET `Fork State`='Finished' ,`Fork Finished Date`=NOW(),`Fork Result`=%s WHERE `Fork Key`=%d ", prepare_mysql('imported'), $fork_key
    );

    $db->exec($sql);

    $sql = sprintf(
        "UPDATE `Upload Dimension` SET  `Upload State`='Finished'  WHERE `Upload Key`=%d ", $upload->id
    );
    $db->exec($sql);

    return true;


}


function update_upload_edit_stats($fork_key, $upload_key, $db) {

    $elements = array(
        'InProcess' => 0,
        'OK'        => 0,
        'Error'     => 0,
        'Warning'   => 0,
        'Cancelled' => 0,
        'NoChange'  => 0
    );

    $sql = sprintf(
        'SELECT `Upload Record State`,count(*) AS num  FROM `Upload Record Dimension` WHERE `Upload Record Upload Key`=%d GROUP BY `Upload Record State`', $upload_key
    );
    if ($result = $db->query($sql)) {
        foreach ($result as $row) {
            $elements[$row['Upload Record State']] = $row['num'];
        }
    }

    $sql = sprintf(
        'UPDATE `Fork Dimension` SET `Fork Operations Done`=%d,`Fork Operations No Changed`=%d,  `Fork Operations Errors`=%d ,`Fork Operations Cancelled`=%d WHERE `Fork Key`=%d ', ($elements['OK'] + $elements['Warning']), $elements['NoChange'], $elements['Error'],
        $elements['Cancelled'], $fork_key
    );

    $db->exec($sql);

    $sql = sprintf(
        'UPDATE `Upload Dimension` SET `Upload OK`=%d,`Upload No Change`=%d,`Upload Errors`=%d ,`Upload Warnings`=%d WHERE `Upload Key`=%d ', $elements['OK'], $elements['NoChange'], $elements['Error'], $elements['Warning'], $upload_key
    );
    $db->exec($sql);

}


function new_object($db, $editor, $data, $upload, $fork_key) {

    $error = false;
    $error_code='';
    $error_metadata='';

    $parent = get_object($data['parent'], $data['parent_key']);


    $editor['Date'] = gmdate('Y-m-d H:i:s');

    $parent->editor = $editor;

    switch ($data['object']) {

        case 'product':
            include_once 'class.Product.php';


            if ($parent->get_object_name() != 'Store') {


                $sql = sprintf(
                    "UPDATE `Upload Record Dimension` SET `Upload Record Date`=%s ,`Upload Record State`='Error', `Upload Record Status`='Done'  WHERE `Upload Record Upload Key`=%d AND `Upload Record State`='InProcess' ", prepare_mysql(gmdate('Y-m-d H:i:s')),
                    $upload->id
                );
                $db->exec($sql);
                update_upload_edit_stats($fork_key, $upload->id, $db);

                $sql = sprintf(
                    "UPDATE `Fork Dimension` SET `Fork Finished Date`=NOW(),`Fork Result`=%s `Fork Operations Errors`=(`Fork Operations Total Operations`-`Fork Operations Done`-`Fork Operations No Changed`-`Fork Operations Errors`) WHERE `Fork Key`=%d ",
                    prepare_mysql('error'), $fork_key
                );
                $db->exec($sql);

                $sql = sprintf(
                    "UPDATE `Upload Dimension` SET `Upload State`='Finished' WHERE `Upload Key`=%d ", $upload->id
                );
                $db->exec($sql);


                return false;


            }
            /**
             * @var $store \Store
             */
            $store = $parent;

            $store->fork = true;

            $object = $store->create_product($data['fields_data']);


            if ($store->error) {
                $error          = $store->error;
                $error_metadata = ($store->error_metadata ?? '');
                $error_code     = $store->error_code;
                // print_r($parent);

            }

            break;
        case 'supplier_part':
        case 'Supplier Part':

            include_once 'class.SupplierPart.php';

            /**
             * @var $parent \Supplier
             */
            $object = $parent->create_supplier_part_record(
                $data['fields_data'], $data['allow_duplicate_part_reference']
            );
            if ($parent->error) {
                $error          = $parent->error;
                $error_metadata = ($parent->error_metadata ?? '');
                $error_code     = $parent->error_code;
            }

            break;

        case 'Manufacture_Task':
            include_once 'class.Manufacture_Task.php';
            $object = $parent->create_manufacture_task($data['fields_data']);

            if (!$parent->new_manufacture_task) {

                $response = array(
                    'state' => 400,
                    'msg'   => $parent->msg

                );
                echo json_encode($response);
                exit;
            }
            break;
        case 'User':
            include_once 'class.User.php';

            $parent->get_user_data();
            $object = $parent->create_user($data['fields_data']);


            break;
        case 'Location':
        case 'location':

            include_once 'class.Location.php';
            $object = $parent->create_location(
                $data['fields_data']
            );
            if ($parent->error) {
                $error          = $parent->error;
                $error_metadata = ($parent->error_metadata ?? '');
                $error_code     = $parent->error_code;
            }


            break;
        case 'Customer':
            include_once 'class.Customer.php';
            /**
             * @var $parent \Store
             */
            $_result = $parent->create_customer($data['fields_data']);
            $object  = $_result['Customer'];

            break;
        case 'Supplier':
            include_once 'class.Supplier.php';
            $object = $parent->create_supplier($data['fields_data']);

            break;
        case 'Contractor':
            include_once 'class.Staff.php';

            $data['fields_data']['Staff Type'] = 'Contractor';

            $object = $parent->create_staff($data['fields_data']);

            break;
        case 'Staff':
        case 'employee':
            include_once 'class.Staff.php';

            $object = $parent->create_staff($data['fields_data']);


            break;
        case 'API_Key':
            include_once 'class.API_Key.php';

            $object = $parent->create_api_key($data['fields_data']);

            break;
        case 'Timesheet_Record':
            include_once 'class.Timesheet_Record.php';
            /**
             * @var $parent \Staff|\Timesheet
             */
            $object = $parent->create_timesheet_record($data['fields_data']);
            // if (!$parent->error) {
            //$updated_data = array(
            //    'Timesheet_Clocked_Hours' => $parent->get('Clocked Hours')
            //);
            //}
            break;
        case 'warehouse_area':
            include_once 'class.WarehouseArea.php';
            $object = $parent->create_warehouse_area(
                $data['fields_data']
            );
            if ($parent->error) {
                $error          = $parent->error;
                $error_metadata = ($parent->error_metadata ?? '');
                $error_code     = $parent->error_code;
            }
            break;
        case 'prospect':
            include_once 'class.Prospect.php';
            /**
             * @var $parent \Store
             */
            $object = $parent->create_prospect(
                $data['fields_data']
            );
            if ($parent->error) {
                $error          = $parent->error;
                $error_metadata = ($parent->error_metadata ?? '');
                $error_code     = $parent->error_code;
            }
            break;
        case 'fulfilment_asset':
            /**
             * @var $parent \Fulfilment_Delivery
             */



            $object = $parent->create_fulfilment_asset(
                $data['fields_data']
            );
            if ($parent->error) {
                $error          = $parent->error;
                $error_metadata = ($parent->error_metadata ?? '');
                $error_code     = $parent->error_code;
            }
            break;
        default:

            print 'warning: object '.$data['object'].' still not configured';

            return false;


    }

    //print $object->get('Code')."\n";
    if ($error) {

        $sql = sprintf(
            "UPDATE `Upload Record Dimension` SET `Upload Record Date`=%s , `Upload Record Message Code`=%s , `Upload Record Message Metadata`=%s ,`Upload Record Status`='Done' ,`Upload Record State`='Error' WHERE `Upload Record Key`=%d ",
            prepare_mysql(gmdate('Y-m-d H:i:s')), prepare_mysql($error_code), prepare_mysql($error_metadata), $data['upload_record_key']

        );


        $db->exec($sql);

        return false;


    } else {

        $sql = sprintf(
            "UPDATE `Upload Record Dimension` SET `Upload Record Date`=%s ,`Upload Record Message Code`='created' ,`Upload Record Status`='Done' ,`Upload Record State`='OK',`Upload Record Object Key`=%d WHERE `Upload Record Key`=%d ",
            prepare_mysql(gmdate('Y-m-d H:i:s')), $object->id, $data['upload_record_key']
        );
        $db->exec($sql);


        return $object->id;
    }


}



