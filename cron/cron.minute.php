<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 22 June 2018 at 15:08:31 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'utils/new_fork.php';


send_periodic_email_mailshots($db, $account);


function send_periodic_email_mailshots($db, $account) {


    $sql = sprintf('select `Email Campaign Type Code`,`Email Campaign Type Metadata`,`Email Campaign Type Key` from `Email Campaign Type Dimension` where `Email Campaign Type Status`="Active" ');

    if ($result = $db->query($sql)) {
        foreach ($result as $row) {

            if ($row['Email Campaign Type Metadata'] != '') {
                $metadata = json_decode($row['Email Campaign Type Metadata'], true);

                if (isset($metadata['Schedule'])) {

                    date_default_timezone_set($metadata['Schedule']['Timezone']);


                    if ($metadata['Schedule']['Time'] == date('H:i') ) {
                        if (isset($metadata['Schedule']['Days'])) {
                            if ($metadata['Schedule']['Days'][iso_860_to_day_name(date('N'))] == 'Yes') {




                              

                               new_housekeeping_fork(
                                    'au_housekeeping', array(
                                    'type'     => 'create_and_send_mailshot',
                                    'email_template_type_key' => $row['Email Campaign Type Key'],

                                ), $account->get('Account Code')
                                );

                              // exit;

                            }
                        }
                    }


                }


            }


        }
    } else {
        print_r($error_info = $db->errorInfo());
        print "$sql\n";
        exit;
    }


}


function iso_860_to_day_name($num) {
    switch ($num) {
        case 1:
            return 'Monday';
            break;
        case 2:
            return 'Tuesday';
            break;
        case 3:
            return 'Wednesday';
            break;
        case 4:
            return 'Thursday';
            break;
        case 5:
            return 'Friday';
            break;
        case 6:
            return 'Saturday';
            break;
        case 7:
            return 'Sunday';
            break;
        default:
            break;
    }
}


?>
