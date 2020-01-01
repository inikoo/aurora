<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:    31 December 2020  14:31::53  +0800, Kuala Lumpur Malaysia

 Copyright (c) 2019, Inikoo

 Version 3.0
*/

function get_offers_module() {


    return array(
        'section'  => 'offers',
        'sections' => array(


            'campaigns' => array(
                'type'  => 'navigation',
                'label' => _('Categories'),
                'title' => _("Offer's categories"),

                'icon'      => 'sitemap',
                'reference' => 'offers/%d/categories',
                'tabs'      => array(
                    'campaigns' => array(
                        'label' => _("Offer's categories"),
                        'icon'  => 'tags',
                    ),


                )

            ),

            'offers' => array(
                'type'  => 'navigation',
                'label' => _('Offers'),

                'icon'      => 'tags',
                'reference' => 'offers/%d',
                'tabs'      => array(

                    'deals'     => array(
                        'label' => _('Offers'),
                        'icon'  => 'tag'
                    ),

                )

            ),





            'vouchers' => array(
                'type'  => 'object',
                'title' => _("Vouchers"),

                'tabs' => array(


                    'campaign.deals'     => array(
                        'label' => _('Vouchers'),
                        'icon'  => 'money-bill-wave',

                    ),
                    'campaign.orders'    => array(
                        'label'         => _('Orders'),
                        'icon'          => 'shopping-cart',
                        'quantity_data' => array(
                            'object' => '_object',
                            'field'  => 'Used Orders'
                        ),
                    ),
                    'campaign.customers' => array(
                        'label'         => _('Customers'),
                        'icon'          => 'users',
                        'quantity_data' => array(
                            'object' => '_object',
                            'field'  => 'Used Customers'
                        ),
                    ),
                    'campaign.history'   => array(
                        'title'         => _('History, notes'),
                        'label'         => '',
                        'icon'          => 'road',
                        'class'         => 'right icon_only',
                        'quantity_data' => array(
                            'object' => '_object',
                            'field'  => 'Number History Records'
                        ),
                    ),
                    'campaign.details'   => array(
                        'class' => 'right icon_only',
                        'label' => '',
                        'icon'  => 'sliders-h',
                        'title' => _('Settings')
                    ),

                )
            ),


            'campaign' => array(
                'type'  => 'object',
                'title' => _("Offer category"),

                'tabs' => array(


                    'campaign.deals'     => array(
                        'label'         => _('Offers'),
                        'icon'          => 'tags',
                        'quantity_data' => array(
                            'object' => '_object',
                            'field'  => 'Number Current Deals'
                        ),
                    ),
                    'campaign.orders'    => array(
                        'label'         => _('Orders'),
                        'icon'          => 'shopping-cart',
                        'quantity_data' => array(
                            'object' => '_object',
                            'field'  => 'Used Orders'
                        ),
                    ),
                    'campaign.customers' => array(
                        'label'         => _('Customers'),
                        'icon'          => 'users',
                        'quantity_data' => array(
                            'object' => '_object',
                            'field'  => 'Used Customers'
                        ),
                    ),
                    'campaign.history'   => array(
                        'title'         => _('History, notes'),
                        'label'         => '',
                        'icon'          => 'road',
                        'class'         => 'right icon_only',
                        'quantity_data' => array(
                            'object' => '_object',
                            'field'  => 'Number History Records'
                        ),
                    ),
                    'campaign.details'   => array(
                        'class' => 'right icon_only',
                        'label' => '',
                        'icon'  => 'sliders-h',
                        'title' => _('Settings')
                    ),

                )
            ),


            'campaign_order_recursion' => array(
                'type'  => 'object',
                'title' => _("Reorder incentive"),

                'tabs' => array(

                    'campaign_order_recursion.components' => array(
                        'label'         => _('Offers'),
                        'icon'          => 'tags',
                        'quantity_data' => array(
                            'object' => '_object',
                            'field'  => 'Number Deal Components'
                        ),
                    ),
                    'campaign_order_recursion.reminders'  => array(
                        'label' => _('Reminders'),
                        'icon'  => 'envelope'

                    ),
                    'campaign.orders'                     => array(
                        'label'         => _('Orders'),
                        'icon'          => 'shopping-cart',
                        'quantity_data' => array(
                            'object' => '_object',
                            'field'  => 'Used Orders'
                        ),
                    ),
                    'campaign.customers'                  => array(
                        'label'         => _('Customers'),
                        'icon'          => 'users',
                        'quantity_data' => array(
                            'object' => '_object',
                            'field'  => 'Used Customers'
                        ),
                    ),
                    'campaign.history'                    => array(
                        'title'         => _('History, notes'),
                        'label'         => '',
                        'icon'          => 'road',
                        'class'         => 'right icon_only',
                        'quantity_data' => array(
                            'object' => '_object',
                            'field'  => 'Number History Records'
                        ),
                    ),

                    'campaign.details' => array(
                        'class' => 'right icon_only',
                        'label' => '',
                        'icon'  => 'sliders-h',
                        'title' => _('Settings')
                    ),

                )
            ),


            'deal' => array(
                'type'  => 'object',
                'title' => _("Offer"),

                'tabs' => array(
                    'deal.details' => array(
                        'label' => _('Data'),
                        'icon'  => 'database',
                        'title' => _('Details')
                    ),

                    'deal.components' => array(
                        'label'         => _('Allowances'),
                        'icon'          => '',
                        'quantity_data' => array(
                            'object' => '_object',
                            'field'  => 'Number Active Components'
                        ),


                    ),
                    'deal.orders'     => array(
                        'label' => _('Orders'),
                        'icon'  => 'shopping-cart',

                        'quantity_data' => array(
                            'object' => '_object',
                            'field'  => 'Used Orders'
                        ),
                    ),
                    'deal.customers'  => array(
                        'label'         => _('Customers'),
                        'icon'          => 'users',
                        'quantity_data' => array(
                            'object' => '_object',
                            'field'  => 'Used Customers'
                        ),
                    ),
                    'deal.history'    => array(
                        'title'         => _('History/Notes'),
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


            'deal_component' => array(
                'type'  => 'object',
                'title' => _("Offer"),

                'tabs' => array(
                    'deal_component.details' => array(
                        'label' => _('Settings'),
                        'icon'  => 'sliders-h',
                    ),


                    'deal_component.orders'    => array(
                        'label' => _('Orders'),
                        'icon'  => 'shopping-cart',

                        'quantity_data' => array(
                            'object' => '_object',
                            'field'  => 'Used Orders'
                        ),
                    ),
                    'deal_component.customers' => array(
                        'label'         => _('Customers'),
                        'icon'          => 'users',
                        'quantity_data' => array(
                            'object' => '_object',
                            'field'  => 'Used Customers'
                        ),
                    ),
                    'deal_component.history'   => array(
                        'title'         => _('History/Notes'),
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

            'campaign.new' => array(
                'type' => 'new_object',
                'tabs' => array(
                    'campaign.new' => array(
                        'label' => _(
                            'New campaign'
                        )
                    ),

                )

            ),

            'deal.new'           => array(
                'type'  => 'new_object',
                'title' => _("New offer"),
                'tabs'  => array(
                    'deal.new' => array(
                        'label' => _(
                            'New offer'
                        )
                    ),

                )

            ),
            'deal_component.new' => array(
                'type'  => 'new_object',
                'title' => _("New offer"),
                'tabs'  => array(
                    'deal_component.new' => array(
                        'label' => _(
                            'New offer'
                        )
                    ),

                )

            ),





        )
    );
}