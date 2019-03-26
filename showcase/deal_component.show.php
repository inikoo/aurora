<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 26 February 2019 at 13:49:40 GMT+8, Kuala Lumpur, Malaysia
 
 Copyright (c) 2016, Inikoo

 Version 3.0
*/

function get_deal_component_showcase($data, $smarty) {


    $deal_component = $data['_object'];
    if (!$deal_component->id) {
        return "";
    }

    $deal=get_object('Deal',$deal_component->get('Deal Key'));
    $campaign = get_object('DealCampaign', $deal->get('Deal Campaign Key'));

    switch ($campaign->get('Deal Campaign Code')){
        case 'CA':
            $category=get_object('Category',$deal->get('Deal Trigger Key'));
            $smarty->assign('category', $category);

            break;
    }



    $smarty->assign('campaign', $campaign);


    $smarty->assign('deal_component', $deal_component);

    return $smarty->fetch('showcase/deal_component.tpl');


}


?>