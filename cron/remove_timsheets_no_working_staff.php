<?php

/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 10 December 2015 at 14:41:35 GMT Sheffield UK
 Copyright (c) 2015, Inikoo

 Version 3

*/



require_once 'common.php';
require_once 'class.Staff.php';
require_once 'class.Timesheet.php';
require_once 'class.Timesheet_Record.php';

require_once 'utils/date_functions.php';

$sql=sprintf("select `Staff Key` from `Staff Dimension` where `Staff Currently Working`='No'");

if ($result=$db->query($sql)) {
	foreach ($result as $row) {
		$employee=new Staff($row['Staff Key']);
		$to=$employee->get('Staff Valid To');

		if ($to) {
			$delete_from=date('Y-m-d', strtotime($employee->get('Staff Valid To').' + 1 day'  ));
			$sql=sprintf("delete from `Timesheet Record Dimension` where `Timesheet Record Staff Key`=%d and `Timesheet Record Date`>=%s and `Timesheet Record Type`!='ClockingRecord'  ",
				$employee->id,
				prepare_mysql($delete_from)
			);
			$db->exec($sql);


			$sql=sprintf("select `Timesheet Key` from `Timesheet Dimension` where `Timesheet Staff Key`=%d and `Timesheet Date`>=%s   ",
				$employee->id,
				prepare_mysql($delete_from)
			);

			if ($result3=$db->query($sql)) {

				foreach ($result3 as $data) {
					$timesheet=new Timesheet($data['Timesheet Key']);

					print $timesheet->get('Timesheet Date').' '.$timesheet->get('Timesheet Staff Key')."\n";

					$sql=sprintf("select count(*) as num  from `Timesheet Record Dimension` where `Timesheet Record Timesheet Key`=%d    ",
						$timesheet->id
					);

					print "$sql\n";
					if ($result2=$db->query($sql)) {

						if ($row2 = $result2->fetch()) {
							if ($row2['num']>0) {
								$timesheet->update_number_clocking_records();
								$timesheet->process_clocking_records_action_type();
								$timesheet->update_clocked_time();
								$timesheet->update_working_time();
								$timesheet->update_unpaid_overtime();

							}else {

								$sql=sprintf("delete from `Timesheet Dimension` where `Timesheet Key`=%d   ",
									$timesheet->id
								);
								$db->exec($sql);
								print "$sql\n";

							}
						}
					}else {
						print_r($error_info=$db->errorInfo());
						exit;

					}







				}

			}else {
				print_r($error_info=$db->errorInfo());
				exit;
			}



		}



	}

}else {
	print_r($error_info=$db->errorInfo());
	exit;
}

/*
$sql=sprintf("delete from `Timesheet Dimension`  where `Timesheet Staff Key`=%d and `Timesheet Date`=%s   ",
	$employee->id,
	prepare_mysql($delete_from)
);
$db->exec($sql);
*/

?>
