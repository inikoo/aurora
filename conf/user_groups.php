<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 14 November 2015 at 16:45:03 GMT, Sheffield UK

 Copyright (c) 2015, Inikoo

 Version 3.0
*/

// 24 aug 2019
// 9 will be transformed to store worker
// 11 will be divided nin pickers and packers 24, 25

$user_groups = array(

    1 => array(
        'Key' => 1,

        'Name'   => _('Administrator'),
        'View'   => '',
        'Edit'   => '',
        'Rights' => array(
            'AV',
            'AE',
            'UV',
            'UE',
            'EV',
            'EE',
            'URV'

        )
    ),

    6  => array(
        'Key'    => 6,
        'Name'   => _('Human Resources'),
        'View'   => '',
        'Edit'   => '',
        'Rights' => array(
            'EV',
            'EE',
            'ES',

        )

    ),
    20 => array(
        'Key'    => 20,
        'Name'   => _('Human Resources (Supervisor workers view)'),
        'View'   => '',
        'Edit'   => '',
        'Rights' => array(
            'EVW',
            'EV'

        )

    ),

    8  => array(
        'Key'    => 8,
        'Name'   => _('Buyer'),
        'View'   => '',
        'Edit'   => '',
        'Rights' => array(
            'BV',
            'BE',
            'PE',
            'PV'


        )

    ),
    21 => array(
        'Key'    => 21,
        'Name'   => _('Buyer supervisor'),
        'View'   => '',
        'Edit'   => '',
        'Rights' => array(
            'BV',
            'BE',
            'BS',
            'PE',
            'PV',
            'PS'
        )

    ),


    7 => array(
        'Key'               => 7,
        'Name'              => _('Production manager'),
        'View'              => '',
        'Edit'              => '',
        'Rights'            => array(
            'LV',
            'PV',
            'FV',
            'FE',
        ),
        'Productions_Scope' => true,
        'Warehouses_Scope'  => true


    ),

    4 => array(
        'Key'               => 4,
        'Name'              => _('Production operative'),
        'View'              => '',
        'Edit'              => '',
        'Rights'            => array('FV'),
        'Productions_Scope' => true,
        'Warehouses_Scope'  => true

    ),

    3 => array(
        'Key'              => 3,
        'Name'             => _('Warehouse worker'),
        'View'             => '',
        'Edit'             => '',
        'Rights'           => array(
            'SV',
            'LV',
            'PV',
            'PLV',
            'PLE',

        ),
        'Warehouses_Scope' => true

    ),

    22 => array(
        'Key'              => 22,
        'Name'             => _('Warehouse supervisor'),
        'View'             => '',
        'Edit'             => '',
        'Rights'           => array(
            'SV',
            'LV',
            'LE',
            'PV',

            'LS',

            'PLV',
            'PLE',
            'PLS',

        ),
        'Warehouses_Scope' => true

    ),


    23 => array(
        'Key'          => 23,
        'Name'         => _('Accounting'),
        'View'         => '',
        'Edit'         => '',
        'Rights'       => array(
            'SV',
            'OV',
            'CV',
            'IS'
        ),
        'Stores_Scope' => true,


    ),


    17 => array(
        'Key'              => 17,
        'Name'             => _('Goods out manager'),
        'View'             => '',
        'Edit'             => '',
        'Rights'           => array(
            'OV',
            'PV',
            'LV',
            'DNPiE',
            'PiV'

        ),
        'Warehouses_Scope' => true

    ),

    24 => array(
        'Key'              => 24,
        'Name'             => _('Picker'),
        'View'             => '',
        'Edit'             => '',
        'Rights'           => array(
            'OV',
            'PV',
            'LV'
        ),
        'Warehouses_Scope' => true

    ),
    25 => array(
        'Key'              => 25,
        'Name'             => _('Packer'),
        'View'             => '',
        'Edit'             => '',
        'Rights'           => array(
            'OV',
            'PV',
            'LV'
        ),
        'Warehouses_Scope' => true

    ),


    16 => array(
        'Key'              => 16,
        'Name'             => _('Customer services supervisor'),
        'View'             => '',
        'Edit'             => '',
        'Rights'           => array(
            'CS',
            'CV',
            'CE',
            'SV',
            'PV'

        ),
        'Stores_Scope'     => true,
        'Websites_Scope'   => true,
        'Warehouses_Scope' => true


    ),

    2 => array(
        'Key'              => 2,
        'Name'             => _('Customer Services'),
        'View'             => '',
        'Edit'             => '',
        'Rights'           => array(
            'CV',
            'CE',

            'SV',

            'PV',
            'OV',
            'OE',


        ),
        'Stores_Scope'     => true,
        'Websites_Scope'   => true,
        'Warehouses_Scope' => true


    ),


    18 => array(
        'Key'              => 16,
        'Name'             => _('Store supervisor'),
        'View'             => '',
        'Edit'             => '',
        'Rights'           => array(
            'CV',
            'SV',
            'MV',
            'ME',
            'WE',
            'WV',
            'SS',
            'MS',
            'WS'

        ),
        'Stores_Scope'     => true,
        'Websites_Scope'   => true,
        'Warehouses_Scope' => true


    ),

    9 => array(
        'Key'          => 9,
        'Name'         => _('Store Worker'),
        'View'         => '',
        'Edit'         => '',
        'Rights'       => array(
            'CV',
            'SV',
            'MV',
            'ME',
            'WE',
            'WV',


        ),
        'Stores_Scope' => true
    ),


    5  => array(
        'Key'    => 5,
        'Name'   => _('Sales'),
        'View'   => '',
        'Edit'   => '',
        'Rights' => array(
            'SV',
            'PV',
            'SRV'

        )

    ),
    26 => array(
        'Key'    => 26,
        'Name'   => _('Customers and Orders'),
        'View'   => '',
        'Edit'   => '',
        'Rights' => array(
            'CV',
            'OV',
            'IV',
            'DNV',
            'CRV'

        )

    ),
    28 => array(
        'Key'    => 27,
        'Name'   => _('Suppliers'),
        'View'   => '',
        'Edit'   => '',
        'Rights' => array(
            'BV',
            'BRV',

        )

    ),
    27 => array(
        'Key'    => 27,
        'Name'   => _('Inventory'),
        'View'   => '',
        'Edit'   => '',
        'Rights' => array(
            'LV',
            'PV',
            'IRV',

        )

    ),


    14 => array(
        'Key'    => 14,
        'Name'   => _('KPIs'),
        'View'   => '',
        'Edit'   => '',
        'Rights' => array(

            'KRV'
        )
    ),
    15 => array(
        'Key'    => 15,
        'Name'   => _('Users'),
        'View'   => '',
        'Edit'   => '',
        'Rights' => array(
            'EV',
            'UA'
        )

    ),


);
