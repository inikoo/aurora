<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 1 February 2017 at 12:39:39 GMT+8, Cyberjaya, Malaydia
 Copyright (c) 2017, Inikoo

 Version 3

*/

require_once __DIR__.'/cron_common.php';
require_once 'class.Staff.php';
require_once 'utils/date_functions.php';



$sql = sprintf(
    'SELECT `Staff Key` FROM `Staff Dimension` WHERE `Staff Type`!="Contractor" and `Staff Currently Working`="Yes"   ORDER BY `Staff Key` DESC     '
);

if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        $employee = new Staff($row['Staff Key']);




        $to = date('Y-m-d', strtotime(date('Y', strtotime('now + 2 year')).'-'.$account->get('Account HR Start Year')));

                $from = date('Y-m-d');



      //  $from = date('Y-m-d', strtotime(date('Y', strtotime('now ')).'-'.$account->get('Account HR Start Year')));


       // $from='2021-04-01';
     //   print $from;



      //  print "$from $to";

                if ($from and $to) {


                    $dates = date_range($from, $to);
                    foreach ($dates as $date) {



                        $timesheet = $employee->create_timesheet(strtotime($date.' 00:00:00'), 'force');




                        $timesheet->update_number_clocking_records();
                        $timesheet->process_clocking_records_action_type();
                        if ($timesheet->get('Timesheet Clocking Records') > 0) {


                            $timesheet->update_clocked_time();
                            $timesheet->update_working_time();
                            $timesheet->update_unpaid_overtime();
                        }


                    }


                    //print_r($timesheet);
                    //exit;


                }

    }

} else {
    print_r($error_info = $db->errorInfo());
    exit;
}


?>
