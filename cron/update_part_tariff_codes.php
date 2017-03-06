<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 6 March 2017 at 22:00:19 GMT+8, Sanur, Bali, Indonesia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'class.Part.php';


update_tariff_code($db,'39199000%','3919908000');

update_tariff_code($db,'44191900%','4419190000');
update_tariff_code($db,'85234939%','8523492000');



function update_tariff_code($db,$tariff_old,$tariff_new) {
    $sql = sprintf(
        'SELECT `Part SKU` FROM `Part Dimension` WHERE `Part Reference`="FO-P7" ORDER BY `Part SKU` desc  '
    );
    $sql = sprintf(
        'SELECT `Part SKU` FROM `Part Dimension` where `Part Tariff Code` like %s ',prepare_mysql($tariff_old)
    );

    if ($result = $db->query($sql)) {
        foreach ($result as $row) {
            $part = new Part($row['Part SKU']);

           
                print $part->get('Reference')." ".$part->get('Part Tariff Code')."\n";
$part->update(array('Part Tariff Code'=>$tariff_new));

            


        }

    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }
}


?>
