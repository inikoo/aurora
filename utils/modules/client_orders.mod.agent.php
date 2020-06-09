<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  27 December 2019  12:09::12  +0800, Kuala Lumpur Malaysia

 Copyright (c) 2019, Inikoo

 Version 3.0
*/

function get_agent_client_orders_module() {
    return array(
        'sections' => array(
            'orders' => array(
                'type'      => 'navigation',
                'label'     => _("Client's orders"),
                'icon'      => 'shopping-cart',
                'reference' => 'agent_orders',
                'tabs'      => array(
                    'agent.client_orders' => array()
                )
            ),
            'client_order' => array(
                'type' => 'object',
                'tabs' => array(

                    /*
                    'client_order.details' => array(
                        'label' => _('Data'),
                        'icon'  => 'database'
                    ),
                    */
                    'client_order.items' => array(
                        'label' => _(
                            'Items'
                        ),
                        'icon'  => 'bars'
                    ),

                    'client_order.suppliers' => array(
                        'label' => _("Supplier's individual orders"),
                        'icon'  => 'layer-group'
                    ),

                    'client_order.history' => array(
                        'label' => _(
                            'History/Notes'
                        ),
                        'icon'  => 'road',
                        'class' => 'right icon_only'
                    ),

                )

            ),
            'agent_supplier_order' => array(
                'type' => 'object',
                'tabs' => array(

                    /*
                    'client_order.details' => array(
                        'label' => _('Data'),
                        'icon'  => 'database'
                    ),
                    */
                    'agent_supplier_order.items' => array(
                        'label' => _(
                            'Items'
                        ),
                        'icon'  => 'bars'
                    ),


                    'agent_supplier_order.history' => array(
                        'label' => _(
                            'History/Notes'
                        ),
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

        )
    );
}