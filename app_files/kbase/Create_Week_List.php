<?php
// Move to cron to run
include_once 'common.php';
/** @var PDO $db */


$start_date = '2002-12-30';

$end_date = '2030-01-04';

$i    = 0;
$date = strtotime($start_date);
while ($date < strtotime($end_date)) {
    $i++;
    if (date('W', $date) == 1) {
        $__y = date('Y', strtotime(date('Y-m-d', $date).' +6 days'));
    } else {
        $__y = date('Y', strtotime(date('Y-m-d', $date).' +0 days'));
    }


    $sql = sprintf(
        "insert into kbase.`Week Dimension` values ('%s%s','%s','%s','%s%s','%s','%s','%s','%s','%s')", $__y, date('W', $date), date('Y-m-d', $date), date('Y-m-d', strtotime(date('Y-m-d', $date).' +6 days')), $__y, date('W', $date), date('W', $date),
        date('Y-m-d', strtotime(date('Y-m-d', $date).' +3 days')), date('Y-m-d', strtotime(date('Y-m-d', $date).' +6 days')), $__y, date('W', $date)
    );
    $db->exec($sql);
    if (date('W', $date) == 53) {
        $_year = date('Y', $date);
        if (date('d', $date >= 25)) {
            $__last_day = '';
            $sql       = sprintf("select  `Last Day` from kbase.`Week Dimension` where `Year Week`='%s02'", $_year);
            $stmt      = $db->prepare($sql);
            $stmt->execute(
                array()
            );
            while ($row = $stmt->fetch()) {
                $__last_day = $row['Last Day'];
            }
            $sql = sprintf("update kbase.`Week Dimension` set `Normalized Last Day`='%s' where `Year Week`='%d%02d'", $__last_day, $_year, 1);
            $db->exec($sql);

            for ($i = 2; $i <= 53; $i++) {
                $sql = sprintf("update kbase.`Week Dimension` set `Week Normalized`='%02d' where `Year Week`='%d%02d'", $i - 1, $_year, $i);
                $db->exec($sql);
                $sql = sprintf("update kbase.`Week Dimension` set `Year Week Normalized`=%s%02d where `Year Week`='%d%02d'", $_year, $i - 1, $_year, $i);
                $db->exec($sql);
            }
        } else {
            $sql = sprintf("update kbase.`Week Dimension` set `Normalized Last Day`='%s' where `Year Week`='%d%02d'", date('Y-m-d', strtotime(date('Y-m-d', $date).' +6 days')), $_year, 52);
            $db->exec($sql);
            $sql = sprintf("update kbase.`Week Dimension` set `Week Normalized`=%d where `Year Week`='%d%02d'", 52, $_year, 53);
            $db->exec($sql);
            $sql = sprintf("update kbase.`Week Dimension` set `Year Week Normalized`=%s%02d where `Year Week`='%d%02d'", $_year, 52, $_year, 53);
            $db->exec($sql);
        }

    }

    $date = strtotime(date('Y-m-d', $date).' +7 days');
    if ($i > 100000) {
        exit;
    }


}



