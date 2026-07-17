<?php


require_once __DIR__.'/cron_common.php';
require_once 'utils/new_fork.php';
/** @var PDO $db */
/** @var Redis $redis */
/** @var Account $account */


$time = gmdate('H:i');



switch ($time) {
    case '00:00':



        update_staff_attendance($db);


        break;

    case '01:30':
        //just in case
        update_staff_attendance($db);
        break;



    default:


        break;
}




function update_staff_attendance($db) {
    $sql  = "SELECT `Staff Key` FROM `Staff Dimension` where `Staff Currently Working`='Yes'";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    while ($row = $stmt->fetch()) {
        /** @var Staff $staff */
        $staff = get_object('Staff', $row['Staff Key']);
        $staff->update_attendance();
    }


}

