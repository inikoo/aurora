<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 04-07-2019 13:35:44 MYT Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'utils/object_functions.php';
require_once 'class.PartLocation.php';

$where = ' where `Part Status` = "Not in Use"  and `Part SKU`=365  ';
$where     = ' where `Part Status` = "Not in Use"  ';

$print_est = false;

$sql = sprintf("SELECT count(*) AS num FROM `Part Dimension` %s", $where);
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


$sql = sprintf('SELECT `Part SKU` FROM `Part Dimension`  %s ORDER BY `Part SKU`   ', $where);

if ($result = $db->query($sql)) {
    foreach ($result as $row) {


        $part = get_object('Part', $row['Part SKU']);

        $discontinued_date  = $part->get('Part Valid To');
        $_discontinued_date = gmdate('U', strtotime($discontinued_date.' +0:00'));


        $last_transaction_date = '';
        $running_stock         = 0;
        $sql                   = sprintf('select `Date` ,`Running Stock` from `Inventory Transaction Fact` where `Part SKU`=%d order by `Date` desc  limit 1', $part->id);
        if ($result = $db->query($sql)) {
            if ($row = $result->fetch()) {
                $last_transaction_date = $row['Date'];

            }
        }


        if ($last_transaction_date != '') {

            $_last_transaction_date = gmdate('U', strtotime($last_transaction_date.' +0:00'));

            if ($_last_transaction_date > $_discontinued_date) {
                $_discontinued_date = $_last_transaction_date ;
            }


        }
        $_discontinued_date=$_discontinued_date+1;

        $sql = sprintf(
            "SELECT `Location Key`  FROM `Inventory Transaction Fact` WHERE  `Inventory Transaction Type` LIKE 'Associate' AND  `Part SKU`=%d AND `Date`<=%s GROUP BY `Location Key`", $part->id, prepare_mysql($discontinued_date)
        );


        $locations_still_associated = array();
        $stock=0;

        if ($result3 = $db->query($sql)) {
            foreach ($result3 as $row3) {

                $part_location = new PartLocation($part->id.'_'.$row3['Location Key']);
                if ($part_location->exist_on_date(gmdate('Y-m-d H:i:s', $_discontinued_date))) {

                    $locations_still_associated[] = $part_location;

                }


                // $part_location->update_stock_history_date($row['Date']);


            }
        }


        // print_r($locations_still_associated);
        $date = gmdate('Y-m-d H:i:s', $_discontinued_date);


        $part->update_stock_run();



        $sql = sprintf(
            "SELECT `Running Stock`,`Running Stock Value`,`Running Cost per SKO` from `Inventory Transaction Fact` WHERE  `Date`<=%s AND `Part SKU`=%d and `Inventory Transaction Record Type` in ('Movement')  order by `Date` desc  ,`Inventory Transaction Key` desc  ", prepare_mysql($date), $part->id
        );
      //  print "$sql\n";

        if ($result = $db->query($sql)) {
            if ($row = $result->fetch()) {

            //print_r($row);
                $stock         = $row['Running Stock'];
                $value_per_sko = $row['Running Cost per SKO'];
            } else {
                $stock         = 0;
                $value_per_sko = $part->get('Part Cost');

            }
        }

        if($stock<0.001 or  $stock>-0.001){
            $stock=0;
        }

   //  print "Stock-> $stock\n";

        if (count($locations_still_associated) > 0) {




            print $part->id." missing dis associated Stock-> $stock  $date \n";


            //print "$date\n";

            //print $part->id."\n";



                foreach ($locations_still_associated as $part_location) {


                    $base_data = array(
                        'Date'         => $date,
                        'Note'         => _('Removing stock for discontinued parts'),
                        'Metadata'     => 'FIX_190704',
                        'History Type' => 'Admin'
                    );


                    if ($stock != 0) {

                        $stock = $stock * -1;

                        $value_change = $stock * $value_per_sko;

                        $record_type = 'Movement';
                        $section     = 'Other';
                        if ($stock > 0) {
                            $transaction_type = 'Lost';
                        } else {
                            $transaction_type = 'Other Out';

                        }
                        $details = _('Adjust stock part discontinued');

                        $sql = sprintf(
                            "INSERT INTO `Inventory Transaction Fact` (`Inventory Transaction Record Type`,`Inventory Transaction Section`,`Part SKU`,`Location Key`,`Inventory Transaction Type`,`Inventory Transaction Quantity`,`Inventory Transaction Amount`,`User Key`,`Note`,`Date`)
		VALUES (%s,%s,%d,%d,%s,%f,%.3f,%s,%s,%s)", prepare_mysql($record_type), prepare_mysql($section), $part_location->part_sku, $part_location->location_key, prepare_mysql($transaction_type), $stock, $value_change, 0, prepare_mysql($details, false),
                            prepare_mysql($date)

                        );
                        //print "$sql\n";
                        $db->exec($sql);

                    }


                    $sql = sprintf(
                        "INSERT INTO `Inventory Transaction Fact` (`Inventory Transaction Record Type`,`Inventory Transaction Section`,`Date`,`Part SKU`,`Location Key`,`Inventory Transaction Type`,`Inventory Transaction Quantity`,`Inventory Transaction Amount`,`Note`,`Metadata`,`History Type`)

		VALUES (%s,%s,%s,%d,%d,%s,0,0,%s,%s,%s)", "'Helper'", "'Other'", prepare_mysql($base_data['Date']), $part_location->part_sku, $part_location->location_key, "'Disassociate'", prepare_mysql($base_data['Note'], false), prepare_mysql($base_data['Metadata'], false),
                        prepare_mysql($base_data['History Type'], false)

                    );
                    //print "$sql\n";

                    $db->exec($sql);


                }


            $part->update_stock_run();

            $part->fast_update(array('Part Valid To' => gmdate('Y-m-d H:i:s', $_discontinued_date)));


        } elseif ($stock != 0) {


          //  print "Oh no stock wrong Stock: $stock\n";
          //  print $part->id."\n";
          //  print "$date\n";
            $stock = $stock * -1;


            $value_change = $stock * $value_per_sko;

            $record_type = 'Movement';
            $section     = 'Other';
            if ($stock > 0) {
                $transaction_type = 'Lost';
            } else {
                $transaction_type = 'Other Out';

            }
            $details = _('Adjust stock part discontinued (L)');

            $sql = sprintf(
                "INSERT INTO `Inventory Transaction Fact` (`Inventory Transaction Record Type`,`Inventory Transaction Section`,`Part SKU`,`Location Key`,`Inventory Transaction Type`,`Inventory Transaction Quantity`,`Inventory Transaction Amount`,`User Key`,`Note`,`Date`)
		VALUES (%s,%s,%d,%d,%s,%f,%.3f,%s,%s,%s)", prepare_mysql($record_type), prepare_mysql($section), $part->id, 1, prepare_mysql($transaction_type), $stock, $value_change, 0, prepare_mysql($details, false),
                prepare_mysql($date)

            );
            //print "$sql\n";
            $db->exec($sql);

            $part->update_stock_run();

            $part->fast_update(array('Part Valid To' =>$date));




        } else {
            continue;
        }


        //  print count($locations_still_associated);





        $contador++;
        $lap_time1 = gmdate('U');

        if ($print_est) {
            print 'Pa '.percentage($contador, $total, 3)."  lap time ".sprintf("%.2f", ($lap_time1 - $lap_time0) / $contador)." EST  ".sprintf(
                    "%.1f", (($lap_time1 - $lap_time0) / $contador) * ($total - $contador) / 3600
                )."h  ($contador/$total) \r";
        }

    }

} else {
    print_r($error_info = $db->errorInfo());
    exit;
}



