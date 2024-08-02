<?php

$features_json = [];
try {
    $features_data = [
        'www.ancientwisdom.biz' => [
            'reviews'  => [
                'type' => 'reviews.io',
                'code' => '<script src="https://widget.reviews.io/badge-ribbon/dist.js"></script>'
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
        ]
    ];


    if (isset($features_data[$website->get('Website URL')])) {
        $features_json = $features_data[$website->get('Website URL')];
    }
} catch (Exception $e) {
    //
}

$smarty->assign('features_json', $features_json);