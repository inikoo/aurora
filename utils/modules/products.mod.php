<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:   27 December 2019  11:02::38  +0800, Kuala Lumpur Malaysia

 Copyright (c) 2019, Inikoo

 Version 3.0
*/

function get_products_module() {

    include_once 'utils/modules/product.sec.php';
    include_once 'utils/modules/service.sec.php';

    return array(
        'section'  => 'products',
        'sections' => array(

            'store' => array(
                'type'      => 'left_button',
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
            'category'          => array(
                'type'           => 'object',
                'subtabs_parent' => array(
                    'category.product.sales.plot'     => 'category.sales',
                    'category.product.sales.history'  => 'category.sales',
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

                    'category.correlations' => array(
                        'title' => _('Related categories'),
                        'label' => _('Related categories'),
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

            'website.new' => array(
                'type' => 'new_object',
                'tabs' => array(
                    'website.new' => array(
                        'label' => _('New website')
                    ),

                )

            ),


            'no_website' => array(
                'type'  => 'object',
                'tabs' => array(
                    'no_website' => array(
                        'label' => _('No website')
                    ),

                )

            ),



            'settings' => array(
                'type'           => 'right_button',
                'label'          => _('Settings'),
                'title'          => _('Settings'),
                'icon'           => 'sliders-h',
                'reference'      => 'store/%d/settings',
                'subtabs_parent' => array(


                    'store.current_shipping_zones'   => 'store.shipping_zones',
                    'store.shipping_zones_schemas'   => 'store.shipping_zones',

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


                    'localization.materials' => array(
                        'label' => _('Localization (Materials/Ingredients)'),
                        'icon'  => 'language',


                    ),
                    'store.history'          => array(
                        'label' => _('History'),
                        'icon'  => 'road',
                        'class' => 'right icon_only'
                    ),


                )

            ),

/*
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
*/
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
            'product'           => get_product_section(),
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
            'service'           => get_service_section(),
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
    );
}