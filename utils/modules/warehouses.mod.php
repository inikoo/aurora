<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  27 December 2019  11:35::43  +0800, Kuala Lumpur Malaysia

 Copyright (c) 2019, Inikoo

 Version 3.0
*/

function get_warehouses_module() {
    return array(
        'sections' => array(


            'dashboard'      => array(
                'type'      => 'left_button',
                'title'     => _('Dashboard'),
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
                    'warehouse.details' => array(
                        'label' => _('Settings'),
                        'title' => _('Warehouse settings'),
                        'icon'  => 'sliders-h'
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


                    'warehouse.locations'         => array(
                        'label'             => _('Locations'),
                        'icon'              => 'pallet',
                        'dynamic_reference' => 'warehouse/%d/locations',

                    ),
                    'warehouse.areas'             => array(
                        'label'             => _('Warehouse areas'),
                        'title'             => _('Warehouse areas (Rankings, aisles, rooms)'),
                        'icon'              => 'inventory',
                        'dynamic_reference' => 'warehouse/%d/areas',

                    ),
                    'warehouse.deleted_locations' => array(
                        'label'             => _('Deleted locations'),
                        'icon'              => 'ban',
                        'dynamic_reference' => 'warehouse/%d/deleted_locations',
                        'class'             => 'right'

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
                    'warehouse_area.new' => array(
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


            'production_deliveries' => array(
                'type'      => 'navigation',
                'label'     => _('Production deliveries'),
                'icon'      => 'industry',
                'reference' => 'warehouse/%d/production_deliveries',
                'tabs'      => array(
                    'warehouse.production_deliveries.todo'      => array(
                        'label' => _('To-do'),
                        'icon'  => 'clipboard-list'
                    ),
                    'warehouse.production_deliveries.done'      => array(
                        'label' => _('Done'),
                        'icon'  => 'clipboard-check'
                    ),
                    'warehouse.production_deliveries.cancelled' => array(
                        'label' => _('Cancelled')
                    ),
                    'warehouse.production_deliveries.all'       => array(
                        'label' => _('All'),
                        'class' => 'right'
                    )

                )
            ),
            'production_delivery'   => array(
                'type' => 'object',
                'tabs' => array(
                    'supplier.delivery.items' => array(
                        'label' => _('Items'),
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


                    'supplier.delivery.history' => array(
                        'label' => '',
                        'title' => _('History/Notes'),
                        'icon'  => 'road',
                        'class' => 'right icon_only'
                    ),

                )

            ),
            'returns'               => array(
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


            'warehouse_kpis' => array(
                'type'      => 'navigation',
                'label'     => _('KPIs'),
                'icon'      => 'hand-holding-usd',
                'reference' => 'warehouse/%d/kpis',
                'tabs'      => array(
                    'warehouse_bonus_report' => array(
                        'label' => _('Bonus report'),

                    ),
                    'picking_bands'          => array(
                        'label' => _('Picking bands'),
                        'class' => 'right'

                    ),
                    'packing_bands'          => array(
                        'label' => _('Packing bands'),
                        'class' => 'right'
                    ),

                )
            ),


            'staff_warehouse_kpi' => array(
                'type' => '',
                'tabs' => array(
                    'staff_warehouse_kpi.delivery_notes' => array(
                        'label' => _('Deliveries'),
                        'icon'  => 'truck'
                    ),
                  //  'staff_warehouse_kpi.feedback'       => array(
                  //      'label' => _('Feedback'),
                  //      'icon'  => 'poop'
                  //  ),
                )

            ),

            'feedback' => array(
                'type'      => 'right_button',
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
    );
}