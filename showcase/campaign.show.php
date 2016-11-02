<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 10 May 2016 at 13:15:26 GMT+8, Kuala Lumpur, Malaysia
 
 Copyright (c) 2016, Inikoo

 Version 3.0
*/

function get_campaign_showcase($data, $smarty) {


    $campaign = $data['_object'];
    if (!$campaign->id) {
        return "";
    }

    $smarty->assign('campaign', $campaign);

    return $smarty->fetch('showcase/campaign.tpl');


}


?>