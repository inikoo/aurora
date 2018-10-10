<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 10 October 2018 at 13:03:22 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'utils/natural_language.php';
require_once 'utils/date_functions.php';
require_once 'utils/object_functions.php';
require_once 'utils/ip_geolocation.php';
require_once 'utils/parse_user_agent.php';
require_once 'utils/natural_language.php';


require_once 'utils/parse_email_status_codes.php';


$editor = array(
    'Author Name'  => '',
    'Author Alias' => '',
    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => 0,
    'Date'         => gmdate('Y-m-d H:i:s'),
    'Subject'      => 'System',
    'Subject Key'  => 0,
    'Author Name'  => 'Email tracker'
);


$sql = sprintf('select * from `Purchase Order Transaction Fact` ');

if ($result = $db->query($sql)) {
    foreach ($result as $row) {


        $supplier_part = get_object('Supplier_Part', $row['Supplier Part Key']);

        $sql = sprintf('select * from `Supplier Part Historic Dimension` where `Supplier Part Historic Key`=%d ', $row['Supplier Part Historic Key']);
        if ($result2 = $db->query($sql)) {
            if ($row2 = $result2->fetch()) {
               // print_r($row2);
                $submitted_units_per_sko=$row2['Supplier Part Historic Units Per Package'];

                $submitted_skos_per_carton=$row2['Supplier Part Historic Packages Per Carton'];
                $submited_unit_cost=$row2['Supplier Part Historic Unit Cost'];

            }else{
                exit('ups error');
            }
        } else {
            print_r($error_info = $db->errorInfo());
            print "$sql\n";
            exit;
        }

       // exit;
    }
} else {
    print_r($error_info = $db->errorInfo());
    print "$sql\n";
    exit;
}
