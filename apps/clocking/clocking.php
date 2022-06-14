<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 13 Jun 2022 18:28:56 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 3.0
 */

/** @var integer $clocking_machine_key */
/** @var PDO $db */

$editor = array(
    'Author Name'  => '',
    'Author Alias' => '',
    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => 0,
    'Date'         => gmdate('Y-m-d H:i:s'),
    'Subject'      => 'System',
    'Subject Key'  => 0,
    'Author Name'  => 'Clocking machine V1 (PIN)'
);

require_once 'common.php';

if(!$clocking_machine_key){
    echo json_encode(
        [
            'status' => 400,
            'msg'    => 'App not authenticated'
        ]
    );
    exit;
}



if (empty($_POST['pin'])) {
    echo json_encode(
        [
            'status' => 400,
            'msg'    => 'PIN not provided'
        ]
    );
    exit;
}
$pin=$_POST['pin'];



$sql="select `Staff Key`,`Staff Alias` from `Staff Dimension`  where `Staff Clocking PIN`=? ";
$stmt = $db->prepare($sql);
$stmt->execute(
    [
       $pin
    ]
);
if ($row = $stmt->fetch()) {

    $staff=get_object('Staff',$row['Staff Key']);
    $source        = 'ClockingMachine';



    $data = array(
        'Timesheet Record Date'       => gmdate('Y-m-d H:i:s'),
        'Timesheet Record Source'     => $source,
        'Timesheet Record Source Key' => $clocking_machine_key,
        'Timesheet Record Type'       => 'ClockingRecord',
        'editor'                      => $editor
    );
    $staff->create_timesheet_record($data);



    if($staff->get('Staff Attendance Status')=='Work'){
        $msg='😀 Hello '.$row['Staff Alias'].', (Clocked in)';
        $status='in';
    }else{
        $msg='👋 Bye '.$row['Staff Alias'].', (Clocked out)';
        $status='out';

    }


    echo json_encode(
        [
            'status' => 200,
            'msg'    => $msg,
            'clocking_status'=>$status
        ]
    );
    exit;

}


echo json_encode(
    [
        'status' => 400,
        'msg'    => 'Invalid PIN'
    ]
);
exit;
