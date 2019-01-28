<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 27 January 2019 at 14:38:07 GMT+8,  Kuala Lumpur, Malaysia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/

/**
 * @param $data
 * @param $smarty \Smarty
 *
 * @return string html
 * @throws \SmartyException
 */
function get_production_part_showcase($data, $smarty) {


    $production_part = $data['_object'];

    $part = $data['_object']->part;
    if (!$part->id) {
        return "";
    }


    $smarty->assign('production_part', $production_part);
    $smarty->assign('part', $part);

    return $smarty->fetch('showcase/production_part.tpl');


}



