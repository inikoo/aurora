<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  27 December 2019  11:10::44  +0800 Kuala Lumpur Malaysia

 Copyright (c) 2019, Inikoo

 Version 3.0
*/

function get_production_module() {
    return array(
        'section'     => 'production',
        'parent'      => 'account',
        'parent_type' => 'key',
        'sections'    => array(
            'dashboard' => array(
                'type'      => 'navigation',
                'label'     => '',
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
                'label'     => _('Products'),
                'icon'      => 'box-heart',
                'reference' => 'production/%d/parts',
                'tabs'      => array(


                    'production.discontinued_parts'  => array(
                        'label' => _('Discontinued'),
                        'class' => 'very_discreet right'
                    ),
                    'production.discontinuing_parts' => array(
                        'label' => _('Discontinuing'),
                        'class' => 'discreet right'
                    ),
                    'production.in_process_parts'    => array(
                        'label' => _('In process'),
                        'class' => 'discreet right'
                    ),
                    'production.parts'               => array(
                        'label' => _('Active'),
                        'class'=>'right'
                    ),



                    'production.production_parts' => array(
                        'label' => _('All product'),
                        'quantity_data' => array(
                            'object' => '_object',
                            'field'  => 'Number Parts'
                        ),

                    )
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
                    'production_part.batch'   => array(
                        'label' => _('Batch'),
                        'icon'  => 'conveyor-belt'
                    ),


                    'production_part.purchase_orders' => array(
                        'label'   => _('Job orders'),
                        'icon'    => 'clipboard',
                        'subtabs' => array(

                            'production_part.supplier.orders'     => array(
                                'label' => _('Job orders'),
                                'icon'  => 'clipboard'
                            ),
                            'production_part.supplier.deliveries' => array(
                                'label' => _("Deliveries"),
                                'icon'  => 'hand-holding-heart'
                            ),

                        )

                    ),

                    'production_part.details' => array(
                        'label' => _('Data'),
                        'icon'  => 'database'
                    ),


                    'production_part.history' => array(
                        'label' => '',
                        'title' => _('History/Notes'),
                        'icon'  => 'road',
                        'class' => 'right icon_only'
                    ),
                    'production_part.images'  => array(
                        'label' => '',
                        'title' => _('Images'),
                        'icon'  => 'camera-retro',
                        'class' => 'right icon_only'
                    ),
                    'bill_of_materials'       => array(
                        'title' => _('Bill of materials'),
                        'label' => '',
                        'icon'  => 'puzzle-piece',
                        'class' => 'right icon_only'

                    ),

                    'production_part.tasks' => array(
                        'title' => _('List of tasks'),
                        'icon'  => 'tasks',
                        'label' => '',
                        'class' => 'right icon_only'
                    ),


                )
            ),


            'raw_materials' => array(
                'type'      => 'navigation',
                'label'     => _('Raw Materials'),
                'icon'      => 'puzzle-piece',
                'reference' => 'production/%d/raw_materials',
                'tabs'      => array(
                    'production.materials' => array('label' => _('Raw materials'))
                )


            ),

            'raw_material' => array(
                'type'  => 'object',
                'label' => _('Raw material'),
                'icon'  => 'tasks',
                'tabs'  => array(
                    'raw_material.details' => array(
                        'label' => _('Data'),
                        'icon'  => 'database'
                    ),
                    'raw_material.history' => array(
                        'label' => '',
                        'title' => _('History/Notes'),
                        'icon'  => 'road',
                        'class' => 'right icon_only'
                    ),

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
            'operative'  => array(
                'type'  => 'object',
                'label' => _('Worker'),
                'icon'  => 'digging',
                'tabs'  => array(
                    'operative.tasks'      => array('label' => _('Tasks')),
                    'operative.job_orders' => array('label' => _('Job orders')),

                    'operative.products' => array(
                        'label' => _('Products'),
                        'class' => 'right'
                    ),

                )


            ),


            'production_supplier_orders' => array(
                'type'      => 'navigation',
                'label'     => _('Job orders'),
                'icon'      => 'clipboard',
                'reference' => 'production/%d/orders',
                'tabs'      => array(

                    'production_supplier.orders' => array(
                        'label' => _('Job orders')
                    )
                    /*
                    'production_supplier.deliveries'       => array(
                        'label' => _('Deliveries')
                    )
                    */

                )
            ),


            'order' => array(
                'type' => 'object',
                'tabs' => array(
                    'job_order.items'            => array(
                        'label' => _('Items'),
                        'icon'  => 'bars'
                    ),
                    'job_order.items_in_process' => array(
                        'label' => _('Items'),
                        'icon'  => 'bars'
                    ),


                    'job_order.all_production_parts' => array(
                        'label' => _("All products"),
                        'icon'  => 'th-list'
                    ),


                    'supplier.order.history' => array(
                        'label'         => _('History/Notes'),
                        'icon'          => 'road',
                        'class'         => 'right icon_only',
                        'quantity_data' => array(
                            'object' => '_object',
                            'field'  => 'Number History Records'
                        ),
                    ),
                    'supplier.order.details' => array(
                        'label' => _('Data'),
                        'icon'  => 'database',
                        'class' => 'right'
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
    );
}