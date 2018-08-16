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
        'webpage.preview'           => 'product.webpage',
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

                'webpage.preview'         => array(
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
                    'label' => _('Info'),
                    'icon'  => 'info',
                    'class' => 'right icon_only'
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
                'icon'      => 'user-friends',
                'reference' => 'prospects/%d',
                'tabs'      => array(
/*
                    'prospects.dashboard' => array(
                        'label' => _('Dashboard')
                    ),
*/
                    'prospects'           => array(
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

            /*
            'categories' => array(
                'type'      => 'navigation',
                'label'     => _('Categories'),
                'icon'      => 'sitemap',
                'reference' => 'customers/%d/categories',
                'tabs'      => array(
                    'customers.categories' => array()
                ),

            ),
            */


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


            'email_campaigns' => array(
                'type'      => 'navigation',
                'label'     => _('Email Comms.'),
                'icon'      => 'paper-plane',
                'reference' => 'customers/%s/email_campaigns',


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
                    'email_template_types' => array(
                        'label' => _('Operations'),
                        'icon'  => 'handshake-alt',

                        /*
                          'subtabs' => array(

                              'sent_emails.welcome' => array(
                                  'label' => _('Welcome'),
                                  'icon'  => 'door-open'
                              ),
                              'sent_emails.order_notification' => array(
                                  'label' => _('Order notification'),
                                  'icon'  => 'shopping-cart'
                              ),
                              'sent_emails.dispatched_order' => array(
                                  'label' => _('Dispatched'),
                                  'icon'  => 'truck'
                              ),
                              'send_email.reminders'   => array(
                                  'label' => _('Deal reminders'),
                                  'icon'  => 'bell'
                              ),
                              'send_email.back_in_stock'   => array(
                                  'label' => _('Back in stock'),
                                  'icon'  => 'dolly'
                              ),
    )
  */

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
                        'label' => _('Next recipients'),
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


            'email_campaign' => array(
                'type' => 'object',
                'tabs' => array(
                    'email_campaign.details'       => array(
                        'label' => _('Data'),
                        'icon'  => 'database',
                        'title' => _('Details')
                    ),
                    'email_campaign.set_mail_list' => array(
                        'label' => _('Set recipients'),
                        'icon'  => 'users',
                    ),

                    'email_campaign.mail_list' => array(
                        'label' => _('Recipients'),
                        'icon'  => 'users',
                    ),

                    'email_campaign.workshop' => array(
                        'label' => _('Workshop'),
                        'icon'  => 'wrench'
                    ),

                    'email_campaign.published_email' => array(
                        'label' => _('Email'),
                        'icon'  => 'envelope'
                    ),


                    'email_campaign.sent_emails' => array(
                        'label' => _('Sent emails'),
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

            'mailshot.new' => array(
                'type' => 'new_object',
                'tabs' => array(
                    'mailshot.new' => array(
                        'label' => 'new mailshot'
                    ),

                )

            ),


            'newsletter' => array(
                'type' => 'object',
                'tabs' => array(
                    'email_campaign.details'   => array(
                        'label' => _('Data'),
                        'icon'  => 'database',
                        'title' => _('Details')
                    ),
                    'email_campaign.mail_list' => array(
                        'label' => _('Recipients'),
                        'icon'  => 'users',
                    ),

                    'email_campaign.email_template'   => array(
                        'label' => _('Email'),
                        'icon'  => 'envelope'
                    ),
                    'email_campaign.email_blueprints' => array(
                        'label'   => _('Email HTML templates'),
                        'icon_v2' => 'fab fa-html5'
                    ),

                    'email_campaign.sent_emails'     => array(
                        'label' => _('Sent emails'),
                        'icon'  => 'paper-plane'
                    ),
                    'email_campaign.published_email' => array(
                        'label' => _('Email'),
                        'icon'  => 'envelope'
                    ),
                    'email_campaign.history'         => array(
                        'title' => _('History'),
                        'label' => '',
                        'icon'  => 'road',
                        'class' => 'right icon_only'
                    ),

                )
            ),


            'list' => array(
                'type' => 'object',
                'tabs' => array(
                    'list.details'   => array(
                        'label' => _('Data'),
                        'icon'  => 'database',
                        'title' => _('Details')
                    ),
                    'customers.list' => array(
                        'label' => _('Customers'),
                        'icon'  => 'users',

                    ),
                    'list.history'   => array(
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
                    'customer.marketing.overview'   => 'customer.marketing',
                    'customer.marketing.families'   => 'customer.marketing',
                    'customer.marketing.products'   => 'customer.marketing',
                    'customer.marketing.favourites' => 'customer.marketing',
                    'customer.marketing.search'     => 'customer.marketing',

                    'customer.sales.plot'      => 'customer.sales',
                    'customer.sales.history'   => 'customer.sales',
                    'customer.sales.calendar'  => 'customer.sales',
                    'customer.sales.dashboard' => 'customer.sales',
                    'customer.sales.info'      => 'customer.sales',

                ),
                'tabs'           => array(
                    'customer.details' => array(
                        'label' => _('Data'),
                        'icon'  => 'database'
                    ),
                    'customer.sales'   => array(
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
                                'label' => '',
                                'title' => _('Info'),
                                'icon'  => 'info',
                                'class' => 'right icon_only'
                            ),


                        )
                    ),

                    'customer.insights'    => array(
                        'label' => _('Insights'),
                        'icon'  => 'graduation-cap'
                    ),
                    'customer.sent_emails' => array(
                        'label' => _('Sent emails'),
                        'icon'  => 'paper-plane'
                    ),
                    'customer.history'     => array(
                        'label' => _('History, notes'),
                        'icon'  => 'sticky-note'
                    ),
                    'customer.orders'      => array(
                        'label' => _('Orders'),
                        'icon'  => 'shopping-cart'

                    ),
                    'customer.invoices'    => array('label' => _('Invoices')),
                    'customer.marketing'   => array(
                        'label'   => _('Interests'),
                        'title'   => _("Customer's interests"),
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
                    'customer.discounts'   => array(
                        'label' => _('Discounts'),
                        'icon'  => 'tags'
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


        )
    ),
    'customers_server' => array(

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
    'orders'           => array(
        'section'     => 'orders',
        'parent'      => 'store',
        'parent_type' => 'key',
        'sections'    => array(


            'dashboard' => array(
                'type'      => 'navigation',
                'label'     => _('Control panel'),
                'icon'      => 'angle-double-right',
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


            'invoices' => array(
                'type'      => 'navigation',
                'label'     => _('Invoices'),
                'icon'      => 'file-alt',
                'reference' => 'invoices/%d',
                'tabs'      => array(
                    'invoices' => array()
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
                        'icon'  => 'file-alt'
                    ),
                    'order.payments'       => array(
                        'label' => _('Payments'),
                        'icon'  => 'fa-dollar-sign'
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
                        'icon'  => 'file-alt'
                    ),
                )

            ),
            'invoice'       => array(
                'type' => 'object',
                'tabs' => array(


                    'invoice.items'    => array(
                        'label' => _(
                            'Items'
                        ),
                        'icon'  => 'bars'
                    ),
                    'invoice.details'  => array(
                        'label' => _(
                            'Data'
                        ),
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
                    /*,
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
                    */
                )

            ),


            'refund'          => array(
                'type' => 'object',
                'tabs' => array(


                    'refund.items'   => array(
                        'label' => _(
                            'Items'
                        ),
                        'icon'  => 'bars'
                    ),
                    'refund.details' => array(
                        'label' => _(
                            'Data'
                        ),
                        'icon'  => 'database'
                    ),
                    'refund.history' => array(
                        'label' => _(
                            'History'
                        ),
                        'icon'  => 'road'
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


                    'refund.new.items' => array(
                        'label' => _(
                            'Items'
                        ),
                        'icon'  => 'bars'
                    )


                )

            ),

            'payment'        => array(
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
            'email_campaign' => array(
                'type' => 'object',
                'tabs' => array(
                    'email_campaign.details'   => array(
                        'label' => _('Data'),
                        'icon'  => 'database',
                        'title' => _('Details')
                    ),
                    'email_campaign.mail_list' => array(
                        'label' => _('Recipients'),
                        'icon'  => 'users',
                    ),

                    'email_campaign.workshop' => array(
                        'label' => _('Workshop'),
                        'icon'  => 'wrench'
                    ),

                    'email_campaign.published_email' => array(
                        'label' => _('Email'),
                        'icon'  => 'envelope'
                    ),


                    'email_campaign.sent_emails' => array(
                        'label' => _('Sent emails'),
                        'icon'  => 'paper-plane'
                    ),

                    'email_campaign.history' => array(
                        'title' => _('History'),
                        'label' => '',
                        'icon'  => 'road',
                        'class' => 'right icon_only'
                    ),

                )
            )

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
                'icon'      => 'angle-double-right',
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
            'invoices'  => array(
                'type'      => 'navigation',
                'label'     => _('Invoices'),
                'icon'      => 'file-alt',
                'reference' => 'invoices/all',
                'tabs'      => array(
                    'invoices_server' => array()
                )

            ),


            'categories' => array(
                'type'      => 'navigation',
                'label'     => _('Invoices categories'),
                'icon'      => 'sitemap',
                'reference' => 'invoices/all/categories',
                'tabs'      => array(
                    'invoices.categories' => array()
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

                    'category.subjects'   => array('label' => _('Invoices')),
                    'category.categories' => array(
                        'label' => _('Categories')
                    ),
                    'category.history'    => array(
                        'title' => _('History'),
                        'label' => '',
                        'icon'  => 'road',
                        'class' => 'right icon_only'
                    ),

                )

            ),


            'email_campaign' => array(
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
            )

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
                'icon'      => 'file-alt',
                'reference' => 'invoices/%d',
                'tabs'      => array(
                    'invoices' => array(
                        'label' => _(
                            'Invoices'
                        ),
                        'icon'  => 'file-alt'
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
                        'icon'  => 'file-alt'
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
                        'icon'  => 'file-alt'
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
                        'icon'  => 'file-alt'
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
                        'icon'  => 'file-alt'
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

            'group_by_store' => array(
                'type'      => 'navigation',
                'label'     => _('Group by store'),
                'icon'      => 'compress',
                'reference' => 'delivery_notes/all/by_store',
                'tabs'      => array(
                    'delivery_notes_group_by_store' => array()
                )

            ),


            'delivery_notes' => array(
                'type'      => 'navigation',
                'icon'      => 'truck',
                'label'     => _('Delivery notes').' ('._('All stores').')',
                'reference' => 'delivery_notes/all',
                'tabs'      => array(
                    'delivery_notes_server' => array()
                )

            ),


        )

    ),


    'payments_server' => array(
        'section'     => 'invoices',
        'parent'      => 'store',
        'parent_type' => 'key',
        'sections'    => array(


            'payments_by_store' => array(
                'type'      => 'navigation',
                'label'     => _('Group by store'),
                'icon'      => 'compress',
                'reference' => 'payments/by_store',
                'tabs'      => array(
                    'payments_group_by_store' => array()
                )

            ),

            'payment_service_providers' => array(
                'type'      => 'navigation',
                'label'     => _('Payment Service Providers'),
                'icon'      => 'university',
                'reference' => 'payment_service_providers',
                'tabs'      => array(
                    'payment_service_providers' => array(
                        'label' => _('Payment Service Providers'),
                        'icon'  => 'university',

                    ),
                )
            ),

            'payment_accounts' => array(
                'type'      => 'navigation',
                'label'     => _("Payment accounts"),
                'icon'      => 'money-check-alt',
                'reference' => 'payment_accounts/%s',
                'tabs'      => array(
                    'payment_accounts' => array(),
                )
            ),


            'credits' => array(
                'type'      => 'navigation',
                'label'     => _('Credit vault'),
                'html_icon' => '<i class="fa fa-piggy-bank"></i>',
                'reference' => 'credits/all',
                'tabs'      => array(
                    'account.credits' => array(
                        'label' => _('Credits'),
                        'icon'  => 'university',

                    ),
                )
            ),

            'payments' => array(
                'type'      => 'navigation',
                'label'     => _('Payments'),
                'icon'      => 'credit-card',
                'reference' => 'payments/all',
                'tabs'      => array(
                    'account.payments' => array()
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
                        'icon'  => 'sticky-note'
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
                        'label'         => _('Transactions'),
                        'title'         => _('Payments transactions'),
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
                    ),
                    'payment_account.websites' => array(
                        'label'         => _('Websites'),
                        'quantity_data' => array(
                            'object' => '_object',
                            'field'  => 'Number Websites'
                        ),
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
                        'icon'  => 'sticky-note'
                    ),

                )
            )


        )
    ),

    'payments' => array(
        'section'     => 'invoices',
        'parent'      => 'store',
        'parent_type' => 'key',
        'sections'    => array(


            'credits' => array(
                'type'      => 'navigation',
                'label'     => _('Credit vault'),
                'icon'      => 'university',
                'reference' => 'credits/all',
                'tabs'      => array(
                    'credits' => array(
                        'label' => _('Credits'),
                        'icon'  => 'university',

                    ),
                )
            ),


            'payment_accounts' => array(
                'type'      => 'navigation',
                'label'     => _("Payment accounts"),
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
                        'icon'  => 'sticky-note'
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
                        'title'         => _('History, notes'),
                        'label'         => '',
                        'quantity_data' => array(
                            'object' => '_object',
                            'field'  => 'Number History records'
                        ),

                        'icon'  => 'road',
                        'class' => 'right icon_only'
                    ),
                    'payment_account.payments' => array(
                        'label'         => _('Transactions'),
                        'title'         => _('Payments transactions'),
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
                    ),
                    'payment_account.websites' => array(
                        'label'         => _('Websites'),
                        'quantity_data' => array(
                            'object' => '_object',
                            'field'  => 'Number Websites'
                        ),
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
                        'website.search.queries'       => 'website.search'
    ,
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
                                    'icon'    => 'heart',
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
                                    'icon'    => 'hand-paper',
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
                    'icon'      => 'files',
                    'reference' => 'webpages/%d',

                    'tabs' => array(
                        'website.online_webpages'  => array(
                            'label' => _('Online web pages'),
                            'icon'  => 'files'
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
                            'icon'  => 'files'
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
                            'icon'    => 'file',
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
                'icon'      => 'shopping-basket',
                'showcase'  => 'store',
                'reference' => 'store/%d',
                'class'     => 'icon_only right',

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
                        'label' => _('Sales'),
                        'icon'  => 'fa-dollar-sign',

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

                    'store.charges' => array(
                        'label' => _('Charges'),
                        'icon'  => 'money',
                    ),

                    'store.shipping_zones' => array(
                        'label' => _('Shipping zones'),
                        'icon'  => 'truck fa-flip-horizontal',
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

                    'website.ready_webpages'      => 'website.webpages',
                    'website.online_webpages'     => 'website.webpages',
                    'website.offline_webpages'    => 'website.webpages',
                    'website.webpage.types'       => 'website.webpages',
                    'website.in_process_webpages' => 'website.webpages',


                    'website.info_bar' => 'website.templates',

                    'website.templates' => 'website.templates',


                    'website.details'        => 'website.settings',
                    'website.logos'          => 'website.settings',
                    'website.colours'        => 'website.settings',
                    'website.details'        => 'website.settings',
                    'website.localization'   => 'website.settings',
                    'website.menu.preview'   => 'website.settings',
                    'website.footer.preview' => 'website.settings',
                    'website.header.preview' => 'website.settings',


                ),

                'tabs' => array(


                    'website.settings' => array(
                        'label'   => _('Settings'),
                        'icon'    => 'sliders-h',
                        'subtabs' => array(

                            'website.details' => array(
                                'label' => _('General'),
                                'icon'  => 'cogs'
                            ),

                            'website.colours' => array(
                                'label' => _('Colours'),
                                'icon'  => 'tint',


                            ),


                            'website.header.preview' => array(
                                'label' => _('Header'),
                                'icon'  => 'arrow-alt-to-top',
                            ),


                            'website.menu.preview' => array(
                                'label' => _('Menu'),
                                'icon'  => 'bars',
                            ),

                            'website.footer.preview' => array(
                                'label' => _('Footer'),
                                'icon'  => 'arrow-alt-to-bottom',
                            ),


                            'website.localization' => array(
                                'label' => _('Localization'),
                                'icon'  => 'language',
                            ),

                        ),
                    ),


                    /*

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
                                'icon'    => 'heart',
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
                                'icon'    => 'hand-paper',
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

*/


                    'website.webpages' => array(
                        'label' => _('Web pages'),
                        'icon'  => 'browser',


                        'subtabs' => array(


                            'website.in_process_webpages' => array(
                                'label' => _('In process web pages'),
                                'icon'  => 'seedling'
                            ),

                            'website.ready_webpages' => array(
                                'label' => _('Ready web pages'),
                                'icon'  => 'check-circle '
                            ),

                            'website.online_webpages' => array(
                                'label' => _('Online web pages'),
                                'icon'  => 'rocket '
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


            'webpage.new' => array(
                'type' => 'new_object',
                'tabs' => array(
                    'webpage.new' => array(
                        'label' => _(
                            'New Webpage'
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
                        'label' => _('Data'),
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


            'deleted.webpage' => array(
                'type' => 'object',
                'tabs' => array(


                    'deleted.webpage.history' => array(
                        'label' => _('History/Notes'),
                        'icon'  => 'road',
                        'class' => 'right icon_only'
                    )

                )

            ),


            'marketing' => array(
                'type'  => 'navigation',
                'label' => _('Offers'),

                'icon'      => 'tags',
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
                        'label'         => _('Offers'),
                        'icon'          => 'tags',
                        'quantity_data' => array(
                            'object' => '_object',
                            'field'  => 'Deals Numbers'
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

                )
            ),


            'campaign_order_recursion' => array(
                'type' => 'object',


                'tabs' => array(
                    'campaign.details' => array(
                        'label' => _('Data'),
                        'icon'  => 'database',
                        'title' => _('Details')
                    ),

                    'campaign_order_recursion.components' => array(
                        'label'         => _('Allowances'),
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

                )
            ),


            'deal' => array(
                'type' => 'object',


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
                    'category.webpage.logbook'        => 'category.webpage',
                    'category.customers'              => 'category.customers',
                    'category.customers.favored'      => 'category.customers',

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

                    'category.subjects'  => array('label' => ''),
                    'category.customers' => array(
                        'label' => _('Customers'),
                        // 'quantity_data' => array('object' => '_object', 'field'  => 'Number Customers'),
                        'icon'  => 'user'
                    ),
                    'category.deals'     => array(
                        'label' => _('Offers'),
                        // 'quantity_data' => array('object' => '_object', 'field'  => 'Number Customers'),
                        'icon'  => 'tags'
                    ),

                    /*
                    'category.customers' => array('label' => '',

                                                  'subtabs'       => array(
                                                      'category.customers'         => array(
                                                          'label'         => _('Customers'),
                                                          'quantity_data' => array(
                                                              'object' => '_object',
                                                              'field'  => 'Number Customers'
                                                          ),
                                                      ),
                                                      'category.customers.favored' => array(
                                                          'label'         => _('Customers who favored'),
                                                          'quantity_data' => array(
                                                              'object' => '_object',
                                                              'field'  => 'Number Customers Favored'
                                                          ),

                                                      ),
                                                  )


                    ),

                    */

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
                        'icon'  => 'file-alt'
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


            'shipping_zone' => array(
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
            'stores'   => array(
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
                        'label' => _('Products'),
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
                'icon'      => 'tachometer',
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
                        'icon'  => 'sticky-note'
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

            /*
			,
			'enewsletters'=>array('type'=>'navigation', 'label'=>_('eNewsletters'), 'title'=>_('eNewsletters'), 'icon'=>'newspaper', 'reference'=>'marketing/%d/enewsletters',
				'tabs'=>array(
					'enewsletters'=>array()
				)
			),
			'mailshots'=>array('type'=>'navigation', 'label'=>_('Mailshots'), 'title'=>_('Mailshots'), 'icon'=>'at', 'reference'=>'marketing/%d/mailshots',
				'tabs'=>array(
					'mailshots'=>array()
				)),
			'marketing_post'=>array('type'=>'navigation', 'label'=>_('Marketing Post'), 'title'=>_('Marketing Post'), 'icon'=>'envelope', 'reference'=>'marketing/%d/marketing_post',
				'tabs'=>array(
					'marketing_post'=>array()
				)
			),
			'marketing_media'=>array('type'=>'navigation', 'label'=>_('Marketing Media'), 'title'=>_('Marketing Media'), 'icon'=>'google', 'reference'=>'marketing/%d/marketing_media',
				'tabs'=>array(
					'marketing_media'=>array()
				)
			),
			'ereminders'=>array('type'=>'navigation', 'label'=>_('eReminders'), 'title'=>_('eReminders'), 'icon'=>'bell', 'reference'=>'marketing/%d/ereminders',
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
                'icon'      => 'hand-holding-box',
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
                'icon'      => 'tachometer',
                'reference' => 'production/%d',
                'tabs'      => array(
                    'production.dashboard' => array()
                )
            ),


            'supplier_parts' => array(
                'type'      => 'navigation',
                'label'     => _('Parts'),
                'icon'      => 'hand-receiving',
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
                'icon'      => 'hand-rock',
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
                'class'     => 'icon_only right',
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

            'settings' => array(
                'type'      => 'navigation',
                'label'     => '',
                'title'     => _('Settings'),
                'icon'      => 'sliders',
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

                    'supplier.delivery.items'   => array(
                        'label' => _('Items'),
                        'icon'  => 'bars'
                    ),
                    'supplier.delivery.costing' => array(
                        'label' => _('Costing'),
                        'icon'  => 'box-usd'
                    ),
                    'supplier.delivery.history' => array(
                        'label' => '',
                        'title' => _(
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
                        'label' => _(
                            "Agent's suppliers"
                        ),
                        'icon'  => 'hand-holding-box'
                    ),

                    'agent.supplier_parts' => array(
                        'label' => _("Agent's parts"),
                        'icon'  => 'hand-receiving'
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
                                'label' => '',
                                'title' => _('Info'),
                                'icon'  => 'info',
                                'class' => 'right icon_only'
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
                    'part_family.part_locations'   => array(
                        'label' => _('Parts locations')
                    ),
                    'part_family.product_families' => array(
                        'label' => _('Product families')
                    ),
                    'part_family.products'         => array(
                        'label' => _('Products'),
                        'icon'  => 'cubes'
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
                                'label' => _('Stock movements'),
                                'icon'  => 'exchange',
                            ),
                            'part.stock.cost'         => array(
                                'label' => _('Stock cost'),
                                'icon'  => 'fa-dollar-sign',
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

                    'part.locations'              => array(
                        'label' => _('Locations'),
                        'icon'  => 'inventory'
                    ),
                    'part.paid_orders_in_process' => array(
                        'label' => '',
                        'title' => _('Paid orders in process by customer services'),
                        'icon'  => 'shopping-cart',
                        'class' => 'right icon_only'
                    ),
                    'part.history'                => array(


                        'title'         => _('History/Notes'),
                        'label'         => '',
                        'quantity_data' => array(
                            'object' => '_object',
                            'field'  => 'Number History Records'
                        ),

                        'icon'  => 'road',
                        'class' => 'right icon_only'
                    ),
                    'part.images'                 => array(
                        'title'         => _('Images'),
                        'label'         => '',
                        'quantity_data' => array(
                            'object' => '_object',
                            'field'  => 'Number Images'
                        ),
                        'icon'          => 'camera-retro',
                        'class'         => 'right icon_only'
                    ),
                    'part.attachments'            => array(
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
                'icon'      => 'warehouse-alt',
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
                'icon'      => 'inventory',
                'reference' => 'warehouse/%d/locations',
                'tabs'      => array(
                    'warehouse.locations' => array(
                        'label' => _('Locations'),
                    ),


                )

            ),


            'shippers' => array(
                'type'      => 'navigation',
                'icon'      => 'truck-loading',
                'label'     => _('Shipping companies'),
                'reference' => 'warehouse/%d/shippers',
                'tabs'      => array(
                    'shippers' => array()
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
                        'icon'  => 'box'
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

            'shipper' => array(

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
    ),
    'reports'         => array(

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
                'icon'      => 'money-bill-alt',
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
            'prospect_agents' => array(
                'type' => '',
                'tabs' => array(
                    'prospect_agents' => array(),

                )

            ),

            'sales'            => array(
                'type' => '',
                'tabs' => array(
                    'sales' => array(),

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

            'intrastat'          => array(
                'type' => '',
                'tabs' => array(
                    'intrastat' => array(),

                )

            ),
            'intrastat_orders'   => array(
                'type' => '',
                'tabs' => array(
                    'intrastat_orders' => array(),

                )

            ),
            'intrastat_products' => array(
                'type' => '',
                'tabs' => array(
                    'intrastat_products' => array(),

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
                    /*
                    'sales_representative.prospects' => array(
                        'label' => _('Prospects')
                    ),
                    */


                )

            ),


        )
    ),
    'hr'              => array(

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
                'icon'      => 'calendar',
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
    'profile'         => array(


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
                        'label' => _(
                            'Login history'
                        ),
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

            'profile.api_key.new' => array(
                'type' => 'new_object',
                'tabs' => array(
                    'user.api_key.new' => array(
                        'label' => _('New API')
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


            'staff' => array(
                'type'      => 'object',
                'label'     => _('Staff'),
                'title'     => _("Staff users"),
                'icon'      => 'hand-rock',
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
                'icon'      => 'hand-holding-box',
                'reference' => 'users/suppliers',
            ),
            'warehouse' => array(
                'type'      => 'object',
                'label'     => _('Warehouse'),
                'title'     => _('Warehouse users'),
                'icon'      => 'warehouse-alt',
                'reference' => 'users/warehouse',
            ),
            'root'      => array(
                'type'      => 'object',
                'label'     => 'Root',
                'title'     => _('Root user'),
                'icon'      => 'dot-circle',
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
                'icon'  => 'file',
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
                        'icon'  => 'hand-holding-box'
                    ),

                    'agent.supplier_parts' => array(
                        'label' => _(
                            "Agent's Parts"
                        ),
                        'icon'  => 'hand-receiving'
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
                        'label' => _('History/Notes'),
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
                'icon'      => 'hand-holding-box',
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
                        'icon'  => 'hand-receiving'
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
                    'agent_delivery.items'   => array(
                        'label' => _('Items'),
                        'icon'  => 'bars'
                    ),
                    'agent_delivery.cartons' => array(
                        'label' => _('Boxes'),
                        'icon'  => 'dropbox'
                    ),
                    'agent_delivery.details' => array(
                        'label' => _('Data'),
                        'icon'  => 'database'
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
