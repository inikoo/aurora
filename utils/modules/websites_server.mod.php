<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  27 December 2019  11:33::33  +0800, Kuala Lumpur Malaysia

 Copyright (c) 2019, Inikoo

 Version 3.0
*/

function get_websites_server_module() {
    return array(

        'parent'      => 'none',
        'parent_type' => 'none',
        'section'     => 'websites',
        'sections'    => array(
            'websites' => array(
                'type'      => 'navigation',
                'label'     => _('Websites (All stores)'),
                'icon'      => '',
                'reference' => '',
                'tabs'      => array(
                    'websites' => array()
                )
            ),

        )

    );
}