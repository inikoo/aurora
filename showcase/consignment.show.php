<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 6:27 pm Wednesday, 6 January 2021 (MYT) Kuala Lumpur , Malaysia
 Copyright (c) 2021, Inikoo

 Version 3.0
*/

function get_consignment_showcase($data, $smarty) {


    if (!$data['_object']->id) {
        return "";
    }

    $smarty->assign('consignment', $data['_object']);

    $consignment = $data['_object'];


    $consignment->update_totals();


    $smarty->assign(
        'object_data', json_encode(
                         array(
                             'object' => $data['object'],
                             'key'    => $data['key'],

                             'tab' => $data['tab']
                         )
                     )

    );

    return $smarty->fetch('showcase/consignment.tpl');


}

