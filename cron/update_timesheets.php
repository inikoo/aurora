<?php

/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 7 December 2015 at 21:08:47 GMT Sheffield UK
 Copyright (c) 2015, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'class.Staff.php';

require_once 'class.Timesheet.php';
require_once 'class.Timesheet_Record.php';

require_once 'utils/date_functions.php';

//$sql=sprintf('select `Timesheet Key` from `Timesheet Dimension` where `Timesheet Staff Key`=21 order by `Timesheet Date` desc');
//$sql=sprintf('select `Timesheet Key` from `Timesheet Dimension` where `Timesheet Key`=158458 ');
$sql=sprintf('select `Timesheet Key` from `Timesheet Dimension`  ');

if ($result=$db->query($sql)) {
	foreach ($result as $row) {
		$timesheet=new Timesheet($row['Timesheet Key']);

		$timesheet->update_number_clocking_records();
		$timesheet->process_clocking_records_action_type();
		$timesheet->update_clocked_time();
		$timesheet->update_working_time();
		$timesheet->update_unpaid_overtime();

        $test=$timesheet->data['Timesheet Clocked Time']-$timesheet->data['Timesheet Working Time']-$timesheet->data['Timesheet Breaks Time']-$timesheet->data['Timesheet Unpaid Overtime']-$timesheet->data['Timesheet Paid Overtime'];

    
        if($test!=0){
        print "Test fail:".$timesheet->id."\n";
        }

	}

}else {
	print_r($error_info=$db->errorInfo());
	exit;
}



?>
