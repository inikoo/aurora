<?php

$_features_data = [];
try {
    $features_data = [
        'www.ancientwisdom.biz' => [
            'reviews'  => [
                'type' => 'reviews.io',
                'data' => 'ancient-wisdom-marketing-ltd'
            ],
            'features' => [
                [
                    'label' => 'No Minimum Order',
                    'icon'  => 'far fa-compress-wide',
                    "url"   => "https://www.ancientwisdom.biz/no_minimum_order",
                ],
                [
                    'label' => 'Free UK Delivery',
                    'icon'  => 'far fa-truck',
                    "url"   => "https://www.ancientwisdom.biz/shipping.sys",
                ],
                [
                    'label' => 'Volume Discounts',
                    'icon'  => 'fal fa-badge-percent',
                ],
                [
                    'label' => 'Over 5000 Products',
                    'icon'  => 'fal fa-shapes',
                    'url'   => "https://www.ancientwisdom.biz/catalogue.sys",
                ],
            ]
        ],
        'www.awartisan.es'=>[

        ]
    ];


    if (isset($features_data[$website->get('Website URL')])) {
        $_features_data = $features_data[$website->get('Website URL')];
    }
} catch (Exception $e) {
    //
}

$smarty->assign('features_data', $_features_data);