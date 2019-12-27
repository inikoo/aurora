<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  27 December 2019  11:38::10  +0800, Kuala Lumpur Malaysia

 Copyright (c) 2019, Inikoo

 Version 3.0
*/

function get_inventory_module() {

    include_once 'utils/modules/product.sec.php';

    return array(
        'sections' => array(

            'dashboard' => array(
                'type'      => 'left_button',
                'title'     => _('Inventory dashboard'),
                'label'     => _('Dashboard'),
                'icon'      => 'tachometer',
                'reference' => 'inventory/dashboard',
                'tabs'      => array(
                    'inventory.dashboard' => array('label' => _('Dashboard'))

                )
            ),


            'inventory' => array(

                'type'      => 'navigation',
                'label'     => _('Inventory').' ('._('Parts').')',
                'icon'      => 'boxes',
                'reference' => 'inventory',
                'tabs'      => array(
                    'inventory.in_process_parts'    => array(
                        'label' => _('In process'),
                        'class' => 'discreet'
                    ),
                    'inventory.parts'               => array(
                        'label' => _('Active')
                    ),
                    'inventory.discontinuing_parts' => array(
                        'label' => _(
                            'Discontinuing'
                        ),
                        'class' => 'discreet'
                    ),
                    'inventory.discontinued_parts'  => array(
                        'label' => _(
                            'Discontinued'
                        ),
                        'class' => 'very_discreet'
                    ),

                )
            ),

            'feedback'        => array(
                'type'      => 'navigation',
                'label'     => _('Issues'),
                'icon'      => 'poop',
                'reference' => 'inventory/feedback',
                'tabs'      => array(


                    'inventory.feedback'                 => array(
                        'label' => _('Issues'),
                        'icon'  => 'poop'
                    ),
                    'inventory.feedback_per_part'        => array(
                        'label' => _('Issues group by part'),
                    ),
                    'inventory.feedback_per_part_family' => array(
                        'label' => _('Issues group by family'),
                    ),


                )
            ),
            'barcodes'        => array(
                'type'      => 'navigation',
                'label'     => _('Retail barcodes'),
                'icon'      => 'barcode',
                'reference' => 'inventory/barcodes',
                'tabs'      => array(
                    'inventory.barcodes' => array('label' => _('Barcodes'))

                )
            ),
            'barcode'         => array(
                'type'  => 'object',
                'title' => _('Barcode'),
                'tabs'  => array(
                    'barcode.details' => array(
                        'label' => _('Data'),
                        'icon'  => 'database'
                    ),
                    'barcode.history' => array(
                        'label' => _('History'),
                        'icon'  => 'road'
                    ),

                    'barcode.assets' => array(
                        'label' => _('Products/Parts'),
                        'icon'  => 'cube'
                    ),

                )
            ),
            'deleted_barcode' => array(
                'type'  => 'object',
                'title' => _('Deleted barcode'),
                'tabs'  => array(
                    'barcode.history' => array(
                        'label' => _('History'),
                        'icon'  => 'road'
                    ),


                )
            ),
            'categories'      => array(
                'type'      => 'navigation',
                'label'     => _("Part's families"),
                'icon'      => 'sitemap',
                'reference' => 'inventory/categories',

                'tabs' => array(
                    'part_families' => array(
                        'label' => _(
                            "Part's families"
                        )
                    ),
                )
            ),

            'category' => array(
                'type'           => 'object',
                'title'          => _('Category (Part)'),
                'subtabs_parent' => array(

                    'category.part.sales.plot'     => 'category.part.sales',
                    'category.part.sales.history'  => 'category.part.sales',
                    'category.part.sales.calendar' => 'category.part.sales',
                    'category.part.sales.info'     => 'category.part.sales'

                ),
                'tabs'           => array(

                    'category.details' => array(
                        'label' => _('Data'),
                        'icon'  => 'database'
                    ),

                    'category.part.sales'    => array(
                        'label'   => _('Sales'),
                        'icon'    => 'money-bill-alt',
                        'subtabs' => array(
                            'category.part.sales.plot'     => array(
                                'label' => _(
                                    'Plot'
                                )
                            ),
                            'category.part.sales.history'  => array(
                                'label' => _(
                                    'Sales history'
                                )
                            ),
                            'category.part.sales.calendar' => array(
                                'label' => _(
                                    'Calendar'
                                )
                            ),
                            'category.part.sales.info'     => array(
                                'label'   => '',
                                'title'   => _('Sales data info'),
                                'icon_v2' => 'fal fa-fw fa-chess-clock',
                                'class'   => 'right icon_only'
                            ),

                        )

                    ),
                    'part_category.feedback' => array(
                        'label' => _('Issues'),
                        'icon'  => 'poop',
                    ),
                    'category.subjects'      => array(
                        'label'         => _('Parts'),
                        'quantity_data' => array(
                            'object' => '_object',
                            'field'  => 'Parts'
                        ),
                    ),


                    'category.part.discontinued_subjects' => array(
                        'label'         => _('Discontinued parts'),
                        'quantity_data' => array(
                            'object' => '_object',
                            'field'  => 'Discontinued'
                        ),
                    ),


                    'category.categories'          => array(
                        'label' => _(
                            'Subcategories'
                        )
                    ),
                    'part_family.part_locations'   => array(
                        'label' => _('Parts locations')
                    ),
                    'part_family.product_families' => array(
                        'label' => _('Product families'),
                        'icon'  => 'cubes'
                    ),
                    'part_family.products'         => array(
                        'label' => _('Products'),
                        'icon'  => 'cube'
                    ),

                    'category.images'  => array(
                        'title'         => _('Images'),
                        'label'         => '',
                        'quantity_data' => array(
                            'object' => '_object',
                            'field'  => 'Number Images'
                        ),
                        'icon'          => 'camera-retro',
                        'class'         => 'right icon_only'
                    ),
                    'category.history' => array(
                        'title'         => _('History/Notes'),
                        'label'         => '',
                        'quantity_data' => array(
                            'object' => '_object',
                            'field'  => 'Number History Records'
                        ),


                        'icon'  => 'road',
                        'class' => 'right icon_only'
                    )


                )

            ),


            'part' => array(
                'type'           => 'object',
                'title'          => _('Part'),
                'subtabs_parent' => array(
                    'part.sales.overview'      => 'part.sales',
                    'part.sales.history'       => 'part.sales',
                    'part.sales.products'      => 'part.sales',
                    'part.stock.overview'      => 'part.stock',
                    'part.stock.transactions'  => 'part.stock',
                    'part.stock.history'       => 'part.stock',
                    'part.stock.availability'  => 'part.stock',
                    'part.supplier.orders'     => 'part.supplier_orders',
                    'part.supplier.deliveries' => 'part.supplier_orders',
                    'part.stock.cost'          => 'part.stock',
                    'part.stock.history.plot'  => 'part.stock',
                    'part.sales.plot'          => 'part.sales',
                    'part.sales.calendar'      => 'part.sales',
                    'part.sales.info'          => 'part.sales',

                ),


                'tabs' => array(


                    'part.details' => array(
                        'label' => _('Data'),
                        'icon'  => 'database'
                    ),


                    'part.sales' => array(
                        'label'   => _('Sales'),
                        'icon'    => 'money-bill-alt',
                        'subtabs' => array(
                            'part.sales.plot'     => array(
                                'label' => _(
                                    'Plot'
                                )
                            ),
                            'part.sales.history'  => array(
                                'label' => _(
                                    'Sales history'
                                )
                            ),
                            'part.sales.calendar' => array(
                                'label' => _(
                                    'Calendar'
                                )
                            ),
                            'part.sales.info'     => array(
                                'label'   => '',
                                'title'   => _('Sales data info'),
                                'icon_v2' => 'fal fa-fw fa-chess-clock',
                                'class'   => 'right icon_only'
                            ),

                        )

                    ),

                    'part.stock'    => array(
                        'label'   => _('Stock History'),
                        'icon'    => 'scanner',
                        'subtabs' => array(
                            'part.stock.history'      => array(
                                'label' => _(
                                    'Stock history'
                                ),
                                'icon'  => 'bars'
                            ),
                            'part.stock.history.plot' => array(
                                'label' => _(
                                    'Stock history chart'
                                ),
                                'icon'  => 'area-chart'
                            ),
                            'part.stock.transactions' => array(
                                'label' => _('Stock movements'),
                                'icon'  => 'exchange',
                            ),
                            'part.stock.cost'         => array(
                                'label' => _('Stock cost'),
                                'icon'  => 'fa-dollar-sign',
                            ),
                        )


                    ),
                    'part.feedback' => array(
                        'label' => _('Issues'),
                        'icon'  => 'poop',
                    ),

                    'part.supplier_orders' => array(
                        'label'   => _(
                            'Purchase orders'
                        ),
                        'icon'    => 'clipboard',
                        'subtabs' => array(

                            'part.supplier.orders'     => array(
                                'label' => _(
                                    'Purchase orders'
                                ),
                                'icon'  => 'clipboard'
                            ),
                            'part.supplier.deliveries' => array(
                                'label' => _("Supplier's deliveries"),
                                'icon'  => 'truck'
                            ),

                        )

                    ),


                    'part.supplier_parts' => array(
                        'label'         => _(
                            "Supplier's products"
                        ),
                        'quantity_data' => array(
                            'object' => '_object',
                            'field'  => 'Number Supplier Parts'
                        ),
                        'icon'          => 'hand-receiving'
                    ),

                    'part.products' => array(
                        'label'         => _('Products'),
                        'quantity_data' => array(
                            'object' => '_object',
                            'field'  => 'Products Numbers'
                        ),
                        'icon'          => 'cube'
                    ),


                    'part.history'     => array(
                        'title'         => _('History/Notes'),
                        'label'         => '',
                        'quantity_data' => array(
                            'object' => '_object',
                            'field'  => 'Number History Records'
                        ),
                        'icon'          => 'road',
                        'class'         => 'right icon_only'
                    ),
                    'part.images'      => array(
                        'title'         => _('Images'),
                        'label'         => '',
                        'quantity_data' => array(
                            'object' => '_object',
                            'field'  => 'Number Images'
                        ),
                        'icon'          => 'camera-retro',
                        'class'         => 'right icon_only'
                    ),
                    'part.attachments' => array(
                        'title'         => _('Attachments'),
                        'label'         => '',
                        'quantity_data' => array(
                            'object' => '_object',
                            'field'  => 'Number Attachments'
                        ),
                        'icon'          => 'paperclip',
                        'class'         => 'right icon_only'
                    ),
                    'part.locations'   => array(
                        'title' => _('Locations'),
                        'label' => '',
                        'icon'  => 'inventory',
                        'class' => 'right icon_only'
                    ),


                )
            ),

            'part.new'   => array(
                'type' => 'new_object',
                'tabs' => array(
                    'part.new' => array(
                        'label' => _(
                            'new part'
                        )
                    ),

                )

            ),
            'part.image' => array(
                'type' => 'object',


                'tabs' => array(


                    'part.image.details' => array(
                        'label' => _(
                            'Data'
                        ),
                        'icon'  => 'database'
                    ),
                    'part.image.history' => array(
                        'label' => _(
                            'History/Notes'
                        ),
                        'icon'  => 'road'
                    ),

                )
            ),

            'part.attachment.new' => array(
                'type' => 'new_object',
                'tabs' => array(
                    'part.attachment.new' => array(
                        'label' => _(
                            'new attachment'
                        )
                    ),

                )

            ),
            'part.attachment'     => array(
                'type' => 'object',
                'tabs' => array(
                    'part.attachment.details' => array(
                        'label' => _(
                            'Data'
                        ),
                        'icon'  => 'database'
                    ),
                    'part.attachment.history' => array(
                        'label' => _(
                            'History'
                        ),
                        'icon'  => 'clock'
                    ),

                )

            ),
            /*
            'transactions'=>array(
                'type'=>'navigation', 'label'=>_('Stock Movements'), 'icon'=>'exchange', 'reference'=>'inventory/transactions',
                'tabs'=>array(
                    'inventory.stock.transactions'=>array('label'=>_('Stock movements'))

                )
            ),
            */

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

            'supplier_part.new' => array(
                'type' => 'new_object',
                'tabs' => array(
                    'part.supplier_part.new' => array(
                        'label' => _("New supplier's product")
                    ),

                )

            ),
            'stock_history'     => array(
                'type'      => 'navigation',
                'label'     => _('Stock History'),
                'icon'      => 'scanner',
                'reference' => 'inventory/stock_history',
                'tabs'      => array(
                    'inventory.stock.history' => array(
                        'label' => _(
                            'Stock history'
                        )
                    ),

                    'inventory.stock.history.plot' => array(
                        'label' => _(
                            'Chart'
                        ),
                        'class' => 'right'
                    ),


                )
            ),
            'stock_history.day' => array(
                'type' => '',
                'tabs' => array(
                    'inventory.stock.history.day' => array('label' => ''),


                )
            ),


            'product' => get_product_section(),

            'product.new' => array(
                'type' => 'new_object',
                'tabs' => array(
                    'product.new' => array(
                        'label' => _(
                            'New product'
                        )
                    ),

                )

            ),


        )
    );
}