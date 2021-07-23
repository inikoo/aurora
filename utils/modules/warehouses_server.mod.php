<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  27 December 2019  11:35::08  +0800, Kuala Lumpur Malaysia

 Copyright (c) 2019, Inikoo

 Version 3.0
*/

function get_warehouses_server_module(): array {
    return array(
        'sections' => array(

            'warehouses' => array(

                'type'      => 'navigation',
                'label'     => _('Warehouses'),
                'title'     => _('Warehouses'),
                'icon'      => 'map-maker',
                'reference' => 'warehouses',
                'tabs'      => array(
                    'warehouses' => array()
                )
            ),


        )
    );
}