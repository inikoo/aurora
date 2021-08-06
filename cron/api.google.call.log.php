<?php
/**
 * Created by PhpStorm.
 * User: sasi
 * Date: 14/04/2019
 * Time: 11:43
 */

require_once __DIR__.'/cron_common.php';

$sql = "SELECT `Google API Call Details` FROM kbase.`Google API Call Dimension` ORDER BY `Google API Call Key` DESC LIMIT 1";
$stmt = $db->prepare($sql);
$stmt->execute();
$row = $stmt->fetchAll();

$Mask = "|%-30s |%-30s |%-30s |%-30s |\n";
printf($Mask,"Date Range","Code","Rows","Token");

$someArray = json_decode($row[0]['Google API Call Details'], true);

while($element = current($someArray)) {
    while($arrayRows = current($element)) {
        printf($Mask,key($someArray),key($arrayRows),$arrayRows[key($arrayRows)]['rows'],$arrayRows[key($arrayRows)]['token']);
        next($element);
         }
    next($someArray);
}