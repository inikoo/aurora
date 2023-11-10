<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 10 nov 2023 6:57 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once __DIR__.'/cron_common.php';

require_once 'class.Timeserie.php';
require_once 'class.Store.php';
require_once 'class.Invoice.php';
require_once 'class.Category.php';
require_once 'class.Agent.php';

require_once 'utils/date_functions.php';
require_once 'conf/timeseries.php';

$editor = array(
    'Author Name'  => '',
    'Author Alias' => '',
    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => 0,
    'Date'         => gmdate('Y-m-d H:i:s')
);

$print_est=true;
$where='';
$sql = sprintf("select count(*) as num from `Agent Dimension` $where");
if ($result = $db->query($sql)) {
    if ($row = $result->fetch()) {
        $total = $row['num'];
    } else {
        $total = 0;
    }
} else {
    print_r($error_info = $db->errorInfo());
    exit;
}

$lap_time0 = date('U');
$contador  = 0;



$timeseries = get_time_series_config();


$sql = sprintf('SELECT `Timeseries Key` FROM `Timeseries Dimension`  where `Timeseries Type`="AgentSales"');

if ($result = $db->query($sql)) {
    foreach ($result as $row) {

        $sql = sprintf(
            'delete from `Timeseries Record Dimension` where `Timeseries Record Timeseries Key`=%d ', $row['Timeseries Key']
        );
        $db->exec($sql);
    }
} else {
    print_r($error_info = $db->errorInfo());
    print "$sql\n";
    exit;
}
$sql = sprintf('delete FROM `Timeseries Dimension`  where `Timeseries Type`="AgentSales"');
$db->exec($sql);


$sql = sprintf('SELECT `Agent Key` FROM `Agent Dimension` order by `Agent Code` ');

if ($result = $db->query($sql)) {
    foreach ($result as $row) {

        $agent = new Agent($row['Agent Key']);


        $timeseries_data = $timeseries['Agent'];

        foreach ($timeseries_data as $time_series_data) {

            $editor['Date']             = gmdate('Y-m-d H:i:s');
            $time_series_data['editor'] = $editor;
            $agent->create_timeseries($time_series_data);




        }

        $contador++;
        $lap_time1 = date('U');

        if ($print_est) {
            print $agent->get('Code').'   '.percentage($contador, $total, 3)."  lap time ".sprintf("%.4f", ($lap_time1 - $lap_time0) / $contador)." EST  ".sprintf(
                    "%.4f", (($lap_time1 - $lap_time0) / $contador) * ($total - $contador) / 60
                )."m  ($contador/$total) \r";
        }
    }

} else {
    print_r($error_info = $db->errorInfo());
    exit($sql);
}


?>
