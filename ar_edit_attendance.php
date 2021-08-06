<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 31 December 2016 at 12:58:51 CET, Mijas Costa, Spain
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'utils/ar_common.php';
require_once 'utils/object_functions.php';
require_once 'utils/natural_language.php';
require_once 'utils/parse_natural_language.php';


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
    case 'check_in':
        $data = prepare_values(
            $_REQUEST, array(
                         'staff_key' => array('type' => 'key'),
                         'source'    => array('type' => 'string'),


                     )
        );
        check_in($data, $editor);
        break;

    case 'check_out':
        $data = prepare_values(
            $_REQUEST, array(
                         'staff_key' => array('type' => 'key'),
                         'source'    => array('type' => 'string'),

                     )
        );


        check_out($data, $editor, $db);
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


function check_in($data, $editor) {

    $staff         = get_object('Staff', $data['staff_key']);
    $staff->editor = $editor;

    if ($data['source'] == 'Break') {

        $_source = $staff->properties('current_attendance_source');
        if ($_source == '') {
            $_source = 'Home';
        }
        //'ClockingMachine','Manual','API','System','WorkHome','WorkOutside','Break'
        //'Work','Home','Outside','Off','Break'
        switch ($_source) {
            case 'Home':
                $source = 'WorkHome';
                break;
            case 'Outside':
                $source = 'WorkOutside';
                break;
            case 'Break':
                $source = 'Break';
                break;
            default:
                $source = 'WorkHome';
        }
    }else{
        $source=$data['source'] ;
    }



    if ($data['source'] == '') {
        $data['source'] = 'WorkHome';
    }


    $time_record_data = array(
        'Timesheet Record Date'   => gmdate('Y-m-d H:i:s'),
        'Timesheet Record Source' => $source,
        'Timesheet Record Type'   => 'ClockingRecord',
        'editor'                  => $editor
    );


    // print_r($time_record_data);
   //  exit;

    $staff->create_timesheet_record($time_record_data);


    $response = array(
        'state'             => 200,
        'attendance_status' => $staff->get('Staff Attendance Status')


    );
    echo json_encode($response);


}


function check_out($data, $editor) {


    $staff         = get_object('Staff', $data['staff_key']);
    $staff->editor = $editor;


    if ($data['source'] == 'Break') {
        $source = 'Break';
    } else {
        $_source = $staff->properties('current_attendance_source');
        if ($_source == '') {
            $_source = 'Home';
        }
        //'ClockingMachine','Manual','API','System','WorkHome','WorkOutside','Break'
        //'Work','Home','Outside','Off','Break'
        switch ($_source) {
            case 'Home':
                $source = 'WorkHome';
                break;
            case 'Outside':
                $source = 'WorkOutside';
                break;
            case 'Break':
                $source = 'Break';
                break;
            default:
                $source = 'WorkHome';
        }
    }


    $time_record_data = array(
        'Timesheet Record Date'   => gmdate('Y-m-d H:i:s'),
        'Timesheet Record Source' => $source,
        'Timesheet Record Type'   => 'ClockingRecord',
        'editor'                  => $editor
    );

    //print_r($time_record_data);
    //exit;
    $staff->create_timesheet_record($time_record_data);


    $response = array(
        'state'             => 200,
        'attendance_status' => $staff->get('Staff Attendance Status')


    );
    echo json_encode($response);


}


