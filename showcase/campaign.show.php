<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 10 May 2016 at 13:15:26 GMT+8, Kuala Lumpur, Malaysia
 
 Copyright (c) 2016, Inikoo

 Version 3.0
*/

function get_campaign_showcase($data, $smarty) {


    $campaign = $data['_object'];
    if (!$campaign->id) {
        return "";
    }

//    $campaign->update_number_of_deals();
  //  $campaign->update_usage();

    $smarty->assign('campaign', $campaign);



    if($data['store']->get('Store Order Recursion Campaign Key')==$campaign->id){
        return $smarty->fetch('showcase/campaign_order_recursion.tpl');

    }else{
        return $smarty->fetch('showcase/campaign.tpl');

    }



}


?>