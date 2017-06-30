<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 25 August 2015 12:55:36 GMT+8 Singapore

 Copyright (c) 2015, Inikoo

 Version 3.0
*/


$_product = array(
    'type'           => 'object',
    'subtabs_parent' => array(
        'product.sales.plot'        => 'product.sales',
        'product.sales.history'     => 'product.sales',
        'product.sales.calendar'    => 'product.sales',
        'product.sales.info'        => 'product.sales',
        'product.customers'         => 'product.customers',
        'product.customers.favored' => 'product.customers',
        'product.webpage.settings'  => 'product.webpage',
        'product.webpage.preview'   => 'product.webpage',
        //  'product.webpage.analytics'      => 'product.webpage',
        'product.webpage.logbook'   => 'product.webpage',

    ),

    'tabs' => array(
        'product.details' => array(
            'label' => _('Data'),
            'icon'  => 'database',
            'title' => _('Details')
        ),
        'product.webpage' => array(
            'label'   => _('Website'),
            'icon'    => 'globe',
            'subtabs' => array(
                'product.webpage.settings' => array(
                    'label' => _('Settings'),
                    'icon'  => 'sliders'
                ),

                'product.webpage.preview' => array(
                    'label' => _('Workshop'),
                    'icon'  => 'wrench'
                ),
                'product.webpage.logbook' => array(
                    'label' => _('Logbook'),
                    'icon'  => 'road'
                ),
                //    'product.webpage.analytics'  => array(
                //        'label' => _('Analytics')
                //    ),


            )
        ),

        'product.history' => array(
            'label' => _('History, notes'),
            'icon'  => 'sticky-note-o'
        ),
        'product.sales'   => array(
            'label'   => _('Sales'),
            'title'   => _('Sales'),
            'subtabs' => array(
                'product.sales.plot'     => array(
                    'label' => _(
                        'Plot'
                    )
                ),
                'product.sales.history'  => array(
                    'label' => _(
                        'Sales history'
                    )
                ),
                'product.sales.calendar' => array(
                    'label' => _(
                        'Calendar'
                    )
                ),
                'product.sales.info'     => array(
                    'label' => _('Info'),
                    'icon'  => 'info',
                    'class' => 'right icon_only'
                ),

            )
        ),
        'product.orders'  => array(
            'label'         => _('Orders'),
            'quantity_data' => array(
                'object' => '_object',
                'field'  => 'Number Orders'
            ),

        ),

        'product.customers' => array(
            'label'         => _('Customers'),
            'quantity_data' => array(
                'object' => '_object',
                'field'  => 'Customers Numbers'
            ),
            'subtabs'       => array(
                'product.customers'         => array(
                    'label'         => _('Customers'),
                    'quantity_data' => array(
                        'object' => '_object',
                        'field'  => 'Number Customers'
                    ),
                ),
                'product.customers.favored' => array(
                    'label'         => _('Customers who favored'),
                    'quantity_data' => array(
                        'object' => '_object',
                        'field'  => 'Number Customers Favored'
                    ),

                ),

            )
        ),


        /* To do

        'product.offers'    => array(
            'label' => _('Offers'),
            'title' => _('Offers')
        ),
*/


        'product.history' => array(
            'title'         => _('History/Notes'),
            'label'         => '',
            'quantity_data' => array(
                'object' => '_object',
                'field'  => 'Number History Records'
            ),
            'icon'          => 'road',
            'class'         => 'right icon_only'
        ),
        'product.images'  => array(
            'title'         => _('Images'),
            'label'         => '',
            'quantity_data' => array(
                'object' => '_object',
                'field'  => 'Number Images'
            ),
            'icon'          => 'camera-retro',
            'class'         => 'right icon_only'
        ),
        'product.parts'   => array(
            'title'         => _('Parts'),
            'label'         => '',
            'quantity_data' => array(
                'object' => '_object',
                'field'  => 'Number of Parts'
            ),
            'icon'          => 'square',
            'class'         => 'right icon_only'
        ),

    )
);

$_service = array(
    'type'           => 'object',
    'subtabs_parent' => array(
        'service.sales.plot'           => 'service.sales',
        'service.sales.history'        => 'service.sales',
        'service.sales.calendar'       => 'service.sales',
        'service.customers.customers'  => 'service.customers',
        'service.customers.favourites' => 'service.customers',
        'service.website.webpage'      => 'service.website',
        'service.website.pages'        => 'service.website',
    ),

    'tabs' => array(
        'service.details'   => array(
            'label' => _('Data'),
            'icon'  => 'database',
            'title' => _('Details')
        ),
        'service.history'   => array(
            'label' => _('History, notes'),
            'icon'  => 'sticky-note-o'
        ),
        'service.sales'     => array(
            'label'   => _('Sales'),
            'title'   => _('Sales'),
            'subtabs' => array(
                'service.sales.plot'     => array(
                    'label' => _(
                        'Plot'
                    )
                ),
                'service.sales.history'  => array(
                    'label' => _(
                        'Sales history'
                    )
                ),
                'service.sales.calendar' => array(
                    'label' => _(
                        'Calendar'
                    )
                ),

            )
        ),
        'service.orders'    => array(
            'label' => _('Orders'),

        ),
        'service.customers' => array(
            'label'   => _('Customers'),
            'subtabs' => array(
                'service.customers.customers'  => array(
                    'label' => _(
                        'Customers'
                    ),
                    'title' => _(
                        'Customers'
                    )
                ),
                'service.customers.favourites' => array(
                    'label' => _(
                        'Customers who favorited'
                    ),
                    'title' => _(
                        'Customers who favorited'
                    )
                ),

            )
        ),
        'service.offers'    => array(
            'label' => _('Offers'),
            'title' => _('Offers')
        ),

        'service.website' => array(
            'label'   => _('Website'),
            'title'   => _('Website'),
            'subtabs' => array(
                'service.website.webpage' => array(
                    'label' => _(
                        'Webpage'
                    ),
                    'title' => _(
                        'service webpage'
                    )
                ),
                'service.sales.pages'     => array(
                    'label' => _(
                        'Webpages'
                    ),
                    'title' => _(
                        'Webpages where this service is on sale'
                    )
                ),

            )
        ),
        'service.history' => array(
            'label' => _('History'),
            'icon'  => 'road',
            'class' => 'right icon_only'
        ),
        'category.images' => array(
            'label' => _('Images'),
            'icon'  => 'camera-retro',
            'class' => 'right icon_only'
        ),

    )
);

$modules = array(
    'dashboard'             => array(

        'section'     => 'dashboard',
        'parent'      => 'none',
        'parent_type' => 'none',
        'sections'    => array(
            'dashboard' => array(
                'type'  => 'widgets',
                'label' => _('Home'),
                'icon'  => 'home',
                'tabs'  => array(
                    'dashboard' => array(
                        'label' => _(
                            'Dashboard'
                        )
                    ),

                )

            ),
        )

    ),
    'customers'             => array(
        'section'     => 'customers',
        'parent'      => 'store',
        'parent_type' => 'key',
        'sections'    => array(
            'dashboard' => array(
                'type'      => 'navigation',
                'label'     => _('Dashboard'),
                'title'     => _("Customer's dashboard"),
                'icon'      => 'dashboard',
                'reference' => 'customers/%d/dashboard',
                'tabs'      => array(
                    'customers.dashboard' => array()
                )
            ),

            'customers'  => array(
                'type'      => 'navigation',
                'label'     => _('Customers'),
                'icon'      => 'users',
                'reference' => 'customers/%d',
                'tabs'      => array(
                    'customers' => array()
                )


            ),
            'lists'      => array(
                'type'      => 'navigation',
                'label'     => _('Lists'),
                'icon'      => 'list',
                'reference' => 'customers/%d/lists',
                'tabs'      => array(
                    'customers.lists' => array()
                )
            ),
            'categories' => array(
                'type'      => 'navigation',
                'label'     => _('Categories'),
                'icon'      => 'sitemap',
                'reference' => 'customers/%d/categories',
                'tabs'      => array(
                    'customers.categories' => array()
                ),

            ),
            'statistics' => array(
                'type'      => 'navigation',
                'label'     => _('Statistics'),
                'icon'      => 'line-chart',
                'reference' => 'customers/%dstatistics',
                'tabs'      => array(
                    'contacts'       => array(
                        'label' => _(
                            'Contacts'
                        )
                    ),
                    'customers'      => array(
                        'label' => _(
                            'Customers'
                        )
                    ),
                    'orders'         => array(
                        'label' => _(
                            'Orders'
                        )
                    ),
                    'data_integrity' => array(
                        'label' => _(
                            'Data Integrity'
                        )
                    ),
                    'geo'            => array(
                        'label' => _(
                            'Geographic Distribution'
                        )
                    ),
                    'correlations'   => array(
                        'label' => _(
                            'Correlations'
                        )
                    ),

                )

            ),

            'list'     => array(
                'type' => 'object',
                'tabs' => array(
                    'customers.list' => array()
                )


            ),
            'category' => array(
                'type' => 'object',

                'tabs' => array(
                    'category.details'    => array(
                        'label' => _('Data'),
                        'icon'  => 'database'
                    ),
                    'category.history'    => array(
                        'label' => _('History'),
                        'icon'  => 'sticky-note-o'
                    ),
                    'category.customers'  => array('label' => _('Customers')),
                    'category.categories' => array(
                        'label' => _(
                            'Subcategories'
                        )
                    ),

                )

            ),

            'customer' => array(
                'type'           => 'object',
                'label'          => _('Customer'),
                'title'          => _('Customer'),
                'icon'           => 'user',
                'reference'      => 'customer/%d',
                'subtabs_parent' => array(
                    'customer.marketing.overview'   => 'customer.marketing',
                    'customer.marketing.families'   => 'customer.marketing',
                    'customer.marketing.products'   => 'customer.marketing',
                    'customer.marketing.favourites' => 'customer.marketing',
                    'customer.marketing.search'     => 'customer.marketing',

                ),
                'tabs'           => array(
                    'customer.details'   => array(
                        'label' => _('Data'),
                        'icon'  => 'database'
                    ),
                    'customer.history'   => array(
                        'label' => _(
                            'History, notes'
                        ),
                        'icon'  => 'sticky-note-o'
                    ),
                    'customer.orders'    => array('label' => _('Orders')),
                    'customer.invoices'  => array('label' => _('Invoices')),
                    'customer.marketing' => array(
                        'label'   => _('Interests'),
                        'title'   => _(
                            "Customer's interests"
                        ),
                        'subtabs' => array(
                            'customer.marketing.overview'   => array(
                                'label' => _(
                                    'Overview'
                                )
                            ),
                            'customer.marketing.products'   => array(
                                'label' => _(
                                    'Products ordered'
                                )
                            ),
                            'customer.marketing.families'   => array(
                                'label' => _(
                                    'Families ordered'
                                )
                            ),
                            'customer.marketing.favourites' => array(
                                'label' => _(
                                    'Favourite products'
                                )
                            ),
                            'customer.marketing.search'     => array(
                                'label' => _(
                                    'Search queries'
                                )
                            ),

                        )

                    ),

                )
            ),


            'customer.new' => array(
                'type' => 'new_object',
                'tabs' => array(
                    'customer.new' => array(
                        'label' => _(
                            'New customer'
                        )
                    ),

                )

            ),


        )
    ),
    'customers_server'      => array(

        'parent'      => 'none',
        'parent_type' => 'none',
        'section'     => 'customers',
        'sections'    => array(
            'customers' => array(
                'type'      => 'navigation',
                'label'     => _('Customers (All stores)'),
                'title'     => _('Customers (All stores)'),
                'icon'      => '',
                'reference' => 'customers/all',
                'tabs'      => array(
                    'customers_server' => array()
                )
            ),

        )

    ),
    'orders'                => array(
        'section'     => 'orders',
        'parent'      => 'store',
        'parent_type' => 'key',
        'sections'    => array(


            'dashboard' => array(
                'type'      => 'navigation',
                'label'     => _('Dashboard'),
                'icon'      => 'tachometer',
                'reference' => 'orders/%d/dashboard',
                'tabs'      => array(
                    'orders.dashboard' => array('label' => _('Dashboard'))

                )
            ),


            'basket_orders' => array(
                'type'      => 'navigation',
                'label'     => _('Orders in website'),
                'icon'      => 'globe',
                'reference' => 'orders/%d/website',
                'tabs'      => array(
                    'orders.website' => array('label' => _('Orders in website'))

                )
            ),

            'pending_orders' => array(
                'type'      => 'navigation',
                'label'     => _('Pending orders'),
                'icon'      => 'shopping-cart',
                'reference' => 'orders/%d/flow',
                'tabs'      => array(
                    'orders.pending' => array('label' => _('Pending orders'))

                )
            ),


            'orders' => array(
                'type'      => 'navigation',
                'label'     => _('Orders (Archive)'),
                'icon'      => 'archive',
                'reference' => 'orders/%d',
                'tabs'      => array(
                    'orders' => array()
                )
            ),


            'order'         => array(
                'type' => 'object',
                'tabs' => array(


                    'order.items'          => array(
                        'label' => _(
                            'Items'
                        ),
                        'icon'  => 'bars'
                    ),
                    'order.details'        => array(
                        'label' => _(
                            'Data'
                        ),
                        'icon'  => 'database'
                    ),
                    'order.history'        => array(
                        'label' => _(
                            'History'
                        ),
                        'icon'  => 'road'
                    ),
                    'order.delivery_notes' => array(
                        'label' => _(
                            'Delivery notes'
                        ),
                        'icon'  => 'truck'
                    ),
                    'order.invoices'       => array(
                        'label' => _(
                            'Invoices'
                        ),
                        'icon'  => 'file-text-o'
                    ),
                    'order.payments'       => array(
                        'label' => _(
                            'Payments'
                        ),
                        'icon'  => 'usd'
                    ),

                )

            ),
            'delivery_note' => array(
                'type' => 'object',
                'tabs' => array(


                    'delivery_note.items'    => array(
                        'label' => _(
                            'Items'
                        ),
                        'icon'  => 'bars'
                    ),
                    'delivery_note.details'  => array(
                        'label' => _(
                            'Data'
                        ),
                        'icon'  => 'database'
                    ),
                    'delivery_note.history'  => array(
                        'label' => _(
                            'History'
                        ),
                        'icon'  => 'road'
                    ),
                    'delivery_note.orders'   => array(
                        'label' => _(
                            'Orders'
                        ),
                        'icon'  => 'shopping-cart'
                    ),
                    'delivery_note.invoices' => array(
                        'label' => _(
                            'Invoices'
                        ),
                        'icon'  => 'file-text-o'
                    ),
                )

            ),
            'invoice'       => array(
                'type' => 'object',
                'tabs' => array(


                    'invoice.items'          => array(
                        'label' => _(
                            'Items'
                        ),
                        'icon'  => 'bars'
                    ),
                    'invoice.details'        => array(
                        'label' => _(
                            'Data'
                        ),
                        'icon'  => 'database'
                    ),
                    'invoice.history'        => array(
                        'label' => _(
                            'History'
                        ),
                        'icon'  => 'road'
                    ),
                    'invoice.orders'         => array(
                        'label' => _(
                            'Orders'
                        ),
                        'icon'  => 'shopping-cart'
                    ),
                    'invoice.delivery_notes' => array(
                        'label' => _(
                            'Delivery notes'
                        ),
                        'icon'  => 'truck'
                    ),
                )

            ),

        )
    ),
    'orders_server'         => array(

        'parent'      => 'none',
        'parent_type' => 'none',
        'section'     => 'orders',
        'sections'    => array(
            'orders' => array(
                'type'      => 'navigation',
                'label'     => _('Orders'),
                'icon'      => 'shopping-cart',
                'reference' => 'orders/all',
                'tabs'      => array(
                    'orders_server' => array()
                )

            ),

        )

    ),
    'invoices'              => array(
        'section'     => 'invoices',
        'parent'      => 'store',
        'parent_type' => 'key',
        'sections'    => array(

            'invoices' => array(
                'type'      => 'navigation',
                'label'     => _('Invoices'),
                'icon'      => 'file-text-o',
                'reference' => 'invoices/%d',
                'tabs'      => array(
                    'invoices' => array(
                        'label' => _(
                            'Invoices'
                        ),
                        'icon'  => 'file-text-o'
                    ),
                )
            ),

            'categories' => array(
                'type'      => 'navigation',
                'label'     => _("Categories"),
                'title'     => _("Invoice's categories"),
                'icon'      => 'sitemap',
                'reference' => 'invoices/%d/categories',
                'tabs'      => array(
                    'invoices.categories' => array(),
                )
            ),


            'order'         => array(
                'type' => 'object',
                'tabs' => array(


                    'order.items'          => array(
                        'label' => _(
                            'Items'
                        ),
                        'icon'  => 'bars'
                    ),
                    'order.details'        => array(
                        'label' => _(
                            'Data'
                        ),
                        'icon'  => 'database'
                    ),
                    'order.history'        => array(
                        'label' => _(
                            'History'
                        ),
                        'icon'  => 'road'
                    ),
                    'order.delivery_notes' => array(
                        'label' => _(
                            'Delivery notes'
                        ),
                        'icon'  => 'truck'
                    ),
                    'order.invoices'       => array(
                        'label' => _(
                            'Invoices'
                        ),
                        'icon'  => 'file-text-o'
                    ),

                )

            ),
            'delivery_note' => array(
                'type' => 'object',
                'tabs' => array(


                    'delivery_note.items'    => array(
                        'label' => _(
                            'Items'
                        ),
                        'icon'  => 'bars'
                    ),
                    'delivery_note.details'  => array(
                        'label' => _(
                            'Data'
                        ),
                        'icon'  => 'database'
                    ),
                    'delivery_note.history'  => array(
                        'label' => _(
                            'History'
                        ),
                        'icon'  => 'road'
                    ),
                    'delivery_note.orders'   => array(
                        'label' => _(
                            'Orders'
                        ),
                        'icon'  => 'shopping-cart'
                    ),
                    'delivery_note.invoices' => array(
                        'label' => _(
                            'Invoices'
                        ),
                        'icon'  => 'file-text-o'
                    ),
                )

            ),
            'invoice'       => array(
                'type' => 'object',
                'tabs' => array(


                    'invoice.items'          => array(
                        'label' => _(
                            'Items'
                        ),
                        'icon'  => 'bars'
                    ),
                    'invoice.details'        => array(
                        'label' => _(
                            'Data'
                        ),
                        'icon'  => 'database'
                    ),
                    'invoice.history'        => array(
                        'label' => _(
                            'History'
                        ),
                        'icon'  => 'road'
                    ),
                    'invoice.orders'         => array(
                        'label' => _(
                            'Orders'
                        ),
                        'icon'  => 'shopping-cart'
                    ),
                    'invoice.delivery_notes' => array(
                        'label' => _(
                            'Delivery notes'
                        ),
                        'icon'  => 'truck'
                    ),
                )

            ),

        )
    ),
    'invoices_server'       => array(

        'parent'      => 'none',
        'parent_type' => 'none',

        'sections' => array(
            'invoices' => array(
                'type'      => 'navigation',
                'label'     => _('Invoices'),
                'icon'      => 'shopping-cart',
                'reference' => 'invoices/all',
                'tabs'      => array(
                    'invoices_server' => array()
                )

            ),

            'categories' => array(
                'type'      => 'navigation',
                'label'     => _("Categories"),
                'title'     => _("Invoice's categories").' ('._(
                        'All stores'
                    ).')',
                'icon'      => 'sitemap',
                'reference' => 'invoices/all/categories',
                'tabs'      => array(
                    'invoices_server.categories' => array(),
                )
            ),

            'category' => array(
                'type' => 'object',
                'tabs' => array(

                    'category.details'    => array(
                        'label' => _(
                            'Data'
                        ),
                        'icon'  => 'database'
                    ),
                    'category.history'    => array(
                        'label' => _(
                            'History'
                        ),
                        'icon'  => 'clock'
                    ),
                    'category.invoices'   => array(
                        'label' => _(
                            'Invoices'
                        )
                    ),
                    'category.categories' => array(
                        'label' => _(
                            'Subcategories'
                        )
                    ),

                )

            ),

        )

    ),
    'delivery_notes'        => array(
        'section'     => 'delivery_notes',
        'parent'      => 'store',
        'parent_type' => 'key',
        'sections'    => array(


            'delivery_notes' => array(
                'type'      => 'navigation',
                'label'     => _('All delivery notes'),
                'icon'      => 'truck fa-flip-horizontal',
                'reference' => 'delivery_notes/%d',
                'tabs'      => array(
                    'delivery_notes' => array()
                )
            ),


            'order'         => array(
                'type' => 'object',
                'tabs' => array(


                    'order.items'          => array(
                        'label' => _(
                            'Items'
                        ),
                        'icon'  => 'bars'
                    ),
                    'order.details'        => array(
                        'label' => _(
                            'Data'
                        ),
                        'icon'  => 'database'
                    ),
                    'order.history'        => array(
                        'label' => _(
                            'History'
                        ),
                        'icon'  => 'road'
                    ),
                    'order.delivery_notes' => array(
                        'label' => _(
                            'Delivery notes'
                        ),
                        'icon'  => 'truck'
                    ),
                    'order.invoices'       => array(
                        'label' => _(
                            'Invoices'
                        ),
                        'icon'  => 'file-text-o'
                    ),

                )

            ),
            'delivery_note' => array(
                'type' => 'object',
                'tabs' => array(


                    'delivery_note.items'    => array(
                        'label' => _(
                            'Items'
                        ),
                        'icon'  => 'bars'
                    ),
                    'delivery_note.details'  => array(
                        'label' => _(
                            'Data'
                        ),
                        'icon'  => 'database'
                    ),
                    'delivery_note.history'  => array(
                        'label' => _(
                            'History'
                        ),
                        'icon'  => 'road'
                    ),
                    'delivery_note.orders'   => array(
                        'label' => _(
                            'Orders'
                        ),
                        'icon'  => 'shopping-cart'
                    ),
                    'delivery_note.invoices' => array(
                        'label' => _(
                            'Invoices'
                        ),
                        'icon'  => 'file-text-o'
                    ),
                )

            ),
            'invoice'       => array(
                'type' => 'object',
                'tabs' => array(


                    'invoice.items'          => array(
                        'label' => _(
                            'Items'
                        ),
                        'icon'  => 'bars'
                    ),
                    'invoice.details'        => array(
                        'label' => _(
                            'Data'
                        ),
                        'icon'  => 'database'
                    ),
                    'invoice.history'        => array(
                        'label' => _(
                            'History'
                        ),
                        'icon'  => 'road'
                    ),
                    'invoice.orders'         => array(
                        'label' => _(
                            'Orders'
                        ),
                        'icon'  => 'shopping-cart'
                    ),
                    'invoice.delivery_notes' => array(
                        'label' => _(
                            'Delivery notes'
                        ),
                        'icon'  => 'truck'
                    ),
                )

            ),
            'pick_aid'      => array(
                'type' => 'object',
                'tabs' => array(


                    'pick_aid.items' => array(
                        'label' => _(
                            'Items'
                        ),
                        'icon'  => 'bars'
                    ),
                )

            ),
            'pack_aid'      => array(
                'type' => 'object',
                'tabs' => array(


                    'pack_aid.items' => array(
                        'label' => _(
                            'Items'
                        ),
                        'icon'  => 'bars'
                    ),
                )

            ),

        )
    ),
    'delivery_notes_server' => array(

        'parent'      => 'none',
        'parent_type' => 'none',
        'section'     => 'orders',
        'sections'    => array(
            'delivery_notes' => array(
                'type' => 'navigation',
                'tabs' => array(
                    'delivery_notes_server' => array()
                )

            ),

        )

    ),
    'payments'              => array(
        'section'     => 'invoices',
        'parent'      => 'store',
        'parent_type' => 'key',
        'sections'    => array(

            'payment_service_providers' => array(
                'type'      => 'navigation',
                'label'     => _(
                    'Payment Service Providers'
                ),
                'icon'      => 'university',
                'reference' => 'payment_service_providers',
                'tabs'      => array(
                    'payment_service_providers' => array(
                        'label' => _(
                            'Payment Service Providers'
                        ),
                        'icon'  => 'university'
                    ),
                )
            ),

            'payment_accounts' => array(
                'type'      => 'navigation',
                'label'     => _("Payment Accounts"),
                'icon'      => 'cc',
                'reference' => 'payment_accounts/%s',
                'tabs'      => array(
                    'payment_accounts' => array(),
                )
            ),

            'payments' => array(
                'type'      => 'navigation',
                'label'     => _('Payments'),
                'icon'      => 'credit-card',
                'reference' => 'payments/%s',
                'tabs'      => array(
                    'payments' => array()
                )
            ),

            'payment_service_provider' => array(
                'type' => 'object',
                'tabs' => array(
                    'payment_service_provider.details'  => array(
                        'label' => _(
                            'Data'
                        ),
                        'icon'  => 'database'
                    ),
                    'payment_service_provider.history'  => array(
                        'label' => _(
                            'History, notes'
                        ),
                        'icon'  => 'sticky-note-o'
                    ),
                    'payment_service_provider.accounts' => array(
                        'label' => _(
                            'Accounts'
                        ),
                        'title' => _(
                            'Payment accounts'
                        )
                    ),
                    'payment_service_provider.payments' => array(
                        'label' => _(
                            'Payments'
                        ),
                        'title' => _(
                            'Payments transactions'
                        )
                    ),

                )
            ),
            'payment_account'          => array(
                'type' => 'object',
                'tabs' => array(
                    'payment_account.details'  => array(
                        'label' => _(
                            'Data'
                        ),
                        'icon'  => 'database',
                        'title' => _(
                            'Details'
                        )
                    ),
                    'payment_account.history'  => array(
                        'label' => _(
                            'History, notes'
                        ),
                        'icon'  => 'sticky-note-o'
                    ),
                    'payment_account.payments' => array(
                        'label' => _(
                            'Payments'
                        ),
                        'title' => _(
                            'Payments transactions'
                        )
                    ),

                )
            ),
            'payment'                  => array(
                'type' => 'object',
                'tabs' => array(
                    'payment.details' => array(
                        'label' => _(
                            'Data'
                        ),
                        'icon'  => 'database',
                        'title' => _(
                            'Details'
                        )
                    ),
                    'payment.history' => array(
                        'label' => _(
                            'History, notes'
                        ),
                        'icon'  => 'sticky-note-o'
                    ),

                )
            )


        )
    ),


    'websites_server' => array(

        'parent'      => 'none',
        'parent_type' => 'none',
        'section'     => 'websites',
        'sections'    => array(
            'websites' => array(
                'type'      => 'navigation',
                'label'     => _('Websites (All stores)'),
                'icon'      => '',
                'reference' => '',
                'tabs'      => array(
                    'websites' => array()
                )
            ),

        )

    ),
    /*

        'websites'         => array(
            'section'     => 'dashboard',
            'parent'      => 'website',
            'parent_type' => 'key',
            'sections'    => array(


                'website' => array(
                    'type'           => 'navigation',
                    'label'          => '',
                    'title'          => _('Website'),
                    'icon'           => 'globe',
                    'reference'      => 'website/%d',
                    'class'          => 'icon_only',
                    'subtabs_parent' => array(
                        'website.favourites.families'  => 'website.favourites',
                        'website.favourites.products'  => 'website.favourites',
                        'website.favourites.customers' => 'website.favourites',
                        'website.search.queries'       => 'website.search',
                        'website.search.history'       => 'website.search',
                        'website.reminders.requests'   => 'website.reminders',
                        'website.reminders.customers'  => 'website.reminders',
                        'website.reminders.families'   => 'website.reminders',
                        'website.reminders.products'   => 'website.reminders',
                        'website.root_nodes'           => 'website.content',
                        'website.online_webpages'      => 'website.content',
                        'website.offline_webpages'     => 'website.content',

                        'website.footer.preview' => 'website.footer',
                        //    'website.footer.templates' => 'website.footer',
                        'website.header.preview' => 'website.header',
                        //     'website.header.templates' => 'website.header',


                        'website.templates.categories' => 'website.templates',
                        'website.templates'            => 'website.templates',


                    ),

                    'tabs' => array(

                        'website.analytics' => array(
                            'label' => _('Analytics'),
                            'icon'  => 'line-chart',

                            'subtabs' => array(
                                'website.pageviews'  => array(
                                    'label' => _(
                                        'Pageviews'
                                    ),
                                    'icon'  => 'eye'
                                ),
                                'website.users'      => array(
                                    'label' => _(
                                        'Users'
                                    ),
                                    'icon'  => 'terminal'
                                ),
                                'website.search'     => array(
                                    'label'   => _(
                                        'Queries'
                                    ),
                                    'title'   => _(
                                        'Search Queries'
                                    ),
                                    'icon'    => 'search',
                                    'subtabs' => array(
                                        'website.search.queries' => array(
                                            'label' => _(
                                                'Queries'
                                            ),
                                            'title' => _(
                                                'Search queries goruped by keywords'
                                            )
                                        ),
                                        'website.search.history' => array(
                                            'label' => _(
                                                'Search History'
                                            ),
                                            'title' => _(
                                                'List of all search queries'
                                            )
                                        ),

                                    )

                                ),
                                'website.favourites' => array(
                                    'label'   => _(
                                        'Favourites'
                                    ),
                                    'title'   => _(
                                        'Favourites'
                                    ),
                                    'icon'    => 'heart-o',
                                    'subtabs' => array(
                                        'website.favourites.products'  => array(
                                            'label' => _(
                                                'Products'
                                            )
                                        ),
                                        'website.favourites.customers' => array(
                                            'label' => _(
                                                'Customers'
                                            )
                                        ),

                                    )

                                ),
                                'website.reminders'  => array(
                                    'label'   => _(
                                        'OOS Reminders'
                                    ),
                                    'title'   => _(
                                        'Out of stock reminders'
                                    ),
                                    'icon'    => 'hand-paper-o',
                                    'subtabs' => array(
                                        'website.reminders.requests'  => array(
                                            'label' => _(
                                                'Requests'
                                            ),
                                            'title' => _(
                                                'Out of stock notifications requests'
                                            )
                                        ),
                                        'website.reminders.customers' => array(
                                            'label' => _(
                                                'Customers'
                                            ),
                                            'title' => _(
                                                'Customers who ask for a out of stock notification'
                                            )
                                        ),
                                        'website.reminders.products'  => array(
                                            'label' => _(
                                                'Products'
                                            ),
                                            'title' => _(
                                                'Out of stock notifications grouped by product'
                                            )
                                        ),

                                    )

                                ),

                            )


                        ),
                        'website.details'   => array(
                            'label' => _(
                                'Data'
                            ),
                            'icon'  => 'database'
                        ),


                        'website.templates' => array(
                            'label'   => _('Templates'),
                            'icon'    => 'code',
                            'subtabs' => array(
                                'website.templates.categories' => array(
                                    'label' => _(
                                        "Template categories"
                                    ),
                                    'icon'  => 'sitemap'
                                ),
                                'website.templates'            => array(
                                    'label' => _(
                                        'Templates'
                                    ),
                                    'icon'  => 'code',

                                )

                            ),

                        ),

                        'website.header' => array(
                            'label'   => _(
                                'Header'
                            ),
                            'icon'    => 'header',
                            'subtabs' => array(
                                'website.header.preview' => array(
                                    'label' => _(
                                        'Preview'
                                    ),
                                    'icon'  => 'eye'
                                )

                            ),

                        ),
                        'website.footer' => array(
                            'label'   => _(
                                'Footer'
                            ),
                            'icon'    => 'minus',
                            'subtabs' => array(
                                'website.footer.preview' => array(
                                    'label' => _(
                                        'Preview'
                                    ),
                                    'icon'  => 'eye'
                                )

                            ),

                        ),


                    )
                ),


                'webpages' => array(
                    'type'      => 'navigation',
                    'label'     => _('Web pages'),
                    'icon'      => 'files-o',
                    'reference' => 'webpages/%d',

                    'tabs' => array(
                        'website.online_webpages'  => array(
                            'label' => _('Online web pages'),
                            'icon'  => 'files-o'
                        ),
                        'website.webpage.types'    => array(
                            'label' => _('Web pages by type'),
                            'icon'  => 'server'
                        ),
                        'website.root_nodes'       => array(
                            'label' => _('Sitemap'),
                            'icon'  => 'sitemap',
                            'class' => 'hide'
                        ),
                        'website.offline_webpages' => array(
                            'label' => _('Offline web pages'),
                            'class' => 'right icon_only',
                            'icon'  => 'eye-slash'
                        ),

                    )

                ),


                'webpage_type' => array(
                    'type' => 'object',
                    'tabs' => array(


                        'webpage_type.webpages' => array(
                            'label' => _(
                                'Versions'
                            ),
                            'icon'  => 'files-o'
                        ),

                    )
                ),

                'page' => array(
                    'type' => 'object',
                    'tabs' => array(


                        'page.analytics' => array(
                            'label' => _(
                                'Analytics'
                            ),
                            'icon'  => 'line-chart'
                        ),
                        'page.details'   => array(
                            'label' => _('Data'),
                            'icon'  => 'database'
                        ),
                        'page.versions'  => array(
                            'label' => _(
                                'Versions'
                            ),
                            'icon'  => 'code-fork'
                        ),
                        'page.preview'   => array(
                            'label' => _(
                                'Preview'
                            ),
                            'icon'  => 'eye'
                        ),
                        'page.blocks'    => array(
                            'label' => _(
                                'Blocks'
                            ),
                            'icon'  => 'align-justify'
                        ),
                    )
                ),

                'page_version' => array(
                    'type' => 'object',
                    'tabs' => array(


                        'page_version.analytics' => array(
                            'label' => _(
                                'Analytics'
                            ),
                            'icon'  => 'line-chart'
                        ),
                        'page_version.details'   => array(
                            'label' => _('Data'),
                            'icon'  => 'database'
                        ),

                        'page_version.preview' => array(
                            'label' => _(
                                'Preview'
                            ),
                            'icon'  => 'eye'
                        ),

                    )
                ),

                'website.node' => array(
                    'type' => 'object',

                    'subtabs_parent' => array(
                        'website.node.analytics.pageviews' => 'website.node.analytics',
                        'website.node.analytics.users'     => 'website.node.analytics',
                        'website.node.page.preview'        => 'website.node.page',
                        'website.node.page.versions'       => 'website.node.page',


                    ),


                    'tabs' => array(


                        'website.node.analytics' => array(
                            'label' => _(
                                'Analytics'
                            ),
                            'icon'  => 'line-chart',

                            'subtabs' => array(
                                'website.node.analytics.pageviews' => array(
                                    'label' => _(
                                        'Pageviews'
                                    ),
                                    'icon'  => ''
                                ),
                                'website.node.analytics.users'     => array(
                                    'label' => _(
                                        'Users'
                                    ),
                                    'icon'  => 'terminal'
                                ),

                            )
                        ),
                        'website.node.details'   => array(
                            'label' => _(
                                'Data'
                            ),
                            'icon'  => 'database'
                        ),

                        'website.node.page'  => array(
                            'label'   => _(
                                'Webpage'
                            ),
                            'icon'    => 'file-o',
                            'subtabs' => array(
                                'website.node.page.preview'  => array(
                                    'label' => _(
                                        'Preview'
                                    ),
                                    'icon'  => 'eye'
                                ),
                                'website.node.page.versions' => array(
                                    'label' => _(
                                        'Versions'
                                    ),
                                    'icon'  => 'code-fork',

                                )

                            ),

                        ),
                        'website.node.nodes' => array(
                            'label' => _(
                                'Subnodes'
                            ),
                            'icon'  => 'pagelines'
                        ),
                    )
                ),

                'website.user' => array(
                    'type' => 'object',
                    'tabs' => array(
                        'website.user.details'       => array(
                            'label' => _(
                                'Data'
                            ),
                            'icon'  => 'database',
                            'title' => _(
                                'Details'
                            )
                        ),
                        'website.user.history'       => array(
                            'label' => _(
                                'History, notes'
                            ),
                            'icon'  => 'sticky-note-o'
                        ),
                        'website.user.login_history' => array(
                            'label' => _(
                                'Sessions'
                            ),
                            'title' => _(
                                'Login history'
                            ),
                            'icon'  => 'login'
                        ),
                        'website.user.pageviews'     => array(
                            'label' => _(
                                'Pageviews'
                            ),
                            'icon'  => 'eye'
                        ),

                    )
                )


                //'categories'=>array('label'=>_('Categories'),'title'=>_('Categories'),'icon'=>'sitemap','reference'=>'orders/categories/%d'),
            )
        ),


        */


    'products'        => array(
        'section'  => 'products',
        'sections' => array(
            /*
			'dashboard'=>array('type'=>'navigation', 'label'=>_('Dashboard'), 'title'=>_("Products's dashboard"), 'icon'=>'dashboard', 'reference'=>'store/%d/dashboard',
				'tabs'=>array(
					'store.dashboard'=>array()
				)
			),
*/
            'store' => array(
                'type'      => 'navigation',
                'label'     => '',
                'title'     => _('Store'),
                'icon'      => 'shopping-bag',
                'showcase'  => 'store',
                'reference' => 'store/%d',
                'class'     => 'icon_only',

                'subtabs_parent' => array(
                    'store.sales.plot'     => 'store.sales',
                    'store.sales.history'  => 'store.sales',
                    'store.sales.calendar' => 'store.sales',
                    'store.sales.info'     => 'store.sales'

                ),

                'tabs' => array(
                    'store.details' => array(
                        'label' => _('Data'),
                        'icon'  => 'database',
                        'title' => _('Details')
                    ),
                    'store.sales'   => array(
                        'label'   => _('Sales'),
                        'subtabs' => array(
                            'store.sales.plot'     => array(
                                'label' => _(
                                    'Plot'
                                )
                            ),
                            'store.sales.history'  => array(
                                'label' => _(
                                    'Sales history'
                                )
                            ),
                            'store.sales.calendar' => array(
                                'label' => _(
                                    'Calendar'
                                )
                            ),
                            'store.sales.info'     => array(
                                'label' => _('Info'),
                                'icon'  => 'info',
                                'class' => 'right icon_only'
                            ),

                        )

                    ),
                    'store.history' => array(
                        'label' => _('History'),
                        'icon'  => 'road',
                        'class' => 'right icon_only'
                    ),


                )
            ),

            'store.new' => array(
                'type' => 'new_object',
                'tabs' => array(
                    'store.new' => array(
                        'label' => _(
                            'New store'
                        )
                    ),

                )

            ),

            'services' => array(
                'type'      => 'navigation',
                'label'     => _('Services'),
                'icon'      => 'wrench',
                'reference' => 'services/%d',
                'tabs'      => array(
                    'services' => array()
                )
            ),
            'products' => array(
                'type'      => 'navigation',
                'label'     => _('Products'),
                'title'     => _("Products database"),
                'icon'      => 'cube',
                'reference' => 'products/%d',
                'tabs'      => array(
                    'products' => array()
                )


            ),


            'categories' => array(
                'type'      => 'navigation',
                'label'     => _('Categories'),
                'title'     => _("Products categories"),
                'icon'      => 'sitemap',
                'reference' => 'products/%d/categories',
                'showcase'  => 'products_special_categories',
                'tabs'      => array(
                    'products.categories' => array()
                )

            ),


            'website' => array(
                'type'           => 'navigation',
                'label'          => _('Website'),
                'showcase'       => 'website',
                'icon'           => 'globe',
                'reference'      => 'store/%d/website',
                'subtabs_parent' => array(
                    'website.favourites.families'  => 'website.favourites',
                    'website.favourites.products'  => 'website.favourites',
                    'website.favourites.customers' => 'website.favourites',
                    'website.search.queries'       => 'website.search',
                    'website.search.history'       => 'website.search',
                    'website.reminders.requests'   => 'website.reminders',
                    'website.reminders.customers'  => 'website.reminders',
                    'website.reminders.families'   => 'website.reminders',
                    'website.reminders.products'   => 'website.reminders',

                    'website.root_nodes'          => 'website.webpages',
                    'website.online_webpages'     => 'website.webpages',
                    'website.offline_webpages'    => 'website.webpages',
                    'website.webpage.types'       => 'website.webpages',
                    'website.in_process_webpages' => 'website.webpages',

                    'website.fonts' => 'website.style',
                    'website.colours' => 'website.style',
                    'website.localization'      => 'website.style',

                    'website.footer.preview' => 'website.templates',
                    'website.header.preview' => 'website.templates',
                    'website.templates'      => 'website.templates',


                ),

                'tabs' => array(


                    'website.details'   => array(
                        'label' => _(
                            'Data'
                        ),
                        'icon'  => 'database'
                    ),
                    'website.analytics' => array(
                        'label' => _('Analytics'),
                        'icon'  => 'line-chart',

                        'subtabs' => array(
                            'website.pageviews'  => array(
                                'label' => _(
                                    'Pageviews'
                                ),
                                'icon'  => 'eye'
                            ),
                            'website.users'      => array(
                                'label' => _(
                                    'Users'
                                ),
                                'icon'  => 'terminal'
                            ),
                            'website.search'     => array(
                                'label'   => _(
                                    'Queries'
                                ),
                                'title'   => _(
                                    'Search Queries'
                                ),
                                'icon'    => 'search',
                                'subtabs' => array(
                                    'website.search.queries' => array(
                                        'label' => _(
                                            'Queries'
                                        ),
                                        'title' => _(
                                            'Search queries goruped by keywords'
                                        )
                                    ),
                                    'website.search.history' => array(
                                        'label' => _(
                                            'Search History'
                                        ),
                                        'title' => _(
                                            'List of all search queries'
                                        )
                                    ),

                                )

                            ),
                            'website.favourites' => array(
                                'label'   => _(
                                    'Favourites'
                                ),
                                'title'   => _(
                                    'Favourites'
                                ),
                                'icon'    => 'heart-o',
                                'subtabs' => array(
                                    'website.favourites.products'  => array(
                                        'label' => _(
                                            'Products'
                                        )
                                    ),
                                    'website.favourites.customers' => array(
                                        'label' => _(
                                            'Customers'
                                        )
                                    ),

                                )

                            ),
                            'website.reminders'  => array(
                                'label'   => _(
                                    'OOS Reminders'
                                ),
                                'title'   => _(
                                    'Out of stock reminders'
                                ),
                                'icon'    => 'hand-paper-o',
                                'subtabs' => array(
                                    'website.reminders.requests'  => array(
                                        'label' => _(
                                            'Requests'
                                        ),
                                        'title' => _(
                                            'Out of stock notifications requests'
                                        )
                                    ),
                                    'website.reminders.customers' => array(
                                        'label' => _(
                                            'Customers'
                                        ),
                                        'title' => _(
                                            'Customers who ask for a out of stock notification'
                                        )
                                    ),
                                    'website.reminders.products'  => array(
                                        'label' => _(
                                            'Products'
                                        ),
                                        'title' => _(
                                            'Out of stock notifications grouped by product'
                                        )
                                    ),

                                )

                            ),

                        )


                    ),

                    'website.style' => array(
                        'label'   => _('Look & feel'),
                        'icon'    => 'paint-brush',
                        'subtabs' => array(

                            'website.colours' => array(
                                'label' => _('Colours'),
                                'icon'  => 'tint',


                            ),

                            'website.fonts' => array(
                                'label' => _('Fonts'),
                                'icon'  => 'font',


                            ),


                            'website.localization' => array(
                                'label' => _('Localization'),
                                'icon'  => 'language',


                            ),


                        ),

                    ),

                    'website.templates' => array(
                        'label'   => _('Templates'),
                        'icon'    => 'code',
                        'subtabs' => array(


                            'website.header.preview' => array(
                                'label' => _('Header'),
                                'icon'  => 'header',


                            ),
                            'website.footer.preview' => array(
                                'label' => _('Footer'),
                                'icon'  => 'minus',


                            ),


                            'website.templates' => array(
                                'label' => '',
                                'title' => _('Templates'),
                                'icon'  => 'code',
                                'class' => 'right icon_only',


                            ),

                        ),

                    ),


                    'website.webpages' => array(
                        'label' => _('Web pages'),
                        'icon'  => 'files-o',


                        'subtabs' => array(


                            'website.in_process_webpages' => array(
                                'label' => _('In process web pages'),
                                'icon'  => 'child'
                            ),

                            'website.online_webpages' => array(
                                'label' => _('Online web pages'),
                                'icon'  => 'rocket '
                            ),

                            'website.root_nodes'       => array(
                                'label' => _('Sitemap'),
                                'icon'  => 'sitemap',
                                'class' => 'hide'
                            ),
                            'website.offline_webpages' => array(
                                'label' => _('Offline web pages'),
                                'icon'  => 'rocket fa-flip-vertical'
                            ),


                            'website.webpage.types' => array(
                                'label' => '',
                                'title' => _("Web pages's groups"),
                                'icon'  => 'server',
                                'class' => 'right icon_only',
                            ),

                        )

                    ),


                )
            ),

            'website.new' => array(
                'type' => 'new_object',
                'tabs' => array(
                    'website.new' => array(
                        'label' => _(
                            'New website'
                        )
                    ),

                )

            ),


            'no_website' => array(
                'type'      => '',
                'label'     => '',
                'icon'      => '',
                'reference' => '',
                'tabs'      => array(
                    'no_website' => array()
                )


            ),
            'webpage'    => array(
                'type'           => 'object',
                'label'          => _('Web page'),
                'icon'           => 'globe',
                'subtabs_parent' => array(
                    'webpage.favourites.families'  => 'webpage.favourites',
                    'webpage.favourites.products'  => 'webpage.favourites',
                    'webpage.favourites.customers' => 'webpage.favourites',
                    'webpage.search.queries'       => 'webpage.search',
                    'webpage.search.history'       => 'webpage.search',
                    'webpage.reminders.requests'   => 'webpage.reminders',
                    'webpage.reminders.customers'  => 'webpage.reminders',
                    'webpage.reminders.families'   => 'webpage.reminders',
                    'webpage.reminders.products'   => 'webpage.reminders',

                    'webpage.root_nodes'          => 'webpage.webpages',
                    'webpage.online_webpages'     => 'webpage.webpages',
                    'webpage.offline_webpages'    => 'webpage.webpages',
                    'webpage.webpage.types'       => 'webpage.webpages',
                    'webpage.in_process_webpages' => 'webpage.webpages',

                    'webpage.footer.preview' => 'webpage.templates',
                    'webpage.header.preview' => 'webpage.templates',


                    'webpage.templates' => 'webpage.templates',


                ),

                'tabs' => array(


                    'webpage.details' => array(
                        'label' => _(
                            'Data'
                        ),
                        'icon'  => 'database'
                    ),


                    'webpage.preview' => array(
                        'label' => _('Workshop'),
                        'icon'  => 'wrench'
                    ),

                    'webpage.analytics' => array(
                        'label' => _('Analytics'),
                        'icon'  => 'line-chart',

                        'subtabs' => array(
                            'webpage.pageviews' => array(
                                'label' => _(
                                    'Pageviews'
                                ),
                                'icon'  => 'eye'
                            ),
                            'webpage.users'     => array(
                                'label' => _(
                                    'Users'
                                ),
                                'icon'  => 'terminal'
                            ),


                        )


                    ),

                    'webpage.logbook' => array(
                        'label' => _('Logbook'),
                        'icon'  => 'road'
                    ),


                )
            ),

            'marketing' => array(
                'type'  => 'navigation',
                'label' => _('Marketing'),

                'icon'      => 'bullhorn',
                'reference' => 'marketing/%d',
                'tabs'      => array(
                    'campaigns' => array(
                        'label' => _('Campaigns'),
                        'icon'  => 'tags',
                    ),
                    'deals'     => array(
                        'label' => _('Offers'),
                        'icon'  => 'tag'
                    ),

                )

            ),


            'campaign' => array(
                'type' => 'object',


                'tabs' => array(
                    'campaign.details' => array(
                        'label' => _('Data'),
                        'icon'  => 'database',
                        'title' => _('Details')
                    ),

                    'campaign.deals'     => array(
                        'label' => _('Offers'),
                        'icon'  => 'tags'
                    ),
                    'campaign.orders'    => array(
                        'label' => _('Orders'),
                        'icon'  => 'shopping-cart'
                    ),
                    'campaign.customers' => array(
                        'label' => _('Customers'),
                        'icon'  => 'users'
                    ),
                    'campaign.history'   => array(
                        'label' => _(
                            'History, notes'
                        ),
                        'icon'  => 'road',
                        'class' => 'right icon_only'
                    ),

                )
            ),
            'deal'     => array(
                'type' => 'object',


                'tabs' => array(
                    'campaign.details'    => array(
                        'label' => _('Data'),
                        'icon'  => 'database',
                        'title' => _('Details')
                    ),
                    'campaign.history'    => array(
                        'label' => _(
                            'History, notes'
                        ),
                        'icon'  => 'sticky-note-o'
                    ),
                    'campaign.allowances' => array(
                        'label' => _('Allowances'),
                        'icon'  => ''
                    ),
                    'campaign.orders'     => array(
                        'label' => _('Orders'),
                        'icon'  => 'shopping-cart'
                    ),
                    'campaign.customers'  => array(
                        'label' => _('Customers'),
                        'icon'  => 'users'
                    )

                )
            ),

            'campaign.new' => array(
                'type' => 'new_object',
                'tabs' => array(
                    'campaign.new' => array(
                        'label' => _(
                            'New campaign'
                        )
                    ),

                )

            ),

            'deal.new' => array(
                'type' => 'new_object',
                'tabs' => array(
                    'deal.new' => array(
                        'label' => _(
                            'New offer'
                        )
                    ),

                )

            ),


            'category'          => array(
                'type'           => 'object',
                'subtabs_parent' => array(
                    'category.product.sales.plot'     => 'category.sales',
                    'category.product.sales.history'  => 'category.sales',
                    'category.product.sales.calendar' => 'category.sales',
                    'category.product.sales.info'     => 'category.sales',
                    'category.webpage.preview'        => 'category.webpage',
                    'category.webpage.settings'       => 'category.webpage',
                    'category.webpage.products'       => 'category.webpage',
                    'category.webpage.analytics'      => 'category.webpage',
                    'category.webpage.logbook'        => 'category.webpage'

                ),
                'tabs'           => array(
                    'category.details'    => array(
                        'label' => _('Data'),
                        'icon'  => 'database'
                    ),
                    'category.categories' => array(
                        'label' => _(
                            'Subcategories'
                        )
                    ),


                    'category.webpage' => array(
                        'label'   => _('Website'),
                        'icon'    => 'globe',
                        'subtabs' => array(
                            'category.webpage.settings' => array(
                                'label' => _('Settings'),
                                'icon'  => 'sliders'
                            ),

                            'category.webpage.preview' => array(
                                'label' => _('Workshop'),
                                'icon'  => 'wrench'
                            ),
                            'category.webpage.logbook' => array(
                                'label' => _('Logbook'),
                                'icon'  => 'road'
                            ),
                            //   'category.webpage.analytics'  => array(
                            //      'label' => _('Analytics'),
                            //       'icon'=>'line-chart'
                            //  ),


                        )
                    ),

                    'category.sales' => array(
                        'label'   => _('Sales'),
                        'subtabs' => array(
                            'category.product.sales.plot'     => array(
                                'label' => _(
                                    'Plot'
                                )
                            ),
                            'category.product.sales.history'  => array(
                                'label' => _(
                                    'Sales history'
                                )
                            ),
                            'category.product.sales.calendar' => array(
                                'label' => _(
                                    'Calendar'
                                )
                            ),
                            'category.product.sales.info'     => array(
                                'label' => _('Info'),
                                'icon'  => 'info',
                                'class' => 'right icon_only'
                            ),

                        )

                    ),

                    'category.subjects' => array('label' => ''),


                    'category.history' => array(
                        'title'         => _('History/Notes'),
                        'label'         => '',
                        'quantity_data' => array(
                            'object' => '_object',
                            'field'  => 'Number History Records'
                        ),
                        'icon'          => 'road',
                        'class'         => 'right icon_only'
                    ),
                    'category.images'  => array(
                        'title'         => _('Images'),
                        'label'         => '',
                        'quantity_data' => array(
                            'object' => '_object',
                            'field'  => 'Number Images'
                        ),
                        'icon'          => 'camera-retro',
                        'class'         => 'right icon_only'
                    ),


                )

            ),
            'main_category.new' => array(
                'type' => 'new_object',
                'tabs' => array(
                    'main_category.new' => array(
                        'label' => _(
                            'New category'
                        )
                    ),

                )

            ),
            'product'           => $_product,
            'product.new'       => array(
                'type' => 'new_object',
                'tabs' => array(
                    'product.new' => array(
                        'label' => _(
                            'New product'
                        )
                    ),

                )

            ),
            'service'           => $_service,
            'service.new'       => array(
                'type' => 'new_object',
                'tabs' => array(
                    'service.new' => array(
                        'label' => _(
                            'New service'
                        )
                    ),

                )

            ),


            'upload'        => array(
                'type' => 'object',
                'tabs' => array(
                    'upload.records' => array(
                        'label' => _(
                            'Upload Records'
                        )
                    ),

                )

            ),
            'product.image' => array(
                'type' => 'object',


                'tabs' => array(


                    'product.image.details' => array(
                        'label' => _(
                            'Data'
                        ),
                        'icon'  => 'database'
                    ),
                    'product.history'       => array(
                        'label' => _(
                            'History/Notes'
                        ),
                        'icon'  => 'road',
                        'class' => 'right icon_only'
                    ),

                )
            ),

            'order' => array(
                'type' => 'object',
                'tabs' => array(


                    'order.items'          => array(
                        'label' => _(
                            'Items'
                        ),
                        'icon'  => 'bars'
                    ),
                    'order.details'        => array(
                        'label' => _(
                            'Data'
                        ),
                        'icon'  => 'database'
                    ),
                    'order.history'        => array(
                        'label' => _(
                            'History'
                        ),
                        'icon'  => 'road'
                    ),
                    'order.delivery_notes' => array(
                        'label' => _(
                            'Delivery notes'
                        ),
                        'icon'  => 'truck'
                    ),
                    'order.invoices'       => array(
                        'label' => _(
                            'Invoices'
                        ),
                        'icon'  => 'file-text-o'
                    ),
                    'order.payments'       => array(
                        'label' => _(
                            'Payments'
                        ),
                        'icon'  => 'usd'
                    ),

                )

            ),

        )
    ),
    'products_server' => array(

        'parent'      => 'none',
        'parent_type' => 'none',

        'sections' => array(
            'stores'   => array(
                'type'           => 'navigation',
                'label'          => _('Stores'),
                'icon'           => 'shopping-bag',
                'reference'      => 'stores',
                'showcase'       => 'account',
                'subtabs_parent' => array(
                    'stores.sales.plot'     => 'stores.sales',
                    'stores.sales.history'  => 'stores.sales',
                    'stores.sales.calendar' => 'stores.sales',
                    'stores.sales.info'     => 'stores.sales'

                ),
                'tabs'           => array(
                    'stores'       => array('label' => _('Stores')),
                    'stores.sales' => array(
                        'label'   => _('Sales'),
                        'subtabs' => array(
                            'stores.sales.plot'     => array(
                                'label' => _(
                                    'Plot'
                                )
                            ),
                            'stores.sales.history'  => array(
                                'label' => _(
                                    'Sales history'
                                )
                            ),
                            'stores.sales.calendar' => array(
                                'label' => _(
                                    'Calendar'
                                )
                            ),
                            'stores.sales.info'     => array(
                                'label' => _('Info'),
                                'icon'  => 'info',
                                'class' => 'right icon_only'
                            ),

                        )

                    ),
                )
            ),
            'products' => array(
                'type'      => 'navigation',
                'label'     => _('Products'),
                'icon'      => 'cube',
                'reference' => 'products/all',
                'tabs'      => array(


                    'products' => array(
                        'label' => _(
                            'Products'
                        ),
                        'icon'  => 'cube'
                    ),

                )
            ),

        )
    ),


    'marketingx'        => array(
        'section'     => 'marketing',
        'parent'      => 'store',
        'parent_type' => 'key',
        'sections'    => array(

            'dashboard' => array(
                'type'      => 'navigation',
                'label'     => _('Dashboard'),
                'title'     => _("Marketing dashboard"),
                'icon'      => 'dashboard',
                'reference' => 'marketing/%d',
                'tabs'      => array(
                    'marketing.dashboard' => array()
                )
            ),


            'deals' => array(
                'type'      => 'navigation',
                'label'     => _('Offers'),
                'icon'      => 'tag',
                'reference' => 'deals/%d',
                'tabs'      => array(

                    'deals' => array(
                        'label' => _('Offers')
                    ),

                )


            ),

            'campaigns' => array(
                'type'      => 'navigation',
                'label'     => _('Campaigns'),
                'icon'      => 'tags',
                'reference' => 'campaigns/%d',
                'tabs'      => array(
                    'campaigns' => array(
                        'label' => _('Campaigns')
                    )


                )


            ),


            'campaign' => array(
                'type' => 'object',


                'tabs' => array(
                    'campaign.details' => array(
                        'label' => _('Data'),
                        'icon'  => 'database',
                        'title' => _('Details')
                    ),

                    'campaign.deals'     => array(
                        'label' => _('Offers'),
                        'icon'  => 'tags'
                    ),
                    'campaign.orders'    => array(
                        'label' => _('Orders'),
                        'icon'  => 'shopping-cart'
                    ),
                    'campaign.customers' => array(
                        'label' => _('Customers'),
                        'icon'  => 'users'
                    ),
                    'campaign.history'   => array(
                        'label' => _(
                            'History, notes'
                        ),
                        'icon'  => 'road',
                        'class' => 'right icon_only'
                    ),

                )
            ),
            'deal'     => array(
                'type' => 'object',


                'tabs' => array(
                    'campaign.details'    => array(
                        'label' => _('Data'),
                        'icon'  => 'database',
                        'title' => _('Details')
                    ),
                    'campaign.history'    => array(
                        'label' => _(
                            'History, notes'
                        ),
                        'icon'  => 'sticky-note-o'
                    ),
                    'campaign.allowances' => array(
                        'label' => _('Allowances'),
                        'icon'  => ''
                    ),
                    'campaign.orders'     => array(
                        'label' => _('Orders'),
                        'icon'  => 'shopping-cart'
                    ),
                    'campaign.customers'  => array(
                        'label' => _('Customers'),
                        'icon'  => 'users'
                    )

                )
            ),

            'campaign.new' => array(
                'type' => 'new_object',
                'tabs' => array(
                    'campaign.new' => array(
                        'label' => _(
                            'New campaign'
                        )
                    ),

                )

            ),

            'deal.new' => array(
                'type' => 'new_object',
                'tabs' => array(
                    'deal.new' => array(
                        'label' => _(
                            'New offer'
                        )
                    ),

                )

            ),

            /*
			,
			'enewsletters'=>array('type'=>'navigation', 'label'=>_('eNewsletters'), 'title'=>_('eNewsletters'), 'icon'=>'newspaper-o', 'reference'=>'marketing/%d/enewsletters',
				'tabs'=>array(
					'enewsletters'=>array()
				)
			),
			'mailshots'=>array('type'=>'navigation', 'label'=>_('Mailshots'), 'title'=>_('Mailshots'), 'icon'=>'at', 'reference'=>'marketing/%d/mailshots',
				'tabs'=>array(
					'mailshots'=>array()
				)),
			'marketing_post'=>array('type'=>'navigation', 'label'=>_('Marketing Post'), 'title'=>_('Marketing Post'), 'icon'=>'envelope-o', 'reference'=>'marketing/%d/marketing_post',
				'tabs'=>array(
					'marketing_post'=>array()
				)
			),
			'marketing_media'=>array('type'=>'navigation', 'label'=>_('Marketing Media'), 'title'=>_('Marketing Media'), 'icon'=>'google', 'reference'=>'marketing/%d/marketing_media',
				'tabs'=>array(
					'marketing_media'=>array()
				)
			),
			'ereminders'=>array('type'=>'navigation', 'label'=>_('eReminders'), 'title'=>_('eReminders'), 'icon'=>'bell-o', 'reference'=>'marketing/%d/ereminders',
				'tabs'=>array(
					'ereminders'=>array()
				)
			),
*/


        )
    ),
    'marketingx_server' => array(

        'parent'      => 'none',
        'parent_type' => 'none',
        'section'     => 'marketing',
        'sections'    => array(
            'marketing' => array(
                'type'      => 'navigation',
                'label'     => _('Marketing (All stores)'),
                'title'     => _('Marketing (All stores)'),
                'icon'      => '',
                'reference' => 'marketing/all',
                'tabs'      => array(
                    'marketing_server' => array()
                )
            ),

        )

    ),


    'production_server' => array(
        'sections' => array(

            'production.suppliers' => array(

                'type'      => 'navigation',
                'label'     => _('Suppliers'),
                'icon'      => 'ship',
                'reference' => 'production/all',
                'tabs'      => array(
                    'production.suppliers' => array()
                )
            )

        )
    ),

    'production'        => array(
        'section'     => 'production',
        'parent'      => 'account',
        'parent_type' => 'key',
        'sections'    => array(
            'dashboard' => array(
                'type'      => 'navigation',
                'label'     => _('Dashboard'),
                'title'     => _("Manufacture dashboard"),
                'icon'      => 'dashboard',
                'reference' => 'production/%d',
                'tabs'      => array(
                    'production.dashboard' => array()
                )
            ),


            'supplier_parts' => array(
                'type'      => 'navigation',
                'label'     => _('Parts'),
                'icon'      => 'stop',
                'reference' => 'production/%d/parts',
                'tabs'      => array(
                    'production.supplier_parts' => array('label' => _('Parts'))
                )


            ),
            'supplier_part'  => array(
                'type' => 'object',


                'tabs' => array(


                    'supplier_part.details' => array(
                        'label' => _(
                            'Data'
                        ),
                        'icon'  => 'database'
                    ),


                    'supplier_part.images'  => array(
                        'label' => _(
                            'Images'
                        ),
                        'icon'  => 'camera-retro',
                        'class' => 'right icon_only'
                    ),
                    'supplier_part.history' => array(
                        'label' => _(
                            'History/Notes'
                        ),
                        'icon'  => 'road',
                        'class' => 'right icon_only'
                    ),


                )
            ),
            'batches'        => array(
                'type'      => 'navigation',
                'label'     => _('Batches'),
                'icon'      => 'clone',
                'reference' => 'production/%d/batches',
                'tabs'      => array(
                    'batches' => array('label' => _('Batches'))
                )


            ),

            'materials' => array(
                'type'      => 'navigation',
                'label'     => _('Materials'),
                'icon'      => 'puzzle-piece',
                'reference' => 'production/%d/materials',
                'tabs'      => array(
                    'production.materials' => array('label' => _('materials'))
                )


            ),

            'manufacture_tasks' => array(
                'type'      => 'navigation',
                'label'     => _('Tasks'),
                'icon'      => 'tasks',
                'reference' => 'production/%d/manufacture_tasks',
                'tabs'      => array(
                    'manufacture_tasks' => array('label' => _('Tasks'))
                )


            ),


            'manufacture_task' => array(
                'type'  => 'object',
                'label' => _('Task'),
                'icon'  => 'tasks',
                'tabs'  => array(
                    'manufacture_task.details' => array('label' => _('Data')),
                    'manufacture_task.batches' => array('label' => _('Batches'))

                )


            ),

            'manufacture_task.new' => array(
                'type' => 'new_object',
                'tabs' => array(
                    'manufacture_task.new' => array(
                        'label' => _(
                            'New task'
                        )
                    ),

                )

            ),


            'operatives' => array(
                'type'      => 'navigation',
                'label'     => _('Operatives'),
                'icon'      => 'hand-rock-o',
                'reference' => 'production/%d/operatives',
                'tabs'      => array(
                    'operatives' => array('label' => _('Operatives'))
                )


            ),
            'settings'   => array(
                'type'      => 'navigation',
                'label'     => '',
                'icon'      => 'sliders',
                'reference' => 'production/%d/settings',
                'class'     => 'icon_only',
                'tabs'      => array(
                    'production.settings' => array(
                        'label' => _(
                            'General settings'
                        ),
                        'icon'  => 'sliders',
                        'class' => ''
                    ),


                )


            ),

        )
    ),
    'suppliers'         => array(
        'section'     => 'suppliers',
        'parent'      => 'store',
        'parent_type' => 'key',
        'sections'    => array(
            /*
			'dashboard'=>array('type'=>'navigation', 'label'=>_('Dashboard'), 'title'=>_("Supplier's dashboard"), 'icon'=>'dashboard', 'reference'=>'suppliers/dashboard',
				'tabs'=>array(
					'suppliers.dashboard'=>array()
				)
			),
            */
            'suppliers' => array(

                'type'      => 'navigation',
                'label'     => _('Suppliers'),
                'icon'      => 'ship',
                'reference' => 'suppliers',
                'tabs'      => array(
                    'suppliers' => array()
                )


            ),
            'agents'    => array(
                'type'      => 'navigation',
                'label'     => _('Agents'),
                'icon'      => 'user-secret',
                'reference' => 'agents',
                'tabs'      => array(
                    'agents' => array()
                )
            ),


            'categories' => array(
                'type'      => 'navigation',
                'label'     => _('Categories'),
                'title'     => _('Categories'),
                'icon'      => 'sitemap',
                'reference' => 'suppliers/categories',
                'tabs'      => array(
                    'suppliers.categories' => array()
                )

            ),


            'main_category.new' => array(
                'type' => 'new_object',
                'tabs' => array(
                    'main_category.new' => array(
                        'label' => _(
                            'New category'
                        )
                    ),

                )

            ),

            'orders'     => array(
                'type'      => 'navigation',
                'label'     => _('Purchase orders'),
                'icon'      => 'clipboard',
                'reference' => 'suppliers/orders',
                'tabs'      => array(
                    'suppliers.orders' => array()
                )
            ),
            'deliveries' => array(
                'type'      => 'navigation',
                'label'     => _('Deliveries'),
                'icon'      => 'truck',
                'reference' => 'deliveries',
                'tabs'      => array(
                    'suppliers.deliveries' => array()
                )
            ),

            'order' => array(
                'type' => 'object',
                'tabs' => array(

                    'supplier.order.details' => array(
                        'label' => _(
                            'Data'
                        ),
                        'icon'  => 'database'
                    ),

                    'supplier.order.items'      => array(
                        'label' => _(
                            'Items'
                        ),
                        'icon'  => 'bars'
                    ),
                    // 'supplier.order.supplier_parts'=>array('label'=>_('Parts'), 'icon'=>'stop'),
                    // 'supplier.order.invoices'=>array('label'=>_('Invoices'),  'icon'=>'file-text-o'),
                    'supplier.order.tac.editor' => array(
                        'label' => _(
                            'Terms and conditions'
                        ),
                        'icon'  => 'gavel',
                        'class' => ''
                    ),
                    'supplier.order.history'    => array(
                        'label' => _(
                            'History/Notes'
                        ),
                        'icon'  => 'road',
                        'class' => 'right icon_only'
                    ),

                )

            ),

            'deleted_order' => array(
                'type' => 'object',
                'tabs' => array(


                    'deleted.supplier.order.items'   => array(
                        'label' => _(
                            'Items'
                        ),
                        'icon'  => 'bars'
                    ),
                    'deleted.supplier.order.history' => array(
                        'label' => _(
                            'History/Notes'
                        ),
                        'icon'  => 'road',
                        'class' => 'right icon_only'
                    ),

                )

            ),

            'delivery' => array(
                'type' => 'object',
                'tabs' => array(

                    'supplier.delivery.details' => array(
                        'label' => _(
                            'Data'
                        ),
                        'icon'  => 'database'
                    ),

                    'supplier.delivery.items'   => array(
                        'label' => _(
                            'Items'
                        ),
                        'icon'  => 'bars'
                    ),
                    'supplier.delivery.history' => array(
                        'label' => _(
                            'History/Notes'
                        ),
                        'icon'  => 'road',
                        'class' => 'right icon_only'
                    ),

                )

            ),

            'category'  => array(
                'type' => 'object',

                'tabs' => array(
                    'category.details'    => array(
                        'label' => _('Data'),
                        'icon'  => 'database',
                        'title' => _('Details')
                    ),
                    'category.history'    => array(
                        'label' => _(
                            'History, notes'
                        ),
                        'icon'  => 'sticky-note-o'
                    ),
                    'category.subjects'   => array('label' => _('Suppliers')),
                    'category.categories' => array(
                        'label' => _(
                            'Subcategories'
                        )
                    ),

                )

            ),
            'agent'     => array(
                'type'      => 'object',
                'label'     => _('agent'),
                'icon'      => 'ship',
                'reference' => 'agent/%d',
                'tabs'      => array(
                    'agent.details'   => array(
                        'label' => _('Data'),
                        'icon'  => 'database',
                        'title' => _('Details')
                    ),
                    'agent.suppliers' => array(
                        'label' => _(
                            "Agent's suppliers"
                        ),
                        'icon'  => 'ship'
                    ),

                    'agent.supplier_parts' => array(
                        'label' => _("Agent's Parts"),
                        'icon'  => 'stop'
                    ),
                    'agent.orders'         => array(
                        'label' => _('Orders'),
                        'icon'  => 'clipboard'
                    ),
                    'agent.deliveries'     => array(
                        'label' => _('Deliveries'),
                        'icon'  => 'truck'
                    ),
                    'agent.agent_orders'   => array(
                        'label' => _("Agent's PO"),
                        'icon'  => 'clipboard fa-flip-horizontal'
                    ),

                    'agent.history' => array(
                        'label' => _('History/Notes'),
                        'icon'  => 'road',
                        'class' => 'right icon_only'
                    ),
                    'agent.users'   => array(
                        'label' => _('System users'),
                        'icon'  => 'terminal',
                        'class' => 'right icon_only'
                    ),


                )
            ),
            'agent.new' => array(
                'type' => 'new_object',
                'tabs' => array(
                    'agent.new' => array(
                        'label' => _(
                            'New agent'
                        )
                    ),

                )

            ),
            'supplier'  => array(
                'type'      => 'object',
                'label'     => _('Supplier'),
                'icon'      => 'ship',
                'reference' => 'supplier/%d',

                'subtabs_parent' => array(
                    'supplier.sales.plot'      => 'supplier.sales',
                    'supplier.sales.history'   => 'supplier.sales',
                    'supplier.sales.calendar'  => 'supplier.sales',
                    'supplier.sales.dashboard' => 'supplier.sales',
                    'supplier.sales.info'      => 'supplier.sales',

                ),

                'tabs' => array(
                    'supplier.details' => array(
                        'label' => _('Data'),
                        'icon'  => 'database',
                        'title' => _('Details')
                    ),
                    'supplier.sales'   => array(
                        'label' => _("Part's sales"),
                        'icon'  => ''
                    ),


                    'supplier.sales' => array(
                        'label'   => _('Purchases/Sales'),
                        'subtabs' => array(
                            'supplier.sales.dashboard' => array(
                                'label' => _(
                                    'Dashboard'
                                )
                            ),
                            'supplier.sales.plot'      => array(
                                'label' => _(
                                    'Plot'
                                )
                            ),
                            'supplier.sales.history'   => array(
                                'label' => _(
                                    'Sales history'
                                )
                            ),
                            'supplier.sales.calendar'  => array(
                                'label' => _(
                                    'Calendar'
                                )
                            ),


                            'supplier.sales.info' => array(
                                'label' => '',
                                'title' => _('Info'),
                                'icon'  => 'info',
                                'class' => 'right icon_only'
                            ),


                        )
                    ),

                    'supplier.supplier_parts' => array(
                        'label'         => _("Supplier's Parts"),
                        'quantity_data' => array(
                            'object' => '_object',
                            'field'  => 'Number Parts'
                        ),
                        'icon'          => 'stop'
                    ),
                    'supplier.orders'         => array(
                        'label'         => _(
                            'Purchase orders'
                        ),
                        'quantity_data' => array(
                            'object' => '_object',
                            'field'  => 'Number Purchase Orders'
                        ),
                        'icon'          => 'clipboard'
                    ),
                    'supplier.deliveries'     => array(
                        'label'         => _('Deliveries'),
                        'quantity_data' => array(
                            'object' => '_object',
                            'field'  => 'Number Deliveries'
                        ),
                        'icon'          => 'truck'
                    ),


                    'supplier.history' => array(
                        'title'         => _('History/Notes'),
                        'quantity_data' => array(
                            'object' => '_object',
                            'field'  => 'Number History Records'
                        ),
                        'label'         => '',
                        'icon'          => 'road',
                        'class'         => 'right icon_only'
                    ),
                    'supplier.users'   => array(
                        'title'         => _('System users'),
                        'quantity_data' => array(
                            'object' => '_object',
                            'field'  => 'Number System Users'
                        ),
                        'label'         => '',
                        'icon'          => 'user',
                        'class'         => 'right icon_only'
                    ),

                    'supplier.attachments' => array(
                        'title'         => _('Attachments'),
                        'label'         => '',
                        'quantity_data' => array(
                            'object' => '_object',
                            'field'  => 'Number Attachments'
                        ),
                        'icon'          => 'paperclip',
                        'class'         => 'right icon_only'
                    ),

                )
            ),


            'supplier.new'            => array(
                'type' => 'new_object',
                'tabs' => array(
                    'supplier.new' => array(
                        'label' => _(
                            'New supplier'
                        )
                    ),

                )

            ),
            'supplier.attachment.new' => array(
                'type' => 'new_object',
                'tabs' => array(
                    'supplier.attachment.new' => array(
                        'label' => _(
                            'new attachment'
                        )
                    ),

                )

            ),
            'supplier.attachment'     => array(
                'type' => 'object',
                'tabs' => array(
                    'supplier.attachment.details' => array(
                        'label' => _(
                            'Data'
                        ),
                        'icon'  => 'database'
                    ),
                    'supplier.attachment.history' => array(
                        'label' => _(
                            'History'
                        ),
                        'icon'  => 'clock-o'
                    ),

                )

            ),
            'deleted_supplier'        => array(
                'type' => 'object',
                'tabs' => array(
                    'supplier.history' => array(
                        'label' => _('History'),
                        'icon'  => 'road'
                    ),


                )
            ),

            'supplier.order.item' => array(
                'type'           => 'object',
                'subtabs_parent' => array(

                    'supplier_part.purchase_orders.purchase_orders' => 'supplier_part.purchase_orders',
                    'supplier_part.purchase_orders.delivery_notes'  => 'supplier_part.purchase_orders',
                    'supplier_part.purchase_orders.invoices'        => 'supplier_part.purchase_orders',
                ),


                'tabs' => array(


                    'supplier.order.item.details' => array(
                        'label' => _(
                            'Data'
                        ),
                        'icon'  => 'database'
                    ),


                    'supplier_part.purchase_orders' => array(
                        'label'   => _(
                            'Purchase Orders'
                        ),
                        'icon'    => 'clipboard',
                        'class'   => 'right icon_only',
                        'subtabs' => array(
                            'supplier_part.purchase_orders.purchase_orders' => array(
                                'label' => _(
                                    'Purchase Orders'
                                )
                            ),
                            'supplier_part.purchase_orders.delivery_notes'  => array(
                                'label' => _(
                                    'Delivery Notes'
                                )
                            ),
                            'supplier_part.purchase_orders.invoices'        => array(
                                'label' => _(
                                    'Invoices'
                                )
                            ),

                        )

                    ),

                    'supplier_part.images'  => array(
                        'label' => _(
                            'Images'
                        ),
                        'icon'  => 'camera-retro',
                        'class' => 'right icon_only'
                    ),
                    'supplier_part.history' => array(
                        'label' => _(
                            'History/Notes'
                        ),
                        'icon'  => 'road',
                        'class' => 'right icon_only'
                    ),


                )
            ),

            'agent.order.item' => array(
                'type'           => 'object',
                'subtabs_parent' => array(

                    'supplier_part.purchase_orders.purchase_orders' => 'supplier_part.purchase_orders',
                    'supplier_part.purchase_orders.delivery_notes'  => 'supplier_part.purchase_orders',
                    'supplier_part.purchase_orders.invoices'        => 'supplier_part.purchase_orders',
                ),


                'tabs' => array(


                    'agent.order.item.details' => array(
                        'label' => _(
                            'Data'
                        ),
                        'icon'  => 'database'
                    ),


                    'supplier_part.purchase_orders' => array(
                        'label'   => _(
                            'Purchase Orders'
                        ),
                        'icon'    => 'clipboard',
                        'class'   => 'right icon_only',
                        'subtabs' => array(
                            'supplier_part.purchase_orders.purchase_orders' => array(
                                'label' => _(
                                    'Purchase Orders'
                                )
                            ),
                            'supplier_part.purchase_orders.delivery_notes'  => array(
                                'label' => _(
                                    'Delivery Notes'
                                )
                            ),
                            'supplier_part.purchase_orders.invoices'        => array(
                                'label' => _(
                                    'Invoices'
                                )
                            ),

                        )

                    ),

                    'supplier_part.images'  => array(
                        'label' => _(
                            'Images'
                        ),
                        'icon'  => 'camera-retro',
                        'class' => 'right icon_only'
                    ),
                    'supplier_part.history' => array(
                        'label' => _(
                            'History/Notes'
                        ),
                        'icon'  => 'road',
                        'class' => 'right icon_only'
                    ),


                )
            ),

            'supplier_part' => array(
                'type'           => 'object',
                'subtabs_parent' => array(

                    'supplier_part.supplier.orders'     => 'supplier_part.purchase_orders',
                    'supplier_part.supplier.deliveries' => 'supplier_part.purchase_orders',
                ),


                'tabs' => array(


                    'supplier_part.details' => array(
                        'label' => _(
                            'Data'
                        ),
                        'icon'  => 'database'
                    ),


                    'supplier_part.purchase_orders' => array(
                        'label'   => _(
                            'Purchase orders / deliveries'
                        ),
                        'icon'    => 'clipboard',
                        'subtabs' => array(

                            'supplier_part.supplier.orders'     => array(
                                'label' => _(
                                    'Purchase orders'
                                ),
                                'icon'  => 'clipboard'
                            ),
                            'supplier_part.supplier.deliveries' => array(
                                'label' => _(
                                    "Supplier's deliveries"
                                ),
                                'icon'  => 'truck'
                            ),

                        )

                    ),

                    'supplier_part.images'  => array(
                        'label' => _(
                            'Images'
                        ),
                        'icon'  => 'camera-retro',
                        'class' => 'right icon_only'
                    ),
                    'supplier_part.history' => array(
                        'label' => _(
                            'History/Notes'
                        ),
                        'icon'  => 'road',
                        'class' => 'right icon_only'
                    ),


                )
            ),

            'supplier_part.historic' => array(
                'type'           => 'object',
                'subtabs_parent' => array(

                    'supplier_part.purchase_orders.purchase_orders' => 'supplier_part.purchase_orders',
                    'supplier_part.purchase_orders.delivery_notes'  => 'supplier_part.purchase_orders',
                    'supplier_part.purchase_orders.invoices'        => 'supplier_part.purchase_orders',
                ),


                'tabs' => array(


                    'supplier_part.details' => array(
                        'label' => _(
                            'Data'
                        ),
                        'icon'  => 'database'
                    ),


                    'supplier_part.purchase_orders' => array(
                        'label'   => _(
                            'Purchase Orders'
                        ),
                        'icon'    => 'clipboard',
                        'subtabs' => array(
                            'supplier_part.purchase_orders.purchase_orders' => array(
                                'label' => _(
                                    'Purchase Orders'
                                )
                            ),
                            'supplier_part.purchase_orders.delivery_notes'  => array(
                                'label' => _(
                                    'Delivery Notes'
                                )
                            ),
                            'supplier_part.purchase_orders.invoices'        => array(
                                'label' => _(
                                    'Invoices'
                                )
                            ),

                        )

                    ),

                    'supplier_part.images'  => array(
                        'label' => _(
                            'Images'
                        ),
                        'icon'  => 'camera-retro',
                        'class' => 'right icon_only'
                    ),
                    'supplier_part.history' => array(
                        'label' => _(
                            'History/Notes'
                        ),
                        'icon'  => 'road',
                        'class' => 'right icon_only'
                    ),


                )
            ),

            'supplier_part.new' => array(
                'type' => 'new_object',
                'tabs' => array(
                    'supplier_part.new' => array(
                        'label' => _(
                            'New part'
                        )
                    ),

                )

            ),
            'supplier.user.new' => array(
                'type' => 'new_object',
                'tabs' => array(
                    'supplier.user.new' => array(
                        'label' => _(
                            'New system user'
                        )
                    ),

                )

            ),
            'agent.user.new'    => array(
                'type' => 'new_object',
                'tabs' => array(
                    'agent.user.new' => array(
                        'label' => _(
                            'New system user'
                        )
                    ),

                )

            ),

            'settings' => array(
                'type'      => 'navigation',
                'label'     => '',
                'icon'      => 'sliders',
                'reference' => 'suppliers/settings',
                'class'     => 'icon_only',
                'tabs'      => array(
                    'suppliers.settings'       => array(
                        'label' => _(
                            'General settings'
                        ),
                        'icon'  => 'sliders',
                        'class' => ''
                    ),
                    'suppliers.email_template' => array(
                        'label' => _(
                            'Email template'
                        ),
                        'icon'  => 'envelope-o',
                        'class' => ''
                    ),


                )


            ),
        )
    ),
    'warehouses_server' => array(
        'sections' => array(

            'warehouses' => array(

                'type'      => 'navigation',
                'label'     => _('Warehouses'),
                'title'     => _('Warehouses'),
                'icon'      => 'map-maker',
                'reference' => 'warehouses',
                'tabs'      => array(
                    'warehouses' => array()
                )
            )

        )
    ),

    'inventory'       => array(
        'sections' => array(

            'dashboard' => array(
                'type'      => 'navigation',
                'label'     => _('Dashboard'),
                'icon'      => 'tachometer',
                'reference' => 'inventory/dashboard',
                'tabs'      => array(
                    'inventory.dashboard' => array('label' => _('Dashboard'))

                )
            ),


            'inventory' => array(

                'type'      => 'navigation',
                'label'     => _('Inventory').' ('._('Parts').')',
                'icon'      => 'th-large',
                'reference' => 'inventory',
                'tabs'      => array(
                    'inventory.in_process_parts'    => array(
                        'label' => _(
                            'In process'
                        ),
                        'class' => 'discreet'
                    ),
                    'inventory.parts'               => array(
                        'label' => _(
                            'Active'
                        )
                    ),
                    'inventory.discontinuing_parts' => array(
                        'label' => _(
                            'Discontinuing'
                        ),
                        'class' => 'discreet'
                    ),
                    'inventory.discontinued_parts'  => array(
                        'label' => _(
                            'Discontinued'
                        ),
                        'class' => 'very_discreet'
                    ),

                )
            ),


            'barcodes'          => array(
                'type'      => 'navigation',
                'label'     => _('Retail barcodes'),
                'icon'      => 'barcode',
                'reference' => 'inventory/barcodes',
                'tabs'      => array(
                    'inventory.barcodes' => array('label' => _('Barcodes'))

                )
            ),
            'barcode'           => array(
                'type' => 'object',
                'tabs' => array(
                    'barcode.details' => array(
                        'label' => _('Data'),
                        'icon'  => 'database'
                    ),
                    'barcode.history' => array(
                        'label' => _('History'),
                        'icon'  => 'road'
                    ),

                    'barcode.assets' => array(
                        'label' => _('Products/Parts'),
                        'icon'  => 'cube'
                    ),

                )
            ),
            'deleted_barcode'   => array(
                'type' => 'object',
                'tabs' => array(
                    'barcode.history' => array(
                        'label' => _('History'),
                        'icon'  => 'road'
                    ),


                )
            ),
            'categories'        => array(
                'type'      => 'navigation',
                'label'     => _("Part's categories"),
                'icon'      => 'sitemap',
                'reference' => 'inventory/categories',

                'tabs' => array(
                    'parts.categories' => array(
                        'label' => _(
                            "Part's categories"
                        )
                    ),
                )
            ),
            'main_category.new' => array(
                'type' => 'new_object',
                'tabs' => array(
                    'main_category.new' => array(
                        'label' => _(
                            'New category'
                        )
                    ),

                )

            ),
            'category'          => array(
                'type'           => 'object',
                'subtabs_parent' => array(

                    'category.part.sales.plot'     => 'category.part.sales',
                    'category.part.sales.history'  => 'category.part.sales',
                    'category.part.sales.calendar' => 'category.part.sales',
                    'category.part.sales.info'     => 'category.part.sales'

                ),
                'tabs'           => array(

                    'category.details' => array(
                        'label' => _(
                            'Data'
                        ),
                        'icon'  => 'database'
                    ),

                    'category.part.sales' => array(
                        'label'   => _(
                            'Sales'
                        ),
                        'icon'    => '',
                        'subtabs' => array(
                            'category.part.sales.plot'     => array(
                                'label' => _(
                                    'Plot'
                                )
                            ),
                            'category.part.sales.history'  => array(
                                'label' => _(
                                    'Sales history'
                                )
                            ),
                            'category.part.sales.calendar' => array(
                                'label' => _(
                                    'Calendar'
                                )
                            ),
                            'category.part.sales.info'     => array(
                                'label' => _(
                                    'Info'
                                ),
                                'icon'  => 'info',
                                'class' => 'right icon_only'
                            ),

                        )

                    ),

                    'category.subjects' => array(
                        'label'         => _('Parts'),
                        'quantity_data' => array(
                            'object' => '_object',
                            'field'  => 'Parts'
                        ),
                    ),

                    'category.part.discontinued_subjects' => array(
                        'label'         => _('Discontinued parts'),
                        'quantity_data' => array(
                            'object' => '_object',
                            'field'  => 'Discontinued'
                        ),
                    ),

                    'category.categories'          => array(
                        'label' => _(
                            'Subcategories'
                        )
                    ),
                    'part_family.product_families' => array(
                        'label' => _(
                            'Product families'
                        )
                    ),

                    'category.images'  => array(
                        'title'         => _('Images'),
                        'label'         => '',
                        'quantity_data' => array(
                            'object' => '_object',
                            'field'  => 'Number Images'
                        ),
                        'icon'          => 'camera-retro',
                        'class'         => 'right icon_only'
                    ),
                    'category.history' => array(
                        'title'         => _('History/Notes'),
                        'label'         => '',
                        'quantity_data' => array(
                            'object' => '_object',
                            'field'  => 'Number History Records'
                        ),


                        'icon'  => 'road',
                        'class' => 'right icon_only'
                    )


                )

            ),


            'part' => array(
                'type'           => 'object',
                'subtabs_parent' => array(
                    'part.sales.overview'      => 'part.sales',
                    'part.sales.history'       => 'part.sales',
                    'part.sales.products'      => 'part.sales',
                    'part.stock.overview'      => 'part.stock',
                    'part.stock.transactions'  => 'part.stock',
                    'part.stock.history'       => 'part.stock',
                    'part.stock.availability'  => 'part.stock',
                    'part.supplier.orders'     => 'part.purchase_orders',
                    'part.supplier.deliveries' => 'part.purchase_orders',
                    'part.stock.history'       => 'part.stock',
                    'part.stock.transactions'  => 'part.stock',
                    'part.stock.history.plot'  => 'part.stock',
                    'part.sales.plot'          => 'part.sales',
                    'part.sales.history'       => 'part.sales',
                    'part.sales.calendar'      => 'part.sales',
                    'part.sales.info'          => 'part.sales',

                ),


                'tabs' => array(


                    'part.details' => array(
                        'label' => _('Data'),
                        'icon'  => 'database'
                    ),


                    'part.sales' => array(
                        'label'   => _('Sales'),
                        'icon'    => '',
                        'subtabs' => array(
                            'part.sales.plot'     => array(
                                'label' => _(
                                    'Plot'
                                )
                            ),
                            'part.sales.history'  => array(
                                'label' => _(
                                    'Sales history'
                                )
                            ),
                            'part.sales.calendar' => array(
                                'label' => _(
                                    'Calendar'
                                )
                            ),
                            'part.sales.info'     => array(
                                'label' => _('Info'),
                                'icon'  => 'info',
                                'class' => 'right icon_only'
                            ),

                        )

                    ),

                    'part.stock' => array(
                        'label'   => _(
                            'Stock History'
                        ),
                        'icon'    => 'area-chart',
                        'subtabs' => array(
                            'part.stock.history'      => array(
                                'label' => _(
                                    'Stock history'
                                ),
                                'icon'  => 'bars'
                            ),
                            'part.stock.history.plot' => array(
                                'label' => _(
                                    'Stock history chart'
                                ),
                                'icon'  => 'area-chart'
                            ),
                            'part.stock.transactions' => array(
                                'label' => _(
                                    'Stock movements'
                                ),
                                'icon'  => 'exchange',
                            ),
                        )


                    ),


                    'part.supplier.orders' => array(
                        'label'   => _(
                            'Purchase orders'
                        ),
                        'icon'    => 'clipboard',
                        'subtabs' => array(

                            'part.supplier.orders'     => array(
                                'label' => _(
                                    'Purchase orders'
                                ),
                                'icon'  => 'clipboard'
                            ),
                            'part.supplier.deliveries' => array(
                                'label' => _("Supplier's deliveries"),
                                'icon'  => 'truck'
                            ),

                        )

                    ),


                    'part.supplier_parts' => array(
                        'label'         => _(
                            "Supplier's parts"
                        ),
                        'quantity_data' => array(
                            'object' => '_object',
                            'field'  => 'Number Supplier Parts'
                        ),
                        'icon'          => 'stop'
                    ),

                    'part.products'    => array(
                        'label'         => _('Products'),
                        'quantity_data' => array(
                            'object' => '_object',
                            'field'  => 'Products Numbers'
                        ),
                        'icon'          => 'cube'
                    ),
                    'part.history'     => array(


                        'title'         => _('History/Notes'),
                        'label'         => '',
                        'quantity_data' => array(
                            'object' => '_object',
                            'field'  => 'Number History Records'
                        ),

                        'icon'  => 'road',
                        'class' => 'right icon_only'
                    ),
                    'part.images'      => array(
                        'title'         => _('Images'),
                        'label'         => '',
                        'quantity_data' => array(
                            'object' => '_object',
                            'field'  => 'Number Images'
                        ),
                        'icon'          => 'camera-retro',
                        'class'         => 'right icon_only'
                    ),
                    'part.attachments' => array(
                        'title'         => _('Attachments'),
                        'label'         => '',
                        'quantity_data' => array(
                            'object' => '_object',
                            'field'  => 'Number Attachments'
                        ),
                        'icon'          => 'paperclip',
                        'class'         => 'right icon_only'
                    ),


                )
            ),

            'part.new'   => array(
                'type' => 'new_object',
                'tabs' => array(
                    'part.new' => array(
                        'label' => _(
                            'new part'
                        )
                    ),

                )

            ),
            'part.image' => array(
                'type' => 'object',


                'tabs' => array(


                    'part.image.details' => array(
                        'label' => _(
                            'Data'
                        ),
                        'icon'  => 'database'
                    ),
                    'part.image.history' => array(
                        'label' => _(
                            'History/Notes'
                        ),
                        'icon'  => 'road'
                    ),

                )
            ),

            'part.attachment.new' => array(
                'type' => 'new_object',
                'tabs' => array(
                    'part.attachment.new' => array(
                        'label' => _(
                            'new attachment'
                        )
                    ),

                )

            ),
            'part.attachment'     => array(
                'type' => 'object',
                'tabs' => array(
                    'part.attachment.details' => array(
                        'label' => _(
                            'Data'
                        ),
                        'icon'  => 'database'
                    ),
                    'part.attachment.history' => array(
                        'label' => _(
                            'History'
                        ),
                        'icon'  => 'clock-o'
                    ),

                )

            ),
            /*
			'transactions'=>array(
				'type'=>'navigation', 'label'=>_('Stock Movements'), 'icon'=>'exchange', 'reference'=>'inventory/transactions',
				'tabs'=>array(
					'inventory.stock.transactions'=>array('label'=>_('Stock movements'))

				)
			),
			*/

            'upload' => array(
                'type' => 'object',
                'tabs' => array(
                    'upload.records' => array(
                        'label' => _(
                            'Records'
                        )
                    ),


                )

            ),

            'supplier_part.new' => array(
                'type' => 'new_object',
                'tabs' => array(
                    'part.supplier_part.new' => array(
                        'label' => _(
                            'New supplier part'
                        )
                    ),

                )

            ),
            'stock_history'     => array(
                'type'      => 'navigation',
                'label'     => _('Stock History'),
                'icon'      => 'area-chart',
                'reference' => 'inventory/stock_history',
                'tabs'      => array(
                    'inventory.stock.history' => array(
                        'label' => _(
                            'Stock history'
                        )
                    ),

                    'inventory.stock.history.plot' => array(
                        'label' => _(
                            'Chart'
                        ),
                        'class' => 'right'
                    ),


                )
            ),
            'stock_history.day' => array(
                'type' => '',
                'tabs' => array(
                    'inventory.stock.history.day' => array('label' => ''),


                )
            ),


            'product' => $_product,

            'product.new' => array(
                'type' => 'new_object',
                'tabs' => array(
                    'product.new' => array(
                        'label' => _(
                            'New product'
                        )
                    ),

                )

            ),


        )
    ),
    'warehouses'      => array(
        'sections' => array(
            'dashboard' => array(
                'type'      => 'navigation',
                'label'     => _('Dashboard'),
                'icon'      => 'tachometer',
                'reference' => 'warehouse/%d/dashboard',
                'tabs'      => array(
                    'warehouse.dashboard' => array('label' => _('Dashboard'))

                )
            ),


            'warehouse' => array(

                'type'      => 'navigation',
                'label'     => _('Warehouse'),
                'title'     => _('Warehouse'),
                'icon'      => 'map',
                'reference' => 'warehouse/%d',

                'tabs' => array(
                    'warehouse.details' => array(
                        'label' => _('Data'),
                        'title' => _(
                            'Warehouse details'
                        ),
                        'icon'  => 'database'
                    ),
                    /*
                    'warehouse.replenishments' => array(
                        'label' => _(
                            "Replenishment"
                        )
                    ),
                    */
                    'warehouse.parts'   => array(
                        'label' => _(
                            'Part-Locations'
                        )
                    ),
                    'warehouse.history' => array(
                        'label' => _(
                            'History/Notes'
                        ),
                        'title' => _(
                            'History/Notes'
                        ),
                        'icon'  => 'road',
                        'class' => 'right icon_only'
                    ),

                )

            ),
            /* to add
			'categories'=>array('type'=>'navigation', 'label'=>_("Locations's categories"), 'icon'=>'sitemap', 'reference'=>'warehouse/%d/categories',

				'tabs'=>array(
					'locations.categories'=>array('label'=>_("Locations's categories")),
				)
			),
			*/
            'locations' => array(

                'type'      => 'navigation',
                'label'     => _('Locations'),
                'icon'      => 'map-marker',
                'reference' => 'warehouse/%d/locations',
                'tabs'      => array(
                    'warehouse.locations' => array(
                        'label' => _('Locations'),
                    ),


                )

            ),

            'delivery_notes' => array(

                'type'      => 'navigation',
                'label'     => _('Pending delivery notes'),
                'icon'      => 'truck fa-flip-horizontal',
                'reference' => 'warehouse/%d/delivery_notes',
                'tabs'      => array(
                    'warehouse.delivery_notes' => array(
                        'label' => _('Delivery notes'),
                    ),


                )

            ),

            'warehouse.new' => array(
                'type' => 'new_object',
                'tabs' => array(
                    'warehouse.new' => array(
                        'label' => _(
                            'new warehouse'
                        )
                    ),

                )

            ),
            'location'      => array(

                'type'      => 'object',
                'label'     => _('Location'),
                'icon'      => 'map-sings',
                'reference' => '',
                'tabs'      => array(
                    'location.details'            => array(
                        'label' => _(
                            'Data'
                        ),
                        'title' => _(
                            'Location detais'
                        ),
                        'icon'  => 'database'
                    ),
                    'location.parts'              => array(
                        'label' => _(
                            'Parts'
                        ),
                        'icon'  => 'square'
                    ),
                    'location.stock.transactions' => array(
                        'label' => _(
                            'Stock movements'
                        ),
                        'icon'  => 'exchange'
                    ),
                    'location.history'            => array(
                        'label' => _(
                            'History/Notes'
                        ),
                        'title' => _(
                            'History/Notes'
                        ),
                        'icon'  => 'road',
                        'class' => 'right icon_only'
                    ),

                )

            ),

            'location.new' => array(
                'type' => 'new_object',
                'tabs' => array(
                    'location.new' => array(
                        'label' => _(
                            'New location'
                        )
                    ),

                )

            ),

            'deleted_location' => array(
                'type' => 'object',
                'tabs' => array(
                    'supplier.history' => array(
                        'label' => _('History'),
                        'icon'  => 'road'
                    ),


                )
            ),

            'main_category.new' => array(
                'type' => 'new_object',
                'tabs' => array(
                    'main_category.new' => array(
                        'label' => _(
                            'New category'
                        )
                    ),

                )

            ),
            'category'          => array(
                'type' => 'object',

                'tabs' => array(

                    'category.details'   => array(
                        'label' => _(
                            'Data'
                        ),
                        'icon'  => 'database'
                    ),
                    'category.parts'     => array(
                        'label' => _(
                            'Parts'
                        ),
                        'icon'  => 'square'
                    ),
                    'category.locations' => array(
                        'label' => _(
                            'Locations'
                        ),
                        'icon'  => 'map-sings'
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
    ),
    'reports'         => array(

        'sections' => array(
            'reports' => array(
                'type'      => 'navigation',
                'label'     => _('Activity/Performance'),
                'title'     => _("Activity/Performance"),
                'icon'      => 'thumbs-o-up',
                'reference' => 'users',
                'tabs'      => array(
                    'reports' => array(),

                )

            ),

            'performance' => array(
                'type'      => 'navigation',
                'label'     => _('Activity/Performance'),
                'title'     => _("Activity/Performance"),
                'icon'      => 'thumbs-o-up',
                'reference' => 'users',
                'tabs'      => array(
                    'report.pp'            => array(
                        'label'     => _(
                            'Pickers & Packers'
                        ),
                        'title'     => _(
                            'Pickers & Packers Report'
                        ),
                        'reference' => 'users'
                    ),
                    'report.outofstock'    => array(
                        'label'     => _(
                            "Out of Stock"
                        ),
                        'title'     => _(
                            "Out of Stock"
                        ),
                        'reference' => 'users/'
                    ),
                    'report.top_customers' => array(
                        'label'     => _(
                            'Top Customers'
                        ),
                        'title'     => _(
                            'Top Customers'
                        ),
                        'reference' => 'locations/%d/parts'
                    ),

                )

            ),
            'sales'       => array(
                'type'      => 'navigation',
                'label'     => _('Sales'),
                'title'     => _("Sales"),
                'icon'      => 'money',
                'reference' => 'users',
                'tabs'      => array(
                    'report.sales'      => array(
                        'label'     => _(
                            'Pickers & Packers'
                        ),
                        'title'     => _(
                            'Pickers & Packers Report'
                        ),
                        'reference' => 'users'
                    ),
                    'report.geosales'   => array(
                        'label'     => _(
                            "Geographic Sales"
                        ),
                        'title'     => _(
                            "Geographic Sales Report"
                        ),
                        'reference' => 'users/'
                    ),
                    'report.components' => array(
                        'label'     => _(
                            'Sales Components'
                        ),
                        'title'     => _(
                            'Top Customers'
                        ),
                        'reference' => 'locations/%d/parts'
                    ),

                )

            ),

            'ec_sales_list'                      => array(
                'type' => '',
                'tabs' => array(
                    'ec_sales_list' => array(),

                )

            ),
            'billingregion_taxcategory'          => array(
                'type' => '',
                'tabs' => array(
                    'billingregion_taxcategory' => array(),

                )

            ),
            'billingregion_taxcategory.refunds'  => array(
                'type' => '',
                'tabs' => array(
                    'billingregion_taxcategory.refunds' => array(),

                )

            ),
            'billingregion_taxcategory.invoices' => array(
                'type' => '',
                'tabs' => array(
                    'billingregion_taxcategory.invoices' => array(),

                )

            ),
            'tax'                                => array(
                'type'      => 'navigation',
                'label'     => _(
                    'Tax Reports'
                ),
                'title'     => _(
                    "Tax Reports"
                ),
                'icon'      => 'legal',
                'reference' => 'users',
                'tabs'      => array(
                    'report.notax'     => array(
                        'label'     => _(
                            'No Tax'
                        ),
                        'title'     => _(
                            'No Tax Report'
                        ),
                        'reference' => 'users'
                    ),
                    'report.intrastat' => array(
                        'label'     => _(
                            "Intrastat"
                        ),
                        'title'     => _(
                            "Intrastat"
                        ),
                        'reference' => 'users/'
                    ),

                )

            )


        )
    ),
    'hr'              => array(

        'sections' => array(
            'employees' => array(
                'type'      => 'navigation',
                'label'     => _('Employees'),
                'title'     => _("Employees"),
                'icon'      => 'hand-rock-o',
                'reference' => 'hr',


                'subtabs_parent' => array(
                    'employees.uploads' => 'employees.history_uploads',
                    'employees.history' => 'employees.history_uploads',


                ),

                'tabs' => array(
                    'employees'         => array(
                        'label' => _(
                            'Employees'
                        )
                    ),
                    'deleted.employees' => array(
                        'label' => _(
                            'Deleted employees'
                        ),
                        'icon'  => 'trash-o',
                        'class' => 'right icon_only'
                    ),
                    'exemployees'       => array(
                        'label' => _(
                            'Ex employees'
                        ),
                        'title' => _(
                            'Ex Employees'
                        ),
                        'class' => ''
                    ),


                )

            ),

            'contractors'      => array(
                'type'      => 'navigation',
                'label'     => _('Contractors'),
                'icon'      => 'hand-spock-o',
                'reference' => 'hr/contractors',
                'tabs'      => array(
                    'contractors'         => array('label' => _('Contractors')),
                    'deleted.contractors' => array(
                        'label' => _(
                            'Deleted contractors'
                        ),
                        'icon'  => 'trash-o',
                        'class' => 'right icon_only'
                    ),

                )


            ),
            'overtimes'        => array(
                'type'      => 'navigation',
                'label'     => _('Overtimes'),
                'icon'      => 'clock-o',
                'reference' => 'hr/overtimes',
                'tabs'      => array(
                    'overtimes' => array('label' => _('Overtimes')),

                )


            ),
            'organization'     => array(
                'type'      => 'navigation',
                'label'     => _('Organization'),
                'title'     => _('Organization'),
                'icon'      => 'sitemap',
                'reference' => 'hr/organization',
                'tabs'      => array(
                    'organization.areas'       => array(
                        'label' => _(
                            'Working Areas'
                        ),
                        'class' => 'hide'
                    ),
                    'organization.departments' => array(
                        'label' => _(
                            'Company departments'
                        ),
                        'class' => 'hide'
                    ),
                    'organization.positions'   => array(
                        'label' => _(
                            'Job positions'
                        )
                    ),
                    'organization.organigram'  => array(
                        'label' => _(
                            'Organizational chart'
                        ),
                        'class' => 'hide'
                    ),


                )
            ),
            'employee'         => array(
                'type' => 'object',


                'tabs' => array(
                    'employee.details'                 => array(
                        'label' => _(
                            'Data'
                        ),
                        'icon'  => 'database'
                    ),
                    'employee.today_timesheet.records' => array(
                        'label' => _(
                            'Today timesheet'
                        )
                    ),
                    'employee.timesheets'              => array(
                        'label' => _(
                            'Timesheets'
                        )
                    ),
                    'employee.history'                 => array(
                        'label' => _(
                            'History, notes'
                        ),
                        'icon'  => 'road',
                        'class' => 'right icon_only'
                    ),
                    'employee.images'                  => array(
                        'label' => _(
                            'Images'
                        ),
                        'icon'  => 'camera-retro',
                        'class' => 'right icon_only'
                    ),
                    'employee.attachments'             => array(
                        'label' => _(
                            'Attachments'
                        ),
                        'icon'  => 'paperclip',
                        'class' => 'right icon_only'
                    ),


                )

            ),
            'deleted.employee' => array(
                'type' => 'object',


                'tabs' => array(
                    'deleted.employee.history' => array(
                        'label' => _(
                            'History'
                        ),
                        'icon'  => 'sticky-note-o'
                    ),


                )

            ),

            'employee.new' => array(
                'type' => 'new_object',
                'tabs' => array(
                    'employee.new' => array(
                        'label' => _(
                            'new employee'
                        )
                    ),

                )

            ),

            'employee.attachment.new' => array(
                'type' => 'new_object',
                'tabs' => array(
                    'employee.attachment.new' => array(
                        'label' => _(
                            'new attachment'
                        )
                    ),

                )

            ),
            'employee.user.new'       => array(
                'type' => 'new_object',
                'tabs' => array(
                    'employee.user.new' => array(
                        'label' => _(
                            'new system user'
                        )
                    ),

                )

            ),
            'contractor.user.new'     => array(
                'type' => 'new_object',
                'tabs' => array(
                    'contractor.user.new' => array(
                        'label' => _(
                            'new system user'
                        )
                    ),

                )

            ),

            'employee.attachment' => array(
                'type' => 'object',
                'tabs' => array(
                    'employee.attachment.details' => array(
                        'label' => _(
                            'Data'
                        ),
                        'icon'  => 'database'
                    ),
                    'employee.attachment.history' => array(
                        'label' => _(
                            'History'
                        ),
                        'icon'  => 'clock-o'
                    ),

                )

            ),

            'contractor'         => array(
                'type' => 'object',
                'tabs' => array(
                    'contractor.details' => array(
                        'label' => _(
                            'Data'
                        ),
                        'icon'  => 'database'
                    ),
                    'contractor.history' => array(
                        'label' => _(
                            'History, notes'
                        ),
                        'icon'  => 'sticky-note-o'
                    )

                )

            ),
            'deleted.contractor' => array(
                'type' => 'object',
                'tabs' => array(
                    'deleted.contractor.history' => array(
                        'label' => _(
                            'History'
                        ),
                        'icon'  => 'sticky-note-o'
                    ),


                )

            ),
            'contractor.new'     => array(
                'type' => 'new_object',
                'tabs' => array(
                    'contractor.new' => array(
                        'label' => _(
                            'new contractor'
                        )
                    ),

                )

            ),

            'timesheet' => array(
                'type' => 'object',
                'tabs' => array(
                    'timesheet.records' => array(
                        'label' => _(
                            'Clockings'
                        )
                    ),

                )

            ),

            'timesheets' => array(
                'type'      => 'navigation',
                'icon'      => 'calendar-o',
                'label'     => _('Calendar'),
                'reference' => 'timesheets/day/'.date('Ymd'),
                'tabs'      => array(
                    'timesheets.months' => array(
                        'label' => _(
                            'Months'
                        )
                    ),

                    'timesheets.weeks'     => array(
                        'label' => _(
                            'Weeks'
                        )
                    ),
                    'timesheets.days'      => array(
                        'label' => _(
                            'Days'
                        )
                    ),
                    'timesheets.employees' => array(
                        'label' => _(
                            "Employes'"
                        )
                    ),

                    'timesheets.timesheets' => array(
                        'label' => _(
                            'Timesheets'
                        )
                    ),

                )

            ),


            'new_timesheet_record' => array(
                'type'      => 'new',
                'label'     => _('New timesheet record'),
                'title'     => _('New timesheet record'),
                'icon'      => 'clock',
                'reference' => 'hr/new_timesheet_record',
                'tabs'      => array(
                    'timesheet_record.new'    => array(
                        'label' => _(
                            'New timesheet record'
                        ),
                        'title' => _(
                            'New timesheet record'
                        )
                    ),
                    'timesheet_record.import' => array(
                        'label' => _('Import'),
                        'title' => _(
                            'Import timesheet record'
                        )
                    ),
                    'timesheet_record.api'    => array(
                        'label' => _('API'),
                        'title' => _('API')
                    ),
                    'timesheet_record.cancel' => array(
                        'class' => 'right',
                        'label' => _('Cancel'),
                        'title' => _('Cancel'),
                        'icon'  => 'sign-out fa-flip-horizontal'
                    ),

                )

            ),


            'position' => array(
                'type' => 'object',


                'tabs' => array(


                    'position.employees' => array(
                        'label' => _(
                            'Employees'
                        )
                    ),


                )

            ),


            'uploads'    => array(
                'type' => '',
                'tabs' => array(
                    'uploads' => array(
                        'label' => _(
                            'Uploads'
                        )
                    ),

                )

            ),
            'upload'     => array(
                'type' => 'object',
                'tabs' => array(
                    'upload.employees' => array(
                        'label' => _(
                            'Upload Records'
                        )
                    ),

                )

            ),
            'hr.history' => array(
                'type'      => 'navigation',
                'label'     => '',
                'icon'      => 'road',
                'reference' => 'hr/history',
                'class'     => 'icon_only',
                'tabs'      => array(
                    'hr.history' => array(
                        'label' => _('History'),
                        'icon'  => 'road',
                        'class' => ''
                    ),
                    'hr.uploads' => array(
                        'label' => _('Uploads'),
                        'icon'  => 'upload',
                        'class' => ''
                    ),


                )


            ),

        )
    ),
    'profile'         => array(


        'sections' => array(
            'profile' => array(
                'type'      => 'object',
                'label'     => '',
                'title'     => '',
                'icon'      => '',
                'reference' => '',
                'tabs'      => array(
                    'profile.details'       => array(
                        'label' => _(
                            'Data'
                        ),
                        'title' => _(
                            'My details'
                        )
                    ),
                    'profile.history'       => array(
                        'label' => _(
                            'History, notes'
                        ),
                        'icon'  => 'sticky-note-o'
                    ),
                    'profile.login_history' => array(
                        'label' => _(
                            'Login history'
                        )
                    ),
                )
            ),

        )

    ),
    'account'         => array(


        'sections' => array(

            'setup'               => array(
                'type' => '',
                'tabs' => array(
                    'account.setup' => array(
                        'label' => _(
                            'Account set up'
                        )
                    ),
                )
            ),
            'setup_error'         => array(
                'type' => '',
                'tabs' => array(
                    'account.setup.error' => array('label' => ''),
                )
            ),
            'setup_root_user'     => array(
                'type' => '',
                'tabs' => array(
                    'account.setup.root_user' => array('label' => ''),
                )
            ),
            'setup_account'       => array(
                'type' => '',
                'tabs' => array(
                    'account.setup.account' => array('label' => ''),
                )
            ),
            'setup_add_employees' => array(
                'type' => '',
                'tabs' => array(
                    'account.setup.add_employees' => array('label' => ''),
                )
            ),
            'setup_add_employee'  => array(
                'type' => '',
                'tabs' => array(
                    'account.setup.add_employee' => array('label' => ''),
                )
            ),
            'setup_add_warehouse' => array(
                'type' => '',
                'tabs' => array(
                    'account.setup.add_warehouse' => array('label' => ''),
                )
            ),
            'setup_add_store'     => array(
                'type' => '',
                'tabs' => array(
                    'account.setup.add_store' => array('label' => ''),
                )
            ),

            'account' => array(
                'type'      => 'navigation',
                'label'     => _('Account'),
                'icon'      => 'star',
                'reference' => 'account',

                'tabs' => array(
                    'account.details' => array(
                        'label' => _(
                            'Data'
                        ),
                        'title' => _(
                            'Account details'
                        )
                    ),
                )
            ),

            'users' => array(
                'type'      => 'navigation',
                'label'     => _('Users'),
                'icon'      => 'terminal',
                'reference' => 'account/users',
                'tabs'      => array(
                    'account.users'         => array(
                        'label' => _(
                            'Users categories'
                        )
                    ),
                    'account.deleted.users' => array(
                        'label' => _(
                            'Deleted users'
                        ),
                        'class' => 'right'
                    ),
                )
            ),

            'orders_index' => array(
                'type'      => '',
                'label'     => _("Order's Index"),
                'icon'      => 'bars',
                'reference' => 'account/orders',
                'tabs'      => array(
                    'orders_index'          => array(
                        'label' => _(
                            "Overview"
                        )
                    ),
                    'orders_server'         => array(
                        'label' => _(
                            "Orders list"
                        )
                    ),
                    'delivery_notes_server' => array(
                        'label' => _(
                            "Delivery notes list"
                        )
                    ),
                    'invoices_server'       => array(
                        'label' => _(
                            "Invoice list"
                        )
                    ),

                )
            ),
            'data_sets'    => array(
                'type'      => 'navigation',
                'label'     => _('Data sets'),
                'icon'      => 'align-left',
                'reference' => 'account/data_sets',
                'tabs'      => array(
                    'data_sets' => array(
                        'label' => _(
                            'Data sets'
                        )
                    ),
                )
            ),
            'isf'          => array(
                'type' => '',
                'tabs' => array(
                    'data_sets.isf' => array(
                        'label' => _(
                            'Order transactions timeseries'
                        )
                    ),
                )
            ),
            'osf'          => array(
                'type' => '',
                'tabs' => array(
                    'data_sets.osf' => array(
                        'label' => _(
                            'Inventory transactions timeseries'
                        )
                    ),
                )
            ),
            'images'       => array(
                'type' => '',
                'tabs' => array(
                    'data_sets.images' => array(
                        'label' => _(
                            'Images'
                        )
                    ),
                )
            ),
            'attachments'  => array(
                'type' => '',
                'tabs' => array(
                    'data_sets.attachments' => array(
                        'label' => _(
                            'Attachments'
                        )
                    ),
                )
            ),
            'uploads'      => array(
                'type' => '',
                'tabs' => array(
                    'data_sets.uploads' => array(
                        'label' => _(
                            'Uploads'
                        )
                    ),
                )
            ),
            'materials'    => array(
                'type' => '',
                'tabs' => array(
                    'data_sets.materials' => array(
                        'label' => _(
                            'Materials'
                        )
                    ),
                )
            ),
            'timeseries'   => array(
                'type' => '',
                'tabs' => array(
                    'timeseries' => array(
                        'label' => _(
                            'Timeseries'
                        )
                    ),
                )
            ),

            'upload'   => array(
                'type' => 'object',
                'tabs' => array(
                    'upload.records' => array(
                        'label' => _(
                            'Records'
                        )
                    ),


                )

            ),
            'material' => array(
                'type' => 'object',
                'tabs' => array(
                    'material.details' => array(
                        'label' => _(
                            'Data'
                        ),
                        'icon'  => 'database'
                    ),
                    'material.parts'   => array(
                        'label' => _(
                            'Parts'
                        )
                    ),
                    'material.history' => array(
                        'label' => _(
                            'History'
                        ),
                        'icon'  => 'road',
                        'class' => 'right icon_only'
                    ),


                )

            ),

            'timeserie' => array(
                'type' => '',


                'tabs' => array(
                    'timeserie.plot'    => array(
                        'label' => _(
                            'Plot'
                        )
                    ),
                    'timeserie.records' => array(
                        'label' => _(
                            'Records'
                        ),


                    ),

                )
            ),
            'settings'  => array(
                'type'      => 'navigation',
                'label'     => _('Settings'),
                'icon'      => 'cog',
                'reference' => 'account/settings',
                'tabs'      => array(
                    'account.settings' => array(
                        'label' => _(
                            'Settings'
                        )
                    ),
                )
            ),


            'staff' => array(
                'type'      => 'object',
                'label'     => _('Staff'),
                'title'     => _("Staff users"),
                'icon'      => 'hand-rock-o',
                'reference' => 'users',
                'tabs'      => array(
                    'account.users.staff'       => array(
                        'label' => _(
                            'Users'
                        )
                    ),
                    'users.staff.groups'        => array(
                        'label' => _(
                            "Groups"
                        )
                    ),
                    'users.staff.login_history' => array(
                        'label' => _(
                            'Login History'
                        )
                    ),

                )

            ),

            'suppliers' => array(
                'type'      => 'object',
                'label'     => _('Suppliers'),
                'title'     => _('Suppliers users'),
                'icon'      => 'ship',
                'reference' => 'users/suppliers',
            ),
            'warehouse' => array(
                'type'      => 'object',
                'label'     => _('Warehouse'),
                'title'     => _('Warehouse users'),
                'icon'      => 'th-large',
                'reference' => 'users/warehouse',
            ),
            'root'      => array(
                'type'      => 'object',
                'label'     => 'Root',
                'title'     => _('Root user'),
                'icon'      => 'dot-circle-o',
                'reference' => 'suppliers',
            ),
            'user'      => array(
                'type' => 'object',
                'tabs' => array(
                    'user.details'       => array(
                        'label' => _(
                            'Data'
                        ),
                        'icon'  => 'database',
                        'title' => _(
                            'Details'
                        )
                    ),
                    'user.login_history' => array(
                        'label' => _(
                            'Login history'
                        ),
                        'title' => _(
                            'Login history'
                        )
                    ),
                    'user.api_keys'      => array(
                        'label' => _(
                            'API keys'
                        ),
                        'title' => _(
                            'API keys'
                        )
                    ),

                    'user.history' => array(
                        'label' => _(
                            'History/Notes'
                        ),
                        'icon'  => 'road',
                        'class' => 'right icon_only'
                    ),


                )
            ),


            'deleted.user' => array(
                'type' => 'object',
                'tabs' => array(

                    'deleted.user.login_history' => array(
                        'label' => _(
                            'Login history'
                        ),
                        'title' => _(
                            'Login history'
                        )
                    ),
                    'deleted.user.history'       => array(
                        'label' => _(
                            'History/Notes'
                        ),
                        'icon'  => 'road',
                        'class' => 'right icon_only'
                    )

                )

            ),


            'user.api_key.new' => array(
                'type' => 'new_object',
                'tabs' => array(
                    'user.api_key.new' => array(
                        'label' => _(
                            'New API'
                        )
                    ),

                )
            ),
            'user.api_key'     => array(
                'type' => 'new_object',
                'tabs' => array(
                    'user.api_key.details'  => array(
                        'label' => _(
                            'Data'
                        ),
                        'icon'  => 'database'
                    ),
                    'user.api_key.requests' => array(
                        'label' => _(
                            'Requests'
                        ),
                        'icon'  => 'arrow-circle-right'
                    ),

                )
            ),

            'agents'      => array(
                'type'  => '',
                'label' => _('Agents'),
                'icon'  => 'terminal',
                'tabs'  => array(
                    'account.users.agents' => array(
                        'label' => _(
                            'Agents'
                        )
                    ),
                )
            ),
            'suppliers'   => array(
                'type'  => '',
                'label' => _('Suppliers'),
                'icon'  => 'terminal',
                'tabs'  => array(
                    'account.users.suppliers' => array(
                        'label' => _(
                            'Suppliers'
                        )
                    ),
                )
            ),
            'contractors' => array(
                'type'  => '',
                'label' => _('Contractors'),
                'icon'  => 'terminal',
                'tabs'  => array(
                    'account.users.contractors' => array(
                        'label' => _(
                            'Contractors'
                        )
                    ),
                )
            ),


        )

    ),
    'utils'           => array(
        'sections' => array(
            'forbidden' => array(
                'type'  => 'object',
                'label' => _('Forbidden'),
                'title' => _('Forbidden'),
                'id'    => 'forbidden',
                'tabs'  => array(
                    'forbidden' => array()
                )
            ),
            'not_found' => array(
                'type'  => 'object',
                'label' => _('Not found'),
                'title' => _('Not found'),
                'id'    => 'not_found',

                'tabs' => array(
                    'not_found' => array(),
                )
            ),


            'fire' => array(
                'type'  => 'object',
                'label' => _('Fire'),
                'icon'  => 'file-o',
                'id'    => 'not_found',

                'tabs' => array(
                    'fire' => array(),
                )
            ),

        )
    ),
    'help'            => array(
        'sections' => array(
            'help' => array(
                'type'  => 'object',
                'label' => _('Help'),
                'icon'  => 'shopping-cart',
                'id'    => 'forbidden',
                'tabs'  => array(
                    'help' => array()
                )
            )
        ),
        'about'    => array(
            'about' => array(
                'type'  => 'object',
                'label' => _('About'),
                'icon'  => '',
                'tabs'  => array(
                    'about' => array()
                )
            )
        )
    ),
    'agent_profile'   => array(
        'sections' => array(
            'profile' => array(
                'type'  => 'object',
                'label' => _('Profile'),
                'icon'  => 'user_secret',
                'id'    => '',
                'tabs'  => array(
                    'agent.details'   => array(
                        'label' => _(
                            'Data'
                        ),
                        'icon'  => 'database',
                        'title' => _(
                            'Details'
                        )
                    ),
                    'agent.suppliers' => array(
                        'label' => _(
                            "Agent's suppliers"
                        ),
                        'icon'  => 'ship'
                    ),

                    'agent.supplier_parts' => array(
                        'label' => _(
                            "Agent's Parts"
                        ),
                        'icon'  => 'stop'
                    ),
                    'agent.orders'         => array(
                        'label' => _(
                            'Purchase orders'
                        ),
                        'icon'  => 'clipboard'
                    ),
                    'agent.deliveries'     => array(
                        'label' => _(
                            'Deliveries'
                        ),
                        'icon'  => 'truck'
                    ),
                    'agent.agent_orders'   => array(
                        'label' => _(
                            "Agent's PO"
                        ),
                        'icon'  => 'clipboard fa-flip-horizontal'
                    ),

                    'agent.history' => array(
                        'label' => _(
                            'History/Notes'
                        ),
                        'icon'  => 'road',
                        'class' => 'right icon_only'
                    ),
                    'agent.users'   => array(
                        'label' => _(
                            'System users'
                        ),
                        'icon'  => 'terminal',
                        'class' => 'right icon_only'
                    ),
                )
            )
        ),
    ),
    'agent_suppliers' => array(


        'sections' => array(

            'suppliers' => array(

                'type'      => 'navigation',
                'label'     => _('Suppliers'),
                'icon'      => 'ship',
                'reference' => 'suppliers',
                'tabs'      => array(
                    'agent.suppliers' => array()
                )


            ),


            'order_to_delete' => array(
                'type' => 'object',
                'tabs' => array(

                    'supplier.order.details' => array(
                        'label' => _(
                            'Data'
                        ),
                        'icon'  => 'database'
                    ),

                    'supplier.order.items'      => array(
                        'label' => _(
                            'Items'
                        ),
                        'icon'  => 'bars'
                    ),
                    // 'supplier.order.supplier_parts'=>array('label'=>_('Parts'), 'icon'=>'stop'),
                    // 'supplier.order.invoices'=>array('label'=>_('Invoices'),  'icon'=>'file-text-o'),
                    'supplier.order.tac.editor' => array(
                        'label' => _(
                            'Terms and conditions'
                        ),
                        'icon'  => 'gavel',
                        'class' => ''
                    ),
                    'supplier.order.history'    => array(
                        'label' => _(
                            'History/Notes'
                        ),
                        'icon'  => 'road',
                        'class' => 'right icon_only'
                    ),

                )

            ),

            'deleted_order_to_delete' => array(
                'type' => 'object',
                'tabs' => array(


                    'deleted.supplier.order.items'   => array(
                        'label' => _(
                            'Items'
                        ),
                        'icon'  => 'bars'
                    ),
                    'deleted.supplier.order.history' => array(
                        'label' => _(
                            'History/Notes'
                        ),
                        'icon'  => 'road',
                        'class' => 'right icon_only'
                    ),

                )

            ),

            'deliveryto_delete' => array(
                'type' => 'object',
                'tabs' => array(

                    'supplier.delivery.details' => array(
                        'label' => _(
                            'Data'
                        ),
                        'icon'  => 'database'
                    ),

                    'supplier.delivery.items'   => array(
                        'label' => _(
                            'Items'
                        ),
                        'icon'  => 'bars'
                    ),
                    'supplier.delivery.history' => array(
                        'label' => _(
                            'History/Notes'
                        ),
                        'icon'  => 'road',
                        'class' => 'right icon_only'
                    ),

                )

            ),

            'supplier'                => array(
                'type'      => 'object',
                'label'     => _('Supplier'),
                'icon'      => 'ship',
                'reference' => 'supplier/%d',
                'tabs'      => array(
                    'supplier.details'        => array(
                        'label' => _('Data'),
                        'icon'  => 'database',
                        'title' => _('Details')
                    ),
                    'supplier.supplier_parts' => array(
                        'label' => _(
                            "Supplier's Parts"
                        ),
                        'icon'  => 'stop'
                    ),
                    'supplier.orders'         => array(
                        'label' => _(
                            'Purchase orders'
                        ),
                        'icon'  => 'clipboard'
                    ),

                    'supplier.history' => array(
                        'label' => _('History/Notes'),
                        'icon'  => 'road',
                        'class' => 'right icon_only'
                    ),

                    'supplier.attachments' => array(
                        'label' => _('Attachments'),
                        'icon'  => 'paperclip',
                        'class' => 'right icon_only'
                    ),

                )
            ),
            'supplier.new'            => array(
                'type' => 'new_object',
                'tabs' => array(
                    'supplier.new' => array(
                        'label' => _(
                            'New supplier'
                        )
                    ),

                )

            ),
            'supplier.attachment.new' => array(
                'type' => 'new_object',
                'tabs' => array(
                    'supplier.attachment.new' => array(
                        'label' => _(
                            'new attachment'
                        )
                    ),

                )

            ),
            'supplier.attachment'     => array(
                'type' => 'object',
                'tabs' => array(
                    'supplier.attachment.details' => array(
                        'label' => _(
                            'Data'
                        ),
                        'icon'  => 'database'
                    ),
                    'supplier.attachment.history' => array(
                        'label' => _(
                            'History'
                        ),
                        'icon'  => 'clock-o'
                    ),

                )

            ),
            'deleted_supplier'        => array(
                'type' => 'object',
                'tabs' => array(
                    'supplier.history' => array(
                        'label' => _('History'),
                        'icon'  => 'road'
                    ),


                )
            ),

            'supplier.order.item' => array(
                'type'           => 'object',
                'subtabs_parent' => array(

                    'supplier_part.purchase_orders.purchase_orders' => 'supplier_part.purchase_orders',
                    'supplier_part.purchase_orders.delivery_notes'  => 'supplier_part.purchase_orders',
                    'supplier_part.purchase_orders.invoices'        => 'supplier_part.purchase_orders',
                ),


                'tabs' => array(


                    'supplier.order.item.details' => array(
                        'label' => _(
                            'Data'
                        ),
                        'icon'  => 'database'
                    ),


                    'supplier_part.purchase_orders' => array(
                        'label'   => _(
                            'Purchase Orders'
                        ),
                        'icon'    => 'clipboard',
                        'class'   => 'right icon_only',
                        'subtabs' => array(
                            'supplier_part.purchase_orders.purchase_orders' => array(
                                'label' => _(
                                    'Purchase Orders'
                                )
                            ),
                            'supplier_part.purchase_orders.delivery_notes'  => array(
                                'label' => _(
                                    'Delivery Notes'
                                )
                            ),
                            'supplier_part.purchase_orders.invoices'        => array(
                                'label' => _(
                                    'Invoices'
                                )
                            ),

                        )

                    ),

                    'supplier_part.images'  => array(
                        'label' => _(
                            'Images'
                        ),
                        'icon'  => 'camera-retro',
                        'class' => 'right icon_only'
                    ),
                    'supplier_part.history' => array(
                        'label' => _(
                            'History/Notes'
                        ),
                        'icon'  => 'road',
                        'class' => 'right icon_only'
                    ),


                )
            ),


            'supplier_part' => array(
                'type'           => 'object',
                'subtabs_parent' => array(

                    'supplier_part.supplier.orders'     => 'supplier_part.purchase_orders',
                    'supplier_part.supplier.deliveries' => 'supplier_part.purchase_orders',
                ),


                'tabs' => array(


                    'supplier_part.details' => array(
                        'label' => _(
                            'Data'
                        ),
                        'icon'  => 'database'
                    ),


                    'supplier_part.purchase_orders' => array(
                        'label'   => _(
                            'Purchase orders / deliveries'
                        ),
                        'icon'    => 'clipboard',
                        'subtabs' => array(

                            'supplier_part.supplier.orders'     => array(
                                'label' => _(
                                    'Purchase orders'
                                ),
                                'icon'  => 'clipboard'
                            ),
                            'supplier_part.supplier.deliveries' => array(
                                'label' => _(
                                    "Supplier's deliveries"
                                ),
                                'icon'  => 'truck'
                            ),

                        )

                    ),

                    'supplier_part.images'  => array(
                        'label' => _(
                            'Images'
                        ),
                        'icon'  => 'camera-retro',
                        'class' => 'right icon_only'
                    ),
                    'supplier_part.history' => array(
                        'label' => _(
                            'History/Notes'
                        ),
                        'icon'  => 'road',
                        'class' => 'right icon_only'
                    ),


                )
            ),

            'supplier_part.historic' => array(
                'type'           => 'object',
                'subtabs_parent' => array(

                    'supplier_part.purchase_orders.purchase_orders' => 'supplier_part.purchase_orders',
                    'supplier_part.purchase_orders.delivery_notes'  => 'supplier_part.purchase_orders',
                    'supplier_part.purchase_orders.invoices'        => 'supplier_part.purchase_orders',
                ),


                'tabs' => array(


                    'supplier_part.details' => array(
                        'label' => _(
                            'Data'
                        ),
                        'icon'  => 'database'
                    ),


                    'supplier_part.purchase_orders' => array(
                        'label'   => _(
                            'Purchase Orders'
                        ),
                        'icon'    => 'clipboard',
                        'subtabs' => array(
                            'supplier_part.purchase_orders.purchase_orders' => array(
                                'label' => _(
                                    'Purchase Orders'
                                )
                            ),
                            'supplier_part.purchase_orders.delivery_notes'  => array(
                                'label' => _(
                                    'Delivery Notes'
                                )
                            ),
                            'supplier_part.purchase_orders.invoices'        => array(
                                'label' => _(
                                    'Invoices'
                                )
                            ),

                        )

                    ),

                    'supplier_part.images'  => array(
                        'label' => _(
                            'Images'
                        ),
                        'icon'  => 'camera-retro',
                        'class' => 'right icon_only'
                    ),
                    'supplier_part.history' => array(
                        'label' => _(
                            'History/Notes'
                        ),
                        'icon'  => 'road',
                        'class' => 'right icon_only'
                    ),


                )
            ),

            'supplier_part.new' => array(
                'type' => 'new_object',
                'tabs' => array(
                    'supplier_part.new' => array(
                        'label' => _(
                            'New part'
                        )
                    ),

                )

            ),
            'supplier.user.new' => array(
                'type' => 'new_object',
                'tabs' => array(
                    'supplier.user.new' => array(
                        'label' => _(
                            'New system user'
                        )
                    ),

                )

            ),

            /*
			'settings'=>array(
				'type'=>'navigation', 'label'=>'', 'icon'=>'sliders', 'reference'=>'suppliers/settings', 'class'=>'icon_only',
				'tabs'=>array(
					'suppliers.settings'=>array('label'=>_('Setting'), 'icon'=>'sliders', 'class'=>''),


				)


			),
			*/
        )
    ),

    'agent_client_orders' => array(
        'sections' => array(
            'orders' => array(
                'type'      => 'navigation',
                'label'     => _("Client's orders"),
                'icon'      => 'shopping-cart',
                'reference' => 'agent_orders',
                'tabs'      => array(
                    'agent.client_orders' => array()
                )
            ),


            'client_order' => array(
                'type' => 'object',
                'tabs' => array(
                    'client_order.items'   => array(
                        'label' => _(
                            'Items'
                        ),
                        'icon'  => 'bars'
                    ),
                    'client_order.details' => array(
                        'label' => _('Data'),
                        'icon'  => 'database'
                    ),


                    'client_order.history' => array(
                        'label' => _(
                            'History/Notes'
                        ),
                        'icon'  => 'road',
                        'class' => 'right icon_only'
                    ),

                )

            ),

            'deleted_order' => array(
                'type' => 'object',
                'tabs' => array(


                    'deleted.supplier.order.items'   => array(
                        'label' => _(
                            'Items'
                        ),
                        'icon'  => 'bars'
                    ),
                    'deleted.supplier.order.history' => array(
                        'label' => _(
                            'History/Notes'
                        ),
                        'icon'  => 'road',
                        'class' => 'right icon_only'
                    ),

                )

            ),


        )
    ),

    'agent_client_deliveries' => array(
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
                    'client_order.items'         => array(
                        'label' => _('Items'),
                        'icon'  => 'bars'
                    ),
                    'client_order.client_orders' => array(
                        'label' => _("Client's orders"),
                        'icon'  => 'clipboard'
                    ),
                    'client_order.details'       => array(
                        'label' => _('Data'),
                        'icon'  => 'database'
                    ),


                    'client_order.history' => array(
                        'label' => _('History/Notes'),
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
    ),


    'agent_parts' => array(
        'sections' => array(
            'parts' => array(
                'type'      => 'navigation',
                'label'     => _("Parts"),
                'icon'      => 'stop',
                'reference' => 'agent_parts',
                'tabs'      => array(
                    'agent.parts' => array()
                )
            ),


            'agent_part' => array(
                'type' => 'object',


                'tabs' => array(


                    'supplier_part.details' => array(
                        'label' => _(
                            'Data'
                        ),
                        'icon'  => 'database'
                    ),


                    'supplier_part.images'  => array(
                        'label' => _(
                            'Images'
                        ),
                        'icon'  => 'camera-retro',
                        'class' => 'right icon_only'
                    ),
                    'supplier_part.history' => array(
                        'label' => _(
                            'History/Notes'
                        ),
                        'icon'  => 'road',
                        'class' => 'right icon_only'
                    ),


                )
            ),


        )
    ),


);


function get_sections($module, $parent_key = false) {
    global $modules;

    $sections = array();

    foreach ($modules[$module]['sections'] as $key => $value) {

        if ($value['type'] == 'navigation') {
            if ($parent_key) {
                $value['reference'] = sprintf($value['reference'], $parent_key);
            }

            $sections[$key] = $value;
        }
    }


    return $sections;

}


?>
