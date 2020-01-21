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
                'label'     => _('Parts'),
                'icon'      => 'hand-receiving',
                'reference' => 'production/%d/parts',
                'tabs'      => array(
                    'production.production_parts' => array('label' => _('Parts'))
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


                    'bill_of_materials' => array(
                        'label' => _('Bill of materials'),
                        'icon'  => 'puzzle-piece'
                    ),

                    'bill_of_materials_edit' => array(
                        'class' => 'hide',
                        'label' => _('Edit bill of materials'),
                        'icon'  => 'puzzle-piece'
                    ),
                    'production_part.tasks'  => array(
                        'label' => _('List of tasks'),
                        'icon'  => 'tasks'
                    ),


                    'production_part.purchase_orders' => array(
                        'label'   => _('Job orders / production sheets'),
                        'icon'    => 'clipboard',
                        'subtabs' => array(

                            'production_part.supplier.orders'     => array(
                                'label' => _('Job orders'),
                                'icon'  => 'clipboard'
                            ),
                            'production_part.supplier.deliveries' => array(
                                'label' => _("Production sheets"),
                                'icon'  => 'clipboard-check'
                            ),

                        )

                    ),

                    'production_part.details' => array(
                        'label' => _('Data'),
                        'icon'  => 'database'
                    ),

                    'production_part.images'  => array(
                        'label' => '',
                        'title' => _('Images'),
                        'icon'  => 'camera-retro',
                        'class' => 'right icon_only'
                    ),
                    'production_part.history' => array(
                        'label' => '',
                        'title' => _('History/Notes'),
                        'icon'  => 'road',
                        'class' => 'right icon_only'
                    ),


                )
            ),

            /*
            'batches'        => array(
                'type'      => 'navigation',
                'label'     => _('Batches'),
                'icon'      => 'clone',
                'reference' => 'production/%d/batches',
                'tabs'      => array(
                    'batches' => array('label' => _('Batches'))
                )


            ),
  */
            'materials'        => array(
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


            'production_supplier_orders'     => array(
                'type'      => 'navigation',
                'label'     => _('Job orders'),
                'icon'      => 'clipboard',
                'reference' => 'production/%d/orders',
                'tabs'      => array(
                    'production_supplier.orders' => array()
                )
            ),
            'production_supplier_deliveries' => array(
                'type'      => 'navigation',
                'label'     => _('Production sheets'),
                'icon'      => 'clipboard-check',
                'reference' => 'production/%d/deliveries',
                'tabs'      => array(
                    'production_supplier.deliveries' => array()
                )
            ),


            'order' => array(
                'type' => 'object',
                'tabs' => array(
                    'supplier.order.items'   => array(
                        'label' => _('Items'),
                        'icon'  => 'bars'
                    ),
                    'supplier.order.details' => array(
                        'label' => _('Data'),
                        'icon'  => 'database'
                    ),


                    'supplier.order.items_in_process' => array(
                        'label' => _('Items'),
                        'icon'  => 'bars'
                    ),

                    'supplier.order.all_supplier_parts' => array(
                        'label' => _("All supplier's products"),
                        'icon'  => 'th-list'
                    ),
                    /*
                                        'supplier.order.tac.editor' => array(
                                            'label' => _('Terms and conditions'),
                                            'icon'  => 'gavel',
                                            'class' => ''
                                        ),
                    */
                    'supplier.order.history'            => array(
                        'label' => _('History/Notes'),
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