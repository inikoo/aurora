<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  27 December 2019  11:19::05  +0800, Kuala Lumpur Malaysia

 Copyright (c) 2019, Inikoo

 Version 3.0
*/

function get_accounting_server_module() {
    return array(


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

    );
}