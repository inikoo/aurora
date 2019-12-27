<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  27 December 2019  12:09::38  +0800, Kuala Lumpur Malaysia

 Copyright (c) 2019, Inikoo

 Version 3.0
*/

function get_agent_client_deliveries_module() {
    return array(
        'sections' => array(
            'deliveries' => array(
                'type'      => 'navigation',
                'label'     => _("Deliveries"),
                'icon'      => 'truck',
                'reference' => 'delveries',
                'tabs'      => array(
                    'agent.deliveries' => array()
                )
            ),


            'agent_delivery' => array(
                'type' => 'object',
                'tabs' => array(
                    'agent_delivery.details'            => array(
                        'label' => _('Data'),
                        'icon'  => 'database'
                    ),
                    'agent_delivery.items_in_warehouse' => array(
                        'label' => _('Items in warehouse'),
                        'icon'  => 'warehouse-alt'
                    ),
                    'agent_delivery.items'              => array(
                        'label' => _('Items'),
                        'icon'  => 'bars'
                    ),

                    'agent_delivery.cartons' => array(
                        'label' => _('Boxes'),
                        'icon'  => 'boxes-alt'
                    ),


                    'agent_delivery.history' => array(
                        'label' => '',
                        'title' => _('History/Notes'),
                        'icon'  => 'road',
                        'class' => 'right icon_only'
                    ),

                )

            ),

            'deleted_order' => array(
                'type' => 'object',
                'tabs' => array(


                    'deleted.supplier.order.items'   => array(
                        'label' => _('Items'),
                        'icon'  => 'bars'
                    ),
                    'deleted.supplier.order.history' => array(
                        'label' => _('History/Notes'),
                        'icon'  => 'road',
                        'class' => 'right icon_only'
                    ),

                )

            ),


        )
    );
}