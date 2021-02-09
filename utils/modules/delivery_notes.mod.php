<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  27 December 2019  11:07::59  +0800, Kuala Lumpur Malaysia

 Copyright (c) 2019, Inikoo

 Version 3.0
*/

function get_delivery_notes_module() {
    return array(
        'section'     => 'delivery_notes',
        'parent'      => 'store',
        'parent_type' => 'key',
        'sections'    => array(


            'delivery_notes' => array(
                'type'      => 'navigation',
                'label'     => _('All delivery notes'),
                'icon'      => 'truck fa-flip-horizontal',
                'reference' => 'delivery_notes/%d',
                'tabs'      => array(
                    'delivery_notes' => array()
                )
            ),


            'order'         => array(
                'type' => 'object',
                'tabs' => array(


                    'order.items'          => array(
                        'label' => _(
                            'Items'
                        ),
                        'icon'  => 'bars'
                    ),
                    'order.details'        => array(
                        'label' => _(
                            'Data'
                        ),
                        'icon'  => 'database'
                    ),
                    'order.history'        => array(
                        'label' => _(
                            'History'
                        ),
                        'icon'  => 'road'
                    ),
                    'order.delivery_notes' => array(
                        'label' => _(
                            'Delivery notes'
                        ),
                        'icon'  => 'truck'
                    ),
                    'order.invoices'       => array(
                        'label' => _(
                            'Invoices'
                        ),
                        'icon'  => 'file-invoice-dollar'
                    ),

                )

            ),
            'delivery_note' => array(
                'type'  => 'object',
                'title' => _('Delivery note'),
                'tabs'  => array(


                    'delivery_note.items'        => array(
                        'label' => _('SKOs ordered'),
                        'icon'  => 'bars'
                    ),

                    'delivery_note.units'        => array(
                        'label' => _('Units'),
                        'icon'  => 'dot-circle'
                    ),
                    'delivery_note.tariff_codes' => array(
                        'label' => _('Tariff codes'),
                        'icon'  => 'compress-arrows-alt'
                    ),

                    /*
                    'delivery_note.fast_track_packing' => array(
                        'label' => _('Fast track packing'),
                        'icon'  => 'bolt'
                    ),

                    'delivery_note.picking_aid' => array(
                        'label' => _('Picking aid'),
                        'icon'  => 'hand-lizard'
                    ),
*/
                    'delivery_note.history' => array(
                        'label' => _('History'),
                        'icon'  => 'road',
                        'class' => 'right icon_only'


                    ),
                    'delivery_note.details' => array(
                        'label' => _('Data'),
                        'icon'  => 'database',
                        'class' => 'right icon_only'

                    ),

                    /*
                    ,
                    'delivery_note.orders'   => array(
                        'label' => _(
                            'Orders'
                        ),
                        'icon'  => 'shopping-cart'
                    ),
                    'delivery_note.invoices' => array(
                        'label' => _(
                            'Invoices'
                        ),
                        'icon'  => 'file-invoice-dollar'
                    ),

                    */
                )

            ),
            'invoice'       => array(
                'type' => 'object',
                'tabs' => array(


                    'invoice.items' => array(
                        'label' => _(
                            'Items'
                        ),
                        'icon'  => 'bars'
                    ),


                    'delivery_note.picking_aid' => array(
                        'label' => _('Picking aid'),
                        'icon'  => 'fa-hand-lizard'
                    ),


                    'invoice.details'        => array(
                        'label' => _(
                            'Data'
                        ),
                        'icon'  => 'database'
                    ),
                    'invoice.history'        => array(
                        'label' => _(
                            'History'
                        ),
                        'icon'  => 'road'
                    ),
                    'invoice.orders'         => array(
                        'label' => _(
                            'Orders'
                        ),
                        'icon'  => 'shopping-cart'
                    ),
                    'invoice.delivery_notes' => array(
                        'label' => _(
                            'Delivery notes'
                        ),
                        'icon'  => 'truck'
                    ),
                )

            ),

            'pick_aid' => array(
                'type' => 'object',
                'tabs' => array(


                    'pick_aid.items' => array(
                        'label' => _(
                            'Items'
                        ),
                        'icon'  => 'bars'
                    ),
                )

            ),
            'pack_aid' => array(
                'type' => 'object',
                'tabs' => array(


                    'pack_aid.items' => array(
                        'label' => _(
                            'Items'
                        ),
                        'icon'  => 'bars'
                    ),
                )

            ),

        )
    );
}