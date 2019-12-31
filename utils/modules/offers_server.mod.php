<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:   31 December 2020  14:36::22  +0800, Kuala Lumpur Malaysia

 Copyright (c) 2019, Inikoo

 Version 3.0
*/

function get_offers_server_module() {
    return array(

        'parent'      => 'none',
        'parent_type' => 'none',
        'section'     => 'offers',
        'sections'    => array(

            'group_by_store' => array(
                'type'      => 'navigation',
                'label'     => _('Group by store'),
                'icon'      => 'compress',
                'reference' => 'mailroom/all/by_store',
                'tabs'      => array(
                    'mailroom_group_by_store' => array()
                )


            )
        )
    );


}