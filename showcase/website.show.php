<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 24 May 2017 at 11:45:44 GMT+8, Damansara, Malaysia

 Copyright (c) 2017, Inikoo

 Version 3.0
*/

function get_website_showcase($data, $smarty) {


    $website = $data['_object'];
    if (!$website->id) {
        return "";
    }

    $smarty->assign('website', $website);

    if($website->get('Website State')==''){
        return $smarty->fetch('showcase/website.to_launch.tpl');

    }else{
        return $smarty->fetch('showcase/website.tpl');

    }




}


?>