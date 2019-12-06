<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 22 February 2017 at 11:58:52 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3

*/

require_once 'common.php';

require_once 'class.Timeserie.php';
require_once 'class.Store.php';
require_once 'class.Invoice.php';
require_once 'class.Category.php';
require_once 'class.Supplier.php';

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

$timeseries = get_time_series_config();


$sql = sprintf('SELECT `Store Key` FROM `Store Dimension` ');

if ($result = $db->query($sql)) {
    foreach ($result as $row) {

        $store = new Store($row['Store Key']);

        $email_campaign_types_data = array(
            array(
                'Email Campaign Type Status' => 'Active',
                'Email Campaign Type Scope'  => 'Marketing',
                'Email Campaign Type Code'   => 'Invite Full Mailshot',
            )
        );


        foreach ($email_campaign_types_data as $email_campaign_type_data) {


            $email_campaign_type_data['Email Campaign Type Store Key'] = $store->id;


            $sql = sprintf(
                "INSERT INTO `Email Campaign Type Dimension` (%s) values (%s)", '`'.join('`,`', array_keys($email_campaign_type_data)).'`', join(',', array_fill(0, count($email_campaign_type_data), '?'))
            );


            $stmt = $db->prepare($sql);

            $i = 1;
            foreach ($email_campaign_type_data as $key => $value) {
                $stmt->bindValue($i, $value);
                $i++;
            }


            if ($stmt->execute()) {
                $email_campaign_type_key = $db->lastInsertId();
                $email_campaign_type     = get_object('email_campaign_type', $email_campaign_type_key);



                    $_metadata = array(

                        'Cool_Down_Days' =>180

                    );

                    // print_r($_metadata);


                    $email_campaign_type->fast_update(array('Email Campaign Type Metadata' => json_encode($_metadata)));





            } else {

            }


        }


    }

}





