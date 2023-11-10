<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  27 December 2019  11:50::24  +0800, Kuala Lumpur Malaysia

 Copyright (c) 2019, Inikoo

 Version 3.0
*/

function get_suppliers_module() {
    return array(
        'section'     => 'suppliers',
        'parent'      => 'store',
        'parent_type' => 'key',
        'sections'    => array(
            /*
            'dashboard'=>array('type'=>'navigation', 'label'=>_('Dashboard'), 'title'=>_("Supplier's dashboard"), 'icon'=>'dashboard', 'reference'=>'suppliers/dashboard',
                'tabs'=>array(
                    'suppliers.dashboard'=>array()
                )
            ),
            */


            'dashboard' => array(

                'type'      => 'left_button',
                'title'     => _('Dashboard'),
                'icon'      => 'tachometer-alt',
                'reference' => 'suppliers/dashboard',
                'tabs'      => array('suppliers.dashboard' => array())


            ),

            'suppliers' => array(

                'type'      => 'navigation',
                'label'     => _('Suppliers'),
                'icon'      => 'hand-holding-box',
                'reference' => 'suppliers',
                'tabs'      => array(
                    'suppliers' => array()
                )


            ),
            'agents'    => array(
                'type'      => 'navigation',
                'label'     => _('Agents'),
                'icon'      => 'user-secret',
                'reference' => 'agents',
                'tabs'      => array(
                    'agents' => array()
                )
            ),
            /*

                        'categories' => array(
                            'type'      => 'navigation',
                            'label'     => _('Categories'),
                            'title'     => _('Categories'),
                            'icon'      => 'sitemap',
                            'reference' => 'suppliers/categories',
                            'tabs'      => array(
                                'suppliers.categories' => array()
                            )

                        ),
            */

            'supplier_parts' => array(
                'type'           => 'navigation',
                'subtabs_parent' => array(

                    'suppliers.supplier_parts.surplus'      => 'suppliers.supplier_parts',
                    'suppliers.supplier_parts.ok'           => 'suppliers.supplier_parts',
                    'suppliers.supplier_parts.low'          => 'suppliers.supplier_parts',
                    'suppliers.supplier_parts.critical'     => 'suppliers.supplier_parts',
                    'suppliers.supplier_parts.out_of_stock' => 'suppliers.supplier_parts',
                    'suppliers.supplier_parts.all'          => 'suppliers.supplier_parts',


                ),
                'label'          => _("Products"),
                'title'          => _("Supplier's products"),
                'icon'           => 'hand-receiving',
                'reference'      => 'suppliers/supplier_parts',
                'tabs'           => array(
                    'suppliers.supplier_parts' => array(
                        'label'   => _("Supplier's products"),
                        'icon'    => 'hand-receiving',
                        'subtabs' => array(
                            'suppliers.supplier_parts.surplus' => array(
                                'label' => _('Surplus')
                            ),
                            'suppliers.supplier_parts.ok'      => array(
                                'label' => _('OK')
                            ),
                            'suppliers.supplier_parts.low'     => array(
                                'label' => _('Low')
                            ),

                            'suppliers.supplier_parts.critical'     => array(
                                'label' => _('Critical')
                            ),
                            'suppliers.supplier_parts.out_of_stock' => array(
                                'label' => _('Out of stock')
                            ),
                            'suppliers.supplier_parts.all'          => array(
                                'label' => _('All'),
                                'class' => 'right'
                            )

                        )
                    ),
                    'suppliers.categories'     => array(
                        'label' => _('Categories'),
                        'icon'  => 'sitemap',

                    )
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

            'orders' => array(
                'type'      => 'navigation',
                'label'     => _('Purchase orders / Deliveries'),
                'icon'      => 'clipboard',
                'reference' => 'suppliers/orders',
                'tabs'      => array(
                    'suppliers.orders'     => array(
                        'label' => _('Purchase Orders')
                    ),
                    'suppliers.deliveries' => array(
                        'label' => _('Deliveries')
                    )
                )

            ),


            'settings' => array(

                'type'      => 'right_button',
                'label'     => '',
                'title'     => _('Settings'),
                'icon'      => 'sliders-h',
                'reference' => 'suppliers/settings',
                'class'     => 'icon_only right',
                'tabs'      => array(
                    'suppliers.settings'       => array(
                        'label' => _('General settings'),
                        'icon'  => 'sliders',
                        'class' => ''
                    ),
                    'suppliers.email_template' => array(
                        'label' => _('Email template'),
                        'icon'  => 'envelope',
                        'class' => ''
                    ),


                )


            ),


            'order' => array(
                'type' => 'object',
                'tabs' => array(
                    'supplier.order.details' => array(
                        'label' => _('Data'),
                        'icon'  => 'database'
                    ),

                    'supplier.order.items'            => array(
                        'label' => _('Items'),
                        'icon'  => 'bars'
                    ),
                    'supplier.order.items_in_process' => array(
                        'label' => _('Items'),
                        'icon'  => 'bars'
                    ),

                    'supplier.order.all_supplier_parts' => array(
                        'label' => _("All supplier's products"),
                        'icon'  => 'th-list'
                    ),

                    'supplier.order.history' => array(
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
                        'label' => _('History/Notes'),
                        'icon'  => 'road',
                        'class' => 'right icon_only'
                    ),

                )

            ),

            'delivery' => array(
                'type' => 'object',
                'tabs' => array(

                    'supplier.delivery.details' => array(
                        'label' => _('Settings'),
                        'icon'  => 'sliders-h'
                    ),

                    'supplier.delivery.items'          => array(
                        'label' => _('Items'),
                        'icon'  => 'bars'
                    ),
                    'supplier.delivery.costing'        => array(
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


                    'supplier.delivery.history'     => array(
                        'label'         => '',
                        'title'         => _('History/Notes'),
                        'icon'          => 'road',
                        'class'         => 'right icon_only',
                        'quantity_data' => array(
                            'object' => '_object',
                            'field'  => 'Number History Records'
                        ),
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


            'supplier_delivery.attachment.new' => array(
                'type' => 'new_object',
                'tabs' => array(
                    'supplier_delivery.attachment.new' => array(
                        'label' => _('New attachment')
                    ),

                )

            ),
            'supplier_delivery.attachment'     => array(
                'type' => 'object',
                'tabs' => array(
                    'supplier_delivery.attachment.details' => array(
                        'label' => _('Data'),
                        'icon'  => 'database'
                    ),
                    'supplier_delivery.attachment.history' => array(
                        'label' => _('History'),
                        'icon'  => 'clock'
                    ),

                )

            ),

            'category'  => array(
                'type' => 'object',

                'tabs' => array(
                    'category.details'    => array(
                        'label' => _('Data'),
                        'icon'  => 'database',
                        'title' => _('Details')
                    ),
                    'category.history'    => array(
                        'label' => _(
                            'History, notes'
                        ),
                        'icon'  => 'sticky-note'
                    ),
                    'category.subjects'   => array('label' => _('Suppliers')),
                    'category.categories' => array(
                        'label' => _(
                            'Subcategories'
                        )
                    ),

                )

            ),
            'agent'     => array(
                'type'      => 'object',
                'label'     => _('agent'),
                'icon'      => 'hand-holding-box',
                'reference' => 'agent/%d',

                'subtabs_parent' => array(
                    'agent.sales.plot'      => 'agent.sales',
                    'agent.sales.history'   => 'agent.sales',
                    'agent.sales.dashboard' => 'agent.sales',
                    'agent.sales.info'      => 'agent.sales',

                ),

                'tabs'      => array(
                    'agent.details'   => array(
                        'label' => _('Data'),
                        'icon'  => 'database',
                        'title' => _('Details')
                    ),

                    'agent.sales' => array(
                        'label'   => _('Purchases/Sales'),
                        'icon'    => 'money-bill',
                        'subtabs' => array(
                            'agent.sales.dashboard' => array(
                                'label' => _(
                                    'Dashboard'
                                )
                            ),
                            'agent.sales.plot'      => array(
                                'label' => _(
                                    'Plot'
                                )
                            ),
                            'agent.sales.history'   => array(
                                'label' => _(
                                    'Sales history'
                                )
                            ),


                            'agent.sales.info' => array(
                                'label'   => '',
                                'title'   => _('Sales data info'),
                                'icon_v2' => 'fal fa-fw fa-chess-clock',
                                'class'   => 'right icon_only'
                            ),


                        )
                    ),


                    'agent.suppliers' => array(
                        'label' => _("Agent's suppliers"),
                        'icon'  => 'hand-holding-box'
                    ),

                    'agent.supplier_parts' => array(
                        'label' => _("Agent's parts"),
                        'icon'  => 'hand-receiving'
                    ),
                    'agent.orders'         => array(
                        'label' => _('Purchase orders'),
                        'icon'  => 'clipboard'
                    ),
                    'agent.deliveries'     => array(
                        'label' => _('Deliveries'),
                        'icon'  => 'truck'
                    ),
                    /*
                    'agent.agent_orders'   => array(
                        'label' => _("Agent's PO"),
                        'icon'  => 'clipboard fa-flip-horizontal'
                    ),
  */
                    'agent.history'        => array(
                        'label' => _('History/Notes'),
                        'icon'  => 'road',
                        'class' => 'right icon_only'
                    ),
                    'agent.users'          => array(
                        'label' => _('System users'),
                        'icon'  => 'terminal',
                        'class' => 'right icon_only'
                    ),

                    'agent.images' => array(
                        'title'         => _('Images'),
                        'label'         => '',
                        'quantity_data' => array(
                            'object' => '_object',
                            'field'  => 'Number Images'
                        ),
                        'icon'          => 'camera-alt',
                        'class'         => 'right icon_only'
                    ),


                )
            ),
            'agent.new' => array(
                'type' => 'new_object',
                'tabs' => array(
                    'agent.new' => array(
                        'label' => _(
                            'New agent'
                        )
                    ),

                )

            ),
            'supplier'  => array(
                'type'      => 'object',
                'label'     => _('Supplier'),
                'icon'      => 'hand-holding-box',
                'reference' => 'supplier/%d',

                'subtabs_parent' => array(
                    'supplier.sales.plot'      => 'supplier.sales',
                    'supplier.sales.history'   => 'supplier.sales',
                    'supplier.sales.dashboard' => 'supplier.sales',
                    'supplier.sales.info'      => 'supplier.sales',

                ),

                'tabs' => array(
                    'supplier.details' => array(
                        'label' => _('Data'),
                        'icon'  => 'database',
                        'title' => _('Details')
                    ),
                    'supplier.sales'   => array(
                        'label' => _("Part's sales"),
                        'icon'  => ''
                    ),


                    'supplier.sales' => array(
                        'label'   => _('Purchases/Sales'),
                        'icon'    => 'money-bill',
                        'subtabs' => array(
                            'supplier.sales.dashboard' => array(
                                'label' => _(
                                    'Dashboard'
                                )
                            ),
                            'supplier.sales.plot'      => array(
                                'label' => _(
                                    'Plot'
                                )
                            ),
                            'supplier.sales.history'   => array(
                                'label' => _(
                                    'Sales history'
                                )
                            ),


                            'supplier.sales.info' => array(
                                'label'   => '',
                                'title'   => _('Sales data info'),
                                'icon_v2' => 'fal fa-fw fa-chess-clock',
                                'class'   => 'right icon_only'
                            ),


                        )
                    ),

                    'supplier.supplier_parts' => array(
                        'label'         => _("Supplier's products"),
                        'quantity_data' => array(
                            'object' => '_object',
                            'field'  => 'Number Parts'
                        ),
                        'icon'          => 'hand-receiving'
                    ),
                    'supplier.feedback'       => array(
                        'label' => _("Issues"),

                        'icon' => 'poop'
                    ),
                    'supplier.orders'         => array(
                        'label'         => _(
                            'Purchase orders'
                        ),
                        'quantity_data' => array(
                            'object' => '_object',
                            'field'  => 'Number Purchase Orders'
                        ),
                        'icon'          => 'clipboard'
                    ),
                    'supplier.deliveries'     => array(
                        'label'         => _('Deliveries'),
                        'quantity_data' => array(
                            'object' => '_object',
                            'field'  => 'Number Deliveries'
                        ),
                        'icon'          => 'truck'
                    ),


                    'supplier.history' => array(
                        'title'         => _('History/Notes'),
                        'quantity_data' => array(
                            'object' => '_object',
                            'field'  => 'Number History Records'
                        ),
                        'label'         => '',
                        'icon'          => 'road',
                        'class'         => 'right icon_only'
                    ),
                    'supplier.users'   => array(
                        'title'         => _('System users'),
                        'quantity_data' => array(
                            'object' => '_object',
                            'field'  => 'Number System Users'
                        ),
                        'label'         => '',
                        'icon'          => 'user',
                        'class'         => 'right icon_only'
                    ),

                    'supplier.attachments' => array(
                        'title'         => _('Attachments'),
                        'label'         => '',
                        'quantity_data' => array(
                            'object' => '_object',
                            'field'  => 'Number Attachments'
                        ),
                        'icon'          => 'paperclip',
                        'class'         => 'right icon_only'
                    ),

                    'supplier.images' => array(
                        'title'         => _('Images'),
                        'label'         => '',
                        'quantity_data' => array(
                            'object' => '_object',
                            'field'  => 'Number Images'
                        ),
                        'icon'          => 'camera-alt',
                        'class'         => 'right icon_only'
                    ),

                )
            ),


            'supplier.new'            => array(
                'type' => 'new_object',
                'tabs' => array(
                    'supplier.new' => array(
                        'label' => _(
                            'New supplier'
                        )
                    ),

                )

            ),
            'supplier.attachment.new' => array(
                'type' => 'new_object',
                'tabs' => array(
                    'supplier.attachment.new' => array(
                        'label' => _(
                            'new attachment'
                        )
                    ),

                )

            ),
            'supplier.attachment'     => array(
                'type' => 'object',
                'tabs' => array(
                    'supplier.attachment.details' => array(
                        'label' => _(
                            'Data'
                        ),
                        'icon'  => 'database'
                    ),
                    'supplier.attachment.history' => array(
                        'label' => _(
                            'History'
                        ),
                        'icon'  => 'clock'
                    ),

                )

            ),
            'deleted_supplier'        => array(
                'type' => 'object',
                'tabs' => array(
                    'supplier.history' => array(
                        'label' => _('History'),
                        'icon'  => 'road'
                    ),


                )
            ),

            'supplier.order.item' => array(
                'type'           => 'object',
                'subtabs_parent' => array(

                    'supplier_part.purchase_orders.purchase_orders' => 'supplier_part.purchase_orders',
                    'supplier_part.purchase_orders.delivery_notes'  => 'supplier_part.purchase_orders',

                ),


                'tabs' => array(


                    'supplier.order.item.details' => array(
                        'label' => _(
                            'Data'
                        ),
                        'icon'  => 'database'
                    ),


                    'supplier_part.purchase_orders' => array(
                        'label'   => _(
                            'Purchase Orders'
                        ),
                        'icon'    => 'clipboard',
                        'class'   => 'right icon_only',
                        'subtabs' => array(
                            'supplier_part.purchase_orders.purchase_orders' => array(
                                'label' => _(
                                    'Purchase Orders'
                                )
                            ),
                            'supplier_part.purchase_orders.delivery_notes'  => array(
                                'label' => _(
                                    'Delivery Notes'
                                )
                            ),

                        )

                    ),


                    'supplier_part.images'  => array(
                        'label' => '',
                        'title' => _('Images'),
                        'icon'  => 'camera-retro',
                        'class' => 'right icon_only'
                    ),
                    'supplier_part.history' => array(
                        'label' => '',
                        'title' => _('History/Notes'),
                        'icon'  => 'road',
                        'class' => 'right icon_only'
                    ),


                )
            ),

            'agent.order.item' => array(
                'type'           => 'object',
                'subtabs_parent' => array(

                    'supplier_part.purchase_orders.purchase_orders' => 'supplier_part.purchase_orders',
                    'supplier_part.purchase_orders.delivery_notes'  => 'supplier_part.purchase_orders',

                ),


                'tabs' => array(


                    'agent.order.item.details' => array(
                        'label' => _(
                            'Data'
                        ),
                        'icon'  => 'database'
                    ),


                    'supplier_part.purchase_orders' => array(
                        'label'   => _(
                            'Purchase Orders'
                        ),
                        'icon'    => 'clipboard',
                        'class'   => 'right icon_only',
                        'subtabs' => array(
                            'supplier_part.purchase_orders.purchase_orders' => array(
                                'label' => _(
                                    'Purchase Orders'
                                )
                            ),
                            'supplier_part.purchase_orders.delivery_notes'  => array(
                                'label' => _(
                                    'Delivery Notes'
                                )
                            ),

                        )

                    ),

                    'supplier_part.images'  => array(
                        'label' => '',
                        'title' => _('Images'),
                        'icon'  => 'camera-retro',
                        'class' => 'right icon_only'
                    ),
                    'supplier_part.history' => array(
                        'label' => '',
                        'title' => _('History/Notes'),
                        'icon'  => 'road',
                        'class' => 'right icon_only'
                    ),


                )
            ),

            'supplier_part' => array(
                'type'           => 'object',
                'subtabs_parent' => array(

                    'supplier_part.supplier.orders'     => 'supplier_part.purchase_orders',
                    'supplier_part.supplier.deliveries' => 'supplier_part.purchase_orders',
                ),


                'tabs' => array(


                    'supplier_part.details' => array(
                        'label' => _('Data'),
                        'icon'  => 'database'
                    ),

                    'supplier_part.feedback' => array(
                        'label' => _('Issues'),
                        'icon'  => 'poop'
                    ),


                    'supplier_part.purchase_orders' => array(
                        'label'   => _('Purchase orders / deliveries'),
                        'icon'    => 'clipboard',
                        'subtabs' => array(

                            'supplier_part.supplier.orders'     => array(
                                'label' => _('Purchase orders'),
                                'icon'  => 'clipboard'
                            ),
                            'supplier_part.supplier.deliveries' => array(
                                'label' => _("Supplier's deliveries"),
                                'icon'  => 'truck'
                            ),

                        )

                    ),

                    'supplier_part.images'  => array(
                        'label' => '',
                        'title' => _('Images'),
                        'icon'  => 'camera-retro',
                        'class' => 'right icon_only'
                    ),
                    'supplier_part.history' => array(
                        'label' => '',
                        'title' => _('History/Notes'),
                        'icon'  => 'road',
                        'class' => 'right icon_only'
                    ),


                )
            ),

            'supplier_part.historic' => array(
                'type'           => 'object',
                'subtabs_parent' => array(

                    'supplier_part.purchase_orders.purchase_orders' => 'supplier_part.purchase_orders',
                    'supplier_part.purchase_orders.delivery_notes'  => 'supplier_part.purchase_orders',

                ),


                'tabs' => array(


                    'supplier_part.details' => array(
                        'label' => _(
                            'Data'
                        ),
                        'icon'  => 'database'
                    ),


                    'supplier_part.purchase_orders' => array(
                        'label'   => _(
                            'Purchase Orders'
                        ),
                        'icon'    => 'clipboard',
                        'subtabs' => array(
                            'supplier_part.purchase_orders.purchase_orders' => array(
                                'label' => _(
                                    'Purchase Orders'
                                )
                            ),
                            'supplier_part.purchase_orders.delivery_notes'  => array(
                                'label' => _(
                                    'Delivery Notes'
                                )

                            )

                        ),
                    ),
                    'supplier_part.images'          => array(
                        'label' => '',
                        'title' => _('Images'),
                        'icon'  => 'camera-retro',
                        'class' => 'right icon_only'
                    ),
                    'supplier_part.history'         => array(
                        'label' => '',
                        'title' => _('History/Notes'),
                        'icon'  => 'road',
                        'class' => 'right icon_only'
                    ),


                )
            ),


            'supplier_part.new' => array(
                'type' => 'new_object',
                'tabs' => array(
                    'supplier_part.new' => array(
                        'label' => _('New part')
                    ),

                )

            ),
            'supplier.user.new' => array(
                'type' => 'new_object',
                'tabs' => array(
                    'supplier.user.new' => array(
                        'label' => _(
                            'New system user'
                        )
                    ),

                )

            ),
            'agent.user.new'    => array(
                'type' => 'new_object',
                'tabs' => array(
                    'agent.user.new' => array(
                        'label' => _(
                            'New system user'
                        )
                    ),

                )

            ),


            'timeseries_record' => array(
                'type'     => 'object',
                'label'    => '',
                'showcase' => 'timeseries_record',

                'tabs' => array(
                    'supplier.timeseries_record.parts'    => array(
                        'label' => _('Parts'),
                        'icon'  => 'box',
                        'class' => ''
                    ),
                    'supplier.timeseries_record.families' => array(
                        'label' => _('Families'),
                        'icon'  => 'sitemap',
                        'class' => ''
                    ),


                )


            )

        )
    );
}