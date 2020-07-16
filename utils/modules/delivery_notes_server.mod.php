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
                'icon'      => 'stream',
                'reference' => 'pending_delivery_notes',
                'tabs'      => array(
                    'pending_delivery_notes' => array(
                        'label' => _('Delivery notes'),
                    ),


                )

            ),

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

            'shippers'         => array(
                'type'      => 'navigation',
                'label'     => _('Shipping companies'),
                'icon'      => 'truck-loading',
                'reference' => 'shippers',
                'tabs'      => array(
                    'shipping_companies' => array(
                        'label' => _('Shipping companies'),
                        'title' => _('Shipping companies'),
                        'icon'  => 'truck-loading'
                    ),
                )

            ),

            'shipper.new' => array(
                'type' => 'new_object',
                'showcase'=>'new_shipper',
                'tabs' => array(
                    'shipper.new' => array(
                        'label' => _('New shipping company')
                    ),

                )

            ),
            'shipper'  => array(

                'type'      => 'object',
                'label'     => _('Shipping company'),
                'icon'      => '',
                'reference' => '',
                'tabs'      => array(
                    'shipper.details'      => array(
                        'label' => _('Settings'),
                        'title' => _('Settings'),
                        'icon'  => 'slider-h'
                    ),
                    'shipper.consignments' => array(
                        'label' => _('Consignments'),
                        'icon'  => 'truck'
                    ),

                    'shipper.history' => array(
                        'label' => _('History/Notes'),
                        'title' => _('History/Notes'),
                        'icon'  => 'road',
                        'class' => 'right icon_only'
                    ),

                )

            ),

        )

    );
}