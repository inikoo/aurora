<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  27 December 2019  12:08::43  +0800, Kuala Lumpur Malaysia

 Copyright (c) 2019, Inikoo

 Version 3.0
*/

function get_agent_suppliers_module() {
    return array(


        'sections' => array(


            'suppliers' => array(

                'type'      => 'navigation',
                'label'     => _('Suppliers'),
                'icon'      => 'hand-holding-box',
                'reference' => 'suppliers',
                'tabs'      => array('agent.suppliers' => array())


            ),


            'order_to_delete' => array(
                'type' => 'object',
                'tabs' => array(
                    'supplier.order.items'   => array(
                        'label' => _(
                            'Items'
                        ),
                        'icon'  => 'bars'
                    ),
                    'supplier.order.details' => array(
                        'label' => _(
                            'Data'
                        ),
                        'icon'  => 'database'
                    ),


                    'supplier.order.tac.editor' => array(
                        'label' => _(
                            'Terms and conditions'
                        ),
                        'icon'  => 'gavel',
                        'class' => ''
                    ),
                    'supplier.order.history'    => array(
                        'label' => _(
                            'History/Notes'
                        ),
                        'icon'  => 'road',
                        'class' => 'right icon_only'
                    ),

                )

            ),

            'deleted_order_to_delete' => array(
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

            'deliveryto_delete' => array(
                'type' => 'object',
                'tabs' => array(

                    'supplier.delivery.details' => array(
                        'label' => _(
                            'Data'
                        ),
                        'icon'  => 'database'
                    ),

                    'supplier.delivery.items'   => array(
                        'label' => _(
                            'Items'
                        ),
                        'icon'  => 'bars'
                    ),
                    'supplier.delivery.history' => array(
                        'label' => _(
                            'History/Notes'
                        ),
                        'icon'  => 'road',
                        'class' => 'right icon_only'
                    ),

                )

            ),

            'supplier'                => array(
                'type'      => 'object',
                'label'     => _('Supplier'),
                'icon'      => 'hand-holding-box',
                'reference' => 'supplier/%d',
                'tabs'      => array(
                    'supplier.details'        => array(
                        'label' => _('Data'),
                        'icon'  => 'database',
                        'title' => _('Details')
                    ),
                    'supplier.supplier_parts' => array(
                        'label' => _("Supplier's products"),
                        'icon'  => 'hand-receiving'
                    ),

                    'supplier.orders' => array(
                        'label' => _('Purchase orders'),
                        'icon'  => 'clipboard'
                    ),

                    'supplier.history' => array(
                        'label' => _('History/Notes'),
                        'icon'  => 'road',
                        'class' => 'right icon_only'
                    ),

                    'supplier.attachments' => array(
                        'label'         => _('Attachments'),
                        'icon'          => 'paperclip',
                        'class'         => 'right icon_only',
                        'quantity_data' => array(
                            'object' => '_object',
                            'field'  => 'Number Attachments'
                        ),
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
                    'supplier_part.purchase_orders.invoices'        => 'supplier_part.purchase_orders',
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
                                'label' => _('Purchase Orders')
                            ),
                            'supplier_part.purchase_orders.delivery_notes'  => array(
                                'label' => _('Delivery Notes')
                            ),
                            'supplier_part.purchase_orders.invoices'        => array(
                                'label' => _('Invoices')
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

                    'agent_supplier_part.details' => array(
                        'label' => ('Properties'),
                        'title' => _('Product properties'),
                        'icon'  => 'sliders-h',
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
                    'supplier_part.purchase_orders.invoices'        => 'supplier_part.purchase_orders',
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
                            ),
                            'supplier_part.purchase_orders.invoices'        => array(
                                'label' => _(
                                    'Invoices'
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

            'supplier_part.new' => array(
                'type' => 'new_object',
                'tabs' => array(
                    'supplier_part.new' => array(
                        'label' => _(
                            'New part'
                        )
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

            /*
            'settings'=>array(
                'type'=>'navigation', 'label'=>'', 'icon'=>'sliders', 'reference'=>'suppliers/settings', 'class'=>'icon_only',
                'tabs'=>array(
                    'suppliers.settings'=>array('label'=>_('Setting'), 'icon'=>'sliders', 'class'=>''),


                )


            ),
            */
        )
    );
}