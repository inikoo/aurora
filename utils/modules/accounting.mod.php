<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  27 December 2019  11:19::40  +0800, Kuala Lumpur Malaysia

 Copyright (c) 2019, Inikoo

 Version 3.0
*/

function get_accounting_module() {
    return array(

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
    );
}