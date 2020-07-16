<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 13:28:11 MYT Tuesday, 14 July 2020 Kuala Lumpur , Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/


function get_shippers_data($country_code) {


    $shippers_data = array(

        'apc-overnight'       => [
            'label' => 'APC Overnight',
        ],
        'dhl'                 => [
            'label' => 'DHL',
        ],
        'fedex'               => [
            'label' => 'Fedex',
        ],
        'tnt'                 => [
            'label' => 'TNT',
        ],
        'ups'                 => [
            'label' => 'UPS',
        ],
        'aramex'              => [
            'label' => 'Aramex',
        ],
        'bpost-international' => [
            'label' => 'Bpost International',
        ],
        'dpd'                 => [
            'label' => 'DPD',
        ],
        'dpd-uk'              => [
            'label' => 'DPD UK',
        ],
        'dtdc'                 => [
            'label' => 'DTDC',
        ],
        'hermes'                 => [
            'label' => 'Hermesworld',
        ],
        'landmark-global'                 => [
            'label' => 'Landmark Global',
        ],
        'skynetworldwide'                 => [
            'label' => 'SkyNet Worldwide',
        ],
        'ups-mi'                 => [
            'label' => 'UPS Mail Innovation',
        ],
        'whistl'                 => [
            'label' => 'Whistl',
        ],
        'yodel'                 => [
            'label' => 'Yodel',
        ],
        'spain-correos-es'                 => [
            'label' => 'Correos Spain',
        ],
        'mondialrelay'                 => [
            'label' => 'Mondial Relay',
        ],
        'spanish-seur'                 => [
            'label' => 'Spanish Seur',
        ],
        'dpex'                 => [
            'label' => 'DPEX',
        ],
        'malaysia-post'                 => [
            'label' => 'Malaysia Post',
        ],
        'quantium'                 => [
            'label' => 'Quantium',
        ],
        'sf-express'                 => [
            'label' => 'S.F. Express',
        ],
        'sf-express-ibs'                 => [
            'label' => 'S.F. IBS',
        ],
        'sfb2c'                 => [
            'label' => 'S.F. International',
        ],


    );

    $country_shippers = [
        'GB' => [
            'apc-overnight',
            'aramex',
            'bpost-international',
            'dhl',
            'dpd',
            'dpd-uk' ,
            'dtdc',
            'fedex',
            'hermes' ,
            'landmark-global',
            'skynetworldwide',
            'tnt',
            'ups',
            'ups-mi',
            'whistl',
            'yodel' ,
        ],
        'ES' => [
            'aramex',
            'bpost-international',
            'dhl',
            'dpd',
            'dtdc',
            'fedex',
            'landmark-global',
            'mondialrelay',
            'skynetworldwide',
            'spain-correos-es',
            'spanish-seur',
            'tnt',
            'ups',
            'ups-mi',
        ],
        'SK' => [
            'aramex',
            'bpost-international',
            'dhl',
            'dpd',
            'dtdc',
            'fedex',
            'landmark-global',
            'skynetworldwide',
            'tnt',
            'ups',
            'ups-mi'
        ],
        'MY' => [
            'aramex',
            'bpost-international',
            'dhl',
            'dpd',
            'dpex',
            'dtdc',
            'fedex',
            'landmark-global',
            'malaysia-post',
            'quantium',
            'skynetworldwide',
            'tnt',
            'ups',
            'ups-mi'
        ],
        'CN' => [
            'aramex',
            'bpost-international',
            'dhl',
            'dpd',
            'dpex',
            'dtdc',
            'fedex',
            'landmark-global',
            'sf-express',
            'sf-express-ibs',
            'skynetworldwide',
            'tnt',
            'ups',
            'ups-mi'
        ],
        'IN' => [
            'aramex',
            'bpost-international',
            'dhl',
            'dpd',
            'dpex',
            'dtdc',
            'fedex',
            'landmark-global',
            'sf-express',
            'sf-express-ibs',
            'skynetworldwide',
            'tnt',
            'ups',
            'ups-mi'
        ]
        ];

    // print $country_code;

    if (!isset($country_shippers[$country_code])) {
        return [];
    } else {
        $shippers = [];
        foreach ($country_shippers[$country_code] as $shipper_code) {
            $shippers[$shipper_code] = $shippers_data[$shipper_code];
        }

        return $shippers;
    }

}


/*
 *
 *  'Form'=>[
                  'slug'=>[
                      'label'=>_('Slug'),
                      'type'=>'hidden',
                      'value'=>'apc-overnight',
                      'required'=>'Yes',
                      'description'=>'Courier Slug Accepts: apc-overnight'


                  ],
                  'description'=>[
                      'label'=>_('Description'),
                      'type'=>'string',
                      'required'=>'Yes',
                      'description'=>'The description of the account'


                  ],
                  'address'=>[
                     [
                         'contact_name'=>[]
                     ]
                  ]
                ],

                'Credentials'=>[
                    'password'=>[
                        'label'=>_('Password'),
                        'type'=>'string',
                        'required'=>'Yes'

                    ],
                    'user_email'=>[
                        'label'=>_('User email'),
                        'type'=>'string',
                        'required'=>'Yes'

                    ]
                ]
 */