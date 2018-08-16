<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 15 August 2018 at 13:04:13 GMT+8, Kuta, Bali, Indonesia
 Copyright (c) 2018, Inikoo

 Version 3

*/

require_once 'common.php';

$editor = array(
    'Author Name'  => '',
    'Author Alias' => '',
    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => 0,
    'Date'         => gmdate('Y-m-d H:i:s'),
    'Subject'      => 'System',
    'Subject Key'  => 0,
    'Author Name'  => 'Script (set Agent to Prospect)'
);


$sql = sprintf('select `Prospect User Key`,`Prospect Key` from `Prospect Dimension` ');
if ($result = $db->query($sql)) {
    foreach ($result as $row) {


        $prospect = get_object('Prospect', $row['Prospect Key']);

        include_once('class.Sales_Representative.php');
        $sales_representative = new Sales_Representative(
            'find', array(
                      'Sales Representative User Key' => $row['Prospect User Key'],
                      'editor'                        => $editor
                  )
        );
        $sales_representative->fast_update(array('Sales Representative Prospect Agent' => 'Yes'));


        $prospect->fast_update(
            array(

                'Prospect Sales Representative Key' => $sales_representative->id

            )
        );


    }
} else {
    print_r($error_info = $db->errorInfo());
    print "$sql\n";
    exit;
}


?>
