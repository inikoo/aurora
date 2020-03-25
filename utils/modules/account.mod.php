<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  27 December 2019  12:01::35  +0800, Kuala Lumpur Malaysia

 Copyright (c) 2019, Inikoo

 Version 3.0
*/

function get_account_module() {
    return array(


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
                        'label' => _('Data'),
                        'title' => _('Account details')
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

    );
}