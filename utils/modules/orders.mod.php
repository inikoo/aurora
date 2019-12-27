<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  27 December 2019  11:04::41  +0800, Kuala Lumpur Malaysia

 Copyright (c) 2019, Inikoo

 Version 3.0
*/

function get_orders_module() {
    return array(
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
    );
}