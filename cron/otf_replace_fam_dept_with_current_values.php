<?php


require_once __DIR__.'/cron_common.php';
$print_est = true;


//update `Order Transaction Fact` set `OTF Category Family Key`=NULL ,`OTF Category Department Key`=NULL

$where = '';
$sql = sprintf(
    "SELECT count(*) AS num FROM `Product Dimension` %s", $where
);
if ($result = $db->query($sql)) {
    if ($row = $result->fetch()) {
        $total = $row['num'];
    } else {
        $total = 0;
    }
}

$lap_time0 = date('U');
$contador  = 0;



$sql  = "select `Product ID` ,`Product Family Category Key`,`Product Department Category Key` from `Product Dimension` ";
$stmt = $db->prepare($sql);
$stmt->execute();
while ($row = $stmt->fetch()) {

    $sql = "update `Order Transaction Fact` set `OTF Category Family Key`=? ,`OTF Category Department Key`=?   where `Product ID`=?  ";
    $db->prepare($sql)->execute(
        array(
            $row['Product Family Category Key'],
            $row['Product Department Category Key'],
            $row['Product ID'],
        )
    );

    $db->exec($sql);

    $contador++;
    $lap_time1 = date('U');

    if ($print_est) {
        print 'P   '.percentage($contador, $total, 3)."  lap time ".sprintf("%.2f", ($lap_time1 - $lap_time0) / $contador)." EST  ".sprintf(
                "%.1f", (($lap_time1 - $lap_time0) / $contador) * ($total - $contador) / 3600
            )."h  ($contador/$total) \r";
    }


}


