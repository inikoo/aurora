<?php

$features_json= [];
try {

    $features_data = [
        'www.ancientwisdom.biz' => [
            'reviwes' => [
                'title' => 'Reviews',
                'description' => 'This website has reviews',
                'code'=>''
            ],
        ]
    ];





    if(isset($features_data[$website->get('Website URL')])){
        $features_json= $features_data[$website->get('Website URL')];
    }




}catch (Exception $e) {
   //
}

$smarty->assign('features_json', $features_json);