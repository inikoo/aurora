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
        'product.sales.plot'                                   => 'product.sales',
        'product.sales.history'                                => 'product.sales',
        'product.sales.calendar'                               => 'product.sales',
        'product.sales.info'                                   => 'product.sales',
        'product.customers'                                    => 'product.customers',
        'product.customers.favored'                            => 'product.customers',
        'product.back_to_stock_notification_request.customers' => 'product.customers',


        'product.sales_correlation'     => 'product.correlation',
        'product.sales_anticorrelation' => 'product.correlation'
    ),

    'tabs' => array(
        'product.details' => array(
            'label' => _('Data'),
            'icon'  => 'database',
            'title' => _('Details')
        ),

        'product.webpages' => array(
            'label' => _('Webpages'),
            'icon'  => 'globe',
            'title' => _('Webpages')
        ),

        'product.history' => array(
            'label' => _('History, notes'),
            'icon'  => 'sticky-note'
        ),
        'product.sales'   => array(
            'label'   => _('Sales'),
            'title'   => _('Sales'),
            'icon'    => 'money-bill-alt',
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
                    'label'   => '',
                    'title'   => _('Sales data info'),
                    'icon_v2' => 'fal fa-fw fa-chess-clock',
                    'class'   => 'right icon_only'
                ),

            )
        ),
        'product.orders'  => array(
            'label'         => _('Orders'),
            'icon'          => 'shopping-cart',
            'quantity_data' => array(
                'object' => '_object',
                'field'  => 'Number Orders'
            ),

        ),

        'product.customers' => array(
            'label'         => _('Customers'),
            'icon'          => 'users',
            'quantity_data' => array(
                'object' => '_object',
                'field'  => 'Customers Numbers'
            ),
            'subtabs'       => array(
                'product.customers'                                    => array(
                    'label'         => _('Customers'),
                    'icon'          => 'user',
                    'quantity_data' => array(
                        'object' => '_object',
                        'field'  => 'Number Customers'
                    ),
                ),
                'product.customers.favored'                            => array(
                    'label'         => _('Customers who favored'),
                    'icon'          => 'heart',
                    'quantity_data' => array(
                        'object' => '_object',
                        'field'  => 'Number Customers Favored'
                    ),

                ),
                'product.back_to_stock_notification_request.customers' => array(
                    'label'         => _('Back to stock notification requests'),
                    'icon'          => 'dolly',
                    'quantity_data' => array(
                        'object' => '_object',
                        'field'  => 'Number Customers OOS Notification'
                    ),

                ),

            )
        ),

        'product.mailshots'   => array(
            'label' => _('Mailshots'),
            'icon'  => 'bullhorn'
        ),
        'product.correlation' => array(
            'title'   => _('Sales correlations'),
            'label'   => _('Correlations'),
            'icon'    => 'project-diagram',
            'subtabs' => array(
                'product.sales_correlation'     => array(
                    'label' => _('Sales correlation'),
                    'icon'  => 'user',

                ),
                'product.sales_anticorrelation' => array(
                    'label' => _('Sales anticorrelation'),
                    'icon'  => 'user-slash',


                ),
            )
        ),


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
            'icon'          => 'box',
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
            'icon'  => 'sticky-note'
        ),
        'service.sales'     => array(
            'label'   => _('Sales'),
            'title'   => _('Sales'),
            'icon'    => 'money-bill-alt',
            'subtabs' => array(
                'service.sales.plot'     => array(
                    'label' => _('Plot')
                ),
                'service.sales.history'  => array(
                    'label' => _('Sales history')
                ),
                'service.sales.calendar' => array(
                    'label' => _('Calendar')
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
                    'label' => _('Customers'),
                    'title' => _('Customers')
                ),
                'service.customers.favourites' => array(
                    'label' => _('Customers who favored'),
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
    'dashboard'        => array(

        'section' => 'dashboard',

        'parent'      => 'none',
        'parent_type' => 'none',
        'sections'    => array(
            'dashboard' => array(
                'type'  => 'widgets',
                'label' => _('Home'),
                'title' => _('Dashboard'),
                'icon'  => 'home',
                'tabs'  => array(
                    'dashboard' => array(
                        'label' => _('Dashboard')
                    ),

                )

            ),
        )

    ),
    'customers'        => array(
        'section'     => 'customers',
        'parent'      => 'store',
        'parent_type' => 'key',
        'sections'    => array(
            'dashboard' => array(
                'type'      => 'navigation',
                'label'     => _('Dashboard'),
                'title'     => _("Customer's dashboard"),
                'icon'      => 'tachometer',
                'reference' => 'customers/%d/dashboard',
                'tabs'      => array(
                    'customers.dashboard' => array()
                )
            ),

            'prospects' => array(
                'type'      => 'navigation',
                'label'     => _('Prospects'),
                'title'     => _('Prospects'),
                'icon'      => 'user-friends',
                'reference' => 'prospects/%d',
                'tabs'      => array(
                    /*
                                        'prospects.dashboard' => array(
                                            'label' => _('Dashboard')
                                        ),
                    */
                    'prospects'                 => array(
                        'label' => _('Prospects')
                    ),
                    /*
                                        'prospects.agents'          => array(
                                            'label' => _('Agents')
                                        ),
                    */
                    'prospects.email_templates' => array(
                        'label' => _('Email templates')
                    ),

                )


            ),

            'customers' => array(
                'type'      => 'navigation',
                'label'     => _('Customers'),
                'icon'      => 'users',
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


            'customer_notifications' => array(
                'type'      => 'navigation',
                'label'     => _('Notifications'),
                'icon'      => 'paper-plane',
                'reference' => 'customers/%s/notifications',


                'tabs' => array(

                    /*
                    'email_campaigns.newsletters' => array(
                        'label' => _('Newsletters'),
                        'icon'  => 'newspaper'
                    ),
                    'email_campaigns.mailshots'   => array(
                        'label' => _('Marketing mailshots'),
                        'icon'  => 'bullhorn'
                    ),

*/
                    'customer_notifications' => array(
                        'label' => _('Operations'),
                        'icon'  => 'handshake-alt',


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


            'email_campaign_type' => array(
                'type' => 'object',
                'tabs' => array(
                    'email_campaign_type.details' => array(
                        'label' => _('Settings'),
                        'icon'  => 'sliders-h',
                    ),

                    'email_campaign_type.next_recipients' => array(
                        'label' => _('Notifications to be send next shot'),
                        'title' => _('Next mailshot recipients'),
                        'icon'  => 'user-clock'
                    ),
                    'email_campaign_type.workshop'        => array(
                        'label' => _('Workshop'),
                        'icon'  => 'wrench'
                    ),

                    'email_campaign_type.mailshots'   => array(
                        'label' => _('Mailshots'),
                        'icon'  => 'container-storage'
                    ),
                    'email_campaign_type.sent_emails' => array(
                        'label' => _('Sent emails'),
                        'icon'  => 'paper-plane'
                    ),

                    'email_campaign_type.history' => array(
                        'title' => _('History'),
                        'label' => '',
                        'icon'  => 'road',
                        'class' => 'right icon_only'
                    ),

                )
            ),


            'mailshot' => array(
                'type' => 'object',
                'tabs' => array(
                    'mailshot.details'       => array(
                        'label' => _('Settings'),
                        'icon'  => 'sliders-h',
                        'title' => _('Settings')
                    ),
                    'mailshot.set_mail_list' => array(
                        'label' => _('Set recipients'),
                        'icon'  => 'users',
                    ),

                    'mailshot.mail_list' => array(
                        'label' => _('Recipients'),
                        'icon'  => 'users',
                    ),

                    'mailshot.workshop' => array(
                        'label' => _('Workshop'),
                        'icon'  => 'wrench'
                    ),

                    'mailshot.published_email' => array(
                        'label' => _('Email'),
                        'icon'  => 'envelope'
                    ),


                    'mailshot.sent_emails' => array(
                        'label' => _('Sent emails'),
                        'icon'  => 'paper-plane'
                    ),

                    'mailshot.history' => array(
                        'title' => _('History'),
                        'label' => '',
                        'icon'  => 'road',
                        'class' => 'right icon_only'
                    ),

                )
            ),

            'mailshot.new' => array(
                'type' => 'new_object',
                'tabs' => array(
                    'mailshot.new' => array(
                        'label' => 'new mailshot'
                    ),

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

                    'customer.active_portfolio' => 'customer.portfolio',
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
                        'subtabs' => array(
                            'customer.active_portfolio' => array(
                                'icon'=>'cube',
                                'label' => _("Customer's store products")
                            ),
                            'customer.removed_portfolio' => array(
                                'class'=>'icon_only right',
                                'icon'=>'ghost',
                                'label'=>_('Removed products'),
                                'title' => _('Removed from portfolio')
                            ),

                        )
                    ),

                    'customer.clients' => array(
                        'label' => _("Clients"),

                        'title'         => _("Customer's clients"),
                        'icon'          => 'address-book',

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


        )
    ),
    'customers_server' => array(

        'parent'      => 'none',
        'parent_type' => 'none',
        'section'     => 'customers',
        'sections'    => array(
            'customers'            => array(
                'type'      => 'navigation',
                'label'     => _('Customers (All stores)'),
                'title'     => _('Customers (All stores)'),
                'icon'      => '',
                'reference' => 'customers/all',
                'tabs'      => array(
                    'customers_server' => array()
                )
            ),
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

    ),
    'orders'           => array(
        'section'     => 'orders',
        'parent'      => 'store',
        'parent_type' => 'key',
        'sections'    => array(


            'dashboard' => array(

                'type'      => 'navigation',
                'label'     => _('Control panel'),
                'icon'      => 'stream',
                'reference' => 'orders/%d/dashboard',
                'tabs'      => array(
                    'orders.dashboard' => array('label' => _('Dashboard'))

                )
            ),


            'orders' => array(
                'type'      => 'navigation',
                'label'     => _('Orders'),
                'icon'      => 'shopping-cart',
                'reference' => 'orders/%d',
                'tabs'      => array(
                    'orders' => array()
                )
            ),


            'order'   => array(
                'type' => 'object',
                'tabs' => array(


                    'order.items'        => array(
                        'label' => _(
                            'Items'
                        ),
                        'icon'  => 'bars'
                    ),
                    'order.all_products' => array(
                        'label' => _("All products"),
                        'icon'  => 'th-list'
                    ),

                    'order.customer_history' => array(
                        'label' => _('Customer notes/history'),
                        'icon'  => 'user-tag'
                    ),


                    'order.payments' => array(
                        'label' => _('Payments'),
                        'icon'  => 'dollar-sign'
                    ),


                    'order.history'        => array(
                        'label' => '',
                        'title' => _('History'),
                        'icon'  => 'road',
                        'class' => 'right icon_only'
                    ),
                    'order.delivery_notes' => array(
                        'label' => '',
                        'title' => _('Delivery notes'),
                        'icon'  => 'truck',
                        'class' => 'right icon_only'
                    ),
                    'order.invoices'       => array(
                        'label' => '',
                        'title' => _('Invoices'),
                        'icon'  => 'file-invoice-dollar',
                        'class' => 'right icon_only'
                    ),
                    'order.deals'          => array(
                        'label' => '',
                        'title' => _('Discounts'),
                        'icon'  => 'tag',
                        'class' => 'right icon_only'
                    ),

                    'order.sent_emails' => array(
                        'title' => _('Sent emails'),
                        'label' => '',
                        'icon'  => 'envelope',
                        'class' => 'right icon_only'
                    ),
                    'order.details'     => array(
                        'label' => '',
                        'title' => _('Data'),
                        'icon'  => 'database',
                        'class' => 'right icon_only'
                    ),

                    'order.input_picking_sheet' => array(
                        'label' => _('Picking/Packing data entry'),
                        'icon'  => 'keyboard',
                        'class' => 'hide'
                    ),


                )

            ),
            'invoice' => array(
                'type' => 'object',
                'tabs' => array(
                    'invoice.details' => array(
                        'label' => _('Properties/Operations'),
                        'icon'  => 'database'
                    ),

                    'invoice.items' => array(
                        'label' => _('Items'),
                        'icon'  => 'bars'
                    ),

                    'invoice.payments' => array(
                        'label' => _('Payments'),
                        'icon'  => 'dollar-sign',

                    ),
                    'invoice.history'  => array(
                        'label' => '',
                        'title' => _('History'),
                        'icon'  => 'road',
                        'class' => 'right icon_only'

                    )

                )

            ),

            'deleted_invoice' => array(
                'type' => 'object',
                'tabs' => array(
                    'deleted_invoice.items' => array(
                        'label' => _('Items'),
                        'icon'  => 'bars'
                    ),
                    'invoice.history'       => array(
                        'label' => _('History'),
                        'icon'  => 'road'
                    ),


                )
            ),

            'delivery_note' => array(
                'type' => 'object',
                'tabs' => array(


                    'delivery_note.items' => array(
                        'label' => _(
                            'Items'
                        ),
                        'icon'  => 'bars'
                    ),


                    'delivery_note.picking_aid' => array(
                        'label' => _('Picking aid'),
                        'icon'  => 'fa-hand-lizard'
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
                        'icon'  => 'file-invoice-dollar'
                    ),
                )

            ),


            'refund' => array(
                'type' => 'object',
                'tabs' => array(


                    'invoice.items'    => array(
                        'label' => _('Items'),
                        'icon'  => 'bars'
                    ),
                    'invoice.details'  => array(
                        'label' => _('Data'),
                        'icon'  => 'database'
                    ),
                    'invoice.payments' => array(
                        'label' => _('Payments'),
                        'icon'  => 'fa-dollar-sign',

                    ),
                    'invoice.history'  => array(
                        'label' => '',
                        'title' => _('History'),
                        'icon'  => 'road',
                        'class' => 'right icon_only'

                    )

                )

            ),


            'replacement.new' => array(
                'type' => 'object',
                'tabs' => array(


                    'replacement.new.items' => array(
                        'label' => _(
                            'Items'
                        ),
                        'icon'  => 'bars'
                    )


                )

            ),
            'refund.new'      => array(
                'type' => 'object',
                'tabs' => array(


                    'refund.new.items'     => array(
                        'label' => _('Transactions'),
                        'icon'  => 'bars'
                    ),
                    'refund.new.items_tax' => array(
                        'label' => 'Transactions (Tax only)',
                        'icon'  => 'bars'
                    )

                )

            ),
            'return.new'      => array(
                'type' => 'object',
                'tabs' => array(


                    'return.new.items' => array(
                        'label' => _('Items'),
                        'icon'  => 'bars'
                    )


                )

            ),
            'payment'         => array(
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
                        'icon'  => 'sticky-note'
                    ),

                )
            ),
            'mailshot'        => array(
                'type'           => 'object',
                'subtabs_parent' => array(
                    'mailshot.workshop.templates'              => 'mailshot.workshop',
                    'mailshot.workshop.previous_mailshots'     => 'mailshot.workshop',
                    'mailshot.workshop.other_stores_mailshots' => 'mailshot.workshop',
                    'mailshot.workshop.composer'               => 'mailshot.workshop',
                    'mailshot.workshop.composer_text'          => 'mailshot.workshop',

                ),
                'tabs'           => array(
                    'mailshot.details'       => array(
                        'label' => _('Settings'),
                        'icon'  => 'sliders-h',
                        'title' => _('Settings')
                    ),
                    'mailshot.set_mail_list' => array(
                        'label' => _('Set recipients'),
                        'icon'  => 'users',
                    ),

                    'mailshot.mail_list' => array(
                        'label' => _('Recipients'),
                        'icon'  => 'users',
                    ),

                    'mailshot.workshop' => array(
                        'label'   => _('Workshop'),
                        'icon'    => 'wrench',
                        'subtabs' => array(

                            'mailshot.workshop.composer'      => array(
                                'label'   => _('HTML email composer'),
                                'icon_v2' => 'fab fa-html5'
                            ),
                            'mailshot.workshop.composer_text' => array(
                                'label' => _('Plain text version'),
                                'icon'  => 'align-left'
                            ),

                            'mailshot.workshop.templates' => array(
                                'label' => _('Templates'),
                                'icon'  => 'clone'
                            ),

                            'mailshot.workshop.previous_mailshots'     => array(
                                'label' => _('Previous mailshots'),
                                'icon'  => 'history'
                            ),
                            'mailshot.workshop.other_stores_mailshots' => array(
                                'label' => _('Other stores mailshots'),
                                'icon'  => 'repeat-1'
                            ),


                        )

                    ),

                    'mailshot.published_email' => array(
                        'label' => _('Email'),
                        'icon'  => 'envelope'
                    ),


                    'mailshot.sent_emails' => array(
                        'label' => _('Sent emails'),
                        'icon'  => 'paper-plane'
                    ),

                    'mailshot.history' => array(
                        'title' => _('History'),
                        'label' => '',
                        'icon'  => 'road',
                        'class' => 'right icon_only'
                    ),

                )
            ),

            'email_tracking' => array(
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

            'purge' => array(
                'type' => 'object',
                'tabs' => array(
                    'purge.details' => array(
                        'icon' => 'sliders-h',

                        'label' => _('Settings'),
                        'title' => _('Settings')
                    ),


                    'purge.purged_orders' => array(
                        'label' => _('Purged orders'),
                        'icon'  => 'skull'
                    ),

                    'purge.history' => array(
                        'title' => _('History'),
                        'label' => '',
                        'icon'  => 'road',
                        'class' => 'right icon_only'
                    ),

                )
            ),

        )
    ),
    'orders_server'    => array(

        'parent'      => 'none',
        'parent_type' => 'none',
        'section'     => 'orders',
        'sections'    => array(


            'group_by_store' => array(
                'type'      => 'navigation',
                'label'     => _('Group by store'),
                'icon'      => 'compress',
                'reference' => 'orders/all/by_store',
                'tabs'      => array(
                    'orders_group_by_store' => array()
                )

            ),

            'dashboard' => array(

                'type'      => 'navigation',
                'label'     => _('Control panel'),
                'icon'      => 'stream',
                'reference' => 'orders/all/dashboard',
                'tabs'      => array(
                    'orders_server.dashboard' => array('label' => _('Dashboard'))

                )
            ),
            'orders'    => array(
                'type'      => 'navigation',
                'label'     => _('Orders'),
                'icon'      => 'shopping-cart',
                'reference' => 'orders/all',
                'tabs'      => array(
                    'orders_server' => array()
                )

            ),


            'mailshot' => array(
                'type' => 'object',
                'tabs' => array(
                    'email_campaign.details'          => array(
                        'label' => _('Data'),
                        'icon'  => 'database',
                        'title' => _('Details')
                    ),
                    'email_campaign.mail_list'        => array(
                        'label' => _('Recipients'),
                        'icon'  => 'users',
                    ),
                    'email_campaign.email_blueprints' => array(
                        'label'   => _('Email HTML templates'),
                        'icon_v2' => 'fab fa-html5'
                    ),
                    'email_campaign.email_template'   => array(
                        'label' => _('Email'),
                        'icon'  => 'envelope'
                    ),

                    'email_campaign.sent_emails' => array(
                        'label' => _('Sent email'),
                        'icon'  => 'paper-plane'
                    ),

                    'email_campaign.history' => array(
                        'title' => _('History'),
                        'label' => '',
                        'icon'  => 'road',
                        'class' => 'right icon_only'
                    ),


                )
            ),
            'order'    => array(
                'type' => 'object',
                'tabs' => array(


                    'order.items'        => array(
                        'label' => _(
                            'Items'
                        ),
                        'icon'  => 'bars'
                    ),
                    'order.all_products' => array(
                        'label' => _("All products"),
                        'icon'  => 'th-list'
                    ),

                    'order.customer_history' => array(
                        'label' => _('Customer notes/history'),
                        'icon'  => 'user-tag'
                    ),


                    'order.payments' => array(
                        'label' => _('Payments'),
                        'icon'  => 'dollar-sign'
                    ),


                    'order.history'        => array(
                        'label' => '',
                        'title' => _('History'),
                        'icon'  => 'road',
                        'class' => 'right icon_only'
                    ),
                    'order.delivery_notes' => array(
                        'label' => '',
                        'title' => _('Delivery notes'),
                        'icon'  => 'truck',
                        'class' => 'right icon_only'
                    ),
                    'order.invoices'       => array(
                        'label' => '',
                        'title' => _('Invoices'),
                        'icon'  => 'file-invoice-dollar',
                        'class' => 'right icon_only'
                    ),
                    'order.deals'          => array(
                        'label' => '',
                        'title' => _('Discounts'),
                        'icon'  => 'tag',
                        'class' => 'right icon_only'
                    ),

                    'order.sent_emails' => array(
                        'title' => _('Sent emails'),
                        'label' => '',
                        'icon'  => 'envelope',
                        'class' => 'right icon_only'
                    ),
                    'order.details'     => array(
                        'label' => '',
                        'title' => _('Data'),
                        'icon'  => 'database',
                        'class' => 'right icon_only'
                    ),

                    'order.input_picking_sheet' => array(
                        'label' => _('Picking/Packing data entry'),
                        'icon'  => 'keyboard',
                        'class' => 'hide'
                    ),


                )

            ),

        )

    ),
    /*
    'invoices'              => array(
        'section'     => 'invoices',
        'parent'      => 'store',
        'parent_type' => 'key',
        'sections'    => array(

            'invoices' => array(
                'type'      => 'navigation',
                'label'     => _('Invoices'),
                'icon'      => 'file-invoice-dollar',
                'reference' => 'invoices/%d',
                'tabs'      => array(
                    'invoices' => array(
                        'label' => _(
                            'Invoices'
                        ),
                        'icon'  => 'file-invoice-dollar'
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
                        'icon'  => 'file-invoice-dollar'
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
                        'icon'  => 'file-invoice-dollar'
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
    */


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
                        'icon'  => 'file-invoice-dollar'
                    ),

                )

            ),
            'delivery_note' => array(
                'type'  => 'object',
                'title' => _('Delivery note'),
                'tabs'  => array(


                    'delivery_note.items' => array(
                        'label' => _(
                            'Items'
                        ),
                        'icon'  => 'bars'
                    ),


                    'delivery_note.fast_track_packing' => array(
                        'label' => _('Fast track packing'),
                        'icon'  => 'bolt'
                    ),

                    'delivery_note.picking_aid' => array(
                        'label' => _('Picking aid'),
                        'icon'  => 'hand-lizard'
                    ),


                    'delivery_note.details' => array(
                        'label' => _(
                            'Data'
                        ),
                        'icon'  => 'database'
                    ),
                    'delivery_note.history' => array(
                        'label' => _(
                            'History'
                        ),
                        'icon'  => 'road',
                        'class' => 'right icon_only'


                    )
                    /*
                    ,
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
                        'icon'  => 'file-invoice-dollar'
                    ),

                    */
                )

            ),
            'invoice'       => array(
                'type' => 'object',
                'tabs' => array(


                    'invoice.items' => array(
                        'label' => _(
                            'Items'
                        ),
                        'icon'  => 'bars'
                    ),


                    'delivery_note.picking_aid' => array(
                        'label' => _('Picking aid'),
                        'icon'  => 'fa-hand-lizard'
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

            'pick_aid' => array(
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
            'pack_aid' => array(
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

    ),


    'accounting_server' => array(


        'sections' => array(


            'invoices' => array(
                'type'      => 'navigation',
                'label'     => _('Invoices').' ('._('All').')',
                'icon'      => 'file-invoice-dollar',
                'reference' => 'invoices/per_store',
                'tabs'      => array(
                    'invoices_per_store'      => array(
                        'label'     => _('Invoices per store'),
                        'icon'      => 'layer-group',
                        'reference' => 'invoices/per_store',
                    ),
                    'invoices.categories'     => array(
                        'label'     => _('Invoices per categories'),
                        'icon'      => 'sitemap',
                        'reference' => 'invoices/category/all',
                    ),
                    'invoices_server'         => array(
                        'label'     => _('All invoices'),
                        'icon'      => 'copy',
                        'reference' => 'invoices/all',
                    ),
                    'deleted_invoices_server' => array(
                        'label'     => _('All deleted invoices'),
                        'icon'      => 'ban',
                        'reference' => 'invoices/deleted/all',
                    )

                )

            ),


            'payments' => array(
                'type'      => 'navigation',
                'label'     => _('Payments').' ('._('All').')',
                'icon'      => 'credit-card',
                'reference' => 'payments/per_store',
                'tabs'      => array(

                    'payments_group_by_store' => array(
                        'label'     => _('Payments per store'),
                        'icon'      => 'layer-group',
                        'reference' => 'payments/per_store'
                    ),

                    'account.payments' => array(
                        'icon'      => 'credit-card',
                        'label'     => _('Payments'),
                        'reference' => 'payments/all'
                    ),

                    'account.payment_accounts'  => array(
                        'label'     => _("Payment accounts"),
                        'icon'      => 'money-check-alt',
                        'reference' => 'payment_accounts/all'
                    ),
                    'payment_service_providers' => array(
                        'label'     => _('Payment service providers'),
                        'icon'      => 'cash-register',
                        'reference' => 'payment_service_providers/all'

                    ),

                )
            ),


            'credits' => array(
                'type'      => 'navigation',
                'label'     => _('Credit vault').' ('._('All').')',
                'html_icon' => '<i class="fa fa-piggy-bank"></i>',
                'reference' => 'credits/all',

                'tabs' => array(

                    'credits_group_by_store' => array(
                        'label'     => _('Credits per store'),
                        'icon'      => 'layer-group',
                        'reference' => 'credits/per_store'
                    ),


                    'account.credits' => array(
                        'label' => _('Credits'),
                        'icon'  => 'university',

                    ),
                )
            ),


            'category' => array(
                'type' => 'object',

                'tabs' => array(
                    'category.details' => array(
                        'label' => _('Data'),
                        'icon'  => 'database',
                        'title' => _('Details')
                    ),


                    'category.invoices' => array('label' => _('Invoices')),

                    'category.history' => array(
                        'title' => _('History'),
                        'label' => '',
                        'icon'  => 'road',
                        'class' => 'right icon_only'
                    ),

                )

            ),


            'payment_service_provider' => array(
                'type' => 'object',
                'tabs' => array(
                    'payment_service_provider.details' => array(
                        'label' => _('Data'),
                        'icon'  => 'database'
                    ),

                    'payment_service_provider.accounts' => array(
                        'label' => _('Accounts'),
                        'title' => _('Payment accounts'),
                        'icon'  => 'money-check-alt',
                    ),
                    'payment_service_provider.payments' => array(
                        'label' => _('Payments'),
                        'title' => _('Payments transactions'),
                        'icon'  => 'credit-card'
                    ),

                    'payment_service_provider.history' => array(
                        'title' => _('History'),
                        'label' => '',
                        'icon'  => 'road',
                        'class' => 'right icon_only'
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
                        'title'         => _('History, notes'),
                        'label'         => '',
                        'quantity_data' => array(
                            'object' => '_object',
                            'field'  => 'Number History Records'
                        ),

                        'icon'  => 'road',
                        'class' => 'right icon_only'
                    ),
                    'payment_account.payments' => array(
                        'label'         => _('Payments'),
                        'title'         => _('Payments & refunds'),
                        'quantity_data' => array(
                            'object' => '_object',
                            'field'  => 'Transactions'
                        ),
                    ),
                    'payment_account.stores'   => array(
                        'label'         => _('Stores'),
                        'quantity_data' => array(
                            'object' => '_object',
                            'field'  => 'Number Stores'
                        ),
                    )

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
                        'icon'  => 'sticky-note'
                    ),

                )
            ),


            'invoice'         => array(
                'type' => 'object',
                'tabs' => array(
                    'invoice.details' => array(
                        'label' => _('Properties/Operations'),
                        'icon'  => 'database'
                    ),

                    'invoice.items' => array(
                        'label' => _('Items'),
                        'icon'  => 'bars'
                    ),

                    'invoice.payments' => array(
                        'label' => _('Payments'),
                        'icon'  => 'dollar-sign',

                    ),
                    'invoice.history'  => array(
                        'label' => '',
                        'title' => _('History'),
                        'icon'  => 'road',
                        'class' => 'right icon_only'

                    )

                )

            ),
            'deleted_invoice' => array(
                'type' => 'object',
                'tabs' => array(
                    'deleted_invoice.items' => array(
                        'label' => _('Items'),
                        'icon'  => 'bars'
                    ),
                    'invoice.history'       => array(
                        'label' => _('History'),
                        'icon'  => 'road'
                    ),


                )
            ),


        ),

    ),

    'accounting' => array(

        'sections' => array(


            'invoices' => array(
                'type'      => 'navigation',
                'label'     => _('Invoices'),
                'icon'      => 'file-invoice-dollar',
                'reference' => 'invoices/%d',
                'tabs'      => array(

                    'invoices' => array(
                        'label' => _('Invoices'),
                        'icon'  => 'file-invoice-dollar',
                    )

                )

            ),

            'invoice' => array(
                'type' => 'object',
                'tabs' => array(

                    'invoice.items' => array(
                        'label' => _('Items'),
                        'icon'  => 'bars'
                    ),

                    'invoice.payments' => array(
                        'label' => _('Payments'),
                        'icon'  => 'dollar-sign',

                    ),
                    'invoice.details'  => array(
                        'label' => _('Properties/Operations'),
                        'icon'  => 'database'
                    ),

                    'invoice.history' => array(
                        'label' => '',
                        'title' => _('History'),
                        'icon'  => 'road',
                        'class' => 'right icon_only'

                    )

                )

            ),


            'deleted_invoice'  => array(
                'type' => 'object',
                'tabs' => array(
                    'deleted_invoice.items' => array(
                        'label' => _('Items'),
                        'icon'  => 'bars'
                    ),
                    'invoice.history'       => array(
                        'label' => _('History'),
                        'icon'  => 'road'
                    ),


                )
            ),
            'deleted_invoices' => array(
                'type'      => 'navigation',
                'label'     => _('Deleted invoices'),
                'icon'      => 'ban',
                'reference' => 'invoices/deleted/%s',
                'tabs'      => array(
                    'deleted_invoices' => array(
                        'label' => _('Deleted invoices'),
                        'icon'  => 'ban',
                    ),

                )
            ),
            'payments'         => array(
                'type'      => 'navigation',
                'label'     => _('Payments'),
                'icon'      => 'credit-card',
                'reference' => 'payments/%s',
                'tabs'      => array(
                    'store.payments'         => array(
                        'label'             => _('Payments'),
                        'icon'              => 'credit-card',
                        'dynamic_reference' => 'payments/%s',
                    ),
                    'store.payment_accounts' => array(
                        'icon'              => 'money-check-alt',
                        'label'             => _("Payment accounts"),
                        'dynamic_reference' => 'payment_accounts/%s',
                    )
                )
            ),

            'credits' => array(
                'type'      => 'navigation',
                'label'     => _('Credit vault'),
                'icon'      => 'piggy-bank',
                'reference' => 'credits/%d',
                'tabs'      => array(
                    'store.credits' => array(
                        'label' => _('Credits'),
                        'icon'  => 'piggy-bank',

                    ),
                )
            ),


            'payment_account' => array(
                'type' => 'object',
                'tabs' => array(
                    'payment_account.details'        => array(
                        'label' => _('Data'),
                        'icon'  => 'database',
                        'title' => _('Details')
                    ),
                    'store.payment_account.history'  => array(
                        'title'         => _('History, notes'),
                        'label'         => '',
                        'quantity_data' => array(
                            'object' => '_object',
                            'field'  => 'Number History records'
                        ),

                        'icon'  => 'road',
                        'class' => 'right icon_only'
                    ),
                    'store.payment_account.payments' => array(
                        'label' => _('Payments'),
                        'title' => _('Payments & refunds'),

                    )


                )
            ),
            'payment'         => array(
                'type' => 'object',
                'tabs' => array(
                    'payment.details' => array(
                        'label' => _('Data'),
                        'icon'  => 'database',
                        'title' => _('Details')
                    ),
                    'payment.history' => array(
                        'label' => _('History, notes'),
                        'icon'  => 'sticky-note'
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


    'websites' => array(
        'section'     => 'dashboard',
        'parent'      => 'website',
        'parent_type' => 'key',
        'sections'    => array(


            'webpage.new' => array(
                'type'  => 'new_object',
                'title' => _("New website"),
                'tabs'  => array(
                    'webpage.new' => array(
                        'label' => _(
                            'New Webpage'
                        )
                    ),

                )

            ),
            'analytics'   => array(
                'type'      => 'navigation',
                'label'     => _('Analytics'),
                'icon'      => 'analytics',
                'reference' => 'website/%d/analytics',

                'tabs' => array(
                    'website.analytics' => array(
                        'label' => _('Analytics'),
                        'icon'  => 'analytics'
                    ),
                )

            ),
            'webpages'    => array(
                'type'      => 'navigation',
                'label'     => _('Web pages'),
                'icon'      => 'browser',
                'reference' => 'webpages/%d',

                'tabs' => array(
                    'website.webpage.types'       => array(
                        'label' => _('Web pages by type'),
                        'icon'  => 'server'
                    ),
                    'website.online_webpages'     => array(
                        'label' => _('Online web pages'),
                        'icon'  => 'browser'
                    ),
                    'website.in_process_webpages' => array(
                        'label' => _('To be published web pages'),
                        'icon'  => 'seedling'
                    ),


                    'website.offline_webpages' => array(
                        'label' => _('Offline web pages'),
                        'class' => 'right icon_only',
                        'icon'  => 'eye-slash'
                    ),

                )

            ),
            'web_users'   => array(
                'type'      => 'navigation',
                'label'     => _('Users'),
                'icon'      => 'users-class',
                'reference' => 'website/%d/users',

                'tabs' => array(
                    'website.users' => array(
                        'label' => _('Users'),
                        'icon'  => 'users'
                    )


                )

            ),


            'workshop' => array(
                'type'      => 'navigation',
                'label'     => _('Workshop'),
                'icon'      => 'drafting-compass',
                'reference' => 'website/%d/workshop',

                'tabs' => array(


                    'website.header.preview' => array(
                        'label' => _('Header'),
                        'icon'  => 'arrow-alt-to-top'
                    ),
                    'website.menu.preview'   => array(
                        'label' => _('Menu'),
                        'icon'  => 'bars'
                    ),
                    'website.footer.preview' => array(
                        'label' => _('Footer'),
                        'icon'  => 'arrow-alt-to-bottom'
                    ),


                )

            ),


            'settings' => array(
                'type'      => 'navigation',
                'label'     => _('Settings'),
                'icon'      => 'sliders-h',
                'reference' => 'website/%d/settings',

                'tabs' => array(
                    'website.details'      => array(
                        'label' => _('Setting'),
                        'icon'  => 'sliders-h'
                    ),
                    'website.colours'      => array(
                        'label' => _('Colours'),
                        'icon'  => 'tint',


                    ),
                    'website.localization' => array(
                        'label' => _('Localization'),
                        'icon'  => 'language',
                    ),
                ),


            ),

            'webpage_type' => array(
                'type' => 'object',
                'tabs' => array(

                    'webpage_type.online_webpages'     => array(
                        'label' => _('Online web pages'),
                        'icon'  => 'browser'
                    ),
                    'webpage_type.in_process_webpages' => array(
                        'label' => _('To be published web pages'),
                        'icon'  => 'seedling'
                    ),


                    'webpage_type.offline_webpages' => array(
                        'label' => _('Offline web pages'),
                        'class' => 'right icon_only',
                        'icon'  => 'eye-slash'
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
                        'icon'  => 'sticky-note'
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
            ),

            'webpage' => array(
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

                    'webpage.online_webpages'     => 'webpage.webpages',
                    'webpage.offline_webpages'    => 'webpage.webpages',
                    'webpage.webpage.types'       => 'webpage.webpages',
                    'webpage.in_process_webpages' => 'webpage.webpages',

                    'webpage.footer.preview' => 'webpage.templates',
                    'webpage.header.preview' => 'webpage.templates',


                    'webpage.templates' => 'webpage.templates',


                    'user_notifications'             => 'store.notifications',
                    'store.notifications_recipients' => 'store.notifications',
                    'localization.materials'         => 'store.localization',
                    'localization.website'           => 'store.localization'

                ),

                'tabs' => array(


                    'webpage.details' => array(
                        'label' => _('Data'),
                        'icon'  => 'database'
                    ),
                    'webpage.assets'  => array(
                        'label' => _('Asset links'),
                        'icon'  => 'grip-horizontal'
                    ),
                    'webpage.preview' => array(
                        'label' => _('Workshop'),
                        'icon'  => 'wrench',
                        'class' => 'hide'
                    ),


                    'webpage.logbook' => array(
                        'label' => _('Logbook'),
                        'icon'  => 'road'
                    ),


                )
            ),


            'deleted.webpage' => array(
                'type'  => 'object',
                'title' => _('Deleted web page'),
                'tabs'  => array(


                    'deleted.webpage.history' => array(
                        'label' => _('History/Notes'),
                        'icon'  => 'road',
                        'class' => 'right icon_only'
                    )

                )

            ),


            //'categories'=>array('label'=>_('Categories'),'title'=>_('Categories'),'icon'=>'sitemap','reference'=>'orders/categories/%d'),
        )
    ),


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
                'label'     => _('Dashboard'),
                'title'     => _('Dashboard'),
                'icon'      => 'tachometer-alt',
                'showcase'  => 'store',
                'reference' => 'store/%d',


                'tabs' => array(

                    'store.sales.plot' => array(
                        'label' => _('Plot')
                    ),



                    'store.history' => array(
                        'label' => _('History'),
                        'icon'  => 'road',
                        'class' => 'right icon_only'
                    ),


                )
            ),

            'store.new' => array(
                'type'  => 'new_object',
                'title' => _('New store'),
                'tabs'  => array(
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
                'class'     => 'hide',
                'reference' => 'services/%d',
                'tabs'      => array(
                    'services' => array()
                )
            ),
            'products' => array(
                'type'      => 'navigation',
                'label'     => _('Products'),
                'title'     => _("Products"),
                'icon'      => 'cube',
                'reference' => 'products/%d',
                'tabs'      => array(
                    'products'                                    => array(
                        'label' => _('Products'),
                        'icon'  => 'cube',

                    ),
                    'back_to_stock_notification_request.products' => array(
                        'label' => _('Back in stock notification requests'),
                        'icon'  => 'dolly',

                    ),
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


            'offers' => array(
                'type'  => 'navigation',
                'label' => _('Offers'),

                'icon'      => 'tags',
                'reference' => 'offers/%d',
                'tabs'      => array(
                    'campaigns' => array(
                        'label' => _("Offer's categories"),
                        'icon'  => 'tags',
                    ),
                    'deals'     => array(
                        'label' => _('Offers'),
                        'icon'  => 'tag'
                    ),

                )

            ),

            'marketing' => array(
                'type'  => 'navigation',
                'label' => _('Marketing'),

                'icon'      => 'bullhorn',
                'reference' => 'marketing/%d/emails',
                'tabs'      => array(
                    'marketing_emails' => array(
                        'label' => _('Marketing emails'),
                        'icon'  => 'tags',
                    ),


                )

            ),


            'settings' => array(
                'type'  => 'navigation',
                'label' => _('Settings'),

                'icon'           => 'sliders-h',
                'reference'      => 'store/%d/settings',
                'subtabs_parent' => array(


                    'store.current_shipping_zones'   => 'store.shipping_zones',
                    'store.shipping_zones_schemas'   => 'store.shipping_zones',
                    'user_notifications'             => 'store.notifications',
                    'store.notifications_recipients' => 'store.notifications',


                ),
                'tabs'           => array(

                    'store.details' => array(
                        'label' => _('General'),
                        'icon'  => 'sliders-h',
                        'title' => _('Store settings')
                    ),

                    'store.charges' => array(
                        'label' => _('Charges'),
                        'icon'  => 'money',
                    ),

                    'store.shipping_zones' => array(
                        'label'   => _('Shipping zones'),
                        'icon'    => 'truck fa-flip-horizontal',
                        'subtabs' => array(

                            'store.current_shipping_zones' => array(
                                'label' => _('Shipping zones'),
                                'icon'  => 'globe-americas',
                            ),


                            'store.shipping_zones_schemas' => array(
                                'label' => _('Shipping zones schemas'),
                                'icon'  => 'layer-group ',
                            ),


                        ),
                    ),

                    'store.notifications' => array(
                        'label'   => _('Notifications'),
                        'icon'    => 'bell',
                        'subtabs' => array(

                            'user_notifications' => array(
                                'label' => _('Notifications by type'),
                                'icon'  => 'bell-school',
                            ),

                            'store.notifications_recipients' => array(
                                'label' => _('Recipients'),
                                'icon'  => 'ear ',
                            ),


                        ),
                    ),
                    'localization.materials' => array(
                        'label'   => _('Localization (Materials/Ingredients)'),
                        'icon'    => 'language',




                    ),
                    'store.history' => array(
                        'label' => _('History'),
                        'icon'  => 'road',
                        'class' => 'right icon_only'
                    ),


                )

            ),


            'email_campaign_type' => array(
                'type' => 'object',
                'tabs' => array(
                    'email_campaign_type.details' => array(
                        'label' => _('Settings'),
                        'icon'  => 'sliders-h',
                    ),

                    'email_campaign_type.next_recipients' => array(
                        'label' => _('Notifications to be send next shot'),
                        'title' => _('Next mailshot recipients'),
                        'icon'  => 'user-clock'
                    ),
                    'email_campaign_type.workshop'        => array(
                        'label' => _('Workshop'),
                        'icon'  => 'wrench'
                    ),

                    'email_campaign_type.mailshots'   => array(
                        'label' => _('Mailshots'),
                        'icon'  => 'container-storage'
                    ),
                    'email_campaign_type.sent_emails' => array(
                        'label' => _('Sent emails'),
                        'icon'  => 'paper-plane'
                    ),

                    'email_campaign_type.history' => array(
                        'title' => _('History'),
                        'label' => '',
                        'icon'  => 'road',
                        'class' => 'right icon_only'
                    ),

                )
            ),


            'mailshot' => array(
                'type'           => 'object',
                'title'          => _("Mailshot"),
                'subtabs_parent' => array(
                    'mailshot.workshop.templates'              => 'mailshot.workshop',
                    'mailshot.workshop.previous_mailshots'     => 'mailshot.workshop',
                    'mailshot.workshop.other_stores_mailshots' => 'mailshot.workshop',
                    'mailshot.workshop.composer'               => 'mailshot.workshop',
                    'mailshot.workshop.composer_text'          => 'mailshot.workshop',

                ),
                'tabs'           => array(
                    'mailshot.details'       => array(
                        'label' => _('Settings'),
                        'icon'  => 'sliders-h',
                        'title' => _('Settings')
                    ),
                    'mailshot.set_mail_list' => array(
                        'label' => _('Set recipients'),
                        'icon'  => 'users',
                    ),

                    'mailshot.mail_list' => array(
                        'label' => _('Recipients'),
                        'icon'  => 'users',
                    ),

                    'mailshot.workshop' => array(
                        'label'   => _('Workshop'),
                        'icon'    => 'wrench',
                        'subtabs' => array(

                            'mailshot.workshop.composer'      => array(
                                'label'   => _('HTML email composer'),
                                'icon_v2' => 'fab fa-html5'
                            ),
                            'mailshot.workshop.composer_text' => array(
                                'label' => _('Plain text version'),
                                'icon'  => 'align-left'
                            ),

                            'mailshot.workshop.templates' => array(
                                'label' => _('Templates'),
                                'icon'  => 'clone'
                            ),

                            'mailshot.workshop.previous_mailshots'     => array(
                                'label' => _('Previous mailshots'),
                                'icon'  => 'history'
                            ),
                            'mailshot.workshop.other_stores_mailshots' => array(
                                'label' => _('Other stores mailshots'),
                                'icon'  => 'repeat-1'
                            ),


                        )

                    ),

                    'mailshot.published_email' => array(
                        'label' => _('Email'),
                        'icon'  => 'envelope'
                    ),


                    'mailshot.sent_emails' => array(
                        'label' => _('Sent emails'),
                        'icon'  => 'paper-plane'
                    ),

                    'mailshot.history' => array(
                        'title' => _('History'),
                        'label' => '',
                        'icon'  => 'road',
                        'class' => 'right icon_only'
                    ),

                )
            ),

            'mailshot.new' => array(
                'type' => 'new_object',
                'tabs' => array(
                    'mailshot.new' => array(
                        'label' => 'new mailshot'
                    ),

                )

            ),


            'email_tracking' => array(
                'type'  => 'object',
                'title' => _("Email tracking"),

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

            'vouchers' => array(
                'type'  => 'object',
                'title' => _("Vouchers"),

                'tabs' => array(


                    'campaign.deals'     => array(
                        'label' => _('Vouchers'),
                        'icon'  => 'money-bill-wave',

                    ),
                    'campaign.orders'    => array(
                        'label'         => _('Orders'),
                        'icon'          => 'shopping-cart',
                        'quantity_data' => array(
                            'object' => '_object',
                            'field'  => 'Used Orders'
                        ),
                    ),
                    'campaign.customers' => array(
                        'label'         => _('Customers'),
                        'icon'          => 'users',
                        'quantity_data' => array(
                            'object' => '_object',
                            'field'  => 'Used Customers'
                        ),
                    ),
                    'campaign.history'   => array(
                        'title'         => _('History, notes'),
                        'label'         => '',
                        'icon'          => 'road',
                        'class'         => 'right icon_only',
                        'quantity_data' => array(
                            'object' => '_object',
                            'field'  => 'Number History Records'
                        ),
                    ),
                    'campaign.details'   => array(
                        'class' => 'right icon_only',
                        'label' => '',
                        'icon'  => 'sliders-h',
                        'title' => _('Settings')
                    ),

                )
            ),


            'campaign' => array(
                'type'  => 'object',
                'title' => _("Offer category"),

                'tabs' => array(


                    'campaign.deals'     => array(
                        'label'         => _('Offers'),
                        'icon'          => 'tags',
                        'quantity_data' => array(
                            'object' => '_object',
                            'field'  => 'Number Current Deals'
                        ),
                    ),
                    'campaign.orders'    => array(
                        'label'         => _('Orders'),
                        'icon'          => 'shopping-cart',
                        'quantity_data' => array(
                            'object' => '_object',
                            'field'  => 'Used Orders'
                        ),
                    ),
                    'campaign.customers' => array(
                        'label'         => _('Customers'),
                        'icon'          => 'users',
                        'quantity_data' => array(
                            'object' => '_object',
                            'field'  => 'Used Customers'
                        ),
                    ),
                    'campaign.history'   => array(
                        'title'         => _('History, notes'),
                        'label'         => '',
                        'icon'          => 'road',
                        'class'         => 'right icon_only',
                        'quantity_data' => array(
                            'object' => '_object',
                            'field'  => 'Number History Records'
                        ),
                    ),
                    'campaign.details'   => array(
                        'class' => 'right icon_only',
                        'label' => '',
                        'icon'  => 'sliders-h',
                        'title' => _('Settings')
                    ),

                )
            ),


            'campaign_order_recursion' => array(
                'type'  => 'object',
                'title' => _("Reorder incentive"),

                'tabs' => array(

                    'campaign_order_recursion.components' => array(
                        'label'         => _('Offers'),
                        'icon'          => 'tags',
                        'quantity_data' => array(
                            'object' => '_object',
                            'field'  => 'Number Deal Components'
                        ),
                    ),
                    'campaign_order_recursion.reminders'  => array(
                        'label' => _('Reminders'),
                        'icon'  => 'envelope'

                    ),
                    'campaign.orders'                     => array(
                        'label'         => _('Orders'),
                        'icon'          => 'shopping-cart',
                        'quantity_data' => array(
                            'object' => '_object',
                            'field'  => 'Used Orders'
                        ),
                    ),
                    'campaign.customers'                  => array(
                        'label'         => _('Customers'),
                        'icon'          => 'users',
                        'quantity_data' => array(
                            'object' => '_object',
                            'field'  => 'Used Customers'
                        ),
                    ),
                    'campaign.history'                    => array(
                        'title'         => _('History, notes'),
                        'label'         => '',
                        'icon'          => 'road',
                        'class'         => 'right icon_only',
                        'quantity_data' => array(
                            'object' => '_object',
                            'field'  => 'Number History Records'
                        ),
                    ),

                    'campaign.details' => array(
                        'class' => 'right icon_only',
                        'label' => '',
                        'icon'  => 'sliders-h',
                        'title' => _('Settings')
                    ),

                )
            ),


            'deal' => array(
                'type'  => 'object',
                'title' => _("Offer"),

                'tabs' => array(
                    'deal.details' => array(
                        'label' => _('Data'),
                        'icon'  => 'database',
                        'title' => _('Details')
                    ),

                    'deal.components' => array(
                        'label'         => _('Allowances'),
                        'icon'          => '',
                        'quantity_data' => array(
                            'object' => '_object',
                            'field'  => 'Number Active Components'
                        ),


                    ),
                    'deal.orders'     => array(
                        'label' => _('Orders'),
                        'icon'  => 'shopping-cart',

                        'quantity_data' => array(
                            'object' => '_object',
                            'field'  => 'Used Orders'
                        ),
                    ),
                    'deal.customers'  => array(
                        'label'         => _('Customers'),
                        'icon'          => 'users',
                        'quantity_data' => array(
                            'object' => '_object',
                            'field'  => 'Used Customers'
                        ),
                    ),
                    'deal.history'    => array(
                        'title'         => _('History/Notes'),
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


            'deal_component' => array(
                'type'  => 'object',
                'title' => _("Offer"),

                'tabs' => array(
                    'deal_component.details' => array(
                        'label' => _('Settings'),
                        'icon'  => 'sliders-h',
                    ),


                    'deal_component.orders'    => array(
                        'label' => _('Orders'),
                        'icon'  => 'shopping-cart',

                        'quantity_data' => array(
                            'object' => '_object',
                            'field'  => 'Used Orders'
                        ),
                    ),
                    'deal_component.customers' => array(
                        'label'         => _('Customers'),
                        'icon'          => 'users',
                        'quantity_data' => array(
                            'object' => '_object',
                            'field'  => 'Used Customers'
                        ),
                    ),
                    'deal_component.history'   => array(
                        'title'         => _('History/Notes'),
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

            'deal.new'           => array(
                'type'  => 'new_object',
                'title' => _("New offer"),
                'tabs'  => array(
                    'deal.new' => array(
                        'label' => _(
                            'New offer'
                        )
                    ),

                )

            ),
            'deal_component.new' => array(
                'type'  => 'new_object',
                'title' => _("New offer"),
                'tabs'  => array(
                    'deal_component.new' => array(
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

                    'category.customers'         => 'category.customers',
                    'category.customers.favored' => 'category.customers',

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

                    'category.sales' => array(
                        'label'   => _('Sales'),
                        'icon'    => 'money-bill-alt',
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

                    'category.subjects'        => array('label' => ''),
                    'category.customers'       => array(
                        'label' => _('Customers'),
                        // 'quantity_data' => array('object' => '_object', 'field'  => 'Number Customers'),
                        'icon'  => 'user'
                    ),
                    'category.deal_components' => array(
                        'label' => _('Offers'),
                        'icon'  => 'tags'
                    ),


                    'category.mailshots' => array(
                        'label' => _('Mailshots'),
                        'icon'  => 'bullhorn'
                    ),

                    'category.sales_correlation' => array(
                        'title' => _('Sales correlations'),
                        'label' => _('Correlations'),
                        'icon'  => 'project-diagram',
                    ),


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

            'order' => array(
                'type' => 'object',
                'tabs' => array(


                    'order.items'          => array(
                        'label' => _('Items'),
                        'icon'  => 'bars'
                    ),
                    'order.details'        => array(
                        'label' => _('Data'),
                        'icon'  => 'database'
                    ),
                    'order.history'        => array(
                        'label' => _('History'),
                        'icon'  => 'road'
                    ),
                    'order.delivery_notes' => array(
                        'label' => _('Delivery notes'),
                        'icon'  => 'truck'
                    ),
                    'order.invoices'       => array(
                        'label' => _('Invoices'),
                        'icon'  => 'file-invoice-dollar'
                    ),
                    'order.payments'       => array(
                        'label' => _('Payments'),
                        'icon'  => 'fa-dollar-sign'
                    ),


                )

            ),


            'customer' => array(
                'type' => 'object',


                'tabs' => array(
                    'customer.details' => array(
                        'label' => _('Data'),
                        'icon'  => 'database'
                    ),

                    'customer.insights'  => array(
                        'label' => _('Insights'),
                        'icon'  => 'graduation-cap'
                    ),
                    'customer.history'   => array(
                        'label' => _('History, notes'),
                        'icon'  => 'sticky-note'
                    ),
                    'customer.orders'    => array('label' => _('Orders')),
                    'customer.invoices'  => array('label' => _('Invoices')),
                    'customer.marketing' => array(
                        'label'   => _('Interests'),
                        'title'   => _("Customer's interests"),
                        'subtabs' => array(
                            'customer.marketing.overview'   => array(
                                'label' => _(
                                    'Overview'
                                )
                            ),
                            'customer.marketing.products'   => array(
                                'label' => _('Products ordered')
                            ),
                            'customer.marketing.families'   => array(
                                'label' => _('Families ordered')
                            ),
                            'customer.marketing.favourites' => array(
                                'label' => _('Favourite products')
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


            'charge' => array(
                'type' => 'object',


                'tabs' => array(
                    'charge.details' => array(
                        'label' => _('Data'),
                        'icon'  => 'database',
                        'title' => _('Details')
                    ),


                    'charge.orders'    => array(
                        'label' => _('Orders'),
                        'icon'  => 'shopping-cart',

                        'quantity_data' => array(
                            'object' => '_object',
                            'field'  => 'Orders'
                        ),
                    ),
                    'charge.customers' => array(
                        'label'         => _('Customers'),
                        'icon'          => 'users',
                        'quantity_data' => array(
                            'object' => '_object',
                            'field'  => 'Customers'
                        ),
                    ),
                    'charge.history'   => array(
                        'title'         => _('History/Notes'),
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

            'shipping_zone_schema' => array(
                'type' => 'object',


                'tabs' => array(
                    /*
                    'shipping_zone_schema.details' => array(
                        'label' => _('Settings'),
                        'icon' => 'sliders-h',
                        'title' => _('Settings')
                    ),
                    */
                    'shipping_zone_schema.zones'   => array(
                        'label' => _('Zones'),
                        'icon'  => 'layer-group',

                        'quantity_data' => array(
                            'object' => '_object',
                            'field'  => 'Zones'
                        ),
                    ),
                    /*
                                        'shipping_zone_schema.orders' => array(
                                            'label' => _('Orders'),
                                            'icon' => 'shopping-cart',

                                            'quantity_data' => array(
                                                'object' => '_object',
                                                'field' => 'Orders'
                                            ),
                                        ),
                                        'shipping_zone_schema.customers' => array(
                                            'label' => _('Customers'),
                                            'icon' => 'users',
                                            'quantity_data' => array(
                                                'object' => '_object',
                                                'field' => 'Customers'
                                            ),
                                        ),
                    */
                    'shipping_zone_schema.history' => array(
                        'title'         => _('History/Notes'),
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
            'shipping_zone'        => array(
                'type' => 'object',


                'tabs' => array(
                    'shipping_zone.details' => array(
                        'label' => _('Data'),
                        'icon'  => 'database',
                        'title' => _('Details')
                    ),


                    'shipping_zone.orders'    => array(
                        'label' => _('Orders'),
                        'icon'  => 'shopping-cart',

                        'quantity_data' => array(
                            'object' => '_object',
                            'field'  => 'Orders'
                        ),
                    ),
                    'shipping_zone.customers' => array(
                        'label'         => _('Customers'),
                        'icon'          => 'users',
                        'quantity_data' => array(
                            'object' => '_object',
                            'field'  => 'Customers'
                        ),
                    ),
                    'shipping_zone.history'   => array(
                        'title'         => _('History/Notes'),
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

            'shipping_zone.new' => array(
                'type' => 'new_object',
                'tabs' => array(
                    'shipping_zone.new' => array(
                        'label' => _('New shipping zone')
                    ),

                )

            ),
            'charge.new'        => array(
                'type' => 'new_object',
                'tabs' => array(
                    'charge.new' => array(
                        'label' => _('New charge')
                    ),

                )

            ),

        )
    ),
    'products_server' => array(

        'parent'      => 'none',
        'parent_type' => 'none',

        'sections' => array(
            'stores'    => array(
                'type'           => 'navigation',
                'label'          => _('Stores'),
                'icon'           => 'shopping-basket',
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
                        'icon'    => 'money-bill-alt',
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
                                'label'   => '',
                                'title'   => _('Sales data info'),
                                'icon_v2' => 'fal fa-fw fa-chess-clock',
                                'class'   => 'right icon_only'
                            ),

                        )

                    ),
                )
            ),
            'products'  => array(
                'type'      => 'navigation',
                'label'     => _('Products'),
                'icon'      => 'cube',
                'reference' => 'products/all',
                'tabs'      => array(


                    'products' => array(
                        'label' => _('Products'),
                        'icon'  => 'cube'
                    ),

                )
            ),
            'store.new' => array(
                'type'  => 'new_object',
                'title' => _('New store'),
                'tabs'  => array(
                    'store.new' => array(
                        'label' => _(
                            'New store'
                        )
                    ),

                )

            ),


        )
    ),


    'production_server' => array(
        'sections' => array(

            'production.suppliers' => array(

                'type'      => 'navigation',
                'label'     => _('Suppliers'),
                'icon'      => 'hand-holding-box',
                'reference' => 'production/all',
                'tabs'      => array(
                    'production.suppliers' => array()
                )
            )

        )
    ),

    'production' => array(
        'section'     => 'production',
        'parent'      => 'account',
        'parent_type' => 'key',
        'sections'    => array(
            'dashboard' => array(
                'type'      => 'navigation',
                'label'     => _('Dashboard'),
                'title'     => _("Manufacture dashboard"),
                'icon'      => 'tachometer',
                'reference' => 'production/%d',
                'tabs'      => array(
                    'production.dashboard'     => array(
                        'label' => _('Dashboard'),
                        'icon'  => 'tachometer-alt'
                    ),
                    'production.sales.history' => array(
                        'label' => _('Sales history'),
                        'icon'  => 'th-list'
                    )
                )
            ),


            'production_parts' => array(
                'type'      => 'navigation',
                'label'     => _('Parts'),
                'icon'      => 'hand-receiving',
                'reference' => 'production/%d/parts',
                'tabs'      => array(
                    'production.production_parts' => array('label' => _('Parts'))
                )


            ),
            'production_part'  => array(
                'type'           => 'object',
                'subtabs_parent' => array(

                    'production_part.supplier.orders'     => 'production_part.purchase_orders',
                    'production_part.supplier.deliveries' => 'production_part.purchase_orders',
                ),

                'tabs' => array(


                    'production_part.details' => array(
                        'label' => _('Data'),
                        'icon'  => 'database'
                    ),


                    'bill_of_materials' => array(
                        'label' => _('Bill of materials'),
                        'icon'  => 'puzzle-piece'
                    ),

                    'bill_of_materials_edit' => array(
                        'class' => 'hide',
                        'label' => _('Edit bill of materials'),
                        'icon'  => 'puzzle-piece'
                    ),
                    'production_part.tasks'  => array(
                        'label' => _('List of tasks'),
                        'icon'  => 'tasks'
                    ),


                    'production_part.purchase_orders' => array(
                        'label'   => _('Job orders / production sheets'),
                        'icon'    => 'clipboard',
                        'subtabs' => array(

                            'production_part.supplier.orders'     => array(
                                'label' => _('Job orders'),
                                'icon'  => 'clipboard'
                            ),
                            'production_part.supplier.deliveries' => array(
                                'label' => _("Production sheets"),
                                'icon'  => 'clipboard-check'
                            ),

                        )

                    ),

                    'production_part.details' => array(
                        'label' => _('Data'),
                        'icon'  => 'database'
                    ),

                    'production_part.images'  => array(
                        'label' => '',
                        'title' => _('Images'),
                        'icon'  => 'camera-retro',
                        'class' => 'right icon_only'
                    ),
                    'production_part.history' => array(
                        'label' => '',
                        'title' => _('History/Notes'),
                        'icon'  => 'road',
                        'class' => 'right icon_only'
                    ),


                )
            ),

            /*
            'batches'        => array(
                'type'      => 'navigation',
                'label'     => _('Batches'),
                'icon'      => 'clone',
                'reference' => 'production/%d/batches',
                'tabs'      => array(
                    'batches' => array('label' => _('Batches'))
                )


            ),
*/
            'materials'        => array(
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
                        'label' => _('New task')
                    ),

                )

            ),


            'operatives' => array(
                'type'      => 'navigation',
                'label'     => _('Workers'),
                'icon'      => 'digging',
                'reference' => 'production/%d/operatives',
                'tabs'      => array(
                    'operatives' => array('label' => _('Workers'))
                )


            ),


            'production_supplier_orders'     => array(
                'type'      => 'navigation',
                'label'     => _('Job orders'),
                'icon'      => 'clipboard',
                'reference' => 'production/%d/orders',
                'tabs'      => array(
                    'production_supplier.orders' => array()
                )
            ),
            'production_supplier_deliveries' => array(
                'type'      => 'navigation',
                'label'     => _('Production sheets'),
                'icon'      => 'clipboard-check',
                'reference' => 'production/%d/deliveries',
                'tabs'      => array(
                    'production_supplier.deliveries' => array()
                )
            ),


            'order' => array(
                'type' => 'object',
                'tabs' => array(
                    'supplier.order.items'   => array(
                        'label' => _('Items'),
                        'icon'  => 'bars'
                    ),
                    'supplier.order.details' => array(
                        'label' => _('Data'),
                        'icon'  => 'database'
                    ),


                    'supplier.order.items_in_process' => array(
                        'label' => _('Items'),
                        'icon'  => 'bars'
                    ),

                    'supplier.order.all_supplier_parts' => array(
                        'label' => _("All supplier's products"),
                        'icon'  => 'th-list'
                    ),
                    /*
                                        'supplier.order.tac.editor' => array(
                                            'label' => _('Terms and conditions'),
                                            'icon'  => 'gavel',
                                            'class' => ''
                                        ),
                    */
                    'supplier.order.history'            => array(
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
                    'supplier.delivery.items'   => array(
                        'label' => _('Items'),
                        'icon'  => 'bars'
                    ),
                    'supplier.delivery.costing' => array(
                        'label' => _('Items').' ('._('Costing').')',
                        'icon'  => 'bars'
                    ),


                    'supplier.delivery.items_mismatch' => array(
                        'label' => _('Under/Over delivered items'),
                        'icon'  => 'box-open'
                    ),
                    'supplier.delivery.items_done'     => array(
                        'label' => _('Items'),
                        'icon'  => 'bars'
                    ),


                    'supplier.delivery.details' => array(
                        'label' => _('Settings'),
                        'icon'  => 'sliders-h'
                    ),

                    'supplier.delivery.history'     => array(
                        'label' => '',
                        'title' => _('History/Notes'),
                        'icon'  => 'road',
                        'class' => 'right icon_only'
                    ),
                    'supplier.delivery.attachments' => array(
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


            'settings' => array(
                'type'      => 'navigation',
                'label'     => '',
                'icon'      => 'sliders-h',
                'reference' => 'production/%d/settings',
                'class'     => 'icon_only right',
                'tabs'      => array(
                    'production.settings' => array(
                        'label' => _('General settings'),
                        'icon'  => 'sliders-h',
                        'class' => ''
                    ),


                )


            ),


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

            'production_part.new' => array(
                'type' => 'new_object',
                'tabs' => array(
                    'supplier_part.new' => array(
                        'label' => _('New part')
                    ),

                )

            ),


        )
    ),
    'suppliers'  => array(
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


            'dashboard' => array(

                'type'      => 'navigation',
                'label'     => _('Dashboard'),
                'icon'      => 'tachometer-alt',
                'reference' => 'suppliers/dashboard',
                'tabs'      => array('suppliers.dashboard' => array())


            ),

            'suppliers' => array(

                'type'      => 'navigation',
                'label'     => _('Suppliers'),
                'icon'      => 'hand-holding-box',
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
            /*

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
            */

            'supplier_parts' => array(
                'type'           => 'navigation',
                'subtabs_parent' => array(

                    'suppliers.supplier_parts.surplus'      => 'suppliers.supplier_parts',
                    'suppliers.supplier_parts.ok'           => 'suppliers.supplier_parts',
                    'suppliers.supplier_parts.low'          => 'suppliers.supplier_parts',
                    'suppliers.supplier_parts.critical'     => 'suppliers.supplier_parts',
                    'suppliers.supplier_parts.out_of_stock' => 'suppliers.supplier_parts',


                ),
                'label'          => _("Supplier's parts"),
                'title'          => _("Supplier's parts"),
                'icon'           => 'hand-receiving',
                'reference'      => 'suppliers/supplier_parts',
                'tabs'           => array(
                    'suppliers.supplier_parts' => array(
                        'label'   => _("Supplier's parts"),
                        'icon'    => 'hand-receiving',
                        'subtabs' => array(
                            'suppliers.supplier_parts.surplus' => array(
                                'label' => _('Surplus')
                            ),
                            'suppliers.supplier_parts.ok'      => array(
                                'label' => _('OK')
                            ),
                            'suppliers.supplier_parts.low'     => array(
                                'label' => _('Low')
                            ),

                            'suppliers.supplier_parts.critical'     => array(
                                'label' => _('Critical')
                            ),
                            'suppliers.supplier_parts.out_of_stock' => array(
                                'label' => _('Out of stock')
                            ),

                        )
                    ),
                    'suppliers.categories'     => array(
                        'label' => _('Categories'),
                        'icon'  => 'sitemap',

                    )
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


            'settings' => array(

                'type'      => 'navigation',
                'label'     => '',
                'title'     => _('Settings'),
                'icon'      => 'sliders-h',
                'reference' => 'suppliers/settings',
                'class'     => 'icon_only right',
                'tabs'      => array(
                    'suppliers.settings'       => array(
                        'label' => _('General settings'),
                        'icon'  => 'sliders',
                        'class' => ''
                    ),
                    'suppliers.email_template' => array(
                        'label' => _('Email template'),
                        'icon'  => 'envelope',
                        'class' => ''
                    ),


                )


            ),


            'order' => array(
                'type' => 'object',
                'tabs' => array(
                    'supplier.order.details' => array(
                        'label' => _('Data'),
                        'icon'  => 'database'
                    ),

                    'supplier.order.items'            => array(
                        'label' => _('Items'),
                        'icon'  => 'bars'
                    ),
                    'supplier.order.items_in_process' => array(
                        'label' => _('Items'),
                        'icon'  => 'bars'
                    ),

                    'supplier.order.all_supplier_parts' => array(
                        'label' => _("All supplier's products"),
                        'icon'  => 'th-list'
                    ),

                    'supplier.order.history' => array(
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
                        'label' => _('Settings'),
                        'icon'  => 'sliders-h'
                    ),

                    'supplier.delivery.items'          => array(
                        'label' => _('Items'),
                        'icon'  => 'bars'
                    ),
                    'supplier.delivery.costing'        => array(
                        'label' => _('Items').' ('._('Costing').')',
                        'icon'  => 'bars'
                    ),
                    'supplier.delivery.items_mismatch' => array(
                        'label' => _('Under/Over delivered items'),
                        'icon'  => 'box-open'
                    ),
                    'supplier.delivery.items_done'     => array(
                        'label' => _('Items'),
                        'icon'  => 'bars'
                    ),


                    'supplier.delivery.history'     => array(
                        'label' => '',
                        'title' => _('History/Notes'),
                        'icon'  => 'road',
                        'class' => 'right icon_only'
                    ),
                    'supplier.delivery.attachments' => array(
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


            'supplier_delivery.attachment.new' => array(
                'type' => 'new_object',
                'tabs' => array(
                    'supplier_delivery.attachment.new' => array(
                        'label' => _('New attachment')
                    ),

                )

            ),
            'supplier_delivery.attachment'     => array(
                'type' => 'object',
                'tabs' => array(
                    'supplier_delivery.attachment.details' => array(
                        'label' => _('Data'),
                        'icon'  => 'database'
                    ),
                    'supplier_delivery.attachment.history' => array(
                        'label' => _('History'),
                        'icon'  => 'clock'
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
                        'icon'  => 'sticky-note'
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
                'icon'      => 'hand-holding-box',
                'reference' => 'agent/%d',
                'tabs'      => array(
                    'agent.details'   => array(
                        'label' => _('Data'),
                        'icon'  => 'database',
                        'title' => _('Details')
                    ),
                    'agent.suppliers' => array(
                        'label' => _("Agent's suppliers"),
                        'icon'  => 'hand-holding-box'
                    ),

                    'agent.supplier_parts' => array(
                        'label' => _("Agent's parts"),
                        'icon'  => 'hand-receiving'
                    ),
                    'agent.orders'         => array(
                        'label' => _('Purchase orders'),
                        'icon'  => 'clipboard'
                    ),
                    'agent.deliveries'     => array(
                        'label' => _('Deliveries'),
                        'icon'  => 'truck'
                    ),
                    /*
                    'agent.agent_orders'   => array(
                        'label' => _("Agent's PO"),
                        'icon'  => 'clipboard fa-flip-horizontal'
                    ),
*/
                    'agent.history'        => array(
                        'label' => _('History/Notes'),
                        'icon'  => 'road',
                        'class' => 'right icon_only'
                    ),
                    'agent.users'          => array(
                        'label' => _('System users'),
                        'icon'  => 'terminal',
                        'class' => 'right icon_only'
                    ),

                    'agent.images' => array(
                        'title'         => _('Images'),
                        'label'         => '',
                        'quantity_data' => array(
                            'object' => '_object',
                            'field'  => 'Number Images'
                        ),
                        'icon'          => 'camera-alt',
                        'class'         => 'right icon_only'
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
                'icon'      => 'hand-holding-box',
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
                        'icon'    => 'money-bill',
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
                                'label'   => '',
                                'title'   => _('Sales data info'),
                                'icon_v2' => 'fal fa-fw fa-chess-clock',
                                'class'   => 'right icon_only'
                            ),


                        )
                    ),

                    'supplier.supplier_parts' => array(
                        'label'         => _("Supplier's parts"),
                        'quantity_data' => array(
                            'object' => '_object',
                            'field'  => 'Number Parts'
                        ),
                        'icon'          => 'hand-receiving'
                    ),
                    'supplier.feedback'       => array(
                        'label' => _("Issues"),

                        'icon' => 'poop'
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

                    'supplier.images' => array(
                        'title'         => _('Images'),
                        'label'         => '',
                        'quantity_data' => array(
                            'object' => '_object',
                            'field'  => 'Number Images'
                        ),
                        'icon'          => 'camera-alt',
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
                        'icon'  => 'clock'
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

                        )

                    ),


                    'supplier_part.images'  => array(
                        'label' => '',
                        'title' => _('Images'),
                        'icon'  => 'camera-retro',
                        'class' => 'right icon_only'
                    ),
                    'supplier_part.history' => array(
                        'label' => '',
                        'title' => _('History/Notes'),
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

                        )

                    ),

                    'supplier_part.images'  => array(
                        'label' => '',
                        'title' => _('Images'),
                        'icon'  => 'camera-retro',
                        'class' => 'right icon_only'
                    ),
                    'supplier_part.history' => array(
                        'label' => '',
                        'title' => _('History/Notes'),
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
                        'label' => _('Data'),
                        'icon'  => 'database'
                    ),

                    'supplier_part.feedback' => array(
                        'label' => _('Issues'),
                        'icon'  => 'poop'
                    ),


                    'supplier_part.purchase_orders' => array(
                        'label'   => _('Purchase orders / deliveries'),
                        'icon'    => 'clipboard',
                        'subtabs' => array(

                            'supplier_part.supplier.orders'     => array(
                                'label' => _('Purchase orders'),
                                'icon'  => 'clipboard'
                            ),
                            'supplier_part.supplier.deliveries' => array(
                                'label' => _("Supplier's deliveries"),
                                'icon'  => 'truck'
                            ),

                        )

                    ),

                    'supplier_part.images'  => array(
                        'label' => '',
                        'title' => _('Images'),
                        'icon'  => 'camera-retro',
                        'class' => 'right icon_only'
                    ),
                    'supplier_part.history' => array(
                        'label' => '',
                        'title' => _('History/Notes'),
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

                            )

                        ),
                    ),
                    'supplier_part.images'          => array(
                        'label' => '',
                        'title' => _('Images'),
                        'icon'  => 'camera-retro',
                        'class' => 'right icon_only'
                    ),
                    'supplier_part.history'         => array(
                        'label' => '',
                        'title' => _('History/Notes'),
                        'icon'  => 'road',
                        'class' => 'right icon_only'
                    ),


                )
            ),


            'supplier_part.new' => array(
                'type' => 'new_object',
                'tabs' => array(
                    'supplier_part.new' => array(
                        'label' => _('New part')
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


            'timeseries_record' => array(
                'type'     => 'object',
                'label'    => '',
                'showcase' => 'timeseries_record',

                'tabs' => array(
                    'supplier.timeseries_record.parts'    => array(
                        'label' => _('Parts'),
                        'icon'  => 'box',
                        'class' => ''
                    ),
                    'supplier.timeseries_record.families' => array(
                        'label' => _('Families'),
                        'icon'  => 'sitemap',
                        'class' => ''
                    ),


                )


            )

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
            ),

            'returns' => array(
                'type'      => 'navigation',
                'label'     => _('Returns'),
                'icon'      => 'backspace',
                'reference' => 'returns/all',
                'tabs'      => array(
                    'returns_server' => array(
                        'icon'  => 'backspace',
                        'label' => _('Returns'),

                    ),

                    'returns_group_by_warehouse' => array(
                        'label' => _('Group by warehouse'),
                        'icon'  => 'layer-group',

                    )
                )
            ),

            'return' => array(
                'type' => 'object',
                'tabs' => array(

                    'return.details' => array(
                        'label' => _('Settings'),
                        'icon'  => 'sliders-h'
                    ),

                    'return.items' => array(
                        'label' => _('Items'),
                        'icon'  => 'bars'
                    ),

                    'return.items_done' => array(
                        'label' => _('Items'),
                        'icon'  => 'bars'
                    ),


                    'return.history'     => array(
                        'label' => '',
                        'title' => _('History/Notes'),
                        'icon'  => 'road',
                        'class' => 'right icon_only'
                    ),
                    'return.attachments' => array(
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

        )
    ),

    'inventory'  => array(
        'sections' => array(

            'dashboard' => array(
                'type'      => 'navigation',
                'title'     => _('Inventory dashboard'),
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
                'icon'      => 'boxes',
                'reference' => 'inventory',
                'tabs'      => array(
                    'inventory.in_process_parts'    => array(
                        'label' => _('In process'),
                        'class' => 'discreet'
                    ),
                    'inventory.parts'               => array(
                        'label' => _('Active')
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

            'feedback'          => array(
                'type'      => 'navigation',
                'label'     => _('Issues'),
                'icon'      => 'poop',
                'reference' => 'inventory/feedback',
                'tabs'      => array(


                    'inventory.feedback'                 => array(
                        'label' => _('Issues'),
                        'icon'  => 'poop'
                    ),
                    'inventory.feedback_per_part'        => array(
                        'label' => _('Issues group by part'),
                    ),
                    'inventory.feedback_per_part_family' => array(
                        'label' => _('Issues group by family'),
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
                'type'  => 'object',
                'title' => _('Barcode'),
                'tabs'  => array(
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
                'type'  => 'object',
                'title' => _('Deleted barcode'),
                'tabs'  => array(
                    'barcode.history' => array(
                        'label' => _('History'),
                        'icon'  => 'road'
                    ),


                )
            ),
            'categories'        => array(
                'type'      => 'navigation',
                'label'     => _("Part's families"),
                'icon'      => 'sitemap',
                'reference' => 'inventory/categories',

                'tabs' => array(
                    'part_families' => array(
                        'label' => _(
                            "Part's families"
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
                'title'          => _('Category (Part)'),
                'subtabs_parent' => array(

                    'category.part.sales.plot'     => 'category.part.sales',
                    'category.part.sales.history'  => 'category.part.sales',
                    'category.part.sales.calendar' => 'category.part.sales',
                    'category.part.sales.info'     => 'category.part.sales'

                ),
                'tabs'           => array(

                    'category.details' => array(
                        'label' => _('Data'),
                        'icon'  => 'database'
                    ),

                    'category.part.sales'    => array(
                        'label'   => _('Sales'),
                        'icon'    => 'money-bill-alt',
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
                                'label'   => '',
                                'title'   => _('Sales data info'),
                                'icon_v2' => 'fal fa-fw fa-chess-clock',
                                'class'   => 'right icon_only'
                            ),

                        )

                    ),
                    'part_category.feedback' => array(
                        'label' => _('Issues'),
                        'icon'  => 'poop',
                    ),
                    'category.subjects'      => array(
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
                    'part_family.part_locations'   => array(
                        'label' => _('Parts locations')
                    ),
                    'part_family.product_families' => array(
                        'label' => _('Product families'),
                        'icon'  => 'cubes'
                    ),
                    'part_family.products'         => array(
                        'label' => _('Products'),
                        'icon'  => 'cube'
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
                'title'          => _('Part'),
                'subtabs_parent' => array(
                    'part.sales.overview'      => 'part.sales',
                    'part.sales.history'       => 'part.sales',
                    'part.sales.products'      => 'part.sales',
                    'part.stock.overview'      => 'part.stock',
                    'part.stock.transactions'  => 'part.stock',
                    'part.stock.history'       => 'part.stock',
                    'part.stock.availability'  => 'part.stock',
                    'part.supplier.orders'     => 'part.supplier_orders',
                    'part.supplier.deliveries' => 'part.supplier_orders',
                    'part.stock.history'       => 'part.stock',
                    'part.stock.transactions'  => 'part.stock',
                    'part.stock.cost'          => 'part.stock',
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
                        'icon'    => 'money-bill-alt',
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
                                'label'   => '',
                                'title'   => _('Sales data info'),
                                'icon_v2' => 'fal fa-fw fa-chess-clock',
                                'class'   => 'right icon_only'
                            ),

                        )

                    ),

                    'part.stock'    => array(
                        'label'   => _('Stock History'),
                        'icon'    => 'scanner',
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
                                'label' => _('Stock movements'),
                                'icon'  => 'exchange',
                            ),
                            'part.stock.cost'         => array(
                                'label' => _('Stock cost'),
                                'icon'  => 'fa-dollar-sign',
                            ),
                        )


                    ),
                    'part.feedback' => array(
                        'label' => _('Issues'),
                        'icon'  => 'poop',
                    ),

                    'part.supplier_orders' => array(
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
                        'icon'          => 'hand-receiving'
                    ),

                    'part.products' => array(
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
                        'icon'          => 'road',
                        'class'         => 'right icon_only'
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
                    'part.locations'   => array(
                        'title' => _('Locations'),
                        'label' => '',
                        'icon'  => 'inventory',
                        'class' => 'right icon_only'
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
                        'icon'  => 'clock'
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
                'icon'      => 'scanner',
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
    'warehouses' => array(
        'sections' => array(


            'dashboard'      => array(
                'type'      => 'navigation',
                'label'     => _('Dashboard'),
                'icon'      => 'tachometer',
                'reference' => 'warehouse/%d/dashboard',
                'tabs'      => array(
                    'warehouse.dashboard' => array('label' => _('Dashboard'))

                )
            ),
            'warehouse'      => array(

                'type'           => 'navigation',
                'label'          => _('Warehouse'),
                'title'          => _('Warehouse'),
                'icon'           => 'warehouse-alt',
                'reference'      => 'warehouse/%d',
                'sections_class' => 'icon-only',

                'tabs' => array(
                    'warehouse.details'  => array(
                        'label' => _('Settings'),
                        'title' => _('Warehouse settings'),
                        'icon'  => 'sliders-h'
                    ),
                    'warehouse.shippers' => array(
                        'label' => _('Shipping companies'),
                        'title' => _('Shipping companies'),
                        'icon'  => 'truck-loading'
                    ),

                    'warehouse.history' => array(
                        'label' => '',
                        'title' => _('History'),
                        'icon'  => 'road',
                        'class' => 'right icon_only'
                    ),
                    'warehouse.parts'   => array(
                        'label' => _('Part-Locations'),
                        'icon'  => 'pallet-alt',
                        'class' => 'right'
                    ),

                )

            ),
            'warehouse_area' => array(

                'type'      => 'object',
                'label'     => _('Warehouse area'),
                'icon'      => 'inventory',
                'reference' => '',

                'tabs' => array(
                    'warehouse_area.details' => array(
                        'label' => _('Settings'),
                        'title' => _('Warehouse area settings'),
                        'icon'  => 'sliders-h'
                    ),

                    'warehouse_area.locations' => array(
                        'label' => _('Locations'),
                        'icon'  => 'pallet'
                    ),
                    'warehouse_area.history'   => array(
                        'label' => '',
                        'title' => _('History'),
                        'icon'  => 'road',
                        'class' => 'right icon_only'
                    ),
                    'warehouse_area.parts'     => array(
                        'label' => _('Part-Locations'),
                        'icon'  => 'pallet-alt',
                        'class' => 'right'
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
            /*
                        'warehouse_areas' => array(

                            'type'      => 'navigation',
                            'label'     => _('Areas'),
                            'title'     => _('Warehouse areas (Rankings, aisles, rooms)'),
                            'icon'      => 'inventory',
                            'reference' => 'warehouse/%d/areas',
                            'tabs'      => array(




                            )


                        ),
            */

            'locations' => array(

                'type'      => 'navigation',
                'label'     => _('Locations'),
                'icon'      => 'pallet',
                'reference' => 'warehouse/%d/locations',
                'tabs'      => array(


                    'warehouse.locations' => array(
                        'label'             => _('Locations'),
                        'icon'              => 'pallet',
                        'dynamic_reference' => 'warehouse/%d/locations',

                    ),
                    'warehouse.areas'     => array(
                        'label'             => _('Warehouse areas'),
                        'title'             => _('Warehouse areas (Rankings, aisles, rooms)'),
                        'icon'              => 'inventory',
                        'dynamic_reference' => 'warehouse/%d/areas',

                    ),

                )

            ),


            'shipper.new' => array(
                'type' => 'new_object',
                'tabs' => array(
                    'shipper.new' => array(
                        'label' => _('New shipping company')
                    ),

                )

            ),


            'warehouse.new'      => array(
                'type' => 'new_object',
                'tabs' => array(
                    'warehouse.new' => array(
                        'label' => _('New warehouse')
                    ),

                )

            ),
            'warehouse_area.new' => array(
                'type' => 'new_object',
                'tabs' => array(
                    'warehouse.new' => array(
                        'label' => _('New warehouse area')
                    ),

                )

            ),
            'location'           => array(

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
                        'icon'  => 'box'
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


            'leakages' => array(
                'type' => 'object',
                'tabs' => array(
                    'stock_leakages'       => array(
                        'label' => _('Leakages')
                    ),
                    'stock_leakages_setup' => array(
                        'label' => '',
                        'title' => _('Setup'),
                        'icon'  => 'sliders',
                        'class' => 'right icon_only'
                    ),

                )

            ),

            'timeseries_record' => array(
                'type'     => 'object',
                'label'    => '',
                'showcase' => 'timeseries_record',

                'tabs' => array(
                    'warehouse.leakages.transactions' => array(
                        'label' => _('Transactions'),
                        'icon'  => 'fa-arrow-circle-right',
                        'class' => ''
                    )


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
            'returns'  => array(
                'type'      => 'navigation',
                'label'     => _('Returns'),
                'icon'      => 'backspace',
                'reference' => 'warehouse/%d/returns',
                'tabs'      => array(
                    'warehouse.returns' => array(
                        'icon'  => 'backspace',
                        'label' => _('Returns'),

                    ),

                )
            ),
            'feedback' => array(
                'type'      => 'navigation',
                'label'     => _('Issues'),
                'icon'      => 'poop',
                'reference' => 'warehouse/%d/feedback',
                'tabs'      => array(
                    'warehouse.feedback' => array(
                        'icon'  => 'poop',
                        'label' => _('Issues'),

                    ),

                )
            ),

            'return' => array(
                'type' => 'object',
                'tabs' => array(

                    'return.details' => array(
                        'label' => _('Settings'),
                        'icon'  => 'sliders-h'
                    ),

                    'return.items' => array(
                        'label' => _('Items'),
                        'icon'  => 'bars'
                    ),

                    'return.items_done' => array(
                        'label' => _('Items'),
                        'icon'  => 'bars'
                    ),


                    'return.history'     => array(
                        'label' => '',
                        'title' => _('History/Notes'),
                        'icon'  => 'road',
                        'class' => 'right icon_only'
                    ),
                    'return.attachments' => array(
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

        )
    ),


    'reports' => array(

        'sections' => array(
            'reports' => array(
                'type'      => 'navigation',
                'label'     => _('Activity/Performance'),
                'title'     => _("Activity/Performance"),
                'icon'      => 'thumbs-up',
                'reference' => 'users',
                'tabs'      => array(
                    'reports' => array(),

                )

            ),

            'performance' => array(
                'type'      => 'navigation',
                'label'     => _('Activity/Performance'),
                'title'     => _("Activity/Performance"),
                'icon'      => 'thumbs-up',
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
                        'label'     => _('Top Customers'),
                        'title'     => _('Top Customers'),
                        'reference' => 'locations/%d/parts'
                    ),

                )

            ),
            'sales'       => array(
                'type'      => 'navigation',
                'label'     => _('Sales'),
                'title'     => _("Sales"),
                'icon'      => 'money-bill-alt',
                'reference' => 'users',
                'tabs'      => array(
                    'sales'                  => array(
                        'label' => _('Sales by store'),

                    ),
                    'sales_invoice_category' => array(
                        'label' => _("Sales by invoices' categories"),

                    ),


                )

            ),

            'ec_sales_list' => array(
                'type' => '',
                'tabs' => array(
                    'ec_sales_list' => array(),

                )

            ),

            'pickers'               => array(
                'type' => '',
                'tabs' => array(
                    'pickers' => array(),

                )

            ),
            'packers'               => array(
                'type' => '',
                'tabs' => array(
                    'packers' => array(),

                )

            ),
            'sales_representatives' => array(
                'type' => '',
                'tabs' => array(
                    'sales_representatives' => array(),

                )

            ),
            'prospect_agents'       => array(
                'type' => '',
                'tabs' => array(
                    'prospect_agents' => array(),

                )

            ),


            'stock_given_free' => array(
                'type' => '',
                'tabs' => array(
                    'stock_given_free' => array(),

                )

            ),
            'lost_stock'       => array(
                'type' => '',
                'tabs' => array(
                    'lost_stock' => array(),

                )

            ),


            'report_orders' => array(
                'type' => '',
                'tabs' => array(
                    'report_orders' => array(),

                )

            ),

            'report_orders_components' => array(
                'type' => '',
                'tabs' => array(
                    'report_orders_components' => array(),

                )

            ),

            'report_delivery_notes' => array(
                'type' => '',
                'tabs' => array(
                    'report_delivery_notes' => array(),

                )

            ),

            'intrastat_imports'    => array(
                'type' => '',
                'tabs' => array(
                    'intrastat_imports' => array(),

                )

            ),
            'intrastat'            => array(
                'type' => '',
                'tabs' => array(
                    'intrastat' => array(),

                )

            ),
            'intrastat_orders'     => array(
                'type' => '',
                'tabs' => array(
                    'intrastat_orders' => array(),

                )

            ),
            'intrastat_products'   => array(
                'type' => '',
                'tabs' => array(
                    'intrastat_products' => array(),

                )

            ),
            'intrastat_deliveries' => array(
                'type' => '',
                'tabs' => array(
                    'intrastat_deliveries' => array(),
                )
            ),
            'intrastat_parts'      => array(
                'type' => '',
                'tabs' => array(
                    'intrastat_parts' => array(),

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

            ),

            'sales_representative' => array(
                'type' => 'object',


                'tabs' => array(

                    'sales_representative.customers' => array(
                        'label' => _('Customers')
                    ),
                    'sales_representative.invoices'  => array(
                        'label' => _('Invoices')
                    ),

                    'sales_representative.invoices_group_by_customer' => array(
                        'label' => '',
                        'title' => _('Invoices grouped by customers'),
                        'icon'  => 'users-class',
                        'class' => 'right icon_only'
                    ),

                    /*
                    'sales_representative.prospects' => array(
                        'label' => _('Prospects')
                    ),
                    */


                )

            ),

            'prospect_agent' => array(
                'type' => 'object',


                'tabs' => array(
                    'prospect_agent.prospects'   => array(
                        'label' => _('Prospects')
                    ),
                    'prospect_agent.sent_emails' => array(
                        'label' => _('Sent emails')
                    ),
                    //'prospect_agent.calls'  => array(
                    //    'label' => _('Calls')
                    //),


                )

            ),

            'prospect_agent_email_tracking' => array(
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


        )
    ),
    'hr'      => array(

        'sections' => array(
            'employees' => array(
                'type'      => 'navigation',
                'label'     => _('Employees'),
                'title'     => _("Employees"),
                'icon'      => 'hand-rock',
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
                        'icon'  => 'trash',
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
                'icon'      => 'hand-spock',
                'reference' => 'hr/contractors',
                'tabs'      => array(
                    'contractors'         => array('label' => _('Contractors')),
                    'deleted.contractors' => array(
                        'label' => _(
                            'Deleted contractors'
                        ),
                        'icon'  => 'trash',
                        'class' => 'right icon_only'
                    ),

                )


            ),

            /*
              'salesmen'      => array(
                  'type'      => 'navigation',
                  'label'     => _('Account managers'),
                  'icon'      => 'handshake',
                  'reference' => 'hr/salesmen',
                  'tabs'      => array(
                      'salesmen'         => array('label' => _('Account managers')),


                  )


              ),

    */
            /*
            'overtimes'        => array(
                'type'      => 'navigation',
                'label'     => _('Overtimes'),
                'icon'      => 'clock',
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
             */
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
                        'label'         => _(
                            'Attachments'
                        ),
                        'icon'          => 'paperclip',
                        'class'         => 'right icon_only',
                        'quantity_data' => array(
                            'object' => '_object',
                            'field'  => 'Number Attachments'
                        ),
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
                        'icon'  => 'sticky-note'
                    ),


                )

            ),

            'employee.new' => array(
                'type' => 'new_object',
                'tabs' => array(
                    'employee.new' => array(
                        'label' => _('new employee')
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
                        'icon'  => 'clock'
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
                        'icon'  => 'sticky-note'
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
                        'icon'  => 'sticky-note'
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

            'timesheet'  => array(
                'type' => 'object',
                'tabs' => array(
                    'timesheet.records' => array(
                        'label' => _('Clockings')
                    ),

                )

            ),
            'timesheets' => array(
                'type'      => 'navigation',
                'icon'      => 'calendar',
                'label'     => _('Calendar'),
                'reference' => 'timesheets/day/'.date('Ymd'),
                'tabs'      => array(
                    'timesheets.months'     => array(
                        'label' => _('Months')
                    ),
                    'timesheets.weeks'      => array(
                        'label' => _('Weeks')
                    ),
                    'timesheets.days'       => array(
                        'label' => _('Days')
                    ),
                    'timesheets.employees'  => array(
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


            'clocking_machines'    => array(
                'type'           => 'navigation',
                'icon'           => 'chess-clock',
                'label'          => _('Clocking-in Machines'),
                'reference'      => 'clocking_machines',
                'subtabs_parent' => array(
                    'nfc_tags'         => 'clocking_machines.tags',
                    'pending_nfc_tags' => 'clocking_machines.tags',

                ),
                'tabs'           => array(
                    'clocking_machines'      => array(
                        'icon'  => 'chess-clock',
                        'label' => _('Clocking-in Machines')
                    ),
                    'clocking_machines.tags' => array(
                        'icon'    => 'id-card-alt',
                        'label'   => _('NFC Tags'),
                        'subtabs' => array(
                            'nfc_tags'         => array(
                                'icon'  => 'id-card-alt',
                                'label' => _('Registered nfc-tags')
                            ),
                            'pending_nfc_tags' => array(
                                'icon'  => 'head-side-medical',
                                'label' => _('Pending nfc-tags')
                            )
                        )
                    ),


                )

            ),
            'clocking_machine.new' => array(
                'type' => 'new_object',
                'tabs' => array(
                    'clocking_machine.new' => array(
                        'label' => _('new clocking-in machine')
                    ),

                )

            ),
            'clocking_machine'     => array(
                'type' => 'object',
                'tabs' => array(
                    'clocking_machine.details' => array(
                        'icon'  => 'sliders-h',
                        'label' => _('Settings')
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
                'class'     => 'icon_only right',
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


            'sales_representative' => array(
                'type' => 'object',


                'sales_representative' => array(

                    'sales_representative.customers' => array(
                        'label' => _('Customers')
                    ),
                    'sales_representative.invoices'  => array(
                        'label' => _('Invoices')
                    ),
                    'sales_representative.prospects' => array(
                        'label' => _('Prospects')
                    ),


                )

            ),


        )
    ),
    'profile' => array(


        'sections' => array(
            'profile' => array(
                'type'      => 'object',
                'label'     => '',
                'title'     => '',
                'icon'      => '',
                'reference' => '',
                'tabs'      => array(
                    'profile.details' => array(
                        'label' => _('Settings'),
                        'icon'  => 'sliders-h'
                    ),

                    'user.login_history' => array(
                        'label' => _('Login history'),
                        'icon'  => 'sign-in'
                    ),

                    'profile.api_keys'      => array(
                        'label' => _('API keys'),
                        'icon'  => 'key'
                    ),
                    'profile.history'       => array(
                        'label' => '',
                        'title' => _('History/Notes'),
                        'icon'  => 'road',
                        'class' => 'right icon_only'
                    ),
                    'user.deleted_api_keys' => array(
                        'icon'  => 'ban',
                        'label' => _('Deleted API keys'),
                        'title' => _('Deleted API keys'),
                        'class' => 'right icon_only'
                    ),


                )
            ),

            'profile_admin' => array(
                'type'      => 'object',
                'label'     => '',
                'title'     => '',
                'icon'      => '',
                'reference' => '',
                'tabs'      => array(
                    'profile.details' => array(
                        'label' => _('Settings'),
                        'icon'  => 'sliders-h'
                    ),

                    'user.login_history' => array(
                        'label' => _(
                            'Login history'
                        ),
                        'icon'  => 'sign-in'
                    ),


                    'profile.history' => array(
                        'label' => '',
                        'title' => _('History/Notes'),
                        'icon'  => 'road',
                        'class' => 'right icon_only'
                    ),


                )
            ),

            'profile.api_key.new'     => array(
                'type' => 'new_object',
                'tabs' => array(
                    'user.api_key.new' => array(
                        'label' => _('New API')
                    ),

                )
            ),
            'profile.api_key'         => array(
                'type' => 'object',
                'tabs' => array(
                    'user.api_key.details'  => array(
                        'label' => _('Data'),
                        'icon'  => 'database'
                    ),
                    'user.api_key.requests' => array(
                        'label' => _('Requests'),
                        'icon'  => 'arrow-circle-right'
                    ),
                    'user.api_key.history'  => array(
                        'label' => _('History/Notes'),
                        'icon'  => 'road',
                        'class' => 'right icon_only'
                    ),

                )
            ),
            'profile.deleted_api_key' => array(
                'type'     => 'object',
                'showcase' => 'deleted_api_key',
                'tabs'     => array(

                    'api_key.history' => array(
                        'label' => _('History/Notes'),
                        'icon'  => 'road',
                        'class' => 'right icon_only'
                    ),


                )
            ),

        )

    ),


    'users' => array(


        'sections' => array(


            'users' => array(
                'type'      => 'navigation',
                'label'     => _('Users').' ('._('All').')',
                'icon'      => 'users-class',
                'reference' => 'users',

                'tabs' => array(
                    'users'             => array(
                        'label' => _('Users')
                    ),
                    'users_by_category' => array(
                        'label' => _('Users categories')
                    ),


                    'deleted.users' => array(
                        'label' => _('Deleted users'),
                        'class' => 'right'
                    ),
                )
            ),
            /*
            'groups' => array(
                'type'  => 'navigation',
                'label' => _('Groups'),
                'icon'  => 'ball-pile',
                'reference' => 'users/groups',
                'tabs'  => array(
                    'users.groups' => array(
                        'label' => _('groups')
                    ),
                )
            ),
    */
            'staff' => array(
                'type'      => 'navigation',
                'label'     => _('Employees'),
                'icon'      => 'user-headset',
                'reference' => 'users/staff',

                'tabs' => array(
                    'users.staff' => array(
                        'label' => _('Users')
                    ),

                    'users.staff.login_history' => array(
                        'label' => _('Login History')
                    ),
                    'deleted.staff.users'       => array(
                        'label' => _('Deleted users'),
                        'class' => 'right'
                    ),
                )
            ),


            'contractors' => array(
                'type'      => 'navigation',
                'label'     => _('Contractors'),
                'icon'      => 'user-hard-hat',
                'reference' => 'users/contractors',

                'tabs' => array(
                    'users.contractors' => array(
                        'label' => _('Users')
                    ),


                    'users.contractors.login_history' => array(
                        'label' => _('Login History')
                    ),
                    'deleted.contractors.users'       => array(
                        'label' => _('Deleted users'),
                        'class' => 'right'
                    ),
                )
            ),


            'suppliers' => array(
                'type'      => 'navigation',
                'label'     => _('Suppliers'),
                'reference' => 'users/suppliers',

                'icon' => 'hand-holding-box',
                'tabs' => array(
                    'users.suppliers' => array(
                        'label' => _(
                            'Suppliers'
                        )
                    ),
                )
            ),

            'agents' => array(
                'type'      => 'navigation',
                'label'     => _('Agents'),
                'icon'      => 'user-secret',
                'reference' => 'users/agents',

                'tabs' => array(
                    'users.agents' => array(
                        'label' => _(
                            'Agents'
                        )
                    ),
                )
            ),
            /*
                        'others'      => array(
                            'type'  => 'navigation',
                            'label' => _('Other'),
                            'icon'  => 'users-crown',
                            'reference' => 'users/others',

                            'tabs'  => array(
                                'root.user' => array(
                                    'label' => _('Root user')
                                ),
                                'warehouse.user' => array(
                                    'label' => _('Warehouse user')
                                ),
                            )
                        ),

            */

            'user' => array(
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
                        'title' => _('Login history'),
                        'icon'  => 'sign-in'
                    ),
                    'user.api_keys'      => array(
                        'label' => _('API keys'),
                        'icon'  => 'key'
                    ),


                    'user.history'          => array(
                        'label' => '',
                        'title' => _('History/Notes'),
                        'icon'  => 'road',
                        'class' => 'right icon_only'
                    ),
                    'user.deleted_api_keys' => array(
                        'icon'  => 'ban',
                        'label' => _('Deleted API keys'),
                        'title' => _('Deleted API keys'),
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
                'type' => 'object',
                'tabs' => array(
                    'user.api_key.details'  => array(
                        'label' => _('Data'),
                        'icon'  => 'database'
                    ),
                    'user.api_key.requests' => array(
                        'label' => _('Requests'),
                        'icon'  => 'arrow-circle-right'
                    ),
                    'user.api_key.history'  => array(
                        'label' => _('History/Notes'),
                        'icon'  => 'road',
                        'class' => 'right icon_only'
                    ),

                )
            ),
            'deleted_api_key'  => array(
                'type'     => 'object',
                'showcase' => 'deleted_api_key',
                'tabs'     => array(

                    'api_key.history' => array(
                        'label' => _('History/Notes'),
                        'icon'  => 'road',
                        'class' => 'right icon_only'
                    ),


                )
            ),


        )

    ),

    'account' => array(


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
                    'timeseries_types' => array(
                        'icon'  => 'layer-group',
                        'label' => _('Timeseries type')
                    ),
                    'timeseries'       => array(
                        'icon'  => 'chart-line',
                        'label' => _('Timeseries')
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


        )

    ),
    'utils'   => array(
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
                'icon'  => 'file',
                'id'    => 'fire',

                'tabs' => array(
                    'fire' => array(),
                )
            ),

        )
    ),
    'help'    => array(
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
                    'agent.profile' => array(
                        'label' => _('Settings'),
                        'icon'  => 'sliders-h'
                    ),
                    'agent.details' => array(
                        'label' => _('Agent details'),
                        'icon'  => 'database',
                        'title' => _('Agent details'),
                    ),

                    'agent.details' => array(
                        'label' => _('Agent details'),
                        'icon'  => 'database',
                        'title' => _('Agent details'),
                    ),


                    'agent.history' => array(
                        'label' => _('History/Notes'),
                        'icon'  => 'road',
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
                'icon'      => 'hand-holding-box',
                'reference' => 'suppliers',
                'tabs'      => array('agent.suppliers' => array())


            ),


            'order_to_delete' => array(
                'type' => 'object',
                'tabs' => array(
                    'supplier.order.items'   => array(
                        'label' => _(
                            'Items'
                        ),
                        'icon'  => 'bars'
                    ),
                    'supplier.order.details' => array(
                        'label' => _(
                            'Data'
                        ),
                        'icon'  => 'database'
                    ),


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
                        'label' => _('History/Notes'),
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
                'icon'      => 'hand-holding-box',
                'reference' => 'supplier/%d',
                'tabs'      => array(
                    'supplier.details'        => array(
                        'label' => _('Data'),
                        'icon'  => 'database',
                        'title' => _('Details')
                    ),
                    'supplier.supplier_parts' => array(
                        'label' => _("Supplier's Parts"),
                        'icon'  => 'hand-receiving'
                    ),

                    'supplier.orders' => array(
                        'label' => _('Purchase orders'),
                        'icon'  => 'clipboard'
                    ),

                    'supplier.history' => array(
                        'label' => _('History/Notes'),
                        'icon'  => 'road',
                        'class' => 'right icon_only'
                    ),

                    'supplier.attachments' => array(
                        'label'         => _('Attachments'),
                        'icon'          => 'paperclip',
                        'class'         => 'right icon_only',
                        'quantity_data' => array(
                            'object' => '_object',
                            'field'  => 'Number Attachments'
                        ),
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
                        'icon'  => 'clock'
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
                                'label' => _('Purchase Orders')
                            ),
                            'supplier_part.purchase_orders.delivery_notes'  => array(
                                'label' => _('Delivery Notes')
                            ),
                            'supplier_part.purchase_orders.invoices'        => array(
                                'label' => _('Invoices')
                            ),

                        )

                    ),

                    'supplier_part.images'  => array(
                        'label' => '',
                        'title' => _('Images'),
                        'icon'  => 'camera-retro',
                        'class' => 'right icon_only'
                    ),
                    'supplier_part.history' => array(
                        'label' => '',
                        'title' => _('History/Notes'),
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

                    'agent_supplier_part.details' => array(
                        'label' => ('Properties'),
                        'title' => _('Product properties'),
                        'icon'  => 'sliders-h',
                    ),

                    'supplier_part.images'  => array(
                        'label' => '',
                        'title' => _('Images'),
                        'icon'  => 'camera-retro',
                        'class' => 'right icon_only'
                    ),
                    'supplier_part.history' => array(
                        'label' => '',
                        'title' => _('History/Notes'),
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
                        'label' => '',
                        'title' => _('Images'),
                        'icon'  => 'camera-retro',
                        'class' => 'right icon_only'
                    ),
                    'supplier_part.history' => array(
                        'label' => '',
                        'title' => _('History/Notes'),
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

                    /*
                    'client_order.details' => array(
                        'label' => _('Data'),
                        'icon'  => 'database'
                    ),
                    */
                    'client_order.items' => array(
                        'label' => _(
                            'Items'
                        ),
                        'icon'  => 'bars'
                    ),

                    'client_order.suppliers' => array(
                        'label' => _("Supplier's individual orders"),
                        'icon'  => 'layer-group'
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

            'agent_supplier_order' => array(
                'type' => 'object',
                'tabs' => array(

                    /*
                    'client_order.details' => array(
                        'label' => _('Data'),
                        'icon'  => 'database'
                    ),
                    */
                    'agent_supplier_order.items' => array(
                        'label' => _(
                            'Items'
                        ),
                        'icon'  => 'bars'
                    ),


                    'agent_supplier_order.history' => array(
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
    ),


    'agent_parts' => array(
        'sections' => array(
            'parts' => array(
                'type'      => 'navigation',
                'label'     => _("Parts"),
                'icon'      => 'hand-receiving',
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
                        'label' => '',
                        'title' => _('Images'),
                        'icon'  => 'camera-retro',
                        'class' => 'right icon_only'
                    ),
                    'supplier_part.history' => array(
                        'label' => '',
                        'title' => _('History/Notes'),
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