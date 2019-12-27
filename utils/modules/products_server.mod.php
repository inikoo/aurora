<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  27 December 2019  11:12::02  +0800, Kuala Lumpur Malaysia

 Copyright (c) 2019, Inikoo

 Version 3.0
*/

function get_products_server_module() {
    return array(

        'parent'      => 'none',
        'parent_type' => 'none',

        'sections' => array(
            'stores'    => array(
                'type'           => 'navigation',
                'label'          => _('Stores'),
                'icon'           => 'shopping-basket',
                'reference'      => 'stores',
                'showcase'       => 'account',
                'subtabs_parent' => array(
                    'stores.sales.plot'     => 'stores.sales',
                    'stores.sales.history'  => 'stores.sales',
                    'stores.sales.calendar' => 'stores.sales',
                    'stores.sales.info'     => 'stores.sales'

                ),
                'tabs'           => array(
                    'stores'       => array('label' => _('Stores')),
                    'stores.sales' => array(
                        'label'   => _('Sales'),
                        'icon'    => 'money-bill-alt',
                        'subtabs' => array(
                            'stores.sales.plot'     => array(
                                'label' => _(
                                    'Plot'
                                )
                            ),
                            'stores.sales.history'  => array(
                                'label' => _(
                                    'Sales history'
                                )
                            ),
                            'stores.sales.calendar' => array(
                                'label' => _(
                                    'Calendar'
                                )
                            ),
                            'stores.sales.info'     => array(
                                'label'   => '',
                                'title'   => _('Sales data info'),
                                'icon_v2' => 'fal fa-fw fa-chess-clock',
                                'class'   => 'right icon_only'
                            ),

                        )

                    ),
                )
            ),
            'products'  => array(
                'type'      => 'navigation',
                'label'     => _('Products'),
                'icon'      => 'cube',
                'reference' => 'products/all',
                'tabs'      => array(


                    'products' => array(
                        'label' => _('Products'),
                        'icon'  => 'cube'
                    ),

                )
            ),
            'store.new' => array(
                'type'  => 'new_object',
                'title' => _('New store'),
                'tabs'  => array(
                    'store.new' => array(
                        'label' => _(
                            'New store'
                        )
                    ),

                )

            ),


        )
    );
}