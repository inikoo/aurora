<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 20-06-2019 16:33:26 MYT, Kuala Lumpur, Malaysia
 Copyright (c) 2019, Inikoo

 Version 3

*/

require_once 'common.php';

require 'utils/new_fork.php';

$sql = sprintf('select  `Image Key`  from `Image Dimension`  ');


if ($result2 = $db->query($sql)) {
    foreach ($result2 as $row2) {


        $image = get_object('image', $row2['Image Key']);
        $image->update_public_db();
        /*
                new_housekeeping_fork(
                    'au_housekeeping', array(
                    'type' => 'update_public_db',

                    'image_key' => $row2['Image Key']
                ), DNS_ACCOUNT_CODE
                );
        */

    }
}
