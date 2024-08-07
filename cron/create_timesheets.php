<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 6 December 2015 at 21:39:44 GMT Sheffield UK
 Copyright (c) 2015, Inikoo

 Version 3

*/

require_once __DIR__.'/cron_common.php';
require_once 'class.Staff.php';
require_once 'utils/date_functions.php';

$mode='all';

if($mode=='this HR year'){

    if(date('m-d')!=$account->get('Account HR Start Year')){
        print "not start if HR date ".date('m-d').' != '.$account->get('Account HR Start Year')."\n";
        exit();
    }


}



$sql = sprintf(
    'SELECT `Staff Key` FROM `Staff Dimension` WHERE `Staff Type`!="Contractor"  and  `Staff Key`=114 ORDER BY `Staff Key` DESC '
);

if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        $employee = new Staff($row['Staff Key']);



       if($mode=='all') {
           $from = $employee->get('Staff Valid From');
       }else{

           $from = date(
               'Y-m-d', strtotime(
                          date('Y', strtotime('now ')).'-'.$account->get('Account HR Start Year')
                      )
           );
       }



        if ($employee->get('Staff Currently Working') == 'No') {
            $to = $employee->get('Staff Valid To');
        } else {
            if (!$from) {$from = date('Y-m-d');}



            $to = date(
                'Y-m-d', strtotime(
                    date('Y', strtotime('now + 1 year')).'-'.$account->get(
                        'Account HR Start Year'
                    )
                )
            );
        }


        print "$from $to\n";


        if ($from and $to) {


            $dates = date_range($from, $to);
            foreach ($dates as $date) {
                print "$date\n";
                $timesheet = $employee->create_timesheet(
                    strtotime($date.' 00:00:00'), 'force'
                );


                $timesheet->update_number_clocking_records();
                $timesheet->process_clocking_records_action_type();
                $timesheet->update_clocked_time();
                $timesheet->update_working_time();
                $timesheet->update_unpaid_overtime();


            }


        }

    }

} else {
    print_r($error_info = $db->errorInfo());
    exit;
}



