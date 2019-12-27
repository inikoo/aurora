<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  27 December 2019  11:24::01  +0800, Kuala Lumpur Malaysia

 Copyright (c) 2019, Inikoo

 Version 3.0
*/

function get_mailshots_server_module() {
    return array(

        'parent'      => 'none',
        'parent_type' => 'none',
        'section'     => 'mailshots',
        'sections'    => array(

            'email_communications' => array(
                'type'      => 'navigation',
                'label'     => _('Notifications.').' ('._('All stores').')',
                'icon'      => '',
                'reference' => 'customers/all/email_communications',
                'tabs'      => array(
                    'mailshots' => array()
                )
            ),

        )

    );;
}