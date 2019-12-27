<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:   27 December 2019  11:02::56  +0800, Kuala Lumpur Malaysia

 Copyright (c) 2019, Inikoo

 Version 3.0
*/

function get_orders_server_module() {
    return array(

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

    );
}