<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  27 December 2019  11:40::40  +0800, Kuala Lumpur Malaysia

 Copyright (c) 2019, Inikoo

 Version 3.0
*/

function get_reports_module() {
    return array(

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
            'picker'                => array(
                'type' => '',
                'tabs' => array(
                    'picker.delivery_notes' => array(
                        'label' => _('Deliveries'),
                        'icon'  => 'truck'
                    ),
                    'picker.feedback'       => array(
                        'label' => _('Feedback'),
                        'icon'  => 'poop'
                    ),
                )

            ),
            'packers'               => array(
                'type' => '',
                'tabs' => array(
                    'packers' => array(),

                )

            ),
            'packer'                => array(
                'type' => '',
                'tabs' => array(
                    'packer.delivery_notes' => array(
                        'label' => _('Deliveries'),
                        'icon'  => 'truck'
                    ),
                    'packer.feedback'       => array(
                        'label' => _('Feedback'),
                        'icon'  => 'poop'
                    ),
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
    );
}