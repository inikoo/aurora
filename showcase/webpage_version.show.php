<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 15 November 2016 at 16:30:30 GMT+8, Cyberjaya, Malaysia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/

function get_webpage_version_showcase($data, $smarty) {


    $webpage_version = $data['_object'];
    if (!$webpage_version->id) {
        return "";
    }


    $smarty->assign('webpage_version', $webpage_version);

    return $smarty->fetch('showcase/webpage_version.tpl');


}


?>