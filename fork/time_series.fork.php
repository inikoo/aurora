<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Refurbished: 5 November 2016 at 18:04:29 GMT+8, Cyberjaya, Malaysia
 Created: 2016
 Copyright (c) 2016, Inikoo

 Version 3

*/

include_once 'utils/object_functions.php';

function fork_time_series($job) {


    if (!$_data = get_fork_data($job)) {
        return;
    }


    $db        = $_data['db'];
    $fork_data = $_data['fork_data'];
    $fork_key  = $_data['fork_key'];
    //$inikoo_account_code = $_data['inikoo_account_code'];


    switch ($fork_data['type']) {
        case 'timeseries':
            include_once 'class.Timeserie.php';
            $object         = get_object($fork_data['parent'], $fork_data['parent_key']);
            $object->editor = $fork_data['editor'];
            $object->create_timeseries($fork_data['time_series_data'], $fork_key);
            break;
        case 'isf':

            include_once 'class.Part.php';
            include_once 'class.Location.php';
            include_once 'class.Warehouse.php';
            include_once 'class.PartLocation.php';

            print_r($fork_data);


            $part = new Part($fork_data['key']);

            $from = $part->get('Part Valid From');
            $to   = ($part->get('Part Status') == 'Not In Use' ? $part->get('Part Valid To') : gmdate('Y-m-d H:i:s'));


            $number_dates = 0;
            $sql          = sprintf("SELECT count(*) AS num FROM kbase.`Date Dimension` WHERE `Date`>=date(%s) AND `Date`<=DATE(%s) ORDER BY `Date` DESC", prepare_mysql($from), prepare_mysql($to));

            if ($result = $db->query($sql)) {
                if ($row = $result->fetch()) {
                    $number_dates = $row['num'];
                }
            } else {
                print_r($error_info = $db->errorInfo());
                print "$sql\n";
                exit;
            }


            $sql = sprintf(
                "UPDATE `Fork Dimension` SET `Fork State`='In Process' ,`Fork Operations Total Operations`=%d,`Fork Start Date`=NOW(),`Fork Result`=%d  WHERE `Fork Key`=%d ", $number_dates, $part->id,
                $fork_key
            );

         //   print "$sql\n";

            $db->exec($sql);

            $index = 0;


            $sql = sprintf("DELETE FROM `Inventory Spanshot Fact` WHERE `Part SKU`=%d  AND (`Date`<%s  OR `Date`>%s  )", $part->sku, prepare_mysql($from), prepare_mysql($to));
            $db->exec($sql);


            $sql = sprintf("SELECT `Date` FROM kbase.`Date Dimension` WHERE `Date`>=date(%s) AND `Date`<=DATE(%s) ORDER BY `Date` DESC", prepare_mysql($from), prepare_mysql($to));


            if ($result = $db->query($sql)) {
                foreach ($result as $row) {
                    $index++;

                    $sql = sprintf(
                        "SELECT `Location Key`  FROM `Inventory Transaction Fact` WHERE  `Inventory Transaction Type` LIKE 'Associate' AND  `Part SKU`=%d AND `Date`<=%s GROUP BY `Location Key`",
                        $part->id, prepare_mysql($row['Date'].' 23:59:59')
                    );


                    if ($result3 = $db->query($sql)) {
                        foreach ($result3 as $row3) {
                            //  print $row['Date'].' '.$part->id.'_'.$row3['Location Key']."\r";

                            $part_location = new PartLocation(
                                $part->id.'_'.$row3['Location Key']
                            );
                            $part_location->update_stock_history_date($row['Date']);



                            $isf_records=0;
                            $sql=sprintf('select count(*) as num from `Inventory Spanshot Fact` where `Part SKU`=%d ',
                                         $state['_object']->id
                            );

                            if ($result=$db->query($sql)) {
                                if ($row = $result->fetch()) {
                                    $isf_records=number($row['num']);
                                }
                            }else {
                                print_r($error_info=$db->errorInfo());
                                print "$sql\n";
                                exit;
                            }


                            $part->update(
                                array(
                                    'Part ISF Updated' => gmdate('Y-m-d H:i:s'),
                                    'Part ISF Records' => $isf_records
                                ), 'no_history'

                            );

                        }
                    } else {
                        print_r($error_info = $db->errorInfo());
                        exit;
                    }


                    $sql = sprintf('SELECT `Warehouse Key` FROM `Warehouse Dimension`');
                    if ($result2 = $db->query($sql)) {
                        foreach ($result2 as $row2) {
                            $warehouse = new Warehouse($row2['Warehouse Key']);
                            $warehouse->update_inventory_snapshot($row['Date']);
                        }
                    } else {
                        print_r($error_info = $db->errorInfo());
                        exit;
                    }


                    if ($fork_key) {
                        $skip_every = 1;
                        if ($index % $skip_every == 0) {
                            $sql = sprintf(
                                "UPDATE `Fork Dimension` SET `Fork Operations Done`=%d  WHERE `Fork Key`=%d ", $index, $fork_key
                            );
                            $db->exec($sql);
                            print "$sql\n";
                        }

                    }


                }
            } else {
                print_r($error_info = $db->errorInfo());
                exit;
            }


            if ($fork_key) {

                $sql = sprintf(
                    "UPDATE `Fork Dimension` SET `Fork State`='Finished' ,`Fork Finished Date`=NOW(),`Fork Operations Done`=%d,`Fork Result`=%d WHERE `Fork Key`=%d ", $index, $part->id, $fork_key
                );

                print "$sql\n";
                $db->exec($sql);

            }

            exit;

            // $object->create_timeseries($fork_data['time_series_data'], $fork_key);
            break;

    }

    return false;
}


?>
