<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  27 December 2019  11:08::37  +0800, Kuala Lumpur Malaysia

 Copyright (c) 2019, Inikoo

 Version 3.0
*/

function get_delivery_notes_server_module() {
    return array(

        'parent'      => 'none',
        'parent_type' => 'none',
        'section'     => 'delivery_notes_server',
        'sections'    => array(


            'pending_delivery_notes' => array(

                'type'      => 'navigation',
                'label'     => _('Pending delivery notes'),
                'icon'      => 'stean',
                'reference' => 'pending_delivery_notes',
                'tabs'      => array(
                    'pending_delivery_notes' => array(
                        'label' => _('Delivery notes'),
                    ),


                )

            ),
            /*
                        'group_by_store' => array(
                            'type'      => 'navigation',
                            'label'     => _('Group by store'),
                            'icon'      => 'compress',
                            'reference' => 'delivery_notes/all/by_store',
                            'tabs'      => array(
                                'delivery_notes_group_by_store' => array()
                            )

                        ),

            */
            'delivery_notes'         => array(
                'type'      => 'navigation',
                'label'     => _('Delivery notes').' ('._('All').')',
                'icon'      => 'truck',
                'reference' => 'delivery_notes/all',
                'tabs'      => array(
                    'delivery_notes_server'         => array(
                        'icon'  => 'truck',
                        'label' => _('Delivery notes'),

                    ),
                    'delivery_notes_group_by_store' => array(
                        'label' => _('Group by store'),
                        'icon'  => 'compress',

                    )
                )

            ),


        )

    );
}