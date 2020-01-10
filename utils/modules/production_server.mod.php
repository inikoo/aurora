<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  27 December 2019  11:10::11  +0800, Kuala Lumpur Malaysia

 Copyright (c) 2019, Inikoo

 Version 3.0
*/

function get_production_server_module() {
    return array(
        'sections' => array(

            'production.suppliers' => array(

                'type'      => 'navigation',
                'label'     => _('Suppliers'),
                'icon'      => 'hand-holding-box',
                'reference' => 'production/all',
                'tabs'      => array(
                    'production.suppliers' => array()
                )
            )

        )
    );
}