<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  27 December 2019  11:24::01  +0800, Kuala Lumpur Malaysia

 Copyright (c) 2019, Inikoo

 Version 3.0
*/

function get_mailroom_server_module() {
    return array(

        'parent'      => 'none',
        'parent_type' => 'none',
        'section'     => 'mailroom',
        'sections'    => array(

            'notifications' => array(
                'type'      => 'navigation',
                'label'     => _('Notifications.').' ('._('All stores').')',
                'icon'      => '',
                'reference' => 'mailroom/all/notifications',
                'tabs'      => array(
                    'mailshots' => array()
                )
            ),

        )

    );;
}