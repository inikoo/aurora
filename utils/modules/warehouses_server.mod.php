<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  27 December 2019  11:35::08  +0800, Kuala Lumpur Malaysia

 Copyright (c) 2019, Inikoo

 Version 3.0
*/

function get_warehouses_server_module() {
    return array(
        'sections' => array(

            'warehouses' => array(

                'type'      => 'navigation',
                'label'     => _('Warehouses'),
                'title'     => _('Warehouses'),
                'icon'      => 'map-maker',
                'reference' => 'warehouses',
                'tabs'      => array(
                    'warehouses' => array()
                )
            ),

            'returns' => array(
                'type'      => 'navigation',
                'label'     => _('Returns'),
                'icon'      => 'backspace',
                'reference' => 'returns/all',
                'tabs'      => array(
                    'returns_server' => array(
                        'icon'  => 'backspace',
                        'label' => _('Returns'),

                    ),

                    'returns_group_by_warehouse' => array(
                        'label' => _('Group by warehouse'),
                        'icon'  => 'layer-group',

                    )
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