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
                'reference' => 'fulfilment/%d/dashboard',
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



                    'fulfilment.stored_parts' => array(
                        'label' => _('Stored items'),


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
                'reference' => 'fulfilment/%d/customers',

                'tabs' => array(
                    'fulfilment.dropshipping_customers' => array(
                        'label' => _('Dropshipping cuomers'),
                        'icon'  => 'shopping-basket',
                        'class' => ''
                    ),
                    'fulfilment.asset_keeping_customers' => array(
                        'label' => _('Asset keeping'),
                        'icon'  => 'pallet',
                        'class' => ''
                    ),

                )

            ),
            'customer' => array(

                'type'      => 'object',
                'label'     => _('Customer'),
                'icon'      => 'user',
                'reference' => '',
                'tabs'      => array(
                    'customer.history' => array(
                        'label' => _('History, notes'),
                        'icon'  => 'sticky-note'
                    ),
                    'customer.details' => array(
                        'label' => _('Data'),
                        'icon'  => 'database'
                    ),
                    'customer.deliveries'              => array(
                        'label' => _('Deliveries'),
                        'icon'  => 'arrow-square-down'
                    ),
                    'customer.parts'              => array(
                        'label' => _('Parts'),
                        'icon'  => 'box'
                    ),
                    'customer.stored_assets'              => array(
                        'label' => _('Assets'),
                        'icon'  => 'box-alt'
                    ),
                    'customer.locations' => array(
                        'label' => _('Locations'),
                        'icon'  => 'pallet'
                    ),
                    'customer.invoices' => array(
                        'label' => _('Invoices'),
                        'icon'  => 'file-invoice'
                    ),



                )

            ),

            'locations' => array(

                'type'      => 'navigation',
                'label'     => _('Locations'),
                'icon'      => 'pallet',
                'reference' => 'fulfilment/%d/locations',
                'tabs'      => array(


                    'fulfilment.locations' => array(
                        'label'             => _('Locations'),
                        'icon'              => 'pallet',
                        'dynamic_reference' => 'fulfilment/%d/locations',

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

            'customer_part.new'            => array(
                'type' => 'new_object',
                'tabs' => array(
                    'customer_part.new' => array(
                        'label' => _("New customer's part")
                    ),

                )

            )


        )
    );
}