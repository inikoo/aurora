<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 14 Jun 2021 02:03  +0800, Kuala Lumpur Malaysia

 Copyright (c) 2019, Inikoo

 Version 3.0
*/

function get_fulfilment_module(): array {
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

/*
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
*/



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

            'customers'              => array(

                'type'      => 'navigation',
                'label'     => _('Customers'),
                'icon'      => 'user',
                'reference' => 'fulfilment/%d/customers',

                'tabs' => array(
                    'fulfilment.dropshipping_customers'  => array(
                        'label' => _('Dropshipping customers'),
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
            'asset_keeping_customer' => array(

                'type'      => 'object',
                'label'     => _('Customer'),
                'icon'      => 'user',
                'reference' => '',
                'tabs'      => array(
                    'customer.history'    => array(
                        'label' => _('History, notes'),
                        'icon'  => 'sticky-note'
                    ),
                    'customer.details'    => array(
                        'label' => _('Data'),
                        'icon'  => 'database'
                    ),
                    'customer.deliveries' => array(
                        'label' => _('Deliveries'),
                        'icon'  => 'arrow-square-down'
                    ),

                    'customer.stored_assets' => array(
                        'label' => _('Assets'),
                        'icon'  => 'box-alt'
                    ),
                    'customer.locations'     => array(
                        'label' => _('Locations'),
                        'icon'  => 'pallet'
                    ),
                    'customer.orders'      => array(
                        'label' => _('Orders'),
                        'icon'  => 'shopping-cart'
                    ),

                    'customer.invoices'      => array(
                        'label' => _('Invoices'),
                        'icon'  => 'file-invoice'
                    ),


                )

            ),

            'dropshipping_customer' => array(
                'type'           => 'object',
                'label'          => _('Customer'),
                'title'          => _('Customer'),
                'icon'           => 'user',
                'reference'      => 'customer/%d',
                'subtabs_parent' => array(
                    'customer.marketing.families'   => 'customer.insights',
                    'customer.marketing.products'   => 'customer.insights',
                    'customer.marketing.favourites' => 'customer.insights',
                    'customer.marketing.search'     => 'customer.insights',
                    'customer.poll'                 => 'customer.insights',

                    'customer.sales.plot'      => 'customer.sales',
                    'customer.sales.history'   => 'customer.sales',
                    'customer.sales.dashboard' => 'customer.sales',
                    'customer.sales.info'      => 'customer.sales',

                    'customer.orders'   => 'customer.orders_invoices',
                    'customer.invoices' => 'customer.orders_invoices',

                    'customer.active_portfolio'  => 'customer.portfolio',
                    'customer.removed_portfolio' => 'customer.portfolio',

                    'customer.parts'      => 'customer.fulfilment',
                    'customer.deliveries' => 'customer.fulfilment',
                    'customer.returns'    => 'customer.fulfilment',


                ),
                'tabs'           => array(
                    'customer.history' => array(
                        'label' => _('History, notes'),
                        'icon'  => 'sticky-note'
                    ),
                    'customer.details' => array(
                        'label' => _('Data'),
                        'icon'  => 'database'
                    ),

                    'customer.fulfilment' => array(
                        'label'   => _('Fulfilment'),
                        'icon'    => 'shopping-basket',
                        'subtabs' => array(
                            'customer.parts'      => array(
                                'label' => _('Parts'),
                                'icon'  => 'box'
                            ),
                            'customer.deliveries' => array(
                                'label' => _('Deliveries'),
                                'icon'  => 'download'
                            ),
                            'customer.returns'    => array(
                                'label' => _('Returns'),
                                'icon'  => 'external-link-square-alt'
                            ),
                        )

                    ),

                    'customer.portfolio' => array(

                        'label' => _("Portfolio"),

                        'title'         => _("Customer's store products"),
                        'icon'          => 'store-alt',
                        'quantity_data' => array(
                            'object' => '_object',
                            'field'  => 'Number Products in Portfolio'
                        ),
                        'subtabs'       => array(
                            'customer.active_portfolio'  => array(
                                'icon'  => 'cube',
                                'label' => _("Customer's store products")
                            ),
                            'customer.removed_portfolio' => array(
                                'class' => 'icon_only right',
                                'icon'  => 'ghost',
                                'label' => _('Removed products'),
                                'title' => _('Removed from portfolio')
                            ),

                        )
                    ),

                    'customer.clients' => array(
                        'label' => _("Clients"),

                        'title' => _("Customer's clients"),
                        'icon'  => 'address-book',

                        'quantity_data' => array(
                            'object' => '_object',
                            'field'  => 'Number Clients'
                        ),
                    ),

                    'customer.orders_invoices' => array(
                        'label'   => _('Orders'),
                        'icon'    => 'shopping-cart',
                        'subtabs' => array(
                            'customer.orders'   => array(
                                'label' => _('Orders'),
                                'icon'  => 'shopping-cart'
                            ),
                            'customer.invoices' => array(
                                'label' => _('Invoices'),
                                'icon'  => 'file-invoice-dollar'
                            ),
                        )

                    ),


                    'customer.sales' => array(
                        'label'   => _('Sales'),
                        'icon'    => 'money-bill',
                        'subtabs' => array(
                            'customer.sales.dashboard' => array(
                                'label' => _('Dashboard')
                            ),
                            'customer.sales.plot'      => array(
                                'label' => _('Plot')
                            ),
                            'customer.sales.history'   => array(
                                'label' => _('Sales history')
                            ),

                            'customer.sales.info' => array(
                                'label'   => '',
                                'title'   => _('Sales data info'),
                                'icon_v2' => 'fal fa-fw fa-chess-clock',
                                'class'   => 'right icon_only'
                            ),


                        )
                    ),


                    'customer.insights' => array(
                        'label'   => _('Insights'),
                        'icon'    => 'graduation-cap',
                        'subtabs' => array(
                            'customer.poll'               => array(
                                'label' => _('Poll'),
                                'icon'  => 'poll-people'
                            ),
                            'customer.marketing.products' => array(
                                'label' => _('Products invoiced'),
                                'icon'  => 'cube'
                            ),

                            'customer.marketing.families' => array(
                                'label' => _('Categories ordered'),
                                'icon'  => 'cubes'
                            ),

                            'customer.marketing.favourites' => array(
                                'label' => _('Favourite products'),
                                'icon'  => 'heart'
                            ),

                        )
                    ),


                    'customer.credit_blockchain' => array(
                        'label' => _('Credits'),
                        'icon'  => 'code-commit'
                    ),


                    'customer.sent_emails' => array(
                        'label' => '',
                        'title' => _('Sent emails'),
                        'icon'  => 'paper-plane',
                        'class' => 'icon_only right'
                    ),
                    'customer.deals'       => array(
                        'label' => '',
                        'title' => _('Discounts'),
                        'icon'  => 'tags',
                        'class' => 'icon_only right'
                    ),

                )
            ),

/*
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
*/

            'location' => array(

                'type'      => 'object',
                'label'     => _('Location'),
                'icon'      => 'map-sings',
                'reference' => '',
                'tabs'      => array(
                    'location.details'            => array(
                        'label' => _('Data'),
                        'title' => _('Location details'),
                        'icon'  => 'database'
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

            'customer_part.new' => array(
                'type' => 'new_object',
                'tabs' => array(
                    'customer_part.new' => array(
                        'label' => _("New customer's part")
                    ),

                )

            ),

            'deliveries' => array(

                'type'      => 'navigation',
                'label'     => _('Deliveries'),
                'icon'      => 'truck',
                'reference' => 'fulfilment/%d/deliveries',
                'tabs'      => array(


                    'fulfilment.deliveries' => array(
                        'label'             => _('Deliveries'),
                        'icon'              => 'truck',
                        'dynamic_reference' => 'fulfilment/%d/deliveries',

                    ),

                )

            ),




            'delivery'          => array(
                'type' => 'object',
                'tabs' => array(

                    'fulfilment.delivery.items' => array(
                        'label' => _('Items'),
                        'icon'  => 'bars'
                    ),

                    'fulfilment.delivery.details' => array(
                        'label' => _('Settings'),
                        'icon'  => 'sliders-h'
                    ),




                    'fulfilment.delivery.history'     => array(
                        'label'         => '',
                        'title'         => _('History/Notes'),
                        'icon'          => 'road',
                        'class'         => 'right icon_only',
                        'quantity_data' => array(
                            'object' => '_object',
                            'field'  => 'Number History Records'
                        ),
                    ),
                    'fulfilment.delivery.attachments' => array(
                        'label'         => '',
                        'title'         => _('Attachments'),
                        'icon'          => 'paperclip',
                        'class'         => 'right icon_only',
                        'quantity_data' => array(
                            'object' => '_object',
                            'field'  => 'Number Attachments'
                        ),
                    ),
                )

            ),
            'asset'          => array(
                'type' => 'object',
                'tabs' => array(



                    'fulfilment.asset.details' => array(
                        'label' => _('Settings'),
                        'icon'  => 'sliders-h'
                    ),




                    'fulfilment.asset.history'     => array(
                        'label'         => '',
                        'title'         => _('History/Notes'),
                        'icon'          => 'road',
                        'class'         => 'right icon_only',
                        'quantity_data' => array(
                            'object' => '_object',
                            'field'  => 'Number History Records'
                        ),
                    ),

                )

            ),

            'upload' => array(
                'type' => 'object',
                'tabs' => array(
                    'upload.records' => array(
                        'label' => _('Records')
                    ),


                )

            ),



        )
    );
}