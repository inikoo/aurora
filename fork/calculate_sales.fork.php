<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Refurbished: 6 November 2016 at 10:33:44 GMT+8, Cyberjaya, Malaysia
 Created: 2016
 Copyright (c) 2016, Inikoo

 Version 3

*/

include_once 'utils/object_functions.php';

function fork_calculate_sales($job) {


    if (!$_data = get_fork_data($job)) {
        return;
    }


    $db        = $_data['db'];
    $fork_data = $_data['fork_data'];
    $fork_key  = $_data['fork_key'];
    //$inikoo_account_code = $_data['inikoo_account_code'];


    $object         = get_object($fork_data['parent'], $fork_data['parent_key']);
    $object->editor = $fork_data['editor'];


    print_r($fork_data);

    switch ($fork_data['scope']) {
        case 'X_To_Day':
            $intervals = array(
                'Total',
                'Year To Day',
                'Quarter To Day',
                'Month To Day',
                'Week To Day',
                'Today'
            );
            $result_metadata_field='Acc To Day Updated';
            $number_operations= count($intervals);
            break;
        case 'Ongoing_Intervals':
            $intervals = array(
                '1 Year',
                '1 Month',
                '1 Week',
            );
            $result_metadata_field='Acc Ongoing Intervals Updated';
            $number_operations= count($intervals);
            break;
        case 'Previous_Intervals':
            $intervals = array(
                'Last Year',
                'Last Month',
                'Last Week',
                'Yesterday'
            );
            $result_metadata_field='Acc Previous Intervals Updated';
            $number_operations= count($intervals)+2;
            break;
    }


    $sql = sprintf(
        "UPDATE `Fork Dimension` SET `Fork State`='In Process' ,`Fork Operations Total Operations`=%d,`Fork Start Date`=NOW(),`Fork Result Metadata`=%s  WHERE `Fork Key`=%d ",
       $number_operations,
        prepare_mysql($object->get($result_metadata_field)),
        $fork_key
    );
    //print "$sql\n";
    $db->exec($sql);

    $index = 0;

    if($fork_data['scope']== 'Previous_Intervals') {
        $object->update_previous_years_data();
        $index++;
        $sql = sprintf(
            "UPDATE `Fork Dimension` SET `Fork Operations Done`=%d ,`Fork Result Metadata`=%s WHERE `Fork Key`=%d ",
            $index,
            prepare_mysql($object->get($result_metadata_field)),
            $fork_key
        );

        $db->exec($sql);
        $object->update_previous_quarters_data();
        $index++;
        $sql = sprintf(
            "UPDATE `Fork Dimension` SET `Fork Operations Done`=%d ,`Fork Result Metadata`=%s WHERE `Fork Key`=%d ",
            $index,
            prepare_mysql($object->get($result_metadata_field)),
            $fork_key
        );

        $db->exec($sql);
    }

    foreach ($intervals as $interval) {


        $object->update_sales_from_invoices($interval, true, true);
        $index++;
        $sql = sprintf(
            "UPDATE `Fork Dimension` SET `Fork Operations Done`=%d ,`Fork Result Metadata`=%s WHERE `Fork Key`=%d ",
            $index,
            prepare_mysql($object->get($result_metadata_field)),
            $fork_key
        );

        $db->exec($sql);

    }


    $sql = sprintf(
        "UPDATE `Fork Dimension` SET `Fork State`='Finished' ,`Fork Finished Date`=NOW(),`Fork Operations Done`=%d WHERE `Fork Key`=%d ",
        $index,
        $fork_key
    );
    //print "$sql\n";
    $db->exec($sql);



    //$object->create_timeseries($fork_data['time_series_data'],$fork_key);


    return false;
}


?>
