<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 14 Jun 2021 02:03  +0800, Kuala Lumpur Malaysia

 Copyright (c) 2019, Inikoo

 Version 3.0
*/

function get_fulfilment_module() {
    return array(
        'sections' => array(


            'dashboard' => array(
                'type'      => 'left_button',
                'title'     => _('Dashboard'),
                'icon'      => 'tachometer',
                'reference' => 'fulfilment/dashboard',
                'tabs'      => array(
                    'fulfilment.dashboard' => array('label' => _('Dashboard'))

                )
            ),


            'fulfilment_parts' => array(
                'type'      => 'navigation',
                'label'     => _('Stored items'),
                'icon'      => 'box-heart',
                'reference' => 'fulfilment/%d/parts',
                'tabs'      => array(



                    'fulfilment.fulfilment_parts' => array(
                        'label' => _('Stored items'),
                        'quantity_data' => array(
                            'object' => '_object',
                            'field'  => 'Number Parts'
                        ),

                    )
                )


            ),
            'fulfilment_part'  => array(
                'type'           => 'object',
                'subtabs_parent' => array(

                    'fulfilment_part.supplier.orders'     => 'fulfilment_part.purchase_orders',
                    'fulfilment_part.supplier.deliveries' => 'fulfilment_part.purchase_orders',
                ),

                'tabs' => array(


                    'fulfilment_part.details' => array(
                        'label' => _('Data'),
                        'icon'  => 'database'
                    ),
                    /*
                    'fulfilment_part.batch'   => array(
                        'label' => _('Batch'),
                        'icon'  => 'conveyor-belt'
                    ),
*/

                    'fulfilment_part.purchase_orders' => array(
                        'label'   => _('Job orders'),
                        'icon'    => 'clipboard',
                        'subtabs' => array(

                            'fulfilment_part.supplier.orders'     => array(
                                'label' => _('Job orders'),
                                'icon'  => 'clipboard'
                            ),
                            'fulfilment_part.supplier.deliveries' => array(
                                'label' => _("Deliveries"),
                                'icon'  => 'hand-holding-heart'
                            ),

                        )

                    ),

                    'fulfilment_part.details' => array(
                        'label' => _('Data'),
                        'icon'  => 'database'
                    ),


                    'fulfilment_part.history' => array(
                        'label' => '',
                        'title' => _('History/Notes'),
                        'icon'  => 'road',
                        'class' => 'right icon_only'
                    ),
                    'fulfilment_part.images'  => array(
                        'label' => '',
                        'title' => _('Images'),
                        'icon'  => 'camera-retro',
                        'class' => 'right icon_only'
                    ),
                    'bill_of_materials'       => array(
                        'title' => _('Bill of materials'),
                        'label' => '',
                        'icon'  => 'puzzle-piece',
                        'class' => 'right icon_only'

                    ),

                    'fulfilment_part.tasks' => array(
                        'title' => _('List of tasks'),
                        'icon'  => 'tasks',
                        'label' => '',
                        'class' => 'right icon_only'
                    ),


                )
            ),

            'customers' => array(

                'type'      => 'navigation',
                'label'     => _('Customers'),
                'icon'      => 'user',
                'reference' => '',

                'tabs' => array(

                    'fulfilment.customers' => array(
                        'label' => _('Customers'),
                        'icon'  => 'user',
                        'class' => ''
                    ),

                )

            ),


            'locations' => array(

                'type'      => 'navigation',
                'label'     => _('Locations'),
                'icon'      => 'pallet',
                'reference' => 'fulfilment/locations',
                'tabs'      => array(


                    'fulfilment.locations' => array(
                        'label'             => _('Locations'),
                        'icon'              => 'pallet',
                        'dynamic_reference' => 'fulfilment/locations',

                    ),

                )

            ),



            'location' => array(

                'type'      => 'object',
                'label'     => _('Location'),
                'icon'      => 'map-sings',
                'reference' => '',
                'tabs'      => array(
                    'location.details'            => array(
                        'label' => _(
                            'Data'
                        ),
                        'title' => _('Location details'),
                        'icon'  => 'database'
                    ),
                    'location.parts'              => array(
                        'label' => _(
                            'Parts'
                        ),
                        'icon'  => 'box'
                    ),
                    'location.stock.transactions' => array(
                        'label' => _(
                            'Stock movements'
                        ),
                        'icon'  => 'exchange'
                    ),

                    'location.history' => array(
                        'title'         => _('History'),
                        'label'         => '',
                        'quantity_data' => array(
                            'object' => '_object',
                            'field'  => 'Number History Records'
                        ),
                        'icon'          => 'road',
                        'class'         => 'right icon_only'
                    ),


                )

            ),




        )
    );
}