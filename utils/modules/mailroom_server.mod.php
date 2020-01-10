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

            'group_by_store' => array(
                'type'      => 'navigation',
                'label'     => _('Group by store'),
                'icon'      => 'compress',
                'reference' => 'mailroom/all/by_store',
                'tabs'      => array(
                    'mailroom_group_by_store' => array()
                )

            ),
        /*
            'notifications' => array(
                'type'      => 'navigation',
                'label'     => _('Notifications.').' ('._('All stores').')',
                'icon'      => '',
                'reference' => 'mailroom/all/notifications',
                'tabs'      => array(
                    'mailshots' => array()
                )
            ),
*/
        )

    );;
}