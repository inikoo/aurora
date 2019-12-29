<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:   27 December 2019  10:32::58  +0800, Kuala Lumpur Malaysia

 Copyright (c) 2019, Inikoo

 Version 3.0
*/

function get_customers_module() {
    return array(
        'section'     => 'customers',
        'parent'      => 'store',
        'parent_type' => 'key',
        'sections'    => array(
            'dashboard' => array(
                'type'      => 'left_button',
                'label'     => _('Dashboard'),
                'title'     => _("Customer's dashboard"),
                'icon'      => 'tachometer',
                'reference' => 'customers/%d/dashboard',
                'tabs'      => array(
                    'customers.dashboard' => array()
                )
            ),



            'upload' => array(
                'type' => 'object',
                'tabs' => array(
                    'upload.records' => array(
                        'label' => _(
                            'Upload Records'
                        )
                    ),
                )
            ),

            'customers' => array(
                'type'      => 'navigation',
                'label'     => _('Customers'),
                'icon'      => 'user',
                'reference' => 'customers/%d',
                'tabs'      => array(
                    'customers' => array()
                )


            ),
            'lists'     => array(
                'type'      => 'navigation',
                'label'     => _('Lists'),
                'icon'      => 'list',
                'reference' => 'customers/%d/lists',
                'tabs'      => array(
                    'customers.lists' => array()
                )
            ),


            'insights' => array(
                'type'      => 'navigation',
                'label'     => _('Insights'),
                'icon'      => 'graduation-cap',
                'reference' => 'customers/%s/insights',
                'tabs'      => array(
                    'customers_poll.queries' => array(
                        'label' => _('Poll')
                    ),
                    'customers.geo'          => array(
                        'label' => _('Geographic Distribution')
                    ),
                    /*
                    'contacts'       => array(
                        'label' => _('Contacts')
                    ),
                    'customers'      => array(
                        'label' => _('Customers')
                    ),
                    'orders'         => array(
                        'label' => _('Orders')
                    ),
                    'data_integrity' => array(
                        'label' => _('Data Integrity')
                    ),

                    'correlations'   => array(
                        'label' => _('Correlations')
                    ),
                    */

                )

            ),





            'list' => array(
                'type' => 'object',
                'tabs' => array(
                    'list.details'            => array(
                        'label' => _('Settings'),
                        'icon'  => 'sliders-h',
                        'title' => _('Settings')
                    ),
                    'customers.list'          => array(
                        'label' => _('Customers'),
                        'icon'  => 'users',

                    ),
                    'customer_list.mailshots' => array(
                        'label' => _('Mailshots'),
                        'icon'  => 'bullhorn',

                    ),
                    'list.history'            => array(
                        'title' => _('History'),
                        'label' => '',
                        'icon'  => 'road',
                        'class' => 'right icon_only'
                    ),
                )


            ),

            'list.new' => array(
                'type' => 'new_object',
                'tabs' => array(
                    'customers_list.new' => array(
                        'label' => _('New customer list')
                    ),

                )

            ),


            'category' => array(
                'type' => 'object',

                'tabs' => array(
                    'category.details'   => array(
                        'label' => _('Data'),
                        'icon'  => 'database'
                    ),
                    'category.history'   => array(
                        'label' => _('History'),
                        'icon'  => 'sticky-note'
                    ),
                    'category.customers' => array('label' => _('Customers')),

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
                    'customer.marketing.families'   => 'customer.insights',
                    'customer.marketing.products'   => 'customer.insights',
                    'customer.marketing.favourites' => 'customer.insights',
                    'customer.marketing.search'     => 'customer.insights',
                    'customer.poll'                 => 'customer.insights',

                    'customer.sales.plot'      => 'customer.sales',
                    'customer.sales.history'   => 'customer.sales',
                    'customer.sales.calendar'  => 'customer.sales',
                    'customer.sales.dashboard' => 'customer.sales',
                    'customer.sales.info'      => 'customer.sales',

                    'customer.orders'   => 'customer.orders_invoices',
                    'customer.invoices' => 'customer.orders_invoices',

                    'customer.active_portfolio'  => 'customer.portfolio',
                    'customer.removed_portfolio' => 'customer.portfolio',


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
                            'customer.sales.calendar'  => array(
                                'label' => _('Calendar')
                            ),
                            'customer.sales.info'      => array(
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


                    'customer.deals'             => array(
                        'label' => _('Discounts'),
                        'icon'  => 'tags'
                    ),
                    'customer.credit_blockchain' => array(
                        'label' => _('Credits blockchain'),
                        'icon'  => 'code-commit'
                    ),

                    'customer.sent_emails' => array(
                        'label' => '',
                        'title' => _('Sent emails'),
                        'icon'  => 'paper-plane',
                        'class' => 'icon_only right'

                    ),

                )
            ),


            'customer.new' => array(
                'type' => 'new_object',
                'tabs' => array(
                    'customer.new' => array(
                        'label' => _('New customer')
                    ),

                )

            ),

            'customer_client.new' => array(
                'type' => 'new_object',
                'tabs' => array(
                    'customer_client.new' => array(
                        'label' => _("New customer's client")
                    ),

                )

            ),


            'customer_client' => array(
                'type'           => 'object',
                'label'          => _("Customer's client"),
                'title'          => _("Customer's client"),
                'icon'           => '',
                'subtabs_parent' => array(
                    'customer_client.orders'         => 'customer_client.orders_deliveries',
                    'customer_client.delivery_notes' => 'customer_client.orders_deliveries',
                ),
                'tabs'           => array(
                    'customer_client.details' => array(
                        'label' => _('Data'),
                        'icon'  => 'database'
                    ),


                    'customer_client.orders_deliveries' => array(
                        'label'   => _('Orders'),
                        'icon'    => 'shopping-cart',
                        'subtabs' => array(
                            'customer_client.orders'         => array(
                                'label' => _('Orders'),
                                'icon'  => 'shopping-cart'
                            ),
                            'customer_client.delivery_notes' => array(
                                'label' => _('Delivery notes'),
                                'icon'  => 'truck'
                            ),
                        )

                    ),

                    'customer_client.history' => array(
                        'title' => _('History, notes'),
                        'label' => '',
                        'icon'  => 'road',
                        'class' => 'icon_only right'
                    ),

                )
            ),



            'poll_query' => array(
                'type'  => 'object',
                'label' => _('Poll query'),
                'title' => _('Poll query'),
                'icon'  => '',

                'tabs' => array(
                    'poll_query.details' => array(
                        'label' => _('Data'),
                        'icon'  => 'database'
                    ),

                    'poll_query.answers' => array('label' => _('Answers')),
                    'poll_query.options' => array('label' => _('Poll options')),
                    'poll_query.history' => array(
                        'title' => _('History, notes'),
                        'label' => '',
                        'icon'  => 'road',
                        'class' => 'icon_only right'
                    ),

                )
            ),

            'poll_query.new' => array(
                'type' => 'new_object',
                'tabs' => array(
                    'poll_query.new' => array(
                        'label' => _('New poll query')
                    ),

                )

            ),


            'poll_query_option' => array(
                'type'  => 'object',
                'label' => _('Poll query option'),
                'title' => _('Poll query option'),
                'icon'  => '',

                'tabs' => array(
                    'poll_query_option.details' => array(
                        'label' => _('Data'),
                        'icon'  => 'database'
                    ),

                    'poll_query_option.customers' => array('label' => _('Customers')),
                    'poll_query_option.history'   => array(
                        'title' => _('History, notes'),
                        'label' => '',
                        'icon'  => 'road',
                        'class' => 'icon_only right'
                    ),

                )
            ),
            'poll_query_option.new' => array(
                'type' => 'new_object',
                'tabs' => array(
                    'poll_query_option.new' => array(
                        'label' => _('New poll query option')
                    ),

                )

            ),
            'deleted_customer_poll_query_option' => array(
                'type' => 'object',
                'tabs' => array(
                    'poll_query_option.history' => array(
                        'label' => _('History'),
                        'icon'  => 'road'
                    ),


                )
            ),
            'deleted_customer' => array(
                'type' => 'object',
                'tabs' => array(
                    'customer.history' => array(
                        'label' => _('History'),
                        'icon'  => 'road'
                    ),


                )
            ),

            'email_tracking'         => array(
                'type' => 'object',
                'tabs' => array(
                    'email_tracking.email' => array(
                        'label' => _('Email'),
                        'icon'  => 'envelope',
                    ),

                    'email_tracking.events' => array(
                        'label' => _('Tracking'),
                        'icon'  => 'stopwatch'
                    ),


                )
            ),
            'prospect.compose_email' => array(
                'type' => 'new_object',
                'tabs' => array(
                    'prospect.compose_email' => array(
                        'label' => 'New invitation'
                    ),

                )

            ),
            'product' => array(
                'type' => 'object',
                'tabs' => array(
                    'customer.product.orders' => array(
                        'label' => _('Orders'),
                        'icon'  => 'shopping-cart',


                    ),

                    'customer.product.invoices' => array(
                        'label' => _('Invoices'),
                        'icon'  => 'file-invoice-dollar',


                    ),
                    /*
                                        'customer.product.transaction'  => array(
                                            'label'         => _('Transactions'),
                                            'icon'          => 'exchange-alt',


                                        ),
                    */
                    'product.history'           => array(
                        'title'         => _('History/Notes'),
                        'label'         => '',
                        'quantity_data' => array(
                            'object' => '_object',
                            'field'  => 'Number History Records'
                        ),
                        'icon'          => 'road',
                        'class'         => 'right icon_only'
                    ),
                    'product.images'            => array(
                        'title'         => _('Images'),
                        'label'         => '',
                        'quantity_data' => array(
                            'object' => '_object',
                            'field'  => 'Number Images'
                        ),
                        'icon'          => 'camera-retro',
                        'class'         => 'right icon_only'
                    ),
                    'product.parts'             => array(
                        'title'         => _('Parts'),
                        'label'         => '',
                        'quantity_data' => array(
                            'object' => '_object',
                            'field'  => 'Number of Parts'
                        ),
                        'icon'          => 'box',
                        'class'         => 'right icon_only'
                    ),

                )

            ),

            'prospects' => array(
                'type'      => 'navigation',
                'label'     => _('Prospects'),
                'title'     => _('Prospects'),
                'icon'      => 'user-alien',
                'reference' => 'prospects/%d',
                'tabs'      => array(

                    'prospects'                 => array(
                        'label' => _('Prospects')
                    ),
                    'prospects.mailshots'       => array(
                        'label' => _('Mailshots')
                    ),
                    'prospects.email_templates' => array(
                        'label' => _('Email templates')
                    ),

                )


            ),
            'prospect' => array(
                'type'      => 'object',
                'label'     => _('Prospect'),
                'title'     => _('Prospect'),
                'icon'      => 'user-plus',
                'reference' => 'prospect/%d',

                'tabs' => array(
                    'prospect.details' => array(
                        'label' => _('Data'),
                        'icon'  => 'database'
                    ),

                    'prospect.history' => array(
                        'label' => _('Communications, history, notes'),
                        'icon'  => 'comment'
                    ),

                    'prospect.sent_emails' => array(
                        'label' => _('Sent emails'),
                        'icon'  => 'paper-plane'
                    ),

                )
            ),
            'prospect.new' => array(
                'type' => 'new_object',
                'tabs' => array(
                    'prospect.new' => array(
                        'label' => _('New prospect')
                    ),

                )

            ),
            'prospects.email_template' => array(
                'type' => 'object',
                'tabs' => array(
                    'prospects.template.details'  => array(
                        'label' => _('Data'),
                        'icon'  => 'database'
                    ),
                    'prospects.template.workshop' => array(
                        'label' => _('Workshop'),
                        'icon'  => 'wrench'
                    ),


                )

            ),
            'prospects.template.new' => array(
                'type' => 'new_object',
                'tabs' => array(
                    'prospects.template.new' => array(
                        'label' => _(
                            'New invitation template'
                        )
                    ),

                )

            ),


        )
    );
}